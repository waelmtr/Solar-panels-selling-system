<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
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
            'name'=>'required|string|unique:companies,name' , 
            'logo' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' ,
            'lon'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'lat'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'email'=>'required|email|unique:companies,email' ,
            'password'=>'required|string|confirmed' ,
            'phone'=>'required|string' ,
            'role_id'=>'required|integer' ,
        ];
    }
}
