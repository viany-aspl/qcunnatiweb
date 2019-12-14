<?php
date_default_timezone_set("Asia/Calcutta");
class ModelAseAse extends Model {
public function getasestores($data)
{
$query = $this->db->query("SELECT oc_store.name as store_name,oc_ase_to_store.store_id as store_id FROM oc_ase_to_store left join oc_store on oc_ase_to_store.store_id=oc_store.store_id where oc_ase_to_store.user_id='".$data['user_id']."'  order by oc_store.name asc ");
return $query->rows;
} 

public function getVillageVisit($data = array()) {
	  $sql=" SELECT ovv.farmer_count,ocs.name as store_name,ocv.village_name as village_name,ocu.firstname as firstname,ocu.lastname as lastname,ovv.visit_date as visit_date,ovv.remarks as remarks FROM `oc_village_visit` as ovv join oc_store as ocs on ovv.store_id=ocs.store_id join oc_villages as ocv on ovv.village_id=ocv.SID join oc_user as ocu on ovv.ase_id=ocu.user_id   where ovv.store_id='".$data["filter_store"]."'   ";

if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20; 
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

                $log=new Log("ase-".date('Y-m-d').".log");
	  //$log->write($sql);
		$query = $this->db->query($sql);
                
		return $query->rows;
           
	}

public function getTotalVillageVisit($data = array()) {
	  $sql="select count(*) as total from ( SELECT ovv.farmer_count,ocs.name as store_name,ocv.village_name as village_name,ocu.firstname as firstname,ocu.lastname as lastname,ovv.visit_date as visit_date,ovv.remarks as remarks FROM `oc_village_visit` as ovv join oc_store as ocs on ovv.store_id=ocs.store_id join oc_villages as ocv on ovv.village_id=ocv.SID join oc_user as ocu on ovv.ase_id=ocu.user_id   where ovv.store_id='".$data["filter_store"]."'  ) as aa ";
                $log=new Log("ase-".date('Y-m-d').".log");
	  //$log->write($sql);
		$query = $this->db->query($sql);
                
		return $query->row["total"];
           
	}

public function village_visit_submit($data = array())
{
              $today=date('Y-m-d');
              $log=new Log("ase-".date('Y-m-d').".log");
              $sql1= "select SID from `oc_village_visit` where `village_id`='".$data["village_id"]."' and `farmer_count`='".$data["farmer_count"]."' and date(visit_date)='".$today."' ";
              $query1 = $this->db->query($sql1);
              $log->write($sql1);
	if($query1->row["SID"]!="")
              {
                            $log->write("already data for the same entry with SID -".$query1->row["SID"]);
		return $query1->row["SID"];
              }
              else
	{
              $sql="insert into  `oc_village_visit` set  `village_id`='".$data["village_id"]."',`farmer_count`='".$data["farmer_count"]."',`ase_id`='".$data["ase_id"]."',`remarks`='".$data["remarks"]."',`store_id`='".$data["store_id"]."',`farmerMobile`='".$data['farmerMobile']."',`farmerName`='".$data['farmerName']."'   ";
	
	$log->write($sql);
	$query = $this->db->query($sql);
              $insertid=$this->db->getLastId();
              $log->write("insertid - ".$insertid);
              return $insertid; 
	}
}
public function create_village($data=array())
{
$sql="insert into  `oc_villages` set  `village_name`='".$data["village_name"]."',`district`='".$data["district"]."',`pincode`='".$data["pincode"]."',`store_id`='".$data["filter_store"]."',`addedby`='".$data["logged_user"]."'   ";
	$log=new Log("ase-".date('Y-m-d').".log");
	$log->write($sql);
	$query = $this->db->query($sql);
              $insertid=$this->db->getLastId();
              $log->write("insertid - ".$insertid);
              return $insertid; 

}
public function getAllVillages($data = array()) {
	  $sql=" SELECT * from   `oc_villages`  where store_id='".$data["filter_store"]."' order by village_name asc limit 200   ";
                $log=new Log("ase-".date('Y-m-d').".log");
	  //$log->write($sql);
		$query = $this->db->query($sql);
                
		return $query->rows;
           
	}
public function getASE($data = array()) {
	
	$sql="SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id,oc_user.status,oc_store.name as name FROM oc_user left join oc_store on oc_store.store_id=oc_user.store_id where  oc_user.user_group_id='26' "; 
 
	if (!empty($data['filter_store'])) {
		$sql.=" and oc_user.store_id='".$data['filter_store']."' ";
	}
	if($data['filter_name']!='')
		{
			$sql.="  and concat(oc_user.firstname,' ',oc_user.lastname) like '%".$data['filter_name']."%' ";
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
		return $query->rows;
	}

	public function getTotalASE($data = array()) {
                $sql="select count(*) as total from (SELECT oc_user.firstname,oc_user.lastname,oc_user.username,oc_user.store_id FROM oc_user  where user_group_id='26' ";

	if (!empty($data['filter_store'])) {
		$sql.=" and oc_user.store_id='".$data['filter_store']."' ";
	}
	if($data['filter_name']!='')
		{
			$sql.="  and concat(oc_user.firstname,' ',oc_user.lastname) like '%".$data['filter_name']."%' ";
		}
	$sql.="  ) as bb ";
		$query = $this->db->query($sql);
                            //echo $sql; 
		return $query->row['total'];
	}
public function setpoint($logged_user,$lat,$lng) 
        {
            
              $sql="insert into  `oc_ase_location` set  `ase_id`='".$logged_user."',`latttitude`='".$lat."',`lontitude`='".$lng."'   ";
	$log=new Log("ase-".date('Y-m-d').".log");
	$log->write($sql);
	$query = $this->db->query($sql);
              $insertid=$this->db->getLastId();
              $log->write("insertid - ".$insertid);
              return $insertid; 
        }

public function getMyCustomers($data = array()) {
$sql = "SELECT oc_customer.customer_id,oc_customer.firstname,oc_customer.lastname,oc_customer.telephone,
oc_customer.date_added,oc_user.firstname as adfirstname,oc_user.lastname as adlastname
FROM shop.oc_customer join oc_user on oc_user.user_id=oc_customer.addedby ";

$implode = array();

if (!empty($data['filter_userid'])) {
$implode[] = "oc_customer.addedby = '" . $this->db->escape($data['filter_userid']) . "'";
}


if (!empty($data['filter_date_start'])) {
$implode[] = "DATE(oc_customer.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$implode[] = "DATE(oc_customer.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$implode[] = " oc_user.user_group_id='26' ";

if ($implode) {
$sql .= " WHERE " . implode(" AND ", $implode);
}

 $sql.=" order by oc_customer.date_added asc ";

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

public function getTotalMyCustomers($data = array()) {
$sql = "select count(*) as total from ( SELECT oc_customer.customer_id
FROM shop.oc_customer join oc_user on oc_user.user_id=oc_customer.addedby ";

$implode = array();

if (!empty($data['filter_userid'])) {
$implode[] = "oc_customer.addedby = '" . $this->db->escape($data['filter_userid']) . "'";
}


if (!empty($data['filter_date_start'])) {
$implode[] = "DATE(oc_customer.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$implode[] = "DATE(oc_customer.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}

$implode[] = " oc_user.user_group_id='26' ";

if ($implode) {
$sql .= " WHERE " . implode(" AND ", $implode);
}
$sql.= " ) as aa";
//echo $sql;
$query = $this->db->query($sql);

return $query->row["total"];
}

public function getUserid()
{
$query = $this->db->query("SELECT * FROM shop.oc_user where user_group_id='26' and status=1 order by oc_user.firstname asc ");//where Grroup id='12'
return $query->rows;
} 

public function getmyorders($data = array()) {
	  $sql=" SELECT oc_order_leads.*,oc_store.name as store_name from   `oc_order_leads`  left join oc_store on oc_order_leads.store_id=oc_store.store_id where oc_order_leads.user_id='".$data["ase_id"]."' ";
	  if($data['status']!="")
	  {
		 $sql.=" and oc_order_leads.order_status_id='".$data['status']."' ";  
	  }
	$sql.=" order by order_id desc  ";
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
	else
	{
		$sql.=" limit 20 ";
	}
                $log=new Log("ase-".date('Y-m-d').".log");
	  //$log->write($sql);
		$query = $this->db->query($sql);
                
		return $query->rows;
           
	}
public function getordersbookedcount($data = array(),$ase_id) {
		$sql = "select count(order_id) as totalbookedorder from oc_order_leads as o  ";

		$implode = array();
                $implode[] = " o.order_status_id='1' ";
                
                
               	$cnv_sql=" o.user_id in (select user_id from oc_user where user_group_id='26'  ";
		if (!empty($ase_id)) {
			$cnv_sql.= " and  oc_user.user_id = '" . $this->db->escape($ase_id) . "'";
		}

		$cnv_sql.=" ) ";
		$implode[] =$cnv_sql;

		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

                //$sql.=" limit 1 ";

		
               // echo $sql;
		$query = $this->db->query($sql);

		return $query->row["totalbookedorder"];
	}
        public function getordersconvertedcount($data = array(),$ase_id) {
		$sql = "select count(order_id) as totalbookedorder from oc_order_leads as o ";

		$implode = array();
                $implode[] = " o.order_status_id='5' ";
                //$implode[] = " o.user_id='".$ase_id."' ";
                
                 $cnv_sql=" o.user_id in (select user_id from oc_user where user_group_id='26'  ";
		if (!empty($ase_id)) {
			$cnv_sql.= " and  oc_user.user_id = '" . $this->db->escape($ase_id) . "'";
		}

		$cnv_sql.=" ) ";
		$implode[] =$cnv_sql;
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

                //$sql.=" limit 1 ";

		
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row["totalbookedorder"];
	}
        public function getcustomeraddedcount($data = array(),$ase_id) {
		$sql = "select count(customer_id) as totaladdedcustomer from oc_customer as oc  ";

		$implode = array();
                //$implode[] = " oc.addedby='".$ase_id."' ";
                //$implode[] = " oc.addedby in (select user_id from oc_user where user_group_id='26') ";
		//if (!empty($ase_id)) {
		//	$implode[] = "oc.addedby = '" . $this->db->escape($ase_id) . "'";
		//}

		$cnv_sql=" oc.addedby in (select user_id from oc_user where user_group_id='26'  ";
		if (!empty($ase_id)) {
			$cnv_sql.= " and oc_user.user_id = '" . $this->db->escape($ase_id) . "'";
		}

		$cnv_sql.=" ) ";
		$implode[] =$cnv_sql;



		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(oc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(oc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

                	//echo $sql;
		$query = $this->db->query($sql);

		return $query->row["totaladdedcustomer"];
	}
        public function getases($data = array())
        {
          $sql="SELECT user_id,firstname,lastname FROM shop.oc_user where user_group_id='26' and status=1 ";//where Grroup id='12'
                
		if (!empty($data['filter_userid'])) {
			$sql.= " and user_id = '" . $this->db->escape($data['filter_userid']) . "'";
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
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->rows;
 
        }
        public function getasesall($data = array())
        {
          $sql="SELECT count(user_id) as total FROM shop.oc_user where user_group_id='26' and status=1 ";//where Grroup id='12'
                
                if (!empty($data['filter_userid'])) {
			$sql.= " and user_id = '" . $this->db->escape($data['filter_userid']) . "'";
		}
		
		$query = $this->db->query($sql);

		return $query->row["total"];
 
        }
        public function getVillageVisitcount($data = array()) {
	  $sql=" SELECT ovv.farmer_count,ocs.name as store_name,ocv.village_name as village_name,ocu.firstname as firstname,ocu.lastname as lastname,ovv.visit_date as visit_date,ovv.remarks as remarks FROM `oc_village_visit` as ovv join oc_store as ocs on ovv.store_id=ocs.store_id join oc_villages as ocv on ovv.village_id=ocv.SID join oc_user as ocu on ovv.ase_id=ocu.user_id   where ovv.store_id!=''   ";

	$implode = array();
                $implode[] = " oc.addedby='".$ase_id."' ";
                //$implode[] = " oc.addedby in (select user_id from oc_user where user_group_id='26') ";
		if (!empty($data['filter_userid'])) {
			$implode[] = "oc.addedby = '" . $this->db->escape($data['filter_userid']) . "'";
		}

		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(oc.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(oc.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}


                $log=new Log("ase-".date('Y-m-d').".log");
	  //$log->write($sql);
echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
           
	}
	public function getorderdetails($data = array()) {
	  $sql=" SELECT o.telephone,o.date_added,o.order_id,o.total,concat(ou.firstname,' ',ou.lastname) as ase_name FROM `oc_order_leads` as o join oc_user as ou on ou.user_id=o.user_id  where  o.user_id in (select user_id from oc_user where user_group_id='26' and store_id='".$data["store_id"]."' ) and o.order_status_id='1'  ";
//o.store_id='".$data["store_id"]."' and  

if(!empty($data["order_id"]))
{
 $sql.=" and order_id='".$data["order_id"]."'  "; 
}
$sql.=" order by o.order_id desc ";
if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
 
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                $log=new Log("ase-".date('Y-m-d').".log");
	  $log->write($sql);
		$query = $this->db->query($sql);
                            //print_r($query->row);
		return $query->rows;
           
	}


public function get_village($data = array()) {
$sql=" SELECT UPPER(vill.village_name) as 'village_name',vill.district,vill.pincode,store.name FROM shop.oc_villages as vill
LEFT JOIN oc_store as store on store.store_id=vill.store_id ";
$sql .=" where vill.village_name <> '' ";
if (!empty($data['filter_store']) ) {
$sql .="  and vill.store_id ='".$data['filter_store']."' ";

}
$sql .= " order by vill.village_name ";
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
$log=new Log("ase-".date('Y-m-d').".log");
//$log->write($sql);
$query = $this->db->query($sql);

return $query->rows;

}


public function get_Totalvillage($data = array()) {
$sql="select COUNT(*) as total from ( SELECT vill.village_name,vill.district,vill.pincode,store.name FROM shop.oc_villages as vill
LEFT JOIN oc_store as store on store.store_id=vill.store_id ";
$sql .=" where vill.village_name <> '' ";
if (!empty($data['filter_store']) ) {
$sql .=" and vill.store_id ='".$data['filter_store']."' ";

}
$sql .=") as a";

$log=new Log("ase-".date('Y-m-d').".log");
//$log->write($sql);
$query = $this->db->query($sql);

return $query->row['total'];

}


}