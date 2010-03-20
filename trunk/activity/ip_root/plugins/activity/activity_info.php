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
	die('Hacking attempt');
}

$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_info_body.tpl');
$template->set_filenames(array('activity_info_section' => $template_to_parse));

$where_disabled = ($userdata['user_level'] == ADMIN) ? '' : "WHERE disabled > '0'" ;

/* Get last trophy game played */
$q = "SELECT a.*, b.username
			FROM ". INA_TROPHY ." a, ". USERS_TABLE ." b
		WHERE a.player = b.user_id
		ORDER BY a.date DESC
		LIMIT 1";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$game = $row['game_name'];
$name = $row['player'];
$when = $row['date'];
$score = $row['score'];
$trophy = $row['username'];

$trophy_link = ($name == ANONYMOUS) ? $lang['Guest'] : colorize_username($name);

for ($z = 0; $z < $games_c; $z++)
{
	if ($games_data[$z]['game_name'] == $game)
	{
		$t_game_id = $games_data[$z]['game_id'];
		$t_game_name = $games_data[$z]['proper_name'];
		$t_game_parent = $games_data[$z]['game_parent'];
		$t_game_popup = $games_data[$z]['game_popup'];
		$t_game_win = $games_data[$z]['win_width'];
		$t_game_height = $games_data[$z]['win_height'];
		break;
	}
}

/* Get total games played, most game played, least game played */
$q = "SELECT *
			FROM ". iNA_GAMES ."
		$where_disabled
		ORDER BY played DESC
		LIMIT 1";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$f_game_id = $row['game_id'];
$f_game_name = $row['proper_name'];
$f_game_played = $row['played'];
$f_game_parent = $row['game_parent'];
$f_game_popup = $row['game_popup'];
$f_game_win = $row['win_width'];
$f_game_height = $row['win_height'];

$q = "SELECT *
			FROM ". iNA_GAMES ."
		$where_disabled
		ORDER BY played ASC
		LIMIT 1";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$lf_game_id = $row['game_id'];
$lf_game_name = $row['proper_name'];
$lf_game_played = $row['played'];
$lf_game_parent = $row['game_parent'];
$lf_game_popup = $row['game_popup'];
$lf_game_win = $row['win_width'];
$lf_game_height = $row['win_height'];

$total_played = 0;
for ($z = 0; $z < $games_c; $z++)
	{
$total_played = $total_played + $games_data[$z]['played'];
	if (!$games_data[$z]['game_id'])
		break;
	}
$total = $total_played;

$total_left = 0;
for ($z = 0; $z < sizeof($comment_data); $z++)
	{
$total_left = $total_left + $comment_data[$z]['total_comments'];
	if (!$comment_data[$z]['game'])
		break;
	}
$total_comments = $total_left;
$total_games_available = $games_c;

$q = "SELECT COUNT(game_id) AS total_bets_made
			FROM ". $table_prefix ."ina_gamble";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$total_bets_made = $row['total_bets_made'];

$q = "SELECT SUM(count) AS total_challenges_sent
			FROM ". $table_prefix ."ina_challenge_users";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$total_challenges_sent = $row['total_challenges_sent'];

$totals_lines = $lang['info_box_total_game_played'] . ' ' . number_format($total) . '<br />' . $lang['info_box_total_comments'] . ' ' . number_format($total_comments) . '<br />' . $lang['info_box_total_challenges'] . ' ' . number_format($total_challenges_sent) . '<br />' . $lang['info_box_total_bets'] . ' ' . number_format($total_bets_made) . '<br />' . $lang['info_box_total_gaems'] . ' ' . number_format($total_games_available) .'<br />';
$trophy_game_3 = GameSingleLink($t_game_id, $t_game_parent, $t_game_popup, $t_game_win, $t_game_height, 'activity.', '%l%', $lang['info_box_link_here'], $lang['info_box_trophy_3'], '');

/* Info for the person with the most trophies */
	$q = "SELECT *
				FROM " . USERS_TABLE . "
				ORDER BY user_trophies DESC
				LIMIT 1";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	//$usersname = colorize_username($row['user_id']);
	$usersname = $row['username'];
	$usersid = $row['user_id'];
	$userstrophies = $row['user_trophies'];

