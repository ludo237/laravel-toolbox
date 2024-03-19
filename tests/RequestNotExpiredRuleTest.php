<?php

namespace Ludo237\Toolbox\Tests;

use Illuminate\Support\Facades\Date;
use Ludo237\Toolbox\Rules\RequestNotExpiredRule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @group Rules
 */
class RequestNotExpiredRuleTest extends TestCase
{
    #[Test]
    public function a_not_expired_date_should_pass_the_validation()
    {
        $failed = false;
        $closure = function () use (&$failed) {
            $failed = true;
        };
        $rule = new RequestNotExpiredRule();

        $rule->validate('input', Date::now()->addHour()->timestamp, $closure);
        $this->assertFalse($failed);

        $rule->validate('input', Date::now()->subHour()->timestamp, $closure);
        $this->assertTrue($failed);
    }
}
