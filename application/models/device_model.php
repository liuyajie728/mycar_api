<?php
	class Device_model extends CI_Model
	{
		public $table_name = 'device';
		
		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}
		
		//获取所有设备，或根据id获取特定设备
		public function select($device_id = NULL)
		{
			if ($device_id === NULL):
				$this->db->order_by('time_join desc');
				$this->db->order_by('balance desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['device_id'] = $device_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
	}