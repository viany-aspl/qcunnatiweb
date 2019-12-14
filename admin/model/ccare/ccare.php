<?php
date_default_timezone_set('Asia/Kolkata');
class ModelCcareCcare extends Model {

public function insertincomming($mobile,$customer_id)
    {
        $log=new Log("call-".date('Y-m-d').".log");
        $current_time=date('H:i:s');
        $current_date_time=date('Y-m-d H:i:s');
        $sql1="select notimesreceived,transid from  `cc_incomingcall` where `mobile`='".$mobile."' and status='18' ";
        $log->write($sql1);
        $check_query = $this->db->query($sql1);
        $mobilecode=substr($mobile, 0, 4);

        $sql12="SELECT * FROM `oc_mobilestate`  where mobilecode='".$mobilecode."' ";
        $log->write($sql12);
        $state_query = $this->db->query($sql12);
        $state_code=$state_query->row["stateid"];
        $state_name=$state_query->row["state"];
        $log->write($state_query->row);
        $check_query = $this->db->query($sql1);

        if($check_query->row["notimesreceived"]!="")
        {
           $newcount=$check_query->row["notimesreceived"]+1;
           $transid=$check_query->row["transid"];
           $sql2="update `cc_incomingcall` set notimesreceived=notimesreceived+1,status='18',state_name='".$state_name."',state='".$state_code."',farmerid='".$customer_id."' where `transid`='".$transid."' ";
           $update_query = $this->db->query($sql2);  
           $log->write($sql2);
        }
        else
        {
        
        $sql="insert into `cc_incomingcall` set `mobile`='".$mobile."',status='18',timereceived='".$current_time."' ,state_name='".$state_name."',state='".$state_code."',farmerid='".$customer_id."'  ";
        
              
        $trans_query = $this->db->query($sql); 
        $log->write($sql);
        }
        
 }








      public function SubmitCallData($data= array())
    {
        $sql="insert into `call_trans_history` set `from`='".$data["current_call_status"]."',`to`='".$data["call_status"]."',`order_id`='".$data["order_id"]."',`mobile_number`='".$data["mobile"]."',`datetime`='".$data["datetime"]."' ,`current_order_status`='".$data["current_order_status"]."',`logged_user_data`='".$data["logged_user_data"]."' ";
        	$log=new Log("call_trans_history-".date('Y-m-d').".log");
              $log->write("insert into `call_trans_history` set `from`='".$data["current_call_status"]."',`to`='".$data["call_status"]."',`order_id`='".$data["order_id"]."',`mobile_number`='".$data["mobile"]."',`datetime`='".$data["datetime"]."' ,`current_order_status`='".$data["current_order_status"]."',`logged_user_data`='".$data["logged_user_data"]."' ");

        $trans_query = $this->db->query($sql);
	$transs_id=$this->db->getLastId();
        $update_call_status_sql="update `oc_order_leads` set `call_status`='".$data["call_status"]."' where `order_id`='".$data["order_id"]."' ";
        $call_status_query = $this->db->query($update_call_status_sql);
        $log->write($update_call_status_sql);
        $get_customer_query = $this->db->query("SELECT customer_id FROM oc_customer where `telephone`='".$data["mobile"]."' limit 1 ");
        $customer_id=$get_customer_query->row["customer_id"];
        
        if($data["call_status"]=="1")
        {
        
        $get_customer_address = $this->db->query("SELECT firstname FROM oc_address where `customer_id`='".$customer_id."' limit 1 ");
        if ($get_customer_address->num_rows) {
          $update_cust_address_query = $this->db->query("update `oc_address` set `firstname`='".$data["farmer_first_name"]."',`lastname`='".$data["farmer_last_name"]."',`address_1`='".$data["village"]."',`sowing_date`='".$data["sowing_date"]."' where `customer_id`='".$customer_id."' ");
          $log->write("update `oc_address` set `firstname`='".$this->db->escape($data["farmer_first_name"])."',`lastname`='".$this->db->escape($data["farmer_last_name"])."',`address_1`='".$this->db->escape($data["village"])."',`sowing_date`='".$this->db->escape($data["sowing_date"])."' where `customer_id`='".$this->db->escape($customer_id)."' ");

        }
        else
        {
             $log->write("insert into  `oc_address` set `firstname`='".$data["farmer_first_name"]."',`lastname`='".$data["farmer_last_name"]."',`company`='Unnati',`address_1`='".$data["village"]."',`customer_id`='".$customer_id."',`sowing_date`='".$data["sowing_date"]."' ");
             $insert_cust_address_query = $this->db->query("insert into  `oc_address` set `firstname`='".$this->db->escape($data["farmer_first_name"])."',`lastname`='".$this->db->escape($data["farmer_last_name"])."',`company`='Unnati',`address_1`='".$this->db->escape($data["village"])."',`customer_id`='".$this->db->escape($customer_id)."',`sowing_date`='".$this->db->escape($data["sowing_date"])."' ");
        }
        $insert_response_query = $this->db->query("insert into  `ccare_feedback` set `txt_response`='".$this->db->escape($data["txt_response"])."',`buy_new`='".$data["buy_new"]."',`buy_product_text`='".$this->db->escape($data["buy_product_text"])."',`customer_mobile`='".$data["mobile"]."',`datetime`='".$data["sowing_date"]."',`Reason_of_response`='".$this->db->escape($data["Reason_of_response"])."',"
                . "`Acres`='".$this->db->escape($data["Acres"])."' ,`trans_id`='".$transs_id."',`buy_new_date`='".$data["buying_date"]."'  ");
	$log->write("insert into  `ccare_feedback` set `txt_response`='".$data["txt_response"]."',`buy_new`='".$data["buy_new"]."',`buy_product_text`='".$data["buy_product_text"]."',`customer_mobile`='".$data["mobile"]."',`datetime`='".$data["sowing_date"]."',`Reason_of_response`='".$data["Reason_of_response"]."',"
                . "`Acres`='".$data["Acres"]."' ,`trans_id`='".$transs_id."',`buy_new_date`='".$data["buying_date"]."'  ");
        }
        
 }
    
    
    
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order_leads` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_leads WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

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

			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
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
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
                                'date_potential'              => $order_query->row['date_potential'],
				'date_modified'           => $order_query->row['date_modified'],
                                'call_status'             => $order_query->row['call_status']
			);
		} else {
			return;
		}
	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id,o.store_name,o.date_potential, CONCAT(o.firstname, ' ', o.lastname) AS customer,(SELECT CONCAT(ou.firstname,' ',ou.lastname) from oc_user as ou where user_group_id='26' and ou.user_id=o.user_id ) as ase_name,o.telephone, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order_leads` o   ";

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

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		$today=date('Y-m-d');
		//if (!empty($data['filter_date_added'])) {
			//$sql .= " AND DATE(o.date_added) != '".$today."' ";
			$sql.=" AND DATE(o.date_added) BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 15 DAY ) ) AND DATE ( NOW() ) ";
		//}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
                
