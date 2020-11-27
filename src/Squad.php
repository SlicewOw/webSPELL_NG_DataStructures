<?php

namespace webspell_ng;

use \webspell_ng\DataStatus;
use webspell_ng\Enums\SquadEnums;
use \webspell_ng\Game;


class Squad extends DataStatus {

    /**
     * @var int $id
     */
    private $id = null;

    /**
     * @var \DateTime $date
     */
    private $date;

    /**
     * @var int $rubric
     */
    private $rubric = SquadEnums::SQUAD_RUBRIC_COMMUNITY;

    /**
     * @var bool $is_game_squad
     */
    private $is_game_squad = true;

    /**
     * @var Game $game
     */
    private $game = null;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $icon
     */
    private $icon;

    /**
     * @var string $icon_small
     */
    private $icon_small;

    /**
     * @var string $info
     */
    private $info;

    public function setSquadId(int $squad_id): void
    {
        $this->id = $squad_id;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function setRubric(int $rubric): void
    {
        $this->rubric = $rubric;
    }

    public function setIsGameSquad(bool $is_game_squad): void
    {
        $this->is_game_squad = $is_game_squad;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    public function setIconSmall(string $icon_small): void
    {
        $this->icon_small = $icon_small;
    }

    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    public function getSquadId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRubric(): int
    {
        return $this->rubric;
    }

    public function getIsGameSquad(): bool
    {
        return $this->is_game_squad;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getIconSmall(): ?string
    {
        return $this->icon_small;
    }

}
