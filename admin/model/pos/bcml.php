<?php
class ModelPosBcml extends Model {
	
	//private $url ="http://bcmlcane.in/aspl/service.asmx";
	private $url ="http://www.bcmlcane.in/ASPL_QAS/Service.asmx";//
	public function GetDebitNoteInvoiceNoByDNDate($method, $data = array()) 
	{	
		//$this->GetDebitNoteDetail('GetDebitNoteDetail',$data);
		$data['dndate']=date('m@d@Y',strtotime($data['dndate']));
		
		$log=new Log("BCML-Soapcurl-GetDebitNoteInvoiceNoByDNDate-".date('Y-m-d').".log"); 
		$log->write($data);
		$dataFromTheForm= "<unitid>".$this->encryptRJ256($data['unitid'])."</unitid><invdate>".$this->encryptRJ256($data['dndate'])."</invdate><storeid>".$this->encryptRJ256($data['storeid'])."</storeid>"; 
	    	$log->write($dataFromTheForm);
	    	$response=$this->call($method,$data,true,$dataFromTheForm);
			//print_r($response);
		$log->write('response from bcm is -- ');
	    	$log->write($response);
		
		$temp = json_decode($response, TRUE);
		//$log->write($temp);
		$data_final=array();
		if(!empty($temp))
		{
		$obj = new ArrayObject( $temp );
		$it = $obj->getIterator();	
				
		foreach ($it as $key=>$val)
		{				
			
			$log->write($key.":".$this->decryptRJ256($val['INV_VAL']));
			$log->write($key.":".$this->decryptRJ256($val['INV_NO']));

			$val['INV_VAL']=$this->decryptRJ256($val['INV_VAL']);
			$val['INV_NO']=$this->decryptRJ256($val['INV_NO']);
			
			$data_final[]=($val);	
		}
		}
		//print_r($data_final);
		if(empty($data_final))
		{
			$log->write($response);
			//echo $response;
			return $response;
		}
		else
		{
			$log->write($data_final);
			return $data_final;
		}
		 
	}
	public function CreateDebitNote($method, $data = array()) 
	{	
		$log=new Log("BCML-Soapcurl-CreateDebitNote-".date('Y-m-d').".log"); 
		$log->write($data);
		$dataFromTheForm= "<unitid>".$this->encryptRJ256($data['unitid'])."</unitid><DebitNoteDetail>".$this->encryptRJ256($data['DebitNoteDetail'])."</DebitNoteDetail>"; 
	    	$log->write($dataFromTheForm);
	    	$response=$this->call($method,$data,true,$dataFromTheForm);
	    	$log->write($response);		
		return $response;
	}
	public function GetDebitNoteDetail($method, $data = array()) 
	{	
		$log=new Log("BCML-Soapcurl-GetDebitNoteDetail-".date('Y-m-d').".log"); 
		$log->write($data);
		$dataFromTheForm= "<unitid>".$this->encryptRJ256($data['unitid'])."</unitid><DebitNoteNo>".$this->encryptRJ256($data['DebitNoteNo'])."</DebitNoteNo>"; 
	    	$log->write($dataFromTheForm);
	    	$response=$this->call($method,$data,true,$dataFromTheForm);
		$log->write('response from bcm is -- ');
	    	$log->write($response);
		
		$temp = json_decode($response, TRUE);
		//$log->write($temp);
		$data_final=array();
		if(!empty($temp))
		{
		$obj = new ArrayObject( $temp );
		$it = $obj->getIterator();	
				
		foreach ($it as $key=>$val)
		{				
			$log->write($key.":".$val['DebitNoteNo']);
			$log->write($key.":".$this->decryptRJ256($val['InvoiceNo']));
			$log->write($key.":".$this->decryptRJ256($val['InvoiceDate']));

			$val['DebitNoteNo']=$this->decryptRJ256($val['DebitNoteNo']);
			$val['InvoiceNo']=$this->decryptRJ256($val['InvoiceNo']);
			$val['InvoiceDate']=$this->decryptRJ256($val['InvoiceDate']);
			$val['IndentNo']=$this->decryptRJ256($val['IndentNo']);	
			$val['TaggedValue']=$this->decryptRJ256($val['TaggedValue']);
			$val['UserName']=$this->decryptRJ256($val['UserName']);		
			$val['DebitNoteStatus']=$this->decryptRJ256($val['DebitNoteStatus']);					 				          
			if($val['DebitNoteStatuso']=='')
			{
				$val['DebitNoteStatus']="0";
			}
			
			$data_final[]=($val);	
		}
		}
		if(empty($data_final))
		{
			$log->write($response);
			//echo $response;
			return $response;
		}
		else
		{
			$log->write($data_final);
			return $data_final;
		}
		 
	}
	public function getAdvance($method, $data = array(), $is_json = true) 
	{	
		$log=new Log("BCML-Soapcurl-getAdvance-".date('Y-m-d').".log"); 
		$log->write($data);
		$dataFromTheForm= " <unitid>".$this->encryptRJ256($data['unitid'])."</unitid><advanceno>".$this->encryptRJ256($data['advanceno'])."</advanceno> <storeid>".$this->encryptRJ256($data['store_id'])."</storeid>"; 
	    	$log->write($dataFromTheForm);
	    	$response=$this->call($method,$data,true,$dataFromTheForm);
	    	$log->write($response);
		$temp = json_decode($response, TRUE);
		$log->write('temp data frombcml server');
		$log->write($temp);
		$data_final=array();
		if(!empty($temp))
		{
		$obj = new ArrayObject( $temp );
		$it = $obj->getIterator();	
				
		foreach ($it as $key=>$val)
		{				
			$log->write($key.":".$val['FM_CODE']);
			$log->write($key.":".$this->decryptRJ256($val['AdvanceNo']));
			$log->write($key.":".$this->decryptRJ256($val['VillageCode']));
			$val['AdvanceNo']=$this->decryptRJ256($val['AdvanceNo']);
			$log->write("1");
			$val['VillageCode']=$this->decryptRJ256($val['VillageCode']);
			$log->write("2");
			$val['VillageName']=$this->decryptRJ256($val['VillageName']);
			$log->write("3");
			$val['GrowerCode']=$this->decryptRJ256($val['GrowerCode']);
			$log->write("4");	
			$val['GrowerName']=$this->decryptRJ256($val['GrowerName']);
			$log->write("5");
			$val['FatherName']=$this->decryptRJ256($val['FatherName']);	
			$log->write("6");	
			$val['MobileNo']=$this->decryptRJ256($val['MobileNo']);	
			$log->write("7");				 				          
			if($val['MobileNo']=='')
			{
				$val['MobileNo']="0";
			}
				$log->write("8");
			if($this->decryptRJ256($val['GrowerLimit'])<0)
			{
				$val['GrowerLimit']=0;
			}else{
			$val['GrowerLimit']=$this->decryptRJ256($val['GrowerLimit']);	
			}
			$log->write("9");
			$val['OTP']=$this->decryptRJ256($val['OTP']);
			$log->write("10");		
			$val['OTPValidTill']=$this->decryptRJ256($val['OTPValidTill']);	
			$log->write("11");
			$val['BankAccountNo']=$this->decryptRJ256($val['BankAccountNo']);
			$log->write("12");
			if($val['BankAccountNo']=='')
			{
			 $val['BankAccountNo']="0";
			}
			$log->write("13");
			$this->model_pos_pos->insert_advance_otp($val['AdvanceNo'],$val['OTP']);
			$log->write("14");
			if(!empty($val['MobileNo']))
			{
			$log->write("15");
				$this->load->library('sms');	
			$log->write("16");
				$sdata=array();
			 $sms=new sms($this->registry);
			 $sdata['ttp']=$val['OTP'];
			 $sdata['rqid']=$val['AdvanceNo'];
   			 $sms->sendsms($val['MobileNo'],"5",$sdata);  
			$log->write("sms sent"); 

			}	
			//change
			$data_final[]=($val);	
		}
		}
		$log->write('data_final');
		$log->write($data_final);
		return $data_final;
	}
	public function getFM($method, $data = array(), $is_json = true) 
	{	
		$log=new Log("BCML-Soapcurl-getFM-".date('Y-m-d').".log"); 
		$log->write($data);
		$dataFromTheForm= " <unitid>".$this->encryptRJ256($data['unitid'])."</unitid>"; 
	    	$log->write($dataFromTheForm);
	    	$response=$this->call($method,$data,true,$dataFromTheForm);
	    	$log->write($response);
		$temp = json_decode($response, TRUE);
		$log->write($temp);
		$data_final=array();
		if(!empty($temp))
		{
		$log->write('temp is not empty so we are in');
		$obj = new ArrayObject( $temp );
		$it = $obj->getIterator();	
				
		foreach ($it as $key=>$val)
		{				
			$log->write($key.":".$val['FM_CODE']);
			$log->write($key.":".$this->decryptRJ256($val['FM_CODE']));
			$log->write($key.":".$this->decryptRJ256($val['FM_NAME']));
			$val['FM_NAME']=$this->decryptRJ256($val['FM_NAME']).' '.$this->decryptRJ256($val['FM_CODE']);
			$val['FM_CODE']=$this->decryptRJ256($val['FM_CODE']);
			$val['FM_PWD']=$this->decryptRJ256($val['FM_PWD']);
			$val['FM_MOBILE']=empty($this->decryptRJ256($val['FM_MOBILE']))?'-1':$this->decryptRJ256($val['FM_MOBILE']); 
			//change
			$data_final[]=($val);	
		}
		}
		$log->write($data_final);
		return $data_final;
	}
	
