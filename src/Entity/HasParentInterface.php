<?php

namespace Mrself\Attributes\Entity;

use Doctrine\Common\Collections\Collection;

interface HasParentInterface extends EntityInterface
{
    public function getParent(): HasParentInterface;

    public function getChildren(): Collection;
}
