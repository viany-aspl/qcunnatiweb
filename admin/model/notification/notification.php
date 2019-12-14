<?php
class ModelNotificationNotification extends Model {

public function getnotifications() {
                            $sql=" SELECT * FROM `oc_notifications` where status='1' order by id desc limit 20";
		
		$query = $this->db->query($sql);
               
		return $query->rows;
	}
public function getnotificationscount($uid) {
                            $sql=" SELECT count(*) as count FROM `oc_notifications`  where status='1'  and user_id='".$uid."' ";
		
		$query = $this->db->query($sql); 
               
		return $query->row;
	}
	public function addStore($data) {
		$this->event->trigger('pre.admin.store.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "',`creditlimit`='".$this->db->escape($data['creditlimit'])."' ");

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

	
	
}