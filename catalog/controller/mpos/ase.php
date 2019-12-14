<?php
class Controllermposase extends Controller{

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

/*public function upsub() {

                           $mcrypt=new MCrypt();                           
                            $log=new Log("sub-data-update-".date('Y-m-d').".log");
$log->write("in upsub");
			$this->load->model('checkout/order');
$order_info = $this->model_checkout_order->getsub();
foreach($order_info as $ord)
{	
$log->write($ord);
$this->model_checkout_order->setsub($mcrypt->decrypt($ord['shipping_firstname']),$ord['order_id']);
$log->write("sub");
}

		$this->response->setOutput("1");

}
*/


public function index() {

                           $mcrypt=new MCrypt();
                           
                            $log=new Log("ce-".date('Y-m-d').".log");
		
                            $log->write($this->request->post);
		if (isset($this->request->post['filter_date_start'])) {
			$filter_date_start = $mcrypt->decrypt($this->request->post['filter_date_start']); 		
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->post['filter_date_end'])) {
			$filter_date_end = $mcrypt->decrypt($this->request->post['filter_date_end']); 
		} else {
			$filter_date_end = '';
		}
		if (isset($this->request->post['filter_store'])) {
			$filter_store = 0;//$mcrypt->decrypt($this->request->post['filter_store']); 
		} else {
			$filter_store = 0;
		}
                if (isset($this->request->post['username'])) {
			$username = $mcrypt->decrypt($this->request->post['username']); 
		} else {
			$username = 0;
		}
         
		if (isset($this->request->post['start'])) {
			$start  = $mcrypt->decrypt($this->request->post['start']); 
		} else {
			$start  = 0;
		}
                            
		$log->write($this->request->post);

                            $this->adminmodel('runner/cash');
		//'filter_store' => $filter_store,
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			
			'start'                  =>$start,
			'limit'                  => $this->config->get('config_limit_admin')
		);
                            $log->write($filter_data);
		$data['results'] = array();

		$results = $this->model_runner_cash->getCash_reportPending($filter_data);
                            
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'SIID' => $mcrypt->encrypt($result['transid']),
				'amount'   => $mcrypt->encrypt($result['amount']),
				'store_id'      => $mcrypt->encrypt($result['store_id']),
				'name'     => $mcrypt->encrypt($result['name']),
                                                        'date_added'=>  $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['date_added']))),   
				'bank_name'      => $mcrypt->encrypt($result['bank_name']),
				'status'              => $mcrypt->encrypt($result['status']),
				'tr_id'                => $mcrypt->encrypt($result['transid'])
			);
		}
 
                $data["cash"] = $mcrypt->encrypt($this->currency->format($this->model_runner_cash->get_runner_accepted_Cash($username), $this->config->get('config_currency')));
                $log->write($data);
		$this->response->setOutput(json_encode($data));
	}

