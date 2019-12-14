<?php

class Controllermposschedule extends Controller{

    public function adminmodel($model) 
	{
		$admin_dir = DIR_SYSTEM;
		$admin_dir = str_replace('system/','admin/',$admin_dir);
		$file = $admin_dir . 'model/' . $model . '.php';      
		//$file  = DIR_APPLICATION . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		if (file_exists($file)) 
		{
			include_once($file);
			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} 
		else 
		{
			trigger_error('Error: Could not load model ' . $model . '!');
			exit();               
		}
	}

public function updateorderQR()
{
	$log=new Log("schedule-updateorderqr-".date('Y-m-d').".log");
	$log->write('updateorderQR called');
	$this->adminmodel('pos/pos');
	$this->adminmodel('unit/unit');

	$orders=$this->model_pos_pos->getTodayPendingOrderCompanywise(date('Y-m-d'),'1','1');//(date_added,company_id,order_status_id)//date('Y-m-d')
	//$log->write($orders);
	foreach($orders as $order)
	{
		$this->request->post['oid']=$order['comment'];
		$this->request->post['billno']=$order['order_id'];
		$this->request->post['username']=$order['user_id'];
		$this->request->post['uid']=$order['unit_id'];
		
		$log->write($this->request->post);
		
		//get unit data
		$order_details=$order;//$this->model_pos_pos->getOrder($this->request->post["billno"]);
		$log->write($order_details);
		
		$this->request->post['billnofordscl']=$order_details['invoice_prefix'].$order_details['invoice_no'];
		$log->write($this->request->post);
		
        $unitdata= $this->model_unit_unit->getUnitByID($this->request->post['uid']);
		$log->write($unitdata);
									
		if(!empty($unitdata['company_name']))
		{
			$company=strtolower($unitdata['company_name']);
			$log->write("in company ".$company);
			$this->adminmodel('pos/'.$company);
			$datares = $this->{'model_pos_' . $company}->UpdateStatus('UpdateStatus',$this->request->post,0);	
			$log->write("return by company ".$datares);	
			if(!empty($datares))
			{
				//data check
				try
				{
					$log->write("come in try to send data to RequisitionToBill".$this->request->post["billno"]);
					$this->model_pos_pos->RequisitionToBill($this->request->post['oid'],$this->request->post['billno']);
					$this->request->post['store_id']=$order_details['store_id'];
					$this->request->post['order_id']=$this->request->post["billno"];
					$this->model_pos_pos->updateinventory($this->request->post);
					
					$orders=$this->model_pos_pos->status_update_by($order['order_id'],'Scheduler');
					
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

}
}
?>