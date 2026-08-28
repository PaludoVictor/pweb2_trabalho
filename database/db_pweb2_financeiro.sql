-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           MySQL 8.0 / MariaDB
-- Banco de Dados:               db_pweb2_financeiro
-- Sistema:                      FinanSys - Gestão Financeira (PWEB2)
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `db_pweb2_financeiro` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_pweb2_financeiro`;

-- --------------------------------------------------------
-- Estrutura da tabela `contas`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `transacoes`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `contas`;
DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `contas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome_instituicao` varchar(100) NOT NULL,
  `agencia_numero` varchar(20) NOT NULL,
  `numero_conta` varchar(30) NOT NULL,
  `saldo_atual` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contas_numero_conta_unique` (`numero_conta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contas` (`id`, `nome_instituicao`, `agencia_numero`, `numero_conta`, `saldo_atual`, `created_at`, `updated_at`) VALUES
(1, 'Banco do Brasil', '1234', '5678-9', 4500.00, NOW(), NOW()),
(2, 'Itaú Unibanco', '0002', '123123-1', 8200.50, NOW(), NOW()),
(3, 'Nubank', '0001', '987654-3', 3150.75, NOW(), NOW()),
(4, 'Inter', '0001', '554433-2', 12300.00, NOW(), NOW());

-- --------------------------------------------------------
-- Estrutura da tabela `categorias`
-- --------------------------------------------------------
CREATE TABLE `categorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(100) NOT NULL,
  `tipo_despesa` varchar(50) NOT NULL,
  `limite_orcamento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cor_identificacao` varchar(20) NOT NULL DEFAULT '#10b981',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categorias` (`id`, `nome_categoria`, `tipo_despesa`, `limite_orcamento`, `cor_identificacao`, `created_at`, `updated_at`) VALUES
(1, 'Alimentação', 'Variável', 1200.00, '#10b981', NOW(), NOW()),
(2, 'Moradia & Contas', 'Fixa', 2000.00, '#3b82f6', NOW(), NOW()),
(3, 'Transporte', 'Variável', 600.00, '#f59e0b', NOW(), NOW()),
(4, 'Estudos e Cursos', 'Fixa', 800.00, '#8b5cf6', NOW(), NOW()),
(5, 'Lazer & Cultura', 'Variável', 500.00, '#ec4899', NOW(), NOW());

-- --------------------------------------------------------
-- Estrutura da tabela `transacoes`
-- --------------------------------------------------------
CREATE TABLE `transacoes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `descricao_movimento` varchar(150) NOT NULL,
  `valor_transacao` decimal(10,2) NOT NULL,
  `data_competencia` date NOT NULL,
  `metodo_pagamento` varchar(50) NOT NULL,
  `conta_id` bigint unsigned NOT NULL,
  `categoria_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transacoes_conta_id_foreign` (`conta_id`),
  KEY `transacoes_categoria_id_foreign` (`categoria_id`),
  CONSTRAINT `transacoes_conta_id_foreign` FOREIGN KEY (`conta_id`) REFERENCES `contas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transacoes_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transacoes` (`id`, `descricao_movimento`, `valor_transacao`, `data_competencia`, `metodo_pagamento`, `conta_id`, `categoria_id`, `created_at`, `updated_at`) VALUES
(1, 'Supermercado Central', 450.80, CURDATE() - INTERVAL 5 DAY, 'Cartão de Débito', 1, 1, NOW(), NOW()),
(2, 'Aluguel Apartamento', 1500.00, CURDATE() - INTERVAL 10 DAY, 'Pix', 2, 2, NOW(), NOW()),
(3, 'Livros Técnicos Laravel', 189.90, CURDATE() - INTERVAL 2 DAY, 'Pix', 1, 4, NOW(), NOW()),
(4, 'Cinema e Lanche', 95.00, CURDATE() - INTERVAL 1 DAY, 'Cartão de Crédito', 2, 5, NOW(), NOW());

-- --------------------------------------------------------
-- Estrutura da tabela `usuarios`
-- --------------------------------------------------------
CREATE TABLE `usuarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`),
  UNIQUE KEY `usuarios_login_unique` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `nome`, `telefone`, `email`, `login`, `senha`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', '(49) 99999-9999', 'admin@finansys.com', 'admin', '123', NOW(), NOW()),
(2, 'João Silva', '(49) 98888-8888', 'joao@finansys.com', 'joao', '123456', NOW(), NOW());

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
