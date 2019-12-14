<?php
class Controllermpossubuser extends Controller {


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

/*************************Report******************************************/
	public function CashSales()
	{

			$log=new Log("SubUserCashSales-".date('Y-m-d').".log");
			$mcrypt=new MCrypt();
			$log->write($this->request->post);
			$log->write($this->request->get);
		
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
			
			
				$this->load->model('account/subuser');
			    $jsons = $this->model_account_subuser->getCashSales($uid);
				$log->write($jsons);

				
				  
			foreach ($jsons as $ids) {		
			/*$json['products'][] = array(
					'Cash_Sales'       =>array("name"=>$mcrypt->encrypt('Cash Sales'),"value"=>$mcrypt->encrypt($ids['Cash_Sales'])),
					'Tagged_Sales'     =>array("name"=>$mcrypt->encrypt('Tagged Sales'),"value"=>$mcrypt->encrypt($ids['Tagged_Sales'])),
					'Cash_Tagged'    =>array("name"=>$mcrypt->encrypt('Cash Tagged'),"value"=>$mcrypt->encrypt($ids['Cash_Tagged'])),
					'Cash_Subsidy' => array("name"=>$mcrypt->encrypt('Cash Subsidy'),"value"=>$mcrypt->encrypt($ids['Cash_Subsidy'])),
					'Tagged_Subsidy' =>array("name"=>$mcrypt->encrypt('Tagged Subsidy'),"value"=>$mcrypt->encrypt($ids['Tagged_Subsidy'])),
								
													);*/
													
					$datas['products'][] = array(
					'name'=>$mcrypt->encrypt("Cash Sales"),
					'value'        =>$mcrypt->encrypt($this->currency->format( round($ids['Cash_Sales'],2)))         
					);
					
					$datas['products'][] = array(
					'name'=>$mcrypt->encrypt("Tagged Sales"),
					'value'        =>$mcrypt->encrypt($this->currency->format(round($ids['Tagged_Sales'],2)))           
					);
					
					$datas['products'][] = array(
					'name'=>$mcrypt->encrypt("Cash Subsidy"),
					'value'        =>$mcrypt->encrypt($this->currency->format(round($ids['Cash_Subsidy'],2)))         
					);
					
					
			}


				$this->response->setOutput(json_encode($datas));

	}
	
	
	public function StoreInchargeCashSummary()
	{

			$log=new Log("StoreInchargeCashSummary-".date('Y-m-d').".log");
			$mcrypt=new MCrypt();
			$log->write($this->request->post);
			$log->write($this->request->get);
		
			$log->write("Store ID");
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
			$log->write($sid);
		
				$this->load->model('account/subuser');
			    $jsons = $this->model_account_subuser->StoreInchargeSummary($sid);
				$log->write($jsons);

				
				  
			foreach ($jsons as $ids) {		
			$json['subusercashdtl'][] = array(
						
					'subusername'       =>$mcrypt->encrypt($ids['subusername']),
					'userid'       =>$mcrypt->encrypt($ids['user_id']),
					'Cash_Sales'       =>$mcrypt->encrypt($this->currency->format(number_format((float)$ids['Cash_Sales'],2,'.',''))),
					'Tagged_Sales'     =>$mcrypt->encrypt($this->currency->format(number_format((float)$ids['Tagged_Sales'],2,'.',''))),					
					'Cash_Subsidy' => $mcrypt->encrypt($this->currency->format(number_format((float)$ids['Cash_Subsidy'],2,'.',''))),
					'Cash_in_hand' => $mcrypt->encrypt($this->currency->format(number_format((float)$ids['cash_inhand'],2,'.',''))),
					
								
				);
													
					
					
			}

	$log->write($json);
				$this->response->setOutput(json_encode($json));

	}
	
