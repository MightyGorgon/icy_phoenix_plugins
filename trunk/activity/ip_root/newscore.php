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

define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

@include_once(ACTIVITY_ROOT_PATH . 'includes/functions_amod_newscore.' . PHP_EXT);
if($config['use_rewards_mod'])
{
	if($config['use_point_system'])
	{
		include(IP_ROOT_PATH . 'includes/functions_points.' . PHP_EXT);
	}
	if($config['use_cash_system'] || $config['use_allowance_system'])
	{
		include(ACTIVITY_ROOT_PATH . 'includes/rewards_api.' . PHP_EXT);
	}
}

#==== Start Disable Scores Check ==================== |
if (($config['ina_disable_submit_scores_m']) && ($userdata['user_id'] <> ANONYMOUS))
{
	message_die(GENERAL_MESSAGE, $lang['score_disable_message_m'], $lang['score_disable_info']);
}

if (($config['ina_disable_submit_scores_g']) && ($userdata['user_id'] == ANONYMOUS))
{
	message_die(GENERAL_MESSAGE, $lang['score_disable_message_g'], $lang['score_disable_info']);
}
#==== End Disable Scores Check ====================== |

#==== Start Restriction Check ======================= |
BanCheck();
#==== End Restriction Check ========================= |

#==== Start Deny $_GET Mode Games =================== |
if(($_GET['mode'] == 'check_score') || $_GET['score'] || $_GET['game_name'])
{
	message_die(GENERAL_MESSAGE, $lang['deny_GET_mode_games_1'], $lang['deny_GET_mode_games_2']);
}
#==== End Deny $_GET Mode Games ===================== |

$cheat_name = $userdata['username'];
$game_name = (($_POST['game_name'])) ? $_POST['game_name'] : $_POST['game_name'];
$score = (($_POST['score'])) ? $_POST['score'] : $_POST['score'];
$name = $userdata['username'];
$gen_simple_header = true;

Gamble($score, $userdata['user_id']);

$sql = "SELECT *
	FROM ". iNA_GAMES ."
	WHERE game_name = '". $game_name ."'";
$result = $db->sql_query($sql);
$game_info = $db->sql_fetchrow($result);

