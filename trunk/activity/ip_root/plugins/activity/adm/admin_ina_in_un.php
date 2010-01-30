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
	$module['3000_ACTIVITY']['190_DB_Adjustments'] = $file;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

define('iNA_TOP_SCORES', $table_prefix .'ina_top_scores');
define('iNA_BAN', $table_prefix .'ina_ban');
define('iNA_CHEAT', $table_prefix .'ina_cheat_fix');
define('iNA_CAT', $table_prefix .'ina_categories');
define('iNA_CAT_DATA', $table_prefix .'ina_categories_data');
define('iNA_CHALLENGE', $table_prefix .'ina_challenge_tracker');
define('iNA_CHALLENGE_USERS', $table_prefix .'ina_challenge_users');
define('iNA_TROPHY_COMMENTS', $table_prefix .'ina_trophy_comments');
define('iNA', $table_prefix .'ina_data');
define('iNA_SCORES', $table_prefix .'ina_scores');
define('iNA_GAMES', $table_prefix .'ina_games');
define('iNA_SESSIONS', $table_prefix .'ina_sessions');
define('iNA_LAST_PLAYED', $table_prefix .'ina_last_game_played');
define('iNA_GAMBLE', $table_prefix .'ina_gamble');
define('iNA_GAMBLE_PROGRESS', $table_prefix .'ina_gamble_in_progress');
define('iNA_RATING', $table_prefix .'ina_rating_votes');
define('iNA_FAVORITES', $table_prefix .'ina_favorites');
define('iNA_HOF', $table_prefix .'ina_hall_of_fame');
define('iNA_CHAT', $table_prefix .'ina_chat');

$link = append_sid('admin_ina_in_un.' . PHP_EXT);

echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
echo "	<tr>";
echo "		<th colspan=\"2\">";
echo "			". $lang['admin_db_1'];
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
	echo "				". $lang['admin_db_2'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br /><br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_db_3'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='new_install' action=\"$link\" method=\"post\">";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='fresh_install'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_db_4'] ."' onchange='new_install.edit_trophy.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_db_5'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='upgrade' action=\"$link\" method=\"post\">";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<select name='version'>";
	echo "				<option selected value=\"\">". $lang['admin_db_6'] ."</option>";
	echo "				<option value='1'>". $lang['admin_db_7'] ."</option>";
	echo "				<option value='2'>". $lang['admin_db_8'] ."</option>";
	echo "				<option value='3'>". $lang['admin_db_9'] ."</option>";
	echo "				<option value='4'>". $lang['admin_db_10'] ."</option>";
	echo "				<option value='5'>". $lang['admin_db_11'] ."</option>";
	echo "				<option value='6'>". $lang['admin_db_21'] ."</option>";
	echo "				<option value='7'>". $lang['admin_db_22'] ."</option>";
	echo "				<option value='8'>". $lang['admin_db_23'] ."</option>";
	echo "				<option value='9'>". $lang['admin_db_24'] ."</option>";
	echo "				<option value='10'>". $lang['admin_db_25'] ."</option>";
	echo "				<option value='11'>". $lang['admin_db_26'] ."</option>";
	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='upgrading'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_db_12'] ."' onchange='upgrade.edit_trophy.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_db_13'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='uninstall' action=\"$link\" method=\"post\">";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='remove'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_db_14'] ."' onchange='uninstall.edit_trophy.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
		}

	if ($mode == "upgrading" || $mode == "fresh_install" || $mode == 'remove')
		{
		if (($mode == "fresh_install" || $mode == "remove") && ($mode != "upgrading"))
			{
	$sql = array();
	$sql[] = "DROP TABLE IF EXISTS ". iNA .", ". iNA_GAMES .", ". iNA_SCORES .", ". iNA_TOP_SCORES .", ". iNA_BAN .", ". iNA_CHEAT .", ". iNA_CAT .", ". iNA_CAT_DATA .", ". iNA_CHALLENGE .", ". iNA_CHALLENGE_USERS .", ". iNA_TROPHY_COMMENTS .", ". iNA_SESSIONS .", ". iNA_LAST_PLAYED .", ". iNA_GAMBLE .", ". iNA_GAMBLE_PROGRESS .", ". iNA_RATING .", ". iNA_FAVORITES .", ". iNA_HOF .", ". iNA_CHAT ."";

	$sql[] = "ALTER TABLE ". USERS_TABLE ."
			DROP `ina_last_playtype`,
			DROP `ina_games_played`,
			DROP `user_trophies`,
			DROP `ina_cheat_fix`,
			DROP `ina_games_today`,
			DROP `ina_last_visit_page`,
			DROP `ina_game_playing`,
			DROP `ina_game_pass`,
			DROP `ina_games_pass_day`,
			DROP `ina_time_playing`,
			DROP `ina_char_name`,
			DROP `ina_char_age`,
			DROP `ina_char_from`,
			DROP `ina_char_intrests`,
			DROP `ina_char_img`,
			DROP `ina_char_gender`,
			DROP `ina_char_ge`,
			DROP `ina_char_name_effects`,
			DROP `ina_char_title_effects`,
			DROP `ina_char_saying_effects`,
			DROP `ina_char_views`,
			DROP `ina_char_title`,
			DROP `ina_char_saying`,
			DROP `ina_settings`;";

	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_email_sent';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_rating_reward';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_rating_reward';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_use_logo';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_use_max_games_per_day';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_max_games_per_day';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_version';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_submit_scores_m';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_submit_scores_g';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_trophy_king';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_trophy_king_old';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_use_trophy';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'default_reward_dbfield';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'default_cash';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'use_rewards_mod';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'use_cash_system';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'report_cheater';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_default_order';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'warn_cheater';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'use_point_system';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'use_gamelib';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'games_path';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'gamelib_path';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'use_gk_shop';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'use_allowance_system';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'games_per_page';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'current_ina_date';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_delete';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_default_charge';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_default_increment';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_default_g_path';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_default_g_reward';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_default_g_height';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_default_g_width';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_guest_play';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_button_option';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_post_block';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_post_block_count';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_join_block';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_join_block_count';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_challenge';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_pm_trophy';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_challenge_msg';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_challenge_sub';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_pm_trophy_msg';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_pm_trophy_sub';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'challenges_sent';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_new_game_limit';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_pop_game_limit';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_new_game_count';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_use_newest';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_use_online';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_cheat';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_show_view_profile';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_show_view_topic';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_default_order';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_cash_name';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_top5_page';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_challenges_page';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_gamble_page';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_comments_page';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_trophy_page';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_disable_everything';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_jackpot_pool';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_online_list_color';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_online_list_text';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_max_gamble';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_daily_game_id';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_daily_game_date';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_daily_game_random';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_use_daily_game';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_use_shoutbox';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_force_registration';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_change_char_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_change_gender_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_change_age_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_change_name_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_change_from_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_change_intrests_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_ge_per_game';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_ge_per_beat_score';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_ge_per_trophy';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_show_viewtopic';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_show_viewprofile';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_change_title_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_change_saying_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_name_effects_costs';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_title_effects_costs';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_char_saying_effects_costs';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_game_pass_cost';";
	$sql[] = "DELETE FROM ". CONFIG_TABLE ." WHERE config_name = 'ina_game_pass_length';";

			for ($b = 0; $b < sizeof($sql); $b++)
			{
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql[$b]);
				$db->sql_return_on_error(false);
				if (!$result)
				{
						$error = $db->sql_error();
						echo $sql[$b].'<br /><b>Error: </b>'. $error['message'] .'<br /><br />';
				}
				else
				{
					echo $sql[$b].'<br /><b>Successfully Completed</b><br /><br />';
				}
			}
		} #==== Close fresh install/remove codes

		if ($mode != 'remove')
			{
		$sql = array();

		$sql[] = "CREATE TABLE `". iNA_HOF ."` (
				`game_id` mediumint(10) NOT NULL default '0',
				`current_user_id` mediumint(10) NOT NULL default '0',
				`current_score` float(10,2) NOT NULL default '0.00',
				`current_date` int(10) NOT NULL default '0',
				`old_user_id` mediumint(10) NOT NULL default '0',
				`old_score` float(10,2) NOT NULL default '0.00',
				`old_date` int(10) NOT NULL default '0'
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_CHAT ."` (
				`chat_date` date NOT NULL default '0000-00-00',
				`chat_text` text NOT NULL
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_RATING ."` (
				`game_id` int(15) NOT NULL default '0',
				`rating` int(15) NOT NULL default '0',
				`date` int(15) NOT NULL default '0',
				`player` int(15) NOT NULL default '0'
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_SESSIONS ."` (
				`playing_time` int(15) NOT NULL default '0',
				`playing_id` int(10) NOT NULL default '0',
				`playing` int(11) NOT NULL default '0'
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_LAST_PLAYED ."` (
				`game_id` int(20) default '0',
				`user_id` int(11) default '0',
				`date` int(20) default NULL
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_GAMBLE ."` (
				`game_id` int(20) default '0',
				`sender_id` int(11) default '0',
				`reciever_id` int(11) default '0',
				`amount` int(10) default '0',
				`winner_id` int(11) default '0',
				`loser_id` int(11) default '0',
				`date` int(20) default NULL,
				`been_paid` int(11) default '0'
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_GAMBLE_PROGRESS ."` (
				`game_id` int(20) default '0',
				`sender_id` int(11) default '0',
				`reciever_id` int(11) default '0',
				`sender_score` int(20) default '0',
				`reciever_score` int(20) default '0',
				`sender_playing` int(1) NOT NULL default '0',
				`reciever_playing` int(1) NOT NULL default '0'
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE ". iNA ." (
			version VARCHAR(255) DEFAULT NULL
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_GAMES ."` (
				`game_id` mediumint(9) NOT NULL auto_increment,
				`game_name` varchar(25) default NULL,
				`game_path` varchar(255) default NULL,
				`game_desc` varchar(255) default NULL,
				`game_charge` int(11) unsigned default '0',
				`game_reward` int(11) unsigned NOT NULL default '0',
				`game_bonus` smallint(5) unsigned default '0',
				`game_use_gl` tinyint(3) unsigned default '0',
				`game_flash` tinyint(1) unsigned NOT NULL default '0',
				`game_show_score` tinyint(1) NOT NULL default '1',
				`win_width` smallint(6) NOT NULL default '0',
				`win_height` smallint(6) NOT NULL default '0',
				`highscore_limit` varchar(255) default NULL,
				`reverse_list` tinyint(1) NOT NULL default '0',
				`played` int(10) unsigned NOT NULL default '0',
				`instructions` text,
				`disabled` int(1) NOT NULL default '1',
				`install_date` int(20) NOT NULL default '0',
				`proper_name` text NOT NULL,
				`cat_id` int(4) NOT NULL default '0',
				`jackpot` int(10) NOT NULL default '0',
				`game_popup` smallint(1) NOT NULL default '1',
				`game_parent` smallint(1) NOT NULL default '1',
				PRIMARY KEY  (`game_id`)
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE ". iNA_SCORES ." (
			`game_name` varchar(255) default NULL,
			`player` varchar(40) default NULL,
			`score` int(10) unsigned NOT NULL default '0',
			`user_plays` int(6) default '0',
			`play_time` int(11) default '0',
			`date` int(11) default NULL
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_TOP_SCORES ."` (
			`game_name` varchar(255) default NULL,
			`player` varchar(40) default NULL,
			`score` int(10) unsigned NOT NULL default '0',
			`date` int(11) default NULL
			) TYPE=MyISAM;";

		$sql[] = "CREATE TABLE `". iNA_CHALLENGE ."` (
			`user` int(10) default '0',
			`count` int(25) default '0'
			) TYPE=MyISAM;";


		$sql[] = "CREATE TABLE `". iNA_CHALLENGE_USERS ."` (
			`user_to` int(10) default '0',
			`user_from` int(10) default '0',
			`count` int(25) default '0'
			) TYPE=MyISAM;";


		$sql[] = "CREATE TABLE `". iNA_TROPHY_COMMENTS ."` (
			`game` varchar(255) NOT NULL default '',
			`player` int(10) default NULL,
			`comment` text NOT NULL,
			`date` int(15) NOT NULL default '0',
			`score` int(20) NOT NULL default '0'
			) TYPE=MyISAM;";


		$sql[] = "CREATE TABLE `". iNA_BAN ."` (
				`id` int(10) NOT NULL default '0',
				`username` varchar(40) default NULL
			) TYPE=MyISAM;";


		$sql[] = "CREATE TABLE `". iNA_CHEAT ."` (
				`game_id` int(10) NOT NULL default '0',
				`player` varchar(40) default NULL,
				`game_count` int(100) NOT NULL auto_increment,
				PRIMARY KEY  (`game_count`)
			) TYPE=MyISAM;";


		$sql[] = "CREATE TABLE `". iNA_CAT ."` (
			`cat_id` mediumint(9) NOT NULL auto_increment,
			`cat_name` varchar(25) default NULL,
			PRIMARY KEY  (`cat_id`)
			) TYPE=MyISAM;";


		$sql[] = "CREATE TABLE `". iNA_FAVORITES ."` (
			`user` int(10) NOT NULL default '0',
			`games` text
			) TYPE=MyISAM;";

		$sql[] = "ALTER TABLE ". iNA_SCORES ." CHANGE score score FLOAT(10,2) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_TOP_SCORES ." CHANGE score score FLOAT(10,2) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_CHEAT ." CHANGE player player INT(10) DEFAULT '0';";
		$sql[] = "ALTER TABLE ". iNA_CHEAT ." DROP game_count;";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." CHANGE proper_name proper_name TEXT NOT NULL;";

		$sql[] = "ALTER TABLE ". iNA_GAMBLE ." ADD `winner_score` INT(11) DEFAULT '0' AFTER loser_id;";
		$sql[] = "ALTER TABLE ". iNA_GAMBLE ." ADD `loser_score` INT(11) DEFAULT '0' AFTER winner_score;";

		$sql[] = "ALTER TABLE ". iNA_CAT ." ADD `cat_desc` TEXT NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_CAT ." ADD `cat_img` VARCHAR(255) NOT NULL;";

		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `disabled` INT(1) DEFAULT '1' NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `install_date` INT(20) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `cat_id` INT(4) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `proper_name` varchar(255) default '';";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `jackpot` INT(10) NOT NULL default '0';";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `game_parent` SMALLINT(1) NOT NULL default '1';";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `game_popup` SMALLINT(1) NOT NULL default '1';";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `game_type` SMALLINT(1) DEFAULT '1' NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `game_links` TEXT NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `game_ge_cost` INT(10) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `game_keyboard` SMALLINT(1) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". iNA_GAMES ." ADD `game_mouse` SMALLINT(1) DEFAULT '0' NOT NULL;";

		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `user_trophies` int(10) not null default '0';";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_cheat_fix` int(100) not null default '0';";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_games_today` INT(10) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_last_visit_page` VARCHAR(255) NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_last_playtype` VARCHAR(255) DEFAULT 'parent' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_games_played` INT(10) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_game_playing` INT(10) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_game_pass` INT(10) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_games_pass_day` DATE NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_time_playing` VARCHAR(20) NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_settings` varchar(255) NOT NULL default 'info-1;;daily-1;;newest-1;;newest_count-3;;games-1;;games_count-40;;online-1';";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_name` TEXT NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_age` INT(10) DEFAULT '1' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_from` VARCHAR(255) NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_intrests` VARCHAR(255) NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_img` VARCHAR(255) NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_gender` SMALLINT(1) DEFAULT '1' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_ge` INT(10) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_name_effects` TEXT NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_title_effects` TEXT NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_saying_effects` TEXT NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_views` INT(10) DEFAULT '0' NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_title` VARCHAR(255) NOT NULL;";
		$sql[] = "ALTER TABLE ". USERS_TABLE ." ADD `ina_char_saying` VARCHAR(255) NOT NULL;";

		$sql[] = "INSERT INTO ". iNA ." VALUES ('v2.0.0')";
		$sql[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '1.1.0' WHERE config_name = 'ina_version'";

		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_version', '1.1.0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_jackpot_pool', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_online_list_text', 'Playing Games');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_online_list_color', '#88ff7f');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_max_gamble', '100');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_daily_game_id', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_daily_game_date', '". gmdate('Y-m-d') ."');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_daily_game_random', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_use_daily_game', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_use_shoutbox', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_force_registration', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_email_sent', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_rating_reward', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_use_rating_reward', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_trophy_king', '2');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_use_trophy', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_everything', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_trophy_page', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_comments_page', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_gamble_page', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_challenges_page', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_top5_page', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_cash_name', 'Tokens');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('default_reward_dbfield','');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('default_cash','');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('use_rewards_mod','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('use_cash_system','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_default_order', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('report_cheater','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('warn_cheater','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('use_point_system','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('use_gamelib','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('games_path','');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('gamelib_path','');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('use_gk_shop','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('use_allowance_system','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('games_per_page','20');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('current_ina_date','". gmdate("Y/m/d") ."');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_delete','0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_default_charge', '50');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_default_increment', '5');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_default_g_path', 'game_root/');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_default_g_reward', '20');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_default_g_height', '500');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_default_g_width', '400');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_guest_play', '2');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_button_option', '2');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_post_block', '2');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_post_block_count', '10');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_join_block', '2');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_join_block_count', '14');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_challenge', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_pm_trophy', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_challenge_msg', 'Your Trophy For %g% Has Been Challenged By %n%.');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_challenge_sub', 'Trophy Challenge In Progress');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_pm_trophy_msg', '%n% Has Taken Your Trophy For %g%.');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_pm_trophy_sub', 'Trophy Lost!');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('challenges_sent', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_new_game_limit', '7');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_pop_game_limit', '5');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_new_game_count', '3');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_use_newest', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_use_online', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_cheat', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_show_view_profile', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_show_view_topic', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_use_logo', 'http://phpbb-amod.com/sig.gif');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_use_max_games_per_day', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_max_games_per_day', '100');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_max_games_per_day_date', '');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_submit_scores_m', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_disable_submit_scores_g', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_change_char_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_change_gender_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_change_age_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_change_name_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_change_from_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_change_intrests_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_ge_per_game', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_ge_per_beat_score', '2');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_ge_per_trophy', '3');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_show_viewtopic', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_show_viewprofile', '1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_change_title_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_change_saying_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_name_effects_costs', '7,5,9,3,10,20');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_title_effects_costs', '5,4,3,2,1,1');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_char_saying_effects_costs', '2,2,2,2,2,2');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_game_pass_cost', '0');";
		$sql[] = "INSERT INTO ". CONFIG_TABLE ." (config_name, config_value) VALUES ('ina_game_pass_length', '5');";

			for ($b = 0; $b < sizeof($sql); $b++)
				$db->sql_query($sql[$b]);

			} #==== Close fresh install/upgrading

		if ($mode == 'upgrading')
			message_die(GENERAL_MESSAGE, $lang['admin_db_17'], $lang['admin_db_18']);
		if ($mode == 'remove')
			message_die(GENERAL_MESSAGE, $lang['admin_db_20'], $lang['admin_db_18']);
		if ($mode == 'fresh_install')
			message_die(GENERAL_MESSAGE, $lang['admin_db_19'], $lang['admin_db_18']);

		} #==== Close it all

	include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>