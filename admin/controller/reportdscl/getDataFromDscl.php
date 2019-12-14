<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerReportdsclGetDataFromDscl extends Controller {
	public function index() {
		$this->load->language('report/reconciliation');

		$this->getdebitnotedetail();
	}

public function getdebitnotedetail() {
		$this->load->language('report/reconciliation');

		$this->document->setTitle('Dscl Order Detail');

		
		if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
 
} else {
$filter_date_start = '';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = '';
}
		

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
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Dscl Order Detail',
			'href' => $this->url->link('reportdscl/getDataFromDscl', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/reconciliation');
		$this->load->model('pos/dscl');
        	$this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
            'sdate' => $filter_date_start,
			'edate' => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$order_total = 0;
			if((!empty($filter_date_start)) && (!empty($filter_date_end)))
			{
			$results = $this->model_pos_dscl->GetOrderData('GetDebitNoteDetail',$filter_data,true);
		$data['orders'][] =$results;
			}
		//print_r($results); //exit;
		//echo "dsfsd";
		
		
		
		$data['heading_title'] = 'Dscl Order Detail';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                $data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
		
		$url = '';
        if (isset($this->request->get['filter_letter_number'])) {
			$url .= '&filter_letter_number=' . $this->request->get['filter_letter_number'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportdscl/getdataFromDscl', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_letter_number'] = $filter_letter_number;
		
		$data['filter_unit'] = $filter_unit;
		$data['total_tagged_amount'] =$total_tagged_amount;
		$data['total_tagged_amount_All'] =$total_tagged_amount_all;
$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$this->load->model('unit/unit');
		$data['units']=$this->model_unit_unit->getunit(array('filter_company'=>'2'));
		//print_r($data['units']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportdscl/getdatafromdscl.tpl', $data));
	}
	
	public function GetOrderProductData()
	{
		    $data['oid']=$oid=$this->request->get['oid'];
			$this->load->model('pos/dscl');
			$result = $this->model_pos_dscl->GetOrderProductData('GetOrderProductData',$data,true);
		    //$data['products'][] =$result;
			$this->response->setOutput(json_encode($result));
	}
	
}