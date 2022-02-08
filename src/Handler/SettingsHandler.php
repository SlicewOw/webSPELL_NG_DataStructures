<?php

namespace webspell_ng\Handler;

use \webspell_ng\Settings;
use \webspell_ng\WebSpellDatabaseConnection;


class SettingsHandler
{

    private const DB_TABLE_NAME_SETTINGS = "settings";

    public static function getSettings(string $page_identifier): Settings
    {

        if (empty($page_identifier)) {
            throw new \InvalidArgumentException('page_identifier_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_SETTINGS)
            ->where('page = ?')
            ->setParameter(0, $page_identifier);

        $settings_query = $queryBuilder->executeQuery();
        $settings_result = $settings_query->fetchAssociative();

        if (empty($settings_result)) {
            throw new \UnexpectedValueException('unknown_settings');
        }

        $settings = new Settings();
        $settings->setHomepageTitle($settings_result["title"]);
        $settings->setClanname($settings_result["clanname"]);
        $settings->setClantag($settings_result["clantag"]);
        $settings->setDefaultDateFormat($settings_result["date_format"]);
        $settings->setDefaultTimeFormat($settings_result["time_format"]);

        return $settings;

    }

}