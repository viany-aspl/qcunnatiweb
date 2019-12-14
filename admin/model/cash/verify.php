<?php
class ModelCashVerify extends Model {
	
	public function getCash_report($data = array()) {
	   
            $sql="SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id  ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " where DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
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
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

	public function getTotalCash_transation($data = array()) {
		
            $sql="SELECT count(obt.transid) as total,sum(obt.amount) as total_amount FROM `oc_bank_transaction` as obt ";
                    
            if (!empty($data['filter_date_start'])) {
			$sql .= " WHERE DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                
		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getCash_reportVerified($data = array()) {
	   
            $sql="SELECT oc_bank_transaction_verified.*,oc_store.name,oc_user.firstname,oc_user.lastname "
                    . "FROM oc_bank_transaction_verified "
                    . "join  oc_store on oc_store.store_id=oc_bank_transaction_verified.store_id join oc_user on oc_user.user_id=oc_bank_transaction_verified.verified_by ";
                  
                            if (!empty($data['filter_date_start'])) {
			$sql .= " where DATE(oc_bank_transaction_verified.deposit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_transaction_verified.deposit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_bank_transaction_verified.store_id = '" . $this->db->escape($data['filter_store']) . "'";
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
		$query = $this->db->query($sql);
                //echo $sql;
		return $query->rows;
	}

	public function getTotalCash_transationVerified($data = array()) {
		
            $sql="select count(*) as total,sum(amount) as total_amount from (SELECT oc_bank_transaction_verified.*,oc_store.name "
                    . "FROM oc_bank_transaction_verified "
                    . "join  oc_store on oc_store.store_id=oc_bank_transaction_verified.store_id ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " where DATE(oc_bank_transaction_verified.deposit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_transaction_verified.deposit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_bank_transaction_verified.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
                $sql.=" ) as aa";
		$query = $this->db->query($sql);

		return $query->row;
	}
        
        public function getCash_reportByRunner($data = array()) {
	   
            $sql="SELECT oc_bank_deposit_runner.*,oc_user.firstname,oc_user.lastname,oc_bank.bank as bank_name "
                    . "FROM oc_bank_deposit_runner left join oc_bank on oc_bank.bank_id=oc_bank_deposit_runner.bank "
                    . " join oc_user on oc_user.user_id=oc_bank_deposit_runner.user_id where oc_bank_deposit_runner.bank!='' and DATE(oc_bank_deposit_runner.submit_date) >='2018-04-03' ";
                  
                            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_bank_deposit_runner.submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_deposit_runner.submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND oc_bank_deposit_runner.user_id = '" . $this->db->escape($data['filter_user']) . "'";
		}
		if ($data['filter_status']!='') {
			$sql .= " AND oc_bank_deposit_runner.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
		
            		$sql.=" order by oc_bank_deposit_runner.SID desc ";
            
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
              //  echo $sql;
		return $query->rows; 
	}

	public function getTotalCash_transationByRunner($data = array()) {
		
            $sql="select count(*) as total,sum(amount) as total_amount from (SELECT oc_bank_deposit_runner.*,oc_user.firstname,oc_user.lastname "
                    . "FROM oc_bank_deposit_runner left join oc_bank on oc_bank.bank_id=oc_bank_deposit_runner.bank "
                    . " join oc_user on oc_user.user_id=oc_bank_deposit_runner.user_id where oc_bank_deposit_runner.bank!='' and DATE(oc_bank_deposit_runner.submit_date) >='2018-04-03' ";
                  
                            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_bank_deposit_runner.submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_deposit_runner.submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_user'])) {
			$sql .= " AND oc_bank_deposit_runner.user_id = '" . $this->db->escape($data['filter_user']) . "'";
		}
            		if ($data['filter_status']!='') {
			$sql .= " AND oc_bank_deposit_runner.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
                $sql.=" ) as aa";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}
public function accept_reject_cash($tr_id,$logged_user,$status,$bank_tr_number) {
	
		$date_updated=date('Y-m-d h:i:s'); 
		
		$sql1="select status from oc_bank_deposit_runner where  `SID`='".$tr_id."' limit 1 ";
		$query1 = $this->db->query($sql1);
		
		$oldstatus=$query1->row['status'];
		if($oldstatus=='0')
		{
			$sql="update `oc_bank_deposit_runner` set `status`='".$status."',`verified_by`='".$logged_user."',`verified_time`='".$date_updated."',`transaction_number` ='".$bank_tr_number."'  where  `SID`='".$tr_id."' ";
			$query = $this->db->query($sql); 
			return $oldstatus;
		}
		else
		{
			$oldstatus;
		}

		
	}
public function check_bank_tr_number($tr_id,$bank_tr_number) {
	
		$date_updated=date('Y-m-d h:i:s'); 
		
		$sql1="select transaction_number from oc_bank_deposit_runner where `transaction_number` ='".$bank_tr_number."' limit 1 ";
		$query1 = $this->db->query($sql1);
		
		return $query1->row['transaction_number']; 
		
	}

///////////////////////////////////////////////
        public function getTransactionTypes()
        {
            $sql="SELECT bank,bank_id FROM `oc_bank` order by bank_id desc ";
            $query = $this->db->query($sql);
            //print_r($query->rows);
            return $query->rows;
        }
        public function verify_cash($data=array())
        {
            $sql1="SELECT bank FROM `oc_bank` where `bank_id`='".$data["filter_trans_type"]."' ";
            $query1 = $this->db->query($sql1);
            $bank_name=$query1->row["bank"];
            
            $sql="insert into `oc_bank_transaction_verified` (`user_id`,`store_id`,`bank_id`,`bank_name`,`amount`,`deposit_date`,`transaction_number`,`branch_code`,`branch_location`,`remarks`,`verified_by`,`status`) VALUES ('".$data["logged_user"]."','".$data["filter_store"]."','".$data["filter_trans_type"]."','".$bank_name."','".$data["deposit_amount"]."','".$data["deposit_date"]."','".$data["transaction_number"]."','".$data["branch_code"]."','".$data["branch_location"]."','".$data["remarks"]."','".$data["logged_user"]."','1')";//  ,"."',`transaction_number`='',`branch_code`=,`branch_location`=,`remarks`=,`verified_by`=,`status`='1' where `transid`='".$data["transid"]."' ";
            $query = $this->db->query($sql);
            $query2 = $this->db->query("UPDATE oc_store SET currentcredit = currentcredit - '".$data["deposit_amount"]."' WHERE store_id='".$data["filter_store"]."'");
        }
        public function insert_into_store_trans($data=array())
        {
            $sql="insert into  oc_store_trans set `store_id`='".$data["store_id"]."',`amount`='".$data["deposit_amount"]."',`transaction_type`='3',`cr_db`='DB',`user_id`='".$data["logged_user"]."' ";
            $query = $this->db->query($sql);
            
        }
        public function getstoresdata($store_id)
        {
         $sql="SELECT currentcredit from oc_store  where `store_id`='".$store_id."' limit 1 ";
                  
		$query = $this->db->query($sql);
                
		return $query->row;
        }
        public function getCash_record($data=array())
        {
                $sql="SELECT oc_bank_transaction.*,oc_store.* FROM `oc_bank_transaction` join oc_store on oc_store.store_id=oc_bank_transaction.store_id  where `transid`='".$data["transid"]."' limit 1 ";
                  
		$query = $this->db->query($sql);
                
		return $query->row;
        }
		
		////////cash new////////////

public function getCash_reportByRunner_mid($data = array()) {
	   
            $sql="SELECT 
    oc_bank_transaction.*,
    oc_user.firstname,
    oc_user.lastname,
	oc_store.name as store_name
FROM
    oc_bank_transaction
        LEFT JOIN
    oc_user ON oc_user.user_id = oc_bank_transaction.accept_by
LEFT JOIN
    oc_store ON oc_store.store_id = oc_bank_transaction.store_id
WHERE
    oc_bank_transaction.bank_name != '' and DATE(oc_bank_transaction.date_added) >='2018-03-10' and DATE(oc_bank_transaction.date_added) <='2018-04-03'  ";
                  
                            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_bank_transaction.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_transaction.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND oc_bank_transaction.accept_by = '" . $this->db->escape($data['filter_user']) . "'";
		}
		        if (!empty($data['filter_status'])) {
			$sql .= " and oc_bank_transaction.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
		
            //$sql.=" and oc_bank_transaction.status>0
        $sql.="and oc_bank_transaction.cash_slip!='' ";
            
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
              // echo $sql;
		return $query->rows; 
	}

	public function getTotalCash_transationByRunner_mid($data = array()) {
		
            $sql="select count(*) as total,sum(amount) as total_amount from (SELECT 
    oc_bank_transaction.*,
    oc_user.firstname,
    oc_user.lastname,
	oc_store.name as store_name
FROM
    oc_bank_transaction
        LEFT JOIN
    oc_user ON oc_user.user_id = oc_bank_transaction.accept_by
LEFT JOIN
    oc_store ON oc_store.store_id = oc_bank_transaction.store_id
WHERE
    oc_bank_transaction.bank_name != '' and DATE(oc_bank_transaction.date_added) >='2018-03-10' and DATE(oc_bank_transaction.date_added) <='2018-04-03'  ";
                  
                            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_bank_transaction.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_transaction.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                            if (!empty($data['filter_user'])) {
			$sql .= " AND oc_bank_transaction.accept_by = '" . $this->db->escape($data['filter_user']) . "'";
		}
		        if (!empty($data['filter_status'])) {
			$sql .= " and oc_bank_transaction.status = '" . $this->db->escape($data['filter_status']) . "'";
		}
		
            //$sql.=" and oc_bank_transaction.status>0
        $sql.="and oc_bank_transaction.cash_slip!='' ";
                $sql.=" ) as aa";
		$query = $this->db->query($sql);
		//echo $sql;
		return $query->row;
	}

public function accept_reject_cash_mid($tr_id,$logged_user,$status,$bank_tr_number=null) {
	
		$date_updated=date('Y-m-d h:i:s');
            $sql="update `oc_bank_transaction` set `status`='".$status."',`verified_by_account`='".$logged_user."',`verified_time`='".$date_updated."',bank_tr_number ='".$bank_tr_number."'  where  `transid`='".$tr_id."' ";
                  $log=new Log("cash-new".date('Y-m-d').".log");
            $log->write($sql);
            //echo $sql;
		$query = $this->db->query($sql);

		
	}

	
}