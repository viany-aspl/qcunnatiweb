<?php

class ModelUserUser extends Model {

    public function getUserpasswordhistory($user_id) {
        $sql = "SELECT * FROM oc_user_password_trans  WHERE user_id = '" . $user_id . "' and date(datetime)='" . date('Y-m-d') . "' ";
        $query = $this->db->query($sql);
        $log = new Log("forgot-" . date('Y-m-d') . ".log");
        $log->write($sql);
        return $query->rows;
    }

    public function addUserpasswordhistory($user_id, $username, $imei, $change_forgot) {
        $sql = "insert into  oc_user_password_trans set user_id = '" . $user_id . "',datetime='" . date('Y-m-d h:i:s') . "',username='" . $username . "',imei='" . $imei . "',change_forgot='" . $change_forgot . "' ";
        $log = new Log("forgot-" . date('Y-m-d') . ".log");
        $log->write($sql);
        $query = $this->db->query($sql);
    }

    public function get_user_stores($user_id) {
        $query = $this->db->query("SELECT store_id FROM oc_user_to_store  WHERE user_id = '" . (int) $user_id . "'");

        return $query->rows;
    }

    public function addUser($data) {

        $this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '" . (int) $data['user_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_id = '" . $this->db->escape($data['config_company']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int) $data['status'] . "', allow_cash = '" . (int) $data['allow_cash'] . "',store_id='" . (int) $data['user_store_id'][0] . "', date_added = NOW()");
        $user_id = $this->db->getLastId();

        $this->db->query("insert into oc_user_credit set user_id = '" . (int) $user_id . "',store_id='" . (int) $data['user_store_id'][0] . "', max_credit_amount='" . $this->db->escape($data['max_credit_amount']) . "', user_max_qunatity='" . $this->db->escape($data['user_max_qunatity']) . "'");
        //if($data['user_group_id']=="26")
        //{
        //$this->db->query("DELETE FROM oc_ase_to_store WHERE user_id = '" . (int)$user_id . "'");
        foreach ($data['user_store_id'] as $store_id) {
            $this->db->query("insert into oc_user_to_store set user_id = '" . (int) $user_id . "',store_id='" . $store_id . "' on DUPLICATE KEY update store_id='" . $store_id . "' ");
        }

        foreach ($data['config_unit'] as $unit_id) {
            $this->db->query("insert into oc_user_to_unit set user_id = '" . (int) $user_id . "',unit_id='" . $unit_id . "',company_id='" . $data['config_company'] . "' on DUPLICATE KEY update unit_id='" . $unit_id . "',company_id='" . $data['config_company'] . "' ");
        }
        //}
        if ($data['user_group_id'] == "22") {/////////for runner
            foreach ($data['user_store_id'] as $store_id) {
                $this->db->query("insert into oc_runner_to_store set user_id = '" . (int) $user_id . "',store_id='" . $store_id . "',status='1' on DUPLICATE KEY update store_id='" . $store_id . "' ");
            }
        }
    }

