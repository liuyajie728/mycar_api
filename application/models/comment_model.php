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
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['comment_id'] = $comment_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
		
		public function get_by_station($station_id = NULL)
		{
			$data['station_id'] = $station_id;
			// 需根据每条评论的user_id从user表中获取用户nickname及logo_url
			$this->db->select($this->table_name.'.*, user.nickname as user_nickname, user.logo_url as user_logo_url');
			$this->db->join('user', 'comment.user_id = user.user_id', 'left outer');
			$query = $this->db->get_where($this->table_name, $data);
			return $query->result_array();
		}

		public function get_by_order($order_id = NULL)
		{
			$data['order_id'] = $order_id;
			$query = $this->db->get_where($this->table_name, $data);
			return $query->row_array();
		}

		/**
		* Create comment according to order_id and other params provided
		*
		* @since always
		* @param int $order_id
		* @return int comment_id of created comment.
		*/
		public function create($order_id, $rate_oil, $rate_service, $content)
		{
			// Get user_id and station_id from relevent order according to order_id.
			$order_info = $this->get_order_info($order_id);
			$data = array(
				'order_id' => $order_id,
				'user_id' => $order_info['user_id'],
				'station_id' => $order_info['station_id'],
				'rate_oil' => $rate_oil,
				'rate_service' => $rate_service,
				'content' => $content
			);
			$create_result = $this->db->insert($this->table_name, $data);

			if ($create_result == TRUE):
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
		public function append($comment_id, $append)
		{
			$data = array(
				'append' => $append,
				'time_append' => date('Y-m-d H:i:s')
			);
			$this->db->where('comment_id', $comment_id);
			$append_result = $this->db->update($this->table_name, $data);

			if ($append_result == TRUE):
				// Get order_id using comment_id, then renew order status
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