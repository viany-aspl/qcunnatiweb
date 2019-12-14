<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(1);
class ControllermposReport extends Controller {
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
   public function gstr1()
   {
   	
		$log=new Log("product_sales_report_gstr1".date('Y-m-d').".log");
		$log->write('GSTR 1 Call');

		unset($this->session->data['filter_date_start']);
		unset($this->session->data['filter_date_end']);
		unset($this->session->data['filter_name_id']);
		unset($this->session->data['store_id']);

                        $log->write($this->request->post);

		$mcrypt=new MCrypt();
                        
		$keys = array(
			'filter_date_start',
			'filter_date_end',
			'filter_name_id',
			'store_id'
		);
 
		
		foreach ($keys as $key) 		
		{
              	                                     		$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;    
 			
        		}	
			$log->write($this->request->post);  	
			$json = array();		
			$data['orders'] = array();

			$filter_data = array(
         			   'filter_store'	     => $this->request->post['filter_store'],
			'filter_date_start'	     => $this->request->post['filter_date_start'],
			'filter_date_end'	     => $this->request->post['filter_date_end'],
			'filter_name'           => $this->request->post['filter_name_id'],
			'start'=>0,
			'limit'=>1000
					);
			$log->write($filter_data);      
			$this->adminmodel('report/product_sale');
			//$this->load->controller("report/product_sales/download_excel");
			           $results = $this->model_report_product_sale->exgetOrders($filter_data);
 
			//print_r($results); exit;
			$file_name="Product_sales_report_".date('dMy').'.xls';
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
		
			  include_once './system/library/PHPExcel.php';
   			 include_once './system/library/PHPExcel/IOFactory.php';
   			 $objPHPExcel = new PHPExcel();
    
  			  $objPHPExcel->createSheet();
    
  			  $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

   			 $objPHPExcel->setActiveSheetIndex(0);

 			   // Field names in the first row
 		
 			$fields = array(
					       'Type ',
  					      'Place Of Supply',
   					     'Rate',
      					  'Taxable Value',
  					     'Cess Amount',
     					'E-Commerce GSTIN'
  				       );
   
    			$col = 0;
  			  foreach ($fields as $field)
  			  {
  					      $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
  					      $col++;
  			  }
     
   			 $row = 2;
    
			    foreach($results as $data)
    			{
      				  $col = 0;
      					  $Total=$data['Total_sales']+$data['Total_tax'];
        					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'E');
       					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'UP');       
       					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['tax_title']);
     					   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Total_tax']);
       					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['totalsum']);
      					  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, '');
    			 	 $row++;
                 			 }


			//print_r($results);
    			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  			  // Sending headers to force the user to download the file
   			 header('Content-Type: application/vnd.ms-excel');
   			 header('Content-Disposition: attachment;filename="Product_sales_report_'.date('dMy').'.xls"');
   			 header('Cache-Control: max-age=0');

 			   $objWriter->save('php://output');
			$log->write($json);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
	}
	
public function gstr2()
   {
   	
		$log=new Log("product_sales_report_gstr2".date('Y-m-d').".log");
$log->write('GSTR 2 Call');
		$log->write($this->request->post);
		unset($this->session->data['filter_date_start']);
		unset($this->session->data['filter_date_end']);
		unset($this->session->data['filter_name_id']);
		unset($this->session->data['store_id']);
		$mcrypt=new MCrypt();
		$keys = array(
			'filter_date_start',
			'filter_date_end',
			'filter_name_id',
			'store_id'
		);
		
		foreach ($keys as $key) 		
		{
              $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;     
$log->write($this->request->post[$key]);       
        }		
		$json = array();		
		$data['orders'] = array();
		$filter_data = array(
            'filter_store'	     => $this->request->post['filter_store'],
			'filter_date_start'	     => $this->request->post['filter_date_start'],
			'filter_date_end'	     => $this->request->post['filter_date_end'],
			'filter_name'           => $this->request->post['filter_name_id'],
			'start'=>0,
			'limit'=>1000
		);
		
		$this->adminmodel('report/product_sale');
		$results = $this->model_report_product_sale->exgetOrders($filter_data);
		
		//$this->load->controller("report/product_sales/download_excel");
	  include_once './system/library/PHPExcel.php';
    include_once './system/library/PHPExcel/IOFactory.php';
	
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
           'Type ',
        'Place Of Supply',
        'Rate',
        'Taxable Value',
        'Cess Amount',
     	'E-Commerce GSTIN'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
    
    foreach($results as $data)
    {
        $col = 0;
        $Total=$data['Total_sales']+$data['Total_tax'];
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'E');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'UP');       
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['tax_title']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Total_tax']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['totalsum']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, '');
        
        
            
        

        $row++;
    }
	//print_r($results);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='product_sales_report_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
	
	//exit;
	$mail             = new PHPMailer();

                $body = "<p>Akshamaala Solution Pvt. Ltd.</p>";
                
                $mail->IsSMTP();
                $mail->Host       = "mail.akshamaala.in";
                                                           
                $mail->SMTPAuth   = false;                 
                $mail->SMTPSecure = "";                 
                $mail->Host       = "mail.akshamaala.in";      
                $mail->Port       = 25;                  
                $mail->Username   = "mis@akshamaala.in";  
                $mail->Password   = "mismis";            

                $mail->SetFrom('accounts@unnati.world', 'Accounts');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Product Sales Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('rohit.kumar@aspltech.com', "RK");
                $mail->AddCC('abhishek.ranjan@aspltech.com', "Abbhiishek Ranjan");
                //$mail->AddBCC('vipin.kumar@aspltech.com', "Vipin");

                $mail->AddAttachment(DIR_UPLOAD.$filename);
                
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                  if(!unlink(DIR_UPLOAD.$filename))
                  {
                      echo ("Error deleting ");
                  }
                  else
                  {
                     echo ("Deleted ");
                  }
                                  
                }
	}
	
	}

?>