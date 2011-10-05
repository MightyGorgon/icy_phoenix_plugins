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

// FEEDBACK CONFIG - BEGIN
define('PLUGINS_FEEDBACK_FILE', 'feedback.' . PHP_EXT);

define('PLUGINS_FEEDBACK_TABLE', $table_prefix . 'feedback');
define('PLUGINS_FEEDBACK_FORUMS', '1,2');
define('PLUGINS_FEEDBACK_RATING_START', '0');
define('PLUGINS_FEEDBACK_RATING_END', '10');
// FEEDBACK CONFIG - END

/*
Get user feedback given
*/
function get_user_feedback_given($user_id)
{
	global $db;
	$sql = "SELECT SUM(feedback_rating) feedback_sum, COUNT(feedback_rating) feedback_count FROM " . PLUGINS_FEEDBACK_TABLE . " WHERE feedback_user_id_from = '" . $user_id . "'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $row;
}

/*
Get user feedback received
*/
function get_user_feedback_received($user_id)
{
	global $db;
	$sql = "SELECT SUM(feedback_rating) feedback_sum, COUNT(feedback_rating) feedback_count FROM " . PLUGINS_FEEDBACK_TABLE . " WHERE feedback_user_id_to = '" . $user_id . "'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $row;
}

/*
Check if user is allowed to give feedback
*/
function can_user_give_feedback_topic($user_id, $topic_id)
{
	global $db;
	$sql = "SELECT * FROM " . PLUGINS_FEEDBACK_TABLE . "
					WHERE feedback_topic_id = '" . $topic_id . "'
						AND feedback_user_id_from = '" . $user_id . "'";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$db->sql_freeresult($result);
		return false;
	}
	$db->sql_freeresult($result);
	return true;
}

/*
Check if user is allowed to receive feedback
*/
function can_user_receive_feedback_topic($user_id, $topic_id)
{
	global $db;
	$sql = "SELECT * FROM " . PLUGINS_FEEDBACK_TABLE . "
					WHERE feedback_topic_id = '" . $topic_id . "'
						AND feedback_user_id_to = '" . $user_id . "'";
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$db->sql_freeresult($result);
		return false;
	}
	$db->sql_freeresult($result);
	return true;
}

/*
Get transaction details
*/
function get_transactions_details($topic_id)
{
	global $db;
	$sql = "SELECT t.topic_id, t.topic_title, t.topic_poster, t.forum_id, f.forum_name, p.post_id, p.poster_id
		FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p
		WHERE t.topic_id = '" . $topic_id . "'
			AND f.forum_id = t.forum_id
			AND p.topic_id = '" . $topic_id . "'
		ORDER BY p.post_id ASC";
	$result = $db->sql_query($sql);

	$topic_rows = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$topic_rows[] = $row;
	}
	$db->sql_freeresult($result);
	return $topic_rows;
}

/*
Check if user is allowed to give feedback in the specified topic / forum
*/
function can_user_give_feedback_global($user_id, $topic_id)
{
	global $db, $lang;
	/*
	if (!can_user_give_feedback_topic($user_id, $topic_id))
	{
		return false;
	}
	*/
	$topic_rows = get_transactions_details($topic_id);
	$allowed_forums = explode(',', PLUGINS_FEEDBACK_FORUMS);
	if (!empty($topic_rows[0]) && !in_array($topic_rows[0]['forum_id'], $allowed_forums))
	{
		return false;
	}
	for ($j = 0; $j < sizeof($topic_rows); $j++)
	{
		if ($topic_rows[$j]['poster_id'] == $user_id)
		{
			return true;
		}
	}
	return false;
}

/*
Build feedback rating image
*/
function build_feedback_rating_image($rating)
{
	$rating_scale = ((PLUGINS_FEEDBACK_RATING_END - PLUGINS_FEEDBACK_RATING_START) == 0) ? 10 : (PLUGINS_FEEDBACK_RATING_END - PLUGINS_FEEDBACK_RATING_START);
	$rating_level = round(($rating / $rating_scale) * 10, 0);
	$rating_image = 'feedback_' . $rating_level . '.png';
	return $rating_image;
}

?>