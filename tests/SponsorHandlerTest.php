<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Sponsor;
use webspell_ng\Handler\SponsorHandler;

final class SponsorHandlerTest extends TestCase
{

    public function testIfSponsorInstanceCanBeCreated(): void
    {

        $date = new \DateTime("now");

        $sort = rand(100, 9999);

        $new_sponsor = new Sponsor();
        $new_sponsor->setName("Test Sponsor");
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
        $this->assertEquals("Test Sponsor", $saved_sponsor->getName(), "Sponsor name is set.");
        $this->assertEquals("https://gaming.myrisk-ev.de", $saved_sponsor->getHomepage(), "Sponsor homepage is set.");
        $this->assertEquals("Test Info", $saved_sponsor->getInfo(), "Sponsor info is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo.png", $saved_sponsor->getBanner(), "Sponsor banner is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo_small.png", $saved_sponsor->getBannerSmall(), "Sponsor banner small is set.");
        $this->assertTrue($saved_sponsor->isDisplayed(), "Sponsor is displayed.");
        $this->assertFalse($saved_sponsor->isMainsponsor(), "Sponsor is not a mainsponsor.");
        $this->assertEquals($date, $saved_sponsor->getDate(), "Sponsor date is saved.");
        $this->assertEquals($sort, $saved_sponsor->getSort(), "Sponsor sort is saved.");

        $sponsor = SponsorHandler::getSponsorBySponsorId($saved_sponsor->getSponsorId());

        $this->assertEquals($saved_sponsor->getSponsorId(), $sponsor->getSponsorId(), "Sponsor ID is set.");
        $this->assertEquals($saved_sponsor->getName(), $sponsor->getName(), "Sponsor name is set.");
        $this->assertEquals($saved_sponsor->getHomepage(), $sponsor->getHomepage(), "Sponsor homepage is set.");
        $this->assertEquals($saved_sponsor->getInfo(), $sponsor->getInfo(), "Sponsor info is set.");
        $this->assertEquals($saved_sponsor->getBanner(), $sponsor->getBanner(), "Sponsor banner is set.");
        $this->assertEquals($saved_sponsor->getBannerSmall(), $sponsor->getBannerSmall(), "Sponsor banner small is set.");
        $this->assertEquals($saved_sponsor->isDisplayed(), $sponsor->isDisplayed(), "Sponsor is displayed.");
        $this->assertEquals($saved_sponsor->isMainsponsor(), $sponsor->isMainsponsor(), "Sponsor is not a mainsponsor.");
        $this->assertEquals($saved_sponsor->getDate()->getTimestamp(), $sponsor->getDate()->getTimestamp(), "Sponsor date is saved.");
        $this->assertEquals($saved_sponsor->getSort(), $sponsor->getSort(), "Sponsor sort is saved.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSponsorIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        $sponsor = SponsorHandler::getSponsorBySponsorId(-1);

        // This line is hopefully never be reached
        $this->assertLessThan(1, $sponsor->getSponsorId());

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSponsorDoesNotExist(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SponsorHandler::getSponsorBySponsorId(99999);

    }

}