	public function getallstoreincharge() {
		
        $mcrypt=new MCrypt();
        $log=new Log("getallstoreincharge-".date('Y-m-d').".log"); 

	    if (isset($this->request->post['filter_store'])) {
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
		} else 
		{
			$filter_store = '';
		}

		$log->write($this->request->post['filter_store']);
        $log->write($filter_store);
        $this->load->model('account/subuser');
		
		$filter_data = array(
			
                        'filter_user_group'  =>'11',
						'filter_store'  => $filter_store 
			
		);
		$user_id=$mcrypt->decrypt($this->request->post['username']);
		
		$cash = $this->model_account_subuser->getUserCash($user_id);
		$log->write("User Cash Amount");
		$log->write($cash);
		$data['cash']=$mcrypt->encrypt($this->currency->format($cash));
		$data['results'] = array();
		
		$results = $this->model_account_subuser->getAllStoreIncharge($filter_data);
		//echo "helloo";
        $log->write($results);
		foreach ($results as $result) { //print_r($result);
			    $data['results'][] = array(
				'user_id' => $mcrypt->encrypt($result['user_id']),
				'name'   => $mcrypt->encrypt($result['name']),
				
			);
		}


		$this->response->setOutput(json_encode($data));
	}
	
		public function getStoresubuserSummary() {
		
        $mcrypt=new MCrypt();
        $log=new Log("getStoresubuserSummary-".date('Y-m-d').".log"); 

	    if (isset($this->request->post['store_id'])) {
			$filter_store = $mcrypt->decrypt($this->request->post['store_id']); 
		} else 
		{
			//$filter_store = '8';
		}

		$log->write($this->request->post['store_id']);
        $log->write($filter_store);
        $this->load->model('account/subuser');
		
		$filter_data = array(
			
                        
						'filter_store'  => $filter_store 
			
		);
		
		$data['results'] = array();
		
		$results = $this->model_account_subuser->getStoresubuserSummary($filter_data);
		//echo "helloo";
        $log->write($results);
		foreach ($results as $result) { //print_r($result);
			    $data['results'][] = array(
				'user_id' => $mcrypt->encrypt($result['user_id']),
				'name'   => $mcrypt->encrypt($result['name']),
				'cash'   => $mcrypt->encrypt($result['cash']),
			);
		}


		$this->response->setOutput(json_encode($data));
	}
		public function getStoresubuserSummarydtl() {
		
        $mcrypt=new MCrypt();
        $log=new Log("getStoresubuserSummarydtl-".date('Y-m-d').".log"); 

	    if (isset($this->request->post['user_id'])) {
			$user_id = $mcrypt->decrypt($this->request->post['user_id']); 
		} else 
		{
			//$user_id = '242';
		}

		$log->write($this->request->post['user_id']);
      
        $this->load->model('account/subuser');
		
		$filter_data = array(
			
                       
						'user_id'  => $user_id 
			
		);
		
		$data['results'] = array();
		
		$results = $this->model_account_subuser->getStoresubuserSummarydtl($filter_data);
		//echo "helloo";
        $log->write($results);
		foreach ($results as $result) { //print_r($result);
			    $data['smdtl'][] = array(
			
				'dat'   => $mcrypt->encrypt($result['dat']),
				'cash'   => $mcrypt->encrypt($result['cash']),
			);
		}


		$this->response->setOutput(json_encode($data));
	}
	
	
	
	public function subuser_farmercash_otp()
    {
  
        $log=new Log("subuser_farmercash_otp".date('Y-m-d').".log"); 
		$log->write('subuser_farmercash_otp called');
		$log->write($this->request->post);
		$mcrypt=new MCrypt();
	   // $this->adminmodel('pos/pos');
		$this->load->model('account/subuser');		
			$data=array();
			
			$data['userid']=$mcrypt->decrypt($this->request->post['subuser_id']);  
			
			$data['store_incharged']=$mcrypt->decrypt($this->request->post['store_incharge_id']);  
			
			$data['amount']=$mcrypt->decrypt($this->request->post['amount']); 
			//$data['username']=$mcrypt->decrypt($this->request->post['store_incharge_name']);//$mcrypt->decrypt($this->request->post['ce_name']);  
			$data['storeid']=$mcrypt->decrypt($this->request->post['store_id']);   
			$data['storename']= $this->model_account_subuser->getstorename($data['storeid']);//$mcrypt->decrypt($this->request->post['storename']);  			
			$log->write($data); 
			//$log->write($userid);
			$ids = $this->model_account_subuser->getusermobile($data['store_incharged']);
			$log->write($ids);
		
			$mobile='7050101560';//$ids['username'];//'9911427348';// 
			$log->write($mobile);
			if(strlen($mobile)==9)
			{
				$mobile=$ids['email']; 
			}
			//SMS LIB
			$this->load->library('sms');	
			$sms=new sms($this->registry);
			$pin = rand(1000, 9999);
		 
			$data['otp']=$pin;
			$log->write('before sendimg to sms'); 
			$log->write($data); 
			
			
		   
			$query_return=$this->model_account_subuser->insert_subuser_farmercash_otp($data['userid'],$data['storeid'],$pin);
			
			$log->write($query_return);
			if($query_return>0)
			{
			$sms->sendsms($mobile,"22",$data);
			$this->response->setOutput('1');
			}
			else
			{
				$this->response->setOutput('0');
			}
    }
	
