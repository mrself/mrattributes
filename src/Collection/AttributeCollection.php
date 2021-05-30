<?php

namespace Mrself\Attributes\Collection;

use Mrself\Attributes\Entity\AttributeInterface;

class AttributeCollection extends Collection
{
    use ParentChildCollection;

    public function group(): array
    {
        $grouped = [];
        foreach ($this as $attribute) {
            /** @var AttributeInterface $attribute */
            $groupId = $attribute->getGroup()->getId();

            if (!isset($grouped[$groupId])) {
                $grouped[$groupId] = AttributeCollection::from();
            }

            $grouped[$groupId][] = $attribute;
        }
        return $grouped;
    }
}
