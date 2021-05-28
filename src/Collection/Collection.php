<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace Mrself\Attributes\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Mrself\Attributes\Entity\EntityInterface;

class Collection extends ArrayCollection
{
    /**
     * @param \Doctrine\Common\Collections\Collection|array $source
     * @return static
     */
    public static function from($source = [])
    {
        if ($source instanceof Collection) {
            $source = $source->toArray();
        }

        return new static($source);
    }

    /**
     * Returns the first element of filtered result or null
     * @param callable $filterCallback
     * @return ?EntityInterface
     */
    public function firstFiltered(callable $filterCallback): ?EntityInterface
    {
        $first = $this->filter($filterCallback)->first();
        return $first ?: null;
    }

    public function has($entity): bool
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
        return $this->findBy($by)->first();
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

    public function onlyInCollection(Collection $collection)
    {
        return $this->filter(function (EntityInterface $entity) use ($collection) {
            return $collection->has($entity);
        });
    }
}
