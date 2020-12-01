<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Squad;
use webspell_ng\Enums\SquadEnums;
use webspell_ng\Handler\GameHandler;
use webspell_ng\Handler\SquadHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class SquadHandlerTest extends TestCase
{

    public function testIfSquadCanBeSavedAndUpdated(): void
    {

        $squad_name = "Test Squad " . StringFormatterUtils::getRandomString(10);
        $date = new \DateTime("2020-0" . rand(1, 9) . "-1" . rand(0, 9) ." 12:34:30");

        $new_squad = new Squad();
        $new_squad->setName($squad_name);
        $new_squad->setDate($date);
        $new_squad->setIcon("icon.jpg");
        $new_squad->setIconSmall("icon_small.jpg");
        $new_squad->setInfo("Information text ...");
        $new_squad->setRubric(SquadEnums::SQUAD_RUBRIC_AMATEUR);
        $new_squad->setIsGameSquad(true);
        $new_squad->setGame(
            GameHandler::getGameByGameId(1)
        );

        $squad = SquadHandler::saveSquad($new_squad);

        $this->assertEquals($squad_name, $squad->getName(), "Squad name is set.");
        $this->assertEquals($date, $squad->getDate(), "Squad date is set.");
        $this->assertEquals(SquadEnums::SQUAD_RUBRIC_AMATEUR, $squad->getRubric(), "Squad rubric is set.");
        $this->assertTrue($squad->getIsActive(), "Squad is active.");
        $this->assertTrue($squad->getIsGameSquad(), "Squad is a gamesquad.");
        $this->assertEquals(1, $squad->getGame()->getGameId(), "Squad game ID is set.");
        $this->assertEquals("cs", $squad->getGame()->getTag(), "Squad game tag is set.");
        $this->assertEquals("Information text ...", $squad->getInfo(), "Squad info is set.");
        $this->assertEquals("icon.jpg", $squad->getIcon(), "Squad icon is set.");
        $this->assertEquals("icon_small.jpg", $squad->getIconSmall(), "Squad icon small is set.");
        $this->assertEquals(0, $squad->getHits(), "Squad hits is set.");
        $this->assertFalse($squad->getIsDeleted(), "Squad is not deleted.");

        $changed_squad_name = "Test Squad " . StringFormatterUtils::getRandomString(10);

        $updated_squad = SquadHandler::getSquadBySquadId($squad->getSquadId());
        $updated_squad->setName($changed_squad_name);
        $updated_squad->setRubric(SquadEnums::SQUAD_RUBRIC_PROFESSIONAL);

        SquadHandler::saveSquad($updated_squad);

        $changed_squad = SquadHandler::getSquadBySquadId($updated_squad->getSquadId());

        $this->assertEquals($changed_squad_name, $changed_squad->getName(), "Squad name is set.");
        $this->assertEquals($date, $changed_squad->getDate(), "Squad date is set.");
        $this->assertEquals(SquadEnums::SQUAD_RUBRIC_PROFESSIONAL, $changed_squad->getRubric(), "Squad rubric is set.");
        $this->assertTrue($changed_squad->getIsActive(), "Squad is active.");
        $this->assertTrue($changed_squad->getIsGameSquad(), "Squad is a gamesquad.");
        $this->assertEquals(1, $changed_squad->getGame()->getGameId(), "Squad game ID is set.");
        $this->assertEquals("cs", $changed_squad->getGame()->getTag(), "Squad game tag is set.");
        $this->assertEquals("Information text ...", $changed_squad->getInfo(), "Squad info is set.");
        $this->assertEquals("icon.jpg", $changed_squad->getIcon(), "Squad icon is set.");
        $this->assertEquals("icon_small.jpg", $changed_squad->getIconSmall(), "Squad icon small is set.");
        $this->assertEquals(0, $changed_squad->getHits(), "Squad hits is set.");
        $this->assertFalse($changed_squad->getIsDeleted(), "Squad is not deleted.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSquadIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        $squad = SquadHandler::getSquadBySquadId(-1);

        // This line is hopefully never be reached
        $this->assertLessThan(1, $squad->getSquadId());

    }

    public function testIfInvalidArgumentExceptionIsThrownIfSquadDoesNotExist(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SquadHandler::getSquadBySquadId(9999999);

    }

}