<?php
class ModelCatalogStoremenu extends Model {
	public function addCategory($data) {
		$this->event->trigger('pre.admin.category.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "storemenu SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$category_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "storemenu SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "storemenu_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "storemenu_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "storemenu_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "storemenu_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "storemenu_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "storemenu_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['sub_store'])) {
			foreach ($data['sub_store'] as $store_id) {
				$sql="INSERT INTO " . DB_PREFIX . "storemenu_to_user SET category_id = '" . (int)$category_id . "', user_id = '" . (int)$store_id . "'";
			
				$this->db->query($sql);
			}
		}
		

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'menu_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('storemenu');

		$this->event->trigger('post.admin.category.add', $category_id);

		return $category_id;
	}

	public function editCategory($category_id, $data) {
		$this->event->trigger('pre.admin.category.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "storemenu SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "storemenu SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "storemenu_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "storemenu_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "storemenu_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "storemenu_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "storemenu_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "storemenu_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "storemenu_path` WHERE category_id = '" . (int)$category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "storemenu_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "storemenu_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "storemenu_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_filter WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "storemenu_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_to_store WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "storemenu_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		//$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_to_user WHERE category_id = '" . (int)$category_id . "'");
		if (isset($data['sub_store'])) {
			foreach ($data['sub_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "storemenu_to_user SET category_id = '" . (int)$category_id . "', user_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'menu_id=" . (int)$category_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'menu_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('storemenu');

		$this->event->trigger('post.admin.category.edit', $category_id);
	}

	public function deleteCategory($category_id) {
		$this->event->trigger('pre.admin.category.delete', $category_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_path WHERE category_id = '" . (int)$category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "storemenu_path WHERE path_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "storemenu_to_user WHERE category_id = '" . (int)$category_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'menu_id=" . (int)$category_id . "'");

		$this->cache->delete('storemenu');

		$this->event->trigger('post.admin.category.delete', $category_id);
	}

	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "storemenu WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "storemenu_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "storemenu_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "storemenu_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "storemenu_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}

public function getChildCategories($parent_id = 0,$filter_store,$filter_role,$user_id) {

$tblname='oc_storemenu_to_store';
if(($filter_role==36) || ($filter_role==22) || ($filter_role==16))
{
	$tblname='oc_storemenu_to_user';
}

$sql="SELECT sm.category_id as id,name,sm.image,cd1.meta_title as tab,cd1.meta_keyword as mob FROM " . DB_PREFIX . "storemenu sm LEFT JOIN " . DB_PREFIX . "storemenu_description cd1 ON (sm.category_id = cd1.category_id)   
LEFT JOIN ".$tblname." as osms ON (osms.category_id=cd1.category_id)
WHERE parent_id = '" . (int)$parent_id . "'";

//if (!empty($filter_store)) 
{
	if(($filter_role==36) || ($filter_role==22) || ($filter_role==16)) 
{ 
	
			$sql .= " AND osms.user_id ='".$user_id."'";
}else{
			$sql .= " AND osms.store_id ='".$filter_store."'";
}
			
		}
$sql .=" order by sm.sort_order";
		$log=new Log("getmenu-".date('Y-m-d').".log");
		$log->write($sql);

		$query = $this->db->query($sql);
return $query->rows;
}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "storemenu_path cp LEFT JOIN " . DB_PREFIX . "storemenu_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'menu_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "storemenu c LEFT JOIN " . DB_PREFIX . "storemenu_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}




	public function getCategories($data = array()) {



$log=new Log("getmenu-".date('Y-m-d').".log");
		$log->write("in menu");
		$tblname='oc_storemenu_to_store';
		$log->write($data);
		if(($data['filter_role']==36) || ($data['filter_role']==22) || ($data['filter_role']==16))
		{
			$tblname='oc_storemenu_to_user';
		}

		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, 
			c1.parent_id, c1.sort_order,c1.image FROM " . DB_PREFIX . "storemenu_path cp 
			LEFT JOIN " . DB_PREFIX . "storemenu c1 ON (cp.category_id = c1.category_id) 
			LEFT JOIN " . DB_PREFIX . "storemenu c2 ON (cp.path_id = c2.category_id) 
			LEFT JOIN " . DB_PREFIX . "storemenu_description cd1 ON (cp.path_id = cd1.category_id) 
			LEFT JOIN ".$tblname." as osms ON (osms.category_id=cd1.category_id)
			LEFT JOIN " . DB_PREFIX . "storemenu_description cd2 ON (cp.category_id = cd2.category_id) 
			WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'  ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (!empty($data['filter_parent'])) {
			$sql .= " AND c1.parent_id ='0'";
		}
		if (1) {
				
			if(($data['filter_role']==36) || ($data['filter_role']==22) || ($data['filter_role']==16))
			{
				$sql .= " AND osms.user_id ='".$data['user_id']."'";
			}
			else
			{
				$sql .= " AND osms.store_id ='".$data['filter_store']."'";
			}
		}
		else
		{
			$sql.=" and osms.store_id=0  ";
		}
		$sql .= " GROUP BY cp.category_id ";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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
		$log=new Log("getmenu-".date('Y-m-d').".log");
		$log->write($sql);
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "storemenu_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $category_description_data;
	}

	public function getCategoryFilters($category_id) { 
		$category_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "storemenu_filter WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	public function getCategoryStores($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "storemenu_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}
	public function getSubUsers($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "storemenu_to_user WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['user_id'];
		}

		return $category_store_data;
	}
	


	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "storemenu");

