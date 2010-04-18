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

$plugin_details = array();

$lang_file = dirname(__FILE__) . '/language/lang_' . $config['default_lang'] . '/lang_info.' . PHP_EXT;
if (!file_exists($lang_file))
{
	$lang_file = dirname(__FILE__) . '/language/lang_english/lang_info.' . PHP_EXT;
}
@include($lang_file);

// Please note that config name will be prefixed with plugin_
$plugin_details['config'] = 'activity';
$plugin_details['name'] = $lang['PLUGIN_ACTIVITY'];
$plugin_details['description'] = $lang['PLUGIN_ACTIVITY_EXPLAIN'];
$plugin_details['version'] = '1.0.0';

?>