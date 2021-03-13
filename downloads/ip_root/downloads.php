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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

define('CT_SECLEVEL', 'MEDIUM');
$ct_ignorepvar = array('mod_desc', 'long_desc', 'description', 'warning', 'todo', 'require', 'hack_author', 'hack_author_email', 'hack_author_website', 'hack_dl_url', 'dl_name', 'file_name');
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$cms_page['page_id'] = 'download';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['downloads']['dir'] . 'common.' . PHP_EXT);

/*
* init and get various values
*/
$params = array(
	'submit' => 'submit',
	'cancel' => 'cancel',
	'confirm' => 'confirm',
	'delete' => 'delete',
	'cdelete' => 'cdelete',
	'save' => 'save',
	'post' => 'post',
	'view' => 'view',
	'show' => 'show',
	'order' => 'order',
	'action' => 'action',
	'save' => 'save',
	'goback' => 'goback',
	'edit' => 'edit',
	'bt_show' => 'bt_show',
	'move' => 'move',
	'fmove' => 'fmove',
	'lock' => 'lock',
	'sort' => 'sort',
	'code' => 'code'
);
while(list($var, $param) = @each($params))
{
	${$var} = request_var($param, '');
}

$params = array(
	'df_id' => 'df_id',
	'cat' => 'cat',
	'new_cat' => 'new_cat',
	'cat_id' => 'cat_id',
	'fav_id' => 'fav_id',
	'dl_id' => 'dl_id',
	'dlo' => 'dlo',
	'start' => 'start',
	'sort_by' => 'sort_by',
	'rate_point' => 'rate_point',
	'del_file' => 'del_file',
	'bt_filter' => 'bt_filter',
	'modcp' => 'modcp'
);
while(list($var, $param) = @each($params))
{
	${$var} = request_var($param, 0);
}

$df_id = ($df_id < 0) ? 0 : $df_id;
$cat = ($cat < 0) ? 0 : $cat;
$new_cat = ($new_cat < 0) ? 0 : $new_cat;
$cat_id = ($cat_id < 0) ? 0 : $cat_id;
$fav_id = ($fav_id < 0) ? 0 : $fav_id;
$dl_id = ($dl_id < 0) ? 0 : $dl_id;
$dlo = ($dlo < 0) ? 0 : $dlo;
$start = ($start < 0) ? 0 : $start;
$sort_by = ($sort_by < 0) ? 0 : $sort_by;
$rate_point = ($rate_point < 0) ? 0 : $rate_point;
$del_file = ($del_file < 0) ? 0 : $del_file;
$modcp = ($modcp < 0) ? 0 : $modcp;

/*
* display the confirmation code if needed
*/
if (($view == 'code') && $code)
{
	$hotlink_id = ($code == 'd') ? 'dlvc' : 'repvc';

	$sql = "SELECT code FROM " . DL_HOTLINK_TABLE . "
		WHERE user_id = " . $user->data['user_id'] . "
			AND hotlink_id = '" . $hotlink_id . "'";
	$sql .= (!$user->data['session_logged_in']) ? " AND session_id = '" . $user->data['session_id'] . "' " : '';
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$code = $row['code'];
	$db->sql_freeresult($result);

	if (!$code)
	{
		$code = 'ERROR';
	}

	$im = imagecreate(50, 20);
	$background_color = imagecolorallocate ($im, 150, 150, 150);
	imagefill($im, 0, 0, $background_color);
	$text_color = imagecolorallocate ($im, 0, 0, 255);
	imagestring ($im, 5, 2, 2, $code, $text_color);
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
	header("Content-Type: image/jpeg");
	imagejpeg($im, null, 80);
	imagedestroy($im);
	exit;
}
elseif(($view == 'code') && !$code)
{
	exit;
}

/*
* redirect to details or rating íf needed
*/
if ($cat && $df_id && (($view == 'detail') && ($action != 'rate')))
{
	redirect(append_sid('downloads.' . PHP_EXT . '?view=' . $view . '&cat=' . $cat . '&df_id=' . $df_id . '&dlo=' . $dlo, true));
}

/*
* include and create the main class
*/
if ($cat || !sizeof($_GET) || ((sizeof($_GET) == 1) && isset($_GET['sid'])))
{
	$enable_desc = true;
}
if ($cat)
{
	$enable_rule = true;
}
include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'classes/class_dlmod.' . PHP_EXT);
$dl_mod = new dlmod();
$dl_config = array();
$dl_config = $dl_mod->get_config();

/*
* set the right values for comments
*/
if (!$action)
{
	if ($post)
	{
		$view = 'comment';
		$action = 'post';
	}

	if ($show)
	{
		$view = 'comment';
		$action = 'view';
	}

	if ($save)
	{
		$view = 'comment';
		$action = 'save';
	}

	if ($delete)
	{
		$view = 'comment';
		$action = 'delete';
	}

	if ($edit)
	{
		$view = 'comment';
		$action = 'edit';
	}
}

/*
* wanna have smilies ;-)
*/
if ($action == 'smilies')
{
	generate_smilies('window');
	exit;
}

if ($goback)
{
	$view = 'detail';
	$action = '';
}

/*
* get the needed index
*/
$index = array();

switch ($view)
{
	case 'overall':
	case 'load':
	case 'detail':
	case 'comment':
	case 'upload':
	case 'modcp':
	case 'bug_tracker':

		$index = $dl_mod->full_index();
		break;

	default:

		$index = ($cat) ? $dl_mod->index($cat) : $dl_mod->index();
}

if (($view != 'load') && ($view != 'broken'))
{
	$sql_where = '';

	if (!$user->data['session_logged_in'])
	{
		$sql = "SELECT session_id FROM " . SESSIONS_TABLE . "
			WHERE session_user_id = " . ANONYMOUS;
		$result = $db->sql_query($sql);

		$guest_sids = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$guest_sids .= ($guest_sids != '') ? ", '".$row['session_id'] . "'" : "'" . $row['session_id'] . "'";
		}
		$db->sql_freeresult($result);

		$sql_where = " AND session_id NOT IN (" . $guest_sids . ") ";
	}

	$sql = "DELETE FROM " . DL_HOTLINK_TABLE . "
		WHERE user_id = " . $user->data['user_id'] . "
			$sql_where";
	$result = $db->sql_query($sql);
}

//create todo list
if ($view == 'todo')
{
	$dl_todo = array();
	$dl_todo = $dl_mod->get_todo();

	if (sizeof($dl_todo['file_name']))
	{
		$meta_content['page_title'] = $lang['Dl_mod_todo'];
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		$nav_server_url = create_server_url();
		$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?view=todo') . '">' . $lang['Dl_mod_todo'] . '</a>';

		$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_todo_body.tpl');

		$template->assign_vars(array(
			'L_DESCRIPTION' => $lang['Dl_file_description'],
			'L_DL_TOP' => $lang['Dl_cat_title'],
			'L_DL_TODO' => $lang['Dl_mod_todo'],
			'U_DL_TOP' => append_sid('downloads.' . PHP_EXT)
			)
		);

		for ($i = 0; $i < sizeof($dl_todo['file_name']); $i++)
		{
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('todolist_row', array(
				'FILENAME' => $dl_todo['file_name'][$i],
				'FILE_LINK' => $dl_todo['file_link'][$i],
				'HACK_VERSION' => $dl_todo['hack_version'][$i],
				'TODO' => $dl_todo['todo'][$i],
				'ROW_CLASS' => $row_class
				)
			);
		}
	}
	else
	{
		$view = '';
	}
}

