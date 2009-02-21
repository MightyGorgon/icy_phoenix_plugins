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
if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Amod+ Admin']['Bulk_Add_Games'] = "$file";
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));

require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_activity.' . PHP_EXT);

	if ( isset( $_POST['mode'] ) || isset( $_GET['mode'] ) )
		$mode = ( isset( $_POST['mode']) ) ? $_POST['mode'] : $_GET['mode'];
	else
		$mode = '';

	define("iNA_GAMES", $table_prefix . 'ina_games');

	$link = append_sid('admin_ina_bulk_add.' . PHP_EXT);

	echo "<table width='100%' border='0' class='forumline' cellspacing='2' align='center' valign='middle'>";
	echo "	<tr>";
	echo "		<th class='thHead' colspan='2'>";
	echo "			". $lang['bulk_add_title'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo "<br /><br />";

if($mode == "main" || !$mode)
		{
	echo "<table border='0' align='center' valign='top' class='forumline' width='100%'>";
	echo "	<tr>";
	echo "		<td align='center' valign='top' width='100%' class='row2'>";
	echo "			<span class='genmed'>";
	echo "				". $lang['bulk_add_title_2'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='add_games' action='$link' method='post'>";
	echo "<table border='0' align='center' valign='top'>";
	echo "	<tr>";
	echo "		<td align='center' valign='middle' width='100%' class='row2'>";
	echo "			<input type='hidden' name='mode' value='do_it'>";
	echo "			<input type='submit' class='mainoption' value='". $lang['bulk_add_button'] ."' onchange='add_games.edit_trophy.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
		}

	if($mode == "do_it")
		{

	$game_dir = IP_ROOT_PATH . $board_config['ina_default_g_path'];
	$games = opendir($game_dir);
	$g = 0;
		while ($file = readdir($games))
			{
			if (($file != ".") && ($file != "..") && ($file != "index.htm"))
				{
				$q29 = "SELECT game_name
				 				FROM ". iNA_GAMES ."
						WHERE game_name = '$file'";
				$r29 = $db -> sql_query($q29);

				if (!$db -> sql_fetchrow($r29))
					{
					if($board_config['use_rewards_mod'] == '1')
						{
					$reward = "";
					$charge = "";
					$reward = $board_config['ina_default_g_reward'];
					$charge = $board_config['ina_default_charge'];
						}
					else
						{
					$reward = "";
					$charge = "";
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
						 '". $board_config['ina_default_g_path'] . $file . "/"."',
						 '". $charge ."',
						 '". $reward ."',
						 '1',
						 '1',
						 '". $board_config['ina_default_g_width'] ."',
						 '". $board_config['ina_default_g_height'] ."',
						 '0',
						 '". time() ."',
						 '1')";
				$r2 = $db -> sql_query($q2);
				$g++;
					}
				}
			}
	if($g == 1) message_die(GENERAL_MESSAGE, $lang['bulk_add_add_msg2'], $lang['bulk_add_add_success']);
	if($g > 1) message_die(GENERAL_MESSAGE, str_replace("%g%", $g, $lang['bulk_add_add_msg1']), $lang['bulk_add_add_success']);
		}
	include('page_footer_admin.' . PHP_EXT);

?>