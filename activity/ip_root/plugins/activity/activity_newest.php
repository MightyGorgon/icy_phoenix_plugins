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

$newest_count = ($user_use_newest_count > 0) ? $user_use_newest_count : $config['ina_new_game_count'];

$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_newest_body.tpl');
$template->set_filenames(array('activity_newest_section' => $template_to_parse));

$template->assign_block_vars('newest_only', array(
	'NEWEST_TITLE' => $lang['newest_title_1'] . $newest_count . $lang['newest_title_2'],
	'U_TROPHY'  => append_sid('activity_top_scores.' . PHP_EXT),
	'L_TROPHY' => $lang['trophy_page'],
	'L_STATS' => $lang['stats'],
	'L_COST' => $lang['cost'],
	'L_T_HOLDER' => $lang['trophy_holder'],
	'L_R_UP' => $lang['runner_up'],
	'L_GAMES' => $lang['game_list'],
	'L_SCORES' => $lang['game_score'],
	'L_INFO' => $lang['game_info'],
	'L_PLAYER' => $lang['game_best_player']
	)
);

$where_clause = ($user->data['user_level'] == ADMIN) ? '' : "WHERE disabled = '1'";

$q = "SELECT *
		FROM ". iNA_GAMES ."
		 $where_clause
		 ORDER BY install_date DESC
		 LIMIT 0, ". $newest_count;
$r = $db->sql_query($q);
$game_count = $db->sql_numrows($r);
$game_rows = $db->sql_fetchrowset($r);

