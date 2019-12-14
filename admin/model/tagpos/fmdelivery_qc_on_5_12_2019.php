<?php
class ModelTagposFmdelivery extends Model 
{


public function getOrder_detail($order_id) {


	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			
	return $query->rows;

								
	}

public function get_store_unit($store_id)
{
	
$sql = " select oc_unit.unit_id,oc_unit.unit_name from oc_unit left join oc_store on oc_unit.unit_id=oc_store.unit_id left join oc_store_to_unit on oc_unit.unit_id=oc_store_to_unit.unit_id where oc_store_to_unit.store_id='".$store_id."'  GROUP by unit_id   ";
$query = $this->db->query($sql);
return $query->rows;
}

//company wise
public function getOrdersCompanywise($data = array()) {


		$sql="select * from ( SELECT 

DISTINCT od.invoice_no, DATE(od.create_time) as dat,Date(oc.date_added) as dateorder, oc.store_name, od.fmcode, od.fmname, oc.store_id, oc.tagged,oc.subsidy,oc.telephone, oc.shipping_firstname as grower_id ,oc.payment_firstname as grower_name,oc.payment_address_1 as village_name 

FROM oc_order_delivery od LEFT JOIN oc_order oc ON oc.order_id = od.invoice_no 

WHERE od.invoice_no <> '' AND od.fmcode <> '0' ";
                
if(!empty($data['filter_date_start']) && $data['filter_date_start'] != '') 
{

$sql.= "AND  Date(date_added)='".$this->db->escape($data['filter_date_start'])."'";
}

if(!empty($data['filter_fm_name']) && $data['filter_fm_name'] > 0)
{

$sql.= "AND od.fmcode='".(int)$this->db->escape($data['filter_fm_name'])."' ";
}
if(!empty($data['filter_invoice']) && $data['filter_invoice'] > 0) 
{

$sql.= "AND od.invoice_no LIKE '%".(int)$this->db->escape($data['filter_invoice'])."%' ";
}

 $sql.=" AND oc.store_id='".(int)$this->db->escape($data['filter_store'])."' 
		 AND oc.order_status_id='5' 

UNION ALL

select DISTINCT od.invoice_no, DATE(od.create_time) as dat,Date(oc.date_added) as dateorder, oc.store_name, od.fmcode, od.fmname,  oc.store_id, oc.tagged,oc.subsidy,oc.telephone, oc.shipping_firstname as grower_id ,oc.payment_firstname as grower_name,oc.payment_address_1 as village_name 

FROM oc_order_delivery_advance od LEFT JOIN oc_order oc ON oc.order_id = od.invoice_no 

WHERE od.invoice_no <> '' AND od.fmcode <> '0' AND oc.order_status_id='5' ";

if(!empty($data['filter_date_start']) && $data['filter_date_start'] != '') 
{

$sql.= "AND  Date(date_added)='".$this->db->escape($data['filter_date_start'])."'";
}
if(!empty($data['filter_fm_name']) && $data['filter_fm_name'] > 0) 
{

$sql.= "AND od.fmcode='".(int)$this->db->escape($data['filter_fm_name'])."' ";
}
if(!empty($data['filter_invoice']) && $data['filter_invoice'] > 0) 
{
$sql.= "AND od.invoice_no LIKE '%".(int)$this->db->escape($data['filter_invoice'])."%' ";
}

$sql.=" AND oc.store_id='".(int)$this->db->escape($data['filter_store'])."' )  aa ORDER BY DATE(aa.dat)


DESC ";

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
        
        public function getTotalOrdersCompanywise($data = array()) {
		
$sql="select count(*) as total from ( SELECT 

DATE(od.create_time) as dat,Date(oc.date_added) as dateorder, oc.store_name, od.fmcode, od.fmname, od.invoice_no, oc.store_id, oc.tagged, oc.shipping_firstname as grower_id ,oc.payment_firstname as grower_name,oc.telephone,oc.payment_address_1 as village_name 

FROM oc_order_delivery od LEFT JOIN oc_order oc ON oc.order_id = od.invoice_no 

WHERE od.invoice_no <> '' AND od.fmcode <> '0' ";

if(!empty($data['filter_date_start']) && $data['filter_date_start'] != '') 
{
$sql.= "AND  Date(date_added)='".$this->db->escape($data['filter_date_start'])."'";
}

if(!empty($data['filter_fm_name']))
{

$sql.= "AND od.fmcode='".(int)$data['filter_fm_name']."' ";
}
if(!empty($data['filter_invoice'])) 
{
$sql.= "AND od.invoice_no LIKE '%".(int)$data['filter_invoice']."%' ";
}

$sql.="  AND oc.store_id='".(int)$data['filter_store']."' 

UNION ALL

select DATE(od.create_time) as dat,Date(oc.date_added) as dateorder, oc.store_name, od.fmcode, od.fmname, od.invoice_no, oc.store_id, oc.tagged, oc.shipping_firstname as grower_id ,oc.payment_firstname as grower_name,oc.telephone,oc.payment_address_1 as village_name 

FROM oc_order_delivery_advance od LEFT JOIN oc_order oc ON oc.order_id = od.invoice_no 

WHERE od.invoice_no <> '' AND od.fmcode <> '0' ";

if(!empty($data['filter_date_start']) && $data['filter_date_start'] != '') 
{
$sql.= "AND  Date(date_added)='".$this->db->escape($data['filter_date_start'])."'";
}

if(!empty($data['filter_fm_name']))
{

$sql.= "AND od.fmcode='".(int)$data['filter_fm_name']."' ";
}

if(!empty($data['filter_invoice'])) 
{
$sql.= "AND od.invoice_no LIKE '%".(int)$data['filter_invoice']."%' ";
}

 $sql.=" AND oc.store_id='".(int)$data['filter_store']."' )  aa  ";

             //  echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
       
        
	public function getbatch_status($batch_no)
	{
		$sql="select * from oc_req_batch where sid='".$batch_no."' ";
		$query=$this->db->query($sql);
		return $query->row['status'];
	}
function InsertFmdtl($data)
{

$sql = "select * from oc_req_batch where sid!='' ";

				if (!empty($data['filter_date_start'])) 
				{
					 $sql .=" AND DATE(filter_date)='".$data['filter_date_start']."'";
				}
				if (!empty($data['filter_fm_name'])) 
				{
					 $sql .=" AND fm_code='".(int)$data['filter_fm_name']."'";
				}
			   if (!empty($data['filter_store'])) 
                {
                $sql .=" AND store_id='".(int)$data['filter_store']."'";
                }
				 if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND unit_id='".(int)$data['filter_unit']."'";
                }
				
			
			 //echo $sql; exit;
    $query = $this->db->query($sql);
	$rows=$query->row;
	
	if(count($rows)<=0)
	{
	$cr_date=date('Y-m-d');
	$log=new Log("Insertfmdetail-".date('Y-m-d').".log");
	$sql2 = "insert oc_req_batch set `store_id`='".$this->db->escape($data['filter_store'])."',`unit_id`='".$this->db->escape($data['filter_unit'])."',`fm_code`='".$this->db->escape($data['filter_fm_name'])."',`filter_date`='".$this->db->escape($data['filter_date_start'])."',`create_date`='".$cr_date."',`delivery_date`='',`status`='' ";
	$query2 = $this->db->query($sql2);
	return $lastid=$this->db->getLastId();
	}
	else
	{
	//echo $rows['sid']; exit;
	return $rows['sid'];
	
	}
     


}
function insertbatchdtl($batch_no,$invoice_no)
{

$sql3 = "insert oc_req_batch_dtl set `batch_no`='".$batch_no."',`invoice_no`='".$invoice_no."' ";
$query3= $this->db->query($sql3);


}

public function getfm() {
     
        $sql="SELECT fmcode,fmname from oc_order_delivery ";

        $query = $this->db->query($sql);
        
        return $query->rows;
    }
	public function getorderproducts($inv) {

$sql = "SELECT order_id,name,quantity,price,tax,total FROM oc_order_product where order_id='".$inv."'";

// echo $sql; exit;
$query = $this->db->query($sql);

return $query->rows;
}
public function getRecords2($data = array()) {
// print_r($data);
$sql="select (create_time) as create_date,store_name,fmcode,fmname,product_id,model,sum( qnty) as qnty,sum( ttotal) as ttotal , sum(cnt) as cnt from
(
select date(create_time) create_time ,store_name,od.fmcode as fmcode,od.fmname as fmname,product_id,model,sum(quantity) qnty,( sum(op.total)+(op.tax* sum(quantity) ) ) ttotal ,COUNT(od.invoice_no) as cnt from oc_order_delivery od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id where od.invoice_no<> '' and od.fmcode <> 0 ";
if(!empty($data['filter_fm_name']))
{
	$sql.=" and od.fmcode='" . $this->db->escape($data['filter_fm_name']) . "' ";
}
if(!empty($data['filter_invoice']))
{
	$sql.=" and od.invoice_no='" . $this->db->escape($data['filter_invoice']) . "' ";
}

if(!empty($data['filter_date_start']) && $data['filter_date_start'] != '') 
{
    $sql.= "AND  Date(date_added)='".$this->db->escape($data['filter_date_start'])."'";
}
$sql.=" and oc.store_id='" . $this->db->escape($data['filter_store']) . "' 
	and oc.order_status_id='5'
	GROUP by od.fmcode,product_id

union all

select date(create_time) create_time,store_name,od.fmcode as fmcode,od.fmname as fmname,product_id,model,sum(quantity) qnty,( sum(op.total)+(op.tax* sum(quantity) ) ) ttotal ,COUNT(od.invoice_no) as cnt from oc_order_delivery_advance od left join oc_order_product op on op.order_id=od.invoice_no left join oc_order oc on oc.order_id=op.order_id where od.invoice_no<> '' and od.fmcode <> 0 ";
if(!empty($data['filter_fm_name']))
{
	$sql.=" and od.fmcode='" . $this->db->escape($data['filter_fm_name']) . "' ";
}
if(!empty($data['filter_invoice']))
{
	$sql.=" and od.invoice_no='" . $this->db->escape($data['filter_invoice']) . "' ";
}
if(!empty($data['filter_date_start']) && $data['filter_date_start'] != '') 
{
    $sql.= "AND  Date(date_added)='".$this->db->escape($data['filter_date_start'])."'";
}
$sql.=" and oc.store_id='" . $this->db->escape($data['filter_store']) . "' 
	and oc.order_status_id='5'
	GROUP by od.fmcode,op.product_id
) a GROUP by fmcode,product_id

";



$sql.=" order by qnty desc ";
//echo $sql; exit; 
$query = $this->db->query($sql);
return $query->rows;
}
public function getbatchinvcount($batchno) {

$sql="SELECT count(invoice_no) as cinvoice FROM oc_req_batch_dtl where batch_no='".$batchno."' ";

$query = $this->db->query($sql);

return $query->row['cinvoice'];
}
public function getbatchinvoice($batchno) {

$sql="SELECT invoice_no FROM oc_req_batch_dtl where batch_no='".$batchno."' ";

$query = $this->db->query($sql);

return $query->rows;
}
public function getbatchinvoicelist($data) {

$sql="SELECT invoice_no FROM oc_req_batch_dtl where batch_no='".$data['batchno']."' ";

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
public function getgtax($order_id)
{
			$log=new Log("gtax-".date('Y-m-d').".log");
                
                                          $gtax_query = $this->db->query(" SELECT title,value FROM `oc_order_total` where order_id='".$order_id."' and code='tax'  ");
			$log->write($gtax_query);
			if ($gtax_query->num_rows) {
				$gtax = $gtax_query->rows;
			} else {
				$gtax = '';
			}
			$log->write($gtax);
	return $gtax;
} 
public function productsalefmwise($data = array()) {
// print_r($data);
$sql="
select date(date_added) create_time ,
store_name,od.fmcode,fmname,
product_id,op.name as model,
op.quantity qnty,
oc.total as total,
oc.subsidy as subsidy,
oc.cash as cash,
oc.tagged as tagged,
oc.shipping_firstname as grower_id,
oc.payment_firstname as payment_firstname,
oc.order_id as order_id,
oc.comment as req_id,
oc.payment_method as payment_method
from oc_order_delivery od 
left join oc_order_product op on op.order_id=od.invoice_no 
left join oc_order oc on oc.order_id=op.order_id 
where od.invoice_no<> '' and od.fmcode <> 0 
and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "'
and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "'
and oc.store_id='" . $this->db->escape($data['filter_store']) . "'  ";

if(!empty($data['filter_product']))
{
	$sql.=" and op.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}

$sql.=" and oc.order_status_id='5' 
union all

select date(date_added) create_time,
store_name,od.fmcode,fmname,
product_id,op.name as  model,op.quantity qnty,
oc.total as total,
oc.subsidy as subsidy,
oc.cash as cash,
oc.tagged as tagged,
oc.shipping_firstname as grower_id,
oc.payment_firstname as payment_firstname,
oc.order_id as order_id,
oc.comment as req_id,
oc.payment_method as payment_method
from oc_order_delivery_advance od 
left join oc_order_product op on op.order_id=od.invoice_no 
left join oc_order oc on oc.order_id=op.order_id 
where od.invoice_no<> '' and od.fmcode <> 0 
and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "' 
and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "' 
and oc.store_id='" . $this->db->escape($data['filter_store']) . "' ";

if(!empty($data['filter_product']))
{
	$sql.=" and op.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}

$sql.=" and oc.order_status_id='5' 
 group by create_time,fmcode,product_id,oc.order_id

";



$sql.=" order by date(create_time) desc ";
if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit']; 
        }
//echo $sql; //exit; 
$query = $this->db->query($sql);
return $query->rows;
}
public function totalproductsalefmwise($data = array()) {
// print_r($data);
$sql="
select count(*) as total from ( select date(date_added) create_time ,
store_name,od.fmcode,fmname
from oc_order_delivery od 
left join oc_order_product op on op.order_id=od.invoice_no 
left join oc_order oc on oc.order_id=op.order_id 
where od.invoice_no<> '' and od.fmcode <> 0 
and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "'
and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "'
and oc.store_id='" . $this->db->escape($data['filter_store']) . "' ";
if(!empty($data['filter_product']))
{
	$sql.=" and op.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}

$sql.=" and oc.order_status_id='5' 
union all

select date(date_added) create_time,
store_name,od.fmcode,fmname
from oc_order_delivery_advance od 
left join oc_order_product op on op.order_id=od.invoice_no 
left join oc_order oc on oc.order_id=op.order_id 
where od.invoice_no<> '' and od.fmcode <> 0 
and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "' 
and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "' 
and oc.store_id='" . $this->db->escape($data['filter_store']) . "' ";
if(!empty($data['filter_product']))
{
	$sql.=" and op.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}
$sql.=" and oc.order_status_id='5' 
 group by create_time,fmcode,product_id,oc.order_id

";



$sql.=" ) as aa ";
//echo $sql; //exit; 
$query = $this->db->query($sql);
return $query->row;
}
	public function productsalefmwisecash($data = array()) {
// print_r($data);
$sql="
select date(date_added) create_time ,
store_name,oc.fmcode,'' as fmname,
product_id,op.name as model,
op.quantity qnty,
oc.total as total,
oc.cash as cash,
oc.tagged as tagged,
oc.shipping_firstname as grower_id,
oc.payment_firstname as payment_firstname,
oc.order_id as order_id,
oc.comment as req_id,
oc.payment_method as payment_method
from oc_order as  oc
left join oc_order_product op on op.order_id=oc.order_id 

where  oc.fmcode <> 0 
and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "'
and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "'
and oc.store_id='" . $this->db->escape($data['filter_store']) . "'  ";

if(!empty($data['filter_product']))
{
	$sql.=" and op.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}

$sql.=" and oc.order_status_id='5' 
 group by create_time,fmcode,product_id,oc.order_id

";



$sql.=" order by date(create_time) desc ";
if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit']; 
        }
//echo $sql; //exit; 
$query = $this->db->query($sql);
return $query->rows;
}
public function totalproductsalefmwisecash($data = array()) {
// print_r($data);
$sql="
select count(*) as total from ( select date(date_added) create_time ,
store_name,oc.fmcode
from oc_order as oc 
left join oc_order_product op on op.order_id=oc.order_id 

where  oc.fmcode <> 0 
and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "'
and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "'
and oc.store_id='" . $this->db->escape($data['filter_store']) . "' ";
if(!empty($data['filter_product']))
{
	$sql.=" and op.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}

$sql.=" and oc.order_status_id='5' 

 group by create_time,fmcode,product_id,oc.order_id

";



$sql.=" ) as aa ";
//echo $sql; //exit; 
$query = $this->db->query($sql);
return $query->row;
}
public function cash_productsalefmwise($data = array()) {
// print_r($data);
$sql="
select date(date_added) create_time ,
store_name,oc.fmcode,
product_id,op.name as model,
sum(op.quantity) qnty,
oc.total as total,
oc.cash as cash,
oc.tagged as tagged,
oc.shipping_firstname as grower_id,
oc.payment_firstname as payment_firstname,
oc.order_id as order_id,
oc.comment as req_id,
oc.payment_method as payment_method
from  oc_order_product op  
left join oc_order oc on oc.order_id=op.order_id 
where  oc.fmcode <> 0 
and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "'
and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "'
and oc.store_id='" . $this->db->escape($data['filter_store']) . "'  ";

if(!empty($data['filter_product']))
{
	$sql.=" and op.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}

$sql.=" and oc.order_status_id='5' 
 group by fmcode,product_id";



$sql.=" order by date(oc.date_added) desc ";
if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit']; 
        }
//echo $sql; //exit; 
$query = $this->db->query($sql);
return $query->rows;
}





public function getproductname($sid) {
// print_r($data);
$sql="SELECT op.product_id,op.model,ops.store_id FROM oc_product op
 join oc_product_to_store ops on ops.product_id=op.product_id where op.quantity>0 and store_id='".$sid."'";
$query = $this->db->query($sql);
return $query->rows;
}





public function AddFm($data) 
{
	//print_r($data);
	//exit;
	$log=new Log("aad_loan-inventory-".date('Y-m-d').".log");
	$log->write('data');
	$log->write($data);
	
	   
		
	$fm=$data['filter_fmlist'];
	
	 $result=explode(",",$fm);
	 $res=$result[0];
	 $res1=$result[1];
     $p_id=$data['product_id'];
     $totpid=count($p_id);
     for($i=0;$i<$totpid;$i++){
                    $sql="INSERT INTO oc_fm_loan_inventory SET
                     fm_id ='".$res."',
					 fm_name ='".$res1."',
                     product_id   ='".$data['product_id'][$i]."',
                     issue_date ='".$data['filter_date_start'][$i]."',
					 store_id='".$data['store_id']."',
					 user_id='".$data['user_id']."',
                     quantity ='".$data['qty'][$i]."'";  
					 $log->write($sql);
					 //echo $sql;
					 //exit;
        $query = $this->db->query($sql);						  
        $log->write($query);           
					
}


return $query;
}





public function reportfmloaninventory($data = array()) {
// print_r($data);
$sql="
select ofm.id,ofm.fm_id,ofm.product_id,ofm.store_id,ofm.fm_name,op.model,sum(ofm.quantity) as issuequantity
from oc_fm_loan_inventory ofm 
left join oc_product op on op.product_id=ofm.product_id 
 
where ofm.id<> ''";


if(!empty($data['filter_product']))
{
	$sql.=" and ofm.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}
if(!empty($data['filter_fm']))
{
	$sql.=" and ofm.fm_id='" . $this->db->escape($data['filter_fm']) . "'  ";
}

if(!empty($data['filter_date_start']))
{
	$sql.=" and date(ofm.issue_date)>='" . $this->db->escape($data['filter_date_start']) . "'  ";
}
if(!empty($data['filter_date_end']))
{
	$sql.=" and date(ofm.issue_date)<='" . $this->db->escape($data['filter_date_end']) . "'  ";
}


$sql.=" group by ofm.fm_id,ofm.product_id";
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


public function totalfmloaninventorytrans($data = array()) {
// print_r($data);
$sql="
select count(*) as total
from oc_fm_loan_inventory ofm 
left join oc_product op on op.product_id=ofm.product_id 

where ofm.id<> '' ";


if(!empty($data['filter_product']))
{
	$sql.=" and ofm.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}
if(!empty($data['filter_fm']))
{
	$sql.=" and ofm.fm_id='" . $this->db->escape($data['filter_fm']) . "' group by ofm.fm_name,op.model  ";
}

if(!empty($data['filter_date_start']))
{
	$sql.=" and date(ofm.issue_date)>='" . $this->db->escape($data['filter_date_start']) . "'  ";
}
if(!empty($data['filter_date_end']))
{
	$sql.=" and date(ofm.issue_date)<='" . $this->db->escape($data['filter_date_end']) . "'  ";
}


//echo $sql;
$query = $this->db->query($sql);
return $query->row;
}


public function issuefmloaninventorytrans($data = array()) {
// print_r($data);
$log=new Log("loan-report-inventory-".date('Y-m-d').".log");
$log->write($data);
$sql="
select ofm.id,ofm.fm_id,ofm.product_id,ofm.store_id,ofm.fm_name,op.model,(ofm.quantity) ,issue_date
from oc_fm_loan_inventory ofm 
left join oc_product op on op.product_id=ofm.product_id 

where ofm.id<> ''";
// group by ofm.fm_name,op.model

if(!empty($data['filter_product']))
{
	$sql.=" and ofm.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}
if(!empty($data['filter_fm']))
{
	$sql.=" and ofm.fm_id='" . $this->db->escape($data['filter_fm']) . "'  ";
}

if(!empty($data['filter_date_start']))
{
	$sql.=" and date(ofm.issue_date)>='" . $this->db->escape($data['filter_date_start']) . "'  ";
}


if(!empty($data['filter_date_end']))
{
	$sql.=" and date(ofm.issue_date)<='" . $this->db->escape($data['filter_date_end']) . "'  ";
}


//$sql.=" order by id desc";
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
$log->write($sql);
$query = $this->db->query($sql);
return $query->rows;
}




public function totalfmloaninventory($data = array()) {
// print_r($data);
$sql="
select count(*) as total
from oc_fm_loan_inventory ofm 
left join oc_product op on op.product_id=ofm.product_id 

where ofm.id<> '' ";


if(!empty($data['filter_product']))
{
	$sql.=" and ofm.product_id='" . $this->db->escape($data['filter_product']) . "'  ";
}
if(!empty($data['filter_fm']))
{
	$sql.=" and ofm.fm_id='" . $this->db->escape($data['filter_fm']) . "'";
}

if(!empty($data['filter_date_start']))
{
	$sql.=" and date(ofm.issue_date)>='" . $this->db->escape($data['filter_date_start']) . "'  ";
}
if(!empty($data['filter_date_end']))
{
	$sql.=" and date(ofm.issue_date)<='" . $this->db->escape($data['filter_date_end']) . "'  ";
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
//echo $sql; //exit; 
$query = $this->db->query($sql);
return $query->row;
}



public function get_billed_qty($data,$product_id,$fm_code,$store_id)
	{
		$sql="select sum(aa.qnty) as billed from ( select sum(op.quantity) as qnty
				from oc_order_delivery od 
				left join oc_order_product op on op.order_id=od.invoice_no 
				left join oc_order oc on oc.order_id=op.order_id 
				where od.invoice_no<> '' and od.fmcode <> 0 
				and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "'
				and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "'
				and oc.store_id='" . $this->db->escape($store_id) . "' and od.fmcode='".$fm_code."' 
				and op.product_id='".$product_id."'
				
				UNION ALL 
				
				select sum(op.quantity) as qnty
				from oc_order_delivery_advance od 
				left join oc_order_product op on op.order_id=od.invoice_no 
				left join oc_order oc on oc.order_id=op.order_id 
				where od.invoice_no<> '' and od.fmcode <> 0 
				and date(oc.date_added)>='" . $this->db->escape($data['filter_date_start']) . "'
				and date(oc.date_added)<='" . $this->db->escape($data['filter_date_end']) . "'
				and oc.store_id='" . $this->db->escape($store_id) . "' and od.fmcode='".$fm_code."' 
				and op.product_id='".$product_id."'
				
				) as aa 
				";
			//echo $sql;
			$query = $this->db->query($sql);
			return $query->row['billed'];
	}


public function updatequantity($data){
	$sql="UPDATE oc_fm_loan_inventory SET quantity = '" . $this->db->escape($data['quantity']) . "' WHERE id = '" . ($data['id']). "'";
	//echo $sql;
	$query = $this->db->query($sql);	
     return $query;

	  
   
	}
        
        

    public function updateFMOrderStatus($invoice_id,$status='1'){
        $log=new Log("Fm Order Delivery Notification Status-".date('Y-m-d').".log");
        $log->write('--Prepare Query--');
        $sql_od = "update oc_order_delivery set delivery_status='".$status."' where invoice_no='".$invoice_id."' ";
        $sql_od_advnc = "update oc_order_delivery_advance set delivery_status='".$status."' where invoice_no='".$invoice_id."' "; 
        $log->write('--Prepared query for order delivery -- '.$sql_od.' --');
        $log->write('--Prepared query for order delivery advance -- '.$sql_od_advnc.' --'); 
        $qry = $this->db->query($sql_od);    
        $qry1 = $this->db->query($sql_od_advnc);
        $log->write($qry.' & '.$qry1);
        if($qry || $qry1){
            $msg = array('status'=>'success','responce'=>'Delivery status has been updated.');
        }else{
            $msg = array('status'=>'error','responce'=>'Delivery status not updated. Please try again.');            
        }
        $log->write($msg);
        return $msg;
    } 
}