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

if (!function_exists('generate_text_for_display'))
{
	include_once(IP_ROOT_PATH . 'includes/functions_bbcode.' . PHP_EXT);
}

// COMMON INCLUDES AND OPTIONS - BEGIN
$guestbook_id = $class_guestbooks->guestbook_id;
$post_id = $class_guestbooks->post_id;
$mode_overlay = '';

$guestbook_data = array();

if (empty($guestbook_id))
{
	// Force 'view' mode... then try to automatically catch the missing IDs...
	$mode_overlay = 'view';
	if (!empty($post_id))
	{
		$post_data = $class_guestbooks->get_post($post_id);
		$guestbook_id = $post_data['guestbook_id'];
		$class_guestbooks->guestbook_id = $guestbook_id;
		unset($post_data);
	}
}

$guestbook_data = $class_guestbooks->get_guestbook_data($guestbook_id);
if (empty($guestbook_id) || empty($guestbook_data))
{
	message_die(GENERAL_MESSAGE, $lang['NO_GUESTBOOK_ID']);
}

$guestbook_title = censor_text($guestbook_data['guestbook_title']);
//$guestbook_title = ((strlen($guestbook_title) > 55) ? (htmlspecialchars(substr(htmlspecialchars_decode($guestbook_title, ENT_COMPAT), 0, 52)) . '...') : $guestbook_title);
$bbcode->allow_html = false;
$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $config['allow_smilies']) ? true : false;
$guestbook_description = generate_text_for_display($guestbook_data['guestbook_description'], false, true, false, '999999');

$inputs_array = array();
$is_owner = ($userdata['user_id'] == $guestbook_data['guestbook_owner']) ? true : false;
$admin_allowed = (check_auth_level(AUTH_ADMIN) || $is_owner) ? true : false;
$input_allowed = (check_auth_level($guestbook_data['guestbook_auth_post']) || $is_owner) ? true : false;
$edit_allowed = (check_auth_level(AUTH_ADMIN) || $is_owner) ? true : false;
$input_post_allowed = ($admin_allowed || check_auth_level($guestbook_data['guestbook_auth_post'])) ? true : false;
$edit_post_allowed = ($admin_allowed || check_auth_level($guestbook_data['guestbook_auth_edit'])) ? true : false;

include(IP_ROOT_PATH . 'includes/common_forms.' . PHP_EXT);

$is_auth = true;
if (in_array($mode, array('input', 'save')) && !$admin_allowed && ((($action == 'add') && !$input_post_allowed) || (($action == 'edit') && !$edit_post_allowed)))
{
	$is_auth = false;
}

if (!$is_auth)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
}
// COMMON INCLUDES AND OPTIONS - END

