<?php

namespace Ludo237\Toolbox\Tests;

use Ludo237\Toolbox\Tests\Stubs\UserStub;
use PHPUnit\Framework\Attributes\Test;

class ExposeTablePropertiesTest extends TestCase
{
    #[Test]
    public function it_displays_the_table_name()
    {
        $this->assertEquals('users', UserStub::tableName());
    }

    #[Test]
    public function it_displays_the_primary_key_name()
    {
        $this->assertEquals('id', UserStub::primaryKeyName());
    }

    #[Test]
    public function it_displays_the_primary_key_type()
    {
        $this->assertEquals('int', UserStub::primaryKeyType());
    }

    #[Test]
    public function it_returns_the_complete_set_for_primary_key()
    {
        $this->assertIsArray(UserStub::primaryKey());

        $this->assertEquals('id', UserStub::primaryKey()['name']);
        $this->assertEquals('int', UserStub::primaryKey()['type']);
    }
}
