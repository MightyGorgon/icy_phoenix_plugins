<?php
/***************************************************************************
 *                             activity_top_five.php
 *                            -----------------------
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
/* Start Ban Check */
BanCheck();
/* Start File Specific Disable */
if(($userdata['user_level'] != ADMIN) && ($board_config['ina_disable_top5_page']))
	message_die(GENERAL_ERROR, $lang['disabled_page_error'], $lang['ban_error']);
/* End File Specific Disable */
/* End Restriction Checks */

$start = ( isset($_GET['start']) ) ? intval($_GET['start']) : 0;
$finish = $board_config['games_per_page'];

$template->set_filenames(array('body' => ACTIVITY_MOD_PATH . 'activity_top5.tpl') );

	$template->assign_block_vars('top_five_keys', array(
		'TROPHY_TITLE' => $lang['top_five_1'],
		'GAMES_PLAYED' => $lang['top_five_2'],
		'MOST_COMMENTS' => $lang['top_five_3'],
		'CHALLENGERS' => $lang['top_five_4'],
		'GAMBLERS' => $lang['top_five_5'],
		'GAMBLE_WINNERS' => $lang['top_five_6'],
		'TOP_TITLE' => $lang['top_five_7'],
		'BOTTOM_TITLE' => $lang['top_five_8'],
		'LINKS' => '<b>::</b> <a href="activity.' . PHP_EXT . '?sid='. $userdata['session_id'] .'" class="nav">'. $lang['top_five_9'] .'</a> <b>::</b> <a href="activity.' . PHP_EXT . '?page=top&amp;sid='. $userdata['session_id'] .'" class="nav">'. $lang['top_five_10'] .'</a> <b>::</b>',
		'TITLE' => $board_config['sitename'] ."'". $lang['top_five_11'])
			);

#==== Users Query To Be Used Many Times Here
#==== Saved 5 SQL's Per Game + 5 From Challenge Array (205 Queries @ phpbb-amod)
$q = "SELECT username, user_id, user_active, user_color
			FROM " . USERS_TABLE . "";
$r = $db->sql_query($q);
$users_info = $db->sql_fetchrowset($r);
$db->sql_freeresult($result);

#==== Removed A Challenge Query, Saved 5 SQL's
#==== Removed 2 Gamble Queries, Saved 10 SQL's

/* Start top 5 trophy holders by Skullbone (http://rant-board.com/(skullbone67@lethalvapors.com)) */
$t = 1;
$q = "SELECT *
		FROM " . USERS_TABLE . "
		ORDER BY user_trophies DESC
		LIMIT 0, 5";
$r = $db->sql_query($q);
while ($row = $db->sql_fetchrow($r))
{
	if ($row['user_id'] != ANONYMOUS)
	{
		$link = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
	}
	if ($row['user_id'] == ANONYMOUS)
	{
		$link = $lang['top_five_12'];
	}

	$template->assign_block_vars('trophy', array(
		'TROPHY_TOP_HOLDER' => $t . '.&nbsp;' . $link,
		'AMOUNT' => '<a href="activity.' . PHP_EXT . '?page=trophy_search&amp;user=' . $row['username'] . '&amp;sid=' . $userdata['session_id'] . '" class="nav">' . number_format($row['user_trophies']) .'</a>'
		)
	);
	$t++;
}
$db->sql_freeresult($result);
/*  End top 5 trophy holders by Skullbone (http://rant-board.com/(skullbone67@lethalvapors.com))*/

$t = 1;
$q = "SELECT *
		FROM ". iNA_GAMES ."
			 ORDER BY played DESC
			 LIMIT 0, 5";
$r = $db->sql_query($q);
while ($row = $db->sql_fetchrow($r))
{
	$template->assign_block_vars('played', array(
		'TOP_GAMES' => $t .'.&nbsp;'. GameArrayLink($row['game_id'], $row['game_parent'], $row['game_popup'], $row['win_width'], $row['win_height'], '3%SEP%'. $row['proper_name'], ''),
		'AMOUNT' => number_format($row['played'])
		)
	);
	$t++;
}
$db->sql_freeresult($result);

$t = 1;
$q = "SELECT COUNT(player) AS total, player
		FROM ". INA_TROPHY_COMMENTS ."
		GROUP BY player
			 ORDER BY total DESC
			 LIMIT 0, 5";
$r = $db->sql_query($q);
while ($row = $db->sql_fetchrow($r))
{

	unset($user_id);
	unset($username);
	unset($user_active);
	unset($user_color);
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $row['player'])
		{
			$user_id = $users_info[$x]['user_id'];
			$username = $users_info[$x]['username'];
			$user_active = $users_info[$x]['user_active'];
			$user_color = $users_info[$x]['user_color'];
			break;
		}
	}

	if ($user_id != ANONYMOUS)
	{
		$link = colorize_username($user_id, $username, $user_color, $user_active);
	}
	else
	{
		$link = $lang['top_five_12'];
	}

	$template->assign_block_vars("comments", array(
		'COMMENTS' => $t . '.&nbsp;' . $link,
		'AMOUNT' => number_format($row['total'])
		)
	);
	$t++;
}
$db->sql_freeresult($result);

$t = 1;
$q = "SELECT SUM(count) AS total, user_from
		FROM ". INA_CHALLENGE_USERS ."
		GROUP BY user_from
			ORDER BY total DESC
			LIMIT 0, 5";
