<?php
class ModelCardCard extends Model {

public function getCardList($data)
{
$sql='SELECT od.amount,od.transaction_type,DATE(od.create_date) as create_date,od.payment_method,store.name,user.firstname,user.lastname FROM `oc_payout_dtl` as od
LEFT JOIN oc_store as store on store.store_id=od.store_id
LEFT JOIN oc_user as user on user.user_id=od.user_id';
if (!empty($data['filter_stores_id'])) {
$sql .= " WHERE od.store_id= '" . (int)$data['filter_stores_id'] . "'";
} else {
$sql .= " WHERE od.store_id > '0'";
}
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$sql.=" order by sid desc ";

if (isset($data['start']) || isset($data['limit'])) {
if ($data['start'] < 0) {
$data['start'] = 0;
}

if ($data['limit'] < 1) {
$data['limit'] = 20;
}

$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
}
$query= $this->db->query($sql);
return $query->rows;
}
public function getCardListTotal()
{
$sql='select count(*) as total from (SELECT od.amount,od.transaction_type,DATE(od.create_date) as create_date,od.payment_method,store.name,user.firstname,user.lastname FROM `oc_payout_dtl` as od
LEFT JOIN oc_store as store on store.store_id=od.store_id
LEFT JOIN oc_user as user on user.user_id=od.user_id';
if (!empty($data['filter_stores_id'])) {
$sql .= " WHERE od.store_id= '" . (int)$data['filter_stores_id'] . "'";
} else {
$sql .= " WHERE od.store_id > '0'";
}
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
$sql.=" ) as aa";
$query= $this->db->query($sql);
return $query->row['total'];
}
 

public function check_grower_details($data)
{
$sql="SELECT * from cn_ryot_mst LEFT JOIN oc_card_issue on oc_card_issue.GROWER_ID=cn_ryot_mst.GROWER_CODE WHERE (oc_card_issue.CARD_SERIAL_NUMBER = '" .$data['Card_Serial_Number']."' or MOB = '" .$data['mobile']."') " ;
if($data['old_pin']!='')
{
    //$sql.=" and oc_card_issue.CARD_PIN='".$data['old_pin']."'";
}
$query= $this->db->query($sql);
return $query->row;
}
public function generate_pin($data)
{
    $pin = rand(1000, 9999);
    $log=new Log('card-pin'.date('Y-m-d').'.log');
    //GROWER_ID = '" .$data['grower_id']."' and

    $sql="insert into  oc_card_otp set grower_id ='',card_number = '" .$data['Card_Serial_Number']."',otp = '" .$pin."',mobile_number = '" .$data['MOB']."' " ;
    $query= $this->db->query($sql);
    $log->write($sql);

    $sql="update oc_card_issue set CARD_PIN ='".$pin."',CARD_STATUS='9' WHERE  CARD_SERIAL_NUMBER = '" .$data['Card_Serial_Number']."' " ;
    $query= $this->db->query($sql);
    $log->write($sql);
    $ret=$this->db->countAffected();
    $log->write($ret);
    $data['pin']=$pin;
    $this->load->library('card_trans'); 
    $card_trans=new card_trans($this->registry);
    $card_trans->addtrans($data['Card_Serial_Number'], 9, date('Y-m-d'), 0, 0, 0);
    if($ret>0)
    {
       $log->write('now call sms library');
       $this->load->library('sms'); 
       $sms=new sms($this->registry); 
       $sms->sendsms($data['MOB'], 14, $data);////////pin generation msg 
    }
    else
    {
        
    }
    return $ret;
}
public function send_otp($data)
{
    $pin = rand(1000, 9999);
    $log=new Log('card-pin'.date('Y-m-d').'.log');
    $sql="insert into  oc_card_otp set grower_id ='".$data['grower_id']."',card_number = '" .$data['Card_Serial_Number']."',otp = '" .$pin."',mobile_number = '" .$data['MOB']."' " ;
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
       $sms=new sms($this->registry);
       //$sms->sendsms($data['MOB'], 13, $data);////////send otp 
    }
    else
    {
        
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
public function check_otp($data)
{
        $log=new Log('card-pin'.date('Y-m-d').'.log');
        //and grower_id = '" .$data['grower_id']."'
        $sql="select * from  oc_card_otp where otp ='".$data['otp']."'  and card_number = '" .$data['Card_Serial_Number']."' limit 1 " ;
        $query= $this->db->query($sql);
        $log->write($sql);
        //$ret=$this->db->countAffected();
   
    
        return $query->row;;
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
       $sms=new sms($this->registry);
       $sms->sendsms($data['MOB'], 15, $data);//////pin change message
    }
    else
    {
        
    }
    return $ret;
}


}
?>