<?php

namespace DoubleC\LaravelShopify\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class HasPlanIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    #[ArrayShape(["plan_id" => "string"])]
    public function rules(): array
    {
        return [
            "plan_id" => "required|numeric"
        ];
    }
}
