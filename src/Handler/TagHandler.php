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

use webspell_ng\WebSpellDatabaseConnection;

class TagHandler
{
    /**
     * @param string $relType
     * @param int $relID
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

            $queryBuilder->execute();

        }

        return True;

    }

    public static function getTagsAsCommaSeparatedString(string $related_type, int $related_id): string
    {
        return implode(", ", self::getTags($related_type, $related_id));
    }

    private static function getTagConditionToBeUsedInQueryBuilder(QueryBuilder $query_builder, string $related_type, int $related_id)
    {

        $where_clause = $query_builder->expr()->andX();
        $where_clause->add($query_builder->expr()->eq('rel', '\'' . $related_type . '\''));
        $where_clause->add($query_builder->expr()->eq('ID', $related_id));

        return $where_clause;

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

        $tag_query = $queryBuilder->execute();
        while ($get = $tag_query->fetch()) {
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

        $tag_query = $queryBuilder->execute();
        while ($get = $tag_query->fetch()) {
            if (!empty($get['tag'])) {
                $tags[] = $get['tag'];
            }
        }

        return $tags;

    }

    /**
     * @return array<min: int, max: int, tags: array<int, array<name: string, count: int>>>
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

        $tag_query = $queryBuilder->execute();
        while ($get = $tag_query->fetch()) {
            $data['tags'][] = array('name' => $get['tag'], 'count' => $get['count']);
            $data['min'] = min($data['min'], $get['count']);
            $data['max'] = max($data['max'], $get['count']);
        }

        return $data;

    }

    public static function removeTags(string $related_type, int $related_id)
    {

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->delete(WebSpellDatabaseConnection::getTablePrefix() . "tags")
            ->where(self::getTagConditionToBeUsedInQueryBuilder($queryBuilder, $related_type, $related_id));

        $queryBuilder->execute();

    }

    public static function getTagSizeLogarithmic($count, $mincount, $maxcount, $minsize, $maxsize, $tresholds)
    {
        if (!is_int($tresholds) || $tresholds < 2) {
            $tresholds = $maxsize - $minsize;
            $treshold = 1;
        } else {
            $treshold = ($maxsize - $minsize) / ($tresholds - 1);
        }
        $a = $tresholds * log($count - $mincount + 2) / log($maxcount - $mincount + 2) - 1;
        return round($minsize + round($a) * $treshold);
    }

    public static function getNews($newsID)
    {
        global $userID;
        $result = safe_query(
            "SELECT
                n.*,
                nc.content,
                nc.headline
            FROM
                " . PREFIX . "news n
            JOIN
                " . PREFIX . "news_contents nc ON n.newsID = nc.newsID
            WHERE
                n.newsID = " . (int)$newsID
        );
        $ds = mysqli_fetch_array($result);
        if ($ds['intern'] <= isclanmember($userID) &&
            (
                $ds['published'] ||
                (
                    isnewsadmin($userID) ||
                    (
                        isnewswriter($userID) && $ds['poster'] == $userID
                    )
                )
            )
        ) {
            return array(
                'date' => $ds['date'],
                'type' => 'News',
                'content' => shortenText($ds['content']),
                'title' => $ds['headline'],
                'link' => 'index.php?site=news_comments&amp;newsID=' . $newsID
            );
        } else {
            return false;
        }
    }

    public static function getArticle($articlesID)
    {
        global $userID;
        $get1 = safe_query(
            "SELECT
                title,
                date,
                articlesID
            FROM
                `" . PREFIX . "articles`
            WHERE
                `articlesID` = " . (int)$articlesID . " AND
                `saved` = 1"
        );
        if ($get1->num_rows) {
            $ds = mysqli_fetch_array($get1);
            $get2 = safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "articles_contents
                WHERE
                    `articlesID` = " . (int)$ds['articlesID'] . "
                ORDER BY
                    `page` ASC
                LIMIT
                    0,1"
            );
            $get = mysqli_fetch_assoc($get2);
            return array(
                'date' => $ds['date'],
                'type' => 'Artikel',
                'content' => shortenText($get['content']),
                'title' => $ds['title'],
                'link' => 'index.php?site=articles&amp;action=show&amp;articlesID=' . $articlesID
            );
        } else {
            return false;
        }
    }

    public static function getStaticPage($staticID)
    {
        global $userID;
        $get = safe_query("SELECT * FROM " . PREFIX . "static WHERE staticID='" . $staticID . "'");
        if ($get->num_rows) {
            $ds = mysqli_fetch_array($get);
            $allowed = false;
            switch ($ds['accesslevel']) {
                case 0:
                    $allowed = true;
                    break;
                case 1:
                    if ($userID) {
                        $allowed = true;
                    }
                    break;
                case 2:
                    if (isclanmember($userID)) {
                        $allowed = true;
                    }
                    break;
                default:
                    $allowed = false;
                    break;
            }
            if ($allowed) {
                return array(
                    'date' => time(),
                    'type' => 'StaticPage',
                    'content' => shortenText($ds['content']),
                    'title' => $ds['name'],
                    'link' => 'index.php?site=static&amp;staticID=' . $staticID
                );
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getFaq($faqID)
    {
        global $userID;
        $get = safe_query(
            "SELECT
                `faqID`,
                `faqcatID`,
                `date`,
                `question`,
                `answer`
            FROM
                `" . PREFIX . "faq`
            WHERE
                `faqID` = " . (int)$faqID
        );
        if ($get->num_rows) {
            $ds = mysqli_fetch_array($get);
            $answer = htmloutput($ds['answer']);
            return array(
                'date' => $ds['date'],
                'type' => 'StaticPage',
                'content' => shortenText($answer),
                'title' => $ds['question'],
                'link' =>
                    'index.php?site=faq&amp;action=faq&amp;faqID=' . $ds['faqID'] . '&amp;faqcatID=' . $ds['faqcatID']
            );
        } else {
            return false;
        }
    }

    public static function sortByDate($tag1, $tag2)
    {
        if ($tag1['date'] == $tag2['date']) {
            return 0;
        } else {
            return ($tag1['date'] < $tag2['date']) ? 1 : -1;
        }
    }
}
