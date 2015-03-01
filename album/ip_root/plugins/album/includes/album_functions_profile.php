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

/*
* Function used to display pictures in profile
*/
function album_profile_last_pictures($profiledata)
{
	global $db, $cache, $config, $user, $lang, $template, $images, $cms_config_layouts, $album_config;

	include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['album']['dir'] . 'includes/album_functions.' . PHP_EXT);
	include(IP_ROOT_PATH . PLUGINS_PATH . $config['plugins']['album']['dir'] . 'includes/album_hierarchy_functions.' . PHP_EXT);
	$cms_page_id_tmp = 'album';
	$cms_auth_level_tmp = (isset($cms_config_layouts[$cms_page_id_tmp]['view']) ? $cms_config_layouts[$cms_page_id_tmp]['view'] : AUTH_ALL);
	$show_latest_pics = check_page_auth($cms_page_id_tmp, $cms_auth_level_tmp, true);
	if ($show_latest_pics)
	{
		setup_extra_lang(array('lang_plugin'), ALBUM_ROOT_PATH . 'language/');

		$sql = "SELECT * FROM " . ALBUM_CONFIG_TABLE;
		$result = $db->sql_query($sql, 0, 'album_config_');

		while($row = $db->sql_fetchrow($result))
		{
			$album_config[$row['config_name']] = $row['config_value'];
		}
		$db->sql_freeresult($result);

		$limit_sql = $album_config['img_cols'] * $album_config['img_rows'];
		$cols_per_page = $album_config['img_cols'];

		if ($user->data['user_level'] == ADMIN)
		{
			$cat_view_level_sql = '';
		}
		elseif (!empty($user->data['session_logged_in']))
		{
			$cat_view_level_sql = " AND c.cat_view_level <= 1 ";
		}
		else
		{
			$cat_view_level_sql = " AND c.cat_view_level <= 0 ";
		}

		//$include_personal_galleries_sql = (($user->data['user_level'] == ADMIN) || (!empty($user->data['session_logged_in']) && empty($album_config['personal_gallery_view'])) || (empty($user->data['session_logged_in']) && ($album_config['personal_gallery_view'] == ANONYMOUS))) ? (" AND c.cat_user_id = " . $profiledata['user_id'] . " ") : "";
		$include_personal_galleries_sql = (($user->data['user_level'] == ADMIN) || (!empty($user->data['session_logged_in']) && empty($album_config['personal_gallery_view'])) || (empty($user->data['session_logged_in']) && ($album_config['personal_gallery_view'] == ANONYMOUS))) ? "" : (" AND c.cat_user_id = 0 ");

		$sql = "SELECT p.*, c.*, u.user_id, u.username, u.user_active, u.user_color
				FROM " . ALBUM_TABLE . " AS p, " . ALBUM_CAT_TABLE . " AS c, " . USERS_TABLE . " u
				WHERE p.pic_user_id = '" . $profiledata['user_id'] . "'
					AND p.pic_approval = 1
					" . $include_personal_galleries_sql . $cat_view_level_sql . "
					AND p.pic_cat_id = c.cat_id
					AND u.user_id = p.pic_user_id
				ORDER BY pic_time DESC";
		$result = $db->sql_query($sql);

		$recentrow = array();
		while($row = $db->sql_fetchrow($result))
		{
			$recentrow[] = $row;
		}

		$totalpicrow = sizeof($recentrow);

		$db->sql_freeresult($result);

		if ($totalpicrow > 0)
		{
			$temp_url = append_sid(CMS_PAGE_ALBUM . '?user_id=' . $profiledata['user_id']);
			$album_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_album'] . '" alt="' . htmlspecialchars(sprintf($lang['Personal_Gallery_Of_User'], $profiledata['username'])) . '" title="' . htmlspecialchars(sprintf($lang['Personal_Gallery_Of_User'], $profiledata['username'])) . '" /></a>';
			$album = '<a href="' . $temp_url . '">' . htmlspecialchars(sprintf($lang['Personal_Gallery_Of_User'], $profiledata['username'])) . '</a>';

			$template->assign_block_vars('recent_pics_block', array());
			for ($i = 0; $i < (($totalpicrow < $limit_sql) ? $totalpicrow : $limit_sql); $i += $cols_per_page)
			{
				$template->assign_block_vars('recent_pics_block.recent_pics', array());

				for ($j = $i; $j < ($i + $cols_per_page); $j++)
				{
					if($j >= $totalpicrow)
					{
						break;
					}

					$pic_preview = '';
					$pic_preview_hs = '';
					if ($album_config['lb_preview'])
					{
						$slideshow_cat = 'Profile';
						$slideshow = !empty($slideshow_cat) ? ', { slideshowGroup: \'' . $slideshow_cat . '\' } ' : '';
						$pic_preview_hs = ' class="highslide" onclick="return hs.expand(this' . $slideshow . ');"';

						$pic_preview = 'onmouseover="showtrail(\'' . append_sid('album_picm.' . PHP_EXT . '?pic_id=' . $recentrow[$j]['pic_id']) . '\',\'' . addslashes($recentrow[$j]['pic_title']) . '\', ' . $album_config['midthumb_width'] . ', ' . $album_config['midthumb_height'] . ')" onmouseout="hidetrail()"';
					}

					$template_vars = array(
						'PIC_PREVIEW_HS' => $pic_preview_hs,
						'PIC_PREVIEW' => $pic_preview,
					);
					album_build_column_vars($template_vars, $recentrow[$j]);
					$template->assign_block_vars('recent_pics_block.recent_pics.recent_col', $template_vars);

					$recent_poster = colorize_username($recentrow[$j]['user_id'], $recentrow[$j]['username'], $recentrow[$j]['user_color'], $recentrow[$j]['user_active']);

					$template_vars = array(
						'POSTER' => $recent_poster,
						'PIC_PREVIEW_HS' => $pic_preview_hs,
						'PIC_PREVIEW' => $pic_preview,
						'GROUP_NAME' => 'profile',
					);
					album_build_detail_vars($template_vars, $recentrow[$j]);
					$template->assign_block_vars('recent_pics_block.recent_pics.recent_detail', $template_vars);
				}
			}
		}
		else
		{
			$album_img = '&nbsp;';
			$album = '';
		}

		$template->assign_vars(array(
			'ALBUM_IMG' => $album_img,
			'ALBUM' => $album,
			'U_PERSONAL_GALLERY' => append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id']),
			'L_PERSONAL_GALLERY' => htmlspecialchars(sprintf($lang['Personal_Gallery_Of_User_Profile'], $profiledata['username'], $totalpicrow)),
			'U_TOGGLE_VIEW_ALL' => append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id'] . '&amp;mode=' . ALBUM_VIEW_ALL),
			'TOGGLE_VIEW_ALL_IMG' => $images['icon_tiny_search'],
			'L_TOGGLE_VIEW_ALL' => htmlspecialchars(sprintf($lang['Show_All_Pic_View_Mode_Profile'], $profiledata['username'])),
			'U_ALL_IMAGES_BY_USER' => append_sid('album.' . PHP_EXT . '?user_id=' . $profiledata['user_id'] . '&amp;mode=' . ALBUM_VIEW_LIST),
			'L_ALL_IMAGES_BY_USER' => htmlspecialchars(sprintf($lang['Picture_List_Of_User'], $profiledata['username'])),
			'L_PERSONAL_ALBUM' => $lang['Your_Personal_Gallery'],
			'L_PIC_TITLE' => $lang['Pic_Image'],
			'L_POSTER' => $lang['Pic_Poster'],
			'L_POSTED' => $lang['Posted'],
			'L_VIEW' => $lang['View'],
			'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',
			'L_NO_PICS' => $lang['No_Pics'],
			'L_RECENT_PUBLIC_PICS' => $lang['Recent_Public_Pics'],
			'S_COLS' => $album_config['cols_per_page'],
			//'S_COL_WIDTH' => (100/$album_config['cols_per_page']) . '%',
			'S_COL_WIDTH' => ($album_config['cols_per_page'] == 0) ? '100%' : (100 / $album_config['cols_per_page']) . '%',
			'S_THUMBNAIL_SIZE' => $album_config['thumbnail_size'],
			)
		);

		if ($album_config['show_all_in_personal_gallery'])
		{
			$template->assign_block_vars('enable_view_toggle', array());
		}
	}

}

?>