public function getallvillages() {

                           $mcrypt=new MCrypt();
                           

	              if (isset($this->request->post['filter_store'])) {
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
		} else {
			$filter_store = 8;
		}

		
                            $this->adminmodel('ase/ase');
		
		$filter_data = array(
			'filter_store'	     => $filter_store
                                          
			
		);

		$data['results'] = array();
		$log=new Log("ase-".date('Y-m-d').".log");
		$results = $this->model_ase_ase->getAllVillages($filter_data);
                             $log->write($results);
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'village_id' => $mcrypt->encrypt($result['SID']),
				'village_name'   => $mcrypt->encrypt(strtoupper($result['village_name']))
				
			);
		}


		$this->response->setOutput(json_encode($data));
	}
             public function village_vissit_submit() {
             
             $this->adminmodel('ase/ase');
              $mcrypt=new MCrypt();
             if ($this->request->server['REQUEST_METHOD'] == 'POST')
             {    
                 $log=new Log("ase-".date('Y-m-d').".log");
                 $log->write($this->request->post);
              
                 if($this->request->post['ase_id']!="")
                 {
                   $this->request->post["ase_id"]=$mcrypt->decrypt($this->request->post['ase_id']);
                 }
                 else
                 {
                   $this->request->post['ase_id']="";
                 }
                 if($this->request->post['village_id']!="")
                 {
                   $this->request->post["village_id"]=$mcrypt->decrypt($this->request->post['village_id']);
                 }
                 else
                 {
                   $this->request->post['village_id']="";
                 }
                 if($this->request->post['farmer_count']!="")
                 {
                   $this->request->post["farmer_count"]=$mcrypt->decrypt($this->request->post['farmer_count']);
                 }
                 else
                 {
                   $this->request->post['farmer_count']="";
                 }
                 
                 if($this->request->post["remarks"]!="")
                 {
                   $this->request->post["remarks"]=$mcrypt->decrypt($this->request->post['remarks']);
                 }
                 else
                 {
                   $this->request->post["remarks"]="";
                 } 
                 if($this->request->post["store_id"]!="")
                 {
                   $this->request->post["store_id"]=$mcrypt->decrypt($this->request->post['store_id']);
                 }
                 else
                 {
                   $this->request->post["store_id"]="";
                 } 
		 if($this->request->post['farmerName']!="")
                 {
                   $this->request->post["farmerName"]=$mcrypt->decrypt($this->request->post['farmerName']);
                 }
                 else
                 {
                   $this->request->post['farmerName']="";
                 }
				 if($this->request->post['farmerMobile']!="")
                 {
                   $this->request->post["farmerMobile"]=$mcrypt->decrypt($this->request->post['farmerMobile']);
                 }
                 else
                 {
                   $this->request->post['farmerMobile']="";
                 }
                 $log->write($this->request->post);

                 
                 $log->write($this->request->post);
                 $insertid=$this->model_ase_ase->village_visit_submit($this->request->post);
                 
	   $log->write( $insertid);
                 $this->response->setOutput($insertid);
                 //$this->response->setOutput(json_encode(1));
             }
        }
             public function accept_reject_cash()
	{
                  $this->adminmodel('runner/cash');
             	    
                  $mcrypt=new MCrypt();
                           

                  if (($this->request->post['tr_id'] != '') && ($this->request->post['logged_user'] != ''))
                  { 
		 
                   $tr_id=$mcrypt->decrypt($this->request->post['tr_id']); 
	           $logged_user=$mcrypt->decrypt($this->request->post['logged_user']); 
                   $action=$mcrypt->decrypt($this->request->post['action']); 
                   if($action=="1")////////accept
                   {
                       $amount=$this->request->get['amount'];
                       if(empty($amount))
                       {
			$amount=$this->request->post['amount'];
                       }
                       $log=new Log("ce-".date('Y-m-d').".log");
                       $log->write($this->request->post);
                       $log->write($this->request->get);

                       $amount=$this->model_runner_cash->accept_reject_cash($tr_id,$logged_user,"1");
                       $this->model_runner_cash->add_to_trans_table($tr_id,$logged_user,"CR",$amount);
	               $this->model_runner_cash->add_to_runner_credit($logged_user,$amount);
                       $this->response->setOutput(1);
                   }
                   elseif($action=="2")////////reject
                   {
                      $this->model_runner_cash->accept_reject_cash($tr_id,$logged_user,"2");
                      $this->response->setOutput(1);
                   }
                    
	}
         }
          public function billsubmission()
         {
             $this->adminmodel('tag/order');
             $mcrypt=new MCrypt();
             $log=new Log("tag-".date('Y-m-d').".log");
	   

            if ($this->request->server['REQUEST_METHOD'] == 'POST')
            { 
                 $log->write($this->request->post);
                 if($this->request->post["logged_user"]!="")
                 {
                   $this->request->post["logged_user"]=$mcrypt->decrypt($this->request->post['logged_user']);
                 }
                 else
                 {
                   $this->request->post["logged_user"]="";
                 } 
                  if($this->request->post["submission_date"]!="")
                 {
                   $this->request->post["submission_date"]=$mcrypt->decrypt($this->request->post['submission_date']);
                 }
                 else
                 {
                   $this->request->post["submission_date"]="";
                 } 
                 
                  if($this->request->post["filter_unit"]!="")
                 {
                   $this->request->post["filter_unit"]=$mcrypt->decrypt($this->request->post['filter_unit']);
                 }
                 else
                 {
                   $this->request->post["filter_unit"]="";
                 } 
                  if($this->request->post["filter_store"]!="")
                 {
                   $this->request->post["filter_store"]=$mcrypt->decrypt($this->request->post['filter_store']);
                 }
                 else
                 {
                   $this->request->post["filter_store"]="";
                 } 
                  if($this->request->post["period_date_start"]!="")
                 {
                   $this->request->post["period_date_start"]=$mcrypt->decrypt($this->request->post['period_date_start']);
                 }
                 else
                 {
                   $this->request->post["period_date_start"]="";
                 } 
                  if($this->request->post["period_date_end"]!="")
                 {
                   $this->request->post["period_date_end"]=$mcrypt->decrypt($this->request->post['period_date_end']);
                 }
                 else
                 {
                   $this->request->post["period_date_end"]="";
                 } 
                  if($this->request->post["amount"]!="")
                 {
                   $this->request->post["amount"]=$mcrypt->decrypt($this->request->post['amount']);
                 }
                 else
                 {
                   $this->request->post["amount"]="";
                 } 
                 
                 if($this->request->post["remarks"]!="")
                 {
                   $this->request->post["remarks"]=$mcrypt->decrypt($this->request->post['remarks']);
                 }
                 else
                 {
                   $this->request->post["remarks"]="";
                 } 
                 $log->write($this->request->post);
                 $insertid=$this->model_tag_order->billsubmmision($this->request->post,'');	
                 
	         $log->write( $insertid);
                 $this->response->setOutput($insertid);

     
            }

         }
