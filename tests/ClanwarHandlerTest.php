<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\Clan;
use \webspell_ng\Clanwar;
use \webspell_ng\Event;
use \webspell_ng\Game;
use \webspell_ng\Squad;
use \webspell_ng\Enums\SquadEnums;
use \webspell_ng\Handler\ClanHandler;
use \webspell_ng\Handler\ClanwarHandler;
use \webspell_ng\Handler\EventHandler;
use \webspell_ng\Handler\GameHandler;
use \webspell_ng\Handler\SquadHandler;
use \webspell_ng\Utils\StringFormatterUtils;

final class ClanwarHandlerTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {

        if (!EventHandler::isExistingEvent(1)) {

            $new_event = new Event();
            $new_event->setName("Test Event");
            $new_event->setHomepage("https://cup.myrisk-ev.de");
            $new_event->setSquad(
                SquadHandler::getSquadBySquadId(1)
            );

            EventHandler::saveEvent($new_event);

        }

    }

    public function testIfClanwarCanBeSavedAndUpdated(): void
    {

        $date = new \DateTime("2019-02-28 20:00:00");

        $new_squad_01 = new Squad();
        $new_squad_01->setName("Test Squad " . StringFormatterUtils::getRandomString(10, 2));
        $new_squad_01->setDate($date);
        $new_squad_01->setIcon("icon.jpg");
        $new_squad_01->setIconSmall("icon_small.jpg");
        $new_squad_01->setInfo("Information text ...");
        $new_squad_01->setRubric(SquadEnums::SQUAD_RUBRIC_AMATEUR);
        $new_squad_01->setIsGameSquad(true);
        $new_squad_01->setGame(
            GameHandler::getGameByGameId(1)
        );

        $first_squad = SquadHandler::saveSquad($new_squad_01);

        $new_clan = new Clan();
        $new_clan->setClanName("Test Clan " . StringFormatterUtils::getRandomString(10, 2));
        $new_clan->setClanTag(StringFormatterUtils::getRandomString(10, 2));
        $new_clan->setHomepage("https://gaming.myrisk-ev.de");
        $new_clan->setClanLogotype("myrisk_ev.jpg");

        $clan = ClanHandler::saveClan($new_clan);

        $this->assertGreaterThan(0, $clan->getClanId(), "Clan ID is set.");

        $new_clanwar = new Clanwar();
        $new_clanwar->setSquad(
            $first_squad,
            array(1, 2, 3)
        );
        $new_clanwar->setOpponent($clan);
        $new_clanwar->setLeague(
            EventHandler::getEventById(1)
        );
        $new_clanwar->setMatchURL("https://cup.myrisk-ev.de");
        $new_clanwar->setDate($date);

        $new_clanwar = ClanwarHandler::addMapToClanwar($new_clanwar, 1, 16, 14);
        $new_clanwar = ClanwarHandler::addMapToClanwar($new_clanwar, 2, 14, 16);
        $new_clanwar = ClanwarHandler::addMapToClanwar($new_clanwar, 3, 13, 37);

        $this->assertNull($new_clanwar->getClanwarId());

        $clanwar = ClanwarHandler::saveMatch($new_clanwar);

        $this->assertGreaterThan(0, $clanwar->getClanwarId(), "Clanwar ID is set.");
        $this->assertEquals($date, $clanwar->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals("https://cup.myrisk-ev.de", $clanwar->getMatchHomepage(), "Match URL of clanwar is set.");

        $this->assertNotNull($clanwar->getGame(), "Game of clanwar is not null");
        $this->assertInstanceOf(Game::class, $clanwar->getGame(), "Game is set!");
        $this->assertGreaterThan(0, $clanwar->getGame()->getGameId(), "Game ID is set!");
        $this->assertNotEmpty($clanwar->getGame()->getTag(), "Game tag is expected.");

        $this->assertInstanceOf(Squad::class, $clanwar->getSquad(), "Squad is set!");
        $this->assertEquals($first_squad->getSquadId(), $clanwar->getSquadId(), "Squad is expected.");
        $this->assertEquals($clan->getClanId(), $clanwar->getOpponent()->getClanId(), "Opponent is expected.");

        $this->assertInstanceOf(Event::class, $clanwar->getEvent(), "Event is set!");
        $this->assertEquals(1, $clanwar->getEventId(), "Event ID is expected.");

        $new_date = new \DateTime("2019-07-02 13:37:00");

        $new_squad_02 = new Squad();
        $new_squad_02->setName("Test Squad " . StringFormatterUtils::getRandomString(10, 2));
        $new_squad_02->setDate($date);
        $new_squad_02->setIcon("icon.jpg");
        $new_squad_02->setIconSmall("icon_small.jpg");
        $new_squad_02->setInfo("Information text ...");
        $new_squad_02->setRubric(SquadEnums::SQUAD_RUBRIC_AMATEUR);
        $new_squad_02->setIsGameSquad(true);
        $new_squad_02->setGame(
            GameHandler::getGameByGameId(1)
        );

        $second_squad = SquadHandler::saveSquad($new_squad_02);

        $changed_clanwar = $clanwar;
        $changed_clanwar->setDate($new_date);
        $changed_clanwar->setSquad(
            $second_squad,
            array(3)
        );
        $new_clanwar->setMatchURL("https://tv.myrisk-ev.de");

        $updated_clanwar = ClanwarHandler::saveMatch($changed_clanwar);

        $this->assertEquals($clanwar->getClanwarId(), $updated_clanwar->getClanwarId(), "Clanwar ID is set.");
        $this->assertNotEquals($date, $updated_clanwar->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals($new_date, $updated_clanwar->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals("https://tv.myrisk-ev.de", $updated_clanwar->getMatchHomepage(), "Match URL of clanwar is set.");

        $this->assertNotNull($updated_clanwar->getGame(), "Game of clanwar is not null");
        $this->assertInstanceOf(Game::class, $updated_clanwar->getGame(), "Game is set!");
        $this->assertGreaterThan(0, $updated_clanwar->getGame()->getGameId(), "Game ID is set!");
        $this->assertNotEmpty($updated_clanwar->getGame()->getTag(), "Game tag is expected.");

        $this->assertInstanceOf(Squad::class, $updated_clanwar->getSquad(), "Squad is set!");
        $this->assertEquals($second_squad->getSquadId(), $updated_clanwar->getSquadId(), "Squad is expected.");
        $this->assertEquals($clan->getClanId(), $updated_clanwar->getOpponent()->getClanId(), "Opponent is expected.");

        $this->assertInstanceOf(Event::class, $updated_clanwar->getEvent(), "Event is set!");
        $this->assertEquals(1, $updated_clanwar->getEventId(), "Event ID is expected.");

    }

    public function testIfNoMappingIsFoundIfClanwarDoesNotExist(): void
    {
        $this->assertFalse(ClanwarHandler::isAnyMapSavedForClanwar(999999999), "No mapping is found!");
    }

}
