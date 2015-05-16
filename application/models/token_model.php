<?php
	class Token_model extends CI_Model
	{
		public $table_name = 'token';
		
		// Initiate model
		public function __construct()
		{
			$this->load->database();
		}
		
		/**
		* Get all token informations or certain token information indicated.
		*
		* @return array Token informations got from database.
		* @since always
		*/
		public function select($token = NULL)
		{
			$data = array();
			if ($token != NULL):
				$data['content'] = $token;
			endif;
			$query = $this->db->get_where($this->table_name, $data);
			return $query->row_array();
		}
		
		/**
		* Validate the token to decide if the requirement are authenticated.
		*
		* @param string $token The string that should be provided.
		* @return int The status code judged by processing the token.
		* @since always
		*/
		public function valid($token)
		{
			$data['content'] = $token;
			$query = $this->db->get_where($this->table_name, $data);
			$result = $query->row_array();
			
			if ($query->num_rows() == 0): // If token does not exist.
				return 404;
			
			elseif (isset($result['time_due'])): // If token has a due time.
				if ($result['time_due'] < date('Y-m-d H:i:s')): // If token is no longer valid.
					return 403;
				endif;

			else: // If token exists and is still valid.
				return 200;
			endif;
		}
		
		/**
		* Register generated token into database
		*
		* @return string The token generated if succeed.
		* @return boolean if failed.
		* @since always
		*/
		public function create()
		{
			$token = random_string('alnum', 25);;
			$data['content'] = $token;

			if ($this->db->insert($this->table_name, $data)):
				return $token;

			else:
				return FALSE;
			endif;
		}
	}