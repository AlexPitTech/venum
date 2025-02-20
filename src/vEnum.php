<?php

namespace PitTech\vEnum;

#[\Attribute]
class vEnum
{
    public const UNPAID = null;

    public /* readonly */ string $name;
    public /* readonly */ string $value;

    public function __construct(
        public readonly mixed $payload = self::UNPAID,
        public readonly ?string $label = null,
        public readonly array $tags = [],
        public readonly bool $active = true,
    ){}

}
