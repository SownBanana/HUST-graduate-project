<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CourseType extends Enum
{
    const Draft = 0;
    const Publish = 1;
    const NORMAL = 2;
    const LIVE = 3;
}
