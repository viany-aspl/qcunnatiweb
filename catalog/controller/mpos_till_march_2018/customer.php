<?php
class ControllermposCustomer extends Controller {


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

public function getUnitsbyStore(){
			$log=new Log("getUnitsbyStore-".date('Y-m-d').".log");
            $mcrypt=new MCrypt();
	        $store_id=$mcrypt->decrypt($this->request->post['store_id']);
            $company_id=$mcrypt->decrypt($this->request->post['companyid']);
			$log->write($this->requiest->post);
			$log->write($store_id);
			$log->write($company_id);
	        $this->adminmodel('setting/store');
	        
	        $db_data=$this->model_setting_store->getUnitsbyStore($store_id);
			$log->write($db_data);
			$json=array();
			foreach($db_data as $dbd)
			{
				$json['units'][]=array('unit_id'=>$mcrypt->encrypt($dbd['unit_id']),'unit_name'=>$mcrypt->encrypt($dbd['unit_name']));
			}
			$log->write($json);
            $this->response->setOutput(json_encode($json));
}


public function getDeliveryType(){

		    $mcrypt=new MCrypt();
	        $this->adminmodel('setting/store');
			$jsons = $this->model_setting_store->getDelivery();

			foreach ($jsons as $ids) {		
			$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['sid']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
                                     );
				}
             $this->response->setOutput(json_encode($json));
}


public function verifyotp(){

                      $mcrypt=new MCrypt();
	        $customerID=$mcrypt->decrypt($this->request->post['customerID']);
                      $otp=$mcrypt->decrypt($this->request->post['otp']);
	        $this->adminmodel('setting/store');
	        $this->adminmodel('setting/setting');
	        $jsons = $this->model_setting_store->getStores();
			
                     $this->response->setOutput(json_encode(1));
}
public function verifycustomerotp()
{
	        $mcrypt=new MCrypt();
	        $customerID=$mcrypt->decrypt($this->request->post['customerID']);
                      $otp=$mcrypt->decrypt($this->request->post['otp']);
	        $this->adminmodel('sale/customer');    
	        $res=$this->model_sale_customer->verifycustomerotp($customerID,$otp);
                      if($res=="1")
                      {
                           $res=$this->model_sale_customer->approved_customerotp_update_status($customerID,$otp);
                           $json['success'] = 'Verified Successfully';
                      }
                      else
                      {
		$json['error'] = 'OTP is not Matched';
                      }	
                     $this->response->setOutput(json_encode($json));
}
public function addcustomer(){
            
        $mcrypt=new MCrypt();
$log=new Log("addcust-".date('Y-m-d').".log");

$log->write($this->request->post);
       


             //
              $json = array();
            
             if($this->request->post['firstname']==''){
                 $json['error'] = 'Error: Please firstname name.';

             }
            
             if($this->request->post['telephone']==''){
                 $json['error'] = 'Error: Please enter telephone name.';

             }

             if($this->request->post['card']=='')
                 {

                     $json['error'] = 'Error: Please enter card number.';

             }
             if($this->request->post['village']=='')
                 {
                 $json['error'] = 'Error: Please enter village name.';

             }
     if($this->request->post['pincode']=='')
                 {
                 $json['error'] = 'Error: Please enter pincode name.';

             }
             if($this->request->post['aadhar']=='')
             {
                 $json['error'] = 'Error: Please enter aadhar number';

             }
             $log->write("check");
             //check mobilenummber exits

$keys = array(
            'username',
            'store_id',
            'telephone',
            'village',
            'pincode',
            'firstname',
            'card',
            'crop1',
            'crop2',
            'acre1',
            'acre2',
                                          'aadhar',
		'isotp'
        );


foreach ($keys as $key) {
           

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
           
        }

$log->write($this->request->post);
$this->load->library('sms');   
$sms=new sms($this->registry);
$otp = rand(1000, 9999);

             $this->adminmodel('sale/customer');      
                 if (isset($this->request->post['telephone']) )
                    {
            $customer_info = $this->model_sale_customer->getCustomerByEmail($this->request->post['telephone']);
          }
        $log->write($customer_info);
            
             if (empty($customer_info)){
             $log->write("check if");
                 unset($this->session->data['cid']);
             $this->request->post['email']=($this->request->post['telephone']);
             $this->request->post['fax']=($this->request->post['telephone']);
             $this->request->post['password']=($this->request->post['telephone']);
         $this->request->post['customer_group_id']="1";
             $this->request->post['newsletter']='0';       
             $this->request->post['approved']='0';
             $this->request->post['status']='1';
             $this->request->post['safe']='1';
              $this->request->post['address_1']= ($this->request->post['village']);
                 $this->request->post['address_2']= ($this->request->post['village']);
                 $this->request->post['city']= ($this->request->post['village']);
                 $this->request->post['company']='Unnati';
             $this->request->post['country_id']='0';
             $this->request->post['zone_id']='0';
             $this->request->post['postcode']='0';
             $this->request->post['store_id']=$this->request->post['store_id'];            
             $this->request->post['address']=array($this->request->post);
             $this->request->post['aadhar']=($this->request->post['aadhar']);
            
             $this->request->post['otp']=$otp;
             $log->write($this->request->post);
             $this->model_sale_customer->addCustomer($this->request->post);
            }
             if(isset($this->session->data['cid']))
             {
                 $json['id']=$this->session->data['cid'];
                 $json['success'] = 'Success: customer added.';
                 
                //SMS LIB
               
               
                if($this->request->post['isotp']=='1')
                {
                $sms->sendsms($this->request->post['telephone'],"10",$this->request->post); 
                $json['approved'] = '0';
                }
                else if($this->request->post['isotp']=='0')
                {
                $res=$this->model_sale_customer->approved_customerotp_update_status($this->session->data['cid'],$otp);
                $json['approved'] = '1';
                $sms->sendsms($this->request->post['telephone'],"1",$this->request->post);
                }
             }

            else
             {
                 $json['id']=$customer_info['customer_id'];
                 if($this->request->post['isotp']=='1')
                 {
                 if($customer_info['approved']==0)
                 {
                  $this->model_sale_customer->update_otp($otp,$customer_info['customer_id'],$this->request->post['telephone'],$this->request->post['aadhar']);
                  $sms->sendsms($this->request->post['telephone'],"10",$this->request->post);
                 }
                 $json['approved'] =$customer_info['approved'];
                 }
                 else
                 {
                 $res=$this->model_sale_customer->approved_customerotp_update_status($customer_info['customer_id'],$otp);
                 $json['approved'] ='1';
                 }
                 
                 $json['error'] = 'Error: customer already exists with this telephone.';
             }
             
             $log->write($json);
           
                    $this->response->setOutput(json_encode($json));
                                                  
        
}
public function getStore(){

	$mcrypt=new MCrypt();
	$this->adminmodel('setting/store');
	$this->adminmodel('setting/setting');
	//$jsons = $this->model_setting_store->getStores();
	$log=new Log("store-login-".date('Y-m-d').".log");
	$this->adminmodel('user/user');
	$get_user_info=$this->model_user_user->getUser($mcrypt->decrypt($this->request->post["username"]));
	$log->write($get_user_info);
	$user_group=$get_user_info["user_group_id"];
	$user_store=$get_user_info["store_id"];
	$company_id = $this->model_setting_store->getCompanybystore($user_store);
    	$log->write($company_id);

	//$jsons =$this->model_setting_store->getStoresCompanyWise($company_id);
	$jsons =$this->model_setting_store->getOwnStores(array());
	if($user_group=='26')
	{
		$filter_data=array('filter_user'=>$mcrypt->decrypt($this->request->post["username"]));
		$jsons = $this->model_setting_store->getStoresByUser($filter_data); 
		foreach ($jsons as $ids) 
		{
			if(!empty($ids['store_id']))
			{

 				if($ids['store_id']!='19')
				{

 					if($ids['store_id']!='14')
					{
   						if($ids['config_storestatus']=='1')
						{


							$json['crops'][] = array(
                       								 'id' => $mcrypt->encrypt($ids['store_id']),
                       								 'name'       =>$mcrypt->encrypt($ids['name']),
									'address'   =>	$mcrypt->encrypt($this->model_setting_setting->getSettingbykey('config','config_address',$ids['store_id']))
                                    							 );
						}/////////end if of status
		
					}////////end if of store id 14
				}////////////////end if of storeb id 19
			}////////end if of store not empty
		}//////////end of foreach loop

	}
	else if($company_id=='3')
	{
			$jsons =$this->model_setting_store->getStoresCompanyWise($company_id);
			$log->write('in else if company id is 3 means isec');
			$log->write($jsons);

			foreach ($jsons as $ids) 
			{
				if(!empty($ids['store_id']))
				{

 					if($ids['store_id']!='19')
					{

 						if($ids['store_id']!='14')
						{

							if($ids['store_id']!=$mcrypt->decrypt($this->request->post['store_id']))
							{
								if($ids['config_storestatus']=='1')
								{
									$json['crops'][] = array(
                        								'id' => $mcrypt->encrypt($ids['store_id']),
                        								'name'       =>$mcrypt->encrypt($ids['name']),
									'address'   =>	$mcrypt->encrypt($this->model_setting_setting->getSettingbykey('config','config_address',$ids['store_id']))
                                     							);
								}/////////end if of status
							}////////end if of store is not own store
						}////////end if of store id 14
					}////////end if of store id 19
				}/////////end if of store not empty
			}/////////end of foreach loop
	}/////////////end of else if company id is 3 means isec
	else
	{
			$log->write('in else');
			$log->write($jsons);

			foreach ($jsons as $ids) 
			{
				if(!empty($ids['store_id']))
				{

 					if($ids['store_id']!='19')
					{

 						if($ids['store_id']!='14')
						{

							if($ids['store_id']!=$mcrypt->decrypt($this->request->post['store_id']))
							{
								if($ids['config_storestatus']=='1')
								{
									$json['crops'][] = array(
                        								'id' => $mcrypt->encrypt($ids['store_id']),
                        								'name'       =>$mcrypt->encrypt($ids['name']),
									'address'   =>	$mcrypt->encrypt($this->model_setting_setting->getSettingbykey('config','config_address',$ids['store_id']))
                                     							);
								}/////////end if of status
							}////////end if of store is not own store
						}////////end if of store id 14
					}////////end if of store id 19
				}/////////end if of store not empty
			}/////////end of foreach loop
	}/////////////end of else

             $this->response->setOutput(json_encode($json));
}


