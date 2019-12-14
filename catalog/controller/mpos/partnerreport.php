<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
class ControllermposPartnerreport extends Controller{

   public function adminmodel($model) 
   {
      
      $admin_dir = DIR_SYSTEM;
      $admin_dir = str_replace('system/','admin/',$admin_dir);
      $file = $admin_dir . 'model/' . $model . '.php';      
      //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
      $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
      
      if (file_exists($file)) {
         include_once($file);
         
         $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
      } else {
         trigger_error('Error: Could not load model ' . $model . '!');
         exit();               
      }
   }
	public function partner_payment() 
	{
            $mcrypt=new MCrypt();                           
            $log=new Log("partner-report-".date('Y-m-d').".log");
	        $log->write('partner_payment called');		
            $log->write($this->request->get);
            $log->write($this->request->post);
			if (isset($this->request->post['start_date'])) 
			{
				$filter_date_start = $mcrypt->decrypt($this->request->post['start_date']); 		
			} 
			else
			{
				$filter_date_start = date('Y-m-d');  		
			} 
            if (isset($this->request->post['end_date'])) 
			{
				$filter_date_end = $mcrypt->decrypt($this->request->post['end_date']); 		
			} 
			else
			{
				$filter_date_end = date('Y-m-d');  		
			} 
            if (isset($this->request->post['store_id'])) 
			{
				$filter_stores_id = $mcrypt->decrypt($this->request->post['store_id']); 		
			} 
			else
			{
				$filter_stores_id = 0;  		
			}   
			if (isset($this->request->post['start'])) 
			{
				$start = $mcrypt->decrypt($this->request->post['start']);
			} 
			else 
			{
				$start = 0;
			}
			$filter_data = array(
					'filter_date_start'	     => $filter_date_start,
					'filter_date_end'	     => $filter_date_end,
					'filter_stores_id' => $filter_stores_id,
					'start'                  => $start,
					'limit'                  => 200
					);
            $this->adminmodel('report/storelazer');     
            $results = $this->model_report_storelazer->getTransaction_Franchise_payment_received($filter_data);
			foreach ($results as $result) 
			{ 
			
			$data['products'][] = array
				(
				'date'      => $mcrypt->encrypt($result['Date']),
				'payment_method'      => $mcrypt->encrypt(str_replace('Payment','',$result['Mode'])),
				'transaction_type'      => $mcrypt->encrypt(str_replace('Posting','',$result['transaction_type'])),
				'amount'      => $mcrypt->encrypt((number_format((float)$result['Deposite'], 2, '.', ''))), 
                'remarks'      => $mcrypt->encrypt($result['remarks']),
				'order_id'   => $mcrypt->encrypt($result['order_id'])
                );
			$log->write($data);
			$this->response->setOutput(json_encode($data));
		}	
                        
	}
	public function partner_billing() 
	{
            $mcrypt=new MCrypt();                           
            $log=new Log("partner-report-".date('Y-m-d').".log");
	        $log->write('partner_billing called');		
            $log->write($this->request->get);
            $log->write($this->request->post);
			if (isset($this->request->post['start_date'])) 
			{
				$filter_date_start = $mcrypt->decrypt($this->request->post['start_date']); 		
			} 
			else
			{
				$filter_date_start = '2017-09-30';//date('Y-m-d');  		
			} 
            if (isset($this->request->post['end_date'])) 
			{
				$filter_date_end = $mcrypt->decrypt($this->request->post['end_date']); 		
			} 
			else
			{
				$filter_date_end = date('Y-m-d');  		
			} 
            if (isset($this->request->post['store_id'])) 
			{
				$filter_stores_id = $mcrypt->decrypt($this->request->post['store_id']); 		
			} 
			else
			{
				$filter_stores_id = 26;//0;  		
			}   
			if (isset($this->request->post['start'])) 
			{
				$start = $mcrypt->decrypt($this->request->post['start']);
			} 
			else 
			{
				$start = 0;
			}
			$filter_data = array(
					'filter_date_start'	     => $filter_date_start,
					'filter_date_end'	     => $filter_date_end,
					'filter_stores_id' => $filter_stores_id,
					'start'                  => $start,
					'limit'                  => 200
					);
            $this->adminmodel('report/storelazer');     
            $results = $this->model_report_storelazer->getTransaction_Franchise_partner_billing($filter_data);
			foreach ($results as $result) 
			{
			
				$data['products'][] = array
				(
					'date'      => $mcrypt->encrypt($result['Date']),
					'payment_method'      => $mcrypt->encrypt($result['Mode']),
					'amount'       => $mcrypt->encrypt((number_format((float)$result['Withdrawals'], 2, '.', ''))),
					'remarks'      => $mcrypt->encrypt($result['remarks']),
					'order_id'   => $mcrypt->encrypt($result['order_id']),
					'paid_status'      => $mcrypt->encrypt($result['paid_status']),
					'product_name'      => $mcrypt->encrypt($result['product_name']),
					'p_qnty'      => $mcrypt->encrypt($result['p_qnty']),
					'p_price'      => $mcrypt->encrypt($result['p_price']),
					'p_amount'      => $mcrypt->encrypt($result['p_amount'])
                );
		
			}
			$log->write($data);
			$this->response->setOutput(json_encode($data));
			
                        
	}
	
