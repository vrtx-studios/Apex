-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Värd: localhost:3306
-- Tid vid skapande: 02 mars 2020 kl 09:46
-- Serverversion: 10.1.41-MariaDB-0+deb9u1
-- PHP-version: 7.0.33-14+0~20191218.25+debian9~1.gbpae1889

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `dronki_db`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `entLayout`
--

CREATE TABLE `entLayout` (
  `layoutId` int(10) NOT NULL,
  `layoutKey` varchar(255) NOT NULL,
  `layoutTitle` varchar(255) NOT NULL,
  `layoutModuleTemplate` varchar(255) NOT NULL,
  `layoutTemplate` varchar(255) NOT NULL,
  `layoutCreated` datetime NOT NULL,
  `layoutUpdated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `entLayout`
--

INSERT INTO `entLayout` (`layoutId`, `layoutKey`, `layoutTitle`, `layoutModuleTemplate`, `layoutTemplate`, `layoutCreated`, `layoutUpdated`) VALUES
(1, 'guestStartpage', 'Welcome', '', 'default', '2019-12-29 00:00:00', '0000-00-00 00:00:00'),
(2, 'adminLogin', 'Login', 'Dashboard', 'dashboard', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellstruktur `entLocale`
--

CREATE TABLE `entLocale` (
  `localeId` int(10) NOT NULL,
  `localeDomain` varchar(255) NOT NULL,
  `localePath` varchar(255) NOT NULL,
  `localeAutoload` enum('no','yes') NOT NULL DEFAULT 'yes',
  `localeCreated` datetime NOT NULL,
  `localeUpdated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `entLocale`
--

INSERT INTO `entLocale` (`localeId`, `localeDomain`, `localePath`, `localeAutoload`, `localeCreated`, `localeUpdated`) VALUES
(1, 'default', '/home/httpd/dronki/apex.dronki.tech/Config/../Locale', 'yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'comment', '/home/httpd/dronki/apex.dronki.tech/Config/../Modules/Comment/Locale', 'yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellstruktur `entNavigation`
--

CREATE TABLE `entNavigation` (
  `navigationId` int(11) NOT NULL,
  `navigationGroup` varchar(255) NOT NULL,
  `navigationParentId` int(11) NOT NULL,
  `navigationSort` int(255) NOT NULL,
  `navigationName` varchar(255) NOT NULL,
  `navigationClass` varchar(255) NOT NULL,
  `navigationHref` varchar(255) NOT NULL,
  `navigationBehavior` enum('_self','_blank','_parent','_top') NOT NULL,
  `navigationPrefixContent` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `entNavigation`
--

INSERT INTO `entNavigation` (`navigationId`, `navigationGroup`, `navigationParentId`, `navigationSort`, `navigationName`, `navigationClass`, `navigationHref`, `navigationBehavior`, `navigationPrefixContent`) VALUES
(0, 'admin', 0, 0, 'Dashboard', '', '/', '_self', '<i class=\"fa fa-home fa-2x\"></i>');

-- --------------------------------------------------------

--
-- Tabellstruktur `entRoute`
--

CREATE TABLE `entRoute` (
  `routeId` int(11) NOT NULL,
  `routePath` varchar(255) NOT NULL,
  `routeLangId` int(11) NOT NULL,
  `routeLayoutKey` varchar(255) NOT NULL,
  `routeCreated` datetime NOT NULL,
  `routeUpdated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `entRoute`
--

INSERT INTO `entRoute` (`routeId`, `routePath`, `routeLangId`, `routeLayoutKey`, `routeCreated`, `routeUpdated`) VALUES
(1, '/', 1, 'guestStartpage', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '/admin/login', 1, 'adminLogin', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellstruktur `entRouteToObject`
--

CREATE TABLE `entRouteToObject` (
  `routeId` int(10) NOT NULL,
  `objectParent` varchar(255) NOT NULL,
  `objectId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `entRouteToObject`
--

INSERT INTO `entRouteToObject` (`routeId`, `objectParent`, `objectId`) VALUES
(1, 'testObject', 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `entUser`
--

CREATE TABLE `entUser` (
  `userId` int(10) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userPass` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userOrigin` enum('onSite','offSite','google') NOT NULL,
  `userLastLogin` datetime NOT NULL,
  `userLastLoginIP` varchar(255) NOT NULL,
  `userCreated` datetime NOT NULL,
  `userUpdated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `entUser`
--

INSERT INTO `entUser` (`userId`, `userName`, `userPass`, `userEmail`, `userOrigin`, `userLastLogin`, `userLastLoginIP`, `userCreated`, `userUpdated`) VALUES
(1, 'superApex', '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq', 'apex@dronki.tech', 'onSite', '2019-12-29 16:50:11', '1313327443', '2019-12-29 00:00:00', '0000-00-00 00:00:00'),
(6, 'admin', '$2y$10$BXvcmGYgfLzpb8Tb/86G2ueVIiuF/jfVoUsNAYv1VLs7eU/3jvUle', 'test@dronki.tech', 'onSite', '0000-00-00 00:00:00', '', '2019-12-29 17:06:15', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellstruktur `entUserGroups`
--

CREATE TABLE `entUserGroups` (
  `groupId` int(10) NOT NULL,
  `groupName` varchar(255) NOT NULL,
  `groupDescription` text NOT NULL,
  `groupCreated` datetime NOT NULL,
  `groupUpdated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `entUserGroups`
--

INSERT INTO `entUserGroups` (`groupId`, `groupName`, `groupDescription`, `groupCreated`, `groupUpdated`) VALUES
(1, 'guest', 'The default group for guests, should never be used.', '2019-12-29 15:55:00', '0000-00-00 00:00:00'),
(2, 'user', 'Standard user, doesn\'t permit access to backend.', '2019-12-29 15:57:00', '0000-00-00 00:00:00'),
(3, 'contributor', 'Permits access to some parts of the backend.', '2019-12-29 16:00:00', '0000-00-00 00:00:00'),
(4, 'admin', 'Admin-level access, permits access to everything.', '2019-12-29 16:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellstruktur `entUserToGroup`
--

CREATE TABLE `entUserToGroup` (
  `userId` int(10) NOT NULL,
  `groupId` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `entUserToGroup`
--

INSERT INTO `entUserToGroup` (`userId`, `groupId`) VALUES
(1, 4);

-- --------------------------------------------------------

--
-- Tabellstruktur `entViewToLayout`
--

CREATE TABLE `entViewToLayout` (
  `layoutId` int(10) NOT NULL,
  `viewModule` varchar(255) NOT NULL,
  `viewCallback` enum('no','yes') NOT NULL,
  `viewFile` varchar(255) NOT NULL,
  `viewOrder` int(10) NOT NULL,
  `relationCreated` datetime NOT NULL,
  `relationUpdated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `entViewToLayout`
--

INSERT INTO `entViewToLayout` (`layoutId`, `viewModule`, `viewCallback`, `viewFile`, `viewOrder`, `relationCreated`, `relationUpdated`) VALUES
(1, 'InfoContent', 'no', 'showTestView', 0, '2019-12-29 00:00:00', '0000-00-00 00:00:00'),
(2, 'Dashboard', 'no', 'formLogin', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `entLayout`
--
ALTER TABLE `entLayout`
  ADD PRIMARY KEY (`layoutId`);

--
-- Index för tabell `entLocale`
--
ALTER TABLE `entLocale`
  ADD PRIMARY KEY (`localeId`);

--
-- Index för tabell `entNavigation`
--
ALTER TABLE `entNavigation`
  ADD PRIMARY KEY (`navigationId`);

--
-- Index för tabell `entRoute`
--
ALTER TABLE `entRoute`
  ADD PRIMARY KEY (`routeId`);

--
-- Index för tabell `entUser`
--
ALTER TABLE `entUser`
  ADD PRIMARY KEY (`userId`);

--
-- Index för tabell `entUserGroups`
--
ALTER TABLE `entUserGroups`
  ADD PRIMARY KEY (`groupId`);

--
-- Index för tabell `entUserToGroup`
--
ALTER TABLE `entUserToGroup`
  ADD PRIMARY KEY (`userId`);

--
-- Index för tabell `entViewToLayout`
--
ALTER TABLE `entViewToLayout`
  ADD PRIMARY KEY (`layoutId`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `entLayout`
--
ALTER TABLE `entLayout`
  MODIFY `layoutId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `entLocale`
--
ALTER TABLE `entLocale`
  MODIFY `localeId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `entRoute`
--
ALTER TABLE `entRoute`
  MODIFY `routeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `entUser`
--
ALTER TABLE `entUser`
  MODIFY `userId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT för tabell `entUserGroups`
--
ALTER TABLE `entUserGroups`
  MODIFY `groupId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
