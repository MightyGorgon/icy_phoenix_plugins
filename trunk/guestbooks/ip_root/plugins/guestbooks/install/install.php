<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$install_data = array(
	'1.0.0' => array(
		'sql' => array(
			"CREATE TABLE `" . $table_prefix . "guestbooks` (
				`guestbook_id` mediumint(9) NOT NULL auto_increment,
				`guestbook_owner` mediumint(9) NOT NULL default '0',
				`guestbook_user_id_create` mediumint(9) NOT NULL default '-1',
				`guestbook_user_id_update` mediumint(9) NOT NULL default '-1',
				`guestbook_time_creation` int(11) unsigned NOT NULL default '0',
				`guestbook_time_update` int(11) unsigned NOT NULL default '0',
				`guestbook_title` varchar(255) NOT NULL,
				`guestbook_description` text,
				`guestbook_status` tinyint(1) unsigned NOT NULL default '0',
				`guestbook_notifications` tinyint(1) unsigned NOT NULL default '0',
				`guestbook_auth_read` tinyint(1) unsigned NOT NULL default '0',
				`guestbook_auth_post` tinyint(1) unsigned NOT NULL default '0',
				`guestbook_auth_edit` tinyint(1) unsigned NOT NULL default '0',
				`guestbook_auth_delete` tinyint(1) unsigned NOT NULL default '0',
				PRIMARY KEY (`guestbook_id`)
			);",
			"CREATE TABLE `" . $table_prefix . "guestbooks_posts` (
				`post_id` mediumint(8) unsigned NOT NULL auto_increment,
				`guestbook_id` smallint(5) unsigned NOT NULL DEFAULT '0',
				`poster_id` mediumint(8) NOT NULL DEFAULT '0',
				`post_time` int(11) NOT NULL DEFAULT '0',
				`poster_ip` varchar(8) NOT NULL DEFAULT '',
				`poster_email` varchar(255) DEFAULT NULL,
				`post_username` varchar(255) DEFAULT NULL,
				`post_subject` varchar(255) DEFAULT NULL,
				`post_text` text,
				`post_status` tinyint(3) NOT NULL DEFAULT '0',
				`post_flags` mediumint(8) NOT NULL DEFAULT '0',
				PRIMARY KEY (`post_id`),
				KEY `guestbook_id` (`guestbook_id`),
				KEY `poster_id` (`poster_id`),
				KEY `post_time` (`post_time`)
			);"
		),
		'functions' => array(),
	),
	'1.0.1' => array(),
);

$uninstall_data = array(
	'sql' => array(
		"DELETE FROM " . PLUGINS_CONFIG_TABLE . " WHERE config_name LIKE \"guestbooks_%\";",
		"DROP TABLE `" . $table_prefix . "guestbooks`;",
		"DROP TABLE `" . $table_prefix . "guestbooks_posts`;"
	),
	'functions' => array(),
);

?>