// Start output of page
$meta_content['page_title'] = $lang['GUESTBOOKS_PAGE'];
$meta_content['description'] = $lang['GUESTBOOKS_PAGE'];
$meta_content['keywords'] = $lang['GUESTBOOKS_PAGE'];
$breadcrumbs_links_right = '';
if ($input_allowed)
{
	$breadcrumbs_links_left = '';
	$breadcrumbs_links_right .= (($breadcrumbs_links_right != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id . '&amp;mode=input') . '">' . $lang['GUESTBOOKS_LINK_POST_MESSAGE'] . '</a>';
}
$breadcrumbs_links_right .= (($breadcrumbs_links_right != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id) . '">' . $lang['GUESTBOOK_PAGE'] . '</a>';

if ($mode == 'save')
{
	$post_data = $class_form->request_vars_data($table_posts_fields);

	// In case a guest is posting we need some basic checks...
	$error['status'] = false;
	$error['message'] = '';
	if ($userdata['user_id'] == ANONYMOUS)
	{
		if (empty($post_data['post_username']))
		{
			$error['status'] = true;
			$error['message'] .= '<br /><br />' . $lang['GUESTBOOKS_ERROR_EMPTY_USERNAME'];
		}
	}
	else
	{
		if (empty($post_data['post_text']))
		{
			$error['status'] = true;
			$error['message'] .= '<br /><br />' . $lang['GUESTBOOKS_ERROR_EMPTY_MESSAGE'];
		}
		/*
		if (empty($post_data['post_subject']))
		{
			$error['status'] = true;
			$error['message'] .= '<br /><br />' . $lang['GUESTBOOKS_ERROR_EMPTY_TITLE'];
		}
		*/
	}

	if ($error['status'])
	{
		$error['message'] .= '<br /><br />' . $lang['GUESTBOOKS_ERROR_MESSAGE'];
		message_die(GENERAL_MESSAGE, $error['message']);
	}

	$current_time = time();
	$current_user_id = $userdata['user_id'];
	$current_username = ($userdata['user_id'] != ANONYMOUS) ? htmlspecialchars($userdata['username']) : (!empty($post_data['post_username']) ? $post_data['post_username'] : $lang['Guest']);
	$current_user_color = (($userdata['user_id'] != ANONYMOUS) && !empty($userdata['user_color'])) ? $userdata['user_color'] : '';

	$post_data['guestbook_id'] = $guestbook_id;
	$post_data['post_id'] = $post_id;

	if ($action == 'edit')
	{
		if (empty($guestbook_id) || ($guestbook_id <= 0))
		{
			message_die(GENERAL_MESSAGE, $lang['NO_GUESTBOOK_ID']);
		}

		if (empty($post_id) || ($post_id <= 0))
		{
			message_die(GENERAL_MESSAGE, $lang['NO_GUESTBOOK_POST_ID']);
		}

		// Reset and unset some unused fields when updating...
		unset($post_data['poster_id']);
		unset($post_data['post_time']);
		unset($post_data['poster_ip']);

		$class_guestbooks->submit_post($post_data, 'update');
		$message = $lang['GUESTBOOK_COMMENT_UPDATED'];
	}
	else
	{
		// Re-assign action just in case the above condition is not verified!
		$action == 'add';

		$post_data['poster_id'] = $current_user_id;
		$post_data['post_time'] = $current_time;
		$post_data['poster_ip'] = $user_ip;
		$post_data['post_username'] = $current_username;

		// If it is a new insert, we need to unset the $item_id, because it will be automatically incremented by the DB
		unset($post_data['post_id']);

		if (!$userdata['session_logged_in'])
		{
			include_once(IP_ROOT_PATH . 'includes/class_captcha.' . PHP_EXT);
			$class_captcha = new class_captcha();
			$class_captcha->clear_confirm_table();
			$class_captcha->check_attempts(false);
			$captcha_result = $class_captcha->check_code();
			if ($captcha_result['error'])
			{
				message_die(GENERAL_MESSAGE, $captcha_result['error_msg']);
			}
		}
		$class_guestbooks->submit_post($post_data, 'insert');
		$post_id = $post_data['post_id'];
		$message = $lang['GUESTBOOK_COMMENT_ADDED'];
	}

	// Check if we are submitting via CMS Block...
	$cms_redirect = request_var('cms_redirect', '', true);
	if (!empty($cms_redirect))
	{
		redirect(append_sid(IP_ROOT_PATH . $cms_redirect . ((strpos($cms_redirect, '?') !== false) ? '&' : '?') . $class_guestbooks->post_id_var . '=' . $post_id . '#' . $class_guestbooks->post_id_var . $post_id, true));
	}

	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_POST'], '<a href="' . append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id . '&amp;' . $class_guestbooks->post_id_var . '=' . $post_id . '#' . $class_guestbooks->post_id_var . $post_id) . '">', '</a>');
	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_GUESTBOOK'], '<a href="' . append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'delete')
{
	if ($post_id > 0)
	{
		$class_guestbooks->remove_post($post_id);
		$message = $lang['GUESTBOOK_COMMENT_REMOVED'];
	}
	else
	{
		$message = $lang['Error'];
	}

	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_GUESTBOOK'], '<a href="' . append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'input')
{
	// This check is to be removed when CAPTCHA is working for $mode = 'input'
	// CAPTCHA code is at the bottom
	if (!$userdata['session_logged_in'])
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
	}

	$items_row = array();

	if ($action == 'edit')
	{
		$items_row_processed = false;
		if ($post_id > 0)
		{
			$items_row_processed = true;
			$items_row = $class_guestbooks->get_post($post_id);
		}
		else
		{
			// Re-assign action just in case the above conditions are not verified!
			$action == 'add';
		}

		if ($items_row_processed && empty($items_row))
		{
			message_die(GENERAL_ERROR, 'Could not query guestbooks table', $lang['Error'], __LINE__, __FILE__);
		}
	}
	else
	{
		// Re-assign action just in case the above condition is not verified!
		$action == 'add';
	}

	$s_hidden_fields = build_hidden_fields(array(
		'mode' => 'save',
		'action' => $action,
		$class_guestbooks->guestbook_id_var => !empty($items_row['guestbook_id']) ? (int) $items_row['guestbook_id'] : $guestbook_id,
		$class_guestbooks->post_id_var => !empty($items_row['post_id']) ? (int) $items_row['post_id'] : $post_id,
		)
	);

	$items_row['guestbook_id'] = !empty($items_row['guestbook_id']) ? $items_row['guestbook_id'] : (!empty($guestbook_id) ? $guestbook_id : 0);

	$template_to_parse = 'items_add_body.tpl';

	$s_bbcb_global = false;
	$table_fields = array();
	$table_fields_keys = array(
		'guestbook_id' => array('post_type' => 'post', 'value' => $table_posts_fields['guestbook_id']),
		'post_id' => array('post_type' => 'post', 'value' => $table_posts_fields['post_id']),
		'post_time' => array('post_type' => 'post', 'value' => $table_posts_fields['post_time']),
		'poster_id' => array('post_type' => 'post', 'value' => $table_posts_fields['poster_id']),
		'post_subject' => array('post_type' => 'post', 'value' => $table_posts_fields['post_subject']),
		'post_text' => array('post_type' => 'post', 'value' => $table_posts_fields['post_text']),
		'post_status' => array('post_type' => 'post', 'value' => $table_posts_fields['post_status']),
		'post_flags' => array('post_type' => 'post', 'value' => $table_posts_fields['post_flags']),
	);

	if ($userdata['user_id'] == ANONYMOUS)
	{
		$table_fields_keys_extra = array(
			'post_username' => array('post_type' => 'post', 'value' => $table_posts_fields['post_username']),
			'poster_email' => array('post_type' => 'post', 'value' => $table_posts_fields['poster_email']),
		);
		$table_fields_keys = array_merge($table_fields_keys_extra, $table_fields_keys);
	}

	foreach ($table_fields_keys as $k => $v)
	{
		$table_fields[$k] = $v['value'];
	}
	$class_form->create_input_form($table_fields, $inputs_array, $current_time, $s_bbcb_global, $mode, $action, $items_row);

	$template->assign_vars(array(
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	/*
	// Not working yet!
	if (!$userdata['session_logged_in'])
	{
		include_once(IP_ROOT_PATH . 'includes/class_captcha.' . PHP_EXT);
		$class_captcha = new class_captcha();
		$class_captcha->create_image();
	}
	*/
}
elseif ($mode == 'view')
{
	if (empty($post_id))
	{
		message_die(GENERAL_MESSAGE, $lang['NO_GUESTBOOK_POST_ID']);
	}

	$items_row = array();
	$items_row = $class_guestbooks->get_post($post_id);
	if (empty($items_row))
	{
		message_die(GENERAL_ERROR, 'Could not query data table', $lang['Error'], __LINE__, __FILE__);
	}

	// Page Title - BEGIN
	$item_title = htmlspecialchars($items_row['guestbook_title']);
	$meta_content['page_title'] = (!empty($item_title) ? (strip_tags($item_title) . ' - ') : '') . $meta_content['page_title'];
	// Page Title - END

	$template_to_parse = 'items_view_body.tpl';

	$class_form->create_view_page($table_posts_fields, $inputs_array, $items_row);

	$template->assign_vars(array(
		'U_ITEM_EDIT' => append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id . '&amp;' . $class_guestbooks->post_id_var . '=' . $post_id . '&amp;mode=input&amp;action=edit'),
		'EXTRA_CONTENT_TOP' => (!empty($extra_content_top) ? $extra_content_top : ''),
		'EXTRA_CONTENT_BOTTOM_FORM' => (!empty($extra_content_bottom_form) ? $extra_content_bottom_form : ''),
		'EXTRA_CONTENT_BOTTOM' => (!empty($extra_content_bottom) ? $extra_content_bottom : ''),
		)
	);
}
else
{
	// Page Title - BEGIN
	$item_title = $guestbook_title;
	$item_title_url = append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id . '&amp;mode=view');
	$meta_content['page_title'] = (!empty($item_title) ? (strip_tags($item_title) . ' - ') : '') . $meta_content['page_title'];
	// Page Title - END

	$template_to_parse = $class_plugins->get_tpl_file(GUESTBOOKS_TPL_PATH, 'guestbook_body.tpl');

	//Now get all posts
	$items_array = $class_guestbooks->get_posts($guestbook_id, '', $start, $n_items);
	$page_items = sizeof($items_array);

	if ($page_items == 0)
	{
		$template->assign_var('NO_GUESTBOOK_POSTS', true);
	}
	else
	{
		for ($i = 0; $i < $page_items; $i++)
		{
			$post_guestbook_id = $items_array[$i]['guestbook_id'];
			$post_post_id = $items_array[$i]['post_id'];

			$guestbook_poster = ($items_array[$i]['poster_id'] != ANONYMOUS) ? colorize_username($items_array[$i]['poster_id'], htmlspecialchars_decode($items_array[$i]['username']), $items_array[$i]['user_color'], $items_array[$i]['user_active']) : (!empty($items_array[$i]['post_username']) ? $items_array[$i]['post_username'] : $lang['Guest']);
			$guestbook_title = censor_text($items_array[$i]['post_subject']);
			$guestbook_date = create_date_ip($config['default_dateformat'], $items_array[$i]['post_time'], $config['board_timezone']);

			//$bbcode->allow_html = ($userdata['user_allowhtml'] && $config['allow_html'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_HTML)) ? true : false;
			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = ($userdata['user_allowbbcode'] && $config['allow_bbcode'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_BBCODE)) ? true : false;
			$bbcode->allow_smilies = ($userdata['user_allowsmile'] && $config['allow_smilies'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_SMILIES)) ? true : false;
			$guestbook_post = generate_text_for_display($items_array[$i]['post_text'], false, true, false, '999999');

			$edit_link = '';
			$edit_img = '';

			$delete_link = '';
			$delete_img = '';

			$post_append_url = $class_guestbooks->guestbook_id_var . '=' . $post_guestbook_id . '&amp;' . $class_guestbooks->post_id_var . '=' . $post_post_id;

			$post_moderation_allowed = ($admin_allowed || (($items_array[$i]['poster_id'] != ANONYMOUS) && ($items_array[$i]['poster_id'] == $userdata['user_id']) && check_auth_level($guestbook_data['guestbook_auth_edit']))) ? true : false;
			if ($post_moderation_allowed)
			{
				$edit_link = append_sid(THIS_FILE . '?' . $post_append_url . '&amp;mode=input&amp;action=edit');
				$edit_img = '<a href="' . $edit_link . '"><img src="' . IP_ROOT_PATH . 'images/cms/b_edit.png" alt="' . $lang['EDIT'] . '" title="' . $lang['EDIT'] . '" /></a>';

				$delete_link = append_sid(THIS_FILE . '?' . $post_append_url . '&amp;mode=delete');
				$delete_img = '<a href="' . $delete_link . '"><img src="' . IP_ROOT_PATH . 'images/cms/b_delete.png" alt="' . $lang['DELETE'] . '" title="' . $lang['DELETE'] . '" /></a>';
			}

			$class = ($i % 2) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('posts', array(
				'CLASS' => $class,
				'POST_ID' => $post_post_id,

				'POSTER' => $guestbook_poster,
				'DATE' => $guestbook_date,
				'MESSAGE' => $guestbook_post,
				'POSTED_BY' => sprintf($lang['GUESTBOOKS_POSTED_BY'], $guestbook_poster, $guestbook_date),

				'S_MOD' => $post_moderation_allowed,

				'U_EDIT' => $edit_link,
				'S_EDIT' => $edit_img,
				'U_DELETE' => $delete_link,
				'S_DELETE' => $delete_img,
				)
			);
		}
		$db->sql_freeresult($result);

		$total_items = $class_guestbooks->get_total_posts($guestbook_id);
		$pagination = generate_pagination(append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id . $url_full_append), $total_items, $n_items, $start) . '&nbsp;';
		$template->assign_vars(array(
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $n_items) + 1), ceil($total_items / $n_items)),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
	}

	$post_post_allowed = ($admin_allowed || check_auth_level($guestbook_data['guestbook_auth_post'])) ? true : false;
	if ($post_post_allowed)
	{
		$items_row = array();
		$s_hidden_fields = build_hidden_fields(array(
			'mode' => 'save',
			'action' => 'add',
			$class_guestbooks->guestbook_id_var => $guestbook_id,
			)
		);

		$table_fields = array();
		$table_fields_keys = array(
			'post_subject' => array('post_type' => 'post', 'value' => $table_posts_fields['post_subject']),
			'post_text' => array('post_type' => 'post', 'value' => $table_posts_fields['post_text']),
		);

		if (($userdata['user_id'] == ANONYMOUS) || ($action == 'edit'))
		{
			$table_fields_keys_extra = array(
				'post_username' => array('post_type' => 'post', 'value' => $table_posts_fields['post_username']),
				'poster_email' => array('post_type' => 'post', 'value' => $table_posts_fields['poster_email']),
			);
			$table_fields_keys = array_merge($table_fields_keys_extra, $table_fields_keys);
		}

		foreach ($table_fields_keys as $k => $v)
		{
			$table_fields[$k] = $v['value'];
		}
		$class_form->create_input_form($table_fields, $inputs_array, $current_time, $s_bbcb_global, $mode, 'add', $items_row);

		$template->assign_vars(array(
			'POST_POST_ALLOWED' => $post_post_allowed,
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}

	if (!$userdata['session_logged_in'])
	{
		include_once(IP_ROOT_PATH . 'includes/class_captcha.' . PHP_EXT);
		$class_captcha = new class_captcha();
		$class_captcha->create_image();
	}
}

$template->assign_vars(array(
	'L_GUESTBOOK_TITLE' => $guestbook_data['guestbook_title'],
	'U_GUESTBOOK_TITLE' => append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id),

	'GUESTBOOK_ID' => $guestbook_id,
	'GUESTBOOK_ID_VAR' => $class_guestbooks->guestbook_id_var,
	'GUESTBOOK_POST_ID_VAR' => $class_guestbooks->post_id_var,

	'TITLE' => $guestbook_title,
	'DESCRIPTION' => $guestbook_description,

	'L_PAGE_NAME' => $meta_content['page_title'],
	'L_ITEM_TITLE' => !empty($item_title) ? $item_title : false,
	'U_ITEM_TITLE' => $item_title_url,
	'U_ITEM_ADD' => append_sid(THIS_FILE . '?' . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id . '&amp;mode=input'),

	'S_ADMIN_ALLOWED' => ($admin_allowed ? true : false),
	'S_INPUT_ALLOWED' => ($input_allowed ? true : false),
	'S_EDIT_ALLOWED' => ($edit_allowed ? true : false),
	'S_MODE_ACTION' => append_sid(THIS_FILE),
	'S_ACTION' => append_sid(THIS_FILE),
	)
);

// BBCBMG - BEGIN
if ($s_bbcb_global)
{
	define('BBCB_MG_CUSTOM', true);
	include_once(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
}
// BBCBMG - END

full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>