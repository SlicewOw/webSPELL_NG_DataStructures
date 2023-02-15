<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Event;


final class EventTest extends TestCase
{

    public function testIfNullIsReturndIfSquadIsNotSet(): void
    {

        $event = new Event();

        $this->assertNull($event->getSquadId(), "Squad ID is null if squad is not set.");
    }
}
