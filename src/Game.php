<?php

namespace webspell_ng;

class Game {

    private int $game_id;
    private string $game_tag;
    private string $game_name;

    public function setGameId(int $game_id): void
    {
        $this->game_id = $game_id;
    }

    public function getGameId(): int
    {
        return $this->game_id;
    }

    public function setTag(string $game_tag): void
    {
        $this->game_tag = $game_tag;
    }

    public function getTag(): string
    {
        return $this->game_tag;
    }

    public function setName(string $game_name): void
    {
        $this->game_name = $game_name;
    }

    public function getName(): string
    {
        return $this->game_name;
    }

}
