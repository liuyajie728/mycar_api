<?php
	class Article_model extends CI_Model
	{
		public $table_name = 'article';

		// Initiate model
		public function __construct()
		{
			$this->load->database();
		}

		/** Get all articles, or get certain article by articles_id
		*
		* @since always
		* @param int $articles_id
		* @return array
		*/
		public function get()
		{
			$article_id = $this->input->post('article_id');

			if ($article_id === NULL):
				$this->db->order_by('time_create desc');
				$query = $this->db->get($this->table_name);
				return $query->result_array();
				
			elseif (!is_int($article_id)):
				return $this->get_by_nicename($article_id);
				
			else:
				$data['article_id'] = $article_id;
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}

		/** Get all articles, or get certain article by nicename
		*
		* @since always
		* @param string $nicename
		* @return array
		*/
		public function get_by_nicename($nicename)
		{
			$data['nicename'] = $nicename;
			$query = $this->db->get_where($this->table_name, $data);
			return $query->row_array();
		}
	}