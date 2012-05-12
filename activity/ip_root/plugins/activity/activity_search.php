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

$user_id = $user->data['user_id'];
if($config['ina_guest_play'] == "2")
{
if(!$user->data['session_logged_in'] && $user_id == ANONYMOUS)
	{
$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";
header($header_location . append_sid(CMS_PAGE_LOGIN . '?redirect=activity.' . PHP_EXT, true));
exit;
	}
}

UpdateSessions();
BanCheck();
UpdateActivitySession();

if($config['use_rewards_mod'])
{
	if ($config['use_point_system'])
	{
		include(IP_ROOT_PATH . 'includes/functions_points.' . PHP_EXT);
	}

	if ($config['use_cash_system'] || $config['use_allowance_system'])
	{
		include(IP_ROOT_PATH . 'includes/rewards_api.' . PHP_EXT);
	}
}

if($_GET['mode'] != 'game')
{
	UpdateUsersPage($user->data['user_id'], $_SERVER['REQUEST_URI']);
}

if ($config['use_point_system'])
{
	$game_cost = $config['points_name'];
}
else
{
	$game_cost = $lang['game_cost'];
}

$order_by = AdminDefaultOrder();

if($config['use_gamelib'] == 1)
{
	$gamelib_link = '<div align="center"><span class="copyright">' . $lang['game_lib_link'] . '</span></div>';
}

if($config['use_gamelib'] == 0)
{
	$gamelib_link = '';
}

if($_POST['mode'] == 'search')
{
	$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_search_body.tpl');
	$template->set_filenames(array('body' => $template_to_parse));

	$query = $_POST['query'];
	$type = $_POST['top_left'];
	$extra = $_POST['top_right'];
	$cat = $_POST['category'];
	$wc = $_POST['wildcard'];

	if(!($query))
	{
		message_die(GENERAL_ERROR, "Specify A Query.", "Error");
	}

	/* Gonna be a mess! Building the where clause */
	if($wc)
	{
		$wildcard = "LIKE";
		$p = "%";
	}
	elseif(!$wc)
	{
		$wildcard = " = ";
		$p = "";
	}
	else
	{
		$wildcard = "LIKE";
		$p = "%";
	}

	$where_clause = "WHERE game_id > '0'";
	if($type == "flash")
	{
		$where_clause .= " AND game_flash = '1'";
	}
	elseif($type == "glib")
	{
		$where_clause .= " AND game_use_gl = '1'";
	}
	else
	{
		$where_clause .= " AND game_flash = '1'";
	}

	if($extra == "desc")
	{
		$where_clause .= " AND game_desc ". $wildcard ." '". $p . $query . $p ."'";
	}
	elseif($extra == "name")
	{
		$where_clause .= " AND game_name ". $wildcard ." '". $p . $query . $p ."'";
	}
	elseif($extra == "reverse")
	{
		$where_clause .= " AND reverse_list = '1'";
	}
	else
	{
		$where_clause .= " AND game_desc ". $wildcard ." '". $p . $query . $p ."'";
	}

	$i = 1;
	$q2 = "SELECT *
			FROM ". iNA_GAMES ."
			 $where_clause";
	$r2 = $db -> sql_query($q2);
	while($row = $db -> sql_fetchrow($r2))
	{
		$game_name = eregi_replace($query, "<b>". $query . "</b>", $row['proper_name']);
		$game_desc = eregi_replace($query, "<b>". $query . "</b>", $row['game_desc']);
		$game_img = GameArrayLink($row['game_id'], $row['game_parent'], $row['game_popup'], $row['win_width'], $row['win_height'], '3%SEP%'. CheckGameImages($row['game_name'], $row['proper_name']), '');
		$row_class = (!($i % 2)) ? 'row1' : 'row2';

		$template->assign_block_vars('search_results', array(
			'ROW_CLASS' => $row_class,
			'IMAGE' => $game_img,
			'DESC' => $game_desc,
			'NAME' => $game_name
			)
		);
		$i++;
	}
}
else
{
	$template_to_parse = $class_plugins->get_tpl_file(ACTIVITY_TPL_PATH, 'activity_search_body.tpl');
	$template->set_filenames(array('body' => $template_to_parse));
	$template->assign_block_vars('search_switch', array());

	$template->assign_vars(array(
		'SEARCH_TITLE' => $lang['search_title'],
		'L_TOP_LEFT' => $lang['search_option_title_1'],
		'L_TOP_RIGHT' => $lang['search_option_title_2'],
		'L_TL_OPTION_1' => $lang['search_option_flash'],
		'L_TL_OPTION_2' => $lang['search_option_glib'],
		'L_TR_OPTION_1' => $lang['search_option_desc'],
		'L_TR_OPTION_2' => $lang['search_option_name'],
		'L_TR_OPTION_3' => $lang['search_option_reverse'],
		'L_QUERY' => $lang['search_query'],
		'L_WILDCARD' => $lang['search_use_wildcard'],
		'L_CATEGORY_TITLE' => $lang['search_category'],
		'L_CATEGORY_NONE' => $lang['search_category_none'],
		'L_SUBMIT' => $lang['search_submit']
		)
	);
}
$template->pparse('body');

/* Give credit where credit is due. */
echo ('
<script type="text/javascript">
function copyright()
{
	var popurl = \'' . ACTIVITY_PLUGIN_PATH . 'includes/functions_amod_plusC.php\'
	var winpops = window.open(popurl, "", "width=400, height=400,")
}
</script>
<table width="100%" cellspacing="0" cellpadding="0" />
<tr>
<td align="left" valign="top">
<a style="text-decoration:none;" href="javascript:copyright();" class="gensmall">&copy; Activity Mod Plus</a>
</td>
</tr>
</table>
');
?>