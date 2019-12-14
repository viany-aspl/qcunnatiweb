<?php
class ModelSubsidySubsidy extends Model {
	
	
        public function submit_form($data=array())
        {
            $log=new Log('subsidy_'.date('Y-m-d').".log");
            $sql="SELECT product_subsidy_id FROM oc_product_subsidy where product_id='".$data['filter_name_id']."' and  store_id='".$data['filter_store']."' and `category_id`='".$data["filter_category"]."'  limit 1  ";
            $query = $this->db->query($sql);
            $arrray=$query->row;
            $num_res=count($arrray);
            
            $set_date=date('Y-m-d');
            if($num_res>0)
            {
            $update_to_id=$arrray["product_subsidy_id"];
            $sql2="update `oc_product_subsidy` set  `store_id`='".$data["filter_store"]."',`product_id`='".$data['filter_name_id']."',`customer_group_id`='1',`quantity`='1'"
                    . ",`priority`='1',`subsidy`='".$data["subsidy"]."',`category_id`='".$data["filter_category"]."',`date_start`='".$set_date."',`date_end`='".$set_date."' where product_subsidy_id='".$update_to_id."' ";
            $log->write($sql2);
            $query = $this->db->query($sql2);
            
            }
            else
            {
                $sql2="insert into  `oc_product_subsidy` set  `store_id`='".$data["filter_store"]."',`product_id`='".$data['filter_name_id']."',`customer_group_id`='1',`quantity`='1'"
                    . ",`priority`='1',`subsidy`='".$data["subsidy"]."',`category_id`='".$data["filter_category"]."',`date_start`='".$set_date."',`date_end`='".$set_date."'  ";
                
                $log->write($sql2);
                $query = $this->db->query($sql2);
            }
          
        }
         public function get_subsidy($data=array())
        {
            $sql="SELECT oc_product_subsidy.*,oc_store.name as store_name,oc_product.model as product_name,oc_category_subsidy.category_name as category_name FROM oc_product_subsidy left join oc_store on oc_store.store_id=oc_product_subsidy.store_id left join oc_product on oc_product.product_id=oc_product_subsidy.product_id left join oc_category_subsidy on oc_product_subsidy.category_id=oc_category_subsidy.category_id where oc_product_subsidy.subsidy > '0' AND oc_product_subsidy.store_id";
            
            if (!empty($data['filter_store']) ) {
               $sql .=" ='".$data['filter_store']."' ";
			
		}
            else {
                $sql .=" >'0' ";
            }
                 if (!empty($data['filter_name_id']) ) {
               $sql .=" and oc_product_subsidy.product_id ='".$data['filter_name_id']."' ";
			
		}
	 if (!empty($data['filter_category']) ) {
               $sql .=" and oc_product_subsidy.category_id ='".$data['filter_category']."' ";
			
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
               // echo $sql;
                
		$query = $this->db->query($sql);
                
		return $query->rows;
        }
        public function get_Totalsubsidy($data=array())
        {
              $sql="select count(*) as total from ( SELECT oc_product_subsidy.*,oc_store.name as store_name,oc_product.model as product_name FROM oc_product_subsidy join oc_store on oc_store.store_id=oc_product_subsidy.store_id join oc_product on oc_product.product_id=oc_product_subsidy.product_id where oc_product_subsidy.subsidy > '0' AND oc_product_subsidy.store_id";
            
            if (!empty($data['filter_store']) ) {
               $sql .=" ='".$data['filter_store']."' ";
			
		}
            else {
                $sql .=" >'0' ";
            }
                 if (!empty($data['filter_name_id']) ) {
               $sql .=" and oc_product_subsidy.product_id ='".$data['filter_name_id']."' ";
			
		}
	 if (!empty($data['filter_category']) ) {
               $sql .=" and oc_product_subsidy.category_id ='".$data['filter_category']."' ";
			
		}
                $sql.=" ) as aa";
                //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->row["total"];
        }
        public function updSubsidyZero($storeid,$product,$category)
        {
            $log=new Log('updateSubsidyZero-'.date('Y-m-d').'.log');
           $sql="update `oc_product_subsidy` set `subsidy`='0' where store_id='".$storeid."' and product_id='".$product."' and category_id='".$category."' ";
            $log->write($sql);
            $this->db->query($sql);
        //return $this->db->getLastId;
                return 1;
        
        }
         public function updProductSubsidyZero($storeid)
        {
            $log=new Log('updateProductSubsidyZero-'.date('Y-m-d').'.log');
          echo  $sql="update `oc_product_subsidy` set `subsidy`='0' where store_id='".$storeid."'";
            $log->write($sql);
            $this->db->query($sql);
        //return $this->db->getLastId;
                return 1;
        
        }
	public function getsubsidycategory($data)
        {
            $sql="SELECT oc_product_subsidy.category_id,oc_category_subsidy.category_name FROM oc_product_subsidy join `oc_category_subsidy` on `oc_category_subsidy`.category_id=oc_product_subsidy.category_id where oc_product_subsidy.store_id='".$data['store_id']."' group by oc_product_subsidy.category_id ";

		$log=new Log("getsubsidycategory-".date('Y-m-d').".log");
        $log->write($sql);
            $query = $this->db->query($sql);
               
            return $query->rows;
        }
        public function getsubsidycategory_products($data,$cat_id)
        {
            $sql=" select oc_product_subsidy.product_id,oc_product.model as product_name,oc_product_subsidy.subsidy as subsidy from oc_product_subsidy left join oc_product on oc_product_subsidy.product_id=oc_product.product_id where oc_product_subsidy.store_id='".$data['store_id']."' and oc_product_subsidy.category_id='".$cat_id."' ";
	$log=new Log("getsubsidycategory-".date('Y-m-d').".log");
             $log->write($sql);
            $query=$this->db->query($sql);
            return $query->rows;
        }

	public function getcategories()
        {
            $sql=" SELECT * FROM `oc_category_subsidy` ";
            $query=$this->db->query($sql);
            return $query->rows;
        }
	
}