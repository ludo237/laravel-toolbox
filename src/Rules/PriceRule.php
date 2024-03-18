<?php

namespace Ludo237\Toolbox\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PriceRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match("/^[\d.,]+$/", (string) $value)) {
            $fail('validation.price')->translate();
        }
    }
}
