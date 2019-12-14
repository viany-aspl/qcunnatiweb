<?php
class ModelInventoryReturnorder extends Model {
	public function insert_return_order($data = array()){
		$log=new Log('po-return-'.date('Y-m-d').".log");
                 $log->write('in model');
                $store= $data['store'];
                $username= $data['username'];
                $remarks= $data['remarks'];
                //INSERT INTO `shop`.`oc_po_return` (`order_id`, `product_id`, `store_id`, `supplier_id`, `return_quantity`, `reason`, `return_date`, `delete_bit`, `user_id`) VALUES ('0', '67', '67', '0', '7', 'hjk', '2017-08-08', false, '9');

		//insert order details
                $log->write($data);
                $order_id="";
		for($i=0;$i<count($data['products']);$i++)
                {
                   $product_id= $data['products'][$i];
                   $product_quantity= $data['quantity'][$i];
                   
                   $sql="INSERT INTO `oc_po_return` (`order_id`, `product_id`, `store_id`, `supplier_id`, `return_quantity`, `reason`, `return_date`, `delete_bit`, `user_id`) VALUES ('0', '".$this->db->escape($product_id)."', '".$this->db->escape($store)."', '0', '".$this->db->escape($product_quantity)."', '".$this->db->escape($remarks)."', '".date('Y-m-d')."', false, '".$this->db->escape($username)."')";
                   $log->write($sql);
                   $query = $this->db->query($sql);
                   $insert_id=$this->db->getLastId();
                   $log->write($insert_id);
                   
                   $inv_db_query="UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$product_quantity . ") WHERE product_id = '" . (int)$product_id . "' AND store_id = '".(int)$store."'";
                   $log->write($inv_db_query);
		$this->db->query($inv_db_query);
				
                $log->write('call trans');
                   $this->load->library('trans');
                   $trans=new trans($this->registry);
                   $trans->addproducttrans($store,$product_id,$product_quantity,$insert_id,'DB','PORETURN');  
               
                   
                   if($order_id=="")
                   {
                       $order_id=$insert_id;
                   }
                   else
                   {
                       $order_id=$order_id.",".$insert_id;
                   }
                }
		
		return $order_id;
	}
	

}
?>