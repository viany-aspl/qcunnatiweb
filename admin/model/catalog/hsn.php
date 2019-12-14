<?php

class ModelCatalogHsn extends Model {

    public function addhsn($data) {
      
                 $sql2="insert into  oc_product_hsn set hsn_code='".$data["hsn_code"]."',hsn_name='".$data["hsn_name"]."'";
		$query2 = $this->db->query($sql2);
    }


    public function gethsn($data = array()) {

        $sql = "SELECT  * FROM " . DB_PREFIX . "product_hsn ";
	 if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
        $query = $this->db->query($sql);
        // echo $sql;exit;
        return $query->rows;
    }

    public function getTotalhsn($data = array()) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_hsn");

        return $query->row['total'];
    }


}
