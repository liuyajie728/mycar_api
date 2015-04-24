<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	/* Order 订单 */
	$route['order/edit/(:any)'] = 'order/edit/$1';
	$route['order/delete/(:any)'] = 'order/delete/$1';
	$route['order/create'] = 'order/create';
	$route['order/(:any)'] = 'order/index/$1';
	$route['order'] = 'order/index';
	
	/* Payment 支付 */
	$route['payment/edit/(:any)'] = 'payment/edit/$1';
	$route['payment/delete/(:any)'] = 'payment/delete/$1';
	$route['payment/create'] = 'payment/create';
	$route['payment/(:any)'] = 'payment/index/$1';
	$route['payment'] = 'payment/index';

	/* Station 加油站 */
	$route['station/edit/(:any)'] = 'station/edit/$1';
	$route['station/delete/(:any)'] = 'station/delete/$1';
	$route['station/create'] = 'station/create';
	$route['station/(:any)'] = 'station/index/$1';
	$route['station'] = 'station/index';
		
		$route['station_brand/(:any)'] = 'station_brand/index/$1';
		$route['station_brand'] = 'station_brand/index';

	/* User 会员 */
	$route['user/login'] = 'user/login';
	$route['user/edit/(:any)'] = 'user/edit/$1';
	$route['user/delete/(:any)'] = 'user/delete/$1';
	$route['user/(:any)'] = 'user/index/$1';
	$route['user'] = 'user/index';

	/* Sms 短信 */
	$route['sms/send'] = 'sms/send';
	$route['sms/balance'] = 'sms/balance';
	$route['sms/(:any)'] = 'sms/index/$1';
	$route['sms'] = 'sms/index';

	/* Coupon 优惠券 */
	$route['coupon/edit/(:any)'] = 'coupon/edit/$1';
	$route['coupon/delete/(:any)'] = 'coupon/delete/$1';
	$route['coupon/create'] = 'coupon/create';
	$route['coupon/(:any)'] = 'coupon/index/$1';
	$route['coupon'] = 'coupon/index';

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