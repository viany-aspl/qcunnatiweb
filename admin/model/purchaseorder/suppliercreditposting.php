<?php
class ModelPurchaseorderSuppliercreditposting extends Model {

        
        public function getPostingList($data)
{
$sql="SELECT od.amount,od.transaction_type,DATE(od.create_date) as create_date,
    od.payment_method, concat(oc_po_supplier.first_name,' ',oc_po_supplier.last_name) as name,
	user.firstname,user.lastname 
	FROM `oc_supplier_credit_posting` as od
LEFT JOIN oc_po_supplier as oc_po_supplier on oc_po_supplier.id=od.supplier_id

LEFT JOIN oc_user as user on user.user_id=od.user_id";
if (!empty($data['filter_supplier'])) 
{
$sql .= " WHERE od.supplier_id= '" . (int)$data['filter_supplier'] . "'";
} 
else 
{
$sql .= " WHERE od.supplier_id > '0'";
}
$sql .= " and od.entry_type!= 'Payment' " ;

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
public function getTotalPosting($data)
{
$sql='select count(*) as total from (SELECT od.amount,od.transaction_type,DATE(od.create_date) as create_date,od.payment_method,user.firstname,user.lastname FROM `oc_supplier_credit_posting` as od
LEFT JOIN oc_po_supplier as oc_po_supplier on oc_po_supplier.id=od.supplier_id

LEFT JOIN oc_user as user on user.user_id=od.user_id';
if (!empty($data['filter_supplier'])) 
{
$sql .= " WHERE od.supplier_id= '" . (int)$data['filter_supplier'] . "'"; 
} else {
$sql .= " WHERE od.supplier_id > '0'";
}

$sql .= " and od.entry_type!= 'Payment' " ;

if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(od.create_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(od.create_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
$sql.=" ) as aa";
$query= $this->db->query($sql);
return $query->row['total'];
}
   public function insertCreditPosting($data,$updated_by) 
        {
			 $log = new Log('suuplier-'.date('Y-m-d').'.log') ; 
             $sql="insert into  oc_supplier_credit_posting set supplier_id='".$data["supplier"]."',user_id='".$updated_by."',amount='".$data['amount']."',transaction_type='".$data['transaction_type']."',payment_method='".$data['payment_method']."',payment_bank='".$data['payment_bank']."',tr_number='".$data['tr_number']."',entry_type='Bulk Posting',remarks='".$data['remarks']."',invoice_date='".$data['invoice_date']."' ";
             $query = $this->db->query($sql);
             $insert_id=$this->db->getLastId();

             if($data['transaction_type']=='Credit Posting' || $data['transaction_type']=='Waiver Subsidy' )
             { 
              $sql2="update  oc_po_supplier set wallet_balance=wallet_balance +'".$data["amount"]."' where id='".$data['supplier']."'";
              $query2 = $this->db->query($sql2);

		try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addsuppliertrans($data['supplier'],$data["amount"],'CR',$insert_id,$data['transaction_type'],$data['payment_method']);  
                            
   
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
             }
             if($data['transaction_type']=='Debit') 
             {
             $sql2="update  oc_po_supplier set wallet_balance=wallet_balance -'".$data["amount"]."' where id='".$data['supplier']."'";
             $query2 = $this->db->query($sql2);
             
             try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addsuppliertrans($data['supplier'],$data["amount"],'DB',$insert_id,$data['transaction_type'],$data['payment_method']);  
                            
   
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
             }
             return $insert_id;
        }     
       public function insertCreditPostingPaymentDone($data,$updated_by) 
       {
            $log = new Log('suuplier-'.date('Y-m-d').'.log') ;  
            $sql2="SELECT oc_supplier_po_order.supplier_id as supplier_id,oc_supplier_po_invoice.amount as amount FROM oc_supplier_po_invoice left join oc_supplier_po_order on oc_supplier_po_invoice.po_no=oc_supplier_po_order.sid where oc_supplier_po_order.sid='".$data['order_id']."'  ";

//exit;

            $query2= $this->db->query($sql2);
            $log->write($sql2);
            $supplierid = $query2->row['supplier_id'];
			$invoice_date = $query2->row['invoice_date'];
            $amount = $data['paid_amount'];
            $log->write($query2->row);
             $sql="insert into  oc_supplier_credit_posting set supplier_id='".$supplierid."',user_id='".$updated_by."',amount='".$amount."',transaction_type='Credit Posting',payment_method='".$data['payment_method']."',payment_bank='".$data['payment_bank']."',tr_number='".$data['tr_number']."',entry_type='Payment',remarks='".$data['remarks']."',po_number='".$data['order_id']."' ";
             $query = $this->db->query($sql);
	$log->write($sql);
             $insert_id=$this->db->getLastId();
	$log->write($insert_id);
             
              $sql2="update  oc_po_supplier set wallet_balance=wallet_balance +'".$amount."' where id='".$supplierid."'"; 
              $query2 = $this->db->query($sql2);
	$log->write($sql2);
		try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addsuppliertrans($supplierid,$amount,'CR',$insert_id,'Payment',$data['order_id']);   
                            
   
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
             //echo "here";
              
             return $amount;
        }     
        public function update_snapshotfile($order_id,$file_name)
        {
            $sql2="update  oc_supplier_credit_posting set snapshot_path='".$file_name."' where sid='".$order_id."'";
            $query2 = $this->db->query($sql2);
        }
}
?>