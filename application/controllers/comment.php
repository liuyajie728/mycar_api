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
			$token = $this->input->post('token');
			$this->token->valid($token);

			$this->load->model('comment_model');
		}

		/**
		* Get comments or certain comment according to comment_id if is provided.
		*
		* @since always
		* @param void
		* @return json Comments or single comment.
		*/
		public function index()
		{
			$order_id = $this->input->post('order_id')? $this->input->post('order_id'): NULL;
			$comment = $this->comment_model->get_by_order($order_id);

			if (!empty($comment)):
				$output['status'] = 200;
				$output['content'] = $comment;
			else:
				$output['status'] = 400;
				$output['content'] = '未找到相应评论！';
			endif;

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
			$rate_oil = !empty($this->input->post('rate_oil'))? $this->input->post('rate_oil'): NULL;
			$rate_service = !empty($this->input->post('rate_service'))? $this->input->post('rate_service'): NULL;
			$content = !empty($this->input->post('content'))? $this->input->post('content'): NULL;
			
			$order_id = $this->input->post('order_id');
			$result = $this->comment_model->create($order_id, $rate_oil, $rate_service, $content);

			if ($result == TRUE):
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
			// Check if params are valid and not harmful.
			$append = $this->input->post('append');

			$comment_id = $this->input->post('comment_id');
			$result = $this->comment_model->append($comment_id, $append);

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