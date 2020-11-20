<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\User;
use webspell_ng\Handler\UserHandler;

final class UserHandlerTest extends TestCase
{

    public function testIfUserCanBeSavedAndReadFromDatabase(): void
    {

        $user = UserHandler::getUserByUserId(1);

        $this->assertEquals(User::class, get_class($user), "Instance of class 'User' is returned.");
        $this->assertEquals(1, $user->getUserId(), "User ID is set.");
        $this->assertTrue(!empty($user->getUsername()), "Username is set.");

    }

}
