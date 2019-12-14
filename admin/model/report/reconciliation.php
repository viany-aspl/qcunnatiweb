<?php
class ModelReportReconciliation extends Model 
{
	public function tagged_amount($data = array()) 
	{
		$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		
		$sql = "SELECT o.date_start as date_start,o.sid as letter_no,o.unit_id as unit_id,o.total_amount as total_amount,u.unit_name as unit_name
		from oc_tagged_bill_trans o  
		LEFT JOIN oc_store os on o.store_id=os.store_id 
		left join oc_unit as u on o.unit_id=u.unit_id 
		where  o.sid>0 ";

		$sql .= "  and o.unit_id in ('01','02','03','04') ";
		if(!empty($data['filter_unit']))
		{
			$sql.=" and o.unit_id='".$data['filter_unit']."'  ";
		}
		
		$sql .= " ORDER BY DATE(o.date_start)  ASC";

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
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function tagged_amountTotal($data = array()) 
	{
		$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		
		$sql = "SELECT count(o.sid) as total
		from oc_tagged_bill_trans o  where  o.sid>0 ";

		$sql .= "  and o.unit_id in ('01','02','03','04') ";
		if(!empty($data['filter_unit']))
		{
			$sql.=" and o.unit_id='".$data['filter_unit']."'  ";
		}
		//echo $sql; 
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getorderproducts($inv) 
	{
		$sql = "SELECT order_id,name,tagged_quantity,quantity,price,tax,total,SubSidyPer,SUBSIDY_CAT_DESC,discount_type,discount_value,reward FROM oc_order_product where order_id='".$inv."'";
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function update_bcml_upload($data=array()) 
	{
		foreach($data as $data1)
		{
			//print_r($data1);
			$sql=" update oc_order set bcml_upload='1' where order_id='".$data1['invoiceno']."'  ";
			$query = $this->db->query($sql);
		}

	}

	public function delete_file_data_tagged($file_aspl) 
	{
		$sql = "delete from  oc_tagged_bill_trans  where `sid`='".$this->db->escape($file_aspl)."' ";
		$query = $this->db->query($sql);

	}

	public function getOrdersBcmlAllStatus($data = array()) 
	{
		$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,
		o.subsidy,o.total,o.tagged,o.bcml_tagged,o.comment as requisition_id,
		o.comment as o_requisition_id,
		o.shipping_address_2,
		'' as company,o.payment_address_1,
		o.payment_address_1 as o_payment_address_1,
		o.payment_firstname as o_payment_firstname,
		o.shipping_firstname,
		(SELECT GROUP_CONCAT(unit_name) as unit_name FROM `oc_store_to_unit` as osu left join oc_unit on osu.unit_id=oc_unit.unit_id WHERE osu.store_id=o.store_id group by osu.store_id  ) as unit_name   
		from oc_order o  
		LEFT JOIN oc_store os on os.store_id=o.store_id 
		left join oc_store_to_unit as osun on o.store_id=osun.store_id 
		
		where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' or o.`payment_method`='Tagged Cash Subsidy' )  ";

		$sql .= " and o.order_status_id < '9'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
		if (!empty($data['filter_date'])) {
			$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) ";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}

 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
		if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND osun.unit_id='".$data['filter_unit']."'  ";  
                }
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
		$log=new Log("create_bill_bcml-".date('Y-m-d').".log"); 
		$log->write($sql);
		$query = $this->db->query($sql);
		return $query->rows;
	}		
public function getOrdersBcml($data = array()) {
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,
		o.total,o.tagged,o.bcml_tagged,o.comment as requisition_id,
		o.comment as o_requisition_id,
		o.shipping_address_2,
		'' as company,o.payment_address_1,
		o.payment_address_1 as o_payment_address_1,
		o.payment_firstname as o_payment_firstname,
		o.shipping_firstname,
		(SELECT GROUP_CONCAT(unit_name) as unit_name FROM `oc_store_to_unit` as osu left join oc_unit on osu.unit_id=oc_unit.unit_id WHERE osu.store_id=o.store_id group by osu.store_id  ) as unit_name   
		from oc_order o  
		LEFT JOIN oc_store os on os.store_id=o.store_id 
		left join oc_store_to_unit as osun on o.store_id=osun.store_id 
		
		where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' )  and o.bcml_upload='0' ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
		if (!empty($data['filter_date'])) {
			$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) ";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}

 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
		if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND osun.unit_id='".$data['filter_unit']."'  ";  
                }
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
		$log=new Log("create_bill_bcml-".date('Y-m-d').".log"); 
		$log->write($sql);
		$query = $this->db->query($sql);
		return $query->rows;
	}		
public function getOrders($data = array()) {
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "SELECT o.store_name,o.store_id,o.order_id,
		DATE(o.date_added) as date,
		o.total,o.tagged,o.subsidy,
		o.cash,o.grower_id as dscl_grower_id,o.bcml_tagged,
		o.comment as requisition_id,
		o.comment as o_requisition_id,
		o.shipping_address_2,
		'' as company,o.payment_address_1,
		o.payment_address_1 as o_payment_address_1,
		o.payment_firstname as o_payment_firstname,
		o.shipping_firstname,
		oc_order_delivery.fmcode as fmcode,
		oc_order_delivery.fmname as fmname,
		(SELECT GROUP_CONCAT(unit_name) as unit_name FROM `oc_store_to_unit` as osu left join oc_unit on osu.unit_id=oc_unit.unit_id WHERE osu.store_id=o.store_id group by osu.store_id  ) as unit_name   
		from oc_order o  
		LEFT JOIN oc_store os on os.store_id=o.store_id 
		left join oc_order_delivery on oc_order_delivery.invoice_no=o.order_id
		left join oc_store_to_unit as osun on o.store_id=osun.store_id ";
		
		if($data['filter_company']=='1')
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash'  ) ";
		}
		else
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' ) ";
		} 
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
		if (!empty($data['filter_date'])) {
			$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) ";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}
 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
		if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND osun.unit_id='".$data['filter_unit']."'  ";   
                }
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
public function getOrdersDuplicate($data = array()) {
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,
		o.total,o.tagged,o.cash,o.grower_id as dscl_grower_id,
		o.bcml_tagged,o.comment as requisition_id,
		o.comment as o_requisition_id,o.shipping_address_2,
		'' as company,o.payment_address_1,
		o.payment_address_1 as o_payment_address_1,
		o.payment_firstname as o_payment_firstname,o.shipping_firstname,
		(SELECT GROUP_CONCAT(unit_name) as unit_name FROM `oc_store_to_unit` as osu left join oc_unit on osu.unit_id=oc_unit.unit_id WHERE osu.store_id=o.store_id group by osu.store_id  ) as unit_name   
		from oc_order o  
		LEFT JOIN oc_store os on os.store_id=o.store_id 
		left join oc_store_to_unit as osun on o.store_id=osun.store_id 
		where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' ) ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
		if (!empty($data['filter_date'])) {
			//$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
			//$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) ";
			//$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}

 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
		if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND osun.unit_id='".$data['filter_unit']."'  ";  
                }
	if (!empty($data['invoice_number'])) 
                {
                $sql .=" AND  o.order_id in (".$data['invoice_number'].") ";  
                }
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
public function getOrdersProductsSummaryNewBillingDuplicate($data = array()) 
{
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "SELECT op.name as product_name,
		op.product_id as product_id,
		sum(op.quantity) as total_quantity,
		sum(op.tagged_quantity) as total_tagged_quantity,
		sum(SubsidyAmount) as total_SubsidyAmount,
		op.price as product_price,
		op.tax as product_tax,
		o.comment as req_id from  
		oc_order_product AS op
        INNER JOIN oc_order AS o
     ON o.order_id = op.order_id ";
	 if($data['filter_company']=='1')
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash'  ) ";
		}
		else
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' ) ";
		}
	 
	 $sql.="and concat('',o.payment_address_1 * 1) != o.payment_address_1 ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
	if (!empty($data['filter_date'])) {
			//$sql .= " AND DATE(o.date_added) = '" . $this->db->escape($data['filter_date']) . "'"; 
		}

 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
	if (!empty($data['invoice_number'])) 
                {
                $sql .=" AND  o.order_id in (".$data['invoice_number'].") ";  
                }
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY op.product_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
public function getOrdersProductsSummary($data = array()) {
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "SELECT op.name as product_name,op.product_id as product_id,sum(op.quantity) as total_quantity,op.price as product_price,op.tax as product_tax,o.comment as req_id from  oc_order_product AS op
        INNER JOIN oc_order AS o
     ON o.order_id = op.order_id where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
		if (!empty($data['filter_date'])) {
			$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) ";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}

 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
	
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY op.product_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
public function getOrdersProductsSummaryNewBilling($data = array()) {
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "SELECT op.name as product_name,op.product_id as product_id,
		sum(op.quantity) as total_quantity,
		sum(op.tagged_quantity) as total_tagged_quantity,
		sum(SubsidyAmount) as total_SubsidyAmount,
		op.price as product_price,op.tax as product_tax,o.comment as req_id from  oc_order_product AS op
        INNER JOIN oc_order AS o
     ON o.order_id = op.order_id ";
	 if($data['filter_company']=='1')
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash'  ) ";
		}
		else
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' ) ";
		}
	 
	 $sql.=" and concat('',o.payment_address_1 * 1) != o.payment_address_1 ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
		if (!empty($data['filter_date'])) {
			$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) ";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}

 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
	
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY op.product_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
public function getTotalOrders($data = array()) {
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "select count(*) as total,sum(tagged) as total_tagged_amount from (SELECT o.store_name,o.order_id,DATE(o.date_added) as date,o.total,o.tagged from oc_order o  Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id left JOIN oc_unit ou on ou.unit_id=ol.shipping_address_2 left join oc_store_to_unit as osun on o.store_id=osun.store_id where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' )  ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			
		}
		
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ( ol.shipping_address_2='".$data['filter_unit']."' OR osun.unit_id='".$data['filter_unit']."' ) ";  
                }
                 $sql .=" AND o.payment_company!='spray'";

		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC) as aa";

                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}

 public function getGrowerDetails($order_id) 
        {
            //echo $order_id;
            $sql = "SELECT requisition_id from oc_requisition_to_bill o where `bill_id`='".$order_id."' ";
            $query = $this->db->query($sql);
            $req_id=$query->row["requisition_id"];
            
            $sql2 = "SELECT payment_address_1,order_id from oc_order_leads o where `order_id`='".$req_id."' ";
            $query2 = $this->db->query($sql2);
            return $data["farmer_info_string"]=$query2->row["payment_address_1"]."-".$req_id;                                                            
        
        }

 public function getGrowerDetailsByNum($phone) 
        {
            
            
            $sql2 = "SELECT payment_address_1,order_id from oc_order_leads o where `telephone`='".$phone."' ";
            $query2 = $this->db->query($sql2);
            return $data["farmer_info_string"]=$query2->row["payment_address_1"];                                                            
        
        }

