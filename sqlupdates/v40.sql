ALTER TABLE `general_settings` ADD `watermark_en` VARCHAR(255) NULL DEFAULT NULL AFTER `id`, ADD `watermark_ar` VARCHAR(255) NULL DEFAULT NULL AFTER `watermark_en`;
ALTER TABLE `sliders` CHANGE `link` `link_en` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `sliders` ADD `link_ar` VARCHAR(255) NULL DEFAULT NULL AFTER `link_en`;
ALTER TABLE `links` CHANGE `url` `url_en` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `links` ADD `url_ar` VARCHAR(255) NOT NULL AFTER `url_en`;

ALTER TABLE `products` CHANGE `tags` `tags_en` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `products` ADD `tags_ar` MEDIUMTEXT NULL DEFAULT NULL AFTER `tags_en`;
