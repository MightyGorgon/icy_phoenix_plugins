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
	$module['3000_ACTIVITY']['180_Hide_Show_Games'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

$link = append_sid('admin_ina_disable.' . PHP_EXT);

if(($mode == 'main') || !$mode)
{
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "			". $lang['a_disable_1'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['a_disable_2'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='do_it' action=\"$link\" method=\"post\">";
	echo "<table border='0' align='center' valign='middle' class='forumline' width='100%'>";
	echo "	<tr>";
	echo "		<td align='left' valign='middle' width='50%' class='row2'>";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['a_disable_3'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td align='center' valign='middle' width='50%' class='row2'>";
	echo "			<select name='game_choice'>";
	echo "				<option selected value=\"\">". $lang['a_disable_4'] ."</option>";

	$q = "SELECT *
			FROM ". iNA_GAMES ."
			ORDER BY game_id ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$g_name = $row['game_name'];
	$g_id = $row['game_id'];
	$g_dis = $row['disabled'];

	if($g_dis == "2")
		{
	$new_name = "($g_id) $g_name*";
		}
	else
		{
	$new_name = "($g_id) $g_name";
		}

	echo "				<option value='$g_id'>$new_name</option>";
		}

	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='hide_show'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['a_disable_5'] ."' onchange='document.do_it.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";
}

	if($mode == "hide_show")
		{
	$id = $_POST['game_choice'];

		if(!$id)
			{
		message_die(GENERAL_ERROR, $lang['a_disable_6'] ."<a href='". $link ."'>". $lang['a_disable_7'], $lang['ban_error']);
			}

	$q = "SELECT disabled
			FROM ". iNA_GAMES ."
			WHERE game_id = '$id'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$disabled = $row['disabled'];

		if($disabled == "2")
			{
	$q = "UPDATE ". iNA_GAMES ."
			SET disabled = '1'
			WHERE game_id = '$id'";
	$r = $db->sql_query($q);

		message_die(GENERAL_MESSAGE, $lang['a_disable_8'] ."<a href='". $link ."'>". $lang['a_disable_9'] , $lang['a_ban_22']);
			}
		elseif($disabled == '1')
			{
	$q = "UPDATE ". iNA_GAMES ."
			SET disabled = '2'
			WHERE game_id = '$id'";
	$r = $db->sql_query($q);

		message_die(GENERAL_MESSAGE, $lang['a_disable_10'] ."<a href='". $link ."'>". $lang['a_disable_11'], $lang['a_ban_22']);
			}
		else
			{
		message_die(GENERAL_ERROR, $lang['a_disable_13'] ."<a href='". $link ."'>". $lang['a_disable_12'], $lang['ban_error']);
			}
		}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
?>