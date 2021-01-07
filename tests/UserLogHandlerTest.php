<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\User;
use webspell_ng\Enums\UserEnums;
use webspell_ng\Handler\CountryHandler;
use webspell_ng\Handler\UserHandler;
use webspell_ng\Handler\UserLogHandler;
use webspell_ng\Utils\StringFormatterUtils;

final class UserLogHandlerTest extends TestCase
{

    public function testIfNewUserHasOneUserLog(): void
    {

        $username = "Test User " . StringFormatterUtils::getRandomString(10);
        $old_date = new \DateTime("1 minute ago");

        $new_user = new User();
        $new_user->setUsername($username);
        $new_user->setFirstname(StringFormatterUtils::getRandomString(10));
        $new_user->setLastname(StringFormatterUtils::getRandomString(10));
        $new_user->setEmail(StringFormatterUtils::getRandomString(10) . "@webspell-ng.de");
        $new_user->setSex(UserEnums::SEXUALITY_DIVERS);
        $new_user->setTown(StringFormatterUtils::getRandomString(10));
        $new_user->setBirthday(new \DateTime("2020-09-04 00:00:00"));
        $new_user->setCountry(
            CountryHandler::getCountryByCountryShortcut("uk")
        );

        $user = UserHandler::saveUser($new_user);

        $user_logs = UserLogHandler::getLogsOfUser($user);

        $this->assertEquals(1, count($user_logs), "Count of user logs is expected.");

        $user_log = $user_logs[0];

        $this->assertEquals($username, $user_log->getUsername(), "Username is set.");
        $this->assertGreaterThan($old_date, $user_log->getDate(), "Date is set.");

    }

}
