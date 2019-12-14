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

	  public function getreturnorderdata($data = array())
	{// print_r($data);
		$sql = "SELECT r.status as status,r.id,os.name as store_name,os2.name as supplier,opd.name as product_name,ou.firstname,ou.lastname,r.order_id,r.return_date,r.return_quantity,r.reason FROM oc_po_return as r
LEFT JOIN oc_store as os on os.store_id=r.store_id
LEFT JOIN oc_store as os2 on os2.store_id=r.supplier_id
LEFT JOIN oc_product as op on op.product_id=r.product_id
left join oc_product_description as opd on op.product_id=opd.product_id
LEFT JOIN oc_user as ou on ou.user_id=r.user_id where r.id!=''";
  
if (!empty($data['filter_id']) ) {
    $sql .=" and r.id=".$data['filter_id'];
            
        }

if (!empty($data['filter_name_id']) ) {
    $sql .=" and r.product_id=".$data['filter_name_id'];
            
        }
if (!empty($data['filter_warehouse']) ) {
    $sql .="and  r.supplier_id=".$data['filter_warehouse'];
            
        }

if (!empty($data['filter_date_start']) ) {
    $sql .="and  date(r.return_date)>='".$data['filter_date_start']."'";
			
}
if (!empty($data['filter_date_end']) ) {
    $sql .=" and date(r.return_date)<='".$data['filter_date_end']."'";
			
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
//echo $sql;

        $query2 = $this->db->query($sql);
        return $query2->rows;
	}
        
        
           public function getreturnordertotaldata($data)
	{
		$sql = "select count(*) as total from (SELECT r.status as status,r.id,os.name as store_name,os2.name as supplier,model as product_name,ou.firstname,ou.lastname,r.order_id,r.return_date,r.return_quantity,r.reason FROM shop.oc_po_return as r
LEFT JOIN oc_store as os on os.store_id=r.store_id
LEFT JOIN oc_store as os2 on os2.store_id=r.supplier_id
LEFT JOIN oc_product as op on op.product_id=r.product_id
LEFT JOIN oc_user as ou on ou.user_id=r.user_id where r.id!=''";
  
if (!empty($data['filter_id']) ) {
    $sql .=" and r.id=".$data['filter_id'];
            
        }

if (!empty($data['filter_name_id']) ) {
    $sql .=" and r.product_id=".$data['filter_name_id'];
            
        }
if (!empty($data['filter_warehouse']) ) {
    $sql .="and  r.supplier_id=".$data['filter_warehouse'];
            
        }

if (!empty($data['filter_date_start']) ) {
    $sql .="and  date(r.return_date)>='".$data['filter_date_start']."'";
			
}
if (!empty($data['filter_date_end']) ) {
    $sql .=" and date(r.return_date)<='".$data['filter_date_end']."'";
			
}
$sql.=" ) as aa";
//echo $sql;

        $query2 = $this->db->query($sql);
        return $query2->row['total'];
	}
        
        public function create_note($data)
        {   //print_r($data);
            $log=new Log('credit-note-'.date('Y-m-d').'.log');
            $this->load->library('trans');
            $trans=new trans($this->registry);
            $sql11=" select user_id as warehouse_user_id from oc_user where store_id='".$data['filter_ware_house']."' and user_group_id='11' limit 1 ";
            $log->write($sql11);
            $query11=$this->db->query($sql11);
            $rwarehouse_user_id=$query11->row['warehouse_user_id'];
            $log->write($rwarehouse_user_id);
            
            $sql12=" update oc_po_return set supplier_id='".$data['filter_ware_house']."',warehouse_user_id='".$rwarehouse_user_id."'  where id='".$data['order_id']."' ";
            $log->write($sql12);
            $query12=$this->db->query($sql12);
            //exit;   
            
            $sql1=" select oc_po_return.*,oc_product.wholesale_price as wholesale_price,oc_product.price as base_price from oc_po_return left join oc_product "
                    . " on oc_product.product_id=oc_po_return.product_id "
                    . " where oc_po_return.id='".$data['order_id']."' limit 1";
            $log->write($sql1);
            $query1=$this->db->query($sql1);
            
            $po_return_row=$query1->row;
            $log->write($po_return_row);
            $product_id=$po_return_row['product_id'];
            $order_id=$po_return_row['order_id'];
            $store_id=$po_return_row['store_id'];
            $ware_house_id=$po_return_row['supplier_id'];
            $return_quantity=$po_return_row['return_quantity'];
            $user_id=$po_return_row['user_id'];
            $warehouse_user_id=$po_return_row['warehouse_user_id'];
            $product_price=$po_return_row['wholesale_price'];
            if($product_price=="")
            {
                $product_price=$po_return_row['base_price'];
            }
            
            $sql2=" select quantity from oc_product_to_store where product_id='".$product_id."' and store_id='".$store_id."' ";
            $log->write($sql2);
            $query2=$this->db->query($sql2);
            $product_quantity=$query2->row['quantity'];
            $log->write($product_quantity);
           // if($product_quantity<$return_quantity)
           // {
            //    $log->write('0,Qunatity not available at store');
            //    return '0,Qunatity not available at store';
           // }
           // else
            {
               $product_details=$this->getProductDetails($product_id)[0]; 
               //print_r($product_details);
               $log->write($product_details);
               $product_name=$product_details['model'];
               $product_hstn=$product_details['hstn'];
               $product_tax_type=$product_details['product_tax_type'];
               $product_tax_rate=$product_details['product_tax_rate'];
               
               $sql22=" select return_tbl_id from oc_po_credit_note where return_tbl_id='".$data['order_id']."'  ";
               $log->write($sql22);
               $query22=$this->db->query($sql22);
               
               if($query22->row['return_tbl_id']=="")
               { 
                   
               $total_price_w_o_tax=$product_price*$return_quantity;
               if($product_tax_type=="GST@5%")
               {
                   $total_tax=($total_price_w_o_tax*5)/100;
                   
               }
               if($product_tax_type=="GST@12%")
               {
                   $total_tax=($total_price_w_o_tax*12)/100;
               }
               if($product_tax_type=="GST@18%")
               {
                   $total_tax=($total_price_w_o_tax*18)/100;
               }
               $total_price_with_tax=$total_price_w_o_tax+$total_tax;
               ////////////now insert all data into oc_po_credit_note///////////
               $sql3=" insert into oc_po_credit_note set return_tbl_id='".$data['order_id']."',order_id='".$order_id."', "
                   . " product_id='".$product_id."',store_id='".$store_id."',supplier_id='".$ware_house_id."', "
                   . " return_quantity='".$return_quantity."',user_id='".$user_id."',"
                   . " product_name='".$this->db->escape($product_name)."',product_hstn='".$product_hstn."', "
                   . " product_tax_type='".$product_tax_type."',product_tax_rate='".$product_tax_rate."',"
                   . " product_price='".$product_price."',total_price_w_o_tax='".$total_price_w_o_tax."',total_tax='".$total_tax."',total_price_with_tax='".$total_price_with_tax."' ";
                //echo $sql3;
                $log->write($sql3);
                $query3=$this->db->query($sql3);
                $insert_id=$this->db->getLastId();
                $log->write($insert_id);
               
                //$sql4=" update oc_product_to_store set quantity=quantity-'".$return_quantity."'  where product_id='".$product_id."' and store_id='".$store_id."' ";
                //$log->write($sql4);
                //$query4=$this->db->query($sql4);
               
                //$trans->addproducttrans($store_id,$product_id,$return_quantity,$data['order_id'],'DB','PORETURN');  
               
                $sql5=" update oc_product_to_store set quantity=quantity+'".$return_quantity."'  where product_id='".$product_id."' and store_id='".$ware_house_id."' ";
                $log->write($sql5);
                $query5=$this->db->query($sql5);
                $trans->addproducttrans($ware_house_id,$product_id,$return_quantity,$data['order_id'],'CR','PORETURN');  
               
                
                $sql6=" update oc_store set currentcredit=currentcredit-'".$total_price_with_tax."'  where store_id='".$store_id."' ";
                $log->write($sql6);
                $query6=$this->db->query($sql6);
                $trans->addstoretrans($total_price_with_tax,$store_id,$user_id,'DB',$data['order_id'],'PORETURN',$total_price_with_tax,$product_name.'Product return -'.$return_quantity.' - quantity');  
               
                $sql7=" update oc_store set currentcredit=currentcredit+'".$total_price_with_tax."'  where store_id='".$ware_house_id."' ";
                $log->write($sql7);
                $query7=$this->db->query($sql7);
                
                $trans->addstoretrans($total_price_with_tax,$ware_house_id,$warehouse_user_id,'CR',$data['order_id'],'PORETURN',$total_price_with_tax,$product_name.'Product return -'.$return_quantity.' - quantity');  
                $sql8=" update oc_po_return set status='1'  where id='".$data['order_id']."' ";
                $log->write($sql8);
                $query8=$this->db->query($sql8);
                
                return '1,Created Successfully';
                //$warehouse_user_id
               }
               else 
               {
                   return '2,Create note is already created for this request';
               }
               
            }
            
        }
        public function getProductDetails($product_id) {
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

		

		if (!empty($product_id)) {
			$sql .= " where p.product_id = '" . $this->db->escape($product_id) . "'";
		}

		
		

		
                $sql.=" ) as a ";
		//echo $sql;	
		//$logs=new Log("a.log");
		//$logs->write($sql);	
		$query = $this->db->query($sql);

		return $query->rows;
	}
        public function get_ware_houses() {
        $sql2 = "select name,store_id from oc_store where store_id in 
(select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('2','4') )";
        $query2 = $this->db->query($sql2);
        return $query2->rows;
    }
    public function getReturnNote($order_id)
    {
        $sql2 = " select oc_po_credit_note.*,opd.name as product_name2 from oc_po_credit_note left join oc_product_description as opd on oc_po_credit_note.product_id=opd.product_id  where return_tbl_id='".$order_id."'  ";
        $query2 = $this->db->query($sql2);
        return $query2->rows; 
    }
    public function getStoreInfo($store_id)
        {
           $store_data='';
           $sql="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_name'";
            
           $query = $this->db->query($sql);
           $store_data=$query->row['value']; 
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_address'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_telephone'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value']; 
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_email'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_PAN_ID_number'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
           
           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_gstn'";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
           $sql2="SELECT `creditlimit` from  oc_store where store_id = ".$store_id;
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".number_format((float)$query2->row['creditlimit'], 2, '.', '');
           
           $sql2="SELECT `currentcredit` from  oc_store where store_id = ".$store_id;
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".number_format((float)$query2->row['currentcredit'], 2, '.', '');
		   
		   $sql2="SELECT `cash` from  oc_user where user_group_id='11' and store_id = ".$store_id." limit 1";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".number_format((float)$query2->row['cash'], 2, '.', '');
		   
           return $store_data;
           
        }
	public function getAllWareHouses()
	{
		$sql2 = "select name,store_id from oc_store where store_id in 
(select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('2','4') )";
        $query2 = $this->db->query($sql2);
        return $query2->rows;
	}
}
?>