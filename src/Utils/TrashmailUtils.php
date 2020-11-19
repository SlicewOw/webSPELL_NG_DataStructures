<?php

namespace webspell_ng\Utils;

class TrashmailUtils {

    /**
     * @var string $TRASHMAIL_FILE_NAME
     */
    public const TRASHMAIL_FILE_NAME = 'trashmails.json';

    /**
     * @param array<string> $email_array
     */
    public static function isTrashEmail(array $email_array): bool
    {

        if (!($trashmail_json = @file_get_contents(__DIR__ . '/../../resources/' . self::TRASHMAIL_FILE_NAME))) {
            return TRUE;
        }

        $trashmailArray = json_decode($trashmail_json, TRUE);

        $mailArray = explode('.', $email_array[1]);
        $anzElements = count($mailArray);

        $mail_ext = $mailArray[$anzElements - 1];

        if (!isset($trashmailArray[$mail_ext])) {
            return TRUE;
        }

        if (in_array($email_array[1], $trashmailArray[$mail_ext]) || in_array('*.' . $mail_ext, $trashmailArray[$mail_ext])) {
            return FALSE;
        }

        return TRUE;

    }

}
