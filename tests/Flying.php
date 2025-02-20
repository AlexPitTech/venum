<?php

namespace PitTech\vEnum\Tests;

use PitTech\vEnum\vEnum;

interface Flying
{

    const string ANIMALS = 'animals';
    const string MECHANICAL = 'mechanical';

    #[vEnum(33, tags: [self::ANIMALS])]
    const int HAWK = 1;

    #[vEnum(34, tags: [self::MECHANICAL])]
    const int AIRPLANE = 2;

    #[vEnum(45, tags: [self::MECHANICAL], active: false)]
    const int UFO = 3;

}