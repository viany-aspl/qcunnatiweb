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
			'username'=>$username,
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
	
        ///////////////cash///////////////
        function getrunnerunitlist()
	{ 
		$log=new Log("runner-letter-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write('getunitlist called');
		$log->write($this->request->post);
	
		$uid=$mcrypt->decrypt($this->request->post['username']); 
		$log->write($uid);
		$this->adminmodel('runner/cash');
		$results = $this->model_runner_cash->getrunnerunitlist($uid); 
		foreach ($results as $result)
		{ 
		
		$data['units'][] = array(
			'unit_name' => $mcrypt->encrypt($result['unit_name']),
			'unit_id' => $mcrypt->encrypt($result['unit_id'])
		); 

	}
	
	$log->write($data);
	$this->response->setOutput(json_encode($data));
	
	}
        function pendingtagbill()
{ 
	$log=new Log("runner-letter-".date('Y-m-d').".log");
	$mcrypt=new MCrypt();
	$log->write('pendingtagbill called');
	$log->write($this->request->post);

	$uid=$mcrypt->decrypt($this->request->post['username']); 
	$log->write($uid);
	if (isset($this->request->post['date'])) 
	{
		$filter_date_start =$filter_date_end= $mcrypt->decrypt($this->request->post['date']);
	} 
	else 
	{
		$filter_date_start =$filter_date_end= date('Y-m-d');
	}

	if (isset($this->request->post['store_id'])) 
	{
		$filter_store = $mcrypt->decrypt($this->request->post['store_id']);
	} 
	else 
	{
		$filter_store = 0;
	}
	if (isset($this->request->post['unit_id'])) 
	{
		$filter_unit = $mcrypt->decrypt($this->request->post['unit_id']);
	} 
	else 
	{
		$filter_unit = 0;
	}
	if (isset($this->request->post['username'])) 
	{
		$filter_username = $mcrypt->decrypt($this->request->post['username']);
	} 
	else 
	{
		$filter_username = 0;
	}
	if (isset($this->request->post['start'])) 
	{
		$start = $mcrypt->decrypt($this->request->post['start']);
	} 
	else 
	{
		$start = 0;
	}
	if (isset($this->request->post['type'])) 
	{
		$type = $mcrypt->decrypt($this->request->post['type']);
	} 
	else 
	{
		$type = 'pending';//submitted
	}
	$this->adminmodel('runner/cash');
	
	$filter_data = array(
		'filter_store' => $filter_store,
		'filter_date_start' => $filter_date_start,
		'filter_date_end' => $filter_date_end,
		'filter_unit' => $filter_unit,
		
		'type' => $type,
		'start' => $start,
		'limit' => 20
	);
	$log->write($filter_data);

	//$this->adminmodel('report/reconciliation');
	if(!empty($filter_unit))
	{
		$log->write('in if');
		$results = $this->model_runner_cash->getTaggedBillWithLetter($filter_data);
		$log->write('after results');		
	}
	//$log->write($results);
	//print_r($results);
	foreach ($results as $result)
	{ 
		$filter_data2 = array(
			'filter_store' => $filter_store,
			'filter_date_start' => $result['date_start'],
			'filter_date_end' => $result['date_start']
		);
		$total_orders = 0;//count($this->model_report_reconciliation->gettaggedOrdersletter($filter_data2));

		$data['results'][] = array(
			'letter_number' => $mcrypt->encrypt($result['sid']),
			'tr_id' => $mcrypt->encrypt($result['sid']),
			'date' => $mcrypt->encrypt(date('Y-m-d',strtotime($result['date_start']))),
			'date_display' => $mcrypt->encrypt(date('d/m/Y',strtotime($result['date_start']))),
			'number_of_orders' => $mcrypt->encrypt($total_orders),
			'tagged_amount' => $mcrypt->encrypt(number_format((float)$result['total_amount'], 0, '.', '')),
			'submit_date' => $mcrypt->encrypt($result['tagged_submit_date']),
			'runner_name' => $mcrypt->encrypt($result['runner_name']) 
		); 

	}
	//$mcrypt->encrypt(number_format((float)$result['total'], 0, '.', '')),
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
		$log->write($mcrypt->decrypt($this->request->post['tr_id']));
		 
        if (($this->request->post['tr_id'] != '') && ($this->request->post['logged_user'] != ''))
        { 
		 
            $tr_id=$mcrypt->decrypt($this->request->post['tr_id']); 
			$logged_user=$mcrypt->decrypt($this->request->post['logged_user']); 
            $action=1; 
            if($action=="1")////////accept
            {
					$path = DIR_UPLOAD."tagslips/"; 
					$log->write(@$path);
					$log->write($_FILES);
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
						$log->write('if file name is not empty');
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
							$log->write($new_file_name); 
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
								$status_amount=$this->model_runner_cash->insert_into_bank_transaction($tr_id,$logged_user,"1",$new_file_name);
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
						$status_amount=$this->model_runner_cash->insert_into_bank_transaction($tr_id,$logged_user,"1",'');
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
        ////////////
        public function index() 
	{

        $mcrypt=new MCrypt();
                           
        $log=new Log("cash-new-".date('Y-m-d').".log");
		
        $log->write($this->request->post);
		if (isset($this->request->post['filter_date_start'])) 
		{
			$filter_date_start = $mcrypt->decrypt($this->request->post['filter_date_start']); 		
		} 
		else 
		{
			$filter_date_start = '';
		}

		if (isset($this->request->post['filter_date_end'])) 
		{
			$filter_date_end = $mcrypt->decrypt($this->request->post['filter_date_end']); 
		} 
		else 
		{
			$filter_date_end = '';
		}
		if (isset($this->request->post['filter_store'])) 
		{
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']); 
		} 
		else 
		{
			$filter_store = 0;
		}
        if (isset($this->request->post['username'])) 
		{
			$username = $mcrypt->decrypt($this->request->post['username']); 
		} 
		else 
		{
			$username = 0;
		}
         		
		if (isset($this->request->post['type'])) 
		{
			$type = $mcrypt->decrypt($this->request->post['type']); 
		} 
		else 
		{
			$type = 'pending'; 
		}
		if (isset($this->request->post['start'])) 
		{
			$start  = $mcrypt->decrypt($this->request->post['start']); 
		} 
		else 
		{
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
		
        $log->write($filter_data);
		$data['results'] = array();
		
		if($type=='pending')
		{
			
			$log->write('before calling to getCash accepted by runner'); 
			$results = $this->model_runner_cash->getCash($filter_data);
			                  
			foreach ($results as $result) 
			{ 
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
		}
		else/////// 
		{
			$log->write('before calling to getCashDeposited by runner');   
			$results = $this->model_runner_cash->getCashDeposited($filter_data);
			//$log->write($results);                   
			foreach ($results as $result) 
			{ 	
				if($result['status']==0)
				{
					$status='Pending';
				}
				if($result['status']==1)
				{
					$status='Accepted';
				}
				if($result['status']==2)
				{
					$status='Rejected';
				}
				$result['status'];
			    $data['results'][] = array(
				'SIID' => $mcrypt->encrypt($result['SID']),
				'amount'   => $mcrypt->encrypt($result['amount']),
				'store_id'      => $mcrypt->encrypt('0'),
				'name'     => $mcrypt->encrypt('NA'),
                'date_added'=>  $mcrypt->encrypt(date($this->language->get('date_format_short'), strtotime($result['submit_date']))),  
				'bill_date'=>  $mcrypt->encrypt('0'),  
				'bank_name'      => $mcrypt->encrypt($result['bank_name']),
				'status'              => $mcrypt->encrypt($status),
				'tr_id'                => $mcrypt->encrypt($result['SID']),
				'letter_number'=>$mcrypt->encrypt($result['SID'])
				);
			}
		}
        $cash=$this->model_runner_cash->get_runner_accepted_Cash($username);
		$log->write($cash);
		$data["cash"] = $mcrypt->encrypt($this->currency->format($cash, $this->config->get('config_currency')));
		$deposit_cash=$this->model_runner_cash->get_runner_deposited_Cash($username);
		$log->write($deposit_cash);
		if(empty($deposit_cash))
		{
			$deposit_cash='0';
			
		}
		
		$data["deposit_cash"] = $mcrypt->encrypt($this->currency->format($deposit_cash, $this->config->get('config_currency')));
		$log->write('return data');
        $log->write($data);
		$this->response->setOutput(json_encode($data));
	}
	
	public function getcashinhand() 
	{

        $mcrypt=new MCrypt();
                           
        $log=new Log("cash-new-".date('Y-m-d').".log");
		$log->write('getcashinhand called');
        $log->write($this->request->post);
		
        if (isset($this->request->post['username'])) 
		{
			$username = $mcrypt->decrypt($this->request->post['username']); 
		} 
		else 
		{
			$username = 0;
		}
         		
		$this->adminmodel('runner/cash');
		$cash_cal=$this->model_runner_cash->get_runner_accepted_Cash($username);
        $data["cash"] = $mcrypt->encrypt($this->currency->format($cash_cal, $this->config->get('config_currency')));
		$deposit_cash=$this->model_runner_cash->get_runner_deposited_Cash($username);
		$log->write($deposit_cash);
		if(empty($deposit_cash))
		{
			$deposit_cash='0';
			
		}
		
		$log->write($deposit_cash);
		$data["deposit_cash_to_show"] = $mcrypt->encrypt($this->currency->format($deposit_cash, $this->config->get('config_currency')));
		$data["deposit_cash"] = $mcrypt->encrypt($deposit_cash);
		$data["cash_cal"] = $mcrypt->encrypt($cash_cal);
		$log->write('return by getcashinhand');
        $log->write($data);
		$this->response->setOutput(json_encode($data)); 
	}
    ////////////
    public function accept_reject_cash()
	{
        $this->adminmodel('runner/cash');
             	    
		$mcrypt=new MCrypt();
        
        $log=new Log("cash-new-".date('Y-m-d').".log");
        $log->write($this->request->post);                   
		$log->write('accept_reject_cash called in ce');
        		$log->write($this->request->post['amount']);
		$this->request->post['amount']=$mcrypt->decrypt($this->request->post['amount']);
		$log->write($this->request->post['amount']);

		if(is_numeric($this->request->post['amount']))
		{
			$this->request->post['amount']=$this->request->post['amount'];
		}
		else
		{
			$this->request->post['amount']=$mcrypt->decrypt($this->request->post['amount']);
		}
		

		$log->write($this->request->post['amount']);
		$log->write(@$_FILES);
		
        if ($this->request->post['logged_user'] != '')
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
							
							$new_file_name='cash_'.date('dmy')."_".date('his').".".$file_ext;
							$file_path=$path.$new_file_name;
							$move= move_uploaded_file($file_tmp,$file_path);
							if($move)
							{
								$amount=$this->request->get['amount'];
								if(empty($amount))
								{
									$amount=$this->request->post['amount'];
								}
                       
								$status_amount=$this->model_runner_cash->accept_reject_cash($amount,$logged_user,"1",$new_file_name,$bankId);
								$log->write($status_amount);
								if($status_amount>0)
								{
									//$this->model_runner_cash->add_to_trans_table($tr_id,$logged_user,"CR",$amount);
									//$this->model_runner_cash->add_to_runner_credit($logged_user,$amount);
									$this->response->setOutput(1);
								}
								else
								{
									$log->write('Oops ! Some error in insert, please try again.');
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