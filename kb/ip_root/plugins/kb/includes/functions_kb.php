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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// get_quick_stats();
// gets number of articles
function get_quick_stats($category_id = '')
{
	global $db, $template, $lang, $kb_config;

	$sql_stat = "SELECT *
			FROM " . KB_TYPES_TABLE;
	// newssuite addon
	if ($kb_config['news_operate_mode'] && !empty($category_id))
	{
		$kb_types_list = ns_auth_item($category_id);
		$sql_stat .= " WHERE id IN " . $kb_types_list;
	}

	$sql_stat .= " ORDER BY type";

	$result = $db->sql_query($sql_stat);

	$ii = 0;
	while ($type = $db->sql_fetchrow($result))
	{
		$ii++;
		$type_id = $type['id'];
		$type_name = $type['type'];

		$sql = "SELECT article_id FROM " . KB_ARTICLES_TABLE . "
			WHERE article_type = $type_id ";
		// newssuite addon
		if ($kb_config['news_operate_mode'] && !empty($category_id))
		{
			$kb_types_list = ns_auth_item($category_id);
			$sql .= " AND article_type IN " . $kb_types_list;
		}

		if (!empty($category_id))
		{
			$sql .= " AND article_category_id = '$category_id'";
		}

		$count = $db->sql_query($sql);
		$number_count = 0;
		$number_count = $db->sql_numrows($count);

		if (!empty($category_id) && $number_count > 0)
		{
			$template->assign_block_vars('quick_stats', array(
				'Q_TYPE_NAME' => (($ii == 1) ? '' . $type_name : $type_name),
				'Q_TYPE_AMOUNT' => '(' . $number_count . ')'
				)
			);
		}
	}

	return $template;
}

// get author of article

function get_kb_author($id, $get_all_userdata = false)
{
	global $db;

	$sql = "SELECT *
				FROM " . USERS_TABLE . "
				WHERE user_id = $id";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		if ($get_all_userdata)
		{
			$name = $row;
		}
		else
		{
			$name = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		}
	}
	else
	{
		$name = '';
	}

	return $name;
}

// get type of article

function get_kb_type($id)
{
	global $db;

	$sql = "SELECT type
					FROM " . KB_TYPES_TABLE . "
					WHERE id = '$id'";
	$result = $db->sql_query($sql);

	if ($row = $db->sql_fetchrow($result))
	{
		$type = $row['type'];
	}

	return $type;
}

// get category for article

function get_kb_cat($id)
{
	global $db;

	$sql = "SELECT *
			FROM " . KB_CATEGORIES_TABLE . "
			WHERE category_id = $id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);

	$row = $db->sql_fetchrow($result);

	return $row;
}

// get_kb_nav($cat_id)
// gets parents for category

function get_kb_nav($parent)
{
	global $db, $lang;
	global $path_kb, $path_kb_array,$path_kb_array3, $path_kb_array4, $is_block, $page_id;

	$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . "
					WHERE category_id = $parent";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	if (ereg('-kbc', $_SERVER['REQUEST_URI']) || ereg('mode=cat', $_SERVER['REQUEST_URI']))
	{
		$temp_url = append_sid(this_kb_mxurl('mode=cat&amp;cat=' . $row['category_id']));
		$path_kb_array[] .= $lang['Nav_Separator'] . '<a href="' . $temp_url . '" class="nav">' . $row['category_name'] . '</a>';
		$path_kb_array4[] = $lang['Nav_Separator'] . '<a href="' . $temp_url . '" class="nav-current">' . $row['category_name'] . '</a>';
		$path_kb_array3[] .= $row['category_name'];
		if ($row['parent'] != '0')
		{
			get_kb_nav($row['parent']);
			return;
		}

		$path_kb_array2 = array_reverse($path_kb_array);
		$path_kb_array5 = array_reverse($path_kb_array4);
		$i = 0;
		//echo $path_kb_array2[$i];
		while ($i < (sizeof($path_kb_array2) -1))
		{
			$path_kb .= $path_kb_array2[$i];
			$i++;
		}
		//echo $path_kb_array2[$i];
		$path_kb .= $path_kb_array5[$i];
		return;
	}
	else
	{
		$temp_url = append_sid(this_kb_mxurl('mode=cat&amp;cat=' . $row['category_id']));
		$path_kb_array[] .= $lang['Nav_Separator'] . '<a href="' . $temp_url . '" class="nav">' . $row['category_name'] . '</a>';

		if ($row['parent'] != '0')
		{
			get_kb_nav($row['parent']);
			return;
		}

		$path_kb_array2 = array_reverse($path_kb_array);

		$i = 0;
		while ($i <= sizeof($path_kb_array2))
		{
			$path_kb .= $path_kb_array2[$i];
			$i++;
		}

		return;
	}
}

// get articles for the category

