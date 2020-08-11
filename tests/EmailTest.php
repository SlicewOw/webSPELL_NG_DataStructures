<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Email;

final class EmailTest extends TestCase
{

    public function testIfEmailCanBeSend(): void
    {

        $mail_status_array = Email::sendEmail(
            "slicewow@myrisk-ev.de",
            "Test Mail Module",
            "me@slicewow.de",
            "Test Subject to Test",
            "Test Message with Content :)",
            false
        );

        $this->assertEquals("fail", $mail_status_array["result"]);
        $this->assertEquals("Could not instantiate mail function.", $mail_status_array["error"]);
        $this->assertEquals(null, $mail_status_array["debug"]);

    }

}
