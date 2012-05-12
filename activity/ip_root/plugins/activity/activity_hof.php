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

$meta_content['page_title'] = $lang['hof_page_title'];

$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_hof_body.tpl');
$template->set_filenames(array('body' => $template_to_parse));

$template->assign_vars(array(
	'TITLE' => $meta_content['page_title'],
	'ONE' => $lang['hof_top_one'],
	'TWO' => $lang['hof_top_two'],
	'THREE' => $lang['hof_top_three'],
	'FOUR' => $lang['hof_top_four']
	)
);

$i = 0;
$begin = ($_GET['start']) ? $_GET['start'] : $_GET['start'];
$start = ($begin) ? $begin : '0';
$finish = $config['games_per_page'];

$q = "SELECT user_id, username, user_active, user_color FROM " . USERS_TABLE;
$r = $db -> sql_query($q);
$users_data = $db -> sql_fetchrowset($r);
$db->sql_freeresult($r);

$q = "SELECT * FROM " . INA_HOF . "
		GROUP BY game_id
		LIMIT $start, $finish";
$r = $db -> sql_query($q);
$hof_data = $db -> sql_fetchrowset($r);
$db->sql_freeresult($r);

$q = "SELECT * FROM " . iNA_GAMES . "
		GROUP BY game_id";
$r = $db -> sql_query($q);
$game_data = $db -> sql_fetchrowset($r);
$db->sql_freeresult($r);

unset($proper_name, $game_name, $game_id, $old_user_id, $old_username, $old_score, $old_date, $new_user_id, $new_username, $new_score, $new_date, $game_image);
for ($a = 0; $a < sizeof($hof_data); $a++)
{
	for ($b = 0; $b < sizeof($game_data); $b++)
	{
		if ($hof_data[$a]['game_id'] == $game_data[$b]['game_id'])
		{
			$proper_name = $game_data[$b]['proper_name'];
			$game_name = $game_data[$b]['game_name'];
			$game_id = $game_data[$b]['game_id'];
			$game_parent = $game_data[$b]['game_parent'];
			$game_popup = $game_data[$b]['game_popup'];
			$game_width = $game_data[$b]['win_width'];
			$game_height = $game_data[$b]['win_height'];
			break;
		}
	}
	$old_score = '';
	$old_date = '';
	$old_user_id = '';
	$old_username = '';

	for ($c = 0; $c < sizeof($users_data); $c++)
	{
		if ($hof_data[$a]['current_user_id'] == $users_data[$c]['user_id'])
		{
			$new_user_id = $users_data[$c]['user_id'];
			$new_user_name = $users_data[$c]['username'];
			$new_user_active = $users_data[$c]['user_active'];
			$new_user_color = $users_data[$c]['user_color'];
			$new_username = colorize_username($users_data[$c]['user_id'], $users_data[$c]['username'], $users_data[$c]['user_color'], $users_data[$c]['user_active']);
			break;
		}
	}

	for ($d = 0; $d < sizeof($users_data); $d++)
	{
		if ($hof_data[$a]['old_user_id'] == $users_data[$d]['user_id'])
		{
			$old_user_id = $users_data[$d]['user_id'];
			$old_user_name = $users_data[$c]['username'];
			$old_user_active = $users_data[$c]['user_active'];
			$old_user_color = $users_data[$c]['user_color'];
			$old_username = colorize_username($users_data[$d]['user_id'], $users_data[$d]['username'], $users_data[$d]['user_color'], $users_data[$d]['user_active']);
			break;
		}
	}

	$new_score = FormatScores($hof_data[$a]['current_score']);
	$new_date = create_date_ip($config['default_dateformat'], $hof_data[$a]['current_date'], $config['board_timezone']);
	$old_score = FormatScores($hof_data[$a]['old_score']);
	$old_date = create_date_ip($config['default_dateformat'], $hof_data[$a]['old_date'], $config['board_timezone']);
	$game_image = CheckGameImages($game_name, $proper_name);
	$row_class = (!($i % 2)) ? 'row1' : 'row2';
	if (!$old_user_id)
	{
		$old_score = $old_date = $old_user_id = $old_username = '';
	}

	if ($i % 2)
	{
		$block_var = 'hof_left';
	}
	else
	{
		$block_var = 'hof_right';
	}

	$template->assign_block_vars($block_var, array(
		'ONE' => '<b>' . $proper_name . '</b><br />' . GameArrayLink($game_id, $game_parent, $game_popup, $game_width, $game_height, '3%SEP%' . CheckGameImages($game_name, $proper_name), ''),
		'TWO' => colorize_username($new_user_id, $new_user_name, $new_user_color, $new_user_active),
		'THREE' => $new_score,
		'FOUR' => $new_date,
		'FIVE' => $lang['hof_page_previous'],
		'SIX' => colorize_username($old_user_id, $old_user_name, $old_user_color, $old_user_active),
		//'SIX' => ($old_user_id != ANONYMOUS) ? '<a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $old_user_id) . '">' . $old_username . '</a>' : $old_username,
		'SEVEN' => $old_score,
		'EIGHT' => $old_date,
		'ROW' => $row_class
		)
	);

	$i++;
	if (!$game_id)
	{
		break;
	}
}

$q = "SELECT *
		FROM ". INA_HOF ."
		GROUP BY game_id";
$r = $db -> sql_query($q);
$hof_count = $db -> sql_numrows($r);

$pagination = generate_pagination('activity.' . PHP_EXT . '?page=hof&amp;next', $hof_count, $config['games_per_page'], $start) . '&nbsp;';
$page_number = sprintf($lang['Page_of'], (floor($start / $config['games_per_page']) + 1), ceil($hof_count / $config['games_per_page']));

$template->assign_vars(array(
	'PAGE_1' => $page_number,
	'PAGE_2' => $pagination
	)
);


// Generate page
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