	public function subuser_farmercash_add()
{

		$log=new Log("subuser_farmercash_add".date('Y-m-d').".log");
		$log->write('subuser_farmercash_add called');
		$mcrypt=new MCrypt();
		$log->write($this->request->post);

            		$this->load->model('account/subuser');	    
            		$data=array();
			
			$otp=$mcrypt->decrypt($this->request->post['ttp']);
		
			$data['ce_id']=$mcrypt->decrypt($this->request->post['subuser_id']);
			$log->write("before called to model to check otp");
			$chekotp=$this->model_account_subuser->cheksubuserotp($otp,$data['ce_id']);
			$log->write("Otp Check Valid or not");
			$log->write($chekotp);
			if($chekotp->num_rows>0)
			{
			
			/*$data['bank_id']=$mcrypt->decrypt($this->request->post['bid']);
			$data['bank_name']=$mcrypt->decrypt($this->request->post['bname']);
			$data['amount']=$mcrypt->decrypt($this->request->post['bamt']);
			$data['user_id']=$mcrypt->decrypt($this->request->post['username']);
			$data['store_id']=$mcrypt->decrypt($this->request->post['store_id']);

			$data['user_id']=$mcrypt->decrypt($this->request->post['username']);
			$data['ce_name']=$mcrypt->decrypt($this->request->post['ce_name']);

 			$log->write($data); 
			if(!empty($data['amount']) && !empty($data['store_id'])){
                                             
                                             $current_cash= $this->model_account_customer->get_current_cash($data['user_id']);
                                             $log->write($current_cash);
                                           if(($current_cash<$data['amount'])  && ($data['bank_id']!="4"))
                                           {
                                               $log->write('Error: Amount can not be greater then Cash in Hand.');
                                               $json['success'] = 'Error: Amount can not be greater then Cash in Hand.';
                                           }
             			else
             			{
								$jsons = $this->model_account_customer->addbankTrans($data);
								$log->write($jsons);
								$this->adminmodel('runner/cash');
								$this->model_runner_cash->add_to_trans_table($jsons,$data['ce_id'],"CR",$data['amount']);
								$this->model_runner_cash->add_to_runner_credit($data['ce_id'],$data['amount']);*/
								$json['success'] = 'Success: Transaction added.';
								
			 }

			/*} 
			else{
				/if(empty($data['amount'])){
				$json['success'] = 'Error: Amount can not be zero.';
				}
				if(empty($data['store_id'])){
				$json['success'] = 'Error: You are not authorized.';
				}

				}  
		      
}*/
else
			 {
			  $json['error'] = 'OTP is not Matched';
			 }
			  $this->response->setOutput(json_encode($json));
}
	

public function getSubUser(){

	$mcrypt=new MCrypt();	
	$this->adminmodel('setting/setting');
	$log=new Log("getSubUser-".date('Y-m-d').".log");	
	$this->load->model('account/subuser');
	
	$data['store_id']=$mcrypt->decrypt($this->request->post['store_id']);     
	$jsons =$this->model_account_subuser->getSubUserlist($data);
			$log->write($jsons);
			foreach ($jsons as $ids) 
			{
				$log->write($ids);
				if(!empty($ids['user_id']))
				{
					$log->write("in");
					$json['crops'][] = array(
                        	'id' => $mcrypt->encrypt($ids['user_id']),
                        	'name'       =>$mcrypt->encrypt($ids['name']),
							'address'   =>	$mcrypt->encrypt($this->model_setting_setting->getSettingbykey('config','config_address',$data['store_id']))
                            ); 
							$log->write("end in");
				}
			}
	
	$log->write($json);
             $this->response->setOutput(json_encode($json));
}
	

