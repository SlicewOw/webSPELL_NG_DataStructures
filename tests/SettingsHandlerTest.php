<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Handler\SettingsHandler;

final class SettingsHandlerTest extends TestCase
{

    public function testIfSettingsCanBeAccessed(): void
    {

        $settings = SettingsHandler::getSettings("gaming");

        $this->assertNotEmpty($settings->getHomepageTitle());
        $this->assertEquals("myRisk Gaming e.V.", $settings->getClanname());
        $this->assertEquals("myRisk eV", $settings->getClantag());
        $this->assertEquals("d.m.y", $settings->getDefaultDateFormat());
        $this->assertEquals("G:i", $settings->getDefaultTimeFormat());

    }

    public function testIfEmptyStringIsDetected(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SettingsHandler::getSettings("");

    }

    public function testIfUnknownPageIsDetected(): void
    {

        $this->expectException(UnexpectedValueException::class);

        SettingsHandler::getSettings("unknown");

    }

}
