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

			"DROP TABLE IF EXISTS " . $table_prefix . "ajax_chat_online;",
			"CREATE TABLE " . $table_prefix . "ajax_chat_online (
				userID INT(11) NOT NULL,
				userName VARCHAR(64) NOT NULL,
				userRole INT(1) NOT NULL,
				channel INT(11) NOT NULL,
				dateTime DATETIME NOT NULL,
				ip VARBINARY(16) NOT NULL
			);",

			"DROP TABLE IF EXISTS " . $table_prefix . "ajax_chat_messages;",
			"CREATE TABLE " . $table_prefix . "ajax_chat_messages (
				id INT(11) NOT NULL AUTO_INCREMENT,
				userID INT(11) NOT NULL,
				userName VARCHAR(64) NOT NULL,
				userRole INT(1) NOT NULL,
				channel INT(11) NOT NULL,
				dateTime DATETIME NOT NULL,
				ip VARBINARY(16) NOT NULL,
				text TEXT,
				PRIMARY KEY (id)
			);",

			"DROP TABLE IF EXISTS " . $table_prefix . "ajax_chat_bans;",
			"CREATE TABLE " . $table_prefix . "ajax_chat_bans (
				userID INT(11) NOT NULL,
				userName VARCHAR(64) NOT NULL,
				dateTime DATETIME NOT NULL,
				ip VARBINARY(16) NOT NULL
			);",

			"DROP TABLE IF EXISTS " . $table_prefix . "ajax_chat_invitations;",
			"CREATE TABLE " . $table_prefix . "ajax_chat_invitations (
				userID INT(11) NOT NULL,
				channel INT(11) NOT NULL,
				dateTime DATETIME NOT NULL
			);",
		),
		'functions' => array(),
	),
	'1.0.1' => array(),
);

$uninstall_data = array(
	'sql' => array(
		"DELETE FROM " . PLUGINS_CONFIG_TABLE . " WHERE config_name LIKE \"chat_%\";",
		"DROP TABLE IF EXISTS " . $table_prefix . "ajax_chat_online;",
		"DROP TABLE IF EXISTS " . $table_prefix . "ajax_chat_messages;",
		"DROP TABLE IF EXISTS " . $table_prefix . "ajax_chat_bans;",
		"DROP TABLE IF EXISTS " . $table_prefix . "ajax_chat_invitations;",
	),
	'functions' => array(),
);

?>