	public function GetIndentByInvoiceNo($method, $data = array(), $is_json = true) 
	{	
		$log=new Log("BCML-GetIndentByInvoiceNo-".date('Y-m-d').".log"); 
		$log->write($data);
		$dataFromTheForm= " <unitid>".$this->encryptRJ256($data['unitid'])."</unitid><invoiceno>".$this->encryptRJ256($data['invoiceno'])."</invoiceno> <storeid>".$this->encryptRJ256($data['store_id'])."</storeid>"; 
	    	$log->write($dataFromTheForm);
	    	$response=$this->call($method,$data,true,$dataFromTheForm);
	    	$log->write($response);
			
		$temp = json_decode($response, TRUE);
		//$log->write($temp);
		$data_final=array();
		if(!empty($temp))
		{
		$obj = new ArrayObject( $temp );
		$it = $obj->getIterator();	
				
		foreach ($it as $key=>$val)
		{				
			//$log->write($key.":".$val['FM_CODE']);
			$log->write($key.":".$this->decryptRJ256($val['InvoiceNo']));
			$log->write($key.":".$this->decryptRJ256($val['IndentNo']));
			$val['InvoiceNo']=$this->decryptRJ256($val['InvoiceNo']);
			$val['IndentNo']=$this->decryptRJ256($val['IndentNo']);
			$val['StoreID']=$this->decryptRJ256($val['StoreID']);
			//$val['InvoiceValue']=$this->decryptRJ256($val['InvoiceValue']);	
			//$val['DeliveryMode']=$this->decryptRJ256($val['DeliveryMode']);
			//$val['DeliveryDate']=$this->decryptRJ256($val['DeliveryDate']);		
			
			//change
			$data_final[]=($val);	
		}
		}
		$log->write($data_final);
		return $data_final;
	}

	
	public function SendRequest($method, $data = array(), $is_json = true) 
	{	
		$log=new Log("BCML-Soapcurl-SendRequest-".date('Y-m-d').".log"); 
		$dataFromTheForm= " <trans>
        <CARD_SERIAL_NUMBER>".$data['Card_Serial_Number']."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$data['grower_id']."</CARD_GROWER_ID>
        <NUM>".$data['otp']."</NUM>
        <MOB>".$data['MOB']."</MOB>
        <TrxType>".$data['TX']."</TrxType>
      </trans>"; 
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		return $response;
	}
	public function GetTrans($method, $data = array(), $is_json = true) 
	{
	
		$log=new Log("BCML-Soapcurl-GetTrans-".date('Y-m-d').".log"); 
		$dataFromTheForm= " <card>
        <CARD_SERIAL_NUMBER>".$data['Card_Serial_Number']."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$data['grower_id']."</CARD_GROWER_ID>
        <NUM></NUM>
        <MOB></MOB>
        <TrxType>".$data['TX']."</TrxType>
      </card>"; 
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		$card_status=json_decode($response);
		$log->write($card_status[0]);
		$fdata=$card_status[0];
		$log->write($this->decryptRJ($fdata->CARD_STATUS));
		return $this->decryptRJ(($fdata->CARD_STATUS));
	}
	public function CardRequest($method, $data = array(), $is_json = true) 
	{
	
		$log=new Log("BCML-Soapcurl-CardRequest-".date('Y-m-d').".log"); 
		$dataFromTheForm=  "<card>
        <CARD_SERIAL_NUMBER>".$data['CARD_SERIAL_NUMBER']."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$data['growerid']."</CARD_GROWER_ID>
        <CARD_UNIT>".$data['unitno']."</CARD_UNIT>
        <DATE>".$data['DATE']."</DATE>
        <USER>".$data['USER']."</USER>
        <CARD_PIN>".$data['CARD_PIN']."</CARD_PIN>
        <CARD_STATUS>"."1"."</CARD_STATUS>
        <CARD_CREATE_DATE>".$data['CARD_CREATE_DATE']."</CARD_CREATE_DATE>
        <CARD_VALIDITY_DATE>".$data['CARD_VALIDITY_DATE']."</CARD_VALIDITY_DATE>
        <CARD_ISSUE_DATE>".$data['CARD_ISSUE_DATE']."</CARD_ISSUE_DATE>
        <CARD_QR_SRTING>".$data['CARD_QR_SRTING']."</CARD_QR_SRTING>
        <CARD_KYC_DOCUMENT>".$data['CARD_KYC_DOCUMENT']."</CARD_KYC_DOCUMENT>
      </card>
      <grower>
        <ID>".$data['growerid']."</ID>
        <MNUM>".$data['farmermob']."</MNUM>
        <GROWER_NAME>".$data['fname']."</GROWER_NAME>
        <COMPANY_ID>".$data['COMPANY_ID']."</COMPANY_ID>
        <VILL>".$data['village']."</VILL>
        <FTH_HUS_NAME>".$data['fathername']."</FTH_HUS_NAME>
      </grower>";
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		return $response;
	}
public function CardStatus($method, $data = array(), $is_json = true) 
	{
	$log=new Log("BCML-Soapcurl-CardStatus-".date('Y-m-d').".log"); 
	if($data['CARD_STATUS']=="8"){
	$data['CARD_ISSUE_DATE']=date('Y-m-d');;
	}else{$data['CARD_ISSUE_DATE']="0";}
	
		$dataFromTheForm="<card>
        <CARD_SERIAL_NUMBER>".$data['CARD_SERIAL_NUMBER']."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$data['CARD_GROWER_ID']."</CARD_GROWER_ID>
        <CARD_UNIT>".$data['CARD_UNIT']."</CARD_UNIT>
        <DATE>0</DATE>
        <USER>0</USER>
        <CARD_PIN>0</CARD_PIN>
        <CARD_STATUS>".$data['CARD_STATUS']."</CARD_STATUS>
		 <CARD_STATUS_DESC>".$data['CARD_STATUS_DESC']."</CARD_STATUS_DESC>
        <CARD_CREATE_DATE>0</CARD_CREATE_DATE>
        <CARD_VALIDITY_DATE>0</CARD_VALIDITY_DATE>
        <CARD_ISSUE_DATE>".$data['CARD_ISSUE_DATE']."</CARD_ISSUE_DATE>
        <CARD_QR_SRTING>".$data['CARD_QR_SRTING']."</CARD_QR_SRTING>
        <CARD_KYC_DOCUMENT>0</CARD_KYC_DOCUMENT>
      </card>";
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		return $response;
	  
	}

public function GetCardStatus($method, $data = array(), $is_json = true) 
	{
		$log=new Log("BCML-Soapcurl-GetCardStatus-".date('Y-m-d').".log"); 	
		$dataFromTheForm="<card>
        <CARD_SERIAL_NUMBER>".$data['CARD_SERIAL_NUMBER']."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$data['CARD_GROWER_ID']."</CARD_GROWER_ID>
        <CARD_UNIT>".$data['CARD_UNIT']."</CARD_UNIT>
        <DATE>0</DATE>
        <USER>0</USER>
        <CARD_PIN>0</CARD_PIN>
        <CARD_STATUS>0</CARD_STATUS>
		 <CARD_STATUS_DESC>0</CARD_STATUS_DESC>
        <CARD_CREATE_DATE>0</CARD_CREATE_DATE>
        <CARD_VALIDITY_DATE>0</CARD_VALIDITY_DATE>
        <CARD_ISSUE_DATE>0</CARD_ISSUE_DATE>
        <CARD_QR_SRTING>0</CARD_QR_SRTING>
        <CARD_KYC_DOCUMENT>0</CARD_KYC_DOCUMENT>
      </card>";
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		$card_status=json_decode($response);
		$log->write($card_status[0]);
		$fdata=$card_status[0];
		$log->write($fdata->CARD_STATUS);
		return $this->decryptRJ(($fdata->CARD_STATUS));
	  
	}
	public function GetGrower($method, $datai = array(), $unit_id) 
	{
	$log=new Log("BCML-Soapcurl-GetGrower-".date('Y-m-d').".log"); 
	$log->write($data);
		// $dataFromTheForm ='<grower><VILL>'.$data['village_id'].'</VILL><ID>'.$data['grower_id'].'</ID>'.'<MNUM>'.$data['mobile'].'</MNUM></grower>'; // request data from the form
		 $dataFromTheForm='<unitid>'.$this->encryptRJ256($unit_id).'</unitid>
      <villcd>'.$this->encryptRJ256($datai['village_id']).'</villcd>
      <growcd>'.$this->encryptRJ256($datai['grower_id']).'</growcd>';
	  $log->write($dataFromTheForm);
		 $response=$this->call($method,$datai,true,$dataFromTheForm);
		 $temp = json_decode($response, TRUE);
           $log->write($temp);
           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();
           $data = array();                    
            foreach ($it as $key=>$val)
            {	
			$log->write($val['MobileNo']);
                      $data['GROWER_NAME']= $this->decryptRJ256($val['GrowerName']);
                      $data['FATHER_NAME']=$this->decryptRJ256($val['FatherName']);
                      $data['GROWER_ID']=$this->decryptRJ256($val['G_code']);
                      $data['UNIT_ID']='';
                      $data['MOB']=$this->decryptRJ256($val['MobileNo']);
                      $data['VILLAGE_CODE']=$this->decryptRJ256($val['VillageCode']);
					  $data['VILLAGE_NAME']=$this->decryptRJ256($val['VillageName']);
                      $data['CARD_STATUS_DESC']='Card Request';
                      $data['SID']='';
                      $data['CARD_SERIAL_NUMBER']='';
                      $data['SITE']='';
					  	$log->write($val);
            }
			$log->write($data);
			return $data;
	}

