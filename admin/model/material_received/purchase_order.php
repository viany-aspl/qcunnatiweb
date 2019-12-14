<?php
class ModelMaterialReceivedPurchaseOrder extends Model 
{
	public function update_po_link_staus($data = array()) 
	{
		$log = new Log('po-' . date('Y-m-d') . '.log');
		$log->write('update_po_link_staus called');
		$log->write($data);
		$sql = "UPDATE oc_po_order SET spo_po_linked_status=0 WHERE id = " . $data['order_id'];
        $log->write($sql);
        $this->db->query($sql);
		return $data['order_id'];
	}
	public function cancel_po_order($data = array()) 
	{
		$log = new Log('po-' . date('Y-m-d') . '.log');
		$log->write('cancel_po_order called');
		$log->write($data);
		$sql = "UPDATE oc_po_order SET order_sup_send = '1974-00-00',receive_date = '1974-00-00',pending_bit=1,pre_supplier_bit=0,order_status_id=3,canceled_message='Cancled' WHERE id = " . $data['order_id'];
        $log->write($sql);
        $this->db->query($sql);
		return $data['order_id'];
	}
    public function get_ware_houses() 
	{
        $sql2 = "select name,store_id from oc_store where store_id in 
(select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('2','4') )";
        $query2 = $this->db->query($sql2);
        return $query2->rows;
    }

