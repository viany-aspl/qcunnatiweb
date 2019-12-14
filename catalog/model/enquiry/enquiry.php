<?php
class ModelEnquiryEnquiry extends Model {
	function postMessage($ProdId,$ProdName,$QryMob) {
            $dt=date('Y-m-d H:i:s');
            $query = $this->db->query("insert into oc_inquiry_form (form_type,customer_mobile,Product_name,date_time,product_id) values ('Enquiry','".$QryMob."','".$ProdName."','".$dt."','".$ProdId."') ");
            if($query){
                return 1;
            }
            else {
                return 0;
            }
	}
}