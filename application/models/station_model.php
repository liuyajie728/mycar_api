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
			$data = array(
				'status' => '4' // 只获取正常营业的加油站
			);
			
			// 获取加油站详情
			if ($station_id != NULL):
				$data['station_id'] = $station_id;
				$query = $this->db->get_where($this->table_name, $data);
				$result = $query->row_array();
				unset($result['order_code']); // 隐藏加油口令
				return $result;
			
			// 获取加油站列表
			else:
				$longitude = $this->input->post('longitude')? $this->input->post('longitude'): NULL;
				$latitude = $this->input->post('latitude')? $this->input->post('latitude'): NULL;

				if (!empty($longitude) && !empty($longitude)):
					// 根据传入的经纬度计算每个加油站与该点的距离，并将计算结果作为distance字段写入返回结果
					$this->db->select('station_id, brand_id, name, latitude, longitude, province, city, district, address, tel, image_url, rate_oil, rate_service,
					ROUND(6371.392896*2*ASIN(SQRT(POW(SIN(('.$latitude.'*PI()/180-latitude*PI()/180)/2),2)+COS('.$latitude.'*PI()/180)*COS(latitude*PI()/180)*POW(SIN(('.$longitude.'*PI()/180-longitude*PI()/180)/2),2)))*1000)
					AS distance', FALSE);
					$this->db->order_by('distance');
				endif;
				
				$this->db->order_by('rate_oil', 'DESC');
				$this->db->order_by('rate_service', 'DESC');
				$query = $this->db->get_where($this->table_name, $data);
				return $query->result_array();

			endif;
		}
	}