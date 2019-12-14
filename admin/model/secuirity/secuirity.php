<?php

class ModelSecuiritySecuirity extends Model {

    public function addsecuirity($data) {
      $log=new Log('security-'.date('Y-m-d').".log");
                $sql2="insert into  oc_sequirity_deposit set store_id='".$data["filter_store"]."',bank_id='".$data["filter_bank"]."',ifsc_code='".$data['ifsc']."',check_no='".$data['chequeno']."',amount='".$data['amount']."',cheque_issue_date='".$data['dateadded']."',remarks='".$data['remarks']."'  ";
$log->write($sql2);
		$query2 = $this->db->query($sql2);
    }


    public function getsecuirity($data = array()) {

        $sql = "SELECT os.name as store_name,ob.bank_name,oseq.ifsc_code,oseq.check_no,oseq.amount,oseq.cheque_issue_date,oseq.remarks 
FROM oc_sequirity_deposit as oseq
JOIN oc_store as os on os.store_id=oseq.store_id
JOIN oc_bank_list as ob on ob.sid=oseq.bank_id ";
        if(!empty($data['filter_store']))
	{
		$sql.=" where oseq.store_id='".$data['filter_store']."' ";
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
        //echo $sql;//exit;
        return $query->rows;
    }
    
    public function getTotalsecuirity($data = array()) {
	$sql="SELECT COUNT(*) AS total FROM (SELECT os.name as store_name,ob.bank_name,oseq.ifsc_code,oseq.check_no,oseq.amount,oseq.cheque_issue_date 
FROM oc_sequirity_deposit as oseq
JOIN oc_store as os on os.store_id=oseq.store_id
JOIN oc_bank_list as ob on ob.sid=oseq.bank_id) as aa ";
	
        	$query = $this->db->query($sql);

        	return $query->row['total'];
    }
public function getstorebyunitid($unit_id)
{
$sql = "SELECT oc_store.* FROM " . DB_PREFIX . "store_to_unit left join oc_store on oc_store_to_unit.store_id=oc_store.store_id where oc_store_to_unit.unit_id='".$unit_id."' ";

$query = $this->db->query($sql);
// echo $query->row['name'];
return $query->rows;
}

   public function getStores() {

        $sql = "SELECT  * FROM " . DB_PREFIX . "store";
	 
        $query = $this->db->query($sql);
       
        return $query->rows;
    }
       public function getBankList() {

        $sql = "SELECT  * FROM " . DB_PREFIX . "bank_list";
	 
        $query = $this->db->query($sql);
       
        return $query->rows;
    }


   
}