public function getcashposition() {

                          $mcrypt=new MCrypt();
                          $log=new Log("ase-".date('Y-m-d').".log"); 

	              if (isset($this->request->post['filter_store'])) {
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
		} else {
			$filter_store = 0;
		}

		$log->write($this->request->post['filter_store']);
                            $log->write($filter_store); 
                            $this->adminmodel('report/cash');
		
		$filter_data = array(
			'filter_store'	     => $filter_store
                       
			
		);

		$data['results'] = array();
		
		$results = $this->model_report_cash->getCash_position($filter_data);
                            $log->write($results);
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(

                                                        'amount'   => $mcrypt->encrypt($result['amount']),
                                                        'user'   => $mcrypt->encrypt($result['firstname']." ".$result['lastname']),
				'store_id'      => $mcrypt->encrypt($result['store_id']),
				'store_name'     => $mcrypt->encrypt($result['store_name'])

				
				
			);
		}


		$this->response->setOutput(json_encode($data));
	}

public function setpoint()
{
 	             $mcrypt=new MCrypt();
                           $log=new Log("ase-".date('Y-m-d').".log"); 

	              if (isset($this->request->post['logged_user'])) {
			$logged_user = $mcrypt->decrypt($this->request->post['logged_user']); 
		} else {
			$logged_user = 0;
		}
		if (isset($this->request->post['lat'])) {
			$lat = $mcrypt->decrypt($this->request->post['lat']); 
		} else {
			$lat = 0;
		}
		if (isset($this->request->post['lng'])) {
			$lng = $mcrypt->decrypt($this->request->post['lng']); 
		} else {
			$lng = 0;
		}

                            $log->write($filter_store); 
                            $this->adminmodel('ase/ase');
		
		$results = $this->model_ase_ase->setpoint($logged_user,$lat,$lng); 
		$this->response->setOutput($mcrypt->encrypt($results));

}
public function getmyorders() {

                           $mcrypt=new MCrypt();
                           

	              if (isset($this->request->post['ase_id'])) {
			$ase_id = $mcrypt->decrypt($this->request->post['ase_id']); 
		} else {
			$ase_id = NULL; 
		}
		if (isset($this->request->post['status'])) {
			$status = $this->request->post['status']; 
		} else {
			$status = NULL; 
		}
		if (isset($this->request->post['start'])) {
			$start = $mcrypt->decrypt($this->request->post['start']); 
		} else {
			$start = 0;
		}
                            $this->adminmodel('ase/ase');
		
		$filter_data = array(
			'ase_id'	     => $ase_id,
                                          'status'	     => $status,
			'start'	=>$start,
			'limit'=>$this->config->get('config_limit_admin')
			
		);

		$data['results'] = array();
		$log=new Log("ase-".date('Y-m-d').".log");
		$results = $this->model_ase_ase->getmyorders($filter_data);
                             $log->write($results);
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'order_id' => $mcrypt->encrypt($result['order_id']),
				'total'   => $mcrypt->encrypt($result['total']),
				'store_name'   => $mcrypt->encrypt($result['store_name']),
				'date_added'   => $mcrypt->encrypt(date('d M Y h:i:A',strtotime($result['date_added']))),
				'order_status_id'   => $mcrypt->encrypt($result['order_status_id']),
                                                        'telephone' => $mcrypt->encrypt($result['telephone'])
				
			);
		}


		$this->response->setOutput(json_encode($data));
	}

	public function getorderdetails() {  

                           $mcrypt=new MCrypt();
                          $log=new Log("ase-".date('Y-m-d').".log"); 

	              if (isset($this->request->post['store_id'])) {
			$store_id = $mcrypt->decrypt($this->request->post['store_id']); 
		} else {
			$store_id = '';
		}
		if (isset($this->request->post['order_id'])) {
			$order_id = $mcrypt->decrypt($this->request->post['order_id']); 
		} else {
			$order_id = '';
		}
		if (isset($this->request->post['start'])) {
			$start = $mcrypt->decrypt($this->request->post['start']); 
		} else {
			$start = 0;
		}
		$log->write($this->request->post);
                           
                            $this->adminmodel('ase/ase');
		
		$filter_data = array(
			'store_id'	     => $store_id,
                       		'order_id'	     => $order_id,
			'start'	=>$start,
			'limit'=>$this->config->get('config_limit_admin')
			
		);
		$log->write($filter_data);
		$data['results'] = array();
		$this->adminmodel('sale/orderleads');
		$results = $this->model_ase_ase->getorderdetails($filter_data);
                            $log->write($results);
		
		foreach ($results as $result) {  
                	$products=array();         
		$stax=$this->model_sale_orderleads->getorderTaxvalue($result['order_id']);
		$tax=$mcrypt->encrypt(empty($stax)? 0:$stax);
		$subtotal=$mcrypt->encrypt($this->model_sale_orderleads->getorderSubTotalvalue($result['order_id']));

		$results_prd = $this->model_sale_orderleads->getOrderProducts($result['order_id']);

		foreach ($results_prd as $resultp) {
		//if($resultp['tax']=="") { $ttax="0"; } else { $ttax=$resultp['tax']; }
		$log->write($resultp);
			$products[] = array(
				'order_product_id'      =>$mcrypt->encrypt( $resultp['order_product_id']),
				'product_id'      =>$mcrypt->encrypt( $resultp['product_id']),
				'name'        =>$mcrypt->encrypt( $resultp['name']),
				'model'         => $mcrypt->encrypt($resultp['model']),
				'quantity'    =>$mcrypt->encrypt( $resultp['quantity']),
				'price' => $mcrypt->encrypt( ($resultp['price']) ),
				'total' => $mcrypt->encrypt(($resultp['total'])+(($resultp['tax'])*$resultp['quantity'])),
				'tax'	=> $mcrypt->encrypt( empty($resultp['tax'])? 0:$resultp['tax']),			
				'subsidy'		=> $mcrypt->encrypt( empty($resultp['subsidy'])? 0:$resultp['subsidy'])
			
			);
		
		}

			         $data['results'][] = array( 

                                                        'telephone'   => $mcrypt->encrypt($result['telephone']),
                                                        'date_added'   => $mcrypt->encrypt(date('Y-m-d',strtotime($result['date_added'])) ),
				'order_id'      => $mcrypt->encrypt($result['order_id']),
				'total'     => $mcrypt->encrypt($result['total']),
				'ase_name'     => $mcrypt->encrypt($result['ase_name']),
				'products'=>$products, 
				'tax'=>$tax,
				'subtotal'=>$subtotal
				
				
			);


		}
	

		$this->response->setOutput(json_encode($data));
	}
	public function getasestores() {

        $mcrypt=new MCrypt();
        $log=new Log("ase-".date('Y-m-d').".log"); 

	    if (isset($this->request->post['user_id'])) {
			$user_id = $mcrypt->decrypt($this->request->post['user_id']); 
		} else {
			$user_id = 0;
		}

		$log->write($this->request->post['user_id']);
        $log->write($user_id); 
        $this->adminmodel('ase/ase');
		
		$filter_data = array(
			'user_id'	     => $user_id
                       
			
		);

		$data['results'] = array(); 
		
		$results = $this->model_ase_ase->getasestores($filter_data);
        $log->write($results);
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'store_id'      => $mcrypt->encrypt($result['store_id']),
				'store_name'     => $mcrypt->encrypt($result['store_name'])	
			);
		}


		$this->response->setOutput(json_encode($data));
	}

}
?>