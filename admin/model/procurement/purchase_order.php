<?php
class ModelProcurementPurchaseOrder extends Model {
    
    
	public function getList($data)
	{ //print_r($data);
                $sql="SELECT
			oc_po_order.*,'2,4' as store_type
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
                        , oc_store.name as store_name
                        , oc_store.creditlimit as creditlimit
                        , oc_store.currentcredit as currentcredit
                      	,oc_po_product.name as product
			,oc_po_product.quantity
			FROM
			oc_po_order
			LEFT JOIN
oc_po_product ON (oc_po_order.id= oc_po_product.order_id)
			LEFT JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
                        LEFT JOIN oc_store
                ON (oc_po_receive_details.store_id = oc_store.store_id)
			LEFT JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			LEFT JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.id != ''  ";
		
                $sql.=" and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('2','4')) ";
                // ,(select name from oc_po_order_status where oc_po_order_status.order_status_id=oc_po_order.order_status_id) as order_status
                if(!empty($data['filter_status']))
                {
                    if(($data['filter_status']=="0") || ($data['filter_status']=="1"))
                    {
                    $sql.=" and oc_po_order.receive_bit='".$data['filter_status']."' and oc_po_order.canceled_message==''  ";
                    }
                    if($data['filter_status']=="3")
                    {
                    $sql.=" and oc_po_order.canceled_message!='' ";
                    }
                }
                
                if(!empty($data['filter_date_start']))
                {
                    $sql.=" and oc_po_order.order_date>='".$data['filter_date_start']."' ";
                }
                if(!empty($data['filter_date_end']))
                {
                    $sql.=" and oc_po_order.order_date<='".$data['filter_date_end']."' ";
                }
                if(!empty($data['filter_id']))
                {
                    $sql.=" and oc_po_order.id='".$data['filter_id']."' ";
                }
	  if (!empty($data['filter_store'])) {
            		$sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        	}
                $sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC";
                if($data['start']>=0)
                {
                $sql.=" LIMIT " . $data['start'] . "," . $data['limit'];
                }
                //echo $sql;
                //echo $data['start'];
                $query = $this->db->query($sql);
		
		return $query->rows;
	}
	public function getTotalOrders($data)
	{
                $sql="select count(*) as total_orders from (SELECT
			oc_po_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
                        , oc_store.name as store_name
                        , oc_store.creditlimit as creditlimit
                        , oc_store.currentcredit as currentcredit
                      
			FROM
			oc_po_order
			LEFT JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
                        LEFT JOIN oc_store
                ON (oc_po_receive_details.store_id = oc_store.store_id)
			LEFT JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			LEFT JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.id != ''  ";
		
                $sql.=" and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('2','4')) ";
                //  ,(select name from oc_po_order_status where oc_po_order_status.order_status_id=oc_po_order.order_status_id) as order_status
                if(!empty($data['filter_status']))
                {
                    if(($data['filter_status']=="0") || ($data['filter_status']=="1"))
                    {
                    $sql.=" and oc_po_order.receive_bit='".$data['filter_status']."' and oc_po_order.canceled_message==''  ";
                    }
                    if($data['filter_status']=="3")
                    {
                    $sql.=" and oc_po_order.canceled_message!='' ";
                    }
                }
                
                if(!empty($data['filter_date_start']))
                {
                    $sql.=" and oc_po_order.order_date>='".$data['filter_date_start']."' ";
                }
                if(!empty($data['filter_date_end']))
                {
                    $sql.=" and oc_po_order.order_date<='".$data['filter_date_end']."' ";
                }
                if(!empty($data['filter_id']))
                {
                    $sql.=" and oc_po_order.id='".$data['filter_id']."' ";
                }
	if (!empty($data['filter_store'])) {
            		$sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        	}
                $sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC";
                if($data['start']>=0)
                {
                $sql.=" LIMIT " . $data['start'] . "," . $data['limit'];
                }
                $sql.=" ) as aa";
                //echo $sql;
                //echo $data['start'];
                $query = $this->db->query($sql);
		
		return $query->row['total_orders'];
	}
        public function getStatuses()
        {
            //return $user_group_id;
            $sql="SELECT * FROM oc_po_order_status  ";
            $query = $this->db->query($sql);
            return $query->rows;
		
        }
        
    
    public function view_order_details($order_id)
	{
        
		$query = $this->db->query("SELECT oc_po_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
                                        
                                        FROM oc_po_order
					LEFT JOIN ".DB_PREFIX."user
						ON ".DB_PREFIX."user.user_id = oc_po_order.user_id
                                                    
							WHERE id = " . $order_id );
		
        $order_info = $query->row;

		$view_order_details="SELECT
        oc_po_product.*,oc_po_receive_details.quantity as rd_quantity,
                oc_product.price as price_p,
                oc_po_receive_details.price,
                oc_po_supplier.first_name,
                oc_po_supplier.last_name,oc_po_supplier.id as supplier_id,
                oc_po_receive_details.order_id
        FROM
            oc_po_receive_details
        LEFT JOIN oc_po_product
            ON (oc_po_receive_details.order_id = oc_po_product.order_id)
            AND (oc_po_receive_details.product_id = oc_po_product.product_id)
        LEFT JOIN oc_po_supplier
            ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
            LEFT JOIN
    oc_product ON (oc_product.product_id = oc_po_product.product_id)
                WHERE (oc_po_receive_details.order_id =".$order_id.")";
	
		$query = $this->db->query($view_order_details);

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
                                                        $prices_p[$index] = $products[$j]['price_p'];
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
                                $all_prices_p[$index1] = $prices_p;
				$all_supplier_names[$index1] = $supplier_names;
				unset($quantities);
				unset($suppliers);
				unset($prices);
                                unset($prices_p);
				unset($supplier_names);
				$quantities = array();
				$suppliers = array();
				$prices = array();
                                $prices_p = array();
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
                        $products[$i]['prices_p'] = $all_prices_p[$i];
			$products[$i]['supplier_names'] = $all_supplier_names[$i];
		}
	}
	else
	{
		$query = $this->db->query("SELECT * FROM oc_po_product WHERE order_id = " . $order_info['id']);
		$products = $query->rows;
	}
		$i = 0;
		foreach($products as $product)
		{
			$query = $this->db->query("SELECT * FROM oc_po_attribute_group WHERE product_id = ". $product['id']);
			$attribute_groups[$i] = $query->rows;
			$i++;
		}
		
