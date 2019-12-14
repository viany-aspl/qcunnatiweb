<?php
class ControllerreportdsclDebitstore extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Stores');

		$this->load->model('setting/store');

		$this->getList();
	}
public function audit() {
		$this->load->language('setting/store');

		$this->document->setTitle('Store Waiver');

		
                $this->load->model('setting/auditstore');
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			//print_r($this->request->post);exit;
                    $this->load->model('user/user');
                
                    $data['logged_user_data'] = $this->user->getId();
                    $this->model_setting_auditstore->debitStore($this->request->get['store_id'], $this->request->post,$data['logged_user_data']);

			
			$this->session->data['success'] = 'Submitted Successfully';

			$this->response->redirect($this->url->link('setting/debitstore/waiver_report', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
		}
                
                $getdata=$this->model_setting_auditstore->getdebitamount($this->request->get['store_id'],$this->request->get['store_user_id']);
		
                $data["cash"]=$getdata["cash"];
                $data["store_user_id"]=$getdata["user_id"];
                $data["remarks"]=$getdata["remarks"];
               // $data["card"]=$getdata["card"];
                 $data["firstname"]=$getdata["firstname"];
                 $data["lastname"]=$getdata["lastname"];
                 $data["storename"]=$getdata["storename"]; 
              //exit;
               
                $data["cancel"]=$_SERVER["HTTP_REFERER"];   
                                                
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/debitstoreedit.tpl', $data));
	}


	protected function getList() {
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Stores for Audit Amount',
			'href' => $this->url->link('setting/debitstore', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
		

		$data['stores'] = array();

		/*$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
*/
		$store_total = $this->model_setting_store->getTotalStores();

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_url'] = $this->language->get('column_url');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/store_debit_list.tpl', $data));
	}
	    public function exp() {
		$this->load->language('setting/store');

		$this->document->setTitle('Submit Waiver off');

		
                	$this->load->model('setting/auditstore');
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			//print_r($this->request->post);exit;
                    		$this->load->model('user/user');
                
                    		$data['logged_user_data'] = $this->user->getId();
                    		$this->model_setting_auditstore->expStore($this->request->get['store_id'], $this->request->post,$data['logged_user_data']);

			
			$this->session->data['success'] = 'Data submitted Successfully';

			$this->response->redirect($this->url->link('setting/debitstore/getWaveoffdata', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
		}
                
                $getdata=$this->model_setting_auditstore->getepfdata($this->request->get['store_id'],$this->request->get['store_user_id']);
		
                $data["cash"]=$getdata["cash"];
                $data["store_user_id"]=$getdata["user_id"];
                $data["remarks"]=$getdata["remarks"];
               // $data["card"]=$getdata["card"];
                 $data["firstname"]=$getdata["firstname"];
                 $data["lastname"]=$getdata["lastname"];
                 $data["storename"]=$getdata["storename"]; 
              //exit;
                //$data['store_user_id']=$this->user->getId();

                $data["cancel"]=$_SERVER["HTTP_REFERER"];   
                                                
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/exp.tpl', $data));
	}
     public function explist() {
		$this->load->language('setting/store');

		$this->document->setTitle('Stores');

		$this->load->model('setting/store');

		$this->getexpList();
	}    
        
     	protected function getexpList() {
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Expense Wavier off',
			'href' => $this->url->link('setting/debitstore', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
		

		$data['stores'] = array();

		/*$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
*/
		$store_total = $this->model_setting_store->getTotalStores();

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_url'] = $this->language->get('column_url');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/expstorelist.tpl', $data));
	}   
        	
/////////////////////////////////////////////////////

public function waiver_report() {

$this->load->language('setting/store');

$this->document->setTitle('Waiver Report');

$this->load->model('setting/store');

if (isset($this->request->get['filter_date_start'])) {
$data['filter_date_start']=$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m')."-01";
}

if (isset($this->request->get['filter_date_end'])) {
$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['filter_stores_id'])) {
$data['filter_stores_id']=$filter_stores_id = $this->request->get['filter_stores_id'];
} else {
$filter_stores_id = 0;
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
'text' => 'Waiver Report',
'href' => $this->url->link('reportdscl/debitstore/waiver _report', 'token=' . $this->session->data['token'] , 'SSL')
);
$data['token'] = $this->session->data['token'];
$this->load->model('setting/store');
$data['order_stores'] = $this->model_setting_store->getStoresCompanyWise('1');
$data['waveoffdata'] = array();

