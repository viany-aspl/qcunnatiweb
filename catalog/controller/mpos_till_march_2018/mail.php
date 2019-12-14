<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');

ini_set("display_errors", "On");
error_reporting(E_ALL ^ E_NOTICE);
class Controllermposmail extends Controller {


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

   public function email_cash_report() {
           
        $this->adminmodel('report/cash');
        $data['orders'] = array();

        $filter_data = array(
            'filter_date_start'         => date('Y-m-d'),
            'filter_date_end'         => date('Y-m-d')
            
        );

        $data['orders'] = array();

        $results = $this->model_report_cash->getCash_report($filter_data);
                
    include_once DIR_SYSTEM .'library/PHPExcel.php';
    include_once DIR_SYSTEM .'library/PHPExcel/IOFactory.php';

    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Store Name ',
        'Bank',
        'Date',
        'Amount'
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
    
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['bank_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, date('Y-m-d',strtotime($data['date_added'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['amount']);

        $row++;
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='cash_report_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    //
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

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Cash Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		//$mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");
		
                

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
      public function email_sale_order() {
        
        

				
		$this->adminmodel('report/sale');
                
		$data['orders'] = array();

		$filter_data = array(
                        'filter_store'	     => '',
			'filter_date_start'	     => date('Y-m-d'),
			'filter_date_end'	     => date('Y-m-d'),
			
			'filter_order_status_id' => '5'
		);

		

		$results = $this->model_report_sale->getOrders($filter_data);

		

        
    include_once DIR_SYSTEM .'library/PHPExcel.php';
    include_once DIR_SYSTEM .'library/PHPExcel/IOFactory.php';

    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Date Start',
        'Date End',
        'No. Orders',
        'Store',
        'Total'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    // Fetching the table data
    //$this->load->model('report/searchattendance');
    //$results = $this->model_report_searchattendance->getmdoattendance($filter_data);
    
    $row = 2;
    
    foreach($results as $data)
    { 
        $col = 0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('Y-m-d',strtotime($data['date_start'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('Y-m-d',strtotime($data['date_end'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['orders']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['total']);            
        

        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    // Sending headers to force the user to download the file
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="tagged_report_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
    $filename='sale_order_report_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    //
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
		//$mail->From = "mail.akshamaala.in";
		//$mail->FromName = "Support Team";
                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Sale orders Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		//$mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");
                

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
    public function email_product_sales() {
           
    		


		$this->adminmodel('report/product_sale');
                
		$data['orders'] = array();

		$filter_data = array(
                        'filter_date_start'	     => date('Y-m-d'),
			'filter_date_end'	     => date('Y-m-d')
		);

		$order_total = $this->model_report_product_sale->getTotalOrders($filter_data);

		$results = $this->model_report_product_sale->getOrders($filter_data);
        

        

                
    include_once DIR_SYSTEM .'library/PHPExcel.php';
    include_once DIR_SYSTEM .'library/PHPExcel/IOFactory.php';

    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        'Sale Date ',
        'Product Name',
        'Store',
        'No of Orders',
        'Total Sales',
        'Total Tax',
        'Total (Sales + Tax)'
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
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('Y-m-d',strtotime($data['dats'])));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['No_of_orders']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, number_format((float)$data['Total_sales'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, number_format((float)$data['Total_tax'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, number_format((float)$Total, 2, '.', ''));
        
        
            
        

        $row++;
    }               

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='product_sales_report_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    //
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

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Product Sales Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		//$mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");
                

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

       public function email_inventory_report(){
        
$this->adminmodel('report/Inventory');
$data['orders'] = array();

 $results = $this->model_report_Inventory->getInventory_report($filter_data);
             
        
    include_once DIR_SYSTEM .'library/PHPExcel.php';
    include_once DIR_SYSTEM .'library/PHPExcel/IOFactory.php';

    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        'Product ID',
        'Product Name',
        'Store name',
        'Qnty',
        'Price',
        'Amount'
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
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['price']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $data['Amount']);
        
        $row++;
    }

    

    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="Inventory_report_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
    $filename='inventory_report_'.date('ymdhis').'.xls';
	$results = $this->model_report_Inventory->getInventory_report($filter_data);
    $objWriter->save(DIR_UPLOAD.$filename );
    //
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

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Inventory Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
               $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
               $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
	$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
	$mail->AddBCC('vipin.kumar@aspltech.com', "Vipin");
              $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");
                
                

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
/*
public function email_sale_summary() { 
           $this->adminmodel('report/sale_summary');
        $order_total = $this->model_report_sale_summary->getTotalSale($filter_data);

		$data['orders'] = array();

		$results = $this->model_report_sale_summary->getSale_summary($filter_data);
                
		                
    include_once DIR_SYSTEM .'library/PHPExcel.php';
    include_once DIR_SYSTEM .'library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
        
        
        'Store Name',
	'Cash',
	'Tagged',
        'Total'
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
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['Cash']); 
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['Tagged']); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, ($data['Cash']+$data['Tagged']));
             
   
        $row++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename='sale_summary_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    //
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

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Sale Summary";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		//$mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");
                
                

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
   */

   public function email_summary() { 
            $day1=date('Y-m-d');
            
            $day2 = date('Y-m-d', strtotime($day1 . ' -1 day'));
            $day3 = date('Y-m-d', strtotime($day2 . ' -1 day'));
            $day4 = date('Y-m-d', strtotime($day3 . ' -1 day'));
            $day5 = date('Y-m-d', strtotime($day4 . ' -1 day'));
            $day6 = date('Y-m-d', strtotime($day5 . ' -1 day'));
            $day7 = date('Y-m-d', strtotime($day6 . ' -1 day'));



            $filter_data = array(
            'filter_date_start'         => $day7,
            'filter_date_end'         => $day1
        );
            
           $this->adminmodel('setting/store');
           $this->adminmodel('report/sale_summary');
        
        $data['orders'] = array();

                        
                    
    include_once DIR_SYSTEM .'/library/PHPExcel.php';
    include_once DIR_SYSTEM .'/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    
    
    // Field names in the first row
    $fields = array(
        
        
        
    $day1,
        '',
    $day2,
        '',
        $day3,
        '',
        $day4,
        '',
        $day5,    
        '',
        $day6,
        '',
        $day7,
        ''
    );
   
    $col = 3;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Store Name');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'MTD');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, '');
    $fields2 = array(
        
        '',
    'Cash',
        'Tagging',
    'Cash',
        'Tagging',
        'Cash',
        'Tagging',
        'Cash',
        'Tagging',
        'Cash',    
        'Tagging',
        'Cash',
        'Tagging',
        'Cash',
        'Tagging',
        'Cash',
        'Tagging'
    );
   
    $col2 = 0;
    foreach ($fields2 as $field2)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 2, $field2);
        $col2++;
    }
    
    $row=1;
         $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$row.':C'.$row);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$row.':E'.$row);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$row.':G'.$row);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$row.':I'.$row);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.$row.':K'.$row);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.$row.':M'.$row);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('N'.$row.':O'.$row);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('P'.$row.':Q'.$row);
        
   $row = 3;
   
   $get_stores= $this->model_setting_store->getStores();
   
    foreach($get_stores as $stores)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $stores['name']);
        
        $day1_cashmtd=$this->model_report_sale_summary->getcashmonth($stores['store_id']);
        $day1_taggedmtd=$this->model_report_sale_summary->gettaggedmonth($stores['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $day1_cashmtd["Cash"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $day1_taggedmtd["Tagged"]);

        $day1_cash=$this->model_report_sale_summary->getcash($stores['store_id'],$day1);
        $day1_tagged=$this->model_report_sale_summary->gettagged($stores['store_id'],$day1);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $day1_cash["Cash"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $day1_tagged["Tagged"]);
        
        $day2_cash=$this->model_report_sale_summary->getcash($stores['store_id'],$day2);
        $day2_tagged=$this->model_report_sale_summary->gettagged($stores['store_id'],$day2);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $day2_cash["Cash"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $day2_tagged["Tagged"]);
        
        $day3_cash=$this->model_report_sale_summary->getcash($stores['store_id'],$day3);
        $day3_tagged=$this->model_report_sale_summary->gettagged($stores['store_id'],$day3);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $day3_cash["Cash"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $day3_tagged["Tagged"]);
        
        $day4_cash=$this->model_report_sale_summary->getcash($stores['store_id'],$day4);
        $day4_tagged=$this->model_report_sale_summary->gettagged($stores['store_id'],$day4);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $day4_cash["Cash"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $day4_tagged["Tagged"]);
        
        $day5_cash=$this->model_report_sale_summary->getcash($stores['store_id'],$day5);
        $day5_tagged=$this->model_report_sale_summary->gettagged($stores['store_id'],$day5);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $day5_cash["Cash"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $day5_tagged["Tagged"]);
        
        $day6_cash=$this->model_report_sale_summary->getcash($stores['store_id'],$day6);
        $day6_tagged=$this->model_report_sale_summary->gettagged($stores['store_id'],$day6);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $day6_cash["Cash"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $day6_tagged["Tagged"]);
        
        $day7_cash=$this->model_report_sale_summary->getcash($stores['store_id'],$day7);
        $day7_tagged=$this->model_report_sale_summary->gettagged($stores['store_id'],$day7);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $day7_cash["Cash"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, $day7_tagged["Tagged"]);
        
        //print_r($day1_cash["Cash"]);
        $row++;
    }
    
   
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="sale_summary_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

     //$objWriter->save('php://output');
     //exit;
    
    $filename='sale_summary_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    
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

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Sale Summary";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		//$mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");                
                

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
 

        public function email_product_report() {
            
           $this->adminmodel('setting/store');
           $this->adminmodel('report/product_report');
                       
    include_once DIR_SYSTEM .'/library/PHPExcel.php';
    include_once DIR_SYSTEM .'/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    $get_stores= $this->model_setting_store->getStores();
    
    $fields=array();
    
    foreach($get_stores as $stores)
    {
       array_push($fields,$stores['name']);
    
    }

    
    $col = 1;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col=$col+2;
    }
    $col2=1;
    $col3=2; 
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 2, 'Today Sales(Amount) - (Qnty)');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col3, 2, 'EOD Inventory');
        $col2=$col2+2;
        $col3=$col3+2;
    }
    $x = 65;
    
    foreach ($fields as $field)
    {
    $row=1;
    $y=chr(++$x);
    $n=chr(++$x);
    if($n=="Y")
    {
       $inc="1";
       $x = 65;
    }
    if(($n=="Y") && ($inc=="1"))
    {
       $inc2="2";
       $x = 65;
    }
    //echo $n;
    if($inc=="1")
    {
        //echo "A".$y.$row.':'."A".$n.$row;
       $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$y.$row.':'."A".$n.$row);
    }
    if($inc2=="2")
    {
        //echo "B".$y.$row.':'."B".$n.$row;
       $objPHPExcel->setActiveSheetIndex(0)->mergeCells("B".$y.$row.':'."B".$n.$row);
    }

    else
    {
       $objPHPExcel->setActiveSheetIndex(0)->mergeCells($y.$row.':'.$n.$row);  
    }
    
    
    }
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("X".$row.':Y'.$row);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("Z".$row.':AA'.$row);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("AZ".$row.':BA'.$row);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("BZ".$row.':CA'.$row);
    //print_r($get_stores);
    //echo $inc."<br/>".$inc2;
    //exit;
    $getproducts = $this->model_report_product_report->getproducts();

    $row = 3;
    foreach($getproducts as $products)
    {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $products['model']);
    $coll=1;
    foreach($get_stores as $stores)
    {
        $coll2=$coll+1;
        $getproducts = $this->model_report_product_report->getproductquantitybystore($stores["store_id"],$products["product_id"]);
        $getsale = $this->model_report_product_report->getsale($stores["store_id"],$products["product_id"]);
        
        if($getsale["total"]!="")
        {
          $getsale_qnty = $this->model_report_product_report->getsale($stores["store_id"],$products["product_id"]);
          $qnty=" - (".$getsale_qnty["quantity"].")";
        }
        else
        {
          $qnty="";
        }
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll2, $row, $getproducts["quantity"]);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll, $row, number_format((float)$getsale["total"], 2, '.', '').$qnty);
        
        
        $coll=$coll+2;
    }
    //print_r($products);
    
    $row++;
    }
    
    //exit;
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="product_wise_summary_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

    //$objWriter->save('php://output');
    
    //exit;
    $filename='product_wise_summary_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    
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

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Product Wise Summary";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		//$mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");                

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

  public function dscl_input_sales() 
  {
            $this->adminmodel('setting/store');
           $this->adminmodel('report/dscl_input_sales');
                       
    include_once DIR_SYSTEM .'/library/PHPExcel.php';
    include_once DIR_SYSTEM .'/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle("Sales");
    //$get_stores= $this->model_setting_store->getStores();
    $today=date('Y-m-d');
    $get_stores=$this->model_report_dscl_input_sales->getstores();
    $fields=array('',
        '',
        $today,
        '',
        'To Date',
        ''
        
    );
    
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col=$col+1;
    }
    $fields2=array('Factory Unit',
        'Store Name',
        'Cash',
        'Tagging',
        'Cash',
        'Tagging'
        
    );
    $col2 = 0;
    foreach ($fields2 as $field2)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 2, $field2);
        $col2=$col2+1;
    }
    $row=1;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("C".$row.':D'.$row);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("E".$row.':F'.$row);
    
    $row=3;
    $coll=1;
    
    //$today='2017-02-22';
    foreach($get_stores as $stores)
    {  
       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll, $row, $stores["name"]);
       
       $today_cash=$this->model_report_dscl_input_sales->getcash($stores['store_id'],$today);
       $today_tagged=$this->model_report_dscl_input_sales->gettagged($stores['store_id'],$today);
       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, number_format((float)$today_cash["Cash"], 2, '.', ''));
       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, number_format((float)$today_tagged["Tagged"], 2, '.', ''));
       
       $till_date_cash=$this->model_report_dscl_input_sales->getcashtilldate($stores['store_id']);
       $till_date_tagged=$this->model_report_dscl_input_sales->gettaggedtilldate($stores['store_id']);
       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, number_format((float)$till_date_cash["Cash"], 2, '.', ''));
       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, number_format((float)$till_date_tagged["Tagged"], 2, '.', ''));
       $row++;
    }
    //number_format((float)$number, 2, '.', '')
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'CCE5FF'
        )
    ));
    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => 'CCE5FF'
        )
    ));
    
    
    /*
    $styleArray = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
    );
   */
    /////////////2nd sheet start from here///////////
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("Stock Position")->setDescription("Stock Position");

    $objPHPExcel->setActiveSheetIndex(1); 
    $objPHPExcel->getActiveSheet()->setTitle("Stock Position");
    
    $this->adminmodel('report/product_report');
    $getproducts = $this->model_report_dscl_input_sales->getproducts();
    $product_fields=array();
    foreach($getproducts as $products)
    { 
        array_push($product_fields,$products["model"]);
    
    }
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Factory Unit');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Store Name');
            
    $col2 = 2;
    foreach ($product_fields as $product_field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 1, $product_field);
        $col2=$col2+1;
    }
    
    $row = 2;
    
    foreach($get_stores as $stores)
    {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $stores['name']);
    $coll222=2;
    $getproducts = $this->model_report_dscl_input_sales->getproducts();
    foreach($getproducts as $products2)
    {   
        $getproducts = $this->model_report_dscl_input_sales->getproductquantitybystore($stores["store_id"],$products2["product_id"]);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll222, $row, $getproducts["quantity"]);
        
        $coll222++;
        
    } 
    $coll222=2;
    $row++;
    }
    /////////////2nd sheet end from here///////////
    /////////////3rd sheet start from here///////////
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("Stock Position")->setDescription("Stock Position");

    $objPHPExcel->setActiveSheetIndex(2); 
    $objPHPExcel->getActiveSheet()->setTitle("Product Wise Sales");
    
    $this->adminmodel('report/product_report');
    $getproducts = $this->model_report_dscl_input_sales->getproducts();
    $product_fields=array();
    foreach($getproducts as $products)
    { 
        array_push($product_fields,$products["model"]);
    
    }
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Factory Unit');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Store Name');
            
    $col2 = 2;
    foreach ($product_fields as $product_field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col2, 1, $product_field);
        $col2=$col2+1;
    }
    
    $row = 2;
    $get_stores=$this->model_report_dscl_input_sales->getstores();
    foreach($get_stores as $stores)
    {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $stores['name']);
    $coll222=2;
    $getproducts = $this->model_report_dscl_input_sales->getproducts();
    foreach($getproducts as $products2)
    {   
        $getproductsales = $this->model_report_dscl_input_sales->getsale($stores["store_id"],$products2["product_id"]);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($coll222, $row, $getproductsales["quantity"]);
        
        $coll222++;
        
    } 
    $coll222=2;
    $row++;
    }
    /////////3rd sheet end here//////////
  
    //exit;
    $objPHPExcel->setActiveSheetIndex(0); 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    
    //header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="dscl_input_sales_'.date('dMy').'.xls"');
    //header('Cache-Control: max-age=0');

   // $objWriter->save('php://output');
    
   // exit;
    $filename='dscl_input_sales_'.date('ymdhis').'.xls';
    $objWriter->save(DIR_UPLOAD.$filename );
    
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

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "DSCL Input Sales";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		//$mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('chetan.singh@akshamaala.com', "Chetan Singh");                

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
  public function email_today_tagged_order_pdf()
        {
                require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
                $filter_data = array(
			
			'filter_date_added'    => date('Y-m-d', strtotime("-1 days")),
			
			'sort'                 => $sort,
			'order'                => $order
			
		);
//echo date('Y-m-d', strtotime("-1 days"));exit; 
$this->adminmodel('tag/order');
//$order_total = $this->model_tag_order->getTotalOrders($filter_data);
//exit;
		$results = $this->model_tag_order->getOrdersMailPdf($filter_data);
                $total_amount=0; 
		foreach ($results as $result) { //print_r($result);
 
                       

         $data['products'] = array();

			$products = $this->model_tag_order->getOrderProducts($result['order_id']);
                        $product_info_string="";
			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_tag_order->getOrderOptions($data['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL')
							);
						}
					}
				}

				$data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
                           if($product_info_string=="")
                           {
                             $product_info_string.=$product['name']." - (".$product['quantity'].")";
                           }
                           else
                           {
                            
                               $product_info_string.=", ".$product['name']." - (".$product['quantity'].")";
                           }
			}

			$data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
                                                        'telephone'     => $result['telephone'],
                                                        'store_name'    => $result['store_name'],
				'total'         => $result['total'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_potential' => date($this->language->get('date_format_short'), strtotime($result['date_potential'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('tag/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'edit'          => $this->url->link('tag/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'delete'        => $this->url->link('tag/order/delete', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                                'products_info' => $product_info_string

			);
                        $total_amount=$total_amount+$result['total'];
		}

		$data["total_amount"]=$total_amount;


              //  $html = $this->load->view('tag/today_tagged_order_pdf.tpl',$data);
               
                $base_url = HTTP_CATALOG;
                
                
                $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
                $header = '<div class="header" style="">
                   
