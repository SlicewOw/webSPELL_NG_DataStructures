<?php

namespace webspell_ng\Handler;

use webspell_ng\News;
use webspell_ng\NewsSource;
use webspell_ng\WebSpellDatabaseConnection;


class NewsSourceHandler {

    private const DB_TABLE_NAME_NEWS_SOURCES = "news_sources";

    public static function getSourcesOfNews(News $news): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_SOURCES)
            ->where('newsID = ?')
            ->setParameter(0, $news->getNewsId());

        $source_query = $queryBuilder->execute();

        $sources = array();
        while ($source_result = $source_query->fetch())
        {

            $source = new NewsSource();
            $source->setSourceId((int) $source_result['sourceID']);
            $source->setName($source_result['name']);
            $source->setHomepage($source_result['homepage']);

            array_push(
                $sources,
                $source
            );

        }

        return $sources;

    }

    public static function saveSource(News $news, NewsSource $source): NewsSource
    {

        if (is_null($source->getSourceId())) {
            $source = self::insertSource($news, $source);
        } else {
            self::updateSource($news, $source);
        }

        return $source;

    }

    private static function insertSource(News $news, NewsSource $source): NewsSource
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_SOURCES)
            ->values(
                    [
                        'newsID' => '?',
                        'name' => '?',
                        'homepage' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $news->getNewsId(),
                        1 => $source->getName(),
                        2 => $source->getHomepage()
                    ]
                );

        $queryBuilder->execute();

        $source->setSourceId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $source;

    }

    private static function updateSource(News $news, NewsSource $source): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_SOURCES)
            ->set('newsID', '?')
            ->set('name', '?')
            ->set('homepage', '?')
            ->where('sourceID = ?')
            ->setParameter(0, $news->getNewsId())
            ->setParameter(1, $source->getName())
            ->setParameter(2, $source->getHomepage())
            ->setParameter(3, $source->getSourceId());

        $queryBuilder->execute();

    }

}
