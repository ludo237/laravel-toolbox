<?php

namespace Ludo237\Toolbox\Tests;

use Ludo237\Toolbox\Rules\WithoutSpaceRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('rules'), CoversClass(WithoutSpaceRule::class)]
class WithoutSpacesRuleTest extends TestCase
{
    #[Test]
    public function a_string_passes_without_spaces()
    {
        $failed = false;
        $closure = function () use (&$failed) {
            $failed = true;
        };
        $rule = new WithoutSpaceRule();

        $rule->validate('input', 'avalidstring', $closure);
        $this->assertFalse($failed);

        $rule->validate('input', 'not a valid string', $closure);
        $this->assertTrue($failed);
    }
}
