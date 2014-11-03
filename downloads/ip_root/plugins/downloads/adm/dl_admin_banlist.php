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

if ($cancel)
{
	$action = '';
}

$action_test = request_var('edit_banlist', '')
$action = ($action_test == 'edit') ? 'edit' : $action;

$action_test = request_var('delete_banlist', '')
$action = ($action_test == 'delete') ? 'delete' : $action;

if($action == 'add')
{
	$ban_id = request_var('ban_id', 0);
	$user_id = request_var('user_id', 0);
	$user_ip_dl = request_var('user_ip', '', true);
	$user_ip_dl = (!empty($user_ip_dl) ? $user_ip_dl : '');
	$user_agent_dl = request_var('user_agent', '', true);
	$username = request_var('username', '', true);
	$username = (!empty($username) ? phpbb_clean_username($username) : '');
	$guests = request_var('guests', 0);

	if ($ban_id)
	{
		$sql = "UPDATE " . DL_BANLIST_TABLE . "
			SET user_id = " . (int) $user_id . ", user_ip = '" . $db->sql_escape($user_ip_dl) . "', user_agent = '" . $db->sql_escape($user_agent_dl) . "', username = '" . $db->sql_escape($username) . "', guests = " . (int) $guests . "
			WHERE ban_id = " . (int) $ban_id;
	}
	else
	{
		$sql = "INSERT INTO " . DL_BANLIST_TABLE . "
			(user_id, user_ip, user_agent, username, guests)
			VALUES
			(" . (int) $user_id . ", '" . $db->sql_escape($user_ip_dl) . "', '" . $db->sql_escape($user_agent_dl) . "', '" . $db->sql_escape($username) . "', " . (int) $guests . ")";
	}
	$result = $db->sql_query($sql);

	$action = '';
}
elseif($action == 'delete')
{
	$ban_id = (isset($_POST['ban_id'])) ? $_POST['ban_id'] : array();

	if (!$confirm)
	{
		$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_confirm_body.tpl');
		$template->set_filenames(array('confirm_body' => $template_to_parse));

		for ($i = 0; $i < sizeof($ban_id); $i++)
		{
			$s_hidden_fields .= '<input type="hidden" name="ban_id[]" value="'.intval($ban_id[$i]).'" />';
		}

		$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => $lang['Dl_confirm_delete_ban_values'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=banlist'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	}
	else
	{
		$sql_ext_in = '';
		for ($i = 0; $i < sizeof($ban_id); $i++)
		{
			$sql_ext_in .= ($sql_ext_in != '') ? ", ".intval($ban_id[$i]) : intval($ban_id[$i]);
		}

		if ($sql_ext_in)
		{
			$sql = "DELETE FROM " . DL_BANLIST_TABLE . "
				WHERE ban_id IN ($sql_ext_in)";
			$db->sql_query($sql);

			message_die(GENERAL_MESSAGE, $lang['Dl_banlist_updated'] . '<br /><br />' . sprintf($lang['Click_return_banlistadmin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=banlist') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>'));
		}

		$action = '';
	}
}

if ($action == '' || $action == 'edit')
{
	$template->set_filenames(array('banlist' => DL_ADM_TPL_PATH . 'dl_banlist_body.tpl'));

	$sql = "SELECT * FROM " . DL_BANLIST_TABLE . "
		ORDER BY ban_id";
	$result = $db->sql_query($sql);

	$row_class = '';
	$i = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$row_class = ip_zebra_rows($row_class);

		$ban_id = $row['ban_id'];
		$user_id = $row['user_id'];
		$user_ip_dl = ($row['user_ip']) ? $row['user_ip'] : '';
		$user_agent_dl = $row['user_agent'];
		$username = $row['username'];
		$guests = $row['guests'];

		$template->assign_block_vars('banlist_row', array(
			'ROW_CLASS' => $row_class,
			'BAN_ID' => $ban_id,
			'USER_ID' => $user_id,
			'USER_IP' => $user_ip_dl,
			'USER_AGENT' => $user_agent_dl,
			'USERNAME' => $username,
			'GUESTS' => ($guests) ? $lang['Yes'] : $lang['No']
			)
		);

		$i++;
	}
	$db->sql_freeresult($result);

	$ban_id = (isset($_POST['ban_id'])) ? $_POST['ban_id'] : array();
	$banlist_id = intval($ban_id[0]);

	$s_hidden_fields = '<input type="hidden" name="action" value="add" />';

	if ($action == 'edit' && $banlist_id)
	{

		$sql = "SELECT * FROM " . DL_BANLIST_TABLE . "
			WHERE ban_id = $banlist_id";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$ban_id = $row['ban_id'];
			$user_id = $row['user_id'];
			$user_ip_dl = ($row['user_ip']) ? $row['user_ip'] : '';
			$user_agent_dl = $row['user_agent'];
			$username = $row['username'];
			$guests = $row['guests'];
			$s_hidden_fields .= '<input type="hidden" name="ban_id" value="' . $ban_id . '" />';
		}
		$db->sql_freeresult($result);
	}
	else
	{
		$ban_id = '';
		$user_id = '';
		$user_ip_dl = '';
		$user_agent_dl = '';
		$username = '';
		$guests = '';
	}

	$template->assign_vars(array(
		'L_DL_BANLIST_EXPLAIN' => $lang['Dl_acp_banlist_explain'],
		'L_DL_USER_ID' => $lang['Dl_user_id'],
		'L_DL_USER_IP' => $lang['Dl_ip'],
		'L_DL_USER_AGENT' => $lang['Dl_browser'],
		'L_DL_USERNAME' => $lang['Username'],
		'L_DL_GUESTS' => $lang['Guest'],
		'L_DL_ADD_NEW' => $lang['Submit'],
		'L_DL_DELETE' => $lang['Dl_delete'],
		'L_DL_YES' => $lang['Yes'],
		'L_DL_NO' => $lang['No'],
		'L_DL_EDIT' => $lang['Edit'],

		'DL_USER_ID' => $user_id,
		'DL_USER_IP' => $user_ip_dl,
		'DL_USER_AGENT' => $user_agent_dl,
		'DL_USERNAME' => $username,
		'CHECKED_YES' => ($guests) ? 'checked="checked"' : '',
		'CHECKED_NO' => (!$guests) ? 'checked="checked"' : '',

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_DOWNLOADS_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=banlist')
		)
	);
}

$template->pparse('banlist');

?>