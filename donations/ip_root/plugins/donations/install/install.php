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
			"CREATE TABLE `" . $table_prefix . "mg_donations` (
				`donation_id` mediumint(9) NOT NULL auto_increment,
				`donation_time` int(11) unsigned NOT NULL default '0',
				`donation_amount` varchar(20) NOT NULL default '0',
				`donation_description` text,
				`donation_user_id` mediumint(9) NOT NULL default '-1',
				`donation_user_name` varchar(255) NOT NULL default '',
				`donation_email` varchar(255) NOT NULL default '',
				`donation_website` varchar(255) NOT NULL default '',
				PRIMARY KEY (`donation_id`)
			);",
			"CREATE TABLE `" . $table_prefix . "mg_donations_data` (
				`transaction_id` mediumint(8) unsigned NOT NULL auto_increment,
				`txn_id` varchar(18) NOT NULL,
				`txn_type` varchar(32) NOT NULL,
				`confirmed` tinyint(1) unsigned NOT NULL default '0',
				`user_id` mediumint(8) NOT NULL default '-1',
				`username` varchar(128) NOT NULL,
				`item_name` varchar(128) NOT NULL,
				`item_number` varchar(128) NOT NULL,
				`payment_time` int(11) unsigned NOT NULL default '0',
				`business` varchar(128) NOT NULL,
				`payment_status` varchar(32) NOT NULL,
				`payment_gross` decimal(8,2) NOT NULL,
				`payment_fee` decimal(8,2) NOT NULL,
				`payment_type` varchar(16) NOT NULL,
				`mc_currency` varchar(16) NOT NULL,
				`payment_date` varchar(32) NOT NULL,
				`payer_id` varchar(16) NOT NULL,
				`payer_email` varchar(128) NOT NULL,
				`payer_website` varchar(255) NOT NULL,
				`payer_status` varchar(16) NOT NULL,
				`first_name` varchar(64) NOT NULL,
				`last_name` varchar(64) NOT NULL,
				`memo` varchar(255) NOT NULL,
				PRIMARY KEY (`transaction_id`),
				KEY user_id (`user_id`),
				KEY txn_id (`txn_id`)
			);",
			"CREATE TABLE `" . $table_prefix . "mg_donations_perks` (
				`perk_id` mediumint(8) unsigned NOT NULL auto_increment,
				`perk_title` varchar(100) NOT NULL,
				`perk_text` mediumtext NOT NULL,
				`perk_desc_bitfield` varchar(255) NOT NULL default '',
				`perk_desc_options` int(11) unsigned NOT NULL default '7',
				`perk_desc_uid` varchar(8) NOT NULL default '',
				`perk_order` tinyint(3) unsigned NOT NULL default '0',
				`perk_active_date` int(11) unsigned NOT NULL default '0',
				`perk_expire_date` int(11) unsigned NOT NULL default '0',
				PRIMARY KEY (`perk_id`),
				KEY perk_active_date (`perk_active_date`),
				KEY perk_expire_date (`perk_expire_date`)
			);"
		),
		'functions' => array(),
	),
	'1.0.1' => array(
		'sql' => array(
			"RENAME TABLE `" . $table_prefix . "mg_donations` TO `" . $table_prefix . "donations`;",
			"RENAME TABLE `" . $table_prefix . "mg_donations_data` TO `" . $table_prefix . "donations_data`;",
			"RENAME TABLE `" . $table_prefix . "mg_donations_perks` TO `" . $table_prefix . "donations_perks`;"
		),
		'functions' => array(),
	),
	'1.0.2' => array(
		'sql' => array(
			"ALTER TABLE `" . $table_prefix . "donations_data` ADD `payer_website_text` varchar(255) NOT NULL AFTER `payer_website`;",
			"ALTER TABLE `" . $table_prefix . "donations_data` ADD `payer_website_sponsor` tinyint(1) unsigned NOT NULL default '0' AFTER `payer_website_text`;",
		),
		'functions' => array(),
	)
);

$uninstall_data = array(
	'sql' => array(
		"DELETE FROM " . PLUGINS_CONFIG_TABLE . " WHERE config_name LIKE \"donations_%\";",
		"DROP TABLE `" . $table_prefix . "donations`;",
		"DROP TABLE `" . $table_prefix . "donations_data`;",
		"DROP TABLE `" . $table_prefix . "donations_perks`;"
	),
	'functions' => array(),
);

?>