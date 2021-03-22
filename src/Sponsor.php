<?php

namespace webspell_ng;

use webspell_ng\SocialNetwork;


class Sponsor extends DataStatus {

    /**
     * @var ?int $sponsor_id
     */
    private $sponsor_id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $homepage
     */
    private $homepage;

    /**
     * @var ?string $info
     */
    private $info;

    /**
     * @var ?string $banner
     */
    private $banner;

    /**
     * @var ?string $banner_small
     */
    private $banner_small;

    /**
     * @var bool $is_mainsponsor
     */
    private $is_mainsponsor = false;

    /**
     * @var \DateTime $date
     */
    private $date;

    /**
     * @var array<SocialNetwork> $social_networks
     */
    private $social_networks = array();

    public function setSponsorId(int $sponsor_id): void
    {
        $this->sponsor_id = $sponsor_id;
    }

    public function getSponsorId(): ?int
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

    /**
     * @param array<SocialNetwork> $social_networks
     */
    public function setSocialNetworks(array $social_networks): void
    {
        $this->social_networks = $social_networks;
    }

    /**
     * @return array<SocialNetwork>
     */
    public function getSocialNetworks(): array
    {
        return $this->social_networks;
    }

}