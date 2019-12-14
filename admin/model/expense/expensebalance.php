<?php
class ModelExpenseExpensebalance extends Model {

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
        public function insrtexpensebalancedtl($data,$updated_by) 
        {
            $log=new Log("expenceblance-".date('Y-m-d').".log");
            $log->write($data); 
            $sql="insert into  expense_balance set unit='".$data["unit"]."',store='".$data['store']."',amount='".$data['amount']."',transaction_no='".$data['transaction_no']."',payment_method='".$data['payment_method']."',bank_name='".$data['bank_name']."',account_no='".$data['accountno']."',ifsc_code='".$data['ifsc']."',account_name='".$data['account_name']."',user_id='".$updated_by."',cr_date=NOW() ";
            $log->write($sql); 
            $query = $this->db->query($sql);
            
            $updatesql="update  oc_store set expense_balance=expense_balance+'".$data['amount']."' where store_id='".$data['store']."'";
            $updatequery = $this->db->query($updatesql);
             $log->write($updatesql); 
        }
        public function getexpenseList($data)
{
$sql='SELECT eb.*,DATE(eb.cr_date) as create_date,store.name as store,store.expense_balance,user.firstname,user.lastname FROM `expense_balance` as eb
LEFT JOIN oc_store as store on store.store_id=eb.store
LEFT JOIN oc_user as user on user.user_id=eb.user_id';
if (!empty($data['filter_stores_id'])) {
$sql .= " WHERE eb.store= '" . (int)$data['filter_stores_id'] . "'";
} else {
$sql .= " WHERE eb.store > '0'";
}
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(eb.cr_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(eb.cr_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$sql.="order by eb.sid desc  ";

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
public function getTotalexpenseList()
{
$sql='select count(*) as total from (SELECT eb.* FROM `expense_balance` as eb
LEFT JOIN oc_store as store on store.store_id=eb.store
LEFT JOIN oc_user as user on user.user_id=eb.user_id';
if (!empty($data['filter_stores_id'])) {
$sql .= " WHERE eb.store= '" . (int)$data['filter_stores_id'] . "'";
} else {
$sql .= " WHERE eb.store > '0'";
}
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(eb.cr_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(eb.cr_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$sql.="order by eb.sid desc  ";
$sql.=" ) as aa";
//echo $sql;
$query= $this->db->query($sql);
return $query->row['total'];
}
        
        
}
?>