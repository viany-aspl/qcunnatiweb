<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerTagBillsubmission extends Controller {
	private $error = array();

	public function index() { //print_r($this->request->post);exit;
		$this->load->language('tag/order');

		$this->document->setTitle('Tagged Bill Submission');

                if ($this->request->server['REQUEST_METHOD'] == 'POST')
                { 
		//print_r($_FILES);
		//print_r($this->request->post);
                 $this->load->model('tag/order');    
                 $path = "../system/upload/bill/"; 
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
                   $new_file_name=$this->request->post['filter_unit'].date('dmy')."_".date('his').".".$file_ext;
                   $file_path=$path.$new_file_name;
                   $move= move_uploaded_file($file_tmp,$file_path);
                   if($move)
                   {
                      
                      $this->model_tag_order->billsubmmision($this->request->post,$new_file_name);
                      $this->session->data['success'] = 'Submitted Successfully';
                      $this->response->redirect($this->url->link('tag/billsubmission', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
                      
                   }
                   else ///////if some error in upload the file
                   {
                      $this->session->data['error_warning'] = 'Oops ! Some error occur, please try again.';
                      $this->response->redirect($this->url->link('tag/billsubmission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                      
                   }
                 }
                 else ///////if file extensions is not matched
                 {
                    $this->session->data['error_warning'] = 'Oops ! Please check format of the uploaded file, Only pdf,doc,docx,zip,rar,JPEG,JPG,PNG,jpg is allowed';
                    $this->response->redirect($this->url->link('tag/billsubmission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                       
                 }
              }///////// if file name is not empty end here
 	      else////////data is submit but no file chossen
	      { 
                      $this->model_tag_order->billsubmmision($this->request->post,'');
                      $this->session->data['success'] = 'Submitted Successfully';
                      $this->response->redirect($this->url->link('tag/billsubmission', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
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
                $logged_user_data = $this->user->getId();
                $this->load->model('setting/store');
                $data['stores'] = $this->model_setting_store->getStores();
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['logged_user'] = $logged_user_data;
                $this->response->setOutput($this->load->view('tag/bill_submission.tpl', $data));
                }

        }
        public function getlist()
        {
            $this->load->language('tag/order');

            $this->document->setTitle('Bill Submission List (View)');

	    $this->load->model('tag/order');
            
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
                if (isset($this->request->get['filter_date_submission'])) {
			$filter_date_submission = $this->request->get['filter_date_submission'];
		} else {
			$filter_date_submission = null;
		}
                if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
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
                
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
                if (isset($this->request->get['filter_date_submission'])) {
			$url .= '&filter_date_submission=' . $this->request->get['filter_date_submission'];
		}
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
		}
                
                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Bill submission list (View)',
			'href' => $this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                        'filter_date_submission' => $filter_date_submission,
                        'filter_store'           => $filter_store,
                        'filter_unit'            => $filter_unit,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
                
                $order_total = $this->model_tag_order->getTotalbill($filter_data);

		$results = $this->model_tag_order->getBills($filter_data);
                foreach ($results as $result) {  //print_r($result);
                    
                    $data['bills'][] = array(
					'submission_date'  => $result['submission_date'],
					'unit'             => $result['unit'],
					'date_start'       => $result['date_start'],
					'date_end'         => $result['date_end'],
					'amount'           => $result["amount"],
					'remarks'	   => $result['remarks'],
					'payment_status'   => $result['payment_status'],
					'uploded_file'     => $result['uploded_file'],
					'store'     	   => $result['store'],
                                        'store_name'       => $result['store_name'],
                                        'SID'              => $result["SID"],
                                        'logged_user'      => $result["logged_user"],   
			'submitted_by'      => $result["firstname"]." ".$result["lastname"]  
				);
                }
                
                $data['heading_title'] = $this->language->get('heading_title');
		$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('tag/billsubmission/getlist');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');

		$this->load->model('setting/store');
                $data['stores'] = $this->model_setting_store->getStores();
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_return_id'] = $this->language->get('entry_return_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_insert'] = $this->language->get('button_insert');
                            $data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');

		

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
                
                $url = '';
                
                if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
                if (isset($this->request->get['filter_date_submission'])) {
			$url .= '&filter_date_submission=' . $this->request->get['filter_date_submission'];
		}
                if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		}
                if (isset($this->request->get['filter_unit'])) {
			$url .= '&filter_unit=' . $this->request->get['filter_unit'];
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
		$data['filter_date_submission'] = $filter_date_submission;
		$data['filter_store'] = $filter_store;
                $data['filter_unit'] = $filter_unit;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['token'] = $this->session->data['token'];
		$this->response->setOutput($this->load->view('tag/bill_list_view.tpl', $data));


        }

	public function getlist_download()
        {
            $this->load->language('tag/order');

           

	    $this->load->model('tag/order');
            
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
                if (isset($this->request->get['filter_date_submission'])) {
			$filter_date_submission = $this->request->get['filter_date_submission'];
		} else {
			$filter_date_submission = null;
		}
                if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
                if (isset($this->request->get['filter_unit'])) {
			$filter_unit = $this->request->get['filter_unit'];
		} else {
			$filter_unit = null;
		}
                
               

		$data['orders'] = array();

		$filter_data = array(
			
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                        'filter_date_submission' => $filter_date_submission,
                        'filter_store'           => $filter_store,
                        'filter_unit'            => $filter_unit,
			'sort'                   => $sort,
			'order'                  => $order
		);
                
                $order_total = $this->model_tag_order->getTotalbill($filter_data);

		$results = $this->model_tag_order->getBills($filter_data);
                foreach ($results as $result) {  //print_r($result);
                    
                    $data['bills'][] = array(
					'submission_date'  => $result['submission_date'],
					'unit'             => $result['unit'],
					'date_start'       => $result['date_start'],
					'date_end'         => $result['date_end'],
					'amount'           => $result["amount"],
					'remarks'	   => $result['remarks'],
					'payment_status'   => $result['payment_status'],
					'uploded_file'     => $result['uploded_file'],
					'store'     	   => $result['store'],
                                        'store_name'       => $result['store_name'],
                                        'SID'              => $result["SID"],
                                        'logged_user'      => $result["logged_user"],   
			'submitted_by'      => $result["firstname"]." ".$result["lastname"]  
				);
                }
                
               
include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Submission date',
        'Unit',
        'Store',
        'Submitted by',
        'Date start',
        'Date end',
        'Amount',
        'Remarks',
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
					'submission_date'  => $result['submission_date'],
					'unit'             => $result['unit'],
					'date_start'       => $result['date_start'],
					'date_end'         => $result['date_end'],
					'amount'           => $result["amount"],
					'remarks'	   => $result['remarks'],
					'payment_status'   => $result['payment_status'],
					'uploded_file'     => $result['uploded_file'],
					'store'     	   => $result['store'],
                                        'store_name'       => $result['store_name'],
                                        'SID'              => $result["SID"],
                                        'logged_user'      => $result["logged_user"],   
			'submitted_by'      => $result["firstname"]." ".$result["lastname"]  
				);
                }
    foreach($results as $data)
    {
        $col = 0;
        if($data['payment_status']=="0")
        {$status= "Pending";} 
        else if($data['payment_status']=="1") 
        { $status= "Accepted"; } 
        else if($data['payment_status']=="2") 
        { $status= "Rejected"; } 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['submission_date']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['unit']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, date('Y-m-d',strtotime($data['date_start'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date('Y-m-d',strtotime($data['date_end'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, number_format((float)$data['amount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $data['remarks']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $status);
        
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="bill_submission_report_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');

        }

        public function accept()
        {
            if ($this->request->get['bill_id'] != '')
            {
                $this->load->model('tag/order');
                $updateBill = $this->model_tag_order->acceptBill($this->request->get['bill_id']);
                if($updateBill)
                {
                $this->session->data['success'] = 'Accepted Successfully';
                $this->response->redirect($this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                else 
                {
                  $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";
                  $this->response->redirect($this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 
                }
            }
            else
            {
                $this->session->data['error_warning'] = "You can't take this action.";
                $this->response->redirect($this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                
            }
        }
        public function reject()
        {
            if ($this->request->post['bill_id'] != '')
            {
                $this->load->model('tag/order');
                $updateBill = $this->model_tag_order->rejectBill($this->request->post['bill_id'],$this->request->post['reject_Message']);
                //$updateBill = 1;
                if($updateBill)
                {
                $user_data=$this->model_tag_order->getEmailofUser($this->request->post['logged_user']);
                $user_email_id=$user_data["email"];
                $user_name=$user_data["firstname"]." ".$user_data["lastname"];
                
                $this->sendReject_mail($user_email_id,$this->request->post['submission_date'],$this->request->post['unit'],$this->request->post['store_name'],$this->request->post['date_start'],$this->request->post['date_end'],$this->request->post['amount'],$user_name,$this->request->post['reject_Message']);
                $this->session->data['success'] = 'Rejected Successfully';
                $this->response->redirect($this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                else 
                {
                  $this->session->data['error_warning'] = "Oops ! Some error occur.Please try again.";
                  $this->response->redirect($this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                 
                }
            }
            else
            {
                 
                 $this->session->data['error_warning'] = "You can't take this action.";
                 $this->response->redirect($this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                      
            }
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
}
