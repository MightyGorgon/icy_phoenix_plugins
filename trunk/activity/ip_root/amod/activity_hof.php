<?php
/***************************************************************************
 *                            activity_hof.php
 *                           ------------------
 *		Version			: 1.1.0
 *		Email			: austin@phpbb-amod.com
 *		Site			: http://phpbb-amod.com
 *		Copyright		: aUsTiN-Inc 2003/5
 *
 ***************************************************************************/

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

$page_title = $lang['hof_page_title'];
$template->set_filenames(array('body' => ACTIVITY_MOD_PATH . 'activity_hof_body.tpl') );

$template->assign_vars(array(
	'TITLE' => $page_title,
	'ONE' => $lang['hof_top_one'],
	'TWO' => $lang['hof_top_two'],
	'THREE' => $lang['hof_top_three'],
	'FOUR' => $lang['hof_top_four']
	)
);

$i = 0;
$begin = ($_GET['start']) ? $_GET['start'] : $_GET['start'];
$start = ($begin) ? $begin : '0';
$finish = $board_config['games_per_page'];

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
for ($a = 0; $a < count($hof_data); $a++)
{
	for ($b = 0; $b < count($game_data); $b++)
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

	for ($c = 0; $c < count($users_data); $c++)
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

	for ($d = 0; $d < count($users_data); $d++)
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
	$new_date = create_date2($board_config['default_dateformat'], $hof_data[$a]['current_date'], $board_config['board_timezone']);
	$old_score = FormatScores($hof_data[$a]['old_score']);
	$old_date = create_date2($board_config['default_dateformat'], $hof_data[$a]['old_date'], $board_config['board_timezone']);
	$game_image = CheckGameImages($game_name, $proper_name);
	$row_class = ( !($i % 2) ) ? 'row1' : 'row2';
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
		//'SIX' => ($old_user_id != ANONYMOUS) ? '<a href="' . append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $old_user_id) . '">' . $old_username . '</a>' : $old_username,
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

$pagination = generate_pagination('activity.' . PHP_EXT . '?page=hof&amp;next', $hof_count, $board_config['games_per_page'], $start) . '&nbsp;';
$page_number = sprintf($lang['Page_of'], ( floor($start / $board_config['games_per_page'] ) + 1 ), ceil($hof_count / $board_config['games_per_page']));

$template->assign_vars(array(
	'PAGE_1' => $page_number,
	'PAGE_2' => $pagination
	)
);


// Generate page
$template->pparse('body');

/* Give credit where credit is due. */
echo ('
<script language="JavaScript">
function copyright()
{
	var popurl = \'' . ACTIVITY_MOD_PATH . 'includes/functions_amod_plusC.' . PHP_EXT . '\'
	var winpops = window.open(popurl, "", "width=400, height=400,")
}
</script>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td align="left" valign="top">
<a style="text-decoration:none;" href="javascript:copyright();"><span class="gensmall">&copy; Activity Mod Plus</a></span>
</td>
</tr>
</table>
');
?>