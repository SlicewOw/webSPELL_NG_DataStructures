<?php

/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

namespace webspell_ng;

use Respect\Validation\Validator;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\POP3;

use webspell_ng\Handler\SettingsHandler;
use webspell_ng\WebSpellDatabaseConnection;

class Email
{

    /** @var string $host */
    private static $host;

    /** @var string $user */
    private static $user;

    /** @var string $password */
    private static $password;

    /** @var int $port */
    private static $port;

    /** @var bool $auth */
    private static $auth;

    /** @var bool $html */
    private static $html;

    /** @var int $smtp */
    private static $smtp;

    /** @var int $secure */
    private static $secure;

    /** @var int $debug */
    private static $debug = 0;

    /**
     * @return array{result: string, error: ?string, debug: ?string}
     */
    public static function sendEmail(string $from, string $module, string $to, string $subject, string $message): array
    {

        self::setMailSettings();

        $settings = SettingsHandler::getSettings();

        $GLOBALS['mail_debug'] = '';

        $mail = self::getMailDefaultBody();

        $mail->Subject = $subject;
        $mail->setFrom($from, $settings->getHomepageTitle() . ' - (' . $module . ')');
        $mail->addAddress($to);
        $mail->addReplyTo($from);
        $mail->CharSet = 'UTF-8';
        $mail->WordWrap = 78;

        if (self::$html == 1) {
            $mail->isHTML(true);
            $mail->msgHTML($message);
        } else {
            $mail->isHTML(false);
            $plain = $mail->html2text($message);
            $mail->Body = $plain;
            $mail->AltBody = $plain;
        }

        if (!$mail->send()) {
            if (!self::$debug) {
                return array("result" => "fail", "error" => $mail->ErrorInfo, "debug" => null);
            } else {
                return array("result" => "fail", "error" => $mail->ErrorInfo, "debug" => $GLOBALS['mail_debug']);
            }
        } else {
            if (!self::$debug) {
                return array("result" => "done", "error" => null, "debug" => null);
            } else {
                return array("result" => "done", "error" => null, "debug" => $GLOBALS['mail_debug']);
            }
        }

    }

    private static function getMailDefaultBody(): PHPMailer
    {

        if (self::$smtp == 0) {
            self::$debug = 0;
        }

        $mail = new PHPMailer;

        $mail->SMTPDebug = self::$debug;
        $mail->Debugoutput = function ($str, $level) {
            $GLOBALS['mail_debug'] .= $str . ' - ' . $level . '<br />';
        };

        if (self::$smtp == 1) {
            $mail->isSMTP();
            $mail->Host = self::$host;
            $mail->Port = self::$port;
            if (self::$auth == 1) {
                $mail->SMTPAuth = true;
                $mail->Username = self::$user;
                $mail->Password = self::$password;
            } else {
                $mail->SMTPAuth = false;
            }

            if (extension_loaded('openssl')) {
                switch (self::$secure) {
                    case 0:
                        $mail->SMTPSecure = '';
                        break;
                    case 1:
                        $mail->SMTPSecure = 'tls';
                        break;
                    case 2:
                        $mail->SMTPSecure = 'ssl';
                        break;
                    default:
                        $mail->SMTPSecure = '';
                        break;
                }
            } else {
                $mail->SMTPSecure = '';
            }
        } else {
            $mail->isMail();
        }

        return $mail;

    }

    private static function setMailSettings(): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder->select('*')->from(WebSpellDatabaseConnection::getTablePrefix() . 'email');

        $email_query = $queryBuilder->execute();
        $get = $email_query->fetch();

        if (!Validator::arrayType()->validate($get)) {
            throw new \InvalidArgumentException("email_settings_cannot_be_set");
        }

        self::$host = isset($get['host']) ? $get['host'] : null;
        self::$user = isset($get['user']) ? $get['user'] : null;
        self::$password = isset($get['password']) ? $get['password'] : null;
        self::$port = isset($get['port']) ? $get['port'] : null;
        self::$debug = isset($get['debug']) ? $get['debug'] : 0;
        self::$auth = (isset($get['auth']) && $get['auth'] == 1);
        self::$html = (isset($get['html']) && $get['html'] == 1);
        self::$smtp = isset($get['smtp']) ? $get['smtp'] : null;
        self::$secure = isset($get['secure']) ? $get['secure'] : null;

    }

}
