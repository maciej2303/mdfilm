<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProjectComponentType extends Enum implements LocalizedEnum
{
    const BRIEF = "brief";
    const CREATIVE_CONCEPT =  "creative_concept";
    const SCENARIO = "scenario";
    const STORYBOARD = "storyboard";
    const RECORDINGS = "recordings";
    const ANIMATION = "animation";
    const MOVIE =  "movie";
}
