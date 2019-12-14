<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createVillage
 *
 * @author agent
 */
class Modelrechargerechargereport extends Model {
    
    
    
    function getrechargeData($data){
        $sql="SELECT rt.mobile,rt.recharge_amount,rt.order_id,rt.product_id,rt.product_quantity,rt.operator_code,rt.operator_name,concat(rip.ResSerSts,rip.ResErrMsg) as ResSerSts,rip.ResRocTransID,ocp.model as product_name,ocs.name as store_name,rt.scheme_id,ocrp.scheme_name,rt.create_date FROM `oc_recharge_transactions` as rt left join `oc_recharge_transactions_rocket` as rip on rip.sid=rt.rocket_tbl_id left join oc_product as ocp on ocp.product_id=rt.product_id left join oc_store as ocs on ocs.store_id=rt.store_id left join oc_recharge_products as ocrp on ocrp.scheme_id=rt.scheme_id where date(rt.create_date) BETWEEN '".$data['filter_from_date']."' and '".$data['filter_to_date']."'   ";
	if(!empty($data["filter_mobile"]))
              {
                   $sql.=" and rt.mobile='".$data["filter_mobile"]."' ";
              }
              if(!empty($data["filter_store"]))
              {
                   $sql.=" and rt.store_id='".$data["filter_store"]."' ";
              }
          if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
	$sql.=" order by rt.create_date desc ";
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);
        //echo $sql;
      return $query->rows;  
    }
    function getrechargeDatacount($data){
        
       $sql="SELECT count(rt.mobile) as total  FROM `oc_recharge_transactions` as rt left join `oc_recharge_transactions_rocket` as rip on rip.sid=rt.rocket_tbl_id where date(rt.create_date) BETWEEN '".$data['filter_from_date']."' and '".$data['filter_to_date']."'";
              if(!empty($data["filter_mobile"]))
              {
                   $sql.=" and rt.mobile='".$data["filter_mobile"]."' ";
              }
	if(!empty($data["filter_store"]))
              {
                   $sql.=" and rt.store_id='".$data["filter_store"]."' ";
              }
        $query = $this->db->query($sql);
     // echo $sql;
      return $query->row["total"];  
    }
    
   
    function getrechargeDatacountnumber($data){
        $sql="SELECT count(concat(rip.ResSerSts,rip.ResErrMsg)) as count_n,concat(rip.ResSerSts,rip.ResErrMsg) as ResSerSts FROM `oc_recharge_transactions` as rt left join `oc_recharge_transactions_rocket` as rip on rip.sid=rt.rocket_tbl_id left join oc_product as ocp on ocp.product_id=rt.product_id left join oc_store as ocs on ocs.store_id=rt.store_id left join oc_recharge_products as ocrp on ocrp.scheme_id=rt.scheme_id where date(rt.create_date) BETWEEN '".$data['filter_from_date']."' and '".$data['filter_to_date']."'   ";
	if(!empty($data["filter_mobile"]))
              {
                   $sql.=" and rt.mobile='".$data["filter_mobile"]."' ";
              }
              if(!empty($data["filter_store"]))
              {
                   $sql.=" and rt.store_id='".$data["filter_store"]."' ";
              }
          if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
	
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

           //$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
$sql.=" group by (concat(rip.ResSerSts,rip.ResErrMsg))  ";
        $query = $this->db->query($sql);
        //echo $sql;
      return $query->rows;  
    }

public function get_pending_data()
{
$sql="SELECT rt.sid as transid,rip.ResRocTransID FROM `oc_recharge_transactions` as rt left join `oc_recharge_transactions_rocket` as rip on rip.sid=rt.rocket_tbl_id where rip.ResSerSts='Pending'  ";

        $query = $this->db->query($sql);
        //echo $sql;
      return $query->rows;  
}
public function update_recharge_status_data($ResRocTransID,$transid,$resstatus)
{ 
$log=new Log("recharge-update-status-".date('Y-m-d').".log");	
$log->write($ResRocTransID.",".$transid.",".$resstatus);

$sql="update `oc_recharge_transactions_rocket` set ResSerSts='".$resstatus."' where ResRocTransID='".$ResRocTransID."' ";
$log->write($sql);

$query = $this->db->query($sql);

if($resstatus=="Success")
{
 $status=1;
}
else if($resstatus=="Refund")
{
$status=2;
}
else
{
$status=0;
}

$sql2="update `oc_recharge_transactions` set status='".$status."' where  sid='".$transid."' ";
$log->write($sql2);
$query = $this->db->query($sql2);
}
public function update_recharge_status_data_re_hit($ResRocTransID,$transid,$resstatus)
{ 
$log=new Log("recharge-update-status-".date('Y-m-d').".log");	
$log->write($ResRocTransID.",".$transid.",".$resstatus);

$sql="update `oc_recharge_transactions_rocket` set ResSerSts='".$resstatus."',`ResErrMsg`='' where sid='".$transid."' ";
$log->write($sql);

$query = $this->db->query($sql);

if($resstatus=="Success")
{
 $status=1;
}
else if($resstatus=="Refund")
{
$status=2;
}
else
{
$status=0;
}

$sql2="update `oc_recharge_transactions` set status='".$status."' where  sid='".$transid."' ";
$log->write($sql2);
$query = $this->db->query($sql2);
}
}
