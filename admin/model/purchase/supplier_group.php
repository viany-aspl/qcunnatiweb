<?php
class ModelPurchaseSupplierGroup extends Model {
	public function insert_supplier_group($data)
	{
		if($this->db->query("INSERT INTO oc_po_supplier_group (supplier_group_name,supplier_group_desc) VALUES('" . $data['supplier_group_name'] . "','" . $data['supplier_group_desc'] ."')"))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	public function get_supplier_groups($start,$limit)
	{
		$query = $this->db->query("SELECT * FROM oc_po_supplier_group WHERE delete_bit = ". 0 ." ORDER BY id ASC LIMIT ".$start. "," .$limit);
		return $query->rows;
	}
	public function get_all_supplier_groups()
	{
		$query = $this->db->query("SELECT * FROM oc_po_supplier_group WHERE delete_bit = " . 0);
		return $query->rows;
	}
	
	/*-----------------get_total_count_supplier_group() funtion starts here---------------*/
	
	public function get_total_count_supplier_group()
	{
		$query = $this->db->query("SELECT COUNT(id) AS total_supplier_group FROM oc_po_supplier_group WHERE delete_bit = " . 0);
		return $query->row['total_supplier_group'];
	}
	
	/*-----------------get_total_count_supplier_group() function ends here----------------*/
	
	/*------------------------delete_supplier_group() funtion starts here---------------------*/
	
	public function delete_supplier_group($supplier_group_ids)
	{
		$supplier_group_ids = implode(',', $supplier_group_ids);
		$query = $this->db->query("SELECT COUNT(*) as total FROM oc_po_supplier WHERE delete_bit = 0 AND supplier_group_id IN(".$supplier_group_ids.")");
		if($query->row['total']>0)
		{
			return false;
		}
		else
		{
			$query = $this->db->query("UPDATE oc_po_supplier_group SET delete_bit = ". 1 ." WHERE id IN(".$supplier_group_ids.")");
			if($this->db->countAffected() > 0)
			{
				return true;
			}
		}
	}
	
	/*------------------------delete_supplier_group() funtion starts here---------------------*/
	
	/*-------------------------supplier_group_edit_form function starts here-----------------*/
	
	public function supplier_group_edit_form($supplier_group_id)
	{
		$query = $this->db->query("SELECT * FROM oc_po_supplier_group WHERE id = " . $supplier_group_id);
		return $query->row;
	}
	
	/*-------------------------supplier_group_edit_form fucntion ends here-----------------------*/
	
	
	/*------------------------update supplier group function starts here------------------*/
	
	public function update_supplier_group($update_info)
	{
		$query = $this->db->query("UPDATE oc_po_supplier_group SET supplier_group_name ='" . $update_info['supplier_group_name'] . "', supplier_group_desc = '" . $update_info['supplier_group_desc'] . "' WHERE id = " . $update_info['supplier_group_id']);
		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*------------------------update supplier group function ends here-------------------*/
	
}
?>