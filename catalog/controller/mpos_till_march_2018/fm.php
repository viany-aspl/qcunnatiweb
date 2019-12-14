<?php
class Controllermposfm extends Controller{

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


public function index() {

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


}
?>