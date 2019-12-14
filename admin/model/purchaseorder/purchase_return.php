<?php
class ModelPurchaseOrderPurchaseReturn extends Model {

	public function getList($data)
	{ 
            $sql="SELECT concat(ps.first_name,ps.last_name) as supplier,ps.gst as supplier_gstn,oc_supplier_po_return.*,os.name as warehouse FROM oc_supplier_po_return 
LEFT JOIN oc_store as os on os.store_id=oc_supplier_po_return.warehouse_id
LEFT JOIN oc_po_supplier as ps on ps.id=oc_supplier_po_return.supplier_id where oc_supplier_po_return.sid!='' ";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_return.valid_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_return.valid_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_return.supplier_id='".$data['filter_supplier']."'";
			
                        }
                       
                        $sql.="ORDER BY oc_supplier_po_return.sid DESC ";
                
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
		
		return $query->rows;
	}
	public function getTotalOrders($data)
	{
		$sql="select count(*) as total from (SELECT oc_supplier_po_return.* FROM oc_supplier_po_return where oc_supplier_po_return.sid!='' ";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_return.valid_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(oc_supplier_po_return.valid_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and oc_supplier_po_return.supplier_id='".$data['filter_supplier']."'";
			
                        }
             $sql.=" ) as aa"; 
		$query = $this->db->query($sql);			 
		return $query->row['total'];
		
	}

        public function submit_purchase_return($data = array()) 
		{
			//print_r($data);exit;//$data['p_tax_rate']
            $log=new Log('supplier-'.date('Y-m-d').'.log');
           
			$sqlget="select quantity from  " . DB_PREFIX . "product_to_store  WHERE product_id = '" . (int)$data['product_id'] . "' AND store_id = '".(int)$data['received_store']."'";
	        $log->write($sqlget);
	        $query1=$this->db->query($sqlget);
			$query1->row['quantity'];
			$db_qnty=$query1->row['quantity'];
			//print_r($data);
			//exit;
			if($data['no_ware_house']==1)
			{
				$db_qnty=$data['p_qnty'];
			}
			if($db_qnty>=$data['p_qnty'])
			{
			$sql3="insert into oc_supplier_po_return set supplier_id='".$data['filter_supplier']."',warehouse_id='".$data['received_store']."',
			invoice_no='".$data['invoiceno']."',valid_date='".$data['filter_date']."',
			`product_id`='".$data['product_id']."',`rate`='".$data['p_price']."',`quantity`='".$data['p_qnty']."',
			`unit`='".$data['p_unit']."',`discount`='".$data['p_discount']."',`amount`='".$data['p_amount']."',
			`rebate`='".$data['transport_charge']."',`grand_total`='".$data['grand_total']."',`create_time`=NOW(),`product_hsn`='".$data['product_hsn'][0]."',
			`tax_type`='".$data['p_tax_type']."',`tax_rate`='".(($data['span_cgst_1']+$data['span_sgst_1'])/$data['p_qnty'])."',`product_name`='".$data['product_name'][0]."',
			`cgst_type`='".$data['span_cgst_type_1']."',`cgst_value`='".$data['span_cgst_1']."',`sgst_type`='".$data['span_sgst_type_1']."',
			`sgst_value`='".$data['span_sgst_1']."',`received_prn`='".$data['received_prn']."' ";

            $query = $this->db->query($sql3);
            $insert_id=$this->db->getLastId();
			
			$sql2="update  oc_po_supplier set wallet_balance=wallet_balance +'".$data['grand_total']."' where id='".$data['filter_supplier']."'";
            $query2 = $this->db->query($sql2);
            $log->write($sql2);
            try
            {
                $this->load->library('trans');
                $trans=new trans($this->registry);
                $trans->addsuppliertrans($data['filter_supplier'],$data['grand_total'],'CR',$insert_id,'Supplier Return','');  
            } 
			catch (Exception $ex) 
			{
                $log->write($ex->getMessage());
            }
			if($data['no_ware_house']==1)
			{
			$sqlpdeduct="UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$data['p_qnty'] . ") WHERE product_id = '" . (int)$data['product_id'] . "' AND subtract = '1'";
	        $log->write($sqlpdeduct);
	        $this->db->query($sqlpdeduct);

            $sqlpdeduct2="UPDATE " . DB_PREFIX . "product_to_store SET quantity = (quantity - " . (int)$data['p_qnty'] . ") WHERE product_id = '" . (int)$data['product_id'] . "' AND store_id = '".(int)$data['received_store']."'";
	        $log->write($sqlpdeduct2);
	        $this->db->query($sqlpdeduct2);

			try
			{ 
                $this->load->library('trans');
                $trans=new trans($this->registry);
                $trans->addproducttrans($data['received_store'],$data['product_id'],$data['p_qnty'],$insert_id,'DB','Supplier Return','web');  
            }
			catch (Exception $e)
			{
                $log->write($e->getMessage());
            }
			}
			return $insert_id;
			}
			else
			{
				return 0;
			}
                     
        }
        public function get_prn_list($data)
        	{
           		$sql="SELECT oc_supplier_po_invoice.invoice_no as invoice_no,oc_supplier_po_invoice.product_id as product_id,
						oc_supplier_po_invoice.Quantity as Quantity,oc_supplier_po_invoice.create_date as order_date,
						oc_supplier_po_order.sid as po_id,opd.name as product_name FROM oc_supplier_po_order 
						left join oc_supplier_po_invoice on oc_supplier_po_invoice.po_no=oc_supplier_po_order.sid
						left join oc_product_description as opd on oc_supplier_po_invoice.product_id=opd.product_id 

				WHERE oc_supplier_po_invoice.status=1  ";
			if (!empty($data['store_id'])) 
			{
			$sql .= " and  oc_supplier_po_order.supplier_id = '".$data['store_id']."' ";
			}
			if (!empty($data['product_id'])) 
			{
			$sql .= " and oc_supplier_po_invoice.product_id = '".$data['product_id']."' "; 
			}
			$sql.=" order by oc_supplier_po_order.sid desc ";
			echo $sql;
           $query = $this->db->query($sql);
           return $query->rows;
           
        }
}
?>