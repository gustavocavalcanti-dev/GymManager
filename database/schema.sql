CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `perfil` ENUM('admin','professor','recepcionista') NOT NULL DEFAULT 'recepcionista',
    `ativo` TINYINT(1) NOT NULL DEFAULT 1,
    `ultimo_login` TIMESTAMP NULL DEFAULT NULL,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `professores` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `telefone` VARCHAR(20) NULL,
    `especialidade` VARCHAR(100) NULL,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `planos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `descricao` TEXT NULL,
    `valor` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `duracao_meses` INT NOT NULL DEFAULT 1,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `alunos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `telefone` VARCHAR(20) NULL,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `matriculas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `aluno_id` INT NOT NULL,
    `plano_id` INT NOT NULL,
    `data_inicio` DATE NOT NULL,
    `data_fim` DATE NOT NULL,
    `status` ENUM('ativa','cancelada','expirada') NOT NULL DEFAULT 'ativa',
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`aluno_id`) REFERENCES `alunos`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`plano_id`) REFERENCES `planos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pagamentos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `matricula_id` INT NOT NULL,
    `valor` DECIMAL(10,2) NOT NULL,
    `data_pagamento` DATE NOT NULL,
    `forma_pagamento` ENUM('dinheiro','cartao_credito','cartao_debito','pix','boleto') NOT NULL DEFAULT 'dinheiro',
    `status` ENUM('pago','pendente','cancelado') NOT NULL DEFAULT 'pendente',
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`matricula_id`) REFERENCES `matriculas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `treinos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `aluno_id` INT NOT NULL,
    `professor_id` INT NOT NULL,
    `descricao` TEXT NOT NULL,
    `dia_semana` VARCHAR(20) NULL,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`aluno_id`) REFERENCES `alunos`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`professor_id`) REFERENCES `professores`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `usuarios` (`nome`, `email`, `senha`, `perfil`, `ativo`) VALUES
('Administrador', 'admin@gymmanager.com', '$2y$10$z3x68wzC46IKp0r2TvEki.FcN/G0eF1xfNrSvtCF1J0SQ1YaU3yvS', 'admin', 1);
