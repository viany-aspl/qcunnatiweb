<?php
class ModelCardIntegration extends Model {
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
public function card_dispatch($data)
{
    
    $log=new Log("Card-Dispatch-".date('Y-m-d').".log");
    $this->load->library('card_trans'); 
    $card_trans=new card_trans($this->registry);
	$status_name=$card_trans->getstatusname(7);
	
    $sql="update oc_card_issue set CARD_STATUS='7',CARD_STATUS_DESC='".$status_name."' WHERE GROWER_ID = '" .$data['grower_id']."' and CARD_SERIAL_NUMBER = '" .$data['Card_Serial_Number']."' " ;
    $query= $this->db->query($sql);
    $log->write($sql);
    $ret=$this->db->countAffected();
    $log->write($ret);
    
    
    $card_trans->addtrans($data['Card_Serial_Number'], 7, date('Y-m-d'), 0, 0, 0);
    
    return $ret;
}
public function card_delivery($data)
{
    
    $log=new Log("Card-delivery-".date('Y-m-d').".log");
    $this->load->library('card_trans'); 
    $card_trans=new card_trans($this->registry);
	$status_name=$card_trans->getstatusname(9);
    $sql="update oc_card_issue set CARD_STATUS='9',CARD_STATUS_DESC='".$status_name."' WHERE GROWER_ID = '" .$data['grower_id']."' and CARD_SERIAL_NUMBER = '" .$data['Card_Serial_Number']."' " ;
    $query= $this->db->query($sql);
    //$log->write($sql);
    $ret=$this->db->countAffected();
    $log->write($ret);
    
    
    $card_trans->addtrans($data['Card_Serial_Number'], 8, date('Y-m-d'), 0, 0, 0,$data['grower_id']); 
    
    return $ret;
}
public function send_otp($data)
{
 $log=new Log('Card-pin-'.date('Y-m-d').'.log');
     $log->write($data);
if($data["OD"]=="5")
{
  $pin = rand(10000, 99999);
}else{
    $pin = rand(1000, 9999);
	}
   $data['otp']= $pin;
   if(empty($data['TX']))
   {
	$data['TX']="0";
   }
     $unit=$data['CARD_UNIT'];
//	 $this->load->model('unit/unit');
	     $log->write("in pin");
	 $unitdata= $this->model_unit_unit->getUnitByID($unit);
	  $log->write($unitdata);
	 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						//$this->load->model('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->SendRequest('SendRequest',$data,0); 						
						}
				 
						//	$this->load->library('soapcurl');
						//$soapcurl = new soapcurl($this->registry);
						//$datares= $soapcurl->SendRequest('SendRequest',$data,0); 
    $log->write($datares);
   if($datares=="1"){
    $sql="insert into  oc_card_otp set trx_type='".$data['TX']."',grower_id ='".$data['grower_id']."',card_number = '" .$data['Card_Serial_Number']."',otp = '" .$pin."',mobile_number = '" .$data['MOB']."' " ;
    $query= $this->db->query($sql);
    $log->write($sql);
    $ret=$this->db->countAffected();
	$ret=$datares;
    $data['pin']=$pin;
    $this->load->library('card_trans'); 
    $card_trans=new card_trans($this->registry);
    $card_trans->addtrans($data['Card_Serial_Number'], 9, date('Y-m-d'), 0, 0, 0);
    if($ret>0)
    {
       $this->load->library('sms'); 
       $sms=new sms($this->registry);
	   if($data['TX']=="2")
	   {
			$sms->sendsms($data['MOB'], 16, $data);////////send otp 
	   }
	   else if($data['TX']=="1")
	   {
       //$sms->sendsms($data['MOB'], 18, $data);////////send otp 
	   }
    }
    else
    {
        
    }
	}
    return $ret;
}
public function check_pin($data)
{
        $log=new Log('card-pin'.date('Y-m-d').'.log');
        //and GROWER_ID = '" .$data['grower_id']."'
        $sql="select * from  oc_card_issue where CARD_PIN ='".$data['old_pin']."' and CARD_SERIAL_NUMBER = '" .$data['Card_Serial_Number']."' limit 1 " ;
        $query= $this->db->query($sql);
        $log->write($sql);
        $ret=$this->db->countAffected();
        return $ret;
}
public function get_grower_by_card($Card_Serial_Number,$GROWER_ID)
{
        $log=new Log('order-'.date('Y-m-d').'.log'); 
        //and GROWER_ID = '" .$data['grower_id']."'
        $sql="select * from  oc_card_issue where CARD_SERIAL_NUMBER = '" .$Card_Serial_Number."' and GROWER_ID= '" .$GROWER_ID."' limit 1 " ;
        $query= $this->db->query($sql);
        $log->write($sql);
       
        return $query->row;
}