if (($score > '0') && ($name) && ($game_info['game_type'] != 2))
{
#==== Start Game Started Check ====================== |
	$sql = "SELECT *
			FROM ". INA_CHEAT ."
			WHERE game_id = '". $game_info['game_id'] ."'
			AND player = " . $userdata['user_id'];
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if(!$row['player'] || $row['game_id'] != $game_info['game_id'])
	{
		message_die(GENERAL_MESSAGE, $lang['no_game_start_error_1'], $lang['no_game_start_error_2']);
	}
	$sql = "DELETE FROM ". INA_CHEAT ."
			WHERE player = " . $userdata['user_id'];
	$db->sql_query($sql);
	RemovePlayingGame($userdata['user_id']);
#==== End Game Started Check========================= |

	Gamble($score, $game_info['game_id']);

	$sql = "SELECT *
		FROM ". iNA_SCORES ."
		WHERE game_name = '". $game_name ."'
		ORDER BY score DESC";
	$result = $db->sql_query($sql);
	$score_info = $db->sql_fetchrow($result);

#==== Start Bonus =================================== |
	$q = "SELECT *
			FROM ". $table_prefix ."ina_top_scores
			WHERE game_name = '". $game_name ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$trophy_score = $row['score'];
	$user_id = $userdata['user_id'];
	$bonus = 0;


	if (($game_info['reverse_list'] != '1') && ($score > $trophy_score))
	{
		$bonus = $game_info['game_bonus'];
	}
	elseif(($game_info['reverse_list'] == '1') && ($score < $trophy_score))
	{
		$bonus = $game_info['game_bonus'];
	}
	else
	{
		$bonus = '0';
	}

	if (($config['use_point_system']) && ($config['use_rewards_mod']))
	{
		if (($game_info['game_reward'] > 0) && ($bonus > 0))
		{
			$reward = (intval($score) / intval($game_info['game_reward']) + $bonus);
		}
		elseif (($game_info['game_reward'] > 0) && (!$bonus))
		{
			$reward = (intval($score) / intval($game_info['game_reward']));
		}
		elseif ((!$game_info['game_reward']) && ($bonus > 0))
		{
			$reward = $bonus;
		}
		else
		{
			$reward = '0';
		}
		add_points($user_id, $reward);
	}

	if (($config['use_cash_system'] || $config['use_allowance_system']) && $config['use_rewards_mod'])
	{
		if (($game_info['game_reward'] > 0) && ($bonus > 0))
		{
			$reward = (intval($score) / intval($game_info['game_reward']) + $bonus);
		}
		elseif (($game_info['game_reward'] > 0) && (!$bonus))
		{
			$reward = (intval($score) / intval($game_info['game_reward']));
		}
		elseif ((!$game_info['game_reward']) && ($bonus > 0))
		{
			$reward = $bonus;
		}
		else
		{
			$reward = '0';
		}
		add_reward($user_id, $reward);
	}
#==== End Bonus ===================================== |

#==== Start Trophies ================================ |
	$q = "SELECT *
			FROM ". $table_prefix ."ina_top_scores
			WHERE game_name = '". $game_name ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$old_score = $row['score'];
	$t_holder = $row['player'];
	$trophy_won = '';

	if (($game_info['reverse_list'] == "1") && ($score < $old_score))
	{
		$q = "SELECT proper_name
				FROM ". iNA_GAMES ."
				WHERE game_name = '". $game_name ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$proper_name = $row['proper_name'];

		$q = "SELECT user_id
				FROM ". USERS_TABLE ."
				WHERE username = '". $name ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$name_id = $row['user_id'];

		$q1 = "UPDATE ". $table_prefix ."ina_top_scores
				 SET player = '". $name_id ."', score = '". $score ."', date = '". time() ."'
				 WHERE game_name = '". $game_name ."'";
		$r1 = $db->sql_query($q1);

		$trophy_won = $lang['trophy_won_notice'];

		$message_sent = $lang['pm_trophy_msg'];
		$message_sent = str_replace('%s%', FormatScores($score), $message_sent);
		$message_sent = str_replace('%n%', $userdata['username'], $message_sent);
		$message_sent = str_replace('%g%', $proper_name, $message_sent);
		if ((!$config['ina_disable_comments_page']) && ($config['ina_pm_trophy'] == '1') && ($t_holder != "-1") && ($t_holder != $userdata['user_id']))
		{
			send_challenge_pm($t_holder, $lang['pm_trophy_sub'], $message_sent);
		}
	}

	if (($game_info['reverse_list'] == '0') && ($score > $old_score))
	{
		$q = "SELECT proper_name
				FROM ". iNA_GAMES ."
				WHERE game_name = '". $game_name ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$proper_name = $row['proper_name'];

		$q = "SELECT user_id
				FROM ". USERS_TABLE ."
				WHERE username = '". $name ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$name_id = $row['user_id'];

		$q1 = "UPDATE ". $table_prefix ."ina_top_scores
				 SET player = '". $name_id ."', score = '". $score ."', date = '". time() ."'
				 WHERE game_name = '". $game_name ."'";
		$r1 = $db->sql_query($q1);

		$trophy_won = $lang['trophy_won_notice'];

		$message_sent = $lang['pm_trophy_msg'];
		$message_sent = str_replace('%s%', FormatScores($score), $message_sent);
		$message_sent = str_replace('%n%', $userdata['username'], $message_sent);
		$message_sent = str_replace('%g%', $proper_name, $message_sent);
		if ((!$config['ina_disable_comments_page']) && ($config['ina_pm_trophy'] == '1') && ($t_holder != '-1') && ($t_holder != $userdata['user_id']))
		{
			send_challenge_pm($t_holder, $lang['pm_trophy_sub'], $message_sent);
		}
	}
#==== End Trophies ================================== |

#==== Start Jackpot ================================= |
	if ($trophy_won)
	{
		if (($config['use_point_system']) && ($config['use_rewards_mod']))
		{
			add_points($user_id, intval($game_info['jackpot']));
			ResetJackpot($game_info['game_id']);
		}

		if (($config['use_cash_system'] || $config['use_allowance_system']) && $config['use_rewards_mod'])
		{
			add_reward($user_id, intval($game_info['jackpot']));
			ResetJackpot($game_info['game_id']);
		}
	}
#==== End Jackpot =================================== |

#==== Start Comments ================================ |
	$template->assign_block_vars('comment', array(
		'COMMENT_LINK' => '<a href="#" onclick="popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=comments&amp;action=leave_comment&amp;user=' . $userdata['user_id'] . '&amp;game=' . $game_name) . '\', \'New_Window\', \'400\', \'300\', \'yes\')' . '; return false;">' . $lang['trophy_comment_notice'] . '</a>'
		)
	);
