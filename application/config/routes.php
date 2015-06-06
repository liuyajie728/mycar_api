<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	$route['wepay/unified_order'] = 'wepay/unified_order';
	$route['wepay/notify'] = 'wepay/notify';
	$route['wepay'] = 'wepay/index';
	
	/* 应用信息 */
	$route['meta'] = 'meta/index';

	/* Order 订单 */
	$route['order/update_status'] = 'order/update_status';
	$route['order/create'] = 'order/create';
	$route['order/consume/(:any)'] = 'order/index/$1';
	$route['order/consume'] = 'order/index';
	$route['order/recharge/(:any)'] = 'order/index_recharge/$1';
	$route['order/recharge'] = 'order/index_recharge';
	$route['order/(:any)'] = 'order/index/$1';
	$route['order'] = 'order/index';

	/* Station 加油站 */
	$route['station/edit/(:any)'] = 'station/edit/$1';
	$route['station/delete/(:any)'] = 'station/delete/$1';
	$route['station/create'] = 'station/create';
	$route['station/(:any)'] = 'station/index/$1';
	$route['station'] = 'station/index';

		/* Station_brand 加油站品牌 */
		$route['station_brand/(:any)'] = 'station_brand/index/$1';
		$route['station_brand'] = 'station_brand/index';

	/* Comment */
	$route['comment/create'] = 'comment/create'; // 评价订单
	$route['comment/append'] = 'comment/append'; // 追加评价
	$route['comment/station'] = 'comment/index_by_station';
	$route['comment'] = 'comment/index';

	/* User 会员 */
	$route['user/login'] = 'user/login';
	$route['user/update'] = 'user/update';
	$route['user/(:any)'] = 'user/index/$1';
	$route['user'] = 'user/index';

	/* Sms 短信 */
	$route['sms/send'] = 'sms/send';
	$route['sms/balance'] = 'sms/balance';
	$route['sms/(:any)'] = 'sms/index/$1';
	$route['sms'] = 'sms/index';

	/* 文章 */
	$route['article'] = 'article/index';

	/* Stuff 员工 */
	$route['stuff/edit/(:any)'] = 'stuff/edit/$1';
	$route['stuff/delete/(:any)'] = 'stuff/delete/$1';
	$route['stuff/create'] = 'stuff/create';
	$route['stuff/(:any)'] = 'stuff/index/$1';
	$route['stuff'] = 'stuff/index';

	$route['default_controller'] = 'index';
	$route['404_override'] = '';

/* End of file routes.php */
/* Location: ./application/config/routes.php */