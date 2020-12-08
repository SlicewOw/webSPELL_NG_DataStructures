<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\Clanwar;


final class ClanwarsTest extends TestCase
{

    public function testIfDefWinIsDetected(): void
    {

        $clanwar = new Clanwar();
        $clanwar->setStatus("def_win");

        $this->assertTrue($clanwar->getIsDefaultWin());
        $this->assertFalse($clanwar->getIsDefaultLoss());

    }

    public function testIfDefLossIsDetected(): void
    {

        $clanwar = new Clanwar();
        $clanwar->setStatus("def_loss");

        $this->assertFalse($clanwar->getIsDefaultWin());
        $this->assertTrue($clanwar->getIsDefaultLoss());

    }

    public function testIfNormalClanwarStatusIsDetected(): void
    {

        $clanwar = new Clanwar();
        $clanwar->setStatus("normal");

        $this->assertFalse($clanwar->getIsDefaultWin());
        $this->assertFalse($clanwar->getIsDefaultLoss());

    }

    public function testIfUnnormalClanwarStatusIsDetected(): void
    {

        $clanwar = new Clanwar();
        $clanwar->setStatus("unnormal");

        $this->assertFalse($clanwar->getIsDefaultWin());
        $this->assertFalse($clanwar->getIsDefaultLoss());

    }

}
