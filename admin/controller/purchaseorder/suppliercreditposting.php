<?php
	require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
	require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
	error_reporting(0);
	class ControllerPurchaseorderSuppliercreditposting extends Controller 
	{
		public function add($return_orders = array()) 
		{
			$url = '';
			
			$this->load->model('purchaseorder/suppliercreditposting'); 
                        $this->load->model('purchaseorder/purchase_order');
			$this->document->setTitle('Credit Posting-Supplier');
			$data['heading_title'] = 'Credit Posting-Supplier';
			$data['text_list'] = 'Credit Posting-Supplier';
			
			
			
			$data['button_filter'] = $this->language->get('button_filter');
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
                        
                        $data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();
                        
                        if ($this->request->server['REQUEST_METHOD'] == 'POST')
                        {
			//print_r($this->request);exit;
                     
                        $data['logged_user_data'] = $this->user->getId();
                        $insert_id=$this->model_purchaseorder_suppliercreditposting->insertCreditPosting($this->request->post,$data['logged_user_data']);
                        $path = "../system/upload/Supplier/"; 
                        
                        $file_extensions= array("pdf","doc","docx","zip","rar",'jpeg','jpg','png');
                 
                        $file_name = @$_FILES['snapshot']['name'];

                        $file_size =@$_FILES['snapshot']['size'];
                        $file_tmp =@$_FILES['snapshot']['tmp_name'];
                        $file_type=@$_FILES['snapshot']['type'];
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
                                    
                                }
                            $new_file_name=$this->request->post['supplier'].date('dmy')."_".date('his').".".$file_ext;
                            $file_path=$path.$new_file_name;
                            $move= move_uploaded_file($file_tmp,$file_path);
                            if($move)
                            {
                            
                                $this->model_purchaseorder_suppliercreditposting->update_snapshotfile($insert_id,$new_file_name);
                               
                            }
                      
                      
                      
                        }
                        else ///////if some error in upload the file
                        {
                            //$this->session->data['error_warning'] = 'Oops ! Some error occur, please try again.';
                            //$data=array('status'=>'failure','msg'=>'Oops ! Some error occur, please try again.');
                      
                        }
                    }
                    
                
                        //exit;
                        $this->session->data['success'] = 'Credit Posting done successfully';
                        $this->response->redirect($this->url->link('purchaseorder/suppliercreditposting/getlist', 'token=' . $this->session->data['token'] , 'SSL'));
                        }
                        
                        
                     
			
			$url ='';
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Credit Posting-Supplier',
				'href' => $this->url->link('purchaseorder/suppliercreditposting', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['cancel']=$this->url->link('purchaseorder/suppliercreditposting/getlist', 'token=' . $this->session->data['token'], 'SSL');
			$data['header'] = $this->load->controller('common/header');
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('purchaseorder/credit_posting_form.tpl', $data));
			
		}
        
