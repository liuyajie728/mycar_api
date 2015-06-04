<?php
/**
 * 对账单接口demo
 * ====================================================
 * 商户可以通过该接口下载历史交易清单。
*/
	include_once("../WxPayPubHelper/WxPayPubHelper.php");

	//对账单日期
	if (!isset($_POST['bill_date'])):
		$bill_date = date('Ymd');
	else:
	    $bill_date = $_POST['bill_date'];
		
		//使用对账单接口
		$downloadBill = new DownloadBill_pub();
		//设置对账单接口参数
		//设置必填参数
		//appid、mch_id、noncestr、spbill_create_ip、sign已填,商户无需重复填写
		$downloadBill->setParameter('bill_date',"$bill_date");//对账单日期 
		$downloadBill->setParameter('bill_type','ALL');//账单类型
		
		//对账单接口结果
		$downloadBillResult = $downloadBill->getResult();
		echo $downloadBillResult['return_code'];
		
		if ($downloadBillResult['return_code'] == 'FAIL'):
			echo '通信出错：'.$downloadBillResult['return_msg'];
		else:
			 print_r('<pre>');
			 echo '对账单详<br>';
			 print_r($downloadBill->response);
			 print_r('</pre>');
		 endif;
	endif;
?>
<!doctype html>
<html lang=zh-cn>
<head>
	<meta charset=utf-8>
	<title>微信安全支付</title>
</head>
<body>
	<div>
		<form action="./download_bill.php" method=post>
			<p>对账单查询</p>
			<p>日期(格式：20140101): <input type=text name=bill_date value="<?php echo $bill_date; ?>"></p>
		    <button>提交</button>
		</form>
		<a href="../index.php">返回首页</a>
	</div>
</body>
</html>