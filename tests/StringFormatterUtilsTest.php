<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Utils\StringFormatterUtils;

final class StringFormatterUtilsTest extends TestCase
{

    public function testIfStringCanBeConvertedToHtmlOutput(): void
    {
        $this->assertEquals(
            '<a href="https://123.de">Link</a> test 123 ä ß',
            StringFormatterUtils::resolveHtmlToString('&lt;a href="https://123.de"&gt;Link&lt;/a&gt; test 123 ä ß')
        );
    }

    public function testIfInputIsConverted(): void
    {
        $this->assertEquals("test 123 ä ß", StringFormatterUtils::getInput("test 123 ä ß", false));
    }

    public function testIfEmptyStringIsReturnedIfTextIsInvalid(): void
    {
        $this->assertEmpty(StringFormatterUtils::getInput("123", true));
    }

    public function testStringReadynessForDatabase(): void
    {

        $converted_text = StringFormatterUtils::getTextFormattedForDatabase("Text ä for DB ' containing \ characters :)");

        $this->assertEquals(
            "Text ä for DB &#039; containing  characters :)",
            $converted_text
        );

    }

    public function testIfFilenameIsReturned(): void
    {

        $original_filename = StringFormatterUtils::getRandomString(10, 2);

        $converted_filename_01 = StringFormatterUtils::convert2filename($original_filename, true, true);

        $this->assertGreaterThan(
            0,
            preg_match(
                '/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]_[0-9][0-9]-[0-9][0-9]-[0-9][0-9]_(.*)/',
                $converted_filename_01
            )
        );

        $converted_filename_02 = StringFormatterUtils::convert2filename($original_filename, true, false);

        $this->assertGreaterThan(
            0,
            preg_match(
                '/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]_(.*)/',
                $converted_filename_02
            )
        );

        $converted_filename_03 = StringFormatterUtils::convert2filename($original_filename, false, false);

        $this->assertEquals(strtolower($original_filename), $converted_filename_03);

    }

    public function testIfRandomStringsCanBeGenerated(): void
    {

        $this->assertNotEquals(StringFormatterUtils::getRandomString(10, 0), StringFormatterUtils::getRandomString(10, 0));
        $this->assertNotEquals(StringFormatterUtils::getRandomString(10, 1), StringFormatterUtils::getRandomString(10, 1));
        $this->assertNotEquals(StringFormatterUtils::getRandomString(10, 2), StringFormatterUtils::getRandomString(10, 2));
        $this->assertNotEquals(StringFormatterUtils::getRandomString(10, 3), StringFormatterUtils::getRandomString(10, 3));

    }

    public function testIfYouTubeIdIsFound(): void
    {
        $this->assertEquals(
            "webSPELL_NG",
            StringFormatterUtils::convertToYoutubeId("https://youtube.com/webSPELL_NG")
        );
    }

    public function testIfYouTubeLiveIdIsFound(): void
    {
        $this->assertEquals(
            "myRisk_eV",
            StringFormatterUtils::convertToYoutubeLiveId("https://gaming.youtube.com/myRisk_eV")
        );
    }

    public function testIfFacebookIdIsFound(): void
    {
        $this->assertEquals(
            "myRisk_Gaming_eV",
            StringFormatterUtils::convertToFacebookId("https://facebook.com/myRisk_Gaming_eV")
        );
    }

    public function testIfTwitterIdIsFound(): void
    {
        $this->assertEquals(
            "twitter",
            StringFormatterUtils::convertToTwitterId("https://twitter.com/twitter")
        );
    }

    public function testIfTwitchIdIsFound(): void
    {
        $this->assertEquals(
            "ESL_CSGO",
            StringFormatterUtils::convertToTwitchId("https://twitch.tv/ESL_CSGO")
        );
    }

    public function testIfPrizeConvertionIsWorking(): void
    {

        $this->assertEquals("13.37", StringFormatterUtils::convertStringToPrizeValue("13.37"), "Normal prize is detected with english format");
        $this->assertEquals("13.37", StringFormatterUtils::convertStringToPrizeValue("13,37"), "Normal prize is detected with german format");
        $this->assertEquals("13.00", StringFormatterUtils::convertStringToPrizeValue("13"), "Normal prize is detected");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfPrizeValueIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        StringFormatterUtils::convertStringToPrizeValue("13,37,00");

    }

}
