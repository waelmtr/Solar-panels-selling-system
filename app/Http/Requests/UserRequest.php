<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required|string" ,
            "lon" => "required|regex:/^\d+(\.\d{1,3})?$/",
            "lat" => "required|regex:/^\d+(\.\d{1,3})?$/",
            "email" => "required|email|unique:users,email" ,
            "password" => "required|string|confirmed" ,
            "phone" => "required|string" ,
            "role_id" => "required|integer"
        ];
    }
}
