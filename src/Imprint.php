<?php

namespace webspell_ng;


class Imprint {

    /**
     * @var string $page
     */
    private $page;

    /**
     * @var string $info
     */
    private $info;

    /**
     * @var ?\DateTime $date
     */
    private $date;

    public function setPage(string $page): void
    {
        $this->page = $page;
    }

    public function getPage(): string
    {
        return $this->page;
    }

    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    public function getInfo(): string
    {
        return $this->info;
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

}
