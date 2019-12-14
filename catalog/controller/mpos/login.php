<?php

class POS
{
    public $api_id;
    public $api_name;
    public $api_store_id;
    public $api_group_id;
    public $api_cash;
    public $api_card;		
    public $success;	
    public $error;
    public $api_cid;
    public $api_url;
	public $api_card_billing_status;
	public $allow_cash;
    }
class ControllermposLogin extends Controller 
{
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
	public function password_check() 
	{
		$mcrypt=new MCrypt();
		$this->adminmodel('user/user');
		$log=new Log("password_check-".date('Y-m-d').".log");
		 	
		$log->write($this->request->post);	
		$this->request->post['user_id']=$mcrypt->decrypt($this->request->post['user_id']);
		$log->write($this->request->post);
		
		////////////check passwordhistory start here//////////////
		$passwordhistory=	$this->model_user_user->get_last_password_change_date($this->request->post['user_id']);
		$log->write($passwordhistory);
		if(count($passwordhistory)>0)
		{
			$log->write('in if count is greater then 1');
			$last_changed_date=$passwordhistory['datetime'];
			$today=date('Y-m-d');
			$diff = strtotime($today) - strtotime($last_changed_date); 
			$diff_days=abs(round($diff / 86400));
			if($diff_days>15)
			{
				$log->write('in if password is old then 15 days so need to changed the password');
				$json['msg']=$mcrypt->encrypt("Please changed the password");
				$json['success']        =$mcrypt->encrypt("1");  
				$this->response->setOutput(json_encode($json));
				return;
			}
			else
			{
				$log->write('in if password is not old then 15 days so no need to changed the password');
				$json['msg']=$mcrypt->encrypt("No need changed the password");
				$json['success']        =$mcrypt->encrypt("1");  
				$this->response->setOutput(json_encode($json));
				return;
			}
			
		}
		else
		{
			$log->write('never changed password so need to change password');
			$json['msg']=$mcrypt->encrypt("Please changed the password");
			$json['success']        =$mcrypt->encrypt("1");  
			$this->response->setOutput(json_encode($json));
			return;
		}
	}


	function crypto_rand_secure($min, $max)
	{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
	}

	function getToken($length)
	{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .=$codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
    }