function get_kb_articles($id = false, $approve, $block_name, $start = -1, $articles_in_cat = 0, $kb_is_auth = '')
{
	global $db, $config, $template, $images, $user, $lang, $is_block, $page_id, $is_admin;
	global $kb_news_sort_method_extra, $kb_news_sort_method, $kb_news_sort_par, $kb_config, $kb_is_auth;

	$server_url = create_server_url();

	$sql = "SELECT t.*, u.user_id, u.username, u.user_active, u.user_color, u.user_rank, u.user_sig, u.user_allowsmile
			FROM " . KB_ARTICLES_TABLE . " t, " . USERS_TABLE . " u
			WHERE ";

	if ($id)
	{
		$sql .= " t.article_category_id = " . $id . " AND";
	}
	// $sql .= " tt.topic_id = t.topic_id AND";
	$sql .= " u.user_id = t.article_author_id";

	if (!$kb_is_auth['auth_mod'])
	{
		$sql .= " AND t.approved = " . $approve;
	}
	// newssuite addon
	if ($kb_config['news_operate_mode'])
	{
		$kb_types_list = ns_auth_item($id);
		$sql .= " AND t.article_type IN " . $kb_types_list;
	}

	if (defined('IN_ADMIN'))
	{
		$sql .= " ORDER BY t.article_id";
	}
	else
	{
		$sql .= " ORDER BY " . $kb_news_sort_method_extra . $kb_news_sort_method . " " . $kb_news_sort_par;
	}
	if ($start > -1 && $articles_in_cat > 0)
	{
		$sql .= " LIMIT $start, $articles_in_cat";
	}

	$article_result = $db->sql_query($sql);

	$i = 0;
	while ($article = $db->sql_fetchrow($article_result))
	{
		$i++;
		$article_description = $article['article_description'] ;
		$article_cat = $article['article_category_id'];
		$article_approved = $article['approved'];
		// type
		$type_id = $article['article_type'];
		$article_type = get_kb_type($type_id);

		$article_date = create_date_ip($config['default_dateformat'], $article['article_date'], $config['board_timezone']);
		// author information
		$author_id = $article['article_author_id'];
		$author = ($author_id == -1) ? $lang['Guest'] : colorize_username($article['article_author_id'], $article['username'], $article['user_color'], $article['user_active']);

		$article_id = $article['article_id'];
		$views = $article['views'];

		$article_title = $article['article_title'];
		$article_description = censor_text($article_description);
		$article_title = censor_text($article_title);

		if (($config['url_rw'] == true) || (($config['url_rw_guests'] == true) && ($user->data['user_id'] == ANONYMOUS)))
		{
			$temp_url = append_sid (str_replace ('--', '-', make_url_friendly($article['article_title']) . '-kba' . $article_id . '.html'));
		}
		else
		{
			$temp_url = append_sid(IP_ROOT_PATH . 'kb.' . PHP_EXT . '?mode=article&amp;k=' . $article_id);
		}
		//$temp_url = append_sid(IP_ROOT_PATH . 'kb.' . PHP_EXT . '?mode=article&amp;k=' . $article_id);
		$article_link = '<a href="' . $temp_url . '" class="gen">' . $article_title . '</a>';

		$approve = '';
		$delete = '';
		$category_name = '';

		if (defined('IN_ADMIN'))
		{
			$category = get_kb_cat($article_cat);
			$category_name = $category['category_name'];

			if (($article_approved == 2) || ($article_approved == 0))
			{
				// approve
				$approve_url = append_sid('admin_kb_art.' . PHP_EXT . '?mode=approve&amp;a=' . $article_id . '&amp;start=' . $start);
				$approve_img = '<a href="' . $approve_url . '"><img src="' . $images['icon_approve'] . '" alt="' . $lang['Approve'] . '" /></a>';
				$approve = '<a href="' . $approve_url . '">' . $lang['Approve'] . '</a>';
			}
			elseif ($article_approved == 1)
			{
				// unapprove
				$approve_url = append_sid('admin_kb_art.' . PHP_EXT . '?mode=unapprove&amp;a=' . $article_id . '&amp;start=' . $start);
				$approve_img = '<a href="' . $approve_url . '"><img src="' . $images['icon_unapprove'] . '" alt="' . $lang['Un_approve'] . '" /></a>';
				$approve = '<a href="' . $approve_url . '">' . $lang['Un_approve'] . '</a>';
			}
			// delete
			$delete_url = append_sid('admin_kb_art.' . PHP_EXT . '?mode=delete&amp;a=' . $article_id . '&amp;start=' . $start);
			$delete_img = '<a href="' . $delete_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" /></a>';
			$delete = '<a href="' . $delete_url . '">' . $lang['Delete'] . '</a>';
		}
		else
		{
			$category = get_kb_cat($article_cat);
			$category_name = $category['category_name'];

			if ($kb_is_auth['auth_mod'])
			{
				if ($article_approved == 2 || $article_approved == 0)
				{
					// approve
					$approve_url = append_sid(this_kb_mxurl('mode=moderate&action=approve&amp;a=' . $article_id . '&cat=' . $article_cat . '&page=' . $page_id . '&start=' . $start));
					$approve_img = '<a href="' . $approve_url . '"><img src="' . $server_url . $images['icon_approve'] . '" alt="' . $lang['Approve'] . '" /></a>';
					$approve = '<a href="' . $approve_url . '">' . $lang['Approve'] . '</a>';
				}
				elseif ($article_approved == 1)
				{
					// unapprove
					$approve_url = append_sid(this_kb_mxurl('mode=moderate&action=unapprove&amp;a=' . $article_id . '&cat=' . $article_cat . '&page=' . $page_id . '&start=' . $start));
					$approve_img = '<a href="' . $approve_url . '"><img src="' . $server_url . $images['icon_unapprove'] . '" alt="' . $lang['Un_approve'] . '" /></a>';
					$approve = '<a href="' . $approve_url . '">' . $lang['Un_approve'] . '</a>';
				}
			}
			if ($kb_is_auth['auth_delete'] || $kb_is_auth['auth_mod'])
			{
				// delete
				$delete_url = append_sid(this_kb_mxurl('mode=moderate&action=delete&amp;a=' . $article_id . '&cat=' . $article_cat . '&page=' . $page_id . '&start=' . $start));
				$delete_img = '<a href="' . $delete_url . '"><img src="' . $server_url . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" /></a>';
				$delete = '<a href="' . $delete_url . '">' . $lang['Delete'] . '</a>';
			}
		}

		if (($article['article_rating'] == 0) || ($article['article_totalvotes'] == 0))
		{
			$rating = 0;
			$rating_votes = 0;
			$rating_message = '';
		}
		else
		{
			$rating = round($postrow[$i]['link_rating'] / $postrow[$i]['link_totalvotes'], 2);
			$rating_votes = $postrow[$i]['link_totalvotes'];
			$rating_message = '(' . $rating . '/10, </span><span class="gensmall">' . $rating_votes . ' votes)';
		}
		// Newssuite operation mode
		// if (ns_auth_item($article_cat, $type_id) && ns_auth_cat($article_cat))
		// {
		$template->assign_block_vars($block_name, array(
			'ARTICLE' => $article_link,
			'ARTICLE_DESCRIPTION' => $article_description,
			'ARTICLE_TYPE' => $article_type,
			'ARTICLE_DATE' => $article_date,
			'ARTICLE_AUTHOR' => $author,
			'CATEGORY' => $category_name,
			'ART_VIEWS' => $views,
			'ART_VOTES' => $rating_message,
			'U_APPROVE_IMG' => $approve_img,
			'U_APPROVE' => $approve,
			'U_DELETE_IMG' => $delete_img,
			'U_DELETE' => $delete
			)
		);
	}  // end loop

	if ($i == 0)
	{
		$template->assign_block_vars('no_articles', array('COMMENT' => $lang['No_Articles']));
	}

	return $i;
}

// get articles for the category

function get_kb_stats($type = false, $approve, $block_name, $start = -1, $articles_in_cat = 0, $kb_is_auth)
{
	global $db, $config, $template, $images, $user, $lang, $is_block, $page_id, $is_admin;

	$server_url = create_server_url();

	$sql = "SELECT k.*, u.user_id, u.username, u.user_active, u.user_color
					FROM " . KB_ARTICLES_TABLE . " k, " . USERS_TABLE . " u
					WHERE";

	$sql .= " k.approved = " . $approve;
	$sql .= " AND u.user_id = k.article_author_id";

	if ($type)
	{
		if ($type == 'toprated')
		{
			$sql .= " AND k.article_totalvotes > 0";
			$sql .= " ORDER BY k.article_rating / k.article_totalvotes DESC";
		}
		elseif ($type == 'latest')
		{
			$sql .= " ORDER BY k.article_date DESC";
		}
		elseif ($type == 'mostpopular')
		{
			$sql .= " AND k.views > 0";
			$sql .= " ORDER BY k.views DESC";
		}
	}

	if ($start > -1 && $articles_in_cat > 0)
	{
		$sql .= " LIMIT $start, $articles_in_cat";
	}
	$article_result = $db->sql_query($sql);

	$i = 0;

	while ($article = $db->sql_fetchrow($article_result))
	{
		if ($i == $articles_in_cat)
		{
			break;
		}

		$article_description = $article['article_description'];
		$article_cat = $article['article_category_id'];
		$article_approved = $article['approved'];
		// type
		$type_id = $article['article_type'];
		$article_type = get_kb_type($type_id);

		$article_date = create_date_ip($config['default_dateformat'], $article['article_date'], $config['board_timezone']);
		// author information
		$author_id = $article['article_author_id'];
		if ($author_id == -1)
		{
			$author = ($article['username'] == '') ? $lang['Guest'] : $article['username'];
		}
		else
		{
			$author = colorize_username($article['article_author_id'], $article['username'], $article['user_color'], $article['user_active']);
		}

		$article_id = $article['article_id'];
		$views = $article['views'];

		$article_title = $article['article_title'];
		$temp_url = append_sid(this_kb_mxurl('mode=article&amp;k=' . $article_id));
		$article_link = '<a href="' . $temp_url . '" class="gen">' . $article_title . '</a>';

		$approve = '';
		$delete = '';
		$category_name = '';

		$category = get_kb_cat($article_cat);
		$category_id = $category['category_id'];
		$category_name = $category['category_name'];
		$category_temp = append_sid(this_kb_mxurl('mode=cat&amp;cat=' . $category_id));
		$category_url = '<a href="' . $category_temp . '" class="genmed">' . $category_name . '</a>';

		if (defined('IN_ADMIN'))
		{
			$category = get_kb_cat($article_cat);
			$category_name = $category['category_name'];

			if ($article_approved == 2 || $article_approved == 0)
			{
				// approve
				$approve_url = append_sid('admin_kb_art.' . PHP_EXT . '?mode=approve&amp;a=' . $article_id);
				$approve_img = '<a href="' . $approve_url . '"><img src="' . $images['icon_approve'] . '" alt="' . $lang['Approve'] . '" /></a>';
				$approve = '<a href="' . $approve_url . '">' . $lang['Approve'] . '</a>';
			}
			elseif ($article_approved == 1)
			{
				// unapprove
				$approve_url = append_sid('admin_kb_art.' . PHP_EXT . '?mode=unapprove&amp;a=' . $article_id);
				$approve_img = '<a href="' . $approve_url . '"><img src="' . $images['icon_unapprove'] . '" alt="' . $lang['Un_approve'] . '" /></a>';
				$approve = '<a href="' . $approve_url . '">' . $lang['Un_approve'] . '</a>';
			}
			// delete
			$delete_url = append_sid('admin_kb_art.' . PHP_EXT . '?mode=delete&amp;a=' . $article_id);
			$delete_img = '<a href="' . $delete_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" /></a>';
			$delete = '<a href="' . $delete_url . '">' . $lang['Delete'] . '</a>';
		}
		elseif ($user->data['user_level'] == ADMIN)
		{
			$category = get_kb_cat($article_cat);
			$category_name = $category['category_name'];

			if ($article_approved == 2 || $article_approved == 0)
			{
				// approve
				$approve_url = append_sid(IP_ROOT_PATH . ADM . '/admin_kb_art.' . PHP_EXT . '?mode=approve&amp;a=' . $article_id);
				$approve_img = '<a href="' . $approve_url . '"><img src="' . $server_url . $images['icon_approve'] . '" alt="' . $lang['Approve'] . '" /></a>';
				$approve = '<a href="' . $approve_url . '">' . $lang['Approve'] . '</a>';
			}
			elseif ($article_approved == 1)
			{
				// unapprove
				$approve_url = append_sid(IP_ROOT_PATH . ADM . '/admin_kb_art.' . PHP_EXT . '?mode=unapprove&amp;a=' . $article_id);
				$approve_img = '<a href="' . $approve_url . '"><img src="' . $server_url . $images['icon_unapprove'] . '" alt="' . $lang['Un_approve'] . '" /></a>';
				$approve = '<a href="' . $approve_url . '">' . $lang['Un_approve'] . '</a>';
			}
			// delete
			$delete_url = append_sid(IP_ROOT_PATH . ADM . '/admin_kb_art.' . PHP_EXT . '?mode=delete&amp;a=' . $article_id);
			$delete_img = '<a href="' . $delete_url . '"><img src="' . $server_url . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" /></a>';
			$delete = '<a href="' . $delete_url . '">' . $lang['Delete'] . '</a>';
		}

		if ($article['article_rating'] == 0 || $article['article_totalvotes'] == 0)
		{
			$rating = 0;
			$rating_votes = 0;
			$rating_message = '';
		}
		else
		{
			$rating = round($postrow[$i]['link_rating'] / $postrow[$i]['link_totalvotes'], 2);
			$rating_votes = $postrow[$i]['link_totalvotes'];
			$rating_message = '(' . $rating . '/10, </span><span class="gensmall">' . $rating_votes . ' votes)';
		}

		if (ns_auth_item($article_cat, $type_id) && ns_auth_cat($article_cat)  && $kb_is_auth[$article_cat]['auth_view'])
		{
			$i++;
			$template->assign_block_vars($block_name, array(
					'ARTICLE' => $article_link,
					'ARTICLE_DESCRIPTION' => $article_description,
					'ARTICLE_TYPE' => $article_type,
					'ARTICLE_DATE' => $article_date,
					'ARTICLE_AUTHOR' => $author,
					'CATEGORY' => $category_url,
					'ART_VIEWS' => $views,
					'ART_VOTES' => $rating_message,

					'U_APPROVE_IMG' => $approve_img,
					'U_APPROVE' => $approve,
					'U_DELETE_IMG' => $delete_img,
					'U_DELETE' => $delete
					)
				);
		}
	}
	if ($i == 0)
	{
		$template->assign_block_vars('no_articles', array('COMMENT' => $lang['No_Articles']));
	}

	return $template;
}

