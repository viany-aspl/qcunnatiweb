<?php
class ControllerMarginMargin extends Controller {
	private $error = array();

	public function index() 
	{
		$this->document->setTitle($this->language->get('Margin'));
        $this-> load->model('margin/margin');
		$this->getList();
	}
    protected function getList() 
	{
        $this-> load->model('margin/margin');
        if (isset($this->request->get['filter_store'])) 
		{
			$filter_store = $this->request->get['filter_store'];
		} 
		else 
		{
			$filter_store = null;
		}

		if (isset($this->request->get['filter_month'])) 
		{
			$filter_month = $this->request->get['filter_month'];
		} 
		else 
		{
			$filter_month = null;
		}
       
		if (isset($this->request->get['page'])) 
		{
			$page = $this->request->get['page'];
		} 
		else 
		{
			$page = 1;
		}

		$url = '';
                
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . urlencode(html_entity_decode($this->request->get['filter_store'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . urlencode(html_entity_decode($this->request->get['filter_month'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) 
		{
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] =   array();

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('Margin'),
			'href' =>  $this->url->link('margin/margin', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		//$data['margin'] = $this->url->link('margin/margin/setmargin', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('margin/margin/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['stores'] = array();
		$filter_data = array(
			'filter_store'	  => $filter_store,
			'filter_month'	  => $filter_month,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
        $data['storess'] = $this->model_margin_margin->getstore();
        $results = $this->model_margin_margin->getstores($filter_data);
		$location_total = count($this->model_margin_margin->getstorestotal($filter_data));
              
               foreach ($results as $result) 
                {
                            $data['stores'][] = array(
                                'margin_id' => $result['margin_id'],
				'store_id'      =>$result['store_id'],
				'store_name'       => $result['store_name'],
                                'month_name'       => $result['month_name'],
                                'month_id'       => $result['month_id'],
								
								 'upload_margin'       => $this->url->link('margin/margin/download', 'token=' . $this->session->data['token'].'&filename='.$result['upload_margin'], 'SSL'),
                                'edit'       => $this->url->link('margin/margin/edit', 'token=' . $this->session->data['token'].'&margin_id='.$result['margin_id'] . $url, 'SSL'),
			);
                    
		}
               
		$data['heading_title'] = $this->language->get('Margin');
		
		$data['text_list'] = $this->language->get('Margin');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('Month');
		$data['column_address'] = $this->language->get('column_address');
		$data['column_action'] = $this->language->get('Action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		$url = '';

		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . urlencode(html_entity_decode($this->request->get['filter_store'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . urlencode(html_entity_decode($this->request->get['filter_month'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_address'] = $this->url->link('margin/margin', 'token=' . $this->session->data['token'] . '&sort=address' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
                 if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . urlencode(html_entity_decode($this->request->get['filter_store'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . urlencode(html_entity_decode($this->request->get['filter_month'], ENT_QUOTES, 'UTF-8'));
		}

                
		$pagination = new Pagination();
		$pagination->total = $location_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('margin/margin', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($location_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($location_total - $this->config->get('config_limit_admin'))) ? $location_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $location_total, ceil($location_total / $this->config->get('config_limit_admin')));
               // $data['token']=$this->session->data['token'];
		$data['filter_month'] = $filter_month;
		$data['filter_store'] = $filter_store;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('margin/margin_list.tpl', $data));
	}
	public function  download() 
	{
		$file=DIR_UPLOAD.'margin_doc/'.$this->request->get['filename'];
		if (file_exists($file)) 
		{ 
		header('Content-Description: File Transfer');
		header('Content-Type: application/vnd.ms-excel; charset=utf-8');
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		exit;
	}
		
	}
	public function delete() 
	{
		$this->load->model('margin/margin');

		if (isset($this->request->post['selected'])) 
		{
			foreach ($this->request->post['selected'] as $margin_id) 
			{
				
				$this->model_margin_margin->deletemargin($margin_id);
			}

			$this->session->data['success'] = 'Delete Successfully';
			$this->response->redirect($this->url->link('margin/margin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

	}
    public function edit() 
	{
		$this->load->language('margin/margin');

		$this->document->setTitle($this->language->get('Edit Margin'));

		$this->load->model('margin/margin');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) 
		{
            if (!empty($this->request->files['upload_margin']['name'])) 
			{
				//echo DIR_UPLOAD."margin_doc/" .$this->request->post['old_file'];
				unlink(DIR_UPLOAD."margin_doc/" .$this->request->post['old_file']);
				
				$store=(explode(",",$this->request->post['store_id']));
                $file_n=explode('.',$this->request->files['upload_margin']['name']);
                $file_ext=end($file_n);
                $file_upload_margin_doc=date('Y_m_d_h_i_s').'_'.$store[0]."_upload_margin.".$file_ext;
                $path=DIR_UPLOAD."margin_doc/" .$file_upload_margin_doc;
                move_uploaded_file($this->request->files['upload_margin']['tmp_name'], $path);    
                $this->request->post["upload_margin"]=$file_upload_margin_doc;    
            }
            else 
            {
                $this->request->post["upload_margin"]=$this->request->post['old_file'];  
            }
			$this->model_margin_margin->editmargin($this->request->get['margin_id'], $this->request->post);
			//exit;
			$this->session->data['success'] = 'Updated Successfully';

			//print_r($this->request->post);exit;
			$this->response->redirect($this->url->link('margin/margin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
	public function add() 
	{
		

		$this->load->model('margin/margin');
                
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) 
		{
                    $fol =mkdir(DIR_UPLOAD."margin_doc/", 0777, true);
                         if (!empty($this->request->files['upload_margin']['name'])) 
                         {
							$store=(explode(",",$this->request->post['store_id']));
                                $file_n=explode('.',$this->request->files['upload_margin']['name']);
                                $file_ext=end($file_n);
                                $file_upload_margin_doc=date('Y_m_d_h_i_s').'_'.$store[0]."_upload_margin.".$file_ext;
                                $path=DIR_UPLOAD."margin_doc/" .$file_upload_margin_doc;
                                move_uploaded_file($this->request->files['upload_margin']['tmp_name'], $path);    
                                $this->request->post["upload_margin"]=$file_upload_margin_doc;    
                         }
                         else 
                         {
                             $this->request->post["upload_margin"]=$this->request->post['upload_margin_h'];  
                         }
			$this->model_margin_margin->addmargin($this->request->post);

			$this->session->data['success'] = $this->language->get('Successfully Insert');

			$url = '';

                         
                        
                        
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('margin/margin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
    protected function getForm() 
	{
        $this-> load->model('margin/margin');
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['zone_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		
        $data['text_form'] = $this->language->get('Add Margin');
        $data['success'] = $this->language->get('Successfully Inserted');

		$data['error_unit'] = $this->language->get('error_unit');
        $data['error_storage'] = $this->language->get('error_storage');
        $data['entry_name'] = $this->language->get('entry_name');
		
		$data['entry_image'] = $this->language->get('entry_image');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$this->load->model('margin/margin');
		//echo $this->request->get['margin_id'];
		if(!empty($this->request->get['margin_id']))
		{
			$product_info=$this->model_margin_margin->getmargin($this->request->get['margin_id']);
			//print_r($product_info);exit;
		}
		if (isset($this->request->get['margin_id'])) 
		{
			$data['margin_id'] = $this->request->get['margin_id'];
		} 
		elseif (!empty($product_info)) 
		{
			$data['margin_id'] = $product_info['store_id'];
		} 
		else 
		{
			$data['margin_id'] = 0;
		}
		
		if (isset($this->request->post['margin_id'])) 
		{
			$data['store_id'] = $this->request->post['store_id'];
		} 
		elseif (!empty($product_info)) 
		{
			$data['store_id'] = $product_info['store_id'];
		} 
		else 
		{
			$data['store_id'] = 0;
		}
		if (isset($this->request->get['month_id'])) 
		{
			$data['month_id'] = $this->request->get['month_id'];
		} 
		elseif (!empty($product_info)) 
		{
			$data['month_id'] = $product_info['month_id'];
		} 
		else 
		{
			$data['month_id'] = 0;
		}
		if (!empty($product_info)) 
		{
			$data['upload_margin'] = $product_info['upload_margin'];
		} 
		

		$url = '';

		if (isset($this->request->get['margin_id'])) 
		{
			$url .= '&margin_id=' . $this->request->get['margin_id'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('Margin'),
			'href' => $this->url->link('margin/margin', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!empty($this->request->get['margin_id'])) 
		{
			$data['action'] = $this->url->link('margin/margin/edit', 'token=' . $this->session->data['token'] .'&margin_id=' . $this->request->get['margin_id']. $url, 'SSL');
		} 
		else 
		{
			$data['action'] = $this->url->link('margin/margin/add', 'token=' . $this->session->data['token']  . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('margin/margin', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['token'] = $this->session->data['token'];

		$data['stores'] =  $this->model_margin_margin->getstore();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
 
		$this->response->setOutput($this->load->view('margin/margin_form.tpl', $data));
	}
        
        
        protected function validate() {
                $file_ext=array('pdf','zip','rar');
                
		if (!$this->user->hasPermission('modify', 'margin/margin')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
             
                
                
                if (!$this->request->post['store_id']) {
			$this->error['url'] = 'Please enter Store Name';
		}
                
                   if (!$this->request->post['month_id']) {
			$this->error['url'] = 'Please enter Month Name';
		}
                
		if (!$this->request->files['upload_margin']["name"]) 
                   {
                       if($this->request->get["store_id"]=="")
                       {
                           $this->error['upload_margin'] = 'Please select Margin doc file';
                       }
                       
                   }
                   else 
                   {
                     
                     $file_n_GST_doc=explode('.',$this->request->files['upload_margin']['name']);
                     $file_ext_GST_doc=end($file_n_GST_doc); 
                     if(!in_array($file_ext_GST_doc, $file_ext))
                     {
                         $this->error['GST_doc'] = 'Only pdf file is allowd for Margin doc file';
                     }
                    
                   }
                   
                ///////////////for file handling end here//////////
                
           
                
            
                if(!empty($this->error))
                {
                    //print_r($this->request->post);
                    //print_r($this->error);
                    //exit;
                }
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = serialize($this->error);//$this->language->get('error_warning'); 
		}

		return !$this->error;
	}

	
	
	public function margingetList() 
	{
        $this-> load->model('margin/margin');
        if (isset($this->request->get['filter_name'])) 
		{
			$filter_name = $this->request->get['filter_name'];
		} 
		else 
		{
			$filter_name = null;
		}

		if (isset($this->request->get['filter_month'])) 
		{
			$filter_month = $this->request->get['filter_month'];
		} 
		else 
		{
			$filter_month = null;
		}
       
		if (isset($this->request->get['page'])) 
		{
			$page = $this->request->get['page'];
		} 
		else 
		{
			$page = 1;
		}

		$url = '';
                
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . urlencode(html_entity_decode($this->request->get['filter_month'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) 
		{
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] =   array();

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('Margin'),
			'href' =>  $this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['canclelist'] = $this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['deletemargin'] = $this->url->link('margin/margin/deletemargin', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['margins'] = array();
		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_month'	  => $filter_month,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		
		
		$allmonth=array(
			'1-2019'=>'January',
			'2-2019'=>'February',
			'3-2019'=>'March',
			'4-2019'=>'April',
			'5-2019'=>'May',
			'6-2019'=>'June',
			'7-2019'=>'July',
			'8-2019'=>'August',
			'9-2019'=>'September',
			'10-2019'=>'October',
			'11-2019'=>'November',
			'12-2019'=>'December'
			);
			
			$data['allmonth']=$allmonth;
		
		
		
       
        $results = $this->model_margin_margin->getmarginlist($filter_data);
		$location_total = count($this->model_margin_margin->getmargintotal($filter_data));
              
               foreach ($results as $result) 
                {
                    $data['margins'][] = array(
                    'product_id' => $result['product_id'],
					'margin_id' => $result['margin_id'],
					'product_name'      =>$result['product_name'],
					'margin'       => $result['margin'],
					'month_year'       => $result['month_year'],
					//'month_id'       => $result['month_id'],
					
					'editmargin'       => $this->url->link('margin/margin/editmargin', 'token=' . $this->session->data['token'].'&margin_id='.$result['margin_id'] . $url, 'SSL'),
			);
                    
		}
               
		$data['heading_title'] = $this->language->get('Margin');
	    $data['Title']=$this->document->setTitle($this->language->get('Margin'));
		$data['text_list'] = $this->language->get('Margin');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('Month');
		$data['column_address'] = $this->language->get('column_address');
		$data['column_action'] = $this->language->get('Action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . urlencode(html_entity_decode($this->request->get['filter_month'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_address'] = $this->url->link('margin/margin', 'token=' . $this->session->data['token'] . '&sort=address' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
                 if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . urlencode(html_entity_decode($this->request->get['filter_month'], ENT_QUOTES, 'UTF-8'));
		}

                
		$pagination = new Pagination();
		$pagination->total = $location_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($location_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($location_total - $this->config->get('config_limit_admin'))) ? $location_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $location_total, ceil($location_total / $this->config->get('config_limit_admin')));
            $data['token']=$this->session->data['token'];
		$data['filter_month'] = $filter_month;
		$data['filter_name'] = $filter_name;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('margin/margin_get_list.tpl', $data));
	}
	
	
    public function setmargin() 
	{
		//print_r($this->request->post);
		
		
		$allmonth=array(
			'1-2019'=>'January',
			'2-2019'=>'February',
			'3-2019'=>'March',
			'4-2019'=>'April',
			'5-2019'=>'May',
			'6-2019'=>'June',
			'7-2019'=>'July',
			'8-2019'=>'August',
			'9-2019'=>'September',
			'10-2019'=>'October',
			'11-2019'=>'November',
			'12-2019'=>'December'
			);
			
			
			
	   $this->document->setTitle($this->language->get('Add Margin'));

		 $this->load->model('margin/margin');
                
		if (($this->request->server['REQUEST_METHOD'] == 'POST')&& $this->validatemargin()) 
		{
			$this->model_margin_margin->addsetmargin($this->request->post);
			$this->session->data['success'] = $this->language->get('Successfully Insert');
			$this->response->redirect($this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

			if (isset($this->request->get['margin_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) 
			{
				$margin_info = $this->model_margin_margin->getmarginlistbyid($this->request->get['margin_id']);
				//print_r($margin_info);
				
				$data['action']='editmargin';
				$this->document->setTitle($this->language->get('Edit Margin'));
				//$this->response->redirect($this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			else
			{
				$data['action']='setmargin';
				$this->document->setTitle($this->language->get('Add Margin'));
				
			}
			
		if (isset($this->request->post['product_name'])) {
			$data['product_name'] = $this->request->post['product_name'];
		} elseif (!empty($margin_info)) {
			 $data['product_name'] = $margin_info['product_name'];
		} else {
			$data['product_name'] = '';
		}
		if (isset($this->request->post['product_id'])) {
			$data['product_name'] = $this->request->post['product_id'];
		} elseif (!empty($margin_info)) {
			 $data['product_id'] = $margin_info['product_id'];
		} else {
			$data['product_id'] = '';
		}
		if (isset($this->request->post['margin'])) {
			$data['margin'] = $this->request->post['margin'];
		} elseif (!empty($margin_info)) {
			 $data['margin'] = $margin_info['margin'];
		} else {
			$data['margin'] = '';
		}
		if (isset($this->request->post['margin_id'])) {
			$data['margin_id'] = $this->request->post['margin_id'];
		} elseif (!empty($margin_info)) {
			 $data['margin_id'] = $margin_info['margin_id'];
		} else {
			$data['margin_id'] = '';
		}
		if (isset($this->request->post['month'])) {
			$data['month'] = $this->request->post['month'];
		} elseif (!empty($margin_info)) {
	         $data['month'] = $margin_info['month_year'];
		} else {
			$data['month'] = '';
		}	
		 if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['product_id'])) {
			$data['error_product_id'] = $this->error['product_id'];
		} else {
			$data['error_product_id'] = '';
		}
		
		if (isset($this->error['month'])) {
			$data['error_month'] = $this->error['month'];
		} else {
			$data['error_month'] = '';
		}
		
		if (isset($this->error['margin'])) {
			$data['error_margin'] = $this->error['margin'];
		} else {
			$data['error_margin'] = '';
		}
	    
		$data['allmonth']=$allmonth;
		$data['token']=$this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		
		$this->response->setOutput($this->load->view('margin/margin_set_form.tpl', $data));
	}
	
	
	 public function deletemargin() 
	{
		$this->load->model('margin/margin');

		if (isset($this->request->post['selected'])) 
		{
			foreach ($this->request->post['selected'] as $margin_id) 
			{
				
				$this->model_margin_margin->deletemarginproduct($margin_id);
			}

			$this->session->data['success'] = 'Delete Successfully';
			$this->response->redirect($this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

	} 
        
	public function editmargin() 
	{
		$this->load->language('margin/margin');
		$this->document->setTitle($this->language->get('Edit Margin'));
		$this->load->model('margin/margin');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')&& $this->validatemargin()) 
		{
			$this->model_margin_margin->editmarginproduct($this->request->get['margin_id'], $this->request->post);
			$this->session->data['success'] = 'Updated Successfully';
			$this->response->redirect($this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		else
		{
			//$this->response->redirect($this->url->link('margin/margin/editmargin', 'token=' . $this->session->data['token'] . $url.'&margin_id='.$this->request->get['margin_id'], 'SSL'));
		}
			
		$allmonth=array(
			'1-2019'=>'January',
			'2-2019'=>'February',
			'3-2019'=>'March',
			'4-2019'=>'April',
			'5-2019'=>'May',
			'6-2019'=>'June',
			'7-2019'=>'July',
			'8-2019'=>'August',
			'9-2019'=>'September',
			'10-2019'=>'October',
			'11-2019'=>'November',
			'12-2019'=>'December'
			);
			
			
			
	    $this->document->setTitle($this->language->get('Edit Margin'));
		$this->load->model('margin/margin');
       

			if (isset($this->request->get['margin_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) 
			{
				$margin_info = $this->model_margin_margin->getmarginlistbyid($this->request->get['margin_id']);
				//print_r($margin_info);
				
				$data['action']='editmargin'.'&margin_id='.$this->request->get['margin_id'];
				$this->document->setTitle($this->language->get('Edit Margin'));
				//$this->response->redirect($this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			else
			{
				$data['action']='setmargin';
				$this->document->setTitle($this->language->get('Add Margin'));
				
			}
			
		if (isset($this->request->post['product_name'])) {
			$data['product_name'] = $this->request->post['product_name'];
		} elseif (!empty($margin_info)) {
			 $data['product_name'] = $margin_info['product_name'];
		} else {
			$data['product_name'] = '';
		}
		if (isset($this->request->post['product_id'])) {
			$data['product_name'] = $this->request->post['product_id'];
		} elseif (!empty($margin_info)) {
			 $data['product_id'] = $margin_info['product_id'];
		} else {
			$data['product_id'] = '';
		}
		if (isset($this->request->post['margin'])) {
			$data['margin'] = $this->request->post['margin'];
		} elseif (!empty($margin_info)) {
			 $data['margin'] = $margin_info['margin'];
		} else {
			$data['margin'] = '';
		}
		if (isset($this->request->post['margin_id'])) {
			$data['margin_id'] = $this->request->post['margin_id'];
		} elseif (!empty($margin_info)) {
			 $data['margin_id'] = $margin_info['margin_id'];
		} else {
			$data['margin_id'] = '';
		}
		if (isset($this->request->post['month'])) {
			$data['month'] = $this->request->post['month'];
		} elseif (!empty($margin_info)) {
	         $data['month'] = $margin_info['month_year'];
		} else {
			$data['month'] = '';
		}	
		 if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['product_id'])) {
			$data['error_product_id'] = $this->error['product_id'];
		} else {
			$data['error_product_id'] = '';
		}
		
		if (isset($this->error['month'])) {
			$data['error_month'] = $this->error['month'];
		} else {
			$data['error_month'] = '';
		}
		
		if (isset($this->error['margin'])) {
			$data['error_margin'] = $this->error['margin'];
		} else {
			$data['error_margin'] = '';
		}
	    
		$data['allmonth']=$allmonth;
		$data['token']=$this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		
		$this->response->setOutput($this->load->view('margin/margin_set_form.tpl', $data));
	}
	
	protected function validatemargin() {
     
		if (!$this->user->hasPermission('modify', 'margin/margin')) 
		{
			$this->error['warning'] = 'Warning: No Access! ';
		}

		
		if ((utf8_strlen($this->request->post['product_id']) < 1) || (utf8_strlen($this->request->post['product_id']) > 64)) {
			$this->error['product_id'] = 'Please enter Product Name';
		} 
		if ((utf8_strlen($this->request->post['margin']) < 1) || (utf8_strlen($this->request->post['margin']) > 64)) {
			$this->error['margin'] = 'Please enter Margin';
		} 
		if ((utf8_strlen($this->request->post['month']) < 1) || (utf8_strlen($this->request->post['month']) > 64)) {
			$this->error['month'] = 'Please enter Month';
		} 
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}


	
}