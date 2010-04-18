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
* aUsTiN-Inc 2003/5 (austin@phpbb-amod.com) - (http://phpbb-amod.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking Attempt');
}

function VersionCheck()
{
	global $config, $userdata;

	$version = '1.1.0';
	$ver_check = $config['ina_version'];

	if(!$ver_check)
	{
		$this_version = 'Unknown';
	}
	if($ver_check)
	{
		$this_version = 'v' . $ver_check;
	}
	$error_msg = "Sorry, the games are currently offline until the admin upgrades/installs the current version. The current version is <b>". $version ."</b>. Your version is <b>". $this_version ."</b>.";

	if($userdata['user_level'] == ADMIN)
	{
		$msg_switch = 'Since you are an admin, please goto your admin panel, you can get there by clicking <a href="' . ADM . '/index.' . PHP_EXT . '?sid=' . $userdata['session_id'] . '"><i><b>here</b></i></a>. After you do that, please look on the left for <b>Amod+ Admin</b> and under it you will see a link, Db Adjustments. If this is a fresh install for you, please click <b>Install Activity Mod Plus</b>. If you are upgrading from a previous version, please look in the second section, and in the drop down menu and select what you are upgrading from. You are upgrading to <b>' . $version . '</b>. After doing that, this error will go away and you will be allowed to play games.';
	}
	else
	{
		$msg_switch = "Since you are not an admin, and cannot fix this, please link this page to an admin so they can get it fixed.";
	}

	$error_div = "<br /><br /><center><b>Instructions</b></center><br /><br />";

	if ($config['ina_version'] != $version)
	{
		message_die(CRITICAL_ERROR, $error_msg . $error_div . $msg_switch);
	}

}

function UpdateSessions()
{
	global $db, $userdata;

	$sql = "SELECT playing_id
			FROM " . INA_SESSIONS . "
			WHERE playing_id = '" . $userdata['user_id'] . "'";
	$result = $db->sql_query($sql);
	$exists = $db->sql_fetchrow($result);

	if (($exists) && ($userdata['user_id'] != ANONYMOUS))
	{
		$sql = "UPDATE " . INA_SESSIONS . "
				SET playing_time = '" . time() . "'
				WHERE playing_id = '" . $userdata['user_id'] . "'";
		$db->sql_query($sql);
	}
	else
	{
		if ($userdata['user_id'] == ANONYMOUS)
		{
			$logged_in = '0';
		}
		else
		{
			$logged_in = '1';
		}

		$sql = "INSERT INTO " . INA_SESSIONS . "
				VALUES ('" . time() . "', '" . $userdata['user_id'] . "', '" . $logged_in . "')";
		$db->sql_query($sql);
	}

	$sql = "DELETE FROM " . INA_SESSIONS . "
			WHERE playing_time < '" . $expired . "'";
	$db->sql_query($q1);
}

function BanCheck()
{
	global $userdata, $db, $config, $lang;

	$sql = "SELECT id
			FROM ". INA_BAN ."
			WHERE id = '". $userdata['user_id'] ."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$ban_1 = $row['id'];

	if ($ban_1)
	{
		message_die(GENERAL_ERROR, $lang['ban'], $lang['ban_error']);
	}

	$sql = "SELECT *
			FROM ". INA_BAN ."
			WHERE username = '". $userdata['username'] ."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$ban_2 = $row['username'];

	if ($ban_2)
	{
		message_die(GENERAL_ERROR, $lang['ban'], $lang['ban_error']);
	}

	if ($config['ina_post_block'] == '1')
	{
		if($userdata['user_posts'] < $config['ina_post_block_count'])
		{
			message_die(GENERAL_ERROR, str_replace("%B%", $config['ina_post_block_count'], $lang['restriction_check_1']), $lang['ban_error']);
		}
	}

	if ($config['ina_join_block'] == '1')
	{
		$days_block = $config['ina_join_block_count'];
		$length_check = time() - $userdata['user_regdate'];
		$length_block = $length_check / 86400;
		$rounded = round($length_block);

		if ($rounded < $days_block)
		{
			message_die(GENERAL_ERROR, str_replace("%B%", $config['ina_join_block_count'], $lang['restriction_check_2']), $lang['ban_error']);
		}
	}

	if (($config['ina_disable_everything']) && ($userdata['user_level'] != ADMIN))
	{
		message_die(GENERAL_ERROR, $lang['restriction_check_3'], $lang['ban_error']);
	}

}

