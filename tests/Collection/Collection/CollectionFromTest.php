<?php

namespace Mrself\Attributes\Tests\Collection\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Mrself\Attributes\Collection\Collection;
use Mrself\Attributes\Entity\EntityInterface;
use Mrself\Attributes\Tests\Test;

class CollectionFromTest extends Test
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

    public function testItCreatesInstanceByCollection()
    {
        $entity = new class implements EntityInterface {
            public function getId(): int
            {
                return 1;
            }
        };

        $arrayCollection = new ArrayCollection([$entity]);
        $collection = Collection::from($arrayCollection);
        $this->assertEquals(1, $collection->first()->getId());
    }
}
