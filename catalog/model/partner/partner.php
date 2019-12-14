<?php
class ModelPartnerPartner extends Model {
	function postMessage($name,$firm,$mob,$email,$msg) {
            $dt=date('Y-m-d H:i:s');
            $query = $this->db->query("insert into oc_inquiry_form (form_type,customer_mobile,message,date_time,Name,Firm_Name,Email_ID) values ('Partner','".$mob."','".$msg."','".$dt."','".$name."','".$firm."','".$email."') ");
            if($query){
                return 1;
            }
            else {
                return 0;
            }
	}
}