// update number of articles in a category

function update_kb_number($id, $change)
{
	global $db;
	// update number of articles in category if article has been approve
	$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '" . $id . "'";
	$result = $db->sql_query($sql);
	if ($kb_cat = $db->sql_fetchrow($result))
	{
		$new_number = $kb_cat['number_articles'] . $change;
	}

	$sql = "UPDATE " . KB_CATEGORIES_TABLE . " SET number_articles = " . $new_number . " WHERE category_id = '" . $id . "'";
	$result = $db->sql_query($sql);

	if ($kb_cat['parent'] != '0')
	{
		update_kb_number($kb_cat['parent'], $change);
	}

	return;
}

// email admin

function kb_notify($action, $message, $to_id, $from_id, $info = 'new')
{
	global $lang, $config, $kb_config, $db, $user;

	switch ($info)
	{
		case 'new':
			$subject_tmp = $lang['KB_notify_subject_new'];
		break;

		case 'edited':
			$subject_tmp = $lang['KB_notify_subject_edited'];
		break;

		case 'approved':
			$subject_tmp = $lang['KB_notify_subject_approved'];
		break;

		case 'unapproved':
			$subject_tmp = $lang['KB_notify_subject_unapproved'];
		break;
	}

	if ($action == 2) // Mail
	{
		$email_subject = $lang['KB_title'] . ' - ' . $subject_tmp;
		$email_body = $lang['KB_notify_body']  . $message;

		kb_mailer($to_id, $email_body, $email_subject, $from_id);

	}
	else if ($action == 1) // PM
	{
		$email_subject = $lang['KB_title'] . ' - ' . $subject_tmp;
		$email_body = $lang['KB_notify_body'] . $message;

		kb_insert_pm($to_id, $email_body, $email_subject, $from_id);
	}
}

// wgErics good old insert_pm function
function kb_insert_pm($to_id, $message, $subject, $from_id, $html_on = 0, $acro_auto_on = 1, $bbcode_on = 1, $smilies_on = 1)
{
	global $db, $config, $user, $lang;

	if (empty($from_id))
	{
		$from_id = $user->data['user_id'];
	}

	//get varibles ready
	$to_id = intval($to_id);
	$from_id = intval($from_id);
	$msg_time = time();
	$attach_sig = $user->data['user_attachsig'];

	// Why send PM to yourself???
	if ($to_id == $from_id)
	{
		return;
	}

	//get to users info
	$sql = "SELECT user_id, user_notify_pm, user_email, user_lang, user_active
		FROM " . USERS_TABLE . "
		WHERE user_id = '$to_id'
			AND user_id <> " . ANONYMOUS;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		$error = true;
		$error_msg = $lang['NO_USER'];
	}

	$to_userdata = $db->sql_fetchrow($result);

	$privmsg_subject = trim(strip_tags($subject));
	if (empty($privmsg_subject))
	{
		$error = true;
		$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Empty_subject'];
	}

	if (!empty($message))
	{
		if (!$error)
		{
			$privmsg_message = prepare_message($message, $html_on, $bbcode_on, $smilies_on);
			//$privmsg_message = str_replace('\\\n', '\n', $privmsg_message);
		}
	}
	else
	{
		$error = true;
		$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Empty_message'];
	}

	//
	// See if recipient is at their inbox limit
	//
	$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time
		FROM " . PRIVMSGS_TABLE . "
		WHERE (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
				OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "
				OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . ")
			AND privmsgs_to_userid = " . $to_userdata['user_id'];
	$result = $db->sql_query($sql);

	if ($inbox_info = $db->sql_fetchrow($result))
	{
		if ($inbox_info['inbox_items'] >= $config['max_inbox_privmsgs'])
		{
			$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . "
				WHERE (privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
						OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "
						OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " )
					AND privmsgs_date = " . $inbox_info['oldest_post_time'] . "
					AND privmsgs_to_userid = " . $to_userdata['user_id'];
			$result = $db->sql_query($sql);
			$old_privmsgs_id = $db->sql_fetchrow($result);
			$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];

			$sql = "DELETE FROM " . PRIVMSGS_TABLE . "
				WHERE privmsgs_id = $old_privmsgs_id";
			$db->sql_query($sql);
		}
	}

	$sql_info = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_text, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_enable_autolinks_acronyms, privmsgs_attach_sig)
		VALUES (" . PRIVMSGS_NEW_MAIL . ", '" . $db->sql_escape($privmsg_subject) . "', '" . $db->sql_escape($privmsg_message) . "', " . $from_id . ", " . $to_userdata['user_id'] . ", $msg_time, '" . $db->sql_escape($user->ip) . "', $html_on, $bbcode_on, $smilies_on, $acro_auto_on, $attach_sig)";
	$result = $db->sql_query($sql_info);
	{
		message_die(GENERAL_ERROR, "Could not insert/update private message sent info.", "", __LINE__, __FILE__, $sql_info);
	}

	// Add to the users new pm counter
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "
		WHERE user_id = " . $to_userdata['user_id'];
	$status = $db->sql_query($sql);

	if ($to_userdata['user_notify_pm'] && !empty($to_userdata['user_email']) && $to_userdata['user_active'])
	{
		$server_url = create_server_url();
		$privmsg_url = $server_url . CMS_PAGE_PRIVMSG;

		include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
		$emailer = new emailer();

		$emailer->use_template('privmsg_notify', $to_userdata['user_lang']);
		$emailer->to($to_userdata['user_email']);
		$emailer->set_subject($lang['Notification_subject']);

		$email_sig = create_signature($config['board_email_sig']);
		$emailer->assign_vars(array(
			'USERNAME' => $to_username,
			'SITENAME' => $config['sitename'],
			'EMAIL_SIG' => $email_sig,

			'U_INBOX' => $privmsg_url . '?folder=inbox'
			)
		);

		$emailer->send();
		$emailer->reset();
	}

	return;

	$msg = $lang['Message_sent'] . '<br /><br />' . sprintf($lang['Click_return_inbox'], '<a href="' . append_sid(CMS_PAGE_PRIVMSG . '?folder=inbox') . '">', '</a> ') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(CMS_PAGE_FORUM) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $msg);

} // insert_pm()

