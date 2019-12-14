<?php
class ModelReportInventory extends Model {
	/*
	public function getInventory_report($data = array()) { //print_r($data);
	   $sql="
               select s.product_id,opd.name as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,round((b.price+ifnull((b.tax),0)),2)as price,round(sum(b.price+ifnull((b.tax),0)),2)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id 
left join oc_product_description as opd on p.product_id=opd.product_id

left join
  (select product_id,store_id,price,tax from
 ( SELECT p.product_id,p2s.product_id as pid,p2s.store_id,p.model,
 sum(p2s.quantity) as qnty, sum(CASE WHEN p2s.store_price='0.0000'
 THEN p.price ELSE p2s.store_price END) as price, (sum(p2s.quantity) * (price)) as total,
 ( ( SELECT (CASE WHEN type='F' then rate WHEN type='p' then (price *(rate/100)) else rate end) as rate
 FROM `oc_tax_rule` as rl LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id
 WHERE `tax_class_id`=p.tax_class_id ) )as tax FROM oc_product p
 LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
 WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.quantity > 0
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY p.product_id,p2s.store_id ORDER BY p.sort_order ASC )
 as a)as b on b.product_id = s.product_id and s.store_id = b.store_id

 where s.quantity>0
";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}
 

		
$sql .= " group by s.product_id";

            
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
	*/
	public function getInventory_report($data = array()) { //print_r($data);
	   $sql="
               select s.product_id,opd.name as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,ROUND((s.store_price + IFNULL((s.store_tax_amt), 0)), 2) AS price,
    ROUND(SUM(s.store_price + IFNULL((s.store_tax_amt), 0)), 2) AS Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id 
left join oc_product_description as opd on p.product_id=opd.product_id

 where s.quantity>0
";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}
 

		
$sql .= " group by s.product_id";

            
            if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
              //  echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

	public function getTotalInventory($data = array()) {
		
            $sql="select count(*) as total,sum(Qnty*price) as total_sum  from ( select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,ROUND((s.store_price + IFNULL((s.store_tax_amt), 0)), 2) AS price,
    ROUND(SUM(s.store_price + IFNULL((s.store_tax_amt), 0)), 2) AS Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id 


 where s.quantity>0
";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		} 
 

		
$sql .= " group by s.product_id ) as aa";

                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
public function getInventory_report_excel($data = array()) { //print_r($data); 
       $sql="
               select s.product_id,opd.name as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,ROUND((s.store_price + IFNULL((s.store_tax_amt), 0)), 2) AS price,
    ROUND(SUM(s.store_price + IFNULL((s.store_tax_amt), 0)), 2) AS Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id
left join oc_product_description as opd on p.product_id=opd.product_id

where s.quantity>0
";
            
            
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
            
        }
 

        
$sql .= " group by s.product_id";

            
            
                //echo $sql;
        $query = $this->db->query($sql);
                
        return $query->rows;
    }
  

   public function getInventory_reportProductWise($data = array()) { //print_r($data);
       $sql="
               select p2s.store_id,os.name as store_name, p2s.product_id,opd.name as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,
			   ROUND((p2s.store_price + IFNULL((p2s.store_tax_amt), 0)), 2) AS price,
    ROUND(SUM(p2s.store_price + IFNULL((p2s.store_tax_amt), 0)), 2) AS Amount
               from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
               left join oc_product as p on p2s.product_id = p.product_id
			   LEFT JOIN
    oc_setting ost ON p2s.store_id = ost.store_id
	left join oc_product_description as opd on p.product_id=opd.product_id

                
";
            
         
if (!empty($data['filter_name_id']) ) {
    $sql .=" where p2s.product_id=".$data['filter_name_id'];
            
        }
		else
		{
			$sql .=" where p2s.product_id!='' ";
		}
 

        
$sql.=" and p2s.quantity>0
and ost.value=1
     and ost.`key`='config_storestatus'
	 and os.store_id not in (52,55)
";  
 
$sql .= " GROUP by p2s.store_id

";
if (empty($data['filter_name_id']) ) {
    $sql .=" ,p2s.product_id ";
            
        }
            $sql.=" order by Qnty desc";
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
public function getTotalInventoryProductWise($data = array()) {
        
            $sql="select count(*)as total,sum(Qnty) as total_Qnty from (
                select p2s.store_id,os.name as store_name, p2s.product_id,p.model as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,
				p2s.store_price as price,sum(p2s.store_price)as Amount
               from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
               left join oc_product as p on p2s.product_id = p.product_id
			LEFT JOIN
    oc_setting ost ON p2s.store_id = ost.store_id
";
            
          
if (!empty($data['filter_name_id']) ) {
    $sql .=" where p2s.product_id=".$data['filter_name_id'];
            
        }
		else
		{
			$sql .=" where p2s.product_id!='' ";
		}
        
$sql.=" and p2s.quantity>0
and ost.value=1
     and ost.`key`='config_storestatus'
	 and os.store_id not in (52,55)
";  
 
$sql .= " GROUP by p2s.store_id ";
            if (empty($data['filter_name_id']) ) {
    $sql .=" ,p2s.product_id ";
            
        }
                $sql.=") as a";
                //echo $sql;
        $query = $this->db->query($sql);

        return $query->row;
    }

	public function getInventory_linked_product($data = array()) { //print_r($data);
	   $sql="
               select s.product_id,opd.name as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
s.quantity as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_product_description as opd on p.product_id=opd.product_id
left join oc_store as st on st.store_id = s.store_id 
 where s.quantity>=0 and s.store_id!=0
";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}
 

		
$sql .= " group by s.product_id,s.store_id";

            
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

	public function getTotalInventory_linked_product($data = array()) {
		
            $sql="select count(*)as total from (
                select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
s.quantity as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id  where s.quantity>=0 and s.store_id!=0
";
            
          
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}

		
$sql .= " group by s.product_id,s.store_id) as a";
            
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getInventory_report_daily_email($data = array()) { //print_r($data);
$sql="SELECT ots.store_id as store_id,ots.product_id,ots.store_price,ots.store_tax_amt,ots.quantity as Qnty,
os.name as store_name,opd.name as Product_name 

FROM `oc_product_to_store` as ots 
left join oc_store as os on ots.store_id=os.store_id 

left join oc_product_description as opd on ots.product_id=opd.product_id
 where ots.quantity>0 and ots.store_id not in (14,52) "; 	 

            
            
                //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}

          public function setInventory_report_daily_email($filename,$report_date)
          {
             $sql1=" delete from  `oc_store_inventory_daily_report` where `report_date`='".$report_date."' ";
             $query = $this->db->query($sql1);

             $sql=" insert into `oc_store_inventory_daily_report` set `filename`='".$filename."',`report_date`='".$report_date."' ";
             $query = $this->db->query($sql);
          }
          public function get_old_report_daily_email($data=array())
          {
             $sql1=" select * from  `oc_store_inventory_daily_report`  ";
             if($data["filter_date"]!="")
             {
             $sql1.=" where `report_date`='".$data["filter_date"]."' ";

              
             }
			 $sql1.=" order by report_date desc ";
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
             $query = $this->db->query($sql1);
             return $query->rows;
             
          }
          public function get_total_old_report_daily_email($data=array())
          {
             $sql1=" select count(*) as total from ( select * from  `oc_store_inventory_daily_report`  ";
             if($data["filter_date"]!="")
             {
             $sql1.=" where `report_date`='".$data["filter_date"]."' ";

              
             }
             $sql1.=" ) as aa";

             $query = $this->db->query($sql1);
             return $query->row["total"];
             
          }
          public function add_to_db_month_starting($store_name,$store_id,$product_id,$Product_name,$Qnty,$currentdate)
          {
            
            $sql=" insert into `oc_store_inventory_month` set  `Store_Name`='".$store_name."',`Store_ID`='".$store_id."',`Product_ID`='".$product_id."',`Product_Name`='".$Product_name."',`Current_Qnty`='".$Qnty."',`CurrentDate`='".$currentdate."' ";

             $query = $this->db->query($sql);
          }

	 public function getInventory_reportProductCompanyWise($data = array()) { //print_r($data);
       $sql="
               select p2s.store_id,os.company_id,os.name as store_name, p2s.product_id,opd.name as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,
			   ROUND((p2s.store_price + IFNULL((p2s.store_tax_amt), 0)), 2) AS price,
    ROUND(SUM(p2s.store_price + IFNULL((p2s.store_tax_amt), 0)), 2) AS Amount
               from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
               left join oc_product as p on p2s.product_id = p.product_id
			   LEFT JOIN
    oc_setting ost ON p2s.store_id = ost.store_id
	left join oc_product_description as opd on p.product_id=opd.product_id
                
";
            
     $sql .= " where os.company_id='".$data['filter_company']."' ";      
if (!empty($data['filter_name_id']) ) {
    $sql .=" and p2s.product_id=".$data['filter_name_id'];
            
        }
 

    $sql.=" and p2s.quantity>0
	and ost.value=1
     and ost.`key`='config_storestatus'
	 and os.store_id not in (52,55)
	";  
 
$sql .= " GROUP by p2s.store_id

";
if (empty($data['filter_name_id']) ) {
    $sql .=" ,p2s.product_id ";
            
        }
       $sql.=" order by p2s.quantity desc";     
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
        //print_r($query->rows);        
        return $query->rows;
}

public function getTotalInventoryProductCompanyWise($data = array()) {
        
            $sql="select count(*)as total,sum(Qnty) as total_Qnty from (
                select p2s.store_id,os.name as store_name, p2s.product_id,p.model as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,
				ROUND((p2s.store_price + IFNULL((p2s.store_tax_amt), 0)), 2) AS price,
    ROUND(SUM(p2s.store_price + IFNULL((p2s.store_tax_amt), 0)), 2) AS Amount
               from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
               left join oc_product as p on p2s.product_id = p.product_id
			   LEFT JOIN
    oc_setting ost ON p2s.store_id = ost.store_id
";
            
           $sql .= " where os.company_id='".$data['filter_company']."' ";
if (!empty($data['filter_name_id']) ) {
    $sql .=" and p2s.product_id=".$data['filter_name_id'];
            
        }
$sql.=" and p2s.quantity>0
and ost.value=1
     and ost.`key`='config_storestatus'
	 and os.store_id not in (52,55)
"; 
       
$sql .= " GROUP by p2s.store_id";
            if (empty($data['filter_name_id']) ) {
    $sql .=" ,p2s.product_id ";
            
        }
                $sql.=") as a";
                //echo $sql;
        $query = $this->db->query($sql);

        return $query->row;
    }
	public function getInventory_linked_productCompanyWise($data = array()) { //print_r($data);
	   $sql="
               select s.product_id,opd.name as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
s.quantity as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_product_description as opd on p.product_id=opd.product_id
left join oc_store as st on st.store_id = s.store_id 
 where s.quantity>=0 and s.store_id!=0
";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}


        $sql .=" and st.company_id='".$data['filter_company']."'";
$sql .= " group by s.product_id,s.store_id";

            
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
        
        	public function getTotalInventory_linked_productCompanyWise($data = array()) {
		
            $sql="select count(*)as total from (
                select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
s.quantity as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id  where s.quantity>=0 and s.store_id!=0
";
            
          
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}
$sql .=" and st.company_id='".$data['filter_company']."'";
		
$sql .= " group by s.product_id,s.store_id) as a";
            
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        public function getInventory_report_companywise($data = array()) { //print_r($data);
	   $sql="
               select s.product_id,opd.name as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,round((b.price+ifnull((b.tax),0)),2)as price,round(sum(b.price+ifnull((b.tax),0)),2)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id 
left join oc_product_description as opd on p.product_id=opd.product_id

left join
  (select product_id,store_id,price,tax from
 ( SELECT p.product_id,p2s.product_id as pid,p2s.store_id,p.model,
 sum(p2s.quantity) as qnty, sum(CASE WHEN p2s.store_price='0.0000'
 THEN p.price ELSE p2s.store_price END) as price, (sum(p2s.quantity) * (price)) as total,
 ( ( SELECT (CASE WHEN type='F' then rate WHEN type='p' then (price *(rate/100)) else rate end) as rate
 FROM `oc_tax_rule` as rl LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id
 WHERE `tax_class_id`=p.tax_class_id ) )as tax FROM oc_product p
 LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
 WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.quantity > 0
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY p.product_id,p2s.store_id ORDER BY p.sort_order ASC )
 as a)as b on b.product_id = s.product_id and s.store_id = b.store_id

 where s.quantity>0
";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}
 

		
$sql .= " group by s.product_id";

            
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

	public function getTotalInventory_companywise($data = array()) {
		
            $sql="select count(*) as total,sum(Qnty*price) as total_sum  from ( select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,round((b.price+ifnull((b.tax),0)),2)as price,round(sum(b.price+ifnull((b.tax),0)),2)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id 


left join
  (select product_id,store_id,price,tax from
 ( SELECT p.product_id,p2s.product_id as pid,p2s.store_id,p.model,
 sum(p2s.quantity) as qnty, sum(CASE WHEN p2s.store_price='0.0000'
 THEN p.price ELSE p2s.store_price END) as price, (sum(p2s.quantity) * (price)) as total,
 ( ( SELECT (CASE WHEN type='F' then rate WHEN type='p' then (price *(rate/100)) else rate end) as rate
 FROM `oc_tax_rule` as rl LEFT JOIN oc_tax_rate on oc_tax_rate.tax_rate_id=rl.tax_rate_id
 WHERE `tax_class_id`=p.tax_class_id ) )as tax FROM oc_product p
 LEFT JOIN oc_product_to_store p2s ON (p.product_id = p2s.product_id)
 WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.quantity > 0
 AND p2s.store_id=ifnull(null,p2s.store_id) GROUP BY p.product_id,p2s.store_id ORDER BY p.sort_order ASC )
 as a)as b on b.product_id = s.product_id and s.store_id = b.store_id

 where s.quantity>0
";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		} 
 

		
$sql .= " group by s.product_id ) as aa";

                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	
	public function getfieldinventory($data = array()) 
	{ //print_r($data);
       $sql="SELECT opsf.quantity as field_quantity,DATE(opsf.MOD_DATE) as dat,oc_store.name,oc_product.model,
			oc_product_to_store.quantity as store_quantity,
			oc_product_to_store.store_price,
			oc_product_to_store.store_tax_amt 
			FROM oc_product_to_store_field as opsf
			left join oc_store on oc_store.store_id=opsf.store_id
			left join oc_product on oc_product.product_id=opsf.product_id
			left join oc_product_to_store on oc_product_to_store.product_id=opsf.product_id 
			
			and oc_product_to_store.store_id=opsf.store_id where opsf.product_id!='' and oc_product_to_store.quantity>0 ";
            
         
		if (!empty($data['filter_store']) ) {
			$sql .=" and opsf.store_id=".$data['filter_store'];
            
        }
		if (!empty($data['filter_name_id']) ) {
			$sql .=" and opsf.product_id=".$data['filter_name_id'];
            
        }
		if (!empty($data['filter_company']) ) {
			$sql .=" and oc_store.company_id=".$data['filter_company'];
            
        }
        
//$sql .= " GROUP by opsf.product_id";

            
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
             // print_r($query->rows);  
			 
        return $query->rows;
}
public function gettotalfieldinventory($data = array()) {
        
            $sql="select count(*)as total from (
                SELECT opsf.quantity as field_quantity,DATE(opsf.MOD_DATE) as dat,oc_store.name,oc_product.model,oc_product_to_store.quantity as store_quantity FROM oc_product_to_store_field as opsf
left join oc_store on oc_store.store_id=opsf.store_id
left join oc_product on oc_product.product_id=opsf.product_id
left join oc_product_to_store on oc_product_to_store.product_id=opsf.product_id and oc_product_to_store.store_id=opsf.store_id where opsf.product_id!=''
 and oc_product_to_store.quantity>0  ";
            
		if (!empty($data['filter_store']) ) {
				$sql .=" and  opsf.store_id=".$data['filter_store'];
            
        }
		if (!empty($data['filter_name_id']) ) {
			$sql .=" and opsf.product_id=".$data['filter_name_id'];
            
        }
		if (!empty($data['filter_company']) ) {
			$sql .=" and oc_store.company_id=".$data['filter_company'];
            
        }
//$sql .= " GROUP by opsf.product_id";
         
                $sql.=") as a";
              //  echo $sql;
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
	
	
}