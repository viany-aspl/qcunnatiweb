<?php
class ModelReportProductSale extends Model {
	
	
	public function getOrders($data = array()) { //print_r($data['filter_store']);
		//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
         
		$sql="select p.name,p.order_id,p.product_id, s.store_name,s.store_id,
					count(p.order_id)as No_of_orders, sum(p.quantity) as qnty,
					p.total as Total_sales,(p.tax)as Total_tax,p.price as price, date(s.date_added)as dats,
					p.discount_type as discount_type,p.discount_value as discount_value from oc_order_product as p 
					left join oc_order as s on p.order_id = s.order_id  ";
					/*
					left join (select ocp.product_id,tr.name as tax_title
from  oc_product as op
left join oc_order_product as ocp on ocp.product_id = op.product_id
left join oc_tax_rule as tl on tl.tax_class_id = op.tax_class_id
left join oc_tax_rate as tr on tr.tax_rate_id = tl.tax_rate_id
where ocp.product_id is not null group by ocp.product_id) as a on a.product_id=p.product_id
					*/
$sql.=' where s.order_status_id=5  ';
		if (!empty($data['filter_name'])) {
			$sql .= " and  p.product_id= '".$data['filter_name']."'  ";
		}
                
                if (!empty($data['filter_store'])) {
			$sql .= " and  s.store_id= ('".$data['filter_store']."') ";
		}


                if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (s.date_added) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (s.date_added) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}


		    $sql.=" group by date(s.date_added),s.store_id,p.product_id,p.order_id order by s.date_added desc";
			//,p.order_id

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
	//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}

	$sql="select count(*) as total from ( select p.name from oc_order_product as p 
		   left join oc_order as s on p.order_id = s.order_id  ";
		$sql.=' where s.order_status_id=5  ';
		if (!empty($data['filter_name'])) {
			$sql .= " and  p.product_id= ('".$data['filter_name']."') ";
		}
                if (!empty($data['filter_store'])) {
			$sql .= " and  s.store_id= ('".$data['filter_store']."') ";
		}

                
                if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (s.date_added) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (s.date_added) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
    $sql.=" group by date(s.date_added),s.store_id,p.product_id,p.order_id ";
	//,p.order_id

		$sql.=") as data";

                //echo $sql;

		
                $query = $this->db->query($sql);

		return $query->row;
	}
        
public function getOrders_wo_isec($data = array()) { //print_r($data['filter_store']);
		
         //if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
		$sql="select p.name,p.order_id,a.tax_title as tax_title,p.product_id, s.store_name,s.store_id,count(p.order_id)as No_of_orders, sum(p.quantity) as qnty,
                   p.total as Total_sales,(p.tax)as Total_tax, date(s.date_added)as dats,p.discount_type as discount_type,p.discount_value as discount_value from oc_order_product as p 
		   left join oc_order as s on p.order_id = s.order_id  
                             
left join (select ocp.product_id,tr.name as tax_title
from  oc_product as op
left join oc_order_product as ocp on ocp.product_id = op.product_id
left join oc_tax_rule as tl on tl.tax_class_id = op.tax_class_id
left join oc_tax_rate as tr on tr.tax_rate_id = tl.tax_rate_id
where ocp.product_id is not null group by ocp.product_id) as a on a.product_id=p.product_id
                           ";
$sql.=' where s.order_status_id=5  ';
		if (!empty($data['filter_name'])) {
			$sql .= " and  p.product_id= '".$data['filter_name']."'  ";
		}
                
                
                if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (s.date_added) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (s.date_added) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
		$sql.=" and s.store_id not in (select store_id from oc_store_to_unit where unit_id in (select unit_id from oc_unit where company_id=3)) ";

		    $sql.=" group by date(p.ORD_DATE),s.store_id,p.product_id,p.order_id order by s.date_added desc";
			//,p.order_id

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

	public function getTotalOrders_wo_isec($data = array()) {
//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}

	$sql="select count(*) as total from ( select p.name from oc_order_product as p 
		   left join oc_order as s on p.order_id = s.order_id  ";
		$sql.=' where s.order_status_id=5  ';
		if (!empty($data['filter_name'])) {
			$sql .= " and  p.product_id= ('".$data['filter_name']."') ";
		}
                
                
                if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (s.date_added) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (s.date_added) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
	$sql.=" and s.store_id not in (select store_id from oc_store_to_unit where unit_id in (select unit_id from oc_unit where company_id=3)) ";
    $sql.=" group by date(p.ORD_DATE),s.store_id,p.product_id,p.order_id ";
	//,p.order_id

		$sql.=") as data";

                //echo $sql;

		
                $query = $this->db->query($sql);

		return $query->row;
	}
        


public function getOrdersCompanyWise($data = array()) { //print_r($data['filter_store']);
		
        //if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			} 
		$sql="SELECT
    p.name,
    p.order_id,
    
    p.product_id,
    s.store_name,
    s.store_id,
    COUNT(p.order_id) AS No_of_orders,
    SUM(p.quantity) AS qnty,
    p.total AS Total_sales,
    (p.tax) AS Total_tax,
    DATE(s.date_added) AS dats,
    s.telephone as telephone,
	p.price as price,
    concat(s.invoice_prefix,'-',s.invoice_no) as invoice_number,
    s.payment_firstname as farmer_name,
    s.payment_address_1 as village,
os.company_id
   
FROM
    oc_order_product AS p
        LEFT JOIN
    oc_order AS s ON p.order_id = s.order_id
        
    
     LEFT JOIN oc_store as os on os.store_id = s.store_id";
                
           
            $sql.=' where s.order_status_id=5  ';
            
		if (!empty($data['filter_name'])) {
			$sql .= " and  p.product_id= '".$data['filter_name']."'  ";
		}
                
                
                if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (s.date_added) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (s.date_added) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}

                 $sql .= " AND os.company_id='".$data['filter_company']."' ";
		    $sql.=" group by date(p.ORD_DATE),s.store_id,p.product_id,p.order_id order by s.date_added desc";
               
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

        public function getTotalOrdersCompanyWise($data = array()) {

//if($data["filter_date_start"]==$data["filter_date_end"])
			{
				$data["filter_date_end"]=date('Y-m-d',strtotime($data["filter_date_end"] . "+1 days"));
			}
	$sql="SELECT
    COUNT(*) AS total
FROM
    (SELECT
        p.name
    FROM
        oc_order_product AS p
    LEFT JOIN oc_order AS s ON p.order_id = s.order_id
    LEFT JOIN oc_store as os on s.store_id = os.store_id ";
        
        $sql .= " where os.company_id='".$data['filter_company']."' ";
		$sql.=' and s.order_status_id=5  ';
		if (!empty($data['filter_name'])) {
			$sql .= " and  p.product_id= ('".$data['filter_name']."') ";
		}
                
                
                if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  (s.date_added) >= CAST('".$data['filter_date_start']."' as DATETIME) ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and (s.date_added) <= CAST('".$data['filter_date_end']."' as DATETIME) ";
                        
		}
    $sql.=" group by date(p.ORD_DATE),s.store_id,p.product_id,p.order_id ";

		$sql.=") as data";

              //  echo $sql;

		
                $query = $this->db->query($sql);

		return $query->row;
	}




}