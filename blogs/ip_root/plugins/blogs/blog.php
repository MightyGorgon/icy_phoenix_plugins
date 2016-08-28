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

// COMMON INCLUDES AND OPTIONS - BEGIN
$blog_id = $class_blogs->blog_id;
$topic_id = $class_blogs->topic_id;
$post_id = $class_blogs->post_id;
$mode_overlay = '';

$post_type = request_var('post_type', '');
$post_type = (!empty($post_type) && in_array($post_type, array('article', 'comment'))) ? $post_type : '';

$blog_data = array();

if (empty($blog_id))
{
	// Force 'view' mode... then try to automatically catch the missing IDs...
	$mode_overlay = 'view';
	$post_type = 'article';
	if (!empty($topic_id))
	{
		$article_data = $class_blogs->get_article($topic_id);
		$blog_id = $article_data['blog_id'];
		unset($article_data);
	}
	elseif (!empty($post_id))
	{
		$comment_data = $class_blogs->get_comment($post_id);
		$blog_id = $comment_data['blog_id'];
		$topic_id = $comment_data['topic_id'];
		unset($comment_data);
	}
}

$blog_data = $class_blogs->get_blog_data($blog_id);

if (empty($blog_id) || empty($blog_data))
{
	message_die(GENERAL_MESSAGE, $lang['NO_BLOG_ID']);
}

$inputs_array = array();
$is_owner = ($user->data['user_id'] == $blog_data['blog_owner']) ? true : false;
$user->data['user_id_plugin_owner'] = !empty($is_owner) ? $user->data['user_id'] : 0;
$admin_allowed = (check_auth_level(AUTH_ADMIN) || $is_owner) ? true : false;
$input_allowed = (check_auth_level($blog_data['blog_auth_post']) || $is_owner) ? true : false;
$edit_allowed = (check_auth_level(AUTH_ADMIN) || $is_owner) ? true : false;
$input_comment_allowed = ($admin_allowed || (($post_type == 'comment') && check_auth_level($blog_data['blog_auth_reply']))) ? true : false;
$edit_comment_allowed = ($admin_allowed || (($post_type == 'comment') && check_auth_level($blog_data['blog_auth_edit']))) ? true : false;

include(IP_ROOT_PATH . 'includes/common_forms.' . PHP_EXT);

$is_auth = true;
if ($post_type == 'comment')
{
	if (in_array($mode, array('input', 'save')) && !$admin_allowed && ((($action == 'add') && !$input_comment_allowed) || (($action == 'edit') && !$edit_comment_allowed)))
	{
		$is_auth = false;
	}
}
else
{
	if ((in_array($mode, array('input', 'save')) && !$admin_allowed && ((($action == 'add') && !$input_allowed) || (($action == 'edit') && !$edit_allowed))) || (($mode == 'delete') && !$admin_allowed))
	{
		$is_auth = false;
	}
}

if (!$is_auth)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
}
// COMMON INCLUDES AND OPTIONS - END

if (!function_exists('generate_text_for_display'))
{
	include_once(IP_ROOT_PATH . 'includes/functions_bbcode.' . PHP_EXT);
}

// Start output of page
$meta_content['page_title'] = $lang['BLOGS_PAGE'];
$meta_content['description'] = $lang['BLOGS_PAGE'];
$meta_content['keywords'] = $lang['BLOGS_PAGE'];
$breadcrumbs['bottom_right_links'] = '';
if ($input_allowed)
{
	$breadcrumbs['bottom_left_links'] = '';
	$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id . '&amp;mode=input&amp;post_type=article') . '">' . $lang['BLOG_LINK_POST_ARTICLE'] . '</a>';
}
$breadcrumbs['bottom_right_links'] .= (($breadcrumbs['bottom_right_links'] != '') ? ('&nbsp;' . MENU_SEP_CHAR . '&nbsp;') : '') . '<a href="' . append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id) . '">' . $lang['BLOG_LINK_ALL_ARTICLES'] . '</a>';

