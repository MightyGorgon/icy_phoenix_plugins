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

/**
* Blogs class
*/
class class_blogs
{

	var $blog_vars = array();

	var $blogs_list_table = 'blogs';
	var $blogs_topics_table = 'blogs_topics';
	var $blogs_posts_table = 'blogs_posts';

	var $blog_id_var = 'blid';
	var $blog_id = 0;
	var $blog_id_append = '';

	var $topic_id_var = 'blt';
	var $topic_id = 0;
	var $topic_id_append = '';

	var $post_id_var = 'blp';
	var $post_id = 0;
	var $post_id_append = '';
	var $post_id_append_url = '';

	/**
	* Initialize vars
	*/
	function var_init()
	{
		$blog_id = request_var($this->blog_id_var, 0);
		$this->blog_id = ($blog_id < 0) ? 0 : $blog_id;

		$topic_id = request_var($this->topic_id_var, 0);
		$this->topic_id = ($topic_id < 0) ? 0 : $topic_id;

		$post_id = request_var($this->post_id_var, 0);
		$this->post_id = ($post_id < 0) ? 0 : $post_id;

		$this->blog_id_append = (!empty($this->blog_id) ? ($this->blog_id_var . '=' . $this->blog_id) : '');
		$this->topic_id_append = (!empty($this->topic_id) ? ($this->topic_id_var . '=' . $this->topic_id) : '');
		$this->post_id_append = (!empty($this->post_id) ? ($this->post_id_var . '=' . $this->post_id) : '');
		$this->post_id_append_url = (!empty($this->post_id) ? ('#' . $this->post_id_var . $this->post_id) : '');

		return true;
	}

	/**
	* Get blog data
	*/
	function get_blog_data($blog_id)
	{
		global $db, $cache, $config, $lang;

		$blog_id = (int) $blog_id;

		$sql = "SELECT b.*
						FROM " . $this->blogs_list_table . " b
						WHERE b.blog_id = " . $blog_id;
		$result = $db->sql_query($sql);
		$blog_data = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $blog_data;
	}

	/**
	* Get articles
	*/
	function get_articles($blog_id, $order_by = '', $start = 0, $n_items = 0)
	{
		global $db, $cache, $config, $lang;

		$articles = array();
		$blog_id = (int) $blog_id;

		if (!empty($blog_id))
		{
			$order_sql = " ORDER BY " . (!empty($order_by) ? $order_by : "t.topic_time DESC");
			$limit_sql = (!empty($n_items) ? (" LIMIT " . (!empty($start) ? ($start . ", " . $n_items) : ($n_items . " "))) : "");

			$sql = "SELECT t.*, p.*
							FROM " . $this->blogs_topics_table . " t, " . $this->blogs_posts_table . " p
							WHERE t.blog_id = " . $blog_id . "
								AND p.post_id = t.topic_first_post_id"
							. $order_sql
							. $limit_sql;
			$result = $db->sql_query($sql);
			$articles = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
		}

		return $articles;
	}

	/**
	* Get next / prev articles
	*/
	function get_next_prev_articles($blog_id, $topic_id, $n_items = 1)
	{
		global $db, $cache, $config, $lang;

		$articles = array();
		$blog_id = (int) $blog_id;
		$topic_id = (int) $topic_id;
		$n_items = !empty($n_items) ? (int) $n_items : 1;

		if (!empty($topic_id))
		{
			$sql = "SELECT t.topic_id, t.topic_title
							FROM " . $this->blogs_topics_table . " t
							WHERE t.blog_id = " . $blog_id . "
								AND t.topic_id > " . $topic_id . "
							ORDER BY t.topic_id ASC LIMIT " . $n_items;
			$result = $db->sql_query($sql);
			$articles['next'] = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			$sql = "SELECT t.topic_id, t.topic_title
							FROM " . $this->blogs_topics_table . " t
							WHERE t.blog_id = " . $blog_id . "
								AND t.topic_id < " . $topic_id . "
							ORDER BY t.topic_id DESC LIMIT " . $n_items;
			$result = $db->sql_query($sql);
			$articles['prev'] = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
		}

		return $articles;
	}

	/**
	* Get article
	*/
	function get_article($topic_id)
	{
		global $db, $cache, $config, $lang;

		$article = array();
		$topic_id = (int) $topic_id;

		if (!empty($topic_id))
		{
			$sql = "SELECT t.*, p.*
							FROM " . $this->blogs_topics_table . " t, " . $this->blogs_posts_table . " p
							WHERE t.topic_id = " . $topic_id . "
								AND p.post_id = t.topic_first_post_id";
			$result = $db->sql_query($sql);
			$article = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
		}

		return $article;
	}

	/**
	* Get total articles
	*/
	function get_total_articles($blog_id)
	{
		global $db, $cache, $config, $lang;

		$blog_id = (int) $blog_id;

		$sql = "SELECT COUNT(t.topic_id) AS total_articles
						FROM " . $this->blogs_topics_table . " t
						WHERE t.blog_id = " . $blog_id;
		$result = $db->sql_query($sql);
		$articles_data = $db->sql_fetchrow($result);
		$total_articles = $articles_data['total_articles'];
		$db->sql_freeresult($result);

		return $total_articles;
	}

