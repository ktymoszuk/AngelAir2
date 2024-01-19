<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class CsvValidation implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        if ($value->getClientOriginalExtension() !== "csv") {
            $fail('Il :attribute deve essere un file csv');
        }
    }
}
