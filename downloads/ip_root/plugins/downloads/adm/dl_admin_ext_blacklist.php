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

if ($cancel)
{
	$action = '';
}

if($action == 'add')
{
	$extention = request_var('extention', '', true);

	if ($extention)
	{
		$sql = "SELECT * FROM " . DL_EXT_BLACKLIST . "
			WHERE extention = '" . $db->sql_escape($extention) . "'";
		$result = $db->sql_query($sql);
		$ext_exist = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if (!$ext_exist)
		{
			$sql = "INSERT INTO " . DL_EXT_BLACKLIST . "
				(extention) VALUES ('" . $db->sql_escape($extention) . "')";
			$db->sql_query($sql);
		}
	}

	$action = '';
}
elseif($action == 'delete')
{
	$extention = (isset($_POST['extention'])) ? $_POST['extention'] : array();

	if (!$confirm)
	{
		$template_to_parse = $class_plugins->get_tpl_file(DL_TPL_PATH, 'dl_confirm_body.tpl');
		$template->set_filenames(array('confirm_body' => $template_to_parse));

		for ($i = 0; $i < sizeof($extention); $i++)
		{
			$s_hidden_fields .= '<input type="hidden" name="extention[]" value="'.htmlspecialchars($extention[$i]).'" />';
		}

		$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => (sizeof($extention) == 1) ? sprintf($lang['Dl_confirm_delete_extention'], $extention[0]) : sprintf($lang['Dl_confirm_delete_extentions'], implode(', ', $extention)),

			'L_DELETE_FILE_TOO' => (sizeof($extention) == 1) ? $lang['Dl_delete_extention_confirm'] : $lang['Dl_delete_extentions_confirm'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=ext_blacklist'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
	}
	else
	{
		$sql_ext_in = '';
		for ($i = 0; $i < sizeof($extention); $i++)
		{
			$sql_ext_in .= ($sql_ext_in != '') ? ", '".htmlspecialchars($extention[$i])."'" : "'".htmlspecialchars($extention[$i])."'";
		}

		if ($sql_ext_in)
		{
			$sql = "DELETE FROM " . DL_EXT_BLACKLIST . "
				WHERE extention IN ($sql_ext_in)";
			$db->sql_query($sql);

			$message = ((sizeof($extention) == 1) ? $lang['Extention_removed'] : $lang['Extentions_removed']) . '<br /><br />' . sprintf($lang['Click_return_extblacklistadmin'], '<a href="' . append_sid('admin_downloads.' . PHP_EXT . '?submod=ext_blacklist') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}

		$action = '';
	}
}

if ($action == '')
{
	$template->set_filenames(array(
		'ext_bl' => DL_ADM_TPL_PATH . 'dl_ext_blacklist_body.tpl')
	);

	$sql = "SELECT extention FROM " . DL_EXT_BLACKLIST . "
		ORDER BY extention";
	$result = $db->sql_query($sql);

	$i = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$extention = $row['extention'];
		$template->assign_block_vars('extention_row', array(
			'ROW_CLASS' => $row_class,
			'EXTENTION' => $extention
			)
		);

		$i++;
	}

	$template->assign_vars(array(
		'L_DL_EXT_BLACKLIST_EXPLAIN' => $lang['Dl_ext_blacklist_explain'],
		'L_DL_EXTENTION' => $lang['Dl_extention'],
		'L_DL_EXTENTIONS' => $lang['Dl_extentions'],
		'L_DL_ADD_EXTENTION' => $lang['Dl_add_extention'],
		'L_DL_DEL_EXTENTIONS' => $lang['Dl_delete'],
		'L_MARK_ALL' => $lang['Mark_all'],
		'L_UNMARK_ALL' => $lang['Unmark_all'],

		'S_DOWNLOADS_ACTION' => append_sid('admin_downloads.' . PHP_EXT . '?submod=ext_blacklist')
		)
	);
}

$template->pparse('ext_bl');

?>