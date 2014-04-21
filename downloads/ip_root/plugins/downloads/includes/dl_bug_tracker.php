<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/*
* clean up bug tracker for unset categories
* hard stuff to do this, but we must be sure to track downloads only in the choosen categories...
*/
$sql = "SELECT d.id FROM " . DL_CAT_TABLE . " c, " . DOWNLOADS_TABLE . " d
	WHERE c.bug_tracker = 0
		AND c.id = d.cat";
$result = $db->sql_query($sql);

$dl_ids = '';
while ($row = $db->sql_fetchrow($result))
{
	$dl_ids .= ($dl_ids) ? ', '.$row['id'] : $row['id'];
}
$db->sql_freeresult($result);

if ($dl_ids)
{
	$sql = "DELETE FROM " . DL_BUGS_TABLE . "
		WHERE df_id IN ($dl_ids)";
	$result = $db->sql_query($sql);

	$sql = "DELETE FROM " . DL_BUG_HISTORY_TABLE . "
		WHERE df_id IN ($dl_ids)";
	$result = $db->sql_query($sql);

	$dl_ids = '';
}

/*
* check the current user for mod permissions
*/
if (($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD))
{
	$allow_bug_mod = true;
}
else
{
	$allow_bug_mod = false;
}

/*
* save new or edited bug report
*/
if (($action == 'save') && $user->data['session_logged_in'])
{
	$report_title = request_var('report_title', '', true);

	if (!$report_title)
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_bug_report_no_title']);
	}

	$report_text = request_var('report_text', '', true);

	if (!$report_text)
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_bug_report_no_text']);
	}

	$html_on = 0;
	$bbcode_on = 1;
	$smilies_on = 0;

	$report_text = stripslashes(prepare_message(addslashes(unprepare_message($report_text)), 0, 1, 0));

	$report_file_ver = request_var('report_file_ver', '', true);
	$report_php = request_var('report_php', '', true);
	$report_db = request_var('report_db', '', true);
	$report_forum = request_var('report_forum', '', true);

	$sql = "INSERT INTO " . DL_BUGS_TABLE . "
		(df_id, report_title, report_text, report_file_ver, report_date, report_author_id, report_status_date, report_php, report_db, report_forum)
		VALUES
		($df_id, '" . $db->sql_escape($report_title) . "', '" . $db->sql_escape($report_text) . "', '" . $db->sql_escape($report_file_ver) . "', " . time() . ", " . $user->data['user_id'] . ", " . time() . ", '" . $db->sql_escape($report_php) . "', '" . $db->sql_escape($report_db) . "', '" . $db->sql_escape($report_forum) . "')";
	$db->sql_query($sql);
	$fav_id = $db->sql_nextid();

	$sql = "INSERT INTO " . DL_BUG_HISTORY_TABLE . "
		(df_id, report_id, report_his_type, report_his_date, report_his_value)
		VALUES
		($df_id, $fav_id, 'status', " . time() . ", '0:" . $db->sql_escape($user->data['username']) . "')";
	$result = $db->sql_query($sql);

	$message = $lang['Dl_bug_report_added'] . '<br /><br />' . sprintf($lang['Click_return_bug_tracker'], '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}

/*
* add new status to report
*/
if ($action == 'status' && $fav_id && $allow_bug_mod)
{
	$new_status = request_var('new_status', 0);
	$new_status_text = request_var('new_status_text', '', true);
	$new_status_text = str_replace(':', ' ', $new_status_text);

	$sql = "SELECT df_id, report_status, report_author_id, report_title FROM " . DL_BUGS_TABLE . "
		WHERE report_id = $fav_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$df_id = $row['df_id'];
	$report_status = $row['report_status'];
	$report_author_id = $row['report_author_id'];
	$report_title = $row['report_title'];
	$db->sql_freeresult($result);

	$sql = "INSERT INTO " . DL_BUG_HISTORY_TABLE . "
		(df_id, report_id, report_his_type, report_his_date, report_his_value)
		VALUES
		($df_id, $fav_id, 'status', " . time() . ", '$new_status:" . $db->sql_escape($user->data['username']) . ":" . $db->sql_escape($new_status_text) . "')";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . DL_BUGS_TABLE . "
		SET report_status = $new_status, report_status_date = " . time() . "
		WHERE report_id = $fav_id";
	$result = $db->sql_query($sql);

	// Send email to report author about new status if it will not be the current one
	if ($report_author_id <> $user->data['user_id'])
	{
		$sql = "SELECT user_email, user_lang FROM " . USERS_TABLE . "
			WHERE user_id = $report_author_id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$user_email = $row['user_email'];
		$user_lang = $row['user_lang'];
		$db->sql_freeresult($result);

		if ($new_status_text)
		{
			$status_text = sprintf($lang['Dl_bug_report_email_status'], $new_status_text);
		}
		else
		{
			$status_text = '';
		}

		$script_path = $config['script_path'];
		$server_name = trim($config['server_name']);
		$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
		$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';

		$server_url = $server_name . $server_port . $script_path;
		$server_url = $server_protocol . str_replace('//', '/', $server_url);

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$config['smtp_delivery'])
		{
			$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions
			// since php's are broken by default
			$config['smtp_delivery'] = 1;
			$config['smtp_host'] = @$ini_val('SMTP');
		}

		$emailer = new emailer();

		$emailer->headers('X-AntiAbuse: Board servername - ' . trim($config['server_name']));
		$emailer->headers('X-AntiAbuse: User_id - ' . $user->data['user_id']);
		$emailer->headers('X-AntiAbuse: Username - ' . $user->data['username']);
		$emailer->headers('X-AntiAbuse: User IP - ' . $user_ip);

		$emailer->use_template('dl_bt_status', $user_lang);
		$emailer->to($user_email);
		$emailer->set_subject();

		$emailer->assign_vars(array(
			'SITENAME' => $config['sitename'],
			'BOARD_EMAIL' => $config['board_email_sig'],
			'USERNAME' => $user->data['username'],
			'REPORT_TITLE' => $report_title,
			'STATUS' => $lang['Dl_report_status'][$report_status],
			'STATUS_TEXT' => $status_text,
			'U_BUG_REPORT' => $server_url.'downloads.' . PHP_EXT . '?view=bug_tracker&action=detail&fav_id=' . $fav_id
			)
		);

		$emailer->send();
		$emailer->reset();
	}

	$action = 'detail';
}

