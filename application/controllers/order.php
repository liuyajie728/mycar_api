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

			$this->load->library('token');
			$token = $this->input->post('token');
			$this->token->valid($token);

			$this->load->model('order_model');
			$this->load->model('user_model');
		}

		public function index()
		{
			$order_id = $this->input->post('order_id')? $this->input->post('order_id'): NULL;
			$order = $this->order_model->get($order_id);

			if (!empty($order)):
				$output['status'] = 200;
				$output['content'] = $order;
			else:
				if (empty($order)):
					$output['status'] = 401;
					$output['content'] = '该用户未创建过消费订单！';
				else:
					$output['status'] = 400;
					$output['content'] = '消费订单获取失败！';
				endif;
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}

		public function index_recharge()
		{
			$order_id = $this->input->post('order_id')? $this->input->post('order_id'): NULL;
			$user_id = $this->input->post('user_id')? $this->input->post('user_id'): NULL;
			$order = $this->order_model->get_recharge($order_id, $user_id);

			if (!empty($order)):
				$output['status'] = 200;
				$output['content'] = $order;
			else:
				if (empty($order)):
					$output['status'] = 401;
					$output['content'] = '该用户未创建过充值订单！';
				else:
					$output['status'] = 400;
					$output['content'] = '充值订单获取失败！';
				endif;
			endif;
			
			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}

		/**
		* Create order according to user_id provided.
		*
		* @since always
		* @param void
		* @return json Order createing result.
		*/
		public function create()
		{
			$user_id = $this->input->post('user_id');
			$user_ip = $this->input->post('user_ip')? $this->input->post('user_ip'): $this->input->ip_address();
			$type = $this->input->post('type');

			// generate order
			$order_id = $this->order_model->create($user_id, $user_ip, $type);

			if (!empty($order_id)):
				// return created order if succeed.
				$output['status'] = 200;
				if ($type == 'recharge'):
					$order = $this->order_model->get_recharge($order_id, $user_id);
				else:
					$order = $this->order_model->get($order_id);
				endif;
				$output['content'] = $order;
			else:
				$output['status'] = 400;
				$output['content'] = '订单创建失败。';
			endif;

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($output);
			echo $output_json;
		}

		/**
		* Update order status.
		*
		* @since always
		* @param void
		* @return void
		*/
		public function update_status()
		{
			$type = $this->input->post('type');
			$order_id = $this->input->post('order_id');
			$status = $this->input->post('status');
			$payment_type = $this->input->post('payment_type')? $this->input->post('payment_type'): NULL; // 支付类型
			$payment_id = $this->input->post('payment_id')? $this->input->post('payment_id'): NULL; // 支付流水

			$result = $this->order_model->update_status($type, $order_id, $status, $payment_type, $payment_id);
			// 若更新后订单属于已付款状态，则更新用户相应最新活动时间，不必检测是否更新成功。
			/*
			if (($status == '3') && ($result == TRUE)):
				$order = $this->order_model->get($order_id);
				$user_id = $order['user_id'];
				$column = ($type == 'recharge')? 'time_last_recharge': 'time_last_consume';
				@$this->user_model->update_certain($user_id, $column, date('Y-m-d H:i:s'));
			endif;
			*/
		}
	}

/* End of file order.php */
/* Location: ./application/controllers/order.php */