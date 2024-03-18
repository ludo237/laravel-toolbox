<?php

namespace Ludo237\Toolbox\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Date;

class RequestNotExpiredRule implements ValidationRule
{
    public function passes($attribute, $value) : bool
    {
        return !($value && Date::now()->getTimestamp() > $value);
    }

    public function message() : string
    {
        return "The current request for :attribute is expired";
    }
}
