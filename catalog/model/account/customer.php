<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		$this->event->trigger('pre.customer.add', $data);

		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->load->language('mail/customer');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail($this->config->get('config_mail'));
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject);
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail->setTo($this->config->get('config_email'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->event->trigger('post.customer.add', $customer_id);

		return $customer_id;
	}

	public function editCustomer($data) {
		$this->event->trigger('pre.customer.edit', $data);

		$customer_id = $this->customer->getId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->event->trigger('post.customer.edit', $customer_id);
	}

	public function editPassword($email, $password) {
		$this->event->trigger('pre.customer.edit.password');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		$this->event->trigger('post.customer.edit.password');
	}

	public function editNewsletter($newsletter) {
		$this->event->trigger('pre.customer.edit.newsletter');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.edit.newsletter');
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}


	public function getsale($uid,$sdate)
		{

		$log=new Log("mysale-".date('Y-m-d').".log");
		
		if(empty($sdate))
		{
			$sql="select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Cash' AND order_status_id='5') group by product_id ";
			$query = $this->db->query($sql);

			$log->write($sql);
		}else{
			$sql="select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".$sdate."' AND payment_method='Cash' AND order_status_id='5') group by product_id ";
			$query = $this->db->query($sql);

			$log->write($sql);

			}

					return $query->rows;
		
		}


	public function getsaleTagged($uid,$sdate,$store_id=null)
		{

		$log=new Log("mysale-tag-".date('Y-m-d').".log");
		if(empty($sdate))
		{
			$sdate=date('Y-m-d');
		}
		$sql="SELECT product_id,oc_order_product.name as name,sum(quantity) as quantity,(oc_order_product.price+oc_order_product.tax) as price FROM `oc_order_product` left join oc_order on oc_order_product.order_id=oc_order.order_id WHERE oc_order.order_id!='' ";

if(!empty($store_id))
{
$sql.=" and oc_order.store_id='".$store_id."' "; 
}
else
{
$sql.=" and oc_order.user_id='".$uid."' "; 
}
$sql.=" and date(oc_order.date_added)='".$sdate."' AND (payment_method ='Tagged' or payment_method ='Tagged Cash' or payment_method ='Tagged Subsidy') AND order_status_id='5' GROUP by oc_order_product.product_id ";

		$log->write($sql);
		
			$query = $this->db->query($sql);
		

					return $query->rows;
		
		}


	public function getsaleSub($uid,$sdate)
		{

		$log=new Log("mysale".date('Y-m-d').".log");
		$log->write("select product_id,name,sum(quantity) as quantity,(price+tax) as price  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Subsidy' AND order_status_id='5') group by product_id");
		if(empty($sdate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND (payment_method='Subsidy' or payment_method='Tagged Subsidy') AND order_status_id='5') group by product_id");
		}else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".$sdate."' AND (payment_method='Subsidy' or payment_method='Tagged Subsidy') AND order_status_id='5') group by product_id");

			}

					return $query->rows;
		
		}

	public function getsaleChq($uid,$sdate)
		{

		$log=new Log("mysale-".date('Y-m-d').".log");
		$log->write("select product_id,name,sum(quantity) as quantity,(price+tax) as price  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Cheque' AND order_status_id='5') group by product_id");
		if(empty($sdate))
		{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".date('Y-m-d')."' AND payment_method='Cheque' AND order_status_id='5') group by product_id");
		}else{
			$query = $this->db->query("select product_id,name,sum(quantity) as quantity,(price) as price,tax  from  oc_order_product  where order_id in ( select order_id from oc_order where user_id='".$uid."' AND DATE(date_added)='".$sdate."' AND payment_method='Cheque' AND order_status_id='5') group by product_id");

			}

					return $query->rows;
		
		}

	public function getCrops() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "crop ");

		return $query->rows;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}



	


	public function getank() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bank` WHERE IsActive='1'and bank_account_number<>'0'");

		return $query->rows;
	}
		public function gethelp() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "help` WHERE IsActive='1'");

		return $query->rows;
	}		

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}
	
	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
		
		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
		}			
	}	
	
	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		return $query->row;
	}

	public function getLastOrderDate($cid) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . $this->db->escape(utf8_strtolower($cid)) . "' order by date_added desc limit 1");
		return $query->row;
	}



	public function getUserSale($uid,$sdate) {
		$log=new Log("mysale-".date('Y-m-d').".log");
		$sql="SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total','tax') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND o.payment_method='Cash' AND o.user_id='".$uid."' ";
		$log->write($sql);	
				$query = $this->db->query($sql);
		return $query->row;
	}
	public function getUserSaleTagged($uid,$sdate) {
		$log=new Log("mysale-".date('Y-m-d').".log");
		$log->write("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND (o.payment_method ='Tagged' or o.payment_method ='Tagged Cash' or o.payment_method ='Tagged Subsidy' ) AND  o.user_id='".$uid."'");	
				$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total','tax') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND (o.payment_method='Tagged' or o.payment_method ='Tagged Cash' or o.payment_method ='Tagged Subsidy') AND o.user_id='".$uid."'");
		return $query->row;
	}
