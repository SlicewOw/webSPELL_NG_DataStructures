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
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner_small` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner_white` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner_small_white` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `displayed` int(11) NOT NULL DEFAULT 1,
  `subpage_only` int(11) NOT NULL DEFAULT 0,
  `frontpage_only` int(11) NOT NULL DEFAULT 0,
  `mainsponsor` int(11) NOT NULL DEFAULT 0,
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
  `registerdate` int(11) NOT NULL DEFAULT 0,
  `firstlogin` int(11) NOT NULL DEFAULT 0,
  `lastlogin` int(11) NOT NULL DEFAULT 0,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password_check` int(11) NOT NULL DEFAULT 0,
  `confirm` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `delete_confirm` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_hide` int(11) NOT NULL DEFAULT 1,
  `email_change` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_activate` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `accept_cookies` int(11) NOT NULL DEFAULT 0,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sex` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'u',
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'eu',
  `town` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `birthday` datetime DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `usertext` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `userpic` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `newsletter` int(11) NOT NULL DEFAULT 1,
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
  `mailonpm` int(11) NOT NULL DEFAULT 0,
  `userdescription` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'de',
  `activity` int(11) NOT NULL DEFAULT 0,
  `activityID` int(11) NOT NULL DEFAULT 0,
  `activityadminID` int(11) NOT NULL DEFAULT 0,
  `activitycupID` int(11) NOT NULL DEFAULT 0,
  `special_rank` int(11) NOT NULL DEFAULT 0,
  `date_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'd.m.Y',
  `time_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'H:i',
  `overview_redirect` int(11) NOT NULL DEFAULT 0,
  `hits` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ws_p40_user` ADD PRIMARY KEY (`userID`), ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `ws_p40_user` MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

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
  `facebook` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `youtube` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `steam` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
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

INSERT INTO `ws_p40_settings` (`settingID`, `page`, `cup_url`, `tv_url`, `forum_url`, `admin_url`, `image_url`, `static_url`, `doc_url`, `bot_url`, `download_url`, `system_user`, `title`, `hp_url`, `clanname`, `clantag`, `adminname`, `adminemail`, `facebook`, `twitter`, `youtube`, `steam`, `cronjob`, `news`, `newsarchiv`, `headlines`, `headlineschars`, `topnewschars`, `articles`, `latestarticles`, `articleschars`, `clanwars`, `results`, `upcoming`, `shoutbox`, `sball`, `sbrefresh`, `topics`, `posts`, `latesttopics`, `latesttopicchars`, `awards`, `demos`, `guestbook`, `feedback`, `messages`, `users`, `profilelast`, `topnewsID`, `sessionduration`, `closed`, `gb_info`, `imprint`, `picsize_l`, `picsize_h`, `pictures`, `publicadmin`, `thumbwidth`, `usergalleries`, `maxusergalleries`, `default_language`, `insertlinks`, `search_min_len`, `max_wrong_pw`, `captcha_math`, `captcha_bgcol`, `captcha_fontcol`, `captcha_type`, `captcha_noise`, `captcha_linenoise`, `autoresize`, `bancheck`, `gpicsize_l`, `gpicsize_h`, `max_lastpic`, `spam_check`, `detect_language`, `spamapikey`, `spamapihost`, `spammaxposts`, `spamapiblockerror`, `date_format`, `time_format`, `user_guestbook`, `sc_files`, `sc_demos`, `modRewrite`) VALUES
(1, 'gaming', 'http://localhost/', 'http://localhost/', 'http://localhost/', 'http://localhost/', 'http://localhost/', 'http://localhost/', 'http://localhost/', NULL, 'http://localhost/', NULL, 'myRisk Gaming e.V.', 'http://localhost/', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 450, 500, 12, 1, 130, 1, 1, 'de', 1, 3, 10, 2, '#FFFFFF', '#000000', 2, 100, 10, 2, 0, 60, 60, 10, 0, 0, '', '', 0, 0, 'd.m.y', 'G:i', 0, 1, 1, 1);

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

INSERT INTO `ws_p40_games` (`gameID`, `tag`, `short`, `name`, `active`) VALUES (1, 'cs', 'CS1.6', 'Counter-Strike', 1);

ALTER TABLE `ws_p40_games` ADD PRIMARY KEY (`gameID`), ADD UNIQUE KEY `tag` (`tag`);
ALTER TABLE `ws_p40_games` MODIFY `gameID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- DONE :)
--

COMMIT;