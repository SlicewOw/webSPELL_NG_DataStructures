<?php

namespace webspell_ng;

use webspell_ng\SocialNetworkType;


class SocialNetwork {

    /**
     * @var SocialNetworkType $type
     */
    private $type;

    /**
     * @var string $value
     */
    private $value;

    public function setSocialNetworkType(SocialNetworkType $type): void
    {
        $this->type = $type;
    }

    public function getSocialNetworkType(): SocialNetworkType
    {
        return $this->type;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
