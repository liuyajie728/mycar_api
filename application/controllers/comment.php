<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	/**
	* Comment Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Comment extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->library('token');
			$this->token->valid($this->input->post('token'));

			$this->load->model('comment_model');
		}

		/**
		* Get comments or certain comment according to comment_id if is provided.
		*
		* @since always
		* @param void
		* @return json Comments or single comment.
		*/
		public function index($comment_id = NULL)
		{
			if ($order_id == NULL && !empty($this->input->post('comment_id'))):
				$order_id = $this->input->post('comment_id');
			endif;
			$output['status'] = 200;
			$output['content'] = $this->comment_model->get($comment_id);

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}

		/**
		* Create comment according to order_id provided.
		*
		* @since always
		* @param void
		* @return json Comment create result.
		*/
		public function create()
		{
			$result = $this->comment_model->create();

			if ($result == TRUE):
				// return created comment id if succeed.
				$output['status'] = 200;
				$output['content'] = '评论成功！';
			else:
				$output['status'] = 400;
				$output['content'] = '评论失败！';
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
		
		/**
		* Append comment according to comment_id provided.
		*
		* @since always
		* @param void
		* @return json Comment append result.
		*/
		public function append()
		{
			$result = $this->comment_model->append();

			if ($result == TRUE):
				$output['status'] = 200;
				$output['content'] = '追加评论成功！';
			else:
				$output['status'] = 400;
				$output['content'] = '追加评论失败！';
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file comment.php */
/* Location: ./application/controllers/comment.php */