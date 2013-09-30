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

if ($submit)
{
	switch($action)
	{
		case 'single':

			switch ($x)
			{
				case 'b':
					$traffic_bytes = $user_traffic;
					break;
				case 'kb':
					$traffic_bytes = floor($user_traffic * 1024);
					break;
				case 'mb':
					$traffic_bytes = floor($user_traffic * 1048576);
					break;
				case 'gb':
					$traffic_bytes = floor($user_traffic * 1073741824);
					break;
				default:
					$traffic_bytes = 0;
			}

			if ($traffic_bytes)
			{
				$sql = get_users_sql($username, false, false, true, true);
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$user_id = $row['user_id'];
				$db->sql_freeresult($result);

				if (!$user_id)
				{
					message_die(GENERAL_MESSAGE, ' <b>' . $lang['Username'] . ' ' . phpbb_clean_username($username) . '</b><br /><br />' . $lang['Admin_user_fail']);
				}

				if ($func == 'add')
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_traffic = user_traffic + $traffic_bytes
						WHERE user_id = $user_id";
				}
				if ($func == 'set')
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_traffic = $traffic_bytes
						WHERE user_id = $user_id";
				}
				$result = $db->sql_query($sql);

				$message = $lang['Admin_user_updated'] . '<br /><br />' . sprintf($lang['Click_return_user_traffic_admin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=traffic') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}

			break;

		case 'all':

			switch ($y)
			{
				case 'b':
					$traffic_bytes = $all_traffic;
					break;
				case 'kb':
					$traffic_bytes = floor($all_traffic * 1024);
					break;
				case 'mb':
					$traffic_bytes = floor($all_traffic * 1048576);
					break;
				case 'gb':
					$traffic_bytes = floor($all_traffic * 1073741824);
					break;
				default:
					$traffic_bytes = 0;
			}

			if ($traffic_bytes)
			{
				if ($func == 'add')
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_traffic = user_traffic + $traffic_bytes";
				}
				if ($func == 'set')
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_traffic = $traffic_bytes";
				}
				$result = $db->sql_query($sql);

				$message = $lang['Admin_user_updated'] . '<br /><br />' . sprintf($lang['Click_return_user_traffic_admin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=traffic') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}

			break;

		case 'group':

			switch ($z)
			{
				case 'b':
					$traffic_bytes = $group_traffic;
					break;
				case 'kb':
					$traffic_bytes = floor($group_traffic * 1024);
					break;
				case 'mb':
					$traffic_bytes = floor($group_traffic * 1048576);
					break;
				case 'gb':
					$traffic_bytes = floor($group_traffic * 1073741824);
					break;
				default:
					$traffic_bytes = 0;
			}

			if ($traffic_bytes)
			{
				if (!function_exists('get_users_in_group'))
				{
					include(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
				}
				$users_array = get_users_in_group($group_id);

				$user_ids = array();
				if (!empty($users_array))
				{
					foreach ($users_array as $dl_user_data)
					{
						$user_ids[] = $dl_user_data['user_id'];
					}
				}

				if (sizeof($user_ids))
				{
					$userdata_group = implode(', ', $user_ids);

					if ($func == 'add')
					{
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_traffic = user_traffic + $traffic_bytes
							WHERE user_id IN ($userdata_group)";
					}
					if ($func == 'set')
					{
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_traffic = $traffic_bytes
							WHERE user_id IN ($userdata_group)";
					}
					$result = $db->sql_query($sql);

					$message = $lang['Admin_user_updated'] . '<br /><br />' . sprintf($lang['Click_return_usergroup_traffic_admin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=traffic') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}
			}

			break;

		case 'auto':

			$groups_data = get_groups_data(false, false, array());

			foreach ($groups_data as $group_data)
			{
				$group_id = $group_data['group_id'];
				$group_dl_auto_traffic = request_var('group_dl_auto_traffic', 0);
				$data_group_range = request_var('data_group_range', '', true);

				if ($data_group_range[$group_id] == 'B')
				{
					$traffic = $group_dl_auto_traffic[$group_id];
				}
				if ($data_group_range[$group_id] == 'KB')
				{
					$traffic = floor($group_dl_auto_traffic[$group_id] * 1024);
				}
				elseif ($data_group_range[$group_id] == 'MB')
				{
					$traffic = floor($group_dl_auto_traffic[$group_id] * 1048576);
				}
				elseif ($data_group_range[$group_id] == 'GB')
				{
					$traffic = floor($group_dl_auto_traffic[$group_id] * 1073741824);
				}
				else
				{
					$traffic = 0;
				}

				$sql = "UPDATE " . GROUPS_TABLE . "
					SET group_dl_auto_traffic = $traffic
					WHERE group_id = " . $group_id;
				$db->sql_query($sql);
			}

			$user_dl_auto_traffic = request_var('user_dl_auto_traffic', 0);
			$data_user_range = request_var('data_user_range', '', true);

			if ($data_user_range == 'B')
			{
				$traffic = $user_dl_auto_traffic;
			}
			elseif ($data_user_range == 'KB')
			{
				$traffic = floor($user_dl_auto_traffic * 1024);
			}
			elseif ($data_user_range == 'MB')
			{
				$traffic = floor($user_dl_auto_traffic * 1048576);
			}
			elseif ($data_user_range == 'GB')
			{
				$traffic = floor($user_dl_auto_traffic * 1073741824);
			}
			else
			{
				$traffic = 0;
			}

			$sql = "UPDATE " . DL_CONFIG_TABLE . "
				SET config_value = " . $traffic . "
				WHERE config_name = 'user_dl_auto_traffic'";
			$db->sql_query($sql);

			$message = $lang['Admin_user_updated'] . '<br /><br />' . sprintf($lang['Click_return_usergroup_traffic_admin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=traffic') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);

			break;
	}
}

