ALTER TABLE `sub_categories` ADD `name_en` VARCHAR(255) NOT NULL AFTER `slug`, ADD `meta_title_en` VARCHAR(255) NOT NULL AFTER `name_en`, ADD `meta_description_en` TEXT NULL DEFAULT NULL AFTER `meta_title_en`;
ALTER TABLE `sub_categories` CHANGE `name` `name_ar` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `meta_title` `meta_title_ar` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `meta_description` `meta_description_ar` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `sub_categories` CHANGE `meta_title_en` `meta_title_en` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `sub_sub_categories` CHANGE `name` `name_ar` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `meta_title` `meta_title_ar` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `meta_description` `meta_description_ar` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `sub_sub_categories`  ADD `name_en` VARCHAR(255) NOT NULL  AFTER `slug`,  ADD `meta_title_en` VARCHAR(255) NULL DEFAULT NULL  AFTER `name_en`,  ADD `meta_description_en` TEXT NULL DEFAULT NULL  AFTER `meta_title_en`;
ALTER TABLE `countries` ADD `name_ar` VARCHAR(255) NOT NULL AFTER `name`;
ALTER TABLE `countries` CHANGE `name` `name_en` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
update countries SET name_ar=name_en
ALTER TABLE `countries` ADD `shipping_cost` DOUBLE NOT NULL DEFAULT '0' AFTER `name_ar`;
