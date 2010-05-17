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
* aUsTiN-Inc 2003/5 (austin@phpbb-amod.com) - (http://phpbb-amod.com)
*
*/

define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['activity']['dir'] . 'common.' . PHP_EXT);

/* Start Version Check */
VersionCheck();
/* End Version Check */

$v3_session = (isset($_POST['sessdo'])) ? $_POST['sessdo'] : '';
$error_path = 'activity.' . PHP_EXT . '?sid=' . $userdata['session_id'];

if ($v3_session != '')
{
	$game_name = (isset($_POST['gamename'])) ? addslashes(stripslashes($_POST['gamename'])) : '';
	$micro_one = (isset($_POST['microone'])) ? $_POST['microone'] : '';
	$score = (isset($_POST['score'])) ? $_POST['score'] : '';
	$fake_key = (isset($_POST['fakekey'])) ? $_POST['fakekey'] : '';

	switch($v3_session)
	{
		case 'sessionstart':
			echo '&connStatus=1&initbar=' . $game_name . '&val=x';
			exit();
		break;

		case 'permrequest':
			echo '&validate=1&microone=' . $score . '|' . $fake_key . '&val=x';
			exit();
		break;

		case 'burn':
			$data = explode('|', $micro_one);
			$game = trim(addslashes(stripslashes($data[1])));
			$score = $data[0];

		echo '<form method="post" name="v3arcade" action="' . append_sid('newscore.' . PHP_EXT . '">';
		echo '<input type="hidden" name="score" value="' . $score . '" />';
		echo '<input type="hidden" name="game_name" value="' . $game . '" />';
		echo '</form>';
		echo '<script type="text/javascript">';
		echo 'window.onload = function(){document.v3arcade.submit()}';
		echo '</script>';
		exit();
	}
}
else
{
	header('Location: ' . $error_path);
}
?>