//handle reported broken download
if (($view == 'broken') && $df_id && $cat_id && ($user->data['session_logged_in'] || (!$user->data['session_logged_id'] && $dl_config['report_broken'])))
{
	if ($dl_config['report_broken_vc'])
	{
		if ($confirm != 'code')
		{
			$code = '';
			$code_string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789';
			srand((double)microtime() * 1000000);
			mt_srand((double)microtime() * 1000000);

			for ($i = 0; $i < 5; $i++)
			{
				$code_pos = mt_rand(1, strlen($code_string)) - 1;
				$code .= $code_string{$code_pos};
			}

			$sql = "DELETE FROM " . DL_HOTLINK_TABLE . "
				WHERE user_id = " . $user->data['user_id'] . "
					AND hotlink_id = 'repvc'";
			if (!$user->data['session_logged_id'])
			{
				$sql .= " AND session_id = '" . $user->data['session_id'] . "'";
			}
			$result = $db->sql_query($sql);

			$sql = "INSERT INTO " . DL_HOTLINK_TABLE . "
				(user_id, session_id, hotlink_id, code)
				VALUES
				(" . $user->data['user_id'] . ", '" . $user->data['session_id'] . "', 'repvc', '$code')";
			$result = $db->sql_query($sql);

			$nav_server_url = create_server_url();
			$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '" class="nav-current">' . $lang['Downloads'] . '</a>';

			$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="view" value="broken" />';
			$s_hidden_fields .= '<input type="hidden" name="confirm" value="code" />';

			$template->assign_vars(array(
				'L_SUBMIT' => $lang['Submit'],

				'MESSAGE_TITLE' => $lang['Information'],
				'MESSAGE_TEXT' => $lang['Dl_report_confirm_code'],
				'CONFIRM_CODE' => append_sid('downloads.' . PHP_EXT . '?view=code&amp;code=r'),

				'S_CONFIRM_ACTION' => append_sid('downloads.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_report_code_body.tpl');
			full_page_generation($template_to_parse, $lang['Downloads'], '', '');
		}
		else
		{
			$sql = "SELECT code FROM " . DL_HOTLINK_TABLE . "
				WHERE user_id = " . $user->data['user_id'] . "
					AND session_id = '" . $user->data['session_id'] . "'
					AND hotlink_id = 'repvc'";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$saved_code = $row['code'];
			$db->sql_freeresult($result);

			if ($saved_code == $code)
			{
				$code_match = true;
			}
			else
			{
				$code_match = 0;
			}
		}
	}

	if ($dl_config['report_broken_vc'] && !$code_match)
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_report_broken_vc_mismatch']);
	}
	else
	{
		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET broken = " . true . "
			WHERE id = " . (int) $df_id;
		$result = $db->sql_query($sql);

		$processing_user = $dl_mod->dl_auth_users($cat_id, 'auth_mod');
		$processing_user .= ($processing_user) ? '' : 0;

		$email_template = 'downloads_report_broken';

		$sql = "SELECT user_email, username, user_lang FROM " . USERS_TABLE . "
			WHERE user_id IN ($processing_user)
				OR user_level = " . ADMIN;
		$result = $db->sql_query($sql);

		$script_path = $config['script_path'];
		$server_name = trim($config['server_name']);
		$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
		$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) . '/' : '/';

		$server_url = $server_name . $server_port . $script_path;
		$server_url = $server_protocol . str_replace('//', '/', $server_url);

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

		while ($row = $db->sql_fetchrow($result))
		{
			// Let's do some checking to make sure that mass mail functions are working in win32 versions of php.

			if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$config['smtp_delivery'])
			{
				$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

				// We are running on windows, force delivery to use our smtp functions since php's are broken by default
				$config['smtp_delivery'] = 1;
				$config['smtp_host'] = @$ini_val('SMTP');
			}

			$username = (!$user->data['session_logged_in']) ? $lang['Dl_a_guest'] : $user->data['username'];

			$emailer = new emailer();

			$emailer->headers('X-AntiAbuse: Board servername - ' . trim($config['server_name']));
			$emailer->headers('X-AntiAbuse: User_id - ' . $user->data['user_id']);
			$emailer->headers('X-AntiAbuse: Username - ' . $user->data['username']);
			$emailer->headers('X-AntiAbuse: User IP - ' . $user_ip);

			$emailer->use_template($email_template, $row['user_lang']);
			$emailer->to($row['user_email']);
			$emailer->set_subject();

			$emailer->assign_vars(array(
				'BOARD_EMAIL' => $config['board_email_sig'],
				'SITENAME' => $config['sitename'],
				'REPORTER' => $username,
				'USERNAME' => $row['username'],
				'U_DOWNLOAD' => $server_url . 'downloads.' . PHP_EXT . '?view=detail&cat_id=' . $cat_id . '&df_id=' . $df_id
				)
			);

			$emailer->send();
			$emailer->reset();
		}
	}

	redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id . '&cat_id=' . $cat_id));
}

//reset reported broken download if allowed
if ($view == 'unbroken' && $df_id && $cat_id)
{
	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if ($index[$cat_id]['auth_mod'] || $cat_auth['auth_mod'] || $user->data['user_level'] == ADMIN)
	{
		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET broken = 0
			WHERE id = " . (int) $df_id;
		$result = $db->sql_query($sql);
	}

	redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id . '&cat_id=' . $cat_id));
}

//set favorite for the choosen download
if (($view == 'fav') && $df_id && $cat_id && $user->data['session_logged_in'])
{
	$sql = "SELECT * FROM " . DL_FAVORITES_TABLE . "
		WHERE fav_dl_id = " . (int) $df_id . "
			AND fav_user_id = " . $user->data['user_id'];
	$result = $db->sql_query($sql);

	$fav_check = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if (!$fav_check)
	{
		$sql = "INSERT INTO " . DL_FAVORITES_TABLE . "
			(fav_dl_id, fav_dl_cat, fav_user_id)
			VALUES
			(" . (int) $df_id . ", " . (int) $cat_id . ", " . $user->data['user_id'] . ")";
		$result = $db->sql_query($sql);
	}

	redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id . '&cat_id=' . $cat_id));
}

//drop favorite for the choosen download
if (($view == 'unfav') && $fav_id && $df_id && $cat_id && $user->data['session_logged_in'])
{
	$sql = "DELETE FROM " . DL_FAVORITES_TABLE . "
		WHERE fav_id = " . (int) $fav_id . "
			AND fav_dl_id = " . (int) $df_id . "
			AND fav_user_id = " . $user->data['user_id'];
	$result = $db->sql_query($sql);
	redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id . '&cat_id=' . $cat_id));
}

/*
* open the bug tracker, if choosen and possible
*/
if ($view == 'bug_tracker')
{
	if ($user->data['session_logged_in'])
	{
		$bug_tracker = $dl_mod->bug_tracker();
		if ($bug_tracker)
		{
			$inc_module = true;
			$meta_content['page_title'] = $lang['Dl_bug_tracker'];
			$meta_content['description'] = '';
			$meta_content['keywords'] = '';
			$nav_server_url = create_server_url();
			$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?view=bug_tracker') . '">' . $lang['Dl_bug_tracker'] . '</a>';
			include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_bug_tracker.' . PHP_EXT);
		}
		else
		{
			$view = '';
		}
	}
	else
	{
		$view = '';
	}
}

