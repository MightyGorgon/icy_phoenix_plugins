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

$help_key = (isset($_GET['help_key'])) ? $_GET['help_key'] : '';

include(IP_ROOT_PATH . DL_PLUGIN_PATH . 'common.' . PHP_EXT);
include(DL_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_dl_help.' . PHP_EXT);

// Pull all user config data
if ($help_key && $lang['HELP_' . $help_key])
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
full_page_generation(DL_TPL_PATH . 'dl_help_body.tpl', $lang['HELP_TITLE'], '', '');

?>