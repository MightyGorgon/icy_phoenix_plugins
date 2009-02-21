<?php
/***************************************************************************
 *                            activity_services.php
 *                           -----------------------
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

	$mode = ($_GET['mode']) ? $_GET['mode'] : $_GET['mode'];
		if (!$mode)
			$mode = ($_POST['mode']) ? $_POST['mode'] : $_POST['mode'];

	$template->set_filenames(array('body' => ACTIVITY_MOD_PATH . 'activity_services_body.tpl') );

	$status_on = '<img src="' . ACTIVITY_MOD_PATH . 'images/on.png" alt="" />';
	$status_off = '<img src="' . ACTIVITY_MOD_PATH . 'images/off.png" alt="" />';

	$current_setting = $board_config['ina_default_order'];
	if($current_setting == '1') $current = $lang['corder_gpA'];
	if($current_setting == '2') $current = $lang['corder_cpD'];
	if($current_setting == '3') $current = $lang['corder_na'];
	if($current_setting == '4') $current = $lang['corder_oa'];
	if($current_setting == '5') $current = $lang['corder_bA'];
	if($current_setting == '6') $current = $lang['corder_bD'];
	if($current_setting == '7') $current = $lang['corder_cA'];
	if($current_setting == '8') $current = $lang['corder_cD'];
	if($current_setting == '9') $current = $lang['corder_properA'];
	if($current_setting == '10') $current = $lang['corder_properD'];

	$template->assign_vars(array(
		'LEFT_TITLE' => $lang['services_service'],
		'RIGHT_TITLE' => $lang['services_service_status'],
		'POINTS_MOD' => ($board_config['use_point_system'] == 1) ? $status_on : $status_off,
		'CASH_MOD' => ($board_config['use_cash_system'] == 1) ? $status_on : $status_off,
		'ALLOWANCE_MOD' => ($board_config['use_allowance_system'] == 1) ? $status_on : $status_off,
		'AUTO_DELETE' => ($board_config['ina_delete'] == 1) ? $status_on : $status_off,
		'CHALLENGE_SYSTEM' => ($board_config['ina_challenge'] == 1) ? $status_on : $status_off,
		'TROPHY_LOSS' => ($board_config['ina_pm_trophy'] == 1) ? $status_on : $status_off,
		'DAILY_GAME' => ($board_config['ina_use_daily_game'] == 1) ? $status_on : $status_off,
		'ONLINE_LIST' => ($board_config['ina_use_online'] == 1) ? $status_on : $status_off,
		'NEWEST_GAMES' => ($board_config['ina_use_newest'] == 1) ? $status_on : $status_off,
		'SHOUTBOX' => ($board_config['ina_use_shoutbox'] == 1) ? $status_on : $status_off,
		'TROPHY_TOPIC' => ($board_config['ina_show_view_topic'] == 1) ? $status_on : $status_off,
		'TROPHY_PROFILE' => ($board_config['ina_show_view_profile'] == 1) ? $status_on : $status_off,
		'TROPHY_KING' => ($board_config['ina_use_trophy'] == 1) ? $status_on : $status_off,
		'MEMBER_SUBMIT' => ($board_config['ina_disable_submit_scores_m'] == 1) ? $status_on : $status_off,
		'GUEST_PLAY' => ($board_config['ina_guest_play'] == 1) ? $status_on : $status_off,
		'GUEST_SUBMIT' => ($board_config['ina_disable_submit_scores_g'] == 1) ? $status_on : $status_off,
		'GUEST_FORCE' => ($board_config['ina_force_registration'] == 1) ? $status_on : $status_off,

		'L_POINTS_MOD' => $lang['services_points_mod'],
		'L_CASH_MOD' => $lang['services_cash_mod'],
		'L_ALLOWANCE_MOD' => $lang['services_allowance_mod'],
		'L_AUTO_DELETE' => $lang['services_auto_delete'],
		'L_CHALLENGE_SYSTEM'=> $lang['services_challenge_system'],
		'L_TROPHY_LOSS' => $lang['services_trophy_pm'],
		'L_DAILY_GAME' => $lang['services_daily_game'],
		'L_ONLINE_LIST' => $lang['services_online_list'],
		'L_NEWEST_GAMES' => $lang['services_newest_games'],
		'L_SHOUTBOX' => $lang['services_shoutbox'],
		'L_TROPHY_TOPIC' => $lang['services_trophy_topic'],
		'L_TROPHY_PROFILE' => $lang['services_trophy_profile'],
		'L_TROPHY_KING' => $lang['services_trophy_king'],
		'L_MEMBER_SUBMIT' => $lang['services_member_submit'],
		'L_GUEST_PLAY' => $lang['services_guest_play'],
		'L_GUEST_SUBMIT' => $lang['services_guest_submit'],
		'L_GUEST_FORCE' => $lang['services_guest_force'],

		'RATING' => ($board_config['ina_use_rating_reward'] == 1) ? $status_on : $status_off,
		'L_RATING' => $lang['services_rating'],
		'L_RATING_2' => str_replace('%T%', number_format($board_config['ina_rating_reward']), $lang['services_rating_2']),

		'L_NEW_IMG' => $lang['services_new_game_img'],
		'NEW_IMG' => str_replace('%T%', number_format($board_config['ina_new_game_limit']), $lang['services_new_game_img_2']),

		'L_POP_IMG' => $lang['services_pop_game_img'],
		'POP_IMG' => str_replace('%T%', number_format($board_config['ina_pop_game_limit']), $lang['services_pop_game_img_2']),

		'GAMES_PER_DAY' => ($board_config['ina_use_max_games_per_day'] != 2) ? $status_on : $status_off,
		'L_GAMES_PER_DAY' => $lang['services_games_per_day'],
		'L_GAMES_PER_DAY_2' => str_replace('%T%', number_format($board_config['ina_max_games_per_day']), $lang['services_games_per_day_2']),

		'POST_BLOCK' => ($board_config['ina_post_block'] != 2) ? $status_on : $status_off,
		'L_POST_BLOCK' => $lang['services_post_count'],
		'L_POST_BLOCK_2' => str_replace('%T%', number_format($board_config['ina_post_block_count']), $lang['services_post_count_2']),

		'JOIN_BLOCK' => ($board_config['ina_join_block'] != 2) ? $status_on : $status_off,
		'L_JOIN_BLOCK' => $lang['services_member_length'],
		'L_JOIN_BLOCK_2' => str_replace('%T%', number_format($board_config['ina_join_block_count']), $lang['services_member_length_2']),

		'L_ORDER' => $lang['services_list_order'],
		'ORDER' => $current)
			);

		if ($board_config['ina_use_rating_reward'])
			$template->assign_block_vars('rating_on', array());

		if ($board_config['ina_use_max_games_per_day'] != 2)
			$template->assign_block_vars('games_per_day', array());

		if ($board_config['ina_post_block'] != 2)
			$template->assign_block_vars('post_block', array());

		if ($board_config['ina_join_block'] != 2)
			$template->assign_block_vars('join_block', array());

$template->pparse('body');

/* Give credit where credit is due. */
echo ('
<script language="JavaScript">
function copyright()
{
	var popurl = \'' . ACTIVITY_MOD_PATH . 'includes/functions_amod_plusC.php\'
	var winpops = window.open(popurl, "", "width=400, height=400,")
}
</script>
<table width="100%" cellspacing="0" cellpadding="0" />
<tr>
<td align="left" valign="top">
<a style="text-decoration:none;" href="javascript:copyright();"><span class="gensmall">&copy; Activity Mod Plus</a></span>
</td>
</tr>
</table>
');
?>