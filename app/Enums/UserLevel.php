<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserLevel extends Enum implements LocalizedEnum
{
    const ADMIN = 'Admin';
    const WORKER = 'Worker';
    const CLIENT = 'Client';
}