    return $token;
	}


	public function forgotten() 
	{
		$mcrypt=new MCrypt();
		$this->adminmodel('user/user');
		$log=new Log("forgot-".date('Y-m-d').".log");
		$json=array();
		$json['success']=$mcrypt->encrypt("Error");
		$json['type']       =$mcrypt->encrypt("0");  	
		$log->write($this->request->post);	
		$log->write($mcrypt->decrypt($this->request->post['uid']));
			$user=	$this->model_user_user->getUserByUsername($mcrypt->decrypt($this->request->post['uid']));
			$log->write($user);
		$this->load->model('account/api');
		$api_info = $this->model_account_api->UserAuthorization($user['user_id']);
		if(empty($api_info))
		{
			$log->write('in UserAuthorization');
			$json['success']=$mcrypt->encrypt("User is not Authorized");
			$json['type']        =$mcrypt->encrypt("0");  
			$this->response->setOutput(json_encode($json));
			return;

			
		}
		////////////check passwordhistory start here//////////////
		$passwordhistory=	$this->model_user_user->getUserpasswordhistory($user['user_id']);
		$log->write($passwordhistory);
		if(count($passwordhistory)>2)
		{
			$log->write('in if limit exceed');
			$json['success']=$mcrypt->encrypt("Password request reached its usage limit!");
			$json['type']        =$mcrypt->encrypt("0");  
			$this->response->setOutput(json_encode($json));
			return;
		}
		////////////check passwordhistory end here//////////////

		if($user){
			$code = sha1(uniqid(mt_rand(), true));
			//$this->model_user_user->editCode($user['email'], $code);

		$log->write("in else");
				$this->model_user_user->editCodeUser($user['username'], $code);

			
				

		$user_info = $this->model_user_user->getUserByCode($code);
		$log->write("info");
					$log->write($user_info);
		if ($user_info) {	
			$pass=sprintf("%06d", mt_rand(1, 999999)); //$this->getToken(8);
			$this->model_user_user->editPassword($user_info['user_id'], $pass);
			$user_info['pass']=$pass;
		//send sms
			$log->write("send sms");
			$this->load->library('sms');		
        	                            $sms=new sms($this->registry);
			$log->write("send sms sending");
			//$user_info['telephone'] 
               		 $sms->sendsms($user_info['username'],"4",$user_info);    
			$log->write("send sms done");

			/////////////insert history////////
			try
			{
				$log->write('in try');
				$this->model_user_user->addUserpasswordhistory($user['user_id'],$user['username'],$mcrypt->decrypt($this->request->post['imei']),'forgot'); 
				$log->write('in try 2');
			}
			catch(Exception $el)
			{
				$log->write('in catch');
				$log->write($el);
			}
			/////////insert history end here//////// 

			$json['success']=$mcrypt->encrypt("New password has been send to your registered mobile number.");
			$json['type']        =$mcrypt->encrypt("1");      			
		}
	
		}
		$this->response->setOutput(json_encode($json));			

	}

	public function getversioncard() 
	{
			$log=new Log("vercard-".date('Y-m-d').".log");
			$log->write("in");
			$this->load->language('api/login');
			$log->write("in1");
		// Delete old login so not to cause any issues if there is an error
		unset($this->session->data['api_id']);
			$log->write("in2");
		 $mcrypt=new MCrypt();
		$keys = array(
			'username',
			'vid',
			);

		$log->write($this->request->post);
		foreach ($keys as $key) {
            

                	$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        		}



			$log->write($this->request->post);
		$json = array();

		$this->load->model('account/api');		

				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->request->post['username'],
					'name'        => $this->request->post
				);

				//$this->model_account_activity->addActivity('version', $activity_data);
		$log->write("going to play store");
			//get version
			$html = file_get_contents('https://play.google.com/store/apps/details?id=com.aspl.dsclqrcode');
			//$first_step = explode( '<div class="content" itemprop="softwareVersion">' , $html );
			//$second_step = explode("</div>" , $first_step[1] );
			//$first_step = explode( '<div class="content" itemprop="softwareVersion">' , $html );
			$first_step = explode( 'Current Version' , $html );
			
			//$second_step = explode("</div>" , $first_step[1] );
			$second_step1 = explode('<span class="htlgb"><div><span class="htlgb">' , $first_step[1] );
			$second_step = explode('</span></div></span></div><div class="hAyfc"><div class="BgcNfc">Requires Android</div>' , $second_step1[1] );
			//print_r($second_step[0]);
			$output=trim($second_step[0]);	
					$log->write($output);
			$log->write(bccomp($output, $this->request->post['vid']));
			if(bccomp($output, $this->request->post['vid'],2)==1)
			{
				$json['ver']=$mcrypt->encrypt("1");
			}
			else if(bccomp($output, $this->request->post['vid'],2)==-1)
			{
				$json['ver']=$mcrypt->encrypt("0");
			}
			else{
				$json['ver']=$mcrypt->encrypt("0");
			}
			$log->write($mcrypt->decrypt($json['ver']));
			$this->response->setOutput(json_encode($json));
	}
	public function getversion() 
	{
		$log=new Log("ver-".date('Y-m-d').".log");
			
		$this->load->language('api/login');
			
		// Delete old login so not to cause any issues if there is an error
		unset($this->session->data['api_id']);
			
		$mcrypt=new MCrypt();
		$keys = array(
			'username',
			'vid',
			);

		$log->write($this->request->post);
		foreach ($keys as $key) 
		{
            $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ; 
        }
		$log->write($this->request->post);
		$json = array();

		$this->load->model('account/api');		

				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->request->post['username'],
					'name'        => $this->request->post
				);

				//$this->model_account_activity->addActivity('version', $activity_data);
			$log->write("going to play store");
			//get version
			/*$html = file_get_contents('https://play.google.com/store/apps/details?id=com.aksha.unnati');
			//$first_step = explode( '<div class="content" itemprop="softwareVersion">' , $html );
			$first_step = explode( 'Current Version' , $html );
			
			//$second_step = explode("</div>" , $first_step[1] );
			$second_step1 = explode('<span class="htlgb"><div><span class="htlgb">' , $first_step[1] );
			$log->write($second_step1[1]);
			$second_step = explode('</span></div></span></div><div class="hAyfc"><div class="BgcNfc">Requires Android</div>' , $second_step1[1] );
			
			//print_r($second_step[0]);
			$log->write($second_step);
			$output=trim($second_step[0]);	*/
			$output='2.21'; 
			$log->write($output);
			$log->write(bccomp($output, $this->request->post['vid']));
			if(bccomp($output, $this->request->post['vid'],2)==1)
			{
				$log->write("in =1");
				$json['ver']=$mcrypt->encrypt("1");
			}
			else if(bccomp($output, $this->request->post['vid'],2)==-1)
			{
				$log->write("in = -1");
				$json['ver']=$mcrypt->encrypt("0");
			}
			else{
				$log->write("in else");
				$json['ver']=$mcrypt->encrypt("0");
			}
			$json['ver']=$mcrypt->encrypt("0");
			$log->write($mcrypt->decrypt($json['ver']));
			$this->response->setOutput(json_encode($json));
	}

	public function getversiontest() 
	{
			$log=new Log("ver-test-".date('Y-m-d').".log");
			
			$this->load->language('api/login');
			
		// Delete old login so not to cause any issues if there is an error
		unset($this->session->data['api_id']);
			
		 $mcrypt=new MCrypt();
		$keys = array(
			'username',
			'vid',
			);

		$log->write($this->request->post);
		foreach ($keys as $key) {
            

                	$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ; 
            
        		}

			$this->request->post['vid']='1.96';

			$log->write($this->request->post);
		$json = array();

		$this->load->model('account/api');		

				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->request->post['username'],
					'name'        => $this->request->post
				);

				//$this->model_account_activity->addActivity('version', $activity_data);
			$log->write("going to play store");
			//get version
			$html = file_get_contents('https://play.google.com/store/apps/details?id=com.aksha.unnati');
			//$first_step = explode( '<div class="content" itemprop="softwareVersion">' , $html );
			$first_step = explode( 'Current Version' , $html );
			
			//$second_step = explode("</div>" , $first_step[1] );
			$second_step1 = explode('<span class="htlgb"><div><span class="htlgb">' , $first_step[1] );
			$log->write($second_step1[1]);
			
			$second_step = explode('</span></div></span></div><div class="hAyfc"><div class="BgcNfc">Requires Android</div>' , $second_step1[1] );
			$log->write($second_step[0]);
			
			//print_r($second_step);
			print_r($second_step);
			$log->write($second_step);
			$output=trim($second_step[0]);	
			$log->write($output);
			$log->write(bccomp($output, $this->request->post['vid']));
			if(bccomp($output, $this->request->post['vid'],2)==1)
			{
				echo 'in =1';
				$log->write("in =1");
				$json['ver']=$mcrypt->encrypt("1");
			}
			else if(bccomp($output, $this->request->post['vid'],2)==-1)
			{
				echo "in = -1";
				$log->write("in = -1");
				$json['ver']=$mcrypt->encrypt("0");
			}
			else
			{
				echo "in else";
				$log->write("in else");
				$json['ver']=$mcrypt->encrypt("0");
			}
					$log->write($mcrypt->decrypt($json['ver']));
			$this->response->setOutput(json_encode($json));

	}
