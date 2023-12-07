ALTER TABLE `banners` ADD `order` integer  DEFAULT 0 AFTER `updated_at`;
COMMIT;