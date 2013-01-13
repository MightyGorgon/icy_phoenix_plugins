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

// DONATION CONFIG - BEGIN
define('PLUGINS_DONATIONS_TABLE', $table_prefix . 'donations');
define('DONATIONS_DATA_TABLE', $table_prefix . 'donations_data');
define('DONATIONS_PERKS_TABLE', $table_prefix . 'donations_perks');
define('ASCII_RANGE', '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

define('PLUGINS_DONATIONS_DB_ITEM', 'transaction_id');
define('PLUGINS_DONATIONS_DB_TABLE', DONATIONS_DATA_TABLE);

$cms_page['global_blocks'] = (isset($cms_page['global_blocks']) ? $cms_page['global_blocks'] : false);
$item_id = PLUGINS_DONATIONS_DB_ITEM;
$donations_admin_auth = (!empty($plugin_config['donations_founder_manage']) ? AUTH_FOUNDER : AUTH_ADMIN);
// DONATION CONFIG - END

$list_yes_no = array('Yes' => 1, 'No' => 0);
$current_time = time();

$table_fields = array(

	'transaction_id' => array(
		'lang_key' => 'DONATION_TRANSACTION_ID',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'txn_id' => array(
		'lang_key' => 'DONATION_TXN_ID',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'txn_type' => array(
		'lang_key' => 'DONATION_TXN_TYPE',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'confirmed' => array(
		'lang_key' => 'DONATION_CONFIRMED',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $list_yes_no,
	),

	'user_id' => array(
		'lang_key' => 'DONATION_USER_ID',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'is_user_id' => true,
		'type' => 'MEDIUMINT',
		'default' => -1,
	),

	'username' => array(
		'lang_key' => 'DONATION_USERNAME',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'item_name' => array(
		'lang_key' => 'DONATION_ITEM_NAME',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'item_number' => array(
		'lang_key' => 'DONATION_ITEM_NUMBER',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payment_time' => array(
		'lang_key' => 'DONATION_PAYMENT_TIME',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => AUTH_ALL,
		'is_time' => true,
		'type' => 'DATE_TIME_INPUT',
		'default' => $current_time,
	),

	'business' => array(
		'lang_key' => 'DONATION_BUSINESS',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payment_status' => array(
		'lang_key' => 'DONATION_PAYMENT_STATUS',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payment_gross' => array(
		'lang_key' => 'DONATION_PAYMENT_GROSS',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payment_fee' => array(
		'lang_key' => 'DONATION_PAYMENT_FEE',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payment_type' => array(
		'lang_key' => 'DONATION_PAYMENT_TYPE',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'mc_currency' => array(
		'lang_key' => 'DONATION_MC_CURRENCY',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payment_date' => array(
		'lang_key' => 'DONATION_PAYMENT_DATE',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payer_id' => array(
		'lang_key' => 'DONATION_PAYER_ID',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payer_email' => array(
		'lang_key' => 'DONATION_PAYER_EMAIL',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payer_website' => array(
		'lang_key' => 'DONATION_PAYER_WEBSITE',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payer_website_text' => array(
		'lang_key' => 'DONATION_PAYER_WEBSITE_TEXT',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'payer_website_sponsor' => array(
		'lang_key' => 'DONATION_PAYER_WEBSITE_SPONSOR',
		'explain' => 'DONATION_PAYER_WEBSITE_SPONSOR_EXPLAIN',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $list_yes_no,
	),

	'payer_status' => array(
		'lang_key' => 'DONATION_PAYER_STATUS',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'first_name' => array(
		'lang_key' => 'DONATION_FIRST_NAME',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'last_name' => array(
		'lang_key' => 'DONATION_LAST_NAME',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'memo' => array(
		'lang_key' => 'DONATION_MEMO',
		'admin_level' => $donations_admin_auth,
		'input_level' => $donations_admin_auth,
		'edit_level' => $donations_admin_auth,
		'view_level' => $donations_admin_auth,
		'type' => 'VARCHAR',
		'default' => '',
	),

);

foreach ($table_fields as $k => $v)
{
	$table_fields[$k]['admin_level'] = (isset($table_fields[$k]['admin_level']) ? $table_fields[$k]['admin_level'] : $donations_admin_auth);
	$table_fields[$k]['input_level'] = (isset($table_fields[$k]['input_level']) ? $table_fields[$k]['input_level'] : $donations_admin_auth);
	$table_fields[$k]['edit_level'] = (isset($table_fields[$k]['edit_level']) ? $table_fields[$k]['edit_level'] : $donations_admin_auth);
	$table_fields[$k]['view_level'] = (isset($table_fields[$k]['view_level']) ? $table_fields[$k]['view_level'] : $donations_admin_auth);
	$table_fields[$k]['default'] = (isset($table_fields[$k]['default']) ? $table_fields[$k]['default'] : 0);
}

?>