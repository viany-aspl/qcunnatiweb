<?php
class ModelTagSaleSummary extends Model {
	
	public function getSale_summary($data = array()) {	  				

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




            $sql="select Cash,Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order from (select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order from (
SELECT sum(total) as 'Cash','0' as 'Tagged',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order FROM `oc_order_leads` o where payment_method='Tagged' and order_status_id='1'  ".$sqldate." ".$sqlgrp."
UNION 
SELECT '0' as 'Cash',sum(total) as 'Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order FROM `oc_order_leads` o where  (payment_method='Tagged' or payment_method='Tagged Cash') and order_status_id='5' ".$sqldate." ".$sqlgrp."
) as tt GROUP BY tt.store_id) as a left join oc_store as st on st.store_id = a.store_id ";
 

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

	public function getTotalSale($data = array()) {


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




            $sql="select count(*) as total,sum(cash_order) as cash_order,sum(tagged_order) as tagged_order from (select Cash,Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order from (select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order from (
SELECT sum(total) as 'Cash','0' as 'Tagged',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order FROM `oc_order_leads` o where payment_method='Tagged' and order_status_id='1'  ".$sqldate." ".$sqlgrp."
UNION 
SELECT '0' as 'Cash',sum(total) as 'Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order FROM `oc_order_leads` o where  (payment_method='Tagged' or payment_method='Tagged Cash') and order_status_id='5' ".$sqldate." ".$sqlgrp."
) as tt GROUP BY tt.store_id) as a left join oc_store as st on st.store_id = a.store_id ";
 

	$sql.=" ) as tt";	
 
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getcash($store_id,$date)       
        {
            $sql="SELECT sum(total) as 'Cash' FROM `oc_order` o where payment_method='Cash' and date(date_added)='".$date."' and `store_id`='".$store_id."' ";
            //echo $sql;
            $query = $this->db->query($sql);                
        return $query->row;
        }
        public function gettagged($store_id,$date)
        {
         $sql="SELECT sum(total) as 'Tagged' FROM `oc_order` o where payment_method='Tagged' and date(date_added)='".$date."'  and `store_id`='".$store_id."'";   
         $query = $this->db->query($sql);                
     return $query->row;
        }
      
        public function getcashmonth($store_id)       
        {
            $sdate=date('Y-m-')."01";
            $edate=date('Y-m-d');
            
            $sql="SELECT sum(total) as 'Cash' FROM `oc_order` o where payment_method='Cash' and date(date_added)<='".$edate."' and date(date_added) >='".$sdate."' and `store_id`='".$store_id."' ";
            //echo $sql;
            $query = $this->db->query($sql);                
        return $query->row;
        }
        public function gettaggedmonth($store_id)
        {
         $sdate=date('Y-m-')."01";
         $edate=date('Y-m-d');
         $sql="SELECT sum(total) as 'Tagged' FROM `oc_order` o where payment_method='Tagged' and date(date_added)<='".$edate."' and date(date_added) >='".$sdate."' and `store_id`='".$store_id."'";   
         $query = $this->db->query($sql);                
         return $query->row;
        }

//company wise
        public function getSale_summaryCompanyWise($data = array()) {                      

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




            $sql="select Cash,Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order from (select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order from (
SELECT sum(total) as 'Cash','0' as 'Tagged',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order FROM `oc_order_leads` o where payment_method='Tagged' and order_status_id='1'  ".$sqldate." ".$sqlgrp."
UNION
SELECT '0' as 'Cash',sum(total) as 'Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order FROM `oc_order_leads` o where  (payment_method='Tagged' or payment_method='Tagged Cash') and order_status_id='5' ".$sqldate." ".$sqlgrp."
) as tt GROUP BY tt.store_id) as a left join oc_store as st on st.store_id = a.store_id  where st.company_id='".$data['filter_company']."'";
 

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
               // echo $sql;
        $query = $this->db->query($sql);
                
        return $query->rows;
    }

    public function getTotalSaleCompanyWise($data = array()) {


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




            $sql="select count(*) as total,sum(cash_order) as cash_order,sum(tagged_order) as tagged_order from (select Cash,Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order from (select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order from (
SELECT sum(total) as 'Cash','0' as 'Tagged',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order FROM `oc_order_leads` o where payment_method='Tagged' and order_status_id='1'  ".$sqldate." ".$sqlgrp."
UNION
SELECT '0' as 'Cash',sum(total) as 'Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order FROM `oc_order_leads` o where  (payment_method='Tagged' or payment_method='Tagged Cash') and order_status_id='5' ".$sqldate." ".$sqlgrp."
) as tt GROUP BY tt.store_id) as a left join oc_store as st on st.store_id = a.store_id where st.company_id='".$data['filter_company']."'";
 

    $sql.=" ) as tt";    
 
                //echo $sql;
        $query = $this->db->query($sql);

        return $query->row;
    }


}