<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\News;
use webspell_ng\WebSpellDatabaseConnection;
use webspell_ng\Handler\NewsContentHandler;
use webspell_ng\Handler\NewsRubricHandler;
use webspell_ng\Handler\NewsSourceHandler;
use webspell_ng\Handler\UserHandler;
use webspell_ng\Utils\DateUtils;


class NewsHandler {

    private const DB_TABLE_NAME_NEWS = "news";

    public static function getNewsByNewsId(int $news_id): News
    {

        if (!Validator::numericVal()->min(1)->validate($news_id)) {
            throw new \InvalidArgumentException('news_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS)
            ->where('newsID = ?')
            ->setParameter(0, $news_id);

        $news_query = $queryBuilder->execute();
        $news_result = $news_query->fetch();

        if (empty($news_result)) {
            throw new \UnexpectedValueException('unknown_news');
        }

        $news = new News();
        $news->setNewsId((int) $news_result['newsID']);
        $news->setIsPublished(
            ($news_result['published'] == 1)
        );
        $news->setIsInternal(
            ($news_result['internal'] == 1)
        );
        $news->setWriter(
            UserHandler::getUserByUserId((int) $news_result['writer'])
        );
        $news->setDate(
            DateUtils::getDateTimeByMktimeValue((int) $news_result['date'])
        );
        $news->setRubric(
            NewsRubricHandler::getRubricByRubricId((int) $news_result['rubricID'])
        );

        $news->setContent(
            NewsContentHandler::getContentsOfNews($news)
        );

        $news->setSources(
            NewsSourceHandler::getSourcesOfNews($news)
        );

        return $news;

    }

    public static function saveNews(News $news): News
    {

        if (is_null($news->getNewsId())) {
            $news = self::insertNews($news);
        } else {
            self::updateNews($news);
        }

        $news_contents = $news->getContent();
        foreach ($news_contents as $news_content) {
            NewsContentHandler::saveContent($news, $news_content);
        }

        $news_sources = $news->getSources();
        foreach ($news_sources as $news_source) {
            NewsSourceHandler::saveSource($news, $news_source);
        }

        return self::getNewsByNewsId($news->getNewsId());

    }

    private static function insertNews(News $news): News
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS)
            ->values(
                    [
                        'date' => '?',
                        'rubricID' => '?',
                        'writer' => '?',
                        'published' => '?',
                        'internal' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $news->getDate()->getTimestamp(),
                        1 => $news->getRubric()->getRubricId(),
                        2 => $news->getWriter()->getUserId(),
                        3 => $news->isPublished() ? 1 : 0,
                        4 => $news->isInternal() ? 1 : 0
                    ]
                );

        $queryBuilder->execute();

        $news->setNewsId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $news;

    }

    private static function updateNews(News $news): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS)
            ->set('date', '?')
            ->set('rubricID', '?')
            ->set('writer', '?')
            ->set('published', '?')
            ->set('internal', '?')
            ->where('newsID = ?')
            ->setParameter(0, $news->getDate()->getTimestamp())
            ->setParameter(1, $news->getRubric()->getRubricId())
            ->setParameter(2, $news->getWriter()->getUserId())
            ->setParameter(3, $news->isPublished() ? 1 : 0)
            ->setParameter(4, $news->isInternal() ? 1 : 0)
            ->setParameter(5, $news->getNewsId());

        $queryBuilder->execute();

    }

    public static function publishNews(News $news): void
    {

        $news->setIsPublished(true);

        self::updateNews($news);

    }

    public static function unpublishNews(News $news): void
    {

        $news->setIsPublished(false);

        self::updateNews($news);

    }

}
