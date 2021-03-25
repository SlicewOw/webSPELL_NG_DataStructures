<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use webspell_ng\Handler\SocialNetworkTypeHandler;
use webspell_ng\SocialNetwork;
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
        $social_network_type_id = rand(1, 10);

        $social_network_01 = new SocialNetwork();
        $social_network_01->setSocialNetworkType(
            SocialNetworkTypeHandler::getSocialNetworkById($social_network_type_id)
        );
        $social_network_01->setValue("https://gaming.myrisk-ev.de");

        $new_sponsor = new Sponsor();
        $new_sponsor->setName($sponsor_name);
        $new_sponsor->setHomepage("https://gaming.myrisk-ev.de");
        $new_sponsor->setInfo("Test Info");
        $new_sponsor->setBanner("https://images.myrisk-ev.de/logo.png");
        $new_sponsor->setBannerSmall("https://images.myrisk-ev.de/logo_small.png");
        $new_sponsor->setIsActive(true);
        $new_sponsor->setIsMainsponsor(false);
        $new_sponsor->setDate($date);
        $new_sponsor->setSort($sort);
        $new_sponsor->addSocialNetwork($social_network_01);

        $saved_sponsor = SponsorHandler::saveSponsor($new_sponsor);

        $this->assertGreaterThan(0, $saved_sponsor->getSponsorId(), "Sponsor ID is set.");
        $this->assertEquals($sponsor_name, $saved_sponsor->getName(), "Sponsor name is set.");
        $this->assertEquals("https://gaming.myrisk-ev.de", $saved_sponsor->getHomepage(), "Sponsor homepage is set.");
        $this->assertEquals("Test Info", $saved_sponsor->getInfo(), "Sponsor info is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo.png", $saved_sponsor->getBanner(), "Sponsor banner is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo_small.png", $saved_sponsor->getBannerSmall(), "Sponsor banner small is set.");
        $this->assertTrue($saved_sponsor->isActive(), "Sponsor is displayed.");
        $this->assertFalse($saved_sponsor->isMainsponsor(), "Sponsor is not a mainsponsor.");
        $this->assertEquals($date->getTimestamp(), $saved_sponsor->getDate()->getTimestamp(), "Sponsor date is saved.");
        $this->assertEquals($sort, $saved_sponsor->getSort(), "Sponsor sort is saved.");
        $this->assertEquals(1, count($saved_sponsor->getSocialNetworks()), "Social network of sponsor is saved.");
        $this->assertGreaterThan(0, count(SponsorHandler::getAllSponsors()), "Sponsor is returned.");

        $changed_sponsor_name = "Test Sponsor " . StringFormatterUtils::getRandomString(10);

        $saved_sponsor->setIsActive(false);
        $saved_sponsor->setName($changed_sponsor_name);

        $updated_sponsor = SponsorHandler::saveSponsor($saved_sponsor);

        $this->assertEquals($saved_sponsor->getSponsorId(), $updated_sponsor->getSponsorId(), "Sponsor ID is set.");
        $this->assertEquals($changed_sponsor_name, $updated_sponsor->getName(), "Sponsor name is set.");
        $this->assertEquals($saved_sponsor->getHomepage(), $updated_sponsor->getHomepage(), "Sponsor homepage is set.");
        $this->assertEquals($saved_sponsor->getInfo(), $updated_sponsor->getInfo(), "Sponsor info is set.");
        $this->assertEquals($saved_sponsor->getBanner(), $updated_sponsor->getBanner(), "Sponsor banner is set.");
        $this->assertEquals($saved_sponsor->getBannerSmall(), $updated_sponsor->getBannerSmall(), "Sponsor banner small is set.");
        $this->assertFalse($updated_sponsor->isActive(), "Sponsor is displayed.");
        $this->assertEquals($saved_sponsor->isMainsponsor(), $updated_sponsor->isMainsponsor(), "Sponsor is not a mainsponsor.");
        $this->assertEquals($saved_sponsor->getDate()->getTimestamp(), $updated_sponsor->getDate()->getTimestamp(), "Sponsor date is saved.");
        $this->assertEquals($saved_sponsor->getSort(), $updated_sponsor->getSort(), "Sponsor sort is saved.");

    }

    public function testIfActiveSponsorsAreReturnedOnly(): void
    {

        $active_sponsors = SponsorHandler::getAllActiveSponsors();

        $any_sponsor_is_hidden = false;

        foreach ($active_sponsors as $sponsor) {

            if (!$sponsor->isActive()) {
                $any_sponsor_is_hidden = true;
            }

        }

        $this->assertFalse($any_sponsor_is_hidden, "No sponsor is hidden.");

    }

    public function testIfHiddenSponsorsAreReturnedToo(): void
    {

        $new_sponsor = new Sponsor();
        $new_sponsor->setName(
            "Test Sponsor " . StringFormatterUtils::getRandomString(10)
        );
        $new_sponsor->setHomepage("https://gaming.myrisk-ev.de");
        $new_sponsor->setInfo("Test Info");
        $new_sponsor->setBanner("https://images.myrisk-ev.de/logo.png");
        $new_sponsor->setBannerSmall("https://images.myrisk-ev.de/logo_small.png");
        $new_sponsor->setIsActive(false);
        $new_sponsor->setIsMainsponsor(false);
        $new_sponsor->setDate(
            new \DateTime("5 minutes ago")
        );
        $new_sponsor->setSort(
            rand(1, 999999)
        );

        SponsorHandler::saveSponsor($new_sponsor);

        $all_sponsors = SponsorHandler::getAllSponsors();

        $any_sponsor_is_hidden = false;
        $sponsors_are_sorted_in_ascending_order = true;

        $tmp_sort_value = -1;
        foreach ($all_sponsors as $sponsor) {

            if (!$sponsor->isActive()) {
                $any_sponsor_is_hidden = true;
            }

            if ($sponsor->getSort() < $tmp_sort_value) {
                $sponsors_are_sorted_in_ascending_order = false;
            }

            $tmp_sort_value = $sponsor->getSort();

        }

        $this->assertTrue($any_sponsor_is_hidden, "Hidden sponsor is returned too.");
        $this->assertTrue($sponsors_are_sorted_in_ascending_order, "Sponsors are sorted.");

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
