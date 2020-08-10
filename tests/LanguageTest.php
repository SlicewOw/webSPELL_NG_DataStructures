<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Language;

final class LanguageTest extends TestCase
{

    public function testIfLanguageCanBeLoaded(): void
    {

        $language = new Language();

        $this->assertFalse($language->setLanguage("unknown"), "Unknown language cannot be set.");
        $this->assertTrue($language->setLanguage("de", true), "Language can be set.");
        $this->assertEquals("de", $language->language, "Language is set.");



    }

}
