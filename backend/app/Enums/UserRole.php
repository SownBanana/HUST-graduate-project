<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserRole extends Enum
{
    const Admin = 0;
    const Mod = 1;
    const Teacher = 2;
    const Student = 3;
}
