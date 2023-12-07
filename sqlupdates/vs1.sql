ALTER TABLE `products` ADD `mrp` integer  DEFAULT 0 AFTER `unit_price`;

COMMIT;