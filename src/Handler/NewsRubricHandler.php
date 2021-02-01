<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\NewsRubric;
use webspell_ng\WebSpellDatabaseConnection;


class NewsRubricHandler {

    private const DB_TABLE_NAME_NEWS_RUBRICS = "news_rubrics";

    public static function getRubricByRubricId(int $rubric_id): NewsRubric
    {

        if (!Validator::numericVal()->min(1)->validate($rubric_id)) {
            throw new \InvalidArgumentException('rubric_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_RUBRICS)
            ->where('rubricID = ?')
            ->setParameter(0, $rubric_id);

        $rubric_query = $queryBuilder->execute();
        $rubric_result = $rubric_query->fetch();

        if (empty($rubric_result)) {
            throw new \UnexpectedValueException('unknown_news_rubric');
        }

        $rubric = new NewsRubric();
        $rubric->setRubricId((int) $rubric_result['rubricID']);
        $rubric->setName($rubric_result['rubric']);
        $rubric->setCategory($rubric_result['category']);
        $rubric->setImage($rubric_result['pic']);
        $rubric->setIsActive(
            ($rubric_result['active'] == 1)
        );

        return $rubric;

    }

    /**
     * @return array<NewsRubric>
     */
    public static function getAllRubrics(): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('rubricID')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_RUBRICS)
            ->where("active = 1")
            ->orderBy("rubric", "ASC");

        $rubric_query = $queryBuilder->execute();

        $rubrics = array();
        while ($rubric_result = $rubric_query->fetch())
        {
            array_push(
                $rubrics,
                self::getRubricByRubricId((int) $rubric_result['rubricID'])
            );
        }

        return $rubrics;

    }

    public static function saveRubric(NewsRubric $rubric): NewsRubric
    {

        if (is_null($rubric->getRubricId())) {
            $rubric = self::insertRubric($rubric);
        } else {
            self::updateRubric($rubric);
        }

        return self::getRubricByRubricId($rubric->getRubricId());

    }

    public static function insertRubric(NewsRubric $rubric): NewsRubric
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->insert(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_RUBRICS)
            ->values(
                    [
                        'rubric' => '?',
                        'category' => '?',
                        'pic' => '?',
                        'active' => '?'
                    ]
                )
            ->setParameters(
                    [
                        0 => $rubric->getName(),
                        1 => $rubric->getCategory(),
                        2 => $rubric->getImage(),
                        3 => $rubric->isActive() ? 1 : 0
                    ]
                );

        $queryBuilder->execute();

        $rubric->setRubricId(
            (int) WebSpellDatabaseConnection::getDatabaseConnection()->lastInsertId()
        );

        return $rubric;

    }

    public static function updateRubric(NewsRubric $rubric): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_NEWS_RUBRICS)
            ->set('rubric', '?')
            ->set('category', '?')
            ->set('pic', '?')
            ->set('active', '?')
            ->where('rubricID = ?')
            ->setParameter(0, $rubric->getName())
            ->setParameter(1, $rubric->getCategory())
            ->setParameter(2, $rubric->getImage())
            ->setParameter(3, $rubric->isActive() ? 1 : 0)
            ->setParameter(4, $rubric->getRubricId());

        $queryBuilder->execute();

    }

    public static function deleteRubric(NewsRubric $rubric): void
    {

        $rubric->setIsActive(false);

        self::updateRubric($rubric);

    }

}
