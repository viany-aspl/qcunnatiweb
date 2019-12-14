<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class sms {
public function __construct($registry) {
                $this->config = $registry->get('config');
		$this->db = $registry->get('db');
                //$this->request = $registry->get('request');
		//$this->session = $registry->get('session');
}

public function sendsms($mobile,$message,$customer_info)
{
    $response ="";
    if(isset($mobile)&&isset($message))
    {
		$log=new Log("smshits-".date('y-m-d').".log");
$log->write("In sms lib");   
		$log->write($customer_info);
		$operator_info=array();
		$operator_info=$this->getoperator('1');
		$log->write($operator_info);
                $api_info=array();
if($operator_info['OPERATOR']=='KIT19'){
                $api_info["username"]=$operator_info['USERNAME'];
                $api_info["password"]=$operator_info['PWD'];		
                $api_info["sender"]=$operator_info['DISPLAYNAME']; 
		$api_info["to"]=$mobile;

		}else{
                        $api_info["User"]=$operator_info['USERNAME'];
                	$api_info["passwd"]=$operator_info['PWD'];
                	$api_info["sid"]=$operator_info['DISPLAYNAME']; 
			$api_info["mobilenumber"]=$mobile;
		}
		
		 $api_info["to"]=$mobile;
		$api_info["priority"]="1";
		$api_info["dnd"]="1";
		$api_info["unicode"]="0";

		if($message=='2')
		{
			$api_info["unicode"]="1";						
			$api_info["DR"]="Y";
			$api_info["Mtype"]="OL";
			$api_info["message"]=str_replace('*',ceil($customer_info['total']),($this->getsms($message)["MESSAGE"]));
			if($operator_info['OPERATOR']=='KIT19'){
                        $api_info["message"]=$api_info["message"];}
                        else{$api_info["message"]= str_replace('%u', '',$this->utf8_to_unicode($api_info["message"]));	}	
			
		}
		else if($message=='1')
		{
			$api_info["unicode"]="1";
			$api_info["DR"]="Y";
			$api_info["Mtype"]="OL";
			$api_info["message"]=str_replace('*',($customer_info['card']),($this->getsms($message)["MESSAGE"]));
			if($operator_info['OPERATOR']=='KIT19'){
                        $api_info["message"]=$api_info["message"];}
                        else{$api_info["message"]= str_replace('%u', '',$this->utf8_to_unicode($api_info["message"]));	}	
			
		}
		else if($message=='5')
		{
			$api_info["message"]=str_replace('*',($customer_info['ttp']),($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['rqid']),$api_info["message"]);			
		}
		else if($message=='4')
		{

			$number =  $customer_info['username'];
			$masked =  str_pad(substr($number, -4), strlen($number), 'X', STR_PAD_LEFT);
			$masked = " for uid " .$masked;
			$api_info["message"]=str_replace('*',($customer_info['pass']).$masked ,($this->getsms($message)["MESSAGE"]));
			
		}
		else if($message=='6')
		{			
			$api_info["message"]=str_replace('*',"farmer",($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['oid']),$api_info["message"]);	
			$api_info["unicode"]="1";
			$api_info["DR"]="Y";
			$api_info["Mtype"]="OL";
			
			if($operator_info['OPERATOR']=='KIT19'){
                        $api_info["message"]=$api_info["message"];}
                        else{$api_info["message"]= str_replace('%u', '',$this->utf8_to_unicode($api_info["message"]));	}		
		}	
		else if($message=='7')
		{
			//sms recharge
			$api_info["unicode"]="1";
			$api_info["DR"]="Y";
			$api_info["Mtype"]="OL";
			if($operator_info['OPERATOR']=='KIT19'){
                        $api_info["message"]=$this->getsms($message)["MESSAGE"];}
                        else{$api_info["message"]= str_replace('%u', '',$this->utf8_to_unicode($this->getsms($message)["MESSAGE"]));	}	

		}
		else if($message=='8')
		{	
			$api_info["unicode"]="1";		
			$api_info["message"]=str_replace('*',"farmer",($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['oid']),$api_info["message"]);			
		}
		else if($message=='9')
		{
			//sms recharge
			$api_info["unicode"]="1";
			$api_info["DR"]="Y";
			$api_info["Mtype"]="OL";
			if($operator_info['OPERATOR']=='KIT19'){
                        $api_info["message"]=$this->getsms($message)["MESSAGE"];}
                        else{$api_info["message"]= str_replace('%u', '',$this->utf8_to_unicode($this->getsms($message)["MESSAGE"]));	}

		}
		else if($message=='10')
		{
			//sms recharge
			$api_info["message"]=str_replace('*',($customer_info['otp']),($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['order_id']),($api_info["message"]));
		}
		else if($message=='11')
		{
			//sms recharge	
			$api_info["message"]=str_replace('*',($customer_info['otp']),($this->getsms($message)["MESSAGE"]));
			
		}
			
		else if($message=='12')
		{
			//sms recharge
			$api_info["DR"]="Y";
			$api_info["Mtype"]="OL";
			//$api_info["message"]= $customer_info["otp"];	
			$api_info["unicode"]="1";
			
			$api_info["message"]=str_replace('*',$customer_info["coupon"],$this->getsms($message)["MESSAGE"]);
			$log->write($api_info["message"]);	
			//$api_info["message"]=str_replace('@',$customer_info["coupon_discount"],$api_info["message"]); 
			//$log->write($api_info["message"]); 	
			if($operator_info['OPERATOR']=='KIT19'){
                        $api_info["message"]=$api_info["message"];}
                        else{$api_info["message"]= str_replace('%u', '',$this->utf8_to_unicode($api_info["message"]));	}
			$log->write($api_info["message"]);	
		}
		else if($message=='13') 
		{
			//sms recharge
			$api_info["unicode"]="1";
			$api_info["DR"]="Y";
			$api_info["Mtype"]="OL";
			$api_info["message"]=$api_info["message"];// str_replace('%u', '',$this->utf8_to_unicode($this->getsms($message)["MESSAGE"]));	
			if($operator_info['OPERATOR']=='KIT19'){
                        $api_info["message"]=$this->getsms($message)["MESSAGE"];}
                        else{$api_info["message"]= str_replace('%u', '',$this->utf8_to_unicode($this->getsms($message)["MESSAGE"]));	}

		}
		else if($message=='14')//////////for card pin generation 
		{
			//sms recharge
			//$api_info["DR"]="Y";
			//$api_info["Mtype"]="OL";
			//$api_info["message"]= $customer_info["otp"];	
			$api_info["message"]=str_replace('*',($customer_info['pin']),($this->getsms($message)["MESSAGE"]));
			
		}
		else if($message=='15')//////////for card pin change
		{
			//sms recharge
			//$api_info["DR"]="Y";
			//$api_info["Mtype"]="OL";
			//$api_info["message"]= $customer_info["otp"];	
			$api_info["message"]=str_replace('*',($customer_info['pin']),($this->getsms($message)["MESSAGE"]));
			
		}
		else if($message=='16')//////////for card delivery code
		{
			//sms recharge
			//$api_info["DR"]="Y";
			//$api_info["Mtype"]="OL";
			//$api_info["message"]= $customer_info["otp"];	
			$api_info["message"]=str_replace('*',($customer_info['otp']),($this->getsms($message)["MESSAGE"]));
			
		}
		else if($message=='19')//////////for otp sendtorunner
		{
			
			$api_info["message"]=str_replace('*',($customer_info['otp']),($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['amount']),($api_info["message"]));
			$api_info["message"]=str_replace('#',($customer_info['storename']),($api_info["message"]));
			$api_info["message"]=str_replace('$',($customer_info['username']),($api_info["message"]));
		} 
			else if($message=='20')//////////for otp sendtorunner for tagged
		{
			
			$api_info["message"]=str_replace('@',($customer_info['otp']),($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('*',($customer_info['amount']),($api_info["message"]));
			$api_info["message"]=str_replace('#',($customer_info['storename']),($api_info["message"]));
			$api_info["message"]=str_replace('$',($customer_info['username']),($api_info["message"]));
			$api_info["message"]=str_replace('&',($customer_info['letter_number']),($api_info["message"]));
			//You have received Tagged Amount Rs * from store # and Store Executive $ with letter number &. Kindly share the OTP @ with him to accept the amount.
		}	
		else if($message=='22')//////////for otp sendtosubuser
		{
			
			
			$api_info["message"]=str_replace('*',($customer_info['trans_id']." ".$customer_info['products']),($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('#',($customer_info['otp']),($api_info["message"]));
		
		} 
		else if($message=='23')//////////for send outstanding sms to partner
		{
			
			
			$api_info["message"]=str_replace('*',date('d-M-Y'),($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['currentcredit']),($api_info["message"]));
		
		} 
		else if($message=='24')//////////for send Current Cash In-Hand for own Store In-Charge
		{
			
			$api_info["message"]=str_replace('*',$customer_info['user_cash'],($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('#',($customer_info['name']),($api_info["message"]));
		
		}
		else if($message=='25')
		{
			$api_info["message"]=str_replace('*',($customer_info['mpin']),($this->getsms($message)["MESSAGE"]));
						
		}
		else if($message=='26')//////////for reset pin of dscl card
		{
			
			$api_info["message"]=str_replace('*',$customer_info['CARD_SERIAL_NUMBER'],($this->getsms($message)["MESSAGE"]));
			$api_info["message"]=str_replace('@',($customer_info['PIN']),($api_info["message"]));
		
		}
		else if($message=='27')
		{
			$api_info["message"]=str_replace('*',($customer_info['otp']),($this->getsms($message)["MESSAGE"]));
						
		}else if($message=='30')//////////for send Current Cash In-Hand for own Store In-Charge
                {   
                   $api_info["message"]=str_replace('*',$customer_info['invoice_no'],($this->getsms($message)["MESSAGE"]));  
                }
		else{
                		$api_info["message"]=$this->getsms($message)["MESSAGE"];
		}
                $curl = curl_init();
                // Set SSL if required
                if (substr(trim($operator_info['HOSTNAME']), 0, 5) == 'https') {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }
	  $log->write($api_info);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, trim($operator_info['QUERY_TYPE']) );
                curl_setopt($curl, CURLOPT_USERAGENT, "unnati");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if(trim($operator_info['QUERY_TYPE'])=='GET'){
                curl_setopt($curl, CURLOPT_URL, trim($operator_info['HOSTNAME'])."?".http_build_query($api_info) );}
		else{
			curl_setopt($curl, CURLOPT_URL, trim($operator_info['HOSTNAME']));
			}
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));
				////
				$json=0;
				if(!empty($mobile))
				{
                $json = curl_exec($curl);
		$first_step = explode( '<html' , $json ); 
		if(sizeof($first_step)>0)
		{
			$log->write($first_step[0]); 
		 $json=	$first_step[0];
		}                 
                $response = str_replace(".","",$json);//json_decode($json, true);
	  $log->write($json);
	  $log->write($response);
                if ($mobile) {
				

				$activity_data = array(
					'customer_id' => $mobile,
					'name'        => $response,
                                    'resp'=>$response
				);
				//$this->addActivity('sms', $activity_data);
			} 
                        //$this->smsinsert('sms', $activity_data);
				}		
		$this->smsinsert($api_info,$response); 
		////////////
                curl_close($curl);
    }
	
                return $response;
}
  public function utf8_to_unicode($str) {
    $unicode = array();
        $values = array();
        $lookingFor = 1;
        for ($i = 0; $i < strlen($str); $i++) {
            $thisValue = ord($str[$i]);
                if($thisValue < 128){
                    $number = dechex($thisValue);
                    $unicode[] = (strlen($number) == 1) ? '%u000' . $number : "%u00" . $number;
                } else {
                    if (count($values) == 0)
                        $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
                    $values[] = $thisValue;
                    if (count($values) == $lookingFor) {
                        $number = ( $lookingFor == 3 ) ?
                            ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
                            ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64
                            );
                        $number = dechex($number);
                        $unicode[] = (strlen($number) == 3) ? "%u0" . $number : "%u" . $number;
                        $values = array();
                        $lookingFor = 1;
                    } // if
                } // else
            }//for
        return implode("", $unicode);
    }//function            
           public function addActivity($key, $data) {
		if (isset($data['customer_id'])) {
			$customer_id = $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_activity` SET `customer_id` = '" . (int)$customer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}  
            public function getsms($id)
            {
                
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sms WHERE LOWER(SID) = '" . $this->db->escape(utf8_strtolower($id)) . "'");

		return $query->row;
            }
 public function getoperator($id)
            {
                
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sms_operator WHERE LOWER(ACT) = '" . $this->db->escape(utf8_strtolower($id)) . "'");

		return $query->row;
            }
	public function smsinsert($data,$api_response)
        {
           
            $sql="INSERT INTO oc_sms_trans SET mobile_number ='".$data["to"]."',message='".$data["message"]."',api_response ='".$api_response."' ";
            $this->db->query($sql);
			if(strcmp(strtolower($api_response),strtolower('ERROR:Insufficient Credits'))==0)
			{
				$log=new Log("sms-alert-".date('y-m-d').".log");
				
				$log->write('in if '); 
				$log->write($api_response); 
				$this->sms_bal_alert(); 
			}
           
           
        }
	/*
        public function smsinsert($data)
        {
            $msg_time=date('H:i:s');
            $sql="INSERT INTO " . DB_PREFIX . "sms_record SET MOBILE_NO='".$data["MOBILE_NO"]."',MESSAGE='".$data["MESSAGE"]."',MESSAGE_DATE='".$data['MESSAGE_DATE']."',MESSAGE_TIME='".$msg_time."',MESSAGE_PROCESSED='0',TRANSACTIONID='".$data["TRANSACTIONID"]."',STATE='0'";
            $this->db->query($sql);
            $ret_id = $this->db->countAffected();
	
            return $ret_id;
        }
        */
        public function updateSms($msgid,$TRANSACTIONID)
        { 
            
        $message_sent=$this->getsms($msgid)["MESSAGE"];
        
           
            $sql="update  " . DB_PREFIX . "sms_record SET message_sent='".$message_sent."',MESSAGE_PROCESSED='1' where TRANSACTIONID='".$TRANSACTIONID."'";
            $this->db->query($sql);
            $ret_id = $this->db->countAffected();
            return $ret_id;
        
        }
	private function sms_bal_alert() 
	{
		$log=new Log("sms-alert-".date('y-m-d').".log");
		$log->write("In sms mail");   
		$mail  = new PHPMailer();
		$body = "<p style='border: 1px solid silver;padding: 15px;'>
			Dear All,
			<br/><br/>
			We have insufficient balance in SMS Account (Unnati-Akshamaala4).
			
			<br/><br/>
			This is computer generated alert.Please do not reply to this email.
			<br/><br/>
			<img src='https://unnati.world/shop/image/cache/no_image-45x45.png' />
			<br/><br/>
			Thanking you,
			<br/>
			IT Team
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

        $mail->Subject    ='SMS Account Balance Alert';

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
