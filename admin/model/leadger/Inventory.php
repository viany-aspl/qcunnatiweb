<?php
class ModelLeadgerInventory extends Model {
	
	public function getInventory_report($data = array()) { //print_r($data);
	   $sql="
               select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
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

	public function getTotalInventory($data = array()) {
		
            $sql="select count(*)as total from (
                select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
sum(s.quantity)as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
left join oc_store as st on st.store_id = s.store_id  where s.quantity>0
";
            
          
if (!empty($data['filter_store']) ) {
    $sql .=" and s.store_id=".$data['filter_store'];
			
		}

		
$sql .= " group by s.product_id) as a";
            
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
public function getInventory_report_excel($data = array()) { //print_r($data);
       $sql="
               select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
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
 

        
$sql .= " group by s.product_id";

            
            
                //echo $sql;
        $query = $this->db->query($sql);
                
        return $query->rows;
    }
  

   public function getInventory_reportProductWise($data = array()) { //print_r($data);
       $sql="
               select p2s.store_id,os.name as store_name, p2s.product_id,p.model as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,round(ifnull(((b.price+ifnull((b.tax),0))),0),2)as price,round(ifnull((sum(b.price+ifnull((b.tax),0))),0),2)as Amount
               from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
               left join oc_product as p on p2s.product_id = p.product_id

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
 as a)as b on b.product_id = p2s.product_id and p2s.store_id = b.store_id
                
";
            
         
if (!empty($data['filter_name_id']) ) {
    $sql .=" where p2s.product_id=".$data['filter_name_id'];
            
        }
 

        
$sql .= " GROUP by p2s.store_id";
if (empty($data['filter_name_id']) ) {
    $sql .=" ,p2s.product_id ";
            
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
        //echo $sql;
        $query = $this->db->query($sql);
                
        return $query->rows;
}
public function getTotalInventoryProductWise($data = array()) {
        
            $sql="select count(*)as total,sum(Qnty) as total_Qnty from (
                select p2s.store_id,os.name as store_name, p2s.product_id,p.model as Product_name,p.tax_class_id, sum(p2s.quantity)as Qnty,p2s.store_price as price,sum(p2s.store_price)as Amount
               from oc_store os LEFT JOIN oc_product_to_store p2s on p2s.store_id=os.store_id
               left join oc_product as p on p2s.product_id = p.product_id
";
            
          
if (!empty($data['filter_name_id']) ) {
    $sql .=" where p2s.product_id=".$data['filter_name_id'];
            
        }

        
$sql .= " GROUP by p2s.store_id";
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
               select s.product_id,p.model as Product_name,p.tax_class_id,s.store_id,st.name as store_name,
s.quantity as Qnty,s.store_price as price,sum(s.store_price)as Amount
from oc_product_to_store as s
left join oc_product as p on s.product_id = p.product_id
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
	
}