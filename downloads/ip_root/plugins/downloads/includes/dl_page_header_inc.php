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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if ($user->data['user_new_download'])
{
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_new_download = 0
		WHERE user_id = " . $user->data['user_id'];
	$db->sql_query($sql);

	if ($user->data['user_dl_note_type'])
	{
		$template->assign_block_vars('switch_new_download', array(
			'U_NEW_DOWNLOAD_POPUP' => append_sid('downloads.' . PHP_EXT . '?view=popup')
			)
		);
	}
	else
	{
		$template->assign_block_vars('switch_new_download_message', array(
			'NEW_DOWNLOAD_POPUP' => sprintf($lang['New_download'], '<a href="' . append_sid('downloads.' . PHP_EXT) . '">', '</a>'))
		);
	}
}

$sql = "SELECT id FROM " . DL_CAT_TABLE . " WHERE bug_tracker = 1";
$result = $db->sql_query($sql);
$db->sql_freeresult($result);
if ($bug_tracker)
{
	$template->assign_block_vars('bug_tracker_head', array(
		'L_BUG_TRACKER' => $lang['Dl_bug_tracker'],
		'U_BUG_TRACKER' => append_sid('downloads.' . PHP_EXT . '?view=bug_tracker')
		)
	);
}

?>