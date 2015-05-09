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
			if ($station_id != NULL):
				$data['station_id'] = $station_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			else:
				$data = array();
				$longitude = $this->input->post('longitude')? $this->input->post('longitude'): 0;
				$latitude = $this->input->post('latitude')? $this->input->post('latitude'): 0;

				if (!empty($longitude) && !empty($longitude)):
				$sql = "SELECT *,
					ROUND(6371.392896*2*ASIN(SQRT(POW(SIN((".$latitude."*PI()/180-latitude*PI()/180)/2),2)+COS(".$latitude."*PI()/180)*COS(latitude*PI()/180)*POW(SIN((".$longitude."*PI()/180-longitude*PI()/180)/2),2)))*1000)
					AS distance
					FROM station ORDER BY distance, rate DESC, time_create";
					$query = $this->db->query($sql);
				else:
					$query = $this->db->get_where($this->table_name, $data);
				endif;

				return $query->result_array();

			endif;
		}
	}