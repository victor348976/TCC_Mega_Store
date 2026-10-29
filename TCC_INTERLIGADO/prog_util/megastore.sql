-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/10/2026 às 18:33
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
-- Banco de dados: `megastore`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_categoria`
--

CREATE TABLE `tb_categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome_categoria` varchar(255) NOT NULL,
  `descricao_categoria` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_categoria`
--

INSERT INTO `tb_categoria` (`id_categoria`, `nome_categoria`, `descricao_categoria`) VALUES
(1, 'Acessório', 'Produtos complementares utilizados para compor estilos, facilitar atividades do dia a dia ou agregar funcionalidade, como bolsas, cintos, bonés, carteiras e outros itens.'),
(2, 'Bermuda', 'Peças de vestuário com comprimento geralmente até os joelhos, disponíveis em diferentes modelos, materiais e estilos para diversas ocasiões.'),
(3, 'Body', 'Peça de vestuário que cobre o tronco e possui fechamento na parte inferior, podendo ser utilizada em diferentes combinações e estilos.'),
(4, 'Calça', 'Peça de vestuário que cobre as pernas, disponível em diferentes cortes, tecidos e modelos, atendendo a diversas necessidades e ocasiões.'),
(5, 'Calçado', 'Produtos utilizados para proteger e vestir os pés, incluindo tênis, sapatos, sandálias, botas, chinelos e outros modelos.'),
(6, 'Camisa', 'Peça de vestuário geralmente caracterizada por gola, abertura frontal e botões, disponível em diferentes tecidos e estilos para ocasiões casuais ou formais.'),
(7, 'Camiseta', 'Peça de vestuário geralmente confeccionada em malha, com mangas curtas ou longas, disponível em diferentes cores, estampas e modelos.'),
(8, 'Casaco', 'Peça utilizada para proteger o corpo do frio ou complementar o visual, incluindo jaquetas, blusas de frio, cardigãs e outros modelos.'),
(9, 'Conjunto', 'Conjunto de peças comercializadas em combinação, desenvolvidas para serem utilizadas juntas ou separadamente, conforme o modelo e a finalidade.'),
(10, 'Macacão', 'Peça única que combina a parte superior e inferior do vestuário, disponível em diferentes modelos, tecidos e estilos para diversas ocasiões.'),
(11, 'Roupa de cama', 'Produtos destinados a compor, proteger e proporcionar conforto à cama, incluindo lençóis, fronhas, colchas, edredons e outros artigos.'),
(12, 'Saia', 'Peça de vestuário que cobre a parte inferior do corpo, disponível em diferentes comprimentos, cortes, tecidos e estilos.'),
(13, 'Tecido', 'Materiais utilizados na confecção de roupas, acessórios, itens de decoração e outros produtos, disponíveis em diferentes composições, texturas e estampas.'),
(14, 'Vestido', 'Peça de vestuário que combina a parte superior e inferior em uma única estrutura, disponível em diferentes comprimentos, modelos e estilos para diversas ocasiões.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_endereco`
--

CREATE TABLE `tb_endereco` (
  `id_endereco` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` int(9) NOT NULL,
  `bairro` int(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `complemento` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_forma_pagamento`
--

CREATE TABLE `tb_forma_pagamento` (
  `id_forma_pagamento` int(11) NOT NULL,
  `nome_forma_pagamento` varchar(150) NOT NULL,
  `descricao_forma_pagamento` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `tb_forma_pagamento`
--

INSERT INTO `tb_forma_pagamento` (`id_forma_pagamento`, `nome_forma_pagamento`, `descricao_forma_pagamento`) VALUES
(1, 'Boleto', 'Método de pagamento realizado por meio de um boleto bancário, que pode ser pago em bancos, aplicativos financeiros ou estabelecimentos autorizados, conforme as condições de vencimento.'),
(2, 'Crédito', 'Método de pagamento realizado por cartão de crédito, permitindo que o valor da compra seja cobrado na fatura do cliente, com possibilidade de parcelamento conforme as condições oferecidas.'),
(3, 'Débito', 'Método de pagamento realizado por cartão de débito, no qual o valor da compra é descontado diretamente do saldo disponível na conta bancária do cliente.'),
(4, 'PIX', 'Método de pagamento eletrônico que permite transferências e pagamentos instantâneos, disponíveis a qualquer dia e horário, por meio de instituições financeiras participantes.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_genero`
--

CREATE TABLE `tb_genero` (
  `id_genero` int(11) NOT NULL,
  `nome_genero` varchar(255) NOT NULL,
  `descricao_genero` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_genero`
--

INSERT INTO `tb_genero` (`id_genero`, `nome_genero`, `descricao_genero`) VALUES
(1, 'Masculino', 'Produtos destinados ao público masculino.'),
(2, 'Feminino', 'Produtos destinados ao público feminino.'),
(3, 'Infantil', 'Produtos destinados ao público infantil.'),
(4, 'Unissex', 'Produtos destinados a todos os públicos, independentemente do gênero.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_imagem_produto`
--

CREATE TABLE `tb_imagem_produto` (
  `id_imagem` int(11) NOT NULL,
  `id_variacao` int(11) NOT NULL,
  `caminho_imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_pagamento`
--

CREATE TABLE `tb_pagamento` (
  `id_pagamento` int(11) NOT NULL,
  `id_venda` int(11) NOT NULL,
  `id_forma_pagamento` int(11) NOT NULL,
  `status_pagamento` tinyint(1) NOT NULL,
  `data_pagamento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_produto`
--

CREATE TABLE `tb_produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(255) NOT NULL,
  `descricao_produto` varchar(500) NOT NULL,
  `preco` double(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `data_cadastro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_produto_salvo`
--

CREATE TABLE `tb_produto_salvo` (
  `id_produto_salvo` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_produto_venda`
--

CREATE TABLE `tb_produto_venda` (
  `id_produto_venda` int(11) NOT NULL,
  `id_venda` int(11) NOT NULL,
  `id_variacao` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome_usuario` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` int(11) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `tipo` tinyint(1) NOT NULL,
  `data_cadastro` date NOT NULL,
  `modo_tela` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_variacao_produto`
--

CREATE TABLE `tb_variacao_produto` (
  `id_variacao` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `tamanho` varchar(5) NOT NULL,
  `cor` varchar(55) NOT NULL,
  `estoque` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_venda`
--

CREATE TABLE `tb_venda` (
  `id_venda` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_pedido` date NOT NULL,
  `status_venda` varchar(55) NOT NULL,
  `valor_total` double(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_categoria`
--
ALTER TABLE `tb_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `tb_endereco`
--
ALTER TABLE `tb_endereco`
  ADD PRIMARY KEY (`id_endereco`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `tb_forma_pagamento`
--
ALTER TABLE `tb_forma_pagamento`
  ADD PRIMARY KEY (`id_forma_pagamento`);

--
-- Índices de tabela `tb_genero`
--
ALTER TABLE `tb_genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Índices de tabela `tb_imagem_produto`
--
ALTER TABLE `tb_imagem_produto`
  ADD PRIMARY KEY (`id_imagem`),
  ADD KEY `id_produto` (`id_variacao`);

--
-- Índices de tabela `tb_pagamento`
--
ALTER TABLE `tb_pagamento`
  ADD PRIMARY KEY (`id_pagamento`),
  ADD KEY `id_venda` (`id_venda`),
  ADD KEY `forma_pagamento` (`id_forma_pagamento`);

--
-- Índices de tabela `tb_produto`
--
ALTER TABLE `tb_produto`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `id_genero` (`id_genero`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices de tabela `tb_produto_salvo`
--
ALTER TABLE `tb_produto_salvo`
  ADD PRIMARY KEY (`id_produto_salvo`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `tb_produto_venda`
--
ALTER TABLE `tb_produto_venda`
  ADD PRIMARY KEY (`id_produto_venda`),
  ADD KEY `id_venda` (`id_venda`),
  ADD KEY `id_variacao` (`id_variacao`);

--
-- Índices de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Índices de tabela `tb_variacao_produto`
--
ALTER TABLE `tb_variacao_produto`
  ADD PRIMARY KEY (`id_variacao`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `tb_venda`
--
ALTER TABLE `tb_venda`
  ADD PRIMARY KEY (`id_venda`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_categoria`
--
ALTER TABLE `tb_categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `tb_endereco`
--
ALTER TABLE `tb_endereco`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_forma_pagamento`
--
ALTER TABLE `tb_forma_pagamento`
  MODIFY `id_forma_pagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_genero`
--
ALTER TABLE `tb_genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_imagem_produto`
--
ALTER TABLE `tb_imagem_produto`
  MODIFY `id_imagem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_pagamento`
--
ALTER TABLE `tb_pagamento`
  MODIFY `id_pagamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_produto`
--
ALTER TABLE `tb_produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_produto_salvo`
--
ALTER TABLE `tb_produto_salvo`
  MODIFY `id_produto_salvo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_produto_venda`
--
ALTER TABLE `tb_produto_venda`
  MODIFY `id_produto_venda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_variacao_produto`
--
ALTER TABLE `tb_variacao_produto`
  MODIFY `id_variacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_venda`
--
ALTER TABLE `tb_venda`
  MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_endereco`
--
ALTER TABLE `tb_endereco`
  ADD CONSTRAINT `tb_endereco_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`);

--
-- Restrições para tabelas `tb_imagem_produto`
--
ALTER TABLE `tb_imagem_produto`
  ADD CONSTRAINT `tb_imagem_produto_ibfk_1` FOREIGN KEY (`id_variacao`) REFERENCES `tb_variacao_produto` (`id_variacao`);

--
-- Restrições para tabelas `tb_pagamento`
--
ALTER TABLE `tb_pagamento`
  ADD CONSTRAINT `tb_pagamento_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `tb_venda` (`id_venda`),
  ADD CONSTRAINT `tb_pagamento_ibfk_2` FOREIGN KEY (`id_forma_pagamento`) REFERENCES `tb_forma_pagamento` (`id_forma_pagamento`);

--
-- Restrições para tabelas `tb_produto`
--
ALTER TABLE `tb_produto`
  ADD CONSTRAINT `tb_produto_ibfk_1` FOREIGN KEY (`id_genero`) REFERENCES `tb_genero` (`id_genero`),
  ADD CONSTRAINT `tb_produto_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `tb_categoria` (`id_categoria`);

--
-- Restrições para tabelas `tb_produto_salvo`
--
ALTER TABLE `tb_produto_salvo`
  ADD CONSTRAINT `tb_produto_salvo_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `tb_produto` (`id_produto`),
  ADD CONSTRAINT `tb_produto_salvo_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`);

--
-- Restrições para tabelas `tb_produto_venda`
--
ALTER TABLE `tb_produto_venda`
  ADD CONSTRAINT `tb_produto_venda_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `tb_venda` (`id_venda`),
  ADD CONSTRAINT `tb_produto_venda_ibfk_2` FOREIGN KEY (`id_variacao`) REFERENCES `tb_variacao_produto` (`id_variacao`);

--
-- Restrições para tabelas `tb_variacao_produto`
--
ALTER TABLE `tb_variacao_produto`
  ADD CONSTRAINT `tb_variacao_produto_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `tb_produto` (`id_produto`);

--
-- Restrições para tabelas `tb_venda`
--
ALTER TABLE `tb_venda`
  ADD CONSTRAINT `tb_venda_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