if ($usersid != ANONYMOUS)
{
	$top_player1 = str_replace('%n%', colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']), $lang['info_box_top_trophy_holder1']);
}
else
{
	$top_player1 = str_replace('%n%', $usersname, $lang['info_box_top_trophy_holder1']);
}
$top_player2 = str_replace('%t%', '<a href="' . append_sid('activity.' . PHP_EXT . '?page=trophy_search&amp;user=' . urlencode($usersname)) . '">' . $userstrophies . '</a>', $top_player1);
$top_player = $top_player2;

/* Get all the info for the viewing user */
$cpu_id = $userdata['user_id'];
$cpu_username = colorize_username($userdata['user_id'], $userdata['username'], $userdata['user_color'], $userdata['user_active']);

$q = "SELECT *
			FROM ". INA_LAST_GAME ."
			WHERE user_id = '". $cpu_id ."'";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$exists = $row['user_id'];
$game = $row['game_id'];
$when = $row['date'];

if ($exists)
{
	for ($z = 0; $z < $games_c; $z++)
	{
		if ($games_data[$z]['game_id'] == $game)
		{
			$l_game_name = $games_data[$z]['proper_name'];
			$l_game_parent = $games_data[$z]['game_parent'];
			$l_game_popup = $games_data[$z]['game_popup'];
			$l_game_win = $games_data[$z]['win_width'];
			$l_game_height = $games_data[$z]['win_height'];
			$last = GameSingleLink($game, $l_game_parent, $l_game_popup, $l_game_win, $l_game_height, 'activity.', '%g%', $l_game_name, $lang['personal_info_last_game'], '');
			$last2 = str_replace('%d%', create_date($config['default_dateformat'], $when, $config['board_timezone']), $last);

			$template->assign_block_vars('personal_info_box', array(
				'LAST_GAME_PLAYED' => '<b>' . $lang['separator_2'] . '</b>&nbsp;'. $last2
				)
			);
		}
	}
}

$q = "SELECT COUNT(*) AS total
			FROM ". iNA_SCORES ."
		WHERE player = '". $userdata['username'] ."'";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$total_scores = $row['total'];
if (!$total_scores)
	$total_scores = '0';

$q = "SELECT SUM(count) AS total
			FROM ". INA_CHALLENGE_USERS ."
		WHERE user_from = '". $userdata['user_id'] ."'";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$total_challenges_sent = $row['total'];
if (!$total_challenges_sent)
	$total_challenges_sent = '0';

$q = "SELECT SUM(count) AS total
			FROM ". INA_CHALLENGE_USERS ."
		WHERE user_to = '". $userdata['user_id'] ."'";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$total_challenges_recieved = $row['total'];
if (!$total_challenges_recieved)
	$total_challenges_recieved = '0';

$q = "SELECT COUNT(player) AS total
			FROM ". INA_TROPHY_COMMENTS ."
		WHERE player = '". $userdata['user_id'] ."'";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$total_comments_left = $row['total'];
if (!$total_comments_left)
	$total_comments_left = '0';

if ($userdata['user_id'] != ANONYMOUS)
	$users_link = colorize_username($userdata['user_id'], $userdata['username'], $userdata['user_color'], $userdata['user_active']);
if ($userdata['user_id'] == ANONYMOUS)
	$users_link = $lang['Guest'];

if ($userdata['user_id'] != ANONYMOUS)
	$sent_link = '<b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%t%', '<a href="' . append_sid('activity.' . PHP_EXT . '?page=challenges&amp;mode=check_user&amp;' . POST_USERS_URL . '=' . $userdata['user_id']) . '" class="nav">' . $total_challenges_sent . '</a>', $lang['personal_info_challenges_1']);
if ($userdata['user_id'] == ANONYMOUS)
	$sent_link = '<b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%t%', $total_challenges_sent, $lang['personal_info_challenges_1']);

/* Select info for last game played */
$q = "SELECT a.game_id, a.user_id, a.date, b.username, b.user_active, b.user_color, c.proper_name, c.game_popup, c.game_parent, c.win_width, c.win_height
			FROM ". INA_LAST_GAME ." a, ". USERS_TABLE ." b, ". iNA_GAMES ." c
		WHERE a.user_id = b.user_id
		AND c.game_id = a.game_id
		ORDER BY a.date DESC
		LIMIT 1";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$newest_game = $row['game_id'];
$newest_user = $row['user_id'];
$newest_date = $row['date'];
$newest_name = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
$newest_game_name = $row['proper_name'];
$newest_game_parent = $row['game_parent'];
$newest_game_popup = $row['game_popup'];
$newest_game_win = $row['win_width'];
$newest_game_height = $row['win_height'];
$newlink = GameSingleLink($newest_game, $newest_game_parent, $newest_game_popup, $newest_game_win, $newest_game_height, 'activity.', '%g%', $newest_game_name, $lang['personal_info_newest_game1'], '');

if ($newest_user != ANONYMOUS)
{
	$newlink2 = str_replace('%u%', colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']), $newlink);
}
else
{
	$newlink2 = str_replace('%u%', $newest_name, $newlink);
}

$newest_game_played_link = $newlink2;
$newest_game_played_title = $lang['personal_info_newest_game'];
$lang_to_use = ($userdata['user_trophies'] == 1) ? $lang['personal_info_trophies_1'] : $lang['personal_info_trophies'];
$game_lang 	 = ($total_scores == 1) ? $lang['personal_info_game_played'] : $lang['personal_info_games_played'];

if ($userdata['user_trophies'] > 0)
	$users_trophies = '<b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%t%', '<a href="' . append_sid('activity.' . PHP_EXT . '?page=trophy_search&amp;user=' . urlencode($userdata['username'])) . '" class="nav">' . $userdata['user_trophies'] . '</a>', $lang_to_use);
