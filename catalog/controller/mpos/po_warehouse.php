<?php

//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
require_once(DIR_SYSTEM . '/library/mail/class.phpmailer.php');
require_once(DIR_SYSTEM . 'library/mail/class.smtp.php');
error_reporting(0);

class ControllermposPoWarehouse extends Controller {

    public function adminmodel($model) {

        $admin_dir = DIR_SYSTEM;
        $admin_dir = str_replace('system/', 'admin/', $admin_dir);
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

    public function orderlist() {


        /* getting the list of the orders */
        $mcrypt = new MCrypt();
        $this->adminmodel('inventory/purchase_order');

        if (isset($this->request->get['page'])) {
            $page = $mcrypt->decrypt($this->request->get['page']);
        } else {
            $page = 1;
        }
        $log = new Log("inv-" . date('Y-m-d') . ".log");
        $start = ($page - 1) * 20;
        $limit = 20;
        $uid = $mcrypt->decrypt($this->request->post['username']);
        //$log->write($this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit));
        //$data['order_list'] =$mcrypt->encrypt(serialize( $this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit)));
        /* getting the list of the orders */
        $da_data = $this->model_inventory_purchase_order->getListRecStore($uid, $start, $limit);
        $log->write($da_data);
        foreach ($da_data as $d_data) {
            $data['order_list'][] = array
                (
                'id' => $mcrypt->encrypt($d_data['id']),
                'order_date' => $mcrypt->encrypt($d_data['order_date']),
                'order_sup_send' => $mcrypt->encrypt($d_data['order_sup_send']),
                'delete_bit' => $mcrypt->encrypt($d_data['delete_bit']),
                'user_id' => $mcrypt->encrypt($d_data['user_id']),
                'receive_date' => $mcrypt->encrypt($d_data['receive_date']),
                'receive_bit' => $mcrypt->encrypt($d_data['receive_bit']),
                'pending_bit' => $mcrypt->encrypt($d_data['pending_bit']),
                'pre_supplier_bit' => $mcrypt->encrypt($d_data['pre_supplier_bit']),
                'order_status_id' => $mcrypt->encrypt($d_data['order_status_id']),
                'canceled_by' => $mcrypt->encrypt($d_data['canceled_by']),
                'canceled_message' => $mcrypt->encrypt($d_data['canceled_message']),
                'store_id' => $mcrypt->encrypt($d_data['store_id']),
                'store_type' => $mcrypt->encrypt($d_data['store_type']),
                'potential_date' => $mcrypt->encrypt($d_data['potential_date']),
                'receivetype' => $mcrypt->encrypt($d_data['receivetype'])
            );
        }
        $log->write($data);
        //getting total orders
        //$total_orders = $this->model_inventory_purchase_order->getTotalOrders();
        //$log->write($total_orders);
        //getting pages
        //getting pages
        //$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));

        $this->response->setOutput(json_encode($data));
    }

    /* ----------------------------view_order_details function starts here------------ */

    public function orderlistAll() {


        /* getting the list of the orders */
        $mcrypt = new MCrypt();
        $this->adminmodel('inventory/purchase_order');

        if (isset($this->request->get['page'])) {
            $page = $mcrypt->decrypt($this->request->get['page']);
        } else {
            $page = 1;
        }
        $log = new Log("inv-" . date('Y-m-d') . ".log");
        $start = ($page - 1) * 20;
        $limit = 20;
        $log->write('orderlistAll called for seacrh purchase request');
        $log->write($this->request->post);
        $uid = $mcrypt->decrypt($this->request->post['username']);
        $order_id = $mcrypt->decrypt($this->request->post['order_id']);
        $log->write('order_id-' . $order_id . ' && username-' . $uid);
        $da_data = $this->model_inventory_purchase_order->getListStore($uid, $start, $limit, $order_id);
        $log->write($da_data);
        foreach ($da_data as $d_data) {
            $data['order_list'][] = array
                (
                'id' => $mcrypt->encrypt($d_data['id']),
                'order_date' => $mcrypt->encrypt($d_data['order_date']),
                'order_sup_send' => $mcrypt->encrypt($d_data['order_sup_send']),
                'delete_bit' => $mcrypt->encrypt($d_data['delete_bit']),
                'user_id' => $mcrypt->encrypt($d_data['user_id']),
                'receive_date' => $mcrypt->encrypt($d_data['receive_date']),
                'receive_bit' => $mcrypt->encrypt($d_data['receive_bit']),
                'pending_bit' => $mcrypt->encrypt($d_data['pending_bit']),
                'pre_supplier_bit' => $mcrypt->encrypt($d_data['pre_supplier_bit']),
                'order_status_id' => $mcrypt->encrypt($d_data['order_status_id']),
                'canceled_by' => $mcrypt->encrypt($d_data['canceled_by']),
                'canceled_message' => $mcrypt->encrypt($d_data['canceled_message']),
                'store_id' => $mcrypt->encrypt($d_data['store_id']),
                'store_type' => $mcrypt->encrypt($d_data['store_type']),
                'potential_date' => $mcrypt->encrypt($d_data['potential_date']),
                'receivetype' => $mcrypt->encrypt($d_data['receivetype'])
            );
        }
        $log->write($data);
        //getting total orders
        //$total_orders = $this->model_inventory_purchase_order->getTotalOrders();
        //$log->write($total_orders);
        //getting pages
        //getting pages
        //$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));

        $this->response->setOutput(json_encode($data));
    }

    /*
      public function orderlist()
      {



      $mcrypt=new MCrypt();
      $this->adminmodel('inventory/purchase_order');

      if (isset($this->request->get['page'])) {
      $page = $mcrypt->decrypt($this->request->get['page']);
      } else {
      $page = 1;
      }
      $log=new Log("inv-".date('Y-m-d').".log");
      $start = ($page-1)*20;
      $limit = 20;
      $uid=$mcrypt->decrypt($this->request->post['username']);
      $log->write($this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit));
      $data['order_list'] =$mcrypt->encrypt(serialize( $this->model_inventory_purchase_order->getListRecStore($uid,$start,$limit)));


      //getting total orders

      $total_orders = $this->model_inventory_purchase_order->getTotalOrders();
      $log->write($total_orders);
      //getting pages


      //getting pages



      $data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));

      $this->response->setOutput(json_encode($data));

      }
     */
    /* ----------------------------view_order_details function starts here------------ */

    public function order_details() {
        $mcrypt = new MCrypt();
        $log = new Log("receive.log");

        $order_id = $mcrypt->decrypt($this->request->get['order_id']);
        $log->write($order_id);
        $this->adminmodel('inventory/purchase_order');
        $data['order_information'] = $this->model_inventory_purchase_order->view_order_details($order_id);
        //print_r($data['order_information']['products']);
        $log->write($data['order_information']);
        $this->response->setOutput(json_encode($data['order_information']['products']));
    }

    /* ----------------------------view_order_details function ends here-------------- */



    /* -----------------------------insert receive order function starts here------------------- */

    public function receive_order() {

        $mcrypt = new MCrypt();
        $log = new Log("receive-product-" . date('Y-m-d') . ".log");
        $this->adminmodel('inventory/purchase_order');

        $log->write($this->request->post);
        $order_id = $mcrypt->decrypt($this->request->post['order_id']);
        $received_quantities = $this->request->post['receive_quantity'];
        $suppliers_ids = $this->request->post['supplier'];
        if (!empty($received_quantities)) {
            $database_quantity = $this->model_inventory_purchase_order->check_quantity($order_id);
            if ($mcrypt->decrypt($received_quantities[0]) != $database_quantity) {
                $datas['receive_message'] = $mcrypt->encrypt('Please Enter Correct Quantity !');

                $this->response->setOutput(json_encode($datas));
                return;
            }
        }
        $received_product_ids = $this->request->post['product_id'];
        $user_id = $mcrypt->decrypt($this->request->post['username']);
        $driver_otp = $mcrypt->decrypt($this->request->post['driver_otp']);

        $log->write($user_id . "-" . $order_id . "-" . $driver_otp);

        $database_otp = $this->model_inventory_purchase_order->check_driver_otp($order_id);
        $log->write($database_otp);
        if ($driver_otp != $database_otp) {
            $datas['receive_message'] = $mcrypt->encrypt('Entered OTP IS wrong. Please try again ! ');

            $this->response->setOutput(json_encode($datas));
            return;
        }
        $log->write($user_id);
        $this->session->data['user_id'] = $user_id;
        $this->load->library('user');
        $this->user = new User($this->registry);

        $i = 0;
        $received_quantities_de = array();
        foreach ($received_quantities as $qnty) {
            $log->write($qnty);
            if ($i != 0) {

                $received_quantities_de[$i] = "next product";
                $i++;
            }
            $received_quantities_de[$i] = $mcrypt->decrypt($qnty);

            $i++;
        }
        $log->write($received_quantities_de);
        $received_quantities = $received_quantities_de;
        $received_product_idss = array();
        $i = 0;
        foreach ($received_product_ids as $pid) {
            $received_product_idss[$i] = $mcrypt->decrypt($pid);
            $i++;
        }

        $log->write($received_product_idss);
        $received_product_ids = $received_product_idss;


        $order_receive_date = date("Y-m-d"); //$this->request->post['order_receive_date'];
        $prices = $this->request->post['price'];
        $rq = $this->request->post['remaining_quantity'];
        if (isset($this->request->post['disable_bit'])) {
            $data['disable_bit'] = 1;
        }
        /* print_r($received_quantities);
          print_r($suppliers_ids);
          print_r($received_product_ids);
          print_r($order_receive_date);
          print_r($prices);
          exit; */
        $received_order_info['received_quantities'] = $received_quantities;
        $received_order_info['received_product_ids'] = $received_product_ids;
        $received_order_info['suppliers_ids'] = $suppliers_ids;
        $received_order_info['order_receive_date'] = $order_receive_date;
        $received_order_info['prices'] = $prices;

        $received_order_info['rq'] = $rq;
        $url = '';
        if ($order_id) {
            $url .= '&order_id=' . $order_id;
        }
        $log->write("before check");
        if ((count($received_quantities) != count(array_filter($received_quantities))) || (count($prices) != count(array_filter($prices))) || $order_receive_date == '') {
            $_SESSION['empty_fields_error'] = 'Warning: Please check the form carefully for errors!';

            $log->write("in check");



            $data['order_information'] = $this->model_inventory_purchase_order->view_order_details($order_id);

            for ($i = 0; $i < count($data['order_information']['products']); $i++) {
                unset($data['order_information']['products'][$i]['quantities']);
                unset($data['order_information']['products'][$i]['prices']);
            }

            $start_loop = 0;
            $data['validation_bit'] = 1;

            for ($i = 0; $i < count($received_product_ids); $i++) {
                for ($j = $start_loop; $j < count($prices); $j++) {
                    if ($prices[$j] == 'next product') {
                        $start_loop = $j + 1;
                        break;
                    } else {
                        $data['order_information']['products'][$i]['quantities'][$j] = $received_quantities[$j];
                        $data['order_information']['products'][$i]['suppliers'][$j] = $suppliers_ids[$j];
                        $data['order_information']['products'][$i]['prices'][$j] = $prices[$j];
                    }
                }

                $data['order_information']['products'][$i]['quantities'] = array_values($data['order_information']['products'][$i]['quantities']);
                $data['order_information']['products'][$i]['suppliers'] = array_values($data['order_information']['products'][$i]['suppliers']);
                $data['order_information']['products'][$i]['prices'] = array_values($data['order_information']['products'][$i]['prices']);
                $data['order_information']['products'][$i]['rq'] = $received_order_info['rq'][$i];
            }

            $data['order_id'] = $order_id;
            if ($order_receive_date) {
                $data['order_information']['order_info']['receive_date'] = $order_receive_date;
            } else {
                $data['order_information']['order_info']['receive_date'] = '0000-00-00';
            }
            $datas['receive_message'] = $mcrypt->encrypt('Warning: Please check the form carefully for errors!');

            $this->response->setOutput(json_encode($datas));
        } else {
            $log->write("after check");
            if (isset($this->request->post['disable_bit'])) {
                unset($received_order_info['suppliers_ids']);
            }
            $this->adminmodel('inventory/purchase_order');
            $checkAccepted = $this->model_inventory_purchase_order->getList_order($order_id);
            $log->write($checkAccepted); ////this log is added by vipin at 22-may-2018-2.43 pm
            if (!$checkAccepted) {
                $inserted = $this->model_inventory_purchase_order->insert_receive_order($received_order_info, $order_id);
                if ($inserted) {
                    $data['receive_message'] = $mcrypt->encrypt('Order received Successfully!!');
                    $log->write("Order received Successfully!!"); ////this log is added by vipin at 22-may-2018-2.43 pm 
                    $this->response->setOutput(json_encode($data));
                } else {
                    $data['receive_message'] = $mcrypt->encrypt('Sorry!! something went wrong, try again');
                    $log->write("Sorry!! something went wrong, try again"); ////this log is added by vipin at 22-may-2018-2.43 pm
                    $this->response->setOutput(json_encode($data));
                }
            } else {
                $log->write("This order has already been received"); ////this log is added by vipin at 22-may-2018-2.43 pm
                $data['receive_message'] = $mcrypt->encrypt('This order has already been received');
                $this->response->setOutput(json_encode($data));
            }
        }
    }

    /* -----------------------------insert receive order function ends here----------------- */



    /* --------------------Insert Purchase Order starts heres------------------------------------------------- */

    public function request_order() {
        $mcrypt = new MCrypt();
        $data['products'] = $_POST['product'];
        $data['options'] = $_POST['options'];
        $data['option_values'] = $_POST['option_values'];
        $data['quantity'] = $_POST['quantity'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['stores'] = $_POST['stores'];
        $data['sid'] = $_POST['sid'];

        $this->load->library('user');
        $this->user = new User($this->registry);
        $log = new Log("request PO ware house ".date('Y-m-d').".log");
        $log->write($this->request->post); //$_POST);
        $this->session->data['user_id'] = $mcrypt->decrypt($_POST['username']);
        $store_id = $mcrypt->decrypt($_POST['pstore']);
        $log->write($store_id);
        $this->adminmodel('catalog/product');
        $this->adminmodel('report/partner');
        $this->adminmodel('catalog/product');
        /* to let the user add products without options */
        for ($i = 0; $i < count($data['options']); $i++) {
            if ($data['options'][$i] == '') {
                $data['options'][$i] = '0_option';
            }
        }

        /* to let the user add products without option values */
        for ($i = 0; $i < count($data['option_values']); $i++) {
            if ($data['option_values'][$i] == '') {
                $data['option_values'][$i] = '0_optionvalue';
            }
        }
        //if((in_array("--products--",$data['products'])) || (in_array("--stores--",$data['stores'])) || (in_array("--Product Options--",$data['options'])) || (in_array("--Option Values--",$data['option_values'])) || (count($data['quantity']) != count(array_filter($data['quantity']))) || (count($data['options']) != count(array_filter($data['options']))) || (count($data['products']) != count(array_filter($data['products']))) || (count($data['option_values']) != count(array_filter($data['option_values']))))
//                    if((in_array("--products--",$data['products'])) || (in_array("--stores--",$data['stores'])) || (in_array("--Product Options--",$data['options'])) || (in_array("--Option Values--",$data['option_values'])) || (count($data['options']) != count(array_filter($data['options']))) || (count($data['products']) != count(array_filter($data['products']))) || (count($data['option_values']) != count(array_filter($data['option_values']))))
//                    {
//                            $log->write("in if");
//
//                            $data['form_bit'] = 0;
//                            $_SESSION['errors'] = "Warning: Please check the form carefully for errors!";
//                            /*------------Working with data received starts-----*/
//
//                            $i = 0;
//                            foreach($data['products'] as $product)
//                            {
//                                    if(strrchr($product,"_"))
//                                    {
//                                    $product_names[$i] = explode('_',$product);
//                                    }
//                                    else
//                                    {
//                                            $product_names[$i] = $product;
//                                    }
//                                    $i++;
//                            }
//                            $data['product_received'] = $product_names;
//                            $i = 0;
//                            foreach($data['options'] as $option)
//                            {
//                                    if(strrchr($option,"_"))
//                                    {
//                                            $options[$i] = explode('_',$option);
//                                    }
//                                    else
//                                    {
//                                            $options[$i] = $option;
//                                    }
//                                    $i++;
//                            }
//                            $data['options_received'] = $options;
//                            $i = 0;
//                            foreach($data['option_values'] as $option_value)
//                            {
//                                    if(strrchr($option_value,"_"))
//                                    {
//                                            $option_values[$i] = explode('_',$option_value);
//                                    }
//                                    else
//                                    {
//                                            $option_values[$i] = $option_value;
//                                    }
//                                    $i++;
//                            }
//                            $data['option_values_received'] = $option_values;
//                            //print_r($data['option_values_received']);
//                            $data['quantities_received'] = $data['quantity'];
//                            /*------working with data received ends---------*/
//                            $this->load->model('catalog/product');
//                            $products = $this->model_catalog_product->getProducts();
//                            $i = 0;
//                            foreach($products as $product)
//                            {
//                                    $products[$i] = $product['name'];
//                                    $product_ids[$i] = $product['product_id'];
//                                    $i++;
//                            }
//                            $data['products'] = $products;
//                            $data['product_ids'] = $product_ids;
//                            $this->load->model('catalog/option');
//                            $data['options'] = $this->model_catalog_option->getOptions();
//                            /*$i = 0;
//                            foreach($data['options_received'] as $option)
//                            {
//                                    $option_values[$i] = $this->model_catalog_option->getOptionValues($option[0]); 
//                                    $i++;
//                            }*/
//                            $data['option_values'] = $option_values;
//                            $url = '';
//
//                            $this->load->model('catalog/option');
//                            $data['options'] = $this->model_catalog_option->getOptions();
//
//                                            }
//                    else
        {

            $log->write("in else");
//                         $data['quantity']=$mcrypt->decrypt($data['quantity']);                          
            //stores
            $store = $data['stores'];

            $log->write($store);

            $productval = explode('_', $store);
            $store_id = $mcrypt->decrypt($productval[0]);
            $store = $mcrypt->decrypt($productval[0]) . "_" . $mcrypt->decrypt($productval[1]);
            $store_names = explode('_', $store);
            $data['stores'] = $store_names;
            $log->write($store_names);

            //products
            $product = $data['products'];

            $log->write($product);

            $productval = explode('_', $product);
            $product = $mcrypt->decrypt($productval[0]) . "_" . $mcrypt->decrypt($productval[1]);
            $product_names = explode('_', $product);
            //$i++;
            $log->write("product read");
            //check for credit limit data                                    
            $log->write("credit limit data");
            $filter_data = array(
                'filter_stores_id' => $store_id,
                'start' => (1 - 1) * $this->config->get('config_limit_admin'),
                'limit' => $this->config->get('config_limit_admin')
            );
            $store_credit = $this->model_report_partner->getCollection_Report($filter_data);
            $log->write($store_credit);
            if (empty($store_credit)) {
                $log->write("Credit Limit not found,please contact admin");
                $json['error'] = $mcrypt->encrypt('Error: Credit Limit not found,please contact admin');
//                $this->response->setOutput(json_encode($json));	
//                  return;                                        
            }
            //  get product
            $log->write("prd id-" . $mcrypt->decrypt($productval[0]));
            $product_pack_size = $this->model_catalog_product->getProduct($mcrypt->decrypt($productval[0]));
            $log->write("Product pack size limit data-");
            $log->write($product_pack_size);

            $this->adminmodel('po_warehouse/purchase_order');

            $log->write("Modal load true");
            //check for old purchase order is pending or not
            $pending_orders = $this->model_po_warehouse_purchase_order->getList_pending_order($store_id, $mcrypt->decrypt($productval[0]));
            $log->write("credit pending orders -" . json_encode($pending_orders));
            if (count($pending_orders) > 0) {
                $log->write("Old order is pending please check");
                $json['error'] = $mcrypt->encrypt('Error: You have a pending order for some product. please complete the previous order to raise new order.

');
//                $this->response->setOutput(json_encode($json));
//                return;
            }

            //check for credit limit
            $log->write("credit check for product-" . $mcrypt->decrypt($productval[0]));
            $creditdata = $this->model_catalog_product->getProductStoresQuantityCredit($mcrypt->decrypt($productval[0]), $store_id);
            $log->write($creditdata);
            $data['quantity'] = $mcrypt->decrypt($data['quantity']);
            $data['supplier_id'] = $mcrypt->decrypt($data['supplier_id']);
            $log->write($data['quantity']);
            $log->write($creditdata[0]['minquantity']);
            if (empty($creditdata[0]['minquantity'])) {
                $creditdata[0]['minquantity'] = 0;
            }
            $log->write("check");
            if (empty($creditdata[0]['maxquantity'])) {
                $creditdata[0]['maxquantity'] = 0;
            }

            $newprdquantity = $data['quantity'] + $creditdata[0]['quantity'];

            if ($newprdquantity > $creditdata[0]['minquantity'] && $newprdquantity <= $creditdata[0]['maxquantity']) {

                $log->write("check in if");
                $json['sucess'] = $mcrypt->encrypt('sucess: order sucessfuly.');
            } else {
                $log->write("check in else");
                $json['error'] = $mcrypt->encrypt('Error: Please contact your regional head and Zonal head for credit limit adjustment.');
//                                    $this->response->setOutput(json_encode($json));	
//                                    return;                                        
            }
            $log->write("check1");
            //end credit limit  


            $log->write($product_names);
            $data['products'] = $product_names;

            $option = $option_value = 0;
            $data['options'] = $option;
            $log->write("before");

            $data['option_values'] = $option_value;

            // UPdated by Vinay on 23/10/2019  

            $log->write("Before po number");
            $po_number = $mcrypt->decrypt($data['sid']);
            $data['po_number'] = $po_number;
            $data['potentialdate'] = $mcrypt->decrypt($this->request->post['potentialdate']);
            $data['store_type'] = $mcrypt->decrypt($this->request->post['store_type']);
            $data['store_type'] = $mcrypt->decrypt($this->request->post['store_type']);
            $log->write("After po number :" . $po_number);

            $log->write($data);
            $order_id = $this->model_po_warehouse_purchase_order->insert_purchase_order($data);
            $log->write("after id" . $order_id);
            if ($order_id) {

                $_SESSION['success_order_message'] = "The Order has been added";
                $json['order_id'] = $mcrypt->encrypt($order_id);
                $json['success'] = $mcrypt->encrypt('Success: new order placed with ID: ' . $order_id);
                $this->response->setOutput(json_encode($json));
            }
        }
    }

    /* --------------------Insert purchase order ends here---------------------------- */

    ///////////////////////////////////////////////////////////////////////////////////
    //raised po list  For  store created on 22 oct
    public function po_warehouse() {
        /* getting the list of the orders */
        $mcrypt = new MCrypt();
        $this->adminmodel('purchase/purchase_order');

        if (isset($this->request->post['page'])) {
            $page = $mcrypt->decrypt($this->request->post['page']);
        } else {
            $page = 1;
        }
        $log = new Log("powarehouse-po-request-list" . date('Y-m-d') . ".log");
        $start = ($page - 1) * 20;
        $limit = 20;
        $log->write('orderlistAll called for seacrh pending PO of warehouse');
        $log->write($this->request->post);
//        $store_id=$mcrypt->decrypt($this->request->post['store_id']); 
//        $product_id=$mcrypt->decrypt($this->request->post['product_id']); 
//        $status=$mcrypt->decrypt($this->request->post['status']); 
        //  $pstore = $mcrypt->decrypt($this->request->post['pstore']);
        $store_id = $mcrypt->decrypt($this->request->post['store_id']);
        $product_id = $mcrypt->decrypt($this->request->post['product_id']);
        //$status=$this->request->post['status']; 
        $log->write('store_id-' . $store_id . ' && product_id-' . $product_id);
        //$this->load->model('przonal/purchase_order');
        //print_r($this->request->get);exit;

        $filter_data = array(
            'filter_approve_status' => '1',
            'start' => $start,
            'limit' => $limit
        );

        if (isset($store_id) && $store_id !== '') {
            $filter_data['filter_store'] = $store_id;
        }

        if (isset($product_id) && $product_id !== '') {
            $filter_data['filter_product'] = $product_id;
        }

        if (isset($status) && $status !== '') {
            $filter_data['filter_status'] = $status;
        } else {
            $filter_data['filter_status'] = '0';
        }

        $log->write($filter_data);
        if ($store_id != '' && $product_id != '') {

            $po_data = $this->model_purchase_purchase_order->getStorePOList($filter_data);
            $log->write($po_data);
            // var_dump($po_data);exit;
            //$data['po_order_list'] = array();
            if (!empty($po_data) && is_array($po_data)) {
                foreach ($po_data as $ky => $d_data) {
                    $k = $ky + 1;
                    $po_order_list[$ky] = array
                        (
                        'id' => "$k",
                        'sid' => $mcrypt->encrypt($d_data['sid']),
                        //  'sid' => $d_data['sid'],
                        'product' => $mcrypt->encrypt($d_data['product']),
                        'Quantity' => $mcrypt->encrypt($d_data['Quantity']),
                        'rate' => $mcrypt->encrypt($d_data['rate'] != '' ? $d_data['rate'] : 0),
                        'id_prefix' => $mcrypt->encrypt($d_data['id_prefix']),
                        'create_date' => $mcrypt->encrypt($d_data['create_date']),
                        'status' => $mcrypt->encrypt($d_data['status']),
                        'supplier' => $mcrypt->encrypt($d_data['supplier']),
                        'supplier_id' => $mcrypt->encrypt($d_data['supplier_id'])
                    );
                }
                $data['po_order_list'] = $po_order_list;
                $log->write($data);
                //getting total orders
                //$total_orders = $this->model_prinventory_purchase_order->getTotalOrders();
                $log->write($total_orders);
                //getting pages
                //getting pages
                //$data['results'] =$mcrypt->encrypt( sprintf($this->language->get('text_pagination'), ($total_orders) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_orders - $this->config->get('config_limit_admin'))) ? $total_orders : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_orders, ceil($total_orders / $this->config->get('config_limit_admin'))));

                if (!empty($data) && is_array($data)) {
                    $data['success'] = $this->language->get('text_upload');
                } else {
                    $data['error'] = 'Store data not found';
                }
            } else {
                $data['error'] = 'Store purchase order warehouse list not found';
            }
        } else {
            $data['error'] = 'Store_id or Product_id not found';
        }

        $this->response->setOutput(json_encode($data));
    }

}
