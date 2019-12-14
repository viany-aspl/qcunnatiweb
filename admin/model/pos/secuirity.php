<?php
class ModelPosDscl extends Model 
{
	private $url="http://182.71.40.85/akshamobtest/service.asmx";
	//private $url="http://dsclsugar.com/akshamob/service.asmx";  
//private $url="http://182.71.40.85/akshamob/service.asmx"; 

	public function dscl_resent_pin_trans($data)
	{
		$log=new Log("Authentication-resetkey-".date('Y-m-d').".log");		
        $sql="insert into oc_dscl_resent_pin_trans SET mobile='".$data['mobile']."',store_id='".$data['store_id']."',date_added = NOW(),request_data='".serialize($data['request_data'])."',response='".serialize($data['response'])."' ";
        $query = $this->db->query($sql);
        $log->write($sql);
	}
	public function VerifyCoupon($method, $data = array(), $is_json = true) 
	{
	$method="VerifiyCoupon";
	$log=new Log("order-coupon-dscl-".date('Y-m-d').".log"); 
	$log->write(' dscl model called');
	$log->write($data);    
		 //$dataFromTheForm ='<grower><VILL>'.$this->encryptRJ($data['village_id']).'</VILL><ID>'.$this->encryptRJ($data['grower_id']).'</ID>'.'<MNUM>'.$this->encryptRJ($data['mobile']).'</MNUM></grower>'; // request data from the form
		$dataFromTheForm = " 
        <uid>".$data['unit_id']."</uid>
        <sid>".$data['store_id']."</sid>
        <coupon>".strtoupper($data['coupon'])."</coupon>
        ";
		$log->write($dataFromTheForm);  

		 $response=$this->call($method,$data,true,$dataFromTheForm);
		$log->write('return by dscl');  
		$log->write($response);    
		 	$response1 = str_replace("<soap:Body>","",$response);
		$log->write($response1);    
           	 	$response2 = str_replace("</soap:Body>","",$response1);			
		$log->write($response2);    
            		// convertingc to XML
            		$parser =simplexml_load_string($response2);
            		$retval=(string)$parser->{$method.'Response'}->{$method.'Result'};
		$log->write($retval);   
	$log->write('before temp');   
	$retval=str_replace('ProductDtls','"ProductDtls"',$retval);
			$product_data=array();
		 $temp = json_decode($retval, TRUE);
           $log->write($temp);
	if(!empty($temp))
	{
           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();
		    
				$log->write('before foreach');
			
            foreach ($it as $key=>$val1)
            {	

		foreach ( $val1 as $val){
		$log->write($val);
		$log->write($val['PRODUCT_CODE']);
		$log->write($val['SUBSIDY_RATE']);
		$data = array();  
                      $data['PRODUCT_ID']= abs(100000-$this->decryptRJ($val['PRODUCT_CODE']));
                      $data['SUBSIDY_RATE']=$this->decryptRJ($val['SUBSIDY_RATE']); 
		$log->write($data);
array_push($product_data,$data);	
}	
                     
            }

	} 
	$log->write('product_data');
	$log->write($product_data);
	//$product_data=array();
	$return_data= array(
				'coupon_id'     => '0',
				'code'          => $data['coupon'],
				'name'          => 'DSCL-SUBSIDY',
				'type'          => 'P',
				'discount'      => '0',
				'shipping'      => '0',
				'total'         => '0',
				'productsubsidy'       => $product_data,
				'date_start'    => '',
				'date_end'      => '',
				'uses_total'    => '',
				'uses_customer' => '',
				'status'        => 1,
				'date_added'    => ''
			);	
			$log->write('return_data');
			$log->write($return_data);
			return $return_data;
	}

public function getDataFromServer($data) 
	{
	$log=new Log("dscl-req-".date('Y-m-d').".log");
	$log->write("save data bcml");		
	$log->write($data);
	        //Data, connection, auth
        $dataFromTheForm ='<empID>'.$data['storeid'].'</empID><oid>'.$data['indentno'].'</oid><unitid>'.$data['unitid'].'</unitid>'; // request data from the form
	$func_name='getrequisitiondelivery';
	$log->write($dataFromTheForm);
	$url= $this->url;
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch); 
	    $log->write($response);	
            curl_close($ch);
            // converting
		
            $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
		$log->write($response1);	
		$log->write($response2);	

            // convertingc to XML
            $parser =simplexml_load_string($response2);
		$retval=(string)$parser->{$func_name.'Response'}->{$func_name.'Result'};
		$log->write($retval);
		
            // user $parser to get your data out of XML response and to display it.
return $retval;

	}

//end data

public function getDataDetailFromServer($data) 
	{

	$log=new Log("dscl-req-dtl-".date('Y-m-d').".log");
	$log->write("save data dscl");		
	$log->write($data);
	$this->load->model('catalog/product');
	$this->load->library('user');
	$log->write("data");
	$this->session->data['user_id']=$data['userid'];;
	$this->user = new User($this->registry);
	$this->config->set('config_store_id',$data['storeid']);

	        //Data, connection, auth
       	 $dataFromTheForm ='<ordID>'.($data['indentno']).'</ordID>'; // request data from the form
	$log->write($dataFromTheForm);
	$func_name='getrequisitiondeliverydtlw';
	$url= $this->url;
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
	  $log->write("result");
            $response = curl_exec($ch); 
	    $log->write($response);	
		if(empty($response))
		{
   		 $retstr= "{error:".$this->encryptRJ(curl_error($ch))."}";		}


else{
            // converting
	 $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
		$log->write($response1);	
		$log->write($response2);	

            // convertingc to XML
            $parser =simplexml_load_string($response2);
		$retval=(string)$parser->{$func_name.'Response'}->{$func_name.'Result'};
		$log->write($retval);
		$retstr=$retval;


}
            curl_close($ch);
	return $retstr;

	}


function setOrderDataToServer($data)
{
	//data
	$log=new Log("dscl-setorder-dtl-".date('Y-m-d').".log");
	$log->write("save data dscl");		
	$log->write($data);
	        //Data, connection, auth
       	$dataFromTheForm ='<oid>'.($data['indentno']).'</oid><eid>'.$data['userid'].'</eid><billno>'.$data['billno'].'</billno><motp>'.$data['otp'].'</motp>'; // request data from the form
	$log->write($dataFromTheForm);
	$func_name='updaterequistion';
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
	  $log->write("result");
            $response = curl_exec($ch); 
	    $log->write($response);	
		if(empty($response))
		{
   		 $retstr= "{error:".$this->encryptRJ(curl_error($ch))."}";		}


else{


   // converting
	 $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
		$log->write($response1);	
		$log->write($response2);	

            // convertingc to XML
            $parser =simplexml_load_string($response2);
		$retval=(string)$parser->{$func_name.'Response'}->{$func_name.'Result'};
		$log->write($retval);
		$retstr=$retval;




}

  curl_close($ch);
	return $retstr;


}



