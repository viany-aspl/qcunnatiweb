<?php
class ModelTaggedbillBill extends Model {
	
	
        public function getReport($data = array()) {
	   
            $sql="SELECT oc_tagged_bill_trans.*,oc_user.firstname,oc_user.lastname,ocu.firstname as a_firstname,ocu.lastname as a_lastname,oc_store.name as store_name,oc_unit.unit_name as unit "
                    . "FROM oc_tagged_bill_trans "
                    . " left join oc_user on oc_user.user_id=oc_tagged_bill_trans.creted_user join oc_store on oc_store.store_id=oc_tagged_bill_trans.store_id join oc_unit on  oc_unit.unit_id=oc_tagged_bill_trans.unit_id  left join oc_user as ocu on ocu.user_id=oc_tagged_bill_trans.accepted_user where oc_tagged_bill_trans.`unit_id`!='' ";
                           
                            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_tagged_bill_trans.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_tagged_bill_trans.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
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

	public function getTotalReport($data = array()) {
		
            $sql="select count(*) as total from ( SELECT oc_tagged_bill_trans.*,oc_user.firstname,oc_user.lastname,oc_store.name as store_name,oc_unit.unit_name as unit "
                    . "FROM oc_tagged_bill_trans "
                    . " left join oc_user on oc_user.user_id=oc_tagged_bill_trans.creted_user join oc_store on oc_store.store_id=oc_tagged_bill_trans.store_id join oc_unit on  oc_unit.unit_id=oc_tagged_bill_trans.unit_id where oc_tagged_bill_trans.`unit_id`!='' ";
                           
                            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_tagged_bill_trans.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_tagged_bill_trans.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            
                $sql.=" ) as aa";
		$query = $this->db->query($sql);
                            //echo $sql;
		return $query->row;
	}
public function accept_reject_cash($bill_id,$logged_user,$status) {
	
            $date_updated=date('Y-m-d');
            $sql="update `oc_tagged_bill_trans` set `status`='".$status."',`accepted_user`='".$logged_user."',`accepted_date`='".$date_updated."'  where  `sid`='".$bill_id."' ";
                  
            //echo $sql;
		$query = $this->db->query($sql);

		
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
	
}