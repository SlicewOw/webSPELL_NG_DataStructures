<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Country;
use webspell_ng\WebSpellDatabaseConnection;


class CountryHandler {

    private const DB_TABLE_NAME_COUNTRIES = "countries";

    public static function getCountryByCountryShortcut(string $country_shortcut): Country
    {

        if (empty($country_shortcut) || (strlen($country_shortcut) != 2)) {
            throw new \InvalidArgumentException('country_shortcut_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . self::DB_TABLE_NAME_COUNTRIES)
            ->where('short = ?')
            ->setParameter(0, $country_shortcut);

        $country_query = $queryBuilder->execute();
        $country_result = $country_query->fetch();

        if (empty($country_result)) {
            throw new \UnexpectedValueException('unknown_country');
        }

        $country = new Country();
        $country->setCountryId((int) $country_result['countryID']);
        $country->setName($country_result['country']);
        $country->setShortcut($country_result['short']);

        return $country;

    }

}