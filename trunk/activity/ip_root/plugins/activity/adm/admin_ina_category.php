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
	$module['3000_ACTIVITY']['160_Category'] = $file;
	return;
}

// Let's set the root dir for phpBB
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . 'common.' . PHP_EXT);

$mode = (isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : ''));

define('INA_CATEGORY', $table_prefix . 'ina_categories');
$link = append_sid('admin_ina_category.' . PHP_EXT);

if(($mode == 'main') || !$mode)
		{
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "			". $lang['admin_cat_1'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_2'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='add_cat' action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_3'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" name='new_cat' class=\"post\" value=\"\">";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_52'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" name='new_cat_desc' class=\"post\" value=\"\">";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_53'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" name='new_cat_img' class=\"post\" value=\"\">";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='add_new_cat'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_cat_4'] ."' onchange='document.add_cat.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_5'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='edit_cat' action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_6'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='edit_category'>";
	echo "				<option selected value=\"\">". $lang['admin_cat_7'] ."</option>";

	$q = "SELECT *
			FROM ". INA_CATEGORY ."
			WHERE cat_id > '0'
			ORDER BY cat_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$cat = $row['cat_name'];
	$cat_id = $row['cat_id'];
	echo "				<option value='". $cat_id ."'>$cat</option>";
		}

	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='edit_exis_cat'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_cat_8'] ."' onchange='document.edit_cat.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_9'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='delete_cat' action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_10'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='delete_category'>";
	echo "				<option selected value=\"\">". $lang['admin_cat_7'] ."</option>";

	$q = "SELECT *
			FROM ". INA_CATEGORY ."
			WHERE cat_id > '0'
			ORDER BY cat_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$cat = $row['cat_name'];
	$cat_id = $row['cat_id'];
	echo "				<option value='". $cat_id ."'>$cat</option>";
		}

	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='delete_exis_cat'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_cat_11'] ."' onchange='document.delete_cat.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_12'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='assign_game' action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_13'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='category_assignment'>";
	echo "				<option selected value=\"\">". $lang['admin_cat_7'] ."</option>";
	echo "				<option value='delete_game_from_cats'>". $lang['admin_cat_14'] ."</option>";

	$q = "SELECT *
			FROM ". INA_CATEGORY ."
			WHERE cat_id > '0'
			ORDER BY cat_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$cat = $row['cat_name'];
	$cat_id = $row['cat_id'];
	echo "				<option value='". $cat_id ."'>$cat</option>";
		}

	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_15'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='game_assignment'>";
	echo "				<option selected value=\"\">". $lang['admin_cat_16'] ."</option>";

	$q = "SELECT *
			FROM ". iNA_GAMES ."
			WHERE game_id > '0'
			ORDER BY proper_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$game_name = $row['proper_name'];
	$game_id = $row['game_id'];
	$exists = $row['cat_id'];

		if($exists > 0)
			{
	echo "				<option value='". $game_id ."'>$game_name</option>";
			}
		}

	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_51'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='game_assignment2'>";
	echo "				<option selected value=\"\">". $lang['admin_cat_16'] ."</option>";

	$q = "SELECT *
			FROM ". iNA_GAMES ."
			WHERE game_id > '0'
			ORDER BY proper_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	$game_name = $row['proper_name'];
	$game_id = $row['game_id'];
	$exists = $row['cat_id'];

		if($exists == 0)
			{
	echo "				<option value='". $game_id ."'>$game_name</option>";
			}
		}

	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td align='center' valign='middle' width='100%'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='assign_game_cat'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_cat_17'] ."' onchange='document.assign_game.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_18'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<form name='check_this_game' action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_19'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<select name='check_assignment'>";
	echo "				<option selected value=\"\">". $lang['admin_cat_16'] ."</option>";

	$q = "SELECT proper_name, game_id
			FROM ". iNA_GAMES ."
			WHERE cat_id <> '0'
			ORDER BY proper_name ASC";
	$r = $db->sql_query($q);
	while($row = $db->sql_fetchrow($r))
		{
	echo "				<option value='". $row['game_id'] ."'>". $row['proper_name'] ."</option>";
		}
	echo "			</select>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table align=\"center\" valign=\"top\" border=\"0\">";
	echo "	<tr>";
	echo "		<td align='center' valign='middle' width='100%'>";
	echo "			<input type=\"hidden\" name=\"mode\" value='check_game_cat'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_cat_20'] ."' onchange='document.check_this_game.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";
		}

	if($mode == "check_game_cat")
		{
	$assignment_check = $_POST['check_assignment'];

	if(!$assignment_check)
	{
		message_die(GENERAL_MESSAGE, str_replace("%L%", $link, $lang['admin_cat_21']), $lang['admin_cat_22']);
	}

	$q = "SELECT proper_name, cat_id
			FROM ". iNA_GAMES ."
			WHERE game_id = '". $assignment_check ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);

	$q = "SELECT cat_name
			FROM ". INA_CATEGORY ."
			WHERE cat_id = '". $row['cat_id'] ."'";
	$r = $db->sql_query($q);
	$row1 = $db->sql_fetchrow($r);
	$cat_name = $row1['cat_name'];

	message_die(GENERAL_MESSAGE, $lang['admin_cat_30'] . $row['proper_name'] . $lang['admin_cat_31'] . $cat_name . $lang['admin_cat_32'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_24']);
}

if($mode == "assign_game_cat")
{
	$cat = $_POST['category_assignment'];
	$game = $_POST['game_assignment'];
	$game1 = $_POST['game_assignment2'];

	if(!$cat || (!$game && !$game1))
	{
		message_die(GENERAL_MESSAGE, $lang['admin_cat_33'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_34'], $lang['admin_cat_22']);
	}

	if($game && $game1)
	{
		message_die(GENERAL_MESSAGE, $lang['admin_cat_33'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_34'], $lang['admin_cat_22']);
	}

	if($game && !$game1)
	{
		$q = "SELECT *
				FROM ". iNA_GAMES ."
				WHERE game_id = '$game'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$exists = $row['cat_id'];

		if(($exists > 0) && ($cat != "delete_game_from_cats"))
		{
			$q = "UPDATE ". iNA_GAMES ."
					SET cat_id = '$cat'
					WHERE game_id = '$game'";
			$r = $db->sql_query($q);

			message_die(GENERAL_MESSAGE, $lang['admin_cat_35'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
		}

		if(($exists == 0) && ($game) && ($cat) && ($cat != "delete_game_from_cats"))
		{
			$q = "UPDATE ". iNA_GAMES ."
					SET cat_id = '$cat'
					WHERE game_id = '$game'";
			$r = $db->sql_query($q);

			message_die(GENERAL_MESSAGE, $lang['admin_cat_36'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
		}

		if($cat == "delete_game_from_cats")
		{
			$q = "UPDATE ". iNA_GAMES ."
					SET cat_id = '0'
					WHERE game_id = '$game'";
			$r = $db->sql_query($q);

			message_die(GENERAL_MESSAGE, $lang['admin_cat_37'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
		}
	}

	if($game1 && !$game)
	{
		$q = "SELECT *
				FROM ". iNA_GAMES ."
				WHERE game_id = '$game1'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$exists = $row['cat_id'];

		if(($exists > 0) && ($cat != "delete_game_from_cats"))
		{
			$q = "UPDATE ". iNA_GAMES ."
					SET cat_id = '$cat'
					WHERE game_id = '$game1'";
			$r = $db->sql_query($q);
			message_die(GENERAL_MESSAGE, $lang['admin_cat_35'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
		}

		if((!$exists) && ($game1) && ($cat) && ($cat != "delete_game_from_cats"))
		{
			$q = "UPDATE ". iNA_GAMES ."
					SET cat_id = '$cat'
					WHERE game_id = '$game1'";
			$r = $db->sql_query($q);
			message_die(GENERAL_MESSAGE, $lang['admin_cat_36'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
		}

		if($cat == "delete_game_from_cats")
		{
			$q = "UPDATE ". iNA_GAMES ."
					SET cat_id = '0'
					WHERE game_id = '$game1'";
			$r = $db->sql_query($q);
			message_die(GENERAL_MESSAGE, $lang['admin_cat_37'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
		}
	}
}

if($mode == "add_new_cat")
{
	$cat = $_POST['new_cat'];
	$des = $_POST['new_cat_desc'];
	$img = $_POST['new_cat_img'];

	if(!$cat || !$des)
	{
		message_die(GENERAL_MESSAGE, $lang['admin_cat_38'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_34'], $lang['admin_cat_22']);
	}

	$q = "SELECT cat_name
			FROM ". INA_CATEGORY ."
			WHERE cat_name = '$cat'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$exists = $row['cat_name'];

	if($exists)
	{
		message_die(GENERAL_MESSAGE, $lang['admin_cat_39'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_34'], $lang['admin_cat_22']);
	}

	$q = "INSERT INTO ". INA_CATEGORY ."
			VALUES ('', '". str_replace("\'", "''", $cat) ."', '". str_replace("\'", "''", $des) ."', '". $img ."')";
	$r = $db->sql_query($q);

	message_die(GENERAL_MESSAGE, $lang['admin_cat_40'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
}

if($mode == "edit_exis_cat")
{
	$cat = $_POST['edit_category'];
	if(!$cat)
	{
		message_die(GENERAL_MESSAGE, $lang['admin_cat_41'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_34'], $lang['admin_cat_22']);
	}

	$q = "SELECT *
			FROM ". INA_CATEGORY ."
			WHERE cat_id = '$cat'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$edit_it = $row['cat_name'];
	$edit_de = $row['cat_desc'];
	$edit_im = $row['cat_img'];

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "			". $lang['admin_cat_42'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo "<br /><br />";

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_43'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";

	echo "<form name='save' action=\"$link\" method=\"post\">";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_10'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" name='edited_cat' class=\"post\" value='$edit_it'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_52'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" name='edited_desc' class=\"post\" value='$edit_de'>";
	echo "		</td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class=\"row2\" width=\"50%\" valign=\"top\">";
	echo "			<span class=\"genmed\">";
	echo "				". $lang['admin_cat_53'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"top\">";
	echo "			<input type=\"text\" name='edited_img' class=\"post\" value='$edit_im'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "<br />";
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"100%\">";
	echo "			<input type=\"hidden\" name=\"mode\" value='save_changes'>";
	echo "			<input type=\"hidden\" name='id' value='$cat'>";
	echo "			<input type=\"hidden\" name='original' value='$edit_it'>";
	echo "			<input type=\"submit\" class=\"mainoption\" value='". $lang['admin_cat_44'] ."' onchange='document.save.submit()'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br /><br />";
		}

	if($mode == "save_changes")
		{
	$cat = $_POST['edited_cat'];
	$id = $_POST['id'];
	$desc = $_POST['edited_desc'];
	$img = $_POST['edited_img'];
	$orig = $_POST['original'];

		if(!$cat || !$desc)
			{
		message_die(GENERAL_MESSAGE, $lang['admin_cat_45'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_34'], $lang['admin_cat_22']);
			}

	if($cat != $orig)
		{
	$q = "SELECT cat_name
			FROM ". INA_CATEGORY ."
			WHERE cat_name = '". $cat ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$exists = $row['cat_name'];

		if($exists)
			{
		message_die(GENERAL_MESSAGE, $lang['admin_cat_46'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_34'], $lang['admin_cat_22']);
			}
		}

	$q = "UPDATE ". INA_CATEGORY ."
			SET
			cat_name = '". str_replace("\'", "''", $cat) ."',
			cat_desc = '". str_replace("\'", "''", $desc) ."',
			cat_img = '". $img ."'
			WHERE cat_id = '". $id ."'";
	$r = $db->sql_query($q);

		message_die(GENERAL_MESSAGE, $lang['admin_cat_47'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
		}

	if($mode == "delete_exis_cat")
	{
		$cat = $_POST['delete_category'];
		if(!$cat)
		{
			message_die(GENERAL_MESSAGE, $lang['admin_cat_48'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_22']);
		}

	$q = "SELECT cat_name
			FROM ". INA_CATEGORY ."
			WHERE cat_id = '$cat'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$check = $row['cat_name'];

	$q = "DELETE FROM ". INA_CATEGORY ."
			WHERE cat_id = '$cat'";
	$r = $db->sql_query($q);

	$q = "UPDATE ". iNA_GAMES ."
			SET cat_id = '0'
			WHERE cat_id = '$cat'";
	$r = $db->sql_query($q);

	message_die(GENERAL_MESSAGE, $lang['admin_cat_49'] . $check . $lang['admin_cat_50'] ."<a href='". $link ."'>". $lang['admin_cat_26'] .'</a>'. $lang['admin_cat_27'], $lang['admin_cat_23']);
		}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>