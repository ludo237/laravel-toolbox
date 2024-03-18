<?php

namespace Ludo237\Toolbox\Tests\Unit\Rules;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Ludo237\Toolbox\Rules\SecureCryptoTokenRule;
use Ludo237\Toolbox\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('rules'), CoversClass(SecureCryptoTokenRule::class)]
class SecureCryptoTokenRuleTest extends TestCase
{
    #[Test]
    public function a_secure_match_must_pass_the_validation()
    {
        $rule = new SecureCryptoTokenRule([1234, 'foo bar'], '_');

        $this->assertTrue(
            $rule->passes('input', Crypt::encrypt('1234_foo bar'))
        );
    }

    #[Test]
    public function an_invalid_match_must_fail_the_validation()
    {
        $rule = new SecureCryptoTokenRule([1234, 'foo bar'], '_');

        $this->assertFalse(
            $rule->passes('input', Crypt::encrypt('1234+foo bar'))
        );

        $this->assertFalse(
            $rule->passes('input', Crypt::encrypt('foo bar_1234'))
        );

        $this->assertFalse(
            $rule->passes('input', Str::random())
        );
    }

    #[Test]
    public function it_returns_a_valid_error_message()
    {
        $rule = new SecureCryptoTokenRule([1234, 'foo bar'], '_');

        $this->assertNotEmpty($rule->message());
        $this->assertIsString($rule->message());
    }
}
