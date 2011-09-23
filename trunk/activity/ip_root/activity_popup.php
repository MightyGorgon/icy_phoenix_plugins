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
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

/* Start Version Check */
VersionCheck();
/* End Version Check */

$mode = ($_GET['mode']) ? $_GET['mode'] : $_GET['mode'];
if (!$mode)
{
	$mode = ($_POST['mode']) ? $_POST['mode'] : $_POST['mode'];
}

$action = ($_GET['action']) ? $_GET['action'] : $_GET['action'];
if (!$action)
{
	$action = ($_POST['action']) ? $_POST['action'] : $_POST['action'];
}

$user_id = $user->data['user_id'];
if (($config['ina_guest_play'] == '2') && !$user->data['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=activity.' . PHP_EXT, true));
	/*
	$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";
	header($header_location . append_sid(CMS_PAGE_LOGIN . '?redirect=activity.' . PHP_EXT, true));
	exit();
	*/
}

function CheckReturnPath($id)
{
	global $lang, $config, $user;

	if(($config['ina_use_rating_reward']) && ($config['ina_rating_reward'] > 0))
	{
		if($config['use_rewards_mod'])
		{
			if($config['use_point_system'])
			{
				include(IP_ROOT_PATH . 'includes/functions_points.' . PHP_EXT);
				$points_name = $config['points_name'];
				add_points($user->data['user_id'], $config['ina_rating_reward']);
				$msg = str_replace("%P%", $config['ina_rating_reward'] .'&nbsp;'. $points_name, $lang['rating_payout_message']);
			}
			if($config['use_cash_system'] || $config['use_allowance_system'])
			{
				include(IP_ROOT_PATH . 'includes/rewards_api.' . PHP_EXT);
				$points_name = $config['ina_cash_name'];
				add_reward($user->data['user_id'], $config['ina_rating_reward']);
				$msg = str_replace("%P%", $config['ina_rating_reward'] .'&nbsp;'. $points_name, $lang['rating_payout_message']);
			}
		}
	}

	if (!$id)
	{
		message_die(GENERAL_MESSAGE, $lang['rating_page_6'] .'<br />'. $msg, $lang['rating_page_success']);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['rating_page_6'] .'<br />'. $msg, $lang['rating_page_success']);
	}
}

