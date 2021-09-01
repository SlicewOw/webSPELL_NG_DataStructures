<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\User;

final class UserTest extends TestCase
{

    public function testIfAgeIsCalculatedCorrectly(): void
    {

        $birthday = new \DateTime("20 years ago");
        $birthday->sub(
            new \DateInterval("P1D")
        );

        $user = new User();
        $user->setBirthday($birthday);

        $this->assertEquals(20, $user->getAge(), "Age is expected.");

    }

}
