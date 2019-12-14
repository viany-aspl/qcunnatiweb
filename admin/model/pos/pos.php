<?php

class ModelPosPos extends Model {
	
	/*
	 * POS database table definition
	 * 
	 */
	
	// This function is how POS module creates it's tables to store order payment entries. You would call this function in your controller in a
	// function called install(). The install() function is called automatically by OC versions 1.4.9.x, and maybe 1.4.8.x when a module is
	// installed in admin.
	public function update_billing_status_excel($INDENT_NO,$order_id='') 
	{            
		$log=new Log("Requisition-web-excel-".date('Y-m-d').".log");
	    $sql = "update  " . DB_PREFIX . "indent_excel set billing_status = '1', invoice_no='".$order_id."' where INDENT_NO='".$INDENT_NO."' ";
		$log->write($sql);
		$log->write($this->db->query($sql));
	}
	public function insert_cash_order_trans($order_id,$store_id,$request_data,$response,$vcode='') 
	{            
			$log=new Log("cash_order_trans-".date('Y-m-d').".log");
	        $sql = "insert into  " . DB_PREFIX . "bcml_cash_order_trans set order_id = '".$order_id."',vcode = '".$vcode."',store_id = '".$store_id."',request_data = '".serialize($request_data)."',response = '".serialize($response)."' ";
			$log->write($sql);
			$log->write($this->db->query($sql));
	}
	public function confirm_coupon($order_info, $code) {
        $log=new Log("order-coupon".date('Y-m-d').".log");
        //$code = '';
	$log->write('confirm_coupon is called');
	$log->write($order_info);
        //$start = strpos($order_total['title'], '(') + 1;
        //$end = strrpos($order_total['title'], ')');

        //if ($start && $end) {
        //    $code = substr($order_total['title'], $start, $end - $start);
        //}

        $this->load->model('checkout/coupon');

        $coupon_info = $this->model_checkout_coupon->getCoupon($code);
        $log->write($coupon_info);
        if ($coupon_info) {
            $sql="INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_id = '" . (int)$coupon_info['coupon_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', customer_id = '" . (int)$order_info['customer_id'] . "', amount = '" .$order_info['order_total'] [1]['value']. "', date_added = NOW()";
            $log->write($sql);
            $this->db->query($sql);
        }
    }

public function UpdateOrderTagged($orderid,$taggedvalue) 
	 {            
				$log=new Log("UpdateRequisition-".date('Y-m-d').".log");
	            $sql = "update " . DB_PREFIX . "order set bcml_tagged = '".$taggedvalue."' where order_id = '".$orderid."'";
				$log->write($sql);
		$log->write($this->db->query($sql));
	}

    public function get_order_total($order_id,$code)
    {
        $sql="select value from oc_order_total where `order_id`='".$order_id."' and `code`='".$code."' limit 1 ";
        $log=new Log("order-".date('Y-m-d').".log");
        $log->write($sql);
        $query=$this->db->query($sql);
        $log->write($query->row['value']);
        return $query->row['value'];
    }


 

	public function createModuleTables() 
	{
           
            $query  = "ALTER TABLE `" . DB_PREFIX . "order` ADD `card_no` TINYINT( 4 ) NULL AFTER `payment_code`;";
            $query .= "ALTER TABLE `" . DB_PREFIX . "order` ADD `user_id` INT( 100 ) NULL AFTER `customer_id`;";
            $query .= "ALTER TABLE `" . DB_PREFIX . "user` ADD `cash` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' AFTER `ip` ,
            ADD `card` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' AFTER `cash` ;";

            $query .= "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pos_withdraw` (
              `pos_withdraw_id` int(100) NOT NULL AUTO_INCREMENT,
              `user_id` int(100) NOT NULL,
              `amount` decimal(10,2) NOT NULL,
              `date` datetime NOT NULL,
              PRIMARY KEY (`pos_withdraw_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

            /*
            $query .= "INSERT INTO '" . DB_PREFIX . "user_group' values('','point of sale','a:2:{s:6:\"access\";a:2:{i:0;s:10:\"module/pos\";i:1;s:10:\"sale/order\";}s:6:\"modify\";a:2:{i:0;s:10:\"module/pos\";i:1;s:10:\"sale/order\";}}')";

            $this->db->query($query);

            $user_group_id = $this->db->getLastId();

            //add setting data
            $this->db->query("DELETE '" . DB_PREFIX . "setting' WHERE key= 'pos_user_group_id'");
            $this->db->query("INSERT INTO '" . DB_PREFIX . "setting' values('','0','POS',pos_user_group_id','".$user_group_id."',0)");
            */
	}

	public function deleteModuleTables() {
		// $query = $this->db->query("DROP TABLE " . DB_PREFIX . "order_payment");
	}

	public function addPayment($data) 
    {
		if(!empty($data['store_id']))
		{
            $store=$data['store_id'];
		}   
		else
		{
			$store=0;  
		}       
        if(($data['payment_method']=="Cash") || ($data['payment_method']=="Subsidy") )
		{
                 $log=new Log("addpayment-".date('Y-m-d').".log");
				 $log->write('');
				 $log->write($data['order_id']);
				 $log->write($data['payment_method']);
				 $log->write($data['cash']);
                 $sql = "update " . DB_PREFIX . "user set cash = cash + ".$data['cash'].", card= card + ".$data['card']." where user_id = '".$data['user_id']."'";
                 if(!empty($data['cash']))
				 {
					$this->db->query($sql);
					$log->write($sql);
				 }
				 else
				 {
					  $log->write('cash is empty ');
				 }
                
                 //$sql2 = "insert into oc_store_cash_trans set amount =  ".$data['cash'].", store_id =  ".$store.",user_id = ".$data['user_id'].",tr_type='CR',`order_id`='".$data["order_id"]."',`payment_method`='".$data["payment_method"]."' ";
                 //$log->write($sql2);
                 //$this->db->query($sql2);  
							try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($data['cash'],$store,$data['user_id'],'CR',$data["order_id"],$data['payment_method'],$data['total'],'','5');  
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage()); 
                               }
        }
		else
		{
					
							try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretranslog($data['cash'],$store,$data['user_id'],'CR',$data["order_id"],$data['payment_method'],$data['total'],'','0');  
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage()); 
                               }
		}
	}
	public function addPayment_complete($data) 
              {
				 $log=new Log("addpayment-complete-".date('Y-m-d').".log");
				 $log->write($data); 
				if(!empty($data['store_id']))
				{
                   $store=$data['store_id'];
				}   
				else
				{
					$store=0;  
				}       
               if(($data['payment_method']=="Tagged Cash") || ($data['payment_method']=="Tagged Cash Susidy") || ($data['payment_method']=="Cash Susidy"))
				{
                 
                 $sql = "update " . DB_PREFIX . "user set cash = cash + ".$data['cash']." where user_id = '".$data['user_id']."'";
                 $this->db->query($sql);
                 $log->write($sql);
                 //$sql2 = "insert into oc_store_cash_trans set amount =  ".$data['cash'].", store_id =  ".$store.",user_id = ".$data['user_id'].",tr_type='CR',`order_id`='".$data["order_id"]."',`payment_method`='".$data["payment_method"]."' ";
                 //$log->write($sql2);
                 //$this->db->query($sql2);  
			try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($data['cash'],$store,$data['user_id'],'CR',$data["order_id"],$data['payment_method'],$data['total'],'',$data['order_status_id']);  
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage()); 
                               }
                }
	}
        public function editPayment($order_id,$data){
            $query = $this->db->query("select user_id, total, payment_method from " . DB_PREFIX . "order where order_id = '".$order_id."'");
            $row = $query->row;
            
            $cash = $card = 0;
            
            if($row['total'] > $data['total'] || $data['payment_method'] != 'Card'){
                $cash = $data['total'] - $row['total'];
            }elseif($data['payment_method'] == 'Card'){
                $card = $data['total'] - $row['total'];
            }
            
            $sql = "update " . DB_PREFIX . "user set cash = cash + ".$cash.", card= card + ".$card." where user_id = ".$data['user_id'];
            $this->db->query($sql);
        }

	public function UpdateOrderStatusTemp($orderid,$id) 
	 {            
				$log=new Log("paycode-".date('Y-m-d').".log");
	            $sql = "update " . DB_PREFIX . "order_temp set order_status_id = '".$id."' where order_id = '".$orderid."'";
				$log->write($sql);
		$log->write($this->db->query($sql));
	}

	public function UpdateOrderStatusLeads($orderid,$id) 
	 {            
	            $log=new Log("dsclpaycode-".date('Y-m-d').".log");
	            $sql = "update " . DB_PREFIX . "order_leads set order_status_id = '".$id."' where order_id = '".$orderid."'";
				$log->write($sql);
		$log->write($this->db->query($sql));
	}
	public function RequisitionToBill($orderid,$billid) 
	 {            
		  
                               $log2=new Log("reconciliation-to-bill-".date('Y-m-d').".log");

		   $sql1 = "select count(*) as total from " . DB_PREFIX . "requisition_to_bill where requisition_id = '".$orderid."'  limit 1";
		   //and bill_id='".$billid."'
	           $query = $this->db->query($sql1);

		   $total= $query->row['total'];
		   if($total>0)
		   {
                   
                        $log2->write('there is already a record for the same ->'.$orderid.",".$billid);
                   }
                   else
                   {
	            $sql = "insert into " . DB_PREFIX . "requisition_to_bill set requisition_id = '".$orderid."',bill_id='".$billid."'";
		
                            $log2->write($sql);
		$log2->write($this->db->query($sql));
		   }

		 //order status update
		$sqlord = "update " . DB_PREFIX . "order set order_status_id = '5',date_added=NOW() where order_id = '".$billid."'"; 
		$log2->write($sqlord);
		$log2->write($this->db->query($sqlord));
		//end 

		$sql2 = "insert into " . DB_PREFIX . "requisition_to_bill_trans set requisition_id = '".$orderid."',bill_id='".$billid."'";
		$log2->write($sql2);
                            
		$this->db->query($sql2);

	}


/*
	public function RequisitionToBill($orderid,$billid) 
	 {            
				$log=new Log("dsclpaycode.log");
	            $sql = "insert into " . DB_PREFIX . "requisition_to_bill set requisition_id = '".$orderid."',bill_id='".$billid."'";
				$log->write($sql);
		$log->write($this->db->query($sql));
	}
*/
    public function adminmodel($model) {
      
      $admin_dir = DIR_SYSTEM;
      $admin_dir = str_replace('system/','admin/',$admin_dir);
      $file = $admin_dir . 'model/' . $model . '.php';      
      //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
      $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
      
      if (file_exists($file)) {
         include_once($file);
         
         $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
      } else {
         trigger_error('Error: Could not load model ' . $model . '!');
         exit();               
      }
   }
public function updateinventory($data)
{
    $log=new Log('updateinventory-'.date('Y-m-d').'.log');
    //quantity  to update in store
	$log->write('updateinventory called from : '.$data['web_app']);
    $getsql="select * from oc_order_product WHERE order_id = '" . (int)$data['order_id'] . "' ";
    $log->write($getsql);
    $getsqlres=$this->db->query($getsql);
    $product_rows=$getsqlres->rows;

    if (!empty($product_rows)) 
	{		
      	foreach ($product_rows as $order_product) 
		{	
			$getsqltrans="select * from oc_product_trans WHERE order_id = '" . (int)$data['order_id'] . "' and product_id='".(int)$order_product['product_id']."' ";
			$log->write($getsqltrans);
			$getsqltransres=$this->db->query($getsqltrans);
			if($getsqltransres->num_rows==0) 
			{
			//quantity  to update in store
                        $sqlpdeduct="UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'";
						$log->write($sqlpdeduct);
						$this->db->query($sqlpdeduct);

                       $sqlpdeduct2="UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND store_id = '".(int)$data['store_id']."'";
						$log->write($sqlpdeduct2);
						$this->db->query($sqlpdeduct2);
			
						//update quantity
						if(!empty($data['store_type']) && $data['store_type']=="8"){
						$sqlpdeduct3="UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND store_id = '".(int)$data['store_id']."' AND user_id='".(int)$data['username']."'";
						$log->write($sqlpdeduct3);
						$this->db->query($sqlpdeduct3);}

			try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addproducttrans($data['store_id'],$order_product['product_id'],$order_product['quantity'],$data['order_id'],'DB','SALE',$data['web_app']);  
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
			}
			else
			{
				$log->write('Inventory already updated for this order -'.$data['order_id']);
			}
        }
    }
}
        
	public function addOrder($data) 
	{


		$log=new Log("quantity-".date('Y-m-d').".log");
		$log->write($data);
		$this->adminmodel('setting/store');
		$log->write("seeting load success");
		$store_info = $this->model_setting_store->getStore($data['store_id']);
		$log->write("setting load store");

		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;
		}
		$log->write("seeting load store 1");
		$this->adminmodel('setting/setting');
		$log->write("seeting load store 2");
		$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
			
		if (isset($setting_info['invoice_prefix'])) {
			$invoice_prefix = $setting_info['invoice_prefix'];
		} else {
			$invoice_prefix = $this->config->get('config_invoice_prefix');
		}
		
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	
					
		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}	

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
		
		if ($currency_info) {
			$currency_id = $currency_info['currency_id'];
			$currency_code = $currency_info['code'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id = 0;
			$currency_code = $this->config->get('config_currency');
			$currency_value = 1.00000;			
		}

		$billat=1;
		if($data['utype']==36)
		{
			$billat=2;	
		}      	
		if(empty($data['subsidy_coupon']))
		{
			$data['subsidy_coupon']=0;	
		}
		if(empty($data['subsidy_form_no']))
		{
			$data['subsidy_form_no']=0;	
		}
				$orderer_query="INSERT INTO `" . DB_PREFIX . "order` SET subsidy_form_no='".$data['subsidy_form_no']."',subsidy_coupon='".$data['subsidy_coupon']."',billed_at='".$billat."',MPIN='".$data['MPIN']."',card_no ='" . $this->db->escape($data['card_no']) . "',  invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "',store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', subsidy_category = '" . $this->db->escape($data['subsidy_cat_id']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW()";
                $log->write($orderer_query);
				$this->db->query($orderer_query);
			
                $order_id = $this->db->getLastId();
				$log->write('after insert order_id-'.$order_id);
                if (isset($data['order_product'])) 
				{		
					foreach ($data['order_product'] as $order_product) 
					{	
						
						if(!empty($data['subsidy_cat_id'])) 
						{
							$sqls = "SELECT oc_product_subsidy.*,oc_category_subsidy.category_name as category_name FROM " . DB_PREFIX . "product_subsidy left join oc_category_subsidy on oc_category_subsidy.category_id=oc_product_subsidy.category_id where oc_product_subsidy.product_id='".(int)$order_product['product_id']."' and oc_product_subsidy.store_id='".(int)$data['store_id']."' and oc_product_subsidy.category_id='".(int)$data['subsidy_cat_id']."' ";

							$querys = $this->db->query($sqls);
							
							$discount_type=$querys->row['category_name'].'-'.$querys->row['category_id'];
							$discount_value=$querys->row['subsidy'];
						}
						//$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', tagged_quantity = '" . (float)($order_product['quantity'] *$data['TAGGEDRATIO']). "',tagged_cash_ratio='".$data['TAGGEDRATIO']."', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
						$prd_queryy="INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', 
						product_id = '" . (int)$order_product['product_id'] . "',
						name = '" . $this->db->escape($order_product['name']) . "', 
						model = '" . $this->db->escape($order_product['model']) . "', 
						quantity = '" . (int)$order_product['quantity'] . "', 
						tagged_quantity = '" . (float)($order_product['quantity'] *$data['TAGGEDRATIO']). "',
						tagged_cash_ratio='".$data['TAGGEDRATIO']."', 
						price = '" . (float)$order_product['price'] . "', 
						total = '" . (float)$order_product['total'] . "', 
						tax = '" . (float)$order_product['tax'] . "', 
						reward = '" . (int)$order_product['reward'] . "',
						discount_type = '" . $discount_type . "',
						discount_value = '" . $discount_value . "',
						ActAmount = '" . (float)$order_product['ActAmount'] . "',
						ActRate = '" . (float)$order_product['ActRate'] . "',
						SubSidyPer = '" . (float)$order_product['SubSidyPer'] . "',
						SubsidyAmount = '" . (float)$order_product['SubsidyAmount'] . "',
						BCMLCODE = '" . $order_product['BCMLCODE'] . "',
						SUBSIDY_CAT = '" .$order_product['S_CODE'] . "',
						SUBSIDY_CAT_DESC = '" .$order_product['S_DESC'] . "',
						SubRate = '" . (float)$order_product['SubRate'] . "' ";
						$log->write($prd_queryy);
						$this->db->query($prd_queryy);
						$order_product_id = $this->db->getLastId();

				//quantity  to update in circle fm
		if(isset($data["stock_fm"])&&(!empty($data["stock_fm"])))
		{

		//$log->write("insert " . DB_PREFIX . "contractor_product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND store_id = '".(int)$data['store_id']."' and `contractor_id`='".$data["stock_fm"]."' ");

		//$this->db->query("UPDATE " . DB_PREFIX . "contractor_product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND store_id = '".(int)$data['store_id']."'  and `contractor_id`='".$data["stock_fm"]."'");
			$log->write("INSERT INTO oc_contractor_product (product_id,name,quantity,order_id,store_id,store_name,price,tax,contractor_id)	VALUES(".(int)$order_product['product_id'].",'".$order_product['name']. "'," . (int)$order_product['quantity'].",".(int)$order_id.",'".(int)$data['store_id']."','0','".(float)$order_product['price']."','".(float)$order_product['tax']."','".$data["stock_fm"]."') ON DUPLICATE KEY UPDATE quantity=quantity-".(int)$order_product['quantity']);

	               $this->db->query("INSERT INTO oc_contractor_product (product_id,name,quantity,order_id,store_id,store_name,price,tax,contractor_id)	VALUES(".(int)$order_product['product_id'].",'".$order_product['name']. "'," . (int)$order_product['quantity'].",".(int)$order_id.",'".(int)$data['store_id']."','0','".(float)$order_product['price']."','".(float)$order_product['tax']."','".$data["stock_fm"]."') ON DUPLICATE KEY UPDATE quantity=quantity-".(int)$order_product['quantity']);

		$log->write("INSERT INTO oc_contractor_product_trans (product_id,name,quantity,order_id,store_id,store_name,price,tax,contractor_id,cr_dr,transaction_type)	VALUES(".(int)$order_product['product_id'].",'".$order_product['name']. "'," . (int)$order_product['quantity'].",".(int)$order_id.",'".(int)$data['store_id']."','0','".(float)$order_product['price']."','".(float)$order_product['tax']."','".$data["stock_fm"]."','dr','". $this->db->escape($data['payment_method']) ."')");


		 $this->db->query("INSERT INTO oc_contractor_product_trans (product_id,name,quantity,order_id,store_id,store_name,price,tax,contractor_id,cr_dr,transaction_type)	VALUES(".(int)$order_product['product_id'].",'".$order_product['name']. "'," . (int)$order_product['quantity'].",".(int)$order_id.",'".(int)$data['store_id']."','0','".(float)$order_product['price']."','".(float)$order_product['tax']."','".$data["stock_fm"]."','dr','". $this->db->escape($data['payment_method']) ."')");

	
		}
		else
		{

	if(($data['payment_method']=='Tagged') || ($data['payment_method']=='Tagged Cash')|| ($data['payment_method']=='Tagged Subsidy')|| ($data['payment_method']=='Cash Subsidy'))
                   {
			$log->write('payment method is '.$data['payment_method'].' so we did not deduct the quantiy'); 	
                    }
                   else
                    {
                       
                    //quantity  to update in store
                        $sqlpdeduct="UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'";
			$log->write($sqlpdeduct);
				$this->db->query($sqlpdeduct);
                                $sqlpdeduct2="UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND store_id = '".(int)$data['store_id']."'";
			$log->write($sqlpdeduct2);

				$this->db->query($sqlpdeduct2);

		try{ 
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addproducttrans($data['store_id'],$order_product['product_id'],$order_product['quantity'],$order_id,'DB','SALE');  
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
                       
                    }

		}
				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
						
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}				
			}
		}
		
		if (isset($data['order_voucher'])) {	
			foreach ($data['order_voucher'] as $order_voucher) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', voucher_id = '" . (int)$order_voucher['voucher_id'] . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . (float)$order_voucher['amount'] . "'");
			
      			$this->db->query("UPDATE " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
			}
		}

		// Get the total
		$total = 0;
		$log->write($data['order_total']);
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "',  `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		if (!empty($this->request->post['affiliate_id'])) {
			$affiliate_id = (int)$this->request->post['affiliate_id'];
		}
		
		if ($affiliate_id > 0 ) {
			$this->load->model('sale/affiliate');
			
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
			
			if ($affiliate_info) {
				$commission = ($total / 100) * $affiliate_info['commission']; 
			}
		}
		if($data['payment_method']=='Cheque')
		{
		 	$sqlbank = "insert into " . DB_PREFIX . "order_by_cheque set order_id = '".(int)$order_id."',cheque_num='".$data['chenum']."',cheque_micr='".$data['chemic']."',cheque_bank='".$data['chebnk']."',	cheque_account_name='".$data['cheacc']."',cheque_account_num='".$data['cheaccno']."'";
			$this->db->query($sqlbank);
			$log->write($sqlbank);
		}
		
		$sqlappend="";
		//check as per type of pos
		if($data['payment_method']=='Cheque')
		{
			$sqlappend=",cheque='".(float)$total."'";	
		}
		if($data['payment_method']=='Cash')
		{
			$sqlappend=",cash='".(float)$total."'";		
			$sqlappend.=",unit_id='".$data['unitid']."'";  	
			$sqlappend.=",fmcode='".$data['fm_code']."'";			
		}
		if($data['payment_method']=='Tagged Cash' && isset($data['amtcash']) && (!empty($data['amtcash'])))
		{
			$sqlappend=",tagged='".((float)$total-(float)$data['amtcash'])."',cash='".(float)$data['amtcash']."'";			
		}
		else if($data['payment_method']=='Tagged Cash Subsidy')
		{
			$log->write("in Tagged Cash Subsidy payment_method");
			$temp_tagged=((float)$total-(float)$data['amtcash']-(float)$data['sub']);
			$sqlappend=",tagged='".$temp_tagged."',cash='".(float)$data['amtcash']."',subsidy='".((float)$data['sub'])."'";			
		}
		else if($data['payment_method']=='Tagged')
		{
			$sqlappend=",tagged='".(float)$total."'";			
		}
		
		if($data['payment_method']=='Subsidy')
		{
			$sqlappend=",cash='".(float)$data['subsidy']."',subsidy='".((float)$data['sub'])."' ";			
		}
		
		if($data['payment_method']=='Tagged Subsidy')
		{
			$data['sub']=abs($data['sub']);
			$sqlappend=",tagged='".((float)$total-(float)$data['sub'])."',subsidy='".((float)$data['sub'])."' ";			
		}
		if($data['payment_method']=='Cash Subsidy')
		{
			$data['sub']=abs($data['sub']);
			$sqlappend=",cash='".((float)$total-(float)$data['sub'])."',subsidy='".((float)$data['sub'])."' ";			
		}
		
		//add for card
		
			if(!empty($data['grower_id'])){
			$data["qrstr"]="AC=01 UN=".$data['CARD_UNIT']." CI=11 GC=".$data['grower_id']." CSN=".$data['Card_Serial_Number']."";
		$sqlappend.=",grower_id='".$data['grower_id']."',card_serial_no='".$data['Card_Serial_Number']."',unit_id='".$data['CARD_UNIT']."',qr_string='".$data['qrstr']."' ";
		}
			
		$log->write($sqlappend);

		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET user_id = '".$this->user->getId()."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' ".$sqlappend." WHERE order_id = '" . (int)$order_id . "'"); 	
		$log->write('before prdsubsidy ');
		if (!empty($data['order_product_subsidy'])) 
		{		
			$log->write('in if prdsubsidy not empty ');
      		foreach ($data['order_product_subsidy'] as $order_product) 
			{
				$psubsql="UPDATE oc_order_product SET  reward = '" .$order_product['reward'] . "',discount_type = '" .$order_product['discount_type'] . "',discount_value = '" .$order_product['discount_value'] . "'  where order_id = '" . $order_id . "' and product_id = '" . $order_product['product_id'] . "'";	
				$log->write($psubsql);
      				$this->db->query($psubsql);
			}			
		}

		return $order_id;
	}
        
	public function editOrder($order_id, $data) {
		$this->load->model('localisation/country');

		$this->load->model('localisation/zone');

		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);

		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	

		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);

		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);

		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}

		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);

		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}			

		// Restock products before subtracting the stock later on
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach($product_query->rows as $product) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

				$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',  shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"); 
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");

		if (isset($data['order_product'])) {
			foreach ($data['order_product'] as $order_product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_product_id = '', order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");

				$order_product_id = $this->db->getLastId();

				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_option_id = '', order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");


						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'"); 

		if (isset($data['order_voucher'])) {	
			foreach ($data['order_voucher'] as $order_voucher) {	
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_voucher_id = '', order_id = '" . (int)$order_id . "', voucher_id = '" . (int)$order_voucher['voucher_id'] . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . (float)$order_voucher['amount'] . "'");

				$this->db->query("UPDATE " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
			}
		}

		// Get the total
                $total = 0;
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'"); 
                
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		if (!empty($this->request->post['affiliate_id'])) {
			$affiliate_id = (int)$this->request->post['affiliate_id'];
		}
		
		if ($affiliate_id > 0 ) {
			$this->load->model('sale/affiliate');
			
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
			
			if ($affiliate_info) {
				$commission = ($total / 100) * $affiliate_info['commission']; 
			}
		}
		
		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET user_id = '".$this->user->getId()."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'"); 	
		
		return $order_id;
	}

	// add for Browse begin
	public function getTopStoreCategories($sid) {
		// get all categories
		$log=new Log("category.log");
		$log->write("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id  LEFT JOIN `" . DB_PREFIX . "category_to_store` c2s ON c.category_id = c2s.category_id   WHERE c.parent_id = 0 and c2s.store_id='".$sid."' and cd.language_id = '". (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
		$query = $this->db->query("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id  LEFT JOIN `" . DB_PREFIX . "category_to_store` c2s ON c.category_id = c2s.category_id   WHERE c.parent_id = 0 and c2s.store_id='".$sid."' and cd.language_id = '". (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
		return $query->rows;
	}


	public function getTopCategories() {
		// get all categories
		$query = $this->db->query("SELECT c.image, c.category_id, c.parent_id, cd.name,cd.meta_hindi FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE c.parent_id = 0 and cd.language_id = '". (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
		return $query->rows;
	}
        
        public function getCategories() {
		// get all categories
		$query = $this->db->query("SELECT c.image, c.category_id, c.parent_id, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE cd.language_id = '". (int)$this->config->get('config_language_id') . "'");
		return $query->rows;
	}
        
	public function getSubCategories($category_id) {
		// get all sub categories under the given category
		$query = $this->db->query("SELECT c.category_id, c.image, cd.name FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON c.category_id = cd.category_id WHERE cd.language_id = '". (int)$this->config->get('config_language_id') . "' AND c.parent_id = '" . $category_id . "'");
		return $query->rows;
	}
        
        public function getProductByBarcode($barcode) {
		// get all products in the given category
		$query = $this->db->query("SELECT p.product_id, GROUP_CONCAT(po.option_id) as options from `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id WHERE p.isbn = '" . $barcode . "'");
		return $query->row;
	}
        
	public function total_products($category_id) {
            // get all products in the given category
            $query = $this->db->query("SELECT count(p.product_id) as total FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "'  AND p.status = '1' AND p.quantity > 0");
            $result = $query->row;
            return $result['total'];
	}
        
        public function getProducts($category_id, $limit = 20, $offset = 0) {
            // get all products in the given category
//"SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "'  AND p.status = '1' AND p.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit
   //         $query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id WHERE pd.language_id = '". '0' . "' AND pc.category_id = '" . $category_id . "'  AND p.status = '1' AND p.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);

	$query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options,ps.store_price FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id LEFT JOIN `" . DB_PREFIX . "product_to_store` ps on p.product_id=ps.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "' AND pc.category_id = '" . $category_id . "'  AND p.status = '1' AND ps.store_id='".$this->user->getStoreId()."' AND p.quantity > 0 AND ps.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);

            return $query->rows;
	}
        
        public function total_search_products($q){
            // get all products in the given category
            $query = $this->db->query("SELECT count( p.product_id ) AS total from `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "'  AND pd.name like '%".$q."%' AND p.status = '1' AND p.quantity > 0");
	    $result= $query->row;
            return $result['total'];
        }
        
        public function searchProducts($q, $limit = 20, $offset = 0){
            // get all products in the given category
            $query = $this->db->query("SELECT p.product_id, p.price, p.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options,ps.store_price FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id LEFT JOIN `" . DB_PREFIX . "product_to_store` ps on p.product_id=ps.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "'  AND pd.name like '%".$q."%' AND p.status = '1' AND ps.store_id='".$this->user->getStoreId()."' AND p.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);
	    return $query->rows;
        }


        public function searchProductsStore($q, $limit = 20, $offset = 0){
            // get all products in the given category
            $query = $this->db->query("SELECT p.product_id, p.price, ps.quantity, p.image, pd.name, GROUP_CONCAT(po.option_id) as options,ps.store_price,p.tax_class_id FROM `" . DB_PREFIX . "product_to_category` pc LEFT JOIN `" . DB_PREFIX . "product` p ON pc.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id LEFT JOIN `" . DB_PREFIX . "product_option` po ON p.product_id = po.product_id LEFT JOIN `" . DB_PREFIX . "product_to_store` ps on p.product_id=ps.product_id WHERE pd.language_id = '". (int)$this->config->get('config_language_id') . "'  AND pd.name like '%".$q."%' AND p.status = '1' AND ps.store_id='".$this->user->getStoreId()."' AND p.quantity > 0 AND ps.quantity > 0 GROUP BY p.product_id LIMIT ".$offset.", ".$limit);
	    return $query->rows;
        }



public function getCustomers($sid,$uid){
            //search customer by name 
            $sql="SELECT * FROM `" . DB_PREFIX . "customer`  WHERE store_id = '".$sid."' limit 20  ";
            $log=new Log("cust-".date('Y-m-d').".log");
            $log->write($sql); 
            $query = $this->db->query($sql);
            return $query->rows;
        }

        
        public function getCustomer($customer_id){
              //search customer by name 
	$log=new Log("cust-".date('Y-m-d').".log");
	$sql="SELECT * FROM `" . DB_PREFIX . "customer`  WHERE customer_id = '".$customer_id."' limit 5 ";
	$log->write($sql); 
              $query = $this->db->query($sql);
	return $query->row;
        }
        public function getCustomerByPhone($customer_id){
            //search customer by name 
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer`  WHERE telephone = '".$customer_id."'");
	    return $query->row;
        }
        public function searchAffiliate($q){
            //search affiliate by name             
            $query = $this->db->query("SELECT c.firstname, c.lastname, c.affiliate_id FROM `" . DB_PREFIX . "affiliate` c WHERE c.firstname like '%".$q."%' or c.lastname like '%".$q."%' or c.telephone like '%".$q."%'");                                    
	    return $query->rows;
        }
        
        public function searchCustomer($q){
            //search customer by name      
	$log=new Log("cust-".date('Y-m-d').".log");  
             $sql="SELECT c.firstname, c.lastname, c.customer_id FROM `" . DB_PREFIX . "customer` c WHERE c.firstname like '%".$q."%' or c.lastname like '%".$q."%' or c.telephone like '%".$q."%'  limit 10 ";                 
            $query = $this->db->query($sql);       
	$log->write($sql);              
	    return $query->rows;
        }
        
        public function getStatistics(){
            $query = $this->db->query("select user_id, username, firstname, lastname, cash, card from " . DB_PREFIX . "user");
            return $query->rows;
        }
        
        public function withdraw($data){
            //user_id, amount 
            //1) insert into oc_pos_withdraw 
            //2) cash = cash - amount on user 
            $this->db->query("insert into `" . DB_PREFIX . "pos_withdraw` set pos_withdraw_id = '', user_id ='".$data['user_id']."', amount= '".$data['amount']."', date = NOW()");
            $this->db->query("update `" . DB_PREFIX . "user` set cash = cash - ".$data['amount']." where user_id = '".$data['user_id']."'");
        }
        
        public function total_history($user_id){
            $query = $this->db->query("select count(*) as total from `" . DB_PREFIX . "pos_withdraw` pw left join `" . DB_PREFIX . "user` u on pw.user_id = u.user_id where pw.user_id='".$user_id."'");
            $row = $query->row;
            return $row['total'];
        }
        
        public function history($user_id, $limit = 10, $offset = 0){            
            $query = $this->db->query("select u.username, u.firstname, u.lastname, pw.* from `" . DB_PREFIX . "pos_withdraw` pw left join `" . DB_PREFIX . "user` u on pw.user_id = u.user_id where pw.user_id='".$user_id."' ORDER BY pw.date DESC LIMIT ".$offset.", ".$limit);
            return $query->rows;
        }
        
        public function hold_cart($data){
            $this->db->query("insert into `" . DB_PREFIX . "cart_holder` set cart_holder_id = '', user_id ='".$data['user_id']."', name= '".$data['name']."', cart = '".serialize($data['cart'])."', date_created = NOW()"); 
            return $this->db->getLastId();
        }
        
        public function get_hold_cart_list(){
            $query = $this->db->query('select cart_holder_id, name, date_created from `' . DB_PREFIX . 'cart_holder` where user_id = "'.$this->user->getId().'"'); 
            return $query->rows;
        }
        
        public function hold_cart_select($id){
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'cart_holder` WHERE cart_holder_id="'.$id.'"');
            return $query->row;
        }
        
        public function hold_cart_delete($id){
            $this->db->query('DELETE FROM `' . DB_PREFIX . 'cart_holder` WHERE cart_holder_id="'.$id.'"');
        }
        
        public function get_today_card($user_id){
            $query = $this->db->query('SELECT sum(total) as total FROM `' . DB_PREFIX . 'order` WHERE user_id="'.$user_id.'" AND payment_method="Card" AND DATE(date_added) = DATE(NOW())');
            $row = $query->row;
            return $row['total'];
        }
        
        public function get_today_cash($user_id){
            $query = $this->db->query('SELECT sum(total) as total FROM `' . DB_PREFIX . 'order` WHERE user_id="'.$user_id.'" AND payment_method="Cash" AND DATE(date_added) = DATE(NOW())');
            $row = $query->row;
            return $row['total'];
        }
        
        

//send order to sugar
public function sendsugar($data) 
	{

	$log=new Log("sugar.log");
	$log->write("save data sugar");		
	$log->write($data);
	$log->write("g=".$data['gcustomer_id']);
        //Data, connection, auth
        $dataFromTheForm =        '<date_potential>'.$data['date_potential'].'</date_potential>
        <date_modified>'.$data['date_potential'].'</date_modified>
        <date_added>'.$data['date_potential'].'</date_added>
        <order_status_id>'.$data['order_status_id'].'</order_status_id>
        <total>'.$data['total'].'</total>
        <payment_code>'.$data['payment_code'].'</payment_code>
        <payment_method>'.$data['payment_method'].'</payment_method>
        <telephone>'.$data['telephone'].'</telephone>
        <email>'.$data['email'].'</email>
        <lastname>'.$data['fname'].'</lastname>
        <firstname>'.$data['farmername'].'</firstname>
        <user_id>'.$data['user_id'].'</user_id>
        <card_no>'.$data['card_no'].'</card_no>
	<uid>'.$data['uid'].'</uid>
	<vid>'.$data['village_id'].'</vid>
	<village_name>'.$data['village_name'].'</village_name>
	<circle_code>'.$data['circle_code'].'</circle_code>
	<transid>'.$data['transid'].'</transid>
	<ename>'.$data['ename'].'</ename>
        <customer_id>'.$data['gcustomer_id'].'</customer_id>
        <customer_group_id>'.$data['customer_group_id'].'</customer_group_id>
        <store_url>'.$data['store_id'].'</store_url>
        <order_id>'.$data['oid'].'</order_id>
        <store_name>'.$data['store_name'].'</store_name>
        <store_id>'.$data['store_id'].'</store_id>'
; // request data from the form
$orddtl='<orddtl>';

                        foreach ($data['order_product'] as $result) {

$orddtl.='
        <orderdetail>
          <ORD_DATE>'.$data['date_potential'].'</ORD_DATE>
          <reward>0</reward>
          <order_product_id>0</order_product_id>
          <order_id>'.$data['oid'].'</order_id>
          <product_id>'.$result['product_id'].'</product_id>
          <name>'.$result['name'].'</name>
          <model>'.$result['model'].'</model>
          <quantity>'.$result['quantity'].'</quantity>
          <price>'.$result['price'].'</price>
          <total>'.$result['total'].'</total>
          <tax>'.$result['tax'].'</tax>
        </orderdetail>';
}
$orddtl.='</orddtl>';

        $soapUrl = "http://dsclsugar.com/akshamob/service.asmx?op=Requisition"; // asmx URL of WSDL
        $soapUser = "username";  //  username
        $soapPassword = "password"; // password

        // xml post structure

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <Requisition xmlns="http://aksha/app/"> 
                                  <oid>'.$dataFromTheForm.'</oid>'.$orddtl.' 
                                </Requisition>
                              </soap:Body>
                            </soap:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://aksha/app/Requisition", 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch); 
	    $log->write($response);	
            curl_close($ch);
            // converting
            $response1 = str_replace("<soap:Body>","",$response);
            $response2 = str_replace("</soap:Body>","",$response1);
		$log->write($response1);	
		$log->write($response2);	

            // convertingc to XML
            $parser = simplexml_load_string($response2);
            // user $parser to get your data out of XML response and to display it.


	}

//end data





//end

//emp
 public function addOrder_leads($data) {


        $log=new Log("dsclquantity-".date('Y-m-d').".log");
        $log->write($data);
        $this->adminmodel('setting/store');
        $log->write("adata");        
        $store_info = $this->model_setting_store->getStore($data['store_id']);
        
        if ($store_info) {
            $store_name = $store_info['name'];
            $store_url = $store_info['url'];
        } else {
            $store_name = $this->config->get('config_name');
            $store_url = HTTP_CATALOG;
        }
        
        $this->load->model('setting/setting');
        
        $setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
            
        if (isset($setting_info['invoice_prefix'])) {
            $invoice_prefix = $setting_info['invoice_prefix'];
        } else {
            $invoice_prefix = $this->config->get('config_invoice_prefix');
        }
        
        $this->load->model('localisation/country');
        
        $this->load->model('localisation/zone');
        
        $country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
        
        if ($country_info) {
            $shipping_country = $country_info['name'];
            $shipping_address_format = $country_info['address_format'];
        } else {
            $shipping_country = '';    
            $shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }    
        
        $zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
        
        if ($zone_info) {
            $shipping_zone = $zone_info['name'];
        } else {
            $shipping_zone = '';            
        }    
                    
        $country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
        
        if ($country_info) {
            $payment_country = $country_info['name'];
            $payment_address_format = $country_info['address_format'];            
        } else {
            $payment_country = '';    
            $payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';                    
        }
    
        $zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
        
        if ($zone_info) {
            $payment_zone = $zone_info['name'];
        } else {
            $payment_zone = '';            
        }    

        $this->load->model('localisation/currency');

        $currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
        
        if ($currency_info) {
            $currency_id = $currency_info['currency_id'];
            $currency_code = $currency_info['code'];
            $currency_value = $currency_info['value'];
        } else {
            $currency_id = 0;
            $currency_code = $this->config->get('config_currency');
            $currency_value = 1.00000;            
        }
                  $insert_q="INSERT INTO `" . DB_PREFIX . "order_leads` SET card_no ='" . $this->db->escape($data['card_no']) . "',  invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "',store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW(),date_potential='".$data['date_potential']."'";
                $this->db->query($insert_q);
          
                $order_id = $this->db->getLastId();

                if (isset($data['order_product'])) {        
              foreach ($data['order_product'] as $order_product) {    
                  $this->db->query("INSERT INTO " . DB_PREFIX . "order_product_leads SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
            
                $order_product_id = $this->db->getLastId();
                //quantity  to update in store
                      
                              
            }
        }
        


        // Get the total
        $total = 0;
        $log->write($data['order_total']);
        if (isset($data['order_total'])) {        
              foreach ($data['order_total'] as $order_total) {    
                  $this->db->query("INSERT INTO " . DB_PREFIX . "order_total_leads SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "',  `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
            }
            
            $total += $order_total['value'];
        }

        // Affiliate
        $affiliate_id = 0;
        $commission = 0;

                        $log->write("comm");
	$log->write("UPDATE `" . DB_PREFIX . "order_leads` SET user_id = '".$data['user_id']."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'");
                        $log->write("dcomm"); 
        // Update order total            
        $this->db->query("UPDATE `" . DB_PREFIX . "order_leads` SET user_id = '".$data['user_id']."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'");     
        
        return $order_id;
    }



public function addOrder_temp($data) {


		$log=new Log("quantity.log");
		$log->write($data);
		$this->adminmodel('setting/store');
		
		$store_info = $this->model_setting_store->getStore($data['store_id']);
		
		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;
		}
		
		$this->load->model('setting/setting');
		
		$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
			
		if (isset($setting_info['invoice_prefix'])) {
			$invoice_prefix = $setting_info['invoice_prefix'];
		} else {
			$invoice_prefix = $this->config->get('config_invoice_prefix');
		}
		
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	
					
		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}	

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
		
		if ($currency_info) {
			$currency_id = $currency_info['currency_id'];
			$currency_code = $currency_info['code'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id = 0;
			$currency_code = $this->config->get('config_currency');
			$currency_value = 1.00000;			
		}
      	        $insert_q="INSERT INTO `" . DB_PREFIX . "order_temp` SET card_no ='" . $this->db->escape($data['card_no']) . "',  invoice_prefix = '" . $this->db->escape($invoice_prefix) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "',store_url = '" . $this->db->escape($store_url) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', currency_id = '" . (int)$currency_id . "', currency_code = '" . $this->db->escape($currency_code) . "', currency_value = '" . (float)$currency_value . "', date_added = NOW(), date_modified = NOW()";
                $this->db->query($insert_q);
      	
                $order_id = $this->db->getLastId();

                if (isset($data['order_product'])) {		
      		foreach ($data['order_product'] as $order_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product_temp SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
			
				$order_product_id = $this->db->getLastId();				
				
			}
		}
		
		

		// Get the total
		$total = 0;
		$log->write($data['order_total']);
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total_temp SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "',  `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}
// Affiliate
        $affiliate_id = 0;
        $commission = 0;

		
		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order_temp` SET user_id = '".$this->user->getId()."', total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'"); 	
		
		return $order_id;
	}

//end emp


//paytm
//paytm function

	public function SendPayTM($data) 
	{


	$log=new Log('paytm.log');
	$log->write("paytm data");
	//start 
	//header("Pragma: no-cache");
	//header("Cache-Control: no-cache");
	//header("Expires: 0");
	$log->write($data);	
	// following files need to be included

	$checkSum = "";
	$paramList = array();
	$paramList = array();
	$paramList['request'] = array('merchantGuid' => 'a6876e0e-dea4-49b2-a631-503b65deb554',
       	'merchantOrderId' => $data['orderid'],     
        'totalAmount'=>$data['amount'],
        'posId'=>$data['store_id'],
        'industryType'=>'Retail',
        'comment'=>$data['username'],
        'currencyCode'=>'INR');
			         			
	$paramList['version'] = $data['version'];
	$paramList['channel'] = 'POS';
	$paramList['ipAddress'] = '120.138.8.16';
	$paramList['operationType'] = 'WITHDRAW_MONEY';
	$paramList['platformName'] = 'PayTM';

	$log->write($paramList);

	$data_string = json_encode($paramList); 
	//Here checksum string will return by getChecksumFromArray() function.
	$checkSum = $this->getChecksumFromString($data_string,"xBBm6NVzYTYCPWIh");
	$log->write($data_string);	
	$ch = curl_init();                    // initiate curl
	$url = "https://trust-uat.paytm.in/wallet-web/v7/withdraw"; // where you want to post data
	$headers = array('Content-Type:application/json','mid:a6876e0e-dea4-49b2-a631-503b65deb554','checksumhash:'.$checkSum,'phone:'.$data['customer_mob'],'otp:'.$data['totp']);
	$log->write($headers);	
	$ch = curl_init();  // initiate curl
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, 1);  // tell curl you want to post something
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string); // define what you want to post
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$output = curl_exec ($ch); // execute
	$info = curl_getinfo($ch);
	$log->write($info);
	$log->write("Output");
	$log->write($output);
	$data= json_decode($output, true); 	
	$log->write($data);
return $output;
//end
		}

function encrypt_e($input, $ky) {
	$log=new Log("payen.log");
	$log->write("in");
	$key = $ky;
	$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$log->write("in1");
	$input = $this->pkcs5_pad_e($input, $size);
	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	$iv = "@@@@&&&&####$$$$";
	mcrypt_generic_init($td, $key, $iv);
	$data = mcrypt_generic($td, $input);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$data = base64_encode($data);
	return $data;
}

function decrypt_e($crypt, $ky) {

	$crypt = base64_decode($crypt);
	$key = $ky;
	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	$iv = "@@@@&&&&####$$$$";
	mcrypt_generic_init($td, $key, $iv);
	$decrypted_data = mdecrypt_generic($td, $crypt);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$decrypted_data = pkcs5_unpad_e($decrypted_data);
	$decrypted_data = rtrim($decrypted_data);
	return $decrypted_data;
}

function pkcs5_pad_e($text, $blocksize) {
	$pad = $blocksize - (strlen($text) % $blocksize);
	return $text . str_repeat(chr($pad), $pad);
}

function pkcs5_unpad_e($text) {
	$pad = ord($text{strlen($text) - 1});
	if ($pad > strlen($text))
		return false;
	return substr($text, 0, -1 * $pad);
}

function generateSalt_e($length) {
	$random = "";
	srand((double) microtime() * 1000000);

	$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
	$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
	$data .= "0FGH45OP89";

	for ($i = 0; $i < $length; $i++) {
		$random .= substr($data, (rand() % (strlen($data))), 1);
	}

	return $random;
}

function checkString_e($value) {
	$myvalue = ltrim($value);
	$myvalue = rtrim($myvalue);
	if ($myvalue == 'null')
		$myvalue = '';
	return $myvalue;
}

function getChecksumFromArray($arrayList, $key, $sort=1) {
	if ($sort != 0) {
		ksort($arrayList);
	}
	$str = $this->getArray2Str($arrayList);
	$salt = $this->generateSalt_e(4);
	$finalString = $str . "|" . $salt;
	$hash = hash("sha256", $finalString);
	$hashString = $hash . $salt;
	$checksum = $this->encrypt_e($hashString, $key);
	return $checksum;
}
function getChecksumFromString($str, $key) {
	$log=new Log("payhash.log");
	$log->write("in");	
	$salt = $this->generateSalt_e(4);
	$log->write("in1");	
	$finalString = $str . "|" . $salt;
	$hash = hash("sha256", $finalString);
		$log->write("in2");
	$hashString = $hash . $salt;
	$checksum = $this->encrypt_e($hashString, $key);
		$log->write("out");
		$log->write($checksum);
	return $checksum;
}

function verifychecksum_e($arrayList, $key, $checksumvalue) {
	$arrayList =$this->removeCheckSumParam($arrayList);
	ksort($arrayList);
	$str = $this->getArray2Str($arrayList);
	$paytm_hash = $this->decrypt_e($checksumvalue, $key);
	$salt = substr($paytm_hash, -4);

	$finalString = $str . "|" . $salt;

	$website_hash = hash("sha256", $finalString);
	$website_hash .= $salt;

	$validFlag = "FALSE";
	if ($website_hash == $paytm_hash) {
		$validFlag = "TRUE";
	} else {
		$validFlag = "FALSE";
	}
	return $validFlag;
}

function verifychecksumFromstr_e($str, $key, $checksumvalue) {
	$paytm_hash = $this->decrypt_e($checksumvalue, $key);
	$salt = substr($paytm_hash, -4);

	$finalString = $str . "|" . $salt;

	$website_hash = hash("sha256", $finalString);
	$website_hash .= $salt;

	$validFlag = "FALSE";
	if ($website_hash == $paytm_hash) {
		$validFlag = "TRUE";
	} else {
		$validFlag = "FALSE";
	}
	return $validFlag;
}

function getArray2Str($arrayList) {
	$paramStr = "";
	$flag = 1;
	foreach ($arrayList as $key => $value) {
		if ($flag) {
			$paramStr .=$this->checkString_e($value);
			$flag = 0;
		} else {
			$paramStr .= "|" . $this->checkString_e($value);
		}
	}
	return $paramStr;
}

function redirect2PG($paramList, $key) {
	$hashString = $this->getchecksumFromArray($paramList);
	$checksum = $this->encrypt_e($hashString, $key);
}

function removeCheckSumParam($arrayList) {
	if (isset($arrayList["CHECKSUMHASH"])) {
		unset($arrayList["CHECKSUMHASH"]);
	}
	return $arrayList;
}

function getTxnStatus($requestParamList) {
	return $this->callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
}

function initiateTxnRefund($requestParamList) {
	$CHECKSUM = $this->getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY,0);
	$requestParamList["CHECKSUM"] = $CHECKSUM;
	return $this->callAPI(PAYTM_REFUND_URL, $requestParamList);
}

function callAPI($apiURL, $requestParamList) {
	$jsonResponse = "";
	$responseParamList = array();
	$JsonData =json_encode($requestParamList);
	$postData = 'JsonData='.urlencode($JsonData);
	$ch = curl_init($apiURL);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
	'Content-Type: application/json', 
	'Content-Length: ' . strlen($postData))                                                                       
	);  
	$jsonResponse = curl_exec($ch);   
	$responseParamList = json_decode($jsonResponse,true);
	return $responseParamList;
}


//end paytm 

public function getaseorderstatus($comment)
{
            $query = $this->db->query('SELECT order_id,date_added FROM `' . DB_PREFIX . 'order` WHERE `comment`="'.$comment.'" AND payment_method="Cash" ');
            return $query->row;
              
} 

public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$reward = 0;

			

			
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
			
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
		
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
		
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => unserialize($order_query->row['shipping_custom_field']),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
		
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
		
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'unit_id'=> $order_query->row['unit_id'],
				'subsidy_coupon'=> $order_query->row['subsidy_coupon'],
				'user_id'                => $order_query->row['user_id'],
				'cash'                => $order_query->row['cash']
			);
		} else {
			return;
		}
	}
	
public function getOrderPos($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "' and o.order_status_id='5' ");

		if ($order_query->num_rows) {
			$reward = 0;

			

			
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
			
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
		
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
		
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => unserialize($order_query->row['shipping_custom_field']),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
		
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
		
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return;
		}
	}
public function createInvoiceNo($order_id) {
		 $log=New log('invoice-'.date('Y-m-d').'.log');
                    $log->write('invoice generate hit-'.$order_id);
                    $order_info = $this->getOrder($order_id);
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

function getunitidandcompanyid($data)
{
$sql="SELECT os.store_id,osu.unit_id,os.company_id,oc.company_name,ou.unit_name FROM oc_store as os
JOIN oc_store_to_unit as osu on os.store_id=osu.store_id join oc_unit as ou on osu.unit_id=ou.unit_id
JOIN oc_company as oc on os.company_id=oc.company_id
where os.store_id='".$data."' limit 1";
$log=new Log("getunitidandcompanyid-".date('Y-m-d').".log");
$log->write($sql);
$query=$this->db->query($sql);
$log->write($query->row);
return $query->rows;
}
public function getProduct($product_id,$store_id) 
{
            //$qu="SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
  $log=new Log('getProduct-'.date('Y-m-d').'.log');
  $log->write('getProduct called in pos/pos/');
  //$sql="SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)  WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'  ";
  $sql="SELECT   case when sprice >0 then sprice else price end as price ,tax_class_id,stax from (
SELECT DISTINCT price,tax_class_id, (SELECT store_price FROM oc_product_to_store WHERE product_id='".(int)$product_id."' and store_id='".(int)$store_id."' ) AS sprice , (SELECT store_tax_amt FROM oc_product_to_store WHERE product_id='".(int)$product_id."' and store_id='".(int)$store_id."' ) AS stax FROM oc_product p LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id)
 WHERE p.product_id = '".(int)$product_id."' AND pd.language_id = '".(int)$this->config->get('config_language_id')."'  ) as a";
  $query = $this->db->query($sql);
  $log->write($sql);
  return $query->row;
 }
 public function insert_order_instance($instance_id,$store_id)
 {
  $log=new Log('order_istance-'.date('Y-m-d').'.log');
  $sql="insert into oc_order_instance set instance_id='".$instance_id."',store_id='".$store_id."' ON DUPLICATE KEY
    UPDATE instance_id='".$instance_id."' ";
  $query = $this->db->query($sql);
  $log->write($sql);
  
 }
 public function update_order_istance_order_id($instance_id,$order_id)
 {
  $log=new Log('order_istance-'.date('Y-m-d').'.log');
  $sql="update oc_order_instance set order_id='".$order_id."' where instance_id='".$instance_id."' ";
  $query = $this->db->query($sql);
  $log->write($sql);
 }
 public function check_order_instance($instance_id)
 {
  $log=new Log('order_istance-'.date('Y-m-d').'.log');
  $sql="select order_id from oc_order_instance where instance_id='".$instance_id."' ";
  $query = $this->db->query($sql);
  $log->write($sql);
  $rows=$query->row;
  if(count($rows)>0)
  {
  return $rows['order_id'];
  }
 }

//for bcml
	public function insert_indent_otp($indent_no,$otp,$tr_type='0') 
    {
        $log=New log('indent-otp-'.date('Y-m-d').'.log');
		$log->write('insert_indent_otp called');
		$indent_no=trim($indent_no);
        $log->write($indent_no.'-'.$otp.'-'.$tr_type);
		//and (invoice_no='' or  invoice_no is null)
        $sql1="select * from  oc_order_delivery  where indent_no = '". $this->db->escape($indent_no)."'  AND (create_time) >= CAST('" . ((date('Y')-1).'-12-31') . "' as DATETIME) ";      
		$query1=$this->db->query($sql1);
		$log->write($sql1);
		$log->write($query1->rows);
		if($query1->num_rows>0)
		{
			return $query1->row['sid'];
		}
		else
		{
		$sql="insert into oc_order_delivery  SET indent_no = '".$this->db->escape(trim($indent_no))."', otp = '". $this->db->escape($otp) ."', tr_type = '". $this->db->escape($tr_type) ."' ON DUPLICATE KEY UPDATE otp = '" . $this->db->escape($otp) . "' ";
                $log->write($sql);
                $this->db->query($sql);

                return $this->db->getLastId(); 
		}
                        
    }
        public function update_indent_deleviery($otp,$invoice_no,$delivery_status,$deliveryreceipt) 
        {
			$log=New log('update-indent-otp-'.date('Y-m-d').'.log');                
                $log->write($otp.'-'.$tr_type.'-'.$invoice_no.'-'.$delivery_status);
                
                $sql1="SELECT otp  FROM oc_order_delivery WHERE invoice_no = '" . $this->db->escape($invoice_no) . "' and tr_type='2' limit 1 ";
                $log->write($sql1);
        $query1 = $this->db->query($sql1);
                $log->write($query1);
                $log->write($query1->row['otp']);
		
		if($query1->row['otp']>0)
		{		
				
                if($query1->row['otp']==$otp)
                {
                    $sql="update oc_order_delivery  SET   otp_verified='1',otp_given='".$this->db->escape($otp)."',delivery_receipt_received='" . $this->db->escape($deliveryreceipt) . "',delivery_status = '" . $this->db->escape($delivery_status) . "' where invoice_no = '" . $this->db->escape($invoice_no). "'  and tr_type='2' ";
                     $log->write($sql);
					$this->db->query($sql);
                   
                    return '1';
                }
                else 
                {
				 $sql="update oc_order_delivery  SET   otp_verified='0',otp_given='".$this->db->escape($otp)."',delivery_receipt_received='" . $this->db->escape($deliveryreceipt) . "',delivery_status = '" . $this->db->escape($delivery_status) . "' where invoice_no = '" . $this->db->escape($invoice_no). "'  and tr_type='2' ";
                     $log->write($sql);
					$this->db->query($sql);
                   return '1';
                }
		}
		else{
$sql2="SELECT otp FROM oc_order_delivery_advance WHERE invoice_no = '" . $this->db->escape($invoice_no) . "' and tr_type='2' limit 1 ";
$log->write($sql2);
$query2 = $this->db->query($sql2); 
$log->write($query2);
$log->write($query2->row['otp']); 
if($query2->row['otp']==$otp)
{
$sql="update oc_order_delivery_advance SET otp_verified='1',otp_given='".$this->db->escape($otp)."',delivery_receipt_received='" . $this->db->escape($deliveryreceipt) . "',delivery_status = '" . $this->db->escape($delivery_status) . "' where invoice_no = '" . $this->db->escape($invoice_no). "' and tr_type='2' ";
$log->write($sql);
$this->db->query($sql);

return '1';
}
else 
{
$sql="update oc_order_delivery_advance SET otp_verified='0',otp_given='".$this->db->escape($otp)."',delivery_receipt_received='" . $this->db->escape($deliveryreceipt) . "',delivery_status = '" . $this->db->escape($delivery_status) . "' where invoice_no = '" . $this->db->escape($invoice_no). "' and tr_type='2' ";
$log->write($sql);
$this->db->query($sql);
return '1';
}
}
		
                        
    }

        public function update_indent_order_deleviery($indent_no,$otp,$tr_type,$invoice_no,$approvaltype,$deliveryreceipt,$fmcode,$fmname) 
        {
			$log=New log('indent-otp-'.date('Y-m-d').'.log');    
            $log->write('update_indent_order_deleviery');
			$indent_no=trim($indent_no);
                $log->write($indent_no.'-'.$otp.'-'.$tr_type.'-'.$invoice_no.'-'.$approvaltype);                                			
               
                    $sql="update oc_order_delivery  SET  fmname='".$this->db->escape($fmname)."',fmcode='".$this->db->escape($fmcode)."',tr_type = '" . $this->db->escape($tr_type) . "',delivery_receipt_given='" . $this->db->escape($deliveryreceipt) . "',invoice_no = '" . $this->db->escape($invoice_no) . "',delivery_status = '0',approvaltype='" . $this->db->escape($approvaltype) . "' where indent_no = '" . $this->db->escape($indent_no). "' ";
                    $sql .= " AND (create_time) >= CAST('" .((date('Y')-1).'-12-31') . "' as DATETIME) ";
					$log->write($sql);
					$this->db->query($sql);
                   
                    return '1';
                
             
                        
    }
//end 
 public function update_advance_order_deleviery($indent_no,$otp,$tr_type,$invoice_no,$approvaltype,$deliveryreceipt,$fmcode,$fmname) 
        {
			$log=New log('indent-otp-'.date('Y-m-d').'.log');
$log->write('update_advance_order_deleviery');		
$indent_no=trim($indent_no);	
                $log->write($indent_no.'-'.$otp.'-'.$tr_type.'-'.$invoice_no.'-'.$approvaltype);                                			
               
                    $sql="update oc_order_delivery_advance  SET  fmname='".$this->db->escape($fmname)."',fmcode='".$this->db->escape($fmcode)."',tr_type = '" . $this->db->escape($tr_type) . "',delivery_receipt_given='" . $this->db->escape($deliveryreceipt) . "',invoice_no = '" . $this->db->escape($invoice_no) . "',delivery_status = '0',approvaltype='" . $this->db->escape($approvaltype) . "' where indent_no = '" . $this->db->escape($indent_no). "' and date(create_time)='".date('Y-m-d')."' ";
                     $log->write($sql);
					$this->db->query($sql);
                   
                    return '1';
                
             
                        
    }
	public function insert_advance_otp($indent_no,$otp,$tr_type='0') 
        {
        $log=New log('indent-otp-'.date('Y-m-d').'.log');
                  $indent_no=trim($indent_no);
                $log->write($indent_no,'-'.$otp.'-'.$tr_type);
               
                $sql="insert into oc_order_delivery_advance  SET indent_no = '" . $this->db->escape($indent_no). "', otp = '" . $this->db->escape($otp) . "', tr_type = '" . $this->db->escape($tr_type) . "' ON DUPLICATE KEY UPDATE otp = '" . $this->db->escape($otp) . "' ";
                $this->db->query($sql);
                $log->write($sql);
                return $this->db->getLastId(); 
                        
    }

	public function getproductprice($storeid,$productid)
	{ 
		$sql="SELECT store_price from oc_product_to_store where store_id='".$storeid."' and product_id='".$productid."'";

		$query = $this->db->query($sql);

		return $query->row['store_price'];

	} 
	public function getusermobile($userid)
	{ 
		$sql="SELECT username,email from oc_user where user_id='".$userid."' "; 

		$query = $this->db->query($sql);

		return $query->row;
 
	}
	public function insert_runner_otp($data,$storeid,$pin) 
        {
        $log=new Log("cash-new".date('Y-m-d').".log");
                  
                $log->write($userid,'-'.$storeid.'-'.$pin);
                               $sql="insert into oc_runner_otp_trans  SET user_id = '" . $this->db->escape($data['userid']). "', store_id = '" . $this->db->escape($storeid) . "', otp = '" . $this->db->escape($pin) . "',system_trans_id = '" . $this->db->escape($data['system_trans_id']) . "',imei = '" . $this->db->escape($data['imei']) . "', cr_date=NOW()";
                //$sql="insert into oc_runner_otp_trans  SET user_id = '" . $this->db->escape($userid). "', store_id = '" . $this->db->escape($storeid) . "', otp = '" . $this->db->escape($pin) . "', cr_date=NOW()";
                //$this->db->query($sql);
                $log->write($sql);
                $ret_id=$this->db->query($sql); 
				$log->write($ret_id);
				return $ret_id;
                        
    }
    /*
    public function get_user_balance($user_id){
            $log=new Log("bnk-".date('Y-m-d').".log");
            $sql='SELECT cash, card FROM `' . DB_PREFIX . 'user` WHERE user_id="'.$user_id.'"';
            $query = $this->db->query($sql); 
            $log->write($sql);
            $log->write($query->row); 
            return $query->row;   
        }
	
	
        */
    
  public function get_user_balance($user_id){
            $log=new Log("cash-new".date('Y-m-d').".log");
            $sql='SELECT cash, card FROM `' . DB_PREFIX . 'user` WHERE user_id="'.$user_id.'"  ';
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->row); 
            return $query->row;   
        }
	public function get_store_cash_balance($store_id){ 
            $log=new Log("cash-new".date('Y-m-d').".log");
            $sql='SELECT sum(cash) as cash FROM `' . DB_PREFIX . 'user` WHERE store_id="'.$store_id.'" and user_group_id=11 group by store_id ';
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->row); 
            return $query->row;   
        }
	public function get_store_balance($store_id){
            $query = $this->db->query('SELECT currentcredit FROM `' . DB_PREFIX . 'store` WHERE store_id="'.$store_id.'"');
            return $query->row['currentcredit'];   
        }
	public function getstorename($store_id){  
            $log=new Log("cash-new".date('Y-m-d').".log");
            $sql='SELECT name FROM oc_store WHERE store_id="'.$store_id.'"  ';
            $query = $this->db->query($sql);  
            $log->write($sql);
            $log->write($query->row); 
            return $query->row['name'];    
        } 

	public function insert_product_to_store($json) 
	{ 
	    	$log=new Log("field_quantity-".date('Y-m-d').".log");
			$log->write($json);
		$sql="insert into oc_product_to_store_field  SET product_id = '" . $this->db->escape($json['product_id']). "', store_id = '" . $this->db->escape($json['store_id']) . "', quantity = '" . $this->db->escape($json['quantity']) . "' ON DUPLICATE KEY UPDATE quantity = '" . $this->db->escape($json['quantity']) . "',`MOD_DATE`='".date('Y-m-d h:i:s')."'  ";
        	$log->write($sql);		
		$query = $this->db->query($sql);		

		 $log->write($query);
		$countAffected=$this->db->countAffected();
		$log->write($countAffected);

		 $p_sql = "insert into oc_product_trans set store_id='".$json['store_id']."',product_id ='".$json['product_id']."',quantity='".$json['quantity']."',order_id='0',cr_db='CR',trans_type='FIELD'  ";
		 $this->db->query($p_sql);	
		  $log->write("query return value");
		 $log->write($query);
		return $query;

	} 
}    
?>