//
	public function SaveCardDataDump($method, $data = array(), $is_json = true) 
	{
	
	$method="SaveCardStatusDump";
	$log=new Log("Soapcurl-SaveCardDataDump-".date('Y-m-d').".log"); 
	$log->write($data);    
		 $dataFromTheForm ='<card><VILLAGE_NAME>'.$this->encryptRJ($data['VILLAGE_NAME']).'</VILLAGE_NAME><CARD_GROWER_ID>'.$this->encryptRJ($data['GROWER_ID']).'</CARD_GROWER_ID>'.'<CARD_UNIT>'.$this->encryptRJ($data['UNIT_ID']).'</CARD_UNIT><GROWER_NAME>'.$this->encryptRJ($data['GROWER_NAME']).'</GROWER_NAME><CARD_SERIAL_NUMBER>'.$this->encryptRJ($data['CARD_SERIAL_NUMBER']).'</CARD_SERIAL_NUMBER><CARD_PIN>'.$this->encryptRJ(($data['CARD_PIN'])).'</CARD_PIN></card>'; 		
		 $log->write($dataFromTheForm);
		$response=$this->call($method,$data,true,$dataFromTheForm);
		$log->write($response);
		$response1 = str_replace("<soap:Body>","",$response);
           	 $response2 = str_replace("</soap:Body>","",$response1);			

            // convertingc to XML
            $parser =simplexml_load_string($response2);
            $retval=(string)$parser->{$method.'Response'}->{$method.'Result'};

		
			 $log->write($retval);
			return $retval;
	}
//


	public function GetCardDataSql($method, $data = array(), $is_json = true) 
	{
	$method="GetQueryColumnName";
	$log=new Log("Soapcurl-GetCardDataSql-".date('Y-m-d').".log"); 
	$log->write($data);    		 
		$dataFromTheForm = "<Query>".($data['sql'])."</Query>";
		$log->write( $dataFromTheForm);
		 $response=$this->call($method,$data,true,$dataFromTheForm);
		 $log->write('response');
		 $log->write( $response);
		$response1 = str_replace("<soap:Body>","",$response);
           	 $response2 = str_replace("</soap:Body>","",$response1);			

            // convertingc to XML
            $parser =simplexml_load_string($response2);
            $retval=(string)$parser->{$method.'Response'}->{$method.'Result'};

		 $temp = json_decode($retval, TRUE);
		 $log->write('temp');
           $log->write($temp);
		    $data = array();                    

           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();

            foreach ($it as $key=>$val)
            {	
                      $data[]=$val;
                     
            }

			 $log->write($data);
			return $data;
	}

	public function GetOrderProductData($method, $data = array(), $is_json = true) 
	{
	$method="GetorderproductById";
	$log=new Log("Soapcurl-GetOrderProductData-".date('Y-m-d').".log"); 
	$log->write($data);    
		 //$dataFromTheForm ='<grower><VILL>'.$this->encryptRJ($data['village_id']).'</VILL><ID>'.$this->encryptRJ($data['grower_id']).'</ID>'.'<MNUM>'.$this->encryptRJ($data['mobile']).'</MNUM></grower>'; // request data from the form
		$dataFromTheForm = "<oid>".$data['oid']."</oid>";
		 $response=$this->call($method,$data,true,$dataFromTheForm);
		 $log->write( $response);
		$response1 = str_replace("<soap:Body>","",$response);
           	 $response2 = str_replace("</soap:Body>","",$response1);			

            // convertingc to XML
            $parser =simplexml_load_string($response2);
            $retval=(string)$parser->{$method.'Response'}->{$method.'Result'};

		 $temp = json_decode($retval, TRUE);
           $log->write($temp);
		    $data = array();                    

           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();

            foreach ($it as $key=>$val)
            {	
                      $data[]=$val;
                     
            }

			 $log->write($data);
			return $data;
	}
 
	public function GetOrderData($method, $data = array(), $is_json = true) 
	{
	$method="GetorderData";
	$log=new Log("Soapcurl-DsclGetOrderData-".date('Y-m-d').".log"); 
	$log->write($data);    
		 	
		$dataFromTheForm = "
        <sdate>".$data['sdate']."</sdate>
        <edate>".$data['edate']."</edate>
        ";
		$log->write("dssd");   		 
		 $response=$this->call($method,$data,true,$dataFromTheForm);
		 $log->write( $response);
		$response1 = str_replace("<soap:Body>","",$response);
           	 $response2 = str_replace("</soap:Body>","",$response1);			

            // convertingc to XML
            $parser =simplexml_load_string($response2);
            $retval=(string)$parser->{$method.'Response'}->{$method.'Result'};

		 $temp = json_decode($retval, TRUE);
           $log->write($temp);
		    $data = array();                    

           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();

            foreach ($it as $key=>$val)
            {	
                      $data[]=$val;
                     
            }

			 //$log->write($data);
			return $data;
	}


//get grower by card id
public function GetGrowerId($method, $data = array(), $is_json = true) 
	{
	$method="GetGrowerId";
	$log=new Log("Soapcurl-getGrowerId-".date('Y-m-d').".log"); 
	$log->write($data);    
		 $dataFromTheForm = "<card_number>".($data['Card_Serial_Number'])."</card_number>";
		$log->write($dataFromTheForm);
		 $response=$this->call($method,$data,true,$dataFromTheForm);
		 $temp = json_decode($response, TRUE);
           $log->write($temp);
		    $data = array();                    

           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();

            foreach ($it as $key=>$val)
            {	
                      $data['GROWER_ID']=$this->decryptRJ($val['GROWER_ID']);
                      $data['UNIT_ID']=$this->decryptRJ($val['UNIT_ID']);					                      
            }

			 $log->write($data);
			return $data;
	}

//end

//get card mpin
public function GetCheckCardMpin($method, $data = array(), $is_json = true) 
	{
	$method="CheckCardMpin";
	$log=new Log("Soapcurl-CheckCardMpin-".date('Y-m-d').".log"); 
	$log->write($data);    
		 $dataFromTheForm = "";
		$log->write($dataFromTheForm);
		 $response=$this->call($method,$data,true,$dataFromTheForm);
		 $log->write( $response);
		$response1 = str_replace("<soap:Body>","",$response);
           	 $response2 = str_replace("</soap:Body>","",$response1);			

            // convertingc to XML
            $parser =simplexml_load_string($response2);
            $retval=(string)$parser->{$method.'Response'}->{$method.'Result'};


		 $temp = json_decode($retval, TRUE);
           $log->write($temp);
		    $datafinal = array();                    
           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();
            foreach ($it as $key=>$val)
            {	
			$data=array();
                      $data['pin']=hexdec($val['CARD_PIN']);
                      $data['MOB']=($val['MOB']);
			 $datafinal[]=$data;
							                      
            }



			 $log->write($datafinal);
			return $datafinal;
	}
//

