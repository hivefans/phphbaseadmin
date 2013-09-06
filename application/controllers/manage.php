<?php

class Manage extends CI_Controller
{
	 public function __construct()
    {
        parent::__construct();
       
    }
	
	public function Index()
	{
	    $this->lang->load('commons');		
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
        $this->load->library('session');
        if($this->session->userdata('is_logged_in')){
			redirect('tables');
        }else{
        	$this->load->view('manage/login',$data);	
        }
	}
    
     function __encrip_password($password) {
        return md5($password);
    }
     /**
    * check the username and the password with the database
    * @return void
    */
    
    
	function validate_user()
	{	

		$this->load->model('users_model');
		$user_name = $this->input->post('user_name');
		$password = $this->__encrip_password($this->input->post('password'));

		$is_valid = $this->users_model->validate($user_name, $password);
		
		if(count($is_valid)>0)
		{
			$data = array(
				'user_name' => $user_name,
				'is_logged_in' => true,
                'group'=>$is_valid[0]["group"],
                'grant'=>$is_valid[0]["grant"]
			);
			$this->session->set_userdata($data);
			redirect('tables');
		}
		else // incorrect username or password
		{
		    $this->lang->load('commons');		
		    $data['common_lang_set'] = $this->lang->line('common_lang_set');
			$data['message_error'] = TRUE;
			$this->load->view('manage/login', $data);	
		}
	}	
    
    /**
    * Destroy the session, and logout the user.
    * @return void
    */		
	function logout()
	{
		$this->session->sess_destroy(); 
        redirect('/');		
		
	}
    
    function userinfo()
    {
      $this->load->model('hbase_table_model','table');
      $this->table->headnav();  
      $this->load->model('users_model');
      $data['users'] = $this->users_model->get_users();  
      $this->load->view('manage/userinfo',$data);  
      $this->load->view('footer');
    }
    
     function groupinfo()
    {
      $this->load->model('hbase_table_model','table');
      $this->table->headnav();  
      $this->load->model('users_model');
      $data['groups'] = $this->users_model->get_usergroup();  
      $this->load->view('manage/groupinfo',$data);  
      $this->load->view('footer');
    }
    
    function adduser()
    {
      $this->load->model('hbase_table_model','table');
      $this->table->headnav();
      $tables=$this->table->get_table_names();
      $result='';
       foreach($tables as $key=>$tablename)
       {
          $result.='{id:"'.($key).'",name:"'.$tablename.'"},';
       }
       $data['result']=$result;
      $this->load->model('users_model');  
      $data['usergroup'] = $this->users_model->get_usergroup();       
      
      if ($this->input->server('REQUEST_METHOD') === 'POST')
        {           
           $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
           $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
           $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]'); 
           $this->form_validation->set_rules('grant', 'Grant', 'trim|required');
           $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
           if ($this->form_validation->run())
            {
                if($this->users_model->create_member()){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }
            }
        }
      $this->load->view('manage/adduser',$data);  
      $this->load->view('footer');  
       
    }
    
    function edit($id)
    {
      $this->load->model('hbase_table_model','table');
      $this->table->headnav();
      $tables=$this->table->get_table_names();
      $result='';
       foreach($tables as $key=>$tablename)
       {
          $result.='{id:"'.($key).'",name:"'.$tablename.'"},';
       }
       $data['result']=$result;
      $this->load->model('users_model');  
      $data['usergroup'] = $this->users_model->get_usergroup();
      $data['userinfo']=$this->users_model->get_users($id); 
       if ($this->input->server('REQUEST_METHOD') === 'POST')
        {           
           $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
           $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');            
           $this->form_validation->set_rules('grant', 'Grant', 'trim|required');
           $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
           if ($this->form_validation->run())
            {
                if($this->users_model->update_member($id)){
                    $data['flash_message'] = TRUE;                   
                }else{
                    $data['flash_message'] = FALSE; 
                }
            }
        }
      
      $this->load->view('manage/edituser',$data);  
      $this->load->view('footer');
    }
    
    function groupedit($id)
    {
       $this->load->model('hbase_table_model','table');
       $this->table->headnav(); 
       $this->load->model('users_model'); 
       $data['groupname']=  $this->users_model->get_usergroup($id);
       $data['user_name']=$this->session->userdata('user_name');
        if ($this->input->server('REQUEST_METHOD') === 'POST')
          {           
           $this->form_validation->set_rules('groupname', 'groupname', 'trim|required|min_length[4]');
           $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
          } 
        if ($this->form_validation->run())
          {
             if($this->users_model->update_group($id)){
                $data['flash_message'] = TRUE;                   
              }else{
                $data['flash_message'] = FALSE; 
              }
          }
       
       $this->load->view('manage/editgroup',$data);  
       $this->load->view('footer');
    }
    
    function addgroup()
    {
       $this->load->model('hbase_table_model','table');
       $this->table->headnav(); 
       $this->load->model('users_model'); 
       $data['user_name']=$this->session->userdata('user_name');
        if ($this->input->server('REQUEST_METHOD') === 'POST')
          {           
           $this->form_validation->set_rules('groupname', 'groupname', 'trim|required|min_length[4]');
           $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
          } 
        if ($this->form_validation->run())
          {
             if($this->users_model->create_group()){
                $data['flash_message'] = TRUE;                   
              }else{
                $data['flash_message'] = FALSE; 
              }
          }  
       $this->load->view('manage/addgroup',$data);  
       $this->load->view('footer');
    }
    
    
    function delete($id)
    {
       $this->load->model('users_model'); 
       $this->users_model->delete_user($id); 
       echo "<script>alert('delete user success');</script>";
        redirect('manage/userinfo');        
    }
    
     function deletegroup($id)
    {
       $this->load->model('users_model'); 
       $this->users_model->delete_group($id); 
       echo "<script>alert('delete group success');</script>";
        redirect('manage/groupinfo');        
    }
    
    
    
    
}