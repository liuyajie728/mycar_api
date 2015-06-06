<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	// Wepay Setups
	define('WEPAY_APP_ID', 'wx9d3b0b10ea14382d');
	define('WEPAY_MCH_ID', '1242431902');
	define('WEPAY_KEY', 'mycar2014sensestrong201194118436');
	define('WEPAY_APP_SECRET', '9c09b61d634ddfd77b3d9261ebea8a4d');
	define('WEPAY_NOTIFY_URL', 'http://api.irefuel.cn/wepay/notify');
	define('WEPAY_SSLCERT_PATH', 'http://api.irefuel.cn/cert/apiclient_cert.pem');
	define('WEPAY_SSLKEY_PATH', 'http://api.irefuel.cn/cert/apiclient_key.pem');
	
	date_default_timezone_set('Asia/Shanghai');

	/**
	* Native CodeIgniter configs.
	* @since always
	*/
	$config['base_url']	= 'http://api.irefuel.cn/';
	$config['index_page'] = 'index.php';

	$config['uri_protocol']	= 'AUTO';

	$config['url_suffix'] = '';

	$config['language']	= 'chinese';

	$config['charset'] = 'UTF-8';

	$config['enable_hooks'] = FALSE;

	$config['subclass_prefix'] = 'MY_';

	$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

	$config['allow_get_array']		= TRUE;
	$config['enable_query_strings'] = FALSE;
	$config['controller_trigger']	= 'c';
	$config['function_trigger']		= 'm';
	$config['directory_trigger']	= 'd'; // experimental not currently in use

	$config['log_threshold'] = 0;

	$config['log_path'] = '';

	$config['log_date_format'] = 'Y-m-d H:i:s';

	$config['cache_path'] = '';

	$config['encryption_key'] = 'mycar';

	$config['sess_cookie_name']		= 'ci_session';
	$config['sess_expiration']		= 7200;
	$config['sess_expire_on_close']	= FALSE;
	$config['sess_encrypt_cookie']	= FALSE;
	$config['sess_use_database']	= FALSE;
	$config['sess_table_name']		= 'ci_sessions';
	$config['sess_match_ip']		= FALSE;
	$config['sess_match_useragent']	= TRUE;
	$config['sess_time_to_update']	= 300;

	$config['cookie_prefix']	= '';
	$config['cookie_domain']	= '.irefuel.cn/';
	$config['cookie_path']		= '/';
	$config['cookie_secure']	= FALSE;

	$config['global_xss_filtering'] = TRUE;

	$config['csrf_protection'] = FALSE;
	$config['csrf_token_name'] = 'csrf_test_name';
	$config['csrf_cookie_name'] = 'csrf_cookie_name';
	$config['csrf_expire'] = 7200;

	$config['compress_output'] = FALSE;

	$config['time_reference'] = 'Asia/Shanghai';

	$config['rewrite_short_tags'] = FALSE;

	$config['proxy_ips'] = '';

/* End of file config.php */
/* Location: ./application/config/config.php */