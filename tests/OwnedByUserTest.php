<?php

namespace Ludo237\Toolbox\Tests;

use Illuminate\Database\Eloquent\Model;
use Ludo237\Toolbox\Tests\Stubs\PostStub;
use Ludo237\Toolbox\Tests\Stubs\UserStub;
use Ludo237\Toolbox\Traits\OwnedByUser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('traits'), CoversClass(OwnedByUser::class)]
class OwnedByUserTest extends TestCase
{
    private UserStub|Model $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserStub::query()->create([
            'uuid' => '123aa-bb456',
            'name' => 'Foo Bar',
            'slug' => 'foo.bar.1',
            'api_key' => '12345abcd',
        ]);
    }

    #[Test]
    public function it_returns_the_right_owner_field()
    {
        $this->assertEquals('id', PostStub::ownerField());
    }

    #[Test]
    public function it_returns_the_right_foreign_owner_field()
    {
        $this->assertEquals('user_id', PostStub::foreignOwnerField());
    }

    #[Test]
    public function it_can_check_if_it_is_owned()
    {
        $post = PostStub::query()->create();

        $anotherPost = PostStub::query()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $this->assertFalse($post->isOwned());
        $this->assertTrue($post->isNotOwned());
        $this->assertFalse($post->isOwnedBy($this->user));
        $this->assertTrue($post->isNotOwnedBy($this->user));
        $this->assertFalse($post->isOwnedByUserId($this->user->getKey()));
        $this->assertTrue($post->isNotOwnedByUserId($this->user->getKey()));

        $this->assertTrue($anotherPost->isOwned());
        $this->assertFalse($anotherPost->isNotOwned());
        $this->assertTrue($anotherPost->isOwnedBy($this->user));
        $this->assertFalse($anotherPost->isNotOwnedBy($this->user));
        $this->assertTrue($anotherPost->isOwnedByUserId($this->user->getKey()));
        $this->assertFalse($anotherPost->isNotOwnedByUserId($this->user->getKey()));
    }

    #[Test]
    public function it_can_check_if_it_is_not_owned()
    {
        $post = PostStub::query()->create();
        $anotherPost = PostStub::query()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $this->assertTrue($post->isNotOwned());
        $this->assertFalse($anotherPost->isNotOwned());
    }

    #[Test]
    public function user_can_be_set_through_a_convenient_mutator()
    {
        $post = PostStub::query()->create();
        $post->owner = $this->user;

        $this->assertEquals($this->user->getKey(), $post->getAttributeValue('user_id'));
        $this->assertEquals($this->user->getKey(), $post->owner->getKey());
    }

    #[Test]
    public function it_inject_the_belongs_to_user_relationship()
    {
        $post = PostStub::query()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $this->assertInstanceOf(UserStub::class, $post->user);
        $this->assertEquals($post->getAttributeValue('user_id'), $post->user->getKey());

        $post = PostStub::query()->create();

        $this->assertNull($post->user);
    }
}