	public function getDataFromServer($data) 
	{
	$log=new Log("bcml-".date('Y-m-d').".log");
	$log->write("getDataFromServer called in  bcml");		
	$log->write($data);
	        //Data, connection, auth
       	 $dataFromTheForm ='<storeid>'.$this->encryptRJ256($data['storeid']).'</storeid><indentno>'.$this->encryptRJ256($data['indentno']).'</indentno><unitid>'.$this->encryptRJ256($data['unitid']).'</unitid>'; // request data from the form
	$func_name='GetIndentByNo';
	$log->write($dataFromTheForm);
	$url=$this->url;
        	$soapUrl = $url."?op=".$func_name; // asmx URL of WSDL	
        	$soapUser = "username";  //  username
        	$soapPassword = "password"; // password

        // xml post structure
        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <'.$func_name.' xmlns="http://aksha/app/"> 
                                  '.$dataFromTheForm.' 
                                </'.$func_name.'>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/".$func_name, 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;
            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch);
 $log->write('response');	
	    $log->write($response);	
if(empty($response))
		{ 
			$log->write('in  if return data is empty');
			$retstr= "{'error':'".$this->encryptRJ256(curl_error($ch))."'}";		
   		 
		}
		

           else{ 
			   $log->write('in else if return data is not empty');
            // converting
	$str_remove ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><'.$func_name.'Response xmlns="http://aksha/app/" /></soap:Body></soap:Envelope>';
	           $response1 = str_replace($str_remove,"",$response);

           // $response1 = str_replace("[","",$response1);

	$response1 = str_replace("IndentNo","order_id",$response1);
	//$response1 = str_replace("IndentDate","date_added",$response1);
	$response1 = str_replace("MobileNo","telephone",$response1);
	$response1 = str_replace("Amount","total",$response1);
	$response1 = str_replace("GrowerName","date_added",$response1);
	$response1 = str_replace("FatherName","lastname",$response1);
	//
	//$response1 = str_replace("AI_Status","error",$response1);
	
	$log->write($response1);
	//
	
	$temp = json_decode($response1, TRUE);
	$log->write($temp);
	
	try{
	$log->write($this->decryptRJ256($temp[0]['order_id']));	
	//if(empty($temp[0]['telephone']))
		//{
   		 //return $retstr= "{error:'".$this->encryptRJ256("Grower mobile number not found")."'}"; 
		// }	
	}catch(Exception $e){$log->write($e);}
	$str_add=',"req_order_id":"'.$temp[0]['order_id'] .'","card_no":"dKsrs3USnzvTuASpwVI6Mw=="}]';
    $response1 = str_replace("}]",$str_add,$response1);		
	$log->write($response1);	
		//order_id,customer_id ,firstname date_added,lastname,telephone,order_status_id,total,unit_code,card_no,req_order_id	
            // convertingc to XML
    $parser =json_decode($response1);// simplexml_load_string($response);
	//parser error
	$log->write("parser data");
	$log->write($parser);
	$log->write($response1 );
	 if (strpos($response1, 'Status') !== false) 
		{	
			/* -1 means not records found in system
				0 means records found but not approved
			*/
			$log->write("in if parser data");	
			$log->write($parser[0]->Status);
			$log->write($parser[0]->telephone);
			if($parser[0]->Status==$this->encryptRJ256("-1"))
			{
				$retstr= "{'error':'".$this->encryptRJ256("No record found")."'}";	
			}	
			else if($parser[0]->Status==$this->encryptRJ256("0"))
			{
			$retstr= "{'error':'".$this->encryptRJ256("Indent approval pending")."'}";	
			}
			else if($parser[0]->Status==$this->encryptRJ256("2"))
			{
				$retstr= "{'error':'".$this->encryptRJ256("Indent already invoiced")."'}";	
			}
			/*else if( empty($this->decryptRJ256($parser[0]->telephone)))
			{
				$retstr= "{error:'".$this->encryptRJ256("Grower mobile number not found")."'}"; 
			}*/
			else if(empty($parser[0]->Status)&& $parser[0]->AI_Status==$this->encryptRJ256("1"))
			{
				  if (strpos($response1, 'A network-related or instance-specific error occurred while establishing a connection to SQL Server') !== false) 
				{
					$retstr= "{'error':'".$this->encryptRJ256("Database execute permission was denied on the object")."'}";	
				}else{
					$retstr="{products:".$response1."}";
				}
			}
			
		}
		else  if (strpos($response1, 'The EXECUTE permission was denied on the object') !== false) 
		{
		$retstr= "{'error':'".$this->encryptRJ256("Database execute permission was denied on the object")."'}";	
		}

		else{
            // user $parser to get your data out of XML response and to display it.
	  $retstr="{products:".$response1."}";}
}
 curl_close($ch);
 $log->write("return value");
 $log->write($retstr);
	return  $retstr;
	}

