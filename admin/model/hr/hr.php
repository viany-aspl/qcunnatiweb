<?php
class ModelHrHr extends Model {
    


////////////////////////////////////////////////////////////////



        public function billsubmmision($data = array(),$file_name)
        {
            $date_time=date('Y-m-d h:i:s');
            $sql="insert into `oc_hr_expense` set `logged_user`='".$data["logged_user"]."',`store_id`='".$data["filter_store"]."',`start_date`='".$data["period_date_start"]."',
	`end_date`='".$data["period_date_end"]."',`filter_month`='".$data["filter_month"]."',`filter_year`='".$data["filter_year"]."',`submitby`='".$data["submitby"]."',
	`approvedby`='".$data["approvedby"]."',`amount`='".$data["amount"]."',`file`='".$file_name."' ,`remarks`='".$data["remarks"]."' ";
            	//echo $sql;
              $log=new Log("tag-".date('Y-m-d').".log");
	$log->write($sql);

	$query = $this->db->query($sql);
              $insertid=$this->db->getLastId();
              $log->write("insertid-".$insertid);
             return $insertid;
   
        }
        
        public function getTotalbill($data = array()) {
		$sql = "select count(*) as total from ( select oc_hr_expense.*,oc_store.name as store_name  from oc_hr_expense left join oc_store on oc_store.store_id=oc_hr_expense.store_id  where oc_hr_expense.`status` in ('0','1','2')";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(start_date) >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(end_date) <= '" . $data['filter_date_end'] . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_hr_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                            if ($data['filter_status']!="") {
			$sql .= " AND oc_hr_expense.status= '" . $this->db->escape($data['filter_status']) . "' ";
		}
		
		
                
                	$sql.=" ) as aa";
		$log=new Log("hr-".date('Y-m-d').".log");
		$log->write($sql);
                
                            //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
        public function getBills($data = array()) {
	        $sql = "select oc_hr_expense.*,oc_store.name as store_name  from oc_hr_expense left join oc_store on oc_store.store_id=oc_hr_expense.store_id  where oc_hr_expense.`status` in ('0','1','2')";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(start_date) >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(end_date) <= '" . $data['filter_date_end'] . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_hr_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                            if ($data['filter_status']!="") {
			$sql .= " AND oc_hr_expense.status= '" . $this->db->escape($data['filter_status']) . "' ";
		}
                           
