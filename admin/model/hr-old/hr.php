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
}