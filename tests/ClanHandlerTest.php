<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Clan;
use webspell_ng\Handler\ClanHandler;
use webspell_ng\Utils\StringFormatterUtils;


final class ClanHandlerTest extends TestCase
{

    public function testIfGameInstanceCanBeCreated(): void
    {

        $clan_name = "Test Clan " . StringFormatterUtils::getRandomString(10);
        $clan_tag = StringFormatterUtils::getRandomString(10);

        $new_clan = new Clan();
        $new_clan->setClanName($clan_name);
        $new_clan->setClanTag($clan_tag);
        $new_clan->setHomepage("https://cup.myrisk-ev.de");
        $new_clan->setClanLogotype("myrisk_ev.jpg");

        $clan = ClanHandler::saveClan($new_clan);

        $this->assertGreaterThan(0, $clan->getClanId(), "Clan ID is set.");
        $this->assertEquals($clan_name, $clan->getClanName(), "Clan name is set.");
        $this->assertEquals($clan_tag, $clan->getClanTag(), "Clan tag is set.");
        $this->assertEquals("https://cup.myrisk-ev.de", $clan->getHomepage(), "Clan homepage is set.");
        $this->assertEquals("myrisk_ev.jpg", $clan->getClanLogotype(), "Clan logotype is set.");

        $this->assertTrue(ClanHandler::isExistingClan($clan_name));

        $changed_clan_tag = StringFormatterUtils::getRandomString(10);

        $changed_clan = ClanHandler::getClanByClanId($clan->getClanId());
        $changed_clan->setClanTag($changed_clan_tag);
        $changed_clan->setHomepage("https://tv.myrisk-ev.de");

        $updated_clan = ClanHandler::saveClan($changed_clan);

        $this->assertEquals($clan->getClanId(), $updated_clan->getClanId(), "Clan ID is set.");
        $this->assertEquals($clan_name, $updated_clan->getClanName(), "Clan name is set.");
        $this->assertEquals($changed_clan_tag, $updated_clan->getClanTag(), "Clan tag is set.");
        $this->assertEquals("https://tv.myrisk-ev.de", $updated_clan->getHomepage(), "Clan homepage is set.");
        $this->assertEquals("myrisk_ev.jpg", $updated_clan->getClanLogotype(), "Clan logotype is set.");

        $this->assertTrue(ClanHandler::isExistingClan($clan_name));

    }

    public function testIfInvalidArgumentExceptionIsThrownIfClanIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        ClanHandler::getClanByClanId(-1);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfClanDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        ClanHandler::getClanByClanId(999999999);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfClanNameIsEmpty(): void
    {

        $this->expectException(InvalidArgumentException::class);

        ClanHandler::isExistingClan("");

    }

}