	/**
	* Get comments
	*/
	function get_comments($topic_id, $user_details = true, $order_by = '', $start = 0, $n_items = 0)
	{
		global $db, $cache, $config, $lang;

		$comments = array();
		$topic_id = (int) $topic_id;

		$sql_select_extra = "";
		$sql_from_extra = "";
		$sql_where_extra = "";
		if ($user_details)
		{
			$sql_select_extra = ", u.username, u.user_active, u.user_color";
			$sql_from_extra = ", " . USERS_TABLE . " u";
			$sql_where_extra = " AND u.user_id = p.poster_id ";
		}

		if (!empty($topic_id))
		{
			$order_sql = " ORDER BY " . (!empty($order_by) ? $order_by : "p.post_time ASC");
			$limit_sql = (!empty($n_items) ? (" LIMIT " . (!empty($start) ? ($start . ", " . $n_items) : ($n_items . " "))) : "");

			$sql = "SELECT p.*" . $sql_select_extra . "
							FROM " . $this->blogs_topics_table . " t, " . $this->blogs_posts_table . " p" . $sql_from_extra . "
							WHERE p.topic_id = " . $topic_id . "
								AND t.topic_id = p.topic_id
								AND p.post_id <> t.topic_first_post_id"
							. $sql_where_extra
							. $order_sql
							. $limit_sql;
			$result = $db->sql_query($sql);
			$comments = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
		}

		return $comments;
	}

	/**
	* Get comment
	*/
	function get_comment($post_id)
	{
		global $db, $cache, $config, $lang;

		$comment = array();
		$post_id = (int) $post_id;

		if (!empty($post_id))
		{
			$sql = "SELECT p.*
							FROM " . $this->blogs_posts_table . " p
							WHERE p.post_id = " . $post_id;
			$result = $db->sql_query($sql);
			$comment = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
		}

		return $comment;
	}

	/**
	* Get total comments
	*/
	function get_total_comments($topic_id)
	{
		global $db, $cache, $config, $lang;

		$topic_id = (int) $topic_id;

		$sql = "SELECT COUNT(p.post_id) AS total_comments
						FROM " . $this->blogs_posts_table . " p
						WHERE p.topic_id = " . $topic_id;
		$result = $db->sql_query($sql);
		$comments_data = $db->sql_fetchrow($result);
		$total_comments = $comments_data['total_comments'];
		$db->sql_freeresult($result);

		return $total_comments;
	}

	/**
	* Submit article
	*/
	function submit_article(&$article_data, &$comment_data, $mode = 'insert', $post_type = 'article')
	{
		global $db, $cache, $config, $lang;

		if ($mode == 'insert')
		{
			// Before submitting the article... check if the user is flooding
			$this->check_user_flood(false);

			$sql = "INSERT INTO " . $this->blogs_topics_table . " " . $db->sql_build_insert_update($article_data, true);
			$result = $db->sql_query($sql);
			$article_data['topic_id'] = $db->sql_nextid();
			$comment_data['topic_id'] = $article_data['topic_id'];
		}
		else
		{
			$sql = "UPDATE " . $this->blogs_topics_table . " SET
				" . $db->sql_build_insert_update($article_data, false) . "
				WHERE topic_id = " . (int) $article_data['topic_id'];
			$result = $db->sql_query($sql);
		}
		$this->submit_comment($article_data, $comment_data, $mode, $post_type);

		return true;
	}

	/**
	* Submit comment
	*/
	function submit_comment(&$article_data, &$comment_data, $mode = 'insert', $post_type = 'article')
	{
		global $db, $cache, $config, $lang;

		if ($mode == 'insert')
		{
			// Before submitting the post... check if the user is flooding... we check this only if we are posting a comment
			if ($post_type == 'comment')
			{
				$this->check_user_flood(false);
			}

			$sql = "INSERT INTO " . $this->blogs_posts_table . " " . $db->sql_build_insert_update($comment_data, true);
			$result = $db->sql_query($sql);
			$comment_data['post_id'] = $db->sql_nextid();
			$article_data['post_id'] = $comment_data['post_id'];

			$update_info = array(
				'topic_last_post_id' => $article_data['post_id'],
				'topic_last_post_time' => $article_data['topic_last_post_time'],
				'topic_last_poster_id' => $article_data['topic_last_poster_id'],
				'topic_last_poster_name' => $article_data['topic_last_poster_name'],
				'topic_last_poster_color' => $article_data['topic_last_poster_color']
			);

			$sql_topics_update = '';
			if ($post_type == 'article')
			{
				$update_info_extra = array(
					'topic_first_post_id' => $article_data['post_id'],
					'topic_first_post_time' => $article_data['topic_first_post_time'],
					'topic_first_poster_id' => $article_data['topic_first_poster_id'],
					'topic_first_poster_name' => $article_data['topic_first_poster_name'],
					'topic_first_poster_color' => $article_data['topic_first_poster_color']
				);
				$update_info = array_merge($update_info_extra, $update_info);
			}
			else
			{
				$sql_topics_update = ", topic_replies = topic_replies + 1 ";
			}

			$sql = "UPDATE " . $this->blogs_topics_table . " SET
				" . $db->sql_build_insert_update($update_info, false) . $sql_topics_update . "
				WHERE topic_id = " . (int) $comment_data['topic_id'];
			$result = $db->sql_query($sql);
		}
		else
		{
			$sql = "UPDATE " . $this->blogs_posts_table . " SET
				" . $db->sql_build_insert_update($comment_data, false) . "
				WHERE post_id = " . (int) $comment_data['post_id'];
			$result = $db->sql_query($sql);
		}

		return true;
	}

