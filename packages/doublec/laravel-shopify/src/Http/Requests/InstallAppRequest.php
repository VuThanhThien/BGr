<?php

namespace DoubleC\LaravelShopify\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class InstallAppRequest extends FormRequest
{
    /**
     * |-----------------------------------
     * |-----------------------------------
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * |-----------------------------------
     * |-----------------------------------
     * Get the validation rules that apply to the request.
     * @return array
     */
    #[ArrayShape(["shop" => "string"])]
    public function rules(): array
    {
        return [
            "shop" => 'required|regex:/^[0-9a-z\.\-]+\\.myshopify\\.com$/',
        ];
    }

    /**
     * |-----------------------------------
     * |-----------------------------------
     * Prepare
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $shop = $this->get("shop", "");
        $shop = str_replace("https://", "", $shop);
        $shop = str_replace("http://", "", $shop);
        $isContainDomain = str_contains(haystack: $shop, needle: ".myshopify.com");
        $shop = $isContainDomain ? substr($shop, 0, strpos($shop, ".myshopify.com")): $shop;
        $shop = "{$shop}.myshopify.com";
        $this->merge([
            "shop" => $shop
        ]);
    }
}
