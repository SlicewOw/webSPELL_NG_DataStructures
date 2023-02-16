<?php

namespace webspell_ng;

use \webspell_ng\Map;


class ClanwarMap
{

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
     * @var bool $default_win
     */
    private $default_win = false;

    /**
     * @var bool $default_loss
     */
    private $default_loss = false;

    /**
     * @var int $sort
     */
    private $sort = 1;

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

    public function setIsDefaultWin(bool $default_win): void
    {
        $this->default_win = $default_win;
    }

    public function isDefaultWin(): bool
    {
        return $this->default_win;
    }

    public function setIsDefaultLoss(bool $default_loss): void
    {
        $this->default_loss = $default_loss;
    }

    public function isDefaultLoss(): bool
    {
        return $this->default_loss;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getSort(): int
    {
        return $this->sort;
    }
}
