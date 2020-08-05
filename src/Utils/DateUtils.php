<?php

namespace webspell_ng\Utils;

class DateUtils {

    public static function getDateTimeByMktimeValue(int $mktime_value): \DateTime
    {
        return new \DateTime(
            date("Y-m-d H:i:s", $mktime_value)
        );
    }

}
