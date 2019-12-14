<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerReportisecCe extends Controller {
	public function index() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('CE Report (Merged)');

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
		
                            if (isset($this->request->get['filter_user'])) {
	                            $filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null; 
		}
                            
		if (isset($this->request->get['page1'])) {
			$page1 = $this->request->get['page1'];
		} else {
			$page1 = 1;
		}
		if (isset($this->request->get['page2'])) {
			$page2 = $this->request->get['page2'];
		} else {
			$page2 = 1;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'CE Report (Merged)',
			'href' => $this->url->link('reportisec/ce', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$this->load->model('report/sale');
                            $this->load->model('report/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	 => $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_user'         => $filter_user,
                        'filter_company'       => '3',
			'start'                  => ($page1 - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_cash->getTotalCash_transation_CompanyWise($filter_data);
		$bank_totals = $this->model_report_cash->get_bank_sum_cash_companywise($filter_data);
		//print_r($bank_totals);
		$data['orders'] = array();

		$results = $this->model_report_cash->getCash_report_CompanyWise($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				'SIID' => $result['transid'],
				'amount'   => $result['amount'],
				'store_id'      => $result['store_id'],
				'name'     => $result['name'],
                                'date_added'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
				'bank_name'      => $result['bank_name'],
				'status'      => $result['status'],
				'accepted_by'      => $result['firstname']." ".$result['lastname']
			);
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
                             if (isset($this->request->get['filter_tab'])) {

			$url .= '&filter_tab=' . $this->request->get['filter_tab'];
                                          
		}
		if (isset($this->request->get['page1'])) {
			$url .= '&page1=' . $this->request->get['page1'];
		}
		if (isset($this->request->get['page2'])) {
			$url .= '&page2=' . $this->request->get['page2'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page1;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportisec/ce', 'token=' . $this->session->data['token'] . $url . '&page1={page}&tab=tab1&page2='.$page2, 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page1 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page1 - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page1 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));


//////////////////////////////////////////////tab 2 start here//////////////////////////

		$filter_data2 = array(
			'filter_date_start'	 => $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_user'         => $filter_user,
                         'filter_company'       => '3',
			'start'                  => ($page2 - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

                           $order_total_records = $this->model_report_cash->getTotalCash_transationRunner_CompanyWise($filter_data2);

		$bank_totals2 = $this->model_report_cash->getTotalCash_transationRunnerbank_companywise($filter_data2);
                            //print_r($bank_totals2 );
		$data['records'] = array();

		$results = $this->model_report_cash->getCash_reportRunner_CompanyWise($filter_data2);
                
		foreach ($results as $result) { 
				if($result['bank_name']=="")
				{
				$bank=$result['bank'];
				}
				else
				{
				$bank=$result['bank_name'];
				}
			         $data['records'][] = array(
				'SIID' => $result['SID'],
				'amount'   => $result['amount'],
				'bank'      => $bank,
				'branch'     => $result['branch'],
                                                        'deposit_date'=>  date($this->language->get('date_format_short'), strtotime($result['deposit_date'])),   
				
				'status'      => $result['status'],
				'runner_name'      => $result['firstname']." ".$result['lastname']
			);
		}

		$pagination2 = new Pagination();
		$pagination2->total = $order_total_records;
		$pagination2->page = $page2;
		$pagination2->limit = $this->config->get('config_limit_admin');
		$pagination2->url = $this->url->link('reportisec/ce', 'token=' . $this->session->data['token'] . $url . '&page2={page}&tab=tab2&page1='.$page1, 'SSL');

		$data['pagination2'] = $pagination2->render();

		$data['results2'] = sprintf($this->language->get('text_pagination'), ($order_total_records) ? (($page2 - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page2 - 1) * $this->config->get('config_limit_admin')) > ($order_total_records - $this->config->get('config_limit_admin'))) ? $order_total_records : ((($page2 - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total_records, ceil($order_total_records / $this->config->get('config_limit_admin')));
		
		
		$data['heading_title'] ='CE Report (Merged)';
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
		
                            if($this->request->get['tab']=='tab2')
                                          {
                                            $data['tab1']="";
			  $data['tab2']=" active";
                                          }
                                          else
                                          {
                                            $data['tab1']=" active";
			  $data['tab2']="";
                                          }
                            $this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStoresCompanyWise('3');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
                            $data['filter_user'] = $filter_user;
                           

		$data["hdfc_total"]=$bank_totals["HDFC"];
		$data["State_Bank_of_India_total"]=$bank_totals["State_Bank_of_India"];
		$data["ICICI_total"]=$bank_totals["ICICI"];
		$data["TAGGED_BILLS_total"]=$bank_totals["TAGGED_BILLS"];

                            $data["hdfc_total2"]=$bank_totals2["HDFC"];
		$data["State_Bank_of_India_total2"]=$bank_totals2["State_Bank_of_India"];
		$data["ICICI_total2"]=$bank_totals2["ICICI"];
		$data["TAGGED_BILLS_total2"]=$bank_totals2["TAGGED_BILLS"];
		$this->load->model('runner/cash');
		$data['allces']=$this->model_runner_cash->getCeByCompany(array('filter_company'=>'2'));
		$data['header'] = $this->load->controller('common/header'); 
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                           // $mcrypt=new MCrypt();
                            //echo $mcrypt->decrypt('7e126d01d0362588e0122c1eac729bdf9277ec1faa37dc20da4821ae901e23ff'); 
		$this->response->setOutput($this->load->view('reportisec/ce_report.tpl', $data));
	}


public function download_excel_runner() {
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d');
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
 		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
                           $this->load->model('report/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_status'           => $filter_status,
                        'filter_company'            => '3',
			'filter_user'	     => $filter_user
		);
               

		$data['orders'] = array();
                       $results = $this->model_report_cash->getCash_reportRunner_CompanyWise($filter_data);
                      
		
		
		include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Trans ID',
        'Deposit Amount',
        'Bank',
        'Branch',
        'Deposit Date',
        'Deposit by',
        'Transaction Number',
        'Remarks',
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
        if($data["status"]=="1") { $status="Accepted"; } else if($data["status"]=="2") { $status="Rejected"; } else { $status="Pending"; }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['SID']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['bank']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['branch']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($data['deposit_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['transaction_number']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['remarks']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $status);
        
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Cash_Deposited_by_CE'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}

        public function download_excel() {
        
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
	if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
                 if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}
                            if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
                $this->load->model('report/cash');
        $data['orders'] = array();

        $filter_data = array(
            'filter_date_start'         => $filter_date_start,
            'filter_date_end'         => $filter_date_end,
            'filter_user'         => $filter_user,
            'filter_company'      => '3',
            'filter_status'         => $filter_status,
            'filter_store'         => $filter_store
            
        );

        $data['orders'] = array();

        $results = $this->model_report_cash->getCash_report_CompanyWise($filter_data);
                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Store Name ',
        'Bank',
        'Date',
        'Amount',
	'Status',
	'By whom'
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
      if($data['status']=="0") { $status="Pending"; } 
	else if($data['status']=="1") { $status="Accepted"; } 
	else if($data['status']=="2") { $status="Rejected"; } 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bank_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $status);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['firstname']." ".$data['lastname']);
            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Cash_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
        














public function runner() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Cash Deposited by CE Report');

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
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
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
			'text' => 'Cash Deposited by CE Report',
			'href' => $this->url->link('reportisec/cash_report/runner', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$this->load->model('report/sale');
                            $this->load->model('report/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_status'           => $filter_status,
			'filter_user' => $filter_user,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_report_cash->getTotalCash_transationRunner($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_cash->getCash_reportRunner($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				'SIID' => $result['SID'],
				'amount'   => $result['amount'],
				'bank'      => $result['bank'],
				'branch'     => $result['branch'],
                                                        'deposit_date'=>  date($this->language->get('date_format_short'), strtotime($result['deposit_date'])),   
				
				'status'      => $result['status'],
				'runner_name'      => $result['firstname']." ".$result['lastname']
			);
		}

		$data['heading_title'] ='Cash Deposited by CE Report';
		
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

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		

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
                            if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/cash_report/runner', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
                            $this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['filter_user'] = $filter_user;
                            $data['filter_status'] = $filter_status;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                           // $mcrypt=new MCrypt();
                            //echo $mcrypt->decrypt('7e126d01d0362588e0122c1eac729bdf9277ec1faa37dc20da4821ae901e23ff'); 
		$this->response->setOutput($this->load->view('report/cash_report_runner.tpl', $data));
	}
        public function runner_cash_position()
        { 
            $this->load->language('report/cash_report');

		$this->document->setTitle('CE Cash Position');

		
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
                if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = NULL;
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
                            if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
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
			'text' => 'CE Cash Position',
			'href' => $this->url->link('report/cash_report/runner_cash_position', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$this->load->model('report/sale');
                            $this->load->model('runner/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_store' => $filter_store,
                        'filter_date'  => $filter_date,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

                $arr=$this->model_runner_cash->get_runner_total_Cash_position($filter_data);

                 //print_r($arr);

                $order_total = $arr["total"];
                $data["total_amount"] = $arr["total_amount"];
                
		$data['orders'] = array();

		$results = $this->model_runner_cash->get_runner_Cash_position($filter_data);
                
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				'amount'   => $result['amount'],
				'user_name'      => $result['firstname']." ".$result['lastname'],
				
                                                        'updated_time'=>  date($this->language->get('date_format_short'), strtotime($result['update_date']))  
				
			);
		}

		$data['heading_title'] = 'CE Cash Position';
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		
		$url = '';

		
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                           if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/cash_report/runner_cash_position', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_store'] = $filter_store;
                            $data['filter_date'] = $filter_date;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $this->response->setOutput($this->load->view('report/ce_current_report.tpl', $data));
        }
        public function download_runner_current_postion_excel()
        { 
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
	if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
                
                $this->load->model('runner/cash');
        $data['orders'] = array();

        $filter_data = array(
            'filter_date_start'         => $filter_date_start,
            'filter_date_end'         => $filter_date_end,
            'filter_store'         => $filter_store
            
        );

        $data['orders'] = array();

        $results = $this->model_runner_cash->get_runner_Cash_position($filter_data);
                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'User Name ',
        'Amount',
        'Last updated'
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
     
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['amount']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['update_date'])));
        
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Runner_current_Cash_position_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
       }
}