#==== End Comments ================================== |

#==== Start Hall Of Fame ============================ |
	HallOfFamePass($userdata['user_id'], $score, $game_info['game_id'], $game_info['reverse_list']);
#==== End Hall Of Fame ============================== |

#==== Start One Score Per User ====================== |
	$name = addslashes(stripslashes($userdata['username']));
	$q = "SELECT player, score
			FROM ". iNA_SCORES ."
			WHERE player = '". $name ."'
			AND game_name = '". $game_name ."'";
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);
	$exist	 = $row['player'];
	$e_score = $row['score'];

/* See if we have a score already & if its a higher score for this game */
	if (($exist) && ($game_info['reverse_list'] == '1') && ($score < $e_score))
	{
		$name = addslashes(stripslashes($userdata['username']));
		$sql = "UPDATE " . iNA_SCORES . "
				SET score = '". $score ."', date = '" . time() . "'
				WHERE player = '". $name ."' AND
				game_name = '". $game_name ."'";
		$result = $db->sql_query($sql);
		$msg = $lang['game_score_saved'];
	}
	elseif (($exist) && ($game_info['reverse_list'] == '0') && ($score > $e_score))
	{
		$name = addslashes(stripslashes($userdata['username']));
		$sql = "UPDATE " . iNA_SCORES . "
				SET score = '". $score ."', date = '" . time() . "'
				WHERE player = '".  $name ."' AND
				game_name = '". $game_name ."'";
		$result = $db->sql_query($sql);
		$msg = $lang['game_score_saved'];
	}
	/* See if we dont have a score for this game */
	elseif ((!$exist) && (!$e_score))
	{
		$name = addslashes(stripslashes($userdata['username']));
		$sql = "INSERT INTO " . iNA_SCORES . " (game_name, player, score, date)
				VALUES ('". $game_name ."', '". $name ."', '". $score ."', '" . time() . "')";
		$result = $db->sql_query($sql);
		$msg = $lang['game_score_saved'];
	}
	else
	{
		$msg = $lang['no_score_saved'];
	}

/* Add the total game plays & time playing this certain game, credits to JRSweets for making me do this! */
	$get_time  = explode(';;', $userdata['ina_time_playing']);
	$game_started = $get_time[0];
	$game_ended = time();
	$time_spent = ceil($game_ended - $game_started);

	$q = "UPDATE ". iNA_SCORES ."
			SET user_plays = user_plays + 1, play_time = play_time + " . $time_spent . "
			WHERE player = '" . $name . "'
			AND game_name = '" . $game_name . "'";
	$db->sql_query($q);
#==== End One Score Per User ======================== |

#==== Start GE Add ================================== |
	if ($trophy_won == $lang['trophy_won_notice'])
	{
		$trophy_GE = 1;
	}

	if ($msg == $lang['game_score_saved'])
	{
		$beat_score_GE = 1;
	}

	if ($userdata['ina_char_name'])
	{
		AMP_Add_GE($userdata['user_id'], $userdata['ina_char_ge'], $trophy_GE, $beat_score_GE);
	}
#==== End GE Add ==================================== |

#==== Start Get Previous Page ======================= |
	$q = "SELECT ina_last_visit_page
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $userdata['user_id'] ."'";
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);
	$last_page_viewed = $row['ina_last_visit_page'];
	if($last_page_viewed)
	{
		$return_page = 'activity.' . PHP_EXT . '?'. $last_page_viewed;
	}
	if(!$last_page_viewed)
	{
		$return_page = 'activity.' . PHP_EXT . '?sid=' . $userdata['session_id'];
	}
#==== End Get Previous Page ========================= |

	$play_again = str_replace("%G%", '<a href="activity.' . PHP_EXT . '?mode=game&amp;id=' . $game_info['game_id'] . '&amp;parent=true&amp;sid=' . $userdata['session_id'] . '">' . $game_info['proper_name'] . '</a>', $lang['play_again_link']);
	$msg_prt1 = str_replace("%G%", $game_info['proper_name'], $lang['score_on_newscore']);
	$msg_prt2 = str_replace("%S%", $score, $msg_prt1);
	$msg .= '<br />' . $msg_prt2;

