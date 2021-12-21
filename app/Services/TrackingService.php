<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Click;
use App\Models\Group;
use App\Models\Tracking;
use App\Models\Shop;
use Carbon\Carbon;

class TrackingService{

    public function create($data)
    {

        if( $data['event_type']  == 'click' ) {

            $affiliate = $this->getAffiliate($data['aff_id']);

            if($affiliate) {
                $now = now();
                $tracking = Tracking::updateOrCreate(
                    [ 'client_id' => $data['client_id'], 'affiliate_id' => $affiliate->id ],
                    [ 'shop_id' => $affiliate->shop_id, 'created_at' => $now, 'expire_at' => $now->addDays($affiliate->group->cookie_days) ]
                );

                $this->storeClick($affiliate->id);
                $tracking->expire_at;
                return [
                    'expire_at' => $tracking->expire_at->timestamp,
                    'event_type' => $data['event_type']
                ];
            }

        }else if( $data['event_type'] == 'add_to_cart' ) {
            Tracking::where('client_id', $data['client_id'])->update(['cart_token'=>$data['cart_token']]);
        }else{
            Tracking::where('client_id', $data['client_id'])->update(['checkout_token'=>$data['checkout_token']]);
        }
        return [
            'event_type' => $data['event_type']
        ];
    }

    private function getAffiliate ($hashCode)
    {
        $affiliate = Affiliate::where('hash_code', $hashCode)->with('group:id,cookie_days')->first();
        return $affiliate;
    }

    private function storeClick($affiliateId)
    {
        $today = Carbon::now()->toDateString();
        $click =Click::firstOrNew(['affiliate_id'=>$affiliateId, 'date' => $today]);
        $click->count = $click->count+1;
        $click->save();
    }


}
