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
if (($config['ina_disable_trophy_page']) && ($userdata['user_level'] != ADMIN))
{
	message_die(GENERAL_ERROR, $lang['disabled_page_error'], $lang['ban_error']);
}
/* End Specific Page Disabled */
/* End Restriction Checks */

$delete_action = (isset($_POST['action'])) ? $_POST['action'] : '';
$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$end = $config['games_per_page'];

if (($delete_action == 'delete_specific_score') && ($userdata['user_level'] == ADMIN))
{
	$delete_certain_score = (isset($_POST['delete_score'])) ? $_POST['delete_score'] : '';

	$q = "SELECT *
				FROM " . iNA_GAMES . "
				WHERE game_name = '" . $delete_certain_score . "'";
	$r = $db->sql_query($q);
	$order = $db->sql_fetchrow($r);

	if ($order['reverse_list'])
		$asc_desc = 'ASC';
	else
		$asc_desc = 'DESC';

	$q1 = "SELECT *
				FROM " . iNA_SCORES . "
				WHERE game_name = '" . $delete_certain_score . "'
				GROUP BY player
				ORDER BY score $asc_desc
				LIMIT 0, 1";
	$r1 = $db->sql_query($q1);
	$row = $db->sql_fetchrow($r1);

	$score1 = $row['score'];
	$player1 = $row['player'];

	$q1 = "SELECT *
				FROM " . iNA_SCORES . "
				WHERE game_name = '" . $delete_certain_score . "'
				GROUP BY player
				ORDER BY score $asc_desc
				LIMIT 1, 1";
	$r1 = $db->sql_query($q1);
	$row = $db->sql_fetchrow($r1);

	$score2 = $row['score'];
	$player2 = $row['player'];
	$player2 = stripslashes($player2);
	$player2 = addslashes($player2);

	$q1 = "SELECT user_id
				FROM " . USERS_TABLE . "
				WHERE username = '" . $player2 . "'";
	$r1 = $db->sql_query($q1);
	$row = $db->sql_fetchrow($r1);

	$player2_n = $row['user_id'];

	if(!$player2_n)
	{
		$player2_n = $userdata['user_id'];
	}

	$q1 = "UPDATE " . $table_prefix . "ina_top_scores
				SET player = '" . $player2_n . "', score = '" . $score2 . "', date = '" . time() . "'
				WHERE game_name = '" . $delete_certain_score . "'";
	$db->sql_query($q1);

	$q1 = "DELETE FROM " . iNA_SCORES . "
				WHERE game_name = '" . $delete_certain_score . "'
				AND score = '" . $score1 . "'
				AND player = '" . $player1 . "'";
	$db->sql_query($q1);

	message_die(GENERAL_MESSAGE, $lang['the_trophy_holder'] . $player1 . $lang['score_of'] . $score1 . $lang['been_deleted_n_replaced'] . $player2 . $lang['score_of'] . $score2 . $lang['please_click'] . 'activity.' . PHP_EXT . '?page=trophy'. $lang['here_to_return'], $lang['success']);
}

if (($delete_action == 'delete_all_scores') && ($userdata['user_level'] == ADMIN))
{
	$q1 = "UPDATE " . $table_prefix . "ina_top_scores
			SET player = '" . $userdata['user_id'] . "', score = '0', date = '" . time() . "'
			WHERE game_name <> ''";
	$db->sql_query($q1);

	message_die(GENERAL_MESSAGE, $lang['scores_reset'] . $userdata['username'] . $lang['zero_score'] . $lang['please_click'] . $_SERVER['PHP_SELF'] . $lang['here_to_return'], $lang['success']);
}

