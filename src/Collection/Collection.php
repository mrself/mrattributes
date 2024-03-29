<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace Mrself\Attributes\Collection;

use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\Common\Collections\Criteria;
use Mrself\Attributes\Entity\EntityInterface;

class Collection extends ArrayCollection
{
    /**
     * @param DoctrineCollection|array $source
     * @return static
     */
    public static function from($source = [])
    {
        if ($source instanceof DoctrineCollection) {
            $source = $source->toArray();
        }

        return new static($source);
    }

    /**
     * Returns the first element of filtered result or null
     * @param Closure $filterCallback
     * @return EntityInterface
     */
    public function firstFiltered(Closure $filterCallback): ?EntityInterface
    {
        return $this->filter($filterCallback)->first() ?: null;
    }

    /**
     * Check if the provided entity exists in this collection
     * @param EntityInterface $entity
     * @return bool
     */
    public function has(EntityInterface $entity): bool
    {
        return $this->hasById($entity->getId());
    }

    public function hasById(int $id): bool
    {
        return (bool) $this->find($id);
    }

    /**
     * @param array $by
     * @return static
     */
    public function findBy(array $by)
    {
        $expr = Criteria::expr();
        $eqExpr = [];
        foreach ($by as $key => $value) {
            $eqExpr[] = $expr->eq($key, $value);
        }
        $whereExpr = call_user_func_array([$expr, 'andX'], $eqExpr);
        $criteria = new Criteria($whereExpr);
        return $this->matching($criteria);
    }

    public function findOneBy(array $by)
    {
        return $this->findBy($by)->first() ?: null;
    }

    /**
     * @param string|int $id
     * @return EntityInterface
     */
    public function find($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @return static
     */
    public function toIds()
    {
        return $this->map(function (EntityInterface $entity) {
            return $entity->getId();
        });
    }

    /**
     * @return string[]|int[]
     */
    public function toArrayOfIds(): array
    {
        return $this->toIds()->toArray();
    }

    /**
     * @param Collection $collection
     * @return static
     */
    public function onlyInCollection(Collection $collection)
    {
        $filtered = $this->filter(function (EntityInterface $entity) use ($collection) {
            return $collection->has($entity);
        });

        return static::from(array_values($filtered->toArray()));
    }

    public function notInCollection(Collection $collection)
    {
        $filtered = $this->filter(function (EntityInterface $entity) use ($collection) {
            return !$collection->has($entity);
        });

        return static::from(array_values($filtered->toArray()));
    }

    /**
     * @param Collection|array $entities
     * @return bool
     */
    public function matchAny($entities): bool
    {
        foreach ($entities as $entity) {
            if ($this->has($entity)) {
                return true;
            }
        }

        return false;
    }

    public function merge($source)
    {
        foreach ($source as $item) {
            $this->add($item);
        }

        return $this;
    }
}
