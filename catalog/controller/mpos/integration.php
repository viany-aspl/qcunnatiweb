<?php
class GROWER{
    public $id;
    public $uid;
    public $name;
    public $fname;
    public $village;
    public $status;
    public $message;  
   
    }
class Controllermposintegration extends Controller{
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
function getDatabyGrowerId()
{
		$log=new Log("Card-getDatabyGrowerId-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->adminmodel('pos/dscl');	
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->get["grower_id"]);	
		$this->request->post["unit_id"]=$mcrypt->decrypt($this->request->get["unit_id"]);
		if(empty($this->request->post["unit_id"]))
		{
			$this->adminmodel('unit/unit');
			$this->request->post["unit_id"]=$this->model_unit_unit->getUnitByGrowerID($this->request->post["grower_id"]);
		}
		
		$log->write($this->request->post);	
		$datamobnew=$this->{'model_pos_dscl'}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);
		$log->write("GetGrowerCardMob check");
		$log->write($datamobnew);
		 $njson['api_ids'][] = array(
                        'api_id' => $mcrypt->encrypt($this->request->post["grower_id"]),
                        'api_name'       =>$mcrypt->encrypt($datamobnew['RYOT_NAME']),
                        'api_cash'        =>$mcrypt->encrypt($datamobnew['MOB']),
						'VNAME'        =>$mcrypt->encrypt($datamobnew['VNAME']),
						'VILLAGE_CODE'        =>$mcrypt->encrypt($datamobnew['VILLAGE_CODE']),
						'FTH_HUS_NAME'        =>$mcrypt->encrypt($datamobnew['FTH_HUS_NAME']),
                    );
						$log->write($njson);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($njson));	
}


