<?php

namespace webspell_ng;


class Partner {

    /**
     * @var ?int $partner_id
     */
    private $partner_id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var ?string $homepage
     */
    private $homepage;

    /**
     * @var ?string $banner
     */
    private $banner;

    /**
     * @var ?\DateTime $date
     */
    private $date;

    /**
     * @var int $sort
     */
    private $sort = 100;

    /**
     * @var bool $displayed
     */
    private $displayed = true;

    /**
     * @var int $hits
     */
    private $hits = 0;

    public function setPartnerId(int $partner_id): void
    {
        $this->partner_id = $partner_id;
    }

    public function getPartnerId(): ?int
    {
        return $this->partner_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setHomepage(?string $homepage): void
    {
        $this->homepage = $homepage;
    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    public function setBanner(?string $banner): void
    {
        $this->banner = $banner;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
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

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setIsDisplayed(bool $is_displayed): void
    {
        $this->displayed = $is_displayed;
    }

    public function isDisplayed(): bool
    {
        return $this->displayed;
    }

    public function setHits(int $hits): void
    {
        $this->hits = $hits;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

}
