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
$donations_admin_auth = (!empty($plugin_config['donations_founder_manage']) ? AUTH_FOUNDER : AUTH_ADMIN);
$admin_allowed = check_auth_level($donations_admin_auth) ? true : false;
$input_allowed = check_auth_level($donations_admin_auth) ? true : false;
$edit_allowed = check_auth_level($donations_admin_auth) ? true : false;

// EXTRA DB OVERLAY - BEGIN
$sql_select_extra = ", u.username as user_username, u.user_active, u.user_color, u.group_id, u.user_email, u.user_website";
$sql_from_extra = ", " . USERS_TABLE . " u";
$sql_where_extra = "u.user_id = i.user_id";
// EXTRA DB OVERLAY - END

// SORTING OPTIONS OVERLAY - BEGIN
$sort_order_default = 'payment_time';
$sort_dir_default = 'DESC';
// SORTING OPTIONS OVERLAY - END

include(DONATIONS_ROOT_PATH . 'donations_array.' . PHP_EXT);
$class_db->main_db_table = PLUGINS_DONATIONS_DB_TABLE;
$class_db->main_db_item = PLUGINS_DONATIONS_DB_ITEM;

include(IP_ROOT_PATH . 'includes/common_forms.' . PHP_EXT);

if ((in_array($mode, array('input', 'save')) && !$admin_allowed && ((($action == 'add') && !$input_allowed) || (($action == 'edit') && !$edit_allowed))) || (($mode == 'delete') && !$admin_allowed))
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
}
// COMMON INCLUDES AND OPTIONS - END

