<?php

namespace Ludo237\Toolbox\Tests;

use Illuminate\Support\Facades\Date;
use Ludo237\Toolbox\Tests\Stubs\UserStub;
use Ludo237\Toolbox\Traits\Bannable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('traits'), CoversClass(Bannable::class)]
class BannableTest extends TestCase
{
    #[Test]
    public function it_returns_right_ban_field()
    {
        $this->assertEquals('banned_at', UserStub::banField());
    }

    #[Test]
    public function it_returns_number_of_days_remaining_for_the_ban()
    {
        $user = UserStub::query()->create([
            'name' => 'foo',
            'banned_at' => Date::today()->addDays(10),
        ]);

        $this->assertEquals(10, $user->remainingBanDays());
    }

    #[Test]
    public function it_returns_true_if_an_entity_is_banned()
    {
        $user = UserStub::query()->create([
            'name' => 'foo',
            'banned_at' => Date::today(),
        ]);

        $this->assertTrue($user->isBanned());
        $this->assertFalse($user->isNotBanned());
    }

    #[Test]
    public function it_returns_true_if_an_entity_is_still_banned_up_until_today()
    {
        $user = UserStub::query()->create([
            'name' => 'foo',
            'banned_at' => Date::today(),
        ]);

        $this->assertTrue($user->isStillBanned());

        $user = UserStub::query()->create([
            'name' => 'foo',
            'banned_at' => Date::today()->subDay(),
        ]);

        $this->assertFalse($user->isStillBanned());
    }

    #[Test]
    public function it_returns_true_if_an_entity_has_an_expired_ban()
    {
        $user = UserStub::query()->create([
            'name' => 'foo',
            'banned_at' => Date::today(),
        ]);

        $this->assertFalse($user->hasExpiredBan());

        $user = UserStub::query()->create([
            'name' => 'foo',
            'banned_at' => Date::today()->subDay(),
        ]);

        $this->assertTrue($user->hasExpiredBan());
    }

    #[Test]
    public function it_can_ban_an_entity()
    {
        $user = UserStub::query()->create(['name' => 'foo']);

        $user->banFor($banDate = Date::today()->addDays(4));
        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'banned_at' => $banDate,
        ]);

        $user->banForOneDay();
        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'banned_at' => Date::tomorrow(),
        ]);

        $user->banForOneWeek();
        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'banned_at' => Date::today()->addWeek(),
        ]);

        $user->banForOneMonth();
        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'banned_at' => Date::today()->addMonth(),
        ]);

        $user->banForOneYear();
        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'banned_at' => Date::today()->addYear(),
        ]);

        $user->banForever();
        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'banned_at' => Date::today()->addCentury(),
        ]);
    }

    #[Test]
    public function it_can_lift_a_ban()
    {
        $user = UserStub::query()->create([
            'name' => 'foo',
            'banned_at' => Date::today(),
        ]);

        $user->liftBan();

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'banned_at' => null,
        ]);
    }
}
