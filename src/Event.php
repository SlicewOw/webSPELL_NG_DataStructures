<?php

namespace webspell_ng;

use \webspell_ng\DataStatus;
use \webspell_ng\Squad;
use \webspell_ng\Utils\ValidationUtils;


class Event extends DataStatus {

    /**
     * @var int $id
     */
    private $id = null;

    /**
     * @var string $name
     */
    private $name = null;

    /**
     * @var \DateTime $date
     */
    private $date;

    /**
     * @var string $category
     */
    private $category = null;

    /**
     * @var string $homepage
     */
    private $homepage = null;

    /**
     * @var Squad $squad
     */
    private $squad = null;

    /**
     * @var bool $is_offline
     */
    private $is_offline = false;

    public function __construct()
    {
        $this->date = new \DateTime("now");
    }

    public function setEventId(int $event_id): void
    {
        $this->id = $event_id;
    }

    public function getEventId(): ?int
    {
        return $this->id;
    }

    public function setName(string $event_name): void
    {
        $this->name = $event_name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDate(\DateTime $event_date): void
    {
        $this->date = $event_date;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setHomepage(string $event_url): void
    {

        if (!ValidationUtils::validateUrl($event_url)) {
            throw new \UnexpectedValueException("event_homepage_value_is_invalid");
        }

        $this->homepage = $event_url;

    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    public function setSquad(Squad $squad): void
    {
        $this->squad = $squad;
    }

    public function getSquad(): ?Squad
    {
        return $this->squad;
    }

    public function getSquadId(): ?int
    {
        if (is_null($this->getSquad())) {
            return null;
        }
        return $this->getSquad()->getSquadId();
    }

    public function setIsOffline(bool $is_offline): void
    {
        $this->is_offline = $is_offline;
    }

    public function getIsOffline(): bool
    {
        return $this->is_offline;
    }

    public function setLeagueCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getLeagueCategory(): ?string
    {
        return $this->category;
    }

}
