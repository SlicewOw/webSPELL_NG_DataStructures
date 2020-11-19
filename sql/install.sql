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
-- DONE :)
--

COMMIT;