		$i = 0;
		foreach($attribute_groups as $attribute_group)
		{
			for($j = 0; $j<count($attribute_group);$j++)
			{
				$query = $this->db->query("SELECT * FROM oc_po_attribute_category WHERE attribute_group_id = ". $attribute_group[$j]['id']);
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
		//print_r($order_information);
		return $order_information;
	}
        public function view_order_details_by_account($order_id)
	{
		$sqlll1="SELECT oc_po_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
                                        FROM oc_po_order
					LEFT JOIN ".DB_PREFIX."user
						ON ".DB_PREFIX."user.user_id = oc_po_order.user_id
                                                    
							WHERE id = " . $order_id ;
		$query = $this->db->query($sqlll1);
		
        $order_info = $query->row;

		$view_order_details="SELECT
        oc_po_product.*,oc_po_receive_details.quantity as rd_quantity,
                oc_po_receive_details.price,oc_po_supplier.first_name,
                oc_po_supplier.last_name,oc_po_supplier.id as supplier_id,
                oc_po_receive_details.order_id
        FROM
            oc_po_receive_details
        LEFT JOIN oc_po_product
            ON (oc_po_receive_details.order_id = oc_po_product.order_id)
            AND (oc_po_receive_details.product_id = oc_po_product.product_id)
        LEFT JOIN oc_po_supplier
            ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
            LEFT JOIN
    oc_product ON (oc_product.product_id = oc_po_product.product_id)
                WHERE (oc_po_receive_details.order_id =".$order_id.")";
	
		$query = $this->db->query($view_order_details);

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
		$query = $this->db->query("SELECT * FROM oc_po_product WHERE order_id = " . $order_info['id']);
		$products = $query->rows;
	}
		$i = 0;
		foreach($products as $product)
		{
			$query = $this->db->query("SELECT * FROM oc_po_attribute_group WHERE product_id = ". $product['id']);
			$attribute_groups[$i] = $query->rows;
			$i++;
		}
		
		$i = 0;
		foreach($attribute_groups as $attribute_group)
		{
			for($j = 0; $j<count($attribute_group);$j++)
			{
				$query = $this->db->query("SELECT * FROM oc_po_attribute_category WHERE attribute_group_id = ". $attribute_group[$j]['id']);
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
        public function view_order_details_by_supplier($order_id,$supplier_id)
	{
        
		$query = $this->db->query("SELECT oc_po_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
                                        FROM oc_po_order
					LEFT JOIN ".DB_PREFIX."user
						ON ".DB_PREFIX."user.user_id = oc_po_order.user_id
                                                    
							WHERE id = " . $order_id );
		
        $order_info = $query->row;
		//print_r($order_info);
		$view_order_details="SELECT
        oc_po_product.*,oc_po_receive_details.quantity as rd_quantity,
                oc_product.price as price_p,
                oc_po_receive_details.price,
                oc_po_supplier.first_name,
                oc_po_supplier.email as supplier_email,
                oc_po_supplier.last_name,oc_po_supplier.id as supplier_id,
                oc_po_receive_details.order_id
        FROM
            oc_po_receive_details
        LEFT JOIN oc_po_product
            ON (oc_po_receive_details.order_id = oc_po_product.order_id)
            AND (oc_po_receive_details.product_id = oc_po_product.product_id)
        LEFT JOIN oc_po_supplier
            ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
            LEFT JOIN
    oc_product ON (oc_product.product_id = oc_po_product.product_id)
                WHERE (oc_po_receive_details.order_id =".$order_id.") "
                        . " and oc_po_supplier.id='".$supplier_id."' ";
	
		$query = $this->db->query($view_order_details);

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
                                $all_prices_p[$index1] = $prices_p;
				$all_supplier_names[$index1] = $supplier_names;
				unset($quantities);
				unset($suppliers);
				unset($prices);
                                unset($prices_p);
				unset($supplier_names);
				$quantities = array();
				$suppliers = array();
				$prices = array();
                                $prices_p = array();
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
                        $products[$i]['prices_p'] = $all_prices_p[$i];
			$products[$i]['supplier_names'] = $all_supplier_names[$i];
		}
	}
	else
	{
		$query = $this->db->query("SELECT * FROM oc_po_product WHERE order_id = " . $order_info['id']);
		$products = $query->rows;
	}
		$i = 0;
		foreach($products as $product)
		{
			$query = $this->db->query("SELECT * FROM oc_po_attribute_group WHERE product_id = ". $product['id']);
			$attribute_groups[$i] = $query->rows;
			$i++;
		}
		
		$i = 0;
		foreach($attribute_groups as $attribute_group)
		{
			for($j = 0; $j<count($attribute_group);$j++)
			{
				$query = $this->db->query("SELECT * FROM oc_po_attribute_category WHERE attribute_group_id = ". $attribute_group[$j]['id']);
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
        public function submit_order_by_supplier($data)
        {
			$log=new Log("procurement-".date('Y-m-d').".log");
		
            $sql="update oc_po_order set order_status_id='8',`driver_otp`='".$data['otp']."',`driver_mobile`='".$data['driver_mobile']."',`pre_supplier_bit`='1' where id='".$data['order_id']."' ";
			$log->write($sql);
			
		    $query = $this->db->query($sql);
			
			$sql2="update oc_po_product set supplier_quantity='".$data['supplier_quantity']."' where order_id='".$data['order_id']."' and product_id='".$data['product_id']."' ";
			$log->write($sql2);
			
		    $query = $this->db->query($sql2);
			$sql3="update oc_po_receive_details set quantity='".$data['supplier_quantity']."' where order_id='".$data['order_id']."' and product_id='".$data['product_id']."' ";
			$log->write($sql3);
			
		    $query = $this->db->query($sql3);
        }
        public function view_store_details($store_id)
	{
		$query = $this->db->query("select * from oc_store where store_id='".$store_id."' ");
		return $query->row;
        }
        public function confirm_order_by_rm($order_id)
        {
            $sql="update oc_po_order set order_status_id='3' where id='".$order_id."' ";
            $this->db->query($sql);
	    
        }
        public function cancel_order_by_rm($order_id,$reject_Message,$user_id)
        {
            $sql="update oc_po_order set order_status_id='2',`canceled_by`='".$user_id."',`canceled_message`='".$reject_Message."' where id='".$order_id."' ";
            $this->db->query($sql);
	    
        }
        
        public function confirm_order_by_cm($order_id)
        {
            $this->db->query("update oc_po_order set order_status_id='5' where id='".$order_id."' ");
	   
        }
        public function cancel_order_by_cm($order_id,$reject_Message,$user_id)
        {
            $sql="update oc_po_order set order_status_id='4',`canceled_by`='".$user_id."',`canceled_message`='".$reject_Message."' where id='".$order_id."' ";
            $this->db->query($sql);
	   
        }
        public function confirm_order_by_account($order_id)
        {
            $this->db->query("update oc_po_order set order_status_id='7' where id='".$order_id."' ");
	   
        }
        public function cancel_order_by_account($order_id,$reject_Message,$user_id)
        {
            $sql="update oc_po_order set order_status_id='6',`canceled_by`='".$user_id."',`canceled_message`='".$reject_Message."' where id='".$order_id."' ";
            $this->db->query($sql);
	   
        }
        public function add_po_trans($order_id,$from,$to,$updated_by)
        { //echo "here";
            $sql="insert into oc_po_trans set order_id='".$order_id."',`from`='".$from."',`to`='".$to."',`updated_by`='".$updated_by."' ";
            $this->db->query($sql);
	   
        }
        /*-----------------------------insert receive order function starts here-------------------*/
	
	public function insert_receive_order($received_order_info,$order_id)
	{
		if($received_order_info['order_receive_date'] != '')
		{
			$received_order_info['order_receive_date'] = strtotime($received_order_info['order_receive_date']);
			$received_order_info['order_receive_date'] = date('Y-m-d',$received_order_info['order_receive_date']);
		}
		$inner_loop_limit = count($received_order_info['received_quantities']);
		$quantities = array();
		$quantity = 0;
		//$this->db->query("UPDATE oc_po_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 0 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);
		// 	order_sup_send
                $this->db->query("UPDATE oc_po_order SET order_status_id='7',order_sup_send = '" .$received_order_info['order_receive_date']."', receive_bit = " . 0 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);

		//if pre selected supplier
		if(count($received_order_info['received_quantities']) != count($received_order_info['suppliers_ids']))
		{
			for($i =0; $i<count($received_order_info['prices']); $i++)
			{
				if($received_order_info['prices'][$i] != "next product")
				{
					$prices[$i] = $received_order_info['prices'][$i];
				}
			}
			
			for($i =0; $i<count($received_order_info['received_quantities']); $i++)
			{
				if($received_order_info['received_quantities'][$i] != "next product")
				{
					$received_quantities[$i] = $received_order_info['received_quantities'][$i];
				}
			}
			
			$prices = array_values($prices);
			$received_quantities = array_values($received_quantities);
			
			for($i =0; $i<count($prices); $i++)
			{
				$this->db->query("UPDATE oc_po_receive_details SET price =" .$prices[$i]. ", quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
				$query = $this->db->query("SELECT quantity FROM oc_po_receive_details WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id =" . $order_id);
				$quantities[$i] = $query->row['quantity'];
			}
		}
		else
		{
			$query = $this->db->query("SELECT * FROM oc_po_receive_details WHERE order_id=".$order_id);
			
			if(count($query->rows) > 0)
			{
				$this->db->query("DELETE FROM oc_po_receive_details WHERE order_id=".$order_id);
			}
		
			for($j = 0; $j<count($received_order_info['received_product_ids']); $j++)
			{
				for($k = 0; $k<$inner_loop_limit; $k++)
				{
					
					if($received_order_info['received_quantities'][$k] != 'next product')
					{
						$this->db->query("INSERT INTO oc_po_receive_details (quantity,price,product_id,supplier_id,order_id,store_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['prices'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.",".$query->rows[$j]["store_id"].")");
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
			$query = $this->db->query("SELECT DISTINCT product_id FROM oc_po_product WHERE id = " . $received_order_info['received_product_ids'][$i]);
			$product_ids[$i] = $query->row;
			$query1 = $this->db->query("UPDATE oc_po_product SET received_products = " . $quantities[$i] . " WHERE id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
		}
		for($i=0; $i<count($product_ids); $i++)
		{
			$query = $this->db->query("SELECT quantity FROM ".DB_PREFIX."product WHERE product_id = " . $product_ids[$i]['product_id']);
			$quantity = $query->row['quantity'] + $quantities[$i];
			$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id']);
			if($query && $query1)
				$bool = true;
		}
		if($bool)
			return true;
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
        
	public function insert_purchase_order($data = array()){
		
		//insert order details
		if($data['supplier_id'] != "--Supplier--")
		{
			$this->db->query('INSERT INTO oc_po_order (order_date,user_id,pre_supplier_bit) VALUES("' . date('Y-m-d') . '",'.$this->session->data['user_id'].',1)');
			$order_id = $this->db->getLastId();
		}
		else
		{
			$this->db->query('INSERT INTO oc_po_order (order_date,user_id) VALUES("' . date('Y-m-d') . '",'.$this->session->data['user_id'].')');
			$order_id = $this->db->getLastId();
		}
		
		//insert product details
		
		for($i = 0; $i<count($data['products']); $i++)
		{
			$this->db->query("INSERT INTO oc_po_product (product_id,name,quantity,order_id,store_id,store_name)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['stores'][$i][0]."','".$data['stores'][$i][1]."')");
			$product_ids[$i] = $this->db->getLastId();
		}
		//insert attribute group
		$start_loop = 0;
		for($j = 0; $j<count($product_ids); $j++)
		{
			for($i = $start_loop; $i<count($data['options']); $i++)
			{
				if($data['options'][$i] != "new product")
				{
					$this->db->query("INSERT INTO oc_po_attribute_group (attribute_group_id,name,product_id) VALUES(".$data['options'][$i][0].",'".$data['options'][$i][1]."',".$product_ids[$j].")");
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
						$this->db->query("INSERT INTO oc_po_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(".$data['option_values'][$j][0].",'".$data['option_values'][$j][1]."',".$attribute_group_ids[$i].")");
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
						$this->db->query("INSERT INTO oc_po_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(".$data['option_values'][$j][0].",'".$data['option_values'][$j][1]."',".$attribute_group_ids[$i+1].")");
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
			{
				$query = $this->db->query("INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$data['quantity'][$i].",".$data['products'][$i][0].",".$data['supplier_id'].",".$order_id.",'".$data['stores'][$i][0]."')");
			}
		}
		else{
			for($i = 0; $i<count($data['products']); $i++)
			{
				$query = $this->db->query("INSERT INTO oc_po_receive_details (product_id,supplier_id,order_id,store_id) VALUES(".$data['products'][$i][0].",-1,".$order_id.",'".$data['stores'][$i][0]."')");
			}
		}
		
		return $order_id;
	}
	
	
	public function delete($ids)
	{
		$deleted = false;
		foreach($ids as $id)
		{
			if($this->db->query("UPDATE oc_po_order SET delete_bit = " . 0 ." WHERE id = " . $id))
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
			oc_po_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			FROM
			oc_po_order
			INNER JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
			INNER JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.delete_bit = 1";
		
		if(isset($filter['filter_id']))
		{
			$query = $query . " AND oc_po_order.id = " . $filter['filter_id'];
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
		
		$query = $query . " GROUP BY (oc_po_order.id) ORDER BY oc_po_order.id DESC";
		
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
			oc_po_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			FROM
			oc_po_order
			INNER JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
			INNER JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.delete_bit = 1";
		
		if(isset($filter['filter_id']))
		{
			$query = $query . " AND oc_po_order.id = " . $filter['filter_id'];
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
		
		$query = $query . " GROUP BY (oc_po_order.id) ORDER BY oc_po_order.id DESC LIMIT ". $start ."," . $limit;
		$query = $this->db->query($query);
		return $query->rows;
	}

	/////////////////////////////////////////////////
        public function check_po_invoice($order_id) {
            $sql="select po_invoice_n from oc_po_invoice where po_order_id='".$order_id."'  ";
            $query = $this->db->query($sql);
            $results = $query->row;
            if (!empty($results['po_invoice_n'])) {
             return $results['po_invoice_n'];
            }
            else
            {
                return 0;
            }
        }
        public function submit_po_invoice($data = array()) {
            
            $sql="select po_invoice_n from oc_po_invoice where po_order_id='".$data['order_id']."'  ";
            $query = $this->db->query($sql);
            $results = $query->row;
            if (empty($results['po_invoice_n'])) {
            $sql2="select MAX(po_invoice_n) as po_invoice_n from oc_po_invoice where po_store_id='".$data['store_id']."'  ";
            $query2 = $this->db->query($sql2);
            
            if ($query2->row['po_invoice_n']) {
				$invoice_no = $query2->row['po_invoice_n'] + 1;
			
                                
            } 
                else 
              {
		$invoice_no = 1;
	      }
            $sql3="insert into oc_po_invoice set po_store_id='".$data['store_id']."',po_order_id='".$data['order_id']."',po_ware_house='".$data['ware_house']."',po_invoice_n='".$invoice_no."',po_invoice_prefix='ASPL/BB',order_total='".$data['grand_total']."'  ";
            $query = $this->db->query($sql3);
            $insert_id=$this->db->getLastId();
            
            for($a=0;$a<count($data['product_id']);$a++)
            {
                $product_id=$data['product_id'][$a];
                $product_hsn=$data['product_hsn'][$a];
                $p_amount=$data['p_amount'][$a];
                $product_name=$data['product_name'][$a];
                $p_price=$data['p_price'][$a];
                $p_qnty=$data['p_qnty'][$a];
                $p_tax_rate=$data['p_tax_rate'][$a];
                $p_tax_type=$data['p_tax_type'][$a];
                $po_store_id=$data['store_id'];
                $sql4="insert into oc_po_invoice_product set invoice_t_s_id='".$insert_id."',"
                        . "invoice_n='".$invoice_no."',"
                        . "product_id='".$product_id."',"
                        . "product_hsn='".$product_hsn."',"
                        . "p_amount='".$p_amount."',"
                        . "product_name='".$product_name."',  "
                        . "p_qnty='".$p_qnty."',  "
                        . "p_tax_rate='".$p_tax_rate."',  "
                        . "p_tax_type='".$p_tax_type."',  "
                        . "po_store_id='".$po_store_id."',  "
                        . "p_price='".$p_price."',po_order_id='".$data['order_id']."'";
                $query = $this->db->query($sql4);
            }
            
            }
            //print_r($data);
            
        }
        public function getProducts($data = array()) {
		$sql = "select product_id ,model as model,HSTN as hstn,(price+product_tax_rate) as price,price as price_wo_t,product_tax_type,product_tax_rate from (
SELECT 
    p.product_id as product_id,p.model as model,p.price,p.HSTN,
    ((SELECT 
                   oc_tax_rate.name
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    rl.`tax_class_id` = p.tax_class_id)) AS product_tax_type,
                    
  ((SELECT 
                    (CASE
                            WHEN type = 'F' THEN rate
                            WHEN type = 'p' THEN (p.price * (rate / 100))
                            ELSE rate
                        END) AS rate
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    rl.`tax_class_id` = p.tax_class_id)) AS product_tax_rate
FROM
    oc_product p ";

		

		if (!empty($data['filter_model'])) {
			$sql .= " where p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} 

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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
                $sql.=" ) as a ";
		//echo $sql;	
		$logs=new Log("a.log");
		//$logs->write($sql);	
		$query = $this->db->query($sql);

		return $query->rows;
	}
        public function view_order_details_invoice($order_id)
	{       $sql="SELECT oc_po_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
				FROM oc_po_order
					LEFT JOIN ".DB_PREFIX."user
						ON ".DB_PREFIX."user.user_id = oc_po_order.user_id
							WHERE id = " . $order_id . " AND delete_bit = " . 1;
		$query = $this->db->query($sql);
		$order_info = $query->row;
                
                $sql2="select name,store_id from oc_store where store_id in 
(select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('2','4') )";
		$query2 = $this->db->query($sql2);
		$ware_houses = $query2->rows;
                
                $view_order_details="SELECT 
oprd.product_id,ocp.price as product_price,oprd.store_id,oprd.quantity as product_quantity,
ocp.model as product_name,ocp.HSTN as product_hsn,
ocp.price as product_base_price,
((SELECT 
                   oc_tax_rate.name
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    rl.`tax_class_id` = ocp.tax_class_id)) AS product_tax_type,
                    
  ((SELECT 
                    (CASE
                            WHEN type = 'F' THEN rate
                            WHEN type = 'p' THEN (ocp.price * (rate / 100))
                            ELSE rate
                        END) AS rate
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    rl.`tax_class_id` = ocp.tax_class_id)) AS product_tax_rate,
                    (SELECT 
            oc_setting.value AS store_address
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_address'
                AND oc_setting.store_id = oprd.store_id) AS store_address,
                (SELECT 
            oc_setting.value AS store_pan
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_PAN_ID_number'
                AND oc_setting.store_id = oprd.store_id) AS store_pan,
                (SELECT 
            oc_setting.value AS store_gst
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_gstn'
                AND oc_setting.store_id = oprd.store_id) AS store_gst
from
  oc_po_receive_details as oprd  
  LEFT JOIN oc_product as ocp on ocp.product_id=oprd.product_id
  where oprd.order_id=".$order_id;
	$products=array();
	$query = $this->db->query($view_order_details);
	if($this->db->countAffected() > 0)
	{
		$products = $query->rows;
                
        }
           $order_information['products'] = $products;
	   $order_information['order_info'] = $order_info;
           $order_information['ware_houses'] = $ware_houses;
	   return $order_information;     
        }
        public function view_order_details_for_created_invoice($order_id)
	{       $sql="SELECT oc_po_invoice.*,oc_store.name as store_name FROM oc_po_invoice"
                . " LEFT JOIN oc_store on oc_store.store_id=oc_po_invoice.po_ware_house WHERE po_order_id = " . $order_id ;
		$query = $this->db->query($sql);
		$order_info = $query->row;
                
                $sql2="select name,store_id from oc_store where store_id in 
(select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('2','4') )";
		$query2 = $this->db->query($sql2);
		$ware_houses = $query2->rows;
                
                $view_order_details="SELECT 
    oprd.product_id,
    oprd.p_price AS product_price,
    oprd.po_store_id as store_id,
    oprd.p_qnty AS product_quantity,
    oprd.product_name AS product_name,
    oprd.product_hsn AS product_hsn,
    oprd.p_price AS product_base_price,
    oprd.p_tax_type AS product_tax_type,
    oprd.p_tax_rate AS product_tax_rate,
    (SELECT 
            oc_setting.value AS store_address
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_address'
                AND oc_setting.store_id = oprd.po_store_id) AS store_address,
    (SELECT 
            oc_setting.value AS store_pan
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_PAN_ID_number'
                AND oc_setting.store_id = oprd.po_store_id) AS store_pan,
    (SELECT 
            oc_setting.value AS store_gst
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_gstn'
                AND oc_setting.store_id = oprd.po_store_id) AS store_gst
FROM
    oc_po_invoice_product AS oprd
       
WHERE
    oprd.po_order_id =".$order_id;
	$products=array();
	$query = $this->db->query($view_order_details);
	if($this->db->countAffected() > 0)
	{
		$products = $query->rows;
                
        }
           $order_information['products'] = $products;
	   $order_information['order_info'] = $order_info;
           $order_information['ware_houses'] = $ware_houses;
	   return $order_information;     
        }
        
        ///////////////////////////////////////////////////////
}
?>