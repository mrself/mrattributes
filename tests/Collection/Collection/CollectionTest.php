<?php

namespace Mrself\Attributes\Tests\Collection\Collection;

use Mrself\Attributes\Collection\Collection;
use Mrself\Attributes\Entity\EntityInterface;
use Mrself\Attributes\Tests\Test;

class CollectionTest extends Test
{
    public function testItCreatesInstanceByArray()
    {
        $entity = new class implements EntityInterface {
            public function getId(): int
            {
                return 1;
            }
        };

        $collection = Collection::from([$entity]);
        $this->assertEquals(1, $collection->first()->getId());
    }
}
