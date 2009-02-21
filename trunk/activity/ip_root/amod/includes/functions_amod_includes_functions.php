<?php
/***************************************************************************
 *                            functions_amod_includes_functions.php
 *                            ---------------------
 *   begin                : 2006/02/19
 *   copyright            : Mighty Gorgon (Luca Libralato)
 *   website              : http://www.mightygorgon.com
 *   email                : mightygorgon@mightygorgon.com
 *   version              : 0.1.0
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/


function UpdateTrophyStats()
{
	global $db, $table_prefix;

	$q = "UPDATE ". USERS_TABLE ."
				SET user_trophies = '0'
				WHERE user_trophies <> '0'";
	$r = $db -> sql_query($q);

	$q = "SELECT player
				FROM ". $table_prefix ."ina_top_scores
				GROUP BY player";
	$r = $db -> sql_query($q);
	while($row = $db -> sql_fetchrow($r))
	{
		$who = $row['player'];

		$q1 = "SELECT COUNT(*) AS trophies
					FROM ". $table_prefix ."ina_top_scores
					WHERE player = '$who'
					GROUP BY player";
		$r1 = $db -> sql_query($q1);
		$row = $db -> sql_fetchrow($r1);
		$total_trophies = $row['trophies'];

		$q2 = "UPDATE ". USERS_TABLE ."
					SET user_trophies = '$total_trophies'
					WHERE user_id = '$who'";
		$r2 = $db -> sql_query($q2);
	}
	return;
}

function CheckGamesDeletion()
{
	global $db, $table_prefix, $board_config;

	$q = "SELECT config_value
				FROM ". CONFIG_TABLE ."
				WHERE config_name = 'current_ina_date'";
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);

	$next_deletion = $row['config_value'];
	$explode_it = explode("-", $next_deletion);
	$d_month = $explode_it[1];
	$t_date = date("Y-m-d");
	$x_date = explode("-", $t_date);
	$c_month = $x_date[1];

	if($d_month == $c_month)
	{
	}
	else
	{
		if($board_config['ina_delete'] == '1')
		{
			$q = "TRUNCATE ". iNA_SCORES ."";
			$r = $db -> sql_query($q);

			$q = "TRUNCATE ". $table_prefix ."ina_trophy_comments";
			$r = $db -> sql_query($q);

			$q = "UPDATE ". CONFIG_TABLE ."
						SET config_value = '$t_date'
						WHERE config_name = 'current_ina_date'";
			$r = $db -> sql_query($q);
		}
	}
	return;
}
?>