function resend()
{		
		
		$log=new Log("Resend-grower-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->request->post["cdata"]=$mcrypt->decrypt($this->request->post["cdata"]);		
		$this->request->post["cdata"]=json_decode($this->request->post["cdata"]);		
		$log->write($this->request->post["cdata"]);
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["cdata"]->GC);		
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt($this->request->post["cdata"]->CSN);		
		$this->adminmodel('card/integration');	
		$datamob=$this->model_card_integration->getmobileno($this->request->post);
		$log->write($datamob);				
		if(!empty($datamob)){
		$dataotptrans=$this->model_card_integration->check_otp_trans($this->request->post);
			$log->write($dataotptrans);
		if(!empty($dataotptrans))
			{			 						
				$mob=$datamob['MOB'];
				$otp=$dataotptrans['otp'];
				//send sms 
				
			}
		
		}			
		
		}

	public function getFmType()
	{
		$log=new Log("getFmType-".date('Y-m-d').".log");
		    $mcrypt=new MCrypt();	        		
			$this->adminmodel('pos/pos');
		$log->write("model");
		$data=array();
		$data['storeid']=$mcrypt->decrypt($this->request->post["store_id"]);				
		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
		if(!empty($companydata)){
		$data['unitid']=$companydata[0]['unit_id'];
		$log->write($companydata);
		$log->write($data);
		$company=strtolower($companydata[0]['company_name']);
		$log->write($company);
		$this->adminmodel('pos/'.$company);
		$results = $this->{'model_pos_' . $company}->getFM("GetFM",$data,0);
		$log->write($results);
				if(!empty($results)){					
		$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput($results);
		$json = array('success' => true, 'metadata' => array());
			$jsons = $results;
			foreach ($jsons as $ids) {		
			$json['metadata'][] = array(
                        'id' => $mcrypt->encrypt($ids['FM_CODE']),
                        'name'       =>$mcrypt->encrypt(strtoupper($ids['FM_NAME'])),
			'mob'	     =>$mcrypt->encrypt($ids['FM_MOBILE'])	
                                     );
				}
             $this->response->setOutput(json_encode($json));
		//error to display as per status			
		}
		}else{		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput("{'error':'".$this->encryptRJ256('Company not defined')."'}");}		 			 			 			 			 
	}
	function getgrowerauthentication()
	{	
		$log=new Log("Get-Authentication-grower-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$this->adminmodel('pos/pos');
		$log->write($this->request->post);
		$log->write($this->request->get);
		$data=array();
		$data['storeid']=$mcrypt->decrypt($this->request->post["store_id"]);
		$data['advanceno']=$mcrypt->decrypt($this->request->post["GrCode"]);				
		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
		$log->write($companydata);
		if(!empty($companydata)){
		$data['unitid']=$companydata[0]['unit_id'];
		$log->write($companydata);
		$log->write($data);
		$company=strtolower($companydata[0]['company_name']);
		$log->write($company);
		$this->adminmodel('pos/'.$company);
		if(isset($this->request->post["Amount"])){
				$log->write("in otp");
				$results = $this->{'model_pos_' . $company}->getAdvance("GetAdvanceDetail",$data,0);
		}
		else{
				$log->write("in search");
				$results = $this->{'model_pos_' . $company}->getAdvance("AdvanceDetailEnquiry",$data,0);			
			}
		$log->write($results);
		$growerid="";
		$fmid="";
		$deltype="";
		$trxtype="";
		$villageid="";
		$unitid="";
		if(strlen($results[0]['BankAccountNo'])==3)
		{
			$results[0]['BankAccountNo']="1".$results[0]['BankAccountNo'];
		}
		if(strlen($results[0]['BankAccountNo'])==2)
		{
			$results[0]['BankAccountNo']="11".$results[0]['BankAccountNo'];
		}
		$json = array('success' => true, 'products' => array());
		$json['products'][] = array(
					'tid'			=> $mcrypt->encrypt($results[0]['AdvanceNo']),
					'tp'			=> $mcrypt->encrypt($results[0]['OTP']),
					'vtp'			=> $mcrypt->encrypt($results[0]['OTPValidTill']),
					'bacc'		=> $mcrypt->encrypt($results[0]['BankAccountNo']),
					'apptype'	=>$mcrypt->encrypt('1'),
					'mobile'	=> $mcrypt->encrypt($results[0]['MobileNo']),
					'gid'	=> $mcrypt->encrypt($results[0]['GrowerCode']),
					'gname'	=>$mcrypt->encrypt($results[0]['GrowerName']),
					'vid'=>$mcrypt->encrypt($results[0]['VillageCode']),
					'vname'=>$mcrypt->encrypt($results[0]['VillageName']),
					'fname'=>$mcrypt->encrypt($results[0]['FatherName']),
					'amt'	=> $mcrypt->encrypt($results[0]['GrowerLimit'])
					);
					$log->write($json);
			$this->response->setOutput(json_encode($json));

	}else{		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput("{'error':'".$this->encryptRJ256('Company not defined')."'}");}
		
	}

	//card 
		
	function authentication()
	{		
		$mcrypt=new MCrypt();
		$log=new Log("Authentication-grower-card-".date('Y-m-d').".log");		
		$log->write('authentication called');
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->request->post["cdata"]=$mcrypt->decrypt($this->request->post["cdata"]);		
		$this->request->post["cdata"]=json_decode($this->request->post["cdata"]);		
		$log->write($this->request->post["cdata"]);
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["cdata"]->GC);		
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt($this->request->post["cdata"]->CSN);		
		$this->adminmodel('card/integration');	
		
		
		  $this->adminmodel('unit/unit');
		//card new 

		if(empty($this->request->post["grower_id"]))
		{
			$log->write('grower id is empty');
			/* 
			$retval['status']=$mcrypt->encrypt('0');
				
			$retval['message']=$mcrypt->encrypt('You are not authorized for billing through Card Serial Number');
			$log->write('You are not authorized for billing through Card Serial Number');
			$log->write($retval);
			$this->response->addHeader('Content-Type: application/json');		
			$this->response->setOutput(json_encode($retval));
			return; 
			*/
			//$this->adminmodel('pos/dscl');
			//$cdatanew=$this->model_pos_dscl->GetGrowerId("GetGrowerId",$this->request->post,0);
			//$log->write($cdatanew);	
			$cdatanew=$this->model_unit_unit->getGrowerIdByCard($this->request->post["Card_Serial_Number"]);
			$log->write($cdatanew);
			if(empty($cdatanew))
			{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('No Record Found');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return;
			}
			$this->request->post["grower_id"]=$cdatanew['GROWER_ID'];
			$unitdata= $this->model_unit_unit->getUnitByID($cdatanew['UNIT_ID']); 
			
			$this->request->post["cdata"]->UN=$cdatanew['UNIT_ID'];
			
			$this->request->post['CARD_UNIT']= $cdatanew['UNIT_ID'];
			$this->request->post['unit_id']= $cdatanew['UNIT_ID']; 
			$this->request->post['CARD_GROWER_ID']=$cdatanew['GROWER_ID'];
		  $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
			
		}
		else
		{
				$log->write('grower id is not empty');

				  $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
				  $this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];
				  $this->request->post['CARD_UNIT']= $this->request->post["cdata"]->UN;
				$this->request->post['unit_id']= $cdatanew['UNIT_ID']= $this->request->post["cdata"]->UN;

		}

		//

			  $this->request->post['USER']=$mcrypt->decrypt($this->request->post["username"]);
			//$this->load->library('email');
			//$email=new email($this->registry);
			//$email->sendmail('Card Authentication',$this->request->post);
			$log->write('after mail');
				$log->write($this->request->post["cdata"]->UN);
                 $unitdata= $this->model_unit_unit->getUnitByID($this->request->post["cdata"]->UN);
				 $log->write('after get unit data');
				 $log->write( $unitdata);
				 $datares="";
				 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$log->write($company);
						$this->adminmodel('pos/'.$company);
						$log->write("call");
						$log->write($this->request->post);


						$datares = $this->{'model_pos_' . $company}->GetCardStatus('GetCardStatus',$this->request->post,0); 
						//get can server GetCardStatus
						$log->write("from cane server");
						
						$log->write($datares);
						}
						$log->write("out");
					$log->write('after get datres');		
			$log->write($datares);	
			$data=array();
                        if(empty($datares))
                        {
							
                            $data['status']=$mcrypt->encrypt('0');
                            $data['message']=$mcrypt->encrypt("No response from cane server.");
							try
							{
								$data['cane_response']=$datares;
								$this->tagged_error_alert($data);
							}
							catch(Exception $e)
							{
								
							}
                        }
                        else{    					
		//get mobile number om card number or grower id
		$datamob=$this->model_card_integration->getmobileno($this->request->post);
		$log->write($datamob);	
		$log->write("Grower details check");

		$datamobnew=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);

		$log->write($datamob);
		$log->write($datamobnew);



		$datamob['MOB']=	$datamobnew['MOB'];

		if(empty($datamobnew['MOB']))
		{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('Mobile Number not found from Cane System');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return;

		}		
		//$data=array(); $datamob['CARD_STATUS']=='9' &&
		if(!empty($datamob)){		
		if($datares=='9'){
		
		$log->write("1");
		//send_otp
		if(!empty($datamob))
		{
			$log->write("2");					
			$this->request->post["MOB"]=$datamob['MOB'];
			$this->request->post["TX"]="1";
			//check otp

			$dataAuthentication = $this->{'model_pos_' . $company}->GetAuthentication('GetAuthentication',$this->request->post,0);

			$log->write("3");
			$log->write("tagged amount");
			$log->write($dataAuthentication);
			
			//$this->load->library('email');
			//$email=new email($this->registry);
			//$email->sendmail('Card Authentication -Status Matched',$dataAuthentication);			

			$log->write('user pin by app -'.$mcrypt->decrypt($this->request->post["pin"]));
			$log->write('user mpin by app -'.$mcrypt->decrypt($this->request->post["mpin"]));
		
				//check pin 
				if(empty($mcrypt->decrypt($this->request->post['mpin'])))
				{
							if($dataAuthentication['GROWER_ID'] !=$mcrypt->decrypt($this->request->post["pin"]))
							{
								$log->write("Wrong PIN.");
								$data['status']=$mcrypt->encrypt('0');
								$data['message']=$mcrypt->encrypt("Wrong PIN.");
								$data['mob']=$mcrypt->encrypt("0");
								$this->response->addHeader('Content-Type: application/json');
									$this->response->setOutput(json_encode($data));	
									return;
							}
				}
				else
				{
					$this->load->model('account/api');
					$api_info = $this->model_account_api->UserAuthorization($mcrypt->decrypt($this->request->post['username']));
					if($api_info['mpin'] !=$mcrypt->decrypt($this->request->post["pin"]))
							{
								/*
								$log->write("Wrong Master PIN.");
								$data['status']=$mcrypt->encrypt('0');
								$data['message']=$mcrypt->encrypt("Wrong Master PIN.");
								$data['mob']=$mcrypt->encrypt("0");
								$this->response->addHeader('Content-Type: application/json');
									$this->response->setOutput(json_encode($data));	
									return;
									*/
							}
				}
			//$dataAuthentication['AMOUNT']=0; 				
			if($dataAuthentication['AMOUNT']<0)
			{
				$dataAuthentication['AMOUNT']=0; 					
			}	
			$data['tagged']=$mcrypt->encrypt($dataAuthentication['AMOUNT']);
			//$dataAuthentication['VDATE'];		
			$log->write(strtotime($dataAuthentication['VDATE']));
			$log->write(time());
			$toBeComparedDate = $dataAuthentication['VDATE'];
			$today = (new DateTime())->format('Y-m-d'); 
			$expiry = (new DateTime($toBeComparedDate))->format('Y-m-d');
			$log->write(strtotime($today));
			$log->write(strtotime($expiry));
			if(strtotime($today) > strtotime($expiry))//strtotime($dataAuthentication['VDATE']) > time())
			{
			$log->write("card expiry");
			$data['status']=$mcrypt->encrypt('0');
			$data['message']=$mcrypt->encrypt("Card expired.");
			}
			else{
			$dataotptrans=0;//$this->model_card_integration->check_otp_trans($this->request->post);
			$log->write($dataotptrans);
			$log->write('totalSum');
			$this->request->post['totalSum']=round(abs($mcrypt->decrypt($this->request->post['totalSum'])),0);
			$log->write($this->request->post['totalSum']);
			
		if(empty($dataotptrans) && (!empty($dataAuthentication['AMOUNT'])))
			{			 
			//get card authentication			
			//$this->model_card_integration->send_otp($this->request->post);	
			$data['status']=$mcrypt->encrypt('1');
			//$data['message']=$mcrypt->encrypt("Wrong PIN.");
			$data['mob']=$mcrypt->encrypt($datamob['MOB']);
			$data['gname']=$mcrypt->encrypt($datamobnew['RYOT_NAME']);
			$data['vname']=$mcrypt->encrypt($datamobnew['VNAME']);
			}
			else if(empty($dataotptrans) && (empty($dataAuthentication['AMOUNT'])) && empty($this->request->post['totalSum']))
			{			 
			//get card authentication			
			//$this->model_card_integration->send_otp($this->request->post);	
			$data['status']=$mcrypt->encrypt('1');
			//$data['message']=$mcrypt->encrypt("Wrong PIN.");
			$data['mob']=$mcrypt->encrypt($datamob['MOB']);
			$data['gname']=$mcrypt->encrypt($datamobnew['RYOT_NAME']);
			$data['vname']=$mcrypt->encrypt($datamobnew['VNAME']);
			}
			else{
				
				 $log->write("in else");
				 //send same otp $dataotptrans['otp']
				 $data['status']=$mcrypt->encrypt('0');
				 $data['mob']=$mcrypt->encrypt($datamob['MOB']);
				 $data['gname']=$mcrypt->encrypt($datamobnew['RYOT_NAME']);
				 $data['vname']=$mcrypt->encrypt($datamobnew['VNAME']);
				 $data['message']=$mcrypt->encrypt("Grower Amount is Zero");
				
				}
			}
		}
		//$data['status']=$mcrypt->encrypt('1');
		}
		else{
		$data['status']=$mcrypt->encrypt('0');
		$data['message']=$mcrypt->encrypt("Card unknown status.");
		}
		$data['mob']=$mcrypt->encrypt($datamob['MOB']);
		}
		else{
		$data['status']=$mcrypt->encrypt('0');
		$data['message']=$mcrypt->encrypt("Grower number not found.");
		}
		}
		$log->write(($data));
		if(!empty($data)){
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));} 
		
}
	function resetkey()
	{		
		$mcrypt=new MCrypt();
		$log=new Log("Authentication-resetkey-".date('Y-m-d').".log");		
		$log->write('resetkey called');
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->request->post["cdata"]=$mcrypt->decrypt($this->request->post["cdata"]);		
		$this->request->post["cdata"]=json_decode($this->request->post["cdata"]);		
		$log->write($this->request->post["cdata"]);
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["cdata"]->GC);		
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt($this->request->post["cdata"]->CSN);
		$this->request->post['store_id']=$mcrypt->decrypt($this->request->post['store_id']);
		
		$this->adminmodel('card/integration');	
		$this->adminmodel('unit/unit');
		//card new 
		$log->write($this->request->post);
		if(empty($this->request->post["grower_id"]))
		{
			$log->write('grower id is empty');
			/*
			$retval['status']=$mcrypt->encrypt('0');
				
				$retval['message']=$mcrypt->encrypt('You are not authorized for billing through Card Serial Number');
				$log->write('You are not authorized for billing through Card Serial Number');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return; 
				*/ 
				
			$cdatanew=$this->model_unit_unit->getGrowerIdByCard($this->request->post["Card_Serial_Number"]);
			$log->write($cdatanew);
			if(empty($cdatanew))
			{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('No Record Found');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return;
			}
			$this->request->post["grower_id"]=$cdatanew['GROWER_ID'];
			$unitdata= $this->model_unit_unit->getUnitByID($cdatanew['UNIT_ID']); 
			
			$this->request->post["cdata"]->UN=$cdatanew['UNIT_ID'];
			
			$this->request->post['CARD_UNIT']= $cdatanew['UNIT_ID'];
			$this->request->post['unit_id']= $cdatanew['UNIT_ID']; 
			$this->request->post['CARD_GROWER_ID']=$cdatanew['GROWER_ID'];
		  $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
			
		}
		else
		{
				$log->write('grower id is not empty');

				  $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
				  $this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];
				  $this->request->post['CARD_UNIT']= $this->request->post["cdata"]->UN;
				$this->request->post['unit_id']= $cdatanew['UNIT_ID']= $this->request->post["cdata"]->UN;

		}

			  $this->request->post['USER']=$mcrypt->decrypt($this->request->post["username"]);
			
				
                 $unitdata= $this->model_unit_unit->getUnitByID($this->request->post["cdata"]->UN);
				 $log->write('after get unit data');
				 $log->write( $unitdata);
				 $datares="";
				 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$log->write($company);
						$this->adminmodel('pos/'.$company);
						$log->write("call");
						$log->write($this->request->post);


						$datares = $this->{'model_pos_' . $company}->GetCardStatus('GetCardStatus',$this->request->post,0); 
						//get can server GetCardStatus
						$log->write("from cane server");
						
						$log->write($datares);
						}
						$log->write("out");
					$log->write('after get datres');		
			$log->write($datares);	
			$data=array();
                        if(empty($datares))
                        {
                            $data['status']=$mcrypt->encrypt('0');
                            $data['message']=$mcrypt->encrypt("No response from cane server.");
                        }
                        else{    					
		//get mobile number om card number or grower id
		$datamob=$this->model_card_integration->getmobileno($this->request->post);
		$log->write($datamob);	
		$log->write("Grower details check");

		$datamobnew=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);

		$log->write($datamob);
		$log->write($datamobnew);



		$datamob['MOB']=	$datamobnew['MOB'];

		if(empty($datamobnew['MOB']))
		{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('Mobile Number not found from Cane System');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return;

		}		
		//$data=array(); $datamob['CARD_STATUS']=='9' &&
		if(!empty($datamob)){		
		if($datares=='9'){
		
		$log->write("1");
		//send_otp
		if(!empty($datamob))
		{
			$log->write("2");					
			$this->request->post["MOB"]=$datamob['MOB'];
			$this->request->post["TX"]="1";
			//check otp

			$dataAuthentication = $this->{'model_pos_' . $company}->GetAuthentication('GetAuthentication',$this->request->post,0);

			$log->write("3");
			
			$log->write($dataAuthentication);
			
				
							if(!empty($dataAuthentication['GROWER_ID']))
							{
								$log->write('in if');
								$log->write($dataAuthentication['GROWER_ID']);
								//$datamob['MOB']=8447882446;
								$cr_ln=strlen($this->request->post['CARD_SERIAL_NUMBER']);
								$this->request->post['CARD_SERIAL_NUMBER']=str_pad(substr($this->request->post['CARD_SERIAL_NUMBER'],($cr_ln-4),4),$cr_ln, "X", STR_PAD_LEFT );
								$smsdata=array('CARD_SERIAL_NUMBER'=>$this->request->post['CARD_SERIAL_NUMBER'],'PIN'=>$dataAuthentication['GROWER_ID']);
								$log->write($smsdata);
								$this->load->library('sms');
								$sms=new sms($this->registry);	  
								$sms->sendsms($datamob['MOB'], 26, $smsdata);
								//$sms->sendsms(9910077037, 26, $smsdata);
								$insert_data=array(
								'mobile'=>$datamob['MOB'],
								'store_id'=>$this->request->post['store_id'],
								'request_data'=>$this->request->post,
								'response'=>$dataAuthentication
								);
								$log->write('insert_data');
								$log->write($insert_data);
								
								
								$this->request->post['MOB']=str_pad(substr($datamob['MOB'],(6),4),10, "X", STR_PAD_LEFT );
								$insert = $this->{'model_pos_' . $company}->dscl_resent_pin_trans($insert_data);
								
								$data['status']=$mcrypt->encrypt('1');
								$data['message']=$mcrypt->encrypt("Pin sent successfully to ".$this->request->post['MOB']); 
								$data['mob']=$mcrypt->encrypt($datamob['MOB']);
								$log->write('return data');
								$log->write($data);
								$this->response->addHeader('Content-Type: application/json');
								$this->response->setOutput(json_encode($data));
								
								return;
							}
							else
							{
								$log->write('in else');
								$log->write($dataAuthentication['GROWER_ID']);
								$data['status']=$mcrypt->encrypt('0');
								$data['message']=$mcrypt->encrypt("Pin not generetaed");
								$data['mob']=$mcrypt->encrypt("0");
								$this->response->addHeader('Content-Type: application/json');
								$this->response->setOutput(json_encode($data));	
								return;
							}
				
			//$dataAuthentication['AMOUNT']=0; 				
			if($dataAuthentication['AMOUNT']<0)
			{
				$dataAuthentication['AMOUNT']=0; 					
			}	
			$data['tagged']=$mcrypt->encrypt($dataAuthentication['AMOUNT']);
			//$dataAuthentication['VDATE'];		
			$log->write(strtotime($dataAuthentication['VDATE']));
			$log->write(time());
			$toBeComparedDate = $dataAuthentication['VDATE'];
			$today = (new DateTime())->format('Y-m-d'); 
			$expiry = (new DateTime($toBeComparedDate))->format('Y-m-d');
			$log->write(strtotime($today));
			$log->write(strtotime($expiry));
			if(strtotime($today) > strtotime($expiry))//strtotime($dataAuthentication['VDATE']) > time())
			{
			$log->write("card expiry");
			$data['status']=$mcrypt->encrypt('0');
			$data['message']=$mcrypt->encrypt("Card expired.");
			}
			else{
			$dataotptrans=0;//$this->model_card_integration->check_otp_trans($this->request->post);
			$log->write($dataotptrans);
		if(empty($dataotptrans) && (!empty($dataAuthentication['AMOUNT'])))
			{			 
			//get card authentication			
			//$this->model_card_integration->send_otp($this->request->post);	
			$data['status']=$mcrypt->encrypt('1');
			//$data['message']=$mcrypt->encrypt("Wrong PIN.");
			$data['mob']=$mcrypt->encrypt($datamob['MOB']);
			$data['gname']=$mcrypt->encrypt($datamobnew['RYOT_NAME']);
			$data['vname']=$mcrypt->encrypt($datamobnew['VNAME']);
			}
			else{
				
				$log->write("in else");
				//send same otp $dataotptrans['otp']
				$data['status']=$mcrypt->encrypt('0');
				$data['mob']=$mcrypt->encrypt($datamob['MOB']);
				$data['gname']=$mcrypt->encrypt($datamobnew['RYOT_NAME']);
				$data['vname']=$mcrypt->encrypt($datamobnew['VNAME']);
				$data['message']=$mcrypt->encrypt("Grower Amount is Zero");
				}
			}
		}
		//$data['status']=$mcrypt->encrypt('1');
		}
		else{
		$data['status']=$mcrypt->encrypt('0');
		$data['message']=$mcrypt->encrypt("Card unknown status.");
		}
		$data['mob']=$mcrypt->encrypt($datamob['MOB']);
		}
		else{
		$data['status']=$mcrypt->encrypt('0');
		$data['message']=$mcrypt->encrypt("Grower number not found.");
		}
		}
		$log->write(($data));
		if(!empty($data))
		{
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
		}
		
	}
	function growerdtl()
	{
		$log=new Log("Card-Grower-dtl-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->request->post["cdata"]=$mcrypt->decrypt($this->request->post["cdata"]);		
		$this->request->post["cdata"]=json_decode($this->request->post["cdata"]);		
		$log->write($this->request->post["cdata"]);
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["cdata"]->GC);		
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt($this->request->post["cdata"]->CSN);	

////////////
		$this->adminmodel('card/integration');				
		$this->adminmodel('unit/unit');
		
		
		if(empty($this->request->post["grower_id"]))
		{
			$log->write('grower id is empty');
			//$this->adminmodel('pos/dscl');
			//$cdatanew=$this->model_pos_dscl->GetGrowerId("GetGrowerId",$this->request->post,0);
			//$log->write($cdatanew);	
			$cdatanew=$this->model_unit_unit->getGrowerIdByCard($this->request->post["Card_Serial_Number"]);
			$log->write($cdatanew);
			if(empty($cdatanew))
			{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('No Record Found');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return;
			}
			$this->request->post["grower_id"]=$cdatanew['GROWER_ID'];
			$unitdata= $this->model_unit_unit->getUnitByID($cdatanew['UNIT_ID']); 
			
			$this->request->post["cdata"]->UN=$cdatanew['UNIT_ID'];
			
			$this->request->post['CARD_UNIT']= $cdatanew['UNIT_ID'];
			$this->request->post['unit_id']= $cdatanew['UNIT_ID']; 
		}
		
		
		////////////////////
				
		$log->write($this->request->post);		
        	$unitdata= $this->model_card_integration->getgrower($this->request->post["Card_Serial_Number"],$this->request->post["cdata"]->UN);
		$log->write($unitdata); 
		if(!empty($unitdata)){
		$retdata=new GROWER();		
		$retdata->id  = $mcrypt->encrypt($unitdata['GROWER_ID']);
		$retdata->uid  = $mcrypt->encrypt($this->request->post["cdata"]->UN);
		$retdata->name      = $mcrypt->encrypt($unitdata['GROWER_NAME']);
		$retdata->fname       = $mcrypt->encrypt($unitdata['FTH_HUS_NAME']); 
		$retdata->village    = $mcrypt->encrypt($unitdata['VILLAGE_NAME']) ;
		$retdata->status	= $mcrypt->encrypt('1');
		$retdata->message	= $mcrypt->encrypt('Grower Details');					
		$this->response->addHeader('Content-Type: application/json');
				$log->write(json_encode($retdata));
		$this->response->setOutput(json_encode($retdata));	
		}		
}

