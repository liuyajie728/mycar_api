<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	/**
	* Token Class
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
		* @since always
		*/
		public function valid($token)
		{
			if ($token == ''): // If token is not provided
				$output['status'] = 400;
				$output['content'] = 'A token is required to access this API. Apply for one if you could.';
				// 输出错误提示
				header("Content-type:application/json;charset=utf-8");
				$output_json = json_encode($output);
				echo $output_json;
				exit;

			elseif (strlen($token) <> 25): // If token does not contain exactly 25 characters
				$output['status'] = 401;
				$output['content'] = 'The token is not in correct format, check your input to fix it if you know the required format.'. strlen($token);

				// Output error information
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
						case 403:
							$output['content'] = 'Token is no longer valid. Apply for a new token if you could.';
							break;
						case 404:
							$output['content'] = 'Token does not exits. Apply for a token to get access to the API you need.';
							break;
						default:
							$output['content'] = 'Token is not valid.';
					}
					// Output error information
					header("Content-type:application/json;charset=utf-8");
					$output_json = json_encode($output);
					echo $output_json;
					exit;
				endif;
			endif;
		}
		
		/**
		* Generate a token & register it in the database
		*
		* @return string $token Generated Token
		* @since always
		*/
		public function create()
		{
			$CI =& get_instance();
			$CI->load->model('token_model');
			$token = $CI->token_model->create();
			return $token;
		}
	}
	// END Token Class

/* End of file Token.php */
/* Location: ./application/libraries/Token.php */