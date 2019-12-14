<?php
class ControllerMaterialDiscard extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle('Material Discard');

		$this->load->model('material/discard');

		$this->getList();
	}
       	public function add() {
		
		$this->document->setTitle("Material Discard Form");

		$this->load->model('material/discard');

		
		$this->getform();
	}



	protected function getList() {
		$url = '';
		if (isset($this->request->get['filter_store'])) {
			$url .= '&filter_store=' . $this->request->get['filter_store'];
			$data['filter_store']=$filter_store=$this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
			$data['filter_product']=$filter_product=$this->request->get['filter_product'];
		}
		if (isset($this->request->get['filter_reason'])) {
			$url .= '&filter_reason=' . $this->request->get['filter_reason'];
			$data['filter_reason']=$filter_reason=$this->request->get['filter_reason'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			$data['filter_date_start']=$filter_date_start=$this->request->get['filter_date_start'];
		}
		else
		{
			$data['filter_date_start']=$filter_date_start=date("Y-m").'-01'; 
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			$data['filter_date_end']=$filter_date_end=$this->request->get['filter_date_end'];
		}
		else
		{
			$data['filter_date_end']=$filter_date_end=date("Y-m-d");
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['page'])) {
			$page=$this->request->get['page'];
		}
		else
		{
			$page=1;
		}
		$data['breadcrumbs'] = array();

		

		$data['breadcrumbs'][] = array(
			'text' => 'Material Discard',
			'href' => $this->url->link('material/discard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];

		$data['b2b'] = array();
		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin'),
			'filter_store'	=> $filter_store,
			'filter_reason'	=> $filter_reason,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_product'	=> $filter_product
		);
		$order_total= $this->model_material_discard->getdiscardlisttotal($filter_data);

		$results = $this->model_material_discard->getdiscardlist($filter_data);

		foreach ($results as $result) {
			$data['b2b'][] = array(
				'store_name' => $result['store_name'],
				'product_name'     => $result['product_name'],
				'product_price'     => number_format((float)$result['product_price'],2,'.','') ,
                                		'quantity' => $result['quantity'],
                                		'reason' => $result['reason'],
				'create_time' => $result['create_time'],
                                		'debit_credit' => $result['debit_credit'],
                               		 'remarks' => $result['remarks']
				
			);
		}

		$data['heading_title'] ='Meterial Discard';

		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();//print_r($data['stores'] );
		
		$this->load->model('catalog/product');
		$data['products'] = $this->model_catalog_product->getProducts();

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
		$data['redirect']=$this->url->link('material/discard/add', 'token=' . $this->session->data['token']);
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('material/discard', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('material/discard_list.tpl', $data));
	}
	public function download_excel() {
		
		if (isset($this->request->get['filter_store'])) {
			
			$data['filter_store']=$filter_store=$this->request->get['filter_store'];
		}
		if (isset($this->request->get['filter_product'])) {
			
			$data['filter_product']=$filter_product=$this->request->get['filter_product'];
		}
		if (isset($this->request->get['filter_reason'])) {
			
			$data['filter_reason']=$filter_reason=$this->request->get['filter_reason'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			
			$data['filter_date_start']=$filter_date_start=$this->request->get['filter_date_start'];
		}
		else
		{
			$data['filter_date_start']=$filter_date_start=date("Y-m").'-01'; 
		}
		if (isset($this->request->get['filter_date_end'])) {
			
			$data['filter_date_end']=$filter_date_end=$this->request->get['filter_date_end'];
		}
		else
		{
			$data['filter_date_end']=$filter_date_end=date("Y-m-d");
		}
		
		$data['token'] = $this->session->data['token'];

		$filter_data = array(
			
			'filter_store'	=> $filter_store,
			'filter_reason'	=> $filter_reason,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_product'	=> $filter_product
		);
		$this->load->model('material/discard');
		$results = $this->model_material_discard->getdiscardlist($filter_data);

		$file_name="Material_Discard_".date('dMy').'.xls';
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=".$file_name);  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);

        		
        		echo '<table id="example2" class="table table-striped table-bordered table-hover">
                		<thead>
                		<tr>
	      			
                    			<th>Store Name</th>
                    			<th>Product Name</th>
				<th>Product Price</th>
                    			<th>Quantity</th>
 	      			
	      			<th>Reason</th>
	      			<th>Discard Date</th>
                    			<th>Debit/Credit</th>
                    			<th>Remarks</th>
                		</tr>
                		</thead>
                	<tbody>';
	$tblbody=" ";
	foreach($results as $data)
	{


                    echo  '<tr> 
	      
                    <td>'.$data['store_name'].'</td>
                    
                    <td>'.$data['product_name'].'</td>
	      <td>'.number_format((float)$data['product_price'],2,'.','').'</td>
                    <td>'.$data['quantity'].'</td>
	      <td>'.$data['reason'].'</td>
                    <td>'.date('Y-m-d',strtotime($data['create_time'])).'</td>
	      <td>'.$data['debit_credit'].'</td>
                    <td>'.$data['remarks'].'</td>
                   </tr>';


	}


		echo '</tbody>
          			</table>';
		exit;
     

	}
        protected function getform() {
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Material Discard Form',
			'href' => $this->url->link('material/discard/add', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['token'] = $this->session->data['token'];
	        	$data['cancel']=$this->url->link('material/discard', 'token=' . $this->session->data['token'], 'SSL');
                	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
                    		if($this->request->post['filter_store'] !="")
                    		{
				
				$category_id = $this->model_material_discard->adddiscard($this->request->post);

				$this->load->model('b2bpartner/b2bpartner');			

				$this->session->data['success'] ="Submit Sucessfully !";

				$this->response->redirect($this->url->link('material/discard', 'token=' . $this->session->data['token'], 'SSL'));
		        
                        
                    		}
               	 }
		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();//print_r($data['stores'] );

		$this->load->model('catalog/product');
		$data['products'] = $this->model_catalog_product->getProducts();

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
               
		$data['column_name'] = $this->language->get('column_name');
		$data['column_url'] = $this->language->get('column_url');
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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('material/discard_form.tpl', $data));
	}
      
        public function getstorebyunit()
        {
            
            $unit_id=$this->request->get['unitid'];
            $this->load->model('b2bpartner/b2bpartner');
            $result = $this->model_b2bpartner_b2bpartner->getstorebyunitid($unit_id);
           // print_r($result);
              $store= count($result);
                echo ' <option value=""> Select Store</option> ';
                for($n=0;$n<$store;$n++)
                { //echo $n;
                     echo '<option value="'.$result[$n]['store_id'].'">'.$result[$n]['name'].'</option>';
                }

        } 
}