<?php
class Controllermposfm extends Controller{

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


	public function index() 
	{

                           $mcrypt=new MCrypt();
                           
                            $log=new Log("fm-report-".date('Y-m-d').".log");
		
                            $log->write($this->request->post);
		if (isset($this->request->post['filter_date'])) {
			$filter_date= $mcrypt->decrypt($this->request->post['filter_date']); 		
		} else {
			$filter_date = '';
		}

		
		if (isset($this->request->post['filter_store'])) {
			$filter_store =$mcrypt->decrypt($this->request->post['filter_store']); 
		} else {
			$filter_store = 0;
		}
                	if (isset($this->request->post['fmcode'])) {
			$fmcode = $mcrypt->decrypt($this->request->post['fmcode']); 
		} else {
			$fmcode = 0;
		}
         		if (isset($this->request->post['filter_report'])) {
			$filter_report = $mcrypt->decrypt($this->request->post['filter_report']); 
		} else {
			$filter_report ='ADVANCE';  //////////////  INDENT
		}
		if (isset($this->request->post['start'])) {
			$start  = $mcrypt->decrypt($this->request->post['start']); 
		} else {
			$start  = 0;
		}
                

                            $this->adminmodel('report/fmreport');
		
		$filter_data = array(
			'filter_date'	     => $filter_date,
			'filter_store'	     => $filter_store,
			'filter_report'	     => $filter_report,
			'start'                  =>$start,
			'limit'                  => $this->config->get('config_limit_admin')
		);
                            $log->write($filter_data);
		if((!empty($filter_store)) && (!empty($filter_date)))
		{
		$results=$this->model_report_fmreport->getRecords($filter_data);
                            }
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'create_date' => $mcrypt->encrypt($result['create_date']),
				'store_name'   => $mcrypt->encrypt($result['store_name']),
				'fmcode'      => $mcrypt->encrypt($result['fmcode']),
				'fmname'     => $mcrypt->encrypt($result['fmname']),
                                                        'model'=>  $mcrypt->encrypt($result['model']),   
				'qnty'      => $mcrypt->encrypt($result['qnty']),
				'ttotal'              => $mcrypt->encrypt(number_format((float)$result['ttotal'],2,'.','')),
				'cnt'                => $mcrypt->encrypt($result['cnt'])
			);
		}
 
                	$log->write($data);
		$this->response->setOutput(json_encode($data));
	}
	public function fm_login()
	{
		$log=new Log("fm_login-".date('Y-m-d').".log"); 
		$log->write('fm_login called');
		$log->write($this->request->post);
		$mcrypt=new MCrypt();
	    $this->adminmodel('pos/bcml');
		$this->adminmodel('setting/store');
		$this->request->post['fmcode']=$mcrypt->decrypt($this->request->post['fmcode']);
		$this->request->post['unitid']=$mcrypt->decrypt($this->request->post['unit_id']);
		$this->request->post['company_id']=$mcrypt->decrypt($this->request->post['company_id']);
		$log->write($this->request->post);
		$fmlist=$this->model_pos_bcml->getFM('GetFM',$this->request->post);
		$log->write($fmlist);
		$json=array();
		foreach ($fmlist as $key => $value) 
		{
  
			if(in_array($this->request->post['fmcode'], $value))
			{
				$json['FM_NAME']=$value["FM_NAME"];
				$json['FM_CODE'] = $value["FM_CODE"];
			}
			
		}
		
		
		if($json['FM_NAME']!='')
		{
			$store=$this->model_setting_store->getStoreByUnit($this->request->post['unitid']);
			$json['STORE_ID'] = $store['store_id'];
			$json['error']="";
			$json['success'] = "1";
			
		}
		else
		{
			$json['error']="Fm code not found";
			$json['success'] = "-1";
		}
		$log->write($json);
		$this->response->setOutput(json_encode($json));
	}
	public function send_sms()
	{
		$log=new Log("fm_login-".date('Y-m-d').".log"); 
		$log->write('send_sms called');
		$log->write($this->request->post);
		$mcrypt=new MCrypt();
	    $this->adminmodel('sale/order');//search invice number in delivery or advance->get otp from table then ->send otp to mobile for which invoice raised
		$order_id=$mcrypt->decrypt($this->request->post['invoice_number']);
		$log->write($order_id);
		$getdeliveryOtp=$this->model_sale_order->getdeliveryOtp($order_id);
		$getAdvancedeliveryOtp=$this->model_sale_order->getAdvancedeliveryOtp($order_id);
		if(!empty($getdeliveryOtp['otp']))
		{
			$otp=$getdeliveryOtp['otp'];
			$indent_no=$getdeliveryOtp['indent_no'];
		}
		else if(!empty($getAdvancedeliveryOtp['otp']))
		{
			$otp=$getAdvancedeliveryOtp['otp'];
			$indent_no=$getAdvancedeliveryOtp['indent_no'];
		}
		else
		{
			$otp='';
			$indent_no='';
		}
		if(!empty($otp))
		{
			$log->write('in if otp not empty');
			$log->write($otp);
			$log->write($indent_no);
			$order_data=$this->model_sale_order->getOrder($order_id);
			$log->write('after order data');
			$results=$this->model_sale_order->getOrderProducts($order_id);
			foreach ($results as $result) 
			{
				$json['products'][] = array(
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
			}
			$log->write('after product data');
			$json['mobile_number']=$mcrypt->encrypt($order_data['telephone']);
			$json['order_id']=$mcrypt->encrypt($order_id);
			$json['error']="";
			$json['success'] = "1";
			
			$this->load->library('sms');	
			$sms=new sms($this->registry);
			$data['ttp']=$otp;
			$data['rqid']=$indent_no;
			$sms->sendsms($order_data['telephone'],"5",$data);
			//$sms->sendsms(8447882446,"5",$data);
		}
		else
		{
			$json['error']="No details found !";
			$json['success'] = "-1";
		}
		$log->write($json);
		$this->response->setOutput(json_encode($json));
	}
	public function issue_consume() 
	{
		
		$log=new Log("fm-product-".date('Y-m-d').".log");
		$log->write('issue called');
		$log->write($this->request->post);
	
		$this-> adminmodel('pos/pos');
		$this-> adminmodel('fm/fm');
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
		$order_istance=$this->model_fm_fm->check_order_instance($mcrypt->decrypt($this->request->post['transid']));
		$log->write("order_istance");
		$log->write($order_istance);
		if(!empty($order_istance))//&&(!empty($order_istance['order_id'])))
		{ 
			$get_bill=$order_istance;
			$invoice_no_instance='';
			
			$log->write('order already placed for this instance '.$get_bill."-".$invoice_no_instance);
			$json['success'] = 'Success: new order placed with ID: '.$get_bill;
			$json['order_id'] = $get_bill;
			$json['invoice_no']=$invoice_no_instance;
			
			$log->write($json);
			$this->response->setOutput(json_encode($json));	
			return;
			
		}
		$this->model_fm_fm->insert_order_instance($mcrypt->decrypt($this->request->post['transid']),$mcrypt->decrypt($this->request->post['store_id']));
	}
	
	$keys = array(
		'store_id',
		
		'user_id',
		'prddtl',
		'telephone',
		'customer_mob',
		'ttp',
		'fm_code',
		'fm_name',
		'sid',
			'mpin',
			'kitdtl',
			'type'
		);
		
		
		
        foreach ($keys as $key) 
        {
           	$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
        }
		//log to system table
		
		
		$otp=$this->model_fm_fm->checkotp($this->request->post['sid']); 
		$log->write("checkotp");
		$log->write($otp);
		if($this->request->post['ttp']!=$otp['otp'])
		{
			$log->write('OTP not matched with system.');
			$json['error']="OTP not matched with system.";
			$json['success'] = "-1";
			$this->response->setOutput(json_encode($json));	
			return;
		}		
        $this->load->model('checkout/order');
		$this->load->model('account/activity');
	
		$this->request->post['storeid']=$this->request->post['store_id'];
		
		
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


    $log->write($this->request->post);
    $prds=json_decode($this->request->post[prddtl],true);
	unset($this->session->data['user_id']);
    $this->session->data['user_id']=$this->request->post['user_id'] ;
    
		$data['store_id'] = $this->request->post['store_id'];
		$data['storeid'] = $this->request->post['store_id'];
        $this->config->set('config_store_id',$data['store_id']);
		$data['store_name'] = $this->config->get('config_name'); 
		//$data['store_url'] = $this->config->get('config_url');
		
		$this->load->model('catalog/product');
		
		
		if(!empty(($this->request->post["kitdtl"])))
		{
			$log->write("in if kitdtl is not empty ");
			$kitprddtl=json_decode($this->request->post["kitdtl"],true);
			$prdstemp=json_decode($this->request->post['prddtl'],true);
			foreach($kitprddtl as $prd)
			{
				$log->write("in loop of kitdtl ");
				$combo_id=$prd['product_id'];
				$log->write($combo_id);
				$log->write($prd['product_name']);
				$comboproducts = $this->model_catalog_product->getComboProducts($prd['product_id']);
				$log->write($comboproducts);
				
				$clmmm=array_column($prdstemp,'product_kit_id');
				$log->write($clmmm);
				$counttt=array_count_values($clmmm)[$combo_id];
				$log->write('count from app');
				$log->write($counttt);
				$log->write('count from db');
				$log->write(count($comboproducts));
				if(count($comboproducts)!=((int)$counttt))
				{
					//////error product count is not same for this kit
					$log->write("Product count is not same for kit ");//+$prd['product_name']
					$json['error']="Product count is not same for kit ";//+$prd['product_name'];
	                $json['success'] = "-1";
					$this->response->setOutput(json_encode($json));	
					return;
				}
				foreach($comboproducts as $comboproduct)
				{
					$log->write("in loop of comboproduct ");
					$cquantity=$comboproduct['quantity'];
					$log->write($cquantity);
					foreach($prdstemp as $pdtemp)
					{
						$log->write("in loop of prdstemp ");
						if($pdtemp['product_id']==$comboproduct['product_id'])
						{
							$log->write("in if product_id match ");
							if($cquantity!=($pdtemp['product_quantity']/$prd['product_quantity']))
							{
								$log->write("in if quantity not match ");
								//check quantity
								$log->write("Product quantity is not match for kit ");//+$prd['product_name']
					$json['error']="Product quantity is not match kit ";//+$prd['product_name'];
	                $json['success'] = "-1";
					$this->response->setOutput(json_encode($json));	
					return;
							}
						}
						
					}
					
				}
				
			}
		}
		$data = array();
		$data['fm_name']=$this->request->post['fm_name'];
		$log->write("check for product quantity ");
        //check for product quantity
        foreach($prds as $prd)
		{
			$log->write($prd['product_id']);
            $log->write("quantity check");
			$product_info = $this->model_catalog_product->getProduct($prd['product_id']);
			$log->write($product_info['squantity']);
			if ($product_info) 
            {     
				/////////////reserved store quantity////////// 
				if( $product_info['squantity'] > 0)
				{ 
					if ($product_info['squantity'] < $prd['product_quantity']) 
					{
						$json['error']="".$prd['product_quantity']." quantity for ".$product_info['name']." not match with system";
	                	$json['success'] = "-1";
						$log->write($prd['product_quantity']." quantity for ".$product_info['name']." not match with system");
						$this->response->setOutput(json_encode($json));	
						return;
					} 

				}
				else
				{
					$json['error']="Stock is low for ".$product_info['name'];
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
				$log->write("Product not found in system");
				$this->response->setOutput(json_encode($json));	
				return;

            }
			$data['order_product'][]=array('product_id'=>$prd['product_id'],'quantity'=>$prd['product_quantity'],'name'=>$prd['product_name']);

        }
		$log->write('after product loop');
		$log->write($data['order_product']);
            $this-> adminmodel('setting/store');
			
				if(!empty($comboprds))
				{
					$data["combo_products"]=$comboprds;
					$data['combo']=1;
				}
				
                $errors = '';                
               
                if($errors != ''){                   
                    $data['errors'] = $errors;
                    $this->response->setOutput(json_encode($data));
                    return;
                }

		
		$data['store_id'] = $this->request->post['store_id'];
		$data['fm_code'] = $this->request->post['fm_code'];
		$data['telephone'] = $this->request->post['telephone'] ;
		
		$data['user_id'] = $this->request->post['user_id'];
        
                //get product list 
                $this->load->library('customer');
                $this->customer = new Customer($this->registry);
                
                $this->load->library('tax');//
                $this->tax = new Tax($this->registry);
            
                $this->load->library('pos_cart');//
                $this->cart = new Pos_cart($this->registry);
                
		//SMS LIB
		$this->load->library('sms');	
        $data['type']=$this->request->post['type'];
       
                $log->write("all data added in the array just  before the call of addorder is : ");
                $log->write($data);
			
			
			
			
                $order_id = $this->model_fm_fm->addOrder($data);
			
				
                $log->write("Order Successfully added in oc_order - ".$order_id);
		if(isset($this->request->post['transid']))
                {
                    $this->model_fm_fm->update_order_istance_order_id($mcrypt->decrypt($this->request->post['transid']),$order_id);
		}		
		
                
                $json['order_id'] = $order_id;
                $log->write("Genereted Invoice number - ".$order_id);
              
                $json['success'] = 'Success: new order placed with ID: '.$order_id;

	   

		$json['orddate'] = date('Y-m-d h:i:s A');

		$log->write("before call to get_order_total");
           
         
		$log->write('final return by addorder in order.php');
		$log->write($json);
   	            $this->response->setOutput(json_encode($json));	

	}
	
	public function fm_item_issue_report()  
	{

		$log=new Log("fm_item_issue_report-".date('Y-m-d').".log");
		$log->write("fm_item_issue_report called");
		$log->write($this->request->post);
		$log->write($this->request->get);
		$mcrypt=new MCrypt();
		if(!empty($mcrypt->decrypt($this->request->post['start_date'])))
		{
			$start_date=$mcrypt->decrypt($this->request->post['start_date']);
		}
		else
		{
			$start_date=date('Y-m-d');
		}
		//$start_date='2018-02-01';
		if(!empty($mcrypt->decrypt($this->request->post['end_date'])))
		{
			$end_date=$mcrypt->decrypt($this->request->post['end_date']);
		}
		else
		{
			$end_date=date('Y-m-d');
		}
		//$end_date='2018-03-01';
		if(!empty($mcrypt->decrypt($this->request->post['fm_code'])))
		{
			$fm_code=$mcrypt->decrypt($this->request->post['fm_code']);
		}
		
		else
		{
			$fm_code=0;
		}
		if(!empty($mcrypt->decrypt($this->request->post['store_id'])))
		{
			$store_id=$mcrypt->decrypt($this->request->post['store_id']);
		}
		
		else
		{
			$store_id=0;
		}
		if(!empty($mcrypt->decrypt($this->request->post['start'])))
		{
			$start=$mcrypt->decrypt($this->request->post['start']);
		}
		
		else
		{
			$start=0;
		}
		if($start<0)
		{
			$start=0;
		}
		$filter_data=array(
					'start_date'=>$start_date,
					'end_date'=>$end_date,
					'fm_code'=>$fm_code,
					'store_id'=>$store_id,
					'start'=>$start,
					'limit'=>20
					);
		$log->write($filter_data);
		$data['products'] = array();
		$results=array();
		$this->adminmodel('fm/fm');
		if(!empty($fm_code))
		{ 
			$results=$this->model_fm_fm->fm_item_issue_report($filter_data);
						
		}
		$total=0;
		foreach($results as $result)//$mcrypt->encrypt(
		{
			$log->write('in loop');
			$log->write($result);
			/*$total=$total+($result['quantity']*$result['price'])+($result['quantity']*$result['tax']);
			$data['products'][]=array(
							'product_name'=>$mcrypt->encrypt($result['name']),
							'product_id'=>$mcrypt->encrypt($result['product_id']),
							'quantity'=>$mcrypt->encrypt($result['quantity']),
							'date'=>$mcrypt->encrypt(date('Y-m-d',strtotime($result['ORD_DATE']))),
							'price'=>$mcrypt->encrypt($result['price']),
							'tax'=>$mcrypt->encrypt($result['tax']),
							'product_total'=>$mcrypt->encrypt(($result['quantity']*$result['price'])+($result['quantity']*$result['tax']))
							);*/
							if(empty($result['Issued'])){ $result['Issued']=0; }
							if(empty($result['Consumed'])){ $result['Consumed']=0; }
							$data['products'][]=array(
							'name'=>$mcrypt->encrypt($result['name']),
							'product_id'=>$mcrypt->encrypt($result['product_id']),
							'Issued'=>$mcrypt->encrypt($result['Issued']),
							'Consumed'=>$mcrypt->encrypt($result['Consumed']),
							'balance'=>$mcrypt->encrypt($result['Issued']-$result['Consumed'])
							
							);
		}
		//$data['total']=$total;
		$log->write($data);
		$this->response->setOutput(json_encode($data));
		
	}
}
?>