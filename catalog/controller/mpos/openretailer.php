<?php

class Controllermposopenretailer  extends Controller{

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

function updatequantity()   
{

$log=new Log("openretailer-".date('Y-m-d').".log");
$log->write('updatequantity called');
$log->write($this->request->post);

		$mcrypt=new MCrypt();
		$keys = array(
			'store_id',
			'product_id',
			'quantity'
			);

foreach ($keys as $key) {            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
		$log->write($this->request->post);
		$this-> adminmodel('catalog/product');
                $this->model_catalog_product->openretailerupdateqty($this->request->post);
	
		$this->response->setOutput("1");				

}
function updateprice()   
{
$log=new Log("openretailer-Price-".date('Y-m-d').".log");
$log->write('updateprice called');
$log->write($this->request->post);

		$mcrypt=new MCrypt();
		$keys = array(
			'username',
			'store_id',
			'product_id',
			'price'
			
			);

foreach ($keys as $key) {            

                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            
        }
								
		$log->write($this->request->post);
		$this-> adminmodel('catalog/product');
		$log->write("model call");
        $this->model_catalog_product->openretailerupdateprice($this->request->post);
		$log->write("model call end");
		$this->response->setOutput("1");				

}



 

public function addcustomer($sid)
 {
$mcrypt=new MCrypt(); 
 $this->request->post['card']="0"; 
 if(!empty($this->request->post['fname']))
 {
 $this->request->post['firstname']=$mcrypt->decrypt($this->request->post['fname']).'-'.$mcrypt->decrypt($this->request->post['lname']);
 }
 if(!empty($this->request->post['growername']))
 {
 $this->request->post['firstname']=$mcrypt->decrypt($this->request->post['growername']);
 }
  if(!empty($this->request->post['aadhar_number']))
 {
	$this->request->post['aadhar']=$mcrypt->decrypt($this->request->post['aadhar_number']);
 }
 
 $this->request->post['lastname']='';
 if(isset($this->request->post['vname']))
 {
 $this->request->post['village']=$mcrypt->decrypt($this->request->post['vname']); 
 } 
 else if(isset($this->request->post['growercode']))
 {
 $this->request->post['village']=$mcrypt->decrypt($this->request->post['growercode']); 
 } 
 $this->adminmodel('sale/customer'); 
 unset($this->session->data['cid']);
 $this->request->post['email']=$this->request->post['customer_mob'];
 $this->request->post['fax']=$this->request->post['customer_mob'];
 $this->request->post['telephone']=$this->request->post['customer_mob'];
 $this->request->post['customer_group_id']="1";
 $this->request->post['password']=$this->request->post['customer_mob'];
 $this->request->post['newsletter']='0'; 
 $this->request->post['approved']='1';
 $this->request->post['status']='1';
 $this->request->post['safe']='1';
 $this->request->post['address_1']= $this->request->post['village'];
 $this->request->post['address_2']= $this->request->post['village'];
 $this->request->post['city']= $this->request->post['village'];
 $this->request->post['company']='Unnati';
 $this->request->post['country_id']='0';
 $this->request->post['zone_id']='0';
 $this->request->post['postcode']='0';
 $this->request->post['store_id']=$sid; 
 $this->request->post['address']=array($this->request->post);
 $this->model_sale_customer->addCustomer($this->request->post); 
 }

 public function addToCart($pid,$qnty) {
               
$log=new Log("order-open-".date('Y-m-d').".log");
$log->write("add to cart - ".$pid."-".$qnty);
$this->request->post['product_id']=$pid;
$this->request->post['quantity']=$qnty;
$log->write($this->request->post);
		$json = array();
                
		$this->load->library('user');
                $this->user = new User($this->registry);

                $this->load->library('customer');
                $this->customer = new Customer($this->registry);
                
		//$log->write($this->config);
		$log->write($this->config->get('config_country_id'));
		$log->write( $this->config->get('config_zone_id'));
		$log->write("tax init");
                $this->load->library('tax');//
                $this->tax = new Tax($this->registry);
            	$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

                $this->load->library('pos_cart');//
                $this->cart = new Pos_cart($this->registry);
                
		$this->load->model('catalog/product');
                
                if (isset($this->request->post['product_id'])) {
                    $product_id = $this->request->post['product_id'];
                } else {
                    $product_id = 0;
                }
$log->write("before get product ");
                        
		$product_info = $this->model_catalog_product->getProduct($product_id);
$log->write($product_info);

		if ($product_info) {			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();	
			}

			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf('%s field required', $product_option['name']);
				}
			}

