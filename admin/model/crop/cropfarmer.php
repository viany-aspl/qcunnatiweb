<?php
class ModelCropCropfarmer extends Model {

 

	public function getcropfarmerdata($data = array()) {
		$sql = "SELECT oc.customer_id,oc.firstname,oc.lastname,oc.aadhar,oc.telephone,DATE(oc.date_added) as date_added,crop.name as crop1,ad.acre1,ad.acre2,crop2.name as crop2
FROM `oc_customer` as oc
left join oc_address as ad on oc.customer_id=ad.customer_id
left join oc_crop as crop on crop.id=ad.crop1
left join oc_crop as crop2 on crop.id=ad.crop2 where oc.customer_id!=''  ";
              if (!empty($data['filter_name'])) {
			$sql .= " and ad.crop1= '" . (int)$data['filter_name'] . "'";
		} 
 if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		} 
		$sql.=" group by  oc.telephone order by oc.date_added desc  ";
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
		$logs=new Log("a.log");
		$logs->write($sql);	
		$query = $this->db->query($sql);

		return $query->rows;
	}
        
	
        	public function getTotalbankdata($data = array()) {
		$sql = "select count(*) as total from ( SELECT oc.customer_id,oc.firstname,oc.lastname,oc.aadhar,oc.telephone,DATE(oc.date_added) as date_added,crop.name as crop1,ad.acre1,ad.acre2,crop2.name as crop2
FROM `oc_customer` as oc
left join oc_address as ad on oc.customer_id=ad.customer_id
left join oc_crop as crop on crop.id=ad.crop1
left join oc_crop as crop2 on crop.id=ad.crop2 where oc.customer_id!=''  ";
              if (!empty($data['filter_name'])) {
			$sql .= " and ad.crop1= '" . (int)$data['filter_name'] . "'";
		} 
 if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		} 
		$sql.=" group by  oc.telephone ) as aa";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
       
        public function getcropname()
        {
                $sql = "SELECT id,name FROM `oc_crop` ";
             
		$query = $this->db->query($sql);

		return $query->rows;
        }

}