function dispatch()
{

		$log=new Log("Card-Dispatch-".date('Y-m-d').".log");
		$retdata=array();
		$status="0";
        		$message="Error";
		try{
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->request->post["cdata"]=$mcrypt->decrypt($this->request->post["cdata"]);		
		$this->request->post["cdata"]=json_decode($this->request->post["cdata"]);		
		$log->write($this->request->post["cdata"]);
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["cdata"]->GC);		
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt($this->request->post["cdata"]->CSN);		
		$this->adminmodel('card/integration');	
		
		$log->write("get unit");	
			$this->adminmodel('unit/unit');
                 		$unitdata= $this->model_unit_unit->getUnitByID($this->request->post["cdata"]->UN);
				 $datares="";
				 $company="";
				 $log->write($unitdata);
				 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->adminmodel('pos/'.$company);
							$log->write("function unit");					
		//get status from cane server
		 $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
				  $this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];
				  $this->request->post['CARD_UNIT']= $this->request->post["cdata"]->UN;
				$this->request->post['unit_id']=$this->request->post["cdata"]->UN;
				  $this->request->post['USER']=$mcrypt->decrypt($this->request->post["username"]);
		$canestatus= $this->{'model_pos_' . $company}->GetCardStatus('GetCardStatus',$this->request->post,0); 
		$log->write("after function");	
		
			$log->write($canestatus);	
			//get mobile number om card number or grower id
		$datamob=$this->model_card_integration->getmobileno($this->request->post);		
		$log->write("Grower details check");
		$datamobnew=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);
		$log->write($datamob);
		$log->write($datamobnew);
		$datamob['MOB']=	$datamobnew['MOB'];
		if(!empty($datamob)){		
		//update cane server
		if($datamob['CARD_STATUS']=='6'&& $canestatus=='6')
		{  
		$this->request->post['CARD_STATUS']="7";
				   $this->request->post['CARD_STATUS_DESC']="CARD DISPATCHED";
				  $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
				  $this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];
				  //$this->request->post['CARD_UNIT']= "0";
				  $this->request->post['CARD_QR_SRTING']="0";							
				$datares= $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
			$log->write($datares);
		if($datares=="1"){
		$isdispatched=$this->model_card_integration->card_dispatch($this->request->post);		
		if($isdispatched=="1")
		{
			$this->load->library('sms'); 
          		$sms=new sms($this->registry);	  
		  //$sms->sendsms($datamob['MOB'], 17, $data);////////send otp 
		$status=$isdispatched;
                $message="Dispatched Success";
		}
		else
		{
			$status="0";
		$message="Dispatched Error";
		}
		$log->write($this->request->post);
		}
		else{
				$status="0";
		$message="Error in getting data from cane server.";
		}
		}
		else
		{
				$log->write('in else');
				$log->write('in cane system status - '.$canestatus); 
				$log->write('in our system status - '.$datamob['CARD_STATUS']);
				$status="0";
				if($canestatus=="7")
				{
				$message="Card already dispatched.";
				}
				else if($canestatus=="9")
				{
				$message="Card already Activated.";
				}
				else{
		$message="Card status not matched with cane system.";
		}
		}
		
		}
		else
		{
		$status="0";
                                $message="Mobile number not found.";
		}
		}
		else
		{
		$status="0";
                                $message="Unknow card scaned";
		}
		}catch(Exception $e)
		{
			$status="0";
                                $message=$e->getMessage();
		}
		$log->write($status);
		$log->write($message);
		$retdata["status"]=$mcrypt->encrypt($status);
		$retdata["message"]=$mcrypt->encrypt($message);
		$log->write($retdata);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($retdata));
}

