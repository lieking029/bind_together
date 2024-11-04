<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserTypeEnum extends Enum
{
    const SUPERADMIN = 'super_admin';
    const ADMINSPORT = 'admin_sport';
    const ADMINORG = 'admin_org';
    const COACH = 'coach';
    const ADVISER = 'adviser';
    const STUDENT = 'student';
}
