-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/06/2026 às 01:28
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `crud_personagem_classpecto`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `habilidades`
--

CREATE TABLE `habilidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `origem` varchar(30) NOT NULL,
  `tipo` enum('Ataque','Cura','Buff','Debuff','Controle','Passiva') NOT NULL,
  `descricao` text DEFAULT NULL,
  `dano` int(11) DEFAULT 0,
  `cura` int(11) DEFAULT 0,
  `custo_mana` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `habilidades`
--

INSERT INTO `habilidades` (`id`, `nome`, `origem`, `tipo`, `descricao`, `dano`, `cura`, `custo_mana`) VALUES
(1, 'Investida Heroica', 'Cavaleiro(a)', 'Ataque', 'Avança contra o inimigo com toda sua força.', 40, 0, 15),
(2, 'Muralha de Aço', 'Cavaleiro(a)', 'Buff', 'Aumenta a defesa do usuário.', 0, 0, 20),
(3, 'Golpe do Guardião', 'Cavaleiro(a)', 'Ataque', 'Golpe poderoso que protege aliados.', 50, 0, 25),
(4, 'Defesa Leal', 'Escudeiro(a)', 'Buff', 'Aumenta a resistência de um aliado.', 0, 0, 15),
(5, 'Cobertura Protetora', 'Escudeiro(a)', 'Controle', 'Recebe o dano destinado a um aliado.', 0, 0, 20),
(6, 'Contra-Ataque', 'Escudeiro(a)', 'Ataque', 'Responde imediatamente após sofrer dano.', 35, 0, 15),
(7, 'Presságio', 'Vidente', 'Controle', 'Revela a próxima ação do inimigo.', 0, 0, 15),
(8, 'Visão Distante', 'Vidente', 'Buff', 'Aumenta a precisão da equipe.', 0, 0, 20),
(9, 'Leitura do Destino', 'Vidente', 'Debuff', 'Reduz a chance crítica dos inimigos.', 0, 0, 25),
(10, 'Bola Arcana', 'Mago(a)', 'Ataque', 'Projétil mágico concentrado.', 45, 0, 20),
(11, 'Explosão Mística', 'Mago(a)', 'Ataque', 'Explosão mágica em área.', 60, 0, 35),
(12, 'Teleporte', 'Mago(a)', 'Controle', 'Reposiciona o usuário no campo.', 0, 0, 20),
(13, 'Ataque Furtivo', 'Ladrão(a)', 'Ataque', 'Golpe com dano aumentado pelas sombras.', 55, 0, 20),
(14, 'Roubo Rápido', 'Ladrão(a)', 'Debuff', 'Rouba recursos do alvo.', 20, 0, 15),
(15, 'Passo Sombrio', 'Ladrão(a)', 'Buff', 'Aumenta evasão temporariamente.', 0, 0, 15),
(16, 'Golpe Preciso', 'Ladino(a)', 'Ataque', 'Ataque direcionado a pontos vitais.', 50, 0, 20),
(17, 'Névoa de Engano', 'Ladino(a)', 'Controle', 'Confunde os inimigos.', 0, 0, 25),
(18, 'Emboscada', 'Ladino(a)', 'Ataque', 'Ataque surpresa devastador.', 65, 0, 30),
(19, 'Cura Menor', 'Servo(a)', 'Cura', 'Recupera vida de um aliado.', 0, 35, 15),
(20, 'Benção Protetora', 'Servo(a)', 'Buff', 'Aumenta a defesa mágica.', 0, 0, 20),
(21, 'Sacrifício Altruísta', 'Servo(a)', 'Cura', 'Transfere parte da própria vida.', 0, 50, 25),
(22, 'Rajada Celeste', 'Sílfide / Silfo', 'Ataque', 'Ataque baseado em ventos mágicos.', 45, 0, 20),
(23, 'Dança dos Ventos', 'Sílfide / Silfo', 'Buff', 'Aumenta velocidade e evasão.', 0, 0, 20),
(24, 'Passo Etéreo', 'Sílfide / Silfo', 'Controle', 'Move-se sem ser alvo por um turno.', 0, 0, 25),
(25, 'Maldição Sombria', 'Bruxo(a)', 'Debuff', 'Enfraquece o alvo.', 20, 0, 20),
(26, 'Ritual Profano', 'Bruxo(a)', 'Buff', 'Aumenta o poder mágico.', 0, 0, 25),
(27, 'Explosão Negra', 'Bruxo(a)', 'Ataque', 'Libera energia destrutiva.', 70, 0, 35),
(28, 'Despertar do Legado', 'Herdeiro(a)', 'Buff', 'Desperta força ancestral.', 0, 0, 25),
(29, 'Aura Nobre', 'Herdeiro(a)', 'Passiva', 'Aumenta atributos naturalmente.', 0, 0, 0),
(30, 'Chamado Ancestral', 'Herdeiro(a)', 'Ataque', 'Invoca o poder dos antepassados.', 55, 0, 30),
(31, 'Decreto Real', 'Príncipe / Princesa', 'Debuff', 'Reduz atributos dos inimigos.', 0, 0, 20),
(32, 'Golpe Imperial', 'Príncipe / Princesa', 'Ataque', 'Ataque majestoso e poderoso.', 60, 0, 25),
(33, 'Inspiração Régia', 'Príncipe / Princesa', 'Buff', 'Fortalece aliados próximos.', 0, 0, 20),
(34, 'Canção de Cura', 'Bardo(a)', 'Cura', 'Melodia que recupera vida.', 0, 40, 20),
(35, 'Melodia Inspiradora', 'Bardo(a)', 'Buff', 'Aumenta ataque dos aliados.', 0, 0, 20),
(36, 'Nota Disruptiva', 'Bardo(a)', 'Debuff', 'Desorganiza os inimigos.', 15, 0, 15),
(37, 'Ordem Absoluta', 'Lorde', 'Controle', 'Força um alvo a obedecer.', 0, 0, 30),
(38, 'Domínio do Campo', 'Lorde', 'Buff', 'Melhora toda a equipe.', 0, 0, 25),
(39, 'Comando Supremo', 'Lorde', 'Ataque', 'Ataque esmagador de autoridade.', 65, 0, 30),
(40, 'Inspiração Divina', 'Musa', 'Buff', 'Aumenta vários atributos.', 0, 0, 25),
(41, 'Encanto Harmônico', 'Musa', 'Controle', 'Encanta e distrai inimigos.', 0, 0, 20),
(42, 'Sinfonia da Alma', 'Musa', 'Cura', 'Recupera vida de todos os aliados.', 0, 60, 35),
(43, 'Fôlego Revigorante', 'Respiração', 'Cura', 'Recupera energia e vida.', 0, 25, 10),
(44, 'Pulmões de Ferro', 'Respiração', 'Passiva', 'Aumenta resistência.', 0, 0, 0),
(45, 'Respiração Perfeita', 'Respiração', 'Buff', 'Melhora todos os atributos temporariamente.', 0, 0, 20),
(46, 'Transfusão Vital', 'Sangue', 'Cura', 'Rouba vida do alvo.', 25, 25, 20),
(47, 'Ritual Carmesim', 'Sangue', 'Buff', 'Aumenta poder através do sangue.', 0, 0, 25),
(48, 'Corrente Sanguínea', 'Sangue', 'Ataque', 'Manipula sangue como arma.', 50, 0, 30),
(49, 'Florescimento', 'Vida', 'Cura', 'Grande recuperação de vida.', 0, 50, 25),
(50, 'Regeneração Natural', 'Vida', 'Passiva', 'Recupera vida por turno.', 0, 0, 0),
(51, 'Toque Vital', 'Vida', 'Cura', 'Cura instantânea.', 0, 35, 15),
(52, 'Colapso', 'Ruína', 'Ataque', 'Destrói a estrutura do alvo.', 55, 0, 25),
(53, 'Desintegração', 'Ruína', 'Ataque', 'Dano mágico devastador.', 75, 0, 40),
(54, 'Queda Inevitável', 'Ruína', 'Debuff', 'Reduz defesa drasticamente.', 0, 0, 25),
(55, 'Raio Purificador', 'Luz', 'Ataque', 'Ataque de luz concentrada.', 50, 0, 20),
(56, 'Revelação', 'Luz', 'Controle', 'Revela inimigos ocultos.', 0, 0, 15),
(57, 'Aurora Sagrada', 'Luz', 'Cura', 'Recupera vida da equipe.', 0, 45, 25),
(58, 'Anulação', 'Vazio', 'Controle', 'Cancela um efeito ativo.', 0, 0, 25),
(59, 'Névoa do Nada', 'Vazio', 'Debuff', 'Reduz precisão dos inimigos.', 0, 0, 20),
(60, 'Buraco Abissal', 'Vazio', 'Ataque', 'Consome energia do alvo.', 60, 0, 30),
(61, 'Aceleração', 'Tempo', 'Buff', 'Aumenta velocidade.', 0, 0, 20),
(62, 'Retrocesso', 'Tempo', 'Cura', 'Recupera vida perdida recentemente.', 0, 30, 30),
(63, 'Paralisação Temporal', 'Tempo', 'Controle', 'Impede ações por um turno.', 10, 0, 35),
(64, 'Dobra Dimensional', 'Espaço', 'Controle', 'Reposiciona alvos.', 0, 0, 20),
(65, 'Teleporte Supremo', 'Espaço', 'Buff', 'Movimentação instantânea.', 0, 0, 15),
(66, 'Fenda Espacial', 'Espaço', 'Ataque', 'Corta o espaço diante do alvo.', 55, 0, 25),
(67, 'Leitura Mental', 'Mente', 'Controle', 'Descobre intenções do alvo.', 0, 0, 15),
(68, 'Confusão Psíquica', 'Mente', 'Debuff', 'Confunde o inimigo.', 15, 0, 20),
(69, 'Controle Mental', 'Mente', 'Controle', 'Assume controle temporário.', 0, 0, 35),
(70, 'Empatia Profunda', 'Coração', 'Buff', 'Fortalece aliados emocionalmente.', 0, 0, 20),
(71, 'Golpe da Alma', 'Coração', 'Ataque', 'Atinge diretamente a alma.', 50, 0, 25),
(72, 'Laço Espiritual', 'Coração', 'Cura', 'Compartilha vitalidade.', 0, 40, 20),
(73, 'Fúria Crescente', 'Odio', 'Buff', 'Aumenta dano conforme sofre ataques.', 0, 0, 20),
(74, 'Vingança Implacável', 'Odio', 'Ataque', 'Golpe mais forte contra quem causou dano.', 60, 0, 25),
(75, 'Ira Devastadora', 'Odio', 'Ataque', 'Explosão de ódio concentrado.', 75, 0, 35),
(76, 'Milagre', 'Esperança', 'Cura', 'Recupera grande quantidade de vida.', 0, 60, 35),
(77, 'Determinação', 'Esperança', 'Buff', 'Aumenta resistência e ataque.', 0, 0, 20),
(78, 'Chama da Esperança', 'Esperança', 'Passiva', 'Mantém o usuário firme em situações críticas.', 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `partida`
--

CREATE TABLE `partida` (
  `ID_partida` int(11) NOT NULL,
  `dif` varchar(50) DEFAULT NULL,
  `local` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `partida`
--

INSERT INTO `partida` (`ID_partida`, `dif`, `local`) VALUES
(10, 'Médio', 'deserto'),
(11, 'Difícil', 'deserto'),
(12, 'Difícil', 'deserto'),
(13, 'Difícil', 'deserto'),
(14, 'Fácil', 'montanha'),
(15, 'Médio', 'deserto'),
(16, 'Difícil', 'montanha'),
(17, 'Médio', 'floresta'),
(18, 'Médio', 'montanha'),
(19, 'Difícil', 'deserto'),
(20, 'Difícil', 'floresta'),
(21, 'Difícil', 'montanha'),
(22, 'Difícil', 'deserto'),
(23, 'Médio', 'montanha'),
(24, 'Fácil', 'montanha'),
(25, 'Fácil', 'montanha'),
(26, 'Difícil', 'montanha'),
(27, 'Difícil', 'montanha'),
(28, 'Médio', 'deserto'),
(29, 'Difícil', 'montanha'),
(30, 'Difícil', 'montanha'),
(31, 'Difícil', 'montanha'),
(32, 'Difícil', 'montanha'),
(33, 'Difícil', 'montanha'),
(34, 'Difícil', 'montanha');

-- --------------------------------------------------------

--
-- Estrutura para tabela `partida_personagem`
--

CREATE TABLE `partida_personagem` (
  `ID_partida` int(11) NOT NULL,
  `ID_personagem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `partida_personagem`
