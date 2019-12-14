<?php
	//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
	class ControllerPayoutPayoutdtl extends Controller {
		public function index($return_orders = array()) {
			$url = '';
			
			$this->load->model('purchase/return_orders');
			$this->document->setTitle('Credit Posting');
			$data['heading_title'] = 'Credit Posting';
			$data['text_list'] = 'Credit Posting';
			
			
			
			$data['button_filter'] = $this->language->get('button_filter');
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
		
                        $this->load->model('payout/payoutdtl');
                        $this->load->model('setting/store');
                        $data["units"] = $this->model_payout_payoutdtl->getAllUnits();
                        $data["store"] = $this->model_setting_store->getFranchiseStores();
                        
                        if ($this->request->server['REQUEST_METHOD'] == 'POST')
                        {
			//print_r($this->request->post);exit;
                        $this->load->model('payout/payoutdtl');
                        $data['logged_user_data'] = $this->user->getId();
                        $this->model_payout_payoutdtl->insrtPayoutdtl($this->request->post,$data['logged_user_data']);


                        $this->session->data['success'] = 'Credit Posting done successfully';
                        $this->response->redirect($this->url->link('payout/payoutdtl/payoutlist', 'token=' . $this->session->data['token'] , 'SSL'));
                        }
                        
                        
                     
			
			$url ='';
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Credit Posting',
				'href' => $this->url->link('payout/payoutdtl', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['cancel']=$this->url->link('payout/payoutdtl/payoutlist', 'token=' . $this->session->data['token'], 'SSL');
			$data['header'] = $this->load->controller('common/header');
			
			
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');
				$this->response->setOutput($this->load->view('payout/payoutdtl.tpl', $data));
			
		}
        
		public function payoutlist()
{
$this->load->language('report/product_purchased');

$this->document->setTitle("Credit Posting List");

$this->load->model('payout/payoutdtl');


$this->getList();
}
protected function getList() {

if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$data['filter_date_start']=$filter_date_start = date('Y-m').'-01';
}

if (isset($this->request->get['filter_date_end'])) {
$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['filter_stores_id'])) {
$filter_stores_id = $this->request->get['filter_stores_id'];
} else {
$filter_stores_id = 0;
}

$data['filter_date_start']=$filter_date_start;
$data['filter_date_end']=$filter_date_end;
$data['filter_stores_id']=$filter_stores_id;

if (isset($this->request->get['page'])) {
$page = $this->request->get['page'];
} else {
$page = 1;
}

$url = '';

if (isset($this->request->get['filter_date_start'])) {
$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
}

if (isset($this->request->get['filter_date_end'])) {
$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
}

if (isset($this->request->get['filter_stores_id'])) {
$url .= '&filter_stores_id=' . $this->request->get['filter_stores_id'];
}

if (isset($this->request->get['page'])) {
$url .= '&page=' . $this->request->get['page'];
}

$data['breadcrumbs'] = array();

$data['breadcrumbs'][] = array(
'text' => $this->language->get('text_home'),
'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
);

$data['breadcrumbs'][] = array(
'text' => $this->language->get('heading_title'),
'href' => $this->url->link('payout/payoutdtl/payoutlist', 'token=' . $this->session->data['token'], 'SSL')
);
$data['cancel']=$this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
$this->load->model('payout/payoutdtl');

$data['payout'] = array();

$filter_data = array(
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'filter_stores_id' => $filter_stores_id,
'start' => ($page - 1) * $this->config->get('config_limit_admin'),
'limit' => $this->config->get('config_limit_admin')
);

$product_total = $this->model_payout_payoutdtl->getTotalPayoutList($filter_data);

$results = $this->model_payout_payoutdtl->getPayoutList($filter_data);

foreach ($results as $result) {
$data['payout'][] = array(
'amount' => $result['amount'],
'transaction_type' => $result['transaction_type'],
'create_date' => $result['create_date'],
'payment_method' => $result['payment_method'],
'name' => $result['name'],
'firstname' => $result['firstname'],
'lastname' => $result['lastname'],
'tr_number'=>$result['tr_number']

);
}

$data['heading_title'] = $this->language->get('heading_title');

$data['text_list'] = $this->language->get('text_list');
$data['text_no_results'] = $this->language->get('text_no_results');


$data['column_name'] = $this->language->get('column_name');
$data['column_model'] = $this->language->get('column_model');
$data['column_quantity'] = $this->language->get('column_quantity');
$data['column_total'] = $this->language->get('column_total');

$data['entry_date_start'] = $this->language->get('entry_date_start');
$data['entry_date_end'] = $this->language->get('entry_date_end');
$data['entry_status'] = $this->language->get('entry_status');


if (isset($this->error['warning'])) {
$data['error_warning'] = $this->error['warning'];
} else {
$data['error_warning'] = '';
}

if (isset($this->session->data['success'])) {
$data['success'] = $this->session->data['success'];

unset($this->session->data['success']);
} else {
$data['success'] = '';
}

$data['button_filter'] = $this->language->get('button_filter');

$data['token'] = $this->session->data['token'];

$this->load->model('localisation/order_status');
$this->load->model('setting/store');
$data['order_stores'] = $this->model_setting_store->getFranchiseStores();
$url = '';

if (isset($this->request->get['filter_date_start'])) {
$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
}

if (isset($this->request->get['filter_date_end'])) {
$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
}

if (isset($this->request->get['filter_stores_id'])) {
$url .= '&filter_stores_id=' . $this->request->get['filter_stores_id'];
}

$pagination = new Pagination();
$pagination->total = $product_total;
$pagination->page = $page;
$pagination->limit = $this->config->get('config_limit_admin');
$pagination->url = $this->url->link('payout/payoutdtl/payoutlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

$data['pagination'] = $pagination->render();
$data['filter_stores_id']=$filter_stores_id;
$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

$data['filter_date_start'] = $filter_date_start;
$data['filter_date_end'] = $filter_date_end;
$data['filter_order_status_id'] = $filter_order_status_id;

$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('payout/payoutlist.tpl', $data));
}
		
	}
?>