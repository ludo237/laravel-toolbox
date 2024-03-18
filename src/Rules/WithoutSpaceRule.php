<?php

namespace Ludo237\Toolbox\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class WithoutSpaceRule implements ValidationRule
{
    public function passes($attribute, $value) : bool
    {
        return intval(preg_match('/^\S*$/u', $value)) === 1;
    }

    public function message() : string
    {
        return "The attribute :attribute contains spaces";
    }
}
