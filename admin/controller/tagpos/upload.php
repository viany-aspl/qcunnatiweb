<?php
  
class ControllerTagposUpload extends Controller 
{
    public function index() 
	{
        $this->document->setTitle('Excel Upload');
             include_once '../system/library/PHPReadExcel.php';
              $this->load->model('tagpos/upload');    
	     
              if (($this->request->server['REQUEST_METHOD'] == 'POST') )
			  {
					//print_r($this->request->files['tfile']);
					$file_n=explode('.',$this->request->files['tfile']['name']);
					$file_ext=end($file_n);
					if($file_ext!='xlsx')
					{
						$data['error_warning'] = 'Only xlsx file are allowed!!';
					}
					else
					{
						if (is_uploaded_file($this->request->files['tfile']['tmp_name'])) 
						{
							$content = file_get_contents($this->request->files['tfile']['tmp_name']);
						} 
						else 
						{
							$content = false;
						} 
             
						if($content)
						{
							
							//print_r($this->request->files['tfile']['name']);exit;
							$read=new PHPReadExcel($this->request->files['tfile']['tmp_name'],$this->request->files['tfile']['name']);
							//echo 'here';
							//echo 'here';
							$unit_id=$this->request->post['unit_id'];
							$filter_date=$this->request->post['filter_date'];
							$arr=$read->getSheetData();
							$main_heading=$arr['0'];
							$sub_heading=$arr['0'];
							unset($arr['0']);
							unset($arr['1']);
							unset($arr['2']);
							unset($arr['3']);
							//echo 'here';
							$file_id= $this->model_tagpos_upload->readExcel($arr,$unit_id,$filter_date,$this->request->files['tfile']['name'],$main_heading,$sub_heading);
							if($file_id=='Wrong file')
							{
								$data['error_warning'] = 'File Format is not correct. Please contact IT Team!!';
							}
							else
							{
							
							$this->session->data['success'] ="Excel Upload Sucessfully !";

							$this->response->redirect($this->url->link('tagpos/upload/view', 'token=' . $this->session->data['token'].'&file_id='.$file_id, 'SSL'));
							}
						}
						else 
						{
							$data['error_warning'] = 'Some error in uploading!!';
						}
					}
				} 
				
				$data['unit_id']=$this->request->post['unit_id'];
				$data['filter_date']=$this->request->post['filter_date'];
               
        $data['heading_title'] = $this->language->get('Excel Upload');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_action'] = $this->language->get('column_action');

	
		$data['button_filter'] = $this->language->get('button_filter');
		
		if (isset($this->session->data['success'])) 
		{
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} 
		else 
		{
			$data['success'] = '';
		}
		$data['token'] = $this->session->data['token'];
		$this->load->model('unit/unit');
        $data['units'] = $this->model_unit_unit->getunitsbycompany(2);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		//echo "here";
		$data['view']=$this->url->link('tagpos/upload/view', 'token=' . $this->session->data['token'], 'SSL');
		$this->response->setOutput($this->load->view('tagpos/excel_upload.tpl', $data));
	}
	public function view()
	{
		$this->load->model('tagpos/upload');
		$this->document->setTitle('View Excel Upload Data');
		$data['breadcrumbs'] = array();
		$url = '';
		if (isset($this->request->get['unit_id'])) 
		{
			$url .= '&unit_id=' . $this->request->get['unit_id'];
			$data['unit_id']=$this->request->get['unit_id'];
		}
		if (isset($this->request->get['filter_date'])) 
		{
			$url .= '&filter_date=' . $this->request->get['filter_date'];
			$data['filter_date']=$this->request->get['filter_date'];
		}
		
		if(!empty($data['unit_id']) && !empty($data['filter_date']))
		{
			$file_id= $this->model_tagpos_upload->get_file_id($data['unit_id'],$data['filter_date']);
			$this->request->get['file_id']=	$file_id;			
		}
		if (isset($this->request->get['file_id'])) 
		{
			$url .= '&file_id=' . $this->request->get['file_id'];
			$data['file_id']=$this->request->get['file_id'];
		}
		if (isset($this->request->get['page'])) 
		{
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['billing'])) 
		{
			$data['billing']=$this->request->get['billing'];
			$url .= '&billing=' . $this->request->get['billing'];
		}
		if (isset($this->request->get['page'])) 
		{
			$page=$this->request->get['page'];
		}
		else
		{
			$page=1;
		}
		$data['breadcrumbs'][] = array(
			'text' => 'Unit List',
			'href' => $this->url->link('tagpos/upload/view', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['token'] = $this->session->data['token'];
		$this->load->library('Barcode39');
		$data['hsn'] = array();
		$file_id=$this->request->get['file_id'];
		$order_total= $this->model_tagpos_upload->getTotaldata($file_id,$data['billing']);
		$filter_data = array(
			'billing'=>$data['billing'],
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'file_id'=>$file_id
		);
		$data['hsn']=$results = $this->model_tagpos_upload->getdata($filter_data);
		//echo count($results);
		

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		

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
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tagpos/upload/view', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
		$this->load->model('unit/unit');
        $data['units'] = $this->model_unit_unit->getunitsbycompany(2);
		if(empty($data['unit_id']))
		{
			$data['unit_id']=$results[0]['unit_id'];
		}
		if(empty($data['filter_date']))
		{
			$data['filter_date']=$results[0]['indent_date'];
		}
			
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['redirect']=$this->url->link('tagpos/upload', 'token=' . $this->session->data['token'], 'SSL');
		$this->response->setOutput($this->load->view('tagpos/list_view.tpl', $data));
		
	}
	public function download_pdf() 
	{ 
		$this->load->library('Barcode39');
		if (isset($this->request->get['file_id'])) 
		{
			$url .= '&file_id=' . $this->request->get['file_id'];
			$data['file_id']=$this->request->get['file_id'];
		}
		if (isset($this->request->get['page'])) 
		{
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['page'])) 
		{
			$page=$this->request->get['page'];
		}
		else
		{
			$page=1;
		}
		
		$this->load->model('tagpos/upload');
		$data['token'] = $this->session->data['token'];
		
		$data['hsn'] = array();
		$file_id=$this->request->get['file_id'];
		$order_total= $this->model_tagpos_upload->getTotaldata($file_id);
		$filter_data = array(
			
			'file_id'=>$file_id
		);
		$data['orders']=$results = $this->model_tagpos_upload->getdata($filter_data);
		$html = $this->load->view('tagpos/indent_excel_pdf.tpl',$data);
		//exit;
		require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
        //$this->response->setOutput($this->load->view('tagpos/fmdelivery_pdf.tpl', $data));
        $base_url = HTTP_CATALOG;
        $mpdf = new mPDF('c', 'A4', '', '', '5', '5', '25', '25', '5', '1', '');
		$mpdf->simpleTables = true;
		$mpdf->shrink_tables_to_fit=1;
                $header = '<div class="header" style="">
                   
<div class="logo" style="width: 100%;" >
<img src="../image/letterhead_text.png" style="height: 40px; width: 121px;" />
<img src="../image/letterhead_log.png" style="height: 55px; width: 121px;float: right;" />

                         </div>
<img src="../image/letterhead_topline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 

</div>';
    
                $mpdf->SetHTMLHeader($header, 'O', false);
                    
                $footer = '<div class="footer">
                        
                        <img src="../image/letterhead_bottomline.png" style="height: 10px; margin-left: -46px;width: 120% !important;" /> 
                        <div class="address"><img src="../image/letterhead_footertext.png" style="height: 10px; margin-left: -40px;width: 120% !important;" /> </div>'
                        . '</div>';

                
                $mpdf->SetHTMLFooter($footer);
                    
                $mpdf->SetDisplayMode('fullpage');
    
                $mpdf->list_indent_first_level = 0;
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->setAutoBottomMargin = 'stretch';
                $mpdf->WriteHTML($html);
				//echo $html;
                $filename='Indent_excel_'.date('Y-m-d')."_".str_replace(' ','',trim($data['summaryorders'][0]['fmname'])).'.pdf';
               // $mpdf->Output(DIR_UPLOAD.$filename,'F');
                $mpdf->Output($filename,'D');
                
		
		
        }
	
}