/* Borrowed From ADR & Modified So I Wouldn't Have To Write It From Scratch =-) */
function send_challenge_pm($dest_user, $subject, $message)
{
	global $db, $config, $userdata, $lang, $user_ip, $bbcode;

	$dest_user = intval($dest_user);
	$msg_time = time();
	$from_id = $userdata['user_id'];

	if ((!$dest_user || !$from_id) || ($dest_user == ANONYMOUS || $from_id == ANONYMOUS))
	{
		return;
	}

	$html_on = 1;
	$bbcode_on = 1;
	$smilies_on = 1;

	@include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

	$privmsg_subject = trim(strip_tags($subject));
	$privmsg_message = trim(strip_tags($message));

	// APM compliance
	if (defined('PRIVMSGA_TABLE'))
	{
		include_once(IP_ROOT_PATH . 'includes/functions_messages.' . PHP_EXT);
		send_pm(0 , '' , $dest_user , $privmsg_subject, $privmsg_message, '');
	}
	else
	{
		$sql = "SELECT user_id, user_notify_pm, user_email, user_lang, user_active
				FROM " . USERS_TABLE . "
				WHERE user_id = '" . $dest_user . "'";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			$error = true;
			$error_msg = $lang['No_such_user'];
		}
		$to_userdata = $db->sql_fetchrow($result);

		$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time
				FROM " . PRIVMSGS_TABLE . "
				WHERE (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
					OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "
					OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")
					AND privmsgs_to_userid = '" . $dest_user . "'";
		$result = $db->sql_query($sql);

		if($inbox_info = $db->sql_fetchrow($result))
		{
			if ($inbox_info['inbox_items'] >= $config['max_inbox_privmsgs'])
			{
				$sql = "SELECT privmsgs_id
						FROM " . PRIVMSGS_TABLE . "
						WHERE (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
						OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "
						OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")
						AND privmsgs_date = " . $inbox_info['oldest_post_time'] . "
						AND privmsgs_to_userid = '" . $dest_user . "'";
				$result = $db->sql_query($sql);
				$old_privmsgs_id = $db->sql_fetchrow($result);
				$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];

				$sql = "DELETE FROM ". PRIVMSGS_TABLE ."
						WHERE privmsgs_id = '". $old_privmsgs_id ."'";
				$db->sql_query($sql);
			}
		}

		$sql_info = "INSERT INTO ". PRIVMSGS_TABLE . "
					(privmsgs_type, privmsgs_subject, privmsgs_text, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies)
					VALUES (1 , '". $db->sql_escape(addslashes($privmsg_subject)) ."', '" . $db->sql_escape(addslashes($privmsg_message)) . "', " . $from_id . ", ". $to_userdata['user_id'] .", $msg_time, '$user_ip' , $html_on, $bbcode_on, $smilies_on)";
		$db->sql_query($sql_info);

		$sql = "UPDATE ". USERS_TABLE ."
				SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "
				WHERE user_id = '" . $to_userdata['user_id'] . "'";
		$status = $db->sql_query($sql);

		if($to_userdata['user_notify_pm'] && !empty($to_userdata['user_email']) && $to_userdata['user_active'])
		{
			// have the mail sender infos
			$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['script_path']));
			$script_name = ($script_name != '') ? $script_name . '/privmsg.' . PHP_EXT : CMS_PAGE_PRIVMSG;
			$server_name = trim($config['server_name']);
			$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
			$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) . '/' : '/';

			include_once(IP_ROOT_PATH . './includes/emailer.' . PHP_EXT);
			$emailer = new emailer();

			$emailer->use_template('privmsg_notify', $to_userdata['user_lang']);
			$emailer->to($to_userdata['user_email']);
			$emailer->set_subject($lang['Notification_subject']);

			if (!empty($config['html_email']))
			{
				//HTML Message
				$message = $bbcode->parse($privmsg_message);
				$message = stripslashes($message);
				//HTML Message
			}
			else
			{
				$message = bbcode_killer_mg ($privmsg_message, '');
			}
			$email_sig = create_signature($config['board_email_sig']);
			$emailer->assign_vars(array(
				// Mighty Gorgon - Begin
				'FROM' => $userdata['username'],
				'DATE' => create_date($config['default_dateformat'], time(), $config['board_timezone']),
				'SUBJECT' => $privmsg_subject,
				'PRIV_MSG_TEXT' => $message,
				// Mighty Gorgon - End
				'USERNAME' => $to_username,
				'SITENAME' => $config['sitename'],
				'EMAIL_SIG' => $email_sig,
				'U_INBOX' => $server_protocol . $server_name . $server_port . $script_name . '?folder=inbox'
				)
			);

			$emailer->send();
			$emailer->reset();
		}
	}
	return;
}