	public function material_summary()
	{

			$log=new Log("meterial_summary-".date('Y-m-d').".log");
			$mcrypt=new MCrypt();
			$log->write($this->request->post);
			$log->write($this->request->get);
		
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$data['subuser_id']=$mcrypt->decrypt($this->request->post['subuser_id']);
			
			if (isset($this->request->post['start'])) {
			$data['start']  = $mcrypt->decrypt($this->request->post['start']); 
			} 
			else 
			{
			$data['start']  = 0;
			}
			$data['limit']  = '20';
			
			$this->load->model('account/subuser');
		    $jsons = $this->model_account_subuser->getmaterial_summary($uid,$data);
			$log->write($jsons);

				
				  
			foreach ($jsons as $result) {		
			$data['MaterialSummary'][] = array(
			'user_id'       => $mcrypt->encrypt($result['contractor_id']),
			'product_id'       => $mcrypt->encrypt($result['product_id']),
			'name'          => $mcrypt->encrypt($result['name']), 
			'material_issued'          => $mcrypt->encrypt($result['ms']),    
			'material_billed'         => $mcrypt->encrypt($result['billed']), 
			'balance_qty'          =>$mcrypt->encrypt($result['bal'])   
			);
			}


			$this->response->setOutput(json_encode($data));

	}
	
