<?php
	class Sms_model extends CI_Model
	{
		public $table_name = 'sms';
		
		// Initiate database
		public function __construct()
		{
			$this->load->database();
		}

		/** Get all smss, or get certain sms by sms_id
		*
		* @since always
		* @param int $sms_id
		* @return array
		*/
		public function get($sms_id = NULL)
		{
			if ($sms_id === NULL):
				$this->db->order_by('time_sent desc'); // 按发送时间排序，最近发送的优先
				$query = $this->db->get($this->table_name);
				return $query->result_array();

			else:
				$data['sms_id'] = $sms_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}

		/**
		* Record sms that is sent successfully.
		*
		* @since always
		* @param int $mobile Mobile number that receives the content.
		* @param string $content Contents that should be sent via sms.
		* @param int $type SMS type.
		* @return int Sms ID
		*/
		public function create($mobile, $content, $type)
		{
			$data = array(
				'mobile' => $mobile,
				'content' => $content,
				'type' => $type
			);
			if ($this->db->insert($this->table_name, $data)):
				return $this->db->insert_id();
			endif;
		}
	}