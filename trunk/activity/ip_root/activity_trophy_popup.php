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
define('MG_KILL_CTRACK', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include_once(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . ACTIVITY_MOD_PATH . 'includes/functions_amod_plus.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$gen_simple_header = true;
$gen_simple_footer = true;

/* Start Version Check */
	VersionCheck();
/*  End Version Check */

	$template->set_filenames(array('body' => ACTIVITY_MOD_PATH . 'activity_tp_body.tpl'));

		$q = "SELECT username
					FROM ". USERS_TABLE ."
					WHERE user_id = '". $_GET['user'] ."'";
		$r = $db -> sql_query($q);
		$row = $db -> sql_fetchrow($r);

		$page_title = str_replace("%u%", $row['username'], $lang['trophy_popup_title']);
		$template->assign_vars(array(
			"TITLE_2" => $lang['trophy_popup_left'],
			"TITLE_3" => $lang['trophy_popup_right'])
		);

		$i = 1;
		$q = "SELECT *
					FROM ". INA_TROPHY ."
					WHERE player = '". $_GET['user'] ."'";
		$r = $db -> sql_query($q);
		while($row = $db -> sql_fetchrow($r))
			{

		$q1 = "SELECT *
					FROM ". iNA_GAMES ."
					WHERE game_name = '". $row['game_name'] ."'";
		$r1 = $db -> sql_query($q1);
		$row1 = $db -> sql_fetchrow($r1);

		$image = "<b><span class='genmed'>". $row1['proper_name'] ."</span></b><br>". PopupImages($row1['game_name']);
		$score = $row['score'];
		$row_class = (!($i % 2)) ? 'row1' : 'row2';
		$i++;

		$template->assign_block_vars('trophy_rows', array(
			'IMAGE' => $image,
			'SCORE' => FormatScores($score),
			'ROW_CLASS' => $row_class
			)
		);
		}

include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>