		 $sql.="  order by SID desc";
		if (isset($data['start']) || isset($data['limit'])) { 
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                	$log=new Log("hr-".date('Y-m-d').".log");
		$log->write($sql);
                
               	//echo $sql; 
                                
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	public function getExpenseReasons()
	{
		$sql = " select * from oc_expense_reason order by reason asc";
           		$query = $this->db->query($sql);
		return $query->rows;
	}
        public function update_reason($bill_id,$expense_reason,$other_Message)
        {
	echo $sql = "update oc_expense set reason='".$expense_reason."',descr='".$other_Message."' where `SID`='".$bill_id."' ";
             // return 1;
              return $query = $this->db->query($sql);
        }
        public function acceptBill($bill_id)
        {
            $sql = "update oc_hr_expense set status='1' where `SID`='".$bill_id."' ";
            return $query = $this->db->query($sql);
            //return 0;
        }
        public function rejectBill($bill_id,$reject_message)
        {
            
            echo $sql = "update oc_hr_expense set `status`='2',`reject_message`='".$reject_message."' where `SID`='".$bill_id."' ";
            return $query = $this->db->query($sql);
        }
        public function getEmailofUser($logged_user)
        {
            
            $sql = "select firstname,lastname,email from oc_user where `user_id`='".$logged_user."' ";
            $query = $this->db->query($sql);
            return $query->row;
            
        }
        public function billsubmmision_update_file($data = array(),$file_name)
        {
            
              $sql="update `tagged_bill_submission` set  `uploded_file`='$file_name'  where `SID`='".$data["billid"]."' ";
	$log=new Log("tag-".date('Y-m-d').".log");
	$log->write($sql);
	$query = $this->db->query($sql);
              
        }

	public function getTotalbillapp($data = array()) {
		$sql = "select count(*) as total from ( select oc_expense.SID  from oc_expense left join oc_store on oc_store.store_id=oc_expense.store_id left join oc_expense_reason on oc_expense_reason.sid=oc_expense.reason  where oc_expense.`status` in (0,1,2) ";

//SELECT * FROM `oc_expense` 
		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(create_time) >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(create_time) <= '" . $data['filter_date_end'] . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                            
                           
		 $sql.="  order by SID desc ) as aa";
		
                	$log=new Log("hr-".date('Y-m-d').".log");
		$log->write($sql);
                
               	//echo $sql; 
                                
		$query = $this->db->query($sql);
		
		return $query->row["total"];
	}
        
        public function getBillsapp($data = array()) {
	        $sql = "select oc_expense.*,oc_store.name as store_name,oc_user.firstname,oc_user.lastname,oc_expense_reason.reason as reason,oc_expense.descr  from oc_expense left join oc_store on oc_store.store_id=oc_expense.store_id left join oc_expense_reason on oc_expense_reason.sid=oc_expense.reason left join oc_user on oc_user.user_id=oc_expense.employee_id  where oc_expense.`status` in (0,1,2) ";

//SELECT * FROM `oc_expense` 
		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(create_time) >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(create_time) <= '" . $data['filter_date_end'] . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                            
                           
		 $sql.="  order by SID desc";
		if (isset($data['start']) || isset($data['limit'])) { 
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                	$log=new Log("hr-".date('Y-m-d').".log");
		$log->write($sql);
                
               	//echo $sql; 
                                
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
        public function acceptBillapp($bill_id)
        {
            $sql = "update oc_expense set status='1' where `SID`='".$bill_id."' ";
            return $query = $this->db->query($sql);
            //return 0;
        }
        public function rejectBillapp($bill_id,$reject_message)
        {
            //$sql = "update oc_expense set `status`='2',`message`='".$reject_message."' where `SID`='".$bill_id."' ";
            //return $query = $this->db->query($sql);

	$logged_user_data="";
          	date_default_timezone_set('Asia/Kolkata');
          	$sql = "update oc_expense set status=2,update_time ='".date('Y-m-d h:i:s')."',message='".$reject_message."',update_by='".$logged_user_data."' where `SID`='".$bill_id."' ";
          	return $query = $this->db->query($sql);
        }

	public function getTotalreimbursementapp($data = array()) {
		$sql = "select count(*) as total from ( select oc_expense.SID  from oc_expense left join oc_store on oc_store.store_id=oc_expense.store_id left join oc_expense_reason on oc_expense_reason.sid=oc_expense.reason  where oc_expense.`status` in (3,4,5) ";

//SELECT * FROM `oc_expense` 
		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(create_time) >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(create_time) <= '" . $data['filter_date_end'] . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                            
                           
		 $sql.="  order by SID desc ) as aa";
		
                	$log=new Log("hr-".date('Y-m-d').".log");
		$log->write($sql);
                
               	//echo $sql; 
                                
		$query = $this->db->query($sql);
		
		return $query->row["total"];
	} 
        
        public function getreimbursementapp($data = array()) {
	        $sql = "select oc_expense.*,oc_store.name as store_name,oc_user.firstname,oc_user.lastname,concat(oc_expense_reason.reason,' ',oc_expense.descr) as reason  from oc_expense left join oc_store on oc_store.store_id=oc_expense.store_id left join oc_expense_reason on oc_expense_reason.sid=oc_expense.reason left join oc_user on oc_user.user_id=oc_expense.employee_id  where oc_expense.`status` in (3,4,5) ";

//SELECT * FROM `oc_expense` 
		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(create_time) >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(create_time) <= '" . $data['filter_date_end'] . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                            
                           
		 $sql.="  order by SID desc";
		if (isset($data['start']) || isset($data['limit'])) { 
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                	$log=new Log("hr-".date('Y-m-d').".log");
		$log->write($sql);
                
               	//echo $sql; 
                                
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

        public function accept_reimbursement($bill_id)
        {
            $sql = "update oc_expense set status='4' where `SID`='".$bill_id."' ";
            return $query = $this->db->query($sql);
            //return 0;
        }
        public function reject_reimbursement($bill_id,$reject_message)
        {
            //$sql = "update oc_expense set `status`='5',`reimbursement_message`='".$reject_message."' where `SID`='".$bill_id."' ";
            //return $query = $this->db->query($sql);

	$logged_user_data="";
          	date_default_timezone_set('Asia/Kolkata');
          	$sql = "update oc_expense set status=5,update_time ='".date('Y-m-d h:i:s')."',reimbursement_message='".$reject_message."',update_by='".$logged_user_data."' where `SID`='".$bill_id."' ";
          	return $query = $this->db->query($sql);
        }
	public function getBills_companywise($data = array()) {
	        $sql = "select oc_hr_expense.*,oc_store.name as store_name  "
                        . "from oc_hr_expense "
                        . "left join oc_store on oc_store.store_id=oc_hr_expense.store_id "
                        . " where oc_hr_expense.`status` in ('0','1','2')";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(start_date) >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(end_date) <= '" . $data['filter_date_end'] . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_hr_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                            if ($data['filter_status']!="") {
			$sql .= " AND oc_hr_expense.status= '" . $this->db->escape($data['filter_status']) . "' ";
		}
                 $sql .= " and oc_store.company_id='".$data['filter_company']."' ";          
		 $sql.="  order by SID desc";
		if (isset($data['start']) || isset($data['limit'])) { 
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                	$log=new Log("hr-".date('Y-m-d').".log");
		$log->write($sql);
                
               //	echo $sql; 
                                
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
        
             public function getTotalbill_companywise($data = array()) {
		$sql = "select count(*) as total from ( select oc_hr_expense.*,oc_store.name as store_name  from oc_hr_expense left join oc_store on oc_store.store_id=oc_hr_expense.store_id  where oc_hr_expense.`status` in ('0','1','2')";

		

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND date(start_date) >= '" . $data['filter_date_start'] . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND date(end_date) <= '" . $data['filter_date_end'] . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_hr_expense.store_id= '" . $this->db->escape($data['filter_store']) . "' ";
		}
                            if ($data['filter_status']!="") {
			$sql .= " AND oc_hr_expense.status= '" . $this->db->escape($data['filter_status']) . "' ";
		}
		
		$sql .= " and oc_store.company_id='".$data['filter_company']."' ";  
                
                	$sql.=" ) as aa";
		$log=new Log("hr-".date('Y-m-d').".log");
		$log->write($sql);
                
                            //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
public function UpdateStoreExpense($data)
 {
 $log=new Log("expense-".date('Y-m-d').".log");
 //return $query = $this->db->query($sql);
 $sql1=" update oc_store set expense_balance=expense_balance-".$data['amount']." where store_id=".$data['store_id'];
 $log->write($sql1);
 
 $sql = "update oc_expense set `status`='3' where `SID`='".$data['tr_id']."' ";
  $query1 = $this->db->query($sql);
  $log->write($sql);
 return $query = $this->db->query($sql1);
 
 
 }

}