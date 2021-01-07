<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Event;


final class EventTest extends TestCase
{

    public function testIfUnexpectedValueExceptionIsThrownIfHomepageValueIsInvalid(): void
    {

        $this->expectException(UnexpectedValueException::class);

        $event = new Event();
        $event->setHomepage("this_is_not_an_link");

    }

    public function testIfNullIsReturndIfSquadIsNotSet(): void
    {

        $event = new Event();

        $this->assertNull($event->getSquadId(), "Squad ID is null if squad is not set.");

    }

}
