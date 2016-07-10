-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Erstellungszeit: 10. Jan 2016 um 07:36
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
GRANT SELECT, INSERT, UPDATE ON `secure_login`.* TO 'secure_login'@'localhost';
USE secure_login_v2;


--
-- Tabellenstruktur für Tabelle `login_attempts`
--

CREATE TABLE `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `members_role`
--

CREATE TABLE `members_role` (
  `members_role_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `members_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
