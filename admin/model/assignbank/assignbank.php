<?php

class ModelAssignbankAssignbank extends Model {

    public function addBank($data) {
      
                $sql2="insert into  oc_bank set bank='".$data["bank_name"]."',bank_account_name='".$data['bank_account_name']."',bank_account_number='".$data['bank_account_number']."',bank_account_type='".$data['bank_account_type']."',bank_ifsc_code='".$data['bank_ifsc_code']."',bank_branch='".$data['bank_branch']."',IsActive='1'  ";
		$query2 = $this->db->query($sql2);
                $lastid = $this->db->getLastId($query2);
                $count  = count($data["filter_company"]);
                foreach($data["filter_company"] as $company_id)
                {
                    $sql2="insert into  oc_bank_to_company set bank_id='".$lastid."',company_id='".$company_id."'";
		    $query2 = $this->db->query($sql2);
                    
                }
              
    }


    public function getBank($data = array()) {

        $sql = "SELECT  * FROM " . DB_PREFIX . "bank where oc_bank.IsActive!=''";

	if(!empty($data['filter_bank']))
	{
		$sql.=" and oc_bank.bank like '%".$data['filter_bank']."%' ";
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
        //echo $sql;
	//exit;
        return $query->rows;
    }
    public function getAssignbankValue($data = array())
    {
    $sql = "SELECT  * FROM " . DB_PREFIX . "bank where bank_id='".$data['id']."' limit 1 ";		 
        $query = $this->db->query($sql);
      //  echo $sql;//exit;
        return $query->rows;
    }
    public function UpdateAssignBank($data) {
               
        echo  $sql2="update  oc_bank set bank='".$data["bank_name"]."',bank_account_name='".$data['bank_account_name']."',bank_account_number='".$data['bank_account_number']."',bank_account_type='".$data['bank_account_type']."',bank_ifsc_code='".$data['bank_ifsc_code']."',bank_branch='".$data['bank_branch']."',IsActive='".$data['IsActive']."'  where bank_id='".$data["id"]."'";


	    $query2 = $this->db->query($sql2);
            //exit;
            if(!empty($data["filter_company"][0]))
            {
            $sql4="delete from  oc_bank_to_company where  bank_id='".$data["id"]."'";
	    $query4 = $this->db->query($sql4);
            
            foreach($data["filter_company"] as $company)
            {
                $sql3="insert into  oc_bank_to_company set bank_id='".$data["id"]."',company_id='".$company."'  ";
		$query3 = $this->db->query($sql3);
            }
            }
    }
   
    public function getTotalBank($data = array()) {
	$sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bank where oc_bank.IsActive!='' ";
	if(!empty($data['filter_bank']))
	{
		$sql.=" and oc_bank.bank like '%".$data['filter_bank']."%' ";
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
public function getAssigncompanies($bank_id)
{
$sql = "SELECT oc_bank_to_company.company_id FROM oc_bank_to_company  where oc_bank_to_company.bank_id='".$bank_id."' ";

$query = $this->db->query($sql);
// echo $query->row['name'];
return $query->rows;
}
   public function getCompanies() {

        $sql = "SELECT  * FROM " . DB_PREFIX . "company where is_active='1' ";
	 
        $query = $this->db->query($sql);
       
        return $query->rows;
    }

   
}
