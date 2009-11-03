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

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);

if (!empty($setmodules) && defined('ACTIVITY_PLUGIN_ENABLED') && ACTIVITY_PLUGIN_ENABLED)
{
	//$file = basename(__FILE__);
	$file = IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . ADM . '/' . basename(__FILE__);
	$module['3000_ACTIVITY']['110_Configuration'] = $file;
	$module['3000_ACTIVITY']['120_Add_Game'] = $file . '?mode=add_game';
	$module['3000_ACTIVITY']['130_Edit_Games'] = $file . '?mode=edit_games';
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . 'common.' . PHP_EXT);

$action = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$mode = (isset($_GET['mode'])) ? $_GET['mode'] : $_POST['mode'];

if ($mode == 'add_game')
{
	$template->set_filenames(array('body' => ACTIVITY_ADM_TPL_PATH . 'game_add_body.tpl'));

	if ($config['use_point_system'])
	{
		$money_name = $config['points_name'];
	}
	else
	{
		$money_name = $lang['admin_charge'];
	}

	$q2 =  "SELECT *
			FROM ". $table_prefix ."ina_categories
			WHERE cat_id > '0'
			GROUP BY cat_name
			ORDER BY cat_name ASC";
	$r2 = $db->sql_query($q2);
	while($row = $db->sql_fetchrow($r2))
	{
		$cat_n = $row['cat_name'];
		$cat_id = $row['cat_id'];

		$template->assign_block_vars('cat', array(
			'C_SELECT_1' => $cat_id,
			'C_SELECT_2' => $cat_n
			)
		);
	}

	$game_dir = ACTIVITY_ROOT_PATH . $config['ina_default_g_path'];
	$games = opendir($game_dir);
	$g = 0;
	while ($file = readdir($games))
	{
		if (($file != '.') && ($file != '..') && ($file != 'index.htm'))
		{
			$q29 = "SELECT game_name
							FROM ". iNA_GAMES ."
					WHERE game_name = '$file'";
			$r29 = $db->sql_query($q29);

			if (!mysql_fetch_row($r29))
			{
				$template->assign_block_vars('drop', array(
					'D_SELECT' => $file
					)
				);
				$g++;
			}
		}
	}

	if (!$g)
		$default = $lang['admin_default_no_games'];

	if ($g == '1')
		$default = $lang['admin_default_1_game'];

	if ($g > '1')
		$default = $g .'&nbsp;'. $lang['admin_default_multi_games'];

	$default_max_cost = $config['ina_default_charge'];
	$increment_value = $config['ina_default_increment'];
	$inc_divide = $default_max_cost / $increment_value;

	$i = 0;
	$inc = $increment_value;

	while (($i < $inc_divide) && ($inc < $default_max_cost))
	{
		$inc = $inc + $increment_value;
		$template->assign_block_vars('charge', array(
			'D_SELECT' => $inc
			)
		);
		$i++;
	}

	$default_max_bonus = $config['ina_default_g_reward'];
	$increment_value2 = $config['ina_default_increment'];
	$inc_divide2 = $default_max_bonus / $increment_value2;

	$i2 = 0;
	$inc2 = $increment_value2;

	while (($i2 < $inc_divide2) && ($inc2 < $default_max_bonus))
	{
		$inc2 = $inc2 + $increment_value2;
		$template->assign_block_vars('bonus', array(
			'D_SELECT' => $inc2
			)
		);
		$i2++;
	}

	$game_type_box = '';
	$game_type_box .= '<select name="game_type">';
	$game_type_box .= '<option class="post" value="1">'. $lang['game_type_one'] .'</option>';
	$game_type_box .= '<option class="post" value="2">'. $lang['game_type_two'] .'</option>';
	$game_type_box .= '<option class="post" value="3">'. $lang['game_type_three'] .'</option>';
	$game_type_box .= '<option class="post" value="4">'. $lang['game_type_four'] .'</option>';
	$game_type_box .= '</select>';

	$template->assign_vars(array(
		'L_TYPE' => $lang['game_type_exp'],
		'V_TYPE' => $game_type_box,
		'L_LINKS' => $lang['game_links'],
		'L_GE_COST' => $lang['ge_cost_per_game'],
		'L_GE_COST_EXP' => $lang['ge_cost_per_game_exp'],
		'L_MOUSE' => $lang['game_mouse'],
		'L_KEYBOARD' => $lang['game_keyboard'],
		'L_FUNCTIONS' => $lang['admin_game_functionality'],
		'L_FUNCTIONS_EXP' => $lang['admin_game_functionality_e'],
		'MOUSE'	 => (($game_mouse) ? 'CHECKED' : ''),
		'KEYBOARD' => (($game_keyboard) ? 'CHECKED' : ''),
		'S_GAME_ACTION' => append_sid('admin_activity.' . PHP_EXT),
		'VERSION' => $version,
		'DASH' 	 => $lang['game_dash'],
		'C_DEFAULT' => $lang['a_default_category'],
		'C_SHORT' => $lang['a_category'],
		'C_EXPLAIN' => $lang['a_category_explain'],
		'V_GAME_HEIGHT' => $config['ina_default_g_height'],
		'V_GAME_WIDTH' => $config['ina_default_g_width'],
		'V_GAME_PATH' => $config['ina_default_g_path'],
		'V_DEFAULT' => $default,
		'V_DEFAULT_2' => $lang['a_default_charge'],
		'V_INC_1' => $increment_value,
		'V_DEFAULT_3' => $lang['a_default_bonus'],
		'V_INC_2' => $increment_value2,
		'L_MENU_HEADER' => $lang['admin_game_editor'],
		'L_MENU_INFO' => $lang['admin_editor_info'],
		'L_DISABLE_DES' => '<b>'. $lang['a_default_hide'] .'</b>',
		'L_DISABLE_DS' => $lang['a_default_hide_explain'],
		'L_NAME' => $lang['admin_name'],
		'L_PROPER_NAME' => $lang['admin_proper_name'],
		'L_PROPER_NAME_INFO' => $lang['admin_proper_name_desc'],
		'L_NAME_INFO' => $lang['admin_name_info'],
		'L_GAME_PATH' => $lang['admin_game_path'],
		'L_GAME_PATH_INFO' => $lang['admin_game_path_info'],
		'L_GAME_DESC' => $lang['admin_game_desc'],
		'L_GAME_DESC_INFO' => $lang['admin_game_desc_info'],
		'L_GAME_CHARGE' => $lang['admin_game_charge'],
		'L_GAME_CHARGE_INFO' => $lang['admin_game_charge_info'],
		'L_GAME_PER' => $lang['admin_game_per'],
		'L_GAME_PER_INFO' => $lang['admin_game_per_info'],
		'L_GAME_BONUS' => $lang['admin_game_bonus'],
		'L_GAME_BONUS_INFO' => $lang['admin_game_bonus_info'],
		'L_GAME_GAMELIB' => $lang['admin_game_gamelib'],
		'L_GAME_GAMELIB_INFO' => $lang['admin_game_gamelib_info'],
		'L_GAME_FLASH' => $lang['admin_game_flash'],
		'L_GAME_FLASH_INFO' => $lang['admin_game_flash_info'],
		'L_GAME_SHOW_SCORE' => $lang['admin_game_show_score'],
		'L_GAME_SHOW_INFO' => $lang['admin_game_show_info'],
		'L_GAME_REVERSE' => $lang['admin_game_reverse'],
		'L_GAME_REVERSE_INFO' => $lang['admin_game_reverse_info'],
		'L_HIGHSCORE_LIMIT' => $lang['admin_game_highscore'],
		'L_HIGHSCORE_INFO' => $lang['admin_game_highscore_info'],
		'L_GAME_SIZE' => $lang['admin_game_size'],
		'L_GAME_SIZE_INFO' => $lang['admin_game_size_info'],
		'L_INSTRUCTIONS' => $lang['game_instructions'],
		'L_INSTRUCTIONS_INFO' => $lang['instructions_info'],
		'L_WIDTH' => $lang['admin_width'],
		'L_HEIGHT' => $lang['admin_height'],
		'L_MONEY' => $money_name,
		'L_REWARD' => $lang['admin_reward'],
		'L_CHARGE' => $lang['admin_charge'],
		'L_BONUS' => $lang['admin_bonus'],
		'L_LIMIT' => $lang['admin_limit'],
		'L_NO' 	 => $lang['No'],
		'L_YES' => $lang['Yes'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],

		'S_HIDDEN_FIELDS' => '<input type="hidden" name="mode" value="edit_games"><input type="hidden" name="action" value="save">'
		)
	);
}