if ($userdata['user_trophies'] < 1)
	$users_trophies = '<b>'. $lang['separator_2'] .'</b>&nbsp;'. str_replace('%t%', $userdata['user_trophies'], $lang_to_use);
if ($config['use_cash_system'] || $config['use_allowance_system'])
	$cash_fix = $config['default_reward_dbfield'];
if (($config['use_rewards_mod']) && ($config['use_point_system']))
	$onhand = '<b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%p%', number_format($userdata['user_points']) .' '. $config['points_name'], $lang['info_box_user_points']);
if (($config['use_rewards_mod']) && ($config['use_cash_system'] ||
	$config['use_allowance_system'])) $onhand = '<b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%p%', number_format($userdata[$cash_fix]) . ' ' . $config['ina_cash_name'], $lang['info_box_user_points']);
if (!$config['use_rewards_mod'])
	$onhand = '';

$shoutbox_link = ($config['ina_use_shoutbox']) ? '<br /><b>' . $lang['separator_2'] . '</b>&nbsp;<a href="' . append_sid('javascript:popup_open(\'activity_popup.' . PHP_EXT . '?mode=chat&amp;action=view\',\'New_Window\',\'550\',\'300\',\'yes\')') . '">' . $lang['shoutbox_link'] . '</a>' : '';

$template->assign_block_vars('info_box', array(
	'MOST_POPULAR_1' => $lang['info_box_popular_1'] . $f_game_name .'.',
	'MOST_POPULAR_2' => '&nbsp;&nbsp;'. str_replace('%g%', $f_game_played, $lang['info_box_popular_2']),
	'MOST_POPULAR_3' => '&nbsp;&nbsp;'. GameSingleLink($f_game_id, $f_game_parent, $f_game_popup, $f_game_win, $f_game_height, 'activity.', '%l%', $lang['info_box_link_here'], $lang['info_box_least_popular_3'], ''),
	'LEAST_POPULAR_1' => $lang['info_box_least_popular_1'] . $lf_game_name .".",
	'LEAST_POPULAR_2' => '&nbsp;&nbsp;'. str_replace('%g%', $lf_game_played, $lang['info_box_least_popular_2']),
	'LEAST_POPULAR_3' => '&nbsp;&nbsp;'. GameSingleLink($lf_game_id, $lf_game_parent, $lf_game_popup, $lf_game_win, $lf_game_height, 'activity.', '%l%', $lang['info_box_link_here'], $lang['info_box_least_popular_3'], ''),
	'TOTAL_GAMES_PLAYED' => $totals_lines,
	'TROPHY_GAME' => $trophy_link,
	'TROPHY_GAME_1' => $lang['info_box_trophy_1'],
	'TROPHY_GAME_2' => str_replace('%g%', $t_game_name, $lang['info_box_trophy_2']),
	'TROPHY_GAME_3' => $trophy_game_3,
	'TROPHY_TOP_HOLDER1' => '<br />' . $top_player,
	'TROPHY_TOP_HOLDER' => $lang['info_box_top_trophy_holder'],
	'USERNAME'	 => $users_link,
	'TOTAL_GAMES_LINK' => '<b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%t%', '<a href="'. append_sid('activity.' . PHP_EXT . '?page=high_scores&amp;mode=highscore&amp;player_search=' . urlencode($userdata['username'])) . '" class="nav">' . $total_scores . '</a>', $game_lang),
	'FAVORITES_LINK' => '<b>' . $lang['separator_2'] . '</b>&nbsp;<a href="activity_favs.' . PHP_EXT . '?sid=' . $userdata['session_id'] . '">'. $lang['favorites_info_link'] . '</a>' . $shoutbox_link,
	'TOTAL_CHALLENGES_SENT' => $sent_link,
	'TOTAL_CHALLENGES_RECIEVED' => '<b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%t%', $total_challenges_recieved, $lang['personal_info_challenges_2']),
	'TOTAL_COMMENTS_LEFT' => '<b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%t%', $total_comments_left, $lang['personal_info_comments']),
	'TOTAL_TROPHIES_HELD' => $users_trophies . '<br /><b>' . $lang['separator_2'] . '</b>&nbsp;' . str_replace('%T%', number_format($userdata['ina_char_ge']), $lang['info_box_user_ge_points']),
	'LAST_GAME_PLAYED' => $newest_game_played_link,
	'TOTAL_ONHAND_POINTS' => $onhand,
	'TOTAL_TIME_IN_GAMES' => '<b>' . $lang['separator_2'] . '</b>&nbsp;' . DisplayPlayingTime(1, $userdata['ina_time_playing']),

	'L_NEWEST_TITLE' => $newest_game_played_title,
	'L_INFO_TITLE' => $lang['info_box_title'],
	'L_INFO_TITLE1' => $lang['info_box_title1'],
	'L_INFO_TITLE2' => $lang['info_box_title2'],
	'L_INFO_TITLE3' => $lang['info_box_title3']
	)
);

$template->assign_var_from_handle('ACTIVITY_INFO_SECTION', 'activity_info_section');
?>