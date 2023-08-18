-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 18-Ago-2023 às 11:03
-- Versão do servidor: 10.2.44-MariaDB-cll-lve
-- versão do PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `zorkpadel`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `Chat`
--

CREATE TABLE `Chat` (
  `id` int(11) NOT NULL,
  `id_liga` int(11) DEFAULT NULL,
  `id_utilizador` int(11) DEFAULT NULL,
  `mensagem` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_hora` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Convites_Pendentes`
--

CREATE TABLE `Convites_Pendentes` (
  `id` int(11) NOT NULL,
  `id_liga` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `codigo_convite` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Jogadores_Jogo`
--

CREATE TABLE `Jogadores_Jogo` (
  `id_utilizador` int(11) NOT NULL,
  `id_jogo` int(11) NOT NULL,
  `pontuacao` int(11) DEFAULT NULL,
  `equipa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `Jogadores_Jogo`
--

INSERT INTO `Jogadores_Jogo` (`id_utilizador`, `id_jogo`, `pontuacao`, `equipa`) VALUES
(1, 3, NULL, 1),
(1, 12, NULL, 1),
(1, 13, NULL, 1),
(1, 15, NULL, NULL),
(6, 3, NULL, 1),
(6, 4, NULL, NULL),
(6, 7, NULL, 2),
(6, 9, NULL, NULL),
(6, 10, NULL, NULL),
(7, 3, NULL, 2),
(8, 3, NULL, 2),
(9, 5, NULL, 1),
(9, 6, NULL, 1),
(9, 7, NULL, 1),
(9, 9, NULL, NULL),
(9, 11, NULL, 1),
(9, 14, NULL, 1),
(10, 5, NULL, 1),
(10, 6, NULL, 2),
(10, 7, NULL, 2),
(10, 11, NULL, 2),
(10, 14, NULL, 2),
(11, 5, NULL, 2),
(11, 6, NULL, 2),
(11, 11, NULL, 2),
(11, 14, NULL, 2),
(12, 5, NULL, 2),
(12, 6, NULL, 1),
(16, 7, NULL, 1),
(17, 11, NULL, 1),
(17, 14, NULL, 1),
(19, 12, NULL, 1),
(19, 13, NULL, 2),
(20, 12, NULL, 2),
(20, 13, NULL, 2),
(21, 12, NULL, 2),
(21, 13, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `Jogos`
--

CREATE TABLE `Jogos` (
  `id` int(11) NOT NULL,
  `id_liga` int(11) DEFAULT NULL,
  `local` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_hora` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `team1_score` int(11) DEFAULT 0,
  `team2_score` int(11) DEFAULT 0,
  `criador` int(11) DEFAULT NULL,
  `fim_jogo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `Jogos`
--

INSERT INTO `Jogos` (`id`, `id_liga`, `local`, `data_hora`, `status`, `team1_score`, `team2_score`, `criador`, `fim_jogo`) VALUES
(1, 1, 'Amorim', '2023-06-30 16:00:00', 1, 0, 0, NULL, NULL),
(3, 3, 'Porto', '2023-07-05 16:00:00', 2, 2, 1, NULL, NULL),
(4, 1, 'amorim', '2023-07-31 15:00:00', 1, 0, 0, NULL, NULL),
(5, 4, 'Porto', '2023-07-01 16:00:00', 0, 2, 1, NULL, NULL),
(6, 4, 'caxinas', '2023-08-01 18:00:00', 0, 2, 1, NULL, NULL),
(7, 5, 'povoa', '2023-07-11 15:15:00', 0, 12, 23, NULL, NULL),
(9, 4, 'Amorim', '2023-07-08 16:08:00', 1, 0, 0, NULL, NULL),
(10, 5, 'dsad', '2322-03-23 23:33:00', 1, 0, 0, NULL, NULL),
(11, 8, 'Monserrate', '2023-07-06 22:00:00', 2, 0, 0, 10, NULL),
(12, 9, 'Amorim', '2023-07-08 16:00:00', 0, 0, 2, NULL, 1),
(13, 9, 'Amorim', '2023-07-08 18:00:00', 0, 1, 2, NULL, 1),
(14, 15, 'Povoa de Varzim', '2023-08-04 16:00:00', 0, 2, 1, 10, 1),
(15, 9, 'Sergio tachini', '2023-07-25 16:30:00', 1, 0, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `Ligas`
--

CREATE TABLE `Ligas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_criador` int(11) DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `codigo_convite` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `Ligas`
--

INSERT INTO `Ligas` (`id`, `nome`, `descricao`, `id_criador`, `data_criacao`, `codigo_convite`) VALUES
(1, 'krokets', 'Liga dos kroketoes', 6, '2023-06-25 21:49:37', 'BBBBB'),
(3, 'Snakes', 'Descrição desta liga de padel', 6, '2023-06-25 23:30:14', 'AAAAA'),
(4, 'Thunders', 'Trovoes', 6, '2023-06-27 20:02:36', 'XVX2Z'),
(5, 'ada', 'ada', 16, '2023-07-01 09:08:44', '9XAA2'),
(7, '\'\'\'', '\'\'\'\'', 18, '2023-07-03 20:57:18', 'KQRQH'),
(8, 'Liga de Testes', 'Para fazer Testes\r\n ', 17, '2023-07-06 12:14:20', '0F1NZ'),
(9, 'Padeleiros', 'Liga dos Padeleiros ', 1, '2023-07-06 21:23:53', 'EW2I2'),
(12, 'Cenas League', ' A liga das cenas!', 22, '2023-07-08 13:36:49', '89U0N'),
(13, 'hjfv', ' hjfchj', 10, '2023-07-09 16:48:45', 'I5AN8'),
(14, 'Testes2', 'testes2\r\n ', 9, '2023-07-09 19:03:48', '06SGA'),
(15, 'Testes3', 'testes3\r\n ', 9, '2023-07-09 19:06:19', 'Y3SFE');

-- --------------------------------------------------------

--
-- Estrutura da tabela `Membros_Liga`
--

CREATE TABLE `Membros_Liga` (
  `id_utilizador` int(11) NOT NULL,
  `id_liga` int(11) NOT NULL,
  `data_admissao` timestamp NULL DEFAULT current_timestamp(),
  `admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `Membros_Liga`
--

INSERT INTO `Membros_Liga` (`id_utilizador`, `id_liga`, `data_admissao`, `admin`) VALUES
(1, 3, '2023-06-26 21:32:02', 0),
(1, 9, '2023-07-06 22:23:53', 1),
(6, 1, '2023-06-25 21:51:01', 6),
(6, 3, '2023-06-25 22:30:14', 1),
(6, 4, '2023-06-27 19:02:36', 1),
(6, 5, '2023-07-01 10:15:36', 0),
(7, 3, '2023-06-26 21:32:02', 0),
(8, 3, '2023-06-26 21:32:02', 0),
(9, 4, '2023-06-27 20:56:59', 0),
(9, 5, '2023-07-01 10:16:01', 0),
(9, 8, '2023-07-06 13:15:23', 0),
(9, 14, '2023-07-09 20:03:48', 1),
(9, 15, '2023-07-09 20:06:19', 1),
(10, 4, '2023-06-27 20:58:25', 0),
(10, 5, '2023-07-01 10:16:26', 0),
(10, 8, '2023-07-06 13:22:37', 0),
(10, 13, '2023-07-09 17:48:45', 1),
(10, 15, '2023-07-09 20:06:46', 0),
(11, 4, '2023-06-27 20:58:57', 0),
(11, 8, '2023-07-06 13:22:54', 0),
(11, 15, '2023-07-09 20:07:26', 0),
(12, 4, '2023-06-27 21:09:16', 0),
(14, 4, '2023-07-01 09:47:33', 0),
(15, 4, '2023-07-01 10:04:53', 0),
(16, 5, '2023-07-01 10:08:44', 1),
(17, 8, '2023-07-06 13:14:20', 1),
(17, 15, '2023-07-09 20:07:55', 0),
(18, 7, '2023-07-03 21:57:18', 1),
(19, 9, '2023-07-06 22:51:15', 0),
(20, 9, '2023-07-07 14:41:50', 0),
(21, 9, '2023-07-07 17:24:02', 0),
(22, 12, '2023-07-08 14:36:49', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `Mensagens`
--

CREATE TABLE `Mensagens` (
  `id` int(11) NOT NULL,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `content`, `link`, `created_at`, `read_at`) VALUES
(1, 9, 'Hugo criou um novo jogo na liga Liga de Testes. Vêm participar!', '/game?id=11', '2023-07-06 13:40:13', '0000-00-00 00:00:00'),
(2, 10, 'Hugo criou um novo jogo na liga Liga de Testes. Vêm participar!', '/game?id=11', '2023-07-06 13:40:13', '0000-00-00 00:00:00'),
(3, 11, 'Hugo criou um novo jogo na liga Liga de Testes. Vêm participar!', '/game?id=11', '2023-07-06 13:40:13', '0000-00-00 00:00:00'),
(4, 19, 'zorkpt criou um novo jogo na liga Padeleiros. Vêm participar!', '/game?id=13', '2023-07-08 17:24:31', '0000-00-00 00:00:00'),
(5, 20, 'zorkpt criou um novo jogo na liga Padeleiros. Vêm participar!', '/game?id=13', '2023-07-08 17:24:31', '0000-00-00 00:00:00'),
(6, 21, 'zorkpt criou um novo jogo na liga Padeleiros. Vêm participar!', '/game?id=13', '2023-07-08 17:24:31', '0000-00-00 00:00:00'),
(7, 9, 'user2 criou um novo jogo na liga Testes3. Vêm participar!', '/game?id=14', '2023-07-09 20:07:10', '0000-00-00 00:00:00'),
(8, 19, 'zorkpt criou um novo jogo na liga Padeleiros. Vêm participar!', '/game?id=15', '2023-07-25 15:24:13', '0000-00-00 00:00:00'),
(9, 20, 'zorkpt criou um novo jogo na liga Padeleiros. Vêm participar!', '/game?id=15', '2023-07-25 15:24:13', '0000-00-00 00:00:00'),
(10, 21, 'zorkpt criou um novo jogo na liga Padeleiros. Vêm participar!', '/game?id=15', '2023-07-25 15:24:13', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `Ranking`
--

CREATE TABLE `Ranking` (
  `id_utilizador` int(11) NOT NULL,
  `id_liga` int(11) NOT NULL,
  `pontos` int(11) DEFAULT 0,
  `jogos_jogados` int(11) DEFAULT 0,
  `jogos_ganhos` int(11) DEFAULT 0,
  `jogos_perdidos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `Ranking`
--

INSERT INTO `Ranking` (`id_utilizador`, `id_liga`, `pontos`, `jogos_jogados`, `jogos_ganhos`, `jogos_perdidos`) VALUES
(1, 9, 0, 2, 0, 2),
(6, 5, 1, 1, 1, 0),
(9, 4, 7, 8, 7, 1),
(9, 5, 0, 1, 0, 1),
(9, 8, 0, 0, 0, 0),
(9, 15, 2, 2, 2, 0),
(10, 4, 6, 7, 6, 1),
(10, 5, 1, 1, 1, 0),
(10, 8, 0, 0, 0, 0),
(10, 15, 0, 1, 0, 1),
(11, 4, 1, 7, 1, 6),
(11, 8, 0, 0, 0, 0),
(11, 15, 0, 1, 0, 1),
(12, 4, 1, 7, 1, 6),
(14, 4, 0, 0, 0, 0),
(15, 4, 0, 0, 0, 0),
(17, 8, 0, 0, 0, 0),
(17, 15, 2, 2, 2, 0),
(19, 9, 1, 2, 1, 1),
(20, 9, 2, 2, 2, 0),
(21, 9, 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `Sets`
--

CREATE TABLE `Sets` (
  `id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `sequence_number` int(11) DEFAULT NULL,
  `team1_score` int(11) DEFAULT 0,
  `team2_score` int(11) DEFAULT 0,
  `winner` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `Sets`
--

INSERT INTO `Sets` (`id`, `game_id`, `sequence_number`, `team1_score`, `team2_score`, `winner`) VALUES
(1, 13, 1, 6, 3, 0),
(3, 13, 2, 2, 6, 0),
(4, 13, 3, 1, 6, 1),
(5, 14, 1, 6, 2, 0),
(6, 14, 2, 4, 6, 0),
(7, 14, 3, 6, 4, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `Utilizadores`
--

CREATE TABLE `Utilizadores` (
  `id` int(11) NOT NULL,
  `nome_utilizador` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_registo` timestamp NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passwordResetToken` varchar(130) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passwordResetExpires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `Utilizadores`
--

INSERT INTO `Utilizadores` (`id`, `nome_utilizador`, `email`, `password_hash`, `data_registo`, `avatar`, `passwordResetToken`, `passwordResetExpires`) VALUES
(1, 'zorkpt', 'hugopocas@gmail.com', '$2y$10$IOexb.BFGSrgfL/jkWv53utEXtg6B9Tjbci8HDskT5CamSJcig49i', '2023-06-23 17:06:41', '/uploads/64a73eead2be17.80003150.png', NULL, NULL),
(6, 'admin', 'hugopocas@gmail.comddd', '$2y$10$0P1YRUZfBejv7FNvlzRpnOBH/sN8FCDI.Um.DYHTNRJDuGw3yV5G6', '2023-06-25 21:42:49', '/uploads/default.png', NULL, NULL),
(7, 'karapodre', 'dasda@dasd.dasd', 'dasdasd', '2023-06-25 21:42:49', '/uploads/default.png', NULL, NULL),
(8, 'Joao', 'joao@dasd.dd', 'dasd', '2023-06-25 21:42:49', '/uploads/default.png', NULL, NULL),
(9, 'user1', 'user@sada.dsad', '$2y$10$ru5cR16gtq7sRcrXRlrzYuBTROv2h8.TEL7ugjn6Y2qr3Gc47ghK6', '2023-06-27 21:56:36', '/uploads/default.png', NULL, NULL),
(10, 'user2', 'sda@dasd.dsad', '$2y$10$5ujTwBI1b5x3sMx3YfEguOZvqStxLItuC6rUQ7sL9.c.MePBJkjr.', '2023-06-27 21:58:07', '/uploads/64a01303202856.30748921.png', NULL, NULL),
(11, 'user3', 'sad@sadasd.das', '$2y$10$aDxdao/G1fYVHlIIBvj2fuGkwaPwum9WPaE5MIBl/vA.xvvjQVjka', '2023-06-27 21:58:48', '/uploads/64a6c072b9f600.88818936.jpg', NULL, NULL),
(12, 'user5', 'usd@dasd.dd', '$2y$10$7pc7e5LZNG/yx8MTOB7WgedNO5ecEI8hUwSTGTgvSmdC3UsvPeCNm', '2023-06-27 22:08:58', '/uploads/default.png', NULL, NULL),
(13, 'zork', 'dasd@dasd.dasd', '$2y$10$iHi.rW3D3FZ8yMlV.DPjWeuTVe5rfTxYI9834Wnc9fr3TnI.hmsXm', '2023-07-01 08:46:35', '/uploads/default.png', NULL, NULL),
(14, 'zoork', 'dddd@dsad.dasd', '$2y$10$P1oeHtg8iI2XYkvdBfasdasdk9sOcgUW/Cx2G1D6Bx77muTc5xIsJP7kLpS', '2023-07-01 08:47:06', '/uploads/default.png', NULL, NULL),
(15, 'caramelo', 'dasdsa@dasd.dasd', '$2y$10$qZggw0m6dMMxasdasdsadEwZf3xcBc.UHHirbN7OB7a7z6KLlJsPtOGDvGKkQ2', '2023-07-01 08:57:25', '/uploads/649ff8851d6903.74392452.png', NULL, NULL),
(16, 'garuna', 'dasdsssssss@gmail.com', '$2y$10$jlKRFc0fMgMhasdsadlFnVkZ3CJehVTWky2o1ID/oxI1rZ6Fys5kLOCg402', '2023-07-01 09:08:21', '/uploads/default.png', NULL, NULL),
(17, 'Hugo', 'dasdsdsadsad@Outlook.pt', '$2y$10$C1hhy3BGTn32bZjvuXldsadsadsa7A.vpdInN7hICjjXWmIM2nbBB2V6IW1cbW', '2023-07-01 16:39:27', '/uploads/default.png', NULL, NULL),
(18, '\'\'\'', 'admin@example.com', '$2y$10$LYNGZ8ez2spkTBinhtEWadsgOvySdyyRm50YVSkzK8OgzDTpyerVL0om', '2023-07-03 20:53:22', '/uploads/default.png', NULL, NULL),
(19, 'Mr.Barroso', 'pasdasdasds@hotmail.com', '$2y$10$zvj22F71sdadsa6MfZGmeU1v6jB.hTalHbU6ru0nZGqOD.usiprnfloex2i', '2023-07-06 21:50:38', '/uploads/default.png', NULL, NULL),
(20, 'Paulo Granja ', 'mdasdasdsad@hotmail.com', '$2y$10$dsaDzdsasdadsarCm.KvOEbmNxPEPvbyYOFxWeGV1qs1cHodQ/jiTjo32YXiUMtRm', '2023-07-06 21:56:03', '/uploads/64a9f98b6f84a3.49963623.jpg', NULL, NULL),
(21, 'AngeloP', 'Angdasdsad@hotmail.com', '$2y$10$2xhP3yUOmNqWdsadsdasLio20UazGsadasd.ubAWpVS6IGSlrChuTEPoSj5MVwivi2u', '2023-07-07 16:23:24', '/uploads/64a8d1b5f1a971.20497384.jpg', NULL, NULL),
(22, 'Teste', 'peteg50748@iturchia.com', '$2y$10$0z.nicuoqhFdadsadsasdas7m9gx4GFb3..bTQVRtmHhVD949i92OSzpYI.IT1YTK', '2023-07-08 13:33:38', '/uploads/default.png', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `Chat`
--
ALTER TABLE `Chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_liga` (`id_liga`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `Convites_Pendentes`
--
ALTER TABLE `Convites_Pendentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_convite` (`codigo_convite`);

--
-- Índices para tabela `Jogadores_Jogo`
--
ALTER TABLE `Jogadores_Jogo`
  ADD PRIMARY KEY (`id_utilizador`,`id_jogo`),
  ADD KEY `id_jogo` (`id_jogo`);

--
-- Índices para tabela `Jogos`
--
ALTER TABLE `Jogos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_liga` (`id_liga`);

--
-- Índices para tabela `Ligas`
--
ALTER TABLE `Ligas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_criador` (`id_criador`);

--
-- Índices para tabela `Membros_Liga`
--
ALTER TABLE `Membros_Liga`
  ADD PRIMARY KEY (`id_utilizador`,`id_liga`),
  ADD KEY `id_liga` (`id_liga`);

--
-- Índices para tabela `Mensagens`
--
ALTER TABLE `Mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_remetente` (`id_remetente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Índices para tabela `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `Ranking`
--
ALTER TABLE `Ranking`
  ADD PRIMARY KEY (`id_utilizador`,`id_liga`),
  ADD KEY `id_liga` (`id_liga`);

--
-- Índices para tabela `Sets`
--
ALTER TABLE `Sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Índices para tabela `Utilizadores`
--
ALTER TABLE `Utilizadores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `Chat`
--
ALTER TABLE `Chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Convites_Pendentes`
--
ALTER TABLE `Convites_Pendentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `Jogos`
--
ALTER TABLE `Jogos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `Ligas`
--
ALTER TABLE `Ligas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `Mensagens`
--
ALTER TABLE `Mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `Sets`
--
ALTER TABLE `Sets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `Utilizadores`
--
ALTER TABLE `Utilizadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `Chat`
--
ALTER TABLE `Chat`
  ADD CONSTRAINT `Chat_ibfk_1` FOREIGN KEY (`id_liga`) REFERENCES `Ligas` (`id`),
  ADD CONSTRAINT `Chat_ibfk_2` FOREIGN KEY (`id_utilizador`) REFERENCES `Utilizadores` (`id`);

--
-- Limitadores para a tabela `Jogadores_Jogo`
--
ALTER TABLE `Jogadores_Jogo`
  ADD CONSTRAINT `Jogadores_Jogo_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `Utilizadores` (`id`),
  ADD CONSTRAINT `Jogadores_Jogo_ibfk_2` FOREIGN KEY (`id_jogo`) REFERENCES `Jogos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `Jogos`
--
ALTER TABLE `Jogos`
  ADD CONSTRAINT `Jogos_ibfk_1` FOREIGN KEY (`id_liga`) REFERENCES `Ligas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `Ligas`
--
ALTER TABLE `Ligas`
  ADD CONSTRAINT `Ligas_ibfk_1` FOREIGN KEY (`id_criador`) REFERENCES `Utilizadores` (`id`);

--
-- Limitadores para a tabela `Membros_Liga`
--
ALTER TABLE `Membros_Liga`
  ADD CONSTRAINT `Membros_Liga_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `Utilizadores` (`id`),
  ADD CONSTRAINT `Membros_Liga_ibfk_2` FOREIGN KEY (`id_liga`) REFERENCES `Ligas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `Mensagens`
--
ALTER TABLE `Mensagens`
  ADD CONSTRAINT `Mensagens_ibfk_1` FOREIGN KEY (`id_remetente`) REFERENCES `Utilizadores` (`id`),
  ADD CONSTRAINT `Mensagens_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `Utilizadores` (`id`);

--
-- Limitadores para a tabela `Ranking`
--
ALTER TABLE `Ranking`
  ADD CONSTRAINT `Ranking_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `Utilizadores` (`id`),
  ADD CONSTRAINT `Ranking_ibfk_2` FOREIGN KEY (`id_liga`) REFERENCES `Ligas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `Sets`
--
ALTER TABLE `Sets`
  ADD CONSTRAINT `Sets_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `Jogos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
