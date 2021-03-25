<?php

namespace webspell_ng;

use webspell_ng\Game;


class Map {

    /**
     * @var int $map_id
     */
    private $map_id = null;

    /**
     * @var string $map_name
     */
    private $map_name = null;

    /**
     * @var string $map_icon
     */
    private $map_icon = null;

    /**
     * @var Game $game
     */
    private $game = null;

    /**
     * @var bool $is_deleted
     */
    private $is_deleted = false;

    public function setMapId(int $map_id): void
    {
        $this->map_id = $map_id;
    }

    public function getMapId(): ?int
    {
        return $this->map_id;
    }

    public function setName(string $map_name): void
    {
        $this->map_name = $map_name;
    }

    public function getName(): ?string
    {
        return $this->map_name;
    }

    public function setIcon(string $map_icon): void
    {
        $this->map_icon = $map_icon;
    }

    public function getIcon(): ?string
    {
        return $this->map_icon;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setIsDeleted(bool $deleted): void
    {
        $this->is_deleted = $deleted;
    }

    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

}