                if(!empty($data['filter_user_id'])){
                    $sql .= " AND user_id = '" . (float)$data['filter_user_id'] . "'";
                }
                $sql.=" AND o.call_status !='1' and o.order_status_id != '5' ";
		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total',
                        'o.store_name'
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
		$query = $this->db->query($sql);

		return $query->rows;
	}
        
        public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM (SELECT o.order_id,o.store_name,o.date_potential, CONCAT(o.firstname, ' ', o.lastname) AS customer,o.telephone, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order_leads` o   ";

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

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		$today=date('Y-m-d');
		//if (!empty($data['filter_date_added'])) {
			//$sql .= " AND DATE(o.date_added) != '".$today."' ";
			$sql.=" AND DATE(o.date_added) BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 15 DAY ) ) AND DATE ( NOW() ) ";
		//}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
                
                if(!empty($data['filter_user_id'])){
                    $sql .= " AND user_id = '" . (float)$data['filter_user_id'] . "'";
                }
                $sql.=" AND o.call_status !='1' and o.order_status_id != '5'  ) as aa";
		$query = $this->db->query($sql);
                   //echo $sql;
		return $query->row['total'];
	}

        
public function getOrdersCompleted($data = array()) {
		$sql = "SELECT o.order_id,o.store_name,o.date_potential, CONCAT(o.firstname, ' ', o.lastname) AS customer,o.telephone, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order_leads` o   ";

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
			$sql .= " WHERE o.order_status_id ='5'";
		}


		if (!empty($data['filter_telephone_id'])) {
			$sql .= " AND o.telephone = '" . $data['filter_telephone_id'] . "'";
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
                $sql.=" AND o.call_status !='1'  ";
		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total',
                        'o.store_name'
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
		$query = $this->db->query($sql);

		return $query->rows;
	}

