<?php

namespace Mrself\Attributes\Entity;

use Doctrine\Common\Collections\Collection;
use Mrself\Attributes\Collection\ParentChildCollection;

interface HasParentInterface extends EntityInterface
{
    /**
     * @return HasParentInterface
     */
    public function getParent();

    /**
     * @return Collection|ParentChildCollection
     */
    public function getChildren();
}
