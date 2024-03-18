<?php

namespace Ludo237\Toolbox\Rules;


use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class MatchPasswordRule implements ValidationRule
{
    private string $expected;

    /**
     * Create a new rule instance.
     *
     * @param string $expected
     */
    public function __construct(string $expected)
    {
        $this->expected = $expected;
    }

    public function passes($attribute, $value) : bool
    {
        return Hash::check($value, $this->expected);
    }

    public function message() : string
    {
        return "The provided password does not match";
    }
}
