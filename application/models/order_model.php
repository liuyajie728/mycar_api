<?php
	class Order_model extends CI_Model
	{
		public $table_name = 'order';
		
		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}
		
		//获取所有订单，或根据id获取特定订单
		public function select($order_id = NULL)
		{
			if ($order_id === NULL):
				$this->db->order_by('time_join desc');
				$this->db->order_by('balance desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['order_id'] = $order_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
	}