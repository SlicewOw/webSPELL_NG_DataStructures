<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\PageData;
use webspell_ng\Enums\PageEnums;
use webspell_ng\Handler\AboutUsHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class AboutUsHandlerTest extends TestCase
{

    public function testIfDefaultAboutUsIsExisting(): void
    {

        $about = AboutUsHandler::getAboutUsByPage(PageEnums::POLICY_DEFAULT);

        $this->assertEquals(PageEnums::POLICY_DEFAULT, $about->getPage(), "Page is set.");
        $this->assertGreaterThan(0, $about->getDate()->getTimestamp(), "Date is set");
        $this->assertNotEmpty($about->getInfo(), "Info is set.");

    }

    public function testIfAboutUsCanBeSavedAndUpdated(): void
    {

        $page = "Test Page " . StringFormatterUtils::getRandomString(10);
        $info = StringFormatterUtils::getRandomString(10);
        $old_date = new \DateTime("1 minute ago");

        $about = new PageData();
        $about->setPage($page);
        $about->setInfo($info);

        AboutUsHandler::saveAbout($about);

        $saved_policy = AboutUsHandler::getAboutUsByPage($page);

        $this->assertEquals($page, $saved_policy->getPage(), "Page is set.");
        $this->assertGreaterThan($old_date, $saved_policy->getDate(), "Date is set automatically");
        $this->assertEquals($info, $saved_policy->getInfo(), "Info is set.");

        $changed_info = StringFormatterUtils::getRandomString(10);

        $saved_policy->setInfo($changed_info);

        AboutUsHandler::saveAbout($saved_policy);

        $saved_policy = AboutUsHandler::getAboutUsByPage($page);

        $this->assertEquals($page, $saved_policy->getPage(), "Page is set.");
        $this->assertGreaterThan($old_date, $saved_policy->getDate(), "Date is set automatically");
        $this->assertEquals($changed_info, $saved_policy->getInfo(), "Info is set.");

    }

    public function testIfDefaultIsReturnedIfPageIsNotSavedYet(): void
    {

        $about = AboutUsHandler::getAboutUsByPage(
            StringFormatterUtils::getRandomString(10)
        );

        $this->assertEquals(PageEnums::POLICY_DEFAULT, $about->getPage(), "Page is set.");
        $this->assertGreaterThan(0, $about->getDate()->getTimestamp(), "Date is set");
        $this->assertNotEmpty($about->getInfo(), "Info is set.");

    }

    public function testIfAllImprintsAreReturned(): void
    {

        $imprints = AboutUsHandler::getAllAboutUs();

        $this->assertGreaterThan(1, count($imprints));

    }

}
