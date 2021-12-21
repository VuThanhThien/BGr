<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Tracking;
use App\Models\Shop;
use App\Models\Conversion;
use App\Models\ShopSetting;
use App\Models\AffiliateCoupon;
use App\Models\ProductCommission;
use App\Events\NewConversionEvent;

class WebhookOrderShopifyService
{
    public function handleHook($data)
    {
        try{
            $checkExist = Conversion::where('order_id', $data['id'])->first();
            if($checkExist ||  $data['source_name'] == 'shopify_draft_order') {
                return;
            }
            dispatch(new \App\Jobs\HandleTrackingOrder($data))->delay(now()->addSeconds(10))->onQueue('track_order');
           
        }catch(\Exception $e){
            report($e);
            return true;
        }
        
    }

    public function handleTrackingConversion($data)
    {
        $shopId = $data['shop_id'];
        $trackingType = 0;
        $affiliate = $this->findAffiliate($data, $shopId, $trackingType);
        if( $affiliate && $affiliate->group->is_active ) {
            $commissionExplanation = $this->calCommission($data, $affiliate->group);
            $newConversion = $this->storeConversion($affiliate, $affiliate->group, $data, $commissionExplanation, $trackingType);
            $this->storeConversionNetwork($affiliate, $newConversion);
            event(new NewConversionEvent($affiliate, $newConversion));
        }
    }

    public function storeConversion($affiliate, $group, $dataOrder, $commissionExplanation, $trackingType)
    {
        $conversion = new Conversion;
        $conversion->shop_id = $affiliate->shop_id;
        $conversion->group_id = $group->id;
        $conversion->affiliate_id = $affiliate->id;
        $conversion->order_id = $dataOrder['id'];
        $conversion->order_name = $dataOrder['name'];
        $conversion->customer_id = $dataOrder['customer']['id'];
        $conversion->customer_info = $this->getCustomerInfo($dataOrder['customer']);
        $conversion->commission_type = $group->commission_type;
        $conversion->commission_amount = $group->commission_amount;
        $conversion->quantity = countItemsInOrder($dataOrder['line_items']);
        $conversion->total = $dataOrder['total_price'];
        $conversion->subtotal = $dataOrder['subtotal_price'];
        $conversion->commission = $commissionExplanation['total_commission'];
        $conversion->tracking_type = $trackingType;
        $conversion->commission_explanation = $commissionExplanation;
        $conversion->status = $group->auto_approve_order ? 1 : 2;
        
        $conversion->save();

        return $conversion;

    }

    private function getCustomerInfo($customer)
    {
        return [
            'id' => $customer['id'],
            'first_name' => $customer['first_name'],
            'last_name' => $customer['last_name'],
            'email' => $customer['email'],
            'phone' => $customer['phone'],
            'address' => $customer['default_address']
        ];
    }

    private function findAffiliate($data, $shopId, &$trackingType)
    {
        $affiliate = $this->findAffiliateByCoupon($data['discount_codes'], $shopId);
        if($affiliate) {
            $trackingType = config('myconfig.constants.tracking_type.coupon');
            return $affiliate;
        }
        $affiliate = $this->findAffiliateByToken($data['cart_token'], $data['checkout_token'], $shopId);
        if($affiliate){
            $trackingType = config('myconfig.constants.tracking_type.link');
            return $affiliate;
        }

    }

    private function findAffiliateByToken($cartToken, $checkoutToken, $shopId)
    {
        $affiliate = null;
        $shopSetting = ShopSetting::where('shop_id', $shopId)->first();
        $sort = $shopSetting->aff_conflict_resolution ? 'asc' : 'desc';
        $orderTracking = Tracking::where('cart_token', $cartToken)
            ->whereNotNull('cart_token')
            ->orwhere(function($query) use ($checkoutToken){
                $query->where('checkout_token', $checkoutToken);
                $query->whereNotNull('checkout_token');

            })
            ->orderBy('id', $sort)
            ->first();

        if( $orderTracking ) {
            $affiliate = Affiliate::where('id', $orderTracking->affiliate_id)
                ->where('status',1)
                ->with('group')
                ->select('id', 'first_name', 'last_name', 'email', 'group_id', 'shop_id', 'parent_id')
                ->first();
        }
        return $affiliate;
    }

    private function findAffiliateByCoupon(Array $coupons, $shopId)
    {
        $affiliate = null;

        $couponcodes = $this->getArrayCouponsFromOrder($coupons);

        $couponInDatabase = AffiliateCoupon::where('shop_id', $shopId)->whereIn('code', $couponcodes)->first();
        if ($couponInDatabase) {
            $affiliate = Affiliate::where('id', $couponInDatabase->affiliate_id)
                ->where('shop_id', $shopId)
                ->where('status', 1)
                ->with('group')
                ->select('id', 'first_name', 'last_name', 'email', 'group_id', 'shop_id', 'parent_id')
                ->first();
        }
        return $affiliate;
    }

