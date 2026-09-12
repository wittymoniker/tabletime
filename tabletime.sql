SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `friends` longtext NOT NULL,
  `posts` longtext NOT NULL,
  `groups` longtext NOT NULL,
  `events` longtext NOT NULL,
  `forums` longtext NOT NULL,
  `tags` longtext NOT NULL,
  `messages` longtext NOT NULL,
  `colors` longtext NOT NULL,
  `votes` longtext NOT NULL,
  `files` longtext NOT NULL,
  `hostips` longtext NOT NULL,
  `hostmode` int NOT NULL DEFAULT 0,
  `aboutcontent` longtext NOT NULL,
  `delay` time NOT NULL DEFAULT '00:00:00',
  `ip` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_at` datetime DEFAULT NULL,
  `protected_account` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_accounts_username` (`username`),
  KEY `idx_accounts_hostmode` (`hostmode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `events` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `members` longtext NOT NULL,
  `about` longtext NOT NULL,
  `groups` longtext NOT NULL,
  `posts` longtext NOT NULL,
  `tags` longtext NOT NULL,
  `type` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_events_title` (`title`),
  KEY `idx_events_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `forums` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(191) NOT NULL,
  `posts` longtext NOT NULL,
  `groups` longtext NOT NULL,
  `events` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_forums_tag` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `about` longtext NOT NULL,
  `members` longtext NOT NULL,
  `forums` longtext NOT NULL,
  `events` longtext NOT NULL,
  `posts` longtext NOT NULL,
  `tags` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_groups_title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `dt` datetime NOT NULL,
  `file` varchar(1024) NOT NULL,
  `tags` longtext NOT NULL,
  `name` varchar(50) NOT NULL,
  `comments` longtext NOT NULL,
  `scope` varchar(32) NOT NULL,
  `recipients` longtext NOT NULL,
  `type` varchar(32) NOT NULL,
  `votes` longtext NOT NULL,
  `pinned` tinyint(1) NOT NULL DEFAULT 0,
  `origin_host` varchar(255) DEFAULT NULL,
  `origin_post_id` varchar(64) DEFAULT NULL,
  `origin_created_at` datetime DEFAULT NULL,
  `federated` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_posts_origin` (`origin_host`,`origin_post_id`),
  KEY `idx_posts_dt` (`dt`),
  KEY `idx_posts_name` (`name`),
  KEY `idx_posts_scope` (`scope`),
  KEY `idx_posts_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `post_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `mime_type` varchar(191) NOT NULL DEFAULT 'application/octet-stream',
  `size_bytes` int unsigned NOT NULL DEFAULT 0,
  `data` mediumblob NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_post_attachments_post` (`post_id`),
  KEY `idx_post_attachments_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tags` (
  `value` varchar(191) NOT NULL,
  `posts` longtext NOT NULL,
  `forums` longtext NOT NULL,
  `events` longtext NOT NULL,
  `groups` longtext NOT NULL,
  PRIMARY KEY (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `post_tags` (
  `tag` varchar(191) NOT NULL,
  `post_id` int unsigned NOT NULL,
  PRIMARY KEY (`tag`,`post_id`),
  KEY `idx_post_tags_post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `tag_index` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int unsigned NOT NULL,
  `tag` varchar(191) NOT NULL,
  `raw_token` varchar(255) NOT NULL,
  `is_numeric` tinyint(1) NOT NULL DEFAULT 0,
  `polarity` tinyint NOT NULL DEFAULT 1,
  `comparator` varchar(2) NOT NULL DEFAULT '',
  `numeric_value` double DEFAULT NULL,
  `expression_text` varchar(191) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_tag_index_tag` (`tag`),
  KEY `idx_tag_index_post` (`post_id`),
  KEY `idx_tag_index_numeric` (`tag`,`is_numeric`,`numeric_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `federation_peers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `base_url` varchar(512) NOT NULL,
  `tier` varchar(16) NOT NULL DEFAULT 'host',
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `cursor_at` datetime DEFAULT NULL,
  `last_sync_at` datetime DEFAULT NULL,
  `last_error` varchar(512) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_federation_peer_url` (`base_url`(191)),
  KEY `idx_federation_due` (`enabled`,`last_sync_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `federation_tombstones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `origin_host` varchar(255) DEFAULT NULL,
  `origin_post_id` varchar(64) NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tombstone_deleted` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `node_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(2048) NOT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `auth_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int unsigned NOT NULL,
  `selector` char(24) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_used_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `revoked_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `uq_auth_selector` (`selector`), KEY `idx_auth_account` (`account_id`,`revoked_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `calls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT, `call_key` char(32) NOT NULL, `scope_type` varchar(16) NOT NULL DEFAULT 'private',
  `scope_id` int unsigned DEFAULT NULL, `created_by` int unsigned NOT NULL, `title` varchar(255) NOT NULL DEFAULT '', `members` longtext NULL,
  `status` varchar(16) NOT NULL DEFAULT 'open', `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), UNIQUE KEY `uq_calls_key` (`call_key`), KEY `idx_calls_scope` (`scope_type`,`scope_id`,`status`), KEY `idx_calls_updated` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `call_participants` (
  `call_id` bigint unsigned NOT NULL, `account_id` int unsigned NOT NULL, `username` varchar(50) NOT NULL DEFAULT '',
  `joined_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `last_seen_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `left_at` datetime DEFAULT NULL,
  PRIMARY KEY (`call_id`,`account_id`), KEY `idx_call_participants_seen` (`call_id`,`left_at`,`last_seen_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `call_signals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT, `call_id` bigint unsigned NOT NULL, `from_account_id` int unsigned NOT NULL, `to_account_id` int unsigned DEFAULT NULL,
  `signal_type` varchar(24) NOT NULL, `payload` mediumtext NOT NULL, `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), KEY `idx_call_signal_poll` (`call_id`,`to_account_id`,`id`), KEY `idx_call_signal_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT, `account_id` int unsigned NOT NULL, `kind` varchar(32) NOT NULL DEFAULT 'activity',
  `title` varchar(255) NOT NULL DEFAULT '', `body` varchar(1000) NOT NULL DEFAULT '', `url` varchar(1024) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `read_at` datetime DEFAULT NULL, PRIMARY KEY (`id`), KEY `idx_notifications_poll` (`account_id`,`read_at`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
