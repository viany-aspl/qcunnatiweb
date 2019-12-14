<?php
class ControllerReportattendanceReport extends Controller {
  public function  index(){
        $this->load->language('report/report');
        $this->load->model('report/farmerreport');
        $this->load->model('report/attendance');
        $data['text_list'] = $this->language->get('text_list');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_price'] = $this->language->get('entry price');

        if (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];
                unset($this->session->data['error']);
        } 
        elseif (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
        } 
        else {
                $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
        } else {
                $data['success'] = '';
        }
        $this->getList();
    	}
    
    protected function getList() {
        $data['createdby'] = $this->model_report_farmerreport->getCreatedBy();
        $data['lastzone'] =$this->model_report_farmerreport->getStateList($this->request->post);
        $data['frmdata'] =$this->model_report_attendance->getAttendanceReport($this->request->post);
        $data['heading_title'] = $this->language->get('heading_title');
        $data['token'] = $this->session->data['token'];
        $this->load->language('report/report');
	$this->document->setTitle("Attendance Report");
      	$this->load->model('report/farmerreport');
      	$data['entry_status'] = $this->language->get('Search Village Name');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
        $data['order_statuses'] = "";
        $this->session->data["title"]=$this->language->get('heading_title');  
        $data['searchvillage'] = $this->url->link('report/farmerreport', 'token=' . $this->session->data['token'], 'SSL');
                
                $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/farmerreport', 'token=' . $this->session->data['token'], 'SSL')
		);

                $data['button_save'] = $this->language->get('button_save');
		$data['button_back'] = $this->language->get('button_back');
                $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
                $this->response->setOutput($this->load->view('report/attendancereport.tpl', $data));
    
        }
public function download(){
        $month_id=$this->request->get['month_id'];
        $region=$this->request->get['region'];
        $state=$this->request->get['state'];
        $this->load->model('report/attendance'); 
        $this->load->model('report/farmerreport'); 
        $createdby= $this->model_report_attendance->getCustList($state,$region);
        $start='1';
        $a_date = date('Y-'.$month_id.'-'.'t');
        $end=date('t', strtotime($a_date));
        // $end=date('t');
        $dateArr=array();
        $from_date=date('Y').'-'.$month_id.'-01';
        $to_date=date('Y').'-'.$month_id.'-'.$end;
        //get Atten Report
        foreach($createdby AS $EP){
              $Eid=$EP['id'];
              $attenData = $this->model_report_attendance->attenReport($Eid,$from_date,$to_date,$state,$region);
              $dateArr[$Eid]= $attenData;
        }
      	// Starting the PHPExcel library
    	$this->load->library('PHPExcel');
    	$this->load->library('PHPExcel/IOFactory');
    	$objPHPExcel = new PHPExcel();
    	$objPHPExcel->createSheet();
    	$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
	$objPHPExcel->setActiveSheetIndex(0);
	// Field names in the first row
    	$fields = array(
            'NAME',
            'STATE',
            'REGION'
             );
        for($i=$start;$i<=$end;$i++){
            array_push($fields, $i);
        }
    	$col = 0;
    	foreach ($fields as $field){
        	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
             
       		$col++;
    	}
     
        $fileIO = fopen('php://memory', 'w+');
        fputcsv($fileIO, $fields,',');
        $i=1;
        foreach($createdby as $val){
         $Eid=$val['id'];
         $fdata=array(
             ucwords($val['name']),
             ucwords($val['STATE_NAME']),
               
         );
         foreach ($dateArr[$Eid]AS $DT){
              array_push($fdata, $DT['STS']);
         }
         fputcsv($fileIO,  $fdata,",");
             $i++;	
    	}
        fseek($fileIO, 0);
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment;filename="attendance_report_'.date('dMy').'.csv"');
        header('Cache-Control: max-age=0');
        fpassthru($fileIO);  
        fclose($fileIO);

    }

public function getFrmData(){
        $month_id=$this->request->post['month_id'];
        $region=$this->request->post['region'];
        $state=$this->request->post['state'];
        $this->load->model('report/attendance'); 
        $this->load->model('report/farmerreport'); 
        $createdby= $this->model_report_attendance->getCustList($state,$region);
       // $Frmdata = $this->model_report_attendance->getAttendanceReport($this->request->post); 
        $i=1;
        $str='';
        $str.='<div class="table-responsive"  width="100%">';
        $str.='<table class="table" id="example" border="1">';
        $str.='<thead>';
        $str.='<tr>';
        $str.='<th style="font-weight: bold; background:#3A8B8E; color:#FFFFFF;">S.No.</th>';
        $str.='<th style="font-weight: bold; background:#3A8B8E; color:#FFFFFF;">NAME</th>';
        //$str.='<th style="font-weight: bold; background:#3A8B8E; color:#FFFFFF;">MOBILE</th>';
        //date
        $start='1';
        $a_date = date('Y-'.$month_id.'-'.'t');
        $end=date('t', strtotime($a_date));
        // $end=date('t');
        $dateArr=array();
        for($i=$start;$i<=$end;$i++){
             $str.='<th style="font-weight: bold; background:#3A8B8E; color:#FFFFFF;">'.$i.'</th>';
        }
        $from_date=date('Y').'-'.$month_id.'-01';
        $to_date=date('Y').'-'.$month_id.'-'.$end;
        //get Atten Report
        foreach($createdby AS $EP){
              $Eid=$EP['id'];
              $attenData = $this->model_report_attendance->attenReport($Eid,$from_date,$to_date,$state,$region);
              $dateArr[$Eid]= $attenData;
        }
        $str.='</tr>';
        $str.='</thead>';
        $str.='<tbody>';
        foreach($createdby as $val){
            $Eid=$val['id'];        
            $str.='<tr>';
            $str.='<td>'.$i.'</td>';
            $str.='<td>'. ucwords($val['name']).'</td>';
           // $str.='<td>'.$val['telephone'].'</td>';
            foreach ($dateArr[$Eid]AS $DT){
                $str.='<td>'.$DT['STS'].'</td>';
            }
            $str.='</tr>';

        $i++;
        }

        $str.='</tbody>';
        $str.='</table>';
        $str.='</div>';
        echo $str;
      
  }
}


