<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['kb']['dir'] . 'common.' . PHP_EXT);

// Mode
$mode = request_var('mode', '');

$search_keywords = request_var('search_keywords', '', true);
$mode = empty($search_keywords) ? '' : $mode;

$search_id = request_var('search_id', '');
if (!empty($search_id))
{
	$mode = 'results';
}

$show_results = request_var('show_results', 'posts');
$search_terms = request_var('search_terms', '');
$search_terms = ($search_terms == 'all') ? 1 : 0;

$search_fields = request_var('search_fields', '');
$search_fields = ($search_fields == 'all') ? 1 : 0;

$sort_dir = request_var('sort_dir', 'DESC');
$sort_dir = check_var_value($sort_dir, array('DESC', 'ASC'));

$sort_by = request_var('sort_by', 0);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

switch ($mode)
{
	case 'results':

		$store_vars = array('search_results', 'total_match_count', 'split_search', 'sort_by', 'sort_dir', 'show_results', 'return_chars');

		// Cycle through options ...
		if (($search_id == '') || ($search_keywords != ''))
		{

			$stopword_array = @file(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/search_stopwords.txt');
			$synonym_array = @file(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/search_synonyms.txt');

			$split_search = array();
			$split_search = (!strstr($multibyte_charset, $lang['ENCODING'])) ? split_words(clean_words('search', stripslashes($search_keywords), $stopword_array, $synonym_array), 'search') : split(' ', $search_keywords);

			$search_msg_only = (!$search_fields) ? "AND m.title_match = 0" : ((strstr($multibyte_charset, $lang['ENCODING'])) ? '' : '');

			$word_count = 0;
			$current_match_type = 'or';

			$word_match = array();
			$result_list = array();

			for($i = 0; $i < sizeof($split_search); $i++)
			{
				switch ($split_search[$i])
				{
					case 'and':
						$current_match_type = 'and';
						break;

					case 'or':
						$current_match_type = 'or';
						break;

					case 'not':
						$current_match_type = 'not';
						break;

					default:
						if (!empty($search_terms))
						{
							$current_match_type = 'and';
						}

						if (!strstr($multibyte_charset, $lang['ENCODING']))
						{
							$match_word = str_replace('*', '%', $split_search[$i]);
							$sql = "SELECT m.article_id
											FROM " . KB_WORD_TABLE . " w, " . KB_MATCH_TABLE . " m
											WHERE w.word_text LIKE '" . $db->sql_escape($match_word) . "'
												AND m.word_id = w.word_id
												AND w.word_common <> 1
											$search_msg_only";
						}
						else
						{
							$match_word = addslashes('%' . str_replace('*', '', $split_search[$i]) . '%');
							$search_msg_only = ($search_fields) ? "OR article_title LIKE '" . $db->sql_escape($match_word) . "'" : '';
							$sql = "SELECT article_id
									FROM " . KB_ARTICLE_TABLE . "
								WHERE article_body  LIKE '" . $db->sql_escape($match_word) . "'
								$search_msg_only";
						}
						$result = $db->sql_query($sql);

						$kb_row = array();
						while ($temp_row = $db->sql_fetchrow($result))
						{
							$kb_row[$temp_row['post_id']] = 1;

							if (!$word_count)
							{
								$result_list[$temp_row['article_id']] = 1;
							}
							elseif ($current_match_type == 'or')
							{
								$result_list[$temp_row['article_id']] = 1;
							}
							elseif ($current_match_type == 'not')
							{
								$result_list[$temp_row['article_id']] = 0;
							}
						}

						if ($current_match_type == 'and' && $word_count)
						{
							@reset($result_list);
							while (list($article_id, $match_count) = @each($result_list))
							{
								if (!$kb_row[$post_id])
								{
									$result_list[$post_id] = 0;
								}
							}
						}

						$word_count++;

						$db->sql_freeresult($result);
				}
			}

			@reset($result_list);

			$search_ids = array();
			while (list($article_id, $matches) = each($result_list))
			{
				if ($matches)
				{
					$search_ids[] = $article_id;
				}
			}

			unset($result_list);
			$total_match_count = sizeof($search_ids);

			// Store new result data

			$search_results = implode(', ', $search_ids);
			$per_page = $config['topics_per_page'];

			// Combine both results and search data (apart from original query)
			// so we can serialize it and place it in the DB

			$store_search_data = array();

			// Limit the character length (and with this the results displayed at all following pages) to prevent
			// truncated result arrays. Normally, search results above 12000 are affected.
			// - to include or not to include
			/*
				$max_result_length = 60000;
				if (strlen($search_results) > $max_result_length)
				{
					$search_results = substr($search_results, 0, $max_result_length);
					$search_results = substr($search_results, 0, strrpos($search_results, ','));
					$total_match_count = count(explode(', ', $search_results));
				}
				*/

			for($i = 0; $i < sizeof($store_vars); $i++)
			{
				$store_search_data[$store_vars[$i]] = $$store_vars[$i];
			}

			$result_array = serialize($store_search_data);
			unset($store_search_data);

			mt_srand ((double) microtime() * 1000000);
			$search_id = mt_rand();

			$sql = "UPDATE " . KB_SEARCH_TABLE . "
							SET search_id = " . $db->sql_escape($search_id) . ", search_array = '" . $db->sql_escape($result_array) . "'
							WHERE session_id = '" . $userdata['session_id'] . "'";
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			$affectedrows = $db->sql_affectedrows();
			if (!$result || !$affectedrow)
			{
				$sql = "INSERT INTO " . KB_SEARCH_TABLE . " (search_id, session_id, search_array)
								VALUES($search_id, '" . $userdata['session_id'] . "', '" . $db->sql_escape($result_array) . "')";
				$result = $db->sql_query($sql);
			}
		}
		else
		{
			$search_id = intval($search_id);
			if ($search_id)
			{
				$sql = "SELECT search_array
								FROM " . KB_SEARCH_TABLE . "
								WHERE search_id = $search_id
									AND session_id = '" . $userdata['session_id'] . "'";
				$result = $db->sql_query($sql);

				if ($kb_row = $db->sql_fetchrow($result))
				{
					$search_data = unserialize($kb_row['search_array']);
					for($i = 0; $i < sizeof($store_vars); $i++)
					{
						$$store_vars[$i] = $search_data[$store_vars[$i]];
					}
				}
			}
		}

		// Look up data ...

		if ($search_results != '')
		{
			$sql = "SELECT t.*, u.username, u.user_id, u.user_active, u.user_color
							FROM " . KB_ARTICLES_TABLE . " t, " . USERS_TABLE . " u
							WHERE t.article_id IN ($search_results)
								AND u.user_id = t.article_author_id";

			$per_page = $config['topics_per_page'];

			$sql .= " ORDER BY t.article_title $sort_dir LIMIT $start, " . $per_page;
			$result = $db->sql_query($sql);

			$searchset = array();
			while ($kb_row = $db->sql_fetchrow($result))
			{
				$searchset[] = $kb_row;
			}
			$db->sql_freeresult($result);
		}

		// Output header

		$l_search_matches = ($total_match_count == 1) ? sprintf($lang['Found_search_match'], $total_match_count) : sprintf($lang['Found_search_matches'], $total_match_count);

		$meta_content['page_title'] = $lang['KB_title'] . ' - ' . $lang['Search'];
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		if (!$is_block)
		{
			$nav_server_url = create_server_url();
			$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('kb.' . PHP_EXT) . '">' . $lang['KB_title'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="#">' .  $l_search_matches . '</a>';
			page_header($meta_content['page_title'], true);
		}

		//include(KB_ROOT_PATH . 'includes/kb_header.' . PHP_EXT);

		$template->set_filenames(array('body' => $class_plugins->get_tpl_file(KB_TPL_PATH, 'kb_search_results.tpl')));

		$template->assign_vars(array(
			'L_SEARCH_MATCHES' => $l_search_matches,
			'L_ARTICLE' => $lang['Article']
			)
		);

		$highlight_active = '';
		$highlight_match = array();
		for($j = 0; $j < sizeof($split_search); $j++)
		{
			$split_word = $split_search[$j];

			if (($split_word != 'and') && ($split_word != 'or') && ($split_word != 'not'))
			{
				$highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $split_word) . ')\b#is';
				$highlight_active .= " " . $split_word;

				for ($k = 0; $k < sizeof($synonym_array); $k++)
				{
					list($replace_synonym, $match_synonym) = split(' ', trim(strtolower($synonym_array[$k])));

					if ($replace_synonym == $split_word)
					{
						$highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $replace_synonym) . ')\b#is';
						$highlight_active .= ' ' . $match_synonym;
					}
				}
			}
		}

		$highlight_active = urlencode(trim($highlight_active));

		for($i = 0; $i < sizeof($searchset); $i++)
		{
			$article_url = append_sid('kb.' . PHP_EXT . '?mode=article&amp;k=' . $searchset[$i]['article_id'] . '&amp;highlight=' . $highlight_active, true);

			$post_date = create_date_ip($config['default_dateformat'], $searchset[$i]['article_date'], $config['board_timezone']);

			$message = $searchset[$i]['article_body'];
			$article_title = $searchset[$i]['article_title'];
			$article_id = $searchset[$i]['article_id'];

			$kb_cat = get_kb_cat($searchset[$i]['article_category_id']);
			$temp_url = append_sid(this_kb_mxurl('mode=cat&amp;cat=' . $searchset[$i]['article_category_id'], true));
			$category = '<a href="' . $temp_url . '" class="name">' . $kb_cat['category_name'] . '</a>';

			$type = get_kb_type($searchset[$i]['article_type']);

			$message = '';

			$article_title = censor_text($searchset[$i]['article_title']);

			//$article_author = '<a href="' . append_sid(IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $searchset[$i]['user_id']) . '" class="name">';
			//$article_author .= $searchset[$i]['username'];
			//$article_author .= '</a>';
			$article_author = colorize_username ($searchset[$i]['user_id'], $searchset[$i]['username'], $searchset[$i]['user_color'], $searchset[$i]['user_active']);
			$template->assign_block_vars('searchresults', array('ARTICLE_ID' => $article_id,
				'ARTICLE_AUTHOR' => $article_author,
				'ARTICLE_TITLE' => $article_title,
				'ARTICLE_DESCRIPTION' => $searchset[$i]['article_description'],
				'ARTICLE_CATEGORY' => $category,
				'ARTICLE_TYPE' => $type,

				'U_VIEW_ARTICLE' => $article_url
				)
			);
		}


		$base_url = this_kb_mxurl_search('search_id=' . $search_id, true);

		$template->assign_vars(array('PAGINATION' => generate_pagination($base_url, $total_match_count, $per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $per_page) + 1), ceil($total_match_count / $per_page)),

			'L_AUTHOR' => $lang['Author'],
			'L_MESSAGE' => $lang['Message'],
			'L_TOPICS' => $lang['Article'],
			'L_TYPE' => $lang['Article_type'],
			'L_CATEGORY' => $lang['Category']
			)
		);

		break;

	default:

		// Output the basic page
		$meta_content['page_title'] = $lang['KB_title'] . ' - ' . $lang['Search'];
		$meta_content['description'] = '';
		$meta_content['keywords'] = '';
		if (!$is_block)
		{
			$nav_server_url = create_server_url();
			$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('kb.' . PHP_EXT) . '">' . $lang['KB_title'] . '</a>' . $lang['Nav_Separator'] . '<a class="nav-current" href="#">' .  $lang['Search_query'] . '</a>';
			page_header($meta_content['page_title'], true);
		}

		//include(KB_ROOT_PATH . 'includes/kb_header.' . PHP_EXT);

		$template->set_filenames(array('body' => $class_plugins->get_tpl_file(KB_TPL_PATH, 'kb_search_body.tpl')));

		$template->assign_vars(array(
				'L_SEARCH_QUERY' => $lang['Search_query'],
				'L_SEARCH_KEYWORDS' => $lang['Search_keywords'],
				'L_SEARCH_KEYWORDS_EXPLAIN' => $lang['Search_keywords_explain'],
				'L_SEARCH_ANY_TERMS' => $lang['Search_for_any'],
				'L_SEARCH_ALL_TERMS' => $lang['Search_for_all'],

				'S_SEARCH_ACTION' => append_sid(this_kb_mxurl_search('mode=results', true)),
				'S_HIDDEN_FIELDS' => '<input type="hidden" name="search_fields" value="all" />',
				'S_SEARCH' => $lang['Search']
				)
			);

		break;
}

$template->pparse('body');

if (!$is_block)
{
	page_footer(true, '', true);
}

?>