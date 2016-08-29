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

define('ROBOTS_NOINDEX', true);
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
/*  End Version Check */

$nav_server_url = create_server_url();
$link_name = !empty($meta_content['page_title']) ? $meta_content['page_title'] : '';
$link_url = !empty($page_link) ? $page_link : '#';
$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('activity.' . PHP_EXT) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Activity'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="' . $link_url . '">' . $link_name . '</a>') : '');

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

UpdateSessions();
BanCheck();
UpdateActivitySession();

if ($config['use_rewards_mod'])
{
	if ($config['use_point_system'])
	{
		include_once(IP_ROOT_PATH . 'includes/functions_points.' . PHP_EXT);
	}

	if ($config['use_cash_system'] || $config['use_allowance_system'])
	{
		include_once(IP_ROOT_PATH . 'includes/rewards_api.' . PHP_EXT);
	}
}

if($_GET['mode'] != 'game')
{
	UpdateUsersPage($user->data['user_id'], $_SERVER['REQUEST_URI']);
}

if ($config['use_point_system'])
{
	$game_cost = $config['points_name'];
}
else
{
	$game_cost = $lang['game_cost'];
}

$order_by = AdminDefaultOrder();

if ($config['use_gamelib'] == 1)
{
	$gamelib_link = '<div align="center"><span class="copyright">' . $lang['game_lib_link'] . '</span></div>';
}

if ($config['use_gamelib'] == 0)
{
	$gamelib_link = '';
}

