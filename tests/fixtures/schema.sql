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

DELIMITER //

CREATE PROCEDURE all_product_data(
    IN filter_sku VARCHAR(255)
)
BEGIN
    SELECT sku,type FROM product WHERE sku LIKE filter_sku;
    SELECT p.sku,pd.attribute_id,pd.value FROM product p INNER JOIN product_int pd ON pd.product_id = p.product_id WHERE p.sku LIKE filter_sku;
    SELECT p.sku,pd.attribute_id,pd.value FROM product p INNER JOIN product_decimal pd ON pd.product_id = p.product_id WHERE p.sku LIKE filter_sku;
    SELECT p.sku,pd.attribute_id,pd.value FROM product p INNER JOIN product_varchar pd ON pd.product_id = p.product_id WHERE p.sku LIKE filter_sku;
    SELECT p.sku,pd.attribute_id,pd.value FROM product p INNER JOIN product_text pd ON pd.product_id = p.product_id WHERE p.sku LIKE filter_sku;
END //

DELIMITER ;