public function getGrowerDetailsd($order_id) 
        {
            //echo $order_id;
            $sql = "SELECT requisition_id from oc_requisition_to_bill o where `bill_id`='".$order_id."' ";
            $query = $this->db->query($sql);
            $req_id=$query->row["requisition_id"];
            
            $sql2 = "SELECT payment_address_1,order_id,ou.unit_name from oc_order_leads o left join oc_unit ou on ou.unit_id=o.shipping_address_2 where `order_id`='".$req_id."' ";
            $query2 = $this->db->query($sql2);
            return $data["farmer_info_string"]=$query2->row["payment_address_1"]."-".$req_id."-".$query2->row["unit_name"];                                                            
        
        }


/////////////////////////////spray///////////////////////////////
public function getOrdersSpray($data = array()) { 
		$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,rb.requisition_id,ol.shipping_address_2,ou.unit_name as company,ol.payment_address_1   from oc_order o  LEFT JOIN oc_store os on os.store_id=o.store_id Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id left JOIN oc_unit ou on ou.unit_id=ol.shipping_address_2 where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) and o.`payment_company`='spray' ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
	if (!empty($data['filter_date'])) {
			$sql .= " AND DATE(o.date_added) = '" . $this->db->escape($data['filter_date']) . "'";
		}

                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
		if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ol.shipping_address_2='".$data['filter_unit']."'";
                }

	
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
public function getTotalOrdersSpray($data = array()) {
		$sql = "select count(*) as total,sum(tagged) as total_tagged_amount from (SELECT o.store_name,o.order_id,DATE(o.date_added) as date,o.total,o.tagged from oc_order o  Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id left JOIN oc_unit ou on ou.unit_id=ol.shipping_address_2 where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) and o.`payment_company`='spray' ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ol.shipping_address_2='".$data['filter_unit']."'";
                }

		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC) as aa";

                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	
