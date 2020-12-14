<?php

namespace webspell_ng\Utils;


class StringFormatterUtils {

    public static function getInput(string $input_string, bool $onlyCharAllowed=false): string
    {

        $text = str_replace(
            array('\r', '\n'),
            array("", ""),
            $input_string
        );

        $text = trim($text);
        $text = stripslashes($text);
        $text = htmlspecialchars($text, ENT_QUOTES);

        if ($onlyCharAllowed) {

            $tmpText = preg_replace(
                "/[^a-zA-Z \-\_]/",
                "",
                $text
            );

            if ($tmpText != $text) {
                return '';
            }

        }

        return $text;

    }

    public static function getTextFormattedForDatabase(string $input_string): string
    {

        $text = nl2br($input_string);
        $text = trim($text);
        $text = stripslashes($text);
        $text = htmlspecialchars($text, ENT_QUOTES);
        return str_replace(
            array('&lt;', '&gt;'),
            array('<', '>'),
            $text
        );

    }

    public static function convert2filename(string $name, bool $setDateAsPrefix = false, bool $setTimeAsPrefix = false): string
    {

        $searchArray = array( ' ',  'ä',  'ö',  'ü',  'ß', '?', '!', ',', '.', '#', '%', '&', '\'', '+', '<', '>', '`', '\´');
        $replaceArray = array('_', 'ae', 'oe', 'ue', 'ss',  '',  '',  '',  '',  '',  '',  '',   '',  '',  '',  '',  '',   '');

        $returnValue = str_replace(
            $searchArray,
            $replaceArray,
            strtolower($name)
        );

        if ($setTimeAsPrefix) {
            $returnValue = date('H-i-s') . '_' . $returnValue;
        }

        if ($setDateAsPrefix) {
            $returnValue = date('Y-m-d') . '_' . $returnValue;
        }

        return $returnValue;

    }

    public static function getRandomString(int $length, int $type = 0): string
    {

        /* Randpass: Generates an random password
        Parameter:
        length - length of the password string
        type - there are 4 types: 0 - all chars, 1 - numeric only, 2 - upper chars only, 3 - lower chars only
        Example:
        echo getRandomString(7, 1); => 0917432
        */
        $pass = '';
        for ($i = 0; $i < $length; $i++) {

            if ($type == 0) {
                $rand = random_int(1, 3);
            } else {
                $rand = $type;
            }

            switch ($rand) {
                case 2:
                    $pass .= chr(random_int(65, 90));
                    break;
                case 3:
                    $pass .= chr(random_int(97, 122));
                    break;
                default:
                    $pass .= chr(random_int(48, 57));
                    break;
            }
        }

        return $pass;

    }

    public static function convertToYoutubeId(string $id): string
    {
        return self::convert2id(
            $id,
            array(
                'https://www.youtube.com/watch?v=',
                'https://www.youtube.de/watch?v=',
                'https://www.youtube.com/',
                'https://www.youtube.de/',
                'http://youtube.com/',
                'https://youtube.com/',
                'http://youtube.de/',
                'https://youtube.de/',
                'http://youtu.be/',
                'https://youtu.be/',
                'watch?v='
            )
        );
    }

    public static function convertToYoutubeLiveId(string $id): string
    {
        return self::convert2id(
            $id,
            array(
                'http://gaming.youtube.com/user/',
                'https://gaming.youtube.com/user/',
                'http://gaming.youtube.com/',
                'https://gaming.youtube.com/',
                'http://gaming.youtube.de/',
                'https://gaming.youtube.de/',
                'watch?v='
            )
        );
    }

    public static function convertToFacebookId(string $id): string
    {
        return self::convert2id(
            $id,
            array(
                'http://www.facebook.com/',
                'https://www.facebook.com/',
                'http://www.facebook.de/',
                'https://www.facebook.de/',
                'http://facebook.com/',
                'https://facebook.com/',
                'http://facebook.de/',
                'https://facebook.de/',
                'http://fb.com/',
                'https://fb.com/',
                'http://fb.de/',
                'https://fb.de/',
                'facebook.com/',
                'facebook.de/',
                'fb.com/',
                'fb.de/'
            )
        );
    }

    public static function convertToTwitterId(string $id): string
    {
        return self::convert2id(
            $id,
            array(
                'http://twitter.com/',
                'https://twitter.com/',
                'http://www.twitter.com/',
                'https://www.twitter.com/',
                'http://twitter.de/',
                'https://twitter.de/',
                'http://www.twitter.de/',
                'https://www.twitter.de/',
                'twitter.com/',
                'twitter.de/',
            )
        );
    }

    public static function convertToTwitchId(string $id): string
    {
        return self::convert2id(
            $id,
            array(
                'http://twitch.com/',
                'http://twitch.de/',
                'https://twitch.com/',
                'https://twitch.de/',
                'http://twitch.tv/',
                'https://twitch.tv/',
                'http://go.twitch.tv/',
                'http://www.twitch.com/',
                'http://www.twitch.de/',
                'https://www.twitch.com/',
                'https://www.twitch.de/',
                'http://www.twitch.tv/',
                'https://www.twitch.tv/',
                'https://go.twitch.tv/'
            )
        );
    }

    /**
     * @param array<string> $search_array
     */
    private static function convert2id(string $id, array $search_array): string
    {

        $id = stripslashes($id);

        return str_replace(
            $search_array,
            '',
            $id
        );

    }

    public static function convertStringToPrizeValue(string $prize): string
    {

        $text = str_replace(
            ',',
            '.',
            $prize
        );

        $textArray = explode('.', $text);

        $count_of_strings = count($textArray);
        if ($count_of_strings == 1) {
            $text .= '.00';
        } else if ($count_of_strings == 2) {
            $second_value = (strlen($textArray[1]) > 1) ? $textArray[1] : $textArray[1] . '0';
            $text = $textArray[0] . '.' . $second_value;
        } else {
            throw new \InvalidArgumentException("prize_value_is_invalid");
        }

        return $text;

    }

}