/*
* No real hard work until here? Must at least run one of the default modules?
*/
$inc_module = false;
if ($view == 'stat')
{
	//getting some stats
	$inc_module = true;
	$meta_content['page_title'] = $lang['Dl_stats'];
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?view=stat') . '">' . $lang['Dl_stats'] . '</a>';
	include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_stats.' . PHP_EXT);
}
elseif ($view == 'user_config')
{
	//drop choosen favorites
	$fav_ids = (isset($_POST['fav_id'])) ? $_POST['fav_id'] : array();
	if ($action == 'drop' && sizeof($fav_ids))
	{
		$sql_fav_ids = '';
		for ($i = 0; $i < sizeof($fav_ids); $i++)
		{
			$sql_fav_ids .= ($sql_fav_ids == '') ? intval($fav_ids[$i]) : ', ' . intval($fav_ids[$i]);
		}

		$sql = "DELETE FROM " . DL_FAVORITES_TABLE . "
			WHERE fav_id IN ($sql_fav_ids)
				AND fav_user_id = " . $user->data['user_id'];
		$result = $db->sql_query($sql);
		$action = '';
		$submit = '';
		$fav_ids = array();
	}

	//display the user config for the downloads
	$inc_module = true;
	$meta_content['page_title'] = $lang['Dl_config'];
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?view=user_config') . '">' . $lang['Dl_config'] . '</a>';
	include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_user_config.' . PHP_EXT);
}
elseif ($view == 'detail')
{
	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if (!$user->data['user_level'] == ADMIN && !$cat_auth['auth_mod'])
	{
		$modcp = 0;
	}

	//default entry point for download details
	$dl_files = array();
	$dl_files = $dl_mod->all_files(0, '', 'ASC', '', $df_id, $modcp);

	//check the permissions
	$check_status = array();
	$check_status = $dl_mod->dl_status($df_id);
	if (!$dl_files['id'])
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
	}

	//save rating into database after submitting
	if ($submit && ($action == 'rate'))
	{
		$rate_user_id = $user->data['user_id'];
		$rate_point = round($rate_point, 2);

		$sql = "INSERT INTO " . DL_RATING_TABLE . " (dl_id, user_id, rate_point)
				VALUES ($df_id, $rate_user_id, '$rate_point')";
		$result = $db->sql_query($sql);

		$sql = "SELECT AVG(rate_point) AS rating FROM " . DL_RATING_TABLE . "
			WHERE dl_id = $df_id
			GROUP BY dl_id";
		$result = $db->sql_query($sql);
		$this_id = $db->sql_fetchrow($result);
		$new_rating = ceil($this_id['rating']);
		$db->sql_freeresult($result);

		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET rating = $new_rating
			WHERE id = $df_id";
		$result = $db->sql_query($sql);

		$view = 'detail';
		$action = '';
		$submit = '';
		$cancel = '';

		if ($dlo == 1)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?view=overall'));
		}
		elseif ($dlo == 2 && $df_id)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id, true));
		}
		elseif (!$dlo)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?cat=' . $cat . '&start=' . $start, true));
		}
	}

	$inc_module = true;
	$meta_content['page_title'] = $lang['Download'] . ' - ' . $dl_files['description'];
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $dl_files['description'] . '</a>';
	include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_details.' . PHP_EXT);
}
elseif ($view == 'search')
{
	//open the search for downloads
	$inc_module = true;
	$meta_content['page_title'] = $lang['Search'] . ' ' . $lang['Downloads'];
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?view=search') . '">' . $lang['Search'] . '</a>';
	include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_search.' . PHP_EXT);
}
elseif ($view == 'popup')
{
	//display the popup for a new or changed download
	$template->assign_vars(array(
		'L_CLOSE_WINDOW' => $lang['Close_window'],
		'L_MESSAGE' => sprintf($lang['New_download'], '<a href="javascript:jump_to_inbox();">', '</a>'),
		'U_PRIVATEMSGS' => append_sid('downloads.' . PHP_EXT)
		)
	);
	$gen_simple_header = true;
	full_page_generation('privmsgs_popup.tpl', $lang['Downloads'], '', '');
}
elseif ($view == 'load')
{
	//check for hotlinking
	$hotlink_disabled = false;
	$sql_where = '';

	if ($dl_config['prevent_hotlink'])
	{
		$hotlink_id = $_POST['hotlink_id'];
		if (!$hotlink_id)
		{
			$hotlink_disabled = true;
		}
		else
		{
			if (!$user->data['session_logged_in'])
			{
				$sql_where = " AND session_id = '" . $user->data['session_id'] . "' ";
			}

			$sql = "SELECT hotlink_id FROM " . DL_HOTLINK_TABLE . "
				WHERE user_id = " . $user->data['user_id'] . "
					AND hotlink_id = '" . $hotlink_id . "'
					$sql_where";
			$result = $db->sql_query($sql);
			$total_hotlinks = $db->sql_numrows($result);
			if ($total_hotlinks <> 1)
			{
				$hotlink_disabled = true;
			}

			$db->sql_freeresult($result);
		}
		if ($hotlink_disabled)
		{
			if ($dl_config['hotlink_action'])
			{
				redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id, true));
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Dl_hotlink_permission']);
			}
		}
	}

	if ($dl_config['download_vc'])
	{
			if (!$user->data['session_logged_in'])
			{
				$sql_where = " AND session_id = '" . $user->data['session_id'] . "' ";
			}

			$sql = "SELECT code FROM " . DL_HOTLINK_TABLE . "
				WHERE user_id = " . $user->data['user_id'] . "
					AND hotlink_id = 'dlvc'
					$sql_where";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if ($row['code'] != $code)
			{
				message_die(GENERAL_MESSAGE, $lang['Dl_vc_permission']);
			}
	}

	// The basic function to get the download!
	$dl_file = array();
	$dl_file = $dl_mod->all_files(0, '', 'ASC', '', $df_id, $modcp);

	$cat_id = ($modcp) ? $cat_id : $dl_file['cat'];

	if ($modcp && $cat_id)
	{
		$cat_auth = array();
		$cat_auth = $dl_mod->dl_cat_auth($cat_id);

		if ((!$user->data['user_level'] == ADMIN) && !$cat_auth['auth_mod'])
		{
			$modcp = 0;
		}
	}
	else
	{
		$modcp = 0;
	}

	//check the permissions
	$check_status = array();
	$check_status = $dl_mod->dl_status($df_id);
	$status = $check_status['auth_dl'];
	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if ($modcp)
	{
		$check_status['auth_dl'] = true;
	}

	$browser = $dl_mod->dl_client();

	if ($check_status['auth_dl'] && $dl_file['id'])
	{
		//fix the mod and admin auth if needed
		if (!$dl_file['approve'])
		{
			if ((($cat_auth['auth_mod'] || $index[$cat_id]['auth_mod']) && $user->data['user_level'] != ADMIN) || $user->data['user_level'] == ADMIN)
			{
				$status = true;
			}
		}

		//update all statistics
		if ($status)
		{
			$sql = "UPDATE " . DOWNLOADS_TABLE . "
				SET klicks = klicks + 1, overall_klicks = overall_klicks + 1, last_time = " . time() . ", down_user = " . $user->data['user_id'] . "
				WHERE id = $df_id";
			$result = $db->sql_query($sql);

			if ($user->data['session_logged_in'] && !$dl_file['free'] && !$dl_file['extern'])
			{
				$count_user_traffic = true;
				// MG DL Counter - BEGIN
				if (($dl_config['user_download_limit_flag'] == true) && ($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD))
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_download_counter = (user_download_counter + 1)
						WHERE user_id = " . $user->data['user_id'];
					$db->sql_query($sql);
				}
				// MG DL Counter - END

				if ($dl_config['user_traffic_once'])
				{
					$sql = "SELECT * FROM " . DL_NOTRAF_TABLE . "
						WHERE user_id = " . $user->data['user_id'] . "
							AND dl_id = " . $dl_file['id'];
					$result = $db->sql_query($sql);
					$still_count = $db->sql_numrows($result);
					$db->sql_freeresult($result);

					if ($still_count)
					{
						$count_user_traffic = false;
					}
				}

				if ($count_user_traffic)
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_traffic = user_traffic - " . $dl_file['file_size'] . "
						WHERE user_id = " . $user->data['user_id'];
					$result = $db->sql_query($sql);

					if ($dl_config['user_traffic_once'])
					{
						$sql = "INSERT INTO " . DL_NOTRAF_TABLE . "
							(user_id, dl_id) VALUES (" . $user->data['user_id'] . ", " . $dl_file['id'] . ")";
						$result = $db->sql_query($sql);
					}
				}
			}

			if (!$dl_file['extern'])
			{
				$sql = "UPDATE " . DL_CONFIG_TABLE . "
					SET config_value = config_value + " . $dl_file['file_size'] . "
					WHERE config_name = 'remain_traffic'";
				$result = $db->sql_query($sql);

				$sql = "UPDATE " . DL_CAT_TABLE . "
					SET cat_traffic_use = cat_traffic_use + " . $dl_file['file_size'] . "
					WHERE id = $cat_id";
				$result = $db->sql_query($sql);
			}

			if ($index[$cat_id]['statistics'])
			{
				if ($index[$cat_id]['stats_prune'])
				{
					$stat_prune = $dl_mod->dl_prune_stats($cat_id, $index[$cat_id]['stats_prune']);
				}

				$sql = "INSERT INTO " . DL_STATS_TABLE . "
					(cat_id, id, user_id, username, traffic, direction, user_ip, browser, time_stamp) VALUES
					($cat_id, $df_id, " . $user->data['user_id'] . ", '" . $db->sql_escape($user->data['username']) . "', " . $db->sql_escape($dl_file['file_size']) . ", 0, '" . $db->sql_escape($user->data['session_ip']) . "', '" . $db->sql_escape($browser) . "', " . time() . ")";
				$result = $db->sql_query($sql);
			}
		}

		/*
		* not it is time and we are ready to rumble: send the file to the user client to download it there!
		*/
		if ($dl_file['extern'])
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: " . $dl_file['file_name']);
		}
		elseif ($status)
		{
			$dl_file_url = $dl_config['dl_path'] . $index[$cat_id]['cat_path'] . $dl_file['file_name'];

			$dl_file_size = sprintf("%u", @filesize($dl_file_url));

			$mem_limit = ini_get('memory_limit');

			$last = strlen($mem_limit) - 1;
			$max_mem_limit = (int)$mem_limit;

			switch($mem_limit{$last})
			{
				case 'G':
					$max_mem_limit *= 1024;
				case 'M':
					$max_mem_limit *= 1024;
				case 'K':
					$max_mem_limit *= 1024;
			}

			if ($dl_file_size > $max_mem_limit && $dl_config['dl_direct'])
			{
				$script_path = $config['script_path'];
				$server_name = trim($config['server_name']);
				$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
				$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) . '/' : '/';

				$server_url = $server_name . $server_port . $script_path;
				$server_url = $server_protocol . str_replace('//', '/', $server_url);

				$dl_file_url = $server_url . $dl_config['download_dir'] . $index[$cat_id]['cat_path'] . $dl_file['file_name'];
				$dl_file_url = str_replace(" ", "%20", $dl_file_url);

				header("HTTP/1.1 301 Moved Permanently");
				header("Location: ".$dl_file_url);
			}
			else
			{
				if ($dl_config['dl_method'] == 1)
				{
					header("Content-Type: application/octet-stream");
					header("Content-Disposition: attachment; filename=\"".$dl_file['file_name']."\"");
					readfile($dl_file_url);
				}
				elseif ($dl_config['dl_method'] == 2)
				{
					$size = sprintf("%u", @filesize($dl_file_url));
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Content-Type: application/octet-stream");
					header("Content-Length: ".$size);
					header("Content-Transfer-Encoding: binary");
					header("Content-Disposition: attachment; filename=\"".$dl_file['file_name']."\"");
					if ($size > $dl_config['dl_method_quota'])
					{
						$dl_mod->readfile_chunked($dl_file_url);
					}
					else
					{
						readfile($dl_file_url);
					}
				}
			}
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Dl_no_access']);
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_access']);
	}

	exit;
}
elseif ($view == 'comment')
{
	//check general permissions
	if (!$dl_mod->cat_auth_comment_read($cat_id))
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
	}

	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if (!$cat_auth['auth_view'] && !$index[$cat_id]['auth_view'] && $user->data['user_level'] != ADMIN)
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
	}

	//redirect to download details if comments are disabled for this category
	if (!$index[$cat_id]['comments'] || $goback)
	{
		$view = 'detail';
		$action = '';
	}

	//take the message if entered
	$comment_text = (!empty($_POST['message'])) ? htmlspecialchars(trim($_POST['message'])) : '';

	//someone cancel a job? list the list again and again...
	if ($cancel && $action == 'delete')
	{
		$action = '';
	}

	//check permissions to manage comments
	$sql = "SELECT user_id FROM " . DL_COMMENTS_TABLE . "
		WHERE id = $df_id
			AND dl_id = $dl_id
			AND approve = " . TRUE . "
			AND cat_id = $cat_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$allow_manage = 0;
	if ($row['user_id'] == $user->data['user_id'] || $cat_auth['auth_mod'] || $index[$cat_id]['auth_mod'] || $user->data['user_level'] == ADMIN)
	{
		$allow_manage = true;
	}

	$deny_post = 0;
	if (!$dl_mod->cat_auth_comment_post($cat_id))
	{
		$allow_manage = 0;
		$deny_post = true;
	}

	//open the comments view for this download if allowed
	if ($action)
	{
		$inc_module = true;
		$meta_content['page_title'] = $lang['Dl_comments'];
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_comments.' . PHP_EXT);
	}
	else
	{
		if ($df_id)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?view=detail&df_id=' . $df_id, true));
		}
		elseif ($cat_id)
		{
			redirect(append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id));
		}
		else
		{
			redirect(append_sid('downloads.' . PHP_EXT));
		}
	}
}
elseif ($view == 'upload')
{
	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	$physical_size = $dl_mod->read_dl_sizes($dl_config['dl_path']);
	if ($physical_size >= $dl_config['physical_quota'])
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_blue_explain']);
	}

	if (($dl_config['stop_uploads'] && $user->data['user_level'] != ADMIN) || !sizeof($index) || (!$cat_auth['auth_up'] && !$index[$cat_id]['auth_up'] && $user->data['user_level'] != ADMIN))
	{
		message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
	}

	$inc_module = true;
	$meta_content['page_title'] = $lang['Dl_upload'];
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?view=upload') . '">' . $lang['Dl_upload'] . '</a>';
	include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_upload.' . PHP_EXT);
}
elseif ($view == 'modcp')
{
	$deny_modcp = 0;

	if (($action == 'edit' || $action == 'save') && $dl_config['edit_own_downloads'])
	{
		$sql = "SELECT add_user FROM " . DOWNLOADS_TABLE . "
			WHERE id = $df_id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($row['add_user'] == $user->data['user_id'])
		{
			$own_edit = true;
		}
		else
		{
			$own_edit = 0;
		}
	}
	else
	{
		$owm_edit = 0;
	}

	if ($own_edit)
	{
		$access_cat[0] = $cat_id;
		$deny_modcp = 0;
	}
	else
	{
		$access_cat = array();
		$access_cat = $dl_mod->full_index(0, 0, 0, 2);
		if (!sizeof($access_cat) && $user->data['user_level'] != ADMIN)
		{
			$deny_modcp = true;
		}
	}

	$cat_auth = array();
	$cat_auth = $dl_mod->dl_cat_auth($cat_id);

	if (!$cat_id && !$cat_auth['auth_mod'] && !$index[$cat_id]['auth_mod'] && $user->data['user_level'] != ADMIN)
	{
		$deny_modcp = true;
	}

	if ($deny_modcp)
	{
		$view = '';
		$action = '';
	}
	else
	{
		$action = ($move) ? 'move' : $action;
		$action = ($delete) ? 'delete' : $action;
		$action = ($cdelete) ? 'cdelete' : $action;
		$action = ($lock) ? 'lock' : $action;
		$action = ($cancel) ? 'manage' : $action;

		$action = (!$action) ? 'manage' : $action;

		$meta_content['description'] = '';
		$meta_content['keywords'] = '';

		switch ($action)
		{
			case 'approve':
				$meta_content['page_title'] = $lang['Dl_modcp_approve'];
				break;
			case 'capprove':
				$meta_content['page_title'] = $lang['Dl_modcp_capprove'];
				break;
			case 'edit':
			case 'save':
				$meta_content['page_title'] = $lang['Dl_modcp_edit'];
				break;
			case 'manage':
			case 'delete':
			case 'cdelete':
			case 'lock':
			case 'move':
				$meta_content['page_title'] = $lang['Dl_modcp_manage'];
				break;
			default:
				$meta_content['page_title'] = $lang['Dl_modcp_manage'];
				$action = 'manage';
		}

		$dl_id = (isset($_POST['dlo_id'])) ? $_POST['dlo_id'] : array();

		if ($fmove && $user->data['user_level'] == ADMIN)
		{
			if ($fmove == 'ABC')
			{
				$sql = "SELECT id FROM " . DOWNLOADS_TABLE . "
					WHERE cat = $cat_id
					ORDER BY description ASC";
				$result = $db->sql_query($sql);
			}
			else
			{
				$sql_move = ($fmove == 1) ? '+ 15' : '-15';

				$sql = "UPDATE " . DOWNLOADS_TABLE . "
					SET sort = sort $sql_move
					WHERE id = $df_id";
				$result = $db->sql_query($sql);

				$sql = "SELECT id FROM " . DOWNLOADS_TABLE . "
					WHERE cat = $cat_id
					ORDER BY sort ASC";
				$result = $db->sql_query($sql);
			}

			$i = 10;

			while($row = $db->sql_fetchrow($result))
			{
				$sql_sort = "UPDATE " . DOWNLOADS_TABLE . "
						SET sort = $i
						WHERE id = " . $row['id'];
				$result_sort = $db->sql_query($sql_sort);
				$i += 10;
			}

			$db->sql_freeresult($result);

			$action = 'manage';
		}

		$fmove = '';

		$inc_module = true;
		include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_modcp.' . PHP_EXT);
	}
}