$template->set_filenames(array('traffic' => DL_ADM_TPL_PATH . 'dl_traffic_body.tpl'));

$groups_data = get_groups_data(true, false, array());
$total_groups = sizeof($groups_data);

$sql = "SELECT group_id, group_name, group_dl_auto_traffic
	FROM " . GROUPS_TABLE . "
	ORDER BY group_name";
$result = $db->sql_query($sql);

$total_groups = $db->sql_numrows($result);
if ($total_groups)
{
	$template->assign_block_vars('group_block', array());

	$s_select_list = '<select name="' . POST_GROUPS_URL . '">';

	foreach ($groups_data as $group_data)
	{
		$group_dl_auto_traffic = ($group_data['group_dl_auto_traffic']) ? $group_data['group_dl_auto_traffic'] : 0;

		if ($group_dl_auto_traffic > 1073741823)
		{
			$group_traffic = number_format($group_dl_auto_traffic / 1073741824, 2);
			$group_data_range_b = '';
			$group_data_range_kb = '';
			$group_data_range_mb = '';
			$group_data_range_gb = 'checked="checked"';
		}
		if ($group_dl_auto_traffic < 1073741824)
		{
			$group_traffic = number_format($group_dl_auto_traffic / 1048576, 2);
			$group_data_range_b = '';
			$group_data_range_kb = '';
			$group_data_range_mb = 'checked="checked"';
			$group_data_range_gb = '';
		}
		if ($group_dl_auto_traffic < 1048576)
		{
			$group_traffic = number_format($group_dl_auto_traffic / 1024, 2);
			$group_data_range_b = '';
			$group_data_range_kb = 'checked="checked"';
			$group_data_range_mb = '';
			$group_data_range_gb = '';
		}
		if ($group_dl_auto_traffic < 1024)
		{
			$group_traffic = $group_dl_auto_traffic;
			$group_data_range_b = 'checked="checked"';
			$group_data_range_kb = '';
			$group_data_range_mb = '';
			$group_data_range_gb = '';
		}

		$template->assign_block_vars('group_row',array(
			'GROUP_ID' => $group_data['group_id'],
			'GROUP_NAME' => $group_data['group_name'],
			'GROUP_DL_AUTO_TRAFFIC' => $group_traffic,
			'GROUP_DATA_RANGE_B' => $group_data_range_b,
			'GROUP_DATA_RANGE_KB' => $group_data_range_kb,
			'GROUP_DATA_RANGE_MB' => $group_data_range_mb,
			'GROUP_DATA_RANGE_GB' => $group_data_range_gb
			)
		);

		$s_select_list .= '<option value="' . $group_data['group_id'] . '">' . $group_data['group_name'] . '</option>';
	}
	$s_select_list .= '</select>';
}

