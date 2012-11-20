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
* Special Thanks to the following individuals for their inspiration:
*  Exreaction (Nathan Guse) http://lithiumstudios.com
*  Micah Carrick (email@micahcarrick.com) http://www.micahcarrick.com
*  Gary White
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

class currency_exchange
{
	private $rates = array();

	private $exchange_url = 'http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml';

	/**
	* Log error messages
	*
	* @param string $message
	*/
	public function log_error($message, $exit = false, $error_type = E_USER_NOTICE, $args = array())
	{
		global $config, $plugin_config;
		$logs_path = !empty($config['logs_path']) ? $config['logs_path'] : 'logs';

		$error_timestamp = gmdate('d-M-Y H:i:s Z');

		$backtrace = '';
		if ($plugin_config['donations_debug'])
		{
			$backtrace = get_backtrace();
			$backtrace = html_entity_decode(strip_tags(str_replace(array('<br />', "\n\n"), "\n", $backtrace)));
		}

		$message = str_replace('<br />', '; ', $message);

		if (sizeof($args))
		{
			$message .= '[args] ';
			foreach ($args as $key => $value)
			{
				$value = urlencode($value);
				$message .= "{$key} = $value; ";
			}
		}

		if ($plugin_config['donations_logging'] || $plugin_config['donations_debug'])
		{
			error_log("[$error_timestamp] $message $backtrace" . "\n", 3, IP_ROOT_PATH . $logs_path . '/transactions.log');
		}

		if ($exit)
		{
			trigger_error($message, $error_type);
		}
	}

	/**
	* Obtain a cached list of currency exchange rates
	*/
	public function obtain_exchange_data()
	{
		global $cache;

		if (($exchange_data = $cache->get('_exchange_rates')) === false)
		{
			if (!function_exists('curl_init'))
			{
				$errno = 0;
				$errstr = '';

				if (!function_exists('get_remote_file'))
				{
					@include_once(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
				}

				$parse_url = parse_url($this->exchange_url);
				$pathinfo = pathinfo($parse_url['path']);

				$port = 80;
				if ($parse_url['scheme'] === 'https')
				{
					$port = 443;
				}

				$exchange_data = get_remote_file($parse_url['host'], $pathinfo['dirname'], $pathinfo['basename'], $errstr, $errno, $port, 30);
			}
			else
			{
				$exchange_data = $this->get_remote_file($this->exchange_url);
			}

			$cache->put('_exchange_rates', $exchange_data, 86400);
		}

		if (!$exchange_data)
		{
			// Hopefully this never happens... but I need to think about a solution if it does.
			$this->log_error($lang['ERROR_NO_EXCHANGE_DATA'], true, E_USER_ERROR);
		}

		if (!function_exists('simplexml_load_string'))
		{
			$this->log_error($lang['PHP5_OR_ABOVE_REQUIRED'], true, E_USER_ERROR);
		}

		$this->xml = simplexml_load_string($exchange_data);
		$this->parse();
	}

	/**
	* If cURL is enabled, we pull the remote data from there.
	*
	* @param unknown_type $url
	* @return unknown
	*/
	private function get_remote_file($url)
	{
		$curl_handle = curl_init();

		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 30);

		$result = curl_exec($curl_handle);

		curl_close($curl_handle);

		return $result;
	}

	/**
	* Convert one currency to another currency.
	*
	* @param string $from_currency
	* @param string $to_currency
	* @param float $amount
	* @return converted value
	*/
	public function convert_currency($from_currency, $to_currency, $amount = 1)
	{
		global $plugin_config;

		if (array_key_exists($from_currency, $this->rates) && array_key_exists($to_currency, $this->rates))
		{
			$rate = ($amount * ($this->rates[$to_currency] / $this->rates[$from_currency]));
			$coversion_percentage = (int) $plugin_config['donations_convert_percentage'] / 100;
			return ((1 + $coversion_percentage) * $rate);
		}
		else
		{
			$error = $debug = array();

			if (!array_key_exists($from_currency, $this->rates))
			{
				$error[] = sprintf($lang['CURRENCY_NOT_RECOGNISED'], $from_currency);
			}

			if (!array_key_exists($to_currency, $this->rates))
			{
				$error[] = sprintf($lang['CURRENCY_NOT_RECOGNISED'], $to_currency);
			}

			if ($plugin_config['donations_debug'])
			{
				$debug = $this->rates;
			}

			$this->log_error(implode('<br />', $error), true, E_USER_NOTICE, $debug);
		}
	}

