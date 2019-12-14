<?php
class Controllermposce extends Controller{

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
/*

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
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
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
			'filter_store' => $filter_store,
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
*/
public function ceactivity() {

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
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
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
                                          'username'	     => $username,
			
			'start'                  =>$start,
			'limit'                  => $this->config->get('config_limit_admin')
		);
                            $log->write($filter_data);
		$data['results'] = array();

		$results = $this->model_runner_cash->getCash_report_Accepted_Rejected_by_ce($filter_data);
                            
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
public function cemydeposit() {

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
                            $this->adminmodel('cash/verify');
		$this->adminmodel('runner/cash');
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			
                                          'filter_user'	     => $username,
			'start'                  => $start,
			'limit'                  => $this->config->get('config_limit_admin')
		);
                
                            

		$data['results'] = array();

		$results = $this->model_cash_verify->getCash_reportByRunner($filter_data);
                           
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				
				
				'amount'      => $mcrypt->encrypt($result['amount']),
				'bank'     => $mcrypt->encrypt($result['bank']),
                             		'branch'      => $mcrypt->encrypt($result['branch']),
                                		'deposit_date'=>  $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['deposit_date']))),   
		    		
                                		'submit_date'   => $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['deposit_date']))),
                                		'runner_name'   => $mcrypt->encrypt($result['firstname']." ".$result['lastname']),
                                		'transaction_number'   => $mcrypt->encrypt($result['transaction_number']),
                               		 'remarks'   => $mcrypt->encrypt($result['remarks']),
				
				'status' => $mcrypt->encrypt($result["status"]),
				'runner_id'=> $mcrypt->encrypt($result["user_id"]),
                            		'SID'=> $mcrypt->encrypt($result["SID"])
                                
			);
		}
                            
		
                            $data["cash"] = $mcrypt->encrypt($this->currency->format($this->model_runner_cash->get_runner_accepted_Cash($username), $this->config->get('config_currency')));
                            $log->write($data);
		$this->response->setOutput(json_encode($data));
	}
