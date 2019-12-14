<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerMposfileuploadbill extends Controller {
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
		
                if ($this->request->server['REQUEST_METHOD'] == 'POST')
                { 
		//print_r($_FILES);
		//print_r($this->request->post);
                 $this->adminmodel('tag/order');    
                 $path = "../system/upload/bill/"; 
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
                      //echo "no";exit; 
                   }
                   $new_file_name=$this->request->post['filter_unit'].date('dmy')."_".date('his').".".$file_ext;
                   $file_path=$path.$new_file_name;
                   $move= move_uploaded_file($file_tmp,$file_path);
                   if($move)
                   {
                      
                      $this->model_tag_order->billsubmmision($this->request->post,$new_file_name);
                      $this->session->data['success'] = 'Submitted Successfully';
                      $this->response->redirect($this->url->link('tag/billsubmission', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
                      
                   }
                   else ///////if some error in upload the file
                   {
                      $this->session->data['error_warning'] = 'Oops ! Some error occur, please try again.';
                      
                      
                   }
                 }
                 else ///////if file extensions is not matched
                 {
                    $this->session->data['error_warning'] = 'Oops ! Please check format of the uploaded file, Only pdf,doc,docx,zip,rar,JPEG,JPG,PNG,jpg is allowed';
                    
                       
                 }
              }///////// if file name is not empty end here
 	      else////////data is submit but no file chossen
	      { 
                      
	      }
                }
                else/////////not posted data
                {
                
                }

        }
}
?>