<?php
class Controllermposcard extends Controller{

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
public function get_grower_details()
                {
					$log=new Log("Card-pin-".date('Y-m-d').".log");
					$mcrypt=new MCrypt();
                          
					$log->write($this->request->post); 
					
					$grower_id=$mcrypt->decrypt($this->request->post['GROWER_ID']);
					$unit_id=$mcrypt->decrypt($this->request->post['UNIT_ID']);
                  
					$this->adminmodel('pos/dscl');
					$data=array('grower_id'=>$grower_id,'unit_id'=>$unit_id);
					
					$res=$this->model_pos_dscl->GetGrowerCard('CardDataView', $data, true);
					//$res=$this->model_card_card->check_grower_details($data); 
					$log->write($res);
					
                    //print_r($this->request);exit;
                   
                    if($res)
                    {
                        if($res['GROWER_ID']==$grower_id)
                        {
                        //print_r($res);
						
                        if($res['CARD_STATUS']==9)
                        {
                        

						if($res['CARD_KYC_DOCUMENT']=="1")
						{
							$CARD_KYC_DOCUMENT="Voter card";
						}
						else if($res['CARD_KYC_DOCUMENT']=="2")
						{
							$CARD_KYC_DOCUMENT="Aadhar card";
						}
						else if($res['CARD_KYC_DOCUMENT']=="3")
						{
							$CARD_KYC_DOCUMENT="Pan card";
						}
						else if($res['CARD_KYC_DOCUMENT']=="4")
						{
							$CARD_KYC_DOCUMENT="Driving Licence";
						}
						else if($res['CARD_KYC_DOCUMENT']=="5")
						{
							$CARD_KYC_DOCUMENT="Ration card";
						}
						else if($res['CARD_KYC_DOCUMENT']=="6")
						{
							$CARD_KYC_DOCUMENT="Arm Licence";
						}
						else if($res['CARD_KYC_DOCUMENT']=="7")
						{
							$CARD_KYC_DOCUMENT="Bank Photo Passbook";
						}
						else
						{
							$CARD_KYC_DOCUMENT="NA";
						}
                          						$data2['message']=$mcrypt->encrypt('Grower Details found');
						  $data2['success']=$mcrypt->encrypt('1');
						  $data2['primary_id']=$mcrypt->encrypt($res['PHOTOID_NUMBER']);
						  $data2['CARD_KYC_DOCUMENT']=$mcrypt->encrypt($CARD_KYC_DOCUMENT);
                         
                          $log->write($res['PHOTOID_NUMBER'].'-'.$CARD_KYC_DOCUMENT);
                        }
                        
                        else
                        {
                            $data2['message']=$mcrypt->encrypt('Card is not Active');
                            $data2['success']=$mcrypt->encrypt('-1');
							$data2['primary_id']=$mcrypt->encrypt('');
                        }
                        }
                        else
                        {
                            $data2['message']=$mcrypt->encrypt('Please check Grower ID');
                            $data2['success']=$mcrypt->encrypt('-1');
							$data2['primary_id']=$mcrypt->encrypt('');
                        }
                    }
                    else 
                    {
                         $data2['message']=$mcrypt->encrypt('Grower ID does not Exist');
						 $data2['success']=$mcrypt->encrypt('-1');
						 $data2['primary_id']=$mcrypt->encrypt('');
                         
                    }
					$log->write($data2);
                    $this->response->setOutput(json_encode($data2));
                }

//new kyc 
public function get_grower_details_kyc()
                {
					$log=new Log("Card-kyc-".date('Y-m-d').".log");
					$mcrypt=new MCrypt();
                          
					$log->write($this->request->post); 
					
					$grower_id=$mcrypt->decrypt($this->request->post['GROWER_ID']);
					$unit_id=$mcrypt->decrypt($this->request->post['UNIT_ID']);
                  
					$this->adminmodel('pos/dscl');
					$data=array('grower_id'=>$grower_id,'unit_id'=>$unit_id);
					
					$res=$this->model_pos_dscl->GetGrowerCard('CardDataViewKYC', $data, true);
					//$res=$this->model_card_card->check_grower_details($data); 
					$log->write($res);
					
                    //print_r($this->request);exit;
                   
                    if($res)
                    {
                        if($res['GROWER_ID']==$grower_id)
                        {
                        //print_r($res);
			if($res['CARD_STATUS']==7 || $res['CARD_STATUS']==2)
                        {
                        
						                                              

						if($res['CARD_KYC_DOCUMENT']=="1")
						{
							$CARD_KYC_DOCUMENT="Voter card";
						}
						else if($res['CARD_KYC_DOCUMENT']=="2")
						{
							$CARD_KYC_DOCUMENT="Aadhar card";
						}
						else if($res['CARD_KYC_DOCUMENT']=="3")
						{
							$CARD_KYC_DOCUMENT="Pan card";
						}
						else if($res['CARD_KYC_DOCUMENT']=="4")
						{
							$CARD_KYC_DOCUMENT="Driving Licence";
						}
						else if($res['CARD_KYC_DOCUMENT']=="5")
						{
							$CARD_KYC_DOCUMENT="Ration card";
						}
						else if($res['CARD_KYC_DOCUMENT']=="6")
						{
							$CARD_KYC_DOCUMENT="Arm Licence";
						}
						else if($res['CARD_KYC_DOCUMENT']=="7")
						{
							$CARD_KYC_DOCUMENT="Bank Photo Passbook";
						}
						else
						{
							$CARD_KYC_DOCUMENT="NA";
						}
                          						$data2['message']=$mcrypt->encrypt('Grower Details found');
						  $data2['success']=$mcrypt->encrypt('1');
						  $data2['primary_id']=$mcrypt->encrypt($res['PHOTOID_NUMBER']);
						  $data2['CARD_KYC_DOCUMENT']=$mcrypt->encrypt("0");//$CARD_KYC_DOCUMENT);
			}
			 else
                        {
                            $data2['message']=$mcrypt->encrypt('Grower KYC cannot be updated.');
                            $data2['success']=$mcrypt->encrypt('-1');
			    $data2['CARD_KYC_DOCUMENT']=$mcrypt->encrypt("-1");	
                        }

                         
                          $log->write($res['PHOTOID_NUMBER'].'-'.$CARD_KYC_DOCUMENT);
                        
                        }
                        else
                        {
                            $data2['message']=$mcrypt->encrypt('Please check Grower ID');
                            $data2['success']=$mcrypt->encrypt('-1');
							$data2['primary_id']=$mcrypt->encrypt('');
                        }
                    }
                    else 
                    {
                         $data2['message']=$mcrypt->encrypt('Grower ID does not Exist');
						 $data2['success']=$mcrypt->encrypt('-1');
						 $data2['primary_id']=$mcrypt->encrypt('');
                         
                    }
					$log->write($data2);
                    $this->response->setOutput(json_encode($data2));
                }


//end kyc