public function getallce() {

                           $mcrypt=new MCrypt();
                          $log=new Log("ce-".date('Y-m-d').".log"); 

	              if (isset($this->request->post['filter_store'])) {
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
		} else {
			$filter_store = 8;
		}

		$log->write($this->request->post['filter_store']);
                $log->write($filter_store);
                            $this->adminmodel('runner/cash');
		
		$filter_data = array(
			
                        'filter_user_group'  =>'22'
			
		);

		$data['results'] = array();
		
		$results = $this->model_runner_cash->getAllCe($filter_data);
                             $log->write($results);
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'user_id' => $mcrypt->encrypt($result['user_id']),
				'name'   => $mcrypt->encrypt($result['firstname']." ".$result['lastname'])
				
			);
		}


		$this->response->setOutput(json_encode($data));
	}
             public function cash_deposit() {
             
             $this->adminmodel('runner/cash');
              $mcrypt=new MCrypt();
             if ($this->request->server['REQUEST_METHOD'] == 'POST')
             {   
		 $log=new Log("ce-".date('Y-m-d').".log");
                 $log->write($this->request->post);
              
                 if($this->request->post['logged_user']!="")
                 {
                   $this->request->post["logged_user"]=$mcrypt->decrypt($this->request->post['logged_user']);
                 }
                 else
                 {
                   $this->request->post['logged_user']="";
                 }
                 if($this->request->post['deposit_amount']!="")
                 {
                   $this->request->post["deposit_amount"]=$mcrypt->decrypt($this->request->post['deposit_amount']);
                 }
                 else
                 {
                   $this->request->post['deposit_amount']="";
                 }
                 if($this->request->post['filter_bank']!="")
                 {
                   $this->request->post["filter_bank"]=$mcrypt->decrypt($this->request->post['filter_bank']);
                 }
                 else
                 {
                   $this->request->post['filter_bank']="";
                 }
                 if($this->request->post['deposit_date']!="")
                 {
                   $this->request->post["deposit_date"]=$mcrypt->decrypt($this->request->post['deposit_date']);
                 }
                 else
                 {
                   $this->request->post['deposit_date']="";
                 }
                 if($this->request->post['transaction_number']!="")
                 {
                   $this->request->post['transaction_number']=$mcrypt->decrypt($this->request->post['transaction_number']);
                 }
                 else
                 {
                   $this->request->post['transaction_number']="";
                 }
                 if($this->request->post["branch"]!="")
                 {
                   $this->request->post["branch"]=$mcrypt->decrypt($this->request->post['branch']);
                 }
                 else
                 {
                   $this->request->post["branch"]="";
                 }             
                 if($this->request->post["deposit_by"]!="")
                 {
                   $this->request->post["deposit_by"]=$mcrypt->decrypt($this->request->post['deposit_by']);
                 }
                 else
                 {
                   $this->request->post["deposit_by"]="";
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

                 $this->model_runner_cash->deposit_cash($this->request->post);
                
                 $this->response->setOutput(json_encode(1));
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
                          $log=new Log("ce-".date('Y-m-d').".log"); 

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
	
	/////////////////letter//////////////////
	public function tagbill() 
	{

        $mcrypt=new MCrypt();
                           
        $log=new Log("runner-letter-".date('Y-m-d').".log");
		$log->write('taggbill called');
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
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
		} else {
			$filter_store = 0;
		}
        if (isset($this->request->post['username'])) {
			$username = $mcrypt->decrypt($this->request->post['username']); 
		} else {
			$username = 0;
		}
		if (isset($this->request->post['type'])) {
			$type = $mcrypt->decrypt($this->request->post['type']); 
		} else {
			$type = 'pending';
		}
         
		if (isset($this->request->post['start'])) {
			$start  = $mcrypt->decrypt($this->request->post['start']); 
		} else {
			$start  = 0;
		}
                            
		
		$this->adminmodel('runner/cash');
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store' => $filter_store,
			'type' => $type,
			'start'                  =>$start,
			'limit'                  => $this->config->get('config_limit_admin')
		);
        $log->write($filter_data);
		$data['results'] = array();

		$results = $this->model_runner_cash->getTagReport($filter_data);
                            $log->write($results);
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'SIID' => $mcrypt->encrypt($result['transid']),
				'amount'   => $mcrypt->encrypt($result['amount']),
				'store_id'      => $mcrypt->encrypt($result['store_id']),
				'name'     => $mcrypt->encrypt($result['store_name']),
                'date_added'=>  $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['date_added']))),  
				'submission_date'=>  $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['date_updated']))),
				'bill_date'=>  $mcrypt->encrypt(date('Y-m-d',strtotime($result['bill_date']))),  
				'bank_name'      => $mcrypt->encrypt($result['bank_name']),
				'status'              => $mcrypt->encrypt($result['status']),
				'tr_id'                => $mcrypt->encrypt($result['transid']),
				'letter_number'=>$mcrypt->encrypt($result['tagged_letter_number'])
			);
		}
 
                $data["cash"] = $mcrypt->encrypt($this->currency->format($this->model_runner_cash->get_runner_accepted_Cash($username), $this->config->get('config_currency')));
                $log->write($data);
		$this->response->setOutput(json_encode($data));
	}
	public function accept_tag_bill()
	{
        $this->adminmodel('runner/cash');
             	    
		$mcrypt=new MCrypt();
      
        $log=new Log("runner-letter-".date('Y-m-d').".log");
        $log->write('accept_tag_bill called');                
		$log->write( $this->request->post);
        if (($this->request->post['tr_id'] != '') && ($this->request->post['logged_user'] != ''))
        { 
		 
            $tr_id=$mcrypt->decrypt($this->request->post['tr_id']); 
			$logged_user=$mcrypt->decrypt($this->request->post['logged_user']); 
            $action=1; 
            if($action=="1")////////accept
            {
					$path = DIR_UPLOAD."tagslips/"; 
					$log->write(@$path);
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
						$log->write('if file name is empty');
						if(in_array($file_ext, $file_extensions)) 
						{
							
							$log->write('if file ext is allowed');
							if(!is_writable($path))
							{
								$log->write('Oops ! directory not writable, please try again.');
								$this->response->setOutput(0);
								exit;
							}
							
							$new_file_name=$mcrypt->decrypt($this->request->post['tr_id']).'_'.date('dmy')."_".date('his').".".$file_ext;
							$file_path=$path.$new_file_name;
							$move= move_uploaded_file($file_tmp,$file_path);
							if($move)
							{
								$amount=$this->request->get['amount'];
								if(empty($amount))
								{
									$amount=$this->request->post['amount'];
								}
                       
								//$status_amount=$this->model_runner_cash->accept_reject_cash($tr_id,$logged_user,"1",$new_file_name);
								$status_amount=$this->model_runner_cash->accept_tag_bill($tr_id,$logged_user,"1",$new_file_name);
								$log->write($status_amount);
								if($status_amount>0)
								{
									$this->model_runner_cash->add_to_trans_table($tr_id,$logged_user,"CR",$amount);
									$this->response->setOutput(1);
					
								}
								else
								{
									$this->response->setOutput(0);
								}
								
								
							}
							else
							{
								$log->write('Oops ! Some error in move_uploaded_file, please try again.');
								$this->response->setOutput(0);
								exit;
							}
						}
						else
						{
							$log->write('if file ext is not allowed');
							$this->response->setOutput(0);
							exit;
						}
					}//////////if file name not empty end here///
					else
					{
						$log->write('in else file name is empty');
						$status_amount=$this->model_runner_cash->accept_tag_bill($tr_id,$logged_user,"1",'');
						$log->write($status_amount);
						if($status_amount>0)
						{
							$this->model_runner_cash->add_to_trans_table($tr_id,$logged_user,"CR",$amount);
							$this->response->setOutput(1);
						}
						else
						{
							$this->response->setOutput(0);
						}
						
					}
                       
                
            }/////if action is 1 end here
                       
		}
	}
        ///////////////cash///////////////
        public function index() {

                           $mcrypt=new MCrypt();
                           
                            $log=new Log("cash-new".date('Y-m-d').".log");
		
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
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
		} else {
			$filter_store = 0;
		}
                	if (isset($this->request->post['username'])) {
			$username = $mcrypt->decrypt($this->request->post['username']); 
		} else {
			$username = 0;
		}
         		
		if (isset($this->request->post['type'])) {
			$type = $mcrypt->decrypt($this->request->post['type']); 
		} else {
			$type = 'received';
		}
		if (isset($this->request->post['start'])) {
			$start  = $mcrypt->decrypt($this->request->post['start']); 
		} else {
			$start  = 0;
		}
                            
		

                            $this->adminmodel('runner/cash');
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_store' => $filter_store,
			'type'=>$type,
			'filter_ce_id'=>$username,
			'start'                  =>$start,
			'limit'                  => $this->config->get('config_limit_admin')
		);
		//
        $log->write($filter_data);
		$data['results'] = array();
		$log->write('1');
		$results = $this->model_runner_cash->getCash($filter_data);
         $log->write('2');                   
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'SIID' => $mcrypt->encrypt($result['transid']),
				'amount'   => $mcrypt->encrypt($result['amount']),
				'store_id'      => $mcrypt->encrypt($result['store_id']),
				'name'     => $mcrypt->encrypt($result['store_name']),
                                                        'date_added'=>  $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['date_added']))),  
				'bill_date'=>  $mcrypt->encrypt(date('Y-m-d',strtotime($result['bill_date']))),  
				'bank_name'      => $mcrypt->encrypt($result['bank_name']),
				'status'              => $mcrypt->encrypt($result['status']),
				'tr_id'                => $mcrypt->encrypt($result['transid']),
				'letter_number'=>$mcrypt->encrypt($result['transid'])
			);
		}
 
                $data["cash"] = $mcrypt->encrypt($this->currency->format($this->model_runner_cash->get_runner_accepted_Cash($username), $this->config->get('config_currency')));
                $log->write($data);
		$this->response->setOutput(json_encode($data));
	}
        ////////////
    public function accept_reject_cash()
	{
        $this->adminmodel('runner/cash');
             	    
		$mcrypt=new MCrypt();
        $mcrypt=new MCrypt();
        $log=new Log("cash-new".date('Y-m-d').".log");
                           
		$log->write($this->request->post);
        $log->write($this->request->get);
		$log->write(@$_FILES);
		
        if (($this->request->post['tr_id'] != '') && ($this->request->post['logged_user'] != ''))
        { 
		 
            $tr_id=$mcrypt->decrypt($this->request->post['tr_id']); 
			$logged_user=$mcrypt->decrypt($this->request->post['logged_user']); 
            $action=$mcrypt->decrypt($this->request->post['action']); 
            $bankId=$mcrypt->decrypt($this->request->post['bankId']); 
            if($action=="1")////////accept
            {
					
					$path = DIR_UPLOAD."cashslips/"; 
					$log->write(@$path);
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
                    
							if(!is_writable($path))
							{
								$log->write('Oops ! directory not writable, please try again.');
								$this->response->setOutput(0);
								exit;
							}
							
							$new_file_name=$mcrypt->decrypt($this->request->post['tr_id']).'_'.date('dmy')."_".date('his').".".$file_ext;
							$file_path=$path.$new_file_name;
							$move= move_uploaded_file($file_tmp,$file_path);
							if($move)
							{
								$amount=$this->request->get['amount'];
								if(empty($amount))
								{
									$amount=$this->request->post['amount'];
								}
                       
								$status_amount=$this->model_runner_cash->accept_reject_cash($tr_id,$logged_user,"1",$new_file_name);
								$log->write($status_amount);
								if($status_amount>0)
								{
									//$this->model_runner_cash->add_to_trans_table($tr_id,$logged_user,"CR",$amount);
									//$this->model_runner_cash->add_to_runner_credit($logged_user,$amount);
								}
								$this->response->setOutput(1);
							}
							else
							{
								$log->write('Oops ! Some error in move_uploaded_file, please try again.');
								$this->response->setOutput(0);
								exit;
							}
						}
					}
                    
                   }
                   elseif($action=="2")////////reject
                   {
						$this->model_runner_cash->accept_reject_cash($tr_id,$logged_user,"2");
						$this->response->setOutput(1);
                   }
                    
		}
	}
}
?>