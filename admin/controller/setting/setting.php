<?php
date_default_timezone_set('Asia/Kolkata');
class ControllerSettingSetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
                $this->load->model('setting/store');
                $data["store_id"]=$this->request->get['store_id'];
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) 
                    {
                        
                        
                        if($this->request->get['store_id']=="")
                        {
                            $store_id = $this->model_setting_store->addStore($this->request->post);
                         
                        }
                        else 
                        {
                             $this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);  
                        
                             $store_id = $this->request->get['store_id'];  
                            
                        }
                         $fol =mkdir(DIR_UPLOAD."store_doc/".$store_id, 0777, true);
                         if (!empty($this->request->files['config_GST_doc']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_GST_doc']['name']);
                                $file_ext=end($file_n);
                                $file_config_GST_doc=date('Y_m_d_h_i_s')."_GST_doc.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_GST_doc;
                                move_uploaded_file($this->request->files['config_GST_doc']['tmp_name'], $path);    
                                $this->request->post["config_GST_doc"]=$file_config_GST_doc;    
                         }
                         
                         ////////////////////////
			$this->model_setting_setting->editSetting('config', $this->request->post,$store_id);

			if ($this->config->get('config_currency_auto')) {
				$this->load->model('localisation/currency');

				$this->model_localisation_currency->refresh();
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_stock'] = $this->language->get('text_stock');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_mail'] = $this->language->get('text_mail');
		$data['text_smtp'] = $this->language->get('text_smtp');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_owner'] = $this->language->get('entry_owner');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_geocode'] = $this->language->get('entry_geocode');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_open'] = $this->language->get('entry_open');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_admin_language'] = $this->language->get('entry_admin_language');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_currency_auto'] = $this->language->get('entry_currency_auto');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_product_limit'] = $this->language->get('entry_product_limit');
		$data['entry_product_description_length'] = $this->language->get('entry_product_description_length');
		$data['entry_limit_admin'] = $this->language->get('entry_limit_admin');
		$data['entry_product_count'] = $this->language->get('entry_product_count');
		$data['entry_review'] = $this->language->get('entry_review');
		$data['entry_review_guest'] = $this->language->get('entry_review_guest');
		$data['entry_review_mail'] = $this->language->get('entry_review_mail');
		$data['entry_voucher_min'] = $this->language->get('entry_voucher_min');
		$data['entry_voucher_max'] = $this->language->get('entry_voucher_max');
		$data['entry_tax'] = $this->language->get('entry_tax');

                	$data['entry_tax_included'] = $this->language->get('entry_tax_included');
		$data['entry_tax_included_store_based'] = $this->language->get('entry_tax_included_store_based');
		$data['entry_tax_included_country'] = $this->language->get('entry_tax_included_country');
		$data['entry_tax_included_zone'] = $this->language->get('entry_tax_included_zone');

		$data['entry_tax_default'] = $this->language->get('entry_tax_default');
		$data['entry_tax_customer'] = $this->language->get('entry_tax_customer');
		$data['entry_customer_online'] = $this->language->get('entry_customer_online');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer_group_display'] = $this->language->get('entry_customer_group_display');
		$data['entry_customer_price'] = $this->language->get('entry_customer_price');
		$data['entry_login_attempts'] = $this->language->get('entry_login_attempts');
		$data['entry_account'] = $this->language->get('entry_account');
		$data['entry_account_mail'] = $this->language->get('entry_account_mail');
		$data['entry_invoice_prefix'] = $this->language->get('entry_invoice_prefix');
		$data['entry_api'] = $this->language->get('entry_api');
		$data['entry_cart_weight'] = $this->language->get('entry_cart_weight');
		$data['entry_checkout_guest'] = $this->language->get('entry_checkout_guest');
		$data['entry_checkout'] = $this->language->get('entry_checkout');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_processing_status'] = $this->language->get('entry_processing_status');
		$data['entry_complete_status'] = $this->language->get('entry_complete_status');
		$data['entry_order_mail'] = $this->language->get('entry_order_mail');
		$data['entry_stock_display'] = $this->language->get('entry_stock_display');
		$data['entry_stock_warning'] = $this->language->get('entry_stock_warning');
		$data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$data['entry_affiliate_approval'] = $this->language->get('entry_affiliate_approval');
		$data['entry_affiliate_auto'] = $this->language->get('entry_affiliate_auto');
		$data['entry_affiliate_commission'] = $this->language->get('entry_affiliate_commission');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_affiliate_mail'] = $this->language->get('entry_affiliate_mail');
		$data['entry_return'] = $this->language->get('entry_return');
		$data['entry_return_status'] = $this->language->get('entry_return_status');
		$data['entry_logo'] = $this->language->get('entry_logo');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_image_category'] = $this->language->get('entry_image_category');
		$data['entry_image_thumb'] = $this->language->get('entry_image_thumb');
		$data['entry_image_popup'] = $this->language->get('entry_image_popup');
		$data['entry_image_product'] = $this->language->get('entry_image_product');
		$data['entry_image_additional'] = $this->language->get('entry_image_additional');
		$data['entry_image_related'] = $this->language->get('entry_image_related');
		$data['entry_image_compare'] = $this->language->get('entry_image_compare');
		$data['entry_image_wishlist'] = $this->language->get('entry_image_wishlist');
		$data['entry_image_cart'] = $this->language->get('entry_image_cart');
		$data['entry_image_location'] = $this->language->get('entry_image_location');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_ftp_hostname'] = $this->language->get('entry_ftp_hostname');
		$data['entry_ftp_port'] = $this->language->get('entry_ftp_port');
		$data['entry_ftp_username'] = $this->language->get('entry_ftp_username');
		$data['entry_ftp_password'] = $this->language->get('entry_ftp_password');
		$data['entry_ftp_root'] = $this->language->get('entry_ftp_root');
		$data['entry_ftp_status'] = $this->language->get('entry_ftp_status');
		$data['entry_mail_protocol'] = $this->language->get('entry_mail_protocol');
		$data['entry_mail_parameter'] = $this->language->get('entry_mail_parameter');
		$data['entry_smtp_hostname'] = $this->language->get('entry_smtp_hostname');
		$data['entry_smtp_username'] = $this->language->get('entry_smtp_username');
		$data['entry_smtp_password'] = $this->language->get('entry_smtp_password');
		$data['entry_smtp_port'] = $this->language->get('entry_smtp_port');
		$data['entry_smtp_timeout'] = $this->language->get('entry_smtp_timeout');
		$data['entry_mail_alert'] = $this->language->get('entry_mail_alert');
		$data['entry_fraud_detection'] = $this->language->get('entry_fraud_detection');
		$data['entry_fraud_key'] = $this->language->get('entry_fraud_key');
		$data['entry_fraud_score'] = $this->language->get('entry_fraud_score');
		$data['entry_fraud_status'] = $this->language->get('entry_fraud_status');
		$data['entry_secure'] = $this->language->get('entry_secure');
		$data['entry_shared'] = $this->language->get('entry_shared');
		$data['entry_robots'] = $this->language->get('entry_robots');
		$data['entry_file_max_size'] = $this->language->get('entry_file_max_size');
		$data['entry_file_ext_allowed'] = $this->language->get('entry_file_ext_allowed');
		$data['entry_file_mime_allowed'] = $this->language->get('entry_file_mime_allowed');
		$data['entry_maintenance'] = $this->language->get('entry_maintenance');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_encryption'] = $this->language->get('entry_encryption');
		$data['entry_seo_url'] = $this->language->get('entry_seo_url');
		$data['entry_compression'] = $this->language->get('entry_compression');
		$data['entry_error_display'] = $this->language->get('entry_error_display');
		$data['entry_error_log'] = $this->language->get('entry_error_log');
		$data['entry_error_filename'] = $this->language->get('entry_error_filename');
		$data['entry_google_analytics'] = $this->language->get('entry_google_analytics');

		$data['help_geocode'] = $this->language->get('help_geocode');
		$data['help_open'] = $this->language->get('help_open');
		$data['help_comment'] = $this->language->get('help_comment');
		$data['help_location'] = $this->language->get('help_location');
		$data['help_currency'] = $this->language->get('help_currency');
		$data['help_currency_auto'] = $this->language->get('help_currency_auto');
		$data['help_product_limit'] = $this->language->get('help_product_limit');
		$data['help_product_description_length'] = $this->language->get('help_product_description_length');
		$data['help_limit_admin'] = $this->language->get('help_limit_admin');
		$data['help_product_count'] = $this->language->get('help_product_count');
		$data['help_review'] = $this->language->get('help_review');
		$data['help_review_guest'] = $this->language->get('help_review_guest');
		$data['help_review_mail'] = $this->language->get('help_review_mail');
		$data['help_voucher_min'] = $this->language->get('help_voucher_min');
		$data['help_voucher_max'] = $this->language->get('help_voucher_max');
		$data['help_tax_default'] = $this->language->get('help_tax_default');
		$data['help_tax_customer'] = $this->language->get('help_tax_customer');
		$data['help_customer_online'] = $this->language->get('help_customer_online');
		$data['help_customer_group'] = $this->language->get('help_customer_group');
		$data['help_customer_group_display'] = $this->language->get('help_customer_group_display');
		$data['help_customer_price'] = $this->language->get('help_customer_price');
		$data['help_login_attempts'] = $this->language->get('help_login_attempts');		
		$data['help_account'] = $this->language->get('help_account');
		$data['help_account_mail'] = $this->language->get('help_account_mail');
		$data['help_api'] = $this->language->get('help_api');
		$data['help_cart_weight'] = $this->language->get('help_cart_weight');
		$data['help_checkout_guest'] = $this->language->get('help_checkout_guest');
		$data['help_checkout'] = $this->language->get('help_checkout');
		$data['help_invoice_prefix'] = $this->language->get('help_invoice_prefix');
		$data['help_order_status'] = $this->language->get('help_order_status');
		$data['help_processing_status'] = $this->language->get('help_processing_status');
		$data['help_complete_status'] = $this->language->get('help_complete_status');
		$data['help_order_mail'] = $this->language->get('help_order_mail');
		$data['help_stock_display'] = $this->language->get('help_stock_display');
		$data['help_stock_warning'] = $this->language->get('help_stock_warning');
		$data['help_stock_checkout'] = $this->language->get('help_stock_checkout');
		$data['help_affiliate_approval'] = $this->language->get('help_affiliate_approval');
		$data['help_affiliate_auto'] = $this->language->get('help_affiliate_auto');
		$data['help_affiliate_commission'] = $this->language->get('help_affiliate_commission');
		$data['help_affiliate'] = $this->language->get('help_affiliate');
		$data['help_affiliate_mail'] = $this->language->get('help_affiliate_mail');
		$data['help_commission'] = $this->language->get('help_commission');
		$data['help_return'] = $this->language->get('help_return');
		$data['help_return_status'] = $this->language->get('help_return_status');
		$data['help_icon'] = $this->language->get('help_icon');
		$data['help_ftp_root'] = $this->language->get('help_ftp_root');
		$data['help_mail_protocol'] = $this->language->get('help_mail_protocol');
		$data['help_mail_parameter'] = $this->language->get('help_mail_parameter');
		$data['help_mail_smtp_hostname'] = $this->language->get('help_mail_smtp_hostname');
		$data['help_mail_alert'] = $this->language->get('help_mail_alert');
		$data['help_fraud_detection'] = $this->language->get('help_fraud_detection');
		$data['help_fraud_score'] = $this->language->get('help_fraud_score');
		$data['help_fraud_status'] = $this->language->get('help_fraud_status');
		$data['help_secure'] = $this->language->get('help_secure');
		$data['help_shared'] = $this->language->get('help_shared');
		$data['help_robots'] = $this->language->get('help_robots');
		$data['help_seo_url'] = $this->language->get('help_seo_url');
		$data['help_file_max_size'] = $this->language->get('help_file_max_size');
		$data['help_file_ext_allowed'] = $this->language->get('help_file_ext_allowed');
		$data['help_file_mime_allowed'] = $this->language->get('help_file_mime_allowed');
		$data['help_maintenance'] = $this->language->get('help_maintenance');
		$data['help_password'] = $this->language->get('help_password');
		$data['help_encryption'] = $this->language->get('help_encryption');
		$data['help_compression'] = $this->language->get('help_compression');
		$data['help_google_analytics'] = $this->language->get('help_google_analytics');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_store'] = $this->language->get('tab_store');
		$data['tab_local'] = $this->language->get('tab_local');
		$data['tab_option'] = $this->language->get('tab_option');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_ftp'] = $this->language->get('tab_ftp');
		$data['tab_mail'] = $this->language->get('tab_mail');
		$data['tab_fraud'] = $this->language->get('tab_fraud');
		$data['tab_server'] = $this->language->get('tab_server');

		$this->load->model('setting/store');

		$data["store_types"]=$this->model_setting_store->getstoretypes();
                	$data["bank_list"]=$this->model_setting_store->getbanks();
		
		//print_r($data["store_types"]);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
                if($this->request->server['REQUEST_METHOD'] == 'POST')
                {
                    $data["error_general"]="";
                    $data["error_store"]="";
                    $data["error_bank"]="";
                    $data["error_gst"]="";
                    $data["error_local"]="";
                }
                else
                {
                    $data["error_general"]="oo";
                    $data["error_store"]="oo";
                    $data["error_bank"]="oo";
                    $data["error_gst"]="oo";
                    $data["error_local"]="oo";
                }
	//print_r($data);

                if(empty($this->request->get["store_id"]))
                {
                $data["cr_sub_heading"]="Create store";
                }
                else { 
                    $data["cr_sub_heading"]="Edit store";
                }
                if (isset($this->error['url'])) {
			$data['error_url'] = $this->error['url'];
                        $data["error_general"]=$this->error['url'];
		} else {
			$data['error_url'] = '';
		}
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
                        $data["error_general"]=$this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['owner'])) {
			$data['error_owner'] = $this->error['owner'];
                        $data["error_general"]=$this->error['owner'];
		} else {
			$data['error_owner'] = '';
		}
		 
               
		if (isset($this->error['tin'])) {
                 		$data['error_tin'] = $this->error['tin'];
                                $data["error_gst"]=$this->error['tin'];
              	 } else {
               		$data['error_tin'] = '';
              	 }
               
		if (isset($this->error['cin'])) {
                		 $data['error_cin'] = $this->error['cin'];
                                 $data["error_gst"]=$this->error['cin'];
               	} else {
               		$data['error_cin'] = '';
               	}
                
		if (isset($this->error['gstn'])) {
                 		$data['error_gstn'] = $this->error['gstn'];
                                $data["error_gst"]=$this->error['gstn'];
               	} else {
               		$data['error_gstn'] = '';
               	}
                
                	if (isset($this->error['registration_date'])) {
                 		$data['error_registration_date'] = $this->error['registration_date'];
                                
               	} else {
               		$data['error_registration_date'] = '';
               	}
		if (isset($this->error['unit'])) {
			$data['error_unit'] = $this->error['unit'];
                       		 $data["error_general"]=$this->error['unit'];
		} else {
			$data['error_unit'] = '';
		}
                /////////new fields error handling start here/////////////
                
               
                if (isset($this->error['MSMFID'])) {
                 		$data['error_MSMFID'] = $this->error['MSMFID'];
                                $data["error_gst"]=$this->error['MSMFID'];
               	} else {
               		$data['error_MSMFID'] = '';
               	}
                
                if (isset($this->error['GST_doc'])) {
                 		$data['error_GST_doc'] = $this->error['GST_doc'];
                                $data["error_gst"]=$this->error['GST_doc'];
               	} else {
               		$data['error_GST_doc'] = '';
               	}
                
                
                if (isset($this->error['Bank_Name'])) {
                 		$data['error_Bank_Name'] = $this->error['Bank_Name'];
                                $data["error_bank"]=$this->error['Bank_Name'];
               	} else {
               		$data['error_Bank_Name'] = '';
               	}
                if (isset($this->error['Account_Number'])) {
                 		$data['error_Account_Number'] = $this->error['Account_Number'];
                                $data["error_bank"]=$this->error['Account_Number'];
               	} else {
               		$data['error_Account_Number'] = '';
               	}
                if (isset($this->error['IFSC_Code'])) {
                 		$data['error_IFSC_Code'] = $this->error['IFSC_Code'];
                                $data["error_bank"]=$this->error['IFSC_Code'];
               	} else {
               		$data['error_IFSC_Code'] = '';
               	}
                if (isset($this->error['Account_Holder_name'])) {
                 		$data['error_Account_Holder_name'] = $this->error['Account_Holder_name'];
                                $data["error_bank"]=$this->error['Account_Holder_name'];
               	} else {
               		$data['error_Account_Holder_name'] = '';
               	}
                if (isset($this->error['Branch_Name'])) {
                 		$data['error_Branch_Name'] = $this->error['Branch_Name'];
                                $data["error_bank"]=$this->error['Branch_Name'];
               	} else {
               		$data['error_Branch_Name'] = '';
               	}
                
                ////////////new fields error handling end here///////////////
                
                
		if (isset($this->error['storetype'])) {
                 		$data['error_storetype'] = $this->error['storetype'];
                                $data["error_general"]=$this->error['storetype'];
               	} else {
               		$data['error_storetype'] = '';
               	}
		if (isset($this->error['firmname'])) {
                 		$data['error_firmname'] = $this->error['firmname'];
                                $data["error_general"]=$this->error['firmname'];
               	} else {
               		$data['error_firmname'] = '';
               	}
		//echo $this->error['company'];
		if (isset($this->error['company'])) {
                 		$data['error_company'] = $this->error['company'];
                                $data["error_general"]=$this->error['company'];
               	} else {
               		$data['error_company'] = '';
               	}
		if (isset($this->error['printer'])) {
                 		$data['error_printer'] = $this->error['printer'];
                                $data["error_general"]=$this->error['printer'];
               	} else {
               		$data['error_printer'] = '';
               	}
		if (isset($this->error['creditlimit'])) { 
                 		$data['error_creditlimit'] = $this->error['creditlimit'];
                                $data["error_general"]=$this->error['creditlimit'];
               	} else {
               		$data['error_creditlimit'] = '';
               	}
		if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
                        $data["error_general"]=$this->error['address'];
		} else {
			$data['error_address'] = '';
		}
		if (isset($this->error['head_office'])) {
			$data['error_head_office'] = $this->error['head_office'];
                        $data["error_general"]=$this->error['head_office'];
		} else {
			$data['error_head_office'] = '';
		}
		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
                        $data["error_general"]=$this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
                        $data["error_general"]=$this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
                        $data["error_store"]=$this->error['meta_title'];
		} else {
			$data['error_meta_title'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}
	
		if (isset($this->error['customer_group_display'])) {
			$data['error_customer_group_display'] = $this->error['customer_group_display'];
		} else {
			$data['error_customer_group_display'] = '';
		}
		
		if (isset($this->error['login_attempts'])) {
			$data['error_login_attempts'] = $this->error['login_attempts'];
		} else {
			$data['error_login_attempts'] = '';
		}	
		
		if (isset($this->error['voucher_min'])) {
			$data['error_voucher_min'] = $this->error['voucher_min'];
		} else {
			$data['error_voucher_min'] = '';
		}

		if (isset($this->error['voucher_max'])) {
			$data['error_voucher_max'] = $this->error['voucher_max'];
		} else {
			$data['error_voucher_max'] = '';
		}

		if (isset($this->error['processing_status'])) {
			$data['error_processing_status'] = $this->error['processing_status'];
		} else {
			$data['error_processing_status'] = '';
		}

		if (isset($this->error['complete_status'])) {
			$data['error_complete_status'] = $this->error['complete_status'];
		} else {
			$data['error_complete_status'] = '';
		}

		if (isset($this->error['ftp_hostname'])) {
			$data['error_ftp_hostname'] = $this->error['ftp_hostname'];
		} else {
			$data['error_ftp_hostname'] = '';
		}

		if (isset($this->error['ftp_port'])) {
			$data['error_ftp_port'] = $this->error['ftp_port'];
		} else {
			$data['error_ftp_port'] = '';
		}

		if (isset($this->error['ftp_username'])) {
			$data['error_ftp_username'] = $this->error['ftp_username'];
		} else {
			$data['error_ftp_username'] = '';
		}

		if (isset($this->error['ftp_password'])) {
			$data['error_ftp_password'] = $this->error['ftp_password'];
		} else {
			$data['error_ftp_password'] = '';
		}

		if (isset($this->error['image_category'])) {
			$data['error_image_category'] = $this->error['image_category'];
		} else {
			$data['error_image_category'] = '';
		}

		if (isset($this->error['image_thumb'])) {
			$data['error_image_thumb'] = $this->error['image_thumb'];
		} else {
			$data['error_image_thumb'] = '';
		}

		if (isset($this->error['image_popup'])) {
			$data['error_image_popup'] = $this->error['image_popup'];
		} else {
			$data['error_image_popup'] = '';
		}

		if (isset($this->error['image_product'])) {
			$data['error_image_product'] = $this->error['image_product'];
		} else {
			$data['error_image_product'] = '';
		}

		if (isset($this->error['image_additional'])) {
			$data['error_image_additional'] = $this->error['image_additional'];
		} else {
			$data['error_image_additional'] = '';
		}

		if (isset($this->error['image_related'])) {
			$data['error_image_related'] = $this->error['image_related'];
		} else {
			$data['error_image_related'] = '';
		}

		if (isset($this->error['image_compare'])) {
			$data['error_image_compare'] = $this->error['image_compare'];
		} else {
			$data['error_image_compare'] = '';
		}

		if (isset($this->error['image_wishlist'])) {
			$data['error_image_wishlist'] = $this->error['image_wishlist'];
		} else {
			$data['error_image_wishlist'] = '';
		}

		if (isset($this->error['image_cart'])) {
			$data['error_image_cart'] = $this->error['image_cart'];
		} else {
			$data['error_image_cart'] = '';
		}

		if (isset($this->error['image_location'])) {
			$data['error_image_location'] = $this->error['image_location'];
		} else {
			$data['error_image_location'] = '';
		}

		if (isset($this->error['error_filename'])) {
			$data['error_error_filename'] = $this->error['error_filename'];
		} else {
			$data['error_error_filename'] = '';
		}

		if (isset($this->error['product_limit'])) {
			$data['error_product_limit'] = $this->error['product_limit'];
		} else {
			$data['error_product_limit'] = '';
		}

		if (isset($this->error['product_description_length'])) {
			$data['error_product_description_length'] = $this->error['product_description_length'];
		} else {
			$data['error_product_description_length'] = '';
		}

		if (isset($this->error['limit_admin'])) {
			$data['error_limit_admin'] = $this->error['limit_admin'];
		} else {
			$data['error_limit_admin'] = '';
		}

		if (isset($this->error['encryption'])) {
			$data['error_encryption'] = $this->error['encryption'];
		} else {
			$data['error_encryption'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_stores'),
			'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

                	if (!isset($this->request->get['store_id'])) {
			$data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL');
		}
                
		//$data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];
               	 // && ($this->request->server['REQUEST_METHOD'] != 'POST')
               	 if (isset($this->request->get['store_id'])) {
			$this->load->model('setting/setting');

			$store_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
		}
                	if (isset($this->request->post['config_storestatus'])) {
			$data['config_storestatus'] = $this->request->post['config_storestatus'];
		}
                	elseif (isset($store_info['config_storestatus'])) {
			$data['config_storestatus'] = $store_info['config_storestatus'];
		}
                	else {
			$data['config_storestatus'] = $this->config->get('config_storestatus');
		}

		$this->load->model('company/company');
		$data['companies_list']=$this->model_company_company->getcompanyName(); 
		$getCompanybystore=$this->model_setting_store->getCompanybystore($this->request->get['store_id']); 

		if (isset($this->request->post['config_company'])) {
			$data['config_company'] = $this->request->post['config_company'];
		}
                	elseif (isset($store_info['config_company'])) {
			$data['config_company'] = $getCompanybystore;
		}
                	else {
			$data['config_company'] = $this->config->get('config_company');
		}
		
		if (isset($this->request->post['config_printer'])) {
			$data['config_printer'] = $this->request->post['config_printer'];
		}
                	elseif (isset($store_info['config_company'])) {
			$data['config_printer'] = $store_info['config_printer'];
		}
                	else {
			$data['config_printer'] = $this->config->get('config_printer');
		}

		if (isset($this->request->post['config_registration_date'])) {
			$data['config_registration_date'] = $this->request->post['config_registration_date'];
		}
                	elseif (isset($store_info['config_registration_date'])) {
			$data['config_registration_date'] = $store_info['config_registration_date'];
		}
                	else {
			$data['config_registration_date'] = $this->config->get('config_registration_date');
		}
		////////////unit/////////////
		$getUnitbystore=$this->model_setting_store->getUnitbystore($this->request->get['store_id']); 
		$this->load->model('unit/unit');
		$data["unit_list"]=$this->model_unit_unit->getunitsbycompany($data['config_company']);  //$this->model_setting_store->getUnits();

		//print_r($this->request->post['config_unit']);
		if (isset($this->request->post['config_unit'])) 
		{
			$data['config_unit'] = $getUnitbystore;//$this->request->post['config_unit'];
		}
                	elseif ($getUnitbystore!="") {
			$data['config_unit'] = $getUnitbystore;
		}
		else
		{
			$data['config_unit'] = '';
		}
                	/////////////////////////

                	if (isset($this->request->post['config_url'])) {
			$data['config_url'] = $this->request->post['config_url'];
		}
                	elseif (isset($store_info['config_url'])) {
			$data['config_url'] = $store_info['config_url'];
		}
                	else {
			$data['config_url'] = $this->config->get('config_url');
		}
                	if (isset($this->request->post['config_ssl'])) {
			$data['config_ssl'] = $this->request->post['config_ssl'];
		}
                	elseif (isset($store_info['config_ssl'])) {
			$data['config_ssl'] = $store_info['config_ssl'];
		}
                	else {
			$data['config_ssl'] = $this->config->get('config_ssl');
		}
		if (isset($this->request->post['config_tin'])) {
			$data['config_tin'] = $this->request->post['config_tin'];
		}
                	elseif (isset($store_info['config_tin'])) {
			$data['config_tin'] = $store_info['config_tin'];
		}
                	else {
			//$data['config_tin'] = $this->config->get('config_tin');
		}

                           if (isset($this->request->post['config_cin'])) {
			$data['config_cin'] = $this->request->post['config_cin'];
		}
                	elseif (isset($store_info['config_cin'])) {
			$data['config_cin'] = $store_info['config_cin'];
		}
                	else {
			//$data['config_cin'] = $this->config->get('config_cin');
		}

		if (isset($this->request->post['config_gstn'])) {
			$data['config_gstn'] = $this->request->post['config_gstn'];
		}
                	elseif (isset($store_info['config_gstn'])) {
			$data['config_gstn'] = $store_info['config_gstn'];
		}
                	else {
			//$data['config_gstn'] = $this->config->get('config_gstn');
		}
                	//////////new fields start here///////
                
                	if (isset($this->request->post['config_MSMFID'])) {
			$data['config_MSMFID'] = $this->request->post['config_MSMFID'];
		}
                	elseif (isset($store_info['config_MSMFID'])) {
			$data['config_MSMFID'] = $store_info['config_MSMFID'];
		}
                	else {
			//$data['config_MSMFID'] = $this->config->get('config_MSMFID');
		}
                	if (isset($this->request->post['config_GST_doc'])) {
			$data['config_GST_doc'] = $this->request->post['config_GST_doc'];
		}
                	elseif (isset($store_info['config_GST_doc'])) {
			$data['config_GST_doc'] = $store_info['config_GST_doc'];
		}
                	else {
			//$data['config_GST_doc'] = $this->config->get('config_GST_doc');
		}
                	if (isset($this->request->post['config_Bank_Name'])) {
			$data['config_Bank_Name'] = $this->request->post['config_Bank_Name'];
		}
                	elseif (isset($store_info['config_Bank_Name'])) {
			$data['config_Bank_Name'] = $store_info['config_Bank_Name'];
		}
                	else {
			//$data['config_Bank_Name'] = $this->config->get('config_Bank_Name');
		}
                	if (isset($this->request->post['config_Account_Number'])) {
			$data['config_Account_Number'] = $this->request->post['config_Account_Number'];
		}
                	elseif (isset($store_info['config_Account_Number'])) {
			$data['config_Account_Number'] = $store_info['config_Account_Number'];
		}
                	else {
			//$data['config_Account_Number'] = $this->config->get('config_Account_Number');
		}
                	if (isset($this->request->post['config_IFSC_Code'])) {
			$data['config_IFSC_Code'] = $this->request->post['config_IFSC_Code'];
		}
                	elseif (isset($store_info['config_IFSC_Code'])) {
			$data['config_IFSC_Code'] = $store_info['config_IFSC_Code'];
		}
                	else {
			//$data['config_IFSC_Code'] = $this->config->get('config_IFSC_Code');
		}
               	 if (isset($this->request->post['config_Account_Holder_name'])) {
			$data['config_Account_Holder_name'] = $this->request->post['config_Account_Holder_name'];
		}
                	elseif (isset($store_info['config_Account_Holder_name'])) {
			$data['config_Account_Holder_name'] = $store_info['config_Account_Holder_name'];
		}
                	else {
			//$data['config_Account_Holder_name'] = $this->config->get('config_Account_Holder_name');
		}
                	if (isset($this->request->post['config_Branch_Name'])) {
			$data['config_Branch_Name'] = $this->request->post['config_Branch_Name'];
		}
                	elseif (isset($store_info['config_Branch_Name'])) {
			$data['config_Branch_Name'] = $store_info['config_Branch_Name'];
		}
                	else {
			//$data['config_Branch_Name'] = $this->config->get('config_Branch_Name');
		}
                
                	//////////////new fields end here/////////////
		if (isset($this->request->post['config_storetype'])) {
			$data['config_storetype'] = $this->request->post['config_storetype'];
		}
                	elseif (isset($store_info['config_storetype'])) {
			$data['config_storetype'] = $store_info['config_storetype'];
		}
                	else {
			//$data['config_storetype'] = $this->config->get('config_storetype');
		}

		if (isset($this->request->post['config_firmname'])) {
			$data['config_firmname'] = $this->request->post['config_firmname'];
		}
                	elseif (isset($store_info['config_firmname'])) {
			$data['config_firmname'] = $store_info['config_firmname'];
		}
                	
	              $store_credit=$this->model_setting_setting->getcredit($this->request->get['store_id']);
		$data['currentcredit']=$store_credit['currentcredit'];
		if (isset($this->request->post['config_creditlimit'])) {
			$data['config_creditlimit'] = $this->request->post['config_creditlimit'];
		}
                	elseif (isset($store_info['config_creditlimit'])) {
			$data['config_creditlimit'] = $store_credit['creditlimit'];//$store_info['config_creditlimit'];
		}
                	else {
			//$data['config_creditlimit'] = $this->config->get('config_creditlimit');
		}


		if (isset($this->request->post['config_name'])) {
			$data['config_name'] = $this->request->post['config_name'];
		}
                
                	elseif (isset($store_info['config_name'])) {
			$data['config_name'] = $store_info['config_name'];
		}
                	else {
			//$data['config_name'] = $this->config->get('config_name');
		}
                	//echo $data['config_name'];
                	//print_r($store_info['config_name']);
		if (isset($this->request->post['config_owner'])) {
			$data['config_owner'] = $this->request->post['config_owner'];
		}
                	elseif (isset($store_info['config_owner'])) {
			$data['config_owner'] = $store_info['config_owner'];
		}
               	 else {
			//$data['config_owner'] = $this->config->get('config_owner');
		}
		
		if (isset($this->request->post['config_address'])) {
			$data['config_address'] = $this->request->post['config_address'];
		}
                	elseif (isset($store_info['config_address'])) {
			$data['config_address'] = $store_info['config_address'];
		}
                	else {
			//$data['config_address'] = $this->config->get('config_address');
		}
		if (isset($this->request->post['config_head_office'])) {
			$data['config_head_office'] = $this->request->post['config_head_office'];
		}
                	elseif (isset($store_info['config_head_office'])) {
			$data['config_head_office'] = $store_info['config_head_office'];
		}
                	else {
			//$data['config_head_office'] = $this->config->get('config_head_office');
		}

		if (isset($this->request->post['config_geocode'])) {
			$data['config_geocode'] = $this->request->post['config_geocode'];
		}
                	elseif (isset($store_info['config_geocode'])) {
			$data['config_geocode'] = $store_info['config_geocode'];
		}
                	else {
			$data['config_geocode'] = $this->config->get('config_geocode');
		}

		if (isset($this->request->post['config_email'])) {
			$data['config_email'] = $this->request->post['config_email'];
		}
               	 elseif (isset($store_info['config_email'])) {
			$data['config_email'] = $store_info['config_email'];
		}
                	else {
			//$data['config_email'] = $this->config->get('config_email');
		}

		if (isset($this->request->post['config_telephone'])) {
			$data['config_telephone'] = $this->request->post['config_telephone'];
		}
                	elseif (isset($store_info['config_telephone'])) {
			$data['config_telephone'] = $store_info['config_telephone'];
		}
                	else {
			
                   	 //$data['config_telephone'] = $this->config->get('config_telephone');
		}

		if (isset($this->request->post['config_fax'])) {
			$data['config_fax'] = $this->request->post['config_fax'];
		}
                	elseif (isset($store_info['config_fax'])) {
			$data['config_fax'] = $store_info['config_fax'];
		}
                	else {
			//$data['config_fax'] = $this->config->get('config_fax');
		}

		if (isset($this->request->post['config_image'])) {
			$data['config_image'] = $this->request->post['config_image'];
		}
                	elseif (isset($store_info['config_image'])) {
			$data['config_image'] = $store_info['config_image'];
		}
                	else {
			$data['config_image'] = $this->config->get('config_image');
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
		}
                	elseif (isset($store_info['config_image']) && is_file(DIR_IMAGE . $store_info['config_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($store_info['config_image'], 100, 100);
		} 
                	elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['config_open'])) {
			$data['config_open'] = $this->request->post['config_open'];
		}
                	elseif (isset($store_info['config_open'])) {
			$data['config_open'] = $store_info['config_open'];
		}
                	else {
			$data['config_open'] = $this->config->get('config_open');
		}

		if (isset($this->request->post['config_comment'])) {
			$data['config_comment'] = $this->request->post['config_comment'];
		}
                	elseif (isset($store_info['config_comment'])) {
			$data['config_comment'] = $store_info['config_comment'];
		}
                	else {
			$data['config_comment'] = $this->config->get('config_comment');
		}

		$this->load->model('localisation/location');

		$data['locations'] = $this->model_localisation_location->getLocations();

		if (isset($this->request->post['config_location'])) {
			$data['config_location'] = $this->request->post['config_location'];
		} elseif ($this->config->get('config_location')) {
			$data['config_location'] = $this->config->get('config_location');
		} else {
			$data['config_location'] = array();
		}

		if (isset($this->request->post['config_meta_title'])) {
			$data['config_meta_title'] = $this->request->post['config_meta_title'];
		}
                	elseif (isset($store_info['config_meta_title'])) {
			$data['config_meta_title'] = $store_info['config_meta_title'];
		}
                	else {
			//$data['config_meta_title'] = $this->config->get('config_meta_title');
		}

		if (isset($this->request->post['config_meta_description'])) {
			$data['config_meta_description'] = $this->request->post['config_meta_description'];
		}
                	elseif (isset($store_info['config_meta_description'])) {
			$data['config_meta_description'] = $store_info['config_meta_description'];
		}
                	else {
			//$data['config_meta_description'] = $this->config->get('config_meta_description');
		}

		if (isset($this->request->post['config_meta_keyword'])) {
			$data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
		}
                	elseif (isset($store_info['config_meta_keyword'])) {
			$data['config_meta_keyword'] = $store_info['config_meta_keyword'];
		}
                	else {
			//$data['config_meta_keyword'] = $this->config->get('config_meta_keyword');
		}

		if (isset($this->request->post['config_layout_id'])) {
			$data['config_layout_id'] = $this->request->post['config_layout_id'];
		}
                	elseif (isset($store_info['config_layout_id'])) {
			$data['config_layout_id'] = $store_info['config_layout_id'];
		}
                	else {
			$data['config_layout_id'] = $this->config->get('config_layout_id');
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->post['config_template'])) {
			$data['config_template'] = $this->request->post['config_template'];
		}
                	elseif (isset($store_info['config_template'])) {
			$data['config_template'] = $store_info['config_template'];
		}
                	else {
			$data['config_template'] = $this->config->get('config_template');
		}

		$data['templates'] = array();

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$data['templates'][] = basename($directory);
		}

		if (isset($this->request->post['config_country_id'])) {
			$data['config_country_id'] = $this->request->post['config_country_id'];
		}
                	elseif (isset($store_info['config_country_id'])) {
			$data['config_country_id'] = $store_info['config_country_id'];
		}
                	else {
			$data['config_country_id'] = $this->config->get('config_country_id');
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['config_zone_id'])) {
			$data['config_zone_id'] = $this->request->post['config_zone_id'];
		}
                	elseif (isset($store_info['config_zone_id'])) {
			$data['config_zone_id'] = $store_info['config_zone_id'];
		}
                	else {
			$data['config_zone_id'] = $this->config->get('config_zone_id');
		}

		if (isset($this->request->post['config_language'])) {
			$data['config_language'] = $this->request->post['config_language'];
		}
                	elseif (isset($store_info['config_language'])) {
			$data['config_language'] = $store_info['config_language'];
		}
                	else {
			$data['config_language'] = $this->config->get('config_language');
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['config_admin_language'])) {
			$data['config_admin_language'] = $this->request->post['config_admin_language'];
		} else {
			$data['config_admin_language'] = $this->config->get('config_admin_language');
		}

		if (isset($this->request->post['config_currency'])) {
			$data['config_currency'] = $this->request->post['config_currency'];
		}
                	elseif (isset($store_info['config_currency'])) {
			$data['config_currency'] = $store_info['config_currency'];
		}
                	else {
			$data['config_currency'] = $this->config->get('config_currency');
		}

		if (isset($this->request->post['config_currency_auto'])) {
			$data['config_currency_auto'] = $this->request->post['config_currency_auto'];
		} else {
			$data['config_currency_auto'] = $this->config->get('config_currency_auto');
		}

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($this->request->post['config_length_class_id'])) {
			$data['config_length_class_id'] = $this->request->post['config_length_class_id'];
		}
                	elseif (isset($store_info['config_length_class_id'])) {
			$data['config_length_class_id'] = $store_info['config_length_class_id'];
		}
                	else {
			$data['config_length_class_id'] = $this->config->get('config_length_class_id');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['config_weight_class_id'])) {
			$data['config_weight_class_id'] = $this->request->post['config_weight_class_id'];
		}
                	elseif (isset($store_info['config_weight_class_id'])) {
			$data['config_weight_class_id'] = $store_info['config_weight_class_id'];
		}
                	else {
			$data['config_weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['config_product_limit'])) {
			$data['config_product_limit'] = $this->request->post['config_product_limit'];
		}
                	elseif (isset($store_info['config_product_limit'])) {
			$data['config_product_limit'] = $store_info['config_product_limit'];
		}
                	else {
			$data['config_product_limit'] = $this->config->get('config_product_limit');
		}

		if (isset($this->request->post['config_product_description_length'])) {
			$data['config_product_description_length'] = $this->request->post['config_product_description_length'];
		}
                	elseif (isset($store_info['config_product_description_length'])) {
			$data['config_product_description_length'] = $store_info['config_product_description_length'];
		}
                	else {
			$data['config_product_description_length'] = $this->config->get('config_product_description_length');
		}

		if (isset($this->request->post['config_limit_admin'])) {
			$data['config_limit_admin'] = $this->request->post['config_limit_admin'];
		}
                	elseif (isset($store_info['config_limit_admin'])) {
			$data['config_limit_admin'] = $store_info['config_limit_admin'];
		}
                	else {
			$data['config_limit_admin'] = $this->config->get('config_limit_admin');
		}

		if (isset($this->request->post['config_product_count'])) {
			$data['config_product_count'] = $this->request->post['config_product_count'];
		}
                	elseif (isset($store_info['config_product_count'])) {
			$data['config_product_count'] = $store_info['config_product_count'];
		}
                	else {
			$data['config_product_count'] = $this->config->get('config_product_count');
		}

		if (isset($this->request->post['config_review_status'])) {
			$data['config_review_status'] = $this->request->post['config_review_status'];
		}
                	elseif (isset($store_info['config_review_status'])) {
			$data['config_review_status'] = $store_info['config_review_status'];
		}
                	else {
			$data['config_review_status'] = $this->config->get('config_review_status');
		}

		if (isset($this->request->post['config_review_guest'])) {
			$data['config_review_guest'] = $this->request->post['config_review_guest'];
		}
                	elseif (isset($store_info['config_review_guest'])) {
			$data['config_review_guest'] = $store_info['config_review_guest'];
		}
                	else {
			$data['config_review_guest'] = $this->config->get('config_review_guest');
		}

		if (isset($this->request->post['config_review_mail'])) {
			$data['config_review_mail'] = $this->request->post['config_review_mail'];
		}
                	elseif (isset($store_info['config_review_mail'])) {
			$data['config_review_mail'] = $store_info['config_review_mail'];
		}
                	else {
			$data['config_review_mail'] = $this->config->get('config_review_mail');
		}

		if (isset($this->request->post['config_voucher_min'])) {
			$data['config_voucher_min'] = $this->request->post['config_voucher_min'];
		} 
                	elseif (isset($store_info['config_voucher_min'])) {
			$data['config_voucher_min'] = $store_info['config_voucher_min'];
		}
                	else {
			$data['config_voucher_min'] = $this->config->get('config_voucher_min');
		}

		if (isset($this->request->post['config_voucher_max'])) {
			$data['config_voucher_max'] = $this->request->post['config_voucher_max'];
		} elseif (isset($store_info['config_voucher_max'])) {
			$data['config_voucher_max'] = $store_info['config_voucher_max'];
		}else {
			$data['config_voucher_max'] = $this->config->get('config_voucher_max');
		}


                	if (isset($this->request->post['config_tax_included'])) {
			$data['config_tax_included'] = $this->request->post['config_tax_included'];
		}
                	elseif (isset($store_info['config_tax_included'])) {
			$data['config_tax_included'] = $store_info['config_tax_included'];
		}
                	else {
			$data['config_tax_included'] = $this->config->get('config_tax_included');
		}
		
		if (isset($this->request->post['config_tax_included_store_based'])) {
			$data['config_tax_included_store_based'] = $this->request->post['config_tax_included_store_based'];
		}
                	elseif (isset($store_info['config_tax_included_store_based'])) {
			$data['config_tax_included_store_based'] = $store_info['config_tax_included_store_based'];
		}
                	else {
			$data['config_tax_included_store_based'] = $this->config->get('config_tax_included_store_based');
		}
		
		if (isset($this->request->post['config_tax_included_country_id'])) {
			$data['config_tax_included_country_id'] = $this->request->post['config_tax_included_country_id'];
		}
                	elseif (isset($store_info['config_tax_included_country_id'])) {
			$data['config_tax_included_country_id'] = $store_info['config_tax_included_country_id'];
		}
                	else {
			$data['config_tax_included_country_id'] = $this->config->get('config_tax_included_country_id');
		}
		
		if (isset($this->request->post['config_tax_included_zone_id'])) {
			$data['config_tax_included_zone_id'] = $this->request->post['config_tax_included_zone_id'];
		}
                	elseif (isset($store_info['config_tax_included_zone_id'])) {
			$data['config_tax_included_zone_id'] = $store_info['config_tax_included_zone_id'];
		}
                	else {
			$data['config_tax_included_zone_id'] = $this->config->get('config_tax_included_zone_id');
		}
		//new
		if (isset($this->request->post['config_tax'])) {
			$data['config_tax'] = $this->request->post['config_tax'];
		}
                	elseif (isset($store_info['config_tax'])) {
			$data['config_tax'] = $store_info['config_tax'];
		}
                	else {
			$data['config_tax'] = $this->config->get('config_tax');
		}

		if (isset($this->request->post['config_tax_default'])) {
			$data['config_tax_default'] = $this->request->post['config_tax_default'];
		}
                	elseif (isset($store_info['config_tax_default'])) {
			$data['config_tax_default'] = $store_info['config_tax_default'];
		}
                	else {
			$data['config_tax_default'] = $this->config->get('config_tax_default');
		}

		if (isset($this->request->post['config_tax_customer'])) {
			$data['config_tax_customer'] = $this->request->post['config_tax_customer'];
		}
                	elseif (isset($store_info['config_tax_default'])) {
			$data['config_tax_customer'] = $store_info['config_tax_customer'];
		}
                	else {
			$data['config_tax_customer'] = $this->config->get('config_tax_customer');
		}

		if (isset($this->request->post['config_customer_online'])) {
			$data['config_customer_online'] = $this->request->post['config_customer_online'];
		}
                	elseif (isset($store_info['config_customer_online'])) {
			$data['config_customer_online'] = $store_info['config_customer_online'];
		}
               	 else {
			$data['config_customer_online'] = $this->config->get('config_customer_online');
		}

		if (isset($this->request->post['config_customer_group_id'])) {
			$data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
		}
                	elseif (isset($store_info['config_customer_group_id'])) {
			$data['config_customer_group_id'] = $store_info['config_customer_group_id'];
		}
                	else {
			$data['config_customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['config_customer_group_display'])) {
			$data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
		}
                	elseif (isset($store_info['config_customer_group_display'])) {
			$data['config_customer_group_display'] = $store_info['config_customer_group_display'];
		}
                	elseif ($this->config->get('config_customer_group_display')) {
			$data['config_customer_group_display'] = $this->config->get('config_customer_group_display');
		} else {
			$data['config_customer_group_display'] = array();
		}

		if (isset($this->request->post['config_customer_price'])) {
			$data['config_customer_price'] = $this->request->post['config_customer_price'];
		}
                	elseif (isset($store_info['config_customer_price'])) {
			$data['config_customer_price'] = $store_info['config_customer_price'];
		}
                	else {
			$data['config_customer_price'] = $this->config->get('config_customer_price');
		}
		
		if (isset($this->request->post['config_login_attempts'])) {
			$data['config_login_attempts'] = $this->request->post['config_login_attempts'];
		}
                
                	elseif ($this->config->has('config_login_attempts')) {
			$data['config_login_attempts'] = $this->config->get('config_login_attempts');
		}
                	elseif (isset($store_info['config_login_attempts'])) {
			$data['config_login_attempts'] = $store_info['config_login_attempts'];
		}
                	else {
			$data['config_login_attempts'] = 5;
		}
		
		if (isset($this->request->post['config_account_id'])) {
			$data['config_account_id'] = $this->request->post['config_account_id'];
		}
                	elseif (isset($store_info['config_account_id'])) {
			$data['config_account_id'] = $store_info['config_account_id'];
		}
                	else {
			$data['config_account_id'] = $this->config->get('config_account_id');
		}

		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($this->request->post['config_account_mail'])) {
			$data['config_account_mail'] = $this->request->post['config_account_mail'];
		}
                	elseif (isset($store_info['config_account_mail'])) {
			$data['config_account_mail'] = $store_info['config_account_mail'];
		}
                	else {
			$data['config_account_mail'] = $this->config->get('config_account_mail');
		}

		if (isset($this->request->post['config_api_id'])) {
			$data['config_api_id'] = $this->request->post['config_api_id'];
		} 
                	elseif (isset($store_info['config_api_id'])) {
			$data['config_api_id'] = $store_info['config_api_id'];
		}
                	else {
			$data['config_api_id'] = $this->config->get('config_api_id');
		}

		$this->load->model('user/api');

		$data['apis'] = $this->model_user_api->getApis();

		if (isset($this->request->post['config_cart_weight'])) {
			$data['config_cart_weight'] = $this->request->post['config_cart_weight'];
		}
                	elseif (isset($store_info['config_cart_weight'])) {
			$data['config_cart_weight'] = $store_info['config_cart_weight'];
		}
                	else {
			$data['config_cart_weight'] = $this->config->get('config_cart_weight');
		}

		if (isset($this->request->post['config_checkout_guest'])) {
			$data['config_checkout_guest'] = $this->request->post['config_checkout_guest'];
		}
                	elseif (isset($store_info['config_checkout_guest'])) {
			$data['config_checkout_guest'] = $store_info['config_checkout_guest'];
		}
                	else {
			$data['config_checkout_guest'] = $this->config->get('config_checkout_guest');
		}

		if (isset($this->request->post['config_checkout_id'])) {
			$data['config_checkout_id'] = $this->request->post['config_checkout_id'];
		}
                	elseif (isset($store_info['config_checkout_id'])) {
			$data['config_checkout_id'] = $store_info['config_checkout_id'];
		}
                	else {
			$data['config_checkout_id'] = $this->config->get('config_checkout_id');
		}

		if (isset($this->request->post['config_invoice_prefix'])) {
			$data['config_invoice_prefix'] = $this->request->post['config_invoice_prefix'];
		}
                	elseif (isset($store_info['config_invoice_prefix'])) {
			$data['config_invoice_prefix'] = $store_info['config_invoice_prefix'];
		}
                	elseif ($this->config->get('config_invoice_prefix')) {
			$data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');
		} else {
			$data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
		}

		if (isset($this->request->post['config_order_status_id'])) {
			$data['config_order_status_id'] = $this->request->post['config_order_status_id'];
		}
                elseif (isset($store_info['config_order_status_id'])) {
			$data['config_order_status_id'] = $store_info['config_order_status_id'];
		}
                else {
			$data['config_order_status_id'] = $this->config->get('config_order_status_id');
		}

		if (isset($this->request->post['config_processing_status'])) {
			$data['config_processing_status'] = $this->request->post['config_processing_status'];
		}
                elseif (isset($store_info['config_processing_status'])) {
			$data['config_processing_status'] = $store_info['config_processing_status'];
		}
                elseif ($this->config->get('config_processing_status')) {
			$data['config_processing_status'] = $this->config->get('config_processing_status');
		} else {
			$data['config_processing_status'] = array();
		}

		if (isset($this->request->post['config_complete_status'])) {
			$data['config_complete_status'] = $this->request->post['config_complete_status'];
		}
                elseif (isset($store_info['config_complete_status'])) {
			$data['config_complete_status'] = $store_info['config_complete_status'];
		}
                elseif ($this->config->get('config_complete_status')) {
			$data['config_complete_status'] = $this->config->get('config_complete_status');
		} else {
			$data['config_complete_status'] = array();
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['config_order_mail'])) {
			$data['config_order_mail'] = $this->request->post['config_order_mail'];
		}
                elseif (isset($store_info['config_order_mail'])) {
			$data['config_order_mail'] = $store_info['config_order_mail'];
		}
                else {
			$data['config_order_mail'] = $this->config->get('config_order_mail');
		}

		if (isset($this->request->post['config_stock_display'])) {
			$data['config_stock_display'] = $this->request->post['config_stock_display'];
		}
                elseif (isset($store_info['config_stock_display'])) {
			$data['config_stock_display'] = $store_info['config_stock_display'];
		}
                else {
			$data['config_stock_display'] = $this->config->get('config_stock_display');
		}

		if (isset($this->request->post['config_stock_warning'])) {
			$data['config_stock_warning'] = $this->request->post['config_stock_warning'];
		}
                elseif (isset($store_info['config_stock_warning'])) {
			$data['config_stock_warning'] = $store_info['config_stock_warning'];
		}
                else {
			$data['config_stock_warning'] = $this->config->get('config_stock_warning');
		}

		if (isset($this->request->post['config_stock_checkout'])) {
			$data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
		}
                elseif (isset($store_info['config_stock_checkout'])) {
			$data['config_stock_checkout'] = $store_info['config_stock_checkout'];
		}
                else {
			$data['config_stock_checkout'] = $this->config->get('config_stock_checkout');
		}

		if (isset($this->request->post['config_affiliate_auto'])) {
			$data['config_affiliate_approval'] = $this->request->post['config_affiliate_approval'];
		}
                elseif (isset($store_info['config_affiliate_commission'])) {
			$data['config_affiliate_commission'] = $store_info['config_affiliate_commission'];
		}
                elseif ($this->config->has('config_affiliate_commission')) {
			$data['config_affiliate_approval'] = $this->config->get('config_affiliate_approval');
		} else {
			$data['config_affiliate_approval'] = '';
		}

		if (isset($this->request->post['config_affiliate_auto'])) {
			$data['config_affiliate_auto'] = $this->request->post['config_affiliate_auto'];
		}
                elseif (isset($store_info['config_affiliate_auto'])) {
			$data['config_affiliate_auto'] = $store_info['config_affiliate_auto'];
		}
                elseif ($this->config->has('config_affiliate_auto')) {
			$data['config_affiliate_auto'] = $this->config->get('config_affiliate_auto');
		} else {
			$data['config_affiliate_auto'] = '';
		}

		if (isset($this->request->post['config_affiliate_commission'])) {
			$data['config_affiliate_commission'] = $this->request->post['config_affiliate_commission'];
		}
                elseif (isset($store_info['config_affiliate_commission'])) {
			$data['config_affiliate_commission'] = $store_info['config_affiliate_commission'];
		}
                elseif ($this->config->has('config_affiliate_commission')) {
			$data['config_affiliate_commission'] = $this->config->get('config_affiliate_commission');
		} else {
			$data['config_affiliate_commission'] = '5.00';
		}

		if (isset($this->request->post['config_affiliate_mail'])) {
			$data['config_affiliate_mail'] = $this->request->post['config_affiliate_mail'];
		}
                elseif (isset($store_info['config_affiliate_mail'])) {
			$data['config_affiliate_mail'] = $store_info['config_affiliate_mail'];
		}
                elseif ($this->config->has('config_affiliate_mail')) {
			$data['config_affiliate_mail'] = $this->config->get('config_affiliate_mail');
		} else {
			$data['config_affiliate_mail'] = '';
		}

		if (isset($this->request->post['config_affiliate_id'])) {
			$data['config_affiliate_id'] = $this->request->post['config_affiliate_id'];
		}
                elseif (isset($store_info['config_affiliate_id'])) {
			$data['config_affiliate_id'] = $store_info['config_affiliate_id'];
		}
                else {
			$data['config_affiliate_id'] = $this->config->get('config_affiliate_id');
		}

		if (isset($this->request->post['config_return_id'])) {
			$data['config_return_id'] = $this->request->post['config_return_id'];
		}
                elseif (isset($store_info['config_return_id'])) {
			$data['config_return_id'] = $store_info['config_return_id'];
		}
                else {
			$data['config_return_id'] = $this->config->get('config_return_id');
		}

		if (isset($this->request->post['config_return_status_id'])) {
			$data['config_return_status_id'] = $this->request->post['config_return_status_id'];
		}
                elseif (isset($store_info['config_return_status_id'])) {
			$data['config_return_status_id'] = $store_info['config_return_status_id'];
		}
                else {
			$data['config_return_status_id'] = $this->config->get('config_return_status_id');
		}

		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		if (isset($this->request->post['config_logo'])) {
			$data['config_logo'] = $this->request->post['config_logo'];
		}
                elseif (isset($store_info['config_logo'])) {
			$data['config_logo'] = $store_info['config_logo'];
		}
                else {
			$data['config_logo'] = $this->config->get('config_logo');
		}

		if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
		}
                elseif (isset($store_info['config_logo'])) {
			$data['config_logo'] = $this->model_tool_image->resize($store_info['config_logo'], 100, 100);
		}
                elseif ($this->config->get('config_logo') && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
		} else {
			$data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['config_icon'])) {
			$data['config_icon'] = $this->request->post['config_icon'];
		}
                elseif (isset($store_info['config_icon'])) {
			$data['config_icon'] = $store_info['config_icon'];
		}
                else {
			$data['config_icon'] = $this->config->get('config_icon');
		}

		if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
			$data['icon'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
		}
                elseif (isset($store_info['config_icon'])) {
			$data['icon'] = $this->model_tool_image->resize($this->request->post['config_icon'], 100, 100);
		}
                elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
		} else {
			$data['icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['config_image_category_width'])) {
			$data['config_image_category_width'] = $this->request->post['config_image_category_width'];
		}
                elseif (isset($store_info['config_image_category_width'])) {
			$data['config_image_category_width'] = $store_info['config_image_category_width'];
		}
                else {
			$data['config_image_category_width'] = $this->config->get('config_image_category_width');
		}

		if (isset($this->request->post['config_image_category_height'])) {
			$data['config_image_category_height'] = $this->request->post['config_image_category_height'];
		}
                elseif (isset($store_info['config_image_category_height'])) {
			$data['config_image_category_height'] = $store_info['config_image_category_height'];
		}
                else {
			$data['config_image_category_height'] = $this->config->get('config_image_category_height');
		}

		if (isset($this->request->post['config_image_thumb_width'])) {
			$data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
		}
                elseif (isset($store_info['config_image_thumb_width'])) {
			$data['config_image_thumb_width'] = $store_info['config_image_thumb_width'];
		}
                else {
			$data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
		}

		if (isset($this->request->post['config_image_thumb_height'])) {
			$data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
		}
                elseif (isset($store_info['config_image_thumb_height'])) {
			$data['config_image_thumb_height'] = $store_info['config_image_thumb_height'];
		}
                else {
			$data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
		}

		if (isset($this->request->post['config_image_popup_width'])) {
			$data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
		}
                elseif (isset($store_info['config_image_popup_width'])) {
			$data['config_image_popup_width'] = $store_info['config_image_popup_width'];
		}
                else {
			$data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
		}

		if (isset($this->request->post['config_image_popup_height'])) {
			$data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
		}
                elseif (isset($store_info['config_image_popup_height'])) {
			$data['config_image_popup_height'] = $store_info['config_image_popup_height'];
		}
                else {
			$data['config_image_popup_height'] = $this->config->get('config_image_popup_height');
		}

		if (isset($this->request->post['config_image_product_width'])) {
			$data['config_image_product_width'] = $this->request->post['config_image_product_width'];
		}
                elseif (isset($store_info['config_image_product_width'])) {
			$data['config_image_product_width'] = $store_info['config_image_product_width'];
		}
                else {
			$data['config_image_product_width'] = $this->config->get('config_image_product_width');
		}

		if (isset($this->request->post['config_image_product_height'])) {
			$data['config_image_product_height'] = $this->request->post['config_image_product_height'];
		}
                elseif (isset($store_info['config_image_product_height'])) {
			$data['config_image_product_height'] = $store_info['config_image_product_height'];
		}
                else {
			$data['config_image_product_height'] = $this->config->get('config_image_product_height');
		}

		if (isset($this->request->post['config_image_additional_width'])) {
			$data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
		}
                elseif (isset($store_info['config_image_additional_width'])) {
			$data['config_image_additional_width'] = $store_info['config_image_additional_width'];
		}
                else {
			$data['config_image_additional_width'] = $this->config->get('config_image_additional_width');
		}

		if (isset($this->request->post['config_image_additional_height'])) {
			$data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
		}
                elseif (isset($store_info['config_image_additional_height'])) {
			$data['config_image_additional_height'] = $store_info['config_image_additional_height'];
		}
                else {
			$data['config_image_additional_height'] = $this->config->get('config_image_additional_height');
		}

		if (isset($this->request->post['config_image_related_width'])) {
			$data['config_image_related_width'] = $this->request->post['config_image_related_width'];
		}
                elseif (isset($store_info['config_image_related_width'])) {
			$data['config_image_related_width'] = $store_info['config_image_related_width'];
		}
                else {
			$data['config_image_related_width'] = $this->config->get('config_image_related_width');
		}

		if (isset($this->request->post['config_image_related_height'])) {
			$data['config_image_related_height'] = $this->request->post['config_image_related_height'];
		}
                elseif (isset($store_info['config_image_related_height'])) {
			$data['config_image_related_height'] = $store_info['config_image_related_height'];
		}
                else {
			$data['config_image_related_height'] = $this->config->get('config_image_related_height');
		}

		if (isset($this->request->post['config_image_compare_width'])) {
			$data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
		}
                elseif (isset($store_info['config_image_compare_width'])) {
			$data['config_image_compare_width'] = $store_info['config_image_compare_width'];
		}
                else {
			$data['config_image_compare_width'] = $this->config->get('config_image_compare_width');
		}

		if (isset($this->request->post['config_image_compare_height'])) {
			$data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
		}
                elseif (isset($store_info['config_image_compare_height'])) {
			$data['config_image_compare_height'] = $store_info['config_image_compare_height'];
		}
                else {
			$data['config_image_compare_height'] = $this->config->get('config_image_compare_height');
		}

		if (isset($this->request->post['config_image_wishlist_width'])) {
			$data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
		}
                elseif (isset($store_info['config_image_wishlist_width'])) {
			$data['config_image_wishlist_width'] = $store_info['config_image_wishlist_width'];
		}
                else {
			$data['config_image_wishlist_width'] = $this->config->get('config_image_wishlist_width');
		}

		if (isset($this->request->post['config_image_wishlist_height'])) {
			$data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
		}
                elseif (isset($store_info['config_image_wishlist_height'])) {
			$data['config_image_wishlist_height'] = $store_info['config_image_wishlist_height'];
		}
                else {
			$data['config_image_wishlist_height'] = $this->config->get('config_image_wishlist_height');
		}

		if (isset($this->request->post['config_image_cart_width'])) {
			$data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
		}
                elseif (isset($store_info['config_image_cart_width'])) {
			$data['config_image_cart_width'] = $store_info['config_image_cart_width'];
		}
                else {
			$data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
		}

		if (isset($this->request->post['config_image_cart_height'])) {
			$data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
		}
                elseif (isset($store_info['config_image_cart_height'])) {
			$data['config_image_cart_height'] = $store_info['config_image_cart_height'];
		}
                else {
			$data['config_image_cart_height'] = $this->config->get('config_image_cart_height');
		}

                
		if (isset($this->request->post['config_image_location_width'])) {
			$data['config_image_location_width'] = $this->request->post['config_image_location_width'];
		}
                elseif (isset($store_info['config_image_location_width'])) {
			$data['config_image_location_width'] = $store_info['config_image_location_width'];
		}
                else {
			$data['config_image_location_width'] = $this->config->get('config_image_location_width');
		}

		if (isset($this->request->post['config_image_location_height'])) {
			$data['config_image_location_height'] = $this->request->post['config_image_location_height'];
		}
                elseif (isset($store_info['config_image_location_height'])) {
			$data['config_image_location_height'] = $store_info['config_image_location_height'];
		}
                else {
			$data['config_image_location_height'] = $this->config->get('config_image_location_height');
		}

		if (isset($this->request->post['config_ftp_hostname'])) {
			$data['config_ftp_hostname'] = $this->request->post['config_ftp_hostname'];
		}
                elseif (isset($store_info['config_ftp_hostname'])) {
			$data['config_ftp_hostname'] = $store_info['config_ftp_hostname'];
		}
                elseif ($this->config->get('config_ftp_hostname')) {
			$data['config_ftp_hostname'] = $this->config->get('config_ftp_hostname');
		} else {
			$data['config_ftp_hostname'] = str_replace('www.', '', $this->request->server['HTTP_HOST']);
		}

		if (isset($this->request->post['config_ftp_port'])) {
			$data['config_ftp_port'] = $this->request->post['config_ftp_port'];
		}
                elseif (isset($store_info['config_ftp_port'])) {
			$data['config_ftp_port'] = $store_info['config_ftp_port'];
		}
                elseif ($this->config->get('config_ftp_port')) {
			$data['config_ftp_port'] = $this->config->get('config_ftp_port');
		} else {
			$data['config_ftp_port'] = 21;
		}

		if (isset($this->request->post['config_ftp_username'])) {
			$data['config_ftp_username'] = $this->request->post['config_ftp_username'];
		}
                elseif (isset($store_info['config_ftp_username'])) {
			$data['config_ftp_username'] = $store_info['config_ftp_username'];
		}
                else {
			$data['config_ftp_username'] = $this->config->get('config_ftp_username');
		}

		if (isset($this->request->post['config_ftp_password'])) {
			$data['config_ftp_password'] = $this->request->post['config_ftp_password'];
		}
                elseif (isset($store_info['config_ftp_password'])) {
			$data['config_ftp_password'] = $store_info['config_ftp_password'];
		}
                else {
			$data['config_ftp_password'] = $this->config->get('config_ftp_password');
		}

		if (isset($this->request->post['config_ftp_root'])) {
			$data['config_ftp_root'] = $this->request->post['config_ftp_root'];
		}
                elseif (isset($store_info['config_ftp_root'])) {
			$data['config_ftp_root'] = $store_info['config_ftp_root'];
		}
                else {
			$data['config_ftp_root'] = $this->config->get('config_ftp_root');
		}

		if (isset($this->request->post['config_ftp_status'])) {
			$data['config_ftp_status'] = $this->request->post['config_ftp_status'];
		}
                elseif (isset($store_info['config_ftp_status'])) {
			$data['config_ftp_status'] = $store_info['config_ftp_status'];
		}
                else {
			$data['config_ftp_status'] = $this->config->get('config_ftp_status');
		}

		if (isset($this->request->post['config_mail'])) {
			$config_mail = $this->request->post['config_mail'];

			$data['config_mail_protocol'] = $config_mail['protocol'];
			$data['config_mail_parameter'] = $config_mail['parameter'];
			$data['config_smtp_hostname'] = $config_mail['smtp_hostname'];
			$data['config_smtp_username'] = $config_mail['smtp_username'];
			$data['config_smtp_password'] = $config_mail['smtp_password'];
			$data['config_smtp_port'] = $config_mail['smtp_port'];
			$data['config_smtp_timeout'] = $config_mail['smtp_timeout'];
		}
                
                elseif ($this->config->get('config_mail')) {
			$config_mail = $this->config->get('config_mail');

			$data['config_mail_protocol'] = $config_mail['protocol'];
			$data['config_mail_parameter'] = $config_mail['parameter'];
			$data['config_smtp_hostname'] = $config_mail['smtp_hostname'];
			$data['config_smtp_username'] = $config_mail['smtp_username'];
			$data['config_smtp_password'] = $config_mail['smtp_password'];
			$data['config_smtp_port'] = $config_mail['smtp_port'];
			$data['config_smtp_timeout'] = $config_mail['smtp_timeout'];
		} else {
			$data['config_mail_protocol'] = '';
			$data['config_mail_parameter'] = '';
			$data['config_smtp_hostname'] = '';
			$data['config_smtp_username'] = '';
			$data['config_smtp_password'] = '';
			$data['config_smtp_port'] = 25;
			$data['config_smtp_timeout'] = 5;
		}

		if (isset($this->request->post['config_mail_alert'])) {
			$data['config_mail_alert'] = $this->request->post['config_mail_alert'];
		}
                elseif (isset($store_info['config_mail_alert'])) {
			$data['config_mail_alert'] = $store_info['config_mail_alert'];
		}
                else {
			$data['config_mail_alert'] = $this->config->get('config_mail_alert');
		}

		if (isset($this->request->post['config_fraud_detection'])) {
			$data['config_fraud_detection'] = $this->request->post['config_fraud_detection'];
		}
                elseif (isset($store_info['config_fraud_detection'])) {
			$data['config_fraud_detection'] = $store_info['config_fraud_detection'];
		}
                else {
			$data['config_fraud_detection'] = $this->config->get('config_fraud_detection');
		}

		if (isset($this->request->post['config_fraud_key'])) {
			$data['config_fraud_key'] = $this->request->post['config_fraud_key'];
		}
                elseif (isset($store_info['config_fraud_key'])) {
			$data['config_fraud_key'] = $store_info['config_fraud_key'];
		}
                else {
			$data['config_fraud_key'] = $this->config->get('config_fraud_key');
		}

		if (isset($this->request->post['config_fraud_score'])) {
			$data['config_fraud_score'] = $this->request->post['config_fraud_score'];
		}
                elseif (isset($store_info['config_fraud_score'])) {
			$data['config_fraud_score'] = $store_info['config_fraud_score'];
		}
                else {
			$data['config_fraud_score'] = $this->config->get('config_fraud_score');
		}

		if (isset($this->request->post['config_fraud_status_id'])) {
			$data['config_fraud_status_id'] = $this->request->post['config_fraud_status_id'];
		}
                elseif (isset($store_info['config_fraud_status_id'])) {
			$data['config_fraud_status_id'] = $store_info['config_fraud_status_id'];
		}
                else {
			$data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');
		}

		if (isset($this->request->post['config_secure'])) {
			$data['config_secure'] = $this->request->post['config_secure'];
		}
                elseif (isset($store_info['config_secure'])) {
			$data['config_secure'] = $store_info['config_secure'];
		}
                else {
			$data['config_secure'] = $this->config->get('config_secure');
		}

		if (isset($this->request->post['config_shared'])) {
			$data['config_shared'] = $this->request->post['config_shared'];
		}
                elseif (isset($store_info['config_shared'])) {
			$data['config_shared'] = $store_info['config_shared'];
		}
                else {
			$data['config_shared'] = $this->config->get('config_shared');
		}

		if (isset($this->request->post['config_robots'])) {
			$data['config_robots'] = $this->request->post['config_robots'];
		}
                elseif (isset($store_info['config_robots'])) {
			$data['config_robots'] = $store_info['config_robots'];
		}
                else {
			$data['config_robots'] = $this->config->get('config_robots');
		}

		if (isset($this->request->post['config_seo_url'])) {
			$data['config_seo_url'] = $this->request->post['config_seo_url'];
		}
                elseif (isset($store_info['config_seo_url'])) {
			$data['config_seo_url'] = $store_info['config_seo_url'];
		}
                else {
			$data['config_seo_url'] = $this->config->get('config_seo_url');
		}

		if (isset($this->request->post['config_file_max_size'])) {
			$data['config_file_max_size'] = $this->request->post['config_file_max_size'];
		}
                elseif (isset($store_info['config_file_max_size'])) {
			$data['config_file_max_size'] = $store_info['config_file_max_size'];
		}
                elseif ($this->config->get('config_file_max_size')) {
			$data['config_file_max_size'] = $this->config->get('config_file_max_size');
		} else {
			$data['config_file_max_size'] = 300000;
		}

		if (isset($this->request->post['config_file_ext_allowed'])) {
			$data['config_file_ext_allowed'] = $this->request->post['config_file_ext_allowed'];
		}
                elseif (isset($store_info['config_file_ext_allowed'])) {
			$data['config_file_ext_allowed'] = $store_info['config_file_ext_allowed'];
		}
                else {
			$data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
		}

		if (isset($this->request->post['config_file_mime_allowed'])) {
			$data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
		}
                elseif (isset($store_info['config_file_mime_allowed'])) {
			$data['config_file_mime_allowed'] = $store_info['config_file_mime_allowed'];
		}
                else {
			$data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
		}

		if (isset($this->request->post['config_maintenance'])) {
			$data['config_maintenance'] = $this->request->post['config_maintenance'];
		}
                elseif (isset($store_info['config_maintenance'])) {
			$data['config_maintenance'] = $store_info['config_maintenance'];
		}
                else {
			$data['config_maintenance'] = $this->config->get('config_maintenance');
		}

		if (isset($this->request->post['config_password'])) {
			$data['config_password'] = $this->request->post['config_password'];
		}
                elseif (isset($store_info['config_password'])) {
			$data['config_password'] = $store_info['config_password'];
		}
                else {
			$data['config_password'] = $this->config->get('config_password');
		}

		if (isset($this->request->post['config_encryption'])) {
			$data['config_encryption'] = $this->request->post['config_encryption'];
		}
                elseif (isset($store_info['config_encryption'])) {
			$data['config_encryption'] = $store_info['config_encryption'];
		}
                else {
			$data['config_encryption'] = $this->config->get('config_encryption');
		}

		if (isset($this->request->post['config_compression'])) {
			$data['config_compression'] = $this->request->post['config_compression'];
		}
                elseif (isset($store_info['config_compression'])) {
			$data['config_compression'] = $store_info['config_compression'];
		}
                else {
			$data['config_compression'] = $this->config->get('config_compression');
		}

		if (isset($this->request->post['config_error_display'])) {
			$data['config_error_display'] = $this->request->post['config_error_display'];
		}
                elseif (isset($store_info['config_error_display'])) {
			$data['config_error_display'] = $store_info['config_error_display'];
		}
                else {
			$data['config_error_display'] = $this->config->get('config_error_display');
		}

		if (isset($this->request->post['config_error_log'])) {
			$data['config_error_log'] = $this->request->post['config_error_log'];
		}
                elseif (isset($store_info['config_error_log'])) {
			$data['config_error_log'] = $store_info['config_error_log'];
		}
                else {
			$data['config_error_log'] = $this->config->get('config_error_log');
		}

		if (isset($this->request->post['config_error_filename'])) {
			$data['config_error_filename'] = $this->request->post['config_error_filename'];
		}
                elseif (isset($store_info['config_error_filename'])) {
			$data['config_error_filename'] = $store_info['config_error_filename'];
		}
                else {
			$data['config_error_filename'] = $this->config->get('config_error_filename');
		}

		if (isset($this->request->post['config_google_analytics'])) {
			$data['config_google_analytics'] = $this->request->post['config_google_analytics'];
		}
                elseif (isset($store_info['config_google_analytics'])) {
			$data['config_google_analytics'] = $store_info['config_google_analytics'];
		}
                else {
			$data['config_google_analytics'] = $this->config->get('config_google_analytics');
		}

                //add new
                $this->load->model('user/user_group');
                $data['user_groups'] = $this->model_user_user_group->getUserGroups();
                
                if (isset($this->request->post['pos_user_group_id'])) {
			$data['pos_user_group_id'] = $this->request->post['pos_user_group_id']; 
		}
                elseif (isset($store_info['pos_user_group_id'])) {
			$data['pos_user_group_id'] = $store_info['pos_user_group_id'];
		}
                else {
			$data['pos_user_group_id'] = $this->config->get('pos_user_group_id');
		} 

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/setting_form.tpl', $data));
	}
