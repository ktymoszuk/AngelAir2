<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            "Email" => "required|email",
            "Password" => "required"
        ];
    }

    public function messages() {
        return [
            "Email.required" => "Il campo Email non è stato compilato",
            "Email.email" => "Inserisci una mail valida",
            "Password.required" => "Il campo Password non è stato compilato"
        ];
    }
}
