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

if(!function_exists('cms_plugin_guestbook_form'))
{
	function cms_plugin_guestbook_form()
	{
		global $db, $cache, $config, $template, $images, $lang, $user, $bbcode, $table_prefix, $block_id, $cms_config_vars;

		if (empty($config['plugins']['guestbooks']['enabled']) || empty($config['plugins']['guestbooks']['dir']))
		{
			$template->assign_vars(array(
				'GUESTBOOK_DISABLED' => true,
				'GUESTBOOK_DISABLED_MSG' => $lang['PLUGIN_DISABLED'],
				)
			);
			//message_die(GENERAL_MESSAGE, 'PLUGIN_DISABLED');
		}
		else
		{
			$template->_tpldata['guestbook_posts.'] = array();

			include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['guestbooks']['dir'] . 'common.' . PHP_EXT);

			if (!function_exists('generate_text_for_display'))
			{
				include_once(IP_ROOT_PATH . 'includes/functions_bbcode.' . PHP_EXT);
			}

			// COMMON INCLUDES AND OPTIONS - BEGIN
			$page_array = array();
			$page_array = extract_current_page(IP_ROOT_PATH);
			$current_file = $page_array['page_full'];
			// Check if it is a CMS Users page
			if (defined('IN_CMS_USERS'))
			{
				global $ip_cms;

				$guestbook_owner = (int) $cms_config_vars['owner'];

				$sql = "SELECT g.guestbook_id
								FROM " . $class_guestbooks->guestbooks_list_table . " g
								WHERE g.guestbook_owner = " . $guestbook_owner . "
								LIMIT 1";
				$result = $db->sql_query($sql);
				$guestbook_data = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$guestbook_id = (!empty($guestbook_data) ? $guestbook_data['guestbook_id'] : 0);
				$ip_root_path = ('../../');
				$current_file = (!empty($page_array['page_dir']) ? (str_replace('//', '/', $page_array['page_dir'] . '/' . $cms_config_vars['folder'] . '/')) : '') . $current_file;
			}
			else
			{
				$guestbook_id = $cms_config_vars['plugin_guestbook_id'][$block_id];
				$ip_root_path = (IP_ROOT_PATH);
			}
			$class_guestbooks->guestbook_id = $guestbook_id;
			$post_id = $class_guestbooks->post_id;
			$mode_overlay = '';

			$guestbook_data = array();

			if (empty($guestbook_id))
			{
				// Force 'view' mode... then try to automatically catch the missing IDs...
				$mode_overlay = 'view';
				if (!empty($post_id))
				{
					$post_data = $class_guestbooks->get_post($post_id);
					$guestbook_id = $post_data['guestbook_id'];
					$class_guestbooks->guestbook_id = $guestbook_id;
					unset($post_data);
				}
			}

			$guestbook_data = $class_guestbooks->get_guestbook_data($guestbook_id);
			if (empty($guestbook_id) || empty($guestbook_data))
			{
				message_die(GENERAL_MESSAGE, $lang['NO_GUESTBOOK_ID']);
			}

			$guestbook_title = censor_text($guestbook_data['guestbook_title']);
			//$guestbook_title = ((strlen($guestbook_title) > 55) ? (htmlspecialchars(substr(htmlspecialchars_decode($guestbook_title, ENT_COMPAT), 0, 52)) . '...') : $guestbook_title);
			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
			$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
			$guestbook_description = generate_text_for_display($guestbook_data['guestbook_description'], false, true, false, '999999');

			$inputs_array = array();
			$is_owner = ($user->data['user_id'] == $guestbook_data['guestbook_owner']) ? true : false;
			$admin_allowed = (check_auth_level(AUTH_ADMIN) || $is_owner) ? true : false;
			$input_allowed = (check_auth_level($guestbook_data['guestbook_auth_post']) || $is_owner) ? true : false;
			$edit_allowed = (check_auth_level(AUTH_ADMIN) || $is_owner) ? true : false;
			$input_post_allowed = ($admin_allowed || check_auth_level($guestbook_data['guestbook_auth_post'])) ? true : false;
			$edit_post_allowed = ($admin_allowed || check_auth_level($guestbook_data['guestbook_auth_edit'])) ? true : false;

			include(IP_ROOT_PATH . 'includes/common_forms.' . PHP_EXT);

			$is_auth = true;
			if (in_array($mode, array('input', 'save')) && !$admin_allowed && ((($action == 'add') && !$input_post_allowed) || (($action == 'edit') && !$edit_post_allowed)))
			{
				$is_auth = false;
			}

			if (!$is_auth)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
			}
			// COMMON INCLUDES AND OPTIONS - END
			$template_to_parse = 'blocks/plugin_guestbook_block.tpl';
			//$template_to_parse = $class_plugins->get_tpl_file(GUESTBOOKS_TPL_PATH, 'guestbook_block.tpl');
			$template->set_filenames(array('plugin_guestbook_block' => $template_to_parse));

			//Now get all posts
			$items_array = $class_guestbooks->get_posts($guestbook_id, '', $start, $n_items);
			$page_items = sizeof($items_array);

			if ($page_items == 0)
			{
				$template->assign_var('NO_GUESTBOOK_POSTS', true);
			}
			else
			{
				for ($i = 0; $i < $page_items; $i++)
				{
					$post_guestbook_id = $items_array[$i]['guestbook_id'];
					$post_post_id = $items_array[$i]['post_id'];

					$guestbook_poster = ($items_array[$i]['poster_id'] != ANONYMOUS) ? colorize_username($items_array[$i]['poster_id'], htmlspecialchars_decode($items_array[$i]['username']), $items_array[$i]['user_color'], $items_array[$i]['user_active']) : (!empty($items_array[$i]['post_username']) ? $items_array[$i]['post_username'] : $lang['Guest']);
					$guestbook_post_title = censor_text($items_array[$i]['post_subject']);
					$guestbook_date = create_date_ip($config['default_dateformat'], $items_array[$i]['post_time'], $config['board_timezone']);

					//$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_HTML)) ? true : false;
					$bbcode->allow_html = false;
					$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_BBCODE)) ? true : false;
					$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_SMILIES)) ? true : false;
					$guestbook_post = generate_text_for_display($items_array[$i]['post_text'], false, true, false, '999999');

					$edit_link = '';
					$edit_img = '';

					$delete_link = '';
					$delete_img = '';

					$post_append_url = $class_guestbooks->guestbook_id_var . '=' . $post_guestbook_id . '&amp;' . $class_guestbooks->post_id_var . '=' . $post_post_id;

					$post_moderation_allowed = ($admin_allowed || (($items_array[$i]['poster_id'] != ANONYMOUS) && ($items_array[$i]['poster_id'] == $user->data['user_id']) && check_auth_level($guestbook_data['guestbook_auth_edit']))) ? true : false;
					if ($post_moderation_allowed)
					{
						$edit_link = append_sid($ip_root_path . CMS_PAGE_GUESTBOOK . '?' . $post_append_url . '&amp;mode=input&amp;action=edit');
						$edit_img = '<a href="' . $edit_link . '"><img src="' . $ip_root_path . 'images/cms/b_edit.png" alt="' . $lang['EDIT'] . '" title="' . $lang['EDIT'] . '" /></a>';

						$delete_link = append_sid($ip_root_path . CMS_PAGE_GUESTBOOK . '?' . $post_append_url . '&amp;mode=delete');
						$delete_img = '<a href="' . $delete_link . '"><img src="' . $ip_root_path . 'images/cms/b_delete.png" alt="' . $lang['DELETE'] . '" title="' . $lang['DELETE'] . '" /></a>';
					}

					$class = ($i % 2) ? $theme['td_class1'] : $theme['td_class2'];

					$template->assign_block_vars('guestbook_posts', array(
						'CLASS' => $class,
						'POST_ID' => $post_post_id,

						'POSTER' => $guestbook_poster,
						'DATE' => $guestbook_date,
						'MESSAGE' => $guestbook_post,
						'POSTED_BY' => sprintf($lang['GUESTBOOKS_POSTED_BY'], $guestbook_poster, $guestbook_date),

						'S_MOD' => $post_moderation_allowed,

						'U_EDIT' => $edit_link,
						'S_EDIT' => $edit_img,
						'U_DELETE' => $delete_link,
						'S_DELETE' => $delete_img,
						)
					);
				}
				$db->sql_freeresult($result);

				$total_items = $class_guestbooks->get_total_posts($guestbook_id);
				$pagination = generate_pagination(append_sid($current_file . ((strpos($current_file, '?') !== false) ? '&amp;' : '?') . $class_guestbooks->guestbook_id_var . '=' . $guestbook_id . $url_full_append), $total_items, $n_items, $start) . '&nbsp;';
				$template->assign_vars(array(
					'PAGINATION' => $pagination,
					'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $n_items) + 1), ceil($total_items / $n_items)),
					'L_GOTO_PAGE' => $lang['Goto_page']
					)
				);
			}

			$post_post_allowed = ($admin_allowed || check_auth_level($guestbook_data['guestbook_auth_post'])) ? true : false;
			if ($post_post_allowed)
			{
				$items_row = array();
				$s_hidden_fields = build_hidden_fields(array(
					'mode' => 'save',
					'action' => 'add',
					$class_guestbooks->guestbook_id_var => $guestbook_id,
					'cms_redirect' => $current_file,
					)
				);

				$table_fields = array();
				$table_fields_keys = array(
					'post_subject' => array('post_type' => 'post', 'value' => $table_posts_fields['post_subject']),
					'post_text' => array('post_type' => 'post', 'value' => $table_posts_fields['post_text']),
				);

				if (($user->data['user_id'] == ANONYMOUS) || ($action == 'edit'))
				{
					$table_fields_keys_extra = array(
						'post_username' => array('post_type' => 'post', 'value' => $table_posts_fields['post_username']),
						'poster_email' => array('post_type' => 'post', 'value' => $table_posts_fields['poster_email']),
					);
					$table_fields_keys = array_merge($table_fields_keys_extra, $table_fields_keys);
				}

				foreach ($table_fields_keys as $k => $v)
				{
					$table_fields[$k] = $v['value'];
				}
				$class_form->create_input_form($table_fields, $inputs_array, $current_time, $s_bbcb_global, $mode, 'add', $items_row);

				$template->assign_vars(array(
					'POST_POST_ALLOWED' => $post_post_allowed,
					'S_HIDDEN_FIELDS' => $s_hidden_fields
					)
				);
			}

			if (!$user->data['session_logged_in'])
			{
				include_once(IP_ROOT_PATH . 'includes/class_captcha.' . PHP_EXT);
				$class_captcha = new class_captcha();
				$class_captcha->build_captcha();
			}

			$current_cms_page_id = request_var('page', 0);
			$template->assign_vars(array(
				'L_GUESTBOOK_TITLE' => $guestbook_title,
				'U_GUESTBOOK_TITLE' => append_sid(basename($current_file) . ((!empty($current_cms_page_id) && (strpos($current_file, 'page=') === false)) ? '?page=' . $current_cms_page_id : '')),

				'GUESTBOOK_ID' => $guestbook_id,
				'GUESTBOOK_ID_VAR' => $class_guestbooks->guestbook_id_var,
				'GUESTBOOK_POST_ID_VAR' => $class_guestbooks->post_id_var,

				'TITLE' => $guestbook_title,
				'DESCRIPTION' => $guestbook_description,

				'S_ADMIN_ALLOWED' => ($admin_allowed ? true : false),
				'S_INPUT_ALLOWED' => ($input_allowed ? true : false),
				'S_EDIT_ALLOWED' => ($edit_allowed ? true : false),
				'S_MODE_ACTION' => append_sid($ip_root_path . CMS_PAGE_GUESTBOOK),
				'S_ACTION' => append_sid($ip_root_path . CMS_PAGE_GUESTBOOK),
				)
			);

			// BBCBMG - BEGIN
			if ($s_bbcb_global)
			{
				define('BBCB_MG_CUSTOM', true);
				include_once(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
				$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
			}
			// BBCBMG - END

		}
	}
}

cms_plugin_guestbook_form();

?>