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

use webspell_ng\Language;
use webspell_ng\WebSpellDatabaseConnection;

class Captcha
{

    /**
     * @var string $hash
     */
    private $hash;

    /**
     * @var int $length
     */
    private $length = 5;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var int $noise
     */
    private $noise = 100;

    /**
     * @var int $linenoise
     */
    private $linenoise = 10;

    /**
     * @var int $valide_time
     */
    private $valide_time = 20; /* captcha or transaction is valide for x minutes */

    /**
     * @var bool $math
     */
    private $math;

    /**
     * @var int $math_max
     */
    private $math_max = 30;

    /**
     * @var array{r: int, g: int, b: int} $bgcol
     */
    private $bgcol = array("r" => 255, "g" => 255, "b" => 255);

    /**
     * @var array{r: int, g: int, b: int} $fontcol
     */
    private $fontcol = array("r" => 0, "g" => 0, "b" => 0);

    /**
     * @return array{r: int, g: int, b: int}
     */
    private function hex2rgb(string $col): array
    {
        $col = str_replace("#", "", $col);
        $int = hexdec($col);
        return array(
            "r" => 0xFF & $int >> 0x10,
            "g" => 0xFF & ($int >> 0x8),
            "b" => 0xFF & $int
        );
    }

    /* constructor: set captcha type */
    public function __construct()
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('captcha_math', 'captcha_bgcol', 'captcha_fontcol', 'captcha_type', 'captcha_noise', 'captcha_linenoise')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'settings');

        $settings_query = $queryBuilder->execute();
        $ds = $settings_query->fetch();

        if (empty($ds)) {
            throw new \InvalidArgumentException('unknown_settings');
        }

        if (mb_strlen($ds[ 'captcha_bgcol' ]) == 7) {
            $this->bgcol = $this->hex2rgb($ds[ 'captcha_bgcol' ]);
        }

        if (mb_strlen($ds[ 'captcha_fontcol' ]) == 7) {
            $this->fontcol = $this->hex2rgb($ds[ 'captcha_fontcol' ]);
        }

        if ($ds[ 'captcha_math' ] == 1) {
            $this->math = true;
        } else if ($ds[ 'captcha_math' ] == 2) {
            $this->math = (rand(0, 1) == 1);
        } else {
            $this->math = false;
        }

        if ($ds[ 'captcha_type' ] == 1) {
            $this->type = 'g';
        } else if (function_exists('imagecreatetruecolor') && ($ds[ 'captcha_type' ] == 2)) {
            $this->type = 'g';
        } else {
            $this->type = 't';
        }

        $this->noise = $ds[ 'captcha_noise' ];
        $this->linenoise = $ds[ 'captcha_linenoise' ];

        $this->clearOldCaptcha();
    }

    public function setMath(bool $math): void
    {
        $this->math = $math;
    }

    /**
     * @return array{text: string, result: string}
     */
    private function generateCaptchaText(): array
    {
        $captcha_shown = "";
        if ($this->math == 1) {
            $this->length = 6;
            $first = rand(1, $this->math_max);
            $catpcha_result = $first;
            while (mb_strlen($first) < mb_strlen((string) $this->math_max)) {
                $first = ' ' . $first;
            }
            $captcha_shown = (string)$first;
            if (rand(0, 1)) {
                $captcha_shown .= "+";
                $next = rand(1, $this->math_max);
                $catpcha_result += $next;
            } else {
                $captcha_shown .= "-";
                $next = rand(1, $first - 1);
                $catpcha_result -= $next;
            }
            while (mb_strlen($next) < mb_strlen((string) $this->math_max)) {
                $next = ' ' . $next;
            }
            $captcha_shown .= $next;
            $captcha_shown .= "=";
        } else {
            for ($i = 0; $i < $this->length; $i++) {
                $int = rand(0, 9);
                $captcha_shown .= $int;
            }
            $catpcha_result = $captcha_shown;
        }
        return array('text' => $captcha_shown, 'result' => $catpcha_result);
    }

    private function createCatpchaImage(string $text): string
    {

        $imgziel = imagecreatetruecolor(($this->length * 15) + 10, 25);
        $bgcolor = imagecolorallocate($imgziel, $this->bgcol[ 'r' ], $this->bgcol[ 'g' ], $this->bgcol[ 'b' ]);
        $fontcolor = imagecolorallocate($imgziel, $this->fontcol[ 'r' ], $this->fontcol[ 'g' ], $this->fontcol[ 'b' ]);
        $xziel = imagesx($imgziel); // get image width
        $yziel = imagesy($imgziel); // get image height
        imagefilledrectangle($imgziel, 0, 0, $xziel, $yziel, $bgcolor);

        // add line and point noise
        for ($i = 0; $i < $this->linenoise; $i++) {
            $color = imagecolorallocate($imgziel, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($imgziel, rand(0, $xziel), rand(0, $yziel), rand(0, $xziel), rand(0, $yziel), $color);
        }

        for ($i = 0; $i < $this->noise; $i++) {
            imagesetpixel($imgziel, rand(0, $xziel), rand(0, $yziel), $fontcolor);
        }

        $lenght = mb_strlen($text);
        for ($i = 0; $i < $lenght; $i++) {
            $char = mb_substr($text, $i, 1);
            if ($char == "-" || $char == "+" || $char == "=") {
                imagesetthickness($imgziel, 2);
                if ($char == "-") {
                    imageline($imgziel, $i * 15, 13, $i * 15 + 8, 13, $fontcolor);
                }
                if ($char == "+") {
                    imageline($imgziel, $i * 15, 13, $i * 15 + 9, 13, $fontcolor);
                    imageline($imgziel, ($i * 15) + 5, 8, ($i * 15) + 5, 18, $fontcolor);
                }
                if ($char == "=") {
                    imageline($imgziel, $i * 15, 11, $i * 15 + 9, 11, $fontcolor);
                    imageline($imgziel, $i * 15, 15, $i * 15 + 9, 15, $fontcolor);
                }
            } else {
                $font = rand(2, 5);
                imagestring($imgziel, $font, $i * 15 + 5, 5, $char, $fontcolor);
            }
        }

        $tmp_file_path = __DIR__ . '/tmp/' . $this->hash . '.jpg';

        imagejpeg($imgziel, $tmp_file_path);
        @chmod($tmp_file_path, 0655);

        $_language = new Language();
        $_language->readModule('captcha');

        return '<img src="' . $tmp_file_path . '" alt="' . $_language->module[ 'security_code' ] . '" />';

    }

    /* create captcha image/string and hash */
    public function createCaptcha(): string
    {

        $this->hash = md5(time() . rand(0, 10000));

        $captcha = $this->generateCaptchaText();
        $captcha_result = $captcha[ 'result' ];
        $captcha_text = $captcha[ 'text' ];

        if ($this->type == 'g') {
            $captcha_text = $this->createCatpchaImage($captcha_text);
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . 'captcha')
            ->setValue('hash', '?')
            ->setValue('captcha', '?')
            ->setValue('deltime', '?')
            ->setParameter(0, $this->hash)
            ->setParameter(1, $captcha_result)
            ->setParameter(2, (time() + ($this->valide_time * 60)));

        return $captcha_text;

    }

    /* create transaction hash for formulars */
    public function createTransaction(): bool
    {

        $this->hash = md5(time() . rand(0, 10000));

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . 'captcha')
            ->setValue('hash', '?')
            ->setValue('captcha', '?')
            ->setValue('deltime', '?')
            ->setParameter(0, $this->hash)
            ->setParameter(1, '0')
            ->setParameter(2, (time() + ($this->valide_time * 60)));

        $queryBuilder->execute();

        return true;

    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function checkCaptcha(int $input, string $hash): bool
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('hash')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'captcha')
            ->where('captcha = ?')
            ->andWhere('hash = ?')
            ->setParameter(0, $input)
            ->setParameter(1, $hash);

        $settings_query = $queryBuilder->execute();
        $ds = $settings_query->fetch();

        if (isset($ds['hash']) && !empty($ds['hash'])) {

            $deleteQueryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
            $deleteQueryBuilder
                ->delete(WebSpellDatabaseConnection::getTablePrefix() . 'captcha')
                ->where('captcha = ?')
                ->andWhere('hash = ?')
                ->setParameter(0, $input)
                ->setParameter(1, $hash);

            $deleteQueryBuilder->execute();

            $tmp_file_path = __DIR__ . '/tmp/' . $ds['hash'] . '.jpg';
            if (file_exists($tmp_file_path)) {
                unlink($tmp_file_path);
            }

            return true;

        } else {
            return false;
        }

    }

    private function clearOldCaptcha(): void
    {
        $time = time();

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('hash')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'captcha')
            ->where('deltime < ?')
            ->setParameter(0, $time);

        $settings_query = $queryBuilder->execute();
        while ($ds = $settings_query->fetch()) {

            $tmp_file_path = __DIR__ . '/tmp/' . $ds['hash'] . '.jpg';
            if (file_exists($tmp_file_path)) {
                unlink($tmp_file_path);
            }

        }

        $deleteQueryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $deleteQueryBuilder
            ->delete(WebSpellDatabaseConnection::getTablePrefix() . 'captcha')
            ->where('deltime < ?')
            ->setParameter(0, $time);

        $deleteQueryBuilder->execute();

    }

}
