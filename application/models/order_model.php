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
		public function get($order_id = NULL, $require_source = 'outer')
		{
			if ($require_source != 'inner'):
				$data['user_id'] = $this->input->post('user_id');
			endif;
			if ($order_id === NULL):
				$this->db->order_by('time_create desc');
				$this->db->order_by('status desc');
				$this->db->order_by('time_payed desc');
				$query = $this->db->get_where($this->table_name, $data);
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

			$data['user_id'] = $this->input->post('user_id');
			if ($order_id === NULL):
				$this->db->order_by('time_create desc');
				$this->db->order_by('status desc');
				$this->db->order_by('time_payed desc');
				$query = $this->db->get_where($this->table_name, $data);
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
			$type = $this->input->post('type');
			$order_id = $this->input->post('order_id');
			$status = $this->input->post('status');

			if ($type == 'recharge'):
				$this->table_name = 'order_recharge';
			endif;

			if ($status == '3'):
				if ($type == 'consume'):
					// 根据order_id获取相关station_id
					$order_result = $this->get($order_id, 'inner'); //Avoid user_id requirement.
					var_dump($order_result);
					$station_id = $order_result['station_id'];
					
					// 根据station_id获取相关加油站order_code
					$order_code_result = $this->get_station_code($station_id);
					$data['order_code'] = $order_code_result['order_code'];
				endif;
				$data['status'] = $status;
				$data['payment_id'] = $this->input->post('payment_id');
				$data['time_payed'] = date('Y-m-d H:i:s');
			endif;

			$this->db->where('order_id', $order_id);
			return $this->db->update($this->table_name, $data);
		}

		// 如果是consume类型订单，支付成功后根据station_id获取对应加油站即时的code（加油口令）并写入。
		public function get_station_code($station_id)
		{
			$data['station_id'] = $station_id;
			$this->db->select('order_code');
			$query = $this->db->get_where('station', $data);
			return $query->row_array();
		}
	}