public function GetAuthentication($method, $data = array(), $is_json = true) 
	{
	$method="GetAuthentication";
	$log=new Log("Soapcurl-GetAuthentication-".date('Y-m-d').".log"); 
	$log->write('GetAuthentication called '); 
	$log->write($data);    
		 //$dataFromTheForm ='<grower><VILL>'.$this->encryptRJ($data['village_id']).'</VILL><ID>'.$this->encryptRJ($data['grower_id']).'</ID>'.'<MNUM>'.$this->encryptRJ($data['mobile']).'</MNUM></grower>'; // request data from the form
		$dataFromTheForm = " <card>
        <CARD_SERIAL_NUMBER>".$this->encryptRJ($data['Card_Serial_Number'])."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$this->encryptRJ($data['grower_id'])."</CARD_GROWER_ID>
        <CARD_UNIT>".$this->encryptRJ($data['CARD_UNIT'])."</CARD_UNIT>
        <DATE>".$this->encryptRJ("0")."</DATE>
        <USER>".$this->encryptRJ($data['USER'])."</USER>
        <CARD_PIN>".$this->encryptRJ("0")."</CARD_PIN>
        <CARD_STATUS>".$this->encryptRJ("0")."</CARD_STATUS>
        <CARD_CREATE_DATE>".$this->encryptRJ("0")."</CARD_CREATE_DATE>
        <CARD_VALIDITY_DATE>".$this->encryptRJ("0")."</CARD_VALIDITY_DATE>
        <CARD_ISSUE_DATE>".$this->encryptRJ("0")."</CARD_ISSUE_DATE>
        <CARD_QR_SRTING>".$this->encryptRJ("4012")."</CARD_QR_SRTING>
        <CARD_KYC_DOCUMENT>".$this->encryptRJ("0")."</CARD_KYC_DOCUMENT>
        <CARD_STATUS_DESC>".$this->encryptRJ("0")."</CARD_STATUS_DESC>
		<HS>".$this->encryptRJ("unnati")."</HS>
      </card>";
		 $response=$this->call($method,$data,true,$dataFromTheForm);
		 $log->write('response');
		 $temp = json_decode($response, TRUE);
           $log->write($temp);
		    $data = array();                    

           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();

            foreach ($it as $key=>$val)
            {	
                      $data['CARD_STATUS']= $this->decryptRJ($val['CARD_STATUS']);
                      $data['GROWER_ID']=$this->decryptRJ($val['GROWER_ID']);
                      $data['AMOUNT']=$this->decryptRJ($val['UNIT_ID']);
					  $vd=explode(" ",$this->decryptRJ($val['VD']));
					  $data['VDATE']=$vd[0];
                     
            }

			 $log->write($data);
			return $data;
	}
	
	public function GetGrower($method, $data = array(), $is_json = true) 
	{
	$method="GetGrowerByIDNUM";
	$log=new Log("Soapcurl-GetGrower-".date('Y-m-d').".log"); 
	$log->write($data);    
		 $dataFromTheForm ='<grower><VILL>'.$this->encryptRJ($data['village_id']).'</VILL><ID>'.$this->encryptRJ($data['grower_id']).'</ID>'.'<MNUM>'.$this->encryptRJ($data['mobile']).'</MNUM></grower>'; // request data from the form
		 $response=$this->call($method,$data,true,$dataFromTheForm);
		 $temp = json_decode($response, TRUE);
           $log->write($temp);
           $obj = new ArrayObject( $temp );
           $it = $obj->getIterator();
           $data = array();                    
            foreach ($it as $key=>$val)
            {	
                      $data['GROWER_NAME']= $this->decryptRJ($val['G_NAME']);
                      $data['FATHER_NAME']=$this->decryptRJ($val['G_FATHER']);
                      $data['GROWER_ID']=$this->decryptRJ($val['G_CODE']);
                      $data['UNIT_ID']='';
                      $data['MOB']=$this->decryptRJ($val['G_PHONENO1']);
                      $data['VILLAGE_CODE']=$this->decryptRJ($val['G_VILL']);
					  $data['VILLAGE_NAME']=$this->decryptRJ($val['V_NAME']);
                      $data['CARD_STATUS_DESC']='Card Request';
                      $data['SID']='';
                      $data['CARD_SERIAL_NUMBER']='';
                      $data['SITE']='';
            }
			return $data;
	}
//mob

public function GetGrowerCardMob($method, $data = array(), $is_json = true) 
	{
	$method="GetGrowerNo";
	$log=new Log("Card-grower-mob-".date('Y-m-d').".log"); 
	$log->write($data);    
		 $dataFromTheForm ='<gid>'.$this->encryptRJ($data['grower_id']).'</gid>'.'<uid>'.$this->encryptRJ($data['unit_id']).'</uid>'; // request data from the form
		 $response=$this->call($method,$data,true,$dataFromTheForm);
$log->write($dataFromTheForm);   
$log->write( $response);  
 $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
			 $log->write('response1 from dscl is : ');
		$log->write($response1);	
		$log->write($response2);	

            // convertingc to XML
            $parser =simplexml_load_string($response2);
			$log->write($parser);	
		$retval=(string)$parser->{$method.'Response'}->{$method.'Result'};
		$log->write($retval);	
		 $temp = json_decode($retval, TRUE);
		if(!empty($temp))
		{
           $log->write($temp);
           $obj = new ArrayObject( @$temp );
           $it = $obj->getIterator();
           $data = array();                    
     
            foreach ($it as $key=>$val)
            {	
                      		$data2['MOB']= $this->decryptRJ($val['MOB']);
		$data2['RYOT_NAME']= $this->decryptRJ($val['RYOT_NAME']);
		$data2['VNAME']= $this->decryptRJ($val['VNAME']);    
		    $data2['VILLAGE_CODE']= $this->decryptRJ($val['VILLAGE_CODE']);    
		$data2['FTH_HUS_NAME']= $this->decryptRJ($val['FTH_HUS_NAME']);  
                                        		
            }
		}
		$log->write( $data2);  
		return $data2;
	}
