<?php

namespace webspell_ng\Handler;

use webspell_ng\NewsLanguage;
use webspell_ng\WebSpellDatabaseConnection;


class NewsLanguageHandler {

    private const DB_TABLE_NAME_NEWS_LANGUAGES = "news_languages";

    public static function getNewsLanguageByShortcut(string $shortcut): NewsLanguage
    {

        if (empty($shortcut) || strlen($shortcut) > 2) {
            throw new \InvalidArgumentException("shortcut_value_is_invalid");
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_LANGUAGES)
            ->where('lang = ?')
            ->setParameter(0, $shortcut);

        $language_query = $queryBuilder->execute();
        $language_result = $language_query->fetch();

        if (empty($language_result)) {
            throw new \UnexpectedValueException('unknown_news_language');
        }

        $news_language = new NewsLanguage();
        $news_language->setLanguageId((int) $language_result['langID']);
        $news_language->setLanguage($language_result['language']);
        $news_language->setShortcut($language_result['lang']);

        return $news_language;

    }

    /**
     * @return array<NewsLanguage>
     */
    public static function getAllLanguages(): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('lang')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_LANGUAGES)
            ->orderBy("lang", "ASC");

        $language_query = $queryBuilder->execute();

        $news_languages = array();
        while ($language_result = $language_query->fetch())
        {
            array_push(
                $news_languages,
                self::getNewsLanguageByShortcut($language_result['lang'])
            );
        }

        return $news_languages;

    }

    public static function saveLanguage(NewsLanguage $language): NewsLanguage
    {

        if (is_null($language->getLanguageId())) {
            $language = self::insertLanguage($language);
        } else {
            self::updateLanguage($language);
        }

        return self::getNewsLanguageByShortcut($language->getShortcut());

    }

    public static function insertLanguage(NewsLanguage $language): NewsLanguage
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_LANGUAGES)
            ->values(
                    [
                        'language' => '?',
                        'lang' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $language->getLanguage(),
                        1 => $language->getShortcut()
                    ]
                );

        $queryBuilder->execute();

        $language->setLanguageId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $language;

    }

    public static function updateLanguage(NewsLanguage $language): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_LANGUAGES)
            ->set('language', '?')
            ->set('lang', '?')
            ->where('langID = ?')
            ->setParameter(0, $language->getLanguage())
            ->setParameter(1, $language->getShortcut())
            ->setParameter(2, $language->getLanguageId());

        $queryBuilder->execute();

    }

}