function kb_mailer($to_id, $message, $subject, $from_id, $html_on = 0, $acro_auto_on = 1, $bbcode_on = 1, $smilies_on = 1)
{
	global $db, $config, $user, $lang;

	if (!$from_id)
	{
		$from_id = $user->data['user_id'];
	}

	//get varibles ready
	$to_id = intval($to_id);
	$from_id = intval($from_id);
	$msg_time = time();
	$attach_sig = $user->data['user_attachsig'];

	//get to users info
	$sql = "SELECT user_id, user_notify_pm, user_email, user_lang, user_active
		FROM " . USERS_TABLE . "
		WHERE user_id = '$to_id'
			AND user_id <> " . ANONYMOUS;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if (!$result)
	{
		$error = true;
		$error_msg = $lang['NO_USER'];
	}

	$to_userdata = $db->sql_fetchrow($result);

	$privmsg_subject = trim(strip_tags($subject));
	if (empty($privmsg_subject))
	{
		$error = true;
		$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Empty_subject'];
	}

	if (!empty($message))
	{
		if (!$error)
		{
			$privmsg_message = prepare_message($message, $html_on, $bbcode_on, $smilies_on);
			$privmsg_message = str_replace('\\\n', '\n', $privmsg_message);
		}
	}
	else
	{
		$error = true;
		$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Empty_message'];
	}

	include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
	$emailer = new emailer();

	$emailer->to($to_userdata['user_email']);
	$emailer->set_subject($privmsg_subject);
	$emailer->msg = $privmsg_message;

	$emailer->send();
	$emailer->reset();
}
// get categories for index

function get_kb_cat_index($parent = 0)
{
	global $db, $template, $user, $is_block, $page_id, $kb_config, $kb_quick_nav;

	$sql = "SELECT *
				FROM " . KB_CATEGORIES_TABLE . "
			WHERE parent = " . $parent . "
			ORDER BY cat_order";
	$result = $db->sql_query($sql);

	// Start auth check
		$kb_is_auth_all = array();
		$kb_is_auth_all = kb_auth(AUTH_ALL, AUTH_LIST_ALL, $user->data);
	// End of auth check

	while ($category = $db->sql_fetchrow($result))
	{
		// newssuite addon

		$category_articles = $category['number_articles'];
		$category_details = $category['category_details'];

		$category_id = $category['category_id'];
		$category_name = $category['category_name'];
		$temp_url = append_sid(this_kb_mxurl('mode=cat&amp;cat=' . $category_id));
		$category = '<a href="' . $temp_url . '" class="forumlink">' . $category_name . '</a>';
		// Newssuite operating mode
		if (ns_auth_cat($category_id) && $kb_is_auth_all[$category_id]['auth_view'])
		{
			$template->assign_block_vars('catrow', array(
					'CATEGORY' => $category,
					'CATEGORY_XS' => $temp_url,
					'CAT_DESCRIPTION' => $category_details,
					'CAT_ARTICLES' => $category_articles
				)
			);
		}
	}

	$kb_quick_nav = get_kb_cat_list('auth_view', 0, 0, true, $kb_is_auth_all);

	return $template;
}

// get sub categories for articles

function get_kb_cat_subs($parent, $kb_is_auth_all = false)
{
	global $db, $template, $is_block, $page_id, $kb_config;

	$sql = "SELECT *
			FROM " . KB_CATEGORIES_TABLE . "
			WHERE parent = " . $parent . "
			ORDER BY cat_order";
	$result = $db->sql_query($sql);
	$result2 = $db->sql_query($sql);

	if (!$kb_is_auth_all)
	{
		// Start auth check
		//
			$kb_is_auth_all = array();
			$kb_is_auth_all = kb_auth(AUTH_ALL, AUTH_LIST_ALL, $user->data);

		// End of auth check
		//
	}

	if ($category2 = $db->sql_fetchrow($result2))
	{
		if ($category2['category_name'] != '')
		{
			// Newssuite operating mode
			if (ns_auth_cat($category2['category_id']) && $kb_is_auth_all[$category2['category_id']]['auth_view'])
			{
				$template->assign_block_vars('switch_sub_cats', array());
			}
		}
	}

	while ($category = $db->sql_fetchrow($result))
	{
		$category_articles = $category['number_articles'];
		$category_details = $category['category_details'];
		// $category_articles = $category['number_articles'];
		$category_id = $category['category_id'];
		$category_name = $category['category_name'];
		$temp_url = append_sid(this_kb_mxurl('mode=cat&amp;cat=' . $category_id));
		$category = '<a href="' . $temp_url . '" class="forumlink">' . $category_name . '</a>';

		// Newssuite operating mode
		if (ns_auth_cat($category_id) && $kb_is_auth_all[$category_id]['auth_view'])
		{
			$template->assign_block_vars('switch_sub_cats.catrow', array(
					'CATEGORY' => $category,
					'CATEGORY_XS' => $temp_url,
					'CAT_DESCRIPTION' => $category_details,
					'CAT_ARTICLES' => $category_articles
				)
			);
		}
	}
	return $template;
}

// get_kb_cat_subs($parent)
// gets sub categories for a category

function get_kb_cat_subs_admin($parent, $select = 1, $indent, $ss)
{
	global $db, $config, $template, $images, $theme, $lang, $row_class, $i;

	$server_url = create_server_url();

	$idfield = 'category_id';

	$sql = "SELECT *
			FROM " . KB_CATEGORIES_TABLE . "
			WHERE parent = " . $parent ;

	if ($select == 0)
	{
		$sql .= " AND $idfield <> $parent";
	}
	$sql .= " ORDER BY cat_order ASC";

	$result = $db->sql_query($sql);

	$row_class = '';
	while ($category2 = $db->sql_fetchrow($result))
	{
		$category_details2 = $category2['category_details'];
		$category_articles2 = $category2['number_articles'];

		$category_id2 = $category2['category_id'];
		$category_name2 = $category2['category_name'];
		$temp_url = append_sid(IP_ROOT_PATH . 'kb.' . PHP_EXT . '?mode=cat&amp;cat=' . $category_id2);
		$category2 = '<a href="' . $temp_url . '" class="gen">' . $category_name2 . '</a>';

		$temp_url = append_sid('admin_kb_cat.' . PHP_EXT . '?mode=edit&amp;cat=' . $category_id2);
		$edit2 = '<a href="' . $temp_url . '"><img src="' . $images['cms_icon_edit'] . '" alt="' . $lang['Edit'] . '"></a>';

		$temp_url = append_sid('admin_kb_cat.' . PHP_EXT . '?mode=delete&amp;cat=' . $category_id2);
		$delete2 = '<a href="' . $temp_url . '"><img src="' . $images['cms_icon_delete'] . '" alt="' . $lang['Delete'] . '"></a>';

		$temp_url = append_sid('admin_kb_cat.' . PHP_EXT . '?mode=up&amp;cat=' . $category_id2);
		$up2 = '<a href="' . $temp_url . '"><img src="' . $images['cms_arrow_up'] . '" alt="' . $lang['MOVE_UP'] . '"></a>';

		$temp_url = append_sid(IP_ROOT_PATH . ADM . '/admin_kb_cat.' . PHP_EXT . '?mode=down&amp;cat=' . $category_id2);
		$down2 = '<a href="' . $temp_url . '"><img src="' . $images['cms_arrow_down'] . '" alt="' . $lang['MOVE_DOWN'] . '"></a>';

		$row_class = ip_zebra_rows($row_class);
		$template->assign_block_vars('catrow.subrow', array(
				'CATEGORY' => $category2,
				'CATEGORY_XS' => $temp_url,
				'CAT_DESCRIPTION' => $category_details2,
				'CAT_ARTICLES' => $category_articles2,

				'INDENT' => $indent . '--&raquo;&nbsp;',

				'U_EDIT' => $edit2,
				'U_DELETE' => $delete2,
				'U_UP' => $up2,
				'U_DOWN' => $down2,

				'ROW_CLASS' => $row_class
				)
			);

			$temp = $indent . '&nbsp;&nbsp;&nbsp;&nbsp;';
			$ss++;
			$ss = get_kb_cat_subs_admin($category_id2, $select, $temp, $ss);

	}
	return $ss;
}