//news
public function getNewsLatest(){

		    $mcrypt=new MCrypt();
$log=new Log("newslatest-".date('Y-m-d').".log");
$log->write($this->request->post);
/*
	        $this->adminmodel('setting/store');
			$jsons = $this->model_setting_store->getNewsLatest();

			foreach ($jsons as $ids) {		
			$json['crops'][] = array(
                        'id' => ($ids['NewsItemID']),
                        'name'       =>($ids['NewsHeader']),
			'by'	=>($ids['PublishedBy']),
			'date'	=>($ids['DatePublished']),
			'img'	=>($ids['NewsImage'])
	
                                     );
				}

*/
$url="https://news.google.com/news/rss/headlines/section/q/agriculture/agriculture?ned=hi_in&hl=hi&gl=IN";
$news = simplexml_load_file($url);

$feeds = array();

$i = 0;
$dat["crops"]=array();
foreach ($news->channel->item as $item) 
{
 
	if($i==2)
	{
		break;
	}   
    preg_match('@src="([^"]+)"@', $item->description, $match);
    $parts = explode('<font size="-1">', $item->description);

    $feeds[$i]['title'] = (string) $item->title;
    $feeds[$i]['link'] = (string) $item->link;
    $feeds[$i]['image'] = $match[1];
    $feeds[$i]['site_title'] = strip_tags($parts[1]);
    $feeds[$i]['story'] = strip_tags($parts[2]);
    $feeds[$i]['date']=  (string) $item->pubDate;
    $json["crops"][]=array(
                            'id'=>('1234'),
		'name'=>(strip_tags($parts[2])),
                            'by'=>(string) $item->title,
		'desc'=>(strip_tags($parts[0])),
		'date'	=>date('D d M Y',strtotime($item->pubDate)),
		'link'=>((string) $item->link),
		'imgread'=>("http://".$match[1])
	);
    $i++;
}
$log->write($json);
//echo '<pre>';
//print_r(json_encode($dat["crops"]));
             $this->response->setOutput(json_encode($json));
}