			if (!$json) {
				$log->write("before call to model add -".$this->request->post['product_id']."-".$quantity);
				$this->cart->add($this->request->post['product_id'], $quantity, $option);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
$log->write($json);
				// Totals
				$this->adminmodel('pos/extension');
				$total_data = array();					
				$total = 0;
$log->write($total);
				$log->write($this->session->data);
				$taxes = $this->cart->getTaxes();

$log->write($taxes);
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$sort_order = array(); 
					$results = $this->model_pos_extension->getExtensions('total');
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}
					array_multisort($sort_order, SORT_ASC, $results);
					foreach ($results as $result) {

					$log->write($result['code'] . '_status');
					$log->write($this->config->get($result['code'] . '_status'));	
						
						if ($this->config->get($result['code'] . '_status')) {
							$this->adminmodel('pos/' . $result['code']);

							$this->{'model_pos_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
						$sort_order = array(); 
						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}
						array_multisort($sort_order, SORT_ASC, $total_data);			
					}
				}
                                
                                $json['total_data'] = $total_data;
				$json['total'] = $this->currency->format($total);
			} 
		}
$log->write($json);

return 		$json;

}


public function addOrder() {

       
$log=new Log("order-open-".date('Y-m-d').".log");

 $log->write('Add order Call');
 $log->write($this->request->post);
 $log->write($this->request->server['HTTP_UPN']);

 $this->adminmodel('card/integration'); 
 $this->adminmodel('unit/unit');
 $this->load->model('checkout/order');
 $this->load->model('account/activity');
 $this-> adminmodel('pos/pos');

$mcrypt=new MCrypt();
 $this->load->model('account/api');
 $api_info = $this->model_account_api->UserAuthorization($mcrypt->decrypt($this->request->post['user_id']));
 $log->write($api_info);
 if(empty($api_info))
 {
 $json['error']="User is not Authorized";
 $json['success'] = "-1";
 $this->response->setOutput(json_encode($json)); 
 return;

}
 $order_istance=$this->model_pos_pos->check_order_instance($mcrypt->decrypt($this->request->post['transid']));
 if(!empty($order_istance))
 {
 $get_bill=$order_istance;


 $log->write('order already placed with this instance '.$get_bill);
 $json['success'] = 'Success: new order placed with ID: '.$get_bill;
 $json['order_id'] = $get_bill;
 $gtax=$this->model_checkout_order->getgtax($get_bill);
 $json['gtax']= $mcrypt->encrypt(json_encode($gtax)); 
 $this->response->setOutput(json_encode($json)); 
 return;
 }
 $this->model_pos_pos->insert_order_instance($mcrypt->decrypt($this->request->post['transid']),$mcrypt->decrypt($this->request->post['store_id']));
 $log->write(base64_decode($this->request->server['HTTP_UPN']));

 $keys = array(
 'store_id',
 'payment_method',
 'customer_id',
 'affiliate_id',
 'user_id',
 'prddtl',
 'customer_mobile',
 'customer_mob',
 'amtcash',
 'subcash',
 'sub',
 'docs',
 'doc_number',
 'comment',
 'stock_fm',
 'spray',
 'coupon',
 'credit_amount'

 );

$log->write($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UPN'])));
$unitdata=array();
foreach ($keys as $key) {


$this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;

 }
$log->write('After Decrypt');
$log->write($this->request->post);
$log->write('After  Print  Decrypted value');
 //log to system table

 $activity_data = array(
 'customer_id' => $mcrypt->decrypt($this->request->post['username']),
 'data' => json_encode($this->request->post),
 );

$this->model_account_activity->addActivity('Order', $activity_data);

//check for mobile is set of not

if(empty($this->request->post['customer_mob']))
{ 
 //mobile number not defined
 $json['error']="Mobile number not defined";
 $json['success'] = "-1";
 $this->response->setOutput(json_encode($json)); 
 return;
}

///check ase order


//check old

$log->write($this->request->post);


$prds=json_decode($this->request->post[prddtl],true);
 unset($this->session->data['user_id']);
$this->session->data['user_id']=$this->request->post['user_id'] ;
$customer_id =$this->request->post['customer_id'];
//check customer
if(isset($customer_id))
{

$log->write("user id in ".$this->request->post['customer_mob']);
 //check customer
 $customer_id=$this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"];
 $log->write("user id in t ".$customer_id);
 if(empty($customer_id))
{
 $this->addcustomer($this->request->post['store_id']);
 $customer_id=$this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"];
 $log->write("user= ".$customer_id);
 $this->request->post['customer_id']=$customer_id;
}

}

$data['store_id'] = $this->request->post['store_id'];
$this->config->set('config_store_id',$data['store_id']);
//check for product quantity
$this->load->model('catalog/product');
/*foreach($prds as $prd)
 { 
 $log->write($prd['product_id']);

$log->write("quantity check");
 $product_info = $this->model_catalog_product->getProduct($prd['product_id']);
 $log->write($product_info);
 if ($product_info) {

if ($product_info['squantity'] < $prd['product_quantity']) 
 {
 $json['error']="".$prd['product_quantity']." quantity for ".$product_info['name']." not match with system";
 $json['success'] = "-1";
 $this->response->setOutput(json_encode($json)); 
 return;
 } 
 // End lpccoder mod
}
else{
 $json['error']=" product not found please contact admin";
 $json['success'] = "-1";
 $this->response->setOutput(json_encode($json)); 
 return;

}

}
*/
//end qnty

//check fm qunty

$this-> adminmodel('setting/store');


//end fm


//add data to cart
	foreach($prds as $prd)
	{
		$log->write($prd['product_id'].' , '.$prd['product_quantity']);
		$log->write($this->addToCart($prd['product_id'],$prd['product_quantity']));
	}

///////////////////////////////////////////////////////////////////////////////////

 //$this->coupon();

///////////////////////////////


 $log->write("after product submit");
 unset($this->session->data['shipping_method']); 
 $data = array(); 
 //card detail
 if(isset($this->request->post["grower_id"]))
 {
 $data["grower_id"]=$this->request->post["grower_id"];
 }
 if(isset($this->request->post["otpu"]))
 {
 $data["otpu"]=$this->request->post["otpu"];
 }

 if(isset($this->request->post["Card_Serial_Number"]))
 {
 $data["Card_Serial_Number"]=$this->request->post["Card_Serial_Number"];
 }
 //validation 
 $errors = '';

 $payment_method = $this->request->post['payment_method'];
 $is_guest = $this->request->post['is_guest'];
 $customer_id =$this->request->post['customer_id'];
 $card_no = $this->request->post['card_no'];
 $data['comment'] = $this->request->post['comment'];

 if($is_guest=='false' && $customer_id==''){
 $errors .= 'Select the customer.<br />';
 }

 if(($payment_method == 'Card') && $card_no==''){
 $errors .= 'Enter the card number.<br />';
 }

 if($errors != ''){ 
 $data['errors'] = $errors;
 $this->response->setOutput(json_encode($data));
 return;
 }


 $data['store_id'] = $this->request->post['store_id'];
 $data['credit_amount']=$this->request->post['credit_amount'];
 $default_country_id = $this->config->get('config_country_id');
 $default_zone_id = $this->config->get('config_zone_id');

 $data['shipping_country_id'] = $default_country_id;
 $data['shipping_zone_id'] = $default_zone_id;
 $data['payment_country_id'] = $default_country_id;
 $data['payment_zone_id'] = $default_zone_id;
 $data['customer_id'] = 0;
 $data['customer_group_id'] = 1;
 if(!empty($mcrypt->decrypt($this->request->post['fname'])))
 {
 $data['firstname'] = $mcrypt->decrypt($this->request->post['fname']);
 }
 else
 {
 $data['firstname'] = 'Walkin';
 }
 $data['lastname'] = "Customer";
 $data['email'] = '';
 $data['telephone'] = $this->request->post['customer_mob'] ;
 $data['fax'] = ''; 
 if(!empty($mcrypt->decrypt($this->request->post['fname'])))
 {
 $data['payment_firstname'] = $mcrypt->decrypt($this->request->post['fname']);
 }
 else
 {
 $data['payment_firstname'] = 'Walkin';
 } 
 //$data['payment_firstname'] = 'Walkin';
 $data['payment_lastname'] = "Customer";
 $data['payment_company'] = $this->request->post['spray'];
 $data['payment_company_id'] = '';
 $data['payment_tax_id'] = '';
 $data['payment_address_1'] = '';
 $data['payment_address_2'] = '';
 $data['payment_city'] = '';
 $data['payment_postcode'] = '';
 $data['payment_country_id'] = '';
 $data['payment_zone_id'] = '';
 $data['payment_method'] = $payment_method;
 $data['payment_code'] = 'in_store';
 if($payment_method=='Cash')
 {
 $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['growercode']) ;


 }elseif($payment_method=='Subsidy')
 {
 $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['growercode']) ;


 }
 elseif($payment_method=='Tagged')
 {
 if(isset($this->request->post['cid']))
 {
 $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['cid']) ;
 }
 else
 {
 $data['shipping_firstname'] = '';
 }


 }
 elseif($payment_method=='Tagged Cash')
 {
 ////////cid for grower_code
 if(isset($this->request->post['cid']))
 {
 $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['cid']) ;
 }
 else
 {
 $data['shipping_firstname'] = '';
 }


 }
 else{
 $data['shipping_firstname'] = '';
 }
 if($payment_method=='Tagged')
 {
 if(isset($this->request->post['vname']))
 {
 $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']) ;
 }
 else if(isset($this->request->post['growercode']))
 {
 $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']) ;
 }
 else
 {
 $data['payment_address_1'] = '';
 }

} 
 else if($payment_method=='Tagged Cash')
 {
 if(isset($this->request->post['vname']))
 {
 $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']) ;
 }
 else if(isset($this->request->post['growercode']))
 {
 $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']) ;
 }
 else
 {
 $data['payment_address_1'] = '';
 }

}
 elseif($payment_method=='Subsidy')
 {
 if(isset($this->request->post['vname']))
 {
 $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']) ;
 }
 else if(isset($this->request->post['growercode']))
 {
 $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']) ;
 }
 else
 {
 $data['payment_address_1'] = '';
 }

}
 else
 {
 if(isset($this->request->post['vname']))
 {
 $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']) ;
 }
 else if(isset($this->request->post['growercode']))
 {
 $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']) ;
 }
 else
 {
 $data['payment_address_1'] = '';
 }
 }
 //$data['shipping_firstname'] = '';
 $data['shipping_lastname'] = '';
 $data['shipping_company'] = '';
 $data['shipping_address_1'] = '';
 $data['shipping_address_2'] = '';
 $data['shipping_city'] = '';
 $data['shipping_postcode'] = '';
 $data['shipping_country_id'] = '';
 $data['shipping_zone_id'] = '';
 if($UPN=='')
 {
 $data['shipping_method'] = 'Pickup From Store';
 }
 else
 {
 $data['shipping_method'] = 'Pickup From Store-qr code';
 }
 $data['shipping_code'] = 'pickup.pickup';
 $log->write("payment_method");
 $log->write($payment_method);
 if($payment_method=='Tagged')
 {
 $log->write("in tagged payment_method");
 $data['order_status_id'] = 1;
 $this->request->post['order_status_id']=1;
 }
 else if($payment_method=='Tagged Cash')
 {
 $log->write("in tagged cash payment_method");
 $data['order_status_id'] = 1;
 $this->request->post['order_status_id']=1;
 }
 else
 { 
 $data['order_status_id'] = 5;
 $this->request->post['order_status_id']=5;
 }
 $data['affiliate_id'] = isset( $this->request->post['affiliate_id'])? $this->request->post['affiliate_id']:0;
 
 $data['card_no'] = $card_no;
 $log->write("data");
 $data['user_id'] = $this->request->post['user_id'];
 $log->write("user id");
 $log->write($customer_id);
 $is_guest='false';
 if(isset($customer_id))
 {

$log->write("user id in ",$this->request->post['customer_mob']);
 //$is_guest='true';
 //$this->addcustomer();
 $customer_id=$this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"];//$this->session->data['cid'];
 $log->write("user= ".$customer_id);

}
 //override for customer 
 if($is_guest=='false'){

 $log->write("false");
 $customer = $this->model_pos_pos->getCustomer($customer_id);
  $this->session->data['customer_id']=$customer_id;
 $data['customer_id'] = $customer_id;
 $data['customer_group_id'] = $customer['customer_group_id'];
 $data['firstname'] = $customer['firstname'];
 $data['lastname'] = $customer['lastname'];
 $data['email'] = $customer['email'];
 $data['telephone'] = $customer['telephone'];
 $data['fax'] = $customer['fax'];

$data['payment_firstname'] = $customer['firstname'];
 $data['payment_lastname'] = $customer['lastname'];

 } 

 //get product list 
 $this->load->library('customer');
 $this->customer = new Customer($this->registry);

 $this->load->library('tax');//
 $this->tax = new Tax($this->registry);

 $this->load->library('pos_cart');//
 $this->cart = new Pos_cart($this->registry);

 //SMS LIB
 $this->load->library('sms'); 