public function getOrder_detail($order_id) {


	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			
	return $query->rows;

								
	}

	
public function getProductSubsidyValue($store_id,$product_id) {


	$query = $this->db->query("SELECT oc_product_subsidy.subsidy as subsidy,oc_category_subsidy.category_name as category_name FROM `oc_product_subsidy` left join oc_category_subsidy on oc_product_subsidy.category_id=oc_category_subsidy.category_id  WHERE oc_product_subsidy.store_id = '" . (int)$store_id . "' and oc_product_subsidy.product_id = '" . (int)$product_id . "' limit 1 ");

			
	return $query->row;

								
	}


public function getOrder_ids_tagged($data=array()) {
$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
$sql = "
SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,os.company  from oc_order o LEFT JOIN oc_store os on os.store_id=o.store_id Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id left JOIN oc_unit ou on ou.unit_id=ol.shipping_address_2 where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";

  
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			
		}
		

                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ol.shipping_address_2='".$data['filter_unit']."'";
                }
	
		$sql .= " GROUP BY o.date_added ";
		$sql .= " ORDER BY o.date_added ASC";

		
		              // echo $sql; 
		$query = $this->db->query($sql);
//return $query->rows;
$data = array();
		foreach($query->rows as $rows1)
		{
			
            //echo $order_id;
            $sql1 = "SELECT requisition_id from oc_requisition_to_bill o where `bill_id`='".$rows1["order_id"]."' ";
            $query1 = $this->db->query($sql1);
            $req_id=$query1->row["requisition_id"];
            
            //$sql2 = "SELECT payment_address_1,order_id from oc_order_leads o where `order_id`='".$req_id."' ";
            ///$query2 = $this->db->query($sql2);
            //return $data["farmer_info_string"]=$query2->row["payment_address_1"]."-".$req_id;                                                            
        
        
			$data[] = $req_id;
		}
return $data;
								
	}

	public function get_file_data_tagged($data=array()) 
	{
		
		$sql = "select * from oc_tagged_bill_trans where `date_start`='".$this->db->escape($data['filter_date'])."'  and `store_id`='".$this->db->escape($data['filter_store'])."'   and `unit_id`='".$this->db->escape($data['filter_unit'])."' ";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function get_file_data_subsidy($data=array()) 
	{
		$sql = "select * from oc_subsidy_bill_trans where `date_start`='".$this->db->escape($data['filter_date'])."'  and `store_id`='".$this->db->escape($data['filter_store'])."'  and `unit_id`='".$this->db->escape($data['filter_unit'])."' ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function add_file_data_tagged($data=array(),$filename) 
	{
		$cr_date=date('Y-m-d');
		$sql = "select * from oc_tagged_bill_trans where `date_start`='".$this->db->escape($data['filter_date'])."'  and `store_id`='".$this->db->escape($data['filter_store'])."'  and `unit_id`='".$this->db->escape($data['filter_unit'])."' ";
		$query = $this->db->query($sql);
		if(count($query->row)>0)
		{
			$return_id=$query->row['sid'];
			$sql = "update oc_tagged_bill_trans set `date_start`='".$this->db->escape($data['filter_date'])."',`store_id`='".$this->db->escape($data['filter_store'])."',`file_name`='".$filename."',`create_date`='".$cr_date."',`unit_id`='".$this->db->escape($data['filter_unit'])."' where sid='".$return_id."' ";
			$query = $this->db->query($sql);

			return $return_id;
		}
		else
		{
			$sql = "insert into oc_tagged_bill_trans set `date_start`='".$this->db->escape($data['filter_date'])."',`store_id`='".$this->db->escape($data['filter_store'])."',`file_name`='".$filename."',`create_date`='".$cr_date."',`unit_id`='".$this->db->escape($data['filter_unit'])."' ";
			$query = $this->db->query($sql);
			return $this->db->getLastId();
		}
	}
	public function add_file_data_subsidy($data=array(),$filename) 
	{
		$cr_date=date('Y-m-d');
		$sql = "select * from oc_subsidy_bill_trans where `date_start`='".$this->db->escape($data['filter_date'])."'  and `store_id`='".$this->db->escape($data['filter_store'])."'   and `unit_id`='".$this->db->escape($data['filter_unit'])."' ";
		$query = $this->db->query($sql);
		if(count($query->row)>0)
		{
			$return_id=$query->row['sid'];
			$sql = "update oc_subsidy_bill_trans set `date_start`='".$this->db->escape($data['filter_date'])."',`store_id`='".$this->db->escape($data['filter_store'])."',`file_name`='".$filename."',`create_date`='".$cr_date."',`unit_id`='".$this->db->escape($data['filter_unit'])."' where sid='".$return_id."' ";
			$query = $this->db->query($sql);

			return $return_id;
		}
		else
		{
			$sql = "insert into oc_subsidy_bill_trans set `date_start`='".$this->db->escape($data['filter_date'])."',`store_id`='".$this->db->escape($data['filter_store'])."',`file_name`='".$filename."',`create_date`='".$cr_date."',`unit_id`='".$this->db->escape($data['filter_unit'])."' ";
			$query = $this->db->query($sql);
			return $this->db->getLastId();
		}
	}
	public function update_file_data_tagged($data=array(),$file_aspl,$total_amount,$logged_user,$filename) 
	{
		$cr_date=date('Y-m-d');
		$sql = "update oc_tagged_bill_trans set `created_update_date`='".$cr_date."',`total_amount`='".$total_amount."',`creted_user`='".$logged_user."',file_name='".$filename."' where `sid`='".$this->db->escape($file_aspl)."' ";
		$query = $this->db->query($sql);

	}
	public function update_file_data_subsidy($data=array(),$file_aspl,$total_amount,$logged_user,$filename) 
	{
		$cr_date=date('Y-m-d');
		$sql = "update oc_subsidy_bill_trans set `created_update_date`='".$cr_date."',`total_amount`='".$total_amount."',`creted_user`='".$logged_user."',file_name='".$filename."' where `sid`='".$this->db->escape($file_aspl)."' ";
		$query = $this->db->query($sql);

	}

public function add_file_data_tagged_duplicate($data=array(),$filename='',$file_number='') 
{
$cr_date=date('Y-m-d');
echo $sql = "insert into oc_tagged_bill_trans set company_id='".$data['filter_company']."',accepted_date='',`date_start`='".$this->db->escape($data['filter_date'])."',`store_id`='".$this->db->escape($data['filter_store'])."',`file_name`='".$filename."',`create_date`='".$cr_date."',`unit_id`='".$this->db->escape($data['filter_unit'])."' ";
$query = $this->db->query($sql);
return $this->db->getLastId(); 
}
public function update_file_data_tagged_duplicate($filename='',$file_number='') 
{
$cr_date=date('Y-m-d');
$sql = "update oc_tagged_bill_trans set `file_name`='".$filename."' where sid='".$file_number."' ";
$query = $this->db->query($sql);

}




public function get_store_unit($store_id)
{
	
$sql = " select oc_unit.unit_id,oc_unit.unit_name from oc_unit left join oc_store on oc_unit.unit_id=oc_store.unit_id left join oc_store_to_unit on oc_unit.unit_id=oc_store_to_unit.unit_id where oc_store_to_unit.store_id='".$store_id."'  GROUP by unit_id   ";
$query = $this->db->query($sql);
return $query->rows;
}



public function get_today_bills()
{
$cur_date=date('Y-m-d');
$sql = " SELECT order_id,comment FROM `oc_order`  where DATE(date_added)='".$cur_date."' and comment<>'' and comment <> '0'  ";
$log=new Log("reconciliation-to-bill-".date('Y-m-d').".log");
$log->write($sql);
$query = $this->db->query($sql);
return $query->rows;
}


//company wise
public function getOrdersCompanywise($data = array()) {

//$log=new Log("a-getOrdersCompanywise-".date('Y-m-d').".log");
$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		if($data['filter_company']=='1')
		{

		$sql = "SELECT o.store_name,o.store_id,
		concat(o.invoice_prefix,'-',o.invoice_no) as order_id,
		DATE(o.date_added) as date,o.total,o.tagged,
		o.cash,o.bcml_tagged as bcml_tagged,o.grower_id as dscl_grower_id,
		o.cash,o.comment as requisition_id,
		'0' as shipping_address_2,
		'' as company,'0' as payment_address_1,
		o.payment_address_1 as o_payment_address_1,
		o.payment_firstname as o_payment_firstname,o.shipping_firstname,
		(SELECT GROUP_CONCAT(unit_name) as unit_name FROM `oc_store_to_unit` as osu left join oc_unit on osu.unit_id=oc_unit.unit_id WHERE osu.store_id=o.store_id group by osu.store_id  ) as unit_name   
		from oc_order o  
		LEFT JOIN oc_store os on os.store_id=o.store_id 
		
		
		left join oc_store_to_unit as osun on o.store_id=osun.store_id 
		
		where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";
             }

	else
	{
		$sql = "SELECT o.store_name,o.store_id,o.order_id,
		DATE(o.date_added) as date,o.total,o.tagged,
		o.subsidy,
		o.cash,o.bcml_tagged as bcml_tagged,
		o.grower_id as dscl_grower_id,o.cash,o.comment as requisition_id,
		o.shipping_address_2,
		'' as company,o.payment_address_1,
		o.payment_address_1 as o_payment_address_1,
		o.payment_firstname as o_payment_firstname,
		o.shipping_firstname,
		(SELECT GROUP_CONCAT(unit_name) as unit_name FROM `oc_store_to_unit` as osu left join oc_unit on osu.unit_id=oc_unit.unit_id WHERE osu.store_id=o.store_id group by osu.store_id  ) as unit_name   
		from oc_order o  
		LEFT JOIN oc_store os on os.store_id=o.store_id 
		left join oc_store_to_unit as osun on o.store_id=osun.store_id 
		where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' ) ";

		}


                $sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
	if (!empty($data['filter_date'])) {
		$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
		$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) ";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}

                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
	  if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND  osun.unit_id='".$data['filter_unit']."'  ";
                }
                $sql .=" AND o.payment_company!='spray'";
               
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.store_name,o.order_id ASC"; 

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//$log->write($sql);
        //echo $sql; 
		$query = $this->db->query($sql);

		return $query->rows;
	}
        
        public function getTotalOrdersCompanywise($data = array()) {
		
         $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));       
            $sql="SELECT
    COUNT(*) AS total, SUM(tagged) AS total_tagged_amount,SUM(subsidy) AS total_subsidy_amount,SUM(bcml_tagged) AS total_bcml_tagged_amount,SUM(cash) AS total_cash_amount
