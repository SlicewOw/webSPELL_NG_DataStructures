<?php

namespace webspell_ng\Handler;

use webspell_ng\PageData;
use webspell_ng\Handler\PageDataHandler;


class ImprintHandler {

    private const DB_TABLE_NAME_IMPRINTS = "imprint";

    public static function getImprintByPage(string $page): PageData
    {
        return PageDataHandler::getPageDataByTableAndPage(self::DB_TABLE_NAME_IMPRINTS, $page);
    }

    /**
     * @return array<PageData>
     */
    public static function getAllImprints(): array
    {
        return PageDataHandler::getAllPageData(self::DB_TABLE_NAME_IMPRINTS);
    }

    public static function saveImprint(PageData $policy): void
    {
        PageDataHandler::savePageData(self::DB_TABLE_NAME_IMPRINTS, $policy);
    }

}
