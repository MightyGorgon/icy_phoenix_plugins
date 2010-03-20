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
	/* Start Ban Check */
	BanCheck();
	/* End Ban Check */
/* End Restriction Checks */

$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_whos_where_body.tpl');
$template->set_filenames(array('body' => $template_to_parse));

$template->assign_block_vars('main', array(
	'TITLE_ONE' => $lang['whos_where_title_1'],
	'TITLE_TWO' => $lang['whos_where_title_2'],
	'TITLE_THREE' => $lang['whos_where_title_3'],
	'TITLE_FOUR' => $lang['whos_where_contact_onsite'],
	'TITLE_FIVE' => $lang['whos_where_contact_offsite'],
	'LINK' => '<a href="index.' . PHP_EXT . '">'. $config['sitename'] .'</a>{NAV_SEP}<a href="activity.' . PHP_EXT . '">' . $lang['game_list'] . '</a>'
	)
);

if($userdata['user_level'] == ADMIN)
	$admin_where = '';
if($userdata['user_level'] != ADMIN)
	$admin_where = "AND user_allow_viewonline = '1'";

$t = 1;
$q = "SELECT playing_id
		FROM " . INA_SESSIONS . "
		GROUP BY playing_id";
$r = $db -> sql_query($q);
while($row = $db -> sql_fetchrow($r))
{
	$q1 = "SELECT *
				FROM ". USERS_TABLE ."
					WHERE user_id = '" . $row['playing_id'] . "'";
	$r1 = $db->sql_query($q1);
	$row1 = $db->sql_fetchrow($r1);

	$q2 = "SELECT *
				FROM ". iNA_GAMES ."
					WHERE game_id = '". $row1['ina_game_playing'] ."'";
	$r2 = $db -> sql_query($q2);
	$row2 = $db -> sql_fetchrow($r2);

	if ($row1['user_id'] != ANONYMOUS)
	{
		$link = colorize_username($row1['user_id'], $row1['username'], $row1['user_color'], $row1['user_active']);
	}
	else
	{
		$link = $lang['top_five_12'];
	}

	if ($row1['user_allow_viewonline'] == '0')
		$link = '<i>'. $link .'</i>';

	if (($row1['user_session_page'] == CMS_PAGE_ACTIVITY) && ($row1['ina_game_playing'] == '0'))
		$located = $lang['whos_where_viewing'];
	elseif (($row1['user_session_page'] == CMS_PAGE_ACTIVITY_GAME) && ($row1['ina_game_playing'] > '0'))
		$located = GameArrayLink($row2['game_id'], $row2['game_parent'], $row2['game_popup'], $row2['win_width'], $row2['win_height'], '3%SEP%'. $row2['proper_name'], '');
	elseif ($row1['user_session_page'] == CMS_PAGE_ACTIVITY)
		$located = $lang['whos_where_viewing'];
	else
		$located = $lang['whos_where_viewing_idle'];

	if (!$located)
		message_die(GENERAL_MESSAGE, $lang['whos_where_no_members'], $lang['whos_where_no_members_t']);

	$msn = ($row1['user_msnm']) ? '<a href="mailto: '. $row1['user_msnm'] .'"><img src="'. $images['icon_msnm'] .'" alt="'. $lang['MSNM'] .'" title="'. $lang['MSNM'] .'" border="0" /></a>' : '';
	$yim = ($row1['user_yim']) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target='. $row1['user_yim'] .'&amp;.src=pg"><img src="'. $images['icon_yim'] .'" alt="'. $lang['YIM'] .'" title="'. $lang['YIM'] .'" border="0" /></a>' : '';
	$aim = ($row1['user_aim']) ? '<a href="aim:goim?screenname='. $row1['user_aim'] .'&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] .'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
	$icq = ($row1['user_icq']) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto='. $row1['user_icq'] .'"><img src="' . $images['icon_icq'] .'" alt="'. $lang['ICQ'] .'" title="' . $lang['ICQ'] .'" border="0" /></a>' : '';
	$www = ($row1['user_website']) ? '<a href="'. $row1['user_website'] .'" target="_userwww"><img src="'. $images['icon_www'] . '" alt="'. $lang['Visit_website'] .'" title="'. $lang['Visit_website'] .'" border="0" /></a>' : '';
	//$mailto = ($config['board_email_form']) ? append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL . '=' . $row1['user_id']) : 'mailto:' . $row1['user_email'];
	$mailto = ($config['board_email_form'] && !empty($row1['user_viewemail'])) ? append_sid(CMS_PAGE_PROFILE . '?mode=email&amp;' . POST_USERS_URL . '=' . $row1['user_id']) : '';
	$mail = ($row1['user_email'] && !empty($row1['user_viewemail'])) ? '<a href="' . $mailto . '"><img src="'. $images['icon_email'] .'" alt="'. $lang['Send_email'] .'" title="' . $lang['Send_email'] . '" border="0" /></a>' : '';
	$pmto = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $row1[user_id]);
	$pm = '<a href="' . $pmto . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
	$pro = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row1[user_id]);
	$profile = '<a href="' . $pro . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Profile'] . '" title="' . $lang['Profile'] . '" /></a>';

	if($row1['user_id'] == ANONYMOUS)
	{
		$msn = '';
		$yim = '';
		$aim = '';
		$icq = '';
		$www = '';
		$mailto = '';
		$mail = '';
		$pmtp = '';
		$pm = '';
		$pro = '';
		$profile = '';
	}

	$row_class = (!($t % 2)) ? 'row1' : 'row2';

	$template->assign_block_vars('rows', array(
		'ROW_CLASS' => $row_class,
		'ONSITE' => $pm . '&nbsp;' . $profile,
		'OFFSITE' => $www . '&nbsp;' . $msn . '&nbsp;' . $yim . '&nbsp;' . $aim . '&nbsp;' . $icq . '&nbsp;' . $mail,
		'NAME' => '&nbsp;'. $link,
		'NUMBER' => $t,
		'WHERE' => '&nbsp;'. $located
		)
	);
	$t++;
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