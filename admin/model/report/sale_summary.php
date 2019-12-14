<?php
class ModelReportSaleSummary extends Model {
	
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
	

////////////////////////subsdiy_cash wit out isec/////////////////////
public function getSale_summary_subsidy_cash_wo_isec($data = array()) {	  				

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

            $sql="select Cash,Tagged,Subsidy,Cash_Tagged,Cash_subsidy,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order,Subsidy_order,Cash_tagged_order from (
                
select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',sum(Subsidy)as Subsidy,sum(Cash_Tagged) as Cash_Tagged,sum(Cash_subsidy) as Cash_subsidy,store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order,sum(Subsidy_order)as Subsidy_order,sum(Cash_tagged_order)as Cash_tagged_order, sum(Subsidy_sale)  from (


SELECT sum(total) as 'Cash','0' as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged','0' as 'Cash_subsidy',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order,'0' as 'Subsidy_order','0' as Cash_tagged_order, '0' as 'Subsidy_sale'  FROM `oc_order` o where order_status_id=5 and payment_method='Cash' ".$sqldate." ".$sqlgrp."
UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged','0' as 'Cash_subsidy',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order,'0' as 'Subsidy_order','0' as Cash_tagged_order, '0' as 'Subsidy_sale' FROM `oc_order` o where order_status_id=5 and  (payment_method='Tagged') ".$sqldate." ".$sqlgrp."

UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy',sum(cash) as 'Cash_Tagged','0' as 'Cash_subsidy',store_id,store_name,date_added,'0' as cash_order,
 '0' as tagged_order,'0' as 'Subsidy_order',count(order_id)as Cash_tagged_order, '0' as 'Subsidy_sale' FROM `oc_order` o where order_status_id=5 and  (payment_method='Tagged Cash') ".$sqldate." ".$sqlgrp."

UNION 

 
  SELECT '0' as 'Cash','0' as 'Tagged',sum(subsidy) as 'Subsidy','0' as 'Cash_Tagged',sum(cash) as 'Cash_subsidy',store_id,store_name,date_added,'0' as cash_order,'0' as tagged_order,
 count(order_id)as 'Subsidy_order','0' as Cash_tagged_order, sum(subsidy) as 'Subsidy_sale'
 FROM `oc_order` o where order_status_id=5 and payment_method='Subsidy' ".$sqldate." ".$sqlgrp."

) as tt GROUP BY tt.store_id) as a left join oc_store as st on st.store_id = a.store_id where a.store_id not in 
(select store_id from oc_store_to_unit where unit_id in (select unit_id from oc_unit where company_id=3) ) ";
 
if (!empty($data['filter_store'])) {
			$sql .= " and a.store_id= '" . $this->db->escape($data['filter_store']) . "'";
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

//////////with out isec////////////////
public function getTotalSale_subsidy_cash_wo_isec($data = array()) {


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


            $sql="select count(*) as total,sum(Cash) as Cash,sum(Tagged) as Tagged,sum(Subsidy) as Subsidy,sum(Cash_Tagged) as Cash_Tagged,sum(Cash_subsidy) as Cash_subsidy from (select Cash,Tagged,Subsidy,Cash_Tagged,Cash_subsidy,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order,Subsidy_order from (
                
select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',sum(Subsidy)as Subsidy,sum(Cash_Tagged) as Cash_Tagged,sum(Cash_subsidy) as Cash_subsidy,store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order,sum(Subsidy_order)as Subsidy_order from (


SELECT sum(total) as 'Cash','0' as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged','0' as 'Cash_subsidy',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order,'0' as 'Subsidy_order','0' as 'Cash_tagged_order' FROM `oc_order` o where order_status_id=5 and payment_method='Cash' ".$sqldate." ".$sqlgrp."
UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged','0' as 'Cash_subsidy',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order,'0' as 'Subsidy_orderr','0' as 'Cash_tagged_order' FROM `oc_order` o where order_status_id=5 and  (payment_method='Tagged') ".$sqldate." ".$sqlgrp."

UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy',sum(cash) as 'Cash_Tagged','0' as 'Cash_subsidy',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order,'0' as 'Subsidy_order',count(order_id)as Cash_tagged_order FROM `oc_order` o where order_status_id=5 and  (payment_method='Tagged Cash') ".$sqldate." ".$sqlgrp."

union
 
  SELECT '0' as 'Cash','0' as 'Tagged',sum(subsidy) as 'Subsidy','0' as 'Cash_Tagged',sum(cash) as 'Cash_subsidy',store_id,store_name,date_added,'0' as cash_order,'0' as tagged_order,
 count(order_id)as 'Subsidy_order','0' as 'Cash_tagged_order'
 FROM `oc_order` o where order_status_id=5 and payment_method='Subsidy' ".$sqldate." ".$sqlgrp."

) as tt GROUP BY tt.store_id) as a left join oc_store as st on st.store_id = a.store_id where a.store_id not in 
(select store_id from oc_store_to_unit where unit_id in (select unit_id from oc_unit where company_id=3) )";

