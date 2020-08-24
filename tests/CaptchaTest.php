<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Captcha;

final class CaptchaTest extends TestCase
{

    public function testIfCaptchaCanBeCreated(): void
    {

        $captcha = new Captcha();
        $captcha->setMath(true);

        $hash = $captcha->createCaptcha();

        $this->assertTrue(!empty($hash), "Captcha can be created.");
        $this->assertEquals(1, preg_match('/' . $captcha->getHash() . '.jpg/', $hash), "Captcha is returned.");

        $captcha->setMath(false);

        $new_hash = $captcha->createCaptcha();

        $this->assertTrue(!empty($new_hash), "Captcha can be created.");
        $this->assertEquals(1, preg_match('/' . $captcha->getHash() . '.jpg/', $new_hash), "Captcha is returned.");

    }

    public function testIfTransactionCanBeCreated(): void
    {

        $captcha = new Captcha();

        $this->assertTrue($captcha->createTransaction(), "Transaction is created.");

    }

}
