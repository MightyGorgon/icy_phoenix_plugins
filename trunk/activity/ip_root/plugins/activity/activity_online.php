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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_online_body.tpl');
$template->set_filenames(array('activity_online_section' => $template_to_parse));

$expired = time() - 300;

$q = "SELECT COUNT(*) AS total
	FROM ". INA_SESSIONS ."
	 WHERE playing = '0'
	 AND playing_time >= '". $expired ."'";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$playing_guests = $row['total'];

if ($playing_guests == '0')
{
	$message3 = $lang['online_no_guests'];
	$message4 = "";
	$playing_guests = "";
}
elseif ($playing_guests == '1')
{
	$message3 = $lang['online_g_one_1'];
	$message4 = $lang['online_g_one_2'];
}
else
{
	$message3 = $lang['online_g_1'];
	$message4 = $lang['online_g_2'];
}

$q = "SELECT COUNT(*) AS total
	FROM ". INA_SESSIONS ."
	 WHERE playing = '1'
	 AND playing_time >= '". $expired ."'";
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);
$playing_members = $row['total'];

	if ($playing_members == '0')
	{
		$message1 = $lang['online_no_members'] ;
		$message2 = '';
		$playing_members = '';
	}
	elseif ($playing_members == '1')
	{
		$message1 = $lang['online_m_one_1'];
		$message2 = $lang['online_m_one_2'];
	}
	else
	{
		$message1 = $lang['online_m_1'];
		$message2 = $lang['online_m_2'];
	}

	$q = "SELECT COUNT(*) AS total
		FROM ". USERS_TABLE ."
		 WHERE (user_session_page = '". CMS_PAGE_ACTIVITY ."' OR user_session_page = '". CMS_PAGE_ACTIVITY_GAME ."')
		 AND user_session_time >= '". $expired ."'
		 AND user_allow_viewonline = '0'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	$playing_hidden = $row['total'];

	if ($playing_hidden == '1')
	{
		$message5 = $lang['online_no_hidden'];
	}
	elseif ($playing_hidden == '1')
	{
		$message5 = $lang['online_one_hidden'];
	}
	else
	{
		$message5 = str_replace('%n%', $playing_hidden, $lang['online_x_hidden']);
	}

	$template->assign_block_vars('playing_games', array(
		'TOTAL_PLAYING' => $playing_total,
		'TOTAL_M_PLAYING' => $playing_members,
		'TOTAL_G_PLAYING' => $playing_guests,
		'ONLINE_TITLE' => $lang['online_title_bar'],
		'CURRENTLY_PLAYING1' => $message1,
		'CURRENTLY_PLAYING2' => $message2,
		'CURRENTLY_PLAYING3' => $message3,
		'CURRENTLY_PLAYING4' => $message4 . '<br /><a href="activity.' . PHP_EXT . '?page=whos_where&amp;sid=' . $userdata['session_id'] . '">' . $lang['whos_where_link'] . '</a><br />',
		'CURRENTLY_PLAYING5' => $message5,
		'MAIN_COLOR1' => '[ <span class="text_red">' . $lang['online_viewing_games'] . '</span> ]',
		'MAIN_SEPERATOR' => '<b> :: </b>',
		'MAIN_COLOR2' => '[ <span class="text_green">' . $lang['online_playing_games'] . '</span> ]'
		)
	);

	$q = "SELECT *
			FROM " . INA_SESSIONS . "
			WHERE playing = '1'
				AND playing_time >= '" . $expired . "'";
	$r = $db->sql_query($q);
	while ($row = $db->sql_fetchrow($r))
	{
		$playing_id = $row['playing_id'];

		if ($userdata['user_level'] == ADMIN)
		{
			$admin_hidden = '';
		}
		if ($userdata['user_level'] != ADMIN)
		{
			$admin_hidden = "AND user_allow_viewonline = '1'";
		}

		$q1 = "SELECT user_id, username, user_active, user_color, user_session_page, user_allow_viewonline
				FROM " . USERS_TABLE . "
				WHERE user_id = '" . $playing_id . "'
			$admin_hidden";
		$r1 = $db->sql_query($q1);
		$row = $db->sql_fetchrow($r1);
		$playing_user = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		$playing_where = $row['user_session_page'];
		$playing_hidden = $row['user_allow_viewonline'];

		if ((!$playing_hidden) && ($userdata['user_level'] == ADMIN))
		{
			$playing_user = '<i>' . $playing_user . '</i>';
		}

		if (($playing_where == CMS_PAGE_ACTIVITY) || ($playing_where == CMS_PAGE_ACTIVITY_GAME))
		{
			$playing_user = '<b>' . $playing_user . '</b>';
		}

		if ($playing_id != ANONYMOUS)
		{
			$username_link = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		}

		$template->assign_block_vars('playing', array(
			'USERNAME' => $username_link,
			'USER_NUMBER'  => '&nbsp;',
			'MAIN_SEPERATOR' => '&nbsp;'
			)
		);
	}
	$template->assign_var_from_handle('ACTIVITY_ONLINE_SECTION', 'activity_online_section');

?>