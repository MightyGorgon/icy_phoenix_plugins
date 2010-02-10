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

if (empty($config['plugins']['activity']['enabled']))
{
	message_die(GENERAL_MESSAGE, 'PLUGIN_DISABLED');
}

define('CMS_PAGE_ACTIVITY', 'activity.' . PHP_EXT);
define('CMS_PAGE_ACTIVITY_GAME', 'activity.' . PHP_EXT);

define('ACTIVITY_PLUGIN_PATH', PLUGINS_PATH . $config['plugins']['activity']['dir']);
define('ACTIVITY_ROOT_PATH', IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH);

define('ACTIVITY_GAMES_PATH', ACTIVITY_ROOT_PATH);
define('ACTIVITY_IMAGES_PATH', ACTIVITY_ROOT_PATH . 'images/');
//define('ACTIVITY_TPL_PATH', 'activity/');
define('ACTIVITY_TPL_PATH', '../../' . ACTIVITY_PLUGIN_PATH . 'templates/');
define('ACTIVITY_ADM_PATH', IP_ROOT_PATH . ACTIVITY_PLUGIN_PATH . ADM . '/');
define('ACTIVITY_ADM_TPL_PATH', '../../' . ACTIVITY_PLUGIN_PATH . ADM . '/templates/');

define('INA_BAN', $table_prefix . 'ina_ban');
define('INA_CATEGORY', $table_prefix . 'ina_categories');
define('INA_CHALLENGE', $table_prefix . 'ina_challenge_tracker');
define('INA_CHALLENGE_USERS', $table_prefix . 'ina_challenge_users');
define('INA_CHEAT', $table_prefix . 'ina_cheat_fix');
define('INA_DISABLE', $table_prefix . 'ina_hidden');
define('INA_GAMBLE', $table_prefix . 'ina_gamble');
define('INA_GAMBLE_GAMES', $table_prefix . 'ina_gamble_in_progress');
define('INA_LAST_GAME', $table_prefix . 'ina_last_game_played');
define('INA_SESSIONS', $table_prefix . 'ina_sessions');
define('INA_TROPHY', $table_prefix . 'ina_top_scores');
define('INA_TROPHY_COMMENTS', $table_prefix . 'ina_trophy_comments');
define('INA_RATINGS', $table_prefix . 'ina_rating_votes');
define('INA_FAVORITES', $table_prefix . 'ina_favorites');
define('INA_HOF', $table_prefix . 'ina_hall_of_fame');
define('INA_CHAT', $table_prefix . 'ina_chat');

if (empty($class_plugins))
{
	include(IP_ROOT_PATH . 'includes/class_plugins.' . PHP_EXT);
	$class_plugins = new class_plugins();
}

include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);

$mem_limit = check_mem_limit();
@ini_set('memory_limit', $mem_limit);

@include_once(ACTIVITY_ROOT_PATH . 'includes/functions_amod_plus.' . PHP_EXT);
@include_once(ACTIVITY_ROOT_PATH . 'includes/functions_amod_plus_char.' . PHP_EXT);

setup_extra_lang(array('lang_activity', 'lang_activity_char'), ACTIVITY_ROOT_PATH . 'language/');

?>