<?php

class ControllermposPocoMargin extends Controller {
    
    private $debugIt = false;
   
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
    

//storemenu
    
public function index()
{
    $log=new Log("pocomargin-".date('Y-m-d').".log");
	$mcrypt=new MCrypt();    
	$json = array();                    
	$this-> adminmodel('catalog/pocomargin');
	$this-> adminmodel('setting/store');
    $log->write($this->request->get['store_id']); 
	
	for($a=(date('Y')-1);$a<=(date('Y'));$a++)
	{
		$allmonth[]=array('1-'.$a,'January,'.$a);
		$allmonth[]=array('2-'.$a,'February,'.$a);
		$allmonth[]=array('3-'.$a,'March,'.$a);
		$allmonth[]=array('4-'.$a,'April,'.$a);
		$allmonth[]=array('5-'.$a,'May,'.$a);
		$allmonth[]=array('6-'.$a,'June,'.$a);
		$allmonth[]=array('7-'.$a,'July,'.$a);
		$allmonth[]=array('8-'.$a,'August,'.$a);
		$allmonth[]=array('9-'.$a,'September,'.$a);
		$allmonth[]=array('10-'.$a,'October,'.$a);
		$allmonth[]=array('11-'.$a,'November,'.$a);
		$allmonth[]=array('12-'.$a,'December,'.$a);
			
	}
	
                $Y=date('Y');
				$months_id=date('m');
				$month_id=str_replace("0","",$months_id);
				$M=$month_id."-".$Y;
	
	if (isset($this->request->get['filter_month'])) 
		{
			$filter_month = $this->request->get['filter_month'];
		} 
		else 
		{
		    $filter_month = $M;
		}
       
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		$url = '';
                
				
		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . urlencode(html_entity_decode($this->request->get['filter_month'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

        $data['margins'] = array();
		$filter_data = array(
			
			'filter_month'	  => $filter_month,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
         
         $results = $this->model_catalog_pocomargin->getmarginlist($filter_data);
		 $location_total = count($this->model_catalog_pocomargin->getmargintotal($filter_data));
               $location_total = $results->num_rows;
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
		
		if (isset($this->request->get['filter_month'])) {
			$url .= '&filter_month=' . urlencode(html_entity_decode($this->request->get['filter_month'], ENT_QUOTES, 'UTF-8'));
		}
		
		 
		
		 $data['filter_month'] = $filter_month;
		
		$pagination = new Pagination();
		$pagination->total = $location_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('mpos/pocomargin', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($location_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($location_total - $this->config->get('config_limit_admin'))) ? $location_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $location_total, ceil($location_total / $this->config->get('config_limit_admin')));

		
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['allmonth']=$allmonth;
		$store_id=$mcrypt->decrypt($this->request->get['store_id']);
		$log->write("store_id");
		$log->write($store_id);
		$data['status'] = $this->model_catalog_pocomargin->getstatus($store_id);
		$pagination = new Pagination();
		$pagination->total = $location_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('location/functional_area', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($location_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($location_total - $this->config->get('config_limit_admin'))) ? $location_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $location_total, ceil($location_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$this->response->setOutput($this->load->view('default/template/pocomargin/pocomargin.tpl', $data));
}



public function updateacceptance()
{
	
    $log=new Log("updateacceptance-".date('Y-m-d').".log");
	$mcrypt=new MCrypt();    
	$json = array(); 
    $log->write($this->request->get['store_id']); 
    $store_id=$this->request->get['store_id'];
    $log->write($store_id);    	
	$this-> adminmodel('catalog/pocomargin');
	$log->write($this->request->post);
	$data = $this->model_catalog_pocomargin->updateacceptance($store_id);
	$this->response->redirect($this->url->link('mpos/pocomargin',$data));
}

//end storemenu



	public function getmargin()
	{
       
        $this-> adminmodel('catalog/pocomargin');
		$temp=explode('-',$this->request->post['filter_month']);
		
		$a_date = $temp[1].'-'.$temp[0].'-01';
		$end_date=date("t-M,Y", strtotime($a_date));
		$start_date='01-'.date("M,Y", strtotime($a_date));
		//$day=$d->format( 't' ); 
		$data=$this->model_catalog_pocomargin->getmargindata($this->request->post);
		//print_r($this->request->post);exit;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(
						json_encode(
							array(
							'data'=>$data,
							'a_date'=>$a_date,
							'start_date'=>$start_date,
							'end_date'=>$end_date
							)
						)
					);
       
        }  



}
