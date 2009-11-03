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
if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking Attempt');
}

function AMP_Profile_Views($character)
{
	global $db;

	$q = "UPDATE ". USERS_TABLE ."
			SET ina_char_views = ina_char_views + 1
			WHERE user_id = '". $character ."'";
	$db->sql_query($q);
}

function AMP_Level_GE($ge)
{
	$level_up = 100;
	if ($ge >= $level_up)
		$level = floor($ge / $level_up);
	else
		$level = 1;

	return $level;
}

function AMP_Add_GE($user, $ge, $trophy, $beat_score)
{
	global $db, $config;
	$ge_per_level = $config['ina_char_ge_level_up'];

	#==== Determine How Much GE They Get
	$ge_per_game = $config['ina_char_ge_per_game'];

	if ($beat_score)
		$ge_per_game = $config['ina_char_ge_per_beat_score'];

	if ($trophy)
		$ge_per_game = $config['ina_char_ge_per_trophy'];

	$total_ge = $ge + $ge_per_game;

	if ($total_ge)
	{
		$q = "UPDATE ". USERS_TABLE ."
				SET ina_char_ge = '". $total_ge ."'
				WHERE user_id = '". $user ."'";
	}

	#==== Send It To The DB
	if ($user != ANONYMOUS)
		$db->sql_query($q);
}

function AMP_Sub_GE($user, $ge)
{
	global $db;

	$q = "UPDATE ". USERS_TABLE ."
			SET ina_char_ge = ina_char_ge - '". $ge ."'
			WHERE user_id = '". $user ."'";

	#==== Send It To The DB
	if ($user != ANONYMOUS)
		$db->sql_query($q);
}

function AMP_Update_Char($user, $name, $age, $gender, $char, $from, $intrests, $title, $saying)
{
	global $db, $userdata, $lang;

	$name = addslashes(stripslashes($name));
	$from = addslashes(stripslashes($from));
	$intrests = addslashes(stripslashes($intrests));
	$title = addslashes(stripslashes($title));
	$saying = addslashes(stripslashes($saying));

	$q = "SELECT *
			FROM ". USERS_TABLE ."
			WHERE ina_char_name = '". $name ."'
			AND user_id <> '". $user ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);

	if ($row['user_id'])
		AMP_Error_Handler(2, $lang['amp_char_cp_name_exists'], 'activity_char', '?sid='. $userdata['session_id']);

	$q = "UPDATE ". USERS_TABLE ."
			SET ina_char_name = '". $name ."',
			ina_char_age = '". $age ."',
			ina_char_from = '". $from ."',
			ina_char_intrests = '". $intrests ."',
			ina_char_img = '". $char ."',
			ina_char_gender = '". $gender ."',
			ina_char_saying = '". $saying ."',
			ina_char_title = '". $title ."'
			WHERE user_id = '". $user ."'";
	$db->sql_query($q);

	AMP_Error_Handler(1, $lang['amp_char_cp_success'], 'activity_char', '?sid='. $userdata['session_id']);
}

function AMP_Error_Handler($type, $msg, $return, $variables)
{
	global $lang;

	if ($type == 1)
		$type = 'GENERAL_MESSAGE';
	if ($type == 2)
		$type = 'GENERAL_ERROR';
	if ($type == 3)
		$type = 'CRITICAL_ERROR';

	message_die($type, $msg .'<br /><br />'. str_replace('%L%', '<a href="' . append_sid(IP_ROOT_PATH . $return . '.' . PHP_EXT . $variables) . '">'. $lang['amp_char_error_msg_here'] .'</a>', $lang['amp_char_error_msg']));
}

function AMP_Gender($gender)
{
	global $lang, $images;

	if ($gender == 1)
		return ($lang['amp_char_cp_gender_m'] .'  <img src="' . $images['icon_minigender_male'] . '">');

	if ($gender == 2)
		return ($lang['amp_char_cp_gender_f'] .'  <img src="' . $images['icon_minigender_female'] . '">');
}

