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

define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

/* Start Version Check */
VersionCheck();
/*  End Version Check */

$q = "SELECT username
			FROM " . USERS_TABLE . "
			WHERE user_id = '" . $_GET['user'] . "'";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);

$meta_content['page_title'] = str_replace("%u%", $row['username'], $lang['trophy_popup_title']);
$template->assign_vars(array(
	'TITLE_2' => $lang['trophy_popup_left'],
	'TITLE_3' => $lang['trophy_popup_right']
	)
);

$i = 1;
$q = "SELECT *
			FROM " . INA_TROPHY . "
			WHERE player = '" . $_GET['user'] . "'";
$r = $db->sql_query($q);
while($row = $db -> sql_fetchrow($r))
{
	$q1 = "SELECT *
				FROM " . iNA_GAMES . "
				WHERE game_name = '" . $row['game_name'] . "'";
	$r1 = $db->sql_query($q1);
	$row1 = $db->sql_fetchrow($r1);

	$image = '<b><span class="genmed">' . $row1['proper_name'] . '</span></b><br />' . PopupImages($row1['game_name']);
	$score = $row['score'];
	$row_class = (!($i % 2) ? 'row1' : 'row2');
	$i++;

	$template->assign_block_vars('trophy_rows', array(
		'IMAGE' => $image,
		'SCORE' => FormatScores($score),
		'ROW_CLASS' => $row_class
		)
	);
}

$gen_simple_header = true;
$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_tp_body.tpl');
full_page_generation($template_to_parse, '', '', '');

?>