		return $query->row['total'];
	}
	public function getmenu() {
		$sql="SELECT os.parent_id,os.category_id,osd.name FROM oc_storemenu as os left join oc_storemenu_description as osd on osd.category_id=os.category_id where os.parent_id=0";
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getsubmenubymenu($mid) {
		$sql="SELECT os.parent_id,os.category_id,osd.name FROM oc_storemenu as os left join oc_storemenu_description as osd on osd.category_id=os.category_id where os.parent_id='".$mid."'";
		$query = $this->db->query($sql);
//echo $sql;
		return $query->rows;
	}
	
	
		public function updategroupmenu($datas) 	
	{
	
			foreach ($datas['filter_user'] as $usergroup) 
			{
			    $sel ="SELECT user_id FROM oc_user where user_group_id='".$usergroup."' and status='1'";
				$query = $this->db->query($sel);
			    $user= $query->rows;
				
				
				foreach ($user as $uid) 
			   { 
			
			   
			    $sql="INSERT INTO " .DB_PREFIX . "storemenu_to_user SET category_id = '" .$datas['filter_menu'] . "', user_id = '".$uid['user_id']."' on DUPLICATE KEY UPDATE category_id = '" .$datas['filter_menu'] . "', user_id ='" .$uid['user_id']."'";
				$this->db->query($sql);
				foreach ($datas['filter_submenu'] as $filter_submenu) 
				{
					$sql1="INSERT INTO " .DB_PREFIX . "storemenu_to_user SET category_id = '" .$filter_submenu. "', user_id = '".$uid['user_id']."' on DUPLICATE KEY UPDATE category_id = '" .$filter_submenu . "', user_id ='" .$uid['user_id']. "'";
				    $this->db->query($sql1);
				}
			   }
			
			}
		
	}
	public function updateusermenu($datas) 	
	{
	//print_r($datas); exit;
			foreach ($datas['filter_user'] as $data) 
			{
			
				$sql="INSERT INTO " . DB_PREFIX . "storemenu_to_user SET category_id = '" .$datas['filter_menu'] . "', user_id = '" .$data. "' on DUPLICATE KEY UPDATE category_id = '" .$datas['filter_menu'] . "', user_id = '" .$data. "'";
				$this->db->query($sql);
				foreach ($datas['filter_submenu'] as $filter_submenu) 
				{
					$sql="INSERT INTO " . DB_PREFIX . "storemenu_to_user SET category_id = '" .$filter_submenu. "', user_id = '" .$data. "' on DUPLICATE KEY UPDATE category_id = '" .$filter_submenu . "', user_id = '" .$data. "'";
					$this->db->query($sql);
				}
			
			}
		//exit;
	}
	public function getusergroupdtl()
	{
		$sql="SELECT user_group_id,name FROM oc_user_group";
		$query = $this->db->query($sql);		
		return $query->rows;
	}

		
}