function get_kb_cat_subs_list($auth_type, $parent, $select = 1, $selected = false, $is_admin = false, $kb_is_auth_all, $indent, $current_id = 0)
{
	global $db;

	$idfield = 'category_id';
	$namefield = 'category_name';

	$sql = "SELECT *
			FROM " . KB_CATEGORIES_TABLE . "
			WHERE parent = " . $parent . "
			AND " . $idfield . " != " . intval($current_id);

	if ($select == 0)
	{
		$sql .= " AND $idfield <> $parent";
	}

	$sql .= " ORDER BY cat_order ASC";

	$result = $db->sql_query($sql);

	$catlist = "";
	while ($category2 = $db->sql_fetchrow($result))
	{
		if ($select == $category2[$idfield] && $selected)
		{
			$status = 'selected';
		}
		else
		{
			$status = '';
		}

		if ((ns_auth_cat($category2[$idfield]) && $kb_is_auth_all[$category2[$idfield]][$auth_type]) || $is_admin)
		{
			$catlist .= "<option value=\"$category2[$idfield]\" $status>" . $indent . '--&raquo;'. $category2[$namefield] . "</option>\n";
			$temp = $indent . '&nbsp;&nbsp;';
			//$catlist .= get_kb_cat_subs_list($auth_type, $category2[$idfield], $select, $selected, $is_admin, $kb_is_auth_all, $temp);
			$catlist .= get_kb_cat_subs_list($auth_type, $category2[$idfield], $select, $selected, $is_admin, $kb_is_auth_all, $temp, $current_id);
		}
	}

	return $catlist;
}

// get category list for adding and editing articles

function get_kb_cat_list($auth_type, $id = 0, $select = 1, $selected = false, $kb_is_auth_all = false, $is_admin = false, $current_id = 0)
{
	global $db, $user;

	$idfield = 'category_id';
	$namefield = 'category_name';

	$sql = "SELECT *
		FROM " . KB_CATEGORIES_TABLE . "
		WHERE parent = 0
		AND $idfield != " . intval($current_id);

	if ($select == 0)
	{
		$sql .= " AND $idfield <> " . (empty($id) ? 0 : $id);
	}

	$sql .= " ORDER BY cat_order ASC";

	$cat_result = $db->sql_query($sql);

	$catlist = '';
	if (!$kb_is_auth_all)
	{
		// Start auth check
		$kb_is_auth_all = array();
		$kb_is_auth_all = kb_auth(AUTH_ALL, AUTH_LIST_ALL, $user->data);
		// End of auth check
	}

	while ($category = $db->sql_fetchrow($cat_result))
	{
		if ($select == $category[$idfield] && $selected)
		{
			$status = 'selected';
		}
		else
		{
			$status = '';
		}

		if ((ns_auth_cat($category[$idfield]) && $kb_is_auth_all[$category[$idfield]][$auth_type]) || $is_admin)
		{
			$catlist .= "<option value=\"$category[$idfield]\" $status>" . $category[$namefield] . "</option>\n";
			$catlist .= get_kb_cat_subs_list($auth_type, $category[$idfield], $select, $selected, $is_admin, $kb_is_auth_all, '&nbsp;&nbsp;', $current_id);
		}
	}

		return $catlist ;
}

// get type list for adding and editing articles
function get_kb_type_list($sel_id)
{
	global $db, $template;

	$sql = "SELECT * FROM " . KB_TYPES_TABLE;
	$type_result = $db->sql_query($sql);

	while ($type = $db->sql_fetchrow($type_result))
	{
		$type_name = $type['type'];
		$type_id = $type['id'];

		if ($sel_id == $type_id)
		{
			$status = 'selected';
		}
		else
		{
			$status = '';
		}

		$type = '<option value="' . $type_id . '" ' . $status . '>' . $type_name . '</option>';

		$template->assign_block_vars('types', array('TYPE' => $type));
	}
	return $template;
}

// get type list for adding and editing articles
function get_kb_article_list($sel_id)
{
	global $db, $template;

	$sql = "SELECT * FROM " . KB_ARTICLES_TABLE;
	$type_result = $db->sql_query($sql);

	$kb_article_list = '<select name="default_article_id">';

	while ($type = $db->sql_fetchrow($type_result))
	{
		$article_name = $type['article_title'];
		$article_id = $type['article_id'];

		if ($sel_id == $article_id)
		{
			$status = 'selected';
		}
		else
		{
			$status = '';
		}

		$kb_article_list .= '<option value="' . $article_id . '" ' . $status . '>' . $article_name . '</option>';

	}
	$kb_article_list .= "</select>";
	return $kb_article_list;
}

/*
 *   Description    :   This functions is used to insert a post into your phpbb forums.
 *                      It handles all the related bits like updating post counts,
 *                      indexing search words, etc.
 *                      The post is inserted for a specific user, so you will have to
 *                      already have a user setup which you want to use with it.
 *
 *                      If you're using the POST method to input data then you should call stripslashes on
 *                      your subject and message before calling insert_post - see test_insert_post for example.
 *
 *   Parameters     :   $message            - the message that will form the body of the post
 *                      $subject            - the subject of the post
 *                      $forum_id           - the forum the post is to be added to
 *                      $user_id            - the id of the user for the post
 *                      $user_name          - the username of the user for the post
 *                      $user_attach_sig    - should the user's signature be attached to the post
 *
 *   Options Params :   $topic_id           - if topic_id is passed then the post will be
 *                                              added as a reply to this topic
 *                      $topic_type         - defaults to POST_NORMAL, can also be
 *                                              POST_STICKY, POST_ANNOUNCE or POST_GLOBAL_ANNOUNCE
 *                      $do_notification    - should users be notified of new posts (only valid for replies)
 *                      $notify_user        - should the 'posting' user be signed up for notifications of this topic
 *                      $current_time       - should the current time be used, if not then you should supply a posting time
 *                      $error_die_function - can be used to supply a custom error function.
 *                      $html_on = 0    - should html be allowed (parsed) in the post text.
 *                      $bbcode_on = 1   - should bbcode be allowed (parsed) in the post text.
 *                      $smilies_on = 1  - should smilies be allowed (parsed) in the post text.
 *
 *   Returns        :   If the function succeeds without an error it will return an array containing
 *                      the post id and the topic id of the new post. Any error along the way will result in either
 *                      the normal phpbb message_die function being called or a custom die function determined
 *                      by the $error_die_function parameter.
 */
// insert post for site updates, by netclectic - Adrian Cockburn
function kb_insert_post($message, $subject, $forum_id, $user_id, $user_name, $user_attach_sig, $topic_id = '', $message_update_text = '', $topic_type = POST_NORMAL, $do_notification = false, $notify_user = false, $current_time = 0, $error_die_function = '', $html_on = 0, $acro_auto_on = 1, $bbcode_on = 1, $smilies_on = 1)
{
	global $db, $config, $user, $lang, $kb_config;
	// initialise some variables

	$poll_title = '';
	$poll_options = array();
	$poll_data = array();
	$mode = 'reply';

	$error_die_function = ($error_die_function == '') ? 'message_die' : $error_die_function;
	$current_time = ($current_time == 0) ? time() : $current_time;

	// parse the message and the subject (belt & braces :)
	$message = addslashes(unprepare_message($message));
	$message = prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on);
	$message_update_text = addslashes(unprepare_message($message_update_text));
	$message_update_text = prepare_message(trim($message_update_text), $html_on, $bbcode_on, $smilies_on);
	$subject = addslashes(unprepare_message(trim($subject)));

	$username = addslashes(unprepare_message(trim($user_name)));
	$username = phpbb_clean_username($username);

	// if this is a new topic then insert the topic details
	if (empty($topic_id))
	{
		$mode = 'newtopic';
		$sql = "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_poster, topic_time, forum_id, topic_status, topic_type) VALUES ('$subject', " . $user_id . ", $current_time, $forum_id, " . TOPIC_UNLOCKED . ", $topic_type)";
		$db->sql_transaction('begin');
		$db->sql_query($sql);
		$topic_id = $db->sql_nextid();
	}

	// insert the post details using the topic id
	if (($mode == 'newtopic') || ($kb_config['bump_post'] == '1'))
	{
		// insert the actual post text for our new post
		$message_tmp = (($mode == 'newtopic') ? $message : $message_update_text);

		$sql = "INSERT INTO " . POSTS_TABLE . " (topic_id, forum_id, poster_id, post_username, post_subject, post_text, post_time, poster_ip, enable_bbcode, enable_html, enable_smilies, enable_autolinks_acronyms, enable_sig) VALUES ($topic_id, $forum_id, " . $user_id . ", '$username', '$subject', '$message_tmp', $current_time, '" . $db->sql_escape($user->ip) . "', $bbcode_on, $html_on, $smilies_on, $acro_auto_on, $user_attach_sig)";
		$db->sql_transaction('begin');
		$db->sql_query($sql);
		$post_id = $db->sql_nextid();

		// update the post counts etc.
		$newpostsql = ($mode == 'newtopic') ? ',forum_topics = forum_topics + 1' : '';
		$sql = "UPDATE " . FORUMS_TABLE . " SET
						forum_posts = forum_posts + 1,
						forum_last_post_id = $post_id
						$newpostsql
						WHERE forum_id = $forum_id";
		$db->sql_query($sql);

		// update the first / last post ids for the topic
		$first_post_sql = ($mode == 'newtopic') ? ", topic_first_post_id = $post_id  " : ' , topic_replies = topic_replies + 1';
		$sql = "UPDATE " . TOPICS_TABLE . " SET
						topic_last_post_id = $post_id
						$first_post_sql
						WHERE topic_id = $topic_id";
		$db->sql_query($sql);

		// update the user's post count and commit the transaction
		$sql = "UPDATE " . USERS_TABLE . " SET
						user_posts = user_posts + 1
						WHERE user_id = $user_id";
		$db->sql_query($sql);
		$db->sql_transaction('commit');

		// add the search words for our new post
		add_search_words('', $post_id, stripslashes($message), stripslashes($subject));

		// do we need to do user notification
		if (($mode != 'newtopic') && $do_notification)
		{
			$post_data = array();
			user_notification($mode, $post_data, $subject, $forum_id, $topic_id, $post_id, $notify_user);
		}
		// End if mode is update_only

		if (!class_exists('class_mcp')) include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
		if (empty($class_mcp)) $class_mcp = new class_mcp();
		$class_mcp->sync_topic_details($topic_id, $forum_id, false, false);
	}

	// Start KB addon - update original post --------------------------------------------------
	$sql = "SELECT topic_first_post_id
					FROM " . TOPICS_TABLE . "
					WHERE topic_id = '$topic_id'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$orig_post_id = $row[0];

	$sql = "UPDATE " . TOPICS_TABLE . " SET
					topic_title = '$subject'
					WHERE topic_id = '$topic_id'";
	$db->sql_transaction('begin');
	$result = $db->sql_query($sql);

	$message_tmp = (($mode == 'newtopic') ? $message : ($message . '\n\n' . $message_update_text));
	$sql = "UPDATE " . POSTS_TABLE . " SET
					post_subject = '$subject',
					post_text = '$message_tmp'
					WHERE post_id = '$orig_post_id'";
	$result = $db->sql_query($sql);
	$db->sql_transaction('commit');
	// End kb addon coe ----------------------------------------------------------

	// if all is well then return the id of our new post
	return array('post_id' => $post_id, 'topic_id' => $topic_id, 'notify' => $message_tmp);
}