--

INSERT INTO `partida_personagem` (`ID_partida`, `ID_personagem`) VALUES
(10, 30),
(10, 31),
(10, 33),
(11, 30),
(11, 31),
(11, 32),
(12, 30),
(12, 32),
(12, 33),
(13, 30),
(13, 32),
(13, 33),
(14, 30),
(14, 32),
(14, 33),
(15, 30),
(15, 32),
(15, 33),
(16, 30),
(16, 32),
(16, 33),
(17, 30),
(17, 32),
(17, 33),
(18, 30),
(18, 32),
(18, 33),
(19, 30),
(19, 31),
(19, 32),
(20, 30),
(20, 32),
(20, 33),
(21, 30),
(21, 32),
(21, 33),
(22, 30),
(22, 32),
(22, 33),
(23, 30),
(23, 32),
(23, 33),
(24, 30),
(24, 31),
(24, 32),
(25, 30),
(25, 32),
(25, 33),
(26, 30),
(26, 32),
(26, 33),
(27, 30),
(27, 32),
(27, 33),
(28, 30),
(28, 32),
(28, 33),
(29, 30),
(29, 32),
(29, 33),
(30, 30),
(30, 32),
(30, 33),
(31, 30),
(31, 32),
(31, 33),
(32, 30),
(32, 32),
(32, 33),
(33, 30),
(33, 31),
(33, 34),
(34, 30),
(34, 31),
(34, 34);

