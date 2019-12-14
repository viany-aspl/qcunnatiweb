<?php

class ModelCategoryCategory extends Model {

    public function addcategory($data) {
      
                 $sql2="insert into  oc_category_subsidy set category_name='".$data["category_name"]."'";
		$query2 = $this->db->query($sql2);
    }


    public function getCategory($data = array()) {

        $sql = "SELECT  * FROM " . DB_PREFIX . "category_subsidy ";
        $query = $this->db->query($sql);
        // echo $sql;exit;
        return $query->rows;
    }

    public function getTotalCategory() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_subsidy");

        return $query->row['total'];
    }


}
