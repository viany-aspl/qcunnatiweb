<?php
class ModelReportAsplSale extends Model {
	
	
	public function getOrders($data = array()) { //print_r($data['filter_store']);
		

		$sql="
select opip.invoice_t_s_id,opip.invoice_n,opip.product_id,opip.product_name as name,
opip.p_price,opip.p_qnty as qnty,opip.po_order_id,opip.p_tax_rate,opip.p_tax_type as tax_title,opip.po_store_id as store_id,
os.name as store_name,os2.name as store_name2,concat(opi.po_invoice_n,'/',opi.po_invoice_prefix)as po_invoice_prefix_n,
opi.create_date  as dats,opi.order_total,opi.partner_type,opi.po_ware_house

from oc_po_invoice_product as opip
left join oc_po_invoice as opi on opip.invoice_t_s_id = opi.sid
left join oc_store as os on os.store_id = opip.po_store_id
left join oc_b2b_partner as os2 on opip.po_store_id=os2.sid  

where    opip.product_id!=''
 ";

	if (!empty($data['filter_name']))
                       {
			$sql .= " and  opip.product_id= '".$data['filter_name']."' ";
                       }
	if (!empty($data['filter_store']))
                       {
			$sql .= " and  opip.po_store_id = '".$data['filter_store']."' ";
                       }

	if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  DATE(opi.create_date) >= ('".$data['filter_date_start']."') ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and DATE(opi.create_date) <= ('".$data['filter_date_end']."') ";
                        
		}
		$sql.="   order by create_date desc  ";
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


	$sql = " select count(*) as total from ( select opip.invoice_t_s_id,opip.invoice_n,opip.product_id,opip.product_name as name,
opip.p_price,opip.p_qnty as qnty,opip.po_order_id,opip.p_tax_rate,opip.p_tax_type as tax_title,opip.po_store_id as store_id,
os.name as store_name,concat(opi.po_invoice_n,'/',opi.po_invoice_prefix)as po_invoice_prefix_n,
opi.create_date  as dats,opi.order_total,opi.partner_type,opi.po_ware_house

from oc_po_invoice_product as opip
left join oc_po_invoice as opi on opip.invoice_t_s_id = opi.sid
left join oc_store as os on os.store_id = opip.po_store_id

where    opip.product_id!=''
 ";

	if (!empty($data['filter_name']))
                       {
			$sql .= " and  opip.product_id= '".$data['filter_name']."' ";
                       }
	if (!empty($data['filter_store']))
                       {
			$sql .= " and  opip.po_store_id = '".$data['filter_store']."' ";
                       }

	if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  DATE(opi.create_date) >= ('".$data['filter_date_start']."') ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and DATE(opi.create_date) <= ('".$data['filter_date_end']."') ";
                        
		}

		$sql.=") as data";

                //echo $sql;

		
                $query = $this->db->query($sql);

		return $query->row;
	}
        

public function getOrdersCompanyWise($data = array()) { //print_r($data['filter_store']);
		
         
		$sql="SELECT
    p.name,
    p.order_id,
    a.tax_title AS tax_title,
    p.product_id,
    s.store_name,
    s.store_id,
    COUNT(p.order_id) AS No_of_orders,
    SUM(p.quantity) AS qnty,
    p.total AS Total_sales,
    (p.tax) AS Total_tax,
    DATE(s.date_added) AS dats,
os.company_id
   
FROM
    oc_order_product AS p
        LEFT JOIN
    oc_order AS s ON p.order_id = s.order_id
        LEFT JOIN
    (SELECT
        ocp.product_id, tr.name AS tax_title
    FROM
        oc_product AS op
    LEFT JOIN oc_order_product AS ocp ON ocp.product_id = op.product_id
    LEFT JOIN oc_tax_rule AS tl ON tl.tax_class_id = op.tax_class_id
    LEFT JOIN oc_tax_rate AS tr ON tr.tax_rate_id = tl.tax_rate_id
    WHERE
        ocp.product_id IS NOT NULL
    GROUP BY ocp.product_id) AS a ON a.product_id = p.product_id
    
     LEFT JOIN oc_store as os on os.store_id = s.store_id";
                
           
            $sql.=' where s.order_status_id=5  ';
            
		if (!empty($data['filter_name'])) {
			$sql .= " and  p.product_id= '".$data['filter_name']."'  ";
		}
                
                
                if (!empty($data['filter_date_start']))
                       {
			$sql .= " and  DATE(s.date_added) >= ('".$data['filter_date_start']."') ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and DATE(s.date_added) <= ('".$data['filter_date_end']."') ";
                        
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
			$sql .= " and  DATE(s.date_added) >= ('".$data['filter_date_start']."') ";
                       }

		
                  if (!empty($data['filter_date_end'])) {
                        
			$sql .= " and DATE(s.date_added) <= ('".$data['filter_date_end']."') ";
                        
		}
    $sql.=" group by date(p.ORD_DATE),s.store_id,p.product_id,p.order_id ";

		$sql.=") as data";

              //  echo $sql;

		
                $query = $this->db->query($sql);

		return $query->row;
	}




}