<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\Clan;
use \webspell_ng\Clanwar;
use webspell_ng\Enums\ClanwarEnums;
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

    /**
     * @var Squad $first_squad
     */
    private static $first_squad;

    /**
     * @var Squad $second_squad
     */
    private static $second_squad;

    /**
     * @var Clan $clan
     */
    private static $clan;

    /**
     * @var Event $event
     */
    private static $event;

    /**
     * @var \DateTime $old_date
     */
    private static $old_date;

    /**
     * @var \DateTime $new_date
     */
    private static $new_date;

    public static function setUpBeforeClass(): void
    {

        if (!EventHandler::isExistingEvent(1)) {

            $new_event = new Event();
            $new_event->setName("Test Event");
            $new_event->setHomepage("https://cup.myrisk-ev.de");
            $new_event->setSquad(
                SquadHandler::getSquadBySquadId(1)
            );

            self::$event = EventHandler::saveEvent($new_event);

        } else {
            self::$event = EventHandler::getEventById(1);
        }

        self::$old_date = new \DateTime("2019-02-28 20:00:00");

        $new_squad_01 = new Squad();
        $new_squad_01->setName("Test Squad " . StringFormatterUtils::getRandomString(10, 2));
        $new_squad_01->setDate(self::$old_date);
        $new_squad_01->setIcon("icon.jpg");
        $new_squad_01->setIconSmall("icon_small.jpg");
        $new_squad_01->setInfo("Information text ...");
        $new_squad_01->setRubric(SquadEnums::SQUAD_RUBRIC_AMATEUR);
        $new_squad_01->setIsGameSquad(true);
        $new_squad_01->setGame(
            GameHandler::getGameByGameId(1)
        );

        self::$first_squad = SquadHandler::saveSquad($new_squad_01);

        self::$new_date = new \DateTime("2019-07-02 13:37:00");

        $new_squad_02 = new Squad();
        $new_squad_02->setName("Test Squad " . StringFormatterUtils::getRandomString(10, 2));
        $new_squad_02->setDate(self::$new_date);
        $new_squad_02->setIcon("icon.jpg");
        $new_squad_02->setIconSmall("icon_small.jpg");
        $new_squad_02->setInfo("Information text ...");
        $new_squad_02->setRubric(SquadEnums::SQUAD_RUBRIC_AMATEUR);
        $new_squad_02->setIsGameSquad(true);
        $new_squad_02->setGame(
            GameHandler::getGameByGameId(1)
        );

        self::$second_squad = SquadHandler::saveSquad($new_squad_02);

        $new_clan = new Clan();
        $new_clan->setClanName("Test Clan " . StringFormatterUtils::getRandomString(10, 2));
        $new_clan->setClanTag(StringFormatterUtils::getRandomString(10, 2));
        $new_clan->setHomepage("https://gaming.myrisk-ev.de");
        $new_clan->setClanLogotype("myrisk_ev.jpg");

        self::$clan = ClanHandler::saveClan($new_clan);

    }

    public function testIfNewClanHasNoClanwarsPlayed(): void
    {

        $new_squad = new Squad();
        $new_squad->setName("Test Squad " . StringFormatterUtils::getRandomString(10, 2));
        $new_squad->setDate(self::$new_date);
        $new_squad->setIcon("icon.jpg");
        $new_squad->setIconSmall("icon_small.jpg");
        $new_squad->setInfo("Information text ...");
        $new_squad->setRubric(SquadEnums::SQUAD_RUBRIC_AMATEUR);
        $new_squad->setIsGameSquad(true);
        $new_squad->setGame(
            GameHandler::getGameByGameId(1)
        );

        $squad = SquadHandler::saveSquad($new_squad);

        $this->assertEquals(0, ClanwarHandler::getCountOfPlayedMatches($squad->getSquadId()), "New squad has not played any clanwar.");

    }

    public function testIfClanwarCanBeSavedAndUpdated(): void
    {

        $this->assertGreaterThan(0, self::$clan->getClanId(), "Clan ID is set.");

        $new_clanwar = new Clanwar();
        $new_clanwar->setSquad(
            self::$first_squad,
            array(1, 2, 3)
        );
        $new_clanwar->setOpponent(self::$clan);
        $new_clanwar->setLeague(self::$event);
        $new_clanwar->setMatchURL("https://cup.myrisk-ev.de");
        $new_clanwar->setDate(self::$old_date);

        $new_clanwar = ClanwarHandler::addMapToClanwar($new_clanwar, 1, 16, 14);
        $new_clanwar = ClanwarHandler::addMapToClanwar($new_clanwar, 2, 14, 16);
        $new_clanwar = ClanwarHandler::addMapToClanwar($new_clanwar, 3, 13, 37);

        $this->assertNull($new_clanwar->getClanwarId());

        $clanwar = ClanwarHandler::saveMatch($new_clanwar);

        $this->assertGreaterThan(0, $clanwar->getClanwarId(), "Clanwar ID is set.");
        $this->assertEquals(self::$old_date, $clanwar->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals("https://cup.myrisk-ev.de", $clanwar->getMatchHomepage(), "Match URL of clanwar is set.");
        $this->assertFalse($clanwar->getIsDefaultWin(), "Match is a default win.");
        $this->assertFalse($clanwar->getIsDefaultLoss(), "Match is not a default loss.");
        $this->assertEquals("1 : 2", $clanwar->getResult(), "Clanwar result is returned.");

        $this->assertNotNull($clanwar->getGame(), "Game of clanwar is not null");
        $this->assertInstanceOf(Game::class, $clanwar->getGame(), "Game is set!");
        $this->assertGreaterThan(0, $clanwar->getGame()->getGameId(), "Game ID is set!");
        $this->assertNotEmpty($clanwar->getGame()->getTag(), "Game tag is expected.");

        $this->assertInstanceOf(Squad::class, $clanwar->getSquad(), "Squad is set!");
        $this->assertEquals(self::$first_squad->getSquadId(), $clanwar->getSquadId(), "Squad is expected.");
        $this->assertEquals(self::$clan->getClanId(), $clanwar->getOpponent()->getClanId(), "Opponent is expected.");

        $this->assertInstanceOf(Event::class, $clanwar->getEvent(), "Event is set!");
        $this->assertEquals(1, $clanwar->getEvent()->getEventId(), "Event ID is expected.");

        $this->assertEquals(3, count($clanwar->getMaps()), "Clanwar maps are returned.");
        foreach ($clanwar->getMaps() as $clanwar_map) {
            $this->assertGreaterThan(0, $clanwar_map->getMappingId(), "Mapping ID is set.");
        }

        $changed_clanwar = $clanwar;
        $changed_clanwar->setDate(self::$new_date);
        $changed_clanwar->setStatus(ClanwarEnums::CLANWAR_STATUS_DEFAULT_WIN);
        $changed_clanwar->setSquad(
            self::$second_squad,
            array(3)
        );
        $changed_clanwar->setMatchURL("https://tv.myrisk-ev.de");

        $updated_clanwar = ClanwarHandler::saveMatch($changed_clanwar);

        $this->assertEquals($clanwar->getClanwarId(), $updated_clanwar->getClanwarId(), "Clanwar ID is set.");
        $this->assertNotEquals(self::$old_date, $updated_clanwar->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals(self::$new_date, $updated_clanwar->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals("https://tv.myrisk-ev.de", $updated_clanwar->getMatchHomepage(), "Match URL of clanwar is set.");
        $this->assertTrue($updated_clanwar->getIsDefaultWin(), "Match is a default win.");
        $this->assertFalse($updated_clanwar->getIsDefaultLoss(), "Match is not a default loss.");

        $this->assertNotNull($updated_clanwar->getGame(), "Game of clanwar is not null");
        $this->assertInstanceOf(Game::class, $updated_clanwar->getGame(), "Game is set!");
        $this->assertGreaterThan(0, $updated_clanwar->getGame()->getGameId(), "Game ID is set!");
        $this->assertNotEmpty($updated_clanwar->getGame()->getTag(), "Game tag is expected.");

        $this->assertInstanceOf(Squad::class, $updated_clanwar->getSquad(), "Squad is set!");
        $this->assertEquals(self::$second_squad->getSquadId(), $updated_clanwar->getSquadId(), "Squad is expected.");
        $this->assertEquals(self::$clan->getClanId(), $updated_clanwar->getOpponent()->getClanId(), "Opponent is expected.");

        $this->assertInstanceOf(Event::class, $updated_clanwar->getEvent(), "Event is set!");
        $this->assertEquals(1, $updated_clanwar->getEvent()->getEventId(), "Event ID is expected.");

        $this->assertEquals(3, count($updated_clanwar->getMaps()), "Clanwar maps are returned.");
        foreach ($updated_clanwar->getMaps() as $index => $clanwar_map) {
            $this->assertEquals($clanwar->getMaps()[$index]->getMappingId(), $clanwar_map->getMappingId(), "Mapping ID is set.");
        }

        $clanwar_from_database = ClanwarHandler::getClanwarByClanwarId($updated_clanwar->getClanwarId());

        $this->assertEquals($clanwar->getClanwarId(), $clanwar_from_database->getClanwarId(), "Clanwar ID is set.");
        $this->assertNotEquals(self::$old_date, $clanwar_from_database->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals(self::$new_date, $clanwar_from_database->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals("https://tv.myrisk-ev.de", $clanwar_from_database->getMatchHomepage(), "Match URL of clanwar is set.");

        $this->assertNotNull($clanwar_from_database->getGame(), "Game of clanwar is not null");
        $this->assertInstanceOf(Game::class, $clanwar_from_database->getGame(), "Game is set!");
        $this->assertGreaterThan(0, $clanwar_from_database->getGame()->getGameId(), "Game ID is set!");
        $this->assertNotEmpty($clanwar_from_database->getGame()->getTag(), "Game tag is expected.");

        $this->assertInstanceOf(Squad::class, $clanwar_from_database->getSquad(), "Squad is set!");
        $this->assertEquals(self::$second_squad->getSquadId(), $clanwar_from_database->getSquadId(), "Squad is expected.");
        $this->assertEquals(self::$clan->getClanId(), $clanwar_from_database->getOpponent()->getClanId(), "Opponent is expected.");

        $this->assertInstanceOf(Event::class, $clanwar_from_database->getEvent(), "Event is set!");
        $this->assertEquals(1, $clanwar_from_database->getEvent()->getEventId(), "Event ID is expected.");

        $recent_clanwars = ClanwarHandler::getRecentMatches(1);

        $this->assertCount(1, $recent_clanwars, "Recent clanwars are returned.");

        $recent_clanwar = $recent_clanwars[0];

        $this->assertEquals(3, count($recent_clanwar->getMaps()), "Clanwar maps are returned.");

        $first_clanwar_map = $recent_clanwar->getMaps()[0];
        $this->assertGreaterThan(0, $first_clanwar_map->getMappingId(), "Mapping ID is set.");

    }

    public function testIfDefaultLossIsSavedToClanwar(): void
    {

        $new_clanwar = new Clanwar();
        $new_clanwar->setSquad(
            self::$first_squad,
            array(1, 2, 3)
        );
        $new_clanwar->setOpponent(self::$clan);
        $new_clanwar->setLeague(self::$event);
        $new_clanwar->setMatchURL("https://gaming.myrisk-ev.de");
        $new_clanwar->setDate(self::$new_date);
        $new_clanwar->setStatus(ClanwarEnums::CLANWAR_STATUS_DEFAULT_LOSS);
        $new_clanwar->setReports(
            "Deutsche Version",
            "English version"
        );

        $clanwar = ClanwarHandler::saveMatch($new_clanwar);

        $this->assertGreaterThan(0, $clanwar->getClanwarId(), "Clanwar ID is set.");
        $this->assertEquals(self::$new_date, $clanwar->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals("https://gaming.myrisk-ev.de", $clanwar->getMatchHomepage(), "Match URL of clanwar is set.");
        $this->assertFalse($clanwar->getIsDefaultWin(), "Match is a default win.");
        $this->assertTrue($clanwar->getIsDefaultLoss(), "Match is not a default loss.");
        $this->assertEquals("Deutsche Version", $clanwar->getReportInGerman(), "German report is set.");
        $this->assertEquals("English version", $clanwar->getReportInEnglish(), "English report is set.");

        $this->assertNotNull($clanwar->getGame(), "Game of clanwar is not null");
        $this->assertInstanceOf(Game::class, $clanwar->getGame(), "Game is set!");
        $this->assertGreaterThan(0, $clanwar->getGame()->getGameId(), "Game ID is set!");
        $this->assertNotEmpty($clanwar->getGame()->getTag(), "Game tag is expected.");

        $this->assertInstanceOf(Squad::class, $clanwar->getSquad(), "Squad is set!");
        $this->assertEquals(self::$first_squad->getSquadId(), $clanwar->getSquadId(), "Squad is expected.");
        $this->assertEquals(self::$clan->getClanId(), $clanwar->getOpponent()->getClanId(), "Opponent is expected.");

        $this->assertInstanceOf(Event::class, $clanwar->getEvent(), "Event is set!");
        $this->assertEquals(1, $clanwar->getEvent()->getEventId(), "Event ID is expected.");

        $clanwar_from_database = ClanwarHandler::getClanwarByClanwarId($clanwar->getClanwarId());

        $this->assertEquals($clanwar->getClanwarId(), $clanwar_from_database->getClanwarId(), "Clanwar ID is set.");
        $this->assertEquals(self::$new_date, $clanwar->getDate(), "Timestamp of clanwar is set.");
        $this->assertEquals("https://gaming.myrisk-ev.de", $clanwar_from_database->getMatchHomepage(), "Match URL of clanwar is set.");
        $this->assertFalse($clanwar->getIsDefaultWin(), "Match is a default win.");
        $this->assertTrue($clanwar->getIsDefaultLoss(), "Match is not a default loss.");

        $this->assertNotNull($clanwar_from_database->getGame(), "Game of clanwar is not null");
        $this->assertInstanceOf(Game::class, $clanwar_from_database->getGame(), "Game is set!");
        $this->assertGreaterThan(0, $clanwar_from_database->getGame()->getGameId(), "Game ID is set!");
        $this->assertNotEmpty($clanwar_from_database->getGame()->getTag(), "Game tag is expected.");

        $this->assertInstanceOf(Squad::class, $clanwar_from_database->getSquad(), "Squad is set!");
        $this->assertEquals(self::$first_squad->getSquadId(), $clanwar_from_database->getSquadId(), "Squad is expected.");
        $this->assertEquals(self::$clan->getClanId(), $clanwar_from_database->getOpponent()->getClanId(), "Opponent is expected.");

        $this->assertInstanceOf(Event::class, $clanwar_from_database->getEvent(), "Event is set!");
        $this->assertEquals(1, $clanwar_from_database->getEvent()->getEventId(), "Event ID is expected.");

        $this->assertGreaterThan(0, ClanwarHandler::getCountOfPlayedMatches(self::$first_squad->getSquadId()), "Squad played matches.");

        $all_matches = ClanwarHandler::getAllMatchesOfSquad(self::$first_squad);

        $this->assertGreaterThan(0, count($all_matches), "Squad played matches.");

        foreach ($all_matches as $match) {
            $this->assertEquals(self::$first_squad->getSquadId(), $match->getSquad()->getSquadId(), "Squad ID is expected of match.");
        }

        $recent_matches = ClanwarHandler::getRecentMatchesOfSquad(self::$first_squad);

        $this->assertGreaterThan(0, count($recent_matches), "Squad played matches recently.");

        foreach ($recent_matches as $match) {
            $this->assertEquals(self::$first_squad->getSquadId(), $match->getSquad()->getSquadId(), "Squad ID is expected of recent match.");
        }

    }

    public function testIfUpcomingMatchIsReturned(): void
    {

        $future_date = new \DateTime("+5 days");

        $old_count_of_upcoming_matches = count(ClanwarHandler::getUpcomingMatchesOfSquad(self::$first_squad));

        $new_clanwar = new Clanwar();
        $new_clanwar->setSquad(
            self::$first_squad,
            array(1, 2, 3)
        );
        $new_clanwar->setOpponent(self::$clan);
        $new_clanwar->setLeague(self::$event);
        $new_clanwar->setMatchURL("https://gaming.myrisk-ev.de");
        $new_clanwar->setDate($future_date);
        $new_clanwar->setReports(
            "Deutsche Version",
            "English version"
        );

        ClanwarHandler::saveMatch($new_clanwar);

        $upcoming_matches = ClanwarHandler::getUpcomingMatchesOfSquad(self::$first_squad);
        $new_count_of_upcoming_matches = count($upcoming_matches);

        $this->assertGreaterThan($old_count_of_upcoming_matches, $new_count_of_upcoming_matches, "Upcoming match is detected.");

        $upcoming_clanwar = $upcoming_matches[$new_count_of_upcoming_matches - 1];

        $this->assertEquals($future_date->getTimestamp(), $upcoming_clanwar->getDate()->getTimestamp(), "Timestamp of clanwar is set.");

        foreach ($upcoming_matches as $upcoming_match) {
            $this->assertEquals(self::$first_squad->getSquadId(), $upcoming_match->getSquad()->getSquadId(), "Upcoming match squad is expected.");
        }

        $upcoming_clanwars = ClanwarHandler::getUpcomingMatches(1);

        $this->assertCount(1, $upcoming_clanwars, "Upcoming clanwars are returned.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfClanwarIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        ClanwarHandler::getClanwarByClanwarId(-1);

    }

    public function testIfUnexpectedValueExceptionIsThrownIfClanwarDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        ClanwarHandler::getClanwarByClanwarId(999999999);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSquadIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        ClanwarHandler::getCountOfPlayedMatches(-1);

    }

}
