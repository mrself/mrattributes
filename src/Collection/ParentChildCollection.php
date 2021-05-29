<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace Mrself\Attributes\Collection;

use Mrself\Attributes\Entity\EntityInterface;
use Mrself\Attributes\Entity\HasParentInterface;
use Mrself\Attributes\Services\MultipleTreeEntities;

/**
 * @mixin Collection
 * @method HasParentInterface|EntityInterface firstFiltered(\Closure $closure)
 * @method ParentChildCollection|Collection onlyInCollection(Collection|ParentChildCollection $collection)
 */
trait ParentChildCollection
{
    /**
     * Returns entities which do not have children in the current collection
     * @return ParentChildCollection|Collection
     */
    public function onlyDeepest()
    {
        return $this->filter(function (HasParentInterface $attribute): bool {
            return !$this->getByParent($attribute);
        });
    }

    /**
     * @param HasParentInterface $parent
     * @return EntityInterface|null|HasParentInterface
     */
    public function getByParent(HasParentInterface $parent)
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
     * @return ParentChildCollection|Collection
     */
    public function getRootAttributes()
    {
        return $this->filter(function (HasParentInterface $entity) {
            return !$entity->getParent();
        });
    }

    /**
     * @return Collection[]|ParentChildCollection[]
     */
    public function findMultipleOneLevelAttributes(): array
    {
        return MultipleTreeEntities::from($this)->findMultipleOneLevelEntities();
    }
}