function CheckGambles()
{
	global $db, $lang;

	$sql = "SELECT *
			FROM ". INA_GAMBLE_GAMES ."
			WHERE reciever_playing = '1'
			AND sender_playing = '1'
			AND reciever_score > '0'
			AND sender_score > '0'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
		{
	$reciever_id = $row['reciever_id'];
	$sender_id = $row['sender_id'];
	$reciever_score = $row['reciever_score'];
	$sender_score = $row['sender_score'];
	$game_id = $row['game_id'];

	$q1 = "SELECT *
			FROM ". iNA_GAMES ."
			WHERE game_id = '". $game_id ."'";
	$r1 = $db->sql_query($q1);
	$row = $db->sql_fetchrow($r1);
	$reverse_list = $row['reverse_list'];
	$game_name = $row['proper_name'];

		if ($reverse_list == '1')
			{
			if ($sender_score < $reciever_score)
				{
			$winner = $sender_id;
			$winner_score = $sender_score;
			$loser = $reciever_id;
			$loser_score = $reciever_score;
				}
			else
				{
			$winner = $reciever_id;
			$winner_score = $reciever_score;
			$loser = $sender_id;
			$loser_score = $sender_score;
				}
			}
		else
			{
			if ($sender_score > $reciever_score)
				{
			$winner = $sender_id;
			$winner_score = $sender_score;
			$loser = $reciever_id;
			$loser_score = $reciever_score;
				}
			else
				{
			$winner = $reciever_id;
			$winner_score = $reciever_score;
			$loser = $sender_id;
			$loser_score = $sender_score;
				}
			}

		if ($sender_score)
			{
	$bet_PM = $lang['bet_PM'];
	$bet_PM_subject = $lang['bet_PM_subject'];

	$q5 = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $winner ."'";
	$r5 = $db->sql_query($q5);
	$row = $db->sql_fetchrow($r5);
	$winners_username = $row['username'];

	$q6 = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $loser ."'";
	$r6 = $db->sql_query($q6);
	$row = $db->sql_fetchrow($r6);
	$losers_username = $row['username'];

	$msg1 = str_replace("%G%", $game_name, $bet_PM);
	$msg2 = str_replace("%W%", $winners_username, $msg1);
	$msg3 = str_replace("%WS%", $winner_score, $msg2);
	$msg4 = str_replace("%L%", $losers_username, $msg3);
	$msg5 = str_replace("%LS%", $loser_score, $msg4);
	$message = $msg5;
	send_challenge_pm($winner, $bet_PM_subject, $message);
	send_challenge_pm($loser, $bet_PM_subject, $message);

	$q3 = "UPDATE ". INA_GAMBLE ."
			SET winner_id = '". $winner ."', loser_id = '". $loser ."', winner_score = '". $winner_score ."', loser_score = '". $loser_score ."'
			WHERE sender_id = '". $sender_id ."'
			AND reciever_id = '". $reciever_id ."'
			AND game_id = '". $game_id ."'
			AND winner_score = '0'
			AND loser_score = '0'";
	$db->sql_query($q3);

	$q4 = "DELETE FROM ". INA_GAMBLE_GAMES ."
			WHERE sender_id = '". $sender_id ."'
			AND reciever_id = '". $reciever_id ."'
			AND game_id = '". $game_id ."'";
	$db->sql_query($q4);
			}
		}
	return;
}

function UpdateGamblePoints()
{
	global $db, $config;

	$sql = "SELECT *
			FROM ". INA_GAMBLE ."
			WHERE been_paid <> '1'
			AND winner_score > '0'
			AND loser_score > '0'
			AND amount > '0'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
		{
	$amount = $row['amount'];
	$winner = $row['winner_id'];
	$loser = $row['loser_id'];
	$game = $row['game_id'];

			if (($config['use_rewards_mod']) && ($config['use_point_system']))
				{
			subtract_points($loser, $amount);
			add_points($winner, $amount);
				}

			if (($config['use_cash_system'] || $config['use_allowance_system']) && $config['use_rewards_mod'])
				{
			subtract_reward($loser, $amount);
			add_reward($winner, $amount);
				}

	$q1 = "UPDATE ". INA_GAMBLE ."
			SET been_paid = '1'
			WHERE winner_id = '". $winner ."'
			AND loser_id = '". $loser ."'
			AND amount = '". $amount ."'
			AND game_id = '". $game ."'";
	$db->sql_query($q1);
			}
	return;
}

function UpdateActivitySession()
{
	global $db, $userdata;

	$sql = "UPDATE ". SESSIONS_TABLE ." s, ". USERS_TABLE ." u
			SET s.session_page = '" . CMS_PAGE_ACTIVITY . "', u.user_session_page = '" . CMS_PAGE_ACTIVITY . "'
			WHERE s.session_user_id = '". $userdata['user_id'] ."'
			AND u.user_id = '". $userdata['user_id'] ."'";
	$db->sql_query($sql);
	return;
}

function ChallengeSelected($who, $game)
{
	global $db, $userdata;

	$sql = "UPDATE ". CONFIG_TABLE ."
			SET config_value = config_value + 1
			WHERE config_name = 'challenges_sent'";
	$db->sql_query($sql);

	$sql = "SELECT *
				FROM ". INA_CHALLENGE_USERS ."
			WHERE user_from = '". $userdata['user_id'] ."'
			AND user_to = '". $who ."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$there = $row['count'];

	if ($there)
		{
	$sql = "UPDATE ". INA_CHALLENGE_USERS ."
			SET count = count + 1
			WHERE user_from = '". $userdata['user_id'] ."'
			AND user_to = '". $who ."'";
	$db->sql_query($sql);
		}
	else
		{
	$sql = "INSERT INTO ". INA_CHALLENGE_USERS ."
			VALUES ('". $who ."', '". $userdata['user_id'] ."', '1')";
	$db->sql_query($sql);
		}

	$sql = "SELECT user
				FROM ". INA_CHALLENGE ."
			WHERE user = '". $userdata['user_id'] ."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$exists = $row['user'];

	if ($exists)
		{
	$sql = "UPDATE ". INA_CHALLENGE ."
			SET count = count + 1
			WHERE user = '". $userdata['user_id'] ."'";
	$db->sql_query($sql);
		}
	else
		{
	$sql = "INSERT INTO ". INA_CHALLENGE ."
			VALUES ('". $userdata['user_id'] ."', '1')";
	$db->sql_query($sql);
		}

	$sql = "SELECT proper_name
				FROM ". iNA_GAMES ."
			WHERE game_id = '". $game ."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$game = $row['proper_name'];

	$sql = "SELECT username
				FROM ". USERS_TABLE ."
			WHERE user_id = '". $who ."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$who = $row['username'];

	$to_return = $who ."%RETURNED%". $game;
	return $to_return;
}