//end data

public function getDataDetailFromServer($data) 
	{
	$log=new Log("bcml-dtl-".date('Y-m-d').".log");
	$log->write("save data bcml");		
	$log->write($data);
	$this->load->model('catalog/product');
	$this->load->library('user');
	$this->load->library('tax');
	$this->tax=new Tax($this->registry);
	$log->write("data");
	$this->session->data['user_id']=$data['userid'];;
	$this->user = new User($this->registry);
	$this->config->set('config_store_id',$data['storeid']);
	
		$log->write($this->decryptRJ256($data['indentno']));
	        //Data, connection, auth
    $dataFromTheForm ='<storeid>'.$this->encryptRJ256($data['storeid']).'</storeid><indentno>'.($data['indentno']).'</indentno><unitid>'.$this->encryptRJ256($data['unitid']).'</unitid>'; // request data from the form
	$func_name='GetIndentDetailByNo';
	$url=$this->url;
    $soapUrl = $url."?op=".$func_name; // asmx URL of WSDL	
    $soapUser = "username";  //  username
    $soapPassword = "password"; // password

        // xml post structure

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <'.$func_name.' xmlns="http://aksha/app/"> 
                                  '.$dataFromTheForm.' 
                                </'.$func_name.'>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/".$func_name, 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch); 
	    $log->write($response);	
		if(empty($response))
		{
   		 $retstr= "{error:".$this->encryptRJ256(curl_error($ch))."}";		}


else{
            // converting
	$str_remove ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><'.$func_name.'Response xmlns="http://aksha/app/" /></soap:Body></soap:Envelope>';
            	$response1 = str_replace($str_remove,"",$response);
           // $response1 = str_replace("[","",$response1);
	$response1 = str_replace("IndentNo","order_id",$response1);
	$response1 = str_replace("ItemCode","product_id",$response1);
	$response1 = str_replace("ItemName","name",$response1);
	$response1 = str_replace("Amount","total",$response1);
	$response1 = str_replace("ApprovedQty","quantity",$response1);
	$response1 = str_replace("OTP","totp",$response1);
	$response1 = str_replace("Rate","price",$response1);
	$response1 = str_replace("UOM","tax",$response1);
	$data_final=array();
	//$log->write($response1);
	$temp = json_decode($response1, TRUE);
	//$log->write($temp);
	$obj = new ArrayObject( $temp );
	$it = $obj->getIterator();
	$total="0";
	$subtotal="0";
	$tax="0";
	$otp="0";
	$growermob=$this->encryptRJ256("0");
	$growername=$this->encryptRJ256("0");
	$growerfathername=$this->encryptRJ256("0");
	$growervillagename=$this->encryptRJ256("0");
	$growervillagecode=$this->encryptRJ256("0");
	$BankAccountNo=$this->encryptRJ256("0");
	$growerlimit="0";
	$otpvaildtime=date("d-m-Y H:i:s");
	$productcheck=array();
	$sbsdy=0;
	$log->write("before start the product loop");
	$total_tax=0;
	foreach ($it as $key=>$val)
	{	
		$log->write("in loop for ".$this->decryptRJ256($val['name']));
		$total=$total+$this->decryptRJ256($val['total']);
		$log->write($key.":".$val['tax']);
		$log->write($key.":".$this->decryptRJ256($val['name'])."-product_id-".$this->decryptRJ256($val['product_id'])); 
		$log->write($key.":".$this->decryptRJ256($val['name'])."-price-".$this->decryptRJ256($val['price']));
		$log->write($key.":".$this->decryptRJ256($val['name'])."-Actprice-".$this->decryptRJ256($val['Actprice']));
		$log->write($key.":".$this->decryptRJ256($val['name'])."-quantity-".$this->decryptRJ256($val['quantity']));
		$log->write($key.":".$this->decryptRJ256($val['name'])."-Subsidytotal-".$this->decryptRJ256($val['Subsidytotal']));
		$log->write($key.":".$this->decryptRJ256($val['name'])."-S_DESC-".$this->decryptRJ256($val['S_DESC']));
		
		if(empty($this->decryptRJ256($val['S_DESC'])))
		{
			$val['S_DESC']=$this->encryptRJ256("000");
		}
		//change
		$product_id=$this->decryptRJ256($val['product_id']);
		$log->write($product_id);
		$log->write("prodct check");
		if(in_array($product_id,$productcheck))
		{
			$log->write("same product id-".$product_id);
			 return "{error:'".$this->encryptRJ256("Same product found multiple times")."',success:''}"; 
		}
		array_push($productcheck,$product_id);
		$log->write($productcheck);
		
		$log->write($product_id);
		$log->write('Subsidy');
		$val['SubRate']=($val['price']);
		$log->write($val['Subsidytotal']);
		$sbsdy=$sbsdy+$this->decryptRJ256($val['Subsidytotal']);
		
		//$log->write($this->decryptRJ256($val['Actprice']));
		//$log->write($val['Acttotal']);
		//$val['SubSidyPer']=$this->decryptRJ256($val['SubSidyPer']);
		//$val['Subsidytotal']=$this->decryptRJ256($val['Subsidytotal']);
		//$val['Acttotal']=$this->decryptRJ256($val['Acttotal']);
		
		
		//$log->write($val['Subsidytotal']);
		//$log->write($val['Acttotal']);
		
		$log->write("read");
		$log->write($product_id);
		$log->write("before call the getProduct");			
		$products = $this->model_pos_pos->getProduct($product_id,$data['storeid']);		
		$log->write("after call the getProduct");
		$log->write($products);
		$log->write("end");
		if(!empty($products['HSTN']))
		{
			$val['hstn']=$this->encryptRJ256($products['HSTN']);
		}
		else
		{
			$val['hstn']=$this->encryptRJ256("0000");
		}
		//end change
		//$val['price']=$this->encryptRJ256($products['price']);
		//$log->write($val['Actprice']);
		$checkprice=$this->decryptRJ256($val['Actprice']);
		$log->write("check price=".$checkprice);
		$products['price']=(number_format((float)$products['price'], 2, '.', ''));
		$ttax=$this->tax->getTax($products['price'], $products['tax_class_id']);
		if(!empty($products['stax']))
		{
			$ttax=number_format((float)($products['stax']), 2, '.', '') ;//($this->decryptRJ256($val['quantity'])*$this->tax->getTax($products['price'], $products['tax_class_id']));
		}
		
		$ttax=(number_format((float)$ttax, 2, '.', ''));
		$subtotal=$subtotal+($products['price']*$this->decryptRJ256($val['quantity']));
		$val['price']=$products['price'];//$this->decryptRJ256($val['price'])-$ttax;
		$log->write("product price=".$products['price']);
		$log->write("product tax=".$ttax);
		$log->write(round($products['price']+$ttax));
		
		$log->write("product total tax=".($ttax*$this->decryptRJ256($val['quantity'])));
		$total_tax=$total_tax+($ttax*$this->decryptRJ256($val['quantity']));
		
		$log->write("total_tax=".$total_tax);
		
		$log->write(round($checkprice));
		$log->write((number_format((float)$checkprice, 2, '.', '')));
		$log->write((number_format((float)($products['price']+$ttax), 2, '.', '')));
		if(empty($val['price']))
		{
   		 return "{error:'".$this->encryptRJ256("Price empty")."',success:''}"; 
		}
		else if($val['price']==0.0000)
		{
		 $log->write("in price error the getProduct");
		 $strerror="{error:'".$this->encryptRJ256("Price mismatch")."',success:''}";
		 return  $strerror;//"{products:".$strerror."}";
		}
		if((number_format((float)$checkprice, 2, '.', ''))>(number_format((float)($products['price']+$ttax), 2, '.', '')))
		//if(round($checkprice)>round($products['price']+$ttax))
		{

		 $log->write("in price error the greater at cane system");
		 $strerror="{error:'".$this->encryptRJ256("Price greater at cane system")."',success:''}";
		 return  $strerror;
			
		}
		if((number_format((float)$checkprice, 2, '.', ''))<(number_format((float)($products['price']+$ttax), 2, '.', '')))
		//if(round($checkprice)<round($products['price']+$ttax))
		{


		 $log->write("in price error price is lower at cane system");
		 $strerror="{error:'".$this->encryptRJ256("Price mismatch with cane system")."',success:''}";
		 return  $strerror;
		
		}
		$log->write($val['price']);
		$val['price']=$this->encryptRJ256($val['price']);
		$val['tax']=$this->encryptRJ256($ttax);//$this->encryptRJ256($this->decryptRJ256($val['quantity'])*$this->tax->getTax($products['price'], $products['tax_class_id']));///I just update this line to get the tax on product base price//$this->encryptRJ256("1");//"dKsrs3USnzvTuASpwVI6Mw==";
		$log->write("tax".$this->decryptRJ256($val['tax']));
		$tax=$tax+$this->decryptRJ256($val['tax']);
		$log->write("Tax");
		$log->write($tax);
		$data_final[]=($val);

		$otp=$val['totp'];
		$log->write($this->decryptRJ256($otp));
		//$log->write("products detail");  
		//$log->write($data_final);
		
		//grower details
		$growerid=$val['G_Code'];
		$growermob=$val['MobileNo'];
		$log->write("mob-".$this->decryptRJ256($growermob));
		$growername=$val['GrowerName'];
		$growerfathername=$val['FatherName'];
		$growervillagename=$val['VillageName'];
		$growervillagecode=$val['VillageCode'];
		$BankAccountNo=$this->decryptRJ256($val["BankAccountNo"]);
		$log->write("BankAccountNo");
		$log->write(($BankAccountNo));
		if(strlen($BankAccountNo)<4)
		{$BankAccountNo="11".$BankAccountNo;}
		if(empty($BankAccountNo))
		{
			return "{error:'".$this->encryptRJ256("Grower A/C no. not found")."',success:''}"; 
		}
		$log->write("GrowerLimit");
		$log->write(($val["GrowerLimit"]));
		$val["GrowerLimit"]=str_replace("tax","UOM",$val["GrowerLimit"]);
		$val["GrowerLimit"]=str_replace("totp","OTP",$val["GrowerLimit"]);
		$log->write(($val["GrowerLimit"]));
		$growerlimit=$this->decryptRJ256($val["GrowerLimit"]);
		$fmcode=$this->decryptRJ256($val["FMCODE"]);
		$log->write('fmcode');
		$log->write($fmcode);
		if(empty($growerlimit))
		{
			$log->write('in if empty');
			$log->write(($growerlimit));
			$val["GrowerLimit"]=str_replace("UOM","tax",$val["GrowerLimit"]);
		    $val["GrowerLimit"]=str_replace("OTP","totp",$val["GrowerLimit"]);
			$growerlimit=$this->decryptRJ256($val["GrowerLimit"]);
		}
		$log->write("GrowerLimit after decode- ");
		$log->write(($growerlimit));
		
		$otpvaildtime=$this->decryptRJ256($val["totpValidTill"]);
		//$this->cache->set($data['indentno'], $val["GrowerLimit"]);
	}
	$log->write('');
	$log->write('after loop of products');
	$log->write('');
	$log->write('subsidy total');
	$log->write($sbsdy);
	
	$log->write("subtotal");
    $log->write($subtotal);
    $log->write("tax");
    $log->write($tax);
	$log->write("total_tax=".$total_tax);
	$log->write("order total");
    $log->write(($subtotal+$total_tax));
	$log->write("sbsdy");
    $log->write($sbsdy);
	$log->write("growerlimit");
    $log->write($growerlimit);
	//if(!empty($sbsdy))
	if(empty($growerlimit))
	{
		$growerlimit=0.0;
	}
	if($growerlimit<0)
	{
		$growerlimit=0.0;
	}	
	$receivelimit=$growerlimit;	
	$ftotal=$growerlimit-$total;
	$log->write(($ftotal));
	$log->write("total data ".($ftotal));
	if($ftotal==0)
	{
		$growerlimit=$total;
	}
	else if ($ftotal > 0)
	{
		$growerlimit= $total;
	}
	
	$bcash=0;
	$bsubsidy=(number_format((float)($sbsdy), 2, '.', ''));
	$btotal=(number_format((float)($subtotal+$total_tax), 2, '.', ''));
	$log->write("bsubsidy");
			$log->write($bsubsidy);
			$log->write("btotal");
			$log->write($btotal);
	if($growerlimit<=0)
		{				
			$log->write("bsubsidy");
			$log->write($bsubsidy);
			$log->write("btotal");
			$log->write($btotal);
			if($bsubsidy==$btotal)
			{
				//tagged subsidy
				
			}
			else if(empty($growerlimit) && $bsubsidy>0 && ($bsubsidy<$btotal))
			{	
				//if cash >0 and tagged =0 then cash subsidy 
				$log->write("cash subsidy");
				$bcash=($btotal-$bsubsidy-$growerlimit);				
				$log->write("bcash");
				$log->write($bcash);
				
			}
			else if(empty((int)$growerlimit) && $bsubsidy>0 && ($bsubsidy<$btotal))
			{	
				//if cash >0 and tagged =0 then cash subsidy 
				$log->write("cash subsidy");
				$bcash=($btotal-$bsubsidy-$growerlimit);				
				$log->write("bcash");
				$log->write($bcash);
				
			}
			else
			{
				return "{error:'".$this->encryptRJ256("Grower limit not found")."',success:''}"; 
			}
		}
		else
		{
			//tagged
			if(empty($bsubsidy) && ($growerlimit>0) && ($growerlimit<$btotal))
			{
				//tagged cash
				$bcash=$btotal-$bsubsidy-$growerlimit;
			}
			else if(($bsubsidy+$growerlimit)==$btotal)
			{
				$bcash=0;
			}
			else if(!empty($growerlimit) && (($growerlimit+$bsubsidy)<$btotal))
			{
				//if cash >0 and tagged >0 then tagged cash subsidy
				$log->write("tagged cash subsidy");
				$bcash=($btotal-$bsubsidy-$growerlimit);				
				$log->write("bcash");
				$log->write($bcash);
			}
		}
		
	$log->write("products detail");  
	$log->write($data_final);
	$log->write(($BankAccountNo));
	$log->write(($total));
	
	
	
	$log->write("after total data ".($growerlimit));
	
	
		$log->write($response1);
		$response1 =json_encode($data_final);		
	    $parser =json_decode($response1);// simplexml_load_string($response);
		$log->write($parser);
		//$startTime = date("d-m-Y H:i:s");
		$log->write($otpvaildtime);
		$cenvertedTime = $otpvaildtime;//date('d-m-Y H:i:s',strtotime('+30 minutes',strtotime($startTime)));
		$log->write($cenvertedTime);	
		$log->write("subtotal");
		$log->write($subtotal);
    
	/*if(!empty($sbsdy))
	{
		if((number_format((float)($subtotal+$total_tax), 2, '.', ''))>(number_format((float)($growerlimit+$sbsdy), 2, '.', '')))
		{
			//return "{error:'".$this->encryptRJ256("Tagged Cash Subsidy Not allowed")."',success:''}"; 
		}
	}
	else
	{
		if((number_format((float)($subtotal+$total_tax), 2, '.', ''))>(number_format((float)($growerlimit), 2, '.', '')))
		{
			$log->write("Order Total ");
			$log->write((number_format((float)($subtotal+$total_tax), 2, '.', '')));
			$log->write("grower limit ");
			$log->write((number_format((float)($growerlimit), 2, '.', '')));
			
			if(($data['storeid']!='65') && ($data['storeid']!='64') && ($data['storeid']!='58') && ($data['storeid']!='56') && ($data['storeid']!='57') && ($data['storeid']!='61')) 
			{
				$log->write("Tagged Cash Not allowed");
				return "{error:'".$this->encryptRJ256("Tagged Cash Not allowed")."',success:''}";
			} 
			if(($data['storeid']=='58') && ((number_format((float)($subtotal+$total_tax), 2, '.', ''))-(number_format((float)($growerlimit), 2, '.', '')))>500)
			{
				$log->write("Tagged Cash Not allowed for greater then 500");
				return "{error:'".$this->encryptRJ256("Tagged Cash Not allowed for greater then 500")."',success:''}";
			}
		}
	}*/
	$str=",total:'".$this->encryptRJ256($growerlimit)."',tax:'".$this->encryptRJ256($tax)."',subtotal:'".$this->encryptRJ256($subtotal)."',bkacc:'"."dKsrs3USnzvTuASpwVI6Mw=="."',idt:'"."dKsrs3USnzvTuASpwVI6Mw=="."',totp:'".$otp."',ext:'".$this->encryptRJ256($cenvertedTime)."',motp:'".$this->encryptRJ256("1234")."',success:'"."dKsrs3USnzvTuASpwVI6Mw=="."',stname:'".$this->encryptRJ256($this->config->get('config_name'))."',cid:'".$growerid."',fname:'".$growername."',lname:'".$growerfathername."',vname:'".$growervillagename."',tname:'".$growermob."',baccount:'".$this->encryptRJ256($BankAccountNo)."',fmcode:'".$this->encryptRJ256($fmcode)."',receivelimit:'".$this->encryptRJ256($receivelimit)."',subsidytotal:'".$this->encryptRJ256($sbsdy)."',cashtotal:'".$this->encryptRJ256($bcash)."'";
	$retstr="{products:".$response1.$str."}";
	//send otp
	//$this->load->library('sms');	
	//$this->adminmodel('pos/pos');
	$this->model_pos_pos->insert_indent_otp($this->decryptRJ256($data['indentno']),$this->decryptRJ256($otp));	
	//$sms=new sms($this->registry);
	$sdata['ttp']=$this->decryptRJ256($otp);
	$sdata['rqid']=$this->decryptRJ256($data['indentno']);
   // $sms->sendsms("9140872430","5",$sdata);  
		//insert otp data delivery
		
}
            curl_close($ch);
	return $retstr;

	}
