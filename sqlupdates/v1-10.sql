ALTER TABLE `flash_deals` CHANGE `title` `title_en` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `flash_deals` ADD `title_ar` VARCHAR(255) NULL DEFAULT NULL AFTER `title_en`;
ALTER TABLE `flash_deals` CHANGE `slug` `slug_en` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `flash_deals` ADD `slug_ar` TEXT NULL DEFAULT NULL AFTER `slug_en`;
ALTER TABLE `currencies` CHANGE `name` `name_en` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `currencies` ADD `name_ar` VARCHAR(255) NOT NULL AFTER `name_en`;
ALTER TABLE `languages` CHANGE `name` `name_en` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `languages` ADD `name_ar` VARCHAR(255) NOT NULL AFTER `name_en`;
