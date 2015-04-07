<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	class Order extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->library('token');
			$this->token->valid($this->input->get('token'));

			$this->load->model('order_model');
		}

		public function index($order_id = NULL)
		{
			$output['status'] = 200;
			$output['content'] = $this->order_model->select($order_id);

			//header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
			
			$this->output->enable_profiler(TRUE);
		}

		// 未完成，将json数据存入mysql
		public function json2mysql($json_content)
		{
			$json_array = json_decode($json_content);
			$this->order_model->insert();
		}
	}

/* End of file order.php */
/* Location: ./application/controllers/order.php */