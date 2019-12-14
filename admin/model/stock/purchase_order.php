<?php
class ModelStockPurchaseOrder extends Model {

public function getListStore($start,$limit,$sid,$order_id)
    {

        $log=new Log("stockhis-".date('Y-m-d').".log");
        $sql="SELECT
            oc_stock_order.*
            ,'ST' as 'receivetype'
                       
            FROM
            oc_stock_order
            INNER JOIN oc_stock_receive_details
                ON (oc_stock_order.id = oc_stock_receive_details.order_id)
            LEFT JOIN oc_store
                ON (oc_stock_receive_details.supplier_id = oc_store.store_id)
            INNER JOIN ".DB_PREFIX."user
                ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id)
                WHERE oc_stock_receive_details.store_id='".$sid."' ";
                if($order_id!="")
                {
                    $sql.=" and oc_stock_order.id='".$order_id."' ";
                }
	$sql.=" and (oc_stock_order.order_date)>=(select value as reg_date from oc_setting where store_id=oc_stock_receive_details.store_id and `key`='config_registration_date' limit 1 ) ";
                $sql.=" AND oc_stock_order.delete_bit = 1 
                GROUP BY oc_stock_order.id
                ORDER BY oc_stock_order.id DESC
                LIMIT " . $start . "," . $limit;
               
        $query = $this->db->query($sql);
    $log->write($sql);
       
