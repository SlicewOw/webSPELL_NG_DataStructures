<?php

namespace webspell_ng\Utils;

use webspell_ng\UserSettings;

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

    public static function getFormattedDate(\DateTime $date_time): string
    {
        return $date_time->format(
            UserSettings::getDateFormat()
        );
    }

    public static function getFormattedTime(\DateTime $date_time): string
    {
        return $date_time->format(
            UserSettings::getTimeFormat()
        );
    }

    public static function getFormattedDateTime(\DateTime $date_time): string
    {
        return $date_time->format(
            UserSettings::getDateFormat() . ' ' . UserSettings::getTimeFormat()
        );
    }

}