public function getNews(){

		    $mcrypt=new MCrypt();
	        $this->adminmodel('setting/store');
/*
			$jsons = $this->model_setting_store->getNews();

			foreach ($jsons as $ids) {		
			$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['NewsItemID']),
                        'name'       =>$mcrypt->encrypt($ids['NewsHeader']),
			'by'	=>$mcrypt->encrypt($ids['PublishedBy']),
			'desc' =>	$mcrypt->encrypt($ids['NewsDetails']),
			'img'	=>	$mcrypt->encrypt($ids['NewsImage']),
			'date'	=>	$mcrypt->encrypt(($ids['DatePublished'])),
			'imgread'=> 	$mcrypt->encrypt($ids['NewsImage'])

				

                                     );
				}
*/
$url="https://news.google.com/news/rss/headlines/section/q/agriculture/agriculture?ned=hi_in&hl=hi&gl=IN";
$news = simplexml_load_file($url);

$feeds = array();

$i = 0;
$dat["crops"]=array();
foreach ($news->channel->item as $item) 
{
    //print_r((string)$item->title);
    preg_match('@src="([^"]+)"@', $item->description, $match);
    $parts = explode('<font size="-1">', $item->description);

    $feeds[$i]['title'] = (string) $item->title;
    $feeds[$i]['link'] = (string) $item->link;
    $feeds[$i]['image'] = $match[1];
    $feeds[$i]['site_title'] = strip_tags($parts[1]);
    $feeds[$i]['story'] = strip_tags($parts[2]);
    $feeds[$i]['date']=  (string) $item->pubDate;
    $json["crops"][]=array(
                            'id'=>('1234'),
		'name'=>(strip_tags($parts[1])),
                            'by'=>(string) $item->title,
		'desc'=>(strip_tags($parts[0])),
		'date'	=>date('D d M Y',strtotime($item->pubDate)),
		'link'=>((string) $item->link),
		'imgread'=>("http://".$match[1])
	);
    $i++;
}

             $this->response->setOutput(json_encode($json));
} 



public function getNewsById(){

		    $mcrypt=new MCrypt();
            $log=new Log("newsid.log");
             $log->write($this->request->post);
             $log->write($this->request->get);
             $log->write(($this->request->get['id']));
	        $this->adminmodel('setting/store');
			$jsons = $this->model_setting_store->getNewsByID(($this->request->get['id']));
			$json=array('crops'=>array());
			foreach ($jsons as $ids) {		
			$json['crops'][] = array(
                        'id' => ($ids['NewsItemID']),
                        'name'       =>($ids['NewsHeader']),
			'by'	=>($ids['PublishedBy']),
			'desc' =>	($ids['NewsDetails']),
			'img'	=>	($ids['NewsImage']),
			'date'	=>	($ids['DatePublished'])	
                                     );
				}
		$this->response->addHeader('Content-Type: application/json');
             $this->response->setOutput(json_encode($json));
}
//end news



public function getTransport(){

		    $mcrypt=new MCrypt();
	        $this->adminmodel('setting/store');
			$jsons = $this->model_setting_store->getTransport();

			foreach ($jsons as $ids) {		
			$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['trans_id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
                                     );
				}
             $this->response->setOutput(json_encode($json));
}

