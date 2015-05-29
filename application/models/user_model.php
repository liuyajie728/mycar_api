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
				$data['user_id'] = $user_id;
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
		* @param string $mobile
		* @return int User_id
		*/
		public function create($mobile)
		{
			$data['mobile'] = $mobile;
			$data['nickname'] = 'i_'. $mobile; // 新用户默认昵称
			if ($this->db->insert($this->table_name, $data)):
				return $this->db->insert_id();
			endif;
		}

		/**
		* NOT FINISHED. Update all user datas
		*
		* @since always
		* @return boolean
		*/
		public function update_all()
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
			$result = $this->db->update($this->table_name, $data);
			if ($result === FALSE): // If no user matches
				return FALSE;
			else: // If one user matches
				return TRUE;
			endif;
		}

		/**
		* Update certain user data
		*
		* @since always
		* @param int $user_id
		* @param string $column
		* @param string $value
		* @return boolean
		*/
		public function update_certain($user_id, $column, $value)
		{
			$data[$column] = $value;
			$this->db->where('user_id', $user_id);
			$result = $this->db->update($this->table_name, $data);
			if ($result != FALSE): // If no user matches
				return FALSE;
			else: // If one user matches
				return TRUE;
			endif;
		}
	}