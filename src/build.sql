
CREATE TABLE `areas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `layout` text COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `config` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `article_contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` bigint(20) unsigned NOT NULL,
  `body` mediumtext,
  `info_box` text NOT NULL,
  `review_by` bigint(20) unsigned NOT NULL DEFAULT '0',
  `review_date` datetime NOT NULL,
  `id2` bigint(20) NOT NULL,
  `paper_section` varchar(32) NOT NULL,
  `paper_page_no` varchar(10) NOT NULL DEFAULT '',
  `paper_filename` varchar(255) NOT NULL DEFAULT '',
  `paper_subsection` varchar(32) NOT NULL,
  `feed_ordering` smallint(5) unsigned NOT NULL DEFAULT '9999',
  `embed_video` text,
  `poll` text,
  `embed_video_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bylineTitle` varchar(255) DEFAULT NULL,
  `short_url` varchar(130) DEFAULT NULL,
  `activation_datetime` datetime DEFAULT NULL,
  `edition_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `articles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `short_title` varchar(255) NOT NULL,
  `photo_id` bigint(20) unsigned DEFAULT NULL,
  `lead_photo_layout` tinyint(3) unsigned DEFAULT NULL,
  `profile_id` bigint(20) unsigned NOT NULL DEFAULT '5',
  `urgency` tinyint(4) NOT NULL DEFAULT '8',
  `summary` text NOT NULL,
  `author_display` varchar(255) NOT NULL,
  `pub_date` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `approved` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `comment_level_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `show_enriched` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `authorables` (
  `author_id` bigint(20) NOT NULL,
  `authorable_id` int(10) unsigned NOT NULL,
  `authorable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `metadata` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `authorables_authorables_id_authorables_type_index` (`authorable_id`,`authorable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(100) CHARACTER SET latin1 NOT NULL,
  `firstname` varchar(100) CHARACTER SET latin1 NOT NULL,
  `department` varchar(100) CHARACTER SET latin1 NOT NULL,
  `title` varchar(100) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `twitter` varchar(100) CHARACTER SET latin1 NOT NULL,
  `phone` varchar(15) CHARACTER SET latin1 NOT NULL,
  `grouping` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `blog` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `author_page` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `handle` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `bio` longtext,
  `mugshot` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `current` tinyint(1) NOT NULL,
  `middlename` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `displayname` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `newsokID` int(11) DEFAULT NULL,
  `facebook_page` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `google_plus_id` varchar(45) DEFAULT NULL,
  `home_phone` varchar(15) DEFAULT NULL,
  `cell_phone` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewsOKID` (`newsokID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table block_types
# ------------------------------------------------------------

CREATE TABLE `block_types` (
  `widget_type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `widget` varchar(64) DEFAULT NULL,
  `handle` varchar(80) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table blocks
# ------------------------------------------------------------

CREATE TABLE `blocks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `handle` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `widgetTypeId` int(10) unsigned DEFAULT NULL,
  `siteSid` varchar(32) CHARACTER SET utf8 NOT NULL,
  `pageSid` varchar(32) CHARACTER SET utf8 NOT NULL,
  `area_id` int(10) unsigned NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `templateName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `caching` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `cacheSecs` mediumint(8) unsigned NOT NULL DEFAULT '60',
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sponsor` mediumtext CHARACTER SET utf8 NOT NULL,
  `modifiedTime` datetime NOT NULL,
  `key1` varchar(255) CHARACTER SET latin1 NOT NULL,
  `key2` varchar(255) CHARACTER SET latin1 NOT NULL,
  `field1` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field2` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field3` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field4` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field5` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field6` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field7` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field8` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field9` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field10` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field11` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field12` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field13` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field14` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field15` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field16` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field17` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field18` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field19` varchar(255) CHARACTER SET utf8 NOT NULL,
  `field20` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text1` longtext CHARACTER SET utf8 NOT NULL,
  `text2` longtext CHARACTER SET utf8 NOT NULL,
  `text3` longtext CHARACTER SET utf8 NOT NULL,
  `currentVersion` int(20) NOT NULL DEFAULT '0',
  `dayPartStartTime` datetime DEFAULT NULL,
  `dayPartEndTime` datetime DEFAULT NULL,
  `dayPartEnabled` tinyint(4) DEFAULT '0',
  `dayPartNoEnd` tinyint(4) DEFAULT '0',
  `date` date DEFAULT NULL,
  `platform` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `config` text DEFAULT NULL,
  `page_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table brands
# ------------------------------------------------------------

CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `footer_logo` text COLLATE utf8_unicode_ci NOT NULL,
  `social_area` text COLLATE utf8_unicode_ci NOT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bg_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body-bg` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#fff',
  `styles` text COLLATE utf8_unicode_ci,
  `sidebar_bg_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'rgba(238, 238, 238, 0.7)',
  `css` text COLLATE utf8_unicode_ci NOT NULL,
  `landingcontent` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `nav_hide` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table editions
# ------------------------------------------------------------

CREATE TABLE `editions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pub_date` date NOT NULL,
  `metadata` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fileables
# ------------------------------------------------------------

CREATE TABLE `fileables` (
  `file_id` bigint(20) NOT NULL,
  `fileable_id` int(10) unsigned NOT NULL,
  `fileable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `metadata` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `fileables_fileable_id_fileable_type_index` (`fileable_id`,`fileable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table files
# ------------------------------------------------------------

CREATE TABLE `files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` bigint(20) DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bucket` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mime_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `metadata` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table forms
# ------------------------------------------------------------

CREATE TABLE `forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fields` text COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






# Dump of table page_aliases
# ------------------------------------------------------------

CREATE TABLE `page_aliases` (
  `page_id` int(11) unsigned NOT NULL,
  `alias` varchar(255) NOT NULL,
  UNIQUE KEY `page_id` (`page_id`,`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table pages
# ------------------------------------------------------------

CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL COMMENT 'rename this field to slug',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `controller` varchar(50) NOT NULL DEFAULT 'page_default.tpl',
  `config` text NOT NULL,
  `template` varchar(255) DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






# Dump of table pg_page_metadatas
# ------------------------------------------------------------

CREATE TABLE `pg_page_metadatas` (
  `site_sid` varchar(32) NOT NULL,
  `page_sid` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `page_name` varchar(32) NOT NULL,
  `section` varchar(32) NOT NULL,
  `subsection` varchar(32) NOT NULL,
  `content_type` varchar(32) NOT NULL,
  `content_title` varchar(255) NOT NULL,
  `view_choice` varchar(32) NOT NULL,
  `priority` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `robot_type` varchar(32) NOT NULL,
  `taxonomy_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cstm_sctn_list` varchar(32) DEFAULT NULL,
  `content_lang` varchar(10) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT '',
  `twitter_handle` varchar(255) DEFAULT '',
  UNIQUE KEY `site_sid` (`site_sid`,`page_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;






# Dump of table social_accounts
# ------------------------------------------------------------

CREATE TABLE `social_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `provider_user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `metadata` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table standalone_blocks
# ------------------------------------------------------------

CREATE TABLE `standalone_blocks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `handle` varchar(100) NOT NULL,
  `block_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table svgs
# ------------------------------------------------------------

CREATE TABLE `svgs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `imgdata` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table taggables
# ------------------------------------------------------------

CREATE TABLE `taggables` (
  `tag_id` bigint(20) NOT NULL,
  `taggable_id` int(10) unsigned NOT NULL,
  `taggable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `metadata` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `taggables_taggable_id_taggable_type_index` (`taggable_id`,`taggable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table tags
# ------------------------------------------------------------

CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `handle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `metadata` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_handle` (`handle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table user_groups
# ------------------------------------------------------------

CREATE TABLE `user_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group` varchar(100) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

ALTER TABLE `users` ADD `username` VARCHAR(191)  CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci  NOT NULL  DEFAULT ''  AFTER `name`;
ALTER TABLE `users` ADD `role` VARCHAR(20)  CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci  NOT NULL  DEFAULT 'username';



# Dump of table workflows
# ------------------------------------------------------------

CREATE TABLE `workflows` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `assigned_user` int(11) DEFAULT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

