<?php

namespace webspell_ng\Handler;

use webspell_ng\PageData;
use webspell_ng\Handler\PageDataHandler;


class AboutUsHandler {

    private const DB_TABLE_NAME_ABOUT = "about";

    public static function getAboutUsByPage(string $page): PageData
    {
        return PageDataHandler::getPageDataByTableAndPage(self::DB_TABLE_NAME_ABOUT, $page);
    }

    /**
     * @return array<PageData>
     */
    public static function getAllAboutUs(): array
    {
        return PageDataHandler::getAllPageData(self::DB_TABLE_NAME_ABOUT);
    }

    public static function saveAbout(PageData $policy): void
    {
        PageDataHandler::savePageData(self::DB_TABLE_NAME_ABOUT, $policy);
    }

}
