<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\Clanwar;
use \webspell_ng\Enums\ClanwarEnums;


final class ClanwarsTest extends TestCase
{

    public function testIfDefWinIsDetected(): void
    {

        $clanwar = new Clanwar();
        $clanwar->setStatus(ClanwarEnums::CLANWAR_STATUS_DEFAULT_WIN);

        $this->assertTrue($clanwar->getIsDefaultWin());
        $this->assertFalse($clanwar->getIsDefaultLoss());

    }

    public function testIfDefLossIsDetected(): void
    {

        $clanwar = new Clanwar();
        $clanwar->setStatus(ClanwarEnums::CLANWAR_STATUS_DEFAULT_LOSS);

        $this->assertFalse($clanwar->getIsDefaultWin());
        $this->assertTrue($clanwar->getIsDefaultLoss());

    }

    public function testIfNormalClanwarStatusIsDetected(): void
    {

        $clanwar = new Clanwar();
        $clanwar->setStatus(ClanwarEnums::CLANWAR_STATUS_NORMAL);

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

    public function testIfEmptyStatusEndsUpInNormalState(): void
    {

        $clanwar = new Clanwar();
        $clanwar->setStatus("");

        $this->assertFalse($clanwar->getIsDefaultWin());
        $this->assertFalse($clanwar->getIsDefaultLoss());

    }

    public function testIfNullValuesAreReturnedIfSquadIsNotSet(): void
    {

        $clanwar = new Clanwar();

        $this->assertNull($clanwar->getSquadId(), "Squad ID is null");
        $this->assertNull($clanwar->getGame(), "Game is null");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfHomepageIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        $clanwar = new Clanwar();
        $clanwar->setMatchURL("123");

    }

}
