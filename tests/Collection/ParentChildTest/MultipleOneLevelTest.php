<?php

namespace Mrself\Attributes\Tests\Collection\ParentChildTest;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Mrself\Attributes\Collection\Collection as AppCollection;
use Mrself\Attributes\Collection\ParentChildCollection;
use Mrself\Attributes\Tests\Mocks\HasParentEntity;
use Mrself\Attributes\Tests\Test;

class MultipleOneLevelTest extends Test
{
    public function testBase()
    {
        $parent = new class extends HasParentEntity {
            public DoctrineCollection $children;

            public function getId(): int
            {
                return 1;
            }

            public function getChildren(): DoctrineCollection
            {
                return $this->children;
            }
        };

        $child1 = new class extends HasParentEntity {
            public function getId(): int
            {
                return 2;
            }

            public function getChildren(): DoctrineCollection
            {
                return new ArrayCollection([]);
            }
        };

        $child2 = new class extends HasParentEntity {
            public function getId(): int
            {
                return 3;
            }

            public function getChildren(): DoctrineCollection
            {
                return new ArrayCollection([]);
            }
        };

        $parent->children = new ArrayCollection([$child1, $child2]);
        $result = Collection::from([$parent, $child1, $child2])->findMultipleOneLevelAttributes();

        $this->assertCount(2, $result);
        $this->assertEquals(2, $result[0]->getId());
        $this->assertEquals(3, $result[1]->getId());
    }
}

class Collection extends AppCollection
{
    use ParentChildCollection;
}
