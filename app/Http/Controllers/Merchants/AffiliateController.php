<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use App\Services\AffiliateService;
use App\Services\CategoryService;
use Response;
use Rap2hpoutre\FastExcel\FastExcel;

class AffiliateController extends Controller
{


    private AffiliateService $affiliateService;
    private CategoryService $categoryService;
    public function __construct(AffiliateService $affiliateService, CategoryService $categoryService)
    {
        $this->affiliateService = $affiliateService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $affiliates = $this->affiliateService->getAffiliateWithPaginate($request->all());
        return $this->successResponse($affiliates, 'success', 200);
    }

    public function show($id)
    {
        $affiliate = $this->affiliateService->getAffiliate($id);
        return $this->successResponse($affiliate, 'success', 200);
    }

    public function getStatistics($id)
    {
        $statistics = $this->affiliateService->getStatistics($id);
        return $this->successResponse($statistics, 'success', 200);
    }

    public function search(Request $request)
    {
        $affiliates = $this->affiliateService->search($request->all());
        return Response::json($affiliates);
    }

    public function changeStatus(Request $request)
    {
        $this->affiliateService->changeStatus($request->only('affiliate_id', 'status'));
        return $this->successResponse('', 'success', 200);
    }

    public function destroy($id)
    {
        $this->affiliateService->destroy($id);
        return $this->successResponse('', 'success', 200);
    }

    public function getCommissionStatistics($id)
    {
        $statistics = $this->affiliateService->getCommissionStatistics($id);
        return $this->successResponse($statistics, 'success', 200);
    }
    public function setParent(Request $request, $id)
    {
        $requestData = $request->only('parent_id');
        $res = $this->affiliateService->setParent($id, $requestData['parent_id']);
        if ($res['status']) {
            return $this->successResponse($res['affiliate'], 'success', 200);
        } else {
            return $this->errorResponse($res['message'], 400, []);
        }
    }

    public function getNetworkStatistics(Request $request, $id)
    {
        $res = $this->affiliateService->getNetworkStatistics($id);
        return $this->successResponse($res, 'success', 200);
    }

    public function LoginAs($id)
    {
        $token = $this->affiliateService->loginAs($id);
        return $this->successResponse($token, 'success', 200);
    }
    public function changeGroup(Request $request, $id)
    {
        $group = $this->affiliateService->changeGroup($request->only('group_id'), $id);
        return $this->successResponse($group, 'success', 200);
    }
    public function removeGroup(Request $request, $id)
    {
        $count = $this->affiliateService->removeGroup($request->only('group_id'), $id);
        return $this->successResponse($count, 'success', 200);
    }
    public function setPrimaryAffLink(Request $request, $id)
    {
        $affiliate = $this->affiliateService->setPrimaryAffLink($request->only('primary_aff_link'), $id);
        return $this->successResponse($affiliate, 'success', 200);
    }
    public function setCustomAffLink(Request $request, $id)
    {
        $res = $this->affiliateService->setCustomAffLink($request->only('path', 'target'), $id);
        return $this->successResponse($res, 'success', 200);
    }
    public function updateCustomAffLink(Request $request, $id)
    {
        $res = $this->affiliateService->updateCustomAffLink($request->only('path'), $id);
        return $this->successResponse($res, 'success', 200);
    }

    public function uploadFileExcel(Request $request)
    {
        $path = $this->categoryService->uploadFileExcel($request->file('image'));
        return $this->successResponse($path, 'success', 200);
    }

    public function getDownlines(Request $request, $id)
    {
        $res = $this->affiliateService->getDownlines($id);
        return $this->successResponse($res, 'success', 200);
    }

