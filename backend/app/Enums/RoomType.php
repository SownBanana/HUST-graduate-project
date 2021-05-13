<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RoomType extends Enum
{
    const ChatRoom = 0;
    const CourseComment = 1;
    const LessonComment = 2;
    const LiveLessonComment = 3;
}
