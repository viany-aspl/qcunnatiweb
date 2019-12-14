<?php
class Modelfarmfarmop extends Model {


public function getOpportunity($user_id,$seasonid, $start = 0, $limit = 10) 
	{

                          $lat = $seasonid['Latt']; // latitude of centre of bounding circle in degrees
                          $lon = $seasonid['Long']; // longitude of centre of bounding circle in degrees
                          $rad = "5";//$_GET['rad']; // radius of bounding circle in kilometers

                          $R = 6371;  // earth's mean radius, km
                          
                           // first-cut bounding box (in degrees)
                          $maxLat = $lat + rad2deg($rad/$R);
                          $minLat = $lat - rad2deg($rad/$R);
                          $maxLon = $lon + rad2deg(asin($rad/$R) / cos(deg2rad($lat)));
                          $minLon = $lon - rad2deg(asin($rad/$R) / cos(deg2rad($lat)));
                          $lat=deg2rad($lat);
                          $lon=deg2rad($lon);      		


		if ($start < 0) {
			$start = 0;
		}
		if ($limit < 1) {
			$limit = 20;
		}
		$log=new Log("opportunity-".date('Y-m-d').".log");
                            $log->write( "latt : ".$lat.",long: ".$lon);
		//$sql="Select * from  oc_op_opportunity  where  oc_op_opportunity.CropID=".$seasonid["CropID"]."  left join oc_crop on oc_crop.id=oc_op_opportunity.CropID order by OpportunityID ";
                //
                            $sql = "Select FirstCut.* ,
                   acos(sin(".$lat.")*sin(radians(Latitute)) + cos(".$lat.")*cos(radians(Latitute))*cos(radians(Longitude)-".$lon.")) * ".$R." As D
            From (
                Select *
                From " . DB_PREFIX . "op_opportunity
                left join oc_crop on oc_crop.id=oc_op_opportunity.CropID
                Where  Latitute Between ".$minLat." And ".$maxLat."
                  And Longitude Between ".$minLon." And ".$maxLon."
                   and CropID='".$seasonid["CropID"]."'
            ) As FirstCut
            Where acos(sin(".$lat.")*sin(radians(Latitute)) + cos(".$lat.")*cos(radians(Latitute))*cos(radians(Longitude)-".$lon.")) * ".$R." < ".$rad." ";

                            $sql.=" LIMIT " . (int)$start . "," . (int)$limit;
		$log->write($sql);
		$query = $this->db->query($sql); 
		return $query->rows;



 
/*

  
    $sql = "Select SID,POS_NAME,POS_MOBILE,LATT , LONGG ,
                   acos(sin(".$lat.")*sin(radians(LATT)) + cos(".$lat.")*cos(radians(LATT))*cos(radians(LONGG)-".$lon.")) * ".$R." As D
            From (
                Select SID,POS_NAME,POS_MOBILE, LATT, LONGG
                From " . DB_PREFIX . "can_pos
                Where CR_BY='".$emp_id."' AND LATT Between ".$minLat." And ".$maxLat."
                  And LONGG Between ".$minLon." And ".$maxLon."
            ) As FirstCut
            Where acos(sin(".$lat.")*sin(radians(LATT)) + cos(".$lat.")*cos(radians(LATT))*cos(radians(LONGG)-".$lon.")) * ".$R." < ".$rad."
            Order by POS_NAME";
    $params = [
        'lat'    => deg2rad($lat),
        'lon'    => deg2rad($lon),
        'minLat' => $minLat,
        'minLon' => $minLon,
        'maxLat' => $maxLat,
        'maxLon' => $maxLon,
        'rad'    => $rad,
        'R'      => $R,
    ];
          
        
     $query = $this->db->query($sql);
     $datas=$query->rows;
     
        }  catch (Exception $e)
        {
            print_r($e);
        }     
     return $datas;

*/



	}


	public function CreateNewOpportunity($data)
	{
		$log=new Log("opportunity-".date('Y-m-d').".log");		
		$sql="INSERT INTO oc_op_opportunity (PostedBy, PostedByName, CropID, Quantity, Unit, Grade, ValidityDate,  PostedDate, Location,Longitude,Latitute,Status,PercentComplete,Price,imagecount)  VALUES ('".$data['PostedBy']."', '".$data['PostedByName']."', '".$data['CropID']."', '".$data['Quantity']."', '".$data['Unit']."', '".$data['Grade']."', '".$data['ValidityDate']."',  '".$data['PostedDate']."', '".$data['Location']."','".$data['Longitude']."','".$data['Latitute']."','".$data['Status']."','".$data['PercentComplete']."','".$data['Price']."','".$data['ImageCount']."')";
		$log->write($sql);
		$this->db->query($sql);
		return $this->db->getLastId();

	}



}

?>