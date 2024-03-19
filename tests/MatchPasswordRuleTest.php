<?php

namespace Ludo237\Toolbox\Tests;

use Ludo237\Toolbox\Rules\MatchPasswordRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('rules'), CoversClass(MatchPasswordRule::class)]
class MatchPasswordRuleTest extends TestCase
{
    #[Test]
    public function a_secure_match_must_pass_the_validation()
    {
        $failed = false;
        $closure = function () use (&$failed) {
            $failed = true;
        };

        // Mocking the attribute but we don't care that much
        $hash = bcrypt('foobar');
        $rule = new MatchPasswordRule($hash);

        $rule->validate('input', 'foobar', $closure);

        $this->assertFalse($failed);
    }

    #[Test]
    public function an_invalid_match_must_fail_the_validation()
    {
        $failed = false;
        $closure = function () use (&$failed) {
            $failed = true;
        };

        // Mocking the attribute but we don't care that much
        $hash = bcrypt('foobar');
        $rule = new MatchPasswordRule($hash);

        $rule->validate('input', 'fooba', $closure);
        $this->assertTrue($failed);
        $failed = false;

        $rule->validate('input', 'foobra', $closure);
        $this->assertTrue($failed);
        $failed = false;

        $rule->validate('input', 'FOOBAR', $closure);
        $this->assertTrue($failed);
        $failed = false;

        $rule->validate('input', 'FooBar', $closure);
        $this->assertTrue($failed);
    }
}
