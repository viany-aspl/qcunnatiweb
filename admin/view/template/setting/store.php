<?php
class ModelSettingStore extends Model {
	public function addStore($data) {
		$this->event->trigger('pre.admin.store.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "'");

		$store_id = $this->db->getLastId();

		// Layout Route
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE store_id = '0'");

		foreach ($query->rows as $layout_route) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_route['layout_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "', store_id = '" . (int)$store_id . "'");
		}

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.add', $store_id);

		return $store_id;
	}

	public function editStore($store_id, $data) {
		$this->event->trigger('pre.admin.store.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "' WHERE store_id = '" . (int)$store_id . "'");

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.edit', $store_id);
	}

	public function deleteStore($store_id) {
		$this->event->trigger('pre.admin.store.delete', $store_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE store_id = '" . (int)$store_id . "'");

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.delete', $store_id);
	}

	public function getStore($store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");

		return $query->row;
	}


public function getStoreInv($store_id) {
$store_data = array(array());
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");


foreach ($query->rows as $storedb) {
                         $store_datan =array(
			'store_id' => $storedb['store_id'],
			'name'     => $storedb['name'],
			'url'      => $storedb['url']
			
		);
			//array_push($store_data,  $store_datan); 
$store_data=array($store_datan);
                                         							
                        }


		return $store_data;
	}

public function getStores($data = array()) {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
                     $store_data = array(array());
                                       $store_data = array(array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG
			
		));
               $ssql="SELECT * FROM " . DB_PREFIX . "store  ORDER BY name asc";
               
	       $query = $this->db->query($ssql);
               
                        foreach ($query->rows as $storedb) {
                         
                         $query2 = $this->db->query("SELECT `value` as config_storestatus FROM oc_setting where store_id='".$storedb['store_id']."' and `key`='config_storestatus' limit 1");
                        
                         $query3 = $this->db->query("SELECT ost.type_name as config_storetype FROM oc_setting join oc_store_type as ost on ost.sid=oc_setting.value where store_id='".$storedb['store_id']."' and `key`='config_storetype' limit 1"); 
		$storestatus=$query2->row["config_storestatus"];
                         if($storestatus!="0")
		{
                         $store_datan =array(
			'store_id' => $storedb['store_id'],
			'name'     => $storedb['name'],
			'url'      => $storedb['url'],
                        		'config_storestatus'=>$storestatus,
                        		'config_storetype'=>$query3->row["config_storetype"]
			
		);
		array_push($store_data,  $store_datan);  
		}
			                                        							
                        }
			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}
public function getStoresWeb($data = array()) {
		//$store_data = $this->cache->get('store');

		if (!$store_data) {
                     $store_data = array(array());
                                       $store_data = array(array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG
			
		));
               $ssql="SELECT * FROM " . DB_PREFIX . "store  ORDER BY name asc";
               if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$ssql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                //echo $ssql;
	       $query = $this->db->query($ssql); 
               
                        foreach ($query->rows as $storedb) {
                         $query2 = $this->db->query("SELECT `value` as config_storestatus FROM oc_setting where store_id='".$storedb['store_id']."' and `key`='config_storestatus' limit 1");
                        
                         $query3 = $this->db->query("SELECT ost.type_name as config_storetype FROM oc_setting join oc_store_type as ost on ost.sid=oc_setting.value where store_id='".$storedb['store_id']."' and `key`='config_storetype' limit 1");
                         //echo $query3->row["config_storetype"];
                         $store_datan =array(
			'store_id' => $storedb['store_id'],
			'name'     => $storedb['name'],
			'url'      => $storedb['url'],
                        		'config_storestatus'=>$query2->row["config_storestatus"],
                        		'config_storetype'=>$query3->row["config_storetype"]
			
		);
			array_push($store_data,  $store_datan);                                          							
                        }
			//$this->cache->set('store', $store_data);
		}

		return $store_data;
	}

	public function getTotalStores() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store");

		return $query->row['total'];
	}

	public function getTotalStoresByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_layout_id' AND `value` = '" . (int)$layout_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByLanguage($language) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_language' AND `value` = '" . $this->db->escape($language) . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCurrency($currency) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_currency' AND `value` = '" . $this->db->escape($currency) . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_country_id' AND `value` = '" . (int)$country_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_zone_id' AND `value` = '" . (int)$zone_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByCustomerGroupId($customer_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_customer_group_id' AND `value` = '" . (int)$customer_group_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	public function getTotalStoresByInformationId($information_id) {
		$account_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND store_id != '0'");

		$checkout_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND store_id != '0'");

		return ($account_query->row['total'] + $checkout_query->row['total']);
	}

	public function getTotalStoresByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_order_status_id' AND `value` = '" . (int)$order_status_id . "' AND store_id != '0'");

