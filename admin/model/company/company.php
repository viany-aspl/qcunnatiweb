<?php

class ModelCompanyCompany extends Model {

    public function addcompany($data) {
      
                 $sql2="insert into  oc_company set company_name='".$data["company_name"]."'";
		$query2 = $this->db->query($sql2);
    }


    public function getcompany($data = array()) {

        $sql = "SELECT  * FROM " . DB_PREFIX . "company ";
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
        // echo $sql;exit;
        return $query->rows;
    }
    
    public function getcompanyName($data = array()) 
    {

        $sql = "SELECT  * FROM " . DB_PREFIX . "company ";
	
        $query = $this->db->query($sql);
      
        return $query->rows;
    }

    public function getTotalcompany($data = array()) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "company");

        return $query->row['total'];
    }
   
	public function UpdateCompany($data = array())
{

$sql2="update oc_company set company_name='".$data["company_name"]."' where company_id='".$data["id"]."'";
$query2 = $this->db->query($sql2);
}

public function getCompanyValue($data = array())
{

$sql = "SELECT * FROM " . DB_PREFIX . "company where company_id='".$data["id"]."'";

$query = $this->db->query($sql);

return $query->rows;
}

}