function AMP_Delete_Char($user)
{
	global $db, $lang;

	$q = "UPDATE ". USERS_TABLE ."
			SET ina_char_name = '',
			ina_char_age = '0',
			ina_char_from = '',
			ina_char_intrests = '',
			ina_char_img = '',
			ina_char_gender = '',
			ina_char_ge = '0',
			ina_char_ge_level = '1',
			ina_char_saying = '',
			ina_char_title = ''
			WHERE user_id = '". $user ."'";
	$db->sql_query($q);

	AMP_Error_Handler(1, $lang['amp_char_cp_delete_complete'], 'activity_char', '?sid='. $userdata['session_id']);
}

function AMP_Profile_Char($user, $char_profile)
{
	global $userdata, $db, $config, $lang, $bbcode;

	$q = "SELECT *
			FROM ". USERS_TABLE ."
			WHERE ina_char_name <> ''";
	$r = $db->sql_query($q);
	$info = $db->sql_fetchrowset($r);

	$q = "SELECT *
			FROM ". INA_HOF ."
			WHERE current_user_id = '". $user ."'";
	$r = $db->sql_query($q);
	$hof = $db->sql_numrows($r);

	#==== Thanks Dashe for the nasty SQL below.
	$q = "SELECT ". INA_LAST_GAME .".*, ". iNA_GAMES .".*
			FROM ". INA_LAST_GAME ." LEFT JOIN ". iNA_GAMES ."
			ON ". INA_LAST_GAME .".game_id = ". iNA_GAMES .".game_id
			WHERE ". INA_LAST_GAME .".user_id = '". $user ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);

	$profile = '';
	for ($a = 0; $a < sizeof($info); $a++)
	{
		if ($info[$a]['user_id'] == $user)
		{
			#==== Build character profile
			$char_gender = AMP_Gender($info[$a]['ina_char_gender']);
			$char_name = stripslashes(trim(AMP_Name_Effects_Pass(strip_tags($info[$a]['ina_char_name']), $info[$a]['ina_char_name_effects'])));
			$char_img = '<img src="' . $info[$a]['ina_char_img'] . '" alt="" />';
			$char_age = intval($info[$a]['ina_char_age']) .' '. $lang['amp_char_years'];
			$char_from = stripslashes(trim(strip_tags($info[$a]['ina_char_from'])));
			$char_ge_level = $info[$a]['ina_char_ge_level'];
			$char_ge = $info[$a]['ina_char_ge'];
			$bbcode->allow_html = ( $config['allow_html'] ? $config['allow_html'] : false );
			$bbcode->allow_bbcode = ( $config['allow_bbcode'] ? $config['allow_bbcode'] : false );
			$bbcode->allow_smilies = ( $config['allow_smilies'] ? $config['allow_smilies'] : false );
			$char_intrests = ($config['allow_smilies']) ? $bbcode->parse(stripslashes(trim(strip_tags($info[$a]['ina_char_intrests'])))) : stripslashes(trim(strip_tags($info[$a]['ina_char_intrests'])));
			//$char_intrests = stripslashes(trim(strip_tags($info[$a]['ina_char_intrests'])));
			$l_char_from = ($info[$a]['ina_char_from']) ? strip_tags($char_from) : $lang['amp_char_no_from'];
			$l_char_intrest = ($info[$a]['ina_char_intrests']) ? strip_tags($char_intrests) : $lang['amp_char_no_intrests'];
			$char_title = ($info[$a]['ina_char_title']) ? '<br />'. stripslashes(trim(AMP_Title_Effects_Pass(strip_tags($info[$a]['ina_char_title']), $info[$a]['ina_char_title_effects']))) .'<br /><br />' : '';
			$char_saying = ($info[$a]['ina_char_saying']) ? '<br /><br />'. stripslashes(trim(AMP_Saying_Effects_Pass(strip_tags($info[$a]['ina_char_saying']), $info[$a]['ina_char_saying_effects']))) .'<br /><br />' : '';

			#==== Build last game played & total games played
			$last_game_played = $row['proper_name'];
			$last_played_time = create_date($config['default_dateformat'], $row['date'], $config['board_timezone']);
			$total_plays = number_format($info[$a]['ina_games_played']);
			$last_played_type = $info[$a]['ina_last_playtype'];

			#==== Trophies & Hall of fame
			$total_trophies = ($info[$a]['user_trophies'] > 0) 	? str_replace('%T%', $info[$a]['user_trophies'], $lang['amp_char_total_trophies']) : str_replace('%T%', '0', $lang['amp_char_total_trophies']);
			$in_hof = str_replace('%A%', ($hof > 0) 	? $lang['amp_char_hof_member_y'] : $lang['amp_char_hof_member_n'], $lang['amp_char_hof_member']);

			if ($last_game_played)
			{
				$part_1 = str_replace('%G%', $last_game_played, $lang['amp_char_last_played_exp']);
				$part_2 = str_replace('%D%', $last_played_time, $part_1);
				$part_3 = str_replace('%T%', ucwords(strtolower($last_played_type)), $part_2);

				$last_played_line = $part_3;
			}
			else
			{
				$last_played_line = 'N/A';
			}

			$profile .= '<br clear="all" />';
			$profile .= '<table class="forumline" width="100%" cellspacing="0" cellpadding="0">';
			$profile .= '<tr>';
			$profile .= '	<td class="row-header" colspan="2"><span>' . $lang['char'] . '</span>';
			$profile .= '</tr>';
			$profile .= '<tr>';
			$profile .= '	<th align="center" width="40%">';
			$profile .= '		'. $char_name;
			$profile .= '	</th>';
			$profile .= '	<td width="60%" class="row2" align="left" valign="top" rowspan="2">';
			$profile .= '		<span class="genmed">';
			$profile .= '			'. $lang['amp_char_cp_gender'] .': '. $char_gender .'<br /><br />';
			$profile .= '			'. $lang['amp_char_cp_age'] .': '. $char_age .'<br /><br />';
			$profile .= '			'. $lang['amp_char_cp_from'] .': '. $l_char_from .'<br /><br />';
			$profile .= '			'. $lang['amp_char_cp_intrests'] .': '. $l_char_intrest .'<br /><br />';
			$profile .= '			'. $lang['amp_char_cp_ge'] .': '. $char_ge .'<br /><br />';
			$profile .= '			'. $lang['amp_char_cp_ge_level'] .': '. AMP_Level_GE($char_ge) .'<br /><br />';
			$profile .= '			'. $lang['amp_char_total_games'] .': '. str_replace('%T%', $total_plays, $lang['amp_char_total_games_exp']) .'<br /><br />';
			$profile .= '			'. $total_trophies .'<br /><br />';
			$profile .= '			'. $in_hof .'<br /><br />';
			$profile .= '			'. $lang['amp_char_last_played'] .': '. $last_played_line .'<br /><br />';
			$profile .= '			'. str_replace('%T%', DisplayPlayingTime(2, $info[$a]['ina_time_playing']), $lang['amp_char_time_spent']) .'<br />';
			$profile .= '		</span>';
			$profile .= '	</td>';
			$profile .= '</tr>';
			$profile .= '<tr>';
			$profile .= '	<td class="row2" align="left" valign="middle">';
			$profile .= '		<span class="genmed">';
			$profile .= '			<center>';
			$profile .= '			'. $char_title;
			$profile .= '			'. $char_img;
			$profile .= '			</center>';
			$profile .= '			'. $char_saying;
			$profile .= '		</span>';
			$profile .= '	</td>';
			$profile .= '</tr>';
			$profile .= '<tr>';
			$profile .= '	<td class="row2" align="left" valign="baseline" colspan="2">';
			$profile .= '		<span class="gensmall">';
			$profile .= '			'. str_replace('%T%', number_format($info[$a]['ina_char_views']), $lang['amp_char_pro_views']);
			$profile .= '		</span>';
			$profile .= '	</td>';
			$profile .= '</tr>';
			$profile .= '</table>';
		break;
		}
	}
	return $profile;
}