//sorting downloads
if ($dl_config['sort_preform'])
{
	$sort_by = 0;
	$order = ASC;
}
else
{
	$sort_by = (!$sort_by) ? $user->data['user_dl_sort_fix'] : $sort_by;
	$order = (!$order) ? (($user->data['user_dl_sort_dir']) ? 'DESC' : 'ASC') : $order;
}

switch ($sort_by)
{
	case 1:
		$sql_sort_by = 'description';
		break;
	case 2:
		$sql_sort_by = 'file_name';
		break;
	case 3:
		$sql_sort_by = 'klicks';
		break;
	case 4:
		$sql_sort_by = 'free';
		break;
	case 5:
		$sql_sort_by = 'extern';
		break;
	case 6:
		$sql_sort_by = 'file_size';
		break;
	case 7:
		$sql_sort_by = 'change_time';
		break;
	case 8:
		$sql_sort_by = 'rating';
		break;
	default:
		$sql_sort_by = 'sort';
}

switch ($order)
{
	case 'ASC':
		$sql_order = 'ASC';
		break;
	case 'DESC':
		$sql_order = 'DESC';
		break;
	default:
		$sql_order = 'ASC';
}

if (!$dl_config['sort_preform'] && $user->data['user_dl_sort_opt'])
{
	$template->assign_block_vars('sort_options', array());

	$s_sort_by = '<select name="sort_by" onchange="forms[\'dl_mod\'].submit()">';
	$s_sort_by .= '<option value="0">' . $lang['Dl_default_sort'] . '</option>';
	$s_sort_by .= '<option value="1">' . $lang['Dl_file_description'] . '</option>';
	$s_sort_by .= '<option value="2">' . $lang['Dl_file_name'] . '</option>';
	$s_sort_by .= '<option value="3">' . $lang['Dl_klicks'] . '</option>';
	$s_sort_by .= '<option value="4">' . $lang['Dl_free'] . '</option>';
	$s_sort_by .= '<option value="5">' . $lang['Dl_extern'] . '</option>';
	$s_sort_by .= '<option value="6">' . $lang['Dl_file_size'] . '</option>';
	$s_sort_by .= '<option value="7">' . $lang['Last_updated'] . '</option>';
	$s_sort_by .= '<option value="8">' . $lang['Dl_rating'] . '</option>';
	$s_sort_by .= '</select>';
	$s_sort_by = str_replace('value="' . $sort_by . '">', 'value="' . $sort_by . '" selected="selected">', $s_sort_by);

	$s_order = '<select name="order" onchange="forms[\'dl_mod\'].submit()">';
	$s_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option>';
	$s_order .= '<option value="DESC">' . $lang['Sort_Descending'] . '</option>';
	$s_order .= '</select>';
	$s_order = str_replace('value="' . $order . '">', 'value="' . $order . '" selected="selected">', $s_order);
}

