<?php

namespace Ludo237\Toolbox\Tests\Unit\Rules;

use Illuminate\Support\Facades\Date;
use Ludo237\Toolbox\Rules\RequestNotExpiredRule;
use Ludo237\Toolbox\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * @group Rules
 */
class RequestNotExpiredRuleTest extends TestCase
{
    #[Test]
    public function a_not_expired_date_should_pass_the_validation()
    {
        $rule = new RequestNotExpiredRule();

        $this->assertTrue(
            $rule->passes('input', Date::now()->addHour()->timestamp)
        );

        $this->assertFalse(
            $rule->passes('input', Date::now()->subHour()->timestamp)
        );
    }

    #[Test]
    public function it_returns_a_valid_error_message()
    {
        $rule = new RequestNotExpiredRule();

        $this->assertNotEmpty($rule->message());
        $this->assertIsString($rule->message());
    }
}
