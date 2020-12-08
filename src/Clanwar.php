<?php

namespace webspell_ng;

use \webspell_ng\Clan;
use \webspell_ng\ClanwarMap;
use \webspell_ng\Event;
use \webspell_ng\Game;
use \webspell_ng\Squad;
use \webspell_ng\Utils\StringFormatterUtils;
use \webspell_ng\Utils\ValidationUtils;


class Clanwar {

    /**
     * @var int $clanwar_id
     */
    private $clanwar_id = null;

    /**
     * @var Squad $squad
     */
    private $squad = null;

    /**
     * @var array<int> $hometeam
     */
    private $hometeam = array();

    /**
     * @var Clan $opponent_clan
     */
    private $opponent_clan = null;

    /**
     * @var Event $league
     */
    private $league = null;

    /**
     * @var string $match_url
     */
    private $match_url = null;

    /**
     * @var \DateTime $date
     */
    private $date;

    /**
     * @var string $report_de
     */
    private $report_de = null;

    /**
     * @var string $report_uk
     */
    private $report_uk = null;

    /**
     * @var bool $def_win
     */
    private $def_win = false;

    /**
     * @var bool $def_loss
     */
    private $def_loss = false;

    /**
     * @var array<ClanwarMap> $maps
     */
    private $maps = array();

    public function __construct()
    {
        $this->date = new \DateTime("now");
    }

    public function setClanwarId(int $clanwar_id): void
    {
        $this->clanwar_id = $clanwar_id;
    }

    public function getClanwarId(): ?int
    {
        return $this->clanwar_id;
    }

    public function getGame(): ?Game
    {
        if (is_null($this->getSquad())) {
            return null;
        }
        return $this->getSquad()->getGame();
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

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return array<int>
     */
    public function getHometeam(): array
    {
        return $this->hometeam;
    }

    public function getIsDefaultWin(): bool
    {
        return $this->def_win;
    }

    public function getIsDefaultLoss(): bool
    {
        return $this->def_loss;
    }

    public function getMatchHomepage(): ?string
    {
        return $this->match_url;
    }

    public function getReportInGerman(): string
    {
        if (is_null($this->report_de)) {
            return "";
        }
        return $this->report_de;
    }

    public function getReportInEnglish(): string
    {
        if (is_null($this->report_uk)) {
            return "";
        }
        return $this->report_uk;
    }

    public function getEvent(): ?Event
    {
        return $this->league;
    }

    public function getEventId(): ?int
    {
        return $this->getEvent()->getEventId();
    }

    public function getOpponent(): ?Clan
    {
        return $this->opponent_clan;
    }

    /**
     * @return array<ClanwarMap>
     */
    public function getMaps(): array
    {
        return $this->maps;
    }

    /**
     * @param array<int> $players
     */
    public function setSquad(Squad $squad, array $players): void
    {

        $this->squad = $squad;

        if (ValidationUtils::validateArray($players)) {
            $this->hometeam = $players;
        }

    }

    public function setOpponent(Clan $opponent_clan): void
    {
        $this->opponent_clan = $opponent_clan;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function setLeague(Event $event): void
    {
        $this->league = $event;
    }

    public function setMatchURL(string $url): void
    {

        if (empty($url) || !ValidationUtils::validateUrl($url)) {
            throw new \UnexpectedValueException('error_cw_url_format');
        }

        $this->match_url = $url;

    }

    public function setStatus(string $status): void
    {

        $game_type_normal = 'normal';

        if (empty($status)) {
            $status = $game_type_normal;
        }

        $statusArray = array(
            'normal',
            'def_win',
            'def_loss'
        );

        if (!in_array($status, $statusArray)) {
            $status = $game_type_normal;
        }

        if ($status == $game_type_normal) {
            $this->def_win = false;
            $this->def_loss = false;
        } else if ($status == 'def_win') {
            $this->def_win = true;
            $this->def_loss = false;
        } else {
            $this->def_win = false;
            $this->def_loss = true;
        }

    }

    /**
     * @param array<ClanwarMap> $maps
     */
    public function setMap(array $maps): void
    {

        if (!ValidationUtils::validateArray($maps, true)) {
            $maps = array();
        }

        $this->maps = $maps;

    }

    public function setReports(string $report_de="", string $report_uk=""): void
    {

        if (!empty($report_de)) {
            $this->report_de = StringFormatterUtils::getTextFormattedForDatabase($report_de);
        }

        if (!empty($report_uk)) {
            $this->report_uk = StringFormatterUtils::getTextFormattedForDatabase($report_uk);
        }

    }

}