    public function exportAffiliate(Request $request)
    {
        $exportAffiliate = $this->affiliateService->exportAffiliate($request->all());
        return (new FastExcel($exportAffiliate['affiliates']))->download('affiliates-collection.xlsx', function ($affiliate) use ($exportAffiliate) {
            return [
                'Email' =>  $affiliate->email,
                'First Name' =>  $affiliate->first_name,
                'Last Name' => $affiliate->last_name,
                'Address' => $affiliate->address,
                'Company' => $affiliate->company,
                'Country' => $affiliate->country,
                'Phone' => $affiliate->phone,
                'Personal Detail' => $affiliate->personal_detail,
                'City' => $affiliate->city,
                'Zipcode' => $affiliate->zipcode,
                'State' => $affiliate->state,
                'Website' => $affiliate->website,
                'Facebook' => $affiliate->facebook,
                'Youtube' => $affiliate->youtube,
                'Instagram' => $affiliate->instagram,
                'Additional Infos' => $this->getAdditionalInfos($affiliate->additional_infos),
                'Program' => $affiliate->group->name,
                'Referral link' => $this->generateLinkAffiliate($affiliate->primary_aff_link ? $affiliate->primary_aff_link : $exportAffiliate['shopSettings']->default_affiliate_link)
                    . $affiliate->group_id . ':' . $affiliate->hash_code,
                'Network link' => $this->convertNetworkLink($exportAffiliate['shopSettings']->sub_domain, $affiliate->group, $affiliate->hash_code),
                'Referral code' => $affiliate->group_id . ':' . $affiliate->hash_code,
                'Payment Method' => $this->getPaymentMethodName($exportAffiliate['paymentMethodsAvailable'], $affiliate->payment_method),
                'Payment Info' => $this->genPaymentMethodInfo($exportAffiliate['paymentMethodsAvailable'], $affiliate->payment_method, $affiliate->payment_info),
                'Coupons' => implode(",", $affiliate->coupons->pluck('code')->toArray()),
                'Status' =>  $this->convertStatus($affiliate->status),
                'Date created' => $affiliate->created_at,
            ];
        });
    }
    protected function convertStatus($status)
    {
        $statusContent = '';
        if ($status == 1) {
            $statusContent = 'Approved';
        }
        if ($status == 2) {
            $statusContent = 'Pending';
        }
        if ($status == 3) {
            $statusContent = 'Denied';
        }
        return $statusContent;
    }

    protected function convertNetworkLink($subDomain, $group, $hashCode)
    {
        $baseDomain = config('endpoint.main_domain');
        $link = "https://" . $subDomain . "." . $baseDomain . "/#/register";
        if (!$group->is_default) {
            $link = $link . '/' . $group->slug . '?parent=' . $hashCode;
            return $link;
        } else {
            $link = $link . '?parent=' . $hashCode;
            return $link;
        }
    }

    protected function generateLinkAffiliate($link)
    {
        if ($link) {
            $arrLinkSplit = explode('?', $link);
            if (count($arrLinkSplit) > 1 && $arrLinkSplit[1] !== '') {
                return $link . "&bg_ref=";
            } else {
                return $link . "?bg_ref=";
            }
        } else {
            return '';
        }
    }

    protected function getPaymentMethodName($paymentMethods, $key)
    {
        if (count($paymentMethods) != 0) {
            foreach ($paymentMethods as $pay) {
                if ($pay->key ==  $key) {
                    return $pay->name;
                }
            }
        }
        return '';
    }

    protected function genPaymentMethodInfo($paymentMethods, $key, $infos)
    {
        $details = [];
        if (count($paymentMethods) != 0) {
            $paymentMethodAffiliate = null;
            foreach ($paymentMethods as $pay) {
                if ($pay->key == $key) {
                    $paymentMethodAffiliate = $pay;
                    break;
                }
            }
            if ($paymentMethodAffiliate) {
                $fields = json_decode($paymentMethodAffiliate->fields, true);
                if ($fields) {
                    foreach ($fields as $field) {
                        $tempArr = [$field['label'] . ':' . $infos[$field['model']]];
                        $details = array_merge($details, $tempArr);
                    }
                } else {
                    $details = ['Automatically Generated'];
                }
            }
        }
        return implode(',', $details);
    }

    protected function getAdditionalInfos($additionalInfos)
    {
        if ($additionalInfos) {
            if (count($additionalInfos) != 0) {
                $details = [];
                foreach ($additionalInfos as $add) {
                    $tempArr = [$add['label'] . ':' . $add['value']];
                    $details = array_merge($details, $tempArr);
                }
                return implode(" , ", $details);
            }
        }
        return '';
    }

    public function updateProfile(Request $request, $id)
    {
        $res = $this->affiliateService->updateProfile($request->all(), $id);
        return $this->successResponse($res, 'success', 200);
    }

    public function updatePassword(Request $request, $id)
    {
        return $this->affiliateService->updatePassword($request->all(), $id);
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        $res = $this->affiliateService->updatePaymentMethod($request->all(), $id);
        return $this->successResponse($res, 'success', 200);
    }
}
