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

// Usage example
//http://localhost/ip/feedback.php?mode=input&t=101&u=3

$input_allowed = false;
if ($user->data['session_logged_in'])
{
	$input_allowed = true;
}

$admin_allowed = false;
//if ($user->data['user_level'] == ADMIN)
if ($user->data['user_id'] == '2')
{
	$admin_allowed = true;
}

// Start output of page
$meta_content['page_title'] = $lang['MG_FEEDBACK'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';
$cms_page['page_nav'] = true;
$cms_page['global_blocks'] = false;

$mode_types = array('list', 'delete', 'input', 'save');
$mode = request_var('mode', $mode_types[0]);
$mode = (!in_array($mode, $mode_types) ? $mode_types[0] : $mode);
$mode = (!$input_allowed ? $mode_types[0] : $mode);
$mode = ((!$admin_allowed && ($mode == 'delete')) ? $mode_types[0] : $mode);

$action_types = array('add', 'edit');
$action = request_var('action', $action_types[0]);
$action = (!in_array($action, $action_types) ? $action_types[0] : $action);
$action = (!$input_allowed ? $action_types[0] : $action);
$action = ((!$admin_allowed && ($action == 'edit')) ? $action_types[0] : $action);

$feedback_user = (isset($_GET[POST_USERS_URL])) ? intval($_GET[POST_USERS_URL]) : false;
$feedback_user = ($feedback_user < 2) ? false : $feedback_user;

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ($mode == 'save')
{
	$feedback_id = (isset($_GET['feedback_id']) ? intval($_GET['feedback_id']) : (isset($_POST['feedback_id']) ? intval($_POST['feedback_id']) : '0'));
	$feedback_time = (isset($_POST['feedback_time'])) ? trim($_POST['feedback_time']) : time();
	$feedback_transaction = (isset($_POST['feedback_transaction'])) ? trim($_POST['feedback_transaction']) : '';
	$feedback_rating = (isset($_POST['feedback_rating'])) ? intval($_POST['feedback_rating']) : '0';
	$feedback_description = (isset($_POST['feedback_description'])) ? trim($_POST['feedback_description']) : '';
	$feedback_url = (isset($_POST['feedback_url'])) ? trim($_POST['feedback_url']) : '';
	$feedback_topic_id = (isset($_POST['feedback_topic_id'])) ? intval($_POST['feedback_topic_id']) : '0';
	$feedback_user_id_from = (isset($_POST['feedback_user_id_from'])) ? intval($_POST['feedback_user_id_from']) : '-1';
	$feedback_user_id_to = (isset($_POST['feedback_user_id_to'])) ? intval($_POST['feedback_user_id_to']) : '-1';

	$feedback_rating = (($feedback_rating < PLUGINS_FEEDBACK_RATING_START) || ($feedback_rating > PLUGINS_FEEDBACK_RATING_END)) ? 0 : $feedback_rating;
	$feedback_topic_id = ($feedback_topic_id < 0) ? 0 : $feedback_topic_id;
	$feedback_user_id_from = ($feedback_user_id_from < 0) ? 0 : $feedback_user_id_from;
	$feedback_user_id_to = ($feedback_user_id_to < 0) ? 0 : $feedback_user_id_to;

	$feedback_time = addslashes($feedback_time);
	$feedback_transaction = addslashes($feedback_transaction);
	$feedback_rating = addslashes($feedback_rating);
	$feedback_description = addslashes($feedback_description);
	$feedback_url = addslashes($feedback_url);
	$feedback_topic_id = addslashes($feedback_topic_id);
	$feedback_user_id_from = addslashes($feedback_user_id_from);
	$feedback_user_id_to = addslashes($feedback_user_id_to);

	if ($feedback_topic_id <= 0)
	{
		$message = $lang['FEEDBACK_TOPIC_ID_ERROR'];
		message_die(GENERAL_MESSAGE, $message);
	}

	if (!$admin_allowed)
	{
		if (($feedback_user_id_to < 2) || ($feedback_user_id_to == $user->data['user_id']))
		{
			$message = $lang['FEEDBACK_USER_ID_ERROR'];
			message_die(GENERAL_MESSAGE, $message);
		}

		if (!can_user_give_feedback_topic($feedback_user_id_from, $feedback_topic_id))
		{
			$message = $lang['FEEDBACK_ALREADY_GIVEN'];
			message_die(GENERAL_MESSAGE, $message);
		}
		$can_give_feedback = can_user_give_feedback_global($user->data['user_id'], $feedback_topic_id);
		if (!$can_give_feedback)
		{
			$message = $lang['FEEDBACK_TOPIC_ID_ERROR'];
			message_die(GENERAL_MESSAGE, $message);
		}
	}

	if (($feedback_rating == '') || ($feedback_description == ''))
	{
		message_die(GENERAL_MESSAGE, $lang['MUST_ENTER_FEEDBACK']);
	}

	if (($action == 'edit') && ($feedback_id > 0))
	{
		$sql = "UPDATE " . PLUGINS_FEEDBACK_TABLE . " SET
			feedback_time = '" . $feedback_time . "',
			feedback_transaction = '" . $feedback_transaction . "',
			feedback_rating = '" . $feedback_rating . "',
			feedback_description = '" . $feedback_description . "',
			feedback_url = '" . $feedback_url . "',
			feedback_topic_id = '" . $feedback_topic_id . "',
			feedback_user_id_from = '" . $feedback_user_id_from . "',
			feedback_user_id_to = '" . $feedback_user_id_to . "'
			WHERE feedback_id = " . $feedback_id;
		$message = $lang['FEEDBACK_UPDATED'];
	}
	else
	{
		// Re-assign action just in case the above condition is not verified!
		$action == 'add';
		$sql = "INSERT INTO " . PLUGINS_FEEDBACK_TABLE . " (feedback_time, feedback_transaction, feedback_rating, feedback_description, feedback_url, feedback_topic_id, feedback_user_id_from, feedback_user_id_to)
			VALUES ('" . $feedback_time . "', '" . $feedback_transaction . "', '" . $feedback_rating . "', '" . $feedback_description . "', '" . $feedback_url . "', '" . $feedback_topic_id . "', '" . $feedback_user_id_from . "', '" . $feedback_user_id_to . "')";
		$message = $lang['FEEDBACK_ADDED'];
	}

	$result = $db->sql_query($sql);

	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_FEEDBACK'], '<a href="' . append_sid(PLUGINS_FEEDBACK_FILE) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_HOME) . '">', '</a>');

	$db->clear_cache('feedback_');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'delete')
{
	$feedback_id = (isset($_GET['feedback_id']) ? intval($_GET['feedback_id']) : (isset($_POST['feedback_id']) ? intval($_POST['feedback_id']) : '0'));
	if ($feedback_id > 0)
	{
		$sql = "DELETE FROM " . PLUGINS_FEEDBACK_TABLE . "
						WHERE feedback_id = '" . $feedback_id . "'";
		$result = $db->sql_query($sql);

		$message = $lang['FEEDBACK_REMOVED'] . '<br /><br />' . sprintf($lang['CLICK_RETURN_FEEDBACK'], '<a href="' . append_sid(PLUGINS_FEEDBACK_FILE) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid(CMS_PAGE_HOME) . '">', '</a>');
	}
	else
	{
		$message = $lang['Error'];
	}

	$db->clear_cache('feedback_');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'input')
{
	$feedback_id = (isset($_GET['feedback_id']) ? intval($_GET['feedback_id']) : (isset($_POST['feedback_id']) ? intval($_POST['feedback_id']) : '0'));
	$s_hidden_fields = '<input type="hidden" name="mode" value="save" />';
	$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
	$s_hidden_fields .= (($action == 'edit') && ($feedback_id > 0)) ? '<input type="hidden" name="feedback_id" value="' . $feedback_id . '" />' : '';
	if (($action == 'edit') && ($feedback_id > 0))
	{
		$sql = "SELECT * FROM " . PLUGINS_FEEDBACK_TABLE . "
						WHERE feedback_id = '" . $feedback_id . "'
						LIMIT 1";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$feedback_time = stripslashes($row['feedback_time']);

			$feedback_transaction = stripslashes($row['feedback_transaction']);
			$transaction_array = array(0, 1, 2);
			$transaction_lang_array = array($lang['FEEDBACK_BUYER'], $lang['FEEDBACK_SELLER'], $lang['FEEDBACK_EXCHANGER']);
			$feedback_transaction_select = $class_form->build_select_box('feedback_transaction', $feedback_transaction, $transaction_array, $transaction_lang_array, '');

			$feedback_rating = stripslashes($row['feedback_rating']);
			$rating_array = array();
			for ($j = PLUGINS_FEEDBACK_RATING_START; $j <= PLUGINS_FEEDBACK_RATING_END; $j++)
			{
				$rating_array[] = $j;
			}
			$feedback_rating_select = $class_form->build_select_box('feedback_rating', $feedback_rating, $rating_array, $rating_array, '');

			$feedback_description = htmlspecialchars(stripslashes($row['feedback_description']));
			$feedback_url = stripslashes($row['feedback_url']);
			$feedback_topic_id = stripslashes($row['feedback_topic_id']);
			$feedback_user_id_from = stripslashes($row['feedback_user_id_from']);
			$feedback_user_id_to = stripslashes($row['feedback_user_id_to']);
		}
		$db->sql_freeresult($result);
		if (!$admin_allowed)
		{
			$s_hidden_fields .= '<input type="hidden" name="feedback_time" value="' . $feedback_time . '" />';
			$s_hidden_fields .= '<input type="hidden" name="feedback_topic_id" value="' . $feedback_topic_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="feedback_transaction" value="' . htmlspecialchars($feedback_transaction) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="feedback_user_id_from" value="' . $feedback_user_id_from . '" />';
			$s_hidden_fields .= '<input type="hidden" name="feedback_user_id_to" value="' . $feedback_user_id_to . '" />';
		}
	}
	else
	{
		// Re-assign action just in case the above condition is not verified!
		$action == 'add';
		$feedback_time = time();

		$feedback_transaction = 0;
		$transaction_array = array(0, 1, 2);
		$transaction_lang_array = array($lang['FEEDBACK_BUYER'], $lang['FEEDBACK_SELLER'], $lang['FEEDBACK_EXCHANGER']);
		$feedback_transaction_select = $class_form->build_select_box('feedback_transaction', $feedback_transaction, $transaction_array, $transaction_lang_array, '');

		$feedback_rating = PLUGINS_FEEDBACK_RATING_END;
		$rating_array = array();
		for ($j = PLUGINS_FEEDBACK_RATING_START; $j <= PLUGINS_FEEDBACK_RATING_END; $j++)
		{
			$rating_array[] = $j;
		}
		$feedback_rating_select = $class_form->build_select_box('feedback_rating', $feedback_rating, $rating_array, $rating_array, '');

		$feedback_description = '';
		$feedback_url = '';

		$feedback_topic_id = (!empty($_GET[POST_TOPIC_URL])) ? intval($_GET[POST_TOPIC_URL]) : '0';
		if ($feedback_topic_id <= 0)
		{
			$message = $lang['FEEDBACK_TOPIC_ID_ERROR'];
			message_die(GENERAL_MESSAGE, $message);
		}

		$feedback_user_id_from = $user->data['user_id'];
		$feedback_user_id_to = (!empty($_GET[POST_USERS_URL])) ? intval($_GET[POST_USERS_URL]) : '-1';

		if (!$admin_allowed)
		{
			if (($feedback_user_id_to < 2) || ($feedback_user_id_to == $user->data['user_id']))
			{
				$message = $lang['FEEDBACK_USER_ID_ERROR'];
				message_die(GENERAL_MESSAGE, $message);
			}

			if (!can_user_give_feedback_topic($feedback_user_id_from, $feedback_topic_id))
			{
				$message = $lang['FEEDBACK_ALREADY_GIVEN'];
				message_die(GENERAL_MESSAGE, $message);
			}
			$can_give_feedback = can_user_give_feedback_global($user->data['user_id'], $feedback_topic_id);
			if (!$can_give_feedback)
			{
				$message = $lang['FEEDBACK_TOPIC_ID_ERROR'];
				message_die(GENERAL_MESSAGE, $message);
			}

			$s_hidden_fields .= '<input type="hidden" name="feedback_time" value="' . $feedback_time . '" />';
			$s_hidden_fields .= '<input type="hidden" name="feedback_topic_id" value="' . $feedback_topic_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="feedback_transaction" value="' . htmlspecialchars($feedback_transaction) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="feedback_user_id_from" value="' . $feedback_user_id_from . '" />';
			$s_hidden_fields .= '<input type="hidden" name="feedback_user_id_to" value="' . $feedback_user_id_to . '" />';
		}
	}

	$template_to_parse = $class_plugins->get_tpl_file(FEEDBACK_TPL_PATH, 'feedback_add_body.tpl');
	$template->assign_vars(array(
		'S_ADMIN_ALLOWED' => ($admin_allowed ? true : false),
		'DATE' => $feedback_time,
		'RATING' => $feedback_rating,
		'RATING_SELECT' => $feedback_rating_select,
		'DESCRIPTION' => $feedback_description,
		'TRANSACTION' => $feedback_transaction,
		'TRANSACTION_SELECT' => $feedback_transaction_select,
		'USERID_FROM' => $feedback_user_id_from,
		'USERID_TO' => $feedback_user_id_to,

		'TRANSACTION_ID' => $feedback_topic_id,
		'U_TRANSACTION' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPICS_URL . '=' . $feedback_topic_id),

		'S_FEEDBACK_ACTION' => append_sid(PLUGINS_FEEDBACK_FILE),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}
else
{
	if (!$admin_allowed)
	{
		$sort_types_array = array('feedback_time', 'feedback_user_id_to');
		$sort_types_lang_array = array($lang['DATE'], $lang['USERNAME_TO']);
	}
	else
	{
		$template->assign_vars(array(
			'S_ADMIN_ALLOWED' => true,
			)
		);
		$sort_types_array = array('feedback_time', 'feedback_rating', 'feedback_user_id_from', 'feedback_user_id_to');
		$sort_types_lang_array = array($lang['DATE'], $lang['FEEDBACK_RATING'], $lang['USERNAME_FROM'], $lang['USERNAME_TO']);
	}

	if (isset($_GET['sort']) || isset($_POST['sort']))
	{
		$sort = (isset($_POST['sort'])) ? htmlspecialchars($_POST['sort']) : htmlspecialchars($_GET['sort']);
	}
	else
	{
		$sort = $sort_types_array[0];
	}

	if (!in_array($sort, $sort_types_array))
	{
		$sort = $sort_types_array[0];
	}

	$select_name = 'sort';
	$default = $sort;
	$select_js = '';
	$select_sort_type_box = $class_form->build_select_box($select_name, $default, $sort_types_array, $sort_types_lang_array, $select_js);

	$order_types_array = array('ASC', 'DESC');
	$order_types_lang_array = array($lang['Sort_Ascending'], $lang['Sort_Descending']);

	if(isset($_GET['order']) || isset($_POST['order']))
	{
		$order = (isset($_GET['order'])) ? htmlspecialchars($_GET['order']) : htmlspecialchars($_POST['order']);
	}
	else
	{
		$order = $order_types_array[0];
	}

	if (!in_array($order, $order_types_array))
	{
		$order = $order_types_array[0];
	}

	$select_name = 'order';
	$default = $order;
	$select_js = '';
	$select_order_type_box = $class_form->build_select_box($select_name, $default, $order_types_array, $order_types_lang_array, $select_js);

	$template_to_parse = $class_plugins->get_tpl_file(FEEDBACK_TPL_PATH, 'feedback_body.tpl');
	$template->assign_vars(array(
		'U_FEEDBACK_ADD' => append_sid(PLUGINS_FEEDBACK_FILE . '?mode=input'),
		'S_MODE_ACTION' => append_sid(PLUGINS_FEEDBACK_FILE),
		'S_SORT_SELECT' => $select_sort_type_box,
		'S_ORDER_SELECT' => $select_order_type_box,
		'S_HIDDEN_FIELDS' => '<input type="hidden" name="mode" value="list" />'
		)
	);

	$where_sql = '';
	if ($feedback_user != false)
	{
		$sql = "SELECT user_id FROM " . USERS_TABLE . "
						WHERE user_id = '" . $feedback_user . "'";
		$result = $db->sql_query($sql);

		$user_exists = false;
		while ($row = $db->sql_fetchrow($result))
		{
			$user_exists = true;
		}
		$db->sql_freeresult($result);

		if ($user_exists)
		{
			$where_sql = " AND f.feedback_user_id_to = '" . $feedback_user . "'";
		}
	}
	$sql = "SELECT f.*, u.user_id, u.username, u.user_active, u.user_color, u2.username as user2, u2.user_id as id2, u2.user_active as user_active2, u2.user_color as user_color2
					FROM " . PLUGINS_FEEDBACK_TABLE . " f, " . USERS_TABLE . " u, " . USERS_TABLE . " u2
					WHERE u.user_id = f.feedback_user_id_from
						AND u2.user_id = f.feedback_user_id_to
						" . $where_sql . "
					ORDER BY " . $sort . " " . $order . "
					LIMIT " . $start . ", " . $config['topics_per_page'];
	$result = $db->sql_query($sql, 0, 'feedback_');

	$feedback_counter = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$feedback_counter++;
		$feedback_time = stripslashes($row['feedback_time']);
		$feedback_transaction = stripslashes($row['feedback_transaction']);
		switch ($feedback_transaction)
		{
			case 0:
				$feedback_transaction = $lang['FEEDBACK_BUYER'];
				break;
			case 1:
				$feedback_transaction = $lang['FEEDBACK_SELLER'];
				break;
			case 2:
				$feedback_transaction = $lang['FEEDBACK_EXCHANGER'];
				break;
			default:
				$feedback_transaction = $lang['FEEDBACK_BUYER'];
				break;

		}
		$feedback_rating = stripslashes($row['feedback_rating']);
		$feedback_description = stripslashes($row['feedback_description']);
		$feedback_url = stripslashes($row['feedback_url']);
		$feedback_topic_id = stripslashes($row['feedback_topic_id']);
		$feedback_user_id_from = stripslashes($row['feedback_user_id_from']);
		$feedback_user_name_from = ($feedback_user_id_from == ANONYMOUS) ? $lang['Guest'] : colorize_username($feedback_user_id_from, $row['username'], $row['user_color'], $row['user_active']);
		$feedback_user_id_to = stripslashes($row['feedback_user_id_to']);
		$feedback_user_name_to = ($feedback_user_id_to == ANONYMOUS) ? $lang['Guest'] : colorize_username($feedback_user_id_to, $row['user2'], $row['user_color2'], $row['user_active2']);

		$edit_link = append_sid(PLUGINS_FEEDBACK_FILE . '?mode=input&amp;action=edit&amp;feedback_id=' . $row['feedback_id']);
		$edit_img = '&nbsp;<a href="' . $edit_link . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['EDIT'] . '" title="' . $lang['EDIT'] . '" /></a>&nbsp;';

		$delete_link = append_sid(PLUGINS_FEEDBACK_FILE . '?mode=delete&amp;feedback_id=' . $row['feedback_id']);
		$delete_img = '&nbsp;<a href="' . $delete_link . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['DELETE'] . '" title="' . $lang['DELETE'] . '" /></a>&nbsp;';

		$class = ($feedback_counter % 2) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('feedback', array(
			'CLASS' => $class,
			'ROW_NUMBER' => $feedback_counter,
			'DATE' => create_date_ip($config['default_dateformat'], $feedback_time, $config['board_timezone']),
			'TRANSACTION' => $feedback_transaction,
			'DESCRIPTION' => $feedback_description,
			'RATING' => $feedback_rating,
			'RATING_IMG' => IP_ROOT_PATH . 'images/feedback/' . build_feedback_rating_image($feedback_rating),
			'USERID_FROM' => $feedback_user_id_from,
			'USERNAME_FROM' => $feedback_user_name_from . ' [&nbsp;' . $feedback_transaction . '&nbsp;]',
			'USERID_TO' => $feedback_user_id_to,
			'USERNAME_TO' => $feedback_user_name_to,

			'U_TRANSACTION' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $feedback_topic_id),

			'EDIT' => $edit_img,
			'U_EDIT' => $edit_link,
			'DELETE' => $delete_img,
			'U_DELETE' => $delete_link,
			)
		);
	}
	$db->sql_freeresult($result);

	if ($feedback_counter == 0)
	{
		$template->assign_block_vars('no_feedback', array());
	}

	$sql = "SELECT count(*) AS total FROM " . PLUGINS_FEEDBACK_TABLE . " WHERE feedback_user_id_to = '" . $feedback_user . "'";
	$result = $db->sql_query($sql);

	$total_feedback = 0;
	if ($total = $db->sql_fetchrow($result))
	{
		$total_feedback = $total['total'];
	}
	$pagination = generate_pagination(PLUGINS_FEEDBACK_FILE . '?sort=' . $sort . '&amp;order=' . $order, $total_feedback, $config['topics_per_page'], $start) . '&nbsp;';
	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $config['topics_per_page']) + 1), ceil($total_feedback / $config['topics_per_page'])),
		'L_GOTO_PAGE' => $lang['Goto_page']
		)
	);

}

full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>