<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerHrExpense extends Controller {
	private $error = array();

	public function index() { //print_r($this->request->post);exit;
		$this->load->language('tag/order');

		$this->document->setTitle('Expense Book');

                if ($this->request->server['REQUEST_METHOD'] == 'POST')
                { 
		//print_r($_FILES);
		//print_r($this->request->post);
                 $this->load->model('hr/hr');    
                 $path = "../system/upload/hrexpensebill/"; 
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
                   $new_file_name=$this->request->post['filter_store'].date('dmy')."_".date('his').".".$file_ext;
                   $file_path=$path.$new_file_name;
                   $move= move_uploaded_file($file_tmp,$file_path);
                   if($move)
                   {
                      
                      $bill_number=$this->model_hr_hr->billsubmmision($this->request->post,$new_file_name);
                      $this->session->data['success'] = 'Submitted Successfully. Bill number is : '.$bill_number;
                      $this->response->redirect($this->url->link('hr/expense', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
                      
                   }
                   else ///////if some error in upload the file
                   {
                      $this->session->data['error_warning'] = 'Oops ! Some error occur, please try again.';
                      $this->response->redirect($this->url->link('hr/expense', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                      
                   }
                 }
                 else ///////if file extensions is not matched
                 {
                    $this->session->data['error_warning'] = 'Oops ! Please check format of the uploaded file, Only pdf,doc,docx,zip,rar,JPEG,JPG,PNG,jpg is allowed';
                    $this->response->redirect($this->url->link('hr/expense', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                       
                 }
              }///////// if file name is not empty end here
 	      else////////data is submit but no file chossen
	      { 
                      $bill_number=$this->model_hr_hr->billsubmmision($this->request->post,'');
                      $this->session->data['success'] = 'Submitted Successfully. Bill number is : '.$bill_number;
                      $this->response->redirect($this->url->link('hr/expense', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
	      }
                }
                else
                {
                if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 
                if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
                $logged_user_data = $this->user->getId();
                $this->load->model('setting/store');
                $data['stores'] = $this->model_setting_store->getStores();
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $data['logged_user'] = $logged_user_data;
	$mcrypt=new MCrypt();
		//echo $mcrypt->decrypt('d3b4bffdee8fddc369a68cef8cee00d3');    

                $this->response->setOutput($this->load->view('hr/bill_submission.tpl', $data));
                }

        }
}
