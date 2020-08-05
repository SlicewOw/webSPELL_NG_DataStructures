<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\User;

final class UserTest extends TestCase
{

    public function testIfGameInstanceCanBeCreated(): void
    {

        $user = new User();
        $user->setUserId(1337);
        $user->setUsername("jinx");
        $user->setFirstname("Max");
        $user->setLastname("Mustermann");

        $this->assertEquals(1337, $user->getUserId(), "User ID is set.");
        $this->assertEquals("jinx", $user->getUsername(), "Username is set.");
        $this->assertEquals("Max", $user->getFirstname(), "Firstname of user is set.");
        $this->assertEquals("Mustermann", $user->getLastname(), "Lastname of user is set.");

    }

}