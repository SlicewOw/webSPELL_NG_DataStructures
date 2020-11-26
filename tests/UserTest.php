<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\User;


final class UserTest extends TestCase
{

    public function testIfEmptyLastnameIsReturndIfLastnameIsNotSet(): void
    {

        $user = new User();

        $this->assertEmpty($user->getLastname());

    }

    public function testIfInvalidEmailCannotBeSet(): void
    {

        $this->expectException(InvalidArgumentException::class);

        $user = new User();
        $user->setEmail("wrong email");

    }

}