if($_GET['mode'] == 'add_fav')
{
	$game = $_GET['game'];

	$q = "SELECT *
			FROM ". iNA_GAMES ."
			WHERE game_id = '". $game ."'";
	$r = $db->sql_query($q);
	$game_check = $db->sql_fetchrow($r);
	if (!$game_check['game_id'])
	{
		message_die(GENERAL_ERROR, "That game does not exist in our database. Sorry.", "Error");
	}

	$q = "SELECT *
			FROM ". INA_FAVORITES ."
			WHERE user = '". $user_id ."'";
	$r = $db->sql_query($q);
	$favorite_data = $db->sql_fetchrowset($r);

	for ($i = 0; $i <= sizeof($favorite_data); $i++)
	{
		$games = $favorite_data[$i]["games"];
		if (eregi(quotemeta("S". $game ."E"), $games))
		{
			message_die(GENERAL_ERROR, "You already have that game added to your favorites.", "Error");
		}
	}

	$q = "SELECT *
			FROM ". INA_FAVORITES ."
			WHERE user = '". $user->data['user_id'] ."'";
	$r = $db->sql_query($q);
	$fav_data = $db->sql_fetchrow($r);

	$new_game_info = $fav_data['games'] ."S". $game ."E";
	if($fav_data['user'])
	{
		$q = "UPDATE ". INA_FAVORITES ."
				SET games = '". $new_game_info ."'
				WHERE user = '". $user_id ."'";
		$r = $db->sql_query($q);
	}
	else
	{
		$q = "INSERT INTO ". INA_FAVORITES ."
				VALUES ('". $user_id ."', 'S". $game ."E')";
		$r = $db->sql_query($q);
	}
	message_die(GENERAL_MESSAGE, 'Game added to your favorites list!<br /><br />Click <a href="activity_favs.' . PHP_EXT . '?sid=' . $user->data['session_id'] . '" class="nav"><i>here</i></a> to view your favorites.', 'Success');
}
elseif($_GET['mode'] == 'del_fav')
{
	$game = $_GET['game'];

	$q = "SELECT *
			FROM ". iNA_GAMES ."
			WHERE game_id = '". $game ."'";
	$r = $db->sql_query($q);
	$game_check = $db->sql_fetchrow($r);
	if(!$game_check['game_id'])
	{
		message_die(GENERAL_ERROR, "That game does not exist in our database. Sorry.", "Error");
	}

	$q = "SELECT *
			FROM ". INA_FAVORITES ."
			WHERE user = '". $user_id ."'";
	$r = $db->sql_query($q);
	$favorite_data = $db->sql_fetchrowset($r);

	for($i = 0; $i <= sizeof($favorite_data); $i++)
	{
		$games = $favorite_data[$i]["games"];

		if(eregi(quotemeta("S". $game ."E"), $games))
		{
			$new_list = str_replace("S". $game ."E", "", $games);
			$q = "UPDATE ". INA_FAVORITES ."
					SET games = '". $new_list ."'
					WHERE user = '". $user_id ."'";
			$r = $db->sql_query($q);
			message_die(GENERAL_MESSAGE, 'Game Removed<br /><br />Click <a href="activity_favs.' . PHP_EXT . '?sid=' . $user->data['session_id'] . '" class="nav"><i>here</i></a> to view your favorites..', 'Success');
		}
		else
		{
			message_die(GENERAL_ERROR, 'That Game Is Not On Your Favorites List.<br /><br />Click <a href="activity_favs.' . PHP_EXT . '?sid=' . $user->data['session_id'] . '" class="nav"><i>here</i></a> to view your favorites.', 'Error');
		}
	}
}
else
{
	// Setup Trophy Array --------------------------------------------------------------- |
	$q3 = "SELECT *
			FROM ". INA_TROPHY ."";
	$r3 = $db->sql_query($q3);
	$trophy_data = $db->sql_fetchrowset($r3);
	$trophy_c	 = $db->sql_numrows($r3);

	// Setup Users Array ---------------------------------------------------------------- |
	$q3 = "SELECT user_id, username
			FROM ". USERS_TABLE ."";
	$r3 = $db->sql_query($q3);
	$user_data = $db->sql_fetchrowset($r3);
	$user_c	 = $db->sql_numrows($r3);

	// Setup Favorites Array ------------------------------------------------------------ |
	$q = "SELECT *
			FROM ". INA_FAVORITES ."
			WHERE user = '". $user->data['user_id'] ."'";
	$r = $db->sql_query($q);
	$favorite_data = $db->sql_fetchrowset($r);
	$fav_c = $db->sql_numrows($r);

	// Setup Games Array ---------------------------------------------------------------- |
	$q = "SELECT *
			FROM ". iNA_GAMES ."";
	$r = $db->sql_query($q);
	$games_data = $db->sql_fetchrowset($r);

	if ($config['use_point_system'] && $config['use_rewards_mod'])
	{
		$template->assign_vars(array("L_MONEY" => $config['points_name']));
	}
	elseif (($config['use_cash_system'] || $config['use_allowance_system']) && $config['use_rewards_mod'])
	{
		$template->assign_vars(array("L_MONEY" => $lang['game_cost']));
	}
	else
	{
		$template->assign_vars(array("L_MONEY" => $lang['game_number']));
	}

	$template->assign_vars(array(
		'CHALLENGE_LINK' => $lang['challenge_Link'],
		'TOP_FIVE_LINK' => $lang['top_five_10'],

		'C_DEFAULT_ALL' => append_sid('activity.' . PHP_EXT),
		'C_DEFAULT_ALL_L' => $lang['category_default_2'],
		'C_CAT_PAGE' => append_sid('activity.' . PHP_EXT . '?mode=category_play'),
		'GAMELIB_LINK' => $gamelib_link,
		'U_TROPHY' => append_sid("activity_top_scores." . PHP_EXT),
		'U_GAMBLING' => '<a href="activity_gambling.' . PHP_EXT . '?sid=' . $user->data['session_id'] . '" class="nav">' . $lang['gambling_link_2'] . '</a>',

		'L_TROPHY' => $lang['trophy_page'],
		'L_STATS' => $lang['stats'],
		'L_COST' => $lang['cost'],
		'L_T_HOLDER' => $lang['trophy_holder'],
		'L_GAMES' => $lang['game_list'],
		'L_SCORES' => $lang['game_score'],
		'L_INFO' => $lang['game_info']
		)
	);

	$p = 1;
	for($i = 0; $i <= $fav_c; $i++)
	{
		$games = $favorite_data[$i]["games"];

		for($j = 0; $j <= sizeof($games_data); $j++)
		{
			if(eregi(quotemeta("S" . $games_data[$j]["game_id"] . "E"), $games))
			{
				$fav_game_id = $games_data[$j]["game_id"];
				$fav_game_name = $games_data[$j]["game_name"];
				$fav_game_path = ACTIVITY_GAMES_PATH . $games_data[$j]["game_path"];
				$fav_game_desc = $games_data[$j]["game_desc"];
				$fav_win_width = $games_data[$j]["win_width"];
				$fav_win_height = $games_data[$j]["win_height"];
				$fav_game_fees = $games_data[$j]["game_charge"];
				$fav_game_played = $games_data[$j]["played"];
				$fav_game_date = $games_data[$j]["install_date"];
				$fav_game_proper = $games_data[$j]["proper_name"];
				$fav_game_order = $games_data[$j]["reverse_list"];
				$fav_game_popup = $games_data[$j]['game_popup'];
				$fav_game_parent = $games_data[$j]['game_parent'];
				$fav_game_ge_cost = $games_data[$j]['game_ge_cost'];

				if (!$fav_game_id)
				{
					message_die(GENERAL_ERROR, $lang['favorites_none_error_1'], $lang['favorites_none_error_2']);
				}

				global $bbcode;
				$html_on = ($user->data['user_allowhtml'] && $config['allow_html']) ? 1 : 0 ;
				$bbcode_on = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? 1 : 0 ;
				$smilies_on = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? 1 : 0 ;

				$bbcode->allow_html = $html_on;
				$bbcode->allow_bbcode = $bbcode_on;
				$bbcode->allow_smilies = $smilies_on;

				$game_desc = $bbcode->parse($game_desc, '');

				$new_image = '';
				if ($fav_game_date > (time() - 86400 * $config['ina_new_game_limit']))
				{
					$new_image = '<img src="' . ACTIVITY_IMAGES_PATH . 'new_game.gif" alt="" /><br />';
				}

				$popular_image = '';
				if ($fav_game_played > $config['ina_pop_game_limit'])
				{
					$popular_image = '<br /><img src="' . ACTIVITY_IMAGES_PATH . 'popular_game.jpg" alt="" />';
				}

				if ($config['use_rewards_mod'])
				{
					if ($games_data[$j]['game_charge'])
					{
						$game_charge = $games_data[$j]['game_charge'];
					}
					else
					{
						$game_charge = $lang['game_free'];
					}
				}

				// Get User Data From Array ------------------------------------------- |
				unset($top_player1, $t_player_id, $top_date, $top_score1, $top_score);

				for($b = 0; $b <= $trophy_c; $b++)
				{
					if ($trophy_data[$b]['game_name'] == $fav_game_name)
					{
						for($c = 0; $c <= $user_c; $c++)
						{
							if($trophy_data[$b]['player'] == $user_data[$c]['user_id'] && $trophy_data[$b]['game_name'] == $fav_game_name)
							{
								$top_player1 = $trophy_data[$b]['player'];
								$t_player_id = $user_data[$c]['user_id'];
								$top_player1 = $user_data[$c]['username'];
								$top_score1 = $trophy_data[$b]['score'];
								$top_date = $trophy_data[$b]['date'];
								$top_date = create_date($config['default_dateformat'], $top_date, $config['board_timezone']);
								$top_score = FormatScores($top_score1);
								break;
							}
						}
					}
				}

				if ($top_player1 == "Anonymous")
				{
					$top_player = "Anonymous";
				}
				if (($top_player1 <> "Anonymous") && (strlen($top_player1) > 1))
				{
					$top_player = '<a href="'. append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $t_player_id) . '" class="nav">' . $top_player1 . '</a>';
				}

				if ($games_data[$j]['game_show_score'] != '1')
				{
					$highscore_link = '';
					$best_score = '';
					$best_player = $lang['best_player_default'];
				}
				else
				{
					$highscore_link = '<a href="' . append_sid('activity.' . PHP_EXT . '?page=high_scores&amp;mode=highscore&amp;game_name=' . urlencode($fav_game_name)) . '" class="nav"> ' . $lang['game_highscores'] . '</a>';
					$best_score = $best_score;
				}

				if (strlen($best_player) < 1 || $best_score < '1')
				{
					$best_player = $lang['best_player_default'];
				}

				if (($fav_game_fees) && ($config['use_point_system']) && ($config['use_rewards_mod']))
				{
					$cost = $fav_game_fees ." ". $config['points_name'];
				}
				elseif (($config['use_cash_system'] || $config['use_allowance_system']) && $config['use_rewards_mod'])
				{
					$cash_fix = "'". $config['default_cash'] ."'";
					$cost = $fav_game_fees ." ". $config[$cash_fix];
				}
				else
				{
					$cost = $lang['game_free'];
				}

				$remove_link = '<br /><br /><center><a href="activity_favs.' . PHP_EXT . '?mode=del_fav&amp;game=' . $fav_game_id . '&amp;sid=' . $user->data['session_id'] . '"><img src="' . ACTIVITY_IMAGES_PATH . 'r_favorite_game.jpg" alt="' . $lang['favorites_r_mouse_over'] . '" /></a></center>';
				$game_link = CheckGameImages($fav_game_name, $fav_game_proper);
				$image_link = GameArrayLink($fav_game_id, $fav_game_parent, $fav_game_popup, $fav_win_width, $fav_win_height, 2, '');
				$games_cost_line = '<br />'. $lang['separator'] . $lang['cost'] .': '. $cost;
				$games_cost_line .= '<br />'. $lang['separator'] . strip_tags($lang['ge_cost_per_game']) .':&nbsp;'. number_format($fav_game_ge_cost);
				$row_class = (!($p % 2)) ? 'row1' : 'row2';

				$template->assign_block_vars('game', array(
					'ROW_CLASS' => $row_class,
					'DESC2' => $lang['new_description'],
					'GAMES_PLAYED' => $lang['new_games_played'],
					'I_PLAYED' => number_format($fav_game_played) . $games_cost_line . $admin_edit,
					'PROPER_NAME' => $fav_game_proper,
					'TOP_PLAYER' => $top_player,
					'POP_PIC' => $popular_image,
					'NEW_PIC' => $new_image,
					'TOP_SCORE' => $lang['score'] . $top_score . $remove_link,
					'ROW_CLASS' => $row_class,
					'TROPHY_LINK' => ACTIVITY_IMAGES_PATH . 'trophy.gif',
					'SEPARATOR' => $lang['separator'],
					'CHARGE' => $cost,
					'IMAGE_LINK' => $game_link . $new_image,
					'NEW_I_LINK' => $image_link,
					'NAME' => $fav_game_name,
					'PATH' => $fav_game_path,
					'DESC' => $fav_game_desc,
					'INFO' => $lang['info'],
					'LINK' => GameArrayLink($fav_game_id, $fav_game_parent, $fav_game_popup, $fav_win_width, $fav_win_height, 1, $games_data[$j]['game_links']),
					'STATS' => 'javascript:popup_open(\'' . append_sid('activity_popup.' . PHP_EXT . '?mode=info&amp;g='. $fav_game_id) .'\',\'New_Window\',\'400\',\'380\',\'yes\')',
					'DASH' => $dash,
					'LIST' => $highscore_link
					)
				);

				if($fav_game_name)
				{
					$p++;
				}
			}
		}
	}
}

$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_favs_body.tpl');
full_page_generation($template_to_parse, $lang['favorites_page_title'], '', '');

?>