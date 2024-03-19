<?php

namespace Ludo237\Toolbox\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPrice implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match("/^[\d.,]+$/", (string) $value)) {
            $fail(':attribute is not a valid price');
        }
    }
}
