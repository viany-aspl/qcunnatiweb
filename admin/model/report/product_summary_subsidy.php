<?php
class ModelReportProductSummarySubsidy extends Model {
	
	
    public function getcategoryprosubsidy() {
		
		$sql="SELECT * FROM oc_category_subsidy_bcml";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	
	
	 public function getproductsummarysubsidy($data = array()) {
		
		$sql="SELECT * FROM oc_order_product As ocp left join oc_order ord ON(ocp.order_id=ord.order_id) 
        where ocp.BCMLCODE!=''" ;
		if (!empty($data['filter_category'])) 
		{
			$sql .= " And ocp.SUBSIDY_CAT= '" . $this->db->escape($data['filter_category'])."'";
		}
		
	    if (!empty($data['filter_start_date'])) 
		{
			$sql .= " AND date(ord.date_added) >= '" . $this->db->escape($data['filter_start_date'])."'";
		}

		if (!empty($data['filter_end_date'])) 
		{
			$sql .= " AND date(ord.date_added) <= '" . $this->db->escape($data['filter_end_date'])."'";
		}  

		
	    if (!empty($data['filter_store'])) 
		{
			$sql .= " and ord.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
		//print_r($sql );
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
	
	
	public function getTotalsubsidy($data = array()) {
		
		$sql="SELECT * FROM oc_order_product As ocp left join oc_order ord ON(ocp.order_id=ord.order_id) 
        where ocp.BCMLCODE!=''" ;
		if (!empty($data['filter_category'])) 
		{
			$sql .= " And ocp.SUBSIDY_CAT= '" . $this->db->escape($data['filter_category'])."'";
		}
		
	    if (!empty($data['filter_start_date'])) 
		{
			$sql .= " AND date(ord.date_added) >= '" . $this->db->escape($data['filter_start_date'])."'";
		}

		if (!empty($data['filter_end_date'])) 
		{
			$sql .= " AND date(ord.date_added) <= '" . $this->db->escape($data['filter_end_date'])."'";
		}  

		
	   if (!empty($data['filter_store'])) 
		{
			$sql .= " and ord.store_id= '" . $this->db->escape($data['filter_store']) . "'";
		}
		//print_r($sql );
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
		return $query->num_rows;
	}

	
}