function AdminDefaultOrder()
{
	global $config;
	$admin_default = $config['ina_default_order'];
	if ($admin_default == '1')
		$admin_d = "played ASC";
	if ($admin_default == "2")
		$admin_d = "played DESC";
	if ($admin_default == "3")
		$admin_d = "game_id DESC";
	if ($admin_default == "4")
		$admin_d = "game_id ASC";
	if ($admin_default == "5")
		$admin_d = "game_bonus ASC";
	if ($admin_default == "6")
		$admin_d = "game_bonus DESC";
	if ($admin_default == "7")
		$admin_d = "game_charge ASC";
	if ($admin_default == "8")
		$admin_d = "game_charge DESC";
	if ($admin_default == "9")
		$admin_d = "proper_name ASC";
	if ($admin_default == "10")
		$admin_d = "proper_name DESC";

	return $admin_d;
}

function SetHeaderLinks()
{
	global $config, $userdata, $lang, $images;
	$links = '';

	if (!$config['ina_disable_trophy_page'])
		$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
					<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=trophy">' . $lang['trophy_page'] . '</a></td></tr>';
	if (!$config['ina_disable_challenges_page'])
		$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
					<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=challenges">' . $lang['challenge_Link'] . '</a></td></tr>';
	if (!$config['ina_disable_gamble_page'])
		$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
					<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=gambling">' . $lang['gambling_link_2'] . '</a></td>
						</tr>';
	if (!$config['ina_disable_top5_page'])
		$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
					<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=top">' . $lang['top_five_10'] . '</a></td></tr>';
	if ($userdata['user_level'] == ADMIN)
		{
	$links = '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=trophy">' . $lang['trophy_page'] . '</a></td></tr>
				<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=challenges">' . $lang['challenge_Link'] . '</a></td></tr>
				<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=gambling">' . $lang['gambling_link_2'] . '</a></td></tr>
				<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=top">' . $lang['top_five_10'] . '</a></td></tr>';
		}
	$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=search">' . $lang['search_link'] . '</a></td></tr>';
	$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=services">' . $lang['services_page_title'] . '</a></td></tr>';
	$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=hof">' . $lang['hof_link'] . '</a></td></tr>';
	$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=settings">' . $lang['games_settings_link'] . '</a></td></tr>';
	$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
				<td class="genmed" align="left"><a href="activity_char.' . PHP_EXT . '">' . $lang['amp_char_link_back'] . '</a></td></tr>';
	return $links;
}


#============================ Function Altered In .9 Thanks To alphalogic ====
function CheckGameImages($game_name, $proper_name)
{
	global $db, $config, $lang, $userdata;

	$sub_link = str_replace('//', '/', ACTIVITY_GAMES_PATH . $config['ina_default_g_path'] . '/' . $game_name . '/' . $game_name . '.gif');
	$no_sub_link = str_replace('//', '/', ACTIVITY_GAMES_PATH . $config['ina_default_g_path'] . '/' . $game_name . '.gif');

	if (file_exists($sub_link))
	{
		$game_link = '<img src="' . $sub_link . '" alt="' . htmlspecialchars($proper_name) . '" />';
	}
	elseif (file_exists($no_sub_link))
	{
		$game_link = '<img src="' . $no_sub_link . '" alt="' . htmlspecialchars($proper_name) . '" />';
	}
	else
	{
		$game_link = $lang['game_link_play'] . '<b>' . htmlspecialchars($proper_name) . '</b>.';
	}

	return $game_link;
}

function TrophyKingRankCheck()
{
	global $lang, $config, $db;

	#==== If switched on, do some checks
	if ($config['ina_use_trophy'])
		{
	$sql = "SELECT *
				FROM ". USERS_TABLE ."
			ORDER BY user_trophies DESC
			LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$king = $row['user_id'];

		#==== If the current holder is not the one the config table shows, change it accordingly
		if ($king != $config['ina_trophy_king'])
			{
			if ($king != ANONYMOUS)
				{
			$sql = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '". $king ."'
					WHERE config_name = 'ina_trophy_king'";
			$db->sql_query($sql);
				}
			}
		}

	#==== If it was switched off, do some checks
	if (!$config['ina_use_trophy'])
		{
		#==== If off & the current trophy king still has the trophy rank, reset it
		if ($config['ina_trophy_king'])
			{
		$sql = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '0'
				WHERE config_name = 'ina_trophy_king'";
		$db->sql_query($sql);
			}
		}
}

function Gamble($score, $id)
{
	global $db, $userdata;

	$sql = "SELECT *
			FROM ". INA_GAMBLE_GAMES ."
			WHERE game_id = '". $id ."' AND
			sender_id = '". $userdata['user_id'] ."' AND
			sender_playing = '1'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$exists = $row['sender_score'];

	if ($exists < '1')
		{
	$sql = "UPDATE ". INA_GAMBLE_GAMES ."
			SET sender_score = '". $score ."'
			WHERE game_id = '". $id ."' AND
			sender_id = '". $userdata['user_id'] ."' AND
			sender_playing = '1'";
	$db->sql_query($sql);
		}

	$sql = "SELECT *
			FROM ". INA_GAMBLE_GAMES ."
			WHERE game_id = '$id' AND
			reciever_id = '". $userdata['user_id'] ."' AND
			reciever_playing = '1'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$exists = $row['reciever_score'];

	if ($exists < '1')
		{
	$sql = "UPDATE ". INA_GAMBLE_GAMES ."
			SET reciever_score = '". $score ."'
			WHERE game_id = '". $id ."' AND
			reciever_id = '". $userdata['user_id'] ."' AND
			reciever_playing = '1'";
	$db->sql_query($sql);
		}
	return;
}

