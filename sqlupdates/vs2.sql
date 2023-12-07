ALTER TABLE `products` ADD `short_description` text  AFTER `description`;
ALTER TABLE `products` ADD `additional_info` text  AFTER `short_description`;

COMMIT;