if (!empty($data['filter_store'])) {
			$sql .= " and a.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}  

 $sql.=" ) as aaa ";
 //echo $sql;
$query = $this->db->query($sql);
		return $query->row; 
	}

/////////////////////////////date wise categorition///////////////

public function getSale_summary_category_date_wise($data = array()) {	  				

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

            $sql="select Cash,Tagged,Subsidy,Cash_Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order,Subsidy_order,Cash_tagged_order,date_added from (
                
select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',sum(Subsidy)as Subsidy,sum(Cash_Tagged) as Cash_Tagged,store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order,sum(Subsidy_order)as Subsidy_order,sum(Cash_tagged_order)as Cash_tagged_order,DATE(date_added) as date_added  from (


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

) as tt GROUP BY tt.store_id,DATE(tt.date_added)) as a left join oc_store as st on st.store_id = a.store_id ";
 
if (!empty($data['filter_store'])) {
			$sql .= " where a.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}  

		$sql .= " order by a.date_added asc";

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

//////////////////////////////
public function getTotalSale_category_date_wise($data = array()) {


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


            $sql="select count(*) as total,sum(Cash) as Cash,sum(Tagged) as Tagged,sum(Subsidy) as Subsidy,sum(Cash_Tagged) as Cash_Tagged from (select Cash,Tagged,Subsidy,Cash_Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order,Subsidy_order from (
                
select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',sum(Subsidy)as Subsidy,sum(Cash_Tagged) as Cash_Tagged,store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order,sum(Subsidy_order)as Subsidy_order,DATE(date_added) as date_added from (


SELECT sum(total) as 'Cash','0' as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order,'0' as 'Subsidy_order','0' as 'Cash_tagged_order' FROM `oc_order` o where payment_method='Cash' ".$sqldate." ".$sqlgrp."
UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order,'0' as 'Subsidy_orderr','0' as 'Cash_tagged_order' FROM `oc_order` o where  (payment_method='Tagged') ".$sqldate." ".$sqlgrp."

UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy',sum(cash) as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order,'0' as 'Subsidy_order',count(order_id)as Cash_tagged_order FROM `oc_order` o where  (payment_method='Tagged Cash') ".$sqldate." ".$sqlgrp."

union
 
  SELECT '0' as 'Cash','0' as 'Tagged',sum(total) as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,'0' as tagged_order,
 count(order_id)as 'Subsidy_order','0' as 'Cash_tagged_order'
 FROM `oc_order` o where payment_method='Subsidy' ".$sqldate." ".$sqlgrp."

) as tt GROUP BY tt.store_id,DATE(tt.date_added)) as a left join oc_store as st on st.store_id = a.store_id";

if (!empty($data['filter_store'])) {
			$sql .= " where a.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}  

 $sql.=" ) as aaa ";
 //echo $sql;
$query = $this->db->query($sql);
		return $query->row; 
	}


/////////////////////////////end of date wise categeration///////////////

//new

/////////////////////////////date wise categorition///////////////

public function getSale_summary_category_date_wise_companywise($data = array()) {	  				

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

            $sql="select Cash,Tagged,Subsidy,Cash_Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order,Subsidy_order,Cash_tagged_order,date_added from (
                
select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',sum(Subsidy)as Subsidy,sum(Cash_Tagged) as Cash_Tagged,store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order,sum(Subsidy_order)as Subsidy_order,sum(Cash_tagged_order)as Cash_tagged_order,DATE(date_added) as date_added  from (


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

) as tt GROUP BY tt.store_id,DATE(tt.date_added)) as a left join oc_store as st on st.store_id = a.store_id ";
 
