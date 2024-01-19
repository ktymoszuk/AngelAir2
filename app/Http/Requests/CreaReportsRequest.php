<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreaReportsRequest extends FormRequest
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
            'Struttura' => 'required|numeric|min:0|max:10000',
            'Nome' => 'nullable|string|max:50',
            'Frequenza' => 'required|numeric|min:1|max:100',
            'Periodo' => 'required|numeric|min:0|max:2',
            'dataDA' => 'nullable|date',
            'isJSON' => 'nullable|between:0,2|required_without_all:isCSV,isXML',
            'isCSV' => 'nullable|between:0,2|required_without_all:isJSON,isXML',
            'isXML' => 'nullable|between:0,2|required_without_all:isJSON,isCSV',
        ];
    }

    public function messages() {
        return [
            "Struttura.required" => "Scegliere la struttura",
            "Nome.max" => "Il nome inserito è troppo lungo. Limite caratteri: 50",
            "isJSON.required_without_all" => "Scegliere almeno un formato",
            "isCSV.required_without_all" => "Scegliere almeno un formato",
            "isXML.required_without_all" => "Scegliere almeno un formato",
            'Frequenza.required' => "Inserire la frequenza",
            'Frequenza.min' => "La frequenza deve avere un valore di almeno 1",
            'Periodo.required' => "Scegliere il periodo",
            'Periodo.min' => "Il periodo deve avere un valore di almeno 0",
            'Periodo.max' => "Il periodo non può avere un valore superiore a 2",
        ];
    }
}
