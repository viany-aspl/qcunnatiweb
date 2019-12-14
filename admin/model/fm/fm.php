<?php

class ModelFmFm extends Model {
	
	/*
	 * POS database table definition
	 * 
	 */
	
	// This function is how POS module creates it's tables to store order payment entries. You would call this function in your controller in a
	// function called install(). The install() function is called automatically by OC versions 1.4.9.x, and maybe 1.4.8.x when a module is
	// installed in admin.

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
   public function insert_order_instance($instance_id,$store_id)
 {
  $log=new Log('order_istance-'.date('Y-m-d').'.log');
  $sql="insert into oc_order_issue_instance set instance_id='".$instance_id."',store_id='".$store_id."' ON DUPLICATE KEY
    UPDATE instance_id='".$instance_id."' ";
  $query = $this->db->query($sql);
  $log->write($sql);
  
 }
   public function update_order_istance_order_id($instance_id,$order_id)
 {
  $log=new Log('order_istance-'.date('Y-m-d').'.log');
  $sql="update oc_order_issue_instance set order_id='".$order_id."' where instance_id='".$instance_id."' ";
  $query = $this->db->query($sql);
  $log->write($sql);
 }
 public function check_order_instance($instance_id)
 {
  $log=new Log('order_istance-'.date('Y-m-d').'.log');
  $sql="select order_id from oc_order_issue_instance where instance_id='".$instance_id."' ";
  $query = $this->db->query($sql);
  $log->write($sql);
  $rows=$query->row;
  if(count($rows)>0)
  {
  return $rows['order_id'];
  }
 }
	public function addOrder($data) 
	{
		$log=new Log("fm-product-".date('Y-m-d').".log");
		$log->write('in model');
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
		$order_query="INSERT INTO `" . DB_PREFIX . "order_issue` SET user_id='".$data['user_id']."',fm_name='".$data['fm_name']."',fm_code='".$data['fm_code']."',store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($store_name) . "', telephone = '" . $this->db->escape($data['telephone']) . "',type='".$data['type']."', date_added = NOW() ";
		$log->write($order_query);

                $this->db->query($order_query);
      
                $order_id = $this->db->getLastId();
                 $log->write("lastid");
				 $log->write($order_id);
                if (isset($data['order_product'])) 
				{		
					foreach ($data['order_product'] as $order_product) 
					{	
						$prd_queryy="INSERT INTO " . DB_PREFIX . "order_product_issue SET order_id = '" . (int)$order_id . "', 
						product_id = '" . (int)$order_product['product_id'] . "',
						name = '" . $this->db->escape($order_product['name']) . "', 
						fm_code='".$data['fm_code']."',
						type='".$data['type']."',
						quantity = '" . (int)$order_product['quantity'] . "' ";
						$log->write($prd_queryy);
						$this->db->query($prd_queryy);
			
				$order_product_id = $this->db->getLastId();
					}
				}

		return $order_id;
	}
	public function checkotp($sid) 
	{
		$sql="SELECT * FROM oc_otp_trans where `sid`='".$sid."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
    
	
	public function fm_item_issue_report($data)
     {
		 
		 $sql="SELECT opi.name, opi.product_id, sum(case when opi.type='issue' then opi.quantity  else 0 end) as 'Issued' ,
			sum(case when opi.type='consume' then opi.quantity else 0 end) as 'Consumed'
			FROM oc_order_product_issue opi
			where opi.fm_code='".$data['fm_code']."' AND DATE(opi.ORD_DATE) BETWEEN '".$data['start_date']."' and '".$data['end_date']."' group by opi.product_id";
			$query = $this->db->query($sql);
		$log=new Log("fm_item_issue_report-".date('Y-m-d').".log");
		$log->write($sql);
		    return $query->rows;
       
 }

  
}    
?>