ALTER TABLE `customer_products` CHANGE `tags` `tags_en` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `customer_products` ADD `tags_ar` VARCHAR(255) NULL DEFAULT NULL AFTER `tags_en`;
ALTER TABLE `banners` CHANGE `url` `url_en` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `banners` ADD `url_ar` VARCHAR(1000) NULL DEFAULT NULL AFTER `url_en`;