/*
* assign bug report to team member
*/
if (($action == 'assign') && $df_id && $fav_id && $allow_bug_mod)
{
	$new_user_id = request_var('user_assign', 0);
	if (!$new_user_id)
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permissions']);
	}

	$sql = "SELECT username, user_email, user_lang FROM " . USERS_TABLE . "
		WHERE user_id = $new_user_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$report_assign_user = $row['username'];
	$report_assign_user_email = $row['user_email'];
	$report_assign_user_lang = $row['user_lang'];
	$db->sql_freeresult($result);

	$sql = "INSERT INTO " . DL_BUG_HISTORY_TABLE . "
		(df_id, report_id, report_his_type, report_his_date, report_his_value)
		VALUES
		($df_id, $fav_id, 'assign', " . time() . ", '$new_user_id:$report_assign_user')";
	$result = $db->sql_query($sql);

	$sql = "UPDATE " . DL_BUGS_TABLE . "
		SET report_assign_id = $new_user_id, report_assign_date = " . time() . "
		WHERE report_id = $fav_id";
	$result = $db->sql_query($sql);

	// Send email to new assigned user if it will not be the current one
	if ($new_user_id <> $user->data['user_id'])
	{
		$script_path = $config['script_path'];
		$server_name = trim($config['server_name']);
		$server_protocol = ( $config['cookie_secure'] ) ? 'https://' : 'http://';
		$server_port = ( $config['server_port'] <> 80 ) ? ':' . trim($config['server_port']) . '/' : '/';

		$server_url = $server_name . $server_port . $script_path;
		$server_url = $server_protocol . str_replace('//', '/', $server_url);

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$config['smtp_delivery'])
		{
			$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions
			// since php's are broken by default
			$config['smtp_delivery'] = 1;
			$config['smtp_host'] = @$ini_val('SMTP');
		}

		$emailer = new emailer();

		$emailer->headers('X-AntiAbuse: Board servername - ' . trim($config['server_name']));
		$emailer->headers('X-AntiAbuse: User_id - ' . $user->data['user_id']);
		$emailer->headers('X-AntiAbuse: Username - ' . $user->data['username']);
		$emailer->headers('X-AntiAbuse: User IP - ' . $user_ip);

		$emailer->use_template('dl_bt_assign', $report_assign_user_lang);
		$emailer->to($report_assign_user_email);
		$emailer->set_subject();

		$emailer->assign_vars(array(
			'SITENAME' => $config['sitename'],
			'BOARD_EMAIL' => $config['board_email_sig'],
			'USERNAME' => $user->data['username'],
			'U_BUG_REPORT' => $server_url.'downloads.' . PHP_EXT . '?view=bug_tracker&action=detail&fav_id=' . $fav_id
			)
		);

		$emailer->send();
		$emailer->reset();
	}

	$action = 'detail';
}

