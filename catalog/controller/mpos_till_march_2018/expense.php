<?php
class Controllermposexpense extends Controller{

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

public function getreasons() {

                           $mcrypt=new MCrypt();
                          
                            $this->adminmodel('expense/expense');

		$data['results'] = array();

		$results = $this->model_expense_expense->getReasons();
                            
		foreach ($results as $result) { //print_r($result);
			         $data['results'][] = array(
				'SID' => $mcrypt->encrypt($result['sid']),
				
				'reason'                => $mcrypt->encrypt($result['reason'])
			);
		}

		$this->response->setOutput(json_encode($data));
	}


public function bill_submission() {

                $mcrypt=new MCrypt();
                $this->adminmodel('expense/expense');
                if ($this->request->server['REQUEST_METHOD'] == 'POST')
                { 
                           if (isset($this->request->post['filter_store'])) {
			$this->request->post['filter_store'] = $mcrypt->decrypt($this->request->post['filter_store']); 		
		} else {
			$this->request->post['filter_store'] = 0;
		}

		if (isset($this->request->post['employee_name'])) {
			$this->request->post['employee_name'] = $mcrypt->decrypt($this->request->post['employee_name']); 
		} else {
			$this->request->post['employee_name'] = '';
		}
		if (isset($this->request->post['employee_id'])) {
			$this->request->post['employee_id'] = $mcrypt->decrypt($this->request->post['employee_id']); 
		} else {
			$this->request->post['employee_id'] = 0;
		}
                          
		if (isset($this->request->post['amount'])) {
			$this->request->post['amount']  = $mcrypt->decrypt($this->request->post['amount']); 
		} else {
			$this->request->post['amount'] = 0;
		}
                            if (isset($this->request->post['reason'])) {
			$this->request->post['reason']  = $mcrypt->decrypt($this->request->post['reason']); 
		} else {
			$this->request->post['reason'] = 0;
		}
                            if (isset($this->request->post['exepense_date'])) {
			$this->request->post['exepense_date']  = $mcrypt->decrypt($this->request->post['exepense_date']); 
		} else {
			$this->request->post['exepense_date'] = '';
		}
		if (isset($this->request->post['desc'])) {
			$this->request->post['desc']  = $mcrypt->decrypt($this->request->post['desc']); 
		} else {
			$this->request->post['desc'] = '';
		}
		if (isset($this->request->post['billattched'])) {
			$this->request->post['billattched']  = $mcrypt->decrypt($this->request->post['billattched']); 
		} else {
			$this->request->post['billattched'] = '';
		}
                           $insertid=$this->model_expense_expense->billsubmmision($this->request->post,'');
                           $this->response->setOutput(($insertid));   
                }
                else 
                {
                  $this->response->setOutput('0');  
                }
		
	}
public function bill_submission_update_status() {

                $mcrypt=new MCrypt();
                $this->adminmodel('expense/expense');
                if ($this->request->server['REQUEST_METHOD'] == 'POST')
                { 
                           if (isset($this->request->post['billid'])) {
			$this->request->post['billid'] = $mcrypt->decrypt($this->request->post['billid']); 		
		} else {
			$this->request->post['billid'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$this->request->post['status'] = $mcrypt->decrypt($this->request->post['status']); 
		} else {
			$this->request->post['status'] = ''; 
		} 
		if (isset($this->request->post['remark'])) {
			$this->request->post['remark'] = $mcrypt->decrypt($this->request->post['remark']); 
		} else {
			$this->request->post['remark'] = ''; 
		} 
		
                           $insertid=$this->model_expense_expense->billsubmmision_update_status($this->request->post);
                           $this->response->setOutput(('1'));   
                }
                else 
                {
                  $this->response->setOutput('0');  
                }
		
	}
public function getexpenses() {

                $mcrypt=new MCrypt();
                $this->adminmodel('expense/expense');
                $log=new Log("expense-".date('Y-m-d').".log");
                $log->write($this->request->post);

                           if (isset($this->request->post['filter_date_start'])) {
			$filter_date_start = $mcrypt->decrypt($this->request->post['filter_date_start']);
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->post['filter_date_end'])) {
			$filter_date_end = $mcrypt->decrypt($this->request->post['filter_date_end']);
		} else {
			$filter_date_end = null;
		}
              
                            if (isset($this->request->post['filter_store'])) {
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']);
		} else {
			$filter_store = null;
		}
                            if (isset($this->request->post['filter_reason'])) {
			$filter_reason = $mcrypt->decrypt($this->request->post['filter_reason']);
		} else {
			$filter_reason = null;
		}
                           if (isset($this->request->post['start'])) {
			$page = $mcrypt->decrypt($this->request->post['start']);
		} else {
			$page = 1;
		}
                            if (isset($this->request->post['user'])) {
			$user = $mcrypt->decrypt($this->request->post['user']);
		} else {
			$user = '';
		}
                            $filter_data = array(
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                                          'filter_store'           => $filter_store,
                                          'filter_reason'           => $filter_reason,
                                          'user'                 => $user,
			'start'                  => ($page - 1) * 20,
			'limit'                  => 20
		);
                           $log->write($filter_data);
                           $results = $this->model_expense_expense->getmyBills($filter_data);
                           foreach ($results as $result) {  //print_r($result);
                    
                    $data['bills'][] = array(
					'center'  => $mcrypt->encrypt($result['store_name']),
                                                                      'sid'  => $mcrypt->encrypt($result['SID']),
					'reason'             => $mcrypt->encrypt($result['reason_txt']),
					'bill_pic'         => $mcrypt->encrypt($result['bill_pic']),
					'amount'           => $mcrypt->encrypt($result["amount"]),
					'exepense_date'	   => $mcrypt->encrypt(date('d/m/Y',strtotime($result['exepense_date']))),
					'status'   => $mcrypt->encrypt($result['status']),
					'billattched'   => $mcrypt->encrypt($result['billattched']),
					'create_time'     => $mcrypt->encrypt(date('d/m/Y',strtotime($result['create_time']))),
			                            		'employee_name'      => $mcrypt->encrypt($result["firstname"]." ".$result["lastname"]) ,
					'name'      => $mcrypt->encrypt($result["firstname"]." ".$result["lastname"]) ,
					'submission_date'	   => $mcrypt->encrypt(date('d/m/Y',strtotime($result['exepense_date']))),	
					'letter_number' =>	$mcrypt->encrypt("0")		
				);
                }
                           $log->write($data);
                           if($user!="")
                           {
                           $this->response->setOutput(json_encode($data));  
                           }
                           else
                           {
                              $this->response->setOutput(json_encode(''));
		}
	}
public function getreimbursement() {

                $mcrypt=new MCrypt();
                $this->adminmodel('expense/expense');
                $log=new Log("expense-".date('Y-m-d').".log");
                $log->write($this->request->post);

                           if (isset($this->request->post['filter_date_start'])) {
			$filter_date_start = $mcrypt->decrypt($this->request->post['filter_date_start']);
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->post['filter_date_end'])) {
			$filter_date_end = $mcrypt->decrypt($this->request->post['filter_date_end']);
		} else {
			$filter_date_end = null;
		}
              