$data['order_product'] = array();
 $log->write("product data ");
 $log->write($prds);

 foreach ($prds as $productt) {
 $option_data = array();
 $product = $this->model_catalog_product->getProduct($productt['product_id']);
$log->write("sdgsdg  helooooo");
$log->write($product);
$log->write("user tax");
 $log->write($product['price']);
 $log->write( $product['tax_class_id']);
 $log->write($this->tax->getTax($product['price'], $product['tax_class_id']));

 $productt['product_price']=str_replace("Rs.","",$productt['product_price']);
 $productt['product_price']=str_replace(",","",$productt['product_price']);
 
 
$data['order_product'][] = array(
 'product_id' => $product['product_id'],
 'name' => $product['name'],
 'model' => $product['model'], 
 'quantity' => $productt['product_quantity'], 
 'price' => $productt['product_price'],
 'total' => $productt['product_price']*$productt['product_quantity'],
 'tax' => $this->tax->getTax($productt['product_price'], $product['tax_class_id']),
 'reward' => $product['reward'],
 'order_option' => $option_data,
 );
 }//foreach products 

$log->write("final product data ");
 $log->write($data['order_product']);
 $this-> adminmodel('pos/extension');

$total_data = array(); 
 $total = 0;
 $taxes = $this->cart->getTaxes();
 $log->write("near");
 // Display prices
 if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
 $sort_order = array();

