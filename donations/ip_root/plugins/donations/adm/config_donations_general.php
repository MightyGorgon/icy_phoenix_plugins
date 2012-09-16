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

include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

$settings_details = array();
$settings_details = array(
	'id' => 'donations_general',
	'name' => 'PLUGIN_DONATIONS_GENERAL',
	'sort' => 0,
	'sub_name' => '',
	'sub_sort' => 0,
	'menu_name' => 'Preferences',
	'menu_sort' => 0,
	'clear_cache' => false,
);

$settings_data = array();
$settings_data = array(

	'donations_paypal_address' => array(
		'lang_key' => 'PAYPAL_ADDRESS',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'donations_founder_manage' => array(
		'lang_key' => 'FOUNDER_MANAGE',
		'explain' => 'FOUNDER_MANAGE_EXPLAIN',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'donations_send_pm' => array(
		'lang_key' => 'SEND_CONFIRM_PM',
		'explain' => 'SEND_CONFIRM_PM_EXPLAIN',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'donations_debug' => array(
		'lang_key' => 'PAYPAL_DEBUG',
		'explain' => 'PAYPAL_DEBUG_EXPLAIN',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'donations_logging' => array(
		'lang_key' => 'ERROR_LOGGING',
		'explain' => 'ERROR_LOGGING_EXPLAIN',
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $this->list_yes_no,
	),

	'donations_paypal_sandbox' => array(
		'lang_key' => 'SANDBOX_TESTING',
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $this->list_yes_no,
	),

	'donations_paypal_sandbox_address' => array(
		'lang_key' => 'SANDBOX_ADDRESS',
		'type' => 'VARCHAR',
		'default' => '',
	),

	'donations_supporters_group_id' => array(
		'lang_key' => 'SUPPORTERS_GROUP',
		'explain' => 'SUPPORTERS_GROUP_EXPLAIN',
		'type' => 'FUNCTION',
		'get_func' => 'groups_select',
		'default' => 0,
	),

	'donations_default_currency' => array(
		'lang_key' => 'DEFAULT_CURRENCY',
		'type' => 'LIST_DROP',
		'default' => 'USD',
		'values' => array_flip($lang['currency_code']),
	),

	'donations_donate_minimum' => array(
		'lang_key' => 'DONATE_MINIMUM',
		'explain' => 'DONATE_MINIMUM_EXPLAIN',
		'type' => 'INT',
		'default' => 10,
	),

	'donations_convert_percentage' => array(
		'lang_key' => 'CONVERT_PERCENTAGE',
		'explain' => 'CONVERT_PERCENTAGE_EXPLAIN',
		'type' => 'TINYINT',
		'default' => 5,
	),

	'donations_default_country' => array(
		'lang_key' => 'DEFAULT_COUNTRY',
		'type' => 'LIST_DROP',
		'default' => 'US',
		'values' => array_flip($lang['country_options']),
	),

);

$this->init_plugins_config($settings_details, $settings_data);

?>