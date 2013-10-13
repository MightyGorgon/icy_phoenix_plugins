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

define('DL_PLUGIN_PATH', PLUGINS_PATH . $config['plugins']['downloads']['dir']);
define('DL_ROOT_PATH', IP_ROOT_PATH . DL_PLUGIN_PATH);
//define('DL_TPL_PATH', 'downloads/');
define('DL_TPL_PATH', '../../' . DL_PLUGIN_PATH . 'templates/');
define('DL_ADM_PATH', IP_ROOT_PATH . DL_PLUGIN_PATH . ADM . '/');
define('DL_ADM_TPL_PATH', '../../' . DL_PLUGIN_PATH . ADM . '/templates/');

if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
if (empty($bbcode)) $bbcode = new bbcode();

if (!class_exists('class_plugins')) include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
if (empty($class_plugins)) $class_plugins = new class_plugins();

include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

setup_extra_lang(array('lang_downloads'), DL_ROOT_PATH . 'language/');

?>