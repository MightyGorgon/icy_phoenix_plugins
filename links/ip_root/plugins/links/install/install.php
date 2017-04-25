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
			// schema
			"CREATE TABLE `" . $table_prefix . "link_categories` (
				`cat_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
				`cat_title` VARCHAR(100) NOT NULL DEFAULT '',
				`cat_order` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`cat_id`),
				KEY `cat_order` (`cat_order`)
			);",
			"CREATE TABLE `" . $table_prefix . "link_config` (
				`config_name` VARCHAR(255) NOT NULL DEFAULT '',
				`config_value` VARCHAR(255) NOT NULL DEFAULT ''
			);",
			"CREATE TABLE `" . $table_prefix . "links` (
				`link_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
				`link_title` VARCHAR(100) NOT NULL DEFAULT '',
				`link_desc` VARCHAR(255) DEFAULT NULL,
				`link_category` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
				`link_url` VARCHAR(100) NOT NULL DEFAULT '',
				`link_logo_src` VARCHAR(120) DEFAULT NULL,
				`link_joined` INT(11) NOT NULL DEFAULT '0',
				`link_active` TINYINT(1) NOT NULL DEFAULT '0',
				`link_hits` INT(10) unsigned NOT NULL DEFAULT '0',
				`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
				`user_ip` VARCHAR(40) NOT NULL DEFAULT '',
				`last_user_ip` VARCHAR(40) NOT NULL DEFAULT '',
				PRIMARY KEY (`link_id`)
			);",
			// basic
			"INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('links', 'links', 'links.php', 0, '', 0, '');",
			"INSERT INTO `" . $table_prefix . "link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (1, 'Arts', 1);",
			"INSERT INTO `" . $table_prefix . "link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (2, 'Business', 2);",
			"INSERT INTO `" . $table_prefix . "link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (3, 'Children And Teens', 3);",
			"INSERT INTO `" . $table_prefix . "link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (4, 'Computers', 4);",
			"INSERT INTO `" . $table_prefix . "link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (5, 'Games', 5);",
			"INSERT INTO `" . $table_prefix . "link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (6, 'Health', 6);",
			"INSERT INTO `" . $table_prefix . "link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (7, 'Home', 7);",
			"INSERT INTO `" . $table_prefix . "link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (8, 'News', 8);",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('site_logo', 'http://www.icyphoenix.com/images/links/banner_ip.gif');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('site_url', 'http://www.icyphoenix.com/');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('width', '88');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('height', '31');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('linkspp', '10');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('display_interval', '6000');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('display_logo_num', '10');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('display_links_logo', '1');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('email_notify', '1');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('pm_notify', '0');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('lock_submit_site', '0');",
			"INSERT INTO `" . $table_prefix . "link_config` (`config_name`, `config_value`) VALUES ('allow_no_logo', '0');",
			"INSERT INTO `" . $table_prefix . "links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (1, 'Icy Phoenix Official Website', 'Icy Phoenix', 4, 'http://www.icyphoenix.com/', 'images/links/banner_ip.gif', 1241136000, 1, 0, 2, '', '');",
			"INSERT INTO `" . $table_prefix . "links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (2, 'Luca Libralato', 'Luca Libralato', 4, 'http://www.lucalibralato.com/', 'images/links/banner_mightygorgon.gif', 1241136000, 1, 0, 2, '', '');",
			"INSERT INTO `" . $table_prefix . "links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (3, 'phpBB Official Website', 'Official phpBB Website', 4, 'http://www.phpbb.com/', 'images/links/banner_phpbb88a.gif', 1241136000, 1, 0, 2, '', '');",
		),
		'functions' => array(),
	),
);
			// 'site_logo' => 'http://' . $server_name . $script_path . 'images/links/banner_ip.gif',
			// 'site_url' => 'http://' . $server_name . $script_path

$uninstall_data = array(
	'sql' => array(
		// schema
		"DROP TABLE `" . $table_prefix . "link_categories`;",
		"DROP TABLE `" . $table_prefix . "link_config`;",
		"DROP TABLE `" . $table_prefix . "links`;",
		// basic
		"DELETE FROM " . PLUGINS_CONFIG_TABLE . " WHERE config_name LIKE \"links_%\";",
		"DELETE FROM " . $table_prefix . "cms_blocks WHERE bs_id = (
			SELECT bs_id FROM
			" . $table_prefix . "cms_block_settings WHERE blockfile = 'plugin_links'
		);",
		"DELETE FROM " . $table_prefix . "cms_block_variable WHERE block = 'plugin_links';",
		"DELETE FROM " . $table_prefix . "cms_block_settings WHERE blockfile = 'plugin_links';",
		"DELETE FROM " . $table_prefix . "cms_layout_special WHERE page_id = 'links';",
		"DELETE FROM " . $table_prefix . "cms_nav_menu WHERE menu_links = 'links.php';",
	),
	'functions' => array(
		//@todo clean blocks
	),
);

?>