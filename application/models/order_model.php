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

			// 获取用户设备的IP地址
			function get_client_ip()
			{ 
			    if(getenv('HTTP_CLIENT_IP')):
			        $client_ip = getenv('HTTP_CLIENT_IP'); 
				elseif(getenv('HTTP_X_FORWARDED_FOR')):
			        $client_ip = getenv('HTTP_X_FORWARDED_FOR'); 
				elseif(getenv('REMOTE_ADDR')):
			        $client_ip = getenv('REMOTE_ADDR'); 
			    else:
			        $client_ip = $_SERVER['REMOTE_ADDR'];
			    endif;
			    return $client_ip; 
			}   
			$data['user_ip'] = get_client_ip();

			$type = $this->input->post('type');
			if ($type == 'recharge'):
				$this->table_name = 'order_recharge';
				$data['amount'] = $this->input->post('amount');
				$data['total'] = $data['amount'];

			else:
				// Using default table_name.
				$data['station_id'] = $this->input->post('station_id');
				$data['refuel_amount'] = $this->input->post('refuel_amount');
				$data['shopping_amount'] = empty($this->input->post('shopping_amount'))? '0.00': $this->input->post('shopping_amount');
				$data['amount'] = $data['refuel_amount'] + $data['shopping_amount']; // 折前订单额
				$data['total'] = $data['refuel_amount'] + $data['shopping_amount']; // 折后订单额

			endif;

			if ($this->db->insert($this->table_name, $data)):
				return $this->db->insert_id();
			endif;
		}
		
		public function update_status()
		{
			$order_id = $this->input->post('order_id');
			$data['status'] = $this->input->post('status');
			$data['type'] = $this->input->post('type');
			
			if ($type == 'recharge'):
				$this->table_name = 'order_recharge';
			endif;
			
			if ($status == '3'):
				$data['payment_id'] = $this->input->post('payment_id');
			endif;
			
			$this->db->where('order_id', $order_id);
			return $this->db->update($this->table_name, $data);
		}
	}