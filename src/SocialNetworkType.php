<?php

namespace webspell_ng;


class SocialNetworkType extends DataStatus {

    /**
     * @var ?int $social_network_id
     */
    private $social_network_id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $icon_prefix
     */
    private $icon_prefix;

    /**
     * @var ?string $placeholder_player
     */
    private $placeholder_player;

    /**
     * @var ?string $placeholder_team
     */
    private $placeholder_team;

    /**
     * @var bool $is_homepage
     */
    private $is_homepage = true;

    public function setSocialNetworkId(int $social_network_id): void
    {
        $this->social_network_id = $social_network_id;
    }

    public function getSocialNetworkId(): ?int
    {
        return $this->social_network_id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setIconPrefix(string $icon_prefix): void
    {
        $this->icon_prefix = $icon_prefix;
    }

    public function getIconPrefix(): string
    {
        return $this->icon_prefix;
    }

    public function setPlaceholderPlayer(string $placeholder_player): void
    {
        $this->placeholder_player = $placeholder_player;
    }

    public function getPlaceholderPlayer(): string
    {
        return $this->placeholder_player;
    }

    public function setPlaceholderTeam(string $placeholder_team): void
    {
        $this->placeholder_team = $placeholder_team;
    }

    public function getPlaceholderTeam(): string
    {
        return $this->placeholder_team;
    }

    public function setIsHomepage(bool $is_homepage): void
    {
        $this->is_homepage = $is_homepage;
    }

    public function isHomepage(): bool
    {
        return $this->is_homepage;
    }

}
