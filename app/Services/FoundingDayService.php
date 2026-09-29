<?php

namespace App\Services;

use Carbon\Carbon;

class FoundingDayService
{
    // Pivot was founded on 24 May 2019
    public const FOUNDING_MONTH = 5;
    public const FOUNDING_DAY   = 24;
    public const FOUNDING_YEAR  = 2019;

    public function isFoundingDay(?Carbon $date = null): bool
    {
        $today = $date ?? Carbon::today();
        return $today->month === self::FOUNDING_MONTH
            && $today->day   === self::FOUNDING_DAY;
    }

    public function getYearsSinceFounding(?Carbon $date = null): int
    {
        $today = $date ?? Carbon::today();
        return $today->year - self::FOUNDING_YEAR;
    }
}
