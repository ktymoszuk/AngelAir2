<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStruttureRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "Nome" => "required|max:50",
            "Indirizzo" => "nullable|max:50",
            "Latitudine" => "nullable|decimal:1,7",
            "Longitudine" => "nullable|decimal:1,7",
            "Km" => "nullable|max:10",
            "Referente" => "nullable|max:150"
        ];
    }

    public function messages() {
        return [
                "Nome.required" => "Il campo nome è richiesto",
                "Nome.max" => "Il nome può avere al massimo 50 caratteri",
                "Indirizzo.max" => "L'indirizzo può avere al massimo 50 caratteri",
                "Latitudine.decimal" => "La latitudine deve essere un numero decimale",
                "Longitudine.decimal" => "La longitudine deve essere un numero decimale",
                "Km.max" => "Il campo km può avere al massimo 10 caratteri",
                "Referente.max" => "Il campo referente può avere al massimo 150 caratteri"
        ];
    }
}
