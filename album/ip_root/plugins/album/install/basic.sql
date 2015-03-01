
## `phpbb_album`
##

## `phpbb_album_cat`
##
INSERT INTO `phpbb_album_cat` (`cat_id`, `cat_title`, `cat_desc`, `cat_wm`, `cat_order`, `cat_view_level`, `cat_upload_level`, `cat_rate_level`, `cat_comment_level`, `cat_edit_level`, `cat_delete_level`, `cat_view_groups`, `cat_upload_groups`, `cat_rate_groups`, `cat_comment_groups`, `cat_edit_groups`, `cat_delete_groups`, `cat_moderator_groups`, `cat_approval`, `cat_parent`, `cat_user_id`) VALUES (1, 'Test Cat 1', 'Test Cat 1', '', 10, -1, 0, 0, 0, 0, 2, '', '', '', '', '', '', '', 0, 0, 0);

## `phpbb_album_comment`
##

## `phpbb_album_config`
##
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_pics', '1024');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('user_pics_limit', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('mod_pics_limit', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_file_size', '128000');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_width', '1024');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_height', '768');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('rows_per_page', '5');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('cols_per_page', '4');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('fullpic_popup', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('thumbnail_quality', '75');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('thumbnail_size', '125');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('thumbnail_cache', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('sort_method', 'pic_time');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('sort_order', 'DESC');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('jpg_allowed', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('png_allowed', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('gif_allowed', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('desc_length', '512');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hotlink_prevent', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hotlink_allowed', 'mightygorgon.com,icyphoenix.com');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_gallery', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_private', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_limit', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_gallery_view', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('rate', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('rate_scale', '10');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('comment', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('gd_version', '2');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('album_version', '.0.56');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_thumb', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_total_pics', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_total_comments', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_comments', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_last_comment', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_last_pic', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_pics', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_recent_in_subcats', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_recent_instead_of_nopics', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('line_break_subcats', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_subcats', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_allow_gallery_mod', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_allow_sub_categories', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_sub_category_limit', '-1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_show_subcats_in_index', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_show_recent_in_subcats', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_show_recent_instead_of_nopics', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_personal_gallery_link', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('album_category_sorting', 'cat_order');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('album_category_sorting_direction', 'ASC');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('album_debug_mode', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_all_in_personal_gallery', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('new_pic_check_interval', '1D');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('index_enable_supercells', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('email_notification', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_download', '2');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_slideshow', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_pic_size_on_thumb', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hon_rate_users', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hon_rate_where', '');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hon_rate_sep', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('hon_rate_times', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_watermark_at', '3');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('wut_users', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('use_watermark', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('rate_type', '2');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_rand', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_mostv', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_high', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('disp_late', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('img_cols', '4');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('img_rows', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('midthumb_use', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('midthumb_height', '450');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('midthumb_width', '600');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('midthumb_cache', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_files_to_upload', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_pregenerated_fields', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('dynamic_fields', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('pregenerate_fields', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('propercase_pic_title', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_index_last_pic_lv', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_pics_approval', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_img_no_gd', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('dynamic_pic_resampling', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_file_size_resampling', '1024000');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('switch_nuffload', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('path_to_bin', './cgi-bin/');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('perl_uploader', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_progress_bar', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('close_on_finish', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_pause', '5');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('simple_format', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('multiple_uploads', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('max_uploads', '5');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('zip_uploads', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('resize_pic', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('resize_width', '600');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('resize_height', '600');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('resize_quality', '70');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_pics_nav', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_inline_copyright', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('enable_nuffimage', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('enable_sepia_bw', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('personal_allow_avatar_gallery', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_gif_mid_thumb', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('slideshow_script', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_exif', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('quick_thumbs', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('set_memory', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('lb_preview', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('use_old_pics_gen', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_last_comments', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('invert_nav_arrows', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_otf_link', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_all_pics_link', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_personal_galleries_link', '1');

## `phpbb_album_rate`
##