<?php

namespace Mrself\Attributes\Tests\Mocks;

use Doctrine\Common\Collections\Collection;
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

    public function getChildren()
    {

    }

    public function getId()
    {

    }

}
