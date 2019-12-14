<?php
date_default_timezone_set("Asia/Calcutta");
class ModelAttendenceAttendence extends Model {
	
public function gettoday_attendence($data = array()) {
	

	  //if($data['attendence_type']=="in")
	  //{
	  $sql=" SELECT * from oc_attendence  where oc_attendence.user_id='".$data["user_id"]."' and date(`in_time`)='".date('Y-m-d')."' limit 1  ";
	  //}
	  /*
	  if($data['attendence_type']=="out")
	  {
	  $sql=" SELECT * from oc_attendence  where oc_attendence.user_id='".$data["user_id"]."' and date(`out_time`)='".date('Y-m-d')."'  limit 1  ";
	  }
	  */
      $log=new Log("attendence-".date('Y-m-d').".log");
	$log->write($sql);
	  $query = $this->db->query($sql);
	  //return $rows=count($query->rows);
	  
      return $query->rows;
           
	}


public function insert_attendence($data = array())
{
              $today=date('Y-m-d H:i:s');
              $log=new Log("attendence-".date('Y-m-d').".log");
              if($data['attendence_type']=="in")
			  {
              $sql="insert into  `oc_attendence` set  `user_id`='".$data["user_id"]."',`location_lat`='".$data["location_lat"]."',`location_long`='".$data["location_long"]."'";
			  
			  $sql.=",`in_time`='".$today."'";
			  }
			  if($data['attendence_type']=="out")
			  {
			  $sql="update `oc_attendence` set  `location_lat`='".$data["location_lat"]."',`location_long`='".$data["location_long"]."',`out_time`='".$today."' where `user_id`='".$data["user_id"]."' and `sid`='".$attendence_id."' ";
			  
			  }
			  $log->write($sql);
			  $query = $this->db->query($sql);
              $insertid=$this->db->getLastId();
              $log->write("insertid - ".$insertid);
              return $insertid; 
	}

public function update_attendence($data = array(),$attendence_id)
{
              $today=date('Y-m-d H:i:s');
              $log=new Log("attendence-".date('Y-m-d').".log");
              
              $sql="update `oc_attendence` set  `location_lat_out`='".$data["location_lat"]."',`location_long_out`='".$data["location_long"]."',`out_time`='".$today."' where `user_id`='".$data["user_id"]."' and `sid`='".$attendence_id."' ";
			  
			  $log->write($sql);
			  $query = $this->db->query($sql);
              
              $log->write("updated ");
              return $attendence_id; 
	
}
public function getattendence($data = array()) {
	  
	  $sql=" SELECT oc_attendence.user_id,oc_attendence.in_time,oc_attendence.out_time,oc_user.firstname as firstname,oc_user.lastname as lastname,
		(select oc_store.name as store_name from oc_store where oc_store.store_id=oc_user.store_id) as store_name,concat(location_lat,'-',location_long) as location_in,concat(location_lat_out,'-',location_long_out) as location_out
	  from oc_attendence  
	  left join oc_user on oc_attendence.user_id=oc_user.user_id
	  where oc_attendence.user_id!='' ";
	  if(!empty($data['filter_userid']))
	  {
		$sql.=" and oc_attendence.user_id='".$data["filter_userid"]."'  ";
	  }
	  if(!empty($data['filter_date_start']))
	  {
		$sql.=" and date(`in_time`)>='".$data['filter_date_start']."'  ";
	  }
	  if(!empty($data['filter_date_end']))
	  {
		$sql.=" and date(`in_time`)<='".$data['filter_date_end']."'  ";
	  }
	  $sql.=" order by  oc_attendence.sid desc ";
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
	
      $log=new Log("attendence-".date('Y-m-d').".log");
	  //$log->write($sql);
	  $query = $this->db->query($sql);
	  //return $rows=count($query->rows);
	  
      return $query->rows;
           
	}
public function getTotalattendence($data = array()) {
	  
	  $sql=" select count(*) as total from (SELECT oc_attendence.user_id,oc_attendence.in_time,oc_attendence.out_time,oc_user.firstname,oc_user.lastname,
		(select oc_store.name as store_name from oc_store where oc_store.store_id=oc_user.store_id) as store_name
	  from oc_attendence  
	  left join oc_user on oc_attendence.user_id=oc_user.user_id
	  where oc_attendence.user_id!='' ";
	  if(!empty($data['filter_userid']))
	  {
		$sql.=" and oc_attendence.user_id='".$data["filter_userid"]."'  ";
	  }
	  if(!empty($data['filter_date_start']))
	  {
		$sql.=" and date(`in_time`)>='".$data['filter_date_start']."'  ";
	  }
	  if(!empty($data['filter_date_end']))
	  {
		$sql.=" and date(`in_time`)<='".$data['filter_date_end']."'  ";
	  }
	  $sql.=" ) as aa ";
      $log=new Log("attendence-".date('Y-m-d').".log");
	  //$log->write($sql);
	  $query = $this->db->query($sql);
	  //return $rows=count($query->rows);
	  
      return $query->row['total'];
           
	}
public function getUsers($data = array()) {
	  
	  $sql=" SELECT * from oc_user  where oc_user.user_id!='1' and status='1' ";
	  if(!empty($data['filter_username']))
	  {
		$sql.=" and oc_user.firstname like '%".$data["filter_username"]."%'  ";
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
      $log=new Log("attendence-".date('Y-m-d').".log");
	  //$log->write($sql);
	  $query = $this->db->query($sql);
	  //return $rows=count($query->rows);
	  
      return $query->rows;
           
	}

	public function in_report($data = array()) {
	  
	  $sql=" 
		select * from ( SELECT oc_attendence.sid,oc_attendence.user_id,oc_attendence.in_time as time,oc_attendence.location_lat as lattitude,location_long as longtitude,'in' as in_out,concat(oc_user.firstname,' ',oc_user.lastname) as username FROM `oc_attendence` left join oc_user on oc_attendence.user_id=oc_user.user_id where oc_attendence.user_id!='' ";

if(!empty($data['filter_userid']))
	  {
		$sql.=" and oc_attendence.user_id='".$data["filter_userid"]."'  ";
	  }
	  if(!empty($data['filter_date_start']))
	  {
		$sql.=" and date(`in_time`)>='".$data['filter_date_start']."'  ";
	  }
	  if(!empty($data['filter_date_end']))
	  {
		$sql.=" and date(`in_time`)<='".$data['filter_date_end']."'  ";
	  }

	$sql.=" UNION ALL SELECT oc_attendence.sid,oc_attendence.user_id,oc_attendence.out_time as time,oc_attendence.location_lat_out as lattitude,location_long_out as longtitude,'out' as in_out,concat(oc_user.firstname,' ',oc_user.lastname) as username FROM `oc_attendence` left join oc_user on oc_attendence.user_id=oc_user.user_id where oc_attendence.user_id!='' and location_long_out!='' ";

if(!empty($data['filter_userid']))
	  {
		$sql.=" and oc_attendence.user_id='".$data["filter_userid"]."'  ";
	  }
	  if(!empty($data['filter_date_start']))
	  {
		$sql.=" and date(`in_time`)>='".$data['filter_date_start']."'  ";
	  }
	  if(!empty($data['filter_date_end']))
	  {
		$sql.=" and date(`in_time`)<='".$data['filter_date_end']."'  ";
	  }

		$sql.=" ) as aa ";
	  
	 
	  $query = $this->db->query($sql);
	 
	  
                return $query->rows; 
           
	}
	public function out_report($data = array()) {
	  
	  $sql=" SELECT  oc_attendence.sid,oc_attendence.user_id,oc_attendence.out_time as time,oc_attendence.location_lat as lattitude,location_long as longtitude,'out' as in_out,concat(oc_user.firstname,' ',oc_user.lastname) as username FROM `oc_attendence` left join oc_user on oc_attendence.user_id=oc_user.user_id where oc_attendence.user_id!='' and location_long_out!='' ";
	  
	  if(!empty($data['filter_userid']))
	  {
		$sql.=" and oc_attendence.user_id='".$data["filter_userid"]."'  ";
	  }
	  if(!empty($data['filter_date_start']))
	  {
		$sql.=" and date(`in_time`)>='".$data['filter_date_start']."'  ";
	  }
	  if(!empty($data['filter_date_end']))
	  {
		$sql.=" and date(`in_time`)<='".$data['filter_date_end']."'  ";
	  }
	  $query = $this->db->query($sql); 
	 
	  
                return $query->rows;
           
	}
}