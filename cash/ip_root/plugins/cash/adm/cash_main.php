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

admin_menu($menu);

$template->set_filenames(array('body' => CASH_ADM_TPL_PATH . 'cash_menu.tpl'));

for ($i = 0; $i < sizeof($menu); $i++)
{
	$template->assign_block_vars('menucat',array(
		'L_CATEGORY' => $menu[$i]->category
		)
	);
	for ($j = 0; $j < $menu[$i]->num(); $j++)
	{
		$template->assign_block_vars('menucat.menuitem', $menu[$i]->items[$j]->data(PHP_EXT, 1, ''));
	}
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>