<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

$plugin_name = 'guestbooks';

if(!empty($setmodules))
{
	if (empty($config['plugins'][$plugin_name]['enabled']))
	{
		return;
	}

	setup_extra_lang(array('lang_plugin'), IP_ROOT_PATH . PLUGINS_PATH . $config['plugins'][$plugin_name]['dir'] . 'language/');
	$acp_file = IP_ROOT_PATH . PLUGINS_PATH . $config['plugins'][$plugin_name]['dir'] . ADM . '/' . basename(__FILE__);
	$module['9000_GUESTBOOKS']['9110_GUESTBOOKS_CONFIG'] = $acp_file;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);

include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

// SETTINGS - BEGIN
$is_plugin = true;
$settings_basename = $plugin_name;
$acp_file = IP_ROOT_PATH . PLUGINS_PATH . $config['plugins'][$plugin_name]['dir'] . ADM . '/' . basename(__FILE__);
// SETTINGS - END

include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
$class_plugins = new class_plugins();
$class_plugins->setup_lang($config['plugins'][$plugin_name]['dir']);

$plugin_config = $class_plugins->get_plugin_config($plugin_name . '_', false);
$class_plugins->setup_plugin($config['plugins'][$plugin_name]['dir']);

// OTHERS SETTINGS - BEGIN
$acp_module_title = $lang['ACP_GUESTBOOKS'];
$acp_module_title_explain = $lang['ACP_GUESTBOOKS_EXPLAIN'];
$acp_modules = $class_plugins->modules;
$acp_default_config = $plugin_config;
// OTHERS SETTINGS - END

include(IP_ROOT_PATH . ADM . '/acp_config_include.' . PHP_EXT);

// footer
$template->pparse('body');
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>