<?php

declare(strict_types=1);

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

        $squad_id = 1;
        $event_name = self::$CONST_EVENT_PREFIX . ' ' . StringFormatterUtils::getRandomString(10);
        $date = new \DateTime("2001-12-06 12:34:56");

        $new_event = new Event();
        $new_event->setName($event_name);
        $new_event->setDate($date);
        $new_event->setHomepage('https://tv.myrisk-ev.de');
        $new_event->setSquad(
            SquadHandler::getSquadBySquadId($squad_id)
        );
        $new_event->setIsActive(true);

        $old_event_count = count(EventHandler::getEventsOfSquad($squad_id));

        $event = EventHandler::saveEvent($new_event);

        $this->assertGreaterThan(0, $event->getEventId(), "Event ID is set.");
        $this->assertEquals($event_name, $event->getName(), "Event name is set.");
        $this->assertEquals($date, $event->getDate(), "Event date is set.");
        $this->assertEquals('https://tv.myrisk-ev.de', $event->getHomepage(), "Event URL is set.");
        $this->assertEquals(1, $event->getSquadId(), "Squad ID of event is set.");
        $this->assertFalse($event->isOffline(), "Event is played online per default.");
        $this->assertEquals("tv.myrisk-ev", $event->getLeagueCategory(), "League category is set.");
        $this->assertTrue($event->isActive(), "Event is active!");

        $this->assertTrue(EventHandler::isExistingEvent($event->getEventId()), "Event is saved into database.");

        $events_of_squad = EventHandler::getEventsOfSquad($squad_id);
        $this->assertNotEmpty($events_of_squad, "Events are returnd");
        $this->assertGreaterThan($old_event_count, count($events_of_squad), "New event of squad is recognized.");
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
        $this->assertFalse($tmp_event->isOffline(), "Event is played online per default.");
        $this->assertFalse($tmp_event->isActive(), "An event is not active per default!");

        $tmp_event->setHomepage('https://cj.myrisk-ev.de');
        $tmp_event->setIsOffline(true);
        $tmp_event->setIsActive(true);

        EventHandler::saveEvent($tmp_event);

        $reloaded_event = EventHandler::getEventById($tmp_event->getEventId());

        $this->assertEquals($tmp_event->getEventId(), $reloaded_event->getEventId(), "Event ID is set.");
        $this->assertEquals($event_name, $reloaded_event->getName(), "Event name is set.");
        $this->assertEquals($date, $reloaded_event->getDate(), "Event date is set.");
        $this->assertEquals('https://cj.myrisk-ev.de', $reloaded_event->getHomepage(), "Event URL is set.");
        $this->assertEquals(1, $reloaded_event->getSquadId(), "Squad ID of event is set.");
        $this->assertTrue($reloaded_event->isOffline(), "Event is played online per default.");
        $this->assertTrue($reloaded_event->isActive(), "The event is active now!");

        $this->assertTrue(EventHandler::isExistingEvent($reloaded_event->getEventId()), "Event is saved into database.");

        $this->assertTrue(EventHandler::removeEventById($reloaded_event->getEventId()), "Event is deleted.");
    }

    public function testIfEventInstanceCanBeCreatedWithoutAHomepage(): void
    {

        $squad_id = 1;
        $event_name = self::$CONST_EVENT_PREFIX . ' ' . StringFormatterUtils::getRandomString(10);
        $date = new \DateTime("10 days ago");

        $new_event = new Event();
        $new_event->setName($event_name);
        $new_event->setDate($date);
        $new_event->setSquad(
            SquadHandler::getSquadBySquadId($squad_id)
        );

        $event = EventHandler::saveEvent($new_event);

        $this->assertGreaterThan(0, $event->getEventId(), "Event ID is set.");

        $reloaded_event = EventHandler::getEventById($event->getEventId());

        $this->assertNull($reloaded_event->getHomepage(), "Homepage is not set, as expected.");
    }

    public function testIfFalseIsReturnedIfEventIdIsInvalid(): void
    {
        $this->assertFalse(EventHandler::isExistingEvent(-1));
    }

    public function testIfInvalidArgumentExceptionIsThrownIfEventIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        EventHandler::getEventById(-1);
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
