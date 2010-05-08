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

// COMMON INCLUDES AND OPTIONS - BEGIN
$inputs_array = array();
$admin_allowed = check_auth_level(AUTH_FOUNDER) ? true : false;
$input_allowed = check_auth_level(AUTH_ADMIN) ? true : false;
$edit_allowed = check_auth_level(AUTH_ADMIN) ? true : false;

// EXTRA DB OVERLAY - BEGIN
$sql_select_extra = ", u.username, u.user_active, u.user_color, u2.username as username2, u2.user_active as user_active2, u2.user_color as user_color2";
$sql_from_extra = ", " . USERS_TABLE . " u, " . USERS_TABLE . " u2";
$sql_where_extra = "u.user_id = i.guestbook_user_id_create AND u2.user_id = i.guestbook_user_id_update";
// EXTRA DB OVERLAY - END

// SORTING OPTIONS OVERLAY - BEGIN
$sort_order_default = 'guestbook_time_update';
$sort_dir_default = 'DESC';
// SORTING OPTIONS OVERLAY - END

include(IP_ROOT_PATH . 'includes/common_forms.' . PHP_EXT);

if ((in_array($mode, array('input', 'save')) && !$admin_allowed && ((($action == 'add') && !$input_allowed) || (($action == 'edit') && !$edit_allowed))) || (($mode == 'delete') && !$admin_allowed))
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
	$breadcrumbs_links_right .= (($breadcrumbs_links_right != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(THIS_FILE . '?mode=input') . '">' . $lang['GUESTBOOK_ADD'] . '</a>';
}
$breadcrumbs_links_right .= (($breadcrumbs_links_right != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(THIS_FILE) . '">' . $lang['GUESTBOOKS_LINK_ALL'] . '</a>';

if ($mode == 'save')
{
	$current_time = time();
	$class_form->create_inputs_array($table_fields, $inputs_array, $current_time, $item_id, $mode, $action);
	$inputs_array = array_map('htmlspecialchars_decode', $inputs_array);

	// EXTRA ASSIGNMENTS - BEGIN
	$inputs_array['guestbook_owner'] = empty($inputs_array['guestbook_owner']) ? ANONYMOUS : $class_form->get_user_id($inputs_array['guestbook_owner']);
	//$inputs_array['guestbook_user_id_update'] = ($admin_allowed) ? $inputs_array['guestbook_user_id_update'] : $userdata['user_id'];
	$inputs_array['guestbook_user_id_update'] = $userdata['user_id'];
	$inputs_array['guestbook_time_update'] = $current_time;
	// EXTRA ASSIGNMENTS - END

	if (($action == 'edit') && ($inputs_array[$item_id] > 0))
	{
		$class_db->update_item($inputs_array[$item_id], $inputs_array);
		$message = $lang['DB_ITEM_UPDATED'];
	}
	else
	{
		// EXTRA ASSIGNMENTS - BEGIN
		$inputs_array['guestbook_owner'] = ($inputs_array['guestbook_owner'] == ANONYMOUS) ? $userdata['user_id'] : $inputs_array['guestbook_owner'];
		$inputs_array['guestbook_user_id_create'] = $userdata['user_id'];
		$inputs_array['guestbook_time_creation'] = $current_time;
		// EXTRA ASSIGNMENTS - END

		// Re-assign action just in case the above condition is not verified!
		$action == 'add';

		// If it is a new insert, we need to unset the $item_id, because it will be automatically incremented by the DB
		unset($inputs_array[$item_id]);

		$class_db->insert_item($inputs_array);
		// Get the ID of the new item
		$inputs_array[$item_id] = $db->sql_nextid();
		$message = $lang['DB_ITEM_ADDED'];
	}

	$message .= '<br /><br />' . sprintf($lang['DB_ITEM_CLICK_VIEW_ITEM'], '<a href="' . append_sid(THIS_FILE . '?mode=view&amp;' . $item_id . '=' . $inputs_array[$item_id]) . '">', '</a>');
	$message .= '<br /><br />' . sprintf($lang['DB_ITEM_CLICK_RETURN_ITEMS'], '<a href="' . append_sid(THIS_FILE) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'delete')
{
	$inputs_array[$item_id] = request_var($item_id, 0);
	if ($inputs_array[$item_id] > 0)
	{
		$class_db->delete_item($inputs_array[$item_id]);
		$message = $lang['DB_ITEM_REMOVED'] . '<br /><br />' . sprintf($lang['DB_ITEM_CLICK_RETURN_ITEMS'], '<a href="' . append_sid(THIS_FILE) . '">', '</a>');
	}
	else
	{
		$message = $lang['Error'];
	}

	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'input')
{
	$inputs_array[$item_id] = request_var($item_id, 0);
	$s_hidden_fields = '<input type="hidden" name="mode" value="save" />';
	$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
	$s_hidden_fields .= (($action == 'edit') && ($inputs_array[$item_id] > 0)) ? '<input type="hidden" name="' . $item_id . '" value="' . $inputs_array[$item_id] . '" />' : '';

	$items_row = array();

	if (($action == 'edit') && ($inputs_array[$item_id] > 0))
	{
		$items_row = $class_db->get_item($inputs_array[$item_id]);
		if (empty($items_row))
		{
			message_die(GENERAL_ERROR, 'Could not query data table', $lang['Error'], __LINE__, __FILE__);
		}
	}
	else
	{
		// Re-assign action just in case the above condition is not verified!
		$action == 'add';
	}

	// EXTRA ASSIGNMENTS - BEGIN
	$items_row['guestbook_owner'] = $class_form->get_username($items_row['guestbook_owner']);
	//$table_fields['guestbook_user_id_update']['default'] = ($admin_allowed && ($action == 'edit')) ? $table_fields['guestbook_user_id_update']['default'] : $userdata['user_id'];
	//$items_row['guestbook_user_id_update'] = ($admin_allowed && ($action == 'edit')) ? $items_row['guestbook_user_id_update'] : $userdata['user_id'];
	// EXTRA ASSIGNMENTS - END

	$template_to_parse = 'items_add_body.tpl';

	$s_bbcb_global = false;
	$class_form->create_input_form($table_fields, $inputs_array, $current_time, $s_bbcb_global, $mode, $action, $items_row);

	$template->assign_vars(array(
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

}
elseif ($mode == 'view')
{
	$inputs_array[$item_id] = request_var($item_id, 0);
	$items_row = array();
	$items_row = $class_db->get_item($inputs_array[$item_id], $sql_select_extra, $sql_from_extra, $sql_where_extra);
	if (empty($items_row))
	{
		message_die(GENERAL_ERROR, 'Could not query data table', $lang['Error'], __LINE__, __FILE__);
	}

	// Page Title - BEGIN
	$item_title = htmlspecialchars($items_row['guestbook_title']);
	$meta_content['page_title'] = (!empty($item_title) ? (strip_tags($item_title) . ' - ') : '') . $meta_content['page_title'];
	// Page Title - END

	$template_to_parse = 'items_view_body.tpl';

	$class_form->create_view_page($table_fields, $inputs_array, $items_row);

	$template->assign_vars(array(
		'U_ITEM_EDIT' => append_sid(THIS_FILE . '?mode=input&amp;action=edit&amp;' . $item_id . '=' . $inputs_array[$item_id]),
		'EXTRA_CONTENT_TOP' => (!empty($extra_content_top) ? $extra_content_top : ''),
		'EXTRA_CONTENT_BOTTOM_FORM' => (!empty($extra_content_bottom_form) ? $extra_content_bottom_form : ''),
		'EXTRA_CONTENT_BOTTOM' => (!empty($extra_content_bottom) ? $extra_content_bottom : ''),
		)
	);

}
else
{
	$template_to_parse = $class_plugins->get_tpl_file(GUESTBOOKS_TPL_PATH, 'guestbooks_body.tpl');
	$template->assign_vars(array(
		'S_SORT_ORDER_SELECT' => $sort_order_select_box,
		'S_SORT_DIR_SELECT' => $sort_dir_select_box,
		'S_HIDDEN_FIELDS' => '<input type="hidden" name="mode" value="' . $mode . '" />' . $s_hidden_fields
		)
	);

	$filter_item = (isset($filter_item) ? $filter_item : '');
	$filter_item_value = (isset($filter_item_value) ? $filter_item_value : '');
	$items_array = $class_db->get_items($n_items, $start, $sort_order, $sort_dir, $sql_select_extra, $sql_from_extra, $sql_where_extra, $filter_item, $filter_item_value);
	$page_items = sizeof($items_array);

	if ($page_items == 0)
	{
		$template->assign_var('NO_GUESTBOOKS', true);
	}
	else
	{
		for ($i = 0; $i < $page_items; $i++)
		{
			$guestbook_poster = colorize_username($items_array[$i]['guestbook_user_id_create'], $items_array[$i]['username'], $items_array[$i]['user_color'], $items_array[$i]['user_active']);

			$guestbook_last_poster = colorize_username($items_array[$i]['guestbook_user_id_update'], $items_array[$i]['username2'], $items_array[$i]['user_color2'], $items_array[$i]['user_active2']);

			//$view_link = append_sid(THIS_FILE . '?mode=view&amp;' . $item_id . '=' . $items_array[$i][$item_id]);
			$view_link = append_sid(CMS_PAGE_GUESTBOOK . '?' . $class_guestbooks->guestbook_id_var . '=' . $items_array[$i][$item_id]);
			$view_img = '<a href="' . $view_link . '"><img src="' . IP_ROOT_PATH . 'images/cms/b_preview.png" alt="' . $lang['GUESTBOOK_VIEW'] . '" title="' . $lang['GUESTBOOK_VIEW'] . '" /></a>';

			$edit_link = append_sid(THIS_FILE . '?mode=input&amp;action=edit&amp;' . $item_id . '=' . $items_array[$i][$item_id]);
			$edit_img = '<a href="' . $edit_link . '"><img src="' . IP_ROOT_PATH . 'images/cms/b_edit.png" alt="' . $lang['EDIT'] . '" title="' . $lang['EDIT'] . '" /></a>';

			$delete_link = append_sid(THIS_FILE . '?mode=delete&amp;' . $item_id . '=' . $items_array[$i][$item_id]);
			$delete_img = '<a href="' . $delete_link . '"><img src="' . IP_ROOT_PATH . 'images/cms/b_delete.png" alt="' . $lang['DELETE'] . '" title="' . $lang['DELETE'] . '" /></a>';

			$class = ($i % 2) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('guestbooks', array(
				'CLASS' => $class,
				//'ROW_NUMBER' => $i + 1,
				'ROW_NUMBER' => $items_array[$i][$item_id],

				'STATUS' => $class_form->get_lang_from_value($items_array[$i]['guestbook_status'], $table_fields['guestbook_status']['values']),
				'TITLE' => ((strlen($items_array[$i]['guestbook_title']) > 55) ? (htmlspecialchars(substr(htmlspecialchars_decode($items_array[$i]['guestbook_title'], ENT_COMPAT), 0, 52)) . '...') : $items_array[$i]['guestbook_title']),
				'DATE' => create_date_ip($config['default_dateformat'], $items_array[$i]['guestbook_time_creation'], $config['board_timezone']),
				'DATE_UPDATE' => create_date_ip($config['default_dateformat'], $items_array[$i]['guestbook_time_update'], $config['board_timezone']),
				'POSTER' => $guestbook_poster,
				'LAST_POSTER' => $guestbook_last_poster,

				'U_VIEW' => $view_link,
				'S_VIEW' => $view_img,
				'U_EDIT' => $edit_link,
				'S_EDIT' => $edit_img,
				'U_DELETE' => $delete_link,
				'S_DELETE' => $delete_img,
				)
			);
		}
		$db->sql_freeresult($result);

		$total_items = $class_db->get_total_items();
		$pagination = generate_pagination(append_sid(THIS_FILE . '?' . $url_full_append), $total_items, $n_items, $start) . '&nbsp;';
		$template->assign_vars(array(
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $n_items) + 1), ceil($total_items / $n_items)),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
	}
}

$template->assign_vars(array(
	'L_PAGE_NAME' => $meta_content['page_title'],
	'L_ITEM_TITLE' => !empty($item_title) ? $item_title : false,
	'U_ITEM_TITLE' => append_sid(THIS_FILE . '?mode=view' . ((!empty($inputs_array[$item_id])) ? ('&amp;' . $item_id . '=' . $inputs_array[$item_id]) : '')),
	'U_ITEM_ADD' => append_sid(THIS_FILE . '?mode=input'),

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