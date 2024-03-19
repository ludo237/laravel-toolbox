<?php

namespace Ludo237\Toolbox\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

readonly class MatchPassword implements ValidationRule
{
    public function __construct(private string $expected)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Hash::check($value, $this->expected)) {
            $fail('The provided password does not match');
        }
    }
}