/*
* view current details from bug report
*/
if ($action == 'detail' && $fav_id)
{
	$sql = "SELECT b.*, d.description AS report_file, u1.username AS report_author, u2.username AS report_assign
		FROM " . DOWNLOADS_TABLE . " d, " . DL_BUGS_TABLE . " b
		LEFT JOIN " . USERS_TABLE . " u1 ON u1.user_id = b.report_author_id
		LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = b.report_assign_id
		WHERE b.df_id = d.id
			AND b.report_id = $fav_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$report_id = $fav_id;
	$report_file_id = $row['df_id'];
	$report_file = $row['report_file'];
	$report_title = $row['report_title'];
	$report_text = $row['report_text'];
	$report_uid = '';
	$report_file_ver = $row['report_file_ver'];
	$report_date = $row['report_date'];
	$report_author_id = $row['report_author_id'];
	$report_assign_id = $row['report_assign_id'];
	$report_assign_date = $row['report_assign_date'];
	$report_status = $row['report_status'];
	$report_status_date = $row['report_status_date'];
	$report_php = $row['report_php'];
	$report_db = $row['report_db'];
	$report_forum = $row['report_forum'];
	$report_author = $row['report_author'];
	$report_assign = $row['report_assign'];

	$db->sql_freeresult($result);

	// Change status in the report was new and a team member will open the details
	if (!$report_status && ($user->data['user_level'] == ADMIN || $user->data['user_level'] == MOD))
	{
		$sql = "INSERT INTO " . DL_BUG_HISTORY_TABLE . "
			(df_id, report_id, report_his_type, report_his_date, report_his_value)
			VALUES
			($report_file_id, $report_id, 'status', " . time() . ", '1:" . $user->data['username'] . "')";
		$result = $db->sql_query($sql);
		$report_status = 1;
		$report_status_date = time();

		$sql = "UPDATE " . DL_BUGS_TABLE . "
			SET report_status = $report_status, report_status_date = $report_status_date
			WHERE report_id = $report_id";
		$result = $db->sql_query($sql);
	}

	$u_report_file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $report_file_id);
	//$u_report_author_link = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $report_author_id);
	$u_report_author_link = colorize_username($report_author_id);

	if ($report_assign_id)
	{
		$template->assign_block_vars('assign', array(
			'L_ASSIGN_TO' => $lang['Dl_bug_report_assigned'],
			'L_ASSIGN_DATE' => $lang['Dl_bug_report_assign_date'],
			'ASSIGN_TO' => $report_assign,
			'ASSIGN_DATE' => create_date($config['default_dateformat'], $report_assign_date, $config['board_timezone']),
			//'U_ASSIGN_TO' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $report_assign_id)
			'U_ASSIGN_TO' => colorize_username($report_assign_id)
			)
		);
	}
	else
	{
		$template->assign_block_vars('no_assign', array(
			'L_NO_ASSIGN' => $lang['Dl_bug_report_unassigned'])
		);
	}

	$report_date = create_date($config['default_dateformat'], $report_date, $config['board_timezone']);

	$report_title = censor_text($report_title);
	$report_text = censor_text($report_text);
	$report_file_ver = censor_text($report_file_ver);
	$report_php = censor_text($report_php);
	$report_db = censor_text($report_db);
	$report_forum = censor_text($report_forum);

	$bbcode->allow_html = $config['allow_html'];
	$bbcode->allow_bbcode = $config['allow_bbcode'];
	$bbcode->allow_smilies = $config['allow_smilies'];
	$report_text = $bbcode->parse($report_text);

	//$report_text = str_replace("\n", "<br />", $report_text);

	$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_bt_detail.tpl');

	$template->assign_vars(array(
		'L_REPORT_ID' => $lang['Dl_bug_report_id'],
		'L_REPORT_TITLE' => $lang['Dl_bug_report_title'],
		'L_REPORT_TEXT' => $lang['Dl_bug_report_text'],
		'L_REPORT_DATE' => $lang['Dl_bug_report_date'],
		'L_REPORT_PHP' => $lang['Dl_bug_report_php'],
		'L_REPORT_DB' => $lang['Dl_bug_report_db'],
		'L_REPORT_FORUM' => $lang['Dl_bug_report_forum'],
		'L_REPORT_FILE' => $lang['Dl_bug_report_file'],
		'L_REPORT_FILE_VER' => $lang['Dl_hack_version'],
		'L_REPORT_AUTHOR' => $lang['Dl_bug_report_author'],
		'L_STATUS' => $lang['Dl_bug_report_status'],
		'L_STATUS_DATE' => $lang['Dl_bug_report_status_date'],
		'L_STATUS_TEXT' => $lang['Dl_bug_report_status_text'],
		'L_STATUS_UPDATE' => $lang['Dl_bug_report_status_update'],
		'L_SUBMIT' => $lang['Dl_bug_report_assign'],

		'L_DOWNLOADS' => $lang['Dl_cat_title'],
		'L_BUG_TRACKER' => $lang['Dl_bug_tracker'],

		'REPORT_ID' => $report_id,
		'REPORT_FILE' => $report_file,
		'REPORT_TITLE' => $report_title,
		'REPORT_TEXT' => $report_text,
		'REPORT_FILE_VER' => $report_file_ver,
		'REPORT_DATE' => $report_date,
		'REPORT_PHP' => $report_php,
		'REPORT_DB' => $report_db,
		'REPORT_FORUM' => $report_forum,
		'REPORT_AUTHOR' => $report_author,
		'REPORT_STATUS' => $lang['Dl_report_status'][$report_status],
		'STATUS_DATE' => create_date($config['default_dateformat'], $report_assign_date, $config['board_timezone']),

		'U_DOWNLOAD_FILE' => $u_report_file_link,
		'U_AUTHOR_LINK' => $u_report_author_link,
		'U_DOWNLOADS_ADV' => append_sid('downloads.' . PHP_EXT),
		'U_BUG_TRACKER' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker')
		)
	);

	// Begin report history
	$sql = "SELECT * FROM " . DL_BUG_HISTORY_TABLE . "
		WHERE report_id = $fav_id
		ORDER BY report_his_id DESC";
	$result = $db->sql_query($sql);
	$total_history = $db->sql_numrows($result);

	if ($total_history)
	{
		$template->assign_block_vars('history', array(
			'L_REPORT_HISTORY' => $lang['Dl_bug_report_history']
			)
		);

		while ($row = $db->sql_fetchrow($result))
		{
			$report_his_type = $row['report_his_type'];
			$report_his_value = $row['report_his_value'];

			$output_date = create_date($config['default_dateformat'], $row['report_his_date'], $config['board_timezone']);

			if ($report_his_type == 'assign')
			{
				$output_value = $lang['Dl_bug_report_assign'];
				$output_data = split(':', $report_his_value);

				//$output_text = $lang['Dl_bug_report_assigned'] . ' -> <a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $output_data[0]) . '" target="_blank">' . $output_data[1] . '</a>';
				$output_text = $lang['Dl_bug_report_assigned'] . '&nbsp;&raquo;&nbsp;' . colorize_username($output_data[0]);
			}
			elseif ($report_his_type == 'status')
			{
				$output_value = $lang['Dl_bug_report_status'];
				$output_data = split(":", $report_his_value);

				$output_status = intval($output_data[0]);
				$output_text = $lang['Dl_report_status'][$output_status] . '&nbsp;&raquo;&nbsp;' . $output_data[1];
				$output_text .= ($output_data[2]) ? '<hr />' . str_replace("\n", '<br />', $output_data[2]) : '';
			}

			$template->assign_block_vars('history.history_row', array(
				'VALUE' => $output_value,
				'DATE' => $output_date,
				'TEXT' => $output_text)
			);
		}
	}

	$db->sql_freeresult($result);

	if ($allow_bug_mod)
	{
		$template->assign_block_vars('delete', array(
			'L_DELETE' => $lang['Dl_delete'],
			'U_DELETE' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $report_file_id . '&amp;fav_id=' . $report_id . '&amp;action=delete')
			)
		);

		if ($report_status < 4)
		{
			$template->assign_block_vars('assign_mod', array());

			// Codeblock to assign the report to a team member
			$sql = "SELECT user_id, username FROM " . USERS_TABLE . "
				WHERE user_level IN (" . ADMIN . ", " . JUNIOR_ADMIN . ", " . MOD . ")
					AND user_id <> ".ANONYMOUS."
					AND user_id <> $report_assign_id
				ORDER BY username";
			$result = $db->sql_query($sql);

			$s_select_assign_member = '<select name="user_assign">';
			while ($row = $db->sql_fetchrow($result))
			{
				$s_select_assign_member .= '<option value="'.$row['user_id'] . '">'.phpbb_clean_username($row['username']).'</option>';
			}
			$db->sql_freeresult($result);
			$s_select_assign_member .= '</select>';
			$s_select_assign_member = str_replace('<option value="'.$user->data['user_id'] . '">', '<option value="'.$user->data['user_id'] . '" selected="selected">', $s_select_assign_member);

			$template->assign_vars(array(
				'S_FORM_ASSIGN_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;action=assign&amp;df_id=' . $report_file_id . '&amp;fav_id=' . $fav_id),
				'S_SELECT_ASSIGN_USER' => $s_select_assign_member
				)
			);
		}

		// Create status select
		$s_select_status = '';
		switch ($report_status)
		{
			case 0:
			case 1:
			case 2:
				$s_select_status .= '<option value="2">' . $lang['Dl_report_status'][2].'</option>';
			case 3:
				$s_select_status .= '<option value="3">' . $lang['Dl_report_status'][3].'</option>';
				$s_select_status .= '<option value="4">' . $lang['Dl_report_status'][4].'</option>';
				$s_select_status .= '<option value="5">' . $lang['Dl_report_status'][5].'</option>';
				break;
			case 4:
			case 5:
		}

		if ($s_select_status)
		{
			$s_select_status = '<select name="new_status">' . $s_select_status . '</select>';
			$template->assign_block_vars('status_select', array(
				'S_FORM_STATUS_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;action=status&amp;df_id=' . $report_file_id . '&amp;fav_id=' . $fav_id),
				'S_SELECT_STATUS' => $s_select_status
				)
			);
		}
	}
}

