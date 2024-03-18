<?php

namespace Ludo237\Toolbox\Tests\Stubs;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Ludo237\Toolbox\Traits\Bannable;
use Ludo237\Toolbox\Traits\CanBeActivate;
use Ludo237\Toolbox\Traits\ExposeTableProperties;
use Ludo237\Toolbox\Traits\HasSlug;

class UserStub extends Model implements Authenticatable
{
    use AuthenticatableTrait, Bannable, CanBeActivate, ExposeTableProperties, HasSlug;

    protected $table = 'users';

    protected $guarded = ['id'];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
