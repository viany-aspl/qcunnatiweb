<?php
class Modelsmssms extends Model {
	
	
        public function AddSMS($data)
        {
            $temp_array=$data;
            unset($temp_array['username']);
            unset($temp_array['password']);
            unset($temp_array['url']);
            unset($temp_array['displayname']);
            unset($temp_array['operator']);
            unset($temp_array['query_type']);
           //print_r($temp_array);
            for($a=0;$a<count($temp_array['name']);$a++)
            {
               $row[]=array($temp_array['name'][$a]=>$temp_array['value'][$a]);
            }
                     
            $serialized_data = serialize($row);
         
            $log=new Log("addsms".date('d_m_Y').".log"); 
            $cr_date=date('Y-m-d');
      
            $sqlIC="INSERT INTO oc_sms_operator SET
                USERNAME='".$data['username']."',
               
                DISPLAYNAME ='".$data['displayname']."',
                PWD='".$data['password']."',
                HOSTNAME = '".$data['url']."',  
                QUERY_TYPE = '".$data['query_type']."',  
                ACT = '0',
                SEND_PARAMETER='".$serialized_data."',
                OPERATOR='".$data['operator']."'";
            
                $log->write("sms".$sqlIC);
                $re_id= $this->db->query($sqlIC);
                if( $re_id==1){
                    return '1';
                }
                
            else{
               return '2';  
            }
     
        }
        
        
        
        
         public function GETSMS($data = array())
         {
             $sql = "SELECT SID,ACT,HOSTNAME,OPERATOR,QUERY_TYPE FROM oc_sms_operator";
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
             
             
	     return $query->rows;
         }
         
        
         public function getTotalSms($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM oc_sms_operator";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
        
        public function statusupdate($sid)
        { 
            $sql1 = "select ACT from  oc_sms_operator where `SID`='".$sid."' limit 1 ";
            $query1 = $this->db->query($sql1);
            $current_STATUS=$query1->row['ACT'];
            
            if($current_STATUS=='1')
            {
                
                $sql = "update oc_sms_operator set ACT='0' where `SID`='".$sid."' ";
                return $query = $this->db->query($sql);
            }
            if($current_STATUS=='0')
            {
                $sql = "update oc_sms_operator set ACT='0'";
                $query = $this->db->query($sql);
                $sql = "update oc_sms_operator set ACT='1' where `SID`='".$sid."' ";
                return $query = $this->db->query($sql);
            }
            //return 0;
        }
	
        public function deleteCustomer($SID) {
		$this->db->query("DELETE FROM oc_sms_operator WHERE SID = '" . (int)$SID . "' AND ACT='0'");
		}
                
        public function selectdetails($data) {
             $sql="SELECT * FROM `oc_sms_operator`  WHERE SID ='".$data['sid']."'";
            $query = $this->db->query($sql);
            return $query->row;
          }
}