$user_dl_auto_traffic = $dl_config['user_dl_auto_traffic'];

if ($user_dl_auto_traffic > 1073741823)
{
	$user_traffic = number_format($user_dl_auto_traffic / 1073741824, 2);
	$user_data_range_b = '';
	$user_data_range_kb = '';
	$user_data_range_mb = '';
	$user_data_range_gb = 'checked="checked"';
}
if ($user_dl_auto_traffic < 1073741824)
{
	$user_traffic = number_format($user_dl_auto_traffic / 1048576, 2);
	$user_data_range_b = '';
	$user_data_range_kb = '';
	$user_data_range_mb = 'checked="checked"';
	$user_data_range_gb = '';
}
if ($user_dl_auto_traffic < 1048576)
{
	$user_traffic = number_format($user_dl_auto_traffic / 1024, 2);
	$user_data_range_b = '';
	$user_data_range_kb = 'checked="checked"';
	$user_data_range_mb = '';
	$user_data_range_gb = '';
}
if ($user_dl_auto_traffic < 1024)
{
	$user_traffic = $user_dl_auto_traffic;
	$user_data_range_b = 'checked="checked"';
	$user_data_range_kb = '';
	$user_data_range_mb = '';
	$user_data_range_gb = '';
}

$template->assign_vars(array(
	'L_DL_BYTES' => $lang['Dl_Bytes_long'],
	'L_DL_KB' => $lang['Dl_KB'],
	'L_DL_MB' => $lang['Dl_MB'],
	'L_DL_GB' => $lang['Dl_GB'],
	'L_USER_TITLE' => $lang['Single_user_traffic_title'],
	'L_ALL_USERS_TITLE' => $lang['Users_traffic_title'],
	'L_USER_EXPLAIN' => $lang['Traffic_single_user_admin_explain'],
	'L_ALL_USERS_EXPLAIN' => $lang['Traffic_all_users_admin_explain'],
	'L_USERNAME' => $lang['Username'],
	'L_USERGROUP_TITLE' => $lang['Usergroup_traffic_title'],
	'L_USERGROUP_EXPLAIN' => $lang['Traffic_usergroup_admin_explain'],
	'L_USERGROUP' => $lang['group_name'],
	'L_USER_WITHOUT_GROUP' => $lang['Dl_users_without_group'],
	'L_USER_DL_AUTO_TRAFFIC' => $lang['Dl_user_auto_traffic'],
	'L_GROUP_NAME' => $lang['Dl_group_name'],
	'L_GROUP_DL_AUTO_TRAFFIC' => $lang['Dl_group_auto_traffic'],
	'L_CONFIGURATION_TITLE' => $lang['Dl_auto_traffic'],
	'L_CONFIGURATION_EXPLAIN' => $lang['Dl_auto_traffic_explain'],
	'L_TRAFFIC' => $lang['Traffic'],
	'L_FUNCTION' => $lang['Dl_function'],
	'L_ADD' => $lang['Dl_add'],
	'L_SET' => $lang['Dl_set'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'USER_DL_AUTO_TRAFFIC' => $user_traffic,
	'USER_DATA_RANGE_B' => $user_data_range_b,
	'USER_DATA_RANGE_KB' => $user_data_range_kb,
	'USER_DATA_RANGE_MB' => $user_data_range_mb,
	'USER_DATA_RANGE_GB' => $user_data_range_gb,

	'S_GROUP_SELECT' => $s_select_list,

	'S_PROFILE_ACTION_ALL' => append_sid('admin_downloads.' . PHP_EXT . '?submod=traffic&amp;action=all'),
	'S_PROFILE_ACTION_USER' => append_sid('admin_downloads.' . PHP_EXT . '?submod=traffic&amp;action=single'),
	'S_PROFILE_ACTION_GROUP' => append_sid('admin_downloads.' . PHP_EXT . '?submod=traffic&amp;action=group'),
	'S_CONFIG_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=traffic&amp;action=auto')
	)
);

$template->pparse('traffic');

?>