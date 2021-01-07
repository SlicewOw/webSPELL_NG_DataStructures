<?php

namespace webspell_ng;

use webspell_ng\DataStatus;
use webspell_ng\SquadMemberPosition;
use webspell_ng\User;


class SquadMember extends DataStatus {

    /**
     * @var int $member_id
     */
    private $member_id = null;

    /**
     * @var User $user
     */
    private $user;

    /**
     * @var SquadMemberPosition $position
     */
    private $position;

    /**
     * @var ?\DateTime $join_date
     */
    private $join_date;

    /**
     * @var ?\DateTime $left_date
     */
    private $left_date = null;

    public function setMemberId(int $member_id): void
    {
        $this->member_id = $member_id;
    }

    public function getMemberId(): ?int
    {
        return $this->member_id;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setMemberPosition(SquadMemberPosition $position): void
    {
        $this->position = $position;
    }

    public function getMemberPosition(): SquadMemberPosition
    {
        return $this->position;
    }

    public function setJoinDate(\DateTime $join_date): void
    {
        $this->join_date = $join_date;
    }

    public function getJoinDate(): \DateTime
    {
        if (is_null($this->join_date)) {
            return new \DateTime("now");
        }
        return $this->join_date;
    }

    public function setLeftDate(\DateTime $left_date): void
    {
        $this->left_date = $left_date;
    }

    public function getLeftDate(): ?\DateTime
    {
        return $this->left_date;
    }

}
