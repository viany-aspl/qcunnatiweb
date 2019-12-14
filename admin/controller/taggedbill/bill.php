<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerTaggedbillBill extends Controller {
	
 public function index() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Bill Verification (Tagged bill)');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m')."-01";
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
			'text' => 'Bill Verification (Tagged bill)',
			'href' => $this->url->link('taggedbill/bill', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                            $this->load->model('taggedbill/bill');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                            $totalsss=$this->model_taggedbill_bill->getTotalReport($filter_data);
                            $data["total_amount"]=$totalsss["total_amount"];
		$order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_taggedbill_bill->getReport($filter_data);
                            $mcrypt=new MCrypt();
		foreach ($results as $result) { //print_r($result);
			         $data['orders'][] = array(
				
		    'total_amount'      => $result['total_amount'],
                                'bill_date'=>  date($this->language->get('date_format_short'), strtotime($result['date_start'])),   
                                'create_date'=>  date($this->language->get('date_format_short'), strtotime($result['create_date'])),  
		    'filled_user'   => $result['firstname']." ".$result['lastname'],
                                'file_name'   => $result['file_name'],
                                'bill_id'   => $result['sid'],
		    'status' => $result["status"],
		    'created_user'=> $result["creted_user"],
		    'store_name'=> $result["store_name"],
		    'unit'=> $result["unit"]
                                
			);
		}

		$data['heading_title'] = 'Bill Verification (Tagged bill)';
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

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

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('taggedbill/bill', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                            $this->load->model('user/user');
  
                            $data["logged_user"] = $mcrypt->encrypt($logged_user_data = $this->user->getId());
		$this->response->setOutput($this->load->view('taggedbill/verified_report.tpl', $data));
	}
//////////////////////////////////////////////////////////////////////
public function history() {   
		$this->load->language('report/cash_report');  

		$this->document->setTitle('Bill verification history');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m')."-01";
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
			'text' => 'Bill verification history',
			'href' => $this->url->link('taggedbill/bill/history', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		
                            $this->load->model('taggedbill/bill');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                            $totalsss=$this->model_taggedbill_bill->getTotalReport($filter_data);
                            $data["total_amount"]=$totalsss["total_amount"];
		$order_total = $totalsss["total"];//$array[0];

		$data['orders'] = array();

		$results = $this->model_taggedbill_bill->getReport($filter_data);
                            $mcrypt=new MCrypt();
		foreach ($results as $result) { //print_r($result);
                             if($result['accepted_date']!="")
		{
		     $accepted_date=date($this->language->get('date_format_short'), strtotime($result['accepted_date']));
		}
		else
		{
		 $accepted_date="";
		}
			         $data['orders'][] = array(
				
		    'total_amount'      => $result['total_amount'],
                                'bill_date'=>  date($this->language->get('date_format_short'), strtotime($result['date_start'])),   
                                'create_date'=>  date($this->language->get('date_format_short'), strtotime($result['create_date'])),  
		    'filled_user'   => $result['firstname']." ".$result['lastname'],
                                'file_name'   => $result['file_name'],
                                'bill_id'   => $result['sid'],
		    'status' => $result["status"],
		    'created_user'=> $result["creted_user"],
		    'store_name'=> $result["store_name"],
		    'unit'=> $result["unit"],
	                  'accepted_date'=>  $accepted_date,
	                  'accepted_user'   => $result['a_firstname']." ".$result['a_lastname']
                                
			);
		}

		$data['heading_title'] = 'Bill verification history';
		
		

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		

		$data['button_filter'] = $this->language->get('button_filter');

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

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('taggedbill/bill/history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_store'] = $filter_store;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                            $this->load->model('user/user');
  
                            $data["logged_user"] = $mcrypt->encrypt($logged_user_data = $this->user->getId());
		$this->response->setOutput($this->load->view('taggedbill/verified_list.tpl', $data));
	}

///////////////////////////////
public function accept_cash()
	{
                  $this->load->model('taggedbill/bill');
                 
             	    $this->load->model('user/user');
                  $mcrypt=new MCrypt();
                  if(($this->request->get['logged_user'])== ($mcrypt->encrypt($this->user->getId())))
                  {

                  if (($this->request->get['bill_id'] != '') && ($this->request->get['logged_user'] != ''))
                  { 
		 
                   $bill_id=$this->request->get['bill_id']; 
	     $logged_user=$mcrypt->decrypt($this->request->get['logged_user']); 
                   $this->model_taggedbill_bill->accept_reject_cash($bill_id,$logged_user,"1");
 
                    $this->session->data['success'] = 'Transaction Accepted Successfully';
                    $this->response->redirect($this->url->link('taggedbill/bill', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
	}
	else
	{ 
              $this->session->data['success'] = 'Unknown User !';	
	 $this->response->redirect($this->url->link('taggedbill/bill', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	}
	public function reject_cash()
	{
	 $this->load->model('user/user');
               $mcrypt=new MCrypt();
               if($this->request->get['logged_user']== $mcrypt->encrypt($logged_user_data = $this->user->getId()))
               {
	    $this->load->model('taggedbill/bill');
             
                  if (($this->request->get['bill_id'] != '') && ($this->request->get['logged_user'] != ''))
                  { 
		 
                   $bill_id=$this->request->get['bill_id']; 
	     $logged_user=$mcrypt->decrypt($this->request->get['logged_user']); 
                   $this->model_taggedbill_bill->accept_reject_cash($bill_id,$logged_user,"2");
                   
                    $this->session->data['success'] = 'Transaction Rejected Successfully';
                   $this->response->redirect($this->url->link('taggedbill/bill', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
	}
              else
	{
              $this->session->data['success'] = 'Unknown User !';	
	 $this->response->redirect($this->url->link('taggedbill/bill', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	}

////////////////////////////////////////////////////////////////



public function download_excel() {
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m')."-01";
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
                
		
                            $this->load->model('taggedbill/bill');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store'	     => $filter_store
		);
                            

		$data['orders'] = array();

		$results = $this->model_taggedbill_bill->getReport($filter_data);
    
    include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'SID',
        'Amount',
        'Store name',
        'Unit',
        'Bill date',
        'Created by',
        'Created date',
        'Status',
        'Verified by',
        'Verification date'
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
         if($data['accepted_date']!="")
		{
		     $accepted_date=date($this->language->get('date_format_short'), strtotime($data['accepted_date']));
		}
		else
		{
		 $accepted_date="";
		}
                            if($data['status']=='1')
		{
		     $status="Accepted";
		}
		else if($data['status']=='')
		{
		    $status="Rejected";
		}
		else
		{
		    $status="Pending";
		}
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['sid']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, number_format((float)$data['total_amount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['unit']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date($this->language->get('date_format_short'), strtotime($data['date_start'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, date($this->language->get('date_format_short'), strtotime($data['create_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $status);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data['a_firstname']." ".$data['a_lastname']);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $accepted_date);
        
        $row++;
    }
    //exit;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="bill_history'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
	}
        
         
       
        public function email_excel() {
           
                $this->load->model('report/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => date('Y-m-d'),
			'filter_date_end'	     => date('Y-m-d')
			
		);

		$data['orders'] = array();

		$results = $this->model_report_cash->getCash_report($filter_data);
                
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
        'Amount'
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
    
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bank_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        
            
        

        $row++;
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='cash_report_'.date('ymdhis').'.xls';
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

				$mail->Subject    = "Tagged orders Report";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				//to get the email of supplier
				
				$mail->AddAddress('vipin.kumar@aspltech.com', "vipin Chahal");
				
				

				$mail->AddAttachment(DIR_UPLOAD.$filename);
				
				if(!$mail->Send()) 
				{
				  echo "Mailer Error: " . $mail->ErrorInfo;
				} 
				else
				{ 
				  if(!unlink($file_to_attach))
				  {
					  echo ("Error deleting $file_to_attach");
				  }
				  else
				  {
					 echo ("Deleted $file_to_attach");
				  }
                                  
				}
        }
}