<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfiloRequest extends FormRequest
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
            "Username" => "required",
            "Email" => "required",
            "FotoProfilo" => "nullable|image|mimes:jpeg,png,jpg|max:1024",
            "FotoProfiloAttuale" => "nullable",
        ];
    }

    public function messages() {
        return [
            "Username.required" => "Il campo Username è obbligatorio",
            "Email.required" => "Il campo Email è obbligatorio",
            "FotoProfilo.image" => "Il campo FotoProfilo deve essere un'immagine",
            "FotoProfilo.mimes" => "L'immagine caricata deve essere di tipo jpeg, png, jpg",
            "FotoProfilo.max" => "L'immagine caricata supera la dimensione massima richiesta: 1,024 MB",
        ];
    }
}
