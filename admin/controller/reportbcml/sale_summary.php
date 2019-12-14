<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
ini_set('max_execution_time', 3000);  //3000 seconds = 50 minutes 

class ControllerReportbcmlSaleSummary extends Controller {
	public function index() {
		$this->load->language('report/sale_report');
					$this->load->language('report/sale_order');
		$this->document->setTitle('Sale Summary');
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
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
			'text' => 'Sale Summary',
			'href' => $this->url->link('report/sale_summary', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                $this->load->model('report/sale_summary');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
                        'filter_company' => '2',
			'filter_group'           => $filter_group,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

						$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$t1=$this->model_report_sale_summary->getTotalSaleComapnywise($filter_data);
		$order_total =$t1["total"] ;

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summaryComapnywise($filter_data);

                		$total_cash_all=$t1["Cash"];
                            $total_tagged_all=$t1["Tagged"];
                            $total_subsidy_all=$t1["Subsidy"];

		foreach ($results as $result) { //print_r($result);
			$total_cash=$total_cash+$result['Cash'];
                                          $total_tagged=$total_tagged+$result['Tagged'];
                                          $total_subsidy=$total_subsidy+$result['Subsidy'];

			         $data['orders'][] = array(
                             				   'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'cash_order' => $result['cash_order'],
				'tagged_order' => $result['tagged_order'],
				'subsidy_order' => $result['Subsidy_order'],
				'cash'		=>$this->currency->format($result['Cash']),	
				'tagged'	=>$this->currency->format($result['Tagged']),	
				'subsidy'	=>$this->currency->format($result['Subsidy']),
                               				 'creditlimit'	=>$this->currency->format($result['creditlimit']),
                                				'currentcredit'	=>$this->currency->format($result['currentcredit']),
				'total'   => $this->currency->format(($result['Cash']+$result['Tagged']+$result['Subsidy']))
			);
		}

		$data['token'] = $this->session->data['token'];
                
		$url = '';
				
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		
		$data['total_cash'] = $total_cash;
                            $data['total_tagged'] = $total_tagged;
                            $data['total_subsidy'] = $total_subsidy;   
		$data['total_cash_all'] = $total_cash_all;
                            $data['total_tagged_all'] = $total_tagged_all;
                            $data['total_subsidy_all'] = $total_subsidy_all;


		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_summary.tpl', $data));
	}

       public function download_excel() {




if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_group'           => $filter_group
			
		);

        $this->load->model('report/sale_summary');
        $order_total = $this->model_report_sale_summary->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Store Name',
        
        'Store Credit Limit',
        'Current Credit',
        'No. Order (Cash)',
        'No. Order (Tagged)',
        'No. Order (Subsidy)',
	'Cash',
	'Tagged+Cash',
        'Subsidy',
        'Total'
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
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['currentcredit']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['creditlimit']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['cash_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['tagged_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Subsidy_order']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Cash']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['Subsidy']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, ($data['Cash']+$data['Tagged']+$data['Subsidy']));
          
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sale_summary_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
        public function email_excel() {
           $this->load->model('report/sale_summary');
        $order_total = $this->model_report_sale_summary->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'Store Name',
	'Cash',
	'Tagged',
        'Total'
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
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Cash']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Tagged']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, ($data['Cash']+$data['Tagged']));
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='sale_summary_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    //
    $mail             = new PHPMailer();

                $body = "<p>Akshamaala Solution Pvt. Ltd.</p>";
                
                $mail->IsSMTP();
                $mail->Host       = "mail.akshamaala.in";
                                                           
                $mail->SMTPAuth   = false;                 
                $mail->SMTPSecure = "";                 
                $mail->Host       = "mail.akshamaala.in";      
                $mail->Port       = 25;                  
                $mail->Username   = "mis@akshamaala.in";  
                $mail->Password   = "mismis";            

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Sale Summary";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('chetan.singh@akshamaala.com', "Chetan Singh");
                
                

                $mail->AddAttachment(DIR_UPLOAD.$filename);
                
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                  if(!unlink(DIR_UPLOAD.$filename))
                  {
                      echo ("Error deleting ");
                  }
                  else
                  {
                     echo ("Deleted ");
                  }
                                  
                }
        }
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function sale_summary() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Sale Summary(Category)');

		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/sale_report');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');


		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

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
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Sale Summary(Category)',
			'href' => $this->url->link('reportbcml/sale_summary/sale_summary', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                            $this->load->model('report/sale_summary');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
			'filter_company'           => '2',
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$t1=$this->model_report_sale_summary->getTotalSale_new($filter_data);
		$order_total =$t1["total"] ;
		
		$data['orders'] = array();
		$results = $this->model_report_sale_summary->getSale_summary_new($filter_data);
        
		$total_cash_all=$t1["Cash"];
        $total_tagged_all=$t1["Tagged"]+$t1["CTagged"];
		$data['total_bcml_tagged_all']=$t1["bcml_tagged"]+$t1["CTagged"];
        $total_subsidy_all=$t1["Cash_subsidy"];
		$total_cash_tagged_all=$t1["Cash_Tagged"];
        $total_cash_subsidy_all=$t1["Subsidy"];

		foreach ($results as $result) {      //print_r($result);
			$total_cash=$total_cash+$result['Cash'];
            $total_bcml_tagged=$total_bcml_tagged+$result['bcml_tagged']+$result['Tagged_cash'];
			$total_tagged=$total_tagged+$result['tagged']+$result['Tagged_cash'];
            $total_subsidy=$total_subsidy+$result['Subsidy'];
			$total_Cash_Tagged=$total_Cash_Tagged+$result['Cash_Tagged'];
            $total_Cash_subsidy=$total_Cash_subsidy+$result['Cash_subsidy'];

			         $data['orders'][] = array(
                             		'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'cash_order' => $result['cash_order'],
				'tagged_order' => $result['tagged_order'],
				'subsidy_order' => $result['Subsidy_order'],
				'Cash_tagged_order' => $result['Cash_tagged_order'],
				'cash'		=>$this->currency->format($result['Cash']),	
				'tagged'	=>$this->currency->format($result['Tagged']+$result['Tagged_cash']),	
				'bcml_tagged'=>	$this->currency->format($result['bcml_tagged']+$result['Tagged_cash']),
				'subsidy'	=>$this->currency->format($result['Subsidy']),
				'Cash_Tagged'	=>$this->currency->format($result['Cash_Tagged']),
                'Cash_subsidy'	=>$this->currency->format($result['Cash_subsidy']),
                
				'total'   => $this->currency->format(($result['Cash']+$result['bcml_tagged']+$result['Tagged_cash']+$result['Subsidy']+$result['Cash_Tagged']+$result['Cash_subsidy']))
			);
		}
		$data['token'] = $this->session->data['token'];
                
		$url = '';
				
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/sale_summary/sale_summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		$data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
	
		$data['total_cash'] = $total_cash;
        $data['total_tagged'] = $total_tagged;
		$data['total_bcml_tagged'] = $total_bcml_tagged;
        $data['total_subsidy'] = $total_subsidy;
		$data["total_Cash_Tagged"]=$total_Cash_Tagged;
		$data['total_Cash_subsidy'] = $total_Cash_subsidy;

		$data['total_cash_all'] = $total_cash_all;
        $data['total_tagged_all'] = $total_tagged_all;
        $data['total_subsidy_all'] = $total_subsidy_all;//-$total_cash_subsidy_all;
		$data["total_cash_tagged_all"]=$total_cash_tagged_all;
		$data["total_cash_subsidy_all"]=$total_cash_subsidy_all;

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/sale_summary/sale_summary.tpl', $data));
	}
