<?php
date_default_timezone_set('Asia/Kolkata');
	//require_once(DIR_SYSTEM .'/library/mpdf/mpdf.php');
	class ControllerCardCardPin extends Controller {

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


		public function index() {
			
			$this->document->setTitle('Pin Retrieval for Card');
			$data['heading_title'] = 'Pin Retrieval for Card';
			
			$data['button_filter'] = $this->language->get('button_filter');
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Pin Retrieval for Card',
				'href' => $this->url->link('card/card_pin', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			//$data['header'] = $this->load->controller('common/header');
			//$data['column_left'] = $this->load->controller('common/column_left');
			//$data['footer'] = $this->load->controller('common/footer');
			//$this->response->setOutput($this->load->view('card/card_pin_generate.tpl', $data));

			 if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/card/card_pin_generate.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/card/card_pin_generate.tpl', $data));
			}
			
		}
                public function generate_pin()
                {
                    $Card_Serial_Number=$this->request->get['Card_Serial_Number'];
                    $grower_id=$this->request->get['grower_id'];
                    $data=array('Card_Serial_Number'=>$Card_Serial_Number,'grower_id'=>$grower_id);
                    
                    $this->adminmodel('card/card');
                    $res=$this->model_card_card->check_grower_details($data);
                    if($res)
                    {
                        if($res['CARD_SERIAL_NUMBER']==$Card_Serial_Number)
                        {
                        //print_r($res);
	          $data['MOB']=$res['MOB'];
                        if($res['CARD_STATUS']==8)
                        {
                          
                          print_r($data);
                          $res=$this->model_card_card->generate_pin($data); 

                          echo $res;
                          return;
                          
                        }
                        else if($res['CARD_STATUS']==9)
                        {
                           //echo 'Card is already Activated for this Grower ID'; 
                            $res=$this->model_card_card->generate_pin($data); 

                            echo $res;
                            return;
                        }
                        else
                        {
                            echo 'Card is not Active';
                            return;
                        }
                        }
                        else
                        {
                            echo 'Please check Card Serial Number';
                            return;
                        }
                    }
                    else 
                    {
                         echo 'Card Serial Number does not Exist';
                         return;
                    }
                    
                }
                
                public function change_pin() {
			
			$this->document->setTitle('Pin Change for Card');
			$data['heading_title'] = 'Pin Change for Card';
			
			$data['button_filter'] = $this->language->get('button_filter');
			$data['button_clear'] = $this->language->get('button_clear');
			
			$data['token'] = $this->session->data['token'];
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Pin Change for Card',
				'href' => $this->url->link('card/card_pin/change_pin', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			//$data['header'] = $this->load->controller('common/header');
			$data['generate_pin_link']=$this->url->link('card/card_pin', 'token=' . $this->session->data['token'] . $url, true);
			
			//$data['column_left'] = $this->load->controller('common/column_left');
			//$data['footer'] = $this->load->controller('common/footer');
			//$this->response->setOutput($this->load->view('card/card_pin_change.tpl', $data));

			 if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/product.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/card/card_pin_change.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/card/card_pin_change.tpl', $data));
			}
			
		}
        public function send_otp()
                {
                    $Card_Serial_Number=$this->request->get['Card_Serial_Number'];
                    $grower_id=$this->request->get['grower_id'];
                    $data=array('Card_Serial_Number'=>$Card_Serial_Number,'grower_id'=>$grower_id);
                    
                    $this->adminmodel('card/card');
                    $res=$this->model_card_card->check_grower_details($data);
                    if($res)
                    {
                        if($res['CARD_SERIAL_NUMBER']==$Card_Serial_Number)
                        {
                        //print_r($res);
                        if($res['CARD_STATUS']==9)
                        {
                          $data['MOB']=$res['MOB'];
                          //print_r($data);
                          $res=$this->model_card_card->send_otp($data); 

                          echo $res;
                          
                          
                        }
                        else if($res['CARD_STATUS']==8)
                        {
                           echo 'Card is not Activated for this Grower ID'; 
                        }
                        }
                        else
                        {
                            echo 'Please check Card Serial Number';
                        }
                    }
                    else 
                    {
                         echo 'Grower ID does not Exist';
                    }
                    
                }
        public function change_pin_function()
        {
                    
                    $Card_Serial_Number=$this->request->get['Card_Serial_Number'];
                    $grower_id=$this->request->get['grower_id'];
                    $old_pin=$this->request->get['old_pin'];
                    $otp=$this->request->get['otp'];
                    $pin_or_otp=$this->request->get['pin_or_otp'];
                    $new_pin=$this->request->get['new_pin'];
                    
                    if(($pin_or_otp=='pin') && ($old_pin==""))
                    {
                        echo "Please enter old Pin";
                        return;
                    }
                    
                    if(($pin_or_otp=='otp') && ($otp==""))
                    {
                        echo "Please enter OTP";
                        return;
                    }
                    
                    $data=array(
                        'Card_Serial_Number'=>$Card_Serial_Number,
                        'grower_id'=>$grower_id,
                        'old_pin'=>$old_pin,
                        'otp'=>$otp,
                        'pin_or_otp'=>$pin_or_otp,
                        'new_pin'=>$new_pin
                        );
                    
                    $this->adminmodel('card/card');
                    $res=$this->model_card_card->check_grower_details($data);
                    if($res)
                    {
                        if($res['CARD_PIN']!=$old_pin)
                        {
                           echo 'Please check old Pin';
                           return; 
                        }
                        if($new_pin==$old_pin)
                        {
                           echo 'Old Pin and New Pin can not be same';
                           return; 
                        }
                        if($res['CARD_SERIAL_NUMBER']==$Card_Serial_Number)
                        {
                        //print_r($res);
	          $data['MOB']=$res['MOB'];
                        if($res['CARD_STATUS']==9)
                        {
                          
                          //print_r($data);
                          //////now check old pin or otp///////////
                          if($pin_or_otp=='pin')
                          {
                            $pin_data=$this->model_card_card->check_pin($data); 
                            if($pin_data>0)
                            {
                                
                                echo $res=$this->model_card_card->change_pin($data);
                                //echo "1";
                                return;
                            }
                            else 
                            {
                               echo 'Please check old Pin';
                               return;
                            }
                          }
                          if($pin_or_otp=='otp')
                          {
                            $otp_data=$this->model_card_card->check_otp($data); 
                            if(count($otp_data)>0)
                            {
                              $otp_sent_time=$otp_data['cr_date'];
                              $start_date = new DateTime($otp_sent_time);
                              $current_time=date('Y-m-d H:i:s');
                              $since_start = $start_date->diff(new DateTime($current_time));
                             
                                $minutes = $since_start->days * 24 * 60;
                                $minutes += $since_start->h * 60;
                                $minutes += $since_start->i;
                                //echo $minutes;
                                if($minutes>15)
                                {
                                    echo "Your otp is expired";
                                    return;
                                }
                                else 
                                {
                                    echo $res=$this->model_card_card->change_pin($data); 
                                    //echo "1";
                                    return;
                                }
                              
                            }
                            else 
                            {
                               echo 'Please check OTP';
                               return; 
                            }
                          }
                          
                          echo $res;
                          return;
                          
                        }
                        else if($res['CARD_STATUS']==8)
                        {
                           echo 'Card is not active for this Grower ID'; 
                           return;
                        }
                        }
                        else
                        {
                            echo 'Please check Card Serial Number';
                            return;
                        }
                    }
                    else 
                    {
                         echo 'Card Serial Number does not Exist';
                         return;
                    }
                    
                }		
	}
?>