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
		/**
		* 发送单条短信
		*
		* @param string $mobile 收信人手机号
		* @param string $content 短信内容
		* @return boolean 发送状态（是否发送成功）
		*/
		public function send($mobile, $content)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://sms-api.luosimao.com/v1/send.json');

			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
			curl_setopt($ch, CURLOPT_HEADER, FALSE);

			curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-d0359aad0edf38a18e737a58a17b0918');

			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile, 'message' => $content));

			$res = curl_exec( $ch );
			curl_close( $ch );
			
			return $res;
		}

		/**
		* 发送多条短信
		*
		* @param array $mobile_list 收信人手机号列表
		* @param string content 短信内容
		* @return boolean 发送状态（是否发送成功）
		*/
		/*
		public function send_group($mobile_list, $content)
		{
			
		}
		*/
		
		/**
		* 查询余额
		*
		* @return int $balance 剩余短信条数
		*/
		public function balance()
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL , "http://sms-api.luosimao.com/v1/status.json");
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
					
			curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-d0359aad0edf38a18e737a58a17b0918');

			$res =  curl_exec( $ch );
			curl_close( $ch ); 
			return $res;
		}
	}
	// END Luosimao Class

/* End of file Luosimao.php */
/* Location: ./application/libraries/Luosimao.php */