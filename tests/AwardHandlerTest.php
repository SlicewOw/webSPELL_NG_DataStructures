<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\Award;
use \webspell_ng\Event;
use \webspell_ng\Handler\AwardHandler;
use \webspell_ng\Handler\EventHandler;
use \webspell_ng\Handler\SquadHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class AwardHandlerTest extends TestCase
{

    private static $CONST_AWARD_PREFIX = "Das ist ein Test Award";
    private static $CONST_AWARD_CHANGED_PREFIX = "Das ist ein ver&auml;nderter Test Award";

    private static $CONST_EVENT_PREFIX = "Test Event";

    private static $CONST_MYRISK_GAMING_URL = "https://gaming.myrisk-ev.de";
    private static $CONST_MYRISK_CUP_URL = "https://cup.myrisk-ev.de";

    public function testIfInvalidArgumentExceptionIsThrownIfAwardIdIsLessThanOne(): void
    {

        $this->expectException(InvalidArgumentException::class);

        AwardHandler::getAwardById(-1);

    }

    public function testIfAwardCannotBeSavedIfDateIsNotSet(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        AwardHandler::saveAward($award);

    }

    public function testIfAwardCannotBeSavedIfTitleIsNotSet(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        $award->setDate(new \DateTime("now"));

        AwardHandler::saveAward($award);

    }

    public function testIfAwardCannotBeSavedIfUrlIsNotSet(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        $award->setDate(new \DateTime("now"));
        $award->setName(self::$CONST_AWARD_PREFIX);

        AwardHandler::saveAward($award);

    }

    public function testIfAwardCannotBeSavedIfRankIsNotSet(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        $award->setDate(new \DateTime("now"));
        $award->setName(self::$CONST_AWARD_PREFIX);
        $award->setHomepage(self::$CONST_MYRISK_GAMING_URL);

        AwardHandler::saveAward($award);

    }

    public function testIfAwardCannotBeSavedIfSquadIsNotSet(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $award = new Award();
        $award->setDate(new \DateTime("now"));
        $award->setName(self::$CONST_AWARD_PREFIX);
        $award->setHomepage(self::$CONST_MYRISK_GAMING_URL);
        $award->setRank(3);

        AwardHandler::saveAward($award);

    }

    public function testIfAwardCanBeSavedWithMinimalParameters(): void
    {

        $date = new \DateTime("2000-01-01 20:13:37");

        $new_award = new Award();
        $new_award->setDate($date);
        $new_award->setName(self::$CONST_AWARD_PREFIX);
        $new_award->setHomepage(self::$CONST_MYRISK_GAMING_URL);
        $new_award->setRank(1);
        $new_award->setSquad(
            SquadHandler::getSquadBySquadId(1)
        );

        $old_award_count = count(AwardHandler::getAwardsOfSquad(1));

        $tmp_award = AwardHandler::saveAward($new_award);

        $this->assertGreaterThan(0, $tmp_award->getAwardId());

        $award = AwardHandler::getAwardById($tmp_award->getAwardId());
        $this->assertEquals($award->getAwardId(), $tmp_award->getAwardId(), "Award ID is set!");
        $this->assertEquals(self::$CONST_AWARD_PREFIX, $award->getName(), "Name des Awards ist editiert!");
        $this->assertEquals(1, $award->getRank(), "Rank des Awards ist editiert!");
        $this->assertEquals($award->getHomepage(), self::$CONST_MYRISK_GAMING_URL, "Homepage des Awards ist editiert!");
        $this->assertFalse($award->getOffline(), "Der Award wurde online erspielt!");
        $this->assertEquals(1, $award->getSquadId(), "Ein Squad hat den Award erspielt!");
        $this->assertEquals($date, $award->getDate(), "Date is set.");
        $this->assertEquals("gaming.myrisk-ev", $award->getLeagueCategory(), "League category is set.");

        $awards_of_squad = AwardHandler::getAwardsOfSquad(1);
        $this->assertNotEmpty($awards_of_squad, "Awards are returnd");
        $this->assertGreaterThan($old_award_count, count($awards_of_squad), "New award of squad is recognized.");

    }

    public function testIfAwardCanBeSavedWithAllParameters(): void
    {

        $event_date = new \DateTime("1999-05-05 01:13:37");
        $award_date = new \DateTime("1998-05-05 13:37:01");

        $squad = SquadHandler::getSquadBySquadId(1);

        $new_event = new Event();
        $new_event->setName(self::$CONST_EVENT_PREFIX . ' ' . StringFormatterUtils::getRandomString(10, 3));
        $new_event->setDate($event_date);
        $new_event->setHomepage(self::$CONST_MYRISK_CUP_URL);
        $new_event->setSquad($squad);

        $event = EventHandler::saveEvent($new_event);

        $this->assertGreaterThan(0, $event->getEventId(), "Event is saved successfully.");

        $new_award = new Award();
        $new_award->setDate($award_date);
        $new_award->setName(self::$CONST_AWARD_PREFIX);
        $new_award->setHomepage(self::$CONST_MYRISK_GAMING_URL);
        $new_award->setRank(1);
        $new_award->setOffline(true);
        $new_award->setEvent($event);
        $new_award->setDescription("Dies ist eine Beschreibung des Awards");
        $new_award->setSquad($squad);

        $tmp_award = AwardHandler::saveAward($new_award);

        $this->assertGreaterThan(0, $tmp_award->getAwardId());

        $award = AwardHandler::getAwardById($tmp_award->getAwardId());
        $this->assertEquals(self::$CONST_AWARD_PREFIX, $award->getName(), "Name des Awards ist editiert!");
        $this->assertEquals(1, $award->getRank(), "Rank des Awards ist editiert!");
        $this->assertEquals(self::$CONST_MYRISK_GAMING_URL, $award->getHomepage(), "Homepage des Awards ist editiert!");
        $this->assertTrue($award->getOffline(), "Der Award wurde offline erspielt!");
        $this->assertEquals(1, $award->getSquadId(), "Squad wurde gesetzt, welches den Award erspielt hat!");
        $this->assertEquals("Dies ist eine Beschreibung des Awards", $award->getDescription(), "Award Beschreibung wurde gespeichert!");
        $this->assertEquals($event->getEventId(), $award->getEventId(), "Event wurde gesetzt, welches den Award erspielt hat!");
        $this->assertEquals($award_date, $award->getDate(), "Date is set.");

    }

    public function testIfAwardCanBeUpdated(): void
    {

        $date = new \DateTime("1999-05-05 01:13:37");

        $new_award = new Award();
        $new_award->setDate($date);
        $new_award->setName(self::$CONST_AWARD_PREFIX);
        $new_award->setHomepage(self::$CONST_MYRISK_GAMING_URL);
        $new_award->setRank(1);
        $new_award->setSquad(
            SquadHandler::getSquadBySquadId(1)
        );

        $new_award = AwardHandler::saveAward($new_award);

        $new_award_id = $new_award->getAwardId();

        // Changed values
        $changed_date = new \DateTime("1992-08-25 00:00:00");

        $changed_award = AwardHandler::getAwardById($new_award_id);
        $changed_award->setRank(2);
        $changed_award->setDate($changed_date);
        $changed_award->setHomepage(self::$CONST_MYRISK_CUP_URL);
        $changed_award->setName(self::$CONST_AWARD_CHANGED_PREFIX);

        $tmp_award = AwardHandler::saveAward($changed_award);

        $this->assertEquals($new_award_id, $tmp_award->getAwardId());

        $award = AwardHandler::getAwardById($tmp_award->getAwardId());
        $this->assertEquals(self::$CONST_AWARD_CHANGED_PREFIX, $award->getName(), "Name des Awards ist editiert!");
        $this->assertEquals(2, $award->getRank(), "Rank des Awards ist editiert!");
        $this->assertEquals(self::$CONST_MYRISK_CUP_URL, $award->getHomepage(), "Homepage des Awards ist editiert!");
        $this->assertFalse($award->getOffline(), "Der Award wurde online erspielt!");
        $this->assertEquals(1, $award->getSquadId(), "Squad wurde gesetzt, welches den Award erspielt hat!");
        $this->assertEquals($changed_date, $award->getDate(), "Date is set.");

    }

    public function testIfUnexpectedValueExceptionIsThrownIfAwardDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        AwardHandler::getAwardById(999999999);

    }

}
