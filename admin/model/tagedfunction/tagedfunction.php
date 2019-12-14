<?php

class ModelTagedfunctionTagedfunction extends Model {

    public function addtaggedfunction($data) {
      
                 $sql2="insert into  oc_company_tagged_func set funname='".$data["f_name"]."',functypeid='".$data["function_name"]."',companyid='".$data["company_name"]."',isactive='1' on DUPLICATE KEY update funname='".$data["f_name"]."',isactive='1' ";
		$query2 = $this->db->query($sql2); 
    }


    public function gettagedfunction($data = array()) {

        $sql = "SELECT otf.funname,oc.company_name as companyid ,of.functypename as functypeid   FROM oc_company_tagged_func as otf
left join oc_company as oc on oc.company_id=otf.companyid
left join oc_functype as of on of.functypeid=otf.functypeid where otf.isactive='1'";
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
        //echo $sql;//exit;
        return $query->rows;
    }
    
    public function getUnitName($data = array()) 
    {

        $sql = "SELECT  * FROM " . DB_PREFIX . "unit ";
	
        $query = $this->db->query($sql);
      
        return $query->rows;
    }

    public function getTotaltagedfunction($data = array()) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM (SELECT otf.funname,oc.company_name as companyid ,of.functypename as functypeid   FROM oc_company_tagged_func as otf
left join oc_company as oc on oc.company_id=otf.companyid
left join oc_functype as of on of.functypeid=otf.functypeid where otf.isactive='1') as aa");

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
        $sql = "SELECT  * FROM " . DB_PREFIX . "functype";
	
        $query = $this->db->query($sql);
      
        return $query->rows; 
    }


}
