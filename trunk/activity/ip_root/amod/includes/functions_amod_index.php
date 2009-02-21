<?php
/***************************************************************************
 *                            functions_amod_index.php
 *                            ---------------------
 *   begin                : 2006/02/19
 *   copyright            : Mighty Gorgon (Luca Libralato)
 *   website              : http://www.mightygorgon.com
 *   email                : mightygorgon@mightygorgon.com
 *   version              : 0.1.0
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

// CTracker_Ignore: File checked by human
if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking Attempt');
}

$ipb_check = (isset($_GET['act'])) ? 'Arcade' : '';
$ipb_score = (isset($_GET['do'])) ? 'newscore' : '';
if (($ipb_check) && ($ipb_score))
{
	$game = trim(addslashes(stripslashes($_POST['gname'])));
	$score = intval($_POST['gscore']);

	$q = "SELECT game_type
		FROM ". iNA_GAMES ."
		WHERE game_name = '". $game ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);

	#==== Only IPB Games Can Use This Format!
	if ($row['game_type'] == 3)
	{
		echo '<form method="post" name="ipb" action="newscore.php">';
		echo '<input type="hidden" name="score" value="' . $score . '">';
		echo '<input type="hidden" name="game_name" value="' . $game . '">';
		echo '</form>';
		echo '<script type="text/javascript">';
		echo 'window.onload = function(){document.ipb.submit()}';
		echo '</script>';
		exit();
	}
	else
	{
		redirect(append_sid('activity.' . PHP_EXT), true);
	}
}
?>