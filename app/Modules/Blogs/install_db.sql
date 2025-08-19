-- ----------------------------
--  Create structure for `task`
--  Use [DB_PREFIX] in front of the tables to implement the default database prefix.
-- ----------------------------
CREATE TABLE IF NOT EXISTS `[DB_PREFIX]blogs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `blogImageID` INT(11) DEFAULT NULL,
    `url` VARCHAR(255) DEFAULT NULL,
    `title` VARCHAR(255) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `location` VARCHAR(255) DEFAULT NULL,
    `category_id` INT(11) DEFAULT NULL,
    `rank` INT(4) DEFAULT NULL,
    `isActive` TINYINT(1) DEFAULT 1,
    `isFront` TINYINT(1) DEFAULT 0,
    `data_lang` VARCHAR(5) DEFAULT NULL,
    `seoKeywords` TEXT DEFAULT NULL,
    `seoDesc` TEXT DEFAULT NULL,
    `picturePrice` VARCHAR(255) DEFAULT NULL,
    `year` VARCHAR(50) DEFAULT NULL,
    `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `createdUser` VARCHAR(255) DEFAULT NULL,
    `lastUpdatedUser` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- blogImages table
CREATE TABLE IF NOT EXISTS `[DB_PREFIX]blogCategories ` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `blogID` INT(11) DEFAULT NULL,
    `title` VARCHAR(255) DEFAULT NULL,
    `url` VARCHAR(255) DEFAULT NULL,
    `isActive` TINYINT(1) DEFAULT 1,
    `rank` INT(4) DEFAULT NULL,
    `data_lang` VARCHAR(5) DEFAULT NULL,
    `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `createdUser` VARCHAR(255) DEFAULT NULL,
    `lastUpdatedUser` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE IF NOT EXISTS `[DB_PREFIX]blog_images` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `blog_id` INT(11) DEFAULT NULL,
    `img_url` VARCHAR(255) DEFAULT NULL,
    `rank` INT(4) DEFAULT NULL,
    `isActive` TINYINT(1) DEFAULT 1,
    `isCover` TINYINT(1) DEFAULT 0,
    `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
