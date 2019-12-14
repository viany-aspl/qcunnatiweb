<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class ControllerReportSubuser extends Controller 
{
	public function material_summary_unit_wise() 
	{
		$this->load->language('report/cash_report');

		$this->document->setTitle('Sub User Material Summary (Unit Wise)');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = (date('Y')-1).'-10-01';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = null;
		}
		
        if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
        if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}   
		if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else
                {
            $filter_name = null;
        }
        if (isset($this->request->get['filter_name_id'])) 
		{
            $filter_name_id = $this->request->get['filter_name_id'];
        }
		else
                {
            $filter_name_id = null;
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
		
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
        if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
                if (isset($this->request->get['filter_name_id'])) {
            $url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
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
			'text' => 'Sub User Material Summary (Unit Wise)',
			'href' => $this->url->link('report/subuser/material_summary_unit_wise', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$this->load->model('report/sale');
                            $this->load->model('report/subuser');
		$data['orders'] = array();

		$filter_data = array(
			'filter_unit'=>$filter_unit,
			'filter_store' => $filter_store,
			'filter_user' => $filter_user,
			'filter_product'=>$filter_name_id,
			'start_date' => $filter_date_start,
			'end_date' => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		if( (!empty($filter_unit)) || (!empty($filter_name_id)))
		{
			$order_total = $this->model_report_subuser->getTotalMaterialSummary($filter_data);

			$data['orders'] = array();

			$results = $this->model_report_subuser->getMaterialSummary($filter_data);
		}
		
		
               // print_r($results);
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
			    'storename' => $result['storename'],
				'username' => $result['username'],
				'name'   => $result['name'],
				'ms'      => $result['ms'],
				'billed'     => $result['billed'],  
				'bal'      => $result['bal']
			);
		}

		$data['heading_title'] ='Material Summary (Unit Wise)';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['getuser'] = $this->model_report_subuser->getSubUser();

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/subuser/material_summary_unit_wise', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
//echo $order_total; exit;
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStoresCompanyWise(1);//getStores();//

		$data['filter_unit'] = $filter_unit;
		$data['filter_user'] = $filter_user;
        $data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        $data['filter_name'] = $filter_name;
		
		$data['filter_date_start']=$filter_date_start;
        $data['filter_date_end']=$filter_date_end;
		
        $data['filter_name_id'] = $filter_name_id;
		$this->response->setOutput($this->load->view('report/subuser_material_summary_unit_wise.tpl', $data));
	} 
	public function index() 
	{
		$this->load->language('report/cash_report');

		$this->document->setTitle('Sub User Material Summary');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = (date('Y')-1).'-10-01';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		
		
        if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
        if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}   
		if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else
                {
            $filter_name = null;
        }
        if (isset($this->request->get['filter_name_id'])) 
		{
            $filter_name_id = $this->request->get['filter_name_id'];
        }
		else
                {
            $filter_name_id = null;
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
		
		
        if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
                if (isset($this->request->get['filter_name_id'])) {
            $url .= '&filter_name_id=' . $this->request->get['filter_name_id'];
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
			'text' => 'Sub User Material Summary',
			'href' => $this->url->link('report/subuser', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$this->load->model('report/sale');
                            $this->load->model('report/subuser');
		$data['orders'] = array();

		$filter_data = array(
			'filter_store' => $filter_store,
			'filter_user' => $filter_user,
			'filter_product'=>$filter_name_id,
			'start_date' => $filter_date_start,
			'end_date' => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		//if((!empty($filter_store)) || (!empty($filter_user)) || (!empty($filter_name_id)))
		//{
			$order_total = $this->model_report_subuser->getTotalMaterialSummary($filter_data);

			$data['orders'] = array();

			$results = $this->model_report_subuser->getMaterialSummary($filter_data);
		//}
		
		
               // print_r($results);
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
			    'storename' => $result['storename'],
				'username' => $result['username'],
				'name'   => $result['name'],
				'ms'      => $result['ms'],
				'billed'     => $result['billed'],  
				'bal'      => $result['bal']
			);
		}

		$data['heading_title'] ='Material Summary';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['getuser'] = $this->model_report_subuser->getSubUser();

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/subuser', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
//echo $order_total; exit;
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStoresCompanyWise(1);//getStores();//

		$data['filter_user'] = $filter_user;
        $data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        $data['filter_name'] = $filter_name;
		
		$data['filter_date_start']=$filter_date_start;
        $data['filter_date_end']=$filter_date_end;
		
        $data['filter_name_id'] = $filter_name_id;
		$this->response->setOutput($this->load->view('report/subuser_material_summary.tpl', $data));
	} 
	public function material_detail() 
	{

		$this->document->setTitle('Material Detail');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = (date('Y')-1).'-10-01';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
        if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
        if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}                   
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		
        if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Cash Deposited by CE Report',
			'href' => $this->url->link('report/cash_report/runner', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$this->load->model('report/sale');
                            $this->load->model('report/subuser');
		$data['orders'] = array();

		$filter_data = array(
			'filter_store'=>$filter_store,
			'filter_user' => $filter_user,
			'start_date'=>$filter_date_start,
			'end_date'=>$filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		//if((!empty($filter_store)) || (!empty($filter_user)))
		{
			$order_total = $this->model_report_subuser->getmaterial_Totaldetail($filter_data);

			$data['orders'] = array();

			$results = $this->model_report_subuser->getmaterial_detail($filter_data);
        }       
		foreach ($results as $result) { //print_r($result);
	    $data['orders'][] = array(
				'username' => $result['username'],
				'name'   => $result['name'],
				'ms'      => $result['ms'],
				'quantity'     => $result['quantity'],  
				'dat'      => $result['dat'],
				'storename'      => $result['storename'],
				'trans_id' => $result['trans_id']
			);
		}

		$data['heading_title'] ='Material Summary';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['getuser'] = $this->model_report_subuser->getSubUser();

		$pagination = new Pagination();
		//echo  $order_total;
		//exit;
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/subuser/material_detail', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
                            $this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStoresCompanyWise(1);
		
		$data['filter_user'] = $filter_user;
        	$data['filter_store'] = $filter_store;
			$data['filter_date_start'] = $filter_date_start;
        	$data['filter_date_end'] = $filter_date_end;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                          
		$this->response->setOutput($this->load->view('report/subuser_material_detail.tpl', $data));
	}
	public function SubUserSaleCash() 
	{

		$this->document->setTitle('Sub User Sale Cash');
		if (isset($this->request->get['filter_date_start'])) 
		{
			$filter_date_start = $this->request->get['filter_date_start'];
		} 
		else 
		{
			$filter_date_start = (date('Y')-1).'-10-01';
		}
		if (isset($this->request->get['filter_date_start2'])) 
		{
			$filter_date_start2 = $this->request->get['filter_date_start2'];
		} 
		else 
		{
			$filter_date_start2 = (date('Y')-1).'-10-01';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		} 
		///////////Tan detail/////////////////////////////
		if (isset($this->request->get['filter_subuser'])) 
		{
			
		if (isset($this->request->get['filter_subuser'])) {
			$filter_subuser = $this->request->get['filter_subuser'];
		}   else {
			$filter_subuser = null;
		}
          
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		
        if (isset($this->request->get['filter_subuser'])) {
			$url .= '&filter_subuser=' . $this->request->get['filter_subuser'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->load->model('report/subuser');
		$data['orders'] = array();

		$filter_data = array(
			'start_date'=>$filter_date_start2,
			'end_date'=>$filter_date_end,
			'filter_subuser' => $filter_subuser,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
    $order_total1 = $this->model_report_subuser->getUserSaleTotalCashdtl($filter_data);

		$data['cashorders'] = array();
		
	  
		$results = $this->model_report_subuser->getUserSaleCashdtl($filter_data);
		
             // print_r($results);  exit;
			 
	    $subcash=0;
		foreach ($results as $result) {// print_r($result); 
		$subcash=$subcash+$result['cash'];
		
	    $data['cashorders'][] = array(	
				
				'cash'   => $result['cash'],
				'dat'      => $result['dat']
			);
		}
		$data['subcash']=$subcash;
		
		$pagination = new Pagination();
		$pagination->total = $order_total1;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/subuser/SubUserSaleCash', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
//echo $order_total; exit;
		$data['pagination1'] = $pagination->render();

		$data['results1'] = sprintf($this->language->get('text_pagination'), ($order_total1) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total1: ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total1, ceil($order_total1 / $this->config->get('config_limit_admin')));
		
			
		}/////end of dtl tab page

		//print_r($this->request->get['filter_user']);
          if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
                            
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		
        if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
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
			'text' => 'Sub User Sale Cash',
			'href' => $this->url->link('report/cash_report/runner', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$this->load->model('report/sale');
                            $this->load->model('report/subuser');
		$data['orders'] = array();

		$filter_data = array(
			'start_date'=>$filter_date_start,
			'end_date'=>$filter_date_end,
			'filter_user' => $filter_user,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();
		$order_total = $this->model_report_subuser->getUserSaleTotalCash($filter_data);
	  
		$results = $this->model_report_subuser->getUserSaleCash($filter_data);
		
              //print_r($results);  
		foreach ($results as $result) {// print_r($result); 
	    $data['orders'][] = array(
				'subusername' => $result['subusername'],
				'store_name'   => $result['store_name'],
				'cash_inhand'      => $result['cash_inhand'],
				'Cash_Sales'     => $result['Cash_Sales'],  
				'Tagged_Sales'      => $result['Tagged_Sales'],
				'Cash_Tagged'      => $result['Cash_Tagged'],
				'Cash_Subsidy'     => $result['Cash_Subsidy'],  
				'Tagged_Subsidy'      => $result['Tagged_Subsidy']
			);
		}

		$data['heading_title'] ='Material Summary';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['getuser'] = $this->model_report_subuser->getSubUser();

		

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_start2'])) {
			$url .= '&filter_date_start2=' . $this->request->get['filter_date_start2'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
                           if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
                            if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/subuser/SubUserSaleCash', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
//echo $order_total; exit;
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
                            $this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_start2'] = $filter_date_start2;
		$data['filter_date_end'] = $filter_date_end;
	// $data['user_cash'] = $this->model_report_subuser->getUserSaleCash($filter_user);
		$data['filter_user'] = $filter_user;
		$data['filter_subuser'] = $filter_subuser;
        $data['filter_status'] = $filter_status;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        // $mcrypt=new MCrypt();
        //echo $mcrypt->decrypt('7e126d01d0362588e0122c1eac729bdf9277ec1faa37dc20da4821ae901e23ff'); 
		$this->response->setOutput($this->load->view('report/subuser_sale_user_cash.tpl', $data));
	}
	
	/*******************End Excel Download*********************************/
	public function SubUserOrderDetail() {

		$this->document->setTitle('Sub User Order Detail');
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		}
		else {
			$filter_date_start = date('Y-m').'-01';
		}
 

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = null;
		}
                            
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		
        if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		
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
			'text' => 'Sub User Order Detail',
			'href' => $this->url->link('report/cash_report/runner', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
        $this->load->model('report/subuser');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_user' => $filter_user,
			'filter_unit' => $filter_unit,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$order_total = $this->model_report_subuser->getTotalorder_summary($filter_data);
		$results = $this->model_report_subuser->getorder_summary($filter_data);
	  
		foreach ($results as $result) { 
		
		$name=explode('-',$result['payment_firstname']);
		
	    $data['orders'][] = array(		
				'order_id' => $result['order_id'],
				'growername'   => $name[0],
				'store_name'      => $result['store_name'],
				'quantity'     => $result['quantity'],  
				'payment_method'      => $result['payment_method'],
				'dat'      => $result['dat'],
				'total'     => $result['total'],
				'tagged'     => $result['tagged'],
				'cash'     => $result['cash'],
				'username' => $result['username']
			);
		}

		$data['heading_title'] ='Sub User Order Detail';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');
		$data['getuser'] = $this->model_report_subuser->getSubUser();
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . $this->request->get['filter_user'];
		}
		if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
		
        if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/subuser/SubUserOrderDetail', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('unit/unit');
		$data['units']=$this->model_unit_unit->getunit(array('filter_company'=>'1'));
		
		$data['filter_user'] = $filter_user;
		$data['filter_unit'] = $filter_unit;
        $data['filter_status'] = $filter_status;
				$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                          
		$this->response->setOutput($this->load->view('report/subuser_order_detail.tpl', $data));
	}
	
	/****************Download Excel*************************/
	public function download_excel_material_summary() {
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = (date('Y')-1).'-10-01';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
        if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
 		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
		if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else
                {
            $filter_name = null;
        }
        if (isset($this->request->get['filter_name_id'])) 
		{
            $filter_name_id = $this->request->get['filter_name_id'];
        }
		else
                {
            $filter_name_id = null;
        }
        $this->load->model('report/subuser');
		
		$data['orders'] = array();

		$filter_data = array(
			'filter_store'	     => $filter_store,
			'filter_user'	     => $filter_user,
			'filter_product'=>$filter_name_id,
			'start_date'=>$filter_date_start,
			'end_date'=>$filter_date_end
		);
               
		
		$data['orders'] = array();
		//if((!empty($filter_store)) || (!empty($filter_user)) || (!empty($filter_name_id)))
		//{
			$results = $this->model_report_subuser->getMaterialSummary($filter_data);    
		//}		
		//print_r($results); exit;
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'User Name',
        'Store Name',
		'Product Name',
        'Material Issued',
        'Material Billed',
        'Balance Qty'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    
    $row = 2;
    
    foreach($results as $data)
    { 
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['username']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['storename']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['ms']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['billed']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['ms']-$data['billed']);
        
        
        $row++;
    } 
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Sub User Material Summary_'.date('d-M-y').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
	
	public function download_excel_material_summaryAll() {
		
		
        if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
 		
        $this->load->model('report/subuser');
		
		$data['orders'] = array();
    
		//print_r($filter_user);
		$data['orders'] = array();
            $results = $this->model_report_subuser->getAllMaterialSummary();                     
		//print_r($results); exit;
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'User Name',
        'Store Name',
		'Product Name',
        'Material Issued',
        'Material Billed',
        'Balance Qty'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    
    $row = 2;
    
    foreach($results as $data)
    { 
	//print_r($data); exit;
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['username']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['storename']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['material_issue']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['biilled']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['material_issue']-$data['biilled']);
        
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="All Sub User Material Summary_'.date('d-M-y').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
	
	
	public function download_excel_material_detail() {
		
		
        if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
 		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = (date('Y')-1).'-10-01';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
        $this->load->model('report/subuser');
		
		$data['orders'] = array();

		$filter_data = array(
			'filter_store'	     => $filter_store,
			'filter_user'	     => $filter_user,
			'start_date'=>$filter_date_start,
			'end_date'=>$filter_date_end
		);
               

		$data['orders'] = array();
            $results = $this->model_report_subuser->getmaterial_detail($filter_data);                     
		
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
	 'Store Name',
        'User Name',
        'Product Name',
        'Material Issued Date',
        'Quantity'
      
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    //$this->load->model('report/searchattendance');
    //$results = $this->model_report_searchattendance->getmdoattendance($filter_data);
    
    $row = 2;
    
    foreach($results as $data)
    { 
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['storename']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['username']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['name']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['dat']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['quantity']);
        
		
        
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Sub User Material Detail_'.date('d-M-y').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
	public function download_excel_SubUserSaleCash() 
	{
		
		if (isset($this->request->get['filter_date_start'])) 
		{
			$filter_date_start = $this->request->get['filter_date_start'];
		} 
		else 
		{
			$filter_date_start = (date('Y')-1).'-10-01';
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		} 
        if (isset($this->request->get['filter_user'])) 
		{
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
 		
        $this->load->model('report/subuser');
		
		$data['orders'] = array();

		$filter_data = array(
			'start_date'=>$filter_date_start,
			'end_date'=>$filter_date_end,
			'filter_user'	     => $filter_user
		);
               

		$data['orders'] = array();
            $results = $this->model_report_subuser->getUserSaleCash($filter_data);                     
		
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'User Name',
        'Store Name',
        'Cash Sales',
        'Tagged Sales',
	
		'Subsidy Sales',
		
		'Cash In Hand'
      
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    //$this->load->model('report/searchattendance');
    //$results = $this->model_report_searchattendance->getmdoattendance($filter_data);
    
    $row = 2;
    
    foreach($results as $data)
    { 
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['subusername']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Cash_Sales']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Tagged_Sales']);
	
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['Cash_Subsidy']);
		       
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['cash_inhand']);
        
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Sub User Sale Summary'.date('d-M-y').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
	
	
	
		public function SubUserOrderProductDetail()
	{
		$oid=$this->request->get['oid'];
		$this->load->model('report/subuser');
		//echo "nhjfbv";
		$results= $this->model_report_subuser->getorder_summarydetail($oid);
		foreach ($results as $result) {//print_r($result); 
	    $data['orders'][] = array(
				'name' => $result['name'],
				'quantity'   => $result['quantity'],
				'price'      => $result['price'],
				'tax'     => $result['tax'],  
				'total'      => $result['total']
			);
		}
		//print_r( $data);
       $this->response->setOutput(json_encode($data));
	}
	 public function Alldownload_pdf() { 
		
		if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user= 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit= null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		}
		else {
			$filter_date_start = null;
		}
 

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}
		
		$this->load->model('report/subuser');
                
		$data['orders'] = array();
		
		$filter_data = array(
      
			'filter_user'	     => $filter_user,
			'filter_unit'	     => $filter_unit ,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
		);
		$results= $this->model_report_subuser->getorder_summary($filter_data);

		foreach ($results as $result) { //print_r($result); exit;
			$orderproducts=array();
			$orderproducts=$this->model_report_subuser->getorder_summarydetail($result['order_id']);
			$data['orders'][] = array(
				
				'date'       => date($this->language->get('date_format_short'), strtotime($result['dat'])),
				'order_id'   => $result['order_id'],
                'store_name' => $result['store_name'],
                'payment_firstname'   => $result['payment_firstname'],
                'total'      => $result['total'],
                'tagged'     => $result['tagged'],
				'cash'     => $result['cash'],
				'subsidy'     => $result['subsidy'],
				'dat'     => $result['dat'],
				'username'     => $result['username'],
				'orderproducts'=>$orderproducts
                               
				
			);
		
			
		}
		
	
		$html = $this->load->view('report/subuser_orderDetail_pdf.tpl',$data);
			
			
				require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
               
               
                $base_url = HTTP_CATALOG;
                
                
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
                $header = '<div class="header" style="">
                   
<div class="logo" style="width: 100%;" >
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />

                         </div>
<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 

</div>';
    
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="height: 10px; margin-left: -40px;width: 120% !important;" /> </div>'
                        . '</div>';

                
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->setAutoBottomMargin = 'stretch';
                $mpdf->WriteHTML($html);
			
                $filename='Sub User Order _Report_'.date('Y-m-d')."_".str_replace(' ','_',$data['summaryorders'][0]['fmname']).'.pdf';
               
                $mpdf->Output($filename,'D');
             
        }
	public function Alldownload_excel() 
	{ 
		if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user= 0;
		}
		if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit= null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		}
		else {
			$filter_date_start = null;
		}
 

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}
		
		$this->load->model('report/subuser');
                
		$data['orders'] = array();
		
		$filter_data = array(
      
			'filter_user'	     => $filter_user,
			'filter_unit'	     => $filter_unit ,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
		);
		
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Order Id',
        'Date',
        'Sub User',
        'Store Name',
		'Grower Name',
		'Grower ID',
		'Card Number',
		'Customer Mobile',
		'Village Name',
		'Transaction type',
		'Order Total',
		'Tagged Amount',
		'Cash Amount',
		'Product Details'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
    $results= $this->model_report_subuser->getorder_summary_mill($filter_data); 
	//exit;
    foreach($results as $data)
    {     

		$orderproductsstring='';
		$orderproducts=$this->model_report_subuser->getorder_summarydetail($data['order_id']);
		//print_r($orderproducts);
		foreach($orderproducts as $orderproduct)
		{
			
				$orderproductsstring=$orderproductsstring.$orderproduct['name'].' - ('.$orderproduct['quantity'].'),';
			
		}
		$orderproductsstring = rtrim($orderproductsstring, ','); 
		//echo $orderproductsstring;
		$name=explode('-',$data['payment_firstname']);
		$col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['order_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['dat']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['username']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $name[0]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['grower_id']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['card_serial_no']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['telephone']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['payment_address_1']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['payment_method']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['total']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $data['tagged']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $data['cash']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $orderproductsstring);
        $row++;
    }
    //exit;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Sub_user_order_report'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
		
        print_r($data['orders']);       
    }
    public function product_autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}  
}