//////////////////////////////////////////////
public function download_excel_sale_summary() 
{
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

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

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
			'filter_company'           => '2'
			
		);

        $this->load->model('report/sale_summary');
       

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary_new($filter_data);
                
		                
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
         'Store Name',
             
         'Cash',      
         'Cash Tagged',
         'Cash Subsidy',
         'Tagged',
		 'BCML Tagged',
         'Subsidy(By Company )',
         'No. Order (Cash)',
         'No. Order (Tagged)',
         'No. Order (Cash_Tagged)',
         'No. Order (Subsidy)', 
         'Total'
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
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
       
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Cash']);  
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Cash_Tagged']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Cash_subsidy']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['Tagged']+$data['Tagged_cash']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['bcml_tagged']+$data['Tagged_cash']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['Subsidy']);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['cash_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['tagged_order']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $data['Cash_tagged_order']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $data['Subsidy_order']);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, ($data['Cash']+$data['bcml_tagged']+$data['Tagged_cash']+$data['Subsidy']+$data['Cash_Tagged']+$data['Cash_subsidy']));
          
             //'Cash_subsidy'	=>$this->currency->format($result['Cash_subsidy']),
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sale_summary_subsidycash_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function subsidy_summary() {
		$this->load->language('report/sale_report');
		$this->load->language('report/sale_order');
		$this->document->setTitle('Subsidy Summary');

		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('report/sale_report');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');


		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

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
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Subsidy Summary',
			'href' => $this->url->link('reportbcml/sale_summary/subsidy_summary', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
	
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');

		$this->load->model('setting/store');
                            $this->load->model('report/sale_summary');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'           => $filter_store,
			'filter_company'           => '2',
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$t1=$this->model_report_sale_summary->getTotalSale_new($filter_data);
		$order_total =$t1["total"] ;
		
		$data['orders'] = array();
		$results = $this->model_report_sale_summary->getSale_summary_new($filter_data);
        
		$total_cash_all=$t1["Cash"];
        $total_tagged_all=$t1["Tagged"]+$t1["CTagged"];
		$data['total_bcml_tagged_all']=$t1["bcml_tagged"]+$t1["CTagged"];
        $total_subsidy_all=$t1["Cash_subsidy"];
		$total_cash_tagged_all=$t1["Cash_Tagged"];
        $total_cash_subsidy_all=$t1["Subsidy"];

		foreach ($results as $result) 
		{      //print_r($result);
			$total_cash=$total_cash+$result['Cash'];
            $total_bcml_tagged=$total_bcml_tagged+$result['bcml_tagged']+$result['Tagged_cash'];
			$total_tagged=$total_tagged+$result['tagged']+$result['Tagged_cash'];
            $total_subsidy=$total_subsidy+$result['Subsidy'];
			$total_Cash_Tagged=$total_Cash_Tagged+$result['Cash_Tagged'];
            $total_Cash_subsidy=$total_Cash_subsidy+$result['Cash_subsidy'];

			         $data['orders'][] = array(
                             		'store_id' => $result['store_id'],
				'store_name' => $result['store_name'],
				'cash_order' => $result['cash_order'],
				'tagged_order' => $result['tagged_order'],
				'subsidy_order' => $result['Subsidy_order'],
				'Cash_tagged_order' => $result['Cash_tagged_order'],
				'cash'		=>$this->currency->format($result['Cash']),	
				'tagged'	=>$this->currency->format($result['Tagged']+$result['Tagged_cash']),	
				'bcml_tagged'=>	$this->currency->format($result['bcml_tagged']+$result['Tagged_cash']),
				'subsidy'	=>$this->currency->format($result['Subsidy']),
				'Cash_Tagged'	=>$this->currency->format($result['Cash_Tagged']),
                'Cash_subsidy'	=>$this->currency->format($result['Cash_subsidy']),
                
				'total'   => $this->currency->format(($result['Cash']+$result['bcml_tagged']+$result['Tagged_cash']+$result['Subsidy']+$result['Cash_Tagged']+$result['Cash_subsidy']))
			);
		}
		$data['token'] = $this->session->data['token'];
                
		$url = '';
				
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reportbcml/sale_summary/subsidy_summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		$data['stores'] = $this->model_setting_store->getStoresCompanyWise('2');
	
		$data['total_cash'] = $total_cash;
        $data['total_tagged'] = $total_tagged;
		$data['total_bcml_tagged'] = $total_bcml_tagged;
        $data['total_subsidy'] = $total_subsidy;
		$data["total_Cash_Tagged"]=$total_Cash_Tagged;
		$data['total_Cash_subsidy'] = $total_Cash_subsidy;

		$data['total_cash_all'] = $total_cash_all;
        $data['total_tagged_all'] = $total_tagged_all;
        $data['total_subsidy_all'] = $total_subsidy_all;//-$total_cash_subsidy_all;
		$data["total_cash_tagged_all"]=$total_cash_tagged_all;
		$data["total_cash_subsidy_all"]=$total_cash_subsidy_all;

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reportbcml/sale_summary/sale_summary.tpl', $data));
	}

}