-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 21 okt 2019 kl 20:44
-- Serverversion: 10.4.6-MariaDB
-- PHP-version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `apex_db`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `entroute`
--

CREATE TABLE `entroute` (
  `routeId` int(11) NOT NULL,
  `routePath` varchar(255) NOT NULL,
  `routeLangId` int(11) NOT NULL,
  `routeLayoutKey` varchar(255) NOT NULL,
  `routeCreated` datetime NOT NULL,
  `routeUpdated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `entroute`
--

INSERT INTO `entroute` (`routeId`, `routePath`, `routeLangId`, `routeLayoutKey`, `routeCreated`, `routeUpdated`) VALUES
(1, '/apexInfoTest', 1, 'guestInfoTest', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `entroute`
--
ALTER TABLE `entroute`
  ADD PRIMARY KEY (`routeId`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `entroute`
--
ALTER TABLE `entroute`
  MODIFY `routeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
