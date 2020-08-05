<?php

namespace webspell_ng;

class Sponsor {

    private int $sponsor_id;
    private string $name;
    private string $homepage;
    private ?string $info;
    private ?string $banner;
    private ?string $banner_small;
    private bool $is_displayed = false;
    private bool $is_mainsponsor = false;
    private \DateTime $date;

    public function setSponsorId(int $sponsor_id): void
    {
        $this->sponsor_id = $sponsor_id;
    }

    public function getSponsorId(): int
    {
        return $this->sponsor_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setHomepage(string $homepage): void
    {
        $this->homepage = $homepage;
    }

    public function getHomepage(): string
    {
        return $this->homepage;
    }

    public function setInfo(?string $info): void
    {
        $this->info = $info;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setBanner(?string $banner): void
    {
        $this->banner = $banner;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBannerSmall(?string $banner_small): void
    {
        $this->banner_small = $banner_small;
    }

    public function getBannerSmall(): ?string
    {
        return $this->banner_small;
    }

    public function setIsDisplayed(bool $is_displayed): void
    {
        $this->is_displayed = $is_displayed;
    }

    public function isDisplayed(): bool
    {
        return $this->is_displayed;
    }

    public function setIsMainsponsor(bool $is_mainsponsor): void
    {
        $this->is_mainsponsor = $is_mainsponsor;
    }

    public function isMainsponsor(): bool
    {
        return $this->is_mainsponsor;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

}