<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');

class ControllerCommonPartner extends Controller {
	public function index() {
                $name=$this->request->post['name'];
                $firm=$this->request->post['firm'];
                $mob=$this->request->post['mob'];
                $email=$this->request->post['email'];
                $msg=$this->request->post['msg'];
                 
		$this->load->model('partner/partner');
                             
                $results = $this->model_partner_partner->postMessage($name,$firm,$mob,$email,$msg);
                if($results==1){
                    ////////////////////////////////
                    //mailing
			/*	
				$mail             = new PHPMailer();
				$body = "<p>Akshamaala Solutions Pvt. Ltd.</p>";
				
				$mail->IsSMTP(); 
				$mail->Host       = "mail.akshamaala.in"; 
														   
				$mail->SMTPAuth   = false;                 
				$mail->SMTPSecure = "";                 
				$mail->Host       = "mail.akshamaala.in";      
				$mail->Port       = 25;                  
				$mail->Username   = "mis@akshamaala.in";  
				$mail->Password   = "mismis";            

				$mail->SetFrom('mis@akshamaala.in', 'company_name');

				$mail->AddReplyTo('mis@akshamaala.in','company_name');

				$mail->Subject    = "Unnati - Partner Connect Request";

				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

				$mail->MsgHTML($body);
				
				$mail->AddAddress("vipin.kumar@aspltech.com", "kunwar rana");
				
				if(!$mail->Send()) 
				{
				  //echo "Mailer Error : " . $mail->ErrorInfo;
				} 
				else
				{
				  //echo "Mailer success : " . $mail->ErrorInfo;
				}
                    */
                    /////////////////////////////////
                    echo 'success';
                }
                else {
                  echo 'info';
                }
	}
        
          
//*****************************MAIL FUNCTION START*********************************************//
function MsgToMail($to,$sub,$msg,$stack){
    print_r($stack[0]); die;
			$tocc=count($to);
			$mail = new PHPMailer;
			//$mail->SMTPDebug = 3;                               		
			$mail->isSMTP();                                      		
			$mail->Host = 'mail.akshamaala.in';                   		
			$mail->SMTPAuth = false;                          
			$mail->Username = 'mis@akshamaala.in';                		
			$mail->Password = 'mismis';                           		
			$mail->Port = 25;                                     		
			$mail->setFrom('mis@akshamaala.in','AKSHAMAALA');   
			$mail->addAddress($to[0]["EMAIL"]);   
			$mail->isHTML(true);                                    	
			$mail->Subject = $sub;		
			$mail->Body    = $msg;									
			$mail->AltBody = 'Mail from Akshamaala';
			$mail->AddAttachment($stack[0]);
			$mail->AddAttachment($stack[1]);
			$mail->AddAttachment($stack[2]);
			$mail->AddAttachment($stack[3]);
			for($i=0;$i<$tocc-1;$i++){
			$mail->AddCC($to[$i+1]["EMAIL"]);
                        			}
			$mail->send();
			
                    }
  
}