FROM
    (SELECT
        o.store_name,
            o.order_id,
            DATE(o.date_added) AS date,
            o.total,
            o.tagged,
			o.subsidy,
	o.bcml_tagged,
	o.cash,
            os.company_id
    FROM
        oc_order o
    
    LEFT JOIN oc_store AS os on os.store_id = o.store_id 
	left join oc_store_to_unit as osun on o.store_id=osun.store_id ";
	
	if($data['filter_company']=='1')
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";
		}
		else
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash'  or o.`payment_method`='Tagged Subsidy'  ) ";
		}
                $sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND osun.unit_id='".$data['filter_unit']."'  ";
                }
                 $sql .=" AND o.payment_company!='spray'";

		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC) as aa";

               //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	/////for dscl////////
	public function getOrdersCompanywiseDscl($data = array()) 
	{
	
		$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		
		$sql = "SELECT o.dscl_upload as dscl_upload,concat(o.invoice_prefix,' ',o.invoice_no) as bill_no, 
		o.card_serial_no as o_csn,o.store_name,
		o.store_id,o.order_id,DATE(o.date_added) as date,
		o.total,o.tagged,o.subsidy as subsidy,o.payment_method as payment_method,o.cash,o.bcml_tagged as bcml_tagged,
		o.grower_id as dscl_grower_id,o.cash,rb.requisition_id,
		o.shipping_address_2,ou.unit_name as company,
		o.payment_address_1,o.payment_address_1 as o_payment_address_1,
		o.payment_firstname as o_payment_firstname,o.shipping_firstname, 
		ou.unit_name as unit_name,
		ouu.unit_name as unit_name_subsidy,
		
		o.subsidy_category as subsidy_category,
		o.subsidy_form_no as subsidy_form_no,
		oc_category_subsidy.category_name as subsidy_category_name
		from oc_order o
		left join oc_category_subsidy on o.subsidy_category=oc_category_subsidy.category_id 		
		LEFT JOIN oc_requisition_to_bill rb ON rb.bill_id = o.order_id
		LEFT JOIN oc_store os on os.store_id=o.store_id 
		left JOIN oc_unit ou on ou.unit_id=o.unit_id 
		left join oc_unit ouu on ouu.unit_id=o.payment_address_1 
		left join oc_store_to_unit as osun on o.store_id=osun.store_id ";
		if($data['bill_type']=='subsidy')
		{
			$sql.=" where ( o.`payment_method`='Tagged Cash Subsidy' or o.`payment_method`='Tagged Subsidy' or o.`payment_method`='Cash Subsidy' ) ";//o.`payment_method`='Subsidy'or
		}
		else if($data['bill_type']=='subsidy-pink')
		{
			$sql.=" where ( o.`payment_method`='Subsidy'  ) ";//o.`payment_method`='Subsidy'or
		}
		else
		{
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Cash Subsidy' or o.`payment_method`='Tagged Subsidy' or o.`payment_method`='Cash Subsidy' ) ";
		}
		
		//$sql.=" where (o.`payment_method`='Tagged'   or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' or o.`payment_method`='Subsidy' ) ";
		
		$sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
			}
		}
	if (!empty($data['filter_date'])) {
		
		$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
		
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME)";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME)";
		}

        if (!empty($data['filter_store'])) 
        {
            $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
        }
		if($data['bill_type']=='subsidy')
		{
			if (!empty($data['filter_unit'])) 
			{
				$sql .=" AND ( o.payment_address_1='".$data['filter_unit']."' OR  o.unit_id='".$data['filter_unit']."'  ) ";
			}
		}
		else if($data['bill_type']=='subsidy-pink')
		{
			if (!empty($data['filter_unit'])) 
			{
				$sql .=" AND ( o.payment_address_1='".$data['filter_unit']."' OR  o.unit_id='".$data['filter_unit']."'  ) ";
			}
		}
		else
		{
			if (!empty($data['filter_unit'])) 
			{
				$sql .=" AND ( o.unit_id='".$data['filter_unit']."' ) ";
			}
		}
		
        $sql .=" AND o.payment_company!='spray'";
               
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.order_id ASC"; 

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
        
        public function getTotalOrdersCompanywiseDscl($data = array()) {
		$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
                
            $sql="SELECT
    COUNT(*) AS total, SUM(total) AS total_order_amount,SUM(tagged) AS total_tagged_amount,SUM(cash) AS total_cash_amount,SUM(subsidy) AS total_subsidy_amount
FROM
    (SELECT
        o.store_name,
            o.order_id,
            DATE(o.date_added) AS date,
            o.total,
            o.tagged,
	o.cash,
	o.subsidy,
            os.company_id
    FROM
        oc_order o
    LEFT JOIN oc_requisition_to_bill rb ON rb.bill_id = o.order_id
   
    LEFT JOIN oc_unit ou ON ou.unit_id = o.unit_id
    LEFT JOIN oc_store AS os on os.store_id = o.store_id 
	left join oc_store_to_unit as osun on o.store_id=osun.store_id ";
		
		if($data['bill_type']=='subsidy')
		{
			$sql.=" where (o.`payment_method`='Tagged Subsidy'  or o.`payment_method`='Tagged Cash Subsidy' or o.`payment_method`='Cash Subsidy' ) ";//o.`payment_method`='Subsidy' or 
		}
		else if($data['bill_type']=='subsidy-pink')
		{
			$sql.=" where ( o.`payment_method`='Subsidy'  ) ";//o.`payment_method`='Subsidy'or
		}
		else
		{
			//$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash'  ) ";
			$sql.=" where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Cash Subsidy' or o.`payment_method`='Tagged Subsidy' or o.`payment_method`='Cash Subsidy' ) ";
		}
		
		//$sql.=" where (o.`payment_method`='Tagged'   or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Subsidy' or o.`payment_method`='Subsidy' ) ";
		$sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
        if($data['bill_type']=='subsidy')
		{
			if (!empty($data['filter_unit'])) 
			{
				$sql .=" AND ( o.payment_address_1='".$data['filter_unit']."' OR  o.unit_id='".$data['filter_unit']."'  ) ";
			}
		}
		else if($data['bill_type']=='subsidy-pink')
		{
			if (!empty($data['filter_unit'])) 
			{
				$sql .=" AND ( o.payment_address_1='".$data['filter_unit']."' OR  o.unit_id='".$data['filter_unit']."'  ) ";
			}
		}
		else
		{
			if (!empty($data['filter_unit'])) 
			{
				$sql .=" AND ( o.unit_id='".$data['filter_unit']."' ) ";
			}
		}
                 $sql .=" AND o.payment_company!='spray'";

		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC) as aa";

        //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
////////comppnay wise subsidy orders

public function getSubsidyOrdersCompanywise($data = array()) {
	
	//(SELECT GROUP_CONCAT(unit_name) as unit_name 
	//	FROM `oc_store_to_unit` as osu left join oc_unit on osu.unit_id=oc_unit.unit_id WHERE osu.store_id=o.store_id group by osu.store_id  ) as unit_name
	//LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id 
	//Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id 
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
		$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,o.subsidy,
		o.cash,o.bcml_tagged as bcml_tagged,o.grower_id as dscl_grower_id,o.cash,'' as requisition_id,
		'' as shipping_address_2,ou.unit_name as company,'' as payment_address_1,o.payment_address_1 as o_payment_address_1,
		o.payment_firstname as o_payment_firstname,o.shipping_firstname,(SELECT GROUP_CONCAT(unit_name) as unit_name 
		FROM oc_unit  WHERE oc_unit.unit_id=o.payment_address_1   ) as unit_name,o.invoice_prefix,o.invoice_no,o.subsidy_form_no
		from oc_order o  LEFT JOIN oc_store os on os.store_id=o.store_id 
		
		
		left JOIN oc_unit ou on ou.unit_id=o.payment_address_1 
		left join oc_store_to_unit as osun on o.store_id=osun.store_id where (o.`payment_method`='Subsidy'   ) ";
             

                $sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
			}
		}
	if (!empty($data['filter_date'])) {
		
		$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
		
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME)";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME)";
		}

                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
	  if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ( o.payment_address_1='".$data['filter_unit']."' ) ";
                }
                $sql .=" AND o.payment_company!='spray'";
               
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.store_name ASC"; 

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
        //echo $sql; exit;
		$query = $this->db->query($sql);

		return $query->rows;
	}
        
        public function getTotalSubsidyOrdersCompanywise($data = array()) {
		$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
                
            $sql="SELECT
    COUNT(*) AS total, SUM(subsidy) AS total_subsidy_amount,SUM(cash) AS total_cash_amount
FROM
    (SELECT
        o.store_name,
            o.order_id,
            DATE(o.date_added) AS date,
            o.total,
            o.tagged,
	o.subsidy,
	o.cash,
            os.company_id
    FROM
        oc_order o
    
  
    LEFT JOIN oc_unit ou ON ou.unit_id = o.payment_address_1
    LEFT JOIN oc_store AS os on os.store_id = o.store_id 
	left join oc_store_to_unit as osun on o.store_id=osun.store_id
   where (o.`payment_method`='Subsidy' ) ";
            
                $sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ( o.payment_address_1='".$data['filter_unit']."' ) ";
                }
                 $sql .=" AND o.payment_company!='spray'";

		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC) as aa";

               //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}


        //company spary
