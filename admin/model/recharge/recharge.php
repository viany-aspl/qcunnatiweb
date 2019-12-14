<?php
date_default_timezone_set("Asia/Calcutta");
class ModelRechargeRecharge extends Model {
	
public function get_master_products($store_id) {
	  $sql=" SELECT product_id FROM `oc_recharge_products`   where store_id='".$store_id."'   ";
                
		$query = $this->db->query($sql);
                
		return $query->rows;
           
	}
public function get_product_recharge_quantity($product_id,$store_id,$current_date) {
	 $sql=" SELECT * FROM `oc_recharge_products`   where store_id='".$store_id."' and product_id='".$product_id."'  and '".$current_date."'>=date(start_date) and '".$current_date."'<=date(end_date)  ";
                
		$query = $this->db->query($sql);
                
		return $query->row;
           
	}
public function get_recharge_scheme_status($scheme_id,$mobile) {
	              $sql=" SELECT * FROM `oc_recharge_transactions`   where scheme_id='".$scheme_id."' and mobile='".$mobile."'  ";
                
		$query = $this->db->query($sql);
                            $log=new Log("recharge-".date('Y-m-d').".log");
                            $log->write($sql);
                            $log->write($query->row);
		return $query->row;
           
	}
public function insertIntoRechargeTrans($data=array(),$operator_name,$operator_code)
        {
            
            $sql="insert into `oc_recharge_transactions` set `mobile`='".$data["mobile"]."',`recharge_amount`='".$data["recharge_amount"]."',`order_id`='".$data["order_id"]."',`store_id`='".$data["store_id"]."',`product_id`='".$data["product_id"]."',`product_quantity`='".$data["product_quantity"]."',`operator_name`='".$operator_name."',`operator_code`='".$operator_code."',`scheme_id`='".$data["scheme_id"]."',`status`='".$data["status"]."',`rocket_trans_id`='".$data["rocket_trans_id"]."',`rocket_err_code`='".$data["rocket_err_code"]."',`rocket_tbl_id`='".$data["rocket_tbl_id"]."'  "; 
            $query = $this->db->query($sql);
            $log=new Log("recharge-".date('Y-m-d').".log");
            $log->write($sql);
            $recharge_id = $this->db->getLastId(); 
            $log->write($recharge_id);
            return $recharge_id;
            
        }
public function insertIntoRechargeTransRocket($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt)
{
$sql="insert into `oc_recharge_transactions_rocket` set `ResErr`='".$ResErr."',`ResErrMsg`='".$ResErrMsg."',`ResAmt`='".$ResAmt."',`ResCom`='".$ResCom."',`ResDT`='".$ResDT."',`ResNum`='".$ResNum."',`ResNwOc`='".$ResNwOc."',`ResNw`='".$ResNw."',`ResNwTransID`='".$ResNwTransID."',`ResRecCode`='".$ResRecCode."',`ResRocTransID`='".$ResRocTransID."',`ResSevTyp`='".$ResSevTyp."',`ResSerSts`='".$ResSerSts."',`ResTransAmt`='".$ResTransAmt."'  "; 
            $query = $this->db->query($sql);
            $log=new Log("recharge-".date('Y-m-d').".log");
            $log->write($sql);
            $recharge_id = $this->db->getLastId(); 
            
            $log->write($recharge_id);
            return $recharge_id;
}
public function insertIntoRechargeTransRocketReHit($ResErr,$ResErrMsg,$ResAmt,$ResCom,$ResDT,$ResNum,$ResNwOc,$ResNw,$ResNwTransID,$ResRecCode,$ResRocTransID,$ResSevTyp,$ResSerSts,$ResTransAmt,$transtblid,$rockettranstblid)
{
$sql="insert into `oc_recharge_transactions_rocket_rehit` set `ResErr`='".$ResErr."',`ResErrMsg`='".$ResErrMsg."',`ResAmt`='".$ResAmt."',`ResCom`='".$ResCom."',`ResDT`='".$ResDT."',`ResNum`='".$ResNum."',`ResNwOc`='".$ResNwOc."',`ResNw`='".$ResNw."',`ResNwTransID`='".$ResNwTransID."',`ResRecCode`='".$ResRecCode."',`ResRocTransID`='".$ResRocTransID."',`ResSevTyp`='".$ResSevTyp."',`ResSerSts`='".$ResSerSts."',`ResTransAmt`='".$ResTransAmt."',`transtblid`='".$transtblid."'  "; 
            $query = $this->db->query($sql);
            $log=new Log("recharge-re-hit-".date('Y-m-d').".log"); 
            $log->write($sql);
            $recharge_id = $this->db->getLastId(); 
            
            $log->write($recharge_id);
            return $recharge_id;
}

}