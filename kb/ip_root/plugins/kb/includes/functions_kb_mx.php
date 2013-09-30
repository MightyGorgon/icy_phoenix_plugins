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

if (!function_exists(mx_smilies_pass))
{
	function mx_smilies_pass($message)
	{
		global $config, $bbcode;
		$smilies_path = $config['smilies_path'];
		$config['smilies_path'] = PHPBB_URL . $config['smilies_path'];
		$bbcode->allow_html = $config['allow_html'];
		$bbcode->allow_bbcode = $config['allow_bbcode'];
		$bbcode->allow_smilies = $config['allow_smilies'];
		$bbcode->parse($message);
		$config['smilies_path'] = $smilies_path;
		return $message;
	}
}

if (!function_exists(mx_message_die))
{

	function mx_message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
	{
		message_die($msg_code, $msg_text, $msg_title, $err_line, $err_file, $sql);
	}

}

if (!function_exists(mx_is_group_member))
{
	// Validates if user belongs to group included in group_ids list
	// Also, adds all usergroups to userdata array
	function mx_is_group_member($group_ids = '', $group_mod_mode = false)
	{
		global $user, $db;

		if ($group_ids == '')
		{
			return false;
		}

		$group_ids_array = explode(",", $group_ids);

		// Try to reuse usergroups result.
		if ($group_mod_mode)
		{
			$userdata_key = 'mx_usergroups_mod' . $user->data['user_id'];

			if (empty($user->data[$userdata_key]))
			{
				// Check if user is group moderator..
				$sql = "SELECT gr.group_id
						FROM " . GROUPS_TABLE . " gr, " . USER_GROUP_TABLE . " ugr
						WHERE gr.group_id = ugr.group_id
							AND gr.group_moderator = '" . $user->data['user_id'] . "'
							AND ugr.user_pending = '0' ";
				$result = $db->sql_query($sql);
				$group_row = $db->sql_fetchrowset($result);
				$user->data[$userdata_key_mod] = $group_row;
			}
		}
		else
		{
			$userdata_key = 'mx_usergroups' . $user->data['user_id'];

			if (empty($user->data[$userdata_key]))
			{
				// Check if user is member of the proper group..
				$sql = "SELECT group_id FROM " . USER_GROUP_TABLE . " WHERE user_id='" . $user->data['user_id'] . "' AND user_pending = 0";
				$result = $db->sql_query($sql);
				$group_row = $db->sql_fetchrowset($result);
				$user->data[$userdata_key] = $group_row;
			}
		}

		for ($i = 0; $i < sizeof($user->data[$userdata_key]); $i++)
		{
			if (in_array($user->data[$userdata_key][$i]['group_id'], $group_ids_array))
			{
				$is_member = true;
				return $is_member;
			}
		}

		return false;
	}
}

