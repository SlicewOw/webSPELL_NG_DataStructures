<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Language;

final class LanguageTest extends TestCase
{

    public function testIfLanguageCanBeLoaded_DE(): void
    {

        $language = new Language();

        $this->assertFalse($language->setLanguage("unknown"), "Unknown language cannot be set.");
        $this->assertTrue($language->setLanguage("de", true), "Language can be set.");
        $this->assertEquals("de", $language->language, "Language is set.");

        $language->readModule("index");

        $this->assertEquals(2, count($language->module_files), "Language module files are set.");
        $this->assertTrue(in_array("index", $language->module_files), "Language module file index.json is used.");
        $this->assertTrue(in_array("formvalidation", $language->module_files), "Language module file formvalidation.json is used.");
        $this->assertGreaterThan(0, count($language->module), "Language modules are set.");

    }

    public function testIfLanguageCanBeLoaded_EN(): void
    {

        $language = new Language();

        $this->assertEquals("en", $language->language, "Language is set.");

        $language->readModule("index");

        $this->assertEquals(2, count($language->module_files), "Language module files are set.");
        $this->assertTrue(in_array("index", $language->module_files), "Language module file index.json is used.");
        $this->assertTrue(in_array("formvalidation", $language->module_files), "Language module file formvalidation.json is used.");
        $this->assertGreaterThan(0, count($language->module), "Language modules are set.");

    }

}
