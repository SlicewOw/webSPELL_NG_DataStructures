<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Squad;
use webspell_ng\SquadMember;
use webspell_ng\SquadMemberPosition;
use webspell_ng\Enums\SquadEnums;
use webspell_ng\Handler\GameHandler;
use webspell_ng\Handler\SquadHandler;
use webspell_ng\Handler\SquadMemberHandler;
use webspell_ng\Handler\SquadMemberPositionHandler;
use webspell_ng\Handler\UserHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class SquadMemberHandlerTest extends TestCase
{

    public function testIfSquadMemberPositionCanBeSavedAndUpdated(): void
    {

        $game = GameHandler::getGameByGameId(1);

        $new_squad = new Squad();
        $new_squad->setName("Test Squad " . StringFormatterUtils::getRandomString(10));
        $new_squad->setDate(new \DateTime("now"));
        $new_squad->setIcon("icon.jpg");
        $new_squad->setIconSmall("icon_small.jpg");
        $new_squad->setInfo("Information text ...");
        $new_squad->setRubric(SquadEnums::SQUAD_RUBRIC_AMATEUR);
        $new_squad->setIsGameSquad(true);
        $new_squad->setGame($game);

        $squad = SquadHandler::saveSquad($new_squad);

        $new_position = new SquadMemberPosition();
        $new_position->setName("Test Position " . StringFormatterUtils::getRandomString(10));
        $new_position->setTag(StringFormatterUtils::getRandomString(10));
        $new_position->setSort(
            rand(100, 999999)
        );
        $new_position->setGame($game);

        $position = SquadMemberPositionHandler::saveMemberPosition($new_position);

        $new_member = new SquadMember();
        $new_member->setMemberPosition($position);
        $new_member->setIsActive(true);
        $new_member->setUser(
            UserHandler::getUserByUserId(1)
        );

        $member = SquadMemberHandler::saveSquadMember($squad, $new_member);

        $this->assertGreaterThan(0, $member->getMemberId(), "Member ID is set.");

        $saved_squad = SquadHandler::getSquadBySquadId($squad->getSquadId());

        $this->assertEquals(1, count($saved_squad->getMembers()), "Member is set.");

        $member = $saved_squad->getMembers()[0];
        $this->assertEquals(1, $member->getUser()->getUserId(), "User data of member is set.");
        $this->assertGreaterThan(0, $member->getJoinDate()->getTimestamp(), "Join date is set.");

    }

}
