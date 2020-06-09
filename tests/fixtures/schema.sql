CREATE TABLE `product` (
    `product_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `sku` VARCHAR(255) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`product_id`),
    UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB;

CREATE TABLE `product_int` (
    `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT UNSIGNED NOT NULL,
    `attribute_id` INT UNSIGNED NOT NULL,
    `value` INT,
    PRIMARY KEY (`value_id`),
    UNIQUE KEY (`product_id`, `attribute_id`)
) ENGINE=InnoDB;

CREATE TABLE `product_decimal` (
   `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
   `product_id` INT UNSIGNED NOT NULL,
   `attribute_id` INT UNSIGNED NOT NULL,
   `value` DECIMAL(12,4),
   PRIMARY KEY (`value_id`),
   UNIQUE KEY (`product_id`, `attribute_id`)
) ENGINE=InnoDB;

CREATE TABLE `product_varchar` (
   `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
   `product_id` INT UNSIGNED NOT NULL,
   `attribute_id` INT UNSIGNED NOT NULL,
   `value` VARCHAR(255),
   PRIMARY KEY (`value_id`),
   UNIQUE KEY (`product_id`, `attribute_id`)
) ENGINE=InnoDB;

CREATE TABLE `product_text` (
    `value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT UNSIGNED NOT NULL,
    `attribute_id` INT UNSIGNED NOT NULL,
    `value` TEXT,
    PRIMARY KEY (`value_id`),
    UNIQUE KEY (`product_id`, `attribute_id`)
) ENGINE=InnoDB;
