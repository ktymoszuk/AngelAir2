<?php

namespace App\Http\Requests;

use App\Rules\CsvValidation;
use Illuminate\Foundation\Http\FormRequest;

class CsvRequest extends FormRequest
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
            "csvFile" => ["required", "file", new CsvValidation],
        ];
    }

    public function messages() {
        return [
            "csvFile.required" => "Il file è obbligatorio",
            "csvFile.file" => "Il documento inserito deve essere un file", 
        ];
    }
}
