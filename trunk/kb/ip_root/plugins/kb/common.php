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

define('KB_ROOT_PATH', IP_ROOT_PATH . KB_PLUGIN_PATH);

include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

include(KB_ROOT_PATH . 'includes/kb_constants.' . PHP_EXT);
include(KB_ROOT_PATH . 'includes/functions_kb.' . PHP_EXT);
include(KB_ROOT_PATH . 'includes/functions_kb_auth.' . PHP_EXT);
include(KB_ROOT_PATH . 'includes/functions_kb_field.' . PHP_EXT);
include(KB_ROOT_PATH . 'includes/functions_kb_mx.' . PHP_EXT);

// Pull all config data
$sql = "SELECT * FROM " . KB_CONFIG_TABLE;
$result = $db->sql_query($sql);
while ($kb_config_row = $db->sql_fetchrow($result))
{
	$config_name = $kb_config_row['config_name'];
	$config_value = $kb_config_row['config_value'];
	$kb_config[$config_name] = $config_value;
}

setup_extra_lang(array('lang_kb'), KB_ROOT_PATH . 'language/');

$kb_module_version = 'Knowledge Base v. 2.0.x';
$kb_module_author = 'Haplo';
$kb_module_orig_author = 'wGEric';

// BBCodes
$bbcode_on = $kb_config['allow_bbcode'] ? 1 : 0;
$html_on = $kb_config['allow_html'] ? 1 : 0;
$smilies_on = $kb_config['allow_smilies'] ? 1 : 0;
$is_admin = (($userdata['user_level'] == ADMIN) && $userdata['session_logged_in']) ? true : 0;

?>