function senddelivery()
{
		$log=new Log("Card-Send-otp-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$retval=array();
		$status="0";
		$message="Error";
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->request->post["cdata"]=$mcrypt->decrypt($this->request->post["cdata"]);
		$log->write($this->request->post);
		$this->request->post["cdata"]=json_decode(($this->request->post["cdata"]));
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["cdata"]->GC);
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt($this->request->post["cdata"]->CSN);
		
		
		$this->adminmodel('card/integration');				
		$this->adminmodel('unit/unit');
		
		
		if(empty($this->request->post["grower_id"]))
		{
			$log->write('grower id is empty');
			$cdatanew=$this->model_unit_unit->getGrowerIdByCard($this->request->post["Card_Serial_Number"]);
			$log->write($cdatanew);
			if(empty($cdatanew))
			{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('No Record Found');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return;
			}
			$this->request->post["grower_id"]=$cdatanew['GROWER_ID'];
			$unitdata= $this->model_unit_unit->getUnitByID($cdatanew['UNIT_ID']);

			$this->request->post['CARD_UNIT']= $cdatanew['UNIT_ID'];
			$this->request->post['unit_id']= $cdatanew['UNIT_ID']; 
		}
		else
		{
			$log->write('grower id not empty');
			$unitdata= $this->model_unit_unit->getUnitByID($this->request->post["cdata"]->UN);
			$this->request->post['CARD_UNIT']= $this->request->post["cdata"]->UN;
			$this->request->post['unit_id']= $this->request->post["cdata"]->UN;
		}
		
		
        		
				 $log->write($unitdata);
				 $datares="";
				 $company="";
				 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->adminmodel('pos/'.$company);
					}		
		//get status from cane server
		 $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
				  $this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];
				 
				  $this->request->post['USER']=$mcrypt->decrypt($this->request->post["username"]);
		$canestatus= $this->{'model_pos_' . $company}->GetCardStatus('GetCardStatus',$this->request->post,0);  
		$log->write($canestatus);
				//


			if(empty($canestatus))
			{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('No Response from cane server');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval)); 
				return;
			}

			//

		
		$historystatusdata=$this->model_card_integration->checkCardStatusHistory( $this->request->post["grower_id"],$this->request->post["Card_Serial_Number"]);
		$log->write($historystatusdata);
		if(1)//sizeof($historystatusdata)>=1)
		{
		if( ($canestatus=='7' || $canestatus=='3')   )//&& $historystatusdata[0]['CARD_STATUS_ID']==3 && $historystatusdata[1]['CARD_STATUS_ID']==7        //if(0)	//($canestatus=='7') || ($canestatus=='3'))
		{

		//get mobile number om card number or grower id
		//$datamob=$this->model_card_integration->getmobileno($this->request->post);
		//$log->write($datamob);
		$log->write("Grower details check");
		$datamob=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);
		$log->write($datamob);
		if(!empty($datamob))
		{
		$this->request->post["MOB"]=$datamob['MOB'];
		$this->request->post["TX"]="2";
		$dataotptrans=$this->model_card_integration->check_otp_trans($this->request->post);
		$log->write($dataotptrans);
		if(empty($dataotptrans))
		{
			$this->request->post["OD"]="5";
			//send_otp
			$this->request->post["TX"]="2";
			$retdata=$this->model_card_integration->send_otp($this->request->post);				
				$status=$retdata;
				$message="Grower details";
			
		}
		else
		{
			$retdata="1";
			$status=$retdata;
			$message="OTP already sent.";
		}		

		//$otp = rand(10000, 99999);
		$log->write($retdata);		
		
		
		
		}
		else{
		
				$status="0";
				$message="Mobile number not found";
		}
		}
		else if($canestatus=='9')
		{
				$log->write("canestatus is 9");
			$status="9";
			$message="Card already activated."; 
			//resend pin if card_pin is empty
			$this->adminmodel('pos/dscl');
			$this->load->library('sms');
			$datamobnew=$this->{'model_pos_dscl'}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);
			$log->write($datamobnew);
			$datamob=$this->model_pos_dscl->GetAuthentication('GetAuthentication',$this->request->post,0);
			//$datamob=$this->model_pos_dscl->GetCheckCardMpin('GetCheckCardMpin',$this->request->post,0);
			$log->write("PIN resend");
			$log->write($datamob);			
			$datamob['pin']=$datamob['GROWER_ID'];
			if((!empty($datamob['pin'])) && (!empty($datamobnew['MOB'])))
			{
				$log->write("pin and mob is not empty so send the sms");
				$sms=new sms($this->registry);	  
				//$sms->sendsms($datamobnew['MOB'], 14, $datamob); 
			}
				//break;
			/*
			foreach($datamob as $data)
			{
				$log->write($data);
			
				if(($data['CARD_SERIAL_NUMBER']==$this->request->post['CARD_SERIAL_NUMBER']) && ($data['GROWER_ID']==$this->request->post['CARD_GROWER_ID']) )
				{	
          		$sms=new sms($this->registry);	  
				$sms->sendsms($datamobnew['MOB'], 14, $data); 
				break;
				}
			
			}
			*/
		     
		}
		else{
		
			$status="8";
			$message="Card not activated.";
		}		
		}
		else
		{
			$status="8";
			$message="Card status mismatched.";
		}
		
		$retval['status']=$mcrypt->encrypt($status);
		$retval['message']=$mcrypt->encrypt($message);
		$log->write($retval);
		$this->response->addHeader('Content-Type: application/json');		
		$this->response->setOutput(json_encode($retval));
}

