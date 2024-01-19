<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            "old" => "required",
            "new" => "required",
            "confirm" => "required",
        ];
    }

    public function messages() {
        return [
            "old.required" => 'Inserire la password attuale',
            "new.required" => 'Inserire la nuova password',
            "confirm.required" => 'Confermare la password',
        ];
    }
}