    public function getList($data) 
	{
        $log=new Log("getList-for-PR-Acknoledgement-".date('Y-m-d').".log");
        $sql = "SELECT
			oc_po_order.*,'1' as store_type
			, " . DB_PREFIX . "user.firstname
			, " . DB_PREFIX . "user.lastname
                        , oc_store.name as store_name
                        , oc_store.creditlimit as creditlimit
                        , oc_store.currentcredit as currentcredit
			,oc_po_product.name as product
			,oc_po_product.quantity
                        FROM
			oc_po_order
			LEFT JOIN oc_po_product ON (oc_po_order.id= oc_po_product.order_id)
			LEFT JOIN oc_po_receive_details ON (oc_po_order.id = oc_po_receive_details.order_id)
                        LEFT JOIN oc_store ON (oc_po_receive_details.store_id = oc_store.store_id)
			LEFT JOIN " . DB_PREFIX . "user ON (oc_po_order.user_id = " . DB_PREFIX . "user.user_id) WHERE oc_po_order.id != ''  ";

        $sql .= " and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('1')) ";
        $sql .= " AND oc_po_order.receive_bit='1' AND oc_po_order.order_status_id='1' ";

       // if (!empty($data['filter_status'])) { 
		//echo $data['filter_status'];
            if (($data['filter_status'] == "0") || ($data['filter_status'] == "1")) {
                $sql .= " and oc_po_order.receive_bit='" . $data['filter_status'] . "'  ";// and oc_po_order.canceled_message==''
				$sql .= " and oc_po_order.canceled_message='' ";
            }
            if ($data['filter_status'] == "3") 
			{
                $sql .= " and oc_po_order.canceled_message!='' ";
            }
        //}

        if (!empty($data['filter_date_start'])) {
            $sql .= " and oc_po_order.order_date>='" . $data['filter_date_start'] . "' ";
        }
        if (!empty($data['filter_date_end'])) {
            $sql .= " and oc_po_order.order_date<='" . $data['filter_date_end'] . "' ";
        }
        if (!empty($data['filter_id'])) {
            $sql .= " and oc_po_order.id='" . $data['filter_id'] . "' ";
        }
       if (!empty($data['filter_store'])) {
            $sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        }
        $sql .= " GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC";
        if ($data['start'] >= 0) {
            $sql .= " LIMIT " . $data['start'] . "," . $data['limit'];
        }
        $log->write( $sql);
        //echo $data['start'];
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrders($data) 
	{
        $sql = "select count(*) as total_orders from (SELECT
			oc_po_order.*
			, " . DB_PREFIX . "user.firstname
			, " . DB_PREFIX . "user.lastname
			
			
                        , oc_store.name as store_name
                        , oc_store.creditlimit as creditlimit
                        , oc_store.currentcredit as currentcredit
                        FROM
			oc_po_order
			LEFT JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
                        LEFT JOIN oc_store
                ON (oc_po_receive_details.store_id = oc_store.store_id)
			
			LEFT JOIN " . DB_PREFIX . "user 
				ON (oc_po_order.user_id = " . DB_PREFIX . "user.user_id) WHERE oc_po_order.id != ''  ";

        $sql .= " and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('1')) ";

        //if (!empty($data['filter_status'])) {
            if (($data['filter_status'] == "0") || ($data['filter_status'] == "1")) {
                $sql .= " and oc_po_order.receive_bit='" . $data['filter_status'] . "'   ";//and oc_po_order.canceled_message==''
				$sql .= " and oc_po_order.canceled_message='' ";
            }
            if ($data['filter_status'] == "3") {
                $sql .= " and oc_po_order.canceled_message!='' ";
            }
        //}

        if (!empty($data['filter_date_start'])) {
            $sql .= " and oc_po_order.order_date>='" . $data['filter_date_start'] . "' ";
        }
        if (!empty($data['filter_date_end'])) {
            $sql .= " and oc_po_order.order_date<='" . $data['filter_date_end'] . "' ";
        }
        if (!empty($data['filter_id'])) {
            $sql .= " and oc_po_order.id='" . $data['filter_id'] . "' ";
        }
        if (!empty($data['filter_store'])) {
            $sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        }
        $sql .= " GROUP BY oc_po_order.id ";

        $sql .= " ) as aa";
        //echo $sql;
        //echo $data['start'];
        $query = $this->db->query($sql);

        return $query->row['total_orders'];
    }

    ////////////////////
    public function submit_po_invoice($data = array()) {

        $sql = "select po_invoice_n from oc_po_invoice where po_order_id='" . $data['order_id'] . "'  ";
        $query = $this->db->query($sql);
        $results = $query->row;
        if (empty($results['po_invoice_n'])) {
            $sql2 = "select MAX(po_invoice_n) as po_invoice_n from oc_po_invoice where po_store_id='" . $data['store_id'] . "'  ";
            $query2 = $this->db->query($sql2);

            if ($query2->row['po_invoice_n']) {
                $invoice_no = $query2->row['po_invoice_n'] + 1;
            } else {
                $invoice_no = 1;
            }
            $sql3 = "insert into oc_po_invoice set po_store_id='" . $data['store_id'] . "',po_order_id='" . $data['order_id'] . "',po_ware_house='" . $data['ware_house'] . "',po_invoice_n='" . $invoice_no . "',po_invoice_prefix='ASPL/BB',order_total='" . $data['grand_total'] . "'  ";
            $query = $this->db->query($sql3);
            $insert_id = $this->db->getLastId();

            for ($a = 0; $a < count($data['product_id']); $a++) {
                $product_id = $data['product_id'][$a];
                $product_hsn = $data['product_hsn'][$a];
                $p_amount = $data['p_amount'][$a];
                $product_name = $data['product_name'][$a];
                $p_price = $data['p_price'][$a];
                $p_qnty = $data['p_qnty'][$a];
                $p_tax_rate = $data['p_tax_rate'][$a];
                $p_tax_type = $data['p_tax_type'][$a];
                $po_store_id = $data['store_id'];
                $sql4 = "insert into oc_po_invoice_product set invoice_t_s_id='" . $insert_id . "',"
                        . "invoice_n='" . $invoice_no . "',"
                        . "product_id='" . $product_id . "',"
                        . "product_hsn='" . $product_hsn . "',"
                        . "p_amount='" . $p_amount . "',"
                        . "product_name='" . $product_name . "',  "
                        . "p_qnty='" . $p_qnty . "',  "
                        . "p_tax_rate='" . $p_tax_rate . "',  "
                        . "p_tax_type='" . $p_tax_type . "',  "
                        . "po_store_id='" . $po_store_id . "',  "
                        . "p_price='" . $p_price . "',po_order_id='" . $data['order_id'] . "'";
                $query = $this->db->query($sql4);
            }
        }
        //print_r($data);
    }

    ///////////////////////////////////////////////////////
    public function view_order_details($order_id) {
        $sql1 = "SELECT oc_po_order.*," . DB_PREFIX . "user.firstname," . DB_PREFIX . "user.lastname
				FROM oc_po_order
					LEFT JOIN " . DB_PREFIX . "user
						ON " . DB_PREFIX . "user.user_id = oc_po_order.user_id
							WHERE id = " . $order_id;
        $query = $this->db->query($sql1);
        $order_info = $query->row;
        //print_r($order_info);
        $view_order_details = "SELECT
        oc_po_product.*,oc_po_receive_details.quantity as rd_quantity,
                oc_store.name as store_name,
                oc_product.price_tax as price,
	  ware_house.name as ware_house_name,
	  ware_house.store_id as ware_house_id,
	  supplier.first_name as supplier_name,
	  supplier.id as supplier_id,
                ((oc_product.price_tax)-(oc_product.price)) as tax,
                
                oc_po_receive_details.order_id
        FROM
            oc_po_receive_details
        LEFT JOIN oc_product ON oc_product.product_id=oc_po_receive_details.product_id
        LEFT JOIN oc_po_product
            ON (oc_po_receive_details.order_id = oc_po_product.order_id)
            AND (oc_po_receive_details.product_id = oc_po_product.product_id)
        LEFT JOIN oc_store as ware_house
            ON (oc_po_receive_details.supplier_id = ware_house.store_id)
	 LEFT JOIN oc_po_supplier as supplier
            ON (oc_po_receive_details.supplier_id = supplier.id)
        LEFT JOIN oc_store as oc_store
            ON (oc_po_receive_details.store_id = oc_store.store_id)
                WHERE (oc_po_receive_details.order_id =" . $order_id . ")";

	//echo $view_order_details;

        $query = $this->db->query($view_order_details);



        if ($this->db->countAffected() > 0) {
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
            for ($i = 0; $i < count($products); $i++) {
                if ($products[$i] != "") {
                    for ($j = 0; $j < count($products); $j++) {
                        if ($products[$j] != "") {
                            if ($products[$i]['id'] == $products[$j]['id']) {
                                $quantities[$index] = $products[$j]['rd_quantity'];
                                $supplier_names[$index] = $products[$j]['first_name'] . " " . $products[$j]['last_name'];
                                $suppliers[$index] = $products[$j]['supplier_id'];
                                $prices[$index] = $products[$j]['price'];
                                if ($j != $i) {
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
            for ($i = 0; $i < count($products); $i++) {
                unset($products[$i]['rd_quantity']);
                unset($products[$i]['first_name']);
                unset($products[$i]['last_name']);
                $products[$i]['quantities'] = $all_quantities[$i];
                $products[$i]['suppliers'] = $all_suppliers[$i];
                $products[$i]['prices'] = $all_prices[$i];
                $products[$i]['supplier_names'] = $all_supplier_names[$i];
            }
        } else {
            $query = $this->db->query("SELECT * FROM oc_po_product WHERE order_id = " . $order_info['id']);
            $products = $query->rows;
        }
        $i = 0;
        foreach ($products as $product) {
            $query = $this->db->query("SELECT * FROM oc_po_attribute_group WHERE product_id = " . $product['id']);
            $attribute_groups[$i] = $query->rows;
            $i++;
        }

        $i = 0;
        foreach ($attribute_groups as $attribute_group) {
            for ($j = 0; $j < count($attribute_group); $j++) {
                $query = $this->db->query("SELECT * FROM oc_po_attribute_category WHERE attribute_group_id = " . $attribute_group[$j]['id']);
                $attribute_categories[$i] = $query->row;
                $i++;
            }
        }
        for ($i = 0; $i < count($products); $i++) {
            for ($j = 0; $j < count($attribute_groups[$i]); $j++) {
                $products[$i]['attribute_groups'][$j] = $attribute_groups[$i][$j]['name'];
            }
        }
        $start_loop = 0;
        //$attribute_categories = array_values(array_filter($attribute_categories));
        //exit;
        for ($i = 0; $i < count($products); $i++) {
            for ($j = $start_loop; $j < ($start_loop + count($products[$i]['attribute_groups'])); $j++) {
                $products[$i]['attribute_category'][$j] = $attribute_categories[$j]['name'];
            }
            $start_loop = $j;
        }
        $order_information['products'] = $products;
        $order_information['order_info'] = $order_info;
        return $order_information;
    }

    public function check_ware_house_quantity($ware_house, $product_id, $p_qnty) {
        $sql = "SELECT `quantity` from  oc_product_to_store where store_id = " . $ware_house . " and `product_id` ='" . $product_id . "'";
        $query = $this->db->query($sql);
        $store_quantity = $query->row['quantity'];
        if ($store_quantity < $p_qnty) {
            return '0';
        } else {
            return '1';
        }
    }

    /* -----------------------------insert receive order function starts here------------------- */

    public function insert_receive_order($received_order_info, $order_id) {
        $log = new Log('po-' . date('Y-m-d') . '.log');
        $user_id = $received_order_info['user_id'];
        $sql = "select username from oc_user where user_group_id='11' and  user_id = " . $user_id." limit 1 ";
        $log->write($sql);
        $query = $this->db->query($sql);
        $store_mobile = $query->row['username'];
        $otp = rand(1000, 9999);
        if ($received_order_info['order_receive_date'] != '') {
            $received_order_info['order_receive_date'] = strtotime($received_order_info['order_receive_date']);
            $received_order_info['order_receive_date'] = date('Y-m-d', $received_order_info['order_receive_date']);
        }
        $sql = "UPDATE oc_po_order SET order_sup_send = '" . $received_order_info['order_receive_date'] . "', receive_bit = " . 0 . ", pending_bit = " . 0 . ",pre_supplier_bit=" . 1 . ",driver_otp='" . $otp . "',driver_mobile='" . $store_mobile . "' WHERE id = " . $order_id;
        $log->write($sql);
        $this->db->query($sql);
        $total_product = count($received_order_info['ware_houses']);


        $this->load->library('trans');
        $trans = new trans($this->registry);

        for ($aa = 0; $aa < $total_product; $aa++) {
            $ware_house = $received_order_info['ware_houses'][$aa];
            $sent_quantity = $received_order_info['receive_quantity'][$aa];
            $product_id = $received_order_info['product_id'][$aa];
            $product_requested_quantity = $received_order_info['product_requested_quantity'][$aa];
            $sql22 = "UPDATE oc_po_receive_details SET supplier_id='" . $ware_house . "', quantity = " . $sent_quantity . " WHERE product_id =" . $product_id . " AND order_id = " . $order_id;
            $log->write($sql22);
            $this->db->query($sql22);
            $sql3 = "UPDATE oc_po_product SET received_products = " . 0 . ",supplier_quantity='".$sent_quantity."' WHERE product_id = " . $product_id . " AND order_id = " . $order_id;
            $log->write($sql3);
            $query1 = $this->db->query($sql3);
            //$sql4 = "UPDATE oc_product_to_store SET quantity = quantity-" . $sent_quantity . " WHERE product_id = " . $product_id . " AND store_id = " . $ware_house;
            //$log->write($sql4);
            //$query = $this->db->query($sql4); 

            //$trans->addproducttrans($ware_house, $product_id, $sent_quantity, $order_id, 'DB', 'POOWN');
        }


        $filter_data = array(
            'user_id' => $user_id,
            'order_id' => $order_id,
            'store_mobile' => $store_mobile,
            'otp' => $otp
        );

        $this->load->library('sms');
        $sms = new sms($this->registry);
        $sms->sendsms($store_mobile, "10", $filter_data);
        return $otp;
    }

    /*
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
      $this->db->query("UPDATE oc_po_order SET order_sup_send = '" .$received_order_info['order_receive_date']."', receive_bit = " . 0 . ", pending_bit = " . 0 . " WHERE id = " . $order_id);

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
     */

    /* -----------------------------insert receive order function ends here----------------- */

    /////////////////
    public function insert_purchase_order($data = array()) {

        //insert order details
        if ($data['supplier_id'] != "--Supplier--") {
            $this->db->query('INSERT INTO oc_po_order (order_date,user_id,pre_supplier_bit) VALUES("' . date('Y-m-d') . '",' . $this->session->data['user_id'] . ',1)');
            $order_id = $this->db->getLastId();
        } else {
            $this->db->query('INSERT INTO oc_po_order (order_date,user_id) VALUES("' . date('Y-m-d') . '",' . $this->session->data['user_id'] . ')');
            $order_id = $this->db->getLastId();
        }

        //insert product details

        for ($i = 0; $i < count($data['products']); $i++) {
            $this->db->query("INSERT INTO oc_po_product (product_id,name,quantity,order_id,store_id,store_name)	VALUES(" . $data['products'][$i][0] . ",'" . $data['products'][$i][1] . "'," . $data['quantity'][$i] . "," . $order_id . ",'" . $data['stores'][$i][0] . "','" . $data['stores'][$i][1] . "')");
            $product_ids[$i] = $this->db->getLastId();
        }
        //insert attribute group
        $start_loop = 0;
        for ($j = 0; $j < count($product_ids); $j++) {
            for ($i = $start_loop; $i < count($data['options']); $i++) {
                if ($data['options'][$i] != "new product") {
                    $this->db->query("INSERT INTO oc_po_attribute_group (attribute_group_id,name,product_id) VALUES(" . $data['options'][$i][0] . ",'" . $data['options'][$i][1] . "'," . $product_ids[$j] . ")");
                    $attribute_group_ids[$i] = $this->db->getLastId();
                } else {
                    $start_loop = $i + 1;
                    $attribute_group_ids[$i] = "new product";
                    break;
                }
            }
        }

        $start_loop = 0;
        for ($i = 0; $i < count($attribute_group_ids); $i++) {
            if ($attribute_group_ids[$i] != "new product") {
                for ($j = $start_loop; $j < count($data['option_values']); $j++) {
                    if ($data['option_values'][$j] != "new product") {
                        $this->db->query("INSERT INTO oc_po_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(" . $data['option_values'][$j][0] . ",'" . $data['option_values'][$j][1] . "'," . $attribute_group_ids[$i] . ")");
                        $attribute_category_ids[$j] = $this->db->getLastId();
                    } else {
                        $attribute_category_ids[$j] = "new product";
                    }
                    $start_loop = $j + 1;
                    break;
                }
            } else {
                for ($j = $start_loop; $j < count($data['option_values']); $j++) {
                    if ($data['option_values'][$j] != "new product") {
                        $this->db->query("INSERT INTO oc_po_attribute_category (attribute_category_id,name,attribute_group_id) VALUES(" . $data['option_values'][$j][0] . ",'" . $data['option_values'][$j][1] . "'," . $attribute_group_ids[$i + 1] . ")");
                        $attribute_category_ids[$j] = $this->db->getLastId();
                        $i = $i + 1;
                    } else {
                        $attribute_category_ids[$j] = "new product";
                    }
                    $start_loop = $j + 1;
                    break;
                }
            }
        }

        if ($data['supplier_id'] != "--Supplier--") {
            for ($i = 0; $i < count($data['products']); $i++) {
                $query = $this->db->query("INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(" . $data['quantity'][$i] . "," . $data['products'][$i][0] . "," . $data['supplier_id'] . "," . $order_id . ",'" . $data['stores'][$i][0] . "')");
            }
        } else {
            for ($i = 0; $i < count($data['products']); $i++) {
                $query = $this->db->query("INSERT INTO oc_po_receive_details (product_id,supplier_id,order_id,store_id) VALUES(" . $data['products'][$i][0] . ",-1," . $order_id . ",'" . $data['stores'][$i][0] . "')");
            }
        }

        return $order_id;
    }
	public function isecgetList($data) {
 $sql = "SELECT
 oc_po_order.*,'1' as store_type
 , " . DB_PREFIX . "user.firstname
 , " . DB_PREFIX . "user.lastname


 , oc_store.name as store_name
 , oc_store.creditlimit as creditlimit
 , oc_store.currentcredit as currentcredit
 FROM
 oc_po_order
 LEFT JOIN oc_po_receive_details
 ON (oc_po_order.id = oc_po_receive_details.order_id)
 LEFT JOIN oc_store
 ON (oc_po_receive_details.store_id = oc_store.store_id)

 LEFT JOIN " . DB_PREFIX . "user 
 ON (oc_po_order.user_id = " . DB_PREFIX . "user.user_id) WHERE oc_po_order.id != '' ";

$sql .= " and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('7')) ";

// if (!empty($data['filter_status'])) { 
 //echo $data['filter_status'];
 if (($data['filter_status'] == "0") || ($data['filter_status'] == "1")) {
 $sql .= " and oc_po_order.receive_bit='" . $data['filter_status'] . "' ";// and oc_po_order.canceled_message==''
 }
 if ($data['filter_status'] == "3") {
 $sql .= " and oc_po_order.canceled_message!='' ";
 }
 //}

if (!empty($data['filter_date_start'])) {
 $sql .= " and oc_po_order.order_date>='" . $data['filter_date_start'] . "' ";
 }
 if (!empty($data['filter_date_end'])) {
 $sql .= " and oc_po_order.order_date<='" . $data['filter_date_end'] . "' ";
 }
 if (!empty($data['filter_id'])) {
 $sql .= " and oc_po_order.id='" . $data['filter_id'] . "' ";
 }
if (!empty($data['filter_store'])) {
            $sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        }
 $sql .= " GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC";
 if ($data['start'] >= 0) {
 $sql .= " LIMIT " . $data['start'] . "," . $data['limit'];
 }
 //echo $sql;
 //echo $data['start'];
 $query = $this->db->query($sql);

return $query->rows;
 }




public function isecgetTotalOrders($data) {
 $sql = "select count(*) as total_orders from (SELECT
 oc_po_order.*
 , " . DB_PREFIX . "user.firstname
 , " . DB_PREFIX . "user.lastname


 , oc_store.name as store_name
 , oc_store.creditlimit as creditlimit
 , oc_store.currentcredit as currentcredit
 FROM
 oc_po_order
 LEFT JOIN oc_po_receive_details
 ON (oc_po_order.id = oc_po_receive_details.order_id)
 LEFT JOIN oc_store
 ON (oc_po_receive_details.store_id = oc_store.store_id)

 LEFT JOIN " . DB_PREFIX . "user 
 ON (oc_po_order.user_id = " . DB_PREFIX . "user.user_id) WHERE oc_po_order.id != '' ";

$sql .= " and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('7')) ";

//if (!empty($data['filter_status'])) {
 if (($data['filter_status'] == "0") || ($data['filter_status'] == "1")) {
 $sql .= " and oc_po_order.receive_bit='" . $data['filter_status'] . "' ";//and oc_po_order.canceled_message==''
 }
 if ($data['filter_status'] == "3") {
 $sql .= " and oc_po_order.canceled_message!='' ";
 }
 //}

if (!empty($data['filter_date_start'])) {
 $sql .= " and oc_po_order.order_date>='" . $data['filter_date_start'] . "' ";
 }
 if (!empty($data['filter_date_end'])) {
 $sql .= " and oc_po_order.order_date<='" . $data['filter_date_end'] . "' ";
 }
 if (!empty($data['filter_id'])) {
 $sql .= " and oc_po_order.id='" . $data['filter_id'] . "' ";
 }
if (!empty($data['filter_store'])) {
            $sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        }
 $sql .= " GROUP BY oc_po_order.id ";

$sql .= " ) as aa";
 //echo $sql;
 //echo $data['start'];
 $query = $this->db->query($sql);

return $query->row['total_orders'];
 }

    
    public function getStorePOList($data) {
        $log=new Log("getStorePOList".date('Y-m-d').".log");
        $log->write($data);
        $sql = "SELECT osi.invoice_no,osi.amount,oc_supplier_po_order.create_date,
			op.model as product,oc_supplier_po_order.Quantity,oc_supplier_po_order.store_id,
                        oc_supplier_po_order.product_id,
			oc_supplier_po_order.sid,oc_supplier_po_order.id_prefix,oc_supplier_po_order.supplier_id,
			oc_supplier_po_order.status,oc_supplier_po_order.valid_date,
			os.name as delivery_address,
			concat(ps.first_name,ps.last_name) as supplier,
			oc_supplier_po_order.supplier_id		
			FROM oc_supplier_po_order 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_supplier_po_invoice as osi on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where oc_supplier_po_order.store_id!=''";


        if (!empty($data['filter_date_start'])) {
            $sql .= " and DATE(oc_supplier_po_order.create_date)>='" . $data['filter_date_start'] . "'";
}
        if (!empty($data['filter_date_end'])) {
            $sql .= " and DATE(oc_supplier_po_order.create_date)<='" . $data['filter_date_end'] . "'";
        }
        if (!empty($data['filter_store'])) {
            $sql .= " and oc_supplier_po_order.store_id='" . $data['filter_store'] . "'";
        }
        if (!empty($data['filter_product'])) {
            $sql .= " and oc_supplier_po_order.product_id='" . $data['filter_product'] . "'";
        }
        if (isset($data['filter_status'])) {
            $sql .= " and oc_supplier_po_order.status='" . $data['filter_status'] . "'";
        }        
        if (!empty($data['filter_approve_status'])) {
            $sql .= " and oc_supplier_po_order.approve_status='" . $data['filter_approve_status'] . "'";
        }
        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
        $sql .= " ORDER BY oc_supplier_po_order.sid DESC ";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        $log->write($sql);
        // echo $sql;exit;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalStorePOrders($data) {
        $sql = "select count(*) as total_orders from ( SELECT osi.invoice_no FROM oc_supplier_po_order 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_order.store_id
LEFT JOIN oc_supplier_po_invoice as osi on osi.po_no=oc_supplier_po_order.sid
LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_order.supplier_id where oc_supplier_po_order.store_id!=''";


        if (!empty($data['filter_date_start'])) {
            $sql .= " and DATE(oc_supplier_po_order.create_date)>='" . $data['filter_date_start'] . "'";
        }
        if (!empty($data['filter_date_end'])) {
            $sql .= " and DATE(oc_supplier_po_order.create_date)<='" . $data['filter_date_end'] . "'";
        }
        if (!empty($data['filter_store'])) {
            $sql .= " and oc_supplier_po_order.store_id='" . $data['filter_store'] . "'";
        }
        if (!empty($data['filter_product'])) {
            $sql .= " and oc_supplier_po_order.product_id='" . $data['filter_product'] . "'";
        }
        if ($data['filter_status'] != '') {
            $sql .= " and oc_supplier_po_order.status='" . $data['filter_status'] . "'";
        }        
        if (!empty($data['filter_approve_status'])) {
            $sql .= " and oc_supplier_po_order.approve_status='" . $data['filter_approve_status'] . "'";
        }
        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
        $sql .= " ) as aa ";

        //echo $sql;
        $query = $this->db->query($sql);

        return $query->row['total_orders'];
        //return $results['total_orders'];
    }
     public function view_order_details_for_created_invoice_b2b($order_id)
    {       $sql="SELECT op.model as product,oc_supplier_po_order.*,ops.*,oc_supplier_po_invoice.invoice_no as invoice_no,oc_supplier_po_invoice.invoice_date as invoice_date FROM oc_supplier_po_order LEFT JOIN oc_po_supplier as ops on ops.id=oc_supplier_po_order.supplier_id LEFT JOIN oc_product as op on op.product_id=oc_supplier_po_order.product_id left join oc_supplier_po_invoice on oc_supplier_po_order.sid=oc_supplier_po_invoice.po_no WHERE oc_supplier_po_order.sid = " . $order_id ;
        $query = $this->db->query($sql);
        $order_info = $query->row;
                //print_r($sql);
                
           
       $order_information['order_info'] = $order_info;
           
       return $order_information;    
        }
          public function get_to_store_data($store_id)
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

           $sql2="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_MSMFID' ";
           $query2 = $this->db->query($sql2);
           $store_data=$store_data."---".$query2->row['value'];
          //echo $store_data;
           return $store_data;
           
        }
        public function getorder(){
            $sql= "SELECT 
    op.model AS product,
    oc_supplier_po_order.*,
    ops.*,
    oc_supplier_po_invoice.invoice_no AS invoice_no,
    oc_supplier_po_invoice.invoice_date AS invoice_date
FROM
    oc_supplier_po_order
        LEFT JOIN
    oc_po_supplier AS ops ON ops.id = oc_supplier_po_order.supplier_id
        LEFT JOIN
    oc_product AS op ON op.product_id = oc_supplier_po_order.product_id
        LEFT JOIN
    oc_supplier_po_invoice ON oc_supplier_po_order.sid = oc_supplier_po_invoice.po_no
WHERE
    oc_supplier_po_order.sid =''";
        }

}

?>