	public function material_detail()
	{

			$log=new Log("getmaterial_detail-".date('Y-m-d').".log");
			$mcrypt=new MCrypt();
			$log->write($this->request->post);
			$log->write($this->request->get);
		
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$data['subuserid']=$mcrypt->decrypt($this->request->post['user_id']);
			$data['productid']=$mcrypt->decrypt($this->request->post['product_id']);
			
			$log->write($data);
			
			if(!empty($data['productid']))
		   {	   
			$this->load->model('account/subuser');
			$results=$this->model_account_subuser->getmaterial_detail_con($data); 
				$log->write($results);  //print_r($results);
		   
		  
		  foreach ($results as $result) {
		   $data1['materialdetail'][] = array(
			'store_name'          => $mcrypt->encrypt($result['store_name']),
			'product_id'    =>$mcrypt->encrypt($result['product_id']),
			'product_name'  =>$mcrypt->encrypt($result['name']),
			'quantity'  =>$mcrypt->encrypt($result['quantity']),
			'dat'  =>$mcrypt->encrypt($result['dat']),
		   );
		  }
		   $log->write($data1);
		  $this->response->setOutput(json_encode($data1));
		   }
			else{
				 $this->response->setOutput('0');
			}
			$this->response->setOutput(json_encode($data1));

	}

	
	public function request_order_trans()
	{
		$log=new Log("request_order_trans-".date('Y-m-d').".log");
		$log->write("request_order_trans CALL");
		$log->write($this->request->post);
		 $mcrypt=new MCrypt();
		$data['products'] = ($_POST['product']);
		$data['prices'] = ($_POST['prices']);	
		$data['taxes'] = ($_POST['taxes']);	
		$data['options'] =($_POST['options']);
		$data['option_values'] =($_POST['option_values']);
		$data['quantity'] = ($_POST['quantity']);
		$data['supplier_id'] =($_POST['supplier_id']);//"--Supplier--";
		$data['stores'] = ($_POST['stores']);
		$data['recipient_number']=$mcrypt->decrypt($_POST['cmt']);
		$data['transport_id']=$mcrypt->decrypt($_POST['tid']);
		$data['tax']=$mcrypt->decrypt($_POST['ta']);
		$data['subtotal']=$mcrypt->decrypt($_POST['sub']);
		$this->session->data['user_id']=$mcrypt->decrypt($_POST['username']);
		//$this->load->library('user');
        //$this->user = new User($this->registry);
		//$log->write("rk 1");
		//$log->write( $this->request->post);//$_POST);
		$this->load->model('account/subuser');	
		$datatrans=array();
		
		$datatrans['user_id']=$mcrypt->decrypt($_POST['username']);
		
		$datatrans['otp']= rand(1000, 9999);
		
		
		foreach($data['stores'] as $store)
			{
				$log->write("in store");
				$productvalcheck=explode('_',$mcrypt->decrypt($store));
				$log->write($productvalcheck);
				$storecheck=$productvalcheck[0]."_".$productvalcheck[1];					
				$log->write($productvalcheck);
				$log->write($storecheck);
				$datatrans['uid']=$productvalcheck[0];		
			}
			foreach($data['supplier_id'] as $supplier)
			{
				$log->write("in supplier");
				$productvalcheck=explode('_',$mcrypt->decrypt($supplier));
				$log->write($productvalcheck);
				$storecheck=$productvalcheck[0]."_".$productvalcheck[1];					
				$log->write($productvalcheck);
				$log->write($storecheck);		                				
				$datatrans['store_id']=$productvalcheck[0];		
			}
			$products=array();
			$product_barred=$this->model_account_subuser->getproductbarred();
			foreach($data['products'] as $product)
			{
				$log->write("in product");
				$productvalcheck=explode('_',$mcrypt->decrypt($product));
				$log->write("after explode to $product");
				$log->write($productvalcheck);
				$storecheck=$productvalcheck[0]."_".$productvalcheck[1];
				$log->write("after storecheck");				
				
				$log->write($storecheck);
				$dataprd=array();
				//check for product barred
				foreach($product_barred as $pbarred)
				{
					if($pbarred['product_id']==$productvalcheck[0])
					{
						$json['success']="";
				   $json['error'] = ('Product barred.');
				   $this->response->setOutput(json_encode($json));
				   return;
					}
				}
				
				$dataprd['product_id']=$productvalcheck[0];
				$dataprd['product_name']=$productvalcheck[1];
				$dataprd['quantity']=$productvalcheck[4];
				$dataprd['price']=$productvalcheck[2];
				$dataprd['tax']=$productvalcheck[3];
				$products[]=$dataprd;								
			}
			$datatrans['products']=serialize($products);
			$log->write($datatrans['products']);
			
			//$price=trim(str_replace("Rs.","",$dataprd['price']));
			//$log->write("*********************gdfjkgnjkldfngjkln*****************************");
			//$log->write(($datatrans['quantity'])*($price)+($dataprd['tax']));
			//$log->write($datatrans['amounts']);
		    $datatrans['system_trans_id']= $mcrypt->decrypt($this->request->post['transid']);   
			$datatrans['imei']= $mcrypt->decrypt($this->request->post['imei']);   
			
			$query_return=$this->model_account_subuser->insert_subuser_receive_products_otp_trans($datatrans);
			
            			
			if(!empty($query_return))
			{
				/*************otp sms******************/
				$ids = $this->model_account_subuser->getusermobile($datatrans['uid']);
				$data['storename']= $this->model_account_subuser->getstorename($datatrans['store_id']);
				
				$log->write($ids);
			
				$mobile='7050101560';//$ids['username'];//'9911427348';// 
				$log->write($mobile);
				if(strlen($mobile)==9)
				{
					$mobile=$ids['email']; 
				}
				//SMS LIB
				$this->load->library('sms');	
				$sms=new sms($this->registry);			
			    $data=array();
				
				
				$data['otp']=$datatrans['otp'];
				$data['amount']=$datatrans['amounts'];
				$data['trans_id']=$query_return;
				
				
				
				$log->write('before sendimg to sms'); 
				$log->write($data); 
				$sms->sendsms($mobile,"22",$data);
				
				/*************otp sms end******************/
				
			    $json['order_id'] = $mcrypt->encrypt( $query_return);
				$json['success'] = $mcrypt->encrypt('Success: new order placed with ID: '.$query_return);						
			}
			else{
					$json['success']="";
				   $json['error'] = ('Error in submission');
			}			
			$this->response->setOutput(json_encode($json));
			
			
	}
	
	
	public function request_order()
	{
		$log=new Log("subuser_request_order-".date('Y-m-d').".log");
		$log->write("subuser_request_order call");
		 $mcrypt=new MCrypt();
		$data['products'] = ($_POST['product']);
		$data['prices'] = ($_POST['prices']);	
		$data['taxes'] = ($_POST['taxes']);	
		$data['options'] =($_POST['options']);
		$data['option_values'] =($_POST['option_values']);
		$data['quantity'] = ($_POST['quantity']);
		$data['supplier_id'] =($_POST['supplier_id']);//"--Supplier--";
		$data['stores'] = ($_POST['stores']);
		$data['recipient_number']=$mcrypt->decrypt($_POST['cmt']);
		$data['transport_id']=$mcrypt->decrypt($_POST['tid']);
		$data['tax']=$mcrypt->decrypt($_POST['ta']);
		$data['subtotal']=$mcrypt->decrypt($_POST['sub']);
		$this->session->data['user_id']=$mcrypt->decrypt($_POST['username']);
		$this->load->library('user');
        $this->user = new User($this->registry);

		
		//$log->write( $this->request->post);//$_POST);
		$this->load->model('account/subuser');
		
		$data['user_id']=$mcrypt->decrypt($_POST['username']);
		$data['otp']=$mcrypt->decrypt($this->request->post['stock_fm']);
		$data['last_order_id']=$mcrypt->decrypt($this->request->post['order_id']);
		
		$data['system_trans_id']= $mcrypt->decrypt($this->request->post['transid']);   
	    $data['imei']= $mcrypt->decrypt($this->request->post['imei']);   
		
		
		$log->write("otp from");
		$log->write($data['otp']);
		$log->write($data['last_order_id']);
		
		foreach($data['stores'] as $store)
			{
				$log->write("in store");
				$productvalcheck=explode('_',$mcrypt->decrypt($store));
				$log->write($productvalcheck);
				$storecheck=$productvalcheck[0]."_".$productvalcheck[1];					
				$log->write($productvalcheck);
				$log->write($storecheck);
				$data['id']=$productvalcheck[0];		
			}
			foreach($data['supplier_id'] as $supplier)
			{
				$log->write("in supplier");
				$productvalcheck=explode('_',$mcrypt->decrypt($supplier));
				$log->write($productvalcheck);
				$storecheck=$productvalcheck[0]."_".$productvalcheck[1];					
				$log->write($productvalcheck);
				$log->write($storecheck);		                				
				$data['store_id']=$productvalcheck[0];		
			}
			/*************check otp********************************/
			
			
			$chekotp=$this->model_account_subuser->cheksubuserotp($data);
			$log->write("Otp Check Valid or not");
			$log->write($chekotp);
			if(!empty($chekotp) && !empty($data['otp'])){
			if($chekotp==$data['otp'])
			{
								
		
			/*************check otp end********************************/
			
			foreach($data['products'] as $product)
			{
				$log->write("in product");
				$productvalcheck=explode('_',$mcrypt->decrypt($product));
				$log->write($productvalcheck);
				$storecheck=$productvalcheck[0]."_".$productvalcheck[1];					
				$log->write($productvalcheck);
				$log->write($storecheck);
				$data['product_id']=$productvalcheck[0];
				$data['product_name']=$productvalcheck[1];
				$data['quantity']=$productvalcheck[4];
				$data['price']=$productvalcheck[2];
				$data['tax']=$productvalcheck[3];
				$query_return=$this->model_account_subuser->insert_subuser_receive_products($data);	
				$query_ret=$this->model_account_subuser->update_oc_contractor_product_otp_trans_status($data);							
			}
			if(!empty($query_return))
			{
			    $json['order_id'] = $mcrypt->encrypt( $query_return);
				$json['success'] = $mcrypt->encrypt('Success: Product Transfered Successfully');						
			}
			else{
				$json['success']="";
				$json['error'] = ('Error in submission');
			}			
			//$this->response->setOutput(json_encode($json));
		}/////otp check end
		else
		{
				$json['success']="";
			    $json['error'] = 'OTP did not Match';
		}
			}else
		{
				$json['success']="";
			    $json['error'] = 'OTP did not Match';
		}
			    $this->response->setOutput(json_encode($json));

			
	}
	/**************Billed Material Report***************************/
	public function getBilledMaterial()
  {  
 
  $log=new Log("getBilledMaterial-".date('Y-m-d').".log");
  $log->write($_POST); 
  $log->write($_GET); 
  $mcrypt=new MCrypt();
  
  $contrator_id = $mcrypt->decrypt($_POST['contrator_id']);
  
    if(!empty($contrator_id)) 
	{		
 $this->load->model('account/subuser');
  $results=$this->model_account_subuser->getBilledMaterial($contrator_id); 
    $log->write($results);  //print_r($results);
	
  
  foreach ($results as $result) {
   $data['BilledMaterial'][] = array(
    'product_id'       => $mcrypt->encrypt($result['product_id']),
	'name'          => $mcrypt->encrypt($result['name']), 
    'material_issued'          => $mcrypt->encrypt($result['ms']),    
    'material_billed'         => $mcrypt->encrypt($result['billed']), 
    'balance_qty'          =>$mcrypt->encrypt($result['bal'])    
   );
  }
  $this->response->setOutput(json_encode($data));
	}
	else{
		 $this->response->setOutput('0');
	}
  }
  public function getBilledMaterialdtl()
  {  
 
  $log=new Log("getBilledMaterialdtl-".date('Y-m-d').".log");
  
  $log->write($_POST); 
  $log->write($_GET); 
  $mcrypt=new MCrypt();
  
  
 
 if(isset($this->request->post['product_id'])){
   $productid=$mcrypt->decrypt($this->request->post['product_id']);
  }
 else{
	 // $productid=72;
 }
 if(isset($this->request->post['contractor_id'])){
   $contrator_id=$mcrypt->decrypt($this->request->post['contractor_id']);
  }
 else{
	// $contrator_id=242;
 }
   if(!empty($productid))
   {	   
	$this->load->model('account/subuser');
	$results=$this->model_account_subuser->getBilledMaterialproductdtl($productid,$contrator_id); 
        $log->write($results);  //print_r($results);
   
  
  foreach ($results as $result) {
   $data['Billedproductdtl'][] = array(
    'quantity'       => $mcrypt->encrypt($result['quantity']),
    'dat'          => $mcrypt->encrypt($result['dat']),
	'transfer_by'  =>$mcrypt->encrypt($result['trans_by']),
   );
  }
   $log->write($data);
  $this->response->setOutput(json_encode($data));
   }
	else{
		 $this->response->setOutput('0');
	}
  }
/**********Billed Material Report*******************/

