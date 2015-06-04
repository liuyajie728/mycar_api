<?php
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
	include_once('./log_.php');
	include_once('../WxPayPubHelper/WxPayPubHelper.php');

    //使用通用通知接口
	$notify = new Notify_pub();

	//存储微信的回调
	$xml = file_get_contents('php://input');
	$notify->saveData($xml);

	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if ($notify->checkSign() === FALSE):
		$notify->setReturnParameter('return_code', 'FAIL'); //返回状态码
		$notify->setReturnParameter('return_msg', '签名失败'); //返回信息
	else:
		$notify->setReturnParameter('return_code', 'SUCCESS'); //设置返回码
	endif;

	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	//以log文件形式记录回调信息
	$log_ = new Log_();
	$log_name = 'notify_url.log'; //log文件路径
	$log_->log_result($log_name, "【接收到的notify通知】:\n". $xml. "\n");

	if($notify->checkSign() === TRUE):
		if ($notify->data['return_code'] === 'FAIL'):
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name, "【通信出错】:\n". $xml. "\n");

		elseif ($notify->data['result_code'] === 'FAIL'):
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name, "【业务出错】:\n". $xml. "\n");

		else:
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name, "【支付成功】:\n". $xml. "\n");
			
			// RESTful API更新订单状态
			@list($order_prefix, $type, $order_id) = split('_', $notify->data['out_trade_no']); // 分解出订单前缀、订单类型（consume或recharge）、哎油订单号等
			$params['type'] = $type; // 从XML中获取type
			$params['order_id'] = $order_id; // 从XML中获取order_id
			$params['status'] = '3';
			$params['payment_type'] = '1'; // 支付方式，全额使用微信支付即为1
			$params['payment_id'] = $notify->data['transaction_id']; // 支付流水号，即微信支付订单号
			// 通过RESTful API更新订单状态
			$params['token'] = '7C4l7JLaM3Fq5biQurtmk6nFS';
			$url = 'http://api.irefuel.cn/order/update_status';
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_URL, $url);

		    // 设置cURL参数，要求结果保存到字符串中还是输出到屏幕上。
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_ENCODING, 'UTF-8');
			curl_setopt($curl, CURLOPT_POST, count($params));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	
		    // 运行cURL，请求API
			$result = curl_exec($curl);

			// 关闭URL请求
		    curl_close($curl);
		endif;

		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	endif;