$results = $this->model_pos_extension->getExtensions('total');
 $log->write($results);
 foreach ($results as $key => $value) {
 $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
 }

array_multisort($sort_order, SORT_ASC, $results);

foreach ($results as $result) {
 if ($this->config->get($result['code'] . '_status')) {
$this-> adminmodel('pos/' . $result['code']);
$this->{'model_pos_' . $result['code']}->getTotal($total_data, $total, $taxes);
 }

$sort_order = array();

foreach ($total_data as $key => $value) {
 $sort_order[$key] = $value['sort_order'];
 }

array_multisort($sort_order, SORT_ASC, $total_data); 
 }
 }
 $log->write("near1");
 $log->write($total_data);
 $data['amtcash']=$this->request->post['amtcash'];
 $data['subsidy']=$this->request->post['subcash'];
 $data['sub']=$this->request->post['sub'];
 $data['order_total'] = $total_data;

 //for tagged

 if(isset($this->request->post['docs']))
 {
 try{
 $data['shipping_address_2'] = ($this->request->post['docs']) ;
 $data['shipping_city'] = ($this->request->post['doc_number']) ;
 }catch(Exception $e){} 
 }

 if(isset($this->session->data['voucher'])){
 $data['order_voucher'] = $this->session->data['voucher'];
 }

 //end of order total 
 $json['customer_name'] = $data['firstname'].' '.$data['lastname'];
 $log->write($json);
 $log->write("near2");
