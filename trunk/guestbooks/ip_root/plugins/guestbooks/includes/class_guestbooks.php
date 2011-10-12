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
class class_guestbooks
{

	var $guestbook_vars = array();

	var $guestbooks_list_table = 'guestbooks';
	var $guestbooks_posts_table = 'guestbooks_posts';

	var $guestbook_id_var = 'gid';
	var $guestbook_id = 0;
	var $guestbook_id_append = '';

	var $post_id_var = 'gp';
	var $post_id = 0;
	var $post_id_append = '';
	var $post_id_append_url = '';

	/**
	* Initialize vars
	*/
	function var_init()
	{
		$guestbook_id = request_var($this->guestbook_id_var, 0);
		$this->guestbook_id = ($guestbook_id < 0) ? 0 : $guestbook_id;

		$post_id = request_var($this->post_id_var, 0);
		$this->post_id = ($post_id < 0) ? 0 : $post_id;

		$this->guestbook_id_append = (!empty($this->guestbook_id) ? ($this->guestbook_id_var . '=' . $this->guestbook_id) : '');
		$this->post_id_append = (!empty($this->post_id) ? ($this->post_id_var . '=' . $this->post_id) : '');
		$this->post_id_append_url = (!empty($this->post_id) ? ('#' . $this->post_id_var . $this->post_id) : '');

		return true;
	}

	/**
	* Get guestbook data
	*/
	function get_guestbook_data($guestbook_id)
	{
		global $db, $cache, $config, $lang;

		$guestbook_id = (int) $guestbook_id;

		$sql = "SELECT g.*
						FROM " . $this->guestbooks_list_table . " g
						WHERE g.guestbook_id = " . $guestbook_id;
		$result = $db->sql_query($sql);
		$guestbook_data = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $guestbook_data;
	}

	/**
	* Get posts
	*/
	function get_posts($guestbook_id, $user_details = true, $order_by = '', $start = 0, $n_items = 0)
	{
		global $db, $cache, $config, $lang;

		$posts = array();
		$guestbook_id = (int) $guestbook_id;

		$sql_select_extra = "";
		$sql_from_extra = "";
		$sql_where_extra = "";
		if ($user_details)
		{
			$sql_select_extra = ", u.username, u.user_active, u.user_color";
			$sql_from_extra = ", " . USERS_TABLE . " u";
			$sql_where_extra = " AND u.user_id = p.poster_id ";
		}

		if (!empty($guestbook_id))
		{
			$order_sql = " ORDER BY " . (!empty($order_by) ? $order_by : "p.post_time ASC");
			$limit_sql = (!empty($n_items) ? (" LIMIT " . (!empty($start) ? ($start . ", " . $n_items) : ($n_items . " "))) : "");

			$sql = "SELECT p.*" . $sql_select_extra . "
							FROM " . $this->guestbooks_posts_table . " p" . $sql_from_extra . "
							WHERE p.guestbook_id = " . $guestbook_id
							. $sql_where_extra
							. $order_sql
							. $limit_sql;
			$result = $db->sql_query($sql);
			$posts = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
		}

		return $posts;
	}

	/**
	* Get post
	*/
	function get_post($post_id)
	{
		global $db, $cache, $config, $lang;

		$post = array();
		$post_id = (int) $post_id;

		if (!empty($post_id))
		{
			$sql = "SELECT p.*
							FROM " . $this->guestbooks_posts_table . " p
							WHERE p.post_id = " . $post_id;
			$result = $db->sql_query($sql);
			$post = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
		}

		return $post;
	}

	/**
	* Get total posts
	*/
	function get_total_posts($guestbook_id)
	{
		global $db, $cache, $config, $lang;

		$guestbook_id = (int) $guestbook_id;

		$sql = "SELECT COUNT(p.post_id) AS total_posts
						FROM " . $this->guestbooks_posts_table . " p
						WHERE p.guestbook_id = " . $guestbook_id;
		$result = $db->sql_query($sql);
		$posts_data = $db->sql_fetchrow($result);
		$total_posts = $posts_data['total_posts'];
		$db->sql_freeresult($result);

		return $total_posts;
	}

	/**
	* Submit post
	*/
	function submit_post(&$post_data, $mode = 'insert')
	{
		global $db, $cache, $config, $lang;

		if ($mode == 'insert')
		{
			// Before submitting the post... check if the user is flooding... we check this only if we are posting a post
			$this->check_user_flood(false);

			$sql = "INSERT INTO " . $this->guestbooks_posts_table . " " . $db->sql_build_insert_update($post_data, true);
			$result = $db->sql_query($sql);
			$post_data['post_id'] = $db->sql_nextid();
		}
		else
		{
			$sql = "UPDATE " . $this->guestbooks_posts_table . " SET
				" . $db->sql_build_insert_update($post_data, false) . "
				WHERE post_id = " . (int) $post_data['post_id'];
			$result = $db->sql_query($sql);
		}

		return true;
	}

	/*
	* Remove a post
	*/
	function remove_post($post_id)
	{
		global $db;

		$post_id = (int) $post_id;

		$sql = "DELETE FROM " . $this->guestbooks_posts_table . " WHERE post_id = " . $post_id;
		$result = $db->sql_query($sql);

		return true;
	}

	/*
	* Remove all posts for the same guestbook id
	*/
	function remove_all_posts($guestbook_id)
	{
		global $db;

		$guestbook_id = (int) $guestbook_id;

		$sql = "DELETE FROM " . $this->guestbooks_posts_table . " WHERE guestbook_id = " . $guestbook_id;
		$result = $db->sql_query($sql);

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
				FROM " . $this->guestbooks_posts_table . "
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