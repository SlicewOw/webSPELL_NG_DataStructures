<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Imprint;

final class ImprintTest extends TestCase
{

    public function testIfDefaultDateIsReturned(): void
    {

        $imprint = new Imprint();

        $this->assertGreaterThan(new \DateTime("1 minute ago"), $imprint->getDate());

    }

}