function UpdateOTPByInvoiceNo($data)
{
	//data
	$log=new Log("bcml-UpdateOTPByInvoiceNo-".date('Y-m-d').".log");	
	$log->write($data);
	$dataFromTheForm ='<invoiceno>'.$this->encryptRJ256($data['billno']).'</invoiceno><otp>'.$this->encryptRJ256($data['otp']).'</otp><DeliveryReceipt>'.$this->encryptRJ256($data['DeliveryReceipt']).'</DeliveryReceipt><unitid>'.$this->encryptRJ256($data['unitid']).'</unitid><userid>'.$this->encryptRJ256($data['userid']).'</userid>'; // request data from the form
	$log->write($dataFromTheForm);
	$func_name='UpdateOTPByInvoiceNo';
	$url=$this->url;
       	 $soapUrl = $url."?op=".$func_name; // asmx URL of WSDL	
        	$soapUser = "username";  //  username
        	$soapPassword = "password"; // password
	  $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <'.$func_name.' xmlns="http://aksha/app/"> 
                                  '.$dataFromTheForm.' 
                                </'.$func_name.'>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/".$func_name, 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);	
            curl_setopt($ch, CURLOPT_TIMEOUT, 400);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
	  $log->write("result");
            $response = curl_exec($ch); 
	    $log->write($response);	
		if(empty($response))
		{
			$log->write(curl_error($ch));
   		 $retstr= "0";		}


else{


   // converting
   $str_remove ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><'.$func_name.'Response xmlns="http://aksha/app/" /></soap:Body></soap:Envelope>';
            	$response1 = str_replace($str_remove,"",$response);
	 //$response1 = str_replace("<soap:Body>","",$response);
          //  $response2 = str_replace("</soap:Body>","",$response1);
		$log->write($response1);	
		//$log->write($response2);	

            // convertingc to XML
            //$parser =simplexml_load_string($response2);
		$retval=$response1;
		$log->write($retval);
		$retstr=$retval;
}

  curl_close($ch);
  $log->write("last result");	
  $log->write($retstr);
	return $retstr;


	}

