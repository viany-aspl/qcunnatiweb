<?php
class ModelFactoryAdjustment extends Model {
        
	public function getList($data)
	{
                $sql="SELECT ose.value as company_id,tb.store_id,oc.company_name,os.name as store_name,tb.status,ou.unit_name,tb.unit_id,tb.create_date,tb.total_amount,tb.date_start,tb.sid,tb.submission_date FROM oc_tagged_bill_trans as tb
                      left join oc_store as os on os.store_id=tb.store_id
                      left join oc_setting as ose on ose.store_id=tb.store_id
                      left join oc_company as oc on oc.company_id=ose.value
                      left join oc_unit as ou on ou.unit_id=tb.unit_id where tb.sid!=''  and ose.key='config_company' ";
                //and ose.key='config_company' 
                        if (!empty($data['filter_store']) ) 
                        {
                            $sql .=" and tb.store_id='".$data['filter_store']."'";
			
                        }
						if (!empty($data['filter_company']) ) 
                        {
                            $sql .=" and ose.value='".$data['filter_company']."'";
			
                        }
						if (!empty($data['filter_unit']) ) 
                        {
                            $sql .=" and tb.unit_id='".$data['filter_unit']."'";
			
                        }
	         
							if (!empty($data['filter_letterno']) ) 
                        {
                            $sql .=" and tb.sid='".$data['filter_letterno']."'";
			
                        }
	
                        $sql .= "GROUP BY tb.sid ORDER BY tb.sid  DESC";
                
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
		$sql="select count(*) as total_orders from (SELECT ose.value as company_id,tb.store_id,oc.company_name,os.name as store_name,tb.status,ou.unit_name,tb.unit_id,tb.create_date,tb.total_amount,tb.date_start,tb.sid FROM oc_tagged_bill_trans as tb
                      left join oc_store as os on os.store_id=tb.store_id
                      left join oc_setting as ose on ose.store_id=tb.store_id
                      left join oc_company as oc on oc.company_id=ose.value
                      left join oc_unit as ou on ou.unit_id=tb.unit_id where tb.sid!=''  and ose.key='config_company' ";
                
                          if (!empty($data['filter_store']) ) 
                        {
                            $sql .=" and tb.store_id='".$data['filter_store']."'";
			
                        }
						if (!empty($data['filter_company']) ) 
                        {
                            $sql .=" and ose.value='".$data['filter_company']."'";
			
                        }
						if (!empty($data['filter_unit']) ) 
                        {
                            $sql .=" and tb.unit_id='".$data['filter_unit']."'";
			
                        }
						if (!empty($data['filter_letterno']) ) 
                        {
                            $sql .=" and tb.sid='".$data['filter_letterno']."'";
			
                        }
		///////////////////////////////////
		
                        $sql .= "GROUP BY tb.sid";
                        
                        
                        $sql.=" ) as aa";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row['total_orders'];
		//return $results['total_orders'];
	}
        public function updatestatus($sid,$company,$unit,$total_amount,$store_id,$updated_by)
        {
           $sql2="update  oc_tagged_bill_trans set status='1' where sid='".$sid."' ";
            $query = $this->db->query($sql2);
            
            $sql3="update  oc_unit set wallet_balance=wallet_balance -".$total_amount." where company_id='".$company."' and unit_id='".$unit."'";
            $query = $this->db->query($sql3);
            
            $query = $this->db->query("SELECT wallet_balance FROM oc_unit where company_id='".$company."' and unit_id='".$unit."'");
            $available_balance= $query->row['wallet_balance'];        
            
            $sql="insert into  oc_unit_cash_trans set store_id='".$store_id."',user_id='".$updated_by."',amount='".$total_amount."',transaction_type='Adjustment',payment_method='Adjustment',unit_id='".$unit."',tr_number='".$sid."',company_id='".$company."',available_balance='".$available_balance."',total_amount='".$total_amount."',cr_db='DB' ";
            $query = $this->db->query($sql);
            
        }
	public function save_submission_date($sid,$company,$unit,$total_amount,$store_id,$updated_by)
        {
	$submission_date=date('Y-m-d');
           $sql2="update  oc_tagged_bill_trans set submission_date='".$submission_date."' where sid='".$sid."' ";
            $query = $this->db->query($sql2);
            
            
            
        }
		public function getunitbalance($unit,$company)
		{
		$sql="SELECT wallet_balance FROM oc_unit where company_id='".$company."' and unit_id='".$unit."'";
		  $query = $this->db->query($sql);
          return $available_balance= $query->row['wallet_balance'];  
		}
        
public function downloadgetList($data)
 {
                $sql="SELECT ose.value as company_id,tb.store_id,oc.company_name,os.name as store_name,tb.status,ou.unit_name,tb.unit_id,tb.create_date,tb.total_amount,tb.date_start,tb.sid FROM oc_tagged_bill_trans as tb
                      left join oc_store as os on os.store_id=tb.store_id
                      left join oc_setting as ose on ose.store_id=tb.store_id
                      left join oc_company as oc on oc.company_id=ose.value
                      left join oc_unit as ou on ou.unit_id=tb.unit_id where tb.sid!='' ";
                //and ose.key='config_company'
                        if (!empty($data['filter_store']) )
                        {
                            $sql .=" and tb.store_id='".$data['filter_store']."'";
   
                        }
      if (!empty($data['filter_company']) )
                        {
                            $sql .=" and ose.value='".$data['filter_company']."'";
   
                        }
      if (!empty($data['filter_unit']) )
                        {
                            $sql .=" and tb.unit_id='".$data['filter_unit']."'";
   
                        }
      if (!empty($data['filter_letterno']) )
                        {
                            $sql .=" and tb.sid='".$data['filter_letterno']."'";
   
                        }
                        $sql .= "GROUP BY tb.sid ORDER BY tb.sid  DESC";
               
                      
  
              //  echo $sql;
                $query = $this->db->query($sql);
  
  return $query->rows;
 }
       
public function getstorebyunit($uid){
$sql="SELECT oc_store_to_unit.store_id,oc_store_to_unit.unit_id,oc_store.name FROM oc_store_to_unit

left join oc_store on oc_store.store_id=oc_store_to_unit.store_id 
WHERE oc_store_to_unit.unit_id='".$uid."' ";
$query = $this->db->query($sql);

//echo $sql;
return $query->rows; 
}

 
}
?>