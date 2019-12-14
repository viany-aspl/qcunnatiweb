<?php

class ModelReportSubuser extends Model {
	
public function getMaterialSummary($data = array()) {
	   
            $log=new Log("getBilledMaterial-".date('Y-m-d').".log");
			
			$sql="
			select * from (
			select product_id,a.name,sum(material_issue) as ms ,sum(biilled) as billed,(sum(material_issue) -sum(biilled)) as bal,concat(oc_user.firstname,' ',oc_user.lastname) as username,oc_store.name as storename from 
			(
    
    SELECT product_id,oc_contractor_product.name,sum(quantity) as material_issue ,'0' as biilled,contractor_id as contractor_id FROM oc_contractor_product where contractor_id!='' 
	";
	if ($data['filter_user']!='') 
	{
		$sql .= " AND contractor_id = '" . $this->db->escape($data['filter_user']) . "'";
	}
	if ($data['filter_store']!='') 
	{
		$sql .= " AND store_id = '" . $this->db->escape($data['filter_store']) . "'";
	}
	if ($data['filter_product']!='') 
	{
		$sql .= " AND product_id = '" . $this->db->escape($data['filter_product']) . "'";
	}
	
	if ($data['start_date']!='') 
	{
		$sql .= " AND date(updatedate) >= '" . $this->db->escape($data['start_date']) . "'";
	}
	if ($data['end_date']!='') 
	{
		$sql .= " AND date(updatedate) <= '" . $this->db->escape($data['end_date']) . "'";
	}
	
	
	$sql.="
	group by product_id,contractor_id
    UNION ALL
    
    select oc_order_product.product_id as product_id,oc_order_product.name as name,'0' as material_issue, sum(oc_order_product.quantity) as biilled,oc_order.user_id as contractor_id from oc_order_product 
	left join oc_order on oc_order_product.order_id=oc_order.order_id left join oc_user on oc_order.user_id=oc_user.user_id 
	where oc_user.user_group_id=36 and oc_order.order_status_id=5
	";
	if ($data['filter_user']!='') 
	{
		$sql .= " AND oc_order.user_id = '" . $this->db->escape($data['filter_user']) . "'";
	}
	if ($data['filter_store']!='') 
	{
		$sql .= " AND oc_order.store_id = '" . $this->db->escape($data['filter_store']) . "'";
	}
	if ($data['filter_product']!='') 
	{
		$sql .= " AND oc_order_product.product_id = '" . $this->db->escape($data['filter_product']) . "'";
	}
	if ($data['start_date']!='') 
	{
		$sql .= " AND date(oc_order.date_added) >= '" . $this->db->escape($data['start_date']) . "'";
	}
	if ($data['end_date']!='') 
	{
		$sql .= " AND date(oc_order.date_added) <= '" . $this->db->escape($data['end_date']) . "'";
	}
	$sql.="
	group by product_id,oc_order.user_id
    
    ) a 
					left join oc_user on oc_user.user_id=a.contractor_id 
					
					left join oc_store on oc_user.store_id=oc_store.store_id group by product_id,contractor_id order by name) as b where b.ms>0
					";
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
            //$log->write($sql);
            //$log->write($query->rows); 
			//echo $sql; //exit;
            return $query->rows;    

	}
	public function getAllMaterialSummary($data = array()) 
	{
	 $log=new Log("getAllMaterialSummary-".date('Y-m-d').".log");
			//$log->write($contrator_id); 
            $sql="SELECT ocp.contractor_id,os.name as storename, concat(ou.firstname,' ',ou.lastname) as username ,
			ocp.product_id,ocp.name,sum(ocp.quantity) as material_issue, 
			ifnull(b.billed,0) as biilled, (sum(ocp.quantity) - sum(ifnull(b.billed,0))) AS bal 
			FROM oc_contractor_product ocp left join 
			(SELECT oo.store_name ,oo.user_id, oop.product_id,oop.name,'0' as material_issue, sum(oop.quantity) as billed from oc_order_product oop 
			left join oc_order oo on oo.order_id=oop.order_id where oo.order_status_id=5 group by oop.product_id, oo.user_id) as b 
			on ocp.contractor_id=b.user_id and ocp.product_id=b.product_id 
			left join oc_user ou on ou.user_id=ocp.contractor_id
			left join oc_store os on os.store_id=ocp.store_id
			group by ocp.product_id, ocp.contractor_id order by ocp.contractor_id";
            $query = $this->db->query($sql);  
            //$log->write($sql);
            //$log->write($query->rows); 
			//echo $sql; exit;
            return $query->rows;    
	
	}

