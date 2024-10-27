<?php 

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FloatValue implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the value is a valid float
        \Log::info("Validating $attribute with value $value");
        return is_numeric($value) && strpos((string)$value, '.') !== false;
    }

    public function message()
    {
        return 'The :attribute must be a valid float value.';
    }
}
