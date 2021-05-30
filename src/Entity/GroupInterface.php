<?php

namespace Mrself\Attributes\Entity;

interface GroupInterface extends EntityInterface
{
    public function getName(): ?string;

    public function getAttributes();
}