	/**
	* Parse the XML into an array of data
	*/
	private function parse()
	{
		if ($this->xml)
		{
			$this->rates['EUR'] = 1.00;

			foreach ($this->xml->Cube->Cube->Cube as $row)
			{
				$attributes = $row->attributes();
				$this->rates[(string) $attributes['currency']] = (float) $attributes['rate'];
			}
		}
	}
}

/**
 * paypal class, this function must work with both PHP4 and PHP5, so var method is used.
 * Configuration is done through the ACP Paypal Donation MOD Module, nothing to change within this file.
 */
class paypal_class extends currency_exchange
{
	// Hold the data for field information to send to paypal
	private $fields = array();

	// Data from transaction
	private $data = array();

	// Sender details
	private $sender_data = array();

	// PayPal URL
	public $u_paypal = '';
	public $u_paypal_ver = '';

	// Transaction verified (bool)
	public $verified = false;

	public $page;

	public $hash_str;

	public $currency = array();

	/**
	* __construct, set some initial values
	*/
	public function __construct()
	{
		global $plugin_config;

		// Set the PayPal URL depending on if the board is using the PayPal sandbox (used for debugging and testing)
		//$this->u_paypal = ($plugin_config['donations_paypal_sandbox'] || $plugin_config['donations_debug']) ? 'http://www.sandbox.paypal.com/cgi-bin/webscr' : 'http://www.paypal.com/cgi-bin/webscr';
		$this->u_paypal = ($plugin_config['donations_paypal_sandbox'] || $plugin_config['donations_debug']) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
		$this->u_paypal_ver = ($plugin_config['donations_paypal_sandbox'] || $plugin_config['donations_debug']) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

		$this->obtain_exchange_data();
		$this->currency = $this->currency_data();
	}

	/**
	* Setup the PayPal fields
	*/
	public function paypal_setup()
	{
		global $template;
		// Assign the variables to the template (MVC)
		$template->assign_vars(array(
			'S_DONATE_ACTION' => $this->u_paypal,
			'S_HIDDEN_FIELDS' => build_hidden_fields($this->fields),
			)
		);
	}

	/**
	* List the currency minimum in a list
	*
	* @return option values
	*/
	public function minimum_currency_list()
	{
		global $lang, $plugin_config;

		$s_currency_list = '';

		foreach($lang['currency_code'] as $key => $value)
		{
			$this->currency[$key]['symbol'] = htmlentities($this->currency[$key]['symbol'], ENT_QUOTES, 'UTF-8');

			$selected = ($key == $plugin_config['donations_default_currency']) ? ' selected="selected"' : '';

			$amount = ($key == $plugin_config['donations_default_currency']) ? $plugin_config['donations_donate_minimum'] : $this->convert_currency($plugin_config['donations_default_currency'], $key, $plugin_config['donations_donate_minimum']);
			$amount = number_format($amount, 2, $this->currency[$key]['decimal'], ' ');

			$currency_placement = ($this->currency[$key]['prefix']) ? $this->currency[$key]['symbol'] . $amount . ' ' . $key : $amount . $this->currency[$key]['symbol'] . ' ' . $key;
			$s_currency_list .= '<option' . $selected . ">$currency_placement</option>\n";
		}

		return $s_currency_list;
	}

