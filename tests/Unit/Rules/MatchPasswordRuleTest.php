<?php

namespace Ludo237\Toolbox\Tests\Unit\Rules;

use Ludo237\Toolbox\Rules\MatchPasswordRule;
use Ludo237\Toolbox\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('rules'), CoversClass(MatchPasswordRule::class)]
class MatchPasswordRuleTest extends TestCase
{
    #[Test]
    public function a_secure_match_must_pass_the_validation()
    {
        // Mocking the attribute but we don't care that much
        $hash = bcrypt('foobar');
        $rule = new MatchPasswordRule($hash);

        dd($rule->validate('input', 'foobaro', function () {
            throw new \Exception();
        }));
        $this->assertNull($rule->validate('input', 'foobar', function () {
            throw new \Exception();
        }));
    }

    #[Test]
    public function an_invalid_match_must_fail_the_validation()
    {
        // Mocking the attribute but we don't care that much
        $hash = bcrypt('foobar');
        $rule = new MatchPasswordRule($hash);

        $this->assertFalse($rule->passes('input', 'fooba'));
        $this->assertFalse($rule->passes('input', 'foobra'));
        $this->assertFalse($rule->passes('input', 'FOOBAR'));
        $this->assertFalse($rule->passes('input', 'FooBar'));
    }

    #[Test]
    public function it_returns_a_valid_error_message()
    {
        $rule = new MatchPasswordRule('foobar');

        $this->assertNotEmpty($rule->message());
        $this->assertIsString($rule->message());
    }
}
