<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	/**
	* Stuff Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Stuff extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->library('token');
			$token = $this->input->post('token');
			$this->token->valid($token);

			$this->load->model('stuff_model');
		}

		public function index()
		{
			$stuff_id = $this->input->post('stuff_id')? $this->input->post('stuff_id'): NULL;
			$stuff = $this->stuff_model->get($stuff_id);

			if (!empty($stuff)):
				$output['status'] = 200;
				$output['content'] = $stuff;
			else:
				$output['status'] = 400;
				$output['content'] = '加油站品牌获取失败！';
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file stuff.php */
/* Location: ./application/controllers/stuff.php */