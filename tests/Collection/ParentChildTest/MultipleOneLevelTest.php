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

        };

        $root2 = new class extends HasParentEntity {
            public function getId(): int
            {
                return 2;
            }

        };

        $result = Collection::from([$root1, $root2])->findMultipleOneLevelAttributes();

        $this->assertCount(1, $result);
        $this->assertEquals(2, $result[0]->count());
    }

    public function testItReturnsMultipleEntitiesOnAllLevels()
    {
        $parent1 = new class extends HasParentEntity {
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

        $parent2 = new class extends HasParentEntity {
            public function getId(): int
            {
                return 2;
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
                return 3;
            }

        };

        $childrenParent = new class extends HasParentEntity {
            public $parent;

            public DoctrineCollection $children;

            public function getParent()
            {
                return $this->parent;
            }

            public function getId(): int
            {
                return 4;
            }

            public function getChildren(): DoctrineCollection
            {
                return $this->children;
            }
        };

        $parent1->children = new ArrayCollection([$child1, $childrenParent]);
        $child1->parent = $parent1;
        $childrenParent->parent = $parent1;

        $child2 = new class extends HasParentEntity {
            public $parent;

            public function getParent()
            {
                return $this->parent;
            }

            public function getId(): int
            {
                return 5;
            }

        };

        $child3 = new class extends HasParentEntity {
            public $parent;

            public function getParent()
            {
                return $this->parent;
            }

            public function getId(): int
            {
                return 6;
            }

        };

        $childrenParent->children = new ArrayCollection([$child2, $child3]);
        $child2->parent = $childrenParent;
        $child3->parent = $childrenParent;


        $entities = [
            $parent1,
            $parent2,
            $child1,
            $childrenParent,
            $child2,
            $child3,
        ];
        $result = Collection::from($entities)->findMultipleOneLevelAttributes();

        $this->assertCount(3, $result);

        $roots = $result[0];
        $this->assertEquals(1, $roots[0]->getId());
        $this->assertEquals(2, $roots[1]->getId());

        $secondLevel = $result[1];
        $this->assertEquals(3, $secondLevel[0]->getId());
        $this->assertEquals(4, $secondLevel[1]->getId());

        $thirdLevel = $result[2];
        $this->assertEquals(5, $thirdLevel[0]->getId());
        $this->assertEquals(6, $thirdLevel[1]->getId());
    }
}

class Collection extends AppCollection
{
    use ParentChildCollection;
}
