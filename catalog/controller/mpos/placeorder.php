<?php

class ControllermposPlaceorder extends Controller {

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

    //\\################ Start## tagged order Api create on 9-12-2019################\\//
    public function placeorder() {

        $log = new Log("dscl placeorder-" . date('Y-m-d') . ".log");
        $log->write('addorder called');
        $log->write($this->request->post);
//         print_r($this->request->post);
//        print_r($_GET);exit;
        $this->adminmodel('pos/pos');
        $this->adminmodel('pos/dscl');
        $this->adminmodel('setting/setting');
        $this->adminmodel('unit/unit');
        $this->load->model('checkout/order');
        $mcrypt = new MCrypt();
        $this->load->model('account/api');

        $currentstatus = $this->model_setting_setting->getBillingStatus('billing');
        if (empty($currentstatus)) {
            //store_id
            $log->write('Billing closed for all store');
            $json['error'] = "Billing is closed for sometime";
            $json['success'] = "-1";
            if (isset($this->request->post['lumpsum'])) {
                $json['dscl_submission'] = "-1";
            }
            $this->response->setOutput(json_encode($json));
            return;
        }
//store based 	
        $currentstatus_store = $this->model_setting_setting->getBillingStatus($mcrypt->decrypt($this->request->post['store_id']));
        if (!empty($currentstatus_store)) {
            //store_id
            $log->write('Billing closed for store-' . $mcrypt->decrypt($this->request->post['store_id']));
            $json['error'] = "Billing is closed for sometime";
            $json['success'] = "-1";
            if (isset($this->request->post['lumpsum'])) {
                $json['dscl_submission'] = "-1";
            }
            $this->response->setOutput(json_encode($json));
            return;
        }


        //$api_info = $this->model_account_api->UserAuthorization($mcrypt->decrypt($this->request->post['user_id']));
        $api_info = $this->model_account_api->UserAuthorization($this->request->post['user_id']);

        if (empty($api_info)) {
            $json['error'] = "User is not Authorized";
            $json['success'] = "-1";
            if (isset($this->request->post['lumpsum'])) {
                $json['dscl_submission'] = "-1";
            }
            $this->response->setOutput(json_encode($json));
            return;
        }
        if (($api_info['user_group_id'] == 36) && ($mcrypt->decrypt($this->request->post['payment_method']) != 'Tagged')) {
            $json['error'] = "User is not authorized for " . $mcrypt->decrypt($this->request->post['payment_method']) . " transaction";
            $json['success'] = "-1";
            $this->response->setOutput(json_encode($json));
            return;
        }
        if (isset($this->request->post['transid'])) {
            $order_istance = $this->model_pos_pos->check_order_instance($mcrypt->decrypt($this->request->post['transid']));
            $log->write("order_istance");
            $log->write($order_istance);
            if (!empty($order_istance)) {//&&(!empty($order_istance['order_id'])))
                $get_bill = $order_istance;
                $invoice_no_instance = '';
                $order_details = $this->model_pos_pos->getOrder($get_bill);
                //if($order_details['order_status_id']=='5')
                //{
                $invoice_no_instance = $order_details['invoice_prefix'] . "-" . $order_details['invoice_no'];
                $log->write('order already placed for this instance ' . $get_bill . "-" . $invoice_no_instance);
                $json['success'] = 'Success: new order placed with ID: ' . $get_bill;
                $json['order_id'] = $get_bill;
                $json['invoice_no'] = $invoice_no_instance;
                $gtax = $this->model_checkout_order->getgtax($get_bill);
                $json['gtax'] = $mcrypt->encrypt(json_encode($gtax));
                $log->write($json);
                $this->response->setOutput(json_encode($json));
                return;
                //}
            }
            $this->model_pos_pos->insert_order_instance($mcrypt->decrypt($this->request->post['transid']), $mcrypt->decrypt($this->request->post['store_id']));
        }
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
            'prdsub',
            'mpin',
            'kitdtl',
            'form_no'
        );
//susidy product
        $unitdata = array();
        // $prdsubs = json_decode($mcrypt->decrypt($this->request->post['prdsub']), true);
        $prdsubs = $mcrypt->decrypt($this->request->post['prdsub']);
        if (!empty($prdsubs)) {
            if ((!empty($this->request->post['CARD_UNIT'])) && (!empty($this->request->post['CARD_COMPANY']))) {
                $unitdata = $this->model_unit_unit->getUnitByComapany_UnitID($this->request->post['CARD_UNIT'], ($this->request->post['CARD_COMPANY']));
                $this->adminmodel('card/integration');
                $log->write('before call to GetGrowerCardMob');
                //  $this->adminmodel('pos/dscl');
                $this->request->post["grower_id"] = $mcrypt->decrypt($this->request->post["growercode"]);
                $this->request->post["unit_id"] = $this->request->post["CARD_UNIT"];
                $log->write($this->request->post["grower_id"]);
                $log->write($this->request->post["unit_id"]);
                $grower_details = $this->{'model_pos_dscl'}->GetGrowerCardMob('GetGrowerCardMob', $this->request->post, 0);
                $log->write($grower_details);
                if (empty($grower_details)) {
                    $json['error'] = " Grower detail not Found ";
                    $json['success'] = "-1";
                    $this->response->setOutput(json_encode($json));
                    return;
                }
                $this->request->post['fname'] = $mcrypt->encrypt($grower_details['RYOT_NAME']);
                $this->request->post['lname'] = $mcrypt->encrypt($grower_details['FTH_HUS_NAME']);
                $this->request->post['vname'] = $mcrypt->encrypt($grower_details['VNAME']);
                $this->request->post['villageid'] = $mcrypt->encrypt($grower_details['VILLAGE_CODE']);
                $this->request->post['otpu'] = 'Cash'; //$mcrypt->decrypt($this->request->post["coupon"]);
                $this->request->post["Card_Serial_Number"] = '0';
                $this->request->post['subsidy_coupon'] = $mcrypt->decrypt($this->request->post["coupon"]);
            }
        }
        $log->write(@$this->request->post['villageid']);

        //end product
        $this->adminmodel('setting/store');
        $store_unit_data = $this->model_setting_store->getUnitsbyStore($mcrypt->decrypt($this->request->post["store_id"]));
        $log->write('before compare unit');
        $log->write($store_unit_data);
        $log->write('unit assign to store - ' . $store_unit_data[0]['unit_id']);

