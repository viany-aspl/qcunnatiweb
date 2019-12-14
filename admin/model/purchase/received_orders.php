<?php
	class ModelPurchaseReceivedOrders extends Model {
		public function get_all_received_orders()
		{
			$query = $this->db->query("SELECT
			oc_po_order.id AS order_id
			, oc_po_order.order_date
			, oc_po_order.receive_date
			, oc_po_product.id AS product_id
			, oc_po_product.name
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			, oc_po_product.quantity
			, oc_po_product.received_products
			, oc_po_receive_details.quantity AS rd_quantity
			, oc_po_receive_details.price
			FROM
			oc_po_receive_details
			INNER JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN oc_po_product 
				ON (oc_po_product.id = oc_po_receive_details.product_id)
			INNER JOIN oc_po_order 
				ON (oc_po_order.id = oc_po_receive_details.order_id) WHERE oc_po_order.delete_bit = 1 AND oc_po_order.receive_bit = 1;");
			/*$query = $this->db->query("SELECT
			oc_po_order.id as order_id
			, oc_po_order.order_date
			, oc_po_order.receive_date
			, oc_po_product.id as product_id
			, oc_po_product.name
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			, oc_po_product.quantity
			, oc_po_product.received_products
			, oc_po_receive_details.quantity as rd_quantity
			, oc_po_receive_details.price
			FROM
			inventorysystem.oc_po_order
			INNER JOIN inventorysystem.oc_po_supplier
				ON (oc_po_supplier.id = oc_po_receive_details.supplier_id)
			INNER JOIN inventorysystem.oc_po_product 
				ON (oc_po_order.id = oc_po_product.order_id)
			INNER JOIN inventorysystem.oc_po_receive_details 
				ON (oc_po_product.id = oc_po_receive_details.product_id) WHERE oc_po_order.delete_bit =" . 1 ." AND oc_po_order.receive_bit =". 1);*/
			$order_details = $query->rows;
			//print_r($order_details);
			//exit;
			$total_price = 0;
			$total_products = 0;
			$done = 0;
			for($i =0; $i<count($order_details); $i++)
			{
				if($order_details[$i] != "")
				{
					$total_products += $order_details[$i]['quantity'];
					for($j = 0; $j<count($order_details); $j++)
					{
						if($order_details[$j] != "")
						{
							if(($order_details[$i]['order_id'] == $order_details[$j]['order_id']))
							{
								$total_price += $order_details[$j]['rd_quantity'] * $order_details[$j]['price'];
								$products[$j] = $order_details[$j]['name'];
								$suppliers[$j] = $order_details[$j]['first_name'] . " " . $order_details[$j]['last_name'];
								$rcvd_qnty[$j] = $order_details[$j]['rd_quantity'];
								$prices[$j] = $order_details[$j]['price'];
								if($order_details[$i]['product_id'] != $order_details[$j]['product_id'])
								{
									if($done != $order_details[$j]['product_id'])
									{
										$total_products += $order_details[$j]['quantity'];
										$done = $order_details[$j]['product_id'];
									}
								}
								if($j!=$i)
								{
									$order_details[$j] = "";
								}
							}
						}
					}
					
				}
				if($total_price != 0 && $total_products != 0)
				{
					$order_details[$i]['products'] = array_values($products);
					$order_details[$i]['suppliers'] = array_values($suppliers);
					$order_details[$i]['rcvd_qnty'] = array_values($rcvd_qnty);
					$order_details[$i]['prices'] = array_values($prices);
					$order_details[$i]['total_price'] = $total_price;
					$order_details[$i]['total_products'] = $total_products;
				}
				unset($products);
				unset($suppliers);
				unset($rcvd_qnty);
				unset($prices);
				$total_price = 0;
				$total_products = 0;
				
			}
			$order_details = array_values(array_filter($order_details));
			return $order_details;
		}
		public function get_filtered_orders($data)
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
			
			
			$order_details = array();
			
			$query_string = "SELECT
			oc_po_order.id AS order_id
			, oc_po_order.order_date
			, oc_po_order.receive_date
			, oc_po_product.id AS product_id
			, oc_po_product.name
			, oc_po_supplier.first_name
			, oc_po_supplier.last_name
			, oc_po_product.quantity
			, oc_po_product.received_products
			, oc_po_receive_details.quantity AS rd_quantity
			, oc_po_receive_details.price
			FROM
			oc_po_receive_details
			INNER JOIN oc_po_supplier 
				ON (oc_po_receive_details.supplier_id = oc_po_supplier.id)
			INNER JOIN oc_po_product 
				ON (oc_po_product.id = oc_po_receive_details.product_id)
			INNER JOIN oc_po_order 
				ON (oc_po_order.id = oc_po_receive_details.order_id) WHERE oc_po_order.delete_bit = 1 AND oc_po_order.receive_bit = 1";
			
			/*if($data['start_date'] != '' && $data['end_date'] != '' && $data['filter_supplier'] != '--supplier--' && $data['filter_product'] != '--product--' && $data['order_id'] != '')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND oc_po_product.name = '" .$data['filter_product']."' AND oc_po_order.id = ".$data['order_id'] . " AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['start_date'] != '' && $data['end_date'] != '' && $data['filter_supplier'] != '--supplier--' && $data['order_id'] != '')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND oc_po_order.id = ".$data['order_id'] . " AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['start_date'] != '' && $data['end_date'] != '' && $data['filter_product'] != '--product--' && $data['order_id'] != '')
			{
				$query_string = $query_string . " AND oc_po_product.name = '" .$data['filter_product']."' AND oc_po_order.id = ".$data['order_id'] . " AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['start_date'] != '' && $data['end_date'] != '' && $data['filter_supplier'] != '--supplier--' && $data['filter_product'] != '--product--')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND oc_po_product.name = '" .$data['filter_product']."' AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['start_date'] != '' && $data['end_date'] != '' && $data['filter_supplier'] != '--supplier--')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['start_date'] != '' && $data['end_date'] != '' && $data['filter_product'] != '--product--')
			{
				$query_string = $query_string . " AND oc_po_product.name = '" .$data['filter_product']."' AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['filter_supplier'] != '--supplier--' && $data['filter_product'] != '--product--' && $data['order_id'] != '')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND oc_po_product.name = '" .$data['filter_product']."' AND oc_po_order.id = ".$data['order_id'];
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['start_date'] != '' && $data['end_date'] != '' && $data['order_id'] != '')
			{
				$query_string = $query_string . " AND oc_po_order.id = ".$data['order_id'] . " AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['start_date'] != '' && $data['end_date'] != '')
			{
				$query_string = $query_string . " AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['filter_supplier'] != '--supplier--' && $data['filter_product'] != '--product--')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND oc_po_product.name = '" .$data['filter_product'] . "'";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['filter_supplier'] != '--supplier--' && $data['order_id'] != '')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '".$name[1]."' AND oc_po_product.order_id = '" .$data['order_id'] . "'";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['filter_product'] != '--product--' && $data['order_id'] != '')
			{
				$query_string = $query_string . " AND oc_po_product.name = '" .$data['filter_product']."' AND oc_po_product.order_id = '" .$data['order_id'] . "'";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['filter_supplier'] != '--supplier--')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '" . $name[1] . "'";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['filter_product'] != '--product--')
			{
				$query_string = $query_string . " AND oc_po_product.name = '" .$data['filter_product']."'";
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}
			elseif($data['order_id'] != '')
			{
				$query_string = $query_string . " AND oc_po_order.id = ".$data['order_id'];
				$query = $this->db->query($query_string);
				$order_details = $query->rows;
			}*/
			
			
			if($data['filter_supplier'] != '--supplier--')
			{
				$name = explode(' ',$data['filter_supplier']);
				$query_string = $query_string . " AND oc_po_supplier.first_name = '" .$name[0]."' AND oc_po_supplier.last_name = '" . $name[1] . "'";
			}
			if($data['filter_product'] != '--product--')
			{
				$query_string = $query_string . " AND oc_po_product.name = '" .$data['filter_product']."'";
			}
			if($data['order_id'] != '')
			{
				$query_string = $query_string . " AND oc_po_order.id = ".$data['order_id'];
			}
			if($data['start_date'] != '' && $data['end_date'] != '')
			{
				$query_string = $query_string . " AND (receive_date BETWEEN '".$data['start_date']."' AND '".$data['end_date']."')";
			}
			
			$query = $this->db->query($query_string);
			$order_details = $query->rows;
			
			$total_price = 0;
			$total_products = 0;
			$done = 0;
			for($i =0; $i<count($order_details); $i++)
			{
				if($order_details[$i] != "")
				{
					$total_products += $order_details[$i]['quantity'];
					for($j = 0; $j<count($order_details); $j++)
					{
						if($order_details[$j] != "")
						{
							if(($order_details[$i]['order_id'] == $order_details[$j]['order_id']))
							{
								$total_price += $order_details[$j]['rd_quantity'] * $order_details[$j]['price'];
								$products[$j] = $order_details[$j]['name'];
								$suppliers[$j] = $order_details[$j]['first_name'] . " " . $order_details[$j]['last_name'];
								$rcvd_qnty[$j] = $order_details[$j]['rd_quantity'];
								$prices[$j] = $order_details[$j]['price'];
								if($order_details[$i]['product_id'] != $order_details[$j]['product_id'])
								{
									if($done != $order_details[$j]['product_id'])
									{
										$total_products += $order_details[$j]['quantity'];
										$done = $order_details[$j]['product_id'];
									}
								}
								if($j!=$i)
								{
									$order_details[$j] = "";
								}
							}
						}
					}
					
				}
				if($total_price != 0 && $total_products != 0)
				{
					$order_details[$i]['products'] = array_values($products);
					$order_details[$i]['suppliers'] = array_values($suppliers);
					$order_details[$i]['rcvd_qnty'] = array_values($rcvd_qnty);
					$order_details[$i]['prices'] = array_values($prices);
					$order_details[$i]['total_price'] = $total_price;
					$order_details[$i]['total_products'] = $total_products;
				}
				unset($products);
				unset($suppliers);
				unset($rcvd_qnty);
				unset($prices);
				$total_price = 0;
				$total_products = 0;
				
			}
			$order_details = array_values(array_filter($order_details));
			return $order_details;
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