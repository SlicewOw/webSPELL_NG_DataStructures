<?php

namespace webspell_ng\Handler;

use webspell_ng\PageData;
use webspell_ng\Handler\PageDataHandler;


class PrivacyPolicyHandler {

    private const DB_TABLE_NAME_PRIVACY_POLICY = "privacy_policy";

    public static function getPrivacyPolicyByPage(string $page): PageData
    {
        return PageDataHandler::getPageDataByTableAndPage(self::DB_TABLE_NAME_PRIVACY_POLICY, $page);
    }

    /**
     * @return array<PageData>
     */
    public static function getAllPrivacyPolicies(): array
    {
        return PageDataHandler::getAllPageData(self::DB_TABLE_NAME_PRIVACY_POLICY);
    }

    public static function savePolicy(PageData $policy): void
    {
        PageDataHandler::savePageData(self::DB_TABLE_NAME_PRIVACY_POLICY, $policy);
    }

}