if ($mode == 'clear_scores')
{
	$sql = "TRUNCATE " . iNA_SCORES;
	$db->sql_query($sql);

	$sql = "UPDATE ". iNA_GAMES ."
			SET played = '0'
			WHERE played <> '0'";
	$result = $db->sql_query($sql);

	$message = $lang['admin_score_reset'];
	$message .= sprintf($lang['admin_return_activity'], '<a href="' . append_sid('admin_activity.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message, '', __LINE__, __FILE__, $sql);
}

function DeleteGame($game_id)
{
	global $lang, $db, $table_prefix;
	if ($game_id)
	{
		$sql = "SELECT game_name
				FROM ". iNA_GAMES ."
				WHERE game_id = '$game_id'";
		$result = $db->sql_query($sql);

		$row = $db->sql_fetchrow($result);
		$game_name_to_delete = $row['game_name'];

		$sql = "DELETE FROM " . iNA_GAMES . "
				WHERE game_id = '$game_id'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM ". $table_prefix ."ina_hall_of_fame
				WHERE game_id = '$game_id'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM ". $table_prefix ."ina_top_scores
				WHERE game_name = '$game_name_to_delete'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM ". $table_prefix ."ina_trophy_comments
				WHERE game = '$game_name_to_delete'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM ". iNA_SCORES ."
				WHERE game_name = '$game_name_to_delete'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM ". $table_prefix ."ina_gamble
				WHERE game_id = '$game_id'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM ". $table_prefix ."ina_gamble_in_progress
				WHERE game_id = '$game_id'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM ". $table_prefix ."ina_rating_votes
				WHERE game_id = '$game_id'";
		$result = $db->sql_query($sql);

		$message = $lang['admin_game_deleted'];
		$message .= sprintf($lang['admin_return_activity'], '<a href="' . append_sid('admin_activity.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message, '', __LINE__, __FILE__, $sql);
	}
	else
	{
		$message = $lang['admin_game_not_deleted'];
		$message .= sprintf($lang['admin_return_activity'], '<a href="' . append_sid('admin_activity.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message, '', __LINE__, __FILE__, $sql);
	}
}

#==== Main Games Array ============================ |
$q =  "SELECT *
			 FROM ". iNA_GAMES ."
		 ORDER BY proper_name ASC";
$r = $db->sql_query($q);
$games_data = $db->sql_fetchrowset($r);
$games_total = $db->sql_numrows($r);

#==== Main Category Array ========================= |
$q = "SELECT *
		FROM ". INA_CATEGORY ."
		WHERE cat_id > '0'
		ORDER BY cat_name ASC";
$r = $db->sql_query($q);
$cat_data = $db->sql_fetchrowset($r);
$cat_count = $db->sql_numrows($r);

if ($mode == 'edit_games')
{
	if (!$action)
	{
		$template->set_filenames(array('body' => ACTIVITY_ADM_TPL_PATH . 'activity_admin_main.tpl'));

		$q =  "SELECT game_id
					 FROM ". iNA_GAMES ."
				 WHERE game_id > '0'";
		$r = $db->sql_query($q);
		$games = $db->sql_numrows($r);

		$template->assign_vars(array(
			'TITLE' => $lang['admin_edit_header'],
			'T_L' => $lang['admin_edit_title_l'],
			'T_LC' => $lang['admin_edit_title_lc'],
			'T_RC' => $lang['admin_edit_title_rc'],
			'T_R' => $lang['admin_edit_title_r'],
			'M_L' => $lang['admin_edit_all_l'],
			'M_RC' => $games,
			'M_R' => '<a href="admin_activity.' . PHP_EXT .'?mode=edit_games&amp;action=view&amp;cat=all&amp;sid='. $userdata['session_id'] .'">'. $lang['admin_edit_title_r_exp'] .'</a>'
			)
		);

		$q =  "SELECT *
				 FROM ". INA_CATEGORY ."
				 WHERE cat_id > '0'";
		$r = $db->sql_query($q);
		while($row = $db->sql_fetchrow($r))
		{
			$cat_img = $row['cat_img'];
			$cat_desc = $row['cat_desc'];
			$cat_name = $row['cat_name'];
			$cat_id = $row['cat_id'];

			$q1 =  "SELECT COUNT(game_id) AS total_games
					FROM ". iNA_GAMES ."
					WHERE cat_id = '". $cat_id ."'";
			$r1 = $db->sql_query($q1);
			$row1 = $db->sql_fetchrow($r1);
			$total_games = $row1['total_games'];

			if (file_exists(IP_ROOT_PATH . $cat_img) == 0)
				$cat_img = '';
			else
				$cat_img = '<img src="' . IP_ROOT_PATH . $cat_img . '" border="0">';

			$template->assign_block_vars('rows', array(
				'ONE' => $cat_name,
				'TWO' => $cat_img,
				'THREE' => $total_games,
				'FOUR' => ($total_games > '0') ? '<a href="admin_activity.' . PHP_EXT .'?mode=edit_games&amp;action=view&amp;cat='. $cat_id .'&amp;sid=' . $userdata['session_id'] .'">'. $lang['admin_edit_title_r_exp'] .'</a>' : $lang['admin_edit_title_r_exp']
				)
			);
		}
	}
}

if ($action == 'view')
{
	$template->set_filenames(array('body' => ACTIVITY_ADM_TPL_PATH . 'activity_admin_edit.tpl'));
	$cat = ($_GET['cat']) ? $_GET['cat'] : $_GET['cat'];

	if ($cat == 'all')
	{
		$template->assign_block_vars('cat_choice', array());

		for ($a = 0; $a < $games_total; $a++)
		{
			$game_id = $games_data[$a]['game_id'];
			$game_name = $games_data[$a]['proper_name'];
			$game_image = '<img src="'. IP_ROOT_PATH . $config['ina_default_g_path'] .'/'. $games_data[$a]['game_name'] .'/'. $games_data[$a]['game_name'] .'.gif" border="0">';
			if (!$game_image)
				$game_image = $lang['admin_edit_title_r'];

			$edit_link = '<a href="admin_activity.' . PHP_EXT .'?mode=edit_games&amp;action=edit&amp;game='. $game_id .'&amp;sid=' . $userdata['session_id'] .'">'. $game_image .'</a>';

			$template->assign_block_vars('cat_choice.rows', array(
				'ONE' => $game_name,
				'TWO' => $edit_link)
					);

				if (!$games_data[$a]['game_id'])
					break;
			}
	}
	else
	{
		$cat = intval($cat);

		$template->assign_block_vars('cat_choice', array());

		for ($a = 0; $a < $games_total; $a++)
		{
			if ($games_data[$a]['cat_id'] == $cat)
			{
				$game_id = $games_data[$a]['game_id'];
				$game_name = $games_data[$a]['proper_name'];
				$game_image = '<img src="'. IP_ROOT_PATH . $config['ina_default_g_path'] .'/'. $games_data[$a]['game_name'] .'/'. $games_data[$a]['game_name'] .'.gif" border="0">';
					if (!$game_image)
						$game_image = $lang['admin_edit_title_r'];

				$edit_link = '<a href="admin_activity.' . PHP_EXT .'?mode=edit_games&amp;action=edit&amp;game='. $game_id .'&amp;sid=' . $userdata['session_id'] .'">'. $game_image .'</a>';

				$template->assign_block_vars('cat_choice.rows', array(
					'ONE' => $game_name,
					'TWO' => $edit_link)
						);
				if (!$games_data[$a]['game_id'])
					break;
			}
			if (!$games_data[$a]['game_id'])
				break;
		}
	}
}

if ($action == 'edit')
{
	$template->set_filenames(array('body' => ACTIVITY_ADM_TPL_PATH . 'activity_admin_edit.tpl'));
	$game = ($_GET['game']) ? $_GET['game'] : $_GET['game'];
	$template->assign_block_vars('editing', array());

	for ($a = 0; $a < $games_total; $a++)
	{
		if ($games_data[$a]['game_id'] == $game)
		{
			#==== Game options
			$game_id = $games_data[$a]['game_id'];			# not changeable
			$game_name = $games_data[$a]['game_name'];			# input box
			$game_proper = $games_data[$a]['proper_name'];		# input box
			$game_width = $games_data[$a]['win_width'];			# input box
			$game_height = $games_data[$a]['win_height'];		# input box
			$game_path = $games_data[$a]['game_path'];			# input box
			$game_scores = $games_data[$a]['game_show_score'];	# radio
			$game_scores_limit = $games_data[$a]['highscore_limit'];	# input box
			$game_scores_order = $games_data[$a]['reverse_list'];		# radio
			$game_flash = $games_data[$a]['game_flash'];		# radio
			$game_glib = $games_data[$a]['game_use_gl'];		# radio
			$game_bonus = $games_data[$a]['game_bonus'];		# input box
			$game_cost = $games_data[$a]['game_charge'];		# input box
			$game_reward = $games_data[$a]['game_reward'];		# input box
			$game_desc = $games_data[$a]['game_desc'];			# text area
			$game_disabled = $games_data[$a]['disabled'];			# radio
			$game_instructions = $games_data[$a]['instructions'];		# text area
			$game_popup = $games_data[$a]['game_popup'];		# radio
			$game_parent = $games_data[$a]['game_parent'];		# radio
			$game_category = $games_data[$a]['cat_id'];			# drop down list
			$game_type = $games_data[$a]['game_type'];			# drop down list
			$game_links = $games_data[$a]['game_links'];		# input box
			$game_ge_cost = $games_data[$a]['game_ge_cost'];		# input box
			$game_mouse = $games_data[$a]['game_mouse'];		# checkbox
			$game_keyboard = $games_data[$a]['game_keyboard'];		# checkbox
			#==== Radio options
			$reverse_yes = ($game_scores_order) 	? 'checked="checked"' : '';
			$reverse_no = (!$game_scores_order) ? 'checked="checked"' : '';
			$scores_yes = ($game_scores) 		? 'checked="checked"' : '';
			$scores_no = (!$game_scores) 		? 'checked="checked"' : '';
			$flash_yes = ($game_flash) 		? 'checked="checked"' : '';
			$flash_no = (!$game_flash) 		? 'checked="checked"' : '';
			$glib_yes = ($game_glib) 			? 'checked="checked"' : '';
			$glib_no = (!$game_glib) 		? 'checked="checked"' : '';
			$disabled_yes = ($game_disabled == 2)	? 'checked="checked"' : '';
			$disabled_no = ($game_disabled == 1) ? 'checked="checked"' : '';
			$popup_yes = ($game_popup) 		? 'checked="checked"' : '';
			$popup_no = (!$game_popup) 		? 'checked="checked"' : '';
			$parent_yes = ($game_parent) 		? 'checked="checked"' : '';
			$parent_no = (!$game_parent) 		? 'checked="checked"' : '';

			if ($game_type == 1)
				$type_one = 'selected';
			if ($game_type == 2)
				$type_two = 'selected';
			if ($game_type == 3)
				$type_three = 'selected';
			if ($game_type == 4)
				$type_four = 'selected';

			$game_type_box = '';
			$game_type_box .= '<select name="game_type">';
			$game_type_box .= '<option class="post" '. $type_one .' value="1">'. $lang['game_type_one'] .'</option>';
			$game_type_box .= '<option class="post" '. $type_two .' value="2">'. $lang['game_type_two'] .'</option>';
			$game_type_box .= '<option class="post" '. $type_three .' value="3">'. $lang['game_type_three'] .'</option>';
			$game_type_box .= '<option class="post" '. $type_four .' value="4">'. $lang['game_type_four'] .'</option>';
			$game_type_box .= '</select>';

			$category_box = '';
			$category_box    .= '<select name="game_cat">';
			if (!$game_category)
			$category_box .= '<option class="post" selected value="">'. $lang['a_default_category'] .'</option>';
			for ($b = 0; $b < $cat_count; $b++)
			{
				if ($cat_data[$b]['cat_id'] == $game_category)
				{
					$category_box .= '<option class="post" selected value="'. $cat_data[$b]['cat_id'] .'">'. $cat_data[$b]['cat_name'] .'</option>';
				}
				else
				{
					$category_box .= '<option class="post" value="'. $cat_data[$b]['cat_id'] .'">'. $cat_data[$b]['cat_name'] .'</option>';
				}
			}
			$category_box    .= '<option class="post" value="">-----</option>';
			$category_box   .= '</select>';

			$template->assign_vars(array(
				'ID' => $game_id,
				'CAT' => $category_box,
				'L_MOUSE' => $lang['game_mouse'],
				'L_KEYBOARD' => $lang['game_keyboard'],
				'L_FUNCTIONS' => $lang['admin_game_functionality'],
				'L_FUNCTIONS_EXP' => $lang['admin_game_functionality_e'],
				'MOUSE' => (($game_mouse) ? 'checked="checked"' : ''),
				'KEYBOARD' => (($game_keyboard) ? 'checked="checked"' : ''),
				'L_GE_COST' => $lang['ge_cost_per_game'],
				'L_GE_COST_EXP' => $lang['ge_cost_per_game_exp'],
				'V_GE_COST' => $game_ge_cost,
				'L_TYPE' => $lang['game_type_exp'],
				'V_TYPE' => $game_type_box,
				'L_LINKS' => $lang['game_links'],
				'V_LINKS' => $game_links,
				'RETURN' => append_sid('admin_activity.' . PHP_EXT .'?mode=edit_games'),
				'V_ONE' => $game_name,
				'L_ONE' => $lang['admin_name'] .'<br /><span class="gensmall">'. $lang['admin_name_info'] .'</span>',
				'V_TWO' => $game_proper,
				'L_TWO' => $lang['admin_proper_name'] .'<br /><span class="gensmall">'. $lang['admin_proper_name_desc'] .'</span>',
				'V_THREE' => $game_path,
				'L_THREE' => $lang['admin_game_path'] .'<br /><span class="gensmall">'. $lang['admin_game_path_info'] .'</span>',
				'V_FOUR' => $game_desc,
				'L_FOUR' => $lang['admin_game_desc'] .'<br /><span class="gensmall">'. $lang['admin_game_desc_info'] .'</span>',
				'V_FIVE' => $game_instructions,
				'L_FIVE' => $lang['game_instructions'] .'<br /><span class="gensmall">'. $lang['instructions_info'] .'</span>',
				'L_SIZE' => $lang['admin_game_size'] .'<br /><span class="gensmall">'. $lang['admin_game_size_info'] .'</span>',
				'V_SIX' => $game_width,
				'L_SIX' => $lang['admin_width'],
				'V_SEVEN' => $game_height,
				'L_SEVEN' => $lang['admin_height'],
				'V_EIGHT' => $game_bonus,
				'L_EIGHT' => $lang['admin_game_bonus'] .'<br /><span class="gensmall">'. $lang['admin_game_bonus_info'] .'</span>',
				'V_NINE' => $game_reward,
				'L_NINE' => $lang['admin_game_per'] .'<br /><span class="gensmall">'. $lang['admin_game_per_info'] .'</span>',
				'V_TEN' => $game_cost,
				'L_TEN' => $lang['admin_game_charge'] .'<br /><span class="gensmall">'. $lang['admin_game_charge_info'] .'</span>',
				'V_ELEVEN' => $game_scores_limit,
				'L_ELEVEN' => $lang['admin_game_highscore'] .'<br /><span class="gensmall">'. $lang['admin_game_highscore_info'] .'</span>',
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_RESET_SCORES' => $lang['admin_game_reset_hs'] .'<br /><span class="gensmall">'. $lang['admin_game_reset_hs_info'] .'</span>',
				'L_RESET_JACKPOT' => $lang['admin_drop_jackpot'] .'<br /><span class="gensmall">'. $lang['admin_drop_jackpot_exp'] .'</span>',
				'L_DELETE_GAME' => $lang['admin_drop_game'] .'<br /><span class="gensmall">'. $lang['admin_drop_game_exp'] .'</span>',
				'L_REVERSE' => $lang['admin_game_reverse'] .'<br /><span class="gensmall">'. $lang['admin_game_reverse_info'] .'</span>',
				'REVERSE_Y' => $reverse_yes,
				'REVERSE_N' => $reverse_no,
				'L_SCORES' => $lang['admin_game_show_score'] .'<br /><span class="gensmall">'. $lang['admin_game_show_info'] .'</span>',
				'SCORES_Y' => $scores_yes,
				'SCORES_N' => $scores_no,
				'L_FLASH' => $lang['admin_game_flash'] .'<br /><span class="gensmall">'. $lang['admin_game_flash_info'] .'</span>',
				'FLASH_Y' => $flash_yes,
				'FLASH_N' => $flash_no,
				'L_GLIB' => $lang['admin_game_gamelib'] .'<br /><span class="gensmall">'. $lang['admin_game_gamelib_info'] .'</span>',
				'GLIB_Y' => $glib_yes,
				'GLIB_N' => $glib_no,
				'L_DISABLE' => $lang['admin_disable_game'] .'<br /><span class="gensmall">'. $lang['admin_disable_game_exp'] .'</span>',
				'DIS_Y' => $disabled_yes,
				'DIS_N' => $disabled_no,
				'L_PARENT' => $lang['admin_parent_game'] .'<br /><span class="gensmall">'. $lang['admin_parent_game_exp'] .'</span>',
				'PARENT_Y' => $parent_yes,
				'PARENT_N' => $parent_no,
				'L_POPUP' => $lang['admin_popup_game'] .'<br /><span class="gensmall">'. $lang['admin_popup_game_exp'] .'</span>',
				'POPUP_Y' => $popup_yes,
				'POPUP_N' => $popup_no,
				'L_CATEGORY' => '<b>'. $lang['a_category'] .'</b><br /><span class="gensmall">'. $lang['a_category_explain'] .'</span>',
				'L_SUBMIT' => $lang['Submit']
				)
			);

			break;
		}
	}
}

if ($action == 'save')
{
	$template->set_filenames(array('body' => ACTIVITY_ADM_TPL_PATH . 'activity_admin_edit.tpl'));
	$game_id = intval(($_POST['game_id']) ? $_POST['game_id'] : $_POST['game_id']);
	$game_name = ($_POST['game_name']) ? urldecode($_POST['game_name']) : urldecode($_POST['game_name']);
	$err1 = (!$game_name) ? 1 : 0;
	$game_proper = ($_POST['game_proper']) ? $_POST['game_proper'] : $_POST['game_proper'];
	$err2 = (!$game_proper) ? 1 : 0;
	$game_cat = intval(($_POST['game_cat']) ? $_POST['game_cat'] : $_POST['game_cat']);
	$game_path = ($_POST['game_path']) ? $_POST['game_path'] : $_POST['game_path'];
	$err3 = (!$game_path) ? 1 : 0;
	$game_width = intval(($_POST['game_width']) ? $_POST['game_width'] : $_POST['game_width']);
	$game_height = intval(($_POST['game_height']) ? $_POST['game_height'] : $_POST['game_height']);
	$game_bonus = intval(($_POST['game_bonus']) ? $_POST['game_bonus'] : $_POST['game_bonus']);
	$game_reward = intval(($_POST['game_reward']) ? $_POST['game_reward'] : $_POST['game_reward']);
	$game_charge = intval(($_POST['game_charge']) ? $_POST['game_charge'] : $_POST['game_charge']);
	$game_max_scores = intval(($_POST['game_highscore']) ? $_POST['game_highscore'] : $_POST['game_highscore']);
	$game_desc = ($_POST['game_description']) ? $_POST['game_description'] : $_POST['game_description'];
	$game_instr = ($_POST['game_instructions']) ? $_POST['game_instructions'] : $_POST['game_instructions'];
	$game_reverse = intval(($_POST['game_reverse']) ? $_POST['game_reverse'] : $_POST['game_reverse']);
	$game_allow_scores = (intval($_POST['game_showscores']) ? $_POST['game_showscores'] : $_POST['game_showscores']);
	$game_allow_scores = (!$game_allow_scores)					? 1								: 0;
	$game_flash = intval(($_POST['game_flash']) ? $_POST['game_flash'] : $_POST['game_flash']);
	$game_glib = intval(($_POST['game_glib']) ? $_POST['game_glib'] : $_POST['game_glib']);
	$game_disabled = intval(($_POST['game_disable']) ? $_POST['game_disable'] : $_POST['game_disable']);
	$game_disabled = ($game_disabled == '2') ? 0 : 1;
	$game_parent = intval(($_POST['game_parent']) ? $_POST['game_parent'] : $_POST['game_parent']);
	$game_popup = intval(($_POST['game_popup']) ? $_POST['game_popup'] : $_POST['game_popup']);
	$game_jackpot = ($_POST['reset_jackpot']) ? $_POST['reset_jackpot'] : $_POST['reset_jackpot'];
	$game_reset_scores = ($_POST['reset_scores']) ? $_POST['reset_scores'] : $_POST['reset_scores'];
	$game_delete = ($_POST['delete_game']) ? $_POST['delete_game'] : $_POST['delete_game'];
	$game_type = intval(($_POST['game_type']) ? $_POST['game_type'] : $_POST['game_type']);
	$game_links = ($_POST['game_links'])				? $_POST['game_links']			: $_POST['game_links'];
	$game_ge_cost = intval(($_POST['game_ge_cost'])		? $_POST['game_ge_cost'] : $_POST['game_ge_cost']);
	$game_keyboard = ($_POST['game_keyboard'] == 'on') ? 1 : 0;
	$game_mouse = ($_POST['game_mouse'] == 'on') ? 1 : 0;

	$q = "SELECT game_name
			FROM ". iNA_GAMES ."
			WHERE game_id = '". $game_id ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$real_game_name = $row['game_name'];

	if ($err1 || $err2 || $err3)
	{
		if ($err1)
			message_die(GENERAL_ERROR, $lang['edit_error_one']);
		if ($err2)
			message_die(GENERAL_ERROR, $lang['edit_error_two']);
		if (($err3) && ($game_id > '0'))
			message_die(GENERAL_ERROR, $lang['edit_error_three']);
	}

	if ($game_delete)
		DeleteGame($game_id);

	if ($game_reset_scores)
	{
		$q = "DELETE FROM ". iNA_SCORES ."
				WHERE game_name = '". $real_game_name ."'";
		$db->sql_query($q);
	}

	if ($game_id)
	{
		$game_name = trim(rtrim(addslashes(stripslashes($game_name))));
		$game_desc = trim(rtrim(addslashes(stripslashes($game_desc))));
		$game_instr = trim(rtrim(addslashes(stripslashes($game_instr))));
		$game_proper = trim(rtrim(addslashes(stripslashes($game_proper))));
		$game_links = trim(rtrim(addslashes(stripslashes($game_links))));
		#==== Build it here, easier to update later!
		$changes = (!$game_name) ? '' : "game_name = '". $game_name ."'";
		$changes .= (!$game_links) ? ", game_links = ''" : ", game_links = '". $game_links ."'";
		$changes .= (!$game_type) ? '' : ", game_type = '". $game_type ."'";
		$changes .= (!$game_path) ? '' : ", game_path = '". $config['ina_default_g_path'] ."$game_name/'";
		$changes .= (!$game_desc) ? '' : ", game_desc = '". $game_desc ."'";
		$changes .= (!$game_charge) ? ", game_charge = '0'" : ", game_charge = '". $game_charge ."'";
		$changes .= (!$game_reward) ? ", game_reward = '0'" : ", game_reward = '". $game_reward ."'";
		$changes .= (!$game_bonus) ? ", game_bonus = '0'" : ", game_bonus = '". $game_bonus ."'";
		$changes .= (!$game_glib) ? '' : ", game_use_gl = '". $game_glib ."'";
		$changes .= (!$game_flash) ? '' : ", game_flash = '". $game_flash ."'";
		$changes .= (!$game_allow_scores) ? '' : ", game_show_score = '". $game_allow_scores ."'";
		$changes .= (!$game_width) ? '' : ", win_width = '". $game_width ."'";
		$changes .= (!$game_height) ? '' : ", win_height = '". $game_height ."'";
		$changes .= (!$game_max_scores) ? '' : ", highscore_limit = '". $game_max_scores ."'";
		$changes .= (!$game_reverse) ? ", reverse_list = '0'" : ", reverse_list = '1'";
		$changes .= (!$game_instr) ? '' : ", instructions = '". $game_instr ."'";
		$changes .= (!$game_disabled) ? ", disabled = '0'" : ", disabled = '1'";
		$changes .= (!$game_proper) ? '' : ", proper_name = '". $game_proper ."'";
		$changes .= (!$game_cat) ? '' : ", cat_id = '". $game_cat ."'";
		$changes .= (!$game_jackpot) ? '' : ", jackpot = '0'";
		$changes .= (!$game_popup) ? ", game_popup = '0'" : ", game_popup = '1'";
		$changes .= (!$game_parent) ? ", game_parent = '0'" : ", game_parent = '1'";
		$changes .= (!$game_ge_cost) ? ", game_ge_cost = '0'" : ", game_ge_cost = '". $game_ge_cost ."'";
		$changes .= ", game_mouse = '". $game_mouse ."'";
		$changes .= ", game_keyboard = '". $game_keyboard ."'";

		$q = "UPDATE ". iNA_GAMES ."
				SET $changes
				WHERE game_id = '". $game_id ."'";
		$db->sql_query($q);

		$q = "INSERT INTO ". iNA_GAMES ."
				(game_name, game_mouse, game_keyboard, game_links, game_type, game_path, game_desc, game_charge, game_reward, game_bonus, game_use_gl, game_flash, game_show_score, win_width, win_height, highscore_limit, reverse_list, played, instructions, disabled, install_date, proper_name, cat_id, jackpot, game_popup, game_parent, game_ge_cost)
				VALUES
				('". str_replace("\'", "''", $game_name) ."', '". $game_mouse ."', '". $game_keyboard ."', '". $game_links ."', '". $game_type ."', '". $config['ina_default_g_path'] ."$game_name/', '". str_replace("\'", "''", $game_desc) ."', '". $game_charge ."', '". $game_reward ."', '". $game_bonus ."', '". $game_glib ."', '". $game_flash ."', '". $game_allow_scores ."', '". $game_width ."', '". $game_height ."', '". $game_max_scores ."', '". $game_reverse ."', '0', '". str_replace("\'", "''", $game_instr) ."', '". $game_disabled ."', '". time() ."', '". str_replace("\'", "''", $game_proper) ."', '". $game_cat ."', '". $config['ina_jackpot_pool'] ."', '1', '1', '". $game_ge_cost ."')";
		$db->sql_query($q);

		$message = $lang['admin_game_saved'];
		$message .= sprintf($lang['admin_return_activity'], '<a href="' . append_sid('admin_activity.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}

if ((!$mode) && (!$action))
{
	$sql = "SELECT *
			FROM " . CONFIG_TABLE . "
			WHERE config_name IN('use_point_system','use_gamelib','games_path','gamelib_path','use_gk_shop','games_per_page','warn_cheater','report_cheater','use_cash_system','use_rewards_mod','use_allowance_system','default_reward_dbfield','default_cash')";

	$result = $db->sql_query($sql);

	if (($config['default_reward_dbfield'] != 'user_money') && ($config['use_allowance_system']))
	{
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = 'user_money'
			WHERE config_name = 'default_reward_dbfield'";
		$db->sql_query($sql);
	}

	if (($config['default_reward_dbfield'] != $config['default_cash']) && ($config['use_cash_system']))
	{
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . $config['default_cash'] . "'
			WHERE config_name = 'default_reward_dbfield'";
		$db->sql_query($sql);
	}

	while($row = $db->sql_fetchrow($result))
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;

		$new[$config_name] = (isset($_POST[$config_name])) ? $_POST[$config_name] : $default_config[$config_name];

		if(isset($_POST['submit']))
		{
			$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
					WHERE config_name = '$config_name'";
			$db->sql_query($sql);
		}
	}

	if (isset($_POST['submit']))
	{
		$message = $lang['admin_config_updated'];
		$message .= sprintf($lang['admin_return_activity'], '<a href="' . append_sid('admin_activity.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message, '', __LINE__, __FILE__, $sql);
	}

	$use_gk_shop_yes = ($new['use_gk_shop']) ? 'checked="checked"' : '';
	$use_gk_shop_no = (!$new['use_gk_shop']) ? 'checked="checked"' : '';

	$use_gamelib_yes = ($new['use_gamelib']) ? 'checked="checked"' : '';
	$use_gamelib_no = (!$new['use_gamelib']) ? 'checked="checked"' : '';

	$use_point_system_yes = ($new['use_point_system']) ? 'checked="checked"' : '';
	$use_point_system_no = (!$new['use_point_system']) ? 'checked="checked"' : '';

	$use_cash_system_yes = ($new['use_cash_system']) ? 'checked="checked"' : '';
	$use_cash_system_no = (!$new['use_cash_system']) ? 'checked="checked"' : '';

	$use_allowance_system_yes = ($new['use_allowance_system']) ? 'checked="checked"' : '';
	$use_allowance_system_no = (!$new['use_allowance_system']) ? 'checked="checked"' : '';

	$use_rewards_yes = ($new['use_rewards_mod']) ? 'checked="checked"' : '';
	$use_rewards_no = (!$new['use_rewards_mod']) ? 'checked="checked"' : '';

	$games_path = $new['games_path'];
	$gamelib_path = $new['gamelib_path'];

	$games_per_page = $new['games_per_page'];

	$template->set_filenames(array('body' => ACTIVITY_ADM_TPL_PATH . 'activity_config_body.tpl'));

	if ($config['use_gamelib'])
		$template->assign_block_vars('display_gamelib_menu', array());

	if ($config['use_gk_shop'])
		$template->assign_block_vars('display_shop_menu', array());

	if ($config['use_rewards_mod'])
		{
	$template->assign_block_vars('rewards_menu_on', array());
		if ($config['use_cash_system'])
			$template->assign_block_vars('cash_default_menu', array());
		}

	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid('admin_activity.' . PHP_EXT),
		'VERSION' => $version,
		'DASH' => $lang['game_dash'],
		'DEFAULT_CASH' => $config['default_cash'],

		'L_CONFIG_MENU' => $lang['admin_config_menu'],
		'L_INA_HEADER' => $lang['admin_main_header'],
		'L_TOGGLES' => $lang['admin_toggles'],
		'L_REWARDS' => $lang['admin_rewards'],
		'L_ACTIVITY_CONFIG' => $lang['admin_activity_config'],
		'L_USE_ADAR_SHOP' => $lang['admin_use_adar_shop'],
		'L_USE_ADAR_INFO' => $lang['admin_use_adar_info'],
		'L_USE_GAMELIB' => $lang['admin_use_gamelib'],
		'L_USE_GL_INFO' => $lang['admin_use_gl_info'],
		'L_USE_POINTS' => $lang['admin_use_points'],
		'L_USE_POINTS_INFO' => $lang['admin_use_pts_info'],
		'L_CASH' => $lang['admin_cash'],
		'L_USE_CASH' => $lang['admin_use_cash'],
		'L_USE_CASH_INFO' => $lang['admin_use_cash_info'],
		'L_CASH_DEFAULT_INFO' => $lang['admin_cash_default_info'],
		'L_USE_ALLOWANCE' => $lang['admin_use_allowance'],
		'L_USE_ALLOWANCE_INFO' => $lang['admin_use_allowance_info'],
		'L_USE_REWARDS' => $lang['admin_use_rewards'],
		'L_USE_REWARDS_INFO' => $lang['admin_use_rewards_info'],
		'L_GL_GAME_PATH' => $lang['admin_gl_game_path'],
		'L_GL_PATH_INFO' => $lang['admin_gl_path_info'],
		'L_GL_LIB_PATH' => $lang['admin_gl_lib_path'],
		'L_GL_LIB_INFO' => $lang['admin_gl_lib_info'],
		'L_GAMES_PER_PAGE' => $lang['admin_games_per_page'],
		'L_GAMES_PER_INFO' => $lang['admin_games_per_info'],
		'L_ADAR_SHOP_CONFIG' => $lang['admin_adar_config'],
		'L_ADAR_SHOP' => $lang['admin_adar_shop'],
		'L_ADAR_INFO' => $lang['admin_no_adar_info'],

		'L_PAGE' => $lang['admin_page'],
		'L_PATH' => $lang['admin_path'],
		'L_EDIT' => $lang['Edit'],
		'L_ADD' => $lang['Add_new'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],

		'S_USE_GKS_YES' => $use_gk_shop_yes,
		'S_USE_GKS_NO' => $use_gk_shop_no,
		'S_USE_GL_YES' => $use_gamelib_yes,
		'S_USE_GL_NO' => $use_gamelib_no,
		'S_USE_PSM_YES' => $use_point_system_yes,
		'S_USE_PSM_NO' => $use_point_system_no,
		'S_USE_CASH_YES' => $use_cash_system_yes,
		'S_USE_CASH_NO' => $use_cash_system_no,
		'S_USE_ASM_YES' => $use_allowance_system_yes,
		'S_USE_ASM_NO' => $use_allowance_system_no,
		'S_USE_REWARDS_YES' => $use_rewards_yes,
		'S_USE_REWARDS_NO' => $use_rewards_no,

		'S_GAMES_PATH' => $games_path,
		'S_GAMELIB_PATH' => $gamelib_path,
		'S_GAMES_PER_PAGE' => $games_per_page,
		'S_HIDDEN_FIELDS' => ''
		)
	);
}

// Generate the page
$template->pparse('body');
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>