// MX add-on
// Generate paths for page and standalone mode
// ...function based on original function written by Markus :-)
function this_kb_mxurl($args = '', $force_standalone_mode = false)
{
	global $page_id, $is_block;
	$mxurl = IP_ROOT_PATH . 'kb.' . PHP_EXT . ($args == '' ? '' : '?' . $args);
	return $mxurl;
}

// MX add-on
// Generate paths for page and standalone mode
// ...function based on original function written by Markus :-)
function this_kb_mxurl_search($args = '', $force_standalone_mode = false)
{
	global $page_id, $is_block;
	$mxurl = IP_ROOT_PATH . 'kb_search.' . PHP_EXT . ($args == '' ? '' : '?' . $args);
	return $mxurl;
}

// Extract all post in the comments topic

function get_kb_comments($topic_id = '', $start = -1, $show_num_comments = 0)
{
	global $db, $cache, $config, $template, $images, $user, $lang, $bbcode, $is_block, $page_id;

	if ($topic_id == '')
	{
		message_die(GENERAL_MESSAGE, 'no topic id');
	}

	$show_num_comments = $start == 0 ? $show_num_comments = $show_num_comments + 1 : $show_num_comments ;
	$start = $start > 0 ? $start = $start + 1: $start;

	// Go ahead and pull all data for this topic

	$sql = "SELECT u.username, u.user_id, u.user_active, u.user_color, u.user_level, u.user_posts, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_regdate, u.user_msnm, u.user_allow_viewemail, u.user_rank, u.user_sig, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, p.*
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u
		WHERE p.topic_id = $topic_id
			AND u.user_id = p.poster_id
			ORDER BY p.post_time ASC";

	if (($start > -1) && ($show_num_comments > 0))
	{
		$sql .= " LIMIT $start, $show_num_comments ";
	}
	$result = $db->sql_query($sql);

	$postrow = array();
	if ($row = $db->sql_fetchrow($result))
	{
		do
		{
			$postrow[] = $row;
		}
		while ($row = $db->sql_fetchrow($result));
		$db->sql_freeresult($result);
		$total_posts = sizeof($postrow);
	}
	else
	{
		include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/functions_delete.' . PHP_EXT);
		if (!class_exists('class_mcp')) include(IP_ROOT_PATH . 'includes/class_mcp.' . PHP_EXT);
		if (empty($class_mcp)) $class_mcp = new class_mcp();
		$class_mcp->sync('topic', $topic_id);
		mx_message_die(GENERAL_MESSAGE, $lang['No_posts_topic']);
	}

	// Okay, let's do the loop, yeah come on baby let's do the loop
	// and it goes like this ...

	$start == 0 ? ($i_init = 1) : ($i_init = 0);

	@include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);
	$ranks_array = $cache->obtain_ranks(false);

	for($i = $i_init; $i < $total_posts; $i++)
	{
		$poster_id = $postrow[$i]['user_id'];
		$poster = ($poster_id == ANONYMOUS) ? $lang['Guest'] : colorize_username($postrow[$i]['user_id'], $postrow[$i]['username'], $postrow[$i]['user_color'], $postrow[$i]['user_active']);

		$post_date = create_date_ip($config['default_dateformat'], $postrow[$i]['post_time'], $config['board_timezone']);

		$poster_posts = ($postrow[$i]['user_id'] != ANONYMOUS) ? $lang['Posts'] . ': ' . $postrow[$i]['user_posts'] : '';

		$poster_from = ($postrow[$i]['user_from'] && $postrow[$i]['user_id'] != ANONYMOUS) ? $lang['Location'] . ': ' . $postrow[$i]['user_from'] : '';

		$poster_joined = ($postrow[$i]['user_id'] != ANONYMOUS) ? $lang['Joined'] . ': ' . create_date($lang['JOINED_DATE_FORMAT'], $postrow[$i]['user_regdate'], $config['board_timezone']) : '';

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$user_ranks = generate_ranks($postrow[$i], $ranks_array);
		if (($user_ranks['rank_01_html'] == '') && ($user_ranks['rank_01_img_html']  == '') && ($user_ranks['rank_02_html'] == '') && ($user_ranks['rank_02_img_html'] == '') && ($user_ranks['rank_03_html'] == '') && ($user_ranks['rank_03_img_html'] == '') && ($user_ranks['rank_04_html'] == '') && ($user_ranks['rank_04_img_html'] == '') && ($user_ranks['rank_05_html'] == '') && ($user_ranks['rank_05_img_html'] == ''))
		{
			$user_ranks['rank_01_html'] = '&nbsp;';
		}
		// Mighty Gorgon - Multiple Ranks - END

		$poster_rank = $user_ranks['rank_01_html'];
		$rank_image = $user_ranks['rank_01_img_html'];

		$poster_avatar = user_get_avatar($postrow[$i]['user_id'], $postrow[$i]['user_level'], $postrow[$i]['user_avatar'], $postrow[$i]['user_avatar_type'], $postrow[$i]['user_allowavatar']);

		// Handle anon users posting with usernames

		if (($poster_id == ANONYMOUS) && ($postrow[$i]['post_username'] != ''))
		{
			$poster = $postrow[$i]['post_username'];
			$poster_rank = $lang['Guest'];
		}
		$post_subject = ($postrow[$i]['post_subject'] != '') ? $postrow[$i]['post_subject'] : '';
		$mini_post_img = $images['icon_minipost'];
		$message = $postrow[$i]['post_text'];

		// If the board has HTML off but the post has HTML
		// on then we process it, else leave it alone

		if (!$config['allow_html'])
		{
			if ($user_sig != '' && $user->data['user_allowhtml'])
			{
				$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
			}

			if ($postrow[$i]['enable_html'])
			{
				$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
			}
		}

		$message = censor_text($message);

		// Parse message and/or sig for BBCode if required
		$bbcode->allow_html = $config['allow_html'];
		$bbcode->allow_bbcode = $config['allow_bbcode'];
		$bbcode->allow_smilies = $config['allow_smilies'] && $postrow[$i]['user_allowsmile'] ? true : false;

		$message = $bbcode->parse($message);

		if($user_sig != '')
		{
			$bbcode->is_sig = true;
			$user_sig = $bbcode->parse($user_sig);
			$sig_cache[$postrow[$i]['user_id']] = $user_sig;
			$bbcode->is_sig = false;
		}

		if ($postrow[$i]['enable_autolinks_acronyms'])
		{
			$message = $bbcode->acronym_pass($message);
			$message = $bbcode->autolink_text($message, '999999');
		}
		//$message = kb_word_wrap_pass($message);
		// Editing information

		if ($postrow[$i]['post_edit_count'])
		{
			$l_edit_time_total = ($postrow[$i]['post_edit_count'] == 1) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];
			$l_edit_id = (intval($postrow[$i]['post_edit_id']) > 1) ? colorize_username($postrow[$i]['post_edit_id']) : $poster;
			$l_edited_by = '<br /><br />' . sprintf($l_edit_time_total, $l_edit_id, create_date_ip($config['default_dateformat'], $postrow[$i]['post_edit_time'], $config['board_timezone']), $postrow[$i]['post_edit_count']);
		}
		else
		{
			$l_edited_by = '';
		}

		$template->assign_block_vars('postrow', array(
				'POSTER_NAME' => $poster,
				'POSTER_FROM' => $poster_from,
				'POSTER_POSTS' => $poster_posts,
				'POSTER_JOINED' => $poster_joined,
				'POSTER_AVATAR' => $poster_avatar,
				'POSTER_FROM' => $poster_from,
				'RANK_IMAGE' => $rank_image,
				'MINI_POST_IMG' => $mini_post_img,
				'POST_DATE' => $post_date,
				'POST_SUBJECT' => $post_subject,
				'MESSAGE' => $message,
				'EDITED_MESSAGE' => $l_edited_by,
				'U_POST_ID' => $postrow[$i]['post_id']
			)
		);
	}

	if (($start == 0 && $total_posts > 1) || ($start != 0 && $total_posts > 0))
	{
		$template->assign_block_vars('switch_comments_show', array());
		return $template;
	}
}

