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

if (!defined('CASH_PLUGIN_PATH')) define('CASH_PLUGIN_PATH', PLUGINS_PATH . $config['plugins']['cash']['dir']);
if (!defined('CASH_ROOT_PATH')) define('CASH_ROOT_PATH', IP_ROOT_PATH . CASH_PLUGIN_PATH);
//if (!defined('CASH_TPL_PATH')) define('CASH_TPL_PATH', 'cash/');
if (!defined('CASH_TPL_PATH')) define('CASH_TPL_PATH', '../../' . CASH_PLUGIN_PATH . 'templates/');
if (!defined('CASH_ADM_PATH')) define('CASH_ADM_PATH', IP_ROOT_PATH . CASH_PLUGIN_PATH . ADM . '/');
if (!defined('CASH_ADM_TPL_PATH')) define('CASH_ADM_TPL_PATH', '../../' . CASH_PLUGIN_PATH . ADM . '/templates/');

?>