//
public function GetGrowerCard($method, $data = array(), $is_json = true) 
	{
		//$method="CardDataView";
	$log=new Log("Card-pin-".date('Y-m-d').".log"); 
	$log->write($data);    
		 $dataFromTheForm ='<gid>'.$this->encryptRJ($data['grower_id']).'</gid>'.'<unitid>'.$this->encryptRJ($data['unit_id']).'</unitid>'; // request data from the form
		 $response=$this->call($method,$data,true,$dataFromTheForm);
$log->write($dataFromTheForm);   
$log->write( $response);  
 $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
			 $log->write('response1 from dscl is : ');
		$log->write($response1);	
		$log->write($response2);	

            // convertingc to XML
            $parser =simplexml_load_string($response2);
			$log->write($parser);	
		$retval=(string)$parser->{$method.'Response'}->{$method.'Result'};
		$log->write($retval);	
		 $temp = json_decode($retval, TRUE);
		if(!empty($temp))
		{
           $log->write($temp);
           $obj = new ArrayObject( @$temp );
           $it = $obj->getIterator();
           $data = array();                    
            foreach ($it as $key=>$val)
            {	
                      		$data2['GROWER_NAME']= $this->decryptRJ($val['GROWER_NAME']);
                      
                      		$data2['GROWER_ID']=$this->decryptRJ($val['GROWER_ID']);
		$data2['VILLAGE_NAME']=$this->decryptRJ($val['VILLAGE_NAME']);

		$data2['PHOTOID_NUMBER']=$this->decryptRJ($val['PHOTOID_NUMBER']);
                   
                      		$data2['CARD_KYC_DOCUMENT']=$this->decryptRJ($val['CARD_KYC_DOCUMENT']);
                     		$data2['CARD_STATUS']=$this->decryptRJ($val['CARD_STATUS']);
            }
		}
		$log->write( $data2);  
		return $data2;
	}
	public function SendRequest($method, $data = array(), $is_json = true) 
	{
	
		$log=new Log("Soapcurl-SendRequest-".date('Y-m-d').".log"); 
		$log->write($data);    
		$dataFromTheForm= " <trans>
        <CARD_SERIAL_NUMBER>".$this->encryptRJ($data['Card_Serial_Number'])."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$this->encryptRJ($data['grower_id'])."</CARD_GROWER_ID>
        <NUM>".$this->encryptRJ($data['otp'])."</NUM>
        <MOB>".$this->encryptRJ($data['MOB'])."</MOB>
        <TrxType>".$this->encryptRJ($data['TX'])."</TrxType>
		<user_id>".$this->encryptRJ($data['USER'])."</user_id>
		<HS>".$this->encryptRJ("unnati")."</HS>
      </trans>"; 
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		return $response;
	}
	public function GetTrans($method, $data = array(), $is_json = true) 
	{
	
		$log=new Log("Soapcurl-GetTrans-".date('Y-m-d').".log"); 
		$log->write($data);    
		$dataFromTheForm= " <card>
        <CARD_SERIAL_NUMBER>".$this->encryptRJ($data['Card_Serial_Number'])."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$this->encryptRJ($data['grower_id'])."</CARD_GROWER_ID>
        <NUM>".$this->encryptRJ("0")."</NUM>
        <MOB>".$this->encryptRJ("0")."</MOB>
        <TrxType>".$this->encryptRJ($data['TX'])."</TrxType>
		 <user_id>".$this->encryptRJ($data['USER'])."</user_id>
		 <HS>".$this->encryptRJ("unnati")."</HS>
      </card>"; 
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		$card_status=json_decode($response);
		$log->write($card_status[0]);
		$fdata=$card_status[0];
		$log->write($fdata->CARD_STATUS);
		$log->write($this->decryptRJ(trim(($fdata->CARD_STATUS))));
		return ($this->decryptRJ(trim($fdata->CARD_STATUS))-50);
	}
	public function CardRequest($method, $data = array(), $is_json = true) 
	{
	
		$log=new Log("Soapcurl-CardRequest-".date('Y-m-d').".log"); 
		$log->write($data);    
		$dataFromTheForm=  "<card>
        <CARD_SERIAL_NUMBER>".$this->encryptRJ($data['CARD_SERIAL_NUMBER'])."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$this->encryptRJ($data['growerid'])."</CARD_GROWER_ID>
        <CARD_UNIT>".$this->encryptRJ($data['unitno'])."</CARD_UNIT>
        <DATE>".$this->encryptRJ($data['DATE'])."</DATE>
        <USER>".$this->encryptRJ($data['USER'])."</USER>
        <CARD_PIN>".$this->encryptRJ($data['CARD_PIN'])."</CARD_PIN>
        <CARD_STATUS>".$this->encryptRJ("1")."</CARD_STATUS>
        <CARD_CREATE_DATE>".$this->encryptRJ($data['CARD_CREATE_DATE'])."</CARD_CREATE_DATE>
        <CARD_VALIDITY_DATE>".$this->encryptRJ($data['CARD_VALIDITY_DATE'])."</CARD_VALIDITY_DATE>
        <CARD_ISSUE_DATE>".$this->encryptRJ($data['CARD_ISSUE_DATE'])."</CARD_ISSUE_DATE>
        <CARD_QR_SRTING>".$this->encryptRJ($data['CARD_QR_SRTING'])."</CARD_QR_SRTING>
        <CARD_KYC_DOCUMENT>".$this->encryptRJ($data['CARD_KYC_DOCUMENT'])."</CARD_KYC_DOCUMENT>
		<HS>".$this->encryptRJ("unnati")."</HS>
      </card>
      <grower>
        <ID>".$this->encryptRJ($data['growerid'])."</ID>
        <MNUM>".$this->encryptRJ($data['farmermob'])."</MNUM>
        <GROWER_NAME>".$this->encryptRJ($data['fname'])."</GROWER_NAME>
        <COMPANY_ID>".$this->encryptRJ($data['COMPANY_ID'])."</COMPANY_ID>
        <VILL>".$this->encryptRJ($data['village'])."</VILL>
        <FTH_HUS_NAME>".$this->encryptRJ($data['fathername'])."</FTH_HUS_NAME>
		<HS>".$this->encryptRJ("unnati")."</HS>
      </grower>";
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		return $response;
	}
public function CardStatus($method, $data = array(), $is_json = true) 
	{
	$log=new Log("Soapcurl-CardStatus-".date('Y-m-d').".log"); 
	$log->write($data);    
	if($data['CARD_STATUS']=="8"){
	$data['CARD_ISSUE_DATE']=date('Y-m-d');
	}else{$data['CARD_ISSUE_DATE']="0";}
	if(empty($data['CARD_PIN'])){
	$data['CARD_PIN']=0;
	}
	
		$dataFromTheForm="<card>
        <CARD_SERIAL_NUMBER>".$this->encryptRJ($data['CARD_SERIAL_NUMBER'])."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$this->encryptRJ($data['CARD_GROWER_ID'])."</CARD_GROWER_ID>
        <CARD_UNIT>".$this->encryptRJ($data['CARD_UNIT'])."</CARD_UNIT>
        <DATE>".$this->encryptRJ("0")."</DATE>
        <USER>".$this->encryptRJ($data['USER'])."</USER>
        <CARD_PIN>".$this->encryptRJ($data['CARD_PIN'])."</CARD_PIN>
        <CARD_STATUS>".$this->encryptRJ($data['CARD_STATUS'])."</CARD_STATUS>
		 <CARD_STATUS_DESC>".$this->encryptRJ($data['CARD_STATUS_DESC'])."</CARD_STATUS_DESC>
        <CARD_CREATE_DATE>".$this->encryptRJ($data['CARD_STATUS'])."</CARD_CREATE_DATE>
        <CARD_VALIDITY_DATE>".$this->encryptRJ($data['CARD_STATUS'])."</CARD_VALIDITY_DATE>
        <CARD_ISSUE_DATE>".$this->encryptRJ($data['CARD_ISSUE_DATE'])."</CARD_ISSUE_DATE>
        <CARD_QR_SRTING>".$this->encryptRJ($data['CARD_QR_SRTING'])."</CARD_QR_SRTING>
        <CARD_KYC_DOCUMENT>".$this->encryptRJ($data['CARD_STATUS'])."</CARD_KYC_DOCUMENT>
		<HS>".$this->encryptRJ("unnati")."</HS>
 <DELIVERED_BY>".$this->encryptRJ($data['USER_DEL_ID'])."</DELIVERED_BY>
        <DELIVERED_BY_NAME>".$this->encryptRJ($data['USER_DEL_NAME'])."</DELIVERED_BY_NAME>
        <DELIVERED_DATE>".$this->encryptRJ(date('Y-m-d'))."</DELIVERED_DATE>
        <DELIVERED_MOBILE>".$this->encryptRJ($data['USER_DEL_MOB'])."</DELIVERED_MOBILE>
        <DELIVERED_IMEI>".$this->encryptRJ($data['USER_DEL_IMEI'])."</DELIVERED_IMEI>

      </card>";
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	   $log->write('response from dscl is : ');
	    $log->write($response);
		 $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
			 $log->write('response1 from dscl is : ');
		$log->write($response1);	
		$log->write($response2);	

            // convertingc to XML
            $parser =simplexml_load_string($response2);
			$log->write($parser);	
		$retval=(string)$parser->{$method.'Response'}->{$method.'Result'};
		$log->write($retval);	
		return $retval;
	  
	}

