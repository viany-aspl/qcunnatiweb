<?php
class ModelPayoutPayoutdtl extends Model {

        public function getAllUnits()
	{
		$query = $this->db->query('SELECT * FROM oc_unit ');
                return $query->rows;
		
	}
        public function getStores()
	{
		$query = $this->db->query('SELECT store_id,name FROM oc_store');
		return $query->rows;
	}
        public function insrtPayoutdtl($data,$updated_by) 
        {
             $sql="insert into  oc_payout_dtl set store_id='".$data["store"]."',user_id='".$updated_by."',amount='".$data['amount']."',transaction_type='".$data['transaction_type']."',payment_method='".$data['payment_method']."',unit_id='".$data['unit']."',tr_number='".$data['tr_number']."' ";
             $query = $this->db->query($sql);
             $insert_id=$this->db->getLastId();

             if($data['transaction_type']=='Credit Posting' || $data['transaction_type']=='Waiver Subsidy' )
             {
              $sql2="update  oc_store set currentcredit=currentcredit +'".$data["amount"]."' where store_id='".$data['store']."'";
              $query2 = $this->db->query($sql2);

		try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addstoretrans($data["amount"],$data['store'],'','CR',$insert_id,$data['transaction_type'],$data["amount"],$data['transaction_type']);  
		$trans->addwalletcredit($data['store'],'1','Credit Posting',$data["amount"],$insert_id,'Credit Posting for the Store via web');       
   
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
             }
             if($data['transaction_type']=='Debit') {
             $sql2="update  oc_store set currentcredit=currentcredit -'".$data["amount"]."' where store_id='".$data['store']."'";
             $query2 = $this->db->query($sql2);
             }
        }
        public function getPayoutList($data)
{
$sql='SELECT od.amount,od.transaction_type,od.tr_number,DATE(od.create_date) as create_date,od.payment_method,store.name,user.firstname,user.lastname FROM `oc_payout_dtl` as od
LEFT JOIN oc_store as store on store.store_id=od.store_id
LEFT JOIN oc_user as user on user.user_id=od.user_id';

if (!empty($data['filter_stores_id'])) {
$sql .= " WHERE od.store_id= '" . (int)$data['filter_stores_id'] . "'";
} else {
$sql .= " WHERE od.store_id > '0'";
}
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$sql.=" order by sid desc ";

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
$query= $this->db->query($sql);
return $query->rows;
}
public function getTotalPayoutList($data)
{
$sql='select count(*) as total from (SELECT od.amount,od.transaction_type,DATE(od.create_date) as create_date,od.payment_method,store.name,user.firstname,user.lastname FROM `oc_payout_dtl` as od
LEFT JOIN oc_store as store on store.store_id=od.store_id
LEFT JOIN oc_user as user on user.user_id=od.user_id';
if (!empty($data['filter_stores_id'])) {
$sql .= " WHERE od.store_id= '" . (int)$data['filter_stores_id'] . "'";
} else {
$sql .= " WHERE od.store_id > '0'";
}
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
$sql.=" ) as aa";
//echo $sql; 
$query= $this->db->query($sql);
return $query->row['total'];
}
        
        
}
?>