-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 05. Sep 2025 um 07:41
-- Server-Version: 10.1.36-MariaDB
-- PHP-Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `wollladen`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bilder`
--

CREATE TABLE `bilder` (
  `id` int(11) NOT NULL,
  `bild_pfad` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wichtigkeit` tinyint(4) NOT NULL,
  `alt` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `bilder`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `marken`
--

CREATE TABLE `marken` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `beschreibung` longtext COLLATE utf8_unicode_ci,
  `logo_pfad` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `wichtigkeit` tinyint(4) NOT NULL,
  `reihenfolge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `marken`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `titel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `anfangsdatum` date NOT NULL,
  `enddatum` date NOT NULL,
  `wichtigkeit` tinyint(4) NOT NULL,
  `aktiv` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `news`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oeffnungszeiten`
--

CREATE TABLE `oeffnungszeiten` (
  `id` int(11) NOT NULL,
  `tag` int(1) NOT NULL,
  `offen` time NOT NULL,
  `zu` time NOT NULL,
  `pause_anfang` time NOT NULL,
  `pause_ende` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `oeffnungszeiten`
--

INSERT INTO `oeffnungszeiten` (`id`, `tag`, `offen`, `zu`, `pause_anfang`, `pause_ende`) VALUES
(1, 1, '09:30:00', '18:00:00', '12:30:00', '14:00:00'),
(2, 2, '09:30:00', '18:00:00', '12:30:00', '14:00:00'),
(3, 3, '09:30:00', '18:00:00', '12:30:00', '14:00:00'),
(4, 4, '09:30:00', '18:00:00', '12:30:00', '14:00:00'),
(5, 5, '09:30:00', '18:00:00', '12:30:00', '14:00:00'),
(6, 6, '09:30:00', '13:00:00', '00:00:00', '00:00:00'),
(7, 7, '00:00:00', '00:00:00', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `socials`
--

CREATE TABLE `socials` (
  `id` int(11) NOT NULL,
  `plattform` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wichtigkeit` tinyint(4) NOT NULL,
  `reihenfolge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `socials`
--

INSERT INTO `socials` (`id`, `plattform`, `url`, `wichtigkeit`, `reihenfolge`) VALUES
(1, 'instagram', 'noch offen', 1, 1),
(2, 'facebook', 'noch offen', 1, 2),
(3, 'youtube', 'noch offen', 0, 0),
(4, 'tiktok', 'noch offen', 0, 0),
(5, 'pinterest', 'noch offen', 0, 0),
(6, 'twitter', 'noch offen', 0, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bilder`
--
ALTER TABLE `bilder`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `marken`
--
ALTER TABLE `marken`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `oeffnungszeiten`
--
ALTER TABLE `oeffnungszeiten`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `socials`
--
ALTER TABLE `socials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bilder`
--
ALTER TABLE `bilder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `marken`
--
ALTER TABLE `marken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `oeffnungszeiten`
--
ALTER TABLE `oeffnungszeiten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `socials`
--
ALTER TABLE `socials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
