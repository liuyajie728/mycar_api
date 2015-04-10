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
			$data['content'] = $token;
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
			
			if($query->num_rows() == 0): // If token does not exist.
				return 404;
			
			elseif(isset($result['time_due'])): // If token has a due time.
				if($result['time_due'] < date('Y-m-d H:i:s')): // If token is no longer valid.
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
			$token = $this->generate();
			$data['content'] = $token;

			if ($this->db->insert($this->table_name, $data)):
				return $token;

			else:
				return FALSE;
			endif;
		}
		
		/**
		* Generate a token
		*
		* @return string Generated Token.
		* @since always
		*/
		public function generate($length = 25)
		{
			// generate a 25 character string which combines intergers & alphabets in both cases. 
			function radom_string($length)
			{
				$string = null;
				$elements = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
				$max = strlen($elements)-1;
				
				for ($i=0; $i<$length; $i++)
				{
					$string .= $elements[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
				}
				
				return $string;
			}
			
			$token = radom_string($length);
			return $token;
		}
	}