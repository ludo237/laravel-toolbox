<?php

namespace Ludo237\Toolbox\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Date;

class RequestNotExpiredRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (($value && Date::now()->getTimestamp() > $value)) {
            $fail('The current request for :attribute is expired');
        }
    }
}
