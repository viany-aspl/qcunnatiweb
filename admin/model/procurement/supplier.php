<?php
class ModelPurchaseSupplier extends Model {
	public function insert_supplier($data)
	{
		$date_added = date('Y-m-d');
$log=new Log("supp.log");
$log->write("INSERT INTO oc_po_supplier (first_name,last_name,email,telephone,fax,supplier_group_id,date_added,ACC_ID,IFSC_CODE,BANK_NAME,ADDRESS) VALUES('".$data['first_name']."','".$data['last_name']."','".$data['email']."','".$data['telephone']."','".$data['fax']."',".$data['supplier_group_id'].",'".$date_added."','".$data['account']."','".$data['ifsc']."','".$data['bank']."','".$data['address']."')");
		$query = $this->db->query("INSERT INTO oc_po_supplier (first_name,last_name,email,telephone,fax,supplier_group_id,date_added,ACC_ID,IFSC_CODE,BANK_NAME,ADDRESS) VALUES('".$data['first_name']."','".$data['last_name']."','".$data['email']."','".$data['telephone']."','".$data['fax']."',".$data['supplier_group_id'].",'".$date_added."','".$data['account']."','".$data['ifsc']."','".$data['bank']."','".$data['address']."')");
		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function get_all_suppliers($start,$limit)
	{
		$query = $this->db->query("SELECT oc_po_supplier.*,oc_po_supplier_group.supplier_group_name FROM oc_po_supplier
			INNER JOIN oc_po_supplier_group ON 
				oc_po_supplier.supplier_group_id = oc_po_supplier_group.id WHERE oc_po_supplier.delete_bit = ". 0 ."
					ORDER BY id DESC LIMIT ".$start. "," .$limit);
		return $query->rows;
	}
	
	public function get_total_suppliers()
	{
		$query = $this->db->query("SELECT oc_po_supplier.*,oc_po_supplier_group.supplier_group_name FROM oc_po_supplier
			INNER JOIN oc_po_supplier_group ON 
				oc_po_supplier.supplier_group_id = oc_po_supplier_group.id WHERE oc_po_supplier.delete_bit = " . 0 );
		return $query->rows;
	}
	
	/*------------------------get total count supplier function starts here------------------------*/
	
	public function get_total_count_supplier()
	{
		$query = $this->db->query("SELECT COUNT(id) as total_supplier FROM oc_po_supplier WHERE delete_bit = " . 0);
		return $query->row['total_supplier'];
	}
	
	/*---------------------get total count supplier function ends here-----------------*/
	/*------------------delete supplier function starts here---------------------*/
	
	public function delete_supplier($supplier_ids)
	{
		$supplier_ids = implode(',',$supplier_ids);
		
		$query = $this->db->query("SELECT
		COUNT(oc_po_receive_details.id) AS total
		FROM
		oc_po_order
			INNER JOIN oc_po_receive_details 
        ON (oc_po_order.id = oc_po_receive_details.order_id) WHERE oc_po_order.delete_bit= 1 AND oc_po_receive_details.supplier_id IN(".$supplier_ids.")");
		if($query->row['total'] == 0 )
		{
			$query = $this->db->query("UPDATE oc_po_supplier SET delete_bit = " . 1 . " WHERE id IN(".$supplier_ids.")");
			if($this->db->countAffected() > 0)
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	/*------------------delete supplier function ends here---------------------*/
	
	/*---------------------edit supplier function starts here-------------------*/
	
	public function edit_supplier_form($supplier_id)
	{
		$query = $this->db->query("SELECT oc_po_supplier.*,oc_po_supplier_group.supplier_group_name FROM oc_po_supplier
			INNER JOIN oc_po_supplier_group ON 
				oc_po_supplier.supplier_group_id = oc_po_supplier_group.id WHERE oc_po_supplier.id = " . $supplier_id);
		return $query->row;
	}
	
	/*---------------------edit supplier funciton ends here---------------------*/
	
	/*--------------------update supplier function starts here----------------------*/
	
	public function update_supplier($update_info)
	{
		$query = $this->db->query("UPDATE oc_po_supplier
					SET first_name ='".$update_info['first_name']."', last_name = '".$update_info['last_name']."', email = '".$update_info['email']."', telephone = '".$update_info['telephone']."', fax = '".$update_info['fax']."', ACC_ID='".$update_info['ACC_ID']."',IFSC_CODE='".$update_info['IFSC_CODE']."',BANK_NAME='".$update_info['BANK_NAME']."',ADDRESS='".$update_info['ADDRESS']."',supplier_group_id = ".$update_info['supplier_group_id']." WHERE id = ".$update_info['supplier_id']
		);
		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/*--------------------update supplier function ends here----------------------*/
	public function filter($data)
	{
		
		if($data['supplier_date_added'] != '')
		{
			$data['supplier_date_added'] = strtotime($data['supplier_date_added']);
			$data['supplier_date_added'] = date('Y-m-d',$data['supplier_date_added']);
		}
		
		$common_query = "SELECT oc_po_supplier.*,oc_po_supplier_group.supplier_group_name FROM oc_po_supplier
			INNER JOIN oc_po_supplier_group ON 
				oc_po_supplier.supplier_group_id = oc_po_supplier_group.id WHERE oc_po_supplier.delete_bit = ". 0;
		
		if($data['supplier_name'] != '' && $data['supplier_email'] != '' && $data['supplier_group'] != '' && $data['supplier_date_added'] != '')
		{
			//$name = explode(' ',$data['supplier_name']);
			$query_string = " AND CONCAT(oc_po_supplier.first_name, ' ', oc_po_supplier.last_name) LIKE '%" . $data['supplier_name'] . "%' AND oc_po_supplier.email LIKE '%".$data['supplier_email']."%' AND oc_po_supplier_group.supplier_group_name = '" . $data['supplier_group'] . "' AND oc_po_supplier.date_added = '" . $data['supplier_date_added'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_name'] != '' && $data['supplier_email'] != '' && $data['supplier_group'] != '')
		{
			//$name = explode(' ',$data['supplier_name']);
			$query_string = " AND CONCAT(oc_po_supplier.first_name, ' ', oc_po_supplier.last_name) LIKE '%" . $data['supplier_name'] . "%' AND oc_po_supplier.email LIKE '%".$data['supplier_email']."%' AND oc_po_supplier_group.supplier_group_name = '" . $data['supplier_group'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['supplier_name'] != '' && $data['supplier_email'] != '' && $data['supplier_date_added'] != '')
		{
			//$name = explode(' ',$data['supplier_name']);
			$query_string = " AND CONCAT(oc_po_supplier.first_name, ' ', oc_po_supplier.last_name) LIKE '%" . $data['supplier_name'] . "%' AND oc_po_supplier.email LIKE '%".$data['supplier_email']."%' AND oc_po_supplier.date_added = '" . $data['supplier_date_added'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['supplier_name'] != '' && $data['supplier_group'] != '' && $data['supplier_date_added'] != '')
		{
			//$name = explode(' ',$data['supplier_name']);
			$query_string = " AND CONCAT(oc_po_supplier.first_name, ' ', oc_po_supplier.last_name) LIKE '%" . $data['supplier_name'] . "%' AND oc_po_supplier_group.supplier_group_name = '" . $data['supplier_group'] . "' AND oc_po_supplier.date_added = '" . $data['supplier_date_added'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_email'] != '' && $data['supplier_group'] != '' && $data['supplier_date_added'] != '')
		{
			$query_string = " AND oc_po_supplier.email LIKE '%".$data['supplier_email']."%' AND oc_po_supplier_group.supplier_group_name = '" . $data['supplier_group'] . "' AND oc_po_supplier.date_added = '" . $data['supplier_date_added'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_name'] != '' && $data['supplier_group'] != '')
		{
			//$name = explode(' ',$data['supplier_name']);
			$query_string = " AND CONCAT(oc_po_supplier.first_name, ' ', oc_po_supplier.last_name) LIKE '%" . $data['supplier_name'] . "%' AND oc_po_supplier_group.supplier_group_name = '" . $data['supplier_group'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_name'] != '' && $data['supplier_email'] != '')
		{
			$name = explode(' ',$data['supplier_name']);
			$query_string = " AND CONCAT(oc_po_supplier.first_name, ' ', oc_po_supplier.last_name) LIKE '%" . $data['supplier_name'] . "%' AND oc_po_supplier.email LIKE '%".$data['supplier_email']."%'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_name'] != '' && $data['supplier_date_added'] != '')
		{
			$name = explode(' ',$data['supplier_name']);
			$query_string = " AND CONCAT(oc_po_supplier.first_name, ' ', oc_po_supplier.last_name) LIKE '%" . $data['supplier_name'] . "%' AND oc_po_supplier.date_added = '" . $data['supplier_date_added'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_email'] != '' && $data['supplier_group'] != '')
		{
			$query_string = " AND oc_po_supplier.email LIKE '%".$data['supplier_email']."%' AND oc_po_supplier_group.supplier_group_name = '" . $data['supplier_group'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_email'] != '' && $data['supplier_date_added'] != '')
		{
			$query_string = " AND oc_po_supplier.email LIKE '%".$data['supplier_email']."%' AND oc_po_supplier.date_added = '" . $data['supplier_date_added'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_group'] != '' && $data['supplier_date_added'] != '')
		{
			$query_string = " AND oc_po_supplier_group.supplier_group_name = '" . $data['supplier_group'] . "' AND oc_po_supplier.date_added = '" . $data['supplier_date_added'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
		}
		elseif($data['supplier_name'] != '')
		{
			//$name = explode(' ',$data['supplier_name']);
			$query_string = " AND CONCAT(oc_po_supplier.first_name, ' ', oc_po_supplier.last_name) LIKE '%" . $data['supplier_name'] . "%'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_email'] != '')
		{
			$query_string = " AND oc_po_supplier.email LIKE '%".$data['supplier_email']."%'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_group'] != '')
		{
			$query_string = " AND oc_po_supplier_group.supplier_group_name = '" . $data['supplier_group'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		elseif($data['supplier_date_added'] != '')
		{
			$query_string = " AND oc_po_supplier.date_added = '" . $data['supplier_date_added'] . "'";
			$query_string = $common_query . $query_string;
			$query = $this->db->query($query_string);
			return $query->rows;
			
		}
		else
		{
			return false;
		}
	}
}
?>