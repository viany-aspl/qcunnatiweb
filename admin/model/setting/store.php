<?php
class ModelSettingStore extends Model {


public function getAllUnits() {

			$query = $this->db->query("SELECT unit_id,unit_name from oc_unit order by unit_name asc");
                                                          							                        
				

		return $query->rows;
	}
public function getDelivery($data = array()) {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bcml_delivery_type ORDER BY name");
                                                          							                        
				

		return $query->rows;
	}
public function getUnitsbyStore($store_id) {

		$query = $this->db->query("SELECT oc_store_to_unit.unit_id,oc_unit.unit_name as unit_name FROM `oc_store_to_unit` left join oc_unit on oc_store_to_unit.unit_id=oc_unit.unit_id where oc_store_to_unit.store_id='".$store_id."' "); 


		return $query->rows;
	}
	public function getUnitsbyCompany($company_id) 
	{

		$query = $this->db->query("SELECT oc_unit.unit_id,oc_unit.unit_name as unit_name FROM  oc_unit where oc_unit.company_id='".$company_id."' "); 


		return $query->rows;
	}


	public function get_nearest_store($data) {
$log=new Log("get_nearest_store-".date('Y-m-d').".log");
$log->write('get_nearest_store called in model/setting/store'); 

    $sql="select b.*,op.model as product_name,
 111.111 *
    DEGREES(ACOS(COS(RADIANS(Latt))
         * COS(RADIANS(latt_current))
         * COS(RADIANS(Longg - long_currnet))
         + SIN(RADIANS(Latt))
         * SIN(RADIANS(latt_current)))) AS distance_in_km
        
         from (


SELECT
    store_id,
    store_name,
    product_id,
    (CASE
        WHEN Latt = '' THEN '0.0'
        ELSE Latt
    END) AS Latt,
    (CASE
        WHEN Longg = '' THEN '0.0'
        ELSE Longg
    END) AS Longg,
    '".$data['latitude']."' as latt_current,
    '".$data['longitude']."' as long_currnet
FROM
    (SELECT
        os.store_id,
     a.product_id,
            ocs.name AS store_name,
            os.`key`,
            `value`,
            SUBSTR(`value`, 1, INSTR(`value`, '-') - 1) AS Latt,
            SUBSTR(`value`, INSTR(`value`, '-') + 1) AS Longg
    FROM
        oc_setting AS os
        left join
        (select product_id,store_id,quantity from oc_product_to_store where quantity>0)as a
        on a.store_id = os.store_id
    LEFT JOIN oc_store AS ocs ON os.store_id = ocs.store_id
    WHERE
        `key` = 'config_geocode'
            AND ocs.name IS NOT NULL) AS a where product_id in (".$data['product_id']."))as b
            left join oc_product as op on b.product_id=op.product_id  group by store_id
            order by distance_in_km asc limit 10 ";

            $query = $this->db->query($sql);
            $log->write('generated query is :');
            $log->write($sql);  
            $log->write($query->rows); 
            $store_data=array();
            foreach ($query->rows as $storedb)
            {
                         $query2 = $this->db->query("SELECT `value` as config_storestatus FROM oc_setting where store_id='".$storedb['store_id']."' and `key`='config_storestatus' limit 1");
                         $storestatus=$query2->row["config_storestatus"];
                         
                         $query3 = $this->db->query("SELECT ost.type_name as config_storetype FROM oc_setting join oc_store_type as ost on ost.sid=oc_setting.value where store_id='".$storedb['store_id']."' and `key`='config_storetype' limit 1");
                         
                         $sql4="SELECT `value` as config_geocode FROM oc_setting where store_id='".$storedb['store_id']."' and `key`='config_geocode' limit 1";
                         $query4 = $this->db->query($sql4);
                       
                         $sql5="SELECT `value` as config_address FROM oc_setting where store_id='".$storedb['store_id']."' and `key`='config_address' limit 1";
                         $query5 = $this->db->query($sql5);
		if($query4->row["config_geocode"]!="")
		{
                            $store_datan =array(
                                'store_id' => $storedb['store_id'],
                                'name'     => $storedb['store_name'],
                                'product_id'      => $storedb['product_id'],
                                'product_name'      => $storedb['product_name'],
                                'distance' =>$storedb['distance_in_km'],
                                'config_storestatus'=>$storestatus,
                                'config_storetype'=>$query3->row["config_storetype"],
                                'config_geocode'=>$query4->row["config_geocode"],
                                'config_address'=>$query5->row["config_address"]
           
        );
        array_push($store_data,  $store_datan); 
	}
            }

        $log->write($store_data);
        return $store_data;
       
    }

