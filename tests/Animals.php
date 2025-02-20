<?php

namespace PitTech\vEnum\Tests;

use PitTech\vEnum\EnumTrait;
use PitTech\vEnum\vEnum;

enum Animals: string
{
    use EnumTrait;

    const SPOTTED = 'spotted';
    const HAS_MANE = 'hasMane';
    const CATS = 'cats';
    const GIRAFFIDAE = 'giraffidae';

    #[vEnum(123, tags: [self::CATS, self::HAS_MANE])]
    case LEO = 'leo';

    #[vEnum(124, tags: [self::CATS, self::SPOTTED])]
    case JAGUAR = 'jaguar';

    #[vEnum(125, tags: [self::CATS])]
    case PANTHER = 'panther';

    #[vEnum(126, 'Guepard', [self::CATS, self::SPOTTED])]
    case GUEPARD = 'guepard';

    #[vEnum(127, tags: [self::GIRAFFIDAE, self::SPOTTED])]
    case GIRAFFE = 'giraffe';

}