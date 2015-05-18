<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	/**
	* User Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class User extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();

			$this->load->library('token');
			$token = $this->input->post('token');
			$this->token->valid($token);

			$this->load->model('sms_model');
			$this->load->model('user_model');
		}

		/**
		* Get the information of users or one certain user.
		*
		* @since always
		* @return array Information of user(s)
		*/
		public function index()
		{
			$user_id = $this->input->post('user_id')? $this->input->post('user_id'): NULL;
			$user = $this->user_model->get($user_id);

			if (!empty($user)):
				$output['status'] = 200;
				$output['content'] = $user;

			else:
				$output['status'] = 400;
				$output['content'] = '用户不存在！';

			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
		
		/**
		* NOT FINISHED. User profile edit.
		*
		* @since always
		* @return boolean Result of profile edit.
		*/
		public function edit()
		{
			$this->output->enable_profiler(TRUE);
		}
		
		/**
		* User Login
		*
		* @since always
		* @return boolean Result of login.
		*/
		public function login()
		{
			$mobile = $this->input->post('mobile');
			$captcha = $this->input->post('captcha');
			$sms_id = $this->input->post('sms_id');

			// 根据sms_id获取短信记录
			$sms_result = $this->sms_model->get($sms_id);
			// 验证mobile和captcha是否与短信中内容相符
			if (($mobile == $sms_result['mobile']) && (strpos($sms_result['content'], $captcha) != FALSE)):
				// 若相符，则检查该mobile是否已注册
				$user_id = $this->user_model->is_registered('mobile', $mobile);
				// 若未注册，创建用户
				if(empty($user_id)):
					$user_id = $this->user_model->create($mobile);
				endif;
				// 根据user_id获取并返回用户信息
				$user_info = $this->user_model->get($user_id);
				@$update_last_activity = $this->user_model->update_certain($user_id, 'time_last_activity', date('Y-m-d H:i:s')); // 更新用户最新活动时间，不必检测是否更新成功。
				$output['status'] = 200;
				$output['content'] = $user_info;

			else:
				// 若不符，则返回失败值
				$output['status'] = 400;
				$output['content'] = '验证码错误';

			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file user.php */
/* Location: ./application/controllers/user.php */