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
    const ChatRoom = 'App\Models\User';
    const CourseComment = 'App\Models\Course';
    const LessonComment = 'App\Models\Lesson';
    const LiveLessonComment = 'App\Models\LiveLesson';
}
