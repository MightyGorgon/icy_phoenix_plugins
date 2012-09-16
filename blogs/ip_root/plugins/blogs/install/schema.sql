##// Usage example
##//http://localhost/ip/blogs.php?mode=input&action=edit&blog_id=1

CREATE TABLE `phpbb_blogs` (
	`blog_id` mediumint(9) NOT NULL auto_increment,
	`blog_owner` mediumint(9) NOT NULL default '0',
	`blog_user_id_create` mediumint(9) NOT NULL default '-1',
	`blog_user_id_update` mediumint(9) NOT NULL default '-1',
	`blog_time_creation` int(11) unsigned NOT NULL default '0',
	`blog_time_update` int(11) unsigned NOT NULL default '0',
	`blog_title` varchar(255) NOT NULL,
	`blog_description` text,
	`blog_status` tinyint(1) unsigned NOT NULL default '0',
	`blog_notifications` tinyint(1) unsigned NOT NULL default '0',
	`blog_auth_read` tinyint(1) unsigned NOT NULL default '0',
	`blog_auth_post` tinyint(1) unsigned NOT NULL default '0',
	`blog_auth_reply` tinyint(1) unsigned NOT NULL default '0',
	`blog_auth_edit` tinyint(1) unsigned NOT NULL default '0',
	`blog_auth_delete` tinyint(1) unsigned NOT NULL default '0',
	PRIMARY KEY (`blog_id`)
);

##CREATE TABLE `phpbb_blogs_auth` (
##	`blog_id` mediumint(9) NOT NULL default '0',
##	`user_id` mediumint(9) NOT NULL default '0',
##	`user_auth` mediumint(9) NOT NULL default '0',
##	PRIMARY KEY (`blog_id`)
##);

CREATE TABLE `phpbb_blogs_topics` (
	`topic_id` mediumint(8) unsigned NOT NULL auto_increment,
	`blog_id` smallint(8) unsigned NOT NULL DEFAULT '0',
	`topic_title` varchar(255) NOT NULL DEFAULT '',
	`topic_title_clean` varchar(255) NOT NULL DEFAULT '',
	`topic_desc` varchar(255) DEFAULT '',
	`topic_poster` mediumint(8) NOT NULL DEFAULT '0',
	`topic_time` int(11) unsigned NOT NULL DEFAULT '0',
	`topic_views` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_replies` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_status` tinyint(3) NOT NULL DEFAULT '0',
	`topic_approved` tinyint(1) NOT NULL DEFAULT '1',
	`topic_first_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_first_post_time` int(11) unsigned NOT NULL DEFAULT '0',
	`topic_first_poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_first_poster_name` varchar(255) NOT NULL DEFAULT '',
	`topic_first_poster_color` varchar(16) NOT NULL DEFAULT '',
	`topic_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_last_post_time` int(11) unsigned NOT NULL DEFAULT '0',
	`topic_last_poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_last_poster_name` varchar(255) NOT NULL DEFAULT '',
	`topic_last_poster_color` varchar(16) NOT NULL DEFAULT '',
	`topic_rating` double unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`topic_id`),
	KEY `blog_id` (`blog_id`),
	KEY `topic_status` (`topic_status`)
);

CREATE TABLE `phpbb_blogs_posts` (
	`post_id` mediumint(8) unsigned NOT NULL auto_increment,
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`blog_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`poster_id` mediumint(8) NOT NULL DEFAULT '0',
	`post_time` int(11) NOT NULL DEFAULT '0',
	`poster_ip` varchar(40) NOT NULL DEFAULT '',
	`poster_email` varchar(255) DEFAULT NULL,
	`post_username` varchar(255) DEFAULT NULL,
	`post_subject` varchar(255) DEFAULT NULL,
	`post_text` text,
	`post_status` tinyint(3) NOT NULL DEFAULT '0',
	`post_flags` mediumint(8) NOT NULL DEFAULT '0',
	PRIMARY KEY (`post_id`),
	KEY `blog_id` (`blog_id`),
	KEY `topic_id` (`topic_id`),
	KEY `poster_id` (`poster_id`),
	KEY `post_time` (`post_time`)
);
