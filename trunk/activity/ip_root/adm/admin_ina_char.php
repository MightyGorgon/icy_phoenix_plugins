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

define('IN_ICYPHOENIX', true);
if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Amod+ Admin']['Char_Settings'] = "$file";
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));

require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_activity_char.' . PHP_EXT);

	if( isset( $_POST['mode'] ) || isset( $_GET['mode'] ) )
		$mode = ( isset( $_POST['mode']) ) ? $_POST['mode'] : $_GET['mode'];
	else
		$mode = '';

	if (!$mode)
		{
	echo '<form name="save_char" method="post" action="admin_ina_char.' . PHP_EXT . '?mode=save&sid='. $userdata['session_id'] .'">';
	echo '<table align="center" width="100%" class="forumline">';
	echo '	<tr>';
	echo '		<th width="80%">';
	echo '			'. $lang['amp_char_change_title_1'];
	echo '		</th>';
	echo '		<th width="20%">&nbsp;</th>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_char'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_change_char_cost'] .'" name="char_img" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_name'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_change_name_cost'] .'" name="char_name" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_title'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_change_title_cost'] .'" name="char_title" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_saying'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_change_saying_cost'] .'" name="char_saying" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_gender'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_change_gender_cost'] .'" name="char_gender" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_from'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_change_from_cost'] .'" name="char_from" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_intrests'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_change_intrests_cost'] .'" name="char_intrests" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_age'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_change_age_cost'] .'" name="char_age" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<th width="80%">';
	echo '			'. $lang['amp_char_change_title_2'];
	echo '		</th>';
	echo '		<th width="20%">&nbsp;</th>';
	echo '	</tr>';
	#color, shadow, glow, bold, italic, underline
	$char_name_costs = explode(',', $board_config['ina_char_name_effects_costs']);
	$char_title_costs = explode(',', $board_config['ina_char_title_effects_costs']);
	$char_saying_costs = explode(',', $board_config['ina_char_saying_effects_costs']);
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_name_c'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_name_costs[0] .'" name="name_cost_one" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_name_s'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_name_costs[1] .'" name="name_cost_two" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_name_g'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_name_costs[2] .'" name="name_cost_three" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_name_b'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_name_costs[3] .'" name="name_cost_four" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_name_i'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_name_costs[4] .'" name="name_cost_five" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_name_u'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_name_costs[5] .'" name="name_cost_six" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_title_c'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_title_costs[0] .'" name="title_cost_one" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_title_s'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_title_costs[1] .'" name="title_cost_two" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_title_g'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_title_costs[2] .'" name="title_cost_three" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_title_b'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_title_costs[3] .'" name="title_cost_four" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_title_i'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_title_costs[4] .'" name="title_cost_five" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_title_u'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_title_costs[5] .'" name="title_cost_six" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_saying_c'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_saying_costs[0] .'" name="saying_cost_one" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_saying_s'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_saying_costs[1] .'" name="saying_cost_two" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_saying_g'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_saying_costs[2] .'" name="saying_cost_three" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_saying_b'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_saying_costs[3] .'" name="saying_cost_four" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_saying_i'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_saying_costs[4] .'" name="saying_cost_five" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_saying_u'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $char_saying_costs[5] .'" name="saying_cost_six" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<th width="80%">';
	echo '			'. $lang['amp_char_change_title_3'];
	echo '		</th>';
	echo '		<th width="20%">&nbsp;</th>';
	echo '	</tr>';
	$viewtopic = (($board_config['ina_char_show_viewtopic'] == 1) ? 'checked="on"' : '');
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_viewtopic'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="viewtopic" '. $viewtopic .'>';
	echo '		</td>';
	echo '	</tr>';
	$viewprofile = (($board_config['ina_char_show_viewprofile'] == 1) ? 'checked="on"' : '');
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_viewprofile'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="checkbox" name="viewprofile" '. $viewprofile .'>';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<th width="80%">';
	echo '			'. $lang['amp_char_change_title_4'];
	echo '		</th>';
	echo '		<th width="20%">&nbsp;</th>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="center" width="100%" colspan="2" class="row2">';
	echo '			<b>'. $lang['amp_char_change_warning'] .'</b>';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_per_game'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_ge_per_game'] .'" name="per_game" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_per_score'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_ge_per_beat_score'] .'" name="per_score" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<td align="left" class="row1">';
	echo 			$lang['amp_char_change_per_trophy'];
	echo '		</td>';
	echo '		<td align="left" class="row2">';
	echo '			<input type="text" class="post" value="'. $board_config['ina_char_ge_per_trophy'] .'" name="per_trophy" size="10">';
	echo '		</td>';
	echo '	</tr>';
	echo '	<tr>';
	echo '		<th width="100%" colspan="2">';
	echo '			<input type="submit" value="'. $lang['amp_char_save_shop'] .'" class="mainoption" onclick="document.save_char.submit()">';
	echo '  </th>';
	echo '	</tr>';
	echo '</table>';
	echo '</form>';
		}

	if ($mode == 'save')
		{
	$change_char_cost = intval($_POST['char_img']);
	$change_char_name = intval($_POST['char_name']);
	$change_char_title = intval($_POST['char_title']);
	$change_char_saying = intval($_POST['char_saying']);
	$change_char_gender = intval($_POST['char_gender']);
	$change_char_location = intval($_POST['char_from']);
	$change_char_intrests = intval($_POST['char_intrests']);
	$change_char_age = intval($_POST['char_age']);

	$change_name_color_cost = intval($_POST['name_cost_one']);
	$change_name_shadow_cost = intval($_POST['name_cost_two']);
	$change_name_glow_cost = intval($_POST['name_cost_three']);
	$change_name_bold_cost = intval($_POST['name_cost_four']);
	$change_name_italic_cost = intval($_POST['name_cost_five']);
	$change_name_underline_cost = intval($_POST['name_cost_six']);

	$change_title_color_cost = intval($_POST['title_cost_one']);
	$change_title_shadow_cost = intval($_POST['title_cost_two']);
	$change_title_glow_cost = intval($_POST['title_cost_three']);
	$change_title_bold_cost = intval($_POST['title_cost_four']);
	$change_title_italic_cost = intval($_POST['title_cost_five']);
	$change_title_underline_cost = intval($_POST['title_cost_six']);

	$change_saying_color_cost = intval($_POST['saying_cost_one']);
	$change_saying_shadow_cost = intval($_POST['saying_cost_two']);
	$change_saying_glow_cost = intval($_POST['saying_cost_three']);
	$change_saying_bold_cost = intval($_POST['saying_cost_four']);
	$change_saying_italic_cost = intval($_POST['saying_cost_five']);
	$change_saying_underline_cost = intval($_POST['saying_cost_six']);

	$show_char_in_posts = ($_POST['viewtopic'] == 'on') ? 1 : '';
	$show_char_in_profiles = ($_POST['viewprofile'] == 'on') ? 1 : '';

	$reward_per_game = intval($_POST['per_game']);
	$reward_per_score = intval($_POST['per_score']);
	$reward_per_trophy = intval($_POST['per_trophy']);

	$compiled_name_effects = $change_name_color_cost .','. $change_name_shadow_cost .','. $change_name_glow_cost .','. $change_name_bold_cost .','. $change_name_italic_cost .','. $change_name_underline_cost;
	$compiled_saying_effects = $change_saying_color_cost .','. $change_saying_shadow_cost .','. $change_saying_glow_cost .','. $change_saying_bold_cost .','. $change_saying_italic_cost .','. $change_saying_underline_cost;
	$compiled_title_effects = $change_title_color_cost .','. $change_title_shadow_cost .','. $change_title_glow_cost .','. $change_title_bold_cost .','. $change_title_italic_cost .','. $change_title_underline_cost;

	$q = array();
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_cost' WHERE config_name = 'ina_char_change_char_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_gender' WHERE config_name = 'ina_char_change_gender_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_age' WHERE config_name = 'ina_char_change_age_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_name' WHERE config_name = 'ina_char_change_name_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_location' WHERE config_name = 'ina_char_change_from_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_intrests' WHERE config_name = 'ina_char_change_intrests_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$reward_per_game' WHERE config_name = 'ina_char_ge_per_game';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$reward_per_score' WHERE config_name = 'ina_char_ge_per_beat_score';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$reward_per_trophy' WHERE config_name = 'ina_char_ge_per_trophy';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$show_char_in_posts' WHERE config_name = 'ina_char_show_viewtopic';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$show_char_in_profiles' WHERE config_name = 'ina_char_show_viewprofile';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_title' WHERE config_name = 'ina_char_change_title_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_saying' WHERE config_name = 'ina_char_change_saying_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$compiled_name_effects' WHERE config_name = 'ina_char_name_effects_costs';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$compiled_title_effects' WHERE config_name = 'ina_char_title_effects_costs';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$compiled_saying_effects' WHERE config_name = 'ina_char_saying_effects_costs';";

		for ($x = 0; $x < count($q); $x++)
			$db->sql_query($q[$x]);

	message_die(GENERAL_MESSAGE, $lang['amp_char_settings_saved'] .'<br /><br />'. sprintf($lang['amp_char_settings_back'], '<a href="'. append_sid($_SERVER['PHP_SELF']) .'">', '</a>'));
		}

include('page_footer_admin.' . PHP_EXT);
?>