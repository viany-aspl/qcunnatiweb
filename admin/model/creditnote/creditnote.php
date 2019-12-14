<?php
class ModelCreditnoteCreditnote extends Model {
public function get_to_store_data_b2b($store_id)
        {
           $store_data='';
           $sql="SELECT `value`  from oc_setting where `key`='config_Account_Holder_name' and store_id =".$store_id;        
           $query = $this->db->query($sql);
           $store_data=$query->row['value'];          
           
           $sql2="SELECT `value`  from oc_setting where `key`='config_address' and store_id =".$store_id;
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
          
           $sql3="SELECT `value`  from oc_setting where `key`='config_email' and store_id =".$store_id;
           $query3= $this->db->query($sql3);
           $store_data=$store_data."---".$query3->row['value'];
          
           
           $sql4="SELECT `value`  from oc_setting where `key`='config_telephone' and store_id =".$store_id;
           $query4 = $this->db->query($sql4);
           $store_data=$store_data."---".$query4->row['value'];  
           
           $sql5="SELECT `value`  from oc_setting where `key`='config_gstn' and store_id =".$store_id;
           $query5= $this->db->query($sql5);
           $store_data=$store_data."---".$query5->row['value'];
        
           return $store_data;
          
        }   	
    
public function getOrders($data = array()) {
		$sql = "SELECT concat(oc_credit_order.credit_prefix,'/'
                        ,oc_credit_order.id) as credit_no,DATE(oc_credit_order.order_date) as cr_date
                        ,oc_store.name as store_name
                        ,oc_credit_order.total_amount 
                        ,oc_credit_order.id
                        FROM oc_credit_order
                        left join oc_store on oc_store.store_id=oc_credit_order.store_id where oc_credit_order.id!=''";

		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_credit_order.order_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_credit_order.order_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
	
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND oc_credit_order.store_id='".(int)$data['filter_store']."'";
                }
		$sql.=" ORDER BY oc_credit_order.id DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= "  LIMIT " . (int)$data['start'] . "," . (int)$data['limit'] ;
			
		}
               // echo $sql; 
		$query = $this->db->query($sql);

		return $query->rows;
	}
        public function getOrderstotal($data = array()) 
        {
           	$sql = "select COUNT(*) as total from (SELECT concat(oc_credit_order.credit_prefix,'/'
                        ,oc_credit_order.id) as credit_no,DATE(oc_credit_order.order_date) as cr_date
                        ,oc_store.name as store_name
                        ,oc_credit_order.total_amount 
                        ,oc_credit_order.id
                        FROM oc_credit_order
                        left join oc_store on oc_store.store_id=oc_credit_order.store_id where oc_credit_order.id!=''";

			
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_credit_order.order_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_credit_order.order_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
	
                if (!empty($data['filter_store'])) 
                {
                $sql .=" AND oc_credit_order.store_id='".(int)$data['filter_store']."'";
                }
		
		
                $sql .=") as aa";
                //echo $sql; 
		$query = $this->db->query($sql);

		return $query->row['total'];
        }
        public function insert_credit_order($store,$grand_total)
        {
             $sql1="insert into  oc_credit_order set order_date=NOW(),store_id='".$store."',total_amount='".$grand_total."',credit_prefix='ASPL/CN' ";
             $query1 = $this->db->query($sql1);
             $order_id=$this->db->getLastId();
             return $order_id;

        }
        public function submit_creditnote_data($rem,$store,$p_amount,$rate,$qty,$activity,$updated_by,$insert_d) 
        {
                        
            
            $sql="insert into  oc_credit_note set remarks='".$rem."',store_id='".$store."',amount='".$p_amount."',rate='".$rate."',qty='".$qty."',activity='".$activity."',user_id='".$updated_by."',order_id='".$insert_d."',create_date=NOW() ";
            $query = $this->db->query($sql);
             


        }
//        public function update_store_currentlimit($store,$grand_total)
//        {
//            $sql2="update  oc_store set currentcredit=currentcredit +'".$grand_total."' where store_id='".$store."'";
//            $query2 = $this->db->query($sql2);
//
//        }
         public function view_credit_details($credit_no)
	{       $sql="select oc_credit_order.id
                      ,oc_credit_order.credit_prefix
                      ,DATE(oc_credit_order.order_date) as o_date
                      ,oc_credit_order.total_amount
                      ,oc_credit_order.store_id
                      ,oc_credit_note.qty
                      ,oc_credit_note.remarks
                      ,concat(oc_credit_order.credit_prefix,'/',oc_credit_order.id) as creditno
                      ,oc_credit_note.rate
                      ,oc_credit_note.amount
                      ,oc_credit_note.activity
                      from oc_credit_order
                      left join oc_credit_note on  oc_credit_note.order_id=oc_credit_order.id
                      where id='".$credit_no."'";
                          
		$query = $this->db->query($sql);
		$order_info = $query->rows;
                
                return $order_info;     
        }
        
        



}
