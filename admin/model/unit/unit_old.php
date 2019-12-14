<?php

class ModelUnitUnit extends Model {

    public function addunit($data) {
      
                 $sql2="insert into  oc_unit set unit_name='".$data["unit_name"]."',unit_id='".$data["unit_id"]."',company_id='".$data['filter_company']."'  ";
		$query2 = $this->db->query($sql2);
    }


    public function getunit($data = array()) {

        $sql = "SELECT  * FROM " . DB_PREFIX . "unit ";

	if(!empty($data['filter_company']))
	{
		$sql.=" where oc_unit.company_id='".$data['filter_company']."' ";
	}
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

    public function getTotalunit($data = array()) {
	$sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "unit";
	if(!empty($data['filter_company']))
	{
		$sql.=" where oc_unit.company_id='".$data['filter_company']."' ";
	}
        	$query = $this->db->query($sql);

        	return $query->row['total'];
    }
public function getstorebyunitid($unit_id)
{
$sql = "SELECT oc_store.* FROM " . DB_PREFIX . "store_to_unit left join oc_store on oc_store_to_unit.store_id=oc_store.store_id where oc_store_to_unit.unit_id='".$unit_id."' ";

$query = $this->db->query($sql);
// echo $query->row['name'];
return $query->rows;
}
public function getunitsbycompany($company_id)
{
$sql = "SELECT oc_unit.* FROM " . DB_PREFIX . "unit  where oc_unit.company_id='".$company_id."' ";

$query = $this->db->query($sql);
// echo $query->row['name'];
return $query->rows;
}
   public function getCompanies() {

        $sql = "SELECT  * FROM " . DB_PREFIX . "company where is_active='1' ";
	 
        $query = $this->db->query($sql);
       
        return $query->rows;
    }

 public function getUnitValue($data = array())
{
$sql = "SELECT * FROM " . DB_PREFIX . "unit where sid='".$data['id']."'";
$query = $this->db->query($sql);
// echo $sql;//exit;
return $query->rows;
}
public function UpdateUnit($data) {

$sql2="update oc_unit set unit_name='".$data["unit_name"]."',unit_id='".$data["unit_id"]."',company_id='".$data['filter_company']."' where sid='".$data["id"]."' ";
$query2 = $this->db->query($sql2);
}  
}