        return $query->rows;
    }




	public function insert_purchase_order($data = array()){
				$totalamount=0;
		$log=new Log("stocklog".date('Y-m-d').".log");
		$log->write($data);	
	//insert order details
		if($data['supplier_id'] != "--Supplier--")
		{
			
			if(!isset($data["stock_fm"]))
			{
			$this->db->query('INSERT INTO oc_stock_order (order_date,user_id,pre_supplier_bit,order_sup_send,recipient_number,transport_id,tax,subtotal) VALUES("' . date('Y-m-d') . '",'.$this->session->data['user_id'].',1,"' . date('Y-m-d') . '","'.$data['recipient_number'].'","'.$data['transport_id'].'","'.$data['tax'].'","'.$data['subtotal'].'")');
			}else {
			$this->db->query('INSERT INTO oc_stock_order (order_date,user_id,pre_supplier_bit,order_sup_send,recipient_number,transport_id,tax,subtotal,trans_type) VALUES("' . date('Y-m-d') . '",'.$this->session->data['user_id'].',1,"' . date('Y-m-d') . '","'.$data['recipient_number'].'","'.$data['transport_id'].'","'.$data['tax'].'","'.$data['subtotal'].'","2")');
			}


			$order_id = $this->db->getLastId();
		}
		else
		{
			if(!isset($data["stock_fm"]))
			{			
			$this->db->query('INSERT INTO oc_stock_order (order_date,user_id,order_sup_send,recipient_number,transport_id,tax,subtotal) VALUES("' . date('Y-m-d') . '",'.$this->session->data['user_id'].',"' . date('Y-m-d') . '","'.$data['recipient_number'].'","'.$data['transport_id'].'","'.$data['tax'].'","'.$data['subtotal'].'")');
			}else {
			$this->db->query('INSERT INTO oc_stock_order (order_date,user_id,pre_supplier_bit,order_sup_send,recipient_number,transport_id,tax,subtotal,trans_type) VALUES("' . date('Y-m-d') . '",'.$this->session->data['user_id'].',1,"' . date('Y-m-d') . '","'.$data['recipient_number'].'","'.$data['transport_id'].'","'.$data['tax'].'","'.$data['subtotal'].'","2")');
			}

			$order_id = $this->db->getLastId();
		}
		
		//insert product details
		
		for($i = 0; $i<count($data['products']); $i++)
		{
			$log->write("INSERT INTO oc_stock_product (product_id,name,quantity,order_id,store_id,store_name,price,tax)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['stores'][$i][0]."','".$data['stores'][$i][1]."','".$data['prices'][$i][1]."','".$data['taxes'][$i][1]."')");

			$this->db->query("INSERT INTO oc_stock_product (product_id,name,quantity,order_id,store_id,store_name,price,tax)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['stores'][$i][0]."','".$data['stores'][$i][1]."','".$data['prices'][$i][1]."','".$data['taxes'][$i][1]."')");

			$product_ids[$i] = $this->db->getLastId();

			//minus qunatity from total and store
			$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity - " . $data['quantity'][$i] . " WHERE product_id = " . $data['products'][$i][0]);
			
						
			$query2 = $this->db->query("UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity - " . $data['quantity'][$i] . " WHERE store_id=".$this->user->getStoreId()." AND product_id = " . $data['products'][$i][0]);
			try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addproducttrans($this->user->getStoreId(),$data['products'][$i][0],$data['quantity'][$i],$order_id,'DB','STOCK TRANSFER');     
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }

                        //upadte current credit
						//get product details
					$queryprd = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_store p2s left join ".DB_PREFIX."product p on p.product_id =p2s.product_id   WHERE store_id=".$this->user->getStoreId()." AND p2s.product_id = " . $data['products'][$i][0]);
					$log->write($queryprd);
					$tax=round($this->tax->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']));
					$totalamount=$totalamount+($data['quantity'][$i]*$queryprd->row['price'])+($data['quantity'][$i]*$tax);
					$log->write($totalamount);				 	
		
	 	
		
		}
		//insert attribute group
		$start_loop = 0; 
		for($j = 0; $j<count($product_ids); $j++)
		{
			for($i = $start_loop; $i<count($data['options']); $i++)
			{
				if($data['options'][$i] != "new product")
				{
					$this->db->query("INSERT INTO oc_stock_attribute_group (attribute_group_id,name,product_id) VALUES(".$data['options'][$i][0].",'".$data['options'][$i][1]."',".$product_ids[$j].")");
					$attribute_group_ids[$i] = $this->db->getLastId();
				}
				else
				{
					$start_loop = $i+1;
					$attribute_group_ids[$i] = "new product";
					break;
				}
			}
		}
		
		$start_loop = 0;
		for($i = 0; $i<count($attribute_group_ids); $i++)
		{
			if($attribute_group_ids[$i] != "new product")
			{
				for($j = $start_loop; $j<count($data['option_values']); $j++)
				{
					if($data['option_values'][$j] != "new product")
					{
						$this->db->query("INSERT INTO oc_stock_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(".$data['option_values'][$j][0].",'".$data['option_values'][$j][1]."',".$attribute_group_ids[$i].")");
						$attribute_category_ids[$j] = $this->db->getLastId();
					}
					else
					{
						$attribute_category_ids[$j] = "new product";
					}
					$start_loop = $j + 1;
					break;
				}
			}
			else
			{
				for($j = $start_loop; $j<count($data['option_values']); $j++)
				{
					if($data['option_values'][$j] != "new product")
					{
						$this->db->query("INSERT INTO oc_stock_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(".$data['option_values'][$j][0].",'".$data['option_values'][$j][1]."',".$attribute_group_ids[$i+1].")");
						$attribute_category_ids[$j] = $this->db->getLastId();
						$i = $i+1;
					}
					else
					{
						$attribute_category_ids[$j] = "new product";
					}
					$start_loop = $j + 1;
					break;
				}
			}
		}
		
		if($data['supplier_id'] != "--Supplier--")
		{
			for($i = 0; $i<count($data['products']); $i++)
			{//quantity,
					$log->write("INSERT INTO oc_stock_receive_details (product_id,supplier_id,order_id,store_id) VALUES(".$data['products'][$i][0].",".$data['supplier_id'][$i].",".$order_id.",'".$data['stores'][$i][0]."')");
				$query = $this->db->query("INSERT INTO oc_stock_receive_details (product_id,supplier_id,order_id,store_id) VALUES(".$data['products'][$i][0].",".$data['supplier_id'][$i].",".$order_id.",'".$data['stores'][$i][0]."')");
			}
		}
		else{
			for($i = 0; $i<count($data['products']); $i++)			{
				$query = $this->db->query("INSERT INTO oc_stock_receive_details (product_id,supplier_id,order_id,store_id) VALUES(".$data['products'][$i][0].",-1,".$order_id.",'".$data['stores'][$i][0]."')");
			}
		}
		//update credit data
		
		if(!empty($totalamount)){
		$log->write("UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit - " . $totalamount . " WHERE store_id=".$this->user->getStoreId());
		$this->db->query("UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit - " . $totalamount . " WHERE store_id=".$this->user->getStoreId());	
		//insert store transaction
			//$sql="insert into  oc_store_trans set `store_id`='".$this->user->getStoreId()."',`amount`='".$totalamount."',`transaction_type`='2',`cr_db`='DB',`user_id`='".$this->session->data['user_id']."' ";
			//$this->db->query($sql);

			try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($totalamount,$this->user->getStoreId(),$this->user->getId(),'DR',$order_id,'ST',$totalamount);  
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
				}

//check for circle credit
			if(isset($data["stock_fm"]))
			{
				

				$this->db->query("UPDATE oc_contractor SET currentcredit = currentcredit + " . $totalamount . " WHERE  store_id='".$this->user->getStoreId()."' and  `circle_code`=".$data["stock_fm"]);	
			for($i = 0; $i<count($data['products']); $i++)
			{
                              $log->write("INSERT INTO oc_contractor_product (product_id,name,quantity,order_id,store_id,store_name,price,tax,contractor_id)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['supplier_id'][$i]."','".$data['stores'][$i][1]."','".$data['prices'][$i][1]."','".$data['taxes'][$i][1]."','".$data["stock_fm"]."') ON DUPLICATE KEY UPDATE quantity=quantity+".$data['quantity'][$i] );

			        $this->db->query("INSERT INTO oc_contractor_product (product_id,name,quantity,order_id,store_id,store_name,price,tax,contractor_id)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['supplier_id'][$i]."','".$data['stores'][$i][1]."','".$data['prices'][$i][1]."','".$data['taxes'][$i][1]."','".$data["stock_fm"]."') ON DUPLICATE KEY UPDATE quantity=quantity+".$data['quantity'][$i] );

				$log->write("INSERT INTO oc_contractor_product_trans (product_id,name,quantity,order_id,store_id,store_name,price,tax,contractor_id,cr_dr,transaction_type)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['supplier_id'][$i]."','".$data['stores'][$i][1]."','".$data['prices'][$i][1]."','".$data['taxes'][$i][1]."','".$data["stock_fm"]."','cr','stock transfer')"  );

			        $this->db->query("INSERT INTO oc_contractor_product_trans (product_id,name,quantity,order_id,store_id,store_name,price,tax,contractor_id,cr_dr,transaction_type)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['supplier_id'][$i]."','".$data['stores'][$i][1]."','".$data['prices'][$i][1]."','".$data['taxes'][$i][1]."','".$data["stock_fm"]."','cr','stock transfer')"  );


			 }
				
			}


		return $order_id;
	}



//
public function getListRecSearch($start,$limit,$sid,$search,$store_id_to)
	{
		$log=new Log("stockhis-".date('Y-m-d').".log");
		$log->write($search);
		if(!empty($search))
		{
			//search text
			if(strlen($search)==10)
			{
				$stext=" AND oc_stock_order.recipient_number = '".$search."'";
			}
			else{
				$stext=" AND oc_stock_order.id='".$search."' ";
			}
		
		}else{
			$stext="";
		}

		$log->write($stext);
		$log->write("SELECT
			oc_stock_order.*
			,'ST' as 'receivetype'

			
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			LEFT JOIN oc_store 
				ON (oc_stock_receive_details.supplier_id = oc_store.store_id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_receive_details.supplier_id='".$sid."' and oc_stock_receive_details.store_id='".$store_id_to."' and   oc_stock_order.order_sup_send <> '0000-00-00'   ".$stext." GROUP BY oc_stock_order.id ORDER BY oc_stock_order.id DESC LIMIT " . $start . "," . $limit);
		$query = $this->db->query("SELECT
			oc_stock_order.*
			,'ST' as 'receivetype'
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			LEFT JOIN oc_store 
				ON (oc_stock_receive_details.supplier_id = oc_store.store_id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_receive_details.supplier_id='".$sid."' and oc_stock_receive_details.store_id='".$store_id_to."'   and   oc_stock_order.order_sup_send <> '0000-00-00' AND oc_stock_order.delete_bit = 1  ".$stext." GROUP BY oc_stock_order.id ORDER BY oc_stock_order.id DESC LIMIT " . $start . "," . $limit);
		
		return $query->rows;
	}
//

public function getListRecSup($start,$limit,$sid,$search)
	{

		$log=new Log("stockhis-".date('Y-m-d').".log");
if(!empty($search))
		{
			//search text
			if(strlen($search)==10)
			{
				$stext=" AND oc_stock_order.recipient_number = '".$search."'";
			}
			else{
				$stext=" AND oc_stock_order.id='".$search."' ";
			}
		
		}else{
			$stext="";
		}


		$query = $this->db->query("SELECT
			oc_stock_order.*
			,'ST' as 'receivetype'
						
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			LEFT JOIN oc_store 
				ON (oc_stock_receive_details.supplier_id = oc_store.store_id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_receive_details.supplier_id='".$sid."' and   oc_stock_order.order_sup_send <> '0000-00-00' AND oc_stock_order.delete_bit = 1  ".$stext." GROUP BY oc_stock_order.id ORDER BY oc_stock_order.id DESC LIMIT " . $start . "," . $limit);
	$log->write("SELECT
			oc_stock_order.*
			,'ST' as 'receivetype'
						
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			LEFT JOIN oc_store 
				ON (oc_stock_receive_details.supplier_id = oc_store.store_id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_receive_details.supplier_id='".$sid."' and   oc_stock_order.order_sup_send <> '0000-00-00' AND oc_stock_order.delete_bit = 1 ".$stext."  GROUP BY oc_stock_order.id ORDER BY oc_stock_order.id DESC LIMIT " . $start . "," . $limit);
		
		return $query->rows;
	}


public function getList_order($oid)
{
	$log=new Log("stockhis-".date('Y-m-d').".log");
	$sql="SELECT receive_bit FROM oc_stock_order where id='".$oid."'";
	$log->write($sql);
	$query = $this->db->query($sql); 
	return $query->row['receive_bit'];

}

	



public function getListRec($start,$limit,$sid)
	{

		$log=new Log("stockhis-".date('Y-m-d').".log");
		$sql="SELECT
			oc_stock_order.*
			,'ST' as 'receivetype'
						
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			LEFT JOIN oc_store 
				ON (oc_stock_receive_details.supplier_id = oc_store.store_id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_receive_details.store_id='".$sid."' and   oc_stock_order.order_sup_send <> '0000-00-00' AND oc_stock_order.delete_bit = 1 AND  oc_stock_order.receive_bit = 0  GROUP BY oc_stock_order.id ORDER BY oc_stock_order.id DESC LIMIT " . $start . "," . $limit;
		$query = $this->db->query($sql);
	$log->write($sql);
		
		return $query->rows;
	}


	public function getList($data)
	{ 
                $sql="SELECT
			oc_stock_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_store.name as 'first_name'
			, '' as 'last_name'
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			LEFT JOIN oc_store 
				ON (oc_stock_receive_details.supplier_id = oc_store.store_id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_order.delete_bit = 1";
		
                        
                if ($data['filter_order_id']!="" ) {
                 $sql .=" and oc_stock_order.id=".$data['filter_order_id'];
			
		}
                if ($data['filter_order_status']!="" ) {
                 $sql .=" and oc_stock_order.receive_bit=".$data['filter_order_status'];
			
		}
                if (!empty($data['filter_date_start']) ) {
                 $sql .=" and oc_stock_order.order_date>='".$data['filter_date_start']."'";
			
		}
                if (!empty($data['filter_date_end']) ) {
                 $sql .=" and oc_stock_order.order_date<='".$data['filter_date_end']."'";
			
		}
                
                        $sql.= " GROUP BY oc_stock_order.id ORDER BY oc_stock_order.id DESC LIMIT " . $data["start"] . "," . $data["limit"];
		//echo $sql;
                        $query = $this->db->query($sql);
		return $query->rows;
	}
	public function getTotalOrders($data)
	{       $sql=$sql="select count(*) as total_orders from (SELECT
			oc_stock_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_store.name as 'first_name'
			, '' as 'last_name'
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			LEFT JOIN oc_store 
				ON (oc_stock_receive_details.supplier_id = oc_store.store_id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_order.delete_bit = 1";
		
                        
                if ($data['filter_order_id']!="" ) {
                 $sql .=" and oc_stock_order.id=".$data['filter_order_id'];
			
		}
                if ($data['filter_order_status']!="" ) {
                 $sql .=" and oc_stock_order.receive_bit=".$data['filter_order_status'];
			
		}
                if (!empty($data['filter_date_start']) ) {
                 $sql .=" and oc_stock_order.order_date>='".$data['filter_date_start']."'";
			
		}
                if (!empty($data['filter_date_end']) ) {
                 $sql .=" and oc_stock_order.order_date<='".$data['filter_date_end']."'";
			
		}
                
                        $sql.= " GROUP BY oc_stock_order.id ORDER BY oc_stock_order.id DESC) as aa ";
		
		$query = $this->db->query($sql);
		$results = $query->row;
		return $results['total_orders'];
	}


public function getOrdersStore($oid)
	{
		$query = $this->db->query("SELECT store_id FROM oc_stock_receive_details WHERE order_id = '" . $oid."' Limit 1" );
		$results = $query->row;
		return $results['store_id'];
	}


	public function view_order_details($order_id)
	{
		$query = $this->db->query("SELECT oc_stock_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
				FROM oc_stock_order
					LEFT JOIN ".DB_PREFIX."user
						ON ".DB_PREFIX."user.user_id = oc_stock_order.user_id
							WHERE id = " . $order_id . " AND delete_bit = " . 1);
		$order_info = $query->row;
		//ON (oc_stock_receive_details.product_id = oc_stock_product.id)
		$query = $this->db->query("SELECT
		oc_stock_product.*,oc_stock_receive_details.quantity as rd_quantity,oc_stock_receive_details.price,oc_stock_supplier.first_name,oc_stock_supplier.last_name,oc_stock_supplier.id as supplier_id,oc_stock_receive_details.order_id
		FROM
			oc_stock_receive_details
		INNER JOIN oc_stock_product 
			ON (oc_stock_receive_details.order_id = oc_stock_product.order_id)
		LEFT JOIN oc_stock_supplier 
			ON (oc_stock_receive_details.supplier_id = oc_stock_supplier.id)
				WHERE (oc_stock_receive_details.order_id =".$order_id.")");
	if($this->db->countAffected() > 0)
	{
		$products = $query->rows;
		$quantities = array();
		$all_quantities = array();
		$prices = array();
		$all_prices = array();
		$suppliers = array();
		$all_suppliers = array();
		$supplier_names = array();
		$all_supplier_names = array();
		$index = 0;
		$index1 = 0;
		for($i =0; $i<count($products); $i++)
		{
			if($products[$i] != "")
			{
				for($j = 0; $j<count($products); $j++)
				{
					if($products[$j] != "")
					{
						if($products[$i]['id'] == $products[$j]['id'])
						{
							$quantities[$index] = $products[$j]['rd_quantity'];
							$supplier_names[$index] = $products[$j]['first_name'] ." ". $products[$j]['last_name'];
							$suppliers[$index] = $products[$j]['supplier_id'];
							$prices[$index] = $products[$j]['price'];
							if($j!=$i)
							{
								$products[$j] = "";
							}
							$index++;
						}
					}
				}
				$index = 0;
				$all_quantities[$index1] = $quantities;
				$all_suppliers[$index1] = $suppliers;
				$all_prices[$index1] = $prices;
				$all_supplier_names[$index1] = $supplier_names;
				unset($quantities);
				unset($suppliers);
				unset($prices);
				unset($supplier_names);
				$quantities = array();
				$suppliers = array();
				$prices = array();
				$supplier_names = array();
				$index1++;
			}
		}
		$products = array_values(array_filter($products));
		for($i = 0; $i<count($products); $i++)
		{
			unset($products[$i]['rd_quantity']);
			unset($products[$i]['first_name']);
			unset($products[$i]['last_name']);
			$products[$i]['quantities'] = $all_quantities[$i];
			$products[$i]['suppliers'] = $all_suppliers[$i];
			$products[$i]['prices'] = $all_prices[$i];
			$products[$i]['supplier_names'] = $all_supplier_names[$i];
		}
	}
	else
	{
		$query = $this->db->query("SELECT * FROM oc_stock_product WHERE order_id = " . $order_info['id']);
		$products = $query->rows;
	}
		$i = 0;
		foreach($products as $product)
		{
			$query = $this->db->query("SELECT * FROM oc_stock_attribute_group WHERE product_id = ". $product['id']);
			$attribute_groups[$i] = $query->rows;
			$i++;
		}
		
		$i = 0;
		foreach($attribute_groups as $attribute_group)
		{
			for($j = 0; $j<count($attribute_group);$j++)
			{
				$query = $this->db->query("SELECT * FROM oc_stock_attribute_category WHERE attribute_group_id = ". $attribute_group[$j]['id']);
				$attribute_categories[$i] = $query->row;
				$i++;
			}
		}
		for($i=0;$i<count($products); $i++)
		{
			for($j=0; $j<count($attribute_groups[$i]);$j++)
			{
				$products[$i]['attribute_groups'][$j] = $attribute_groups[$i][$j]['name'];
			}
		}
		$start_loop = 0;
		//$attribute_categories = array_values(array_filter($attribute_categories));
		//print_r($attribute_categories);
		//exit;
		for($i=0; $i<count($products); $i++)
		{
			for($j=$start_loop; $j<($start_loop + count($products[$i]['attribute_groups']));$j++)
			{
				$products[$i]['attribute_category'][$j] = $attribute_categories[$j]['name'];
			}
			$start_loop = $j;
		}
		$order_information['products'] = $products;
		$order_information['order_info'] = $order_info;
		return $order_information;
	}
	public function delete($ids)
	{
		$deleted = false;
		foreach($ids as $id)
		{
			if($this->db->query("UPDATE oc_stock_order SET delete_bit = " . 0 ." WHERE id = " . $id))
				$deleted = true;
		}
		if($deleted)
		{
			return $deleted;
		}
		else
		{
			return false;
		}
	}
	public function filterCount($filter)
	{
		if(isset($filter['from']))
		{
			$filter['from'] = strtotime($filter['from']);
			$filter['from'] = date('Y-m-d',$filter['from']);
		}
		
		if(isset($filter['to']))
		{
			$filter['to'] = strtotime($filter['to']);
			$filter['to'] = date('Y-m-d',$filter['to']);
		}
		
		$query = "SELECT
			oc_stock_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_stock_supplier.first_name
			, oc_stock_supplier.last_name
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			INNER JOIN oc_stock_supplier 
				ON (oc_stock_receive_details.supplier_id = oc_stock_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_order.delete_bit = 1";
		
		if(isset($filter['filter_id']))
		{
			$query = $query . " AND oc_stock_order.id = " . $filter['filter_id'];
		}
		
		if(isset($filter['status']))
		{
			$query = $query . " AND receive_bit = " . $filter['status'];
		}
		
		if(isset($filter['from']) && isset($filter['to']))
		{
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		elseif(isset($filter['from']))
		{
			$filter['to'] = date('Y-m-d');
			
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		elseif(isset($filter['to']))
		{
			$filter['from'] = date('Y-m-d');
			
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		
		$query = $query . " GROUP BY (oc_stock_order.id) ORDER BY oc_stock_order.id DESC";
		
		$query = $this->db->query($query);
		
		return count($query->rows);
	}
	public function filter($filter,$start,$limit){
		
		if(isset($filter['from']))
		{
			$filter['from'] = strtotime($filter['from']);
			$filter['from'] = date('Y-m-d',$filter['from']);
		}
		
		if(isset($filter['to']))
		{
			$filter['to'] = strtotime($filter['to']);
			$filter['to'] = date('Y-m-d',$filter['to']);
		}
		
		$query = "SELECT
			oc_stock_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_stock_supplier.first_name
			, oc_stock_supplier.last_name
			FROM
			oc_stock_order
			INNER JOIN oc_stock_receive_details
				ON (oc_stock_order.id = oc_stock_receive_details.order_id)
			LEFT JOIN oc_stock_supplier 
				ON (oc_stock_receive_details.supplier_id = oc_stock_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_stock_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_stock_order.delete_bit = 1";
		
		if(isset($filter['filter_id']))
		{
			$query = $query . " AND oc_stock_order.id = " . $filter['filter_id'];
		}
		
		if(isset($filter['status']))
		{
			$query = $query . " AND receive_bit = " . $filter['status'];
		}
		
		if(isset($filter['from']) && isset($filter['to']))
		{
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		elseif(isset($filter['from']))
		{
			$filter['to'] = date('Y-m-d');
			
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		elseif(isset($filter['to']))
		{
			$filter['from'] = date('Y-m-d');
			
			$query = $query . " AND order_date BETWEEN '" . $filter['from'] . "' AND '" . $filter['to'] . "'";
		}
		
		$query = $query . " GROUP BY (oc_stock_order.id) ORDER BY oc_stock_order.id DESC LIMIT ". $start ."," . $limit;
	$log=new Log("receiveorder");
$log->write("sql-".$query);
		$query = $this->db->query($query);

		return $query->rows;
	}

	/*-----------------------------insert receive order function starts here-------------------*/
	
	public function insert_receive_order($received_order_info,$order_id)
	{
		
	$log=new Log("receive-stock-".date('Y-m-d').".log");

		$log->write($received_order_info);
		if($received_order_info['order_receive_date'] != '')
		{
			$received_order_info['order_receive_date'] = strtotime($received_order_info['order_receive_date']);
			$received_order_info['order_receive_date'] = date('Y-m-d',$received_order_info['order_receive_date']);
		}
		$inner_loop_limit = count($received_order_info['received_quantities']);
		$quantities = array();
		$quantity = 0;
		$this->db->query("UPDATE oc_stock_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 1 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);
		$log->write("update order info done");		
		//if pre selected supplier
		if(count($received_order_info['received_quantities']) != count($received_order_info['suppliers_ids']))
		{
		
			$log->write('in if');
			for($i =0; $i<count($received_order_info['prices']); $i++)
			{
				if($received_order_info['prices'][$i] != "next product")
				{
					$prices[$i] = $received_order_info['prices'][$i];
				}
			}
			
			$log->write($received_order_info['received_quantities']);
			for($i =0; $i<count($received_order_info['received_quantities']); $i++)
			{
				if($received_order_info['received_quantities'][$i] != "next product")
				{
					$received_quantities[$i] = $received_order_info['received_quantities'][$i];
				}
			}
			
			$prices = array_values($prices);
			$received_quantities = array_values($received_quantities);
		
			$log->write($prices);		
			$log->write("after price");
			$log->write($received_quantities);		


			for($i =0; $i<count($received_quantities); $i++)
			{
			$log->write("in for loop");

				$log->write("UPDATE oc_stock_receive_details SET price =" .$prices[$i]. ", quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
				$this->db->query("UPDATE oc_stock_receive_details SET  quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
				$query = $this->db->query("SELECT quantity FROM oc_stock_receive_details WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id =" . $order_id);
				$quantities[$i] = $query->row['quantity'];
			$log->write("quantity");	
			$log->write($quantities[$i]);	
			}
		}
		else
		{
			$log->write('in else');
			$query = $this->db->query("SELECT * FROM oc_stock_receive_details WHERE order_id=".$order_id);
					$log->write("update order info done select ".$query);
			if(count($query->rows) > 0)
			{
				$this->db->query("DELETE FROM oc_stock_receive_details WHERE order_id=".$order_id);
			}
		
			for($j = 0; $j<count($received_order_info['received_product_ids']); $j++)
			{
				for($k = 0; $k<$inner_loop_limit; $k++)
				{
					
					if($received_order_info['received_quantities'][$k] != 'next product')
					{
						//"INSERT INTO oc_stock_receive_details (quantity,price,product_id,supplier_id,order_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['prices'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.")"
						$this->db->query("INSERT INTO oc_stock_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.",".$query->rows[$j]["store_id"].")");
						$quantity = $quantity + $received_order_info['received_quantities'][$k];
						unset($received_order_info['received_quantities'][$k]);
						unset($received_order_info['suppliers_ids'][$k]);
						unset($received_order_info['prices'][$k]);
					}
					else
					{
						unset($received_order_info['received_quantities'][$k]);
						unset($received_order_info['suppliers_ids'][$k]);
						unset($received_order_info['prices'][$k]);
						$received_order_info['received_quantities'] = array_values($received_order_info['received_quantities']);
						$received_order_info['suppliers_ids'] = array_values($received_order_info['suppliers_ids']);
						$received_order_info['prices'] = array_values($received_order_info['prices']);
						break;
					}
				}
				$quantities[$j] = $quantity;
				$quantity = 0;
			}
		}
		$bool = false;
		for($i=0; $i<count($quantities); $i++)
		{
			$query = $this->db->query("SELECT DISTINCT product_id FROM oc_stock_product WHERE product_id = " . $received_order_info['received_product_ids'][$i]);
			$product_ids[$i] = $query->row;
			$query1 = $this->db->query("UPDATE oc_stock_product SET received_products = " . $quantities[$i] . " WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
		}
				$totalamount=0;
		for($i=0; $i<count($product_ids); $i++)
		{
			
			$log->write("SELECT quantity FROM ".DB_PREFIX."product_to_store WHERE store_id=".$this->user->getStoreId()." AND product_id = " . $product_ids[$i]['product_id']);
			$query = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product_to_store WHERE store_id=".$this->user->getStoreId()." AND product_id = " . $product_ids[$i]['product_id']);
			$quantity =  $quantities[$i];//$query->row['quantity'] ;''+
			$log->write("UPDATE ".DB_PREFIX."product SET quantity = quantity + " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id']);
	
			$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity + " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id']);
			
			$log->write("UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " WHERE store_id=".$this->user->getStoreId()." AND product_id = " . $product_ids[$i]['product_id']);
			
			$query2 = $this->db->query("UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " WHERE store_id=".$this->user->getStoreId()." AND product_id = " . $product_ids[$i]['product_id']);
			try{
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addproducttrans($this->user->getStoreId(),$product_ids[$i]['product_id'],$quantity,$order_id,'CR','STOCK REC');
                        } catch (Exception $ex) {
                            $log->write($ex->getMessage());
                        }
                        
			$log->write("no product");
			$log->write($query2);
			if($query2->num_rows==0)
			{	
				$log->write("insert into  ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " , store_id=".$this->user->getStoreId()." ,product_id = " . $product_ids[$i]['product_id']);
				$this->db->query("insert into  ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " , store_id=".$this->user->getStoreId()." ,product_id = " . $product_ids[$i]['product_id']);

			}
			
			if($query && $query1 && $query2)
				{
					$log->write("before credit change in ");
					$log->write("SELECT * FROM ".DB_PREFIX."product_to_store p2s left join ".DB_PREFIX."product p on p.product_id =p2s.product_id   WHERE store_id=".$this->user->getStoreId()." AND p2s.product_id = " . $product_ids[$i]['product_id']);
					//upadte current credit
						//get product details
					$queryprd = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_store p2s left join ".DB_PREFIX."product p on p.product_id =p2s.product_id   WHERE store_id=".$this->user->getStoreId()." AND p2s.product_id = " . $product_ids[$i]['product_id']);
					$log->write($queryprd);
					$tax=round($this->tax->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']));
					$totalamount=$totalamount+($quantity*$queryprd->row['price'])+($quantity*$tax);
					$log->write($totalamount);

				}
			if($query && $query1 && $query2)
				$bool = true;
		}
				if($bool)
			{
				//update credit price
				$log->write("UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit + " . $totalamount . " WHERE store_id=".$this->user->getStoreId());
				$this->db->query("UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit + " . $totalamount . " WHERE store_id=".$this->user->getStoreId());	
				//insert store transaction
				//$sql="insert into  oc_store_trans set `store_id`='".$this->user->getStoreId()."',`amount`='".$totalamount."',`transaction_type`='1',`cr_db`='CR',`user_id`='".$this->session->data['user_id']."' ";
				//$log->write($sql);
				//$this->db->query($sql); 
				 try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($totalamount,$this->user->getStoreId(),$this->user->getId(),'CR',$order_id,'SR',$totalamount);  
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
			}
		if($bool)
			return true;
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
	public function getcontractor_transactions($circle_code,$cr_dr,$limit_start,$limit_end)
	{ 
	if($limit_start=="-1"){ $limit_start="0"; }if($limit_start=="1"){ $limit_start="0"; }
	$sql="select * from oc_contractor_product_trans where contractor_id ='".$circle_code."' ";
	   if($cr_dr!="")
	{
	$sql.=" and cr_dr='".$cr_dr."' ";
	}
	$sql.=" limit ".$limit_start.",".$limit_end;  

		$log=new Log("receiveorderstock.log");
		$log->write($sql);
		$query = $this->db->query($sql); 

		return $query->rows;
	}
	public function get_store_quantity($product_id,$suplier_id)
	{ 
	
	$sql=" SELECT quantity FROM `oc_product_to_store` WHERE `product_id` = '".$product_id."' AND `store_id` = '".$suplier_id."'  ";
	   

		$log=new Log("requeststock-".date('Y-m-d').".log");
		$log->write($sql);
		$query = $this->db->query($sql); 

		return $query->row["quantity"]; 
	}
	public function getproductQnty($data)
	{
		$sql="SELECT * FROM oc_stock_product WHERE product_id='".$data['product_id']."' and order_id =".$data['order_id']." limit 1 ";
				
		$query = $this->db->query($sql);
		 $log=new Log("receive-stock-".date('Y-m-d').".log");
		$log->write($sql);
		return $query->row; 
	}
}
?>