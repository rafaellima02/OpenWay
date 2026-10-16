-- OpenWay: estrutura do banco
-- Importe este arquivo no MySQL (phpMyAdmin > Importar, ou: mysql -u root < database/schema.sql)

CREATE DATABASE IF NOT EXISTS openway
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE openway;

-- Feedback sobre uma rota (tela "Cadastro do feedback", issue #2)
CREATE TABLE IF NOT EXISTS feedbacks (
  id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  rota_id           INT UNSIGNED NOT NULL,
  nota              TINYINT UNSIGNED NOT NULL,            -- 1 a 5 estrelas
  pontos_positivos  JSON NULL,                            -- ex.: ["rampa","calcada"]
  pontos_melhorar   JSON NULL,                            -- ex.: ["sinalizacao"]
  comentario        TEXT NULL,                            -- até 500 caracteres
  foto              VARCHAR(100) NULL,                    -- nome do arquivo em storage/uploads/feedback
  created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_feedbacks_rota (rota_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
