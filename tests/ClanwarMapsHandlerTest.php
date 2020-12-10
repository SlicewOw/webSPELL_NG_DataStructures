<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\Handler\ClanwarMapsHandler;

final class ClanwarMapsHandlerTest extends TestCase
{

    public function testIfNoMappingIsFoundIfClanwarDoesNotExist(): void
    {
        $this->assertFalse(ClanwarMapsHandler::isAnyMapSavedForClanwar(999999999), "No mapping is found!");
    }

}
