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

$plugin_name = empty($plugin_name) ? 'feedback' : $plugin_name;
$cms_page['page_id'] = (!empty($cms_page['page_id']) ? $cms_page['page_id'] : $plugin_name);
$cms_page['page_nav'] = (!isset($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false));
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

define('CMS_PAGE_FEEDBACK', 'feedback.' . PHP_EXT);

define('FEEDBACK_PLUGIN_PATH', PLUGINS_PATH . $config['plugins'][$plugin_name]['dir']);
define('FEEDBACK_ROOT_PATH', IP_ROOT_PATH . FEEDBACK_PLUGIN_PATH);
//define('FEEDBACK_TPL_PATH', 'feedback/');
define('FEEDBACK_TPL_PATH', '../../' . FEEDBACK_PLUGIN_PATH . 'templates/');
define('FEEDBACK_ADM_PATH', IP_ROOT_PATH . FEEDBACK_PLUGIN_PATH . ADM . '/');
define('FEEDBACK_ADM_TPL_PATH', '../../' . FEEDBACK_PLUGIN_PATH . ADM . '/templates/');

if (!class_exists('class_plugins')) include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
if (empty($class_plugins)) $class_plugins = new class_plugins();
$class_plugins->setup_lang($config['plugins'][$plugin_name]['dir']);

if (!class_exists('class_form')) include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
if (empty($class_form)) $class_form = new class_form();

include_once(FEEDBACK_ROOT_PATH . 'includes/functions_feedback.' . PHP_EXT);

?>