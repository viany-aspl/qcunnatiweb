<?php
class ModelPoWarehousePurchaseOrder extends Model {
public function getListStore($uid,$start,$limit,$order_id)
    {

                $log=new Log("inv-".date('Y-m-d').".log");
        $sql="SELECT
            oc_po_order.*
            ,'PO' as 'receivetype'


            FROM
            oc_po_order
            INNER JOIN oc_po_receive_details
                ON (oc_po_order.id = oc_po_receive_details.order_id)
            LEFT JOIN oc_po_supplier
                ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
            INNER JOIN ".DB_PREFIX."user
                ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE  oc_po_order.user_id='".$uid."'";
               
            if($order_id!="")
            {
            $sql.=" and oc_po_order.id='".$order_id."' ";
            }
	$sql.=" and (oc_po_order.order_date)>=(select value as reg_date from oc_setting where store_id=oc_po_order.store_id and `key`='config_registration_date' limit 1) ";
                $sql.=" AND oc_po_order.delete_bit = 1  GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit;
                $log->write($sql);
                $query = $this->db->query($sql);
        $log->write($query->rows);
        return $query->rows;
    }
public function check_driver_otp($order_id)
	{
		$log=new Log("receive-product-".date('Y-m-d').".log");

		$log->write($order_id);
		$sql="SELECT oc_po_order.driver_otp as driver_otp FROM oc_po_order WHERE  oc_po_order.id='".$order_id."' LIMIT  1";
		$log->write($sql);
		$query = $this->db->query($sql);
		$log->write($query->row['driver_otp']);
		return $query->row['driver_otp'];
	}
public function check_quantity($order_id)
{
		$log=new Log("receive-product-".date('Y-m-d').".log");

		$log->write($order_id);
		$sql="SELECT oc_po_product.quantity as quantity FROM oc_po_product WHERE  oc_po_product.order_id='".$order_id."' LIMIT  1";
		$log->write($sql);
		$query = $this->db->query($sql);
		$log->write($query->row['quantity']);
		return $query->row['quantity']; 
}
public function insert_purchase_order($data = array()){
        $log=new Log('po_order-'.date('Y-m-d').".log");
        //insert order details
                $log->write($data);
				$ret_id='';
//                for($i = 0; $i<count($data['products']); $i++)
//        {
//		$log->write($i);
        if($data['supplier_id'] != "--Supplier--")
        {
			$sql1='INSERT INTO oc_po_order (order_date,user_id,pre_supplier_bit,store_id,store_type,potential_date,po_number,spo_po_linked_status ) VALUES("' . NOW() . '",'.$this->session->data['user_id'].',1,'.$data['stores'][0].',"'.$data['store_type'].'","'.$data['potentialdate'].'","'.$data['po_number'].'", \'1\')';
            $log->write($sql1);
			$this->db->query($sql1);
            $order_id = $this->db->getLastId();
        }
        else
        {
			$sql2='INSERT INTO oc_po_order (order_date,user_id,store_id,store_type,potential_date,po_number,spo_po_linked_status) VALUES("' . NOW() . '",'.$this->session->data['user_id'].','.$data['stores'][0].',"'.$data['store_type'].'","'.$data['potentialdate'].'","'.$data['po_number'].'", \'1\')';
            $log->write($sql2);
			$this->db->query($sql2);
            $order_id = $this->db->getLastId();
        }
        $sql3="update oc_supplier_po_order set status='3',received_prn='".$order_id."',received_store='".$data['stores'][0]."' where sid='".$data['po_number']."' ";
       $log->write($sql3);
       $this->db->query($sql3);
        if($ret_id=="")
		{
			$ret_id=$order_id;
		}
		else
		{
		$ret_id=$ret_id.",".$order_id;
		}
        //insert product details
        
        
                        $p_sql="INSERT INTO oc_po_product (product_id,name,quantity,order_id,store_id,store_name)    VALUES(".$data['products'][0].",'".$data['products'][1] . "'," . $data['quantity'].",".$order_id.",'".$data['stores'][0]."','".$data['stores'][1]."')";
            $this->db->query($p_sql);
                        $log->write($p_sql);
            $product_ids[0] = $this->db->getLastId();
                        
                        if($data['supplier_id'] != "--Supplier--")
                        {
                            $query = $this->db->query("INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$data['quantity'].",".$data['products'][0].",".$data['supplier_id'].",".$order_id.",'".$data['stores'][0]."')");
                            
                            }
                            else
                            {
                              
                $query = $this->db->query("INSERT INTO oc_po_receive_details (product_id,supplier_id,order_id,store_id) VALUES(".$data['products'][0].",-1,".$order_id.",'".$data['stores'][0]."')");
                              
                            }
                
        //}
        //insert attribute group
        $start_loop = 0;
        $i = 0;
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
        
        return $ret_id;
    }
/*
	public function insert_purchase_order($data = array()){
		$log=new Log('po_order-'.date('Y-m-d').".log");
		//insert order details
                $log->write($data);
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
                        $p_sql="INSERT INTO oc_po_product (product_id,name,quantity,order_id,store_id,store_name)	VALUES(".$data['products'][$i][0].",'".$data['products'][$i][1] . "'," . $data['quantity'][$i].",".$order_id.",'".$data['stores'][$i][0]."','".$data['stores'][$i][1]."')";
			$this->db->query($p_sql);
                        $log->write($p_sql);
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
			for($i = 0; $i<count($data['products']); $i++)			{
				$query = $this->db->query("INSERT INTO oc_po_receive_details (product_id,supplier_id,order_id,store_id) VALUES(".$data['products'][$i][0].",-1,".$order_id.",'".$data['stores'][$i][0]."')");
			}
		}
		
		return $order_id;
	}
	*/
public function getListRec($start,$limit)
	{
		$query = $this->db->query("SELECT
			oc_po_order.*
			,'PO' as 'receivetype'
			FROM
			oc_po_order
			INNER JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
			LEFT JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE  oc_po_order.order_sup_send <> '0000-00-00' AND oc_po_order.delete_bit = 1 AND  oc_po_order.receive_bit = 0 and pre_supplier_bit=1 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
		
		return $query->rows;
	}
public function getList_order($oid)
{
	$log=new Log("stockhis-".date('Y-m-d').".log");
	$sql="SELECT receive_bit FROM oc_po_order where id='".$oid."'";
	$log->write($sql);
	$query = $this->db->query($sql); 
	return $query->row['receive_bit'];

}

public function getList_pending_order($store_id)
{
	$log=new Log("stockhis-".date('Y-m-d').".log");
	$sql="SELECT receive_bit FROM oc_po_order where receive_bit='0' and receive_date='0000-00-00' and order_status_id='1' and store_id='".$store_id."'";
	$log->write($sql);
	$query = $this->db->query($sql); 
	return $query->rows;

}


public function getListRecStore($uid,$start,$limit)
	{

				$log=new Log("inv-".date('Y-m-d').".log");
		
		$query = $this->db->query("SELECT
			oc_po_order.*
			,'PO' as 'receivetype'


			FROM
			oc_po_order
		getProductStoresQuantityCredit	INNER JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
			LEFT JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE  oc_po_order.user_id='".$uid."' AND oc_po_order.order_sup_send <> '0000-00-00' AND oc_po_order.delete_bit = 1 AND  oc_po_order.receive_bit = 0 and pre_supplier_bit=1 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
		$log->write($query->rows);
		return $query->rows;
	}


	public function getList($start,$limit)
	{
		$query = $this->db->query("SELECT
			oc_po_order.*
			, ".DB_PREFIX."user.firstname
			, ".DB_PREFIX."user.lastname
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			FROM
			oc_po_order
			INNER JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
			LEFT JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN ".DB_PREFIX."user 
				ON (oc_po_order.user_id = ".DB_PREFIX."user.user_id) WHERE oc_po_order.delete_bit = 1 GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC LIMIT " . $start . "," . $limit);
		
		return $query->rows;
	}
	public function getTotalOrders()
	{
		$query = $this->db->query("SELECT COUNT(id) as total_orders FROM oc_po_order WHERE delete_bit = " . 1);
		$results = $query->row;
		return $results['total_orders'];
	}
	public function view_order_details($order_id)
	{
		$query = $this->db->query("SELECT oc_po_order.*,".DB_PREFIX."user.firstname,".DB_PREFIX."user.lastname
				FROM oc_po_order
					LEFT JOIN ".DB_PREFIX."user
						ON ".DB_PREFIX."user.user_id = oc_po_order.user_id
							WHERE id = " . $order_id . " AND delete_bit = " . 1);
		$order_info = $query->row;
		//ON (oc_po_receive_details.product_id = oc_po_product.id)
		$query = $this->db->query("SELECT
		oc_po_product.*,oc_po_receive_details.quantity as rd_quantity,oc_po_receive_details.price,oc_po_supplier.first_name,oc_po_supplier.last_name,oc_po_supplier.id as supplier_id,oc_po_receive_details.order_id
		FROM
			oc_po_receive_details
		INNER JOIN oc_po_product 
			ON (oc_po_receive_details.order_id = oc_po_product.order_id)
		LEFT JOIN oc_po_supplier 
			ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
				WHERE (oc_po_receive_details.order_id =".$order_id.")");
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
			LEFT JOIN oc_po_supplier 
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
	$log=new Log("receiveorder");
$log->write("sql-".$query);
		$query = $this->db->query($query);

		return $query->rows;
	}

	/*-----------------------------insert receive order function starts here-------------------*/
	
	public function insert_receive_order($received_order_info,$order_id)
	{
		
	$log=new Log("receiveorder-".date('Y-m-d').".log");

		$log->write($received_order_info);
		 $mcrypt=new MCrypt();
		$st_sql="select value as storetype from oc_setting WHERE `key`='config_storetype' and store_id=".$this->user->getStoreId();
                	$log->write($st_sql);
               	 $st_query=$this->db->query($st_sql);
                	$storetype=$st_query->row['storetype'];
                	$log->write($storetype);

		if($received_order_info['order_receive_date'] != '')
		{
			$received_order_info['order_receive_date'] = strtotime($received_order_info['order_receive_date']);
			$received_order_info['order_receive_date'] = date('Y-m-d',$received_order_info['order_receive_date']);
		}
		$inner_loop_limit = count($received_order_info['received_quantities']);
		$quantities = array();
		$quantity = 0;
		$this->db->query("UPDATE oc_po_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 1 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);
		$log->write("update order info done");		
		//if pre selected supplier
		if(count($received_order_info['received_quantities']) != count($received_order_info['suppliers_ids']))
		{
		

			for($i =0; $i<count($received_order_info['prices']); $i++)
			{
				if($received_order_info['prices'][$i] != "next product")
				{
					$prices[$i] = $mcrypt->decrypt($received_order_info['prices'][$i]);
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

				$log->write("UPDATE oc_po_receive_details SET price =" .$prices[$i]. ", quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
				$this->db->query("UPDATE oc_po_receive_details SET  quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
				$query = $this->db->query("SELECT quantity FROM oc_po_receive_details WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id =" . $order_id);
				$quantities[$i] = $query->row['quantity'];
			$log->write("quantity");	
			$log->write($quantities[$i]);	
			}
		}
		else
		{
			$query = $this->db->query("SELECT * FROM oc_po_receive_details WHERE order_id=".$order_id);
					$log->write("update order info done select ".$query);
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
						//"INSERT INTO oc_po_receive_details (quantity,price,product_id,supplier_id,order_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['prices'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.")"
						$this->db->query("INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.",".$query->rows[$j]["store_id"].")");
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
			$query = $this->db->query("SELECT DISTINCT product_id FROM oc_po_product WHERE product_id = " . $received_order_info['received_product_ids'][$i]);
			$product_ids[$i] = $query->row;
			$query1 = $this->db->query("UPDATE oc_po_product SET received_products = " . $quantities[$i] . " WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id);
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
			$log->write("no product");
				$log->write($query2);
                        try{
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addproducttrans($this->user->getStoreId(),$product_ids[$i]['product_id'],$quantity,$order_id,'CR','PO');      
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
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
					if(($storetype==3) || ($storetype==4))
                				{ 
						if(!empty($queryprd->row['wholesale_price']))
						{
						$tax=$this->tax->getTax($queryprd->row['wholesale_price'], $queryprd->row['tax_class_id']);
						$totalamount=$totalamount+($quantity*$queryprd->row['wholesale_price'])+($quantity*$tax);
						}
						else
						{
							$tax=$this->tax->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']);
							$totalamount=$totalamount+($quantity*$queryprd->row['price'])+($quantity*$tax);
						}
					}
					else
					{
					$tax=$this->tax->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']);
					$totalamount=$totalamount+($quantity*$queryprd->row['price'])+($quantity*$tax);
					}
					$log->write($totalamount);

				}

			if($query && $query1 && $query2)
				$bool = true;
		}
		if($bool)
			{
				//update credit price
                
	  $log->write('get the price from oc_po_invoice');

	  $invoice_am_q=" SELECT order_total FROM `oc_po_invoice` WHERE `po_order_id` = '".$order_id."'  ";
	  $invoice_am = $this->db->query($invoice_am_q);
	  $order_invoice_total=$invoice_am->row['order_total'];	
	  $log->write('order invoice total='.$order_invoice_total);
	  
	
                if(($storetype==3) || ($storetype==4))
                {
                $sql_update="UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit - " . $order_invoice_total . " WHERE store_id=".$this->user->getStoreId();
                $log->write($sql_update);
                $this->db->query($sql_update);   
                try{
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($order_invoice_total,$this->user->getStoreId(),$this->user->getId(),'DB',$order_id,'PO',$order_invoice_total); 
                                
                               } catch (Exception $e){
                                   $log->write($e->getMessage()); 
                               }
                }
                else
                {
                $sql_update="UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit + " . $order_invoice_total . " WHERE store_id=".$this->user->getStoreId();
                $log->write($sql_update);
                $this->db->query($sql_update);   
                try{
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($order_invoice_total,$this->user->getStoreId(),$this->user->getId(),'CR',$order_id,'PO',$order_invoice_total); 
                                
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
                }
			}
		if($bool)
			return true;
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
         /* ######################### Update PR verify doc ##########################*/
      	
	public function upload_pr_document($data) 
        {

            $log=new Log("insertdocument_upload_type-".date('Y-m-d').".log"); 
            $sql2="update oc_po_order SET pr_verification_image='".$data['file']."' where id='" .$data['order_id']. "'";
            $log->write($sql2);
            return $this->db->query($sql2); 
        }
}
?>