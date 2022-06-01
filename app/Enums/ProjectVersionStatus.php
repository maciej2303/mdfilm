<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProjectVersionStatus extends Enum implements LocalizedEnum
{
    const PENDING = "pending";
    const IN_PROGRESS =  "in progress";
    const TO_ACCEPT = "to accept";
    const COMMENTS = "comments";
    const ACCEPTED = "accepted";
    const CLOSED = "closed";
    const SUSPENDED =  "suspended";
    const CANCELED = "canceled";
}
