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

			//$this->load->library('token');
			//$this->token->valid($this->input->post('token'));

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
			$output['status'] = 200;
			$output['content'] = $this->station_model->get($station_id);

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file station.php */
/* Location: ./application/controllers/station.php */