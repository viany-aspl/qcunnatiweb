<?php
class ModelPurchaseSaleOffer extends Model {
	public function filter($filter)
	{
		//echo $filter['filter_category'];
		//exit;
			
		if(isset($filter['filter_category']))
		{
			$category = $filter['filter_category'];
			if (preg_match('/&gt;/',$filter['filter_category']))
			{
				$pos = strrpos($filter['filter_category'], '&gt;');
				$filter['filter_category'] = substr($filter['filter_category'],$pos+4);
				$filter['filter_category'] = htmlentities($filter['filter_category'], null, 'utf-8');
				$filter['filter_category'] = str_replace("&nbsp;", "", $filter['filter_category']);
			}
		}
		
		if(isset($filter['filter_option_value']))
		{
			$option_value = $filter['filter_option_value'];
		}
		
		if($filter['filter_category'] != '' && $filter['filter_option_value'] != '')
		{
			$query = $this->db->query("SELECT name,category_id FROM ".DB_PREFIX."category_description WHERE name='".$filter['filter_category']."'");
			
			$query = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product_to_category WHERE category_id = " . $query->row['category_id']);
			
			$category_product_ids = $query->rows;
			
			//print_r($category_product_ids);
			
			$query = $this->db->query("SELECT name,option_value_id FROM ".DB_PREFIX."option_value_description WHERE name = '".$filter['filter_option_value']."';");
			
			$query = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product_option_value WHERE option_value_id = " . $query->row['option_value_id']);
			
			$option_product_ids = $query->rows;
			
			//print_r($option_product_ids);
			
			//getting common from arrays
			$product_ids = array_uintersect($category_product_ids, $option_product_ids,function($val1, $val2){
								return strcmp($val1['product_id'], $val2['product_id']);
							});
			
			$product_ids = implode(',',array_map(function ($entry) {return $entry['product_id'];},$product_ids));
			
			if($product_ids != '')
			{
				$sql = "SELECT
				".DB_PREFIX."order_product.product_id
				,".DB_PREFIX."order_product.name AS product_name
				,SUM(".DB_PREFIX."order_product.quantity) AS order_quantity
				, ".DB_PREFIX."product.quantity AS stock_quantity
				FROM
				".DB_PREFIX."order
				INNER JOIN ".DB_PREFIX."order_product 
					ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id)
				INNER JOIN ".DB_PREFIX."product 
					ON (".DB_PREFIX."order_product.product_id = ".DB_PREFIX."product.product_id) WHERE ".DB_PREFIX."order_product.product_id IN(".$product_ids.") AND ".DB_PREFIX."order.order_status_id = 5 GROUP BY ".DB_PREFIX."order_product.name;";
				$query = $this->db->query($sql);
				$products = $query->rows;
			}
			else
			{
				$products = array();
			}
			if(count($products) > 0)
			{
				for($i=0; $i<count($products); $i++)
				{
					$products[$i]['category'][0] = $category;
					$products[$i]['option_value'][0] = $option_value;				
				}
			}
			
			return $products;
		}
		elseif($filter['filter_category'] != '')
		{
			
			$query = $this->db->query("SELECT name,category_id FROM ".DB_PREFIX."category_description WHERE name='".$filter['filter_category']."'");
			
			$query = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product_to_category WHERE category_id = " . $query->row['category_id']);
			
			$category_product_ids = $query->rows;
			
			$category_product_ids = implode(',',array_map(function ($entry) {return $entry['product_id'];},$category_product_ids));
			
			if(strlen($category_product_ids) == 0)
			{
				return array();
			}
			else{
			
				$sql = "SELECT
				".DB_PREFIX."product_description.product_id
				, ".DB_PREFIX."product_description.name
				, ".DB_PREFIX."product.quantity
				FROM
				".DB_PREFIX."product
				INNER JOIN ".DB_PREFIX."product_description 
					ON (".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id) WHERE ".DB_PREFIX."product.product_id IN(".$category_product_ids.");";
				$query = $this->db->query($sql);
				
				$all_products = $query->rows;
				
				//print_r($all_products);
				
				$sql = "SELECT
				".DB_PREFIX."order_product.product_id
				,".DB_PREFIX."order_product.name AS product_name
				,SUM(".DB_PREFIX."order_product.quantity) AS order_quantity
				, ".DB_PREFIX."product.quantity AS stock_quantity
				FROM
				".DB_PREFIX."order
				INNER JOIN ".DB_PREFIX."order_product 
					ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id)
				INNER JOIN ".DB_PREFIX."product 
					ON (".DB_PREFIX."order_product.product_id = ".DB_PREFIX."product.product_id) WHERE ".DB_PREFIX."order_product.product_id IN(".$category_product_ids.") AND ".DB_PREFIX."order.order_status_id = 5 GROUP BY ".DB_PREFIX."order_product.name;";
				$query = $this->db->query($sql);
				
				$sale_products = $query->rows;
				
				for($i=0; $i<count($all_products); $i++)
				{
					if(count($sale_products) > 0)
					{
						for($j=0; $j<count($sale_products); $j++)
						{
							if($all_products[$i]['product_id'] == $sale_products[$j]['product_id'])
							{
								$all_products[$i]['order_quantity'] = $sale_products[$j]['order_quantity'];
								unset($sale_products[$j]);
								$sale_products = array_values(array_filter($sale_products));
								break;
							}
							else
							{
								$all_products[$i]['order_quantity'] = 0;
								
							}
						}
					}
					else{
						$all_products[$i]['order_quantity'] = 0;
					}
				}
				
				
				$query = $this->db->query("SELECT
				`".DB_PREFIX."option_value_description`.`name`
				, `".DB_PREFIX."product_option_value`.`product_id`
				FROM
				`".DB_PREFIX."product_option_value`
				INNER JOIN `".DB_PREFIX."option_value_description` 
					ON (`".DB_PREFIX."product_option_value`.`option_value_id` = `".DB_PREFIX."option_value_description`.`option_value_id`) WHERE `".DB_PREFIX."product_option_value`.`product_id` IN(".$category_product_ids.");");
				
				$option_results = $query->rows;
				
				/*group the array based on product_id*/
				$option_values = array();
				
				foreach($option_results as $k => $v)
				{
					$option_values[$v['product_id']]['name'][$k] = $v['name'];
				}
				foreach($option_values as $key => $value)
				{
					$option_values[$key]['product_id'] = $key;
					$option_values[$key]['name'] = array_values($option_values[$key]['name']);
				}
				
				/*group the array based on product_id*/
				$option_values = array_values($option_values);
				
				for($i=0; $i<count($all_products); $i++)
				{
					for($j=0; $j<count($option_values); $j++)
					{
						if($all_products[$i]['product_id'] == $option_values[$j]['product_id'])
						{
							$all_products[$i]['option_value'] = $option_values[$j]['name'];
							unset($option_values[$j]);
							$option_values = array_values(array_filter($option_values));
							
						}
					}
				}
				
				foreach($all_products as &$all_product){
					$all_product['stock_quantity'] = $all_product['quantity'];
					$all_product['product_name'] = $all_product['name'];
					$all_product['category'][0] = $category;
					unset($all_product['quantity']);
					unset($all_product['name']);
				}
				
				return $all_products;
			}
		}
		elseif($filter['filter_option_value'] != '')
		{
			$query = $this->db->query("SELECT
			".DB_PREFIX."product_option_value.product_id
			FROM
			".DB_PREFIX."product_option_value
			INNER JOIN ".DB_PREFIX."option_value_description 
				ON (".DB_PREFIX."product_option_value.option_value_id = ".DB_PREFIX."option_value_description.option_value_id)
			WHERE (".DB_PREFIX."option_value_description.name = '".$filter['filter_option_value']."');");
			
			$option_product_ids = $query->rows;
			
			$option_product_ids = implode(',',array_map(function ($entry) {return $entry['product_id'];},$option_product_ids));
			
			$sql = "SELECT
			".DB_PREFIX."product_description.product_id
			, ".DB_PREFIX."product_description.name
			, ".DB_PREFIX."product.quantity
			FROM
			".DB_PREFIX."product
			INNER JOIN ".DB_PREFIX."product_description 
				ON (".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id) WHERE ".DB_PREFIX."product.product_id IN(".$option_product_ids.");";
			
			$query = $this->db->query($sql);
			
			$all_products = $query->rows;
			
			$sql = "SELECT
			".DB_PREFIX."order_product.product_id
			,".DB_PREFIX."order_product.name AS product_name
			,SUM(".DB_PREFIX."order_product.quantity) AS order_quantity
			, ".DB_PREFIX."product.quantity AS stock_quantity
			FROM
			".DB_PREFIX."order
			INNER JOIN ".DB_PREFIX."order_product 
				ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id)
			INNER JOIN ".DB_PREFIX."product 
				ON (".DB_PREFIX."order_product.product_id = ".DB_PREFIX."product.product_id) WHERE ".DB_PREFIX."order_product.product_id IN(".$option_product_ids.") AND ".DB_PREFIX."order.order_status_id = 5 GROUP BY ".DB_PREFIX."order_product.name;";
			$query = $this->db->query($sql);
			
			$sale_products = $query->rows;
			
			for($i=0; $i<count($all_products); $i++)
			{
				if(count($sale_products) > 0)
				{
					for($j=0; $j<count($sale_products); $j++)
					{
						if($all_products[$i]['product_id'] == $sale_products[$j]['product_id'])
						{
							$all_products[$i]['order_quantity'] = $sale_products[$j]['order_quantity'];
							unset($sale_products[$j]);
							$sale_products = array_values(array_filter($sale_products));
							break;
						}
						else
						{
							$all_products[$i]['order_quantity'] = 0;
							
						}
					}
				}
				else
				{
					$all_products[$i]['order_quantity'] = 0;
				}
			}
			
			$query = $this->db->query("SELECT
			".DB_PREFIX."product_to_category.product_id
			, ".DB_PREFIX."category_description.name
			FROM
			".DB_PREFIX."category_description
			INNER JOIN ".DB_PREFIX."product_to_category 
				ON (".DB_PREFIX."category_description.category_id = ".DB_PREFIX."product_to_category.category_id)
			WHERE (".DB_PREFIX."product_to_category.product_id IN(".$option_product_ids."));");
			
			$category_results = $query->rows;
			
			/*group the array based on product_id*/
			
			foreach($category_results as $k => $v)
			{
				$categories[$v['product_id']]['name'][$k] = $v['name'];
			}
			
			foreach($categories as $key => $value)
			{
				$categories[$key]['product_id'] = $key;
				$categories[$key]['name'] = array_values($categories[$key]['name']);
			}
			/*group the array based on product_id*/
			$categories = array_values($categories);
			
			for($i=0; $i<count($all_products); $i++)
			{
				for($j=0; $j<count($categories); $j++)
				{
					if($all_products[$i]['product_id'] == $categories[$j]['product_id'])
					{
						$all_products[$i]['category'] = $categories[$j]['name'];
						unset($categories[$j]);
						$categories = array_values(array_filter($categories));
						
					}
				}
			}
			
			
			foreach($all_products as &$all_product){
				$all_product['stock_quantity'] = $all_product['quantity'];
				$all_product['product_name'] = $all_product['name'];
				$all_product['option_value'][0] = $option_value;
				unset($all_product['quantity']);
				unset($all_product['name']);
			}
			
			return $all_products;
			
		}
		else{
			
			$sql = "SELECT ".DB_PREFIX."product . product_id,".DB_PREFIX."product_description.name,".DB_PREFIX."product.quantity FROM " . DB_PREFIX . "product LEFT JOIN " . DB_PREFIX . "product_description ON (".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id)";
			$query = $this->db->query($sql);
			
			$all_products = $query->rows;
			
			$product_ids = implode(',',array_map(function ($entry) {return $entry['product_id'];},$all_products));
			
			$query = $this->db->query("SELECT
			".DB_PREFIX."product_option_value.product_id,
			".DB_PREFIX."option_value_description.name
			FROM
			".DB_PREFIX."product_option_value
			INNER JOIN ".DB_PREFIX."option_value_description 
				ON (".DB_PREFIX."product_option_value.option_value_id = ".DB_PREFIX."option_value_description.option_value_id)
			WHERE (".DB_PREFIX."product_option_value.product_id IN(".$product_ids."));");
			
			$option_product_results = $query->rows;
			
			
			/*group the array based on product_id*/
			
			foreach($option_product_results as $k => $v)
			{
				$option_values[$v['product_id']]['name'][$k] = $v['name'];
			}
			
			foreach($option_values as $key => $value)
			{
				$option_values[$key]['product_id'] = $key;
				$option_values[$key]['name'] = array_values($option_values[$key]['name']);
			}
			/*group the array based on product_id*/
			$option_values = array_values($option_values);
			
			
			
			$query = $this->db->query("SELECT
			".DB_PREFIX."product_to_category.product_id
			, ".DB_PREFIX."category_description.name
			FROM
			".DB_PREFIX."category_description
			INNER JOIN ".DB_PREFIX."product_to_category 
				ON (".DB_PREFIX."category_description.category_id = ".DB_PREFIX."product_to_category.category_id)
			WHERE (".DB_PREFIX."product_to_category.product_id IN(".$product_ids."));");
			
			$category_results = $query->rows;
			
			/*group the array based on product_id*/
			
			foreach($category_results as $k => $v)
			{
				$categories[$v['product_id']]['name'][$k] = $v['name'];
			}
			
			foreach($categories as $key => $value)
			{
				$categories[$key]['product_id'] = $key;
				$categories[$key]['name'] = array_values($categories[$key]['name']);
			}
			/*group the array based on product_id*/
			$categories = array_values($categories);
			
			
			$sql = "SELECT
			".DB_PREFIX."order_product.product_id
			,".DB_PREFIX."order_product.name AS product_name
			,SUM(".DB_PREFIX."order_product.quantity) AS order_quantity
			, ".DB_PREFIX."product.quantity AS stock_quantity
			FROM
			".DB_PREFIX."order
			INNER JOIN ".DB_PREFIX."order_product 
				ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id)
			INNER JOIN ".DB_PREFIX."product 
				ON (".DB_PREFIX."order_product.product_id = ".DB_PREFIX."product.product_id) WHERE ".DB_PREFIX."order_product.product_id IN(".$product_ids.") AND ".DB_PREFIX."order.order_status_id = 5 GROUP BY ".DB_PREFIX."order_product.name;";
			$query = $this->db->query($sql);
			
			$sale_products = $query->rows;
			/*merging two arrays all_products and sale_products*/
			for($i=0; $i<count($all_products); $i++)
			{
				if(count($sale_products) > 0)
				{
					for($j=0; $j<count($sale_products); $j++)
					{
						if($all_products[$i]['product_id'] == $sale_products[$j]['product_id'])
						{
							$all_products[$i]['order_quantity'] = $sale_products[$j]['order_quantity'];
							unset($sale_products[$j]);
							$sale_products = array_values(array_filter($sale_products));
							break;
						}
						else
						{
							$all_products[$i]['order_quantity'] = 0;
							
						}
					}
				}
				else
				{
					$all_products[$i]['order_quantity'] = 0;
				}
			}
			/*merging two arrays all_products and sale_products*/
			
			/*for adding categories to all products*/
			for($i=0; $i<count($all_products); $i++)
			{
				for($j=0; $j<count($categories); $j++)
				{
					if($all_products[$i]['product_id'] == $categories[$j]['product_id'])
					{
						$all_products[$i]['category'] = $categories[$j]['name'];
						unset($categories[$j]);
						$categories = array_values(array_filter($categories));
						
					}
				}
			}
			/*for adding categories to all products*/
	
			/*for adding option values to all products*/
			
			for($i=0; $i<count($all_products); $i++)
			{
				for($j=0; $j<count($option_values); $j++)
				{
					if($all_products[$i]['product_id'] == $option_values[$j]['product_id'])
					{
						$all_products[$i]['option_value'] = $option_values[$j]['name'];
						unset($option_values[$j]);
						$option_values = array_values(array_filter($option_values));
						
					}
				}
			}
			
			/*for adding option values to all products*/
	
			/*changing the name of the indexes*/
			foreach($all_products as &$all_product){
				$all_product['stock_quantity'] = $all_product['quantity'];
				$all_product['product_name'] = $all_product['name'];
				unset($all_product['quantity']);
				unset($all_product['name']);
			}
			/*changing the name of the indexes*/
			
			/*for checking if the product belongs to category and products*/
			for($i=0; $i<count($all_products); $i++)
			{
				if(!(array_key_exists("category",$all_products[$i])))
				{
					$all_products[$i]['category'][0] = '';
				}
				
				if(!(array_key_exists("option_value",$all_products[$i])))
				{
					$all_products[$i]['option_value'][0] = '';
				}
			}
		
			/*for checking if the product belongs to category and products*/
			


					
			return $all_products;
			
			
			
			
		}
			
	}
	
	public function allProducts()
	{
		$sql = "SELECT ".DB_PREFIX."product . product_id,".DB_PREFIX."product_description.name,".DB_PREFIX."product.quantity FROM " . DB_PREFIX . "product LEFT JOIN " . DB_PREFIX . "product_description ON (".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id)";
			$query = $this->db->query($sql);
			
			$all_products = $query->rows;
			
			$product_ids = implode(',',array_map(function ($entry) {return $entry['product_id'];},$all_products));
			
			$query = $this->db->query("SELECT
			".DB_PREFIX."product_option_value.product_id,
			".DB_PREFIX."option_value_description.name
			FROM
			".DB_PREFIX."product_option_value
			INNER JOIN ".DB_PREFIX."option_value_description 
				ON (".DB_PREFIX."product_option_value.option_value_id = ".DB_PREFIX."option_value_description.option_value_id)
			WHERE (".DB_PREFIX."product_option_value.product_id IN(".$product_ids."));");
			
			$option_product_results = $query->rows;
			
			
			/*group the array based on product_id*/
			
			foreach($option_product_results as $k => $v)
			{
				$option_values[$v['product_id']]['name'][$k] = $v['name'];
			}
			
			foreach($option_values as $key => $value)
			{
				$option_values[$key]['product_id'] = $key;
				$option_values[$key]['name'] = array_values($option_values[$key]['name']);
			}
			/*group the array based on product_id*/
			$option_values = array_values($option_values);
			
			
			
			$query = $this->db->query("SELECT
			".DB_PREFIX."product_to_category.product_id
			, ".DB_PREFIX."category_description.name
			FROM
			".DB_PREFIX."category_description
			INNER JOIN ".DB_PREFIX."product_to_category 
				ON (".DB_PREFIX."category_description.category_id = ".DB_PREFIX."product_to_category.category_id)
			WHERE (".DB_PREFIX."product_to_category.product_id IN(".$product_ids."));");
			
			$category_results = $query->rows;
			
			/*group the array based on product_id*/
			
			foreach($category_results as $k => $v)
			{
				$categories[$v['product_id']]['name'][$k] = $v['name'];
			}
			
			foreach($categories as $key => $value)
			{
				$categories[$key]['product_id'] = $key;
				$categories[$key]['name'] = array_values($categories[$key]['name']);
			}
			/*group the array based on product_id*/
			$categories = array_values($categories);
			
			
			$sql = "SELECT
			".DB_PREFIX."order_product.product_id
			,".DB_PREFIX."order_product.name AS product_name
			,SUM(".DB_PREFIX."order_product.quantity) AS order_quantity
			, ".DB_PREFIX."product.quantity AS stock_quantity
			FROM
			".DB_PREFIX."order
			INNER JOIN ".DB_PREFIX."order_product 
				ON (".DB_PREFIX."order.order_id = ".DB_PREFIX."order_product.order_id)
			INNER JOIN ".DB_PREFIX."product 
				ON (".DB_PREFIX."order_product.product_id = ".DB_PREFIX."product.product_id) WHERE ".DB_PREFIX."order_product.product_id IN(".$product_ids.") AND ".DB_PREFIX."order.order_status_id = 5 GROUP BY ".DB_PREFIX."order_product.name;";
			$query = $this->db->query($sql);
			
			$sale_products = $query->rows;
			/*merging two arrays all_products and sale_products*/
			
			for($i=0; $i<count($all_products); $i++)
			{
				if(count($sale_products) > 0)
				{
					for($j=0; $j<count($sale_products); $j++)
					{
						if($all_products[$i]['product_id'] == $sale_products[$j]['product_id'])
						{
							$all_products[$i]['order_quantity'] = $sale_products[$j]['order_quantity'];
							unset($sale_products[$j]);
							$sale_products = array_values(array_filter($sale_products));
							break;
						}
						else
						{
							$all_products[$i]['order_quantity'] = 0;
							
						}
					}
				}
				else
				{
					$all_products[$i]['order_quantity'] = 0;
				}
			}
			
			/*merging two arrays all_products and sale_products*/
			
			/*for adding categories to all products*/
			for($i=0; $i<count($all_products); $i++)
			{
				for($j=0; $j<count($categories); $j++)
				{
					if($all_products[$i]['product_id'] == $categories[$j]['product_id'])
					{
						$all_products[$i]['category'] = $categories[$j]['name'];
						unset($categories[$j]);
						$categories = array_values(array_filter($categories));
						
					}
				}
			}
			/*for adding categories to all products*/
	
			/*for adding option values to all products*/
			
			for($i=0; $i<count($all_products); $i++)
			{
				for($j=0; $j<count($option_values); $j++)
				{
					if($all_products[$i]['product_id'] == $option_values[$j]['product_id'])
					{
						$all_products[$i]['option_value'] = $option_values[$j]['name'];
						unset($option_values[$j]);
						$option_values = array_values(array_filter($option_values));
						
					}
				}
			}
			
			/*for adding option values to all products*/
	
			/*changing the name of the indexes*/
			foreach($all_products as &$all_product){
				$all_product['stock_quantity'] = $all_product['quantity'];
				$all_product['product_name'] = $all_product['name'];
				unset($all_product['quantity']);
				unset($all_product['name']);
			}
			/*changing the name of the indexes*/
			
			
			/*for checking if the product belongs to category and products*/
			for($i=0; $i<count($all_products); $i++)
			{
				if(!(array_key_exists("category",$all_products[$i])))
				{
					$all_products[$i]['category'][0] = '';
				}
				
				if(!(array_key_exists("option_value",$all_products[$i])))
				{
					$all_products[$i]['option_value'][0] = '';
				}
			}
		
			/*for checking if the product belongs to category and products*/
				
			return $all_products;

	}
	
	public function applyDiscount($product_ids,$discount)
	{
		$array_product_id = $product_ids;
		
		$product_ids = implode(',',$product_ids);
		
		$query = $this->db->query("SELECT
		`".DB_PREFIX."product`.`price`,
		".DB_PREFIX."product.product_id
		FROM
		`".DB_PREFIX."product`
		WHERE (`".DB_PREFIX."product`.`product_id` IN(".$product_ids."));");
		
		$prices = $query->rows;
		
		for($i=0; $i<count($prices); $i++)
		{
			$prices[$i]['discounted_price'] = $prices[$i]['price']-(($prices[$i]['price'] * $discount)/100);
		}
		
		for($i=0; $i < count($prices); $i++)
		{
			$query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_special WHERE ".DB_PREFIX."product_special.`product_id` = ".$prices[$i]['product_id']);
			
			if($this->db->countAffected() > 0)
			{
				$update_query = $this->db->query("UPDATE ".DB_PREFIX."product_special SET ".DB_PREFIX."product_special.`price` = ".$prices[$i]['discounted_price']." WHERE ".DB_PREFIX."product_special.`product_id` = ".$prices[$i]['product_id']);
			}
			else
			{
				$insert_query = $this->db->query("INSERT INTO ".DB_PREFIX."product_special (".DB_PREFIX."product_special.`price`,".DB_PREFIX."product_special.`product_id`,".DB_PREFIX."product_special.`customer_group_id`) VALUES(".$prices[$i]['discounted_price'].",".$prices[$i]['product_id'].",1)");
			
			}
			
		}
		
		if(isset($update_query) || isset($insert_query))
		{
			return true;
		}
		else
		{
			return false;
		}
		
		
		
	}
}
?>