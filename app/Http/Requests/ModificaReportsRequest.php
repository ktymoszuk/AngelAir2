<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModificaReportsRequest extends FormRequest
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
            // 'isJSON' => 'nullable|between:0,2|required_without_all:isCSV,isXML',
            // 'isCSV' => 'nullable|between:0,2|required_without_all:isJSON,isXML',
            // 'isXML' => 'nullable|between:0,2|required_without_all:isJSON,isCSV',
            'Nome' => 'nullable|string|max:50',
            'Struttura' => 'nullable|numeric|min:0|max:10000',
            'Dispositivo' => 'nullable|numeric|min:0|max:10000',
            'Frequenza' => 'nullable|numeric|min:1|max:100',
            'Periodo' => 'nullable|numeric|min:0|max:2',
            'Formati' => 'nullable',
        ];
    }

    public function messages() {
        return [
            "Nome.max" => "Il nome inserito è troppo lungo. Limite caratteri: 50",
            // "isJSON.required_without_all" => "Scegliere almeno un formato",
            // "isCSV.required_without_all" => "Scegliere almeno un formato",
            // "isXML.required_without_all" => "Scegliere almeno un formato",
            'Frequenza.min' => "La frequenza deve avere un valore di almeno 1",
            'Periodo.min' => "Il periodo deve avere un valore di almeno 1",
            'Periodo.max' => "Il periodo non può avere un valore superiore a 2",
        ];
    }
}