-- --------------------------------------------------------

--
-- Estrutura para tabela `personagem`
--

CREATE TABLE `personagem` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `classe` varchar(50) NOT NULL,
  `aspecto` varchar(50) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `imagem` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `personagem`
--

INSERT INTO `personagem` (`id`, `nome`, `classe`, `aspecto`, `usuario_id`, `imagem`) VALUES
(30, 'Lohan Leonardo monteiro ramos', 'Cavaleiro(a)', 'Respiração', 1, 'uploads/6a231ef225a42.webp'),
(31, 'pedrin', 'Escudeiro(a)', 'Luz', 1, 'uploads/6a259c89628f4.webp'),
(32, 'joao', 'Bruxo(a)', 'Respiração', 1, 'uploads/6a259c9c61dbd.png'),
(33, 'dwadwad', 'Escudeiro(a)', 'Respiração', 1, 'uploads/6a259caaed974.webp'),
(34, 'pedrinooo', 'Cavaleiro(a)', 'Sangue', 1, 'uploads/6a25faee39f1d.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `personagem_habilidade`
--

CREATE TABLE `personagem_habilidade` (
  `id` int(11) NOT NULL,
  `personagem_id` int(11) NOT NULL,
  `habilidade_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `personagem_habilidade`
--

INSERT INTO `personagem_habilidade` (`id`, `personagem_id`, `habilidade_id`) VALUES
(1, 30, 44),
(2, 30, 43),
(3, 30, 2),
(4, 31, 5),
(5, 31, 57),
(6, 31, 4),
(7, 32, 26),
(8, 32, 44),
(9, 32, 45),
(10, 33, 44),
(11, 33, 5),
(12, 33, 43),
(13, 34, 3),
(14, 34, 2),
(15, 34, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` char(64) NOT NULL COMMENT 'Hash SHA256 da senha',
  `criado_em` datetime NOT NULL DEFAULT current_timestamp(),
  `foto_perfil` varchar(500) DEFAULT NULL,
  `biografia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `criado_em`, `foto_perfil`, `biografia`) VALUES
(1, 'Ash Ketchum', 'admin@email.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2026-05-18 16:11:26', 'uploads/perfil/user_1_6a15ce9f0aef8.jpg', 'gosto de pensar em qual classpecto os meus personagens favoritos pertencem'),
(2, 'N', 'n@email.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2026-05-18 16:12:18', NULL, NULL),
(3, 'Pedro Sá de Sousa', 'pedropipoca@email.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2026-05-21 17:58:02', NULL, NULL),
(4, 'Beno Sá de Sousa', 'sollux@email.com', '8bb0cf6eb9b17d0f7d22b456f121257dc1254e1f01665370476383ea776df414', '2026-05-21 18:15:11', NULL, NULL),
(5, 'Davi DE Melo', 'davi@email.com', 'a30a2365ac41a9d2a9ea5f0dd128cffbc9f6aef311e78308021966991e1e4ca1', '2026-05-26 07:35:15', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`ID_partida`);

--
-- Índices de tabela `partida_personagem`
--
ALTER TABLE `partida_personagem`
  ADD PRIMARY KEY (`ID_partida`,`ID_personagem`),
  ADD KEY `ID_personagem` (`ID_personagem`);

--
-- Índices de tabela `personagem`
--
ALTER TABLE `personagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_personagem_usuario` (`usuario_id`);

--
-- Índices de tabela `personagem_habilidade`
--
ALTER TABLE `personagem_habilidade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personagem_id` (`personagem_id`),
  ADD KEY `habilidade_id` (`habilidade_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT de tabela `partida`
--
ALTER TABLE `partida`
  MODIFY `ID_partida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `personagem`
--
ALTER TABLE `personagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `personagem_habilidade`
--
ALTER TABLE `personagem_habilidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `partida_personagem`
--
ALTER TABLE `partida_personagem`
  ADD CONSTRAINT `partida_personagem_ibfk_1` FOREIGN KEY (`ID_partida`) REFERENCES `partida` (`ID_partida`) ON DELETE CASCADE,
  ADD CONSTRAINT `partida_personagem_ibfk_2` FOREIGN KEY (`ID_personagem`) REFERENCES `personagem` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `personagem`
--
ALTER TABLE `personagem`
  ADD CONSTRAINT `fk_personagem_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `personagem_habilidade`
--
ALTER TABLE `personagem_habilidade`
  ADD CONSTRAINT `personagem_habilidade_ibfk_1` FOREIGN KEY (`personagem_id`) REFERENCES `personagem` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `personagem_habilidade_ibfk_2` FOREIGN KEY (`habilidade_id`) REFERENCES `habilidades` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
