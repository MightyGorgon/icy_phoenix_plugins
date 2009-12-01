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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

if(!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['script_path']));
$script_name = ($script_name == '') ? $script_name : '/' . $script_name;

define('PORTAL_URL', $server_protocol . $server_name . $server_port . $script_name . '/');
define('PHPBB_URL', PORTAL_URL);

$reader_mode = false;
$kb_config['news_operate_mode'] = false;
$is_block = false;

define('KB_TPL_PATH', 'kb/');
define('KB_ADM_PATH', IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['kb']['dir'] . ADM . '/');
define('KB_ADM_TPL_PATH', '../../' . PLUGINS_PATH . $config['plugins']['kb']['dir'] . ADM . '/templates/');

// ---------------------------------------------------------------------START
// This file defines specific constants for the module
// -------------------------------------------------------------------------
if (!defined('KB_ARTICLES_TABLE'))
{
	define('KB_ARTICLES_TABLE', $table_prefix . 'kb_articles');
}
define('KB_CATEGORIES_TABLE', $table_prefix . 'kb_categories');
define('KB_CONFIG_TABLE', $table_prefix . 'kb_config');
define('KB_TYPES_TABLE', $table_prefix . 'kb_types');
define('KB_WORD_TABLE', $table_prefix . 'kb_wordlist');
define('KB_SEARCH_TABLE', $table_prefix . 'kb_results');
define('KB_MATCH_TABLE', $table_prefix . 'kb_wordmatch');
define('KB_VOTES_TABLE', $table_prefix . 'kb_votes');

define('KB_CUSTOM_TABLE', $table_prefix . 'kb_custom');
define('KB_CUSTOM_DATA_TABLE', $table_prefix . 'kb_customdata');

// Field Types
define('INPUT', 0);
define('TEXTAREA', 1);
define('RADIO', 2);
define('SELECT', 3);
define('SELECT_MULTIPLE', 4);
define('CHECKBOX', 5);

?>