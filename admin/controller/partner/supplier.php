<?php
	class ControllerPartnerSupplier extends Controller {
		public function index() {
			$url = '';
			$data['add'] = $this->url->link('purchase/supplier/add_supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['delete'] = $this->url->link('purchase/supplier/delete_supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['filter'] = $this->url->link('purchase/supplier/filter_supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['edit'] = $this->url->link('purchase/supplier/edit_supplier_form', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => "Suppliers",
				'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			$this->load->model('purchase/supplier_group');
			$data['supplier_groups'] = $this->model_purchase_supplier_group->get_all_supplier_groups();
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->load->model('purchase/supplier');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
			$start = ($page - 1) * 20;
			$limit = 20;
			
			$data['suppliers'] = $this->model_purchase_supplier->get_all_suppliers($start,$limit);
			//print_r($data['suppliers']);
			//exit;
			$total_suppliers = $this->model_purchase_supplier->get_total_count_supplier();
			//getting pages

			
			
			//getting pages
			
			/*pagination*/
			$pagination = new Pagination();
			$pagination->total = $total_suppliers;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->url = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
			
			$data['pagination'] = $pagination->render();
			
			$data['results'] = sprintf($this->language->get('text_pagination'), ($total_suppliers) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_suppliers - $this->config->get('config_limit_admin'))) ? $total_suppliers : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_suppliers, ceil($total_suppliers / $this->config->get('config_limit_admin')));

			
			/*pagination*/
			
			$this->response->setOutput($this->load->view('purchase/supplier_list.tpl',$data));
		}
		/*---------------------Add supplier function starts here--------------*/
		
		public function add_supplier()
		{
			$url = '';
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => "Suppliers",
				'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['action'] = $this->url->link('purchase/supplier/insert_supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->load->model('purchase/supplier_group');
			$data['supplier_groups'] = $this->model_purchase_supplier_group->get_all_supplier_groups();
			$this->response->setOutput($this->load->view('purchase/supplier_form.tpl', $data));
		}
		
		/*---------------------Add supplier function ends here--------------*/
		
		public function insert_supplier()
		{
			$url = '';
			$supplier_group_id = $this->request->post['supplier_group_id'];
			$first_name = $this->request->post['firstname'];
			$last_name = $this->request->post['lastname'];
			$email = $this->request->post['email'];
			$telephone = $this->request->post['telephone'];
			$fax = $this->request->post['fax'];
			$bank = $this->request->post['bank'];
			$ifsc = $this->request->post['ifsc'];
			$account = $this->request->post['account'];
			$address = $this->request->post['bankaddress'];




			
			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$_SESSION['error_firstname'] = "First Name must be between 1 and 32 characters!";
			}

			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$_SESSION['error_lastname'] = "Last Name must be between 1 and 32 characters!";
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$_SESSION['error_email'] = "E-Mail Address does not appear to be valid!";
			}
			
			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$_SESSION['error_telephone'] = "Telephone must be between 3 and 32 characters!";
			}
			if ((utf8_strlen($this->request->post['fax']) < 3) || (utf8_strlen($this->request->post['fax']) > 32)) {
				$_SESSION['error_district'] = "District must be between 3 and 32 characters!";
			}
			
			if ((utf8_strlen($this->request->post['ifsc']) < 3) || (utf8_strlen($this->request->post['ifsc']) > 11)) {
				$_SESSION['error_ifsc'] = "IFSC Code must be between 1 and 11 characters!";
			}
			if ((utf8_strlen($this->request->post['bank']) < 3) || (utf8_strlen($this->request->post['bank']) > 50)) {
				$_SESSION['error_bank'] = "Bank Name must be between 3 and 50 characters!";
			}

			if ((utf8_strlen($this->request->post['account']) < 8) || (utf8_strlen($this->request->post['account']) > 25)) {
				$_SESSION['error_account'] = "Bank Account Number must be between 8 and 25 characters!";
			}
			if ((utf8_strlen($this->request->post['bankaddress']) < 1)) {
				$_SESSION['error_address'] = "Bank Address Number must be between 1 and 10 characters!";
			}	
	
			
			if(isset($_SESSION['error_address'])||isset($_SESSION['error_account'])||isset($_SESSION['error_bank'])||isset($_SESSION['error_ifsc'])||isset($_SESSION['error_district'])||isset($_SESSION['error_firstname']) || isset($_SESSION['error_firstname']) || isset($_SESSION['error_firstname']) || isset($_SESSION['error_firstname']))
			{
				$data['supplier_group_id'] = $supplier_group_id;
				$data['first_name'] = $first_name;
				$data['last_name'] = $last_name;
				$data['email'] = $email;
				$data['telephone'] = $telephone;
				$data['fax'] = $fax;
				$data['bank'] = $bank;
				$data['address'] = $address;	
				$data['ifsc'] = $ifsc;
				$data['account']=$account;
				$url = '';
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text' => "Home",
					'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
				);

				$data['breadcrumbs'][] = array(
					'text' => "Suppliers",
					'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
				);
				$data['action'] = $this->url->link('purchase/supplier/insert_supplier', 'token=' . $this->session->data['token'] . $url, true);
				$data['cancel'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true);
				$data['header'] = $this->load->controller('common/header');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');
				$this->load->model('purchase/supplier_group');
				$data['supplier_groups'] = $this->model_purchase_supplier_group->get_all_supplier_groups();
				$this->response->setOutput($this->load->view('purchase/supplier_form.tpl', $data));
			}
			else
			{
				$data = array(
					"supplier_group_id" => $supplier_group_id ,
					"first_name" => $first_name ,
					"last_name" => $last_name ,
					"email" => $email ,
					"telephone" => $telephone ,
					"fax" => $fax,
					"bank"=> $bank,
					"ifsc"=>$ifsc,
					"account"=>$account,
					"address"=>$address	
				);
				$this->load->model('purchase/supplier');
				$inserted = $this->model_purchase_supplier->insert_supplier($data);
				if($inserted)
				{
					$_SESSION['success_message'] = "Success: New supplier added! ";
					$this->response->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true));
				}
				else
				{
					$_SESSION['unsuccess_message'] = "Sorry!! Something went woring,try again";
					$this->response->redirect($this->url->link('purchase/supplier/add_supplier', 'token=' . $this->session->data['token'] . $url, true));
				}
			}
		}
		/*------------------Delete supplier function starts here-------------*/
		
		public function delete_supplier()
		{
			$url = '';
			$supplier_ids = $this->request->post['selected'];
			$this->load->model('purchase/supplier');
			if(count($supplier_ids) == 0)
			{
				$this->response->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true));
			}
			$deleted = $this->model_purchase_supplier->delete_supplier($supplier_ids);
			if($deleted)
			{
				$_SESSION['delete_success_message'] = "Success!! Supplier Successfully Deleted";
				$this->response->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true));
			}
			else
			{
				$_SESSION['delete_unsuccess_message'] = "Warning: This supplier cannot be deleted as it is currently assigned to products! ";
				$this->response->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true));
			}
		}
		
		/*------------------Delete supplier function ends here---------------*/
		
		/*--------------Edit supplier function starts here-----------------------*/
		
		public function edit_supplier_form()
		{
			$supplier_id = $this->request->get['supplier_id'];
			$url = '';
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => "Suppliers",
				'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
			);
			$data['action'] = $this->url->link('purchase/supplier/update_supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['cancel'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->load->model('purchase/supplier_group');
			$data['supplier_groups'] = $this->model_purchase_supplier_group->get_all_supplier_groups();
			$this->load->model('purchase/supplier');
			$data['supplier_info'] = $this->model_purchase_supplier->edit_supplier_form($supplier_id);
			$this->response->setOutput($this->load->view('purchase/supplier_edit_form.tpl',$data));
		}
		
		/*--------------Edit supplier function ends here---------------------*/
		
		/*--------------update supplier function starts here-------------------*/
		
		public function update_supplier()
		{
			$url = '';
			$update_info = array(
				"supplier_group_id" => $this->request->post['supplier_group_id'],
				"first_name" => $this->request->post['firstname'],
				"last_name" => $this->request->post['lastname'],
				"email" => $this->request->post['email'],
				"telephone" => $this->request->post['telephone'],
				"fax" => $this->request->post['fax'],
				"supplier_id" => $this->request->post['supplier_id'],
				"ACC_ID"=>$this->request->post['account'],
				"ADDRESS"=>$this->request->post['bankaddress'],
				"BANK_NAME"=>$this->request->post['bank'],
				"IFSC_CODE"=>$this->request->post['code']


			);
			$url .= '&supplier_id=' . $this->request->post['supplier_id'];
			$this->load->model('purchase/supplier');
			$updated = $this->model_purchase_supplier->update_supplier($update_info);
			if($updated)
			{
				$_SESSION['update_success_message'] = "Success!! Supplier updated successfully";
				$this->response->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true));
			}
			else
			{
				$_SESSION['update_unsuccess_message'] = "Sorry!! Something went wrong, try again";
				$this->response->redirect($this->url->link('purchase/supplier/edit_supplier_form', 'token=' . $this->session->data['token'] . $url, true));
			}
		}
		
		public function filter_supplier()
		{
			$filter['supplier_name'] = $this->request->post['filter_name'];
			$filter['supplier_email'] = $this->request->post['filter_email'];
			$filter['supplier_group'] = $this->request->post['filter_supplier_group'];
			$filter['supplier_date_added'] = $this->request->post['filter_date_added'];
			
			$this->load->model('purchase/supplier');
			
			$url = '';
			$data['add'] = $this->url->link('purchase/supplier/add_supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['delete'] = $this->url->link('purchase/supplier/delete_supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['filter'] = $this->url->link('purchase/supplier/filter_supplier', 'token=' . $this->session->data['token'] . $url, true);
			$data['edit'] = $this->url->link('purchase/supplier/edit_supplier_form', 'token=' . $this->session->data['token'] . $url, true);
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => "Home",
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => "Suppliers",
				'href' => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			$this->load->model('purchase/supplier_group');
			$data['supplier_groups'] = $this->model_purchase_supplier_group->get_all_supplier_groups();
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->load->model('purchase/supplier');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
			$start = ($page - 1) * 20;
			$limit = 20;
			
			$data['suppliers'] = $this->model_purchase_supplier->filter($filter);
			if(!$data['suppliers'])
			{
				$this->index();
			}
			else
			{
				$data['filter_name'] = $filter['supplier_name'];
				$data['filter_email'] = $filter['supplier_email'];
				$data['filter_supplier_group'] = $filter['supplier_group'];
				$data['filter_date_added'] = $filter['supplier_date_added'];
				
				$total_suppliers = $this->model_purchase_supplier->get_total_count_supplier();
			
				/*pagination*/
				$pagination = new Pagination();
				$pagination->total = $total_suppliers;
				$pagination->page = $page;
				$pagination->limit = $this->config->get('config_limit_admin');
				$pagination->url = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
				
				$data['pagination'] = $pagination->render();
				
				$data['results'] = sprintf($this->language->get('text_pagination'), ($total_suppliers) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_suppliers - $this->config->get('config_limit_admin'))) ? $total_suppliers : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_suppliers, ceil($total_suppliers / $this->config->get('config_limit_admin')));

				
				/*pagination*/
				
				$this->response->setOutput($this->load->view('purchase/supplier_list.tpl',$data));

			}
		}
		
		/*--------------update supplier function ends here--------------------*/
	}
?>