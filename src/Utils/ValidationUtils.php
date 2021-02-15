<?php

namespace webspell_ng\Utils;

use \webspell_ng\Utils\TrashmailUtils;

class ValidationUtils {

    /**
     * @param array<mixed> $array
     */
    public static function validateArray(array $array, bool $checkForEmpty=true): bool
    {

        if ((!is_array($array)) || ($checkForEmpty && empty($array))) {
            return FALSE;
        }

        return TRUE;

    }

    public static function validateInteger(int $integer_value, bool $checkIfValueIsLessThenOne=true): bool
    {

        if ((!is_numeric($integer_value)) || ($checkIfValueIsLessThenOne && ($integer_value < 1))) {
            return FALSE;
        }

        return TRUE;

    }

    public static function validateUrl(string $url): bool
    {
        return preg_match(
            // @codingStandardsIgnoreStart
            "/^(ht|f)tps?:\/\/([^:@]+:[^:@]+@)?(?!\.)(\.?(?!-)[0-9\p{L}-]+(?<!-))+(:[0-9]{2,5})?(\/[^#\?]*(\?[^#\?]*)?(#.*)?)?$/sui",
            // @codingStandardsIgnoreEnd
            $url
        ) == 1;
    }

    public static function validateEmail(string $email): bool
    {

        if (empty($email)) {
            return FALSE;
        }

        if (!preg_match(
            // @codingStandardsIgnoreStart
            "/^(?!\.)(\.?[\p{L}0-9!#\$%&'\*\+\/=\?^_`\{\|}~-]+)+@(?!\.)(\.?(?!-)[0-9\p{L}-]+(?<!-))+\.[\p{L}0-9]{2,}$/sui",
            // @codingStandardsIgnoreEnd
            $email
        )) {
            return FALSE;
        }

        $emailArray = explode('@', $email);

        if ((count($emailArray) != 2) || (preg_match('/spam/i', $email))) {
            return FALSE;
        }

        return !TrashmailUtils::isTrashEmail($emailArray);

    }

}
