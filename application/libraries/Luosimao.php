<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	/**
	* 短信发送（luosimao）类
	* http://luosimao.com/docs/api
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Luosimao
	{
		// API_key
		protected $api_key = 'api:key-d0359aad0edf38a18e737a58a17b0918';

		/**
		* Send single sms.
		*
		* @since always
		* @param string $mobile Receiver mobile number
		* @param string $content Sms content
		* @return json Sending status code and return string.
		*/
		public function send($mobile, $content)
		{
			$url = 'http://sms-api.luosimao.com/v1/send.json';
			$params = array('mobile' => $mobile, 'message' => $content);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
			curl_setopt($ch, CURLOPT_HEADER, FALSE);

			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $this->api_key);

			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

			$res = curl_exec($ch);
			curl_close($ch);

			return $res;
		}

		/**
		* Send multipal smss.
		*
		* @since always
		* @param array $mobile_list Receivers mobile number list
		* @param string $content Sms content
		* @return json Sending status code and return string
		*/
		/*
		public function send_group($mobile_list, $content)
		{

		}
		*/
		
		/**
		* Check balance.
		*
		* @since always
		* @param void
		* @return json $balance Sms balance
		*/
		public function balance()
		{
			$url = 'http://sms-api.luosimao.com/v1/status.json';

			$ch = curl_init();			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 

			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $this->api_key);

			$res =  curl_exec($ch);
			curl_close($ch); 
			return $res;
		}
	}
	// END Luosimao Class

/* End of file Luosimao.php */
/* Location: ./application/libraries/Luosimao.php */