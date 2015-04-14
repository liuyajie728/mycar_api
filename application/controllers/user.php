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

			//$this->load->library('token');
			//$this->token->valid($this->input->get('token'));

			$this->load->model('sms_model');
			$this->load->model('user_model');
		}
		
		/**
		* Get the information of users or one certain user.
		*
		* @since always
		* @return array Information of user(s)
		*/
		public function index($user_id = NULL)
		{
			$output['status'] = 200;
			$output['content'] = $this->user_model->get($user_id);

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
		
		/**
		* User profile edit.
		*
		* @since always
		* @return boolean Result of profile edit.
		*/
		public function edit($user_id)
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
				$output['status'] = 200;
				// 若相符，则检查该mobile是否已注册
				$user_id = $this->user_model->is_registered('mobile', $mobile);
				if(empty($user_id)): // 若未注册，创建用户
					$user_id = $this->user_model->create();
				endif;
				
				// 根据user_id获取并返回用户信息
				$user_info = $this->user_model->get($user_id);
				$output['content'] = $user_info;

			else:
				// 若不符，则返回失败值
				$output['status'] = 400;
				$output['content'] = '验证码错误';

			endif;

			$output_json = json_encode($output);
			echo $output_json;
		}
		
		// Not finished
		/**
		* User register or create
		*
		* @since always
		* @return boolean Result of creation.
		*/
		private function create()
		{
			$data['class'] = 'user';
			$data['title'] = '创建用户';

			$this->form_validation->set_rules('nickname', '昵称', 'trim|required');
			$this->form_validation->set_rules('userfile', '图片', 'trim');

			//若表单提交不成功
			if($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view('user/create', $data);
				$this->load->view('templates/footer');
				
			else:
				//尝试上传
				$config['upload_path'] = './uploads';
				$config['allowed_types'] = 'jpg|png';
				$config['max_size'] = '4096'; // 文件大小不得高于4M
				$config['max_width']  = '4096';
				$config['max_height']  = '4096';
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config);
				
				//若上传不成功
				if(!$this->upload->do_upload()):
				    $data['error'] = $this->upload->display_errors();

					$this->load->view('templates/header', $data);
					$this->load->view('user/create', $data);
					$this->load->view('templates/footer');
					
				else:
					$data['upload_data'] = $this->upload->data();

					//获取上传的文件路径，与其它表单字段一起写入数据库，并返回刚上传的产品ID
					$local_url = $data['upload_data']['full_path'];

					// 将文件上传到又拍云并将$image_url改为远端URL
					$this->load->library('upyun');
				    $thumbnail_config = array(
				        UpYun::X_GMKERL_TYPE    => 'fix_max', // 缩略图类型为限定最长边，短边自适应
				        UpYun::X_GMKERL_VALUE   => 1024 // 缩略图最大边长
				    );
				    $fh = fopen($local_url, 'rb'); // 载入待上传文件
					$server_url = '/user/logo/'. $data['upload_data']['file_name'];
				    $rsp = $this->upyun->writeFile($server_url, $fh, True, $thumbnail_config); // 上传图片，自动创建目录
				    fclose($fh);
					//var_dump($rsp);

					$image_url = 'http://'. $this->upyun->_bucketname. '.b0.upaiyun.com'. $server_url;
					echo '<img src="'. $image_url .'">';

					// 删除本地文件
					//@unlink($local_url);

					// 创建用户记录
					$item_id = $this->user_model->create($image_url);
					
					//获取数据记录
					$data['user'] = $this->user_model->select($item_id);
					//若新建成功
					/*
					$data['title'] = '保存成功';
					$data['content'] = var_dump($data['upload_data'].$data['user']);
		 		
					$this->load->view('templates/header', $data);
					$this->load->view('user/result', $data);
					$this->load->view('templates/footer');
					*/

				endif;
			endif;
		}
	}

/* End of file user.php */
/* Location: ./application/controllers/user.php */