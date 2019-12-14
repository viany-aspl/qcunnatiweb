<?php
class ModelReportContractor extends Model {
	
	public function getInventory_report($data = array()) { //print_r($data);
	   $sql="select * from oc_contractor_product where quantity>0 ";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and contractor_id=".$data['filter_store'];
			
		}
 
if (!empty($data['filter_unit']) ) {
    $sql .=" and store_id=".$data['filter_unit'];
			
		}
		
$sql .= " group by product_id,contractor_id";

            
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
                select * from oc_contractor_product where quantity>0 ";
            
          
if (!empty($data['filter_store']) ) {
    $sql .=" and contractor_id=".$data['filter_store'];
			
		}

if (!empty($data['filter_unit']) ) {
    $sql .=" and store_id=".$data['filter_unit'];
			
		}		
$sql .= " group by product_id,contractor_id) as a";
            
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

public function get_Transactions_report($data = array()) { //print_r($data);
	   $sql="select * from oc_contractor_product_trans where quantity>0 ";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and contractor_id=".$data['filter_store'];
			
}
if (!empty($data['filter_unit']) ) {
    $sql .=" and store_id=".$data['filter_unit'];
			
		}
if (!empty($data['filter_date_start']) ) {
    $sql .=" and date(crdate)>='".$data['filter_date_start']."'";
			
}
if (!empty($data['filter_date_end']) ) {
    $sql .=" and date(crdate)<='".$data['filter_date_end']."'";
			
}
 

		
$sql .= " group by product_id,contractor_id";

            
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

	public function getTotal_Transactions($data = array()) {
		
            $sql="select count(*)as total from (
                select * from oc_contractor_product_trans where quantity>0 ";
            
          
if (!empty($data['filter_store']) ) {
    $sql .=" and contractor_id=".$data['filter_store'];
			
}
if (!empty($data['filter_unit']) ) {
    $sql .=" and store_id=".$data['filter_unit'];
			
		}
if (!empty($data['filter_date_start']) ) {
    $sql .=" and date(crdate)>='".$data['filter_date_start']."'";
			
}
if (!empty($data['filter_date_end']) ) {
    $sql .=" and date(crdate)<='".$data['filter_date_end']."'";
			
}

		
$sql .= " group by product_id,contractor_id) as a";
            
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}


public function get_Credit_report($data = array()) { //print_r($data);
	   $sql="select * from oc_contractor  where circle_code>0";
            
         
if (!empty($data['filter_store']) ) {
    $sql .=" and circle_code=".$data['filter_store'];
			
}

if (!empty($data['filter_unit']) ) {
    $sql .=" and unit_id=".$data['filter_unit'];
			
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

	public function getTotal_Credit($data = array()) {
		
            $sql="select count(*)as total from (
                select * from oc_contractor where  circle_code>0 ";
            
          
if (!empty($data['filter_store']) ) {
    $sql .=" and circle_code=".$data['filter_store'];
			
}

if (!empty($data['filter_unit']) ) {
    $sql .=" and unit_id=".$data['filter_unit'];
			
		}

$sql.=" ) as aa";
	
            
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}










///////////////////////////////////////////////////////////////////////////////

   public function getInventory_reportProductWise($data = array()) { //print_r($data);
       $sql="
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
        
            $sql="select count(*)as total from (
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

        return $query->row['total'];
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
 
if (!empty($data['filter_unit']) ) {
    $sql .=" and s.unit_id=".$data['filter_unit'];
			
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
if (!empty($data['filter_unit']) ) {
    $sql .=" and s.unit_id=".$data['filter_unit'];
			
		}


		
$sql .= " group by s.product_id,s.store_id) as a";
            
                
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
        public function getContractors($data = array()) {
		$sql="SELECT * FROM " . DB_PREFIX . "contractor ORDER BY circle_code ";
	       $query = $this->db->query($sql);
                        $store_data=array();
                        foreach ($query->rows as $storedb) { 
                         $store_datan =array(
			'store_id' => $storedb['circle_code'],
			'name'     => $storedb['circle_code']."-".$storedb['store_name']."-".$storedb['company']
			
			
		);
			array_push($store_data,  $store_datan);                                          							
                        }
			//$this->cache->set('store', $store_data);
		//$storedb['name']

		return $store_data;
	}
}