public function getStoreSaleTagged($uid,$sdate,$store_id) {
		$log=new Log("mysale-tag-".date('Y-m-d').".log");
		$sql=" select sum(tagged) as total from oc_order where payment_method in ('Tagged','Tagged Cash','Tagged Subsidy') and date(date_added)='".$sdate."' and store_id='".$store_id."' AND order_status_id='5'  ";
 
		$log->write($sql);	
				$query = $this->db->query($sql);
		return $query->row;
	}

		public function getUserSaleSub($uid,$sdate) {
		$log=new Log("mysale.log");
		$log->write("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND (o.payment_method='Subsidy' or o.payment_method='Tagged Subsidy') AND  o.user_id='".$uid."'");	
				$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total','tax') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND (o.payment_method='Subsidy' or o.payment_method='Tagged Subsidy') AND o.user_id='".$uid."'");
		return $query->row;
	}


	public function getUserSaleChq($uid,$sdate) {
		$log=new Log("mysale.log");
		$log->write("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND o.payment_method='Cheque' AND  o.user_id='".$uid."'");	
				$query = $this->db->query("SELECT SUM(ot.value) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code IN ('sub_total','tax') AND o.order_status_id = '5' AND DATE(o.date_added)='".$sdate."' AND o.payment_method='Cheque' AND o.user_id='".$uid."'");
		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

public function getcashinhand($user_id,$store_id)
{

	date_default_timezone_set('Asia/Kolkata');
              $log=new Log("login.log");
	$prev_date = date('Y-m-d', strtotime($date .' -1 day'));
	$today_date = date('Y-m-d');
	$sql="select count(*) as total from (SELECT * FROM `oc_cash_store_position` where `store_id`='".$store_id."' AND `user_id`='".$user_id."'  AND  (DATE(update_date)='".$prev_date."' OR DATE(update_date)='".$today_date."' ) ) as aa";
              $log->write($sql);	
	$query = $this->db->query($sql);
	 
             return  $query->row['total'];

}
 public function get_current_cash($username) {
       $log=new Log("setank-".date("Y-m-d").".log");
                 $sql=" SELECT cash as amount FROM `oc_user` as ocr where ocr.user_id ='".$username."'   ";
                 $log->write($sql);
                            
      $query = $this->db->query($sql);
                $log->write('current cash in hand is : '.$query->row["amount"]);
      return $query->row["amount"];
    }
	public function add_to_store_cash($username,$amount,$order_id,$store_id) {
       $log=new Log("cash-new".date("Y-m-d").".log");
                 $sql=" update `oc_user` set cash=cash+'".$amount."' where user_id ='".$username."'   ";
                 $log->write($sql);
                            
      $query = $this->db->query($sql);
	try{
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($amount,$store_id,$username,'CR',$order_id,'SCR',$amount);  
			
			$this->load->library('sms'); 	
			$sms=new sms($this->registry);
			//$sms->sendsms($this->request->post['customer_mob'],"2",$data);
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
      
    }
	public function addbankTrans($data) {
            $log=new Log("cash-new".date('Y-m-d').".log");
                $sql="INSERT INTO " . DB_PREFIX . "bank_transaction SET bank_id = '" . $data['bank_id'] . "', bank_name = '" . $data['bank_name'] . "', amount= '" . $this->db->escape($data['amount']) . "',user_id= '" . $this->db->escape($data['user_id']) . "',accept_by='".$data['ce_id']."',store_id= '" . $this->db->escape($data['store_id']) . "', date_added = NOW()";
		$this->db->query($sql);
                $log->write($sql);
		$tid = $this->db->getLastId();
		
		$sql12 = "update " . DB_PREFIX . "user set cash = cash - ".$data['amount']."  where user_id = ".$data['user_id']; 
                        $log->write($sql12);
                        $this->db->query($sql12); 
                       	try{
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($data['amount'],$data['store_id'],$data['user_id'],'DB',$tid,'CD',$data['amount']);   
			
			$this->load->library('sms');	
			$sms=new sms($this->registry);
			//$sms->sendsms($this->request->post['customer_mob'],"2",$data);
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               }
		return $tid; 
	} 

public function chekrunnerotp($otp,$user_id) 
	{
       	$log=new Log("cash-new".date('Y-m-d').".log");
		$log->write($otp);
		$log->write($user_id);
        $sql=" SELECT otp FROM `oc_runner_otp_trans`  where user_id ='".$user_id."'  order by sno desc  limit 1";//and otp ='".$otp."'
		$log->write($sql);
		//$query = $this->db->query($sql);
		$ret=$this->db->query($sql);
		$log->write($ret);
		return $ret;
    }	
	public function getanktrans($uid,$sid) {
                $sql="SELECT bt.*,st.name as name,concat(oc_user.firstname,' ',oc_user.lastname) as runner_name FROM `" . DB_PREFIX . "bank_transaction` as bt LEFT JOIN `" . DB_PREFIX . "store` as st on st.store_id=bt.store_id left join oc_user on bt.accept_by=oc_user.user_id  WHERE bt.user_id='".$uid."' order by date_added desc "; 
		$query = $this->db->query($sql);
                $log=new Log("cash-new".date('Y-m-d').".log");
                $log->write($sql);
		return $query->rows; 
	} 
	public function addamounttostoreincharge($data,$tid) {
            $log=new Log("cash-new".date('Y-m-d').".log");
               
		$sql12 = "update " . DB_PREFIX . "user set cash = cash + ".$data['amount']."  where user_id = ".$data['ce_id']; 
                        $log->write($sql12);
                        $this->db->query($sql12);
                       	try{
                                 $this->load->library('trans');
                                 $trans=new trans($this->registry);
                                 $trans->addstoretrans($data['amount'],$data['store_id'],$data['ce_id'],'CR',$tid,'CASHRECEIVED',$data['amount']);  
                                 
                               } catch (Exception $e){
                                   $log->write($e->getMessage());
                               } 
		return $tid; 
	}
}