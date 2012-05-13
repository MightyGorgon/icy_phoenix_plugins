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

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	if (empty($config['plugins']['activity']['enabled']))
	{
		return;
	}

	$file = IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . ADM . '/' . basename(__FILE__);
	$module['3200_ACTIVITY']['220_Xtras'] = $file;
	$module['3200_ACTIVITY']['230_Check_Games'] = $file . '?mode=check_game_listing';
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

if ($_GET['mode'] == 'check_game_listing')
{
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	$file = @file("http://phpbb-amod.com/game_list.php");
	if (!$file)
		echo 'Failed To Open Game List. Please Try Again Later.';
	else
		echo 'Game List Found.';
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo "<br><br>";
	if (!$file)
		exit();

	$match = 'You Have These Games';
	$mis_match = 'You Dont Have These Games';
	$not_listed = 'You Have Games I Dont Have Released';
	$match_array = array();
	$mis_match_array = array();
	$not_listed_array = array();

	$amod_list = $file[0];
	$new_list = explode(',', $amod_list);

		for ($x = 0; $x < sizeof($new_list); $x++)
		{
			$q = "SELECT game_id
					FROM ". iNA_GAMES ."
					WHERE game_name = '". $new_list[$x] ."'";
			$r = $db->sql_query($q);
			$exists = $db->sql_fetchrow($r);

			if ($exists['game_id'])
			{
				$match_array[] = $new_list[$x];
			}
			else
			{
				$not_listed_array[] = $new_list[$x];
			}
		}

	$q = "SELECT game_name
			FROM ". iNA_GAMES ."";
	$r = $db->sql_query($q);
	$games = $db->sql_fetchrowset($r);

	for ($x = 0; $x < sizeof($games); $x++)
	{
		if (!strstr($amod_list, $games[$x]['game_name'] .','))
		{
			$mis_match_array[] = $games[$x]['game_name'];
		}
	}

	echo '<table width="100%" class="forumline" align="center">';
	echo '	<tr>';
	echo '		<th width="100%">';
	echo			 $match;
	echo '		</th>';
	echo '	</tr>';
	if (sizeof($match_array) > 0)
	{
		$row_class = '';
		for ($x = 0; $x < sizeof($match_array); $x++)
		{
			$row_class = ip_zebra_rows($row_class);
			echo '	<tr>';
			echo '		<td align="left" width="100%" class="'. $row_class .'">';
			echo 			$match_array[$x];
			echo '		</td>';
			echo '	</tr>';
		}
	}
	else
	{
		echo '	<tr>';
		echo '		<td align="left" width="100%" class="row2">';
		echo '			No matches.';
		echo '		</td>';
		echo '	</tr>';
	}
	echo '	<tr>';
	echo '		<th width="100%">';
	echo 			$not_listed;
	echo '		</th>';
	echo '	</tr>';
	if (sizeof($mis_match_array) > 0)
	{
		$row_class = '';
		for ($x = 0; $x < sizeof($mis_match_array); $x++)
		{
			$row_class = ip_zebra_rows($row_class);
			echo '	<tr>';
			echo '		<td align="left" width="100%" class="' . $row_class .'">';
			echo 			$mis_match_array[$x];
			echo '		</td>';
			echo '	</tr>';
		}
	}
	else
	{
		echo '	<tr>';
		echo '		<td align="left" width="100%" class="row2">';
		echo '			No matches.';
		echo '		</td>';
		echo '	</tr>';
	}
	echo '	<tr>';
	echo '		<th width="100%">';
	echo			 $mis_match;
	echo '		</th>';
	echo '	</tr>';
	if (sizeof($not_listed_array) > 0)
	{
		$row_class = '';
		for ($x = 0; $x < sizeof($not_listed_array); $x++)
		{
			$row_class = ip_zebra_rows($row_class);
			echo '	<tr>';
			echo '		<td class="' . $row_class .'" width="100%">';
			echo			 $not_listed_array[$x];
			echo '		</td>';
			echo '	</tr>';
		}
	}
	else
	{
		echo '	<tr>';
		echo '		<td class="row2" width="100%">';
		echo '			No matches.';
		echo '		</td>';
		echo '	</tr>';
	}
	echo '</table>';
}

	$link = append_sid('admin_ina_xtras.' . PHP_EXT);
	global $table_prefix, $config;
	define("iNA_TROPHY", $table_prefix .'ina_top_scores');
	define("iNA_GAMES", $table_prefix .'ina_games');
	define("CONFIG_TABLE", $table_prefix .'config');

