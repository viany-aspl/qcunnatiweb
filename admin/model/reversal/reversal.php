<?php
class ModelReversalReversal extends Model {
	
public function check_current_quantity($filter_store,$filter_name_id)
        {
            $sql1="SELECT quantity FROM `oc_product_to_store` where `store_id`='".$filter_store."'  and  `product_id`='".$filter_name_id."'  ";
            $query1 = $this->db->query($sql1);
            return $quantity=$query1->row["quantity"];
            
        }
        public function reverse_quantity($data=array())
        {
            
            $sql="update  `oc_product_to_store` set quantity=quantity-".$data["filter_quantity"]." where `store_id`='".$data["filter_store"]."'  and  `product_id`='".$data["filter_name_id"]."' ";
            $query = $this->db->query($sql);
            $log=new Log("quantity-reversal-".date('Y-m-d').".log");
            $log->write($sql);
            
        }
        public function insert_into_trans($data=array(),$status)
        {
            $sql="insert oc_quantity_reversal_trans set `store_id`='".$data["filter_store"]."',`quantity`='".$data["filter_quantity"]."',`product_id`='".$data["filter_name_id"]."',`product_name`='".$data["filter_name"]."',`remarks`='".$data["remarks"]."',`logged_user`='".$data["logged_user"]."',`status`='".$status."' ";
            $query = $this->db->query($sql);
            $log=new Log("quantity-reversal-".date('Y-m-d').".log");
            $log->write($sql);
            
        }




	public function getTrans_report($data = array()) {
	   
            $sql="SELECT oc_quantity_reversal_trans.*,oc_store.name as store_name FROM `oc_quantity_reversal_trans`  "
                    . "LEFT JOIN oc_store on oc_store.store_id=oc_quantity_reversal_trans.store_id where oc_quantity_reversal_trans.store_id>0  ";
                  
                            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(date_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_quantity_reversal_trans.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
		if (!empty($data['filter_name_id'])) {
			$sql .= " AND oc_quantity_reversal_trans.product_id = '" . $this->db->escape($data['filter_name_id']) . "'";
		}
            
            $sql.=" order by date_time desc ";
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
                            $log=new Log("quantity-reversal-".date('Y-m-d').".log");
	              $log->write($sql);
		return $query->rows;
	}

	public function getTotal_transation($data = array()) {
		
            $sql="select count(store_name) as total from ( SELECT oc_quantity_reversal_trans.*,oc_store.name as store_name FROM `oc_quantity_reversal_trans`  "
                    . "LEFT JOIN oc_store on oc_store.store_id=oc_quantity_reversal_trans.store_id where oc_quantity_reversal_trans.store_id>0  ";
                  
                            if (!empty($data['filter_date_start'])) {
			$sql .= " and DATE(date_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		if (!empty($data['filter_store'])) {
			$sql .= " AND oc_quantity_reversal_trans.store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}
		if (!empty($data['filter_name_id'])) {
			$sql .= " AND oc_quantity_reversal_trans.product_id = '" . $this->db->escape($data['filter_name_id']) . "'";
		}
            
            
                		$sql.= " ) as aa";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
    public function add_quantity($data=array())
        {
            
			$sql="update  `oc_product_to_store` set quantity=quantity+".$data["filter_quantity"]." where `store_id`='".$data["filter_store"]."'  and  `product_id`='".$data["filter_name_id"]."' ";
            $query = $this->db->query($sql);
			try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addproducttrans($data['filter_store'],$data['filter_name_id'],$data['filter_quantity'],'0','CR','SALE-ADDITION','web');  
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
            $log=new Log("quantity-addition-".date('Y-m-d').".log");
            $log->write($sql);
            
        }
        public function insert_into_trans_addition($data=array(),$status) 
        {
            $sql="insert oc_quantity_addition_trans set `store_id`='".$data["filter_store"]."',`quantity`='".$data["filter_quantity"]."',`product_id`='".$data["filter_name_id"]."',`product_name`='".$data["filter_name"]."',`remarks`='".$data["remarks"]."',`logged_user`='".$data["logged_user"]."',`status`='".$status."' ";
            $query = $this->db->query($sql);
            $log=new Log("quantity-addition-".date('Y-m-d').".log");
            $log->write($sql);
            
        }    
        
	
}