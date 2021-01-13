<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\NewsLanguage;
use webspell_ng\Handler\NewsLanguageHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class NewsLanguageHandlerTest extends TestCase
{

    public function testIfNewsRubricCanBeSavedAndUpdated(): void
    {

        $language = StringFormatterUtils::getRandomString(10);
        $shortcut = StringFormatterUtils::getRandomString(2);

        $new_language = new NewsLanguage();
        $new_language->setLanguage($language);
        $new_language->setShortcut($shortcut);

        $saved_language = NewsLanguageHandler::saveLanguage($new_language);

        $this->assertGreaterThan(0, $saved_language->getLanguageId(), "Language ID is set.");
        $this->assertEquals($language, $saved_language->getLanguage(), "Language name is set.");
        $this->assertEquals($shortcut, $saved_language->getShortcut(), "Language shortcut is set.");

        $changed_language_name = StringFormatterUtils::getRandomString(10);

        $saved_language->setLanguage($changed_language_name);

        $updated_language = NewsLanguageHandler::saveLanguage($saved_language);

        $this->assertEquals($saved_language->getLanguageId(), $updated_language->getLanguageId(), "Language ID is set.");
        $this->assertEquals($changed_language_name, $updated_language->getLanguage(), "Language name is set.");
        $this->assertEquals($shortcut, $updated_language->getShortcut(), "Language shortcut is set.");

    }

    public function testIfAllRubricsReturnsAsAnArray(): void
    {

        $languages = NewsLanguageHandler::getAllLanguages();

        $this->assertGreaterThan(0, count($languages), "Array is not empty.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfNewsLanguageValueIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        NewsLanguageHandler::getNewsLanguageByShortcut("abc");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfNewsLanguageDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        NewsLanguageHandler::getNewsLanguageByShortcut("d2");

    }

}
