USE `webspell_ng`;

--
-- Tags
--

CREATE TABLE `ws_p40_tags` (
  `rel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ID` int(11) NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Email
--

CREATE TABLE `ws_p40_email` (
  `emailID` int(1) NOT NULL,
  `user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `port` int(5) NOT NULL,
  `debug` int(1) NOT NULL,
  `auth` int(1) NOT NULL,
  `html` int(1) NOT NULL,
  `smtp` int(1) NOT NULL,
  `secure` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `ws_p40_email`
(`emailID`, `user`, `password`, `host`, `port`, `debug`, `auth`, `html`, `smtp`, `secure`)
VALUES
(1, '', '', '', 25, 0, 0, 1, 0, 0);

ALTER TABLE `ws_p40_email` ADD UNIQUE KEY `emailID` (`emailID`);

--
-- Email
--

CREATE TABLE `ws_p40_captcha` (
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `captcha` int(11) NOT NULL DEFAULT 0,
  `deltime` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_captcha` ADD PRIMARY KEY (`hash`);

--
-- Sponsors
--

CREATE TABLE `ws_p40_sponsors` (
  `sponsorID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `info` text NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `banner_small` varchar(255) DEFAULT NULL,
  `banner_white` varchar(100) DEFAULT NULL,
  `banner_small_white` varchar(100) DEFAULT NULL,
  `displayed` int(1) NOT NULL DEFAULT 1,
  `mainsponsor` int(1) NOT NULL DEFAULT 0,
  `subpage_only` int(1) NOT NULL DEFAULT 0,
  `frontpage_only` int(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 1,
  `hits` int(11) DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_sponsors` ADD PRIMARY KEY (`sponsorID`);
ALTER TABLE `ws_p40_sponsors` MODIFY `sponsorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- User
--

CREATE TABLE `ws_p40_user` (
  `userID` int(11) NOT NULL,
  `registerdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `firstlogin` datetime DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_check` int(1) NOT NULL DEFAULT 0,
  `confirm` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `delete_confirm` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_hide` int(1) NOT NULL DEFAULT 1,
  `email_change` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_activate` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `accept_cookies` int(11) NOT NULL DEFAULT 0,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sex` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'm',
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'eu',
  `town` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `birthday` datetime DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `usertext` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `userpic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `newsletter` int(1) NOT NULL DEFAULT 1,
  `about` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pmgot` int(11) NOT NULL DEFAULT 0,
  `pmsent` int(11) NOT NULL DEFAULT 0,
  `visits` int(11) NOT NULL DEFAULT 0,
  `banned` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ban_reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `topics` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `articles` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `demos` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `files` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `gallery_pictures` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `mailonpm` int(1) NOT NULL DEFAULT 0,
  `userdescription` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'de',
  `activity` int(11) NOT NULL DEFAULT 0,
  `activityID` int(11) NOT NULL DEFAULT 0,
  `activityadminID` int(11) NOT NULL DEFAULT 0,
  `activitycupID` int(11) NOT NULL DEFAULT 0,
  `special_rank` int(11) NOT NULL DEFAULT 0,
  `date_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'd.m.Y',
  `time_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'H:i',
  `overview_redirect` int(1) NOT NULL DEFAULT 0,
  `hits` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_user` ADD PRIMARY KEY (`userID`), ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `ws_p40_user` MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `ws_p40_user` (`username`, `password`, `firstname`, `email`, `birthday`, `country`, `activated`) VALUES
('Test User', 'de33018dfe7f5cfd9791ad42fc519372254330c3831e33f0728be35affdae067b2b725d627c5d89535841f7e91af40ef9cabc5db995cdcdd45dde7dd432272ed', 'Firstname', 'info@webspell-ng.de', '2020-09-04 00:00:00', 'eu', '4330c3831e33f0728be35affd');

--
-- User Log
--

CREATE TABLE `ws_p40_user_log` (
  `userID` int(11) NOT NULL,
  `username` varchar(100) COLLATE latin1_german1_ci NOT NULL,
  `date` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `action` varchar(255) COLLATE latin1_german1_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_user_log` ADD KEY `FK_UserLog_UserId` (`userID`);
ALTER TABLE `ws_p40_user_log` ADD CONSTRAINT `FK_UserLog_UserId` FOREIGN KEY (`userID`) REFERENCES `ws_p40_user` (`userID`) ON DELETE CASCADE;

--
-- User groups
--

CREATE TABLE `ws_p40_user_groups` (
  `userID` int(11) NOT NULL DEFAULT 0,
  `news` int(11) NOT NULL DEFAULT 0,
  `news_writer` int(11) NOT NULL DEFAULT 0,
  `newsletter` int(11) NOT NULL DEFAULT 0,
  `polls` int(11) NOT NULL DEFAULT 0,
  `forum` int(11) NOT NULL DEFAULT 0,
  `moderator` int(11) NOT NULL DEFAULT 0,
  `clanwars` int(11) NOT NULL DEFAULT 0,
  `feedback` int(11) NOT NULL DEFAULT 0,
  `user` int(11) NOT NULL DEFAULT 0,
  `page` int(11) NOT NULL DEFAULT 0,
  `files` int(11) NOT NULL DEFAULT 0,
  `cash` int(11) NOT NULL DEFAULT 0,
  `gallery` int(11) NOT NULL DEFAULT 0,
  `cup` int(11) NOT NULL DEFAULT 0,
  `radio` int(11) NOT NULL DEFAULT 0,
  `tv` int(11) NOT NULL DEFAULT 0,
  `dev` int(11) NOT NULL DEFAULT 0,
  `super` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_user_groups` ADD UNIQUE KEY `userID` (`userID`);

INSERT INTO `ws_p40_user_groups` (`userID`, `super`) VALUES (1, 1);

ALTER TABLE `ws_p40_user_groups` ADD CONSTRAINT `FK_UserGroups_UserId` FOREIGN KEY (`userID`) REFERENCES `ws_p40_user` (`userID`) ON DELETE CASCADE;

--
-- Settings
--

CREATE TABLE `ws_p40_settings` (
  `settingID` int(11) NOT NULL,
  `page` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cup_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `tv_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `forum_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `admin_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `static_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `doc_url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bot_url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `download_url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `system_user` int(11) DEFAULT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `hp_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `clanname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clantag` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `adminname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `adminemail` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `facebook` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `twitter` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `youtube` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `steam` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cronjob` int(11) NOT NULL DEFAULT 0,
  `news` int(11) NOT NULL DEFAULT 0,
  `newsarchiv` int(11) NOT NULL DEFAULT 0,
  `headlines` int(11) NOT NULL DEFAULT 0,
  `headlineschars` int(11) NOT NULL DEFAULT 0,
  `topnewschars` int(11) NOT NULL DEFAULT 0,
  `articles` int(11) NOT NULL DEFAULT 0,
  `latestarticles` int(11) NOT NULL DEFAULT 0,
  `articleschars` int(11) NOT NULL DEFAULT 0,
  `clanwars` int(11) NOT NULL DEFAULT 0,
  `results` int(11) NOT NULL DEFAULT 0,
  `upcoming` int(11) NOT NULL DEFAULT 0,
  `shoutbox` int(11) NOT NULL DEFAULT 0,
  `sball` int(11) NOT NULL DEFAULT 0,
  `sbrefresh` int(11) NOT NULL DEFAULT 0,
  `topics` int(11) NOT NULL DEFAULT 0,
  `posts` int(11) NOT NULL DEFAULT 0,
  `latesttopics` int(11) NOT NULL DEFAULT 0,
  `latesttopicchars` int(11) NOT NULL DEFAULT 0,
  `awards` int(11) NOT NULL DEFAULT 0,
  `demos` int(11) NOT NULL DEFAULT 0,
  `guestbook` int(11) NOT NULL DEFAULT 0,
  `feedback` int(11) NOT NULL DEFAULT 0,
  `messages` int(11) NOT NULL DEFAULT 0,
  `users` int(11) NOT NULL DEFAULT 0,
  `profilelast` int(11) NOT NULL DEFAULT 0,
  `topnewsID` int(11) NOT NULL DEFAULT 0,
  `sessionduration` int(11) NOT NULL DEFAULT 0,
  `closed` int(11) NOT NULL DEFAULT 0,
  `gb_info` int(11) NOT NULL DEFAULT 1,
  `imprint` int(11) NOT NULL DEFAULT 0,
  `picsize_l` int(11) NOT NULL DEFAULT 450,
  `picsize_h` int(11) NOT NULL DEFAULT 500,
  `pictures` int(11) NOT NULL DEFAULT 12,
  `publicadmin` int(11) NOT NULL DEFAULT 1,
  `thumbwidth` int(11) NOT NULL DEFAULT 130,
  `usergalleries` int(11) NOT NULL DEFAULT 1,
  `maxusergalleries` int(11) NOT NULL DEFAULT 1048576,
  `default_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'uk',
  `insertlinks` int(11) NOT NULL DEFAULT 1,
  `search_min_len` int(11) NOT NULL DEFAULT 3,
  `max_wrong_pw` int(11) NOT NULL DEFAULT 10,
  `captcha_math` int(11) NOT NULL DEFAULT 2,
  `captcha_bgcol` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#FFFFFF',
  `captcha_fontcol` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#000000',
  `captcha_type` int(11) NOT NULL DEFAULT 2,
  `captcha_noise` int(11) NOT NULL DEFAULT 100,
  `captcha_linenoise` int(11) NOT NULL DEFAULT 10,
  `autoresize` int(11) NOT NULL DEFAULT 2,
  `bancheck` int(11) NOT NULL DEFAULT 0,
  `gpicsize_l` int(11) NOT NULL DEFAULT 60,
  `gpicsize_h` int(11) NOT NULL DEFAULT 60,
  `max_lastpic` int(11) NOT NULL DEFAULT 10,
  `spam_check` int(11) NOT NULL DEFAULT 0,
  `detect_language` int(11) NOT NULL DEFAULT 0,
  `spamapikey` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `spamapihost` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `spammaxposts` int(11) NOT NULL DEFAULT 0,
  `spamapiblockerror` int(11) NOT NULL DEFAULT 0,
  `date_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'd.m.y',
  `time_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'G:i',
  `user_guestbook` int(11) NOT NULL DEFAULT 0,
  `sc_files` int(11) NOT NULL DEFAULT 1,
  `sc_demos` int(11) NOT NULL DEFAULT 0,
  `modRewrite` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `ws_p40_settings` ADD PRIMARY KEY (`settingID`), ADD UNIQUE KEY `page` (`page`);
ALTER TABLE `ws_p40_settings` MODIFY `settingID` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `ws_p40_settings`
(`settingID`,   `page`,           `cup_url`,            `tv_url`,         `forum_url`,         `admin_url`,         `image_url`,        `static_url`,           `doc_url`, `bot_url`,      `download_url`, `system_user`,              `title`,            `hp_url`,           `clanname`,   `clantag`, `adminname`, `adminemail`)
VALUES
(          1, 'gaming', 'http://localhost/', 'http://localhost/', 'http://localhost/', 'http://localhost/', 'http://localhost/', 'http://localhost/', 'http://localhost/',      NULL, 'http://localhost/',          NULL, 'myRisk Gaming e.V.', 'http://localhost/', 'myRisk Gaming e.V.', 'myRisk eV',          '',           '');

--
-- Games
--

CREATE TABLE `ws_p40_games` (
  `gameID` int(11) NOT NULL,
  `tag` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `short` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `ws_p40_games` (`gameID`, `tag`, `name`, `short`, `active`) VALUES
(1, 'cs', 'Counter-Strike', 'CS1.6', 1),
(2, 'ut', 'Unreal Tournament', 'UT', 1),
(3, 'to', 'Tactical Ops', 'TO', 1),
(4, 'hl2', 'Half-Life 2', "HL 2", 1),
(5, 'wc3', 'WarCraft 3', "WC3", 1),
(6, 'hl', 'Half-Life', "HL", 1),
(7, 'bf', 'Battlefield 1942', "BF 1942", 1),
(8, 'aa', 'Americas Army', "AA", 1),
(9, 'aoe', 'Age of Empires 3', "AoE 3", 1),
(10, 'b21', 'Battlefield 2142', "BF 2142", 1),
(11, 'bf2', 'Battlefield 2', "BF 2", 1),
(12, 'bfv', 'Battlefield Vietnam', "BF Vietnam", 1),
(13, 'c3d', 'Carom 3D', "C3D", 1),
(14, 'cc3', 'Command &amp; Conquer', "C&amp;C", 1),
(15, 'cd2', 'Call of Duty 2', "CoD 2", 1),
(16, 'cd4', 'Call of Duty 4', "CoD 4", 1),
(17, 'cod', 'Call of Duty', "CoD", 1),
(18, 'coh', 'Company of Heroes', "CoH", 1),
(19, 'crw', 'Crysis Wars', "CW", 1),
(20, 'cry', 'Crysis', "Crysis", 1),
(21, 'css', 'Counter-Strike: Source', "CS:S", 1),
(22, 'cz', 'Counter-Strike: Condition Zero', "CS:CZ", 1),
(23, 'dds', 'Day of Defeat: Source', "DoD:S", 1),
(24, 'dod', 'Day of Defeat', "DoD", 1),
(25, 'dow', 'Dawn of War', "DoW", 1),
(26, 'dta', 'DotA', "DotA", 1),
(27, 'et', 'Enemy Territory', "ET", 1),
(28, 'fc', 'FarCry', "FC", 1),
(29, 'fer', 'F.E.A.R.', "F.E.A.R.", 1),
(30, 'fif', 'FIFA', "FIFA", 1),
(31, 'fl', 'Frontlines: Fuel of War', "F: FoW", 1),
(32, 'hal', 'HALO', "HALO", 1),
(33, 'jk2', 'Jedi Knight 2', "JK 2", 1),
(34, 'jk3', 'Jedi Knight 3', "JK 3", 1),
(35, 'lfs', 'Live for Speed', "LfS", 1),
(36, 'lr2', 'LotR: Battle for Middle Earth 2', "LotR: BfME 2", 1),
(37, 'lr', 'LotR: Battle for Middle Earth', "LotR: BfME", 1),
(38, 'moh', 'Medal of Hornor', "MoH", 1),
(39, 'nfs', 'Need for Speed', "NfS", 1),
(40, 'pes', 'Pro Evolution Soccer', "PES", 1),
(41, 'q3', 'Quake 3', "Q3", 1),
(42, 'q4', 'Quake 4', "Q4", 1),
(43, 'ql', 'Quake Live', "QL", 1),
(44, 'rdg', 'Race Driver Grid', "RDG", 1),
(45, 'sc2', 'StarCraft 2', "SC2", 1),
(46, 'sc', 'StarCraft', "SC", 1),
(47, 'sof', 'Soldier of Fortune 2', "SoS 2", 1),
(48, 'sw2', 'Star Wars: Battlefront 2', "SW:B2", 1),
(49, 'sw', 'Star Wars: Battlefront', "SW:B", 1),
(50, 'swa', 'SWAT 4', "SWAT 4", 1),
(51, 'tf2', 'Team Fortress 2', "TF2", 1),
(52, 'tf', 'Team Fortress', "TF", 1),
(53, 'tm', 'TrackMania', "TM", 1),
(54, 'ut3', 'Unreal Tournament 3', "UT3", 1),
(55, 'ut4', 'Unreal Tournament 2004', "UT2004", 1),
(56, 'war', 'War Rock', "WR", 1),
(57, 'wic', 'World in Conflict', "WiC", 1),
(58, 'wow', 'World of Warcraft', "WoW", 1),
(59, 'wrs', 'Warsow', 'Warsow', 1),
(60, 'csg', 'Counter-Strike: Global Offensive', 'CS:GO', 1),
(61, 'dt2', 'DotA 2', "DotA 2", 1);

ALTER TABLE `ws_p40_games` ADD PRIMARY KEY (`gameID`), ADD UNIQUE KEY `tag` (`tag`);
ALTER TABLE `ws_p40_games` MODIFY `gameID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Countries
--

CREATE TABLE `ws_p40_countries` (
  `countryID` int(11) NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `fav` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `ws_p40_countries` (`countryID`, `country`, `short`, `fav`) VALUES
(1, 'Argentina', 'ar', 0),
(2, 'Australia', 'au', 0),
(3, 'Austria', 'at', 0),
(4, 'Belgium', 'be', 0),
(5, 'Bosnia and Herzegowina', 'ba', 0),
(6, 'Brazil', 'br', 0),
(7, 'Bulgaria', 'bg', 0),
(8, 'Canada', 'ca', 0),
(9, 'Chile', 'cl', 0),
(10, 'China', 'cn', 0),
(11, 'Colombia', 'co', 0),
(12, 'Czech Republic', 'cz', 0),
(13, 'Croatia', 'hr', 0),
(14, 'Cyprus', 'cy', 0),
(15, 'Denmark', 'dk', 0),
(16, 'Estonia', 'ee', 0),
(17, 'Finland', 'fi', 0),
(18, 'Faroe Islands', 'fo', 0),
(19, 'France', 'fr', 0),
(20, 'Germany', 'de', 0),
(21, 'Greece', 'gr', 0),
(22, 'Hungary', 'hu', 0),
(23, 'Iceland', 'is', 0),
(24, 'Ireland', 'ie', 0),
(25, 'Israel', 'il', 0),
(26, 'Italy', 'it', 0),
(27, 'Japan', 'jp', 0),
(28, 'South Korea', 'kr', 0),
(29, 'Latvia', 'lv', 0),
(30, 'Lithuania', 'lt', 0),
(31, 'Luxembourg', 'lu', 0),
(32, 'Malaysia', 'my', 0),
(33, 'Malta', 'mt', 0),
(34, 'Netherlands', 'nl', 0),
(35, 'Mexico', 'mx', 0),
(36, 'Mongolia', 'mn', 0),
(37, 'New Zealand', 'nz', 0),
(38, 'Norway', 'no', 0),
(39, 'Poland', 'pl', 0),
(40, 'Portugal', 'pt', 0),
(41, 'Romania', 'ro', 0),
(42, 'Russia', 'ru', 0),
(43, 'Singapore', 'sg', 0),
(44, 'Slovakia', 'sk', 0),
(45, 'Slovenia', 'si', 0),
(46, 'Taiwan', 'tw', 0),
(47, 'South Africa', 'za', 0),
(48, 'Spain', 'es', 0),
(49, 'Sweden', 'se', 0),
(50, 'Syria', 'sy', 0),
(51, 'Switzerland', 'ch', 0),
(53, 'Tunisia', 'tn', 0),
(54, 'Turkey', 'tr', 0),
(55, 'Ukraine', 'ua', 0),
(56, 'United Kingdom', 'uk', 0),
(57, 'USA', 'us', 0),
(58, 'Venezuela', 've', 0),
(59, 'Yugoslavia', 'rs', 0),
(60, 'European Union', 'eu', 0),
(61, 'Albania', 'al', 0),
(62, 'Algeria', 'dz', 0),
(63, 'American Samoa', 'as', 0),
(64, 'Andorra', 'ad', 0),
(65, 'Angola', 'ao', 0),
(66, 'Anguilla', 'ai', 0),
(67, 'Antarctica', 'aq', 0),
(68, 'Antigua and Barbuda', 'ag', 0),
(69, 'Armenia', 'am', 0),
(70, 'Aruba', 'aw', 0),
(71, 'Azerbaijan', 'az', 0),
(72, 'Belize', 'bz', 0),
(73, 'Bahrain', 'bh', 0),
(74, 'Bangladesh', 'bd', 0),
(75, 'Barbados', 'bb', 0),
(76, 'Belarus', 'by', 0),
(77, 'Benelux', 'bx', 0),
(78, 'Benin', 'bj', 0),
(79, 'Bermuda', 'bm', 0),
(80, 'Bhutan', 'bt', 0),
(81, 'Bolivia', 'bo', 0),
(82, 'Botswana', 'bw', 0),
(85, 'Brunei', 'bn', 0),
(86, 'Burkina Faso', 'bf', 0),
(87, 'Burundi', 'bi', 0),
(88, 'Cambodia', 'kh', 0),
(89, 'Cameroon', 'cm', 0),
(90, 'Cape Verde', 'cv', 0),
(91, 'Cayman Islands', 'ky', 0),
(92, 'Central African Republic', 'cf', 0),
(93, 'Christmas Island', 'cx', 0),
(94, 'Cocos Islands', 'cc', 0),
(95, 'Comoros', 'km', 0),
(96, 'Congo', 'cg', 0),
(97, 'Cook Islands', 'ck', 0),
(98, 'Costa Rica', 'cr', 0),
(99, 'Ivory Coast', 'ci', 0),
(100, 'Cuba', 'cu', 0),
(101, 'Democratic Congo', 'cd', 0),
(102, 'North Korea', 'kp', 0),
(103, 'Djibouti', 'dj', 0),
(104, 'Dominica', 'dm', 0),
(105, 'Dominican Republic', 'do', 0),
(107, 'Ecuador', 'ec', 0),
(108, 'Egypt', 'eg', 0),
(109, 'El Salvador', 'sv', 0),
(110, 'England', 'en', 0),
(111, 'Eritrea', 'er', 0),
(112, 'Ethiopia', 'et', 0),
(113, 'Falkland Islands', 'fk', 0),
(114, 'Fiji', 'fj', 0),
(115, 'French Polynesia', 'pf', 0),
(116, 'French Southern Territories', 'tf', 0),
(117, 'Gabon', 'ga', 0),
(118, 'Gambia', 'gm', 0),
(119, 'Georgia', 'ge', 0),
(120, 'Ghana', 'gh', 0),
(121, 'Gibraltar', 'gi', 0),
(122, 'Greenland', 'gl', 0),
(123, 'Grenada', 'gd', 0),
(125, 'Guam', 'gu', 0),
(126, 'Guatemala', 'gt', 0),
(127, 'Guinea', 'gn', 0),
(128, 'Guinea-Bissau', 'gw', 0),
(129, 'Guyana', 'gy', 0),
(130, 'Haiti', 'ht', 0),
(132, 'Vatican City', 'va', 0),
(133, 'Honduras', 'hn', 0),
(134, 'Hong Kong', 'hk', 0),
(135, 'India', 'in', 0),
(136, 'Indonesia', 'id', 0),
(137, 'Iran', 'ir', 0),
(138, 'Iraq', 'iq', 0),
(139, 'Jamaica', 'jm', 0),
(140, 'Jordan', 'jo', 0),
(141, 'Kazakhstan', 'kz', 0),
(142, 'Kenya', 'ke', 0),
(143, 'Kiribati', 'ki', 0),
(144, 'Kuwait', 'kw', 0),
(145, 'Kyrgyzstan', 'kg', 0),
(146, 'Laos', 'la', 0),
(147, 'Lebanon', 'lb', 0),
(148, 'Lesotho', 'ls', 0),
(149, 'Liberia', 'lr', 0),
(150, 'Libya', 'ly', 0),
(151, 'Liechtenstein', 'li', 0),
(152, 'Macau', 'mo', 0),
(153, 'Macedonia', 'mk', 0),
(154, 'Madagascar', 'mg', 0),
(155, 'Malawi', 'mw', 0),
(156, 'Maldives', 'mv', 0),
(157, 'Mali', 'ml', 0),
(158, 'Marshall Islands', 'mh', 0),
(159, 'Mauritania', 'mr', 0),
(160, 'Mauritius', 'mu', 0),
(161, 'Micronesia', 'fm', 0),
(162, 'Moldova', 'md', 0),
(163, 'Monaco', 'mc', 0),
(164, 'Montserrat', 'ms', 0),
(165, 'Morocco', 'ma', 0),
(166, 'Mozambique', 'mz', 0),
(167, 'Burma', 'mm', 0),
(169, 'Nauru', 'nr', 0),
(170, 'Nepal', 'np', 0),
(171, 'Netherlands Antilles', 'an', 0),
(172, 'New Caledonia', 'nc', 0),
(173, 'Nicaragua', 'ni', 0),
(174, 'Nigeria', 'ng', 0),
(175, 'Niue', 'nu', 0),
(176, 'Norfolk Island', 'nf', 0),
(178, 'Northern Mariana Islands', 'mp', 0),
(179, 'Oman', 'om', 0),
(180, 'Pakistan', 'pk', 0),
(181, 'Palau', 'pw', 0),
(182, 'Palestinian', 'ps', 0),
(183, 'Panama', 'pa', 0),
(184, 'Papua New Guinea', 'pg', 0),
(185, 'Paraguay', 'py', 0),
(186, 'Peru', 'pe', 0),
(187, 'Philippines', 'ph', 0),
(188, 'Pitcairn', 'pn', 0),
(189, 'Puerto Rico', 'pr', 0),
(190, 'Qatar', 'qa', 0),
(191, 'Reunion', 're', 0),
(192, 'Rwanda', 'rw', 0),
(193, 'Saint Helena', 'sh', 0),
(194, 'Saint Kitts and Nevis', 'kn', 0),
(195, 'Saint Lucia', 'lc', 0),
(197, 'Saint Vincent', 'vc', 0),
(198, 'Samoa', 'ws', 0),
(199, 'San Marino', 'sm', 0),
(200, 'Sao Tome and Principe', 'st', 0),
(201, 'Saudi Arabia', 'sa', 0),
(202, 'Seychelles', 'sc', 0),
(203, 'Senegal', 'sn', 0),
(204, 'Sierra Leone', 'sl', 0),
(205, 'Solomon Islands', 'sb', 0),
(206, 'Somalia', 'so', 0),
(207, 'South Georgia and the South Sandwich Islands', 'gs', 0),
(208, 'Sri Lanka', 'lk', 0),
(209, 'Sudan', 'sd', 0),
(210, 'Suriname', 'sr', 0),
(212, 'Swaziland', 'sz', 0),
(213, 'Tajikistan', 'tj', 0),
(214, 'Tanzania', 'tz', 0),
(215, 'Thailand', 'th', 0),
(216, 'Togo', 'tg', 0),
(217, 'Tokelau', 'tk', 0),
(218, 'Tonga', 'to', 0),
(219, 'Trinidad and Tobago', 'tt', 0),
(220, 'Turkmenistan', 'tm', 0),
(221, 'Turks and Caicos Islands', 'tc', 0),
(222, 'Tuvalu', 'tv', 0),
(223, 'Uganda', 'ug', 0),
(224, 'United Arab Emirates', 'ae', 0),
(225, 'Uruguay', 'uy', 0),
(226, 'Uzbekistan', 'uz', 0),
(227, 'Vanuatu', 'vu', 0),
(228, 'Vietnam', 'vn', 0),
(229, 'Virgin Islands (British)', 'vg', 0),
(230, 'Virgin Islands (USA)', 'vi', 0),
(232, 'Wallis and Futuna', 'wf', 0),
(233, 'Western Sahara', 'eh', 0),
(234, 'Yemen', 'ye', 0),
(235, 'Zambia', 'zm', 0),
(236, 'Zimbabwe', 'zw', 0),
(237, 'Afghanistan', 'af', 0),
(238, 'Aland Islands', 'ax', 0),
(239, 'Bahamas', 'bs', 0),
(240, 'Saint Barthelemy', 'bl', 0),
(241, 'Caribbean Netherlands', 'bq', 0),
(242, 'Chad', 'td', 0),
(243, 'Curacao', 'cw', 0),
(244, 'French Guiana', 'gf', 0),
(245, 'Guernsey', 'gg', 0),
(246, 'Equatorial Guinea', 'gq', 0),
(247, 'Canary Islands', 'ic', 0),
(248, 'Isle of Man', 'im', 0),
(249, 'Jersey', 'je', 0),
(250, 'Kosovo', 'xk', 0),
(251, 'Martinique', 'mq', 0),
(252, 'Mayotte', 'yt', 0),
(253, 'Montenegro', 'me', 0),
(254, 'Namibia', 'na', 0),
(255, 'Niger', 'ne', 0),
(256, 'Saint Barthelemy', 'bl', 0),
(257, 'Saint Martin', 'mf', 0),
(258, 'Serbia', 'rs', 0),
(259, 'South Sudan', 'ss', 0),
(260, 'Timor-Leste', 'tl', 0);

ALTER TABLE `ws_p40_countries` ADD PRIMARY KEY (`countryID`);
ALTER TABLE `ws_p40_countries` MODIFY `countryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- Squads
--

CREATE TABLE `ws_p40_squads` (
  `squadID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` int(11) NOT NULL,
  `gamesquad` int(11) NOT NULL DEFAULT 1,
  `rubric` int(3) NOT NULL DEFAULT 3,
  `console` int(1) NOT NULL DEFAULT 0,
  `gameID` int(11) DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `icon_small` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `info` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `hits` int(11) NOT NULL DEFAULT 0,
  `active` int(1) NOT NULL DEFAULT 1,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `date_deleted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_squads` ADD PRIMARY KEY (`squadID`);
ALTER TABLE `ws_p40_squads` MODIFY `squadID` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `ws_p40_squads` (`squadID`, `name`, `date`, `gamesquad`, `rubric`, `gameID`) VALUES
(1, 'Team Red', 1007638496, 1, 3, 1);

--
-- Clan
--

CREATE TABLE `ws_p40_clans` (
  `clanID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `homepage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logotype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_clans` ADD PRIMARY KEY (`clanID`);
ALTER TABLE `ws_p40_clans` MODIFY `clanID` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `ws_p40_clans` (`clanID`, `name`, `tag`, `homepage`) VALUES
(1, 'myRisk Gaming e.V.', 'myRisk e.V.', 'https://gaming.myrisk-ev.de');

--
-- Events
--

CREATE TABLE `ws_p40_events` (
  `eventID` int(11) NOT NULL,
  `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `squadID` int(11) NOT NULL DEFAULT 0,
  `offline` int(1) NOT NULL DEFAULT 0,
  `info` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `hits` int(11) NOT NULL DEFAULT 0,
  `active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_events` ADD PRIMARY KEY (`eventID`), ADD UNIQUE KEY `eventID` (`eventID`);
ALTER TABLE `ws_p40_events` MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Awards
--

CREATE TABLE `ws_p40_awards` (
  `awardID` int(11) NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `eventID` int(11) DEFAULT NULL,
  `squadID` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rang` int(5) NOT NULL DEFAULT 0,
  `offline` int(1) NOT NULL DEFAULT 0,
  `info` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `hits` int(11) NOT NULL DEFAULT 0,
  `active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_awards` ADD PRIMARY KEY (`awardID`);
ALTER TABLE `ws_p40_awards` MODIFY `awardID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Clanwars
--

CREATE TABLE `ws_p40_clanwars` (
  `cwID` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `squadID` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `homepage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `opponentID` int(11) DEFAULT NULL,
  `hometeam` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'a:0:{}',
  `report` text COLLATE utf8_unicode_ci NOT NULL,
  `report_uk` text COLLATE utf8_unicode_ci NOT NULL,
  `comments` int(1) NOT NULL DEFAULT 1,
  `hits` int(11) NOT NULL DEFAULT 0,
  `active` int(1) NOT NULL DEFAULT 1,
  `def_win` int(1) NOT NULL DEFAULT 0,
  `def_loss` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_clanwars` ADD PRIMARY KEY (`cwID`);
ALTER TABLE `ws_p40_clanwars` MODIFY `cwID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Clanwars Maps
--

CREATE TABLE `ws_p40_clanwars_maps` (
  `mapID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gameID` int(11) NOT NULL,
  `pic` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_clanwars_maps` ADD PRIMARY KEY (`mapID`), ADD UNIQUE KEY `mapID` (`mapID`);
ALTER TABLE `ws_p40_clanwars_maps` MODIFY `mapID` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `ws_p40_clanwars_maps` (`mapID`, `name`, `gameID`, `pic`) VALUES
(1, 'de_dust2', '1', 'cs_de_dust2.jpg'),
(2, 'de_tuscan', '1', 'cs_de_tuscan.jpg'),
(3, 'de_cpl_mill', '1', 'cs_de_cpl_mill.jpg');

--
-- Clanwars Maps Mapping
--

CREATE TABLE `ws_p40_clanwars_maps_mapping` (
  `mappingID` int(11) NOT NULL,
  `cw_id` int(11) NOT NULL,
  `map_id` int(11) NOT NULL,
  `map_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `score_home` int(11) NOT NULL,
  `score_opponent` int(11) NOT NULL,
  `def_win` int(1) NOT NULL DEFAULT 0,
  `def_loss` int(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_clanwars_maps_mapping` ADD PRIMARY KEY (`mappingID`);
ALTER TABLE `ws_p40_clanwars_maps_mapping` MODIFY `mappingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contact receiver
--

CREATE TABLE `ws_p40_contact` (
  `contactID` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_contact` ADD PRIMARY KEY (`contactID`), ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `ws_p40_contact` MODIFY `contactID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Squad Member Position
--

CREATE TABLE `ws_p40_squads_members_position` (
  `positionID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gameID` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_squads_members_position` ADD PRIMARY KEY (`positionID`);
ALTER TABLE `ws_p40_squads_members_position` MODIFY `positionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Squad Member
--

CREATE TABLE `ws_p40_squads_members` (
  `sqmID` int(11) NOT NULL,
  `squadID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `positionID` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `join_date` int(11) NOT NULL,
  `left_date` int(11) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_squads_members`
  ADD PRIMARY KEY (`sqmID`),
  ADD UNIQUE KEY `unique_squad_member_row` (`squadID`,`userID`),
  ADD KEY `FK_SquadsMembers_UserId` (`userID`),
  ADD KEY `FK_SquadMembers_PositionId` (`positionID`);

ALTER TABLE `ws_p40_squads_members` MODIFY `sqmID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ws_p40_squads_members`
  ADD CONSTRAINT `FK_SquadMembers_PositionId` FOREIGN KEY (`positionID`) REFERENCES `ws_p40_squads_members_position` (`positionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_SquadsMembers_PositionId` FOREIGN KEY (`positionID`) REFERENCES `ws_p40_squads_members_position` (`positionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_SquadsMembers_SquadId` FOREIGN KEY (`squadID`) REFERENCES `ws_p40_squads` (`squadID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_SquadsMembers_UserId` FOREIGN KEY (`userID`) REFERENCES `ws_p40_user` (`userID`) ON DELETE CASCADE;

--
-- Privacy policy
--

CREATE TABLE `ws_p40_privacy_policy` (
  `page` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_privacy_policy` ADD UNIQUE KEY (`page`);

INSERT INTO `ws_p40_privacy_policy`
(`page`, `description`, `date`)
VALUES
('default', 'To be continued ...', "2020-01-01 13:37:00");

--
-- Imprint
--

CREATE TABLE `ws_p40_imprint` (
  `page` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_imprint` ADD UNIQUE KEY (`page`);

INSERT INTO `ws_p40_imprint`
(`page`, `description`, `date`)
VALUES
('default', 'all rights reserved', "2020-01-01 13:37:00");

--
-- About Us
--

CREATE TABLE `ws_p40_about` (
  `page` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_about` ADD UNIQUE KEY (`page`);

INSERT INTO `ws_p40_about`
(`page`, `description`, `date`)
VALUES
('default', 'To be determined ...', "2020-09-04 09:18:00");

--
-- News rubrics
--

CREATE TABLE `ws_p40_news_rubrics` (
  `rubricID` int(11) NOT NULL,
  `rubric` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_news_rubrics` ADD PRIMARY KEY (`rubricID`);
ALTER TABLE `ws_p40_news_rubrics` MODIFY `rubricID` int(11) NOT NULL AUTO_INCREMENT;

--
-- News languages
--

CREATE TABLE `ws_p40_news_languages` (
  `langID` int(11) NOT NULL,
  `language` varchar(255) NOT NULL,
  `lang` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_news_languages` ADD PRIMARY KEY (`langID`);
ALTER TABLE `ws_p40_news_languages` MODIFY `langID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ws_p40_news_languages` ADD UNIQUE(`lang`);

INSERT INTO `ws_p40_news_languages`
(`language`, `lang`)
VALUES
('Deutsch', 'de'),
('English', 'en'),
('Francais', 'fr');

--
-- News
--

CREATE TABLE `ws_p40_news` (
  `newsID` int(11) NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `rubricID` int(11) DEFAULT NULL,
  `writer` int(11) NOT NULL,
  `cwID` int(11) DEFAULT NULL,
  `squadID` int(11) DEFAULT NULL,
  `eventID` int(11) DEFAULT NULL,
  `comments` int(1) NOT NULL DEFAULT 1,
  `published` int(1) NOT NULL DEFAULT 0,
  `internal` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_news` ADD PRIMARY KEY (`newsID`);
ALTER TABLE `ws_p40_news` MODIFY `newsID` int(11) NOT NULL AUTO_INCREMENT;

--
-- News contents
--

CREATE TABLE `ws_p40_news_contents` (
  `newsID` int(11) NOT NULL,
  `language` varchar(2) NOT NULL,
  `headline` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- News sources
--

CREATE TABLE `ws_p40_news_sources` (
  `sourceID` int(11) NOT NULL,
  `newsID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `homepage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_news_sources` ADD PRIMARY KEY (`sourceID`);
ALTER TABLE `ws_p40_news_sources` MODIFY `sourceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Partners
--

CREATE TABLE `ws_p40_partners` (
  `partnerID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `homepage` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `date` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `displayed` int(1) NOT NULL DEFAULT 1,
  `hits` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_partners` ADD PRIMARY KEY (`partnerID`);
ALTER TABLE `ws_p40_partners` MODIFY `partnerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- History
--

CREATE TABLE `ws_p40_history` (
  `year` int(11) NOT NULL DEFAULT 2021,
  `history` text COLLATE utf8_unicode_ci NOT NULL,
  `date` int(11) DEFAULT NULL,
  `public` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_history` ADD UNIQUE KEY `year` (`year`);

--
-- Social network types
--

CREATE TABLE `ws_p40_user_socials_types` (
  `typeID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon_prefix` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `placeholder` varchar(100) DEFAULT NULL,
  `placeholder_team` varchar(255) DEFAULT NULL,
  `is_url` int(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `ws_p40_user_socials_types` (`typeID`, `name`, `icon_prefix`, `placeholder`, `placeholder_team`, `is_url`, `sort`) VALUES
(1, 'Homepage', 'homepage', 'http://', NULL, 1, 1),
(2, 'Skype', 'skype', NULL, NULL, 0, 6),
(3, 'Facebook', 'facebook', 'https://facebook.com', NULL, 1, 2),
(4, 'Twitter', 'twitter', 'https://twitter.com', NULL, 1, 3),
(5, 'YouTube', 'youtube', 'https://www.youtube.com', 'https://www.youtube.com', 1, 4),
(6, 'Twitch', 'twitch', 'https://www.twitch.tv', NULL, 1, 7),
(7, 'ESL', 'esl', 'https://play.eslgaming.com/player/', 'https://play.eslgaming.com/team/', 1, 8),
(8, 'ESEA', 'esea', 'https://play.esea.net/users/', 'https://play.esea.net/teams/', 1, 9),
(9, 'Instagram', 'instagram', 'https://www.instagram.com', NULL, 1, 5),
(10, 'FaceIt', 'faceit', 'https://www.faceit.com/en/players/', 'https://www.faceit.com/en/teams/', 1, 10),
(11, 'Steam', 'steam', 'https://steamcommunity.com/profiles/', NULL, 1, 11);

ALTER TABLE `ws_p40_user_socials_types` ADD PRIMARY KEY (`typeID`);
ALTER TABLE `ws_p40_user_socials_types` MODIFY `typeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Sponsor social networks
--

CREATE TABLE `ws_p40_sponsors_social_network` (
  `sponsorID` int(11) NOT NULL,
  `social_network_id` int(11) NOT NULL,
  `value` VARCHAR(255) NOT NULL
);

ALTER TABLE `ws_p40_sponsors_social_network` ADD UNIQUE KEY `unique_sponsor_social_network` (`sponsorID`,`social_network_id`);
ALTER TABLE `ws_p40_sponsors_social_network` ADD CONSTRAINT `FK_Sponsors_SC_SponsorID` FOREIGN KEY (`sponsorID`) REFERENCES `ws_p40_sponsors`(`sponsorID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `ws_p40_sponsors_social_network` ADD CONSTRAINT `FK_Sponsors_SC_SocialNetworkID` FOREIGN KEY (`social_network_id`) REFERENCES `ws_p40_user_socials_types`(`typeID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Other ForeignKeys
--
ALTER TABLE `ws_p40_events` ADD CONSTRAINT `FK_EventSquad_SquadID` FOREIGN KEY (`squadID`) REFERENCES `ws_p40_squads`(`squadID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- DONE :)
--

COMMIT;