<?php

class ModelUnitUnit extends Model {


public function getGrowerIdByCard($card_number)
	{
		$sql = "SELECT  GROWER_ID,UNIT_ID FROM oc_card_issue where oc_card_issue.CARD_SERIAL_NUMBER='".$card_number."' "; 
	
        		$query = $this->db->query($sql);
        		$log=new Log("Card-Send-otp-".date('Y-m-d').".log");
		
		$log->write($sql); 
        		return $query->row;
	}
	public function getUnitByGrowerID($GROWER_ID)
	{
		$sql = "SELECT UNIT_ID FROM oc_card_issue where oc_card_issue.GROWER_ID='".$GROWER_ID."' limit 1 "; 
	
        		$query = $this->db->query($sql);
        		$log=new Log("Card-getDatabyGrowerId-".date('Y-m-d').".log");
		
				$log->write($sql); 
        		return $query->row['UNIT_ID'];
	}
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
	public function getunitidbyStoreid($store_id,$type = '')
	{
		
		$sql = "SELECT oc_store_to_unit.* FROM " . DB_PREFIX . "store_to_unit left join oc_store on oc_store_to_unit.store_id=oc_store.store_id where oc_store_to_unit.store_id=".(int)$store_id." ";
		$query = $this->db->query($sql);
		if($type=='email')
		{
			$log=new Log("email-create_bill_bcml-".date('Y-m-d').".log");
			$log->write('getunitidbyStoreid called in model');
			$log->write($query);
			
			$log->write($sql);	
			$log->write($query->rows);
		}
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
	public function getCompanies() 
	{
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
	public function UpdateUnit($data) 
	{

		$sql2="update oc_unit set unit_name='".$data["unit_name"]."',unit_id='".$data["unit_id"]."',company_id='".$data['filter_company']."' where sid='".$data["id"]."' ";
		$query2 = $this->db->query($sql2);
	}  
	public function getUnitByID($id,$type='')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "unit  ou left join oc_company occ on occ.company_id= ou.company_id where ou.unit_id='".$id."'";
		$query = $this->db->query($sql);
		if($type=='email')
		{
			$log=new Log("email-create_bill_bcml-".date('Y-m-d').".log");
			$log->write($sql);	
		}
		return $query->row;
	}
	public function getUnitByComapany_UnitID($unitid,$companyid)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "unit  ou left join oc_company occ on occ.company_id= ou.company_id where ou.unit_id='".$unitid."' and ou.company_id='".$companyid."' ";
		$query = $this->db->query($sql);
		$log=new Log("order-".date('Y-m-d').".log");
		$log->write($sql);
		$log->write($query->row);
		return $query->row;
	}  

}
