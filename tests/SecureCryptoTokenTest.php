<?php

namespace Ludo237\Toolbox\Tests;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Ludo237\Toolbox\Rules\SecureCryptoToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('rules'), CoversClass(SecureCryptoToken::class)]
class SecureCryptoTokenTest extends TestCase
{
    #[Test]
    public function a_secure_match_must_pass_the_validation()
    {
        $failed = false;
        $closure = function () use (&$failed) {
            $failed = true;
        };
        $rule = new SecureCryptoToken([1234, 'foo bar'], '_');

        $rule->validate('input', Crypt::encrypt('1234_foo bar'), $closure);
        $this->assertFalse($failed);
    }

    #[Test]
    public function an_invalid_match_must_fail_the_validation()
    {
        $failed = false;
        $closure = function () use (&$failed) {
            $failed = true;
        };
        $rule = new SecureCryptoToken([1234, 'foo bar'], '_');

        $rule->validate('input', Crypt::encrypt('1234+foo bar'), $closure);
        $this->assertTrue($failed);
        $failed = false;

        $rule->validate('input', Crypt::encrypt('foo bar_1234'), $closure);
        $this->assertTrue($failed);
        $failed = false;

        $rule->validate('input', Str::random(), $closure);
        $this->assertTrue($failed);
    }
}