if ($mode == 'chat')
{
	$meta_content['page_title'] = $lang['shoutbox_title'];
	$action = ($_GET['action'])? $_GET['action'] : $_GET['action'];
	if (!$action)
	{
		$action = ($_POST['action'])? $_POST['action'] : $_POST['action'];
	}

	$template->assign_block_vars('chat', array());

	if (!$config['ina_use_shoutbox'] || $user->data['user_id'] == ANONYMOUS)
	{
		message_die(GENERAL_ERROR, $lang['shoutbox_closed']);
	}

	if ($action == 'history')
	{
		$what_day = $_GET['history'];

		$q = "SELECT *
				FROM ". INA_CHAT ."
				WHERE chat_date = '". $what_day ."'";
		$r = $db->sql_query($q);
		$past_chat = $db->sql_fetchrow($r);
		$chat_session = $past_chat['chat_text'];

		$message = $chat_session;

		$message = censor_text($message);

		global $bbcode;
		$html_on = ($user->data['user_allowhtml'] && $config['allow_html']) ? 1 : 0 ;
		$bbcode_on = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? 1 : 0 ;
		$smilies_on = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? 1 : 0 ;

		$bbcode->allow_html = $html_on;
		$bbcode->allow_bbcode = $bbcode_on;
		$bbcode->allow_smilies = $smilies_on;

		$chat_session = $bbcode->parse($message);

		$start = str_replace('%S%', '<tr><td class="row2" colspan="2"><span class="genmed">', $chat_session);
		$end = str_replace('%E%', '</span></td></tr>', $start);
		$display = $end;

		$template->assign_block_vars('chat.history', array(
			'CHAT' => $display,
			'TITLE' => str_replace('%D%', $past_chat['chat_date'], $lang['shoutbox_header']),
			'REFRESH' => $lang['shoutbox_refresh']
			)
		);

		$q = "SELECT chat_date
				FROM ". INA_CHAT ."
				WHERE chat_date <> '". $what_day ."'";
		$r  = $db->sql_query($q);
		$n = $db->sql_numrows($r);
		while ($past_days = $db->sql_fetchrow($r))
		{
			if ($past_days['chat_date'])
			{
				$template->assign_block_vars('chat.history.dates', array(
					'HISTORY' => $past_days['chat_date']
					)
				);
			}
		}
		if ($n > 0)
		{
			$default = $lang['shoutbox_history'];
		}
		else
		{
			$default = $lang['shoutbox_no_history'];
		}

		$template->assign_vars(array(
			'DEFAULT' => $default
			)
		);
	}

	if ($action == 'add')
	{
		$q = "SELECT *
				FROM ". INA_CHAT ."
				WHERE chat_date = '". gmdate('y-m-d') ."'";
		$r = $db->sql_query($q);
		$todays_chat = $db->sql_fetchrow($r);
		$chat_session = $todays_chat['chat_text'];

		$q = "SELECT *
				FROM ". INA_SESSIONS ."
				WHERE playing_id = '". $user->data['user_id'] ."'";
		$r = $db->sql_query($q);
		$playing = $db->sql_fetchrow($r);
		$is_playing = $playing['playing_id'];
		$is_playing_g = $playing['playing'];
		$is_playing_t = $playing['playing_time'];

		include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
		$to_add = $_POST['msg'];
		$to_add = trim($to_add);
		# Make all necessary checks.

		if (empty($to_add))
		{
			message_die(GENERAL_ERROR, $lang['shoutbox_error']);
		}

		$message = $to_add;

		global $bbcode;
		$html_on = ($user->data['user_allowhtml'] && $config['allow_html']) ? 1 : 0 ;
		$bbcode_on = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? 1 : 0 ;
		$smilies_on = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? 1 : 0 ;

		$bbcode->allow_html = $html_on;
		$bbcode->allow_bbcode = $bbcode_on;
		$bbcode->allow_smilies = $smilies_on;
		$message = $bbcode->parse($message);
		$message = prepare_message(trim($to_add), $html_on, $bbcode_on, $smilies_on);

		#==== Same day chat or new days chat?
		if ($chat_session)
		{
			$message = addslashes(stripslashes($message));
			$message = str_replace('%S%', '%s%', $message);
			$message = str_replace('%E%', '%e%', $message);
			$new_session = '%S%[b]' . $user->data['username'] . '[/b]: ' . $message . '%E%';
			$new_session .= $chat_session;
			$new_session = addslashes(stripslashes($new_session));

			$q = "UPDATE ". INA_CHAT ."
				SET chat_text = '" . $new_session . "'
				WHERE chat_date = '" . gmdate('Y-m-d') . "'";
			$db->sql_query($q);
		}
		else
		{
			$message = addslashes(stripslashes($message));
			$message = str_replace('%S%', '%s%', $message);
			$message = str_replace('%E%', '%e%', $message);
			$new_session = '%S%[b]' . $user->data['username'] . '[/b]: ' . $message . '%E%';
			$new_session = addslashes(stripslashes($new_session));

			$q = "INSERT INTO ". INA_CHAT ."
				VALUES ('". gmdate('Y-m-d') ."', '". $new_session ."')";
			$db->sql_query($q);
		}

		#==== Reset users session to playing games if a ina session is there
		if ($is_playing)
		{
			$q = "UPDATE ". USERS_TABLE ."
					SET user_session_page = '". CMS_PAGE_ACTIVITY_GAME ."', ina_cheat_fix = '". $is_playing_g ."', playing_time = '". $is_playing_t ."'
					WHERE user_id = '". $user->data['user_id'] ."'";
			$db->sql_query($q);

			$q = "UPDATE ". SESSIONS_TABLE ."
					SET session_page = '". CMS_PAGE_ACTIVITY_GAME ."'
					WHERE session_user_id = '". $user->data['user_id'] ."'";
			$db->sql_query($q);
		}
	}

	if (($action == 'view' || $action == 'add') && ($action != 'history'))
	{
		$q = "SELECT *
				FROM ". INA_CHAT ."
				WHERE chat_date = '". gmdate('y-m-d') ."'";
		$r = $db->sql_query($q);
		$todays_chat = $db->sql_fetchrow($r);
		$chat_session = $todays_chat['chat_text'];

		$message = $chat_session;

		$message = censor_text($message);

		global $bbcode;
		$html_on = ($user->data['user_allowhtml'] && $config['allow_html']) ? 1 : 0 ;
		$bbcode_on = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? 1 : 0 ;
		$smilies_on = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? 1 : 0 ;

		$bbcode->allow_html = $html_on;
		$bbcode->allow_bbcode = $bbcode_on;
		$bbcode->allow_smilies = $smilies_on;

		$chat_session = $bbcode->parse($message);

		$start = str_replace('%S%', '<tr><td class="row2" colspan="2"><span class="genmed">', $chat_session);
		$end = str_replace('%E%', '</span></td></tr>', $start);
		$display = $end;

		$template->assign_block_vars('chat.view', array(
			'CHAT' => $display,
			'TITLE' => str_replace('%D%', $todays_chat['chat_date'], $lang['shoutbox_header']),
			'SUBMIT' => $lang['shoutbox_submit'],
			'REFRESH' => $lang['shoutbox_refresh']
			)
		);

		$q = "SELECT chat_date
				FROM ". INA_CHAT ."
				WHERE chat_date <> '". gmdate('Y-m-d') ."'";
		$r  = $db->sql_query($q);
		$n = $db->sql_numrows($r);
		while ($past_days = $db->sql_fetchrow($r))
		{
			if ($past_days['chat_date'])
			{
				$template->assign_block_vars('chat.view.history', array(
					'HISTORY' => $past_days['chat_date']
					)
				);
			}
		}
		if ($n > 0)
		{
			$default = $lang['shoutbox_history'];
		}
		else
		{
			$default = $lang['shoutbox_no_history'];
		}

		$template->assign_vars(array(
			'DEFAULT' => $default
			)
		);
	}
}

