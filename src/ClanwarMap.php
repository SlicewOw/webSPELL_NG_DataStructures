<?php

namespace webspell_ng;

use \webspell_ng\Map;


class ClanwarMap {

    /**
     * @var int $mapping_id
     */
    private $mapping_id = null;

    /**
     * @var Map $map
     */
    private $map = null;

    /**
     * @var int $score_home
     */
    private $score_home = 0;

    /**
     * @var int $score_opponent
     */
    private $score_opponent = 0;

    public function setMappingId(int $mapping_id): void
    {
        $this->mapping_id = $mapping_id;
    }

    public function getMappingId(): ?int
    {
        return $this->mapping_id;
    }

    public function setMap(Map $map): void
    {
        $this->map = $map;
    }

    public function getMap(): ?Map
    {
        return $this->map;
    }

    public function setScoreHome(int $score_home): void
    {
        $this->score_home = $score_home;
    }

    public function getScoreHome(): int
    {
        return $this->score_home;
    }

    public function setScoreOpponent(int $score_opponent): void
    {
        $this->score_opponent = $score_opponent;
    }

    public function getScoreOpponent(): int
    {
        return $this->score_opponent;
    }

}
