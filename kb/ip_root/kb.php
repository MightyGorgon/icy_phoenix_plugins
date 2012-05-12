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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
// End session management

$cms_page['page_id'] = 'kb';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['kb']['dir'] . 'common.' . PHP_EXT);

// Instanciate custom fields
$kb_custom_field = new kb_custom_field();
$kb_custom_field->init();

$show_new = true;

// Page number
$page_num = request_var('page_num', 0);
$page_num = ($page_num > 0) ? ($page_num - 1) : 0;

// Print version
$print_version = request_var('print', '');

// Mode
$mode = request_var('mode', '');
$mode = (($mode != 'cat') || (intval($_GET['cat']) != 0)) ? $mode : '';

// Stats
$stats = request_var('stats', '');

$reader_mode = false;

if ($mode == 'article')
{
	include(KB_ROOT_PATH . 'includes/kb_article.' . PHP_EXT);
}
elseif ($mode == 'cat')
{
	include(KB_ROOT_PATH . 'includes/kb_cat.' . PHP_EXT);
}
elseif ($mode == 'add')
{
	include(KB_ROOT_PATH . 'includes/kb_post.' . PHP_EXT);
}
elseif ($mode == 'search')
{
	include(KB_ROOT_PATH . 'includes/kb_search.' . PHP_EXT);
}
elseif ($mode == 'edit')
{
	include(KB_ROOT_PATH . 'includes/kb_post.' . PHP_EXT);
}
elseif ($mode == 'rate')
{
	include(KB_ROOT_PATH . 'includes/kb_rate.' . PHP_EXT);
}
elseif ($mode == 'stats')
{
	include(KB_ROOT_PATH . 'includes/kb_stats.' . PHP_EXT);
}
elseif ($mode == 'moderate')
{
	include(KB_ROOT_PATH . 'includes/kb_moderator.' . PHP_EXT);
}
else
{
	// DEFAULT ACTION
	$meta_content['page_title'] = $lang['KB_title'];
	$meta_content['description'] = '';
	$meta_content['keywords'] = '';

	// load header
	include(KB_ROOT_PATH . 'includes/kb_header.' . PHP_EXT);

	$template->set_filenames(array('body' => $class_plugins->get_tpl_file(KB_TPL_PATH, 'kb_index_body.tpl')));

	$template->assign_vars(array(
		'L_CATEGORY' => $lang['Category'],
		'L_ARTICLES' => $lang['Articles']
		)
	);

	get_kb_cat_index();
}

$template->pparse('body');

// load footer
if (!$print_version)
{
	include(KB_ROOT_PATH . 'includes/kb_footer.' . PHP_EXT);
}

if (!$is_block && !$print_version)
{
	page_footer(true, '', true);
}

?>