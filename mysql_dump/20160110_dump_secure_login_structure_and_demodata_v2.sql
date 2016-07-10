-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Erstellungszeit: 10. Jan 2016 um 07:42
-- Server-Version: 5.6.26
-- PHP-Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `secure_login_v2`
--

DROP DATABASE IF EXISTS`secure_login_v2` ;
CREATE DATABASE `secure_login_v2` ;
GRANT SELECT, INSERT, UPDATE ON `secure_login_v2`.* TO 'secure_login'@'localhost';
USE secure_login_v2;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login_attempts`
--

CREATE TABLE `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `login_attempts`
--

INSERT INTO `login_attempts` (`user_id`, `time`) VALUES
(1, '1450595973'),
(1, '1450601198'),
(1, '1450604186'),
(1, '1450679853'),
(1, '1450693209'),
(1, '1450855344'),
(1, '1450901071'),
(1, '1450951542'),
(1, '1450951564'),
(1, '1450951585'),
(1, '1450951623'),
(1, '1451109247'),
(1, '1451109302'),
(1, '1451109324'),
(1, '1451109412'),
(1, '1451124212'),
(1, '1451897707');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  `email_verified` int(11) DEFAULT NULL,
  `deprecated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `members`
--

INSERT INTO `members` (`id`, `firstname`, `lastname`, `email`, `password`, `salt`, `email_verified`, `deprecated`) VALUES
(1, '', 'test_user', 'developer@bpmspace.org', '00807432eae173f652f2064bdca1b61b290b52d40e429a7d295d76a71084aa96c0233b82f1feac45529e0726559645acaed6f3ae58a286b9f075916ebf66cacc', 'f9aab579fc1b41ed0c44fe4ecdbfcdb4cb99b9023abb241a6db833288f4eea3c02f76e0d35204a8695077dcf81932aa59006423976224be0390395bae152d4ef', 1, NULL),
(2, '', 'admin_user', 'admin@bpmspace.org', '00807432eae173f652f2064bdca1b61b290b52d40e429a7d295d76a71084aa96c0233b82f1feac45529e0726559645acaed6f3ae58a286b9f075916ebf66cacc', 'f9aab579fc1b41ed0c44fe4ecdbfcdb4cb99b9023abb241a6db833288f4eea3c02f76e0d35204a8695077dcf81932aa59006423976224be0390395bae152d4ef', 1, NULL),
(3, '', 'test_user_NV', 'test_user_NOTVERIFIED@bpmspace.org', '00807432eae173f652f2064bdca1b61b290b52d40e429a7d295d76a71084aa96c0233b82f1feac45529e0726559645acaed6f3ae58a286b9f075916ebf66cacc', 'f9aab579fc1b41ed0c44fe4ecdbfcdb4cb99b9023abb241a6db833288f4eea3c02f76e0d35204a8695077dcf81932aa59006423976224be0390395bae152d4ef', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `members_role`
--

CREATE TABLE `members_role` (
  `members_role_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `members_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `members_role`
--

INSERT INTO `members_role` (`members_role_id`, `role_id`, `members_id`) VALUES
(1, 1, 2),
(2, 2, 2),
(3, 2, 1),
(4, 2, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(45) DEFAULT NULL,
  `deprecated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `deprecated`) VALUES
(1, 'admin', NULL),
(2, 'user', NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD KEY `members_id_fk_2_idx` (`user_id`);

--
-- Indizes für die Tabelle `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `members_role`
--
ALTER TABLE `members_role`
  ADD PRIMARY KEY (`members_role_id`),
  ADD UNIQUE KEY `members_role_id_UNIQUE` (`members_role_id`),
  ADD KEY `members_id_fk_1_idx` (`members_id`),
  ADD KEY `roles_id_fk_1_idx` (`role_id`);

--
-- Indizes für die Tabelle `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `members_role`
--
ALTER TABLE `members_role`
  MODIFY `members_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `members_id_fk_2` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `members_role`
--
ALTER TABLE `members_role`
  ADD CONSTRAINT `members_id_fk_1` FOREIGN KEY (`members_id`) REFERENCES `members` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `roles_id_fk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