function AMP_Name_Effects_Pass($name, $effects)
{
	$what_effects = explode(',', $effects);
	for ($x = 0; $x < sizeof($what_effects); $x++)
	{
		#==== Bold Check
		if ($what_effects[$x] == 'b-')
			$name = '<b>'. $name .'</b>';
		#==== Italic Check
		if ($what_effects[$x] == 'i-')
			$name = '<i>'. $name .'</i>';
		#==== Underline Check
		if ($what_effects[$x] == 'u-')
			$name = '<u>'. $name .'</u>';

		#==== Color, Glow, Shadow Check
		if (eregi('c-', $what_effects[$x]) || eregi('g-', $what_effects[$x]) || eregi('s-', $what_effects[$x]))
		{
			#==== Shadow
			if (eregi('s-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$name = '<span style="filter: dropshadow(color='. $color[1] .', OffX=2, OffY=2, positive=2); height=10">'. $name .'</span>';
			}
			#==== Glow
			if (eregi('g-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$name = '<span style="filter: glow(color='. $color[1] .'); height=10">'. $name .'</span>';
			}
			#==== Color
			if (eregi('c-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$name = '<font color="'. $color[1] . '">'. $name .'</font>';
			}
		}
		if (!$what_effects[$x])
		{
			break;
		}
	}

	$name_effects_pass = $name;
	return $name_effects_pass;
}

function AMP_Title_Effects_Pass($title, $effects)
{
	$what_effects = explode(',', $effects);
	for ($x = 0; $x < sizeof($what_effects); $x++)
	{
		#==== Bold Check
		if ($what_effects[$x] == 'b-')
			$title = '<b>'. $title .'</b>';
		#==== Italic Check
		if ($what_effects[$x] == 'i-')
			$title = '<i>'. $title .'</i>';
		#==== Underline Check
		if ($what_effects[$x] == 'u-')
			$title = '<u>'. $title .'</u>';

		#==== Color, Glow, Shadow Check
		if (eregi('c-', $what_effects[$x]) || eregi('g-', $what_effects[$x]) || eregi('s-', $what_effects[$x]))
		{
			#==== Shadow
			if (eregi('s-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$title = '<span style="filter: dropshadow(color='. $color[1] .', OffX=2, OffY=2, positive=2); height=10">'. $title .'</span>';
			}
			#==== Glow
			if (eregi('g-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$title = '<span style="filter: glow(color='. $color[1] .'); height=10">'. $title .'</span>';
			}
			#==== Color
			if (eregi('c-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$title = '<font color="'. $color[1] . '">'. $title .'</font>';
			}
		}
		if (!$what_effects[$x])
		{
			break;
		}
	}

	$title_effects_pass = $title;
	return $title_effects_pass;
}

function AMP_Saying_Effects_Pass($saying, $effects)
{
	$what_effects = explode(',', $effects);
	for ($x = 0; $x < sizeof($what_effects); $x++)
	{
		#==== Bold Check
		if ($what_effects[$x] == 'b-')
			$saying = '<b>'. $saying .'</b>';
		#==== Italic Check
		if ($what_effects[$x] == 'i-')
			$saying = '<i>'. $saying .'</i>';
		#==== Underline Check
		if ($what_effects[$x] == 'u-')
			$saying = '<u>'. $saying .'</u>';

		#==== Color, Glow, Shadow Check
		if (eregi('c-', $what_effects[$x]) || eregi('g-', $what_effects[$x]) || eregi('s-', $what_effects[$x]))
		{
			#==== Shadow
			if (eregi('s-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$saying = '<span style="filter: dropshadow(color='. $color[1] .', OffX=2, OffY=2, positive=2); height=10">'. $saying .'</span>';
			}
			#==== Glow
			if (eregi('g-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$saying = '<span style="filter: glow(color='. $color[1] .'); height=10">'. $saying .'</span>';
			}
			#==== Color
			if (eregi('c-', $what_effects[$x]))
			{
				$color = explode('-', $what_effects[$x]);
				$saying = '<font color="'. $color[1] . '">'. $saying .'</font>';
			}
		}
		if (!$what_effects[$x])
		{
			break;
		}
	}

	$saying_effects_pass = $saying;
	return $saying_effects_pass;
}

?>