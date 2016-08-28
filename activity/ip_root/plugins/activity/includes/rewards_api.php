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
* aUsTiN-Inc 2003/5 (austin@phpbb-amod.com) - (http://phpbb-amod.com)
* Xore (mods@xore.ca)
* Napoleon (support@inetangel.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking Attempt');
}

// give rewards to the user
function add_reward($user_id,$amount)
{
	global $user, $db, $config, $cache;
	$dbfield = get_db_reward();
	if (!empty($dbfield))
	{
		if ($user->data['user_id'] == $user_id)
		{
			$user->data[$dbfield] += $amount;
		}
		$sql = "UPDATE " . USERS_TABLE . "
			SET $dbfield = $dbfield + $amount
			WHERE user_id = $user_id";
		$result = $db->sql_query($sql);
	}
}

// subdtract rewards from the user
function subtract_reward($user_id,$amount)
{
	global $user, $db, $config, $cache;
	$dbfield = get_db_reward();
	if ($user->data['user_id'] == $user_id)
	{
		$user->data[$dbfield] -= $amount;
	}
	$sql = "UPDATE " . USERS_TABLE . "
		SET $dbfield = $dbfield - $amount
		WHERE user_id = $user_id";
	$result = $db->sql_query($sql);
}

// set the user's rewards
function set_reward($user_id,$amount)
{
	global $user, $db, $config, $cache;
	$dbfield = get_db_reward();
	if ($user->data['user_id'] == $user_id)
	{
		$user->data[$dbfield] = $amount;
	}
	$sql = "UPDATE " . USERS_TABLE . "
		SET $dbfield = $amount
		WHERE user_id = $user_id";
	$result = $db->sql_query($sql);
}

// get the user's reward amounts
function get_reward($user_id)
{
	global $user, $db, $config, $cache;
	$dbfield = get_db_reward();
	if ($user->data['user_id'] == $user_id)
	{
		return $user->data[$dbfield];
	}
	else
	{
		$sql = "SELECT $dbfield
			FROM " . USERS_TABLE . "
			WHERE user_id = $user_id";
		$result = $db->sql_query($sql);

		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, "Bad user_id or default reward column", "", __LINE__, __FILE__);
		}
		return $row[$dbfield];
	}
}

// check if user has $amount minimum rewards
function has_reward($user_id, $amount)
{
	$users_amount = get_reward($user_id);
	return ($users_amount >= $amount);
}

// Get the rewards dbfield (API-internal function)
function get_db_reward()
{
	// All rewards mods must store their default database field in the config table ...
	// 'default_reward_dbfield'
	global $config;
	return $config['default_reward_dbfield'];
}
?>