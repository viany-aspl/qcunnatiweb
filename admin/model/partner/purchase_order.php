<?php
class ModelPartnerPurchaseOrder extends Model {
	public function partner_stores()
	{
		$sql22="select name,store_id from oc_store where store_id in (select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('3','4') )";
		$query22 = $this->db->query($sql22);
		return $query22->rows;
	}

	public function get_ware_house_quantity($product_id,$ware_house)
        {
           $sql="SELECT `quantity` from  oc_product_to_store where store_id = ".$ware_house." and `product_id` ='".$product_id."'";
           $query = $this->db->query($sql);
           return $store_quantity=$query->row['quantity'];
           
           
        }
        public function check_ware_house_quantity($ware_house,$product_id,$p_qnty)
        {
           $sql="SELECT `quantity` from  oc_product_to_store where store_id = ".$ware_house." and `product_id` ='".$product_id."'";
           $query = $this->db->query($sql);
           $store_quantity=$query->row['quantity']; 
           if($store_quantity<$p_qnty)
           {
                return '0';
           }
           else
           { 
               return '1';
           }
           
        }
        public function check_ware_house_price($ware_house,$product_id,$p_price)
        {
           $sql="SELECT `wholesale_price` from  oc_product where `product_id` ='".$product_id."'";
           $query = $this->db->query($sql);
           $store_price=$query->row['price']; 
           if($store_price>$p_price)
           {
                return '0';
           }
           else
           {
               return '1';
           }
           
        }
	public function check_ship_to_credit($ship_to,$grand_total)
        {
           $sql="SELECT `creditlimit`,`currentcredit`,`name` from  oc_store where `store_id` ='".$ship_to."'";
           $query = $this->db->query($sql);
           $creditlimit=$query->row['creditlimit'];
           $currentcredit=$query->row['currentcredit'];
           $name=$query->row['name'];
           if(($currentcredit+$grand_total)>$creditlimit)
           {
                return '0';
           }
           else
           {
               return '1';
           }
           
        }
        public function get_to_store_data($store_id)
        {
           $store_data='';
           $sql="SELECT `value` from  oc_setting where store_id = ".$store_id." and `key` ='config_firmname'";
            
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
	public function get_ware_houses() {
        $sql2 = "select name,store_id from oc_store where store_id in 
(select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('2','4') )";
        $query2 = $this->db->query($sql2);
        return $query2->rows;
    }

    public function getList($data) {
        $sql = "SELECT
			oc_po_order.*,'1' as store_type
			, " . DB_PREFIX . "user.firstname
			, " . DB_PREFIX . "user.lastname
			
			
                        , oc_store.name as store_name
                        , oc_store.creditlimit as creditlimit
                        , oc_store.currentcredit as currentcredit
,oc_po_product.name as product,
oc_po_product.quantity
                        FROM
			oc_po_order
LEFT JOIN
oc_po_product ON (oc_po_order.id= oc_po_product.order_id)
			LEFT JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
                        LEFT JOIN oc_store
                ON (oc_po_receive_details.store_id = oc_store.store_id)
LEFT JOIN
oc_po_invoice on (oc_po_order.id= oc_po_invoice.po_order_id)
			
			LEFT JOIN " . DB_PREFIX . "user 
				ON (oc_po_order.user_id = " . DB_PREFIX . "user.user_id) WHERE oc_po_order.id != ''  ";

        $sql .= " and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('3','6')) ";

        if ($data['filter_status']!="") { 
            if (($data['filter_status'] == "0") || ($data['filter_status'] == "1") || ($data['filter_status'] == "2")) { 
                $sql .= " and oc_po_order.receive_bit='" . $data['filter_status'] . "'  ";
            }
            
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " and oc_po_invoice.create_date>='" . $data['filter_date_start'] . "' ";
        }
	if (!empty($data['filter_store'])) {
            $sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        }
        if (!empty($data['filter_date_end'])) {
            $sql .= " and oc_po_invoice.create_date<='" . $data['filter_date_end'] . "' ";
        }
        if (!empty($data['filter_id'])) {
            $sql .= " and oc_po_order.id='" . $data['filter_id'] . "' ";
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

    public function getTotalOrders($data) 
	{
		$sql = "select count(*) as total_orders from ( SELECT
			oc_po_order.*, " . DB_PREFIX . "user.firstname
			, " . DB_PREFIX . "user.lastname
			
			
                        , oc_store.name as store_name
                        , oc_store.creditlimit as creditlimit
                        , oc_store.currentcredit as currentcredit
,oc_po_product.name as product,
oc_po_product.quantity
                        FROM
			oc_po_order
LEFT JOIN
oc_po_product ON (oc_po_order.id= oc_po_product.order_id)
			LEFT JOIN oc_po_receive_details
				ON (oc_po_order.id = oc_po_receive_details.order_id)
                        LEFT JOIN oc_store
                ON (oc_po_receive_details.store_id = oc_store.store_id)
LEFT JOIN
oc_po_invoice on (oc_po_order.id= oc_po_invoice.po_order_id)
			
			LEFT JOIN " . DB_PREFIX . "user 
				ON (oc_po_order.user_id = " . DB_PREFIX . "user.user_id) WHERE oc_po_order.id != ''  ";

        $sql .= " and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('3','6')) ";

        if ($data['filter_status']!="") { 
            if (($data['filter_status'] == "0") || ($data['filter_status'] == "1") || ($data['filter_status'] == "2")) { 
                $sql .= " and oc_po_order.receive_bit='" . $data['filter_status'] . "'  ";
            }
            
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " and oc_po_invoice.create_date>='" . $data['filter_date_start'] . "' ";
        }
	if (!empty($data['filter_store'])) {
            $sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        }
        if (!empty($data['filter_date_end'])) {
            $sql .= " and oc_po_invoice.create_date<='" . $data['filter_date_end'] . "' ";
        }
        if (!empty($data['filter_id'])) {
            $sql .= " and oc_po_order.id='" . $data['filter_id'] . "' ";
        }
        $sql .= " GROUP BY oc_po_order.id ) as aa ";
        
        
        //echo $sql;
        //echo $data['start'];
        $query = $this->db->query($sql);
		return $query->row['total_orders'];
		/*
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

        $sql .= " and oc_po_receive_details.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('3','6')) ";
	//echo $data['filter_status'];
        if ($data['filter_status']!="") { 
            if (($data['filter_status'] == "0") || ($data['filter_status'] == "1") || ($data['filter_status'] == "2")) { 
                $sql .= " and oc_po_order.receive_bit='" . $data['filter_status'] . "'  ";
            }
            
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " and oc_po_order.order_date>='" . $data['filter_date_start'] . "' ";
        }
	if (!empty($data['filter_store'])) {
            $sql .= " and oc_po_receive_details.store_id='" . $data['filter_store'] . "' ";
        }
        if (!empty($data['filter_date_end'])) {
            $sql .= " and oc_po_order.order_date<='" . $data['filter_date_end'] . "' ";
        }
        if (!empty($data['filter_id'])) {
            $sql .= " and oc_po_order.id='" . $data['filter_id'] . "' ";
        }
        $sql .= " GROUP BY oc_po_order.id ";

        $sql .= " ) as aa";
        echo $sql;
        //echo $data['start'];
        $query = $this->db->query($sql);

        return $query->row['total_orders'];
		*/
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
            $log=new Log('create-invoice-'.date('Y-m-d').'.log');
			
            $sql="select po_invoice_n from oc_po_invoice where po_order_id='".$data['order_id']."'  ";
            $query = $this->db->query($sql);
            $results = $query->row;
            if (empty($results['po_invoice_n'])) {
            $sql2="select MAX(po_invoice_n) as po_invoice_n from oc_po_invoice where partner_type='0'  ";
            $query2 = $this->db->query($sql2);
            
            if ($query2->row['po_invoice_n']) {
				$invoice_no = $query2->row['po_invoice_n'] + 1;
			
                                
            } 
                else 
              {
		$invoice_no = 1;
	      }
            $sql3="insert into oc_po_invoice set po_store_id='".$data['store_id']."',po_order_id='".$data['order_id']."',po_ware_house='".$data['ware_house']."',po_invoice_n='".$invoice_no."',po_invoice_prefix='ASPL/BP',order_total='".$data['grand_total']."',store_to='".$data['store_to']."',create_date='".date('Y-m-d')."'  ";
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
				
		$inv_db_query="UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$p_qnty . ") WHERE product_id = '" . (int)$product_id . "' AND store_id = '".(int)$data['ware_house']."'";
                $log->write($inv_db_query);

		$this->db->query($inv_db_query);

                
                $this->load->library('trans');
                $trans=new trans($this->registry);
                $trans->addproducttrans($data['ware_house'],$product_id,$p_qnty,$data['order_id'],'DB','POFRA');  
               
            }
	
            $sql_po="update oc_po_order set  pending_bit = " . 0 . ",pre_supplier_bit=" . 1 . " where id='".$data['order_id']."'";
            $query = $this->db->query($sql_po);
            }
            //print_r($data);
            
        }
        public function getProducts($data = array()) {
		$sql = "select product_id ,model as model,HSTN as hstn,(price2) as price,price as price_wo_t,product_tax_type,product_tax_rate from (
SELECT 
    p.product_id as product_id,opd.name as model,p.wholesale_price as price,p.price as price2,p.HSTN,
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
    oc_product p left join oc_product_description as opd on p.product_id=opd.product_id  ";

		

		if (!empty($data['filter_model'])) {
			//$sql .= " where p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
			$sql .= " where opd.name LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
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
                //print_r($order_info['user_id']);
                $sql2="select name,store_id from oc_store where store_id in 
(select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('2','4') )";
		$query2 = $this->db->query($sql2);
		$ware_houses = $query2->rows;
                
                $sql22="select name,store_id from oc_store where store_id in 
(select store_id from oc_setting  where oc_setting.`key`='config_storetype' and oc_setting.value in ('3') )";
		$query22 = $this->db->query($sql22);
		$to_stores = $query22->rows;
                
                $view_order_details="SELECT 
oprd.product_id,ocp.wholesale_price as product_price,oprd.store_id,oprd.quantity as product_quantity,
ocp.model as product_name,ocp.HSTN as product_hsn,
ocp.wholesale_price as product_base_price,
((SELECT 
                   oc_tax_rate.name
                FROM
                    `oc_tax_rule` AS rl
                LEFT JOIN oc_tax_rate ON oc_tax_rate.tax_rate_id = rl.tax_rate_id
                WHERE
                    rl.`tax_class_id` = ocp.tax_class_id LIMIT 1)) AS product_tax_type,
                    
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
                    rl.`tax_class_id` = ocp.tax_class_id LIMIT 1)) AS product_tax_rate,
                    (SELECT 
            oc_setting.value AS store_address
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_address'
                AND oc_setting.store_id = oprd.store_id LIMIT 1) AS store_address,
                (SELECT 
            oc_setting.value AS store_pan
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_PAN_ID_number'
                AND oc_setting.store_id = oprd.store_id LIMIT 1) AS store_pan,
                (SELECT 
            oc_setting.value AS store_gst
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_gstn'
                AND oc_setting.store_id = oprd.store_id LIMIT 1) AS store_gst,
                (SELECT 
            oc_setting.value AS store_name
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_name'
                AND oc_setting.store_id = oprd.store_id LIMIT 1) AS store_name,
                (SELECT 
            oc_setting.value AS store_name
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_telephone'
                AND oc_setting.store_id = oprd.store_id LIMIT 1) AS store_phone,
                (SELECT 
            oc_setting.value AS store_name
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_email'
                AND oc_setting.store_id = oprd.store_id LIMIT 1) AS store_email,
                (SELECT 
            oc_store.creditlimit AS store_creditlimit
        FROM
            oc_store
        WHERE oc_store.store_id = oprd.store_id LIMIT 1) AS store_creditlimit,
                (SELECT 
            oc_store.currentcredit AS store_currentcredit
        FROM
            oc_store
        WHERE oc_store.store_id = oprd.store_id LIMIT 1) AS store_currentcredit,
                (SELECT 
            oc_user.cash AS store_outstanding
        FROM
            oc_user
        WHERE oc_user.user_id = ".$order_info['user_id']." LIMIT 1) AS store_outstanding
from
  oc_po_receive_details as oprd  
  LEFT JOIN oc_product as ocp on ocp.product_id=oprd.product_id
  where oprd.order_id=".$order_id; //config_telephone
	$products=array();
	$query = $this->db->query($view_order_details);
	if($this->db->countAffected() > 0)
	{
		$products = $query->rows;
                
        }
           $order_information['products'] = $products;
	   $order_information['order_info'] = $order_info;
           $order_information['ware_houses'] = $ware_houses;
           $order_information['to_stores'] = $to_stores;
           
	   return $order_information;     
        }
        public function view_order_details_for_created_invoice($order_id)
	{       $sql="SELECT oc_po_invoice.sid as sid,oc_po_invoice.po_order_id as po_order_id,oc_po_invoice.po_ware_house as po_ware_house,oc_po_invoice.po_store_id as po_store_id,oc_po_invoice.po_invoice_n as po_invoice_n,oc_po_invoice.po_invoice_prefix as po_invoice_prefix,oc_po_invoice.order_total as order_total,oc_po_invoice.store_id as store_id,oc_po_invoice.store_to as store_to,oc_po_invoice.create_date as create_date,oc_po_invoice.partner_type as partner_type,oc_store.name as store_name FROM oc_po_invoice"
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
                AND oc_setting.store_id = oprd.po_store_id limit 1) AS store_address,
    (SELECT 
            oc_setting.value AS store_pan
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_PAN_ID_number'
                AND oc_setting.store_id = oprd.po_store_id limit 1) AS store_pan,
    (SELECT 
            oc_setting.value AS store_gst
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_gstn'
                AND oc_setting.store_id = oprd.po_store_id limit 1) AS store_gst,
                (SELECT 
            oc_setting.value AS store_name
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_firmname'
                AND oc_setting.store_id = oprd.po_store_id LIMIT 1) AS store_name,
                (SELECT 
            oc_setting.value AS store_name
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_telephone'
                AND oc_setting.store_id = oprd.po_store_id LIMIT 1) AS store_phone,
                (SELECT 
            oc_setting.value AS store_name
        FROM
            oc_setting
        WHERE
            oc_setting.`key` = 'config_email'
                AND oc_setting.store_id = oprd.po_store_id LIMIT 1) AS store_email
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
                oc_product.price_tax as price,ware_house.name as ware_house_name,
                ((oc_product.price_tax)-(oc_product.price)) as tax,
                ware_house.store_id as ware_house_id,
                oc_po_receive_details.order_id
        FROM
            oc_po_receive_details
        LEFT JOIN oc_product ON oc_product.product_id=oc_po_receive_details.product_id
        LEFT JOIN oc_po_product
            ON (oc_po_receive_details.order_id = oc_po_product.order_id)
            AND (oc_po_receive_details.product_id = oc_po_product.product_id)
        LEFT JOIN oc_store as ware_house
            ON (oc_po_receive_details.supplier_id = ware_house.store_id)
        LEFT JOIN oc_store as oc_store
            ON (oc_po_receive_details.store_id = oc_store.store_id)
                WHERE (oc_po_receive_details.order_id =" . $order_id . ")";

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

