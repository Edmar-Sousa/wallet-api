DROP TABLE IF EXISTS walletType;
DROP TABLE IF EXISTS wallet;

CREATE TABLE walletType(
    `id` INT PRIMARY KEY,
    `description` VARCHAR(200) NOT NULL
);

INSERT INTO `walletType`(`id`, `description`) VALUES (1, 'user'), (2, 'merchant');

CREATE TABLE IF NOT EXISTS wallet(
    `id` INT PRIMARY KEY AUTO_INCREMENT,

    `fullname` VARCHAR(255) NOT NULL,
    `cpfCnpj` VARCHAR(18) NOT NULL,
    `email` VARCHAR(150) NOT NULL,

    `password` VARCHAR(255) NOT NULL,

    `balance` BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `type` INT NOT NULL,

    CONSTRAINT `fk_wallet_type` FOREIGN KEY (`type`) REFERENCES walletType(`id`),
    CONSTRAINT `cpfCnpj_unique` UNIQUE(`cpfCnpj`),
    CONSTRAINT `email_unique` UNIQUE(`email`)
);

CREATE TABLE `transfer`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,

    `payer` INT NOT NULL,
    `payee` INT NOT NULL,

    `value` BIGINT UNSIGNED NOT NULL,

    CONSTRAINT `fk_wallet_payer` FOREIGN KEY (`payer`) REFERENCES wallet(`id`),
    CONSTRAINT `fk_wallet_payee` FOREIGN KEY (`payee`) REFERENCES wallet(`id`)
);
