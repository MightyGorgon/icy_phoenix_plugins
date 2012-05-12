CREATE TABLE `phpbb_feedback` (
	`feedback_id` mediumint(9) NOT NULL auto_increment,
	`feedback_time` int(11) unsigned NOT NULL default '0',
	`feedback_transaction` varchar(255) NOT NULL default '',
	`feedback_rating` int(2) NOT NULL default '0',
	`feedback_description` text,
	`feedback_url` text,
	`feedback_topic_id` mediumint(9) NOT NULL default '0',
	`feedback_user_id_from` mediumint(9) NOT NULL default '-1',
	`feedback_user_id_to` mediumint(9) NOT NULL default '-1',
	PRIMARY KEY (`feedback_id`)
);