if ($mode == 'challenge')
{
	$who = ($_GET['u']) ? $_GET['u'] : $_GET['u'];
	$who_id = ($_GET['u']) ? $_GET['u'] : $_GET['u'];
	$game = ($_GET['g']) ? $_GET['g'] : $_GET['g'];

	if ($who == $user->data['user_id'])
	{
		exit(1);
	}

	if ($user->data['user_id'] == ANONYMOUS || $who == ANONYMOUS)
	{
		exit(2);
	}

	$meta_content['page_title'] = $lang['challenge_link_key'];
	$returned = ChallengeSelected($who, $game);
	$returned_data = explode('%RETURNED%', $returned);
	$who = $returned_data[0];
	$game = $returned_data[1];
	$message_sent = $lang['pm_challenge_msg'];
	$message_sent = $who .', '. $message_sent;
	$message_sent = str_replace('%n%', $user->data['username'], $message_sent);
	$message_sent = str_replace('%g%', $game, $message_sent);
	$top = $lang['pm_msg_top'];
	$middle = "<br />------------------------------------------------------------------<br />";
	$bottom = $lang['pm_msg_bottom'];
	send_challenge_pm($who_id, $lang['pm_challenge_sub'], $message_sent);

	$template->assign_block_vars('challenge', array(
		'MSG' => $top . $middle . $message_sent . $middle . $bottom,
		'TITLE' => $lang['challenge_information']
		)
	);
}