   public function generate_pin()
                {
					$log=new Log("Card-pin-".date('Y-m-d').".log");
					$mcrypt=new MCrypt();
                          
					$log->write($this->request->post);
					$log->write($this->request->get);
                  					  $Card_Serial_Number=$mcrypt->decrypt($this->request->post['Card_Serial_Number']);
					$grower_id=$mcrypt->decrypt($this->request->post['GROWER_ID']);
					$unit_id=$mcrypt->decrypt($this->request->post['UNIT_ID']);
                    $this->adminmodel('card/card');
			$this->adminmodel('pos/dscl');
			$company="dscl";
		$this->request->post['grower_id']=$grower_id;
		$this->request->post['unit_id']=$unit_id;
		$log->write("Grower details check");
		$datamob=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);

					$data=array('Card_Serial_Number'=>$Card_Serial_Number,'GROWER_ID'=>$grower_id,'UNIT_ID'=>$unit_id);
					$res=$this->model_card_card->check_grower_details($data);
					$log->write($grower_id.'-'.$unit_id);
					$log->write($res);
					
					$this->adminmodel('unit/unit');
					
                    if($res)
                    {
						$log->write('in res');
						$unit=$res['UNIT_ID'];
                        if($res['GROWER_ID']==$grower_id)
                        {
                        $log->write('in grower is matched');
						$data['MOB']=$datamob['MOB'];
                        if($res['CARD_STATUS']==9)
                        {
                        $log->write('in card status is 9');
					$unitdata= $this->model_unit_unit->getUnitByID($unit);
					//print_r($unitdata);exit; 
					$log->write($unitdata);
                  $rand_n=abs( crc32( uniqid() ) );
                  $card_serail_number=$res['CARD_SERIAL_NUMBER'];//$unitdata['company_id'].$unitdata['company_id'].$unit.substr($rand_n, 0,8) ;
                  //$card_sid=$this->request->get['sid'];
				   $StatusId='2';
                  $StatusName='CARD VERIFIED';
				    $this->request->post['CARD_STATUS']="p";
				   $this->request->post['CARD_STATUS_DESC']= $StatusName;
				  $this->request->post['CARD_SERIAL_NUMBER']= $card_serail_number;
				  $this->request->post['CARD_GROWER_ID']=  $grower_id;
				  $this->request->post['CARD_UNIT']= $unit;
				  $this->request->post['CARD_QR_SRTING']="0";
				  $this->request->post['USER']=0;//$this->user->getId();
				  $data['CARD_PIN']=$this->request->post['CARD_PIN']=substr($rand_n, 0,6);
				  $data['Card_Serial_Number']=$card_serail_number;
					$data['grower_id']=$grower_id;	
							//$this->load->library('soapcurl');
						//$soapcurl = new soapcurl($this->registry);
						if(!empty($unitdata['company_name']))
						{
						$company=strtolower($unitdata['company_name']);
						$this->adminmodel('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
						$log->write($datares);
						}
                          //print_r($datares);
						  if($datares==1)
						  {
							  $log->write('in status is updated at server');
                         								 $res=$this->model_card_card->generate_pin($data); 
							if(isset($this->request->post["user_id"]))
							{
								                     $data2['message']=('The Pin has been sent on your Registered Number XXXXXXXX'.substr($data['MOB'],8));
							      	$data2['success']=('1');
							}else{

                        							  $data2['message']=$mcrypt->encrypt('The Pin has been sent on your Registered Number XXXXXXXX'.substr($data['MOB'],8));
							  $data2['success']=$mcrypt->encrypt('1');
							}
						  }
						  else
						  {
							  $log->write('in status is not updated at server');
							  $data2['message']=$mcrypt->encrypt('Some error occour. please try again');
								$data2['success']=$mcrypt->encrypt('-1');
						  }
                         
                          
                        }
                        
                        else
                        {
							$log->write('in card is not active');
                            $data2['message']=$mcrypt->encrypt('Card is not Active');
                            $data2['success']=$mcrypt->encrypt('-1');
                        }
                        }
                        else
                        {
							$log->write('in grower id not matched');
                            $data2['message']=$mcrypt->encrypt('Sorry ! the details submitted by you is incorrect.');
                            $data2['success']=$mcrypt->encrypt('-1');
                        }
                    }
                    else 
                    {
						 $log->write('in data not found at unnati');
                         $data2['message']=$mcrypt->encrypt('Sorry! the details submitted by you is incorrect.');
						 $data2['success']=$mcrypt->encrypt('-1');
                         
                    }
                    $this->response->setOutput(json_encode($data2));
                }

//pin sms
   public function generate_pin_sms($grower_id,$unit_id,$Card_Serial_Number)
                {
					$log=new Log("Card-pinall-".date('Y-m-d').".log");
					$mcrypt=new MCrypt();
                          
					$log->write($this->request->post);
					$log->write($this->request->get);
                  			 // $Card_Serial_Number=$mcrypt->decrypt($this->request->post['Card_Serial_Number']);
					//$grower_id=$mcrypt->decrypt($this->request->post['GROWER_ID']);
					//$unit_id=$mcrypt->decrypt($this->request->post['UNIT_ID']);
                    $this->adminmodel('card/card');
			$this->adminmodel('pos/dscl');
			$company="dscl";
		$this->request->post['grower_id']=$grower_id;
		$this->request->post['unit_id']=$unit_id;
		$log->write($this->request->post);
		$log->write("Grower details check");
		$datamob=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);

