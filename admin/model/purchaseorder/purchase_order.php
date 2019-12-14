<?php
class ModelPurchaseOrderPurchaseOrder extends Model {

        public function getUsers($data)
        {
           $sql="SELECT concat(firstname,' ',lastname) as name,username as mobile_number FROM `oc_user`  ";
	if (!empty($data['filter_name'])) {
			$sql .= " where concat(firstname,' ',lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " and store_id='" . $this->db->escape($data['filter_store']) . "'";
		}
	$sql.=" and status=1 and user_group_id=11 order by firstname asc ";
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
	public function get_prn_list($data)
        	{
            
            //\\############################## Updated on 25-11-2019 ################################//\\
           		//$sql="SELECT opo.id as po_id,oprd.product_id,oprd.quantity,opd.name as product_name,opo.order_date as order_date FROM `oc_po_order` as opo left join oc_po_receive_details as oprd on opo.id=oprd.order_id left join oc_product_description as opd on oprd.product_id=opd.product_id WHERE opo.spo_po_linked_status in (0)  ";
			$sql="SELECT opo.id as po_id,oprd.product_id,oprd.quantity,opd.name as product_name,opo.order_date as order_date FROM `oc_po_order` as opo left join oc_po_receive_details as oprd on opo.id=oprd.order_id left join oc_product_description as opd on oprd.product_id=opd.product_id WHERE opo.spo_po_linked_status in (1)  ";
			
                        if (!empty($data['store_id'])) 
			{
			$sql .= " and  opo.store_id = '".$data['store_id']."' ";
			}
			if (!empty($data['product_id'])) 
			{
			$sql .= " and oprd.product_id = '".$data['product_id']."' "; 
			}
			$sql.=" order by opo.id desc ";
			echo $sql;
           $query = $this->db->query($sql);
           return $query->rows;
           
        }
        public function check_ware_house_quantity($ware_house,$product_id,$p_qnty)
        {
           $sql="SELECT `quantity` from  oc_product_to_store where store_id = ".$ware_house." and `product_id` ='".$product_id."'";
           $query = $this->db->query($sql);
           $store_quantity=$query->row['quantity']; 
           if($store_quantity<$p_qnty)
           {
                return '0';
           }
           else
           {
               return '1';
           }
           
        }
        public function check_ware_house_price($ware_house,$product_id,$p_price)
        {
           $sql="SELECT `wholesale_price` from  oc_product where `product_id` ='".$product_id."'";
           $query = $this->db->query($sql);
           $store_price=$query->row['price']; 
           if($store_price>$p_price)
           {
                return '0';
           }
           else
           {
               return '1';
           }
           
        }
       
        
          public function get_to_supplier_data($supplier_id)
        {
              //echo $supplier_id;
            
           $supplier_data='';
           $sql="SELECT *,concat(first_name,' ',last_name) as name FROM oc_po_supplier where  id='".$supplier_id."'";            
           $query = $this->db->query($sql);
           $supplier_data=$store_data."---".$query->row[name]."---".$query->row[ADDRESS]."---".$query->row[telephone]."---".$query->row[email]."---".$query->row[pan]."---".$query->row[gst]."---".$query->row[wallet_balance];
          //print_r($supplier_data) ;   
         
           return $supplier_data;
           
        }
        
        
        
        public function get_to_store_data($store_id)
        {
           $store_data='';
           $sql="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_name'";
            
           $query = $this->db->query($sql);
           $store_data=$query->row['value']; 
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_address'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_telephone'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value']; 
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_email'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_PAN_ID_number'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_gstn'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];

           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_MSMFID' ";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
          //echo $store_data;
           return $store_data;
           
        }
	public function getremarks($pono)
	{
            $sql="SELECT oc_supplier_po_order.remarks as remarks FROM oc_supplier_po_order  where oc_supplier_po_order.sid=".$pono;
                
                       
                $query = $this->db->query($sql);
		
		return $query->row['remarks'];
	}
	public function getList_for_approve($data)
	{
            
        $log=new Log("getStorePOApprovalList".date('Y-m-d').".log");
            $sql="SELECT osi.invoice_no,osi.amount,oc_supplier_po_order.create_date,
			op.model as product,oc_supplier_po_order.Quantity,
			oc_supplier_po_order.rate as rate,
			oc_supplier_po_order.revised_status as revised_status,
			oc_supplier_po_order.sid,oc_supplier_po_order.id_prefix,oc_supplier_po_order.supplier_id,
			oc_supplier_po_order.status,oc_supplier_po_order.valid_date,
			os.name as delivery_address,
			concat(ps.first_name,ps.last_name) as supplier,
			oc_supplier_po_order.remarks as remarks,
			oc_supplier_po_order.approved_date as approved_date,
			oc_supplier_po_order.approve_status as approve_status,
				concat(cr_by.firstname,' ',cr_by.lastname) as create_by,
				concat(ap_by.firstname,' ',ap_by.lastname) as approved_by
			
			FROM oc_supplier_po_order 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_supplier_po_invoice as osi on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
left join oc_user as cr_by on oc_supplier_po_order.create_by=cr_by.user_id
left join oc_user as ap_by on oc_supplier_po_order.approved_by=ap_by.user_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where oc_supplier_po_order.sid!=''
and oc_supplier_po_order.approve_status='0'
";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
						if (!empty($data['filter_po']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.sid='".$data['filter_po']."'";
			
                        }
                        if ($data['filter_status']!='') 
                        {
                            $sql .=" and oc_supplier_po_order.status='".$data['filter_status']."'";
			
                        }
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.="ORDER BY oc_supplier_po_order.sid DESC ";
                
                        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
           // echo $sql;w
                $log->write($sql);
                $query = $this->db->query($sql);
		
		return $query->rows;
	}
	public function getTotalOrders_for_approve($data)
	{
		$sql="select count(*) as total_orders from ( SELECT osi.invoice_no FROM oc_supplier_po_order 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_supplier_po_invoice as osi on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where oc_supplier_po_order.sid!=''
and oc_supplier_po_order.approve_status='0'
";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
						if (!empty($data['filter_po']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.sid='".$data['filter_po']."'";
			
                        }
                        if ($data['filter_status']!='') 
                        {
                            $sql .=" and oc_supplier_po_order.status='".$data['filter_status']."'";
			
                        }
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.=" ) as aa ";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row['total_orders'];
		//return $results['total_orders'];
	}
	public function getList($data)
	{
            $sql="SELECT osi.invoice_no,osi.amount,oc_supplier_po_order.create_date,
			op.model as product,oc_supplier_po_order.Quantity,
			oc_supplier_po_order.rate as rate,
			oc_supplier_po_order.revised_status as revised_status,
			oc_supplier_po_order.sid,oc_supplier_po_order.id_prefix,oc_supplier_po_order.supplier_id,
			oc_supplier_po_order.status,oc_supplier_po_order.valid_date,
			os.name as delivery_address,
			concat(ps.first_name,ps.last_name) as supplier,
			oc_supplier_po_order.remarks as remarks ,
			oc_supplier_po_order.approved_date as approved_date,
			oc_supplier_po_order.approve_status as approve_status,
				concat(cr_by.firstname,' ',cr_by.lastname) as create_by,
				concat(ap_by.firstname,' ',ap_by.lastname) as approved_by
			
			FROM oc_supplier_po_order 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_supplier_po_invoice as osi on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
left join oc_user as cr_by on oc_supplier_po_order.create_by=cr_by.user_id
left join oc_user as ap_by on oc_supplier_po_order.approved_by=ap_by.user_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where oc_supplier_po_order.sid!=''
";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        if ($data['filter_status']!='') 
                        {
                            $sql .=" and oc_supplier_po_order.status='".$data['filter_status']."'";
			
                        }
						if (!empty($data['filter_po']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.sid='".$data['filter_po']."'";
			
                        }
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.="ORDER BY oc_supplier_po_order.sid DESC ";
                
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
	public function getTotalOrders($data)
	{
		$sql="select count(*) as total_orders from ( SELECT osi.invoice_no FROM oc_supplier_po_order 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_supplier_po_invoice as osi on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where oc_supplier_po_order.sid!=''
";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        if ($data['filter_status']!='') 
                        {
                            $sql .=" and oc_supplier_po_order.status='".$data['filter_status']."'";
			
                        }
						if (!empty($data['filter_po']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.sid='".$data['filter_po']."'";
			
                        }
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.=" ) as aa ";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row['total_orders'];
		//return $results['total_orders'];
	}
	public function approve_reject_po($data)
	{
		//print_r($data);
		$sql="update oc_supplier_po_order set approve_status='".$data['status']."',approved_date=NOW(),approved_by='".$data['approved_by']."' where sid='".$data['sid']."' ";
		$query = $this->db->query($sql);
	}
//invoice

public function getinvoiceList($data)
	{
    
        $log=new Log("getStoreInvoiceList".date('Y-m-d').".log");
            $sql="SELECT osi.invoice_no,osi.amount,
			oc_supplier_po_order.create_date,
			op.model as product,oc_supplier_po_order.Quantity,
			oc_supplier_po_order.sid,
			oc_supplier_po_order.id_prefix,oc_supplier_po_order.supplier_id,
			oc_supplier_po_order.status,
			oc_supplier_po_order.valid_date,
			os.name as delivery_address,
			concat(ps.first_name,ps.last_name) as supplier ,
			oc_supplier_po_order.approved_date as approved_date,
			oc_supplier_po_order.approve_status as approve_status,
				concat(cr_by.firstname,' ',cr_by.lastname) as create_by,
				concat(ap_by.firstname,' ',ap_by.lastname) as approved_by
			FROM oc_supplier_po_order 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_supplier_po_invoice as osi on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
left join oc_user as cr_by on oc_supplier_po_order.create_by=cr_by.user_id
left join oc_user as ap_by on oc_supplier_po_order.approved_by=ap_by.user_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where oc_supplier_po_order.sid!=''  
and oc_supplier_po_order.status='3' AND oc_supplier_po_order.approve_status='1'
 ";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        if (!empty($data['filter_po']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.sid='".$data['filter_po']."'";
			
                        }
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.="ORDER BY oc_supplier_po_order.sid DESC ";
                
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
                $log->write();
                $query = $this->db->query($sql);
		
		return $query->rows;
	}
	public function getinvoiceTotalOrders($data)
	{
		$sql="select count(*) as total_orders from ( SELECT osi.invoice_no FROM oc_supplier_po_order 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_supplier_po_invoice as osi on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where oc_supplier_po_order.sid!='' 
and oc_supplier_po_order.status='3' AND oc_supplier_po_order.approve_status='1'
";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.create_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        if (!empty($data['filter_po']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.sid='".$data['filter_po']."'";
			
                        }
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.=" ) as aa ";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row['total_orders'];
		//return $results['total_orders'];
	}


//payment



        public function getpaymentList($data)
	{
            $sql="SELECT 
    spi.invoice_date as invoice_date,
	oc_supplier_po_order.create_date as po_date,
	oc_supplier_po_order.received_prn as received_prn, 
    op.model AS product,
    spi.Quantity,
    spi.amount,
    spi.rate,
    oc_supplier_po_order.sid,
    spi.id_prefix,    
    oc_supplier_po_order.status,  
    spi.invoice_no, 
    os.name AS delivery_address,
    CONCAT(ps.first_name, ps.last_name) AS supplier
FROM
    oc_supplier_po_invoice as spi
    LEFT JOIN 
    oc_supplier_po_order on oc_supplier_po_order.sid=spi.po_no
        LEFT JOIN
    oc_store AS os ON os.store_id = oc_supplier_po_order.store_id
        LEFT JOIN
    oc_product AS op ON op.product_id = spi.product_id
        LEFT JOIN
    oc_po_supplier AS ps ON ps.id = oc_supplier_po_order.supplier_id
    where spi.sid!='' and oc_supplier_po_order.status='1' ";
                
                       /*
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(spi.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(spi.create_date)<='".$data['filter_date_end']."'";
			
                        }
	         */
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
						if (!empty($data['filter_in_no']) ) 
                        {
                            $sql .=" and spi.invoice_no like '%".$data['filter_in_no']."%'";
			
                        }
                        if (!empty($data['filter_po']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.sid='".$data['filter_po']."'";
			
                        }
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.="ORDER BY spi.invoice_date ASC ";
                
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
	public function getpaymentTotalOrders($data)
	{
		$sql="select count(*) as total_orders,sum(amount) as total_outstanding from (SELECT  
    spi.create_date,
    spi.amount,
    op.model AS product,
    spi.Quantity,
    spi.sid,
    spi.id_prefix,    
    spi.status,   
    os.name AS delivery_address,
    CONCAT(ps.first_name, ps.last_name) AS supplier
FROM
    oc_supplier_po_invoice as spi
    LEFT JOIN 
    oc_supplier_po_order on oc_supplier_po_order.sid=spi.po_no
        LEFT JOIN
    oc_store AS os ON os.store_id = oc_supplier_po_order.store_id
        LEFT JOIN
    oc_product AS op ON op.product_id = oc_supplier_po_order.product_id
        LEFT JOIN
    oc_po_supplier AS ps ON ps.id = oc_supplier_po_order.supplier_id
    where spi.sid!='' and oc_supplier_po_order.status='1' ";
            
                         if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        if (!empty($data['filter_in_no']) ) 
                        {
                            $sql .=" and spi.invoice_no like '%".$data['filter_in_no']."%'";
			
                        }
						if (!empty($data['filter_po']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.sid='".$data['filter_po']."'";
			
                        }
                        $sql.=" group by spi.sid ) as aa";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row;
		//return $results['total_orders'];
	}
	public function gettotaloutstanding($data)
	{
		$sql="select sum(amount) as total_outstanding from (SELECT  
    spi.create_date,
    spi.amount,
    op.model AS product,
    spi.Quantity,
    spi.sid,
    spi.id_prefix,    
    spi.status,   
    os.name AS delivery_address,
    CONCAT(ps.first_name, ps.last_name) AS supplier
FROM
    oc_supplier_po_invoice as spi
    LEFT JOIN 
    oc_supplier_po_order on oc_supplier_po_order.sid=spi.po_no
        LEFT JOIN
    oc_store AS os ON os.store_id = oc_supplier_po_order.store_id
        LEFT JOIN
    oc_product AS op ON op.product_id = oc_supplier_po_order.product_id
        LEFT JOIN
    oc_po_supplier AS ps ON ps.id = oc_supplier_po_order.supplier_id
    where spi.sid!='' and oc_supplier_po_order.status='1' ";
                      
	        
                         if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        
                        $sql.=" group by spi.sid ) as aa";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row;
	}
	public function getallSupplier($data)
	{
		$sql="SELECT    
    CONCAT(oc_po_supplier.first_name, oc_po_supplier.last_name) AS supplier,oc_po_supplier.id as supllier_id,wallet_balance from oc_po_supplier
    where oc_po_supplier.id!='' ";
    if (!empty($data['filter_supplier']) ) 
    {
        $sql .=" and oc_po_supplier.id='".$data['filter_supplier']."'";
	}
    $sql.=" order by oc_po_supplier.wallet_balance desc";
	
	//echo $sql;
    $query = $this->db->query($sql);
	return $query->rows;
	}
	public function getallSupplierWallet_Balance($data)
	{
		$sql="SELECT    
    sum(wallet_balance) as walletbalance from oc_po_supplier
    where oc_po_supplier.id!='' ";
    if (!empty($data['filter_supplier']) ) 
    {
        $sql .=" and oc_po_supplier.id='".$data['filter_supplier']."'";
	}
    $sql.=" order by oc_po_supplier.wallet_balance desc";
	
	//echo $sql;
    $query = $this->db->query($sql);
	return $query->row['walletbalance'];
	}
	
	public function getoutstanding($data)
	{
		$sql="select sum(amount) as total_outstanding,supplier,supplier_id,wallet_balance,(sum(amount)-wallet_balance) as actual_outsanding from (SELECT  
    spi.create_date,
    spi.amount,
    
    spi.Quantity,
    spi.sid,
    spi.id_prefix,    
    spi.status,   
    CONCAT(ps.first_name, ps.last_name) AS supplier,
	oc_supplier_po_order.supplier_id,
	ps.wallet_balance
FROM
    oc_supplier_po_invoice as spi
    LEFT JOIN 
    oc_supplier_po_order on oc_supplier_po_order.sid=spi.po_no
        
        LEFT JOIN
    oc_po_supplier AS ps ON ps.id = oc_supplier_po_order.supplier_id
    where spi.sid!='' and oc_supplier_po_order.status='1' ";
    if (!empty($data['filter_supplier']) ) 
    {
        $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
	}
    $sql.=" group by spi.sid ) as aa  group by supplier_id order by actual_outsanding desc ";
	if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
    //print_r($data);
    $query = $this->db->query($sql);
	return $query->row;
	}
	
	public function get_all_supplier_count($data)
	{
		$sql="select count(*) as total,sum(total_outstanding) as total_outstanding,sum(wallet_balance) as wallet_balance from ( select sum(amount) as total_outstanding,supplier,supplier_id,wallet_balance from (SELECT  
    spi.create_date,
    spi.amount,
    op.model AS product,
    spi.Quantity,
    spi.sid,
    spi.id_prefix,    
    spi.status,   
    os.name AS delivery_address,
    CONCAT(ps.first_name, ps.last_name) AS supplier,
	oc_supplier_po_order.supplier_id,
	ps.wallet_balance
FROM
    oc_supplier_po_invoice as spi
    LEFT JOIN 
    oc_supplier_po_order on oc_supplier_po_order.sid=spi.po_no
        LEFT JOIN
    oc_store AS os ON os.store_id = oc_supplier_po_order.store_id
        LEFT JOIN
    oc_product AS op ON op.product_id = oc_supplier_po_order.product_id
        LEFT JOIN
    oc_po_supplier AS ps ON ps.id = oc_supplier_po_order.supplier_id
    where spi.sid!='' and oc_supplier_po_order.status='1' ";
    if (!empty($data['filter_supplier']) ) 
    {
        $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
	}
    $sql.=" group by spi.sid ) as aa  group by supplier_id ) as bb";
    //echo $sql;
    $query = $this->db->query($sql);
		
		return $query->row;
	}
	
	////////////////
public function getpaidinvoiceList($data)
	{
            $sql="SELECT 
    spi.invoice_date as invoice_date,
	oc_supplier_po_order.create_date as po_date,
oc_supplier_credit_posting.tr_number as bank_tr_no,
oc_supplier_credit_posting.payment_bank as payment_bank,
date(oc_supplier_po_order.payment_date) as payment_date,
    op.model AS product,
    spi.Quantity,
    spi.amount,
    spi.rate,
    oc_supplier_po_order.sid,
    spi.id_prefix,    
    oc_supplier_po_order.status,  
    spi.invoice_no, 
    os.name AS delivery_address,
    CONCAT(ps.first_name, ps.last_name) AS supplier
FROM
    oc_supplier_po_invoice as spi
	left join oc_supplier_credit_posting on spi.po_no=oc_supplier_credit_posting.po_number
    LEFT JOIN 
    oc_supplier_po_order on oc_supplier_po_order.sid=spi.po_no
        LEFT JOIN
    oc_store AS os ON os.store_id = oc_supplier_po_order.store_id
        LEFT JOIN
    oc_product AS op ON op.product_id = spi.product_id
        LEFT JOIN
    oc_po_supplier AS ps ON ps.id = oc_supplier_po_order.supplier_id
    where spi.sid!='' and oc_supplier_po_order.status='2'
	and oc_supplier_credit_posting.transaction_type in ('Credit Posting','Payment Adjustment')
 ";
                
                      if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.payment_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.payment_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.="ORDER BY spi.sid DESC ";
                
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
	public function getpaidinvoicelisttotal($data)
	{
		$sql="select count(*) as total_orders,sum(payment_amount) as total_payment_amount from (SELECT 
    spi.create_date,
	oc_supplier_po_order.payment_amount as payment_amount,
    op.model AS product,
    spi.Quantity,
    spi.sid,
    spi.id_prefix,    
    spi.status,   
    os.name AS delivery_address,
    CONCAT(ps.first_name, ps.last_name) AS supplier
FROM
    oc_supplier_po_invoice as spi
left join oc_supplier_credit_posting on spi.po_no=oc_supplier_credit_posting.po_number
    LEFT JOIN 
    oc_supplier_po_order on oc_supplier_po_order.sid=spi.po_no
        LEFT JOIN
    oc_store AS os ON os.store_id = oc_supplier_po_order.store_id
        LEFT JOIN
    oc_product AS op ON op.product_id = oc_supplier_po_order.product_id
        LEFT JOIN
    oc_po_supplier AS ps ON ps.id = oc_supplier_po_order.supplier_id
    where spi.sid!='' and oc_supplier_po_order.status='2' 
and oc_supplier_credit_posting.transaction_type in ('Credit Posting','Payment Adjustment')
";
                      
	         
                       if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.payment_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_order.payment_date)<='".$data['filter_date_end']."'";
			
                        }
	          
                         if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.=" group by spi.sid ) as aa";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row;
		//return $results['total_orders'];
	}
        public function adjustpayment($pono,$amount,$callby,$logged_user) {
                 $log = new Log('supplier-'.date('Y-m-d').'.log') ;  
	$log->write('adjustpayment called in model');  
	
			$sql2="select supplier_id,status from oc_supplier_po_order where sid='".$pono."'  ";
            $query2= $this->db->query($sql2);
            $supplierid = $query2->row['supplier_id'];
			$current_status=$query2->row['status'];
			if($current_status==2)
			{
				
				return '0';exit;
			}
            $sql="update oc_supplier_po_order set status='2',payment_amount='".$amount."',payment_date='".date('Y-m-d')."' where sid='".$pono."' ";
            $query = $this->db->query($sql);
            $log->write($sql);
            $sql1="update oc_supplier_po_invoice set status='2' where po_no='".$pono."' ";
            $query1 = $this->db->query($sql1); 
            $log->write($sql1);
            
            
            $sql2="update  oc_po_supplier set wallet_balance=wallet_balance -'".$amount."' where id='".$supplierid."'";
	$query2 = $this->db->query($sql2);
             $log->write($sql2);
	$log->write('before inserrt to creditposting');
	$log->write($callby);
	if($callby=='adjustment') 
	{
	  
	$sql="insert into  oc_supplier_credit_posting set supplier_id='".$supplierid."',user_id='".$logged_user."',amount='".$amount."',transaction_type='Payment Adjustment',payment_method='Adjustment',payment_bank='Bulk Posting',tr_number='NA',entry_type='Payment',remarks='Payment released via bulk posting',po_number='".$pono."' ";
             $query = $this->db->query($sql);
	$log->write($sql); 
	
	}
	$log->write('after inserrt to creditposting');
            
             try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addsuppliertrans($supplierid,$amount,'DB',$pono,'Payment Adjustment','');  
                            
   
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
            
            
          return "1";  
                 
        }
       
        
        /////////////////////////////////////////////////
        public function check_po_invoice($order_id) {
            $sql="select po_invoice_n from oc_po_invoice where po_order_id='".$order_id."'  ";
            $query = $this->db->query($sql);
            $results = $query->row;
            if (!empty($results['po_invoice_n'])) {
             return $results['po_invoice_n'];
            }
            else
            {
                return 0;
            }
        }
        public function getProduct($product_id)
	{
		$sql = "select model as model FROM oc_product as p where p.product_id='".$product_id."' ";
		$query = $this->db->query($sql);

		return $query->row['model'];
	}
        public function getProducts($data = array()) {
		$sql = "select product_id ,model as model,HSTN as hstn,(price+product_tax_rate) as price,price as price_wo_t,product_tax_type,product_tax_rate from (
SELECT 
    p.product_id as product_id,p.model as model,p.price,p.HSTN,
    ((SELECT 
                   oc_tax_rate.name
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id 
                WHERE
                    rl.`tax_class_id` = p.tax_class_id)) AS product_tax_type,
                    
  ((SELECT 
                    (CASE
                            WHEN type = 'F' THEN rate
                            WHEN type = 'p' THEN (p.price * (rate / 100))
                            ELSE rate
                        END) AS rate
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    rl.`tax_class_id` = p.tax_class_id)) AS product_tax_rate
FROM
    oc_product p ";

		

		if (!empty($data['filter_model'])) {
			$sql .= " where p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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
                $sql.=" ) as a ";
		//echo $sql;	
		$logs=new Log("a.log");
		//$logs->write($sql);	
		$query = $this->db->query($sql);

		return $query->rows;
	}
        
        

        public function submit_purchase_order($data = array()) {
            $log=new Log('supplier-'.date('Y-m-d').'.log');
           
            $sql3="insert into oc_supplier_po_order set create_by='".$data['create_by']."',store_id='".$data['filter_store']."',supplier_id='".$data['filter_supplier']."',contact_person_name='".$data['contactname']."',contact_person_mobile='".$data['contactmobile']."',valid_date='".$data['filter_date']."',`rate`='".$data['p_price']."',`product_id`='".$data['product_id']."',`delivery_type`='".$data['delivery_type']."',`Quantity`='".$data['p_qnty']."',`amount`='".$data['p_amount']."',`remarks`='".$data['rem']."',`id_prefix`='ASPL/PO/',`create_date`=NOW() ";

	//exit;
            $query = $this->db->query($sql3);
            $insert_id=$this->db->getLastId();
           return $insert_id;
                     
        }
        public function submit_purchase_invoice($order_id,$data = array()) 
        {
            	//$log=new Log('supplier-'.date('Y-m-d').'.log');
			if($data['tax_type']=='Inter-State')
			{
				$data['tax_type']='';
			}
	$insert_id=0;           

	 $sql1="select count(*) as total from oc_supplier_po_invoice  where `invoice_no`='".$data['invoiceno']."' limit 1 "; 
               $query1 = $this->db->query($sql1);

	//if($query1->row['total']<=0)
	{  
          		$sql3="insert into oc_supplier_po_invoice set status='1',invoice_date='".$data['filter_date']."',
				po_no='".$order_id."',`invoice_no`='".$data['invoiceno']."',
				`rate`='".$data['p_price']."',`product_id`='".$data['product_id']."',
				`Quantity`='".$data['p_qnty']."',`unit`='".$data['p_unit']."',`amount`='".$data['grand_total']."',
`cgst_type`='".$data['span_cgst_type_1']."',`sgst_type`='".$data['span_sgst_type_1']."',
`cgst_value`='".$data['span_cgst_1']."',`sgst_value`='".$data['span_sgst_1']."',
`intra_state`='".$data['tax_type']."',`igst_type`='".$data['igst_type']."',`igst_value`='".$data['igst_value']."',
`discount`='".$data['p_discount']."',`sub_total`='".$data['p_amount']."',`transport_charges`='".$data['transport_charge']."',`grand_total`='".$data['grand_total']."',
`remarks`='".$data['rem']."',`id_prefix`='ASPL/BB/',`create_date`=NOW() "; 
            		$query = $this->db->query($sql3);
             		$insert_id=$this->db->getLastId();
             
            		$sql4="update oc_supplier_po_order set status='1',invoice_amount='".$data['grand_total']."',invoice_date='".$data['filter_date']."',received_prn='".$data['received_prn']."',received_store='".$data['received_store']."' where sid='".$order_id."' ";
            		$query1 = $this->db->query($sql4);   
            		if(!empty($data['received_prn']))
		{
	 		$sql5="update oc_po_order set spo_po_linked_status='1' where id='".$data['received_prn']."' ";
               		$query5 = $this->db->query($sql5); 
            		} 
	}
               return $insert_id;
                     
        }
       public function getPODetailsbyID($order_id) {
            
            $sql3=" select * from oc_supplier_po_order where sid='".$order_id."'  ";
            $query = $this->db->query($sql3);
             
            return $query->row['supplier_id'];
                     
        }
       
       
        public function view_order_details_for_created_invoice_b2b($order_id)
    {       $sql="SELECT op.model as product,oc_supplier_po_order.*,ops.*,oc_supplier_po_invoice.invoice_no as invoice_no,oc_supplier_po_invoice.invoice_date as invoice_date FROM oc_supplier_po_order LEFT JOIN oc_po_supplier as ops on ops.id=oc_supplier_po_order.supplier_id LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id left join oc_supplier_po_invoice on oc_supplier_po_order.sid=oc_supplier_po_invoice.po_no WHERE oc_supplier_po_order.sid = " . $order_id ;
        $query = $this->db->query($sql);
        $order_info = $query->row;
                //print_r($order_info);
                
           
       $order_information['order_info'] = $order_info;
           
       return $order_information;    
        }

        public function getSuppliers()
        {
            $sql="SELECT *,concat(first_name,' ',last_name) as name,ACC_ID,IFSC_CODE,BANK_NAME FROM oc_po_supplier ";
            $query = $this->db->query($sql);
            return $query->rows;  
        }
		 public function update_file($order_id,$file_name)
        {
            $sql2="update  oc_supplier_po_invoice set file_path='".$file_name."' where sid='".$order_id."'";
            $query2 = $this->db->query($sql2);
        }
	public function get_ledger($data)
	{
		/*
		$sql=" select * from ( 

SELECT spi.grand_total as total_debit,'' as total_credit,spi.invoice_no as tr_number,spi.invoice_date as tr_date,'Invoice' as tr_type,spi.po_no as po_no,spo.status as invoice_status 
from oc_supplier_po_invoice as spi 
left join oc_supplier_po_order as spo on spi.po_no=spo.sid 
where spi.sid!='' and spo.status in ('1','2') and 

spo.supplier_id='".$data['filter_supplier']."' 
and date(spi.invoice_date)>='".$data['filter_date_start']."'
and date(spi.invoice_date)<='".$data['filter_date_end']."'

union 

SELECT '' as total_debit,spo.payment_amount as total_credit,
scp.tr_number as tr_number,
date(spo.payment_date) as tr_date,
'Payment' as tr_type,
spo.sid as po_no,
'' as invoice_status 
from oc_supplier_po_invoice as spi 
left join oc_supplier_po_order as spo on spi.po_no=spo.sid 
left join oc_supplier_credit_posting as scp on spo.sid=scp.po_number where spo.status='2' and 

spo.supplier_id='".$data['filter_supplier']."' 
and date(spo.payment_date)>='".$data['filter_date_start']."'
and date(spo.payment_date)<='".$data['filter_date_end']."'

) as aa order by aa.tr_date asc  ";
		*/
		$sql=" select * from ( 

SELECT spi.grand_total as total_debit,'' as total_credit,spi.invoice_no as tr_number,spi.invoice_date as tr_date,'Invoice' as tr_type,spi.po_no as po_no,spo.status as invoice_status
from oc_supplier_po_invoice as spi 
left join oc_supplier_po_order as spo on spi.po_no=spo.sid 
where spi.sid!='' and spo.status in ('1','2') and 

spo.supplier_id='".$data['filter_supplier']."' 
and date(spi.invoice_date)>='".$data['filter_date_start']."'
and date(spi.invoice_date)<='".$data['filter_date_end']."'

union 

SELECT '' as total_debit,scp.amount as total_credit,
scp.tr_number as tr_number,
(case when (scp.payment_method = 'CREDIT NOTE') 
 THEN
      scp.invoice_date
 ELSE
      date(scp.create_date)
 END)
  as tr_date,
scp.payment_method as tr_type,
'NA' as po_no,
'' as invoice_status 
from oc_supplier_credit_posting  as scp

 where scp.sid>0 
and scp.payment_method not in ('Adjustment') and
scp.supplier_id='".$data['filter_supplier']."' 
and (case when scp.payment_method = 'CREDIT NOTE'  then date(scp.invoice_date) else date(scp.create_date) end) >='".$data['filter_date_start']."'
and (case when scp.payment_method = 'CREDIT NOTE'  then date(scp.invoice_date) else date(scp.create_date) end)<='".$data['filter_date_end']."'

union 

SELECT '' as total_debit,scp.amount as total_credit,
scp.tr_number as tr_number,
      scp.create_date
  as tr_date,
scp.payment_method as tr_type,
scp.po_number as po_no,
'' as invoice_status 
from oc_supplier_credit_posting  as scp

 where scp.sid>0 
and scp.payment_method in ('CREDIT NOTE') 
and scp.transaction_type in ('CREDIT NOTE') and
scp.supplier_id='".$data['filter_supplier']."' 
and  date(scp.create_date)  >='".$data['filter_date_start']."'
and   date(scp.create_date) <='".$data['filter_date_end']."'

) as aa order by aa.tr_date asc  ";

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

	public function get_ledger_total($data)
	{
		$sql=" select count(*) as total,sum(total_debit) as total_debit,sum(total_credit) as total_credit from ( 

SELECT spi.grand_total as total_debit,'' as total_credit,spi.invoice_no as tr_number,spi.invoice_date as tr_date,'Invoice' as tr_type,spi.po_no as po_no,spo.status as invoice_status
from oc_supplier_po_invoice as spi 
left join oc_supplier_po_order as spo on spi.po_no=spo.sid 
where spi.sid!='' and spo.status in ('1','2') and 

spo.supplier_id='".$data['filter_supplier']."' 
and date(spi.invoice_date)>='".$data['filter_date_start']."'
and date(spi.invoice_date)<='".$data['filter_date_end']."'

union 

SELECT '' as total_debit,scp.amount as total_credit,
scp.tr_number as tr_number,
(case when (scp.payment_method = 'CREDIT NOTE') 
 THEN
      scp.invoice_date
 ELSE
      date(scp.create_date)
 END)
  as tr_date,
scp.payment_method as tr_type,
'NA' as po_no,
'' as invoice_status 
from oc_supplier_credit_posting  as scp

 where scp.sid>0 
and scp.payment_method not in ('Adjustment') and
scp.supplier_id='".$data['filter_supplier']."' 
and (case when scp.payment_method = 'CREDIT NOTE'  then date(scp.invoice_date) else date(scp.create_date) end) >='".$data['filter_date_start']."'
and (case when scp.payment_method = 'CREDIT NOTE'  then date(scp.invoice_date) else date(scp.create_date) end)<='".$data['filter_date_end']."'

union 

SELECT '' as total_debit,scp.amount as total_credit,
scp.tr_number as tr_number,
      scp.create_date
  as tr_date,
scp.payment_method as tr_type,
scp.po_number as po_no,
'' as invoice_status 
from oc_supplier_credit_posting  as scp

 where scp.sid>0 
and scp.payment_method in ('CREDIT NOTE') 
and scp.transaction_type in ('CREDIT NOTE') and
scp.supplier_id='".$data['filter_supplier']."' 
and  date(scp.create_date)  >='".$data['filter_date_start']."'
and   date(scp.create_date) <='".$data['filter_date_end']."'

) as aa   ";
        $query = $this->db->query($sql);
        return $query->row;
	}
	
	public function get_ledger_opening_balance($data)
	{
		$sql=" select sum(total_debit) as total_debit,sum(total_credit) as total_credit from ( 

SELECT spi.grand_total as total_debit,'' as total_credit,spi.invoice_no as tr_number,spi.invoice_date as tr_date,'Invoice' as tr_type,spi.po_no as po_no,spo.status as invoice_status
from oc_supplier_po_invoice as spi 
left join oc_supplier_po_order as spo on spi.po_no=spo.sid 
where spi.sid!='' and spo.status in ('1','2') and 

spo.supplier_id='".$data['filter_supplier']."' 

and date(spi.invoice_date)<='".$data['filter_date_start']."'

union 

SELECT '' as total_debit,scp.amount as total_credit,
scp.tr_number as tr_number,
(case when (scp.payment_method = 'CREDIT NOTE') 
 THEN
      scp.invoice_date
 ELSE
      date(scp.create_date)
 END)
  as tr_date,
scp.payment_method as tr_type,
'NA' as po_no,
'' as invoice_status 
from oc_supplier_credit_posting  as scp

 where scp.sid>0 
and scp.payment_method not in ('Adjustment') and
scp.supplier_id='".$data['filter_supplier']."' 

and (case when scp.payment_method = 'CREDIT NOTE'  then date(scp.invoice_date) else date(scp.create_date) end)<='".$data['filter_date_start']."'

) as aa   ";
//echo $sql;
        $query = $this->db->query($sql);
        return $query->row;
	}
	

          public function update_po_qnty($po_id,$old_qnty,$new_qnty,$user_id,$amount,$remarks) {
            $log=new Log('supplier-'.date('Y-m-d').'.log');
           
          $sql3="update oc_supplier_po_order set Quantity='".$new_qnty."',`old_qnty`='".$old_qnty."',`revised_date`=NOW(),`revised_by`='".$user_id."',`revised_status`='1',
		`amount`='".$amount."',`remarks`='".$remarks."' where sid='".$po_id."' "; 
	 $log->write($sql3); 
           return $query = $this->db->query($sql3); 
             
                     
        }


	public function getTaxList($data)
	{
            $sql="SELECT osi.po_no,osi.invoice_no,osi.invoice_date as invoice_date,
			osi.amount,osi.cgst_type as tax_type,
			osi.cgst_value as cgst,osi.discount as discount,
			osi.sub_total as sub_total,osi.transport_charges as transport_charges,
			osi.grand_total as grand_total,op.model as product,osi.Quantity,osi.unit as unit,
			osi.rate as rate,oc_supplier_po_order.sid as po_id,
			oc_supplier_po_order.id_prefix,oc_supplier_po_order.supplier_id,
			oc_supplier_po_order.status,os.name as delivery_address,
			concat(ps.first_name,ps.last_name) as supplier,
			oc_supplier_po_order.supplier_id,ps.gst as supplier_gst 
			FROM oc_supplier_po_invoice as osi

LEFT JOIN oc_supplier_po_order  on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where osi.sid!=''";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(osi.invoice_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(osi.invoice_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        if ($data['filter_status']!='') 
                        {
                            $sql .=" and oc_supplier_po_order.status='".$data['filter_status']."'";
			
                        }
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.="ORDER BY osi.invoice_date DESC ";
                
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
	public function getTotalTaxOrders($data)
	{
		$sql="select count(*) as total_orders from ( SELECT osi.po_no FROM oc_supplier_po_invoice as osi

LEFT JOIN oc_supplier_po_order  on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where osi.sid!='' ";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(osi.invoice_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(osi.invoice_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_order.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        if ($data['filter_status']!='') 
                        {
                            $sql .=" and oc_supplier_po_order.status='".$data['filter_status']."'"; 
			
                        }
                       
                        $sql.=" ) as aa ";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row['total_orders'];
		//return $results['total_orders'];
	}



}
?>