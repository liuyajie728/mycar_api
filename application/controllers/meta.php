<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	/**
	* Meta Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class meta extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->library('token');
			$token = $this->input->post('token');
			$this->token->valid($token);

			$this->load->model('meta_model');
		}

		/**
		* Get the information of metas or one certain meta.
		*
		* @since always
		* @param int/string $meta_id meta id or name posted in.
		* @return json Information of meta(s)
		*/
		public function index()
		{
			$meta_id = $this->input->post('meta_id')? $this->input->post('meta_id'): NULL;
			$meta = $this->meta_model->get($meta_id);

			if (!empty($meta)):
				$output['status'] = 200;
				$output['content'] = $meta;
			else:
				$output['status'] = 400;
				$output['content'] = '未找到相应应用信息！';
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file meta.php */
/* Location: ./application/controllers/meta.php */