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
* David Lewis (Highway of Life) http://startrekguide.com
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// COMMON INCLUDES AND OPTIONS - BEGIN
include_once(IP_ROOT_PATH . 'includes/functions_users.' . PHP_EXT);

include(DONATIONS_ROOT_PATH . 'donations_array.' . PHP_EXT);
$class_db->main_db_table = PLUGINS_DONATIONS_DB_TABLE;
$class_db->main_db_item = PLUGINS_DONATIONS_DB_ITEM;

include_once(DONATIONS_ROOT_PATH . 'includes/functions_paypal.' . PHP_EXT);
$donate = new paypal_class();
$donate->page = create_server_url() . THIS_FILE;
// COMMON INCLUDES AND OPTIONS - END

// Start output of page
$meta_content['page_title'] = $lang['MG_DONATIONS'];
$meta_content['description'] = '';
$meta_content['keywords'] = '';
$breadcrumbs['bottom_right_links'] = '<a href="' . append_sid('donations.' . PHP_EXT) . '">' . $lang['MG_DONATIONS'] . '</a>';
$cms_page['page_nav'] = true;
$cms_page['global_blocks'] = false;

$template_to_parse = $class_plugins->get_tpl_file(DONATIONS_TPL_PATH, 'donate_body.tpl');

$submit = (isset($_REQUEST['submit'])) ? true : false;
$action = request_var('action', 'donate');

switch ($action)
{
	case 'success':
		trigger_error($lang['THANKS_DONATION']);
	break;

	case 'validate':
	case 'ipn':
		if ($plugin_config['donations_debug'])
		{
			$donate->log_error('DEBUG:', false, E_USER_NOTICE, $_REQUEST);
		}

		$donate->validate_transaction();
		trigger_error($lang['THANKS_DONATION']);
	break;

	case 'cancel':
		trigger_error($lang['DONATION_CANCELED'] . '<br /><br />' . sprintf($lang['RETURN_PAGE'], '<a href="' . append_sid($donate->page) . '">', '</a>'));
	break;

	case 'donate':
		if ($submit)
		{

		}
		else
		{
			$donate->hash_str = 'PGEgaHJlZj0iaHR0cDovL2h0dHA6Ly9zdGFydHJla2d1aWRlLmNvbS9jb21tdW5pdHkvdmlld3RvcGljLnBocD90PTI3ODEiPlBheVBhbCBE';
			$donate->hash_str .= 'b25hdGlvbiBNT0Q8L2E+ICZjb3B5IDIwMDggPGEgaHJlZj0iaHR0cDovL3N0YXJ0cmVrZ3VpZGUuY29tIj5TdGFyVHJla0d1aWRlPC9hPg==';

			$donate->add_fields(array(
				'cmd' => '_xclick',
				'business' => ($plugin_config['donations_paypal_sandbox'] || ($plugin_config['donations_debug'] && ($user->data['user_level'] == ADMIN))) ? $plugin_config['donations_paypal_sandbox_address'] : $plugin_config['donations_paypal_address'],
				'item_name' => $config['sitename'],
				'item_number' => 'uid_' . $user->data['user_id'] . '_' . time(),
				'no_shipping' => 1,
				'return' => append_sid($donate->page . '?action=success'),
				'notify_url' => $donate->page . '?action=ipn',
				'cancel_return' => append_sid($donate->page . '?action=cancel'),
				'tax' => 0,
				'bn' => 'PP-DonationsBF',
				)
			);

			$donate->paypal_setup();

			$now = time();
			$sql = 'SELECT perk_title, perk_text, perk_desc_bitfield, perk_desc_options, perk_desc_uid
					FROM ' . DONATIONS_PERKS_TABLE . "
					WHERE perk_expire_date = 0 OR (perk_active_date < $now AND perk_expire_date > $now)
					ORDER BY perk_order";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$template->assign_block_vars('perk', array(
					'PERK_TITLE' => htmlspecialchars($row['perk_title']),
					'PERK_DESCRIPTION' => htmlspecialchars($row['perk_text']),
					)
				);
			}

			$minimum_donation = '<select>' . $donate->minimum_currency_list() . '</select>';

			if (!function_exists('get_group_details'))
			{
				include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
			}
			$group_details = get_group_details($plugin_config['donations_supporters_group_id']);

			$template->assign_vars(array(
				'DONATE_TO_SITENAME' => sprintf($lang['DONATE_TO_SITENAME'], $config['sitename']),
				'DONATE_TO_SITENAME_EXPLAIN'=> sprintf($lang['DONATE_TO_SITENAME_EXPLAIN'], $config['sitename'], $minimum_donation, $group_details['group_name']),
				'MINIMUM_DONATION' => $minimum_donation,
				)
			);

			$donate->display();
		}
	break;
}

full_page_generation($template_to_parse, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>