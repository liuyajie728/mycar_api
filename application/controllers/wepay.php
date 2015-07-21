<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	/**
	* Wepay Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class wepay extends CI_Controller
	{
		// 环境参数
		protected $app_id = WEPAY_APP_ID;
		protected $mch_id = WEPAY_MCH_ID;
		protected $key = WEPAY_KEY;
		protected $app_secret = WEPAY_APP_SECRET;
		protected $sslcert_path = WEPAY_SSLCERT_PATH;
		protected $sslkey_path = WEPAY_SSLKEY_PATH;
		protected $notify_url = WEPAY_NOTIFY_URL;
		protected $trade_type = 'APP';
		
		//请求型接口属性
		public $url; // 微信支付API接口链接
		public $parameters; //请求参数，类型为关联数组
		public $response; //微信返回的响应
		public $result; //返回参数，类型为关联数组
		
		// 响应型接口属性
		public $data; // 接收到的数据，类型为关联数组
		public $returnParameters;// 返回给微信服务器的参数，类型为关联数组
		
		public function __construct()
		{
			parent::__construct();
			
			$this->load->library('token');
			$token = $this->input->post('token');
			$this->token->valid($token);
		}

/**
 * 基础通用方法
**/
		public function trimString($value)
		{
			$ret = NULL;
			if (NULL != $value):
				$ret = $value;
				if (strlen($ret) == 0):
					$ret = NULL;
				endif;
			endif;
			return $ret;
		}

		/**
		 * 	作用：产生随机字符串，不长于32位
		 */
		public function createNoncestr($length = 32)
		{
			$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$str = '';
			for ($i = 0; $i < $length; $i++){  
				$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
			}  
			return $str;
		}

		/**
		 * 	作用：格式化参数，签名过程需要使用
		 */
		public function formatBizQueryParaMap($paraMap, $urlencode)
		{
			$buff = '';
			ksort($paraMap);
			foreach ($paraMap as $k => $v):
			    if($urlencode)
			    {
				   $v = urlencode($v);
				}
				//$buff .= strtolower($k) . '=' . $v . '&';
				$buff .= $k . '=' . $v . '&';
			endforeach;
			$reqPar;
			if (strlen($buff) > 0):
				$reqPar = substr($buff, 0, strlen($buff)-1);
			endif;
			return $reqPar;
		}

		/**
		 * 	作用：生成签名
		 */
		public function getSign($Obj, $method = NULL)
		{
			foreach ($Obj as $k => $v):
				$Parameters[$k] = $v;
			endforeach;
			//签名步骤一：按字典序排序参数
			ksort($Parameters);
			$String = $this->formatBizQueryParaMap($Parameters, FALSE);
			//签名步骤二：在string后加入KEY
			$String = $String. '&key='. $this->key;
			//签名步骤三：MD5加密
			$String = md5($String);
			//签名步骤四：所有字符转为大写
			$result_ = strtoupper($String);

			return $result_;
		}

		/**
		 * 	作用：array转xml
		 */
		function arrayToXml($arr)
	    {
	        $xml = '<xml>';
	        foreach ($arr as $key => $val):
	        	if (is_numeric($val)):
	        	 	$xml.='<'.$key.'>'.$val.'</'.$key.'>';
			 	else:
	        	 	$xml.='<'.$key.'><![CDATA['.$val.']]></'.$key.'>';  
				endif;
			endforeach;
	        $xml .= '</xml>';
	        return $xml;
	    }

		/**
		 * 	作用：将xml转为array
		 */
		public function xmlToArray($xml)
		{		
	        //将XML转为array        
	        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), TRUE);		
			return $array_data;
		}

		/**
		 * 	作用：以post方式提交xml到对应的接口url
		 */
		public function postXmlCurl($xml, $url, $second = 30)
		{
	        //初始化curl        
	       	$ch = curl_init();
			//设置超时
			curl_setopt($ch, CURLOPT_TIMEOUT, $second);

	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//设置header
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			//要求结果为字符串且输出到屏幕上
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			//post提交方式
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			//运行curl
	        $data = curl_exec($ch);
			//返回结果
			if ($data):
				curl_close($ch);
				return $data;
			else:
				$error = curl_errno($ch);
				echo 'curl出错，错误码:'.$error.'<br>'; 
				echo '<a href="http://curl.haxx.se/libcurl/c/libcurl-errors.html">错误原因查询</a><br>';
				curl_close($ch);
				return FALSE;
			endif;
		}

		/**
		 * 	作用：使用证书，以post方式提交xml到对应的接口url
		 */
		public function postXmlSSLCurl($xml, $url, $second = 30)
		{
			$ch = curl_init();
			//超时时间
			curl_setopt($ch, CURLOPT_TIMEOUT, $second);
			
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//设置header
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			//要求结果为字符串且输出到屏幕上
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			//默认格式为PEM，可以注释
			curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
			curl_setopt($ch, CURLOPT_SSLCERT, $this->sslcert_path);
			//默认格式为PEM，可以注释
			curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
			curl_setopt($ch, CURLOPT_SSLKEY, $this->sslkey_path);
			//post提交方式
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			$data = curl_exec($ch);
			//返回结果
			if ($data):
				curl_close($ch);
				return $data;
			else:
				$error = curl_errno($ch);
				echo 'curl出错，错误码:'.$error.'<br>'; 
				echo '<a href="http://curl.haxx.se/libcurl/c/libcurl-errors.html">错误原因查询</a><br>';
				curl_close($ch);
				return FALSE;
			endif;
		}

		/**
		 * 	作用：打印数组
		 */
		public function printErr($wording = '', $err = '')
		{
			print_r('<pre>');
			echo $wording.'<br>';
			var_dump($err);
			print_r('</pre>');
		}

