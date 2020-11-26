<?php

namespace webspell_ng;


class Country {

    /**
     * @var int $country_id
     */
    private $country_id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $shortcut
     */
    private $shortcut;

    public function setCountryId(int $country_id): void
    {
        $this->country_id = $country_id;
    }

    public function getCountryId(): int
    {
        return $this->country_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setShortcut(string $shortcut): void
    {
        $this->shortcut = $shortcut;
    }

    public function getShortcut(): string
    {
        return $this->shortcut;
    }

}