for ($i = 0; $i < $game_count; $i++)
{
	$game_id = $game_rows[$i]['game_id'];
	$game_name = $game_rows[$i]['game_name'];
	$game_path = ACTIVITY_GAMES_PATH . $game_rows[$i]['game_path'];
	$game_desc = $game_rows[$i]['game_desc'];
	$win_width = $game_rows[$i]['win_width'];
	$win_height = $game_rows[$i]['win_height'];
	$game_fees = $game_rows[$i]['game_charge'];
	$game_played = $game_rows[$i]['played'];
	$game_date = $game_rows[$i]['install_date'];
	$game_proper = $game_rows[$i]['proper_name'];
	$game_popup = $game_rows[$i]['game_popup'];
	$game_parent = $game_rows[$i]['game_parent'];
	$game_type = $game_rows[$i]['game_type'];
	$game_ge_cost = $game_rows[$i]['game_ge_cost'];
	$game_mouse = $game_rows[$i]['game_mouse'];
	$game_keyboard = $game_rows[$i]['game_keyboard'];
	$game_cat = $game_rows[$i]['cat_id'];

	// Get Download Link From Array --------------------------------------- Dashe |
	unset($download_link);

	for ($j = 0; $j <= sizeof($download_data); $j++)
	{
		if (eregi('http://phpbb-amod.com/games/games/' . $game_rows[$i]['game_name'] . '.zip', $download_data[$j]["url"]))
		{
			$download_link = '<br />&nbsp;<a href="downloads.php?mode=download&amp;cid=910&amp;lid=' . $download_data[$j]['lid'] . '&amp;sid=' . $user->data['session_id'] . '" class="nav"><font color="#339933">Download This</font></a><br />';
			break;
		}
	}

	// Get Game Rating From Array ---------------------------------------- Dashe |
	unset($total_votes_given, $total_rating_given);

	for ($j = 0; $j <= sizeof($rating_data); $j++)
	{
		if ($game_rows[$i]['game_id'] == $rating_data[$j]['game_id'])
		{
			$total_votes_given = $rating_data[$j]['total_ratings'];
			$total_rating_given = $rating_data[$j]['game_rated'];
			break;
		}
	}

	// Get Game Comments From Array -------------------------------- Dashe |
	unset($total_comments);

	for ($j = 0; $j <= sizeof($comment_data); $j++)
	{
		if ($game_rows[$i]['game_name'] == $comment_data[$j]['game'])
		{
			$total_comments = $comment_data[$j]['total_comments'];
			break;
		}
	}
	if ($total_comments < 1)
	{
		$total_comments_shown = $lang['no_votes_cast'];
	}
	if ($total_comments)
	{
		$total_comments_shown = $total_comments;
	}

	// Get Favorites Data From Array ------------------------------- Dashe |
	unset($favorites_link);

	for ($j = 0; $j <= sizeof($favorites_data); $j++)
	{
		if (eregi(quotemeta("S". $game_rows[$i]['game_id'] ."E"), $favorites_data[$j]['games']))
		{
			$favorites_link = '<a href="activity_favs.' . PHP_EXT . '?mode=del_fav&amp;game=' . $game_id .'&amp;sid=' . $user->data['session_id'] . '"><img src="' . ACTIVITY_PLUGIN_PATH . 'images/r_favorite_game.jpg" alt="' . $lang['favorites_r_mouse_over'] . '" /></a>';
			break;
		}
		else
		{
			$favorites_link = '<a href="activity_favs.' . PHP_EXT . '?mode=add_fav&amp;game='. $game_id .'&amp;sid=' . $user->data['session_id'] . '"><img src="' . ACTIVITY_PLUGIN_PATH . 'images/favorite_game.jpg" alt="' . $lang['favorites_mouse_over'] . '" /></a>';
			break;
		}
	}

	// Get Rating Info From Array ---------------------------------------- |
	unset($game_rating_image, $rating_title, $rating_votes_cast, $rating_submit);
	for ($j = 0; $j <= $rating_count; $j++)
	{
		if ($game_rows[$i]['game_id'] == $rating_info[$j]['game_id'])
		{
			if ($rating_info[$j]['player'] == $user->data['user_id'])
			{
				$rating_submit = str_replace('%R%', $rating_info[$j]['rating'], $lang['rating_text_line']);
				break;
			}
			else
			{
				$rating_submit = '<a href="#" onclick="popup_open(\''. append_sid('activity_popup.' . PHP_EXT . '?mode=rate&amp;game=' . $game_rows[$i]['game_id']) . '\', \'New_Window\', \'450\', \'300\', \'yes\')' .'; return false;">'. $lang['game_rating_submit'] .'</a>';
			}
		}
	}

	if ($total_votes_given == 1)
	{
		$game_rating = round($total_rating_given / $total_votes_given);
		$rating_votes_cast = str_replace('%V%', $total_votes_given, $lang['game_rating_votes_one']);
		$game_rating_image = '<img src="' . ACTIVITY_IMAGES_PATH . 'ratings/' . $game_rating . '.gif" alt="'. $game_rating . '" />';
		$rating_title = $game_proper ."'s ". $lang['game_rating_title'];
	}
	elseif ($total_votes_given > 0)
	{
		$game_rating = round($total_rating_given / $total_votes_given);
		$rating_votes_cast = str_replace('%V%', $total_votes_given, $lang['game_rating_votes']);
		$game_rating_image = '<img src="' . ACTIVITY_IMAGES_PATH . 'ratings/'. $game_rating . '.gif" alt="'. $game_rating . '" />';
		$rating_title = $game_proper . '\'s ' . $lang['game_rating_title'];
	}
	else
	{
		$game_rating = 0;
		$rating_votes_cast = str_replace('%V%', $lang['no_votes_cast'], $lang['game_rating_votes']);
		$game_rating_image = '<img src="' . ACTIVITY_IMAGES_PATH . 'ratings/'. $game_rating . '.gif" alt="' . $game_rating . '" />';
		$rating_title = $game_proper ."'s ". $lang['game_rating_title'];
		$rating_submit = '<a href="#" onclick="popup_open(\''. append_sid('activity_popup.' . PHP_EXT . '?mode=rate&amp;game='. $game_rows[$i]['game_id']) . '\', \'New_Window\', \'450\', \'300\', \'yes\')' . '; return false;">' . $lang['game_rating_submit'] . '</a>';
	}

	//if ($config['allow_smilies']) $game_desc = smilies_pass($game_desc);
	global $bbcode;
	$html_on = ($user->data['user_allowhtml'] && $config['allow_html']) ? 1 : 0 ;
	$bbcode_on = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? 1 : 0 ;
	$smilies_on = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? 1 : 0 ;

	$bbcode->allow_html = $html_on;
	$bbcode->allow_bbcode = $bbcode_on;
	$bbcode->allow_smilies = $smilies_on;

	$game_desc = $bbcode->parse($game_desc);

	$new_image = ($game_date >= (time() - 86400 * $config['ina_new_game_limit'])) ? '<img src="' . ACTIVITY_IMAGES_PATH . 'new_game.gif" alt="" /><br />' : '';
	$popular_image = ($game_played >= $config['ina_pop_game_limit']) ? '<br /><img src="' . ACTIVITY_IMAGES_PATH . 'popular_game.jpg" alt="" />' : '';
	$list_type = ($game_rows[$i]['reverse_list']) ? 'ASC' : 'DESC';

	if ($config['use_rewards_mod'])
	{
		if ($game_rows[$i]['game_charge'])
		{
			$game_charge = $game_rows[$i]['game_charge'];
		}
		else
		{
			$game_charge = $lang['game_free'];
		}
	}
	else
	{
		$game_charge = $start + $i + 1;
	}

	// Get User Data From Array ------------------------------------------- |
	unset($top_player1, $t_player_id, $top_date, $top_score1, $top_score);
	for ($b = 0; $b <= $trophy_c; $b++)
	{
		if ($trophy_data[$b]['game_name'] == $game_name)
		{
			for ($c = 0; $c <= $user_c; $c++)
			{
				if ($trophy_data[$b]['player'] == $user_data[$c]['user_id'] && $trophy_data[$b]['game_name'] == $game_name)
				{
					$top_player1 = $trophy_data[$b]['player'];
					$t_player_id = $user_data[$c]['user_id'];
					$t_player_username = $user_data[$c]['username'];
					$t_player_user_active = $user_data[$c]['user_active'];
					$t_player_user_color = $user_data[$c]['user_color'];
					$top_player1 = colorize_username($user_data[$c]['user_id'], $user_data[$c]['username'], $user_data[$c]['user_color'], $user_data[$c]['user_active']);
					$top_score1 = $trophy_data[$b]['score'];
					$top_date = $trophy_data[$b]['date'];
					$top_date = create_date_ip($config['default_dateformat'], $top_date, $config['board_timezone']);
					$top_score = FormatScores($top_score1);
					break;
				}
			}
		}
	}

	if ($t_player_id != ANONYMOUS)
	{
		$top_player = colorize_username($t_player_id, $t_player_username, $t_player_user_color, $t_player_user_active);
	}
	else
	{
		$top_player = $lang['Guest'];
	}

	unset($best_score_a, $best_score1, $best_player1);
	for ($z = 0; $z < sizeof($scores_data); $z++)
	{
		if ($scores_data[$z]['player'] != $top_player1)
		{
			if ($scores_data[$z]['game_name'] == $game_name)
			{
				if (!$scores_data[$z]['player'] || $scores_data[$z]['player'] == $top_player1)
				{
					$best_score_a = $lang['best_player_default'];
					$best_score1 = 0;
					$best_player1 = $lang['best_player_default'];
				}
				$best_score_a = $scores_data[$z]['player'];
				$best_score1 = $scores_data[$z]['score'];
				$best_player1 = $scores_data[$z]['player'];

				if ($list_type == 'DESC')
				{
					break;
				}

				if(!$scores_data[$z]['player'])
				{
					break;
				}
			}
		}
	}

	// Get User Data From Array ------------------------------------------- |
	unset($b_player_id);
	for ($a = 0; $a <= $user_c; $a++)
	{
		if ($best_score_a == $user_data[$a]['username'])
		{
			$b_player_id = $user_data[$a]['user_id'];
			$b_player_username = $user_data[$a]['username'];
			$b_player_user_active = $user_data[$a]['user_active'];
			$b_player_user_color = $user_data[$a]['user_color'];
			break;
		}
	}

	$best_score = FormatScores($best_score1);

	if ($b_player_id != ANONYMOUS)
	{
		$best_player = colorize_username($b_player_id, $b_player_username, $b_player_user_color, $b_player_user_active);
	}
	else
	{
		$best_player = $lang['Guest'];
	}

	if ($game_rows[$i]['game_show_score'] != '1')
	{
		$highscore_link = '';
		$best_score = '';
		$best_player = $lang['best_player_default'];
	}
	else
	{
		$highscore_link = '<br />'. $lang['separator'] . '&nbsp;<a href="' . append_sid('activity.' . PHP_EXT . '?page=high_scores&amp;mode=highscore&amp;game_name=' . urlencode($game_name)) . '" class="nav">' . $lang['game_highscores'] . '</a>';
		$best_score = $best_score;
	}

	$row_class = (!($i % 2)) ? 'row1' : 'row2';

	if (strlen($best_player1) < 1 || $best_score < '1')
	{
		$best_player = $lang['best_player_default'];
	}

	if (($game_fees) && ($config['use_point_system']) && ($config['use_rewards_mod']))
	{
		$cost = $game_fees .' '. $config['points_name'];
	}
	elseif (($config['use_cash_system'] || $config['use_allowance_system']) && $config['use_rewards_mod'])
	{
		$cash_fix = "'" . $config['default_cash'] . "'";
		$cost = $game_fees .' '. $config[$cash_fix];
	}
	else
	{
		$cost = $lang['game_free'];
	}

	$game_link = CheckGameImages($game_name, $game_proper);
	$button_link = $config['ina_button_option'];
	if ($button_link == '1')
	{
		$image_link = GameArrayLink($game_id, $game_parent, $game_popup, $win_width, $win_height, 2, '');
	}
	else
	{
		$image_link = GameArrayLink($game_id, $game_parent, $game_popup, $win_width, $win_height, 2, '');
	}

	$challenge = $config['ina_challenge'];
	if (($challenge == '1') && ($t_player_id != ANONYMOUS) && ($user->data['user_id'] != ANONYMOUS))
	{
		$challenge_link = '<br />' . $lang['separator'] . '&nbsp;<a href="#" onclick="popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=challenge&amp;g='. $game_id . '&amp;' . POST_USERS_URL . '=' . $t_player_id) . '\', \'New_Window\', \'400\', \'200\', \'yes\')' . '; return false;">'. $lang['challenge_link_key'] . '</a>';
	}

	if ($challenge != '1' || $t_player_id == ANONYMOUS || $user->data['user_id'] == ANONYMOUS)
	{
		$challenge_link = '<br />'. $lang['separator'] .'&nbsp;'. $lang['challenge_link_key'];
	}

	if ($user->data['user_level'] == ADMIN)
	{
		$admin_edit = '<br />'. $lang['separator'] .'&nbsp;<a href="#" onclick="popup_open(\'' . ACTIVITY_ADM_PATH . 'admin_activity.' . PHP_EXT . '?mode=edit_games&amp;action=edit&amp;game=' . $game_id . '&amp;sid=' . $user->data['session_id'] .'\', \'New_Window\', \'550\', \'300\', \'yes\'); return false;">'. $lang['admin_edit_link'] .'</a>';
	}

	if ($user->data['user_level'] != ADMIN)
	{
		$admin_edit = '';
	}

	$games_cost_line = $show_fees = $show_ge = $show_jack = '';
	if ($game_fees)
	{
		$show_fees = '<br />'. $lang['separator'] .'&nbsp;'. $lang['cost'] .':&nbsp;'. $cost;
	}
	if ($game_ge_cost)
	{
		$show_ge = '<br />'. $lang['separator'] .'&nbsp;'. strip_tags($lang['ge_cost_per_game']) .':&nbsp;'. number_format($game_ge_cost);
	}
	if ($game_rows[$i]['jackpot'])
	{
		$show_jack = ($game_type != 2) ? '<br />'. $lang['separator'] . '&nbsp;' . str_replace('%X%', intval($game_rows[$i]['jackpot']), $lang['jackpot_text']) : '';
	}
	$games_cost_line = $show_fees . $show_ge . $show_jack;

	if (($config['ina_disable_comments_page']) && ($user->data['user_level'] != ADMIN))
	{
		$comments_link = '';
	}
	else
	{
		$comments_link = 'javascript:popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=comments&amp;game=' . $game_name) . '\',\'New_Window\',\'550\',\'300\',\'yes\')';
	}

	if ($game_type == 2)
	{
		$trophy_link = $top_player = $top_score = $top_date = $best_player = $best_score = $trophy_link = $download_link = $challenge_link = $highscore_link = '';
	}

	if ($game_cat > 0)
	{
		$game_category = Amod_Grab_Cat($game_cat, $category_data);
	}
	else
	{
		$game_category = $lang['game_rows_category_no'];
	}

	$template->assign_block_vars('newest', array(
		'RATING_TITLE' => $rating_title,
		'RATING_SUBMIT' => $rating_submit,
		'RATING_SENT' => $rating_votes_cast,
		'RATING_IMAGE' => $game_rating_image,
		'TOP_PLAYER' => $top_player,
		'POP_PIC' => $popular_image,
		'FAVORITE_GAME' => $favorites_link,
		'MOUSE' => (($game_mouse) ? '<img src="' . ACTIVITY_IMAGES_PATH . 'mouseamod.png" alt="'. $lang['game_mouse'] .'" title="'. $lang['game_mouse'] .'" /><br />' : ''),
		'KEYBOARD' => (($game_keyboard) ? '<img src="' . ACTIVITY_IMAGES_PATH . 'keyboard.png" alt="' . $lang['game_keyboard'] . '" title="' . $lang['game_keyboard'] . '" /><br />' : ''),
		'TOP_SCORE' => ($game_type != 2) ? $lang['score'] . $top_score : '',
		'TOP_DATE' => $top_date,
		'ROW_CLASS' => $row_class,
		'BEST_SCORE' => ($game_type != 2) ? $lang['score'] . $best_score : '',
		'BEST_PLAYER' => $best_player,
		'TROPHY_IMG' => ($game_type != 2) ? '<img src="' . ACTIVITY_IMAGES_PATH . 'trophy.gif" alt="" />' : '',
		'RUNNER_IMG' => ($game_type != 2) ? '<img src="' . ACTIVITY_IMAGES_PATH . 'trophy2.gif" alt="" />' : '',
		'DESC2' => $lang['new_description'],
		'GAMES_PLAYED' => $lang['separator'] .'&nbsp;'. $lang['new_games_played'],
		'I_PLAYED' => number_format($game_played) . $games_cost_line . $admin_edit,
		'SEPARATOR' => $lang['separator'] .'&nbsp;',
		'CHARGE' => $cost,
		'PROPER_NAME' => $game_proper,
		'IMAGE_LINK' => $game_link,
		'NEW_I_LINK' => $image_link,
		'NAME' => $game_name,
		'PATH' => $game_path,
		'DESC' => $game_desc,
		'INFO' => $lang['info'],
		'WIDTH' => $win_width,
		'HEIGHT' => $win_height,
		'STATS' => 'javascript:popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=info&amp;g=' . $game_id) . '\',\'New_Window\',\'400\',\'380\',\'yes\')',
		'COMMENTS' => $comments_link,
		'L_COMMENTS' => $total_comments_shown . '&nbsp;'. $lang['comments_link_key'],
		'CHALLENGE' => $challenge_link,
		'DASH' => $dash,
		'LIST' => $highscore_link,
		'DOWNLOAD_LINK' => $download_link,
		'LINKS' => GameArrayLink($game_id, $game_parent, $game_popup, $win_width, $win_height, 1, $game_rows[$i]['game_links']) . (($game_category) ? '<br /><span class="gensmall">&nbsp;'. $game_category .'</span>': '')
		)
	);
}

$template->assign_var_from_handle('ACTIVITY_NEWEST_SECTION', 'activity_newest_section');

?>