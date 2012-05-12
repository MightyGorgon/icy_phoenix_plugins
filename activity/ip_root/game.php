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

if ($user->data['user_session_page'] != ('activity.' . PHP_EXT))
{
	$sql = "UPDATE " . USERS_TABLE . "
			SET user_session_page = '" . ('activity.' . PHP_EXT) . "'
			WHERE user_id = " . $user->data['user_id'];
	$db->sql_query($sql);
}

CheckGamesPerDayMax($user->data['user_id'], $user->data['username']);

/* Start Restriction Checks */
BanCheck();
/* End Restriction Checks */

$game_id = request_var('id', 0);
$cheat_var = time();

$sql = "SELECT *
		FROM ". INA_CHEAT ."
		WHERE game_id = '". $game_id ."'
		AND player = " . $user->data['user_id'];
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
if(!$row['player'] || $row['game_id'] != $game_id)
{
	message_die(GENERAL_MESSAGE, $lang['no_game_start_error_1'], $lang['no_game_start_error_2']);
}


$sql = "SELECT *
		FROM ". iNA_GAMES ."
		WHERE game_id = '". $game_id ."'";
$result = $db->sql_query($sql);

$game_info = $db->sql_fetchrow($result);

#==== Start: Get highest/lowest score for playing user
$q = "SELECT score
		FROM ". iNA_SCORES ."
		WHERE player = '". addslashes(stripslashes($user->data['username'])) ."'
		AND game_name = '". $game_info['game_name'] ."'";
$q .= ($game_info['reverse_list']) ? "ORDER BY score ASC LIMIT 0, 1" : "ORDER BY score DESC LIMIT 0, 1";

$r = $db->sql_query($q);
$best_user_score = $db->sql_fetchrow($r);

$template->assign_vars(array(
	'BEST_USER_SCORE' => $lang['games_header_status'] .' '. $game_info['proper_name'] .': '. (($best_user_score['score'] > 0) ? FormatScores($best_user_score['score']) : '----')
	)
);
#==== End: Get highest/lowest score for playing user

AddJackpot($game_info['game_id'], $game_info['game_charge']);

if ($user->data['user_level'] <> ADMIN)
{
	if ($game_info['disabled'] <> 1)
	{
		redirect('activity.' . PHP_EXT, true);
	}
}

$game_name = $game_info['game_name'];
$proper_name = $game_info['proper_name'];
$game_width = $game_info['win_width'];
$game_height = $game_info['win_height'];
$game_path = ACTIVITY_GAMES_PATH . $game_info['game_path'];
$game_flash = $game_info['game_flash'];
$game_title = $config['sitename'] . $lang['game_dash'] . $lang['game_dash'] . $game_proper;
$game_reverse = $game_info['reverse_list'];
$game_proper = $game_info['proper_name'];
$game_type = $game_info['game_type'];

if ($user->data['user_level'] == ADMIN)
{
	$proper_name = '<a href="#" onclick="popup_open(\'' . ACTIVITY_ADM_PATH . '/admin_activity.' . PHP_EXT . '?mode=edit_games&amp;action=edit&amp;game=' . $game_info['game_id'] . '&amp;sid=' . $user->data['session_id'] . '\', \'New_Window\', \'550\', \'300\', \'yes\'); return false;">' . $game_info['proper_name'] . '</a>';
}
else
{
	$proper_name = $game_info['proper_name'];
}

/* Start Users Total Games Update */
UpdateUsersGames($user->data['user_id']);
/* End Users Total Games Update */

/* Start Insert For Play Type */
if (($game_flash) && ($_GET['parent']))
{
	$sql = "UPDATE ". USERS_TABLE ."
		SET ina_last_playtype = 'parent'
		WHERE user_id = '". $user->data['user_id'] ."'";
	$db->sql_query($sql);
}
elseif (($game_flash) && (!$_GET['parent']))
{
	$sql = "UPDATE ". USERS_TABLE ."
		SET ina_last_playtype = 'popup'
		WHERE user_id = '". $user->data['user_id'] ."'";
	$db->sql_query($sql);
}
else
{
	$sql = "UPDATE ". USERS_TABLE ."
		SET ina_last_playtype = 'parent'
		WHERE user_id = '". $user->data['user_id'] ."'";
	$db->sql_query($sql);
}
/* End Insert For Play Type */

#==== Handle No Scoring Games
if ($game_type == '2')
{
	$_GET['parent'] = '';
}

