<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerExpenseExpense extends Controller {
	private $error = array();

	public function index() { //print_r($this->request->post);exit;
		$this->load->language('tag/order');

		$this->document->setTitle('Expense Bill Submission');
                $this->load->model('expense/expense'); 
                if ($this->request->server['REQUEST_METHOD'] == 'POST')
                { 
		//print_r($_FILES);
		//print_r($this->request->post);
                    
                 $path = "../system/upload/expensebill/"; 
                 $file_extensions= array("pdf","doc","docx","zip","rar",'jpeg','jpg','png');
                 
                 $file_name = @$_FILES['file']['name'];

                 $file_size =@$_FILES['file']['size'];
                 $file_tmp =@$_FILES['file']['tmp_name'];
                 $file_type=@$_FILES['file']['type'];
                 $arrrr=explode('.',$file_name); 
                 $exttt=end($arrrr);
                 $file_ext= strtolower($exttt);
                if($file_name!="")
	           {
                 if(in_array($file_ext, $file_extensions)) 
                 { 
                    
                  if(is_writable($path))
                   {
                    //echo "yes";exit;
                   }
                   else 
                   {
                      //echo "no";exit; 
                   }
                   $new_file_name=date('dmy')."_".date('his').".".$file_ext;
                   $file_path=$path.$new_file_name;
                   $move= move_uploaded_file($file_tmp,$file_path);
                   if($move)
                   {
                      
                      $this->model_expense_expense->billsubmmision($this->request->post,$new_file_name);
                      $this->session->data['success'] = 'Submitted Successfully';
                      $this->response->redirect($this->url->link('expense/expense', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
                      
                   }
                   else ///////if some error in upload the file
                   {
                      $this->session->data['error_warning'] = 'Oops ! Some error occur, please try again.';
                      $this->response->redirect($this->url->link('expense/expense', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                      
                   }
                 }
                 else ///////if file extensions is not matched
                 {
                    $this->session->data['error_warning'] = 'Oops ! Please check format of the uploaded file, Only pdf,doc,docx,zip,rar,JPEG,JPG,PNG,jpg is allowed';
                    $this->response->redirect($this->url->link('expense/expense', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                       
                 }
              }///////// if file name is not empty end here
 	      else////////data is submit but no file chossen
	      { 
                    
                      $this->session->data['error_warning'] = 'Oops ! Please  upload file.';
                      $this->response->redirect($this->url->link('expense/expense', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
	      }
                }
                else
                {
                if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 
                if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
                $data['token'] = $this->session->data['token'];
                $logged_user_data = $this->user->getId();
                $this->load->model('setting/store');
                $data['stores'] = $this->model_setting_store->getStores();
                $data['reasons'] = $this->model_expense_expense->getReasons();
                $data['header'] = $this->load->controller('common/header');
	  $data['column_left'] = $this->load->controller('common/column_left');
	  $data['footer'] = $this->load->controller('common/footer');
                $data['logged_user'] = $logged_user_data;
                $this->response->setOutput($this->load->view('expense/bill_submission.tpl', $data));
                }

        }
         public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = '';
			}

			$this->load->model('expense/expense');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_email' => $filter_email,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_expense_expense->getUsers($filter_data);
                                          //echo "here";
			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['user_id'],
					
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

        public function getlist()
        {
            $this->load->language('tag/order');

            $this->document->setTitle('Expense bill List (View)');

	    $this->load->model('expense/expense');
            
            if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}
              
                            if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
                            if (isset($this->request->get['filter_reason'])) {
			$filter_reason = $this->request->get['filter_reason'];
		} else {
			$filter_reason = null;
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
			'text' => 'Expense bill  list (View)',
			'href' => $this->url->link('expense/expense/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                                          'filter_store'           => $filter_store,
                                          'filter_reason'           => $filter_reason,
                                          'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                
                $order_total = $this->model_expense_expense->getTotalbill($filter_data);

	  $results = $this->model_expense_expense->getBills($filter_data);
                foreach ($results as $result) {  //print_r($result);
                    
                    $data['bills'][] = array(
					'center'  => $result['store_name'],
					'reason'             => $result['reason_txt'],
					'bill_pic'         => $result['bill_pic'],
					'amount'           => $result["amount"],
					'exepense_date'	   => date('d/m/Y',strtotime($result['exepense_date'])),
					'status'   => $result['status'],
					'create_time'     => date('d/m/Y',strtotime($result['create_time'])),
			                            'employee_name'      => $result["firstname"]." ".$result["lastname"]  
				);
                }
                
                            $data['heading_title'] = 'Expense bill List';
		$this->load->model('setting/store');
                            $data['stores'] = $this->model_setting_store->getStores();
                            $data['reasons'] = $this->model_expense_expense->getReasons();
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		

                
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
                           if (isset($this->request->get['filter_reason'])) {
			$url .= '&filter_reason=' . $this->request->get['filter_reason'];
		}
                
                
                            $pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['filter_store'] = $filter_store;
                            $data['filter_reason'] = $filter_reason;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                            $data['token'] = $this->session->data['token'];
		$this->response->setOutput($this->load->view('expense/bill_list_view.tpl', $data));


        }
public function getexpenses()
        {
            $this->load->language('tag/order');

            $this->document->setTitle('Expense bill List');

	    $this->load->model('expense/expense');
            
            if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}
              
                            if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
                            if (isset($this->request->get['filter_reason'])) {
			$filter_reason = $this->request->get['filter_reason'];
		} else {
			$filter_reason = null;
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
			'text' => 'Expense bill  list (View)',
			'href' => $this->url->link('expense/expense/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
                            if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 
                            if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                                          'filter_store'           => $filter_store,
                                          'filter_reason'           => $filter_reason,
                                          'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                $mcrypt=new MCrypt();
                $order_total = $this->model_expense_expense->getTotalbill($filter_data);
         
	  $results = $this->model_expense_expense->getBills($filter_data);
                foreach ($results as $result) {  //print_r($result);
                    
                    $data['bills'][] = array(
					'center'  => $result['store_name'],
                                                                      'sid'  => $mcrypt->encrypt($result['SID']),
					'reason'             => $result['reason_txt'],
					'bill_pic'         => $result['bill_pic'],
					'amount'           => $result["amount"],
					'exepense_date'	   => date('d/m/Y',strtotime($result['exepense_date'])),
					'status'   => $result['status'],
					'create_time'     => date('d/m/Y',strtotime($result['create_time'])),
			                            'employee_name'      => $result["firstname"]." ".$result["lastname"]  
				);
                }
                
                            $data['heading_title'] = 'Expense bill List';
		$this->load->model('setting/store');
                            $data['stores'] = $this->model_setting_store->getStores();
                            $data['reasons'] = $this->model_expense_expense->getReasons();
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		

                
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
                           if (isset($this->request->get['filter_reason'])) {
			$url .= '&filter_reason=' . $this->request->get['filter_reason'];
		}
                
                
                            $pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['filter_store'] = $filter_store;
                            $data['filter_reason'] = $filter_reason;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                            $data['token'] = $this->session->data['token'];
		$this->response->setOutput($this->load->view('expense/bill_list_view_action.tpl', $data));


        }
	public function getlist_download()
        {
            $this->load->language('tag/order');

           

	    $this->load->model('expense/expense');
            
            if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}
               
                if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
                if (isset($this->request->get['filter_reason'])) {
			$filter_reason = $this->request->get['filter_reason'];
		} else {
			$filter_reason = null;
		}
                
               

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                       
                        'filter_store'           => $filter_store,
                        'filter_reason'            => $filter_reason,
			'sort'                   => $sort,
			'order'                  => $order
		);
                
               

	  $results = $this->model_expense_expense->getBills($filter_data);
                
                
               
include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Center',
        'Reason',
        'Employee name',
        'Amount',
        'Exepense date',
        'Submit date',
        'Status',
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
foreach ($results as $result) {  //print_r($result);
                    
                    $data['bills'][] = array(
					'center'  => $result['store_name'],
					'reason'             => $result['reason_txt'],
					'bill_pic'         => $result['bill_pic'],
					'amount'           => $result["amount"],
					'exepense_date'	   => date('d/m/Y',strtotime($result['exepense_date'])),
					'status'   => $result['status'],
					'create_time'     => date('d/m/Y',strtotime($result['create_time'])),
			                            'employee_name'      => $result["firstname"]." ".$result["lastname"]  
				);
                }
   
    foreach($results as $data)
    {
        $col = 0;
        if($data['status']=="0")
        {$status= "Pending";} 
        else if($data['status']=="1") 
        { $status= "Accepted"; } 
        else if($data['status']=="2") 
        { $status= "Rejected"; } 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['reason_txt']);
      
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, number_format((float)$data['amount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date('Y-m-d',strtotime($data['exepense_date'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date('Y-m-d',strtotime($data['create_time'])));
       
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $status);
        
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="expense_bill_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');

        }

       
        public function accept_reject()
        {
            $logged_user_data = $this->user->getId();
            $mcrypt=new MCrypt();
            //$mcrypt->decrypt();
            if (($this->request->post['sid'] != '') && $logged_user_data!="")
            {   //print_r($this->request->post);exit;
                $this->load->model('expense/expense');
                $updateBill = $this->model_expense_expense->accept_rejectBill($mcrypt->decrypt($this->request->post['sid']),$this->request->post['Message'],$this->request->post['status'],$logged_user_data);
                //$updateBill = 1;
                if($updateBill)
                {
                if($this->request->post['status']=="1")
                {
                   $this->session->data['success'] = 'Approved Successfully';
                }
                else
                {
                $this->session->data['success'] = 'Rejected Successfully';
                }
                $this->response->redirect($this->url->link('expense/expense/getexpenses', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                else 
                {
                  $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";
                  $this->response->redirect($this->url->link('expense/expense/getexpenses', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 
                }
            }
            else
            {
                 
                 $this->session->data['error_warning'] = "You can't take this action.";
                 $this->response->redirect($this->url->link('expense/expense/getexpenses', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                      
            }
        }
        
}