function UpdateUsersPage($user, $page)
{
	global $db, $userdata;

	if ($userdata['ina_last_visit_page'] != $page)
		{
	$sql = "UPDATE ". USERS_TABLE ."
			SET ina_last_visit_page = '". $page ."'
			WHERE user_id = '". $user ."'";
	$db->sql_query($sql);
		}
	return;
}

function CheckGamesPerDayMax($user, $username)
{
	global $db, $config, $lang;

	if (!$config['ina_use_max_games_per_day'])
		{
	$sql = "UPDATE ". CONFIG_TABLE ."
			SET config_value = '". gmdate('Y-m-d') ."'
			WHERE config_name = 'ina_max_games_per_day_date'";
	$db->sql_query($sql);
		}

	if ($config['ina_use_max_games_per_day'])
		{
	$sql = "SELECT ina_games_today
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $user ."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

		if ($row['ina_games_today'] > $config['ina_max_games_per_day'])
			message_die(GENERAL_ERROR, str_replace("%U%", $username, $lang['max_games_played_error']), $lang['error_message']);

		if ($row['ina_games_today'] < $config['ina_max_games_per_day'])
			{
	$sql = "UPDATE ". USERS_TABLE ."
			SET ina_games_today = ina_games_today + 1
			WHERE user_id = '". $user ."'";
	$db->sql_query($sql);
			}
		}

	if ($config['ina_use_max_games_per_day'] <> gmdate('Y-m-d'))
		{
	$sql = "UPDATE ". USERS_TABLE ."
			SET ina_games_today = '0'
			WHERE user_id > '0'";
	$db->sql_query($sql);

	$sql = "UPDATE ". CONFIG_TABLE ."
			SET config_value = '". gmdate('Y-m-d') ."'
			WHERE config_name = 'ina_max_games_per_day_date'";
	$db->sql_query($sql);
		}
	return;
}

function InsertPlayingGame($user, $game_id)
{
	global $db;

	$sql = "UPDATE ". USERS_TABLE ."
			SET ina_game_playing = '". $game_id ."'
			WHERE user_id = '". $user ."'";
	$db->sql_query($sql);

	return;
}

function RemovePlayingGame($user)
{
	global $db;

	$sql = "UPDATE ". USERS_TABLE ."
			SET ina_game_playing = '0'
			WHERE user_id = '". $user ."'";
	$result = $db->sql_query($sql);

	return;
}

function CleanInaSessions($expired)
{
	global $db;

	$sql = "DELETE FROM ". INA_SESSIONS ."
			WHERE playing_time < '". $expired ."'";
	$db->sql_query($sql);

	return;
}

function FormatScores($score)
{
	$score_check = explode('.', $score);
	$score_check_1 = number_format((double) $score_check[0]);
	$score_check_2 = round($score_check[1], 5);

	if ($score_check_2 == '00')
		$new_score = $score_check_1;
	else
		$new_score = $score_check_1 .'.'. $score_check_2;
	return $new_score;
}

function PopupImages($game_name)
{
	global $db, $config, $lang, $userdata;

	$sql = "SELECT proper_name
				FROM ". iNA_GAMES ."
			WHERE game_name = '". $game_name ."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$sub_link = "./". $config['ina_default_g_path'] ."/". $game_name ."/". $game_name .".gif";
	$no_sub_link = "./". $config['ina_default_g_path'] ."/". $game_name .".gif";

	if ((file_exists($sub_link) == 0) && (file_exists($no_sub_link) == 0))
		$game_link = '<b>' . $row['proper_name'] ."</b>.";

	if (file_exists($sub_link))
		$game_link = '<img src="./' . $config['ina_default_g_path'] . $game_name . '/' . $game_name . '.gif" alt="" />';

	if (file_exists($no_sub_link))
		$game_link = '<img src="./' . $config['ina_default_g_path'] . $game_name . '.gif" alt="" />';

	return $game_link;
}

function UpdateUsersGames($user)
{
	global $db, $userdata;

	$sql = "UPDATE ". USERS_TABLE ."
			SET ina_games_played = ina_games_played + '1'
			WHERE user_id = '". $user ."'";
	$result = $db->sql_query($sql);

	return;
}