public function payment_done() {
			$url = '';
			
			$this->load->model('purchaseorder/suppliercreditposting');
                        		$this->load->model('purchaseorder/purchase_order');
			
                        
                        if ($this->request->server['REQUEST_METHOD'] == 'POST')
                        {
			
                     
                        $data['logged_user_data'] = $this->user->getId();
		
                        $amount=$this->model_purchaseorder_suppliercreditposting->insertCreditPostingPaymentDone($this->request->post,$data['logged_user_data']);
		//print_r($this->request->post);
		//exit;
		if(!empty($amount))
		{
			$this->model_purchaseorder_purchase_order->adjustpayment($this->request->post['order_id'],$amount,'credit posting',$data['logged_user_data'] );	  
		}
		 
		$file_path=$this->create_pdf_for_payment($this->request->post['order_id']);
		$this->send_payment_email($file_path,'',$this->request->post['order_id']); 
		//exit;
                        $path = "../system/upload/Supplier/"; 
                        
                        $file_extensions= array("pdf","doc","docx","zip","rar",'jpeg','jpg','png');
                 
                        $file_name = @$_FILES['snapshot']['name'];

                        $file_size =@$_FILES['snapshot']['size'];
                        $file_tmp =@$_FILES['snapshot']['tmp_name'];
                        $file_type=@$_FILES['snapshot']['type'];
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
                                    
                                }
                            $new_file_name='supplier_payment_done_'.$this->request->post['order_id'].'_'.date('dmy')."_".date('his').".".$file_ext;
                            $file_path=$path.$new_file_name;
                            $move= move_uploaded_file($file_tmp,$file_path);
                            if($move)
                            {
                            
                                $this->model_purchaseorder_suppliercreditposting->update_snapshotfile($insert_id,$new_file_name);
                               
                            }
                      
                      
                      
                        }
                        else ///////if some error in upload the file
                        {
                            //$this->session->data['error_warning'] = 'Oops ! Some error occur, please try again.';
                            //$data=array('status'=>'failure','msg'=>'Oops ! Some error occur, please try again.');
                      
                        }
                    }
                        
                
                        //exit;
                        $this->session->data['success'] = 'Payment done successfully';
		$url = '';

		if (isset($this->request->post['filter_date_start2'])) {
			$url .= '&filter_date_start=' . $this->request->post['filter_date_start2'];
		}

		if (isset($this->request->post['filter_date_end2'])) {
			$url .= '&filter_date_end=' . $this->request->post['filter_date_end2'];
		}

		if (isset($this->request->post['filter_supplier2'])) {
			$url .= '&filter_supplier=' . $this->request->post['filter_supplier2'];
		}

                        $this->response->redirect($this->url->link("purchaseorder/purchase_order/purchase_payment").$url.'&token=' . $this->session->data['token']);
                        }
                        
                 }
	
        public function create_pdf_for_payment($order_id)
        {
            
             //$data['column_left'] = $this->load->controller('common/column_left');
            // $data['footer'] = $this->load->controller('common/footer');
             //$data['header'] = $this->load->controller('common/header');
               
             
             $this->load->model('purchaseorder/purchase_order');
             $data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice_b2b($order_id);

             $data['store_to_data']=$this->model_purchaseorder_purchase_order->get_to_store_data($data['order_information']['order_info']['store_id']);
             $data['created_po']=$data['order_information']['order_info']['po_invoice_n'];
             $data['invoice_id']=$order_id;
	
	$data['supplier_name']=$data['order_information']['order_info']['first_name'].' '.$data['order_information']['order_info']['last_name'];
	$data['supplier_address']=$data['order_information']['order_info']['ADDRESS'];
	$data['supplier_ac']=$data['order_information']['order_info']['ACC_ID'];
	$data['supplier_ifsc']=$data['order_information']['order_info']['IFSC_CODE'];
	$data['amount']=$this->request->post['paid_amount'];
	$data['tr_number']=$this->request->post['tr_number']; 

	$data['invoice_amount']= $data['order_information']['order_info']['invoice_amount'];
              $data['invoice_number']= $data['order_information']['order_info']['invoice_no'];
	$data['invoice_date']= $data['order_information']['order_info']['invoice_date'];
	$data['bank_name']=$this->request->post['payment_bank'];
	$data['payment_method']=$this->request->post['payment_method'];

             //print_r($data['order_information']['order_info']);
             //$this->response->setOutput($this->load->view('purchaseorder/purchase_order_payment_print.tpl',$data));
            //exit;

             $html=$this->load->view('purchaseorder/purchase_order_payment_print.tpl',$data);
             require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
             $mpdf = new mPDF('c','A3','','' , 0 , 0 , 25 , 10 , 5 , 7);
            
                $header = '<br/><div class="header" style="margin-top: 20px;">
        <div class="logo">
        <img style="float: right;margin-right: 40px;height: 30px;" src="https://unnati.world/shop/image/catalog/logo.png"  />
        </div>
        
        <img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;;" />
    </div>';
               
                $header = '<div class="header" style="">
                  
<div class="logo" style="width: 100%;" >
<div style="padding-left: 50px;">
<img src="https://unnati.world/shop/image/letterhead_text.png" style="height: 40px; width: 121px;margin-top: 20px;marging-left: 100px;" />
<img src="https://unnati.world/shop/image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />
</div>
                         </div>
<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; width: 120% !important;" />

</div>';
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->SetHTMLHeader($header, 'O', false);
                 
                $footer = '<div class="footer">
                       
                        <img src="https://unnati.world/shop/image/letterhead_bottomline.png" style="height: 10px; width: 150% !important;" />
                        <div class="address"><img src="https://unnati.world/shop/image/letterhead_footertext.png" style="width: 120% !important;" /> </div>'
                        . '</div>';

                $mpdf->setAutoBottomMargin = 'stretch';            
                $mpdf->SetHTMLFooter($footer);
                   
                $mpdf->SetDisplayMode('fullpage');
   
                $mpdf->list_indent_first_level = 0;
   
                $mpdf->WriteHTML($html);
				$supplier_name=str_replace('&','-',$data['supplier_name']);
				$supplier_name=str_replace(' ','-',$supplier_name);
				$supplier_name=str_replace('/','-',$supplier_name);
                $filename=DIR_UPLOAD.'Supplier/'.$supplier_name. '_po_payment_'.$order_id.'.pdf';
               
                $mpdf->Output($filename,'F');
                return $filename;
             
           
               
        }
        public function send_payment_email($file_path,$supplier_id,$order_id)
        {
			$this->load->model('purchaseorder/purchase_order');
			
             			$data['order_information'] = $this->model_purchaseorder_purchase_order->view_order_details_for_created_invoice_b2b($order_id);
			//print_r($data['order_information']['order_info']['invoice_no']);
			
			$dbdata=$this->model_purchaseorder_purchase_order->get_to_supplier_data($data['order_information']['order_info']['supplier_id']);
                                	$dbdata2=explode('---',$dbdata);
			//print_r($dbdata2);exit;
                                	$mail = new PHPMailer();

				 $body = "<p>Dear ".$dbdata2[1].",
					<br/><br/>
					We would like to inform you that the following payment has been released .
					
					<br/><br/>
					Invoice number  : ".$data['order_information']['order_info']['invoice_no']."
					<br/><br/>
					Amount : ".$this->request->post['paid_amount']."
					<br/><br/>
					Transaction Number : ".$this->request->post['tr_number']."
					<br/><br/>
					Thanking you for your support and we look forward towards your continued support.
					<br/><br/>
					<strong>
					With Warm Regards,
					<br/>
					Account & Billing
					</strong>
					
					
				</p>";
				
				$mail->IsSMTP(); 
				$mail->Host       = "mail.akshamaala.in"; 
														   
				$mail->SMTPAuth   = false;                 
				$mail->SMTPSecure = "";                 
				$mail->Host       = "mail.akshamaala.in";      
				$mail->Port       = 25;                  
				$mail->Username   = "mis@akshamaala.in";  
				$mail->Password   = "mismis";            

				$mail->SetFrom('accounts@unnati.world', 'Account');

				$mail->AddReplyTo('accounts@unnati.world', 'Account');

				$mail->Subject    = "Payment update Mail";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				//to get the email of supplier
                                		
                                		$address = $dbdata2[4];
                                		//$address = 'vipin.kumar@aspltech.com'; 
                               		 //echo $file_path;
				//exit;
				$mail->AddAddress($address, $dbdata2[1]);
				 
		
			if(!empty(CC_EMAIL)){
			$ccmail = CC_EMAIL;
			$ccmail = explode(',', $ccmail);
			foreach ($ccmail as $value) {
				if(!empty($value)){
				$mail->AddCC($value,$value);}
			}
		        
			}

			if(!empty(BCC_EMAIL)){
			$bccmail = BCC_EMAIL;
			$bccmail = explode(',', $bccmail);
			foreach ($bccmail as $value) {
				if(!empty($value)){
				$mail->AddBCC($value,$value);}
			}
		        
			} 
				$file_to_attach = $file_path;

				$mail->AddAttachment($file_to_attach);
				
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

public function getList() {
$this->load->language('report/product_purchased');

$this->document->setTitle("Credit Posting List");

$this->load->model('purchaseorder/suppliercreditposting');
if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$data['filter_date_start']=$filter_date_start = date('Y-m').'-01';
}

if (isset($this->request->get['filter_date_end'])) {
$data['filter_date_end']=$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['filter_supplier'])) {
$filter_supplier = $this->request->get['filter_supplier'];
} else {
$filter_supplier = 0;
}

