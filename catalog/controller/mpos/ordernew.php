<?php
class ControllermposOrder extends Controller {
    public function adminmodel($model) {
      
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
 private function coupon_dscl($data) {
       $log=new Log("order-coupon-dscl-".date('Y-m-d').".log");
       $this->request->post=$data;
       
       $this->adminmodel('pos/pos');
			$unitdata=$this->model_pos_pos->getunitidandcompanyid($this->request->post);
			$log->write($unitdata); 
			$this->request->post['unit_id']=$unitdata[0]['unit_id'];
			
			$this->adminmodel('pos/dscl');
			$log->write('before call to dscl model');
			$coupon_info=$this->model_pos_dscl->VerifyCoupon('VerifyCoupon', $this->request->post,true);
			$log->write('after call to dscl model');
			$log->write($coupon_info);
			 if (isset($this->request->post['coupon'])) { 
            $coupon = $this->request->post['coupon'];
		$this->session->data['coupon_store'] = $this->request->post['store_id'];
        } else {
            $coupon = '';
        }
        if (empty($this->request->post['coupon'])) 
        {
            $json['error'] = $this->language->get('error_empty');
            $log->write('coupon empty');
        } 
        elseif (!empty($coupon_info))
        {
            $this->session->data['coupon'] = $this->request->post['coupon'];

            $this->session->data['success'] = $this->language->get('text_success');
            $json['coupon_info'] = $coupon_info;
            //$json['redirect'] = $this->url->link('checkout/cart');
            $log->write('coupon success');
        } 
        else 
        {
            $json['error'] = $this->language->get('error_coupon');
            $log->write('any other error');
        }
       return $json;
   }

public function coupon() {
        $this->load->language('checkout/coupon');
        $log=new Log("order-coupon".date('Y-m-d').".log");
        $log->write($this->request->post);
        $log->write('check for coupon is called');
	
		unset($this->session->data['coupon']);
		unset($this->session->data['coupon_store']);

        $mcrypt=new MCrypt();
        $keys = array(
            'prd',
            'coupon',
	    'store_id'
        );
        foreach ($keys as $key)
        {
              $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
           
        }
	////////////////////////////
		
		$this->request->post['storeid']=$this->request->post['store_id'];
		if((isset($this->request->post['cid'])) && (!empty($this->request->post['cid'])))
		{
			$log->write('in if cid is set');
			
                        $json=$this->coupon_dscl($this->request->post);
                        $log->write($json);
                        $this->response->addHeader('Content-Type: application/json');
                        $this->response->setOutput(json_encode($json));
		}
		
		///////////////////////////////////////////////
	else
	{
        $prds=json_decode($this->request->post['prd'],true);
        //$log->write($prds);
        $log->write($this->request->post);
        foreach($prds as $prd)
        {
        //$log->write($prd['product_id']);
        $log->write($this->addToCart($prd['product_id'],$prd['product_quantity']));
        }
        $log->write('after add the product in cart');
        $json = array();

        $this->load->model('checkout/coupon');
        //$this->request->post['coupon']=2222;
        if (isset($this->request->post['coupon'])) {
            $coupon = $this->request->post['coupon'];
		$this->session->data['coupon_store'] = $this->request->post['store_id'];
        } else {
            $coupon = '';
        }

        $log->write('before call the model');

        $coupon_info = $this->model_checkout_coupon->getCoupon($coupon);
        $log->write('after get  data from model');
        //print_r($coupon_info);
        $log->write($coupon_info);
        if (empty($this->request->post['coupon'])) 
        {
            $json['error'] = $this->language->get('error_empty');
            $log->write('coupon empty');
        } 
        elseif (!empty($coupon_info))
        {
            $this->session->data['coupon'] = $this->request->post['coupon'];

            $this->session->data['success'] = $this->language->get('text_success');
            $json['coupon_info'] = $coupon_info;
            //$json['redirect'] = $this->url->link('checkout/cart');
            $log->write('coupon success');
        } 
        else 
        {
            $json['error'] = $this->language->get('error_coupon');
            $log->write('any other error');
        }

        $log->write($json);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
    }




	public function addOrder() {
		
	$log=new Log("order-".date('Y-m-d').".log");
	$log->write('addorder called');
	$log->write($this->request->post);
	$this-> adminmodel('pos/pos');
	$this-> adminmodel('setting/setting');
		$this-> adminmodel('unit/unit');
	$this-> load->model('checkout/order');
	$mcrypt=new MCrypt();
	$this->load->model('account/api');

	$currentstatus=$this->model_setting_setting->getBillingStatus('billing');	
	if(empty($currentstatus))
	{
		//store_id
		$log->write('Billing closed for all store');
		$json['error']="Billing is closed for sometime";
		$json['success'] = "-1";
		if(isset($this->request->post['lumpsum']))
		{
			$json['dscl_submission'] = "-1";
		}
		$this->response->setOutput(json_encode($json));	
		return;
	}
	//store based 	
	$currentstatus_store=$this->model_setting_setting->getBillingStatus($mcrypt->decrypt($this->request->post['store_id']));	
	if(!empty($currentstatus_store))
	{
		//store_id
		$log->write('Billing closed for store-'.$mcrypt->decrypt($this->request->post['store_id']));
		$json['error']="Billing is closed for sometime";
		$json['success'] = "-1";
		if(isset($this->request->post['lumpsum']))
		{
			$json['dscl_submission'] = "-1";
		}
		$this->response->setOutput(json_encode($json));	
		return;
	}

	
	$api_info = $this->model_account_api->UserAuthorization($mcrypt->decrypt($this->request->post['user_id']));
	if(empty($api_info))
	{
		$json['error']="User is not Authorized";
		$json['success'] = "-1";
		if(isset($this->request->post['lumpsum']))
		{
			$json['dscl_submission'] = "-1";
		}
		$this->response->setOutput(json_encode($json));	
		return;
	}
		 
	if(isset($this->request->post['transid']))
	{
		$order_istance=$this->model_pos_pos->check_order_instance($mcrypt->decrypt($this->request->post['transid']));
		$log->write("order_istance");
		$log->write($order_istance);
		if(!empty($order_istance))//&&(!empty($order_istance['order_id'])))
		{ 
			$get_bill=$order_istance;
			$invoice_no_instance='';
			$order_details=$this->model_pos_pos->getOrder($get_bill);
			//if($order_details['order_status_id']=='5')
			//{
			$invoice_no_instance=$order_details['invoice_prefix']."-".$order_details['invoice_no'];
			$log->write('order already placed for this instance '.$get_bill."-".$invoice_no_instance);
			$json['success'] = 'Success: new order placed with ID: '.$get_bill;
			$json['order_id'] = $get_bill;
			$json['invoice_no']=$invoice_no_instance;
			$gtax=$this->model_checkout_order->getgtax($get_bill);
			$json['gtax']= $mcrypt->encrypt(json_encode($gtax)); 
			$log->write($json);
			$this->response->setOutput(json_encode($json));	
			return;
			//}
		}
		$this->model_pos_pos->insert_order_instance($mcrypt->decrypt($this->request->post['transid']),$mcrypt->decrypt($this->request->post['store_id']));
	}
	$keys = array(
		'store_id',
		'payment_method',
		'customer_id',
		'affiliate_id',
		'user_id',
		'prddtl',
		'customer_mobile',
		'customer_mob',
		'amtcash',
		'subcash',
		'sub',
		'docs',
		'doc_number',
		'comment',
		'stock_fm',
        	'spray',
		'coupon',
		'prdsub'	
		);
	//susidy product
	 $unitdata=array();
 $prdsubs=json_decode($mcrypt->decrypt($this->request->post['prdsub']),true);
 if(!empty($prdsubs))
		   {
if((!empty($this->request->post['CARD_UNIT'])) && (!empty($this->request->post['CARD_COMPANY'])))
{
		$unitdata= $this->model_unit_unit->getUnitByComapany_UnitID($this->request->post['CARD_UNIT'],($this->request->post['CARD_COMPANY']) );
		$this->adminmodel('card/integration');
	                    $log->write('before call to GetGrowerCardMob');
		$this->adminmodel('pos/dscl');	
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["growercode"]);	
		$this->request->post["unit_id"]=$this->request->post["CARD_UNIT"];
		$log->write($this->request->post["grower_id"]);
		$log->write($this->request->post["unit_id"]);
		$grower_details=$this->{'model_pos_dscl'}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);		
		$log->write($grower_details);
	      if(empty($grower_details))
		{			
			$json['error']=" Grower detail not Found ";
			$json['success'] = "-1";
			$this->response->setOutput(json_encode($json));	
			return;	

		}
                    $this->request->post['fname']=$mcrypt->encrypt($grower_details['RYOT_NAME']);
                    $this->request->post['lname']=$mcrypt->encrypt($grower_details['FTH_HUS_NAME']);  
                    $this->request->post['vname']=$mcrypt->encrypt($grower_details['VNAME']);
                    $this->request->post['villageid']=$mcrypt->encrypt($grower_details['VILLAGE_CODE']);
					$this->request->post['otpu']='Cash';//$mcrypt->decrypt($this->request->post["coupon"]);
			$this->request->post["Card_Serial_Number"]='0'; 
			$this->request->post['subsidy_coupon']=$mcrypt->decrypt($this->request->post["coupon"]);

}
		   } 
$log->write($this->request->post['villageid']);
	
	//end product
	
	$log->write('before base64');
	////////for card start here///////
	$log->write($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UPN'])));
	//$unitdata=array();
	if(!empty($this->request->server['HTTP_UPN'])) 
	{
		$UPN=$mcrypt->decrypt(base64_decode($this->request->server['HTTP_UPN']));		
		$log->write($this->request->post["cdata"]);
		$this->request->post["grower_id"]=$mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCG']));
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCN']));
		$log->write($this->request->post["grower_id"]);
		$log->write($this->request->post["Card_Serial_Number"]);

		
		if(empty($this->request->post["grower_id"]))
		{
			$log->write('grower id is empty');
			
			/*if($mcrypt->decrypt($this->request->post['utype'])==11)
			{
				
				$retval['status']=$mcrypt->encrypt('0');
				
				$retval['message']=$mcrypt->encrypt('You are not authorized for billing through Card Serial Number');
				$log->write('You are not authorized for billing through Card Serial Number');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return; 
			}*/

			//$this->adminmodel('pos/dscl');
			//$cdatanew=$this->model_pos_dscl->GetGrowerId("GetGrowerId",$this->request->post,0);
			//$log->write($cdatanew);	
			$cdatanew=$this->model_unit_unit->getGrowerIdByCard($this->request->post["Card_Serial_Number"]);
			$log->write($cdatanew);
			if(empty($cdatanew))
			{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('No Record Found');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return;
			}
			$this->request->post["grower_id"]=$cdatanew['GROWER_ID'];
			$unitdata= $this->model_unit_unit->getUnitByID($cdatanew['UNIT_ID']); 
			$log->write($unitdata);
			$this->request->post["cdata"]->UN=$cdatanew['UNIT_ID'];
			
			$this->request->post['CARD_UNIT']= $cdatanew['UNIT_ID'];
			$this->request->post['unit_id']= $cdatanew['UNIT_ID']; 
			$this->request->post['CARD_GROWER_ID']=$cdatanew['GROWER_ID'];
			if(empty($cdatanew['COMPANY_ID']))
			{
				$cdatanew['COMPANY_ID']="1";
			}
			$this->request->post['CARD_COMPANY']=$cdatanew['COMPANY_ID'];
		}
		else{
					$this->request->post['CARD_UNIT']=$mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU']));
		$this->request->post['CARD_COMPANY']=substr($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCI'])),0,1);
			
			}
	
		$this->request->post["otp"]=$UPN;
		$this->request->post["TX"]="1";		
		$this->request->post['otpu']=$UPN;
		//$log->write($this->request->post);
		
		$log->write($this->request->post);
		//check for pin of system not authorized		
		/*$otpdata=	$this->model_card_integration->check_otp($this->request->post);
		$log->write($otpdata);
		if(!empty($otpdata)){
		if($otpdata['otp']!=$UPN){
		//mobile number not defined
		$json['error']="OTP number not matched";
		$json['success'] = "-1";
		$this->response->setOutput(json_encode($json));	
		return;
		}
		}
		else{
		
		if(empty($otpdata))
		{
		// pin check 
		
		}
		
		$json['error']="OTP number not found";
		$json['success'] = "-1";
		$this->response->setOutput(json_encode($json));	
		return;
		}*/
		//get unit data
		$log->write('before getunitbycompany_unitid');
		$this->adminmodel('unit/unit');
        $unitdata= $this->model_unit_unit->getUnitByComapany_UnitID($this->request->post['CARD_UNIT'],($this->request->post['CARD_COMPANY']) );
		$log->write($unitdata);
		//check card authentication
		if(!empty($unitdata['company_name']))
		{
			$company=strtolower($unitdata['company_name']);
			$this->adminmodel('pos/'.$company);
			$dataAuthentication = $this->{'model_pos_' . $company}->GetAuthentication('GetAuthentication',$this->request->post,0);
			$log->write('after dataauthentication');
			$log->write($dataAuthentication);
			//$dataAuthentication['AMOUNT']=400;
			if(!empty($dataAuthentication) && $dataAuthentication['CARD_STATUS']=='9')
			{
				//check pin 
				if($dataAuthentication['GROWER_ID'] !=$UPN)
				{
					$json['error']="Wrong PIN.";
					$json['success'] = "-1";
					$this->response->setOutput(json_encode($json));	
					return;
				}
									
				//check amount 
				if($dataAuthentication['AMOUNT']<=0)
				{
					//pay cash only
					$json['error']="Grower balance is zero.";
					$json['success'] = "-1";
					$this->response->setOutput(json_encode($json));	
					return;
				}
				else
				{
					//tagged data amtcash.
					///get grower details////
                    $this->adminmodel('card/integration');
                    $log->write('before call to get_grower_by_card');
                    $grower_details=$this->model_card_integration->get_grower_by_card($this->request->post["Card_Serial_Number"],$this->request->post["grower_id"]);
                    $log->write($grower_details);
                    $this->request->post['fname']=$mcrypt->encrypt($grower_details['GROWER_NAME']);
                    $this->request->post['lname']=$mcrypt->encrypt($grower_details['FTH_HUS_NAME']);
                    $this->request->post['vname']=$mcrypt->encrypt($grower_details['VILLAGE_NAME']);
                    $this->request->post['villageid']=$mcrypt->encrypt($grower_details['VILLAGE_ID']);
                    if($dataAuthentication['AMOUNT']<($mcrypt->decrypt($this->request->post['INVAMOUNT'])))
					{
						$this->request->post['TAGGEDRATIO']=$dataAuthentication['AMOUNT']/($mcrypt->decrypt($this->request->post['INVAMOUNT']));
					}
				}
			} 
			else
			{
				$json['error']="CARD in Valid";
				$json['success'] = "-1";
				$this->response->setOutput(json_encode($json));	
				return;
			}
											
		}
		else
		{		
			$json['error']="Unit not defined";
			$json['success'] = "-1";
			$this->response->setOutput(json_encode($json));	
			return;
		}
		
	}

		$log->write('after card end');
		/////////for card end here//////////////
		
        foreach ($keys as $key) 
        {
           	$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
        }
		//log to system table
        $this->load->model('checkout/order');
		$this->load->model('account/activity');
		$activity_data = array(
						'customer_id' => $mcrypt->decrypt($this->request->post['username']),
						'data'        => json_encode($this->request->post),
					);

		//$this->model_account_activity->addActivity('Order', $activity_data);
		$this->request->post['storeid']=$this->request->post['store_id'];
		$log->write("before companydata");
		$companydata=$this->model_pos_pos->getunitidandcompanyid($this->request->post); 
		$log->write($companydata);
        if(!empty($companydata))
        {
            $data['unitid']=$companydata[0]['unit_id'];
            $log->write($companydata);
            $company=strtolower($companydata[0]['company_name']);
            $log->write($company);
        }

		if($company!="bcml")
		{
			//check for mobile is set of not
			if(!isset($this->request->post['lumpsum']))
			{
				if(empty($this->request->post['customer_mob']))
				{	
					//mobile number not defined
					$json['error']="Mobile number not defined";
					$json['success'] = "-1";
					if(isset($this->request->post['lumpsum']))
					{
						$json['dscl_submission'] = "-1";
					}	
					$this->response->setOutput(json_encode($json));	
					return;
				}
			}

			//check for mobile is set of not
			if(!isset($this->request->post['lumpsum']))
			{
				if(empty($this->request->post['customer_mob']))
				{	
					//mobile number not defined
					$json['error']="Mobile number not defined";
					$json['success'] = "-1";
					if(isset($this->request->post['lumpsum']))
					{
						$json['dscl_submission'] = "-1";
					}

					$this->response->setOutput(json_encode($json));	
					return;
				}
			}
		}
///check ase order
 if(($this->request->post['payment_method']=='Cash') && ($this->request->post['comment']!=''))
 {
    try
	{
        $ase_data=$this->model_pos_pos->getaseorderstatus($this->request->post['comment']);
        if((!empty($ase_data)) && (count($ase_data)>0))
        {
			$get_bill=$ase_data["order_id"];
        	$order_details=$this->model_pos_pos->getOrder($get_bill);
			$invoice_no_instance=$order_details['invoice_prefix']."-".$order_details['invoice_no'];
			$json['invoice_no']=$invoice_no_instance;
			$json['orddate'] = $ase_data["date_added"];
			$log->write('order already placed with inv no-'.$get_bill.' for bill id-'.$this->request->post['comment']);
			$json['success'] = 'Success: new order placed with ID: '.$get_bill;
			$json['order_id'] = $get_bill;
			$gtax=$this->model_checkout_order->getgtax($get_bill);
			$json['gtax']= $mcrypt->encrypt(json_encode($gtax)); 
			$this->response->setOutput(json_encode($json));	
			return;
        }
        
        
	} 
	catch (Exception $e) 
	{}

 }

	try
	{
		if(($company!='isec') && ($this->request->post['payment_method']=='Subsidy'))
		{
			$this->request->post['comment']='';
			$log->write('payment_method is Subsidy and company is not isec so comment value will be NULL');
		}
	} 
	catch (Exception $e) {}

	//check old
	if(!isset($this->request->post['lumpsum']))
	{
		if(isset($this->request->post['comment'])&& (!empty($this->request->post['comment'])))
		{
			$log->write('come in the condition of the comment is not empty');
			$this->adminmodel('lead/orderleads');
			$get_bill=$this->model_lead_orderleads->getrequisition_to_bil($this->request->post['comment']);
			if(!empty($get_bill))
			{
				$log->write('order already placed with inv no-'.$get_bill.' for bill id-'.$this->request->post['comment']);
        		$order_details=$this->model_pos_pos->getOrder($get_bill);
				$invoice_no_instance=$order_details['invoice_prefix']."-".$order_details['invoice_no'];
				$json['invoice_no']=$invoice_no_instance;
				$json['orddate'] = date('Y-m-d h:i:s A');
				$json['success'] = 'Success: new order placed with ID: '.$get_bill;
				$json['order_id'] = $get_bill;
				$gtax=$this->model_checkout_order->getgtax($get_bill);
				$json['gtax']= $mcrypt->encrypt(json_encode($gtax)); 
				$this->response->setOutput(json_encode($json));	
				return;
			}
		}
	}
    $log->write($this->request->post);
    $prds=json_decode($this->request->post[prddtl],true);
	unset($this->session->data['user_id']);
    $this->session->data['user_id']=$this->request->post['user_id'] ;
    $customer_id =$this->request->post['customer_id'];
    //check customer
    if(isset($customer_id))
    {
        $log->write("user id in ".$this->request->post['customer_mob']);
        //check customer
        $customer_id=$this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"];
        $log->write("user id in t ".$customer_id);
        if(empty($customer_id))
        {
            $this->addcustomer($this->request->post['store_id']);
            $customer_id=$this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"];
            $log->write("user= ".$customer_id);
            $this->request->post['customer_id']=$customer_id;
        }

    }

		$data['store_id'] = $this->request->post['store_id'];
		$data['storeid'] = $this->request->post['store_id'];
        $this->config->set('config_store_id',$data['store_id']);
		$data['store_name'] = $this->config->get('config_name'); 
		//$data['store_url'] = $this->config->get('config_url');
        //check for product quantity
        $this->load->model('catalog/product');
        foreach($prds as $prd)
		{		
			$log->write($prd['product_id']);
            $log->write("quantity check");
			$product_info = $this->model_catalog_product->getProduct($prd['product_id']);
			$log->write($product_info['squantity']);
			if ($product_info) 
            {     
	/////////////reserved store quantity////////// 
	if( $product_info['squantity'] >0){
                if ($product_info['squantity'] < $prd['product_quantity']) 
                {
					$json['error']="".$prd['product_quantity']." quantity for ".$product_info['name']." not match with system";
	                				$json['success'] = "-1";
					/*
					if(isset($this->request->post['lumpsum']))
					{
						$json['dscl_submission'] = "-1";
					}
					*/
					$log->write($prd['product_quantity']." quantity for ".$product_info['name']." not match with system");
					$this->response->setOutput(json_encode($json));	
					return;
                } 
					if($mcrypt->decrypt($this->request->post['utype'])==36){
$log->write("sub user quantity check");
				$this->load->model('account/subuser');
				$results=$this->model_account_subuser->getBilledMaterialProductBased($this->request->post['user_id'],$prd['product_id']); 
				$log->write($results); 
				if(empty($results) && $results <= 0)
				{
					$log->write("in check if");
					//not save data
					$json['error']=$prd['product_quantity']." quantity for ".$product_info['name']." not match with system";
	                $json['success'] = "-1";
					$this->response->setOutput(json_encode($json));	
					return;
				}
				//check balance qunty
				 if ($results < $prd['product_quantity']) 
				 {
					 $log->write("in quantity check if");
					 $json['error']=$prd['product_quantity']." quantity for ".$product_info['name']." not match with system";
	                $json['success'] = "-1";
					$this->response->setOutput(json_encode($json));	
					return;
				 }
				
}

	}
	else
	{
					$json['error']="Stock is low";
	                				$json['success'] = "-1";															
					$log->write($prd['product_quantity']." quantity for ".$product_info['name']." not match with system");
					$this->response->setOutput(json_encode($json));	
					return;
	}
         	// End lpccoder mod
            }
            else
            {
				$json['error']="Product not found please contact admin";
	            $json['success'] = "-1";
				if(isset($this->request->post['lumpsum']))
				{
					$json['dscl_submission'] = "-1";
				}
				$this->response->setOutput(json_encode($json));	
				return;

            }


        }

//end qnty

//check fm qunty

            $this-> adminmodel('setting/store');

            if(isset($this->request->post["stock_fm"]) && $this->request->post["stock_fm"]!="" )
            {
                foreach($prds as $prd)
                {
                    $log->write("quantity check for contractor ".$this->request->post["stock_fm"]);
                    $product_info = $this->model_setting_store->getProduct($prd['product_id'],$this->request->post["stock_fm"],$data['store_id']);
                    $log->write($product_info);
                    if ($product_info) 
                    {         
                        if ($product_info['quantity'] < $prd['product_quantity']) 
                        {
			$json['error']="".$prd['product_quantity']." quantity for ".$product_info['name']." not match with contractor";
	                $json['success'] = "-1";
			if(isset($this->request->post['lumpsum']))
			{$json['dscl_submission'] = "-1";}
			$this->response->setOutput(json_encode($json));	
			return;
                        }         

                    }
                    else
                    {
                        $json['error']="".$prd['product_quantity']." quantity for ".$product_info['name']." not match with contractor";
	                $json['success'] = "-1";
			if(isset($this->request->post['lumpsum']))
			{$json['dscl_submission'] = "-1";}
			$this->response->setOutput(json_encode($json));	
			return;

                    }


                }		
            }
                //end fm
                $spray_array=array('27','345','330','209','350','72');
                if(($this->request->post['spray']=="spray") && !empty($this->request->post['spray']))
                {
                    foreach($prds as $prd)
                    {
                        $log->write($prd['product_id']);
                        if(!in_array($prd['product_id'],$spray_array))
                        {
                                $log->write($prd['product_id']."  is not spray product");
                                $json['error']=$prd['product_name']."  is not spray product";
                                $json['success'] = "-1";
				if(isset($this->request->post['lumpsum']))
				{$json['dscl_submission'] = "-1";}
                                $this->response->setOutput(json_encode($json));	
                                return;
                        }
                    }

                }
                //add data to cart
                foreach($prds as $prd)
                {
                    $log->write($prd['product_id']);
                    $log->write($this->addToCart($prd['product_id'],$prd['product_quantity']));
                }
			$log->write("after product submit");
			unset($this->session->data['shipping_method']);       
			///////////////////////////////////////////////////////////////////////////////////
			 $this->load->language('checkout/coupon');
                unset($this->session->data['coupon']);
                unset($this->session->data['coupon_store']);
			if((!isset($this->request->post['prdsub'])) && (!empty($this->request->post['prdsub'])))
			{
               
                //$this->coupon();
                if (isset($this->request->post['coupon']) && (!empty($this->request->post['coupon'])))
                {
                    $this->load->model('checkout/coupon');
                    //$this->request->post['coupon']=2222;
                    if (isset($this->request->post['coupon'])) 
                    {
                        $coupon = $this->request->post['coupon'];
                        $this->session->data['coupon_store'] = $this->request->post['store_id'];
                    } 
                    else 
                    {
                        $coupon = '';
                    }
					$coupon_info = $this->model_checkout_coupon->getCoupon($coupon);
					$log->write($coupon_info);
                    if (empty($this->request->post['coupon'])) 
                    {
                        $log->write($this->language->get('error_empty'));
                        $json['error'] = $this->language->get('error_empty');
                    } elseif ($coupon_info) 
                    {
                        $this->session->data['coupon'] = $this->request->post['coupon'];
                        $log->write($this->language->get('text_success'));
                        $this->session->data['success'] = $this->language->get('text_success');
                    }
                    else 
                    {
                        $log->write($this->language->get('error_coupon'));
                        $json['error'] = $this->language->get('error_coupon');
                        $json['success'] = "-1";
					if(isset($this->request->post['lumpsum']))
					{$json['dscl_submission'] = "-1";}
                        $this->response->setOutput(json_encode($json));   
                        return;
                    }
                }
			}
                ///////////////////////////////   
				
                $log->write("after product submit");
				unset($this->session->data['shipping_method']);                 
                $data = array();
				if(!empty($this->request->post['TAGGEDRATIO']))
				{
				$data['TAGGEDRATIO']=$this->request->post['TAGGEDRATIO'];
				}
				else
				{
					$data['TAGGEDRATIO']=1;
				}
				//card detail
				if(isset($this->request->post["grower_id"]))
				{
					$data["grower_id"]=$this->request->post["grower_id"];
				}
				if(isset($this->request->post["otpu"]))
				{
					$data["otpu"]=$this->request->post["otpu"];
				}
				if(isset($this->request->post['subsidy_coupon']))
				{
					$data["subsidy_coupon"]=$this->request->post['subsidy_coupon'];
				}		
				else
				{
					$data["subsidy_coupon"]='0';
				}			
				
				if(isset($this->request->post["Card_Serial_Number"]))
				{
				$data["Card_Serial_Number"]=$this->request->post["Card_Serial_Number"];
				}
				if(isset($this->request->post["CARD_UNIT"]))
				{
				$data["CARD_UNIT"]=$this->request->post["CARD_UNIT"];
				}    
				//$data["qrstr"]				
                //validation 
                $errors = '';                
                $payment_method = $this->request->post['payment_method'];
                $is_guest = $this->request->post['is_guest'];
                $customer_id =$this->request->post['customer_id'];
                $card_no = $this->request->post['card_no'];
                $data['comment'] = $this->request->post['comment'];
                
                if($is_guest=='false' && $customer_id==''){
                    $errors .= 'Select the customer.<br />';
                }
                
                if(($payment_method  == 'Card') && $card_no==''){
                    $errors .= 'Enter the card number.<br />';
                }
                
                if($errors != ''){                   
                    $data['errors'] = $errors;
                    $this->response->setOutput(json_encode($data));
                    return;
                }

		if(isset($this->request->post['stock_fm']))
		{
 			$data["stock_fm"]=$this->request->post['stock_fm'];
		}

		$data['store_id'] = $this->request->post['store_id'];
		
		$default_country_id = $this->config->get('config_country_id');
		$default_zone_id = $this->config->get('config_zone_id');
                $data['shipping_country_id'] = $default_country_id;
		$data['shipping_zone_id'] = $default_zone_id;
		$data['payment_country_id'] = $default_country_id;
		$data['payment_zone_id'] = $default_zone_id;
		$data['customer_id'] = 0;
		$data['customer_group_id'] = 1;
		$data['firstname'] = 'Walkin';
		$data['lastname'] = "Customer";
		$data['email'] = '';
		$data['telephone'] = $this->request->post['customer_mob'] ;
		$data['fax'] = '';               
		$data['payment_firstname'] = 'Walkin';
		$data['payment_lastname'] = "Customer";
		$data['payment_company'] = $this->request->post['spray'];
		$data['payment_company_id'] = '';
		$data['payment_tax_id'] = '';
		

		$data['payment_address_2'] = '';
		$data['payment_city'] = '';
		$data['payment_postcode'] = '';
		$data['payment_country_id'] = '';
		$data['payment_zone_id'] = '';
		$data['payment_method'] = $payment_method;
		$data['payment_code'] = 'in_store';
		$data['subsidy_cat_id']=0;

		if($payment_method=='Tagged')
		{
			if(isset($this->request->post['vname']))
			{
				$data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']) ;
			}
                        else if(isset($this->request->post['growercode']))
			{
				$data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']) ;
			}
			else
			{
				$data['payment_address_1'] = '';
			}

		}
                if($payment_method=='Tagged Cash')
		{
			if(isset($this->request->post['vname']))
			{
				$data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']) ;
			}
                        else if(isset($this->request->post['growercode']))
			{
				$data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']) ;
			}
			else
			{
				$data['payment_address_1'] = '';
			}

		}
		elseif($payment_method=='Subsidy')
		{
			if(isset($this->request->post['vname']))
			{
				$data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']) ;
			}
                        else if(isset($this->request->post['growercode']))
			{
				$data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']) ;
			}
			else
			{
				$data['payment_address_1'] = '';
			}
			if(!empty($this->request->post['catid']))
			{
			$data['subsidy_cat_id'] = $this->request->post['catid'];
			}
		}
		else
		{
            if(isset($this->request->post['vname']))
			{
				$data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']) ;
			}
            else if(isset($this->request->post['growercode']))
			{
				$data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']) ; 
			}
			else
			{
				$data['payment_address_1'] = '';
			}
				
			if(!empty($companydata))
			{
				$data['unitid']=$companydata[0]['unit_id'];
			}
			elseif($payment_method=='Subsidy')
			{
				$data['unitid']=$mcrypt->decrypt($this->request->post['unit']);

			}
			else
			{
				$data['unitid']='0';
			}
			if(isset($this->request->post['fm_code']))
			{
				$data['fm_code'] = $mcrypt->decrypt($this->request->post['fm_code']) ;
			}
            
			else
			{
				$data['fm_code'] = '';
			}
			
			
		}

if($payment_method=='Cash Subsidy')
		{
			$data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['growercode']) ;



			$this->request->post['grower_id']=$mcrypt->decrypt($this->request->post['growercode']) ;


			if(empty($data['shipping_firstname']))
			{

				$data['shipping_firstname']='99999';
			}



		}

		elseif($payment_method=='Cash')
		{
			$data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['growercode']) ;
                }
		
		elseif($payment_method=='Subsidy')
		{
			$data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['growercode']) ;

		}
		elseif($payment_method=='Tagged')
		{
			if(isset($this->request->post['cid']))
			{
			$data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['cid']) ;
			}
			else
			{
			$data['shipping_firstname'] = '';
			}

		}
		elseif($payment_method=='Tagged Cash')
		{
			if(isset($this->request->post['cid']))
			{
			$data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['cid']) ;
			}
			else
			{
			$data['shipping_firstname'] = '';
			}

		}
		else{
		$data['shipping_firstname'] = '';
		}
		$data['shipping_lastname'] = '';
		$data['shipping_company'] = '';
		$data['shipping_address_1'] = '';
		$data['shipping_address_2'] = '';
		$data['shipping_city'] = '';
		$data['shipping_postcode'] = '';
		$data['shipping_country_id'] = '';
		$data['shipping_zone_id'] = '';
		$data['shipping_method'] = 'Pickup From Store';
		$data['shipping_code'] = 'pickup.pickup';		
		$data['order_status_id'] = 5;
		$this->request->post['order_status_id']=5;

		if($payment_method=='Tagged')
		{
		$log->write("in tagged payment_method");
			$data['order_status_id'] = 1;
		$this->request->post['order_status_id']=1;
		}
		else if($payment_method=='Tagged Cash')
		{
		$log->write("in tagged cash payment_method");
			$data['order_status_id'] = 1;
		$this->request->post['order_status_id']=1;
		}
		else if($payment_method=='Tagged Subsidy')
		{
		$log->write("in Tagged Subsidy payment_method");
			$data['order_status_id'] = 1;
		$this->request->post['order_status_id']=1;
		}
		else if($payment_method=='Cash Subsidy')
		{
		$log->write("in Cash Subsidy payment_method");
			$data['order_status_id'] = 1;
		$this->request->post['order_status_id']=1;
		}
		else
		{		
		$data['order_status_id'] = 5;
		$this->request->post['order_status_id']=5;
		}
		$data['affiliate_id'] = isset( $this->request->post['affiliate_id'])? $this->request->post['affiliate_id']:0;
                $data['card_no'] = $card_no;
		$data['user_id'] = $this->user->getId();
                $log->write("user id");
                $log->write($customer_id);
		$is_guest='false';
		if(isset($customer_id))
		{
                    $log->write("user id in ",$this->request->post['customer_mob']);
                    $customer_id=$this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"];//$this->session->data['cid'];
                    $log->write("user= ".$customer_id);

		}
                //override for customer 
                if($is_guest=='false'){
                    
                $log->write("false");
                    $customer = $this->model_pos_pos->getCustomer($customer_id);
                   // $this->session->data['customer_id']=$customer_id;
                    $data['customer_id'] = $customer_id;
                    $data['customer_group_id'] = $customer['customer_group_id'];
                    
					
					if(!empty($this->request->post['fname']))
					{
					$data['firstname']=$mcrypt->decrypt($this->request->post['fname']).'-'.$mcrypt->decrypt($this->request->post['lname']);
					$data['payment_firstname'] = $mcrypt->decrypt($this->request->post['fname']).'-'.$mcrypt->decrypt($this->request->post['lname']);
					}
					else if(!empty($this->request->post['growername'])) 
					{
					$data['firstname']=$mcrypt->decrypt($this->request->post['growername']);
					$data['payment_firstname'] = $mcrypt->decrypt($this->request->post['growername']);
					}
           
                    $data['lastname'] = $customer['lastname'];
                    $data['email'] = $customer['email'];
                    $data['telephone'] = $customer['telephone'];
                    $data['fax'] = $customer['fax'];

                    
                    $data['payment_lastname'] = $customer['lastname'];
                }				
                $log->write("all data added in the array just  after getting the customer info is : ");
                $log->write($data);
                //get product list 
                $this->load->library('customer');
                $this->customer = new Customer($this->registry);
                
                $this->load->library('tax');//
                $this->tax = new Tax($this->registry);
            
                $this->load->library('pos_cart');//
                $this->cart = new Pos_cart($this->registry);
                
		//SMS LIB
		$this->load->library('sms');	
                $data['order_product'] = array();
                
                foreach ($this->cart->getProducts() as $product) {
			
			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['option_value'];	
				} else {
					$filename = $this->encryption->decrypt($option['option_value']);

					$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
				}				

				$option_data[] = array(								   
					'product_option_id'  => $option['product_option_id'],
                                        'product_option_value_id'  => $option['product_option_value_id'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type'],
                                        'name'  => $option['name'],
				);
			}

			 $log->write("user tax");
			 $log->write($product['price']);
			 $log->write( $product['tax_class_id']);
			 $log->write($this->tax->getTax($product['price'], $product['tax_class_id']));

			$data['order_product'][] = array(
                                'product_id'   => $product['product_id'],
				'name'         => $product['name'],
				'model'        => $product['model'], 
				'quantity'     => $product['quantity'],                            
				'price'        => $product['price'],
				'total'        => $product['price']*$product['quantity'],
                                'tax'          => $this->tax->getTax($product['price'], $product['tax_class_id']),
                                'reward'       => $product['reward'],
				'order_option' => $option_data,
			);
		}//foreach products 

		 $log->write('just before prdsubs');
			   $log->write( $prdsubs); 
			if(!empty($prdsubs))
		   {
			foreach($prdsubs as $prdsub)
			{
				$data['order_product_subsidy'][] = array(
                                'product_id'   => $prdsub['PRODUCT_ID'],
								'discount_value'         => $prdsub['SUBSIDY_RATE'],
								'discount_type'        => 'P', 			
                                'reward'       => $prdsub['DISCOUNT']
			);
			}
		   }
		$log->write($data['order_product_subsidy']);
		$log->write('just afer prdsubs');
                $this-> adminmodel('pos/extension');
                $total_data = array();					
                $total = 0;
                $taxes = $this->cart->getTaxes();
		$log->write("near");
                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $sort_order = array(); 

                        $results = $this->model_pos_extension->getExtensions('total');
			$log->write($results);
                        foreach ($results as $key => $value) {
                                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                        }

                        array_multisort($sort_order, SORT_ASC, $results);

                        foreach ($results as $result) {
                                if ($this->config->get($result['code'] . '_status')) {

	                                    $this-> adminmodel('pos/' . $result['code']);

                                        $this->{'model_pos_' . $result['code']}->getTotal($total_data, $total, $taxes);
                                }

                                $sort_order = array(); 

                                foreach ($total_data as $key => $value) {
                                        $sort_order[$key] = $value['sort_order'];
                                }

                                array_multisort($sort_order, SORT_ASC, $total_data);			
                        }
                }
		$log->write("near1");
		$log->write($total_data);
		$data['amtcash']=$this->request->post['amtcash'];
		$data['subsidy']=$this->request->post['subcash'];
		$data['sub']=$this->request->post['sub'];
                $data['order_total'] = $total_data;
		//for tagged		
		if(isset($this->request->post['docs']))
		{
			try{
			$data['shipping_address_2'] = ($this->request->post['docs']) ;
			$data['shipping_city'] = ($this->request->post['doc_number']) ;
			}catch(Exception $e){}			
		}

		/*
				if($payment_method=='Tagged Cash')
		{
			$data['shipping_address_2'] = $mcrypt->decrypt($this->request->post['docs']) ;
			$data['shipping_city'] = $mcrypt->decrypt($this->request->post['doc_number']) ;
			
		}
				if($payment_method=='Subsidy')
		{
			$data['shipping_address_2'] = $mcrypt->decrypt($this->request->post['docs']) ;
			$data['shipping_city'] = $mcrypt->decrypt($this->request->post['doc_number']);			
		}*/
		//for cheque
		if($payment_method=='Cheque')
		{
			$data['chenum']=$mcrypt->decrypt($this->request->post['chenum']);
			$data['chemic']=$mcrypt->decrypt($this->request->post['chemic']);
			$data['chebnk']=$mcrypt->decrypt($this->request->post['chebnk']);
			$data['cheacc']=$mcrypt->decrypt($this->request->post['cheacc']);
			$data['cheaccno']=$mcrypt->decrypt($this->request->post['cheaccno']);
		}
                
                if(isset($this->session->data['voucher'])){
                    $data['order_voucher'] = $this->session->data['voucher'];
                }
                
                //end of order total 
                $json['customer_name'] = $data['firstname'].' '.$data['lastname'];
                $log->write($json);
                $log->write("near2");
				$data['utype']=$mcrypt->decrypt($this->request->post['utype']);	
				 
                $log->write("all data added in the array just  before the call of addorder is : ");
                $log->write($data);
                $order_id = $this->model_pos_pos->addOrder($data);	
                $log->write("Order Successfully added in oc_order - ".$order_id);
		if(isset($this->request->post['transid']))
                {
                    $this->model_pos_pos->update_order_istance_order_id($mcrypt->decrypt($this->request->post['transid']),$order_id);
		}		
		//bcml
		try{
		$this->model_pos_pos->update_indent_order_deleviery($this->request->post['comment'],$mcrypt->decrypt($this->request->post['cde']),$mcrypt->decrypt($this->request->post['deliverymode']),$order_id,$mcrypt->decrypt($this->request->post['approvaltype']),$mcrypt->decrypt($this->request->post['deliveryreceipt']),$mcrypt->decrypt($this->request->post['fmcode']),$mcrypt->decrypt($this->request->post['fmname']));	
		} catch (Exception $e) { $log->write($e); }
		//end
		try{
			if(isset($this->request->post['lumpsum'])){
				$this->model_pos_pos->update_advance_order_deleviery($this->request->post['comment'],$mcrypt->decrypt($this->request->post['cde']),$mcrypt->decrypt($this->request->post['deliverymode']),$order_id,$mcrypt->decrypt($this->request->post['approvaltype']),$mcrypt->decrypt($this->request->post['deliveryreceipt']),$mcrypt->decrypt($this->request->post['fmcode']),$mcrypt->decrypt($this->request->post['fmname']));
				} 
		}
		catch (Exception $e) { $log->write($e); }
 
                $data['order_id']=$order_id;
				$data['vill']=$mcrypt->decrypt($this->request->post['villageid']);
				$data['villname']=$mcrypt->decrypt($this->request->post['vname']);
				$data['oid']=$order_id;
				$data['tagged_amt']=$mcrypt->decrypt($this->request->post['tagged_amt']);  
				// add order to cane system
				$log->write($data['store_name']);
                			
				$data['store_name'] = $this->config->get('config_name'); 
				$log->write($data['store_name']);
				$log->write('unit data before call to updatedelivery');
				$log->write($unitdata);
				if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->adminmodel('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->UpdateDelivery('UpdateDelivery',$data,0);	
						$log->write("in company".$datares);	
						
						if(empty($datares))
							{
								//return error
								$json['error']="Error at cane system";
								$json['success'] = "-1";
								$this->response->setOutput(json_encode($json));	
								return;
							}
							if(!is_numeric($datares))
							{
								//return error
								$json['error']="Error at cane system";
								$json['success'] = "-1";
								$this->response->setOutput(json_encode($json));	
								return;
							}
						$log->write("in company-".$datares);	
							if(!empty($datares))
								{
									//data check
									$data['order_trans_id']=$datares;
									$json['qid'] = $datares;
									
								}	
						
							
						}
				
			$log->write($datares);
				
				if((!isset($this->request->post['prdsub'])) && (!empty($this->request->post['prdsub'])))
				{
					$this->model_pos_pos->confirm_coupon($data, $this->request->post['coupon']);
				}
                $log->write('payment_method is -'.$this->request->post['payment_method']);
                if(($this->request->post['payment_method']!='Cash') && ($this->request->post['payment_method']!='Subsidy'))
                {
                try{
                   
                $this->model_pos_pos->UpdateOrderStatusLeads($this->request->post['comment'],'5');
		//$this->model_pos_pos->RequisitionToBill($this->request->post['comment'],$order_id);
		} catch (Exception $e) { $log->write($e); }

               }
                if(($this->request->post['payment_method']=='Cash') && ($this->request->post['comment']!=''))
                {
                try{
                $this->model_pos_pos->UpdateOrderStatusLeads($this->request->post['comment'],'5');
		//$this->model_pos_pos->RequisitionToBill($this->request->post['comment'],$order_id);
		} catch (Exception $e) { $log->write($e); }

               }
                if(($this->request->post['payment_method']=='Subsidy') && ($this->request->post['comment']!=''))
                {
                try{
                $this->model_pos_pos->UpdateOrderStatusLeads($this->request->post['comment'],'5');
		//$this->model_pos_pos->RequisitionToBill($this->request->post['comment'],$order_id);
		} catch (Exception $e) { $log->write($e); }

               }
		if($mcrypt->decrypt($this->request->post['card_no'])=="2"  )
		{
			
			$this->model_setting_store->updatecurrentcash($this->request->post['stock_fm'],$total,$data['store_id']);

		}		

                $log->write("near3");
                unset($this->session->data['discount_amount']);

                //recore for counter payment 
                if($this->request->post['payment_method']  == 'Tagged Cash'){                    
                    $cash = (float)$data['amtcash'];
                    $card = 0;//$total;
                }
                else if($this->request->post['payment_method']  == 'Subsidy'){                    
                    $cash = $this->request->post['subcash'];
                    $card = 0;//$total;
                }
                else{
                    $cash = $total;
                    $card = 0;
                }
                
                $data = array(
                 'user_id' => $this->request->post['user_id'],
                 'cash' => $cash,
                 'card' => $card,    
                 'store_id'=>$this->request->post['store_id'],
	         'order_id'=>$order_id,
	         'payment_method'=>$this->request->post['payment_method'],
		 'total'=>$total             
                );
                
                if($this->request->post['payment_method']  == 'Tagged Cash') 
                {
                    $log->write('Payment Method is: '.$this->request->post['payment_method'].' so we will call the addPayment after success of order in updatestatus ' ); 
                    $this->model_pos_pos->addPayment($data); 
                }
                if($this->request->post['payment_method']  == 'Cash')
                {
                    $log->write('Payment Method is: '.$this->request->post['payment_method']); 
                    $this->model_pos_pos->addPayment($data); 
                }
                if($this->request->post['payment_method']  == 'Subsidy')
                {
                    $log->write('Payment Method is: '.$this->request->post['payment_method']); 
                    $this->model_pos_pos->addPayment($data); 
                }
                /*
	if(($payment_method  == 'Tagged Cash') ||  ($payment_method  == 'Cash')) 
	  {
                  $log->write('Payment Method is: '.$this->request->post['payment_method']); 
                  $log->write($data); 
                $this->model_pos_pos->addPayment($data); 
                }
                //recore for counter payment 
                if($payment_method  == 'Card'){                    
                    $cash = 0;
                    $card = $total;
                }else{
                    $cash = $total;
                    $card = 0;
                }
                
                $data = array(
                 'user_id' => $this->user->getId(),
                 'cash' => $cash,
                 'card' => $card,                 
                );
                
                $this->model_pos_pos->addPayment($data);
                */
                $json['order_id'] = $order_id;
                $log->write("Genereted Invoice number - ".$order_id);
                $balance = $this->model_pos_pos->get_user_balance($this->user->getId());
                $json['cash'] = $this->currency->format($balance['cash']);
                $json['card'] = $this->currency->format($balance['card']);

                $log->write("done----".$customer_id);
		// Set the order history

                $log->write("dones----");
