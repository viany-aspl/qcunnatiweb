<?php
class ModelInvoiceAdjustment extends Model {
        
	public function getList($data)
	{
                $sql="SELECT
			oc_po_invoice.*
			
			, oc_store.name as store_name
			FROM
			oc_po_invoice
			
                        LEFT JOIN oc_store
                ON (oc_po_invoice.po_store_id = oc_store.store_id)
			
			
                        WHERE oc_po_invoice.sid !='' ";
                
                        if (!empty($data['filter_store']) ) 
                        {
                            $sql .=" and oc_po_invoice.po_store_id='".$data['filter_store']."'";
			
                        }
                        if ($data['filter_type']!="" ) 
                        {
                            $sql .=" and oc_po_invoice.paid_status='".$data['filter_type']."'";
			
                        }
                        
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.=" group by oc_po_invoice.sid ORDER BY oc_po_invoice.sid DESC ";
                
                        if (isset($data['start']) || isset($data['limit'])) {
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
		$sql="select count(*) as total_orders from (SELECT
			oc_po_invoice.*
			
			, oc_store.name as store_name
			FROM
			oc_po_invoice
			LEFT JOIN oc_store
                ON (oc_po_invoice.po_store_id = oc_store.store_id)
			
			
                        WHERE oc_po_invoice.sid !='' ";
                
                        if (!empty($data['filter_store']) ) 
                        {
                            $sql .=" and oc_po_invoice.po_store_id='".$data['filter_store']."'";
			
                        }
                        if ($data['filter_type']!="" ) 
                        {
                            $sql .=" and oc_po_invoice.paid_status='".$data['filter_type']."'";
			
                        }
                        
                        
                        //$sql.=" GROUP BY oc_po_order.id ORDER BY oc_po_order.id DESC ";
                        $sql.=" group by oc_po_invoice.sid ) as aa";
                
                //echo $sql;
                $query = $this->db->query($sql);
		
		return $query->row['total_orders'];
		//return $results['total_orders'];
	}
        public function getInvoiceData($invoice_id)
	{
               $sql="SELECT oc_po_invoice.* from oc_po_invoice WHERE oc_po_invoice.sid ='".$invoice_id."' limit 1 ";
                
                $query = $this->db->query($sql);
		
		return $query->row;
	}
        /////////////////////////////////////////////////
        public function getPartnerInfo($data) {
            $sql="select oc_store.*,(select oc_setting.value from oc_setting where `key`='config_firmname' and oc_store.store_id=oc_setting.store_id) as firm_name from oc_store where store_id='".$data['filter_store']."'  ";
            $query = $this->db->query($sql);
            return $query->row;
            
        }
        public function adjust_invoice($invoice_id) {
            $log=new Log('invoice-adjustment'.date('Y-m-d').'.log');
            //echo $invoice_id;
            $getInvoice=$this->getInvoiceData($invoice_id);
            
            $po_store_id=$getInvoice['po_store_id'];
            $order_total=$getInvoice['order_total'];
            
            $getPartnerInfo=$this->getPartnerInfo(array('filter_store'=>$po_store_id));
            
            $wallet_balance=$getPartnerInfo['wallet_balance'];
            //exit;
            if($wallet_balance>=$order_total)
            {
                
                $sql="update oc_po_invoice set paid_status='1',paid_date='".date('Y-m-d')."' where sid='".$invoice_id."'  ";
                $query = $this->db->query($sql);
                $log->write($sql);
                
                try
                        {
                            $this->load->library('trans');
                            $trans=new trans($this->registry);
                            
                            $trans->addwalletdebit($po_store_id,'2','',$order_total,$invoice_id,'Invoice Adjustment for the Store via web');     
                        } catch (Exception $ex) {
                                $log->write($ex->getMessage());
                        }
                return "";
            }
            else
            {
              return "Available Wallet Balance is low !";  
            }
            
            //print_r($getPartnerInfo);exit;
            
            
        }
        

}
?>