public function upload()
{

//upload file
            $log=new Log("upload.log");
             $log->write($this->request->post);
             $log->write($this->request->files);
             //log to table
        
   
                $this->load->model('account/activity');

                $activity_data = $this->request->post;

                $this->model_account_activity->addActivity('upload', $activity_data);
            
       
        //
            
        $this->load->language('api/upload');

        $json = array();



        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                // Sanitize the filename
                $filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

                if ((utf8_strlen($filename) < 1) || (utf8_strlen($filename) > 128)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                // Allowed file extension types
                $allowed = array();

                $extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

                $filetypes = explode("\n", $extension_allowed);

                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

               /* if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }*/

                // Allowed file mime types
                $allowed = array();

                $mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

                $filetypes = explode("\n", $mime_allowed);
		$log->write($filetypes);
                foreach ($filetypes as $filetype) {
                    $allowed[] = trim($filetype);
                }

                /*if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }*/

                // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            $file = $filename . '.' . md5(mt_rand());

            move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

            // Hide the uploaded file name so people can not link to it directly.
                       
            $json['success'] = $this->language->get('text_upload');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    
        
//

}

public function setgeo()
{
		//name,address,geocode,telephone,fax,image,open,comment
		$log=new Log("geo-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$keys = array(
			'username',
			'geocode',
			'store_id',
			'name',
			'address'
		);
		$log->write("in geo");
		$log->write($this->request->post);
		foreach ($keys as $key) {            
                	$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;            
        	}
		$this->request->post['fax']=$this->request->post['store_id'];
		
		$this->load->language('localisation/location');
		$this->adminmodel('localisation/location');
		$data=$this->model_localisation_location->getLocationStore($this->request->post['store_id']);
		$log->write($data);
		if(empty($data)){
		$this->model_localisation_location->addLocation($this->request->post);		 
		}else{
			$this->model_localisation_location->editLocation($data["location_id"],$this->request->post);		 
		}
		$this->adminmodel('setting/setting');
		$this->model_setting_setting->editSettingValue('config','config_geocode',$this->request->post['geocode'],$this->request->post['store_id']);
		//also update geocode in setting table as per store
		$json['success'] = $mcrypt->encrypt("Success: Geo data saved");
		$json['geocode'] = $mcrypt->encrypt($this->request->post['geocode']);
		$this->response->setOutput(json_encode($json));

}

public function sendtorunner()
    {
  
        $log=new Log("cash-new".date('Y-m-d').".log"); 
		$log->write('sendtorunner called');
		$log->write($this->request->post);
		$mcrypt=new MCrypt();
	    $this->adminmodel('pos/pos');			       
			$data=array();
			
			$data['userid']=$mcrypt->decrypt($this->request->post['ce']);     
			$data['amount']=$mcrypt->decrypt($this->request->post['amount']); 
			$data['username']=$mcrypt->decrypt($this->request->post['store_incharge_name']);//$mcrypt->decrypt($this->request->post['ce_name']);  
			$data['storeid']=$mcrypt->decrypt($this->request->post['store_id']);   
			$data['storename']= $this->model_pos_pos->getstorename($data['storeid']);//$mcrypt->decrypt($this->request->post['storename']);  			
			$log->write($data); 
			//$log->write($userid);
			$ids = $this->model_pos_pos->getusermobile($data['userid']);
			$log->write($ids);
		
			$mobile=$ids['username'];//'9911427348';// 
			$log->write($mobile);
			if(strlen($mobile)==9)
			{
				$mobile=$ids['email']; 
			}
			//SMS LIB
			$this->load->library('sms');	
			$sms=new sms($this->registry);
			$pin = rand(1000, 9999);
		 
			$data['otp']=$pin;
			$log->write('before sendimg to sms'); 
			$log->write($data); 
			$sms->sendsms($mobile,"19",$data);
			
		  
			$query_return=$this->model_pos_pos->insert_runner_otp($data['userid'],$data['storeid'],$pin);
			$log->write($query_return);
			if($query_return>0)
			{
			$this->response->setOutput('1');
			}
			else
			{
				$this->response->setOutput('0');
			}
    }


public function setank()
{

		$log=new Log("cash-new".date('Y-m-d').".log");
		$log->write('setank called');
		$mcrypt=new MCrypt();
		$log->write($this->request->post);

            		$this->load->model('account/customer');       
            		$data=array();
			
			$otp=$mcrypt->decrypt($this->request->post['ttp']);
		
			$data['ce_id']=$mcrypt->decrypt($this->request->post['ce']);
			$log->write("before called to model to check otp");
			$chekotp=$this->model_account_customer->chekrunnerotp($otp,$data['ce_id']);
			$log->write("Otp Check Valid or not");
			$log->write($chekotp);
			if($chekotp->num_rows>0 and ($chekotp->row['otp']==$otp))
			{
			
			$data['bank_id']=$mcrypt->decrypt($this->request->post['bid']);
			$data['bank_name']=$mcrypt->decrypt($this->request->post['bname']);
			$data['amount']=$mcrypt->decrypt($this->request->post['bamt']);
			$data['user_id']=$mcrypt->decrypt($this->request->post['username']);
			$data['store_id']=$mcrypt->decrypt($this->request->post['store_id']);

			$data['user_id']=$mcrypt->decrypt($this->request->post['username']);
			$data['ce_name']=$mcrypt->decrypt($this->request->post['ce_name']);

 			$log->write($data); 
			if(!empty($data['amount']) && !empty($data['store_id'])){
                                             
                                             $current_cash= $this->model_account_customer->get_current_cash($data['user_id']);
                                             $log->write($current_cash);
                                           if(($current_cash<$data['amount'])  && ($data['bank_id']!="4"))
                                           {
                                               $log->write('Error: Amount can not be greater then Cash in Hand.');
                                               $json['success'] = 'Error: Amount can not be greater then Cash in Hand.';
                                           }
             			else
             			{
								$jsons = $this->model_account_customer->addbankTrans($data);
								$log->write($jsons);
								$this->adminmodel('runner/cash');
								$this->model_runner_cash->add_to_trans_table($jsons,$data['ce_id'],"CR",$data['amount']);
								$this->model_runner_cash->add_to_runner_credit($data['ce_id'],$data['amount']);
								$json['success'] = 'Success: Transaction added.';
								
			 }

			} 
			else{
				if(empty($data['amount'])){
				$json['success'] = 'Error: Amount can not be zero.';
				}
				if(empty($data['store_id'])){
				$json['success'] = 'Error: You are not authorized.';
				}

				}  
		      
}
else
			 {
			  $json['error'] = 'OTP is not Matched';
			 }
			  $this->response->setOutput(json_encode($json));
}
public function getank()
{

	    $mcrypt=new MCrypt();

             $this->load->model('account/customer');       
                    
			$jsons = $this->model_account_customer->getank();

foreach ($jsons as $ids) {		
$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['bank_id']),
                        'name'       =>$mcrypt->encrypt($ids['bank']),
			'aname' =>$mcrypt->encrypt($ids['bank_account_name']),
			'anum' => $mcrypt->encrypt($ids['bank_account_number']),
			'atype' => $mcrypt->encrypt($ids['bank_account_type']),
			'acode' => $mcrypt->encrypt($ids['bank_ifsc_code']),
			'abranch' => $mcrypt->encrypt($ids['bank_branch'])

                                            );
}

$this->response->setOutput(json_encode($json));



}


public function gethelp()
{

	    $mcrypt=new MCrypt();

             $this->load->model('account/customer');       
                    
			$jsons = $this->model_account_customer->gethelp();

foreach ($jsons as $ids) {		
$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['help_id']),
                        'name'       =>$mcrypt->encrypt($ids['help']),
			'aname' =>$mcrypt->encrypt($ids['help_name']),
			'anum' => $mcrypt->encrypt("Email - ".$ids['help_email']),
			'atype' => $mcrypt->encrypt($ids['help_type']),
			'acode' => $mcrypt->encrypt("Phone - ".$ids['help_number']),
			'abranch' => $mcrypt->encrypt($ids['help_branch'])

                                            );
}

$this->response->setOutput(json_encode($json));



}

