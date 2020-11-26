<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\User;
use \webspell_ng\Enums\UserEnums;
use \webspell_ng\Handler\CountryHandler;
use \webspell_ng\Handler\UserHandler;

final class UserHandlerTest extends TestCase
{

    private function getRandomString(): string
    {
        return bin2hex(random_bytes(10));
    }

    public function testIfUserCanBeSavedAndReadFromDatabase(): void
    {

        $username = "Test User " . $this->getRandomString();

        $new_user = new User();
        $new_user->setUsername($username);
        $new_user->setFirstname($this->getRandomString());
        $new_user->setLastname($this->getRandomString());
        $new_user->setEmail($this->getRandomString() . "@myrisk-ev.de");
        $new_user->setSex(UserEnums::SEXUALITY_WOMAN);
        $new_user->setTown($this->getRandomString());
        $new_user->setBirthday(new \DateTime("2020-09-04 00:00:00"));
        $new_user->setCountry(
            CountryHandler::getCountryByCountryShortcut("uk")
        );

        $saved_user = UserHandler::saveUser($new_user);

        $this->assertGreaterThan(0, $saved_user->getUserId(), "User ID is set.");
        $this->assertEquals($username, $saved_user->getUsername(), "Username is set.");

        $user = UserHandler::getUserByUserId($saved_user->getUserId());

        $this->assertEquals(User::class, get_class($user), "Instance of class 'User' is returned.");
        $this->assertGreaterThan(0, $user->getUserId(), "User ID is set.");
        $this->assertEquals($saved_user->getUserId(), $user->getUserId(), "User ID is expected.");
        $this->assertEquals($username, $user->getUsername(), "Username is set.");
        $this->assertEquals("United Kingdom", $user->getCountry()->getName(), "Country name of user is set.");
        $this->assertEquals("uk", $user->getCountry()->getShortcut(), "Country shortcut of user is set.");

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

}
