<?php
class ModelLeadgerCash extends Model {
	
public function getCash_reportRunner($data = array()) {
	   

            $sql="SELECT oc_bank_deposit_runner.*,oc_user.firstname,oc_user.lastname  from oc_bank_deposit_runner left join oc_user on oc_user.user_id=oc_bank_deposit_runner.user_id where oc_bank_deposit_runner.bank!='' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_bank_deposit_runner.submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_deposit_runner.submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
            $sql.=" Order by oc_bank_deposit_runner.SID desc ";
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

	public function getTotalCash_transationRunner($data = array()) {
		
            $sql="select count(*) as total from (SELECT oc_bank_deposit_runner.*,oc_user.firstname,oc_user.lastname  from oc_bank_deposit_runner left join oc_user on oc_user.user_id=oc_bank_deposit_runner.user_id where oc_bank_deposit_runner.bank!='' ";
                  
            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(oc_bank_deposit_runner.submit_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_bank_deposit_runner.submit_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
            $sql.=" Order by oc_bank_deposit_runner.SID desc ) as aa"; 

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

////////////////////////////
	public function getCash_report($data = array()) {
	   //print_r($data);
//SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt RIGHT JOIN oc_store on oc_store.store_id=obt.store_id  WHERE DATE(obt.date_added) >= '2016-10-30' AND DATE(obt.date_added) <= '2017-01-18'
            $sql="SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name,oc_user.firstname,oc_user.lastname,obt.status FROM `oc_bank_transaction` as obt "
                    . "LEFT JOIN oc_store on oc_store.store_id=obt.store_id left join oc_user on oc_user.user_id=obt.accept_by where obt.bank_name!='' and obt.status='1'  ";
                  
            if (!empty($data['filter_start_date'])) {
			$sql .= " and DATE(obt.date_added) >= '" . $this->db->escape($data['filter_start_date']) . "'";
		}

		if (!empty($data['filter_end_date'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_end_date']) . "'";
		}
            		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
            $sql.=" Order by obt.transid desc ";
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                           // echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

	public function getTotalCash_transation($data = array()) {
		
            $sql="SELECT count(obt.transid) as total FROM `oc_bank_transaction` as obt where obt.bank_name!='' ";
                    //. "WHERE DATE(obt.date_added) >= '2016-10-30' "
                    //. "AND DATE(obt.date_added) <= '2017-01-18'";
            
            if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

              public function get_bank_sum_cash($data=array())
	{
                     $sql="select max(case when bank_name='HDFC' then  bank_amount else 00.00 end)as HDFC,
max(case when bank_name='State Bank of India' then  bank_amount else 00.00 end)as State_Bank_of_India,
max(case when bank_name='ICICI' then  bank_amount else 00.00 end)as ICICI,
max(case when bank_name='TAGGED BILLS' then  bank_amount else 00.00 end)as TAGGED_BILLS

from (

SELECT obt.bank_name,sum(obt.amount)as bank_amount FROM `oc_bank_transaction` as obt
 LEFT JOIN oc_store on oc_store.store_id=obt.store_id left join
 oc_user on oc_user.user_id=obt.accept_by where obt.bank_name!='' and obt.status='1' 
  ";
            
            if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(obt.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(obt.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                		if (!empty($data['filter_store'])) {
			$sql .= " AND obt.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
                $sql.=" group by obt.bank_name Order by obt.transid  )as a ";
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	public function getCash_position($data = array()) {
	   
            $sql="SELECT * FROM `oc_cash_store_position` ";
                  
            if (!empty($data['filter_store'])) {
			$sql .= " where store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
                
                if (!empty($data['filter_date'])) {
                      if (!empty($data['filter_store'])) 
                      {
			$sql .= " and DATE(update_date)= '" . $this->db->escape($data['filter_date']) . "'";
		      }
                      else
                      {
                          $sql .= " where DATE(update_date)= '" . $this->db->escape($data['filter_date']) . "'";
                      }
			
		}

		
            $sql.=" Order by SID desc ";
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

	public function getTotalCash_position($data = array()) {
		
            $sql="SELECT count(*) as total,sum(amount) as total_amount FROM (SELECT * FROM `oc_cash_store_position` ";
                  
            if (!empty($data['filter_store'])) {
			$sql .= " where store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
                 if (!empty($data['filter_date'])) {
                      if (!empty($data['filter_store'])) 
                      {
			$sql .= " and DATE(update_date)= '" . $this->db->escape($data['filter_date']) . "'";
		      }
                      else
                      {
                          $sql .= " where DATE(update_date)= '" . $this->db->escape($data['filter_date']) . "'";
                      }
			
		}
                $sql.=" ) as aa";
		$query = $this->db->query($sql);

		return $query->row;
	}

	  
}