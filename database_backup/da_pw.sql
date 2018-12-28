-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 28-Dez-2018 às 18:36
-- Versão do servidor: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `da_pw`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno`
--

CREATE TABLE `aluno` (
  `fk_idutilizador` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `email` varchar(45) COLLATE utf8_bin NOT NULL,
  `fotografia` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno_projeto`
--

CREATE TABLE `aluno_projeto` (
  `fk_aluno` int(11) DEFAULT NULL,
  `fk_projeto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `idcategoria` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(100) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria_projeto`
--

CREATE TABLE `categoria_projeto` (
  `fk_categoria` int(11) NOT NULL,
  `fk_projeto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `docente`
--

CREATE TABLE `docente` (
  `fk_idutilizador` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `email` varchar(45) COLLATE utf8_bin NOT NULL,
  `fotografia` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `docente_projeto`
--

CREATE TABLE `docente_projeto` (
  `fk_docente` int(11) NOT NULL,
  `fk_projeto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ferramenta`
--

CREATE TABLE `ferramenta` (
  `idferramenta` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `empresa` varchar(50) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ferramenta_projeto`
--

CREATE TABLE `ferramenta_projeto` (
  `fk_ferramenta` int(11) NOT NULL,
  `fk_projeto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ficheiro`
--

CREATE TABLE `ficheiro` (
  `idficheiro` int(11) NOT NULL,
  `fk_idprojeto` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagem`
--

CREATE TABLE `imagem` (
  `idimagem` int(11) NOT NULL,
  `fk_idprojeto` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `palavra_chave`
--

CREATE TABLE `palavra_chave` (
  `fk_idprojeto` int(11) NOT NULL,
  `palavra` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto`
--

CREATE TABLE `projeto` (
  `idprojeto` int(11) NOT NULL,
  `fk_aluno` int(11) NOT NULL,
  `fk_unidade_curricular` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(500) COLLATE utf8_bin NOT NULL,
  `data` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `semestre` int(11) NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidade_curricular`
--

CREATE TABLE `unidade_curricular` (
  `idunidade_curricular` int(11) NOT NULL,
  `fk_iddocente` int(11) DEFAULT NULL,
  `nome` varchar(50) COLLATE utf8_bin NOT NULL,
  `ano_letivo` int(11) NOT NULL,
  `semestre` int(11) NOT NULL,
  `descricao` varchar(200) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

CREATE TABLE `utilizador` (
  `idutilizador` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(45) COLLATE utf8_bin NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`idutilizador`, `username`, `password`, `tipo`) VALUES
(1, 'admin', 'admin', 0),
(2, 'leandro', 'leandro', 2),
(3, 'ana', '1234', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `video`
--

CREATE TABLE `video` (
  `idvideo` int(11) NOT NULL,
  `fk_idprojeto` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aluno`
--
ALTER TABLE `aluno`
  ADD KEY `fk_idutilizador_aluno_idx` (`fk_idutilizador`);

--
-- Indexes for table `aluno_projeto`
--
ALTER TABLE `aluno_projeto`
  ADD KEY `fk_aluno_idx` (`fk_aluno`),
  ADD KEY `fk_projeto_idx` (`fk_projeto`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Indexes for table `categoria_projeto`
--
ALTER TABLE `categoria_projeto`
  ADD KEY `fk_categoria_categoria_idx` (`fk_categoria`),
  ADD KEY `fk_categoria_projeto_idx` (`fk_projeto`);

--
-- Indexes for table `docente`
--
ALTER TABLE `docente`
  ADD KEY `fk_idutilizador_idx` (`fk_idutilizador`),
  ADD KEY `fk_idutilizador_docente_idx` (`fk_idutilizador`);

--
-- Indexes for table `docente_projeto`
--
ALTER TABLE `docente_projeto`
  ADD KEY `fk_docente_idx` (`fk_docente`),
  ADD KEY `fk_projeto_idx` (`fk_projeto`),
  ADD KEY `fk_docente_docente_idx` (`fk_docente`);

--
-- Indexes for table `ferramenta`
--
ALTER TABLE `ferramenta`
  ADD PRIMARY KEY (`idferramenta`);

--
-- Indexes for table `ferramenta_projeto`
--
ALTER TABLE `ferramenta_projeto`
  ADD KEY `fk_ferramenta_ferramenta_idx` (`fk_ferramenta`),
  ADD KEY `fk_ferramenta_projeto_idx` (`fk_projeto`);

--
-- Indexes for table `ficheiro`
--
ALTER TABLE `ficheiro`
  ADD PRIMARY KEY (`idficheiro`),
  ADD KEY `fk_idprojeto_ficheiro_idx` (`fk_idprojeto`);

--
-- Indexes for table `imagem`
--
ALTER TABLE `imagem`
  ADD PRIMARY KEY (`idimagem`),
  ADD KEY `fk_projeto_imagem_idx` (`fk_idprojeto`);

--
-- Indexes for table `palavra_chave`
--
ALTER TABLE `palavra_chave`
  ADD KEY `fk_projeto_palavra_chave_idx` (`fk_idprojeto`);

--
-- Indexes for table `projeto`
--
ALTER TABLE `projeto`
  ADD PRIMARY KEY (`idprojeto`),
  ADD KEY `fk_iduc_projeto_idx` (`fk_unidade_curricular`);

--
-- Indexes for table `unidade_curricular`
--
ALTER TABLE `unidade_curricular`
  ADD PRIMARY KEY (`idunidade_curricular`),
  ADD KEY `fk_docente_idx` (`fk_iddocente`);

--
-- Indexes for table `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`idutilizador`);

--
-- Indexes for table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`idvideo`),
  ADD KEY `fk_idprojeto_video_idx` (`fk_idprojeto`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ferramenta`
--
ALTER TABLE `ferramenta`
  MODIFY `idferramenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ficheiro`
--
ALTER TABLE `ficheiro`
  MODIFY `idficheiro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imagem`
--
ALTER TABLE `imagem`
  MODIFY `idimagem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projeto`
--
ALTER TABLE `projeto`
  MODIFY `idprojeto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unidade_curricular`
--
ALTER TABLE `unidade_curricular`
  MODIFY `idunidade_curricular` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `idutilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `idvideo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `fk_idutilizador_aluno` FOREIGN KEY (`fk_idutilizador`) REFERENCES `utilizador` (`idutilizador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `aluno_projeto`
--
ALTER TABLE `aluno_projeto`
  ADD CONSTRAINT `fk_aluno_aluno` FOREIGN KEY (`fk_aluno`) REFERENCES `aluno` (`fk_idutilizador`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aluno_projeto` FOREIGN KEY (`fk_projeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `categoria_projeto`
--
ALTER TABLE `categoria_projeto`
  ADD CONSTRAINT `fk_categoria_categoria` FOREIGN KEY (`fk_categoria`) REFERENCES `categoria` (`idcategoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_categoria_projeto` FOREIGN KEY (`fk_projeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `fk_idutilizador_docente` FOREIGN KEY (`fk_idutilizador`) REFERENCES `utilizador` (`idutilizador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `docente_projeto`
--
ALTER TABLE `docente_projeto`
  ADD CONSTRAINT `fk_docente_docente` FOREIGN KEY (`fk_docente`) REFERENCES `docente` (`fk_idutilizador`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_docente_projeto` FOREIGN KEY (`fk_projeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `ferramenta_projeto`
--
ALTER TABLE `ferramenta_projeto`
  ADD CONSTRAINT `fk_ferramenta_ferramenta` FOREIGN KEY (`fk_ferramenta`) REFERENCES `ferramenta` (`idferramenta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ferramenta_projeto` FOREIGN KEY (`fk_projeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `ficheiro`
--
ALTER TABLE `ficheiro`
  ADD CONSTRAINT `fk_idprojeto_ficheiro` FOREIGN KEY (`fk_idprojeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `imagem`
--
ALTER TABLE `imagem`
  ADD CONSTRAINT `fk_projeto_imagem` FOREIGN KEY (`fk_idprojeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `palavra_chave`
--
ALTER TABLE `palavra_chave`
  ADD CONSTRAINT `fk_projeto_palavra_chave` FOREIGN KEY (`fk_idprojeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `projeto`
--
ALTER TABLE `projeto`
  ADD CONSTRAINT `fk_iduc_projeto` FOREIGN KEY (`fk_unidade_curricular`) REFERENCES `unidade_curricular` (`idunidade_curricular`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `unidade_curricular`
--
ALTER TABLE `unidade_curricular`
  ADD CONSTRAINT `fk_docente` FOREIGN KEY (`fk_iddocente`) REFERENCES `docente` (`fk_idutilizador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `fk_idprojeto_video` FOREIGN KEY (`fk_idprojeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
