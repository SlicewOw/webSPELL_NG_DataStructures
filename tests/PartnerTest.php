<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Partner;


final class PartnerTest extends TestCase
{

    public function testIfDefaultDateIsReturned(): void
    {

        $partner = new Partner();

        $this->assertGreaterThan(new \DateTime("1 minute ago"), $partner->getDate());

    }

}
