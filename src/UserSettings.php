<?php

namespace webspell_ng;

use webspell_ng\Handler\UserHandler;
use webspell_ng\UserSession;


class UserSettings {

    /**
     * @var string $DEFAULT_DATE_FORMAT
     */
    private static $DEFAULT_DATE_FORMAT = "Y-m-d";

    /**
     * @var string $DEFAULT_TIME_FORMAT
     */
    private static $DEFAULT_TIME_FORMAT = "H:i:s";

    public static function getDateFormat(): string
    {
        $date_format = UserHandler::getDataOfUserByUserId(
            UserSession::getUserId(),
            'date_format'
        );
        if (is_null($date_format)) {
            return self::$DEFAULT_DATE_FORMAT;
        }
        return $date_format;
    }

    public static function getTimeFormat(): string
    {
        $time_format = UserHandler::getDataOfUserByUserId(
            UserSession::getUserId(),
            'time_format'
        );
        if (is_null($time_format)) {
            return self::$DEFAULT_TIME_FORMAT;
        }
        return $time_format;
    }

}