        $log->write('unit from the app - ' . $mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU'])));
        if ($mcrypt->decrypt($this->request->post["payment_method"]) == 'Tagged') {
            $log->write('payment method is cash so we updated the HTT_UCU value to CARD_UNIT =' . $this->request->post["CARD_UNIT"]);
            $this->request->server['HTTP_UCU'] = $mcrypt->encrypt(base64_encode($this->request->post["CARD_UNIT"]));
            $log->write('for payment method cash updated unit from the app - ' . $mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU'])));
            $log->write('for payment method cash updated unit from the app - ' . base64_decode($mcrypt->decrypt($this->request->server['HTTP_UCU'])));
        }
        $log->write('count($store_unit_data)-' . count($store_unit_data));
        //if($mcrypt->decrypt($this->request->post["store_id"])==20)
        if ($mcrypt->decrypt($this->request->post['utype']) == 36) {
            if (empty($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU'])))) {
                $log->write('utype is 36 and HTTP_UCU is empty so we updated the HTT_UCU value to CARD_UNIT =' . $this->request->post["CARD_UNIT"]);
                $this->request->server['HTTP_UCU'] = $mcrypt->encrypt(base64_encode($this->request->post["CARD_UNIT"]));
                $log->write('for utype is 36 unit from the app - ' . $mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU'])));
                $log->write('for utype is 36 updated unit from the app - ' . base64_decode($mcrypt->decrypt($this->request->server['HTTP_UCU'])));
            } else {
                $log->write('utype is 36 and HTTP_UCU is not empty so no need to update HTTP_UCU');
            }
        }
        if (count($store_unit_data) > 1) {
            $not_matched = 1;
            foreach ($store_unit_data as $store_unit_data2) {
                if ($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU'])) == $store_unit_data2['unit_id']) {
                    $not_matched = 0;
                    break;
                }
                if (base64_decode($mcrypt->decrypt($this->request->server['HTTP_UCU'])) == $store_unit_data2['unit_id']) {
                    $not_matched = 0;
                    break;
                }
                $log->write('$not_matched = ' . $not_matched);
            }
            $log->write('$not_matched = ' . $not_matched);
            if ($not_matched == 1) {
                $log->write('Unit Mismatch for multiple units');
                $json['error'] = " Unit Mismatch ";
                $json['success'] = "-1";
                return $this->response->setOutput(json_encode($json));
                $this->request->server['HTTP_UCU'] = $mcrypt->encrypt(base64_encode($this->request->server['HTTP_UCU']));
            }
        } else {
            if ((!empty($store_unit_data[0]['unit_id'])) && (!empty($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU']))))) {// && ($this->request->post['payment_method']=='Tagged Subsidy'))
                if ($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU'])) != $store_unit_data[0]['unit_id']) {
                    $log->write('Unit Mismatch');
                    $json['error'] = " Unit Mismatch ";
                    $json['success'] = "-1";
                    return $this->response->setOutput(json_encode($json));
                    $this->request->server['HTTP_UCU'] = base64_encode($mcrypt->encrypt($store_unit_data[0]['unit_id']));
                } else {
                    $log->write('Unit match');
                }
            }
        }
        $log->write('before base64');
        ////////for card start here///////
        $log->write($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UPN'])));
        //$unitdata=array();
        if (!empty($this->request->server['HTTP_UPN'])) {
            $this->request->post['subsidy_coupon'] = $mcrypt->decrypt($this->request->post["coupon"]);
            $UPN = $mcrypt->decrypt(base64_decode($this->request->server['HTTP_UPN']));
            $log->write($this->request->post["cdata"]);
            $this->request->post["grower_id"] = $mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCG']));
            $this->request->post["Card_Serial_Number"] = $mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCN']));
            $log->write($this->request->post["grower_id"]);
            $log->write($this->request->post["Card_Serial_Number"]);


            if (empty($this->request->post["grower_id"])) {
                $log->write('grower id is empty');

                $json['error'] = "You are not authorized for billing through Card Serial Number";
                $json['success'] = "-1";
                return $this->response->setOutput(json_encode($json));

                if ($mcrypt->decrypt($this->request->post['utype']) == 11) {

                    $retval['status'] = $mcrypt->encrypt('0');

                    $retval['message'] = $mcrypt->encrypt('You are not authorized for billing through Card Serial Number');
                    $log->write('You are not authorized for billing through Card Serial Number');
                    $log->write($retval);
                    $this->response->addHeader('Content-Type: application/json');
                    return $this->response->setOutput(json_encode($retval));
                }

                $cdatanew = $this->model_pos_dscl->GetGrowerId("GetGrowerId", $this->request->post, 0);
                $log->write($cdatanew);
                //   $cdatanew = $this->model_unit_unit->getGrowerIdByCard($this->request->post["Card_Serial_Number"]);
                // $log->write($cdatanew);
                if (empty($cdatanew)) {
                    $retval['status'] = $mcrypt->encrypt('0');
                    $retval['message'] = $mcrypt->encrypt('No Record Found');
                    $log->write($retval);
                    $this->response->addHeader('Content-Type: application/json');
                    return $this->response->setOutput(json_encode($retval));
                }
                $this->request->post["grower_id"] = $cdatanew['GROWER_ID'];
                $unitdata = $this->model_unit_unit->getUnitByID($cdatanew['UNIT_ID']);
                $log->write($unitdata);
                $this->request->post["cdata"]->UN = $cdatanew['UNIT_ID'];

                $this->request->post['CARD_UNIT'] = $cdatanew['UNIT_ID'];
                $this->request->post['unit_id'] = $cdatanew['UNIT_ID'];
                $this->request->post['CARD_GROWER_ID'] = $cdatanew['GROWER_ID'];
                if (empty($cdatanew['COMPANY_ID'])) {
                    $cdatanew['COMPANY_ID'] = "1";
                }
                $this->request->post['CARD_COMPANY'] = $cdatanew['COMPANY_ID'];
            } else {
                $this->request->post['CARD_UNIT'] = $mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCU']));
                $this->request->post['CARD_COMPANY'] = substr($mcrypt->decrypt(base64_decode($this->request->server['HTTP_UCI'])), 0, 1);
            }

            $this->request->post["otp"] = $UPN;
            $this->request->post["TX"] = "1";
            $this->request->post['otpu'] = $UPN;
            //$log->write($this->request->post);

            $log->write($this->request->post);
            //check for pin of system not authorized		
            $otpdata = $this->model_card_integration->check_otp($this->request->post);
            $log->write($otpdata);
            if (!empty($otpdata)) {
                if ($otpdata['otp'] != $UPN) {
                    //mobile number not defined
                    $json['error'] = "OTP number not matched";
                    $json['success'] = "-1";
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            } else {
                $json['error'] = "OTP number not found";
                $json['success'] = "-1";
                $this->response->setOutput(json_encode($json));
                return;
            }
            //get unit data
            $log->write('before getunitbycompany_unitid');
            $this->adminmodel('unit/unit');
            $unitdata = $this->model_unit_unit->getUnitByComapany_UnitID($this->request->post['CARD_UNIT'], ($this->request->post['CARD_COMPANY']));
            $log->write($unitdata);
            //check card authentication
            if (!empty($unitdata['company_name'])) {
                $company = strtolower($unitdata['company_name']);
                $this->adminmodel('pos/' . $company);
                $dataAuthentication = $this->{'model_pos_' . $company}->GetAuthentication('GetAuthentication', $this->request->post, 0);
                $log->write('after dataauthentication');
                $log->write($dataAuthentication);
                //$dataAuthentication['AMOUNT']=400;
                if (!empty($dataAuthentication) && $dataAuthentication['CARD_STATUS'] == '9') {
                    //check pin 
                    if (empty($this->request->post['mpin'])) {
                        if ($dataAuthentication['GROWER_ID'] != $UPN) {
                            $json['error'] = "Wrong PIN.";
                            $json['success'] = "-1";
                            $this->response->setOutput(json_encode($json));
                            return;
                        }
                    } else {

                        if ($api_info['mpin'] != $UPN) {
                            $json['error'] = "Wrong Master PIN.";
                            $json['success'] = "-1";
                            $this->response->setOutput(json_encode($json));
                            return;
                        }
                    }

                    //check pin 
                    $log->write('mpin by post');
                    $log->write($mcrypt->decrypt($this->request->post['mpin']));

                    $log->write('pin by post');
                    $log->write($UPN);

                    $log->write('api_info mpin');
                    $log->write($api_info['mpin']);

                    if ($mcrypt->decrypt($this->request->post['mpin']) != $api_info['mpin'] && ($api_info['user_group_id'] == 11)) {//empty($mcrypt->decrypt($this->request->post['mpin'])))
                        $log->write('in if');
                        if ($dataAuthentication['GROWER_ID'] != $UPN) {
                            $log->write('Wrong PIN.');
                            $json['error'] = "Wrong PIN.";
                            $json['success'] = "-1";
                            $this->response->setOutput(json_encode($json));
                            return;
                        }
                    } else if ($mcrypt->decrypt($this->request->post['mpin']) == 0 && ($api_info['mpin'] == 0) && ($api_info['user_group_id'] == 11)) {//empty($mcrypt->decrypt($this->request->post['mpin'])))
                        $log->write('in else if');
                        if ($dataAuthentication['GROWER_ID'] != $UPN) {
                            $json['error'] = "Wrong PIN.";
                            $json['success'] = "-1";
                            $this->response->setOutput(json_encode($json));
                            return;
                        }
                    } else {
                        $log->write('in else');

                        if (($api_info['mpin'] != $UPN) && ($api_info['user_group_id'] == 36)) {
                            $log->write("Wrong Master PIN.");
                            $json['error'] = "Wrong Master PIN.";
                            $json['success'] = "-1";
                            $this->response->setOutput(json_encode($json));
                            return;
                        }
                    }
                    $log->write($mcrypt->decrypt($this->request->post['INVAMOUNT']));
                    $log->write($mcrypt->decrypt($this->request->post['sub']));

                    //check amount 

                    $this->adminmodel('pos/dscl');
                    if ($dataAuthentication['AMOUNT'] <= 0) {

                        if (($mcrypt->decrypt($this->request->post['INVAMOUNT']) - $mcrypt->decrypt($this->request->post['sub'])) > 0) {
                            //pay cash only
                            $json['error'] = "Grower balance is zero.";
                            $json['success'] = "-1";
                            $this->response->setOutput(json_encode($json));
                            return;
                        }
                        ///get grower details////x
                        $log->write('before call to get_grower_by_card in zero');
                        $grower_details = $this->model_card_integration->get_grower_by_card($this->request->post["Card_Serial_Number"], $this->request->post["grower_id"]);
                        $log->write($grower_details);
                        $this->request->post['fname'] = $mcrypt->encrypt($grower_details['GROWER_NAME']);
                        $this->request->post['lname'] = $mcrypt->encrypt($grower_details['FTH_HUS_NAME']);
                        $this->request->post['vname'] = $mcrypt->encrypt($grower_details['VILLAGE_NAME']);
                        $this->request->post['villageid'] = $mcrypt->encrypt($grower_details['VILLAGE_ID']);
                    } else {
                        //tagged data amtcash.
                        ///get grower details////
                        $log->write('before call to get_grower_by_card');
                        $grower_details = $this->model_card_integration->get_grower_by_card($this->request->post["Card_Serial_Number"], $this->request->post["grower_id"]);
                        $log->write($grower_details);
                        $this->request->post['fname'] = $mcrypt->encrypt($grower_details['GROWER_NAME']);
                        $this->request->post['lname'] = $mcrypt->encrypt($grower_details['FTH_HUS_NAME']);
                        $this->request->post['vname'] = $mcrypt->encrypt($grower_details['VILLAGE_NAME']);
                        $this->request->post['villageid'] = $mcrypt->encrypt($grower_details['VILLAGE_ID']);
                        if ($dataAuthentication['AMOUNT'] < ($mcrypt->decrypt($this->request->post['INVAMOUNT']))) {
                            //$this->request->post['TAGGEDRATIO']=$dataAuthentication['AMOUNT']/($mcrypt->decrypt($this->request->post['INVAMOUNT']));
                            $log->write('in if AMOUNT is less then INVAMOUNT');
                            $log->write($dataAuthentication['AMOUNT']);
                            $log->write((float) ($mcrypt->decrypt($this->request->post['INVAMOUNT'])));
                            $log->write((float) ($mcrypt->decrypt($this->request->post['sub'])));
                            $this->request->post['TAGGEDRATIO'] = $dataAuthentication['AMOUNT'] / ((float) ($mcrypt->decrypt($this->request->post['INVAMOUNT'])) - (float) ($mcrypt->decrypt($this->request->post['sub'])));
                            $log->write('TAGGEDRATIO');
                            $log->write($this->request->post['TAGGEDRATIO']);
                            
                            //\\################################## Add Check point for card amount ########################################//\\
                            
                            
                        }
                    }
                } else {
                    $json['error'] = "CARD in Valid";
                    $json['success'] = "-1";
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            } else {
                $json['error'] = "Unit not defined";
                $json['success'] = "-1";
                $this->response->setOutput(json_encode($json));
                return;
            }
        }

        $log->write('after card end');
        /////////for card end here//////////////

        foreach ($keys as $key) {
            $this->request->post[$key] = $mcrypt->decrypt($this->request->post[$key]);
        }
//log to system table
        $this->load->model('checkout/order');
        $this->load->model('account/activity');
        $activity_data = array(
            'customer_id' => $mcrypt->decrypt($this->request->post['username']),
            'data' => json_encode($this->request->post),
        );

        //$this->model_account_activity->addActivity('Order', $activity_data);
        //$this->request->post['storeid'] = $this->request->post['store_id'];
        $data = $this->request->post['store_id'];
        $log->write($data);
        $log->write("before companydata");
        $companydata = $this->model_pos_pos->getunitidandcompanyid('46');
        $log->write($companydata);
        if (!empty($companydata)) {
            $data['unitid'] = $companydata[0]['unit_id'];
            $log->write($companydata);
            $company = strtolower($companydata[0]['company_name']);
            $log->write($company);
        }
        if (($company == 'dscl') && ($this->request->post['payment_method'] != 'Tagged')) {
            $log->write($this->request->post['payment_method']." payment is blocked for sometime.");
            $json['error']=$this->request->post['payment_method']." payment is blocked for sometime.";
            $json['success'] = "-1";					
            $this->response->setOutput(json_encode($json));	
            return;
        }
       
        if ($company != "bcml" && ($this->request->post['payment_method'] == 'Tagged')) {
            //check for mobile is set of not
            if (!isset($this->request->post['lumpsum'])) {
                if (empty($this->request->post['customer_mob'])) {
                    //mobile number not defined
                    $json['error'] = "Mobile number not defined";
                    $json['success'] = "-1";
                    if (isset($this->request->post['lumpsum'])) {
                        $json['dscl_submission'] = "-1";
                    }
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            }

            //check for mobile is set of not
            if (!isset($this->request->post['lumpsum'])) {
                if (empty($this->request->post['customer_mob'])) {
                    //mobile number not defined
                    $json['error'] = "Mobile number not defined";
                    $json['success'] = "-1";
                    if (isset($this->request->post['lumpsum'])) {
                        $json['dscl_submission'] = "-1";
                    }

                    $this->response->setOutput(json_encode($json));
                    return;
                }
            }
        }
///check ase order
        if (($this->request->post['payment_method'] == 'Tagged') && ($this->request->post['comment'] != '')) {
            try {
                $ase_data = $this->model_pos_pos->getaseorderstatus($this->request->post['comment']);
                if ((!empty($ase_data)) && (count($ase_data) > 0)) {
                    $get_bill = $ase_data["order_id"];
                    $order_details = $this->model_pos_pos->getOrder($get_bill);
                    $invoice_no_instance = $order_details['invoice_prefix'] . "-" . $order_details['invoice_no'];
                    $json['invoice_no'] = $invoice_no_instance;
                    $json['orddate'] = $ase_data["date_added"];
                    $log->write('order already placed with inv no-' . $get_bill . ' for bill id-' . $this->request->post['comment']);
                    $json['success'] = 'Success: new order placed with ID: ' . $get_bill;
                    $json['order_id'] = $get_bill;
                    $gtax = $this->model_checkout_order->getgtax($get_bill);
                    $json['gtax'] = $mcrypt->encrypt(json_encode($gtax));
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            } catch (Exception $e) {
                $json['error'] = $e;
                $json['success'] = "-1";              
                return $this->response->setOutput(json_encode($json));
            }
        }

//        try {
//            if (($company != 'isec') && ($this->request->post['payment_method'] !== 'Subsidy')) {
//                $this->request->post['comment'] = '';
//                $log->write('payment_method is Subsidy and company is not isec so comment value will be NULL');
//            }
//        } catch (Exception $e) {
//            
//        }
        try {
            if (($company = 'dscl') && ($this->request->post['payment_method'] == 'Tagged')) {
              //  $this->request->post['comment'] = '';
                $log->write('payment_method is Tagged and company is dscl');
            }
        } catch (Exception $e) {
            $json['error'] = $e;
            $json['success'] = "-1";              
            return $this->response->setOutput(json_encode($json));
        }

//check old
        if (!isset($this->request->post['lumpsum'])) {
            if (isset($this->request->post['comment']) && (!empty($this->request->post['comment']))) {
                $log->write('come in the condition of the comment is not empty');
                $this->adminmodel('lead/orderleads');
                $get_bill = $this->model_lead_orderleads->getrequisition_to_bil($this->request->post['comment']);
                if (!empty($get_bill)) {
                    $log->write('order already placed with inv no-' . $get_bill . ' for bill id-' . $this->request->post['comment']);
                    $order_details = $this->model_pos_pos->getOrder($get_bill);
                    $invoice_no_instance = $order_details['invoice_prefix'] . "-" . $order_details['invoice_no'];
                    $json['invoice_no'] = $invoice_no_instance;
                    $json['orddate'] = date('Y-m-d h:i:s A');
                    $json['success'] = 'Success: new order placed with ID: ' . $get_bill;
                    $json['order_id'] = $get_bill;
                    $gtax = $this->model_checkout_order->getgtax($get_bill);
                    $json['gtax'] = $mcrypt->encrypt(json_encode($gtax));
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            }
        }
        $log->write($this->request->post);
        //productdetail
        $prds = json_decode($this->request->post['prddtl'], true);
        unset($this->session->data['user_id']);
        $this->session->data['user_id'] = $this->request->post['user_id'];
        $customer_id = $this->request->post['customer_id'];
//check customer
        if (isset($customer_id)) {
            $log->write("user id in " . $this->request->post['customer_mob']);
            //check customer
            $customer_id = $this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"];
            $log->write("user id in t " . $customer_id);
            if (empty($customer_id)) {
                $this->addcustomer($this->request->post['store_id']);
                $customer_id = $this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"];
                $log->write("user= " . $customer_id);
                $this->request->post['customer_id'] = $customer_id;
            }
        }

        $data['store_id'] = $this->request->post['store_id'];
        $data['storeid'] = $this->request->post['store_id'];
        $this->config->set('config_store_id', $data['store_id']);
        $data['store_name'] = $this->config->get('config_name');
        //$data['store_url'] = $this->config->get('config_url');
        //check for product quantity
        $this->load->model('catalog/product');
        $this->adminmodel('pos/dscl');
        foreach ($prds as $prd) {
            if ($prd['product_quantity'] < 1) {
                $json['error'] = "Minimum Quantity should be 1";
                $json['success'] = "-1";
                $this->response->setOutput(json_encode($json));
                return;
            }
            /*
              $cardsql="select  order_id  from oc_order_req_delivery where EMAIL!='tech@em3agri.com' and  UNIT_CODE=".$filter_unit." and TO_CHAR(DATE_ADDED,'YYYY-MM-dd')='".$filter_date."' and ORDER_STATUS_ID='5' ";

              $filter_data_sql = array(

              'sql'=> $cardsql
              );
              //echo "sdbhn";
              //print_r($filter_data);
              $dsclfinaldata=array();
              $akshfinaldata=array();
              $dscl_count = $this->model_pos_dscl->GetCardDataSql('GetCardDataSql',$filter_data_sql,0);
             */
            if (($company == "dscl") && ($this->request->post['payment_method'] != "Cash")) {
                $matcode = 100000 + $prd['product_id'];
                $cardsql = "SELECT COUNT(1) as cnt FROM MATERIAL_MASTER WHERE MATCODE='" . $matcode . "'";

                $filter_data_sql = array(
                    'sql' => $cardsql
                );
                $dscl_count = $this->model_pos_dscl->GetCardDataSql('GetCardDataSql', $filter_data_sql, 0);
                $log->write("dscl_count= ");
                $log->write($dscl_count);
                if (empty($dscl_count[0]['CNT'])) {
                    $log->write("in check if");
                    //not save data
                    $json['error'] = " No product (" . $product_info['name'] . ") found at cane server";
                    $json['success'] = "-1";
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            }
            $log->write($prd['product_id']);
            $log->write("quantity check");
            $product_info = $this->model_catalog_product->getProduct($prd['product_id']);
            $log->write($product_info['squantity']);
            if ($product_info) {

                /////////////reserved store quantity////////// 
                if ($product_info['squantity'] > 1) {
                    if (($product_info['squantity'] - $prd['product_quantity']) < 0) {
                        $json['error'] = "" . $prd['product_quantity'] . " quantity for " . $product_info['name'] . " negative match with system";
                        $json['success'] = "-1";
                        /*
                          if(isset($this->request->post['lumpsum']))
                          {
                          $json['dscl_submission'] = "-1";
                          }
                         */
                        $log->write($prd['product_quantity'] . " quantity for " . $product_info['name'] . " negative match with system");
                        $this->response->setOutput(json_encode($json));
                        return;
                    }

                    if ($product_info['squantity'] < $prd['product_quantity']) {
                        $json['error'] = "" . $prd['product_quantity'] . " quantity for " . $product_info['name'] . " not match with system";
                        $json['success'] = "-1";
                        /*
                          if(isset($this->request->post['lumpsum']))
                          {
                          $json['dscl_submission'] = "-1";
                          }
                         */
                        $log->write($prd['product_quantity'] . " quantity for " . $product_info['name'] . " not match with system");
                        $this->response->setOutput(json_encode($json));
                        return;
                    }
                    if ($mcrypt->decrypt($this->request->post['utype']) == 36) {
                        $log->write("sub user quantity check");
                        $this->load->model('account/subuser');
                        $results = $this->model_account_subuser->getBilledMaterialProductBased($this->request->post['user_id'], $prd['product_id']);
                        $log->write($results);
                        if (empty($results) && $results <= 0) {
                            $log->write("in check if");
                            //not save data
                            $json['error'] = $prd['product_quantity'] . " quantity for " . $product_info['name'] . " not match with system";
                            $json['success'] = "-1";
                            $this->response->setOutput(json_encode($json));
                            return;
                        }
                        //check balance qunty
                        if ($results < $prd['product_quantity']) {
                            $log->write("in quantity check if");
                            $json['error'] = $prd['product_quantity'] . " quantity for " . $product_info['name'] . " not match with system";
                            $json['success'] = "-1";
                            $this->response->setOutput(json_encode($json));
                            return;
                        }
                    }
                } else {
                    $json['error'] = "Stock is low";
                    $json['success'] = "-1";
                    $log->write($prd['product_quantity'] . " quantity for " . $product_info['name'] . " not match with system");
                    $this->response->setOutput(json_encode($json));
                    return;
                }
                // End lpccoder mod
            } else {
                $json['error'] = "Product not found please contact admin";
                $json['success'] = "-1";
                if (isset($this->request->post['lumpsum'])) {
                    $json['dscl_submission'] = "-1";
                }
                $this->response->setOutput(json_encode($json));
                return;
            }
        }

//end qnty
//check fm qunty

        $this->adminmodel('setting/store');

        if (isset($this->request->post["stock_fm"]) && $this->request->post["stock_fm"] != "") {
            foreach ($prds as $prd) {
                $log->write("quantity check for contractor " . $this->request->post["stock_fm"]);
                $product_info = $this->model_setting_store->getProduct($prd['product_id'], $this->request->post["stock_fm"], $data['store_id']);
                $log->write($product_info);
                if ($product_info) {
                    if ($product_info['quantity'] < $prd['product_quantity']) {
                        $json['error'] = "" . $prd['product_quantity'] . " quantity for " . $product_info['name'] . " not match with contractor";
                        $json['success'] = "-1";
                        if (isset($this->request->post['lumpsum'])) {
                            $json['dscl_submission'] = "-1";
                        }
                        $this->response->setOutput(json_encode($json));
                        return;
                    }
                } else {
                    $json['error'] = "" . $prd['product_quantity'] . " quantity for " . $product_info['name'] . " not match with contractor";
                    $json['success'] = "-1";
                    if (isset($this->request->post['lumpsum'])) {
                        $json['dscl_submission'] = "-1";
                    }
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            }
        }
//end fm
        $spray_array = array('27', '345', '330', '209', '350', '72');
        if (($this->request->post['spray'] == "spray") && !empty($this->request->post['spray'])) {
            foreach ($prds as $prd) {
                $log->write($prd['product_id']);
                if (!in_array($prd['product_id'], $spray_array)) {
                    $log->write($prd['product_id'] . "  is not spray product");
                    $json['error'] = $prd['product_name'] . "  is not spray product";
                    $json['success'] = "-1";
                    if (isset($this->request->post['lumpsum'])) {
                        $json['dscl_submission'] = "-1";
                    }
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            }
        }
//add data to cart
        foreach ($prds as $prd) {
            $log->write($prd['product_id']);
            $log->write($this->addToCart($prd['product_id'], $prd['product_quantity']));
        }
        $log->write("after product submit");
        unset($this->session->data['shipping_method']);
        ///////////////////////////////////////////////////////////////////////////////////
        $this->load->language('checkout/coupon');
        unset($this->session->data['coupon']);
        unset($this->session->data['coupon_store']);
        if ((!isset($this->request->post['prdsub'])) && (!empty($this->request->post['prdsub']))) {

            //$this->coupon();
            if (isset($this->request->post['coupon']) && (!empty($this->request->post['coupon']))) {
                $this->load->model('checkout/coupon');
                //$this->request->post['coupon']=2222;
                if (isset($this->request->post['coupon'])) {
                    $coupon = $this->request->post['coupon'];
                    $this->session->data['coupon_store'] = $this->request->post['store_id'];
                } else {
                    $coupon = '';
                }
                $coupon_info = $this->model_checkout_coupon->getCoupon($coupon);
                $log->write($coupon_info);
                if (empty($this->request->post['coupon'])) {
                    $log->write($this->language->get('error_empty'));
                    $json['error'] = $this->language->get('error_empty');
                } elseif ($coupon_info) {
                    $this->session->data['coupon'] = $this->request->post['coupon'];
                    $log->write($this->language->get('text_success'));
                    $this->session->data['success'] = $this->language->get('text_success');
                } else {
                    $log->write($this->language->get('error_coupon'));
                    $json['error'] = $this->language->get('error_coupon');
                    $json['success'] = "-1";
                    if (isset($this->request->post['lumpsum'])) {
                        $json['dscl_submission'] = "-1";
                    }
                    $this->response->setOutput(json_encode($json));
                    return;
                }
            }
        }
        ///////////////////////////////   

        $log->write("after product submit");
        unset($this->session->data['shipping_method']);
        $data = array();
        if (!empty($this->request->post['form_no'])) {
            $data["subsidy_form_no"] = $this->request->post['form_no'];
        } else {
            $data["subsidy_form_no"] = '';
        }
        if ($company == "dscl") {
            $data["MPIN"] = $this->request->post['mpin'];
        } else {
            $data["MPIN"] = 0;
        }
        if (!empty($this->request->post['TAGGEDRATIO'])) {
            $data['TAGGEDRATIO'] = $this->request->post['TAGGEDRATIO'];
        } else {
            $data['TAGGEDRATIO'] = 1;
        }
//card detail
        if (isset($this->request->post["grower_id"])) {
            $data["grower_id"] = $this->request->post["grower_id"];
        }
        if (isset($this->request->post["otpu"])) {
            $data["otpu"] = $this->request->post["otpu"];
        }
        if (isset($this->request->post['subsidy_coupon'])) {
            $data["subsidy_coupon"] = $this->request->post['subsidy_coupon'];
        } else {
            $data["subsidy_coupon"] = '0';
        }

        if (isset($this->request->post["Card_Serial_Number"])) {
            $data["Card_Serial_Number"] = $this->request->post["Card_Serial_Number"];
        }
        if (isset($this->request->post["CARD_UNIT"])) {
            $data["CARD_UNIT"] = $this->request->post["CARD_UNIT"];
        }
        //$data["qrstr"]				
//validation 
        $errors = '';
        $payment_method = $this->request->post['payment_method'];
        $is_guest = $this->request->post['is_guest'];
        $customer_id = $this->request->post['customer_id'];
        $card_no = $this->request->post['card_no'];
        $data['comment'] = $this->request->post['comment'];

        if ($is_guest == 'false' && $customer_id == '') {
            $errors .= 'Select the customer.<br />';
        }

        if (($payment_method == 'Card') && $card_no == '') {
            $errors .= 'Enter the card number.<br />';
        }

        if ($errors != '') {
            $data['errors'] = $errors;
            $this->response->setOutput(json_encode($data));
            return;
        }

        if (isset($this->request->post['stock_fm'])) {
            $data["stock_fm"] = $this->request->post['stock_fm'];
        }

        $data['store_id'] = $this->request->post['store_id'];

        $default_country_id = $this->config->get('config_country_id');
        $default_zone_id = $this->config->get('config_zone_id');
        $data['shipping_country_id'] = $default_country_id;
        $data['shipping_zone_id'] = $default_zone_id;
        $data['payment_country_id'] = $default_country_id;
        $data['payment_zone_id'] = $default_zone_id;
        $data['customer_id'] = 0;
        $data['customer_group_id'] = 1;
        $data['firstname'] = 'Walkin';
        $data['lastname'] = "Customer";
        $data['email'] = '';
        $data['telephone'] = $this->request->post['customer_mob'];
        $data['fax'] = '';
        $data['payment_firstname'] = 'Walkin';
        $data['payment_lastname'] = "Customer";
        $data['payment_company'] = $this->request->post['spray'];
        $data['payment_company_id'] = '';
        $data['payment_tax_id'] = '';


        $data['payment_address_2'] = '';
        $data['payment_city'] = '';
        $data['payment_postcode'] = '';
        $data['payment_country_id'] = '';
        $data['payment_zone_id'] = '';
        $data['payment_method'] = $payment_method;
        $data['payment_code'] = 'in_store';
        $data['subsidy_cat_id'] = 0;

        if ($payment_method == 'Tagged') {
            if ((isset($this->request->post['vname'])) && (!empty($this->request->post['vname'])) && (($this->request->post['vname'] != '0'))) {
                $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']);
            } else if (isset($this->request->post['growercode'])) {
                $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']);
            } else {
                $data['payment_address_1'] = '';
            }
        }
//        if ($payment_method !== 'Tagged Cash') {
//            if ((isset($this->request->post['vname'])) && (!empty($this->request->post['vname'])) && (($this->request->post['vname'] != '0'))) {
//                $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']);
//            } else if (isset($this->request->post['growercode'])) {
//                $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']);
//            } else {
//                $data['payment_address_1'] = '';
//            }
//        } 
//        elseif ($payment_method !== 'Subsidy') {
//            if ((isset($this->request->post['vname'])) && (!empty($this->request->post['vname'])) && (($this->request->post['vname'] != '0'))) {
//                $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']);
//            } else if (isset($this->request->post['growercode'])) {
//                $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['unit']);
//            } else {
//                $data['payment_address_1'] = '';
//            }
//            if (!empty($this->request->post['catid'])) {
//                $data['subsidy_cat_id'] = $this->request->post['catid'];
//            }
//
//            //$data['unitid']=$mcrypt->decrypt($this->request->post['unit']);
//        } 
//        else {
//            $log->write('in else of payment_method');
//            if ((isset($this->request->post['vname'])) && (!empty($this->request->post['vname'])) && (($this->request->post['vname'] != '0'))) {
//                $log->write('in if vname not empty');
//                $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['vname']);
//            } else if (isset($this->request->post['growercode'])) {
//                $data['payment_address_1'] = $mcrypt->decrypt($this->request->post['growercode']);
//            } else {
//                $data['payment_address_1'] = '';
//            }
//
//            if (!empty($companydata)) {
//                $data['unitid'] = $companydata[0]['unit_id'];
//            } else {
//                $data['unitid'] = '0';
//            }
//            if (isset($this->request->post['fm_code'])) {
//                $data['fm_code'] = $mcrypt->decrypt($this->request->post['fm_code']);
//            } else {
//                $data['fm_code'] = '';
//            }
//            $log->write($data['payment_address_1']);
//        }

        if ($payment_method == 'Tagged') {
            if (isset($this->request->post['cid'])) {
                $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['cid']);
            } else {
                $data['shipping_firstname'] = '';
            }


//          
//        } elseif ($payment_method !== 'Cash') {
//            $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['growercode']);
//        } elseif ($payment_method !== 'Subsidy') {
//            $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['growercode']);
//        } elseif   ($payment_method !== 'Cash Subsidy') {
//            $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['growercode']);
//            $this->request->post['grower_id'] = $mcrypt->decrypt($this->request->post['growercode']);
//
//
//            if (empty($data['shipping_firstname'])) {
//
//                $data['shipping_firstname'] = '99999';
//            }
//        } elseif ($payment_method !== 'Tagged Cash') {
//            if (isset($this->request->post['cid'])) {
//                $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['cid']);
//            } else {
//                $data['shipping_firstname'] = '';
//            }
//        } elseif ($payment_method !== 'Tagged Subsidy') {
//            if (isset($this->request->post['cid'])) {
//                $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['cid']);
//            } else {
//                $data['shipping_firstname'] = '';
//            }
//        } elseif ($payment_method !== 'Tagged Cash Subsidy') {
//            if (isset($this->request->post['cid'])) {
//                $data['shipping_firstname'] = $mcrypt->decrypt($this->request->post['cid']);
//            } else {
//                $data['shipping_firstname'] = '';
//            }
        } else {
            $data['shipping_firstname'] = '';
        }
        $data['shipping_lastname'] = '';
        $data['shipping_company'] = '';
        $data['shipping_address_1'] = '';
        $data['shipping_address_2'] = '';
        $data['shipping_city'] = '';
        $data['shipping_postcode'] = '';
        $data['shipping_country_id'] = '';
        $data['shipping_zone_id'] = '';
        $data['shipping_method'] = 'Pickup From Store';
        $data['shipping_code'] = 'pickup.pickup';
        $data['order_status_id'] = 5;
        $this->request->post['order_status_id'] = 5;

        if ($payment_method == 'Tagged') {
            $log->write("in tagged payment_method");
            $data['order_status_id'] = 1;
            $this->request->post['order_status_id'] = 1;
//        } else if ($payment_method == 'Tagged Cash') {
//            $log->write("in tagged cash payment_method");
//            $data['order_status_id'] = 1;
//            $this->request->post['order_status_id'] = 1;
//        } else if ($payment_method == 'Tagged Subsidy') {
//            $log->write("in Tagged Subsidy payment_method");
//            $data['order_status_id'] = 1;
//            $this->request->post['order_status_id'] = 1;
//        } else if ($payment_method == 'Tagged Cash Subsidy') {
//            $log->write("in Cash Subsidy payment_method");
//            $data['order_status_id'] = 1;
//            $this->request->post['order_status_id'] = 1;
//        } else if ($payment_method == 'Cash Subsidy') {
//            $log->write("in Cash Subsidy payment_method");
//            $data['order_status_id'] = 1;
//            $this->request->post['order_status_id'] = 1;
        } else {
            $data['order_status_id'] = 5;
            $this->request->post['order_status_id'] = 5;
        }
        $data['affiliate_id'] = isset($this->request->post['affiliate_id']) ? $this->request->post['affiliate_id'] : 0;
        $data['card_no'] = $card_no;
        $log->write("user id");

        $data['user_id'] = $this->user->getId();

        $log->write($customer_id);
        $is_guest = 'false';
        if (isset($customer_id)) {
            $log->write("user id in ", $this->request->post['customer_mob']);
            $customer_id = $this->model_pos_pos->getCustomerByPhone($this->request->post['customer_mob'])["customer_id"]; //$this->session->data['cid'];
            $log->write("user= " . $customer_id);
        }
//override for customer 
        if ($is_guest == 'false') {

            $log->write("false");
            $customer = $this->model_pos_pos->getCustomer($customer_id);
            // $this->session->data['customer_id']=$customer_id;
            $data['customer_id'] = $customer_id;
            $data['customer_group_id'] = $customer['customer_group_id'];


            if (!empty($this->request->post['fname'])) {
                $data['firstname'] = $mcrypt->decrypt($this->request->post['fname']) . '-' . $mcrypt->decrypt($this->request->post['lname']);
                $data['payment_firstname'] = $mcrypt->decrypt($this->request->post['fname']) . '-' . $mcrypt->decrypt($this->request->post['lname']);
            } else if (!empty($this->request->post['growername'])) {
                $data['firstname'] = $mcrypt->decrypt($this->request->post['growername']);
                $data['payment_firstname'] = $mcrypt->decrypt($this->request->post['growername']);
            }

            $data['lastname'] = $customer['lastname'];
            $data['email'] = $customer['email'];
            $data['telephone'] = $customer['telephone'];
            $data['fax'] = $customer['fax'];


            $data['payment_lastname'] = $customer['lastname'];
        }
        $log->write("all data added in the array just  after getting the customer info is : ");
        $log->write($data);
        //get product list 
        $this->load->library('customer');
        $this->customer = new Customer($this->registry);

        $this->load->library('tax'); //
        $this->tax = new Tax($this->registry);

        $this->load->library('pos_cart'); //
        $this->cart = new Pos_cart($this->registry);

//SMS LIB
        $this->load->library('sms');
        $data['order_product'] = array();

        foreach ($this->cart->getProducts() as $product) {
            foreach ($prds as $prd) {
                if ($product['product_id'] == $prd['product_id']) {
                    if (empty($prd['ActAmount'])) {
                        $prd['ActAmount'] = $prd['actamount'];
                    }
                    if (empty($prd['ActRate'])) {
                        $prd['ActRate'] = $prd['actrate'];
                    }
                    if (empty($prd['SubSidyPer'])) {
                        $prd['SubSidyPer'] = $prd['subsidyper'];
                    }
                    if (empty($prd['SubsidyAmount'])) {
                        $prd['SubsidyAmount'] = $prd['subsidyamount'];
                    }
                    if (empty($prd['SubRate'])) {
                        $prd['SubRate'] = $prd['subrate'];
                    }
                    $product['ActAmount'] = $prd['ActAmount'];
                    $product['ActRate'] = $prd['ActRate'];
                    $product['SubSidyPer'] = $prd['SubSidyPer'];
                    $product['SubsidyAmount'] = $prd['SubsidyAmount'];
                    $product['BCMLCODE'] = empty($prd['bcml_code']) ? $prd['BCMLCODE'] : $prd['bcml_code'];
                    $product['S_CODE'] = $prd['S_CODE'];
                    $product['S_DESC'] = $prd['S_DESC'];
                    $product['SubRate'] = $prd['SubRate'];
                }
                //$log->write($prd['product_id']);
            }
            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['option_value'];
                } else {
                    $filename = $this->encryption->decrypt($option['option_value']);

                    $value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
                }

                $option_data[] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
                    'type' => $option['type'],
                    'name' => $option['name'],
                );
            }

            $log->write("user tax");
            $log->write($product['price']);
            $log->write($product['tax_class_id']);
            $log->write($this->tax->getTax($product['price'], $product['tax_class_id']));

            $data['order_product'][] = array(
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'model' => $product['model'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'total' => $product['price'] * $product['quantity'],
                'tax' => number_format((float) ($this->tax->getTax($product['price'], $product['tax_class_id'])), 2, '.', ''),
                'reward' => $product['reward'],
                'order_option' => $option_data,
                'ActAmount' => empty($product['ActAmount']) ? 0 : $product['ActAmount'],
                'ActRate' => empty($product['ActRate']) ? 0 : $product['ActRate'],
                'SubSidyPer' => empty($product['SubSidyPer']) ? 0 : $product['SubSidyPer'],
                'SubsidyAmount' => empty($product['SubsidyAmount']) ? 0 : $product['SubsidyAmount'],
                'BCMLCODE' => empty($product['BCMLCODE']) ? 0 : $product['BCMLCODE'],
                'SubRate' => empty($product['SubRate']) ? 0 : $product['SubRate'],
                'S_CODE' => empty($product['S_CODE']) ? '' : $product['S_CODE'],
                'S_DESC' => empty($product['S_DESC']) ? '' : $product['S_DESC']
            );
        }
//foreach products 

        $log->write('just before prdsubs');
        $log->write($prdsubs);
        if (!empty($prdsubs)) {
            foreach ($prdsubs as $prdsub) {
                $data['order_product_subsidy'][] = array(
                    'product_id' => $prdsub['PRODUCT_ID'],
                    'discount_value' => $prdsub['SUBSIDY_RATE'],
                    'discount_type' => 'P',
                    'reward' => $prdsub['DISCOUNT']
                );
            }
        }
        $log->write($data['order_product_subsidy']);
        $log->write('just afer prdsubs');
        $this->adminmodel('pos/extension');
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
        $log->write("near1");
        $log->write($total_data);
        $data['amtcash'] = $this->request->post['amtcash'];
        $data['subsidy'] = $this->request->post['subcash'];
        $data['sub'] = $this->request->post['sub'];
        $data['order_total'] = $total_data;
//for tagged		
        if (isset($this->request->post['docs'])) {
            try {
                $data['shipping_address_2'] = ($this->request->post['docs']);
                $data['shipping_city'] = ($this->request->post['doc_number']);
            } catch (Exception $e) {
                
            }
        }

        /*
          if($payment_method=='Tagged Cash')
          {
          $data['shipping_address_2'] = $mcrypt->decrypt($this->request->post['docs']) ;
          $data['shipping_city'] = $mcrypt->decrypt($this->request->post['doc_number']) ;

          }
          if($payment_method=='Subsidy')
          {
          $data['shipping_address_2'] = $mcrypt->decrypt($this->request->post['docs']) ;
          $data['shipping_city'] = $mcrypt->decrypt($this->request->post['doc_number']);
          } */
        //for cheque
//        if ($payment_method == 'Cheque') {
//            $data['chenum'] = $mcrypt->decrypt($this->request->post['chenum']);
//            $data['chemic'] = $mcrypt->decrypt($this->request->post['chemic']);
//            $data['chebnk'] = $mcrypt->decrypt($this->request->post['chebnk']);
//            $data['cheacc'] = $mcrypt->decrypt($this->request->post['cheacc']);
//            $data['cheaccno'] = $mcrypt->decrypt($this->request->post['cheaccno']);
//        }

        if (isset($this->session->data['voucher'])) {
            $data['order_voucher'] = $this->session->data['voucher'];
        }

//end of order total 
        $json['customer_name'] = $data['firstname'] . ' ' . $data['lastname'];
        $log->write($json);
        $log->write("near2");
        $data['utype'] = $mcrypt->decrypt($this->request->post['utype']);

        $log->write("all data added in the array just  before the call of addorder is : ");
        $log->write($data);
        $order_id = $this->model_pos_pos->addOrder($data);
        $log->write("Order Successfully added in oc_order - " . $order_id);
        if (isset($this->request->post['transid'])) {
            $this->model_pos_pos->update_order_istance_order_id($mcrypt->decrypt($this->request->post['transid']), $order_id);
        }
//bcml
//        try {
//            $this->model_pos_pos->update_indent_order_deleviery($this->request->post['comment'], $mcrypt->decrypt($this->request->post['cde']), $mcrypt->decrypt($this->request->post['deliverymode']), $order_id, $mcrypt->decrypt($this->request->post['approvaltype']), $mcrypt->decrypt($this->request->post['deliveryreceipt']), $mcrypt->decrypt($this->request->post['fmcode']), $mcrypt->decrypt($this->request->post['fmname']));
//        } catch (Exception $e) {
//            $log->write($e);
//        }
        //end
        try {
            if (isset($this->request->post['lumpsum'])) {
                $this->model_pos_pos->update_advance_order_deleviery($this->request->post['comment'], $mcrypt->decrypt($this->request->post['cde']), $mcrypt->decrypt($this->request->post['deliverymode']), $order_id, $mcrypt->decrypt($this->request->post['approvaltype']), $mcrypt->decrypt($this->request->post['deliveryreceipt']), $mcrypt->decrypt($this->request->post['fmcode']), $mcrypt->decrypt($this->request->post['fmname']));
            }
        } catch (Exception $e) {
            $log->write($e);
        }
        //send cash data to bcml start here
//        try {
//            if ($company == 'bcml' && $payment_method == 'Cash') {
//                $data['vname'] = $mcrypt->decrypt($this->request->post['vname']);
//                $data['vcode'] = $mcrypt->decrypt($this->request->post['vcode']);
//                $data['gmobile'] = $data['telephone'];
//                $data['gname'] = $data['firstname'];
//                $data['gcode'] = $data['shipping_firstname'];
//                $data['invoicevalue'] = $data['order_total'];
//
//                $data['FmCode'] = $mcrypt->decrypt($this->request->post['fm_code']);
//                $data['DeliveryMode'] = $payment_method;
//                $data['prddtl'] = json_decode($this->request->post['prddtl'], TRUE);
//                $obj = new ArrayObject($data['prddtl']);
//                $it = $obj->getIterator();
//                foreach ($it as $key => $val) {
//                    unset($val['product_combo_prd']);
//                    unset($val['product_tax_per']);
//                    $val['product_price'] = trim(str_replace("Rs.", "", $val['product_price']));
//                    $val['product_price'] = number_format((float) $val['product_price'], 2, '.', '');
//                    $val['product_tax'] = number_format((float) $val['product_tax'], 2, '.', '');
//                    $data_final[] = ($val);
//                    if (empty($val['product_price'])) {
//                        return 0;
//                    }
//                }
//                $data['prddtl'] = json_encode($data_final);
//
//                $data['ordervalue'] = 0;
//                $data['invoicevalue'] = number_format((float) $total, 2, '.', '');
//                $data['unitid'] = empty($data['unit_id']) ? $data['unitid'] : $data['unit_id'];
//                $data['storeid'] = $data['store_id'];
//                $data['glimit'] = 0;
//                $data['userid'] = $data['user_id'];
//                $data['billno'] = $order_id;
//                $data['otp'] = 0;
//                $this->adminmodel('pos/' . $company);
//                $cashreturn = $this->{'model_pos_' . $company}->setOrderDataToServer($data, 'Cash');
//                $cashreturn = str_replace("'", '"', $cashreturn);
//                $this->model_pos_pos->insert_cash_order_trans($order_id, $data['store_id'], $data, $cashreturn, $data['vcode']);
//            }
//            $log->write($cashreturn);
//        } catch (Exception $e) {
//            $log->write($e);
//        }
        //send cash data to bcml end here 
//        $data['order_id'] = $order_id;
//        $data['vill'] = $mcrypt->decrypt($this->request->post['villageid']);
//        $data['villname'] = $mcrypt->decrypt($this->request->post['vname']);
//        $data['oid'] = $order_id;
//        $data['tagged_amt'] = $mcrypt->decrypt($this->request->post['tagged_amt']);
//        // add order to cane system
//        $log->write($data['store_name']);
//
//        $data['store_name'] = $this->config->get('config_name');
//        $log->write($data['store_name']);
//        $log->write('unit data before call to updatedelivery');
//        $log->write($unitdata);
        if (!empty($unitdata['company_name'])) {
            $company = strtolower($unitdata['company_name']);
            $this->adminmodel('pos/' . $company);
            $datares = $this->{'model_pos_' . $company}->UpdateDelivery('UpdateDelivery', $data, 0);
            $log->write("in company" . $datares);

            if (empty($datares)) {
                //return error
                $json['error'] = "Error at cane system";
                $json['success'] = "-1";
                $this->response->setOutput(json_encode($json));
                return;
            }
            if (!is_numeric($datares)) {
                //return error
                $json['error'] = "Error at cane system";
                $json['success'] = "-1";
                $this->response->setOutput(json_encode($json));
                return;
            }
            $log->write("in company-" . $datares);
            if (!empty($datares)) {
                //data check
                $data['order_trans_id'] = $datares;
                $json['qid'] = $datares;
            }
        }

        $log->write($datares);

        if ((!isset($this->request->post['prdsub'])) && (!empty($this->request->post['prdsub']))) {
            $this->model_pos_pos->confirm_coupon($data, $this->request->post['coupon']);
        }
        $log->write('payment_method is -' . $this->request->post['payment_method']);
//        if (($this->request->post['payment_method'] != 'Cash') && ($this->request->post['payment_method'] != 'Subsidy')) {
//            try {
//
//                $this->model_pos_pos->UpdateOrderStatusLeads($this->request->post['comment'], '5');
//                //$this->model_pos_pos->RequisitionToBill($this->request->post['comment'],$order_id);
//            } catch (Exception $e) {
//                $log->write($e);
//            }
//        }
//        if (($this->request->post['payment_method'] == 'Cash') && ($this->request->post['comment'] != '')) {
//            try {
//                $this->model_pos_pos->UpdateOrderStatusLeads($this->request->post['comment'], '5');
//                //$this->model_pos_pos->RequisitionToBill($this->request->post['comment'],$order_id);
//            } catch (Exception $e) {
//                $log->write($e);
//            }
//        }
//        if (($this->request->post['payment_method'] == 'Subsidy') && ($this->request->post['comment'] != '')) {
//            try {
//                $this->model_pos_pos->UpdateOrderStatusLeads($this->request->post['comment'], '5');
//                //$this->model_pos_pos->RequisitionToBill($this->request->post['comment'],$order_id);
//            } catch (Exception $e) {
//                $log->write($e);
//            }
//        }
        if ($mcrypt->decrypt($this->request->post['card_no']) == "2") {

            $this->model_setting_store->updatecurrentcash($this->request->post['stock_fm'], $total, $data['store_id']);
        }

        $log->write("near3");
        unset($this->session->data['discount_amount']);

//recore for counter payment 
//        if ($this->request->post['payment_method'] == 'Tagged Cash') {
//            $cash = (float) $data['amtcash'];
//            $card = 0; //$total;
//        } else if ($this->request->post['payment_method'] == 'Subsidy') {
//            $cash = $this->request->post['subcash'];
//            $card = 0; //$total;
//        } else if ($this->request->post['payment_method'] == 'Tagged Cash Subsidy') {
//            $cash = $this->request->post['amtcash'];
//            $card = 0; //$total;
//        } else {
//            $cash = $total;
//            $card = 0;
//        }

        $data = array(
            'user_id' => $this->request->post['user_id'],
            'cash' => $cash,
            'card' => $card,
            'store_id' => $this->request->post['store_id'],
            'order_id' => $order_id,
            'payment_method' => $this->request->post['payment_method'],
            'total' => $total
        );

//        if ($this->request->post['payment_method'] == 'Tagged Cash') {
//            $log->write('Payment Method is: ' . $this->request->post['payment_method'] . ' so we will call the addPayment after success of order in updatestatus ');
//            $this->model_pos_pos->addPayment($data);
//        }
//        if ($this->request->post['payment_method'] == 'Cash') {
//            $log->write('Payment Method is: ' . $this->request->post['payment_method']);
//            $this->model_pos_pos->addPayment($data);
//        }
//        if ($this->request->post['payment_method'] == 'Subsidy') {
//            $log->write('Payment Method is: ' . $this->request->post['payment_method']);
//            $this->model_pos_pos->addPayment($data);
//        }
        /*
          if(($payment_method  == 'Tagged Cash') ||  ($payment_method  == 'Cash'))
          {
          $log->write('Payment Method is: '.$this->request->post['payment_method']);
          $log->write($data);
          $this->model_pos_pos->addPayment($data);
          }
          //recore for counter payment
          if($payment_method  == 'Card'){
          $cash = 0;
          $card = $total;
          }else{
          $cash = $total;
          $card = 0;
          }

          $data = array(
          'user_id' => $this->user->getId(),
          'cash' => $cash,
          'card' => $card,
          );

          $this->model_pos_pos->addPayment($data);
         */
        $json['order_id'] = $order_id;
        $log->write("Genereted Invoice number - " . $order_id);
        $balance = $this->model_pos_pos->get_user_balance($this->user->getId());
        $json['cash'] = $this->currency->format($balance['cash']);
        $json['card'] = $this->currency->format($balance['card']);

        $log->write("done----" . $customer_id);
// Set the order history

        $log->write("dones----");
        try {


            if (isset($this->request->post['order_status_id'])) {
                $order_status_id = $this->request->post['order_status_id'];
            } else {
                $order_status_id = $this->config->get('config_order_status_id');
            }

            //$this->model_checkout_order->addOrderHistory($json['order_id'], $order_status_id);
        } catch (Exception $e) {
            $log->write('add order history  in catch');
            $log->write($e);
        }



        $log->write("done1");

        $json['success'] = 'Success: new order placed with ID: ' . $order_id;

        try {
            $log->write("Inv genrate");
            $invoice_no = $this->model_pos_pos->createInvoiceNo($order_id);
            $json['invoice_no'] = $invoice_no;
            $log->write($invoice_no);
            $log->write("Inv genrate update done");
        } catch (Exception $e) {
            $log->write('generate invoice gone in catch');
            $log->write($e);
        }

        $json['orddate'] = date('Y-m-d h:i:s A');

        $log->write("before call to get_order_total");
        $json['coupon_discount'] = $this->model_pos_pos->get_order_total($order_id, 'coupon');
        $log->write("after call to get_order_total");

        $gtax = $this->model_checkout_order->getgtax($order_id);
        $log->write("gtax");
        $log->write($gtax);
        $json['gtax'] = $mcrypt->encrypt(json_encode($gtax));
        $sms = new sms($this->registry);
        try {
            $companydata = $this->model_pos_pos->getunitidandcompanyid($data);
            if (!empty($companydata)) {
                $data['unitid'] = $companydata[0]['unit_id'];
                $log->write($companydata);
                $company = strtolower($companydata[0]['company_name']);
                $log->write($company);
            }
            if ($company != 'isec') {
                $sms->sendsms($this->request->post['customer_mob'], "2", $data);
            }
        } catch (Exception $e) {
            $log->write($e);
        }


        if (strtolower($this->request->post['coupon']) == "diwali") {
            try {
                //$log2=new Log("recharge-".date('Y-m-d').".log");
                $log->write('Diwali Coupon so now we will call the thread for' . $order_id);

                //($mobile,$muid,$scheme,$order_id,$store_id)
                //$asyncOperation=new AsyncOperationSeasionalRecharge($this->request->post['customer_mob'],$this->request->post['muid'],'7',$order_id,$this->request->post['store_id']);
                $log->write('now we will call the thread 3');
                //$asyncOperation->start();
                $log->write('now we will call the thread 4');
            } catch (Exception $e) {
                $log2->write($e);
            }
        } else {

//send to recharge
            $log->write('Recharge thread open');
            try {
                if ($this->request->post['payment_method'] == 'Cash') {
                    $log->write('Payment method is :' . $this->request->post['payment_method'] . " so start the thread");
                    //$asyncOperation=new AsyncOperation($this->request->post['customer_mob'],$order_id,$this->request->post['store_id'],$prds);
                    //$asyncOperation->start();
                    $log->write('Recharge thread start');
                } else {
                    $log->write('Payment method is :' . $this->request->post['payment_method'] . " so no need to call thread");
                }
            } catch (Exception $e) {
                $log->write($e);
            }
//end recharge        
        }
        $log->write('final return by addorder in order.php');
        $log->write($json);
        return $this->response->setOutput(json_encode($json));
    }

    //\\################ End## Placed tagged order ################\\//
}
