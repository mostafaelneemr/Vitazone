ALTER TABLE `brands` CHANGE `name` `name_ar` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL, CHANGE `meta_title` `meta_title_ar` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_520_ci NULL DEFAULT NULL, CHANGE `meta_description` `meta_description_ar` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_520_ci NULL DEFAULT NULL;
ALTER TABLE `brands` ADD `name_en` INT(50) NOT NULL AFTER `meta_description_ar`, ADD `meta_title_en` VARCHAR(255) NULL DEFAULT NULL AFTER `name_en`, ADD `meta_description_en` TEXT NULL DEFAULT NULL AFTER `meta_title_en`;
ALTER TABLE `brands` CHANGE `name_en` `name_en` VARCHAR(50) NOT NULL;


ALTER TABLE `categories` CHANGE `name` `name_ar` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `meta_title` `meta_title_ar` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `meta_description` `meta_description_ar` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `categories`  ADD `name_en` VARCHAR(100) NOT NULL  AFTER `meta_title_ar`,  ADD `meta_title_en` VARCHAR(255) NULL DEFAULT NULL  AFTER `name_en`,  ADD `meta_description_en` TEXT NULL DEFAULT NULL  AFTER `meta_title_en`;
