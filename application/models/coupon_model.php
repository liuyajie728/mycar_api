<?php
	class Coupon_model extends CI_Model
	{
		public $table_name = 'coupon';
		
		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}
		
		//获取所有折抵券，或根据id获取特定折抵券
		public function select($coupon_id = NULL)
		{
			if ($coupon_id === NULL):
				$this->db->order_by('time_join desc');
				$this->db->order_by('balance desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['coupon_id'] = $coupon_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
	}