	public function email_invoice()
    {
		$mcrypt=new MCrypt();  
        $log=new Log("partner-report-".date('Y-m-d').".log");
	    $log->write('email_invoice called');		
        $log->write($mcrypt->decrypt($this->request->post['order_id']));      
        $order_id = $mcrypt->decrypt($this->request->post['order_id']);
        $this->adminmodel('partner/purchase_order');
        $data['order_information'] = $this->model_partner_purchase_order->view_order_details_for_created_invoice($order_id); 
        $data['store_to_data']=$this->model_partner_purchase_order->get_to_store_data($data['order_information']['order_info']['store_to']);
        $data['created_po']=$data['order_information']['order_info']['po_invoice_n'];
        $data['order_id']=$order_id;

        $inv_number=$data['order_information']['order_info']['po_invoice_prefix']."/".$data['created_po'];

        //$this->response->setOutput($this->load->view('partner/order_invoice_print.tpl',$data));
        $store_to_data=explode('---',$data['store_to_data']);
        $store_user_email_id=$store_to_data[3];
        //$this->load->view('default/template/card/card_pin_generate.tpl', $data)   
        $html=$this->load->view('default/template/partner/order_invoice_print.tpl',$data);
		
		require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
        $mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
            
        $header = '<br/>
					<div class="header" style="margin-top: 20px;">
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
                        <div class="address">
							<img src="https://unnati.world/shop/image/letterhead_footertext.png" style="width: 120% !important;" /> 
						</div>'
                    . '</div>';

        $mpdf->setAutoBottomMargin = 'stretch';       	 
        $mpdf->SetHTMLFooter($footer);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->list_indent_first_level = 0;
		$mpdf->WriteHTML($html);
        $filename='Invoice_'.$inv_number.'.pdf';
        $filename=str_replace('/','_',$filename);
		$mpdf->Output(DIR_UPLOAD.$filename,'F');

		//echo 'here';
		$mail  = new PHPMailer();

		$body = "<p style='border: 1px solid silver;padding: 15px;'>
			Dear Sir,
			<br/><br/>
			Please find the invoice as per request. The details are as follows:-
			<br/>
			Party Name : ".$data['order_information']['products'][0]['store_name']."
			<br/>
			Invoice Number : ".$inv_number." 
			<br/><br/>
			This is computer generated invoice and does not need signature of stamp.
			<br/><br/>

			Please do not reply to this email.
			<br/><br/>
			<img src='https://unnati.world/shop/image/cache/no_image-45x45.png' />
			
			<br/><br/>
			Thanking you,
			<br/>
			Care Team
			<br/>
			Unnati
			
			<br/>
			<br/>
			<span  style='font-size:7.5pt'> Please consider the environment before printing this email. Ask yourself whether you really need a hard copy.</span>
		</p>";
                
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

        $mail->Subject    = "Invoice number - ".$inv_number;

        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);
                
        //to get the email of supplier
        //$mail->AddAddress($store_user_email_id, $store_user_email_id);
        //$mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
        $mail->AddAddress('vipin.kumar@aspltech.com', "Vipin");
		
		$mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");
		
		$mail->AddAttachment(DIR_UPLOAD.$filename);
		