$r = $db->sql_query($q);
while ($row = $db->sql_fetchrow($r))
{

	unset($username);
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $row['user_from'])
		{
			$username = $users_info[$x]['username'];
			break;
		}
	}

	if ($row['user_from'] != ANONYMOUS)
	{
		$link = colorize_username($row['user_from']);
	}
	if ($row['user_from'] == ANONYMOUS)
	{
		$link = $lang['top_five_12'];
	}

	$template->assign_block_vars('challenge', array(
		'CHALLENGES' => $t .'.&nbsp;'. $link,
		'AMOUNT' => number_format($row['total'])
		)
	);
	$t++;
	}

$t = 1;
$q = "SELECT COUNT(sender_id) AS total, sender_id
		FROM ". INA_GAMBLE ."
		GROUP BY sender_id
			 ORDER BY total DESC
			 LIMIT 0, 5";
$r = $db->sql_query($q);
while ($row = $db->sql_fetchrow($r))
{
	unset($username);
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $row['sender_id'])
		{
			$username = $users_info[$x]['username'];
			break;
		}
	}

	if ($row['sender_id'] != ANONYMOUS)
	{
		$link = colorize_username($row['sender_id']);
	}
	if ($row['sender_id'] == ANONYMOUS)
	{
		$link = $lang['top_five_12'];
	}

	$template->assign_block_vars('bets', array(
		'BETS' => $t .'.&nbsp'. $link,
		'AMOUNT' => number_format($row['total'])
		)
	);
	$t++;
}

$t = 1;
$q = "SELECT COUNT(winner_id) AS total, winner_id
		FROM ". INA_GAMBLE ."
		WHERE winner_score > 0
		AND loser_score > 0
		GROUP BY winner_id
			 ORDER BY total DESC
			 LIMIT 0, 5";
$r = $db->sql_query($q);
while ($row = $db->sql_fetchrow($r))
{
	unset($username);
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $row['winner_id'])
		{
			$username = $users_info[$x]['username'];
			break;
		}
	}

	if ($row['winner_id'] != ANONYMOUS)
	{
		$link = colorize_username($row['winner_id']);
	}
	if ($row['winner_id'] == ANONYMOUS)
	{
		$link = $lang['top_five_12'];
	}

	$template->assign_block_vars('bet_winners', array(
		'WINNERS' => $t .'.&nbsp;'. $link,
		'AMOUNT' => number_format($row['total'])
		)
	);
	$t++;
}

$nbcol = 3;
$t = 1;
$q = "SELECT distinct game_id, game_name, reverse_list, proper_name, game_parent, game_popup, win_width, win_height
		FROM ". iNA_GAMES ."
		GROUP BY game_id
		LIMIT $start, $finish";
if (!$r = $db->sql_query($q))
	message_die(GENERAL_ERROR, $lang['top_five_13'], '', __LINE__, __FILE__, $q);

$error = '';
if (!$row = $db->sql_fetchrow($r))
{
	$error = true;
}
while (!$error)
{
	$template -> assign_block_vars('one', array());
	for ($cg = 1 ; $cg <= $nbcol ; $cg++)
	{
		$template->assign_block_vars('one.two', array());
		if (!$error)
		{
			$template->assign_block_vars('one.two.games_name', array(
				'NAME' => GameArrayLink($row['game_id'], $row['game_parent'], $row['game_popup'], $row['win_width'], $row['win_height'], '3%SEP%'. $row['proper_name'], '')
				)
			);

			if ($row['reverse_list'] == '1')
			{
				$order_type = 'ASC';
			}
			else
			{
				$order_type = 'DESC';
			}

			$pos = 0;
			$posreelle = 0;
			$lastscore = 0;

			$q1 = "SELECT *
					FROM ". iNA_SCORES ."
					WHERE game_name = '". $row['game_name'] ."'
					GROUP BY player
					ORDER BY score $order_type
					LIMIT 0, 5";
			if (!$result2 = $db->sql_query($q1))
			{
				message_die(GENERAL_ERROR, $lang['top_five_14'], '', __LINE__, __FILE__, $q1);
			}

			while ($row1 = $db->sql_fetchrow($result2))
			{
				unset($user_id);
				for ($x = 0; $x < count($users_info); $x++)
				{
					if ($users_info[$x]['username'] == $row1['player'])
					{
						$user_id = $users_info[$x]['user_id'];
						$username = $user_data[$a]['username'];
						$user_active = $user_data[$a]['user_active'];
						$user_color = $user_data[$a]['user_color'];
						break;
					}
				}

				if ($user_id != ANONYMOUS)
				{
					$link = colorize_username($user_id, $username, $user_color, $user_active);
				}
				else
				{
					$link = $lang['top_five_12'];
				}

				$posreelle++ ;
				if ($lastscore != $row1['score'])
				$pos = $posreelle ;

				$lastscore = $row1['score'] ;
				$template -> assign_block_vars('one.two.games_name.games', array(
					'NUMBER' => $t .'.',
					'GAMES' => '&nbsp;'. $link,
					'SCORE' => FormatScores($row1['score'])
					)
				);
				$t++;
			}
			$t = 1;
			$order_type = '';
			if (!$row = $db->sql_fetchrow($r))
			{
				$error = true;
			}
		}
	}
}

$sql = "SELECT count(game_id) AS total
		FROM " . iNA_GAMES . "
		WHERE game_id <> '0'";
if (!$result = $db->sql_query($sql))
	message_die(GENERAL_ERROR, $lang['no_game_total'], '', __LINE__, __FILE__, $sql);

if ($total = $db->sql_fetchrow($result))
	{
$total_games = $total['total'];
$pagination = generate_pagination('activity.' . PHP_EXT . '?page=top&amp;mode=next_' . $board_config['games_per_page'], $total_games, $board_config['games_per_page'], $start) . '&nbsp;';
	}

	$template->assign_block_vars('pagination', array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['games_per_page'] ) + 1 ), ceil( $total_games / $board_config['games_per_page'] ))
		)
	);

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