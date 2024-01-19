<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StrutturaRequest extends FormRequest
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
            "Nome" => "required",
            "Indirizzo" => "required",
            "Referente" => "nullable",
            "Latiutdine" => "nullable|between:0,9999999999.9999999",
            "Longitudine" => "nullable|between:0,9999999999.9999999",
            "Cartografia" => "nullable|image|mimes:jpeg,png,jpg|max:1024",
            "CartografiaAttuale" => "nullable",
            "codAutomazione" => "required"
        ];
    }

    public function messages() {
        return [
            "Nome.required" => "Il campo Nome è obbligatorio",
            "Indirizzo.required" => "Il campo Indirizzo è obbligatorio",
            "Latitudine.between" => "Il campo Latitudine deve essere un decimale per rappresentare una coordinata",
            "Longitudine.between" => "Il campo Longitudine deve essere un decimale per rappresentare una coordinata",
            "Cartografia.image" => "Il campo Cartografia deve essere un'immagine",
            "Cartografia.mimes" => "L'immagine caricata deve essere di tipo jpeg, png, jpg",
            "Cartografia.max" => "L'immagine caricata supera la dimensione massima richiesta: 1,024 MB",
            "codAutomazione.required" => "Il campo Automazione è obbligatorio"
        ];
    }
}
