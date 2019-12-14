<?php
class ModelFarmerrequestFarmerrequest extends Model 
{
	public function reprintcardupdatestatus($data)
	{
		$log=new Log("reprintcard-".date('Y-m-d').".log");		
        $sql="update oc_card_reprint_request SET STATUS='1' where CARD_SERIAL_NUMBER='".$data['CARD_SERIAL_NUMBER']."' and grower_id='".$data['grower_id']."' ";
        $query = $this->db->query($sql);
        $log->write($sql);
	}
	public function reprintcardrequest($data)
	{
		$log=new Log("reprintcard-".date('Y-m-d').".log");	
		$sql1="select * from oc_card_reprint_request where STATUS='0' and CARD_SERIAL_NUMBER='".$data['CARD_SERIAL_NUMBER']."' and grower_id='".$data['grower_id']."' ";
        $log->write($sql1);
		$log->write($query1->num_rows);
		$query1=$this->db->query($sql1);
		if($query1->num_rows>0)
		{
			return '0';
		}
		else
		{
			$sql="insert into oc_card_reprint_request SET GROWER_NAME='".$data['GROWER_NAME']."',FTH_HUS_NAME='".$data['FTH_HUS_NAME']."',VILLAGE_NAME='".$data['VILLAGE_NAME']."',CARD_SERIAL_NUMBER='".$data['CARD_SERIAL_NUMBER']."',grower_id='".$data['grower_id']."',unit_id='".$data['unit_id']."',user_id='".$data['user_id']."',store_id='".$data['store_id']."',ip='".$data['ip']."',CREATE_DATE = NOW() ";
			$query = $this->db->query($sql);
			$log->write($sql);
			return '1';
		}
	}
	public function getreprint_requestlist($data)
	{
		$log=new Log("reprintcard-".date('Y-m-d').".log");	
		$sql="SELECT oc_card_reprint_request.*,ou.unit_name,concat(oc_user.firstname,' ',oc_user.lastname) as SUBUSER_NAME from  oc_card_reprint_request  left join oc_unit ou on ou.unit_id= oc_card_reprint_request.unit_id  
		left join oc_user on oc_card_reprint_request.user_id=oc_user.user_id
		where grower_id!='' ";
        $log->write($sql);
		if($data['filter_unit']!="")
		{
			$sql.=" and oc_card_reprint_request.unit_id = '" .$data['filter_unit']."' ";
		}
		if($data['filter_subuser']!="")
		{
			$sql.=" and oc_card_reprint_request.user_id = '" .$data['filter_subuser']."' ";
		}
		if($data['filter_growerid']!="")
		{
			$sql.="and oc_card_reprint_request.grower_id = '" .$data['filter_growerid']."' ";
		}
        if($data['filter_date_start']!="")
		{
			$sql.=" and date(oc_card_reprint_request.CREATE_DATE)>= '" .$data['filter_date_start']."' ";
		}
        if($data['filter_date_end']!="")
		{
			$sql.=" and date(oc_card_reprint_request.CREATE_DATE)<= '" .$data['filter_date_end']."' ";
		}
		if($data['filter_status']!="")
		{
			if($data['filter_status']=='2')
			{
				$sql.="and oc_card_reprint_request.STATUS = '0' ";
			}
			if($data['filter_status']=='1')
			{
				$sql.="and oc_card_reprint_request.STATUS = '1' ";
			}
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
		$log->write($sql);
        $query = $this->db->query($sql);

		return $query->rows;
	}
	public function getreprint_requestlist_Total($data)
	{
		$log=new Log("reprintcard-".date('Y-m-d').".log");	
		$sql="SELECT count(*) as total from  oc_card_reprint_request where grower_id!='' ";
        
		if($data['filter_unit']!="")
		{
			$sql.=" and oc_card_reprint_request.unit_id = '" .$data['filter_unit']."' ";
		}
		if($data['filter_subuser']!="")
		{
			$sql.=" and oc_card_reprint_request.user_id = '" .$data['filter_subuser']."' ";
		}
		if($data['filter_growerid']!="")
		{
			$sql.="and oc_card_reprint_request.grower_id = '" .$data['filter_growerid']."' ";
		}
        if($data['filter_date_start']!="")
		{
			$sql.=" and date(oc_card_reprint_request.CREATE_DATE)>= '" .$data['filter_date_start']."' ";
		}
        if($data['village_id']!="")
		{
			$sql.=" and date(oc_card_reprint_request.CREATE_DATE)<= '" .$data['filter_date_end']."' ";
		}
		if($data['filter_status']!="")
		{
			if($data['filter_status']=='2')
			{
				$sql.="and oc_card_reprint_request.STATUS = '0' ";
			}
			if($data['filter_status']=='1')
			{
				$sql.="and oc_card_reprint_request.STATUS = '1' ";
			}
		}
        $query = $this->db->query($sql);

		return $query->row['total'];
	}
	        
        public function addCarddetail($data,$StatusId,$StatusName) {
            $log=new Log("Card-add-".date('Y-m-d').".log"); 
            
           // insert into oc_card_issue            
            $sql="INSERT INTO  oc_card_issue SET COMPANY_ID='".$this->db->escape($data['COMPANY_ID'])."',VILLAGE_NAME='".$this->db->escape($data['village_name'])."',VILLAGE_ID='".$this->db->escape($data['village'])."', GROWER_ID = '" . $this->db->escape($data['growerid']) . "',FTH_HUS_NAME='".$data['fathername']."', GROWER_NAME = '" . $this->db->escape($data['fname']) . "', MOB = '" . $this->db->escape($data['farmermob']) . "', UNIT_ID = '" .$data['unitno'] . "',CARD_STATUS='".$StatusId."',CARD_STATUS_DESC='".$StatusName."',CREATE_DATE = NOW()";
            $query = $this->db->query($sql);
            $card_id = $this->db->getLastId();
            $log->write($sql);
          // insert into oc_card_lcm_history
            $sql2="INSERT INTO  `oc_card_lcm_history` SET  GROWER_ID = '" . $this->db->escape($data['growerid']) . "',CARD_STATUS_ID = '" . $StatusId . "', CARD_STATUS_NAME = '" .$StatusName . "', CARD_ISSUE_SID = '" .$card_id . "',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
          //update into cn_ryot_mst            
//            $sql3="UPDATE   cn_ryot_mst SET  UNIT_CODE = '" .$data['unitno'] . "', VILLAGE_CODE = '" .$data['village'] . "', RYOT_NAME = '" .$data['fname'] . "', FTH_HUS_NAME = '" .$data['fathername'] . "'  where GROWER_CODE = '" . $this->db->escape($data['growerid']). "'";
//            $query3 = $this->db->query($sql3);
//            $log->write($sql3);
        }
        public function reissuedCard($data,$StatusId,$StatusName) {
            $log=new Log("Card-".date('Y-m-d').".log");  
              // insert into oc_card_issue 
            $sql2="INSERT INTO  `oc_card_lcm_history` SET CARD_STATUS_ID = '" . $StatusId . "', CARD_STATUS_NAME = '" .$StatusName . "', CARD_ISSUE_SID = '" .$data['card_id'] . "', OLD_CARD_NO = '" .$data['cardserialno'] . "', CARD_SERIAL_NO = '" .$data['cardserialno'] . "', DATE = '" .date('Y-m-d'). "',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
            
             
            // update into oc_card_lcm_history
            $sql="UPDATE oc_card_issue SET CARD_STATUS='".$StatusId."',CARD_STATUS_DESC='".$StatusName."',CARD_SERIAL_NUMBER=' ' where SID='".$data['card_id']."'";
            $query = $this->db->query($sql);

            $log->write($sql);          
            
          
        }
        
       public function  getdetail($data)
       {      $log=new Log("Card-".date('Y-m-d').".log");   
           $sql="SELECT *,ou.unit_name from  oc_card_issue  left join oc_unit ou on ou.unit_id= oc_card_issue.unit_id  where GROWER_ID!='' ";
           if($data['grower_id']!="")
		{
			$sql.="and   GROWER_ID = '" .$data['grower_id']."' ";
		}
                if($data['mobile']!="")
		{
			$sql.=" and MOB = '" .$data['mobile']."' ";
		}
           if($data['village_id']!="")
{
$sql.=" and VILLAGE_ID = '" .$data['village_id']."' ";
}

           $query = $this->db->query($sql);

		return $query->rows;
       }
       
       public function  getreviwmodaldtl($growerid)
       {       $log=new Log("Card-Review-".date('Y-m-d').".log");  
           $sql="SELECT *,ou.UNIT_NAME from  oc_card_issue left join oc_unit ou on ou.unit_id= oc_card_issue.unit_id where GROWER_ID = '" .$growerid."' ";
                  $log->write($sql);  
           $query = $this->db->query($sql);

		return $query->rows;
       }
       
       
        public function  getdetail2($data)
       {        $log=new Log("Card-".date('Y-m-d').".log"); 
           $sql="SELECT * from  cn_ryot_mst  where GROWER_CODE!='' ";
           if($data['grower_id']!="")
		{
			$sql.="and   GROWER_CODE = '" .$data['grower_id']."' ";
		}
                if($data['mobile']!="")
		{
			$sql.=" and MOB = '" .$data['mobile']."' ";
		}
           
           $query = $this->db->query($sql);

		return $query->rows;
       }
       public function  UpdateBolcked($data,$StatusId,$StatusName)
       {
           $log=new Log("Card-".date('Y-m-d').".log"); 
           // insert into oc_card_issue            
            $sql="UPDATE   oc_card_issue SET CARD_STATUS='".$StatusId."',CARD_STATUS_DESC='".$StatusName."',CREATE_DATE = NOW() where GROWER_ID = '" . $this->db->escape($data['grower_id']) . "'";
            $query = $this->db->query($sql);
            $retval =$this->db->countAffected();
            
            
            $log->write($sql);
          // insert into oc_card_lcm_history
            $sql2="INSERT INTO  `oc_card_lcm_history` SET GROWER_ID='".$this->db->escape($data['grower_id'])."',CARD_STATUS_ID = '" . $StatusId . "', CARD_STATUS_NAME = '" .$StatusName . "', CARD_ISSUE_SID = '" .$card_id . "',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
            return $retval;
           
       }
       
        public function  Updaterejectstatusremove($data,$StatusId,$StatusName)
       {
           $log=new Log("Card-".date('Y-m-d').".log"); 
           // insert into oc_card_issue            
            $sql="UPDATE   oc_card_issue SET CARD_STATUS='".$StatusId."',CARD_STATUS_DESC='".$StatusName."',CREATE_DATE = NOW() where GROWER_ID = '" . $this->db->escape($data['grower_id']) . "'";
            $query = $this->db->query($sql);
            $retval =$this->db->countAffected();
            $log->write($sql);
            $sqlsel="select SID from oc_card_issue where GROWER_ID = '" . $this->db->escape($data['grower_id'])."'";
            $query3 = $this->db->query($sqlsel);
            $SID=$query3->row['SID'];
            
            $log->write($sqlsel);
          // insert into oc_card_lcm_history
            $sql2="INSERT INTO  `oc_card_lcm_history` SET GROWER_ID='".$this->db->escape($data['grower_id'])."',CARD_STATUS_ID = '" . $StatusId . "', CARD_STATUS_NAME = '" .$StatusName . "', CARD_ISSUE_SID = '" .$SID . "',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
            return $retval;
           
       }
       //Review Dtl
       public function getreviewdtl($data = array())
       { 
	   //print_r($data);
	   $log=new Log("Card-".date('Y-m-d').".log"); 
              $sql="select oci.VILLAGE_NAME,oci.GROWER_ID,oci.GROWER_NAME,oci.UNIT_ID,oci.SID,oc.unit_name from oc_card_issue as oci left join oc_unit as oc on oc.unit_id=oci.UNIT_ID where CARD_STATUS in (1,12)";
              if($data['filter_growerid']!="")
		{
			$sql.=" and GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
		if($data['filter_company']!="")
 {
 $sql.=" and oci.COMPANY_ID = '".$data['filter_company']."' ";
 }
 	if($data['filter_village']!="")
 {
 $sql.=" and oci.VILLAGE_ID = '".$data['filter_village']."' ";
 }
 
 if($data['filter_unit']!="")
 {
 $sql.=" and oci.UNIT_ID = '".$data['filter_unit']."' ";
 }
	
		//echo $sql;
              $query = $this->db->query($sql);
                
	      return $query->rows;
       }
        public function getreviewdtlToatal($data = array())
       { $log=new Log("Card-".date('Y-m-d').".log"); 
               $sql="select COUNT(*) as total from oc_card_issue where CARD_STATUS in (1,12)";
                if($data['filter_growerid']!="")
		{
			$sql.=" and GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
              $query = $this->db->query($sql);
                
	      return $query->row['total'];
       }
       public function updateReviewStatus($gid,$StatusId,$StatusName,$card_serail_number)
       {
	   $log=new Log("Card-".date('Y-m-d').".log"); 
           $sql="UPDATE   oc_card_issue SET CARD_STATUS='".$StatusId."',CARD_STATUS_DESC='".$StatusName."',CARD_SERIAL_NUMBER='".$card_serail_number."',CREATE_DATE = NOW() where GROWER_ID = '" . $gid. "'";
            $query = $this->db->query($sql);
            
            $sqlsel="select SID from oc_card_issue where GROWER_ID = '" . $gid. "'";
            $query3 = $this->db->query($sqlsel);
            $SID=$query3->row['SID'];
            
             $sql2="INSERT INTO  `oc_card_lcm_history` SET GROWER_ID='".$gid."',CARD_STATUS_ID = '" . $StatusId . "', CARD_STATUS_NAME = '" .$StatusName . "', CARD_ISSUE_SID = '".$SID."',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
            //echo "here";
            $sql2="INSERT into  oc_card_serial_no SET CARD_NUMBER='".$card_serail_number."',STATUS='".$StatusId."',CARD_ISSUE_SID='".$SID."'";
			try{
            $query2 = $this->db->query($sql2);
			}
			catch(Exception $e)
			{
			//echo $e;
			$log->write($e);
			}
            
       }
       public function updateRejectStatus($gid,$StatusId,$StatusName)
       {
	       $log=new Log("Card-".date('Y-m-d').".log"); 
           $sql="UPDATE   oc_card_issue SET CARD_STATUS='".$StatusId."',CARD_STATUS_DESC='".$StatusName."',CREATE_DATE = NOW() where GROWER_ID = '" . $gid. "'";
            $query = $this->db->query($sql);
             $log->write($sql);
             $sqlsel="select SID from oc_card_issue where GROWER_ID = '" . $gid. "'";
            $query3 = $this->db->query($sqlsel);
            $SID=$query3->row['SID'];
            $log->write($sqlsel);
			$log->write($query3->row);
             $sql2="INSERT INTO  `oc_card_lcm_history` GROWER_ID='".$gid."', SET CARD_STATUS_ID = '" . $data['StatusId'] . "', CARD_STATUS_NAME = '" .$data['StatusName'] . "', CARD_ISSUE_SID = '".$data['SID']."',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
       }
       
       //Approve Dtl
        public function getapprovedtl($data = array())
       { 
	    $log=new Log("Card-".date('Y-m-d').".log"); 
              $sql="select oci.VILLAGE_NAME,oci.GROWER_ID,oci.GROWER_NAME,oci.UNIT_ID,oci.CARD_SERIAL_NUMBER,oc.unit_name from oc_card_issue as oci left join oc_unit as oc on oc.unit_id=oci.UNIT_ID  where oci.CARD_STATUS=2";
              if($data['filter_growerid']!="")
		{
			$sql.=" and GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
			if($data['filter_company']!="")
 {
 $sql.=" and oci.COMPANY_ID = '".$data['filter_company']."' ";
 }
 	if($data['filter_village']!="")
 {
 $sql.=" and oci.VILLAGE_ID = '".$data['filter_village']."' ";
 }
 
 if($data['filter_unit']!="")
 {
 $sql.=" and oci.UNIT_ID = '".$data['filter_unit']."' ";
 }
              $query = $this->db->query($sql);
                
	      return $query->rows;
       }
        public function getapproveToatal($data = array())
       { $log=new Log("Card-".date('Y-m-d').".log"); 
               $sql="select COUNT(*) as total from oc_card_issue where CARD_STATUS=2";
              if($data['filter_growerid']!="")
		{
			$sql.=" and GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
               $query = $this->db->query($sql);
                
	      return $query->row['total'];
       }
        public function updateApprovedStatus($gid,$StatusId,$StatusName,$card_data)
       { $log=new Log("Card-".date('Y-m-d').".log"); 
           $sql="UPDATE   oc_card_issue SET CARD_STATUS='".$StatusId."',CARD_STATUS_DESC='".$StatusName."',CREATE_DATE = NOW(),QR_SRTING='".$card_data."' where GROWER_ID = '" . $gid. "'";
            $query = $this->db->query($sql);
          $log->write($sql);
             $sqlsel="select SID,CARD_SERIAL_NUMBER from oc_card_issue where GROWER_ID = '" . $gid. "'";
            $query3 = $this->db->query($sqlsel);
			$log->write($sqlsel);
            $SID=$query3->row['SID'];
            $cardserialid=$query3->row['CARD_SERIAL_NUMBER'];
            $log->write($query3->row);
             $sql2="INSERT INTO  `oc_card_lcm_history` GROWER_ID='".$gid."',SET CARD_STATUS_ID = '" . $StatusId . "', CARD_STATUS_NAME = '" .$StatusName . "', CARD_ISSUE_SID = '".$SID."',CARD_SERIAL_NO='".$cardserialid."',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
       }
	    public function updateApprovedStatusNet($data)
       { 	$log=new Log("Card-".date('Y-m-d').".log"); 
            
	/*if(!empty($data['card_data']))
	{
		   $sql="UPDATE   oc_card_issue SET CARD_STATUS_DESC='".$data['StatusName']."',CARD_STATUS='".$data['StatusId']."',CREATE_DATE = NOW(),QR_SRTING="."\"".$data['card_data']."\"".",CARD_SERIAL_NUMBER='".$data['CardSerialNo']."' where GROWER_ID = '" . $data['gid']. "' and UNIT_ID = '" . $data['unitid']. "'";

	}
	else
	{
 $sql="UPDATE   oc_card_issue SET CARD_STATUS_DESC='".$data['StatusName']."',CARD_STATUS='".$data['StatusId']."',CREATE_DATE = NOW(),CARD_SERIAL_NUMBER='".$data['CardSerialNo']."' where GROWER_ID = '" . $data['gid']. "' and UNIT_ID = '" . $data['unitid']. "'";

		}*/
if($data['StatusId']==1)
			{
				$sql="UPDATE   oc_card_issue SET CARD_STATUS_DESC='".$data['StatusName']."',CARD_STATUS='".$data['StatusId']."',CREATE_DATE = NOW(),QR_SRTING="."\"".$data['card_data']."\"".",CARD_SERIAL_NUMBER='".trim($data['CardSerialNo'])."' where GROWER_ID = '" . $data['gid']. "' and UNIT_ID = '" . $data['unitid']. "'";
			}
			else if($data['StatusId']==5)
			{
				$sql="UPDATE   oc_card_issue SET CARD_STATUS_DESC='".$data['StatusName']."',CARD_STATUS='".$data['StatusId']."',CREATE_DATE = NOW(),QR_SRTING="."\"".$data['card_data']."\"".",CARD_SERIAL_NUMBER='".trim($data['CardSerialNo'])."' where GROWER_ID = '" . $data['gid']. "' and UNIT_ID = '" . $data['unitid']. "'";
			}
			else{
				$sql="UPDATE   oc_card_issue SET CARD_STATUS_DESC='".$data['StatusName']."',CARD_STATUS='".$data['StatusId']."',CREATE_DATE = NOW() where GROWER_ID = '" . $data['gid']. "' and UNIT_ID = '" . $data['unitid']. "'";
			}

            		$query = $this->db->query($sql);
			$log->write($sql);
			$log->write($query);
             $sqlsel="select SID,CARD_SERIAL_NUMBER from oc_card_issue where GROWER_ID = '" . $data['gid']. "'";
            $query3 = $this->db->query($sqlsel);
			$log->write($sqlsel);
            $SID=$query3->row['SID'];
            $cardserialid=$data['CardSerialNo'];//$query3->row['CARD_SERIAL_NUMBER'];
            $log->write($query3->row);
             $sql2="INSERT INTO  `oc_card_lcm_history` SET GROWER_ID='".$data['gid']."',CARD_STATUS_NAME = '" .$data['StatusName'] . "',CARD_STATUS_ID = '" . $data['StatusId'] . "', CARD_ISSUE_SID = '".$SID."',CARD_SERIAL_NO='".$cardserialid."',CR_DATE = '".date('Y-m-d')."'";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
//->num_rows
		return $query;
       }
       public function updateRejectStatusNet($data)
       {
	       $log=new Log("Card-rej-".date('Y-m-d').".log"); 
           $sql="UPDATE   oc_card_issue SET CARD_STATUS_DESC='".$data['StatusName']."',CARD_STATUS='".$data['StatusId']."',CREATE_DATE = NOW() where GROWER_ID = '" . $data['gid']. "'";
            $query = $this->db->query($sql);
             $log->write($sql);
             $sqlsel="select SID from oc_card_issue where GROWER_ID = '" . $data['gid']. "'";
            $query3 = $this->db->query($sqlsel);
            $SID=$query3->row['SID'];
            $log->write($sqlsel);
			$log->write($query3->row);
		if(empty($SID))
		{$SID="0";}
             $sql2="INSERT INTO  `oc_card_lcm_history` SET   GROWER_ID='".$data['gid']."',CARD_STATUS_NAME = '" .$data['StatusName'] . "',CARD_STATUS_ID = '" . $data['StatusId'] . "', CARD_ISSUE_SID = '".$SID."',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
       }
       // Card Print dtl

         public function getprintdtl($data = array())
       { //print_r($data); 
	   $log=new Log("Card-Print-".date('Y-m-d').".log"); 
              $sql="select oci.ZONE,oci.CIRCLE,oci.VILLAGE_NAME,oc.company_name,oci.COMPANY_ID,GROWER_NAME,FTH_HUS_NAME as 'FATHER_NAME',GROWER_ID,UNIT_NAME,CARD_SERIAL_NUMBER,QR_SRTING,oci.UNIT_ID from oc_card_issue  oci LEFT JOIN oc_unit  ou on ou.unit_id=oci.UNIT_ID   LEFT JOIN oc_company  oc on oc.company_id=oci.COMPANY_ID   where CARD_STATUS=5 and CARD_SERIAL_NUMBER!='0' and GROWER_ID!='0'";
              if($data['filter_growerid']!="")
		{
			$sql.=" and GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
 if($data['filter_villageid']!="")
{
$sql.=" and VILLAGE_ID = '".$data['filter_villageid']."'";
}


		if($data['filter_company']!="")
 {
 $sql.=" and oci.COMPANY_ID = '".$data['filter_company']."' ";
 }
 	if($data['filter_zone']!="")
 {
 $sql.=" and oci.ZONE = '".$data['filter_zone']."' ";
 }
 	if($data['filter_circle']!="")
 {
 $sql.=" and oci.CIRCLE = '".$data['filter_circle']."' ";
 }
 if($data['filter_unit']!="")
 {
 $sql.=" and oci.UNIT_ID = '".$data['filter_unit']."' ";
 }
	
		if (!empty($data['filter_date_start'])) {
$sql .= " AND date(CREATE_DATE) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND date(CREATE_DATE) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
if(!empty($data['selected_growers']))
 {
 $comma_separated = implode(",", $data['selected_growers']);
 $sql.=" and GROWER_ID in (".$comma_separated.") ";
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
$log->write($sql);
              $query = $this->db->query($sql);
		//   echo $sql;
	//$log->write('data from our db');
	//$log->write($query->rows);
	      return $query->rows;
       }
        public function getprintToatal($data = array())
       { $log=new Log("Card-".date('Y-m-d').".log"); 
             //  $sql=" oc_card_issue where CARD_STATUS=2 and CARD_SERIAL_NUMBER!='0' and GROWER_ID!='0'";

  $sql="select COUNT(*) as total from (select oci.ZONE,oci.CIRCLE,oci.VILLAGE_NAME,oc.company_name,oci.COMPANY_ID,GROWER_NAME,FTH_HUS_NAME as 'FATHER_NAME',GROWER_ID,UNIT_NAME,CARD_SERIAL_NUMBER,QR_SRTING,oci.UNIT_ID from oc_card_issue  oci LEFT JOIN oc_unit  ou on ou.unit_id=oci.UNIT_ID   LEFT JOIN oc_company  oc on oc.company_id=oci.COMPANY_ID   where CARD_STATUS=5 and CARD_SERIAL_NUMBER!='0' and GROWER_ID!='0'";
              if($data['filter_growerid']!="")
		{
			$sql.=" and GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
		if($data['filter_company']!="")
 {
 $sql.=" and oci.COMPANY_ID = '".$data['filter_company']."' ";
 }
 if($data['filter_villageid']!="")
{
$sql.=" and VILLAGE_ID = '".$data['filter_villageid']."'";
}


 	if($data['filter_zone']!="")
 {
 $sql.=" and oci.ZONE = '".$data['filter_zone']."' ";
 }
 	if($data['filter_circle']!="")
 {
 $sql.=" and oci.CIRCLE = '".$data['filter_circle']."' ";
 }
 if($data['filter_unit']!="")
 {
 $sql.=" and oci.UNIT_ID = '".$data['filter_unit']."' ";
 }
	
		if (!empty($data['filter_date_start'])) {
$sql .= " AND date(CREATE_DATE) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND date(CREATE_DATE) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
if(!empty($data['selected_growers']))
 {
 $comma_separated = implode(",", $data['selected_growers']);
 $sql.=" and GROWER_ID in (".$comma_separated.") ";
 } 
$sql.=") aa";

            
//echo $sql;
               $query = $this->db->query($sql);
                
	      return $query->row['total'];
       }
         public function updatePrintStatus($gid,$StatusId,$StatusName,$assoc)
       { 
			$log=new Log("Card-Print-".date('Y-m-d').".log"); 
           $sql="UPDATE   oc_card_issue SET CARD_STATUS='".$StatusId."',CARD_STATUS_DESC='".$StatusName."',ASSOCIATION='".$assoc."',CREATE_DATE = NOW() where GROWER_ID = '" . $gid. "'";
             $log->write($sql);
			 
		   $query = $this->db->query($sql);
            $log->write($sql);
             $sqlsel="select SID from oc_card_issue where GROWER_ID = '" . $gid. "'";
            $query3 = $this->db->query($sqlsel);
            $SID=$query3->row['SID'];
            
             $sql2="INSERT INTO  `oc_card_lcm_history` SET GROWER_ID='".$gid."',CARD_STATUS_ID = '" . $StatusId . "', CARD_STATUS_NAME = '" .$StatusName . "', CARD_ISSUE_SID = '".$SID."',CR_DATE = NOW()";
            $query2 = $this->db->query($sql2);
           // $log->write($sql2);
		 return   $SID;
       }
       public function getgrowerid($data = array())
       { $log=new Log("Card-".date('Y-m-d').".log"); 
          $growerid_data = array();
          $sql="select GROWER_ID,GROWER_NAME from oc_card_issue ";
          if($data['filter_growerid']!="")
		{
			$sql.=" where GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
                
          $query = $this->db->query($sql);
                
	  return $query->rows; 
       }
        public function  getcardmobileno($data)
       {        
           $sql="SELECT MOB,CARD_PIN from  oc_card_issue  where CARD_SERIAL_NUMBER='".$data['cardserial_id']."'";
           $query = $this->db->query($sql);
		   return $query->rows;
       }
	   public function getcardserialno($grower_id)
	  { 
		$log=new Log("CARDSTATUS-Approve-All-".date('Y-m-d').".log"); 
		$sql="SELECT CARD_SERIAL_NUMBER,UNIT_ID from oc_card_issue where GROWER_ID='".$grower_id."' ";
		$log->write($sql);
		$query = $this->db->query($sql);
		return $query->row;
	  }
	public function getUnit()
	 { 
	 $sql="SELECT unit_id,unit_name from oc_unit";

	 $query = $this->db->query($sql);

	return $query->rows;
	 }
 public function getunitname($unitid)
 { 
 $sql="SELECT unit_name from oc_unit where unit_id='".$unitid."'";

 $query = $this->db->query($sql);

return $query->row['unit_name'];
 }
 public function getcompanyname($companyid)
 { 
 $sql="SELECT company_name from oc_company where company_id='".$companyid."'";

 $query = $this->db->query($sql);

return $query->row['company_name'];
 }
 
 public function getComapny()
 { 
 $sql="SELECT company_id,company_name from oc_company where is_active='1'";

 $query = $this->db->query($sql);

return $query->rows;
 }
 public function getunitbycompany($cid){
 $sql="SELECT unit_id,unit_name from oc_unit WHERE company_id='".$cid."' ";
 $query = $this->db->query($sql);

//echo $sql;
 return $query->rows; 
 }
 public function getprinteddtl($data = array())
       { //print_r($data); 
	   $log=new Log("Card-Print-".date('Y-m-d').".log"); 
              $sql="select oci.ASSOCIATION,oci.ZONE,oci.CIRCLE,oci.VILLAGE_NAME,oc.company_name,oci.COMPANY_ID,GROWER_NAME,FTH_HUS_NAME as 'FATHER_NAME',GROWER_ID,UNIT_NAME,CARD_SERIAL_NUMBER,QR_SRTING,oci.UNIT_ID from oc_card_issue  oci LEFT JOIN oc_unit  ou on ou.unit_id=oci.UNIT_ID   LEFT JOIN oc_company  oc on oc.company_id=oci.COMPANY_ID   where CARD_STATUS=6 and CARD_SERIAL_NUMBER!='0' and GROWER_ID!='0'";
              if($data['filter_growerid']!="")
		{
			$sql.=" and GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
		    if($data['filter_association']!="")
		{
			$sql.=" and oci.ASSOCIATION = '".$data['filter_association']."' ";
		}
 if($data['filter_villageid']!="")
{
$sql.=" and VILLAGE_ID = '".$data['filter_villageid']."'";
}


		if($data['filter_company']!="")
 {
 $sql.=" and oci.COMPANY_ID = '".$data['filter_company']."' ";
 }
 	if($data['filter_zone']!="")
 {
 $sql.=" and oci.ZONE = '".$data['filter_zone']."' ";
 }
 	if($data['filter_circle']!="")
 {
 $sql.=" and oci.CIRCLE = '".$data['filter_circle']."' ";
 }
 if($data['filter_unit']!="")
 {
 $sql.=" and oci.UNIT_ID = '".$data['filter_unit']."' ";
 }
	
		if (!empty($data['filter_date_start'])) {
$sql .= " AND date(CREATE_DATE) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND date(CREATE_DATE) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
if(!empty($data['selected_growers']))
 {
 $comma_separated = implode(",", $data['selected_growers']);
 $sql.=" and GROWER_ID in (".$comma_separated.") ";
 } 
 if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " order by oci.CREATE_DATE desc LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
$log->write($sql);
              $query = $this->db->query($sql);
		// echo $sql;
	      return $query->rows;
       }
        public function getprintedToatal($data = array())
       { $log=new Log("Card-".date('Y-m-d').".log"); 
             //  $sql=" oc_card_issue where CARD_STATUS=2 and CARD_SERIAL_NUMBER!='0' and GROWER_ID!='0'";

  $sql="select COUNT(*) as total from (select oci.ASSOCIATION,oci.ZONE,oci.CIRCLE,oci.VILLAGE_NAME,oc.company_name,oci.COMPANY_ID,GROWER_NAME,FTH_HUS_NAME as 'FATHER_NAME',GROWER_ID,UNIT_NAME,CARD_SERIAL_NUMBER,QR_SRTING,oci.UNIT_ID from oc_card_issue  oci LEFT JOIN oc_unit  ou on ou.unit_id=oci.UNIT_ID   LEFT JOIN oc_company  oc on oc.company_id=oci.COMPANY_ID   where CARD_STATUS=6 and CARD_SERIAL_NUMBER!='0' and GROWER_ID!='0'";
              if($data['filter_growerid']!="")
		{
			$sql.=" and GROWER_ID like '%".$data['filter_growerid']."%' ";
		}
		   if($data['filter_association']!="")
		{
			$sql.=" and oci.ASSOCIATION = '".$data['filter_association']."' ";
		}
 if($data['filter_villageid']!="")
{
$sql.=" and VILLAGE_ID = '".$data['filter_villageid']."'";
}


		if($data['filter_company']!="")
 {
 $sql.=" and oci.COMPANY_ID = '".$data['filter_company']."' ";
 }
 	if($data['filter_zone']!="")
 {
 $sql.=" and oci.ZONE = '".$data['filter_zone']."' ";
 }
 	if($data['filter_circle']!="")
 {
 $sql.=" and oci.CIRCLE = '".$data['filter_circle']."' ";
 }
 if($data['filter_unit']!="")
 {
 $sql.=" and oci.UNIT_ID = '".$data['filter_unit']."' ";
 }
	
		if (!empty($data['filter_date_start'])) {
$sql .= " AND date(CREATE_DATE) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND date(CREATE_DATE) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
if(!empty($data['selected_growers']))
 {
 $comma_separated = implode(",", $data['selected_growers']);
 $sql.=" and GROWER_ID in (".$comma_separated.") ";
 } 
$sql.=") aa";

            
//echo $sql;
               $query = $this->db->query($sql);
                
	      return $query->row['total'];
       }
public function getcardsummarydtl($data = array())
       { //print_r($data); , 3, 4, 5, 6, 7, 9
	   $log=new Log("CardSummaryreport-".date('Y-m-d').".log"); 
              $sql="SELECT 
oc_unit.unit_name,
SUM(CASE WHEN CARD_STATUS IN (1,2, 3, 4, 5, 6, 7, 9,10,11,12 ) THEN 1 ELSE 0 END) AS requsition,
SUM(CASE WHEN CARD_STATUS IN (1) THEN 1 ELSE 0 END) AS request,
SUM(CASE WHEN CARD_STATUS IN (4) THEN 1 ELSE 0 END) AS rejected,
SUM(CASE WHEN CARD_STATUS IN (3) THEN 1 ELSE 0 END) AS approved,
SUM(CASE WHEN CARD_STATUS IN (5) THEN 1 ELSE 0 END) AS printing,
SUM(CASE WHEN CARD_STATUS IN (6 ) THEN 1 ELSE 0 END) AS printed,
SUM(CASE WHEN CARD_STATUS IN (7 ) THEN 1 ELSE 0 END) AS dispatch,
SUM(CASE WHEN CARD_STATUS IN (2 ) THEN 1 ELSE 0 END) AS verify,
SUM(CASE WHEN CARD_STATUS IN (11) THEN 1 ELSE 0 END) AS blocked,
SUM(CASE WHEN CARD_STATUS IN (9) THEN 1 ELSE 0 END) AS deliver
FROM
oc_card_issue
left join oc_unit on oc_unit.unit_id=oc_card_issue.UNIT_ID where oc_card_issue.CARD_STATUS!=''";


	
$sql.="GROUP BY oc_card_issue.unit_id";
            

$log->write($sql);
              $query = $this->db->query($sql);
		//echo $sql;
	      return $query->rows;
       }
public function getvillagebyunit($cid){
$sql="SELECT VILLAGE_ID,VILLAGE_NAME from oc_card_issue WHERE UNIT_ID='".$cid."' group by VILLAGE_ID";
$query = $this->db->query($sql);

//echo $sql;
return $query->rows;
}
public function  getcardstatusdetail($data)
  
       {      $log=new Log("Card-".date('Y-m-d').".log");  
           $sql="SELECT *,ou.unit_name from  oc_card_issue  left join oc_unit ou on ou.unit_id= oc_card_issue.unit_id  where GROWER_ID!='' ";
           if($data['grower_id']!="")
  {
   $sql.="and   oc_card_issue.GROWER_ID = '" .$data['grower_id']."' ";
  }
                if($data['mobile']!="")
  {
   $sql.=" and oc_card_issue.MOB = '" .$data['mobile']."' ";
  }
        if($data['card_serial_no']!="")
        {
             $sql.=" and oc_card_issue.CARD_SERIAL_NUMBER = '" .$data['card_serial_no']."' ";
        }
  if($data['unit_id']!="")
        {
             $sql.=" and oc_card_issue.UNIT_ID = '" .$data['unit_id']."' ";
        }
  

//echo $sql; //exit;
           $query = $this->db->query($sql);

  return $query->rows; 
       }
	 
	    public function  getqrstring($cid)
       {        
           $sql="SELECT UNIT_ID,COMPANY_ID,GROWER_ID,CARD_SERIAL_NUMBER from  oc_card_issue  where CARD_SERIAL_NUMBER='".$cid."'";
           $query = $this->db->query($sql);
		   return $query->row;
       }
	    public function  getstatusdatehistory($cid)
       {      

     	   $log=new Log("Cardgetstatusdatehistory-".date('Y-m-d').".log");  
            $sql="SELECT DATE(CR_DATE) as statusdate,CARD_STATUS_NAME from  oc_card_lcm_history  where CARD_SERIAL_NO='".$cid."' and CARD_STATUS_ID in('7','9') group by CARD_STATUS_ID";
           $query = $this->db->query($sql);
		   $log->write($sql);
		   $log->write($query->rows);
		   return $query->rows;
		   
       }
	    public function  getcardorderhistory($cid)
       {      

     	   $log=new Log("Cardgetstatusorderhistory-".date('Y-m-d').".log");  
           $sql="SELECT oc_order.order_id,oc_store.name as storename,oc_order.tagged,oc_order.cash,DATE(oc_order.date_added)  datea FROM oc_order left join oc_store on oc_store.store_id=oc_order.store_id  where oc_order.card_serial_no='".$cid."' and oc_order.order_status_id='5'";
           $query = $this->db->query($sql);
		   $log->write($sql);
		   //$log->write($query->rows);
			$ret_array=array();
		   /*foreach($query->rows as $order_row)
		   {
				$ret_array[]=$order_row;
				$sql2="SELECT * FROM oc_order_product  where order_id='".$order_row['order_id']."' ";
				$query2 = $this->db->query($sql2);
				$log->write($sql2);
				$ret_array['products']=$query2->rows;
		   }*/
		   return $query->rows;//$ret_array;
		   
       }
	   
	   
	   
	   
	      public function  getcarddetail($data)
		 
       {      $log=new Log("Card-".date('Y-m-d').".log");   
           $sql="SELECT *,ou.unit_name from  oc_card_issue  left join oc_unit ou on ou.unit_id= oc_card_issue.unit_id  where GROWER_ID!='' ";
           if($data['grower_id']!="")
		{
			$sql.="and   oc_card_issue.GROWER_ID = '" .$data['grower_id']."' ";
		}
                if($data['mobile']!="")
		{
			$sql.=" and oc_card_issue.MOB = '" .$data['mobile']."' ";
		}
        if($data['card_serial_no']!="")
        {
             $sql.=" and oc_card_issue.CARD_SERIAL_NUMBER = '" .$data['card_serial_no']."' ";
        }
		if($data['unit_id']!="")
        {
             $sql.=" and oc_card_issue.UNIT_ID = '" .$data['unit_id']."' ";
        }
		

//echo $sql; //exit;
           $query = $this->db->query($sql);

		return $query->rows;
       }
	   
	  public function orderproductdetails($order_id)
	  {
		  $log=new Log("orderproductdetails-".date('Y-m-d').".log");   
		  	 $sql="SELECT * FROM oc_order_product  where order_id='".$order_id."'";
			$query = $this->db->query($sql);
			   $log->write($sql);
			   $log->write($query->rows);
			return  $query->rows;
			
		  
	  }
	  
	     public function  getcardno()
       {        
           $sql="SELECT card_no FROM `qr_string` ";
           $query = $this->db->query($sql);
		   return $query->rows;
       }
	    public function  updateqrstring($cid,$card_data,$uid)
       {        
	    $log=new Log("updateqrstring-".date('Y-m-d').".log");
		
           $sql='update qr_string SET qr_string="'.$card_data.'"  where card_no="'.$cid.'" ';
		   //  $log->write($sql);	
           $query = $this->db->query($sql);
		   
		   //$card_data =addslashes($card_data);
		   
		   $data =addslashes($card_data);
		   $sql2="update oc_card_issue SET QR_SRTING='".$data."' where CARD_SERIAL_NUMBER='".$cid."' and UNIT_ID='".$uid."'";
           $log->write($sql2);	
            $query2 = $this->db->query($sql2);		   
		   //return $query->rows;
       }
	   
	    public function  lostCarddetail()
       {        
           $sql="SELECT * FROM `lostcard`";
           $query = $this->db->query($sql);
		   return $query->rows;
       }
	   
	    public function  UpdateLost($cid,$data)
        {        
	    $log=new Log("updateqrstring-".date('Y-m-d').".log");
        $sql='update lostcard SET D_QR_SRTING="'.$data.'"  where SID="'.$cid.'" ';
		echo $sql;
		   
		exit;
        $query = $this->db->query($sql);
		  		   
		   //return $query->rows;
       }
	   
	   
        
}