	public function addStore($data) {
		$this->event->trigger('pre.admin.store.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "',`unit_id`='" . $this->db->escape($data['config_unit'][0]) . "', `url` = '" . $this->db->escape($data['config_url']) . "', company_id = '" . $this->db->escape($data['config_company']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "'");

		$store_id = $this->db->getLastId();

		// Layout Route
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE store_id = '0'");

		foreach ($query->rows as $layout_route) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_route['layout_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "', store_id = '" . (int)$store_id . "'");
		}

		$this->cache->delete('store');

		$this->event->trigger('post.admin.store.add', $store_id);
		foreach($data['config_unit'] as $unit)
            		{
                
                		$sql11="INSERT INTO oc_store_to_unit set store_id='$store_id',unit_id='".$unit."' ON DUPLICATE KEY UPDATE store_id='$store_id',unit_id='".$unit."' ";
                		$this->db->query($sql11);

           		 }
		return $store_id;
	}

	public function editStore($store_id, $data) {

		$sql111="DELETE from  oc_store_to_unit where store_id='".$store_id."' ";
                	$this->db->query($sql111);

		foreach($data['config_unit'] as $unit)
            		{
                
                		$sql11="INSERT INTO oc_store_to_unit set store_id='$store_id',unit_id='".$unit."' ON DUPLICATE KEY UPDATE store_id='$store_id',unit_id='".$unit."' ";
                		$this->db->query($sql11);

           		 }
		$this->event->trigger('pre.admin.store.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "', unit_id = '" . $this->db->escape($data['config_unit'][0]) . "', company_id = '" . $this->db->escape($data['config_company']) . "',`url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "' WHERE store_id = '" . (int)$store_id . "'");

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
		//$store_data = $this->cache->get('store');
		//print_r($data);
		/*
		if (!$store_data) {
                     $store_data = array(array());
                                       $store_data = array();
		
		
                            $ssql="SELECT * FROM " . DB_PREFIX . "store  ";
		if($data['filter_store']!="")
		{
			//$ssql.=" where name like '%".$data['filter_store']."%' ";
		}
		$ssql.=" ORDER BY name asc";
               	//echo $ssql;
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
			//$this->cache->set('store', $store_data);
		}
		//print_r($store_data);
		return $store_data;
		*/
		$store_data = array();
		$sql="select oc_store.*,
			(select value from oc_setting where oc_setting.store_id=oc_store.store_id and `key`='config_storestatus' limit 1) as store_status,
			(SELECT ost.type_name as config_storetype FROM oc_setting 
			left join oc_store_type as ost on ost.sid=oc_setting.value where store_id=oc_store.store_id and `key`='config_storetype' limit 1) as storetype 
			from oc_store ORDER BY oc_store.name asc";
		$query = $this->db->query($sql);
        foreach ($query->rows as $storedb) 
		{
			if($storedb['store_status']!="0")
			{
                         $store_datan =array(
								'store_id' => $storedb['store_id'],
								'name'     => $storedb['name'],
								'url'      => $storedb['url'],
                        		'config_storestatus'=>$storedb['store_status'],
                        		'config_storetype'=>$storedb['storetype']
			
		);
		array_push($store_data,  $store_datan);  
		}
		}
		return $store_data;
	}
///////////////
public function getStoresForProducts($data = array()) {
		
		
                     		$store_data = array(array());
                                       $store_data = array(array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG
			
		));
		
		$ssql="SELECT * FROM " . DB_PREFIX . "store  ";
		
		$ssql.=" ORDER BY name asc";
               	
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
			//$this->cache->set('store', $store_data);
		
		//print_r($store_data);
		return $store_data;
	}
///////////////////////////////////////////////////
public function getStoresByUser($data = array()) {
		
		if (!$store_data) 
		{
                     		$store_data = array(array());
                                       $store_data = array(array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG
			
		));
		
		
                            $ssql="SELECT oc_store.* FROM oc_user_to_store left join oc_store on oc_user_to_store.store_id=oc_store.store_id ";
		if($data['filter_user']!="")
		{
			$ssql.=" where oc_user_to_store.user_id = '".$data['filter_user']."' ";
		}
		$ssql.=" ORDER BY oc_store.name asc";
               	//echo $ssql;
	       	$query = $this->db->query($ssql);
               
                        	foreach ($query->rows as $storedb) 
		{
                         
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
			
		}
		//print_r($store_data);
		return $store_data;
	}

//////////////////////////////////////////////////

public function getOwnStores($data = array()) {
		
		
		
                     $store_data = array();
                                       
		$ssql="SELECT * FROM " . DB_PREFIX . "store  ";
		
		$ssql.=" ORDER BY name asc";
               
	       $query = $this->db->query($ssql);
               
                        foreach ($query->rows as $storedb) {
                         
                         $query2 = $this->db->query("SELECT `value` as config_storestatus FROM oc_setting where store_id='".$storedb['store_id']."' and `key`='config_storestatus' limit 1");
                        
                         $query3 = $this->db->query("SELECT ost.type_name as config_storetype,oc_setting.value as type_id FROM oc_setting join oc_store_type as ost on ost.sid=oc_setting.value where store_id='".$storedb['store_id']."' and `key`='config_storetype' limit 1"); 
		$storestatus=$query2->row["config_storestatus"];
                            $config_storetype=$query3->row["config_storetype"];
		$type_id=$query3->row["type_id"];
                if(($storestatus!="0") && (($type_id=='1') || ($type_id=='2')))
		{
                         $store_datan =array(
			'store_id' => $storedb['store_id'],
			'name'     => $storedb['name'],
			'url'      => $storedb['url'],
                        'config_storestatus'=>$storestatus,
                        'config_storetype'=>$config_storetype
			
		);
		array_push($store_data,  $store_datan);  
		}
			                                        							
                        
			//$this->cache->set('store', $store_data);
		}
		//print_r($store_data);
		return $store_data;
	}
//////////////////////////////////////////////////

public function getWarehouses($data = array()) { 
		
		
		
                     $store_data = array();
                                       
		$ssql="SELECT * FROM " . DB_PREFIX . "store  ";
		
		$ssql.=" ORDER BY name asc";
               
	       $query = $this->db->query($ssql);
               
                        foreach ($query->rows as $storedb) {
                         
                         $query2 = $this->db->query("SELECT `value` as config_storestatus FROM oc_setting where store_id='".$storedb['store_id']."' and `key`='config_storestatus' limit 1");
                        
                         $query3 = $this->db->query("SELECT ost.type_name as config_storetype,oc_setting.value as type_id FROM oc_setting join oc_store_type as ost on ost.sid=oc_setting.value where store_id='".$storedb['store_id']."' and `key`='config_storetype' limit 1"); 
		$storestatus=$query2->row["config_storestatus"];
                            $config_storetype=$query3->row["config_storetype"];
		$type_id=$query3->row["type_id"];
                if(($storestatus!="0") &&  ($type_id=='2'))
		{
                         $store_datan =array(
			'store_id' => $storedb['store_id'],
			'name'     => $storedb['name'],
			'url'      => $storedb['url'],
                        'config_storestatus'=>$storestatus,
                        'config_storetype'=>$config_storetype
			
		);
		array_push($store_data,  $store_datan);  
		}
			                                        							
                        
			//$this->cache->set('store', $store_data);
		}
		//print_r($store_data);
		return $store_data;
	}
public function getFranchiseStores($data = array()) {
		
		
		
                     $store_data = array();
                                       
		$ssql="SELECT * FROM " . DB_PREFIX . "store  ";
		
		$ssql.=" ORDER BY name asc";
               
	       $query = $this->db->query($ssql);
               
                        foreach ($query->rows as $storedb) {
                         
                         $query2 = $this->db->query("SELECT `value` as config_storestatus FROM oc_setting where store_id='".$storedb['store_id']."' and `key`='config_storestatus' limit 1");
                        
                         $query3 = $this->db->query("SELECT ost.type_name as config_storetype,oc_setting.value as type_id FROM oc_setting join oc_store_type as ost on ost.sid=oc_setting.value where store_id='".$storedb['store_id']."' and `key`='config_storetype' limit 1"); 
		$storestatus=$query2->row["config_storestatus"];
                            $config_storetype=$query3->row["config_storetype"];
		$type_id=$query3->row["type_id"];
                if(($storestatus!="0") && (($type_id=='3') || ($type_id=='4')))
		{
                         $store_datan =array(
			'store_id' => $storedb['store_id'],
			'name'     => $storedb['name'],
			'url'      => $storedb['url'],
                        'config_storestatus'=>$storestatus,
                        'config_storetype'=>$config_storetype
			
		);
		array_push($store_data,  $store_datan);  
		}
			                                        							
                        
			//$this->cache->set('store', $store_data);
		}
		//print_r($store_data);
		return $store_data;
	}
//company wise

     public function getStoresCompanyWise($company_id) { //echo $company_id;
		$store_data = '';//$this->cache->get('store');

		if (!$store_data) {
                     $store_data = array();
                               
               $ssql="SELECT * FROM " . DB_PREFIX . "store where company_id='".$company_id."' ORDER BY name asc";
               
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
//////////////////////////////////////////////////////////////////

public function getStoresWeb($data = array()) {
		//$store_data = $this->cache->get('store');

		//if (!$store_data) {
                     $store_data = array();
			/*
                                       $store_data = array(array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG
			
		));
		*/
		if(empty($data['filter_unit']))
		{
			$ssql="SELECT * FROM " . DB_PREFIX . "store  ";
			if($data['filter_store']!="")
			{
				$ssql.=" where name like '%".$data['filter_store']."%' ";
			}
			$ssql.=" ORDER BY name asc";
		}
		if(!empty($data['filter_unit']))
		{
			$ssql="SELECT * FROM " . DB_PREFIX . "store left join oc_store_to_unit on oc_store_to_unit.store_id=oc_store.store_id where oc_store.name!='' and oc_store_to_unit.unit_id='".$data['filter_unit']."' ";
			if($data['filter_store']!="")
			{
				$ssql.=" and oc_store.name like '%".$data['filter_store']."%' ";
			}
			$ssql.=" ORDER BY oc_store.name asc";
		}
		//echo $ssql;
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
                        
			//$this->cache->set('store', $store_data);
		}
		//}
		return $store_data;
	}

	public function getTotalStores($data=array()) 
	{
		if(empty($data['filter_unit']))
		{
			$ssql="SELECT count(*) as total FROM " . DB_PREFIX . "store  ";
			if($data['filter_store']!="")
			{
				$ssql.=" where name like '%".$data['filter_store']."%' ";
			}
			$ssql.=" ORDER BY name asc";
		}
		if(!empty($data['filter_unit']))
		{
			$ssql="SELECT count(*) as total FROM " . DB_PREFIX . "store left join oc_store_to_unit on oc_store_to_unit.store_id=oc_store.store_id where oc_store.name!='' and oc_store_to_unit.unit_id='".$data['filter_unit']."' ";
			if($data['filter_store']!="")
			{
				$ssql.=" and oc_store.name like '%".$data['filter_store']."%' ";
			}
			$ssql.=" ORDER BY oc_store.name asc";
		}
		//echo $ssql;
		$query = $this->db->query($ssql);

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
                            $sql=" SELECT `oc_setting`.`value` as store_geo,`oc_store`.`store_id` as store_id,`oc_store`.`name` as store_name,
(select oc_setting.value from oc_setting where oc_setting.store_id=oc_store.store_id and oc_setting.key='config_address' limit 1) as store_address FROM `oc_setting` join `oc_store` on `oc_store`.`store_id`=`oc_setting`.`store_id` WHERE `oc_setting`.`key`='config_geocode'  ";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getstoretypes() {

		$query = $this->db->query("SELECT DISTINCT * FROM oc_store_type where `status`='1' ");


		return $query->rows;
	}
        	public function getbanks() {

		$query = $this->db->query("SELECT DISTINCT * FROM oc_bank_list  where `status`='1'  order by bank_name asc "); 


		return $query->rows;
	}
	public function getUnits() {

		$query = $this->db->query("SELECT * FROM `oc_unit`   order by unit_name asc "); 


		return $query->rows;
	}
	public function getUnitbystore($store_id) {

		$query = $this->db->query("SELECT unit_id FROM `oc_store_to_unit`  where store_id='".$store_id."' "); 


		return $query->rows;
	}
	public function getStoreByUnit($unit_id) {

		$query = $this->db->query("SELECT * FROM `oc_store_to_unit`  where unit_id='".$unit_id."' "); 


		return $query->row;
	}
	public function getCompanybystore($store_id) {

		$query = $this->db->query("SELECT oc_store.company_id as company_id  FROM `oc_store`   where oc_store.store_id='".$store_id."' ");  


		return $query->row['company_id'];
	}
	public function getstoretype($storetype) {
        	$log=new Log("category-".date('Y-m-d').".log");
	$sql="select type_name from oc_store_type where `sid`='".$storetype."' limit 1 ";
	$log->write($sql);
	$query=$this->db->query($sql);
        	$log->write($query->row["type_name"]);
	return $query->row["type_name"];
		
	}
	public function cancel_exp_trans($filter_data)
	{
		$order_id=$filter_data['order_id'];
		$sql1="select * from  oc_waive_exp where id='".$order_id."' ";
		$query1= $this->db->query($sql1);
		
		
		$user_info=$filter_data['user_info'];
		$login_info=$filter_data['login_info'];
		
		$data['cash']=$query1->row['cash'];
		$data['store_user_id']=$query1->row['store_user_id'];
		$store_id=$query1->row['store_id'];
		$data['remarks']="CANCEL BY ".$login_info['firstname'].' '.$login_info['lastname'];
		$insert_id=$order_id;
		//print_r($login_info);
		
		//exit;
		
		$sql="update oc_waive_exp set status=0 where id='".$order_id."' ";
		$query= $this->db->query($sql);
		
		if($user_info['user_group_id']==11)
		{
			$sql2="update oc_user set cash= cash +'".$data["cash"]."' WHERE user_id='".$data['store_user_id']."' ";
			//exit;
			$query = $this->db->query($sql2);
			try
			{
				$this->load->library('trans');
				$trans=new trans($this->registry);
				$trans->addstoretrans($data["cash"],$store_id,$data['store_user_id'],'CR',$insert_id,'EXPWOFF-CANCEL',$data["cash"],$data['remarks']);     
			}	 
			catch (Exception $ex) 
			{
				$log->write($ex->getMessage());
			}
		}
		if($user_info['user_group_id']==22)
		{
			$sql2="INSERT INTO `oc_runner_cash_position` (runner_id,amount) VALUES (".$data['store_user_id'].",".$data["cash"].") ON DUPLICATE KEY UPDATE amount=amount+".$data["cash"];
            
			
			$query = $this->db->query($sql2);
			try
			{
				$sql1="SELECT amount FROM `oc_runner_cash_position`  where runner_id='".$data['store_user_id']."' limit 1 ";
				$query1 = $this->db->query($sql1);
                $current_balance=$query1->row['amount'];
				$sql="INSERT INTO `oc_runner_cash_transactions`  (runner_id,trans_type,amount,transid,current_balance) VALUES (".$data['store_user_id'].",'EXPENSE-CANCEL',".$data["cash"].",".$insert_id.",".$current_balance.")";
				$query = $this->db->query($sql);  
			}	 
			catch (Exception $ex) 
			{
				$log->write($ex->getMessage());
			}
		}
	}
	public function getWaiveoffdata($data)
	{
		$sql='select we.store_user_id as store_user_id,we.id as id,we.from_date,we.to_date,we.response,DATE(we.cr_date) as cr_date,
				ou.firstname,ou.lastname,os.name,we.cash,we.store_id,
				(select concat(firstname," ",lastname) as store_user from oc_user where store_id=we.store_id and user_id=we.store_user_id  and  user_group_id in (11,22) limit 1) as store_user 
				from oc_waive_exp as we
				left join oc_store as os on os.store_id=we.store_id
				left join oc_user as ou on ou.user_id=we.user_id';
				if (!empty($data['filter_stores_id'])) 
				{
					$sql .= " WHERE we.store_id= '" . (int)$data['filter_stores_id'] . "'";
				} 
				else 
				{
					$sql .= " WHERE we.store_id > '0'";
				}
				if (!empty($data['filter_date_start'])) 
				{
					$sql .= " AND DATE(we.cr_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
				}

				if (!empty($data['filter_date_end'])) 
				{
					$sql .= " AND DATE(we.cr_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
				}
				$sql .= " AND `type`= '0' ";
				$sql .= " AND we.status= '1' ";
				$sql.=" order by id desc ";

				if (isset($data['start']) || isset($data['limit'])) 
				{
					if ($data['start'] < 0) 
					{
						$data['start'] = 0;
					}

					if ($data['limit'] < 1) 
					{
						$data['limit'] = 20;
					}

					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}
				//echo $sql;
				$query= $this->db->query($sql);
				return $query->rows;
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
$sql .= " AND `type`= '0' ";
$sql .= " AND we.status= '1' ";
$sql .= ") as aa";
//echo $sql;
$query= $this->db->query($sql);
return $query->row['total'];
}



public function Waiver_Report($data)
{
$sql='select we.from_date,we.to_date,we.response,
DATE(we.cr_date) as cr_date,ou.firstname,ou.lastname,os.name,
we.cash,we.store_id,we.document_no,
(select concat(firstname," ",lastname) as store_user 
from oc_user where store_id=we.store_id 
and  user_group_id="11"  
and oc_user.user_id=we.store_user_id) as store_user 

from oc_waive_exp as we
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
$sql .= " AND `type`= '1' ";
$sql.=" order by id desc ";

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
$query= $this->db->query($sql);
return $query->rows;
}
public function getTotalWaiver_Report($data)
{
$sql='select count(*) as total from ( select we.from_date,we.to_date,we.response,DATE(we.cr_date) as cr_date,ou.firstname,ou.lastname,os.name,we.cash,we.store_id,we.document_no,(select concat(firstname," ",lastname) as store_user from oc_user where store_id=we.store_id and  user_group_id="11"  and oc_user.user_id=we.store_user_id) as store_user from oc_waive_exp as we
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
$sql .= " AND `type`= '1' ";


$sql .= ") as aa";
//echo $sql;
$query= $this->db->query($sql);
return $query->row['total'];
}



public function getWaiveoffdata_companywise($data)
{
$sql='select we.from_date,we.to_date,we.response,DATE(we.cr_date) as cr_date,ou.firstname,ou.lastname,os.name,we.cash,we.store_id,(select concat(firstname," ",lastname) as store_user from oc_user where store_id=we.store_id and  user_group_id="11" limit 1) as store_user from oc_waive_exp as we
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
$sql .=" and os.company_id='".$data['filter_company']."' ";
//$sql .= " AND `type`= '0' ";
$sql.=" order by id desc ";

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
$query= $this->db->query($sql);
return $query->rows;
}
public function getTotalWaiveoffdata_companywise($data)
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
$sql .= " AND DATE(we.from_date) >= '" . $this->db->escape($data['filter_date_start']) . "'";
}

if (!empty($data['filter_date_end'])) {
$sql .= " AND DATE(we.to_date) <= '" . $this->db->escape($data['filter_date_end']) . "'";
}
$sql .=" and os.company_id='".$data['filter_company']."' ";
//$sql .= " AND `type`= '0' ";

$sql .= ") as aa";
//echo $sql;
$query= $this->db->query($sql);
return $query->row['total'];
}



public function Waiver_Report_companywise($data)
{
$sql='select we.from_date,we.to_date,we.response,DATE(we.cr_date) as cr_date,ou.firstname,ou.lastname,os.name,we.cash,we.store_id,we.document_no,(select concat(firstname," ",lastname) as store_user from oc_user where store_id=we.store_id and  user_group_id="11"  and oc_user.user_id=we.store_user_id) as store_user from oc_waive_exp as we
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

$sql .=" and os.company_id='".$data['filter_company']."' ";
//$sql .= " AND `type`= '1' ";
$sql.=" order by id desc ";

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
$query= $this->db->query($sql);
return $query->rows;
}
public function getTotalWaiver_Report_companywise($data)
{
$sql='select count(*) as total from ( select we.from_date,we.to_date,we.response,DATE(we.cr_date) as cr_date,ou.firstname,ou.lastname,os.name,we.cash,we.store_id,we.document_no,(select concat(firstname," ",lastname) as store_user from oc_user where store_id=we.store_id and  user_group_id="11"  and oc_user.user_id=we.store_user_id) as store_user from oc_waive_exp as we
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
//$sql .= " AND `type`= '1' ";

$sql .=" and os.company_id='".$data['filter_company']."' ";
$sql .= ") as aa";
//echo $sql;
$query= $this->db->query($sql);
return $query->row['total'];
}



public function getIsecStores($data = array()) {
  //$store_data = $this->cache->get('store');
  //print_r($data);
  
  if (!$store_data) {
                     $store_data = array(array());
                                       $store_data = array();
  
  
                            $ssql="SELECT * FROM " . DB_PREFIX . "store where company_id='3' ";
  if($data['filter_store']!="")
  {
   //$ssql.=" and  name like '%".$data['filter_store']."%' ";
  }
  $ssql.=" ORDER BY name asc";
                //echo $ssql;
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
   //$this->cache->set('store', $store_data);
  }
  //print_r($store_data);
  return $store_data;
 }

public function getSubUser() {
		$sql="SELECT user_id,concat(firstname,' ',lastname) as name from oc_user  where user_group_id='36' ";
           
	              //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	}
public function getStoreUsers() {
		$sql="SELECT user_id,concat(firstname,' ',lastname) as name from oc_user  where user_group_id='11' ";
           
	              //echo $sql;
		$query = $this->db->query($sql);
                
		return $query->rows;
	} 

}