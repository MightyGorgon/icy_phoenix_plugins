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

	$mode = ($_POST['mode']) ? $_POST['mode'] : $_POST['mode'];

	if ($mode == 'save')
		{
	$use_online = intval((($_POST['online']) ? $_POST['online'] : $_POST['online']));
	$use_daily = intval((($_POST['daily']) ? $_POST['daily'] : $_POST['daily']));
	$use_new = intval((($_POST['new']) ? $_POST['new'] : $_POST['new']));
	$use_new_count = intval((($_POST['new_count']) ? $_POST['new_count'] : $_POST['new_count']));
	$use_games = intval((($_POST['games']) ? $_POST['games'] : $_POST['games']));
	$use_games_count = intval((($_POST['game_count']) ? $_POST['game_count'] : $_POST['game_count']));
	$use_info = intval((($_POST['info']) ? $_POST['info'] : $_POST['info']));
	# info-1;;daily-1;;newest-1;;newest_count-5;;games-1;;games_count-10;;online-1

	$compiled = '';
	$compiled			.= 'info-'. $use_info .';;';
	$compiled			.= 'daily-'. $use_daily .';;';
	$compiled			.= 'newest-'. $use_new .';;';
	$compiled			.= 'newest_count-'. (($use_new_count > 0) ? $use_new_count : 1) .';;';
	$compiled			.= 'games-'. $use_games .';;';
	$compiled			.= 'games_count-'. (($use_games_count > 0) ? $use_games_count : 1) .';;';
	$compiled			.= 'online-'. $use_online;

	$q = "UPDATE ". USERS_TABLE ."
			SET ina_settings = '". $compiled ."'
			WHERE user_id = ". $userdata['user_id'] ."";
	$db->sql_query($q);

	message_die(GENERAL_MESSAGE, $lang['games_settings_finished']);
		}

	$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_settings_body.tpl');
	$template->set_filenames(array('body' => $template_to_parse));

	$user_amod_settings = $userdata['ina_settings'];
	$decifer_settings = explode(';;', $user_amod_settings);
	$decifer_info = explode('-', $decifer_settings[0]);
	$user_use_info = $decifer_info[1];
	$decifer_daily = explode('-', $decifer_settings[1]);
	$user_use_daily = $decifer_daily[1];
	$decifer_newest = explode('-', $decifer_settings[2]);
	$user_use_newest = $decifer_newest[1];
	$decifer_newest_count = explode('-', $decifer_settings[3]);
	$user_use_newest_count = $decifer_newest_count[1];
	$decifer_games = explode('-', $decifer_settings[4]);
	$user_use_games = $decifer_games[1];
	$decifer_games_count = explode('-', $decifer_settings[5]);
	$user_use_games_count = $decifer_games_count[1];
	$decifer_online = explode('-', $decifer_settings[6]);
	$user_use_online = $decifer_online[1];

	$template->assign_vars(array(
		'L_GAMES' => $lang['games_settings_games'],
		'V_GAMES' => (($user_use_games) ? '<input type="radio" name="games" value="1" checked="checked">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="games" value="0">&nbsp;'. $lang['radio_no'] : '<input type="radio" name="games" value="1">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="games" value="0" checked="checked">&nbsp;'. $lang['radio_no']),
		'L_GAMES_COUNT' => $lang['games_settings_games_count'],
		'V_GAMES_COUNT' => $user_use_games_count,
		'L_INFO' => $lang['games_settings_info'],
		'V_INFO' => (($user_use_info) ? '<input type="radio" name="info" value="1" checked="checked">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="info" value="0">&nbsp;'. $lang['radio_no'] : '<input type="radio" name="info" value="1">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="info" value="0" checked="checked">&nbsp;'. $lang['radio_no']),
		'L_DAILY' => $lang['games_settings_daily'],
		'V_DAILY' => (($user_use_daily) ? '<input type="radio" name="daily" value="1" checked="checked">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="daily" value="0">&nbsp;'. $lang['radio_no'] : '<input type="radio" name="daily" value="1">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="daily" value="0" checked="checked">&nbsp;'. $lang['radio_no']),
		'L_ONLINE' => $lang['games_settings_online'],
		'V_ONLINE' => (($user_use_online) ? '<input type="radio" name="online" value="1" checked="checked">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="online" value="0">&nbsp;'. $lang['radio_no'] : '<input type="radio" name="online" value="1">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="online" value="0" checked="checked">&nbsp;'. $lang['radio_no']),
		'L_NEW' => $lang['games_settings_new'],
		'V_NEW' => (($user_use_newest) ? '<input type="radio" name="new" value="1" checked="checked">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="new" value="0">&nbsp;'. $lang['radio_no'] : '<input type="radio" name="new" value="1">&nbsp;'. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="new" value="0" checked="checked">&nbsp;'. $lang['radio_no']),
		'L_NEW_COUNT' => $lang['games_settings_new_count'],
		'V_NEW_COUNT' => $user_use_newest_count,
		'SUBMIT' => $lang['games_settings_submit'])
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