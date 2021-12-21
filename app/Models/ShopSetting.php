<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ShopSetting extends Model
{
    use HasFactory;
    protected $table = 'shop_settings';
    protected $casts = [
        'embedded_portal_settings' => 'array',
        'price_rule' => 'array',
        'checkout_popup_data' => 'array',
        'autopay_discount_code' => 'array',
        'klaviyo' => 'array',
        'login_page_config' => 'array',
    ];
    protected $fillable = ['logo'];

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop', 'shop_id', 'id');
    }

    public function info()
    {
        return $this->hasOne('App\Models\ShopInfo', 'shop_id', 'shop_id');
    }
    public function getCheckoutPopupDataAttribute(){
        if($this->attributes['checkout_popup_data'])
        {
            $checkoutPopupData = json_decode($this->attributes['checkout_popup_data']);
            if($checkoutPopupData->imgSrc)
            {
                $checkoutPopupData->pathCDN = Storage::disk('s3')->url($checkoutPopupData->imgSrc);
            }
            return $checkoutPopupData;
        }
        else{
            $checkoutPopupData = config('myconfig.checkoutpopupdata');
            $checkoutPopupData['pathCDN'] = Storage::disk('s3')->url($checkoutPopupData['imgSrc']);
            return $checkoutPopupData;
        }
    }
    public function getLogoAttribute(){
        if(isset($this->attributes['logo']))
        {
            return 'https://d2xrtfsb9f45pw.cloudfront.net/'.$this->attributes['logo'];
        }
        else{         
            return null;
            
        }
    }

    public function getLoginPageConfigAttribute()
    {
        // if($this->attributes['login_page_config']) {
        //     return json_decode($this->attributes['login_page_config']);
        //     // return array_merge_recursive(config('myconfig.registration'), json_decode($this->attributes['registration_form'], true));
        // }else{
            return synConfigUpdateLoginData($this->attributes['login_page_config']);
        // }

    }
}
