<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutomazioneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            "Nome" => "required",
            "Latiutdine" => "nullable|between:0,9999999999.9999999",
            "Longitudine" => "nullable|between:0,9999999999.9999999",
            "Cartografia" => "nullable|image|mimes:jpeg,png,jpg|max:1024",
            "CartografiaAttuale" => "nullable"
        ];
    }

    public function messages() {
        return [
            "Nome.required" => "Il campo Nome è obbligatorio",
            "Latitudine.between" => "Il campo Latitudine deve essere un decimale per rappresentare una coordinata",
            "Longitudine.between" => "Il campo Longitudine deve essere un decimale per rappresentare una coordinata",
            "Cartografia.image" => "Il campo Cartografia deve essere un'immagine",
            "Cartografia.mimes" => "L'immagine caricata deve essere di tipo jpeg, png, jpg",
            "Cartografia.max" => "L'immagine caricata supera la dimensione massima richiesta: 1,024 MB"
        ];
    }
}