public function GetCardStatus($method, $data = array(), $is_json = true) 
	{
		$log=new Log("Soapcurl-GetCardStatus-".date('Y-m-d').".log"); 	
		$log->write('GetCardStatus called');
		 $log->write($data);
		$dataFromTheForm="<card>
        <CARD_SERIAL_NUMBER>".$this->encryptRJ($data['CARD_SERIAL_NUMBER'])."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$this->encryptRJ($data['CARD_GROWER_ID'])."</CARD_GROWER_ID>
        <CARD_UNIT>".$this->encryptRJ($data['CARD_UNIT'])."</CARD_UNIT>
        <DATE>".$this->encryptRJ("0")."</DATE>
        <USER>".$this->encryptRJ($data['USER'])."</USER>
        <CARD_PIN>".$this->encryptRJ("0")."</CARD_PIN>
        <CARD_STATUS>".$this->encryptRJ("0")."</CARD_STATUS>
		 <CARD_STATUS_DESC>".$this->encryptRJ("0")."</CARD_STATUS_DESC>
        <CARD_CREATE_DATE>".$this->encryptRJ("0")."</CARD_CREATE_DATE>
        <CARD_VALIDITY_DATE>".$this->encryptRJ("0")."</CARD_VALIDITY_DATE>
        <CARD_ISSUE_DATE>".$this->encryptRJ("0")."</CARD_ISSUE_DATE>
        <CARD_QR_SRTING>".$this->encryptRJ("0")."</CARD_QR_SRTING>
        <CARD_KYC_DOCUMENT>".$this->encryptRJ("0")."</CARD_KYC_DOCUMENT>
		<HS>".$this->encryptRJ("unnati")."</HS>
      </card>";
	  
	   $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	   $log->write('data from server');
	    $log->write($response);
		$card_status=json_decode($response);
		$log->write($card_status[0]);
		$fdata=$card_status[0];
		$log->write($fdata->CARD_STATUS);
		$rtval=$this->decryptRJ(($fdata->CARD_STATUS));
		$log->write($rtval);
		return $rtval;
	  
	}

	
	public function UpdateDelivery($method, $data = array(), $is_json = true) 
	{
		if(!empty($data['payment_method']))
		{
			$log=new Log("Soapcurl-UpdateDelivery-"."-".str_replace(' ','-',$data['payment_method'])."-".date('Y-m-d').".log"); 
		}
		else
		{
			$log=new Log("Soapcurl-UpdateDelivery-".date('Y-m-d').".log");
		}
		$total = 0.0;
		$tagged=0.0;
		$log->write('UpdateDelivery called');  
		$log->write($data);    
		//$log->write($data['order_total']);
		//$this->load->library('email');
		//$email=new email($this->registry);
		//$email->sendmail('Card Update Delivery-'.$data['store_name'],$data);
		if (isset($data['order_total'])) 
		{		
      		foreach ($data['order_total'] as $order_total) 
			{
				
				if($order_total['code']=='total'){
      				$total += $order_total['value'];
				}
			
			}			
			//$total += $order_total['value'];
		}
		$log->write('total'); 
		$log->write($total); 
		
		if($data['payment_method']=='Tagged Cash' && isset($data['amtcash']) && (!empty($data['amtcash'])))
		{
			$tagged=(float)$data['tagged_amt'];//((float)$total-(float)$data['amtcash']);			
		}
		else if($data['payment_method']=='Tagged Cash Subsidy')
		{
			$tagged=(float)((float)$data['tagged_amt']+(float)$data['sub']);			
		}
		else if($data['payment_method']=='Tagged')
		{
			$tagged=(float)$total;			
		}
		if($data['payment_method']=='Subsidy')
		{
			$data['amtcash']=(float)$data['subsidy'];
		}
		if($data['payment_method']=='Tagged Subsidy')
		{
			$tagged=((float)$total-(float)$data['sub']);
			if($tagged<0)
			{$tagged=0;}
		}	
		if($data['payment_method']=='Cash Subsidy')
		{
			$tagged=0;
			$data['payment_method']="Cash Subsidy";
		}	
		if(empty($data['sub']))
		{
		$data['sub']=0.0;
		}
		//$data['invoice_no']=0;
		if(empty($data['amtcash']))
		{
			$data['amtcash']=0.0;
		}
		if(empty($data['subsidy_coupon']))
		{
			$data['subsidy_coupon']='0'; 
		}
		
		//$data['vill']="0";
		$mcrypt=new MCrypt();
		$log->write($tagged);
		$tagged=round($tagged,2);
		$log->write($tagged); 

		$farmer=explode('-',$data['firstname']);
		if((!empty($data['date_added'])) && ($data['payment_method']=='Subsidy'))
		{
			$date_added=date('Y-m-d',strtotime($data['date_added']));
		}
		else
		{
			$date_added=date('Y-m-d');
		}
		$orddata="<order>
			<order_trans_id>".$this->encryptRJ($data['order_trans_id'])."</order_trans_id>
			<order_id>".$this->encryptRJ($data['oid'])."</order_id>
			<invoice_no>".$this->encryptRJ($data['invoice_no'])."</invoice_no>
			<tagged>".$this->encryptRJ($tagged)."</tagged>
			<subsidy>".$this->encryptRJ($data['sub'])."</subsidy>
			<cash>".$this->encryptRJ($data['amtcash'])."</cash>
			<total>".$this->encryptRJ($tagged)."</total>
			<comment>".$this->encryptRJ($data['otpu'])."</comment>
			<payment_method>".$this->encryptRJ($data['payment_method'])."</payment_method>
			<telephone>".$this->encryptRJ(($data['telephone']))."</telephone>
			<lastname>".$this->encryptRJ(@$farmer[1])."</lastname>
			<firstname>".$this->encryptRJ($farmer[0])."</firstname>
			<card_no>".$this->encryptRJ($data['Card_Serial_Number'])."</card_no>
			<user_id>".$this->encryptRJ($data['user_id'])."</user_id>
			<customer_id>".$this->encryptRJ($data['grower_id'])."</customer_id>
			<store_name>".$this->encryptRJ($data['store_name'])."</store_name>
			<store_id>".$this->encryptRJ($data['store_id'])."</store_id>
			<invoice_prefix>".$this->encryptRJ($data['invoice_prefix'])."</invoice_prefix>
			<order_status_id>".$this->encryptRJ($data['order_status_id'])."</order_status_id>
			<date_added>".$this->encryptRJ($date_added)."</date_added>
			<vill>".$this->encryptRJ($data['vill'])."</vill>
			<village_name>".$this->encryptRJ($data['villname'])."</village_name>
			<unitid>".$this->encryptRJ($data['CARD_UNIT'])."</unitid>
			<HS>".$this->encryptRJ("unnati")."</HS>
			<REQ_ORDER_ID>".$this->encryptRJ($data['subsidy_coupon'])."</REQ_ORDER_ID>
		</order>";
		$productdata="";
		$data['TAGGEDRATIO']=number_format((float)$data['TAGGEDRATIO'], 12, '.', '');
		//$total_tagged_ratio=(float)((float)$data['tagged_amt']/((float)$total-(float)$data['sub']));
		$log->write('TAGGEDRATIO');
		$log->write($data['TAGGEDRATIO']);
		//$data['TAGGEDRATIO']=$total_tagged_ratio;	
		foreach ($data['order_product'] as $order_product) 
		{   
			$reward_v=0;
			$reward_per=0;
			$log->write('order_product quantity'); 
			$log->write($order_product['quantity']); 
			$actqnty=$order_product['quantity'];
			foreach ($data['order_product_subsidy'] as $order_product_s)
			{  
				$log->write('in inner loop');
				$log->write($order_product['product_id']); 
				$log->write($order_product_s['product_id']); 
		
				if($order_product['product_id']==$order_product_s['product_id'])
				{
					$reward_v=$order_product_s['reward'];
					$reward_per=$order_product_s['discount_value'];

					$log->write($reward_v);
					$x1 = $reward_v - floor($reward_v);
					$log->write($x1);
					$y=number_format((float)$x1, 2, '.', '');  
					$log->write($y);
					if($y<0.09)
					{
					  $reward_v=intval($reward_v);
					}
					$log->write($reward_v);
					$log->write('matched');
					if(($order_product_s['discount_value'])!=100)
					{
						$order_product['quantity']=$order_product['quantity'];//$order_product['quantity']-(($order_product['quantity']*$order_product_s['discount_value'])/100);
					}
					else
					{
						$order_product['quantity']=$order_product['quantity'];
					}
					break;
				}
				else
				{
					$reward_v=0;
					$reward_per=0;
					$log->write('not matched'); 
				}
			}
			$order_product['quantity']=number_format((float)$order_product['quantity'], 8, '.', '');
			$log->write('updated quantity'); 
			$log->write($order_product['quantity']); 
		
			$log->write($reward_v); 
			$log->write($reward_per); 
			$order_product_quantity=($order_product['quantity']*$data['TAGGEDRATIO']);
			$order_product_quantity=number_format((float)$order_product_quantity, 8, '.', '');
		
			$order_product['price']=number_format((float)$order_product['price'], 2, '.', '');
			$order_product['tax']=number_format((float)$order_product['tax'], 2, '.', '');
		
			$log->write('order_product_quantity');
			$log->write($order_product_quantity);
			$log->write('product total : ');
			//number_format((float)(($order_product['price']*$order_product_quantity)+($order_product['tax']*$order_product_quantity)), 2, '.', '')
			$log->write(number_format((float)(($order_product['price']*$order_product_quantity)+($order_product['tax']*$order_product_quantity)), 2, '.','')); 
			$log->write('order_product_cash');
			$log->write($data['amtcash']);
			$log->write((float)($data['amtcash']/$total));
			$log->write($actqnty);
			$log->write(((float)$order_product['price']+(float)$order_product['tax']));
			$log->write((((float)($data['amtcash']/$total)*$actqnty)*((float)$order_product['price']+(float)$order_product['tax'])));
			$lcash=0;
			if(!empty($data['amtcash']))
			{	
				$lcash=	number_format((((float)($data['amtcash']/$total)*$actqnty)*((float)$order_product['price']+(float)$order_product['tax'])),2,'.','');
			}
			$log->write($lcash);
			if($data['payment_method']=='Tagged Cash')
			{
				$lcash=0;
			}
			if($data['old_data']=='yes')
			{
				$log->write('in if old_data');
				$lcash=number_format((float)(($order_product['price']*$order_product_quantity)+($order_product['tax']*$order_product_quantity)), 2, '.', '')-$reward_v;
				
			}
			$log->write($lcash);
			$log->write($order_product);
			// echo "{$key} => {$value} ";    
			$productdata.="<OrderProduct>
				<order_id>".$this->encryptRJ($data['oid'])."</order_id>
				<product_id>".$this->encryptRJ(100000+$order_product['product_id'])."</product_id>
				<name>".$this->encryptRJ($order_product['name'])."</name>";
				if($data['payment_method']=='Tagged Cash Subsidy')
				{ 
					$productdata.="<quantity>".$this->encryptRJ($actqnty)."</quantity>";
				}
				else
				{ 
					$productdata.="<quantity>".$this->encryptRJ($order_product_quantity)."</quantity>";		  
				}		  		  
			$productdata.="<tquantity>".$this->encryptRJ($order_product_quantity)."</tquantity>
				<price>".$this->encryptRJ(round($order_product['price'],2))."</price>
				<total>".$this->encryptRJ(number_format((float)(($order_product['price']*$order_product_quantity)+($order_product['tax']*$order_product_quantity)), 2, '.', ''))."</total>
				<cash>".$this->encryptRJ($lcash)."</cash>
				<tax>".$this->encryptRJ(round($order_product['tax'],2))."</tax> 
				<reward>".$this->encryptRJ(round($reward_v,2))."</reward>
				<reward_per>".$this->encryptRJ($reward_per)."</reward_per> 
				<HS>".$this->encryptRJ("unnati")."</HS>
			</OrderProduct>";
		}
		$ordproduct="<orderproduct>".$productdata."</orderproduct>";
		$dataFromTheForm=$orddata.$ordproduct;
		$log->write('dataFromTheForm');
		$log->write($dataFromTheForm);
		$response=$this->call($method,$data,true,$dataFromTheForm);
		$log->write('response');
	    $log->write($response);
		return $response;
	}
	
	/*
	public function UpdateDelivery($method, $data = array(), $is_json = true) 
	{
		$log=new Log("Soapcurl-UpdateDelivery-".date('Y-m-d').".log"); 
		$total = 0.0;
		$tagged=0.0;
		$log->write($data);    
		$log->write($data['order_total']);
		//$this->load->library('email');
		//$email=new email($this->registry);
		//$email->sendmail('Card Update Delivery-'.$data['store_name'],$data);
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {
				
				if($order_total['code']=='total'){
      				$total += $order_total['value'];
				}
			
			}			
			//$total += $order_total['value'];
		}
		$log->write('total'); 
		$log->write($total); 
		if($data['payment_method']=='Tagged Cash' && isset($data['amtcash']) && (!empty($data['amtcash'])))
		{
			$tagged=(float)$data['tagged_amt'];//((float)$total-(float)$data['amtcash']);			
		}
		else if($data['payment_method']=='Tagged Cash Subsidy')
		{
			$tagged=(float)((float)$data['tagged_amt']+(float)$data['sub']);			
		}
		else if($data['payment_method']=='Tagged')
		{
			$tagged=(float)$total;			
		}
		if($data['payment_method']=='Subsidy')
		{
			$data['amtcash']=(float)$data['subsidy'];
		}
		if($data['payment_method']=='Tagged Subsidy')
		{
			$tagged=((float)$total-(float)$data['sub']);
		}	
		if($data['payment_method']=='Cash Subsidy')
		{
			$tagged=0;
			$data['payment_method']="Cash Subsidy";
		}	
		if(empty($data['sub']))
		{
		$data['sub']=0.0;
		}
		//$data['invoice_no']=0;
		if(empty($data['amtcash']))
		{
			$data['amtcash']=0.0;
		}
		if(empty($data['subsidy_coupon']))
		{
			$data['subsidy_coupon']='0'; 
		}
		
		//$data['vill']="0";
		$mcrypt=new MCrypt();
		$log->write($tagged);
		$tagged=round($tagged,2);
		$log->write($tagged); 

		$farmer=explode('-',$data['firstname']);
		
		$orddata="<order>
        <order_trans_id>".$this->encryptRJ($data['order_trans_id'])."</order_trans_id>
        <order_id>".$this->encryptRJ($data['oid'])."</order_id>
        <invoice_no>".$this->encryptRJ($data['invoice_no'])."</invoice_no>
        <tagged>".$this->encryptRJ($tagged)."</tagged>
        <subsidy>".$this->encryptRJ($data['sub'])."</subsidy>
        <cash>".$this->encryptRJ($data['amtcash'])."</cash>
        <total>".$this->encryptRJ($tagged)."</total>
        <comment>".$this->encryptRJ($data['otpu'])."</comment>
        <payment_method>".$this->encryptRJ($data['payment_method'])."</payment_method>
        <telephone>".$this->encryptRJ(($data['telephone']))."</telephone>
        <lastname>".$this->encryptRJ($farmer[1])."</lastname>
        <firstname>".$this->encryptRJ($farmer[0])."</firstname>
        <card_no>".$this->encryptRJ($data['Card_Serial_Number'])."</card_no>
        <user_id>".$this->encryptRJ($data['user_id'])."</user_id>
        <customer_id>".$this->encryptRJ($data['grower_id'])."</customer_id>
        <store_name>".$this->encryptRJ($data['store_name'])."</store_name>
        <store_id>".$this->encryptRJ($data['store_id'])."</store_id>
        <invoice_prefix>".$this->encryptRJ($data['invoice_prefix'])."</invoice_prefix>
        <order_status_id>".$this->encryptRJ($data['order_status_id'])."</order_status_id>
        <date_added>".$this->encryptRJ(date('Y-m-d'))."</date_added>
        <vill>".$this->encryptRJ($data['vill'])."</vill>
	<village_name>".$this->encryptRJ($data['villname'])."</village_name>
		<unitid>".$this->encryptRJ($data['CARD_UNIT'])."</unitid>
		<HS>".$this->encryptRJ("unnati")."</HS>
		<REQ_ORDER_ID>".$this->encryptRJ($data['subsidy_coupon'])."</REQ_ORDER_ID>
      </order>";
	  $data['TAGGEDRATIO']=number_format((float)$data['TAGGEDRATIO'], 8, '.', '');
	  $log->write('TAGGEDRATIO');
		$log->write($data['TAGGEDRATIO']);
	  $productdata="";
	  foreach ($data['order_product'] as $order_product) 
	  {   
		$reward_v=0;
		$reward_per=0;
		foreach ($data['order_product_subsidy'] as $order_product_s)
		{  
			$log->write('in inner loop');
			$log->write($order_product['product_id']); 
			$log->write($order_product_s['product_id']); 
		$log->write('order_product quantity'); 
		$log->write($order_product['quantity']); 
		$actqnty=$order_product['quantity'];
			if($order_product['product_id']==$order_product_s['product_id'])
			{
				$reward_v=$order_product_s['reward'];
				$reward_per=$order_product_s['discount_value'];
				$log->write('matched'); 
				$order_product['quantity']=$order_product['quantity']-(($order_product['quantity']*$order_product_s['discount_value'])/100);
				break;
			}
			else
			{
				$reward_v=0;
				$reward_per=0;
				$log->write('not matched'); 
			}
		}
		$order_product['quantity']=number_format((float)$order_product['quantity'], 8, '.', '');
		$log->write('updated quantity'); 
		$log->write($order_product['quantity']);
		
		$log->write($reward_v); 
		$log->write($reward_per); 
		
		$order_product_quantity=round(($order_product['quantity']*$data['TAGGEDRATIO']),8);
		$order_product_quantity=number_format((float)$order_product_quantity, 8, '.', '');;
		
		$log->write('order_product_quantity');
		$log->write($order_product_quantity);
		$log->write(round(((round($order_product['price'],4)*$order_product_quantity)+($order_product['tax']*$order_product_quantity)),2));
   
   // echo "{$key} => {$value} ";    
	  $productdata.="<OrderProduct>
	  <order_id>".$this->encryptRJ($data['oid'])."</order_id>
          <product_id>".$this->encryptRJ(100000+$order_product['product_id'])."</product_id>
          <name>".$this->encryptRJ($order_product['name'])."</name>";
            if($data['payment_method']=='Tagged Cash Subsidy')
		{ $productdata.="<quantity>".$this->encryptRJ($actqnty)."</quantity>";}
		else{
		  $productdata.="<quantity>".$this->encryptRJ($order_product_quantity)."</quantity>";		  
		  }		  		  
          $productdata.="<tquantity>".$this->encryptRJ($order_product_quantity)."</tquantity>
          <price>".$this->encryptRJ(round($order_product['price'],2))."</price>
<total>".$this->encryptRJ( round(((round($order_product['price'],2)*$order_product_quantity)+($order_product['tax']*$order_product_quantity)),2))."</total>
			<cash>".$this->encryptRJ(number_format((((float)($data['amtcash']/$total)*$actqnty)*((float)$order_product['price']+(float)$order_product['tax'])),2,'.',''))."</cash>
			<tax>".$this->encryptRJ(round($order_product['tax'],2))."</tax> 
			<reward>".$this->encryptRJ(round($reward_v,2))."</reward>
			<reward_per>".$this->encryptRJ($reward_per)."</reward_per> 
			<HS>".$this->encryptRJ("unnati")."</HS>
	  </OrderProduct>";
	  }
	  $ordproduct="<orderproduct>".$productdata."</orderproduct>";
		$dataFromTheForm=$orddata.$ordproduct;
		 $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		return $response;
	}*/
	
	public function UpdateStatus($method, $data = array(), $is_json = true) 
	{
		if(!empty($data['payment_method']))
		{
			$log=new Log("Soapcurl-UpdateStatus-"."-".str_replace(' ','-',$data['payment_method'])."-".date('Y-m-d').".log"); 
		}
		else
		{
			$log=new Log("Soapcurl-UpdateStatus-".date('Y-m-d').".log");
		}
			
		$log->write('UpdateStatus called');  
		$log->write($data);    
		$log->write($data['order_total']);			
		$mcrypt=new MCrypt();
		$data['order_status_id']="5";
		$orddata="<ord>
        <order_trans_id>".$this->encryptRJ($data['oid'])."</order_trans_id>
        <order_id>".$this->encryptRJ($data['billno'])."</order_id>
        <invoice_no>".$this->encryptRJ($data['billnofordscl'])."</invoice_no>
        <tagged>".$this->encryptRJ($tagged)."</tagged>
        <subsidy>".$this->encryptRJ($data['sub'])."</subsidy>
        <cash>".$this->encryptRJ($data['amtcash'])."</cash>
        <total>".$this->encryptRJ($total)."</total>
        <comment>".$this->encryptRJ($data['comment'])."</comment>
        <payment_method>".$this->encryptRJ($data['payment_method'])."</payment_method>
        <telephone>".$this->encryptRJ($mcrypt->decrypt($data['telephone']))."</telephone>
        <lastname>".$this->encryptRJ($data['lastname'])."</lastname>
        <firstname>".$this->encryptRJ($data['firstname'])."</firstname>
        <card_no>".$this->encryptRJ($data['Card_Serial_Number'])."</card_no>
        <user_id>".$this->encryptRJ($data['user_id'])."</user_id>
        <customer_id>".$this->encryptRJ($data['grower_id'])."</customer_id>
        <store_name>".$this->encryptRJ($data['store_name'])."</store_name> 
        <store_id>".$this->encryptRJ($data['store_id'])."</store_id>
        <invoice_prefix>".$this->encryptRJ($data['invoice_prefix'])."</invoice_prefix>
        <order_status_id>".$this->encryptRJ($data['order_status_id'])."</order_status_id>
        <date_added>".$this->encryptRJ(date('Y-m-d'))."</date_added>
        <vill>".$this->encryptRJ($data['vill'])."</vill>
		<unitid>".$this->encryptRJ($data['CARD_UNIT'])."</unitid>
		<HS>".$this->encryptRJ("unnati")."</HS>
		<REQ_ORDER_ID>".$this->encryptRJ($data['subsidy_coupon'])."</REQ_ORDER_ID>
      </ord>";
	  
		$dataFromTheForm=$orddata;
		$log->write($dataFromTheForm);
		$response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		$response1 = str_replace("<soap:Body>","",$response);
        $response2 = str_replace("</soap:Body>","",$response1);
		$log->write('response1 from dscl for '.$data['billno'].' is : ');
		$log->write($response1);	
		$log->write($response2);	
		// convertingc to XML
        $parser =simplexml_load_string($response2);
		$log->write($parser);	
		$retval=(string)$parser->{$method.'Response'}->{$method.'Result'};
		$log->write($retval);	
		return $retval;
	}