/**
 * 请求型接口的方法
**/
		/**
		 * 	作用：设置请求参数
		 */
		public function setParameter($parameter, $parameterValue)
		{
			$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
		}
	
		/**
		 * 	作用：post请求xml
		 */
		public function postXml($xml)
		{
			$this->response = $this->postXmlCurl($xml, $this->url);
			return $this->response;
		}
	
		/**
		 * 	作用：使用证书post请求xml
		 */
		public function postXmlSSL($xml)
		{
			$this->response = $this->postXmlSSLCurl($xml,$this->url);
			return $this->response;
		}

		/**
		 * 	作用：获取结果，默认不使用证书
		 */
		public function getResult() 
		{		
			$this->postXml();
			$this->result = $this->xmlToArray($this->response);
			return $this->result;
		}

/**
 * 响应型接口方法
**/
	 	function saveData($xml)
	 	{
	 		$this->data = $this->xmlToArray($xml);
	 	}

	 	function checkSign()
	 	{
	 		$tmpData = $this->data;
	 		unset($tmpData['sign']);
	 		$sign = $this->getSign($tmpData);//本地签名
	 		if ($this->data['sign'] == $sign):
	 			return TRUE;
			endif;
	 		return FALSE;
	 	}

	 	/**
	 	 * 获取微信的请求数据
	 	 */
	 	function getData()
	 	{		
	 		return $this->data;
	 	}

	 	/**
	 	 * 设置返回微信的xml数据
	 	 */
	 	function setReturnParameter($parameter, $parameterValue)
	 	{
	 		$this->returnParameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	 	}

	 	/**
	 	 * 生成接口参数xml
	 	 */
	 	function createXml()
	 	{
	 		return $this->arrayToXml($this->returnParameters);
	 	}

	 	/**
	 	 * 将xml数据返回微信
	 	 */
	 	function returnXml()
	 	{
	 		$returnXml = $this->createXml();
	 		return $returnXml;
	 	}
		
