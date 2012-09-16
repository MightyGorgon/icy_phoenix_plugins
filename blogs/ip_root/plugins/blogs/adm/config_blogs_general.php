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

$settings_details = array();
$settings_details = array(
	'id' => 'blogs_general',
	'name' => 'PLUGIN_BLOGS_GENERAL',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'blogs_config_01' => array(
		'lang_key' => 'BLOGS_CONFIG_01',
		'explain' => 'BLOGS_CONFIG_01_EXPLAIN',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'blogs_config_02' => array(
		'lang_key' => 'BLOGS_CONFIG_02',
		'explain' => 'BLOGS_CONFIG_02_EXPLAIN',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

);

$this->init_plugins_config($settings_details, $settings_data);

?>