	/**
	* Send the received data back to PayPal to validate the authenticity of the transaction.
	* set $this->data['confirmed'] = true; if PayPal has verified the transaction.
	*/
	public function validate_data($data = array())
	{
		global $lang;

		$values = array();
		$errstr = $msg = '';
		$errno = 0;

		if (!sizeof($data))
		{
			// Grab the post data from and set in an array to be used in the URI to PayPal
			foreach ($_POST as $key => $value)
			{
				$encoded = urlencode($value);
				$values[] = $key . '=' . $encoded;

				// Assign the values to the $user->data array
				$this->data[$key] = $value;
			}
		}
		else
		{
			foreach ($data as $key => $value)
			{
				$encoded = urlencode($value);
				$values[] = $key . '=' . $encoded;

				$this->data[$key] = $value;
			}
		}

		// Add the cmd=_notify-validate for PayPal
		$values[] = 'cmd=_notify-validate';

		// Implode the array into a string URI
		$params = implode('&', $values);

		// Post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= 'Content-Length: ' . strlen($params) . "\r\n\r\n";

		$parse_url = parse_url($this->u_paypal_ver);

		$port = 80;
		if ($parse_url['scheme'] === 'https')
		{
			$port = 443;
		}

		$fp = fsockopen($parse_url['host'], $port, $errno, $errstr, 30);

		if (!$fp)
		{
			$msg = $errno . ' (' . $errstr . ')';
			$this->log_error($lang['HTTP_ERROR'] . " $errno ($errstr)");
			$this->send_message(true, "$errno ($errstr)<br />\n", $lang['HTTP_ERROR']);
		}
		else
		{
			// Send the data to PayPal
			fputs($fp, $header . $params);

			// Loop through the response
			while (!feof($fp))
			{
				// If the result is not verified...
				if (!$this->verified)
				{
					$line = fgets($fp, 1024);
					$msg .= $line . '<br />';

					// If the line is verified, set verified to true and break out of the loop
					if (strcmp($line, 'VERIFIED') == 0)
					{
						$this->verified = true;
						break;
					}
					elseif (strcmp($line, 'INVALID') == 0)
					{
						// If the line is invalid, set verified to false and break out of the loop
						$this->verified = false;
						break;
					}
				}
			}
			fclose($fp);

			return $msg;
		}
	}

	/**
	* Add a key=>value pair to the fields array, this will be sent to PayPal
	*
	* Usage:
	* <code>
	* $paypal->add_fields(array('field_name' => 'value'));
	* </code>
	* Unlimited array to add fields, single or multiple.
	*
	* @param array $fields
	*/
	public function add_fields($fields)
	{
		if (is_array($fields) && sizeof($fields))
		{
			foreach ($fields as $field => $value)
			{
				$this->fields[$field] = $value;
			}
		}
	}

