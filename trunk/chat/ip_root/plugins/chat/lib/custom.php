<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*
* @package AJAX_Chat
* @author Sebastian Tschan
* @copyright (c) Sebastian Tschan
* @license GNU Affero General Public License
* @link https://blueimp.net/ajax/
*
* Icy Phoenix integration :
* Informpro @ http://icyphoenix.com
*/

define('IN_ICYPHOENIX', true);
//if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', AJAX_CHAT_PATH . '../');
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$user->session_begin();
//$auth->acl($user->data);
$user->setup();
// End session management

?>