function kb_get_data($row = '', $user_data = '', $kb_post_mode = 'add')
{
	global $db, $lang, $username, $kb_config;

		// Debug checks
		if (empty($row) || empty($user_data))
		{
			die('kb_get_data - empty pars');
		}

		$kb_author_data = get_kb_author($row['article_author_id'], true);

		$sql = "SELECT * FROM " . KB_CATEGORIES_TABLE . " WHERE category_id = '" . $row['article_category_id'] . "'";
		$result = $db->sql_query($sql);
		$cat_row = $db->sql_fetchrow($result);

		// Article data
		$kb_comment['article_id'] = $row['article_id'];
		$kb_comment['article_title'] = $row['article_title'];
		$kb_comment['article_desc'] = $row['article_description'];

		$kb_comment['article_category_id'] = $row['article_category_id'];
		$kb_comment['category_name'] = $cat_row['category_name'];
		$kb_comment['category_forum_id'] = $cat_row['comments_forum_id'];
		$kb_comment['topic_id'] = $kb_post_mode == 'edit' ? $row['topic_id'] : '';

		$kb_comment['article_type_id'] = $row['article_type'];
		$kb_comment['article_type'] = get_kb_type($kb_comment['article_type_id']);

		// Article author
		$kb_comment['article_author_id'] = $row['article_author_id'];
		$kb_comment['article_author'] = $row['article_author_id'] != -1 ? $kb_author_data['username'] : (($row['username'] == '') ? $lang['Guest'] : $row['username'])  ;
		$kb_comment['article_author_sig'] = $kb_author_data['user_attachsig'];

		// Article editor
		$kb_comment['article_editor_id'] = $user_data['user_id'];
		$kb_comment['article_editor'] = ($user_data['user_id'] != '-1') ? $user_data['username'] : (($username == '') ? $lang['Guest'] : stripslashes($username));
		$kb_comment['article_editor_sig'] = ($user_data['user_id'] != '-1') ? $user_data['user_attachsig'] : '0';

		// Debug checks
		if ($kb_post_mode == 'edit' && $kb_config['use_comments'] && empty($kb_comment['topic_id']))
		{
			die('kb_get_data - no forum topic id for comment');
		}

		return $kb_comment;
}

// Compose phpbb comment header
function kb_compose_comment($kb_comment)
{
	global $lang, $kb_custom_field;

		$search = array ("'&(quot|#34);'i", // Replace HTML entities
			"'&(amp|#38);'i",
			"'&(lt|#60);'i",
			"'&(gt|#62);'i"
			);

		$replace = array ("\"",
			"&",
			"<",
			">"
			);

		// Compose phpBB post header
		$temp_url = PORTAL_URL . this_kb_mxurl("mode=" . "article&k=" . $kb_comment['article_id']);

		$message = "[b]" . $lang['Article_title'] . ":[/b] " . preg_replace($search, $replace, $kb_comment['article_title']) . "\n";
		$message .= "[b]" . $lang['Author'] . ":[/b] " . $kb_comment['article_author'] . "\n";
		$message .= "[b]" . $lang['Article_description'] . ":[/b] [i]" . preg_replace($search, $replace, $kb_comment['article_desc']) . "[/i]\n\n";

		$message .= "[b]" . $lang['Category'] . ":[/b] " . $kb_comment['category_name'] . "\n";
		$message .= "[b]" . $lang['Article_type'] . ":[/b] " . $kb_comment['article_type'] . "\n";

		$message .= $kb_custom_field->add_comment($kb_comment['article_id']);

		$message .= "\n\n[b][url=" . append_sid($temp_url) . "]" . $lang['Read_full_article'] . "[/url][/b]";

		$message_update_text = "[i]" . $lang['Edited_Article_info'] . $kb_comment['article_editor'] . "[/i]" . "\n\n";

		return array('message' => $message, 'update_message' => $message_update_text);
}

function article_formatting($article)
{
	// Prepare ingress/preword
	$search = array ();
	$replace = array ();

	$search = array ("'\[title*?[^\[\]]*?\]'si",
		"'\[\/title*?[^\[\]]*?\]'si",
		"'\[subtitle*?[^\[\]]*?\]'si",
		"'\[\/subtitle*?[^\[\]]*?\]'si",
		"'\[subsubtitle*?[^\[\]]*?\]'si",
		"'\[\/subsubtitle*?[^\[\]]*?\]'si",
		"'\[quote*?[^\[\]]*?\]'si",
		"'\[\/quote*?[^\[\]]*?\]'si",
		"'\[abstract*?[^\[\]]*?\]'si",
		"'\[\/abstract*?[^\[\]]*?\]'si");

	$replace = array ("<span class=\"forumlink\">",
		"</span>",
		"<span class=\"topiclink\">",
		"</span>",
		"<span class=\"gensmall\"><b>",
		"</b></span>",
		"<div align=\"center\"><span class=\"gensmall\"><i>''",
		"''</i></span></div>",
		"<table cellpadding=\"20\" style=\"margin-bottom: -20px;\"><tr><td><div class=\"post-text\" style=\"font-weight: bold; font-size: 9pt;\">",
		"</div></td></td></tr></table>");

	$article = preg_replace($search, $replace, $article);

	return $article;
}

// Functions for newssuite operation mode

// get type list for adding and editing articles

function kb_get_types()
{
	global $db, $template;

	$sql = "SELECT *
				FROM " . KB_TYPES_TABLE;

	$type_result = $db->sql_query($sql);
	$item_types_array = array();
	$item_types_id_array = array();
	// $item_types_name_array = array();
	while ($type = $db->sql_fetchrow($type_result))
	{
		$item_types_array[] = 'type_' . $type['id'];
		$item_types_id_array[] = $type['id'];
		// $item_types_name_array[] = $type['type'];
	}

	return array($item_types_array, $item_types_id_array);
}

function ns_auth_cat($cat_id)
{
	return true;
}

function ns_auth_item($cat_id, $item_type = 0)
{
	return true;
}

function kb_decode_truncate_fixup($mytext = '')
{
	global $config, $newssuite_config;
	// ------------------------------------------------------------------------
	// $mytext = stripslashes($mytext);
	if ($newssuite_config['fix_up'])
	{
		$mytext = kb_magic_url($mytext);
		$mytext = kb_magic_img($mytext);
		//$mytext = kb_word_wrap_pass($mytext);
	}
	return $mytext;
}

