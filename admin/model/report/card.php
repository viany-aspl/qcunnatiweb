<?php
class ModelReportCard extends Model 
{
	public function  getUnits()
       {      

           $sql="SELECT * FROM `oc_unit` where company_id=1 ";
		
           $query = $this->db->query($sql);
		  //echo $sql;
		   return $query->rows;
		   
       }  

	public function  getcardtrans($data)
       {   
$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));	   
           $sql="SELECT oc_order.order_id,oc_store.name as storename,oc_order.tagged,oc_order.cash,oc_order.total,oc_order.subsidy,
		   oc_order.grower_id,oc_order.card_serial_no,oc_order.date_added,oc_unit.unit_name,oc_order.payment_method ,
		   DATE(oc_order.date_added)  datea 
		   FROM oc_order left join oc_store on oc_store.store_id=oc_order.store_id 
			left join oc_unit on oc_order.unit_id=oc_unit.unit_id
		   where oc_order.order_status_id='5' and oc_order.card_serial_no!='0' ";
		if (!empty($data['filter_date_start'])) 
		{
			$sql .= " and (oc_order.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) 
		{
			$sql .= " AND (oc_order.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
        if (!empty($data['filter_unit_id'])) 
		{
			$sql .= " AND oc_order.unit_id = '" . $this->db->escape($data['filter_unit_id']) . "'";
		}
		if (!empty($data['filter_grower_id'])) 
		{
			$sql .= " AND oc_order.grower_id like '%" . $this->db->escape($data['filter_grower_id']) . "%'";
		}
		if (!empty($data['filter_card_number'])) 
		{
			$sql .= " AND oc_order.card_serial_no like '%" . $this->db->escape($data['filter_card_number']) . "%'";
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
		  //echo $sql;
		   return $query->rows;
		   
       }
	   public function  getcardhistortyTotal($data)
       {      
			$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));	  
           $sql=" select count(*) as total from ( SELECT oc_order.order_id,oc_store.name as storename,oc_order.tagged,oc_order.cash,DATE(oc_order.date_added)  datea 
		   FROM oc_order left join oc_store on oc_store.store_id=oc_order.store_id 
			left join oc_unit on oc_order.unit_id=oc_unit.unit_id
		   where oc_order.order_status_id='5' and  oc_order.card_serial_no!='0'  ";
		if (!empty($data['filter_date_start'])) 
		{
			$sql .= " and (oc_order.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME) ";
		}

		if (!empty($data['filter_date_end'])) 
		{
			$sql .= " AND (oc_order.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
        if (!empty($data['filter_unit_id'])) 
		{
			$sql .= " AND oc_order.unit_id = '" . $this->db->escape($data['filter_unit_id']) . "'";
		}
		if (!empty($data['filter_grower_id'])) 
		{
			$sql .= " AND oc_order.grower_id like '%" . $this->db->escape($data['filter_grower_id']) . "%'";
		}
		if (!empty($data['filter_card_number'])) 
		{
			$sql .= " AND oc_order.card_serial_no like '%" . $this->db->escape($data['filter_card_number']) . "%'";
		}
		$sql.=" ) as aa  ";
		  //echo $sql; 
           $query = $this->db->query($sql);
		  
		   return $query->row['total'];
		   
       }
		public function getorder_summarydetail($data)
		{
		$sql="SELECT name,quantity,price,tax,total FROM oc_order_product where order_id='".$data."' group by product_id";
		$query = $this->db->query($sql);  
        return $query->rows;   
		
		}
	   ////////////////////////////////////////////
	
		
        
       public function  getdetail($data)
       {      $log=new Log("Card-".date('Y-m-d').".log");   
           $sql="SELECT *,ou.unit_name from  oc_card_issue  left join oc_unit ou on ou.unit_id= oc_card_issue.unit_id  where GROWER_ID!='' ";
           if($data['grower_id']!="")
		{
			$sql.="and   GROWER_ID = '" .$data['grower_id']."' ";
		}
                if($data['mobile']!="")
		{
			$sql.=" and MOB = '" .$data['mobile']."' ";
		}
           if($data['village_id']!="")
{
$sql.=" and VILLAGE_ID = '" .$data['village_id']."' ";
}

           $query = $this->db->query($sql);

		return $query->rows;
       }
      
      
	   
	   
        
}