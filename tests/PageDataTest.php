<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\PageData;

final class PageDataTest extends TestCase
{

    public function testIfDefaultDateIsReturned(): void
    {

        $imprint = new PageData();

        $this->assertGreaterThan(new \DateTime("1 minute ago"), $imprint->getDate());

    }

}
