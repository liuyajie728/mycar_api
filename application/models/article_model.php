<?php
	class Article_model extends CI_Model
	{
		public $table_name = 'article';

		// Initiate model
		public function __construct()
		{
			$this->load->database();
		}

		/** Get all articles, or get certain article by articles_id, or get certain article by nicename
		*
		* @since always
		* @param int/string $article_id
		* @return array
		*/
		public function get($article_id = NULL)
		{
			$data = array(
				'status >' => '0' // 仅获取非草稿文件
			);
			if ($article_id === NULL):
				$this->db->order_by('time_create desc');
				$query = $this->db->get_where($this->table_name, $data);
				return $query->result_array();
				
			else:
				$this->db->where('article_id', $article_id);
				$this->db->or_where('nicename', $article_id); 
				$query = $this->db->get_where($this->table_name, $data);
				return $query->row_array();

			endif;
		}
	}