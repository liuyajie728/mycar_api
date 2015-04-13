<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	/**
	* Sms Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Sms extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			// $this->load->library('token');
			// $this->token->valid($this->input->get('token'));

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
			// generate sms content
			$mobile = $this->input->post('mobile'); // mobile number
			$type = $this->input->post('type'); // sms type
			switch ($type) // generate $content according to $type value
			{
				case 1: // for register\login\password reset\email binding sms
					$captcha = random_string('numeric', 4); // generate 4 intergers' string
					$content = '验证码:'. $captcha .'【哎油】';
					break;
				case 2: // for order delivery
					$content = '';
					break;
				default:
					$captcha = random_string('numeric', 4); // generate 4 intergers' string
					$content = '验证码:'. $captcha .'【哎油】';
			}

			$this->load->library('luosimao');
			$result = $this->luosimao->send($mobile, $content);
			$result_array = json_decode($result);
			if ($result_array->error == 0): // if sms sent successfully.
				$output['status'] = 200;
				$output['content'] = 'Sms sent successfully.';
				$output['sms_id'] = $this->sms_model->create($mobile, $content, $type);
				$output_json = json_encode($output);
				echo $output_json;
			endif;
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