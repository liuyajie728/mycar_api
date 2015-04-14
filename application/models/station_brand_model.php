<?php
	class station_brand_model extends CI_Model
	{
		public $table_name = 'station_brand';
		
		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}

		//获取所有加油站，或根据id获取特定加油站
		public function get($brand_id = NULL)
		{
			if ($brand_id === NULL):
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['brand_id'] = $brand_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
	}