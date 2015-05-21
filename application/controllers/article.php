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
			
			$this->load->library('token');
			$token = $this->input->post('token');
			$this->token->valid($token);

			$this->load->model('article_model');
		}

		/**
		* Get the information of articles or one certain article.
		*
		* @since always
		* @param int/string $article_id Article id or nicename posted in.
		* @return json Information of article(s)
		*/
		public function index()
		{
			$article_id = $this->input->post('article_id')? $this->input->post('article_id'): NULL;
			$article = $this->article_model->get($article_id);

			if (!empty($article)):
				$output['status'] = 200;
				$output['content'] = $article;
			else:
				$output['status'] = 400;
				$output['content'] = '未找到相应文章！';
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file article.php */
/* Location: ./application/controllers/article.php */