#==== Start Favorites =============================== |
		$q = "SELECT games
				FROM ". INA_FAVORITES ."
				WHERE user = '". $userdata['user_id'] ."'";
		$r = $db -> sql_query($q);
		$row = $db -> sql_fetchrow($r);
		$fav_games = $row['games'];
		if (eregi(quotemeta("S" . $game_info['game_id'] . "E"), $fav_games))
		{
			$add_to_favs = $lang['saved_body_fav_no'];
		}
		else
		{
			$add_to_favs = '<a href="activity_favs.' . PHP_EXT . '?mode=add_fav&amp;game=' . $game_info['game_id'] . '&amp;sid=' . $userdata['session_id'] . '" target="_blank">'. $lang['saved_body_favs'] .'</a>';
		}
#==== End Favorites ================================= |

			$game_img = CheckGameImages($game_info['game_name'], $game_info['proper_name']);

#==== Setup Links Based On Gameplay================== |
	if ($userdata['ina_last_playtype'] == 'parent')
	{
		$link1 = $return_page;
		$lang1 = $lang['go_back_to_games'];
		$add_to_favs = '<a href="activity_favs.' . PHP_EXT . '?mode=add_fav&amp;game=' . $game_info['game_id'] . '&amp;sid=' . $userdata['session_id'] . '" target="_parent">' . $lang['saved_body_favs'] .'</a>';
	}
	elseif ($userdata['ina_last_playtype'] == 'popup')
	{
		$link1 = 'javascript:parent.window.close();';
		$lang1 = $lang['game_score_close'];
		$play_again = '';
		$add_to_favs = '<a href="activity_favs.' . PHP_EXT . '?mode=add_fav&amp;game='. $game_info['game_id'] . '&amp;sid=' . $userdata['session_id'] . '" target="_blank">' . $lang['saved_body_favs'] . '</a>';
	}
	else
	{
		$link1 = $return_page;
		$lang1 = $lang['go_back_to_games'];
	}

	$rate = '<a href="#" onclick="javascript:popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=rate&amp;game=' . $game_info['game_id']) .'\', \'New_Window\', \'450\', \'300\', \'yes\')' . '; return false;">' . $lang['saved_body_rate'] . '</a>';
	if (($config['ina_disable_comments_page']) && ($userdata['user_level'] != ADMIN))
	{
		$comms = '';
	}
	else
	{
		$comms = '<a href="#" onclick="popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=comments&amp;game=' . $game_info['game_name']) . '\', \'New_Window\', \'550\', \'300\', \'yes\')' . '; return false;">' . $lang['saved_body_comms'] . '</a>';
	}

	$template->assign_vars(array(
		'GAME_NAME' => $game_info['proper_name'],
		'GAME_IMAGE' => $game_img,
		'GAME_COMMS' => $comms,
		'GAME_RATE' => $rate,
		'GAME_FAV' => $add_to_favs,
		'TITLE' => $lang['saved_body_title'],
		'MSG' => $msg,
		'T_WON' => $trophy_won,
		'U_RETURN' => $link1,
		'U_AGAIN' => $play_again,
		'L_RETURN' => $lang1
		)
	);
}
else
{
#==== Start Comments ================================ |
	$template->assign_block_vars('comment', array(
		'COMMENT_LINK' => '<a href="#" onclick="popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=comments&amp;action=leave_comment&amp;user=' . $userdata['user_id'] . '&amp;game=' . $game_name) . '\', \'New_Window\', \'400\', \'300\', \'yes\')' . '; return false;">' . $lang['trophy_comment_notice'] . '</a>'
		)
	);
#==== End Comments ================================== |

#==== Start GE Add ================================== |
	if ($trophy_won == $lang['trophy_won_notice'])
	{
		$trophy_GE = 1;
	}

	if ($msg == $lang['game_score_saved'])
	{
		$beat_score_GE = 1;
	}

	if ($userdata['ina_char_name'])
	{
		AMP_Add_GE($userdata['user_id'], $userdata['ina_char_ge'], $trophy_GE, $beat_score_GE);
	}
#==== End GE Add ==================================== |

