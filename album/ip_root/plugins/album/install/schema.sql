
## --------------------------------------------------------

## `phpbb_album`

CREATE TABLE `phpbb_album` (
	`pic_id` INT(11) unsigned NOT NULL auto_increment,
	`pic_filename` VARCHAR(255) NOT NULL DEFAULT '',
	`pic_size` INT(15) unsigned NOT NULL DEFAULT '0',
	`pic_thumbnail` VARCHAR(255) DEFAULT '',
	`pic_title` VARCHAR(255) NOT NULL DEFAULT '',
	`pic_desc` TEXT NOT NULL,
	`pic_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`pic_username` VARCHAR(32) DEFAULT '',
	`pic_user_ip` VARCHAR(40) NOT NULL DEFAULT '0',
	`pic_time` INT(11) unsigned NOT NULL DEFAULT '0',
	`pic_cat_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '1',
	`pic_view_count` INT(11) unsigned NOT NULL DEFAULT '0',
	`pic_lock` TINYINT(3) NOT NULL DEFAULT '0',
	`pic_approval` TINYINT(3) NOT NULL DEFAULT '1',
	PRIMARY KEY (`pic_id`),
	KEY `pic_cat_id` (`pic_cat_id`),
	KEY `pic_user_id` (`pic_user_id`),
	KEY `pic_time` (`pic_time`)
);

## `phpbb_album`


## --------------------------------------------------------

## `phpbb_album_cat`

CREATE TABLE `phpbb_album_cat` (
	`cat_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`cat_title` VARCHAR(255) NOT NULL DEFAULT '',
	`cat_desc` TEXT NOT NULL,
	`cat_wm` TEXT NOT NULL,
	`cat_pics` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`cat_order` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`cat_view_level` TINYINT(3) NOT NULL DEFAULT '-1',
	`cat_upload_level` TINYINT(3) NOT NULL DEFAULT '0',
	`cat_rate_level` TINYINT(3) NOT NULL DEFAULT '0',
	`cat_comment_level` TINYINT(3) NOT NULL DEFAULT '0',
	`cat_edit_level` TINYINT(3) NOT NULL DEFAULT '0',
	`cat_delete_level` TINYINT(3) NOT NULL DEFAULT '2',
	`cat_view_groups` VARCHAR(255) DEFAULT '',
	`cat_upload_groups` VARCHAR(255) DEFAULT '',
	`cat_rate_groups` VARCHAR(255) DEFAULT '',
	`cat_comment_groups` VARCHAR(255) DEFAULT '',
	`cat_edit_groups` VARCHAR(255) DEFAULT '',
	`cat_delete_groups` VARCHAR(255) DEFAULT '',
	`cat_moderator_groups` VARCHAR(255) DEFAULT '',
	`cat_approval` TINYINT(3) NOT NULL DEFAULT '0',
	`cat_parent` MEDIUMINT(8) unsigned DEFAULT '0',
	`cat_user_id` MEDIUMINT(8) unsigned DEFAULT '0',
	PRIMARY KEY (`cat_id`),
	KEY `cat_order` (`cat_order`)
);

## `phpbb_album_cat`


## --------------------------------------------------------

## `phpbb_album_comment`

CREATE TABLE `phpbb_album_comment` (
	`comment_id` INT(11) unsigned NOT NULL auto_increment,
	`comment_pic_id` INT(11) unsigned NOT NULL DEFAULT '0',
	`comment_cat_id` INT(11) NOT NULL DEFAULT '0',
	`comment_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`comment_username` VARCHAR(32) DEFAULT '',
	`comment_user_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`comment_time` INT(11) unsigned NOT NULL DEFAULT '0',
	`comment_text` TEXT NOT NULL,
	`comment_edit_time` INT(11) unsigned DEFAULT NULL,
	`comment_edit_count` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`comment_edit_user_id` MEDIUMINT(8) DEFAULT NULL,
	PRIMARY KEY (`comment_id`),
	KEY `comment_pic_id` (`comment_pic_id`),
	KEY `comment_user_id` (`comment_user_id`),
	KEY `comment_user_ip` (`comment_user_ip`),
	KEY `comment_time` (`comment_time`)
);

## `phpbb_album_comment`

## --------------------------------------------------------

## `phpbb_album_comment_watch`

CREATE TABLE `phpbb_album_comment_watch` (
	pic_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
	user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
	notify_status TINYINT(1) NOT NULL DEFAULT '0',
	KEY pic_id (pic_id),
	KEY user_id (user_id),
	KEY notify_status (notify_status)
);

## `phpbb_album_comment_watch`

## --------------------------------------------------------

## `phpbb_album_config`

CREATE TABLE `phpbb_album_config` (
	`config_name` VARCHAR(255) NOT NULL DEFAULT '',
	`config_value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`config_name`)
);

## `phpbb_album_config`


## --------------------------------------------------------

## `phpbb_album_rate`

CREATE TABLE `phpbb_album_rate` (
	`rate_pic_id` INT(11) unsigned NOT NULL DEFAULT '0',
	`rate_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`rate_user_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`rate_point` TINYINT(3) unsigned NOT NULL DEFAULT '0',
	`rate_hon_point` TINYINT(3) NOT NULL DEFAULT '0',
	KEY `rate_pic_id` (`rate_pic_id`),
	KEY `rate_user_id` (`rate_user_id`),
	KEY `rate_user_ip` (`rate_user_ip`),
	KEY `rate_point` (`rate_point`)
);

## `phpbb_album_rate`