/**
 * 具体业务方法
**/
		public function index()
		{
			echo $this->createNoncestr();
			/*
			header('Content-type:application/json;charset=utf-8');
			$output_json = json_encode($output);
			echo $output_json;
			*/
		}
		
		/* 创建微信支付订单 */
		public function unified_order()
		{
			$out_trade_no = $this->input->post('out_trade_no');
			$body = $this->input->post('body');
			$total_fee = $this->input->post('total_fee');

			$this->parameters['out_trade_no'] = $out_trade_no;
			$this->parameters['body'] = $body;
			$this->parameters['total_fee'] = $total_fee * 100; // 默认以分为货币单位

			$this->url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
		   	$this->parameters['appid'] = $this->app_id; // 公众账号ID
		   	$this->parameters['mch_id'] = $this->mch_id; // 商户号
		    $this->parameters['nonce_str'] = $this->createNoncestr(); // 随机字符串
			$this->parameters['spbill_create_ip'] = $_SERVER['REMOTE_ADDR']; // 客户端IP
			$current_timestamp = time();
			$this->parameters['time_start'] = date('YmdHis', $current_timestamp); // 服务器生成订单的时间
			$this->parameters['time_expire'] = date('YmdHis', $current_timestamp + 3*60);; // 订单失效的时间，默认为下单后3分钟
			$this->parameters['notify_url'] = $this->notify_url;
			$this->parameters['trade_type'] = $this->trade_type;
		    $this->parameters['sign'] = $this->getSign($this->parameters); // 根据以上参数生成的签名
		    $xml = $this->arrayToXml($this->parameters);

			$this->postXml($xml);
			$this->result = $this->xmlToArray($this->response);
			$prepay_id = $this->result['prepay_id'];
			$return_parameters['prepayid'] = $prepay_id;
			$return_parameters['appid'] = $this->app_id; //公众账号ID
			$return_parameters['partnerid'] = $this->mch_id;
			$return_parameters['noncestr'] = $this->createNoncestr();
			$return_parameters['package'] = 'Sign=WXPay';
			$return_parameters['timestamp'] = $current_timestamp;
			$return_parameters['sign'] = $this->getSign($return_parameters);

			// 输出返回的json
			$output['status'] = 200;
			$output['content'] = $return_parameters;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}

		/* 接收并回复微信发来的支付结果回调，并根据回调结果处理相应订单 */
		public function notify()
		{
			// 存储微信的回调
			$xml = file_get_contents('php://input');
			$this->saveData($xml);

			if ($this->data['return_code'] == FALSE):
				echo '此接口仅用于接收微信推送的付款状态通知';
				exit;
			endif;
			
			// 验证签名，并回应微信。
			//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
			//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
			//尽可能提高通知的成功率，但微信不保证通知最终能成功。
			if ($this->checkSign() === FALSE):
				$this->setReturnParameter('return_code', 'FAIL'); //返回状态码
				$this->setReturnParameter('return_msg', '签名失败'); //返回信息
			else:
				$this->setReturnParameter('return_code', 'SUCCESS'); //设置返回码
			endif;
			$returnXml = $this->returnXml();
			echo $returnXml;
			
			// 根据通知参数进行相应处理
			if($this->checkSign() === TRUE):
				if ($this->data['return_code'] === 'FAIL'):
					//此处应该更新一下订单状态，商户自行增删操作
					//$log_->log_result($log_name, "【通信出错】:\n". $xml. "\n");

				elseif ($this->data['result_code'] === 'FAIL'):
					//此处应该更新一下订单状态，商户自行增删操作
					//$log_->log_result($log_name, "【业务出错】:\n". $xml. "\n");

				else:
					//此处应该更新一下订单状态，商户自行增删操作
					//$log_->log_result($log_name, "【支付成功】:\n". $xml. "\n");
			
					// RESTful API更新订单状态
					@list($order_prefix, $type, $order_id) = split('_', $this->data['out_trade_no']); // 分解出订单前缀、订单类型（consume或recharge）、哎油订单号等
					$params['type'] = $type; // 从XML中获取type
					$params['order_id'] = $order_id; // 从XML中获取order_id
					$params['status'] = '3';
					$params['payment_type'] = '1'; // 支付方式，全额使用微信支付即为1
					$params['payment_id'] = $this->data['transaction_id']; // 支付流水号，即微信支付订单号
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

				// 商户自行增加处理流程
				//例如：更新订单状态
				//例如：数据库操作
				//例如：推送支付完成信息
			endif;
		}
	}

/* End of file wepay.php */
/* Location: ./application/controllers/wepay.php */