<?php

namespace webspell_ng;

use webspell_ng\Game;


class SquadMemberPosition {

    /**
     * @var int $position_id
     */
    private $position_id = null;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $tag
     */
    private $tag;

    /**
     * @var Game $game
     */
    private $game;

    /**
     * @var int $sort
     */
    private $sort = 1;

    public function setPositionId(int $position_id): void
    {
        $this->position_id = $position_id;
    }

    public function getPositionId(): ?int
    {
        return $this->position_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function getGame(): ?Game
    {
        if (is_null($this->game)) {
            return null;
        }
        return $this->game;
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
