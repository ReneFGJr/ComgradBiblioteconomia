-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 13-Ago-2021 às 23:47
-- Versão do servidor: 5.7.31
-- versão do PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `comgradbib`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplinas`
--

DROP TABLE IF EXISTS `disciplinas`;
CREATE TABLE IF NOT EXISTS `disciplinas` (
  `id_d` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `d_nome` char(150) NOT NULL,
  `d_codigo` char(10) NOT NULL,
  `d_etapa` int(11) NOT NULL,
  `d_professor` int(11) NOT NULL,
  `d_dt_ini` date NOT NULL,
  `d_dt_fim` date NOT NULL,
  `d_ativo` int(11) NOT NULL,
  `d_dia` int(11) NOT NULL,
  `d_hora` int(11) NOT NULL,
  `d_creditos` int(11) NOT NULL DEFAULT '0',
  `d_ementa` text NOT NULL,
  UNIQUE KEY `id_d` (`id_d`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `disciplinas`
--

INSERT INTO `disciplinas` (`id_d`, `d_nome`, `d_codigo`, `d_etapa`, `d_professor`, `d_dt_ini`, `d_dt_fim`, `d_ativo`, `d_dia`, `d_hora`, `d_creditos`, `d_ementa`) VALUES
(3, 'Biblioteconomia e Interdisciplinaridade', 'BIBAD001', 1, 0, '2021-04-01', '2021-04-01', 1, 1, 19, 2, ''),
(4, 'Biblioteconomia e Sociedade', 'BIBAD002', 1, 1, '2021-04-01', '2021-04-01', 1, 1, 1, 0, ''),
(5, 'Introdução à Educação a Distância', 'BIBAD003', 1, 0, '2021-04-01', '2021-04-01', 1, 1, 19, 2, ''),
(6, 'Introdução à Filosofia', 'BIBAD004', 1, 1, '2021-04-01', '2021-04-01', 1, 1, 1, 0, ''),
(7, 'Introdução às Tecnologias de Informação e Comunicação', 'BIBAD005', 1, 0, '2021-04-01', '2021-04-01', 1, 1, 19, 2, ''),
(8, 'Língua Portuguesa', 'BIBAD006', 1, 1, '2021-04-01', '2021-04-01', 1, 1, 1, 0, ''),
(9, 'Sociologia Geral', 'BIBAD007', 1, 0, '2021-04-01', '2021-04-01', 1, 1, 19, 2, ''),
(10, 'Cultura e Memória Social', 'BIBAD008', 1, 1, '2021-04-01', '2021-04-01', 1, 1, 1, 0, ''),
(11, 'BIBAD009', 'BIBAD009', 1, 0, '2021-04-01', '2021-04-01', 1, 1, 19, 2, ''),
(12, 'BIBAD010', 'BIBAD010', 1, 1, '2021-04-01', '2021-04-01', 1, 1, 1, 0, ''),
(13, 'BIBAD011', 'BIBAD011', 1, 0, '2021-04-01', '2021-04-01', 1, 1, 19, 2, ''),
(14, 'BIBAD012', 'BIBAD012', 1, 1, '2021-04-01', '2021-04-01', 1, 1, 1, 0, ''),
(15, 'BIBAD013', 'BIBAD013', 1, 0, '2021-04-01', '2021-04-01', 1, 1, 19, 2, ''),
(16, 'BIBAD014', 'BIBAD014', 1, 1, '2021-04-01', '2021-04-01', 1, 1, 1, 0, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
