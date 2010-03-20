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

/* Start Version Check */
VersionCheck();
/*  End Version Check */

/* Start Restriction Checks */
BanCheck();
/* Start Specific Page Disabled */
if(($config['ina_disable_trophy_page']) && ($userdata['user_level'] != ADMIN)) message_die(GENERAL_ERROR, $lang['disabled_page_error'], $lang['ban_error']);
/* End Specific Page Disabled */
/* End Restriction Checks */

$search = $_GET['user'];
if ($search)
{
	$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_top_scores_search_body.tpl');
	$template->set_filenames(array('body' => $template_to_parse));

	$search = str_replace("%APOS%", "\'", $search);
	$search = stripslashes($search);
	$search = addslashes($search);

	if ($userdata['user_gender'] >= '0')
		$use_gender_mod = '1';

	$q1 = "SELECT *
			FROM ". USERS_TABLE ."
			WHERE username = '" . $search . "'";
	$r1 = $db->sql_query($q1);
	while($row = $db->sql_fetchrow($r1))
	{
		$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		$users_id = $row['user_id'];
		$user_regdate = $row['user_regdate'];
		$user_posts	 = $row['user_posts'];
		$user_level = $row['user_level'];
		$user_lastvisit = $row['user_lastvisit'];
		$user_rank = $row['user_rank'];

		if ($use_gender_mod == '1')
			$user_gender = $row['user_gender'];

		$q2 = "SELECT * FROM ". RANKS_TABLE ."";
		$r2 = $db->sql_query($q2);
		$row2 = $db->sql_fetchrowset($r2);

		if ($user_rank != '0')
		{
			for ($x = 0; $x < sizeof($row2); $x++)
			{
				if (($row2[$x]['rank_id'] == $user_rank) && ($row2[$x]['rank_special'] == '1'))
				{
					$user_rank_title = $row2[$x]['rank_title'];
					$user_rank_image = $row2[$x]['rank_image'];
					break;
				}
			}
		}
		else
		{
			for ($x = 0; $x < sizeof($row2); $x++)
			{
				if (($row2[$x]['rank_min'] <= $user_posts) && ($row2[$x]['rank_special'] == '0'))
				{
					$user_rank_title = $row2[$x]['rank_title'];
					$user_rank_image = $row2[$x]['rank_image'];
				}
			}
		}

		$user_regdate = create_date($lang['JOINED_DATE_FORMAT'], $user_regdate, $config['board_timezone']);
		$user_lastvisit = create_date_ip($config['default_dateformat'], $user_lastvisit, $config['board_timezone']);

		if ($user_gender == '0')
			$user_gender = $lang['gender_none'] ;
		if ($user_gender == '1')
			$user_gender = $lang['gender_male'] ;
		if ($user_gender == '2')
			$user_gender = $lang['gender_female'] ;
		if ($use_gender_mod <> '1')
			$user_gender = $lang['gender_not_installed'] ;

		if ($user_level == '0')
			$user_level = $lang['level_member'] ;
		if ($user_level == '1')
			$user_level = $lang['level_admin'] ;
		if ($user_level == '2')
			$user_level = $lang['level_mod'] ;
		if ($user_level == '3')
			$user_level = $lang['level_less_admin'] ;

		$msn = ($row['user_msnm']) ? '<a href="mailto: '. $row['user_msnm'] .'"><img src="'. $images['icon_msnm'] .'" alt="'. $lang['MSNM'] .'" title="'. $lang['MSNM'] .'" border="0" /></a>' : '';
		$yim = ($row['user_yim']) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target='. $row['user_yim'] .'&amp;.src=pg"><img src="'. $images['icon_yim'] .'" alt="'. $lang['YIM'] .'" title="'. $lang['YIM'] .'" border="0" /></a>' : '';
		$aim = ($row['user_aim']) ? '<a href="aim:goim?screenname='. $row['user_aim'] .'&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] .'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
		$icq = ($row['user_icq']) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto='. $row['user_icq'] .'"><img src="' . $images['icon_icq'] .'" alt="'. $lang['ICQ'] .'" title="' . $lang['ICQ'] .'" border="0" /></a>' : '';
		$www = ($row['user_website']) ? '<a href="'. $row['user_website'] .'" target="_userwww"><img src="'. $images['icon_www'] . '" alt="'. $lang['Visit_website'] .'" title="'. $lang['Visit_website'] .'" border="0" /></a>' : '';
		$mailto = ($config['board_email_form']) ? append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL . '=' . $row['user_id']) : 'mailto:' . $row['user_email'];
		$mail	 = ($row['user_email']) ? '<a href="' . $mailto . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>' : '';
		$pmto	 = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $row[user_id]);
		$pm = '<a href="' . $pmto . '"><img src="'. $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
		$pro = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row[user_id]);
		$profile = '<a href="' . $pro . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Profile'] . '" title="' . $lang['Profile'] . '" /></a>';
	}

		if (file_exists($user_rank_image))
			$rank_image_link = '<br /><img src="' . $user_rank_image . '" alt="" /><br />' . $user_rank_title;
		if (!file_exists($user_rank_image))
			$rank_image_link = '<br />'. $user_rank_title;

		$template->assign_block_vars('search_player', array(
			'L_LINK' => $lang['trophy_holders'],
			'U_LINK' => 'activity.' . PHP_EXT . '?page=trophy',
			'L_LINK_DESC' => $search . '\'s ' . $lang['game_profile'],
			'BUTTONS' => "$profile   $pm   $www   $mail   $msn   $yim   $aim   $icq",
			'TOP_ONE' => $lang['join_date'] .'<br />('. $lang['posts'] .')',
			'TOP_TWO' => $lang['last_visit'],
			'TOP_THREE' => $lang['gender'],
			'TOP_FOUR' => $lang['permissions'],
			'USERNAME' => $username,
			'RANK_IMAGE' => $rank_image_link,
			'BOTTOM_ONE' => $user_regdate .'<br />(' . $user_posts .')',
			'BOTTOM_TWO' => $user_lastvisit,
			'BOTTOM_THREE' => $user_gender,
			'BOTTOM_FOUR' => $user_level,
			'HEADER_ONE' => $lang['game'],
			'HEADER_TWO' => $lang['score_2'] . '<br />' . $lang['date_took'])
				);

	$i = 1;
	$q = "SELECT *
				FROM ". $table_prefix ."ina_top_scores
				WHERE player = '". $users_id ."'";
	$r = $db -> sql_query($q);
	while($row = $db -> sql_fetchrow($r))
	{
		$score = $row['score'];
		$game_name = $row['game_name'];
		$who = $username;
		$date = $row['date'];
		$score = FormatScores($score);
		$date = create_date_ip($config['default_dateformat'], $date, $config['board_timezone']);

		$q1 = "SELECT *
					FROM ". iNA_GAMES ."
					WHERE game_name = '". $game_name ."'";
		$r1 = $db -> sql_query($q1);
		$row1 = $db -> sql_fetchrow($r1);

		$game_image = '<center>' . htmlspecialchars($row1['proper_name']) .'</center><br />' . GameArrayLink($row1['game_id'], $row1['game_parent'], $row1['game_popup'], $row1['win_width'], $row1['win_height'], '3%SEP%'. CheckGameImages($game_name, $row1['proper_name']), '');
		$row_class = (!($i % 2)) ? 'row1' : 'row2';

			$template->assign_block_vars('search_player_games', array(
				'ROW_CLASS' => $row_class,
				'GAMES' => $game_image,
				'SCORE_DATE' => $score .'<br />'. $date
				)
			);
			$i++;
	}
}

$template->pparse('body');
/* Give credit where credit is due. */
echo ('
<script type="text/javascript">
function copyright()
{
	var popurl = \'' . ACTIVITY_PLUGIN_PATH . 'includes/functions_amod_plusC.' . PHP_EXT . '\'
	var winpops = window.open(popurl, "", "width=400, height=400,")
}
</script>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td align="left" valign="top">
<a style="text-decoration:none;" href="javascript:copyright();" class="gensmall">&copy; Activity Mod Plus</a>
</td>
</tr>
</table>
');
?>