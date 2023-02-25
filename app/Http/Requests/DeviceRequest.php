<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
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
            "img" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048" ,
            "voltage" => "required|integer" ,
            "voltage_pouer" => "required|integer" ,
            "number_fazes" => "required|integer" ,
        ];
    }
}
