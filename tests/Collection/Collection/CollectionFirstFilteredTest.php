<?php

namespace Mrself\Attributes\Tests\Collection\Collection;

use Mrself\Attributes\Collection\Collection;
use Mrself\Attributes\Entity\EntityInterface;
use Mrself\Attributes\Tests\Test;

class CollectionFirstFilteredTest extends Test
{
    public function testItReturnsFirstFilteredElementWhenItExists()
    {
        $array = [
            new class implements EntityInterface {
                public function getId(): int
                {
                    return 1;
                }
            },

            new class implements EntityInterface {
                public function getId(): int
                {
                    return 2;
                }
            }
        ];

        $collection = Collection::from($array);
        $result = $collection->firstFiltered(function (EntityInterface $object): bool {
            return $object->getId() === 2;
        });

        $this->assertEquals(2, $result->getId());
    }
}
