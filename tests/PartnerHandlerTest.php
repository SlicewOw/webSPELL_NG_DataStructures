<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Partner;
use webspell_ng\Handler\PartnerHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class PartnerHandlerTest extends TestCase
{

    public function testIfPartnerCanBeSavedAndUpdated(): void
    {

        $partner_name = "Test Partner " . StringFormatterUtils::getRandomString(10);
        $date = new \DateTime("now");

        $sort = rand(100, 9999);

        $new_partner = new Partner();
        $new_partner->setName($partner_name);
        $new_partner->setHomepage("https://gaming.myrisk-ev.de");
        $new_partner->setBanner("https://images.myrisk-ev.de/logo.png");
        $new_partner->setIsDisplayed(true);
        $new_partner->setDate($date);
        $new_partner->setSort($sort);

        $saved_partner = PartnerHandler::savePartner($new_partner);

        $this->assertGreaterThan(0, $saved_partner->getPartnerId(), "Partner ID is set.");
        $this->assertEquals($partner_name, $saved_partner->getName(), "Partner name is set.");
        $this->assertEquals("https://gaming.myrisk-ev.de", $saved_partner->getHomepage(), "Partner homepage is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo.png", $saved_partner->getBanner(), "Partner banner is set.");
        $this->assertTrue($saved_partner->isDisplayed(), "Partner is displayed.");
        $this->assertEquals($date->getTimestamp(), $saved_partner->getDate()->getTimestamp(), "Partner date is saved.");
        $this->assertEquals($sort, $saved_partner->getSort(), "Partner sort is saved.");
        $this->assertEquals(0, $saved_partner->getHits(), "Partner hits is saved.");

        $changed_sponsor_name = "Test Partner " . StringFormatterUtils::getRandomString(10);

        $saved_partner->setName($changed_sponsor_name);

        $updated_partner = PartnerHandler::savePartner($saved_partner);

        $this->assertEquals($saved_partner->getPartnerId(), $updated_partner->getPartnerId(), "Sponsor ID is set.");
        $this->assertEquals($changed_sponsor_name, $updated_partner->getName(), "Sponsor name is set.");
        $this->assertEquals($saved_partner->getHomepage(), $updated_partner->getHomepage(), "Sponsor homepage is set.");
        $this->assertEquals($saved_partner->getBanner(), $updated_partner->getBanner(), "Sponsor banner is set.");
        $this->assertEquals($saved_partner->isDisplayed(), $updated_partner->isDisplayed(), "Sponsor is displayed.");
        $this->assertEquals($saved_partner->getDate()->getTimestamp(), $updated_partner->getDate()->getTimestamp(), "Sponsor date is saved.");
        $this->assertEquals($saved_partner->getSort(), $updated_partner->getSort(), "Sponsor sort is saved.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfPartnerIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        PartnerHandler::getPartnerByPartnerId(-1);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfPartnerDoesNotExist(): void
    {

        $this->expectException(InvalidArgumentException::class);

        PartnerHandler::getPartnerByPartnerId(99999999);

    }

}