if (!function_exists(mx_add_search_words))
{
	// Add search words for blocks
	function mx_add_search_words($mode, $post_id, $post_text, $post_title = '', $mx_mode = 'mx')
	{
		global $db, $config, $lang;

		// $search_match_table = SEARCH_MATCH_TABLE;
		// $search_word_table = SEARCH_WORD_TABLE;

		switch ($mx_mode)
		{
			case 'mx':
				$search_match_table = MX_MATCH_TABLE;
				$search_word_table = MX_WORD_TABLE;
				$db_key = 'block_id';
			break;
			case 'kb':
				$search_match_table = KB_MATCH_TABLE;
				$search_word_table = KB_WORD_TABLE;
				$db_key = 'article_id';
			break;
		}

		stopwords_synonyms_init();

		$search_raw_words = array();
		$search_raw_words['text'] = split_words(clean_words('post', $post_text, $stopwords_array, $synonyms_array));
		$search_raw_words['title'] = split_words(clean_words('post', $post_title, $stopwords_array, $synonyms_array));

		@set_time_limit(0);

		$word = array();
		$word_insert_sql = array();
		while (list($word_in, $search_matches) = @each($search_raw_words))
		{
			$word_insert_sql[$word_in] = '';
			if (!empty($search_matches))
			{
				for ($i = 0; $i < sizeof($search_matches); $i++)
				{
					$search_matches[$i] = trim($search_matches[$i]);

					if($search_matches[$i] != '')
					{
						$word[] = $search_matches[$i];
						if (!strstr($word_insert_sql[$word_in], "'" . $search_matches[$i] . "'"))
						{
							$word_insert_sql[$word_in] .= ($word_insert_sql[$word_in] != "") ? ", '" . $search_matches[$i] . "'" : "'" . $search_matches[$i] . "'";
						}
					}
				}
			}
		}

		if (sizeof($word))
		{
			sort($word);

			$prev_word = '';
			$word_text_sql = '';
			$temp_word = array();
			for($i = 0; $i < sizeof($word); $i++)
			{
				if ($word[$i] != $prev_word)
				{
					$temp_word[] = $word[$i];
					$word_text_sql .= (($word_text_sql != '') ? ', ' : '') . "'" . $word[$i] . "'";
				}
				$prev_word = $word[$i];
			}
			$word = $temp_word;

			$check_words = array();

			$value_sql = '';
			$match_word = array();
			for ($i = 0; $i < sizeof($word); $i++)
			{
				$new_match = true;
				if (isset($check_words[$word[$i]]))
				{
					$new_match = false;
				}

				if ($new_match)
				{
					$value_sql .= (($value_sql != '') ? ', ' : '') . '(\'' . $word[$i] . '\', 0)';
				}
			}

			if ($value_sql != '')
			{
				$sql = "INSERT IGNORE INTO " . $search_word_table . " (word_text, word_common) VALUES $value_sql";
				$db->sql_query($sql);
			}
		}

		while(list($word_in, $match_sql) = @each($word_insert_sql))
		{
			$title_match = ($word_in == 'title') ? 1 : 0;

			if ($match_sql != '')
			{
				$sql = "INSERT INTO " . $search_match_table . " ($db_key, word_id, title_match)
					SELECT $post_id, word_id, $title_match
						FROM " . $search_word_table . "
						WHERE word_text IN ($match_sql)";
				$db->sql_query($sql);
			}
		}

		if ($mode == 'single')
		{
			// remove_common('single', 4/10, $word);
		}

		return;
	}

	function mx_remove_search_post($post_id_sql, $mx_mode = 'mx')
	{
		global $db;

		// $search_match_table = SEARCH_MATCH_TABLE;
		// $search_word_table = SEARCH_WORD_TABLE;

		switch ($mx_mode)
		{
			case 'mx':
				$search_match_table = MX_MATCH_TABLE;
				$search_word_table = MX_WORD_TABLE;
				$db_key = 'block_id';
			break;
			case 'kb':
				$search_match_table = KB_MATCH_TABLE;
				$search_word_table = KB_WORD_TABLE;
				$db_key = 'article_id';
			break;
		}

		$words_removed = false;

		switch (SQL_LAYER)
		{
			case 'mysql':
			case 'mysql4':
				$sql = "SELECT word_id
					FROM " . $search_match_table . "
					WHERE $db_key IN ($post_id_sql)
					GROUP BY word_id";
				$db->sql_return_on_error(true);
				$result = $db->sql_query($sql);
				$db->sql_return_on_error(false);
				if ($result)
				{
					$word_id_sql = '';
					while ($row = $db->sql_fetchrow($result))
					{
						$word_id_sql .= ($word_id_sql != '') ? ', ' . $row['word_id'] : $row['word_id'];
					}

					$sql = "SELECT word_id
						FROM " . $search_match_table . "
						WHERE word_id IN ($word_id_sql)
						GROUP BY word_id
						HAVING COUNT(word_id) = 1";
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if ($result)
					{
						$word_id_sql = '';
						while ($row = $db->sql_fetchrow($result))
						{
							$word_id_sql .= ($word_id_sql != '') ? ', ' . $row['word_id'] : $row['word_id'];
						}

						if ($word_id_sql != '')
						{
							$sql = "DELETE FROM " . $search_word_table . "
								WHERE word_id IN ($word_id_sql)";
							$db->sql_query($sql);
							$words_removed = $db->sql_affectedrows();
						}
					}
				}
				break;

			default:
				$sql = "DELETE FROM " . $search_word_table . "
					WHERE word_id IN (
						SELECT word_id
						FROM " . $search_match_table . "
						WHERE word_id IN (
							SELECT word_id
							FROM " . $search_match_table . "
							WHERE $db_key IN ($post_id_sql)
							GROUP BY word_id
						)
						GROUP BY word_id
						HAVING COUNT(word_id) = 1
					)";
				$db->sql_query($sql);

				$words_removed = $db->sql_affectedrows();

				break;
		}

		$sql = "DELETE FROM " . $search_match_table . "
			WHERE $db_key IN ($post_id_sql)";
		$db->sql_query($sql);

		return $words_removed;
	}
}

if (!function_exists(mx_do_install_upgrade))
{
	// Generating output

	function mx_do_install_upgrade($sql = '', $main_install = false)
	{
		global $table_prefix, $mx_table_prefix, $user, $template, $lang, $db, $config, $_POST;

		$inst_error = false;
		$n = 0;
		$message = "<b>This is the result list of the SQL queries needed for the install/upgrade</b><br /><br />";

		while ($sql[$n])
		{
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql[$n]);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				$message .= '<span class="text_red">[Error or Already added]</span> line: ' . ($n + 1) . ' , ' . $sql[$n] . '<br />';
				$inst_error = true;
			}
			else
			{
				$message .= '<span class="text_blue">[Added/Updated]</span> line: ' . ($n + 1) . ' , ' . $sql[$n] . '<br />';
			}
			$n++;
		}
		$message .= '<br /> If you get some Errors, Already Added or Updated messages, relax, this is normal when updating modules';

		if ($main_install)
		{
			if (!$inst_error)
			{
				$message .= '-> no db errors :-)<br /><br /><b>Portal installed successfully! </b><hr><br /><br />';
				$message .= '1) Now, delete the /install and /contrib folders!!!<br /><br />';
				$message .= '2) If you haven\'t already done a db backup, now is the time ;)<br /><br />';
				$message .= '3) Then (after step 1), you HAVE to configure MX core and its modules from within the adminCP, simply \'upgrade\' MX portal Core and all modules in use!!!<br /><br />';

				$message .= 'Click <a href=../' . ADM . '/admin_mx_module.php>Here</a> to administer/upgrade the portal/modules. You will be promted for an admin username and pass. The upgrade process provide informative output...';
			}
			else
			{
				$message .= '<br /><br /><b>Portal installed successfully (with some warnings)! </b><hr><br /><br />';
				$message .= '1) Now, delete the /install and /contrib folders!!!<br /><br />';
				$message .= '2) If you haven\'t already done a db backup, now is the time ;)<br /><br />';
				$message .= '3) Now (after step 1), you HAVE to configure MX core and its modules from within the adminCP, simply \'upgrade\' MX portal Core and all modules in use!!!<br /><br />';

				$message .= 'Click <a href=../' . ADM . '/admin_mx_module.php>Here</a> to administer/upgrade the portal/modules. You will be promted for an admin username and pass. The upgrade process provide informative output...';
			}
		}
		return $message;
	}
}

?>