	/**
	* Increase view counter
	*/
	function increase_view_counter($topic_id)
	{
		global $db, $cache, $config, $lang;

		$sql = "UPDATE " . $this->blogs_topics_table . " SET topic_views = topic_views + 1 WHERE topic_id = " . (int) $topic_id;
		$result = $db->sql_query($sql);

		return true;
	}

	/*
	* Remove an article
	*/
	function remove_article($topic_id)
	{
		global $db;

		$db->sql_transaction('begin');

		$sql = "DELETE FROM " . $this->blogs_topics_table . " WHERE topic_id = " . $topic_id;
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM " . $this->blogs_posts_table . " WHERE topic_id = " . $topic_id;
		$result = $db->sql_query($sql);

		$db->sql_transaction('commit');

		return true;
	}

	/*
	* Remove a comment
	*/
	function remove_comment($post_id)
	{
		global $db;

		$db->sql_transaction('begin');

		$sql = "DELETE FROM " . $this->blogs_posts_table . " WHERE post_id = " . $post_id;
		$result = $db->sql_query($sql);

		$this->resync_articles();

		$db->sql_transaction('commit');

		return true;
	}

	/**
	* Resync articles posters info
	*/
	function resync_articles()
	{
		global $db, $cache, $config, $lang;

		$sql = "SELECT t.topic_id, t.topic_replies, t.topic_first_post_id, t.topic_last_post_id, COUNT(p.post_id) - 1 AS new_replies, MIN(p.post_id) AS new_first_post_id, MAX(p.post_id) AS new_last_post_id
			FROM " . $this->blogs_topics_table . " t, " . $this->blogs_posts_table . " p
			WHERE t.topic_id = p.topic_id
			GROUP BY t.topic_id, t.topic_replies, t.topic_first_post_id, t.topic_last_post_id
			HAVING new_replies <> t.topic_replies OR
				new_first_post_id <> t.topic_first_post_id OR
				new_last_post_id <> t.topic_last_post_id";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$sql2 = "UPDATE " . $this->blogs_topics_table . "
				SET topic_replies = " . $row['new_replies'] . ",
					topic_first_post_id = " . $row['new_first_post_id'] . ",
					topic_last_post_id = " . $row['new_last_post_id'] . "
				WHERE topic_id = " . $row['topic_id'];
			$result2 = $db->sql_query($sql2);
		}
		$db->sql_freeresult($result);

		$sql = "UPDATE " . $this->blogs_topics_table . " t, " . $this->blogs_posts_table . " p, " . $this->blogs_posts_table . " p2, " . USERS_TABLE . " u, " . USERS_TABLE . " u2
			SET t.topic_first_post_id = p.post_id, t.topic_first_post_time = p.post_time, t.topic_first_poster_id = p.poster_id, t.topic_first_poster_name = u.username, t.topic_first_poster_color = u.user_color, t.topic_last_post_id = p2.post_id, t.topic_last_post_time = p2.post_time, t.topic_last_poster_id = p2.poster_id, t.topic_last_poster_name = u2.username, t.topic_last_poster_color = u2.user_color
			WHERE t.topic_first_post_id = p.post_id
				AND p.poster_id = u.user_id
				AND t.topic_last_post_id = p2.post_id
				AND p2.poster_id = u2.user_id";
		$db->sql_query($sql);

		return true;
	}

	/*
	* Check if the user is flooding
	*/
	function check_user_flood($return = false)
	{
		global $db, $cache, $config, $user, $lang;

		if (($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD))
		{
			$where_sql = ($user->data['user_id'] == ANONYMOUS) ? ("poster_ip = '" . $db->sql_escape($user->ip) . "'") : ('poster_id = ' . $user->data['user_id']);
			$sql = "SELECT MAX(post_time) AS last_post_time
				FROM " . $this->blogs_posts_table . "
				WHERE $where_sql";
			$result = $db->sql_query($sql);
			if ($row = $db->sql_fetchrow($result))
			{
				$ip_post_flood_time = (int) $row['last_post_time'] + (int) $config['flood_interval'];
				if ($ip_post_flood_time >= time())
				{
					if ($return)
					{
						return true;
					}
					else
					{
						message_die(GENERAL_MESSAGE, $lang['Flood_Error']);
					}
				}
			}
		}

		return false;
	}

}

?>