function DeletedAMPUser($user_id, $username)
{
	global $db;

	#====== Comments Table
	$sql = "DELETE FROM ". INA_TROPHY_COMMENTS ."
			WHERE player = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Trophy Table, Gonna Be A Mess!
		#===== Get All Games They Have A Trophy For
	$sql = "SELECT *
			FROM ". INA_TROPHY ."
			WHERE player = '". $user_id ."'";
	$result = $db->sql_query($sql);
	$trophy_data = $db->sql_fetchrowset($result);
	$trophy_count = $db->sql_numrows($result);

		#===== Get All Games In The Database
	$sql = "SELECT *
			FROM ". iNA_GAMES ."";
	$result = $db->sql_query($sql);
	$game_data = $db->sql_fetchrowset($result);
	$game_count = $db->sql_numrows($result);

		#===== Get All Max Scores & Min In The Database
	$sql = "SELECT MAX(score) AS highest, MIN(score) AS lowest, game_name, player, date
			FROM ". iNA_SCORES ."
			GROUP BY game_id";
	$result = $db->sql_query($sql);
	$score_data = $db->sql_fetchrowset($result);
	$score_count = $db->sql_numrows($result);

	for($a = 0; $a <= $trophy_count; $a++)
		{
		for($b = 0; $b <= $game_count; $b++)
			{
			if($trophy_data[$a]['game_name'] == $game_data[$b]['game_name'])
				{
				for($c = 0; $c <= $score_count; $c++)
					{
					if(!$game_data[$b]['reverse_list'])
						{
						#===== Normal Ordered Scores
					$sql = "UPDATE ". INA_TROPHY ."
							SET player = '". $score_data[$c]['player'] ."', score = '". $score_data[$c]['highest'] ."', date = '". $score_data[$c]['date'] ."'
							WHERE game_name = '". $game_data[$b]['game_name'] ."'";
					$result = $db->sql_query($sql);
						}
					else
						{
						#===== Reverse Ordered Scores
					$sql = "UPDATE ". INA_TROPHY ."
							SET player = '". $score_data[$c]['player'] ."', score = '". $score_data[$c]['lowest'] ."', date = '". $score_data[$c]['date'] ."'
							WHERE game_name = '". $game_data[$b]['game_name'] ."'";
					$result = $db->sql_query($sql);
						}
					}
				}
			}
		}

	#====== Scores Table
	$sql = "DELETE FROM ". iNA_SCORES ."
			WHERE player = '". $username ."'";
	$result = $db->sql_query($sql);

	#====== Rating Table
	$sql = "DELETE FROM ". INA_RATINGS ."
			WHERE player = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Challenge Tracker Table
	$sql = "DELETE FROM ". INA_CHALLENGE ."
			WHERE user = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Challenge Data Table
	$sql = "DELETE FROM ". INA_CHALLENGE_USERS ."
			WHERE user_from = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Last Game Played Table
	$sql = "DELETE FROM ". INA_LAST_GAME ."
			WHERE user_id = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Sessions Table
	$sql = "DELETE FROM ". INA_SESSIONS ."
			WHERE playing_id = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Favorites Table
	$sql = "DELETE FROM ". INA_FAVORITES ."
			WHERE user = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Ban Table
	$sql = "DELETE FROM ". INA_BAN ."
			WHERE id = '". $user_id ."'";
	$result = $db->sql_query($sql);

	$sql = "DELETE FROM ". INA_BAN ."
			WHERE username = '". $username ."'";
	$result = $db->sql_query($sql);

	#====== Gamble In Progress Table
	$sql = "DELETE FROM ". INA_GAMBLE_GAMES ."
			WHERE player = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Gamble Table
	$sql = "DELETE FROM ". INA_GAMBLE ."
			WHERE player = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Trophy Comments Table
	$sql = "DELETE FROM ". INA_TROPHY_COMMENTS ."
			WHERE player = '". $user_id ."'";
	$result = $db->sql_query($sql);

	#====== Cheat Fix Table
	$sql = "DELETE FROM ". INA_CHEAT ."
			WHERE user = '". $user_id ."'";
	$result = $db->sql_query($sql);
}

function HallOfFamePass($user, $score, $game, $order)
{
	global $db;

	$sql = "SELECT *
			FROM ". INA_HOF ."
			WHERE game_id = '". $game ."'";
	$result = $db->sql_query($sql);
	$data = $db->sql_fetchrow($result);

	$cur_s = $data['current_score'];
	$cur_d = $data['current_date'];
	$cur_u = $data['current_user_id'];

	$sql = "UPDATE ". INA_HOF ."
			SET current_user_id = '". $user ."', current_score = '". $score ."', `current_date` = '". time() ."', old_user_id = '". $cur_u ."', old_score = '". $cur_s ."', `old_date` = '". $cur_d ."'
			WHERE game_id = '". $game ."'";

	if (($score > $cur_s) && (!$order))
		$db->sql_query($sql);

	if (($score < $cur_s) && ($order == '1'))
		$db->sql_query($sql);

	if (!$data['current_score'] && !$data['current_date'] && !$data['current_user_id'])
		{
	$sql = "INSERT INTO ". INA_HOF ."
			(current_user_id, current_score, `current_date`, game_id)
			VALUES
			('". $user ."', '". $score ."', '". time() ."', '". $game ."')";
		$db->sql_query($sql);
		}
}

function AddJackpot($game_id, $game_cost)
{
	global $db;
	if ($game_cost > '0')
		{
	$sql = "UPDATE ". iNA_GAMES ."
			SET jackpot = jackpot + ". $game_cost ."
			WHERE game_id = '". $game_id ."'";
	$db->sql_query($sql);
		}
}

function ResetJackpot($game_id)
{
	global $db, $config;

	$sql = "UPDATE ". iNA_GAMES ."
			SET jackpot = '". $config['ina_jackpot_pool'] ."'
			WHERE game_id = '". $game_id ."'";
	$db->sql_query($sql);
}

