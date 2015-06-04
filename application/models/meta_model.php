<?php
	class meta_model extends CI_Model
	{
		public $table_name = 'meta';

		// Initiate model
		public function __construct()
		{
			$this->load->database();
		}

		/** Get all metas, or get certain meta by metas_id, or get certain meta by nicename
		*
		* @since always
		* @param int/string $meta_id
		* @return array
		*/
		public function get($meta_id = NULL)
		{
			if ($meta_id === NULL):
				$query = $this->db->get($this->table_name);
				return $query->result_array();
				
			else:
				$this->db->where('meta_id', $meta_id);
				$this->db->or_where('name', $meta_id); 
				$query = $this->db->get($this->table_name);
				return $query->row_array();

			endif;
		}
	}