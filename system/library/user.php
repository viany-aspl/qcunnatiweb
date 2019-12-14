<?php
class User {
	private $user_id;
	private $username;
	private $usernameshow;
        private $user_store_id;
		private $unit_id;
	private $user_group_id;
	private $permission = array();

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->config=$registry->get('config');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];
				$this->usernameshow = $user_query->row['firstname']." ".$user_query->row['lastname'];
				$this->user_group_id = $user_query->row['user_group_id'];
                                $this->user_store_id= $user_query->row['store_id'];
								$this->unit_id = empty($user_query->row['unit_id'])?0:$user_query->row['unit_id'];
				$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");
				
				//setting to change
                                    $this->config->set('config_store_id', $this->user_store_id);
                                // Settings
                                       $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)  $this->config->get('config_store_id') . "' ORDER BY store_id ASC");

                                        foreach ($query->rows as $result) {
                                            if (!$result['serialized']) {
                                                $this->config->set($result['key'], $result['value']);
                                                } else {
                                                    $this->config->set($result['key'], unserialize($result['value']));
                                                }
                                            }

                                 //end setting			

		
				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

				$permissions = unserialize($user_group_query->row['permission']);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($username, $password) {
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

		if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];

			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];
			$this->usernameshow = $user_query->row['firstname']." ".$user_query->row['lastname'];
			$this->user_group_id = $user_query->row['user_group_id'];
                        $this->user_store_id= $user_query->row['store_id'];
			
			//setting to change
                                    $this->config->set('config_store_id', $this->user_store_id);
                                // Settings
                                       $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)  $this->config->get('config_store_id') . "' ORDER BY store_id ASC");

                                        foreach ($query->rows as $result) {
                                            if (!$result['serialized']) {
                                                $this->config->set($result['key'], $result['value']);
                                                } else {
                                                    $this->config->set($result['key'], unserialize($result['value']));
                                                }
                                            }

                                 //end setting
			
			$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			$permissions = unserialize($user_group_query->row['permission']);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['user_id']);

		$this->user_id = '';
		$this->username = '';
	}

	public function hasPermission($key, $value) {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}
	
public function getUserNameShow() {
		return $this->usernameshow;
	}

	public function getGroupId() {
		return $this->user_group_id;
	}	
        	public function getStoreId() {
		return $this->user_store_id;
	}	
	public function getUnitId() {
		return $this->unit_id;
	}
}