function setOrderDataToServer($data,$paytype=null)
{
	//data
	$log=new Log("bcml-setorder-dtl-".date('Y-m-d').".log");
	$log->write("save data dscl");		
	$log->write($data);
	if( empty($data['ApprovalType']))
	{
	 $data['ApprovalType']="F";
	}
	//$log->write($this->cache->get($data['indentno']));
	//$data['prddtl']
	        //Data, connection, auth
    $dataFromTheForm ='<VerifiedThrough>'.$this->encryptRJ256($data['ApprovalType']).'</VerifiedThrough><FmCode>'.$this->encryptRJ256($data['FmCode']).'</FmCode><DeliveryMode>'.$this->encryptRJ256($data['DeliveryMode']).'</DeliveryMode><DeliveryReceipt>'.$this->encryptRJ256($data['DeliveryReceipt']).'</DeliveryReceipt><ItemDetail>'.$this->encryptRJ256($data['prddtl']).'</ItemDetail><taggedvalue>'.$this->encryptRJ256($data['ordervalue']).'</taggedvalue><cash>'.$this->encryptRJ256($data['cash']).'</cash><subsidy>'.$this->encryptRJ256($data['subsidy']).'</subsidy><invoicevalue>'.$this->encryptRJ256($data['invoicevalue']).'</invoicevalue><unitid>'.$this->encryptRJ256($data['unitid']).'</unitid><storeid>'.$this->encryptRJ256($data['storeid']).'</storeid><indentno>'.($data['indentno']).'</indentno><glimit>'.$this->encryptRJ256($data['glimit']).'</glimit><eid>'.$this->encryptRJ256($data['userid']).'</eid><invoiceno>'.$this->encryptRJ256($data['billno']).'</invoiceno><otp>'.$this->encryptRJ256($data['otp']).'</otp>'; // request data from the form
	$func_name=null;
	if(empty($paytype))
	{
		$func_name='UpdateDelivery';
	}
	else
	{
		$func_name='UpdateCashDelivery';
		$dataFromTheForm='<unitid>'.$this->encryptRJ256($data['unitid']).'</unitid><storeid>'.$this->encryptRJ256($data['storeid']).'</storeid><invoiceno>'.$this->encryptRJ256($data['billno']).'</invoiceno><gmobile>'.$this->encryptRJ256($data['gmobile']).'</gmobile><gname>'.$this->encryptRJ256($data['gname']).'</gname><gcode>'.$this->encryptRJ256($data['gcode']).'</gcode><vcode>'.$this->encryptRJ256($data['vcode']).'</vcode><vname>'.$this->encryptRJ256($data['vname']).'</vname><cash>'.$this->encryptRJ256($data['cash']).'</cash><invoicevalue>'.$this->encryptRJ256($data['invoicevalue']).'</invoicevalue><FmCode>'.$this->encryptRJ256($data['FmCode']).'</FmCode><ItemDetail>'.$this->encryptRJ256($data['prddtl']).'</ItemDetail>';
		
	}
	$log->write($dataFromTheForm);
	//$this->cache->delete($data['indentno']);
	$log->write("func_name");            
	$log->write($func_name);	
	if(empty($func_name))
	{
		$log->write("function name not defined.");
   		$retstr= "0";		
		return $retstr;
	}
	$url=$this->url;
       	 $soapUrl = $url."?op=".$func_name; // asmx URL of WSDL	
        	$soapUser = "username";  //  username
        	$soapPassword = "password"; // password

        // xml post structure

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <'.$func_name.' xmlns="http://aksha/app/"> 
                                  '.$dataFromTheForm.' 
                                </'.$func_name.'>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/".$func_name, 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
            curl_setopt($ch, CURLOPT_TIMEOUT,400);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
	  $log->write("result");
            $response = curl_exec($ch); 
	    $log->write($response);	
		if(empty($response))
		{
			$log->write(curl_error($ch));
   		 $retstr= "0";		}


else{


   // converting
   $str_remove ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><'.$func_name.'Response xmlns="http://aksha/app/" /></soap:Body></soap:Envelope>';
            	$response1 = str_replace($str_remove,"",$response);
	 //$response1 = str_replace("<soap:Body>","",$response);
          //  $response2 = str_replace("</soap:Body>","",$response1);
		$log->write($response1);	
		//$log->write($response2);	

            // convertingc to XML
            //$parser =simplexml_load_string($response2);
		$retval=$response1;
		$log->write($retval);
		$retstr=$retval;




}

  curl_close($ch);
  $log->write("last result");	
  $log->write($retstr);
	return $retstr;


}