<div class="logo" style="width: 100%;" >
<img src="https://unnati.world/shop/image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="https://unnati.world/shop/image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />

                         </div>
<img src="https://unnati.world/shop/image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 

</div>';
    
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="https://unnati.world/shop/image/letterhead_bottomline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 
                        <div class="address"><img src="https://unnati.world/shop/image/letterhead_footertext.png" style="height: 10px; margin-left: -40px;width: 120% !important;" /> </div>'
                        . '</div>';

                
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->setAutoBottomMargin = 'stretch';

		//$html='testing';
 $html='<div id="content">
 
  <div class="container-fluid">
    <div class="panel panel-default">
      
      <div class="panel-body">
       
          <style>
             td,th{border: 1px solid silver;text-align: center; }
              </style>
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered" style="width: 95%;">
            <thead>
              <tr>
                <th class="text-left">Sl.No.</th>
                <th class="text-left">Requisition id</th>
                <th class="text-right">Customer (Grower ID-Farmer name-Father/Husband name)</th>
                <th class="text-right">Status</th>
                <th  class="text-right">Mobile Number</th>
                <th  class="text-right">Store name</th>
	        <th  class="text-right">Total</th>
                <th class="text-right">Create Date</th>
                <th class="text-right">Expected Date of delivery</th>
                <th class="text-right">Circle code</th>
                <th class="text-right">Tagged products info</th>
                
              </tr>            </thead>
            <tbody>';
              if ($data['orders']) { $aa=1; $total=0; 
              foreach ($data['orders'] as $order) { //print_r($order["products_info"]); 
                if($product_info_string=="")
                           {
                             $product_info_string.=$product['name']." - (".$product['quantity'].")";
                           }
                           else
                           {
                            
                               $product_info_string.=", ".$product['name']." - (".$product['quantity'].")";
                           }
              
              $html.='<tr>
                <td class="text-left">'.$aa.'</td>
                <td class="text-left">'.$order['order_id'].'</td>
                <td class="text-right">'.$order['customer'].'</td>
                <td class="text-right">'.$order['status'].'</td>
                <td class="text-right">'.$order['telephone'].'</td>
	        <td class="text-right">'.$order['store_name'].'</td>
                <td class="text-right">'.number_format((float)$order['total'], 2, '.', '').'</td>
                <td class="text-right" >'.$order['date_added'].'</td>
                <td class="text-right">'.$order['date_potential'].'</td>
                <td class="text-right">'.$order['shipping_code'].'</td>
               
                <td class="text-right">'.$order["products_info"].'</td>
              </tr> ';   
             
              
              $aa++; } 

              
              $html.='<tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                
                <td class="text-right" style="text-align: right;"><b>Total : </b></td>
                
                <td class="text-right" style="text-align: right;">'.number_format((float)$total_amount, 2, '.', '').'</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
              </tr>';  
               } else { 
              $html.='<tr>
                <td class="text-center" colspan="6">no results</td>
              </tr>';
               } 
            $html.='</tbody>
          </table>
        </div>


      </div>
    </div>
  </div>
  </div> 
 ';
                $mpdf->WriteHTML($html);

                $filename='tagged_orders_report'.date('Y-m-d').'.pdf';
                $mpdf->Output(DIR_UPLOAD.'tagged_pdf/'.$filename,'F');
                //$mpdf->Output($filename,'D');
               // exit;
                //
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

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Tagged orders Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('chetan.singh@akshamaala.com', "Chetan Singh");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
		$mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
		//$mail->AddCC('rakesh.aggarwal@aspltech.com', "Rakesh Aggarwal");
                $mail->AddBCC('vipin.kumar@aspltech.com', "Vipin"); 
                
                

                $mail->AddAttachment(DIR_UPLOAD.'tagged_pdf/'.$filename);
                
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                {
                  if(!unlink(DIR_UPLOAD.'tagged_pdf/'.$filename))
                  {
                      echo ("Error deleting ");
                  }
                  else
                  {
                     echo ("Deleted ");
                  }
                }
        
        }
        public function store_inventory()
        { 
	$log= new Log('mail-daily-inventory-'.date('Y-m-d').'.log');
	$log->write('store_inventory called');
            $this->adminmodel('setting/store');
            $this->adminmodel('report/Inventory');
        $data['orders'] = array();
       $filter_store=0;
        $filter_data = array(
            
            'filter_store' => $filter_store
          
        );

        $log->write('1');

        $data['orders'] = array();
 
    $results = $this->model_report_Inventory->getInventory_report_daily_email($filter_data);
	//	print_r($results );exit;
   $log->write('2');
     include_once DIR_SYSTEM .'/library/PHPExcel.php';
    include_once DIR_SYSTEM .'/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
   
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'Store Name',
        'Store ID',
       
        'Product ID',
        'Product Name',
        'Current Qnty',
        'Date'
        
    );
   //'Price',
     //   'Amount'
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    
    $row = 2;
    $log->write('3');
    foreach($results as $data)
    {   
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['store_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['product_id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['Product_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $data['Qnty']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date('d/m/Y')); 
       
        $row++;

      // $add_to_db_month_starting = $this->model_report_Inventory->add_to_db_month_starting($data['store_name'],$data['store_id'],$data['product_id'],$data['Product_name'],$data['Qnty'],date('Y-m-d'));
       
    }
	$log->write('4');
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
     	$filename="Inventory_report_".date('dMy-his').".xls";
	$log->write($filename);

	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
	$cacheSettings = array( 'memoryCacheSize' => '128MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings); 
	$log->write(DIR_UPLOAD."inventory/".$filename);

	try{
     $objWriter->save(DIR_UPLOAD."inventory/".$filename );
	$log->write('5 after file save beofore add to db');
    $add_to_db = $this->model_report_Inventory->setInventory_report_daily_email($filename,date('Y-m-d'));
     }
	catch(Exception $e)
	{
		$log->write($e);
	}

     //exit; 
     $mail             = new PHPMailer();

                $body = "<p>Please find the attached file for the current inventory.</p><br/><br/><p>Akshamaala Solution Pvt. Ltd.</p>";
                
                $mail->IsSMTP();
                $mail->Host       = "mail.akshamaala.in";
                                                           
                $mail->SMTPAuth   = false;                 
                $mail->SMTPSecure = "";                 
                $mail->Host       = "mail.akshamaala.in";      
                $mail->Port       = 25;                  
                $mail->Username   = "mis@akshamaala.in";  
                $mail->Password   = "mismis";            

                $mail->SetFrom('mail.akshamaala.in', 'Akshamaala');

                $mail->AddReplyTo('mail.akshamaala.in','Akshamaala');

                $mail->Subject    = "Daily Inventory Report";

                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

                $mail->MsgHTML($body);
                
                //to get the email of supplier
                
                $mail->AddAddress('amit.s@akshamaala.com', "Amit Sinha");
                $mail->AddCC('ashok.prasad@akshamaala.com', "Ashok Prasad");
	  $mail->AddCC('pragya.singh@aspltech.com', "Pragya Singh");
	 
                $mail->AddCC('chetan.singh@akshamaala.com', "Chetan Singh");                
                $mail->AddBCC('vipin.kumar@aspltech.com', "Amit Sinha");

                $mail->AddAttachment(DIR_UPLOAD."inventory/".$filename); 
              
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                }
                else
                { echo "sent";
                          
                }

    
       }
}

?>