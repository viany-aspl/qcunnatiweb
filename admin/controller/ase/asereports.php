<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerAseAsereports extends Controller {


public function index() {
$this->load->language('report/customer_activity');

$this->document->setTitle('Get My Farmers');

if (isset($this->request->get['filter_userid'])) {
$filter_userid = $this->request->get['filter_userid'];
} else {
$filter_userid = null;
}


if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m').'-01';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['page'])) {
$page = $this->request->get['page'];
} else {
$page = 1;
}

$url = '';

if (isset($this->request->get['filter_customer'])) {
$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
}


if (isset($this->request->get['filter_date_start'])) {
$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
}

if (isset($this->request->get['filter_date_end'])) {
$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
}



if (isset($this->request->get['filter_userid'])) {
$url .= '&filter_userid=' . $this->request->get['filter_userid'];
}

if (isset($this->request->get['page'])) {
$url .= '&page=' . $this->request->get['page'];
}

$data['breadcrumbs'] = array();

$data['breadcrumbs'][] = array(
'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
'text' => $this->language->get('text_home')
);

$data['breadcrumbs'][] = array(
'href' => $this->url->link('ase/asereports', 'token=' . $this->session->data['token'] . $url, 'SSL'),
'text' => 'Get My Farmers'
);

$this->load->model('ase/ase');

$data["listUserId"] = $this->model_ase_ase->getUserid();

$data['activities'] = array();

$filter_data = array(
'filter_userid' => $filter_userid,

'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'start' => ($page - 1) * 20,
'limit' => 20
);

$activity_total = $this->model_ase_ase->getTotalMyCustomers($filter_data);

$results = $this->model_ase_ase->getMyCustomers($filter_data);

foreach ($results as $result) {

$data['activities'][] = array(
'firstname' => strtoupper($result['firstname']." ".$result['laststname']),
'telephone' => $result['telephone'],
'date_added' => date('d/m/Y', strtotime($result['date_added'])),
'adfirstname' =>strtoupper($result['adfirstname']." ".$result['adlastname'])
);
}

$data['heading_title'] ='Get My Farmers';

$data['text_list'] = $this->language->get('text_list');
$data['text_no_results'] = $this->language->get('text_no_results');


$data['entry_customer'] = $this->language->get('entry_customer');

$data['entry_date_start'] = $this->language->get('entry_date_start');
$data['entry_date_end'] = $this->language->get('entry_date_end');

$data['button_filter'] = $this->language->get('button_filter');

$data['token'] = $this->session->data['token'];



$pagination = new Pagination();
$pagination->total = $activity_total;
$pagination->page = $page;
$pagination->limit = $this->config->get('config_limit_admin');
$pagination->url = $this->url->link('ase/asereports', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

$data['pagination'] = $pagination->render();

$data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($activity_total - $this->config->get('config_limit_admin'))) ? $activity_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $activity_total, ceil($activity_total / $this->config->get('config_limit_admin')));

$data['filter_userid'] = $filter_userid;
$data['filter_date_start'] = $filter_date_start;
$data['filter_date_end'] = $filter_date_end;

$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('ase/getmyfarmers.tpl', $data)); 
}


