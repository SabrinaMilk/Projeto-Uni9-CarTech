CREATE TABLE usuarios_empresa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_empresa VARCHAR(255) NOT NULL,
    nome_proprietario VARCHAR(255) NOT NULL,
    tipo_documento ENUM('CPF', 'CNPJ') NOT NULL,
    documento VARCHAR(20) NOT NULL UNIQUE,
    cep VARCHAR(10) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    complemento VARCHAR(255),
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    quantidade_usuarios INT DEFAULT 1,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    data_cadastro DATETIME NOT NULL
);

ALTER TABLE usuarios_empresa 
DROP COLUMN IF EXISTS senha_temporaria, 
DROP COLUMN IF EXISTS primeiro_acesso;

ALTER TABLE usuarios_empresa 
ADD COLUMN senha_temporaria VARCHAR(255) DEFAULT '' AFTER senha,
ADD COLUMN primeiro_acesso BOOLEAN DEFAULT TRUE AFTER senha_temporaria;

-- Verificar estrutura atual da tabela
DESCRIBE usuarios_empresa;

-- Adicionar colunas uma por uma (se a anterior falhar)
ALTER TABLE usuarios_empresa ADD COLUMN token_recuperacao VARCHAR(100) DEFAULT NULL;
ALTER TABLE usuarios_empresa ADD COLUMN token_expiracao DATETIME DEFAULT NULL;