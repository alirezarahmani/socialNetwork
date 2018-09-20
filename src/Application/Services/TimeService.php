<?php
declare(strict_types=1);
namespace SocialNetwork\Application\Services;

use SocialNetwork\Infrastructure\Exceptions\InvalidArgumentException;

class TimeService implements ApplicationServiceInterface
{

    const SECOND = 1;
    const MINUTE = 60;
    const HOUR = 3600;
    const DAY = 86400;
    const WEEK = 604800;
    const MONTH = 2592000;
    const YEAR = 31104000;

    const NAMES = [
        self::SECOND => 'second',
        self::MINUTE => 'minute',
        self::HOUR => 'hour',
        self::DAY => 'day',
        self::WEEK => 'week',
        self::MONTH => 'month',
        self::YEAR => 'year',
    ];

    public function elapsed($time):string
    {
        $time = time() - $time;
        $time = ($time<1)? 1 : $time;
        $tokens = self::NAMES;
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) {
                continue;
            }
            $numberOfUnits = floor($time / $unit);
            return '(' . $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'') . ' ago)';
        }
        throw new InvalidArgumentException('no valid time');
    }
}
