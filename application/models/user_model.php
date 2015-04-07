<?php
	class User_model extends CI_Model
	{
		public $table_name = 'user';
		
		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}
		
		//获取所有会员，或根据id获取特定会员
		public function select($user_id = NULL)
		{
			if ($user_id === NULL):
				$this->db->order_by('time_join desc');
				$this->db->order_by('balance desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['user_id'] = $user_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
		
		//新增会员，并返回插入后的行ID
		public function create($image_url)
		{
			$data = array(
				'nickname' => $this->input->post('nickname'),
				'logo_url' => $image_url
			);
		
			if($this->db->insert($this->table_name, $data)):
				return $this->db->insert_id();
			endif;
		}
	}