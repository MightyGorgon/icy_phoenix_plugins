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

// BLOG CONFIG - BEGIN
define('BLOG_SEND_EMAIL', true);

define('PLUGINS_BLOGS_TABLE', $table_prefix . 'blogs');
define('PLUGINS_BLOGS_DB_ITEM', 'blog_id');
define('PLUGINS_BLOGS_DB_TABLE', PLUGINS_BLOGS_TABLE);

$item_id = PLUGINS_BLOGS_DB_ITEM;
// BLOG CONFIG - END

$list_yes_no = array('Yes' => 1, 'No' => 0);
$current_time = time();

$table_fields = array(

	'blog_id' => array(
		'lang_key' => 'BLOGS_BLOG_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'blog_owner' => array(
		'lang_key' => 'BLOGS_OWNER',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_username' => true,
		'type' => 'USERNAME_INPUT',
		'default' => '',
	),

	'blog_time_creation' => array(
		'lang_key' => 'BLOGS_TIME_CREATION',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_time' => true,
		'type' => 'HIDDEN',
		'default' => $current_time,
	),

	'blog_user_id_create' => array(
		'lang_key' => 'BLOGS_USER_ID_CREATE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_user_id' => true,
		'type' => 'HIDDEN',
		'default' => -1,
	),

	'blog_time_update' => array(
		'lang_key' => 'BLOGS_TIME_UPDATE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_time' => true,
		'type' => 'HIDDEN',
		'default' => $current_time,
	),

	'blog_user_id_update' => array(
		'lang_key' => 'BLOGS_USER_ID_UPDATE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_user_id' => true,
		'type' => 'HIDDEN',
		'default' => -1,
	),

	'blog_title' => array(
		'lang_key' => 'BLOGS_TITLE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'blog_description' => array(
		'lang_key' => 'BLOGS_DESCRIPTION',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'bbcode_box' => true,
		'bbcode_parse' => true,
		'type' => 'HTMLTEXT',
		'default' => '',
	),

	'blog_status' => array(
		'lang_key' => 'BLOGS_STATUS',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_ADMIN,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => 1,
		'values' => array(
			'BLOGS_STATUS_DISABLED' => 0,
			'BLOGS_STATUS_ENABLED' => 1,
			'BLOGS_STATUS_HIDDEN' => 2,
		),
	),

	'blog_notifications' => array(
		'lang_key' => 'BLOGS_NOTIFICATIONS',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_RADIO',
		'default' => 0,
		'values' => $list_yes_no,
	),

	'blog_auth_read' => array(
		'lang_key' => 'BLOGS_AUTH_READ',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_ALL,
		'values' => array(
			'BLOGS_AUTH_ALL' => AUTH_ALL,
			'BLOGS_AUTH_REG' => AUTH_REG,
		),
	),

	'blog_auth_post' => array(
		'lang_key' => 'BLOGS_AUTH_POST',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_OWNER,
		'values' => array(
			'BLOGS_AUTH_REG' => AUTH_REG,
			'BLOGS_AUTH_OWNER' => AUTH_OWNER,
		),
	),

	'blog_auth_reply' => array(
		'lang_key' => 'BLOGS_AUTH_REPLY',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_REG,
		'values' => array(
			'BLOGS_AUTH_ALL' => AUTH_ALL,
			'BLOGS_AUTH_REG' => AUTH_REG,
			'BLOGS_AUTH_OWNER' => AUTH_OWNER,
		),
	),

	'blog_auth_edit' => array(
		'lang_key' => 'BLOGS_AUTH_EDIT',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_REG,
		'values' => array(
			'BLOGS_AUTH_REG' => AUTH_REG,
			'BLOGS_AUTH_OWNER' => AUTH_OWNER,
		),
	),

	'blog_auth_delete' => array(
		'lang_key' => 'BLOGS_AUTH_DELETE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => AUTH_OWNER,
		'values' => array(
			'BLOGS_AUTH_REG' => AUTH_REG,
			'BLOGS_AUTH_OWNER' => AUTH_OWNER,
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

$table_topics_fields = array(

	'topic_id' => array(
		'lang_key' => 'BLOGS_TOPIC_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'blog_id' => array(
		'lang_key' => 'BLOGS_BLOG_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_title' => array(
		'lang_key' => 'BLOGS_TOPIC_TITLE',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_REG,
		'edit_level' => AUTH_REG,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'topic_title_clean' => array(
		'type' => 'HIDDEN',
		'default' => '',
	),

	'topic_desc' => array(
		'type' => 'HIDDEN',
		'default' => '',
	),

	'topic_poster' => array(
		'lang_key' => 'BLOGS_TOPIC_POSTER',
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

	'topic_time' => array(
		'lang_key' => 'BLOGS_TOPIC_TIME',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_time' => true,
		'type' => 'HIDDEN',
		'default' => $current_time,
	),

	'topic_views' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_replies' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_status' => array(
		'lang_key' => 'BLOGS_STATUS',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_ADMIN,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => 1,
		'values' => array(
			'BLOGS_STATUS_DISABLED' => 0,
			'BLOGS_STATUS_ENABLED' => 1,
			'BLOGS_STATUS_HIDDEN' => 2,
		),
	),

	'topic_approved' => array(
		'lang_key' => 'BLOGS_TOPIC_APPROVED',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_ADMIN,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_RADIO',
		'default' => 1,
		'values' => $list_yes_no,
	),

	'topic_first_post_id' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_first_post_time' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_first_poster_id' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_first_poster_name' => array(
		'type' => 'HIDDEN',
		'default' => '',
	),

	'topic_first_poster_color' => array(
		'type' => 'HIDDEN',
		'default' => '',
	),

	'topic_last_post_id' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_last_post_time' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_last_poster_id' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_last_poster_name' => array(
		'type' => 'HIDDEN',
		'default' => '',
	),

	'topic_last_poster_color' => array(
		'type' => 'HIDDEN',
		'default' => '',
	),

	'topic_rating' => array(
		'type' => 'HIDDEN',
		'default' => 0,
	),

);

$table_posts_fields = array(

	'post_id' => array(
		'lang_key' => 'BLOGS_POST_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'topic_id' => array(
		'lang_key' => 'BLOGS_TOPIC_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'blog_id' => array(
		'lang_key' => 'BLOGS_BLOG_ID',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => 0,
	),

	'poster_id' => array(
		'lang_key' => 'BLOGS_TOPIC_POSTER',
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
		'lang_key' => 'BLOGS_TOPIC_TIME',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'is_time' => true,
		'type' => 'HIDDEN',
		'default' => $current_time,
	),

	'poster_ip' => array(
		'lang_key' => 'BLOGS_POST_IP',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_FOUNDER,
		'view_level' => AUTH_ALL,
		'type' => 'HIDDEN',
		'default' => $user_ip,
	),

	'poster_email' => array(
		'lang_key' => 'BLOGS_POST_EMAIL',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_GUEST_ONLY,
		'edit_level' => AUTH_GUEST_ONLY,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'post_username' => array(
		'lang_key' => 'BLOGS_POST_USERNAME',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_GUEST_ONLY,
		'edit_level' => AUTH_GUEST_ONLY,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'post_subject' => array(
		'lang_key' => 'BLOGS_POST_SUBJECT',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_ALL,
		'edit_level' => AUTH_ALL,
		'view_level' => AUTH_ALL,
		'type' => 'VARCHAR',
		'default' => '',
	),

	'post_text' => array(
		'lang_key' => 'BLOGS_POST_TEXT',
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
		'lang_key' => 'BLOGS_STATUS',
		'admin_level' => AUTH_FOUNDER,
		'input_level' => AUTH_FOUNDER,
		'edit_level' => AUTH_ADMIN,
		'view_level' => AUTH_ALL,
		'type' => 'LIST_DROP',
		'default' => 1,
		'values' => array(
			'BLOGS_STATUS_DISABLED' => 0,
			'BLOGS_STATUS_ENABLED' => 1,
			'BLOGS_STATUS_HIDDEN' => 2,
		),
	),

	'post_flags' => array(
		'lang_key' => 'BLOGS_POST_TEXT',
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
$article_data = array(
	'topic_id' => 0,
	'blog_id' => 0,
	'topic_title' => '',
	'topic_title_clean' => '',
	'topic_desc' => '',
	'topic_poster' => 0,
	'topic_time' => 0,
	'topic_views' => 0,
	'topic_replies' => 0,
	'topic_status' => 0,
	'topic_approved' => 0,
	'topic_first_post_id' => 0,
	'topic_first_post_time' => 0,
	'topic_first_poster_id' => 0,
	'topic_first_poster_name' => '',
	'topic_first_poster_color' => '',
	'topic_last_post_id' => 0,
	'topic_last_post_time' => 0,
	'topic_last_poster_id' => 0,
	'topic_last_poster_name' => '',
	'topic_last_poster_color' => '',
	'topic_rating' => 0
);

$comment_data = array(
	'post_id' => 0,
	'topic_id' => 0,
	'blog_id' => 0,
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