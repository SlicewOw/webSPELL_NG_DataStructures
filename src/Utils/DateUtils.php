<?php

namespace webspell_ng\Utils;

class DateUtils {

    public static function getDateTimeByMktimeValue(int $mktime_value): \DateTime
    {
        return new \DateTime(
            date("Y-m-d H:i:s", $mktime_value)
        );
    }

    public static function daysSinceTimestamp(int $time): int
    {

        if (!is_numeric($time) || ($time == 0)) {
            return -1;
        }

        $timeNow = time();

        $timeDiff = $timeNow - $time;

        return (int) ($timeDiff / 60 / 60 / 24);

    }

}
