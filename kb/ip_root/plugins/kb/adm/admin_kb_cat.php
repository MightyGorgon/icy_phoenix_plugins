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

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	if (empty($config['plugins']['kb']['enabled']))
	{
		return;
	}

	$file = IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['kb']['dir'] . ADM . '/' . basename(__FILE__);
	$module['1800_KB_title']['120_Cat_man'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../../../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require(IP_ROOT_PATH . 'adm/pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['kb']['dir'] . 'common.' . PHP_EXT);

if (!function_exists('get_forums'))
{
	function get_forums($sel_id = 0)
	{
		$forumlist = '<select name="forum_id">';
		if ($sel_id == 0)
		{
			$forumlist .= '<option value="0" selected >Select a Forum!</option>';
		}
		
		$forumlist .= get_tree_option($sel_id);
		
		$forumlist .= '</select>';
		return $forumlist;
	}
}

$mode = request_var('mode', '');
if (empty($mode))
{
	$mode = '';
	if ($create)
	{
		$mode = 'create';
	}
	else if ($edit)
	{
		$mode = 'edit';
	}
	else if ($delete)
	{
		$mode = 'delete';
	}
}

switch ($mode)
{
	case 'create':

		if (!$_POST['submit'])
		{
			$new_cat_name = request_var('new_cat_name', '', true);

			// Generate page

			$template->set_filenames(array('body' => KB_ADM_TPL_PATH . 'kb_cat_edit_body.tpl'));

			$template->assign_block_vars('switch_cat', array());

			$template->assign_vars(array('L_EDIT_TITLE' => $lang['Create_cat'],
				'L_EDIT_DESCRIPTION' => $lang['Create_description'],
				'L_CATEGORY' => $lang['Category'],
				'L_DESCRIPTION' => $lang['Article_description'],
				'L_NUMBER_ARTICLES' => $lang['Articles'],
				'L_CAT_SETTINGS' => $lang['Cat_settings'],
				'L_CREATE' => $lang['Create'],
				'L_PARENT' => $lang['Parent'],
				'L_NONE' => $lang['None'],

				'PARENT_LIST' => get_kb_cat_list('', 0, 0, 0, 0, true),

				'L_FORUM_ID' => $lang['Forum_id'],
				'L_FORUM_ID_EXPLAIN' => $lang['Forum_id_explain'],
				'FORUM_LIST' => get_forums(),

				'S_ACTION' => append_sid('admin_kb_cat.' . PHP_EXT . '?mode=create'),
				'CAT_NAME' => $new_cat_name,
				'DESC' => '',
				'NUMBER_ARTICLES' => '0',

				// Category permissions
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],
				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],
				'L_DELETE_LEVEL' => $lang['Delete_level'],
				'L_APPROVAL_LEVEL' => $lang['Approval_level'],
				'L_APPROVAL_EDIT_LEVEL' => $lang['Approval_edit_level'],
				'L_GUEST' => $lang['Forum_ALL'],
				'L_REG' => $lang['Forum_REG'],
				'L_PRIVATE' => $lang['Forum_PRIVATE'],
				'L_MOD' => $lang['Forum_MOD'],
				'L_ADMIN' => $lang['Forum_ADMIN'],

				'L_DISABLED' => $lang['Disabled'],
				'VIEW_GUEST' => 'selected="selected"',
				'UPLOAD_REG' => 'selected="selected"',
				'RATE_REG' => 'selected="selected"',
				'COMMENT_REG' => 'selected="selected"',
				'EDIT_REG' => 'selected="selected"',
				'DELETE_MOD' => 'selected="selected"',
				'APPROVAL_DISABLED' => 'selected="selected"',

				'S_GUEST' => AUTH_ALL,
				'S_USER' => AUTH_REG,
				'S_PRIVATE' => AUTH_ACL,
				'S_MOD' => AUTH_MOD,
				'S_ADMIN' => AUTH_ADMIN
				)
			);
		}
		elseif ($_POST['submit'])
		{
			$cat_name = request_var('catname', '', true);

			if (!$cat_name)
			{
				echo "Please put a category name in!";
			}

			$cat_desc = request_var('catdesc', '', true);
			$parent = request_var('parent', 0);
			$comments_forum_id = request_var('forum_id', 0);

			if ($comments_forum_id == 0)
			{
				mx_message_die(GENERAL_MESSAGE , 'Select a Forum');
			}

			$view_level = request_var('auth_view', 0);
			$post_level = request_var('auth_post', 0);
			$rate_level = request_var('auth_rate', 0);
			$comment_level = request_var('auth_comment', 0);
			$edit_level = request_var('auth_edit', 0);
			$delete_level = request_var('auth_delete', 0);
			$approval_level = request_var('auth_approval', 0);
			$approval_edit_level = request_var('auth_approval_edit', 0);

			$sql = "SELECT MAX(cat_order) AS cat_order
			FROM " . KB_CATEGORIES_TABLE . " WHERE parent = $parent";
			$result = $db->sql_query($sql);

			if (!($id = $db->sql_fetchrow($result)))
			{
				mx_message_die(GENERAL_ERROR, 'Could not obtain next type id', '', __LINE__, __FILE__, $sql);
			}
			$cat_order = $id['cat_order'] + 10;

			$sql = "INSERT INTO " . KB_CATEGORIES_TABLE . " (category_name, category_details, number_articles, parent, cat_order, auth_view, auth_post, auth_rate, auth_comment, auth_edit, auth_delete, auth_approval, auth_approval_edit, comments_forum_id)" . " VALUES ('" . $db->sql_escape($cat_name) . "', '" . $db->sql_escape($cat_desc) . "', '0', '$parent', '$cat_order', '$view_level', '$post_level', '$rate_level', '$comment_level', '$edit_level', '$delete_level', '$approval_level', '$approval_edit_level', '$comments_forum_id')";
			$result = $db->sql_query($sql);

			$message = $lang['Cat_created'] . '<br /><br />' . sprintf($lang['Click_return_cat_manager'], '<a href="' . append_sid('admin_kb_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		break;

	case 'edit':

		if (!$_POST['submit'])
		{
			$cat_id = request_var('cat', 0);

			$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = " . $cat_id;
			$result = $db->sql_query($sql);

			if ($kb_cat = $db->sql_fetchrow($result))
			{
				$cat_name = $kb_cat['category_name'];
				$cat_desc = $kb_cat['category_details'];
				$number_articles = $kb_cat['number_articles'];
				$parent = $kb_cat['parent'];
				$comments_forum_id = $kb_cat['comments_forum_id'];
			}

			// Generate page
			$template->set_filenames(array('body' => KB_ADM_TPL_PATH . 'kb_cat_edit_body.tpl'));

			$template->assign_block_vars('switch_cat', array());
			$template->assign_block_vars('switch_cat.switch_edit_category', array());

			$template->assign_vars(array(
				'L_EDIT_TITLE' => $lang['Edit_cat'],
				'L_EDIT_DESCRIPTION' => $lang['Edit_description'],
				'L_CATEGORY' => $lang['Category'],
				'L_DESCRIPTION' => $lang['Article_description'],
				'L_NUMBER_ARTICLES' => $lang['Articles'],
				'L_CAT_SETTINGS' => $lang['Cat_settings'],
				'L_CREATE' => $lang['Edit'],

				'L_PARENT' => $lang['Parent'],
				'L_NONE' => $lang['None'],

				'PARENT_LIST' => get_kb_cat_list('', $parent, $parent, true, 0, true),

				'S_ACTION' => append_sid('admin_kb_cat.' . PHP_EXT . '?mode=edit'),
				'CAT_NAME' => $cat_name,
				'CAT_DESCRIPTION' => $cat_desc,
				'NUMBER_ARTICLES' => $number_articles,

				'L_FORUM_ID' => $lang['Forum_id'],
				'L_FORUM_ID_EXPLAIN' => $lang['Forum_id_explain'],
				'FORUM_LIST' => get_forums($comments_forum_id),

				// Cat permissions
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],
				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],
				'L_DELETE_LEVEL' => $lang['Delete_level'],
				'L_APPROVAL_LEVEL' => $lang['Approval_level'],
				'L_APPROVAL_EDIT_LEVEL' => $lang['Approval_edit_level'],
				'L_GUEST' => $lang['Forum_ALL'],
				'L_REG' => $lang['Forum_REG'],
				'L_PRIVATE' => $lang['Forum_PRIVATE'],
				'L_MOD' => $lang['Forum_MOD'],
				'L_ADMIN' => $lang['Forum_ADMIN'],

				'L_DISABLED' => $lang['Disabled'],

				'VIEW_GUEST' => ($kb_cat['auth_view'] == AUTH_ALL) ? 'selected="selected"' : '',
				'VIEW_REG' => ($kb_cat['auth_view'] == AUTH_REG) ? 'selected="selected"' : '',
				'VIEW_PRIVATE' => ($kb_cat['auth_view'] == AUTH_ACL) ? 'selected="selected"' : '',
				'VIEW_MOD' => ($kb_cat['auth_view'] == AUTH_MOD) ? 'selected="selected"' : '',
				'VIEW_ADMIN' => ($kb_cat['auth_view'] == AUTH_ADMIN) ? 'selected="selected"' : '',

				'UPLOAD_GUEST' => ($kb_cat['auth_post'] == AUTH_ALL) ? 'selected="selected"' : '',
				'UPLOAD_REG' => ($kb_cat['auth_post'] == AUTH_REG) ? 'selected="selected"' : '',
				'UPLOAD_PRIVATE' => ($kb_cat['auth_post'] == AUTH_ACL) ? 'selected="selected"' : '',
				'UPLOAD_MOD' => ($kb_cat['auth_post'] == AUTH_MOD) ? 'selected="selected"' : '',
				'UPLOAD_ADMIN' => ($kb_cat['auth_post'] == AUTH_ADMIN) ? 'selected="selected"' : '',

				'RATE_GUEST' => ($kb_cat['auth_rate'] == AUTH_ALL) ? 'selected="selected"' : '',
				'RATE_REG' => ($kb_cat['auth_rate'] == AUTH_REG) ? 'selected="selected"' : '',
				'RATE_PRIVATE' => ($kb_cat['auth_rate'] == AUTH_ACL) ? 'selected="selected"' : '',
				'RATE_MOD' => ($kb_cat['auth_rate'] == AUTH_MOD) ? 'selected="selected"' : '',
				'RATE_ADMIN' => ($kb_cat['auth_rate'] == AUTH_ADMIN) ? 'selected="selected"' : '',

				'COMMENT_GUEST' => ($kb_cat['auth_comment'] == AUTH_ALL) ? 'selected="selected"' : '',
				'COMMENT_REG' => ($kb_cat['auth_comment'] == AUTH_REG) ? 'selected="selected"' : '',
				'COMMENT_PRIVATE' => ($kb_cat['auth_comment'] == AUTH_ACL) ? 'selected="selected"' : '',
				'COMMENT_MOD' => ($kb_cat['auth_comment'] == AUTH_MOD) ? 'selected="selected"' : '',
				'COMMENT_ADMIN' => ($kb_cat['auth_comment'] == AUTH_ADMIN) ? 'selected="selected"' : '',

				'EDIT_REG' => ($kb_cat['auth_edit'] == AUTH_REG) ? 'selected="selected"' : '',
				'EDIT_PRIVATE' => ($kb_cat['auth_edit'] == AUTH_ACL) ? 'selected="selected"' : '',
				'EDIT_MOD' => ($kb_cat['auth_edit'] == AUTH_MOD) ? 'selected="selected"' : '',
				'EDIT_ADMIN' => ($kb_cat['auth_edit'] == AUTH_ADMIN) ? 'selected="selected"' : '',

				'DELETE_REG' => ($kb_cat['auth_delete'] == AUTH_REG) ? 'selected="selected"' : '',
				'DELETE_PRIVATE' => ($kb_cat['auth_delete'] == AUTH_ACL) ? 'selected="selected"' : '',
				'DELETE_MOD' => ($kb_cat['auth_delete'] == AUTH_MOD) ? 'selected="selected"' : '',
				'DELETE_ADMIN' => ($kb_cat['auth_delete'] == AUTH_ADMIN) ? 'selected="selected"' : '',

				'APPROVAL_DISABLED' => ($kb_cat['auth_approval'] == AUTH_ALL) ? 'selected="selected"' : '',
				'APPROVAL_MOD' => ($kb_cat['auth_approval'] == AUTH_MOD) ? 'selected="selected"' : '',
				'APPROVAL_ADMIN' => ($kb_cat['auth_approval'] == AUTH_ADMIN) ? 'selected="selected"' : '',

				'APPROVAL_EDIT_DISABLED' => ($kb_cat['auth_approval_edit'] == AUTH_ALL) ? 'selected="selected"' : '',
				'APPROVAL_EDIT_MOD' => ($kb_cat['auth_approval_edit'] == AUTH_MOD) ? 'selected="selected"' : '',
				'APPROVAL_EDIT_ADMIN' => ($kb_cat['auth_approval_edit'] == AUTH_ADMIN) ? 'selected="selected"' : '',

				'S_GUEST' => AUTH_ALL,
				'S_USER' => AUTH_REG,
				'S_PRIVATE' => AUTH_ACL,
				'S_MOD' => AUTH_MOD,
				'S_ADMIN' => AUTH_ADMIN,


				'S_HIDDEN' => '<input type="hidden" name="catid" value="' . $cat_id . '">'
				)
			);
		}
		elseif ($_POST['submit'])
		{
			$cat_id = request_var('catid', 0);
			$cat_name = request_var('catname', '', true);
			$cat_desc = request_var('catdesc', '', true);
			$number_articles = request_var('number_articles', 0);
			$parent = request_var('parent', 0);
			$comments_forum_id = request_var('forum_id', 0);

			$view_level = request_var('auth_view', 0);
			$post_level = request_var('auth_post', 0);
			$rate_level = request_var('auth_rate', 0);
			$comment_level = request_var('auth_comment', 0);
			$edit_level = request_var('auth_edit', 0);
			$delete_level = request_var('auth_delete', 0);
			$approval_level = request_var('auth_approval', 0);
			$approval_edit_level = request_var('auth_approval_edit', 0);

			if (!$cat_name)
			{
				echo "Please put a category name in!";
			}

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET category_name = '" . $db->sql_escape($cat_name) . "', category_details = '" . $db->sql_escape($cat_desc) . "', number_articles = '" . $number_articles . "', parent = '" . $parent . "', auth_view = '" . $view_level . "', auth_post = '" . $post_level . "', auth_rate = '" . $rate_level . "', auth_comment = '" . $comment_level . "', auth_edit = '" . $edit_level . "', auth_delete = '" . $delete_level . "', auth_approval = '" . $approval_level . "', auth_approval_edit = '" . $approval_edit_level . "', comments_forum_id = '" . $comments_forum_id . "' WHERE category_id = " . $cat_id;
			$result = $db->sql_query($sql);

			$message = $lang['Cat_edited'] . '<br /><br />' . sprintf($lang['Click_return_cat_manager'], '<a href="' . append_sid('admin_kb_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			mx_message_die(GENERAL_MESSAGE, $message);
		}
		break;

	case 'delete':

		if (!$_POST['submit'])
		{
			$cat_id = request_var('cat', 0);

			$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '" . $cat_id . "'";
			$cat_result = $db->sql_query($sql);

			if ($category = $db->sql_fetchrow($cat_result))
			{
				$cat_name = $category['category_name'];
			}

			// Generate page

			$template->set_filenames(array('body' => KB_ADM_TPL_PATH . 'kb_cat_del_body.tpl'));

			$template->assign_vars(array('L_DELETE_TITLE' => $lang['Cat_delete_title'],
				'L_DELETE_DESCRIPTION' => $lang['Cat_delete_desc'],
				'L_CAT_DELETE' => $lang['Cat_delete_title'],
				'L_DELETE_ARTICLES' => $lang['Delete_all_articles'],

				'L_CAT_NAME' => $lang['Article_category'],
				'L_MOVE_CONTENTS' => $lang['Move_contents'],
				'L_DELETE' => $lang['Move_and_Delete'],

				'S_HIDDEN_FIELDS' => '<input type="hidden" name="catid" value="' . $cat_id . '">',
				'S_SELECT_TO' => get_kb_cat_list('', $cat_id, 0, true, 0, true),
				'S_ACTION' => append_sid('admin_kb_cat.' . PHP_EXT . '?mode=delete'),

				'CAT_NAME' => $cat_name
				)
			);
		}
		elseif ($_POST['submit'])
		{
			$new_category = request_var('move_id', 0);
			$old_category = request_var('catid', 0);

			if ($new_category != '0')
			{
				$sql = "UPDATE " . KB_ARTICLES_TABLE . "
					SET article_category_id = '$new_category'
					WHERE article_category_id = '$old_category'";
				$move_result = $db->sql_query($sql);

				$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '$new_category'";
				$cat_result = $db->sql_query($sql);

				if ($new_cat = $db->sql_fetchrow($cat_result))
				{
					$new_articles = $new_cat['number_articles'];
				}

				$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '$old_category'";
				$oldcat_result = $db->sql_query($sql);
				if ($old_cat = $db->sql_fetchrow($oldcat_result))
				{
					$old_articles = $old_cat['number_articles'];
				}

				$number_articles = $new_articles + $old_articles;

				$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET number_articles = '" . $number_articles . "' WHERE category_id = " . $new_category;
				$number_result = $db->sql_query($sql);
			}
			else
			{
				$sql = "DELETE FROM " . KB_ARTICLES_TABLE . "
					WHERE article_category_id = " . $old_category;
				$delete__articles = $db->sql_query($sql);
			}

			$sql = "DELETE FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = $old_category";
			$delete_result = $db->sql_query($sql);

			$message = $lang['Cat_deleted'] . '<br /><br />' . sprintf($lang['Click_return_cat_manager'], '<a href="' . append_sid('admin_kb_cat.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			mx_message_die(GENERAL_MESSAGE, $message);
		}
		break;

	default:

		if ($mode == 'up')
		{
			$cat_id = request_var('cat', 0);

			$sql = "SELECT *
							FROM " . KB_CATEGORIES_TABLE . "
							WHERE category_id = $cat_id";
			$result = $db->sql_query($sql);

			if ($category = $db->sql_fetchrow($result))
			{
				$parent = $category['parent'];
				$old_pos = $category['cat_order'];
				$new_pos = $old_pos-10;
			}

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET cat_order = '" . $old_pos . "'
							WHERE parent = " . $parent . " AND cat_order = " . $new_pos;
			$result = $db->sql_query($sql);

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET cat_order = '" . $new_pos . "'
							WHERE category_id = " . $cat_id;
			$result = $db->sql_query($sql);
		}

		if ($mode == "down")
		{
			$cat_id = $_GET['cat'];

			$sql = "SELECT *
							FROM " . KB_CATEGORIES_TABLE . "
							WHERE category_id = $cat_id";
			$result = $db->sql_query($sql);

			if ($category = $db->sql_fetchrow($result))
			{
				$parent = $category['parent'];
				$old_pos = $category['cat_order'];
				$new_pos = $old_pos + 10;
			}

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET cat_order = '" . $old_pos . "'
						WHERE parent = " . $parent . " AND cat_order = " . $new_pos;
			$result = $db->sql_query($sql);

			$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET cat_order = '" . $new_pos . "'
							WHERE category_id = " . $cat_id;
			$result = $db->sql_query($sql);
		}

		// Generate page

		$template->set_filenames(array('body' => KB_ADM_TPL_PATH . 'kb_cat_admin_body.tpl'));

		$template->assign_vars(array('L_KB_CAT_TITLE' => $lang['Cat_man'],
				'L_KB_CAT_DESCRIPTION' => $lang['KB_cat_description'],

				'L_CREATE_CAT' => $lang['Create_cat'],
				'L_CREATE' => $lang['Create'],
				'L_CATEGORY' => $lang['Article_category'],
				'L_ACTION' => $lang['Art_action'],
				'L_ARTICLES' => $lang['Articles'],
				'L_ORDER' => $lang['Update_order'],

				'S_ACTION' => append_sid('admin_kb_cat.' . PHP_EXT . '?mode=create'))
			);
		// get categories
		$sql = "SELECT *
					FROM " . KB_CATEGORIES_TABLE . "
					WHERE parent = 0 ORDER BY cat_order ASC";
		$cat_result = $db->sql_query($sql);

		$row_class = '';
		$ss = 0;
		while ($category = $db->sql_fetchrow($cat_result))
		{
			$category_details = $category['category_details'];
			$category_articles = $category['number_articles'];

			$category_id = $category['category_id'];
			$category_name = $category['category_name'];
			$temp_url = append_sid(IP_ROOT_PATH . 'kb.' . PHP_EXT . '?mode=cat&amp;cat=' . $category_id);
			$category_link = '<a href="' . $temp_url . '" class="gen">' . $category_name . '</a>';

			$temp_url = append_sid(KB_ADM_PATH . 'admin_kb_cat.' . PHP_EXT . '?mode=edit&amp;cat=' . $category_id);
			$edit = '<a href="' . $temp_url . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_edit'] . '" alt="' . $lang['Edit'] . '"></a>';

			$temp_url = append_sid(KB_ADM_PATH . 'admin_kb_cat.' . PHP_EXT . '?mode=delete&amp;cat=' . $category_id);
			$delete = '<a href="' . $temp_url . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_delete'] . '" alt="' . $lang['Delete'] . '"></a>';

			$temp_url = append_sid(KB_ADM_PATH . 'admin_kb_cat.' . PHP_EXT . '?mode=up&amp;cat=' . $category_id);
			$up = '<a href="' . $temp_url . '"><img src="' . IP_ROOT_PATH . $images['cms_arrow_up'] . '" alt="' . $lang['MOVE_UP'] . '"></a>';

			$temp_url = append_sid(KB_ADM_PATH . 'admin_kb_cat.' . PHP_EXT . '?mode=down&amp;cat=' . $category_id);
			$down = '<a href="' . $temp_url . '"><img src="' . IP_ROOT_PATH . $images['cms_arrow_down'] . '" alt="' . $lang['MOVE_DOWN'] . '"></a>';

			$row_class = ip_zebra_rows($row_class);
			$template->assign_block_vars('catrow', array('CATEGORY' => $category_link,
				'CAT_DESCRIPTION' => $category_details,
				'CAT_ARTICLES' => $category_articles,

				'U_EDIT' => $edit,
				'U_DELETE' => $delete,
				'U_UP' => $up,
				'U_DOWN' => $down,

				'ROW_CLASS' => $row_class
				)
			);

			$i++;
			$ss++;
			$ss = get_kb_cat_subs_admin($category_id, '1', '&nbsp;&nbsp;&nbsp;&nbsp;', $ss);
		}
		break;
}

$template->pparse('body');
include_once(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>
