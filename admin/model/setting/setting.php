<?php
class ModelSettingSetting extends Model {
	public function getBillingStatus($key) 
	{
		$sql=" select  * from oc_billing_status where `key`='".$key."'  ";
		$query = $this->db->query($sql);
		
		return $query->row['value'];
	}

	public function updateBillingStatus($key,$current_status) 
	{
		if($current_status=='1')
		{
			$new_status='0';
		}
		if($current_status=='0')
		{
			$new_status='1';
		}
		$sql=" update oc_billing_status set `value`='".$new_status."' where `key`='".$key."'  ";
		return $query = $this->db->query($sql);
		
		
	}

	public function getSetting($code, $store_id = 0) {
		$setting_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$setting_data[$result['key']] = $result['value'];
			} else {
				$setting_data[$result['key']] = unserialize($result['value']); 
			}
		}

		return $setting_data;
	}
public function getcredit($store_id) {
$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "' " );
return $query->row;
}
public function getSettingsql($code, $store_id = 0) {


		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		 
			
		return $query->rows;
	}

	public function getSettingbykey($code,$key, $store_id = 0) {


		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "' and `key`='".$this->db->escape($key)."'");

		
		 return $query->row['value'];
	}





	public function editSetting($code, $data, $store_id = 0) {
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
//print_r($data['config_creditlimit']);
$upd_sql="update `" . DB_PREFIX . "store`  set creditlimit='".$data['config_creditlimit']."' WHERE store_id = '" . (int)$store_id . " ' ";
$this->db->query($upd_sql);
		foreach ($data as $key => $value) {
                    $this->editSettingValue($code, $key, $value, $store_id);
                    /*
			if (substr($key, 0, strlen($code)) == $code) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
				}
			}
                        */
		}
	}

	public function deleteSetting($code, $store_id = 0) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");
	}

	public function editSettingValue($code = '', $key = '', $value = '', $store_id = 0) {
$log=new Log("stores-".date('Y-m-d').".log");
		if (!is_array($value)) {
                                          $sql="insert into  " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "',`code` = '" . $this->db->escape($code) . "',`key` = '" . $this->db->escape($key) . "',store_id = '" . (int)$store_id . "'on duplicate key update value='".$this->db->escape($value)."' ";
			$log->write($sql);
			$this->db->query($sql);
		} else {
			$sql="insert into " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1',`code` = '" . $this->db->escape($code) . "',`key` = '" . $this->db->escape($key) . "',store_id = '" . (int)$store_id . "' on duplicate key update value='".$this->db->escape(serialize($value))."' ";
$log->write($sql);
			$this->db->query($sql);
		}
	}
}