if ($mode == 'rate')
{
	$template->assign_block_vars('rate', array());
	if (!$action)
	{
		$cat_var_id = ($_GET['id']) ? $_GET['id'] : $_GET['id'];
		$game = ($_GET['game']) ? $_GET['game'] : $_GET['game'];

		if ($cat_var_id)
		{
			$cat_var = '?return=cat&id=' . $cat_var_id;
		}
		else
		{
			$cat_var = '';
		}

		if (!$game)
			message_die(GENERAL_MESSAGE, $lang['rating_page_1'], $lang['rating_page_error']);

		$q = "SELECT *
				FROM ". INA_RATINGS ."
				WHERE game_id = '". $game ."'
				AND player = '". $user->data['user_id'] ."'";
		$r = $db -> sql_query($q);
		$row = $db -> sql_fetchrow($r);

		if ($row['player'])
			message_die(GENERAL_MESSAGE, $lang['rating_page_error_exists'], $lang['rating_page_error']);

		$q = "SELECT *
				FROM ". iNA_GAMES ."
				WHERE game_id = '". $game ."'";
		$r = $db -> sql_query($q);
		$row = $db -> sql_fetchrow($r);

	$template->assign_block_vars('rate.main', array(
		'TITLE' => str_replace('%g%', $row['proper_name'], $lang['rating_page_3']),
		'CAT_RATE' => $cat_var,
		'DEFAULT_RATE' => $lang['rate_game_default'],
		'CHOICES' => $lang['rating_page_4'],
		'GAME' => $row['game_id'],
		'SUBMIT' => $lang['rating_page_5'])
			);
		}

	if($action == 'submit_rating')
		{
	$rating = ($_POST['rating']) ? $_POST['rating'] : $_POST['rating'];
	$game = ($_POST['game']) ? $_POST['game'] : $_POST['game'];

			$q = "SELECT *
					FROM ". iNA_GAMES ."
					WHERE game_id = '". $game ."'";
			$r = $db -> sql_query($q);
			$row = $db -> sql_fetchrow($r);

		if (!$rating)
			message_die(GENERAL_MESSAGE, str_replace("%G%", $row['proper_name'], $lang['rate_game_error']), $lang['error_message']);

			$q = "SELECT *
					FROM ". INA_RATINGS ."
					WHERE game_id = '". $game ."'
					AND player = '". $user->data['user_id'] ."'";
			$r = $db -> sql_query($q);
			$row = $db -> sql_fetchrow($r);
			if ($row['player'])
				message_die(GENERAL_ERROR, $lang['rating_page_error_exists'], $lang['rating_page_error']);


			if (!$rating || !$game)
				message_die(GENERAL_ERROR, $lang['rating_page_7'], $lang['rating_page_error']);

			$q = "INSERT INTO ". INA_RATINGS ."
					VALUES ('". $game ."', '". $rating ."', '". time() ."', '". $user->data['user_id'] ."')";
			$r = $db -> sql_query($q);

	CheckReturnPath($cat_var_id);
		}
	}

