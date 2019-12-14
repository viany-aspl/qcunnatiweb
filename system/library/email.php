<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class email {
public function __construct($registry) {
                $this->config = $registry->get('config');
	  $this->db = $registry->get('db');
                
}
public function sendmail($mail_subject,$mail_body)
{ 
			$log= new Log('email-'.date('Y-m-d').'.log');
			 $log->write($mail_body);
		$names = is_array($mail_body) ? $mail_body : array($mail_body);
		
 		$body = "";
		$htmlBody =$body; // Header HTML
		$htmlBody.="<br/><table><tr><td>Message Details</td></tr>";
		foreach ($names as $name) {
		$log->write($name);
		 if (!is_object($name)) {
		    $htmlBody .= "<tr><td>".$name."</td></tr>";
		}

		}
		$htmlBody.="</table>";
		$log->write($htmlBody);

	$mail             = new PHPMailer();

                $log->write($htmlBody);
                
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

                $mail->Subject    = $mail_subject;

                $mail->AltBody    = "No Data in message body!"; // optional, comment out and test

                $mail->MsgHTML($htmlBody);
                
                $mail->AddAddress('vipin.kumar@aspl.ind.in', "Vipin");                
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
                  $log->write("Mailer Error: " . $mail->ErrorInfo);
                }
                else
                {
                  
                     $log->write("Success ");
                  
                                  
                }			
}
}
?>