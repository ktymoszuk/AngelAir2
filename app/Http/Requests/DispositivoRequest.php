<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DispositivoRequest extends FormRequest
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
            "Deveui" => "required|max:16",
            "Latitudine" => "between:0,999999.999999",
            "Longitudine" => "between:0,999999.999999",
            "codCategoriaDisp" => "required",
            "codStruttura" => "required",
        ];
    }

    public function messages() {
        return [
            "Nome.required" => "Il campo Nome è obbligatorio",
            "Deveui.required" => "Il campo Deveui è obbligatorio",
            "Deveui.max" => "Il campo Deveui deve essere di massimo 16 caratteri",
            "Latitudine.between" => "Il campo Latitudine deve essere un decimale per rappresentare una coordinata",
            "Longitudine.between" => "Il campo Longitudine deve essere un decimale per rappresentare una coordinata",
            "codCategoriaDisp.required" => "Il campo Categoria è obbligatorio",
            "codStruttura.required" => "Il campo Struttura è obbligatorio"            
        ];
    }
}
