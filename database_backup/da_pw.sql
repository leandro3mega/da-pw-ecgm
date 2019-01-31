-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 30-Jan-2019 às 23:27
-- Versão do servidor: 10.1.32-MariaDB
-- PHP Version: 5.6.36

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
  `email` varchar(40) COLLATE utf8_bin NOT NULL,
  `fotografia` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `aluno`
--

INSERT INTO `aluno` (`fk_idutilizador`, `nome`, `email`, `fotografia`) VALUES
(69, 'Maria Inês Vieira Vieira', 'maria@ipvc.pt', 'a5e10b16c37ec6ac0b0deb3e82b786bb.jpg'),
(76, 'Leandro Martins Magalhães', 'leandrom@ipvc.pt', '76.jpg'),
(93, 'Arlete Magalhães', 'arlete@hotmail.pt', 'logotipo_white.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno_projeto`
--

CREATE TABLE `aluno_projeto` (
  `fk_aluno` int(11) DEFAULT NULL,
  `fk_projeto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `aluno_projeto`
--

INSERT INTO `aluno_projeto` (`fk_aluno`, `fk_projeto`) VALUES
(69, 39),
(69, 25),
(69, 42),
(69, 43),
(69, 44),
(69, 45),
(69, 46),
(69, 47),
(69, 48),
(69, 49),
(69, 50),
(69, 51),
(69, 52);

-- --------------------------------------------------------

--
-- Estrutura da tabela `docente`
--

CREATE TABLE `docente` (
  `fk_idutilizador` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `email` varchar(40) COLLATE utf8_bin NOT NULL,
  `fotografia` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `docente`
--

INSERT INTO `docente` (`fk_idutilizador`, `nome`, `email`, `fotografia`) VALUES
(80, 'Leandro Martins Magalhães', 'leandrom@ipvc.pt', 'logotipo_white.png'),
(90, 'Luís Mota', 'luis@ipvc.pt', '90.jpg'),
(94, 'Daniel Magalhães', 'daniel@hotmail.pt', 'logotipo_white.png'),
(95, 'Miguel Morais', 'miguelmorais@ipvc.pt', 'logotipo_white.png'),
(96, 'Joaquim Gomes', 'joaquimg@hotmail.com', 'logotipo_white.png'),
(97, 'Helena Coelho', 'helenacc@ipvc.pt', 'logotipo_white.png'),
(98, 'Joana Cardoso', 'joana@ipvc.pt', 'logotipo_white.png'),
(99, 'Inês Vieira', 'ines@ipvc.pt', 'logotipo_white.png'),
(100, 'Matilde Veiga', 'matilde@estg.ipvc', 'logotipo_white.png'),
(101, 'Jorge Oliveira', 'jorge@estg.ipvc.pt', 'logotipo_white.png'),
(102, 'Mafalda Castro', 'mafalda@estg.ipvc.pt', 'logotipo_white.png'),
(103, 'Alexandre Cunha', 'alexandre@ipvc.pt', 'logotipo_white.png'),
(104, 'Martim Costa', 'martim@ipvc.pt', 'logotipo_white.png'),
(105, 'Mauricio Pinto', 'mauricio@ipvc.pt', 'logotipo_white.png'),
(106, 'Marta Ramos', 'marta@ipvc.pt', 'logotipo_white.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `docente_uc`
--

CREATE TABLE `docente_uc` (
  `fk_iddocente` int(11) NOT NULL,
  `fk_iduc` int(11) NOT NULL,
  `ano_letivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `docente_uc`
--

INSERT INTO `docente_uc` (`fk_iddocente`, `fk_iduc`, `ano_letivo`) VALUES
(90, 7, 20182019),
(80, 6, 20182019),
(80, 10, 20182019),
(80, 2, 20182019),
(90, 1, 20182019),
(97, 3, 20182019);

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

--
-- Extraindo dados da tabela `ferramenta`
--

INSERT INTO `ferramenta` (`idferramenta`, `nome`, `descricao`, `empresa`) VALUES
(1, 'Adobe Illustrator', 'Ferramenta de design gráfico vetorial.', 'Adobe'),
(2, 'Sketch', 'Sketch é um editor de gráficos vetoriais.', 'Sketch');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ferramenta_projeto`
--

CREATE TABLE `ferramenta_projeto` (
  `fk_ferramenta` int(11) NOT NULL,
  `fk_projeto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `ferramenta_projeto`
--

INSERT INTO `ferramenta_projeto` (`fk_ferramenta`, `fk_projeto`) VALUES
(1, 39),
(2, 39),
(1, 42),
(1, 43);

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

--
-- Extraindo dados da tabela `ficheiro`
--

INSERT INTO `ficheiro` (`idficheiro`, `fk_idprojeto`, `nome`, `descricao`) VALUES
(4, 25, '25.pdf', ''),
(6, 39, '39.pdf', ''),
(7, 47, '47.pdf', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagem`
--

CREATE TABLE `imagem` (
  `idimagem` int(11) NOT NULL,
  `fk_idprojeto` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(100) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `imagem`
--

INSERT INTO `imagem` (`idimagem`, `fk_idprojeto`, `nome`, `descricao`) VALUES
(18, 44, '44_a82cb774195ab9ce02b870f64b75d0b90.jpg', NULL),
(19, 45, '45_02fc99b597cdd0e9aa14dd8361072f670.jpg', NULL),
(20, 46, '46_911a0e111a0a5052d85e7380addbe9240.jpg', NULL),
(21, 47, '47_40956b77912a443c2f899618537d6a450.jpg', NULL),
(22, 48, '48_3ebddc37217dbe91a9b0591c5af7e3350.jpg', NULL),
(23, 49, '49_783caea9721fa3821b0a010acfac15370.jpg', NULL),
(24, 50, '50_5351d7179cfdaa3c9d4c8c839a51f55f0.jpg', NULL),
(25, 51, '51_e5a554b5d6e229bed3a2a9e1dd106bd10.jpg', NULL),
(27, 43, '43_a0f19caedff6e6fa22f3f0029eaabd79.jpg', NULL),
(28, 42, '42_3ff8998e8300b6e5e059558a22dcd982.jpg', NULL),
(29, 42, '42_b1fa55530883c6655a449fafce17e817.jpg', NULL),
(30, 39, '39_0caac6a575f549f2e59b72fe6cc97f14.jpg', NULL),
(31, 39, '39_a956a0884dbf0ef0b1e3174bf9e81309.jpg', NULL),
(32, 39, '39_cf24457c54d52d320ac3adec000b6911.png', NULL),
(33, 25, '25_6d16da3c02d5fd0655a97ece15703de1.jpg', NULL),
(34, 25, '25_2a8575f7d9634e7b4675a37297c049a0.jpg', NULL),
(35, 25, '25_863325860bca66ad199d9b08af136f19.jpg', NULL),
(36, 25, '25_887e45c1d3a2c3d522ec4f25a4030ed6.jpg', NULL),
(37, 52, '52_4d3011b410414a20449fc82742582f030.png', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `palavra_chave`
--

CREATE TABLE `palavra_chave` (
  `fk_idprojeto` int(11) NOT NULL,
  `palavra` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `palavra_chave`
--

INSERT INTO `palavra_chave` (`fk_idprojeto`, `palavra`) VALUES
(25, 'Desenho; Caneta; Chouriça; Porca; Atum; Bilhar.'),
(39, 'Desenho; Algo Mais; Caneta.'),
(42, 'Desenho; Mockup; Cadeira; '),
(43, 'Cadeira; sketch.'),
(44, 'Desenho.'),
(45, 'bonecos; '),
(46, 'interior; desenho; '),
(47, 'design; logo; '),
(48, 'cadeira; '),
(49, 'borboletas; '),
(50, 'sofa; cores; '),
(51, 'design; quadrados; '),
(52, 'texto; livro; ');

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto`
--

CREATE TABLE `projeto` (
  `idprojeto` int(11) NOT NULL,
  `fk_iduc` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(610) COLLATE utf8_bin NOT NULL,
  `autores` varchar(200) COLLATE utf8_bin NOT NULL,
  `data` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `semestre` int(11) NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `projeto`
--

INSERT INTO `projeto` (`idprojeto`, `fk_iduc`, `titulo`, `descricao`, `autores`, `data`, `ano`, `semestre`, `tipo`) VALUES
(25, 6, 'Projeto Pintura', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'João Almeida; Ana.', 20190103, 1, 2, 1),
(39, 1, 'Projeto Cores', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\nLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit \namet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit,\nsed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et do', 'Leandro Magalhães; Pedro Cunha.', 20181108, 3, 1, 2),
(42, 3, 'Lorem ipsum dolor sit amet, consectetur adipiscing', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Maria Inês Pinto; Luís Mota.', 20190109, 2, 2, 2),
(43, 3, 'Logótipo de uma Marca', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'Maria Inês Pinto; Leandro Magalhães.', 20190126, 2, 2, 2),
(44, 6, 'Projeto de Arquitetura', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'Leandro; João.', 20190126, 3, 2, 2),
(45, 7, 'Projeto de Design de Ambientes', 'Este projeto foi feito para o curso de DA.', 'Leandro Magalhães; Maria Pinto.', 20190128, 2, 2, 2),
(46, 1, 'Projeto de interiores', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'Miguel Morais; Inês Machado.', 20180414, 3, 1, 1),
(47, 3, 'Projeto Logótipo', 'Projeto criado para um logótipo do curso de Design de Ambientes.', 'Maria Pinto.', 20100925, 2, 2, 2),
(48, 8, 'Projeto Cadeira', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'Leandro Magalhães; Inês Vieira.', 20170402, 1, 1, 1),
(49, 3, 'Projeto Estilista', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'Luís Mota; João Costa.', 20090517, 2, 2, 2),
(50, 6, 'Projeto Sofás', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'Maria Pinto; Tiago Cunha; Leonor Almeida.', 20111010, 3, 2, 1),
(51, 3, 'Projeto Papel de Parede', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'Leandro Magalhães; Beatriz Salvado.', 20140506, 2, 2, 2),
(52, 7, 'Projeto Teórico sobre Design', 'O Lorem Ipsum é um texto modelo da indústria tipográfica e de impressão. O Lorem Ipsum tem vindo a ser o texto padrão usado por estas indústrias desde o ano de 1500, quando uma misturou os caracteres de um texto para criar um espécime de livro. Este texto não só sobreviveu 5 séculos, mas também o salto para a tipografia electrónica, mantendo-se essencialmente inalterada. Foi popularizada nos anos 60 com a disponibilização das folhas de Letraset, que continham passagens com Lorem Ipsum, e mais recentemente com os programas de publicação como o Aldus PageMaker que incluem versões do Lorem Ipsum.', 'Maria Pinto.', 20060505, 1, 2, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidade_curricular`
--

CREATE TABLE `unidade_curricular` (
  `idunidade_curricular` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8_bin NOT NULL,
  `ano_curricular` int(11) NOT NULL,
  `semestre` int(11) NOT NULL,
  `descricao` varchar(200) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `unidade_curricular`
--

INSERT INTO `unidade_curricular` (`idunidade_curricular`, `nome`, `ano_curricular`, `semestre`, `descricao`) VALUES
(1, 'Desenho 1', 2, 1, 'Cadeira de introdução ao desenho técnico.'),
(2, 'História da Arte e da Cultura', 1, 1, 'Cadeira sobre a historia da arte relacionado com a cultura.'),
(3, 'Estudos Sociais', 1, 2, 'Estudos sociais sobre coisas.'),
(4, 'Introdução ao Projeto I', 1, 1, 'Introdução ao Projeto'),
(5, 'História e Crítica do Design', 1, 1, ''),
(6, 'Desenho II', 1, 2, ''),
(7, 'Estudos de Antropologia e do Património', 1, 2, ''),
(8, 'Introdução ao Projeto II', 1, 1, ''),
(10, 'Multimédia Interativa', 1, 1, ''),
(11, 'Técnicas de Comunicação e Relações Interpessoais', 1, 1, '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

CREATE TABLE `utilizador` (
  `idutilizador` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(80) COLLATE utf8_bin NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`idutilizador`, `username`, `password`, `tipo`) VALUES
(1, 'admin', '$2y$10$UYK7CP3tPMsSP0eql9uRSOH6uLWubtO4Wo1DlgwkD.R2num9atPOW', 0),
(69, 'maria', '$2y$10$sXR3Y7Vu/9Js2UxTxHs5MumkUWs65M1XHoUdJPsy20H9su49TyCEi', 1),
(76, 'leandrom', '$2y$10$XgZctm5OrUH/OpwlL8u7/OQ2nI4dk01mU12OZ0Vlv1AFhRe.knwKe', 1),
(80, 'leandro', '$2y$10$a7r8M0KVwM.lxlwLbzfXGeOtXj.TlbD7C0H90E8.h4CRsh59CUQz6', 2),
(90, 'luis', '$2y$10$lpKHRFTIA0oTF8PWqtFuj.cur46Z/JdbxZKhTsmnid2Ujtlcgvte.', 2),
(93, 'arlete', '$2y$10$.T8qxlCJ4zHBH8guuFwhwenaViPbj3xc/PvABSzR5fQcz1WCVcM6q', 1),
(94, 'daniel', '$2y$10$tzwKG6qDuRJAJ/gELeSpJOX5.Ze7xVKGnTP818rRj3sl/WDtiN.ta', 2),
(95, 'miguelmorais', '$2y$10$VYpc14GbY8g8iH0I8xR7DuO3AYGEJovC/vdE5sMaYDwbNH9XyscRa', 2),
(96, 'joaquim', '$2y$10$YgF6oQYKeO3QlTuCuzg0XeKcXEeqoaYSNCozmNRnjXunR5QBxmg6S', 2),
(97, 'helena', '$2y$10$lIjATBvJt6ojMsNalsmx5uQCa1cO/xYYPnGGWtAl5OwEYIc33ZpOq', 2),
(98, 'joana', '$2y$10$QROiC7UejnOFXWuhBRXM0u5.yhAhBbEn30NKGZRPIgQRR27W41Daq', 2),
(99, 'inesvieira', '$2y$10$jnYBZlHQrFXSEM6m3a/rmOR7SHB9EhUR/dUlTfj2Xx7dp5TDQH8tS', 2),
(100, 'matilde', '$2y$10$FFiQ4C/jSjkCYKc7I/sgaOc1g/EljVV.AxYCHqDc2C2rvurW8Oz8K', 2),
(101, 'jorge', '$2y$10$gr7XK800X9.V2X6leu59F.t.27w1fhbcMA6H/Pixi9Ap3xWHXNntW', 2),
(102, 'mafalda', '$2y$10$wVR0mV2ikyVO08KgALhgqOuQpMR6MthiCMIm689O.8IsI8cYCXhyu', 2),
(103, 'alexandre', '$2y$10$K.9D0asa7DmenB.YXh0kEuKW4Mf8qKsPuzrSiLATJsFJ9svPilfLi', 2),
(104, 'martim', '$2y$10$LKTigDkAoSFphGRkLWWj5eYnVAA7YQ3xOVyQ9fWDm0CjVunIMTZWi', 2),
(105, 'mauricio', '$2y$10$x.Azr4GR1JwL9gKvStb43.5aMuKcEy7CaRebJk7uO/HFRE49Pg7ey', 2),
(106, 'marta', '$2y$10$5yvI/laVZc68r35jIY0bnOv1bNHvQirLMrEirNTtWs2mrw80sEdga', 2);

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
-- Extraindo dados da tabela `video`
--

INSERT INTO `video` (`idvideo`, `fk_idprojeto`, `nome`, `descricao`) VALUES
(9, 39, '1PH30jnvaJo', ''),
(10, 25, 'AXSKj68uhZ4', ''),
(11, 46, 'Ek-km4vPSto', '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_alunosdocentes`
-- (See below for the actual view)
--
CREATE TABLE `view_alunosdocentes` (
`fk_idutilizador` int(11)
,`nome` varchar(100)
,`email` varchar(40)
,`fotografia` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_useralunosdocentes`
-- (See below for the actual view)
--
CREATE TABLE `view_useralunosdocentes` (
`idutilizador` int(11)
,`username` varchar(20)
,`tipo` int(11)
,`nome` varchar(100)
,`email` varchar(40)
,`fotografia` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure for view `view_alunosdocentes`
--
DROP TABLE IF EXISTS `view_alunosdocentes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_alunosdocentes`  AS  select `aluno`.`fk_idutilizador` AS `fk_idutilizador`,`aluno`.`nome` AS `nome`,`aluno`.`email` AS `email`,`aluno`.`fotografia` AS `fotografia` from `aluno` union all select `docente`.`fk_idutilizador` AS `fk_idutilizador`,`docente`.`nome` AS `nome`,`docente`.`email` AS `email`,`docente`.`fotografia` AS `fotografia` from `docente` ;

-- --------------------------------------------------------

--
-- Structure for view `view_useralunosdocentes`
--
DROP TABLE IF EXISTS `view_useralunosdocentes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_useralunosdocentes`  AS  select `u`.`idutilizador` AS `idutilizador`,`u`.`username` AS `username`,`u`.`tipo` AS `tipo`,`view`.`nome` AS `nome`,`view`.`email` AS `email`,`view`.`fotografia` AS `fotografia` from (`view_alunosdocentes` `view` join `utilizador` `u`) where (`u`.`idutilizador` = `view`.`fk_idutilizador`) ;

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
-- Indexes for table `docente`
--
ALTER TABLE `docente`
  ADD KEY `fk_idutilizador_idx` (`fk_idutilizador`),
  ADD KEY `fk_idutilizador_docente_idx` (`fk_idutilizador`);

--
-- Indexes for table `docente_uc`
--
ALTER TABLE `docente_uc`
  ADD KEY `fk_docente_uc_idx` (`fk_iddocente`),
  ADD KEY `fk_uc_docente_idx` (`fk_iduc`);

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
  ADD KEY `fk_projeto_iduc_idx` (`fk_iduc`),
  ADD KEY `fk_projeto_uc_idx` (`fk_iduc`);

--
-- Indexes for table `unidade_curricular`
--
ALTER TABLE `unidade_curricular`
  ADD PRIMARY KEY (`idunidade_curricular`);

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
-- AUTO_INCREMENT for table `ferramenta`
--
ALTER TABLE `ferramenta`
  MODIFY `idferramenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ficheiro`
--
ALTER TABLE `ficheiro`
  MODIFY `idficheiro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `imagem`
--
ALTER TABLE `imagem`
  MODIFY `idimagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `projeto`
--
ALTER TABLE `projeto`
  MODIFY `idprojeto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `unidade_curricular`
--
ALTER TABLE `unidade_curricular`
  MODIFY `idunidade_curricular` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `idutilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `idvideo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Limitadores para a tabela `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `fk_idutilizador_docente` FOREIGN KEY (`fk_idutilizador`) REFERENCES `utilizador` (`idutilizador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `docente_uc`
--
ALTER TABLE `docente_uc`
  ADD CONSTRAINT `fk_docente_uc` FOREIGN KEY (`fk_iddocente`) REFERENCES `docente` (`fk_idutilizador`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uc_docente` FOREIGN KEY (`fk_iduc`) REFERENCES `unidade_curricular` (`idunidade_curricular`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_projeto_uc` FOREIGN KEY (`fk_iduc`) REFERENCES `unidade_curricular` (`idunidade_curricular`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `fk_idprojeto_video` FOREIGN KEY (`fk_idprojeto`) REFERENCES `projeto` (`idprojeto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