try
	   {

		
				if (isset($this->request->post['order_status_id'])) 
                                {
					$order_status_id = $this->request->post['order_status_id'];
				} 
                                else 
                                {
					$order_status_id = $this->config->get('config_order_status_id');
				}
	
				//$this->model_checkout_order->addOrderHistory($json['order_id'], $order_status_id);
 }
	   catch (Exception $e) 
	   {
                  $log->write('add order history  in catch');
                  $log->write($e);
           }


	
                $log->write("done1");
 
                $json['success'] = 'Success: new order placed with ID: '.$order_id;

	   try
	   {
                 $log->write("Inv genrate");
		 $invoice_no=$this->model_pos_pos->createInvoiceNo($order_id);
		 $json['invoice_no']=$invoice_no;
                 $log->write($invoice_no);
		 $log->write("Inv genrate update done");
	   }
	   catch (Exception $e) 
	   {
                  $log->write('generate invoice gone in catch');
                  $log->write($e);
           }

		$json['orddate'] = date('Y-m-d h:i:s A');

		$log->write("before call to get_order_total");
                $json['coupon_discount']=$this->model_pos_pos->get_order_total($order_id,'coupon');
                $log->write("after call to get_order_total");
		
                $gtax=$this->model_checkout_order->getgtax($order_id);

                $json['gtax']= $mcrypt->encrypt(json_encode($gtax));  
                $sms=new sms($this->registry);
                try
                {				
                    $companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
                    if(!empty($companydata))
                    {
                        $data['unitid']=$companydata[0]['unit_id'];
                        $log->write($companydata);
                        $company=strtolower($companydata[0]['company_name']);
                        $log->write($company);
                    }
                    if($company!='isec')
                    {
                     //$sms->sendsms($this->request->post['customer_mob'],"2",$data);
                    }
		}
		catch (Exception $e) 
                {
                                $log->write($e);
                }
                   

