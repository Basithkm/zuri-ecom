ALTER TABLE `addresses` ADD `first_name` varchar(100)  AFTER `user_id`;
ALTER TABLE `addresses` ADD `last_name` varchar(100) NULL  AFTER `first_name`;
ALTER TABLE `addresses` ADD `address2` varchar(255) NULL  AFTER `address`;
ALTER TABLE `addresses` ADD `city` varchar(150)  AFTER `address2`;
ALTER TABLE `addresses` ADD `order_note` text NULL  AFTER `phone`;

COMMIT;