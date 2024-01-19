<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SogliaRequest extends FormRequest
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
            "codAutomazione" => "required",
            "codCategoriaSoglia" => "required",
            "TipoDatoSoglia" => "required",
            "TipoSoglia" => "required",
            "OperatoreMinimo" => "required",
            "ValoreMinimo" => "required",
            "AliasMinimo" => "required",
            "OperatoreMassimo" => "required_if:TipoSoglia,Range",
            "ValoreMassimo" => "required_if:TipoSoglia,Range",
            "AliasMassimo" => "required_if:TipoSoglia,Range",
            "codCategoriaDisp" => "required_if:codCategoriaSoglia,2,3,5",
            "ColonnaAssociata" => "required_if:codCategoriaSoglia,2,3,5",
            "ValoreAssociato" => "required_if:codCategoriaSoglia,2,3,5",

        ];
    }

    public function messages() {
        return [
            "Nome.required" => "Il campo Nome Soglia non è stato compilato",
            "codAutomazione.required" => "Non è stata selezionata un'Automazione",
            "codCategoriaSoglia.required" => "Non è stata selezionata una Categoria Soglia",
            "TipoDatoSoglia.required" => "Non è stata indicata la Tipologia Dato Soglia",
            "TipoSoglia.required" => "Non è stata indicata la Tipologia Soglia",
            "OperatoreMinimo.required" => "Non è stato selezionato un'operatore aritmetico per la Soglia Minima",
            "ValoreMinimo.required" => "Il campo Valore Minimo non è stato compilato",
            "AliasMinimo.required" => "Il campo Alias Minimo non è stato compilato",
            "OperatoreMassimo.required" => "Non è stato selezionato un'operatore aritmetico per la Soglia Massima",
            "ValoreMassimo.required_if" => "Il campo Valore Massimo non è stato compilato",
            "AliasMassimo.required_if" => "Il campo Alias Massimo non è stato compilato",
            "codCategoriaDisp.required_if" => "Non è stata selezionata una Categoria Dispositivo",
            "ColonnaAssociata.required_if" => "Non è stato selezionato un valore da associare alla soglia",
            "ValoreAssociato.required_if" => "Non è stato selezionato un valore da associare alla soglia",
        ];
    }
}
