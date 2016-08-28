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

//$plugin_name = empty($plugin_name) ? 'blogs' : $plugin_name;
$plugin_name = 'blogs';
$cms_page['page_id'] = (!empty($cms_page['page_id']) ? $cms_page['page_id'] : $plugin_name);
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

define('CMS_PAGE_BLOG', 'blog.' . PHP_EXT);
define('CMS_PAGE_BLOGS', 'blogs.' . PHP_EXT);

define('BLOGS_LIST_TABLE', $table_prefix . 'blogs');
define('BLOGS_TOPICS_TABLE', $table_prefix . 'blogs_topics');
define('BLOGS_POSTS_TABLE', $table_prefix . 'blogs_posts');

define('BLOGS_PLUGIN_PATH', PLUGINS_PATH . $config['plugins'][$plugin_name]['dir']);
define('BLOGS_ROOT_PATH', IP_ROOT_PATH . BLOGS_PLUGIN_PATH);
//define('BLOGS_TPL_PATH', 'blogs/');
define('BLOGS_TPL_PATH', '../../' . BLOGS_PLUGIN_PATH . 'templates/');
define('BLOGS_ADM_PATH', IP_ROOT_PATH . BLOGS_PLUGIN_PATH . ADM . '/');
define('BLOGS_ADM_TPL_PATH', '../../' . BLOGS_PLUGIN_PATH . ADM . '/templates/');

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

include(BLOGS_ROOT_PATH . 'includes/class_blogs.' . PHP_EXT);
$class_blogs = new class_blogs();
$class_blogs->var_init();
$class_blogs->blogs_list_table = BLOGS_LIST_TABLE;
$class_blogs->blogs_topics_table = BLOGS_TOPICS_TABLE;
$class_blogs->blogs_posts_table = BLOGS_POSTS_TABLE;

include(BLOGS_ROOT_PATH . 'blogs_array.' . PHP_EXT);
$class_db->main_db_table = PLUGINS_BLOGS_DB_TABLE;
$class_db->main_db_item = PLUGINS_BLOGS_DB_ITEM;

?>