if(strtolower($this->request->post['coupon'])=="diwali")
{
try{
	   //$log2=new Log("recharge-".date('Y-m-d').".log");
                 $log->write('Diwali Coupon so now we will call the thread for'.$order_id);
               
                 //($mobile,$muid,$scheme,$order_id,$store_id)
                $asyncOperation=new AsyncOperationSeasionalRecharge($this->request->post['customer_mob'],$this->request->post['muid'],'7',$order_id,$this->request->post['store_id']);
                $log->write('now we will call the thread 3');
	  $asyncOperation->start();
                $log->write('now we will call the thread 4');
}
catch(Exception $e)
{
$log2->write($e);
}
}
else
{

//send to recharge
              $log->write('Recharge thread open');
try{
if($this->request->post['payment_method']  == 'Cash') 
{              $log->write('Payment method is :'.$this->request->post['payment_method']." so start the thread");
	 $asyncOperation=new AsyncOperation($this->request->post['customer_mob'],$order_id,$this->request->post['store_id'],$prds);
	  $asyncOperation->start();
              $log->write('Recharge thread start');
}
else
{
$log->write('Payment method is :'.$this->request->post['payment_method']." so no need to call thread");
}
} catch (Exception $e) {
                                $log->write($e);
                            }
//end recharge        
}        
		$log->write('final return by addorder in order.php');
		$log->write($json);
   	            $this->response->setOutput(json_encode($json));	

	}//END add order 

