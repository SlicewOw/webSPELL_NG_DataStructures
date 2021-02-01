<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Handler\CountryHandler;

final class CountryHandlerTest extends TestCase
{

    public function testIfCountryInstanceIsReturned(): void
    {

        $country = CountryHandler::getCountryByCountryShortcut("de");

        $this->assertEquals(20, $country->getCountryId(), "Country ID is set.");
        $this->assertEquals("de", $country->getShortcut(), "Game shortcut is set.");
        $this->assertEquals("Germany", $country->getName(), "Game name is set.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfCountryShortcutIsEmpty(): void
    {

        $this->expectException(InvalidArgumentException::class);

        CountryHandler::getCountryByCountryShortcut("");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfCountryShortcutIsTooLong(): void
    {

        $this->expectException(InvalidArgumentException::class);

        CountryHandler::getCountryByCountryShortcut("abcd");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfCountryDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        CountryHandler::getCountryByCountryShortcut("d2");

    }

}