    private function getArrayCouponsFromOrder($discountCodes): array
    {
        $couponArr = [];
        foreach ($discountCodes as $c) {
            $couponArr[] = $c['code'];
        }

        return $couponArr;
    }

    public function calCommission($dataOrder, $group): array
    {
        $totalCommission = 0;
        $commissionExplanation = [
            'total_commission' => $totalCommission,
            'taxes_included' => $dataOrder['taxes_included'],
            'exclude_tax' => $group->exclude_tax,
            'exclude_shipping' => $group->exclude_shipping,
            'include_discounts' => $group->include_discounts,
            'line_items' => [],
            'total_shipping' => $this->getTotalShippingPrice( $dataOrder['shipping_lines'] ),
            'total_discounts' => $dataOrder['total_discounts'],
            'total_tax' => $dataOrder['total_tax'],
            'commission_type' => $group->commission_type,
            'commission_amount' => $group->commission_amount

        ];

        foreach ($dataOrder['line_items'] as $item){
            $productCommission = $this->isProductCommission($item['variant_id'], $group->id);
            $itemPrice = $item['price'];
            $totalTaxItem = $this->getTotalTaxOfItem($item['tax_lines']);
            $totalDiscountItem = $this->getTotalDiscountOfItem($item['discount_allocations']);
            
            if( !$productCommission ) {
                $commissionType = $group->commission_type;
                $commissionAmount = $group->commission_amount;
            }else{
                $commissionType = $productCommission->commission_type;
                $commissionAmount = $productCommission->commission_amount;
            }

            if( $group->commission_type == Group::FLAT_RATE_PER_ORDER && !$productCommission) {
                $itemCommission = 0;
            }else{
                $itemCommission = $this->calCommissionByItem($commissionType, $commissionAmount, $itemPrice, $item['quantity']);
            }
            
            $taxCommission = $this->calCommissionTax($totalTaxItem, $commissionType, $commissionAmount);
            $discountCommission = $this->calCommissionDiscount($totalDiscountItem, $commissionType, $commissionAmount);
            
            //tính tổng commission
            $totalCommission += $itemCommission;
            if($group->exclude_tax) {
                if($dataOrder['taxes_included']){
                    $totalCommission = $totalCommission - $taxCommission;
                }
            }else{
                if(!$dataOrder['taxes_included']){
                    $totalCommission = $totalCommission + $taxCommission;
                }
            }
            if($group->include_discounts) {
                $totalCommission = $totalCommission - $discountCommission;
            }

            // $totalCommission += $itemCommission;

            $commissionExplanation['line_items'][] = [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'name' => $item['name'],
                'sku' => $item['sku'],
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'],
                'commission' => $itemCommission,
                'is_product_commission' => $productCommission ? 1 : 0,
                'commission_type' => $commissionType,
                'commission_amount' => $commissionAmount,
                'total_discount' => $this->getTotalDiscountOfItem($item['discount_allocations']),
                'total_tax' => $this->getTotalTaxOfItem($item['tax_lines']),
                'tax_commission' => $taxCommission,
                'discount_commission' => $discountCommission

            ];
        }

        if( $group->commission_type == config('myconfig.constants.commission_type.percent_of_sale') ) {

            if(!$group->exclude_shipping){
                $shippingCommission = $dataOrder['total_shipping_price_set']['shop_money']['amount'] * $group->commission_amount / 100;
                $totalCommission += $shippingCommission;
                $commissionExplanation['shipping_commission'] = $shippingCommission;
            }
            if($group->exclude_tax){
                $shippingTaxCommission = $this->getShippingTax($dataOrder['shipping_lines']) * $group->commission_amount / 100;
                $totalCommission = $totalCommission - $shippingTaxCommission;
                $commissionExplanation['shipping_tax_commission'] = $shippingTaxCommission;
            }

        }

        if($group->commission_type == Group::FLAT_RATE_PER_ORDER) {
            $totalCommission += $group->commission_amount;
            $commissionExplanation['order_commission'] = $group->commission_amount;
        }

        $commissionExplanation['total_commission'] = $totalCommission;
        return $commissionExplanation;
    }