//create download overall view
if (($view == 'overall') && sizeof($index))
{
	$meta_content['page_title'] = $lang['Dl_overview'];
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT . '?view=overall') . '">' . $lang['Dl_overview'] . '</a>';

	$sql = "SELECT dl_id, user_id FROM " . DL_RATING_TABLE;
	$result = $db->sql_query($sql);

	$ratings = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$ratings[$row['dl_id']][] = $row['user_id'];
	}
	$db->sql_freeresult($result);

	$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_overview_body.tpl');

	$template->assign_vars(array(
		'L_DESCRIPTION' => $lang['Dl_file_description'],
		'L_DL_CAT' => $lang['Dl_cat_name'],
		'L_DL_FILES' => $lang['Dl_cat_files'],
		'L_DESCRIPTION' => $lang['Dl_file_description'],
		'L_STATUS' => $lang['Dl_info'],
		'L_KLICKS' => $lang['Dl_klicks'],
		'L_OVERALL_KLICKS' => $lang['Dl_overall_klicks'],
		'L_KL_M_T' => $lang['Dl_klicks_total'],
		'L_SIZE' => $lang['Dl_file_size'],
		'L_NAME' => $lang['Dl_name'],
		'L_TOP' => $lang['Back_to_top'],
		'L_SORT_BY' => $lang['Sort_by'],
		'L_ORDER' => $lang['Order'],
		'L_RATING' => $lang['Dl_rating'],
		'L_DOWNLOADS' => $lang['Dl_cat_title'],
		'L_GO' => $lang['Go'],

		'S_SORT_BY' => $s_sort_by,
		'S_ORDER' => $s_order,

		'U_DOWNLOADS_ADV' => append_sid('downloads.' . PHP_EXT . '?view=overall'),
		'U_DL_INDEX' => append_sid('downloads.' . PHP_EXT),

		'COLSPAN' => $colspan,
		'PAGE_NAME' => $meta_content['page_title']
		)
	);

	$dl_files = array();
	$dl_files = $dl_mod->all_files();

	$total_files = 0;

	if (sizeof($dl_files))
	{
		for ($i = 0; $i < sizeof($dl_files); $i++)
		{
			$cat_id = $dl_files[$i]['cat'];
			$cat_auth = array();
			$cat_auth = $dl_mod->dl_cat_auth($cat_id);
			if ($cat_auth['auth_view'] || $index[$cat_id]['auth_view'] || $user->data['user_level'] == ADMIN)
			{
				$total_files++;
			}
		}
	}

	if ($total_files > $config['topics_per_page'])
	{
		$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=overall&amp;sort_by=' . $sort_by . '&amp;order=' . $order, $total_files, $config['topics_per_page'], $start);
		$template->assign_vars(array(
			'PAGINATION' => $pagination
			)
		);
	}

	$sql_sort_by = ($sql_sort_by == 'sort') ? 'cat, sort' : $sql_sort_by;

	$dl_files = array();
	$dl_files = $dl_mod->all_files(0, '', '', ' ORDER BY ' . $sql_sort_by . ' ' . $sql_order . ' LIMIT ' . $start . ', ' . $config['topics_per_page'], 0, 0, 'cat, id, description, hack_version, extern, file_size, klicks, overall_klicks, rating');

	if (sizeof($dl_files))
	{
		for ($i = 0; $i < sizeof($dl_files); $i++)
		{
			$cat_id = $dl_files[$i]['cat'];
			$cat_auth = array();
			$cat_auth = $dl_mod->dl_cat_auth($cat_id);
			if ($cat_auth['auth_view'] || $index[$cat_id]['auth_view'] || $user->data['user_level'] == ADMIN)
			{
				$cat_name = $index[$cat_id]['cat_name'];
				$cat_name = str_replace('&nbsp;&nbsp;|', '', $cat_name);
				$cat_name = str_replace('___&nbsp;', '', $cat_name);
				$cat_view = $index[$cat_id]['nav_path'];

				$file_id = $dl_files[$i]['id'];
				$mini_file_icon = $dl_mod->mini_status_file($cat_id, $file_id);

				$description = $dl_files[$i]['description'];
				$dl_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

				$hack_version = '&nbsp;' . $dl_files[$i]['hack_version'];

				$dl_status = array();
				$dl_status = $dl_mod->dl_status($file_id);
				$status = $dl_status['status'];

				if ($dl_files[$i]['extern'])
				{
					$file_size = $lang['Dl_not_availible'];
				}
				else
				{
					$file_size = $dl_mod->dl_size($dl_files[$i]['file_size'], 2);
				}

				$file_klicks = $dl_files[$i]['klicks'];
				$file_overall_klicks = $dl_files[$i]['overall_klicks'];

				$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$rating_points = $dl_files[$i]['rating'];

				$u_rating_link = '';
				if (($rating_points == 0 || !@in_array($user->data['user_id'], $ratings[$file_id])) && $user->data['session_logged_in'])
				{
					$u_rating_link = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;action=rate&amp;df_id=' . $file_id . '&amp;dlo=1') . '">' . $lang['Dl_klick_to_rate'] . '</a>';
				}

				if (sizeof($ratings[$file_id]))
				{
					$rating_count_text = '&nbsp;[ ' . sizeof($ratings[$file_id]) . ' ]';
				}
				else
				{
					$rating_count_text = '';
				}

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('download', array(
					'ROW_CLASS' => $row_class,
					'DESCRIPTION' => $mini_icon.$description,
					'HACK_VERSION' => $hack_version,
					'STATUS' => $status,
					'FILE_SIZE' => $file_size,
					'FILE_KLICKS' => $file_klicks,
					'FILE_OVERALL_KLICKS' => $file_overall_klicks,
					'RATING_IMG' => $dl_mod->rating_img($rating_points),
					'RATINGS' => $rating_count_text,
					'U_CAT_VIEW' => $cat_view,
					'CAT_NAME' => $cat_name,
					'U_RATING' => $u_rating_link,
					'U_DL_LINK' => $dl_link
					)
				);
			}
		}
	}
}

