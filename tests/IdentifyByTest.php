<?php

namespace Ludo237\Toolbox\Tests;

use Ludo237\Toolbox\QueryBuilders\IdentifyBy;
use Ludo237\Toolbox\Tests\Stubs\UserStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('query-builders'), CoversClass(IdentifyBy::class)]
class IdentifyByTest extends TestCase
{
    #[Test]
    public function it_fetches_model_by_identifiers()
    {
        $results = UserStub::query()->tap(new IdentifyBy(1))->get(['id']);

        $this->assertCount(0, $results);

        $model = UserStub::query()->create(['name' => 'foo bar']);
        $results = UserStub::query()->tap(new IdentifyBy($model->getKey()))->get(['id']);

        $this->assertCount(1, $results);
    }
}