// $order_id= $this->model_checkout_order->addOrder($order_data);
 $log->write("nearf");
 $this->adminmodel('pos/pos');
 $order_id = $this->model_pos_pos->addOrderOpen($data); 
 
 
 $this->model_pos_pos->update_order_istance_order_id($mcrypt->decrypt($this->request->post['transid']),$order_id);
 if(!empty($data['credit_amount']))
 {
	$order_info=array();
	$order_info['customer_id']=$customer_id;
	$order_info['order_id']=$order_id;
	$order_total=array();
	$order_total['value']=-$data['credit_amount'];
	$this->model_pos_credit->confirm($order_info,$order_total);
 }
 $data['oid']=$order_id;
 // add order to cane system 
 $log->write($datares); 

$log->write("near3");
 unset($this->session->data['discount_amount']);

//recore for counter payment 
 if($this->request->post['payment_method'] == 'Tagged Cash'){ 
 $cash = (float)$data['amtcash'];
 $card = 0;//$total;
 }
 else if($this->request->post['payment_method'] == 'Subsidy'){ 
 $cash = $this->request->post['subcash'];
 $card = 0;//$total;
 }
 else{
 $cash = $total;
 $card = 0;
 }

 $data = array(
 'user_id' => $this->request->post['user_id'],
 'cash' => $cash,
 'card' => $card, 
 'store_id'=>$this->request->post['store_id'],
 'order_id'=>$order_id,
 'payment_method'=>$this->request->post['payment_method'],
 'total'=>$total
 );

 if($this->request->post['payment_method'] == 'Tagged Cash') 
 {
 $log->write('Payment Method is: '.$this->request->post['payment_method'] ); 
 $log->write($data); 
 $this->model_pos_pos->addPayment($data); 
 }
 if($this->request->post['payment_method'] == 'Tagged') 
 {
 $log->write('Payment Method is: '.$this->request->post['payment_method'] ); 
 $log->write($data); 
 $this->model_pos_pos->addPayment($data); 
 }
 if($this->request->post['payment_method'] == 'Cash')
 {
 $log->write('Payment Method is: '.$this->request->post['payment_method']); 
 $log->write($data); 
 $this->model_pos_pos->addPayment($data); 
 }
 if($this->request->post['payment_method'] == 'Subsidy')
 {
 $log->write('Payment Method is: '.$this->request->post['payment_method']); 
 $log->write($data); 
 $this->model_pos_pos->addPayment($data); 
 }

 $json['order_id'] = $order_id;
 $log->write("Genereted Invoice number - ".$order_id);
 $balance = $this->model_pos_pos->get_user_balance($this->user->getId());
 $json['cash'] = $this->currency->format($balance['cash']);
 $json['card'] = $this->currency->format($balance['card']);

