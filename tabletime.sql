CREATE TABLE `accounts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `friends` TEXT NULL,
  `posts` TEXT NULL,
  `groups` TEXT NULL,
  `events` TEXT NULL,
  `forums` TEXT NULL,
  `tags` TEXT NULL,
  `messages` TEXT NULL,
  `colors` TEXT NULL,
  `votes` TEXT NULL,
  `files` TEXT NULL,
  `hostips` TEXT NULL,
  `hostmode` FLOAT NULL,
  `aboutcontent` TEXT NULL,
  `delay` TIME NULL,
  `ip` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_account` (`username`, `email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

CREATE TABLE `events` (
  `id` INT(11) NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NULL,
  `members` TEXT NULL,
  `about` TEXT NULL,
  `groups` TEXT NULL,
  `posts` TEXT NULL,
  `tags` TEXT NULL,
  `type` VARCHAR(50) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

CREATE TABLE `forums` (
  `id` INT(11) NULL AUTO_INCREMENT,
  `tag` VARCHAR(255) NULL,
  `posts` TEXT NULL,
  `groups` TEXT NULL,
  `events` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_forum_tag` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

CREATE TABLE `groups` (
  `id` INT(11) NULL AUTO_INCREMENT,
  `about` TEXT NULL,
  `members` TEXT NULL,
  `forums` TEXT NULL,
  `events` TEXT NULL,
  `posts` TEXT NULL,
  `tags` TEXT NULL,
  `title` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_group_title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

CREATE TABLE `posts` (
  `id` INT(11) NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NULL,
  `content` TEXT NULL,
  `dt` DATETIME NULL,
  `file` TEXT NULL,
  `tags` TEXT NULL,
  `name` VARCHAR(255) NULL,
  `comments` TEXT NULL,
  `scope` TEXT NULL,
  `recipients` TEXT NULL,
  `type` VARCHAR(50) NULL,
  `votes` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_post_title` (`title`, `name`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

CREATE TABLE `tags` (
  `id` INT(11) NULL AUTO_INCREMENT,
  `posts` TEXT NULL,
  `forums` TEXT NULL,
  `events` TEXT NULL,
  `groups` TEXT NULL,
  `value` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tag_value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

COMMIT;
