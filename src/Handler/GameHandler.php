<?php

namespace webspell_ng\Handler;

use Respect\Validation\Validator;

use webspell_ng\Game;
use webspell_ng\WebSpellDatabaseConnection;


class GameHandler {

    public static function getGameByGameId(int $game_id): Game
    {

        if (!Validator::numericVal()->min(1)->validate($game_id)) {
            throw new \InvalidArgumentException('game_id_value_is_invalid');
        }

        $queryBuilder = WebSpellDatabaseConnection::getDatabaseConnection()->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from(WebSpellDatabaseConnection::getTablePrefix() . 'games')
            ->where('gameID = ?')
            ->setParameter(0, $game_id);

        $game_query = $queryBuilder->execute();
        $game_result = $game_query->fetch();

        if (!$game_result || count($game_result) < 1) {
            throw new \InvalidArgumentException('unknown_game');
        }

        $game = new Game();
        $game->setGameId((int) $game_result['gameID']);
        $game->setTag($game_result['tag']);
        $game->setName($game_result['name']);

        return $game;

    }

}