$log->write("done----".$customer_id);
 // Set the order history

$log->write("dones----");
if (isset($this->request->post['order_status_id'])) {
 $order_status_id = $this->request->post['order_status_id'];
 } else {
 $order_status_id = $this->config->get('config_order_status_id');
 }
 $log->write("dones--1--");

 $this->model_checkout_order->addOrderHistory($json['order_id'], $order_status_id);


 $log->write("dones--2--");
 $json['success'] = 'Success: new order placed with ID: '.$order_id;
 $json['invoice_no']='INV-'.$order_id;

 $json['orddate'] = date('Y-m-d h:i:s A');
 $log->write("before call to get_order_total");
 $json['coupon_discount']=$this->model_pos_pos->get_order_total($order_id,'coupon');
 $log->write("after call to get_order_total");
 //send sms
 $gtax=$this->model_checkout_order->getgtax($order_id);

$json['gtax']= $mcrypt->encrypt(json_encode($gtax));

//$sms=new sms($this->registry);

 //$sms->sendsms($this->request->post['customer_mob'],"2",$data);


//send to recharge
 $log->write('Recharge thread open');
try{
if($this->request->post['payment_method'] == 'Cash') 
{ $log->write('Payment method is :'.$this->request->post['payment_method']." so start the thread");
 // $asyncOperation=new AsyncOperation($this->request->post['customer_mob'],$order_id,$this->request->post['store_id'],$prds);
 //$asyncOperation->start();
 $log->write('Recharge thread start');
}
else
{
$log->write('Payment method is :'.$this->request->post['payment_method']." so no need to call thread");
}
} catch (Exception $e) {
 $log->write($e);
 }