if (($game_flash) && (!$_GET['parent']))
{
	$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'flash_body.tpl');
	$template->set_filenames(array('body' => $template_to_parse));
	$template->assign_vars(array(
		'TITLE' => $game_title,
		'WIDTH' => $game_width,
		'HEIGHT' => $game_height,
		'SWFNAME' => $game_name . '.swf',
		'PATH' => $game_path
		)
	);
	$template->pparse('body');
}
elseif (($game_flash) && ($_GET['parent']))
{

	$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'flash_body2.tpl');

	$q = "SELECT *
			FROM ". INA_TROPHY ."
			WHERE game_name = '". $game_name ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$t_holder_id = $row['player'];
	$t_holder_sc = $row['score'];
	$t_holder_da = $row['date'];
	$trophy_score = FormatScores($t_holder_sc);
	$trophy_date = create_date_ip($config['default_dateformat'], $t_holder_da, $config['board_timezone']);

	$q1 = "SELECT username, user_active, user_color
			 FROM " . USERS_TABLE . "
			 WHERE user_id = '" . $t_holder_id . "'";
	$r1 = $db->sql_query($q1);
	$row = $db->sql_fetchrow($r1);
	$t_holder_name = $row['username'];
	$t_holder_link = colorize_username($t_holder_id, $row['username'], $row['user_color'], $row['user_active']);

	$template->assign_vars(array(
		'T_HOLDER' => $lang['trophy_holder'],
		'T_HOLDER_1'=> $t_holder_name,
		'T_DATE' => $trophy_date,
		'T_DATE_1' => $lang['trophy_held_since'],
		'T_SCORE' => $trophy_score,
		'T_SCORE_1' => $lang['score_to_beat'],
		'T_LINK' => $t_holder_name . '\'s ' . $t_holder_link,
		'T_LINK_1' => $t_holder_name . '\'s <a href="' . append_sid('activity.' . PHP_EXT . '?page=trophy_search&amp;user=' . urlencode($t_holder_name) . '&amp;sid=' . $user->data['session_id']) . '">'. $lang['game_profile'] .'</a>',
		'T_IMAGE' => ACTIVITY_IMAGES_PATH . 'trophy.gif',
		'R_TITLE' => $lang['top_ten'],

		'U_PLAY_POPUP' => '<a href="#" onclick="popup_open(\'activity.' . PHP_EXT . '?mode=game&amp;id=' . $game_info['game_id'] . '&amp;sid=' . $user->data['session_id'] . '\', \'New_Window\', \'550\', \'300\', \'yes\'); return false;">' . $lang['new_window'] . '</a>',

		'NAME' => $proper_name,
		'TITLE' => $game_title,
		'WIDTH' => $game_width,
		'HEIGHT' => $game_height,
		'SWFNAME' => $game_name .'.swf',
		'PATH' => $game_path
		)
	);

	if ($game_reverse == '1')
	{
		$order = 'ASC';
	}
	if ($game_reverse == '0')
	{
		$order = 'DESC';
	}

	$q2 = "SELECT *, MAX(score) AS hscore
				FROM ". iNA_SCORES ."
				WHERE game_name = '". $game_name ."'
				GROUP BY player
				ORDER BY score $order
				LIMIT 0,10";
	$r2 = $db->sql_query($q2);
	if ($row = $db->sql_fetchrow($r2))
	{
		$p = 1;
		do
		{
			$runner_up_name = $row['player'];
			$runner_up_score1 = $row['hscore'];
			$runner_up_score = FormatScores($runner_up_score1);

			$template->assign_block_vars('runner', array(
				'R_U_NAME' => $runner_up_name,
				'R_U_SCORE' => $runner_up_score
				)
			);
			$p++;
		}
		while ($row = $db->sql_fetchrow($r2));
	}

	full_page_generation($template_to_parse, '', '', '');
}
else
{
	$template->set_filenames(array('body' => $game_name . '_body.tpl'));

	$template->assign_vars(array(
		'USERNAME' => $user->data['username'],
		'PATH' => $game_path,
		'GAMELIB' => './' . $config['games_path'] . '/' . $config['gamelib_path'] . '/',
		'S_GAME_ACTION' => append_sid('newscore.' . PHP_EXT . '?mode=check_score&amp;game_name=' . urlencode($game_name))
		)
	);
	$template->pparse('body');
}

?>