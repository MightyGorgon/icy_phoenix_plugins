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

define('CASH_PLUGIN_PATH', PLUGINS_PATH . $config['plugins']['cash']['dir']);
define('CASH_ROOT_PATH', IP_ROOT_PATH . CASH_PLUGIN_PATH);
//define('CASH_TPL_PATH', 'cash/');
define('CASH_TPL_PATH', '../../' . CASH_PLUGIN_PATH . 'templates/');
define('CASH_ADM_PATH', IP_ROOT_PATH . CASH_PLUGIN_PATH . ADM . '/');
define('CASH_ADM_TPL_PATH', '../../' . CASH_PLUGIN_PATH . ADM . '/templates/');

?>