// Replace magic urls of form http://xxx.xxx., www.xxx. and xxx@xxx.xxx.
// Cuts down displayed size of link if over 50 chars, turns absolute links
// into relative versions when the server/script path matches the link
function kb_magic_url($url)
{
	global $config;
	// $url = stripslashes($url);
	if ($url)
	{
		$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
		$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) . '/' : '/';

		$match = array();
		$replace = array();
		// relative urls for this board
		$match[] = '#(^|[\n ])' . $server_protocol . trim($config['server_name']) . $server_port . preg_replace('/^\/?(.*?)(\/)?$/', '$1', trim($config['script_path'])) . '/([^ \t\n\r <"\']+)#i';
		$replace[] = '<a href="$1" target="_blank">$1</a>';
		// matches a xxxx://aaaaa.bbb.cccc. ...
		$match[] = '#(^|[\n ])([\w]+?://.*?[^ \t\n\r<"]*)#ie';
		$replace[] = "'\$1<a href=\"\$2\" target=\"_blank\">' . ((strlen('\$2') > 25) ? substr(str_replace('http://','','\$2'), 0, 17) . '...' : '\$2') . '</a>'";
		// $replace[] = "'\$1<a href=\"\$2\" target=\"_blank\">' . ((strlen('\$2') > 25) ? substr(str_replace('http://','','\$2'), 0, 12) . ' ... ' . substr('\$2', -3) : '\$2') . '</a>'";
		// matches a "www.xxxx.yyyy[/zzzz]" kinda lazy URL thing
		$match[] = '#(^|[\n ])(www\.[\w\-]+\.[\w\-.\~]+(?:/[^ \t\n\r<"]*)?)#ie';
		$replace[] = "'\$1<a href=\"http://\$2\" target=\"_blank\">' . ((strlen('\$2') > 25) ? substr(str_replace(' ', '%20', str_replace('http://','', '\$2')), 0, 17) . '...' : '\$2') . '</a>'";
		// $replace[] = "'\$1<a href=\"http://\$2\" target=\"_blank\">' . ((strlen('\$2') > 25) ? substr(str_replace(' ', '%20', str_replace('http://','', '\$2')), 0, 12) . ' ... ' . substr('\$2', -3) : '\$2') . '</a>'";
		// matches an email@domain type address at the start of a line, or after a space.
		$match[] = '#(^|[\n ])([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)#ie';
		$replace[] = "'\$1<a href=\"mailto:\$2\">' . ((strlen('\$2') > 25) ? substr('\$2', 0, 15) . ' ... ' . substr('\$2', -5) : '\$2') . '</a>'";

		$url = preg_replace($match, $replace, $url);
		// Also fix already tagged links
		$url = preg_replace("/<a href=(.*?)>(.*?)<\/a>/ie", "(strlen(\"\\2\") > 25 && !eregi(\"<\", \"\\2\")) ? '<a href='.stripslashes(\"\\1\").'>'.substr(str_replace(\"http://\",\"\",\"\\2\"), 0, 17) . '...</a>' : '<a href='.stripslashes(\"\\1\").'>'.\"\\2\".'</a>'", $url);
		// $url = preg_replace("/<a href=(.*?)>(.*?)<\/a>/ie", "(strlen(\"\\2\") > 25 && !eregi(\"<\", \"\\2\")) ? '<a href='.stripslashes(\"\\1\").'>'.substr(str_replace(\"http://\",\"\",\"\\2\"), 0, 12) . ' ... ' . substr(\"\\2\", -3).'</a>' : '<a href='.stripslashes(\"\\1\").'>'.\"\\2\".'</a>'", $url);
		return $url;
	}
	return $url;
}

// Validates the img for block_size and resizes when needed
// run within a div tag to ensure the table layout is not broken
function kb_magic_img($img)
{
	global $config, $block_size;
	// $img = stripslashes($img);
	$image_size = '300';
	if ($img)
	{
		// Also fix already tagged links
		// $img = preg_replace("/<img src=(.*?)(|border(.*?)|alt(.*?))>/ie", "'<br /><br /><div style="text-align: center;"><img src='.stripslashes(\"\\1\").' width=\"'.makeImgWidth(trim(stripslashes(\"\\1\"))).'\" ></div><br />'", $img);
		$img = preg_replace("/<img src=(.*?)>/ie", "(substr_count(\"\\1\", \"smiles\") > 0) ? '<img src='.stripslashes(\"\\1\").'>' :

		'<div style=\"overflow: hidden; margin: 0px; padding: 0px; float: left;\">
		<img class=\"noenlarge\" src='.stripslashes(\"\\1\").' border=\"0\" OnLoad=\"if(this.width > $image_size) { this.width = $image_size }\" onclick = \"full_img(this.src)\" alt=\" Click to enlarge \">
		</div>'", $img);
		return $img;
	}
	return $img;
}

// Force Word Wrapping (by TerraFrost)
function kb_word_wrap_pass($message)
{
	$tempText = "";
	$finalText = "";
	$curCount = $tempCount = 0;
	$longestAmp = 9;
	$inTag = false;
	$ampText = "";

	for ($num = 0;$num < strlen($message);$num++)
	{
		$curChar = $message{$num};

		if ($curChar == "<")
		{
			for ($snum = 0;$snum < strlen($ampText);$snum++)
			kb_addWrap($ampText{$snum}, $ampText{$snum+1}, $finalText, $tempText, $curCount, $tempCount);
			$ampText = "";
			$tempText .= "<";
			$inTag = true;
		}
		elseif ($inTag && $curChar == ">")
		{
			$tempText .= ">";
			$inTag = false;
		}
		elseif ($inTag)
			$tempText .= $curChar;
		elseif ($curChar == "&")
		{
			for ($snum = 0;$snum < strlen($ampText);$snum++)
			kb_addWrap($ampText{$snum}, $ampText{$snum+1}, $finalText, $tempText, $curCount, $tempCount);
			$ampText = "&";
		}elseif (strlen($ampText) < $longestAmp && $curChar == ";" &&
				(strlen(html_entity_decode("$ampText;")) == 1 || preg_match('/^&#[0-9][0-9]*$/', $ampText)))
		{
			kb_addWrap("$ampText;", $message{$num+1}, $finalText, $tempText, $curCount, $tempCount);
			$ampText = "";
		}
		elseif (strlen($ampText) >= $longestAmp || $curChar == ";")
		{
			for ($snum = 0;$snum < strlen($ampText);$snum++)
			kb_addWrap($ampText{$snum}, $ampText{$snum+1}, $finalText, $tempText, $curCount, $tempCount);
			kb_addWrap($curChar, $message{$num+1}, $finalText, $tempText, $curCount, $tempCount);
			$ampText = "";
		}
		elseif (strlen($ampText) != 0 && strlen($ampText) < $longestAmp)
			$ampText .= $curChar;
		else
			kb_addWrap($curChar, $message{$num+1}, $finalText, $tempText, $curCount, $tempCount);
	}

	return $finalText . $tempText;
}

function kb_addWrap($curChar, $nextChar, &$finalText, &$tempText, &$curCount, &$tempCount)
{
	$softHyph = (!preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) ? '&#8203;': '&shy;';
	$maxChars = 10;
	$wrapProhibitedChars = "([{!;,:?}])";

	if ($curChar == " " || $curChar == "\n")
	{
		$finalText .= $tempText . $curChar;
		$tempText = "";
		$curCount = 0;
		$curChar = "";
	}elseif ($curCount >= $maxChars)
	{
		$finalText .= $tempText . $softHyph;
		$tempText = "";
		$curCount = 1;
	}
	else
	{
		$tempText .= $curChar;
		$curCount++;
	}
	// the following code takes care of (unicode) characters prohibiting non-mandatory breaks directly before them.
	// $curChar isn't a " " or "\n"
	if ($tempText != "" && $curChar != "")
		$tempCount++;
	// $curChar is " " or "\n", but $nextChar prohibits wrapping.
	elseif (($curCount == 1 && strstr($wrapProhibitedChars, $curChar) !== false) ||
			($curCount == 0 && $nextChar != "" && $nextChar != " " && $nextChar != "\n" && strstr($wrapProhibitedChars, $nextChar) !== false))
		$tempCount++;
	// $curChar and $nextChar aren't both either " " or "\n"
	elseif (!($curCount == 0 && ($nextChar == " " || $nextChar == "\n")))
		$tempCount = 0;

	if ($tempCount >= $maxChars && $tempText == "")
	{
		$finalText .= "&nbsp;";
		$tempCount = 1;
		$curCount = 2;
	}

	if ($tempText == "" && $curCount > 0)
		$finalText .= $curChar;
}


// Just to be safe ;o)
if(!defined('ENT_COMPAT'))
{
	define('ENT_COMPAT', 2);
}
if(!defined('ENT_NOQUOTES'))
{
	define('ENT_NOQUOTES', 0);
}
if(!defined('ENT_QUOTES'))
{
	define('ENT_QUOTES', 3);
}

?>