public function getmycustomers_download() {
		
if (isset($this->request->get['filter_userid'])) {
$filter_userid = $this->request->get['filter_userid'];
} else {
$filter_userid = null;
}
//echo $filter_userid;
if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m').'-01';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

		

$results = array();

 $this->load->model('ase/ase');



$filter_data = array(
'filter_userid' => $filter_userid,

'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end
);



$results = $this->model_ase_ase->getMyCustomers($filter_data);
//exit;
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Farmer Name',
        'Mobile No',
        'Date Added',
        'Added By'
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
      
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, strtoupper($data['firstname']." ".$data['laststname']));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['telephone']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('d/m/Y', strtotime($data['date_added'])));
       
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, strtoupper($data['adfirstname']." ".$data['adlastname']));
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="farmer_add_by_ase'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}



        public function village_visit() {
		$this->load->language('report/Inventory_report');

		$this->document->setTitle('ASE- Village Visit');

		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
                
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'ASE- Village Visit',
			'href' => $this->url->link('ase/asereports/village_visit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('setting/store');
                            $this->load->model('ase/ase');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_store' => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		

		$data['orders'] = array();
 if ($this->request->get['filter_store']!="") {
     $order_total = $this->model_ase_ase->getTotalVillageVisit($filter_data);
 $results = $this->model_ase_ase->getVillageVisit($filter_data);
 
 }
		
                //$taxc=new Tax();                

		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
                                                        'village_name' => strtoupper($result['village_name']),
				'farmer_count' => $result['farmer_count'],
                                                        'ase_name'      => strtoupper($result['firstname']." ".$result['lastname']),
                                                        'store_name'      => $result['store_name'],
				'visit_date'   => $result['visit_date'],
				'remarks'      => $result['remarks']
                                
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');
                            $data['stores'] = $this->model_setting_store->getStores();//print_r($data['stores'] );
		
		$url = '';

		
                            if ($this->request->get['filter_store']!="") {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ase/asereports/village_visit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_store'] = $filter_store;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ase/village_visit_report.tpl', $data));
	}
       public function village_visit_download() {
		
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
                            $this->load->model('ase/ase');
		$data['orders'] = array();

		$filter_data = array(
			
			'filter_store' => $filter_store
		);

		

		$results = array();

 if ($this->request->get['filter_store']!="") {
     
 $results = $this->model_ase_ase->getVillageVisit($filter_data);
 
 }
		
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Village name',
        'Store Name',
        'ASE name',
        'Visit date',
        'Farmer count',
        'Remarks'
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
      
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, strtoupper($data['village_name'])); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, strtoupper($data['firstname']." ".$data['lastname']));
       
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, date($this->language->get('date_format_short'), strtotime($data['visit_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['farmer_count']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['remarks']);
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Village_visit_by_ase'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}

public function aselist() {
		$this->load->language('report/stock_transfer');

		$this->document->setTitle("All ASE Users");

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['filter_name'])) {
			$data['filter_name']=$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		if (isset($this->request->get['filter_store'])) {
			$data['filter_store']=$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
		$url = '';

		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		} 
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => "All ASE Users",
			'href' => $this->url->link('ase/asereports/aselist', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('ase/ase');
                //$this->load->model('setting/store');

		$data['orders'] = array();

		$filter_data = array(
                        
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'filter_store'=>$filter_store,
			'filter_name'=>$filter_name
		);
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStoresWeb();
		$order_total = $this->model_ase_ase->getTotalASE($filter_data);
		
		$results = $this->model_ase_ase->getASE($filter_data);

		foreach ($results as $result) { //print_r($result);
                
                
			$data['orders'][] = array(
				'store_name'   => $result['name'],
				'status' => $result['status'],
				'store_id'     => $result['store_id'],
				
                                'telephone'   => $result['username'],
                                'name'   => $result['firstname']." ". $result['lastname']
                                );
		}

		$data['heading_title'] = 'ASE Users';
		
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];


		$url = '';
                
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ase/asereports/aselist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
                $data['filter_store'] = $filter_store;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['filter_name']= $filter_name;
                $data['filter_name_id']= $filter_name_id;
		$this->response->setOutput($this->load->view('ase/view_all_ase.tpl', $data));
	}
         public function download_excel_aselist() {
        
        $this->load->model('ase/ase');
        if (isset($this->request->get['filter_name'])) {
			$data['filter_name']=$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		if (isset($this->request->get['filter_store'])) {
			$data['filter_store']=$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
        

		$data['orders'] = array();

		$filter_data = array(
			
		'filter_store'=>$filter_store,
			'filter_name'=>$filter_name	
			
		);


    $results = $this->model_ase_ase->getASE($filter_data);
//echo count($results);exit;
        
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'Name',
        'Telephone',
	'Store Name',
	'Status'
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
        if($data['status']=="1")
       {
	$status="Active";
       }
       else
      {
	$status="Deactive";
      }
       // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['name']);
        //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['firstname']." ".$data["lastname"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['username']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $status);
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="ase-user'.'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    
    }
///////////////////////////////
public function summary() {
		$this->load->language('report/customer_activity');

		$this->document->setTitle('ASE Summary Report');

		if (isset($this->request->get['filter_userid'])) {
			$filter_userid = $this->request->get['filter_userid'];
		} else {
			$filter_userid = null;
		}

		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m').'-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('ase/asereports/summary', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text' => 'Get My Farmer'
		);

		$this->load->model('ase/ase');
                
                $data["listUserId"] = $this->model_ase_ase->getUserid();
                
		$data['activities'] = array();

		$filter_data = array(
			'filter_userid'     => $filter_userid,			
			'filter_date_start' => $filter_date_start,
			'filter_date_end'   => $filter_date_end,
			'start'             => ($page - 1) * 20,
			'limit'             => 20
		);

		
                $activity_total=$this->model_ase_ase->getasesall($filter_data);
                $getases=$this->model_ase_ase->getases($filter_data);
		foreach ($getases as $result) {
                    //print_r($result["user_id"]);
                    $totalbookedorder = $this->model_ase_ase->getordersbookedcount($filter_data,$result["user_id"]); 
                    $totalconvertedorder = $this->model_ase_ase->getordersconvertedcount($filter_data,$result["user_id"]);                
	            $totaladdedcustomer= $this->model_ase_ase->getcustomeraddedcount($filter_data,$result["user_id"]);
			$data['returndata'][] = array(
				'firstname'    => strtoupper($result['firstname']),
				'lastname'         => strtoupper($result['lastname']),
			        'totalbookedorder' =>$totalbookedorder,
                                'totalconvertedorder'=>$totalconvertedorder,
                                'totaladdedcustomer'=>$totaladdedcustomer
                            );
		}
                //print_r($data['returndata']);
		$data['heading_title'] ='ASE Summary Report';
		
		
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $activity_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('ase/asereports/summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($activity_total - $this->config->get('config_limit_admin'))) ? $activity_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $activity_total, ceil($activity_total / $this->config->get('config_limit_admin')));

		$data['filter_userid'] = $filter_userid;
		
		$data['filter_date_start'] = $filter_date_start; 
		$data['filter_date_end'] = $filter_date_end;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('ase/asesummary.tpl', $data));
	}


public function getvillage() {
$this->document->setTitle('Village List');
$this->load->model('ase/ase');
$this->getVillageList();
}

protected function getVillageList() { 
$this->document->setTitle("Village List");
if (isset($this->request->get['filter_store'])) {
$filter_store = $this->request->get['filter_store'];
} else {
$filter_store = 0;
}
if (isset($this->request->get['page'])) {
$page = $this->request->get['page'];
} else {
$page = 1;
}

$url = '';

if (isset($this->request->get['filter_store'])) {
$url .= '&filter_store=' . $this->request->get['filter_store'];
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
'text' => "Village list",
'href' => $this->url->link('ase/asereports/getvillage', 'token=' . $this->session->data['token'] . $url, 'SSL')
);
$this->load->model('setting/store');
$data['stores'] = $this->model_setting_store->getStores();
$this->load->model('ase/ase');
$data['orders'] = array();

$filter_data = array(
'filter_store' => $filter_store,
'start' => ($page - 1) * $this->config->get('config_limit_admin'),
'limit' => $this->config->get('config_limit_admin')
);

$order_total = $this->model_ase_ase->get_Totalvillage($filter_data);

$data['orders'] = array();

$results = $this->model_ase_ase->get_village($filter_data);

foreach ($results as $result) { //print_r($result);
$data['orders'][] = array(
'village_name' => $result['village_name'],
'district' => $result['district'],
'pincode' => $result['pincode'],
'name' => $result['name']
);
}
$pagination = new Pagination();
$pagination->total = $order_total;
$pagination->page = $page;
$pagination->limit = $this->config->get('config_limit_admin');
$pagination->url = $this->url->link('ase/asereports/getVillage', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
$this->load->model('setting/store');
$data['stores'] = $this->model_setting_store->getStores();
$data['pagination'] = $pagination->render();

$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

$data['filter_store'] = $filter_store;

$data['token'] = $this->session->data['token'];
$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');
$data['action'] = $this->url->link('ase/asereports/getvillage', 'token=' . $this->session->data['token'] . $url, 'SSL');
$this->response->setOutput($this->load->view('ase/getVillageList.tpl', $data));
}


}