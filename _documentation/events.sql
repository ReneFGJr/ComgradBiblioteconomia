-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 04, 2017 at 10:28 AM
-- Server version: 5.6.20-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `comgradbib`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`id_e` bigint(20) unsigned NOT NULL,
  `e_name` char(200) NOT NULL,
  `e_data_i` date NOT NULL,
  `e_data_f` date NOT NULL,
  `e_status` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id_e`, `e_name`, `e_data_i`, `e_data_f`, `e_status`) VALUES
(1, '70 anos do Curso de Biblioteconomia da UFRGS', '2017-12-05', '2017-12-05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events_inscritos`
--

CREATE TABLE IF NOT EXISTS `events_inscritos` (
`id_i` bigint(20) unsigned NOT NULL,
  `i_evento` int(11) NOT NULL,
  `i_date_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `i_user` int(11) NOT NULL,
  `i_status` int(11) NOT NULL,
  `i_date_out` timestamp NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `events_inscritos`
--

INSERT INTO `events_inscritos` (`id_i`, `i_evento`, `i_date_in`, `i_user`, `i_status`, `i_date_out`) VALUES
(1, 1, '2017-12-04 10:14:50', 1, 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `events_names`
--

CREATE TABLE IF NOT EXISTS `events_names` (
`id_n` bigint(20) unsigned NOT NULL,
  `n_nome` char(100) NOT NULL,
  `n_cracha` char(15) NOT NULL,
  `n_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `n_email` char(100) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `events_names`
--

INSERT INTO `events_names` (`id_n`, `n_nome`, `n_cracha`, `n_created`, `n_email`) VALUES
(1, 'ADEMIR SOUZA DA SILVA', '00229511', '2017-12-04 08:14:50', 'ademirsouza2525@hotmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD UNIQUE KEY `id_e` (`id_e`);

--
-- Indexes for table `events_inscritos`
--
ALTER TABLE `events_inscritos`
 ADD UNIQUE KEY `id_i` (`id_i`);

--
-- Indexes for table `events_names`
--
ALTER TABLE `events_names`
 ADD UNIQUE KEY `id_n` (`id_n`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `id_e` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `events_inscritos`
--
ALTER TABLE `events_inscritos`
MODIFY `id_i` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `events_names`
--
ALTER TABLE `events_names`
MODIFY `id_n` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
