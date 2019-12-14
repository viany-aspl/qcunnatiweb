<?php
class ModelExpenseExpense extends Model {
    
         public function getReasons()
        {
            
            $sql = " select * from oc_expense_reason ";
            $query = $this->db->query($sql);
            return $query->rows;
        }
        public function billsubmmision($data = array(),$file_name)
        {
            $date_time=date('Y-m-d h:i:s');
            $sql="insert into `oc_expense` set `store_id`='".$data["filter_store"]."',`employee_name`='".$data["employee_name"]."',`employee_id`='".$data["employee_id"]."',`amount`='".$data["amount"]."',`reason`='".$data["reason"]."',`exepense_date`='".$data["exepense_date"]."',`bill_pic`='$file_name',`descr`='".$data["desc"]."',`billattched`='".$data["billattched"]."' ";
            	//echo $sql;
	$log=new Log("expense-".date('Y-m-d').".log");
	$log->write($sql);
	$query = $this->db->query($sql);
              $insertid=$this->db->getLastId();
              $log->write("insertid-".$insertid);
              return $insertid;
        }
        public function billsubmmision_update_file($data = array(),$file_name)
        {
            
              $sql="update `oc_expense` set  `bill_pic`='$file_name'  where `SID`='".$data["billid"]."' ";
	$log=new Log("expense-".date('Y-m-d').".log");
	$log->write($sql);
	$query = $this->db->query($sql);
              
        }
        public function billsubmmision_update_status($data = array())
        {
            
              $sql="update `oc_expense` set  `status`='".$data["status"]."',`remarks`='".$data["remark"]."'  where `SID`='".$data["billid"]."' ";
	$log=new Log("expense-".date('Y-m-d').".log");
	$log->write($sql);
	$query = $this->db->query($sql); 
              
        }
        

        public function getUsers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . "user c where status='1' and  user_id!='1'  ";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}
		
		if (isset($data['filter_store_id']) && !is_null($data['filter_store_id'])) {
			$implode[] = "c.store_id = '" . (int)$data['filter_store_id'] . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY firstname";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

		return $query->rows;
	}
        public function getTotalbill($data = array()) {
		$sql = "select count(*) as total from (select oc_expense.employee_id,oc_store.name as store_name,oc_user.firstname,oc_user.lastname  from oc_expense left join oc_store on oc_store.store_id=oc_expense.store_id join oc_user on oc_user.user_id=oc_expense.employee_id  where oc_expense.status in ('0','1','2')";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND exepense_date >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND exepense_date <= '" . $data['filter_date_end'] . "'";
		}

		
		
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                if (!empty($data['filter_reason'])) {
			$sql .= " AND oc_expense.reason= '" . $this->db->escape($data['filter_reason']) . "' ";
		}
		
                $sql.=" ) as aa";
                               //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
        public function getBills($data = array()) {
	        $sql = "select oc_expense.*,oc_store.name as store_name,oc_user.firstname,oc_user.lastname,oc_expense_reason.reason as reason_txt  from oc_expense left join oc_store on oc_store.store_id=oc_expense.store_id join oc_user on oc_user.user_id=oc_expense.employee_id left join oc_expense_reason on oc_expense_reason.sid=oc_expense.reason  where oc_expense.status in ('0','1','2')"; 

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND exepense_date >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND exepense_date <= '" . $data['filter_date_end'] . "'";
		}

		
		
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                if (!empty($data['filter_reason'])) {
			$sql .= " AND oc_expense.reason= '" . $this->db->escape($data['filter_reason']) . "' ";
		}
		 $sql.="  order by sid desc";
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
                $log=new Log("expense-".date('Y-m-d').".log");
	  $log->write($sql);
                $query = $this->db->query($sql);
	  return $query->rows;
	}
        public function getmyBills($data = array()) {
	        $sql = "select oc_expense.*,oc_store.name as store_name,oc_user.firstname,oc_user.lastname,concat(oc_expense_reason.reason,' ',oc_expense.descr) as reason_txt  from oc_expense left join oc_store on oc_store.store_id=oc_expense.store_id join oc_user on oc_user.user_id=oc_expense.employee_id left join oc_expense_reason on oc_expense_reason.sid=oc_expense.reason  where oc_expense.status in ('0','1','2') and `employee_id`='".$data["user"]."'  ";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND exepense_date >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND exepense_date <= '" . $data['filter_date_end'] . "'";
		}

		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                           if (!empty($data['filter_reason'])) {
			$sql .= " AND oc_expense.reason= '" . $this->db->escape($data['filter_reason']) . "' ";
		}
		 $sql.="  order by sid desc";
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
                $log=new Log("expense-".date('Y-m-d').".log");
	  $log->write($sql);
                $query = $this->db->query($sql);
	  return $query->rows;
	}
	public function getmyBillsreimbursement($data = array()) {
	        $sql = "select oc_expense.*,oc_store.name as store_name,oc_user.firstname,oc_user.lastname,oc_expense_reason.reason as reason_txt  from oc_expense left join oc_store on oc_store.store_id=oc_expense.store_id join oc_user on oc_user.user_id=oc_expense.employee_id left join oc_expense_reason on oc_expense_reason.sid=oc_expense.reason  where  `employee_id`='".$data["user"]."' and oc_expense.status in (3,4,5) "; 

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND exepense_date >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND exepense_date <= '" . $data['filter_date_end'] . "'";
		}

		
		
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                if (!empty($data['filter_reason'])) {
			$sql .= " AND oc_expense.reason= '" . $this->db->escape($data['filter_reason']) . "' ";
		}
		 $sql.="  order by sid desc";
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
                $log=new Log("expense-".date('Y-m-d').".log");
	  $log->write($sql);
                $query = $this->db->query($sql);
	  return $query->rows;
	}
        public function accept_rejectBill($sid,$Message,$status,$logged_user_data)
        {
          date_default_timezone_set('Asia/Kolkata');
          $sql = "update oc_expense set status='".$status."',update_time ='".date('Y-m-d h:i:s')."',message='".$Message."',update_by='".$logged_user_data."' where `SID`='".$sid."' ";
          return $query = $this->db->query($sql);
            //return 0;
        }
        
        
}