    private function calCommissionByItem($commissionType, $commissionAmount, $price, $quantity): float|int
    {
        if( $commissionType == config('myconfig.constants.commission_type.percent_of_sale') ) {
            $commission = $price * $quantity * $commissionAmount/100;
        }else if( $commissionType == config('myconfig.constants.commission_type.flat_rate_order') ){
            $commission = $commissionAmount;
        }else{
            $commission = $commissionAmount * $quantity;
        }
        return $commission;
    }
    private function calCommissionTax($totalTaxItem, $commissionType, $commissionAmount) 
    {
        $commission = 0;
        if($commissionType == 1) {
            $commission = $totalTaxItem * $commissionAmount / 100;
        }

        return $commission;
    }
    private function calCommissionDiscount($totalDiscountItem , $commissionType, $commissionAmount)
    {
        $commission = 0;
        if($commissionType == 1) {
            $commission = $totalDiscountItem * $commissionAmount / 100;
        }

        return $commission;
    }

    private function isProductCommission($variantId, $groupId)
    {
        return ProductCommission::where('variant_id', $variantId)->where('group_id', $groupId)->first();
    }

    private function calTotal($subtotal,$totalShipping, $totalTax, $excludeShipping, $excludeTax, $includeVat )
    {
        $total = $subtotal;
        if(!$excludeShipping) {
            $total = $total + $totalShipping;
        }

        if($excludeTax) {
            if( $includeVat ) {
                $subtotal = $subtotal - $totalTax;
            }
        }else{
            if( !$includeVat ) {
                $subtotal = $subtotal + $totalTax;
            }
        }
        return $total;
    }

    private function getTotalTaxOfItem($taxLines)
    {
        $totalTax = 0;
        foreach ($taxLines as $t) {
            $totalTax += $t['price'];
        }
        return $totalTax;
    }

    private function getTotalDiscount($discounts)
    {
        $total = 0;
        foreach ($discounts as $discount) {
            $total += $discount['amount'];
        }

        return $total;
    }

    private function getTotalDiscountOfItem($discountAllocations)
    {
        $total = 0;
        foreach ($discountAllocations as $d) {
            $total += $d['amount'];
        }
        return $total;
    }

    private function getShippingTax($shippingLines)
    {
        $total = 0;
        foreach ($shippingLines as $s) {
            foreach ($s['tax_lines'] as $taxLine) {
                $total += $taxLine['price'];
            }
        }
        return $total;
    }

    private function getTotalShippingPrice($shippingLines)
    {
        $total = 0;
        foreach ($shippingLines as $s) {
            $total += $s['price'];
        }
        return $total;
    }

    private function calCommissionNetwork($referral, $level, $calculateType) 
    {

        if($level['commission_type'] == 1 ) {
            if($calculateType == 1) {
                return $referral->total*$level['commission_amount']/100;
            }else{
                return $referral->commission*$level['commission_amount']/100;
            }
            
        }
        return $level['commission_amount'];

    }


    public function storeConversionNetwork($affiliate, $conversion)
    {
        $shopId = $affiliate->shop_id;
        
        $currentAffiliate = $affiliate;
        for($i = 1; $i <= 20; $i++) {
            
            if( $currentAffiliate &&  $currentAffiliate->parent_id != 0 ) {
                $parent = Affiliate::where('id',$currentAffiliate->parent_id)->where('status', 1)->first();
                
                if( !$parent ){
                    return '';
                }
                
                $group = Group::where('id', $parent->group_id)->select('is_active', 'id','mlm_tree','is_enable_mlm','mlm_commission_on')->first();
                
                if(!$group || !$group->is_active || !$group->is_enable_mlm || !isset($group->mlm_tree[$i-1])) {
                    $currentAffiliate = $parent;
                    continue;

                }
                
                $levels = $group->mlm_tree;
                $calculateType = $group->mlm_commission_on;

                $commission = $this->calCommissionNetwork( $conversion, $levels[$i-1], $calculateType );
                $newConversion = new Conversion;
                $newConversion->shop_id = $shopId;
                $newConversion->order_id = $conversion->order_id;
                $newConversion->order_name = $conversion->order_name;
                $newConversion->affiliate_id = $parent->id;
                $newConversion->commission = $commission;
                $newConversion->total = $conversion->total;
                $newConversion->subtotal = $conversion->subtotal;
                $newConversion->commission_type = $conversion->commission_type;
                $newConversion->commission_amount = $conversion->commission_amount;
                $newConversion->quantity = $conversion->quantity;
                $newConversion->group_id = $conversion->group_id;
                $newConversion->level = $i;
                $newConversion->conversion_id = $conversion->id;
                $newConversion->child_affiliate_id = $conversion->affiliate_id;

                $newConversion->tracking_type = Conversion::TRACKING_TYPE_NETWORK;
                $newConversion->save();
                $currentAffiliate = $parent;

                
            }else{
                break;
            }
        }
        

        // return $newConversion;
    }


}
