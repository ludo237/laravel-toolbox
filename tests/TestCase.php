<?php

namespace Ludo237\Toolbox\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase();
    }

    protected function setupDatabase(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ulid('uid');
            $table->string('slug')->unique();
            $table->string('name');
            $table->dateTime('banned_at')->nullable()->default(null);
            $table->dateTime('activated_at')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });
    }
}
