<?php

namespace webspell_ng\Handler;

use \webspell_ng\Award;
use \webspell_ng\Event;
use \webspell_ng\WebSpellDatabaseConnection;
use \webspell_ng\Utils\ValidationUtils;


class LeagueCategoryHandler
{

    public static function setAwardLeagueCategory(Award $award): Award
    {
        if (!is_null($award->getHomepage())) {
            $award_category = self::setLeagueCategory($award->getHomepage(), $award->getAwardId(), 'awards', 'awardID');
            $award->setLeagueCategory($award_category);
        }
        return $award;
    }

    public static function setEventLeagueCategory(Event $event): Event
    {
        if (!is_null($event->getHomepage())) {
            $event_category = self::setLeagueCategory($event->getHomepage(), $event->getEventId(), 'events', 'eventID');
            $event->setLeagueCategory($event_category);
        }
        return $event;
    }

    private static function setLeagueCategory(?string $homepage, ?int $parent_id, string $table, string $parent_id_column): string
    {

        if (empty($homepage) || is_null($parent_id)) {
            throw new \UnexpectedValueException('invalid_parameters');
        }

        $categoryArray = array();

        preg_match(
            '@^(?:http[s]?://)?([^/]+)@i',
            $homepage,
            $categoryArray
        );

        if (!ValidationUtils::validateArray($categoryArray, true)) {
            throw new \UnexpectedValueException('unknown_category_array');
        }

        $getCount = count($categoryArray);

        if ($getCount < 2) {
            throw new \UnexpectedValueException('unknown_category_array_count');
        }

        $categorySubArray = explode('.', $categoryArray[1]);

        $getSubCount = count($categorySubArray);
        unset($categorySubArray[$getSubCount - 1]);

        $unsetIfExistArray = array(
            'www',
            'play'
        );

        foreach ($unsetIfExistArray as $identifier) {

            if ($categorySubArray[0] == $identifier) {
                unset($categorySubArray[0]);
            } else if (isset($categorySubArray[1]) && ($categorySubArray[1] == $identifier)) {
                unset($categorySubArray[1]);
            }
        }

        $category = implode('.', $categorySubArray);

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->update(WebSpellDatabaseConnection::getTablePrefix() . $table)
            ->set('category', '?')
            ->where($parent_id_column . ' = ?')
            ->setParameter(0, $category)
            ->setParameter(1, $parent_id);

        $queryBuilder->executeQuery();

        return $category;
    }
}