//end recharge 
 $log->write($json);
 $this->response->setOutput(json_encode($json));

}//END add order

//ADD Product 
function addproduct()
{        
        
        $log=new Log("addproduct-".date('Y-m-d').".log");
        $mcrypt=new MCrypt();
		$log->write('Addproduct called');
        $log->write($this->request->post);
        $log->write($this->request->get);
        $data=array();
        $data['PostedBy']=$mcrypt->decrypt($this->request->post['PostedBy']);        
        $data['productname']=$mcrypt->decrypt($this->request->post['productname']);
        $data['mrp']=$mcrypt->decrypt($this->request->post['mrp']);
        $data['hstncode']=$mcrypt->decrypt($this->request->post['hstncode']);
        $data['gsttype']=$mcrypt->decrypt($this->request->post['gsttype']);        
        $data['ImageCount']=$mcrypt->decrypt($this->request->post['ImageCount']);
                            
        $this->adminmodel('openretailer/openretailer');
        $log->write("model");
        if( !empty($data['productname']) )
{
        $prdid = $this->model_openretailer_openretailer->addproduct($data);
        //$datas['success']=$mcrypt->encrypt("Product data submitted successfully.");
		$datas['success']="???? ??????? ???? ?? ??? ??? ";
        $datas['product']=$mcrypt->encrypt($prdid);
}        else{
        $datas['success']=$mcrypt->encrypt("product added.");
}
        if(!empty($datas)){
        $this->response->setOutput(json_encode($datas,JSON_UNESCAPED_UNICODE));}            
}
public function getCredit(){

		    $mcrypt=new MCrypt();
	        $this->adminmodel('openretailer/openretailer');
			$m =$mcrypt->decrypt($this->request->post['mobile']);
			//$jsons = $this->model_openretailer_openretailer->getCredit($m);

			$json['credit']='1212';
             $this->response->setOutput(json_encode($json));
}

}

?>