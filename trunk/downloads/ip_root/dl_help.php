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

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

$userdata = session_pagestart($user_ip);
init_userprefs($userdata);

$help_key = request_var('help_key', '');

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['downloads']['dir'] . 'common.' . PHP_EXT);
setup_extra_lang(array('lang_dl_help'), DL_ROOT_PATH . 'language/');

// Pull all user config data
if (!empty($help_key) && isset($lang['HELP_' . $help_key]))
{
	$help_string = $lang['HELP_' . $help_key];
}
else
{
	$help_string = $lang['Dl_no_help_aviable'];
}

$template->assign_vars(array(
	'L_CLOSE' => $lang['Close_window'],
	'HELP_TITLE' => $lang['HELP_TITLE'],
	'HELP_OPTION' => $lang[$help_key],
	'HELP_STRING' => $help_string
	)
);

$gen_simple_header = true;
$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_help_body.tpl');
full_page_generation($template_to_parse, $lang['HELP_TITLE'], '', '');

?>