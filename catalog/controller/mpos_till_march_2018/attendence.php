<?php
class Controllermposattendence extends Controller{

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


public function set_attendence() {

                           $mcrypt=new MCrypt();
                           
                            $log=new Log("attendence-".date('Y-m-d').".log");
		
        $log->write($this->request->post);
		$log->write($this->request->get);         
	$this->load->model('account/api');
	  $api_info = $this->model_account_api->UserAuthorization($mcrypt->decrypt($this->request->post['user_id']));
	  $log->write($api_info);
	  if(empty($api_info))
	  {
	      	$log->write("User is not Authorized");
	   	$json['message']="User is not Authorized";
		   $json['success'] = "-1";
	   	$this->response->setOutput(json_encode($json)); 
		   return;
  	}          

		if (isset($this->request->post['user_id'])) {
			$user_id = $mcrypt->decrypt($this->request->post['user_id']); 		
		}
        else if (isset($this->request->get['user_id'])) {
			$user_id = $mcrypt->decrypt($this->request->get['user_id']); 		
		}
                            else {
			$user_id = '';
		}
		
		if (isset($this->request->post['location_lat'])) {
			$location_lat = $this->request->post['location_lat']; 		
		}
        else if (isset($this->request->get['location_lat'])) {
			$location_lat = $this->request->get['location_lat']; 		
		}
                            else {
			$location_lat = '';
		}
		if (isset($this->request->post['location_long'])) {
			$location_long = $this->request->post['location_long']; 		
		}
        else if (isset($this->request->get['location_long'])) {
			$location_long = $this->request->get['location_long']; 		
		}
                            else {
			$location_long = '';
		}
		if (isset($this->request->post['attendence_type'])) {
			$attendence_type = $this->request->post['attendence_type']; 		
		}
        else if (isset($this->request->get['attendence_type'])) {
			$attendence_type = $this->request->get['attendence_type']; 		
		}
                            else {
			$attendence_type = '';
		}
		
        
		//echo "here";exit;
        $this->adminmodel('attendence/attendence');
		
		$this->load->library('trans');
		$trans=new trans($this->registry);
        $trans->addattendencetrans($user_id,$location_lat,$location_long,$attendence_type);
		
		$data=array();
		if(!empty($location_long))
        {
                             
             $this->request->post['user_id']=$user_id;
             $this->request->post['location_lat']=$location_lat;
             $this->request->post['location_long']=$location_long;
             $this->request->post['attendence_type']=$attendence_type;        
             
             $attendence_data=$this->model_attendence_attendence->gettoday_attendence($this->request->post); 
		$log->write($this->request->post);
			 $attendence_id=$attendence_data[0]['sid'];
			 if(!empty($attendence_id))
			 {
				
				if($attendence_type=="in")
				{
				$data['success']='-1';
				$data['message']='Your IN Attendance is already Registered';
				$log->write('Your IN Attendance is already Registered');
				}
				if($attendence_type=="out")
				{
				    //$data['message']='Your OUT Time is already Registered';
					$attendence_info=$this->model_attendence_attendence->update_attendence($this->request->post,$attendence_id);
					$data['success']='1';
					$data['message']='Your OUT Attendance is Registered Successfully';
					$log->write('Your OUT Attendance is Registered Successfully');
				}
			 }
			 else///////means first time in or out
			 {
				if($attendence_type=="in")
				{
					$attendence_info=$this->model_attendence_attendence->insert_attendence($this->request->post);
					$data['success']='1';
					$data['message']='Your IN Attendance is Registered Successfully';
					$log->write('Your IN Attendance is Registered Successfully');
				}
				if($attendence_type=="out")
				{
				    
					$attendence_info=$this->model_attendence_attendence->update_attendence($this->request->post,$attendence_id);
					$data['success']='-1';
					$data['message']='Your IN Attendance is not available for today';
					$log->write('Your IN Attendance is not available for today');
				}
			 
					
					
			 }
             $log->write($attendence_info);
           
                           
        }
        else
        {
		 $data['success']='-1';
		 $data['message']='Please Enable your Phone Location';
		$log->write('Please Enable your Phone Location');
        }

		$log->write('return data by webservice is : ');
		$log->write($data);
		$this->response->setOutput(json_encode($data));  
	}
public function gettodayattendence() {

                           $mcrypt=new MCrypt();
                           
                            $log=new Log("attendence-".date('Y-m-d').".log");
		
                            
		if (isset($this->request->post['user_id'])) {
			$user_id = $mcrypt->decrypt($this->request->post['user_id']); 		
		}
        else if (isset($this->request->get['user_id'])) {
			$user_id = $mcrypt->decrypt($this->request->get['user_id']); 		
		}
        else {
			$user_id = '';
		}

		
        $this->adminmodel('attendence/attendence');
		
		$filter_data = array(
			'user_id'	     => $user_id
			
		);

		$data['results'] = array();
		
		$results = $this->model_attendence_attendence->gettoday_attendence($filter_data);
        $log->write($results);
		foreach ($results as $result) { //print_r($result);
				if($result['out_time']=='0000-00-00 00:00:00')
				{
					$out_time="";
				}
				else
				{
				$out_time=$result['out_time'];
				}
			    $data['results'][] = array(
				'sid' => $mcrypt->encrypt($result['sid']),
				'user_id' => $mcrypt->encrypt($result['user_id']),
				'in_time'   => $mcrypt->encrypt($result['in_time']),
				'out_time'   => $mcrypt->encrypt($out_time),
				'location_lat'   => $mcrypt->encrypt($result['location_lat']),
				'location_long'   => $mcrypt->encrypt($result['location_long']),
				'location_lat_out'   => $mcrypt->encrypt($result['location_lat_out']),
				'location_long_out'   => $mcrypt->encrypt($result['location_long_out'])
				
			);
		}


		$this->response->setOutput(json_encode($data));
	}
 
}
?>