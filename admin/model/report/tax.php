<?php
class ModelReportTax extends Model {
	
	public function getTaxreport($data = array()) {
	   
//SELECT obt.transid,obt.bank_name,obt.amount,obt.date_added,obt.bank_id,obt.store_id,oc_store.name FROM `oc_bank_transaction` as obt RIGHT JOIN oc_store on oc_store.store_id=obt.store_id  WHERE DATE(obt.date_added) >= '2016-10-30' AND DATE(obt.date_added) <= '2017-01-18'
            $sql="select oco.store_id,oco.store_name,oco.order_id,ocp.product_id,ocp.name as Product_name,
date(ocp.ORD_DATE) as sale_date,round(ifnull(ocp.price,'00.00'),2)as price,
round(ifnull(ocp.tax,'00.00'),2)as tax_amount,
 ifnull(ocp.quantity,0)as quantity,
 round((ifnull(ocp.price,'00.00')+ifnull(ocp.tax,'00.00'))*ifnull(ocp.quantity,0),2)as Total,
 
 round(ifnull(ocp.tax,'00.00')*ifnull(ocp.quantity,0),2)as Total_Tax

from oc_order_product as ocp
left join oc_order as oco on ocp.order_id = oco.order_id

where date(ocp.ORD_DATE) between ifnull('" . $this->db->escape($data['filter_date_start']) . "',date(ocp.ORD_DATE))
and ifnull('" . $this->db->escape($data['filter_date_end']) . "',date(ocp.ORD_DATE))

";
 
                  
           
            $sql.=" Order by oco.order_id desc ";
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

	public function getTotal_transation($data = array()) {
		
            $sql="SELECT count(*) as total FROM (select oco.store_id,oco.store_name,oco.order_id,ocp.product_id,ocp.name as Product_name,
date(ocp.ORD_DATE) as sale_date,round(ifnull(ocp.price,'00.00'),2)as price,
round(ifnull(ocp.tax,'00.00'),2)as tax_amount,
 ifnull(ocp.quantity,0)as quantity,
 round((ifnull(ocp.price,'00.00')+ifnull(ocp.tax,'00.00'))*ifnull(ocp.quantity,0),2)as Total,
 
 round(ifnull(ocp.tax,'00.00')*ifnull(ocp.quantity,0),2)as Total_Tax

from oc_order_product as ocp
left join oc_order as oco on ocp.order_id = oco.order_id

where date(ocp.ORD_DATE) between ifnull('" . $this->db->escape($data['filter_date_start']) . "',date(ocp.ORD_DATE))
and ifnull('" . $this->db->escape($data['filter_date_end']) . "',date(ocp.ORD_DATE))
 ) as aa";
                    
                    
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
}