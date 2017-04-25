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
* Xore (mods@xore.ca)
*
*/

define('IN_CASHMOD', true);
define('IN_ICYPHOENIX', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

if (empty($config['plugins']['cash']['enabled']))
{
	message_die(GENERAL_MESSAGE, 'PLUGIN_DISABLED');
}

/*
if ($config['cash_adminnavbar'])
{
	$navbar = 1;
	include('./admin_cash.' . PHP_EXT);
}
*/
$navbar = $config['cash_adminnavbar'];
include('./admin_cash.' . PHP_EXT);

// Start page proper
$template->set_filenames(array('body' => CASH_ADM_TPL_PATH . 'cash_menu.tpl'));

$j = 0;
$help = array();
$help[0] = new cash_menucat($lang['Cmenu_cash_help']);
$help[0]->additem(new cash_menuitem($j, 'Cmh_support', 'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#623226', $lang['Cmhe_support']));
$help[0]->additem(new cash_menuitem($j, 'Cmh_troubleshooting', 'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#625402', $lang['Cmhe_troubleshooting']));
$help[0]->additem(new cash_menuitem($j, 'Cmh_upgrading', 'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#648190', sprintf($lang['Cmhe_upgrading'], $config['cash_version'])));
$help[0]->additem(new cash_menuitem($j, 'Cmh_addons', 'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#655651', $lang['Cmhe_addons']));
$help[0]->additem(new cash_menuitem($j, 'Cmh_demo_boards', 'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#658468', $lang['Cmhe_demo_boards']));
$help[0]->additem(new cash_menuitem($j, 'Cmh_translations', 'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#662158', $lang['Cmhe_translations']));
$help[0]->additem(new cash_menuitem($j, 'Cmh_features', 'http://www.phpbb.com/phpBB/viewtopic.php?p=623226#664549', $lang['Cmhe_features']));

for ($i = 0; $i < sizeof($help); $i++)
{
	$template->assign_block_vars('menucat', array('L_CATEGORY' => $help[$i]->category));
	for ($j = 0; $j < $help[$i]->num(); $j++)
	{
		$template->assign_block_vars('menucat.menuitem', $help[$i]->items[$j]->data('', 1, ' target="cmh"', false));
	}
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>