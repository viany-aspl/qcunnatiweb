<?php
require_once(DIR_SYSTEM .'/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllerAttendanceAttendance extends Controller {

         
public function index() {
$this->load->language('report/customer_activity');

$this->document->setTitle('Attendance Report');

if (isset($this->request->get['filter_userid'])) {
$filter_userid = $this->request->get['filter_userid'];
} else {
$filter_userid = null;
}
if (isset($this->request->get['filter_username'])) {
$filter_username = $this->request->get['filter_username'];
} else {
$filter_username = null;
}


if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m').'-01';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['page'])) {
$page = $this->request->get['page'];
} else {
$page = 1;
}

$url = '';

if (isset($this->request->get['filter_userid'])) {
$url .= '&filter_userid=' . urlencode($this->request->get['filter_userid']);
}
if (isset($this->request->get['filter_username'])) {
$url .= '&filter_username=' . urlencode($this->request->get['filter_username']);
}


if (isset($this->request->get['filter_date_start'])) {
$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
}

if (isset($this->request->get['filter_date_end'])) {
$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
}

if (isset($this->request->get['page'])) {
$url .= '&page=' . $this->request->get['page'];
}

$data['breadcrumbs'] = array();

$data['breadcrumbs'][] = array(
'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
'text' => $this->language->get('text_home')
);

$data['breadcrumbs'][] = array(
'href' => $this->url->link('attendance/attendance', 'token=' . $this->session->data['token'] . $url, 'SSL'),
'text' => 'Attendance Report'
);

$this->load->model('attendence/attendence');


$data['activities'] = array();

$filter_data = array(
'filter_userid' => $filter_userid,
'filter_username' => $filter_username,
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end,
'start' => ($page - 1) * 20,
'limit' => 20
);

$activity_total = $this->model_attendence_attendence->getTotalattendence($filter_data);

$results = $this->model_attendence_attendence->getattendence($filter_data);

foreach ($results as $result) {
if($result['in_time']!='0000-00-00 00:00:00')
{
$in_time_1=strtotime($result['in_time']);
$in_time=date('d M Y - h:i A',$in_time_1);
}
else
{
$in_time="NA";
}
if($result['out_time']!='0000-00-00 00:00:00')
{
$out_time_1=strtotime($result['out_time']);
$out_time=date('d M Y - h:i A',$out_time_1); 
}
else
{
$out_time="NA";
}
$data['activities'][] = array(
'username' => $result['firstname']." ".$result['lasttname'],
'in_time' => $in_time,
'out_time' => $out_time,
'store_name' => $result['store_name'],
'user_id'=>$result['user_id'],
'location_in'=>$result['location_in'],
'location_out'=>$result['location_out'] 
);
}

$data['heading_title'] ='Attendance Report';
$this->load->language('common/information');
		$data['tool_tip']=$this->language->get('attendance/attendance');

		$data['tool_tip_style']=$this->language->get('tool_tip_style');
		$data['tool_tip_class']=$this->language->get('tool_tip_class');
$data['text_list'] = $this->language->get('text_list');
$data['text_no_results'] = $this->language->get('text_no_results');

$data['button_filter'] = $this->language->get('button_filter');

$data['token'] = $this->session->data['token'];


$pagination = new Pagination();
$pagination->total = $activity_total;
$pagination->page = $page;
$pagination->limit = $this->config->get('config_limit_admin');
$pagination->url = $this->url->link('attendance/attendance', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

$data['pagination'] = $pagination->render();

$data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($activity_total - $this->config->get('config_limit_admin'))) ? $activity_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $activity_total, ceil($activity_total / $this->config->get('config_limit_admin')));

$data['filter_userid'] = $filter_userid;
$data['filter_username'] = $filter_username;
$data['filter_date_start'] = $filter_date_start;
$data['filter_date_end'] = $filter_date_end;

$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');

$this->response->setOutput($this->load->view('attendance/attendancereport.tpl', $data)); 
}