public function updateorderQR()
{

	$log=new Log("updateorderqr-".date('Y-m-d').".log");
	$log->write($this->request->post);
	$mcrypt=new MCrypt();
	$keys = array(
			'oid',
			'billno',
			'username',
			'uid'
			);

	foreach ($keys as $key) {            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
		
	//log to system table
	$this->load->model('account/activity');
	$activity_data = array(
					'customer_id' => ($this->request->post['username']),
					'data'        => json_encode($this->request->post),
		);

	//$this->model_account_activity->addActivity('updateOrder', $activity_data);

		//get unit data
		$this->adminmodel('pos/pos');
		$order_details=$this->model_pos_pos->getOrder($this->request->post["billno"]); 
		$log->write($order_details);
		if(empty($this->request->post['uid']))
		{
			$this->request->post['uid']=$order_details['unit_id'];
		}	
		
		$this->request->post['billnofordscl']=$order_details['invoice_prefix'].$order_details['invoice_no'];
		$log->write($this->request->post);
		$this->adminmodel('unit/unit');
        $unitdata= $this->model_unit_unit->getUnitByID($this->request->post['uid']);
		$log->write($unitdata);
									
					if(!empty($unitdata['company_name']))
					{
						$company=strtolower($unitdata['company_name']);
						$log->write("in company ".$company);
						$this->adminmodel('pos/'.$company);
						$log->write("before sending to updatestatus for ".$this->request->post["billno"]);
						$datares = $this->{'model_pos_' . $company}->UpdateStatus('UpdateStatus',$this->request->post,0);	
						$log->write("return by company ".$datares." for ".$this->request->post["billno"]);	
						if(!empty($datares))
						{
							//data check
							try
							{
								//$log->write($this->request->post);
								$log->write("come in try to send data to RequisitionToBill".$this->request->post["billno"]);
								$this->model_pos_pos->RequisitionToBill($this->request->post['oid'],$this->request->post['billno']);
								$this->request->post['store_id']=$order_details['store_id'];
								$this->request->post['order_id']=$this->request->post["billno"];
								$this->request->post['web_app']='qr'; 
								$this->model_pos_pos->updateinventory($this->request->post);
								$this->model_pos_pos->addPayment_complete($order_details); 		
								$this->response->setOutput("1");
							} 
							catch (Exception $e) 
							{
                                $log->write("come in catch to send data to RequisitionToBill".$this->request->post["billno"]);
                            }
		
									
						}
						else
						{
							$log->write("data return by updatestatus is empty");
						}
						
					}	 
}
  
public function updateorder()
{

$log=new Log("updateorder-".date('Y-m-d').".log");
$log->write('updateorder called in order.php');
$log->write($this->request->post);

$retval="0";
		 $mcrypt=new MCrypt();
		$keys = array(
			'oid',
			'billno',
			'username',
			);

foreach ($keys as $key) {            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
				//log to system table
				$this->load->model('account/activity');
				$activity_data = array(
					'customer_id' => ($this->request->post['username']),
					'data'        => json_encode($this->request->post),
				);

				//$this->model_account_activity->addActivity('updateOrder', $activity_data);

		$log->write($this->request->post);
		$this-> adminmodel('pos/pos');
                            $this->model_pos_pos->UpdateOrderStatusLeads($this->request->post['oid'],'5');
		try{
			$log->write("come in try to send data to RequisitionToBill".$this->request->post["billno"]);
		$this->model_pos_pos->RequisitionToBill($this->request->post['oid'],$this->request->post['billno']);
		


			//chnage
					$order_details=$this->model_pos_pos->getOrder($this->request->post["billno"]);
					$log->write($order_details);
					$this->request->post['store_id']=$order_details['store_id'];
					$this->request->post['order_id']=$this->request->post["billno"]; 
					$this->request->post['web_app']='app';
					$this->model_pos_pos->updateinventory($this->request->post); 
					$this->model_pos_pos->addPayment_complete($order_details); 					
					
							$companydata1=$this->model_pos_pos->getunitidandcompanyid(array('storeid'=>$order_details['store_id'])); 

							if(!empty($companydata1))
                    		{
                        		$data1['unitid']=$companydata1[0]['unit_id'];
                       	 		$log->write($companydata1);
                        			$company1=strtolower($companydata1[0]['company_name']);
									
                        			$log->write($company1);
									if($company1=='bcml')
									{
										$this->adminmodel('pos/'.$company1);
										$retbcml=$this->{'model_pos_' .$company1}->GetIndentByInvoiceNo('GetIndentByInvoiceNo',array('unitid'=>$data1['unitid'],'invoiceno'=>$this->request->post["billno"],'store_id'=>$order_details['store_id']),true); 
										$log->write($retbcml);
										if($retbcml[0]['InvoiceNo']==$this->request->post["billno"])
										{ /////////////success
											$log->write('success');											
											$retval=("1");	
										}
										else
										{ ///////// invoice number not matched
											$log->write('invoice number not matched');
											$retval=("0");	
										}
				
									}
									else
									{  //////////////company is not bcml
										$log->write('company is not bcml');
										$retval=("1");	
									}
							}	
							else
							{ ////////////company details not found
								$log->write('company details not found');
								$retval=("1");
							}
			//end
		} catch (Exception $e) {
                                $log->write("come in catch to send data to RequisitionToBill".$this->request->post["billno"]);
					
                            }
		$this->response->setOutput($retval);				

}





public function addcustomer($sid)
                {       
$mcrypt=new MCrypt();                                                               
             $this->request->post['card']=="0";   
             $this->request->post['firstname']=='';
	 if(!empty($this->request->post['fname']))
   	 {
             $this->request->post['firstname']=$mcrypt->decrypt($this->request->post['fname']).'-'.$mcrypt->decrypt($this->request->post['lname']);
    	}
	else if(!empty($this->request->post['growername']))
   	 {
             $this->request->post['firstname']=$mcrypt->decrypt($this->request->post['growername']);
    	}
             $this->request->post['lastname']=='';
             $this->request->post['village']=='';    
	 if(!empty($this->request->post['vname']))
   	 {
             $this->request->post['village']=$mcrypt->decrypt($this->request->post['vname']); 
    	}        
        else if(!empty($this->request->post['growercode']))
   	 {
             $this->request->post['village']=$mcrypt->decrypt($this->request->post['growercode']); 
    	} 
             $this->adminmodel('sale/customer');                           
              unset($this->session->data['cid']);
             $this->request->post['email']=$this->request->post['customer_mob'];
             $this->request->post['fax']=$this->request->post['customer_mob'];
             $this->request->post['telephone']=$this->request->post['customer_mob'];
	     $this->request->post['customer_group_id']="1";
             $this->request->post['password']=$this->request->post['customer_mob'];
             $this->request->post['newsletter']='0';        
             $this->request->post['approved']='1';
             $this->request->post['status']='1';
             $this->request->post['safe']='1';
             $this->request->post['address_1']= $this->request->post['village'];
             $this->request->post['address_2']= $this->request->post['village'];
             $this->request->post['city']= $this->request->post['village'];
             $this->request->post['company']='Unnati';
             $this->request->post['country_id']='0';
             $this->request->post['zone_id']='0';
             $this->request->post['postcode']='0';
             $this->request->post['store_id']=$sid;             
             $this->request->post['address']=array($this->request->post);
             $this->model_sale_customer->addCustomer($this->request->post);             
          }



	public function edit() {
		$this->load->language('api/order');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/order');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info) {
				// Customer
				if (!isset($this->session->data['customer'])) {
					$json['error'] = $this->language->get('error_customer');
				}

				// Payment Address
				if (!isset($this->session->data['payment_address'])) {
					$json['error'] = $this->language->get('error_payment_address');
				}

				// Payment Method
				if (!isset($this->session->data['payment_method'])) {
					$json['error'] = $this->language->get('error_payment_method');
				}

				// Shipping
				if ($this->cart->hasShipping()) {
					// Shipping Address
					if (!isset($this->session->data['shipping_address'])) {
						$json['error'] = $this->language->get('error_shipping_address');
					}

					// Shipping Method
					if (!isset($this->request->post['shipping_method'])) {
						$json['error'] = $this->language->get('error_shipping_method');
					}
				} else {
					unset($this->session->data['shipping_address']);
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}

				// Cart
				if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
					$json['error'] = $this->language->get('error_stock');
				}

				// Validate minimum quantity requirements.
				$products = $this->cart->getProducts();

				foreach ($products as $product) {
					$product_total = 0;

					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}

					if ($product['minimum'] > $product_total) {
						$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

						break;
					}
				}

				if (!$json) {
					$order_data = array();

					// Store Details
					$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
					$order_data['store_id'] = $this->config->get('config_store_id');
					$order_data['store_name'] = $this->config->get('config_name');
					$order_data['store_url'] = $this->config->get('config_url');

					// Customer Details
					$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
					$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
					$order_data['firstname'] = $this->session->data['customer']['firstname'];
					$order_data['lastname'] = $this->session->data['customer']['lastname'];
					$order_data['email'] = $this->session->data['customer']['email'];
					$order_data['telephone'] = $this->session->data['customer']['telephone'];
					$order_data['fax'] = $this->session->data['customer']['fax'];
					$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

					// Payment Details
					$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
					$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
					$order_data['payment_company'] = $this->session->data['payment_address']['company'];
					$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
					$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
					$order_data['payment_city'] = $this->session->data['payment_address']['city'];
					$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
					$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
					$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
					$order_data['payment_country'] = $this->session->data['payment_address']['country'];
					$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
					$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
					$order_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];

					if (isset($this->session->data['payment_method']['title'])) {
						$order_data['payment_method'] = $this->session->data['payment_method']['title'];
					} else {
						$order_data['payment_method'] = '';
					}

					if (isset($this->session->data['payment_method']['code'])) {
						$order_data['payment_code'] = $this->session->data['payment_method']['code'];
					} else {
						$order_data['payment_code'] = '';
					}

					// Shipping Details
					if ($this->cart->hasShipping()) {
						$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
						$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
						$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
						$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
						$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
						$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
						$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
						$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
						$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
						$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
						$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
						$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
						$order_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'];

						if (isset($this->session->data['shipping_method']['title'])) {
							$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
						} else {
							$order_data['shipping_method'] = '';
						}

						if (isset($this->session->data['shipping_method']['code'])) {
							$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
						} else {
							$order_data['shipping_code'] = '';
						}
					} else {
						$order_data['shipping_firstname'] = '';
						$order_data['shipping_lastname'] = '';
						$order_data['shipping_company'] = '';
						$order_data['shipping_address_1'] = '';
						$order_data['shipping_address_2'] = '';
						$order_data['shipping_city'] = '';
						$order_data['shipping_postcode'] = '';
						$order_data['shipping_zone'] = '';
						$order_data['shipping_zone_id'] = '';
						$order_data['shipping_country'] = '';
						$order_data['shipping_country_id'] = '';
						$order_data['shipping_address_format'] = '';
						$order_data['shipping_custom_field'] = array();
						$order_data['shipping_method'] = '';
						$order_data['shipping_code'] = '';
					}

					// Products
					$order_data['products'] = array();

					foreach ($this->cart->getProducts() as $product) {
						$option_data = array();

						foreach ($product['option'] as $option) {
							$option_data[] = array(
								'product_option_id'       => $option['product_option_id'],
								'product_option_value_id' => $option['product_option_value_id'],
								'option_id'               => $option['option_id'],
								'option_value_id'         => $option['option_value_id'],
								'name'                    => $option['name'],
								'value'                   => $option['value'],
								'type'                    => $option['type']
							);
						}

						$order_data['products'][] = array(
							'product_id' => $product['product_id'],
							'name'       => $product['name'],
							'model'      => $product['model'],
							'option'     => $option_data,
							'download'   => $product['download'],
							'quantity'   => $product['quantity'],
							'subtract'   => $product['subtract'],
							'price'      => $product['price'],
							'total'      => $product['total'],
							'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
							'reward'     => $product['reward']
						);
					}

					// Gift Voucher
					$order_data['vouchers'] = array();

					if (!empty($this->session->data['vouchers'])) {
						foreach ($this->session->data['vouchers'] as $voucher) {
							$order_data['vouchers'][] = array(
								'description'      => $voucher['description'],
								'code'             => substr(md5(mt_rand()), 0, 10),
								'to_name'          => $voucher['to_name'],
								'to_email'         => $voucher['to_email'],
								'from_name'        => $voucher['from_name'],
								'from_email'       => $voucher['from_email'],
								'voucher_theme_id' => $voucher['voucher_theme_id'],
								'message'          => $voucher['message'],
								'amount'           => $voucher['amount']
							);
						}
					}

					// Order Totals
					$this->load->model('extension/extension');

					$order_data['totals'] = array();
					$total = 0;
					$taxes = $this->cart->getTaxes();

					$sort_order = array();

					$results = $this->model_extension_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);

							$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
						}
					}

					$sort_order = array();

					foreach ($order_data['totals'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $order_data['totals']);

					if (isset($this->request->post['comment'])) {
						$order_data['comment'] = $this->request->post['comment'];
					} else {
						$order_data['comment'] = '';
					}

					$order_data['total'] = $total;

					if (isset($this->request->post['affiliate_id'])) {
						$subtotal = $this->cart->getSubTotal();

						// Affiliate
						$this->load->model('affiliate/affiliate');

						$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

						if ($affiliate_info) {
							$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
							$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
						} else {
							$order_data['affiliate_id'] = 0;
							$order_data['commission'] = 0;
						}
					} else {
						$order_data['affiliate_id'] = 0;
						$order_data['commission'] = 0;
					}

					$this->model_checkout_order->editOrder($order_id, $order_data);

					// Set the order history
					if (isset($this->request->post['order_status_id'])) {
						$order_status_id = $this->request->post['order_status_id'];
					} else {
						$order_status_id = $this->config->get('config_order_status_id');
					}

					$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);

					$json['success'] = $this->language->get('text_success');
				}
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('api/order');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/order');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info) {
				$this->model_checkout_order->deleteOrder($order_id);

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}





	public function OrderProducts() 
		{

		$log=new Log("OrderProducts-".date('Y-m-d').".log");
		 $mcrypt=new MCrypt();
		$data['products'] = array();
		$log->write("OrderProducts called");
                            $filter_order_id =$mcrypt->decrypt($this->request->get['order_id']);
                            if($mcrypt->decrypt($this->request->get['order_id'])=="1090623")
                            {
                             $filter_order_id ="51248";
                            }
                           
                            
			
				$log->write("hist2");
			$this->adminmodel('sale/order');
			$this->adminmodel('setting/setting');
			$this->adminmodel('user/user');
		$resord=$this->model_sale_order->getOrderUser($filter_order_id);
		$log->write($resord);
		$results = $this->model_sale_order->getOrderProducts($filter_order_id);
						$log->write("hist3");	
		$store_id=$this->model_sale_order->getOrderStoreId($mcrypt->decrypt($this->request->get['order_id']));
						$log->write("hist4");
		$store_add=$this->model_setting_setting->getSettingbykey('config','config_address', $store_id);
		$store_gstn=$this->model_setting_setting->getSettingbykey('config','config_gstn', $store_id);
		
		foreach ($results as $result) {

		$log->write($result);
		if(!empty($result['HSTN']))
		{
			$HSTN=$result['HSTN'];
		}
		else
		{
			$HSTN=$result['hsn_code'];
		}
			$data['products'][] = array(
				'order_product_id'      =>$mcrypt->encrypt( $result['order_product_id']),
				'product_id'      =>$mcrypt->encrypt( $result['product_id']),
				'subsidy'	=> $mcrypt->encrypt(empty($this->model_sale_order->getProductSubsidy($result['product_id'],$store_id))?0:$this->model_sale_order->getProductSubsidy($result['product_id'],$store_id)),
				'name'        =>$mcrypt->encrypt( $result['name'] ),
				'model'         => $mcrypt->encrypt($result['model']),
				'quantity'    =>$mcrypt->encrypt( $result['quantity']),
				'price' => $mcrypt->encrypt( ($result['price']) ),
				'total' => $mcrypt->encrypt(($result['total'])+(round($result['tax'])*$result['quantity'])),
				'tax'	=> $mcrypt->encrypt(($result['tax'])),
				'hstn'	=> $mcrypt->encrypt(($HSTN))
			
			);
			$log->write($HSTN);
		}

		//$log->write($this->model_sale_order->getorderSubTotalvalue($filter_order_id));

		$data['total']=$mcrypt->encrypt($this->model_sale_order->getorderTotalvalue($filter_order_id));
		$data['tax']=$mcrypt->encrypt($this->model_sale_order->getorderTaxvalue($filter_order_id));
		$data['subtotal']=$mcrypt->encrypt($this->model_sale_order->getorderSubTotalvalue($filter_order_id));
		$data['subsidy']=$mcrypt->encrypt($this->model_sale_order->getOrderSubsidy(($filter_order_id)));
		$data['cash']=$mcrypt->encrypt($this->model_sale_order->getOrdercash(($filter_order_id)));
		
		$data['oid']=$mcrypt->encrypt($this->model_sale_order->getOrderreqid(($filter_order_id)));
		//getOrderreqid		
		try{
    $log->write("before order details");
   $orderDetails=$this->model_sale_order->getSuccessOrderInfo($filter_order_id);

	$subsidy_category_id=$orderDetails['subsidy_category'];
            $data['categoryname']='0';
            if(!empty($subsidy_category_id))
            {
            $data['categoryname']=$this->model_sale_order->getsubsidyCategoryName($subsidy_category_id);
            }
            $data['categoryname']=$mcrypt->encrypt($data['categoryname']);

  
   $log->write($orderDetails);
   $this->adminmodel('lead/orderleads');
   $log->write("in data y"); 
  //getOrder
  //new addidtion
  $oid=$this->model_lead_orderleads->getbill_to_requisition($filter_order_id);
     $log->write("in data y ".$oid);
 
  $orderlead=$this->model_lead_orderleads->getOrderdtl($oid);
  $log->write("in data s ");
    $log->write($orderlead);
  $data['cus_id']=$mcrypt->encrypt($orderlead[0]['payment_address_1']);
  if(empty($orderlead[0]['payment_address_1']))
  {
   if(!empty($orderDetails['shipping_firstname']))
   {
   $data['cus_id']=$mcrypt->encrypt($orderDetails['shipping_firstname']);
   }
   else
   {
   $data['cus_id']=$mcrypt->encrypt($orderDetails['customer_id']);
   }
  }
  ///////////////////////////////
  if(!empty($orderDetails['shipping_firstname']))
  {
	$data['cus_id']=$mcrypt->encrypt($orderDetails['shipping_firstname']);////////in case of bcml tagged
  }
  else if(!empty($orderDetails['grower_id']))
  {
	$data['cus_id']=$mcrypt->encrypt($orderDetails['grower_id']);///////in case of dscl tagged
  }
  else
  {
	$data['cus_id']=$mcrypt->encrypt($orderDetails['customer_id']);/////////in case of cash
  }
  /////////////////////////
  $data['far_name']=$mcrypt->encrypt($orderlead[0]['payment_firstname']);
  if(!empty($orderDetails['firstname']))
  {
  $farm_father_array=explode('-',$orderDetails['firstname']);
  }
  else
  {
  $farm_father_array=array($orderDetails['shipping_firstname'],'');
  }
  if(empty($orderlead[0]['payment_firstname']))
  {
   $data['far_name']=$mcrypt->encrypt($farm_father_array[0]);
  }
  $data['fath_name']=$mcrypt->encrypt($orderlead[0]['payment_lastname']);
  if(empty($orderlead[0]['payment_lastname']))
  {
   $data['fath_name']=$mcrypt->encrypt($farm_father_array[1]);
  }
  
  $log->write("'fath_name'=".$farm_father_array[1]);
  
  $data['vill_name']=$mcrypt->encrypt($orderlead[0]['shipping_firstname']);
  if(empty($orderlead[0]['shipping_firstname']))
  {
   $data['vill_name']=$mcrypt->encrypt($orderDetails['payment_address_1']);
  }
  $data['stor_name']=$mcrypt->encrypt($orderlead[0]['store_name']);
  if(empty($orderlead[0]['store_name']))
  {
   $data['stor_name']=$mcrypt->encrypt($orderDetails['store_name']);
  }
  $data['cid']=$mcrypt->encrypt($orderlead[0]['payment_address_1']);
  if(empty($orderlead[0]['payment_address_1']))
  {
   if(!empty($orderDetails['shipping_firstname']))
   {
   $data['cid']=$mcrypt->encrypt($orderDetails['shipping_firstname']);
   }
   else
   {
   $data['cid']=$mcrypt->encrypt($orderDetails['customer_id']);
   }
  }
  $data['fname']=$mcrypt->encrypt($orderlead[0]['payment_firstname']);
  if(empty($orderlead[0]['payment_firstname']))
  {
   $data['fname']=$mcrypt->encrypt($farm_father_array[0]);
  }
  $data['lname']=$mcrypt->encrypt($orderlead[0]['payment_lastname']);
  if(empty($orderlead[0]['payment_lastname']))
  {
   $data['lname']=$mcrypt->encrypt($farm_father_array[1]);
  }
  
  $log->write("'lname'=".$farm_father_array[1]);
  
  $data['vname']=$mcrypt->encrypt($orderlead[0]['shipping_firstname']);
  if(empty($orderlead[0]['shipping_firstname']))
  {
   $data['vname']=$mcrypt->encrypt($orderDetails['payment_address_1']);
  }
  $data['stname']=$mcrypt->encrypt($orderlead[0]['store_name']);
  if(empty($orderlead[0]['store_name']))
  {
   $data['stname']=$mcrypt->encrypt($orderDetails['store_name']);
  }
$delm=	$this->model_sale_order->getOrderdelivery($filter_order_id);
$log->write($delm);

  $data['deliverymode']=$mcrypt->encrypt($delm['delivery_type_name']);//$mcrypt->encrypt("test");

  $data['fmname']=$mcrypt->encrypt($delm['fmname']);  
	 // $log->write($data);
  $log->write($resord);

  $usernames=$this->model_user_user->getUser($resord);
  $log->write($usernames);
  $data['opname']=$mcrypt->encrypt($usernames["firstname"]." ".$usernames["lastname"]);
  $data['stadd']=$mcrypt->encrypt($store_add);
  ///////////////////////
	if(!empty($orderDetails['shipping_firstname']))
   	{
   		$data['grower_id']=$mcrypt->encrypt($orderDetails['shipping_firstname']);
   	}
   	else if(!empty($orderDetails['grower_id']))
   	{
   		$data['grower_id']=$mcrypt->encrypt($orderDetails['grower_id']);
   	}
	else
   	{
   		$data['grower_id']=$mcrypt->encrypt($orderDetails['customer_id']);/////////in case of cash
   	}
	///////////////////////
	$data['pay_method']=$mcrypt->encrypt($orderDetails['payment_method']);
	$data['mob']=$mcrypt->encrypt($orderDetails['telephone']);
	$data['gstn']=$mcrypt->encrypt($store_gstn); 
	$data['date']=$mcrypt->encrypt($orderDetails['date_added']);
	$data['invoice_no']=$mcrypt->encrypt($orderDetails['invoice_prefix']."-".$orderDetails['invoice_no']);
	$data['tagged']=$mcrypt->encrypt($orderDetails['tagged']);
	$data['order_id']=$mcrypt->encrypt($filter_order_id); 
	$extra_array=array('test key 1'=>$mcrypt->encrypt('test val 1'),'test key 2'=>$mcrypt->encrypt('test val 2'));
	$data['extra']=$extra_array; 
	
  }catch(Exception $el){
   $log->write($el);
   }
				$log->write($data);
		$this->load->model('checkout/order');
                
		$gtax=$this->model_checkout_order->getgtax($filter_order_id); 

	              $data['gtax']= $mcrypt->encrypt(json_encode($gtax));

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));

		}

	public function history() 
	{
		$mcrypt=new MCrypt();
		$log=new Log("order-history-".date('Y-m-d').".log");
		$log->write('Order History called');
		$this->load->language('api/order');
		$json = array();
		$keys = array(
				'order_status_id',
				'notify',
				'append',
				'comment'
		);
		foreach ($keys as $key) 
		{
				if (!isset($this->request->post[$key])) 
				{
					$this->request->post[$key] = '';
				}
		}
		$log->write($this->request->get);
		$log->write($this->request->post);
		if(isset($this->request->get['order_id']) && isset($this->request->post['s_type']) && (!empty($this->request->post['s_type'])))
		{
			if($mcrypt->decrypt($this->request->post['s_type'])=="1")
			{
				$log->write("In reg func");
				$this->adminmodel('lead/orderleads');
				$get_bill=$this->model_lead_orderleads->getrequisition_to_bil($mcrypt->decrypt($this->request->get['order_id']));
				$log->write("In reg func-".$get_bill);
				$log->write($mcrypt->decrypt($this->request->get['order_id']));
				if(!empty($get_bill))
				{
					$this->request->get['order_id']=$mcrypt->encrypt($get_bill);
				}
			}

		}
		$log->write('new process start');
		//new 

		if (isset($this->request->get['order_id'])) 
		{
			$filter_order_id = $mcrypt->decrypt($this->request->get['order_id']);
			if(strlen($filter_order_id)==10)
			{									
				$filter_telephone_id=$filter_order_id;
				$filter_order_id = null;					
			}
		} 
		else
		{
			$filter_order_id = null;			
		}
		$filter_user_id=null;

 		if (isset($this->request->get['fd'])) 
		{
			$this->request->get['filter_date_added']=date('Y-m-d');
			$filter_user_id=$mcrypt->decrypt($this->request->post['username']);						
		}


		if (isset($this->request->get['filter_customer'])) 
		{
			$filter_customer = $this->request->get['filter_customer'];
		} 
		else 
		{
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status'])) 
		{
			$filter_order_status = $this->request->get['filter_order_status'];
		} 
		else 
		{
			$filter_order_status = null;
		}

		if (isset($this->request->get['filter_total'])) 
		{
			$filter_total = $this->request->get['filter_total'];
		} 
		else 
		{
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) 
		{
			$filter_date_added = $this->request->get['filter_date_added'];
		} 
		else 
		{
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) 
		{
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} 
		else 
		{
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) 
		{
			$sort = $this->request->get['sort'];
		} 
		else 
		{
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) 
		{
			$order = $this->request->get['order'];
		} 
		else 
		{
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) 
		{
			$page = $this->request->get['page'];
		} 
		else 
		{
			$page = 1;
		}

		$data['products'] = array();

		
		$filter_data = array(
			'filter_user_id' => $filter_user_id,
			'filter_telephone_id' => $filter_telephone_id,
			'filter_order_id'      => $filter_order_id,
			'filter_customer'      => $filter_customer,
			'filter_order_status'  => $filter_order_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);


		$this->adminmodel('sale/order');
		
		$log->write('before call to getOrders');
		$results = $this->model_sale_order->getOrders($filter_data);
		$log->write($results);
		foreach ($results as $result) 
		{
            if(empty($result['telephone']))
			{
				$result['telephone']="0";
			}

			$data['products'][] = array(
				'order_id'      =>$mcrypt->encrypt( $result['order_id']),
				'customer'      =>$mcrypt->encrypt( $result['customer']),
				'status'        =>$mcrypt->encrypt( $result['status']),
				'invoice_no'	=>$mcrypt->encrypt( $result['invoice_no']),
				'total'         => $mcrypt->encrypt($this->currency->format($result['total'], $result['currency_code'], $result['currency_value'])),
				'date_added'    =>$mcrypt->encrypt( date($this->language->get('date_format_short'), strtotime($result['date_added']))),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $mcrypt->encrypt($result['shipping_code']),
				'telephone'	=> $mcrypt->encrypt($result['telephone']),
				'pay'	=> $mcrypt->encrypt($result['payment_method'])							
			);
		}

		//$log->write($data);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

//cart
	public function addToCart($pid,$qnty) {
               
$log=new Log("order.log");
$log->write("add to cart");
$this->request->post['product_id']=$pid;
$this->request->post['quantity']=$qnty;
$log->write($this->request->post);
		$json = array();
                
		$this->load->library('user');
                $this->user = new User($this->registry);

                $this->load->library('customer');
                $this->customer = new Customer($this->registry);
                
		//$log->write($this->config);
		$log->write($this->config->get('config_country_id'));
		$log->write( $this->config->get('config_zone_id'));
		$log->write("tax init");
                $this->load->library('tax');//
                $this->tax = new Tax($this->registry);
            	$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

                $this->load->library('pos_cart');//
                $this->cart = new Pos_cart($this->registry);
                
		$this->load->model('catalog/product');
                
                if (isset($this->request->post['product_id'])) {
                    $product_id = $this->request->post['product_id'];
                } else {
                    $product_id = 0;
                }
$log->write("add to cart ".$product_id);
                        
		$product_info = $this->model_catalog_product->getProduct($product_id);
$log->write($product_info);

		if ($product_info) {			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();	
			}

			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf('%s field required', $product_option['name']);
				}
			}

			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
$log->write($json);
				// Totals
				$this->adminmodel('pos/extension');
				$total_data = array();					
				$total = 0;
$log->write($total);
				$log->write($this->session->data);
				$taxes = $this->cart->getTaxes();

$log->write($taxes);
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$sort_order = array(); 
					$results = $this->model_pos_extension->getExtensions('total');
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}
					array_multisort($sort_order, SORT_ASC, $results);
					foreach ($results as $result) {

					$log->write($result['code'] . '_status');
					$log->write($this->config->get($result['code'] . '_status'));	
						
						if ($this->config->get($result['code'] . '_status')) {
							$this->adminmodel('pos/' . $result['code']);

							$this->{'model_pos_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
						$sort_order = array(); 
						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}
						array_multisort($sort_order, SORT_ASC, $total_data);			
					}
				}
                                
                                $json['total_data'] = $total_data;
				$json['total'] = $this->currency->format($total);
			} 
		}
