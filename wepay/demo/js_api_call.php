<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	include_once('../WxPayPubHelper/WxPayPubHelper.php');

	$type = $_GET['type'];
	$total_fee = $_GET['total_fee'];
	$order_id = $_GET['order_id'];
	$order_name = $_GET['order_name'];

	//使用jsapi接口
	$jsApi = new JsApi_pub();

	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid
	if (!isset($_GET['code'])):
		//触发微信返回code码
		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL. rawurlencode('?showwxpaytitle=1&type='. $type. '&total_fee='. $total_fee. '&order_id='. $order_id. '&order_name='. $order_name));
		Header("Location: $url");

	else:
		//获取code码，以获取openid
	    $code = $_GET['code'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
	endif;

	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	//设置统一支付接口参数
	//设置必填参数
	//appid、mch_id、noncestr、spbill_create_ip、sign已填,商户无需重复填写
	$unifiedOrder->setParameter('openid', $openid); // 步骤1中获取到的openid
	$unifiedOrder->setParameter('notify_url', WxPayConf_pub::NOTIFY_URL); //通知地址
	$unifiedOrder->setParameter('trade_type', 'JSAPI'); //交易类型

	//自定义订单号；与已经成功支付过的订单号相同的订单号将无法成功获取prepayID，因此测试和生产环境时请务必加前缀（by Kamas 'Iceberg' Lau）
	$standard_order_id = 'test_'. $type. '_'. $order_id;
	$unifiedOrder->setParameter('out_trade_no', $standard_order_id); //商户订单号(自定义)
	$unifiedOrder->setParameter('body', $order_name); //订单描述
	$unifiedOrder->setParameter('total_fee', $total_fee * 100); //总金额，数字单位为分
	// 以下非必填参数，可根据实际情况选填
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
	$prepay_id = $unifiedOrder->getPrepayId();

	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);
	$jsApiParameters = $jsApi->getParameters();
?>
<!doctype html>
<html lang=zh-cn>
	<head>
		<meta charset=utf-8>
		<meta http-equiv=x-dns-prefetch-control content=on>
		<link rel=dns-prefetch href="http://cdn.key2all.com">
		<link rel=dns-prefetch href="http://images.key2all.com">
	    <title>哎油 - 订单支付</title>
		<meta name=robots content="noindex, nofollow">
		<meta name=description content="哎油">
		<meta name=keywords content="哎油">
		<meta name=version content="revision20150604">
		<meta name=author content="刘亚杰,童小燕">
		<meta name=copyright content="刘亚杰, 森思壮SenseStrong, 青岛我的车信息技术有限公司, 哎油">
		<meta name=contact content="liuyaji@sensestrong.com, http://weibo.com/sensestrong">
		<meta name=viewport content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!--[if (lt IE 9) & (!IEMobile)]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<![endif]-->

		<link rel=stylesheet media=all href="http://cdn.key2all.com/css/reset.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<link rel=stylesheet media=all href="http://web.irefuel.cn/css/style.css">
		
		<link rel="shortcut icon" href="http://images.key2all.com/logo/brand/6_32.png">
		<link rel="apple-touch-icon" href="http://images.key2all.com/logo/brand/6_120.png">

		<meta name=format-detection content="telephone=yes, email=no, address=no">

		<!-- 苹果设备优化 -->
		<meta name=apple-mobile-web-app-capable content=yes>
		<meta name=apple-mobile-web-app-status-bar-style content=black-translucent>
		<script>
			//调用微信JS api 支付
			function jsApiCall()
			{
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest',
					<?php echo $jsApiParameters ?>,
					function(res)
					{
						if (res.err_msg == 'get_brand_wcpay_request:ok')
						{
							
							// AJAX根据订单号检查订单状态，若支付成功则转到订单详情页
							location.href = 'http://web.irefuel.cn/order/<?php echo $type. '/'. $order_id ?>';
						}
						else if (res.err_msg == 'get_brand_wcpay_request:cancel')
						{
							alert('您已取消支付！');
						}
						else
						{
							alert('支付失败，请重新支付！');
						}
					}
				);
			}

			function callpay()
			{
				if (typeof WeixinJSBridge == 'undefined'){
				    if( document.addEventListener ){
				        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
				    }else if (document.attachEvent){
				        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
				        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
				    }
				}else{
				    jsApiCall();
				}
			}
		</script>
	</head>
<?php
	//将head内容立即输出，让用户浏览器立即开始请求head中各项资源，提高页面加载速度
	ob_flush();flush();
?>
<style>
strong{color:#00a1d8;font-weight:bold;}
ul#order_info{background-color:#fff;margin:20px 0;border-top:1px solid #e8e8e8;border-bottom:1px solid #e8e8e8;}
	ul#order_info li{width:90%;margin:0 auto;height:40px;line-height:40px;border-bottom:1px solid #e8e8e8;}
		ul#order_info li:last-child{border-bottom:0;}
	ul#order_info li span{float:right;}
a.button{color:#fff;font-size:18px;background-color:#00a1d8;display:block;width:90%;margin:0 auto;height:40px;line-height:40px;text-align:center;}
</style>
	<body class=wepay>
		<div id=maincontainer class=container>
			<div id=content>
				<ul id=order_info>
					<li>订单号 <span><?php echo $order_id ?></span></li>
					<li>订单类别 <span><?php echo $type == 'consume'? '消费': '充值'; ?></span></li>
					<li>订单内容 <span><?php echo $order_name ?></span></li>
					<li>支付金额 <span><strong><?php echo $total_fee ?> 元</strong></span></li>
				</ul>
				<a class=button href=# onclick="callpay()">确定支付</a>
			</div>
		</div>
	</body>
<!-- 内容结束 -->
</html>