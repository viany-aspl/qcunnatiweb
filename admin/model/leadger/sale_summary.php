<?php
class ModelLeadgerSaleSummary extends Model { 
	

/////////////////////////////categorition///////////////

public function getSale_summary_category($data = array()) {	  				

		if (!empty($data['filter_date_start'])) {
			$sqldate .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sqldate .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}                                
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
                 
		switch($group) {
			case 'day';
				$sqlgrp .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added),o.store_id";
				break;
			default:
			case 'week':
				$sqlgrp .= " GROUP BY DATE(o.date_added),o.store_id";
				break;
			case 'month':
				$sqlgrp .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added),o.store_id";
				break;
			case 'year':
				$sqlgrp .= " GROUP BY YEAR(o.date_added),o.store_id";
				break;
		}

            $sql="select Cash,Tagged,Subsidy,Cash_Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order,Subsidy_order,Cash_tagged_order from (
                
select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',sum(Subsidy)as Subsidy,sum(Cash_Tagged) as Cash_Tagged,store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order,sum(Subsidy_order)as Subsidy_order,sum(Cash_tagged_order)as Cash_tagged_order  from (


SELECT sum(total) as 'Cash','0' as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order,'0' as 'Subsidy_order','0' as Cash_tagged_order  FROM `oc_order` o where payment_method='Cash' ".$sqldate." ".$sqlgrp."
UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order,'0' as 'Subsidy_order','0' as Cash_tagged_order FROM `oc_order` o where  (payment_method='Tagged') ".$sqldate." ".$sqlgrp."

UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy',sum(cash) as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,
 '0' as tagged_order,'0' as 'Subsidy_order',count(order_id)as Cash_tagged_order FROM `oc_order` o where  (payment_method='Tagged Cash') ".$sqldate." ".$sqlgrp."

union
 
  SELECT '0' as 'Cash','0' as 'Tagged',sum(total) as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,'0' as tagged_order,
 count(order_id)as 'Subsidy_order','0' as Cash_tagged_order 
 FROM `oc_order` o where payment_method='Subsidy' ".$sqldate." ".$sqlgrp."

) as tt GROUP BY tt.store_id) as a left join oc_store as st on st.store_id = a.store_id ";
 
if (!empty($data['filter_store'])) {
			$sql .= " where a.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}  

		//$sql .= " ORDER BY o.date_added DESC";

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




}