$search = (isset($_GET['user'])) ? $_GET['user'] : '';
if (!$search)
{
	$template->set_filenames(array('body' => ACTIVITY_TPL_PATH . 'activity_top_scores_body.tpl'));

	$q = "SELECT game_name, proper_name, game_id, game_parent, game_popup, win_width, win_height
				FROM " . iNA_GAMES;
	$r = $db->sql_query($q);
	$g_data = $db->sql_fetchrowset($r);
	$db->sql_freeresult($r);

	$q = "SELECT *
			FROM " . $table_prefix . "ina_top_scores
			ORDER BY game_name ASC";
	$r = $db->sql_query($q);
	$t_data = $db->sql_fetchrowset($r);
	$db->sql_freeresult($r);

	$q = "SELECT user_id, username, user_active, user_color
			FROM ". USERS_TABLE;
	$r = $db->sql_query($q);
	$u_data  = $db->sql_fetchrowset($r);
	$db->sql_freeresult($r);

	if ($userdata['user_level'] == ADMIN)
	{
		$template->assign_block_vars('admin', array());
		$games_drop = '';
		$games_drop .= '<select name="delete_score">';
		$games_drop .= '<option value="" class="post">----------</option>';
		for ($a = 0; $a < sizeof($t_data); $a++)
		{
			$games_drop .= '<option class="post" value="' . $t_data[$a]['game_name'] . '">' . $t_data[$a]['game_name'] . '</option>';
		}
		$games_drop .= '</select>';
	}

	$template->assign_vars(array(
		'L_DELETE_SPECIFIC' => $lang['delete_specific'],
		'L_DEFAULT_ONE' => $lang['admin_delete_default'] ,
		'L_DELETE_SINGLE' => $lang['delete_this_button'],
		'L_DELETE_ALL' => $lang['delete_all'],
		'L_DELETE_ALL_MSG' => $lang['delete_all_button'],
		'GAMES' => $games_drop,

		'L_T_LINK' => $lang['t_holder_link_name'],
		'U_T_LINK' => append_sid('activity.' . PHP_EXT . '?page=trophy'),
		'TROPHY_TOTAL' => '<a href="activity.' . PHP_EXT . '?page=trophy_holders">' . $lang['trophy_count_link'] . '</a>',
		'HEADER_ONE' => $lang['game'],
		'HEADER_TWO' => $lang['trophy_holder'],
		'HEADER_THREE' => $lang['score_owned_on'],
		'HEADER_FOUR' => $lang['contacts']
		)
	);

	for ($a = $start; $a < ($start + $end); $a++)
	{
		for ($b = 0; $b < sizeof($u_data); $b++)
		{
			if ($t_data[$a]['player'] == $u_data[$b]['user_id'])
			{
				$game_name = $t_data[$a]['game_name'];
				$who = $t_data[$a]['player'];
				$date = $t_data[$a]['date'];
				$score = FormatScores($t_data[$a]['score']);
				$date = create_date($config['default_dateformat'], $date, $config['board_timezone']);
				$user_n = stripslashes($u_data[$b]['username']);
				$fix_user_n = str_replace("'", "%APOS%", $user_n);
				$user_color = colorize_username($u_data[$b]['user_id'], $u_data[$b]['username'], $u_data[$b]['user_color'], $u_data[$b]['user_active'], false, true);

				$pmto = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $u_data[$b]['user_id']);
				$pm = '<a href="' . $pmto . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
				$pro = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $u_data[$b]['user_id']);
				$profile = '<a href="' . $pro . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Profile'] . '" title="' . $lang['Profile'] . '" border="0" /></a>';

				$game_image = '';
				for ($c = 0; $c < sizeof($g_data); $c++)
				{
					if ($g_data[$c]['game_name'] == $game_name)
					{
						$game_image = '<center>' . htmlspecialchars($g_data[$c]['proper_name']) . '</center><br />' . GameArrayLink($g_data[$c]['game_id'], $g_data[$c]['game_parent'], $g_data[$c]['game_popup'], $g_data[$c]['win_width'], $g_data[$c]['win_height'], '3%SEP%'. CheckGameImages($game_name, $g_data[$c]['proper_name']), '');
						break;
					}
				}

				$row_class = (!($a % 2)) ? 'row1' : 'row2';

				if ($user_data[$b]['user_id'] == ANONYMOUS)
				{
					$pm = '';
					$profile = '';
				}

				$template->assign_block_vars('top_scores_rows', array(
					'ROW_CLASS' => $row_class,
					'GAME_IMAGE' => $game_image,
					'USER_SEARCH' => '<a href="activity.' . PHP_EXT . '?page=trophy_search&amp;user=' . urlencode($fix_user_n) . '&amp;sid=' . $userdata['session_id'] . '" ' . $user_color . '>' . $user_n . '</a>',
					'SCORE_DATE' => $score . '<br />' . $date,
					'PM_PROFILE' => $pm . ' ' . $profile
					)
				);
			}
		}
	}
}

$pagination = generate_pagination('activity.' . PHP_EXT . '?page=trophy', sizeof($t_data), $config['games_per_page'], $start). ' ';

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER'  => sprintf($lang['Page_of'], floor(($start / $config['games_per_page']) + 1), ceil(sizeof($t_data) / $config['games_per_page']))
	)
);

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