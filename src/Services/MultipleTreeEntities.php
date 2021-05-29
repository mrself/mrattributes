<?php

namespace Mrself\Attributes\Services;

use Mrself\Attributes\Collection\Collection;
use Mrself\Attributes\Collection\ParentChildCollection;
use Mrself\Attributes\Entity\HasParentInterface;

class MultipleTreeEntities
{
    /**
     * @var Collection|ParentChildCollection
     */
    protected $collection;

    protected function __construct()
    {
    }

    /**
     * @param ParentChildCollection $collection
     * @return static
     */
    public static function from($collection)
    {
        $instance = new static();
        $instance->collection = $collection;
        return $instance;
    }

    /**
     * @return Collection[]|ParentChildCollection[]
     */
    public function findMultipleOneLevelEntities(): array
    {
        $groups = $this->findRootMultipleOneLevelEntities();
        foreach ($this->collection as $entity) {
            $groups = array_merge($groups, $this->findChildrenInCollection($entity));
        }

        return $groups;
    }

    protected function findChildrenInCollection(HasParentInterface $entity): array
    {
        $children = Collection::from($entity->getChildren());
        $foundChildren = $this->collection->onlyInCollection($children);
        if ($foundChildren->count() > 1) {
            return [$foundChildren];
        }

        return [];
    }

    private function findRootMultipleOneLevelEntities(): array
    {
        $roots = $this->collection->getRootAttributes();
        if ($roots->count() > 1) {
            return [$roots];
        }

        return [];
    }
}