$filter_data = array(
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
     'filter_company'   => '1',
'filter_stores_id' => $filter_stores_id,
'start' => ($page - 1) * $this->config->get('config_limit_admin'),
'limit' => $this->config->get('config_limit_admin')
);


$product_total = $this->model_setting_store->getTotalWaiver_Report_companywise($filter_data);

$results = $this->model_setting_store->Waiver_Report_companywise($filter_data);

foreach ($results as $result) { //print_r($result);
$data['waveoffdata'][] = array(
'amount' => $result['cash'],
'response' => $result['response'],
'cr_date' => $result['cr_date'],
'name' => $result['name'],
'from_date' => $result['from_date'],
'to_date' => $result['to_date'],
'firstname' => $result['firstname'],
'lastname' => $result['lastname'],
'store_id' => $result['store_id'],
'store_user' =>$result['store_user'],
'document_no' => $result['document_no']
);

}

$data['stores'] = $this->model_setting_store->getStoresCompanyWise('1');

$data['heading_title'] = 'Waiver Report';

$data['text_list'] = $this->language->get('text_list');
$data['text_no_results'] = $this->language->get('text_no_results');
$data['text_confirm'] = $this->language->get('text_confirm');

$data['column_name'] = $this->language->get('column_name');
$data['column_url'] = $this->language->get('column_url');
$data['column_action'] = $this->language->get('column_action');

$data['button_add'] = $this->language->get('button_add');
$data['button_edit'] = $this->language->get('button_edit');
$data['button_delete'] = $this->language->get('button_delete');

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

if (isset($this->request->post['selected'])) {
$data['selected'] = (array)$this->request->post['selected'];
} else {
$data['selected'] = array();
}
$data['insert']=$this->url->link('setting/debitstore/exp', 'token=' . $this->session->data['token'], 'SSL');
$data['cancel']=$this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');

$pagination = new Pagination();
$pagination->total = $product_total;
$pagination->page = $page;
$pagination->limit = $this->config->get('config_limit_admin');
$pagination->url = $this->url->link('reportdscl/debitstore/waiver _report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

$data['pagination'] = $pagination->render();
$data['filter_stores_id']=$filter_stores_id;
$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

$data['filter_date_start'] = $filter_date_start;
$data['filter_date_end'] = $filter_date_end;
$data['filter_order_status_id'] = $filter_order_status_id;

$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('reportdscl/Waiver_Report.tpl', $data));
}
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

public function getWaveoffdata() {
$this->load->language('setting/store');

$this->document->setTitle('Expense Report');

$this->load->model('setting/store');

$this->getWaveoffdataList();
}

protected function getWaveoffdataList() {
if (isset($this->request->get['filter_date_start'])) {
$data['filter_date_start']=$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m')."-01";
}

if (isset($this->request->get['filter_date_end'])) {
$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['filter_stores_id'])) {
$data['filter_stores_id']=$filter_stores_id = $this->request->get['filter_stores_id'];
} else {
$filter_stores_id = 0;
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
'text' => 'Expense Report',
'href' => $this->url->link('reportdscl/debitstore/getWaveoffdata', 'token=' . $this->session->data['token'] , 'SSL')
);
$data['token'] = $this->session->data['token'];
$this->load->model('setting/store');
$data['order_stores'] = $this->model_setting_store->getStoresCompanyWise('1');
$data['waveoffdata'] = array();

$filter_data = array(
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
 'filter_company'   => '1',
'filter_stores_id' => $filter_stores_id,
'start' => ($page - 1) * $this->config->get('config_limit_admin'),
'limit' => $this->config->get('config_limit_admin')
);


$product_total = $this->model_setting_store->getTotalWaiveoffdata_companywise($filter_data);

$results = $this->model_setting_store->getWaiveoffdata_companywise($filter_data);

foreach ($results as $result) { //print_r($result);
$data['waveoffdata'][] = array(
'amount' => $result['cash'],
'response' => $result['response'],
'cr_date' => $result['cr_date'],
'name' => $result['name'],
'from_date' => $result['from_date'],
'to_date' => $result['to_date'],
'firstname' => $result['firstname'],
'lastname' => $result['lastname'],
'store_id' => $result['store_id'],
'store_user' =>$result['store_user']
);

}

$data['stores'] = $this->model_setting_store->getStoresCompanyWise('1');

$data['heading_title'] = 'Expense Report';

$data['text_list'] = $this->language->get('text_list');
$data['text_no_results'] = $this->language->get('text_no_results');
$data['text_confirm'] = $this->language->get('text_confirm');

