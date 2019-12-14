<?php
class ModelPurchaseChart extends Model {
	public function getChartData()
	{
		/*$query = $this->db->query("SELECT
		oc_order_product.quantity
		, oc_order_product.product_id
		, oc_order_product.name
		FROM
		oc_order
		INNER JOIN oc_order_product 
			ON (oc_order.order_id = oc_order_product.order_id) WHERE oc_order.date_added BETWEEN '2016-07-12 00:00:00.00' AND '2016-07-12 23:59:59.999'");*/
		
		$query = $this->db->query("SELECT
		".DB_PREFIX."order_product.quantity
		, ".DB_PREFIX."order_product.product_id
		, ".DB_PREFIX."order_product.name
		FROM
		".DB_PREFIX."order
		INNER JOIN ".DB_PREFIX."order_product 
			ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5;");
		
		
		$data = $query->rows;
		$quantity = 0; 
		for($i=0; $i<count($data); $i++)
		{
			if($data[$i]!= '')
			{
				$quantity = $quantity + $data[$i]['quantity'];
				for($j=0; $j<count($data); $j++)
				{
					if(($data[$i]['product_id'] == $data[$j]['product_id']) && ($i != $j))
					{
						$quantity = $quantity + $data[$j]['quantity'];
						unset($data[$j]);
					}
				}
				$data[$i]['quantity'] = $quantity;
				$data = array_values(array_filter($data));
				$quantity = 0;
			}
		}
		
		return $data;
	}
	public function getChartFilterData($data)
	{
		if($data['date_start'] != '')
		{
			$data['date_start'] = strtotime($data['date_start']);
			$data['date_start'] = date('Y-m-d',$data['date_start']);
		}
		if($data['date_end'] != '')
		{
			$data['date_end'] = strtotime($data['date_end']);
			$data['date_end'] = date('Y-m-d',$data['date_end']);
		}
		
		if(($data['date_start'] != '') && ($data['date_end'] == ''))
		{
			$data['date_end'] = date('Y-m-d');
		}
		
		$query = $this->db->query("SELECT
		".DB_PREFIX."order_product.quantity
		, ".DB_PREFIX."order_product.product_id
		, ".DB_PREFIX."order_product.name
		FROM
		".DB_PREFIX."order
		INNER JOIN ".DB_PREFIX."order_product 
			ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id) WHERE ".DB_PREFIX."order.order_status_id = 5 AND ".DB_PREFIX."order.date_added BETWEEN '".$data['date_start']." 00:00:00.00' AND '".$data['date_end']." 23:59:59.999' AND ".DB_PREFIX."order.date_modified BETWEEN '".$data['date_start']." 00:00:00.00' AND '".$data['date_end']." 23:59:59.999'");
		
		$data = $query->rows;
		$quantity = 0; 
		for($i=0; $i<count($data); $i++)
		{
			if($data[$i]['quantity'] != '')
			{
				$quantity = $quantity + $data[$i]['quantity'];
				for($j=0; $j<count($data); $j++)
				{
					if(($data[$i]['product_id'] == $data[$j]['product_id']) && ($i != $j))
					{
						$quantity = $quantity + $data[$j]['quantity'];
						unset($data[$j]);
					}
				}
				$data[$i]['quantity'] = $quantity;
				$data = array_values(array_filter($data));
				$quantity = 0;
			}
		}
		return $data;
	
	}
	
	
	//purchase
	
	public function getPurchaseChartData()
	{
		$query = $this->db->query("SELECT
		oc_po_product.quantity
		, oc_po_product.product_id
		, oc_po_product.name
		FROM
		oc_po_order
			INNER JOIN oc_po_product 
        ON (oc_po_order.id = oc_po_product.order_id) WHERE receive_bit = 1 AND delete_bit = 1;");
		
		
		$data = $query->rows;
		
		$quantity = 0;
		
		for($i=0; $i<count($data); $i++)
		{
			if($data[$i]!= '')
			{
				$quantity = $quantity + $data[$i]['quantity'];
				for($j=0; $j<count($data); $j++)
				{
					if(($data[$i]['product_id'] == $data[$j]['product_id']) && ($i != $j))
					{
						$quantity = $quantity + $data[$j]['quantity'];
						unset($data[$j]);
					}
				}
				$data[$i]['quantity'] = $quantity;
				$data = array_values(array_filter($data));
				$quantity = 0;
			}
		}
		
		$quantity = 0;
		
		for($i=0;$i<count($data); $i++)
		{
			$quantity = $quantity + $data[$i]['quantity'];
			for($j=0; $j<count($data); $j++)
			{
				if(($data[$i]['product_id'] == $data[$j]['product_id']) && ($i != $j))
				{
					$quantity = $quantity + $data[$j]['quantity'];
					unset($data[$j]);
				}
			}
			$data[$i]['purchase_quantity'] = $quantity;
			$data = array_values(array_filter($data));
			$quantity = 0;
		
		}
		
		for($i=0; $i<count($data); $i++)
		{
			unset($data[$i]['quantity']);
		}
		$data = array_filter($data);
		
		return $data;
	}
	
	public function getPurchaseChartFilterData($data)
	{
		if($data['date_start'] != '')
		{
			$data['date_start'] = strtotime($data['date_start']);
			$data['date_start'] = date('Y-m-d',$data['date_start']);
		}
		if($data['date_end'] != '')
		{
			$data['date_end'] = strtotime($data['date_end']);
			$data['date_end'] = date('Y-m-d',$data['date_end']);
		}
		
		if(($data['date_start'] != '') && ($data['date_end'] == ''))
		{
			$data['date_end'] = date('Y-m-d');
		}
		
		$query = $this->db->query("SELECT
		oc_po_product.quantity
		, oc_po_product.product_id
		, oc_po_product.name
		FROM
		oc_po_order
			INNER JOIN oc_po_product 
        ON (oc_po_order.id = oc_po_product.order_id) WHERE receive_bit = 1 AND delete_bit = 1 AND receive_date BETWEEN '".$data['date_start']."' AND '".$data['date_end']."'");
		
		$data = $query->rows;
		
		$quantity = 0;
		
		for($i=0; $i<count($data); $i++)
		{
			if($data[$i]!= '')
			{
				$quantity = $quantity + $data[$i]['quantity'];
				for($j=0; $j<count($data); $j++)
				{
					if(($data[$i]['product_id'] == $data[$j]['product_id']) && ($i != $j))
					{
						$quantity = $quantity + $data[$j]['quantity'];
						unset($data[$j]);
					}
				}
				$data[$i]['quantity'] = $quantity;
				$data = array_values(array_filter($data));
				$quantity = 0;
			}
		}
		
		$quantity = 0;
		
		for($i=0;$i<count($data); $i++)
		{
			$quantity = $quantity + $data[$i]['quantity'];
			for($j=0; $j<count($data); $j++)
			{
				if(($data[$i]['product_id'] == $data[$j]['product_id']) && ($i != $j))
				{
					$quantity = $quantity + $data[$j]['quantity'];
					unset($data[$j]);
				}
			}
			$data[$i]['purchase_quantity'] = $quantity;
			$data = array_values(array_filter($data));
			$quantity = 0;
		
		}
		
		for($i=0; $i<count($data); $i++)
		{
			unset($data[$i]['quantity']);
		}
		$data = array_filter($data);
		
		return $data;
	}
	
	
}
?>