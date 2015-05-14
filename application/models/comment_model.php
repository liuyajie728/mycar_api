<?php
	class Comment_model extends CI_Model
	{
		public $table_name = 'comment';

		// Initiate model
		public function __construct()
		{
			$this->load->database();
		}

		/** Get all comments, or get certain comment by comment_id
		*
		* @since always
		* @param int $comment_id
		* @return array
		*/
		public function get($comment_id = NULL)
		{
			if ($comment_id === NULL):
				$this->db->order_by('time_create desc');
				$this->db->order_by('time_append desc');
				$query = $this->db->get_where($this->table_name, $data);
				return $query->result_array();

			else:
				$data['comment_id'] = $comment_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}

		/**
		* Create comment according to order_id and other params provided
		*
		* @since always
		* @param int $order_id
		* @return int comment_id of created comment.
		*/
		public function create()
		{
			$order_id = $this->input->post('order_id');
			// Get user_id and station_id from relevent order according to order_id.
			$order_info = $this->get_order_info($order_id);
			$data['user_id'] = $order_info['user_id'];
			$data['station_id'] = $order_info['station_id'];

			$data['order_id'] = $order_id;
			$data['rate_oil'] = !empty($this->input->post('rate_oil'))? $this->input->post('rate_oil'): NULL;
			$data['rate_service'] = !empty($this->input->post('rate_service'))? $this->input->post('rate_service'): NULL;
			$data['content'] = !empty($this->input->post('content'))? $this->input->post('content'): NULL;

			if ($this->db->insert($this->table_name, $data)):
				return $this->update_order_status($order_id, '4');
			endif;
		}

		/**
		* Create comment according to order_id and other params provided
		*
		* @since always
		* @param int $order_id
		* @return int comment_id of created comment.
		*/
		public function append()
		{
			$comment_id = $this->input->post('comment_id');
			$data['append'] = $this->input->post('append');
			$data['time_append'] = date('Y-m-d H:i:s');

			$this->db->where('comment_id', $comment_id);
			$append_result = $this->db->update($this->table_name, $data);
			
			if ($append_result == TRUE):
				$comment = $this->get($comment_id);
				$order_id = $comment['order_id'];
				return $this->update_order_status($order_id, '5');
			endif;
		}

		public function get_order_info($order_id)
		{
			$data['order_id'] = $order_id;
			$this->db->select('user_id, station_id');
			$query = $this->db->get_where('order_refuel', $data);
			return $query->row_array();
		}
		
		public function update_order_status($order_id, $new_status)
		{
			$data['status'] = $new_status;
			$this->db->where('order_id', $order_id);
			return $this->db->update('order_refuel', $data);
		}
	}