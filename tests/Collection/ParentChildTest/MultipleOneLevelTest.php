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
            public $parent;

            public function getParent()
            {
                return $this->parent;
            }

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
            public $parent;

            public function getParent()
            {
                return $this->parent;
            }

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
        $child1->parent = $parent;
        $child2->parent = $parent;
        $result = Collection::from([$parent, $child1, $child2])->findMultipleOneLevelAttributes();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(AppCollection::class, $result[0]);
    }

    public function testItReturnsEmptyCollectionIfParentHasOnlyOneChild()
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
            public HasParentEntity $parent;

            public function getId(): int
            {
                return 2;
            }

            public function getParent()
            {
                return $this->parent;
            }

            public function getChildren(): DoctrineCollection
            {
                return new ArrayCollection([]);
            }
        };
        $child1->parent = $parent;

        $parent->children = new Collection([$child1]);
        $result = Collection::from([$parent, $child1])->findMultipleOneLevelAttributes();

        $this->assertEmpty($result);
    }

    public function testItReturnsRootsIfThereMoreThanOne()
    {
        $root1 = new class extends HasParentEntity {
            public DoctrineCollection $children;

            public function getId(): int
            {
                return 1;
            }

            public function getChildren(): DoctrineCollection
            {
                return new ArrayCollection([]);
            }
        };

        $root2 = new class extends HasParentEntity {
            public function getId(): int
            {
                return 2;
            }

            public function getChildren(): DoctrineCollection
            {
                return new ArrayCollection([]);
            }
        };

        $result = Collection::from([$root1, $root2])->findMultipleOneLevelAttributes();

        $this->assertCount(1, $result);
        $this->assertEquals(2, $result[0]->count());
    }
}

class Collection extends AppCollection
{
    use ParentChildCollection;
}
