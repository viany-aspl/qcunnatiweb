<?php

class ControllermposChain extends Controller {
    
    private $debugIt = false;
   
    public function adminmodel($model) 
	{
      
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
   public function index()
   {
		$mcrypt=new MCrypt();
                           
        $log=new Log("block-chain-".date('Y-m-d').".log");
		$log->write($this->request->get);
                            
		if (isset($this->request->post['text'])) 
		{
			$this->request->post['text'] = $this->request->post['text']; 		
		}
        else if (isset($this->request->get['text'])) 
		{
			$this->request->post['text'] = $this->request->get['text']; 		
		}
        else 
		{
			$this->request->post['text'] = json_encode(array('this is','test array'));
		}
		if (isset($this->request->post['key'])) 
		{
			$this->request->post['key'] = $this->request->post['key']; 		
		}
        else if (isset($this->request->get['key'])) 
		{
			$this->request->post['key'] = $this->request->get['key']; 		
		}
        else 
		{
			$this->request->post['key'] = strtotime(date('Y-m-d h:i:s'));
		}
		if (isset($this->request->post['from'])) 
		{
			$this->request->post['from'] = $this->request->post['from']; 		
		}
        else if (isset($this->request->get['from'])) 
		{
			$this->request->post['from'] = $this->request->get['from']; 		
		}
        else 
		{
			$this->request->post['from'] = '15KC8az1uJQCtCCSRdTPtwLZadSUY4UYwLQ63n';
		}
		if (isset($this->request->post['name'])) 
		{
			$this->request->post['name'] = $this->request->post['name']; 		
		}
        else if (isset($this->request->get['name'])) 
		{
			$this->request->post['name'] = $this->request->get['name']; 		
		}
        else 
		{
			$this->request->post['name'] = 'Unnati';
		}
		if (isset($this->request->post['publish'])) 
		{
			$this->request->post['publish'] = $this->request->post['publish']; 		
		}
        else if (isset($this->request->get['publish'])) 
		{
			$this->request->post['publish'] = $this->request->get['publish']; 		
		}
        else 
		{
			$this->request->post['publish'] = '1';
		}
		
		$log->write($this->request->post);
		$log->write($this->request->get);
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"http://test.willowood.akshapp.com/?chain=default&page=publish");
		curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($this->request->post));

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
		$log->write($server_output);
		curl_close ($ch);

		// further processing ....
		Print_r($server_output);
		
   }
   public function getdata()
   {
		$mcrypt=new MCrypt();
                           
        $log=new Log("block-chain-".date('Y-m-d').".log");
		$log->write($this->request->get);
        $this->request->post['getdata']='';                    
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"http://test.willowood.akshapp.com/?chain=default&page=view&stream=32cbd92125cff7d36f7404f7f97a888a8d40447fda7a46ce7f2abde9181b04e1&getdata=");
		curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($this->request->post));

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
		$log->write($server_output);
		curl_close ($ch);

		// further processing ....
		Print_r($server_output);
		
   }

}
