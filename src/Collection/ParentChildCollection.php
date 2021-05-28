<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace Mrself\Attributes\Collection;

use Mrself\Attributes\Entity\HasParentInterface;

trait ParentChildCollection
{
    /**
     * Returns entities which do not have children in the current collection
     * @return static
     */
    public function onlyDeepest()
    {
        return $this->filter(function (HasParentInterface $attribute): bool {
            return !$this->getByParent($attribute);
        });
    }

    public function getByParent(HasParentInterface $parent): ?HasParentInterface
    {
        return $this->firstFiltered(function (HasParentInterface $entity) use ($parent) {
            return $this->isParentEqualTo($entity, $parent);
        });
    }

    public function isParentEqualTo(HasParentInterface $entity, HasParentInterface $toCompare): bool
    {
        $parent = $entity->getParent();

        if (!$parent) {
            return false;
        }

        return $parent->getId() === $toCompare->getId();
    }

    /**
     * Returns entities without parent
     * @return static
     */
    public function getRootAttributes()
    {
        return $this->filter(function (HasParentInterface $entity) {
            return !$entity->getParent();
        });
    }

    /**
     * @return static
     */
    public function findMultipleOneLevelAttributes()
    {
        $attributes = $this->findOneLevelAttributes();
        return $attributes->count() ? $attributes : static::from();
    }

    /**
     * @return static
     */
    public function findOneLevelAttributes()
    {
        $attributes = $this->getNonRootAttributes();
        return $attributes->count() ? $attributes : $this->getRootAttributes();
    }

    /**
     * @return static
     */
    public function getNonRootAttributes()
    {
        $result = static::from();
        foreach ($this as $attribute) {
            /** @var HasParentInterface $attribute */
            $loadedChildren = $attribute->getChildren();
            $collection = static::from($loadedChildren);

            $definedChildren = $this->onlyInCollection($collection);
            if ($definedChildren->count() > 1) {
                $result->merge($definedChildren);
            }
        }
        return $result;
    }
}
