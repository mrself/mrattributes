<?php

namespace Mrself\Attributes\Services;

use Mrself\Attributes\Collection\AttributeCollection;

class EntityMatcher
{
    private AttributeCollection $sourceCollection;

    private AttributeCollection $targetCollection;

    protected function __construct()
    {
    }

    public static function make(AttributeCollection $sourceCollection, AttributeCollection $targetCollection)
    {
        $instance = new static();
        $instance->sourceCollection = $sourceCollection;
        $instance->targetCollection = $targetCollection;
        return $instance;
    }

    public function checkMatch(): bool
    {
        if ($this->sourceCollection->isEmpty() && $this->targetCollection->isEmpty()) {
            return true;
        }

        $orConditionAttributes = $this->targetCollection->findMultipleOneLevelAttributes();
        $orConditionCollections = AttributeCollection::from();
        foreach ($orConditionAttributes as $collection) {
            $orConditionCollections->merge($collection);
        }
        $andConditionAttributes = $this->targetCollection->notInCollection($orConditionCollections);

        if (!$this->sourceMatchesAndAttributes($andConditionAttributes)) {
            return false;
        }

        return $this->sourceMatchesOrAttributes($orConditionAttributes);
    }

    protected function sourceMatchesOrAttributes(array $orConditionAttributes): bool
    {
        $groupsCount = count($orConditionAttributes);
        foreach ($orConditionAttributes as $attributes) {
            if ($this->sourceCollection->matchAny($attributes)) {
                $groupsCount--;
            }
        }
        return $groupsCount === 0;
    }

    protected function sourceMatchesAndAttributes(AttributeCollection $andConditionAttributes): bool
    {
        $andConditionIds = $andConditionAttributes->toArrayOfIds();
        $sourceIds = $this->sourceCollection->toArrayOfIds();

        return !array_diff($andConditionIds, $sourceIds);
    }
}
