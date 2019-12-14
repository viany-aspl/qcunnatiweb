<?php
class ControllerUserUserAssign extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('user/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

		$this->getList();
	}

	public function add() {
		$this->load->language('user/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_user->addUser($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('user/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			//print_r($this->request->post);exit;
			$this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('user/user_assign', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
               
	}

	public function delete() {
		$this->load->language('user/user');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_user->deleteUser($user_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['filter_mobile'])) {
			$data['filter_mobile']=$filter_mobile = $this->request->get['filter_mobile'];
		} else {
			$filter_mobile = '';
		}
		if (isset($this->request->get['filter_name'])) {
			$data['filter_name']=$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		if (isset($this->request->get['filter_store'])) {
			$data['filter_store']=$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
  if (isset($this->request->get['filter_user_group_id'])) {
			$data['filter_user_group_id']=$filter_user_group_id = $this->request->get['filter_user_group_id'];
		} else {
			$filter_user_group_id = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['filter_mobile'])) {
			$url .= '&filter_mobile=' . $this->request->get['filter_mobile'];
		} 

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		} 
  if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		} 

		$this->load->model('user/user_group');

		$data['user_groups'] = $this->model_user_user_group->getUserGroups();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('user/user/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('user/user/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['users'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin'),
			'filter_user_group_id'=>$filter_user_group_id,
			'filter_store'=>$filter_store,
			'filter_mobile'=>$filter_mobile,
			'filter_name'=>$filter_name
		);

		$user_total = $this->model_user_user->getTotalUsers($filter_data);

		$results = $this->model_user_user->getUsers($filter_data);
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStoresWeb();
		$data['token']=$this->session->data['token'];
		foreach ($results as $result) {
			$data['users'][] = array(
				'user_id'    => $result['user_id'],
				'username'   => $result['username'],
				'name'   => $result['firstname']." ".$result['lastname'],
				'store_name'   => $result['store_name'],
				'user_group_name'   => $result['user_group_name'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('user/user/edit', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL'),
				'assign_user' => $this->url->link('user/user/assign_users', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_assign'] = $this->language->get('text_assign');

		$data['column_username'] = $this->language->get('column_username');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_username'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=username' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		} 
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
		} 
		if (isset($this->request->get['filter_mobile'])) {
			$url .= '&filter_mobile=' . $this->request->get['filter_mobile'];
		}
 		if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		} 

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($user_total - $this->config->get('config_limit_admin'))) ? $user_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $user_total, ceil($user_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/user_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['user_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_user_group'] = $this->language->get('entry_user_group');
                $data['entry_user_store'] = $this->language->get('entry_user_store');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_confirm'] = $this->language->get('entry_confirm');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}
		if (isset($this->error['company'])) {
			$data['error_company'] = $this->error['company'];
		} else {
			$data['error_company'] = '';
		}
		if (isset($this->error['unit'])) {
			$data['error_unit'] = $this->error['unit'];
		} else {
			$data['error_unit'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['user_id'])) {
			$data['action'] = $this->url->link('user/user_assign/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('user/user_assign/edit', 'token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('user/user_assign', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$user_info = $this->model_user_user->getUser($this->request->get['user_id']);
		}

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} elseif (!empty($user_info)) {
			$data['username'] = $user_info['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['user_group_id'])) {
			$data['user_group_id'] = $this->request->post['user_group_id'];
		} elseif (!empty($user_info)) {
			$data['user_group_id'] = $user_info['user_group_id'];
		} else {
			$data['user_group_id'] = '';
		}

                //store addition
        if (isset($this->request->post['user_store_id'])) 
		{
            $data['user_store_id'][] = $this->request->post['user_store_id'];
        } 
		elseif (!empty($user_info)) 
		{
            $data['user_store_id'] = $this->model_user_user->get_user_stores($this->request->get['user_id']);//$user_info['store_id'];
        } 
		else 
		{
            $data['user_store_id'] = '';
        }
        //print_r($data['user_store_id']);
                //store addition end
        

        //vinay sub user 
               //store addition
        if (isset($this->request->post['user_store_id'])) 
        {
        $data['user_store_id'][] = $this->request->post['user_store_id'];
        } 
        elseif (!empty($user_info)) 
        {
        $data['user_store_id'] = $this->model_user_user->get_user_stores($this->request->get['user_id']);//$user_info['store_id'];
        } 
        else 
        {
        $data['user_store_id'] = '';
        }
        //print_r($data['user_store_id']);
        //store addition end
        //end sub user 
		$this->load->model('user/user_group');

		$data['user_groups'] = $this->model_user_user_group->getUserGroups();
                
                // get the default store id
			$this->load->model('setting/store');
			$data['user_stores'] = $this->model_setting_store->getStores();			               
                 
		$this->load->model('company/company');
		$data['companies_list']=$this->model_company_company->getcompanyName(); 
		
		

		if (isset($this->request->post['config_company'])) 
		{
			$data['config_company'] = $this->request->post['config_company'];
		}
        elseif (isset($user_info['company_id'])) 
		{
			
			$data['config_company'] = $user_info['company_id'];
		}
        else 
		{
			$data['config_company'] = $this->config->get('config_company');
		}
		////////////unit/////////////
		$getUnitbyUser='';
		if(!empty($this->request->get['user_id']))
		{
		
		$getUnitbyUser=$this->model_user_user->getUnitbyUser($this->request->get['user_id']); 
		}
		$this->load->model('unit/unit');
		$data["unit_list"]=$this->model_unit_unit->getunitsbycompany($data['config_company']);  //$this->model_setting_store->getUnits();

		//print_r($this->request->post['config_unit']);
		if (isset($this->request->post['config_unit'])) 
		{
			$data['config_unit'] = $this->request->post['config_unit'];
		}
        elseif ($getUnitbyUser!="") {
			$data['config_unit'] = $getUnitbyUser;
		}
		else
		{
			$data['config_unit'] = '';
		}
                
                	/////////////////////////
                
                        print_r($this->request->post['subuser']); 
             
//                if (isset($this->request->post['subuser'])) 
//		{
//			$data['subuser'] = $this->request->post['subuser'];
//		}
//        elseif ($getUnitbyUser!="") {
//			$data['subuser'] = $getUnitbyUser;
//		}
//		else
//		{
//			$data['subuser'] = '';
//		}
//                
                
                
                
				$data['token']= $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/user_form.tpl', $data));
	}

	protected function validateForm() {
		//if (!$this->user->hasPermission('modify', 'user/user')) {
			//$this->error['warning'] = $this->language->get('error_permission');
		//}

		if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 40)) {
			$this->error['username'] = $this->language->get('error_username');
		}

		$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);

		if (!isset($this->request->get['user_id'])) {
			if ($user_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname'); 
		}
		if(($this->request->post['user_group_id']=='11'))
		{
		if (empty($this->request->post['config_company'])) {
			$this->error['company'] = 'Please Select Company'; 
		}
		
		if (empty($this->request->post['config_unit'])) {
			$this->error['unit'] = 'Please Select Company Unit'; 
		}
		}
		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'user/user')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
		}

		return !$this->error;
	}
	public function download_users() 
	{
		
		if (isset($this->request->get['filter_mobile'])) {
			$data['filter_mobile']=$filter_mobile = $this->request->get['filter_mobile'];
		} else {
			$filter_mobile = '';
		}
		if (isset($this->request->get['filter_name'])) {
			$data['filter_name']=$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		if (isset($this->request->get['filter_store'])) {
			$data['filter_store']=$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = '';
		}
		if (isset($this->request->get['filter_user_group_id'])) {
			$data['filter_user_group_id']=$filter_user_group_id = $this->request->get['filter_user_group_id'];
		} else {
			$filter_user_group_id = '';
		}

		
		$this->load->model('user/user');

		$filter_data = array(
			'filter_user_group_id'=>$filter_user_group_id,
			'filter_store'=>$filter_store,
			'filter_mobile'=>$filter_mobile,
			'filter_name'=>$filter_name
		);


		$results = $this->model_user_user->getUsers($filter_data);
		
		
	include_once '../system/library/PHPExcel.php';
    include_once '../system/library/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->createSheet();
    
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

    $objPHPExcel->setActiveSheetIndex(0);

    // Field names in the first row
    $fields = array(
       'Username',
        'Name',
        'Store',
        'User Group',
		'Status',
		'Date Added',
		'Cash in Hand'
    );
   
    $col = 0;
    foreach ($fields as $field)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $col++;
    }
     
    $row = 2;
    
    foreach($results as $data)
    {         $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $data['username']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $data['firstname']." ".$data['lastname']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $data['store_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $data['user_group_name']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, ($data['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, date($this->language->get('date_format_short'), strtotime($data['date_added'])));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $data['cash']);
        $row++;
    }

    

    
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // Sending headers to force the user to download the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Users_'.date('dMy').'.xls"');
    header('Cache-Control: max-age=0');
	//print_r($results);
    $objWriter->save('php://output');
		
	}
        
        //\\################################## Updated On 12th Nov 2019 by Shashi Bhushan Rai #######################################//\\
       
        public function assign_users(){
            $this->load->language('user/user');
      $this->document->setTitle($this->language->get('Sub User Assign'));
            $data['heading_title'] = $this->language->get('Sub User Assign');
            
            $this->load->model('user/user');
           
            $user_info = $this->model_user_user->getUser($this->request->get['user_id']);
 
        $subuser = $this->model_user_user->subuser();
                $data['subuser'] = $subuser;
            // echo "<pre>"; print_r($subuser); echo "<pre>"; exit;
            if(!empty($user_info)){
                $data['entry_username'] = $this->language->get('entry_username');
                $data['entry_user_group'] = $this->language->get('entry_user_group');
                $data['entry_user_store'] = $this->language->get('entry_user_store');

                if (isset($this->error['warning'])) {
                        $data['error_warning'] = $this->error['warning'];
                } else {
                        $data['error_warning'] = '';
                }

                if (isset($this->error['username'])) {
                        $data['error_username'] = $this->error['username'];
                } else {
                        $data['error_username'] = '';
                }
                if (isset($this->error['unit'])) {
                        $data['error_unit'] = $this->error['unit'];
                } else {
                        $data['error_unit'] = '';
                }

                if (isset($this->error['lastname'])) {
                        $data['error_lastname'] = $this->error['lastname'];
                } else {
                        $data['error_lastname'] = '';
                }
                $url = '';

                if (isset($this->request->get['user_id'])) {
                        $url .= '&user_id=' . $this->request->get['user_id'];
                }

                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('text_home'),
                        'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
                );

                $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('heading_title'),
                        'href' => $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL')
                );

                $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('text_assign'),
                        'href' => $this->url->link('user/user/assign_users', 'token=' . $this->session->data['token'] . $url, 'SSL')
                );

                $data['action'] = $this->url->link('user/user_assign/assign_users', 'token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url, 'SSL');

                $data['cancel'] = $this->url->link('user/user_assign', 'token=' . $this->session->data['token'] . $url, 'SSL');
                
                if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else{
			$data['username'] = $user_info['username'];
		}
                
                 //store addition
                if (isset($this->request->post['user_store_id'])) {
                    $data['user_store_id'][] = $this->request->post['user_store_id'];
                } else 
                        {
                   $data['user_store_id'] = $this->model_user_user->get_user_stores($this->request->get['user_id']);//$user_info['store_id'];
                }
                
		$this->load->model('user/user_group');
      
		$data['user_groups'] = $this->model_user_user_group->getUserGroups();
                
                // get the default store id
                $this->load->model('setting/store');
                $data['user_stores'] = $this->model_setting_store->getStores();			               
                 
		$this->load->model('company/company');
		$data['companies_list']=$this->model_company_company->getcompanyName(); 
		
	

		if (isset($this->request->post['config_company'])) 
		{
			$data['config_company'] = $this->request->post['config_company'];
		}
        elseif (isset($user_info['company_id'])) 
		{
			
			$data['config_company'] = $user_info['company_id'];
		}
        else 
		{
			$data['config_company'] = $this->config->get('config_company');
		}
		////////////unit/////////////
		
		$getUnitbyUser=$this->model_user_user->getUnitbyUser($this->request->get['user_id']); 
                
		$this->load->model('unit/unit');
		$data["unit_list"]=$this->model_unit_unit->getunitsbycompany($data['config_company']);  //$this->model_setting_store->getUnits();

		//print_r($this->request->post['config_unit']);
		if (isset($this->request->post['config_unit'])) 
		{
			$data['config_unit'] = $this->request->post['config_unit'];
		}
                elseif ($getUnitbyUser!="") {
			$data['config_unit'] = $getUnitbyUser;
		}
		else
		{
			$data['config_unit'] = '';
		}
                
                print_r($this->request->post['user_id']);
                if (isset($this->request->post)) 
		{
                    $ass_usr = $this->request->post['subuser'];
                    foreach($ass_usr as $ky=>$vl){
                       $this->model_user_user->assignsubuser($this->request->get['user_id'],$vl);
                      // print_r($this->request->get['user_id']); exit;
                    }
                  
               }
                if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

                       // print_r($this->request->post);exit;
                        $this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);

                        $this->session->data['success'] = $this->language->get('text_success');

                        $this->response->redirect($this->url->link('user/user/assign_users', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }                
                $data['token']= $this->session->data['token'];
                $data['header'] = $this->load->controller('common/header');
                $data['column_left'] = $this->load->controller('common/column_left');
                $data['footer'] = $this->load->controller('common/footer');
 
                $this->response->setOutput($this->load->view('user/user_assign.tpl', $data));
            }else{                
                $this->response->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }
}