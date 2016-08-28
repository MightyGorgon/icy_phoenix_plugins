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

//$plugin_name = empty($plugin_name) ? 'donations' : $plugin_name;
$plugin_name = 'donations';
$cms_page['page_id'] = (!empty($cms_page['page_id']) ? $cms_page['page_id'] : $plugin_name);
$cms_page['page_nav'] = (!isset($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false));
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

define('CMS_PAGE_DONATE', 'donate.' . PHP_EXT);
define('CMS_PAGE_DONATIONS', 'donations.' . PHP_EXT);

define('DONATIONS_PLUGIN_PATH', PLUGINS_PATH . $config['plugins'][$plugin_name]['dir']);
define('DONATIONS_ROOT_PATH', IP_ROOT_PATH . DONATIONS_PLUGIN_PATH);
//define('DONATIONS_TPL_PATH', 'donations/');
define('DONATIONS_TPL_PATH', '../../' . DONATIONS_PLUGIN_PATH . 'templates/');
define('DONATIONS_ADM_PATH', IP_ROOT_PATH . DONATIONS_PLUGIN_PATH . ADM . '/');
define('DONATIONS_ADM_TPL_PATH', '../../' . DONATIONS_PLUGIN_PATH . ADM . '/templates/');

if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
if (empty($bbcode)) $bbcode = new bbcode();
$bbcode->allow_html = ($config['allow_html'] ? true : false);
$bbcode->allow_bbcode = ($config['allow_bbcode'] ? true : false);
$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);

if (!class_exists('class_plugins')) include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
if (empty($class_plugins)) $class_plugins = new class_plugins();
$class_plugins->setup_lang($config['plugins'][$plugin_name]['dir']);

$plugin_config = $class_plugins->get_plugin_config($plugin_name . '_', false);

if (!class_exists('class_form')) include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
if (empty($class_form)) $class_form = new class_form();

if (!class_exists('class_db')) include(IP_ROOT_PATH . 'includes/class_db.' . PHP_EXT);
if (empty($class_db)) $class_db = new class_db();

?>