//
 public function change() 
 {

		 $mcrypt=new MCrypt();
		$this->adminmodel('user/user');
		$log=new Log("change-".date('Y-m-d').".log");
		$log->write($this->request->post);
		$log->write($mcrypt->decrypt($this->request->post['username']));
		$json=array();
		$json['success']=$mcrypt->encrypt("Error");
		$json['type']       =$mcrypt->encrypt("0");  		

			$user=	$this->model_user_user->getUser($mcrypt->decrypt($this->request->post['username']));
			$log->write($user);
		$this->load->model('account/api');
		$api_info = $this->model_account_api->UserAuthorization($user['user_id']);
		if(empty($api_info))
		{
			$log->write('in UserAuthorization');
			$json['success']=$mcrypt->encrypt("User is not Authorized");
			$json['type']        =$mcrypt->encrypt("0");  
			$this->response->setOutput(json_encode($json));
			return;

			
		}
		////////////check passwordhistory start here//////////////
		$passwordhistory=	$this->model_user_user->getUserpasswordhistory($user['user_id']);
		$log->write($passwordhistory);
		if(count($passwordhistory)>2)
		{
			$log->write('in if limit exceed');
			$json['success']=$mcrypt->encrypt("Password request reached its usage limit!"); 
			$json['type']        =$mcrypt->encrypt("0");  
			$this->response->setOutput(json_encode($json));
			return;
		}
		////////////check passwordhistory end here//////////////
			
		if($user){
			$code = sha1(uniqid(mt_rand(), true));

		$log->write("in else");
				$this->model_user_user->editCodeUser($user['username'], $code);

			
				
			$user_info = $this->model_user_user->getUserByCode($code);
					$log->write($user_info);
		if ($user_info) {	
			$pass=$mcrypt->decrypt($this->request->post['pid']);//$this->getToken(8);
			$this->model_user_user->editPassword($user_info['user_id'], $pass);
			$user_info['pass']=$pass;
			//send sms
			$log->write("send sms");
			$this->load->library('sms');		
        	        $sms=new sms($this->registry);
			$log->write("send sms sending".$user_info['username']);
			//$user_info['telephone']
               		 $sms->sendsms($user_info['username'],"4",$user_info);  
			$log->write("send sms done");
			/////////////insert history////////
			try
			{
				$this->model_user_user->addUserpasswordhistory($user['user_id'],$user['username'],$mcrypt->decrypt($this->request->post['imei']),'change');
			}
			catch(Exception $el)
			{
				$log->write($el);
			}
			/////////insert history end here//////// 
			$json['success']=$mcrypt->encrypt("New password has been send to your registered mobile number.");
			$json['type']        =$mcrypt->encrypt("1");      			
		}
	
		}else{

			$json['success']=$mcrypt->encrypt("No user found");
			$json['type']        =$mcrypt->encrypt("0");
		}
		
		$this->response->setOutput(json_encode($json));			

}
//


