<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UtenteRequest extends FormRequest
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
            "Username" => "required",
            "Email" => "required",
            "Ruolo" => "required"
        ];
    }

    public function messages() {
        return [
            "Username.required" => "Il campo Username è obbligatorio",
            "Email.required" => "Il campo Email è obbligatorio",
            "Ruolo.required" => "Selezionare un ruolo per l'utente"
        ];
    }
}
