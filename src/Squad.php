<?php

namespace webspell_ng;

use webspell_ng\DataStatus;
use webspell_ng\Game;
use webspell_ng\SquadMember;
use webspell_ng\Enums\SquadEnums;


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
     * @var ?\DateTime $date_deleted
     */
    private $date_deleted;

    /**
     * @var int $rubric
     */
    private $rubric = SquadEnums::SQUAD_RUBRIC_COMMUNITY;

    /**
     * @var bool $is_game_squad
     */
    private $is_game_squad = true;

    /**
     * @var bool $is_console_squad
     */
    private $is_console_squad = false;

    /**
     * @var array<SquadMember> $members
     */
    private $members = array();

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

    public function setDateOfDeletion(?\DateTime $date_deleted): void
    {
        $this->date_deleted = $date_deleted;
    }

    public function setRubric(int $rubric): void
    {
        $this->rubric = $rubric;
    }

    public function setIsGameSquad(bool $is_game_squad): void
    {
        $this->is_game_squad = $is_game_squad;
    }

    public function setIsConsoleSquad(bool $is_console_squad): void
    {
        $this->is_console_squad = $is_console_squad;
    }

    /**
     * @param array<SquadMember> $members
     */
    public function setMembers(array $members): void
    {
        $this->members = $members;
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

    public function getIsConsoleSquad(): bool
    {
        return $this->is_console_squad;
    }

    /**
     * @return array<SquadMember>
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getDateOfDeletion(): ?\DateTime
    {
        return $this->date_deleted;
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
