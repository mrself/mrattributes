<?php

namespace Mrself\Attributes\Entity;

interface AttributeInterface extends HasParentInterface
{
    public function getName(): ?string;

    public function getGroup(): GroupInterface;
}
