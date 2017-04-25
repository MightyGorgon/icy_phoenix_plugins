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

$plugin_name = 'cash';

if (empty($config['plugins'][$plugin_name]['enabled']))
{
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins'][$plugin_name]['dir'] . 'common.' . PHP_EXT);

if (!defined('CASH_INCLUDE'))
{
	include(CASH_ROOT_PATH . 'functions.' . PHP_EXT);
}

if (!defined('ADMIN_MENU'))
{
	define('ADMIN_MENU', 1);
	if (!function_exists('admin_menu'))
	{
		function admin_menu(&$menu)
		{
			global $lang;
			$i = 0;
			$j = 0;
			$menu[$i] = new cash_menucat($lang['Cmcat_main']);
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Configuration', 'cash_config', $lang['Cmenu_cash_config']));
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Currencies', 'cash_currencies', $lang['Cmenu_cash_currencies']));
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Forums', 'cash_forums', $lang['Cmenu_cash_forums']));
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Settings', 'cash_settings', $lang['Cmenu_cash_settings']));
			/*
			$i++;
			$menu[$i] = new cash_menucat($lang['Cmcat_addons']);
			*/
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Events', 'cash_events', $lang['Cmenu_cash_events']));
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Reset', 'cash_reset', $lang['Cmenu_cash_reset']));
			/*
			$i++;
			$menu[$i] = new cash_menucat($lang['Cmcat_other']);
			*/
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Exchange', 'cash_exchange', $lang['Cmenu_cash_exchange']));
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Groups', 'cash_groups', $lang['Cmenu_cash_groups']));
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Logs', 'cash_log', $lang['Cmenu_cash_log']));
			/*
			$i++;
			$menu[$i] = new cash_menucat($lang['Cmcat_help']);
			*/
			$menu[$i]->additem(new cash_menuitem($j, 'Cash_Help', 'cash_help', $lang['Cmenu_cash_help']));
		}
	}
}

//if (!empty($navbar) && defined('IN_ICYPHOENIX'))
if (isset($navbar) && defined('IN_ICYPHOENIX'))
{
	if (empty($navbar))
	{
		return;
	}
	$menu = array();
	if (!defined('CASH_INCLUDE'))
	{
		message_die(GENERAL_ERROR, 'Cash Plugin is disabled, enable it in ACP &raquo; Plugins');
	}
	admin_menu($menu);

	$template->set_filenames(array('navbar' => CASH_ADM_TPL_PATH . 'cash_navbar.tpl'));

	$class = 0;
	for ($i = 0; $i < sizeof($menu); $i++)
	{
		$template->assign_block_vars('navcat',array(
			'L_CATEGORY' => $menu[$i]->category,
			'WIDTH' => $menu[$i]->num()
			)
		);
		for ($j = 0; $j < $menu[$i]->num(); $j++)
		{
			$template->assign_block_vars('navitem', $menu[$i]->items[$j]->data($class + 1, ''));
			$class = ($class + 1) % 2;
		}
	}
	$template->assign_var_from_handle('NAVBAR', 'navbar');
	return;
}

if (!empty($setmodules) && defined('IN_ICYPHOENIX'))
{
	if (empty($table_prefix))
	{
		// jr admin mod
		// Since this gets included within a function, and we require these base-scope variables, we copy them in from the global scope
		global $table_prefix, $config, $lang;
		/*
		$table_prefix = $GLOBALS['table_prefix'];
		$config = $GLOBALS['board_config'];
		$lang = $GLOBALS['lang'];
		*/
	}

	$menu = array();
	admin_menu($menu);

	if ($config['cash_adminbig'])
	{
		for ($i = 0; $i < sizeof($menu); $i++)
		{
			for ($j = 0; $j < $menu[$i]->num(); $j++)
			{
				$module['3100_CASH'][$menu[$i]->items[$j]->title] = CASH_ADM_PATH . $menu[$i]->items[$j]->linkage();
				if (($j == $menu[$i]->num() - 1) && !($i == sizeof($menu) - 1))
				{
					$lang[$menu[$i]->items[$j]->title] = $lang[$menu[$i]->items[$j]->title] . '</a></span></td></tr><tr><td class="row2" height="7"><span class="genmed"><a id="cm' . $menu[$i]->num() . '">';
				}
			}
		}
	}
	else
	{
		$file = basename(__FILE__);
		//$module['3100_CASH']['110_Cash_Admin'] = CASH_ADM_PATH . $file;
		$module['3100_CASH']['110_Cash_Admin'] = CASH_ADM_PATH . 'cash_main.' . PHP_EXT;;
		$module['3100_CASH']['120_Cash_Help'] = CASH_ADM_PATH . 'cash_help.' . PHP_EXT;
	}
	return;
}

define('IN_ICYPHOENIX', true);
define('IN_CASHMOD', true);

// Load default Header
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

if (empty($config['plugins']['cash']['enabled']))
{
	message_die(GENERAL_ERROR, 'Cash Plugin is disabled, enable it in ACP &raquo; Plugins');
}

/*
if ($config['cash_adminnavbar'])
{
	$navbar = 1;
	include(CASH_ADM_PATH . 'admin_cash.' . PHP_EXT);
}

//$menu = array();
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
		$template->assign_block_vars('menucat.menuitem', $menu[$i]->items[$j]->data(1, ''));
	}
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
*/

?>