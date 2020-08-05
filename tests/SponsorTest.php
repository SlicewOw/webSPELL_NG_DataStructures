<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Sponsor;

final class SponsorTest extends TestCase
{

    public function testIfSponsorInstanceCanBeCreated(): void
    {

        $sponsor = new Sponsor();
        $sponsor->setSponsorId(123);
        $sponsor->setName("Test Sponsor");
        $sponsor->setHomepage("https://gaming.myrisk-ev.de");
        $sponsor->setInfo("Test Info");
        $sponsor->setBanner("https://images.myrisk-ev.de/logo.png");
        $sponsor->setBannerSmall("https://images.myrisk-ev.de/logo_small.png");
        $sponsor->setIsDisplayed(true);
        $sponsor->setIsMainsponsor(false);

        $this->assertEquals(123, $sponsor->getSponsorId(), "Sponsor ID is set.");
        $this->assertEquals("Test Sponsor", $sponsor->getName(), "Sponsor name is set.");
        $this->assertEquals("https://gaming.myrisk-ev.de", $sponsor->getHomepage(), "Sponsor homepage is set.");
        $this->assertEquals("Test Info", $sponsor->getInfo(), "Sponsor info is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo.png", $sponsor->getBanner(), "Sponsor banner is set.");
        $this->assertEquals("https://images.myrisk-ev.de/logo_small.png", $sponsor->getBannerSmall(), "Sponsor banner small is set.");
        $this->assertTrue($sponsor->isDisplayed(), "Sponsor is displayed.");
        $this->assertFalse($sponsor->isMainsponsor(), "Sponsor is not a mainsponsor.");

    }

}
