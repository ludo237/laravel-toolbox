<?php

namespace Ludo237\Toolbox\Rules;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Crypt;

readonly class SecureCryptoTokenRule implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(private array $params, private string $glue)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if (implode($this->glue, $this->params) !== Crypt::decrypt($value)) {
                $fail('The attribute :attribute contains an invalid crypto token');
            }
        } catch (DecryptException $exception) {
            $fail($exception->getMessage());
        }
    }
}