	public function document_upload_type()
	{

			$log=new Log("document_upload_type-".date('Y-m-d').".log");
			$mcrypt=new MCrypt();		
			
		
				$this->load->model('account/subuser');
			    $jsons = $this->model_account_subuser->getdocument_upload_type($sid);
				$log->write($jsons);
				  
			foreach ($jsons as $ids) {		
			$json['documenttype'][] = array(
						
					'id'       =>$mcrypt->encrypt($ids['sid']),
					'name'       =>$mcrypt->encrypt($ids['document_description']),
			
								
				);												
					
					
			}

			    $log->write($json);
				$this->response->setOutput(json_encode($json));

	}
    public function insert_document_upload_type()
	{

			$log=new Log("document_upload_type-".date('Y-m-d').".log");
			$log->write($_POST); 
			$log->write($_GET); 
			$mcrypt=new MCrypt();
  
            $data['store_id'] = $mcrypt->decrypt($_POST['store_id']);
			$data['user_id'] = $mcrypt->decrypt($_POST['user_id']);
			$data['remarks'] = $mcrypt->decrypt($_POST['remarks']);
			$data['document_id'] = $mcrypt->decrypt($_POST['document_id']);
			$data['imagename'] = $mcrypt->decrypt($_POST['imagename']);
		
			$this->load->model('account/subuser');
			$jsons = $this->model_account_subuser->insertdocument_upload_type($data);
			$log->write($jsons);
			
			$this->response->setOutput(json_encode($jsons));

	}


}