<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class Controllermposreconciliation extends Controller {


    public function adminmodel($model) {
      
      $admin_dir = DIR_SYSTEM;
      $admin_dir = str_replace('system/','admin/',$admin_dir);
      $file = $admin_dir . 'model/' . $model . '.php';      
      //$file  = DIR_APPLICATION . 'model/' . $model . '.php';
      $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
      
      if (file_exists($file)) {
         include_once($file);
         
         $this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
      } else {
         trigger_error('Error: Could not load model ' . $model . '!');
         exit();               
      }
   }

   public function index() {
            date_default_timezone_set('Asia/Kolkata');
            $log=new Log("reconciliation-to-bill-tagged-".date('Y-m-d').".log");
            $log->write(' - auto correction of the bills is started');
            $this->adminmodel('report/reconciliation');
            $this->adminmodel('lead/orderleads');
            $this->adminmodel('pos/pos');
            $inv_ids= $this->model_report_reconciliation->get_today_bills();
            $log->write($inv_ids);
           foreach($inv_ids as $inv_idsa)
           {
	//print_r($inv_idsa["order_id"]);
             // print_r($inv_idsa["comment"]);
if(empty($inv_idsa["comment"]))
{
continue;
}
             $get_bill=$this->model_lead_orderleads->getrequisition_to_bil($inv_idsa["comment"]);
             $log->write($get_bill);
             if($get_bill=="")//////means its not in rec tablle
             {
                $log->write('Date time : '.date('Y-m-d h:i:s').' - this bill is not in table so need to insert : '.inv_idsa["comment"].",".$inv_idsa["order_id"]);
                $this->model_pos_pos->RequisitionToBill($inv_idsa["comment"],$inv_idsa["order_id"]);
             }
  
           }
         $log->write(' - auto correction of the bills is closed');
         echo "done";
        }
}
?>