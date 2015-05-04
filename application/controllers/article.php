<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	/**
	* Article Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Article extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			// $this->load->library('token');
			// $this->token->valid($this->input->post('token'));

			$this->load->model('article_model');
		}

		/**
		* Get the information of articles or one certain article.
		*
		* @since always
		* @return json Information of article(s)
		*/
		public function index()
		{
			$output['status'] = 200;
			$output['content'] = $this->article_model->get();

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file article.php */
/* Location: ./application/controllers/article.php */