public function getOrdersSprayCompanywise($data = array()) { 
		$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,rb.requisition_id,ol.shipping_address_2,ou.unit_name as company,ol.payment_address_1   from oc_order o  LEFT JOIN oc_store os on os.store_id=o.store_id Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id left JOIN oc_unit ou on ou.unit_id=ol.shipping_address_2 left join oc_store_to_unit as osun on o.store_id=osun.store_id where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) and o.`payment_company`='spray' ";
                
                 $sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
	if (!empty($data['filter_date'])) {
			$sql .= " AND DATE(o.date_added) = '" . $this->db->escape($data['filter_date']) . "'";
		}

                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
		if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ( ol.shipping_address_2='".$data['filter_unit']."' OR osun.unit_id='".$data['filter_unit']."' ) ";
                }

	
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
		$query = $this->db->query($sql);

		return $query->rows;
	}
        
        public function getTotalOrdersSprayCompanywise($data = array()) {
		
            $sql="SELECT
    COUNT(*) AS total, SUM(tagged) AS total_tagged_amount
FROM
    (SELECT
        o.store_name,
            o.order_id,
            DATE(o.date_added) AS date,
            o.total,
            o.tagged,
            os.company_id
    FROM
        oc_order o
    LEFT JOIN oc_requisition_to_bill rb ON rb.bill_id = o.order_id
    LEFT JOIN oc_order_leads ol ON ol.order_id = rb.requisition_id
    LEFT JOIN oc_unit ou ON ou.unit_id = ol.shipping_address_2
    LEFT JOIN oc_store as os on os.store_id = o.store_id
	left join oc_store_to_unit as osun on o.store_id=osun.store_id
    WHERE
        (o.`payment_method` = 'Tagged'
            OR o.`payment_method` = 'Tagged Cash')
            AND o.`payment_company` = 'spray'";
                
                $sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ( ol.shipping_address_2='".$data['filter_unit']."' OR osun.unit_id='".$data['filter_unit']."' ) ";
                }

		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC) as aa";

               // echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
        public function getOrderstaggedtotal($data)
{
$sql = "SELECT

DATE(o.date_added) AS date,
sum(o.total) as total,
sum(o.tagged) as tagged,concat(ou.firstname,' ',ou.lastname) as runner_name,o.tagged_submit_date as tagged_submit_date FROM
oc_order o  ";


$sql .=" left join oc_user as ou on o.user_agent=ou.user_id ";


$sql.="  where (o.`payment_method`='Tagged' or o.`payment_method`='Tagged Cash' ) ";

$sql .= " and o.order_status_id = '5' ";

if (!empty($data['filter_date_start'])) {
//$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
//$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}


if (!empty($data['filter_store']))
{
$sql .=" AND o.store_id='".(int)$data['filter_store']."'";
}
if (!empty($data['filter_unit']))
{
$sql .=" AND o.shipping_address_2='".$data['filter_unit']."'";
}
if ($data['type']=="pending")
{
$sql .=" AND o.user_agent=''";
$order_by=" ORDER BY o.date_added DESC";
}
if ($data['type']=="submitted")
{
$sql .=" AND o.user_agent!=''";
$order_by=" ORDER BY o.tagged_submit_date DESC";
}
//$sql .=" AND o.payment_company!='spray'";

$sql .= " GROUP BY date(o.date_added) ";
$sql .= $order_by;

if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$log=new Log("tagbill-".date('Y-m-d').".log");
		$log->write('getOrderstaggedtotal query');
		$log->write($sql);
//echo $sql;
$query = $this->db->query($sql);

return $query->rows;
}
	
	
public function update_runner_id($order_id,$runner_id) 
{
$log=new Log("tagbill-".date('Y-m-d').".log");
$cr_date=date('Y-m-d h:i:s');
$sql = "update oc_order set `user_agent`='".$runner_id."',tagged_submit_date='".$cr_date."' where `order_id`='".$this->db->escape($order_id)."' ";
$query = $this->db->query($sql);
$sql2 = "insert oc_req_to_runner set `order_id`='".$this->db->escape($order_id)."',`runner_id`='".$this->db->escape($runner_id)."' ";
$query2 = $this->db->query($sql2);
$log->write($sql);
$log->write($sql2);
}
function insert_into_bank_transaction($data)
{
$cr_date=date('Y-m-d');
$log=new Log("tagbill-".date('Y-m-d').".log");
$sql2 = "insert oc_bank_transaction set `user_id`='".$this->db->escape($data['user_id'])."',`store_id`='".$this->db->escape($data['store_id'])."',`bank_id`='".$this->db->escape('4')."',`bank_name`='".$this->db->escape('TAGGED BILLS')."',`amount`='".$this->db->escape($data['amount'])."',`status`='".$this->db->escape('0')."',`accept_by`='".$this->db->escape($data['runner_id'])."' ";
$query2 = $this->db->query($sql2);
$log->write($sql2);
}



public function gettaggedOrders($data = array()) {
	
		$log=new Log("tagbill-".date('Y-m-d').".log");
		$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,rb.requisition_id,ol.shipping_address_2,ou.unit_name as company,ol.payment_address_1   from oc_order o  LEFT JOIN oc_store os on os.store_id=o.store_id Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id left JOIN oc_unit ou on ou.unit_id=ol.shipping_address_2 left join oc_store_to_unit as osun on o.store_id=osun.store_id where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";

		$sql="SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,
ol.shipping_address_2,ou.unit_name as company,ol.payment_address_1,o.shipping_firstname as grower_id,o.payment_firstname 

FROM
oc_order o  left join oc_order_leads as ol on o.comment=ol.order_id 
left join oc_unit as ou on ol.shipping_address_2=ou.unit_id where (o.`payment_method`='Tagged' or o.`payment_method`='Tagged Cash' ) ";

		$sql .= " and o.order_status_id = '5'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
	if (!empty($data['filter_date'])) {
			$sql .= " AND DATE(o.date_added) = '" . $this->db->escape($data['filter_date']) . "'";
		}

                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
		if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ( ol.shipping_address_2='".$data['filter_unit']."' ) ";
                }
                //$sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC";

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
		$log->write($sql);
		return $query->rows;
	}
	/////////////////
