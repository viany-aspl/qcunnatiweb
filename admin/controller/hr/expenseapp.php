<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerHrExpenseapp extends Controller { 
	private $error = array();

	
        public function index()
        {
            $this->load->language('tag/order');

            $this->document->setTitle('Expense Bill List (by app) ');

	    $this->load->model('hr/hr');
            	    $this->load->model('user/user');
	    $user_info = $this->model_user_user->getUser($this->user->getId());
	

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
			$filter_store = null;
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
		
              
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
               
                
                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Expense Bill List  (by app)',
			'href' => $this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
			'filter_store'           => $filter_store,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                
                $order_total = $this->model_hr_hr->getTotalbillapp($filter_data);
	  $results = $this->model_hr_hr->getBillsapp($filter_data);
	  $data['expense_reasons'] = $this->model_hr_hr->getExpenseReasons();
$use=$user_info['user_id'];
 	  $mcrypt=new MCrypt();
                foreach ($results as $result) {  //print_r($result);
                    if(($result['descr']!='0') && ($result['descr']!=''))
	      {
		$reason=$result['reason']."  - ".$result['descr'];
	      }
                    else
	      {
		$reason=$result['reason'];
	      }
                    $data['bills'][] = array(
					'exepense_date'  => $result['exepense_date'],
					
					'store_name'       => $result['store_name'],
					'submitby'             => $result['firstname']." ".$result['lastname'],
					'amount'           => $result["amount"],
					'expensetype'       => $reason,
					'status'   => $result['status'],
					'SID'              => $result["SID"],
					'message'              => $result["message"],
					'tr_id'                => $mcrypt->encrypt($result['SID']),
					'users' =>$use,
					'employee_id' => $result['employee_id']
				);
                }
                
                $data['heading_title'] = 'Expense Bill List';
	  $this->load->model('setting/store');
                $data['stores'] = $this->model_setting_store->getStores();
		

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
                        unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
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
               
                $pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_date_submission'] = $filter_date_submission;
		$data['filter_store'] = $filter_store;
                $data['filter_unit'] = $filter_unit;
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['user_group_id']=$user_info['user_group_id'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['token'] = $this->session->data['token'];
		$this->response->setOutput($this->load->view('hr/bill_list_for_approve_by_app.tpl', $data));


        }

	public function getlist_download()
        {
            $this->load->language('hr/hr');

           

	    $this->load->model('hr/hr');
            
            if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m')."01";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
               
                           if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
                	$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
			'filter_store'           => $filter_store
		);
		$results = $this->model_hr_hr->getBillsapp($filter_data);
                
include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'TRANS ID',
        'Exepense date',
        'Store',
        'Submit by',
        'Amount',
        'Expense type',
        'Status',
        'Reason for Reject (If any)'
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
        if($data['status']=="0")
        {$status= "Pending";} 
        else if($data['status']=="1") 
        { $status= "Accepted"; } 
        else if($data['status']=="2") 
        { $status= "Rejected"; } 
	 if(($data['descr']!='0') && ($data['descr']!=''))
	      {
		$reason=$data['reason']."  - ".$data['descr'];
	      }
                    else
	      {
		$reason=$data['reason'];
	      }

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['SID']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['exepense_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['firstname']." ".$data['lastname']);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, number_format((float)$data['amount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $reason);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $status);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data["message"]);
        
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="expense_report_submit_by_app_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');

        }
        public function update_reason()
        {
	$mcrypt=new MCrypt();
            if ($this->request->post['bill_id2'] != '')
            {
	  $this->request->get['bill_id']=$mcrypt->decrypt($this->request->post['bill_id2']);
                $this->request->get['expense_reason']=$this->request->post['expense_reason'];
                $this->request->get['other_Message']=$this->request->post['other_Message'];
                $this->load->model('hr/hr');
                $updateBill = $this->model_hr_hr->update_reason($this->request->get['bill_id'],$this->request->get['expense_reason'],$this->request->get['other_Message']);//exit;
                if($updateBill)
                {
                  $this->session->data['success'] = 'Updated Successfully';
	    header('location: '.$_SERVER['HTTP_REFERER']);
                  //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                else 
                {
                   $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";
	     header('location: '.$_SERVER['HTTP_REFERER']);
                   //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 
                }
            }
            else
            {
                $this->session->data['error_warning'] = "You can't take this action.";
	  header('location: '.$_SERVER['HTTP_REFERER']);
                //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                
            }
        }
        public function accept()
        {
	$mcrypt=new MCrypt();
            if ($this->request->get['bill_id'] != '')
            {
                $this->request->get['bill_id']=$mcrypt->decrypt($this->request->get['bill_id']);
                $this->load->model('hr/hr');
                $updateBill = $this->model_hr_hr->acceptBillapp($this->request->get['bill_id']);
                if($updateBill)
                {
                  $this->session->data['success'] = 'Accepted Successfully';
	    header('location: '.$_SERVER['HTTP_REFERER']);
                  //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                else 
                {
                   $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";
	     header('location: '.$_SERVER['HTTP_REFERER']);
                   //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 
                }
            }
            else
            {
                $this->session->data['error_warning'] = "You can't take this action.";
	  header('location: '.$_SERVER['HTTP_REFERER']);
                //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                
            }
        }
        public function reject()
        {
	$mcrypt=new MCrypt();
            if ($this->request->post['bill_id'] != '')
            { 
	  $this->request->post['bill_id']=$mcrypt->decrypt($this->request->post['bill_id']);
                $this->load->model('hr/hr');
                $updateBill = $this->model_hr_hr->rejectBillapp($this->request->post['bill_id'],$this->request->post['reject_Message']);
                //$updateBill = 1;
                if($updateBill) 
                {
                
                  $this->session->data['success'] = 'Rejected Successfully';
	    header('location: '.$_SERVER['HTTP_REFERER']);
                  //$this->response->redirect($this->url->link('hr/expenseapprove', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                else 
                {
                   $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";
	     header('location: '.$_SERVER['HTTP_REFERER']);
                   //$this->response->redirect($this->url->link('hr/expenseapprove', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 
                }
            }
            else
            {
                 
                  $this->session->data['error_warning'] = "You can't take this action.";
	    header('location: '.$_SERVER['HTTP_REFERER']);
                  //$this->response->redirect($this->url->link('hr/expenseapprove', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                      
            }
        }



	public function reimbursement()
        {
            $this->load->language('tag/order');

            $this->document->setTitle('Reimbursement Bill List  ');

	    $this->load->model('hr/hr');
            
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
			$filter_store = null;
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
		
              
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
               
                
                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Reimbursement Bill List',
			'href' => $this->url->link('hr/expenseapp/reimbursement', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
			'filter_store'           => $filter_store,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                
                $order_total = $this->model_hr_hr->getTotalreimbursementapp($filter_data);
	  $results = $this->model_hr_hr->getreimbursementapp($filter_data);
 	  $mcrypt=new MCrypt();
                foreach ($results as $result) {  //print_r($result);
                    
                    $data['bills'][] = array(
					'exepense_date'  => $result['exepense_date'],
					'store_name'       => $result['store_name'],
					'submitby'             => $result['firstname']." ".$result['lastname'],
					'amount'           => $result["amount"],
					'expensetype'       => $result['reason'],
					'status'   => $result['status'],
					'bill_pic'   => $result['bill_pic'],
					'remarks'   => $result['remarks'],
					'SID'              => $result["SID"],
					'reimbursement_message'              => $result["reimbursement_message"],
					'tr_id'                => $mcrypt->encrypt($result['SID'])
				);
                }
                
                $data['heading_title'] = 'Reimbursement Bill List';
		$this->load->model('setting/store');
                $data['stores'] = $this->model_setting_store->getStores();
		

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
                        unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
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
               
                $pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('hr/expenseapp/reimbursement', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_date_submission'] = $filter_date_submission;
		$data['filter_store'] = $filter_store;
                $data['filter_unit'] = $filter_unit;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['token'] = $this->session->data['token'];
		$this->response->setOutput($this->load->view('hr/bill_list_for_reimbursement.tpl', $data));


        }

        public function accept_reimbursement()
        {
	$mcrypt=new MCrypt();
            if ($this->request->get['bill_id'] != '')
            {
                $this->request->get['bill_id']=$mcrypt->decrypt($this->request->get['bill_id']);
                $this->load->model('hr/hr');
                $updateBill = $this->model_hr_hr->accept_reimbursement($this->request->get['bill_id']);
                if($updateBill)
                {
                  $this->session->data['success'] = 'Accepted Successfully';
	    header('location: '.$_SERVER['HTTP_REFERER']);
                  //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                else 
                {
                   $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";
	     header('location: '.$_SERVER['HTTP_REFERER']);
                   //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 
                }
            }
            else
            {
                $this->session->data['error_warning'] = "You can't take this action.";
	  header('location: '.$_SERVER['HTTP_REFERER']);
                //$this->response->redirect($this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                
            }
        }
        public function reject_reimbursement()
        {
	$mcrypt=new MCrypt();
            if ($this->request->post['bill_id'] != '')
            { 
	  $this->request->post['bill_id']=$mcrypt->decrypt($this->request->post['bill_id']);
                $this->load->model('hr/hr');
                $updateBill = $this->model_hr_hr->reject_reimbursement($this->request->post['bill_id'],$this->request->post['reject_Message']);
                //$updateBill = 1;
                if($updateBill) 
                {
                
                  $this->session->data['success'] = 'Rejected Successfully';
	    header('location: '.$_SERVER['HTTP_REFERER']);
                  //$this->response->redirect($this->url->link('hr/expenseapprove', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                else 
                {
                   $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";
	     header('location: '.$_SERVER['HTTP_REFERER']);
                   //$this->response->redirect($this->url->link('hr/expenseapprove', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 
                }
            }
            else
            {
                 
                  $this->session->data['error_warning'] = "You can't take this action.";
	    header('location: '.$_SERVER['HTTP_REFERER']);
                  //$this->response->redirect($this->url->link('hr/expenseapprove', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                      
            }
        }

public function getlist_download_reimbursement()
        {
            $this->load->language('hr/hr');

           

	    $this->load->model('hr/hr');
            
            if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m')."01";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
               
                           if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
                
		$results = $this->model_hr_hr->getreimbursementapp($filter_data);
                
include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'TRANS ID',
        'Exepense date',
        'Store',
        'Submit by',
        'Amount',
        'Expense type',
        'Remarks',
        'Status',
        'Reason for Reject (If any)'
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
        if($data['status']=="1")
        {$status= "Pending";} 
        else if($data['status']=="3") 
        { $status= "Accepted"; } 
        else if($data['status']=="4") 
        { $status= "Rejected"; } 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['SID']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['exepense_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['firstname']." ".$data['lastname']);
        //'remarks'   => $result['remarks'],
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, number_format((float)$data['amount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['reason']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['remarks']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $status);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $data["reimbursement_message"]);
        
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reimbursement_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');

        }


        private function sendReject_mail($email_id,$submission_date,$unit,$store_name,$date_start,$date_end,$amount,$user_name,$reject_Message)
        { 
            $mail             = new PHPMailer();

                $body = '<div class="header" style="">
                         <div class="logo" style="width: 100%;height: 40px !important;" >
                         <img src="../image/letterhead_text.png" style="height: 40px !important; width: 121px;margin-left: 39px;margin-top: 30px;" />
                         <img src="../image/letterhead_log.png" style="height: 55px !important; width: 121px;float: right;" />

                         </div>
                         <img src="../image/letterhead_topline.png" style="height: 10px; width: 105% !important;margin-left: -10px;" /> 

                          </div>';
                
                $body.='<div style="padding-left: 55px;">Dear '.ucfirst($user_name).',<br/><br/>Your submitted bill is rejected. Details are as following : <br/><br/>';
                $body.='<strong>Submission date : </strong>'.$submission_date.'<br/><br/>';
                $body.='<strong>Unit : </strong>'.$unit.'<br/><br/>';
                $body.='<strong>store_name : </strong>'.$store_name.'<br/><br/>';
                $body.='<strong>Date start : </strong>'.$date_start.'<br/><br/>';
                $body.='<strong>Date end : </strong>'.$date_end.'<br/><br/>';
                $body.='<strong>Amount : </strong>'.$amount.'<br/><br/>';
                $body.='<strong>Reason of reject : </strong>'.$reject_Message.'<br/><br/></div>';
                
                $body.='<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; width: 105% !important;margin-left: -10px;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="height: 90px; width: 100% !important;" /> </div>
                        </div>';
                $mail->IsSMTP();
                $mail->Host       = "mail.akshamaala.in";
                //$mail->SMTPDebug  = 1;                                       
                $mail->SMTPAuth   = false;                 
                $mail->SMTPSecure = "";                 
                $mail->Host       = "mail.akshamaala.in";      
                $mail->Port       = 25;                  
                $mail->Username   = "mis@akshamaala.in";  
                $mail->Password   = "mismis";            
                $mail->IsHTML(true);
                
                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Tagged Bill Rejected";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                if($email_id=="")
                {
                    $mail->AddAddress('omprakash@unnati.world', "Om prakash");
                }
                elseif ($email_id=="sugar") 
                {
                    $mail->AddAddress('omprakash@unnati.world', "Om prakash");
                }
                else 
                {
                    $mail->AddAddress($email_id, $user_name);
                }
                
                $mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddBCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");
                $mail->AddBCC('vipin.kumar@aspltech.com', "vipin");
                
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                    echo ("sent ");                
                }
                
        }
 public function adjudteexpense()
 {
 $this->load->model('hr/hr');
 if($this->request->get['store_id']!="")
 {
 $expense=$this->model_hr_hr->UpdateStoreExpense($this->request->get);
 $this->session->data['success']="Expense Adjust Sucessfully";
 }
 else
 {
 $this->session->data['error']="something went wrong";
 }
 }
}
