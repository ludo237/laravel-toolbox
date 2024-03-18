<?php

namespace Ludo237\Toolbox\Tests\Unit\Traits;

use Illuminate\Support\Str;
use Ludo237\Toolbox\Tests\Stubs\UserStub;
use Ludo237\Toolbox\Tests\TestCase;
use Ludo237\Toolbox\Traits\HasSlug;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('traits'), CoversClass(HasSlug::class)]
class HasSlugTest extends TestCase
{
    #[Test]
    public function it_has_a_sluggable_key()
    {
        $this->assertEquals('name', UserStub::sluggableKey());
    }

    #[Test]
    public function it_has_a_separator()
    {
        $this->assertEquals('.', UserStub::separator());
    }

    #[Test]
    public function it_has_a_slug_key()
    {
        $this->assertEquals('slug', UserStub::slugKey());
    }

    #[Test]
    public function it_creates_a_slug_if_not_provided_when_creating()
    {
        $user = UserStub::query()->create(['name' => 'foo']);

        $slug = $user->getAttributeValue('slug');
        $slug = explode('.', $slug);

        $this->assertNotNull($slug);
        $this->assertEquals(8, strlen($slug[1]));
        $this->assertEquals(Str::slug($user->getAttributeValue('name')), $slug[0]);
    }

    #[Test]
    public function it_does_not_create_a_slug_if_provided_when_creating()
    {
        $user = UserStub::query()->create(['name' => 'foo', 'slug' => 'foo.bar_baz']);

        $this->assertEquals('foo.bar_baz', $user->getAttributeValue('slug'));
    }

    #[Test]
    public function it_creates_a_slug_if_not_provided_when_updating()
    {
        $user = UserStub::query()->create(['name' => 'foo', 'slug' => 'foo.bar_baz']);

        $user->update([
            'slug' => null,
        ]);

        $slug = $user->getAttributeValue('slug');
        $slug = explode('.', $slug);

        $this->assertNotNull($slug);
        $this->assertEquals(8, strlen($slug[1]));
        $this->assertEquals(Str::slug($user->getAttributeValue('name')), $slug[0]);

        // Better safe than sorry
        $user->update([
            'slug' => '',
        ]);

        $slug = $user->getAttributeValue('slug');
        $slug = explode('.', $slug);

        $this->assertNotNull($slug);
        $this->assertEquals(8, strlen($slug[1]));
        $this->assertEquals(Str::slug($user->getAttributeValue('name')), $slug[0]);
    }

    #[Test]
    public function it_does_not_create_a_slug_if_provided_when_updating()
    {
        $user = UserStub::query()->create(['name' => 'foo', 'slug' => 'foo.bar_baz']);

        $user->update([
            'name' => 'new name',
            'slug' => 'foo.baz',
        ]);

        $this->assertEquals('foo.baz', $user->getAttributeValue('slug'));
    }
}
