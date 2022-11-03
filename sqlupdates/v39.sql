ALTER TABLE `attributes` CHANGE `name` `name_en` VARCHAR(255) CHARACTER SET utf32 COLLATE utf32_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `attributes` ADD `name_ar` VARCHAR(255) NOT NULL AFTER `name_en`;
ALTER TABLE `customer_products` CHANGE `name` `name_en` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `description` `description_en` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `meta_title` `meta_title_en` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `meta_description` `meta_description_en` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `slug` `slug_en` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `customer_products` ADD `name_ar` VARCHAR(255) NULL DEFAULT NULL AFTER `slug_en`, ADD `description_ar` LONGTEXT NULL DEFAULT NULL AFTER `name_ar`, ADD `slug_ar` VARCHAR(255) NULL DEFAULT NULL AFTER `description_ar`, ADD `meta_title_ar` VARCHAR(255) NULL DEFAULT NULL AFTER `slug_ar`, ADD `meta_description_ar` VARCHAR(255) NULL DEFAULT NULL AFTER `meta_title_ar`;



CREATE TABLE `model_setting` (
  `id` int(11) NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_ar` text NOT NULL,
  `description_en` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `model_setting` (`id`, `name_ar`, `name_en`, `description_ar`, `description_en`, `status`, `created_at`, `updated_at`) VALUES
(1, 'العنوااااااااااان', 'title', 'لديك كوبون خصم   12345', 'coboun number 123456', 1, '2020-10-16 00:44:43', '2020-10-15 22:46:27');


ALTER TABLE `model_setting`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `model_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
