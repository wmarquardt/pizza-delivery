-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 01, 2016 at 03:23 PM
-- Server version: 5.7.12-0ubuntu1
-- PHP Version: 7.0.4-7ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pizzaria_db`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `get_status` (`s` INT) RETURNS VARCHAR(11) CHARSET utf8 BEGIN
    DECLARE r VARCHAR(20);

    IF s = 0 THEN SET r = 'Em aberto';
    ELSEIF s = 1 THEN SET r = 'Concluído';
    ELSEIF s = 2 THEN SET r = 'Cancelado';
    ELSE SET r = 'Em Produção';
    END IF;

    RETURN r;
  END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `login` varchar(20) NOT NULL,
  `senha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `login`, `senha`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997');

-- --------------------------------------------------------

--
-- Table structure for table `bebidas`
--

CREATE TABLE `bebidas` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `valor` double NOT NULL DEFAULT '0',
  `id_categoria` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bebidas`
--

INSERT INTO `bebidas` (`id`, `nome`, `valor`, `id_categoria`, `ativo`) VALUES
(1, 'Coca cola', 7, 1, 1),
(2, ' Coca Cola', 4, 2, 1),
(3, 'Brahma', 3.5, 3, 1),
(4, 'Skol', 4, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bebidas_categorias`
--

CREATE TABLE `bebidas_categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bebidas_categorias`
--

INSERT INTO `bebidas_categorias` (`id`, `nome`) VALUES
(1, 'Refrigerante 2L'),
(2, 'Refrigerante Lata'),
(3, 'Cerveja Lata'),
(4, 'Refrigerante 600ml');

-- --------------------------------------------------------

--
-- Table structure for table `calzones_categorias`
--

CREATE TABLE `calzones_categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `calzones_categorias`
--

INSERT INTO `calzones_categorias` (`id`, `nome`, `descricao`) VALUES
(3, 'Doces', ''),
(4, 'Salgados', '');

-- --------------------------------------------------------

--
-- Table structure for table `calzones_sabores`
--

CREATE TABLE `calzones_sabores` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` longtext,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `calzones_sabores`
--

INSERT INTO `calzones_sabores` (`id`, `nome`, `descricao`, `id_categoria`) VALUES
(1, 'Calabresa', 'teste sabor calabresa', 4);

-- --------------------------------------------------------

--
-- Table structure for table `calzones_tamanhos`
--

CREATE TABLE `calzones_tamanhos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `tamanho_cm` varchar(3) NOT NULL,
  `num_sabores` int(11) NOT NULL,
  `valor` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `calzones_tamanhos`
--

INSERT INTO `calzones_tamanhos` (`id`, `nome`, `tamanho_cm`, `num_sabores`, `valor`) VALUES
(1, 'Normal', '', 1, 30);

-- --------------------------------------------------------

--
-- Table structure for table `cidades`
--

CREATE TABLE `cidades` (
  `id` varchar(15) NOT NULL,
  `nome` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cidades`
--

INSERT INTO `cidades` (`id`, `nome`) VALUES
('mafra', 'Mafra'),
('rio_negro', 'Rio Negro');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `chave` varchar(200) NOT NULL,
  `valor` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` bigint(20) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0-em aberto / 1-finalizado / 2-cancelado / 3-em producao',
  `valor_total` double NOT NULL,
  `tipo_entrega` varchar(10) NOT NULL,
  `valor_entrega` double NOT NULL DEFAULT '0',
  `tipo_pagamento` varchar(10) DEFAULT NULL,
  `troco_para` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `data_pedido`, `status`, `valor_total`, `tipo_entrega`, `valor_entrega`, `tipo_pagamento`, `troco_para`) VALUES
(89, 22, '2016-05-24 00:19:25', 1, 110, 'entregar', 3, 'Dinheiro', 114),
(90, 22, '2016-05-24 01:11:49', 2, 70, 'retirar', 0, NULL, NULL),
(91, 22, '2016-05-24 15:57:53', 3, 122, 'entregar', 3, 'Dinheiro', 126),
(92, 22, '2016-05-25 18:24:16', 0, 35, 'entregar', 3, 'Dinheiro', 39),
(93, 22, '2016-05-27 14:15:52', 0, 88, 'retirar', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos_bebidas`
--

