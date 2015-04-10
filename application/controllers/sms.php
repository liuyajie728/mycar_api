<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	class Sms extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			//$this->load->library('token');
			//$this->token->valid($this->input->get('token'));

			$this->load->model('sms_model');
		}

		public function index($sms_id = NULL)
		{
			$output['status'] = 200;
			$output['content'] = $this->sms_model->select($sms_id);

			//header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
			
			$this->output->enable_profiler(TRUE);
		}

		// 未完成，将json数据存入mysql
		public function json2mysql($json_content)
		{
			$json_array = json_decode($json_content);
			$this->sms_model->insert();
		}

		// 调用luosimao类发送短信
		public function send()
		{
			$data['title'] = '短信发送';
			$this->load->view('templates/header', $data);
			$this->load->view('sms/create', $data);
			$this->load->view('templates/footer');

			$type = $this->input->post('type'); // 短信类型
			$mobile = $this->input->post('mobile'); // 表单提供的手机号
			$captcha = random_string('numeric', 4); // 生成4位随机数字
			$content = '验证码:'. $captcha .'【哎油】';
			echo $mobile, $content;

			$this->load->library('luosimao');
			$result = $this->luosimao->send($mobile, $content);
			$result_array = json_decode($result);
			if ($result_array->error == 0): // If sms sent successfully.
				$output['status'] = 200;
				$output['content'] = 'Sms sent successfully.';
				$output['sms_id'] = $this->sms_model->create($mobile, $content, $type);
				$output_json = json_encode($output);
				echo $output_json;
			endif;

			$this->output->enable_profiler(TRUE);
		}

		// 调用sms类查询短信余额
		public function balance()
		{
			$this->load->library('luosimao');
			$balance = $this->luosimao->balance();
			var_dump($balance);
		}
	}

/* End of file sms.php */
/* Location: ./application/controllers/sms.php */