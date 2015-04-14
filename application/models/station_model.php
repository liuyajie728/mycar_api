<?php
	class station_model extends CI_Model
	{
		public $table_name = 'station';
		
		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}

		//获取所有加油站，或根据id获取特定加油站
		public function get($station_id = NULL)
		{
			if ($station_id === NULL):
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['station_id'] = $station_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
	}