if (!empty($data['filter_store'])) {
			$sql .= " where a.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}  

		$sql .= " order by a.date_added asc";

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

//////////////////////////////
public function getTotalSale_category_date_wise_companywise($data = array()) {


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


            $sql="select count(*) as total,sum(Cash) as Cash,sum(Tagged) as Tagged,sum(Subsidy) as Subsidy,sum(Cash_Tagged) as Cash_Tagged from (select Cash,Tagged,Subsidy,Cash_Tagged,a.store_id,a.store_name,st.creditlimit,st.currentcredit,cash_order,tagged_order,Subsidy_order from (
                
select sum(Cash) as 'Cash' ,sum(Tagged) as 'Tagged',sum(Subsidy)as Subsidy,sum(Cash_Tagged) as Cash_Tagged,store_id,store_name,sum(cash_order)as cash_order,sum(tagged_order)as tagged_order,sum(Subsidy_order)as Subsidy_order,DATE(date_added) as date_added from (


SELECT sum(total) as 'Cash','0' as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,count(order_id)as cash_order,'0' as tagged_order,'0' as 'Subsidy_order','0' as 'Cash_tagged_order' FROM `oc_order` o where payment_method='Cash' ".$sqldate." ".$sqlgrp."
UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order,'0' as 'Subsidy_orderr','0' as 'Cash_tagged_order' FROM `oc_order` o where  (payment_method='Tagged') ".$sqldate." ".$sqlgrp."

UNION 


SELECT '0' as 'Cash',sum(tagged) as 'Tagged','0' as 'Subsidy',sum(cash) as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,
 count(order_id)as tagged_order,'0' as 'Subsidy_order',count(order_id)as Cash_tagged_order FROM `oc_order` o where  (payment_method='Tagged Cash') ".$sqldate." ".$sqlgrp."

union
 
  SELECT '0' as 'Cash','0' as 'Tagged',sum(total) as 'Subsidy','0' as 'Cash_Tagged',store_id,store_name,date_added,'0' as cash_order,'0' as tagged_order,
 count(order_id)as 'Subsidy_order','0' as 'Cash_tagged_order'
 FROM `oc_order` o where payment_method='Subsidy' ".$sqldate." ".$sqlgrp."

) as tt GROUP BY tt.store_id,DATE(tt.date_added)) as a left join oc_store as st on st.store_id = a.store_id";

if (!empty($data['filter_store'])) {
			$sql .= " where a.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}  

 $sql.=" ) as aaa ";
 //echo $sql;
$query = $this->db->query($sql);
		return $query->row; 
	}


/////////////////////////////end of date wise categeration///////////////



////////////////////////new sale summary with case/////////////////////
public function getSale_summary_new($data = array()) {	  				
//if($data['filter_date_start']==$data['filter_date_end'])
{
	$data['filter_date_end']=date('Y-m-d',strtotime($data['filter_date_end'] . "+1 days"));
}
		$sql="
		SELECT    
SUM(CASE WHEN payment_method='Cash' THEN Cash ELSE 0 END) AS Cash,
SUM(CASE WHEN payment_method='Tagged'THEN tagged ELSE 0 END) AS Tagged,
SUM(CASE WHEN payment_method='Tagged'THEN bcml_tagged ELSE 0 END) AS bcml_tagged,
SUM(CASE WHEN payment_method='Subsidy' THEN subsidy ELSE 0 END) AS Subsidy,
SUM(CASE WHEN payment_method='Tagged Cash' THEN (tagged) ELSE 0 END) AS Tagged_cash,
SUM(CASE WHEN payment_method='Tagged Cash' THEN (cash) ELSE 0 END) AS Cash_Tagged,

SUM(CASE WHEN payment_method='Tagged Subsidy' THEN (tagged) ELSE 0 END) AS Tagged_subsidy,
SUM(CASE WHEN payment_method='Tagged Subsidy' THEN (subsidy) ELSE 0 END) AS subsidy_Tagged,

SUM(CASE WHEN payment_method='Subsidy' THEN (cash) ELSE 0 END) AS Cash_subsidy,
SUM(CASE WHEN payment_method='Cash' THEN 1 ELSE 0 END) AS cash_order,
SUM(CASE WHEN payment_method='Tagged'THEN 1 ELSE 0 END) AS tagged_order,
SUM(CASE WHEN payment_method='Subsidy' THEN 1 ELSE 0 END) AS Subsidy_order,
SUM(CASE WHEN payment_method='Tagged Cash' THEN (1) ELSE 0 END) AS Cash_tagged_order,

SUM(CASE WHEN payment_method='Tagged Subsidy' THEN (1) ELSE 0 END) AS Tagged_subsidy_order,

o.store_id,store_name,date(date_added)

FROM

`oc_order` o left join oc_store as st on st.store_id = o.store_id  where order_status_id=5 ";

	if (!empty($data['filter_date_start'])) 
	{
		
		$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . " 00:00:00' as datetime)";
	}

	if (!empty($data['filter_date_end'])) 
	{
		$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . " 00:00:00' as datetime)";
	}  
	if (!empty($data['filter_company'])) 
	{
		$sql .= " and st.company_id= '" . $this->db->escape($data['filter_company']) . "'";
	}
	if (!empty($data['filter_store'])) 
	{
		$sql .= " and o.store_id= '" . $this->db->escape($data['filter_store']) . "'";
	} 
	$sql.=" GROUP BY o.store_id ";
	
	if (isset($data['start']) || isset($data['limit'])) 
	{
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
	
//////////////////////////
public function getTotalSale_new($data = array()) 
{
	//if($data['filter_date_start']==$data['filter_date_end'])
{
	$data['filter_date_end']=date('Y-m-d',strtotime($data['filter_date_end'] . "+1 days"));
}
	$sql=" select count(DISTINCT o.store_id) as total, 
		SUM(CASE WHEN payment_method='Cash' THEN Cash ELSE 0 END) AS Cash,
		SUM(CASE WHEN payment_method='Tagged' THEN Tagged ELSE 0 END) AS Tagged,
		SUM(CASE WHEN payment_method='Tagged' THEN bcml_tagged ELSE 0 END) AS bcml_tagged,
		SUM(CASE WHEN payment_method='Subsidy' THEN Subsidy ELSE 0 END) AS Subsidy,
		SUM(CASE WHEN payment_method='Subsidy' THEN Cash ELSE 0 END) AS Cash_subsidy,
		SUM(CASE WHEN payment_method='Tagged Cash' THEN Cash ELSE 0 END) AS Cash_Tagged,
		SUM(CASE WHEN payment_method='Tagged Cash' THEN Tagged ELSE 0 END) AS CTagged,
		
		SUM(CASE WHEN payment_method='Tagged Subsidy' THEN Subsidy ELSE 0 END) AS Subsidy_Tagged,
		SUM(CASE WHEN payment_method='Tagged Subsidy' THEN Tagged ELSE 0 END) AS Tagged_Subsidy
		 FROM
	`oc_order` o left join oc_store as st on st.store_id = o.store_id  where order_status_id=5 ";

	if (!empty($data['filter_date_start'])) 
	{
		$sql .= " AND (o.date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . " 00:00:00' as datetime)";
	}

	if (!empty($data['filter_date_end'])) 
	{
		$sql .= " AND (o.date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . " 00:00:00' as datetime)";
	}  
	
	if (!empty($data['filter_company'])) 
	{
		$sql .= " and st.company_id= '" . $this->db->escape($data['filter_company']) . "'";
	}
	if (!empty($data['filter_store'])) 
	{
		$sql .= " and o.store_id= '" . $this->db->escape($data['filter_store']) . "'";
	} 
	//echo $sql;
	$query = $this->db->query($sql);
		return $query->row; 
}
public function getFm_cash_sale($data = array()) {   
                 

        $sql="select cash,total,fmcode,store_name,order_id,store_id,payment_firstname,telephone,date(date_added) as order_date 
		from oc_order where payment_method='Cash' ";
		if (!empty($data['filter_date_start'])) 
		{
            $sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) 
		{
            $sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        } 
		if (!empty($data['filter_store'])) 
		{
            $sql .= " and store_id= '" . $this->db->escape($data['filter_store']) . "'";
        }		
		if (isset($data['start']) || isset($data['limit'])) 
		{
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
public function getFm_cash_sale_total($data = array()) {                      

        $sql="select count(*) as total from ( select order_id from oc_order where payment_method='Cash' ";
		if (!empty($data['filter_date_start'])) 
		{
            $sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) 
		{
            $sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        } 
		if (!empty($data['filter_store'])) 
		{
            $sql .= " and store_id= '" . $this->db->escape($data['filter_store']) . "'";
        }
		$sql.=" ) as aa ";		
        
        $query = $this->db->query($sql);
                
        return $query->row;
    }
        
        public function get_product($order_id){
        
       $sql=" SELECT name,quantity,product_id FROM oc_order_product where order_id='".$order_id."'";
       $query = $this->db->query($sql);
        return $query->rows;
       
}


}