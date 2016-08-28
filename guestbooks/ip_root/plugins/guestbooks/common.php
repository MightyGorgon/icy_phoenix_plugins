<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//$plugin_name = empty($plugin_name) ? 'guestbooks' : $plugin_name;
$plugin_name = 'guestbooks';
if (empty($skip_page_auth))
{
	$cms_page['page_id'] = (!empty($cms_page['page_id']) ? $cms_page['page_id'] : $plugin_name);
	$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
	$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
	$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
	check_page_auth($cms_page['page_id'], $cms_auth_level);
}

if (!defined('CMS_PAGE_GUESTBOOK'))
{
	define('CMS_PAGE_GUESTBOOK', 'guestbook.' . PHP_EXT);
	define('CMS_PAGE_GUESTBOOKS', 'guestbooks.' . PHP_EXT);

	define('GUESTBOOKS_LIST_TABLE', $table_prefix . 'guestbooks');
	define('GUESTBOOKS_POSTS_TABLE', $table_prefix . 'guestbooks_posts');

	define('GUESTBOOKS_PLUGIN_PATH', PLUGINS_PATH . $config['plugins'][$plugin_name]['dir']);
	define('GUESTBOOKS_ROOT_PATH', IP_ROOT_PATH . GUESTBOOKS_PLUGIN_PATH);
	//define('GUESTBOOKS_TPL_PATH', 'guestbooks/');
	define('GUESTBOOKS_TPL_PATH', '../../' . GUESTBOOKS_PLUGIN_PATH . 'templates/');
	define('GUESTBOOKS_ADM_PATH', IP_ROOT_PATH . GUESTBOOKS_PLUGIN_PATH . ADM . '/');
	define('GUESTBOOKS_ADM_TPL_PATH', '../../' . GUESTBOOKS_PLUGIN_PATH . ADM . '/templates/');
}

if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
if (empty($bbcode)) $bbcode = new bbcode();
$bbcode->allow_html = ($config['allow_html'] ? true : false);
$bbcode->allow_bbcode = ($config['allow_bbcode'] ? true : false);
$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);

if (!class_exists('class_plugins')) include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
if (empty($class_plugins)) $class_plugins = new class_plugins();
$class_plugins->setup_lang($config['plugins'][$plugin_name]['dir']);

if (!class_exists('class_form')) include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
if (empty($class_form)) $class_form = new class_form();

if (!class_exists('class_db')) include(IP_ROOT_PATH . 'includes/class_db.' . PHP_EXT);
if (empty($class_db)) $class_db = new class_db();

if (!class_exists('class_guestbooks'))
{
	include(GUESTBOOKS_ROOT_PATH . 'includes/class_guestbooks.' . PHP_EXT);
}
$class_guestbooks = new class_guestbooks();
$class_guestbooks->var_init();
$class_guestbooks->guestbooks_list_table = GUESTBOOKS_LIST_TABLE;
$class_guestbooks->guestbooks_posts_table = GUESTBOOKS_POSTS_TABLE;

include(GUESTBOOKS_ROOT_PATH . 'guestbooks_array.' . PHP_EXT);
$class_db->main_db_table = PLUGINS_GUESTBOOKS_DB_TABLE;
$class_db->main_db_item = PLUGINS_GUESTBOOKS_DB_ITEM;

?>