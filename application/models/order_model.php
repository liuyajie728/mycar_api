<?php
	class Order_model extends CI_Model
	{
		public $table_name = 'order_refuel';

		// Initiate model
		public function __construct()
		{
			$this->load->database();
		}

		/** Get all consume orders, or get certain consume order by order_id
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
				$this->db->order_by('time_payed desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['order_id'] = $order_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}

		/** Get all recharge orders, or get certain recharge order by order_id
		*
		* @since always
		* @param int $order_id
		* @return array
		*/
		public function get_recharge($order_id = NULL)
		{
			$this->table_name = 'order_recharge';

			if ($order_id === NULL):
				$this->db->order_by('time_create desc');
				$this->db->order_by('status desc');
				$this->db->order_by('time_payed desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['order_id'] = $order_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}

		/**
		* Create orders according to user_id and other params provided
		*
		* @since always
		* @param int $user_id
		* @return int Order_id of created order.
		*/
		public function create()
		{
			$data['user_id'] = $this->input->post('user_id');
			$type = $this->input->post('type');

			if ($type == 'recharge'):
				$this->table_name = 'order_recharge';
				$data['amount'] = $this->input->post('amount');

			else:
				// Using default table_name.
				$data['station_id'] = $this->input->post('station_id');
				$data['refuel_cost'] = $this->input->post('refuel_cost');
				$data['shopping_cost'] = $this->input->post('shopping_cost');
				$data['amount'] = $data['refuel_cost'] + $data['shopping_cost'];

			endif;

			if ($this->db->insert($this->table_name, $data)):
				return $this->db->insert_id();
			endif;
		}
	}