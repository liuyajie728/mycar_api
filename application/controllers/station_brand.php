<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	/**
	* Station_brand Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Station_brand extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();

			//$this->load->library('token');
			//$this->token->valid($this->input->post('token'));

			$this->load->model('station_brand_model');
		}
		
		/**
		* Get the information of station brands or one certain station brand.
		*
		* @since always
		* @return array Information of station brand(s)
		*/
		public function index($brand_id = NULL)
		{
			if ($this->input->is_ajax_request()):
				$station_id = $this->input->post('brand_id');
				$output['status'] = 200;
				$output['content'] = $this->station_brand_model->get($brand_id);
			else:
				$output['status'] = 200;
				$output['content'] = $this->station_brand_model->get($brand_id);
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file station_brand.php */
/* Location: ./application/controllers/station_brand.php */