-- ksf_FA_BankAdmin: bank account master table

CREATE TABLE IF NOT EXISTS `0_ksf_bank_accounts` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `account_number` VARCHAR(50)  NOT NULL DEFAULT '',
  `account_name`   VARCHAR(100) NOT NULL DEFAULT '',
  `account_type`   VARCHAR(30)  NOT NULL DEFAULT '' COMMENT 'Ref code to 0_ksf_code_master.code_type = bank_account_type',
  `balance`        DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `currency`       VARCHAR(3)   NOT NULL DEFAULT 'USD',
  `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_number` (`account_number`),
  KEY `idx_account_type` (`account_type`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `0_ksf_bank_transactions` (
  `id`              INT(11) NOT NULL AUTO_INCREMENT,
  `account_id`      INT(11) NOT NULL COMMENT 'FK to 0_ksf_bank_accounts.id',
  `transaction_number` VARCHAR(50)  NOT NULL DEFAULT '',
  `transaction_type`  VARCHAR(30)  NOT NULL DEFAULT '' COMMENT 'Ref code to 0_ksf_code_master.code_type = bank_transaction_type',
  `amount`            DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `transaction_date`  DATE NOT NULL,
  `reference`         VARCHAR(100) DEFAULT NULL,
  `status`            VARCHAR(20)  NOT NULL DEFAULT 'pending' COMMENT 'pending|cleared|reconciled|voided',
  `created_at`        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_transaction_number` (`transaction_number`),
  KEY `idx_account_id` (`account_id`),
  KEY `idx_transaction_type` (`transaction_type`),
  KEY `idx_transaction_date` (`transaction_date`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
