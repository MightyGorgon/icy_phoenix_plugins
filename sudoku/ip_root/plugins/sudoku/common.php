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

define('SUDOKU_PLUGIN_PATH', PLUGINS_PATH . $config['plugins'][$plugin_name]['dir']);
define('SUDOKU_ROOT_PATH', IP_ROOT_PATH . SUDOKU_PLUGIN_PATH);
define('SUDOKU_TPL_PATH', '../../' . SUDOKU_PLUGIN_PATH . 'templates/');
define('SUDOKU_IMG_PATH', SUDOKU_ROOT_PATH . 'images/');
define('SUDOKU_MAIN_FILE', 'sudoku.' . PHP_EXT);

$cms_page['page_id'] = $plugin_name;
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
check_page_auth($cms_page['page_id'], $cms_auth_level);

if (!class_exists('class_plugins')) include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
if (empty($class_plugins)) $class_plugins = new class_plugins();
$class_plugins->setup_lang($config['plugins'][$plugin_name]['dir']);

$sudoku_config = $class_plugins->get_config(basename($config['plugins'][$plugin_name]['dir']));

include(SUDOKU_ROOT_PATH . 'includes/functions_sudoku.' . PHP_EXT);

// SUDOKU IMAGES - BEGIN
$images['sudoku_x'] = SUDOKU_IMG_PATH . 'sudoku_blank.gif';
$images['sudoku_1'] = SUDOKU_IMG_PATH . '1_given.gif';
$images['sudoku_2'] = SUDOKU_IMG_PATH . '2_given.gif';
$images['sudoku_3'] = SUDOKU_IMG_PATH . '3_given.gif';
$images['sudoku_4'] = SUDOKU_IMG_PATH . '4_given.gif';
$images['sudoku_5'] = SUDOKU_IMG_PATH . '5_given.gif';
$images['sudoku_6'] = SUDOKU_IMG_PATH . '6_given.gif';
$images['sudoku_7'] = SUDOKU_IMG_PATH . '7_given.gif';
$images['sudoku_8'] = SUDOKU_IMG_PATH . '8_given.gif';
$images['sudoku_9'] = SUDOKU_IMG_PATH . '9_given.gif';
$images['sudoku_11'] = SUDOKU_IMG_PATH . '11_given.gif';
$images['sudoku_12'] = SUDOKU_IMG_PATH . '12_given.gif';
$images['sudoku_13'] = SUDOKU_IMG_PATH . '13_given.gif';
$images['sudoku_14'] = SUDOKU_IMG_PATH . '14_given.gif';
$images['sudoku_15'] = SUDOKU_IMG_PATH . '15_given.gif';
$images['sudoku_16'] = SUDOKU_IMG_PATH . '16_given.gif';
$images['sudoku_17'] = SUDOKU_IMG_PATH . '17_given.gif';
$images['sudoku_18'] = SUDOKU_IMG_PATH . '18_given.gif';
$images['sudoku_19'] = SUDOKU_IMG_PATH . '19_given.gif';
$images['sudoku_21'] = SUDOKU_IMG_PATH . '1_ran.gif';
$images['sudoku_22'] = SUDOKU_IMG_PATH . '2_ran.gif';
$images['sudoku_23'] = SUDOKU_IMG_PATH . '3_ran.gif';
$images['sudoku_24'] = SUDOKU_IMG_PATH . '4_ran.gif';
$images['sudoku_25'] = SUDOKU_IMG_PATH . '5_ran.gif';
$images['sudoku_26'] = SUDOKU_IMG_PATH . '6_ran.gif';
$images['sudoku_27'] = SUDOKU_IMG_PATH . '7_ran.gif';
$images['sudoku_28'] = SUDOKU_IMG_PATH . '8_ran.gif';
$images['sudoku_29'] = SUDOKU_IMG_PATH . '9_ran.gif';
// SUDOKU IMAGES - END


?>