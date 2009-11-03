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
	$module['3000_ACTIVITY']['140_User_Ban'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . 'common.' . PHP_EXT);

$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

define('INA_BAN', $table_prefix . 'ina_ban');
$link = append_sid('admin_ina_ban.' . PHP_EXT);

if(($mode == 'main') || !$mode)
		{
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "			". $lang['a_ban_1'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['a_ban_2'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";

	echo "<form name=\"un_ban\" action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['a_ban_3'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name=\"unban_id\">";
	echo "				<option selected value=\"\">". $lang['a_ban_4'] ."</option>";

	$q = "SELECT id
			FROM ". INA_BAN ."
			WHERE id <> '0'";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$id = $row['id'];
	echo "				<option value=\"". $id ."\">$id</option>";
		}

	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['a_ban_5'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name=\"unban_name\">";
	echo "				<option selected value=\"\">". $lang['a_ban_6'] ."</option>";

	$q = "SELECT username
			FROM ". INA_BAN ."
			WHERE username <> ''";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$name = $row['username'];
	echo "				<option value=\"". $name ."\">$name</option>";
		}

	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value=\"unban\" />";
	echo "			<input type=\"submit\" class=\"mainoption\" value=\"". $lang['a_ban_7'] ."\" onchange=\"document.un_ban.submit()\" />";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['a_ban_8'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";

	echo "<form name=\"ban_someone\" action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['a_ban_9'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input name='ban_id' type=\"text\" size=\"25\" value=\"\" class=\"post\" />";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['a_ban_10'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input name='ban_name' type=\"text\" size=\"25\" value=\"\" class=\"post\" />";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value=\"ban\" />";
	echo "			<input type=\"submit\" class=\"mainoption\" value=\"". $lang['a_ban_11'] ."\" onchange=\"document.ban_someone.submit()\" />";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";
		}

	if($mode == "ban")
		{
	$ban_id = $_POST['ban_id'];
	$ban_name = $_POST['ban_name'];

		if(($ban_id) && (!is_numeric($ban_id)))
			{
	message_die(GENERAL_ERROR, $lang['a_ban_12'] ."<a href=". $link .">". $lang['a_ban_13'], $lang['ban_error']);
			}

		if((!$ban_id) && (!$ban_name))
			{
	message_die(GENERAL_ERROR, $lang['a_ban_14'] ."<a href=". $link .">". $lang['a_ban_13'], $lang['ban_error']);
			}

		if($ban_id)
			{
	$q = "SELECT user_id
			FROM ". USERS_TABLE ."
			WHERE user_id = '$ban_id'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$user_check = $row['user_id'];
		if(!$user_check)
			{
	message_die(GENERAL_ERROR, $lang['a_ban_15'] . $ban_id . $lang['a_ban_16'] ."<a href=". $link .">". $lang['a_ban_17'], $lang['ban_error']);
			}

	$q = "SELECT id
			FROM ". INA_BAN ."
			WHERE id = '$ban_id'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$user_check = $row['id'];
		if($user_check)
			{
	message_die(GENERAL_ERROR, $lang['a_ban_15'] . $ban_id . $lang['a_ban_18'] ."<a href=". $link .">". $lang['a_ban_17'], $lang['ban_error']);
			}

	$q = "INSERT INTO ". INA_BAN ."
			VALUES
			('$ban_id', '')";
	$r = $db->sql_query($q);
	message_die(GENERAL_MESSAGE, $lang['a_ban_19']. $ban_id . $lang['a_ban_20']  ."<a href=". $link .">". $lang['a_ban_21'], $lang['a_ban_22']);
			}

		if($ban_name)
			{
	$q = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE username = '$ban_name'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$user_check = $row['username'];
		if(!$user_check)
			{
	message_die(GENERAL_ERROR, $lang['a_ban_23'] . $ban_name . $lang['a_ban_16'] ."<a href=". $link .">". $lang['a_ban_17'], $lang['ban_error']);
			}

	$q = "SELECT username
			FROM ". INA_BAN ."
			WHERE username = '$ban_name'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$user_check = $row['username'];
		if($user_check)
			{
	message_die(GENERAL_ERROR, $lang['a_ban_23'] . $ban_name . $lang['a_ban_18'] ."<a href=". $link .">". $lang['a_ban_17'], $lang['ban_error']);
			}

	$q = "INSERT INTO ". INA_BAN ."
			VALUES
			('', '$ban_name')";
	$r = $db->sql_query($q);

	message_die(GENERAL_MESSAGE, $lang['a_ban_24'] . $ban_name . $lang['a_ban_20'] ."<a href=". $link .">". $lang['a_ban_21'], $lang['a_ban_22']);
			}
		}

	if($mode == "unban")
		{
	$unban_id = $_POST['unban_id'];
	$unban_name = $_POST['unban_name'];

		if($unban_id)
			{
	$q = "DELETE FROM ". INA_BAN ."
			WHERE id = '$unban_id'";
	$r = $db->sql_query($q);
	message_die(GENERAL_MESSAGE, $lang['a_ban_25'] . $unban_id . $lang['a_ban_20'] ."<a href=". $link .">". $lang['a_ban_21'], $lang['a_ban_22']);
			}

		if($unban_name)
			{
	$q = "DELETE FROM ". INA_BAN ."
			WHERE username = '$unban_name'";
	$r = $db->sql_query($q);
	message_die(GENERAL_MESSAGE, $lang['a_ban_26'] . $unban_name . $lang['a_ban_20'] ."<a href=". $link .">". $lang['a_ban_21'], $lang['a_ban_22']);
			}

		if((!$unban_id) && (!$unban_name))
			{
	message_die(GENERAL_ERROR, $lang['a_ban_27'] ."<a href=". $link .">". $lang['a_ban_28'], $lang['ban_error']);
			}
		}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>