	/**
	* Post Data back to PayPal to validate
	*/
	public function validate_transaction()
	{
		global $db, $user, $action, $plugin_config;

		$data = array();
		$this->data_list();
		$validate = ($action == 'validate') ? true : false;

		// We ensure that the txn_id (transaction ID) contains only ASCII chars...
		$pos = strspn($this->data['txn_id'], ASCII_RANGE);
		$len = strlen($this->data['txn_id']);

		if ($pos != $len)
		{
			return;
		}

		$decode_ary = array('payer_email', 'payment_date', 'business');
		foreach ($decode_ary as $key)
		{
			$this->data[$key] = urldecode($this->data[$key]);
		}

		if ($validate)
		{
			// If we are trying to confirm a previous transaction -- the first attempt to confirm the transaction failed and the administrator is trying to auto confirm it again
			// I do not believe there is any way this could be abused by non-founder administrators.
			if (!$user->data['session_logged_in'])
			{
				if ($user->data['is_bot'])
				{
					// If the user is a bot, we do not proceed, send the bot back to home page
					redirect(append_sid(CMS_PAGE_HOME));
				}

				// Force the user to login before we continue
				$page_array = array();
				$page_array = extract_current_page(IP_ROOT_PATH);
				redirect(append_sid( CMS_PAGE_LOGIN . '?redirect=' . str_replace(('.' . PHP_EXT . '?'), ('.' . PHP_EXT . '&'), $page_array['page']), true));
			}

			// If the user is not an administrator, we cannot continue
			if ($user->data['user_level'] != ADMIN)
			{
				trigger_error('NOT_AUTHORIZED');
			}

			// If there is no transaction ID, we cannot continue
			if (!$this->data['txn_id'])
			{
				trigger_error('NO_TRANSACTION_ID');
			}

			// Select all the records in the DB for this transaction
			$sql = 'SELECT *
					FROM ' . DONATIONS_DATA_TABLE . "
					WHERE txn_id = '" . $this->data['txn_id'] . "'";
			$result = $db->sql_query($sql);
			$data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			// There is no transaction ID or no data was returned
			if (!sizeof($data) || !$data['txn_id'])
			{
				$this->log_error($lang['INVALID_TRANSACTION_RECORD'], true);
			}

			// If data['confirmed'] is set to true, this record has already been verified and confirmed.
			if ($data['confirmed'] == true)
			{
				$this->log_error($lang['TRANSACTION_ALREADY_CONFIRMED'], true, E_USER_NOTICE, array($data['txn_id']));
			}

			$msg = $this->validate_data($data);
			$this->data['confirmed'] = ($this->verified) ? true : false;
			$this->log_to_db(true);

			if ($this->verified)
			{
				trigger_error('TRANSACTION_VERIFIED');
			}
			else
			{
				trigger_error($lang['TRANSACTION_VERIFICATION_FAILED'] . "\n<hr /><br />\n" . $msg, E_USER_ERROR);
			}
		}
		elseif (!$this->data['txn_id'])
		{
			$this->log_error($lang['INVALID_TRANSACTION_RECORD'], true, E_USER_NOTICE, $this->data);
		}

		$this->validate_data();

		// Set confirmed to true/false depending on if the transaction was verified.
		$this->data['confirmed'] = ($this->verified) ? true : false;

		// The item number contains the user_id and the payment time in timestamp format
		list($uid, $this->data['user_id'], $this->data['payment_time']) = explode('_', $this->data['item_number']);

		$anonymous_user = false;

		// If the user_id is not anonymous, get the user information (user id, username)
		if ($this->data['user_id'] != ANONYMOUS)
		{
			$sql = 'SELECT user_id, username
					FROM ' . USERS_TABLE . '
					WHERE user_id = ' . (int) $this->data['user_id'];
			$result = $db->sql_query($sql);
			$this->sender_data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if (!sizeof($this->sender_data))
			{
				// No results, therefore the user is anonymous...
				$anonymous_user = true;
			}
		}
		else
		{
			// The user is anonymous by default
			$anonymous_user = true;
		}

		if ($anonymous_user)
		{
			// If the user is anonymous, check the paypal email address with all known email to determine if the user exists in the database with that email
			$sql = "SELECT user_id, username
					FROM " . USERS_TABLE . "
					WHERE LOWER(user_email) = '" . strtolower($this->data['payer_email']) . "'";
			$result = $db->sql_query($sql);
			$this->sender_data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if (!sizeof($this->sender_data))
			{
				// No results, therefore the user is really a guest
				$this->sender_data = false;
			}
		}

		// If the user is registered, we check to ensure they have donated the minimum amount before being added to the supporters group
		if ($this->sender_data)
		{
			// Set the minimum amount to the config minimum amount value
			$minimum_amount = $plugin_config['donations_donate_minimum'];
			if ($this->data['mc_currency'] != $plugin_config['donations_default_currency'])
			{
				// If the payer currency is not the default currency, convert the default currency to the payer currency to determine if they paid the minimum in that currency.
				$minimum_amount = $this->convert_currency($plugin_config['donations_default_currency'], $this->data['mc_currency'], $plugin_config['donations_donate_minimum']);
			}

			// If they meet or exceed the minimum amount, add the user to the supporters group and set as default.
			if (!$anonymous_user && ($this->data['mc_gross'] >= $minimum_amount))
			{
				if (!function_exists('group_user_add'))
				{
					include(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
				}
				group_user_add($plugin_config['donations_supporters_group_id'], $this->sender_data['user_id'], false);
			}
		}

		//$this->send_message();
		$this->log_to_db();
	}

	/**
	* Send a message to the Founders informing them of the Donation received.
	* Echo information based on if the donation is verified or unverified.
	*
	* @param bool $send_pm
	* @param string $message
	* @param string $subject
	*/
	private function send_message($send_pm = false, $message = '', $subject = '')
	{
		global $db, $config, $user, $plugin_config;

		if (!$subject)
		{
			$l_title = ($this->verified) ? 'DONATION_RECEIVED_VERIFIED' : 'DONATION_RECEIVED_UNVERIFIED';

			$subject = sprintf($lang[$l_title], ($this->sender_data) ? $this->sender_data['username'] : $lang['GUEST']);
		}

		if (!$message)
		{
			$currency = $this->currency[$this->data['mc_currency']];
			$currency_format = ($currency['prefix']) ? $currency['symbol'] . $this->data['mc_gross'] : $this->data['mc_gross'] . $currency['symbol'];
			$amount = $currency_format . ' ' . $this->data['mc_currency'];

			$message = ($this->verified) ? 'DONATION_RECEIVED_MSG_VERIFIED' : 'DONATION_RECEIVED_MSG_UNVERIFIED';
			$message = sprintf($user->lang[$message], $this->data['payer_email'], ($this->sender_data) ? $this->sender_data['username'] : $lang['GUEST'], $amount);

			// if there is a memo, add the memo to the message
			if (!empty($this->data['memo']))
			{
				$message .= "\n\n" . $lang['DONATION_MESSAGE'] . ":\n\n" . $db->sql_escape($this->data['memo']);
			}

			// if the transaction is not verified, all the admin to manually verify the transaction.
			if (!$this->verified)
			{
				$message .= "\n\n" . sprintf($lang['TRANSACTION_NOT_VERIFIED'], $this->page, 'action=validate&amp;txn_id=' . $this->data['txn_id']);
			}
		}

		// Determine if we are sending a PM or e-mailing the founders instead.
		if ($plugin_config['donations_send_pm'] || $send_pm)
		{
			$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());

			include_once(IP_ROOT_PATH . 'includes/class_pm.' . PHP_EXT);
			$privmsg_subject = $subject;
			$privmsg_message = $message;
			$privmsg_sender = ($this->sender_data) ? $this->sender_data['user_id'] : $user->data['user_id'];
			$privmsg_recipient = $founder_id;

			$privmsg = new class_pm();
			$privmsg->delete_older_message('PM_INBOX', $privmsg_recipient);
			$privmsg->send($privmsg_sender, $privmsg_recipient, $privmsg_subject, $privmsg_message);
			unset($privmsg);
		}
		else
		{
			$sender_id = ($this->sender_data) ? $this->sender_data['user_id'] : $user->data['user_id'];
			$sender = ($this->sender_data) ? $this->sender_data['username'] : $lang['GUEST'];

			include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
			$emailer = new emailer();

			$emailer->headers('X-AntiAbuse: Board servername - ' . trim($config['server_name']));
			$emailer->headers('X-AntiAbuse: User_id - ' . $sender_id);
			$emailer->headers('X-AntiAbuse: Username - ' . $sender_name);
			$emailer->headers('X-AntiAbuse: User IP - ' . $user->ip);

			$emailer->use_template('empty_email', $user_lang);
			$emailer->to($config['board_email']);
			$emailer->from($sender);
			$emailer->replyto($sender);
			$emailer->set_subject($subject);

			$emailer->assign_vars(array(
				'MESSAGE' => $message
				)
			);
			$emailer->send();
			$emailer->reset();
		}
	}

