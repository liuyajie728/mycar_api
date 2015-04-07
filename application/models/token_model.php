<?php
	class Token_model extends CI_Model
	{
		public $table_name = 'token';
		
		//初始化模型
		public function __construct()
		{
			$this->load->database();
		}
		
		//获取所有会员，或根据id获取特定会员
		public function select($token = NULL)
		{
			$data['content'] = $token;
			$query = $this->db->get_where($this->table_name, $data);
			return $query->row_array();
		}
		
		/**
		* Validate the token to decide if the requirement are authenticated.
		*
		* @param string $token The string that should be provided.
		* @return int $status The status judged by processing the token.
		*/
		public function valid($token)
		{
			return 200;
		}
	}