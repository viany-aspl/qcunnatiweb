<?php
class ModelReportLicense extends Model {
	
	
	
	public function getOrders($data = array()) {
		
               $sql1 = "SELECT * FROM `" . DB_PREFIX . "store`";
                if (!empty($data['filter_store'])) 
                {
                $sql1 .=" where store_id='".(int)$data['filter_store']."'";
                }
               if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql1 .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                
               $query1 = $this->db->query($sql1);
               $storerow= $query1->rows;
              // print_r($storerow[0][store_id] ); exit;
               $array=array();
               foreach($storerow as $store)
               {  //exit;

               $sql = "SELECT value FROM shop.oc_setting where `key`='config_gstn' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $gstn_row=$query->row;
               
               $sql = "SELECT value FROM shop.oc_setting where `key`='config_fertilizer_number' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $fertilizer_row=$query->row;
               
               $sql = "SELECT value FROM shop.oc_setting where `key`='config_partner_name' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $partner_row=$query->row;
               
               $sql = "SELECT value FROM shop.oc_setting where `key`='config_fertilizer_from' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $fertilizerissue_row=$query->row;
               
               $sql = "SELECT value FROM shop.oc_setting where `key`='config_fertilizer_to' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $fertilizerissue_row=$query->row;
               
                 $sql = "SELECT value FROM shop.oc_setting where `key`='config_Pesticide_number' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $pesticide_row=$query->row;
               
                 $sql = "SELECT value FROM shop.oc_setting where `key`='config_Pesticide_from' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $pesticidefrom_row=$query->row;
               
                $sql = "SELECT value FROM shop.oc_setting where `key`='config_Pesticide_to' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $pesticideto_row=$query->row;
               
               $sql = "SELECT value FROM shop.oc_setting where `key`='config_Seed_number' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $seed_row=$query->row;
               
               $sql = "SELECT value FROM shop.oc_setting where `key`='config_MSMFID' and store_id='".$store['store_id']."'";
               $query = $this->db->query($sql);
               $MSMFID_row=$query->row;
               
               $array[]=array('store_id'=>$store['store_id'],
                   'gstn'=>$gstn_row['value'],
                   'fertilizer'=>$fertilizer_row['value'],
                   'partner'=>$partner_row['value'],
                   'fertilizerissue'=>$fertilizerissue_row['value'],
                   'fertilizerto'=>$fertilizerto_row['value'],
                   'pesticide'=>$pesticide_row['value'],
                   'pesticidefrom'=>$pesticidefrom_row['value'],
                   'pesticideto'=>$pesticideto_row['value'],
                   'seed'=>$seed_row['value'],
                   'MSMFID'=>$MSMFID_row['value']);
               }

                //echo $sql;
		
//print_r($array);
		return $array;
                
	}

	public function getTotalOrders($data = array()) {

                $sql = "select count(*) as total from (SELECT * FROM `" . DB_PREFIX . "store`) as  aa";


                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND store_id='".(int)$data['filter_store']."'";
                }
                
		

		$query = $this->db->query($sql);

		return $query->row;
	}

	
}