<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerRunnerCashReport extends Controller {
	public function index() {
		$this->load->language('report/cash_report');

		$this->document->setTitle('Cash Received Verification');

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
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = 0;
		}
                            /*
		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}

		
                           */
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
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                            /*
		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		
                           */
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Cash Received Verification',
			'href' => $this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$this->load->model('report/sale');
                            $this->load->model('runner/cash');
		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			//'filter_group'           => $filter_group,
			'filter_store' => $filter_store,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_runner_cash->getTotalCash_transation($filter_data);

		$data['orders'] = array();

		$results = $this->model_runner_cash->getCash_report($filter_data);
                $mcrypt=new MCrypt();
                            //echo $mcrypt->decrypt('7e126d01d0362588e0122c1eac729bdf9277ec1faa37dc20da4821ae901e23ff'); 
		foreach ($results as $result) { //print_r($result);
				if($result['bank_id']=="6")
                                {
                                    if($result['status']!="0")
                                    {
                                    $bank_name=$result['bank_name']."-".$result['mpesa_trans_id'];
                                    }
                                    else
                                    {
                                       $bank_name=$result['bank_name']; 
                                    }
                                }
                                else
                                {
                                       $bank_name=$result['bank_name']; 
                                }
			         $data['orders'][] = array(
				'SIID' => $result['transid'],
				'amount'   => $result['amount'],
				'store_id'      => $result['store_id'],
				'name'     => $result['name'],
                                		'date_added'=>  date($this->language->get('date_format_short'), strtotime($result['date_added'])),   
				'bank_name'      => $bank_name,
				'status'              => $result['status'],
				'tr_id'                => $mcrypt->encrypt($result['transid']),
				'bank_id'=>$result['bank_id']
			);
		}

		$data['heading_title'] = 'Cash Received Verification';
		
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
		$pagination->url = $this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
                            $this->load->model('setting/store');
		$data["stores"]=$this->model_setting_store->getStores();

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['filter_store'] = $filter_store;
                            $this->load->model('user/user');
  
                            $data["logged_user"] = $mcrypt->encrypt($logged_user_data = $this->user->getId());
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                            
		$this->response->setOutput($this->load->view('runner/cash_report.tpl', $data));
	}
	public function accept_cash_mpesa()
	{ //print_r($this->request->post);exit;
                $this->load->model('runner/cash');
                $this->load->model('user/user');
                $mcrypt=new MCrypt();
                if(($this->request->post['logged_user'])== ($mcrypt->encrypt($this->user->getId())))
		{

                  if (($this->request->post['bill_id'] != '') && ($this->request->post['logged_user'] != ''))
                  { 
		 
                   $tr_id=$mcrypt->decrypt($this->request->post['bill_id']); 
                   $logged_user=$mcrypt->decrypt($this->request->post['logged_user']); 
                   $amount=$mcrypt->decrypt($this->request->post['amount']); 
                   $m_pesa_tr_number=$this->request->post['m_pesa_tr_number']; 
                   
                   $this->model_runner_cash->accept_reject_cash_mpesa($tr_id,$logged_user,"1",$m_pesa_tr_number);
                   $this->model_runner_cash->add_to_trans_table($tr_id,$logged_user,"CR",$this->request->post['amount']);
                   $this->model_runner_cash->add_to_runner_credit($logged_user,$this->request->post['amount']);

                    $this->session->data['success'] = 'Transaction Accepted Successfully';   //  header('location: '.$_SERVER['HTTP_REFERER']);
                   header('location: '.$_SERVER['HTTP_REFERER']);//$this->response->redirect($this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
                }
                else
                { 
                    $this->session->data['success'] = 'Unknown User !';	
                    header('location: '.$_SERVER['HTTP_REFERER']);//$this->response->redirect($this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
	}
              public function accept_cash()
	{
                  $this->load->model('runner/cash');
             	 $this->load->model('user/user');
  $mcrypt=new MCrypt();
                           if(($this->request->get['logged_user'])== ($mcrypt->encrypt($logged_user_data = $this->user->getId())))
		{

                  if (($this->request->get['tr_id'] != '') && ($this->request->get['logged_user'] != ''))
                  { 
		 
                   $tr_id=$mcrypt->decrypt($this->request->get['tr_id']); 
	     $logged_user=$mcrypt->decrypt($this->request->get['logged_user']); 
                   $this->model_runner_cash->accept_reject_cash($tr_id,$logged_user,"1");
                   $this->model_runner_cash->add_to_trans_table($tr_id,$logged_user,"CR",$this->request->get['amount']);
	      $this->model_runner_cash->add_to_runner_credit($logged_user,$this->request->get['amount']);

                    $this->session->data['success'] = 'Transaction Accepted Successfully';   //  header('location: '.$_SERVER['HTTP_REFERER']);
                   header('location: '.$_SERVER['HTTP_REFERER']);//$this->response->redirect($this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
	}
	else
	{ 
              $this->session->data['success'] = 'Unknown User !';	
	 header('location: '.$_SERVER['HTTP_REFERER']);//$this->response->redirect($this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	}
	public function reject_cash()
	{
	 $this->load->model('user/user');
  $mcrypt=new MCrypt();
                           if($this->request->get['logged_user']== $mcrypt->encrypt($logged_user_data = $this->user->getId()))
		{
	$this->load->model('runner/cash');
             
                  if (($this->request->get['tr_id'] != '') && ($this->request->get['logged_user'] != ''))
                  { 
		 
                   $tr_id=$mcrypt->decrypt($this->request->get['tr_id']); 
	     $logged_user=$mcrypt->decrypt($this->request->get['logged_user']); 
                   $this->model_runner_cash->accept_reject_cash($tr_id,$logged_user,"2");
                   
                    $this->session->data['success'] = 'Transaction Rejected Successfully';
                   header('location: '.$_SERVER['HTTP_REFERER']);//$this->response->redirect($this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 }
	}
              else
	{
              $this->session->data['success'] = 'Unknown User !';	
	 header('location: '.$_SERVER['HTTP_REFERER']);//$this->response->redirect($this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	}

////////////////////////////////////////////////////////////////
public function cash_deposit() {
             
             $this->document->setTitle(' Cash deposit form');
             
             
             $this->load->model('runner/cash');
             
             if ($this->request->server['REQUEST_METHOD'] == 'POST')
             { 
                 $this->model_runner_cash->deposit_cash($this->request->post);
                
                 $this->session->data['success'] = 'Transaction submitted successfully';
                 $this->response->redirect($this->url->link('runner/cash_report/cash_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
             }
		
             $this->getForm();
         }
         protected function getForm() {
            	
                $this->load->model('user/user');
                $this->load->model('setting/store');
                
                $logged_user_data = $this->user->getId();
                
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['deposit_date'])) {
			$data['error_deposit_date'] = $this->error['deposit_date'];
		} else {
			$data['error_deposit_date'] = '';
		}

		

		$url = '';

		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Cash deposit',
			'href' => $this->url->link('runner/cash_report/cash_deposit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		

		$data['cancel'] = $this->url->link('runner/cash_report', 'token=' . $this->session->data['token'] . $url, 'SSL');
                
                
                $data['logged_user'] = $logged_user_data;
                if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
                $data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['action'] = $this->url->link('runner/cash_report/cash_deposit', 'token=' . $this->session->data['token'] . $url.'&SID='.$this->request->get["SID"], 'SSL');
		$this->response->setOutput($this->load->view('runner/deposit_form.tpl', $data));
	}





      ///////////////////////
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
                
                $this->load->model('report/cash');
        $data['orders'] = array();

        $filter_data = array(
            'filter_date_start'         => $filter_date_start,
            'filter_date_end'         => $filter_date_end,
            'filter_store'         => $filter_store
            
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
    if($data['bank_id']=="6")
                                {
                                    if($data['status']!="0")
                                    {
                                    $bank_name=$data['bank_name']."-".$data['mpesa_trans_id'];
                                    }
                                    else
                                    {
                                       $bank_name=$data['bank_name']; 
                                    }
                                }
                                else
                                {
                                       $bank_name=$data['bank_name']; 
                                }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $bank_name);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);
        
            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Cash_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
        
    }
        public function email_excel() {
           
                $this->load->model('report/cash');
        $data['orders'] = array();

        $filter_data = array(
            'filter_date_start'         => date('Y-m-d'),
            'filter_date_end'         => date('Y-m-d')
            
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

                $mail->Subject    = "Cash Report";

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
}