// Start output of page
$meta_content['page_title'] = $lang['MG_DONATIONS'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';
$breadcrumbs['bottom_right_links'] = '';
if ($input_allowed)
{
	$breadcrumbs['bottom_left_links'] = '';
	$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(THIS_FILE . '?mode=input') . '">' . $lang['DONATION_ADD'] . '</a>';
}
$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid('donate.' . PHP_EXT) . '">' . $lang['MG_DONATE'] . '</a>';
$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(THIS_FILE) . '">' . $lang['MG_DONATIONS'] . '</a>';

if ($mode == 'save')
{
	$current_time = time();
	$class_form->create_inputs_array($table_fields, $inputs_array, $current_time, $item_id, $mode, $action);

	// EXTRA ASSIGNMENTS - BEGIN
	if ($inputs_array['user_id'] >= 2)
	{
		$sql_row = array();
		$sql_row = get_userdata($inputs_array['user_id']);
		if (!empty($sql_row['username']))
		{
			$inputs_array['username'] = $sql_row['username'];
		}
		if (!empty($sql_row['user_website']) && empty($inputs_array['payer_website']))
		{
			$inputs_array['payer_website'] = $sql_row['user_website'];
		}
	}

	// We keep this here just to make sure that username is filled with something if the user is a guest or if we want to force the username with something else...
	$inputs_array['username'] = (!empty($inputs_array['username']) ? $inputs_array['username'] : ($inputs_array['last_name'] . ' ' . $inputs_array['first_name']));
	// EXTRA ASSIGNMENTS - END

	if (($action == 'edit') && ($inputs_array[$item_id] > 0))
	{
		$class_db->update_item($inputs_array[$item_id], $inputs_array);
		$message = $lang['DB_ITEM_UPDATED'];
	}
	else
	{
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
	$item_title = $items_row['donation_user_name'];
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
	$template_to_parse = $class_plugins->get_tpl_file(DONATIONS_TPL_PATH, 'donations_body.tpl');
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
		$template->assign_var('NO_DONATIONS', true);
	}
	else
	{
		include_once(DONATIONS_ROOT_PATH . 'includes/functions_paypal.' . PHP_EXT);
		$donate = new paypal_class();
		$donation_minimum_amount = $plugin_config['donations_donate_minimum'];

		$row_class = '';
		for ($i = 0; $i < $page_items; $i++)
		{
			$donation_username = ((($items_array[$i]['user_id'] == ANONYMOUS) && !empty($items_array[$i]['username'])) ? $items_array[$i]['username'] : colorize_username($items_array[$i]['user_id'], $items_array[$i]['user_username'], $items_array[$i]['user_color'], $items_array[$i]['user_active']));

			$donation_username = (!empty($donation_username) ? $donation_username : (!empty($items_array[$i]['last_name']) ? ($items_array[$i]['last_name'] . ' ' . $items_array[$i]['first_name']) : $lang['Guest']));

			$donation_email = (!empty($items_array[$i]['payer_email']) ? $items_array[$i]['payer_email'] : (!empty($items_array[$i]['user_email']) ? $items_array[$i]['user_email'] : ''));
			$donation_email_link = (!empty($donation_email) ? ('&nbsp;<a href="mailto:' . $donation_email . '" target="_blank"><img src="' . $images['icon_email'] . '" alt="' . $lang['EMAIL'] . '" title="' . $donation_email . '" /></a>&nbsp;') : '&nbsp;');

			$donation_amount = $items_array[$i]['payment_gross'];
			if (!empty($items_array[$i]['mc_currency']) && ($items_array[$i]['mc_currency'] != $plugin_config['donations_default_currency']))
			{
				// If the payer currency is not the default currency, convert the default currency to the payer currency to determine if they paid the minimum in that currency.
				$donation_amount = $donate->convert_currency($plugin_config['donations_default_currency'], $items_array[$i]['mc_currency'], $items_array[$i]['payment_gross']);
				$donation_amount = round($donation_amount, 2);
			}

			$donation_website = '';
			$donation_website_link = '';
			if (!empty($items_array[$i]['payer_website_display']))
			{
				$donation_website = (!empty($items_array[$i]['payer_website']) ? $items_array[$i]['payer_website'] : (!empty($items_array[$i]['user_website']) ? $items_array[$i]['user_website'] : ''));
				$donation_website_text = (!empty($items_array[$i]['payer_website_text']) ? $items_array[$i]['payer_website_text'] : $donation_website);
				// 0 = image, 1 = text link
				$display_text_link = !empty($items_array[$i]['payer_website_link_type']) ? true : false;
				if (!empty($donation_website))
				{
					$donation_website_link = '&nbsp;<a href="' . $donation_website . '" title="' . $donation_website_text . '"' . (!empty($items_array[$i]['payer_website_sponsor']) ? ' rel="nofollow"' : '') . ' target="_blank">' . ($display_text_link ? $donation_website_text : ('<img src="' . $images['icon_www'] . '" alt="' . $donation_website_text . '" />')) . '</a>&nbsp;';
				}
			}

			$view_link = append_sid(THIS_FILE . '?mode=view&amp;' . $item_id . '=' . $items_array[$i][$item_id]);
			$view_img = '<a href="' . $view_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_preview'] . '" alt="' . $lang['DONATION_VIEW'] . '" title="' . $lang['DONATION_VIEW'] . '" /></a>';

			$edit_link = append_sid(THIS_FILE . '?mode=input&amp;action=edit&amp;' . $item_id . '=' . $items_array[$i][$item_id]);
			$edit_img = '<a href="' . $edit_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_edit'] . '" alt="' . $lang['EDIT'] . '" title="' . $lang['EDIT'] . '" /></a>';

			$delete_link = append_sid(THIS_FILE . '?mode=delete&amp;' . $item_id . '=' . $items_array[$i][$item_id]);
			$delete_img = '<a href="' . $delete_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_delete'] . '" alt="' . $lang['DELETE'] . '" title="' . $lang['DELETE'] . '" /></a>';

			$row_class = ip_zebra_rows($row_class);
			$template->assign_block_vars('donations', array(
				'CLASS' => $row_class,
				//'ROW_NUMBER' => $i + 1,
				'ROW_NUMBER' => $items_array[$i][$item_id],

				//'DATE' => create_date_ip($config['default_dateformat'], $items_array[$i]['payment_time'], $config['board_timezone']),
				'DATE' => create_date_ip('Y/m/d', $items_array[$i]['payment_time'], $config['board_timezone'], true),
				'USERNAME' => $donation_username,
				'WEBSITE' => $donation_website_link,
				'U_WEBSITE' => $donation_website,
				'TEXT_LINK' => $display_text_link,
				'EMAIL' => $donation_email_link,
				'U_EMAIL' => $donation_email,
				'AMOUNT' => $donation_amount,

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

		$total_items = $class_db->get_total_items('', $filter_item, $filter_item_value);
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
	'U_DONATE' => append_sid('donate.' . PHP_EXT),
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