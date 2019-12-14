<?php
date_default_timezone_set('Asia/Kolkata');

class ModelCcareIncommingcall extends Model {
public function SubmitCallData($data= array())
{
        $sql="insert into `call_trans_history` set `from`='".$data["current_call_status"]."',`to`='".$data["call_status"]."',`mobile_number`='".$data["mobile"]."',`datetime`='".$data["datetime"]."' ,`logged_user_data`='".$data["logged_user_data"]."' ";
        $log=new Log("call_trans_history-".date('Y-m-d').".log");
        $log->write($sql);
        $trans_query = $this->db->query($sql);
	$transs_id=$this->db->getLastId();       
   
        if($data["call_status"]=="27")
        {
        $sql2="insert into  `ccare_feedback` set `first_name`='".$data["farmer_first_name"]."',`last_name`='".$data["farmer_last_name"]."',`txt_response`='".$data["txt_response"]."',`buy_new`='".$this->db->escape($data["buy_new"])."',`buy_product_text`='".$this->db->escape($data["buy_product_text"])."',`customer_mobile`='".$data["mobile"]."',`datetime`='".$data["sowing_date"]."',"
                . "`Acres`='".$this->db->escape($data["Acres"])."' ,`trans_id`='".$transs_id."',`buy_new_date`='".$data["buying_date"]."',`query`='".$this->db->escape($data["query"])."',`solution`='".$this->db->escape($data["solution"])."'  ";
        $insert_response_query = $this->db->query($sql2);
	$log->write($insert_response_query);
        $query = $this->db->query($insert_response_query);
         }
         $cc_transid=$data["transid"];
         $current_date=date('Y-m-d');
         $current_time=date('H:i:s');
         $da=date('Y-m-d H:i:s');
         
        
         
         $get_date_time_diff=$this->db->query("SELECT DATEDIFF( '".$current_date."',DATE(datereceived)) as datedifference,TIMEDIFF(CURRENT_TIMESTAMP,datereceived) as timediff from cc_incomingcall where `transid`='".$cc_transid."' ");
         $datediff=$get_date_time_diff->row["datedifference"];
         $timediffrence=$get_date_time_diff->row["timediff"];
        
        $update_call_status_sql="update `cc_incomingcall` set `status`='".$data["call_status"]."',`datediscussed`='".$current_date."',`timediscussed`='".$current_time."',`daydiff`='".$datediff."',`timediff`='".$timediffrence."',`feedback_trans_id`='".$transs_id."' where `transid`='".$cc_transid."' ";
        $call_status_query = $this->db->query($update_call_status_sql);
        $log->write($update_call_status_sql);
        
 }

public function getInventory_reportProductWise($data = array()) { //print_r($data);
$sql="
select p2s.store_id,os.name as store_name, p2s.product_id,p.model as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,round(ifnull(((b.price+ifnull((b.tax),0))),0),2)as price,round(ifnull((sum(b.price+ifnull((b.tax),0))),0),2)as Amount
from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
left join oc_product as p on p2s.product_id = p.product_id

left join
(select product_id,store_id,price,tax from
( SELECT p.product_id,p2s.product_id as pid,p2s.store_id,p.model,
sum(p2s.quantity) as qnty, sum(CASE WHEN p2s.store_price='0.0000'
THEN p.price ELSE p2s.store_price END) as price, (sum(p2s.quantity) * (price)) as total,
( ( SELECT (CASE WHEN type='F' then rate WHEN type='p' then (price *(rate/100)) else rate end) as rate
FROM `oc_tax_rule` as rl LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id
WHERE `tax_class_id`=p.tax_class_id ) )as tax FROM oc_product p
LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
WHERE p.status = '1' AND p.date_available <= NOW() 
AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY p.product_id,p2s.store_id ORDER BY p.sort_order ASC )
as a)as b on b.product_id = p2s.product_id and p2s.store_id = b.store_id
where p2s.product_id!=''
"; 


if (!empty($data['filter_name_id']) ) {
$sql .=" and p2s.product_id=".$data['filter_name_id'];

}
if (!empty($data['filter_store']) ) {
$sql .=" and p2s.store_id=".$data['filter_store'];

}


$sql .= " GROUP by p2s.store_id";
if (empty($data['filter_name_id']) ) {
$sql .=" ,p2s.product_id ";

}

if (isset($data['start']) || isset($data['limit'])) {
if ($data['start'] < 0) {
$data['start'] = 0;
}

if ($data['limit'] < 1) {
$data['limit'] = 20;
}

$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
}
//echo $sql;
$query = $this->db->query($sql);
//print_r($query->row);
return $query->rows;
}




public function getrechargedtl($mobile)
{
$sql="SELECT rt.mobile,rt.recharge_amount,rt.order_id,rt.product_id,rt.product_quantity,rt.operator_code,rt.operator_name,concat(rip.ResSerSts,rip.ResErrMsg) as ResSerSts,rip.ResRocTransID,ocp.model as product_name,ocs.name as store_name,rt.scheme_id,ocrp.scheme_name,rt.create_date FROM `oc_recharge_transactions` as rt left join `oc_recharge_transactions_rocket` as rip on rip.sid=rt.rocket_tbl_id left join oc_product as ocp on ocp.product_id=rt.product_id left join oc_store as ocs on ocs.store_id=rt.store_id left join oc_recharge_products as ocrp on ocrp.scheme_id=rt.scheme_id where  rt.mobile='".$mobile."'   ";
	
          
	$sql.=" order by rt.create_date desc ";
            if ($data['limit'] < 1) {
                $data['limit'] = 50;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        

        $query = $this->db->query($sql);
      //echo $sql;
      return $query->rows; 

}
 public function getCallStatus()
 {
     
        $sql ="SELECT * FROM `oc_callstatus` where ACT=1 order by STATUS_NAME asc";
        $query = $this->db->query($sql);
        return $query->rows;
 }
    public function getCrop()
    {
        $sql ="SELECT * FROM oc_crop ";
        $query = $this->db->query($sql);
        return $query->rows;
        
    }
    public function getStoreLocationdtl($dist_id)
    {
        $sql ="SELECT oc_store.*,oc_setting.value as address FROM oc_store join oc_setting on oc_setting.store_id=oc_store.store_id where oc_store.name like '".$dist_id."-%' and oc_setting.key='config_address' ";
        $query = $this->db->query($sql);
        return $query->rows;
        
    }
      
    public function getIncomingCall($data)
    {
        $sql ="SELECT transid,mobile,datereceived,oc_callstatus.STATUS_NAME as status,state_name,cc_incomingcall.status as status_id from cc_incomingcall join oc_callstatus on oc_callstatus.STATUS_ID=cc_incomingcall.status where cc_incomingcall.status in (4,6,8,11,18,20,22,23)  ";

	if (!empty($data['filter_start_date'])) 
	{
		$sql.= " and DATE(datereceived) >= '" . $this->db->escape($data['filter_start_date']) . "'";
	}

	if (!empty($data['filter_end_date'])) 
	{
		$sql .= " and DATE(datereceived) <= '" . $this->db->escape($data['filter_end_date']) . "'";
	}        
	if (!empty($data['filter_status'])) 
	{
		$sql .= " and cc_incomingcall.status= '" . $this->db->escape($data['filter_status']) . "'";
	} 
	if (!empty($data['filter_number'])) 
	{
		$sql .= " and cc_incomingcall.mobile= '" . $this->db->escape($data['filter_number']) . "'";
	} 
	$sql.=" order by cc_incomingcall.datereceived desc ";
        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
	//echo $sql;
        $query = $this->db->query($sql);
        return $query->rows;
    }
    
    public function getAllIncomingCall($data)
    {
        $sql ="select count(*) as total from  ( 
		SELECT mobile from cc_incomingcall join oc_callstatus on oc_callstatus.STATUS_ID=cc_incomingcall.status where cc_incomingcall.status in (4,6,8,11,18,20,22,23) ";  
		if (!empty($data['filter_start_date'])) 
		{
			$sql.= " and DATE(datereceived) >= '" . $this->db->escape($data['filter_start_date']) . "'";
		}

		if (!empty($data['filter_end_date'])) 
		{
			$sql .= " and DATE(datereceived) <= '" . $this->db->escape($data['filter_end_date']) . "'";
		}          
		if (!empty($data['filter_status'])) 
		{
			$sql .= " and cc_incomingcall.status= '" . $this->db->escape($data['filter_status']) . "'";
		} 
		if (!empty($data['filter_number'])) 
		{
			$sql .= " and cc_incomingcall.mobile= '" . $this->db->escape($data['filter_number']) . "'";
		} 
	$sql.=" ) as aa ";
        
        
        $query = $this->db->query($sql);
        return $query->row["total"];
    }
   

 public function getTotalincomingcall_answer($data = array()) {
$sql = "select COUNT(*) as total
from (
select ci.transid
from cc_incomingcall as ci
left join oc_callstatus as ocs on ocs.STATUS_ID=ci.status
left join ccare_feedback as cf on ci.feedback_trans_id=cf.trans_id
where ci.status='27' ";

if (!empty($data['filter_date_start'])) {
$sql.= " and DATE(ci.datereceived) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " and DATE(ci.datereceived) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
$sql.=" group by ci.mobile ) as aa ";
//echo $sql;
$query = $this->db->query($sql);

return $query->row['total'];
}


public function incomingcall_answer($data)
{
$sql="select ci.datereceived,ci.mobile,ci.transid,ocs.STATUS_NAME,ci.datediscussed,cf.*
from cc_incomingcall as ci
left join oc_callstatus as ocs on ocs.STATUS_ID=ci.status 
left join ccare_feedback as cf on ci.feedback_trans_id=cf.trans_id
where ci.status='27' ";
$implode = array();


if (!empty($data['filter_date_start'])) {
$sql.= " and DATE(ci.datereceived) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " and DATE(ci.datereceived) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$sql .= " group by ci.mobile order by  ci.transid desc";

if (isset($data['start']) || isset($data['limit'])) { 
if ($data['start'] < 0) {
$data['start'] = 0;
}

if ($data['limit'] < 1) {
$data['limit'] = 20;
}

$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
}
//echo $sql;
$query = $this->db->query($sql);
//print_r($query);
return $query->rows;
}
}