public function download_report() {


if (isset($this->request->get['filter_userid'])) {
$filter_userid = $this->request->get['filter_userid'];
} else {
$filter_userid = null;
}
if (isset($this->request->get['filter_username'])) {
$filter_username = $this->request->get['filter_username'];
} else {
$filter_username = null;
}


if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m').'-01';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}

if (isset($this->request->get['page'])) {
$page = $this->request->get['page'];
} else {
$page = 1;
}

$this->load->model('attendence/attendence');


$data['activities'] = array();

$filter_data = array(
'filter_userid' => $filter_userid,
'filter_username' => $filter_username,
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end
);

//print_r($filter_data);exit;

$results = $this->model_attendence_attendence->getattendence($filter_data);



include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       
        'USER NAME',
        'STORE NAME',
        'IN TIME',
        'OUT TIME',
	'IN LOCATION',
	'OUT LOCATION'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    //echo "here";
		
    $row = 2;
    
    foreach($results as $result)
    {         
        $col = 0;
        	if($result['in_time']!='0000-00-00 00:00:00')
	{
	$in_time_1=strtotime($result['in_time']);
	$in_time=date('d M Y - h:i A',$in_time_1);
	}
	else
	{
	$in_time="NA";
	}
	if($result['out_time']!='0000-00-00 00:00:00')
	{
	$out_time_1=strtotime($result['out_time']);
	$out_time=date('d M Y - h:i A',$out_time_1);
	}
	else
	{
	$out_time="NA";
	}

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['firstname']." ".$result['lasttname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $result['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $in_time);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $out_time);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['location_in']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $result['location_out']);

        
        $row++;
    }

    

    
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Attendance_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
}
  public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_username'])) {
			$this->load->model('attendence/attendence');
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_username'  => $this->request->get['filter_username'],
				
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_attendence_attendence->getUsers($filter_data);

			foreach ($results as $result) {
				
				

				$json[] = array(
					'user_id' => $result['user_id'],
					'name'       => strip_tags(html_entity_decode($result['firstname']." ".$result['lastname'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}     


     	public function map()
	{
		$this->load->language('report/customer_activity');

		$this->document->setTitle('Attendance Report-Map');
		$this->load->model('attendence/attendence');

		$data=array();
		$data['token']=$this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if (isset($this->request->get['filter_userid'])) {
			$filter_userid = $this->request->get['filter_userid'];
		} else {
			$filter_userid = null;
		}
		if (isset($this->request->get['filter_username'])) {
			$filter_username = $this->request->get['filter_username'];
		} else {
			$filter_username = null;
		}


		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m').'-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		$data['filter_userid']=$filter_userid;
		$data['filter_username']=$filter_username;
		$data['filter_date_start']=$filter_date_start;
		$data['filter_date_end']=$filter_date_end;
		

		$filter_data = array(
			'filter_userid' => $filter_userid,
			'filter_username' => $filter_username,
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end
		);
		$url = '';

		if (isset($this->request->get['filter_userid'])) {
			$url .= '&filter_userid=' . urlencode($this->request->get['filter_userid']);
		}
		if (isset($this->request->get['filter_username'])) {
			$url .= '&filter_username=' . urlencode($this->request->get['filter_username']);
		}


		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$in_results = $this->model_attendence_attendence->in_report($filter_data);
		$out_results = $this->model_attendence_attendence->out_report($filter_data);

		$array=array_merge($in_results,$out_results);
		//print_r($array);
		foreach($array as $row){
  
  			$lattitude=$lattitude+$row['lattitude'];
  			$longtitude=$longtitude+$row['longtitude'];
  

		}
		$data['average_lattitude'] = $lattitude / count($array);
		$data['average_longtitude'] = $longtitude / count($array);
		

		$this->response->setOutput($this->load->view('attendance/attendancemap.tpl', $data)); 
	}

	public function map_data()
	{
		

$this->load->model('attendence/attendence');

if (isset($this->request->get['filter_userid'])) {
$filter_userid = $this->request->get['filter_userid'];
} else {
$filter_userid = null;
}
if (isset($this->request->get['filter_username'])) {
$filter_username = $this->request->get['filter_username'];
} else {
$filter_username = null;
}


if (isset($this->request->get['filter_date_start'])) {
$filter_date_start = $this->request->get['filter_date_start'];
} else {
$filter_date_start = date('Y-m').'-01';
}

if (isset($this->request->get['filter_date_end'])) {
$filter_date_end = $this->request->get['filter_date_end'];
} else {
$filter_date_end = date('Y-m-d');
}


$filter_data = array(
'filter_userid' => $filter_userid,
'filter_username' => $filter_username,
'filter_date_start' => $filter_date_start,
'filter_date_end' => $filter_date_end
);

header("Content-type: text/xml");

$in_results = $this->model_attendence_attendence->in_report($filter_data);
//$out_results = $this->model_attendence_attendence->out_report($filter_data);

//$results=array_merge($in_results,$out_results);
//print_r($results);
// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
$a=1;
foreach($in_results as $row){

  //print_r($row);
  // Add to XML document node
  echo '<marker ';
  echo 'id="' . $row['user_id']. '" ';
  echo 'name="' . $row['username'] . '" ';
  echo 'address="' . $row['time'] . '" ';
  echo 'lat="' . $row['lattitude'] . '" ';
  echo 'lng="' . $row['longtitude'] . '" ';
  echo 'type="' . $row['in_out'] . '" ';
  echo '/>'; 

}
foreach($out_results1 as $row){

  //print_r($row);
  // Add to XML document node
  echo '<marker ';
  echo 'id="' . $row['user_id']. '" ';
  echo 'name="' . $row['username'] . '" ';
  echo 'address="' . $row['time'] . '" ';
  echo 'lat="' . $row['lattitude'] . '" ';
  echo 'lng="' . $row['longtitude'] . '" ';
  echo 'type="' . $row['in_out'] . '" ';
  echo '/>'; 

}
// End XML file
echo '</markers>';
	}




function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}
}