	/*-----------------------------insert receive order function starts here-------------------*/
	
public function insert_receive_order($received_order_info, $order_id) 
{
        $log = new Log('po-' . date('Y-m-d') . '.log');
        $user_id = $received_order_info['user_id'];
        $sql = "select username from oc_user where  user_id = " . $user_id;
        $log->write($sql);
        $query = $this->db->query($sql);
        $store_mobile = $query->row['username'];
        $otp = rand(1000, 9999);
        if ($received_order_info['order_receive_date'] != '') {
            $received_order_info['order_receive_date'] = strtotime($received_order_info['order_receive_date']);
            $received_order_info['order_receive_date'] = date('Y-m-d', $received_order_info['order_receive_date']);
        }
        $sql = "UPDATE oc_po_order SET order_sup_send = '" . $received_order_info['order_receive_date'] . "', receive_bit = " . 0 . ", pending_bit = " . 0 . ",driver_otp='" . $otp . "',driver_mobile='" . $store_mobile . "' WHERE id = " . $order_id;
        //,pre_supplier_bit=" . 1 . "
        $log->write($sql);
        $this->db->query($sql);
        $total_product = count($received_order_info['ware_houses']);


        $this->load->library('trans');
        $trans = new trans($this->registry);

        for ($aa = 0; $aa < $total_product; $aa++) 
       {
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

        //$this->load->library('sms');
        //$sms = new sms($this->registry);
        //$sms->sendsms($store_mobile, "10", $filter_data);
        return $otp;
    }
	
	/*-----------------------------insert receive order function ends here-----------------*/

	public function download_excel($data=array())
	{
	$sql="select oc_po_order.id,opip.product_name,opip.p_price,opip.p_qnty,opip.p_tax_rate,opip.p_tax_type,opip.p_amount,oc_store.name,oc_store.store_id,opi.create_date,concat(po_invoice_prefix,'/',opi.sid) as invoice_number,(SELECT oc_setting.value FROM `oc_setting` WHERE `key`='config_gstn' and oc_setting.store_id=oc_po_order.store_id limit 1 ) as gstn from oc_po_order left join oc_po_invoice_product as opip on oc_po_order.id=opip.po_order_id left join oc_store on oc_po_order.store_id=oc_store.store_id left join oc_po_invoice as opi on oc_po_order.id=opi.po_order_id where oc_po_order.store_id in (select store_id from oc_setting where `key`='config_storetype' and `value` in ('3')) and opip.p_amount!='' ";

	

        if ($data['filter_status']!="") {
            if (($data['filter_status'] == "0") || ($data['filter_status'] == "1")) {
                $sql .= " and oc_po_order.receive_bit='" . $data['filter_status'] . "'  ";
            }
           
        }

        if (!empty($data['filter_date_start'])) {
            $sql .= " and opi.create_date>='" . $data['filter_date_start'] . "' ";
        }
	if (!empty($data['filter_store'])) {
            $sql .= " and oc_po_order.store_id ='" . $data['filter_store'] . "' ";
        }
        if (!empty($data['filter_date_end'])) {
            $sql .= " and opi.create_date<='" . $data['filter_date_end'] . "' ";
        }
        if (!empty($data['filter_id'])) {
            $sql .= " and oc_po_order.id='" . $data['filter_id'] . "' ";
        }
        $sql .= " GROUP BY oc_po_order.id order by oc_po_order.id desc  ";
//	echo $sql; 

	$query = $this->db->query($sql);
	return $query->rows;
	}
	public function insert_partner_receive_order($received_order_info,$order_id,$store_id,$user_id)
	{
		
	$log=new Log("receiveorder-".date('Y-m-d').".log");

		$log->write($received_order_info); 
		//tax LIB
		$this->load->library('tax');
		$taxobj=new Tax($this->registry);
                  	 

		$st_sql="select value as storetype from oc_setting WHERE `key`='config_storetype' and store_id=".$store_id;
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
		$updquery="UPDATE oc_po_order SET receive_date = '" .$received_order_info['order_receive_date']."', receive_bit = " . 1 . ", pending_bit = " . 0 . " WHERE id = " . $order_id;
		$this->db->query($updquery);
		$log->write("update order info done - ".$updquery);		
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
			$log->write("price");
			$log->write($prices);		
			$log->write("qnty");
			$log->write($received_quantities);		


			for($i =0; $i<count($received_quantities); $i++)
			{
			$log->write("in for loop");
				$updsql2="UPDATE oc_po_receive_details SET  quantity = ".$received_quantities[$i]." WHERE product_id =".$received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id;
				$log->write($updsql2);
				$this->db->query($updsql2);
				$query = $this->db->query("SELECT quantity FROM oc_po_receive_details WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id =" . $order_id);
				$quantities[$i] = $query->row['quantity'];
			$log->write("quantity");	
			$log->write($quantities[$i]);	
			}
		}
		else
		{
			$query = $this->db->query("SELECT * FROM oc_po_receive_details WHERE order_id=".$order_id);
			$log->write($query);
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
						$insertsql="INSERT INTO oc_po_receive_details (quantity,product_id,supplier_id,order_id,store_id) VALUES(".$received_order_info['received_quantities'][$k].",".$received_order_info['received_product_ids'][$j].",".$received_order_info['suppliers_ids'][$k].",".$order_id.",".$query->rows[$j]["store_id"].")";
						$this->db->query($insertsql);
						$log->write($insertsql);
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

			$updsql3="UPDATE oc_po_product SET received_products = " . $quantities[$i] . " WHERE product_id = " . $received_order_info['received_product_ids'][$i] . " AND order_id = " . $order_id;
			$query1 = $this->db->query($updsql3);
			$log->write($updsql3);
		}
					$totalamount=0;
		for($i=0; $i<count($product_ids); $i++)
		{
			$sql4="SELECT quantity FROM ".DB_PREFIX."product_to_store WHERE store_id=".$store_id." AND product_id = " . $product_ids[$i]['product_id'];
			$log->write($sql4);
			$query = $this->db->query($sql4);
			$quantity =  $quantities[$i];
			$sql5="UPDATE ".DB_PREFIX."product SET quantity = quantity + " . $quantity . " WHERE product_id = " . $product_ids[$i]['product_id'];
			$log->write($sql5);
			$query1 = $this->db->query($sql5);
			$sql6="UPDATE ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " WHERE store_id=".$store_id." AND product_id = " . $product_ids[$i]['product_id'];
			$log->write($sql6);
			$query2 = $this->db->query($sql6);
			$log->write("no product");
				
                        try{
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            $trans->addproducttrans($store_id,$product_ids[$i]['product_id'],$quantity,$order_id,'CR','PO');      
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
			if($query2->num_rows==0)
			{	
				$sql7="insert into  ".DB_PREFIX."product_to_store SET quantity = quantity + " . $quantity . " , store_id=".$store_id." ,product_id = " . $product_ids[$i]['product_id'];
				$log->write($sql7);
				$this->db->query($sql7);

			}
			if($query && $query1 && $query2)
				{
					$log->write("before credit change in ");
					$sql8="SELECT * FROM ".DB_PREFIX."product_to_store p2s left join ".DB_PREFIX."product p on p.product_id =p2s.product_id   WHERE store_id=".$store_id." AND p2s.product_id = " . $product_ids[$i]['product_id'];
					$log->write($sql8); 
					//upadte current credit
						//get product details
					$queryprd = $this->db->query($sql8);
					$log->write($queryprd);
					if(($storetype==3) || ($storetype==4))
                				{ 
						$log->write('in if storetype 3 or 4');
						if(!empty($queryprd->row['wholesale_price']))
						{
						$log->write('in if wholsale_price not empty');
						$tax=$taxobj->getTax($queryprd->row['wholesale_price'], $queryprd->row['tax_class_id']);
						//$tax=$this->tax->getTax($queryprd->row['wholesale_price'], $queryprd->row['tax_class_id']);
						$log->write('after tax');
						$log->write($tax);
						$totalamount=$totalamount+($quantity*$queryprd->row['wholesale_price'])+($quantity*$tax);
						}
						else
						{
							$log->write('in else wholsale_price empty');
                                                        $tax=$taxobj->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']);
							//$tax=$this->tax->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']);
							$log->write('after tax');
							$log->write($tax);
							$totalamount=$totalamount+($quantity*$queryprd->row['price'])+($quantity*$tax);
						}
					}
					else
					{
						$log->write('in else storetype not 3 or 4');
                                                $tax=$taxobj->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']);
						//$tax=$this->tax->getTax($queryprd->row['price'], $queryprd->row['tax_class_id']);
						$log->write('after tax');
						$log->write($tax);
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
                
                if(($storetype==3) || ($storetype==4))
                {
                $sql_update="UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit - " . $totalamount . " WHERE store_id=".$store_id;
                $log->write($sql_update);
                $this->db->query($sql_update);   
                try{
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($totalamount,$store_id,$user_id,'DB',$order_id,'PO',$totalamount); 
                                
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
                }
                else
                {
                $sql_update="UPDATE ".DB_PREFIX."store SET currentcredit = currentcredit + " . $totalamount . " WHERE store_id=".$store_id;
                $log->write($sql_update);
                $this->db->query($sql_update);   
                try{
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($totalamount,$store_id,$user_id,'CR',$order_id,'PO',$totalamount); 
                                
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
                }
			}
		if($bool)
			return true; 
	}
	
	/*-----------------------------insert receive order function ends here-----------------*/
	public function get_order_data($order_id)
	{
		$sql="SELECT * FROM oc_po_order where id=".$order_id;
		$query = $this->db->query($sql); 
		return $query->row;
		
	} 
} 
?>