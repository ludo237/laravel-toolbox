<?php

namespace Ludo237\Toolbox\Tests\Unit\Traits;

use Illuminate\Support\Facades\Date;
use Ludo237\Toolbox\Tests\Stubs\UserStub;
use Ludo237\Toolbox\Tests\TestCase;
use Ludo237\Toolbox\Traits\CanBeActivate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('traits'), CoversClass(CanBeActivate::class)]
class CanBeActivateTest extends TestCase
{
    #[Test]
    public function it_returns_the_right_activate_field()
    {
        $this->assertEquals('activated_at', UserStub::activateAtField());
    }

    #[Test]
    public function it_returns_true_if_a_model_is_active()
    {
        $user = UserStub::query()->create([
            'name' => 'foo',
            'activated_at' => Date::today(),
        ]);

        $this->assertTrue($user->isActive());
        $this->assertFalse($user->isNotActive());
    }

    #[Test]
    public function it_can_activate_a_model()
    {
        $user = UserStub::query()->create([
            'name' => 'foo',
            'activated_at' => null,
        ]);

        $user->activate();

        $this->assertDatabaseMissing('users', [
            'id' => $user->getKey(),
            'activated_at' => null,
        ]);
    }

    #[Test]
    public function it_can_deactivate_a_model()
    {
        $user = UserStub::query()->create([
            'name' => 'foo',
            'activated_at' => Date::today(),
        ]);

        $user->deactivate();

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'activated_at' => null,
        ]);
    }
}