$log->write($json);

return 		$json;

}

}


class AsyncOperation extends Thread {

    public function __construct($mobile,$order_id,$store_id,$products) {
        $this->mobile = $mobile;
        $this->order_id = $order_id;
        $this->store_id = $store_id;
        $this->products = $products;

    }

    public function run() {

	$log=new Log("recharge-".date('Y-m-d').".log");
		 $mcrypt=new MCrypt();
	$log->write('come in run at thread'); 
	$log->write($this->mobile."&&".$this->order_id."&&".$this->store_id."&&");
	$log->write($this->products);
	if (($this->mobile) && ($this->order_id) && ($this->store_id) && ($this->products)) 
	{


	       	$request = "https://unnati.world/shop/index.php?route=mpos/recharge/recharge&mobile=".$this->mobile."&order_id=".$this->order_id."&store_id=".$this->store_id;
		$log->write($request);
		$fields_string .= 'products'.'='.$mcrypt->encrypt(json_encode($this->products,true)).'&'; 
		rtrim($fields_string, '&');
		$log->write($fields_string);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);  
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
		$json =curl_exec($ch);
		curl_close($ch); 
		$log->write($json);
        }	

    } 
}


class AsyncOperationSeasionalRecharge extends Thread {

    public function __construct($mobile,$muid,$scheme,$order_id,$store_id) {
        $this->mobile = $mobile;
        $this->muid = $muid;
        $this->scheme = $scheme;
        $this->order_id = $order_id;
        $this->store_id = $store_id;
        

    }

    public function run() {

	$log=new Log("recharge-".date('Y-m-d').".log");
		 $mcrypt=new MCrypt();
	$log->write('come in run at thread');  
	$log->write($this->mobile."&&".$this->muid."&&".$this->scheme);
	$log->write($this->products);
	if (($this->mobile)  && ($this->scheme) ) 
	{

		
	       	$request = "https://unnati.world/shop/index.php?route=mpos/recharge/rechargetest&mobile=".$this->mobile."&muid=".$this->muid."&scheme_id=".$this->scheme."&order_id=".$this->order_id."&store_id=".$this->store_id;
		$log->write($request);
		//$fields_string .= 'products'.'='.$mcrypt->encrypt(json_encode($this->products,true)).'&'; 
		rtrim($fields_string, '&');
		$log->write($fields_string);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);  
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);    
		$json =curl_exec($ch);
		curl_close($ch); 
		$log->write($json);
        } 	

    }    
} 