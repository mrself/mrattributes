<?php

namespace Mrself\Attributes\Tests\Mocks;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Mrself\Attributes\Entity\HasParentInterface;

class HasParentEntity implements HasParentInterface
{
    /**
     * @return HasParentInterface
     */
    public function getParent()
    {
        return null;
    }

    public function getChildren(): DoctrineCollection
    {
        return new ArrayCollection([]);
    }

    public function getId()
    {

    }

}
