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

if (!empty($setmodules))
{
	if (empty($config['plugins']['activity']['enabled']))
	{
		return;
	}

	$file = IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . ADM . '/' . basename(__FILE__);
	$module['3000_ACTIVITY']['200_Mass_Change'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

if (!$mode)
{
	echo '<form name="save_mass" method="post" action="admin_ina_mass.' . PHP_EXT . '?mode=save&sid='. $userdata['session_id'] .'">';
	echo '<table align="center" width="100%" class="forumline">';
	echo '	<tr>';
	echo '		<th width="80%">';
	echo '			'. $lang['Mass_Change'];
	echo '		</th>';
	echo '		<th width="20%">&nbsp;</th>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="center" width="100%" colspan="2" class="row2">';
	echo			$lang['mass_change_title'];
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_cost'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_cost" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_ge_cost'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_ge_cost" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_path'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_path" size="20">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_jackpot'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_jackpot" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_bonus'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_bonus" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_reward'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_reward" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_parent_1'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="parent_on">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_parent_2'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="parent_off">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_popup_1'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="popup_on">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_popup_2'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="popup_off">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_links_1'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="remove_links">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_links_2'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_links" size="20">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_cats'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="remove_cats">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_height'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_height" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_width'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_width" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_highscores'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="" name="new_highscores" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_desc'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="remove_desc">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_info'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="remove_inst">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_disable_1'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="hide_games">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_disable_2'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="show_games">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_scores_1'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="hide_scores">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['mass_change_scores_2'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="show_scores">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<th width="100%" colspan="2">';
	echo '			<input type="submit" value="'. $lang['mass_change_submit'] .'" class="mainoption" onclick="document.save_mass.submit()">';
	echo '		</th>';
	echo '	</tr>';
	echo '</table>';
	echo '</form>';
}

if ($mode == 'save')
{
	$change_cost = ($_POST['new_cost']) ? $_POST['new_cost'] : $_POST['new_cost'];
	$change_ge_cost = ($_POST['new_ge_cost']) ? $_POST['new_ge_cost'] : $_POST['new_ge_cost'];
	$change_path = ($_POST['new_path']) ? $_POST['new_path'] : $_POST['new_path'];
	$change_jackpot = ($_POST['new_jackpot']) ? $_POST['new_jackpot'] : $_POST['new_jackpot'];
	$change_bonus = ($_POST['new_bonus']) ? $_POST['new_bonus'] : $_POST['new_bonus'];
	$change_reward = ($_POST['new_reward']) ? $_POST['new_reward'] : $_POST['new_reward'];
	$allow_parent = ($_POST['parent_on']) ? $_POST['parent_on'] : $_POST['parent_on'];
	$disallow_parent = ($_POST['parent_off']) ? $_POST['parent_off'] : $_POST['parent_off'];
	$allow_popup = ($_POST['popup_on']) ? $_POST['popup_on'] : $_POST['popup_on'];
	$disallow_popup = ($_POST['popup_off']) ? $_POST['popup_off'] : $_POST['popup_off'];
	$delete_links = ($_POST['remove_links']) ? $_POST['remove_links'] : $_POST['remove_links'];
	$add_links = ($_POST['new_links']) ? $_POST['new_links'] : $_POST['new_links'];
	$remove_cats = ($_POST['remove_cats']) ? $_POST['remove_cats'] : $_POST['remove_cats'];
	$change_height = ($_POST['new_height']) ? $_POST['new_height'] : $_POST['new_height'];
	$change_width = ($_POST['new_width']) ? $_POST['new_width'] : $_POST['new_width'];
	$change_highscores = ($_POST['new_highscores']) ? $_POST['new_highscores'] : $_POST['new_highscores'];
	$change_desc = ($_POST['remove_desc']) ? $_POST['remove_desc'] : $_POST['remove_desc'];
	$change_info = ($_POST['remove_inst']) ? $_POST['remove_inst'] : $_POST['remove_inst'];
	$hide_games = ($_POST['hide_games']) ? $_POST['hide_games'] : $_POST['hide_games'];
	$show_games = ($_POST['show_games']) ? $_POST['show_games'] : $_POST['show_games'];
	$hide_scores = ($_POST['hide_scores']) ? $_POST['hide_scores'] : $_POST['hide_scores'];
	$show_scores = ($_POST['show_scores']) ? $_POST['show_scores'] : $_POST['show_scores'];


		if ($change_path)
			{
		$q = "SELECT game_id, game_name
				FROM ". iNA_GAMES ."";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrowset($r);

			for ($x = 0; $x < sizeof($row); $x++)
				{
			$new_path = $change_path .'/'. $row[$x]['game_name'] .'/';
			$q = "UPDATE ". iNA_GAMES ."
					SET game_path = '$new_path'
					WHERE game_id = '". $row[$x]['game_id'] ."'";
			$db->sql_query($q);

				if (!$row[$x]['game_id'])
					break;
				}
			}

		if ($add_links)
			{
		$q = "SELECT game_id, game_links
				FROM ". iNA_GAMES ."";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrowset($r);

			for ($x = 0; $x < sizeof($row); $x++)
				{
			$new_links = $row[$x]['game_links'];
			$new_links .= $add_links;

			$q = "UPDATE ". iNA_GAMES ."
					SET game_links = '$new_links'
					WHERE game_id = '". $row[$x]['game_id'] ."'";
			$db->sql_query($q);

				if (!$row[$x]['game_id'])
					break;
				}
			}

	$set = '';
	$msg = '';
	$set = array();
		if ((intval($change_cost) >= 0) && (!empty($change_cost) || $change_cost == '0'))
			$set[] = "game_charge = '$change_cost'";

		if ((intval($change_ge_cost) >= 0) && (!empty($change_ge_cost) || $change_ge_cost == '0'))
			$set[] = "game_ge_cost = '$change_ge_cost'";

		if ((intval($change_jackpot) >= 0) && (!empty($change_jackpot) || $change_jackpot == '0'))
			$set[] = "jackpot = '$change_jackpot'";

		if ((intval($change_bonus) >= 0) && (!empty($change_bonus) || $change_bonus == '0'))
			$set[] = "game_bonus = '$change_bonus'";

		if ((intval($change_reward) >= 0) && (!empty($change_reward) || $change_reward == '0'))
			$set[] = "game_reward = '$change_reward'";

		if (($allow_parent == 'on') && (!$disallow_parent))
			$set[] = "game_parent = '1'";

		if (($disallow_parent == 'on') && (!$allow_parent))
			$set[] = "game_parent = '0'";

		if (($allow_popup == 'on') && (!$disallow_popup))
			$set[] = "game_popup = '1'";

		if (($disallow_popup == 'on') && (!$allow_popup))
			$set[] = "game_popup = '0'";

		if ($delete_links == 'on')
			$set[] = "game_links = ''";

		if ($remove_cats == 'on')
			$set[] = "cat_id = ''";

		if ((intval($change_width) >= 0) && (!empty($change_width) || $change_width == '0'))
			$set[] = "win_width = '$change_width'";

		if ((intval($change_height) >= 0) && (!empty($change_height) || $change_height == '0'))
			$set[] = "win_height = '$change_height'";

		if ((intval($change_highscores) >= 0) && (!empty($change_highscores) || $change_highscores == '0'))
			$set[] = "highscore_limit = '$change_highscores'";

		if ($change_desc == 'on')
			$set[] = "game_desc = ''";

		if ($change_info == 'on')
			$set[] = "instructions = ''";

		if (($hide_games == 'on') && (!$show_games))
			$set[] = "disabled = '0'";

		if (($show_games == 'on') && (!$hide_games))
			$set[] = "disabled = '1'";

		if (($hide_scores == 'on') && (!$show_scores))
			$set[] = "game_show_score = '0'";

		if (($show_scores == 'on') && (!$hide_scores))
			$set[] = "game_show_score = '1'";

		for ($x = 0; $x < sizeof($set); $x++)
			$update_sql .= $set[$x] . ((($x + 1) < sizeof($set)) ? ', ' : '');

		$q = "UPDATE ". iNA_GAMES ."
				SET $update_sql
				WHERE game_id > '0'";
		$db->sql_query($q);

		message_die(GENERAL_MESSAGE, sprintf($lang['mass_settings_complete'], '<a href="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'" class="nav">', '</a>'));
		}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
?>