function GameArrayLink($id, $parent, $popup, $win_width, $win_height, $type, $links)
{
	global $userdata, $lang;

	$link = '';
	$switch = '';
	$switch = $type;
	if (eregi('%SEP%', $switch))
		$switch = explode('%SEP%', $switch);
	if (($parent) && ($switch == '1'))
		$link .= '&nbsp;<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'">'. $lang['same_window'] .'</a><br />';
	if (($popup) && ($switch == '1'))
		$link .= '&nbsp;<a href="#" onclick="popup_open(\'activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;sid=' . $userdata['session_id'] .'\', \'New_Window\', \''. $win_width .'\', \''. $win_height .'\', \'no\'); blur(); return false;">' . $lang['new_window'] . '</a>';
	if (($parent) && ($switch == '2'))
		$link = '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'">';
	if (($popup) && ($switch == '2'))
		$link = '<a href="#" onclick="popup_open(\'activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;sid=' . $userdata['session_id'] .'\', \'New_Window\', \''. $win_width .'\', \''. $win_height .'\', \'no\'); blur(); return false;">';
	if (($popup) && ($parent) && ($switch == '2'))
		$link = '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'">';
	if (($parent) && ($switch[0] == 3))
		$link = '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'">' . $switch[1] .'</a>';
	if (($popup) && ($switch[0] == 3))
		$link = '<a href="#" onclick="popup_open(\'activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;sid=' . $userdata['session_id'] .'\', \'New_Window\', \''. $win_width .'\', \''. $win_height .'\', \'no\'); blur(); return false;">'. $switch[1] .'</a>';
	if (($popup) && ($parent) && ($switch[0] == 3))
		$link = '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'">' . $switch[1] .'</a>';

	$any_links = explode(';', $links);
	for ($x = 0; $x < sizeof($any_links); $x++)
	{
		if ($any_links[$x])
		{
			$split_link = explode(',', $any_links[$x]);
			$link .= '<br />&nbsp;<a href="' . trim(rtrim($split_link[0])) . '">' . trim(rtrim($split_link[1])) . '</a>';
		}
	}
	return $link;
}

function GameSingleLink($id, $parent, $popup, $win_width, $win_height, $page, $one, $two, $three, $links)
{
	global $userdata;

	$link = '';
	if (($parent) && ($popup))
	{
		$link = str_replace($one, '<a href="'. append_sid($page . PHP_EXT . '?mode=game&amp;id=' . $id . '&amp;parent=true') . '">' . $two . '</a>', $three);
	}
	elseif (($parent) && (!$popup))
	{
		$link = str_replace($one, '<a href="'. append_sid($page . PHP_EXT . '?mode=game&amp;id=' . $id . '&amp;parent=true') . '">' . $two . '</a>', $three);
	}
	else
	{
		$link = str_replace($one, '<a href="javascript:popup_open(\'' . $page . PHP_EXT . '?mode=game&amp;id=' . $id . '&amp;sid=' . $userdata['session_id'] .'\',\'New_Window\',\''. $width .'\',\''. $height .'\',\'no\')">'. $two .'</a>', $three);
	}

	$any_links = explode(';', $links);
	for ($x = 0; $x < sizeof($any_links); $x++)
	{
		if ($any_links[$x])
		{
			$split_link = explode(',', $any_links[$x]);
			$link .= '<br /><b>&bull;</b>&nbsp;<a href="' . trim(rtrim($split_link[0])) . '">'. trim(rtrim($split_link[1])) .'</a>';
		}
	}

	return $link;
}

function GamesPassLength($page)
{
	global $userdata, $config, $lang, $db;

	#==== Drop the users pass 1 day, every day they play.
	if (!$page)
		{
		if (($userdata['ina_games_pass_day'] != gmdate('Y-m-d')) && ($userdata['ina_games_pass'] > 0))
			{
		$sql = "UPDATE ". USERS_TABLE ."
				SET ina_games_pass = ina_games_pass - 1, ina_games_pass_day = '". gmdate('Y-m-d') ."'
				WHERE user_id = '". $userdata['user_id'] ."'";
		$db->sql_query($sql);
			}
		}

	#==== The display on activity.php
	if ($page == 1)
		{
		#==== Is it active? Is points on?
		if (($config['ina_game_pass_cost']) && ($config['ina_game_pass_length']) && ($config['use_rewards_mod']))
			{
		$user_pass = $userdata['ina_game_pass'];

		if ($config['use_point_system'])
			$points_cost = $config['ina_game_pass_cost'] .' '. $config['points_name'];

		if ($config['use_cash_system'])
			$points_cost = $config['ina_game_pass_cost'] .' '. $config['ina_cash_name'];

			if ($user_pass < 1)
				$msg = str_replace('%C%', $points_cost, '<a href="activity.' . PHP_EXT . '?mode=game_pass">'. $lang['game_pass_buy'] .'</a>');

			if ($user_pass == 1)
				$msg = $lang['game_pass_left_one'];

			if ($user_pass > 1)
				$msg = str_replace('%T%', $user_pass, $lang['game_pass_left_multi']);

			return $msg;
			} #==== Is active
		} #==== Page = 1

	#==== Buy a pass
	if ($page == 2)
		{

		}
}

function UpdateGamePlayTime($time, $info)
{
	global $db, $userdata;

	$info = explode(';;', $info);
	$time_started = $info[0];
	$time_spent = $info[1];
	$time_elapsed = (time() - $time_started);
	$new_time_spent = ($time_spent + $time_elapsed);
	$final_entry = $time .';;'. $new_time_spent;

	$sql = "UPDATE ". USERS_TABLE ."
			SET ina_time_playing = '". $final_entry ."'
			WHERE user_id = '". $userdata['user_id'] ."'";
	$db->sql_query($sql);
}

