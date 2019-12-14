<?php

class ModelB2bpartnerB2bpartner extends Model {

    public function addb2bpartner($data) {
      
                 $sql2="insert into  oc_b2b_partner set name='".$data["name"]."',email='".$data["email"]."',pan_card='".$data["pancard"]."',gstn='".$data["gstn"]."',address='".$data["address"]."',telephone='".$data["telephone"]."'  ";
		$query2 = $this->db->query($sql2);
    }


    public function getb2bpartner($data = array()) {

        $sql = "SELECT * FROM " . DB_PREFIX . "b2b_partner";
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
       //  echo $sql;//exit;
        return $query->rows;
    }
    
    public function getUnitName($data = array()) 
    {

        $sql = "SELECT  * FROM " . DB_PREFIX . "unit ";
	
        $query = $this->db->query($sql);
      
        return $query->rows;
    }

    public function getTotalb2bpartner($data = array()) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "b2b_partner");

        return $query->row['total'];
    }
    public function getstorebyunitid($unit_id)
    {
        $sql = "SELECT  oc_store.* FROM " . DB_PREFIX . "store_to_unit left join oc_store on oc_store_to_unit.store_id=oc_store.store_id where oc_store_to_unit.unit_id='".$unit_id."' ";
	
        $query = $this->db->query($sql);
     // echo $query->row['name'];
        return $query->rows;
    }
    public function getcompany()
    {
        $sql = "SELECT  * FROM " . DB_PREFIX . "company";
	
        $query = $this->db->query($sql);
      
        return $query->rows; 
    }
     public function getfunctype()
    {
        $sql = "SELECT  * FROM " . DB_PREFIX . "functype  where isactive='1'";
	
        $query = $this->db->query($sql);
      
        return $query->rows; 
    }


}
