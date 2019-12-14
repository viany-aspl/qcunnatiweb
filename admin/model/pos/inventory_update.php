<?php
////////this file is for inventory match
class ModelPosInventoryUpdate extends Model 
{
	
        
    public function getandupdatequantity() 
	{
		
		
		/*$order_query = $this->db->query("SELECT op.product_id as product_id,o.order_id as order_id,op.quantity as quantity,op.name as product_name,o.store_id as store_id,op.ORD_DATE as ORD_DATE FROM oc_order_product as op left join oc_order as o on op.order_id=o.order_id WHERE o.store_id=61 AND date(o.date_added)='2018-03-27' and o.order_status_id=5  ");
		
		$order_product_rows=$order_query->rows;
		
		foreach($order_product_rows as $order_product_row)
		{
			$trans_query = $this->db->query("select * from oc_product_trans where store_id='".$order_product_row['store_id']."' and product_id='".$order_product_row['product_id']."' and order_id='".$order_product_row['order_id']."' ");
			$data=array();
			$product_trans_rows=$trans_query->rows;
			if(count($product_trans_rows)==0) ///means data for the order_ and store_id and product_id
			{
				
				//echo $order_product_row['store_id'].", ".$order_product_row['product_id']." , ".$order_product_row['order_id']." , ".$order_product_row['quantity'];
				//echo '<br/>';
				$data=array('order_id'=>$order_product_row['order_id'],'store_id'=>$order_product_row['store_id'],'web_app'=>'web','ORD_DATE'=>$order_product_row['ORD_DATE']);
				$this->updateinventory($data);
			}
		}
		$log=new Log('updateinventory-error-'.date('Y-m-d',strtotime($order_product_rows[0]['ORD_DATE'])).'.log');
		$log->write('done');*/
		echo 'done';
	}
	public function updateinventory($data)
{
    $log=new Log('updateinventory-error-'.date('Y-m-d',strtotime($data['ORD_DATE'])).'.log');
    //quantity  to update in store
	$log->write('updateinventory called from : '.$data['web_app']);
    $getsql="select * from oc_order_product WHERE order_id = '" . (int)$data['order_id'] . "' ";
    $log->write($getsql);
    $getsqlres=$this->db->query($getsql);
    $product_rows=$getsqlres->rows;

    if (!empty($product_rows)) {		
      		foreach ($product_rows as $order_product) 
			{	
			//quantity  to update in store
            $sqlpdeduct="UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'";
	        $log->write($sqlpdeduct);
	        $this->db->query($sqlpdeduct);

            $sqlpdeduct2="UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND store_id = '".(int)$data['store_id']."'";
	        $log->write($sqlpdeduct2);
	        $this->db->query($sqlpdeduct2);

			try
			{ 
               $p_sql = " insert into oc_product_trans set billing_type='".$data['web_app']."',store_id='".$data['store_id']."',product_id ='".$order_product['product_id']."',quantity='".$order_product['quantity']."',trans_time='".$data['ORD_DATE']."',order_id='".$data['order_id']."',cr_db='DB',trans_type='SALE',current_quantity='0'  ";
			$log->write($p_sql);
			$query = $this->db->query($p_sql);  
					
			} 
			catch (Exception $e)
			{
                $log->write($e->getMessage());
				
			}
        }
    }
}

	
}
?>