public function document() {
		$this->load->language('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
                $this->load->model('setting/store');
                $data["store_id"]=$this->request->get['store_id'];
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_document()) 
                    {
                            //$this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);  
                        
                             $store_id = $this->request->get['store_id'];  
                             $fol =mkdir(DIR_UPLOAD."store_doc/".$store_id, 0777, true);
                        
                        
                         if (!empty($this->request->files['config_fertilizer_file']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_fertilizer_file']['name']);
                                $file_ext=end($file_n);
                                $file_config_fertilizer_file=date('Y_m_d_h_i_s')."_fertilizer_file.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_fertilizer_file;
                                move_uploaded_file($this->request->files['config_fertilizer_file']['tmp_name'], $path);    
                                $this->request->post["config_fertilizer_file"]=$file_config_fertilizer_file;    
                         }
                         if (!empty($this->request->files['config_Pesticide_file']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Pesticide_file']['name']);
                                $file_ext=end($file_n);
                                $file_config_Pesticide_file=date('Y_m_d_h_i_s')."_Pesticide_file.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Pesticide_file;
                                move_uploaded_file($this->request->files['config_Pesticide_file']['tmp_name'], $path);    
                                $this->request->post["config_Pesticide_file"]=$file_config_Pesticide_file;    
                         }
                         if (!empty($this->request->files['config_Seed_file']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Seed_file']['name']);
                                $file_ext=end($file_n);
                                $file_config_Seed_file=date('Y_m_d_h_i_s')."_Seed_file.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Seed_file;
                                move_uploaded_file($this->request->files['config_Seed_file']['tmp_name'], $path);    
                                $this->request->post["config_Seed_file"]=$file_config_Seed_file;    
                         }
                         
                         ///////////////////////
                         
                         if (!empty($this->request->files['config_Bank_signature']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Bank_signature']['name']);
                                $file_ext=end($file_n);
                                $file_config_Bank_signature=date('Y_m_d_h_i_s')."_Bank_signature.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Bank_signature;
                                move_uploaded_file($this->request->files['config_Bank_signature']['tmp_name'], $path);    
                                $this->request->post["config_Bank_signature"]=$file_config_Bank_signature;    
                         }
                         if (!empty($this->request->files['config_Partner_Agreement']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Partner_Agreement']['name']);
                                $file_ext=end($file_n);
                                $file_config_Partner_Agreement=date('Y_m_d_h_i_s')."_Partner_Agreement.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Partner_Agreement;
                                move_uploaded_file($this->request->files['config_Partner_Agreement']['tmp_name'], $path);    
                                $this->request->post["config_Partner_Agreement"]=$file_config_Partner_Agreement;    
                         }
                         if (!empty($this->request->files['config_Stamp_Paper_Agreement_1']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Stamp_Paper_Agreement_1']['name']);
                                $file_ext=end($file_n);
                                $file_config_Stamp_Paper_Agreement_1=date('Y_m_d_h_i_s')."_Stamp_Paper_Agreement_1.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Stamp_Paper_Agreement_1;
                                move_uploaded_file($this->request->files['config_Stamp_Paper_Agreement_1']['tmp_name'], $path);    
                                $this->request->post["config_Stamp_Paper_Agreement_1"]=$file_config_Stamp_Paper_Agreement_1;    
                         }
                         if (!empty($this->request->files['config_Stamp_Paper_Agreement_2']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Stamp_Paper_Agreement_2']['name']);
                                $file_ext=end($file_n);
                                $file_config_Stamp_Paper_Agreement_2=date('Y_m_d_h_i_s')."_Stamp_Paper_Agreement_2.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Stamp_Paper_Agreement_2;
                                move_uploaded_file($this->request->files['config_Stamp_Paper_Agreement_2']['tmp_name'], $path);    
                                $this->request->post["config_Stamp_Paper_Agreement_2"]=$file_config_Stamp_Paper_Agreement_2;    
                         }
                         if (!empty($this->request->files['config_Aadhar_ID_file']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Aadhar_ID_file']['name']);
                                $file_ext=end($file_n);
                                $file_config_Aadhar_ID_file=date('Y_m_d_h_i_s')."_Aadhar_ID_file.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Aadhar_ID_file;
                                move_uploaded_file($this->request->files['config_Aadhar_ID_file']['tmp_name'], $path);    
                                $this->request->post["config_Aadhar_ID_file"]=$file_config_Aadhar_ID_file;    
                         }
                         if (!empty($this->request->files['config_PAN_ID_file']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_PAN_ID_file']['name']);
                                $file_ext=end($file_n);
                                $file_config_PAN_ID_file=date('Y_m_d_h_i_s')."_PAN_ID_file.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_PAN_ID_file;
                                move_uploaded_file($this->request->files['config_PAN_ID_file']['tmp_name'], $path);    
                                $this->request->post["config_PAN_ID_file"]=$file_config_PAN_ID_file;    
                         }
                         if (!empty($this->request->files['config_Residence_Proof']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Residence_Proof']['name']);
                                $file_ext=end($file_n);
                                $file_config_Residence_Proof=date('Y_m_d_h_i_s')."_Residence_Proof.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Residence_Proof;
                                move_uploaded_file($this->request->files['config_Residence_Proof']['tmp_name'], $path);    
                                $this->request->post["config_Residence_Proof"]=$file_config_Residence_Proof;    
                         }
                         if (!empty($this->request->files['config_Bank_Statement']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Bank_Statement']['name']);
                                $file_ext=end($file_n);
                                $file_config_Bank_Statement=date('Y_m_d_h_i_s')."_Bank_Statement.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Bank_Statement;
                                move_uploaded_file($this->request->files['config_Bank_Statement']['tmp_name'], $path);    
                                $this->request->post["config_Bank_Statement"]=$file_config_Bank_Statement;    
                         }
                         if (!empty($this->request->files['config_Signed_Cheque']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Signed_Cheque']['name']);
                                $file_ext=end($file_n);
                                $file_config_Signed_Cheque=date('Y_m_d_h_i_s')."_Signed_Cheque.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Signed_Cheque;
                                move_uploaded_file($this->request->files['config_Signed_Cheque']['tmp_name'], $path);    
                                $this->request->post["config_Signed_Cheque"]=$file_config_Signed_Cheque;    
                         }
                         if (!empty($this->request->files['config_Cheque_issuance']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Cheque_issuance']['name']);
                                $file_ext=end($file_n);
                                $file_config_Cheque_issuance=date('Y_m_d_h_i_s')."_Cheque_issuance.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Cheque_issuance;
                                move_uploaded_file($this->request->files['config_Cheque_issuance']['tmp_name'], $path);    
                                $this->request->post["config_Cheque_issuance"]=$file_config_Cheque_issuance;    
                         }
                         if (!empty($this->request->files['config_Cheque_UFC']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Cheque_UFC']['name']);
                                $file_ext=end($file_n);
                                $file_config_Cheque_UFC=date('Y_m_d_h_i_s')."_Cheque_UFC.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Cheque_UFC;
                                move_uploaded_file($this->request->files['config_Cheque_UFC']['tmp_name'], $path);    
                                $this->request->post["config_Cheque_UFC"]=$file_config_Cheque_UFC;    
                         }
                         if (!empty($this->request->files['config_Cheque_Akshamaala']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Cheque_Akshamaala']['name']);
                                $file_ext=end($file_n);
                                $file_config_Cheque_Akshamaala=date('Y_m_d_h_i_s')."_Cheque_Akshamaala.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Cheque_Akshamaala;
                                move_uploaded_file($this->request->files['config_Cheque_Akshamaala']['tmp_name'], $path);    
                                $this->request->post["config_Cheque_Akshamaala"]=$file_config_Cheque_Akshamaala;    
                         }
                         if (!empty($this->request->files['config_Signature_verification']['name'])) 
                         {
                                $file_n=explode('.',$this->request->files['config_Signature_verification']['name']);
                                $file_ext=end($file_n);
                                $file_config_Signature_verification=date('Y_m_d_h_i_s')."_Signature_verification.".$file_ext;
                                $path=DIR_UPLOAD."store_doc/".$store_id.'/' .$file_config_Signature_verification;
                                move_uploaded_file($this->request->files['config_Signature_verification']['tmp_name'], $path);    
                                $this->request->post["config_Signature_verification"]=$file_config_Signature_verification;    
                         }
                         ////////////////////////
			$this->model_setting_setting->editSetting('config', $this->request->post,$store_id);

			if ($this->config->get('config_currency_auto')) {
				$this->load->model('localisation/currency');

				$this->model_localisation_currency->refresh();
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_tax'] = $this->language->get('text_tax');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if($this->request->server['REQUEST_METHOD'] == 'POST')
                {
                    $data["error_document"]="";
                    $data["error_license"]="";
                    
                }
                else
                {
                    $data["error_document"]="oo";
                    $data["error_license"]="oo";
                   
                }

		$this->load->model('setting/store');

		$data["store_types"]=$this->model_setting_store->getstoretypes();
		//print_r($data["store_types"]);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
                
		
                /////////new fields error handling start here/////////////
                if (isset($this->error['fertilizer_number'])) {
                 		$data['error_fertilizer_number'] = $this->error['fertilizer_number'];
                                $data["error_license"]=$this->error['fertilizer_number'];
               	} else {
               		$data['error_fertilizer_number'] = '';
               	}
                if (isset($this->error['fertilizer_from'])) {
                 		$data['error_fertilizer_from'] = $this->error['fertilizer_from'];
                                $data["error_license"]=$this->error['fertilizer_from'];
               	} else {
               		$data['error_fertilizer_from'] = '';
               	}
                if (isset($this->error['fertilizer_to'])) {
                 		$data['error_fertilizer_to'] = $this->error['fertilizer_to'];
                                $data["error_license"]=$this->error['fertilizer_to'];
               	} else {
               		$data['error_fertilizer_to'] = '';
               	}
                
                
                if (isset($this->error['fertilizer_file'])) {
                 		$data['error_fertilizer_file'] = $this->error['fertilizer_file'];
                                $data["error_license"]=$this->error['fertilizer_file'];
               	} else {
               		$data['error_fertilizer_file'] = '';
               	}
                
                
                if (isset($this->error['Pesticide_number'])) {
                 		$data['error_Pesticide_number'] = $this->error['Pesticide_number'];
                                $data["error_license"]=$this->error['Pesticide_number'];
               	} else {
               		$data['error_Pesticide_number'] = '';
               	}
                if (isset($this->error['Pesticide_from'])) {
                 		$data['error_Pesticide_from'] = $this->error['Pesticide_from'];
                                $data["error_license"]=$this->error['Pesticide_from'];
               	} else {
               		$data['error_Pesticide_from'] = '';
               	}
                if (isset($this->error['Pesticide_to'])) {
                 		$data['error_Pesticide_to'] = $this->error['Pesticide_to'];
                                $data["error_license"]=$this->error['Pesticide_to'];
               	} else {
               		$data['error_Pesticide_to'] = '';
               	}
                
                
                if (isset($this->error['Pesticide_file'])) {
                 		$data['error_Pesticide_file'] = $this->error['Pesticide_file'];
                                $data["error_license"]=$this->error['Pesticide_file'];
               	} else {
               		$data['error_Pesticide_file'] = '';
               	}
                
                
                if (isset($this->error['Seed_number'])) {
                 		$data['error_Seed_number'] = $this->error['Seed_number'];
                                $data["error_license"]=$this->error['Seed_number'];
               	} else {
               		$data['error_Seed_number'] = '';
               	}
                if (isset($this->error['Seed_from'])) {
                 		$data['error_Seed_from'] = $this->error['Seed_from'];
                                $data["error_license"]=$this->error['Seed_from'];
               	} else {
               		$data['error_Seed_from'] = '';
               	}
                if (isset($this->error['Seed_to'])) {
                 		$data['error_Seed_to'] = $this->error['Seed_to'];
                                $data["error_license"]=$this->error['Seed_to'];
               	} else {
               		$data['error_Seed_to'] = '';
               	}
                
                
                if (isset($this->error['Seed_file'])) {
                 		$data['error_Seed_file'] = $this->error['Seed_file'];
                                $data["error_license"]=$this->error['Seed_file'];
               	} else {
               		$data['error_Seed_file'] = '';
               	}
                
                
                if (isset($this->error['Aadhar_ID_number'])) {
                 		$data['error_Aadhar_ID_number'] = $this->error['Aadhar_ID_number'];
                                $data["error_document"]=$this->error['Aadhar_ID_number'];
               	} else {
               		$data['error_Aadhar_ID_number'] = '';
               	}
                if (isset($this->error['PAN_ID_number'])) {
                 		$data['error_PAN_ID_number'] = $this->error['PAN_ID_number'];
                                $data["error_document"]=$this->error['PAN_ID_number'];
               	} else {
               		$data['error_PAN_ID_number'] = '';
               	}
                
                if (isset($this->error['Bank_signature'])) {
                 		$data['error_Bank_signature'] = $this->error['Bank_signature'];
                                $data["error_document"]=$this->error['Bank_signature'];
               	} else {
               		$data['error_Bank_signature'] = '';
               	}
                if (isset($this->error['Partner_Agreement'])) {
                 		$data['error_Partner_Agreement'] = $this->error['Partner_Agreement'];
                                $data["error_document"]=$this->error['Partner_Agreement'];
               	} else {
               		$data['error_Partner_Agreement'] = '';
               	}
                if (isset($this->error['Stamp_Paper_Agreement_1'])) {
                 		$data['error_Stamp_Paper_Agreement_1'] = $this->error['Stamp_Paper_Agreement_1'];
                                $data["error_document"]=$this->error['Stamp_Paper_Agreement_1'];
               	} else {
               		$data['error_Stamp_Paper_Agreement_1'] = '';
               	}
                if (isset($this->error['Stamp_Paper_Agreement_2'])) {
                 		$data['error_Stamp_Paper_Agreement_2'] = $this->error['Stamp_Paper_Agreement_2'];
                                $data["error_document"]=$this->error['Stamp_Paper_Agreement_2'];
               	} else {
               		$data['error_Stamp_Paper_Agreement_2'] = '';
               	}
                if (isset($this->error['Aadhar_ID_file'])) {
                 		$data['error_Aadhar_ID_file'] = $this->error['Aadhar_ID_file'];
                                $data["error_document"]=$this->error['Aadhar_ID_file'];
               	} else {
               		$data['error_Aadhar_ID_file'] = '';
               	}
                if (isset($this->error['PAN_ID_file'])) {
                 		$data['error_PAN_ID_file'] = $this->error['PAN_ID_file'];
                                $data["error_document"]=$this->error['PAN_ID_file'];
               	} else {
               		$data['error_PAN_ID_file'] = '';
               	}
                if (isset($this->error['Residence_Proof'])) {
                 		$data['error_Residence_Proof'] = $this->error['Residence_Proof'];
                                $data["error_document"]=$this->error['Residence_Proof'];
               	} else {
               		$data['error_Residence_Proof'] = '';
               	}
                if (isset($this->error['Bank_Statement'])) {
                 		$data['error_Bank_Statement'] = $this->error['Bank_Statement'];
                                $data["error_document"]=$this->error['Bank_Statement'];
               	} else {
               		$data['error_Bank_Statement'] = '';
               	}
                if (isset($this->error['Signed_Cheque'])) {
                 		$data['error_Signed_Cheque'] = $this->error['Signed_Cheque'];
                                $data["error_document"]=$this->error['Signed_Cheque'];
               	} else {
               		$data['error_Signed_Cheque'] = '';
               	}
                if (isset($this->error['Cheque_issuance'])) {
                 		$data['error_Cheque_issuance'] = $this->error['Cheque_issuance'];
                                $data["error_document"]=$this->error['Cheque_issuance'];
               	} else {
               		$data['error_Cheque_issuance'] = '';
               	}
                if (isset($this->error['Cheque_UFC'])) {
                 		$data['error_Cheque_UFC'] = $this->error['Cheque_UFC'];
                                $data["error_document"]=$this->error['Cheque_UFC'];
               	} else {
               		$data['error_Cheque_UFC'] = '';
               	}
                if (isset($this->error['Cheque_Akshamaala'])) {
                 		$data['error_Cheque_Akshamaala'] = $this->error['Cheque_Akshamaala'];
                                $data["error_document"]=$this->error['Cheque_Akshamaala'];
               	} else {
               		$data['error_Cheque_Akshamaala'] = '';
               	}
                if (isset($this->error['Signature_verification'])) {
                 		$data['error_Signature_verification'] = $this->error['Signature_verification'];
                                $data["error_document"]=$this->error['Signature_verification'];
               	} else {
               		$data['error_Signature_verification'] = '';
               	}
                ////////////new fields error handling end here///////////////
                

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_stores'),
			'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/setting/document', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

                if (!isset($this->request->get['store_id'])) {
			$data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('setting/setting/document', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL');
		}
                
		//$data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];
                // && ($this->request->server['REQUEST_METHOD'] != 'POST')
                if (isset($this->request->get['store_id'])) {
			$this->load->model('setting/setting');

			$store_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
		}
                
                
                if (isset($this->request->post['config_fertilizer_number'])) {
			$data['config_fertilizer_number'] = $this->request->post['config_fertilizer_number'];
		}
                	elseif (isset($store_info['config_fertilizer_number'])) {
			$data['config_fertilizer_number'] = $store_info['config_fertilizer_number'];
		}
                	else {
			//$data['config_fertilizer_number'] = $this->config->get('config_fertilizer_number');
		}
                if (isset($this->request->post['config_fertilizer_from'])) {
			$data['config_fertilizer_from'] = $this->request->post['config_fertilizer_from'];
		}
                	elseif (isset($store_info['config_fertilizer_from'])) {
			$data['config_fertilizer_from'] = $store_info['config_fertilizer_from'];
		}
                	else {
			//$data['config_fertilizer_from'] = $this->config->get('config_fertilizer_from');
		}
                if (isset($this->request->post['config_fertilizer_to'])) {
			$data['config_fertilizer_to'] = $this->request->post['config_fertilizer_to'];
		}
                	elseif (isset($store_info['config_fertilizer_to'])) {
			$data['config_fertilizer_to'] = $store_info['config_fertilizer_to'];
		}
                	else {
			//$data['config_fertilizer_to'] = $this->config->get('config_fertilizer_to');
		}
                if (isset($this->request->post['config_fertilizer_file'])) {
			$data['config_fertilizer_file'] = $this->request->post['config_fertilizer_file'];
		}
                	elseif (isset($store_info['config_fertilizer_file'])) {
			$data['config_fertilizer_file'] = $store_info['config_fertilizer_file'];
		}
                	else {
			//$data['config_fertilizer_file'] = $this->config->get('config_fertilizer_file');
		}
                if (isset($this->request->post['config_Pesticide_number'])) {
			$data['config_Pesticide_number'] = $this->request->post['config_Pesticide_number'];
		}
                	elseif (isset($store_info['config_Pesticide_number'])) {
			$data['config_Pesticide_number'] = $store_info['config_Pesticide_number'];
		}
                	else {
			//$data['config_Pesticide_number'] = $this->config->get('config_Pesticide_number');
		}
                if (isset($this->request->post['config_Pesticide_from'])) {
			$data['config_Pesticide_from'] = $this->request->post['config_Pesticide_from'];
		}
                	elseif (isset($store_info['config_Pesticide_from'])) {
			$data['config_Pesticide_from'] = $store_info['config_Pesticide_from'];
		}
                	else {
			//$data['config_Pesticide_from'] = $this->config->get('config_Pesticide_from');
		}
                if (isset($this->request->post['config_Pesticide_to'])) {
			$data['config_Pesticide_to'] = $this->request->post['config_Pesticide_to'];
		}
                	elseif (isset($store_info['config_Pesticide_to'])) {
			$data['config_Pesticide_to'] = $store_info['config_Pesticide_to'];
		}
                	else {
			//$data['config_Pesticide_to'] = $this->config->get('config_Pesticide_to');
		}
                if (isset($this->request->post['config_Pesticide_file'])) {
			$data['config_Pesticide_file'] = $this->request->post['config_Pesticide_file'];
		}
                	elseif (isset($store_info['config_Pesticide_file'])) {
			$data['config_Pesticide_file'] = $store_info['config_Pesticide_file'];
		}
                	else {
			//$data['config_Pesticide_file'] = $this->config->get('config_Pesticide_file');
		}
                if (isset($this->request->post['config_Seed_number'])) {
			$data['config_Seed_number'] = $this->request->post['config_Seed_number'];
		}
                	elseif (isset($store_info['config_Seed_number'])) {
			$data['config_Seed_number'] = $store_info['config_Seed_number'];
		}
                	else {
			//$data['config_Seed_number'] = $this->config->get('config_Seed_number');
		}
                if (isset($this->request->post['config_Seed_from'])) {
			$data['config_Seed_from'] = $this->request->post['config_Seed_from'];
		}
                	elseif (isset($store_info['config_Seed_from'])) {
			$data['config_Seed_from'] = $store_info['config_Seed_from'];
		}
                	else {
			//$data['config_Seed_from'] = $this->config->get('config_Seed_from');
		}
                if (isset($this->request->post['config_Seed_to'])) {
			$data['config_Seed_to'] = $this->request->post['config_Seed_to'];
		}
                	elseif (isset($store_info['config_Seed_to'])) {
			$data['config_Seed_to'] = $store_info['config_Seed_to'];
		}
                	else {
			//$data['config_Seed_to'] = $this->config->get('config_Seed_to');
		}
                if (isset($this->request->post['config_Seed_file'])) {
			$data['config_Seed_file'] = $this->request->post['config_Seed_file'];
		}
                	elseif (isset($store_info['config_Seed_file'])) {
			$data['config_Seed_file'] = $store_info['config_Seed_file'];
		}
                	else {
			//$data['config_Seed_file'] = $this->config->get('config_Seed_file');
		}
                /////////////////////
                
                if (isset($this->request->post['config_Bank_signature'])) {
			$data['config_Bank_signature'] = $this->request->post['config_Bank_signature'];
		}
                	elseif (isset($store_info['config_Bank_signature'])) {
			$data['config_Bank_signature'] = $store_info['config_Bank_signature'];
		}
                	else {
			//$data['config_Bank_signature'] = $this->config->get('config_Bank_signature');
		}
                if (isset($this->request->post['config_Partner_Agreement'])) {
			$data['config_Partner_Agreement'] = $this->request->post['config_Partner_Agreement'];
		}
                	elseif (isset($store_info['config_Partner_Agreement'])) {
			$data['config_Partner_Agreement'] = $store_info['config_Partner_Agreement'];
		}
                	else {
			//$data['config_Partner_Agreement'] = $this->config->get('config_Partner_Agreement');
		}
                if (isset($this->request->post['config_Stamp_Paper_Agreement_1'])) {
			$data['config_Stamp_Paper_Agreement_1'] = $this->request->post['config_Stamp_Paper_Agreement_1'];
		}
                	elseif (isset($store_info['config_Stamp_Paper_Agreement_1'])) {
			$data['config_Stamp_Paper_Agreement_1'] = $store_info['config_Stamp_Paper_Agreement_1'];
		}
                	else {
			//$data['config_Stamp_Paper_Agreement_1'] = $this->config->get('config_Stamp_Paper_Agreement_1');
		}
                if (isset($this->request->post['config_Stamp_Paper_Agreement_2'])) {
			$data['config_Stamp_Paper_Agreement_2'] = $this->request->post['config_Stamp_Paper_Agreement_2'];
		}
                	elseif (isset($store_info['config_Stamp_Paper_Agreement_2'])) {
			$data['config_Stamp_Paper_Agreement_2'] = $store_info['config_Stamp_Paper_Agreement_2'];
		}
                	else {
			//$data['config_Stamp_Paper_Agreement_2'] = $this->config->get('config_Stamp_Paper_Agreement_2');
		}
                if (isset($this->request->post['config_Aadhar_ID_number'])) {
			$data['config_Aadhar_ID_number'] = $this->request->post['config_Aadhar_ID_number'];
		}
                	elseif (isset($store_info['config_Aadhar_ID_number'])) {
			$data['config_Aadhar_ID_number'] = $store_info['config_Aadhar_ID_number'];
		}
                	else {
			//$data['config_Aadhar_ID_number'] = $this->config->get('config_Aadhar_ID_number');
		}
                if (isset($this->request->post['config_Aadhar_ID_file'])) {
			$data['config_Aadhar_ID_file'] = $this->request->post['config_Aadhar_ID_file'];
		}
                	elseif (isset($store_info['config_Aadhar_ID_file'])) {
			$data['config_Aadhar_ID_file'] = $store_info['config_Aadhar_ID_file'];
		}
                	else {
			//$data['config_Aadhar_ID_file'] = $this->config->get('config_Aadhar_ID_file');
		}
                if (isset($this->request->post['config_PAN_ID_number'])) {
			$data['config_PAN_ID_number'] = $this->request->post['config_PAN_ID_number'];
		}
                	elseif (isset($store_info['config_PAN_ID_number'])) {
			$data['config_PAN_ID_number'] = $store_info['config_PAN_ID_number'];
		}
                	else {
			//$data['config_PAN_ID_number'] = $this->config->get('config_PAN_ID_number');
		}
                if (isset($this->request->post['config_PAN_ID_file'])) {
			$data['config_PAN_ID_file'] = $this->request->post['config_PAN_ID_file'];
		}
                	elseif (isset($store_info['config_PAN_ID_file'])) {
			$data['config_PAN_ID_file'] = $store_info['config_PAN_ID_file'];
		}
                	else {
			//$data['config_PAN_ID_file'] = $this->config->get('config_PAN_ID_file');
		}
                if (isset($this->request->post['config_Residence_Proof'])) {
			$data['config_Residence_Proof'] = $this->request->post['config_Residence_Proof'];
		}
                	elseif (isset($store_info['config_Residence_Proof'])) {
			$data['config_Residence_Proof'] = $store_info['config_Residence_Proof'];
		}
                	else {
			//$data['config_Residence_Proof'] = $this->config->get('config_Residence_Proof');
		}
                if (isset($this->request->post['config_Bank_Statement'])) {
			$data['config_Bank_Statement'] = $this->request->post['config_Bank_Statement'];
		}
                	elseif (isset($store_info['config_Bank_Statement'])) {
			$data['config_Bank_Statement'] = $store_info['config_Bank_Statement'];
		}
                	else {
			//$data['config_Bank_Statement'] = $this->config->get('config_Bank_Statement');
		}
                if (isset($this->request->post['config_Signed_Cheque'])) {
			$data['config_Signed_Cheque'] = $this->request->post['config_Signed_Cheque'];
		}
                	elseif (isset($store_info['config_Signed_Cheque'])) {
			$data['config_Signed_Cheque'] = $store_info['config_Signed_Cheque'];
		}
                	else {
			//$data['config_Signed_Cheque'] = $this->config->get('config_Signed_Cheque');
		}
                if (isset($this->request->post['config_Cheque_issuance'])) {
			$data['config_Cheque_issuance'] = $this->request->post['config_Cheque_issuance'];
		}
                	elseif (isset($store_info['config_Cheque_issuance'])) {
			$data['config_Cheque_issuance'] = $store_info['config_Cheque_issuance'];
		}
                	else {
			//$data['config_Cheque_issuance'] = $this->config->get('config_Cheque_issuance');
		}
                if (isset($this->request->post['config_Cheque_UFC'])) {
			$data['config_Cheque_UFC'] = $this->request->post['config_Cheque_UFC'];
		}
                	elseif (isset($store_info['config_Cheque_UFC'])) {
			$data['config_Cheque_UFC'] = $store_info['config_Cheque_UFC'];
		}
                	else {
			//$data['config_Cheque_UFC'] = $this->config->get('config_Cheque_UFC');
		}
                if (isset($this->request->post['config_Cheque_Akshamaala'])) {
			$data['config_Cheque_Akshamaala'] = $this->request->post['config_Cheque_Akshamaala'];
		}
                	elseif (isset($store_info['config_Cheque_Akshamaala'])) {
			$data['config_Cheque_Akshamaala'] = $store_info['config_Cheque_Akshamaala'];
		}
                	else {
			//$data['config_Cheque_Akshamaala'] = $this->config->get('config_Cheque_Akshamaala');
		}
                if (isset($this->request->post['config_Signature_verification'])) {
			$data['config_Signature_verification'] = $this->request->post['config_Signature_verification'];
		}
                	elseif (isset($store_info['config_Signature_verification'])) {
			$data['config_Signature_verification'] = $store_info['config_Signature_verification'];
		}
                	else {
			//$data['config_Signature_verification'] = $this->config->get('config_Signature_verification');
		}
                //////////////new fields end here/////////////
		
		if (isset($this->request->post['config_name'])) {
			$data['config_name'] = $this->request->post['config_name'];
		}
                elseif (isset($store_info['config_name'])) {
			$data['config_name'] = $store_info['config_name'];
		}
                else {
			//$data['config_name'] = $this->config->get('config_name');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/setting_document.tpl', $data));
	}
	protected function validate() {
                $file_ext=array('pdf','zip','rar');
                
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
                if (!$this->request->post['config_url']) {
			$this->error['url'] = 'Please enter Tagged URL';
		}
		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}
		if (!$this->request->post['config_registration_date']) {
			$this->error['registration_date'] = 'Please Select Registration Date';
		}
		if (!$this->request->post['config_unit']) {
			$this->error['unit'] = 'Please Select Unit';
		}
		if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
			$this->error['address'] = $this->language->get('error_address');
		}
		if ((utf8_strlen($this->request->post['config_head_office']) < 3) || (utf8_strlen($this->request->post['config_head_office']) > 256)) {
			$this->error['head_office'] = 'Head Office Address length must be between 3 and 256';
		}
		if ((utf8_strlen($this->request->post['config_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['config_email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['config_telephone']) < 10) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
			$this->error['telephone'] ='Telephone must be between 10 and 32 characters!' ;//$this->language->get('error_telephone');
		}

		if (!$this->request->post['config_meta_title']) {
			$this->error['meta_title'] = $this->language->get('error_meta_title');
		}
		
		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
		}
		
		if ($this->request->post['config_login_attempts'] < 1) {
			$this->error['login_attempts'] = $this->language->get('error_login_attempts');
		}
		
		if (!$this->request->post['config_voucher_min']) {
			$this->error['voucher_min'] = $this->language->get('error_voucher_min');
		}

		if (!$this->request->post['config_voucher_max']) {
			$this->error['voucher_max'] = $this->language->get('error_voucher_max');
		}

		if (!isset($this->request->post['config_processing_status'])) {
			$this->error['processing_status'] = $this->language->get('error_processing_status');
		}

		if (!isset($this->request->post['config_complete_status'])) {
			$this->error['complete_status'] = $this->language->get('error_complete_status');
		}

		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		}

		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}

		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}

		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}

		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}

		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}

		if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
			$this->error['image_compare'] = $this->language->get('error_image_compare');
		}

		if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
			$this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
		}

		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}

		if (!$this->request->post['config_image_location_width'] || !$this->request->post['config_image_location_height']) {
			$this->error['image_location'] = $this->language->get('error_image_location');
		}

		if ($this->request->post['config_ftp_status']) {
			if (!$this->request->post['config_ftp_hostname']) {
				$this->error['ftp_hostname'] = $this->language->get('error_ftp_hostname');
			}

			if (!$this->request->post['config_ftp_port']) {
				$this->error['ftp_port'] = $this->language->get('error_ftp_port');
			}

			if (!$this->request->post['config_ftp_username']) {
				$this->error['ftp_username'] = $this->language->get('error_ftp_username');
			}

			if (!$this->request->post['config_ftp_password']) {
				$this->error['ftp_password'] = $this->language->get('error_ftp_password');
			}
		}

		if (!$this->request->post['config_error_filename']) {
			$this->error['error_error_filename'] = $this->language->get('error_error_filename');
		}

		if (!$this->request->post['config_product_limit']) {
			$this->error['product_limit'] = $this->language->get('error_limit');
		}

		if (!$this->request->post['config_product_description_length']) {
			$this->error['product_description_length'] = $this->language->get('error_limit');
		}

		if (!$this->request->post['config_limit_admin']) {
			$this->error['limit_admin'] = $this->language->get('error_limit');
		}
		if (!$this->request->post['config_tin']) {
			$this->error['tin'] = 'Please enter TIN number';
		}
                            if (!$this->request->post['config_cin']) {
			$this->error['cin'] = 'Please enter CIN number';
		}
		if (!$this->request->post['config_gstn']) {
			$this->error['gstn'] = 'Please enter GST number';
		}
                /////////new fields error handling start here/////////////
                
                
                if (!$this->request->post['config_MSMFID']) {
			$this->error['MSMFID'] = 'Please enter MSMFID number';
		}
                
                ///////////////for file handling start here//////////
                
                   if (!$this->request->files['config_GST_doc']["name"]) 
                   {
                       if($this->request->get["store_id"]=="")
                       {
                           $this->error['GST_doc'] = 'Please select GST doc file';
                       }
                       elseif (!$this->request->post['GST_doc_h']) 
                       {
                           $this->error['GST_doc'] = 'Please select GST doc file';
                       }
                   }
                   else 
                   {
                     
                     $file_n_GST_doc=explode('.',$this->request->files['config_GST_doc']['name']);
                     $file_ext_GST_doc=end($file_n_GST_doc); 
                     if(!in_array($file_ext_GST_doc, $file_ext))
                     {
                         $this->error['GST_doc'] = 'Only pdf file is allowd for GST doc file';
                     }
                    
                   }
                   
                ///////////////for file handling end here//////////
                
                if (!$this->request->post['config_Bank_Name']) {
			$this->error['Bank_Name'] = 'Please Select Bank name';
		}
                if (!$this->request->post['config_Account_Number']) {
			$this->error['Account_Number'] = 'Please enter Account number';
		}
                if (!$this->request->post['config_IFSC_Code']) {
			$this->error['IFSC_Code'] = 'Please enter ISC code';
		}
                if (!$this->request->post['config_Account_Holder_name']) {
			$this->error['Account_Holder_name'] = 'Please enter Account holder namme';
		}
                if (!$this->request->post['config_Branch_Name']) {
			$this->error['Branch_Name'] = 'Please enter branch name';
		}
                
                
                ////////////new fields error handling end here///////////////
		if (!$this->request->post['config_company']) {
			$this->error['company'] = 'Please Select Company';
		}
		if ($this->request->post['config_printer']=="") {
			$this->error['printer'] = 'Please Select Printer Status';
		}
		if (!$this->request->post['config_storetype']) {
			$this->error['storetype'] = 'Please Select Store Type';
		}
		else
		{
		     if(($this->request->post['config_storetype']=="3") || ($this->request->post['config_storetype']=="4"))
		     {
			if($this->request->post['config_firmname']=="")
			{
				$this->error['firmname'] = 'Please Enter Firmname';
			}
		     }
		}
		if (!$this->request->post['config_creditlimit']) {
			$this->error['creditlimit'] = 'Please enter credit limit'; 
		}
		if ((utf8_strlen($this->request->post['config_encryption']) < 3) || (utf8_strlen($this->request->post['config_encryption']) > 32)) {
			$this->error['encryption'] = $this->language->get('error_encryption');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning'); 
		}

		return !$this->error;
	}
protected function validate_document() {
                $file_ext=array('pdf','zip','rar');
                
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
                
                /////////new fields error handling start here/////////////
                if (!$this->request->post['config_fertilizer_number']) {
			//$this->error['fertilizer_number'] = 'Please enter fertilizer license number';
		}
                if (!$this->request->post['config_fertilizer_from']) {
			//$this->error['fertilizer_from'] = 'Please select fertilizer license start date';
		}
                if (!$this->request->post['config_fertilizer_to']) {
			//$this->error['fertilizer_to'] = 'Please select fertilizer license end date';
		}
                
                if (!$this->request->post['config_Pesticide_number']) {
			//$this->error['Pesticide_number'] = 'Please enter Pesticide license number';
		}
                if (!$this->request->post['config_Pesticide_from']) {
			//$this->error['Pesticide_from'] = 'Please select Pesticide license start date';
		}
                if (!$this->request->post['config_Pesticide_to']) {
			//$this->error['Pesticide_to'] = 'Please select Pesticide license end date';
		}
                
                if (!$this->request->post['config_Seed_number']) {
			//$this->error['Seed_number'] = 'Please enter seed license number';
		}
                if (!$this->request->post['config_Seed_from']) {
			//$this->error['Seed_from'] = 'Please select seed license start date';
		}
                if (!$this->request->post['config_Seed_to']) {
			//$this->error['Seed_to'] = 'Please select seed license end date';
		}
                
                if (!$this->request->post['config_Aadhar_ID_number']) {
			//$this->error['Aadhar_ID_number'] = 'Please enter Aadhar number';
		}
                if (!$this->request->post['config_PAN_ID_number']) {
			//$this->error['PAN_ID_number'] = 'Please enter PAN number';
		}
                ///////////////for file handling start here//////////
                
                  
                   /////////////////////
                   if (!$this->request->files['config_fertilizer_file']["name"]) 
                   {
                       if (!$this->request->post['fertilizer_file_h']) 
                       {
			//$this->error['fertilizer_file'] = 'Please select fertilizer license file';
		       }
                   }
                   else 
                   {
                     
                     $file_n_fertilizer_file=explode('.',$this->request->files['config_fertilizer_file']['name']);
                     $file_ext_fertilizer_file=end($file_n_fertilizer_file); 
                     if(!in_array($file_ext_fertilizer_file, $file_ext))
                     {
                         $this->error['fertilizer_file'] = 'Only pdf file is allowd for fertilizer license file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Pesticide_file']["name"]) 
                   {    
                        if (!$this->request->post['Pesticide_file_h']) 
                        {
			//$this->error['Pesticide_file'] = 'Please select Pesticide license file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Pesticide_file=explode('.',$this->request->files['config_Pesticide_file']['name']);
                     $file_ext_Pesticide_file=end($file_n_Pesticide_file); 
                     if(!in_array($file_ext_Pesticide_file, $file_ext))
                     {
                         $this->error['Pesticide_file'] = 'Only pdf file is allowd for Pesticide license file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Seed_file']["name"]) 
                   {
                        if (!$this->request->post['Seed_file_h']) 
                        {
			//$this->error['Seed_file'] = 'Please select seed license file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Seed_file=explode('.',$this->request->files['config_Seed_file']['name']);
                     $file_ext_Seed_file=end($file_n_Seed_file); 
                     if(!in_array($file_ext_Seed_file, $file_ext))
                     {
                         $this->error['Seed_file'] = 'Only pdf file is allowd for Pesticide license file';
                     }
                     
                   }
                /////////////document////////
                   
                   
                   if (!$this->request->files['config_Bank_signature']["name"]) 
                   {
                        if (!$this->request->post['Bank_signature_h']) 
                        {
			//$this->error['Bank_signature'] = 'Please select Bank signature file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Bank_signature=explode('.',$this->request->files['config_Bank_signature']['name']);
                     $file_ext_Bank_signature=end($file_n_Bank_signature); 
                     if(!in_array($file_ext_Bank_signature, $file_ext))
                     {
                         $this->error['Bank_signature'] = 'Only pdf file is allowd for Bank signature file';
                     }
                   }
                   /////////////////////
                   if (!$this->request->files['config_Partner_Agreement']["name"]) 
                   {
                        if (!$this->request->post['Partner_Agreement_h']) 
                        {
			//$this->error['Partner_Agreement'] = 'Please select Partner Agreement file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Partner_Agreement_file=explode('.',$this->request->files['config_Partner_Agreement']['name']);
                     $file_ext_Partner_Agreement=end($file_n_Partner_Agreement_file); 
                     if(!in_array($file_ext_Partner_Agreement, $file_ext))
                     {
                         $this->error['Partner_Agreement'] = 'Only pdf file is allowd for Partner Agreement  file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Stamp_Paper_Agreement_1']["name"]) 
                   {
                        if (!$this->request->post['Stamp_Paper_Agreement_1_h']) 
                        {
			//$this->error['Stamp_Paper_Agreement_1'] = 'Please select Stamp Paper Agreement copy 1  file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Stamp_Paper_Agreement_1=explode('.',$this->request->files['config_Stamp_Paper_Agreement_1']['name']);
                     $file_ext_Stamp_Paper_Agreement_1=end($file_n_Stamp_Paper_Agreement_1); 
                     if(!in_array($file_ext_Stamp_Paper_Agreement_1, $file_ext))
                     {
                         $this->error['Stamp_Paper_Agreement_1'] = 'Only pdf file is allowd for Stamp Paper Agreement copy 1 file';
                     }
                     
                   }
                   //////////
                   if (!$this->request->files['config_Stamp_Paper_Agreement_2']["name"]) 
                   {
                        if (!$this->request->post['Stamp_Paper_Agreement_2_h']) 
                        {
			//$this->error['Stamp_Paper_Agreement_2'] = 'Please select Stamp Paper Agreement copy 1  file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Stamp_Paper_Agreement_2=explode('.',$this->request->files['config_Stamp_Paper_Agreement_2']['name']);
                     $file_ext_Stamp_Paper_Agreement_2=end($file_n_Stamp_Paper_Agreement_2); 
                     if(!in_array($file_ext_Stamp_Paper_Agreement_2, $file_ext))
                     {
                         $this->error['Stamp_Paper_Agreement_2'] = 'Only pdf file is allowd for Stamp Paper Agreement copy 2 file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Aadhar_ID_file']["name"]) 
                   {
                        if (!$this->request->post['Aadhar_ID_file_h']) 
                        {
			//$this->error['Aadhar_ID_file'] = 'Please select Aadhar ID file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Aadhar_ID_file=explode('.',$this->request->files['config_Aadhar_ID_file']['name']);
                     $file_ext_Aadhar_ID_file=end($file_n_Aadhar_ID_file); 
                     if(!in_array($file_ext_Aadhar_ID_file, $file_ext))
                     {
                         $this->error['Aadhar_ID_file'] = 'Only pdf file is allowd for Aadhar ID file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_PAN_ID_file']["name"]) 
                   {
                        if (!$this->request->post['PAN_ID_file_h']) 
                        {
			//$this->error['PAN_ID_file'] = 'Please select PAN CARD file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_PAN_ID_file=explode('.',$this->request->files['config_PAN_ID_file']['name']);
                     $file_ext_PAN_ID_file=end($file_n_PAN_ID_file); 
                     if(!in_array($file_ext_PAN_ID_file, $file_ext))
                     {
                         $this->error['PAN_ID_file'] = 'Only pdf file is allowd for PAN CARD file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Residence_Proof']["name"]) 
                   {
                        if (!$this->request->post['Residence_Proof_h']) 
                        {
			//$this->error['Residence_Proof'] = 'Please select seed Residence Proof file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Residence_Proof_file=explode('.',$this->request->files['config_Residence_Proof']['name']);
                     $file_ext_Residence_Proof_file=end($file_n_Residence_Proof_file); 
                     if(!in_array($file_ext_Residence_Proof_file, $file_ext))
                     {
                         $this->error['Residence_Proof'] = 'Only pdf file is allowd for Residence Proof file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Bank_Statement']["name"]) 
                   {
                        if (!$this->request->post['Bank_Statement_h']) 
                        {
			//$this->error['Bank_Statement'] = 'Please select Bank Statement file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Bank_Statement_file=explode('.',$this->request->files['config_Bank_Statement']['name']);
                     $file_ext_Bank_Statement_file=end($file_n_Bank_Statement_file); 
                     if(!in_array($file_ext_Bank_Statement_file, $file_ext))
                     {
                         $this->error['Bank_Statement'] = 'Only pdf file is allowd for Bank Statement file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Signed_Cheque']["name"]) 
                   {
                        if (!$this->request->post['Signed_Cheque_h']) 
                        {
			//$this->error['Signed_Cheque'] = 'Please select Signed Cheque file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Signed_Cheque_file=explode('.',$this->request->files['config_Signed_Cheque']['name']);
                     $file_ext_Signed_Cheque_file=end($file_n_Signed_Cheque_file); 
                     if(!in_array($file_ext_Signed_Cheque_file, $file_ext))
                     {
                         $this->error['Signed_Cheque'] = 'Only pdf file is allowd for Signed Cheque file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Cheque_issuance']["name"]) 
                   {
                        if (!$this->request->post['Cheque_issuance_h']) 
                        {
			//$this->error['Cheque_issuance'] = 'Please select Cheque issuance file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Cheque_issuance_file=explode('.',$this->request->files['config_Cheque_issuance']['name']);
                     $file_ext_Cheque_issuance_file=end($file_n_Cheque_issuance_file); 
                     if(!in_array($file_ext_Cheque_issuance_file, $file_ext))
                     {
                         $this->error['Cheque_issuance'] = 'Only pdf file is allowd for Cheque issuance file';
                     }
                     
                   }
                   ////////////////////////////
                   if (!$this->request->files['config_Cheque_UFC']["name"]) 
                   {
                        
                   }
                   else 
                   {
                     
                     $file_n_config_Cheque_UFC_file=explode('.',$this->request->files['config_Cheque_UFC']['name']);
                     $file_ext_config_Cheque_UFC_file=end($file_n_config_Cheque_UFC_file); 
                     if(!in_array($file_ext_config_Cheque_UFC_file, $file_ext))
                     {
                         $this->error['Cheque_UFC'] = 'Only pdf file is allowd for Cheque of UFC branding ';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Cheque_Akshamaala']["name"]) 
                   {
                        if (!$this->request->post['Cheque_Akshamaala_h']) 
                        {
			//$this->error['Cheque_Akshamaala'] = 'Please select Cheque Akshamaala file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Cheque_Akshamaala_file=explode('.',$this->request->files['config_Cheque_Akshamaala']['name']);
                     $file_ext_Cheque_Akshamaala_file=end($file_n_Cheque_Akshamaala_file); 
                     if(!in_array($file_ext_Cheque_Akshamaala_file, $file_ext))
                     {
                         $this->error['Cheque_Akshamaala'] = 'Only pdf file is allowd for Cheque Akshamaala file';
                     }
                     
                   }
                   /////////////////////
                   if (!$this->request->files['config_Signature_verification']["name"]) 
                   {
                        if (!$this->request->post['Signature_verification_h']) 
                        {
			//$this->error['Signature_verification'] = 'Please select Signature verification file';
                        }
                   }
                   else 
                   {
                     
                     $file_n_Signature_verification_file=explode('.',$this->request->files['config_Signature_verification']['name']);
                     $file_ext_Signature_verification_file=end($file_n_Signature_verification_file); 
                     if(!in_array($file_ext_Signature_verification_file, $file_ext))
                     {
                         $this->error['Signature_verification'] = 'Only pdf file is allowd for Signature verification file';
                     }
                     
                   }
                ///////////////for file handling end here//////////
                
                ////////////new fields error handling end here///////////////
		

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
	public function template() {

$log=new Log("tem.log");
$log->write($this->request->server['HTTPS']);
		if ($this->request->server['HTTPS']) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}
$log->write(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png');


		if (is_file(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
			$this->response->setOutput($server . 'image/templates/' . basename($this->request->get['template']) . '.png');
		} else {
			$this->response->setOutput($server . 'image/no_image.png');
		}
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        	public function view() {
		$this->load->language('setting/setting');

		$this->document->setTitle('View store details');

		$this->load->model('setting/setting');
                $this->load->model('setting/store');
                $data["store_id"]=$this->request->get['store_id'];

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_stock'] = $this->language->get('text_stock');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_mail'] = $this->language->get('text_mail');
		$data['text_smtp'] = $this->language->get('text_smtp');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_owner'] = $this->language->get('entry_owner');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_geocode'] = $this->language->get('entry_geocode');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_open'] = $this->language->get('entry_open');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_admin_language'] = $this->language->get('entry_admin_language');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_currency_auto'] = $this->language->get('entry_currency_auto');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_product_limit'] = $this->language->get('entry_product_limit');
		$data['entry_product_description_length'] = $this->language->get('entry_product_description_length');
		$data['entry_limit_admin'] = $this->language->get('entry_limit_admin');
		$data['entry_product_count'] = $this->language->get('entry_product_count');
		$data['entry_review'] = $this->language->get('entry_review');
		$data['entry_review_guest'] = $this->language->get('entry_review_guest');
		$data['entry_review_mail'] = $this->language->get('entry_review_mail');
		$data['entry_voucher_min'] = $this->language->get('entry_voucher_min');
		$data['entry_voucher_max'] = $this->language->get('entry_voucher_max');
		$data['entry_tax'] = $this->language->get('entry_tax');

                $data['entry_tax_included'] = $this->language->get('entry_tax_included');
		$data['entry_tax_included_store_based'] = $this->language->get('entry_tax_included_store_based');
		$data['entry_tax_included_country'] = $this->language->get('entry_tax_included_country');
		$data['entry_tax_included_zone'] = $this->language->get('entry_tax_included_zone');

		$data['entry_tax_default'] = $this->language->get('entry_tax_default');
		$data['entry_tax_customer'] = $this->language->get('entry_tax_customer');
		$data['entry_customer_online'] = $this->language->get('entry_customer_online');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer_group_display'] = $this->language->get('entry_customer_group_display');
		$data['entry_customer_price'] = $this->language->get('entry_customer_price');
		$data['entry_login_attempts'] = $this->language->get('entry_login_attempts');
		$data['entry_account'] = $this->language->get('entry_account');
		$data['entry_account_mail'] = $this->language->get('entry_account_mail');
		$data['entry_invoice_prefix'] = $this->language->get('entry_invoice_prefix');
		$data['entry_api'] = $this->language->get('entry_api');
		$data['entry_cart_weight'] = $this->language->get('entry_cart_weight');
		$data['entry_checkout_guest'] = $this->language->get('entry_checkout_guest');
		$data['entry_checkout'] = $this->language->get('entry_checkout');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_processing_status'] = $this->language->get('entry_processing_status');
		$data['entry_complete_status'] = $this->language->get('entry_complete_status');
		$data['entry_order_mail'] = $this->language->get('entry_order_mail');
		$data['entry_stock_display'] = $this->language->get('entry_stock_display');
		$data['entry_stock_warning'] = $this->language->get('entry_stock_warning');
		$data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$data['entry_affiliate_approval'] = $this->language->get('entry_affiliate_approval');
		$data['entry_affiliate_auto'] = $this->language->get('entry_affiliate_auto');
		$data['entry_affiliate_commission'] = $this->language->get('entry_affiliate_commission');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_affiliate_mail'] = $this->language->get('entry_affiliate_mail');
		$data['entry_return'] = $this->language->get('entry_return');
		$data['entry_return_status'] = $this->language->get('entry_return_status');
		$data['entry_logo'] = $this->language->get('entry_logo');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_image_category'] = $this->language->get('entry_image_category');
		$data['entry_image_thumb'] = $this->language->get('entry_image_thumb');
		$data['entry_image_popup'] = $this->language->get('entry_image_popup');
		$data['entry_image_product'] = $this->language->get('entry_image_product');
		$data['entry_image_additional'] = $this->language->get('entry_image_additional');
		$data['entry_image_related'] = $this->language->get('entry_image_related');
		$data['entry_image_compare'] = $this->language->get('entry_image_compare');
		$data['entry_image_wishlist'] = $this->language->get('entry_image_wishlist');
		$data['entry_image_cart'] = $this->language->get('entry_image_cart');
		$data['entry_image_location'] = $this->language->get('entry_image_location');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_ftp_hostname'] = $this->language->get('entry_ftp_hostname');
		$data['entry_ftp_port'] = $this->language->get('entry_ftp_port');
		$data['entry_ftp_username'] = $this->language->get('entry_ftp_username');
		$data['entry_ftp_password'] = $this->language->get('entry_ftp_password');
		$data['entry_ftp_root'] = $this->language->get('entry_ftp_root');
		$data['entry_ftp_status'] = $this->language->get('entry_ftp_status');
		$data['entry_mail_protocol'] = $this->language->get('entry_mail_protocol');
		$data['entry_mail_parameter'] = $this->language->get('entry_mail_parameter');
		$data['entry_smtp_hostname'] = $this->language->get('entry_smtp_hostname');
		$data['entry_smtp_username'] = $this->language->get('entry_smtp_username');
		$data['entry_smtp_password'] = $this->language->get('entry_smtp_password');
		$data['entry_smtp_port'] = $this->language->get('entry_smtp_port');
		$data['entry_smtp_timeout'] = $this->language->get('entry_smtp_timeout');
		$data['entry_mail_alert'] = $this->language->get('entry_mail_alert');
		$data['entry_fraud_detection'] = $this->language->get('entry_fraud_detection');
		$data['entry_fraud_key'] = $this->language->get('entry_fraud_key');
		$data['entry_fraud_score'] = $this->language->get('entry_fraud_score');
		$data['entry_fraud_status'] = $this->language->get('entry_fraud_status');
		$data['entry_secure'] = $this->language->get('entry_secure');
		$data['entry_shared'] = $this->language->get('entry_shared');
		$data['entry_robots'] = $this->language->get('entry_robots');
		$data['entry_file_max_size'] = $this->language->get('entry_file_max_size');
		$data['entry_file_ext_allowed'] = $this->language->get('entry_file_ext_allowed');
		$data['entry_file_mime_allowed'] = $this->language->get('entry_file_mime_allowed');
		$data['entry_maintenance'] = $this->language->get('entry_maintenance');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_encryption'] = $this->language->get('entry_encryption');
		$data['entry_seo_url'] = $this->language->get('entry_seo_url');
		$data['entry_compression'] = $this->language->get('entry_compression');
		$data['entry_error_display'] = $this->language->get('entry_error_display');
		$data['entry_error_log'] = $this->language->get('entry_error_log');
		$data['entry_error_filename'] = $this->language->get('entry_error_filename');
		$data['entry_google_analytics'] = $this->language->get('entry_google_analytics');

		$data['help_geocode'] = $this->language->get('help_geocode');
		$data['help_open'] = $this->language->get('help_open');
		$data['help_comment'] = $this->language->get('help_comment');
		$data['help_location'] = $this->language->get('help_location');
		$data['help_currency'] = $this->language->get('help_currency');
		$data['help_currency_auto'] = $this->language->get('help_currency_auto');
		$data['help_product_limit'] = $this->language->get('help_product_limit');
		$data['help_product_description_length'] = $this->language->get('help_product_description_length');
		$data['help_limit_admin'] = $this->language->get('help_limit_admin');
		$data['help_product_count'] = $this->language->get('help_product_count');
		$data['help_review'] = $this->language->get('help_review');
		$data['help_review_guest'] = $this->language->get('help_review_guest');
		$data['help_review_mail'] = $this->language->get('help_review_mail');
		$data['help_voucher_min'] = $this->language->get('help_voucher_min');
		$data['help_voucher_max'] = $this->language->get('help_voucher_max');
		$data['help_tax_default'] = $this->language->get('help_tax_default');
		$data['help_tax_customer'] = $this->language->get('help_tax_customer');
		$data['help_customer_online'] = $this->language->get('help_customer_online');
		$data['help_customer_group'] = $this->language->get('help_customer_group');
		$data['help_customer_group_display'] = $this->language->get('help_customer_group_display');
		$data['help_customer_price'] = $this->language->get('help_customer_price');
		$data['help_login_attempts'] = $this->language->get('help_login_attempts');		
		$data['help_account'] = $this->language->get('help_account');
		$data['help_account_mail'] = $this->language->get('help_account_mail');
		$data['help_api'] = $this->language->get('help_api');
		$data['help_cart_weight'] = $this->language->get('help_cart_weight');
		$data['help_checkout_guest'] = $this->language->get('help_checkout_guest');
		$data['help_checkout'] = $this->language->get('help_checkout');
		$data['help_invoice_prefix'] = $this->language->get('help_invoice_prefix');
		$data['help_order_status'] = $this->language->get('help_order_status');
		$data['help_processing_status'] = $this->language->get('help_processing_status');
		$data['help_complete_status'] = $this->language->get('help_complete_status');
		$data['help_order_mail'] = $this->language->get('help_order_mail');
		$data['help_stock_display'] = $this->language->get('help_stock_display');
		$data['help_stock_warning'] = $this->language->get('help_stock_warning');
		$data['help_stock_checkout'] = $this->language->get('help_stock_checkout');
		$data['help_affiliate_approval'] = $this->language->get('help_affiliate_approval');
		$data['help_affiliate_auto'] = $this->language->get('help_affiliate_auto');
		$data['help_affiliate_commission'] = $this->language->get('help_affiliate_commission');
		$data['help_affiliate'] = $this->language->get('help_affiliate');
		$data['help_affiliate_mail'] = $this->language->get('help_affiliate_mail');
		$data['help_commission'] = $this->language->get('help_commission');
		$data['help_return'] = $this->language->get('help_return');
		$data['help_return_status'] = $this->language->get('help_return_status');
		$data['help_icon'] = $this->language->get('help_icon');
		$data['help_ftp_root'] = $this->language->get('help_ftp_root');
		$data['help_mail_protocol'] = $this->language->get('help_mail_protocol');
		$data['help_mail_parameter'] = $this->language->get('help_mail_parameter');
		$data['help_mail_smtp_hostname'] = $this->language->get('help_mail_smtp_hostname');
		$data['help_mail_alert'] = $this->language->get('help_mail_alert');
		$data['help_fraud_detection'] = $this->language->get('help_fraud_detection');
		$data['help_fraud_score'] = $this->language->get('help_fraud_score');
		$data['help_fraud_status'] = $this->language->get('help_fraud_status');
		$data['help_secure'] = $this->language->get('help_secure');
		$data['help_shared'] = $this->language->get('help_shared');
		$data['help_robots'] = $this->language->get('help_robots');
		$data['help_seo_url'] = $this->language->get('help_seo_url');
		$data['help_file_max_size'] = $this->language->get('help_file_max_size');
		$data['help_file_ext_allowed'] = $this->language->get('help_file_ext_allowed');
		$data['help_file_mime_allowed'] = $this->language->get('help_file_mime_allowed');
		$data['help_maintenance'] = $this->language->get('help_maintenance');
		$data['help_password'] = $this->language->get('help_password');
		$data['help_encryption'] = $this->language->get('help_encryption');
		$data['help_compression'] = $this->language->get('help_compression');
		$data['help_google_analytics'] = $this->language->get('help_google_analytics');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_store'] = $this->language->get('tab_store');
		$data['tab_local'] = $this->language->get('tab_local');
		$data['tab_option'] = $this->language->get('tab_option');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_ftp'] = $this->language->get('tab_ftp');
		$data['tab_mail'] = $this->language->get('tab_mail');
		$data['tab_fraud'] = $this->language->get('tab_fraud');
		$data['tab_server'] = $this->language->get('tab_server');

		$this->load->model('setting/store');

		$data["store_types"]=$this->model_setting_store->getstoretypes();
		//print_r($data["store_types"]);

		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_stores'),
			'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'View store details',
			'href' => $this->url->link('setting/setting/view&store_id='.$this->request->get['store_id'], 'token=' . $this->session->data['token'], 'SSL')
		);

                
		//$data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

                if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$this->load->model('setting/setting');

			$store_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
		}
                if (isset($this->request->post['config_url'])) {
			$data['config_url'] = $this->request->post['config_url'];
		}
                	elseif (isset($store_info['config_url'])) {
			$data['config_url'] = $store_info['config_url'];
		}
                	else {
			$data['config_url'] = $this->config->get('config_url');
		}
                if (isset($this->request->post['config_ssl'])) {
			$data['config_ssl'] = $this->request->post['config_ssl'];
		}
                	elseif (isset($store_info['config_ssl'])) {
			$data['config_ssl'] = $store_info['config_ssl'];
		}
                	else {
			$data['config_ssl'] = $this->config->get('config_ssl');
		}
		if (isset($this->request->post['config_tin'])) {
			$data['config_tin'] = $this->request->post['config_tin'];
		}
                	elseif (isset($store_info['config_tin'])) {
			$data['config_tin'] = $store_info['config_tin'];
		}
                	else {
			$data['config_tin'] = $this->config->get('config_tin');
		}

                           if (isset($this->request->post['config_cin'])) {
			$data['config_cin'] = $this->request->post['config_cin'];
		}
                	elseif (isset($store_info['config_cin'])) {
			$data['config_cin'] = $store_info['config_cin'];
		}
                	else {
			$data['config_cin'] = $this->config->get('config_cin');
		}

		if (isset($this->request->post['config_gstn'])) {
			$data['config_gstn'] = $this->request->post['config_gstn'];
		}
                	elseif (isset($store_info['config_gstn'])) {
			$data['config_gstn'] = $store_info['config_gstn'];
		}
                	else {
			$data['config_gstn'] = $this->config->get('config_gstn');
		}
                //////////new fields start here///////
                
                if (isset($this->request->post['config_MSMFID'])) {
			$data['config_MSMFID'] = $this->request->post['config_MSMFID'];
		}
                	elseif (isset($store_info['config_MSMFID'])) {
			$data['config_MSMFID'] = $store_info['config_MSMFID'];
		}
                	else {
			$data['config_MSMFID'] = $this->config->get('config_MSMFID');
		}
                if (isset($this->request->post['config_GST_doc'])) {
			$data['config_GST_doc'] = $this->request->post['config_GST_doc'];
		}
                	elseif (isset($store_info['config_GST_doc'])) {
			$data['config_GST_doc'] = $store_info['config_GST_doc'];
		}
                	else {
			$data['config_GST_doc'] = $this->config->get('config_GST_doc');
		}
                if (isset($this->request->post['config_Bank_Name'])) {
			$data['config_Bank_Name'] = $this->request->post['config_Bank_Name'];
		}
                	elseif (isset($store_info['config_Bank_Name'])) {
			$data['config_Bank_Name'] = $store_info['config_Bank_Name'];
		}
                	else {
			$data['config_Bank_Name'] = $this->config->get('config_Bank_Name');
		}
                if (isset($this->request->post['config_Account_Number'])) {
			$data['config_Account_Number'] = $this->request->post['config_Account_Number'];
		}
                	elseif (isset($store_info['config_Account_Number'])) {
			$data['config_Account_Number'] = $store_info['config_Account_Number'];
		}
                	else {
			$data['config_Account_Number'] = $this->config->get('config_Account_Number');
		}
                if (isset($this->request->post['config_IFSC_Code'])) {
			$data['config_IFSC_Code'] = $this->request->post['config_IFSC_Code'];
		}
                	elseif (isset($store_info['config_IFSC_Code'])) {
			$data['config_IFSC_Code'] = $store_info['config_IFSC_Code'];
		}
                	else {
			$data['config_IFSC_Code'] = $this->config->get('config_IFSC_Code');
		}
                if (isset($this->request->post['config_Account_Holder_name'])) {
			$data['config_Account_Holder_name'] = $this->request->post['config_Account_Holder_name'];
		}
                	elseif (isset($store_info['config_Account_Holder_name'])) {
			$data['config_Account_Holder_name'] = $store_info['config_Account_Holder_name'];
		}
                	else {
			$data['config_Account_Holder_name'] = $this->config->get('config_Account_Holder_name');
		}
                if (isset($this->request->post['config_Branch_Name'])) {
			$data['config_Branch_Name'] = $this->request->post['config_Branch_Name'];
		}
                	elseif (isset($store_info['config_Branch_Name'])) {
			$data['config_Branch_Name'] = $store_info['config_Branch_Name'];
		}
                	else {
			$data['config_Branch_Name'] = $this->config->get('config_Branch_Name');
		}
                if (isset($this->request->post['config_fertilizer_number'])) {
			$data['config_fertilizer_number'] = $this->request->post['config_fertilizer_number'];
		}
                	elseif (isset($store_info['config_fertilizer_number'])) {
			$data['config_fertilizer_number'] = $store_info['config_fertilizer_number'];
		}
                	else {
			$data['config_fertilizer_number'] = $this->config->get('config_fertilizer_number');
		}
                if (isset($this->request->post['config_fertilizer_from'])) {
			$data['config_fertilizer_from'] = $this->request->post['config_fertilizer_from'];
		}
                	elseif (isset($store_info['config_fertilizer_from'])) {
			$data['config_fertilizer_from'] = $store_info['config_fertilizer_from'];
		}
                	else {
			$data['config_fertilizer_from'] = $this->config->get('config_fertilizer_from');
		}
                if (isset($this->request->post['config_fertilizer_to'])) {
			$data['config_fertilizer_to'] = $this->request->post['config_fertilizer_to'];
		}
                	elseif (isset($store_info['config_fertilizer_to'])) {
			$data['config_fertilizer_to'] = $store_info['config_fertilizer_to'];
		}
                	else {
			$data['config_fertilizer_to'] = $this->config->get('config_fertilizer_to');
		}
                if (isset($this->request->post['config_fertilizer_file'])) {
			$data['config_fertilizer_file'] = $this->request->post['config_fertilizer_file'];
		}
                	elseif (isset($store_info['config_fertilizer_file'])) {
			$data['config_fertilizer_file'] = $store_info['config_fertilizer_file'];
		}
                	else {
			$data['config_fertilizer_file'] = $this->config->get('config_fertilizer_file');
		}
                if (isset($this->request->post['config_Pesticide_number'])) {
			$data['config_Pesticide_number'] = $this->request->post['config_Pesticide_number'];
		}
                	elseif (isset($store_info['config_Pesticide_number'])) {
			$data['config_Pesticide_number'] = $store_info['config_Pesticide_number'];
		}
                	else {
			$data['config_Pesticide_number'] = $this->config->get('config_Pesticide_number');
		}
                if (isset($this->request->post['config_Pesticide_from'])) {
			$data['config_Pesticide_from'] = $this->request->post['config_Pesticide_from'];
		}
                	elseif (isset($store_info['config_Pesticide_from'])) {
			$data['config_Pesticide_from'] = $store_info['config_Pesticide_from'];
		}
                	else {
			$data['config_Pesticide_from'] = $this->config->get('config_Pesticide_from');
		}
                if (isset($this->request->post['config_Pesticide_to'])) {
			$data['config_Pesticide_to'] = $this->request->post['config_Pesticide_to'];
		}
                	elseif (isset($store_info['config_Pesticide_to'])) {
			$data['config_Pesticide_to'] = $store_info['config_Pesticide_to'];
		}
                	else {
			$data['config_Pesticide_to'] = $this->config->get('config_Pesticide_to');
		}
                if (isset($this->request->post['config_Pesticide_file'])) {
			$data['config_Pesticide_file'] = $this->request->post['config_Pesticide_file'];
		}
                	elseif (isset($store_info['config_Pesticide_file'])) {
			$data['config_Pesticide_file'] = $store_info['config_Pesticide_file'];
		}
                	else {
			$data['config_Pesticide_file'] = $this->config->get('config_Pesticide_file');
		}
                if (isset($this->request->post['config_Seed_number'])) {
			$data['config_Seed_number'] = $this->request->post['config_Seed_number'];
		}
                	elseif (isset($store_info['config_Seed_number'])) {
			$data['config_Seed_number'] = $store_info['config_Seed_number'];
		}
                	else {
			$data['config_Seed_number'] = $this->config->get('config_Seed_number');
		}
                if (isset($this->request->post['config_Seed_from'])) {
			$data['config_Seed_from'] = $this->request->post['config_Seed_from'];
		}
                	elseif (isset($store_info['config_Seed_from'])) {
			$data['config_Seed_from'] = $store_info['config_Seed_from'];
		}
                	else {
			$data['config_Seed_from'] = $this->config->get('config_Seed_from');
		}
                if (isset($this->request->post['config_Seed_to'])) {
			$data['config_Seed_to'] = $this->request->post['config_Seed_to'];
		}
                	elseif (isset($store_info['config_Seed_to'])) {
			$data['config_Seed_to'] = $store_info['config_Seed_to'];
		}
                	else {
			$data['config_Seed_to'] = $this->config->get('config_Seed_to');
		}
                if (isset($this->request->post['config_Seed_file'])) {
			$data['config_Seed_file'] = $this->request->post['config_Seed_file'];
		}
                	elseif (isset($store_info['config_Seed_file'])) {
			$data['config_Seed_file'] = $store_info['config_Seed_file'];
		}
                	else {
			$data['config_Seed_file'] = $this->config->get('config_Seed_file');
		}
                /////////////////////
                if (isset($this->request->post['config_Agreement_Upload'])) {
			$data['config_Agreement_Upload'] = $this->request->post['config_Agreement_Upload'];
		}
                	elseif (isset($store_info['config_Agreement_Upload'])) {
			$data['config_Agreement_Upload'] = $store_info['config_Agreement_Upload'];
		}
                	else {
			$data['config_Agreement_Upload'] = $this->config->get('config_Agreement_Upload');
		}
                if (isset($this->request->post['config_Bank_signature'])) {
			$data['config_Bank_signature'] = $this->request->post['config_Bank_signature'];
		}
                	elseif (isset($store_info['config_Bank_signature'])) {
			$data['config_Bank_signature'] = $store_info['config_Bank_signature'];
		}
                	else {
			$data['config_Bank_signature'] = $this->config->get('config_Bank_signature');
		}
                if (isset($this->request->post['config_Partner_Agreement'])) {
			$data['config_Partner_Agreement'] = $this->request->post['config_Partner_Agreement'];
		}
                	elseif (isset($store_info['config_Partner_Agreement'])) {
			$data['config_Partner_Agreement'] = $store_info['config_Partner_Agreement'];
		}
                	else {
			$data['config_Partner_Agreement'] = $this->config->get('config_Partner_Agreement');
		}
                if (isset($this->request->post['config_Stamp_Paper_Agreement_1'])) {
			$data['config_Stamp_Paper_Agreement_1'] = $this->request->post['config_Stamp_Paper_Agreement_1'];
		}
                	elseif (isset($store_info['config_Stamp_Paper_Agreement_1'])) {
			$data['config_Stamp_Paper_Agreement_1'] = $store_info['config_Stamp_Paper_Agreement_1'];
		}
                	else {
			$data['config_Stamp_Paper_Agreement_1'] = $this->config->get('config_Stamp_Paper_Agreement_1');
		}
                if (isset($this->request->post['config_Stamp_Paper_Agreement_2'])) {
			$data['config_Stamp_Paper_Agreement_2'] = $this->request->post['config_Stamp_Paper_Agreement_2'];
		}
                	elseif (isset($store_info['config_Stamp_Paper_Agreement_2'])) {
			$data['config_Stamp_Paper_Agreement_2'] = $store_info['config_Stamp_Paper_Agreement_2'];
		}
                	else {
			$data['config_Stamp_Paper_Agreement_2'] = $this->config->get('config_Stamp_Paper_Agreement_2');
		}
                if (isset($this->request->post['config_Aadhar_ID_number'])) {
			$data['config_Aadhar_ID_number'] = $this->request->post['config_Aadhar_ID_number'];
		}
                	elseif (isset($store_info['config_Aadhar_ID_number'])) {
			$data['config_Aadhar_ID_number'] = $store_info['config_Aadhar_ID_number'];
		}
                	else {
			$data['config_Aadhar_ID_number'] = $this->config->get('config_Aadhar_ID_number');
		}
                if (isset($this->request->post['config_Aadhar_ID_file'])) {
			$data['config_Aadhar_ID_file'] = $this->request->post['config_Aadhar_ID_file'];
		}
                	elseif (isset($store_info['config_Aadhar_ID_file'])) {
			$data['config_Aadhar_ID_file'] = $store_info['config_Aadhar_ID_file'];
		}
                	else {
			$data['config_Aadhar_ID_file'] = $this->config->get('config_Aadhar_ID_file');
		}
                if (isset($this->request->post['config_PAN_ID_number'])) {
			$data['config_PAN_ID_number'] = $this->request->post['config_PAN_ID_number'];
		}
                	elseif (isset($store_info['config_PAN_ID_number'])) {
			$data['config_PAN_ID_number'] = $store_info['config_PAN_ID_number'];
		}
                	else {
			$data['config_PAN_ID_number'] = $this->config->get('config_PAN_ID_number');
		}
                if (isset($this->request->post['config_PAN_ID_file'])) {
			$data['config_PAN_ID_file'] = $this->request->post['config_PAN_ID_file'];
		}
                	elseif (isset($store_info['config_PAN_ID_file'])) {
			$data['config_PAN_ID_file'] = $store_info['config_PAN_ID_file'];
		}
                	else {
			$data['config_PAN_ID_file'] = $this->config->get('config_PAN_ID_file');
		}
                if (isset($this->request->post['config_Residence_Proof'])) {
			$data['config_Residence_Proof'] = $this->request->post['config_Residence_Proof'];
		}
                	elseif (isset($store_info['config_Residence_Proof'])) {
			$data['config_Residence_Proof'] = $store_info['config_Residence_Proof'];
		}
                	else {
			$data['config_Residence_Proof'] = $this->config->get('config_Residence_Proof');
		}
                if (isset($this->request->post['config_Bank_Statement'])) {
			$data['config_Bank_Statement'] = $this->request->post['config_Bank_Statement'];
		}
                	elseif (isset($store_info['config_Bank_Statement'])) {
			$data['config_Bank_Statement'] = $store_info['config_Bank_Statement'];
		}
                	else {
			$data['config_Bank_Statement'] = $this->config->get('config_Bank_Statement');
		}
                if (isset($this->request->post['config_Signed_Cheque'])) {
			$data['config_Signed_Cheque'] = $this->request->post['config_Signed_Cheque'];
		}
                	elseif (isset($store_info['config_Signed_Cheque'])) {
			$data['config_Signed_Cheque'] = $store_info['config_Signed_Cheque'];
		}
                	else {
			$data['config_Signed_Cheque'] = $this->config->get('config_Signed_Cheque');
		}
                if (isset($this->request->post['config_Cheque_issuance'])) {
			$data['config_Cheque_issuance'] = $this->request->post['config_Cheque_issuance'];
		}
                	elseif (isset($store_info['config_Cheque_issuance'])) {
			$data['config_Cheque_issuance'] = $store_info['config_Cheque_issuance'];
		}
                	else {
			$data['config_Cheque_issuance'] = $this->config->get('config_Cheque_issuance');
		}
                if (isset($this->request->post['config_Cheque_UFC'])) {
			$data['config_Cheque_UFC'] = $this->request->post['config_Cheque_UFC'];
		}
                	elseif (isset($store_info['config_Cheque_UFC'])) {
			$data['config_Cheque_UFC'] = $store_info['config_Cheque_UFC'];
		}
                	else {
			$data['config_Cheque_UFC'] = $this->config->get('config_Cheque_UFC');
		}
                if (isset($this->request->post['config_Cheque_Akshamaala'])) {
			$data['config_Cheque_Akshamaala'] = $this->request->post['config_Cheque_Akshamaala'];
		}
                	elseif (isset($store_info['config_Cheque_Akshamaala'])) {
			$data['config_Cheque_Akshamaala'] = $store_info['config_Cheque_Akshamaala'];
		}
                	else {
			$data['config_Cheque_Akshamaala'] = $this->config->get('config_Cheque_Akshamaala');
		}
                if (isset($this->request->post['config_Signature_verification'])) {
			$data['config_Signature_verification'] = $this->request->post['config_Signature_verification'];
		}
                	elseif (isset($store_info['config_Signature_verification'])) {
			$data['config_Signature_verification'] = $store_info['config_Signature_verification'];
		}
                	else {
			$data['config_Signature_verification'] = $this->config->get('config_Signature_verification');
		}
                //////////////new fields end here/////////////
		if (isset($this->request->post['config_storetype'])) {
			$data['config_storetype'] = $this->request->post['config_storetype'];
		}
                	elseif (isset($store_info['config_storetype'])) {
			$data['config_storetype'] = $store_info['config_storetype'];
		}
                	else {
			$data['config_storetype'] = $this->config->get('config_storetype');
		}
		if (isset($this->request->post['config_creditlimit'])) {
			$data['config_creditlimit'] = $this->request->post['config_creditlimit'];
		}
                	elseif (isset($store_info['config_creditlimit'])) {
			$data['config_creditlimit'] = $store_info['config_creditlimit'];
		}
                	else {
			$data['config_creditlimit'] = $this->config->get('config_creditlimit');
		}


		if (isset($this->request->post['config_name'])) {
			$data['config_name'] = $this->request->post['config_name'];
		}
                elseif (isset($store_info['config_name'])) {
			$data['config_name'] = $store_info['config_name'];
		}
                else {
			$data['config_name'] = $this->config->get('config_name');
		}

		if (isset($this->request->post['config_owner'])) {
			$data['config_owner'] = $this->request->post['config_owner'];
		}
                elseif (isset($store_info['config_owner'])) {
			$data['config_owner'] = $store_info['config_owner'];
		}
                else {
			$data['config_owner'] = $this->config->get('config_owner');
		}
		
		if (isset($this->request->post['config_address'])) {
			$data['config_address'] = $this->request->post['config_address'];
		}
                elseif (isset($store_info['config_address'])) {
			$data['config_address'] = $store_info['config_address'];
		}
                else {
			$data['config_address'] = $this->config->get('config_address');
		}
		if (isset($this->request->post['config_head_office'])) {
			$data['config_head_office'] = $this->request->post['config_head_office'];
		}
                elseif (isset($store_info['config_head_office'])) {
			$data['config_head_office'] = $store_info['config_head_office'];
		}
                else {
			$data['config_head_office'] = $this->config->get('config_head_office');
		}

		if (isset($this->request->post['config_geocode'])) {
			$data['config_geocode'] = $this->request->post['config_geocode'];
		}
                elseif (isset($store_info['config_geocode'])) {
			$data['config_geocode'] = $store_info['config_geocode'];
		}
                else {
			$data['config_geocode'] = $this->config->get('config_geocode');
		}

		if (isset($this->request->post['config_email'])) {
			$data['config_email'] = $this->request->post['config_email'];
		}
                elseif (isset($store_info['config_email'])) {
			$data['config_email'] = $store_info['config_email'];
		}
                else {
			$data['config_email'] = $this->config->get('config_email');
		}

		if (isset($this->request->post['config_telephone'])) {
			$data['config_telephone'] = $this->request->post['config_telephone'];
		}
                elseif (isset($store_info['config_telephone'])) {
			$data['config_telephone'] = $store_info['config_telephone'];
		}
                else {
			$data['config_telephone'] = $this->config->get('config_telephone');
		}

		if (isset($this->request->post['config_fax'])) {
			$data['config_fax'] = $this->request->post['config_fax'];
		}
                elseif (isset($store_info['config_fax'])) {
			$data['config_fax'] = $store_info['config_fax'];
		}
                else {
			$data['config_fax'] = $this->config->get('config_fax');
		}

		if (isset($this->request->post['config_image'])) {
			$data['config_image'] = $this->request->post['config_image'];
		}
                elseif (isset($store_info['config_image'])) {
			$data['config_image'] = $store_info['config_image'];
		}
                else {
			$data['config_image'] = $this->config->get('config_image');
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
		}
                elseif (isset($store_info['config_image']) && is_file(DIR_IMAGE . $store_info['config_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($store_info['config_image'], 100, 100);
		} 
                elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['config_open'])) {
			$data['config_open'] = $this->request->post['config_open'];
		}
                elseif (isset($store_info['config_open'])) {
			$data['config_open'] = $store_info['config_open'];
		}
                else {
			$data['config_open'] = $this->config->get('config_open');
		}

		if (isset($this->request->post['config_comment'])) {
			$data['config_comment'] = $this->request->post['config_comment'];
		}
                elseif (isset($store_info['config_comment'])) {
			$data['config_comment'] = $store_info['config_comment'];
		}
                else {
			$data['config_comment'] = $this->config->get('config_comment');
		}

		$this->load->model('localisation/location');

		$data['locations'] = $this->model_localisation_location->getLocations();

		if (isset($this->request->post['config_location'])) {
			$data['config_location'] = $this->request->post['config_location'];
		} elseif ($this->config->get('config_location')) {
			$data['config_location'] = $this->config->get('config_location');
		} else {
			$data['config_location'] = array();
		}

		if (isset($this->request->post['config_meta_title'])) {
			$data['config_meta_title'] = $this->request->post['config_meta_title'];
		}
                elseif (isset($store_info['config_meta_title'])) {
			$data['config_meta_title'] = $store_info['config_meta_title'];
		}
                else {
			$data['config_meta_title'] = $this->config->get('config_meta_title');
		}

		if (isset($this->request->post['config_meta_description'])) {
			$data['config_meta_description'] = $this->request->post['config_meta_description'];
		}
                elseif (isset($store_info['config_meta_description'])) {
			$data['config_meta_description'] = $store_info['config_meta_description'];
		}
                else {
			$data['config_meta_description'] = $this->config->get('config_meta_description');
		}

		if (isset($this->request->post['config_meta_keyword'])) {
			$data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
		}
                elseif (isset($store_info['config_meta_keyword'])) {
			$data['config_meta_keyword'] = $store_info['config_meta_keyword'];
		}
                else {
			$data['config_meta_keyword'] = $this->config->get('config_meta_keyword');
		}

		if (isset($this->request->post['config_layout_id'])) {
			$data['config_layout_id'] = $this->request->post['config_layout_id'];
		}
                elseif (isset($store_info['config_layout_id'])) {
			$data['config_layout_id'] = $store_info['config_layout_id'];
		}
                else {
			$data['config_layout_id'] = $this->config->get('config_layout_id');
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->post['config_template'])) {
			$data['config_template'] = $this->request->post['config_template'];
		}
                elseif (isset($store_info['config_template'])) {
			$data['config_template'] = $store_info['config_template'];
		}
                else {
			$data['config_template'] = $this->config->get('config_template');
		}

		$data['templates'] = array();

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$data['templates'][] = basename($directory);
		}

		if (isset($this->request->post['config_country_id'])) {
			$data['config_country_id'] = $this->request->post['config_country_id'];
		}
                elseif (isset($store_info['config_country_id'])) {
			$data['config_country_id'] = $store_info['config_country_id'];
		}
                else {
			$data['config_country_id'] = $this->config->get('config_country_id');
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['config_zone_id'])) {
			$data['config_zone_id'] = $this->request->post['config_zone_id'];
		}
                elseif (isset($store_info['config_zone_id'])) {
			$data['config_zone_id'] = $store_info['config_zone_id'];
		}
                else {
			$data['config_zone_id'] = $this->config->get('config_zone_id');
		}

		if (isset($this->request->post['config_language'])) {
			$data['config_language'] = $this->request->post['config_language'];
		}
                elseif (isset($store_info['config_language'])) {
			$data['config_language'] = $store_info['config_language'];
		}
                else {
			$data['config_language'] = $this->config->get('config_language');
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['config_admin_language'])) {
			$data['config_admin_language'] = $this->request->post['config_admin_language'];
		} else {
			$data['config_admin_language'] = $this->config->get('config_admin_language');
		}

		if (isset($this->request->post['config_currency'])) {
			$data['config_currency'] = $this->request->post['config_currency'];
		}
                elseif (isset($store_info['config_currency'])) {
			$data['config_currency'] = $store_info['config_currency'];
		}
                else {
			$data['config_currency'] = $this->config->get('config_currency');
		}

		if (isset($this->request->post['config_currency_auto'])) {
			$data['config_currency_auto'] = $this->request->post['config_currency_auto'];
		} else {
			$data['config_currency_auto'] = $this->config->get('config_currency_auto');
		}

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($this->request->post['config_length_class_id'])) {
			$data['config_length_class_id'] = $this->request->post['config_length_class_id'];
		}
                elseif (isset($store_info['config_length_class_id'])) {
			$data['config_length_class_id'] = $store_info['config_length_class_id'];
		}
                else {
			$data['config_length_class_id'] = $this->config->get('config_length_class_id');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['config_weight_class_id'])) {
			$data['config_weight_class_id'] = $this->request->post['config_weight_class_id'];
		}
                elseif (isset($store_info['config_weight_class_id'])) {
			$data['config_weight_class_id'] = $store_info['config_weight_class_id'];
		}
                else {
			$data['config_weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['config_product_limit'])) {
			$data['config_product_limit'] = $this->request->post['config_product_limit'];
		}
                elseif (isset($store_info['config_product_limit'])) {
			$data['config_product_limit'] = $store_info['config_product_limit'];
		}
                else {
			$data['config_product_limit'] = $this->config->get('config_product_limit');
		}

		if (isset($this->request->post['config_product_description_length'])) {
			$data['config_product_description_length'] = $this->request->post['config_product_description_length'];
		}
                elseif (isset($store_info['config_product_description_length'])) {
			$data['config_product_description_length'] = $store_info['config_product_description_length'];
		}
                else {
			$data['config_product_description_length'] = $this->config->get('config_product_description_length');
		}

		if (isset($this->request->post['config_limit_admin'])) {
			$data['config_limit_admin'] = $this->request->post['config_limit_admin'];
		}
                elseif (isset($store_info['config_limit_admin'])) {
			$data['config_limit_admin'] = $store_info['config_limit_admin'];
		}
                else {
			$data['config_limit_admin'] = $this->config->get('config_limit_admin');
		}

		if (isset($this->request->post['config_product_count'])) {
			$data['config_product_count'] = $this->request->post['config_product_count'];
		}
                elseif (isset($store_info['config_product_count'])) {
			$data['config_product_count'] = $store_info['config_product_count'];
		}
                else {
			$data['config_product_count'] = $this->config->get('config_product_count');
		}

		if (isset($this->request->post['config_review_status'])) {
			$data['config_review_status'] = $this->request->post['config_review_status'];
		}
                elseif (isset($store_info['config_review_status'])) {
			$data['config_review_status'] = $store_info['config_review_status'];
		}
                else {
			$data['config_review_status'] = $this->config->get('config_review_status');
		}

		if (isset($this->request->post['config_review_guest'])) {
			$data['config_review_guest'] = $this->request->post['config_review_guest'];
		}
                elseif (isset($store_info['config_review_guest'])) {
			$data['config_review_guest'] = $store_info['config_review_guest'];
		}
                else {
			$data['config_review_guest'] = $this->config->get('config_review_guest');
		}

		if (isset($this->request->post['config_review_mail'])) {
			$data['config_review_mail'] = $this->request->post['config_review_mail'];
		}
                elseif (isset($store_info['config_review_mail'])) {
			$data['config_review_mail'] = $store_info['config_review_mail'];
		}
                else {
			$data['config_review_mail'] = $this->config->get('config_review_mail');
		}

		if (isset($this->request->post['config_voucher_min'])) {
			$data['config_voucher_min'] = $this->request->post['config_voucher_min'];
		} 
                elseif (isset($store_info['config_voucher_min'])) {
			$data['config_voucher_min'] = $store_info['config_voucher_min'];
		}
                else {
			$data['config_voucher_min'] = $this->config->get('config_voucher_min');
		}

		if (isset($this->request->post['config_voucher_max'])) {
			$data['config_voucher_max'] = $this->request->post['config_voucher_max'];
		} elseif (isset($store_info['config_voucher_max'])) {
			$data['config_voucher_max'] = $store_info['config_voucher_max'];
		}else {
			$data['config_voucher_max'] = $this->config->get('config_voucher_max');
		}


                if (isset($this->request->post['config_tax_included'])) {
			$data['config_tax_included'] = $this->request->post['config_tax_included'];
		}
                elseif (isset($store_info['config_tax_included'])) {
			$data['config_tax_included'] = $store_info['config_tax_included'];
		}
                else {
			$data['config_tax_included'] = $this->config->get('config_tax_included');
		}
		
		if (isset($this->request->post['config_tax_included_store_based'])) {
			$data['config_tax_included_store_based'] = $this->request->post['config_tax_included_store_based'];
		}
                elseif (isset($store_info['config_tax_included_store_based'])) {
			$data['config_tax_included_store_based'] = $store_info['config_tax_included_store_based'];
		}
                else {
			$data['config_tax_included_store_based'] = $this->config->get('config_tax_included_store_based');
		}
		
		if (isset($this->request->post['config_tax_included_country_id'])) {
			$data['config_tax_included_country_id'] = $this->request->post['config_tax_included_country_id'];
		}
                elseif (isset($store_info['config_tax_included_country_id'])) {
			$data['config_tax_included_country_id'] = $store_info['config_tax_included_country_id'];
		}
                else {
			$data['config_tax_included_country_id'] = $this->config->get('config_tax_included_country_id');
		}
		
		if (isset($this->request->post['config_tax_included_zone_id'])) {
			$data['config_tax_included_zone_id'] = $this->request->post['config_tax_included_zone_id'];
		}
                elseif (isset($store_info['config_tax_included_zone_id'])) {
			$data['config_tax_included_zone_id'] = $store_info['config_tax_included_zone_id'];
		}
                else {
			$data['config_tax_included_zone_id'] = $this->config->get('config_tax_included_zone_id');
		}
//new
		if (isset($this->request->post['config_tax'])) {
			$data['config_tax'] = $this->request->post['config_tax'];
		}
                elseif (isset($store_info['config_tax'])) {
			$data['config_tax'] = $store_info['config_tax'];
		}
                else {
			$data['config_tax'] = $this->config->get('config_tax');
		}

		if (isset($this->request->post['config_tax_default'])) {
			$data['config_tax_default'] = $this->request->post['config_tax_default'];
		}
                elseif (isset($store_info['config_tax_default'])) {
			$data['config_tax_default'] = $store_info['config_tax_default'];
		}
                else {
			$data['config_tax_default'] = $this->config->get('config_tax_default');
		}

		if (isset($this->request->post['config_tax_customer'])) {
			$data['config_tax_customer'] = $this->request->post['config_tax_customer'];
		}
                elseif (isset($store_info['config_tax_default'])) {
			$data['config_tax_customer'] = $store_info['config_tax_customer'];
		}
                else {
			$data['config_tax_customer'] = $this->config->get('config_tax_customer');
		}

		if (isset($this->request->post['config_customer_online'])) {
			$data['config_customer_online'] = $this->request->post['config_customer_online'];
		}
                elseif (isset($store_info['config_customer_online'])) {
			$data['config_customer_online'] = $store_info['config_customer_online'];
		}
                else {
			$data['config_customer_online'] = $this->config->get('config_customer_online');
		}

		if (isset($this->request->post['config_customer_group_id'])) {
			$data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
		}
                elseif (isset($store_info['config_customer_group_id'])) {
			$data['config_customer_group_id'] = $store_info['config_customer_group_id'];
		}
                else {
			$data['config_customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['config_customer_group_display'])) {
			$data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
		}
                elseif (isset($store_info['config_customer_group_display'])) {
			$data['config_customer_group_display'] = $store_info['config_customer_group_display'];
		}
                elseif ($this->config->get('config_customer_group_display')) {
			$data['config_customer_group_display'] = $this->config->get('config_customer_group_display');
		} else {
			$data['config_customer_group_display'] = array();
		}

		if (isset($this->request->post['config_customer_price'])) {
			$data['config_customer_price'] = $this->request->post['config_customer_price'];
		}
                elseif (isset($store_info['config_customer_price'])) {
			$data['config_customer_price'] = $store_info['config_customer_price'];
		}
                else {
			$data['config_customer_price'] = $this->config->get('config_customer_price');
		}
		
		if (isset($this->request->post['config_login_attempts'])) {
			$data['config_login_attempts'] = $this->request->post['config_login_attempts'];
		}
                
                elseif ($this->config->has('config_login_attempts')) {
			$data['config_login_attempts'] = $this->config->get('config_login_attempts');
		}
                elseif (isset($store_info['config_login_attempts'])) {
			$data['config_login_attempts'] = $store_info['config_login_attempts'];
		}
                else {
			$data['config_login_attempts'] = 5;
		}
		
		if (isset($this->request->post['config_account_id'])) {
			$data['config_account_id'] = $this->request->post['config_account_id'];
		}
                elseif (isset($store_info['config_account_id'])) {
			$data['config_account_id'] = $store_info['config_account_id'];
		}
                else {
			$data['config_account_id'] = $this->config->get('config_account_id');
		}

		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($this->request->post['config_account_mail'])) {
			$data['config_account_mail'] = $this->request->post['config_account_mail'];
		}
                elseif (isset($store_info['config_account_mail'])) {
			$data['config_account_mail'] = $store_info['config_account_mail'];
		}
                else {
			$data['config_account_mail'] = $this->config->get('config_account_mail');
		}

		if (isset($this->request->post['config_api_id'])) {
			$data['config_api_id'] = $this->request->post['config_api_id'];
		} 
                elseif (isset($store_info['config_api_id'])) {
			$data['config_api_id'] = $store_info['config_api_id'];
		}
                else {
			$data['config_api_id'] = $this->config->get('config_api_id');
		}

		$this->load->model('user/api');

		$data['apis'] = $this->model_user_api->getApis();

		if (isset($this->request->post['config_cart_weight'])) {
			$data['config_cart_weight'] = $this->request->post['config_cart_weight'];
		}
                elseif (isset($store_info['config_cart_weight'])) {
			$data['config_cart_weight'] = $store_info['config_cart_weight'];
		}
                else {
			$data['config_cart_weight'] = $this->config->get('config_cart_weight');
		}

		if (isset($this->request->post['config_checkout_guest'])) {
			$data['config_checkout_guest'] = $this->request->post['config_checkout_guest'];
		}
                elseif (isset($store_info['config_checkout_guest'])) {
			$data['config_checkout_guest'] = $store_info['config_checkout_guest'];
		}
                else {
			$data['config_checkout_guest'] = $this->config->get('config_checkout_guest');
		}

		if (isset($this->request->post['config_checkout_id'])) {
			$data['config_checkout_id'] = $this->request->post['config_checkout_id'];
		}
                elseif (isset($store_info['config_checkout_id'])) {
			$data['config_checkout_id'] = $store_info['config_checkout_id'];
		}
                else {
			$data['config_checkout_id'] = $this->config->get('config_checkout_id');
		}

		if (isset($this->request->post['config_invoice_prefix'])) {
			$data['config_invoice_prefix'] = $this->request->post['config_invoice_prefix'];
		}
                elseif (isset($store_info['config_invoice_prefix'])) {
			$data['config_invoice_prefix'] = $store_info['config_invoice_prefix'];
		}
                elseif ($this->config->get('config_invoice_prefix')) {
			$data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');
		} else {
			$data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
		}

		if (isset($this->request->post['config_order_status_id'])) {
			$data['config_order_status_id'] = $this->request->post['config_order_status_id'];
		}
                elseif (isset($store_info['config_order_status_id'])) {
			$data['config_order_status_id'] = $store_info['config_order_status_id'];
		}
                else {
			$data['config_order_status_id'] = $this->config->get('config_order_status_id');
		}

		if (isset($this->request->post['config_processing_status'])) {
			$data['config_processing_status'] = $this->request->post['config_processing_status'];
		}
                elseif (isset($store_info['config_processing_status'])) {
			$data['config_processing_status'] = $store_info['config_processing_status'];
		}
                elseif ($this->config->get('config_processing_status')) {
			$data['config_processing_status'] = $this->config->get('config_processing_status');
		} else {
			$data['config_processing_status'] = array();
		}

		if (isset($this->request->post['config_complete_status'])) {
			$data['config_complete_status'] = $this->request->post['config_complete_status'];
		}
                elseif (isset($store_info['config_complete_status'])) {
			$data['config_complete_status'] = $store_info['config_complete_status'];
		}
                elseif ($this->config->get('config_complete_status')) {
			$data['config_complete_status'] = $this->config->get('config_complete_status');
		} else {
			$data['config_complete_status'] = array();
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['config_order_mail'])) {
			$data['config_order_mail'] = $this->request->post['config_order_mail'];
		}
                elseif (isset($store_info['config_order_mail'])) {
			$data['config_order_mail'] = $store_info['config_order_mail'];
		}
                else {
			$data['config_order_mail'] = $this->config->get('config_order_mail');
		}

		if (isset($this->request->post['config_stock_display'])) {
			$data['config_stock_display'] = $this->request->post['config_stock_display'];
		}
                elseif (isset($store_info['config_stock_display'])) {
			$data['config_stock_display'] = $store_info['config_stock_display'];
		}
                else {
			$data['config_stock_display'] = $this->config->get('config_stock_display');
		}

		if (isset($this->request->post['config_stock_warning'])) {
			$data['config_stock_warning'] = $this->request->post['config_stock_warning'];
		}
                elseif (isset($store_info['config_stock_warning'])) {
			$data['config_stock_warning'] = $store_info['config_stock_warning'];
		}
                else {
			$data['config_stock_warning'] = $this->config->get('config_stock_warning');
		}

		if (isset($this->request->post['config_stock_checkout'])) {
			$data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
		}
                elseif (isset($store_info['config_stock_checkout'])) {
			$data['config_stock_checkout'] = $store_info['config_stock_checkout'];
		}
                else {
			$data['config_stock_checkout'] = $this->config->get('config_stock_checkout');
		}

		if (isset($this->request->post['config_affiliate_auto'])) {
			$data['config_affiliate_approval'] = $this->request->post['config_affiliate_approval'];
		}
                elseif (isset($store_info['config_affiliate_commission'])) {
			$data['config_affiliate_commission'] = $store_info['config_affiliate_commission'];
		}
                elseif ($this->config->has('config_affiliate_commission')) {
			$data['config_affiliate_approval'] = $this->config->get('config_affiliate_approval');
		} else {
			$data['config_affiliate_approval'] = '';
		}

		if (isset($this->request->post['config_affiliate_auto'])) {
			$data['config_affiliate_auto'] = $this->request->post['config_affiliate_auto'];
		}
                elseif (isset($store_info['config_affiliate_auto'])) {
			$data['config_affiliate_auto'] = $store_info['config_affiliate_auto'];
		}
                elseif ($this->config->has('config_affiliate_auto')) {
			$data['config_affiliate_auto'] = $this->config->get('config_affiliate_auto');
		} else {
			$data['config_affiliate_auto'] = '';
		}

		if (isset($this->request->post['config_affiliate_commission'])) {
			$data['config_affiliate_commission'] = $this->request->post['config_affiliate_commission'];
		}
                elseif (isset($store_info['config_affiliate_commission'])) {
			$data['config_affiliate_commission'] = $store_info['config_affiliate_commission'];
		}
                elseif ($this->config->has('config_affiliate_commission')) {
			$data['config_affiliate_commission'] = $this->config->get('config_affiliate_commission');
		} else {
			$data['config_affiliate_commission'] = '5.00';
		}

		if (isset($this->request->post['config_affiliate_mail'])) {
			$data['config_affiliate_mail'] = $this->request->post['config_affiliate_mail'];
		}
                elseif (isset($store_info['config_affiliate_mail'])) {
			$data['config_affiliate_mail'] = $store_info['config_affiliate_mail'];
		}
                elseif ($this->config->has('config_affiliate_mail')) {
			$data['config_affiliate_mail'] = $this->config->get('config_affiliate_mail');
		} else {
			$data['config_affiliate_mail'] = '';
		}

		if (isset($this->request->post['config_affiliate_id'])) {
			$data['config_affiliate_id'] = $this->request->post['config_affiliate_id'];
		}
                elseif (isset($store_info['config_affiliate_id'])) {
			$data['config_affiliate_id'] = $store_info['config_affiliate_id'];
		}
                else {
			$data['config_affiliate_id'] = $this->config->get('config_affiliate_id');
		}

		if (isset($this->request->post['config_return_id'])) {
			$data['config_return_id'] = $this->request->post['config_return_id'];
		}
                elseif (isset($store_info['config_return_id'])) {
			$data['config_return_id'] = $store_info['config_return_id'];
		}
                else {
			$data['config_return_id'] = $this->config->get('config_return_id');
		}

		if (isset($this->request->post['config_return_status_id'])) {
			$data['config_return_status_id'] = $this->request->post['config_return_status_id'];
		}
                elseif (isset($store_info['config_return_status_id'])) {
			$data['config_return_status_id'] = $store_info['config_return_status_id'];
		}
                else {
			$data['config_return_status_id'] = $this->config->get('config_return_status_id');
		}

		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		if (isset($this->request->post['config_logo'])) {
			$data['config_logo'] = $this->request->post['config_logo'];
		}
                elseif (isset($store_info['config_logo'])) {
			$data['config_logo'] = $store_info['config_logo'];
		}
                else {
			$data['config_logo'] = $this->config->get('config_logo');
		}

		if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
		}
                elseif (isset($store_info['config_logo'])) {
			$data['config_logo'] = $this->model_tool_image->resize($store_info['config_logo'], 100, 100);
		}
                elseif ($this->config->get('config_logo') && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
		} else {
			$data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['config_icon'])) {
			$data['config_icon'] = $this->request->post['config_icon'];
		}
                elseif (isset($store_info['config_icon'])) {
			$data['config_icon'] = $store_info['config_icon'];
		}
                else {
			$data['config_icon'] = $this->config->get('config_icon');
		}

		if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
			$data['icon'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
		}
                elseif (isset($store_info['config_icon'])) {
			$data['icon'] = $this->model_tool_image->resize($this->request->post['config_icon'], 100, 100);
		}
                elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
		} else {
			$data['icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['config_image_category_width'])) {
			$data['config_image_category_width'] = $this->request->post['config_image_category_width'];
		}
                elseif (isset($store_info['config_image_category_width'])) {
			$data['config_image_category_width'] = $store_info['config_image_category_width'];
		}
                else {
			$data['config_image_category_width'] = $this->config->get('config_image_category_width');
		}

		if (isset($this->request->post['config_image_category_height'])) {
			$data['config_image_category_height'] = $this->request->post['config_image_category_height'];
		}
                elseif (isset($store_info['config_image_category_height'])) {
			$data['config_image_category_height'] = $store_info['config_image_category_height'];
		}
                else {
			$data['config_image_category_height'] = $this->config->get('config_image_category_height');
		}

		if (isset($this->request->post['config_image_thumb_width'])) {
			$data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
		}
                elseif (isset($store_info['config_image_thumb_width'])) {
			$data['config_image_thumb_width'] = $store_info['config_image_thumb_width'];
		}
                else {
			$data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
		}

		if (isset($this->request->post['config_image_thumb_height'])) {
			$data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
		}
                elseif (isset($store_info['config_image_thumb_height'])) {
			$data['config_image_thumb_height'] = $store_info['config_image_thumb_height'];
		}
                else {
			$data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
		}

		if (isset($this->request->post['config_image_popup_width'])) {
			$data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
		}
                elseif (isset($store_info['config_image_popup_width'])) {
			$data['config_image_popup_width'] = $store_info['config_image_popup_width'];
		}
                else {
			$data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
		}

		if (isset($this->request->post['config_image_popup_height'])) {
			$data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
		}
                elseif (isset($store_info['config_image_popup_height'])) {
			$data['config_image_popup_height'] = $store_info['config_image_popup_height'];
		}
                else {
			$data['config_image_popup_height'] = $this->config->get('config_image_popup_height');
		}

		if (isset($this->request->post['config_image_product_width'])) {
			$data['config_image_product_width'] = $this->request->post['config_image_product_width'];
		}
                elseif (isset($store_info['config_image_product_width'])) {
			$data['config_image_product_width'] = $store_info['config_image_product_width'];
		}
                else {
			$data['config_image_product_width'] = $this->config->get('config_image_product_width');
		}

		if (isset($this->request->post['config_image_product_height'])) {
			$data['config_image_product_height'] = $this->request->post['config_image_product_height'];
		}
                elseif (isset($store_info['config_image_product_height'])) {
			$data['config_image_product_height'] = $store_info['config_image_product_height'];
		}
                else {
			$data['config_image_product_height'] = $this->config->get('config_image_product_height');
		}

		if (isset($this->request->post['config_image_additional_width'])) {
			$data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
		}
                elseif (isset($store_info['config_image_additional_width'])) {
			$data['config_image_additional_width'] = $store_info['config_image_additional_width'];
		}
                else {
			$data['config_image_additional_width'] = $this->config->get('config_image_additional_width');
		}

		if (isset($this->request->post['config_image_additional_height'])) {
			$data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
		}
                elseif (isset($store_info['config_image_additional_height'])) {
			$data['config_image_additional_height'] = $store_info['config_image_additional_height'];
		}
                else {
			$data['config_image_additional_height'] = $this->config->get('config_image_additional_height');
		}

		if (isset($this->request->post['config_image_related_width'])) {
			$data['config_image_related_width'] = $this->request->post['config_image_related_width'];
		}
                elseif (isset($store_info['config_image_related_width'])) {
			$data['config_image_related_width'] = $store_info['config_image_related_width'];
		}
                else {
			$data['config_image_related_width'] = $this->config->get('config_image_related_width');
		}

		if (isset($this->request->post['config_image_related_height'])) {
			$data['config_image_related_height'] = $this->request->post['config_image_related_height'];
		}
                elseif (isset($store_info['config_image_related_height'])) {
			$data['config_image_related_height'] = $store_info['config_image_related_height'];
		}
                else {
			$data['config_image_related_height'] = $this->config->get('config_image_related_height');
		}

		if (isset($this->request->post['config_image_compare_width'])) {
			$data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
		}
                elseif (isset($store_info['config_image_compare_width'])) {
			$data['config_image_compare_width'] = $store_info['config_image_compare_width'];
		}
                else {
			$data['config_image_compare_width'] = $this->config->get('config_image_compare_width');
		}

		if (isset($this->request->post['config_image_compare_height'])) {
			$data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
		}
                elseif (isset($store_info['config_image_compare_height'])) {
			$data['config_image_compare_height'] = $store_info['config_image_compare_height'];
		}
                else {
			$data['config_image_compare_height'] = $this->config->get('config_image_compare_height');
		}

		if (isset($this->request->post['config_image_wishlist_width'])) {
			$data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
		}
                elseif (isset($store_info['config_image_wishlist_width'])) {
			$data['config_image_wishlist_width'] = $store_info['config_image_wishlist_width'];
		}
                else {
			$data['config_image_wishlist_width'] = $this->config->get('config_image_wishlist_width');
		}

		if (isset($this->request->post['config_image_wishlist_height'])) {
			$data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
		}
                elseif (isset($store_info['config_image_wishlist_height'])) {
			$data['config_image_wishlist_height'] = $store_info['config_image_wishlist_height'];
		}
                else {
			$data['config_image_wishlist_height'] = $this->config->get('config_image_wishlist_height');
		}

		if (isset($this->request->post['config_image_cart_width'])) {
			$data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
		}
                elseif (isset($store_info['config_image_cart_width'])) {
			$data['config_image_cart_width'] = $store_info['config_image_cart_width'];
		}
                else {
			$data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
		}

		if (isset($this->request->post['config_image_cart_height'])) {
			$data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
		}
                elseif (isset($store_info['config_image_cart_height'])) {
			$data['config_image_cart_height'] = $store_info['config_image_cart_height'];
		}
                else {
			$data['config_image_cart_height'] = $this->config->get('config_image_cart_height');
		}

                
		if (isset($this->request->post['config_image_location_width'])) {
			$data['config_image_location_width'] = $this->request->post['config_image_location_width'];
		}
                elseif (isset($store_info['config_image_location_width'])) {
			$data['config_image_location_width'] = $store_info['config_image_location_width'];
		}
                else {
			$data['config_image_location_width'] = $this->config->get('config_image_location_width');
		}

		if (isset($this->request->post['config_image_location_height'])) {
			$data['config_image_location_height'] = $this->request->post['config_image_location_height'];
		}
                elseif (isset($store_info['config_image_location_height'])) {
			$data['config_image_location_height'] = $store_info['config_image_location_height'];
		}
                else {
			$data['config_image_location_height'] = $this->config->get('config_image_location_height');
		}

		if (isset($this->request->post['config_ftp_hostname'])) {
			$data['config_ftp_hostname'] = $this->request->post['config_ftp_hostname'];
		}
                elseif (isset($store_info['config_ftp_hostname'])) {
			$data['config_ftp_hostname'] = $store_info['config_ftp_hostname'];
		}
                elseif ($this->config->get('config_ftp_hostname')) {
			$data['config_ftp_hostname'] = $this->config->get('config_ftp_hostname');
		} else {
			$data['config_ftp_hostname'] = str_replace('www.', '', $this->request->server['HTTP_HOST']);
		}

		if (isset($this->request->post['config_ftp_port'])) {
			$data['config_ftp_port'] = $this->request->post['config_ftp_port'];
		}
                elseif (isset($store_info['config_ftp_port'])) {
			$data['config_ftp_port'] = $store_info['config_ftp_port'];
		}
                elseif ($this->config->get('config_ftp_port')) {
			$data['config_ftp_port'] = $this->config->get('config_ftp_port');
		} else {
			$data['config_ftp_port'] = 21;
		}

		if (isset($this->request->post['config_ftp_username'])) {
			$data['config_ftp_username'] = $this->request->post['config_ftp_username'];
		}
                elseif (isset($store_info['config_ftp_username'])) {
			$data['config_ftp_username'] = $store_info['config_ftp_username'];
		}
                else {
			$data['config_ftp_username'] = $this->config->get('config_ftp_username');
		}

		if (isset($this->request->post['config_ftp_password'])) {
			$data['config_ftp_password'] = $this->request->post['config_ftp_password'];
		}
                elseif (isset($store_info['config_ftp_password'])) {
			$data['config_ftp_password'] = $store_info['config_ftp_password'];
		}
                else {
			$data['config_ftp_password'] = $this->config->get('config_ftp_password');
		}

		if (isset($this->request->post['config_ftp_root'])) {
			$data['config_ftp_root'] = $this->request->post['config_ftp_root'];
		}
                elseif (isset($store_info['config_ftp_root'])) {
			$data['config_ftp_root'] = $store_info['config_ftp_root'];
		}
                else {
			$data['config_ftp_root'] = $this->config->get('config_ftp_root');
		}

		if (isset($this->request->post['config_ftp_status'])) {
			$data['config_ftp_status'] = $this->request->post['config_ftp_status'];
		}
                elseif (isset($store_info['config_ftp_status'])) {
			$data['config_ftp_status'] = $store_info['config_ftp_status'];
		}
                else {
			$data['config_ftp_status'] = $this->config->get('config_ftp_status');
		}

		if (isset($this->request->post['config_mail'])) {
			$config_mail = $this->request->post['config_mail'];

			$data['config_mail_protocol'] = $config_mail['protocol'];
			$data['config_mail_parameter'] = $config_mail['parameter'];
			$data['config_smtp_hostname'] = $config_mail['smtp_hostname'];
			$data['config_smtp_username'] = $config_mail['smtp_username'];
			$data['config_smtp_password'] = $config_mail['smtp_password'];
			$data['config_smtp_port'] = $config_mail['smtp_port'];
			$data['config_smtp_timeout'] = $config_mail['smtp_timeout'];
		}
                
                elseif ($this->config->get('config_mail')) {
			$config_mail = $this->config->get('config_mail');

			$data['config_mail_protocol'] = $config_mail['protocol'];
			$data['config_mail_parameter'] = $config_mail['parameter'];
			$data['config_smtp_hostname'] = $config_mail['smtp_hostname'];
			$data['config_smtp_username'] = $config_mail['smtp_username'];
			$data['config_smtp_password'] = $config_mail['smtp_password'];
			$data['config_smtp_port'] = $config_mail['smtp_port'];
			$data['config_smtp_timeout'] = $config_mail['smtp_timeout'];
		} else {
			$data['config_mail_protocol'] = '';
			$data['config_mail_parameter'] = '';
			$data['config_smtp_hostname'] = '';
			$data['config_smtp_username'] = '';
			$data['config_smtp_password'] = '';
			$data['config_smtp_port'] = 25;
			$data['config_smtp_timeout'] = 5;
		}

		if (isset($this->request->post['config_mail_alert'])) {
			$data['config_mail_alert'] = $this->request->post['config_mail_alert'];
		}
                elseif (isset($store_info['config_mail_alert'])) {
			$data['config_mail_alert'] = $store_info['config_mail_alert'];
		}
                else {
			$data['config_mail_alert'] = $this->config->get('config_mail_alert');
		}

		if (isset($this->request->post['config_fraud_detection'])) {
			$data['config_fraud_detection'] = $this->request->post['config_fraud_detection'];
		}
                elseif (isset($store_info['config_fraud_detection'])) {
			$data['config_fraud_detection'] = $store_info['config_fraud_detection'];
		}
                else {
			$data['config_fraud_detection'] = $this->config->get('config_fraud_detection');
		}

		if (isset($this->request->post['config_fraud_key'])) {
			$data['config_fraud_key'] = $this->request->post['config_fraud_key'];
		}
                elseif (isset($store_info['config_fraud_key'])) {
			$data['config_fraud_key'] = $store_info['config_fraud_key'];
		}
                else {
			$data['config_fraud_key'] = $this->config->get('config_fraud_key');
		}

		if (isset($this->request->post['config_fraud_score'])) {
			$data['config_fraud_score'] = $this->request->post['config_fraud_score'];
		}
                elseif (isset($store_info['config_fraud_score'])) {
			$data['config_fraud_score'] = $store_info['config_fraud_score'];
		}
                else {
			$data['config_fraud_score'] = $this->config->get('config_fraud_score');
		}

		if (isset($this->request->post['config_fraud_status_id'])) {
			$data['config_fraud_status_id'] = $this->request->post['config_fraud_status_id'];
		}
                elseif (isset($store_info['config_fraud_status_id'])) {
			$data['config_fraud_status_id'] = $store_info['config_fraud_status_id'];
		}
                else {
			$data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');
		}

		if (isset($this->request->post['config_secure'])) {
			$data['config_secure'] = $this->request->post['config_secure'];
		}
                elseif (isset($store_info['config_secure'])) {
			$data['config_secure'] = $store_info['config_secure'];
		}
                else {
			$data['config_secure'] = $this->config->get('config_secure');
		}

		if (isset($this->request->post['config_shared'])) {
			$data['config_shared'] = $this->request->post['config_shared'];
		}
                elseif (isset($store_info['config_shared'])) {
			$data['config_shared'] = $store_info['config_shared'];
		}
                else {
			$data['config_shared'] = $this->config->get('config_shared');
		}

		if (isset($this->request->post['config_robots'])) {
			$data['config_robots'] = $this->request->post['config_robots'];
		}
                elseif (isset($store_info['config_robots'])) {
			$data['config_robots'] = $store_info['config_robots'];
		}
                else {
			$data['config_robots'] = $this->config->get('config_robots');
		}

		if (isset($this->request->post['config_seo_url'])) {
			$data['config_seo_url'] = $this->request->post['config_seo_url'];
		}
                elseif (isset($store_info['config_seo_url'])) {
			$data['config_seo_url'] = $store_info['config_seo_url'];
		}
                else {
			$data['config_seo_url'] = $this->config->get('config_seo_url');
		}

		if (isset($this->request->post['config_file_max_size'])) {
			$data['config_file_max_size'] = $this->request->post['config_file_max_size'];
		}
                elseif (isset($store_info['config_file_max_size'])) {
			$data['config_file_max_size'] = $store_info['config_file_max_size'];
		}
                elseif ($this->config->get('config_file_max_size')) {
			$data['config_file_max_size'] = $this->config->get('config_file_max_size');
		} else {
			$data['config_file_max_size'] = 300000;
		}

		if (isset($this->request->post['config_file_ext_allowed'])) {
			$data['config_file_ext_allowed'] = $this->request->post['config_file_ext_allowed'];
		}
                elseif (isset($store_info['config_file_ext_allowed'])) {
			$data['config_file_ext_allowed'] = $store_info['config_file_ext_allowed'];
		}
                else {
			$data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
		}

		if (isset($this->request->post['config_file_mime_allowed'])) {
			$data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
		}
                elseif (isset($store_info['config_file_mime_allowed'])) {
			$data['config_file_mime_allowed'] = $store_info['config_file_mime_allowed'];
		}
                else {
			$data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
		}

		if (isset($this->request->post['config_maintenance'])) {
			$data['config_maintenance'] = $this->request->post['config_maintenance'];
		}
                elseif (isset($store_info['config_maintenance'])) {
			$data['config_maintenance'] = $store_info['config_maintenance'];
		}
                else {
			$data['config_maintenance'] = $this->config->get('config_maintenance');
		}

		if (isset($this->request->post['config_password'])) {
			$data['config_password'] = $this->request->post['config_password'];
		}
                elseif (isset($store_info['config_password'])) {
			$data['config_password'] = $store_info['config_password'];
		}
                else {
			$data['config_password'] = $this->config->get('config_password');
		}

		if (isset($this->request->post['config_encryption'])) {
			$data['config_encryption'] = $this->request->post['config_encryption'];
		}
                elseif (isset($store_info['config_encryption'])) {
			$data['config_encryption'] = $store_info['config_encryption'];
		}
                else {
			$data['config_encryption'] = $this->config->get('config_encryption');
		}

		if (isset($this->request->post['config_compression'])) {
			$data['config_compression'] = $this->request->post['config_compression'];
		}
                elseif (isset($store_info['config_compression'])) {
			$data['config_compression'] = $store_info['config_compression'];
		}
                else {
			$data['config_compression'] = $this->config->get('config_compression');
		}

		if (isset($this->request->post['config_error_display'])) {
			$data['config_error_display'] = $this->request->post['config_error_display'];
		}
                elseif (isset($store_info['config_error_display'])) {
			$data['config_error_display'] = $store_info['config_error_display'];
		}
                else {
			$data['config_error_display'] = $this->config->get('config_error_display');
		}

		if (isset($this->request->post['config_error_log'])) {
			$data['config_error_log'] = $this->request->post['config_error_log'];
		}
                elseif (isset($store_info['config_error_log'])) {
			$data['config_error_log'] = $store_info['config_error_log'];
		}
                else {
			$data['config_error_log'] = $this->config->get('config_error_log');
		}

		if (isset($this->request->post['config_error_filename'])) {
			$data['config_error_filename'] = $this->request->post['config_error_filename'];
		}
                elseif (isset($store_info['config_error_filename'])) {
			$data['config_error_filename'] = $store_info['config_error_filename'];
		}
                else {
			$data['config_error_filename'] = $this->config->get('config_error_filename');
		}

		if (isset($this->request->post['config_google_analytics'])) {
			$data['config_google_analytics'] = $this->request->post['config_google_analytics'];
		}
                elseif (isset($store_info['config_google_analytics'])) {
			$data['config_google_analytics'] = $store_info['config_google_analytics'];
		}
                else {
			$data['config_google_analytics'] = $this->config->get('config_google_analytics');
		}

//add new
 $this->load->model('user/user_group');
                $data['user_groups'] = $this->model_user_user_group->getUserGroups();
                
                if (isset($this->request->post['pos_user_group_id'])) {
			$data['pos_user_group_id'] = $this->request->post['pos_user_group_id']; 
		}
                elseif (isset($store_info['pos_user_group_id'])) {
			$data['pos_user_group_id'] = $store_info['pos_user_group_id'];
		}
                else {
			$data['pos_user_group_id'] = $this->config->get('pos_user_group_id');
		} 

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/setting.tpl', $data));
	}
}