public function getSubsidyOrders($data = array()) {
        $log=new Log("subsidybill-".date('Y-m-d').".log");

	$log->write('getSubsidyOrders query : ');

        //$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,rb.requisition_id,ol.shipping_address_2,ou.unit_name as company,ol.payment_address_1   from oc_order o  LEFT JOIN oc_store os on os.store_id=o.store_id Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id left JOIN oc_unit ou on ou.unit_id=ol.shipping_address_2  where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";

        $sql="SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,o.subsidy,o.shipping_firstname as grower_id,oc_unit.unit_name

FROM
oc_order o left join oc_store_to_unit as osu on o.store_id=osu.store_id left join oc_unit on osu.unit_id=oc_unit.unit_id  where (o.`payment_method`='Subsidy' ) ";

        $sql .= " and o.order_status_id = '5'";
       
        if (!empty($data['filter_date_start'])) {
            $sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }
    if (!empty($data['filter_date'])) {
            $sql .= " AND DATE(o.date_added) = '" . $this->db->escape($data['filter_date']) . "'";
        }

                if (!empty($data['filter_store']))
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
       
                //$sql .=" AND o.payment_company!='spray'";
   
        $sql .= " GROUP BY o.date_added ";
        $sql .= " ORDER BY o.date_added DESC";

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
        $log->write($sql);
        return $query->rows;
    }
