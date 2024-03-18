<?php

namespace Ludo237\Toolbox\Tests\Unit\Rules;

use Ludo237\Toolbox\Rules\WithoutSpaceRule;
use Ludo237\Toolbox\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('rules'), CoversClass(WithoutSpaceRule::class)]
class WithoutSpacesRuleTest extends TestCase
{
    #[Test]
    public function a_string_passes_without_spaces()
    {
        $rule = new WithoutSpaceRule();

        $this->assertTrue(
            $rule->passes('input', 'avalidstring')
        );

        $this->assertFalse(
            $rule->passes('input', 'not a valid string')
        );
    }

    #[Test]
    public function it_displays_the_right_message()
    {
        $rule = new WithoutSpaceRule();

        $this->assertEquals('The attribute :attribute contains spaces', $rule->message());
    }
}
