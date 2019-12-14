<?php
class ModelGeneratePinPin extends Model {
	
        public function getuserbygroupid($data)       
        {
            $sql="SELECT * FROM oc_user where user_group_id='36'";
			if(!empty($data['username']))
			{
				$sql.=" and username like '%".$data['username']."%' ";
			}
            //echo $sql;
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
		public function getuserbygroupidTotal($data)       
        {
            $sql="SELECT * FROM oc_user where user_group_id='36'";
            //echo $sql;
			if(!empty($data['username']))
			{
				$sql.=" and username like '%".$data['username']."%' ";
			}
            $query = $this->db->query($sql);                
            return $query->num_rows;
        }
     
//////////with out isec////////////////

//////////////////////////////


}