$data['filter_date_start']=$filter_date_start;
$data['filter_date_end']=$filter_date_end;
$data['filter_supplier']=$filter_supplier;

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

if (isset($this->request->get['filter_supplier'])) {
$url .= '&filter_supplier=' . $this->request->get['filter_supplier'];
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
'text' => $this->language->get('heading_title'),
'href' => $this->url->link('purchaseorder/suppliercreditposting/getlist', 'token=' . $this->session->data['token'], 'SSL')
);
$data['cancel']=$this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');


$data['payout'] = array();

$filter_data = array(
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'filter_supplier' => $filter_supplier,
'start' => ($page - 1) * $this->config->get('config_limit_admin'),
'limit' => $this->config->get('config_limit_admin')
);

$product_total = $this->model_purchaseorder_suppliercreditposting->getTotalPosting($filter_data);

$results = $this->model_purchaseorder_suppliercreditposting->getPostingList($filter_data);

foreach ($results as $result) {
$data['payout'][] = array(
'amount' => $result['amount'],
'transaction_type' => $result['transaction_type'],
'create_date' => $result['create_date'],
'payment_method' => $result['payment_method'],
'name' => $result['name'],
'firstname' => $result['firstname'],
'lastname' => $result['lastname']

);
}

$data['heading_title'] = $this->language->get('heading_title');

$data['text_list'] = $this->language->get('text_list');
$data['text_no_results'] = $this->language->get('text_no_results');


$data['column_name'] = $this->language->get('column_name');
$data['column_model'] = $this->language->get('column_model');
$data['column_quantity'] = $this->language->get('column_quantity');
$data['column_total'] = $this->language->get('column_total');

$data['entry_date_start'] = $this->language->get('entry_date_start');
$data['entry_date_end'] = $this->language->get('entry_date_end');
$data['entry_status'] = $this->language->get('entry_status');


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

$data['button_filter'] = $this->language->get('button_filter');

$data['token'] = $this->session->data['token'];

$this->load->model('purchaseorder/purchase_order');
$data['suppliers'] = $this->model_purchaseorder_purchase_order->getSuppliers();

$pagination = new Pagination();
$pagination->total = $product_total; 
$pagination->page = $page;
$pagination->limit = $this->config->get('config_limit_admin');
$pagination->url = $this->url->link('model_purchaseorder_suppliercreditposting/getlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

$data['pagination'] = $pagination->render();
$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

$data['filter_date_start'] = $filter_date_start;
$data['filter_date_end'] = $filter_date_end;
$data['filter_supplier'] = $filter_supplier;

$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('purchaseorder/getlist.tpl', $data));
}
		
	}
?>