		return $query->row['total'];
	}

	//transport store
	public function getTransport($data = array()) {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "transport ORDER BY name");
                                                          							                        
				

		return $query->rows;
	}

	public function getCircles($store_id) 
        {
		                            $log=new Log("custcircle-".date('Y-m-d').".log");
		$sql="SELECT * FROM oc_contractor where `store_id`='".$store_id."' ORDER BY circle_code  ";
		$log->write($sql);
	      $query = $this->db->query("SELECT * FROM oc_contractor where `store_id`='".$store_id."' ORDER BY circle_code  ");
              return $query->rows; 
		
	}
	public function setCash( $store_name,$store_id,$user_id,$amount,$mobile,$name,$update_date)
        {

		$log=new Log("setcash-".date('Y-m-d').".lpg");
              $sql="insert into oc_cash_store_position_trans (`store_name`,`store_id`,`user_id`,`amount`,`mobile`,`name`) "
                      . "values "
                      . "('".$store_name."','".$store_id."','".$user_id."','".$amount."','".$mobile."','".$name."')"; 
              $query = $this->db->query($sql);
              
              $sql="insert into oc_cash_store_position (`store_name`,`store_id`,`user_id`,`amount`,`mobile`,`name`,`update_date`) " 
                      . "values "
                      . "('".$store_name."','".$store_id."','".$user_id."','".$amount."','".$mobile."','".$name."','".$update_date."') ON DUPLICATE KEY UPDATE amount='".$amount."',`update_date`='".$update_date."' ,ucode=(FLOOR( 1 + RAND( ) *60 )) ";
		$log->write($sql);
	             $query = $this->db->query($sql);

              return $query; 
		
	}
	public function getcashtrans($sid) {
		$query = $this->db->query("SELECT name,store_name,amount,DATE(update_date) as update_date FROM  `oc_cash_store_position_trans`   WHERE store_id='".$sid."' order by SID  desc limit 15");
		return $query->rows;
	}
	public function getcashpostion($sid) {
		$query = $this->db->query("SELECT amount FROM  `oc_cash_store_position`   WHERE store_id='".$sid."'  limit 1");
		return $query->row["amount"];
	}


	public function getCircleCredit($code,$sid)
        {

                $query = $this->db->query("SELECT * FROM  `oc_contractor`   WHERE store_id='".$sid."' and circle_code='".$code."'  ");
		return $query->row;


        }


	public function updatecurrentcash($circle,$amount,$sid) {
		$sql=" update oc_contractor set currentcredit=currentcredit-".$amount."  WHERE store_id='".$sid."' and  circle_code='".$circle."'  ";
		$log=new Log("cash.log");
                            $log->write($sql);
		$query = $this->db->query($sql);
		//return $query->row["amount"];
	}	




	public function getProduct($product_id,$contractor_id,$sid) {
                $sql="SELECT quantity from  `oc_contractor_product` where store_id='".$sid."' `product_id`='$product_id' and `contractor_id`='".$contractor_id."'  ";
		$log=new Log("cash-".date('Y-m-d').".log");
                $log->write($sql);

		$query = $this->db->query($sql);
		return $query->row;
	}


//news
	public function getNewsByID($id) {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news where NewsItemID='".$id."'");
                                                          							                        				
		return $query->rows;
	}

	public function getNews($data = array()) {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news ORDER BY DatePublished desc");                                                          							                        				
		return $query->rows;
	}


	public function getNewsLatest($data = array()) 
	{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news ORDER BY DatePublished desc Limit 4");                                           							                        		
		return $query->rows;
	}

              public function getStorelocation() {
                            $sql=" SELECT `oc_setting`.`value` as store_geo,`oc_store`.`store_id` as store_id,`oc_store`.`name` as store_name FROM `oc_setting` join `oc_store` on `oc_store`.`store_id`=`oc_setting`.`store_id` WHERE `oc_setting`.`key`='config_geocode'  ";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getstoretypes() {

		$query = $this->db->query("SELECT DISTINCT * FROM oc_store_type where `status`='1' ");


		return $query->rows;
	}
        public function getbanks() {

		$query = $this->db->query("SELECT DISTINCT * FROM oc_bank_list where `status`='1' ");


		return $query->rows;
	}
	public function getstoretype($storetype) {
        	$log=new Log("category-".date('Y-m-d').".log");
	$sql="select type_name from oc_store_type where `sid`='".$storetype."' limit 1 ";
	$log->write($sql);
	$query=$this->db->query($sql);
        	$log->write($query->row["type_name"]);
	return $query->row["type_name"];
		
	}
	public function getWaiveoffdata($data)
{
$sql='select we.from_date,we.to_date,we.response,DATE(we.cr_date) as cr_date,ou.firstname,ou.lastname,os.name,we.cash from oc_waive_exp as we
left join oc_store as os on os.store_id=we.store_id
left join oc_user as ou on ou.user_id=we.user_id';
if (!empty($data['filter_stores_id'])) {
$sql .= " WHERE we.store_id= '" . (int)$data['filter_stores_id'] . "'";
} else {
$sql .= " WHERE we.store_id > '0'";
}
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(we.from_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}


if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(we.to_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
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
echo $sql;
$query= $this->db->query($sql);
//return $query->rows;  
}
public function getTotalWaiveoffdata($data)
{
$sql='select count(*) as total from (select we.from_date,we.to_date,we.response,we.cr_date,ou.firstname,ou.lastname,os.name as storename from oc_waive_exp as we
left join oc_store as os on os.store_id=we.store_id
left join oc_user as ou on ou.user_id=we.user_id';
if (!empty($data['filter_stores_id'])) {
$sql .= " WHERE we.store_id= '" . (int)$data['filter_stores_id'] . "'";
} else {
$sql .= " WHERE we.store_id > '0'";
}
if (!empty($data['filter_date_start'])) {
$sql .= " AND DATE(we.cr_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(we.cr_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
$sql .= ") as aa";
//echo $sql;
$query= $this->db->query($sql);
return $query->row['total'];
}
}