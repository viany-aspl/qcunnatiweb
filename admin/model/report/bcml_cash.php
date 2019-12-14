<?php
class ModelReportBcmlcash extends Model {
public function fm_item_wise_summary($data) {
	
		$sql="
			SELECT 
    sum(oc_order_product.quantity) as quantity,
	oc_order_product.name as name,
	oc_order_product.product_id as product_id,
	fmorder.date_added as ORD_DATE,
	oc_order_product.price as price,
	oc_order_product.tax as tax 

FROM
    oc_order_product
 join (SELECT 
            oc_order.order_id as order_id,oc_order.date_added
        FROM
            oc_order_delivery_advance
       		left join oc_order on oc_order_delivery_advance.invoice_no=oc_order.order_id
       
        WHERE
            fmcode = '".$data['fm_code']."'
                AND DATE(oc_order.date_added) BETWEEN '".$data['start_date']."' and '".$data['end_date']."'
				and store_id='".$data['store_id']."'
                UNION ALL 
                SELECT 
            oc_order.order_id as order_id,oc_order.date_added
        FROM
            oc_order_delivery
       		left join oc_order on oc_order_delivery.invoice_no=oc_order.order_id
        WHERE
            fmcode = '".$data['fm_code']."'
                AND DATE(oc_order.date_added) BETWEEN '".$data['start_date']."' and '".$data['end_date']."' and store_id='".$data['store_id']."' ) 
                as fmorder 
                on fmorder.order_id=oc_order_product.order_id 
              
                group by oc_order_product.product_id  
                
		 ";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$log=new Log("fm_item_wise_summary-".date('Y-m-d').".log");
		$log->write($sql); 
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOrder($order_id) 
	{
		$log=new Log("fm_login-".date('Y-m-d').".log"); 
		//$log->write('in model');
		//$log->write($order_id);
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) 
		{
			//$log->write('in if');
			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			//$log->write('1');
			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
			//$log->write('2');
			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
			//$log->write('3');
			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
			//$log->write('4');
			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
			//$log->write('5');
			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			//$this->load->model('marketing/affiliate');

			//$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);
			//$log->write('6');
			/*
			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}
			*/
			$affiliate_firstname = '';
			$affiliate_lastname = '';
			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
			//$log->write('7');
			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}
			//$log->write('8');
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'user_id'              => $order_query->row['user_id'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
                                'card_no' => $order_query->row['card_no'],
				'custom_field'            => unserialize($order_query->row['custom_field']),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => unserialize($order_query->row['payment_custom_field']),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => unserialize($order_query->row['shipping_custom_field']),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'fmcode'                      => $order_query->row['fmcode'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'unit_id'           => $order_query->row['unit_id'],
				'tagged'		=>   $order_query->row['tagged'],
				'cash'		=>   $order_query->row['cash'],
				'subsidy'		=>   $order_query->row['subsidy']		

			);
		} else {
			//$log->write('in else');
			return;
		}
		//$log->write($order_id);
	}
	public function getSubsidyOrders($data = array())
	{
		$data['filter_date_modified']=date('Y-m-d',strtotime($data['filter_date_modified'] . "+1 days"));
		$log=new Log("order-history-".date('Y-m-d').".log");
		$sql="select * from oc_order as o where o.payment_method in ('Subsidy','Tagged Subsidy') and o.order_status_id=5 and o.subsidy_form_no=0 ";
		
		if (!empty($data['filter_order_id'])) 
		{
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_store'])) 
		{
			$sql .= " AND o.store_id = '" . (int)$data['filter_store'] . "'";
		}
		if (!empty($data['grower_id'])) 
		{
			$sql .= " AND o.shipping_firstname = '" .$data['grower_id'] . "'";
		}
		$sql.=" order by o.order_id desc";
		if (isset($data['start']) || isset($data['limit'])) 
		{
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$log->write($sql);
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getFmOrders($data = array()) 
	{
		$data['filter_date_modified']=date('Y-m-d',strtotime($data['filter_date_modified'] . "+1 days"));
		$log=new Log("order-history-".date('Y-m-d').".log");

		$sql = "
		select * from (
		
		SELECT  oda.fmcode as fmcode,oda.delivery_status as delivery_status ,o.user_id,o.order_id,o.payment_method as payment_method, 
		CONCAT(o.firstname, ' ', o.lastname) AS customer,o.shipping_firstname,
		o.telephone, (SELECT os.name 
		FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id 
		AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, 
		o.shipping_code, o.total,o.tagged,o.cash, o.currency_code, o.currency_value, o.date_added, o.date_modified,
		o.store_name,o.store_id,CONCAT(o.invoice_prefix,'-',o.invoice_no) as `invoice_no`
		FROM `" . DB_PREFIX . "order` o
		left join oc_order_delivery_advance as oda on o.order_id=oda.invoice_no
		";
		//$log->write($sql);
		$sql .= " WHERE o.order_status_id = '5' ";
		
		if (!empty($data['filter_order_id'])) 
		{
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['grower_id'])) 
		{
			$sql .= " AND o.shipping_firstname = '" .$data['grower_id'] . "'";
		}
		$sql .= " AND o.payment_method != 'Cash' and  oda.fmcode !='0' ";
		if(!empty($data['filter_fm_id']))
		{
				$sql .= " AND oda.fmcode = '" . (int)$data['filter_fm_id'] . "'";
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
		
		$sql .= "
		union all
		
		SELECT  oda.fmcode as fmcode,oda.delivery_status as delivery_status ,o.user_id,o.order_id,o.payment_method as payment_method, 
		CONCAT(o.firstname, ' ', o.lastname) AS customer,o.shipping_firstname,
		o.telephone, (SELECT os.name 
		FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id 
		AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, 
		o.shipping_code, o.total,o.tagged,o.cash, o.currency_code, o.currency_value, o.date_added, o.date_modified,
		o.store_name,o.store_id,CONCAT(o.invoice_prefix,'-',o.invoice_no) as `invoice_no`
		FROM `" . DB_PREFIX . "order` o
		left join oc_order_delivery as oda on o.order_id=oda.invoice_no
		";
		//$log->write($sql);
		$sql .= " WHERE o.order_status_id = '5' ";
		
		if (!empty($data['filter_order_id'])) 
		{
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['grower_id'])) 
		{
			$sql .= " AND o.shipping_firstname = '" .$data['grower_id'] . "'";
		}
		$sql .= " AND o.payment_method != 'Cash' and  oda.fmcode !='0' ";
		if(!empty($data['filter_fm_id']))
		{
				$sql .= " AND oda.fmcode = '" . (int)$data['filter_fm_id'] . "'";
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
		
		$sql.=") as a";
		 $sql .= " ORDER BY a.order_id desc";
		//echo $sql;exit;
		$log->write($sql);
		$query = $this->db->query($sql);

		return $query->rows;

	}
	
	
	
	public function getTotalOrders($data = array()) {
			
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "bcml_cash_order_trans`";

		if (!empty($data['filter_order_id'])) {
        $sql .= " WHERE order_id= '" . (int)$data['filter_order_id'] . "'";
        }
      if (!empty($data['filter_store_id'])) {
        $sql .= " AND store_id= '" . (int)$data['filter_store_id'] . "'";
        }	
		  
                //echo $sql;
		$query = $this->db->query($sql);

		return $query->row;
	}
	public function getOrders($data = array()) 
	{

		//$data['filter_date_modified']=date('Y-m-d',strtotime($data['filter_date_modified'] . "+1 days"));
		$log=new Log("bcml-cash-".date('Y-m-d').".log");

		$sql = "SELECT * FROM oc_bcml_cash_order_trans";
		
	if (!empty($data['filter_order_id'])) {
        $sql .= " WHERE order_id= '" . (int)$data['filter_order_id'] . "'";
        }
      if (!empty($data['filter_store_id'])) {
        $sql .= " AND store_id= '" . (int)$data['filter_store_id'] . "'";
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
	
		$log->write($sql);
		$query = $this->db->query($sql);

		return $query->rows;

	}
	public function getOrderscompanywise($data = array()) {


		$log=new Log("historys-".date('Y-m-d').".log");

		$sql = "SELECT o.order_id,o.comment as req_id,o.payment_method as payment_method, CONCAT(o.firstname, ' ', o.lastname) AS customer,o.telephone, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.payment_method,o.store_name,o.store_id,CONCAT(o.invoice_prefix,'-',o.invoice_no) as `invoice_no`,ou.company_id,ou.unit_name FROM `" . DB_PREFIX . "order` o   left join oc_store_to_unit as osu on o.store_id=osu.store_id left join oc_unit as ou on osu.unit_id=ou.unit_id ";
		$log->write($sql);
		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")"; 
			} else {

			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}


		if (!empty($data['filter_telephone_id'])) {
			$sql .= " AND o.telephone = '" . $data['filter_telephone_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_requistion_id'])) {
			$sql .= " AND o.comment = '" . (int)$data['filter_requistion_id'] . "'";
		}
		if (!empty($data['filter_payment'])) {
			$sql .= " AND o.payment_method = '" .$data['filter_payment'] . "'";
		}
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
                	if (!empty($data['filter_store'])) {
			$sql .= " AND o.store_id = '" . (float)$data['filter_store'] . "'";
		}
                if(!empty($data['filter_user_id'])){
                    $sql .= " AND user_id = '" . (float)$data['filter_user_id'] . "'";
                }
                $sql.="  and ou.company_id='".$data['filter_company']."' ";
		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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
		//echo $sql;
		$log->write($sql);
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOrderspos($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE o.order_status_id <>'5' AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
                
                if(!empty($data['filter_user_id'])){
                    $sql .= " AND user_id = '" . (float)$data['filter_user_id'] . "'";
                }
                
		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOrderProducts($order_id) 
	{
		$query = $this->db->query("SELECT oc_order_product.*,oc_product.HSTN as hsn_code FROM " . DB_PREFIX . "order_product left join oc_product on oc_order_product.product_id=oc_product.product_id WHERE oc_order_product.order_id = '" . (int)$order_id . "'");

		return $query->row;
	}

	public function getOrderOption($order_id, $order_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");

		return $query->row;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderVoucherByVoucherId($voucher_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getorderTotalvalue($oid)
	{
		//
			$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('total') AND o.order_id='".$oid."' ");
			return $query->row["total"];
		//		

	}	

	public function getorderTaxvalue($oid)
	{
		//
			$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('tax') AND o.order_id='".$oid."' ");
			if(empty($query->row["total"]))
			{$query->row["total"]=0;}
			return $query->row["total"];
		//		

	}

	public function getorderSubTotalvalue($oid)
	{
		//
			$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_id='".$oid."' ");
			return $query->row["total"];
		//		

	}





	
	public function getTotalOrderscompanywise($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`   left join oc_store_to_unit as osu on oc_order.store_id=osu.store_id left join oc_unit as ou on osu.unit_id=ou.unit_id ";

		if (!empty($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_requistion_id'])) {
			$sql .= " AND comment = '" . (int)$data['filter_requistion_id'] . "'";
		}
		if (!empty($data['filter_payment'])) {
			$sql .= " AND payment_method = '" .$data['filter_payment'] . "'";
		}
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_added']) . "' as DATETIME)";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_modified']) . "' as DATETIME)";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}
                	if (!empty($data['filter_store'])) {
			$sql .= " AND store_id = '" . (float)$data['filter_store'] . "'";
		}
                if(!empty($data['filter_user_id'])){
                    $sql .= " AND user_id = '" . (float)$data['filter_user_id'] . "'";
                }
                $sql.="  and ou.company_id='".$data['filter_company']."' ";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByProcessingStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_processing_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByCompleteStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}
/*
	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}
*/
public function createInvoiceNo($order_id) {
                    $order_info = $this->getOrder($order_id);
                    $log=New log('invoice-'.date('Y-m-d').'.log');
                    $log->write($order_info);
                    if ($order_info && !$order_info['invoice_no']) {
                        $sql="SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' and store_id='" . $this->db->escape($order_info['store_id']) . "'";
                        $log->write($sql);
			$query = $this->db->query($sql);
                        $log->write($query);
			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}
                        $log->write($invoice_no);
                        $sql2="SELECT invoice_no as inv_no  FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' and store_id='" . $this->db->escape($order_info['store_id']) . "' and invoice_no='".$invoice_no."' ";
                        $query2 = $this->db->query($sql2);
			$log->write($sql2);
                        $log->write($query2->row['inv_no']);
                        if($query2->row['inv_no']<$invoice_no)
                        {
                           $sql3="UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'";
                           $this->db->query($sql3);
                           $log->write($sql3);
                           return $order_info['invoice_prefix'] . $invoice_no; 
                        }
                        else 
                        {   
                            $this->createInvoiceNo($order_id);
                            
                        }
                        
                        
                        }
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}

	public function getEmailsByProductsOrdered($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}

	public function getTotalEmailsByProductsOrdered($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

		return $query->row['total'];
	}

	public function getProductSubsidy($product_id,$store_id) {
		$log=new Log("historyprd.log");
		$log->write("SELECT * FROM " . DB_PREFIX . "product_subsidy WHERE store_id = '".$store_id."' product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity >= 1  ORDER BY quantity ASC, priority ASC, price ASC");
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_subsidy WHERE store_id = '".$store_id."' AND product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity >= 1  ORDER BY quantity ASC, priority ASC");
		return $query->row["subsidy"];
	}

public function getOrderStoreId($order_id) {
		$log=new Log("historyprd-".date('Y-m-d').".log");
		$log->write("SELECT store_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");	
		$query = $this->db->query("SELECT store_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");
		return $query->row["store_id"];
	}
public function getOrderSubsidy($order_id) {
		$query = $this->db->query("SELECT subsidy FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");
		return $query->row["subsidy"];
	}
	public function getOrder_detail($order_id) {


	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			
	return $query->rows;

								
	}



public function getOrderUser($order_id) {
		$query = $this->db->query("SELECT user_id FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");
		return $query->row["user_id"];
	}


public function getOrdercash($order_id) {
		$query = $this->db->query("SELECT cash FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");
		return $query->row["cash"];
	}
public function getOrderreqid($order_id) {
		$query = $this->db->query("SELECT comment FROM " . DB_PREFIX . "order WHERE order_id = '" . (int)$order_id . "'");
		if(empty($query->row["comment"]))
		{
			$query = $this->db->query("SELECT requisition_id as comment FROM " . DB_PREFIX . "requisition_to_bill WHERE bill_id = '" . (int)$order_id . "'");
		}
		return $query->row["comment"];
	}


public function gettodaysales_cash_tageed_subsidy($today_date,$store_id) {
		
		$sql="SELECT sum(total) as total,sum(cash) as cash,sum(tagged) as tagged,sum(subsidy) as subsidy FROM `oc_order` where DATE(date_added)='".$today_date."' and store_id='".$store_id."'  and order_status_id='5' "; 
		$log=new Log("todaysale-".date('Y-m-d').".log"); 
		$log->write($sql); 
		$query = $this->db->query($sql);		
		return $query->row;
	}
public function getTop_5_Orders($data=array()) {
                         
 $sql="select sum(total) as total ,store_name FROM `oc_order` o left join oc_store as st on st.store_id = o.store_id 
 where (o.date_added) >= CAST('".$this->db->escape($data['filter_date_start'])." 00:00:00' as datetime ) AND (o.date_added) <= CAST('".$this->db->escape($data['filter_date_end'])." 00:00:00' as datetime) GROUP BY o.store_id order by total desc limit 0,5 ";
 
 $query = $this->db->query($sql);
		return $query->rows;
	}
public function getTop_5_Products($data=array()) {

 $sql="select oc_order_product.product_id as product_id,sum(oc_order_product.quantity)as sales_of_qnty,oc_order_product.name from oc_order_product where 
 order_id IN (select order_id from oc_order where order_status_id ='5' ";
 
 if (!empty($data['filter_date_start'])) {
			$sql .= " AND (date_added) >= CAST('" . $this->db->escape($data['filter_date_start']) . "' as DATETIME)";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND (date_added) <= CAST('" . $this->db->escape($data['filter_date_end']) . "' as DATETIME)";
		}
 $sql.=" ) group by product_id order by sales_of_qnty desc limit 5 ";
 
 
 //echo $sql;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getOrderInfo($order_id) {


	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id = '" . $order_id . "'  ");

			
	return $query->row;

								
	}
	public function getSuccessOrderInfo($order_id) {


	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE order_id = '" . $order_id . "' and order_status_id=5 ");

			
	return $query->row;

								
	}
	public function getsubsidyCategoryName($cat_id) {


	$query = $this->db->query(" SELECT category_name FROM `oc_category_subsidy` where category_id='".$cat_id."'  ");

			
	return $query->row['category_name']; 

								
	}

	public function getOrderdelivery($order_id,$fmcode=0) 
	{
		$sql1="SELECT obdt.name as delivery_type_name,fmname,delivery_status,ood.fmcode as fmcode FROM oc_order_delivery as ood left join oc_bcml_delivery_type as obdt on ood.tr_type=obdt.sid WHERE ood.invoice_no = '" . $order_id . "'";
		if(!empty($fmcode))
		{
			$sql1.=" and ood.fmcode='".$fmcode."' ";
		}
		$log=new Log("order-history-".date('Y-m-d').".log");
		$log->write($sql1);
		$query = $this->db->query($sql1);
		if(empty($query->row['delivery_type_name']))
		{	
			$sql2="SELECT obdt.name as delivery_type_name,fmname,delivery_status,ood.fmcode as fmcode FROM oc_order_delivery_advance as ood left join oc_bcml_delivery_type as obdt on ood.tr_type=obdt.sid WHERE ood.invoice_no = '" . $order_id . "'";
			if(!empty($fmcode))
			{
				$sql2.=" and ood.fmcode='".$fmcode."' ";
			}
			$query = $this->db->query($sql2);
			$log->write($sql2);
		}
   
		return $query->row;
	}
	public function getdeliveryOtp($order_id) 
	{
		$query = $this->db->query("SELECT * from oc_order_delivery WHERE invoice_no = '" . $order_id . "'");
		return $query->row;
	}
	public function getAdvancedeliveryOtp($order_id) 
	{
		$query = $this->db->query("SELECT * from oc_order_delivery_advance WHERE invoice_no = '" . $order_id . "'");
		return $query->row;
	}
	
	
	
	public function update_subsidy_form($data){
		
		
		 $log=new Log("update_subsidy_form-".date('Y-m-d').".log");
		 $sql="UPDATE `" . DB_PREFIX . "order` SET subsidy_form_no = '" .$data['subsidy_form_no'] . "' WHERE order_id = '" . $data['order_id'] . "'";
         $log->write($sql);	 
	     $query= $this->db->query($sql);
	     $log->write($query);
	     return $query; 
}
}