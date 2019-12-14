<?php
class ModelCatalogPocoMargin extends Model {
	public function getstatus($store_id) {
			
		$sql="SELECT * FROM oc_store where  store_id = '" . (int)$store_id . "'  ";
		$query=$this->db->query($sql);
		return $query->row;
		
		
	}
	
	public function updateacceptance($store_id)
        {
         $this->db->query("UPDATE " . DB_PREFIX . "store SET poco_margin_acceptance = '1' WHERE store_id = '" . $store_id . "'");

        }
		
		
		public function getmarginlist($data = array())
    { 
        $sql="select * from ".DB_PREFIX . "product_margin where margin_id!='' "; 
		
		if (!empty($data['filter_month'])) 
        {
            $sql.=" and month_year='".$data['filter_month']."'";
		}
		if (isset($data['start']) || isset($data['limit'])) 
		{
			if ($data['start'] < 0) 
			{
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) 
			{
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		//echo $sql;
		$query = $this->db->query($sql);
		
		return $query->rows;
    }
	
	public function getmargintotal($data = array())
    { 
        $sql="select * from ".DB_PREFIX . "product_margin where margin_id!='' "; 
		

		$query = $this->db->query($sql);
		
		return $query->rows;
    }
	
	
	public function getmargindata($data = array())
    { 
        $sql="select * from ".DB_PREFIX . "product_margin where margin_id!='' and month_year='".$data['filter_month']."'"; 
		
		
		$query = $this->db->query($sql);
		
		return $query->rows;
    }
}
