ALTER TABLE `usuarios` ADD COLUMN IF NOT EXISTS `perfil` ENUM('admin','professor','recepcionista') NOT NULL DEFAULT 'recepcionista' AFTER `senha`;

ALTER TABLE `usuarios` ADD COLUMN IF NOT EXISTS `ativo` TINYINT(1) NOT NULL DEFAULT 1 AFTER `perfil`;

ALTER TABLE `usuarios` ADD COLUMN IF NOT EXISTS `ultimo_login` TIMESTAMP NULL DEFAULT NULL AFTER `ativo`;

ALTER TABLE `matriculas` ADD COLUMN IF NOT EXISTS `status` ENUM('ativa','cancelada','expirada') NOT NULL DEFAULT 'ativa' AFTER `data_fim`;

ALTER TABLE `pagamentos` ADD COLUMN IF NOT EXISTS `forma_pagamento` ENUM('dinheiro','cartao_credito','cartao_debito','pix','boleto') NOT NULL DEFAULT 'dinheiro' AFTER `data_pagamento`;

ALTER TABLE `treinos` ADD COLUMN IF NOT EXISTS `dia_semana` VARCHAR(20) NULL AFTER `descricao`;

INSERT IGNORE INTO `usuarios` (`nome`, `email`, `senha`, `perfil`, `ativo`) VALUES
('Administrador', 'admin@gymmanager.com', '$2y$10$z3x68wzC46IKp0r2TvEki.FcN/G0eF1xfNrSvtCF1J0SQ1YaU3yvS', 'admin', 1);