function setOrderDataToServer_lumpsum($data)
{
	//data
	$log=new Log("bcml-setorder-lumpsum-dtl-".date('Y-m-d').".log");
	$log->write("save data dscl");		
	$log->write($data);
	if( empty($data['ApprovalType']))
	{
	 $data['ApprovalType']="F";
	}
	//$log->write($this->cache->get($data['indentno']));
	//$data['prddtl']
	        //Data, connection, auth
    $dataFromTheForm ='<VerifiedThrough>'.$this->encryptRJ256($data['ApprovalType']).'</VerifiedThrough><FmCode>'.$this->encryptRJ256($data['FmCode']).'</FmCode><DeliveryMode>'.$this->encryptRJ256($data['DeliveryMode']).'</DeliveryMode><DeliveryReceipt>'.$this->encryptRJ256($data['DeliveryReceipt']).'</DeliveryReceipt><ItemDetail>'.$this->encryptRJ256($data['prddtl']).'</ItemDetail><taggedvalue>'.$this->encryptRJ256($data['ordervalue']).'</taggedvalue><cash>'.$this->encryptRJ256($data['cash']).'</cash><invoicevalue>'.$this->encryptRJ256($data['invoicevalue']).'</invoicevalue><unitid>'.$this->encryptRJ256($data['unitid']).'</unitid><storeid>'.$this->encryptRJ256($data['storeid']).'</storeid><Advanceno>'.$this->encryptRJ256($data['indentno']).'</Advanceno><glimit>'.$this->encryptRJ256($data['glimit']).'</glimit><eid>'.$this->encryptRJ256($data['userid']).'</eid><invoiceno>'.$this->encryptRJ256($data['billno']).'</invoiceno><otp>'.$this->encryptRJ256($data['otp']).'</otp>'; // request data from the form
	$log->write($dataFromTheForm);
	//$this->cache->delete($data['indentno']);
	$func_name='UpdateDelivery_LumpSum';
	$url=$this->url;
       	 $soapUrl = $url."?op=".$func_name; // asmx URL of WSDL	
        	$soapUser = "username";  //  username
        	$soapPassword = "password"; // password

        // xml post structure

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <'.$func_name.' xmlns="http://aksha/app/"> 
                                  '.$dataFromTheForm.' 
                                </'.$func_name.'>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/".$func_name, 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);	
            curl_setopt($ch, CURLOPT_TIMEOUT, 400);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
	  $log->write("result");
            $response = curl_exec($ch); 
	    $log->write($response);	
		if(empty($response))
		{
			$log->write(curl_error($ch));
   		 $retstr= "0";		}


else{


   // converting
   $str_remove ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><'.$func_name.'Response xmlns="http://aksha/app/" /></soap:Body></soap:Envelope>';
            	$response1 = str_replace($str_remove,"",$response);
	 //$response1 = str_replace("<soap:Body>","",$response);
          //  $response2 = str_replace("</soap:Body>","",$response1);
		$log->write($response1);	
		//$log->write($response2);	

            // convertingc to XML
            //$parser =simplexml_load_string($response2);
		$retval=$response1;
		$log->write($retval);
		$retstr=$retval;




}

  curl_close($ch);
  $log->write("last result");	
  $log->write($retstr);
	return $retstr;


}


function utf8ize($d)
{ 
    if (is_array($d) || is_object($d))
        foreach ($d as &$v) $v = utf8ize($v);
    else
        return utf8_encode($d);

    return $d;
}

//decrypt .net value 
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