page_header($meta_content['page_title'], true);

//default user entry. redirect to index or category
if (empty($view) && !$inc_module)
{
	$view = 'view';

	if (!$cat)
	{
		$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'view_dl_cat_body.tpl');
	}
	else
	{
		$cat_auth = array();
		$cat_auth = $dl_mod->dl_cat_auth($cat);
		$index_auth = array();
		$index_auth = $dl_mod->full_index($cat);

		if (!$cat_auth['auth_view'] && !$index_auth[$cat]['auth_view'] && $user->data['user_level'] != ADMIN)
		{
			redirect(append_sid('downloads.' . PHP_EXT));
		}

		$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'downloads_body.tpl');

		$sql = "SELECT dl_id, user_id FROM " . DL_RATING_TABLE;
		$result = $db->sql_query($sql);

		$ratings = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$ratings[$row['dl_id']][] = $row['user_id'];
		}
		$db->sql_freeresult($result);
	}

	$path_dl_array = array();
	$dl_nav = $cat ? ($dl_mod->dl_nav($cat, 'text')) : '';
	$meta_content['page_title'] = $lang['Downloads'] . $dl_nav;
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';
	$nav_server_url = create_server_url();
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>' . (($dl_nav != '') ? ('<a class="nav-current" href="#">' . $dl_nav . '</a>'): '');
	$path_dl_array = array();

	$user_id = $user->data['user_id'];
	$username = $user->data['username'];
	$user_traffic = $user->data['user_traffic'];

	$sql = "SELECT c.parent, d.cat, d.id, d.change_time, d. description, d.change_user, u.user_id, u.username, u.user_active, u.user_color
		FROM " . DOWNLOADS_TABLE . " d, " . USERS_TABLE . " u, " . DL_CAT_TABLE . " c
		WHERE d.cat = c.id
			AND d.approve = " . TRUE . "
			AND u.user_id = d.change_user
		ORDER BY cat, change_time DESC, id DESC";
	$result = $db->sql_query($sql);
	$last_dl = array();
	$last_id = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['cat'] != $last_id)
		{
			$last_id = $row['cat'];
			$last_dl[$last_id]['change_time'] = $row['change_time'];
			$last_dl[$last_id]['parent'] = $row['parent'];
			$last_dl[$last_id]['desc'] = $row['description'];
			$last_dl[$last_id]['user'] = ($row['username'] == '') ? $lang['Guest'] : $row['username'];
			$last_dl[$last_id]['time'] = create_date($config['default_dateformat'], $row['change_time'], $config['board_timezone']);
			$last_dl[$last_id]['link'] = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $row['id']);
			//$last_dl[$last_id]['user_link'] = append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['change_user']);
			$last_dl[$last_id]['user_link'] = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		}
	}
	$db->sql_freeresult($result);

	if (sizeof($index) > 0)
	{
		$i = 0;
		foreach(array_keys($index) as $key)
		{
			$cat_id = $key;
			$parent_id = $index[$cat_id]['parent'];
			$cat_name = $index[$cat_id]['cat_name'];
			$cat_desc = $index[$cat_id]['description'];
			$cat_view = $index[$cat_id]['nav_path'];
			$cat_sublevel = $index[$cat_id]['sublevel'];

			if ($cat_desc)
			{
				$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html']) ? true : false;
				$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
				$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
				$cat_desc = $bbcode->parse($cat_desc);
				$cat_desc = str_replace("\n", "\n<br />", $cat_desc);
			}

			$mini_icon = array();
			$mini_icon = $dl_mod->mini_status_cat($cat_id, $cat_id);

			$mini_cat_icon = '';

			if ($mini_icon[$cat_id]['new'])
			{
				$mini_cat_icon .= '<img src="' . $images['Dl_new'] . '" alt="' . $lang['DL_new'] . '" title="' . $lang['DL_new'] . '" border="0" />&nbsp;';
			}
			if ($mini_icon[$cat_id]['edit'])
			{
				$mini_cat_icon .= '<img src="' . $images['Dl_edit'] . '" alt="' . $lang['DL_edit'] . '" title="' . $lang['DL_edit'] . '" border="0" />&nbsp;';
			}

			$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$cat_pages = '';
			if ($index[$cat_id]['total'] > $dl_config['dl_links_per_page'])
			{
				$cat_pages .= '<br />[&nbsp;' . $lang['Goto_page'] . ':';
				$page = 1;
				for ($j = 0; $j < $index[$cat_id]['total']; $j += $dl_config['dl_links_per_page'])
				{
					$cat_pages .= '&nbsp;<a href="' . append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id . '&amp;start=' . $j) . '">' . $page . '</a>';
					if ($page < ceil($index[$cat_id]['total'] / $dl_config['dl_links_per_page']))
					{
						$cat_pages .= ',';
					}
					$page++;
				}
				$cat_pages .= '&nbsp;]';
			}

			$last_dl_time = $dl_mod->find_latest_dl($last_dl, $cat_id);
			$last_cat_id = $last_dl_time['cat_id'];

			if ($last_dl[$cat_id]['change_time'] > $last_dl_time['change_time'])
			{
				$last_cat_id = $cat_id;
			}

			if ($cat)
			{
				$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'view_dl_subcat_body.tpl');
				$template->set_filenames(array('subcats' => $template_to_parse));

				$block = 'subcats';

				$template->assign_vars(array(
					'L_DESCRIPTION' => $lang['Dl_file_description'],
					'L_DL_CAT' => $lang['Dl_cat_name'],
					'L_DL_FILES' => $lang['Dl_cat_files'],
					'L_LAST' => $lang['Last_updated']
					)
				);
			}
			else
			{
				$block = 'downloads';
			}

			if ((time() - $last_dl[$last_cat_id]['change_time']) < $dl_config['dl_new_time'])
			{
				$cat_img = $images['forum_nor_read'];
			}
			else
			{
				$cat_img = $images['forum_nor_unread'];
			}

			$template->assign_block_vars($block, array(
				'ROW_CLASS' => $row_class,
				'ROW_SPAN' => (sizeof($cat_sublevel['cat_path'])) ? 'rowspan="2"' : '',
				'MINI_IMG' => $mini_cat_icon,
				'U_CAT_VIEW' => $cat_view,
				'SUBLEVEL' => $cat_sublevel_out,
				'CAT_IMG' => $cat_img,
				'CAT_DESC' => $cat_desc,
				'CAT_NAME' => $cat_name,
				'CAT_PAGES' => $cat_pages,
				'CAT_DL' => $index[$cat_id]['total'],
				'CAT_LAST_DL' => $last_dl[$last_cat_id]['desc'],
				'CAT_LAST_USER' => $last_dl[$last_cat_id]['user'],
				'CAT_LAST_TIME' => $last_dl[$last_cat_id]['time'],
				'U_CAT_LAST_LINK' => $last_dl[$last_cat_id]['link'],
				'U_CAT_LAST_USER' => $last_dl[$last_cat_id]['user_link']
				)
			);

			if (sizeof($cat_sublevel['cat_path']))
			{
				$template->assign_block_vars($block . '.sub', array());
			}

			for ($j = 0; $j < sizeof($cat_sublevel['cat_path']); $j++)
			{
				$sub_id = $cat_sublevel['cat_sub'][$j];
				$mini_icon = array();
				$mini_icon = $dl_mod->mini_status_cat($sub_id, $sub_id);

				$mini_cat_icon = '';

				if ($mini_icon[$sub_id]['new'])
				{
					$mini_cat_icon .= '<img src="' . $images['Dl_new'] . '" border="0" alt="' . $lang['DL_new'] . '" title="' . $lang['DL_new'] . '" />&nbsp;';
				}
				if ($mini_icon[$sub_id]['edit'])
				{
					$mini_cat_icon .= '<img src="' . $images['Dl_edit'] . '" border="0" alt="' . $lang['DL_edit'] . '" title="' . $lang['DL_edit'] . '" />&nbsp;';
				}

				$row_class = (($j % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars($block.'.sub.sublevel_row', array(
					'ROW_CLASS' => $row_class,
					'L_SUBLEVEL' => $cat_sublevel['cat_name'][$j],
					'SUBLEVEL_COUNT' => $cat_sublevel['total'][$j] + $dl_mod->get_sublevel_count($cat_sublevel['cat_id'][$j]),
					'M_SUBLEVEL' => $mini_cat_icon,
					'U_SUBLEVEL' => $cat_sublevel['cat_path'][$j]
					)
				);
			}

			$i++;

			if ($cat)
			{
				$template->assign_var_from_handle('SUBCAT_BOX', 'subcats');
			}
		}
	}
	else
	{
		$template->assign_block_vars('no_category', array(
			'L_NO_CATEGORY' => $lang['Dl_no_category_index']
			)
		);
	}

	if ($cat)
	{
		$index_cat = array();
		$index_cat = $dl_mod->full_index($cat);
		$total_downloads = $index_cat[$cat]['total'];

		if ($total_downloads)
		{
			$pagination = generate_pagination('downloads.' . PHP_EXT . '?cat=' . $cat . '&amp;sort_by=' . $sort_by . '&amp;order=' . $order, $total_downloads, $dl_config['dl_links_per_page'], $start);

			$template->assign_vars(array(
				'PAGINATION' => $pagination,
				'PAGE_NUMBER' => ($total_downloads > $dl_config['dl_links_per_page']) ? sprintf($lang['Page_of'], (floor($start / $dl_config['dl_links_per_page']) + 1), ceil($total_downloads / $dl_config['dl_links_per_page'])) : '',
				'L_GOTO_PAGE' => $lang['Goto_page']
				)
			);
		}

		if ($index_cat[$cat]['rules'])
		{
			$cat_rule = $index_cat[$cat]['rules'];
			$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html']) ? true : false;
			$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
			$cat_rule = $bbcode->parse($cat_rule);
			$cat_rule = str_replace("\n", "\n<br />", $cat_rule);
			$template->assign_block_vars('cat_rule', array(
				'CAT_RULE' => $cat_rule
				)
			);
		}

		if ($dl_mod->user_auth($cat, 'auth_mod'))
		{
			$template->assign_block_vars('modcp', array(
				'DL_MODCP' => ($total_downloads) ? sprintf($lang['Dl_modcp_mod_auth'], '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;cat_id=' . $cat) . '">', '</a>') : ''
				)
			);
		}

		$physical_size = $dl_mod->read_dl_sizes($dl_config['dl_path']);

		if ($physical_size < $dl_config['physical_quota'] && (!$dl_config['stop_uploads']) || ($user->data['user_level'] == ADMIN))
		{
			if ($dl_mod->user_auth($cat, 'auth_up'))
			{
				$dl_upload_link = append_sid('downloads.' . PHP_EXT . '?view=upload&amp;cat_id=' . $cat);
				$template->assign_vars(array(
					'DL_UPLOAD_URL' => $dl_upload_link,
					'DL_UPLOAD_LANG' => $lang['Dl_upload'],
					'DL_UPLOAD' => '<a href="' . $dl_upload_link . '"><img src="' . $images['Dl_upload'] . '" border="0" alt="' . $lang['Dl_upload'] . '"  title="' . $lang['Dl_upload'] . '" /></a>&nbsp;&nbsp;&nbsp;'
					)
				);
			}
		}

		$cat_traffic = $index_cat[$cat]['cat_traffic'] - $index_cat[$cat]['cat_traffic_use'];
		if ($index_cat[$cat]['cat_traffic'] && $cat_traffic > 0)
		{
			$cat_traffic = ($cat_traffic > $dl_config['overall_traffic']) ? $dl_config['overall_traffic'] : $cat_traffic;
			$cat_traffic = $dl_mod->dl_size($cat_traffic);

			$template->assign_block_vars('cat_traffic', array(
				'CAT_TRAFFIC' => sprintf($lang['Dl_cat_traffic_main'], $cat_traffic)
				)
			);
		}
	}

	$i = 0;

	if ($cat && $total_downloads)
	{
		$dl_files = array();
		$dl_files = $dl_mod->files($cat, $sql_sort_by, $sql_order, $start, $dl_config['dl_links_per_page'], 'id, description, hack_version, extern, file_size, klicks, overall_klicks, rating, long_desc');

		if ($dl_mod->cat_auth_comment_read($cat))
		{
			$sql = "SELECT count(dl_id) AS total_comments, id FROM " . DL_COMMENTS_TABLE . "
				WHERE cat_id = $cat
					AND approve = " . TRUE . "
				GROUP BY id";
			$result = $db->sql_query($sql);
			$comment_count = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$comment_count[$row['id']] = $row['total_comments'];
			}
			$db->sql_freeresult($result);
		}

		for ($i = 0; $i < sizeof($dl_files); $i++)
		{
			$file_id = $dl_files[$i]['id'];
			$mini_file_icon = $dl_mod->mini_status_file($cat, $file_id);

			$description = $dl_files[$i]['description'];
			$file_url = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id);

			$hack_version = '&nbsp;' . $dl_files[$i]['hack_version'];

			$long_desc = stripslashes($dl_files[$i]['long_desc']);
			if (intval($dl_config['limit_desc_on_index']) && strlen($long_desc) > intval($dl_config['limit_desc_on_index']))
			{
				$long_desc = substr($long_desc, 0, intval($dl_config['limit_desc_on_index'])) . ' [...]';
			}

			$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html']) ? true : false;
			$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
			$long_desc = $bbcode->parse($long_desc);
			$long_desc = str_replace("\n", "\n<br />\n", $long_desc);

			$dl_status = array();
			$dl_status = $dl_mod->dl_status($file_id);
			$status = $dl_status['status'];

			if ($dl_files[$i]['extern'])
			{
				$file_size = $lang['Dl_not_availible'];
			}
			else
			{
				$file_size = $dl_mod->dl_size($dl_files[$i]['file_size'], 2);
			}

			$file_klicks = $dl_files[$i]['klicks'];
			$file_overall_klicks = $dl_files[$i]['overall_klicks'];

			$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			if ($cat)
			{
				$rating_points = $dl_files[$i]['rating'];

				$l_rating_text = $u_rating_text = '';
				if ((!$rating_points || !@in_array($user->data['user_id'], $ratings[$file_id])) && $user->data['session_logged_in'])
				{
					$l_rating_text = $lang['Dl_klick_to_rate'];
					$u_rating_text = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;action=rate&amp;df_id=' . $file_id . '&amp;dlo=2&amp;start=' . $start);
				}

				if (sizeof($ratings[$file_id]))
				{
					$rating_count_text = '&nbsp;[ ' . sizeof($ratings[$file_id]) . ' ]';
				}
				else
				{
					$rating_count_text = '';
				}
			}

			$template->assign_block_vars('downloads', array(
				'ROW_CLASS' => $row_class,
				'DESCRIPTION' => $description,
				'MINI_IMG' => $mini_file_icon,
				'HACK_VERSION' => $hack_version,
				'LONG_DESC' => $long_desc,
				'RATING_IMG' => $dl_mod->rating_img($rating_points),
				'RATINGS' => $rating_count_text,
				'L_RATING' => $l_rating_text,
				'U_RATING' => $u_rating_text,
				'STATUS' => $status,
				'FILE_SIZE' => $file_size,
				'FILE_KLICKS' => $file_klicks,
				'FILE_OVERALL_KLICKS' => $file_overall_klicks,
				'U_FILE' => $file_url
				)
			);

			$col_width = 6;

			if ($index_cat[$cat]['comments'] && $dl_mod->cat_auth_comment_read($cat))
			{
				$col_width++;

				if ($comment_count[$file_id])
				{
					$template->assign_block_vars('downloads.comments', array(
						'L_COMMENT_POST' => ($dl_mod->cat_auth_comment_post($cat)) ? $lang['Dl_post_comment'] : '',
						'L_COMMENT_SHOW' => $lang['Dl_view_comments'],
						'BREAK' => ($dl_mod->cat_auth_comment_post($cat)) ? '<br />' : '',
						'U_COMMENT_POST' => ($dl_mod->cat_auth_comment_post($cat)) ? append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=post&amp;cat_id=' . $cat . '&amp;df_id=' . $file_id) : '',
						'U_COMMENT_SHOW' => append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=view&amp;cat_id=' . $cat . '&amp;df_id=' . $file_id)
						)
					);
				}
				elseif ($dl_mod->cat_auth_comment_post($cat))
				{
					$template->assign_block_vars('downloads.comments', array(
						'L_COMMENT_POST' => $lang['Dl_post_comment'],
						'U_COMMENT_POST' => append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=post&amp;cat_id=' . $cat . '&amp;df_id=' . $file_id)
						)
					);
				}
				else
				{
					$template->assign_block_vars('downloads.comments', array());
				}
			}
		}
	}

	if ($i)
	{
		$template->assign_block_vars('download_rows', array());

		if ($index_cat[$cat]['comments'] && $dl_mod->cat_auth_comment_read($cat))
		{
			$sql = "SELECT count(dl_id) as total_comments, id FROM " . DL_COMMENTS_TABLE . "
				WHERE cat_id = $cat
					AND approve = " . TRUE . "
				GROUP BY id";
			$result = $db->sql_query($sql);

			$comment_count = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$comment_count[$row['id']] = $row['total_comments'];
			}
			$db->sql_freeresult($result_comment);

			$template->assign_block_vars('download_rows.comment_header', array(
				'L_COMMENTS' => $lang['Dl_comments']
				)
			);
		}
	}

	if ($cat && !$total_downloads)
	{
		$template->assign_block_vars('empty_category', array(
			'L_EMPTY_CATEGORY' => $lang['Dl_empty_category'],
			'COL_WIDTH' => 6
			)
		);
	}

	$template->assign_vars(array(
		'L_INFO' => $lang['Dl_info'],
		'L_NAME' => $lang['Dl_name'],
		'L_DL_CAT' => $lang['Dl_cat_name'],
		'L_DL_FILES' => $lang['Dl_cat_files'],
		'L_DESCRIPTION' => $lang['Dl_file_description'],
		'L_SIZE' => $lang['Dl_file_size'],
		'L_KLICKS' => $lang['Dl_klicks'],
		'L_OVERALL_KLICKS' => $lang['Dl_overall_klicks'],
		'L_KL_M_T' => $lang['Dl_klicks_total'],
		'L_RATING' => $lang['Dl_rating'],
		'L_SORT_BY' => $lang['Sort_by'],
		'L_ORDER' => $lang['Order'],
		'L_DL_TOP' => $lang['Dl_cat_title'],
		'L_LAST' => $lang['Last_updated'],
		'L_GO' => $lang['Go'],
		'L_LEGEND' => $lang['legend'],

		'COL_WIDTH' => $col_width,
		'ROW_1' => $theme['td_class1'],
		'ROW_2' => $theme['td_class2'],

		'S_SORT_BY' => $s_sort_by,
		'S_ORDER' => $s_order,

		'T_DL_CAT' => ($cat) ? $index[$cat]['cat_name'] : $lang['Dl_cat_name'],

		'U_DOWNLOADS' => append_sid('downloads.' . PHP_EXT . '?start=' . $start . '&amp;cat=' . $cat),
		'U_DL_SEARCH' => (sizeof($index) || $cat) ? '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=search') . '"><img src="' . $images['icon_search'] . '" alt="' . $lang['Search'] . '" title="' . $lang['Search'] . '" border="0" /></a>' : '&nbsp;',
		'U_DL_CAT' => ($cat) ? $dl_mod->dl_nav($cat, 'url') : '',

		'U_DL_TOP' => append_sid('downloads.' . PHP_EXT)
		)
	);
}

$view_check = array('comment', 'detail', 'load', 'modcp', 'overall', 'popup', 'search', 'stat', 'todo', 'upload', 'user_config', 'view', 'broken', 'unbroken', 'fav', 'unfav', 'bug_tracker');
if (!in_array($view, $view_check))
{
	message_die(GENERAL_MESSAGE, $lang['Dl_no_permission']);
}

$template->assign_vars(array(
	'U_HELP_POPUP' => IP_ROOT_PATH . 'dl_help.' . PHP_EXT . '?help_key='
	)
);

$template->set_filenames(array('body' => $template_to_parse));
$template->pparse('body');
include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'includes/dl_footer.' . PHP_EXT);
page_footer(true, '', true);

?>