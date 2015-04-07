<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	/**
	* Token类
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	* @since always
	*/
	class Token
	{
		/**
		* Validate the token to decide if the requirement are authenticated.
		*
		* @param string $token The string that should be provided.
		* @return int $status The status judged by processing the token.
		*/
		public function valid($token)
		{
			if ($token == ''):
				$output['status'] = 400;
				$output['content'] = 'A token is required to access this API. Apply for one if you could.';
				// 输出错误提示
				header("Content-type:application/json;charset=utf-8");
				$output_json = json_encode($output);
				echo $output_json;
				exit;
			else:
				$CI =& get_instance();
				$CI->load->model('token_model');
				$status_code = $CI->token_model->valid($token);
				if ($status_code != 200):
					$output['status'] = $status_code;
					switch ($status_code)
					{
						case 401:
							$output['content'] = 'Token is no longer valid. Apply for a new token if you could.';
							break;
						case 404:
							$output['content'] = 'Token does not exits. Apply for a token to get access to the API you need.';
							break;
						default:
							$output['content'] = 'Token is not valid.';
					}
					// 输出错误提示
					header("Content-type:application/json;charset=utf-8");
					$output_json = json_encode($output);
					echo $output_json;
					exit;
				endif;
			endif;
		}
	}
	// END Token Class

/* End of file Token.php */
/* Location: ./application/libraries/Token.php */