function delivery()
{
		$log=new Log("Card-delivery-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$retval=array();
		//$log->write($this->request->server);
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->request->post["cdata"]=json_decode($mcrypt->decrypt($this->request->post["cdata"]));
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["cdata"]->GC);
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt($this->request->post["cdata"]->CSN);
		
		//
		$this->adminmodel('card/integration');				
		$this->adminmodel('unit/unit');
		
		
		if(empty($this->request->post["grower_id"]))
		{
			$log->write('grower id is empty');
			$cdatanew=$this->model_unit_unit->getGrowerIdByCard($this->request->post["Card_Serial_Number"]);
			$log->write($cdatanew);
			if(empty($cdatanew))
			{
				$retval['status']=$mcrypt->encrypt('0');
				$retval['message']=$mcrypt->encrypt('No Record Found');
				$log->write($retval);
				$this->response->addHeader('Content-Type: application/json');		
				$this->response->setOutput(json_encode($retval));
				return;
			}
			$this->request->post["grower_id"]=$cdatanew['GROWER_ID'];
			$unitdata= $this->model_unit_unit->getUnitByID($cdatanew['UNIT_ID']);

			$this->request->post['CARD_UNIT']= $cdatanew['UNIT_ID'];
			$this->request->post['unit_id']= $cdatanew['UNIT_ID']; 
			$this->request->post["cdata"]->UN= $cdatanew['UNIT_ID']; 
		}
		else
		{
			$log->write('grower id not empty');
			$unitdata= $this->model_unit_unit->getUnitByID($this->request->post["cdata"]->UN);
			$this->request->post['CARD_UNIT']= $this->request->post["cdata"]->UN;
			$this->request->post['unit_id']= $this->request->post["cdata"]->UN;
		}
		
		//
		
		$this->request->post["otp"]=$mcrypt->decrypt($this->request->post["cin"]);		
		$this->request->post['TX']= "2";
		$this->request->post['USER']=$mcrypt->decrypt($this->request->post["username"]);
		//$this->adminmodel('card/integration');
		//$this->load->library('soapcurl');
		//$soapcurl = new soapcurl($this->registry);
		
                 		//$unitdata= $this->model_unit_unit->getUnitByID($this->request->post["cdata"]->UN);
				 $datares="";
				 $company="";
				 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->adminmodel('pos/'.$company);
					}	
		$otpdata=	$this->model_card_integration->check_otp($this->request->post);
		$log->write($otpdata);
		if($otpdata['otp']==$this->request->post['otp']){
			$log->write("in if data");
		//get status from cane server
		 $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
				  $this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];				 
				  $log->write("in if data 1");
		$canestatus= $this->{'model_pos_' . $company}->GetCardStatus('GetCardStatus',$this->request->post,0); 
			$log->write($canestatus);	
		//if($canestatus!='9'){
		$historystatusdata=$this->model_card_integration->checkCardStatusHistory( $this->request->post["grower_id"],$this->request->post["Card_Serial_Number"]);
		if(sizeof($historystatusdata)>=1)
		{
		if( ($canestatus=='7' || $canestatus=='3') )// && $historystatusdata[0]['CARD_STATUS_ID']==3 && $historystatusdata[1]['CARD_STATUS_ID']==7 )        //if(0)	//($canestatus=='7') || ($canestatus=='3'))
		{
		//update to cane server
				  $this->request->post['CARD_STATUS']="9";
				  $this->request->post['CARD_STATUS_DESC']="CARD ACTIVATED";
				  $this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
				  $this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];
				  //$this->request->post['CARD_UNIT']= "0";
				  $this->request->post['CARD_QR_SRTING']="0";
				  $this->request->post['USER_DEL_ID']=$mcrypt->decrypt($this->request->post["username"]);
					$this->request->post['USER_DEL_NAME']=$mcrypt->decrypt($this->request->post["del_user"]);
					$this->request->post['USER_DEL_MOB']=$mcrypt->decrypt($this->request->post["del_mob"]);
					$this->request->post['USER_DEL_IMEI']=$mcrypt->decrypt($this->request->post["del_imei_no"]);
					if(empty($this->request->post['USER_DEL_IMEI']))
					{
					$this->request->post['USER_DEL_IMEI']="0";
					}
					if(empty($this->request->post['USER_DEL_MOB']))
					{
					$this->request->post['USER_DEL_MOB']="0";
					}
				  //$this->request->post['CARD_UNIT']= "0";
				  $this->request->post['CARD_QR_SRTING']="0";
				$rand_n=abs( crc32( uniqid() ) );
				$this->request->post['CARD_PIN']=substr($rand_n, 0,6);	
				$data['CARD_PIN']=$this->request->post['CARD_PIN'];	
				  $this->request->post['USER']=$mcrypt->decrypt($this->request->post["username"]);
				  
						
						$datares= $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
			$log->write($datares);
		if($datares=="1"){
		$isdelivered=$this->model_card_integration->card_delivery($this->request->post);
		$data=array('Card_Serial_Number'=>$this->request->post['CARD_SERIAL_NUMBER'],'GROWER_ID'=>$this->request->post['CARD_GROWER_ID'],'UNIT_ID'=>$this->request->post['CARD_UNIT']);
		//$datamob=$this->model_card_integration->getmobileno($this->request->post);
		$this->request->post['grower_id']=$this->request->post['CARD_GROWER_ID'];
		$this->request->post['unit_id']=$this->request->post['CARD_UNIT'];
		$log->write("Grower details check");
		$datamob=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);
		$log->write($datamob);
		
		$data['MOB']=$datamob['MOB'];
		$log->write("at mobile");

		//$unit =$unit_grower[1];//$getdatar['UNIT_ID'];
								
								
								
								$card_serail_number=$Card_Serial_Number;//$unitdata['company_id'].$unitdata['company_id'].$unit.substr($rand_n, 0,8) ;
								//$card_sid=$this->request->get['sid'];
								$StatusId='2';
								$StatusName='CARD VERIFIED';
								$this->request->post['CARD_STATUS']="p";
								$this->request->post['CARD_STATUS_DESC']= $StatusName;
								$this->request->post['CARD_SERIAL_NUMBER']= $this->request->post['CARD_SERIAL_NUMBER'];
								$this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];
								$this->request->post['CARD_UNIT']= $this->request->post["cdata"]->UN;
								$this->request->post['CARD_QR_SRTING']="0";
								$this->request->post['USER']=0;//$this->user->getId();
								

				                               
								//$this->load->library('soapcurl');
								//$soapcurl = new soapcurl($this->registry);
								if(!empty($unitdata['company_name']))
								{
									$company=strtolower($unitdata['company_name']);
									$this->adminmodel('pos/'.$company);
									$datares ="1";// $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
									$log->write($datares);
								}
								//print_r($datares);
								if($datares==1)
								{	
		
		//server update
				$log->write("at update end");

		$res="1";//$this->model_card_card->generate_pin($data); 
 		$data['pin']=$this->request->post['CARD_PIN'];
		$log->write($data);
           		$log->write('now call sms library');
     		  $this->load->library('sms'); 
      		 $sms=new sms($this->registry); 
      		 //$sms->sendsms($data['MOB'], 14, $data);
	    	$log->write('sms sent');
		$log->write("after update end");

		$log->write($this->request->post);
		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput($isdelivered);}	
		
		$message="Success";
		$retval['status']=$mcrypt->encrypt($isdelivered);
		$retval['message']=$mcrypt->encrypt($message);
		}
								else
								{
									$message="Unable to connect cane system.";
		$retval['status']=$mcrypt->encrypt("0");
		$retval['message']=$mcrypt->encrypt($message);
								}
		
		}
		else
		{
		
		$message="Unable to connect cane system.";
		$retval['status']=$mcrypt->encrypt("0");
		$retval['message']=$mcrypt->encrypt($message);
		
		
		}
		}else
		{
		
		$message="Card status is not matched.";
		$retval['status']=$mcrypt->encrypt("0");
		$retval['message']=$mcrypt->encrypt($message);
		
		
		}
}else
		{
		
		$message="Card status is not matched.";
		$retval['status']=$mcrypt->encrypt("0");
		$retval['message']=$mcrypt->encrypt($message);
		
		
		}
}
else
		{
		
		$message="OTP is not correct.";
		$retval['status']=$mcrypt->encrypt("0");
		$retval['message']=$mcrypt->encrypt($message);
		
		
		}
