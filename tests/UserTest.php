<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\User;

final class UserTest extends TestCase
{

    public function testIfGameInstanceCanBeCreated(): void
    {

        $birthday = new \DateTime("2020-09-04 00:00:00");

        $user = new User();
        $user->setUserId(1337);
        $user->setUsername("jinx");
        $user->setFirstname("Max");
        $user->setLastname("Mustermann");
        $user->setEmail("test@webspell-ng.de");
        $user->setTown("London");
        $user->setSex("w");
        $user->setCountry("uk");
        $user->setBirthday($birthday);

        $this->assertEquals(1337, $user->getUserId(), "User ID is set.");
        $this->assertEquals("jinx", $user->getUsername(), "Username is set.");
        $this->assertEquals("Max", $user->getFirstname(), "Firstname of user is set.");
        $this->assertEquals("Mustermann", $user->getLastname(), "Lastname of user is set.");
        $this->assertEquals("test@webspell-ng.de", $user->getEmail(), "Mail of user is set.");
        $this->assertEquals("London", $user->getTown(), "Town of user is set.");
        $this->assertEquals("w", $user->getSex(), "Sex of user is set.");
        $this->assertEquals("uk", $user->getCountry(), "Country of user is set.");
        $this->assertEquals($birthday, $user->getBirthday(), "Birthday of user is set.");

    }

    public function testIfInvalidEmailCannotBeSet(): void
    {

        $this->expectException(InvalidArgumentException::class);

        $user = new User();
        $user->setEmail("wrong email");

    }

}