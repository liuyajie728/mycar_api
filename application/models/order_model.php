<?php
	class Order_model extends CI_Model
	{
		public $table_name = 'order';

		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}

		/** Get all orders, or get certain order by order_id
		*
		* @since always
		* @param int $order_id
		* @return array
		*/
		public function get($order_id = NULL)
		{
			if ($order_id === NULL):
				$this->db->order_by('time_create desc');
				$this->db->order_by('status desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['order_id'] = $order_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}

		/**
		* Create models according to user_id provided
		*
		* @since always
		* @param int $user_id
		* @return int Order_id of created order.
		*/
		public function create($user_id)
		{
			$data['user_id'] = $user_id;
			if ($this->db->insert($this->table_name, $data)):
				return $this->db->insert_id();
			endif;
		}
	}