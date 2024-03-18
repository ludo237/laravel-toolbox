<?php

namespace Ludo237\Toolbox\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Ludo237\Toolbox\Traits\OwnedByUser;

class PostStub extends Model
{
    use OwnedByUser;

    protected $table = 'posts';

    protected $guarded = ['id'];

    protected function ownerClass(): string
    {
        return UserStub::class;
    }
}
