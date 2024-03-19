<?php

namespace Ludo237\Toolbox\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WithoutSpace implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (intval(preg_match('/^\S*$/u', $value)) !== 1) {
            $fail('The attribute :attribute contains spaces');
        }
    }
}
