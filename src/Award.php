<?php

namespace webspell_ng;

use \webspell_ng\Squad;
use \webspell_ng\Event;
use \webspell_ng\Utils\ValidationUtils;


class Award {

	/**
	 * @var int $award_id
	 */
	var $award_id;

	/**
	 * @var string $name
	 */
	var $name = null;

	/**
	 * @var ?Event $event
	 */
	var $event = null;

	/**
	 * @var ?Squad $squad
	 */
	var $squad = null;

	/**
	 * @var int $rank
	 */
	var $rank = -1;

	/**
	 * @var ?string $category
	 */
	var $category = null;

	/**
	 * @var ?string $homepage
	 */
	var $homepage = null;

	/**
	 * @var bool $offline
	 */
	var $offline = false;

	/**
	 * @var ?string $info
	 */
	var $info = null;

	/**
	 * @var int $hits
	 */
	var $hits = 0;

	/**
	 * @var \DateTime $date
	 */
	var $date;

	public function __construct()
	{
		$this->date = new \DateTime("now");
	}

	public function setAwardId(int $award_id): void
	{
		$this->award_id = $award_id;
	}

	public function setName(string $name): void
	{

		if (empty($name)) {
			throw new \UnexpectedValueException('enter_title');
		}

		$this->name = $name;

	}

	public function setEvent(Event $event): void
	{
		$this->event = $event;
	}

	public function setSquad(Squad $squad): void
	{
		$this->squad = $squad;
	}

	public function setRank(int $rank): void
	{

		if (!ValidationUtils::validateInteger($rank, true)) {
			throw new \UnexpectedValueException('enter_rank_type');
		}

		$this->rank = $rank;

	}

	public function setOffline(bool $offline): void
	{
		$this->offline = $offline;
	}

	public function setHomepage(string $homepage): void
	{

		if (!ValidationUtils::validateUrl($homepage)) {
			throw new \UnexpectedValueException('enter_url');
		}

		$this->homepage = $homepage;

	}

	public function setLeagueCategory(string $category): void
	{

		if (empty($category)) {
			throw new \UnexpectedValueException('enter_category');
		}

		$this->category = $category;

	}

	public function setDate(\DateTime $date): void
	{
		$this->date = $date;
	}

	public function setDescription(string $info): void
	{

		if (empty($info)) {
			$this->info = '';
		} else {
			$this->info = $info;
		}

	}

	public function setHits(int $hits): void
	{

		if ($hits > 0) {
			$this->hits = $hits;
		}

	}

	public function getAwardId(): ?int
	{
		return $this->award_id;
	}

	public function getEvent(): ?Event
	{
		return $this->event;
	}

	public function getEventId(): ?int
	{

		if (is_null($this->getEvent())) {
			return null;
		}

		return $this->getEvent()->getEventId();

	}

	public function getName(): ?string
	{
		return $this->name;
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

	public function getRank(): ?int
	{
		return $this->rank;
	}

	public function getHomepage(): ?string
	{
		return $this->homepage;
	}

	public function getOffline(): bool
	{
		return $this->offline;
	}

	public function getDescription(): ?string
	{
		return $this->info;
	}

	public function getDate(): \DateTime
	{
		return $this->date;
	}

	public function getLeagueCategory(): ?string
	{
		return $this->category;
	}

}