        if(!$mail->Send())
		{
            $this->response->setOutput(json_encode("Mailer Error: " . $mail->ErrorInfo));
        }
        else
        {
            if(!unlink(DIR_UPLOAD.$filename))
            {
                $this->response->setOutput(json_encode(1));
            }
            else
            {
                $this->response->setOutput(json_encode(1));
            }
                                  
        }
              
                
    }
	public function download_invoice()
    {
		$mcrypt=new MCrypt();  
        $log=new Log("partner-report-".date('Y-m-d').".log");
	    $log->write('download_invoice called');		
        $log->write($mcrypt->decrypt($this->request->post['order_id']));      
        $order_id = $mcrypt->decrypt($this->request->post['order_id']);
        $this->adminmodel('partner/purchase_order');
        $data['order_information'] = $this->model_partner_purchase_order->view_order_details_for_created_invoice($order_id); 
        $data['store_to_data']=$this->model_partner_purchase_order->get_to_store_data($data['order_information']['order_info']['store_to']);
        $data['created_po']=$data['order_information']['order_info']['po_invoice_n'];
        $data['order_id']=$order_id;

        $inv_number=$data['order_information']['order_info']['po_invoice_prefix']."/".$data['created_po'];

        //$this->response->setOutput($this->load->view('partner/order_invoice_print.tpl',$data));
        $store_to_data=explode('---',$data['store_to_data']);
        $store_user_email_id=$store_to_data[3];
        //$this->load->view('default/template/card/card_pin_generate.tpl', $data)   
        $html=$this->load->view('default/template/partner/order_invoice_print.tpl',$data);
		
		require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
        $mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
            
        $header = '<br/>
					<div class="header" style="margin-top: 20px;">
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
                        <div class="address">
							<img src="https://unnati.world/shop/image/letterhead_footertext.png" style="width: 120% !important;" /> 
						</div>'
                    . '</div>';

        $mpdf->setAutoBottomMargin = 'stretch';       	 
        $mpdf->SetHTMLFooter($footer);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->list_indent_first_level = 0;
		$mpdf->WriteHTML($html);
        $filename='Invoice_'.$inv_number.'.pdf';
        $filename=str_replace('/','_',$filename);
		$log->write($filename);	
		
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment;filename="'.$filename.'"'); 
		header('Cache-Control: max-age=0');
		$mpdf->Output($filename,'D');

                
    }
	public function collection_report() 
	{
		$this->adminmodel('report/partner');
		
		$filter_data = array(
			'start'                  => 0,
			'limit'                  => 200
		);

		$results = $this->model_report_partner->getCollection_Report($filter_data);
			
		//SMS LIB
		$this->load->library('sms');
		$sms=new sms($this->registry);
		
		foreach ($results as $result) 
		{
			/*
			$data['products'][] = array(
				'name'      => $result['name'],
				'partner_name'      => $result['partner_name'],
				'partner_email'      => $result['partner_email'],
				'address'      => $result['address'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'creditlimit'      => number_format((float)$result['creditlimit'], 2, '.', ''),
				'currentcredit'      => number_format((float)$result['currentcredit'], 2, '.', ''),
                'mobile'      => $result['username'],
				'partner_telephone'      => $result['partner_telephone'],
				'store_id'=>$result['store_id']
				);
				*/
				if($result['currentcredit']!=0)
				{
					$result['currentcredit']=number_format((float)$result['currentcredit'], 2, '.', '')*(-1);
					//$sms->sendsms(8447882446,"23",$result); 
					$sms->sendsms($result['partner_telephone'],"2",$result);
					$this->generate_pdf($result['store_id'],$result['currentcredit'],$result['partner_email']);
				}
		}

		
		
	}
	
	private function generate_pdf($filter_stores_id,$currentcredit,$partner_email) 
	{
		
		//if($filter_stores_id==26)
		{
		//echo $partner_email;
		$filter_date_start = date('Y-m').'-01';
		$filter_date_end = date('Y-m-d');
		
		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;

        $this->adminmodel('report/storelazer');

		$data['products'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_stores_id' => $filter_stores_id
		);
		
		

		if($filter_stores_id!="")
		{
			if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
			{
		 		$ord_total = $this->model_report_storelazer->getTotalTransaction_Own($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Own($filter_data);
			}
			else
			{
                $ord_total = $this->model_report_storelazer->getTotalTransaction_Franchise($filter_data);
				$product_total=$ord_total['total'];
				$results = $this->model_report_storelazer->getTransaction_Franchise($filter_data);
			}
		
		}
		
		foreach ($results as $result) {
			
			$data['products'][] = array(
				'Date'      => $result['Date'],
				'Mode'      => $result['Mode'],
				'Deposite'      => number_format((float)$result['Deposite'], 2, '.', ''),
				'Withdrawals'       => number_format((float)$result['Withdrawals'], 2, '.', ''),
				'Credit_Balance'      => number_format((float)$result['Credit_Balance'], 2, '.', ''),
				 'Cash_Balance'      => number_format((float)$result['Cash_Balance'], 2, '.', ''),
                                                        'remarks'      => $result['remarks'],
				'order_id'   => $result['order_id'],
				
                            
                           
                            
                            'store_name'      => $result['store_name'],
                            'user_Name'      => $result['user_Name']
                            
                                
			);
		if($filter_stores_id!="")
		{
		$data['store_name']=$result['store_name'];
		}
		
		}
		if(empty($data['store_name']))
		{
			$data['store_name']=$this->model_report_storelazer->getStoreName($filter_data);
		}
		$data['store_address']=$this->model_report_storelazer->getStoreaddress($filter_data);
		$data['store_type']=$this->model_report_storelazer->getStoreType($filter_data);
		$data['store_type_id']=$this->model_report_storelazer->getStoreType_id($filter_data);
		$data['store_incharge']=$this->model_report_storelazer->getStoreInCharge($filter_data);
		$data['store_gstn']=$this->model_report_storelazer->getStoreGstn($filter_data);
		$data['closed_credit']=number_format((float)$ord_total['Credit_Balance'], 2, '.', '');
		$data['closed_balance']=number_format((float)$ord_total['Cash_Balance'], 2, '.', '');
		
		require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
        $mpdf = new mPDF('c','A4','','' , 0 , 0 , 25 , 10 , 5 , 7);
		if(($data['store_type_id']=="1") || ($data['store_type_id']=="2"))
		{
		 		$html=$this->load->view('report/partner/store_ledger_pdf_own.tpl', $data);
		}
		else
		{
               $html=$this->load->view('default/template/partner/store_ledger_pdf_franchise.tpl', $data);
		}
		//exit;

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
                  
                	$footer = '<div class="footer" style="margin-top: 40px;">
                        
                        		<img src="https://unnati.world/shop/image/letterhead_bottomline.png" style="height: 10px; width: 150% !important;" /> 
                        		<div class="address"><img src="https://unnati.world/shop/image/letterhead_footertext.png" style="width: 120% !important;" /> </div>'
                        		. '</div>';

                	$mpdf->setAutoBottomMargin = 'stretch';       	 
                	$mpdf->SetHTMLFooter($footer);
                    
                	$mpdf->SetDisplayMode('fullpage');
    
                	$mpdf->list_indent_first_level = 0;
    
                	$mpdf->WriteHTML($html);
                
                	$filename='statement_'.$filter_date_start.'-'.$filter_date_end.'.pdf';
                
                	
        $filename=str_replace('/','_',$filename);
		$mpdf->Output(DIR_UPLOAD.$filename,'F');

		//echo 'here';
		$mail  = new PHPMailer();

		$body = "<p style='border: 1px solid silver;padding: 15px;'>
			Dear ".$data['store_incharge'].",
			<br/><br/>
			Your Current Outstanding is Rs. <b>".$currentcredit.".</b>
			<br/>
			Please find the Statement from <b>".date('d M Y',strtotime($data['filter_date_start']))." </b>- to - <b>".date('d M Y',strtotime($data['filter_date_end']))."</b>
			
			<br/><br/>
			This is computer generated statement and does not need signature or stamp.
			<br/><br/>

			Please do not reply to this email.
			<br/><br/>
			<img src='https://unnati.world/shop/image/cache/no_image-45x45.png' />
			<br/><br/>
			Thanking you,
			<br/>
			Care Team
			<br/>
			Unnati
			
			<br/>
			<br/>
			<span  style='font-size:7.5pt;color: #090;'> Please consider the environment before printing this email. Ask yourself whether you really need a hard copy.</span>
		</p>";
                
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

        $mail->Subject    =$data['store_name'] .'-Statement '.$filter_date_start.'-to-'.$filter_date_end;

        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);
                
        //to get the email of supplier
        //$mail->AddAddress($partner_email, $data['store_incharge']);
        //$mail->AddAddress('ashok.prasad@akshamaala.com', "Ashok Prasad");
		
		
        
		$mail->AddAddress('pragya.singh@aspltech.com', "Pragya Singh");
		$mail->AddCC('chetan.singh@akshamaala.com', "Chetan Singh");
		//$mail->AddCC('subhash.jha@unnati.world', "Subhash Jha"); 
		$mail->AddCC('vipin.kumar@aspltech.com', "Vipin");
		//$mail->AddCC('ravi.ranjan@unnati.world', "Ravi Ranjan");
		
		$mail->AddCC('amit.s@akshamaala.com', 'Amit Sinha');
		$mail->AddAttachment(DIR_UPLOAD.$filename);
		
        if(!$mail->Send())
		{
            $this->response->setOutput(json_encode("Mailer Error: " . $mail->ErrorInfo));
        }
        else
        {
            if(!unlink(DIR_UPLOAD.$filename))
            {
                $this->response->setOutput(json_encode(1));
            }
            else
            {
                $this->response->setOutput(json_encode(1));
            }
                                  
        }
		
			
		}
	}
	

	public function test()
	{
		$mcrypt=new MCrypt();  
		echo $mcrypt->decrypt('e6d1fddb49fe8ec10b97972c354f18ac'); 
	}
             
}
?>