if ($mode == 'comments')
{
	if(($config['ina_disable_comments_page']) && ($user->data['user_level'] != ADMIN))
	{
		message_die(GENERAL_ERROR, $lang['disabled_page_error'], $lang['ban_error']);
	}

	$meta_content['page_title'] = $lang['comments_link_key'];
	$template->assign_block_vars('comments', array());
	$game_comment = ($_GET['game']) ? $_GET['game'] : $_GET['game'];
	$comment = ($_GET['user']) ? $_GET['user'] : $_GET['user'];
	$action = ($_GET['action']) ? $_GET['action'] : $_GET['action'];
	if (!$action)
	{
		$action = ($_POST['action']) ? $_POST['action'] : $_POST['action'];
	}

	if($action == 'posting_comment')
	{
		$comment_left = ($_POST['comment']) ? $_POST['comment'] : $_POST['comment'];
		$game_for_comment = ($_POST['comment_game_name']) ? $_POST['comment_game_name'] : $_POST['comment_game_name'];

		$q = "SELECT score
				FROM ". iNA_SCORES ."
				WHERE game_name = '". $game_for_comment ."'
				AND player = '". $user->data['username'] ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$score = $row['score'];

	if (strlen($comment_left) > 200)
	{
		$difference = strlen($comment_left) - 200;
		message_die(GENERAL_ERROR, $lang['trophy_comment_2'] . $difference . $lang['trophy_comment_3'], $lang['ban_error']);
	}

	if (strlen($comment_left) < 2)
	{
		message_die(GENERAL_ERROR, $lang['trophy_comment_4'], $lang['ban_error']);
	}

	$q = "SELECT * FROM " . WORDS_TABLE;
	if (!$r = $db -> sql_query($q))
	{
		message_die(GENERAL_ERROR, "Error Selecting Censored Word List.", "", __LINE__, __FILE__, $q);
	}

	while ($row = $db -> sql_fetchrow($r))
	{
		if (eregi(quotemeta($row['word']), $comment_left))
		{
			$comment_left = str_replace($row['word'], $row['replacement'], $comment_left);
		}
	}

	$comment_left = addslashes(stripslashes($comment_left));

	$sql = "INSERT INTO ". INA_TROPHY_COMMENTS ."
			VALUES ('" . $game_for_comment . "', '" . $user->data['user_id'] . "', '" . $comment_left . "', '" . time() . "', '" . $score . "')";
		if (!$result = $db -> sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Inserting Comment Information.", "", __LINE__, __FILE__, $sql);
		}

				redirect('activity_popup.' . PHP_EXT . '?mode=comments&game=' . $game_for_comment, true);
			}
		}

	if (($action == 'leave_comment') && ($comment > '0') && ($game_comment))
	{
		$game_link = CheckGameImages($game_comment, '');

		$template->assign_block_vars('comments.post_comment', array(
			'POST_TITLE' => $lang['trophy_comment_7'],
			'POST_LENGTH' => $lang['trophy_comment_8'],
			'POST_SUBMIT' => $lang['trophy_comment_9'],
			'POST_GAME' => $game_comment,
			'POST_LINK' => IP_ROOT_PATH . 'activity_popup.' . PHP_EXT .'?mode=comments',
			'POST_IMAGE' => $game_link
			)
		);
	}

	if ((!$action) && ($game_comment))
	{
		$check_comments = ($_GET['game']) ? $_GET['game'] : $_GET['game'];

#==== Trophy Holder ===================================== |
		$sql = "SELECT *
			FROM " . INA_TROPHY . "
			WHERE game_name = '" . $check_comments . "'";
		if (!$result = $db -> sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Retrieving Current Trophy Holder.", "", __LINE__, __FILE__, $sql);
		}

		$trophy_row = $db -> sql_fetchrow($result);
		$current_holder_id = $trophy_row['player'];
		$current_holder_date = $trophy_row['date'];
		$current_holder_score = $trophy_row['score'];

#==== Game Data ========================================= |
		$sql = "SELECT *
			FROM " . iNA_GAMES . "
			WHERE game_name = '" . $check_comments . "'";
		if (!$result = $db -> sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['no_game_data'], "", __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$game_link = $row['proper_name'];
		$game_image = CheckGameImages($check_comments, $row['proper_name']);

	if ($row['reverse_list'])
	{
		$list_type = 'ASC';
	}
	else
	{
		$list_type = 'DESC';
	}
	$db->sql_freeresult($result);

#==== Comments Array ==================================== |
	$sql = "SELECT *
		FROM ". INA_TROPHY_COMMENTS ."
		WHERE game = '" . $check_comments . "'
		ORDER BY score $list_type";
	if (!$result = $db -> sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error Selecting Comments.", "", __LINE__, __FILE__, $sql);
	}

	$trophy_comments = $db->sql_fetchrowset($result);
	$total_comments = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if (!$total_comments)
	{
		message_die(GENERAL_MESSAGE, $lang['trophy_comment_10'], $lang['trophy_comment_6']);
	}

#==== User Array ======================================== |
	$sql = "SELECT user_id, username
	FROM " . USERS_TABLE;
	if (!$result = $db -> sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error Selecting User Information.", "", __LINE__, __FILE__, $sql);
	}
	$users_data = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	for ($a = 0; $a < sizeof($users_data); $a++)
	{
		if ($current_holder_id == $users_data[$a]['user_id'])
		{
			$current_holder_name = $users_data[$a]['username'];
			break;
		}
	}

	for ($a = 0; $a < $total_comments; $a++)
	{
		if ($trophy_comments[$a]['player'] == $current_holder_id)
		{
			$current_holder_comment = htmlspecialchars($trophy_comments[$a]['comment']);
			break;
		}
	}

	$current_holder_score = ($user->data['user_level'] == ADMIN) ? '<a href="'. append_sid('activity_popup.' . PHP_EXT .'?mode=comments&amp;action=delete_comment&amp;game=' . $game_comment . '&amp;player=' . $current_holder_id) . '">' . FormatScores($current_holder_score) . '</a>' : FormatScores($current_holder_score);

	$template->assign_block_vars('comments.main', array(
		'MAIN_LEFT' => $lang['trophy_comment_11'],
		'MAIN_CENTER1' => $lang['trophy_comment_12'],
		'MAIN_CENTER2' => $lang['trophy_comment_13'],
		'MAIN_RIGHT' => $lang['trophy_comment_14'],
		'MAIN_IMAGE' => $game_image,
		'MAIN_NAME' => $game_link,
		'TROPHY_HOLDER' => colorize_username($current_holder_id),
		'TROPHY_DATE' => create_date($config['default_dateformat'], $current_holder_date, $config['board_timezone']),
		'TROPHY_SCORE' => $current_holder_score,
		'TROPHY_COMMENT' => $current_holder_comment
		)
	);

		$i = 0;
		$pos = 2;
		for ($a = 0; $a < $total_comments; $a++)
		{
			#==== Skip the trophy holder, as its already shown from above.
			if ((htmlspecialchars($trophy_comments[$a]['comment']) != $current_holder_comment) && ($trophy_comments[$a]['date'] != $current_holder_date))
				{
			$row_class = (!($i % 2)) ? 'row1' : 'row2';
			$comment_left_text = htmlspecialchars($trophy_comments[$a]['comment']);
			$comment_left_date = create_date($config['default_dateformat'], $trophy_comments[$a]['date'], $config['board_timezone']);
			$comment_left_score = FormatScores($trophy_comments[$a]['score']);
			$comment_left_id = $trophy_comments[$a]['player'];
			$i++;

				for ($b = 0; $b < sizeof($users_data); $b++)
				{
					if ($comment_left_id == $users_data[$b]['user_id'])
					{
						$comment_left_name = $users_data[$b]['username'];
						break;
					}
				}
			$comment_left_score = ($user->data['user_level'] == ADMIN) ? '<a href="' . append_sid('activity_popup.' . PHP_EXT . '?mode=comments&amp;action=delete_comment&amp;game=' . $game_comment . '&amp;player=' . $comment_left_id) . '">' . $comment_left_score . '</a>' : $comment_left_score;

			$template->assign_block_vars('comments.comment', array(
				'TROPHY_HOLDER' => '<a href="'. append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $comment_left_id) . '">' . $comment_left_name . '</a>',
				'TROPHY_DATE' => $comment_left_date,
				'TROPHY_SCORE' => $comment_left_score,
				'TROPHY_COMMENT' => $comment_left_text,
				'ROW' => $row_class,
				'POS' => $pos
				)
			);
			$pos++;
				}
			}
		}

