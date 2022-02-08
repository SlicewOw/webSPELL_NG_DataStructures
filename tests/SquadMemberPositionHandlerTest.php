<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Game;
use webspell_ng\SquadMemberPosition;
use webspell_ng\Handler\GameHandler;
use webspell_ng\Handler\SquadMemberPositionHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class SquadMemberPositionHandlerTest extends TestCase
{

    /**
     * @var SquadMemberPosition $global_position_with_game
     */
    private static $global_position_with_game;

    /**
     * @var SquadMemberPosition $global_position_without_game
     */
    private static $global_position_without_game;

    /**
     * @var Game $game
     */
    private static $game;

    public static function setUpBeforeClass(): void
    {

        self::$game = GameHandler::getGameByGameId(1);

        $new_position = new SquadMemberPosition();
        $new_position->setName("Position with Game " . StringFormatterUtils::getRandomString(10));
        $new_position->setTag(StringFormatterUtils::getRandomString(5));
        $new_position->setGame(self::$game);
        $new_position->setSort(rand(100, 999999));

        self::$global_position_with_game = SquadMemberPositionHandler::saveMemberPosition($new_position);

        $new_position = new SquadMemberPosition();
        $new_position->setName("Position without Game " . StringFormatterUtils::getRandomString(10));
        $new_position->setTag(StringFormatterUtils::getRandomString(5));
        $new_position->setSort(rand(100, 999999));

        self::$global_position_without_game = SquadMemberPositionHandler::saveMemberPosition($new_position);

    }

    public function testIfSquadMemberPositionCanBeSavedAndUpdated(): void
    {

        $position_name = "Position " . StringFormatterUtils::getRandomString(10);
        $position_tag = StringFormatterUtils::getRandomString(6);
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
        $position->setGame(self::$game);

        SquadMemberPositionHandler::saveMemberPosition($position);

        $updated_position = SquadMemberPositionHandler::getMemberPositionById($position->getPositionId());

        $this->assertGreaterThan(0, $updated_position->getPositionId(), "Position ID is set.");
        $this->assertEquals($changed_position_name, $updated_position->getName(), "Position name is set");
        $this->assertEquals($position_tag, $updated_position->getTag(), "Position tag is set");
        $this->assertEquals($changed_sort, $updated_position->getSort(), "Position sort is set");
        $this->assertNotNull($updated_position->getGame(), "Game is set!");
        $this->assertEquals(self::$game->getGameId(), $position->getGame()->getGameId(), "Game ID is set.");

    }

    public function testIfPostionIsFoundUsingTheTag(): void
    {

        $this->assertNotEmpty(self::$global_position_without_game->getTag());

        $member_position = SquadMemberPositionHandler::getMemberPositionByParameters(self::$global_position_without_game->getTag(), null);

        $this->assertGreaterThan(0, $member_position->getPositionId(), "Position ID is set.");
        $this->assertEquals(self::$global_position_without_game->getName(), $member_position->getName(), "Position name is set");
        $this->assertEquals(self::$global_position_without_game->getTag(), $member_position->getTag(), "Position tag is set");
        $this->assertEquals(self::$global_position_without_game->getSort(), $member_position->getSort(), "Position sort is set");
        $this->assertNull($member_position->getGame(), "Game is NULL!");

    }

    public function testIfPostionIsFoundUsingTheTagAndGame(): void
    {

        $this->assertNotEmpty(self::$global_position_with_game->getTag());
        $this->assertInstanceOf(Game::class, self::$game);

        $member_position = SquadMemberPositionHandler::getMemberPositionByParameters(self::$global_position_with_game->getTag(), self::$game);

        $this->assertGreaterThan(0, $member_position->getPositionId(), "Position ID is set.");
        $this->assertEquals(self::$global_position_with_game->getName(), $member_position->getName(), "Position name is set");
        $this->assertEquals(self::$global_position_with_game->getTag(), $member_position->getTag(), "Position tag is set");
        $this->assertEquals(self::$global_position_with_game->getSort(), $member_position->getSort(), "Position sort is set");
        $this->assertNotNull($member_position->getGame(), "Game is set!");
        $this->assertEquals(self::$game->getGameId(), $member_position->getGame()->getGameId(), "Game ID is set.");

    }

    public function testIfAllMemberPositionsAreReturned(): void
    {

        $all_member_positions = SquadMemberPositionHandler::getAllMemberPositions();

        $this->assertNotEmpty($all_member_positions);

        $global_position_found = false;

        foreach ($all_member_positions as $member_position) {

            if ($member_position->getPositionId() == self::$global_position_with_game->getPositionId()) {
                $global_position_found = true;
            }

        }

        $this->assertTrue($global_position_found, "Global saved member position found.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfMemberPositionIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SquadMemberPositionHandler::getMemberPositionById(-1);

    }

    public function testIfUnexpectedValueExceptionIsThrownIfMemberPositionDoesNotExist(): void
    {

        $this->expectException(UnexpectedValueException::class);

        SquadMemberPositionHandler::getMemberPositionById(99999999);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfMemberPositionTagIsEmpty(): void
    {

        $this->expectException(InvalidArgumentException::class);

        SquadMemberPositionHandler::getMemberPositionByParameters("");

    }

}
