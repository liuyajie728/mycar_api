<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');

	/**
	* Order Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Order extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			// $this->load->library('token');
			// $this->token->valid($this->input->post('token'));

			$this->load->model('order_model');
		}

		public function index($order_id = NULL)
		{
			$output['status'] = 200;
			$output['content'] = $this->order_model->get($order_id);

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}

		/**
		* Create Order according to user_id provide.
		*
		* @since always
		* @param int $_POST['user_id'] User id.
		* @return json Order create result.
		*/
		public function create()
		{
			// generate order
			$order_id = $this->order_model->create();

			if (!empty($order_id)):
				// return created order if succeed.
				$output['status'] = 200;
				$order = $this->order_model->get($order_id);
				$output['content']['order'] = $order;
			else:
				$output['status'] = 400;
				$output['content'] = 'Order not create.';
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}
	}

/* End of file order.php */
/* Location: ./application/controllers/order.php */