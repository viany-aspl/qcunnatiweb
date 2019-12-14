<?php
class ModelPurchaseReturnOrders extends Model {
	public function getProducts($order_id)
	{
		$query = $this->db->query('SELECT * FROM oc_po_order WHERE id =' . $order_id . ' AND delete_bit = ' . 1 . ' AND receive_bit = ' . 1);
		if($query->num_rows > 0)
		{
			//return $query->num_rows;
			$query = $this->db->query('SELECT * FROM oc_po_product WHERE order_id = ' . $order_id);
			$products = $query->rows;
			return $products;
		}
		else
		{
			return "nothing";
		}
	}
        public function getStores($order_id,$product_id)
	{
		$query = $this->db->query('SELECT * FROM oc_po_order WHERE id =' . $order_id . ' AND delete_bit = ' . 1 . ' AND receive_bit = ' . 1);
		if($query->num_rows > 0)
		{
			//return $query->num_rows;
			$query = $this->db->query('SELECT *,p.store_id FROM oc_po_product p LEFT JOIN '.DB_PREFIX.'store s on s.store_id=p.store_id WHERE order_id = ' . $order_id.' AND product_id = '.$product_id);
			$stores = $query->rows;
			return $stores;
		}
		else
		{
			return "nothing";
		}
	}
        
        
        
