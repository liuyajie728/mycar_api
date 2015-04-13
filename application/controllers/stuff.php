<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	/**
	* Stuff Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Stuff extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			//$this->load->library('token');
			//$this->token->valid($this->input->get('token'));

			$this->load->model('stuff_model');
		}

		public function index($stuff_id = NULL)
		{
			$output['status'] = 200;
			$output['content'] = $this->stuff_model->select($stuff_id);

			//header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;

			$this->output->enable_profiler(TRUE);
		}

		/**
		* Transfer and save the json data into MySql
		* @todo
		* @since always
		*/
		public function json2mysql($json_content)
		{
			$json_array = json_decode($json_content);
			$this->stuff_model->insert();
			
			$this->output->enable_profiler(TRUE);
		}
		
		/**
		* Create a stuff profile
		*
		* @since always
		* @return int $stuff_id The new created stuff's ID if successfully created.
		*/
		public function create()
		{
			$data['title'] = '新建员工';
			$data['class'] = 'stuff';
			
			$this->form_validation->set_rules('lastname', '姓', 'trim|required');
			$this->form_validation->set_rules('firstname', '名', 'trim|required');
			$this->form_validation->set_rules('dob', '生日', 'trim|required');
			$this->form_validation->set_rules('mobile', '手机号', 'trim|required|is_natural|exact_length[11]');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
			$this->form_validation->set_rules('gender', '性别', 'trim|required');
			$this->form_validation->set_rules('userfile', '照片', 'trim');

			if ($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view('stuff/create');
				$this->load->view('templates/footer');
				
			// 若此手机号已被注册过
			elseif ($this->stuff_model->stuff_check()):
				$data['content'] = '这个手机号已被注册过，请尝试另一个号码或直接登录。';
				
				$this->load->view('templates/header', $data);
				$this->load->view('stuff/result', $data);
				$this->load->view('templates/footer');
				
			elseif ($this->stuff_model->create()):
				$data['content'] = '新员工创建成功。请告知该员工管理后台登录网址（'.base_url().'），用户名为该员工手机号，初始密码为该员工手机号后6位数字。';

				$this->load->view('templates/header', $data);
				$this->load->view('stuff/result', $data);
				$this->load->view('templates/footer');
				
			endif;
			
			$this->output->enable_profiler(TRUE);
		}
		
		/**
		* Edit the profile of one certain stuff who's been indicated by the stuff ID.
		*
		* @since always
		* @param int $stuff_id 员工ID
		*/
		public function edit($stuff_id)
		{
			$data['title'] = '员工资料编辑';
			$data['class'] = 'stuff';
						
			$this->output->enable_profiler(TRUE);
		}
	}

/* End of file stuff.php */
/* Location: ./application/controllers/stuff.php */