/*
* display form to add a bug report
*/
if (($action == 'add') && $user->data['session_logged_in'])
{
	$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_bt_add.tpl');

	$s_hidden_fields = '<input type="hidden" name="action" value="save" />';

	if ($df_id)
	{
		$sql = "SELECT description FROM " . DOWNLOADS_TABLE . "
			WHERE id = $df_id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$s_select_download = $row['description'];
		$db->sql_freeresult($result);
		$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
	}
	else
	{
		$sql = "SELECT d.id, d.description FROM " . DOWNLOADS_TABLE . " d, " . DL_CAT_TABLE . " c
			WHERE d.cat = c.id
				AND c.bug_tracker = " . TRUE . "
			ORDER BY c.sort ASC, d.sort ASC";
		$result = $db->sql_query($sql);

		$s_select_download = '<select name="df_id">';
		while ($row = $db->sql_fetchrow($result))
		{
			$s_select_download .= '<option value="'.$row['id'] . '">'.$row['description'] . '</option>';
		}
		$s_select_download .= '</select>';

		$db->sql_freeresult($result);
	}

	$template->assign_vars(array(
		'L_REPORT_FILE' => $lang['Dl_bug_report_file'],
		'L_REPORT_TITLE' => $lang['Dl_bug_report_title'],
		'L_REPORT_TEXT' => $lang['Dl_bug_report_text'],
		'L_REPORT_PHP' => $lang['Dl_bug_report_php'],
		'L_REPORT_DB' => $lang['Dl_bug_report_db'],
		'L_REPORT_FORUM' => $lang['Dl_bug_report_forum'],
		'L_REPORT_FILE_VER' => $lang['Dl_hack_version'],

		'L_DOWNLOADS' => $lang['Dl_cat_title'],
		'L_BUG_TRACKER' => $lang['Dl_bug_tracker'],
		'L_ADD_REPORT' => $lang['New_post'],
		'L_SUBMIT' => $lang['Submit'],
		'L_FONT_SIZE' => $lang['Font_size'],
		'L_FONT_COLOR' => $lang['Font_color'],
		'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],

		'S_FORM_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_SELECT_DOWNLOAD' => $s_select_download,

		'U_DOWNLOADS_ADV' => append_sid('downloads.' . PHP_EXT),
		'U_BUG_TRACKER' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker')
		)
	);

	page_footer();
}

