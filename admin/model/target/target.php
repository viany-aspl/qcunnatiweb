<?php
class ModelTargetTarget extends Model {
	
	
        public function submit_form($data=array())
        {
            $log=new Log('target_'.date('Y-m-d').".log");
            
            $set_date=date('Y-m-d');
            $submit_date_time=date('Y-m-d h:i:s');
            $year=date('Y');

		$sql="SELECT * FROM stores_target where store_id='".$data["filter_store"]."' and  month='".$data["filter_month"]."' and year='".$data["filter_year"]."' limit 1  ";
            $query = $this->db->query($sql);
            $arrray=$query->row;
            $num_res=count($arrray);
            
            $set_date=date('Y-m-d');
            if($num_res>0)
            {
		$SID=$arrray["SID"];
		$sql="update `stores_target` set  `store_id`='".$data["filter_store"]."',`logged_user`='".$data["logged_user"]."',`month`='".$data["filter_month"]."',`set_date`='".$set_date."'"
                    . ",`Fertilizer`='".$data["Fertilizer"]."',`Crop_Protection`='".$data["Crop_Protection"]."'"
                    . ",`Crop_Care`='".$data["Crop_Care"]."',`Seeds`='".$data["Seeds"]."'"
                    . ",`Remarks`='".$data["remarks"]."',`submit_date_time`='".$submit_date_time."',`year`='".$data["filter_year"]."' where `SID`='".$SID."' ";
	    }
            else
	    {
            $sql="insert into `stores_target` set  `store_id`='".$data["filter_store"]."',`logged_user`='".$data["logged_user"]."',`month`='".$data["filter_month"]."',`set_date`='".$set_date."'"
                    . ",`Fertilizer`='".$data["Fertilizer"]."',`Crop_Protection`='".$data["Crop_Protection"]."'"
                    . ",`Crop_Care`='".$data["Crop_Care"]."',`Seeds`='".$data["Seeds"]."'"
                    . ",`Remarks`='".$data["remarks"]."',`submit_date_time`='".$submit_date_time."',`year`='".$data["filter_year"]."' ";
	    }

            //echo $sql;
            $log->write($sql);
            $query = $this->db->query($sql);
           
        }
        public function get_targets($data=array())
        {
            $sql="SELECT stores_target.*,oc_store.name as store_name FROM stores_target join oc_store on oc_store.store_id=stores_target.store_id where stores_target.store_id";
            
            if (!empty($data['filter_store']) ) {
               $sql .=" ='".$data['filter_store']."' ";
			
		}
            else {
                $sql .=" >'0' ";
            }
                 if (!empty($data['filter_month']) ) {
               $sql .=" and month ='".$data['filter_month']."' ";
			
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
        public function get_Totaltarget($data=array())
        {
             $sql="select count(*) as total from (SELECT month FROM stores_target where store_id";
            
            if (!empty($data['filter_store']) ) {
               $sql .=" ='".$data['filter_store']."' ";
			
		}
            else {
                $sql .=" >'0' ";
            }
                 if (!empty($data['filter_month']) ) {
               $sql .=" and month ='".$data['filter_month']."' ";
			
		}
                $sql.=" ) as aa";
                //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->row["total"];
        }
        
	
}