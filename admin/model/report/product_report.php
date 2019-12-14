<?php
class ModelReportProductReport extends Model {
	
	   
        public function getsale($store_id,$product_id)       
        {
            $date=date('Y-m-d');
            $sql="SELECT (sum(op.total)+(sum(op.quantity)*(op.tax))) as total,sum(op.quantity) as quantity FROM `oc_order_product` op join `oc_order` o on o.order_id=op.order_id where o.store_id='".$store_id."' and op.product_id='".$product_id."' and date(op.ORD_DATE)='".$date."' group by op.product_id";
            //echo $sql;
            $query = $this->db->query($sql);                
	    return $query->row;
        }
        public function getproductquantitybystore($store_id,$product_id)
        {
         $sql="SELECT quantity FROM `oc_product_to_store` where product_id='".$product_id."' and store_id='".$store_id."'";   
         $query = $this->db->query($sql);                
	 return $query->row;
        }
        public function getSalesquantitybystore($store_id,$product_id)
        {
         $sql="SELECT quantity FROM `oc_product_to_store` where product_id='".$product_id."' and store_id='".$store_id."'";   
         $query = $this->db->query($sql);                
	 return $query->row;
        }
        public function getproducts()
        {
         $sql="SELECT product_id,model FROM `oc_product`  order by model asc ";   
         $query = $this->db->query($sql);                
	 return $query->rows;
        }
	
	
}