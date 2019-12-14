<?php
class ModelPurchaseCreditNode extends Model 
{
	public function insert_data_manual($data) 
	{
		$log=new Log("supplier_cn-".date('Y-m-d').".log");
		$log->write('insert_data_manual called');
		$log->write($data);
		//$order_info=$this->getInvoice($data['invoice_num'],$data['po_no'],$data['supplier_id']);
		//$log->write('po_no - '.$order_info['po_no']);
		
		//$log->write('cn_status - '.$order_info['cn_status']);
		
		
			$this->load->library('trans');
			$trans=new trans($this->registry);
			$user_id=$this->user->getId();
			
			$sql="insert into  oc_supplier_credit_note set supplier_id='".$data["supplier_id"]."',user_id='".$user_id."',
			po_no='".$data['po_no']."',invoice_num='".$data['invoice_num']."',product_id='".$data['product_id']."',
				invoice_date='".$data['invoice_date']."',cn_no='".$data['cn_no']."',percentage='".$data['percentage']."',
				sgst='".$data['sgst']."',cgst='".$data['cgst']."',sub_total='".$data['sub_total']."',
				round_off='".$data['round_off']."',total='".$data['total']."',grand_total='".$data['grand_total']."' ";
				
            $log->write($sql);
			$query = $this->db->query($sql);
            $insert_id=$this->db->getLastId();
			
			$sql0="insert into  oc_supplier_credit_posting set supplier_id='".$data["supplier_id"]."',user_id='".$user_id."',
			amount='".$data['grand_total']."',transaction_type='CREDIT NOTE',payment_method='CREDIT NOTE',payment_bank='CREDIT NOTE',
			tr_number='".$data['cn_no']."',entry_type='Payment',po_number='".$data['po_no']."',remarks='".$data['remarks']."' ";
            $query = $this->db->query($sql0);
            
			$sql1="update oc_po_supplier set `wallet_balance`=wallet_balance+".$data['grand_total']." where `id`='".$data['supplier_id']."' ";
			$log->write($sql1);
			$userquery = $this->db->query($sql1);
			
			try
            {
                $trans->addsuppliertrans($data['supplier_id'],$data['grand_total'],'CR',$insert_id,'CREDIT NOTE','CREDIT NOTE');  
            } catch (Exception $ex) 
			{
                $log->write($ex->getMessage());
            }
			
		return $insert_id;
		
		
		exit;
	}
	public function insert_data($data) 
	{
		$log=new Log("supplier_cn-".date('Y-m-d').".log");
		$log->write($data);
		$order_info=$this->getInvoice($data['invoice_num'],$data['po_no'],$data['supplier_id']);
		$log->write('po_no - '.$order_info['po_no']);
		
		$log->write('cn_status - '.$order_info['cn_status']);
		
		if($order_info['cn_status']==0)
		{
			$this->load->library('trans');
			$trans=new trans($this->registry);
			$user_id=$this->user->getId();
			
			$sql="insert into  oc_supplier_credit_note set supplier_id='".$data["supplier_id"]."',user_id='".$user_id."',
			po_no='".$data['po_no']."',invoice_num='".$data['invoice_num']."',product_id='".$data['product_id']."',
				invoice_date='".$data['invoice_date']."',cn_no='".$data['cn_no']."',percentage='".$data['percentage']."',
				sgst='".$data['sgst']."',cgst='".$data['cgst']."',sub_total='".$data['sub_total']."',
				round_off='".$data['round_off']."',total='".$data['total']."',grand_total='".$data['grand_total']."' ";
				
            $log->write($sql);
			$query = $this->db->query($sql);
            $insert_id=$this->db->getLastId();
			
			$sql0="insert into  oc_supplier_credit_posting set supplier_id='".$data["supplier_id"]."',user_id='".$user_id."',
			amount='".$data['grand_total']."',transaction_type='CREDIT NOTE',payment_method='CREDIT NOTE',payment_bank='CREDIT NOTE',
			tr_number='".$data['cn_no']."',entry_type='Payment',po_number='".$data['po_no']."',remarks='".$data['remarks']."' ";
            $query = $this->db->query($sql0);
            
			 
			
			$sql1="update oc_po_supplier set `wallet_balance`=wallet_balance+".$data['grand_total']." where `id`='".$data['supplier_id']."' ";
			$log->write($sql1);
			$userquery = $this->db->query($sql1);
			
			$sql12="update oc_supplier_po_invoice set `cn_status`=1 where `invoice_no`='".$data['invoice_num']."' and `po_no`='".$data['po_no']."' ";
			$log->write($sql12);
			$userquery = $this->db->query($sql12);
			
			try
            {
                $trans->addsuppliertrans($data['supplier_id'],$data['grand_total'],'CR',$insert_id,'CREDIT NOTE','CREDIT NOTE');  
            } catch (Exception $ex) 
			{
                $log->write($ex->getMessage());
            }
			
		return $data['invoice_num'];
		}
		else
		{
			$log->write('cn_status - '.$order_info['cn_status'].' so we dont add the order');
			return '0';
		}
		exit;
	}
	public function getInvoices($invoice_number,$supplier_id) 
	{
		$sql="SELECT oc_supplier_po_invoice.*,oc_product_description.name as product_name,oc_supplier_po_order.supplier_id as supplier_id,concat(oc_po_supplier.first_name,' ',oc_po_supplier.last_name) as  supplier_name from  
		oc_supplier_po_invoice 
		left join oc_product_description on oc_supplier_po_invoice.product_id=oc_product_description.product_id
		left join oc_supplier_po_order on oc_supplier_po_invoice.po_no=oc_supplier_po_order.sid
		left join oc_po_supplier on oc_po_supplier.id=oc_supplier_po_order.supplier_id
                                                WHERE oc_supplier_po_invoice.invoice_no like '%" .$invoice_number . "%' ";
		if(!empty($supplier_id))
		{
			$sql.=" and oc_supplier_po_order.supplier_id='".$supplier_id."' ";
		}									
		$sql.=" limit 20 ";
		//echo $sql;
		$order_query = $this->db->query($sql);
        return $order_query->rows;
		
	}
	public function getInvoice($invoice_number,$po_number,$supplier_id) 
	{
		$sql="SELECT oc_supplier_po_invoice.*,oc_product_description.name as product_name,oc_supplier_po_order.supplier_id as supplier_id from  oc_supplier_po_invoice left join oc_product_description
		on oc_supplier_po_invoice.product_id=oc_product_description.product_id
		left join oc_supplier_po_order on oc_supplier_po_invoice.po_no=oc_supplier_po_order.sid
                                                WHERE oc_supplier_po_invoice.invoice_no like'%" .$invoice_number . "%' ";
		if(!empty($po_number))
		{
			$sql.=" and oc_supplier_po_invoice.po_no='".$po_number."' ";
		}
		if(!empty($supplier_id))
		{
			$sql.=" and oc_supplier_po_order.supplier_id='".$supplier_id."' ";
		}
		$sql.=" limit 1";
		//echo $sql;
		$order_query = $this->db->query($sql);
        return $order_query->row;
		
	}
    public function getList($data) 
	{
         $sql="SELECT osc.*,
			op.model as product,
			concat(ps.first_name,ps.last_name) as supplier,
			osi.invoice_date as invoice_date,
			osc.invoice_date as cn_invoice_date,
			osi.sub_total as inv_sub_total,
			osi.grand_total as inv_grand_total
			FROM oc_supplier_credit_note as osc
			left join oc_supplier_po_invoice as osi on osc.po_no=osi.po_no
			LEFT JOIN oc_product as op on op.product_id=osc.product_id
			LEFT JOIN oc_po_supplier as ps on ps.id=osc.supplier_id where osc.sid!=''";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(osc.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(osc.create_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and osc.supplier_id='".$data['filter_supplier']."'";
			
                        }
                        $sql.="ORDER BY osc.sid DESC ";
                
                        if (isset($data['start']) || isset($data['limit'])) 
						{
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
            //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->rows;	
	}
    public function getTotalOrders($data) 
	{
         $sql="SELECT count(osc.sid) as total
			
			FROM oc_supplier_credit_note as osc
			where osc.sid!=''";
                
                       
                        if (!empty($data['filter_date_start']) ) 
                        {
                            $sql .=" and DATE(osc.create_date)>='".$data['filter_date_start']."'";
			
                        }
                        if (!empty($data['filter_date_end']) ) 
                        {
                            $sql .=" and DATE(osc.create_date)<='".$data['filter_date_end']."'";
			
                        }
                        if (!empty($data['filter_supplier']) ) 
                        {
                            $sql .=" and osc.supplier_id='".$data['filter_supplier']."'";
			
                        }
            $query = $this->db->query($sql);
		
		return $query->row['total'];	
	}   
    
	// Function to get the client IP address
	public function get_client_ip() 
	{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
	
}