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
/* End Restriction Checks */

$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_trophy_holders_body.tpl');
$template->set_filenames(array('body' => $template_to_parse));

$template->assign_vars(array(
	'U_TROPHY_PAGE' => append_sid('activity.' . PHP_EXT . '?page=trophy'),
	'L_POSITION' => $lang['trophy_count_1'],
	'L_TROPHIES' => $lang['trophy_count_2'],
	'L_USER_SEARCH' => $lang['trophy_count_3'],
	'L_PM_PROFILE' => $lang['trophy_count_4'],
	'L_LINK' => $lang['trophy_count_link']
	)
);

$i = 1;
$s = 0;

$q = "SELECT *
		FROM ". USERS_TABLE ."
		WHERE user_trophies > '0'
		ORDER BY user_trophies DESC";
$r = $db -> sql_query($q);

while($row = $db -> sql_fetchrow($r))
{
	$user_n = $row['username'];
	$user_c = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active'], true);
	$trophies = $row['user_trophies'];

	$pmto = append_sid(CMS_PAGE_PRIVMSG . '?mode=post&amp;' . POST_USERS_URL . '=' . $row[user_id]);
	$pm = '<a href="' . $pmto . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" alt="" /></a>';
	$pro = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row[user_id]);
	$profile = '<a href="' . $pro . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Profile'] . '" title="' . $lang['Profile'] . '" /></a>';

	$row_class = (!($i % 2)) ? 'row1' : 'row2';

	if($row['user_id'] == ANONYMOUS)
	{
		$pm = "";
		$profile = "";
	}

	$template->assign_block_vars('top_trophies', array(
		'ROW_CLASS' => $row_class,
		'POSITION' => $i,
		'TROPHIES' => $trophies,
		'USER_SEARCH' => '<a href="activity.' . PHP_EXT . '?page=trophy_search&amp;user=' . urlencode($user_n) . '&ampsid=' . $user->data['session_id'] . '" style="text-decoration:none">' . $user_c . '</a>',
		'PM_PROFILE' => $pm . ' &nbsp; ' . $profile)
		);
	$i++;
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