    public function editUser($user_id, $data) {
        $sql = "UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '" . (int) $data['user_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', company_id = '" . $this->db->escape($data['config_company']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int) $data['status'] . "', allow_cash= '" . (int) $data['allow_cash'] . "',store_id='" . (int) $data['user_store_id'][0] . "' WHERE user_id = '" . (int) $user_id . "'";

        $query = $this->db->query($sql);
//            $log=new Log("editUser-".date('Y-m-d').".log");
//		$log->write($sql);      


        if ($data['password']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int) $user_id . "'");
        }
        //if($data['user_group_id']=="26") 
        //{
        $this->db->query("DELETE FROM oc_user_to_store WHERE user_id = '" . (int) $user_id . "'");
        foreach ($data['user_store_id'] as $store_id) {
            $this->db->query("insert into oc_user_to_store set user_id = '" . (int) $user_id . "',store_id='" . $store_id . "' on DUPLICATE KEY update store_id='" . $store_id . "' ");
            
            $num_count = $this->db->query("select count(id) as total from oc_user_credit where user_id='" . $user_id . "' && store_id='" .$store_id. "' ")->row;
            if ($num_count['total'] >= 1) {
                $this->db->query("UPDATE `oc_user_credit` SET max_credit_amount='" . $this->db->escape($data['max_credit_amount']) . "', user_max_qunatity='" . $this->db->escape($data['user_max_qunatity']) . "' WHERE user_id = '" . (int) $user_id . "' && store_id='" .$store_id. "'");
            } else {
                $this->db->query("insert into oc_user_credit set user_id = '" . (int) $user_id . "',store_id='" .$store_id. "', max_credit_amount='" . $this->db->escape($data['max_credit_amount']) . "', user_max_qunatity='" . $this->db->escape($data['user_max_qunatity']) . "'");
            }
        }
        $this->db->query("DELETE FROM oc_user_to_unit WHERE user_id = '" . (int) $user_id . "'");
        foreach ($data['config_unit'] as $unit_id) {
            $this->db->query("insert into oc_user_to_unit set user_id = '" . (int) $user_id . "',unit_id='" . $unit_id . "',company_id='" . $data['config_company'] . "' on DUPLICATE KEY update unit_id='" . $unit_id . "',company_id='" . $data['config_company'] . "' ");
        }
        //}
        if ($data['user_group_id'] == "22") {/////////for runner
            foreach ($data['user_store_id'] as $store_id) {
                $this->db->query("insert into oc_runner_to_store set user_id = '" . (int) $user_id . "',store_id='" . $store_id . "',status='1' on DUPLICATE KEY update store_id='" . $store_id . "' ");
            }
        }
    }

    public function editPassword($user_id, $password) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int) $user_id . "'");
    }

    public function editCode($email, $code) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function editCodeUser($email, $code) {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(username) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function deleteUser($user_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int) $user_id . "'");
    }

    public function getUser($user_id) {
        $query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int) $user_id . "'");

        return $query->row;
    }

    public function getUserByUsername($username) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");

        return $query->row;
    }

    public function getUserByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

        return $query->row;
    }

    public function getUsers($data = array()) {
        $sql = "SELECT oc_user.*,oc_store.name as store_name,oc_user_group.name as user_group_name FROM `" . DB_PREFIX . "user` left join oc_store on oc_user.store_id=oc_store.store_id left join oc_user_group on oc_user.user_group_id=oc_user_group.user_group_id where oc_user.user_id!='' ";
        if ($data['filter_user_group_id'] != '') {

            $sql .= " and oc_user.user_group_id=ifnull('" . $data['filter_user_group_id'] . "',oc_user.user_group_id)";
        }
        $sort_data = array(
            'username',
            'status',
            'date_added'
        );
        if ($data['filter_name'] != '') {
            $sql .= "  and concat(oc_user.firstname,' ',oc_user.lastname) like '%" . $data['filter_name'] . "%' ";
        }

        if ($data['filter_store'] != '') {
            $sql .= " and oc_user.store_id='" . $data['filter_store'] . "' ";
        }
        if ($data['filter_mobile'] != '') {
            $sql .= "  and oc_user.username like '%" . $data['filter_mobile'] . "%' ";
        }
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY username";
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

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        //echo $sql;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUsers($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` where oc_user.user_id!='' ";
        if ($data['filter_user_group_id'] != '') {

            $sql .= " and oc_user.user_group_id=ifnull('" . $data['filter_user_group_id'] . "',oc_user.user_group_id)";
        }
        if ($data['filter_name'] != '') {
            $sql .= "  and concat(oc_user.firstname,' ',oc_user.lastname) like '%" . $data['filter_name'] . "%' ";
        }
        if ($data['filter_mobile'] != '') {
            $sql .= "  and oc_user.username like '%" . $data['filter_mobile'] . "%' ";
        }
        if ($data['filter_store'] != '') {
            $sql .= " and oc_user.store_id='" . $data['filter_store'] . "' ";
        }
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalUsersByGroupId($user_group_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int) $user_group_id . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }

    public function getUsersByGroupId($user_group_id) {
        $query = $this->db->query("SELECT user_id, username FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int) $user_group_id . "' LIMIT 1");
        return $query->rows;
    }

    public function getUnitbyUser($user_id) {
        $query = $this->db->query("SELECT unit_id FROM oc_user_to_unit WHERE user_id = '" . (int) $user_id . "'");

        return $query->rows;
    }

    public function get_user_credit($user_id) {
        if ($user_id != '') {
            $query = $this->db->query("SELECT user_id, store_id, user_max_qunatity, max_credit_amount  FROM oc_user_credit where user_id='" . $user_id . "'");
            if ($query->num_rows > 0) {
                $msg = array('status' => 'success', 'response' => $query->rows);
            } else {
                $msg = array('status' => 'error', 'response' => 'User credit not found. Please contact admin');
            }
        } else {
            $msg = array('status' => 'error', 'response' => 'User not found..');
        }

        return $msg;
    }

}