$this->response->addHeader('Content-Type: application/json');		
		$this->response->setOutput(json_encode($retval));
}
//end card
		
function getRequisition()
	{

		$log=new Log("Requisition-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);

		$this->adminmodel('pos/pos');
		$log->write("model");
		$data=array();
		$data['storeid']=$mcrypt->decrypt($this->request->post["storeid"]);		
		$data['indentno']=$mcrypt->decrypt($this->request->post["indentno"]);
		
		$this->adminmodel('lead/orderleads');
		$get_bill=$this->model_lead_orderleads->getrequisition_to_bil_indent($data['indentno']);
		$log->write($data['indentno']);
		$log->write($get_bill);
		if(!empty($get_bill))
		{
			$log->write('Order already placed with inv no-'.$get_bill.' for bill id-'.$data['indentno']);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput("{'error':'".$this->encryptRJ256('Order already placed with inv no-'.$get_bill.' for bill id-'.$data['indentno'])."'}");
			return;
		}
		
		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
		if(!empty($companydata)){
		$data['unitid']=$companydata[0]['unit_id'];
		$log->write($companydata);
		$log->write($data);
		$company=strtolower($companydata[0]['company_name']);
		$log->write($company);
		$this->adminmodel('pos/'.$company);
		$results = $this->{'model_pos_' . $company}->getDataFromServer($data);
		$log->write($results);
				if(!empty($results)){
					
/*				
if (strpos($results, 'xml') !== false) {
					$this->response->addHeader('Content-Type: application/xhtml+xml');}
else{*/

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($results);
		//error to display as per status
			
		}
		}else{		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput("{'error':'".$this->encryptRJ256('Company not defined')."'}");}
		
				
		}

function getRequisitiondtl()
	{

		$log=new Log("Requisitiondtl-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->adminmodel('pos/pos');
		$data=array();
		$data['storeid']=$mcrypt->decrypt($this->request->post["storeid"]);		
		$data['indentno']=$mcrypt->decrypt($this->request->post["indentno"]);
		$data['userid']=$mcrypt->decrypt($this->request->post["username"]);		
		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
		$log->write($companydata);
		$log->write($data);
		$company=strtolower($companydata[0]['company_name']);
		$data['unitid']=$companydata[0]['unit_id'];
		$log->write($company);
		$this->adminmodel('pos/'.$company);
		$log->write("model");		
		$results = $this->{'model_pos_' . $company}->getDataDetailFromServer($data);//$this->model_pos_bcml->getDataFromServer($data);
		$log->write($results);
				if(!empty($results)){
		
$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($results);}
				
		}

	function Updaterequisition()
	{

		$log=new Log("UpdateRequisition-w".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write('UpdateRequisition called');
		$log->write($this->request->post);
		$log->write($this->request->get);
		$log->write($mcrypt->decrypt($this->request->post["payment_method"]));
		$log->write($mcrypt->decrypt($this->request->post["cash"]));
		$log->write($mcrypt->decrypt($this->request->post["invoicevalue"]));
		$log->write($mcrypt->decrypt($this->request->post["ordervalue"]));
		$log->write($mcrypt->decrypt($this->request->post["glimit"]));
		$this->adminmodel('pos/pos');
		$data=array();
		$returnbypos=$this->model_pos_pos->get_order_total($mcrypt->decrypt($this->request->post["invoiceno"]),'total');
		$log->write('return by pos:'.$returnbypos);
		$returnbypos=number_format((float)$returnbypos,2,'.','');//bcdiv ($returnbypos, 1 ,2 ); 
		$this->request->post["invoicevalue"]=$mcrypt->encrypt($returnbypos); //number_format((float), 2,'.','') 
		$log->write($this->request->post["invoicevalue"]);
		$data['storeid']=$mcrypt->decrypt($this->request->post["storeid"]);		
		$data['indentno']=$mcrypt->decrypt($this->request->post["oid"]);
		$data['userid']=$mcrypt->decrypt($this->request->post["username"]);
		
		$data['otp']=$mcrypt->decrypt($this->request->post["otp"]);
		if(strlen($data['otp'])>4)
		{
			$data['otp']="0";
		}
		$data['billno']=$mcrypt->decrypt($this->request->post["invoiceno"]);
		$data['prddtl']=$mcrypt->decrypt($this->request->post["prddtl"]);
		//product base price to set
		$data['prddtl']= json_decode($data['prddtl'],TRUE);
                $obj = new ArrayObject($data['prddtl']);
		$it = $obj->getIterator();	
		$data_final=array();
		$order_total_value=0;
		$order_subsidy_amount=0;
		foreach ($it as $key=>$val)
		{	
			$log->write('in loop');
			$log->write('product_name:'.$val['product_name']);
			$log->write('product_price:'.$val['product_price']);
			$log->write('product_tax:'.$val['product_tax']);
			$log->write('product_id:'.$val['product_id']);
			if(empty($val['subsidyper']))
			{
				$val['subsidyper']=0;
			}
			
			unset($val['product_combo_prd']);
			$store_price=$this->model_pos_pos->getproductprice($data['storeid'],$val['product_id']);
			$log->write('store_price:'.$store_price);
			if((!empty($store_price)) && ($store_price!='0.0000')&& ($store_price!='0.000')&& ($store_price!='0.00')&& ($store_price!='0.0'))
			{
				$val['product_price']=$store_price;
			}
			$log->write('product_price:'.$val['product_price']);
			$val['product_price']=number_format((float)$val['product_price'],2,'.','');
			$val['product_tax']=number_format((float)$val['product_tax'],2,'.','');
			
			$order_total_value=$order_total_value+(($val['product_price']+$val['product_tax'])*$val['product_quantity']);
			
			//subsidy
			$order_subsidy_amount=$order_subsidy_amount+($val['subsidyamount']);
			//change
			$data_final[]=($val);	
			if(empty($val['product_price'])) 
				{
					return 0;
				}
		} 
			$log->write('order_total_value by calculation '.$order_total_value);
		$data['prddtl']= json_encode($data_final);
		
		$this->request->post["invoicevalue"]=$mcrypt->encrypt($order_total_value);		
		$data['subsidy']=$order_subsidy_amount;
		$data['cash']=$mcrypt->decrypt($this->request->post["cash_total"]);//$order_total_value-$order_subsidy_amount-$mcrypt->decrypt($this->request->post["glimit"]);
		$data['ordervalue']=$mcrypt->decrypt($this->request->post["invoicevalue"])-$mcrypt->decrypt($this->request->post["cash"]);
		/*if($mcrypt->decrypt($this->request->post["payment_method"])=='Tagged Subsidy')
		{
			 $data['cash']=0;
			 $data['subsidy']=$mcrypt->decrypt($this->request->post["cash"]);
		}
		else if($mcrypt->decrypt($this->request->post["payment_method"])=='Tagged Cash Subsidy')
		{
			 $data['cash']=$mcrypt->decrypt($this->request->post["cash"]);
			 $data['subsidy']=$mcrypt->decrypt($this->request->post["invoicevalue"])-$mcrypt->decrypt($this->request->post["cash"]);
		}
		
		else
		{
			$data['cash']=$mcrypt->decrypt($this->request->post["cash"]);
			$data['subsidy']=0;
		} */
		
		$data['invoicevalue']=$mcrypt->decrypt($this->request->post["invoicevalue"]);
		 
		//$this->model_pos_pos->UpdateOrderTagged($data['billno'],$data['ordervalue']); 
		
		//end price
		$data['FmCode']=$mcrypt->decrypt($this->request->post["fmcode"]);
		$data['DeliveryMode']=$mcrypt->decrypt($this->request->post["deliverymode"]);
		$data['DeliveryReceipt']=substr($mcrypt->decrypt($this->request->post["deliveryreceipt"]), 0, 1);
		$data['ApprovalType']=substr($mcrypt->decrypt($this->request->post["approvaltype"]), 0, 1); 
		$data['glimit']=$mcrypt->decrypt($this->request->post["glimit"]);
		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
		$log->write($companydata);
		$log->write($data);
		$company=strtolower($companydata[0]['company_name']); 
		$data['unitid']=$companydata[0]['unit_id'];
		$log->write($company);
		$this->adminmodel('pos/'.$company);
		$log->write("model");	
		
		if(isset($this->request->post["lumpsum"])){
		$results = $this->{'model_pos_' . $company}->setOrderDataToServer_lumpsum($data);
		}else{
		$results = $this->{'model_pos_' . $company}->setOrderDataToServer($data);
		}
	
		
		$log->write('results by bcml is :'.$results);
		if($results!=1)
		{
			$log->write("in if");
			$results="0";		
		}
		try{	
			//$retbcml=$this->{'model_pos_' .$company}->GetIndentByInvoiceNo('GetIndentByInvoiceNo',array('unitid'=>$data['unitid'],'invoiceno'=>$data['billno'],'store_id'=>$data['storeid']),true); 
			//$log->write($retbcml);
			/*if(($retbcml[0]['InvoiceNo']==$data['billno']) && $results=="1" )
			{
				$results="1";	
			}else{
				$results="0";	
				}*/
			
		} catch (Exception $e) {
                                $log->write($e);
                            }
		
				//if(!empty($results)){					
		//$this->response->addHeader('Content-Type: application/json');
		$log->write($results);
		$results=trim($results);
		$log->write($results);
		if($results==1)
		{
			$log->write('in if result is 1');
			$log->write('UpdateRequisition end here');
			$this->response->setOutput("1");

		}
		else
		{
			$log->write('in if result is 0');
			$log->write('UpdateRequisition end here');
			$this->response->setOutput("0");
		}
		
		//}		
	}


function Updaterequisitionduplicate()
	{

		$log=new Log("UpdateRequisition-dup-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->adminmodel('pos/pos');
		$data=array();
		$data['storeid']="61";//$mcrypt->decrypt($this->request->post["storeid"]);		
		$data['indentno']="ncBJ7hEP2trvd2rZ+LTW6A==";//"2100632";//$mcrypt->decrypt($this->request->post["oid"]);
		$data['userid']="225";//$mcrypt->decrypt($this->request->post["username"]);
		$data['ordervalue']="930.0";//$mcrypt->decrypt($this->request->post["invoicevalue"])-$mcrypt->decrypt($this->request->post["cash"]);
		$data['cash']="0";//$mcrypt->decrypt($this->request->post["cash"]);
		$data['invoicevalue']="930.0";//$mcrypt->decrypt($this->request->post["invoicevalue"]);
		$data['otp']="0";//$mcrypt->decrypt($this->request->post["otp"]);
		if(strlen($data['otp'])>4)
		{
			$data['otp']="0";
		}
		$data['billno']="93300";//$mcrypt->decrypt($this->request->post["invoiceno"]);
		$data['prddtl']='[{"product_tax":"22.142855","product_quantity":"2","product_id":"357","total":"930.00000","product_name":"SULPHO ZINC 5 Kg","SN":"1","product_price":"442.8571","product_hstn":"0000"}]';
		//$mcrypt->decrypt($this->request->post["prddtl"]);
		//product base price to set
		$data['prddtl']= json_decode($data['prddtl'],TRUE);
                $obj = new ArrayObject($data['prddtl']);
		$it = $obj->getIterator();	
		$data_final=array();		
		foreach ($it as $key=>$val)
		{							
			$val['product_price']=$this->model_pos_pos->getproductprice($data['storeid'],$val['product_id']);			
			//change
			$data_final[]=($val);	
		}                
		$data['prddtl']= json_encode($data_final);
		//end price
		$data['FmCode']="297";//$mcrypt->decrypt($this->request->post["fmcode"]);
		$data['DeliveryMode']="2";//$mcrypt->decrypt($this->request->post["deliverymode"]);
		$data['DeliveryReceipt']="y";//substr($mcrypt->decrypt($this->request->post["deliveryreceipt"]), 0, 1);
		$data['ApprovalType']="0";substr($mcrypt->decrypt($this->request->post["approvaltype"]), 0, 1);
		$data['glimit']="27512.25";//$mcrypt->decrypt($this->request->post["glimit"]);
		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 
		$log->write($companydata);
		$log->write($data);
		$company=strtolower($companydata[0]['company_name']);
		$data['unitid']=$companydata[0]['unit_id'];
		$log->write($company);
		$this->adminmodel('pos/'.$company);
		$log->write("model");	
		
		if(isset($this->request->post["lumpsum"])){
		//$results = $this->{'model_pos_' . $company}->setOrderDataToServer_lumpsum($data);
		}else{
		 //$results = $this->{'model_pos_' . $company}->setOrderDataToServer($data);
		}
	
		
		$log->write($results);
		if($results!=1)
		{
			$log->write("in if");
			$results="0";		
		}
		try{	
			//$retbcml=$this->{'model_pos_' .$company}->GetIndentByInvoiceNo('GetIndentByInvoiceNo',array('unitid'=>$data['unitid'],'invoiceno'=>$data['billno'],'store_id'=>$data['storeid']),true); 
			//$log->write($retbcml);
			/*if(($retbcml[0]['InvoiceNo']==$data['billno']) && $results=="1" )
			{
				$results="1";	
			}else{
				$results="0";	
				}*/
			
		} catch (Exception $e) {
                                $log->write($e);
                            }
		
				//if(!empty($results)){					
		//$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput((string)$results);
		//}		
	}

	function UpdateOTPByInvoiceNo()
	{
		$log=new Log("UpdateOTPByInvoiceNo-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->adminmodel('pos/pos');
		$data=array();
		$data['storeid']=$mcrypt->decrypt($this->request->post["storeid"]);				
		$data['userid']=$mcrypt->decrypt($this->request->post["username"]);	
		$data['otp']=$mcrypt->decrypt($this->request->post["otp"]);
		$data['billno']=$mcrypt->decrypt($this->request->post["invoiceno"]);				
		$data['DeliveryReceipt']=substr($mcrypt->decrypt($this->request->post["deliveryreceipt"]), 0, 1);			
		$companydata=$this->model_pos_pos->getunitidandcompanyid($data); 			
		$log->write($companydata);
		$log->write($data);
		$company=strtolower($companydata[0]['company_name']);
		$data['unitid']=$companydata[0]['unit_id'];
		$log->write($company);
		$this->adminmodel('pos/'.$company);
		$log->write("model");		
		$results = $this->{'model_pos_' . $company}->UpdateOTPByInvoiceNo($data);		
		$log->write($results);		
				if(!empty($results)){					
		//$this->response->addHeader('Content-Type: application/json');
		if($results=="1"){
		$this->adminmodel('pos/pos');
		$results= $this->model_pos_pos->update_indent_deleviery($data['otp'],$data['billno'],"1",$data['DeliveryReceipt']);
		if(!empty($results)){
		$this->response->setOutput((string)$results);
		}
		}
		if($results=="2")
		{
			$this->response->setOutput("OTP not matched");
		}	
		if($results=="3")
		{
			$this->response->setOutput("Invoice does not exits");
		}					
		}
				
	}

	
	
function encryptRJ256($encrypted)
{
			
     $iv = '!QAZ2WSX#EDC4RFV'; #Same as in C#.NET
     $key = '5TGB&YHN7UJM(IK<'; #Same as in C#.NET	
    //PHP strips "+" and replaces with " ", but we need "+" so add it back in...
    //$encrypted = str_replace(' ', '+', $encrypted);
    //get all the bits
$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
$pad = $blockSize - (strlen($encrypted) % $blockSize);
    $rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted.str_repeat(chr($pad), $pad), MCRYPT_MODE_CBC, $iv);
$rtn = base64_encode($rtn);
    return($rtn);
}
function decryptRJ256($encrypted)
{
			
     $iv = '!QAZ2WSX#EDC4RFV'; #Same as in C#.NET
     $key = '5TGB&YHN7UJM(IK<'; #Same as in C#.NET	
    //PHP strips "+" and replaces with " ", but we need "+" so add it back in...
    $encrypted = str_replace(' ', '+', $encrypted);
    //get all the bits
    $encrypted = base64_decode($encrypted);
    $rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted, MCRYPT_MODE_CBC, $iv);
    $rtn = $this->unpad($rtn);
    return($rtn); 
}
function utf8ize($d)
{ 
    if (is_array($d) || is_object($d))
        foreach ($d as &$v) $v = utf8ize($v);
    else
        return utf8_encode($d);

    return $d;
}
function pkcs7pad($plaintext, $blocksize)
{
$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
    $padsize = $blocksize - (strlen($plaintext) % $blocksize);
    return $plaintext . str_repeat(chr($padsize), $padsize);
}

//removes PKCS7 padding
function unpad($value)
{
    $blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $packing = ord($value[strlen($value) - 1]);
    if($packing && $packing < $blockSize)
    {
        for($P = strlen($value) - 1; $P >= strlen($value) - $packing; $P--)
        {
            if(ord($value{$P}) != $packing)
            {
                $packing = 0;
            }
        }
    }

    return substr($value, 0, strlen($value) - $packing); 
}
/*function test()
{
$this->adminmodel('pos/pos');
$products = $this->model_pos_pos->getProduct(80,8); 

print_r($products); 
echo '<br/>';
print_r('tax_class_id-'.$products['tax_class_id']);
echo '<br/>';
echo 'tax-'.$this->tax->getTax($products['price'], $products['tax_class_id']);


}*/
function testmdecrypt()
 {
	 $mcrypt=new MCrypt();
	 echo $this->request->get['value'];
	 echo '<br><br>';
	  echo $mcrypt->decrypt($this->request->get['value']); 
	exit;
	
 } 
function testdecrypt()
 {
	  echo $this-> decryptRJ256($this->request->get['value']); 
	exit;
	
 } 
 function testencrypt()
 {
	  echo $this-> encryptRJ256($this->request->get['value']);  
	exit;
 } 
	function tagged_error_alert($data) 
	{
		$log=new Log("tagged_error_alert-".date('y-m-d').".log");
		$log->write("In tagged_error_alert");   
		$log->write($data);  
		$mail  = new PHPMailer();
		$body = "<p style='border: 1px solid silver;padding: 15px;'>
			Dear All,
			<br/><br/>
			We are getting no data from dscl cane server .
			
			<br/><br/>
			This is computer generated alert.Please do not reply to this email.
			<br/><br/>
			
			Thanking you,
			
			<br/>
			Unnati
			
			<br/>
			<br/>
			<span  style='font-size:7.5pt;color: green;'> Please consider the environment before printing this email. Ask yourself whether you really need a hard copy.</span>
		</p>";
                
        $mail->IsSMTP();
        $mail->Host       = "mail.akshamaala.in";
                                                           
        $mail->SMTPAuth   = false;                 
        $mail->SMTPSecure = "";                 
        $mail->Host       = "mail.akshamaala.in";      
        $mail->Port       = 25;                  
        $mail->Username   = "mis@akshamaala.in";  
        $mail->Password   = "mismis";            

        $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

        $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

        $mail->Subject    ='DSCL Cane Server Error Alert';

        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);
                
		$mail->AddAddress('vipin.kumar@aspl.ind.in', "vipin kumar");
		

		if(!empty(CC_EMAIL))
				{
					$ccemail = CC_EMAIL;
					$ccemail= explode(',', $ccemail);
					foreach ($ccemail as $value) {
						if(!empty($value)){				    
						$mail->AddCC($value,$value);}
					}
				}
					
				if(!empty(BCC_EMAIL))
				{
					$bccemail = BCC_EMAIL;
					$bccemail= explode(',', $bccemail);
					foreach ($bccemail  as $value) {
						if(!empty($value)){				    
						$mail->AddBCC($value,$value);}
					}
				}
		
		
        if(!$mail->Send())
		{
            
        }
        else
        { 
                          
        }
		
	}
}
?>