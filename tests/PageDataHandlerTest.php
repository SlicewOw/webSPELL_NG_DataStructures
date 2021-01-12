<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\PageData;
use webspell_ng\Handler\PageDataHandler;

final class PageDataHandlerTest extends TestCase
{

    public function testIfInvalidArgumentExceptionIsThrownIfPageValueIsInvalid(): void
    {

        $this->expectException(InvalidArgumentException::class);

        PageDataHandler::getPageDataByTableAndPage("test", "");

    }

    public function testIfInvalidArgumentExceptionIsThrownIfPageIsNotSetYet(): void
    {

        $this->expectException(InvalidArgumentException::class);

        $page_data = new PageData();
        $page_data->setPage("");

        PageDataHandler::savePageData("test", $page_data);

    }

}
