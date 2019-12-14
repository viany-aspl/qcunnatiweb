<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);
date_default_timezone_set("Asia/Calcutta");

class ControllerMposUploadbillexpense extends Controller {
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
	public function upload() { 
                $log=new Log("expense-".date('Y-m-d').".log");
                $log->write('just called the service');
                if ($this->request->server['REQUEST_METHOD'] == 'POST')
                { 
                  
	    $log->write($this->request->post);
                  $mcrypt=new MCrypt();
                  $this->request->post['billid'] = $mcrypt->decrypt($this->request->post['billid']);
                  $this->request->post['file_action'] = $mcrypt->decrypt($this->request->post['file_action']);
                  $log->write($this->request->post);
                  $log->write(@$_FILES);
	    //print_r($_FILES);
	   //print_r($this->request->post);
                 
                 if($this->request->post['file_action']=="expense")
                  {
                    $path = "/var/www/html/unnati/shop/system/upload/expensebill/"; 
                    
                  }
                  elseif($this->request->post['file_action']=="taggedbill")
                  {
                    $path = "/var/www/html/unnati/shop/system/upload/bill/"; 
                  }
	    elseif($this->request->post['file_action']=="cash")
                  {
                    $path = "/var/www/html/unnati/sugar/system/upload/cashbankslip/"; 
                    
                  }
                  $log->write(@$path);
                 $file_extensions= array("pdf","doc","docx","zip","rar",'jpeg','jpg','png');
                 
                 $file_name = @$_FILES['file']['name'];

                 $file_size =@$_FILES['file']['size'];
                 $file_tmp =@$_FILES['file']['tmp_name'];
                 $file_type=@$_FILES['file']['type'];
	         $arrrr=explode('.',$file_name); 
                 $exttt=end($arrrr);
                 $file_ext= strtolower($exttt);
                 if($file_name!="")
	         {
                 if(in_array($file_ext, $file_extensions)) 
                 { 
                    
                  if(is_writable($path))
                   {
                    //echo "yes";exit;
                   }
                   else 
                   {
                      $data=array('status'=>'failure','msg'=>'Oops ! directory not writable, please try again.');
                      $log->write('Oops ! directory not writable, please try again.');
                   }
                   $new_file_name=$this->request->post['billid'].'_'.date('dmy')."_".date('his').".".$file_ext;
                   $file_path=$path.$new_file_name;
                   $move= move_uploaded_file($file_tmp,$file_path);
                   if($move)
                   {
                      if($this->request->post['file_action']=="expense")
                      {
                        $this->adminmodel('expense/expense'); 
                        $this->model_expense_expense->billsubmmision_update_file($this->request->post,$new_file_name);
                        $data=array('status'=>'success','msg'=>'Submitted Successfully');
                        $log->write('Submitted Successfully');
                      }
                      elseif($this->request->post['file_action']=="taggedbill")
                      {
                       $this->adminmodel('tag/order');    
                       $this->model_tag_order->billsubmmision_update_file($this->request->post,$new_file_name);
                       $data=array('status'=>'success','msg'=>'Submitted Successfully');
                       $log->write('Submitted Successfully');
	        }
	        elseif($this->request->post['file_action']=="cash")
                      {
                       $this->adminmodel('runner/cash');    
                       $this->model_runner_cash->cashsubmmision_update_file($this->request->post,$new_file_name);
                       $data=array('status'=>'success','msg'=>'Submitted Successfully');
                       $log->write('Submitted Successfully');
	        }
                      else
                      {
                        $data=array('status'=>'failure','msg'=>"Oops ! Dont know where to save the file");
                        $log->write('Oops ! Dont know where to save the file');
                      }
                      
                      
                      
                   }
                   else ///////if some error in upload the file
                   {
                      
                      $data=array('status'=>'failure','msg'=>'Oops ! Some error occur, please try again.');
                      $log->write('Oops ! Some error occur, please try again.');
                      
                   }
                 }
                 else ///////if file extensions is not matched
                 {
                   
                    $data=array('status'=>'failure','msg'=>'Oops ! Please check format of the uploaded file, Only pdf,doc,docx,zip,rar,JPEG,JPG,PNG,jpg is allowed');
                    $log->write('Oops ! Please check format of the uploaded file, Only pdf,doc,docx,zip,rar,JPEG,JPG,PNG,jpg is allowed');
                 }
              }///////// if file name is not empty end here
 	      else////////data is submit but no file chossen
	      { 
                      $data=array('status'=>'failure','msg'=>'data is submit but no file upload');
                      $log->write('data is submit but no file upload');
	      }
                }
                else/////////not posted data
                {
                  $data=array('status'=>'failure','msg'=>'not posted data');
                  $log->write('not posted data');
                }
                $this->response->setOutput(json_encode($data));   
        }
}
?>