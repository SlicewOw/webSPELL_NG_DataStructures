<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Exception\AccessDeniedException;


final class AccessDeniedExceptionTest extends TestCase
{

    public function testIfAccessDeniedExceptionCanBeCreated(): void
    {

        $exception = new AccessDeniedException("123");

        $exception_message = substr((string) $exception, 0, 71);

        $this->assertEquals("webspell_ng\Exception\AccessDeniedException: [0]: Access denied. 123 in", $exception_message, "Exception output is expected.");

    }

}
