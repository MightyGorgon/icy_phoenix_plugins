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

// GUESTBOOK CONFIG - BEGIN
define('GUESTBOOK_SEND_EMAIL', true);

define('PLUGINS_GUESTBOOKS_TABLE', $table_prefix . 'guestbooks');
define('PLUGINS_GUESTBOOKS_DB_ITEM', 'guestbook_id');
define('PLUGINS_GUESTBOOKS_DB_TABLE', PLUGINS_GUESTBOOKS_TABLE);

$item_id = PLUGINS_GUESTBOOKS_DB_ITEM;
// GUESTBOOK CONFIG - END

$list_yes_no = array('Yes' => 1, 'No' => 0);
$current_time = time();

$table_fields = array(

	'guestbook_id' => array(
		'lang_key' => 'GUESTBOOKS_GUESTBOOK_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'guestbook_owner' => array(
		'lang_key' => 'GUESTBOOKS_OWNER',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_username' => true,
		'type' => 'USERNAME_INPUT',
		'default' => '',
	),

	'guestbook_time_creation' => array(
		'lang_key' => 'GUESTBOOKS_TIME_CREATION',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_time' => true,
		'type' => 'HIDDEN',
		'default' => $current_time,
	),

	'guestbook_user_id_create' => array(
		'lang_key' => 'GUESTBOOKS_USER_ID_CREATE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_user_id' => true,
		'type' => 'HIDDEN',
		'default' => -1,
	),

	'guestbook_time_update' => array(
		'lang_key' => 'GUESTBOOKS_TIME_UPDATE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_time' => true,
		'type' => 'HIDDEN',
		'default' => $current_time,
	),

	'guestbook_user_id_update' => array(
		'lang_key' => 'GUESTBOOKS_USER_ID_UPDATE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_user_id' => true,
		'type' => 'HIDDEN',
		'default' => -1,
	),

	'guestbook_title' => array(
		'lang_key' => 'GUESTBOOKS_TITLE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'guestbook_description' => array(
		'lang_key' => 'GUESTBOOKS_DESCRIPTION',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'bbcode_box' => true,
		'bbcode_parse' => true,
		'type' => 'HTMLTEXT',
		'default' => '',
	),

	'guestbook_status' => array(
		'lang_key' => 'GUESTBOOKS_STATUS',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_ADMIN,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => 1,
		'values' => array(
			'GUESTBOOKS_STATUS_DISABLED' => 0,
			'GUESTBOOKS_STATUS_ENABLED' => 1,
			'GUESTBOOKS_STATUS_HIDDEN' => 2,
		),
	),

	'guestbook_notifications' => array(
		'lang_key' => 'GUESTBOOKS_NOTIFICATIONS',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $list_yes_no,
	),

	'guestbook_auth_read' => array(
		'lang_key' => 'GUESTBOOKS_AUTH_READ',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_ALL,
		'values' => array(
			'GUESTBOOKS_AUTH_ALL' => AUTH_ALL,
			'GUESTBOOKS_AUTH_REG' => AUTH_REG,
		),
	),

	'guestbook_auth_post' => array(
		'lang_key' => 'GUESTBOOKS_AUTH_POST',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_ALL,
		'values' => array(
			'GUESTBOOKS_AUTH_ALL' => AUTH_ALL,
			'GUESTBOOKS_AUTH_REG' => AUTH_REG,
			'GUESTBOOKS_AUTH_OWNER' => AUTH_OWNER,
		),
	),

	'guestbook_auth_edit' => array(
		'lang_key' => 'GUESTBOOKS_AUTH_EDIT',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_REG,
		'values' => array(
			'GUESTBOOKS_AUTH_REG' => AUTH_REG,
			'GUESTBOOKS_AUTH_OWNER' => AUTH_OWNER,
		),
	),

	'guestbook_auth_delete' => array(
		'lang_key' => 'GUESTBOOKS_AUTH_DELETE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_OWNER,
		'values' => array(
			'GUESTBOOKS_AUTH_REG' => AUTH_REG,
			'GUESTBOOKS_AUTH_OWNER' => AUTH_OWNER,
		),
	),

);

foreach ($table_fields as $k => $v)
{
	$table_fields[$k]['admin_level'] = (isset($table_fields[$k]['admin_level']) ? $table_fields[$k]['admin_level'] : AUTH_FOUNDER);
	$table_fields[$k]['input_level'] = (isset($table_fields[$k]['input_level']) ? $table_fields[$k]['input_level'] : AUTH_FOUNDER);
	$table_fields[$k]['edit_level'] = (isset($table_fields[$k]['edit_level']) ? $table_fields[$k]['edit_level'] : AUTH_FOUNDER);
	$table_fields[$k]['view_level'] = (isset($table_fields[$k]['view_level']) ? $table_fields[$k]['view_level'] : AUTH_FOUNDER);
	$table_fields[$k]['default'] = (isset($table_fields[$k]['default']) ? $table_fields[$k]['default'] : 0);
}

$table_posts_fields = array(

	'post_id' => array(
		'lang_key' => 'GUESTBOOKS_POST_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'guestbook_id' => array(
		'lang_key' => 'GUESTBOOKS_GUESTBOOK_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'poster_id' => array(
		'lang_key' => 'GUESTBOOKS_POST_POSTER',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_username' => true,
		/*
		'type' => 'USERNAME_INPUT',
		'default' => '',
		*/
		'type' => 'HIDDEN',
		'default' => -1,
	),

	'post_time' => array(
		'lang_key' => 'GUESTBOOKS_POST_TIME',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_time' => true,
		'type' => 'HIDDEN',
		'default' => $current_time,
	),

	'poster_ip' => array(
		'lang_key' => 'GUESTBOOKS_POST_IP',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => $user_ip,
	),

	'poster_email' => array(
		'lang_key' => 'GUESTBOOKS_POST_EMAIL',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_GUEST_ONLY,
		'edit_level' => AUTH_GUEST_ONLY,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'post_username' => array(
		'lang_key' => 'GUESTBOOKS_POST_USERNAME',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_GUEST_ONLY,
		'edit_level' => AUTH_GUEST_ONLY,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'post_subject' => array(
		'lang_key' => 'GUESTBOOKS_POST_SUBJECT',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_ALL,
		'edit_level' => AUTH_ALL,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'post_text' => array(
		'lang_key' => 'GUESTBOOKS_POST_TEXT',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_ALL,
		'edit_level' => AUTH_ALL,
		'view_level' => AUTH_ALL,
		'bbcode_box' => true,
		'bbcode_parse' => true,
		'type' => 'HTMLTEXT',
		'default' => '',
	),

	'post_status' => array(
		'lang_key' => 'GUESTBOOKS_STATUS',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_ADMIN,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => 1,
		'values' => array(
			'GUESTBOOKS_STATUS_DISABLED' => 0,
			'GUESTBOOKS_STATUS_ENABLED' => 1,
			'GUESTBOOKS_STATUS_HIDDEN' => 2,
		),
	),

	'post_flags' => array(
		'lang_key' => 'GUESTBOOKS_POST_TEXT',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_FLAGS',
		'default' => 3,
		'values' => array(
			'POST_ENABLE_BBCODE' => 1,
			'POST_ENABLE_SMILEYS' => 2,
		),
	),

);

/*
$post_data = array(
	'post_id' => 0,
	'guestbook_id' => 0,
	'poster_id' => 0,
	'post_time' => 0,
	'poster_ip' => '',
	'poster_email' => '',
	'post_username' => '',
	'post_subject' => '',
	'post_text' => '',
	'post_status' => 0,
	'post_flags' => 0
);
*/

?>