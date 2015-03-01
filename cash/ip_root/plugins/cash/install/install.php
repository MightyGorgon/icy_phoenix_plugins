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
	'1.0.0' => array(),
	'1.0.1' => array(
		'sql' => array(

			"UPDATE `" . $table_prefix . "plugins` SET `plugin_functions` = 1 WHERE `plugin_name` = 'cash';",

		),
		'functions' => array(),
	),
);

$uninstall_data = array(
	'sql' => array(
		"DELETE FROM " . PLUGINS_CONFIG_TABLE . " WHERE config_name LIKE \"cash_%\";",
		"DELETE FROM `" . $table_prefix . "cms_layout_special` WHERE page_id = 'cash';"
	),
	'functions' => array(),
);

?>