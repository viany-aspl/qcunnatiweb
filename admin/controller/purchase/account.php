<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
class ControllerPurchaseAccount extends Controller{
    
    
        public function confirm_order_by_account() {
                $this->load->language('report/Inventory_report');
		$data['column_left'] = $this->load->controller('common/column_left');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $user_group_id=$user_info['user_group_id'];
                
                
                
                $url = '';
              
                if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (!empty($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
                
                $order_total=$this->request->get['order_total'];
                $creditlimit=$this->request->get['creditlimit'];
                $currentcredit=$this->request->get['currentcredit'];
                if(($currentcredit+$order_total)>$creditlimit)
                {
                  if (isset($this->request->get['order_id'])) 
                  {
			$url .= '&order_id=' . $this->request->get['order_id'];
		  }
                  $this->session->data['warning'] = 'Order total is greater then Available credit Limit !';
                  $this->response->redirect($this->url->link('purchase/purchase_order/view_order_details', 'token=' . $this->session->data['token'] . $url, 'SSL'));  
                }
		else
                {
                $order_id = $this->request->get['order_id'];
                $from=$this->request->get['order_status_id'];
                $user_id=$this->request->get['user_id'];
                
                $this->load->model('purchase/purchase_order');
		$results = $this->model_purchase_purchase_order->confirm_order_by_account($order_id);
		$this->model_purchase_purchase_order->add_po_trans($order_id,$from,'7',$user_id);
                $this->session->data['success'] = 'Order is approved successfully';
               
                $this->response->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
        }
        public function cancel_order_by_account() {
                $this->load->language('report/Inventory_report');
		$data['column_left'] = $this->load->controller('common/column_left');
		$user_info = $this->model_user_user->getUser($this->user->getId());
                $user_group_id=$user_info['user_group_id'];
                
		$url = '';
              
                if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (!empty($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
                $order_id = $this->request->get['order_id'];
                $reject_Message=$this->request->get['reject_Message'];
                $user_id=$this->request->get['user_id'];
                $from=$this->request->get['order_status_id'];
                
                $this->load->model('purchase/purchase_order');
		$results = $this->model_purchase_purchase_order->cancel_order_by_account($order_id,$reject_Message,$user_id);
		$this->model_purchase_purchase_order->add_po_trans($order_id,$from,'6',$user_id);
                $this->session->data['success'] = 'Order is canceled successfully';
                
                $this->response->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
        public function send_mail($order_id,$suppliers_id)
        { //echo $order_id.",".$suppliers_id;
            $mcrypt=new MCrypt();
            $order_id=$mcrypt->encrypt($order_id);
            $suppliers_id=$mcrypt->encrypt($suppliers_id);
            
            $body = "<p>Akshamaala Solution Pvt. Ltd.</p>";
            $body.="<br/><br/>http://field.unnati.world/index.php?route=supplier/supplier&order_id=".$order_id."&supplier_id=".$suppliers_id;
            
            
            $mail             = new PHPMailer();

                
                
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

                $mail->Subject    = "Purchase request link";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
                
                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                //$mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                //$mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		//$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		$mail->AddAddress('vipin.kumar@aspltech.com', "Vipin Kumar");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");
		
                

                //$mail->AddAttachment(DIR_UPLOAD.$filename);
                
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                  
                     echo "sent";
                  
                                  
                }
                
        }
        /*-----------------------------Receive order function starts here------------------*/
	
	public function receive_order()
	{
                $this->load->language('report/Inventory_report');
		$this->document->setTitle("Purchase Order Confirm");
		$order_id = $this->request->get['order_id'];
		$data['order_id'] = $order_id;
		$data['column_left'] = $this->load->controller('common/column_left');
		/*$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');*/
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
                $user_info = $this->model_user_user->getUser($this->user->getId());
                $user_group_id=$user_info['user_group_id'];
                $data['user_id']=$user_info['user_id'];
		$url = '';
              
                if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (!empty($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			//echo $url;
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		$this->load->model('purchase/purchase_order');
		$data['order_information'] = $this->model_purchase_purchase_order->view_order_details_by_account($order_id);
		if($data['order_information']['order_info']['receive_bit']==1)
		{
			$data['receive_bit'] = $data['order_information']['order_info']['receive_bit'];
		}
		else
		{
			$data['ftime_bit'] = 1;
		}
                
		$data['action'] = $this->url->link('purchase/account/insert_receive_order', 'token=' . $this->session->data['token'] . $url, true);
		$data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
		$this->load->model('purchase/supplier');
		$data['suppliers'] = $this->model_purchase_supplier->get_total_suppliers();
		$this->response->setOutput($this->load->view('purchase/receive_order.tpl',$data));
	
	}
	
	/*-----------------------------Receive order function ends here-----------------*/
        /*-----------------------------insert receive order function starts here-------------------*/
	
	public function insert_receive_order()
	{
		$order_id = $this->request->get['order_id'];
		$received_quantities = $this->request->post['receive_quantity'];
		$suppliers_ids = $this->request->post['supplier'];
		$received_product_ids = $this->request->post['product_id'];
		
		$order_receive_date = $this->request->post['order_receive_date'];
		$prices = $this->request->post['price'];
		$rq = $this->request->post['remaining_quantity'];
		if(isset($this->request->post['disable_bit']))
		{
			$data['disable_bit'] = 1;
		}
                
		/*print_r($received_quantities);
		print_r($suppliers_ids);
		print_r($received_product_ids);
		print_r($order_receive_date);
		print_r($prices);
		exit;*/
		$received_order_info['received_quantities'] = $received_quantities;
		$received_order_info['received_product_ids'] = $received_product_ids;
		$received_order_info['suppliers_ids'] = $suppliers_ids;
		$received_order_info['order_receive_date'] = $order_receive_date;
		$received_order_info['prices'] = $prices;
                

		$received_order_info['rq'] = $rq;
		$url = '';
              
                if ($this->request->get['filter_id']!="") {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
                if ($this->request->get['filter_status']!="") {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
                if ($this->request->get['filter_date_start']!="") {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
                if ($this->request->get['filter_date_end']!="") {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if($order_id)
		{
			$url .= '&order_id=' . $order_id;
		}
                
		if((count($received_quantities) != count(array_filter($received_quantities))) || (count($prices) != count(array_filter($prices))) || $order_receive_date == '')
		{ 
			$_SESSION['empty_fields_error'] = 'Warning: Please check the form carefully for errors!';
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$data['breadcrumbs'][] = array(
			'text' => "Purchase Orders",
			'href' => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			
			$this->load->model('purchase/purchase_order');
			$data['order_information'] = $this->model_purchase_purchase_order->view_order_details($order_id);
			
			for($i =0; $i<count($data['order_information']['products']); $i++)
			{
				unset($data['order_information']['products'][$i]['quantities']);
				unset($data['order_information']['products'][$i]['prices']);
			}
			
			$start_loop = 0;
			$data['validation_bit'] = 1;
			
			for($i = 0; $i<count($received_product_ids); $i++)
			{
				for($j = $start_loop; $j<count($prices); $j++)
				{
					if($prices[$j] == 'next product')
					{
						$start_loop = $j + 1;
						break;
					}
					else
					{
						$data['order_information']['products'][$i]['quantities'][$j] = $received_quantities[$j];
						$data['order_information']['products'][$i]['suppliers'][$j] = $suppliers_ids[$j];
						$data['order_information']['products'][$i]['prices'][$j] = $prices[$j];
					}
				}
				
				$data['order_information']['products'][$i]['quantities'] = array_values($data['order_information']['products'][$i]['quantities']);
				$data['order_information']['products'][$i]['suppliers'] = array_values($data['order_information']['products'][$i]['suppliers']);
				$data['order_information']['products'][$i]['prices'] = array_values($data['order_information']['products'][$i]['prices']);
				$data['order_information']['products'][$i]['rq'] = $received_order_info['rq'][$i];
			}
			
			$data['order_id'] = $order_id;
			if($order_receive_date)
			{
				$data['order_information']['order_info']['receive_date'] =  $order_receive_date;
			}
			else
			{
				$data['order_information']['order_info']['receive_date'] =  '0000-00-00';
			}
			//echo $order_receive_date;
			$data['action'] = $this->url->link('purchase/account/insert_receive_order', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true);
			$this->load->model('purchase/supplier');
			$data['suppliers'] = $this->model_purchase_supplier->get_total_suppliers();
			$this->response->redirect($this->url->link('purchase/account/receive_order', 'token=' . $this->session->data['token'] . $url, true));
			$this->response->setOutput($this->load->view('purchase/receive_order.tpl',$data));
		}
		else
		{
                        
			if(isset($this->request->post['disable_bit']))
			{
				unset($received_order_info['suppliers_ids']);
			}
			$this->load->model('purchase/purchase_order');
                        //print_r($this->request->post);
                        $user_id=$this->request->post['user_id'];
                        $this->model_purchase_purchase_order->add_po_trans($order_id,'5','7',$user_id);
			$inserted = $this->model_purchase_purchase_order->insert_receive_order($received_order_info,$order_id);
			if($inserted)
			{
                                foreach($suppliers_ids as $suppliers_id)
                                {
                                     //echo $suppliers_id;
                                    if($suppliers_id!='next product')
                                    {
                                        $this->send_mail($order_id,$suppliers_id);
                                    }
                                }
				$this->session->data['success'] = 'Order received Successfully!!';
				$this->response->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, true));
			}
			else
			{
				$this->session->data['error'] = 'Sorry!! something went wrong, try again';
				$this->response->redirect($this->url->link('purchase/account/insert_receive_order', 'token=' . $this->session->data['token'] . $url, true));
			}
				
		}
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
}

?>