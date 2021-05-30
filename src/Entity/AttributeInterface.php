<?php

namespace Mrself\Attributes\Entity;

interface AttributeInterface extends HasParentInterface
{
    public function getName(): ?string;

    /**
     * @return GroupInterface|null
     */
    public function getGroup();
}
