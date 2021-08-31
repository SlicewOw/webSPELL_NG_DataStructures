<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\User;
use webspell_ng\Enums\UserEnums;
use webspell_ng\Handler\CountryHandler;
use webspell_ng\Handler\UserHandler;
use webspell_ng\Utils\StringFormatterUtils;


final class UserHandlerTest extends TestCase
{

    public function testIfUserCanBeSavedAndReadFromDatabase(): void
    {

        $username = "Test User ä ß " . StringFormatterUtils::getRandomString(10);
        $firstname = StringFormatterUtils::getRandomString(10);

        $new_user = new User();
        $new_user->setUsername($username);
        $new_user->setFirstname($firstname);
        $new_user->setEmail(StringFormatterUtils::getRandomString(10) . "@myrisk-ev.de");
        $new_user->setSex(UserEnums::SEXUALITY_WOMAN);
        $new_user->setTown(StringFormatterUtils::getRandomString(10));
        $new_user->setBirthday(new \DateTime("2020-09-04 00:00:00"));
        $new_user->setCountry(
            CountryHandler::getCountryByCountryShortcut("uk")
        );

        $saved_user = UserHandler::saveUser($new_user);

        $this->assertGreaterThan(0, $saved_user->getUserId(), "User ID is set.");
        $this->assertEquals($username, $saved_user->getUsername(), "Username is set.");
        $this->assertGreaterThan(0, $saved_user->getUserId(), "User ID is set.");
        $this->assertEquals($saved_user->getUserId(), $saved_user->getUserId(), "User ID is expected.");
        $this->assertEquals($username, $saved_user->getUsername(), "Username is set.");
        $this->assertEmpty($saved_user->getLastname(), "Lastname is set as empty.");
        $this->assertEquals("United Kingdom", $saved_user->getCountry()->getName(), "Country name of user is set.");
        $this->assertEquals("uk", $saved_user->getCountry()->getShortcut(), "Country shortcut of user is set.");
        $this->assertNull($saved_user->getFirstLoginDate(), "First login date is NULL per default.");
        $this->assertNull($saved_user->getLastLoginDate(), "Last login date is NULL per default.");

        $changed_lastname = StringFormatterUtils::getRandomString(10);

        $changed_user = $saved_user;
        $changed_user->setLastname($changed_lastname);

        $updated_user = UserHandler::saveUser($changed_user);

        $this->assertEquals(User::class, get_class($updated_user), "Instance of class 'User' is returned.");
        $this->assertEquals($saved_user->getUserId(), $updated_user->getUserId(), "User ID is set.");
        $this->assertEquals($saved_user->getUserId(), $updated_user->getUserId(), "User ID is expected.");
        $this->assertEquals($username, $updated_user->getUsername(), "Username is set.");
        $this->assertEquals($firstname, $updated_user->getFirstname(), "Firstname is set.");
        $this->assertEquals($changed_lastname, $updated_user->getLastname(), "Lastname is set.");
        $this->assertEquals("United Kingdom", $updated_user->getCountry()->getName(), "Country name of user is set.");
        $this->assertEquals("uk", $updated_user->getCountry()->getShortcut(), "Country shortcut of user is set.");
        $this->assertNull($updated_user->getFirstLoginDate(), "First login date is NULL per default.");
        $this->assertNull($updated_user->getLastLoginDate(), "Last login date is NULL per default.");

        $logged_in_user = UserHandler::loginUser($updated_user);

        $this->assertEquals($saved_user->getUserId(), $logged_in_user->getUserId(), "User ID is set.");
        $this->assertNotNull($logged_in_user->getFirstLoginDate(), "First login date is set.");
        $this->assertNotNull($logged_in_user->getLastLoginDate(), "Last login date is set.");

        $last_login_datetime = new \DateTime("now");
        $last_login_datetime->add(
            new \DateInterval("PT5M")
        );

        $second_update_of_user = clone $logged_in_user;
        $second_update_of_user->setLastLoginDate($last_login_datetime);

        $custom_datetime_objects_of_user = UserHandler::saveUser($second_update_of_user);

        $this->assertEquals($saved_user->getUserId(), $custom_datetime_objects_of_user->getUserId(), "User ID is set.");
        $this->assertNotNull($custom_datetime_objects_of_user->getFirstLoginDate(), "First login date is NULL per default.");
        $this->assertEquals($logged_in_user->getFirstLoginDate(), $custom_datetime_objects_of_user->getFirstLoginDate(), "First login date is equal.");
        $this->assertNotNull($custom_datetime_objects_of_user->getLastLoginDate(), "Last login date is set.");
        $this->assertGreaterThan($logged_in_user->getLastLoginDate(), $custom_datetime_objects_of_user->getLastLoginDate(), "Last login date is newer than before.");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfUserIdIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        UserHandler::getUserByUserId(-1);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfUserDoesNotExist(): void
    {

        $this->expectException(InvalidArgumentException::class);

        UserHandler::getUserByUserId(99999999);

    }

    public function testIfInvalidArgumentExceptionIsThrownIfUserHasNoEmail(): void
    {

        $this->expectException(InvalidArgumentException::class);

        UserHandler::saveUser(new User());

    }

}
