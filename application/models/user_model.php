<?php
	class User_model extends CI_Model
	{
		public $table_name = 'user';
		
		// Initiate database
		public function __construct()
		{
			$this->load->database();
		}
		
		/** Get all users, or get certain user by user_id
		*
		* @since always
		* @param int $user_id
		* @return array
		*/
		public function get($user_id = NULL)
		{
			if ($user_id === NULL):
				$this->db->order_by('time_join desc');
				$this->db->order_by('balance desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['user_id'] = isset($user_id)? $user_id: $this->input->post('user_id');
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}

		/**
		* Check if a mobile number had been registered as user. If true, return corresponding user_id
		*
		* @since always
		* @param string $value Column value to be checked.
		* @param string $index Column name to be checked.
		* @return boolean
		*/
		public function is_registered($column, $value)
		{
			$data[$column] = $value;
			$query = $this->db->get_where($this->table_name, $data);
			$result = $query->row_array();

			if (empty($result)): // If no user matches
				return FALSE;
			else: // If one user matches
				return $result['user_id'];
			endif;
		}

		/**
		* Create new user
		*
		* @since always
		* @return int User_id
		*/
		public function create()
		{
			$data['mobile'] = $this->input->post('mobile');
			if ($this->db->insert($this->table_name, $data)):
				return $this->db->insert_id();
			endif;
		}

		/**
		* Update user profile
		*
		* @since always
		* @return boolean
		*/
		public function update()
		{
			$data = array(
				'nickname' => $this->input->post('nickname'),
				'lastname' => $this->input->post('lastname'),
				'firstname' => $this->input->post('firstname'),
				'gender' => $this->input->post('gender'),
				'dob' => $this->input->post('dob'),
				'mobile' => $this->input->post('mobile'),
				'email' => $this->input->post('email'),
				'logo_url' => $this->input->post('logo_url')
			);
			$this->db->where('user_id', $this->input->post('user_id'));
			$query = $this->db->update($this->table_name, $data);
			if (empty($query->row_array)): // If no user matches
				return FALSE;
			else: // If one user matches
				return TRUE;
			endif;
		}
		
		/**
		* Update certain user data
		*
		* @since always
		* @return boolean
		*/
		public function update_certain($column, $value)
		{
			$data[$column] = $value;
			$this->db->where('user_id', $this->input->post('user_id'));
			$query = $this->db->update($this->table_name, $data);
			if (empty($query->row_array)): // If no user matches
				return FALSE;
			else: // If one user matches
				return TRUE;
			endif;
		}
	}