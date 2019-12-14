<?php
class Modelfarmfarm extends Model {


public function getCurrentFarms($farmer_id,$seasonid, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}
		//AND Season = '".$seasonid."' 
		/// AND year = '". date('Y')."'
		$sql="Select * from oc_farmer_farm where farmerid = '".$farmer_id."'   order by sowingdate desc LIMIT " . (int)$start . "," . (int)$limit;
		$log=new Log("farm-".date('Y-m-d').".log");
		$query = $this->db->query($sql);
$log->write('in model');
$log->write($sql);
		return $query->rows;
	}

public function getOlderFarms($farmer_id,$seasonid,$year, $start = 0, $limit = 10) 
		{
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$sql="Select * from oc_farmer_farm where farmerid = '".$farmer_id."' AND year = '". $year."' AND Season = '".$seasonid."' LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);

		return $query->rows;
	}

public function getFarmsCalendar($farm_id) 
		{
		
		$sql="Select * from oc_farm_calendar where FarmID = '".$farm_id."'   order by StartDate desc";

		$query = $this->db->query($sql);
		$log=new Log("calfarm".date('Y-m-d').".log");
		$log->write("model");
		$log->write($sql);
		return $query->rows;
	}


public function getActivityOnFarmID($farm_id) 
		{
		
		$sql="Select * from oc_farm_activity where FarmID = '".$farm_id."'   order by dateconducted desc";

		$query = $this->db->query($sql);
		$log=new Log("calActivityfarm-".date('Y-m-d').".log");
		$log->write("model");
		$log->write($sql);
		return $query->rows;
	}


public function getImageText($crop_id,$activityid) 
		{

		$sql="Select * from  oc_crop_calendar_activity where cropcalendarid = '".$crop_id."' and activityid='".$activityid."'";
		$query = $this->db->query($sql);

		return $query->rows;
	}


public function getseasons() 
		{

		$sql="Select * from oc_season where isactive='1'";
		$query = $this->db->query($sql);

		return $query->rows;
	}
public function getactivity() 
		{

		$sql="Select * from oc_activity where isactive='1'";
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function CreateNewFarm($data)
	{
	//create new farm
		$log=new Log("newfarm-".date('Y-m-d').".log");
$log->write('in model');
		$sql="INSERT INTO oc_farmer_farm (farmerid, farmname,cropid,seedsown,year,acreage,sowingdate,season) VALUES ('".$data['farmerid']."','".$data['farmname']."','".$data['cropid']."','".$data['seedsown']."','".$data['year']."','".$data['acreage']."','".$data['sowingdate']."','".$data['season']."')";
		$log->write($sql);
		$this->db->query($sql);
		$farmid=$this->db->getLastId();
                            $log->write('farmid->'.$farmid);
		$sqlcal="INSERT INTO oc_farm_calendar (farmerid, farmid, cropcalendarid, cropcalendarname, activityid, activityname, startdate, enddate, complete) 
		 Select '".$data['farmerid']."' as 'farmerid','".$farmid."' as 'farmid',A.cropcalendarid,cropcalendarname,activityid,activityname,DATE_ADD('".$data['sowingdate']."',INTERVAL A.startdaysfromsowing DAY) as 'startdate',DATE_ADD('".$data['sowingdate']."',INTERVAL enddaysfromsowing DAY) as 'enddate','0' as complete from oc_crop_calendar as A JOIN oc_crop_calendar_activity AS B on A.CropCalendarID = B.CropCalendarID where CropID ='".$data['cropid']."'";	
				$log->write($sqlcal);
				$this->db->query($sqlcal);
		return $farmid;//$this->db->getLastId();
	}

public function CreateNewFarmActivity($data)
{
			$log=new Log("newactivity-".date('Y-m-d').".log");
	$sql="INSERT INTO oc_farm_activity (activityid, dateconducted, appliedproduct, quantityapplied, unit, remarks, dateupdated,  farmerid, farmid)  VALUES ('".$data['activityid']."','".$data['dateconducted']."','".$data['appliedproduct']."','".$data['quantityapplied']."','".$data['unit']."','".$data['remarks']."','".$data['dateupdated']."','".$data['farmerid']."','".$data['farmid']."')";
	$log->write($sql);
	 $this->db->query($sql);
	return $this->db->getLastId();

}

public function CreateSoilTest($data)
{
			$log=new Log("newsoiltest-".date('Y-m-d').".log");
	$sql="INSERT INTO tblsoiltest (farmid, farmerid, texture, ec, ocn, p2o5, k2o, zinc, ph, dateconducted, datecollected)  VALUES ('" . $data['farmid'] . "','" . $data['farmerid'] . "','" . $data['texture'] . "','" . $data['ec']. "','" . $data['ocn']. "','" . $data['p2o5'] . "','" . $data['k2o'] . "','" . $data['zinc'] . "','" . $data['ph'] . "','" . $data['dateconducted']. "','" . $data['datecollected'] . "') ON DUPLICATE KEY UPDATE texture= VALUES(texture),ec= VALUES(ec),ocn= VALUES(ocn),p2o5= VALUES(p2o5),k2o= VALUES(k2o),zinc= VALUES(zinc),ph= VALUES(ph),dateconducted= VALUES(dateconducted),datecollected= VALUES(datecollected)";
	$log->write($sql);
	 $this->db->query($sql);
	return $this->db->getLastId();

}


}

?>