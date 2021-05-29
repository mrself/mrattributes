<?php

namespace Mrself\Attributes\Tests\Mocks;

use Doctrine\Common\Collections\Collection;
use Mrself\Attributes\Entity\HasParentInterface;

class HasParentEntity implements HasParentInterface
{
    public function getParent(): HasParentInterface
    {

    }

    public function getChildren(): Collection
    {

    }

    public function getId()
    {

    }

}