					$data=array('Card_Serial_Number'=>$Card_Serial_Number,'GROWER_ID'=>$grower_id,'UNIT_ID'=>$unit_id);
					$res=$this->model_card_card->check_grower_details($data);
					$log->write($grower_id.'-'.$unit_id);
					$log->write($res);
					
					$this->adminmodel('unit/unit');
					
                    if($res)
                    {
						$log->write('in res');
						$unit=$res['UNIT_ID'];
                        if($res['GROWER_ID']==$grower_id)
                        {
                        $log->write('in grower is matched');
						$data['MOB']=$datamob['MOB'];
                        if($res['CARD_STATUS']==9)
                        {
                        $log->write('in card status is 9');
					$unitdata= $this->model_unit_unit->getUnitByID($unit);
					//print_r($unitdata);exit; 
					$log->write($unitdata);
                  $rand_n=abs( crc32( uniqid() ) );
                  $card_serail_number=$res['CARD_SERIAL_NUMBER'];//$unitdata['company_id'].$unitdata['company_id'].$unit.substr($rand_n, 0,8) ;
                  //$card_sid=$this->request->get['sid'];
				   $StatusId='2';
                  $StatusName='CARD VERIFIED';
				    $this->request->post['CARD_STATUS']="p";
				   $this->request->post['CARD_STATUS_DESC']= $StatusName;
				  $this->request->post['CARD_SERIAL_NUMBER']= $card_serail_number;
				  $this->request->post['CARD_GROWER_ID']=  $grower_id;
				  $this->request->post['CARD_UNIT']= $unit;
				  $this->request->post['CARD_QR_SRTING']="0";
				  $this->request->post['USER']=0;//$this->user->getId();
				  $data['CARD_PIN']=$this->request->post['CARD_PIN']=substr($rand_n, 0,6);
				  $data['Card_Serial_Number']=$card_serail_number;
					$data['grower_id']=$grower_id;	
							//$this->load->library('soapcurl');
						//$soapcurl = new soapcurl($this->registry);
						if(!empty($unitdata['company_name']))
						{
						$company=strtolower($unitdata['company_name']);
						$this->adminmodel('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
						$log->write($datares);
						}
                          //print_r($datares);
						  if($datares==1)
						  {
							  $log->write('in status is updated at server');
                         								 $res=$this->model_card_card->generate_pin($data); 
							if(isset($this->request->post["user_id"]))
							{
								                     $data2['message']=('The Pin has been sent on your Registered Number XXXXXXXX'.substr($data['MOB'],8));
							      	$data2['success']=('1');
							}else{

                        							  $data2['message']=$mcrypt->encrypt('The Pin has been sent on your Registered Number XXXXXXXX'.substr($data['MOB'],8));
							  $data2['success']=$mcrypt->encrypt('1');
							}
						  }
						  else
						  {
							  $log->write('in status is not updated at server');
							  $data2['message']=$mcrypt->encrypt('Some error occour. please try again');
								$data2['success']=$mcrypt->encrypt('-1');
						  }
                         
                          
                        }
                        
                        else
                        {
							$log->write('in card is not active');
                            $data2['message']=$mcrypt->encrypt('Card is not Active');
                            $data2['success']=$mcrypt->encrypt('-1');
                        }
                        }
                        else
                        {
							$log->write('in grower id not matched');
                            $data2['message']=$mcrypt->encrypt('Sorry ! the details submitted by you is incorrect.');
                            $data2['success']=$mcrypt->encrypt('-1');
                        }
                    }
                    else 
                    {
						 $log->write('in data not found at unnati');
                         $data2['message']=$mcrypt->encrypt('Sorry! the details submitted by you is incorrect.');
						 $data2['success']=$mcrypt->encrypt('-1');
                         
                    }
                   // $this->response->setOutput(json_encode($data2));
                }
//


//send pin to all
   public function generatepinall()
	{
		//send pin
		$log=new Log("Card-pinall-".date('Y-m-d').".log");
		$this->adminmodel('pos/dscl');
		$datamob=$this->model_pos_dscl->GetCheckCardMpin('GetCheckCardMpin',$this->request->post,0);
		  $this->load->library('sms');                
		$log->write($datamob); 
		foreach($datamob as $data){
			$log->write($data);

          		$sms=new sms($this->registry);	  
		  $sms->sendsms($data['MOB'], 14, $data);
			
					}
		$this->response->setOutput("1");
			
		
	}
//


}
?>