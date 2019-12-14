<?php
class Controllermposcard extends Controller
{

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
	public function getreprintcardlist()
	{
		$log=new Log("reprintcard-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
        $log->write($this->request->post); 
		
		$page=$mcrypt->decrypt($this->request->post['page']);
		$filter_subuser=$mcrypt->decrypt($this->request->post['user_id']);
		$filter_growerid=$mcrypt->decrypt($this->request->post['grower_id']);
		$filter_unit_id=$mcrypt->decrypt($this->request->post['unit_id']);
		$filter_date_start=$mcrypt->decrypt($this->request->post['filter_date_start']);
		$filter_date_end=$mcrypt->decrypt($this->request->post['filter_date_end']);
		$filter_status=$mcrypt->decrypt($this->request->post['status_id']);
		
		if(empty($filter_date_start))
		{
			$filter_date_start=date('Y-m').'-01';
		}
		if(empty($filter_date_end))
		{
			$filter_date_end=date('Y-m-d');
		}
		if(empty($page))
		{
			$page=0;
		}
		if(empty($filter_status))
		{
			$filter_status=0;
		}
		$filter_data = array(
			'start'                  => ($page) ,
			'limit'                  => 20,
			'filter_unit' =>$filter_unit_id,
			'filter_subuser'=>$filter_subuser,
			'filter_growerid'=>$filter_growerid,
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'filter_status'=>$filter_status
		);
		
		$log->write($filter_data);
		
		$this->adminmodel('farmerrequest/farmerrequest');
		if(!empty($filter_subuser))
		{
			$log->write("in if");
			$results = $this->model_farmerrequest_farmerrequest->getreprint_requestlist($filter_data);
			$log->write($results);
		}
		else
		{
			$data['results']=array();
		}
		foreach ($results as $result) 
		{ //print_r($result);
			if($result['STATUS']==0)
			{
				$status='Not Printed';
			}
			else
			{
				$status='Printed';
			}
			$data['results'][] = array(
                'GROWER_ID' => $mcrypt->encrypt($result['grower_id']),
				'UNIT_NAME'     => $mcrypt->encrypt($result['unit_name']),
				'UNIT_ID'     => $mcrypt->encrypt($result['unit_id']),
				'SUBUSER_ID' => $mcrypt->encrypt($result['user_id']),
				'SUBUSER_NAME' => $mcrypt->encrypt($result['SUBUSER_NAME']),
				'CREATE_DATE' => $mcrypt->encrypt($result['CREATE_DATE']),
				'STATUS' => $mcrypt->encrypt($result['STATUS']),
				'GROWER_NAME' => $mcrypt->encrypt($result['GROWER_NAME']),
				'FTH_HUS_NAME' => $mcrypt->encrypt($result['FTH_HUS_NAME']),
				'VILLAGE_NAME' => $mcrypt->encrypt($result['VILLAGE_NAME']),
				'CARD_SERIAL_NUMBER' => $mcrypt->encrypt($result['CARD_SERIAL_NUMBER']),
				'STATUS_NAME' => $mcrypt->encrypt($status)
				
			);
			
		}
		$this->response->setOutput(json_encode($data));
	}
	public function reprintcard()
	{
		$log=new Log("reprintcard-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
        $log->write($this->request->post); 
		
		$this->request->post['grower_id']=$mcrypt->decrypt($this->request->post['grower_id']);//'511202450';
		$this->request->post['unit_id']=$mcrypt->decrypt($this->request->post['unit_id']);//'03';//
		$this->request->post['user_id']=$mcrypt->decrypt($this->request->post['user_id']);
		$this->request->post['store_id']=$mcrypt->decrypt($this->request->post['store_id']);
		if ($_SERVER['HTTP_X_FORWARDED_FOR'])
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} 
		else
		{ 
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$this->request->post['ip']=$ip;
		
        $this->adminmodel('farmerrequest/farmerrequest');
		$this->adminmodel('pos/dscl');
		$cardsql="select * FROM aspl_card_issue where GROWER_ID='".$this->request->post['grower_id']."' and UNIT_ID='".$this->request->post['unit_id']."'  ";
		$log->write($cardsql);
		$filter_data_sql = array(
			
			'sql'=> $cardsql	
		);
		$dsql_data=$this->model_pos_dscl->GetCardDataSql('GetCardDataSql',$filter_data_sql,0);
		
		if(!empty($dsql_data[0]['CARD_SERIAL_NUMBER']))
		{
			if($dsql_data[0]['CARD_STATUS']==9)
			{
				$this->request->post['GROWER_NAME']=$dsql_data[0]['GROWER_NAME'];
				$this->request->post['FTH_HUS_NAME']=$dsql_data[0]['FTH_HUS_NAME'];
				$this->request->post['VILLAGE_NAME']=$dsql_data[0]['VILLAGE_NAME'];
				$this->request->post['CARD_SERIAL_NUMBER']=$dsql_data[0]['CARD_SERIAL_NUMBER'];
		
				$res=$this->model_farmerrequest_farmerrequest->reprintcardrequest($this->request->post); 
				if($res=='1')
				{
					$data['message']=$mcrypt->encrypt('Grower details sent');
					$data['success']=$mcrypt->encrypt('1');
				}
				else
				{
					$data['message']=$mcrypt->encrypt('Request already submitted');
					$data['success']=$mcrypt->encrypt('0');
				}
			}
			else
			{
				$data['message']=$mcrypt->encrypt('Card not activated');
				$data['success']=$mcrypt->encrypt('0');
			}
		}
		else
		{
			$data['message']=$mcrypt->encrypt('Invalid Grower Code');
			$data['success']=$mcrypt->encrypt('0');
		}
		
		$this->response->setOutput(json_encode($data));
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
						
                        if($res['CARD_STATUS']==9 && (strlen($res['PHOTOID_NUMBER'])>=4))
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
			else if($res['CARD_STATUS']==9 && (strlen($res['PHOTOID_NUMBER'])<4))
			{
				$data2['message']=$mcrypt->encrypt('Card KYC pending');
                            $data2['success']=$mcrypt->encrypt('-1');
							$data2['primary_id']=$mcrypt->encrypt('');

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
/*
   public function generate_pin()
                {
					$log=new Log("Card-pin-".date('Y-m-d').".log");
					$log->write('generate_pin called ');
					$mcrypt=new MCrypt();
                          
					$log->write($this->request->post);
					$log->write($this->request->get);
                  			$Card_Serial_Number=$mcrypt->decrypt($this->request->post['Card_Serial_Number']);
					$username=$mcrypt->decrypt($this->request->post['username']);
					$grower_id=$mcrypt->decrypt($this->request->post['GROWER_ID']);
					$unit_id=$mcrypt->decrypt($this->request->post['UNIT_ID']);
					$log->write("unit id- ".$unit_id);
                    			$this->adminmodel('card/card');
					$this->adminmodel('pos/dscl');
					$company="dscl";
					$this->request->post['grower_id']=$grower_id;
					$this->request->post['unit_id']=$unit_id;
					$log->write("Grower details check");
					$datamob=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);
					$log->write("Grower details by company");
					$log->write($datamob);
					$data=array('Card_Serial_Number'=>$Card_Serial_Number,'GROWER_ID'=>$grower_id,'UNIT_ID'=>$unit_id);
					$res=$this->model_card_card->check_grower_details($data);
					$log->write($grower_id.'-'.$unit_id);
					$log->write($res);
					
					$this->adminmodel('unit/unit');
					$datatocomany=array(
									'CARD_SERIAL_NUMBER'=>$res['CARD_SERIAL_NUMBER'],
									'CARD_GROWER_ID'=>$grower_id,
									'CARD_UNIT'=>$unit_id,
									'USER'=>$username
								);
								$log->write('datatocomany');
								$log->write($datatocomany);
								
					$resbycompany = $this->{'model_pos_' . $company}->GetCardStatus('GetCardStatus',$datatocomany,0); 
					$log->write($resbycompany);
					
                    if($res)
                    {
						$log->write('in res');
						$unit=$res['UNIT_ID'];
                        if($res['GROWER_ID']==$grower_id)
                        {
                        $log->write('in grower is matched');
						$data['MOB']=$datamob['MOB'];
                        if($resbycompany==9)
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
						$log->write('CardStatus by company');
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
	*/
//
	function cardorderhistory()
	{	
		$log=new Log("card-orders-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$log->write($this->request->post);
		$log->write($this->request->get);
		$this->request->post["cdata"]=$mcrypt->decrypt($this->request->post["cdata"]);		
		$this->request->post["cdata"]=json_decode($this->request->post["cdata"]);		
		$log->write($this->request->post["cdata"]);
		$this->request->post["grower_id"]=$mcrypt->decrypt($this->request->post["cdata"]->GC);		
		$this->request->post["Card_Serial_Number"]=$mcrypt->decrypt($this->request->post["cdata"]->CSN);		
		
		$this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
		
		if (isset($this->request->get['filter_date_start'])) 
		{
			$filter_date_start = $this->request->get['filter_date_start'];
		} 
		else 
		{
			$filter_date_start = '2017-01-01';
		}

		if (isset($this->request->get['filter_date_end'])) 
		{
			$filter_date_end = $this->request->get['filter_date_end'];
		} 
		else 
		{
			$filter_date_end = date('Y-m-d');
		}
		
		$this->adminmodel('report/card');
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_unit_id' => null,
			'filter_card_number' => $this->request->post['CARD_SERIAL_NUMBER'],
			'filter_grower_id' => null
		);
		if(!empty($this->request->post['CARD_SERIAL_NUMBER']))
		{
			$datares = $this->model_report_card->getcardtrans($filter_data); 
			$data['products']=array();
			if(count($datares)>0)
			{
				foreach ($datares as $datare) 
				{
					$products=array();
					$results= $this->model_report_card->getorder_summarydetail($datare['order_id']);
					foreach ($results as $result) 
					{
						$products[] = array(
						'name' => $mcrypt->encrypt($result['name']),
						'quantity'   => $mcrypt->encrypt($result['quantity']),
						'price'      => $mcrypt->encrypt($result['price']),
						'tax'     => $mcrypt->encrypt($result['tax']),  
						'total'      => $mcrypt->encrypt($result['total'])
						);
					}        
					$data['products'][] = array(

					'card_serial_no' => $mcrypt->encrypt($datare['card_serial_no']),
          
					'grower_id'        =>$mcrypt->encrypt( $datare['grower_id']),
			
					'unit_name' =>$mcrypt->encrypt($datare['unit_name'])	,
			
					'storename'	=>$mcrypt->encrypt($datare['storename'])	,
			
					'date'	=>$mcrypt->encrypt($datare['datea'])	,
			
					'order_id'	=>$mcrypt->encrypt($datare['order_id']),
					'payment_method'	=>$mcrypt->encrypt($datare['payment_method'])	,
					'total'	=>$mcrypt->encrypt($datare['total'])	,
					'tagged'	=>$mcrypt->encrypt($datare['tagged'])	,
					'cash'	=>$mcrypt->encrypt($datare['cash'])	,
					'subsidy'	=>$mcrypt->encrypt($datare['subsidy']),
					'products'=>$products
					);
		
				}
				$data['status']='1';
				$data['meassage']='Success';
			}
			else
			{
				$data['status']='0';
				$data['meassage']='No data found';
			}
		}
		else
		{
			$data['status']='0';
			$data['meassage']='Card number not available ';
		}	
		if(!empty($data))
		{
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}
		
	}


}
?>