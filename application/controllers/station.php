<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	/**
	* Station Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Station extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();

			$this->load->library('token');
			$token = $this->input->post('token');
			$this->token->valid($token);

			$this->load->model('station_model');
		}
		
		/**
		* Get the information of stations or one certain station.
		*
		* @since always
		* @return array Information of station(s)
		*/
		public function index()
		{
			$station_id = $this->input->post('station_id')? $this->input->post('station_id'): NULL;
			$station = $this->station_model->get($station_id);

			if (!empty($station)):
				$output['status'] = 200;
				$output['content'] = $station;
			else:
				$output['status'] = 400;
				$output['content'] = '加油站品牌获取失败！';
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file station.php */
/* Location: ./application/controllers/station.php */