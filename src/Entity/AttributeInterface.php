<?php

namespace Mrself\Attributes\Entity;

interface AttributeInterface
{
    public function getId();

    public function getName(): string;
}