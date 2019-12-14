<?php
class ControllerDashboardActivity extends Controller {
/*
	public function index() {
		$this->load->language('dashboard/activity');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['token'] = $this->session->data['token'];

		$data['activities'] = array();

		$this->load->model('report/activity');

		$results = $this->model_report_activity->getActivities();

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_' . $result['key']), unserialize($result['data']));

			$find = array(
				'customer_id=',
				'order_id=',
				'affiliate_id=',
				'return_id='
			);

			$replace = array(
				$this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=', 'SSL'),
				$this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=', 'SSL'),
				$this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=', 'SSL'),
				$this->url->link('sale/return/edit', 'token=' . $this->session->data['token'] . '&return_id=', 'SSL')
			);

			$data['activities'][] = array(
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		return $this->load->view('dashboard/activity.tpl', $data);
	}
*/


	public function index() { 
	
		$this->load->language('dashboard/recent');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		
		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		// Last 5 Orders
		$data['orders'] = array();

		$filter_data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5,
			'filter_date_start'=>$this->request->get['start_date'],
			'filter_date_end'=>$this->request->get['end_date']
		);

		if($this->user->getGroupId()!="1")
                    {
		$filter_data = array(
                    'filter_user_id' => $this->user->getId(),
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);
                }
		$this->load->model('sale/order');
		$results = $this->model_sale_order->getTop_5_Products($filter_data);

		foreach ($results as $result) { //print_r($result);
			$data['orders'][] = array(
				'product_name'   => $result['name'],
				
				'sales_of_qnty'      => $result['sales_of_qnty']
			);
		}

		return $this->load->view('dashboard/activity.tpl', $data);
	}


}