public function index() 
{
	$log=new Log("login-".date('Y-m-d').".log");
	$log->write("in");
	$this->load->language('api/login');
	$log->write("in1");
	// Delete old login so not to cause any issues if there is an error
	unset($this->session->data['api_id']);
	$log->write("in2");

	$mcrypt=new MCrypt();
	$keys = array(
			'username',
			'password',
			'utype',
			'eid'
		);

	$data=new POS();
	$log->write("in3");
	$log->write($this->request->post);
	foreach ($keys as $key)
	{
            
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
       	 }

	$log->write($this->request->post);
	$json = array();

	$this->load->model('account/api');
	$this->adminmodel('setting/store');
	$api_info = $this->model_account_api->loginm($this->request->post['username'], $this->request->post['password']);
	if ($api_info) 
	{
			$this->load->model('account/activity');

			$activity_data = array(
					'customer_id' => $api_info['user_id'],
					'name'        => $api_info['firstname'] . ' ' . $api_info['lastname']
				);

			$this->model_account_activity->addActivity('login', $activity_data);
	}
	$log->write($api_info);
	if ($api_info) 
	{ 
		//check emp type match
		$utype="1";//$this->request->post['utype'];
		if(($utype=="1"&&$api_info['user_group_id']=="11")||($utype=="1"&&$api_info['user_group_id']=="14")||($utype=="1"&&$api_info['user_group_id']=="16")||($utype=="1"&&$api_info['user_group_id']=="22")||($utype=="1"&&$api_info['user_group_id']=="26") ||($utype=="1"&&$api_info['user_group_id']=="27") ||($utype=="1"&&$api_info['user_group_id']=="29")|| ($utype=="1"&&$api_info['user_group_id']=="34") ||($utype=="1"&&$api_info['user_group_id']=="35")||($utype=="1"&&$api_info['user_group_id']=="36")||($utype=="1"&&$api_info['user_group_id']=="39")||($utype=="1"&&$api_info['user_group_id']=="40") ||($utype=="1"&&$api_info['user_group_id']=="41") ||($utype=="1"&&$api_info['user_group_id']=="42") || ($utype=="1"&&$api_info['user_group_id']=="43"))
		{ 


			if($api_info['user_group_id']==29) 
			{
				$api_info['user_group_id']=27;
			}
			$log->write("in");
			$data->api_id = $mcrypt->encrypt($api_info['user_id']);
			$log->write("in");
                         		$data->api_name =$mcrypt->encrypt($api_info['firstname']." ".$api_info['lastname']); 
			$log->write("in");
                         		$data->api_store_id =$mcrypt->encrypt($api_info['store_id']);
                         		$data->api_group_id =$mcrypt->encrypt($api_info['user_group_id']);
                         		$data->api_cash=$mcrypt->encrypt($api_info['cash']);
                         		$data->api_card=$mcrypt->encrypt($api_info['card']);
								$data->api_card_billing_status=$mcrypt->encrypt(1);//$api_info['card_billing_status']);
	           			$data->api_url=$mcrypt->encrypt($this->model_setting_store->getstore($api_info['store_id'])["url"]);
					$data->api_cid=$mcrypt->encrypt( $this->model_setting_store->getStore(( $api_info['store_id']))["company_id"]);	
                        		$data->success = $this->language->get('text_success');
                        		$data->error="0";
								$data->allow_cash=$mcrypt->encrypt("0");
		}
		else
		{
			$data->error=$this->language->get('error_login');
			$json['error'] ='Please Retry, User not active'; //$this->language->get('error_login');


		}
		$log->write("out");	
	} 
	else
	 {
		$data->error=$this->language->get('error_login');
		$json['error'] = 'User name OR Password is wrong'; //$this->language->get('error_login');
	}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
}


public function getcashinhand()
{
     $log=new Log("logincash.log");
     $log->write("in getcashinhand");
		
    $mcrypt=new MCrypt();
		
    $log->write($this->request->post);
          
              $this->request->post['user_id'] =$mcrypt->decrypt($this->request->post['user_id']) ;
	$this->request->post['store_id'] =$mcrypt->decrypt($this->request->post['store_id']) ;
            
    

    $log->write($this->request->post);
    $json = array();

    $this->load->model('account/customer');
    $num_rows=$this->model_account_customer->getcashinhand($this->request->post['user_id'],$this->request->post['store_id']);
    //echo $num_rows=$this->model_account_customer->getcashinhand(78,22); 

if($num_rows>0)
{
$this->response->addHeader('Content-Type: application/json');
$this->response->setOutput($mcrypt->encrypt(0));
}  
else
{ 
$this->response->addHeader('Content-Type: application/json');
$this->response->setOutput($mcrypt->encrypt(2));   
}
}  




 
}