if ($mode == 'save')
{
	$article_data = $class_form->request_vars_data($table_topics_fields);
	$comment_data = $class_form->request_vars_data($table_posts_fields);

	// In case a guest is posting we need some basic checks...
	$error['status'] = false;
	$error['message'] = '';
	if ($user->data['user_id'] == ANONYMOUS)
	{
		if (empty($comment_data['post_username']))
		{
			$error['status'] = true;
			$error['message'] .= '<br /><br />' . $lang['BLOGS_ERROR_EMPTY_USERNAME'];
		}
	}
	else
	{
		if (empty($comment_data['post_text']))
		{
			$error['status'] = true;
			$error['message'] .= '<br /><br />' . $lang['BLOGS_ERROR_EMPTY_MESSAGE'];
		}
		if (($post_type == 'article') && empty($comment_data['post_subject']))
		{
			$error['status'] = true;
			$error['message'] .= '<br /><br />' . $lang['BLOGS_ERROR_EMPTY_TITLE'];
		}
	}

	if ($error['status'])
	{
		$error['message'] .= '<br /><br />' . $lang['BLOGS_ERROR_MESSAGE'];
		message_die(GENERAL_MESSAGE, $error['message']);
	}

	$current_time = time();
	$current_user_id = $user->data['user_id'];
	$current_username = ($user->data['user_id'] != ANONYMOUS) ? htmlspecialchars($user->data['username']) : (!empty($comment_data['post_username']) ? $comment_data['post_username'] : $lang['Guest']);
	$current_user_color = (($user->data['user_id'] != ANONYMOUS) && !empty($user->data['user_color'])) ? $user->data['user_color'] : '';

	$article_data['blog_id'] = $blog_id;
	$article_data['topic_id'] = $topic_id;

	$comment_data['blog_id'] = $blog_id;
	$comment_data['topic_id'] = $topic_id;
	$comment_data['post_id'] = $post_id;

	if ($action == 'edit')
	{
		if (empty($topic_id) || ($topic_id <= 0))
		{
			message_die(GENERAL_MESSAGE, $lang['NO_BLOG_TOPIC_ID']);
		}

		// Reset and unset some unused fields when updating...
		unset($article_data['topic_poster']);
		unset($article_data['topic_time']);
		unset($article_data['topic_first_post_id']);
		unset($article_data['topic_first_poster_id']);
		unset($article_data['topic_first_post_time']);
		unset($article_data['topic_first_poster_name']);
		unset($article_data['topic_first_poster_color']);
		unset($article_data['topic_last_post_id']);
		unset($article_data['topic_last_poster_id']);
		unset($article_data['topic_last_post_time']);
		unset($article_data['topic_last_poster_name']);
		unset($article_data['topic_last_poster_color']);

		unset($comment_data['poster_id']);
		unset($comment_data['post_time']);
		unset($comment_data['poster_ip']);

		if ($post_type == 'article')
		{
			$article_data['topic_title'] = $comment_data['post_subject'];
			$class_blogs->submit_article($article_data, $comment_data, 'update', $post_type);
			$message = $lang['BLOG_ARTICLE_UPDATED'];
		}
		else
		{
			if (empty($post_id) || ($post_id <= 0))
			{
				message_die(GENERAL_MESSAGE, $lang['NO_BLOG_POST_ID']);
			}
			$class_blogs->submit_comment($article_data, $comment_data, 'update', $post_type);
			$message = $lang['BLOG_COMMENT_UPDATED'];
		}
	}
	else
	{
		// Re-assign action just in case the above condition is not verified!
		$action = 'add';

		$article_data['topic_last_poster_id'] = $current_user_id;
		$article_data['topic_last_post_time'] = $current_time;
		$article_data['topic_last_poster_name'] = $current_username;
		$article_data['topic_last_poster_color'] = $current_user_color;

		$comment_data['poster_id'] = $current_user_id;
		$comment_data['post_time'] = $current_time;
		$comment_data['poster_ip'] = $user_ip;
		$comment_data['post_username'] = $current_username;

		if ($post_type == 'article')
		{
			$article_data['topic_title'] = $comment_data['post_subject'];
			$article_data['topic_poster'] = $current_user_id;
			$article_data['topic_time'] = $current_time;
			$article_data['topic_first_poster_id'] = $current_user_id;
			$article_data['topic_first_post_time'] = $current_time;
			$article_data['topic_first_poster_name'] = $current_username;
			$article_data['topic_first_poster_color'] = $current_user_color;

			// If it is a new insert, we need to unset the $item_id, because it will be automatically incremented by the DB
			unset($article_data['topic_id']);
			unset($comment_data['topic_id']);

			$class_blogs->submit_article($article_data, $comment_data, 'insert', $post_type);
			$topic_id = $article_data['topic_id'];
			$message = $lang['BLOG_ARTICLE_ADDED'];
		}
		else
		{
			// If it is a new insert, we need to unset the $item_id, because it will be automatically incremented by the DB
			unset($comment_data['post_id']);

			if (!$user->data['session_logged_in'])
			{
				// Clean old sessions and old confirm codes
				$user->confirm_gc();
				include_once(IP_ROOT_PATH . 'includes/class_captcha.' . PHP_EXT);
				$class_captcha = new class_captcha();
				$class_captcha->check_attempts(false);
				$captcha_result = $class_captcha->check_code();
				if ($captcha_result['error'])
				{
					message_die(GENERAL_MESSAGE, $captcha_result['error_msg']);
				}
			}
			$class_blogs->submit_comment($article_data, $comment_data, 'insert', $post_type);
			$post_id = $comment_data['post_id'];
			$message = $lang['BLOG_COMMENT_ADDED'];
		}
	}

	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_ARTICLE'], '<a href="' . append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id . '&amp;' . $class_blogs->topic_id_var . '=' . $topic_id . '&amp;mode=view&amp;post_type=article') . '">', '</a>');
	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_BLOG'], '<a href="' . append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'delete')
{
	if ($post_type == 'article')
	{
		if ($admin_allowed && ($topic_id > 0))
		{
			$class_blogs->remove_article($topic_id);
			$message = $lang['BLOG_ARTICLE_REMOVED'];
		}
		else
		{
			$message = $lang['Error'];
		}
	}
	else
	{
		if ($admin_allowed && ($post_id > 0))
		{
			$class_blogs->remove_comment($post_id);
			$message = $lang['BLOG_COMMENT_REMOVED'];
		}
		else
		{
			$message = $lang['Error'];
		}
	}

	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_BLOG'], '<a href="' . append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id) . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ($mode == 'input')
{
	$items_row = array();

	if ($action == 'edit')
	{
		$items_row_processed = false;
		if (($post_type == 'comment') && ($post_id > 0))
		{
			$items_row_processed = true;
			$items_row = $class_blogs->get_comment($post_id);
		}
		elseif (($post_type == 'article') && ($topic_id > 0))
		{
			$items_row_processed = true;
			$items_row = $class_blogs->get_article($topic_id);
		}
		else
		{
			// Re-assign action just in case the above conditions are not verified!
			$action = 'add';
		}

		if ($items_row_processed && empty($items_row))
		{
			message_die(GENERAL_ERROR, 'Could not query blogs table', $lang['Error'], __LINE__, __FILE__);
		}
	}
	else
	{
		// Re-assign action just in case the above condition is not verified!
		$action = 'add';
	}

	$s_hidden_fields = build_hidden_fields(array(
		'mode' => 'save',
		'action' => $action,
		'post_type' => $post_type,
		$class_blogs->blog_id_var => !empty($items_row['blog_id']) ? (int) $items_row['blog_id'] : $blog_id,
		$class_blogs->topic_id_var => !empty($items_row['topic_id']) ? (int) $items_row['topic_id'] : $topic_id,
		$class_blogs->post_id_var => !empty($items_row['post_id']) ? (int) $items_row['post_id'] : $post_id,
		)
	);

	$items_row['blog_id'] = !empty($items_row['blog_id']) ? $items_row['blog_id'] : (!empty($blog_id) ? $blog_id : 0);

	$template_to_parse = 'items_add_body.tpl';

	$s_bbcb_global = false;
	$table_fields = array();
	$table_fields_keys = array(
		'blog_id' => array('post_type' => 'comment', 'value' => $table_posts_fields['blog_id']),
		'topic_id' => array('post_type' => 'comment', 'value' => $table_posts_fields['topic_id']),
		'post_id' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_id']),
		'post_time' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_time']),
		'poster_id' => array('post_type' => 'comment', 'value' => $table_posts_fields['poster_id']),
		'post_subject' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_subject']),
		'post_text' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_text']),
		'post_status' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_status']),
		'post_flags' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_flags']),
		'topic_status' => array('post_type' => 'article', 'value' => $table_topics_fields['topic_status']),
		'topic_approved' => array('post_type' => 'article', 'value' => $table_topics_fields['topic_approved']),
	);

	if (($user->data['user_id'] == ANONYMOUS) || ($action == 'edit'))
	{
		$table_fields_keys_extra = array(
			'post_username' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_username']),
			'poster_email' => array('post_type' => 'comment', 'value' => $table_posts_fields['poster_email']),
		);
		$table_fields_keys = array_merge($table_fields_keys_extra, $table_fields_keys);
	}

	foreach ($table_fields_keys as $k => $v)
	{
		if (($post_type == 'article') || (($post_type == 'comment') && ($v['post_type'] != 'article')))
		{
			$table_fields[$k] = $v['value'];
		}
	}
	$class_form->create_input_form($table_fields, $inputs_array, $current_time, $s_bbcb_global, $mode, $action, $items_row);

	$template->assign_vars(array(
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}
elseif ($mode == 'view')
{
	$article_data = array();
	$article_data = $class_blogs->get_article($topic_id);

	$article_blog_id = $article_data['blog_id'];
	$article_topic_id = $article_data['topic_id'];

	$blog_poster = colorize_username($article_data['topic_first_poster_id'], htmlspecialchars_decode($article_data['topic_first_poster_name']), $article_data['topic_first_poster_color'], 1);
	$blog_last_poster = colorize_username($article_data['topic_last_poster_id'], htmlspecialchars_decode($article_data['topic_last_poster_name']), $article_data['topic_last_poster_color'], 1);
	$blog_title = censor_text($article_data['topic_title']);
	//$blog_title = ((strlen($blog_title) > 55) ? (htmlspecialchars(substr(htmlspecialchars_decode($blog_title, ENT_COMPAT), 0, 52)) . '...') : $blog_title);
	$blog_date = create_date_ip($config['default_dateformat'], $article_data['topic_first_post_time'], $config['board_timezone']);
	$blog_date_last_post = create_date_ip($config['default_dateformat'], $article_data['topic_last_post_time'], $config['board_timezone']);
	$blog_comments = $article_data['topic_replies'];

	//$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html'] && ($article_data['post_flags'] & OPTION_FLAG_HTML)) ? true : false;
	$bbcode->allow_html = false;
	$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode'] && ($article_data['post_flags'] & OPTION_FLAG_BBCODE)) ? true : false;
	$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies'] && ($article_data['post_flags'] & OPTION_FLAG_SMILIES)) ? true : false;
	$blog_article = generate_text_for_display($article_data['post_text'], false, true, false, '999999');

	// Page Title - BEGIN
	$item_title = $article_data['topic_title'];
	$item_title_url = append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id . '&amp;' . $class_blogs->topic_id_var . '=' . $topic_id . '&amp;mode=view');
	$meta_content['page_title'] = (!empty($item_title) ? (strip_tags($item_title) . ' - ') : '') . $meta_content['page_title'];
	// Page Title - END

	$article_append_url = $class_blogs->blog_id_var . '=' . $article_blog_id . '&amp;' . $class_blogs->topic_id_var . '=' . $article_topic_id;

	//$view_link = append_sid(THIS_FILE . '?' . $article_append_url . '&amp;mode=view&amp;post_type=article');
	$view_link = append_sid(THIS_FILE . '?' . $class_blogs->topic_id_var . '=' . $article_topic_id);
	$view_img = '<a href="' . $view_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_preview'] . '" alt="' . $lang['BLOG_ARTICLE_VIEW'] . '" title="' . $lang['BLOG_ARTICLE_VIEW'] . '" /></a>';

	$edit_link = append_sid(THIS_FILE . '?' . $article_append_url . '&amp;mode=input&amp;action=edit&amp;post_type=article');
	$edit_img = '<a href="' . $edit_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_edit'] . '" alt="' . $lang['EDIT'] . '" title="' . $lang['EDIT'] . '" /></a>';

	$delete_link = append_sid(THIS_FILE . '?' . $article_append_url . '&amp;mode=delete&amp;post_type=article');
	$delete_img = '<a href="' . $delete_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_delete'] . '" alt="' . $lang['DELETE'] . '" title="' . $lang['DELETE'] . '" /></a>';

	$template_to_parse = $class_plugins->get_tpl_file(BLOGS_TPL_PATH, 'blog_article_body.tpl');

	$next_prev_articles_data = $class_blogs->get_next_prev_articles($blog_id, $topic_id, 1);

	$prev_article_link = '';
	if (!empty($next_prev_articles_data['prev'][0]))
	{
		$prev_article_link = '<a href="' . append_sid(THIS_FILE . '?' . $class_blogs->topic_id_var . '=' . $next_prev_articles_data['prev'][0]['topic_id']) . '">' . $next_prev_articles_data['prev'][0]['topic_title'] . '</a>';
	}

	$next_article_link = '';
	if (!empty($next_prev_articles_data['next'][0]))
	{
		$next_article_link = '<a href="' . append_sid(THIS_FILE . '?' . $class_blogs->topic_id_var . '=' . $next_prev_articles_data['next'][0]['topic_id']) . '">' . $next_prev_articles_data['next'][0]['topic_title'] . '</a>';
	}

	$template->assign_vars(array(
		'BLOG_ID' => $blog_id,
		'TOPIC_ID' => $topic_id,

		'BLOG_TITLE' => $blog_title,
		'POSTER' => $blog_poster,
		'LAST_POSTER' => $blog_last_poster,
		'DATE' => $blog_date,
		'DATE_LAST_POST' => $blog_date_last_post,
		'COMMENTS' => $blog_comments,
		'POSTED_BY' => sprintf($lang['BLOGS_POSTED_BY'], $blog_poster, $blog_date),

		'ARTICLE' => $blog_article,

		'NEXT_ARTICLE' => $next_article_link,
		'PREV_ARTICLE' => $prev_article_link,

		'U_VIEW' => $view_link,
		'S_VIEW' => $view_img,
		'U_EDIT' => $edit_link,
		'S_EDIT' => $edit_img,
		'U_DELETE' => $delete_link,
		'S_DELETE' => $delete_img,
		)
	);

	//Now get all comments
	$items_array = $class_blogs->get_comments($topic_id, '', $start, $n_items);
	$page_items = sizeof($items_array);

	if ($page_items == 0)
	{
		$template->assign_var('NO_BLOG_COMMENTS', true);
	}
	else
	{
		$row_class = '';
		for ($i = 0; $i < $page_items; $i++)
		{
			$comment_blog_id = $items_array[$i]['blog_id'];
			$comment_topic_id = $items_array[$i]['topic_id'];
			$comment_post_id = $items_array[$i]['post_id'];

			$blog_poster = ($items_array[$i]['poster_id'] != ANONYMOUS) ? colorize_username($items_array[$i]['poster_id'], htmlspecialchars_decode($items_array[$i]['username']), $items_array[$i]['user_color'], $items_array[$i]['user_active']) : (!empty($items_array[$i]['post_username']) ? $items_array[$i]['post_username'] : $lang['Guest']);
			$blog_title = censor_text($items_array[$i]['post_subject']);
			$blog_date = create_date_ip($config['default_dateformat'], $items_array[$i]['post_time'], $config['board_timezone']);

			//$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_HTML)) ? true : false;
			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_BBCODE)) ? true : false;
			$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_SMILIES)) ? true : false;
			$blog_comment = generate_text_for_display($items_array[$i]['post_text'], false, true, false, '999999');

			$edit_link = '';
			$edit_img = '';

			$delete_link = '';
			$delete_img = '';

			$comment_append_url = $class_blogs->blog_id_var . '=' . $comment_blog_id . '&amp;' . $class_blogs->topic_id_var . '=' . $comment_topic_id . '&amp;' . $class_blogs->post_id_var . '=' . $comment_post_id;

			$comment_moderation_allowed = ($admin_allowed || (($items_array[$i]['poster_id'] != ANONYMOUS) && ($items_array[$i]['poster_id'] == $user->data['user_id']) && check_auth_level($blog_data['blog_auth_edit']))) ? true : false;
			if ($comment_moderation_allowed)
			{
				$edit_link = append_sid(THIS_FILE . '?' . $comment_append_url . '&amp;mode=input&amp;action=edit&amp;post_type=comment');
				$edit_img = '<a href="' . $edit_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_edit'] . '" alt="' . $lang['EDIT'] . '" title="' . $lang['EDIT'] . '" /></a>';

				$delete_link = append_sid(THIS_FILE . '?' . $comment_append_url . '&amp;mode=delete&amp;post_type=comment');
				$delete_img = '<a href="' . $delete_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_delete'] . '" alt="' . $lang['DELETE'] . '" title="' . $lang['DELETE'] . '" /></a>';
			}

			$row_class = ip_zebra_rows($row_class);
			$template->assign_block_vars('comments', array(
				'CLASS' => $row_class,
				'POST_ID' => $comment_post_id,

				'POSTER' => $blog_poster,
				'DATE' => $blog_date,
				'COMMENT' => $blog_comment,
				'POSTED_BY' => sprintf($lang['BLOGS_POSTED_BY'], $blog_poster, $blog_date),

				'S_MOD' => $comment_moderation_allowed,

				'U_EDIT' => $edit_link,
				'S_EDIT' => $edit_img,
				'U_DELETE' => $delete_link,
				'S_DELETE' => $delete_img,
				)
			);
		}
		$db->sql_freeresult($result);

		$total_items = $class_blogs->get_total_comments($topic_id);
		$pagination = generate_pagination(append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id . $url_full_append), $total_items, $n_items, $start) . '&nbsp;';
		$template->assign_vars(array(
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $n_items) + 1), ceil($total_items / $n_items)),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
	}

	$comment_post_allowed = ($admin_allowed || check_auth_level($blog_data['blog_auth_post'])) ? true : false;
	if ($comment_post_allowed)
	{
		$items_row = array();
		$s_hidden_fields = build_hidden_fields(array(
			'mode' => 'save',
			'action' => 'add',
			'post_type' => 'comment',
			$class_blogs->blog_id_var => $blog_id,
			$class_blogs->topic_id_var => $topic_id,
			)
		);

		$table_fields = array();
		$table_fields_keys = array(
			//'post_subject' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_subject']),
			'post_text' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_text']),
		);

		if ($user->data['user_id'] == ANONYMOUS)
		{
			$table_fields_keys_extra = array(
				'post_username' => array('post_type' => 'comment', 'value' => $table_posts_fields['post_username']),
				'poster_email' => array('post_type' => 'comment', 'value' => $table_posts_fields['poster_email']),
			);
			$table_fields_keys = array_merge($table_fields_keys_extra, $table_fields_keys);
		}

		foreach ($table_fields_keys as $k => $v)
		{
			$table_fields[$k] = $v['value'];
		}
		$class_form->create_input_form($table_fields, $inputs_array, $current_time, $s_bbcb_global, $mode, 'add', $items_row);

		$template->assign_vars(array(
			'COMMENT_POST_ALLOWED' => $comment_post_allowed,
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
}
else
{
	// Page Title - BEGIN
	$item_title = $blog_data['blog_title'];
	$item_title_url = append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id);
	$meta_content['page_title'] = (!empty($item_title) ? (strip_tags($item_title) . ' - ') : '') . $meta_content['page_title'];
	// Page Title - END

	$template_to_parse = $class_plugins->get_tpl_file(BLOGS_TPL_PATH, 'blog_body.tpl');

	$items_array = $class_blogs->get_articles($blog_id, '', $start, $n_items);
	$page_items = sizeof($items_array);

	if ($page_items == 0)
	{
		$template->assign_var('NO_BLOG_ARTICLES', true);
	}
	else
	{
		$row_class = '';
		for ($i = 0; $i < $page_items; $i++)
		{
			$article_blog_id = $items_array[$i]['blog_id'];
			$article_topic_id = $items_array[$i]['topic_id'];

			$blog_poster = colorize_username($items_array[$i]['topic_first_poster_id'], htmlspecialchars_decode($items_array[$i]['topic_first_poster_name']), $items_array[$i]['topic_first_poster_color'], 1);
			$blog_last_poster = colorize_username($items_array[$i]['topic_last_poster_id'], htmlspecialchars_decode($items_array[$i]['topic_last_poster_name']), $items_array[$i]['topic_last_poster_color'], 1);
			$blog_title = censor_text($items_array[$i]['topic_title']);
			//$blog_title = ((strlen($blog_title) > 55) ? (htmlspecialchars(substr(htmlspecialchars_decode($blog_title, ENT_COMPAT), 0, 52)) . '...') : $blog_title);
			$blog_date = create_date_ip($config['default_dateformat'], $items_array[$i]['topic_first_post_time'], $config['board_timezone']);
			$blog_date_last_post = create_date_ip($config['default_dateformat'], $items_array[$i]['topic_last_post_time'], $config['board_timezone']);
			$blog_comments = $items_array[$i]['topic_replies'];

			//$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_HTML)) ? true : false;
			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_BBCODE)) ? true : false;
			$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies'] && ($items_array[$i]['post_flags'] & OPTION_FLAG_SMILIES)) ? true : false;
			$blog_article = generate_text_for_display($items_array[$i]['post_text'], false, true, false, '999999');

			$article_append_url = $class_blogs->blog_id_var . '=' . $article_blog_id . '&amp;' . $class_blogs->topic_id_var . '=' . $article_topic_id;

			//$view_link = append_sid(THIS_FILE . '?' . $article_append_url . '&amp;mode=view&amp;post_type=article');
			$view_link = append_sid(THIS_FILE . '?' . $class_blogs->topic_id_var . '=' . $article_topic_id);
			$view_img = '<a href="' . $view_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_preview'] . '" alt="' . $lang['BLOG_ARTICLE_VIEW'] . '" title="' . $lang['BLOG_ARTICLE_VIEW'] . '" /></a>';

			$edit_link = append_sid(THIS_FILE . '?' . $article_append_url . '&amp;mode=input&amp;action=edit&amp;post_type=article');
			$edit_img = '<a href="' . $edit_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_edit'] . '" alt="' . $lang['EDIT'] . '" title="' . $lang['EDIT'] . '" /></a>';

			$delete_link = append_sid(THIS_FILE . '?' . $article_append_url . '&amp;mode=delete&amp;post_type=article');
			$delete_img = '<a href="' . $delete_link . '"><img src="' . IP_ROOT_PATH . $images['cms_icon_delete'] . '" alt="' . $lang['DELETE'] . '" title="' . $lang['DELETE'] . '" /></a>';

			$row_class = ip_zebra_rows($row_class);
			$template->assign_block_vars('articles', array(
				'CLASS' => $row_class,
				'BLOG_ID' => $blog_id,
				'TOPIC_ID' => $topic_id,

				'TITLE' => $blog_title,
				'POSTER' => $blog_poster,
				'LAST_POSTER' => $blog_last_poster,
				'DATE' => $blog_date,
				'DATE_LAST_POST' => $blog_date_last_post,
				'COMMENTS' => $blog_comments,
				'POSTED_BY' => sprintf($lang['BLOGS_POSTED_BY'], $blog_poster, $blog_date),

				'ARTICLE' => $blog_article,

				'U_VIEW' => $view_link,
				'S_VIEW' => $view_img,
				'U_EDIT' => $edit_link,
				'S_EDIT' => $edit_img,
				'U_DELETE' => $delete_link,
				'S_DELETE' => $delete_img,
				)
			);
		}
		$db->sql_freeresult($result);

		$total_items = $class_blogs->get_total_articles($blog_id);
		$pagination = generate_pagination(append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id . $url_full_append), $total_items, $n_items, $start) . '&nbsp;';
		$template->assign_vars(array(
			'PAGINATION' => $pagination,
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $n_items) + 1), ceil($total_items / $n_items)),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
	}
}

$template->assign_vars(array(
	'L_BLOG_TITLE' => $blog_data['blog_title'],
	'U_BLOG_TITLE' => append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id),

	'BLOG_ID_VAR' => $class_guestbooks->guestbook_id_var,
	'BLOG_TOPIC_ID_VAR' => $class_guestbooks->topic_id_var,
	'BLOG_POST_ID_VAR' => $class_guestbooks->post_id_var,

	'L_PAGE_NAME' => $meta_content['page_title'],
	'L_ITEM_TITLE' => !empty($item_title) ? $item_title : false,
	'U_ITEM_TITLE' => $item_title_url,
	'U_ITEM_ADD' => append_sid(THIS_FILE . '?' . $class_blogs->blog_id_var . '=' . $blog_id . '&amp;mode=input&amp;post_type=article'),

	'S_ADMIN_ALLOWED' => ($admin_allowed ? true : false),
	'S_INPUT_ALLOWED' => ($input_allowed ? true : false),
	'S_EDIT_ALLOWED' => ($edit_allowed ? true : false),
	'S_MODE_ACTION' => append_sid(THIS_FILE),
	'S_ACTION' => append_sid(THIS_FILE),
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

full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>