	/**
	* Send final variables to the template for display.
	*/
	public function display()
	{
		global $template;

		if (!$this->hash_str)
		{
			$this->hash_str = 'Q29weXJpZ2h0IFJlbW92ZWQsIE1PRCBkaXNhYmxlZA==';
			trigger_error($hash_str);
		}

		$string = ((!empty($lang['TRANSLATION_INFO'])) ? $lang['TRANSLATION_INFO'] . '<br />' : '') . base64_decode($this->hash_str);

		$lang['TRANSLATION_INFO'] = $string;

		$template->assign_vars(array(
			'S_CURRENCY_OPTIONS' => currency_options(),
			'S_COUNTRY_OPTIONS' => country_options(),
			'S_DONATE_ACTION' => $this->u_paypal,
			)
		);
	}

	/**
	* Log the transaction to the database
	*
	* @param bool $update -- update an existing transaction or insert a new transaction
	*/
	public function log_to_db($update = false)
	{
		global $db;

		list($uid, $this->data['user_id'], $this->data['payment_time']) = explode('_', $this->data['item_number']);

		// list the data to be thrown into the database
		$sql_ary = array(
			'confirmed' => $this->data['confirmed'],
			'user_id' => $this->data['user_id'],
			'txn_id' => $this->data['txn_id'],
			'txn_type' => $this->data['txn_type'],

			'item_name' => $this->data['item_name'],
			'item_number' => $this->data['item_number'],
			'business' => $this->data['business'],

			'payment_status' => $this->data['payment_status'],
			'payment_gross' => $this->data['mc_gross'],
			'payment_fee' => $this->data['payment_fee'],
			'payment_type' => $this->data['payment_type'],
			'payment_time' => $this->data['payment_time'],
			'mc_currency' => $this->data['mc_currency'],
			'payment_date' => $this->data['payment_date'],

			'payer_id' => $this->data['payer_id'],
			'payer_email' => $this->data['payer_email'],
			'payer_status' => $this->data['payer_status'],
			'first_name' => $this->data['first_name'],
			'last_name' => $this->data['last_name'],

			'memo' => $this->data['memo'],
		);

		if ($update)
		{
			$sql = 'UPDATE ' . DONATIONS_DATA_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE txn_id = '" . $this->data['txn_id'] . "'";
			$db->sql_query($sql);
		}
		else
		{
			// insert the data
			$sql = 'INSERT INTO ' . DONATIONS_DATA_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);
		}
	}

	/**
	* Setup the data list with default values.
	*/
	private function data_list()
	{
		$data_ary = array(
			'txn_id' => '', // Transaction ID
			'txn_type' => '', // Transaction type - Should be: 'send_money'

			'item_name' => '', // $config['sitename']
			'item_number' => '', // 'uid_' . $user->data['user_id'] . '_' . time()
			'business' => '', // $config['board_contact']

			'payment_status' => '', // 'Completed'
			'mc_gross' => '', // Amt received (before fees)
			'payment_gross' => '', // Amt received (before fees)
			'payment_fee' => '', // Amt of fees
			'payment_type' => '', // Payment type
			'mc_currency' => '', // Currency
			'payment_date' => '', // Payment Date/Time EX: '19:08:04 Oct 03, 2007 PDT'

			'payer_id' => '', // Paypal sender ID
			'payer_email' => '', // Paypal sender email address
			'payer_status' => '', // Paypal sender status (verified, unverified?)
			'first_name' => '', // First name of sender
			'last_name' => '', // Last name of sender
			'memo' => '', // Memo sent by the donor
		);

		$this->data['confirmed'] = false; // used to check if the payment is confirmed

		foreach ($data_ary as $key => $default)
		{
			$this->data[$key] = request_var($key, $default, true);
		}
	}

	/**
	* List each currency symbol, prefix or suffix and decimal in an array of options
	* Used to merge with $user->lang['currency_code'] array.
	*
	* @return array $currency_code
	*/
	private function currency_data()
	{
		$currency_code = array(
			'USD' => array('symbol' => '$', 'prefix' => true, 'decimal' => '.'),
			'AUD' => array('symbol' => '$', 'prefix' => true, 'decimal' => '.'),
			'CAD' => array('symbol' => '$', 'prefix' => true, 'decimal' => ','),
			'CZK' => array('symbol' => 'Kč', 'prefix' => false, 'decimal' => ','),
			'DKK' => array('symbol' => 'Kr', 'prefix' => false, 'decimal' => ','),
			'EUR' => array('symbol' => '€', 'prefix' => true, 'decimal' => ','),
			'HKD' => array('symbol' => 'HK$', 'prefix' => false, 'decimal' => NULL),
			'HUF' => array('symbol' => 'Ft', 'prefix' => false, 'decimal' => ','),
			'NZD' => array('symbol' => '$', 'prefix' => true, 'decimal' => '.'),
			'NOK' => array('symbol' => 'kr', 'prefix' => false, 'decimal' => ','),
			'PLN' => array('symbol' => 'zł', 'prefix' => false, 'decimal' => ','),
			'GBP' => array('symbol' => '£', 'prefix' => false, 'decimal' => '.'),
			'SGD' => array('symbol' => '$', 'prefix' => true, 'decimal' => '.'),
			'SEK' => array('symbol' => 'kr', 'prefix' => false, 'decimal' => ','),
			'CHF' => array('symbol' => 'CHF', 'prefix' => false, 'decimal' => ','),
			'JPY' => array('symbol' => '¥', 'prefix' => false, 'decimal' => NULL),
		);

		return $currency_code;
	}
}


/**
* List the currency options defined in the language file
*
* @return option list $s_currency_options
*/
function currency_options($default = 0)
{
	global $lang, $plugin_config;

	$default = ($default) ? $default : $plugin_config['donations_default_currency'];

	// setup a list of currencies
	$s_currency_options = '';

	// if currencies need to be removed, they may be done so from the language file
	foreach ($lang['currency_code'] as $key => $value)
	{
		$selected = ($key == $default) ? ' selected="selected"' : '';
		$s_currency_options .= '<option value="' . $key . '"' . $selected . '>' . $value . ' (' . $key . ")</option>\n";
	}

	return $s_currency_options;
}

/**
* List the country options defined in the language file
*
* @return option list $s_country_options
*/
function country_options($default = '')
{
	global $lang, $plugin_config;

	$default = ($default) ? $default : $plugin_config['donations_default_country'];

	// Setup a list of Countries.
	$s_country_options = '';

	// If Countries need to be removed, they may be done so from the language file.
	foreach ($lang['country_options'] as $key => $value)
	{
		$selected = ($key == $default) ? ' selected="selected"' : '';
		$s_country_options .= '<option value="' . $key . '"' . $selected . '>' . $value . "</option>\n";
	}

	return $s_country_options;
}

?>