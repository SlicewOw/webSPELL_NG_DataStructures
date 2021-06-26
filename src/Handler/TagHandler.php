<?php

/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Query\Expression\CompositeExpression;

use webspell_ng\WebSpellDatabaseConnection;

class TagHandler
{
    /**
     * @param string $related_type
     * @param int $related_id
     * @param array<string> $tags
     */
    public static function setTags(string $related_type, int $related_id, array $tags): bool
    {

        if (!Validator::numericVal()->min(1)->validate($related_id)) {
            throw new \InvalidArgumentException("related_id_value_is_invalid");
        }

        self::removeTags($related_type, $related_id);

        $tags = array_map("trim", $tags);
        $tags = array_unique($tags);

        foreach ($tags as $tag) {

            if (empty($tag)) {
                continue;
            }

            $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
            $queryBuilder
                ->insert(WebSpellDatabaseConnection::getTablePrefix() . 'tags')
                ->values(
                    array(
                        'tag' => '?',
                        'rel' => '?',
                        'ID' => '?'
                    )
                )
                ->setParameter(0, $tag)
                ->setParameter(1, $related_type)
                ->setParameter(2, $related_id);

            $queryBuilder->executeQuery();

        }

        return True;

    }

    public static function getTagsAsCommaSeparatedString(string $related_type, int $related_id): string
    {
        return implode(", ", self::getTags($related_type, $related_id));
    }

    private static function getTagConditionToBeUsedInQueryBuilder(QueryBuilder $query_builder, string $related_type, int $related_id): CompositeExpression
    {
        return $query_builder->expr()->and(
            $query_builder->expr()->eq('rel', '\'' . $related_type . '\''),
            $query_builder->expr()->eq('ID', $related_id)
        );
    }

    /**
     * @return array<string>
     */
    public static function getTags(string $related_type, int $related_id): array
    {

        $tags = array();

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();

        $queryBuilder
            ->select('tag')
            ->distinct()
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'tags')
            ->where(self::getTagConditionToBeUsedInQueryBuilder($queryBuilder, $related_type, $related_id));

        $tag_query = $queryBuilder->executeQuery();
        while ($get = $tag_query->fetchAssociative()) {
            $tags[] = $get['tag'];
        }

        return $tags;

    }

    public static function getTagsLinked(string $related_type, int $related_id): string
    {
        $tags = array();
        foreach (self::getTags($related_type, $related_id) as $tag) {
            $tags[] = '<a href="index.php?site=tags&amp;tag=' . $tag . '">' . $tag . '</a>';
        }
        return implode(", ", $tags);
    }

    /**
     * @return array<string>
     */
    public static function getTagsPlain(): array
    {
        $tags = array();

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('tag')
            ->distinct()
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'tags');

        $tag_query = $queryBuilder->executeQuery();
        while ($get = $tag_query->fetchAssociative()) {
            if (!empty($get['tag'])) {
                $tags[] = $get['tag'];
            }
        }

        return $tags;

    }

    /**
     * @return array{min: int, max: int, tags: array{array{name: string, count: int}}}
     */
    public static function getTagCloud(): array
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select("t.tag")
            ->select("COUNT(t.`ID`) AS `count`")
            ->distinct()
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'tags', "t")
            ->groupBy("t.`tag`");

        $data = array();
        $data['min'] = 999999999999;
        $data['max'] = 0;
        $data['tags'] = array();

        $tag_query = $queryBuilder->executeQuery();
        while ($get = $tag_query->fetchAssociative()) {
            $data['tags'][] = array(
                'name' => (string)$get['tag'],
                'count' => (int)$get['count']
            );
            $data['min'] = (int)min($data['min'], $get['count']);
            $data['max'] = (int)max($data['max'], $get['count']);
        }

        return $data;

    }

    public static function removeTags(string $related_type, int $related_id): void
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->delete(WebSpellDatabaseConnection::getTablePrefix() . "tags")
            ->where(self::getTagConditionToBeUsedInQueryBuilder($queryBuilder, $related_type, $related_id));

        $queryBuilder->executeQuery();

    }

}