public function CardReissue($method, $data = array(), $is_json = true) 
	{
		$log=new Log("Soapcurl-CardReissue-".date('Y-m-d').".log"); 
		//CARD_KYC_DOCUMENT=old_card_serial_number
		//CARD_PIN=card_issue_id
		$log->write($data);    					
		$mcrypt=new MCrypt();		
		$orddata="<card>
        <CARD_SERIAL_NUMBER>".$this->encryptRJ($data['CARD_SERIAL_NUMBER'])."</CARD_SERIAL_NUMBER>
        <CARD_GROWER_ID>".$this->encryptRJ($data['CARD_GROWER_ID'])."</CARD_GROWER_ID>
        <CARD_UNIT>".$this->encryptRJ($data['CARD_UNIT'])."</CARD_UNIT>
        <DATE>".$this->encryptRJ("0")."</DATE>
        <USER>".$this->encryptRJ($data['USER'])."</USER>
        <CARD_PIN>".$this->encryptRJ($data['CARD_PIN'])."</CARD_PIN>
        <CARD_STATUS>".$this->encryptRJ($data['CARD_STATUS'])."</CARD_STATUS>
        <CARD_CREATE_DATE>".$this->encryptRJ("0")."</CARD_CREATE_DATE>
        <CARD_VALIDITY_DATE>".$this->encryptRJ("0")."</CARD_VALIDITY_DATE>
        <CARD_ISSUE_DATE>".$this->encryptRJ("0")."</CARD_ISSUE_DATE>
        <CARD_QR_SRTING>".$this->encryptRJ("0")."</CARD_QR_SRTING>
        <CARD_KYC_DOCUMENT>".$this->encryptRJ($data['CARD_KYC_DOCUMENT'])."</CARD_KYC_DOCUMENT>
        <CARD_STATUS_DESC>".$this->encryptRJ($data['CARD_STATUS_DESC'])."</CARD_STATUS_DESC>
        <HS>".$this->encryptRJ("unnati")."</HS>
        <HSA>".$this->encryptRJ($data['CARD_PIN'])."</HSA>
      </card>";
	  
		$dataFromTheForm=$orddata;
		 $log->write($dataFromTheForm);
	   $response=$this->call($method,$data,true,$dataFromTheForm);
	    $log->write($response);
		return $response;
	}
	
	public function call($method, $data = array(), $is_json = true,$dataFromTheForm ) 
	{
        $log=new Log("Soap-Dscl-Curl-".date('Y-m-d').".log"); 
		if ($is_json) 
		{
			$arg_string = json_encode($data);
		} 
		else 
		{
			$arg_string = $data;
		}

		//xml post
        $log->write($data);     
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($ch, CURLOPT_ENCODING, '');
			$response = curl_exec($ch);
			if(empty($response))
			{
				$log->write("Error for method ".$method);
				$log->write(curl_error($ch));
			} 
			curl_close($ch);
			$log->write('actual response from the dscl web service is for method '.$method); 
			$log->write($response);
			$str_remove ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><'.$func_name.'Response xmlns="http://aksha/app/" /></soap:Body></soap:Envelope>';
			$response1 = str_replace($str_remove,"",$response);
            $log->write($response1);
            return $response1;
    }





//decrypt .net value 
function decryptRJ($encrypted)
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

function encryptRJ($encrypted)
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
  
}  
?>