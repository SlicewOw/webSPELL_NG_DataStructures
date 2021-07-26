<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Email;

final class EmailTest extends TestCase
{

    public function testIfEmailCanBeSend(): void
    {

        $email_response = Email::sendEmail(
            "slicewow@myrisk-ev.de",
            "Test Mail Module",
            "me@slicewow.de",
            "Test Subject to Test",
            "Test Message with Content :)",
            false
        );

        $this->assertFalse($email_response->isFailed(), "Email response is expected.");
        $this->assertTrue($email_response->isSuccess(), "Email response is expected.");
        $this->assertNull($email_response->getError(), "Error message is NOT set.");
        $this->assertNull($email_response->getDebugMessage(), "Debug message is NOT set.");

    }

}
