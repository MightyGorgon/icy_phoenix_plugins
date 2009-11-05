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
	if (!defined('ACTIVITY_PLUGIN_ENABLED') || (defined('ACTIVITY_PLUGIN_ENABLED') && !ACTIVITY_PLUGIN_ENABLED))
	{
		return;
	}

	//$file = basename(__FILE__);
	$file = IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . ADM . '/' . basename(__FILE__);
	$module['3000_ACTIVITY']['150_Bulk_Add_Games'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . 'common.' . PHP_EXT);

$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

define("iNA_GAMES", $table_prefix . 'ina_games');

$link = append_sid('admin_ina_bulk_add.' . PHP_EXT);

echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
echo "	<tr>";
echo "		<th colspan=\"2\">";
echo "			". $lang['bulk_add_title'];
echo "		</th>";
echo "	</tr>";
echo "</table>";
echo "<br /><br />";

if(($mode == 'main') || !$mode)
{
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['bulk_add_title_2'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name=\"add_games\" action=\"$link\" method=\"post\">";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='do_it'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['bulk_add_button'] ."' onchange='add_games.edit_trophy.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
}

if($mode == 'do_it')
{
	$game_dir = IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . $config['ina_default_g_path'];
	$games = @opendir($game_dir);
	$g = 0;
	while ($file = @readdir($games))
	{
		if (($file != '.') && ($file != '..') && ($file != 'index.htm'))
		{
			$q29 = "SELECT game_name
							FROM " . iNA_GAMES . "
							WHERE game_name = '$file'";
			$r29 = $db->sql_query($q29);

			if (!$db->sql_fetchrow($r29))
			{
				if($config['use_rewards_mod'] == '1')
				{
					$reward = '';
					$charge = '';
					$reward = $config['ina_default_g_reward'];
					$charge = $config['ina_default_charge'];
				}
				else
				{
					$reward = '';
					$charge = '';
					$reward = '0';
					$charge = '0';
				}

				$q2 = "INSERT INTO ". iNA_GAMES ."
					 (game_id,
					 game_name,
					 proper_name,
					 game_path,
					 game_charge,
					 game_bonus,
					 game_flash,
					 game_show_score,
					 win_width,
					 win_height,
					 reverse_list,
					 install_date,
					 disabled)
					 VALUES
					 ('',
					 '". $file ."',
					 '". $file ."',
					 '". $config['ina_default_g_path'] . $file . "/"."',
					 '". $charge ."',
					 '". $reward ."',
					 '1',
					 '1',
					 '". $config['ina_default_g_width'] ."',
					 '". $config['ina_default_g_height'] ."',
					 '0',
					 '". time() ."',
					 '1')";
				$r2 = $db->sql_query($q2);
				$g++;
			}
		}
	}
	if($g == 1) message_die(GENERAL_MESSAGE, $lang['bulk_add_add_msg2'], $lang['bulk_add_add_success']);
	if($g > 1) message_die(GENERAL_MESSAGE, str_replace("%g%", $g, $lang['bulk_add_add_msg1']), $lang['bulk_add_add_success']);
}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>