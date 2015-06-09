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
			// If not used by other functions, then require user_id
			if ($require_source != 'inner'):
				$data['user_id'] = $this->input->post('user_id');
			endif;

			// 获取订单详情的同时也获取消费加油站的信息
			$this->db->select($this->table_name.'.*, station.name as station_name, station.brand_id as station_brand_id, station.image_url as station_image_url, station.city as station_city, station.district as station_district, station.address as station_address, station.tel as station_tel');
			$this->db->join('station', $this->table_name.'.station_id = station.station_id', 'left outer');

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
		public function get_recharge($order_id = NULL, $user_id = NULL)
		{
			$this->table_name = 'order_recharge';

			$data['user_id'] = $user_id;

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
		* Create order according to user_id and other params provided
		*
		* @since always
		* @param int $user_id
		* @param string $type
		* @return int Order_id of created order.
		*/
		public function create($user_id, $user_ip, $type)
		{
			$data['user_id'] = $user_id;
			$data['user_ip'] = $user_ip;

			if ($type == 'recharge'):
				$this->table_name = 'order_recharge';
				$data['amount'] = $this->input->post('total');
				$data['total'] = $this->input->post('total'); // 支付金额

			else:
				// Using default table_name.
				$data['station_id'] = $this->input->post('station_id');
				$data['stuff_id'] = $this->input->post('stuff_id');
				$data['refuel_amount'] = $this->input->post('refuel_amount');
				$data['shopping_amount'] = empty($this->input->post('shopping_amount'))? '0.00': $this->input->post('shopping_amount');
				$data['amount'] = $data['refuel_amount'] + $data['shopping_amount']; // 折前订单额
				$data['total'] = $data['refuel_amount'] + $data['shopping_amount']; // 折后订单额(支付金额)

			endif;

			if ($this->db->insert($this->table_name, $data)):
				return $this->db->insert_id();
			endif;
		}

		public function update_status($type, $order_id, $status, $payment_type = NULL, $payment_id = NULL)
		{
			if ($type == 'recharge'):
				$this->table_name = 'order_recharge';
			endif;

			// 若需将订单状态更改为已付款，需传入type等参数
			if ($status == '3'):
				if ($type == 'consume'):
					// 根据order_id获取相关stuff_id
					$order_result = $this->get($order_id, 'inner'); // Avoid user_id requirement.
					$stuff_id = $order_result['stuff_id'];

					// 根据station_id获取相关加油站order_code
					$order_code_result = $this->get_stuff_code($stuff_id);
					$data['order_code'] = $order_code_result['order_code'];
					$data['order_operator'] = $order_code_result['lastname']. $order_code_result['firstname'];
				endif;

				$data['payment_type'] = $payment_type;
				$data['payment_id'] = $payment_id;
				$data['time_payed'] = date('Y-m-d H:i:s');
			endif;

			$data['status'] = $status;
			$this->db->where('order_id', $order_id);
			return $this->db->update($this->table_name, $data);
		}

		// 未完成：需修改为如果是consume类型订单，支付成功后根据stuff_id从stuff表中获取对应员工的code并返回；如果是consume类型订单，支付成功后根据station_id获取对应加油站即时的code（加油口令）并写入。
		public function get_stuff_code($stuff_id)
		{
			$data['stuff_id'] = $stuff_id;
			$this->db->select('lastname, firstname, order_code');
			$query = $this->db->get_where('stuff', $data);
			return $query->row_array();
		}
	}