public function getLeadsFromServer($data) 
	{
	//print_r($data);
	$log=new Log("getleads-bcml-".date('Y-m-d').".log");
	$log->write("save data bcml");		
	$log->write($data);
	//Data, connection, auth $this->encryptRJ256
    $dataFromTheForm ='<unitid>'.($data['unitid']).'</unitid><datefrom>'.($data['datefrom']).'</datefrom><dateto>'.($data['dateto']).'</dateto><status>'.($data['status']).'</status>'; // request data from the form
	$func_name='GetIndentList';
	$log->write($dataFromTheForm);
	$url=$this->url;
        	$soapUrl = $url."?op=".$func_name; // asmx URL of WSDL	
        	$soapUser = "username";  //  username
        	$soapPassword = "password"; // password

        // xml post structure
        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <'.$func_name.' xmlns="http://aksha/app/"> 
                                  '.$dataFromTheForm.' 
                                </'.$func_name.'>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/".$func_name, 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;
            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch);
			$log->write('response from server');	
			$log->write($response);	
			if(empty($response))
			{
				$retstr= "{'error':'".$this->encryptRJ256(curl_error($ch))."'}";		
   		 
			}

			else
			{
				// converting
				$str_remove ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><'.$func_name.'Response xmlns="http://aksha/app/" /></soap:Body></soap:Envelope>';
				$response1 = str_replace($str_remove,"",$response);
				// $response1 = str_replace("[","",$response1);
	
				$temps = json_decode($response1, TRUE);
	
				/*
				try{
				$log->write($this->decryptRJ256($temp[0]['order_id']));
				}catch(Exception $e){$log->write($e);}
				*/
	
	$log->write($temps);
	$query=$this->db->query("select store_id from oc_store_to_unit where unit_id='".$data['unitid']."' limit 1 ");
	$log->write('1');
	$store_data = $query->row;
	$store_id=$store_data['store_id'];
	///$this->load->model('pos/pos');
	$log->write('2');
	foreach($temps as $temp)
	{
	$log->write('in loop');
	$log->write($temp);
	//echo '<br/>IndentNo';
	
	$MobileNo=$this->decryptRJ256($temp['MobileNo']);
	$log->write($MobileNo);
	$GrowerName=$this->decryptRJ256($temp['GrowerName']);
	$log->write('3');
	$VillageName=$this->decryptRJ256($temp['VillageName']);
	
	$this->addcustomer($store_id,array('customer_mob'=>$MobileNo,'village_name'=>$VillageName,'farmer_name'=>$GrowerName));
	$customer_id='0';//$this->model_pos_pos->getCustomerByPhone($MobileNo)["customer_id"];
	$log->write('4');
	$user_id=$this->decryptRJ256($temp['user_id']);
	
	$G_Code=$this->decryptRJ256($temp['G_Code']);
	$payment_lastname=$this->decryptRJ256($temp['GrowerName']);
	$payment_firstname=$this->decryptRJ256($temp['FatherName']);
	$payment_address_1=$G_Code.'-'.$payment_lastname.'-'.$payment_firstname;
	
	
	$invoice_prefix='';
	$card_no='';
	$customer_group_id='1';
	$payment_company='';
	$payment_address_2='';
	$payment_city='';
	$payment_postcode='';
	$payment_country='';
	$payment_country_id='0';
	$payment_zone='';
	$payment_zone_id='0';
	$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';                    
	$payment_method='Tagged';
	$payment_code='in_store';
	$shipping_firstname=$VillageName.'-';
	$shipping_lastname='';
	$shipping_company='';
	$shipping_address_1='';
	$shipping_address_2=$data['unitid'];
	$shipping_code='';
	$shipping_city='';
	$shipping_postcode='';
	$shipping_country='0';
	$shipping_zone='';
	$shipping_zone_id='0';
	$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
	$shipping_method='Pickup From Store';
	$shipping_code='';
	$currency_value='1';
	$total=$this->decryptRJ256($temp['Amount']);
	$order_status_id='1';
	$IndentNo=$this->decryptRJ256($temp['IndentNo']);
	$i_sql="select order_id from oc_order_leads where IndentNo='".$IndentNo."' limit 1 ";
	$query2=$this->db->query($i_sql);
	$indent_data = $query2->row;
	$order_id=$indent_data['order_id'];
	$log->write($i_sql);
	$log->write($order_id);
	
	if(!empty($order_id))///////means already added in the table
	{
	$upd_sql="update oc_order_leads set total=total+".$total." where IndentNo='".$IndentNo."' ";
	$log->write($upd_sql);
	
	$query3=$this->db->query($upd_sql);
	
	}
	else//////means new data and need to insert
	{
	 $insert_q="INSERT INTO `" . DB_PREFIX . "order_leads` SET card_no ='" . $this->db->escape($card_no) . "',  invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$store_id . "', store_name = '" . $this->db->escape('') . "',store_url = '" . $this->db->escape('') . "', customer_id = '" . (int)$customer_id . "', 
	 customer_group_id = '" . (int)$customer_group_id . "', firstname = '" . $this->db->escape($payment_lastname) . "', lastname = '" . $this->db->escape('') . "', 
	 email = '" . $this->db->escape($MobileNo) . "', telephone = '" . $this->db->escape($MobileNo) . "', fax = '" . $this->db->escape($MobileNo) . "', 
	 payment_firstname = '" . $this->db->escape($payment_firstname) . "', payment_lastname = '" . $this->db->escape($payment_lastname) . "', 
	 payment_company = '" . $this->db->escape($payment_company) . "', payment_address_1 = '" . $this->db->escape($payment_address_1) . "', 
	 payment_address_2 = '" . $this->db->escape($payment_address_2) . "', payment_city = '" . $this->db->escape($payment_city) . "', 
	 payment_postcode = '" . $this->db->escape($payment_postcode) . "', payment_country = '" . $this->db->escape($payment_country) . "', 
	 payment_country_id = '" . (int)$payment_country_id . "', payment_zone = '" . $this->db->escape($payment_zone) . "', 
	 payment_zone_id = '" . (int)$payment_zone_id . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', 
	 payment_method = '" . $this->db->escape($payment_method) . "', 
	 payment_code = '" . $this->db->escape($payment_code) . "', shipping_firstname = '" . $this->db->escape($shipping_firstname) . "', 
	 shipping_lastname = '" . $this->db->escape($shipping_lastname) . "', shipping_company = '" . $this->db->escape($shipping_company) . "', 
	 shipping_address_1 = '" . $this->db->escape($shipping_address_1) . "', shipping_address_2 = '" . $this->db->escape($shipping_address_2) . "', 
	 shipping_city = '" . $this->db->escape($shipping_city) . "', shipping_postcode = '" . $this->db->escape($shipping_postcode) . "', 
	 shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$shipping_country_id. "', 
	 shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$shipping_zone_id. "', 
	 shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($shipping_method) . "', 
	 shipping_code = '" . $this->db->escape($shipping_code) . "', comment = '" . $this->db->escape('') . "', order_status_id = '" . (int)$order_status_id . "',
	 affiliate_id  = '0', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '2', currency_code = '" . $this->db->escape('INR') . "', 
	 currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW(),date_potential='".$data['date_potential']."',
	 total='".$total."',IndentNo='".$IndentNo."' ";
          $this->db->query($insert_q);
          $log->write($insert_q);
          $order_id = $this->db->getLastId();
	}
	
	$product_id=$this->decryptRJ256($temp['ItemCode']);
	$ItemName=$this->decryptRJ256($temp['ItemName']);
	$UOM=$this->decryptRJ256($temp['UOM']);
	$price=$this->decryptRJ256($temp['Rate']);
	$quantity=$this->decryptRJ256($temp['ApprovedQty']);
	$Amount=$this->decryptRJ256($temp['Amount']);
	$tax='0';
	$reward='0';
	
	$p_query="INSERT INTO " . DB_PREFIX . "order_product_leads SET order_id = '" . (int)$order_id . "', 
	product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($ItemName) . "', 
	model = '" . $this->db->escape($ItemName) . "', quantity = '" . (int)$quantity . "', 
	price = '" . (float)$price . "', 
	total = '" . (float)$Amount . "', tax = '" . (float)$tax. "', 
	reward = '" . (int)$reward. "'";
	
	$log->write($p_query);
	$this->db->query($p_query);
	
	}
	//$log->write($response1);	
		
    //$parser =json_decode($response1);// simplexml_load_string($response);
	//$log->write($parser);
            
	  //$retstr="{products:".$response1."}";
}
 curl_close($ch);
	//return  $retstr;
	}

//end data
public function addcustomer($sid,$data)
                {                                                                      
             $log=new Log("getleads-bcml-".date('Y-m-d').".log");
				$log->write("addcustomer");	
              //$this->load->model('sale/customer');                           
              unset($this->session->data['cid']);
             $this->request->post['email']=$data['customer_mob'];
             $this->request->post['fax']=$data['customer_mob'];
             $this->request->post['telephone']=$data['customer_mob'];
	         $this->request->post['customer_group_id']="1";
             $this->request->post['password']=$data['customer_mob'];
             $this->request->post['newsletter']='0';        
             $this->request->post['approved']='1';
             $this->request->post['status']='1';
             $this->request->post['safe']='1';
             $this->request->post['address_1']= $data['village'];
             $this->request->post['address_2']= $data['village'];
             $this->request->post['city']= $data['village'];
             $this->request->post['company']='Unnati';
             $this->request->post['country_id']='0';
             $this->request->post['zone_id']='0';
             $this->request->post['postcode']='0';
             $this->request->post['store_id']=$sid;             
             $this->request->post['address']=array($data);
			 $log->write($this->request->post);
            // $this->model_sale_customer->addCustomer($this->request->post); 
			$log->write('addcustomer done');			 
          }


		  	public function call($method, $data = array(), $is_json = true,$dataFromTheForm ) {
            
              $log=new Log("BCML-Soapcurl-".date('Y-m-d').".log"); 
		
                //xml post              
	$func_name=$method;
	 $log->write($dataFromTheForm);
        	$soapUrl =  $this->url."?op=".$func_name; // asmx URL of WSDL	
        	

        // xml post structure
        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <'.$func_name.' xmlns="http://aksha/app/"> 
                                  '.$dataFromTheForm.' 
                                </'.$func_name.'>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/".$func_name, 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;
             $log->write($url);
            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                //end post
                                                               


//		curl_setopt_array($ch, $defaults);
           $response = curl_exec($ch);
           curl_close($ch);
           $log->write($response);
           $str_remove ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><'.$func_name.'Response xmlns="http://aksha/app/" /></soap:Body></soap:Envelope>';
           $response1 = str_replace($str_remove,"",$response);
           
           
        /*  $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
			

            // convertingc to XML
            $parser =simplexml_load_string($response2);   
            $retval=(string)$parser->{$func_name.'Response'}->{$func_name.'Result'};*/

             $log->write($response1);
            return $response1;
        }
 
} 
?>