///////////////
   
public function getOrdersSubsidytotal($data)
{
$log=new Log("subsidybill-".date('Y-m-d').".log");
$sql = "SELECT

DATE(o.date_added) AS date,
sum(o.total) as total,
sum(o.tagged) as tagged,sum(o.subsidy) as subsidy,concat(ou.firstname,' ',ou.lastname) as runner_name,o.tagged_submit_date as tagged_submit_date FROM
oc_order o  ";


$sql .=" left join oc_user as ou on o.user_agent=ou.user_id ";


$sql.="  where (o.`payment_method`='Subsidy' ) ";

$sql .= " and o.order_status_id = '5' ";

if (!empty($data['filter_date_start'])) {
//$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
//$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}


if (!empty($data['filter_store']))
{
$sql .=" AND o.store_id='".(int)$data['filter_store']."'";
}
if (!empty($data['filter_unit']))
{
$sql .=" AND ( ol.shipping_address_2='".$data['filter_unit']."'  ) ";
}
if ($data['type']=="pending")
{
$sql .=" AND o.user_agent=''";
$order_by=" ORDER BY o.date_added DESC";
}
if ($data['type']=="submitted")
{
$sql .=" AND o.user_agent!=''";
$order_by=" ORDER BY o.tagged_submit_date DESC";
}
//$sql .=" AND o.payment_company!='spray'";

$sql .= " GROUP BY date(o.date_added) ";
$sql .= $order_by;

if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
       
        $log->write('getOrdersSubsidytotal query');
        $log->write($sql);
//echo $sql;
$query = $this->db->query($sql);

return $query->rows;
}

function insert_into_bank_transaction_subsidy($data)
{
$cr_date=date('Y-m-d');
$log=new Log("subsidybill-".date('Y-m-d').".log");
$sql2 = "insert oc_bank_transaction set `user_id`='".$this->db->escape($data['user_id'])."',`store_id`='".$this->db->escape($data['store_id'])."',`bank_id`='".$this->db->escape('4')."',`bank_name`='".$this->db->escape('SUBSIDY BILLS')."',`amount`='".$this->db->escape($data['amount'])."',`status`='".$this->db->escape('0')."',`accept_by`='".$this->db->escape($data['runner_id'])."' ";
$query2 = $this->db->query($sql2);
$log->write($sql2);
}
public function getTotalOrdersCompanywiseAllStatus($data = array()) {
		
              $data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));  
            $sql="SELECT
    COUNT(*) AS total, SUM(tagged) AS total_tagged_amount,SUM(cash) AS total_cash_amount
FROM
    (SELECT
        o.store_name,
            o.order_id,
            DATE(o.date_added) AS date,
            o.total,
            o.tagged,
	o.cash,
            os.company_id
    FROM
        oc_order o
    LEFT JOIN oc_requisition_to_bill rb ON rb.bill_id = o.order_id
    LEFT JOIN oc_order_leads ol ON ol.order_id = rb.requisition_id
    LEFT JOIN oc_unit ou ON ou.unit_id = ol.shipping_address_2
    LEFT JOIN oc_store AS os on os.store_id = o.store_id 
	left join oc_store_to_unit as osun on o.store_id=osun.store_id
   where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' or o.`payment_method`='Tagged Cash Subsidy' ) ";
            
                $sql .= " and os.company_id='".$data['filter_company']."' ";
		$sql .= " and o.order_status_id < '9' ";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
		}
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
                if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ( ol.shipping_address_2='".$data['filter_unit']."' OR osun.unit_id='".$data['filter_unit']."' ) ";
                }
                 $sql .=" AND o.payment_company!='spray'";

		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC) as aa";

               //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
