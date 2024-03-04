<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class EventStatus extends Enum
{
    const Red = "Red";
    const Amber = "Amber";
    const Green = "Green";
    const Blue = "Blue";
}
