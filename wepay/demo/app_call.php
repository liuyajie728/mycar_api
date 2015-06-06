<?php
/**
 * APP（iOS/Android）支付
 * ====================================================
 * 生成并返回prepay_id到APP。接口输入输出数据格式为JSON。
 *
 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
 * @copyright SenseStrong <www.sensestrong.com>
*/
	include_once('../WxPayPubHelper/WxPayPubHelper.php');
	/*
	$type = $_POST['type'];
	$total_fee = $_POST['total_fee'];
	$order_id = $_POST['order_id'];
	$order_name = $_POST['order_name'];
	*/
	$type = 'consume';
	$total_fee = 1;
	$order_id = 0;
	$order_name = '哎油测试订单';

	//=========步骤1：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	//设置统一支付接口参数
	//设置必填参数
	//appid、mch_id、noncestr、spbill_create_ip、sign已填,商户无需重复填写
	$unifiedOrder->setParameter('notify_url', WxPayConf_pub::NOTIFY_URL); //通知地址
	$unifiedOrder->setParameter('trade_type', 'APP'); //交易类型
	//自定义订单号；与已经成功支付过的订单号相同的订单号将无法成功获取prepayID，因此测试和生产环境时请务必加前缀（by Kamas 'Iceberg' Lau）
	$standard_order_id = 'test_'. $type. '_'. $order_id;
	$unifiedOrder->setParameter('out_trade_no', $standard_order_id); //商户订单号(自定义)
	$unifiedOrder->setParameter('body', $order_name); //订单描述
	$unifiedOrder->setParameter('total_fee', $total_fee * 100); //总金额，数字单位为分
	// 以下非必填参数，可根据实际情况选填
	//$unifiedOrder->setParameter('attach','XXXX');//附加数据
	//$unifiedOrder->setParameter('time_start',date('YmdHis'));//交易起始时间
	//$unifiedOrder->setParameter('time_expire',"XXXX");//交易结束时间

	// 获得的prepay_id
	$data['prepay_id'] = $unifiedOrder->getPrepayId();
	
	$appCall_pub = new AppCall_pub();
	$appCall_pub->generate($data['prepay_id']);
	// 需要发送给iOS/Android的发起微信支付的参数
	$data['appid'] = $appCall_pub->parameters['appid'];
	$data['mch_id'] = $appCall_pub->parameters['mch_id'];
	$data['nonce_str'] = $appCall_pub->parameters['nonce_str'];
	$data['package'] = $appCall_pub->parameters['package'];
	$data['timestamp'] = $appCall_pub->parameters['timestamp'];
	$data['sign'] = $appCall_pub->parameters['sign'];

	// 输出返回的json
	$output['status'] = 200;
	$output['content'] = $data;

	header("Content-type:application/json;charset=utf-8");
	$output_json = json_encode($output);
	echo $output_json;