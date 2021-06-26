<?php

namespace webspell_ng\Handler;

use webspell_ng\News;
use webspell_ng\NewsContent;
use webspell_ng\WebSpellDatabaseConnection;


class NewsContentHandler {

    private const DB_TABLE_NAME_NEWS_CONTENTS = "news_contents";

    /**
     * @return array<NewsContent>
     */
    public static function getContentsOfNews(News $news): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_CONTENTS)
            ->where('newsID = ?')
            ->setParameter(0, $news->getNewsId());

        $content_query = $queryBuilder->executeQuery();

        $news_contents = array();
        while ($content_result = $content_query->fetchAssociative())
        {

            $content = new NewsContent();
            $content->setHeadline($content_result['headline']);
            $content->setContent($content_result['content']);
            $content->setLanguage(
                NewsLanguageHandler::getNewsLanguageByShortcut($content_result['language'])
            );

            array_push(
                $news_contents,
                $content
            );

        }

        return $news_contents;

    }

    public static function saveContent(News $news, NewsContent $content): void
    {

        if (!self::isExistingNewsContent($news, $content)) {
            self::insertContent($news, $content);
        } else {
            self::updateContent($news, $content);
        }

    }

    private static function isExistingNewsContent(News $news, NewsContent $content): bool
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_CONTENTS)
            ->where('newsID = ?', 'language = ?')
            ->setParameter(0, $news->getNewsId())
            ->setParameter(1, $content->getLanguage()->getShortcut());

        $content_query = $queryBuilder->executeQuery();

        return !empty($content_query->fetchAssociative());

    }

    private static function insertContent(News $news, NewsContent $content): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_CONTENTS)
            ->values(
                    [
                        'newsID' => '?',
                        'language' => '?',
                        'headline' => '?',
                        'content' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $news->getNewsId(),
                        1 => $content->getLanguage()->getShortcut(),
                        2 => $content->getHeadline(),
                        3 => $content->getContent()
                    ]
                );

        $queryBuilder->executeQuery();

    }

    private static function updateContent(News $news, NewsContent $content): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_CONTENTS)
            ->set('headline', '?')
            ->set('content', '?')
            ->where('newsID = ?', 'language = ?')
            ->setParameter(0, $content->getHeadline())
            ->setParameter(1, $content->getContent())
            ->setParameter(2, $news->getNewsId())
            ->setParameter(3, $content->getLanguage()->getShortcut());

        $queryBuilder->executeQuery();

    }

}