/*
* delete bug report - only if the report really will not be a bug ;)
*/
if ($action == 'delete' && $df_id && $fav_id && $allow_bug_mod)
{
	if (!$confirm && !$cancel)
	{
		// Confirm deletion
		$s_hidden_fields = '<input type="hidden" name="df_id" value="' . $df_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="fav_id" value="'.$fav_id.'" />';
		$s_hidden_fields .= '<input type="hidden" name="view" value="bug_tracker" />';
		$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';

		$l_confirm = $lang['Confirm_delete_bug_report'];

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => $l_confirm,

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('downloads.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
		full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
	}
	elseif (!$cancel)
	{
		$sql = "DELETE FROM " . DL_BUGS_TABLE . "
			WHERE report_id = $fav_id";
		$db->sql_query($sql);

		$sql = "DELETE FROM " . DL_BUG_HISTORY_TABLE . "
			WHERE report_id = $fav_id";
		$db->sql_query($sql);
		$fav_id = 0;
	}

	$df_id = 0;
	$action = '';
	$confirm = '';
	$cancel = '';
}

if (!$action)
{
	// Load board header and send default values to template
	$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_bt_list.tpl');

	$s_select_filter = '<select name="bt_filter">';
	$s_select_filter .= '<option value="0">' . $lang['Dl_no_filter'] . '</option>';
	$s_select_filter .= '<option value="1">' . $lang['Dl_report_status'][0].'</option>';
	$s_select_filter .= '<option value="2">' . $lang['Dl_report_status'][1].'</option>';
	$s_select_filter .= '<option value="3">' . $lang['Dl_report_status'][2].'</option>';
	$s_select_filter .= '<option value="4">' . $lang['Dl_report_status'][3].'</option>';
	$s_select_filter .= '<option value="5">' . $lang['Dl_report_status'][4].'</option>';
	$s_select_filter .= '<option value="6">' . $lang['Dl_report_status'][5].'</option>';
	$s_select_filter .= '</select>';
	$s_select_filter = str_replace('value="'.$bt_filter.'">', 'value="'.$bt_filter.'" selected="selected">', $s_select_filter);

	$s_hidden_fields = '<input type="hidden" name="df_id" value="' . $df_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="action" value="add" />';

	$template->assign_vars(array(
		'L_REPORT_TITLE' => $lang['Dl_bug_report_title_details'],
		'L_REPORT_TEXT' => $lang['Dl_bug_report_text'],
		'L_REPORT_DATE' => $lang['Dl_bug_report_date'],
		'L_REPORT_PHP' => $lang['Dl_bug_report_php'],
		'L_REPORT_DB' => $lang['Dl_bug_report_db'],
		'L_REPORT_FORUM' => $lang['Dl_bug_report_forum'],
		'L_REPORT_FILE' => $lang['Dl_bug_report_file'],
		'L_REPORT_AUTHOR' => $lang['Dl_bug_report_author'],
		'L_REPORT_ASSIGN' => $lang['Dl_bug_report_assigned'],
		'L_REPORT_ASSIGN_DATE' => $lang['Dl_bug_report_assign_date'],
		'L_REPORT_STATUS' => $lang['Dl_bug_report_status'],
		'L_REPORT_STATUS_DATE' => $lang['Dl_bug_report_status_date'],
		'L_REPORT_DETAIL' => $lang['Dl_bug_report_detail'],
		'L_REPORT_FILTER' => $lang['Dl_filter'],
		'L_REPORT_FILTER_OWN' => $lang['Dl_filter_bt_own'],
		'L_REPORT_FILTER_ASSIGN' => $lang['Dl_filter_bt_assign'],

		'L_DOWNLOADS' => $lang['Dl_cat_title'],
		'L_BUG_TRACKER' => $lang['Dl_bug_tracker'],

		'S_SELECT_FILTER' => $s_select_filter,
		'S_FORM_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker'),
		'S_FORM_FILTER_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id),
		'S_FORM_OWN_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id . '&amp;bt_show=own'),
		'S_FORM_ASSIGN_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id . '&amp;bt_show=assign'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields,

		'U_DOWNLOADS_ADV' => append_sid('downloads.' . PHP_EXT),
		'U_BUG_TRACKER' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker')
		)
	);

	/*
	* view bug tracker - detail overview for given download
	*/
	if ($df_id)
	{
		if ($bt_filter)
		{
			$bt_filter--;
			$sql_where = " AND report_status = $bt_filter ";
		}
		else
		{
			$sql_where = '';
		}

		if ($bt_show == 'own')
		{
			$sql_where .= " AND report_author_id = " . $user->data['user_id'];
		}
		else
		{
			$template->assign_block_vars('own_report', array());
		}

		if ($bt_show == 'assign')
		{
			$sql_where .= " AND report_assign_id = " . $user->data['user_id'];
		}
		else
		{
			$template->assign_block_vars('assign_report', array());
		}

		$sql = "SELECT *
			FROM " . DL_BUGS_TABLE . "
			WHERE df_id = $df_id
				$sql_where";
		$result = $db->sql_query($sql);
		$total_reports = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if ($total_reports > $dl_config['dl_links_per_page'])
		{
			$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id, $total_reports, $dl_config['dl_links_per_page'], $start);
		}
		else
		{
			$pagination = '';
		}

		$template->assign_vars(array(
			'PAGINATION' => $pagination
			)
		);

		if ($total_reports)
		{
			$sql = "SELECT b.*, d.description AS report_file, u1.username AS report_author, u2.username AS report_assign
				FROM " . DOWNLOADS_TABLE . " d, " . DL_BUGS_TABLE . " b
				LEFT JOIN " . USERS_TABLE . " u1 ON u1.user_id = b.report_author_id
				LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = b.report_assign_id
				WHERE b.df_id = d.id
					AND b.df_id = $df_id
					$sql_where
				ORDER BY b.report_date DESC
				LIMIT $start, " . $dl_config['dl_links_per_page'];
			$result = $db->sql_query($sql);
			$reports_num = $db->sql_numrows($result);
		}
		else
		{
			$reports_num = 0;
		}

		if ($reports_num)
		{
			$i = 0;

			$template->assign_block_vars('bug_tracker_file_head', array());

			$row_class = '';
			while ($row = $db->sql_fetchrow($result))
			{
				$report_id = $row['report_id'];
				$report_title = $row['report_title'];
				$report_text = $row['report_text'];
				$report_file_ver = $row['report_file_ver'];
				$report_file = $row['report_file'];
				$report_date = $row['report_date'];
				$report_author_id = $row['report_author_id'];
				$report_assign_id = $row['report_assign_id'];
				$report_author = $row['report_author'];
				$report_assign = $row['report_assign'];
				$report_assign_date = $row['report_assign_date'];
				$report_status = $row['report_status'];
				$report_status_date = $row['report_status_date'];
				$report_php = $row['report_php'];
				$report_db = $row['report_db'];
				$report_forum = $row['report_forum'];

				$report_title = censor_text($report_title);
				$report_text = censor_text($report_text);
				$report_file_ver = censor_text($report_file_ver);
				$report_php = censor_text($report_php);
				$report_db = censor_text($report_db);
				$report_forum = censor_text($report_forum);

				$report_text = (strlen($report_text) > 200) ? substr($report_text, 0, 200) . ' [ ... ]' : $report_text;
				$report_text = str_replace("\n", "<br />", $report_text);

				$row_class = ip_zebra_rows($row_class);
				$template->assign_block_vars('bug_tracker_file', array(
					'ROW_CLASS' => $row_class,

					'REPORT_ID' => $report_id,
					'REPORT_TITLE' => $report_title,
					'REPORT_TEXT' => $report_text,
					'REPORT_DATE' => create_date($config['default_dateformat'], $report_date, $config['board_timezone']),

					'REPORT_PHP' => $report_php,
					'REPORT_DB' => $report_db,
					'REPORT_FORUM' => $report_forum,

					'REPORT_FILE' => $report_file,
					'REPORT_FILE_VER' => $report_file_ver,
					'REPORT_FILE_LINK' => append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id),

					//'REPORT_AUTHOR_LINK' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $report_author_id),
					'REPORT_AUTHOR_LINK' => colorize_username($report_author_id),
					'REPORT_AUTHOR' => phpbb_clean_username($report_author),

					'REPORT_STATUS' => $lang['Dl_report_status'][$report_status],
					'REPORT_STATUS_DATE' => create_date($config['default_dateformat'], $report_status_date, $config['board_timezone']),

					'REPORT_DETAIL' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;fav_id=' . $report_id . '&amp;action=detail'))
				);

				if ($report_assign_id)
				{
					$template->assign_block_vars('bug_tracker_file.assign', array(
						//'REPORT_ASSIGN_LINK' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $report_assign_id),
						'REPORT_ASSIGN_LINK' => colorize_username($report_assign_id),
						'REPORT_ASSIGN' => phpbb_clean_username($report_assign),
						'REPORT_ASSIGN_DATE' => create_date($config['default_dateformat'], $report_assign_date, $config['board_timezone'])
						)
					);
				}
				else
				{
					$template->assign_block_vars('bug_tracker_file.no_assign', array(
						'L_NO_ASSIGNED' => $lang['Dl_bug_report_unassigned']
						)
					);
				}

				if ($allow_bug_mod)
				{
					$template->assign_block_vars('bug_tracker_file.mod', array(
						'L_DELETE' => $lang['Dl_delete'],
						'U_DELETE' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id . '&amp;fav_id=' . $report_id . '&amp;action=delete')
						)
					);

					$template->assign_block_vars('bug_tracker_file.status_mod', array(
						'U_STATUS' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id . '&amp;fav_id=' . $report_id . '&amp;action=status')
						)
					);
				}
				else
				{
					$template->assign_block_vars('bug_tracker_file.no_status_mod', array());
				}

				$i++;
			}

			$db->sql_freeresult($result);
		}
		else
		{
			$template->assign_block_vars('no_bug_tracker_file', array(
				'L_NO_BUG_TRACKER' => $lang['Dl_no_bug_tracker']
				)
			);
		}
	}
	else
	{
		/*
		* view bug tracker - users default entry point
		*/
		if ($bt_filter)
		{
			$bt_filter--;
			$sql_where = " WHERE report_status = $bt_filter ";
		}
		else
		{
			$sql_where = '';
		}

		if ($bt_show == 'own')
		{
			$sql_where .= (($sql_where) ? " AND " : " WHERE ") . " report_author_id = " . $user->data['user_id'];
		}
		else
		{
			$template->assign_block_vars('own_report', array());
		}

		if ($bt_show == 'assign')
		{
			$sql_where .= (($sql_where) ? " AND " : " WHERE ") . " report_assign_id = " . $user->data['user_id'];
		}
		else
		{
			$template->assign_block_vars('assign_report', array());
		}

		$sql = "SELECT *
			FROM " . DL_BUGS_TABLE . "
			$sql_where";
		$result = $db->sql_query($sql);
		$total_reports = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if ($total_reports > $dl_config['dl_links_per_page'])
		{
			$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id, $total_reports, $dl_config['dl_links_per_page'], $start);
		}
		else
		{
			$pagination = '';
		}

		$template->assign_vars(array(
			'PAGINATION' => $pagination
			)
		);

		if ($bt_filter)
		{
			$sql_where = " AND report_status = $bt_filter ";
		}
		else
		{
			$sql_where = '';
		}

		if ($bt_show == 'own')
		{
			$sql_where .= " AND report_author_id = " . $user->data['user_id'];
		}

		if ($bt_show == 'assign')
		{
			$sql_where .= " AND report_assign_id = " . $user->data['user_id'];
		}

		if ($total_reports)
		{
			$sql = "SELECT b.*, d.id AS file_id, d.description AS report_file, u1.username AS report_author, u2.username AS report_assign
				FROM " . DOWNLOADS_TABLE . " d, " . DL_BUGS_TABLE . " b
				LEFT JOIN " . USERS_TABLE . " u1 ON u1.user_id = b.report_author_id
				LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = b.report_assign_id
				WHERE b.df_id = d.id
					$sql_where
				ORDER BY b.report_date DESC
				LIMIT $start, " . $dl_config['dl_links_per_page'];
			$result = $db->sql_query($sql);
			$reports_num = $db->sql_numrows($result);
		}
		else
		{
			$reports_num = 0;
		}

		if ($reports_num)
		{
			$i = 0;

			$template->assign_block_vars('bug_tracker_list_head', array());

			$row_class = '';
			while ($row = $db->sql_fetchrow($result))
			{
				$report_id = $row['report_id'];
				$df_id = $row['file_id'];
				$report_title = $row['report_title'];
				$report_file_ver = $row['report_file_ver'];
				$report_file = $row['report_file'];
				$report_date = $row['report_date'];
				$report_author_id = $row['report_author_id'];
				$report_assign_id = $row['report_assign_id'];
				$report_author = $row['report_author'];
				$report_assign = $row['report_assign'];
				$report_assign_date = $row['report_assign_date'];
				$report_status = $row['report_status'];
				$report_status_date = $row['report_status_date'];

				$report_title = censor_text($report_title);
				$report_file_ver = censor_text($report_file_ver);

				$row_class = ip_zebra_rows($row_class);
				$template->assign_block_vars('bug_tracker_list', array(
					'ROW_CLASS' => $row_class,

					'REPORT_ID' => $report_id,
					'REPORT_TITLE' => $report_title,
					'REPORT_DATE' => create_date($config['default_dateformat'], $report_date, $config['board_timezone']),

					'REPORT_FILE' => $report_file,
					'REPORT_FILE_VER' => $report_file_ver,
					'REPORT_FILE_LINK' => append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id),

					//'REPORT_AUTHOR_LINK' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $report_author_id),
					'REPORT_AUTHOR_LINK' => colorize_username($report_author_id),
					'REPORT_AUTHOR' => phpbb_clean_username($report_author),

					'REPORT_STATUS' => $lang['Dl_report_status'][$report_status],
					'REPORT_STATUS_DATE' => create_date($config['default_dateformat'], $report_status_date, $config['board_timezone']),

					'REPORT_DETAIL' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;fav_id=' . $report_id . '&amp;action=detail')
					)
				);

				if ($report_assign_id)
				{
					$template->assign_block_vars('bug_tracker_list.assign', array(
						//'REPORT_ASSIGN_LINK' => append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $report_assign_id),
						'REPORT_ASSIGN_LINK' => colorize_username($report_assign_id),
						'REPORT_ASSIGN' => phpbb_clean_username($report_assign),
						'REPORT_ASSIGN_DATE' => create_date($config['default_dateformat'], $report_assign_date, $config['board_timezone']))
					);
				}
				else
				{
					$template->assign_block_vars('bug_tracker_list.no_assign', array(
						'L_NO_ASSIGNED' => $lang['Dl_bug_report_unassigned'])
					);
				}

				if ($allow_bug_mod)
				{
					$template->assign_block_vars('bug_tracker_list.mod', array(
						'L_DELETE' => $lang['Dl_delete'],
						'U_DELETE' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id . '&amp;fav_id=' . $report_id . '&amp;action=delete')
						)
					);

					$template->assign_block_vars('bug_tracker_list.status_mod', array(
						'U_STATUS' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker&amp;df_id=' . $df_id . '&amp;fav_id=' . $report_id . '&amp;action=status')
						)
					);
				}
				else
				{
					$template->assign_block_vars('bug_tracker_list.no_status_mod', array());
				}

				$i++;
			}

			$db->sql_freeresult($result);
		}
		else
		{
			$template->assign_block_vars('no_bug_tracker_list', array(
				'L_NO_BUG_TRACKER' => $lang['Dl_no_bug_tracker']
				)
			);
		}
	}
}

$colspan = ($df_id) ? 'colspan="6"' : '';
$colspan = (!$df_id && $i) ? 'colspan="5"' : $colspan;

$template->assign_vars(array(
	'SPANINC' => $colspan
	)
);

if ($user->data['session_logged_in'])
{
	$template->assign_block_vars('add_new_report', array(
		'L_ADD_REPORT' => $lang['New_post']
		)
	);
}

$dl_config['show_footer_legend'] = false;

?>