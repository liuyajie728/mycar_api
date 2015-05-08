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
			//$article_id = $this->input->post('article_id')? $this->input->post('article_id'): NULL;
			$article_id = 1;

			if (is_int($article_id) or $article_id === NULL):
				$output['content'] = $this->article_model->get($article_id);
			else:
				$output['content'] = $this->article_model->get_by_nicename($article_id);
			endif;

			$output['status'] = 200;
			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file article.php */
/* Location: ./application/controllers/article.php */