	public function getSuppliers($order_id,$product_id)
	{
		$query = $this->db->query('SELECT
		oc_po_supplier.first_name
		, oc_po_supplier.last_name
		, oc_po_supplier.id
		FROM
			oc_po_receive_details
		INNER JOIN oc_po_supplier 
			ON (oc_po_receive_details.supplier_id = oc_po_supplier.id) WHERE order_id = '.$order_id.' AND product_id = '.$product_id);
		return $query->rows;
	}
	public function checkQuantity($order_id,$product_id,$supplier_id,$store_id)
	{
		$query = $this->db->query("SELECT
		quantity - returned_products as quantity
		FROM
			oc_po_receive_details WHERE order_id =" .$order_id. " AND product_id = ".$product_id." AND supplier_id =". $supplier_id."  AND store_id=".$store_id);
		return $query->row;
	}
	
	public function save_return_order($data)
	{
		$query = $this->db->query("SELECT product_id FROM oc_po_product WHERE id = " . $data['product_id'] . " AND order_id = " . $data['order_id']);
		$product_id = $query->row;
		$query = $this->db->query("UPDATE oc_po_receive_details SET returned_products = " . $data['return_quantity'] . " WHERE product_id=" . $data['product_id'] . " AND order_id = " . $data['order_id'] . " AND supplier_id = " . $data['supplier']);
		$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity - " . $data['return_quantity'] . " WHERE product_id = " . $product_id['product_id']);
                $query3 = $this->db->query("UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity - " . $data['return_quantity'] . " WHERE product_id = " . $product_id['product_id']." AND store_id=".$data["store"] );
		$query2 = $this->db->query("INSERT INTO oc_po_return (order_id,product_id,supplier_id,return_quantity,reason,return_date,user_id,store_id) VALUES(".$data['order_id'].",".$data['product_id'].",".$data['supplier'].",".$data['return_quantity'].",'". $data['reason'] ."','".date('Y-m-d')."',".$this->session->data['user_id'].",'".$data["store"]."')");
		if($query && $query1 && $query2 && $query3)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function getList()
	{
		$query = $this->db->query("SELECT
		oc_po_return.id
		, oc_po_return.order_id
		, oc_po_product.name
		, oc_po_supplier.first_name
		, oc_po_supplier.last_name
		, oc_po_return.return_date
		,oc_po_return.return_quantity
		,".DB_PREFIX."user.firstname
		,".DB_PREFIX."user.lastname
		FROM
		oc_po_return
		INNER JOIN oc_po_product 
			ON (oc_po_return.order_id = oc_po_product.order_id)
		INNER JOIN ".DB_PREFIX."user
			ON ".DB_PREFIX."user.user_id = oc_po_return.user_id
		INNER JOIN oc_po_supplier 
			ON (oc_po_return.supplier_id = oc_po_supplier.id) WHERE oc_po_return.delete_bit = " . 0 . " ORDER BY oc_po_return.id DESC");
		return $query->rows;
	}
	public function delete($delete_ids)
	{
		for($i=0; $i<count($delete_ids); $i++)
		{
			$query = $this->db->query("UPDATE oc_po_return SET delete_bit = 1 WHERE id = " . $delete_ids[$i]);
		}
		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function filter($data)
	{
		if($data['start_date'] == '' && $data['end_date'] == '')
		{
			$data['start_date'] = '';
			$data['end_date'] = '';
		}
		else
		{
			if($data['start_date'] != '')
			{
				$data['start_date'] = strtotime($data['start_date']);
				$data['start_date'] = date('Y-m-d',$data['start_date']);
			}
			else
			{
				$data['start_date'] = date('Y-m-d');
			}
			if($data['end_date'] != '')
			{
				$data['end_date'] = strtotime($data['end_date']);
				$data['end_date'] = date('Y-m-d',$data['end_date']);
			}
			else
			{
				$data['end_date'] = date('Y-m-d');
			}
		}
		$query_string = "SELECT
			oc_po_return.id
			, oc_po_return.order_id
			, oc_po_product.name
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			, oc_po_return.return_date
			,oc_po_return.return_quantity
			,".DB_PREFIX."user.firstname
			,".DB_PREFIX."user.lastname
			FROM
			oc_po_return
			INNER JOIN oc_po_product 
				ON (oc_po_return.product_id = oc_po_product.id)
			INNER JOIN ".DB_PREFIX."user
				ON ".DB_PREFIX."user.user_id = oc_po_return.user_id
			INNER JOIN oc_po_supplier 
				ON (oc_po_return.supplier_id = oc_po_supplier.id) WHERE oc_po_return.delete_bit = " . 0;
		
		/*if($data['return_id'] != '' && $data['order_id'] != '' && $data['product'] != '--product--' && $data['start_date'] != '' && $data['end_date'] != '' && $data['supplier'] != '--supplier--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_return.order_id = ".$data['order_id']." AND oc_po_product.name = '".$data['product']."' AND oc_po_return.id = ".$data['return_id']." AND oc_po_supplier.first_name = '" . $name[0] . "' AND oc_po_supplier.last_name = '" . $name[1] . "' AND oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['order_id'] != '' && $data['product'] != '--product--' && $data['start_date'] != '' && $data['end_date'] != '' && $data['supplier'] != '--supplier--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_return.order_id = ".$data['order_id']." AND oc_po_product.name = '".$data['product']."'  AND oc_po_supplier.first_name = '" . $name[0] . "' AND oc_po_supplier.last_name = '" . $name[1] . "' AND oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '' && $data['product'] != '--product--' && $data['start_date'] != '' && $data['end_date'] != '' && $data['supplier'] != '--supplier--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_return.id = ".$data['return_id']." AND oc_po_supplier.first_name = '" . $name[0] . "' AND oc_po_supplier.last_name = '" . $name[1] . "' AND oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '' && $data['product'] != '--product--' && $data['start_date'] != '' && $data['end_date'] != '')
		{
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_return.id = ".$data['return_id']." AND oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['product'] != '--product--' && $data['supplier'] != '--supplier--' && $data['start_date'] != '' && $data['end_date'] != '')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_supplier.first_name = '" . $name[0] . "' AND oc_po_supplier.last_name = '" . $name[1] . "' AND oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '' && $data['product'] != '--product--' && $data['supplier'] != '--supplier--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_return.id = ".$data['return_id']." AND oc_po_supplier.first_name = '" . $name[0] . "' AND oc_po_supplier.last_name = '" . $name[1] . "') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['supplier'] != '--supplier--' && $data['start_date'] != '' && $data['end_date'] != '')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_supplier.first_name = '" . $name[0] . "' AND oc_po_supplier.last_name = '" . $name[1] . "' AND oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['product'] != '--product--' && $data['start_date'] != '' && $data['end_date'] != '')
		{
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '' && $data['order_id'] != '' && $data['supplier'] != '--supplier--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_supplier.first_name = '" . $name[0] . "' AND oc_po_supplier.last_name = '" . $name[1] . "' AND oc_po_return.order_id = ".$data['order_id']." AND oc_po_return.id = " . $data['return_id'] . ") ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '' && $data['order_id'] != '' && $data['product'] != '--product--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_return.order_id = ".$data['order_id']." AND oc_po_return.id = " . $data['return_id'] . ") ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '' && $data['order_id'] != '')
		{
			$query_string = $query_string . " AND (oc_po_return.order_id = ".$data['order_id']." AND oc_po_return.id = " . $data['return_id'] . ") ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '' && $data['product'] != '--product--')
		{
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_return.id = " . $data['return_id'] . ") ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['order_id'] != '' && $data['product'] != '--product--')
		{
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_return.order_id = " . $data['order_id'] . ") ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['order_id'] != '' && $data['product'] != '--product--')
		{
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."' AND oc_po_return.order_id = " . $data['order_id'] . ") ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['order_id'] != '' && $data['supplier'] != '--supplier--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_supplier.first_name = '".$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND oc_po_return.order_id = " . $data['order_id'] . ") ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '' && $data['supplier'] != '--supplier--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_supplier.first_name = '".$name[0]."' AND oc_po_supplier.last_name ='".$name[1]."' AND oc_po_return.id = " . $data['return_id'] . ") ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['product'] != '--product--' && $data['supplier'] != '--supplier--')
		{
			$name = explode(' ',$data['supplier']);
			$query_string = $query_string . " AND (oc_po_supplier.first_name = '".$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND oc_po_product.name = '" . $data['product'] . "') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['start_date'] != '' && $data['end_date'] != '')
		{
			$query_string = $query_string . " AND (oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ORDER BY oc_po_return.id DESC"; 
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['return_id'] != '')
		{
			$query_string = $query_string . " AND (oc_po_return.id = ".$data['return_id'].") ORDER BY oc_po_return.id DESC";
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['order_id'] != '')
		{
			$query_string = $query_string . " AND (oc_po_return.order_id = ".$data['order_id'].") ORDER BY oc_po_return.id DESC";
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['product'] != '--product--')
		{
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."') ORDER BY oc_po_return.id DESC";
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['supplier'] != '--supplier--')
		{
			$name = explode(' ', $data['supplier']);
			$query_string = $query_string . " AND (oc_po_supplier.first_name = '".$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."') ORDER BY oc_po_return.id DESC";
			$query = $this->db->query($query_string);
			return $query->rows;
		}*/
		if($data['return_id'] != '')
		{
			$query_string = $query_string . " AND (oc_po_return.id = ".$data['return_id'].")";
		}
		if($data['order_id'] != '')
		{
			$query_string = $query_string . " AND (oc_po_return.order_id = ".$data['order_id'].")";
		}
		if($data['product'] != '--product--')
		{
			$query_string = $query_string . " AND (oc_po_product.name = '".$data['product']."')";
		}
		if($data['supplier'] != '--supplier--')
		{
			$name = explode(' ', $data['supplier']);
			$query_string = $query_string . " AND (oc_po_supplier.first_name = '".$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."')";
		}
		if($data['start_date'] != '' && $data['end_date'] != '')
		{
			$query_string = $query_string . " AND (oc_po_return.return_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')"; 
			
		}
		$sql = " ORDER BY oc_po_return.id DESC";
		$query_string = $query_string . $sql;
		
		$query = $this->db->query($query_string);
		return $query->rows;
	}
	public function getReturnOrder($return_order_id)
	{
			$query = $this->db->query("SELECT * FROM oc_po_return WHERE id = " . $return_order_id);
			return $query->row;
	}
	
	public function checkUpdateQuantity($order_id,$product_id,$supplier_id)
	{
		$query = $this->db->query("SELECT
		quantity
		FROM
			oc_po_receive_details WHERE order_id =" .$order_id. " AND product_id = ".$product_id." AND supplier_id =". $supplier_id );
		return $query->row;
	}
	
	public function update_return_order($data)
	{
		$query = $this->db->query("SELECT product_id FROM oc_po_product WHERE id = " . $data['product_id'] . " AND order_id = " . $data['order_id']);
		$product_id = $query->row;
		$query = $this->db->query("SELECT returned_products FROM oc_po_receive_details WHERE product_id=" . $data['product_id'] . " AND order_id = " . $data['order_id'] . " AND supplier_id = " . $data['supplier']);
		$return_products = $query->row;
		
		$remaining_quantity = $data['return_quantity'] - $return_products['returned_products'];
		$query = $this->db->query("UPDATE oc_po_receive_details SET returned_products = " . $data['return_quantity'] . " WHERE product_id=" . $data['product_id'] . " AND order_id = " . $data['order_id'] . " AND supplier_id = " . $data['supplier']);
		if($remaining_quantity > 0)
		{
			$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity - " . $remaining_quantity . " WHERE product_id = " . $product_id['product_id']);
		}
		else
		{
			$remaining_quantity = (-1) * $remaining_quantity;
			$query1 = $this->db->query("UPDATE ".DB_PREFIX."product SET quantity = quantity + " . $remaining_quantity . " WHERE product_id = " . $product_id['product_id']);
		}
			
		$query2 = $this->db->query("UPDATE oc_po_return SET return_quantity =" .$data['return_quantity']. " WHERE order_id = ".$data['order_id']." AND product_id = ".$data['product_id']." AND supplier_id = ".$data['supplier'] . " AND delete_bit = " . 0);
		$effected = $this->db->countAffected();
		if($query && $query1 && $query2 && $effected > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getTotalReturnOrders()
	{
		$query = $this->db->query("SELECT COUNT(id) as total FROM oc_po_return WHERE delete_bit = " . 0);
		return $query->row['total'];
		
	}
	
	public function getAllSuppliers()
	{
		$query = $this->db->query('SELECT
		first_name
		,last_name
		FROM
		oc_po_supplier WHERE delete_bit = 0');
		return $query->rows;
	}
}
?>