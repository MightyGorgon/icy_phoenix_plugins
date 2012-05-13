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
			"CREATE TABLE `" . $table_prefix . "feedback` (
				`feedback_id` mediumint(9) NOT NULL auto_increment,
				`feedback_time` int(11) unsigned NOT NULL default '0',
				`feedback_transaction` varchar(255) NOT NULL default '',
				`feedback_rating` int(2) NOT NULL default '0',
				`feedback_description` text,
				`feedback_url` text,
				`feedback_topic_id` mediumint(9) NOT NULL default '0',
				`feedback_user_id_from` mediumint(9) NOT NULL default '-1',
				`feedback_user_id_to` mediumint(9) NOT NULL default '-1',
				PRIMARY KEY (`feedback_id`)
			);"
		),
		'functions' => array(),
	),
	'1.0.1' => array(
		'sql' => array(),
		'functions' => array(),
	)
);

$uninstall_data = array(
	'sql' => array(
		"DELETE FROM " . PLUGINS_CONFIG_TABLE . " WHERE config_name LIKE \"feedback_%\";",
		"DROP TABLE `" . $table_prefix . "feedback`;"
	),
	'functions' => array(),
);

?>