CREATE TABLE `pedidos_bebidas` (
  `id` bigint(20) NOT NULL,
  `id_pedido` bigint(20) NOT NULL,
  `id_bebida` int(11) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `valor` double NOT NULL,
  `subtotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pedidos_bebidas`
--

INSERT INTO `pedidos_bebidas` (`id`, `id_pedido`, `id_bebida`, `quantidade`, `valor`, `subtotal`) VALUES
(1, 91, 3, 2, 3.5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos_calzones`
--

CREATE TABLE `pedidos_calzones` (
  `id` bigint(20) NOT NULL,
  `id_pedido` bigint(20) NOT NULL,
  `id_tamanho` int(11) DEFAULT NULL,
  `sabores` longtext NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT '1',
  `valor` double NOT NULL,
  `subtotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pedidos_calzones`
--

INSERT INTO `pedidos_calzones` (`id`, `id_pedido`, `id_tamanho`, `sabores`, `quantidade`, `valor`, `subtotal`) VALUES
(21, 89, 1, '[{"id":"1","nome":"Calabresa","descricao":"Queijo mussarela e calabresa\\r\\n","id_categoria":"1"}]', 1, 30, 30),
(22, 91, 1, '[{"id":"1","nome":"Calabresa","descricao":"Queijo mussarela e calabresa\\r\\n","id_categoria":"1"}]', 1, 30, 30),
(23, 93, 1, '[{"id":"1","nome":"Calabresa","descricao":"Queijo mussarela e calabresa\\r\\n","id_categoria":"1"}]', 2, 30, 60);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos_pizzas`
--

CREATE TABLE `pedidos_pizzas` (
  `id` bigint(20) NOT NULL,
  `id_pedido` bigint(20) NOT NULL,
  `id_tamanho` int(11) DEFAULT NULL,
  `sabores` longtext NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT '1',
  `valor` double NOT NULL,
  `subtotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pedidos_pizzas`
--

INSERT INTO `pedidos_pizzas` (`id`, `id_pedido`, `id_tamanho`, `sabores`, `quantidade`, `valor`, `subtotal`) VALUES
(48, 89, 2, '[{"id":"4","nome":"Alho e \\u00d3leo","descricao":"Queijo mussarela, alho e \\u00f3leo","id_categoria":"1"},{"id":"1","nome":"Calabresa","descricao":"Queijo mussarela e calabresa\\r\\n","id_categoria":"1"}]', 2, 35, 70),
(49, 89, 4, '[{"id":"3","nome":"Chocolate","descricao":null,"id_categoria":"2"}]', 1, 10, 10),
(50, 90, 2, '[{"id":"4","nome":"Alho e \\u00d3leo","descricao":"Queijo mussarela, alho e \\u00f3leo","id_categoria":"1"},{"id":"5","nome":"Portuguesa","descricao":null,"id_categoria":"1"}]', 2, 35, 70),
(51, 91, 2, '[{"id":"4","nome":"Alho e \\u00d3leo","descricao":"Queijo mussarela, alho e \\u00f3leo","id_categoria":"1"}]', 1, 35, 35),
(52, 91, 4, '[{"id":"5","nome":"Portuguesa","descricao":null,"id_categoria":"1"}]', 5, 10, 50),
(53, 92, 2, '[{"id":"4","nome":"Alho e \\u00d3leo","descricao":"Queijo mussarela, alho e \\u00f3leo","id_categoria":"1"}]', 1, 35, 35),
(54, 93, 1, '[{"id":"4","nome":"Alho e \\u00d3leo","descricao":"Queijo mussarela, alho e \\u00f3leo","id_categoria":"1"}]', 1, 28, 28);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos_tmp`
--

CREATE TABLE `pedidos_tmp` (
  `id` bigint(20) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pedido` longtext NOT NULL COMMENT 'JSON com pedido completo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tabela temporária de pedidos';

--
-- Dumping data for table `pedidos_tmp`
--

INSERT INTO `pedidos_tmp` (`id`, `id_usuario`, `data_pedido`, `pedido`) VALUES
(46, 22, '2016-05-30 18:23:17', '""'),
(47, NULL, '2016-05-30 18:31:12', '""');

-- --------------------------------------------------------

--
-- Table structure for table `pizzaria`
--

CREATE TABLE `pizzaria` (
  `id` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cidade` varchar(30) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `rua` varchar(50) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `cep` varchar(12) NOT NULL,
  `bairro` varchar(30) NOT NULL,
  `tempo_entrega` int(3) NOT NULL COMMENT 'tempo de entrega em minutos',
  `disp_dias` varchar(200) NOT NULL COMMENT 'dias disponiveis na semana (json)',
  `disp_horas` varchar(100) NOT NULL COMMENT 'horas  disponíveis (json)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pizzaria`
--

INSERT INTO `pizzaria` (`id`, `nome`, `cidade`, `uf`, `rua`, `numero`, `cep`, `bairro`, `tempo_entrega`, `disp_dias`, `disp_horas`) VALUES
(1, 'Zaz', 'Mafra', 'SC', 'Felipe Schmidt', '1010', '89300000', 'Centro', 20, '{   "dom": "true",   "seg": "false",   "ter": "true",   "qua": "true",   "qui": "true",   "sex": "true",   "sab": "true" }', '{   "inicio": "18",   "fim": "22:30" }');

-- --------------------------------------------------------

--
-- Table structure for table `pizzas_categorias`
--

CREATE TABLE `pizzas_categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pizzas_categorias`
--

INSERT INTO `pizzas_categorias` (`id`, `nome`, `descricao`) VALUES
(1, 'Salgadas', ''),
(2, 'Doces', '');

-- --------------------------------------------------------

--
-- Table structure for table `pizzas_sabores`
--

CREATE TABLE `pizzas_sabores` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` longtext,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pizzas_sabores`
--

INSERT INTO `pizzas_sabores` (`id`, `nome`, `descricao`, `id_categoria`) VALUES
(1, 'Calabresa', 'Queijo mussarela e calabresa\r\n', 1),
(2, 'Chocolate branco', NULL, 2),
(3, 'Chocolate', NULL, 2),
(4, 'Alho e Óleo', 'Queijo mussarela, alho e óleo', 1),
(5, 'Portuguesa', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pizzas_tamanhos`
--

CREATE TABLE `pizzas_tamanhos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `fatias` varchar(3) NOT NULL,
  `tamanho_cm` varchar(3) NOT NULL,
  `num_sabores` int(11) NOT NULL,
  `valor` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pizzas_tamanhos`
--

INSERT INTO `pizzas_tamanhos` (`id`, `nome`, `fatias`, `tamanho_cm`, `num_sabores`, `valor`) VALUES
(1, 'Média', '12', '35', 3, 28),
(2, 'Grande', '16', '40', 3, 35),
(3, 'Pequena', '8', '25', 2, 20),
(4, 'Mini', '1', '10', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `recupera_senha`
--

CREATE TABLE `recupera_senha` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(32) NOT NULL,
  `data_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rua_refs`
--

CREATE TABLE `rua_refs` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `valor` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rua_refs`
--

INSERT INTO `rua_refs` (`id`, `nome`, `valor`) VALUES
(1, 'Tenente Ary Rauen', 3);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(70) NOT NULL,
  `senha` longtext NOT NULL,
  `confirmado` tinyint(1) NOT NULL DEFAULT '0',
  `fone` varchar(20) NOT NULL,
  `cidade` varchar(15) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `rua` varchar(200) NOT NULL,
  `rua_ref` int(11) DEFAULT NULL COMMENT 'referencia de rua (obtem o valor da entrega)',
  `bairro` varchar(50) NOT NULL,
  `numero` varchar(30) NOT NULL,
  `ponto_referencia` varchar(100) DEFAULT NULL,
  `cpf` varchar(11) NOT NULL,
  `data_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hash` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `confirmado`, `fone`, `cidade`, `cep`, `rua`, `rua_ref`, `bairro`, `numero`, `ponto_referencia`, `cpf`, `data_add`, `hash`) VALUES
(22, 'William Marquardt', 'williammqt@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, '4797100436', 'mafra', '89300000', 'Tenente Ary Rauen', 1, 'Alto de Mafra', '1072', 'SENAI', '07457099905', '2016-04-19 10:24:14', '04462f19b34180d03c607c6b69dfeff7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bebidas`
--
ALTER TABLE `bebidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `bebidas_categorias`
--
ALTER TABLE `bebidas_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calzones_categorias`
--
ALTER TABLE `calzones_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calzones_sabores`
--
ALTER TABLE `calzones_sabores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `calzones_tamanhos`
--
ALTER TABLE `calzones_tamanhos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cidades`
--
ALTER TABLE `cidades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `pedidos_bebidas`
--
ALTER TABLE `pedidos_bebidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_bebida` (`id_bebida`);

--
-- Indexes for table `pedidos_calzones`
--
ALTER TABLE `pedidos_calzones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_tamanho` (`id_tamanho`);

--
-- Indexes for table `pedidos_pizzas`
--
ALTER TABLE `pedidos_pizzas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_tamanho` (`id_tamanho`);

--
-- Indexes for table `pedidos_tmp`
--
ALTER TABLE `pedidos_tmp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `pizzaria`
--
ALTER TABLE `pizzaria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pizzas_categorias`
--
ALTER TABLE `pizzas_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pizzas_sabores`
--
ALTER TABLE `pizzas_sabores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `pizzas_tamanhos`
--
ALTER TABLE `pizzas_tamanhos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recupera_senha`
--
ALTER TABLE `recupera_senha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `rua_refs`
--
ALTER TABLE `rua_refs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rua_ref` (`rua_ref`),
  ADD KEY `cidade` (`cidade`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `bebidas`
--
ALTER TABLE `bebidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `bebidas_categorias`
--
ALTER TABLE `bebidas_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `calzones_categorias`
--
ALTER TABLE `calzones_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `calzones_sabores`
--
ALTER TABLE `calzones_sabores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `calzones_tamanhos`
--
ALTER TABLE `calzones_tamanhos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT for table `pedidos_bebidas`
--
ALTER TABLE `pedidos_bebidas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pedidos_calzones`
--
ALTER TABLE `pedidos_calzones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `pedidos_pizzas`
--
ALTER TABLE `pedidos_pizzas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `pedidos_tmp`
--
ALTER TABLE `pedidos_tmp`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `pizzaria`
--
ALTER TABLE `pizzaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pizzas_categorias`
--
ALTER TABLE `pizzas_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pizzas_sabores`
--
ALTER TABLE `pizzas_sabores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `pizzas_tamanhos`
--
ALTER TABLE `pizzas_tamanhos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `recupera_senha`
--
ALTER TABLE `recupera_senha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `rua_refs`
--
ALTER TABLE `rua_refs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bebidas`
--
ALTER TABLE `bebidas`
  ADD CONSTRAINT `bebidas_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `bebidas_categorias` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `calzones_sabores`
--
ALTER TABLE `calzones_sabores`
  ADD CONSTRAINT `calzones_sabores_fgk1` FOREIGN KEY (`id_categoria`) REFERENCES `calzones_categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `pedidos_bebidas`
--
ALTER TABLE `pedidos_bebidas`
  ADD CONSTRAINT `pedidos_bebidas_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_bebidas_ibfk_2` FOREIGN KEY (`id_bebida`) REFERENCES `bebidas` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `pedidos_calzones`
--
ALTER TABLE `pedidos_calzones`
  ADD CONSTRAINT `pedidos_calzones_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_calzones_ibfk_2` FOREIGN KEY (`id_tamanho`) REFERENCES `calzones_tamanhos` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `pedidos_pizzas`
--
ALTER TABLE `pedidos_pizzas`
  ADD CONSTRAINT `pedidos_pizzas_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_pizzas_ibfk_2` FOREIGN KEY (`id_tamanho`) REFERENCES `pizzas_tamanhos` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `pedidos_tmp`
--
ALTER TABLE `pedidos_tmp`
  ADD CONSTRAINT `pedidos_tmp_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `pizzas_sabores`
--
ALTER TABLE `pizzas_sabores`
  ADD CONSTRAINT `pizzas_sabores_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `pizzas_categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recupera_senha`
--
ALTER TABLE `recupera_senha`
  ADD CONSTRAINT `recupera_senha_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rua_ref`) REFERENCES `rua_refs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`cidade`) REFERENCES `cidades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
