<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\Event;
use \webspell_ng\Handler\EventHandler;
use \webspell_ng\Handler\LeagueCategoryHandler;
use \webspell_ng\Handler\SquadHandler;
use \webspell_ng\Utils\StringFormatterUtils;


final class EventHandlerTest extends TestCase
{

    private static $CONST_EVENT_PREFIX = "Test Event";

    public function testIfEventInstanceCanBeCreated(): void
    {

        $event_name = self::$CONST_EVENT_PREFIX . ' ' . StringFormatterUtils::getRandomString(10);
        $date = new \DateTime("2001-12-06 12:34:56");

        $new_event = new Event();
        $new_event->setName($event_name);
        $new_event->setDate($date);
        $new_event->setHomepage('https://tv.myrisk-ev.de');
        $new_event->setSquad(
            SquadHandler::getSquadBySquadId(1)
        );

        $event = EventHandler::saveEvent($new_event);

        $this->assertGreaterThan(0, $event->getEventId(), "Event ID is set.");
        $this->assertEquals($event_name, $event->getName(), "Event name is set.");
        $this->assertEquals($date, $event->getDate(), "Event date is set.");
        $this->assertEquals('https://tv.myrisk-ev.de', $event->getHomepage(), "Event URL is set.");
        $this->assertEquals(1, $event->getSquadId(), "Squad ID of event is set.");
        $this->assertFalse($event->getIsOffline(), "Event is played online per default.");
        $this->assertEquals("tv.myrisk-ev", $event->getLeagueCategory(), "League category is set.");

        $this->assertTrue(EventHandler::isExistingEvent($event->getEventId()), "Event is saved into database.");

    }

    public function testIfEventCanBeUpdated(): void
    {

        $event_name = self::$CONST_EVENT_PREFIX . ' ' . StringFormatterUtils::getRandomString(10);
        $date = new \DateTime("2001-12-06 12:34:56");

        $new_event = new Event();
        $new_event->setName($event_name);
        $new_event->setDate($date);
        $new_event->setHomepage('https://tv.myrisk-ev.de');
        $new_event->setSquad(
            SquadHandler::getSquadBySquadId(1)
        );

        $tmp_event = EventHandler::saveEvent($new_event);

        $this->assertGreaterThan(0, $tmp_event->getEventId(), "Event ID is set.");

        $tmp_event->setHomepage('https://cj.myrisk-ev.de');
        $tmp_event->setIsOffline(true);

        $event = EventHandler::saveEvent($tmp_event);

        $this->assertEquals($tmp_event->getEventId(), $event->getEventId(), "Event ID is set.");
        $this->assertEquals($event_name, $event->getName(), "Event name is set.");
        $this->assertEquals($date, $event->getDate(), "Event date is set.");
        $this->assertEquals('https://cj.myrisk-ev.de', $event->getHomepage(), "Event URL is set.");
        $this->assertEquals(1, $event->getSquadId(), "Squad ID of event is set.");
        $this->assertTrue($event->getIsOffline(), "Event is played online per default.");

        $this->assertTrue(EventHandler::isExistingEvent($event->getEventId()), "Event is saved into database.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfEventIdIsInvalid_01(): void
    {

        $this->expectException(InvalidArgumentException::class);

        EventHandler::isExistingEvent(-1);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfEventIdIsInvalid_02(): void
    {

        $this->expectException(InvalidArgumentException::class);

        EventHandler::getEventById(-1);

    }

    public function testIfUnexpectedValueExceptionIsRaisedIfParentIdIsInvalid(): void
    {

        $this->expectException(TypeError::class);

        LeagueCategoryHandler::setEventLeagueCategory(new Event());

    }

    public function testIfInvalidArgumentExceptionIsThrownIfEventDoesNotExist(): void
    {

        $this->expectException(InvalidArgumentException::class);

        EventHandler::getEventById(999999999);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSquadIsNotSet(): void
    {

        $this->expectException(InvalidArgumentException::class);

        EventHandler::saveEvent(new Event());

    }

}