                            if (isset($this->request->post['filter_store'])) {
			$filter_store = $mcrypt->decrypt($this->request->post['filter_store']);
		} else {
			$filter_store = null;
		}
                            if (isset($this->request->post['filter_reason'])) {
			$filter_reason = $mcrypt->decrypt($this->request->post['filter_reason']);
		} else {
			$filter_reason = null;
		}
                           if (isset($this->request->post['start'])) {
			$page = $mcrypt->decrypt($this->request->post['start']);
		} else {
			$page = 1;
		}
                            if (isset($this->request->post['user'])) {
			$user = $mcrypt->decrypt($this->request->post['user']);
		} else {
			$user = '';
		}
                            $filter_data = array(
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
                                          'filter_store'           => $filter_store,
                                          'filter_reason'           => $filter_reason,
                                          'user'                 => $user,
			'start'                  => ($page - 1) * 20,
			'limit'                  => 20
		);
                           $log->write($filter_data);
                           $results = $this->model_expense_expense->getmyBillsreimbursement($filter_data);
                           foreach ($results as $result) {  //print_r($result);
                    
                    $data['bills'][] = array(
					'center'  => $mcrypt->encrypt($result['store_name']),
                                                                      'sid'  => $mcrypt->encrypt($result['SID']),
					'reason'             => $mcrypt->encrypt($result['reason_txt']),
					'bill_pic'         => $mcrypt->encrypt($result['bill_pic']),
					'amount'           => $mcrypt->encrypt($result["amount"]),
					'exepense_date'	   => $mcrypt->encrypt(date('d/m/Y',strtotime($result['exepense_date']))),
					'status'   => $mcrypt->encrypt($result['status']),
					'billattched'   => $mcrypt->encrypt($result['billattched']),
					'create_time'     => $mcrypt->encrypt(date('d/m/Y',strtotime($result['create_time']))),
			                            'employee_name'      => $mcrypt->encrypt($result["firstname"]." ".$result["lastname"]) 
				);
                }
                           $log->write($data);
                           if($user!="")
                           {
                           $this->response->setOutput(json_encode($data));  
                           }
                           else
                           {
                              $this->response->setOutput(json_encode(''));
		}
	}
}