public function getanktrans()
{

	    $mcrypt=new MCrypt();
$log=new Log("cash-new".date('Y-m-d').".log");
$log->write($this->request->post);
             $this->load->model('account/customer');       
                    
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$sid=$mcrypt->decrypt($this->request->post['store_id']);
			$jsons = $this->model_account_customer->getanktrans($uid,$sid);
$log->write($jsons);

foreach ($jsons as $ids) {		
$json['crops'][] = array(
                        'name'       =>$mcrypt->encrypt($ids['name']),
                        'aname'       =>$mcrypt->encrypt($ids['bank_name']),
			'pirce' =>$mcrypt->encrypt($ids['amount']),
			'date_added' => $mcrypt->encrypt($ids['date_added']),
			'rstatus'       => $mcrypt->encrypt($ids['status']),
			'runner_name'       => $mcrypt->encrypt($ids['runner_name']),
			'transid'       => $mcrypt->encrypt($ids['transid'])
	
						
                                            );
}
$this->adminmodel('pos/pos'); 
                $balance = $this->model_pos_pos->get_user_balance($uid);
                $json['cash'] = $mcrypt->encrypt($balance['cash']); 
	$store_balance = $this->model_pos_pos->get_store_cash_balance($sid);
	$json['storecash'] = $mcrypt->encrypt($store_balance['cash']); 
	$log->write('user balance: '.$balance['cash']);
	$log->write('store balance: '.$store_balance['cash']);
$this->response->setOutput(json_encode($json));

}


public function addaff()
{

$log=new Log("addaff.log");
	    $mcrypt=new MCrypt();
$log->write($this->request->post);

		$keys = array(
			'username',
			'firstname',
	'lastname',
	'payment',
	'telephone',
	'city',
	'postcode',
	'code',
	'bank_name',
'bank_branch_number',
'bank_swift_code',
'bank_account_name',
'bank_account_number',
'rabi_crop_1',
'rabi_crop_2',
'kharif_crop_1',
'kharif_crop_2',
'kharif_crop_1_acre',
'kharif_crop_2_acre',
'rabi_crop_1_acre',
'rabi_crop_2_acre',
'Address',
'email',
'fax',
'store_id'

		);
foreach ($keys as $key) {
            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
$log->write($this->request->post);


		$this->load->language('marketing/affiliate');
              $json = array();
             
		$this->request->post['password']="ufc@unnati";
		$this->request->post['confirm']="ufc@unnati";                 
             
$json['error']="";

//
if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) 
{
			   $json['error']  = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			 $json['error']  = $this->language->get('error_lastname');
		}

		/*if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email']))) {
			 $json['error']  = $this->language->get('error_email');
		}*/

		if ($this->request->post['payment'] == 'cheque') {
			if ($this->request->post['cheque'] == '') {
				 $json['error']  = $this->language->get('error_cheque');
			}
		} elseif ($this->request->post['payment'] == 'paypal') {
			if ((utf8_strlen($this->request->post['paypal']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['paypal'])) {
				$this->error['paypal'] = $this->language->get('error_paypal');
			}
		} elseif ($this->request->post['payment'] == 'bank') {
			if ($this->request->post['bank_account_name'] == '') {
				 $json['error']  = $this->language->get('error_bank_account_name');
			}

			if ($this->request->post['bank_account_number'] == '') {
				 $json['error']  = $this->language->get('error_bank_account_number');
			}
		}
$this->request->post['address_1']=$this->request->post['Address'];
	$this->adminmodel('marketing/affiliate');

$log->write("out");
		$affiliate_info = $this->model_marketing_affiliate->getAffiliateByTelephone($this->request->post['telephone']);
$log->write("out1");
		if (!isset($this->request->get['affiliate_id'])) {
			if ($affiliate_info) {
				 $json['error']  = $this->language->get('error_exists');
			}
		} else {
			if ($affiliate_info && ($this->request->get['affiliate_id'] != $affiliate_info['affiliate_id'])) {
				 $json['error']  = $this->language->get('error_exists');
			}
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			 $json['error']  = $this->language->get('error_telephone');
		}

		if ($this->request->post['password'] || (!isset($this->request->get['affiliate_id']))) {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				 $json['error']  = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				 $json['error']  = $this->language->get('error_confirm');
			}
		}

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			 $json['error']  = $this->language->get('error_address_1');
		}

		if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
			 $json['error']  = $this->language->get('error_city');
		}
$log->write("out3");
		$this->adminmodel('localisation/country');
$log->write("out4");
$this->request->post['country_id']="99";
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			 $json['error']  = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '') {
			 $json['error']  = $this->language->get('error_country');
		}
		$this->request->post['zone_id']="1";
		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
			 $json['error']  = $this->language->get('error_zone');
		}

		if (!$this->request->post['code']) {
			 $json['error']  = $this->language->get('error_code');
		}

	$this->request->post['user_store_id']=$this->request->post['store_id'];
//

$log->write("out");
$log->write($json['error']);

	if( $json['error']=="")	
{

$log->write("in");


			$this->model_marketing_affiliate->addAffiliate($this->request->post);
		$json['success']="Success: UFC added.";
}
             $this->response->setOutput(json_encode($json));


}


public function getaff()
{
$log=new Log("viewaff.log");
	    $mcrypt=new MCrypt();
$log->write($this->request->post);

		$this->load->language('marketing/affiliate');
$log->write("after language");
$this->adminmodel('marketing/affiliate');
$data['products'] = array();




		$filter_data = array(
			'filter_store'   => $mcrypt->decrypt($this->request->post["store_id"]),
			'filter_user_id' => $mcrypt->decrypt($this->request->post["username"]),
			'filter_name'       => $filter_name,
			'filter_email'      => $filter_email,
			'filter_status'     => $filter_status,
			'filter_approved'   => $filter_approved,
			'filter_date_added' => $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$results = $this->model_marketing_affiliate->getAffiliates($filter_data);

		foreach ($results as $result) {
											
			$data['products'][] = array(
				'id' => $mcrypt->encrypt($result['affiliate_id']),
				'name'         => $mcrypt->encrypt($result['firstname']),
				'acode'		=>$mcrypt->encrypt($result['code']),
				'telephone'        => $mcrypt->encrypt($result['telephone']),
				'abranch'        => $mcrypt->encrypt($result['city']),
				'balance'      => $mcrypt->encrypt($this->currency->format($result['balance'], $this->config->get('config_currency'))),
				'status'       =>$mcrypt->encrypt( ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'))),
				'date_added'   =>$mcrypt->encrypt( date($this->language->get('date_format_short'), strtotime($result['date_added']))),
							);
		}
             $this->response->setOutput(json_encode($data));



}




public function getCrops(){

	    $mcrypt=new MCrypt();

             $this->load->model('account/customer');       
                    
			$jsons = $this->model_account_customer->getCrops();

foreach ($jsons as $ids) {		
$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['id']),
                        'name'       =>($ids['name']),
                                            );
}
             $this->response->setOutput(json_encode($json,JSON_UNESCAPED_UNICODE));
}


