<?php
	class Stuff_model extends CI_Model
	{
		public $table_name = 'stuff';
		
		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}

		//获取所有员工，或根据id获取特定员工
		/**
		* Grab all stuff informations, or get infos of certain single stuff according to stuff_id passed in.
		*
		* @param int $stuff_id
		* @return array
		* @since always
		*/
		public function select($stuff_id = NULL)
		{
			if ($stuff_id === NULL):
				$this->db->order_by('date_join');
				$this->db->order_by('dob');
				$this->db->order_by('time_create');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['stuff_id'] = $stuff_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
	}