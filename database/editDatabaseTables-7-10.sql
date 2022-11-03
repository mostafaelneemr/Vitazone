ALTER TABLE `users` ADD `password_code` VARCHAR(100) NULL AFTER `city`;
ALTER TABLE `policies` CHANGE `content` `content_en` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `policies` ADD `content_ar` LONGTEXT NULL DEFAULT NULL AFTER `content_en`;
