-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 06, 2017 at 03:14 AM
-- Server version: 5.6.20-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `redd`
--

-- --------------------------------------------------------

--
-- Table structure for table `person_graduacao`
--

CREATE TABLE IF NOT EXISTS `person_graduacao` (
`id_g` bigint(20) unsigned NOT NULL,
  `g_curso_1` int(11) NOT NULL,
  `g_curso_2` int(11) NOT NULL,
  `g_ano_em` int(11) NOT NULL,
  `g_ingresso` char(6) NOT NULL,
  `g_ingresso_sem` char(1) NOT NULL,
  `g_diplomacao` char(6) NOT NULL,
  `g_statis` int(11) NOT NULL DEFAULT '1',
  `g_person` int(11) NOT NULL,
  `g_afastado` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `person_graduacao`
--
ALTER TABLE `person_graduacao`
 ADD UNIQUE KEY `id_g` (`id_g`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `person_graduacao`
--
ALTER TABLE `person_graduacao`
MODIFY `id_g` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
