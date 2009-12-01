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
	$module['3000_ACTIVITY']['210_Scores_Editor'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

define('INA_CATEGORY', $table_prefix .'ina_categories');
define('INA_CATEGORY_DATA', $table_prefix .'ina_categories_data');
define('INA_CATEGORY_MAIN', $table_prefix .'ina_main_categories');
define('INA_TROPHY', $table_prefix .'ina_top_scores');
$link = append_sid('admin_ina_scores_edit.' . PHP_EXT);

echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
echo "	<tr>";
echo "		<th colspan=\"2\">";
echo "			". $lang['admin_scores_1'];
echo "		</th>";
echo "	</tr>";
echo "</table>";
echo "<br /><br />";

if(($mode == 'main') || !$mode)
{
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td align='left' valign='top' width='100%' class='row2'>";
	echo "			<span class='gensmall'>";
	echo "				". $lang['admin_scores_2'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br /><br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_scores_3'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='edit_score' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_scores_4'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='game_selected'>";
	echo "				<option selected value=\"\">". $lang['admin_scores_5'] ."</option>";

	$q = "SELECT game_name, proper_name
			FROM ". iNA_GAMES ."
			WHERE game_id > '0'
			ORDER BY proper_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$game_name = $row['proper_name'];
	$game_id = $row['game_name'];
	echo "				<option value='". $game_id ."'>". $game_name ."</option>";
		}
	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='edit_game_score'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_scores_6'] ."' onchange='document.edit_score.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_scores_7'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='edit_trophy' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_scores_4'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='trophy_game_selected'>";
	echo "				<option selected value=\"\">". $lang['admin_scores_5'] ."</option>";

	$q = "SELECT proper_name, game_name
			FROM ". iNA_GAMES ."
			WHERE game_id > '0'
			ORDER BY proper_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$game_name = $row['proper_name'];
	$game_name2 = $row['game_name'];
	echo "				<option value='". $game_name2 ."'>". $game_name ."</option>";
		}
	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='edit_trophy_game_score'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_scores_8'] ."' onchange='document.edit_trophy.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_scores_20'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='delete_players_scores' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_scores_11'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='delete_id'>";
	echo "				<option selected value=\"\">". $lang['admin_scores_12'] ."</option>";

	$q = "SELECT *
			FROM ". iNA_SCORES ."
			WHERE score > '0'
			GROUP BY player";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$play_name = $row['player'];

	echo "				<option value='". $play_name ."'>". $play_name ."</option>";
		}
	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='delete_scores'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_scores_21'] ."' onchange='document.delete_players_scores.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";
		}



	if($mode == "delete_scores")
		{
	$who = $_POST['delete_id'];

	$q = "DELETE FROM ". iNA_SCORES ."
			WHERE player = '$who'";
	$r = $db->sql_query($q);

		message_die(GENERAL_ERROR, $lang['admin_scores_22'], $lang['admin_scores_18']);
		}

	if($mode == "edit_game_score")
		{
	$game = $_POST['game_selected'];
	if(!$game) message_die(GENERAL_ERROR, $lang['admin_scores_9'], $lang['admin_scores_10']);

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='edit_trophy_player' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_scores_11'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='player_selected'>";
	echo "				<option selected value=\"\">". $lang['admin_scores_12'] ."</option>";

	$q = "SELECT player
			FROM ". iNA_SCORES ."
			WHERE game_name = '$game'
			GROUP BY player";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$player_name = $row['player'];
	echo "				<option value='". $player_name ."'>". $player_name ."</option>";
		}
	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='edit_player'>";
	echo "			<input type=\"hidden\" name='game_selected' value='$game'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_scores_13'] ."' onchange='document.edit_trophy_player.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
		}

	if($mode == "edit_player")
		{
	$player = $_POST['player_selected'];
	$game = $_POST['game_selected'];

	$q = "SELECT *
			FROM ". iNA_SCORES ."
			WHERE game_name = '$game'
			AND player = '$player'
			GROUP BY player
			ORDER BY score ASC
			LIMIT 0, 1";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$player_name = $row['player'];
	$player_score = $row['score'];
	$new_lang = str_replace("%p%", $player_name, $lang['admin_scores_14']);

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='save_score' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". str_replace("%g%", $game, $new_lang);
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". number_format($player_score);
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". str_replace("%p%", $player_name, $lang['admin_scores_15']);
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" name='new_score' value='$player_score' class=\"post\">";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='save_new_score'>";
	echo "			<input type=\"hidden\" name='game_selected' value='$game'>";
	echo "			<input type=\"hidden\" name='player_selected' value='$player_name'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_scores_16'] ."' onchange='document.save_score.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
		}

	if($mode == "save_new_score")
		{
	$game = $_POST['game_selected'];
	$player = $_POST['player_selected'];
	$score = $_POST['new_score'];

	$q = "SELECT *
			FROM ". INA_TROPHY ."
			WHERE game_name = '". $game ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$trophy_id = $row['player'];
	$trophy_sc = $row['score'];

	$q = "SELECT user_id
			FROM ". USERS_TABLE ."
			WHERE username = '". $player ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$player_id = $row['user_id'];

		if($trophy_id == $player_id)
			{
		$q = "UPDATE ". iNA_SCORES ."
				SET score = '". $score ."'
				WHERE player = '". $player ."'
				AND game_name = '". $game ."'";
		$r = $db->sql_query($q);

		$q = "UPDATE ". INA_TROPHY ."
				SET score = '". $score ."'
				WHERE player = '". $player_id ."'
				AND game_name = '". $game ."'";
		$r = $db->sql_query($q);
			}
		else
			{
		$q = "UPDATE ". iNA_SCORES ."
				SET score = '". $score ."'
				WHERE player = '". $player ."'
				AND game_name = '". $game ."'";
		$r = $db->sql_query($q);
			}
	message_die(GENERAL_MESSAGE, $player ."'". $lang['admin_scores_17'], $lang['admin_scores_18']);
		}

	if($mode == "edit_trophy_game_score")
		{
	$game = $_POST['trophy_game_selected'];
	if(!$game) message_die(GENERAL_ERROR, $lang['admin_scores_19'], $lang['admin_scores_10']);

	$q = "SELECT *
			FROM ". INA_TROPHY ."
			WHERE game_name = '$game'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$player_score = $row['score'];
	$player_id = $row['player'];

	$q = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $player_id ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$player = $row['username'];
	$new_lang = str_replace("%p%", $player, $lang['admin_scores_14']);

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='save_score' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". str_replace("%g%", $game, $new_lang);
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". number_format($player_score);
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". str_replace("%p%", $player, $lang['admin_scores_15']);
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" name='new_score' value='$player_score' class=\"post\">";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='save_new_t_score'>";
	echo "			<input type=\"hidden\" name='game_selected' value='$game'>";
	echo "			<input type=\"hidden\" name='player_selected' value='$player'>";
	echo "			<input type=\"hidden\" name='player_id_selected' value='$player_id'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_scores_16'] ."' onchange='document.save_score.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
		}

	if($mode == "save_new_t_score")
		{
	$game = $_POST['game_selected'];
	$player = $_POST['player_selected'];
	$score = $_POST['new_score'];
	$id = $_POST['player_id_selected'];

	$q = "SELECT *
			FROM ". INA_TROPHY ."
			WHERE game_name = '". $game ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$trophy_id = $row['player'];
	$trophy_sc = $row['score'];

		if($trophy_id == $id)
			{
		$q = "UPDATE ". iNA_SCORES ."
				SET score = '". $score ."'
				WHERE player = '". $player ."'
				AND game_name = '". $game ."'";
		$r = $db->sql_query($q);

		$q = "UPDATE ". INA_TROPHY ."
				SET score = '". $score ."'
				WHERE player = '". $id ."'
				AND game_name = '". $game ."'";
		$r = $db->sql_query($q);
			}
		else
			{
		$q = "UPDATE ". iNA_SCORES ."
				SET score = '". $score ."'
				WHERE player = '". $player ."'
				AND game_name = '". $game ."'";
		$r = $db->sql_query($q);
			}
	message_die(GENERAL_MESSAGE, $player ."'". $lang['admin_scores_17'], $lang['admin_scores_18']);
		}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
?>