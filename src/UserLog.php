<?php

namespace webspell_ng;


class UserLog {

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var ?\DateTime $date
     */
    private $date;

    /**
     * @var int $parent_id
     */
    private $parent_id;

    /**
     * @var string $info
     */
    private $info;

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getDate(): \DateTime
    {
        if (is_null($this->date)) {
            return new \DateTime("now");
        }
        return $this->date;
    }

    public function setParentId(int $parent_id): void
    {
        $this->parent_id = $parent_id;
    }

    public function getParentId(): int
    {
        return $this->parent_id;
    }

    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

}