#==== Start Get Previous Page ======================= |
	$q = "SELECT ina_last_visit_page
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $userdata['user_id'] ."'";
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);
	$last_page_viewed = $row['ina_last_visit_page'];
	if ($last_page_viewed)
	{
		$return_page = $last_page_viewed;
	}
	if (!$last_page_viewed)
	{
		$return_page = 'activity.' . PHP_EXT . '?sid='. $userdata['session_id'];
	}
#==== End Get Previous Page ========================= |

	$play_again = str_replace("%G%", '<a href="activity.' . PHP_EXT . '?mode=game&amp;id=' . $game_info['game_id'] . '&amp;parent=true&amp;sid=' . $userdata['session_id'] . '">' . $game_info['proper_name'] . '</a>', $lang['play_again_link']);
	$msg_prt1 = str_replace("%G%", $game_info['proper_name'], $lang['score_on_newscore']);
	$msg_prt2 = str_replace("%S%", $score, $msg_prt1);
	$msg .= '<br />' . $msg_prt2;

#==== Start Favorites =============================== |
	$q = "SELECT games
			FROM ". INA_FAVORITES ."
			WHERE user = '". $userdata['user_id'] ."'";
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);
	$fav_games = $row['games'];
	if (eregi(quotemeta("S". $game_info['game_id'] ."E"), $fav_games))
	{
		$add_to_favs = $lang['saved_body_fav_no'];
	}
	else
	{
		$add_to_favs = '<a href="activity_favs.' . PHP_EXT . '?mode=add_fav&amp;game=' . $game_info['game_id'] . '&amp;sid=' . $userdata['session_id'] . '" class="mainmenu" target="_blank">' . $lang['saved_body_favs'] . '</a>';
	}
#==== End Favorites ================================= |

	$game_img = CheckGameImages($game_info['game_name'], $game_info['proper_name']);

#==== Setup Links Based On Gameplay================== |
	if ($userdata['ina_last_playtype'] == 'parent')
	{
		$link1 = $return_page;
		$lang1 = $lang['go_back_to_games'];
		$add_to_favs = '<a href="activity_favs.' . PHP_EXT . '?mode=add_fav&amp;game=' . $game_info['game_id'] . '&amp;sid=' . $userdata['session_id'] . '" class="mainmenu" target="_parent">' . $lang['saved_body_favs'] . '</a>';
	}
	elseif ($userdata['ina_last_playtype'] == 'popup')
	{
		$link1 = 'javascript:parent.window.close();';
		$lang1 = $lang['game_score_close'];
		$play_again = '';
		$add_to_favs = '<a href="activity_favs.' . PHP_EXT . '?mode=add_fav&amp;game=' . $game_info['game_id'] . '&amp;sid=' . $userdata['session_id'] . '" class="mainmenu" target="_blank">' . $lang['saved_body_favs'] . '</a>';
	}
	else
	{
		$link1 = $return_page;
		$lang1 = $lang['go_back_to_games'];
	}

	$rate = '<a href="#" onclick="popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=rate&amp;game=' . $game_info['game_id']) . '\', \'New_Window\', \'450\', \'300\', \'yes\')' . '; return false;">' . $lang['saved_body_rate'] . '</a>';
	if (($config['ina_disable_comments_page']) && ($userdata['user_level'] != ADMIN))
	{
		$comms = '';
	}
	else
	{
		$comms = '<a href="#" onclick="popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=comments&amp;game=' . $game_info['game_name']) . '\', \'New_Window\', \'550\', \'300\', \'yes\')' . '; return false;">' . $lang['saved_body_comms'] . '</a>';
	}

	$template->assign_vars(array(
		'GAME_NAME' => $game_info['proper_name'],
		'GAME_IMAGE' => $game_img,
		'GAME_COMMS' => $comms,
		'GAME_RATE' => $rate,
		'GAME_FAV' => $add_to_favs,
		'TITLE' => $lang['saved_body_title'],
		'MSG' => ($game_info['game_type'] == 2) ? $lang['game_type_message'] : $lang['no_score_saved'],
		'T_WON' => $trophy_won,
		'U_RETURN' => $link1,
		'U_AGAIN' => $play_again,
		'L_RETURN' => $lang1
		)
	);
}

UpdateTrophyStats();
CheckGamesDeletion();
TrophyKingRankCheck();
UpdateGamePlayTime(time(), $userdata['ina_time_playing']);

#==== Generate Page ================================= |
$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'saved_body.tpl');
full_page_generation($template_to_parse, '', '', '');

?>