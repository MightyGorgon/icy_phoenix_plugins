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

// Please note that config name will be prefixed with plugin_
$plugin_details['config'] = 'album';
$plugin_details['name'] = $lang['PLUGIN_ALBUM'];
$plugin_details['description'] = $lang['PLUGIN_ALBUM_EXPLAIN'];
$plugin_details['version'] = '1.0.0';
$plugin_details['constants'] = 1;
$plugin_details['common'] = 0;
$plugin_details['functions'] = 0;
$plugin_details['class'] = 0;

?>