$data['column_name'] = $this->language->get('column_name');
$data['column_url'] = $this->language->get('column_url');
$data['column_action'] = $this->language->get('column_action');

$data['button_add'] = $this->language->get('button_add');
$data['button_edit'] = $this->language->get('button_edit');
$data['button_delete'] = $this->language->get('button_delete');

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

if (isset($this->request->post['selected'])) {
$data['selected'] = (array)$this->request->post['selected'];
} else {
$data['selected'] = array();
}
$data['insert']=$this->url->link('setting/debitstore/exp', 'token=' . $this->session->data['token'], 'SSL');
$data['cancel']=$this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');

$pagination = new Pagination();
$pagination->total = $product_total;
$pagination->page = $page;
$pagination->limit = $this->config->get('config_limit_admin');
$pagination->url = $this->url->link('reportdscl/debitstore/getWaveoffdata', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

$data['pagination'] = $pagination->render();
$data['filter_stores_id']=$filter_stores_id;
$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

$data['filter_date_start'] = $filter_date_start;
$data['filter_date_end'] = $filter_date_end;
$data['filter_order_status_id'] = $filter_order_status_id;

$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('reportdscl/wave_offdtl_list.tpl', $data));
}

/////////////////////////

public function getWaveoffdata_download() {


$this->load->model('setting/store');
if (isset($this->request->get['filter_date_start'])) {
$data['filter_date_start']=$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m')."-01";
}

if (isset($this->request->get['filter_date_end'])) {
$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['filter_stores_id'])) {
$data['filter_stores_id']=$filter_stores_id = $this->request->get['filter_stores_id'];
} else {
$filter_stores_id = 0;
}



$data['waveoffdata'] = array();

$filter_data = array(
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
 'filter_company'   => '1',
'filter_stores_id' => $filter_stores_id
);


$results = $this->model_setting_store->getWaiveoffdata_companywise($filter_data);

include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name',
        'Store ID',
        'User Name',
        'From date',
        'To Date',
        'Expense Description',
        'Create Date',
        'Amount',
        'Approved By'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
foreach ($results as $result) { //print_r($result);
$data['waveoffdata'][] = array(
'amount' => $result['cash'],
'response' => $result['response'],
'cr_date' => $result['cr_date'],
'name' => $result['name'],
'from_date' => $result['from_date'],
'to_date' => $result['to_date'],
'firstname' => $result['firstname'],
'lastname' => $result['lastname'],
'store_id' => $result['store_id'],
'store_user' =>$result['store_user']
);

}		
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_user']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['from_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['to_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['response']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['cr_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['cash']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['firstname']." ".$data['lastname']);
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Expense_Report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');

}

public function get_store_incharges()
{
$this->load->model('setting/auditstore');
if (isset($this->request->get['store_id'])) 
{
$data['store_id']=$store_id = $this->request->get['store_id'];
} 


$filter_data = array(
'store_id' => $store_id
);


$results = $this->model_setting_auditstore->get_store_incharges($filter_data);
//print_r($results);
$return='<option value="">SELECT IN-CHARGE</option>';
foreach($results as $result)
{
if($result['status']=='1')
{
$status='Enabled';
}
else
{
$status='Disabled';
}
$return.='<option value="'.$result['user_id'].'">'.$result['firstname'].' '.$result['lastname'].' - '.$status.'</option>';
}
echo $return;
}


//////////////////////////////////////////////

public function waiver_report_download() {



if (isset($this->request->get['filter_date_start'])) {
$data['filter_date_start']=$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m')."-01";
}

if (isset($this->request->get['filter_date_end'])) {
$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['filter_stores_id'])) {
$data['filter_stores_id']=$filter_stores_id = $this->request->get['filter_stores_id'];
} else {
$filter_stores_id = 0;
}


$data['token'] = $this->session->data['token'];
$this->load->model('setting/store');
$data['order_stores'] = $this->model_setting_store->getOwnStores();
$data['waveoffdata'] = array();

$filter_data = array(
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'filter_stores_id' => $filter_stores_id,
'filter_company' => '1'
);
$results = $this->model_setting_store->Waiver_Report_companywise($filter_data);



//$results = $this->model_setting_store->getWaiveoffdata($filter_data);

include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name',
        'Store ID',
        'User Name',
        'Date',
        'Waiver Description',
        'Amount',
        'Approved By',
        'Document No / Letter ID'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
   	
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_user']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['cr_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['response']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['cash']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['document_no']);
       
        
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Waiver_Report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');

}

/////////////////////////////////////////////////////



	}