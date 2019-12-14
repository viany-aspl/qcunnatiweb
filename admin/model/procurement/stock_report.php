<?php
class ModelPurchaseStockReport extends Model {
	public function getStockDetails($start,$limit)
	{
		$query = $this->db->query("SELECT
		".DB_PREFIX."product.product_id
		, ".DB_PREFIX."product_description.name
		, ".DB_PREFIX."product.quantity
		FROM
			".DB_PREFIX."product
		INNER JOIN ".DB_PREFIX."product_description 
			ON (".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id)  LIMIT ".$start.",".$limit);
		return $query->rows;
	}
	
	public function getStockDetailsExport()
	{
		$query = $this->db->query("SELECT
		".DB_PREFIX."product.product_id
		, ".DB_PREFIX."product_description.name
		, ".DB_PREFIX."product.quantity
		FROM
			".DB_PREFIX."product
		INNER JOIN ".DB_PREFIX."product_description 
			ON (".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id)");
		return $query->rows;
	}
	
	public function getTotalProducts(){
		$query = $this->db->query("SELECT
		COUNT(product_id) as total_products
		FROM
		".DB_PREFIX."product;");
		
		return $query->row['total_products'];
	}
	
	public function getInoutDetails()
	{
		$query1 = $this->db->query("SELECT
		".DB_PREFIX."order_product.product_id
		, ".DB_PREFIX."order_product.name
		, ".DB_PREFIX."order_product.quantity
		FROM
			".DB_PREFIX."order
		INNER JOIN ".DB_PREFIX."order_product 
			ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5;");
			
		$sale_products = $query1->rows;
		
		$quantity = 0;
		
		for($i=0;$i<count($sale_products); $i++)
		{
			if($sale_products[$i] != '')
			{
				$quantity = $quantity + $sale_products[$i]['quantity'];
				for($j=0; $j<count($sale_products); $j++)
				{
					if(($sale_products[$i]['product_id'] == $sale_products[$j]['product_id']) && ($i != $j))
					{
						$quantity = $quantity + $sale_products[$j]['quantity'];
						unset($sale_products[$j]);
					}
				}
				$sale_products[$i]['sales_quantity'] = $quantity;
				$sale_products = array_values(array_filter($sale_products));
				$quantity = 0;
			}
		}

		/*purchase products*/
		$query2 = $this->db->query("SELECT
		oc_po_product.product_id
		, oc_po_product.name
		, oc_po_product.received_products as quantity
		FROM
			oc_po_order
		INNER JOIN oc_po_product 
			ON (oc_po_order.id = oc_po_product.order_id) WHERE oc_po_order.receive_bit = 1 AND delete_bit=1;");
		
		$purchase_products = $query2->rows;
		
		$quantity = 0;
		
		for($i=0;$i<count($purchase_products); $i++)
		{
			$quantity = $quantity + $purchase_products[$i]['quantity'];
			for($j=0; $j<count($purchase_products); $j++)
			{
				if(($purchase_products[$i]['product_id'] == $purchase_products[$j]['product_id']) && ($i != $j))
				{
					$quantity = $quantity + $purchase_products[$j]['quantity'];
					unset($purchase_products[$j]);
				}
			}
			$purchase_products[$i]['quantity'] = $quantity;
			$purchase_products = array_values(array_filter($purchase_products));
			$quantity = 0;
		
		}
		
		$quantity = 0;
		
		for($i=0;$i<count($purchase_products); $i++)
		{
			$quantity = $quantity + $purchase_products[$i]['quantity'];
			for($j=0; $j<count($purchase_products); $j++)
			{
				if(($purchase_products[$i]['product_id'] == $purchase_products[$j]['product_id']) && ($i != $j))
				{
					$quantity = $quantity + $purchase_products[$j]['quantity'];
					unset($purchase_products[$j]);
				}
			}
			$purchase_products[$i]['purchase_quantity'] = $quantity;
			$purchase_products = array_values(array_filter($purchase_products));
			$quantity = 0;
		
		}
		
		/*purchase products*/
		
		for($i=0;$i<count($sale_products); $i++)
		{
			for($j=0; $j<count($purchase_products); $j++)
			{
				if($sale_products[$i]['product_id'] == $purchase_products[$j]['product_id'])
				{
					$sale_products[$i]['purchase_quantity'] = $purchase_products[$j]['purchase_quantity'];
					unset($purchase_products[$j]);
					$purchase_products = array_values(array_filter($purchase_products));
					break;
				}
				
			}
			
		}
		
		$data = array_merge($sale_products,$purchase_products);
		
		for($i=0; $i<count($data); $i++)
		{
			unset($data[$i]['quantity']);
		}
		$data = array_filter($data);
		
		return $data;
	}
	
	public function filter_inout($filter)
	{
		$sale_products = array();
		$purchase_products = array();
		if($filter['date_start'] == '' && $filter['date_end'] == '')
		{
			$filter['date_start'] = '';
			$filter['date_end'] = '';
		}
		else
		{
			if($filter['date_start'] != '')
			{
				$filter['date_start'] = strtotime($filter['date_start']);
				$filter['date_start'] = date('Y-m-d',$filter['date_start']);
			}
			else
			{
				$filter['date_start'] = date('Y-m-d');
			}
			if($filter['date_end'] != '')
			{
				$filter['date_end'] = strtotime($filter['date_end']);
				$filter['date_end'] = date('Y-m-d',$filter['date_end']);
			}
			else
			{
				$filter['date_end'] = date('Y-m-d');
			}
		}
		
		if(($filter['date_start'] != '') && ($filter['date_end'] != '') && ($filter['product'] != '--product--'))
		{
			$query1 = $this->db->query("SELECT
		".DB_PREFIX."order_product.product_id
		, ".DB_PREFIX."order_product.name
		, ".DB_PREFIX."order_product.quantity
		FROM
			".DB_PREFIX."order
		INNER JOIN ".DB_PREFIX."order_product 
			ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5 AND ".DB_PREFIX."order.date_modified BETWEEN '".$filter['date_start']." 00:00:00.00' AND '".$filter['date_end']." 23:59:59.999' AND ".DB_PREFIX."order_product.name = '".$filter['product']."';");
			
			$sale_products = $query1->rows;
			
			$query2 = $this->db->query("SELECT
		oc_po_product.product_id
		, oc_po_product.name
		, oc_po_product.received_products as quantity
		FROM
			oc_po_order
		INNER JOIN oc_po_product 
			ON (oc_po_order.id = oc_po_product.order_id) WHERE oc_po_order.receive_bit = 1 AND delete_bit=1 AND oc_po_order.receive_date BETWEEN '".$filter['date_start']."' AND '".$filter['date_end']."' AND oc_po_product.name = '".$filter['product']."';");
			
			$purchase_products = $query2->rows;
		
		}
		elseif(($filter['date_start'] != '') && ($filter['date_end'] != ''))
		{
			$query1 = $this->db->query("SELECT
		".DB_PREFIX."order_product.product_id
		, ".DB_PREFIX."order_product.name
		, ".DB_PREFIX."order_product.quantity
		FROM
			".DB_PREFIX."order
		INNER JOIN ".DB_PREFIX."order_product 
			ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5 AND ".DB_PREFIX."order.date_modified BETWEEN '".$filter['date_start']." 00:00:00.00' AND '".$filter['date_end']." 23:59:59.999';");
			
			$sale_products = $query1->rows;
			
			$query2 = $this->db->query("SELECT
		oc_po_product.product_id
		, oc_po_product.name
		, oc_po_product.received_products as quantity
		FROM
			oc_po_order
		INNER JOIN oc_po_product 
			ON (oc_po_order.id = oc_po_product.order_id) WHERE oc_po_order.receive_bit = 1 AND delete_bit=1 AND oc_po_order.receive_date BETWEEN '".$filter['date_start']."' AND '".$filter['date_end']."';");
			
			$purchase_products = $query2->rows;
		}
		elseif($filter['product'] != '--product--')
		{
			$query1 = $this->db->query("SELECT
		".DB_PREFIX."order_product.product_id
		, ".DB_PREFIX."order_product.name
		, ".DB_PREFIX."order_product.quantity
		FROM
			".DB_PREFIX."order
		INNER JOIN ".DB_PREFIX."order_product 
			ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5 AND ".DB_PREFIX."order_product.name = '".$filter['product']."';");
			
			$sale_products = $query1->rows;
			
			$query2 = $this->db->query("SELECT
		oc_po_product.product_id
		, oc_po_product.name
		, oc_po_product.received_products as quantity
		FROM
			oc_po_order
		INNER JOIN oc_po_product 
			ON (oc_po_order.id = oc_po_product.order_id) WHERE oc_po_order.receive_bit = 1 AND delete_bit=1 AND oc_po_product.name = '".$filter['product']."';");
			
			$purchase_products = $query2->rows;
			
		}
		/*sales*/
		
		$quantity = 0;
		
		for($i=0;$i<count($sale_products); $i++)
		{
			if($sale_products[$i] != '')
			{
				$quantity = $quantity + $sale_products[$i]['quantity'];
				for($j=0; $j<count($sale_products); $j++)
				{
					if(($sale_products[$i]['product_id'] == $sale_products[$j]['product_id']) && ($i != $j))
					{
						$quantity = $quantity + $sale_products[$j]['quantity'];
						unset($sale_products[$j]);
					}
				}
				$sale_products[$i]['sales_quantity'] = $quantity;
				$sale_products = array_values(array_filter($sale_products));
				$quantity = 0;
			}
		}
		
		/*sales*/
		
		/*purchase*/
		
		$quantity = 0;
		
		for($i=0;$i<count($purchase_products); $i++)
		{
			$quantity = $quantity + $purchase_products[$i]['quantity'];
			for($j=0; $j<count($purchase_products); $j++)
			{
				if(($purchase_products[$i]['product_id'] == $purchase_products[$j]['product_id']) && ($i != $j))
				{
					$quantity = $quantity + $purchase_products[$j]['quantity'];
					unset($purchase_products[$j]);
				}
			}
			$purchase_products[$i]['quantity'] = $quantity;
			$purchase_products = array_values(array_filter($purchase_products));
			$quantity = 0;
		
		}
		
		$quantity = 0;
		
		for($i=0;$i<count($purchase_products); $i++)
		{
			$quantity = $quantity + $purchase_products[$i]['quantity'];
			for($j=0; $j<count($purchase_products); $j++)
			{
				if(($purchase_products[$i]['product_id'] == $purchase_products[$j]['product_id']) && ($i != $j))
				{
					$quantity = $quantity + $purchase_products[$j]['quantity'];
					unset($purchase_products[$j]);
				}
			}
			$purchase_products[$i]['purchase_quantity'] = $quantity;
			$purchase_products = array_values(array_filter($purchase_products));
			$quantity = 0;
		
		}
		
		/*purchase*/
		
		for($i=0;$i<count($sale_products); $i++)
		{
			for($j=0; $j<count($purchase_products); $j++)
			{
				if($sale_products[$i]['product_id'] == $purchase_products[$j]['product_id'])
				{
					$sale_products[$i]['purchase_quantity'] = $purchase_products[$j]['purchase_quantity'];
					unset($purchase_products[$j]);
					$purchase_products = array_values(array_filter($purchase_products));
					break;
				}
			}
		}
		
		$data = array_merge($sale_products,$purchase_products);
		
		for($i=0; $i<count($data); $i++)
		{
			unset($data[$i]['quantity']);
		}
		$data = array_filter($data);
		
		return $data;
	}
	
	
	public function view_inout_details($detail)
	{
		$sale_products = array();
		$purchase_products = array();
		if($detail['date_start'] != '')
		{
			$detail['date_start'] = strtotime($detail['date_start']);
			$detail['date_start'] = date('Y-m-d',$detail['date_start']);
		}
		if($detail['date_end'] != '')
		{
			$detail['date_end'] = strtotime($detail['date_end']);
			$detail['date_end'] = date('Y-m-d',$detail['date_end']);
		}
		
		$common_query1 = "SELECT
			".DB_PREFIX."order_product.product_id
			, ".DB_PREFIX."order_product.name
			, ".DB_PREFIX."order_product.quantity
			, ".DB_PREFIX."order.date_modified
			FROM
				".DB_PREFIX."order
			INNER JOIN ".DB_PREFIX."order_product 
				ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5 AND ".DB_PREFIX."order_product.product_id = ".$detail['product_id'];
		
		/*if($detail['report_bit'] == 2)
		{
			$common_query2 = "SELECT
				oc_po_product.product_id
				, oc_po_product.name
				, oc_po_product.received_products AS quantity
				, oc_po_order.receive_date
				, oc_po_supplier.first_name
				, oc_po_supplier.last_name
				, oc_po_receive_details.quantity AS received_quantity
			FROM
				  oc_po_receive_details
				INNER JOIN oc_po_supplier 
					ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
				INNER JOIN `oc_po_product` 
					ON (oc_po_receive_details.product_id = oc_po_product.id)
				INNER JOIN oc_po_order 
					ON (oc_po_order.id = oc_po_receive_details.order_id) WHERE oc_po_order.receive_bit = 1 AND oc_po_order.delete_bit=1 AND oc_po_product.product_id =".$detail['product_id'];
		}
		else
		{*/
		
			$common_query2 = "SELECT
				oc_po_product.product_id
				, oc_po_product.name
				, oc_po_product.received_products as quantity
				, oc_po_order.receive_date
				FROM
					oc_po_order
				INNER JOIN oc_po_product 
					ON (oc_po_order.id = oc_po_product.order_id) WHERE oc_po_order.receive_bit = 1 AND delete_bit=1 AND oc_po_product.product_id = ".$detail['product_id'];
		/*}*/
		if(($detail['date_start'] != '') && ($detail['date_end'] != '') && ($detail['product'] != '--product--'))
		{
			if($detail['report_bit'] == 1)
			{
				$query_string1 = $common_query1 . " AND ".DB_PREFIX."order.date_modified BETWEEN '".$detail['date_start']." 00:00:00.00' AND '".$detail['date_end']." 23:59:59.999' AND ".DB_PREFIX."order_product.name = '".$detail['product']."';";
				
				$query1 = $this->db->query($query_string1);
				
				$sale_products = $query1->rows;
				
				$query_string2 = $common_query2 . " AND oc_po_order.receive_date BETWEEN '".$detail['date_start']."' AND '".$detail['date_end']."' AND oc_po_product.name = '".$detail['product']."';";
				
				$query2 = $this->db->query($query_string2);
				
				$purchase_products = $query2->rows;
			}
			elseif($detail['report_bit'] == 2)
			{
				$query_string2 = $common_query2 . " AND oc_po_order.receive_date BETWEEN '".$detail['date_start']."' AND '".$detail['date_end']."' AND oc_po_product.name = '".$detail['product']."';";
				
				$query2 = $this->db->query($query_string2);
				
				$purchase_products = $query2->rows;
			}
			elseif($detail['report_bit'] == 3)
			{
				$query_string1 = $common_query1 . " AND ".DB_PREFIX."order.date_modified BETWEEN '".$detail['date_start']." 00:00:00.00' AND '".$detail['date_end']." 23:59:59.999' AND ".DB_PREFIX."order_product.name = '".$detail['product']."';";
				
				$query1 = $this->db->query($query_string1);
				
				$sale_products = $query1->rows;
			}
		
		}
		elseif(($detail['date_start'] != '') && ($detail['date_end'] != ''))
		{
			if($detail['report_bit'] == 1)
			{
				$query_string1 = $common_query1 . " AND ".DB_PREFIX."order.date_modified BETWEEN '".$detail['date_start']." 00:00:00.00' AND '".$detail['date_end']." 23:59:59.999';";
				
				$query1 = $this->db->query($query_string1);
				
				$sale_products = $query1->rows;
				
				$query_string2 = $common_query2 . " AND oc_po_order.receive_date BETWEEN '".$detail['date_start']."' AND '".$detail['date_end']."';";
				
				$query2 = $this->db->query($query_string2);
				
				$purchase_products = $query2->rows;
			}
			elseif($detail['report_bit'] == 2)
			{
				$query_string2 = $common_query2 . " AND oc_po_order.receive_date BETWEEN '".$detail['date_start']."' AND '".$detail['date_end']."';";
				
				$query2 = $this->db->query($query_string2);
				
				$purchase_products = $query2->rows;
			}
			elseif($detail['report_bit'] == 3)
			{
				$query_string1 = $common_query1 . " AND ".DB_PREFIX."order.date_modified BETWEEN '".$detail['date_start']." 00:00:00.00' AND '".$detail['date_end']." 23:59:59.999';";
				
				$query1 = $this->db->query($query_string1);
				
				$sale_products = $query1->rows;
			}
		}
		elseif($detail['product'] != '--product--')
		{
			if($detail['report_bit'] == 1)
			{
				$query_string1 = $common_query1 . " AND ".DB_PREFIX."order_product.name = '".$detail['product']."';";
				
				$query1 = $this->db->query($query_string1);
				
				$sale_products = $query1->rows;
				
				$query_string2 = $common_query2 . " AND oc_po_product.name = '".$detail['product']."';";
				
				$query2 = $this->db->query($query_string2);
				
				$purchase_products = $query2->rows;
			}
			elseif($detail['report_bit'] == 2)
			{
				$query_string2 = $common_query2 . " AND oc_po_product.name = '".$detail['product']."';";
				
				$query2 = $this->db->query($query_string2);
				
				$purchase_products = $query2->rows;
			}
			elseif($detail['report_bit'] == 3)
			{
				$query_string1 = $common_query1 . " AND ".DB_PREFIX."order_product.name = '".$detail['product']."';";
				
				$query1 = $this->db->query($query_string1);
				
				$sale_products = $query1->rows;
			}
			
		}
		else
		{
			if($detail['report_bit'] == 1)
			{
				$query1 = $this->db->query($common_query1);
				
				$sale_products = $query1->rows;
				
				$query2 = $this->db->query($common_query2);
				
				$purchase_products = $query2->rows;
			}
			elseif($detail['report_bit'] == 2)
			{
				$query2 = $this->db->query($common_query2);
				
				$purchase_products = $query2->rows;
			}
			if($detail['report_bit'] == 3)
			{
				$query1 = $this->db->query($common_query1);
				
				$sale_products = $query1->rows;
			}
		}
		
		/*if($detail['report_bit'] == 2)
		{
			for($i=0;$i<count($purchase_products); $i++)
			{
				for($j=0; $j<count($purchase_products); $j++)
				{
					if(($purchase_products[$i]['product_id'] == $purchase_products[$j]['product_id']) && ($purchase_products[$i]['receive_date'] == $purchase_products[$j]['receive_date']))
					{
						$purchase_products[$i]['rd_quantity'][$j] = $purchase_products[$j]['received_quantity'];
						$purchase_products[$i]['suppliers'][$j] = $purchase_products[$j]['first_name'] . " " . $purchase_products[$j]['last_name'];
						
						if($i!= $j)
						{
							unset($purchase_products[$j]);
						}
					}
				}
				
				$purchase_products = array_values(array_filter($purchase_products));
			}
		}*/
		
		for($i=0; $i<count($sale_products); $i++)
		{
			$sale_products[$i]['date_modified'] = strstr($sale_products[$i]['date_modified'], ' ', true);
		}
		
		if((count($sale_products) > 0) && (count($purchase_products) > 0))
		{
			for($i=0;$i<count($sale_products); $i++)
			{
				for($j=0; $j<count($purchase_products); $j++)
				{
					if($sale_products[$i]['date_modified'] == $purchase_products[$j]['receive_date'])
					{
						$sale_products[$i]['purchase_quantity'] = $purchase_products[$j]['quantity'];
						unset($purchase_products[$j]);
						$purchase_products = array_values(array_filter($purchase_products));
					}
				}
			}
			
			$data['sale_products'] = $sale_products;
			$data['purchase_products'] = $purchase_products;	
			return $data;
		}
		elseif((count($sale_products) > 0))
		{
			$data['sale_products'] = $sale_products;	
			return $data;
		}if((count($purchase_products) > 0))
		{
			$data['purchase_products'] = $purchase_products;	
			return $data;
		}
	}
	
	public function getDeadProducts($filter = array())
	{
		//getting all products
		$this->load->model('catalog/product');
		$products = array();
		$products = $this->model_catalog_product->getProducts($products);
		$i = 0;
		foreach($products as $product)
		{
			$data[$i]['name'] = $product['name'];
			$data[$i]['product_id'] = $product['product_id'];
			$data[$i]['sales_quantity'] = 0;
			$i++;
		}
		$all_products = $data;
		
		//getting sold products
		
		if(count(array_filter($filter)) > 0)
		{
			if($filter['date_start'] != '')
			{
				$filter['date_start'] = strtotime($filter['date_start']);
				$filter['date_start'] = date('Y-m-d',$filter['date_start']);
			}
			if($filter['date_end'] != '')
			{
				$filter['date_end'] = strtotime($filter['date_end']);
				$filter['date_end'] = date('Y-m-d',$filter['date_end']);
			}
			
			if(($filter['date_start'] != '') && ($filter['date_end'] == ''))
			{
				$filter['date_end'] = date('Y-m-d');
			}
			
			$query = $this->db->query("SELECT
			".DB_PREFIX."order_product.product_id
			, ".DB_PREFIX."order_product.name
			, ".DB_PREFIX."order_product.quantity
			FROM
				".DB_PREFIX."order
			INNER JOIN ".DB_PREFIX."order_product 
				ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5  AND ".DB_PREFIX."order.date_modified BETWEEN '".$filter['date_start']." 00:00:00.00' AND '".$filter['date_end']." 23:59:59.999'");
			
			$sold_products = $query->rows;
		}
		else
		{
			$query = $this->db->query("SELECT
			".DB_PREFIX."order_product.product_id
			, ".DB_PREFIX."order_product.name
			, ".DB_PREFIX."order_product.quantity
			FROM
				".DB_PREFIX."order
			INNER JOIN ".DB_PREFIX."order_product 
				ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5");
				
			$sold_products = $query->rows;
		}
		
		/*For counting total quantity of sold products, individual products*/
		$quantity = 0;
		
		for($i=0;$i<count($sold_products); $i++)
		{
			if($sold_products[$i] != '')
			{
				$quantity = $quantity + $sold_products[$i]['quantity'];
				for($j=0; $j<count($sold_products); $j++)
				{
					if(($sold_products[$i]['product_id'] == $sold_products[$j]['product_id']) && ($i != $j))
					{
						$quantity = $quantity + $sold_products[$j]['quantity'];
						unset($sold_products[$j]);
					}
				}
				$sold_products[$i]['sales_quantity'] = $quantity;
				$sold_products = array_values(array_filter($sold_products));
				$quantity = 0;
			}
		}
		/*For counting total quantity of sold products, individual products*/
		
		/*to remove the quantity index from sold products*/
		for($i = 0; $i<count($sold_products); $i++)
		{
			unset($sold_products[$i]['quantity']);
		}
		/*to remove the quantity index from sold products*/
		
		/*to merge the all products and sold products*/
		for($i = 0; $i<count($sold_products); $i++)
		{
			for($j =0; $j<count($all_products); $j++)
			{
				if($sold_products[$i]['product_id'] == $all_products[$j]['product_id'])
				{
					unset($all_products[$j]);
					break;
				}
			}
			$all_products = array_values(array_filter($all_products));
		}
		/*to merge the all products and sold products*/
		$data = array_merge($sold_products,$all_products);
		/*For getting the stock*/
		$query = $this->db->query("SELECT
		".DB_PREFIX."product.product_id
		, ".DB_PREFIX."product_description.name
		, ".DB_PREFIX."product.quantity
		FROM
			".DB_PREFIX."product
		INNER JOIN ".DB_PREFIX."product_description 
			ON (".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id)");
		$stock = $query->rows;
		
		for($i = 0; $i < count($data); $i++)
		{
			for($j = 0; $j < count($stock); $j++)
			{
				if($data[$i]['product_id'] == $stock[$j]['product_id'])
				{
					$data[$i]['quantity'] = $stock[$j]['quantity'];
					unset($stock[$j]);
					break;
				}
			}
			$stock = array_values(array_filter($stock));
		}
		
		/*for getting the stock*/
		
		return $data;
	}
	
	public function best_products($filter = array())
	{
		if(count(array_filter($filter)) > 0)
		{
			if($filter['date_start'] != '')
			{
				$filter['date_start'] = strtotime($filter['date_start']);
				$filter['date_start'] = date('Y-m-d',$filter['date_start']);
			}
			if($filter['date_end'] != '')
			{
				$filter['date_end'] = strtotime($filter['date_end']);
				$filter['date_end'] = date('Y-m-d',$filter['date_end']);
			}
			
			if(($filter['date_start'] != '') && ($filter['date_end'] == ''))
			{
				$filter['date_end'] = date('Y-m-d');
			}
			
			$query = $this->db->query("SELECT
			".DB_PREFIX."order_product.product_id
			, ".DB_PREFIX."order_product.name
			, ".DB_PREFIX."order_product.quantity
			FROM
				".DB_PREFIX."order
			INNER JOIN ".DB_PREFIX."order_product 
				ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5  AND ".DB_PREFIX."order.date_modified BETWEEN '".$filter['date_start']." 00:00:00.00' AND '".$filter['date_end']." 23:59:59.999'");
			
			$sold_products = $query->rows;
		}
		else
		{
			$query = $this->db->query("SELECT
			".DB_PREFIX."order_product.product_id
			, ".DB_PREFIX."order_product.name
			, ".DB_PREFIX."order_product.quantity
			FROM
				".DB_PREFIX."order
			INNER JOIN ".DB_PREFIX."order_product 
				ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5");
				
			$sold_products = $query->rows;
		}
		
		/*For counting total quantity of sold products, individual products*/
		$quantity = 0;
		
		for($i=0;$i<count($sold_products); $i++)
		{
			if($sold_products[$i] != '')
			{
				$quantity = $quantity + $sold_products[$i]['quantity'];
				for($j=0; $j<count($sold_products); $j++)
				{
					if(($sold_products[$i]['product_id'] == $sold_products[$j]['product_id']) && ($i != $j))
					{
						$quantity = $quantity + $sold_products[$j]['quantity'];
						unset($sold_products[$j]);
					}
				}
				$sold_products[$i]['sales_quantity'] = $quantity;
				$sold_products = array_values(array_filter($sold_products));
				$quantity = 0;
			}
		}
		/*For counting total quantity of sold products, individual products*/
		
		/*to remove the quantity index from sold products*/
		for($i = 0; $i<count($sold_products); $i++)
		{
			unset($sold_products[$i]['quantity']);
		}
		/*to remove the quantity index from sold products*/
		
		$query = $this->db->query("SELECT
		".DB_PREFIX."product.product_id
		, ".DB_PREFIX."product_description.name
		, ".DB_PREFIX."product.quantity
		FROM
			".DB_PREFIX."product
		INNER JOIN ".DB_PREFIX."product_description 
			ON (".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id)");
		$stock = $query->rows;
		
		for($i = 0; $i < count($sold_products); $i++)
		{
			for($j = 0; $j < count($stock); $j++)
			{
				if($sold_products[$i]['product_id'] == $stock[$j]['product_id'])
				{
					$sold_products[$i]['quantity'] = $stock[$j]['quantity'];
					unset($stock[$j]);
					break;
				}
			}
			$stock = array_values(array_filter($stock));
		}
		
		return $sold_products;
	}
}
?>