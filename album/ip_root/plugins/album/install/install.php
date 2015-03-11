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


$install_data = array(
	'1.0.0' => array(
		'sql_files' => array('schema.sql', 'basic.sql'),
		'sql' => array(
			// schema
			"CREATE TABLE `" . $table_prefix . "album` (
				`pic_id` INT(11) unsigned NOT NULL auto_increment,
				`pic_filename` VARCHAR(255) NOT NULL DEFAULT '',
				`pic_size` INT(15) unsigned NOT NULL DEFAULT '0',
				`pic_thumbnail` VARCHAR(255) DEFAULT '',
				`pic_title` VARCHAR(255) NOT NULL DEFAULT '',
				`pic_desc` TEXT NOT NULL,
				`pic_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
				`pic_username` VARCHAR(32) DEFAULT '',
				`pic_user_ip` VARCHAR(40) NOT NULL DEFAULT '0',
				`pic_time` INT(11) unsigned NOT NULL DEFAULT '0',
				`pic_cat_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '1',
				`pic_view_count` INT(11) unsigned NOT NULL DEFAULT '0',
				`pic_lock` TINYINT(3) NOT NULL DEFAULT '0',
				`pic_approval` TINYINT(3) NOT NULL DEFAULT '1',
				PRIMARY KEY (`pic_id`),
				KEY `pic_cat_id` (`pic_cat_id`),
				KEY `pic_user_id` (`pic_user_id`),
				KEY `pic_time` (`pic_time`)
			);",

			"CREATE TABLE `" . $table_prefix . "album_cat` (
				`cat_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
				`cat_title` VARCHAR(255) NOT NULL DEFAULT '',
				`cat_desc` TEXT NOT NULL,
				`cat_wm` TEXT NOT NULL,
				`cat_pics` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
				`cat_order` MEDIUMINT(8) NOT NULL DEFAULT '0',
				`cat_view_level` TINYINT(3) NOT NULL DEFAULT '-1',
				`cat_upload_level` TINYINT(3) NOT NULL DEFAULT '0',
				`cat_rate_level` TINYINT(3) NOT NULL DEFAULT '0',
				`cat_comment_level` TINYINT(3) NOT NULL DEFAULT '0',
				`cat_edit_level` TINYINT(3) NOT NULL DEFAULT '0',
				`cat_delete_level` TINYINT(3) NOT NULL DEFAULT '2',
				`cat_view_groups` VARCHAR(255) DEFAULT '',
				`cat_upload_groups` VARCHAR(255) DEFAULT '',
				`cat_rate_groups` VARCHAR(255) DEFAULT '',
				`cat_comment_groups` VARCHAR(255) DEFAULT '',
				`cat_edit_groups` VARCHAR(255) DEFAULT '',
				`cat_delete_groups` VARCHAR(255) DEFAULT '',
				`cat_moderator_groups` VARCHAR(255) DEFAULT '',
				`cat_approval` TINYINT(3) NOT NULL DEFAULT '0',
				`cat_parent` MEDIUMINT(8) unsigned DEFAULT '0',
				`cat_user_id` MEDIUMINT(8) unsigned DEFAULT '0',
				PRIMARY KEY (`cat_id`),
				KEY `cat_order` (`cat_order`)
			);",

			"CREATE TABLE `" . $table_prefix . "album_comment` (
				`comment_id` INT(11) unsigned NOT NULL auto_increment,
				`comment_pic_id` INT(11) unsigned NOT NULL DEFAULT '0',
				`comment_cat_id` INT(11) NOT NULL DEFAULT '0',
				`comment_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
				`comment_username` VARCHAR(32) DEFAULT '',
				`comment_user_ip` VARCHAR(40) NOT NULL DEFAULT '',
				`comment_time` INT(11) unsigned NOT NULL DEFAULT '0',
				`comment_text` TEXT NOT NULL,
				`comment_edit_time` INT(11) unsigned DEFAULT NULL,
				`comment_edit_count` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
				`comment_edit_user_id` MEDIUMINT(8) DEFAULT NULL,
				PRIMARY KEY (`comment_id`),
				KEY `comment_pic_id` (`comment_pic_id`),
				KEY `comment_user_id` (`comment_user_id`),
				KEY `comment_user_ip` (`comment_user_ip`),
				KEY `comment_time` (`comment_time`)
			);",

			"CREATE TABLE `" . $table_prefix . "album_comment_watch` (
				pic_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
				user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
				notify_status TINYINT(1) NOT NULL DEFAULT '0',
				KEY pic_id (pic_id),
				KEY user_id (user_id),
				KEY notify_status (notify_status)
			);",

			"CREATE TABLE `" . $table_prefix . "album_config` (
				`config_name` VARCHAR(255) NOT NULL DEFAULT '',
				`config_value` VARCHAR(255) NOT NULL DEFAULT '',
				PRIMARY KEY (`config_name`)
			);",

			"CREATE TABLE `" . $table_prefix . "album_rate` (
				`rate_pic_id` INT(11) unsigned NOT NULL DEFAULT '0',
				`rate_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
				`rate_user_ip` VARCHAR(40) NOT NULL DEFAULT '',
				`rate_point` TINYINT(3) unsigned NOT NULL DEFAULT '0',
				`rate_hon_point` TINYINT(3) NOT NULL DEFAULT '0',
				KEY `rate_pic_id` (`rate_pic_id`),
				KEY `rate_user_id` (`rate_user_id`),
				KEY `rate_user_ip` (`rate_user_ip`),
				KEY `rate_point` (`rate_point`)
			);",

			// basic
			"INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('album', 'album', 'album.php', 0, '', 0, '');",

			"INSERT INTO `" . $table_prefix . "album_cat` (`cat_id`, `cat_title`, `cat_desc`, `cat_wm`, `cat_order`, `cat_view_level`, `cat_upload_level`, `cat_rate_level`, `cat_comment_level`, `cat_edit_level`, `cat_delete_level`, `cat_view_groups`, `cat_upload_groups`, `cat_rate_groups`, `cat_comment_groups`, `cat_edit_groups`, `cat_delete_groups`, `cat_moderator_groups`, `cat_approval`, `cat_parent`, `cat_user_id`) VALUES (1, 'Test Cat 1', 'Test Cat 1', '', 10, -1, 0, 0, 0, 0, 2, '', '', '', '', '', '', '', 0, 0, 0);",

			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('fap_version', '1.5.0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_pics', '1024');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('user_pics_limit', '-1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('mod_pics_limit', '-1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_file_size', '128000');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_width', '1024');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_height', '768');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('rows_per_page', '5');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('cols_per_page', '4');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('fullpic_popup', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('thumbnail_quality', '75');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('thumbnail_size', '125');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('thumbnail_cache', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('sort_method', 'pic_time');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('sort_order', 'DESC');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('jpg_allowed', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('png_allowed', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('gif_allowed', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('desc_length', '512');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('hotlink_prevent', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('hotlink_allowed', 'mightygorgon.com,icyphoenix.com');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_gallery', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_private', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_limit', '-1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_view', '-1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('rate', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('rate_scale', '10');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('comment', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('gd_version', '2');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('album_version', '.0.56');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_thumb', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_total_pics', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_total_comments', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_comments', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_last_comment', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_last_pic', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_pics', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_recent_in_subcats', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_recent_instead_of_nopics', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('line_break_subcats', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_subcats', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_allow_gallery_mod', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_allow_sub_categories', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_sub_category_limit', '-1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_show_subcats_in_index', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_show_recent_in_subcats', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_show_recent_instead_of_nopics', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_personal_gallery_link', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('album_category_sorting', 'cat_order');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('album_category_sorting_direction', 'ASC');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('album_debug_mode', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_all_in_personal_gallery', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('new_pic_check_interval', '1D');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('index_enable_supercells', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('email_notification', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_download', '2');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_slideshow', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_pic_size_on_thumb', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('hon_rate_users', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('hon_rate_where', '');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('hon_rate_sep', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('hon_rate_times', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('disp_watermark_at', '3');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('wut_users', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('use_watermark', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('rate_type', '2');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('disp_rand', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('disp_mostv', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('disp_high', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('disp_late', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('img_cols', '4');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('img_rows', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('midthumb_use', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('midthumb_height', '450');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('midthumb_width', '600');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('midthumb_cache', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_files_to_upload', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_pregenerated_fields', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('dynamic_fields', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('pregenerate_fields', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('propercase_pic_title', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_index_last_pic_lv', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_pics_approval', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_img_no_gd', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('dynamic_pic_resampling', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_file_size_resampling', '1024000');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('switch_nuffload', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('path_to_bin', './cgi-bin/');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('perl_uploader', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_progress_bar', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('close_on_finish', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_pause', '5');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('simple_format', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('multiple_uploads', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('max_uploads', '5');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('zip_uploads', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('resize_pic', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('resize_width', '600');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('resize_height', '600');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('resize_quality', '70');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_pics_nav', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_inline_copyright', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('enable_nuffimage', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('enable_sepia_bw', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('personal_allow_avatar_gallery', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_gif_mid_thumb', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('slideshow_script', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_exif', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('quick_thumbs', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('set_memory', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('lb_preview', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('use_old_pics_gen', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_last_comments', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('invert_nav_arrows', '0');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_otf_link', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_all_pics_link', '1');",
			"INSERT INTO `" . $table_prefix . "album_config` (`config_name`, `config_value`) VALUES ('show_personal_galleries_link', '1');",
		),
		'functions' => array(),
	)
);

$uninstall_data = array(
	'sql' => array(
		// schema
		// Better avoid removing all album tables...
		/*
		"DROP TABLE `" . $table_prefix . "album`;",
		"DROP TABLE `" . $table_prefix . "album_cat`;",
		"DROP TABLE `" . $table_prefix . "album_comment`;",
		"DROP TABLE `" . $table_prefix . "album_comment_watch`;",
		"DROP TABLE `" . $table_prefix . "album_config`;",
		"DROP TABLE `" . $table_prefix . "album_rate`;",
		*/
		// basic
		"DELETE FROM " . PLUGINS_CONFIG_TABLE . " WHERE config_name LIKE \"album_%\";",
		"DELETE FROM " . $table_prefix . "cms_blocks WHERE bs_id = (
			SELECT bs_id FROM
			" . $table_prefix . "cms_block_settings WHERE blockfile = 'plugin_album'
		);",
		"DELETE FROM " . $table_prefix . "cms_block_variable WHERE block = 'plugin_album';",
		"DELETE FROM " . $table_prefix . "cms_block_settings WHERE blockfile = 'plugin_album';",
		"DELETE FROM " . $table_prefix . "cms_layout_special WHERE page_id = 'album';",
		"DELETE FROM " . $table_prefix . "cms_nav_menu WHERE menu_links = 'album.php';",
	),
	'functions' => array(
		//@todo clean blocks
	),
);

?>