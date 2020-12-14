<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use webspell_ng\Handler\GameHandler;
use webspell_ng\SquadMemberPosition;
use webspell_ng\Handler\SquadMemberPositionHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class SquadMemberPositionHandlerTest extends TestCase
{

    public function testIfSquadMemberPositionCanBeSavedAndUpdated(): void
    {

        $position_name = "Position " . StringFormatterUtils::getRandomString(10);
        $position_tag = StringFormatterUtils::getRandomString(3);
        $sort = rand(100, 999999);

        $new_position = new SquadMemberPosition();
        $new_position->setName($position_name);
        $new_position->setTag($position_tag);
        $new_position->setSort($sort);

        $position = SquadMemberPositionHandler::saveMemberPosition($new_position);

        $this->assertGreaterThan(0, $position->getPositionId(), "Position ID is set.");
        $this->assertEquals($position_name, $position->getName(), "Position name is set");
        $this->assertEquals($position_tag, $position->getTag(), "Position tag is set");
        $this->assertEquals($sort, $position->getSort(), "Position sort is set");
        $this->assertNull($position->getGame(), "Game is not set!");

        $changed_position_name = "Position " . StringFormatterUtils::getRandomString(10);
        $changed_sort = rand(100, 999999);

        $position->setName($changed_position_name);
        $position->setSort($changed_sort);
        $position->setGame(
            GameHandler::getGameByGameId(1)
        );

        SquadMemberPositionHandler::saveMemberPosition($position);

        $updated_position = SquadMemberPositionHandler::getMemberPositionById($position->getPositionId());

        $this->assertGreaterThan(0, $updated_position->getPositionId(), "Position ID is set.");
        $this->assertEquals($changed_position_name, $updated_position->getName(), "Position name is set");
        $this->assertEquals($position_tag, $updated_position->getTag(), "Position tag is set");
        $this->assertEquals($changed_sort, $updated_position->getSort(), "Position sort is set");
        $this->assertNotNull($updated_position->getGame(), "Game is set!");
        $this->assertEquals(1, $position->getGame()->getGameId(), "Game ID is set.");

    }

}