public function getOrdersAllStatus($data = array()) {
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));  
		$sql = "SELECT o.store_name,o.store_id,o.order_id,DATE(o.date_added) as date,o.total,o.tagged,o.cash,o.bcml_tagged,rb.requisition_id,ol.shipping_address_2,ou.unit_name as company,ol.payment_address_1,o.payment_address_1 as o_payment_address_1,o.payment_firstname as o_payment_firstname,o.shipping_firstname,o.order_status_id as order_status_id,o.comment as req_id2,(SELECT GROUP_CONCAT(unit_name) as unit_name FROM `oc_store_to_unit` as osu left join oc_unit on osu.unit_id=oc_unit.unit_id WHERE osu.store_id=o.store_id group by osu.store_id  ) as unit_name   from oc_order o  LEFT JOIN oc_store os on os.store_id=o.store_id Left join oc_requisition_to_bill rb on rb.bill_id=o.order_id LEFT JOIN oc_order_leads ol on ol.order_id=rb.requisition_id left JOIN oc_unit ou on ou.unit_id=ol.shipping_address_2 left join oc_store_to_unit as osun on o.store_id=osun.store_id where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";

		$sql .= " and o.order_status_id < '9' ";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
	if (!empty($data['filter_date'])) {
		$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
		$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME)";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}

 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
		if (!empty($data['filter_unit'])) 
                {
                $sql .=" AND ( ol.shipping_address_2='".$data['filter_unit']."' OR osun.unit_id='".$data['filter_unit']."' ) ";  
                }
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                echo $sql; 
		$query = $this->db->query($sql);

		return $query->rows;
	}
public function getOrdersProductsSummaryAllStatus($data = array()) {
	$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days")); 
		$sql = "SELECT op.name as product_name,op.product_id as product_id,sum(op.quantity) as total_quantity,op.price as product_price,op.tax as product_tax from  oc_order_product AS op
        INNER JOIN oc_order AS o
     ON o.order_id = op.order_id where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";

		$sql .= " and o.order_status_id < '9' ";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) {
			if (empty($data['filter_date'])) {
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME) ";
			}
		}
	if (!empty($data['filter_date'])) {
		$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days")); 
		$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) "; 
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) "; 
		}

 
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND o.store_id='".(int)$data['filter_store']."'";
                }
	
                $sql .=" AND o.payment_company!='spray'";
	
		$sql .= " GROUP BY op.product_id ";
		$sql .= " ORDER BY o.date_added ASC";

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
	/////////////////////// letter wise/////////////
public function getTaggedBillWithLetter($data)
{
	if ($data['type']=="pending")
	{
		$sql = "SELECT oc_tagged_bill_trans.* FROM `oc_tagged_bill_trans`  ";
		$sql.="  where status=0  ";
		//$sql.=" and oc_tagged_bill_trans.date_start='".$data['filter_date_start']."' ";
		if (!empty($data['filter_store'])) 
		{
			$sql .=" AND oc_tagged_bill_trans.store_id='".(int)$data['filter_store']."'";
		}


		
	}

	if ($data['type']=="submitted")
	{
		$sql =" select oc_tagged_bill_trans.*,concat(oc_user.firstname,' ',oc_user.lastname) as runner_name,oc_bank_transaction.date_added as tagged_submit_date  FROM `oc_tagged_bill_trans`  join oc_bank_transaction on  oc_tagged_bill_trans.sid=oc_bank_transaction.tagged_letter_number left join oc_user on oc_bank_transaction.accept_by=oc_user.user_id ";
		
		$sql.="  where oc_tagged_bill_trans.status=1  ";
		//$sql.=" and oc_tagged_bill_trans.date_start='".$data['filter_date_start']."' ";
		if (!empty($data['filter_store'])) 
		{
			$sql .=" AND oc_tagged_bill_trans.store_id='".(int)$data['filter_store']."'";
		}

		
	}
	$sql .=" order by oc_tagged_bill_trans.date_start DESC  ";
	if (isset($data['start']) || isset($data['limit'])) 
	{
		if ($data['start'] < 0) 
		{
				$data['start'] = 0;
		}

		if ($data['limit'] < 1) 
		{
				$data['limit'] = 20;
		}

		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	$log=new Log("tagbill-letter-".date('Y-m-d').".log");
	$log->write('getTaggedBillWithLetter');
	$log->write($sql);
	//echo $sql;
	$query = $this->db->query($sql);

	return $query->rows;
}
public function gettaggedOrdersletter($data = array()) { 
		$log=new Log("tagbill-letter-".date('Y-m-d').".log");

		$sql1= " select date_start,store_id from oc_tagged_bill_trans where sid='".$data['letter_number']."' limit 1 ";
		$query1 = $this->db->query($sql1);
		$log->write($sql1);
		$data['filter_date']=$query1->row['date_start'];
		$data['filter_store']=$query1->row['store_id'];
		$sql = "SELECT o.store_name as store_name,o.store_id as store_id,o.order_id,order_id,DATE(o.date_added) as date,o.total as total,o.tagged as tagged,o.bcml_tagged as bcml_tagged,
				ou.unit_name as unit_name,o.payment_firstname as payment_firstname,o.shipping_firstname as shipping_firstname  
			 from oc_order o  
			LEFT JOIN oc_store os on o.store_id=os.store_id 
			left join oc_store_to_unit as ostu on o.store_id=ostu.store_id
 			left JOIN oc_unit ou on ostu.unit_id=ou.unit_id
			 where (o.`payment_method`='Tagged'  or o.`payment_method`='Tagged Cash' ) ";

		$sql .= " and o.order_status_id = '5'";
		
		
		if (!empty($data['filter_date'])) {
			$data["filter_date2"]=date('Y-m-d',strtotime($data["filter_date"] . "+1 days"));
			$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date2']) . "' as DATETIME) ";
			$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date']) . "' as DATETIME) ";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND o.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
		$sql .= " GROUP BY o.order_id ";
		$sql .= " ORDER BY o.date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) 
		{
			if ($data['start'] < 0) 
			{
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) 
			{
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                	//echo $sql; 
		$query = $this->db->query($sql);
		$log->write($sql);
		return $query->rows;
	}
	/////////////////


function insert_into_bank_transaction_letter($data) 
{
	$cr_date=date('Y-m-d');
	$log=new Log("tagbill-letter-".date('Y-m-d').".log");

	$sql2 = "insert oc_bank_transaction set `user_id`='".$this->db->escape($data['user_id'])."',`store_id`='".$this->db->escape($data['store_id'])."',`bank_id`='".$this->db->escape('4')."',`bank_name`='".$this->db->escape('TAGGED BILLS')."',`amount`='".$this->db->escape($data['amount'])."',`status`='".$this->db->escape('0')."',`accept_by`='".$this->db->escape($data['runner_id'])."',tagged_letter_number='".$data['letter_number']."' ";
	$query2 = $this->db->query($sql2);
	$log->write($sql2);

	$sql = "update oc_tagged_bill_trans set status=1 where  sid='".$data['letter_number']."' ";
	$query = $this->db->query($sql);
	$log->write($sql);
}
	////////////////////////////letter wise///////////////////// 
}