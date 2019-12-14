<?php
class ModelFactoryPaymentdtl extends Model {

        public function getAllUnits()
	{
		$query = $this->db->query('SELECT * FROM oc_unit ');
                return $query->rows;
		
	}
          public function getAllCompanys()
	{
		$query = $this->db->query('SELECT * FROM oc_company ');
                return $query->rows;
		
	}
        public function getunitbycompany($cid){
$sql="SELECT unit_id,unit_name from oc_unit WHERE company_id='".$cid."' ";
$query = $this->db->query($sql);

//echo $sql;
return $query->rows; 
}
        public function getStores()
	{
		$query = $this->db->query('SELECT store_id,name FROM oc_store');
		return $query->rows;
	}
    public function insrtPaymentdtl($data,$updated_by) 
        {
       	$log=new log('factory_payment'.date('Y-m').'.log');
	$log->write($data["amount"]);
	$data["amount"]=str_replace(",","",$data["amount"]);
	$log->write($data["amount"]);
            $sql1="update  oc_unit set wallet_balance=wallet_balance +".$data["amount"]." where company_id='".$data['company']."' and unit_id='".$data['unit']."'";
            $query1 = $this->db->query($sql1);
	$log->write($sql1);
            //exit;
            $query = $this->db->query("SELECT wallet_balance FROM oc_unit where company_id='".$data['company']."' and unit_id='".$data['unit']."'");
            $available_balance= $query->row['wallet_balance'];
        $log->write($available_balance);
        
            $sql="insert into  oc_unit_cash_trans set  recieve_date='".$data["recieve_date"]."',store_id='".$data["store"]."',user_id='".$updated_by."',amount='".$data['amount']."',transaction_type='".$data['transaction_type']."',payment_method='".$data['payment_method']."',unit_id='".$data['unit']."',tr_number='".$data['tr_number']."',company_id='".$data['company']."',available_balance='".$available_balance."',total_amount='".$data['amount']."',cr_db='CR',bank='".$data['payment_bank']."' ";
            $query = $this->db->query($sql);
            $insert_id=$this->db->getLastId();
	$log->write($sql);
        }
        public function getPaymentList($data)
{
$sql='SELECT unit.unit_name,od.bank,od.recieve_date,od.amount,company.company_name,od.transaction_type,od.tr_number as transaction_no,DATE(od.create_date) as create_date,od.payment_method,user.firstname,user.lastname FROM `oc_unit_cash_trans` as od
LEFT JOIN oc_unit as unit on unit.unit_id=od.unit_id
LEFT JOIN oc_company as company on company.company_id=od.company_id
LEFT JOIN oc_user as user on user.user_id=od.user_id where od.unit_id!="" and od.transaction_type="Credit"';
                         if (!empty($data['filter_company']) ) 
                        {
                            $sql .=" and od.company_id='".$data['filter_company']."'";
			
                        }
						if (!empty($data['filter_unit']) ) 
                        {
                            $sql .=" and od.unit_id='".$data['filter_unit']."'";
			
                        }
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$sql.=" order by od.sid desc ";

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
public function getTotalPaymentList($data)
{
$sql='select count(*) as total , sum(amount) as amounttotal from (SELECT unit.unit_name,od.amount,od.transaction_type,DATE(od.create_date) as create_date,od.payment_method,user.firstname,user.lastname FROM `oc_unit_cash_trans` as od
LEFT JOIN oc_unit as unit on unit.unit_id=od.unit_id
LEFT JOIN oc_user as user on user.user_id=od.user_id where od.transaction_type="Credit" ';
  if (!empty($data['filter_company']) ) 
                        {
                            $sql .=" and od.company_id='".$data['filter_company']."'";
			
                        }
						if (!empty($data['filter_unit']) ) 
                        {
                            $sql .=" and od.unit_id='".$data['filter_unit']."'";
			
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
return $query->row;
}
 

        public function downloadgetPaymentList($data)
{
$sql='SELECT unit.unit_name,od.bank,od.amount,company.company_name,od.transaction_type,od.tr_number as transaction_no,DATE(od.create_date) as create_date,od.payment_method,user.firstname,user.lastname FROM `oc_unit_cash_trans` as od
LEFT JOIN oc_unit as unit on unit.unit_id=od.unit_id
LEFT JOIN oc_company as company on company.company_id=od.company_id
LEFT JOIN oc_user as user on user.user_id=od.user_id where od.unit_id!="" and od.transaction_type="Credit"';
                         if (!empty($data['filter_company']) )
                        {
                            $sql .=" and od.company_id='".$data['filter_company']."'";
   
                        }
      if (!empty($data['filter_unit']) )
                        {
                            $sql .=" and od.unit_id='".$data['filter_unit']."'";
   
                        }
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$sql.=" order by od.sid desc ";

//echo $sql;
$query= $this->db->query($sql);
return $query->rows;
}

 


        
}
?>