function DisplayPlayingTime($page, $time)
{
	global $userdata, $lang;

	$time_spent = explode(';;', $time);
	$math_start = $time_spent[1];

	$hours = floor ($math_start / 3600);
	$math_start = ($math_start - ($hours * 3600));
	$minutes = floor ($math_start / 60);
	$seconds = ($math_start - ($minutes * 60));

	$time_spent_pass_one = str_replace('%H%', $hours, (($page == 1) ? $lang['info_box_time_spent'] : $lang['info_box_time_spent_two']));
	$time_spent_pass_two = str_replace('%M%', $minutes, $time_spent_pass_one);
	$time_spent_pass_three = str_replace('%S%', $seconds, $time_spent_pass_two);
	$time_spent_pass_four = str_replace('%LH%', (($hours == 1) ? $lang['info_box_time_spent_hour'] : $lang['info_box_time_spent_hours']), $time_spent_pass_three);
	$time_spent_pass_five = str_replace('%LM%', (($minutes == 1) ? $lang['info_box_time_spent_min'] : $lang['info_box_time_spent_mins']), $time_spent_pass_four);
	$time_spent = str_replace('%LS%', (($seconds == 1) ? $lang['info_box_time_spent_sec'] : $lang['info_box_time_spent_secs']), $time_spent_pass_five);

	return $time_spent;
}

function Amod_Grab_Cat($cat_id, $cat_info)
{
	global $lang;
	if (!is_array($cat_info))
		return;

	for ($c = 0; $c < sizeof($cat_info); $c++)
		{
		if ($cat_info[$c]['cat_id'] == $cat_id)
			{
		return sprintf($lang['game_rows_category_yes'], '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=category_play&amp;cat=' . $cat_id) . '" class="copyright">'. $cat_info[$c]['cat_name'] .'</a>');
		break;
			}

		if (!$cat_info[$c]['cat_id'])
			break;
		}
}

function Amod_Build_Topics($hof_data, $user_id, $user_trophies, $user_name, $user_char)
{
	global $config, $lang, $userdata;

	unset($hof, $amod_stats, $char, $hof_link, $trophy_count, $trophy_holder, $trophy, $trophies, $show_trophies, $trophy_image);

	#==== Output The Hall Of Fame Link
	for ($hof = 0; $hof < sizeof($hof_data); $hof++)
		{
		if (!$hof_data[$hof])
			break;

		if ($hof_data[$hof]['current_user_id'] == $user_id)
			{
		$hof_link = '<a href="'. append_sid('activity.' . PHP_EXT . '?page=hof&amp;u='. $user_id) . '">'. $lang['hof_topic_profile'] .'</a>';
		break;
			}
		}

	#==== Output Trophies
	if (($config['ina_show_view_topic']) && ($user_trophies > 0) && ($user_id != ANONYMOUS))
		$trophies = '<a href="#" onclick="popup_open(\'activity_trophy_popup.' . PHP_EXT . '?user=' . $user_id . '&amp;sid=' . $userdata['session_id'] . '\', \'New_Window\', \'400\', \'380\', \'yes\'); blur(); return false;">' . $lang['Trohpy'] . '</a>:&nbsp;&nbsp;' . $user_trophies;

	#==== Output Character Link
	if (($config['ina_char_show_viewtopic']) && ($user_char) && ($user_id != ANONYMOUS))
		$char = '<a href="activity_char.' . PHP_EXT . '?mode=profile_char&amp;char='. $user_id .'&amp;sid=' . $userdata['session_id'] . '">'. $lang['amp_char_topic_link'] .'</a>';

	if ($trophies)
		$amod_stats .= $trophies .'<br />';
	if ($hof_link)
		$amod_stats .= $hof_link .'<br />';
	if ($char)
		$amod_stats .= $char .'<br />';

	return ('<span class="gensmall">'. $amod_stats .'</span>');
}

function Amod_Trophy_King_Image($user_id)
{
	global $config, $lang;
	unset($trophy_king, $trophy_image);
	$trophy_image = '<img src="' . ACTIVITY_IMAGES_PATH . 'trophy_king.gif" alt="' . $lang['trophy_holder_rank_name'] . '" title="' . $lang['trophy_holder_rank_name'] . '">';
	if (($config['ina_use_trophy']) && ($user_id == $config['ina_trophy_king']))
		$trophy_king = '<br />'. $trophy_image;

	return $trophy_king;
}

function Amod_Individual_Game_Time($plays, $time)
{
	global $lang;
	$i_hours = floor ($time / 3600);
	$i_math = ($time - ($i_hours * 3600));
	$i_minutes = floor ($i_math / 60);
	$i_seconds = ($i_math - ($i_minutes * 60));
	$played = $plays;
	$hours = ($i_hours == 1) ? $lang['game_info_hour'] : $lang['game_info_hours'];
	$mins = ($i_minutes == 1) ? $lang['game_info_min'] : $lang['game_info_mins'];
	$secs = ($i_seconds == 1) ? $lang['game_info_sec'] : $lang['game_info_secs'];
	$plays = ($plays == 1) ? $lang['game_info_time'] : $lang['game_info_times'];
	$display = sprintf($lang['game_info_display'], number_format($played) .' '. $plays, ($i_hours < 1) ? '' : (number_format($i_hours) .' '. $hours), ($i_minutes < 1) ? '' : (number_format($i_minutes) .' '. $mins), (number_format($i_seconds) .' '. $secs));
	return '  '. $display;
}

?>