if (($action == 'delete_comment') && ($user->data['user_level'] == ADMIN))
	{
	$g = ($_GET['game']) ? $_GET['game'] : $_GET['game'];
	$n = ($_GET['player']) ? $_GET['player'] : $_GET['player'];

			$q = "DELETE FROM ". INA_TROPHY_COMMENTS ."
						WHERE player = '". $n ."'
			AND game = '". $g ."'";
	if (!$r = $db -> sql_query($q))
		message_die(GENERAL_ERROR, "Error Deleting Comment.", "", __LINE__, __FILE__, $q);

		redirect('activity_popup.' . PHP_EXT . '?mode=comments&game='. $g, true);
	}

if ($mode == 'info')
	{
$game_id = (isset($_GET['g'])) ? intval($_GET['g']) : 0;

	$sql = "SELECT *
			FROM ". iNA_GAMES ."
			WHERE game_id = '". $game_id ."'";
	$result = $db->sql_query($sql);

	$game_info = $db->sql_fetchrow($result);

	$sql = "SELECT *
			FROM ". INA_CATEGORY ."
			WHERE cat_id = '". $game_info['cat_id'] ."'";
	$result = $db->sql_query($sql);
	$cat_info = $db->sql_fetchrow($result);

	if ($game_info['reverse_list'])
		$list_type = 'ASC';
	else
		$list_type = 'DESC';

	$sql = "SELECT *
			FROM ". INA_TROPHY ."
			WHERE game_name = '". $game_info['game_name'] ."'";
	$result = $db->sql_query($sql);
	$score_info = $db->sql_fetchrow($result);

	$best_score = $score_info['score'];
	$best_player = $score_info['player'];
	$meta_content['page_title'] = $game_info['proper_name'];

	$sql = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $best_player ."'";
	$result = $db->sql_query($sql);
	$user_info = $db->sql_fetchrow($result);

	$best_player = $user_info['username'];

	if ($game_info['game_charge'])
		$cost = $game_info['game_charge'] . '&nbsp;';
	else
		$cost = $lang['game_free'];

	if ($config['use_point_system'] && ($game_info['game_charge'] > 0))
		$cost .= $config['points_name'];

	if ($game_info['instructions'])
		$instructions = $game_info['instructions'];
	else
		$instructions = $lang['game_no_instructions'];

	$q = "SELECT MAX(date) AS last_date
			FROM ". iNA_SCORES ."
			WHERE game_name = '". $game_info['game_name'] ."'";
	$r = $db->sql_query($q);
	$date = $db->sql_fetchrow($r);


	$game_type = '';
	$game_type = ($game_info['game_type'] == 1) ? $lang['game_type_one'] : $game_type;
	$game_type = ($game_info['game_type'] == 2) ? $lang['game_type_two'] : $game_type;
	$game_type = ($game_info['game_type'] == 3) ? $lang['game_type_three'] : $game_type;
	$game_type = ($game_info['game_type'] == 4) ? $lang['game_type_four'] : $game_type;
	$borrowed = $game_info['played'] * $game_info['game_charge'];
	$game_date = create_date($config['default_dateformat'], $date['last_date'], $config['board_timezone']);

	$template->assign_block_vars('info', array(
		'L_TITLE' => $lang['info_page_title'],
		'L_TITLE_2' => $lang['info_page_title_2'],
		'L_PLAYED' => $lang['info_page_played'],
		'L_PLAYER' => ($game_info['game_type'] != 2) ? $lang['info_page_player'] : '',
		'L_COST' => $lang['info_page_cost'],
		'L_SCORE' => ($game_info['game_type'] != 2) ? $lang['info_page_score'] : '',
		'L_BONUS' => $lang['info_page_bonus'],
		'L_CATEGORY' => $lang['info_page_category'],
		'L_BORROWED' => $lang['info_page_borrowed'],
		'L_TYPE' => $lang['game_info_type'],
		'L_DATE' => $lang['game_info_date'],

		'DATE' => $game_date,
		'TYPE' => $game_type,
		'NAME' => $game_info['game_name'],
		'PATH' => ACTIVITY_GAMES_PATH . $game_info['game_path'],
		'DESC' => $game_info['game_desc'],
		'PLAYED' => number_format($game_info['played']),
		'COST' => number_format($cost),
		'BORROWED' => number_format($borrowed),
		'CATEGORY' => $cat_info['cat_name'],
		'BONUS' => $game_info['game_bonus'],
		'BEST_PLAYER' => ($game_info['game_type'] != 2) ? $best_player : '',
		'BEST_SCORE' => ($game_info['game_type'] != 2) ? FormatScores($best_score) : '',
		'INSTRUCTIONS' => $instructions)
		);
	}

$gen_simple_header = true;
$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_popup_body.tpl');
full_page_generation($template_to_parse, '', '', '');

?>