if (($mode == 'main') || !$mode)
		{
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "			". $lang['activity_xtras'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo "<br /><br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_xtras_game_link_msg'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
/* Deletion */
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "				". $lang['auto_delete'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='adjust' action=\"$link\" method=\"post\">";

	$auto_status = $config['ina_delete'];

	if($auto_status == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['select_option'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type=\"radio\" name=\"select\" value=\"1\" $on>&nbsp;&nbsp;". $lang['activate_radio_button'];
	echo "					<br />";
	echo "				<input type=\"radio\" name=\"select\" value=\"0\" $off>&nbsp;&nbsp;". $lang['deactivate_radio_button'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='change'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['apply_changes_button'] ."' onchange='document.adjust.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "				". $lang['bug_fixers'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
/* Delete All Scores */
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='truncate_scores' action='admin_activity." . PHP_EXT . "?sid=". $user->data['session_id'] ."' method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['truncate_scores_table'];
	echo "			</span>";
	echo '				<br />';
	echo "			<span class='gensmall'>";
	echo "				". $lang['truncate_scores_table_e'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='clear_scores'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['truncate_scores_table_s'] ."' onchange='document.truncate_scores.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</form>";
	echo '<br />';
/* Hall Of Fame */
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='phof' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class='gensmall'>";
	echo "				". $lang['hof_acp_title'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='hof'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['hof_acp_button'] ."' onchange='document.phof.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</form>";
/* Re-Sync */
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "<form name='resync' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class='gensmall'>";
	echo "				". $lang['resync_message'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='re_sync'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['resync_button'] ."' onchange='document.resync.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</form>";
	echo "<form name='fix_scores' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class='gensmall'>";
	echo "				". $lang['scores_message'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='scores_update'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['scores_update_button'] ."' onchange='document.fix_scores.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</form>";
	echo "<form name='fix_trophies' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class='gensmall'>";
	echo "				". $lang['trophy_update_message'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='trophies_update'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['trophy_update_button'] ."' onchange='document.fix_trophies.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</form>";
	echo "<form name='fix_trophy_count' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class='gensmall'>";
	echo "				". $lang['trophy_count_message'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='trophy_count_fix'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['reset_trophy_button'] ."' onchange='document.fix_trophy_count.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</form>";
	echo "<form name='delete_all_comments' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class='gensmall'>";
	echo "				". $lang['delete_all_com_mess'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='del_comments'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['delete_comments_message'] ."' onchange='document.delete_all_comments.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</form>";
	echo "<form name='reset_all_jackpots' action=\"$link\" method=\"post\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class='gensmall'>";
	echo "				". $lang['reset_jackpot_mess'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='reset_jackpots'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['reset_jackpot_button'] ."' onchange='document.reset_all_jackpots.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</form>";
	echo "</table>";
	echo "<br /><br />";
/* Config Values */
	echo "<form name='config' action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "				". $lang['extra_config_values'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['max_charge'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='max_charge' value='". $config['ina_default_charge'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['increment'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='increment' value='". $config['ina_default_increment'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['path_for_games'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='g_path' value='". $config['ina_default_g_path'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['bonus_for_games'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='reward' value='". $config['ina_default_g_reward'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['game_height'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='w_height' value='". $config['ina_default_g_height'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['game_width'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='w_width' value='". $config['ina_default_g_width'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['cm_pts_name'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='cash_name' value='". $config['ina_cash_name'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['use_logo'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='use_logo' value='". $config['ina_use_logo'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['use_jackpot'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='use_jackpot' value='". $config['ina_jackpot_pool'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['max_gamble_amount'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='max_gamble' value='". $config['ina_max_gamble'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['def_list_order'] ."<br /></span>";
	echo "			<span class='gensmall'>". $lang['main_pg_order'] ."</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<select name='order_type'>";
	echo "					<option selected value=\"\">". $lang['type_choose'] ."</option>";
	echo "					<option value='1'>". $lang['games_played_A'] ."</option>";
	echo "					<option value='2'>". $lang['games_played_D'] ."</option>";
	echo "					<option value='3'>". $lang['new_add'] ."</option>";
	echo "					<option value='4'>". $lang['old_add'] ."</option>";
	echo "					<option value='5'>". $lang['bonus_A'] ."</option>";
	echo "					<option value='6'>". $lang['bonus_D'] ."</option>";
	echo "					<option value='7'>". $lang['cost_A'] ."</option>";
	echo "					<option value='8'>". $lang['cost_D'] ."</option>";
	echo "					<option value='9'>". $lang['proper_A'] ."</option>";
	echo "					<option value='10'>". $lang['proper_D'] ."</option>";
	echo "				</select>";
	echo "			</span>";
	$current_setting = $config['ina_default_order'];
	if($current_setting == '1') $current = $lang['corder_gpA'];
	if($current_setting == "2") $current = $lang['corder_cpD'];
	if($current_setting == "3") $current = $lang['corder_na'];
	if($current_setting == "4") $current = $lang['corder_oa'];
	if($current_setting == "5") $current = $lang['corder_bA'];
	if($current_setting == "6") $current = $lang['corder_bD'];
	if($current_setting == "7") $current = $lang['corder_cA'];
	if($current_setting == "8") $current = $lang['corder_cD'];
	if($current_setting == "9") $current = $lang['corder_properA'];
	if($current_setting == "10") $current = $lang['corder_properD'];
	echo "			<br /><span class='gensmall'>$current</span>";
	echo "		</td>";
	echo "	</tr>";
	$use_day_limit_restriction = $config['ina_use_max_games_per_day'];
	if($use_day_limit_restriction == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['max_games_played'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='max_played' value='1' $on> ". $lang['radio_yes']. "<br /><input type='radio' name='max_played' value='2' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<center>". $lang['max_games_played_desc'] ."</center>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='max_played_count' value='". $config['ina_max_games_per_day'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	$post_block = $config['ina_post_block'];
	if($post_block == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['req_post_count'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='post_block' value='1' $on> ". $lang['radio_yes']. "<br /><input type='radio' name='post_block' value='2' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<center>". $lang['ify_how_many'] ."</center>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='post_block_count' value='". $config['ina_post_block_count'] ."'>";
	echo "		</td>";
	echo "	</tr>";

	$join_block = $config['ina_join_block'];
	if($join_block == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['req_mem_time'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='join_block' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='join_block' value='2' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<center>". $lang['ify_how_long'] ."</center>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='join_block_count' value='". $config['ina_join_block_count'] ."'><br />Current Setting: ". $config['ina_join_block_count'] ." Day(s).";
	echo "		</td>";
	echo "	</tr>";

	$challenge = $config['ina_challenge'];
	if($challenge == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['act_challenge'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='challenge' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='challenge' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<center>". $lang['admin_xtras_msg_text'] ."</center>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='challenge_msg' value='". $config['ina_challenge_msg'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['sub_chal_mess'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='challenge_m_sub' value='". $config['ina_challenge_sub'] ."'>";
	echo "		</td>";
	echo "	</tr>";

	$trophy_pm = $config['ina_pm_trophy'];
	if($trophy_pm == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['act_pm_trop_loss'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='trophy' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='trophy' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<center>". $lang['admin_xtras_msg_text_1'] ."</center>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='trophy_msg' value='". $config['ina_pm_trophy_msg'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['sub_trop_loss_mess'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='trophy_m_sub' value='". $config['ina_pm_trophy_sub'] ."'>";
	echo "		</td>";
	echo "	</tr>";

	$show_new = $config['ina_use_newest'];
	if($show_new == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['show_new'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='shownew' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='shownew' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<center>". $lang['ify_amt_to_show'] ."</center>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='new_game_limit' value='". $config['ina_new_game_count'] ."'>";
	echo "		</td>";
	echo "	</tr>";

	$button_link = $config['ina_button_option'];
	if($button_link == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['button_link_load_style'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='button_link' value='1' $on> ". $lang['radio_popup'] ."<br /><input type='radio' name='button_link' value='2' $off> ". $lang['radio_parent'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['new_game_length'] ."<span class='gensmall'>". $lang['amt_days_show'] ."</span>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='new_game' value='". $config['ina_new_game_limit'] ."'>";
	echo "		</td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['pop_game_limit'] ."<span class='gensmall'>". $lang['game_req_to_show'] ."</span>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='new_pop' value='". $config['ina_pop_game_limit'] ."'>";
	echo "		</td>";
	echo "	</tr>";

	$rating_reward = $config['ina_use_rating_reward'];
	if($rating_reward == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_use_rating_reward'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='rating_reward' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='rating_reward' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<center>". $lang['admin_use_rating_reward_1'] ."</center>";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='rating_reward_value' value='". $config['ina_rating_reward'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['online_list_text'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='online_list_text' value='". $config['ina_online_list_text'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['online_list_color'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" class=\"post\" name='online_list_color' value='". $config['ina_online_list_color'] ."'>";
	echo "		</td>";
	echo "	</tr>";
	$daily_game = $config['ina_use_daily_game'];
	if($daily_game == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['god_admin_one'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='daily_game' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='daily_game' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$random_daily = $config['ina_daily_game_random'];
	if($random_daily == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['god_admin_three'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='random_daily' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='random_daily' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['god_admin_two'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo '				<select name="daily_game_id">';
	$q = "SELECT game_id, proper_name
			FROM ". iNA_GAMES ."
			WHERE game_id > '0'
			ORDER BY proper_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	if ($row['game_id'] == $config['ina_daily_game_id'])
		$selected = 'selected ';
	else
		$selected = '';
	echo '					<option '. $selected .' value="'. $row['game_id'] .'">'. $row['proper_name'] .'</option>';
		}
	echo '				</select>';
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
/* Toggle Values */
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "				". $lang['extra_toggle_values'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td width='50%' align='center' valign='top' class='row2'>";

	echo "<table align=\"center\" valign=\"top\" border=\"0\" width='100%'>";
	$guests_allowed = $config['ina_guest_play'];
	if($guests_allowed == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td align='left' valign='top' width='80%' class='row2'>";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['allow_guest'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='left' valign='top' width='20%' class='row2'>";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='guests' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='guests' value='2' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";

	$show_online_list = $config['ina_use_online'];
	if($show_online_list == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['act_games_online'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='online_list' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='online_list' value='2' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$disable_cheat = $config['ina_disable_cheat'];
	if($disable_cheat == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['turn_cheat_off'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='cheat_fix' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='cheat_fix' value='2' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$show_profile_info = $config['ina_show_view_profile'];
	if($show_profile_info == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['show_trop_n_profile'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='viewprofile' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='viewprofile' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$show_topic_info = $config['ina_show_view_topic'];
	if($show_topic_info == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['show_stats_n_topic'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='viewtopic' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='viewtopic' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$use_shoutbox = $config['ina_use_shoutbox'];
	if ($use_shoutbox == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_use_shoutbox'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='shoutbox' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='shoutbox' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$disable_games = $config['ina_disable_everything'];
	if($disable_games == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['disable_everything'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_disable'] ."</i></span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='disable_everything' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='disable_everything' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$disable_trophies_page = $config['ina_disable_trophy_page'];
	if($disable_trophies_page == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['disable_trophy_page'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_disable'] ."</i></span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='disable_trophy_page' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='disable_trophy_page' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";

	echo "		</td>";
	echo "		<td width='50%' align='center' valign='top' class='row2'>";

	echo "<table align=\"center\" valign=\"top\" border=\"0\" width='100%'>";
	$disable_comments_page = $config['ina_disable_comments_page'];
	if($disable_comments_page == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td align='left' valign='top' width='80%' class='row2'>";
	echo "			<span class=\"genmed\">". $lang['disable_comments'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_disable'] ."</i></span>";
	echo "		</td>";
	echo "		<td align='left' valign='top' width='20%' class='row2'>";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='disable_comments_page' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='disable_comments_page' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$disable_gamble_page = $config['ina_disable_gamble_page'];
	if($disable_gamble_page == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['disable_gamble_page'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_disable'] ."</i></span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='disable_gamble_page' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='disable_gamble_page' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$disable_challenge_page = $config['ina_disable_challenges_page'];
	if($disable_challenge_page == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['disable_chall_page'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_disable'] ."</i></span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='disable_challenge_page' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='disable_challenge_page' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$disable_top5_page = $config['ina_disable_top5_page'];
	if($disable_top5_page == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['disable_top_five'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_disable'] ."</i></span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='disable_top5_page' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='disable_top5_page' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$use_trophy_king = $config['ina_use_trophy'];
	if($use_trophy_king == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['use_trophy_king'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_use_trophy_king'] ."</i></span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='use_trophy_king' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='use_trophy_king' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$disable_submit_score = $config['ina_disable_submit_scores_m'];
	if($disable_submit_score == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['disable_score_submit_m'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_disable_score_submit_m'] ."</i></span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='disable_submit_score' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='disable_submit_score' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$disable_submit_score_g = $config['ina_disable_submit_scores_g'];
	if($disable_submit_score_g == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['disable_score_submit_g'] ."<br /></span>";
	echo "			<span class='gensmall'>&nbsp;<i>". $lang['why_disable_score_submit_g'] ."</i></span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='disable_submit_score_g' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='disable_submit_score_g' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	$force_g_register = $config['ina_force_registration'];
	if ($force_g_register == '1')
		{
	$on = "checked='checked'";
	$off = "";
		}
	else
		{
	$on = "";
	$off = "checked='checked'";
		}
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">". $lang['admin_guest_view'] ."</span>";
	echo "		</td>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				<input type='radio' name='force_reg' value='1' $on> ". $lang['radio_yes'] ."<br /><input type='radio' name='force_reg' value='0' $off> ". $lang['radio_no'] ."";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";

	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td align='center' valign='top' class='row2' colspan=\"2\">";

	echo "<table align=\"center\" valign=\"top\" border=\"0\" width='100%'>";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='sconfig'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_xtras_save_button'] ."' onchange='document.config.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";

	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
		}

	if ($mode == 'hof')
		{
	$q = "TRUNCATE ". $table_prefix ."ina_hall_of_fame";
	$db->sql_query($q);

	$q = "SELECT *
			FROM ". iNA_GAMES ."";
	$r = $db->sql_query($q);
	$game_info = $db->sql_fetchrowset($r);

	$q = "SELECT *
			FROM ". $table_prefix ."ina_top_scores";
	$r = $db->sql_query($q);
	$score_info = $db->sql_fetchrowset($r);

	$adjusted = 0;
	unset($game_id, $hof_u, $hof_g, $hof_d, $hof_s);
		for ($a = 0; $a <= sizeof($score_info); $a++)
			{
			for ($b = 0; $b <= sizeof($game_info); $b++)
				{
				if ($score_info[$a]['game_name'] == $game_info[$b]['game_name'])
					{
				$game_id = $game_info[$b]['game_id'];
					}
				}
		$hof_u = $score_info[$a]['player'];
		$hof_d = $score_info[$a]['date'];
		$hof_s = $score_info[$a]['score'];
		$hof_g = $game_id;

			if ($hof_g > '0')
				{
			$q = "INSERT INTO ". $table_prefix ."ina_hall_of_fame
					VALUES ('". $hof_g ."', '". $hof_u ."', '". $hof_s ."', '". $hof_d ."', '', '', '')";
			$db->sql_query($q);
				}

		$adjusted++;
			if (!$hof_g) break;
			}
		message_die(GENERAL_MESSAGE, str_replace('%X%', $adjusted - 1, $lang['hof_finished']), $lang['success']);
		}

	if($mode == "trophy_count_fix")
		{
	$sql = "UPDATE ". USERS_TABLE ."
			SET user_trophies = '0'
			WHERE user_trophies > '0'";
	$r = $db->sql_query($sql);

	message_die(GENERAL_MESSAGE, $lang['all_trophy_reset'], $lang['success_message']);
		}

	if($mode == "scores_update")
		{
	$sql = "CREATE TABLE ". $table_prefix ."scores_fixer
			(`game_name` varchar(255) default NULL,
			`player` varchar(40) default NULL,
			`score` FLOAT(10,2) DEFAULT '0' NOT NULL,
			`date` int(11) default NULL)";
	$r = $db->sql_query($sql);

	$f = 0;

	$q = "SELECT *
			FROM ". iNA_SCORES ."
			GROUP BY player, game_name";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$game_name = $row['game_name'];
	$score = $row['score'];
	$player = $row['player'];
	$date = $row['date'];

	$q3 = "INSERT INTO ". $table_prefix ."scores_fixer
			 VALUES ('$game_name', '". $db->sql_escape($player) ."', '$score', '$date')";
	$r3 = $db->sql_query($q3);

	$f++;
		}

	$q = "TRUNCATE ". iNA_SCORES;
	$r = $db->sql_query($q);

	$f = 0;

	$q = "SELECT *
			FROM ". $table_prefix ."scores_fixer";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$game_name = $row['game_name'];
	$score = $row['score'];
	$player = $row['player'];
	$date = $row['date'];

	$q3 = "INSERT INTO ". iNA_SCORES ."
			 VALUES ('$game_name', '". $db->sql_escape($player) ."', '$score', '$date')";
	$r3 = $db->sql_query($q3);

	$f++;
		}

	$q = "DROP TABLE ". $table_prefix ."scores_fixer";
	$r = $db->sql_query($q);

	message_die(GENERAL_MESSAGE, $f . $lang['scores_updated'], $lang['success_message']);
		}

	if($mode == "del_comments")
		{
	$q = "TRUNCATE ". $table_prefix ."ina_trophy_comments";
	$r = $db->sql_query($q);
	message_die(GENERAL_MESSAGE, $lang['all_comments_deleted'], $lang['success_message']);
		}

	if($mode == "reset_jackpots")
		{
	$q = "UPDATE ". iNA_GAMES ."
			SET jackpot = '". $config['ina_jackpot_pool'] ."'
			WHERE jackpot <> '". $config['ina_jackpot_pool'] ."'";
	$db->sql_query($q);
	message_die(GENERAL_MESSAGE, $lang['reset_jackpot_success'], $lang['success_message']);
		}

	if($mode == "trophies_update")
		{
		$i = 0;
		$q =  "SELECT *
				 FROM ". iNA_GAMES ."
				 GROUP BY game_id";
		$r = $db->sql_query($q);
		while($row = $db->sql_fetchrow($r))
			{
		$games_name = $row['game_name'];
		$games_order = $row['reverse_list'];

		if ($games_order)
			{
		$min_max = 'MIN';
			}
		else
			{
		$min_max = 'MAX';
			}

		$q1 =  "SELECT $min_max(score) AS highest
				FROM ". iNA_SCORES ."
				WHERE game_name = '$games_name'";
		$r1 = $db->sql_query($q1);
		$row1 = $db->sql_fetchrow($r1);
		$score_pass = $row1['highest'];

		$q2 =  "SELECT *
				FROM ". iNA_SCORES ."
				WHERE game_name = '$games_name'
				AND score = '$score_pass'
				ORDER BY date DESC
				LIMIT 0, 1";
		$r2 = $db->sql_query($q2);
		$row2 = $db->sql_fetchrow($r2);
		$who = $row2['player'];
		$date = $row2['date'];

		$q3 = get_users_sql($who, false, false, true, false);
		$r3 = $db->sql_query($q3);
		$row3 = $db->sql_fetchrow($r3);
		$who_id = $row3['user_id'];

		$q5 =  "UPDATE ". iNA_TROPHY ."
				SET player = '$who_id', score = '$score_pass', date = '$date'
				WHERE game_name = '$games_name'";
		$r5 = $db->sql_query($q5);
		$i++;
			}
		message_die(GENERAL_MESSAGE, $lang['trophy_tab_updated']. $i, $lang['success_message']);
		}

		if ($mode == "sconfig")
			{
		$nmc = $_POST['max_charge'];
		$ni = $_POST['increment'];
		$ngp = $_POST['g_path'];
		$ngr = $_POST['reward'];
		$ngl = $_POST['new_game_limit'];
		$ngpop = $_POST['new_pop'];
		$ngn = $_POST['new_game'];
		$ngv = $_POST['guests'];
		$nwh = $_POST['w_height'];
		$nww = $_POST['w_width'];
		$nbl = $_POST['button_link'];
		$npb = $_POST['post_block'];
		$npc = $_POST['post_block_count'];
		$njb = $_POST['join_block'];
		$njc = $_POST['join_block_count'];
		$nco = $_POST['challenge'];
		$ncm = $_POST['challenge_msg'];
		$ncs = $_POST['challenge_m_sub'];
		$nto = $_POST['trophy'];
		$ntm = $_POST['trophy_msg'];
		$nts = $_POST['trophy_m_sub'];
		$sng = $_POST['shownew'];
		$aol = $_POST['online_list'];
		$dcf = $_POST['cheat_fix'];
		$show_profile = $_POST['viewprofile'];
		$show_topic = $_POST['viewtopic'];
		$order_type = $_POST['order_type'];
		$disable_all = $_POST['disable_everything'];
		$cash_name = $_POST['cash_name'];
		$disable_trophy = $_POST['disable_trophy_page'];
		$disable_comments = $_POST['disable_comments_page'];
		$disable_gamble = $_POST['disable_gamble_page'];
		$disable_challenges = $_POST['disable_challenge_page'];
		$disable_top5 = $_POST['disable_top5_page'];
		$use_trophy_king = $_POST['use_trophy_king'];
		$use_logo = $_POST['use_logo'];
		$max_games_per_day = $_POST['max_played_count'];
		$use_max_games = $_POST['max_played'];
		$disable_score_submit = $_POST['disable_submit_score'];
		$disable_score_submit_g = $_POST['disable_submit_score_g'];
		$rating_reward = $_POST['rating_reward'];
		$rating_reward_amount = $_POST['rating_reward_value'];
		$use_jackpot = (is_numeric($_POST['use_jackpot'])) ? $_POST['use_jackpot'] : '0';
		$online_text = $_POST['online_list_text'];
		$online_color = $_POST['online_list_color'];
		$max_gamble = $_POST['max_gamble'];
		$use_daily_game = intval($_POST['daily_game']);
		$use_daily_random = intval($_POST['random_daily']);
		$use_daily_game_id = intval($_POST['daily_game_id']);
		$force_registration = intval($_POST['force_reg']);
		$use_shoutbox = intval($_POST['shoutbox']);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$force_registration'
				WHERE config_name = 'ina_force_registration'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$use_shoutbox'
				WHERE config_name = 'ina_use_shoutbox'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$use_daily_game'
				WHERE config_name = 'ina_use_daily_game'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$use_daily_random'
				WHERE config_name = 'ina_daily_game_random'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$use_daily_game_id'
				WHERE config_name = 'ina_daily_game_id'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$max_gamble'
				WHERE config_name = 'ina_max_gamble'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$rating_reward'
				WHERE config_name = 'ina_use_rating_reward'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$disable_score_submit'
				WHERE config_name = 'ina_disable_submit_scores_m'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$disable_score_submit_g'
				WHERE config_name = 'ina_disable_submit_scores_g'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$use_max_games'
				WHERE config_name = 'ina_use_max_games_per_day'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$use_jackpot'
				WHERE config_name = 'ina_jackpot_pool'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$disable_trophy'
				WHERE config_name = 'ina_disable_trophy_page'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$disable_comments'
				WHERE config_name = 'ina_disable_comments_page'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$disable_gamble'
				WHERE config_name = 'ina_disable_gamble_page'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$disable_challenges'
				WHERE config_name = 'ina_disable_challenges_page'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$use_trophy_king'
				WHERE config_name = 'ina_use_trophy'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$disable_top5'
				WHERE config_name = 'ina_disable_top5_page'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$disable_all'
				WHERE config_name = 'ina_disable_everything'";
		$r = $db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$show_profile'
				WHERE config_name = 'ina_show_view_profile'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$show_topic'
				WHERE config_name = 'ina_show_view_topic'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$dcf'
				WHERE config_name = 'ina_disable_cheat'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$aol'
				WHERE config_name = 'ina_use_online'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$sng'
				WHERE config_name = 'ina_use_newest'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$nco'
				WHERE config_name = 'ina_challenge'";
		$db->sql_query($q);

		$q = "UPDATE ". CONFIG_TABLE ."
				SET config_value = '$nto'
				WHERE config_name = 'ina_pm_trophy'";
		$db->sql_query($q);

			if (!empty($online_text))
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$online_text'
					WHERE config_name = 'ina_online_list_text'";
			$db->sql_query($q);
				}

			if (!empty($online_color))
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$online_color'
					WHERE config_name = 'ina_online_list_color'";
			$db->sql_query($q);
				}

			if ($rating_reward_amount > 0)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$rating_reward_amount'
					WHERE config_name = 'ina_rating_reward'";
			$db->sql_query($q);
				}

			if ($max_games_per_day > 1)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$max_games_per_day'
					WHERE config_name = 'ina_max_games_per_day'";
			$db->sql_query($q);
				}

			if ($use_logo)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$use_logo'
					WHERE config_name = 'ina_use_logo'";
			$db->sql_query($q);
				}

			if ($cash_name)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$cash_name'
					WHERE config_name = 'ina_cash_name'";
			$db->sql_query($q);
				}

			if ($order_type)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$order_type'
					WHERE config_name = 'ina_default_order'";
			$db->sql_query($q);
				}

			if (is_numeric($ngn))
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ngn'
					WHERE config_name = 'ina_new_game_limit'";
			$db->sql_query($q);
				}

			if (is_numeric($ngpop))
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ngpop'
					WHERE config_name = 'ina_pop_game_limit'";
			$db->sql_query($q);
				}

			if (is_numeric($ngl))
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ngl'
					WHERE config_name = 'ina_new_game_count'";
			$db->sql_query($q);
				}

			if ($nts)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$nts'
					WHERE config_name = 'ina_pm_trophy_sub'";
			$db->sql_query($q);
				}

			if ($ntm)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ntm'
					WHERE config_name = 'ina_pm_trophy_msg'";
			$db->sql_query($q);
				}

			if ($ncs)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ncs'
					WHERE config_name = 'ina_challenge_sub'";
			$db->sql_query($q);
				}

			if ($ncm)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ncm'
					WHERE config_name = 'ina_challenge_msg'";
			$db->sql_query($q);
				}

			if ($njb)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$njb'
					WHERE config_name = 'ina_join_block'";
			$db->sql_query($q);
				}

			if ($njc)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$njc'
					WHERE config_name = 'ina_join_block_count'";
			$db->sql_query($q);
				}

			if ($npb)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$npb'
					WHERE config_name = 'ina_post_block'";
			$db->sql_query($q);
				}

			if ($npc)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$npc'
					WHERE config_name = 'ina_post_block_count'";
			$db->sql_query($q);
				}

			if ($nbl)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$nbl'
					WHERE config_name = 'ina_button_option'";
			$db->sql_query($q);
				}

			if ($ngv)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ngv'
					WHERE config_name = 'ina_guest_play'";
			$db->sql_query($q);
				}

			if ($nmc)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$nmc'
					WHERE config_name = 'ina_default_charge'";
			$db->sql_query($q);
				}

			if ($ni)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ni'
					WHERE config_name = 'ina_default_increment'";
			$db->sql_query($q);
				}

			if ($ngp)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ngp'
					WHERE config_name = 'ina_default_g_path'";
			$db->sql_query($q);
				}

			if ($ngr)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$ngr'
					WHERE config_name = 'ina_default_g_reward'";
			$db->sql_query($q);
				}

			if ($nwh)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$nwh'
					WHERE config_name = 'ina_default_g_height'";
			$db->sql_query($q);
				}

			if ($nww)
				{
			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$nww'
					WHERE config_name = 'ina_default_g_width'";
			$db->sql_query($q);
				}

	message_die(GENERAL_MESSAGE, $lang['config_updated'], $lang['success_message']);
	}

		if ($mode == "re_sync")
			{
		$i = 0;
		$q = "SELECT *
				FROM ". iNA_GAMES ."
				WHERE game_id <> '0'";
		$r = $db->sql_query($q);
			while ($row = $db->sql_fetchrow($r))
				{

			$q1 = "SELECT *
					 FROM ". iNA_TROPHY ."
					 WHERE game_name = '". $row['game_name'] ."'";
			$r1 = $db->sql_query($q1);
			$row1 = $db->sql_fetchrow($r1);

				if (!$row1['game_name'])
					{
				$q2 = "INSERT INTO ". iNA_TROPHY ."
						 VALUES ('". $row['game_name'] ."', '". $user->data['user_id'] ."', '1', '". time() ."')";
				$db->sql_query($q2);
					}
			$i++;
				}
			message_die(GENERAL_MESSAGE, $lang['tables_updated'] . $i . $lang['games_fixed'], $lang['success_message']);
			}

		if ($mode == "change")
			{
		$to_do = $_POST['select'];

			$q = "UPDATE ". CONFIG_TABLE ."
					SET config_value = '$to_do'
					WHERE config_name = 'ina_delete'";
			$db->sql_query($q);

		if ($to_do == '1')
			message_die(GENERAL_MESSAGE, $lang['auto_del_1'] . $link . $lang['auto_del_2'], $lang['success_message']);
		elseif ($to_do == '0')
			message_die(GENERAL_MESSAGE, $lang['auto_del_3'] . $link . $lang['auto_del_2'], $lang['success_message']);
		else
			message_die(GENERAL_ERROR, $lang['error_saving'] . $link . $lang['auto_del_2'], $lang['error_message']);
		}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
?>