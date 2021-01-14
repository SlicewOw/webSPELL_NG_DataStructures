<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Sponsor;
use webspell_ng\Handler\SponsorHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class SponsorHandlerTest extends TestCase
{

    public function testIfSponsorCanBeSavedAndUpdated(): void
    {

        $sponsor_name = "Test Sponsor " . StringFormatterUtils::getRandomString(10);
        $date = new \DateTime("now");

        $sort = rand(100, 9999);

        $new_sponsor = new Sponsor();
        $new_sponsor->setName($sponsor_name);
        $new_sponsor->setHomepage("https://gaming.myrisk-ev.de");
        $new_sponsor->setInfo("Test Info");
        $new_sponsor->setBanner("https://images.myrisk-ev.de/logo.png");
        $new_sponsor->setBannerSmall("https://images.myrisk-ev.de/logo_small.png");
        $new_sponsor->setIsDisplayed(true);
        $new_sponsor->setIsMainsponsor(false);
        $new_sponsor->setDate($date);
        $new_sponsor->setSort($sort);

        $saved_sponsor = SponsorHandler::saveSponsor($new_sponsor);

        $this->assertGreaterThan(0, $saved_sponsor->getSponsorId(), "Sponsor ID is set.");
        $this->assertEquals($sponsor_name, $saved_sponsor->getName(), "Sponsor name is set.");
        $this->assertEquals("https://gaming.myrisk-ev.de", $saved_sponsor->getHomepage(), "Sponsor homepage is set.");
        $this->assertEquals("Test Info", $saved_sponsor->getInfo(), "Sponsor info is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo.png", $saved_sponsor->getBanner(), "Sponsor banner is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo_small.png", $saved_sponsor->getBannerSmall(), "Sponsor banner small is set.");
        $this->assertTrue($saved_sponsor->isDisplayed(), "Sponsor is displayed.");
        $this->assertFalse($saved_sponsor->isMainsponsor(), "Sponsor is not a mainsponsor.");
        $this->assertEquals($date->getTimestamp(), $saved_sponsor->getDate()->getTimestamp(), "Sponsor date is saved.");
        $this->assertEquals($sort, $saved_sponsor->getSort(), "Sponsor sort is saved.");

        $changed_sponsor_name = "Test Sponsor " . StringFormatterUtils::getRandomString(10);

        $saved_sponsor->setIsDisplayed(false);
        $saved_sponsor->setName($changed_sponsor_name);

        $updated_sponsor = SponsorHandler::saveSponsor($saved_sponsor);

        $this->assertEquals($saved_sponsor->getSponsorId(), $updated_sponsor->getSponsorId(), "Sponsor ID is set.");
        $this->assertEquals($changed_sponsor_name, $updated_sponsor->getName(), "Sponsor name is set.");
        $this->assertEquals($saved_sponsor->getHomepage(), $updated_sponsor->getHomepage(), "Sponsor homepage is set.");
        $this->assertEquals($saved_sponsor->getInfo(), $updated_sponsor->getInfo(), "Sponsor info is set.");
        $this->assertEquals($saved_sponsor->getBanner(), $updated_sponsor->getBanner(), "Sponsor banner is set.");
        $this->assertEquals($saved_sponsor->getBannerSmall(), $updated_sponsor->getBannerSmall(), "Sponsor banner small is set.");
        $this->assertFalse($saved_sponsor->isDisplayed(), "Sponsor is displayed.");
        $this->assertEquals($saved_sponsor->isMainsponsor(), $updated_sponsor->isMainsponsor(), "Sponsor is not a mainsponsor.");
        $this->assertEquals($saved_sponsor->getDate()->getTimestamp(), $updated_sponsor->getDate()->getTimestamp(), "Sponsor date is saved.");
        $this->assertEquals($saved_sponsor->getSort(), $updated_sponsor->getSort(), "Sponsor sort is saved.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSponsorIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SponsorHandler::getSponsorBySponsorId(-1);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSponsorDoesNotExist(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SponsorHandler::getSponsorBySponsorId(9999999);

    }

}
