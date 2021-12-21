<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\Auth;

class SubdomainUnique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $shopId = Auth::user()->shop_id;
        $isExist = ShopSetting::where('sub_domain', $value)->where('shop_id', '!=', $shopId)->first();

        return $isExist ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The sub domain has already been taken.';
    }
}