	public function getTotalMaterialSummary($data = array()) {
		/*
            $sql="select count(*) as total from (select  * from (
					select product_id,a.name,sum(material_issue) as ms ,sum(biilled) as billed,(sum(material_issue) -sum(biilled)) as bal,concat(oc_user.firstname,' ',oc_user.lastname) as username,oc_store.name as storename from (
					SELECT product_id,oc_contractor_product.name,sum(quantity) as material_issue ,'0' as biilled FROM oc_contractor_product where contractor_id='".$data['filter_user']."'  group by product_id 
					union
					SELECT product_id,oc_order_product.name,'0' as material_issue, sum(quantity) as biilled from oc_order_product where order_id in(SELECT order_id FROM oc_order where user_id='".$data['filter_user']."' )  group by product_id 
					) a left join oc_user on oc_user.user_id='".$data['filter_user']."' left join oc_store on oc_user.store_id=oc_store.store_id group by product_id order by name
					) b where ms >0 ";
           
            $sql.=" ) as aa"; 
*/
$sql="
			select count(*) as total from ( select product_id,a.name,sum(material_issue) as ms ,
			sum(biilled) as billed,(sum(material_issue) -sum(biilled)) as bal,
			concat(oc_user.firstname,' ',oc_user.lastname) as username,oc_store.name as storename from 
			(
    
    SELECT product_id,oc_contractor_product.name,sum(quantity) as material_issue ,
	'0' as biilled,contractor_id as contractor_id FROM oc_contractor_product where contractor_id!='' 
	";
	if ($data['filter_user']!='') 
	{
		$sql .= " AND contractor_id = '" . $this->db->escape($data['filter_user']) . "'";
	}
	if ($data['filter_store']!='') 
	{
		$sql .= " AND store_id = '" . $this->db->escape($data['filter_store']) . "'";
	}
	if ($data['filter_product']!='') 
	{
		$sql .= " AND product_id = '" . $this->db->escape($data['filter_product']) . "'";
	}
	if ($data['start_date']!='') 
	{
		$sql .= " AND date(updatedate) >= '" . $this->db->escape($data['start_date']) . "'";
	}
	if ($data['end_date']!='') 
	{
		$sql .= " AND date(updatedate) <= '" . $this->db->escape($data['end_date']) . "'";
	}
	$sql.="
	group by product_id,contractor_id
    UNION ALL
    
    select oc_order_product.product_id as product_id,oc_order_product.name as name,'0' as material_issue, sum(oc_order_product.quantity) as biilled,oc_order.user_id as contractor_id from oc_order_product 
	left join oc_order on oc_order_product.order_id=oc_order.order_id
	left join oc_user on oc_order.user_id=oc_user.user_id 
	where oc_user.user_group_id=36 and oc_order.order_status_id=5
	";
	if ($data['filter_user']!='') 
	{
		$sql .= " AND oc_order.user_id = '" . $this->db->escape($data['filter_user']) . "'";
	}
	if ($data['filter_store']!='') 
	{
		$sql .= " AND oc_order.store_id = '" . $this->db->escape($data['filter_store']) . "'";
	}
	if ($data['filter_product']!='') 
	{
		$sql .= " AND oc_order_product.product_id = '" . $this->db->escape($data['filter_product']) . "'";
	}
	if ($data['start_date']!='') 
	{
		$sql .= " AND date(oc_order.date_added) >= '" . $this->db->escape($data['start_date']) . "'";
	}
	if ($data['end_date']!='') 
	{
		$sql .= " AND date(oc_order.date_added) <= '" . $this->db->escape($data['end_date']) . "'";
	}
	$sql.="
	group by product_id,oc_order.user_id
    
    ) a 
					left join oc_user on oc_user.user_id=a.contractor_id 
					
					left join oc_store on oc_user.store_id=oc_store.store_id group by product_id,contractor_id order by name) as b where b.ms>0
					";

		$query = $this->db->query($sql);
//echo $sql;
		return $query->row['total'];
	}
	public function getmaterial_detail($data)
		{
      
		$log=new Log("getmaterial_detail-".date('Y-m-d').".log");
		//$log->write($data);
		$sql="SELECT concat(oc_user.firstname,' ',oc_user.lastname) as username,
		ocp.product_id,ocp.name,ocp.quantity as ms,oop.quantity as billed,
		ocp.quantity,DATE(ocp.updatedate) dat,oc_store.name as storename,
		ocp.id as trans_id
		FROM oc_contractor_product as ocp
		left join oc_order on oc_order.order_id=ocp.contractor_id
		left join oc_user on oc_user.user_id=ocp.contractor_id
		left join oc_store on ocp.store_id=oc_store.store_id
		left join oc_order_product as oop on oop.order_id=oc_order.order_id where ocp.contractor_id!='' ";

		if ($data['filter_user']!='') 
		{
			$sql .= " AND ocp.contractor_id = '" . $this->db->escape($data['filter_user']) . "'";
		}
		if ($data['filter_store']!='') 
		{
			$sql .= " AND ocp.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
		if ($data['start_date']!='') 
		{
			$sql .= " AND date(ocp.updatedate) >= '" . $this->db->escape($data['start_date']) . "'";
		}
		if ($data['end_date']!='') 
		{
			$sql .= " AND date(ocp.updatedate) <= '" . $this->db->escape($data['end_date']) . "'";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " order by ocp.id desc LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql;
            $query = $this->db->query($sql);  
            //$log->write($sql);
            //$log->write($query->rows); 
			//echo $sql; //exit;
            return $query->rows;   
		
		}
		
	public function getmaterial_Totaldetail($data = array()) {
		
            $sql="select count(*) as total from (SELECT concat(oc_user.firstname,' ',oc_user.lastname) as username,ocp.product_id,ocp.name,ocp.quantity as ms,oop.quantity as billed,ocp.quantity,DATE(ocp.updatedate) dat FROM oc_contractor_product as ocp
left join oc_order on oc_order.order_id=ocp.contractor_id
left join oc_user on oc_user.user_id=ocp.contractor_id
left join oc_order_product as oop on oop.order_id=oc_order.order_id where ocp.contractor_id!=''";
                  
        if ($data['filter_user']!='') 
		{
			$sql .= " AND ocp.contractor_id = '" . $this->db->escape($data['filter_user']) . "'";
		}
		if ($data['filter_store']!='') 
		{
			$sql .= " AND ocp.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
		if ($data['start_date']!='') 
		{
			$sql .= " AND date(ocp.updatedate) >= '" . $this->db->escape($data['start_date']) . "'";
		}
		if ($data['end_date']!='') 
		{
			$sql .= " AND date(ocp.updatedate) <= '" . $this->db->escape($data['end_date']) . "'";
		}
            $sql.=" ) as aa"; 

		$query = $this->db->query($sql);
//echo $sql;
		return $query->row['total'];
	}
	
		public function getSubUser() {
			$sql="SELECT * from oc_user  where user_group_id='36' ";
           
	              //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
	public function getUserSaleCash($data)
	{
      
		$log=new Log("getUserSaleCash-".date('Y-m-d').".log");
		$log->write($data['filter_user']);
		
		$sql="SELECT concat(oc_user.firstname,' ',oc_user.lastname) as subusername,
		oc_user.user_id,oc_order.store_name,
		sum(oc_order.cash) as Cash_Sales,
		sum(oc_order.tagged) as Tagged_Sales,
		sum(oc_order.subsidy) asCash_Subsidy,
		oc_user.cash as cash_inhand FROM oc_order 
		left join oc_user on oc_user.user_id=oc_order.user_id
		where oc_order.user_id!='' and oc_user.user_group_id='36' and oc_order.order_status_id=5 ";
		if ($data['start_date']!='') 
		{
			$sql .= " AND date(oc_order.date_added) >= '" . $this->db->escape($data['start_date']) . "'";
		}	
		if ($data['end_date']!='') 
		{
			$sql .= " AND date(oc_order.date_added) <= '" . $this->db->escape($data['end_date']) . "'";
		}
		if ($data['filter_user']!='') 
		{
			$sql .= " AND oc_order.user_id ='".$this->db->escape($data['filter_user'])."'";
		}
		$sql.="GROUP BY oc_order.user_id ";
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
            $log->write($sql);
            $log->write($query->rows); 
			//echo $sql; //exit;
            return $query->rows;   
		
	}
	//,oc_user.cash as cash_inhand
	public function getUserSaleTotalCash($data = array()) 
	{
		$sql="select count(*) as total from (SELECT concat(oc_user.firstname,' ',oc_user.lastname) as subusername,
			oc_user.user_id,oc_order.store_name,sum(oc_order.cash) as Cash_Sales,
			sum(oc_order.tagged) as Tagged_Sales,
			sum(oc_order.subsidy) asCash_Subsidy,
			oc_user.cash as cash_inhand FROM oc_order 
			left join oc_user on oc_user.user_id=oc_order.user_id
			where oc_order.user_id!='' and oc_user.user_group_id='36' and oc_order.order_status_id=5 ";
            if ($data['start_date']!='') 
			{
				$sql .= " AND date(oc_order.date_added) >= '" . $this->db->escape($data['start_date']) . "'";
			}	
			if ($data['end_date']!='') 
			{
				$sql .= " AND date(oc_order.date_added) <= '" . $this->db->escape($data['end_date']) . "'";
			}      
			if ($data['filter_user']!='') 
			{
				$sql .= " AND oc_order.user_id= '" . $this->db->escape($data['filter_user']) . "'";
			}
			$sql.="GROUP BY oc_order.user_id ) as aa";
           
			//echo $sql;

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	/*
	public function getSubUser() {
			$sql="SELECT * from oc_user  where user_group_id='36' ";
           
	              //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}*/

public function getUserCash($user_id)
		{  
		//$contracter_id='242';
            $log=new Log("getUserCash-".date('Y-m-d').".log");
			 $log->write($contrator_id); 
            $sql="SELECT cash FROM oc_user where user_id='".$user_id."' ";
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->rows); 
			//echo $sql; exit;
            return $query->row['cash'];    
        } 
		public function getUserSaleCashdtl($data = array())
		{
			$log=new Log("getUserSaleCash-".date('Y-m-d').".log");
		$log->write($data['filter_subuser']);
		
		 $sql="SELECT amount as cash,DATE(date_added) as dat FROM oc_bank_transaction where  user_id!='' ";

		if ($data['filter_subuser']!='') {
			$sql .= " and  user_id ='".$this->db->escape($data['filter_subuser'])."'";
		}
		if ($data['start_date']!='') 
		{
			$sql .= " AND date(date_added) >= '" . $this->db->escape($data['start_date']) . "'";
		}
		if ($data['end_date']!='') 
		{
			$sql .= " AND date(date_added) <= '" . $this->db->escape($data['end_date']) . "'";
		}
	 $sql.=" order by transid desc";
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
            $log->write($sql);
            $log->write($query->rows); 
			//echo $sql; //exit;
            return $query->rows;   
		}
			public function getUserSaleTotalCashdtl($data = array()) {
		
            $sql="select count(*) as total from (SELECT amount as cash,DATE(date_added) as dat FROM oc_bank_transaction where user_id!='' ";
                  
			if ($data['filter_subuser']!='') 
			{
				$sql .= " and  user_id ='".$this->db->escape($data['filter_subuser'])."'";
			}
			if ($data['start_date']!='') 
			{
				$sql .= " AND date(date_added) >= '" . $this->db->escape($data['start_date']) . "'";
			}	
			if ($data['end_date']!='') 
			{
				$sql .= " AND date(date_added) <= '" . $this->db->escape($data['end_date']) . "'";
			}
			$sql.=") as aa";
           
			//echo $sql;

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
		public function getorder_summary($data = array())
		{     
			$log=new Log("getorder_summary-".date('Y-m-d').".log");
			//$log->write($data);
			$sql1="SELECT concat(oc_user.firstname,' ',oc_user.lastname) as username,order_id,
			payment_firstname,oc_order.store_name,
			oc_order.payment_method,oc_order.total,
			oc_order.cash,oc_order.tagged,oc_order.subsidy,
			DATE(oc_order.date_added) as dat,
				oc_order.telephone,
				oc_order.grower_id,
				oc_order.card_serial_no
			
			FROM oc_order 
			left join oc_user on oc_user.user_id=oc_order.user_id
			left join oc_store_to_unit on oc_store_to_unit.store_id=oc_order.store_id
			where oc_user.user_group_id='36' and oc_order.order_status_id=5 ";
			
			if (!empty($data['filter_user'])) {
				
				$sql1.= " and oc_order.user_id='".$data['filter_user']."'";				

				}
			if (!empty($data['filter_unit'])) {
				
					$sql1.= " and oc_store_to_unit.unit_id='".$data['filter_unit']."'";				

					}


			if (!empty($data['filter_date_start'])) {
				$sql1.= "AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql1.= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			
			$sql1 .= " group by oc_order.order_id";
				if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql1 .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql1;
	
			//$log->write($sql1);
			$querys = $this->db->query($sql1);		
			return $querys->rows;   		
		}
		public function getorder_summarydetail($data)
		{

		$log=new Log("order_detail-".date('Y-m-d').".log");
		$log->write($data);
		$sql="SELECT name,quantity,price,tax,total FROM oc_order_product where order_id='".$data."' group by product_id";
		
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->rows); 
			//echo $sql; //exit; 
            return $query->rows;   
		
		}
		public function getTotalorder_summary($data = array()) {
		
            $sql="select count(*) as total from (SELECT order_id
			FROM oc_order left join oc_user on oc_user.user_id=oc_order.user_id
			where oc_user.user_group_id='36' and oc_order.order_status_id=5 ";
		

if (!empty($data['filter_user'])) {
				
				$sql.= " and oc_order.user_id='".$data['filter_user']."'";				

				}
			



if (!empty($data['filter_date_start'])) {
				$sql.= "AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql.= "AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
		$sql .= " group by order_id";
			$sql.=") as aa";
           
			//echo $sql;

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
		
     public function getorder_summary_mill($data = array())
		{     
			$sql1="SELECT concat(oc_user.firstname,' ',oc_user.lastname) as username,order_id,
					payment_firstname,oc_order.store_name,
					oc_order.payment_method,oc_order.total,
					oc_order.cash,oc_order.tagged,oc_order.subsidy,
					DATE(oc_order.date_added) as dat,
					oc_order.telephone,
					oc_order.grower_id,
					oc_order.card_serial_no,
					oc_order.payment_address_1
			
					FROM oc_order 
					left join oc_user on oc_user.user_id=oc_order.user_id
					left join oc_store_to_unit on oc_store_to_unit.store_id=oc_order.store_id
					where oc_user.user_group_id='36'
					and `payment_method` in ('Tagged','Tagged Cash','Cash')  
					and oc_order.order_status_id=5 ";
					if (!empty($data['filter_unit'])) {
				
					$sql1.= " and oc_store_to_unit.unit_id='".$data['filter_unit']."'";				

					}
			
					if (!empty($data['filter_user'])) {
				
					$sql1.= " and oc_order.user_id='".$data['filter_user']."'";				

					}
			


			if (!empty($data['filter_date_start'])) {
				$sql1.= "AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql1.= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			
			$sql1 .= " group by oc_order.order_id";
				if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql1 .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql1;exit;
			$querys = $this->db->query($sql1);		
			return $querys->rows;   		
		}   
 
}