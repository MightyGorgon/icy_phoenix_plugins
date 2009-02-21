<?
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

// CTracker_Ignore: File checked by human
if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking Attempt');
}

global $table_prefix, $board_config;

include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_activity.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_activity_char.' . PHP_EXT);
include_once(IP_ROOT_PATH . ACTIVITY_MOD_PATH . 'includes/functions_amod_plus_char.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);

$mem_limit = check_mem_limit();
@ini_set('memory_limit', $mem_limit);

define('INA_BAN', $table_prefix . 'ina_ban');
define('INA_CATEGORY', $table_prefix . 'ina_categories');
define('INA_CHALLENGE', $table_prefix . 'ina_challenge_tracker');
define('INA_CHALLENGE_USERS', $table_prefix . 'ina_challenge_users');
define('INA_CHEAT', $table_prefix . 'ina_cheat_fix');
define('INA_DISABLE', $table_prefix . 'ina_hidden');
define('INA_GAMBLE', $table_prefix . 'ina_gamble');
define('INA_GAMBLE_GAMES', $table_prefix . 'ina_gamble_in_progress');
define('INA_LAST_GAME', $table_prefix . 'ina_last_game_played');
define('INA_SESSIONS', $table_prefix . 'ina_sessions');
define('INA_TROPHY', $table_prefix . 'ina_top_scores');
define('INA_TROPHY_COMMENTS', $table_prefix . 'ina_trophy_comments');
define('INA_RATINGS', $table_prefix . 'ina_rating_votes');
define('INA_FAVORITES', $table_prefix . 'ina_favorites');
define('INA_HOF', $table_prefix . 'ina_hall_of_fame');
define('INA_CHAT', $table_prefix . 'ina_chat');

function UpdateSessions()
{
	global $userdata, $db;

	$q = "SELECT playing_id
			FROM " . INA_SESSIONS . "
			WHERE playing_id = '" . $userdata['user_id'] . "'";
	$r = $db->sql_query($q);
	$exists = $db->sql_fetchrow($r);

	if (($exists) && ($userdata['user_id'] != ANONYMOUS))
	{
		$q = "UPDATE " . INA_SESSIONS . "
				SET playing_time = '" . time() . "'
				WHERE playing_id = '" . $userdata['user_id'] . "'";
		$db->sql_query($q);
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

		$q = "INSERT INTO " . INA_SESSIONS . "
				VALUES ('" . time() . "', '" . $userdata['user_id'] . "', '" . $logged_in . "')";
		$db->sql_query($q);
	}

	$q = "DELETE FROM " . INA_SESSIONS . "
			WHERE playing_time < '" . $expired . "'";
	$db->sql_query($q1);
}

function BanCheck()
{
	global $userdata, $db, $lang, $board_config;

	$q = "SELECT id
			FROM ". INA_BAN ."
			WHERE id = '". $userdata['user_id'] ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$ban_1 = $row['id'];

	if ($ban_1)
	{
		message_die(GENERAL_ERROR, $lang['ban'], $lang['ban_error']);
	}

	$q = "SELECT *
			FROM ". INA_BAN ."
			WHERE username = '". $userdata['username'] ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$ban_2 = $row['username'];

	if ($ban_2)
	{
		message_die(GENERAL_ERROR, $lang['ban'], $lang['ban_error']);
	}

	if ($board_config['ina_post_block'] == '1')
	{
		if($userdata['user_posts'] < $board_config['ina_post_block_count'])
		{
			message_die(GENERAL_ERROR, str_replace("%B%", $board_config['ina_post_block_count'], $lang['restriction_check_1']), $lang['ban_error']);
		}
	}

	if ($board_config['ina_join_block'] == '1')
	{
		$days_block = $board_config['ina_join_block_count'];
		$length_check = time() - $userdata['user_regdate'];
		$length_block = $length_check / 86400;
		$rounded = round($length_block);

		if ($rounded < $days_block)
		{
			message_die(GENERAL_ERROR, str_replace("%B%", $board_config['ina_join_block_count'], $lang['restriction_check_2']), $lang['ban_error']);
		}
	}

	if ( ($board_config['ina_disable_everything']) && ($userdata['user_level'] != ADMIN) )
	{
		message_die(GENERAL_ERROR, $lang['restriction_check_3'], $lang['ban_error']);
	}

}

/* Borrowed From ADR & Modified So I Wouldn't Have To Write It From Scratch =-) */
function send_challenge_pm($dest_user, $subject, $message)
{
	global $db, $lang, $user_ip, $board_config, $userdata;

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

	include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

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
		if (!($result = $db->sql_query($sql)))
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
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_user']);
		}

		if($inbox_info = $db->sql_fetchrow($result))
		{
			if ($inbox_info['inbox_items'] >= $board_config['max_inbox_privmsgs'])
			{
				$sql = "SELECT privmsgs_id
						FROM " . PRIVMSGS_TABLE . "
						WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
						OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "
						OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " )
						AND privmsgs_date = " . $inbox_info['oldest_post_time'] . "
						AND privmsgs_to_userid = '" . $dest_user . "'";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not find oldest privmsgs (inbox)', '', __LINE__, __FILE__, $sql);
				}
				$old_privmsgs_id = $db->sql_fetchrow($result);
				$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];

				$sql = "DELETE FROM ". PRIVMSGS_TABLE ."
						WHERE privmsgs_id = '". $old_privmsgs_id ."'";
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs (inbox)'.$sql, '', __LINE__, __FILE__, $sql);
				}
			}
		}

		$sql_info = "INSERT INTO ". PRIVMSGS_TABLE . "
					(privmsgs_type, privmsgs_subject, privmsgs_text, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies)
					VALUES ( 1 , '". str_replace("\'", "''", addslashes($privmsg_subject)) ."', '" . str_replace("\'", "''", addslashes($privmsg_message)) . "', " . $from_id . ", ". $to_userdata['user_id'] .", $msg_time, '$user_ip' , $html_on, $bbcode_on, $smilies_on)";
		if(!$db->sql_query($sql_info))
		{
			message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs text (inbox)', '', __LINE__, __FILE__, $sql_info);
		}

		$sql = "UPDATE ". USERS_TABLE ."
				SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "
				WHERE user_id = '" . $to_userdata['user_id'] . "'";
		if(!$status = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update private message new/read status for user', '', __LINE__, __FILE__, $sql);
		}

		if($to_userdata['user_notify_pm'] && !empty($to_userdata['user_email']) && $to_userdata['user_active'] )
		{
			// have the mail sender infos
			$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
			$script_name = ( $script_name != '' ) ? $script_name . '/privmsg.' . PHP_EXT : 'privmsg.' . PHP_EXT;
			$server_name = trim($board_config['server_name']);
			$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
			$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

			include_once(IP_ROOT_PATH . './includes/emailer.' . PHP_EXT);
			$emailer = new emailer($board_config['smtp_delivery']);

			$email_headers = 'From: ' . $board_config['board_email'] . "\nReturn-Path: " . $board_config['board_email'] . "\n";
			$emailer->use_template('privmsg_notify', $to_userdata['user_lang']);
			$emailer->extra_headers($email_headers);
			$emailer->email_address($to_userdata['user_email']);
			$emailer->set_subject($lang['Notification_subject']);

			if ( $board_config['html_email'] === '1')
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
			$emailer->assign_vars(array(
				// Mighty Gorgon - Begin
				'FROM' => $userdata['username'],
				'DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
				'SUBJECT' => $privmsg_subject,
				'PRIV_MSG_TEXT' => $message,
				// Mighty Gorgon - End
				'USERNAME' => $to_username,
				'SITENAME' => $board_config['sitename'],
				'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
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
			global $table_prefix, $db, $lang;

			$q = "SELECT *
					FROM ". INA_GAMBLE_GAMES ."
					WHERE reciever_playing = '1'
					AND sender_playing = '1'
					AND reciever_score > '0'
					AND sender_score > '0'";
			$r = $db->sql_query($q);
			while ($row = $db->sql_fetchrow($r))
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
		global $table_prefix, $db, $board_config;

		$q = "SELECT *
				FROM ". INA_GAMBLE ."
				WHERE been_paid <> '1'
				AND winner_score > '0'
				AND loser_score > '0'
				AND amount > '0'";
		$r	 = $db->sql_query($q);
		while ($row = $db->sql_fetchrow($r))
			{
		$amount = $row['amount'];
		$winner = $row['winner_id'];
		$loser = $row['loser_id'];
		$game = $row['game_id'];

				if ( ($board_config['use_rewards_mod']) && ($board_config['use_point_system']) )
					{
				subtract_points($loser, $amount);
				add_points($winner, $amount);
					}

				if ( ($board_config['use_cash_system'] || $board_config['use_allowance_system']) && $board_config['use_rewards_mod'])
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
		global $table_prefix, $db, $userdata;

		$q = "UPDATE ". SESSIONS_TABLE ." s, ". USERS_TABLE ." u
				SET s.session_page = '". PAGE_ACTIVITY ."', u.user_session_page = '". PAGE_ACTIVITY ."'
				WHERE s.session_user_id = '". $userdata['user_id'] ."'
				AND u.user_id = '". $userdata['user_id'] ."'";
		$db->sql_query($q);
		return;
		}

	function ChallengeSelected($who, $game)
		{
		global $db, $userdata, $table_prefix;

		$sql = "UPDATE ". CONFIG_TABLE ."
				SET config_value = config_value + 1
				WHERE config_name = 'challenges_sent'";
		if (!$db->sql_query($sql))
			message_die(GENERAL_ERROR, "Error Updating Challenge Count.", '', __LINE__, __FILE__, $sql);

		$q = "SELECT *
					FROM ". INA_CHALLENGE_USERS ."
				WHERE user_from = '". $userdata['user_id'] ."'
				AND user_to = '". $who ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$there = $row['count'];

		if ($there)
			{
		$q = "UPDATE ". INA_CHALLENGE_USERS ."
				SET count = count + 1
				WHERE user_from = '". $userdata['user_id'] ."'
				AND user_to = '". $who ."'";
		$db->sql_query($q);
			}
		else
			{
		$q = "INSERT INTO ". INA_CHALLENGE_USERS ."
				VALUES ('". $who ."', '". $userdata['user_id'] ."', '1')";
		$db->sql_query($q);
			}

		$q = "SELECT user
					FROM ". INA_CHALLENGE ."
				WHERE user = '". $userdata['user_id'] ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$exists = $row['user'];

		if ($exists)
			{
		$q = "UPDATE ". INA_CHALLENGE ."
				SET count = count + 1
				WHERE user = '". $userdata['user_id'] ."'";
		$db->sql_query($q);
			}
		else
			{
		$q = "INSERT INTO ". INA_CHALLENGE ."
				VALUES ('". $userdata['user_id'] ."', '1')";
		$db->sql_query($q);
			}

		$q = "SELECT proper_name
					FROM ". iNA_GAMES ."
				WHERE game_id = '". $game ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$game = $row['proper_name'];

		$q = "SELECT username
					FROM ". USERS_TABLE ."
				WHERE user_id = '". $who ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$who = $row['username'];

		$to_return = $who ."%RETURNED%". $game;
		return $to_return;
		}

	function AdminDefaultOrder()
		{
		global $board_config;
	$admin_default = $board_config['ina_default_order'];
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
		global $board_config, $userdata, $lang, $images;
		$links = '';

		if (!$board_config['ina_disable_trophy_page'])
			$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
						<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=trophy">' . $lang['trophy_page'] . '</a></td></tr>';
		if (!$board_config['ina_disable_challenges_page'])
			$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
						<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=challenges">' . $lang['challenge_Link'] . '</a></td></tr>';
		if (!$board_config['ina_disable_gamble_page'])
			$links .= '<tr><td width="8" align="left" valign="middle"><img src="' . $images['menu_sep'] . '" alt="" /></td>
						<td class="genmed" align="left"><a href="activity.' . PHP_EXT . '?page=gambling">' . $lang['gambling_link_2'] . '</a></td>
							</tr>';
		if (!$board_config['ina_disable_top5_page'])
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
		global $lang, $board_config, $db, $userdata, $table_prefix;

		$sub_link = "./". $board_config['ina_default_g_path'] . "/" . $game_name . "/" . $game_name . ".gif";
		$no_sub_link = "./" . $board_config['ina_default_g_path'] . "/" . $game_name . ".gif";

		if ( (file_exists($sub_link) == 0) && (file_exists($no_sub_link) == 0) )
			$game_link = $lang['game_link_play'] .'<b>' . $proper_name . '</b>.';

		if (file_exists($sub_link))
			$game_link = '<img src="./' . $board_config['ina_default_g_path'] . $game_name . '/' . $game_name . '.gif" alt="" />';

		if (file_exists($no_sub_link))
			$game_link = '<img src="./' . $board_config['ina_default_g_path'] . $game_name . '.gif" alt="" />';

		return $game_link;
		}

	function TrophyKingRankCheck()
		{
		global $lang, $board_config, $db;

		#==== If switched on, do some checks
		if ($board_config['ina_use_trophy'])
			{
		$q = "SELECT *
					FROM ". USERS_TABLE ."
				ORDER BY user_trophies DESC
				LIMIT 1";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$king = $row['user_id'];

			#==== If the current holder is not the one the config table shows, change it accordingly
			if ($king != $board_config['ina_trophy_king'])
				{
				if ($king != ANONYMOUS)
					{
				$q = "UPDATE ". CONFIG_TABLE ."
						SET config_value = '". $king ."'
						WHERE config_name = 'ina_trophy_king'";
				$db->sql_query($q);
					}
				}
			}

		#==== If it was switched off, do some checks
		if (!$board_config['ina_use_trophy'])
			{
			#==== If off & the current trophy king still has the trophy rank, reset it
			if ($board_config['ina_trophy_king'])
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '0'
					WHERE config_name = 'ina_trophy_king'";
			$db->sql_query($q);
				}
			}
		}

	function Gamble($score, $id)
		{
		global $table_prefix, $db, $userdata;

		$q = "SELECT *
				FROM ". INA_GAMBLE_GAMES ."
				WHERE game_id = '". $id ."' AND
				sender_id = '". $userdata['user_id'] ."' AND
				sender_playing = '1'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$exists = $row['sender_score'];

		if ($exists < '1')
			{
		$q = "UPDATE ". INA_GAMBLE_GAMES ."
				SET sender_score = '". $score ."'
				WHERE game_id = '". $id ."' AND
				sender_id = '". $userdata['user_id'] ."' AND
				sender_playing = '1'";
		$db->sql_query($q);
			}

		$q = "SELECT *
				FROM ". INA_GAMBLE_GAMES ."
				WHERE game_id = '$id' AND
				reciever_id = '". $userdata['user_id'] ."' AND
				reciever_playing = '1'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$exists = $row['reciever_score'];

		if ($exists < '1')
			{
		$q = "UPDATE ". INA_GAMBLE_GAMES ."
				SET reciever_score = '". $score ."'
				WHERE game_id = '". $id ."' AND
				reciever_id = '". $userdata['user_id'] ."' AND
				reciever_playing = '1'";
		$db->sql_query($q);
			}
		return;
		}

	Function UpdateUsersPage($user, $page)
			{
			global $db, $userdata;

			if ($userdata['ina_last_visit_page'] != $page)
				{
			$q = "UPDATE ". USERS_TABLE ."
					SET ina_last_visit_page = '". $page ."'
					WHERE user_id = '". $user ."'";
			$db->sql_query($q);
				}
			return;
			}

	function CheckGamesPerDayMax($user, $username)
		{
		global $board_config, $db, $lang, $table_prefix;

		if (!$board_config['ina_use_max_games_per_day'])
			{
		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '". date("Y-m-d") ."'
				WHERE config_name = 'ina_max_games_per_day_date'";
		$db->sql_query($q);
			}

		if ($board_config['ina_use_max_games_per_day'])
			{
		$q = "SELECT ina_games_today
				FROM ". USERS_TABLE ."
				WHERE user_id = '". $user ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);

			if ($row['ina_games_today'] > $board_config['ina_max_games_per_day'])
				message_die(GENERAL_ERROR, str_replace("%U%", $username, $lang['max_games_played_error']), $lang['error_message']);

			if ($row['ina_games_today'] < $board_config['ina_max_games_per_day'])
				{
		$q = "UPDATE ". USERS_TABLE ."
				SET ina_games_today = ina_games_today + 1
				WHERE user_id = '". $user ."'";
		$db->sql_query($q);
				}
			}

		if ($board_config['ina_use_max_games_per_day'] <> date("Y-m-d"))
			{
		$q = "UPDATE ". USERS_TABLE ."
				SET ina_games_today = '0'
				WHERE user_id > '0'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '". date("Y-m-d") ."'
				WHERE config_name = 'ina_max_games_per_day_date'";
		$db->sql_query($q);
			}
		return;
		}

	function InsertPlayingGame($user, $game_id)
		{
		global $db;

		$q = "UPDATE ". USERS_TABLE ."
				SET ina_game_playing = '". $game_id ."'
				WHERE user_id = '". $user ."'";
		$db->sql_query($q);

		return;
		}

	function RemovePlayingGame($user)
		{
		global $db;

		$q = "UPDATE ". USERS_TABLE ."
				SET ina_game_playing = '0'
				WHERE user_id = '". $user ."'";
		$r = $db->sql_query($q);

		return;
		}

	function CleanInaSessions($expired)
		{
		global $table_prefix, $db;

		$q = "DELETE FROM ". INA_SESSIONS ."
				WHERE playing_time < '". $expired ."'";
		$db->sql_query($q);

		return;
		}

	function FormatScores($score)
		{
		$score_check = explode(".", $score);
		$score_check_1 = number_format($score_check[0]);
		$score_check_2 = round($score_check[1], 5);

		if ($score_check_2 == '00')
			$new_score = $score_check_1;
		else
			$new_score = $score_check_1 .'.'. $score_check_2;
		return $new_score;
		}

	function PopupImages($game_name)
		{
		global $lang, $board_config, $db, $userdata, $table_prefix;

		$q = "SELECT proper_name
					FROM ". iNA_GAMES ."
				WHERE game_name = '". $game_name ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);

	$sub_link = "./". $board_config['ina_default_g_path'] ."/". $game_name ."/". $game_name .".gif";
	$no_sub_link = "./". $board_config['ina_default_g_path'] ."/". $game_name .".gif";

	if ( (file_exists($sub_link) == 0) && (file_exists($no_sub_link) == 0) )
		$game_link = '<b>' . $row['proper_name'] ."</b>.";

	if (file_exists($sub_link))
		$game_link = '<img src="./' . $board_config['ina_default_g_path'] . $game_name . '/' . $game_name . '.gif" alt="" />';

	if (file_exists($no_sub_link))
		$game_link = '<img src="./' . $board_config['ina_default_g_path'] . $game_name . '.gif" alt="" />';

		return $game_link;
		}

	function VersionCheck()
	{
		global $board_config, $userdata;

		$version = '1.1.0';
		$ver_check = $board_config['ina_version'];

		if(!$ver_check)
		{
			$this_version = 'Unknown';
		}
		if($ver_check)
		{
			$this_version = 'v' . $ver_check;
		}
		$error_msg = "Sorry, the games are currently offline until the admin upgrades/installs the current version. The
		current version is <b>". $version ."</b>. Your version is <b>". $this_version ."</b>.";

		if($userdata['user_level'] == ADMIN)
		{
			$msg_switch = 'Since you are an admin, please goto your admin panel, you can get there by clicking <a href="' . ADM . 'index.' . PHP_EXT . '?sid=' . $userdata['session_id'] . '"><i><b>here</b></i></a>. After you do that, please look on the left for <b>Amod+ Admin</b> and under it you will see a link, Db Adjustments. If this is a fresh install for you, please click <b>Install Activity Mod Plus</b>. If you are upgrading from a previous version, please look in the second section, and in the drop down menu and select what you are upgrading from. You are upgrading to <b>' . $version . '</b>. After doing that, this error will go away and you will be allowed to play games.';
		}
		else
		{
			$msg_switch = "Since you are not an admin, and can not fix this, please link this page to an admin so they can get it fixed.";
		}

		$error_div = "<br /><br /><center><b>Instructions</b></center><br /><br />";

		if ($board_config['ina_version'] != $version)
		{
			message_die(CRITICAL_ERROR, $error_msg . $error_div . $msg_switch);
		}

		}

	function UpdateUsersGames($user)
		{
		global $db, $userdata;

		$q = "UPDATE ". USERS_TABLE ."
				SET ina_games_played = ina_games_played + '1'
				WHERE user_id = '". $user ."'";
		$r = $db->sql_query($q);

		return;
		}

	function DeletedAMPUser($user_id, $username)
		{
		global $db;

		#====== Comments Table
		$q = "DELETE FROM ". INA_TROPHY_COMMENTS ."
				WHERE player = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Trophy Table, Gonna Be A Mess!
			#===== Get All Games They Have A Trophy For
		$q = "SELECT *
				FROM ". INA_TROPHY ."
				WHERE player = '". $user_id ."'";
		$r = $db->sql_query($q);
		$trophy_data = $db->sql_fetchrowset($r);
		$trophy_count = $db->sql_numrows($r);

			#===== Get All Games In The Database
		$q = "SELECT *
				FROM ". iNA_GAMES ."";
		$r = $db->sql_query($q);
		$game_data = $db->sql_fetchrowset($r);
		$game_count = $db->sql_numrows($r);

			#===== Get All Max Scores & Min In The Database
		$q = "SELECT MAX(score) AS highest, MIN(score) AS lowest, game_name, player, date
				FROM ". iNA_SCORES ."
				GROUP BY game_id";
		$r = $db->sql_query($q);
		$score_data = $db->sql_fetchrowset($r);
		$score_count = $db->sql_numrows($r);

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
						$q = "UPDATE ". INA_TROPHY ."
								SET player = '". $score_data[$c]['player'] ."', score = '". $score_data[$c]['highest'] ."', date = '". $score_data[$c]['date'] ."'
								WHERE game_name = '". $game_data[$b]['game_name'] ."'";
						$r = $db->sql_query($q);
							}
						else
							{
							#===== Reverse Ordered Scores
						$q = "UPDATE ". INA_TROPHY ."
								SET player = '". $score_data[$c]['player'] ."', score = '". $score_data[$c]['lowest'] ."', date = '". $score_data[$c]['date'] ."'
								WHERE game_name = '". $game_data[$b]['game_name'] ."'";
						$r = $db->sql_query($q);
							}
						}
					}
				}
			}

		#====== Scores Table
		$q = "DELETE FROM ". iNA_SCORES ."
				WHERE player = '". $username ."'";
		$r = $db->sql_query($q);

		#====== Rating Table
		$q = "DELETE FROM ". INA_RATINGS ."
				WHERE player = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Challenge Tracker Table
		$q = "DELETE FROM ". INA_CHALLENGE ."
				WHERE user = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Challenge Data Table
		$q = "DELETE FROM ". INA_CHALLENGE_USERS ."
				WHERE user_from = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Last Game Played Table
		$q = "DELETE FROM ". INA_LAST_GAME ."
				WHERE user_id = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Sessions Table
		$q = "DELETE FROM ". INA_SESSIONS ."
				WHERE playing_id = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Favorites Table
		$q = "DELETE FROM ". INA_FAVORITES ."
				WHERE user = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Ban Table
		$q = "DELETE FROM ". INA_BAN ."
				WHERE id = '". $user_id ."'";
		$r = $db->sql_query($q);

		$q = "DELETE FROM ". INA_BAN ."
				WHERE username = '". $username ."'";
		$r = $db->sql_query($q);

		#====== Gamble In Progress Table
		$q = "DELETE FROM ". INA_GAMBLE_GAMES ."
				WHERE player = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Gamble Table
		$q = "DELETE FROM ". INA_GAMBLE ."
				WHERE player = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Trophy Comments Table
		$q = "DELETE FROM ". INA_TROPHY_COMMENTS ."
				WHERE player = '". $user_id ."'";
		$r = $db->sql_query($q);

		#====== Cheat Fix Table
		$q = "DELETE FROM ". INA_CHEAT ."
				WHERE user = '". $user_id ."'";
		$r = $db->sql_query($q);
		}

	function HallOfFamePass($user, $score, $game, $order)
		{
		global $db, $table_prefix;

	$q = "SELECT *
			FROM ". INA_HOF ."
			WHERE game_id = '". $game ."'";
	$r = $db->sql_query($q);
	$data = $db->sql_fetchrow($r);

	$cur_s = $data['current_score'];
	$cur_d = $data['current_date'];
	$cur_u = $data['current_user_id'];

		$q = "UPDATE ". INA_HOF ."
				SET current_user_id = '". $user ."', current_score = '". $score ."', `current_date` = '". time() ."', old_user_id = '". $cur_u ."', old_score = '". $cur_s ."', `old_date` = '". $cur_d ."'
				WHERE game_id = '". $game ."'";

		if ( ($score > $cur_s) && (!$order) )
			$db->sql_query($q);

		if ( ($score < $cur_s) && ($order == '1') )
			$db->sql_query($q);

		if (!$data['current_score'] && !$data['current_date'] && !$data['current_user_id'])
			{
		$q = "INSERT INTO ". INA_HOF ."
				(current_user_id, current_score, `current_date`, game_id)
				VALUES
				('". $user ."', '". $score ."', '". time() ."', '". $game ."')";
			if (!$db->sql_query($q))
				message_die(GENERAL_ERROR, 'Error inserting HOF score', "", __LINE__, __FILE__, $q);
			}
		}

	function AddJackpot($game_id, $game_cost)
		{
		global $db;
		if ($game_cost > '0')
			{
		$q = "UPDATE ". iNA_GAMES ."
				SET jackpot = jackpot + ". $game_cost ."
				WHERE game_id = '". $game_id ."'";
		$db->sql_query($q);
			}
		}

	function ResetJackpot($game_id)
		{
		global $db, $board_config;

	$q = "UPDATE ". iNA_GAMES ."
			SET jackpot = '". $board_config['ina_jackpot_pool'] ."'
			WHERE game_id = '". $game_id ."'";
	$db->sql_query($q);
		}

	function GameArrayLink($id, $parent, $popup, $win_width, $win_height, $type, $links)
		{
		global $lang, $userdata;

		$link = '';
		$switch = '';
		$switch = $type;
		if (eregi('%SEP%', $switch))
			$switch = explode('%SEP%', $switch);
		if ( ($parent) && ($switch == '1') )
			$link .= '<b></b>&nbsp;<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&parent=true') .'" class="nav">'. $lang['same_window'] .'</a><br />';
		if ( ($popup) && ($switch == '1') )
			$link .= '<b></b>&nbsp;<a class="nav" href="javascript:Gk_PopTart(\'activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;sid=' . $userdata['session_id'] .'\', \'New_Window\', \''. $win_width .'\', \''. $win_height .'\', \'no\')" onclick="blur()">'. $lang['new_window'] .'</a>';
		if ( ($parent) && ($switch == '2') )
			$link = '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'" class="nav">';
		if ( ($popup) && ($switch == '2') )
			$link = '<a class="nav" href="javascript:Gk_PopTart(\'activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;sid=' . $userdata['session_id'] .'\', \'New_Window\', \''. $win_width .'\', \''. $win_height .'\', \'no\')" onclick="blur()">';
		if ( ($popup) && ($parent) && ($switch == '2') )
			$link = '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'" class="nav">';
		if ( ($parent) && ($switch[0] == 3) )
			$link = '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'" class="nav">'. $switch[1] .'</a>';
		if ( ($popup) && ($switch[0] == 3) )
			$link = '<a class="nav" href="javascript:Gk_PopTart(\'activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;sid=' . $userdata['session_id'] .'\', \'New_Window\', \''. $win_width .'\', \''. $win_height .'\', \'no\')" onclick="blur()">'. $switch[1] .'</a>';
		if ( ($popup) && ($parent) && ($switch[0] == 3) )
			$link = '<a href="'. append_sid('activity.' . PHP_EXT . '?mode=game&amp;id='. $id .'&amp;parent=true') .'" class="nav">'. $switch[1] .'</a>';

		$any_links = explode(';', $links);
		for ($x = 0; $x < count($any_links); $x++)
			{
			if ($any_links[$x])
				{
			$split_link = explode(',', $any_links[$x]);
			$link .= '<br /><b></b>&nbsp;<a href="'. trim(rtrim($split_link[0])) .'" class="nav">'. trim(rtrim($split_link[1])) .'</a>';
				}
			}
		return $link;
		}

	function GameSingleLink($id, $parent, $popup, $win_width, $win_height, $page, $one, $two, $three, $links)
		{
		global $userdata;

		$link = '';
		if ( ($parent) && ($popup) )
		{
			$link = str_replace($one, '<a href="'. append_sid($page . PHP_EXT . '?mode=game&amp;id=' . $id . '&amp;parent=true') . '" class="nav">' . $two . '</a>', $three);
		}
		elseif (($parent) && (!$popup))
		{
			$link = str_replace($one, '<a href="'. append_sid($page . PHP_EXT . '?mode=game&amp;id=' . $id . '&amp;parent=true') . '" class="nav">' . $two . '</a>', $three);
		}
		else
		{
			$link = str_replace($one, '<a href="javascript:Gk_PopTart(\'' . $page . PHP_EXT . '?mode=game&amp;id=' . $id . '&amp;sid=' . $userdata['session_id'] .'\', \'New_Window\', \''. $width .'\', \''. $height .'\', \'no\')" class="nav">'. $two .'</a>', $three);
		}

		$any_links = explode(';', $links);
		for ($x = 0; $x < count($any_links); $x++)
		{
			if ($any_links[$x])
			{
				$split_link = explode(',', $any_links[$x]);
				$link .= '<br /><b>&middot;</b>&nbsp;<a href="' . trim(rtrim($split_link[0])) . '" class="nav">'. trim(rtrim($split_link[1])) .'</a>';
			}
		}

		return $link;
		}

	function GamesPassLength($page)
		{
		global $userdata, $board_config, $lang, $db;

		#==== Drop the users pass 1 day, every day they play.
		if (!$page)
			{
			if ( ($userdata['ina_games_pass_day'] != date('Y-m-d')) && ($userdata['ina_games_pass'] > 0) )
				{
			$q = "UPDATE ". USERS_TABLE ."
					SET ina_games_pass = ina_games_pass - 1, ina_games_pass_day = '". date('Y-m-d') ."'
					WHERE user_id = '". $userdata['user_id'] ."'";
			$db->sql_query($q);
				}
			}

		#==== The display on activity.php
		if ($page == 1)
			{
			#==== Is it active? Is points on?
			if ( ($board_config['ina_game_pass_cost']) && ($board_config['ina_game_pass_length']) && ($board_config['use_rewards_mod']) )
				{
			$user_pass = $userdata['ina_game_pass'];

			if ($board_config['use_point_system'])
				$points_cost = $board_config['ina_game_pass_cost'] .' '. $board_config['points_name'];

			if ($board_config['use_cash_system'])
				$points_cost = $board_config['ina_game_pass_cost'] .' '. $board_config['ina_cash_name'];

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

		$q = "UPDATE ". USERS_TABLE ."
				SET ina_time_playing = '". $final_entry ."'
				WHERE user_id = '". $userdata['user_id'] ."'";
		$db->sql_query($q);
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

		for ($c = 0; $c < count($cat_info); $c++)
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
		global $board_config, $lang, $userdata;

		unset($hof, $amod_stats, $char, $hof_link, $trophy_count, $trophy_holder, $trophy, $trophies, $show_trophies, $trophy_image);

		#==== Output The Hall Of Fame Link
		for ($hof = 0; $hof < count($hof_data); $hof++)
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
		if ( ($board_config['ina_show_view_topic']) && ($user_trophies > 0) && ($user_id != ANONYMOUS) )
			$trophies = "<a href=\"javascript:Trophy_Popup('activity_trophy_popup." . PHP_EXT . "?user=" . $user_id . '&amp;sid=' . $userdata['session_id'] ."','New_Window','400','380','yes')\" onclick=\"blur()\">" . $lang['Trohpy'] . "</a>:&nbsp;&nbsp;" . $user_trophies;

		#==== Output Character Link
		if ( ($board_config['ina_char_show_viewtopic']) && ($user_char) && ($user_id != ANONYMOUS) )
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
		global $board_config, $lang;
		unset($trophy_king, $trophy_image);
		$trophy_image = '<img src="'. IP_ROOT_PATH . ACTIVITY_MOD_PATH . 'images/trophy_king.gif" alt="'. $lang['trophy_holder_rank_name'] .'" title="'. $lang['trophy_holder_rank_name'] . '">';
		if ( ($board_config['ina_use_trophy']) && ($user_id == $board_config['ina_trophy_king']) )
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
		$display = sprintf($lang['game_info_display'], number_format($played) .' '. $plays, ($i_hours < 1) ? '' : (number_format($i_hours) .' '. $hours), ($i_minutes < 1) ? '' : (number_format($i_minutes) .' '. $mins), (number_format($i_seconds) .' '. $secs) );
		return '  '. $display;
	}

?>