public function check_otp($data)
{
        $log=new Log('Card-pin-'.date('Y-m-d').'.log');		
		//GetTrans
			//$this->load->library('soapcurl');
					//	$soapcurl = new soapcurl($this->registry);
					//	$datares= $soapcurl->GetTrans('GetTrans',$data,0);
	 $unit=$data['CARD_UNIT'];
	
	
	$log->write($data);
	$log->write("in");
	 try{
	  $this->adminmodel('unit/unit');
	 $unitdata= $this->model_unit_unit->getUnitByID($unit);
	 }catch(Exception $e)
	{
	$log->write($e);
	}
	 $log->write("out");
	 $log->write($unitdata);
	 if(!empty($unitdata['company_name'])){
						$company=strtolower($unitdata['company_name']);
						$this->adminmodel('pos/'.$company);
						$datares = $this->{'model_pos_' . $company}->GetTrans('GetTrans',$data,0); 						
						}					
    $log->write($datares);
   if($data['otp']==$datares){
        //and grower_id = '" .$data['grower_id']."'
        $sql="select * from  oc_card_otp where trx_type='".$data['TX']."' and otp ='".$data['otp']."'  and card_number = '" .$data['Card_Serial_Number']."' and cr_date >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) order by sid desc limit 1 " ;
        $query= $this->db->query($sql);
        $log->write($sql);
        //$ret=$this->db->countAffected();
        return $query->row;}
		return array();
}

public function check_otp_trans($data)
{
        $log=new Log('card-otp-trans-'.date('Y-m-d').'.log');		
		//GetTrans	       
        $sql="select * from  oc_card_otp where trx_type='".$data['TX']."' and card_number = '" .$data['Card_Serial_Number']."' and cr_date >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) order by sid desc limit 1 " ;
        $query= $this->db->query($sql);
        $log->write($sql);
        
        return $query->row;
		
}

public function change_pin($data)
{
    $pin = $data['new_pin'];
    $log=new Log('card-pin'.date('Y-m-d').'.log');
    //GROWER_ID = '" .$data['grower_id']."' and

    $sql="insert into  oc_card_otp set grower_id ='',card_number = '" .$data['Card_Serial_Number']."',otp = '" .$pin."',mobile_number = '" .$data['MOB']."' " ;
    $query= $this->db->query($sql);
    $log->write($sql);

    $sql="update oc_card_issue set CARD_PIN ='".$pin."' WHERE  CARD_SERIAL_NUMBER = '" .$data['Card_Serial_Number']."' " ;
    $query= $this->db->query($sql);
    $log->write($sql);
    $ret=$this->db->countAffected();
    $data['pin']=$pin;
    $this->load->library('card_trans'); 
    $card_trans=new card_trans($this->registry);
    $card_trans->addtrans($data['Card_Serial_Number'], 9, date('Y-m-d'), 0, 0, 0);
    if($ret>0)
    {
       $this->load->library('sms'); 
       $sms=new sms($this->registry);//$data['MOB']
       $sms->sendsms('9560031154', 15, $data);//////pin change message
    }
    else
    {
        
    }
    return $ret;
}
public function getmobileno($data)
{ 




$sql="SELECT MOB,CARD_STATUS from oc_card_issue where CARD_SERIAL_NUMBER='".$data['Card_Serial_Number']."' and GROWER_ID='".$data['grower_id']."'";

$query = $this->db->query($sql);

return $query->row;
}

public function getgrower($CARD_SERIAL_NUMBER,$UNIT_ID)
{
$sql = "SELECT GROWER_ID,GROWER_NAME,FTH_HUS_NAME,VILLAGE_NAME FROM " . DB_PREFIX . "card_issue  where CARD_SERIAL_NUMBER='".$CARD_SERIAL_NUMBER."' and UNIT_ID='".$UNIT_ID."' ";

$query = $this->db->query($sql);
// echo $query->row['name'];
return $query->row;
}

public function checkCardStatusHistory($grower_id,$CARD_SERIAL_NUMBER)
{
$log=new Log("Card-Send-otp-".date('Y-m-d').".log");
$sql = "SELECT CARD_STATUS_ID FROM " . DB_PREFIX . "card_lcm_history where CARD_SERIAL_NO='".$CARD_SERIAL_NUMBER."' and (CARD_STATUS_ID=3 or CARD_STATUS_ID=7)order by CARD_STATUS_ID ";//and UNIT_ID='".$UNIT_ID."' ";
$query = $this->db->query($sql);
$log->write($sql);
return $query->rows;
}
 

}
?>