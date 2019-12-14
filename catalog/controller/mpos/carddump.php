<?php
/////qc////////
class Controllermposcarddump extends Controller {
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
   

  //get grower detail 
   function GetGrowerData()
   {
		$log=new Log("Card-GetGrowerData-dump-".date('Y-m-d').".log");
		$company="dscl";			
		$this->adminmodel('pos/'.$company);
		$this->request->post["sql"]="Select GROWER_ID,GROWER_NAME,UNIT_ID,VILLAGE_NAME,CARD_SERIAL_NUMBER,CARD_PIN from aspl_card_issue where CARD_STATUS='9' and UNIT_ID='04'";
		$datares= $this->{'model_pos_' . $company}->GetCardDataSql('GetCardDataSql',$this->request->post,0);
		$log->write($datares);
		$ret_array=array();
		foreach ($datares as $data) 
		{
			$ret_array[]=array('CARD_PIN'=>hexdec($data['CARD_PIN']),
							'GROWER_ID'=>$data['GROWER_ID'],
							'GROWER_NAME'=>$data['GROWER_NAME'],
							'UNIT_ID'=>$data['UNIT_ID'],
							'VILLAGE_NAME'=>$data['VILLAGE_NAME'],
							'CARD_SERIAL_NUMBER'=>$data['CARD_SERIAL_NUMBER']
								);
			$data['CARD_PIN']=hexdec($data['CARD_PIN']);
		}
		$log->write($ret_array);
		$this->response->setOutput(json_encode($ret_array));
			
}


   //get grower detail 
   function GetGrowerDetial()
   {

		$log=new Log("Card-GetGrowerDetial-dump-".date('Y-m-d').".log");
		$company="dscl";			
		$this->adminmodel('pos/'.$company);
		$this->request->post["sql"]="Select GROWER_ID,GROWER_NAME,UNIT_ID,VILLAGE_NAME,CARD_SERIAL_NUMBER,CARD_PIN from aspl_card_issue where CARD_STATUS='7'";
		$datares= $this->{'model_pos_' . $company}->GetCardDataSql('GetCardDataSql',$this->request->post,0);
		$log->write($datares);
		foreach ($datares as $data) {
		$log->write($data);
		$retdel=$this->delivery($data);
		$data['CARD_PIN']=$retdel; 
		$log->write($retdel);
		
		$log->write($datasave);
		}
   }
   
   //
   function delivery($data)
	{
		$log=new Log("Card-delivery-dump-".date('Y-m-d').".log");
		$mcrypt=new MCrypt();
		$retval=array();		
		$log->write($data);
		$this->request->post["grower_id"]=$data['GROWER_ID'];
		$this->request->post["Card_Serial_Number"]=$data['CARD_SERIAL_NUMBER'];	
		$this->request->post['CARD_UNIT']=$data['UNIT_ID'];
		$this->request->post['VILLAGE_NAME']=$data['VILLAGE_NAME'];
		$this->request->post['GROWER_ID']=$data['GROWER_ID'];
		$this->request->post['UNIT_ID']=$data['UNIT_ID'];
		$this->request->post['GROWER_NAME']=$data['GROWER_NAME'];

		 //$dataFromTheForm ='<card><VILLAGE_NAME>'.$this->encryptRJ($data['VILLAGE_NAME']).'</VILLAGE_NAME><CARD_GROWER_ID>'.$this->encryptRJ($data['GROWER_ID']).'</CARD_GROWER_ID>'.'<CARD_UNIT>'.$this->encryptRJ($data['UNIT_ID']).'</CARD_UNIT><GROWER_NAME>'.$this->encryptRJ($data['GROWER_NAME']).'</GROWER_NAME><CARD_SERIAL_NUMBER>'.$this->encryptRJ($data['CARD_SERIAL_NUMBER']).'</CARD_SERIAL_NUMBER><CARD_PIN>'.$this->encryptRJ(($data['CARD_PIN'])).'</CARD_PIN></card>'; 		

		//
		$this->adminmodel('card/integration');				
		$this->adminmodel('unit/unit');									
		//					
		$this->request->post['TX']= "2";
		$this->request->post['USER']="1";				       
		$datares="";
		$company="dscl";			
		$this->adminmodel('pos/'.$company);				
		$log->write("in if data");
		//get status from cane server
		$this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
		$this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];				 
		$log->write("in if data 1");		
		//update to cane server
		$this->request->post['CARD_STATUS']="9";
		$this->request->post['CARD_STATUS_DESC']="CARD ACTIVATED";
		$this->request->post['CARD_SERIAL_NUMBER']= $this->request->post["Card_Serial_Number"];
		$this->request->post['CARD_GROWER_ID']=  $this->request->post["grower_id"];		
		$this->request->post['CARD_QR_SRTING']="0";
		$this->request->post['USER_DEL_ID']="1";
		$this->request->post['USER_DEL_NAME']="SATHGURU";
		$this->request->post['USER_DEL_MOB']="0";
		$this->request->post['USER_DEL_IMEI']="0";						 
		$this->request->post['CARD_QR_SRTING']="0";
		$rand_n=abs( crc32( uniqid() ) );
		$this->request->post['CARD_PIN']=substr($rand_n, 0,6);	
		$data['CARD_PIN']=$this->request->post['CARD_PIN'];	
		$this->request->post['USER']="1";
		$datares= $this->{'model_pos_' . $company}->CardStatus('CardStatus',$this->request->post,0); 
		$log->write($datares);
		if($datares=="1"){
		$isdelivered=0;//$this->model_card_integration->card_delivery($this->request->post);
		$data=array('Card_Serial_Number'=>$this->request->post['CARD_SERIAL_NUMBER'],'GROWER_ID'=>$this->request->post['CARD_GROWER_ID'],'UNIT_ID'=>$this->request->post['CARD_UNIT']);
		//$datamob=$this->model_card_integration->getmobileno($this->request->post);
		$this->request->post['grower_id']=$this->request->post['CARD_GROWER_ID'];
		$this->request->post['unit_id']=$this->request->post['CARD_UNIT'];
		$log->write("Grower details check");
		$datamob=$this->{'model_pos_' . $company}->GetGrowerCardMob('GetGrowerCardMob',$this->request->post,0);
		$log->write($datamob);		
		$data['MOB']=$datamob['MOB'];
		$log->write("at mobile");					
		//server update
		$log->write("at update end");
		$res="1";
 		$data['pin']=$this->request->post['CARD_PIN'];
		$log->write($data);
        	$log->write('now call sms library');
		$this->load->library('sms'); 
      		$sms=new sms($this->registry); 
      		//$sms->sendsms($data['MOB'], 14, $data); 
	    	$log->write('sms sent');
		$log->write("after update end");
		$log->write($this->request->post);
		$datasave= $this->{'model_pos_' . $company}->SaveCardDataDump('SaveCardDataDump',$this->request->post,0);			
		$message="Success"; 
		$retval['status']=($isdelivered);
		$retval['message']=($message);									
		}
		else
		{		
		$message="Unable to connect cane system.";
		$retval['status']=("0");
		$retval['message']=($message);	 		
		}	
		$log->write($retval);	
		return	($data['CARD_PIN']);
	}
 
   //
   }
