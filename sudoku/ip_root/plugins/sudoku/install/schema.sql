## --------------------------------------------------------

## `phpbb_sudoku_sessions`

CREATE TABLE `phpbb_sudoku_sessions` (
	`user_id` INT(11) NOT NULL DEFAULT '0',
	`session_time` INT(11) NOT NULL DEFAULT '0'
);

## `phpbb_sudoku_sessions`


## --------------------------------------------------------

## `phpbb_sudoku_solutions`

CREATE TABLE `phpbb_sudoku_solutions` (
	`game_pack` INT(5) NOT NULL DEFAULT '0',
	`game_num` INT(5) NOT NULL DEFAULT '0',
	`line_1` VARCHAR(20) NOT NULL DEFAULT '',
	`line_2` VARCHAR(20) NOT NULL DEFAULT '',
	`line_3` VARCHAR(20) NOT NULL DEFAULT '',
	`line_4` VARCHAR(20) NOT NULL DEFAULT '',
	`line_5` VARCHAR(20) NOT NULL DEFAULT '',
	`line_6` VARCHAR(20) NOT NULL DEFAULT '',
	`line_7` VARCHAR(20) NOT NULL DEFAULT '',
	`line_8` VARCHAR(20) NOT NULL DEFAULT '',
	`line_9` VARCHAR(20) NOT NULL DEFAULT '',
	KEY `game_pack` (`game_pack`)
);

## `phpbb_sudoku_solutions`


## --------------------------------------------------------

## `phpbb_sudoku_starts`

CREATE TABLE `phpbb_sudoku_starts` (
	`game_pack` INT(5) NOT NULL DEFAULT '0',
	`game_num` INT(5) NOT NULL DEFAULT '0',
	`game_level` INT(1) NOT NULL DEFAULT '0',
	`line_1` VARCHAR(20) NOT NULL DEFAULT '',
	`line_2` VARCHAR(20) NOT NULL DEFAULT '',
	`line_3` VARCHAR(20) NOT NULL DEFAULT '',
	`line_4` VARCHAR(20) NOT NULL DEFAULT '',
	`line_5` VARCHAR(20) NOT NULL DEFAULT '',
	`line_6` VARCHAR(20) NOT NULL DEFAULT '',
	`line_7` VARCHAR(20) NOT NULL DEFAULT '',
	`line_8` VARCHAR(20) NOT NULL DEFAULT '',
	`line_9` VARCHAR(20) NOT NULL DEFAULT '',
	KEY `game_pack` (`game_pack`)
);

## `phpbb_sudoku_starts`


## --------------------------------------------------------

## `phpbb_sudoku_stats`

CREATE TABLE `phpbb_sudoku_stats` (
	`user_id` INT(11) NOT NULL DEFAULT '0',
	`played` INT(11) NOT NULL DEFAULT '0',
	`points` INT(11) NOT NULL DEFAULT '0',
	KEY `user_id` (`user_id`)
);

## `phpbb_sudoku_stats`


## --------------------------------------------------------

## `phpbb_sudoku_users`

CREATE TABLE `phpbb_sudoku_users` (
	`user_id` INT(11) NOT NULL DEFAULT '0',
	`game_pack` INT(5) NOT NULL DEFAULT '0',
	`game_num` INT(5) NOT NULL DEFAULT '0',
	`game_level` INT(1) NOT NULL DEFAULT '0',
	`line_1` VARCHAR(30) NOT NULL DEFAULT '',
	`line_2` VARCHAR(30) NOT NULL DEFAULT '',
	`line_3` VARCHAR(30) NOT NULL DEFAULT '',
	`line_4` VARCHAR(30) NOT NULL DEFAULT '',
	`line_5` VARCHAR(30) NOT NULL DEFAULT '',
	`line_6` VARCHAR(30) NOT NULL DEFAULT '',
	`line_7` VARCHAR(30) NOT NULL DEFAULT '',
	`line_8` VARCHAR(30) NOT NULL DEFAULT '',
	`line_9` VARCHAR(30) NOT NULL DEFAULT '',
	`points` INT(11) NOT NULL DEFAULT '0',
	`done` INT(1) NOT NULL DEFAULT '0',
	KEY `user_id` (`user_id`)
);

## `phpbb_sudoku_users`


