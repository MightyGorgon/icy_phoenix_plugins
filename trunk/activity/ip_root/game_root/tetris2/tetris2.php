<?php
/***************************************************************************
 *
 *                                 game.php
 *                            ------------------
 *   begin                : Thursday, August 1, 2002
 *   copyright            : (c) 2002 iNetAngel
 *   email                : support@iNetAngel.com
 *
 *   $Id                  : activity.php v0.3.0
 *
 ***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************
 *
 *   This is a MOD for phpbb v2+. The phpbb group has all rights to the
 *   phpbb source. They can be contacted at :
 *
 *      I-Net : www.phpbb.com
 *      E-Mail: support@phpbb.com
 *
 *   If you have made any changes then please notify me so they can be added
 *   if they are improvments. You of course will get the credit for helping
 *   out. If you would like to see other MODs that I have made then check
 *   out my forum at : www.iNetAngel.com and click on the community button.
 *
 ***************************************************************************/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

//===========================================================================
// Change the part that says 'game' to the Name of the game your trying to
// install. This is case sensetive so type it exactly how you typed it in
// the game editor.

$game_name = 'tetris2';

//===========================================================================

// Grab Game info from game_id
$sql = "SELECT * FROM " . GAMES_GAMES . "
				WHERE game_name = '" . $game_name . "'";
if(!$result = $db->sql_query($sql))
{
  message_die(GENERAL_ERROR, "Couldn't obtain Game data", "", __LINE__, __FILE__, $sql);
}
$game_info = $db->sql_fetchrow($result);

// Extra Vars
$game_id = $game_info['game_id'];
$game_width = $game_info['win_width'];
$game_height = $game_info['win_height'];
$game_path = $game_info['game_path'];
$game_flash = $game_info['game_flash'];
$game_title = $board_config['sitename'] . ' :: ' . $game_name;

if ($game_flash)
{
	$template->set_filenames(array('body' => 'flash_body.tpl'));

	// Generate page
	$template->assign_vars(array(
		'TITLE' => $game_title,
		'WIDTH' => $game_width,
		'HEIGHT' => $game_height,
		'SWFNAME' => $game_name . '.swf',
		'PATH' => $game_path
		)
	);
}
else
{
	$template->set_filenames(array('body' => 'flash_body.tpl'));

	$template->assign_vars(array(
		'USERNAME' => $userdata['username'],
		'PATH' => $game_path,
		'S_GAME_ACTION' => append_sid('onlinegames_scores.' . PHP_EXT . '?mode=check_score&game_name='.$game_name)
		)
	);
}

$template->pparse('body');

?>