public function getStoreNoAdd(){

		    $mcrypt=new MCrypt();
	        $this->adminmodel('setting/store');
			$jsons = $this->model_setting_store->getStores();
			foreach ($jsons as $ids) {		
			$json['crops'][] = array(
                        'id' => $mcrypt->encrypt($ids['id']),
                        'name'       =>$mcrypt->encrypt($ids['name']),
                                     );
				}
             $this->response->setOutput(json_encode($json));
}







public function addcustomer_by_mobile()
{
             
            $mcrypt=new MCrypt();
            $log=new Log("addcust-".date('Y-m-d').".log");

            $log->write($this->request->post);
		 
             //
              $json = array();
             /*
             if($this->request->post['firstname']==''){
                 $json['error'] = 'Error: Please firstname name.';

             }
             if($this->request->post['lastname']=='')
             {
                 $json['error'] = 'Error: Please enter lastname name.';
                 $this->response->setOutput( json_encode($json));
                 die();
             } 

             if($this->request->post['card']=='')
             {
                $json['error'] = 'Error: Please enter card number.';

             }
             
             */
            if($this->request->post['telephone']=='')
             {
                 $json['error'] = 'Error: Please enter telephone number.';
             }
             if($this->request->post['longtitude']=='')
             {
                 $json['error'] = 'Error: Please enter longtitude.';

             }
             if($this->request->post['lattitude']=='')
             {
                 $json['error'] = 'Error: Please enter lattitude.';
             }
             
             $log->write("check");
             //check mobilenummber exits

            $keys = array(
			'username',
			'store_id',
			'telephone',
			'village',
			'pincode',
			'firstname',
			'card',
			'crop1',
			'crop2',
			'acre1',
			'acre2',
                                          'longtitude',
                                          'lattitude',
                                          'muid'	
		);

            foreach ($keys as $key) 
            {
            
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;//$this->request->get[$key]; //
            
             }

             $log->write($this->request->post);

             $this->adminmodel('sale/customer');       
             	if (isset($this->request->post['telephone']) ) 
              {
	   $customer_info = $this->model_sale_customer->getCustomerByEmailApp($this->request->post['telephone']);
	}
             $log->write($customer_info);

//print_r($this->request->post);
             //print_r($customer_info["customer_id"]);
//exit;
             if (empty($customer_info))
             {
             $log->write("check if");
             unset($this->session->data['cid']);
             $this->request->post['email']=($this->request->post['telephone']);
             $this->request->post['fax']=($this->request->post['telephone']);
             $this->request->post['password']=($this->request->post['telephone']);
             $this->request->post['customer_group_id']="1";
             $this->request->post['newsletter']='0';        
             $this->request->post['approved']='1';
             $this->request->post['status']='1';
             $this->request->post['safe']='1';
             $this->request->post['address_1']= ($this->request->post['village']);
             $this->request->post['address_2']= ($this->request->post['village']);
             $this->request->post['city']= ($this->request->post['village']);
             $this->request->post['company']='Unnati';
             $this->request->post['country_id']='0';
             $this->request->post['zone_id']='0';
             $this->request->post['postcode']='0';
             $this->request->post['store_id']=$this->request->post['store_id'];             
             $this->request->post['address']=array($this->request->post);
             $this->request->post['imei']=$this->request->post['muid'];    
             $this->model_sale_customer->addCustomer_by_mobile($this->request->post);
             
               if(isset($this->session->data['cid']))
               {
                 $json['id']=$mcrypt->encrypt($this->session->data['cid']);
                 $log->write($this->session->data['cid']);
                 $log=new Log("recharge-".date('Y-m-d').".log");
                 $log->write('now we will call the thread'.$this->session->data['cid']);
               
                $asyncOperation=new AsyncOperation($this->request->post['telephone'],$this->request->post['muid'],'6');
                $log->write('now we will call the thread 3');
	  $asyncOperation->start();
                $log->write('now we will call the thread 4');
               }
           
             }
             else
             {
                 if(!isset($json['error']))
                 {
                    $json['id']=$mcrypt->encrypt($customer_info["customer_id"]);
                    $log->write($customer_info["customer_id"]);
	   }
             }
             //html update              
             
             if(!isset($json['error']))
             {
                            $json['success'] = 'Success: customer added.';
                            $log->write($this->request->post['telephone']);
	              	
                            $json['telephone'] = $mcrypt->encrypt($this->request->post['telephone']); 
	              //sms send
          	              //SMS LIB
		$this->load->library('sms');	
		$sms=new sms($this->registry);
                            $sms->sendsms($this->request->post['telephone'],"1",$this->request->post);       
             }
	$log->write($json);	          

            $this->response->setOutput(json_encode($json));
             //                                       
        }

        public function Customer(){
$log=new Log("cust.log");

$log->write($this->request->get);
	    $mcrypt=new MCrypt();
            $this->adminmodel('pos/pos');
            $q =$mcrypt->decrypt($this->request->get['q']);
            $json = $this->model_pos_pos->searchCustomer($q);
		$njson['api_ids'] = array();
foreach ($json as $ids) {

	    $jsons = $this->model_pos_pos->getCustomer($ids['customer_id']);
                    $njson['api_ids'][] = array(
                        'api_id' => $mcrypt->encrypt($jsons['customer_id']),
                        'api_name'       =>$mcrypt->encrypt($jsons['firstname']." ".$jsons['lastname']),
                        'api_cash'        =>$mcrypt->encrypt($jsons['telephone']),
                    );
		}				
            return $this->response->setOutput(json_encode($njson));
        }



        public function getcustomer(){
$log=new Log("cust-".date('Y-m-d').".log");

$log->write($this->request->post);
	    $mcrypt=new MCrypt();
            $this->adminmodel('pos/pos');
            $this->load->model('account/customer');
            $sid =$mcrypt->decrypt($this->request->post['store_id']);
            $uid =$mcrypt->decrypt($this->request->post['username']);



		$njson['products'] = array();

if(isset($this->request->get['q'])){


            $q =$mcrypt->decrypt($this->request->get['q']);
            $json = $this->model_pos_pos->searchCustomer($q);
		$njson['api_ids'] = array();
foreach ($json as $ids) {
            $jsons = $this->model_pos_pos->getCustomer($ids['customer_id']);
            if($this->model_account_customer->getLastOrderDate($jsons['customer_id'])["date_added"]=="")
            {
            $date_added="";
            }
            else
            {
            $date_added=date('d/m/Y',strtotime($this->model_account_customer->getLastOrderDate($jsons['customer_id'])["date_added"]));
            }
	    
                    $njson['products'][] = array(
                        'id' => $mcrypt->encrypt($jsons['customer_id']),
                        'name'       =>$mcrypt->encrypt($jsons['firstname']." ".$jsons['lastname']),
                        'telephone'        =>$mcrypt->encrypt($jsons['telephone']),
			'date_added' =>$mcrypt->encrypt($date_added) 
                    );
		}



}
else{

	    $jsons = $this->model_pos_pos->getCustomers($sid,$uid);
foreach ($jsons as $ids) {
            if($this->model_account_customer->getLastOrderDate($ids['customer_id'])["date_added"]=="")
            {
            $date_added="";
            }
            else
            {
            $date_added=date('d/m/Y',strtotime($this->model_account_customer->getLastOrderDate($ids['customer_id'])["date_added"]));
            }
                    $njson['products'][] = array(
                        'id' => $mcrypt->encrypt($ids['customer_id']),
                        'name'       =>$mcrypt->encrypt($ids['firstname']." ".$jsons['lastname']),
                        'telephone'        =>$mcrypt->encrypt($ids['telephone']),
			'date_added' =>$mcrypt->encrypt($date_added)
			
                    );
		}

	}	


		
            return $this->response->setOutput(json_encode($njson));
        }






	public function index() {




		$this->load->language('api/customer');

		// Delete past customer in case there is an error
		unset($this->session->data['customer']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'customer_id',
				'customer_group_id',
				'firstname',
				'lastname',
				'email',
				'telephone',
				'fax'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			// Customer
			if ($this->request->post['customer_id']) {
				$this->load->model('account/customer');

				$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

				if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
					$json['error']['warning'] = $this->language->get('error_customer');
				}
			}

			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email']))) {
				$json['error']['email'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			// Customer Group
			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

			foreach ($custom_fields as $custom_field) {
				if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}

			if (!$json) {
				$this->session->data['customer'] = array(
					'customer_id'       => $this->request->post['customer_id'],
					'customer_group_id' => $customer_group_id,
					'firstname'         => $this->request->post['firstname'],
					'lastname'          => $this->request->post['lastname'],
					'email'             => $this->request->post['email'],
					'telephone'         => $this->request->post['telephone'],
					'fax'               => $this->request->post['fax'],
					'custom_field'      => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : array()
				);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


public function setCash()
{ 
    $mcrypt=new MCrypt();
    $this->adminmodel('setting/store');
    $log=new Log("setcash-".date('Y-m-d').".lpg");
    $store_name=$mcrypt->decrypt($this->request->post['store_name']);
    $store_id=$mcrypt->decrypt($this->request->post['store_id']);
    $user_id=$mcrypt->decrypt($this->request->post['user_id']);
    $amount=$mcrypt->decrypt($this->request->post['amount']);
    $mobile=$mcrypt->decrypt($this->request->post['user_id']);
    $name=$mcrypt->decrypt($this->request->post['name']);
    $day=$mcrypt->decrypt($this->request->post['day']);
    $log->write($this->request->post);
    if(!empty($day))
     {
        $update_date=date('Y-m-d', strtotime(' -1 day'));
     }
     else
     {
       $update_date=date('Y-m-d h:i:s');
     }
     $jsons = $this->model_setting_store->setCash( $store_name,$store_id,$user_id,$amount,$mobile,$name,$update_date);  

     $json['success'] = 'Success: Transaction added.';

     $this->response->setOutput(json_encode($json));

	
}

public function getcashtrans()
{
$mcrypt=new MCrypt();
$log=new Log("cash.log");
$log->write($this->request->post);
$this->adminmodel('setting/store');      
      $sid=$mcrypt->decrypt($this->request->post['store_id']);
      $jsons = $this->model_setting_store->getcashtrans($sid);
$log->write($jsons);

$lamount = $this->model_setting_store->getcashpostion($sid);

$json['lamt']=$mcrypt->encrypt($lamount);
foreach ($jsons as $ids) {		
$json['crops'][] = array(
                        'name'       =>$mcrypt->encrypt($ids['name']),
                        'aname'       =>$mcrypt->encrypt($ids['store_name']),
			'pirce' =>$mcrypt->encrypt($ids['amount']),
			'date_added' => $mcrypt->encrypt($ids['update_date']),
						
                                            );
}

$this->response->setOutput(json_encode($json));



}

public function getCircles(){

                            $log=new Log("custcircles-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
	        $this->adminmodel('setting/store');
		$log->write($this->request->post);			       
               $store_id=$mcrypt->decrypt($this->request->post['store_id']);
		$log->write($store_id);

	 	$jsons = $this->model_setting_store->getCircles($store_id);
if(!empty($jsons)){
		foreach ($jsons as $ids) {
			
		$json['crops'][] = array(
                'id' => $mcrypt->encrypt($ids['circle_code']),
                'name'       =>$mcrypt->encrypt($ids['name']),
                'crlimit'       =>$mcrypt->encrypt($ids['creditlimit']),
                'ccredit'       =>$mcrypt->encrypt($ids['currentcredit']),
                    
			
                 );}
		
	$log->write($json);
			
				
             $this->response->setOutput(json_encode($json));
}
else{
            $this->response->setOutput("0");//json_encode($json));
}

	}

    public function getCircleLimit(){
                            $log=new Log("curcircle-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
	        $this->adminmodel('setting/store');
			       
                $store_id=$mcrypt->decrypt($this->request->post['store_id']);
                $circle_id=$mcrypt->decrypt($this->request->post['circle_id']);

		$log->write($store_id);
		$ids = $this->model_setting_store->getCircleCredit($circle_id ,$store_id);


						$log->write($ids);

		$json['crops'][] = array(
                'id' => $mcrypt->encrypt($ids['circle_code']),
                'name'       =>$mcrypt->encrypt($ids['name']),
                'crlimit'       =>$mcrypt->encrypt($ids['creditlimit']),
                'ccredit'       =>$mcrypt->encrypt($ids['currentcredit']),
		'number'       =>$mcrypt->encrypt($ids['currentcredit']),                    
			
                 );

             $this->response->setOutput(json_encode($json));
	}

 public function getStorelocation(){
                            $log=new Log("Storelocation-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
	        $this->adminmodel('setting/store');
			       
                
		$jsons = $this->model_setting_store->getStorelocation();
                            $log->write($ids);

                          foreach ($jsons as $ids) {
                                          if($ids['store_geo']!="")
                                          {
			$json['geolocations'][] = array(
                'store_id' => $mcrypt->encrypt($ids['store_id']),
                'store_name'       =>$mcrypt->encrypt($ids['store_name']),
                'store_geo'       =>$mcrypt->encrypt($ids['store_geo']),
	  'store_address'       =>$mcrypt->encrypt($ids['store_address'])
                                 
			
                 );
		}	
                  }
		

             $this->response->setOutput(json_encode($json));
	}

public function getnotifications()
{

	    $mcrypt=new MCrypt();
                  $log=new Log("notofication-".date('Y-m-d').".log");

             $this->adminmodel('notification/notification');      
                    
			
			//$sid=$mcrypt->decrypt($this->request->post['store_id']);
			$jsons = $this->model_notification_notification->getnotifications();
$log->write($jsons);

foreach ($jsons as $ids) {		
$json['notifications'][] = array(
                                          'description'       =>$mcrypt->encrypt($ids['description']),
                   	              'name'       =>$mcrypt->encrypt($ids['name']),
			'heading' =>$mcrypt->encrypt($ids['heading']),
			'imgurl' => $mcrypt->encrypt($ids['imgurl']),
			'title' => $mcrypt->encrypt($ids['title']),
			'status' => $mcrypt->encrypt($ids['status']),
			'create_time ' => $mcrypt->encrypt($ids['create_time ']),
						
                                            );
}

$this->response->setOutput(json_encode($json,JSON_UNESCAPED_UNICODE));



}
public function getnotificationscount()
{

	   		$mcrypt=new MCrypt();
                  	$log=new Log("notofication-".date('Y-m-d').".log");
             		$this->adminmodel('notification/notification');                          			
			$uid=$mcrypt->decrypt($this->request->post['username']);
			$jsons = $this->model_notification_notification->getnotificationscount($uid);
			$log->write($jsons);

			foreach ($jsons as $ids) {		
			$json=$mcrypt->encrypt($ids['count']);						                                         
			}
			$this->response->setOutput($json);

}
public function getstoreledger()
{

        $mcrypt=new MCrypt();
                  $log=new Log("store-ledger-".date('Y-m-d').".log");

                     $this->adminmodel('report/storelazer');  
                  $store_id=$mcrypt->decrypt($this->request->post['store_id']);
        $filter_date_start=$mcrypt->decrypt($this->request->post['filter_date_start']);
        $filter_date_end=$mcrypt->decrypt($this->request->post['filter_date_end']);
        $page=$mcrypt->decrypt($this->request->post['page']);
        //$store_id=8;
        $filter_data = array(
            'filter_date_start'         => $filter_date_start,
            'filter_date_end'         => $filter_date_end,
            'filter_stores_id' => $store_id,
            'start'                  => ($page - 1) * 20,
            'limit'                  => 20
        );
    $results = $this->model_report_storelazer->getStorecash($filter_data);
//print_r($results);
    $json=array();
    foreach ($results as $result) {
           
            if($result['Withdrawals']!="")
            {
            $amount="-".$this->currency->format($result['Withdrawals']);
            }
            if($result['Deposite']!="")
            {
            $amount=$this->currency->format($result['Deposite']);
            }
            $data['ledger'][] = array(
                'amount'       => $mcrypt->encrypt($amount),
                'tr_type'      => $mcrypt->encrypt($result['Mode']),
                'remarks'      => $mcrypt->encrypt($result['remarks']),
                'order_id'   => $mcrypt->encrypt($result['order_id']),
                'payment_method'      => $mcrypt->encrypt($result['Mode']),
                            'updated_credit'      => $mcrypt->encrypt($result['Credit_Balance']),
                            'updated_cash'      => $mcrypt->encrypt($result['Cash_Balance']),
                            'create_time'      => $mcrypt->encrypt($result['Date']),
                            'store_name'      => $mcrypt->encrypt($result['store_name']),
                            'user_name'      => $mcrypt->encrypt($result['user_Name'])

                           
                               
            );
//print_r($result);
        }
//print_r($data);

$log->write($jsons);
$this->response->setOutput(json_encode($data,JSON_UNESCAPED_UNICODE));
//$this->response->setOutput();
}
}
/////////////////////////////////////////////////
class AsyncOperation extends Thread {

    public function __construct($mobile,$muid,$scheme) {
        $this->mobile = $mobile;
        $this->muid = $muid;
        $this->scheme = $scheme;
        

    }

    public function run() {

	$log=new Log("recharge-".date('Y-m-d').".log");
		 $mcrypt=new MCrypt();
	$log->write('come in run at thread'); 
	$log->write($this->mobile."&&".$this->muid."&&".$this->scheme);
	$log->write($this->products);
	if (($this->mobile) && ($this->muid) && ($this->scheme) ) 
	{

		
	       	$request = "https://unnati.world/shop/index.php?route=mpos/recharge/rechargetest&mobile=".$this->mobile."&muid=".$this->muid."&scheme_id=".$this->scheme;
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
