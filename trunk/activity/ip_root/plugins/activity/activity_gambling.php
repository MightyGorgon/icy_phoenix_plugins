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

/* Start Version Check */
	VersionCheck();
/*  End Version Check */

/* Start Restriction Checks */
	BanCheck();
	/* Start File Specific Disable */
	if(($config['ina_disable_gamble_page']) && ($userdata['user_level'] != ADMIN)) message_die(GENERAL_ERROR, $lang['disabled_page_error'], $lang['ban_error']);
	/* End File Specific Disable */
/* End Restriction Checks */

	if($config['use_rewards_mod'])
		{
		if($config['use_point_system'])
			{
		include_once(IP_ROOT_PATH . 'includes/functions_points.' . PHP_EXT);
		$points_name = $config['points_name'];
			}
		if($config['use_cash_system'] || $config['use_allowance_system'])
			{
		include_once(IP_ROOT_PATH . 'includes/rewards_api.' . PHP_EXT);
		$points_name = $config['ina_cash_name'];
			}
		}

		$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_gambling_body.tpl');
		$template->set_filenames(array('body' => $template_to_parse));

		if($_GET['mode'] == 'stats')
		{
			$template->assign_block_vars('stats', array(
				'L_TITLE_1' => $lang['gambling_stats_title_1'],
				'L_TITLE_2' => $lang['gambling_stats_title_2'],
				'L_TITLE_3' => $lang['gambling_stats_title_3'],
				'L_TITLE_4' => $lang['gambling_stats_title_4'],
				'L_TITLE_5' => $lang['gambling_stats_title_5'],
				'L_TITLE_6' => $lang['gambling_stats_title_6']
				)
			);

		$i = 1;
		$q = "SELECT *
				FROM ". INA_GAMBLE ."
				WHERE winner_id > '1'
				AND loser_id > '1'
				ORDER BY date DESC";
		$r = $db->sql_query($q);
		while($row = $db->sql_fetchrow($r))
		{
			$game_id = $row['game_id'];
			$winner_id = $row['winner_id'];
			$winner_sc = $row['winner_score'];
			$loser_id = $row['loser_id'];
			$loser_sc = $row['loser_score'];
			$amount = $row['amount'];
			$date = $row['date'];
			$row_class = (!($i % 2)) ? 'row1' : 'row2';

			$q1 = "SELECT game_name, proper_name
					FROM ". iNA_GAMES ."
					WHERE game_id = '". $game_id ."'";
			$r1 = $db->sql_query($q1);
			$row = $db->sql_fetchrow($r1);
			$db->sql_freeresult($result);
			$game_name = $row['game_name'];
			$proper_name = $row['proper_name'];

			$q2 = "SELECT username, user_active, user_color
					FROM ". USERS_TABLE ."
					WHERE user_id = '" . $winner_id . "'";
			$r2 = $db->sql_query($q2);
			$row = $db->sql_fetchrow($r2);
			$db->sql_freeresult($result);
			$winner_name = colorize_username($winner_id, $row['username'], $row['user_color'], $row['user_active']);

			$q3 = "SELECT username, user_active, user_color
					FROM ". USERS_TABLE ."
					WHERE user_id = '". $loser_id ."'";
			$r3 = $db->sql_query($q3);
			$row = $db->sql_fetchrow($r3);
			$db->sql_freeresult($result);
			$loser_name = colorize_username($loser_id, $row['username'], $row['user_color'], $row['user_active']);

			$game_image = CheckGameImages($game_name, $proper_name);
			if($amount > 0)
			{
				$amount_bet = number_format($amount) ." ". $config['points_name'];
			}
			if($amount < 1)
			{
				$amount_bet = $lang['gambling_stats_for_fun'];
			}

			$template->assign_block_vars('stats_rows', array(
				'ROW_CLASS' => $row_class,
				'GAME_IMAGE' => $game_image,
				'GAME_NUMBER' => $i,
				'WINNER_LINK' => $winner_name . '<br />' . FormatScores($winner_sc),
				'LOSER_LINK' => $loser_name . '<br />' . FormatScores($loser_sc),
				'AMOUNT' => $amount_bet,
				'DATE' => create_date_ip($config['default_dateformat'], $date, $config['board_timezone'])
				)
			);
			$i++;
		}
	}

		if($_GET['mode'] == 'denybet')
		{
			$deny_id = $_GET['id'];
			$game_id = $_GET['game'];
			if($userdata['user_id'] != $deny_id)
			{
				message_die(GENERAL_ERROR, $lang['gambling_deny_error'], $lang['gambling_error']);
			}

			$q = "SELECT *
					FROM ". INA_GAMBLE_GAMES ."
					WHERE reciever_id = '". $userdata['user_id'] ."'
					AND game_id = '". $game_id ."'";
			$r = $db->sql_query($q);
			$row = $db->sql_fetchrow($r);
			$sender_id = $row['sender_id'];
			$game_id = $row['game_id'];

			$q = "SELECT game_name, proper_name
					FROM ". iNA_GAMES ."
					WHERE game_id = '". $game_id ."'";
			$r = $db->sql_query($q);
			$row = $db->sql_fetchrow($r);
			$game = $row['game_name'];
			$game2 = $row['proper_name'];

			$q = "DELETE FROM ". INA_GAMBLE ."
					WHERE reciever_id = '". $userdata['user_id'] ."'
					AND game_id = '". $game_id ."'";
			$r = $db->sql_query($q);

			$q = "DELETE FROM ". INA_GAMBLE_GAMES ."
					WHERE reciever_id = '". $userdata['user_id'] ."'
					AND game_id = '". $game_id ."'";
			$r = $db->sql_query($q);

		$new_msg1 = str_replace("%u%", $userdata['username'], $lang['gambling_deny_bet_sub']);
		$new_msg2 = str_replace("%g%", $game2, $new_msg1);
		send_challenge_pm($sender_id, $new_msg2, $new_msg2);
		message_die(GENERAL_MESSAGE, $lang['gambling_bet_denied_msg'], $lang['gambling_bet_denied']);
	}

		if($_GET['mode'] == "betting")
			{
		$switch = $_GET['user'];
			if($switch == "sender")
				{
			$sender_id = $_GET['id'];
			$game_id = $_GET['game'];

		$q = "SELECT *
				FROM ". INA_GAMBLE ."
				WHERE sender_id = '". $sender_id ."'
				AND game_id = '". $game_id ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$amount_bet = $row['amount'];
		if($config['use_point_system'] && $config['use_rewards_mod'])
			{
			if ($userdata['user_points'] < $amount_bet)
				{
			message_die(GENERAL_MESSAGE, $lang['not_enough_points'], '', __LINE__, __FILE__, $sql);
				}
			}
		if(($config['use_cash_system'] || $config['use_allowance_system']) && $config['use_rewards_mod'])
			{
			if (get_reward($userdata['user_id']) < $amount_bet)
				{
			message_die(GENERAL_MESSAGE, $lang['not_enough_reward'], '', __LINE__, __FILE__, $sql);
				}
			}

		$q = "SELECT *
				FROM ". INA_GAMBLE_GAMES ."
				WHERE sender_id = '". $sender_id ."'
				AND game_id = '". $game_id ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$sender_id = $row['sender_id'];
		$game_id = $row['game_id'];
		$sender_score = $row['sender_score'];

				if((!$sender_score) && ($sender_id))
					{
				$q = "UPDATE ". INA_GAMBLE_GAMES ."
						SET sender_playing = '1'
						WHERE sender_id = '". $sender_id ."'
						AND game_id = '". $game_id ."'";
				$r = $db->sql_query($q);

				echo
				'<script type="text/javascript">
				self.location.href=\'activity.' . PHP_EXT . '?mode=game&amp;id=' . $game_id . '&amp;parent=true\';
				</script>';
					}
				else
					{
				echo
				'<script type="text/javascript">
				self.location.href=\'activity.' . PHP_EXT . '\';
				</script>';
					}
				}
			if($switch == "receiver")
				{
			$receiver_id = $_GET['id'];
			$game_id = $_GET['game'];

		$q = "SELECT *
				FROM ". INA_GAMBLE ."
				WHERE reciever_id = '". $receiver_id ."'
				AND game_id = '". $game_id ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$amount_bet = $row['amount'];
		if($config['use_point_system'] && $config['use_rewards_mod'])
			{
			if ($userdata['user_points'] < $amount_bet)
				{
			message_die(GENERAL_MESSAGE, $lang['not_enough_points'], '', __LINE__, __FILE__, $sql);
				}
			}
		if(($config['use_cash_system'] || $config['use_allowance_system']) && $config['use_rewards_mod'])
			{
			if (get_reward($userdata['user_id']) < $amount_bet)
				{
			message_die(GENERAL_MESSAGE, $lang['not_enough_reward'], '', __LINE__, __FILE__, $sql);
				}
			}

		$q = "SELECT *
				FROM ". INA_GAMBLE_GAMES ."
				WHERE reciever_id = '". $receiver_id ."'
				AND game_id = '". $game_id ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$receiver_id = $row['reciever_id'];
		$game_id = $row['game_id'];
		$reciever_score = $row['reciever_score'];

				if((!$reciever_score) && ($receiver_id))
					{
				$q = "UPDATE ". INA_GAMBLE_GAMES ."
						SET reciever_playing = '1'
						WHERE reciever_id = '". $receiver_id ."'
						AND game_id = '". $game_id ."'";
				$r = $db->sql_query($q);

				echo
				'<script type="text/javascript">
				self.location.href=\'activity.' . PHP_EXT . '?mode=game&amp;id=' . $game_id . '&amp;parent=true\';
				</script>';
					}
				else
					{
				echo
				'<script type="text/javascript">
				self.location.href=\'activity.' . PHP_EXT . '\';
				</script>';
					}
				}
			}

	if ($_POST['mode'] == "submit_gamble")
		{
		if (intval($_POST['user_option_one']) > 1)
			$reciever_id = $_POST['user_option_one'];
		elseif ($_POST['user_option_two'])
			{
				$q = "SELECT user_id
						FROM ". USERS_TABLE ."
						WHERE username = '". addslashes(stripslashes($_POST['user_option_two'])) ."'";
				$r = $db->sql_query($q);
				$exists = $db->sql_fetchrow($r);
			if (!$exists['user_id'])
				message_die(GENERAL_ERROR, $lang['No_such_user'], $lang['error']);
			else
				$reciever_id = $exists['user_id'];
			}

	$game_id = $_POST['game_selected'];
	$sender_id = $userdata['user_id'];
	$free_fee = $_POST['bet_selection'];
	$amount = round($_POST['bet_amount']);

		/* Start all the possible screw ups */
	if($userdata['user_id'] == ANONYMOUS || $userdata['user_id'] == "") redirect('activity.' . PHP_EXT, true);
	if($amount > $config['ina_max_gamble']) message_die(GENERAL_ERROR, $lang['gambling_max_exceeded_error'], $lang['gambling_error']);
	if($reciever_id == $sender_id) message_die(GENERAL_ERROR, $lang['gambling_bet_self'], $lang['gambling_error']);
	if(!$free_fee) message_die(GENERAL_ERROR, $lang['gambling_no_fee_error'], $lang['gambling_error']);
	if(!$game_id) message_die(GENERAL_ERROR, $lang['gambling_no_game_selected'], $lang['gambling_error']);
	if(!$reciever_id) message_die(GENERAL_ERROR, $lang['gambling_no_user_selected'], $lang['gambling_error']);
	if(!is_numeric($amount)) message_die(GENERAL_ERROR, str_replace("%u%", $userdata['username'], $lang['gambling_numerical_error']), $lang['gambling_error']);
	if(($free_fee == "2") && ($userdata['user_id'] == ANONYMOUS)) message_die(GENERAL_ERROR, $lang['gambling_anonymous_error'], $lang['gambling_error']);
	if(($free_fee == "2") && (!$amount)) message_die(GENERAL_ERROR, str_replace("%u%", $userdata['username'], $lang['gambling_no_bet_error']), $lang['gambling_error']);
	if(($config['use_point_system']) && ($config['use_rewards_mod']) && ($free_fee == "2"))
		{
		if($userdata['user_points'] < $amount)
			{
		message_die(GENERAL_ERROR, str_replace("%u%", $userdata['username'], $lang['gambling_low_points']), $lang['gambling_error']);
			}
		}
	if(($config['use_cash_system'] || $config['use_allowance_system']) && ($config['use_rewards_mod']) && ($free_fee == "2"))
		{
		if(get_reward($userdata['user_id']) < $amount)
			{
		message_die(GENERAL_ERROR, str_replace("%u%", $userdata['username'], $lang['gambling_low_points']), $lang['gambling_error']);
			}
		}
		/* End all the possible screw ups */

	$q = "SELECT *
				FROM ". INA_GAMBLE_GAMES ."
			WHERE game_id = '". $game_id  ."'
			AND (sender_id = '". $userdata['user_id'] ."' OR
			reciever_id = '". $userdata['user_id'] ."')
			AND (sender_score = '' OR reciever_score = '')";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$exists = $row['sender_id'];
	if($exists) message_die(GENERAL_ERROR, $lang['gambling_in_progress_error'], $lang['gambling_error']);

	$q = "INSERT INTO ". INA_GAMBLE_GAMES ."
			VALUES ('". $game_id ."', '". $sender_id ."', '". $reciever_id ."', '', '', '', '')";
	$r = $db->sql_query($q);

	$q = "INSERT INTO ". INA_GAMBLE ."
			VALUES ('". $game_id ."', '". $sender_id ."', '". $reciever_id ."', '". $amount ."', '', '', '','','". time() ."', '')";
	$r = $db->sql_query($q);

	$q = "SELECT game_name, proper_name
				FROM ". iNA_GAMES ."
			WHERE game_id = '". $game_id ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$game = $row['game_name'];
	$game2 = $row['proper_name'];

	if($free_fee == '1') $amount_bet = $lang['gambling_no_bet_pm'];
	if($free_fee == '2') $amount_bet = $amount . $points_name;
	$senders_link = 'activity.' . PHP_EXT . '?page=gambling&amp;mode=betting&amp;user=sender&amp;id=' . $sender_id . '&amp;game=' . $game_id . ' ';
	$recievers_link_a = 'activity.' . PHP_EXT . '?page=gambling&amp;mode=betting&amp;user=receiver&amp;id=' . $reciever_id . '&amp;game=' . $game_id . ' ';
	$recievers_link_d = 'activity.' . PHP_EXT . '?page=gambling&amp;mode=denybet&amp;user=receiver&amp;id=' . $reciever_id . '&amp;game=' . $game_id . ' ';
	$sender_message = str_replace("%P%", "http://". $config['server_name'] . $config['script_path'] . $senders_link, $lang['gambling_pm_sender_msg']);
	$reciever_message1 = str_replace("%D%", "http://". $config['server_name'] . $config['script_path'] . $recievers_link_d, $lang['gambling_pm_reciever_msg']);
	$reciever_message2 = str_replace("%A%", "http://". $config['server_name'] . $config['script_path'] . $recievers_link_a, $reciever_message1);
	$reciever_message3 = str_replace("%u%", $userdata['username'], $reciever_message2);
	$reciever_message4 = str_replace("%g%", $game2, $reciever_message3);
	$reciever_message = str_replace("%C%", $amount_bet, $reciever_message4);

	send_challenge_pm($sender_id, $lang['gambling_pm_sender_sub'], $sender_message);
	send_challenge_pm($reciever_id, str_replace("%u%", $userdata['username'], $lang['gambling_pm_reciever_sub']), $reciever_message);

	message_die(GENERAL_MESSAGE, $lang['gambling_success_msg'], $lang['gambling_success']);
		}

	if ($_GET['mode'] == '')
	{
		if (!$config['use_rewards_mod'])
		{
			$points_disabled = '<i>('. $lang['gambling_points_disabled'] . ')</i>';
		}

		$template->assign_block_vars('user_selection', array(
			'L_USER_SELECTION_TITLE' => $lang['gambling_user_select_title'],
			'L_USER_SELECTION_DEFAULT' => $lang['gambling_default_user'],
			'L_TEXT_BOX_DEFAULT' => $lang['gambling_text_option_2'])
				);

		$template->assign_block_vars('links', array(
			'U_GAMBLING' => '<a href="activity.' . PHP_EXT . '?page=gambling' . '" class="nav">' . $lang['gambling_link_2'] . '</a>',
			'U_GAMBLING_2' => '<a href="activity.' . PHP_EXT . '?page=gambling&amp;mode=stats' . '" class="nav">' . $lang['gambling_link_3'] . '</a>',
			'U_ACTIVITY' => '<a href="activity.' . PHP_EXT . '" class="nav">' . $lang['gambling_link_1'] . '</a> '
			)
		);

		$template->assign_block_vars('game_selection', array(
			'L_GAME_RADIO' => $lang['gambling_game_choice'],
			'L_GAME_IMAGE' => $lang['gambling_game_image'],
			'L_GAME_DESC' => $lang['gambling_game_description'],
			'L_GAME_BET' => $lang['gambling_bet_amount'],
			'L_GAME_MAX' => $config['ina_max_gamble'])
				);

		$template->assign_block_vars('bet_selection', array(
			'L_BET_TITLE' => $lang['gambling_bet_choices'],
			'L_BET_FOR_FUN' => $lang['gambling_bet_choice_1'],
			'L_BET_FOR_FEE' => $lang['gambling_bet_choice_2'],
			'L_BET_DESC' => $lang['gambling_bet_choice_desc'],
			'L_MAX_BET_DESC' => str_replace("%a%", $config['ina_max_gamble'] ." ". $points_name, $lang['gambling_max_bet']),
			'L_GAME_SUBMIT' => $lang['gambling_select_button'],
			'L_POINTS_OFF' => $points_disabled,
			'L_SUBMIT_TITLE' => $lang['gambling_submit_title'])
				);

	$q = "SELECT user_id, username
				FROM ". USERS_TABLE ."
				WHERE user_id <> ". ANONYMOUS ."
				ORDER BY username ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
	{
		$id = $row['user_id'];
		$name = $row['username'];

		$template->assign_block_vars('user_selection_array', array(
			'USER_ID' => $id,
			'USERNAME' => $name
			)
		);
	}

	$i = 1;
	$admin_d = AdminDefaultOrder();

	$q = "SELECT *
				FROM ". iNA_GAMES ."
				WHERE game_id > '1'
				AND game_type <> '2'
				ORDER BY $admin_d";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
	{
		$game_name = $row['game_name'];
		$game_id = $row['game_id'];
		$game_desc = $row['game_desc'];
		$game_prop = $row['proper_name'];
		$game_image = CheckGameImages($game_name, $game_prop);

		$row_class = (!($i % 2)) ? 'row1' : 'row2';

		$template->assign_block_vars('game_selection_rows', array(
			'ROW_CLASS' => $row_class,
			'GAME_IMAGE' => $game_prop . '<br />' . $game_image,
			'GAME_DESC' => $game_desc,
			'GAME_ID' => $game_id,
			'GAME_NUMBERS' => $i
			)
		);
		$i++;
	}
}
$template->pparse('body');

/* Give credit where credit is due. */
echo ('
<script type="text/javascript">
function copyright()
{
	var popurl = \'' . ACTIVITY_PLUGIN_PATH . 'includes/functions_amod_plusC.' . PHP_EXT . '\'
	var winpops = window.open(popurl, "", "width=400, height=400,")
}
</script>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td align="left" valign="top">
<a style="text-decoration:none;" href="javascript:copyright();" class="gensmall">&copy; Activity Mod Plus</a>
</td>
</tr>
</table>
');
?>