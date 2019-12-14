<?php
error_reporting(1);
class ControllerInvoiceWalletCreditSet extends Controller{
       
        
        public function index()
		{ 
			$this->document->setTitle("enter partner id in url as store_id=example");
			
			$this->load->model('invoice/adjustment');
		
			if (isset($this->request->get['store_id'])) 
			{
				$store_id =  $this->request->get['store_id'];
			}
            if (isset($this->request->get['filter_date'])) 
			{
				$filter_date =  $this->request->get['filter_date'];
			}
                        
                        $filter_data=array(
                            'store_id'=>$store_id,
							
                            'filter_date'=>$filter_date
                        );
						//echo 'here';
				if($store_id!="")
                {
					echo 'Reg date : ';
					echo $regdate= $this->model_invoice_adjustment->getPartnerregdate($store_id);
					echo '<br/>';
					$ledger= $this->model_invoice_adjustment->getPartnerledger($store_id,$regdate);
					foreach($ledger as $ledger2)
					{
						$sid=$ledger2['sid']; //'1947';//
					
						$get_last_credit_wallet_balance=$this->model_invoice_adjustment->get_last_credit_wallet_balance($store_id,$regdate,$sid);
					
						$old_credit=$get_last_credit_wallet_balance['updated_credit'];
						$old_wallet=$get_last_credit_wallet_balance['updated_wallet_balance'];
						if(empty($old_credit))
						{
							$old_credit=0;
						}
						if(empty($old_wallet))
						{
							$old_wallet=0;
						}
						
						if(trim($ledger2['payment_method'])=='PO')
						{
							echo '<br/>';
							echo 'actual_invoice_amount : ';
							echo $get_actual_invoice_amount=$this->model_invoice_adjustment->get_actual_invoice_amount($store_id,$ledger2['order_id']);
							
							$new_credit=$old_credit-$get_actual_invoice_amount;
							$new_wallet=$old_wallet;
							$set_last_credit_wallet_balance=$this->model_invoice_adjustment->set_last_credit_wallet_balance_with_amount($store_id,$regdate,$sid,$new_credit,$new_wallet,$get_actual_invoice_amount);
							
						}
						
						if(trim($ledger2['payment_method'])=='Credit Posting')
						{
							$new_credit=$old_credit+$ledger2['amount'];
							$new_wallet=$old_wallet;
							$set_last_credit_wallet_balance=$this->model_invoice_adjustment->set_last_credit_wallet_balance($store_id,$regdate,$sid,$new_credit,$new_wallet);
						}
						if(trim($ledger2['payment_method'])=='PORETURN')
						{ 
							
							$new_credit=$old_credit+$ledger2['amount'];
							$new_wallet=$old_wallet;
							$set_last_credit_wallet_balance=$this->model_invoice_adjustment->set_last_credit_wallet_balance($store_id,$regdate,$sid,$new_credit,$new_wallet);
							
						}
						if(trim($ledger2['payment_method'])=='Sale Return')
						{ 
							echo '<br/>'.$ledger2['payment_method'].'<br/>';
							echo 'new_credit : ';
							echo '<br/>';
							echo $new_wallet;
							echo '<br/>';
							$new_credit=$old_credit+$ledger2['amount'];
							$new_wallet=$old_wallet;
							$set_last_credit_wallet_balance=$this->model_invoice_adjustment->set_last_credit_wallet_balance($store_id,$regdate,$sid,$new_credit,$new_wallet);
							
						}
						
					}
                }
                echo '<br/>';
				echo 'old_credit : ';
				echo $old_credit;
				echo '<br/>';
				echo 'new_credit : ';
				echo $new_credit;
                //print_r($get_last_credit_wallet_balance);
        
	}
	public function cash()
		{ 
			$this->document->setTitle("enter store_id and user_id in url as store_id=example and user_id=example");
			
			$this->load->model('invoice/adjustment');
		
			if (isset($this->request->get['store_id'])) 
			{
				$store_id =  $this->request->get['store_id'];
			}
            if (isset($this->request->get['filter_date'])) 
			{
				$filter_date =  $this->request->get['filter_date'];
			}
                        
                        $filter_data=array(
                            'store_id'=>$store_id,
							
                            'filter_date'=>$filter_date
                        );
						//echo 'here';
				if($store_id!="")
                {
					echo 'Reg date : ';
					echo $regdate= $this->model_invoice_adjustment->getPartnerregdate($store_id);
					echo '<br/>';
					$ledger= $this->model_invoice_adjustment->getPartnerledger($store_id,$regdate);
					foreach($ledger as $ledger2)
					{
						$sid=$ledger2['sid']; //'1947';//
					
						$get_last_credit_wallet_balance=$this->model_invoice_adjustment->get_last_credit_wallet_balance($store_id,$regdate,$sid);
					
						$old_credit=$get_last_credit_wallet_balance['updated_credit'];
						$old_wallet=$get_last_credit_wallet_balance['updated_wallet_balance'];
						if(empty($old_credit))
						{
							$old_credit=0;
						}
						if(empty($old_wallet))
						{
							$old_wallet=0;
						}
						
						if(trim($ledger2['payment_method'])=='PO')
						{
							echo '<br/>';
							echo 'actual_invoice_amount : ';
							echo $get_actual_invoice_amount=$this->model_invoice_adjustment->get_actual_invoice_amount($store_id,$ledger2['order_id']);
							
							$new_credit=$old_credit-$get_actual_invoice_amount;
							$new_wallet=$old_wallet;
							$set_last_credit_wallet_balance=$this->model_invoice_adjustment->set_last_credit_wallet_balance_with_amount($store_id,$regdate,$sid,$new_credit,$new_wallet,$get_actual_invoice_amount);
							
						}
						
						if(trim($ledger2['payment_method'])=='Credit Posting')
						{
							$new_credit=$old_credit+$ledger2['amount'];
							$new_wallet=$old_wallet;
							$set_last_credit_wallet_balance=$this->model_invoice_adjustment->set_last_credit_wallet_balance($store_id,$regdate,$sid,$new_credit,$new_wallet);
						}
						if(trim($ledger2['payment_method'])=='PORETURN')
						{ 
							
							$new_credit=$old_credit+$ledger2['amount'];
							$new_wallet=$old_wallet;
							$set_last_credit_wallet_balance=$this->model_invoice_adjustment->set_last_credit_wallet_balance($store_id,$regdate,$sid,$new_credit,$new_wallet);
							
						}
						if(trim($ledger2['payment_method'])=='Sale Return')
						{ 
							echo '<br/>'.$ledger2['payment_method'].'<br/>';
							echo 'new_credit : ';
							echo '<br/>';
							echo $new_wallet;
							echo '<br/>';
							$new_credit=$old_credit+$ledger2['amount'];
							$new_wallet=$old_wallet;
							$set_last_credit_wallet_balance=$this->model_invoice_adjustment->set_last_credit_wallet_balance($store_id,$regdate,$sid,$new_credit,$new_wallet);
							
						}
						
					}
                }
                echo '<br/>';
				echo 'old_credit : ';
				echo $old_credit;
				echo '<br/>';
				echo 'new_credit : ';
				echo $new_credit;
                //print_r($get_last_credit_wallet_balance);
        
	}
	
	
}

?>