public function getTotalOrdersCompleted($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_leads`";

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
			$sql .= " WHERE order_status_id = '5'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}
                
                if(!empty($data['filter_user_id'])){
                    $sql .= " AND user_id = '" . (float)$data['filter_user_id'] . "'";
                }
                $sql.=" AND call_status='0'";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

        
        
               public function getCallFeedback($ct_SID) 
        {
            $sql="select * from ccare_feedback where trans_id='".$ct_SID."' limit 1";
            
            $query = $this->db->query($sql);
            return $query->row;
        }
        public function getCallData($ct_SID,$order_id)
        {
            $sql="select oco.store_name,oca.sowing_date,oca.firstname,oca.lastname,oca.address_1 as village_name from  oc_order_leads as oco join oc_address as oca on oco.customer_id=oca.customer_id where oco.order_id='".$order_id."' ";
        
            $query = $this->db->query($sql);
            return $query->row;
        }
       
        
        public function getCallsPending($data = array()) {
		$sql = "SELECT ch.SID,ch.to,ch.mobile_number,ch.order_id,ch.datetime as call_time "
                       
                        ."FROM `call_trans_history` as ch "
                        . "where DATE(ch.datetime)>='".$data["filter_date_start"]."' ";

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(ch.datetime) <= '" . $data['filter_date_end'] . "'";
		}

                $sql.=" and current_order_status='1' order by ch.SID desc";
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
		$query = $this->db->query($sql);

		return $query->rows;
	}
        
        public function getTotalCallsPending($data = array()) {
		$sql = "select count(*) as total from  (SELECT * FROM `call_trans_history` where DATE(datetime)>='".$data["filter_date_start"]."' ";

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(datetime) <= '" . $data['filter_date_end'] . "'";
		}
                $sql.=" and current_order_status='1' ) as aa";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

        public function getCallscompleted($data = array()) {
		$sql = "SELECT ch.SID,ch.to,ch.mobile_number,ch.order_id,ch.datetime as call_time "
                       
                        ."FROM `call_trans_history` as ch "
                        . "where DATE(ch.datetime)>='".$data["filter_date_start"]."' ";

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(ch.datetime) <= '" . $data['filter_date_end'] . "'";
		}

                $sql.=" and current_order_status='5' order by ch.SID desc";
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
		$query = $this->db->query($sql);

		return $query->rows;
	}
        
        public function getTotalCallscompleted($data = array()) {
		$sql = "select count(*) as total from  (SELECT * FROM `call_trans_history` where DATE(datetime)>='".$data["filter_date_start"]."' ";

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(datetime) <= '" . $data['filter_date_end'] . "'";
		}
                $sql.=" and current_order_status='5' ) as aa";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

        
        
        	   public function getCustomers($data=array())
        {
            $sql="SELECT oc.*,os.name as store_name FROM oc_customer as oc join oc_store as os on os.store_id=oc.store_id where "
                    . "oc.telephone not in (select telephone from oc_order_leads) "
                    . "and "
                    . "oc.telephone not in (select telephone from oc_order) and oc.call_status!='1' order by oc.customer_id desc";
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
            $query=$this->db->query($sql);
            return $query->rows;
        }
        public function getTotalCustomers($data=array())
        {
             $sql="select count(*) as total from (SELECT oc.*,os.name as store_name FROM oc_customer as oc join oc_store as os on os.store_id=oc.store_id where "
                    . "oc.telephone not in (select telephone from oc_order_leads) "
                    . "and "
                    . "oc.telephone not in (select telephone from oc_order) and oc.call_status!='1' "
                    ." ) as aa";
                //echo $sql;
            $query=$this->db->query($sql);
            return $query->row["total"]; 
        }
        
        
        
      public function SubmitRechargeCallData($data)
        {
        $sql="insert into `call_trans_history` set `from`='".$data["current_call_status"]."',`to`='".$data["call_status"]."',`order_id`='".$data["order_id"]."',`mobile_number`='".$data["mobile"]."',`datetime`='".$data["datetime"]."' ,`current_order_status`='".$data["current_order_status"]."',`logged_user_data`='".$data["logged_user_data"]."' ";
        $log=new Log("call_recharge_trans_history-".date('Y-m-d').".log");
        $log->write($sql);
        //echo "<br/>";
        $trans_query = $this->db->query($sql);
    $transs_id=$this->db->getLastId();
        
        $update_call_status_sql="update `oc_recharge_transactions` set `call_status`='".$data["call_status"]."' where `order_id`='".$data["order_id"]."' and `sid`='".$data["rtransid"]."' ";
        //echo "<br/>";
        
        $call_status_query = $this->db->query($update_call_status_sql);
        $log->write($update_call_status_sql);
        $get_customer_query = $this->db->query("SELECT customer_id FROM oc_customer where `telephone`='".$data["mobile"]."' limit 1 ");
        $customer_id=$get_customer_query->row["customer_id"];
        
        if($data["call_status"]=="27")
        {
        
        $get_customer_address = $this->db->query("SELECT firstname FROM oc_address where `customer_id`='".$customer_id."' limit 1 ");
        if ($get_customer_address->num_rows) {
          
          $sql_c_u="update `oc_address` set `firstname`='".$data["farmer_first_name"]."',`lastname`='".$data["farmer_last_name"]."' where `customer_id`='".$customer_id."' ";
          $update_cust_address_query = $this->db->query($sql_c_u);
          $log->write($sql_c_u);

        }
        else
        {
             $sqlc_i="insert into  `oc_address` set `firstname`='".$this->db->escape($data["farmer_first_name"])."',`lastname`='".$this->db->escape($data["farmer_last_name"])."',`company`='Unnati',`customer_id`='".$this->db->escape($customer_id)."' ";
             $log->write($sqlc_i);
             $insert_cust_address_query = $this->db->query($sqlc_i);
        }
        $sql_r="insert into  `ccare_recharge_feedback` set `firstname`='".$this->db->escape($data["farmer_first_name"])."',`lastname`='".$this->db->escape($data["farmer_last_name"])."',`remarks`='".$this->db->escape($data["remarks"])."',`customer_mobile`='".$data["mobile"]."' ,`trans_id`='".$transs_id."',`order_id`='".$data["order_id"]."',`r_tbl_id`='".$data["rtransid"]."'";
        $insert_response_query = $this->db->query($sql_r);
    $log->write($sql_r);
        }
        }


public function getRechargeCustomers($data=array())
        {
            $sql="SELECT ocrt.mobile as telephone,ocrt.order_id,ocrt.sid as transid,ocrt.recharge_amount,ocrt.call_status as call_status,oc_callstatus.STATUS_NAME as call_STATUS_NAME,concat(ocrtr.ResSerSts,ocrtr.ResErrMsg) as ResSerSts,oc_callstatus.STATUS_NAME,date(ocrt.create_date) as recharge_date,ocrtr.ResRocTransID,orp.scheme_name "
                    . "FROM `oc_recharge_transactions` as ocrt "
                    . "left join oc_recharge_transactions_rocket as ocrtr on ocrt.rocket_tbl_id=ocrtr.sid "
                    . "left join oc_callstatus on oc_callstatus.STATUS_ID=ocrt.call_status left join oc_recharge_products orp on orp.scheme_id=ocrt.scheme_id";

                $sql.=" order by ocrt.sid desc  ";
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
            $query=$this->db->query($sql);
            return $query->rows;
        }
          public function getTotalRechargeCustomers($data=array())
        {
             $sql="select count(*) as total from(SELECT ocrt.mobile as telephone,ocrt.order_id,ocrt.recharge_amount,ocrt.call_status as call_status,ocrtr.ResSerSts,oc_callstatus.STATUS_NAME,date(ocrt.create_date) as recharge_date "
                    . "FROM `oc_recharge_transactions` as ocrt "
                    . "left join oc_recharge_transactions_rocket as ocrtr on ocrt.rocket_tbl_id=ocrtr.sid "
                    . "left join oc_callstatus on oc_callstatus.STATUS_ID=ocrt.call_status)as aa";
                //echo $sql;
            $query=$this->db->query($sql);
            return $query->row["total"];
        }  
        
         public function getOrder_recharge($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_leads WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

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

			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
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
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
                                'date_potential'              => $order_query->row['date_potential'],
				'date_modified'           => $order_query->row['date_modified'],
                                'call_status'             => $order_query->row['call_status']
			);
		} else {
			return;
		}
	} 
        
        public function getOrderProducts_recharge($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	

	public function getOrderOptions_recharge($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderVouchers_for_recharge($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	

	public function getOrderTotals_recharge($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

        
        
        
        
        
        
        
        
        
        
        
        
        
        
///////////////////////////////////////////////////////////////////////////////


	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product_leads WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderOption($order_id, $order_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option_leads WHERE order_id = '" . (int)$order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");

		return $query->row;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option_leads WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

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
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total_leads WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getorderTotalvalue($oid)
	{
		//
			$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total_leads` ot LEFT JOIN `" . DB_PREFIX . "order_leads` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('total') AND o.order_id='".$oid."' ");
			return $query->row["total"];
		//		

	}	

	public function getorderTaxvalue($oid)
	{
		//
			$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total_leads` ot LEFT JOIN `" . DB_PREFIX . "order_leads` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('tax') AND o.order_id='".$oid."' ");
			return $query->row["total"];
		//		

	}

	public function getorderSubTotalvalue($oid)
	{
		//
			$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total_leads` ot LEFT JOIN `" . DB_PREFIX . "order_leads` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_id='".$oid."' ");
			return $query->row["total"];
		//		

	}


	
	public function getTotalOrdersByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_leads` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_leads` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByProcessingStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_processing_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_leads` WHERE " . implode(" OR ", $implode) . "");

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
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_leads` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_leads` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_leads` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order_leads` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "order_leads` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history_leads oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history_leads WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history_leads WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}

	public function getEmailsByProductsOrdered($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order_leads` o LEFT JOIN " . DB_PREFIX . "order_product_leads op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}

	public function getbill_to_requisition($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "requisition_to_bill WHERE bill_id = '" . (int)$order_id . "'");
		return $query->row['requisition_id'];
	}

	public function getTotalEmailsByProductsOrdered($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order_leads` o LEFT JOIN " . DB_PREFIX . "order_product_leads op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");

		return $query->row['total'];
	}
}